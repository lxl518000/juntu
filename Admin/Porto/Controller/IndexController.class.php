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
		$this->assign('news',$news);
		$this->assign('news1',$news1);
		$this->assign('ppt',$ppt);
		$this->assign('acname','index');
		$this->display();

    }
    
	
    

    
    
}
