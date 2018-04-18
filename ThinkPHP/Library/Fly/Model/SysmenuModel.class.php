<?php
namespace Fly\Model;
use Think\Model;
//use Think\Model\RelationModel;
class SysmenuModel extends Model{
	   
	protected $tableName = 'sys_menu';

	  protected $_auto = array (    
	       // array('time','getTime',1,'callback'), // 对update_time字段在更新的时候写入当前时间戳    
	   );

	  protected $_validate = array(   
	 	   array('text','require','菜单名称不能为空'), //默认情况下用正则进行验证
	 	   // array('cid','require','请选择一个分类'),
	      // array('name','','分类名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一   
	      //  array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内  
	      // array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致     
	       //array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式 

	    );


	  




	
}