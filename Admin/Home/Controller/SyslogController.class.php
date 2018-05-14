<?php
namespace Home\Controller;
use Think\Controller;


class SyslogController extends BackendController {
	
	protected $name = "系统日志";
	
	protected function loadModel(){
		$this->model = D('sys_log');
		return $this->model;
	}
	
	protected $order = "id desc";
	
	
	


	protected function _search($name = '') {
		//加载model
		$this->assign('_search_block',1);
		
		
		if(!empty($_REQUEST['username'])){
			$map['username'] = array('like',"%{$_REQUEST['username']}%");
		}
		
		
		return $map;
	}
	
	
}