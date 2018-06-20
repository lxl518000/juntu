<?php
namespace Home\Controller;

class SiteController extends BackendController {
	
	protected $name = "站点管理";
	
	protected function loadModel(){
		$this->model = D('Site');
		return $this->model;
	}
	


	protected function _format($list){
			
		$cats = D('Cate')->getField('id,title',true);
		foreach($list as $k=>$vo){
			$op = '';
	
			$op .=  getToolIcon('edit','J_open btn-xs ',U('edit',['id'=>$vo['id']]),'','','','J_open')."&nbsp;";
	
			$op .= getToolIcon('delete','J_confirm btn-xs ',U('delete',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	

			$vo['thumb'] = 	getThumbImg($vo['pic']);					
			$vo['cname'] = $cats[$vo['cid']];
	
			$vo['operate'] = $op;
			$list[$k] = $vo;
		}
		return $list;
	}
	

	//添加站点成功后 初始化添加站点默认配置
	protected function _after_insert($data){
        $now = "添加时间";
        $user = getUser();
	    $arr = [
            ['sid'=>$data['sid'],'name'=>"站点名称",'key'=>'WEB_TITLE','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'定义浏览器顶部标签显示的文字。对SEO友好。'],
            ['sid'=>$data['sid'],'name'=>"站点关键字",'key'=>'WEB_KEYWORD','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'用空格或英文的逗号 , 分割。对SEO友好'],
            ['sid'=>$data['sid'],'name'=>"站点描述",'key'=>'WEB_DESCRIPTION','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'网站简易描述，对SEO友好'],
            ['sid'=>$data['sid'],'name'=>"公司地址",'key'=>'WEB_ADDRESS','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'联系地址'],
            ['sid'=>$data['sid'],'name'=>"邮箱地址",'key'=>'WEB_EMAIL','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'邮箱地址'],
            ['sid'=>$data['sid'],'name'=>"备案号",'key'=>'WEB_COPYRIGHT','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'备案信息'],
            ['sid'=>$data['sid'],'name'=>"邮编",'key'=>'WEB_POSTCODE','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'邮编'],
            ['sid'=>$data['sid'],'name'=>"联系电话",'key'=>'WEB_PHONE','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'如400电话'],
            ['sid'=>$data['sid'],'name'=>"客服电话",'key'=>'WEB_PHONE_KEFU','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'如客服联系电话'],

        ];

    }


    
    
    
    
}