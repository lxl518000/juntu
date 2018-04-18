<?php
namespace Home\Controller;
use Think\Controller;


class SmslogController extends BackendController {
	
	protected $name = "短信日志日志";
	
	protected function loadModel(){
		$this->model = D('sys_smslog');
		return $this->model;
	}
	
	protected $order = "id desc";
	
	
	


	protected function _search($name = '') {
		//加载model
		$this->assign('_search_block',1);
		
		
		if(!empty($_REQUEST['username'])){
			$map['username'] = array('like',"%{$_REQUEST['username']}%");
		}
		if(!empty($_REQUEST['phone'])){
			$map['phone'] = array('like',"%{$_REQUEST['phone']}%");
		}
		
		
		return $map;
	}
	
	
}