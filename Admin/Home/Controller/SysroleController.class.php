<?php
namespace Home\Controller;

class SysroleController extends BackendController {
	
	protected $name = "角色管理";
	
	public function loadModel(){
		$this->model = D('Role');
		return $this->model;
	}
	
	
	public function index(){
		$where = array();
		if($_REQUEST['title']){
			$where['title'] = array('like',"%{$_REQUEST['title']}%");
		}
		$list = $this->loadModel()->where($where)->select();
		$list = $this->_format($list);
		$this->assign('list',$list);
		$this->display();
	}
	
	
	public function _before_add(){
		$this->loadModel();
		$parent = $this->model->getParent();
		$this->assign('parent',$parent);
		
		$list['pid'] = I('pid',0);
		$list['status'] = 1;
		$list['isadmin'] = 2;
		$this->assign('list',$list);
	}

	
	protected function _after_insert($data){
		$path = $this->model->where(['id'=>$data['pid']])->getField('path');
		if(!$path){
			$path = "0,";
		}else{
			$path .= $data['id'].','; 
		}
		
		$this->model->where(['id'=>$data['id']])->save(['path'=>$path]);
		return true;
	}
	
	protected function _after_update($data){
		$path = $this->model->where(['id'=>$data['pid']])->getField('path');
		if(!$path){
			$path = "0,";
		}else{
			$path .= $data['id'].',';
		}
		
		$this->model->where(['id'=>$data['id']])->save(['path'=>$path]);
		return true;
	}
	

	
	public function _format($list){
		return listLevel($list);
	}
    
    public function _after_edit($list){
    	$this->loadModel();
    	$parent = $this->model->getParent();
    	$this->assign('parent',$parent);
    	
    }
    
    
    
    /**
     * 授予权限
     */
    public function authrule(){
    	$id = I('id');
    	if(IS_POST){
    		$model= $this->loadModel();
    		$data= $model->create();
    		if(!$data) $this->error($model->getError());
    		$return= $model->save($data);
    		if($return) $this->success('授权成功');
    		else $this->error('授权失败');
    	}
    	$this->assign('id',$id);
    	$rule_all = D('Menu')->where("status=1")->getField('id,pid,title,type');
    	$uinfo = D('Role')->where("id={$id}")->find();
    	$user_group = $uinfo['rules'];
    	$this->assign('user_group',$user_group);
    	$user_rule=explode(',', $user_group);
    	
    	
    	$node=array();
    	foreach ($rule_all as $v) {
    		$t=array(
    				'id'=>$v['id'],
    				'pId'=>$v['pid'],
    				'name'=>$v['title'],
    		);
    		//if($v['pid']==0) $t['open']=true;
    		$t['open'] = true;
    		if(in_array($v['id'], $user_rule) || $uinfo['isadmin'] == 1) $t['checked']=true;
    		$node[]=$t;
    	}
    	$this->assign('node',json_encode($node));
    	
    	$this->display();
    }
    
}