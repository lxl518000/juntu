<?php
namespace Home\Controller;

class SiteConfigController extends BackendController {
	
	protected $name = "站点配置";
	
	protected function loadModel(){
		$this->model = D('SiteConfig');
		return $this->model;
	}

	protected function _initialize(){

	    $host = D('Site')->getField('id,host',true);

	    $this->host = $host;
	    $this->types = [1=>'普通文本','富文本'];
	    $this->assign('types',$this->types);
        $this->assign('host',$host);

    }

    protected function _before_add(){
		$this->loadModel();
		$parent = D('Cate')->getParent();
		$this->assign('parent',$parent);
		$list['type'] = 1;
		$this->assign('list',$list);
		$this->assign('callback','subfun');
	}
	
	protected function _format($list){
			
		$host = $this->host;
		$types = $this->types;
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

			$vo['type'] = $types[$vo['type']];
			$vo['host'] = $host[$vo['sid']];

			$vo['operate'] = $op;
			$list[$k] = $vo;
		}
		return $list;
	}
	
	
	
    protected function _after_edit($list){

    }
    
    
    
    
}