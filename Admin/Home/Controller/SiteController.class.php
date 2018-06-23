<?php
namespace Home\Controller;

class SiteController extends BackendController {
	
	protected $name = "站点管理";
	
	protected function loadModel(){
		$this->model = D('Site');
		return $this->model;
	}

	public function copy(){
		
		if(IS_POST){
			$sid = I('sid');
			if(!$sid){
				$this->error('无效的源目标');
			}
			 if (false === $data = D('Site')->create ()) {
                   $this->error($this->model->getError());
            }
             $newid=D('Site')->add ($data);
			if(!$newid){
				$this->error('添加失败');
			}
			$config = D('SiteConfig')->where(['sid'=>$sid])->select();
			$menu = D('SiteMenu')->where(['sid'=>$sid])->select();
			foreach($config as $k=>$v){
				$config[$k]['sid'] = $newid;
				unset($config[$k]['id']);
			}
			foreach($menu as $k=>$v){
				$menu[$k]['sid'] = $newid;
				unset($menu[$k]['id']);
			}			
			D('SiteConfig')->addAll($config);
			D('SiteMenu')->addAll($menu);
			
			$this->success('复制成功');
			
		}
		
		$this->display();
	}

    protected function _format($list){
			
		$cats = D('Cate')->getField('id,title',true);
		foreach($list as $k=>$vo){
			$op = '';
	
			$op .=  getToolIcon('edit','J_open btn-xs ',U('edit',['id'=>$vo['id']]),'','','','J_open')."&nbsp;";
	
			//$op .= getToolIcon('delete','J_confirm btn-xs ',U('delete',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";

			$op .= getToolIcon('','J_open btn-xs btn btn-success',U('SiteConfig/index',['sid'=>$vo['id']]),'基础配置','wrench','','J_open')."&nbsp;";


            $op .= getToolIcon('','J_open btn-xs btn btn-danger',U('SiteMenu/index',['sid'=>$vo['id']]),'导航配置','duplicate','','J_open')."&nbsp;";;



            $op .= getToolIcon('','J_confirm btn-xs btn-primary ',U('cache',['sid'=>$vo['id'],'host'=>$vo['host']]),'更新站点缓存','thumbs-up','','J_confirm')."&nbsp;";

            
            $op .= getToolIcon('','J_confirm btn-xs btn-warning ',U('copy',['sid'=>$vo['id'],'host'=>$vo['host']]),'复制站点配置','copyright-mark','','J_open');
            
			$vo['thumb'] = 	getThumbImg($vo['pic']);					
			$vo['cname'] = $cats[$vo['cid']];
			$vo['jump'] = "<a href='//{$vo['host']}' target='_blank'>{$vo['host']}</a>";
			$vo['operate'] = $op;
			$list[$k] = $vo;
		}
		return $list;
	}

	public  function cache(){
        $sid = I('sid');
        $host = I('host');
        if(!$sid || !$host){
            $this->error('请选择更新的站点');
        }

      
        $res = cacheSiteConfig($sid, $host);
        if(!$res){
            $this->error('缓存失败');
        }

        $this->success('缓存成功');

    }

	

	//添加站点成功后 初始化添加站点默认配置
	protected function _after_insert($data){
        $now = date('Y-m-d H:i:s');
        $user = getUser();
	    $arr = [
            ['sid'=>$data['id'],'name'=>"站点名称",'key'=>'WEB_TITLE','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'定义浏览器顶部标签显示的文字。对SEO友好。'],
            ['sid'=>$data['id'],'name'=>"站点关键字",'key'=>'WEB_KEYWORD','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'用空格或英文的逗号 , 分割。对SEO友好'],
            ['sid'=>$data['id'],'name'=>"站点描述",'key'=>'WEB_DESCRIPTION','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'网站简易描述，对SEO友好'],
            ['sid'=>$data['id'],'name'=>"公司地址",'key'=>'WEB_ADDRESS','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'联系地址'],
            ['sid'=>$data['id'],'name'=>"邮箱地址",'key'=>'WEB_EMAIL','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'邮箱地址'],
            ['sid'=>$data['id'],'name'=>"备案号",'key'=>'WEB_COPYRIGHT','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'备案信息'],
            ['sid'=>$data['id'],'name'=>"邮编",'key'=>'WEB_POSTCODE','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'邮编'],
            ['sid'=>$data['id'],'name'=>"联系电话",'key'=>'WEB_PHONE_CONTACT','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'如400电话'],
            ['sid'=>$data['id'],'name'=>"客服电话",'key'=>'WEB_PHONE_KEFU','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'多个逗号分开'],
            ['sid'=>$data['id'],'name'=>"联系QQ",'key'=>'WEB_QQ_KEFU','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'多个逗号分开'],
            ['sid'=>$data['id'],'name'=>"联系Skype",'key'=>'WEB_SKYPE_KEFU','value'=>'','addtime'=>$now,'adduser'=>$user,'remark'=>'多个逗号分开'],
        ];
	    D('SiteConfig')->addAll($arr);

    }


    
    
    
    
}