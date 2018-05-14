<?php
namespace Home\Model;
use Think\Model;
class ContactsModel extends Model{
	
	// protected $tableName = 'sys_helper';
	
	
	protected $_validate = array(
			//添加
			// array('username', '2,20', '姓名长度不符,请保持在2-20个字符之内',1, 'length',3),
			// array('username','','该账号已被使用',1,'unique',1),
			// array('password', '6,30', '密码长度不符,请保持在6-30个字符之内',1,'length',1),
			
			//修改
			// array('password','6,30','密码长度不符,请保持在6-30个字符之内',2,'length',2),
	
			//通用
			
			array('name','2,20', '真实姓名长度不符,请保持在2-20个字符之内',1,'length',3),
			array('phone','/^\d{11}$/','请正确填写手机号码',2,'regex',3),
		   // array('mobile','','手机号已经存在！',2,'unique',3), // 在新增的时候验证name字段是否唯一
			// array('mobile','','该手机号已被使用',1,'unique',1),
			array('qqid','2,20','请正确填写QQ号码',0,'length',3),
            array('wxid','2,20','请正确填写微信号码',0,'length',3),
		
		
	);
	
	// protected $_auto = array (
	// 		array('adduser','getUser',3,'callback'),
	// 		array('addtime','getTime',1,'callback'),
	// );
	
	// protected function getUser(){
	// 	return session('adminuser.id');
	// }
	
	// protected function getTime(){
	// 	return date('Y-m-d H:i:s');
	// }
	
	
	


}