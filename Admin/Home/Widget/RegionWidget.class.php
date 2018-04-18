<?php 

namespace Home\Widget;
use Think\Controller;

class RegionWidget extends Controller {  
	
	
	/*
	 * 添加页面的 区域选择组件
	 * @param $type tr是以表格形式展示 用以添加页面时候调用 li是以横向列表展示 多用于搜索条件展示
	 * @param $ext 是否显示网吧编号和网吧名搜索 默认都显示
	 */
	public function RegionAddSelect($type = 'tr',$ext = 1,$tshow=0,$showName=1){
		$tpl = $type == 'tr' ? 'Public:regiontr':'Public:regionli';
		$regionModel = D('Region');
		
		$ProvinceList = $regionModel->getProvinceByAuth(); //获取可操作省份
		$CityList  = $regionModel->getCityByAuth(); //获取可操作城市
		$CountryList  = $regionModel->getCountryByAuth(); //获取可操作区
		$TownList = $regionModel->getTownByAuth();//获取可操作乡镇
		if(!empty($ext)){
			$disabled = '';
			//判断是否只可操作网吧
			$auth = session('authuser.auth');
			if(strlen($auth)>6){
				//最小权限指定到网吧
				$_REQUEST['Barcode'] = $auth;
				$_REQUEST['BarCode'] = $auth;
				$_REQUEST['Name'] = query_bar($auth);
				$disabled = 'readonly';
			}
			$this->assign('disabled',$disabled);
			
		}
		$this->assign('showName',$showName);
		$this->assign('ext',$ext); //是否显示网吧编号和网吧名选项
		$this->assign('ProvinceList',$ProvinceList);
		$this->assign('CityList',$CityList);
		$this->assign('CountryList',$CountryList);
		$this->assign('TownList',$TownList);
		$this->assign('tshow',$tshow);
		$this->display($tpl);
	}
	
	
	/**
	 * 编辑页面的  地区选择组件 
	 * @param 当前的区域代码
	 * @param unknown $RegionCode
	 */
	public function RegionReadSelect($RegionCode,$ext=1,$tshow=0,$type='tr'){
		$tpl = $type == 'tr' ? 'Public:regiontr':'Public:regionli';
		
		//获取省市信息
		$RegionModel = D('Region');
		
		//获取可操作省份
		if(empty($RegionCode)){
			$ProvinceList = $RegionModel->getProvinceByAuth(); //获取可操作省份
			$cityList  = $RegionModel->getCityByAuth(); //获取可操作城市
			$countryList  = $RegionModel->getCountryByAuth(); //获取可操作区
			$TownList = $RegionModel->getTownByAuth();//获取可操作乡镇
		}else{
			if(strlen($RegionCode)>=2){
				$_REQUEST['province'] = substr($RegionCode, 0,2);
			}
			if(strlen($RegionCode)>=4){
				$_REQUEST['city'] = substr($RegionCode, 0,4);
			}
			if(strlen($RegionCode)>=6){
				$_REQUEST['country'] = substr($RegionCode, 0,6);
			}
			if(strlen($RegionCode)>=9){
				$_REQUEST['town'] = substr($RegionCode, 0,9);
			}
			$ProvinceList = $RegionModel->getProvinceByAuth();
			$cityList = $RegionModel->getCityListByRegionCode($RegionCode);
			$countryList = $RegionModel->getCountryListByRegionCode($RegionCode);
			$TownList = $RegionModel->getTownListByRegionCode($RegionCode);
		}
	//	dump($cityList);
		
	
	
		$this->assign('ProvinceList',$ProvinceList);
		$this->assign('CountryList',$countryList);
		$this->assign('CityList',$cityList);
		$this->assign('TownList',$TownList);
		$this->assign('tshow',$tshow);
		$barShow = strlen($RegionCode)>4?'':'none';//用以控制是否显示网吧编号
        if(strlen($RegionCode)>6){
        	$_REQUEST['BarCode'] = $RegionCode;
        }
        $this->assign('ext',$ext);
		$this->assign('barShow',$barShow);
		$this->assign('RegionCode',$RegionCode);
		$this->display($tpl);
	}
	
	/*
	 * 获取省份下拉框
	 */
	public function Province(){
		//获取省市信息
		$RegionModel = gM('Region');
		//获取可操作省份
		$ProvinceList = $RegionModel->getProvinceByAuth();
		$this->assign('ProvinceList',$ProvinceList);
		$RegionCode = session('authuser.auth');
		$this->assign('RegionCode',$RegionCode);
		$this->display("public:province");
	}
	
	/**
	 * 获取下级多选框
	 */
	public function sonbox(){
		$RegionModel = gM('Region');
		$auth = session('authuser.auth');
		$list = $RegionModel->where(array('ParentCode'=>$auth))->select();
		if(empty($list)){
			$list[] = array('RegionCode'=>$auth,'RegionName'=>getRegionInfo($auth,'country'));
		}
		$this->assign('list',$list);
		$this->display('public:sonbox');
	}
	
	
	/**
	 * 获取市级多选框
	 */
	public function Citybox(){
		$RegionModel = gM('Region');
		$auth = session('authuser.auth');
		if(strlen($auth) != 4){
			return false;
		}
		
		$cityList = $RegionModel->where(array('ParentCode'=>$auth))->select();
		
		$this->assign('list',$cityList);
		$this->display('public:citybox');
	}
	
}

?>