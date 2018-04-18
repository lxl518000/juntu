<?php 
/*
 * 自动完成挂件
 * @author liufei
 * @time   2015-11-17
 */
namespace Home\Widget;
use Think\Controller;

class EditorWidget extends Controller {  
	
	/**
	 * 自动完成
	 */
	public function ueditor($name='content',$width='1000',$height='300',$content){
		
		$this->assign('name',$name);
		$this->assign('content',$content);
		$this->assign('width',$width);
		$this->assign('height',$height);
		$this->display('Widget/ueditor');
	}

}


?>