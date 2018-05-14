<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用入口文件
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');



define('APP_DEBUG',TRUE);
define('BIND_MODULE','Home');

$_GET['c'] = !empty($_GET['c']) ? $_GET['c'] : 'Index';
$_GET['a'] = !empty($_GET['a']) ? $_GET['a'] : 'index';

define('ROOTPATH',dirname(__FILE__).'/');

define('APP_PATH',ROOTPATH.'../Admin/');

require ROOTPATH.'../ThinkPHP/ThinkPHP.php';
