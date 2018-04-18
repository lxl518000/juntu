<?php
namespace Home\Model;
use Think\Model;
class LoaderModel extends Model{

	protected $tableName = 'loader';
	
	protected $_validate = array(
			array('ver','require','请输入版本号！'), //默认情况下用正则进行验证
	);
	
	
	protected $_auto = array (
			array('adduser','getUser',3,'callback'),
			array('addtime','getTime',1,'callback'),
	);
	
	

	protected function getUser(){
		$u = session('adminuser');
		return $u['realname']."({$u['id']})";
	}
	
	protected function getTime(){
		return date('Y-m-d H:i:s');
	}
	

	
}