<?php
/**
 * K3呼叫中心 接口服务
 * @author
 *
 *
 * 	服务器地址：cc1.hxnts.com （替换文档中的192.168.11.11）
 *	UID：57ad5e5c4cedd
 *
 */
namespace Home\Service;
use Home\Service\Service;
class KcallService extends Service{
	
	protected $SERVER_ADD; //服务器地址
	protected $UID; 	   //用户名
	protected $key;		   //认证key
	
	public function __construct(){
		$this->SERVER_ADD = 'http://58.49.120.26:27777';
		$this->UID = '57ad5e5c4cedd';
		$this->UID = 'be936b2a75f01891';
		$this->key = $this->_getAuthKey();
	}
	
	//获取认证key
	protected function _getAuthKey(){
		return md5($this->UID.date('Y-m-d'));
	}
	
	//获取分机号
	public function getextinterface($exten){
		$url = $this->SERVER_ADD."/webservice/getextinterface.php?webkey=".$this->key.'&extension='.$exten.'&json=2';
		$res = curlInfo($url, $urlParams);
		$res = json_decode($res,true);
		
		return $res;
	}
	
	public function getextInfo($exten){
		$redis = service('Redis');
		$ext = $redis->hget('kcall_ext',$exten);
		if(!$ext){
			$res = $this->getextinterface($exten);
			if(!$res){
				return  false;
			}
			$ext = $res['root']['row']['0'];
			$ext = json_encode($ext);
			$redis->hset('kcall_ext',$exten,$ext);
		}
		
		return json_decode($ext,true);
	}
	
	
	/**
	 * 拨打电话接口
	 * @param string  $exten require 主叫分机号码
	 * @param string  $tel   require 被叫电话号码
	 * @return 成功         json {'errcode':0,'info':'success'}
	 * @return 失败        json {'errcode':1,'info':'失败原因'}
	 */
	public function callOut($exten,$tel){
		//$url = $this->SERVER_ADD.'/rhadmin/telservice/dial.php?exten='.$exten.'&tel='.$tel.'&uid='.$this->UID.'&key='.$this->key;
		
		$extinfo = $this->getextInfo($exten);
		$url = $this->SERVER_ADD.'/webservice/ctioriginate.php?webkey='.$this->key."&channel={$extinfo['interface']}&exten={$tel
		}&callerid={$exten}&context={$extinfo['context']}&priority=1&async=1&json=2";
		
		$res = curlInfo($url, $urlParams);
		
		return $res;
	}
	
	/**
	 * 通话状态查询
	 * @param string $exten require 分机号码
	 * @return 成功         json {'errcode':0,'info':{'createtime':'','channel':'','uniqueid':'','callerid':'','calleridname':'','direct':'','miscdata':'','miscuniqueid','','state':''}}
	 * @return 失败        json {'errcode':1,'info':'失败原因'}
	 * 
	 * direct in 呼入 out 呼出
	 * callerid 呼入手机号
	 * uniqueid 呼叫编号 调取录音
	 * 
	 */
	public function callStatus($exten){
		if(!$exten){
			$info['errcode'] = 1;
			$info['info'] = '无分机号';
			
			return $info;
		}
		$url = $this->SERVER_ADD.'/rhadmin/telservice/pop.php?exten='.$exten.'&uid='.$this->UID.'&key='.$this->key;
		
		$extinfo = $this->getextInfo($exten);
		$url = $this->SERVER_ADD.'/webservice/getpopupscreen.php?webkey='.$this->key."&channel={$extinfo['interface']}&json=2";
		
		$res = curlInfo($url, $urlParams);
		$org = serialize($res);
	 /* 	$res = '{
		    "root": {
		        "row": [
		            {
		                "createtime": "2017-03-13 14:10:54",
		                "channel": "SIP/2730-00000378",
		                "uniqueid": "1489385454.924",
		                "callerid": "15527374110",
		                "calleridname": "2730",
		                "direct": "IN",
		                "miscdata": "SIP/59620798-00000379",
		                "miscuniqueid": "1489385454.9251",
		                "dialstring": "59620798/15527374110",
		                "queuenum": "",
		                "dnid": "",
		                "agentno": "",
		                "holdtime": "0",
		                "ivridhist": "",
		                "ivrhist": "",
		                "calldata": "",
		                "state": "Ring"
		            }
		        ]
		    }
		}'; */
		$res = json_decode($res,true);
	
	//	print_r($res);
	/* 	$info = array();
		if(empty($res['root'])){
			
		}else{
			$info['errorcode'] = 0;
			$info['info'] = $res['root']['row'][0];
		} */
		
	//	print_r($url);

		$info['errcode'] = 0;
		$info['info'] = $res['root']['row'][0];
		$info['url'] = $url;
		//$info['json'] = $org;
		
		return $info;
	}
	
	public function getVedio($filename,$type=1){
		if($type == 1){
			return $this->SERVER_ADD.'/rhadmin/telservice/getmonitor.php?&type=1&key='.$this->key.'&monitor='.$filename.'&uid='.$this->UID;
		}else{
			return $this->SERVER_ADD.'/rhadmin/telservice/getmonitor.php?&type=2&key='.$this->key.'&uniqueid='.$filename.'&uid='.$this->UID;
		}
		//http://cc1.hxnts.com:8088/webservice/getmonitor.php?webkey=bd479b36ff4cb192d79c9b1cdb6b8bf7&filename=2016/11/15/OUT2730-20161115-155943-1479196783.645397.wav
	}
	
	/**
	 * 获取话单
	 * @param string $start 开始时间
	 * @param string $end   结束时间
	 * @param string $pageno 当前页
	 * @param string $pagesize 页大小
	 * @return  有则返回话单
	 * 会返回count
	 */
	public function getList($start,$end,$pageno,$pagesize){
		
		$url = $this->SERVER_ADD.'/rhadmin/telservice/getcdr.php?start_time='.urlencode($start).'&end_time='.urlencode($end).'&page_no='.$pageno.'&page_size='.$pagesize.'&uid='.$this->UID.'&key='.$this->key;
		
		$res = file_get_contents($url);
		return $res;
	}
	
	
	
	
	
	
}