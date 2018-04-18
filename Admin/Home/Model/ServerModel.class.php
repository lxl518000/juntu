<?php
namespace Home\Model;
use Think\Model;
class ServerModel extends Model{

	protected $tableName = 'sock5_server';
	
	
	protected $_validate = array(
			//添加
			array('name', '2,20', '请输入SERVER名称',1, 'length',3),
			array('serverip', '2,20', '请输入SERVERIP',1, 'length',3),
			array('username', '2,20', '请输入SERVER用户名',1, 'length',3),
			array('password', '2,20', '请输入SERVER密码',1, 'length',3),
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