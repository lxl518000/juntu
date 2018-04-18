<?php
namespace Vpnapi\Controller;
use Think\Controller;
class BaseApiController extends Controller {

	protected $appkey = "cR2sB4hH2bB1dD2c";
	public function __construct(){
		parent::__construct();
		$this->_checkAuth();
		
		
	}

	public function index(){
		
	}
	

	
	protected function _checkAuth(){
		//$_REQUEST['sign'] = $this->getSignature($_REQUEST);
		$method = $_REQUEST['method'];
	
		
		if(!$method){
				$this->setRes('unknown method');
		}
			
		$t = $_REQUEST['t'];
		
		$params = $_REQUEST;
		$sign = $_REQUEST['sign'];
		$checkSign = $this->getSignature($params);

		if(!$t){
			$this->setRes('param t need');
		}
		
		 if(!$sign){
			$this->setRes('param sign need');
		}
			
		if($_REQUEST['debug'] == 1){
			dump($checkSign);
			exit;
		}
			
		if($sign != $checkSign){
			$this->setRes('check faild');
		} 
		
		
		if(method_exists($this, $method)){
			return call_user_func_array(array($this,$method), $_REQUEST);
		}else{
			$this->setRes('Unavailable method');
		}
	}
	
	//输出内容
	protected function setRes($info,$status=0,$data = array()){
	
		$status = (int) $status;
	
		$return = $this->return;
		$return['info'] = $info;
		$return['status'] = $status;
		$return['data'] = $data;
	
		//print_r($return);exit;
		
		$res = json_encode($return);
		$len = strlen($res);
		header('Content-Length: '.$len);
		echo $res;
		exit;
	
	}
	
	
	protected function getSignature($params){
		
		unset($params['sign']);
		unset($params['debug']);
		unset($params['PHPSESSID']);
		unset($params['pgv_pvi']);
		unset($params['pgv_si']);
		unset($params['_currentUrl_']);
		$str = '';  //待签名字符串
		//先将参数以其参数名的字典序升序进行排序
		ksort($params);
		
		$params = http_build_query($params);
		$params = urldecode($params);
		
		
		$str = $this->appkey.'-'.$params.'-';
		
		if($_REQUEST['debug'] == 3){
			
			echo $str;exit;
		}
		
		
		//通过md5算法为签名字符串生成一个md5签名，该签名就是我们要追加的sign参数值
		return md5($str);
	}
	
    
}