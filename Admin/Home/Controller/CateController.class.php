<?php
namespace Home\Controller;

class CateController extends BackendController {
	
	protected $name = "产品分类菜单";
	
	protected function loadModel(){
		$this->model = D('Cate');
		return $this->model;
	}
	
	protected function _before_add(){
		$this->loadModel();
		$parent = $this->model->getParent();
		$this->assign('parent',$parent);
		
		$list['pid'] = I('pid',0);
		$list['icon'] = "fa-align-justify";
		$list['sort'] = 1;
		$list['status'] = 1;
		$list['type'] = 2;
		$this->assign('list',$list);
		
	}
	
	public function index(){
		$where = array();
	
		$list = $this->loadModel()->where($where)->select();
		$list = $this->_format($list);
		$this->assign('list',$list);
		$this->display();
	}
	
	
	protected function _format($list){
		return listLevel($list);
	}
    
    protected function _after_edit($list){
    	$_GET['pid'] = $list['pid'];
    	$this->loadModel();
    	$parent = $this->model->getParent();
    	$this->assign('parent',$parent);
    }
    
    
    
    
}