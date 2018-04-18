<?php
/**
 * 太极对接接口
 * @author
 *准入：http://192.168.70.20:8080/webservice/services/FacadeServicePort?wsdl
 *执法：http://192.168.70.20:8080/webservice/services/zhiFaInfoService?wsdl
 *
 *S43000051561533901		Password423
 */
namespace Home\Service;
use Home\Service\Service;
class ReportService extends Service{
	
	
	
	public function __construct(){
		header('content-type:text/html;charset:utf8');
		$this->SOAP_HOST = 'http://192.168.70.20:8080/webservice/services/FacadeServicePort?wsdl';
		$this->SOAP_HOST1 = 'http://192.168.70.20:8080/webservice/services/zhiFaInfoService?wsdl';
		
		$this->auth =  array('USER_ID'=>'S43000051561533901','PASSWORD'=>'Password423');
		
		
	}
	

	
	
	
	public function login(){
		
		$wsdl = $this->SOAP_HOST;
		$soap = new \SoapClient($wsdl);
	
		
		
		
		//dump($soap->__getFunctions());
		
		$str = '<?xml version="1.0" encoding="UTF-8"?>
	<SERVICE>	
		<HEADER>		
			<USER_ID>S43000051561533901</USER_ID>
			<PASSWORD>Password423</PASSWORD>
		</HEADER>
	</SERVICE>:';
		
		$res = $soap->queryShowActivity();
		$return = $res->return;
		
		$arr = simplexml_load_string($return);
		dump($arr);
		
		return $res->return;
	}
	

	
}