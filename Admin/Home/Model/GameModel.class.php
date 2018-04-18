<?php
namespace Home\Model;
use Think\Model;
class GameModel extends Model{

	protected $tableName = 'game_list';
	
	protected $_validate = array(
			//添加
			array('name', '2,20', '请输入用户名',1, 'length',3),
			array('process', '2,20', '请输入进程名',1, 'length',3),
			array('pmd5','','该进程MD5已存在',1,'unique',3),
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