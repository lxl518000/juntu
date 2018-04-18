<?php
namespace Org\Net;
use Org\Io\Files;
/**
 * PLUpoad工具类
 *
 * @version $Id: PLUpload.class.php 6 2016-01-12 01:35:23Z wangjin $
 *
 */
class PLUpload
{
	/**
	 * 默认上传配置
	 * @param array
	 */
    private $config = array(
        'mimes'         =>  array(), //允许上传的文件MiMe类型
        'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  array(), //允许上传的文件后缀
    	'rootPath'      =>  '../upload/', //保存根路径
        'hash'          =>  true, //是否生成hash编码
        'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
    );
	
	public function __construct($config = array())
    {	
    	/* 获取配置 */
        $this->config   =   array_merge($this->config, $config);
        /* 调整配置，把字符串配置参数转换为数组 */
        if(!empty($this->config['exts'])){
            $this->exts = explode(',', $this->config['exts']);
            $this->exts = array_map('strtolower', $this->exts);
        }
    }
    
	public function upload($savePath)
	{
		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		$targetDir = $this->config['rootPath']. $savePath;
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds

		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Uncomment this one to fake upload time
		// usleep(5000);

		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

		// Clean the fileName for security reasons
		$ext = strrpos($fileName, '.');
		if(!$this->checkExt($this->getExt($fileName))) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "非法的文件名!"}, "id" : "id"}');
		}
		$fileName = urlencode(substr($fileName, 0, $ext)). substr($fileName, $ext);
		//$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);

		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);

			$count = 1;
			while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
			$count++;

			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        // Create target dir
//		if(!Files::checkDirs($targetDir))  {
//			die('{"jsonrpc" : "2.0", "error" : {"code": 10011, "message": "Failed to open temp directory."}, "id" : "id"}');
//		}

        if(!file_exists($targetDir))
        {
            @mkdir($targetDir, 0775, true);
            chmod($targetDir, 0775); // mkdir 创建文件夹权限不一定成功
        }
        
		// Remove old temp files
		if ($cleanupTargetDir) {
			if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
				while (($file = readdir($dir)) !== false) {
					$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

					// Remove temp file if it is older than the max age and is not the current file
					if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
						@unlink($tmpfilePath);
					}
				}
				closedir($dir);
			} else {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}
		}

		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
		$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

		if (isset($_SERVER["CONTENT_TYPE"]))
		$contentType = $_SERVER["CONTENT_TYPE"];

		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				$fileMd5 = md5_file($_FILES['file']['tmp_name']);
				$fileSha1 = sha1_file($_FILES['file']['tmp_name']);
				/* 调用回调函数检测文件是否存在 */
				if($this->config['autoSave']) {
					$data = call_user_func($this->config['callback'], array('md5' => $fileMd5, 'sha1' => $fileSha1));
					if( $this->config['callback'] && $data ){
						if ( file_exists($this->config['rootPath'].$data['sourceFile'])  ) {
							die ( '{"jsonrpc" : "2.0", "result" : "' . $data['sourceFile'] . '", "id" : "id", "fileId" : "'.$data['id'].'", "status" : "1"}' );
						}elseif($this->config['removeTrash']){
							call_user_func($this->config['removeTrash'],$data);//删除垃圾据
						}
					}
				}
				
				// Open temp file
				$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = @fopen($_FILES['file']['tmp_name'], "rb");

					if ($in) {
						while ( $buff = fread ( $in, 4096 ) ) {
							$_SESSION['_size'] += strlen($buff);
							fwrite ( $out, $buff );
						}
					} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					@fclose($in);
					@fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		} else {
			// Open temp file
			$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = @fopen("php://input", "rb");

				if ($in) {
					while ( $buff = fread ( $in, 4096 ) ) {
						$_SESSION['_size'] += strlen($buff);
						fwrite ( $out, $buff );
					}
				} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

				@fclose($in);
				@fclose($out);
			} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}

		// Check if file has been uploaded
		if (! $chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off
			rename ( "{$filePath}.part", $filePath );
            chmod($filePath, 0664);
			$fileId = 0;
			if($this->config['autoSave']) {					
				//文件保存到数据库
				$filesModel = new \Huaqin\Model\FilesModel();
                //var_dump($_REQUEST);
				$sourceName = !empty($_REQUEST['sourceName']) ? $_REQUEST['sourceName'] : $fileName;
				if (!is_login()) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Not logged in"}, "id" : "id"}');
				}
				
				$data = array (
						'name' => $sourceName,
						'size' => $_SESSION['_size'],
						'sourceFile' => $savePath . '/' . $fileName,
						'ext' => $this->getExt($sourceName),
						'md5' => $fileMd5,
						'sha1' => $fileSha1,
                        'status' => $this->config['status'],
						'addTime' => NOW_TIME,
						'userId' => get_uid()
				);
				$fileId = $filesModel->add($data);
				$_SESSION['_size'] = 0;
			}
			die ( '{"jsonrpc" : "2.0", "result" : "' . $savePath . '/' . $fileName . '", "id" : "id", "fileId" : "'.$fileId.'", "status" : "1"}' );
		} else {
			die ( '{"jsonrpc" : "2.0", "result" : "' . $savePath . '/' . $fileName.'", "id" : "id"}');
		}
	}
    
 	/**
     * 检查上传的文件后缀是否合法
     * @param string $ext 后缀
     */
    private function checkExt($ext) {
        return empty($this->config['exts']) ? true : in_array(strtolower($ext), $this->exts);
    }
    
	/**
     * 取得文件的后缀
     * 
     * @param string $filename 文件名
     * 
     * @return boolean
     */
    protected  function getExt($filename) {
        $pathinfo = pathinfo($filename);
        return strtolower($pathinfo['extension']);
    }
}