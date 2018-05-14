<?php
namespace Home\Controller;
use Think\Controller;
class EmptyController extends Controller{ 
	
	
	
	 public function _empty($name){      
	 	dump($name); 
	 	dump(CONTROLLER_NAME);
	 	dump(MODULE_NAME);
	 	dump(ACTION_NAME);
	 	echo '即将上线';
	  }
	
	
}