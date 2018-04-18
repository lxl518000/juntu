<?php
$common = include APP_PATH.'/Common/Conf/config.php';
$user = include APP_PATH.'/Home/Conf/user.php';
$home = include APP_PATH.'/Home/Conf/config.php';
$conf = array(
		
'SESSION_AUTO_START'=>false,
		
);
return array_merge($common,$home,$user,$conf);

