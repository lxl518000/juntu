<?php
namespace Home\Controller;
use Think\Controller;


/**
 * 场所管理
 * @author Administrator
 *
 */
class ApplyController extends BackendController {
	
	public function __construct(){
		parent::__construct();
		$this->order = "id desc";
	}
	
	


	public function index(){
		$this->assign('_search_block',1);
		if(!empty($_REQUEST['realname'])){
			$map['realname'] = array('like',"%{$_REQUEST['realname']}%");
		}
		
		$list = $this->_list(D('apply_user'), $map);
		$this->assign('list',$list);
		$this->display();
	}
	

	public function detail(){
		$id = I('id');
		$where['id'] = $id;
		$list = D('apply_user')->where($where)->find();
		
		$this->assign('list',$list);
		$this->display();
	}
	
	
    
    
}