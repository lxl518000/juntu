<?php
namespace Home\Model;
use Think\Model;
class EnCateModel extends CommonModel{
	
	protected $tableName = 'en_cate';

	
	
	
	public function getParent(){
		$where['status']=1;
	
		$list = $this->where($where)->getField('id,pid,title',true);
		$list = listLevel($list);
		$list_root=array('id'=>0,'title'=>'顶级分类','level'=>0,'mark'=>'');
		array_unshift($list, $list_root);
		return $list;
	}
}            