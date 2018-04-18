<?php
/**
 * 解析log生成统计数据
 * @author
 *
 */
namespace Home\Service;
use Home\Service\Service;
class LogService extends Service{
	
	private $okKeySuccess;
	private $okKeyError;
	private $cameraSuccess;
	private $cameraError;
	private $cameraTotal;
	private $today;
	private $redis;
	private $faceKey;
	
	private $face = array(
			'faceid_detect',
			'faceid_verify',
			'facepp_/detection/detect',
			'facepp_/faceset/add_face',
			'facepp_/recognition/compare',
			'facepp_/recognition/search',
			'facepp_/train/search',
	);
	
	private  $faceType = array('success','error','all');
	
	function __construct(){
		$this->today = date('Y-m-d');
		$this->okKeySuccess = 'okKeySuccess_'.$this->today;	
		$this->okKeyError = 'okKeyError_'.$this->today;
		$this->cameraError = 'cameraError_'.$this->today;
		$this->cameraSuccess = 'cameraSuccess_'.$this->today;
		$this->cameraTotal = 'cameraTotal_'.$this->today;
		$this->faceKey = 'facekey_';
		$this->redis = service('Redis');
	}
	
	/**
	 * 获取所有face调用接口
	 * @return array
	 */
	public function getAllFaceKey(){
		$return = array();
		foreach ($this->face as $face){
			foreach($this->faceType as $type){
				$return[] = $face.'_'.$type;
			}
		}
		return $return;
	}
	
	/**
	 * 设置face调用次数+1
	 * @param string $face
	 * @param string $type
	 * @example setFaceCount('faceid_detect','success');
	 */
	public function setFaceCount($face,$type,$date,$val=1){
		$key = $face.'_'.$type;
		$this->redis->hIncrBy($this->faceKey.$date,$key,$val);
	}
	
	/**
	 * 获取face接口调用次数
	 * @param string Ymd $date
	 * @param string $Key 没有就返回hash所有值 
	 * 
	 */
	public function getFaceCount($date,$key){
		if($key){
			return $this->redis->hget($this->faceKey.$date,$key);
		}
		return $this->redis->hGetAll($this->faceKey.$date);
	}
	
	public function updateRedisLog(){
		$s = strtotime('20151105');
		$allKey = $this->face;
		for($i=$s;$i<=time();$i+=86400){
			foreach ($allKey as $kk){
				foreach ($this->faceType as $type){
					$oldKey = $kk.'_'.date('Ymd',$i).'_'.$type;
					//$val = $this->redis->get($oldKey);
					$this->redis->remove($oldKey);
					//$this->setFaceCount($kk, $type, date('Ymd',$i),$val);
				}
			}
		}
	}
	
	
	/**
	 * 写入统计日志
	 */
	public function doLog(){
		$data = $this->_getDayIdCard();
		$sql = " insert into `tb_upload_stat` (date,Barcode,ok1,ok0,camerasuccess,cameraerror,cardcount,cameracount) values  ";
		$str = $dot = '';
		if($data){
			foreach($data as $key=>$val){
				$Barcode = $key;
				$ok1 = $this->_getOK1($Barcode) ? $this->_getOK1($Barcode): 0;
				$ok0 = $this->_getOk0($Barcode) ? $this->_getOk0($Barcode) : 0;
				$cameraSuccess = $this->_getCameraSuccess($Barcode) ? $this->_getCameraSuccess($Barcode) : 0;
				$cameraError = $this->_getCameraError($Barcode) ? $this->_getCameraError($Barcode) : 0;
				$cardcount = intval($val);
				$cameraCount = $this->_getCameraTotal($Barcode) ? $this->_getCameraTotal($Barcode) :0;
				$str .= $dot."( '{$this->today}','{$Barcode}','{$ok1}','{$ok0}','{$cameraSuccess}','{$cameraError}','{$cardcount}','{$cameraCount}' )";
				$dot = ',';
			}
			M()->execute($sql.$str);
			
			$this->redis->remove($this->okKeyError);
			$this->redis->remove($this->okKeySuccess);
			$this->redis->remove($this->cameraError);
			$this->redis->remove($this->cameraSuccess);
			$this->redis->remove($this->cameraTotal);
		}
	}
	
	/**
	 * 获取当日的网吧身份证总数
	 */
	private function _getDayIdCard(){
		$return = array();
		$today = $this->today;
		$sql = " SELECT COUNT(DISTINCT(IdCard)) AS t, DATE_FORMAT(Logintime,'%Y-%m-%d') as d,Barcode FROM tb_clientonline WHERE IdCard<>'' AND IdCard IS NOT NULL GROUP BY Barcode,d HAVING d='{$today}'";
		
		$res = M()->query($sql);
		if($res){
			foreach ($res as $val){
				$return[$val['Barcode']] = $val['t'];
			}
		}
		return $return;
	}
	
	/**
	 * 图片是ok=1的统计数加1
	 * @param unknown $data
	 */
	public function setOk1($Barcode){
		$this->redis->hIncrBy($this->okKeySuccess,$Barcode);
	}
	
	/**
	 * 图片是ok=0的统计数加1
	 * @param unknown $data
	 */
	public function setOk0($Barcode){
		$this->redis->hIncrBy($this->okKeyError,$Barcode);
	}
	
	/**
	 * 身份证拍照成功
	 * @param array $data
	 */
	public function setCameraSuccess($Barcode){
		$this->setCameraTotal($Barcode);
		$this->redis->hIncrBy($this->cameraSuccess,$Barcode);
	}
	
	public function setCameraTotal($Barcode){
		$this->redis->hIncrBy($this->cameraTotal,$Barcode);
	}
	
	private function _getCameraTotal($Barcode){
		return $this->redis->hget($this->cameraTotal,$Barcode);
	}
	
	/**
	 * 身份证拍照失败
	 * @param array $data
	 */
	public function setCameraError($Barcode){
		$this->setCameraTotal($Barcode);
		$this->redis->hIncrBy($this->cameraError,$Barcode);
	}
	
	private function _getCameraSuccess($Barcode){
		return $this->redis->hget($this->cameraSuccess,$Barcode);
	}
	
	private function _getCameraError($Barcode){
		return $this->redis->hget($this->cameraError,$Barcode);
	}
	
	private function _getOK1($Barcode){
		return $this->redis->hget($this->okKeySuccess,$Barcode);
	}

	private function _getOk0($Barcode){
		return $this->redis->hget($this->okKeyError,$Barcode);
	}
	
	
	
	
	
	
}