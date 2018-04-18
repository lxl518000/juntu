<?php
namespace Home\Controller;
use Think\Controller;


class QuestionController extends BackendController {
	
	protected $name = "常见问题管理";

	protected $isp;
	public function __construct(){
		parent::__construct();
		$this->model = D('Question');
	}
	
	public function _before_add(){

		$list['status'] = 1;
		$list['sort'] = 1;
		$this->assign('list',$list);
	}
	
	public function _after_insert(){
		$this->_cache();
	}
	
	
	protected function _cache($list){
		$where = array();
		$where['status'] = 1;
		$field = "title,content";
		$list = $this->model->field($field)->where($where)->order('sort desc')->select();
		$redis = service('Redis');
		$redis->set("SPEED:QUESTION_LIST",json_encode($list));
		return true;
	}
	
	
 	protected function _format($list){
 		
 		$isp = $this->isp;
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
	 		
	 		$vo['isp'] = $isp[$vo['isp']];
	 	
	 		$vo['operate'] = $op;
	 		$list[$k] = $vo;
	 	}
	 	return $list;
	 }
	
	
	public function _after_edit($list){
		$this->_cache();
	}
    
	
	

}