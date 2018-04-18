<?php 
/*
 * swf上传多图挂件
 * @author liufei
 */
namespace Home\Widget;
use Think\Controller;

class SwfWidget extends Controller {  
	
	/**
	 * 自动完成
	 */
	public function swf($url,$ext){
		$ext = empty($ext) ? '*.png;*.jpeg;*.jpg;*.gif;*.bmp;' : $ext;
		$this->assign('url',$url);
		$this->assign('ext',$ext);
		$this->display('Widget/swf');
	}

	public function swfile($url,$ext){
		$ext = empty($ext) ? '*.png;*.jpeg;*.jpg;*.gif;*.bmp;' : $ext;
		$this->assign('url',$url);
		$this->assign('ext',$ext);
		$this->display('Widget/swfile');
	}


}

?>