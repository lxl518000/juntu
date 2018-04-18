<?php
namespace Home\Model;
use Think\Model;
class RegionModel extends Model{
	protected $tableName = 'region';
	
	// protected $connection = 'CENTER_CONFIG';
	
	/**
	 * 通过上级code查找区域数据 查询列表
	 * @param string $code
	 * @return array
	 */
	public function getRegionByParentCode($code=''){
		$map = array(
			'ParentCode'=>$code
		);
	
		//基础区域过滤		
		$map['RegionCode'] = listAreaFilter();
		return $this->where($map)->select();
	}
	
	/**
	 * 通过地址code查询地区数据  查询一条
	 * @param string $code
	 * @return boolean|Ambigous 
	 */
	public function getRegionByCode($code){
		if (!$code){
			return false;
		}
		return $this->where(array('RegionCode'=>$code))->find();
	}
	
	/**
	 * 获取省份信息
	 * @return array
	 */
	public function getProvinceList(){
		return $this->where(array('ParentCode'=>""))->select();
	}
	
	
	/**
	 * 更新区域缓存
	 */
	public function region(){
		return ;
		$redis = service('Redis');
		$list = gM('Region')->getField('RegionCode,RegionName',true);
		foreach($list as $k=>$v){
			$redis->hset('REGION_MAP',$k,$v);
		}
	}
	

	/**
	 * 根据管理地区获取省份列表
	 * @see 为级联所用
	 */
	public function getProvinceByAuth($regionCode){
		$auth = session('adminuser.auth');
		$region = session('adminuser.region');
		$where = array();
		if(strlen($region)>=2){
			$code = substr($region, 0,2);
			if($code){
				$where['RegionCode'] = $code;
			}
			// $where['RegionCode'] = $region;
			
		}
	
		if(empty($auth)){
			$where['ParentCode'] = '';
		}else{
			$code = substr($auth, 0,2);
			if($code){
				$where['RegionCode'] = $code;
			}
		}
		
		return $this->where($where)->select();
		// dump(D()->_sql());
	}
	
	/**
	 * 根据管理地区获取城市列表
	 * @see 为级联所用
	 */
	public function getCityByAuth($regionCode){
		$auth = session('adminuser.region');

		if(!empty($_REQUEST['province'])){
			if(strlen($auth)>2){
				$where['RegionCode'] = substr($auth, 0,4);
			}else{
				$where['RegionCode'] = listAreaFilter();
			}
			
			$where['ParentCode'] = $_REQUEST['province'];
			return $this->where($where)->select();
		}
		
		
		if(!empty($auth)){
			if(strlen($auth) ==2){
				$where['ParentCode'] = $auth; 
			}
			
			if(strlen($auth)>= 4 ){
				$code = substr($auth, 0,4);
				
				if($code){
					$where['RegionCode'] = $code; //管理指定的市
				}
			}
			
			return $this->where($where)->select(); 
			// dump(D()->_sql());
		}
		
	}
	
	/**
	 * 根据管理地区获取地区列表
	 * @see 为级联所用
	 */
	public function getCountryByAuth($regionCode){
		$auth = session('adminuser.region');
		$region = session('adminuser.region');
		$where = array();
	
		if(!empty($_REQUEST['city'])){
			$where['RegionCode'] = listAreaFilter();
			$where['ParentCode'] = $_REQUEST['city'];
			return $this->where($where)->select();
		}
		
		if(!empty($auth)){
			
			if(strlen($auth) == 2){
				return null;
			}
			
			if(strlen($auth) == 4){
				$where['ParentCode'] = $auth;
					
			}
			if(strlen($auth)>=6){
				$code = substr($auth, 0,6);
				if($code){
					$where['RegionCode'] = $code;
					
				}
			}
			return $this->where($where)->select();
			
		}
		
	}
	
