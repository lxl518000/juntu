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
	    $this->types = [1=>'普通文本',2=>'富文本',3=>'图片'];
	    $this->assign('types',$this->types);
        $this->assign('host',$host);

    }

    protected function _before_add(){
		$this->loadModel();
		$parent = D('Cate')->getParent();
		$this->assign('parent',$parent);
		$list['type'] = 1;
		$list['sid'] = $_REQUEST['sid'] ? $_REQUEST['sid'] : '';
		$this->assign('list',$list);
		$this->assign('callback','subfun');
	}

	protected function _before_insert($data){
        return $this->_MakeData($data);

    }

    protected function _before_update($data){
        return $this->_MakeData($data);
    }

    protected function _MakeData($data){
	    $type = I('type');
	    if($type ==1){
	        $data['value'] = I('value');
        }elseif($type == 2){
	        $data['value'] = I('content');
        }else{
	        $data['value'] = I('pic');
        }

	    return $data;
    }
	
	protected function _format($list){
			
		$host = $this->host;
		$types = $this->types;
		
		foreach($list as $k=>$vo){
			$op = '';
	
			$op .=  getToolIcon('edit','J_open btn-xs ',U('edit',['id'=>$vo['id']]),'','','','J_open')."&nbsp;";
	
			$op .= getToolIcon('delete','J_confirm btn-xs ',U('delete',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	



			if($vo['type']==3){
				$pics = explode(';',$vo['value']);
				$tmp = '';
				foreach($pics as $d){
					$tmp .= "<img style='max-height:60px;max-width:60px' src='{$d}'>";
				}
			    $vo['value'] = $tmp;
            }elseif($vo['type'] == 2){
            	$vo['value'] = msubstr(strip_tags(htmlspecialchars_decode($vo['value'])),0, 100);
            }else{
            	$vo['value'] = msubstr($vo['value'],0,100);
            }
     
            $vo['type'] = $types[$vo['type']];
			$vo['host'] = $host[$vo['sid']];

			$vo['operate'] = $op;
			$list[$k] = $vo;
		}
		return $list;
	}
	
	
	
    protected function _after_edit(&$list){
	    if($list['type'] == 2){
	        $list['content'] = $list['value'];
        }elseif($list['type'] == 3){
	        $list['pic'] = $list['content'];
        }
    }
    
    
    
    
}