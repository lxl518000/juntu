<?php 
/*
 * Plupload
 * @author liufei
 * @time   2015-11-17
 */
namespace Home\Widget;
use Think\Controller;

class PluploadWidget extends Controller {  
	
	/**
	 * 自动完成
	 */
	public function uploadone($url,$callback='',$hideName="upfile",$ext="*"){
		
		
		$this->assign('ext',$ext);
		$this->assign('hidename',$hideName);
		$this->assign('callback',$callback);
		$this->assign('url',$url);
		$this->display('Widget/uploadone');
	}
	
	
	public function collect($url,$callback='',$hideName="upfile",$ext="*"){
		$this->assign('ext',$ext);
		$this->assign('hidename',$hideName);
		$this->assign('callback',$callback);
		$this->assign('url',$url);
		$this->display('Widget/collect');
	}
	

}

?>