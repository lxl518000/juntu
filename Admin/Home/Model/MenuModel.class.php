<?php
namespace Home\Model;
use Think\Model;
class MenuModel extends Model{
	
	protected $tableName = 'sys_menu';

	protected $_validate = array(
			array('title','require','请填写菜单名称'),
		
	);
	
	public function getParent(){
		$where['status']=1;
		$where['type']=array('IN','2,3');
		$list = $this->where($where)->getField('id,pid,title',true);
		$list = listLevel($list);
		$list_root=array('id'=>0,'title'=>'顶级菜单','level'=>0,'mark'=>'');
		array_unshift($list, $list_root);
		return $list;
	}
}            