<?php
namespace Home\Service;
class AdService extends Service{
	
	
	protected function getPluginDir(){
		$dir = 'Upclient/Plugin/';
		if(!is_dir($dir)){
			mkdir($dir,true);
		
		}
		chmod($dir, 0777);
		return $dir;
	}
	
	protected function getMeidaDir(){
		$month = date('Ym');
		$dir = 'Upload/Media/'.$month.'/';
		if(!is_dir($dir)){
			$old = umask(0);
			mkdir($dir,0777,true);
			umask($old);
		}
		return $dir;
	}
	
	public  function uploadPlugin(){
		$type = $_REQUEST['type'];
		
		$config = array(
				'mimes'         =>  array(), //允许上传的文件MiMe类型
				'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
				'exts'          =>  array(), //允许上传的文件后缀
				'rootPath'      =>  ROOTPATH, //保存根路径
				'hash'          =>  true, //是否生成hash编码
				'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
		);
		$this->config = $config;
		$savePath = substr($this->getPluginDir(), 0,-1);
		header("Access-Control-Allow-Origin: *");
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: no-store, no-cache, must-revalidate" );
		header ( "Cache-Control: post-check=0, pre-check=0", false );
		header ( "Pragma: no-cache" );
		$targetDir = $this->config ['rootPath'] . $savePath;
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		
		// 5 minutes execution time
		@set_time_limit ( 5 * 60 );
		
		// Uncomment this one to fake upload time
		// usleep(5000);
		
		// Get parameters
		$chunk = isset ( $_REQUEST ["chunk"] ) ? intval ( $_REQUEST ["chunk"] ) : 0;
		$chunks = isset ( $_REQUEST ["chunks"] ) ? intval ( $_REQUEST ["chunks"] ) : 0;
		$fileName = isset ( $_REQUEST ["name"] ) ? $_REQUEST ["name"] : '';
		
		//指定文件的后缀名 王坚上传视频监控用
		if(isset($_REQUEST['extt'])){
			$pinfo = pathinfo($fileName);
			$fileName = str_replace('.'.$pinfo['extension'], '.'.$_REQUEST['extt'], $fileName);
		}
		
		// Clean the fileName for security reasons
		$ext =  strrpos ( $fileName, '.' );
		if (! $this->checkExt ( $this->getExt ( $fileName ) )) {
			die ( '{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "非法的文件名!"}, "id" : "id"}' );
		}
		$fileName = urlencode ( substr ( $fileName, 0, $ext ) ) . substr ( $fileName, $ext );
		// $fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
		
		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists ( $targetDir . DIRECTORY_SEPARATOR . $fileName )) {
			$fileName_a = substr ( $fileName, 0, $ext );
			$fileName_b = substr ( $fileName, $ext );
				
			$count = 1;
			while ( file_exists ( $targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b ) )
				$count ++;
				
			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}
		
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		
		// Create target dir
		/*
		 * if (!file_exists($targetDir)) @mkdir($targetDir);
		*/
		// import("Org.Io.Files");
		/*
		 * if(!Files::checkDirs($targetDir)) { die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}'); }
		*/
		// Remove old temp files
		if ($cleanupTargetDir) {
			if (is_dir ( $targetDir ) && ($dir = opendir ( $targetDir ))) {
				while ( ($file = readdir ( $dir )) !== false ) {
					$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
						
					// Remove temp file if it is older than the max age and is not the current file
					if (preg_match ( '/\.part$/', $file ) && (filemtime ( $tmpfilePath ) < time () - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
						@unlink ( $tmpfilePath );
					}
				}
				closedir ( $dir );
			} else {
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}' );
			}
		}
		
		// Look for the content type header
		if (isset ( $_SERVER ["HTTP_CONTENT_TYPE"] ))
			$contentType = $_SERVER ["HTTP_CONTENT_TYPE"];
		
