<?php

return array(
	
	
	// Redis相关配置
	'REDIS_CONFIG' => array(
		//array(  'host'=>'10.171.133.251','port'=>6379,'pwd'=>'Hhz425KQnj'),
		//array(  'host'=>'192.168.0.215','port'=>6379,'pwd'=>''),
			
		array(  'host'=>'127.0.0.1','port'=>6379,'pwd'=>''),
	),
		
	//本机redis
	'SELF_REDIS_CONFIG'=>array(
			array( 'host'=>'127.0.0.1','port'=>6379,'pwd'=>''),
	),

	'REDIS_NEW_CONFIG'=>array(
		'master'=>array('host'=>'127.0.0.1','port'=>6379,'pwd'=>''),
		'slave'=>array('host'=>'127.0.0.1','port'=>6379,'pwd'=>'')
	),
	
	'REDIS_LOCAL_CONFIG'=>array(
		'master'=>array('host'=>'127.0.0.1','port'=>6379,'pwd'=>''),
	),
		
		
		

		
);
