<?php
namespace Home\Model;
use Think\Model;
class ChannelModel extends Model{
	
	protected $tableName = 'channel';

	protected $_validate = array(
			array('name','require','请填写渠道名称'),
		
	);
	
	public function getParent(){
		$list = $this->where($where)->getField('id,pid,name as title',true);
		$list = listLevel($list);
		$list_root=array('id'=>0,'title'=>'一级渠道','level'=>0,'mark'=>'');
		array_unshift($list, $list_root);
		return $list;
	}
	
	
	protected $_auto = array (
			array('adduser','getUser',3,'callback'),
			array('addtime','getTime',1,'callback'),
			array('link','getLink',1,'callback'),
	);
	
	protected function getUser(){
		return session('adminuser.id');
	}
	
	protected function getTime(){
		return date('Y-m-d H:i:s');
	}
	
	protected function getLink(){
		return uniqid();
	}
}            