<?php
namespace Home\Controller;
use Think\Controller;


class SysuserController extends BackendController {
	
	protected $name = "用户";
	
	public function __construct(){
		parent::__construct();
		$this->model = D('Adminuser');
	}
	

	
	public function _before_add(){
		$parent = D('Role')->where()->getRole();
		$this->assign('parent',$parent);

		$list['status'] = 1;
		$this->assign('list',$list);
	}
	
	
	protected function _after_index(){
		$parent = D('Role')->where()->getRole();
		$this->assign('parent',$parent);
	}
	
	
 	protected function _format($list){
 		
 		$roles = D('Role')->getField('id,title',true);
	 	foreach($list as $k=>$vo){
	 		$op = '';
	 		
		 		$op .=  getToolIcon('edit','J_open btn-xs ',U('edit',['id'=>$vo['id']]),'','','','J_open')."&nbsp;";
	 		
	 		$op .= getToolIcon('delete','J_confirm btn-xs ',U('delete',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	 		
	 		if($vo['status']==1){
	 			$op .= getToolIcon('off','J_confirm btn-xs ',U('disable',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	 			$vo['status'] = "<span style='color:green'>启用</span>";
	 		}else{
	 			$op .= getToolIcon('on','J_confirm btn-xs ',U('enable',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	 			$vo['status'] = "<span style='color:#ccc'>禁用</span>";
	 		}
	 		
	 		$vo['role_id'] = $roles[$vo['role_id']];
	 		
	 		$vo['operate'] = $op;
	 		$list[$k] = $vo;
	 	}
	 	return $list;
	 }
	
	
	public function _after_edit($list){
		$_GET['pid'] = $list['pid'];
		$parent = D('Role')->getRole();
		$this->assign('parent',$parent);
		 
	}
    
	
	

}