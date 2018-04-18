<?php 
/*
 * 自动完成挂件
 * @author liufei
 * @time   2015-11-17
 */
namespace Home\Widget;
use Think\Controller;

class AutoWidget extends Controller {  
	
	/**
	 * 自动完成
	 */
	public function autoobj($url,$callback='',$hideName="barcode",$width='600px',$default=''){
	
		$this->assign('class',$class);
		$this->assign('width',$width);
		$this->assign('callback',$callback);
		$this->assign('default',$default);
		$this->assign('url',$url);
		$this->assign('hideName',$hideName);
		$this->display('Widget/autoobj');
	}
	
	/**
	 * 自动完成
	 */
	public function autobar($url,$callback='',$hideName="barcode",$width='600px',$default=''){
		
		$this->assign('class',$class);
		$this->assign('width',$width);
		$this->assign('callback',$callback);
		$this->assign('default',$default);
		$this->assign('url',$url);
		$this->assign('hideName',$hideName);
		$this->display('Widget/autobar');
	}
	
	/**
	 * 公司自动完成
	 */
	public function autoCompany($url,$callback='',$hideName="cid",$width='600px',$default=''){
	
		$this->assign('class',$class);
		$this->assign('width',$width);
		$this->assign('callback',$callback);
		$this->assign('default',$default);
		$this->assign('url',$url);
		$this->assign('hideName',$hideName);
		$this->display('Widget/autoCompany');
	}
	
	
	/**
	 * 客服自动完成
	 */
	public function automen($url,$callback='',$hideName="passto",$width='200px'){
		
		$this->assign('class',$class);
		$this->assign('width',$width);
		$this->assign('callback',$callback);
		$this->assign('url',$url);
		$this->assign('hideName',$hideName);
		$this->display('Widget/automen');
	}
	/**
	 * 我的工单自动完成
	 */
	public function automenws($url,$callback='',$hideName="passto",$width='110px'){
		
		$this->assign('class',$class);
		$this->assign('width',$width);
		$this->assign('callback',$callback);
		$this->assign('url',$url);
		$this->assign('hideName',$hideName);
		$this->display('Widget/automenws');
	}

	public function automenwl($url,$callback='',$hideName="passto",$width='110px'){
		
		$this->assign('class',$class);
		$this->assign('width',$width);
		$this->assign('callback',$callback);
		$this->assign('url',$url);
		$this->assign('hideName',$hideName);
		$this->display('Widget/automenwl');
	}
	
	/**
	 * 公司自动完成
	 */
	public function company($url,$callback='',$hideName="RegionCode",$width='400px;',$class='dfinput'){
		
		$this->assign('class',$class);
		$this->assign('width',$width);
		$this->assign('callback',$callback);
		$this->assign('url',$url);
		$this->assign('hideName',$hideName);
		$this->display('Widget/autoCompany');
	}
	
	
	/**
	 * 代理商自动完成
	 */
	public function agent($url,$callback='',$hideName="uid",$width='400px;',$class='dfinput'){
	
		$this->assign('class',$class);
		$this->assign('width',$width);
		$this->assign('callback',$callback);
		$this->assign('url',$url);
		$this->assign('hideName',$hideName);
		$this->display('Widget/autoagent');
	}
	
	
/**
	 * 自动完成
	 */
	public function snauto($url,$callback='',$hideName="RegionCode",$width='518px;',$class='dfinput'){
		
		$this->assign('class',$class);
		$this->assign('width',$width);
		$this->assign('callback',$callback);
		$this->assign('url',$url);
		$this->assign('hideName',$hideName);
		$this->display('Widget/sncomplete');
	}
	
}

?>