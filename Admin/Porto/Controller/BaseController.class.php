<?php
namespace Porto\Controller;
use Think\Controller;
class BaseController extends Controller {

	
	protected $site;
	
	public function __construct(){
		parent::__construct();
		
		$this->site = ltrim($_SERVER['HTTP_HOST'],'www');
		
		
		$config = S("cfg_{$this->site}");
		if(!$config){
			echo '拉取系统配置失败，请联系管理员在后台生成缓存';
			exit;
		}
		
		$this->config = $config;
		$this->assign('config',$config);
	
		
		$pmenu = ACTION_NAME;
		
		//字典映射
		//$map = ['about'];
		
		
		
		$this->assign('pmenu',CONTROLLER_NAME.'/'.$pmenu);
	}
	
	
	/**
	 * 获取顶级产品分类
	 */
	protected function getTopCate(){
		$list = S("{$this->site}:TOP_CATE");
		
		if($list){
			return $list;
		}
		
		$list = D('cate')->where(['pid'=>0])->select();

		S("{$this->site}:TOP_CATE",$list,3600);
		
		return $list;
	}
	
	
	
	
	
	/*
	 * 获取所有分类
	 */
	protected  function getAllCate(){
		$list = S("{$this->site}:ALL_CATE");
		if($list){
		
			return $list;
		}
		$list = D('cate')->where(['status'=>1])->select();
		$rs = S("{$this->site}:ALL_CATE",$list,3600);
	
		return $list;
	}

}
