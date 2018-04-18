<?php
namespace Home\Controller;

class VpnrouteController extends BackendController {
	
	protected $name = "路由表设置";
	
	protected function loadModel(){
		$this->model = D('VpnRoute');
		return $this->model;
	}
	
	
	
	protected function _before_add(){
		$list['status'] = 1;
		$this->assign('list',$list);		
	}
	

	
	protected function _format($list){
		
		$group = C('CONFIG_GROUPS');
		$status = [1=>"<span style='color:green'>正常</span>",2=>'<span style="color:red">禁用</span>'];
		foreach($list as $k=>$v){
		
			$op = '';
	 		
	 		$op .=  getToolIcon('edit','J_open btn-xs ',U('edit',['id'=>$v['id']]),'','','','J_open')."&nbsp;";
	 		
	 		$op .= getToolIcon('delete','J_confirm btn-xs ',U('delete',['id'=>$v['id']]),'','','','J_confirm')."&nbsp;";
	 		
	 		if($v['status']==1){
	 			$op .= getToolIcon('off','J_confirm btn-xs ',U('disable',['id'=>$v['id']]),'','','','J_confirm')."&nbsp;";
	 		}else{
	 			$op .= getToolIcon('on','J_confirm btn-xs ',U('enable',['id'=>$v['id']]),'','','','J_confirm')."&nbsp;";
	 		}
	 		
	 		$v['status'] = $status[$v['status']];
	 		
	 		$v['operate'] = $op;
	 		
			
			$list[$k] = $v;
		}
		
		return $list;
	}
	

	
	
	
 
    
    
    
    
   
}