		if (isset ( $_SERVER ["CONTENT_TYPE"] ))
			$contentType = $_SERVER ["CONTENT_TYPE"];
			
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos ( $contentType, "multipart" ) !== false) {
			if (isset ( $_FILES ['file'] ['tmp_name'] ) && is_uploaded_file ( $_FILES ['file'] ['tmp_name'] )) {
				$fileMd5 = md5_file ( $_FILES ['file'] ['tmp_name'] );
				$fileSha1 = sha1_file ( $_FILES ['file'] ['tmp_name'] );
				// Open temp file
				$out = @fopen ( "{$filePath}.part", $chunk == 0 ? "wb" : "ab" );
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = @fopen ( $_FILES ['file'] ['tmp_name'], "rb" );
						
					if ($in) {
						while ( $buff = fread ( $in, 4096 ) ) {
							$_SESSION ['_size'] += strlen ( $buff );
							fwrite ( $out, $buff );
						}
					} else
						die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
					@fclose ( $in );
					@fclose ( $out );
					@unlink ( $_FILES ['file'] ['tmp_name'] );
				} else
					die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
			} else
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}' );
		} else {
			// Open temp file
			$out = @fopen ( "{$filePath}.part", $chunk == 0 ? "wb" : "ab" );
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = @fopen ( "php://input", "rb" );
		
				if ($in) {
					while ( $buff = fread ( $in, 4096 ) ) {
						$_SESSION ['_size'] += strlen ( $buff );
						fwrite ( $out, $buff );
					}
				} else
					die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
		
				@fclose ( $in );
				@fclose ( $out );
			} else
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
		}
		
		
		
		
		// Check if file has been uploaded
		if (! $chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off
			rename ( "{$filePath}.part", $filePath );
			$fileId = 0;
			if ($this->config ['autoSave']) {
				/* 调用回调函数检测文件是否存在 */
				$data = call_user_func ( $this->config ['callback'], array (
						'md5' => $fileMd5,
						'sha1' => $fileSha1
				) );
				if ($this->config ['callback'] && $data) {
					if (file_exists ( $this->config ['rootPath'] . $savePath . '/' . $fileName )) {
						die ( '{"jsonrpc" : "2.0", "result" : "' . $data ['sourceFile'] . '", "id" : "id", "fileId" : "' . $data ['id'] . '", "status" : "1"}' );
					} elseif ($this->config ['removeTrash']) {
						call_user_func ( $this->config ['removeTrash'], $data ); // 删除垃圾据
					}
				}
			}
			$zipfile = ROOTPATH . $savePath . '/' . $fileName;
			$normd5 = md5_file($zipfile);
			$md5 = str_replace(array("\r\n","\r","\n"), '', file_get_contents($md5file));
			//ftp上传到软件更新服务器
			$tar = '/webroot/Upload/Plugin/';
			$add = 'Plugin';
			
			die ( '{"jsonrpc" : "2.0","md5":"'.$md5.'","type":"'.$type.'","result" : "' . C('CLIENT_DOWNLOAD_CDN') . $add . '/' . $fileName . '", "id" : "id", "fileId" : "' . $fileId . '", "normd5":"'.$normd5.'","status" : "1"}' );
		} else {
			$zipfile = ROOTPATH . $savePath . '/' . $fileName;
			$normd5 = md5_file($zipfile);
			$tar = '/webroot/Upload/Plugin/';
			$add = 'Plugin';
			
			die ( '{"jsonrpc" : "2.0","md5":"'.$md5.'","type":"'.$type.'", "result" : "' . C('CLIENT_DOWNLOAD_CDN') . $add . '/' . $fileName . '", "id" : "id", "normd5":"'.$normd5.'"}' );
		}
		
	}
	
	
	public  function uploadMedia(){
		$type = $_REQUEST['type'];
	
		$config = array(
				'mimes'         =>  array(), //允许上传的文件MiMe类型
				'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
				'exts'          =>  array(), //允许上传的文件后缀
				'rootPath'      =>  ROOTPATH, //保存根路径
				'hash'          =>  true, //是否生成hash编码
				'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
		);
		$this->config = $config;
		$savePath = substr($this->getMeidaDir(), 0,-1);
		header("Access-Control-Allow-Origin: *");
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: no-store, no-cache, must-revalidate" );
		header ( "Cache-Control: post-check=0, pre-check=0", false );
		header ( "Pragma: no-cache" );
		$targetDir = $this->config ['rootPath'] . $savePath;
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
	
		// 5 minutes execution time
		@set_time_limit ( 5 * 60 );
	
		// Uncomment this one to fake upload time
		// usleep(5000);
	
		// Get parameters
		$chunk = isset ( $_REQUEST ["chunk"] ) ? intval ( $_REQUEST ["chunk"] ) : 0;
		$chunks = isset ( $_REQUEST ["chunks"] ) ? intval ( $_REQUEST ["chunks"] ) : 0;
		$fileName = isset ( $_REQUEST ["name"] ) ? $_REQUEST ["name"] : '';
	
		//指定文件的后缀名 王坚上传视频监控用
		if(isset($_REQUEST['extt'])){
			$pinfo = pathinfo($fileName);
			$fileName = str_replace('.'.$pinfo['extension'], '.'.$_REQUEST['extt'], $fileName);
		}
	
		// Clean the fileName for security reasons
		$ext =  strrpos ( $fileName, '.' );
		if (! $this->checkExt ( $this->getExt ( $fileName ) )) {
			die ( '{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "非法的文件名!"}, "id" : "id"}' );
		}
		$fileName = urlencode ( substr ( $fileName, 0, $ext ) ) . substr ( $fileName, $ext );
		// $fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
	
		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists ( $targetDir . DIRECTORY_SEPARATOR . $fileName )) {
			$fileName_a = substr ( $fileName, 0, $ext );
			$fileName_b = substr ( $fileName, $ext );
	
			$count = 1;
			while ( file_exists ( $targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b ) )
				$count ++;
	
			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}
	
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
	
		// Create target dir
		/*
		 * if (!file_exists($targetDir)) @mkdir($targetDir);
		*/
		// import("Org.Io.Files");
		/*
		 * if(!Files::checkDirs($targetDir)) { die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}'); }
		*/
		// Remove old temp files
		if ($cleanupTargetDir) {
			if (is_dir ( $targetDir ) && ($dir = opendir ( $targetDir ))) {
				while ( ($file = readdir ( $dir )) !== false ) {
					$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
	
					// Remove temp file if it is older than the max age and is not the current file
					if (preg_match ( '/\.part$/', $file ) && (filemtime ( $tmpfilePath ) < time () - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
						@unlink ( $tmpfilePath );
					}
				}
				closedir ( $dir );
			} else {
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}' );
			}
		}
	
		// Look for the content type header
		if (isset ( $_SERVER ["HTTP_CONTENT_TYPE"] ))
			$contentType = $_SERVER ["HTTP_CONTENT_TYPE"];
	
		if (isset ( $_SERVER ["CONTENT_TYPE"] ))
			$contentType = $_SERVER ["CONTENT_TYPE"];
			
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos ( $contentType, "multipart" ) !== false) {
			if (isset ( $_FILES ['file'] ['tmp_name'] ) && is_uploaded_file ( $_FILES ['file'] ['tmp_name'] )) {
				$fileMd5 = md5_file ( $_FILES ['file'] ['tmp_name'] );
				$fileSha1 = sha1_file ( $_FILES ['file'] ['tmp_name'] );
				// Open temp file
				$out = @fopen ( "{$filePath}.part", $chunk == 0 ? "wb" : "ab" );
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = @fopen ( $_FILES ['file'] ['tmp_name'], "rb" );
	
					if ($in) {
						while ( $buff = fread ( $in, 4096 ) ) {
							$_SESSION ['_size'] += strlen ( $buff );
							fwrite ( $out, $buff );
						}
					} else
						die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
					@fclose ( $in );
					@fclose ( $out );
					@unlink ( $_FILES ['file'] ['tmp_name'] );
				} else
					die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
			} else
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}' );
		} else {
			// Open temp file
			$out = @fopen ( "{$filePath}.part", $chunk == 0 ? "wb" : "ab" );
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = @fopen ( "php://input", "rb" );
	
				if ($in) {
					while ( $buff = fread ( $in, 4096 ) ) {
						$_SESSION ['_size'] += strlen ( $buff );
						fwrite ( $out, $buff );
					}
				} else
					die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
	
				@fclose ( $in );
				@fclose ( $out );
			} else
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
		}
	
	
	
	
		// Check if file has been uploaded
		if (! $chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off
			rename ( "{$filePath}.part", $filePath );
			$fileId = 0;
			if ($this->config ['autoSave']) {
				/* 调用回调函数检测文件是否存在 */
				$data = call_user_func ( $this->config ['callback'], array (
						'md5' => $fileMd5,
						'sha1' => $fileSha1
				) );
				if ($this->config ['callback'] && $data) {
					if (file_exists ( $this->config ['rootPath'] . $savePath . '/' . $fileName )) {
						die ( '{"jsonrpc" : "2.0", "result" : "' . $data ['sourceFile'] . '", "id" : "id", "fileId" : "' . $data ['id'] . '", "status" : "1"}' );
					} elseif ($this->config ['removeTrash']) {
						call_user_func ( $this->config ['removeTrash'], $data ); // 删除垃圾据
					}
				}
			}
			//ftp上传到软件更新服务器
			if($_SERVER['HTTP_HOST']=='netbar.com'){
				$u = 'http://netbar.com/';
			}else{
				$u =  'http://uploadimg.110route.com/';
			}
			$data = array();
			$size = getimagesize($savePath.'/'.$fileName);
			$bits = filesize($savePath.'/'.$fileName);
			$md5 = md5_file($savePath.'/'.$fileName);
			$data['type'] = $size['mime'];
			$data['size'] = $size['0'].','.$size[1];
			$data['bits'] = $bits;
			$data['md5'] = $md5;
			$data['addtime'] = date('Y-m-d H:i:s');
			$data['file'] =  $u  . $savePath .'/'. $fileName ;
			$rs = D('AdMaterial')->add($data);
			//dump($rs);
			//echo(D('AdMaterial')->_sql());
			die ( '{"jsonrpc" : "2.0","width":"'.$size['0'].'","height":"'.$size['1'].'","bits":"'.$size["bits"].'","mime":"'.$size["mime"].'","type":"'.$type.'","result" : "' . $u  . $savePath .'/'. $fileName . '", "id" : "id", "fileId" : "' . $fileId . '", "status" : "1"}' );
		} else {
		
			if($_SERVER['HTTP_HOST']=='netbar.com/'){
				$u = 'http://netbar.com/';
			}else{
				$u =  'http://uploadimg.110route.com/';
			}
			
			$data = array();
			$size = getimagesize($savePath.'/'.$fileName);
			$bits = filesize($savePath.'/'.$fileName);
			$md5 = md5_file($savePath.'/'.$fileName);
			$data['type'] = $size['mime'];
			$data['size'] = $size['0'].'*'.$size[1];
			$data['bits'] = $bits;
			$data['md5'] = $md5;
			$data['addtime'] = date('Y-m-d H:i:s');
			$data['file'] =  $u  . $savePath .'/'. $fileName ;
			D('AdMaterial')->add($data);
			//dump($rs);
			//echo(D('AdMaterial')->_sql());
			die ( '{"jsonrpc" : "2.0","width":"'.$size['0'].'","height":"'.$size['1'].'","bits":"'.$size["bits"].'","mime":"'.$size["mime"].'","type":"'.$type.'","result" : "' . $u  . $savePath .'/'. $fileName . '", "id" : "id", "fileId" : "' . $fileId . '", "status" : "1"}' );
		}
	
	}
	

	/**
	 * 检查上传的文件后缀是否合法
	 * @param string $ext 后缀
	 */
	private function checkExt($ext) {
		return true;
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