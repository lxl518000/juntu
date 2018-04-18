<?php
namespace Home\Service;
use Home\Service\Service;
class AmapService extends Service{
	
	//企业key  92124cdc3eadc74433d01a1a98ebac72
	//个人key 4972c93f5aee294088838e3038ff2d8c
	
	private static $api = 'http://restapi.amap.com/v3/geocode/geo?key=92124cdc3eadc74433d01a1a98ebac72&address={address}&city={city}';
	
	/**
	 * 批量获取经纬度坐标
	 */
	public function run(){
		$map = array(
			'_string'=>' Address<>"" and Address is NOT NULL and (x IS NULL OR x="") ',
		);
		$data = D('place')->field('id,Address,region')->where($map)->select();
		//echo M()->_sql();exit;
		$m = D('place');
		foreach ($data as $value){
			$location = $this->getLocation($value['region'], $value['Address']);
			if($location){
				$where = array(
					'id'=>$value['id']
				);
				$save = array('x'=>$location['x'],'y'=>$location['y']);
				$m->where($where)->save($save);
			}
		}
	}
	
	/**
	 * 获取网吧的经纬度坐标
	 * @param string $Barcode
	 * @param string $address
	 */
	public function getLocation($Barcode,$address){
		$return = array();
		if(!$Barcode || !$address){
			return $return;
		}
		$cityCode = substr($Barcode, 0,4);
			
		$city = D('region')->where(array('RegionCode'=>$cityCode))->getField('RegionName');
			
		if($city && $address){
			$url = strtr(self::$api, array('{address}'=>$address,'{city}'=>$city));
			$content = file_get_contents($url);
			$res = json_decode($content,true);
			//dump($res);exit;
			if($res && $res['status'] == 1){
				$location = $res['geocodes'][0]['location'];
				list($x,$y) = explode(',',$location);
				$return = array('x'=>$x,'y'=>$y);
			}
		
		}
		return $return;
	}
	
	/**
	 * 通过经纬度获取地址
	 * @param 经度 $lat
	 * @param 纬度 $lon
	 * @return string
	 */
	public function getAddress($lat,$lon){
		$api = 'http://restapi.amap.com/v3/geocode/regeo?key=92124cdc3eadc74433d01a1a98ebac72&location='.$lat.','.$lon.'&radius=1000&extensions=all&batch=false&roadlevel=1';
		
		$res = file_get_contents($api);
		$data = json_decode($res,true);
		if($data['status']){
			return $data['regeocode']['formatted_address'];
		}
		return '';
	}
	
	
}