<?php
return array(
	/* 数据缓存设置 */
	'DATA_CACHE'			=>	FALSE, //是否开启缓存
	'DATA_CACHE_TIME'       =>  3600,      // 数据缓存有效期 0表示永久缓存
	'DATA_CACHE_F_FUNCTION'	=>	'F',	//永久存储函数
	'URL_CASE_INSENSITIVE' => true,
	'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
	// 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
	
	/* 互斥锁 */
	'LOCKED_TYPE'			=>	'File',	//锁类型: File,Memcache
	'LOCKED_TIME'			=>	10,//获取锁等待时间 单位秒 ,Memcache下有效
	'LOCKED_STATUS'			=>	false,//当前锁状态	
	

	// 允许访问的模块列表
	'MODULE_ALLOW_LIST'     =>  array('Home','Admin','Porto'),

	 'DEFAULT_MODULE'     => 'Porto', 	//默认模块   
	
	/*数据库配置*/	
	'DB_TYPE'               => 'mysql',     // 数据库类型

	'DB_HOST'               => '127.0.0.1', // 服务器地址
	'DB_NAME'               => '6fei',         // 数据库名 

	'DB_USER'               => 'root',      // 用户名
	'DB_PWD'                => 'lf123456',          // 密码
	'DB_PORT'               => 3306,        // 端口
	'DB_PREFIX'             => 'tb_',    // 数据库表前缀
	'DB_SUFFIX'             => '',          // 数据库表后缀
	'DB_FIELDTYPE_CHECK'    => true,       // 是否进行字段类型检查
	'DB_FIELDS_CACHE'       => false,        // 启用字段缓存
	'DB_CHARSET'            => 'utf8',      // 数据库编码默认采用utf8
	'DB_DEPLOY_TYPE'        => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
	'DB_RW_SEPARATE'        => false,       // 数据库读写是否分离 主从式有效
	
		// 允许访问的模块列表
	'MODULE_ALLOW_LIST'     =>  array('Home'),
		
	'MODULE_DENY_LIST'      =>  array('Common','Runtime','Util'),
		
	'SHOW_PAGE_TRACE'		=> false,//显示日志信息
	
	'LOAD_EXT_CONFIG' => 'user,mz,credit',
		
	//'CONTROLLER_LEVEL'      =>  2,
	'SESSION_EXPIRE'=>604800,
	'SESSION_AUTO_START'=>true,
		
);		

	