	public function getTownByAuth($regionCode){

	$auth = session('adminuser.region');
	$region = session('adminuser.region');
	$where = array();
			
		if(!empty($_REQUEST['country'])){
			
			$where['RegionCode'] = listAreaFilter();
			$where['ParentCode'] = $_REQUEST['country'];
			
			return $this->where($where)->select();
				
		}
		
		if(!empty($auth)){
		
			if(strlen($auth) == 2||strlen($auth) == 4){
				return null;
			}
		
			if(strlen($auth) == 6){
				$where['ParentCode'] = $auth;
					
			}
			
			if(strlen($auth)>=9){
				$code = substr($auth,0,9);
			
				if($code){
					$where['RegionCode'] = $code;
						
				}
			}
			
			
			
			
		
		
			return $this->where($where)->select();
		
		}
	}
		
	/**
	 * 根据RegionCode和auth获取市级信息
	 * @param int $RegionCode
	 * @return array 
	 */
	public function getCityListByRegionCode($RegionCode){
		$code = substr($RegionCode,0,2);
		if(empty($code)){
			return false;
		}
		$where['ParentCode'] = $code;
		$auth = session('adminuser.region');
		$region = session('adminuser.region');
	

		if(strlen($region)>=4){
			$code = substr($region, 0,4);
			if($code){
				$where['RegionCode'] = $code;
			}
			$where['ParentCode'] = $_REQUEST['province'];
			// dumo($_REQUEST['province']);
			// $where['RegionCode'] = $region;
		
		}
		if(!empty($auth)){
			if(strlen($auth) ==2){
				$where['ParentCode'] = $auth;
			}
				
			if(strlen($auth)>= 4 ){
				$code = substr($auth, 0,4);
				if($code){
					$where['RegionCode'] = $code; //管理指定的市
				}
			}
				
		}
		return $this->where($where)->select();
	}
	
	/**
	 * 根据RegionCode和auth获取市级信息
	 * @param int $RegionCode
	 * @return array
	 */
	public function getCountryListByRegionCode($RegionCode){
		if(strlen($RegionCode) == 2){
			return null;
		}
		$region = session('adminuser.region');
	

		if(strlen($region)>=6){
			$code = substr($region, 0,6);
			if($code){
				$where['RegionCode'] = $code;
			}
			$where['ParentCode'] = $_REQUEST['city'];
			// dumo($_REQUEST['province']);
			// $where['RegionCode'] = $region;
		
		}
	
		$code = substr($RegionCode,0,4);
		$where['ParentCode'] = $code;
		$auth = session('adminuser.region');
		if(!empty($auth)){
				
// 			if(strlen($auth) == 2){
				
// 			}
				
			if(strlen($auth) == 4){
				$where['ParentCode'] = $auth;
					
			}
			if(strlen($auth)>=6){
				$code = substr($auth, 0,6);
				if($code){
					$where['RegionCode'] = $code;
						
				}
			}
		}
		return $_RegionCodeList = gM("Region")->where($where)->select();
	}

public function getTownListByRegionCode($RegionCode){
	if(strlen($RegionCode) == 2){
		return null;
	}
	$region = session('adminuser.region');


	if(strlen($region)>=9){
		$code = substr($region, 0,9);
		if($code){
			$where['RegionCode'] = $code;
		}
		$where['ParentCode'] = $_REQUEST['country'];
		// dumo($_REQUEST['province']);
		// $where['RegionCode'] = $region;
	
	}

	$code = substr($RegionCode,0,6);
	$where['ParentCode'] = $code;
	$auth = session('adminuser.region');
	if(!empty($auth)){
			
// 			if(strlen($auth) == 2){
			
// 			}
			
		if(strlen($auth) == 6){
			$where['ParentCode'] = $auth;
				
		}
		if(strlen($auth)>=9){
			$code = substr($auth, 0,9);
			if($code){
				$where['RegionCode'] = $code;
					
			}
		}
	}
	return $_RegionCodeList = gM("Region")->where($where)->select();
}

	
}