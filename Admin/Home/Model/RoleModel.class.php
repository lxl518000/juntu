<?php
namespace Home\Model;
use Think\Model;
class RoleModel extends Model{
	
	protected $tableName = 'sys_role';

	protected $_validate = array(
			array('title','require','请填写角色名称'),
		
	);
	
	
   public function getParent(){
        $list=$this->where($where)->getField('id,pid,title');
        $list = listLevel($list);
	    $list_root=array('id'=>0,'title'=>'顶级角色','level'=>0,'mark'=>'');
	    array_unshift($list, $list_root);
       
        return $list;
    }
    
    public function getTree(){
    	$where['status']=1;
        $where=array();
        if(!session('adminuser.isadmin')==1){
              $roleid = session('adminuser.role_id');
              
              $data['id']=$roleid;
              $data['status']=1;
              $path = $this->where($data)->getField('path');
             
              $where['path']=array('like',"{$path}%");
              $where['status']=1;
            //   $where['path']=array('neq',"{$path}");
              
        }
       
    
        $list=$this->where($where)->select();
        // print_r(D()->_sql());
        // dump($list);
        $list[0]['pid']=0;
        $list = listLevel($list);
    //    dump($list);
    	return $list;
    }
    
    public function getRole(){
        $where['status']=1;
        $where=array();
        $path = session('adminuser.role_path');
        $where['status'] = 1;
        $where['path'] = array('like',"{$path}%");
        $list=$this->where($where)->select();
        $list[0]['pid']=0;
       $list = listLevel($list);
        return $list;
    }
    
    /**
     * cache this
     */
    public function getCacheAll(){
    	$all = $this->getField('id,title',true);
    	return $all;
    	
    }
	

}