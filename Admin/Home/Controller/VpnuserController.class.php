<?php
namespace Home\Controller;

class VpnuserController extends BackendController {
	
	protected $name = "用户管理";
	
	protected function loadModel(){
		$this->model = D('VpnUser');
		return $this->model;
	}
	
	
	
	protected function _before_add(){
		$list['status'] = 1;
		$this->assign('list',$list);		
	}
	

	
	protected function _format($list){
		
		$group = C('CONFIG_GROUPS');
		$status = [1=>"<span style='color:green'>正常</span>",2=>'<span style="color:red">禁用</span>'];
		$userlevel = [1=>'普通用户',2=>'黄金用户',3=>'钻石用户'];;
		foreach($list as $k=>$v){
		
			$op = '';
	 		
	 	
	 		
	 		if($v['status']==1){
	 			$op .= getToolIcon('off','J_confirm btn-xs ',U('disable',['id'=>$v['id']]),'','','','J_confirm')."&nbsp;";
	 		}else{
	 			$op .= getToolIcon('on','J_confirm btn-xs ',U('enable',['id'=>$v['id']]),'','','','J_confirm')."&nbsp;";
	 		}
	 		
	 		$v['usertype'] = $userlevel[$v['usertype']];
	 		$v['status'] = $status[$v['status']];
	 		
	 		$v['operate'] = $op;
	 		
			
			$list[$k] = $v;
		}
		
		return $list;
	}
	

	
	
	
 
    
    
    
    
   
}