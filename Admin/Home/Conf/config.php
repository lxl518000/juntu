<?php
return array(

	'VAR_SESSION_ID'      => 'var_session_id',     //sessionID的提交变量
	
	'DEFAULT_PATE_SIZE'=>15,
		

	
		
	//短信配置	
	'MOBILE_CONFIG' => array(
		'API_URL'  => 'http://m.5c.com.cn/api/send/?',
		'USER_NAME' => '18007139710',
		'USER_PASS' => 'LK5s0aZtWcJX',
		'USER_KEY'  => 'a0357c1c4144c7513ad967b373439772'	
	),	
		
	
	//动态配置中Select所需参数
	'CONFIG_GROUPS'=>array(
			1=>'常用配置',
			3=>'手机短信',
			4=>'电子邮箱',
			5=>'综合配置',
			11=>'上传配置',
	),

		
		
);