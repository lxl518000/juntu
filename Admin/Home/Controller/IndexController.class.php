<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BackendController {
	
	public function __construct(){
		parent::__construct();
	}

	public function test1(){
	    $list = D('products')->select();
        $en = [];
        $cn = [];
        foreach($list as $v){
            $tmp = [];
            $entmp = [];
            $tmp['name'] = $v['p_name'];
            $tmp['cid'] = $v['p_cid'];
            $tmp['pic'] = "/Uploads/2018-06-20/".$v['p_img'];

            $sear = ['http://juntu.pw/web/Tpl/Public/kindeditor-4.1.10/attached/image/','/web/Tpl/Public/kindeditor-4.1.10/attached/image/'];

            $tmp['content'] = str_replace($sear,'/Uploads/',$v['p_content']);
            $tmp['keyword'] = $v['p_name'];
            $tmp['description'] = $v['p_name'];
            $tmp['addtime'] = date('Y-m-d H:i:s',$v['p_time']);



            $entmp['name'] = $v['p_en_name'];
            $entmp['cid'] = $v['p_cid'];
            $entmp['pic'] = "/Uploads/2018-06-20/".$v['p_img'];
            $entmp['content'] = str_replace($sear,'/Uploads/',$v['p_en_content']);
            $entmp['keyword'] = $v['p_en_name'];
            $entmp['description'] = $v['p_en_name'];
            $entmp['addtime'] = date('Y-m-d H:i:s',$v['p_time']);

            $cn[] = $tmp;
            $en[] = $entmp;

        }
        $rs = D('product')->addAll($cn);
        dump($rs);
        $rs =  D('en_product')->addAll($en);
        dump($rs);
    }


	public function test(){

	    $list = D('cats')->where(['c_type'=>1])->select();

	    $en = [];
	    $cn = [];
	    foreach($list as $v){
            $tmp = [];
            $entmp = [];
            $tmp['pid'] = $v['c_pid'];
            $tmp['title'] = $v['c_name'];
            $tmp['addtime'] = date('Y-m-d H:i:s',$v['c_time']);
            $tmp['desc'] = $v['c_intro'];
            $tmp['keyword'] = $v['c_name'];


            $entmp['pid'] = $v['c_pid'];
            $entmp['title'] = $v['c_en_name'];
            $entmp['addtime'] = $tmp['addtime'];
            $entmp['desc'] = $v['c_en_intro'];
            $entmp['keyword'] = $v['c_en_name'];

            $cn[] = $tmp;
            $en[] = $entmp;

        }
       $rs = D('cate')->addAll($cn);
	    dump($rs);
	   $rs =  D('en_cate')->addAll($en);

	   dump($rs);
	    echo 1;
    }
	
	/**
	* 上传微信图片
	*/
	public function upweixin(){
		$upService = service("Upfile");
		$upService->normal();
	}
	

	
	
	
	public function getAreaChildren() {
		$area = p ( 'area' );
		$_RegionCodeList = gM ( "Region" )->where ( array (
				'ParentCode' => $area
		) )->select ();
		empty ( $_RegionCodeList ) && $_RegionCodeList = array ();
		$this->ajaxReturn ( $_RegionCodeList );
	}

	/**
	 * 个人中心
	 */
	public function my(){
		 $my = session('adminuser');
		 
		 if(!$my){
		 	$this->redirect(U('Public/login'));
		 }
		 
		 if(IS_POST){
		 	$m = D('Adminuser');
			$rs = $m->create($_POST);
		 	if(!$rs){
		 		$this->error($m->getError());
		 	}
		 	$rs = $m->where(array('id'=>$my['id']))->save($data);
		 	
		 	if($rs){
		 		$my['realname'] = I('realname');
		 		$my['mobile'] = I('mobile');
		 		$my['email'] = I('email');
		 		$my['nps'] = I('nps');
		 		session('adminuser',$my);
		 		$this->success('修改成功');
		 	}else{
		 		$this->error('修改失败');
		 	}
		 }
		 
		 
		$this->assign('list',$my);
		$this->display();
	}
	
	
	

	
	
	public function index(){
		$this->_checkLogin();// 检查登录
		$admin_menu = session('adminuser.admin_menu');
		$this->assign('admin_menu',$admin_menu);
		$this->assign('admin',session('adminuser'));
		$this->display();
	}
	
	public function main(){
		echo "<pre>";
		//print_r(session('adminuser.admin_menu'));
		echo "</pre>";
		$this->display();
	}
	
	public function upfile(){
		
		$service = service('Upfile');
		$service->normal();
	}
	
	
	/**
	 * 网吧自动完成
	 */
	public function autobar(){
		
		$params['method'] = 'Barinfo/autoBar';
		$params['query'] = I('query');
		$rs = getApi($params);
		$return = array();
		$return['suggestions'] = $rs['data'];
		echo json_encode($return,JSON_UNESCAPED_UNICODE);
	}
	

	
	


	public function server(){
		//检测访问
		if(empty($_GET['action'])) return false;
	
		//准备配置
		$config=$this->config;
		$action = $_GET['action'];
		$param=array(
				'water'=>intval($_GET['water']),
		);
	
		//事件处理
		switch ($action) {
			case 'config':
				$result =  json_encode($config);
				break;
				/* 上传图片 */
			case 'uploadimage':
				/* 上传涂鸦 */
			case 'uploadscrawl':
				/* 上传视频 */
			case 'uploadvideo':
				/* 上传文件 */
			case 'uploadfile':
	
				if($file=$this->uploadOne($param)){
					$file_data=array(
							"state" => "SUCCESS",
							"url" => $file['url'],
							"title" => $file['title'],
							"original" => $file['title'],
							"type" => $file['ext'],
							"size" => $file['size'],
					);
					$result=json_encode($file_data);
				}
				break;
	
				/* 列出图片 */
			case 'listimage':
				$config['imageManagerListPath'] = __ROOT__.'/Uploads/';
				$result = include(ROOTPATH."Public/Vendor/ueditor/php/action_list.php");
				break;
				/* 列出文件 */
			case 'listfile':
				$config['fileManagerListPath'] = __ROOT__.'/Uploads/file/';
				$result = include(ROOTPATH."Public/Vendor/ueditor/php/action_list.php");
				
				break;
	
				/* 抓取远程文件 */
			case 'catchimage':
				$config['fileManagerListPath']=__ROOT__.'/Uploads/catcher/{yyyy}{mm}{dd}/{time}{rand:6}';
				$result = include(ROOTPATH."Public/Vendor/ueditor/php/action_crawler.php");
				break;
	
			default:
				$result = json_encode(array(
				'state'=> '请求地址出错'
						));
						break;
		}
	
		/* 输出结果 */
		if (isset($_GET["callback"])) {
			if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
				echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
			} else {
				echo json_encode(array(
						'state'=> 'callback参数不合法'
				));
			}
		} else {
			echo $result;
		}
	}
	
	protected $config = array
	(
			'imageActionName' => 'uploadimage',
			'imageFieldName' => 'upfile',
			'imageMaxSize' => 2048000,
			'imageAllowFiles' => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
			'imageCompressEnable' => true,
			'imageCompressBorder' => 1600,
			'imageInsertAlign' => 'none',
			'imageUrlPrefix' => '',
			'imagePathFormat' => 'Upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
	
			'scrawlActionName' => 'uploadscrawl',
			'scrawlFieldName' => 'upfile',
			'scrawlPathFormat' => 'Upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
			'scrawlMaxSize' => 2048000,
			'scrawlUrlPrefix' => '',
			'scrawlInsertAlign' => 'none',
	
			'snapscreenActionName' => 'uploadimage',
			'snapscreenPathFormat' => 'Upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
			'snapscreenUrlPrefix' => '',
			'snapscreenInsertAlign' => 'none',
	
			'catcherLocalDomain' => array('127.0.0.1', 'localhost', 'img.baidu.com'),
			'catcherActionName' => 'catchimage',
			'catcherFieldName' => 'source',
			'catcherPathFormat' => 'Upload/catcher/{yyyy}{mm}{dd}/{time}{rand:6}',
			'catcherUrlPrefix' => '',
			'catcherMaxSize' => 2048000,
			'catcherAllowFiles' => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
	
			'videoActionName' => 'uploadvideo',
			'videoFieldName' => 'upfile',
			'videoPathFormat' => 'Upload/video/{yyyy}{mm}{dd}/{time}{rand:6}',
			'videoUrlPrefix' => '',
			'videoMaxSize' => 102400000,
			'videoAllowFiles' => array('.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid'),
	
			'fileActionName' => 'uploadfile',
			'fileFieldName' => 'upfile',
			'filePathFormat' => 'Upload/file/{yyyy}{mm}{dd}/{time}{rand:6}',
			'fileUrlPrefix' => '',
			'fileMaxSize' => 51200000,
			'fileAllowFiles' => array('.png','.jpg','.jpeg','.gif','.bmp','.flv','.swf','.mkv','.avi','.rm','.rmvb','.mpeg','.mpg','.ogg','.ogv','.mov','.wmv','.mp4','.webm','.mp3','.wav','.mid','.rar','.zip','.tar','.gz','.7z','.bz2','.cab','.iso','.doc','.docx','.xls','.xlsx','.ppt','.pptx','.pdf','.txt','.md','.xml'),
	
			'imageManagerActionName' => 'listimage',
			'imageManagerListPath' => 'Uploads/',
			'imageManagerListSize' => 20,
			'imageManagerUrlPrefix' => '',
			'imageManagerInsertAlign' => 'none',
			'imageManagerAllowFiles' => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
	
			'fileManagerActionName' => 'listfile',
			'fileManagerListPath' => 'Upload/file/',
			'fileManagerUrlPrefix' => '',
			'fileManagerListSize' => 20,
			'fileManagerAllowFiles' => array('.png','.jpg','.jpeg','.gif','.bmp','.flv','.swf','.mkv','.avi','.rm','.rmvb','.mpeg','.mpg','.ogg','.ogv','.mov','.wmv','.mp4','.webm','.mp3','.wav','.mid','.rar','.zip','.tar','.gz','.7z','.bz2','.cab','.iso','.doc','.docx','.xls','.xlsx','.ppt','.pptx','.pdf','.txt','.md','.xml'),
	);
	
	public function uploadOne($config=array()){

		$file_data=reset($_FILES);
		$ext_arr= array(
				'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
				'photo' => array('jpg', 'jpeg', 'png'),
				'flash' => array('swf', 'flv'),
				'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
				'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pdf')
		);
		
		//增加自定义条件
		$config['maxSize']=$this->allow_size;
		$config['exts']=$this->allow_ext;
	
		//上传服务器
			// 上传文件配置
		$config=array(
				//'maxSize'   =>  '',               // 上传文件最大为500M
				'rootPath'  =>  './',                   // 文件上传保存的根路径
				'savePath'  =>  './Uploads/',         // 文件上传的保存路径（相对于根路径）
				'saveName'  =>  array('uniqid',''),     // 上传文件的保存规则，支持数组和字符串方式定义
				'autoSub'   =>  true,                   // 自动使用子目录保存上传文件 默认为true
				'exts'      =>    isset($ext_arr[$format])?$ext_arr[$format]:'',
		);
	
		// 实例化上传
		$upload=new \Think\Upload($config);
	
		// 调用上传方法
		$file = $upload->upload();
			
		$info = $file['upfile'];
	
		$find = D('sys_file')->where(array('md5'=>$info['md5']))->find();
		if($find){
			$data['url']= $find["savepath"].$file['savename'];
			unlink($find['savepath'].$find['savename']);
			
			return $data;
		}
		
		
		// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
		$thumbname = '';
		if(in_array($info['ext'],$ext_arr['image'])){
			$image = new \Think\Image();
			$image->open($info['savepath'].$info['savename']);
			$thumbname = 'thumb240_'.$info['savename'];
			$image->thumb(240,240)->save($info['savepath'].$thumbname);
			$info['thumbname'] = $thumbname;
		}
		$info['savepath'] = ltrim($info['savepath'],'.');
		//添加文件
		$file = [];
		$file['name'] = $info['name'];
		$file['md5'] = $info['md5'];
		$file['savename'] = $info['savename'];
		$file['savepath'] = $info['savepath'];
		$file['size'] = $info['size'];
		$file['ext'] = $info['ext'];
		$file['thumbname'] = $thumbname;
		$file['minitype'] = $info['type'];
		$file['addtime'] = date('Y-m-d H:i:s');
		$file['adduser'] = getUser();
		try{
			D('sys_file')->add($file);
		}catch (\Exception $e){
				
		}
		$data['url']= $info['savepath'].$info['savename'];
		//缓存新增附件信息
	
		return $data;
	}
	
	//图片处理(缩放|水印)
	private function doImage($file=array(),$config=array()){
		$file_path='Uploads/'.$file['savepath'].$file['savename'];
	
		$Image = new \Think\Image();
		$Image->open($file_path);
		//指定缩放尺寸
		if($config['thumb']){
			if($config['height'] && $config['width']){
				$file_height=$config['height'];
				$file_width=$config['width'];
			}else{
				$file_height=C('UPLOAD_THUMB_HEIGHT');
				$file_width=C('UPLOAD_THUMB_WIDTH');
			}
			$Image->thumb($file_width,$file_height,\Think\Image::IMAGE_THUMB_FIXED);
			$file['type']=2;//图片缩略图类型
		}
		//添加水印
		if(C('UPLOAD_WATER') && $config['water']){
			$water=C('UPLOAD_WATER_PATH');
			if(is_file($water)){
				$Image->water($water,9);
			}
		}
	
		$Image->save($file_path);
		$file['size']=filesize($file_path);
		return $file;
	}
	

    
    
    
    
}