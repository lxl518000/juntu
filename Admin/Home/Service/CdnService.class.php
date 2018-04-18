<?php
/**
 * cdn操作类
 * @author Administrator
 *
 */
namespace Home\Service;
use Home\Service\Service;
date_default_timezone_set('UTC');
class CdnService extends  Service{
	private $config = array();
	
	function __construct(){
		$this->config = array(
			'AccessKeyId'=>'LTAIw6ytLNmwAlHQ',
			'AccessScret'=>'iTBFrbMaUGXPIoshZN03yTq73tAX3B'
		);
	}
	
	/**
	 * 刷新cdn
	 * @param string $url
	 * @return mixed
	 */
	public function freshCache($url){
		$path = str_replace('http://', '', $url);
		$arr = $this->_getBaseParam();
		$arr['Action'] = 'RefreshObjectCaches';
		$arr['ObjectPath'] = $path;
		
		ksort($arr);
		
		$prefix = 'GET&%2F&';
		$value = $prefix.urlencode(http_build_query($arr));
		$m_strKey = $this->config['AccessScret'].'&';
		$Signature = base64_encode(hash_hmac("sha1",$value,$m_strKey,true));
		
		$arr['Signature'] = $Signature;
		$request = 'http://cdn.aliyuncs.com/?'.http_build_query($arr);
		$json = file_get_contents($request);
		$data = json_decode($json,true);
		return $data;
	}
	
	/**
	 * 预热接口
	 * @param string $url
	 * @return mixed
	 */
	public function pushCache($url){
		$path = str_replace('http://', '', $url);
		$arr = $this->_getBaseParam();
		$arr['Action'] = 'PushObjectCache';
		$arr['ObjectPath'] = $path;
		
		ksort($arr);
		
		$prefix = 'GET&%2F&';
		$value = $prefix.urlencode(http_build_query($arr));
		$m_strKey = $this->config['AccessScret'].'&';
		$Signature = base64_encode(hash_hmac("sha1",$value,$m_strKey,true));
		
		$arr['Signature'] = $Signature;
		$request = 'http://cdn.aliyuncs.com/?'.http_build_query($arr);
		//echo $request;exit;
		$json = file_get_contents($request);
		$data = json_decode($json,true);
		return $data;
	}
	
	/**
	 * 查询cdn的预热结果
	 * @param int $taskid
	 * @return mixed
	 */
	public function queryCdnTask($taskid){
		$arr = $this->_getBaseParam();
		$arr['Action'] = 'DescribeRefreshTasks';
		$arr['TaskId'] = $taskid;
		ksort($arr);
		
		$prefix = 'GET&%2F&';
		$value = $prefix.urlencode(http_build_query($arr));
		$m_strKey = $this->config['AccessScret'].'&';
		$Signature = base64_encode(hash_hmac("sha1",$value,$m_strKey,true));
		
		$arr['Signature'] = $Signature;
		$request = 'http://cdn.aliyuncs.com/?'.http_build_query($arr);
		$json = file_get_contents($request);
		$data = json_decode($json,true);
		return $data;
	}
	
	
	/**
	 * 获取公用请求参数
	 * @return array
	 */
	private function _getBaseParam(){
		$arr = array(
			'Format'=>'JSON',
			'Version'=>'2014-11-11',
			'AccessKeyId'=>$this->config['AccessKeyId'],
			'SignatureMethod'=>'HMAC-SHA1',
			'Timestamp'=>date('Y-m-d').'T'.date('H:i:s').'Z',
			'SignatureVersion'=>'1.0',
			'SignatureNonce'=>md5(time().mt_rand(10000,99999).uniqid()),
		);
		return $arr;
	}
	
	public function getCdnState($state){
		$map = array(
				'Complete'=>'完成',
				'Refreshing'=>'刷新中',
				'Failed'=>'刷新失败',
				'Pending'=>'等待刷新'
		);
		return $map[$state];
	}
	
	
}