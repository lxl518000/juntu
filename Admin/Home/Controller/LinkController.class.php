<?php
namespace Home\Controller;
use Think\Controller;


/**
 * 场所管理
 * @author Administrator
 *
 */
class LinkController extends BackendController {


	protected function loadModel(){
		$this->model = D('Channel');
		return $this->model;
	}
	
	
	public function __construct(){
		parent::__construct();
	}
	
	protected function _before_add(){
		$this->loadModel();
		$parent = $this->model->getParent();
		$this->assign('parent',$parent);

	}
	
	
	public function _format($list){
		return listLevel($list);
	}
	

	
	protected function _after_edit($list){
		$_GET['pid'] = $list['pid'];
		$this->loadModel();
		$parent = $this->model->getParent();
		$this->assign('parent',$parent);
		 
	}
	
	
    
    
}