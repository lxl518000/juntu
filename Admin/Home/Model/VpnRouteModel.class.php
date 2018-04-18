<?php
namespace Home\Model;
use Think\Model;
class VpnRouteModel extends CommonModel{
	
	protected $tableName = 'vpn_route';

	 protected $_auto = array (      
	 	        array('addtime','getTime',1,'callback'), // 对name字段在新增和编辑的时候回调getName方法    
	 	        array('adduser','getUser',3,'callback'), // 对update_time字段在更新的时候写入当前时间戳  
	 	        array('updtime','getTime',3,'callback'), // 对update_time字段在更新的时候写入当前时间戳  
	 	  );
	
	 
	
	 
}            