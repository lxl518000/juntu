<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 微信接口
 * @author liufei
 *
 */

class ApiWxController extends Controller {
	public function __construct(){
		parent::__construct();
		$this->txtModel = D('wx_text');
	}
	
	public function getScense(){
		$id = I('id');
		$openid = I('openid');
		$id = trim($id,'qrscene_');
		$pid = 0;
		if(!$id){
			$id = 0;
		}else{
			$pid = D('qrcode')->where(array('id'=>$id))->getField('pid');
		}
		$date = date('Y-m-d');
		$sql = "insert into tb_qrcode_tongji (`qid`,`date`,`num`,`pid`) values('{$id}','{$date}','1','{$pid}')  on duplicate key update  num=num+1";
		D()->execute($sql);
		
		$sql = "insert into tb_qrcode_count(`openid`,`qid`,`pid`,`first`,`num`) values('{$openid}','{$id}','{$pid}','{$date}','1')  on duplicate key update  num=num+1";

		D()->execute($sql);
		
		
		$root = "http://jr.app.te6.com/";
		$keyword = '图文';
		$info = $this->txtModel->where(array('b_keyword'=>$keyword))->order('id desc')->limit(5)->select();
		$datas = array();
		$datas['type'] = 'list';
		foreach ($info as $k=>$v){
			$datas['info'][$k]['Title'] = $v['b_title'];
			$datas['info'][$k]['Description'] = trim($v['content']);
			$datas['info'][$k]['PicUrl'] =  $root.$v['b_path'];
			$datas['info'][$k]['Url'] = $v['b_tourl'];
		}
		
		echo json_encode($datas,JSON_UNESCAPED_UNICODE);
		exit;
		
		$content = $this->txtModel->where(array('b_typeid'=>5))->getField('content');
	
		echo $content;
		exit;
	
	}
	
	
	
	//文本消息
	public function getText(){
		$keyword = I('keyword');
		
		//通过关键字去查找消息类型
		$type = $this->txtModel->where(array('b_keyword'=>$keyword))->find();
		$root = "http://jr.app.te6.com/";
		switch ($type['b_typeid']){
			case 1:  //文本
				$info = $this->txtModel->where(array('b_keyword'=>$keyword))->find();
				$datas = array();
				$datas['type'] = 'text';
					
				$datas['info']	= trim($info['content']);	
				break;
			case 2:	//图文
				$info = $this->txtModel->where(array('b_keyword'=>$keyword))->order('id desc')->limit(5)->select();
				$datas = array();
				$datas['type'] = 'list';
				foreach ($info as $k=>$v){
					$datas['info'][$k]['Title'] = $v['b_title'];
					$datas['info'][$k]['Description'] = trim($v['content']);
					$datas['info'][$k]['PicUrl'] =  $root.$v['b_path'];
					$datas['info'][$k]['Url'] = $v['b_tourl'];
				}
				
				break;
			case 3:  //音乐消息
				echo "音乐消息";
				break;	
		
			default:  //如果不存在,就自动回复
				$datas = array();
				$datas['type'] = 'text';
				$datas['info']	= '试试其他关键字吧!';
		}
		
		echo json_encode($datas,JSON_UNESCAPED_UNICODE);
		
	}
	
	
	//首次关注
	public function getSubscribe(){
		$openid = I('openid');
	//	file_put_contents(ROOTPATH.'openid.txt', 'openid:'.$openid."\r\n",FILE_APPEND);
		$content = $this->txtModel->where(array('b_typeid'=>5))->getField('content');
		$date = date('Y-m-d');
		$sql = "insert into tb_qrcode_tongji (`qid`,`date`,`num`) values('0','{$date}','1')  on duplicate key update  num=num+1";
	    $rs = D()->execute($sql);
	   
		$sql = "insert into tb_qrcode_count(`openid`,`qid`,`pid`,`first`,`num`) values('{$openid}','0','0','{$date}','1')  on duplicate key update  num=num+1";
		 D()->execute($sql);
	   
	   $root = "http://jr.app.te6.com/";
	   $keyword = '图文';
	   $info = $this->txtModel->where(array('b_keyword'=>$keyword))->order('id desc')->limit(5)->select();
	   $datas = array();
	   $datas['type'] = 'list';
	   foreach ($info as $k=>$v){
	   	$datas['info'][$k]['Title'] = $v['b_title'];
	   	$datas['info'][$k]['Description'] = trim($v['content']);
	   	$datas['info'][$k]['PicUrl'] =  $root.$v['b_path'];
	   	$datas['info'][$k]['Url'] = $v['b_tourl'];
	   }
	   
	   echo json_encode($datas,JSON_UNESCAPED_UNICODE);
	   exit;
	   echo $content;
		exit;
	}
	

	
}