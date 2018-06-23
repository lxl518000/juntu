<?php
namespace Porto\Controller;

class IndexController extends BaseController {

	
	public function index(){
		//获取推荐的最新产品
		$where = array();
		$where['status'] = array('eq',1);
		$where['iscommend'] = array('eq',1);
		$products = D('product')->where($where)->order('addtime desc')->limit(32)->select();
		$products = array_chunk($products, 8);
		
		//获取全部分类
		$allcate = $this->getAllCate();
		
		//获取顶级产品分类
		$topcate = $this->getTopCate();
		
		//获取ppt
		$ppt = $this->config['config']['WEB_PPT'];
		
		if($ppt){
			$ppt = explode(';', $ppt);
		}
	
		$this->assign('ppt',$ppt);
		$this->assign('topcate',$topcate);
		$this->assign('allcate',$allcate);
		$this->assign('products',$products);
		$this->assign('ppt',$ppt);
		$this->assign('acname','index');
		$this->display();
    }
    
    public function about(){
    	$this->display();
    }
	
    public function products(){
    	$cid = $_REQUEST['cid'];
    	//获取分类信息
    	$catModel = D('cate');
    	if($cid){
    		$cinfo = $catModel->where("id={$cid}")->find();
    		$cpid = $cinfo['pid'];
    		 $ids = array();
		     //获取子类id
		     $ids = $catModel->where("pid={$cid}")->getField('id',true);
		     $ids[] = $cid;
    		$where['cid'] = array('in',$ids);
    		$this->assign('cinfno',$cinfo);
    		$this->assign('keyword',$cinfo['keyword']);
    		$this->assign('description',$cinfo['desc']);
    	}
    	
    	$products = $this->getAllCate();
    	
    	foreach($products as $k=>$v){
    		if($cid == $v['id'] || $v['id'] == $cinfo['pid']){
    			$v['on'] = 'on';
    		}else{
    			$v['on'] = '';
    		}
    		$products[$k] = $v;
    	}
    	
    	$products = list_to_tree($products,'id','pid','_child');
    	
    	$this->assign('products',$products);
    	
    	if(isset($_REQUEST['keyword']))
    	{
    		$keyword = I('keyword');
    		$where['_string'] = "name like '%{$keyword}%'";
    	}
    	
    	$order = 'addtime desc';
    	
    	//获取列表
    	$totalCount = D('product')->where($where)->count();
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	//import('ORG.Util.Page');// 导入分页类
    	//echo APP_PATH."Extend/Tool/Page.php";exit;
    	require_once APP_PATH."Extend/Tool/Page.php";
    	$Page  = new \Page($totalCount,12);// 实例化分页类 传入总记录数和每页显示的记录数
    	
    	$plist =  D('product')->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
    	$show = $Page->show();// 分页显示输
    	

    	$this->assign('show',$show);
    	$this->assign('plist',$plist);
    	
    	$this->assign('acname','products');
    	$this->assign('cid',$_GET['cid']);
    	$this->display();
    }
    
    public function pinfo(){
    	$pid = $_REQUEST['pid'];
    	if(empty($pid)){
    		redirect('products');
    	}
    	$pinfo = D('product')->where("id={$pid}")->find();
    	$cid = $_REQUEST['cid'] = $pinfo['cid'];
    	//获取分类信息
    	if($cid){
    		$cinfo = D('cate')->where("id={$cid}")->find();
    		$cpid = $cinfo['c_pid'];
    		$pos[] = $cinfo;
    		
    		$where['cid'] = array('in',$ids);
    		$this->assign('cinfno',$cinfo);
    	}
    	
    	$products = $this->getAllCate();
    	 
    	foreach($products as $k=>$v){
    		if($cid == $v['id'] || $v['id'] == $cinfo['c_pid']){
    			$v['on'] = 'on';
    		}else{
    			$v['on'] = '';
    		}
    		$products[$k] = $v;
    	}
    	 
    	$products = list_to_tree($products,'id','pid','_child');
    	 
    	$this->assign('products',$products);
    	 
    	$this->assign('pinfo',$pinfo);
    	//获取上一个下一个
    	$next = D('product')->where(['cid'=>$pinfo['cid'],'addtime'=>['lt',$pinfo['addtime']]])->order('addtime desc')->limit(1)->find();
    	$prev = D('product')->where(['cid'=>$pinfo['cid'],'addtime'=>['gt',$pinfo['addtime']]])->order('addtime desc')->limit(1)->find();
    	
   
    	$this->assign('keyword',$pinfo['keyword']);
    	$this->assign('description',$pinfo['description']);
    	$this->assign('next',$next);
    	$this->assign('prev',$prev);
    	$this->assign('acname','products');
    	$this->assign('cid',$pinfo['cid']);
    	$this->display();
    }
    
    public function qualification(){
    	
    	$this->display();
    }
    
    public function business(){
    	$this->display();
    }
    
    public function contact(){
    	
    	$this->display();
    }

    
    
}
