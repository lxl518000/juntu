<?php 
/*
 * swf上传多图挂件
 * @author liufei
 */
namespace Home\Widget;
use Think\Controller;

class ZtreeWidget extends Controller {  
	
	/**
	 * 自动完成
	 */
	public function getselect($type,$hidename,$checked,$checktype='{"Y":"", "N":""}'){
		
		
		$set = explode(',',$checked);
		// dump(session('adminuser'));
		$this->model = D('Department');
		$where = array();
		
		$dpath = session('adminuser.dep_path');
		$code = session('adminuser.region');
		if($code){
			$where['region'] = array('like',"{$code}%");
		}
		
		if($dpath){
			$where['path'] = array('like',"{$dpath}%");
		}
		
		$list = D('Department')->where($where)->select();
		$all = array();
		foreach ($list as $v) {
			$all[$v['id']] = $v['name'];
			$t=array(
					'id'=>$v['id'],
					'pId'=>$v['pid'],
					'name'=>$v['name'],
			);
			/* if($v['pid']==0) {
				$t['open']=true;
			}else{
				$t['open'] = false;
			} */
			$t['open'] = false;
			//$t['open']=true;
			if(in_array($v['id'], $set)) $t['checked']=true;
			$node[]=$t;
		}
		$add = array('id'=>0,'pid'=>'0','name'=>'不限','open'=>true);
		array_unshift($node, $add);
		
		if($set){
			$names =D('Department')->where(array('id'=>array('in',$set)))->getField('name',true);
			$this->assign('checknames',implode(',', $names));
			$this->assign('checked',$checked);
		}
		
		$hidename = $hidename?$hidename:"bid";
		$type = $type?$type:'radio';
		$this->assign('hidename',$hidename);
		$this->assign('type',$type);
		$this->assign('checktype',$checktype);
		$this->assign('node',json_encode($node));
		$this->assign('all',json_encode($all,JSON_UNESCAPED_UNICODE));
		$this->assign('list',$list);
		$this->display('Widget/getselect');
	}

	

}

?>