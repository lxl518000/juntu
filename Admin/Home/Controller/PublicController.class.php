<?php
namespace Home\Controller;
use Think\Controller;
class PublicController extends BackendController {
	
	
	public function webupload(){
		
		if(I('pic')){
			$pics = explode(';', $_REQUEST['pic']);
		}else{
			$pics = [];
		}
		$this->assign('pics',json_encode($pics));
		$this->display();
	}
	
	public function showbd(){
		$this->display();
	}
	
	public function showsc(){
		$this->display();
	}
	
	function ajax_upload()
	{
	
		ini_set('max_execution_time', '0');
	
		// 上传文件类型控制
		$ext_arr= array(
				'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
				'photo' => array('jpg', 'jpeg', 'png'),
				'flash' => array('swf', 'flv'),
				'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
				'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pdf')
		);
		
		$return = array();
		$return['code'] = 0;
		$return['message'] = '上传错误';
		$return['data'] = [];
		if(!$_FILES){
			echo json_encode($return);
			exit;
		}
		
		// 上传文件配置
		$config=array(
				//'maxSize'   =>  '',               // 上传文件最大为500M
				'rootPath'  =>  ROOTPATH,                   // 文件上传保存的根路径
				'savePath'  =>  './Uploads/',         // 文件上传的保存路径（相对于根路径）
				'saveName'  =>  array('uniqid',''),     // 上传文件的保存规则，支持数组和字符串方式定义
				'autoSub'   =>  true,                   // 自动使用子目录保存上传文件 默认为true
				'exts'      =>    isset($ext_arr[$format])?$ext_arr[$format]:'',
		);
	
		// 实例化上传
		$upload=new \Think\Upload($config);
	
		// 调用上传方法
		$info=$upload->upload();
			
		if(!$info){
			// 返回错误信息
			$error=$upload->getError();
			$return['message']= $error;
			echo json_encode($return);
			exit;
		}
		$info = $info['file'];
		//查找库里是否有该文件
		$find = D('sys_file')->where(array('md5'=>$info['md5']))->find();
		if($find){
			$return['message'] = '上传成功';
			$return['code'] = 1;
			$return['data'] = $find;
			echo json_encode($return);
			unlink($info['savepath'].$info['savename']);
			exit;
		}
		
		//print_r($info);exit;
		
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
		$return['message'] = '上传成功';
		$return['code'] = 1;
		$return['data'] = $info;
		echo json_encode($return);
		
		
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
		
		exit;
	}
	
	/**
	 * 上传文件
	 */
	public function upfile(){
		if (isset($_POST["PHPSESSID"])) {
			session_id($_POST["PHPSESSID"]);
		}
	
		//dump($_REQUEST);exit;
	
		ini_set("html_errors", "0");
	
		// Check the upload
		if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
			echo "ERROR:invalid upload";
			exit(0);
		}
	
		$ext = '.'.pathinfo($_FILES["Filedata"]["name"],PATHINFO_EXTENSION);//获取上传文件的后缀名
	
		$file_id = date('YmdHis').uniqid().$ext;
		$dir = './Upload/file/'.date('Ymd',time()).'/';
		if(!is_dir($dir)){
			$oldid = umask(0);
			mkdir($dir,0777,true);
			umask($oldid);
		}
	
		if(is_uploaded_file($_FILES["Filedata"]['tmp_name'])){
	
			// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
			move_uploaded_file($_FILES["Filedata"]['tmp_name'],$dir.$file_id);
			/* $image = new \Think\Image();
			 $image->open($dir.$file_id);
			$image->thumb(600, 600)->save($dir.$file_id); */
			//exit;
		}
		echo "FILEID:" .$dir.$file_id;	// Return the file id to the script
	
	}
	
	

	public function getAreaChildren() {
		$area = p ( 'area' );
		$_RegionCodeList = gM ( "Region" )->where ( array (
				'ParentCode' => $area
		) )->select ();
		empty ( $_RegionCodeList ) && $_RegionCodeList = array ();
		$this->ajaxReturn ( $_RegionCodeList );
	}
	

	
	
	
	public function upimg(){
		/**
		 * 上传文件
		 */
			if (isset($_POST["PHPSESSID"])) {
				session_id($_POST["PHPSESSID"]);
			}
		
			$type = $_GET['type'] ? $_GET['type'] : 'face';
			//dump($_REQUEST);exit;
		
			ini_set("html_errors", "0");
		
			// Check the upload
			if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
				echo "ERROR:invalid upload";
				exit(0);
			}
		
			$ext = '.'.pathinfo($_FILES["Filedata"]["name"],PATHINFO_EXTENSION);//获取上传文件的后缀名
		
			$file_id = date('YmdHis').uniqid().$ext;
			$dir = './Upload/'.$type.'/'.date('Ymd',time()).'/';
			if(!is_dir($dir)){
				$oldid = umask(0);
				mkdir($dir,0777,true);
				umask($oldid);
			}
		
			if(is_uploaded_file($_FILES["Filedata"]['tmp_name'])){
		
				// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
				move_uploaded_file($_FILES["Filedata"]['tmp_name'],$dir.$file_id);
				/* $image = new \Think\Image();
				 $image->open($dir.$file_id);
				$image->thumb(600, 600)->save($dir.$file_id); */
				//exit;
			}
			echo "FILEID:" .$dir.$file_id;	// Return the file id to the script
		
		
	}
	
	

	
	
	

	
    public function login(){
    	if(!empty(session('adminuser'))){
    		redirect(U('Index/index'));
    	}
    	$this->display();
    }
    
    
    public function verify()
    {
    	//$key = $_GET['keyid']?$_GET['keyid']:'Login' ;
    	$Verify = new \Think\Verify();
    	$Verify->fontSize = 17;
    	$Verify->length   = 4;
    	$Verify->useNoise = true;
    	$Verify->useCurve = false;
    	$Verify->imageH = 42;
  
    	$Verify->codeSet = '023456789';
    	$Verify->imageW = 119;
    	$Verify->entry($key);
    }
    
    
    
    /**
     * 手机号登录
     */
    public function dologin(){
      	//账号密码登录
    	$phone = I('cellphone');
    	$password = I('password');
    	$code = I('yzm');
    	if(empty($phone) ||empty($password) || empty($code)){
    		$this->error('请输入完整信息！');
    	}
    	//普通难证码登录
    	$verify = new \Think\Verify();
    	$check = $verify->check($code);
    	if(!$check){
    		$this->error('验证码错误');
    	}
    	$userModel = D('Adminuser');

    	$where = array();
    	$where['mobile'] = $phone;
    	$res = $userModel->where($where)->find();
    	if(!$res || (md5($password) !== $res['password'])){
    		$this->error('用户名或密码错误！');
    	}
    
    	$chk = $userModel->checkLogin($res);
    
    	if(!$chk){
    		$this->error('当角用户角色未被授权');
    	}
    	
    	$this->syslog('登录成功');
    	
    	$this->success('登录成功！',U('Sysmenu/index'));
    
    }
    
    /**
     * 退出登录
     */
    public function logout(){
    	
    	session('adminuser',null);
    	cookie('currArea','');
    	redirect(U('login'));
    }
    
	
	

}