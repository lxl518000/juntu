<?php
namespace Porto\Controller;
use Think\Controller;
class BaseController extends Controller {

	
	protected $site;
	
	public function __construct(){
		parent::__construct();
		
		$this->site = ltrim($_SERVER['HTTP_HOST'],'www.');
		
		
		$config = S("cfg_{$this->site}");
		if(!$config){
			
			$find = D('site')->where(['host'=>$this->site])->find();
			if($find){
				cacheSiteConfig($find['id'], $this->site);
				$config = S("cfg_{$this->site}");
			}else{
				exit('站点域名不存在'.$this->site);
			}
		}
		
		$this->config = $config;
		$this->assign('config',$config);
	
		
		$pmenu = ACTION_NAME;
		//字典映射
		$action = CONTROLLER_NAME.'/'.$pmenu;
		$this->assign('pmenu',$action);
		
		//获取keyword和description
		$keyword = $config['menu'][$action]['keyword'] ?  $config['menu'][$action]['keyword'] : $config['config']['WEB_KEYWORD'];
		$description = $config['menu'][$action]['description'] ?  $config['menu'][$action]['description'] : $config['config']['description'];
		$title = $config['menu'][$action]['name'];
		$this->assign('keyword',$keyword);
		$this->assign('description',$description);
		$this->assign('title',$title);
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
