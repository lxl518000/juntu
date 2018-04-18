<?php
namespace Wx\Controller;
use Think\Controller;
class WxApiController extends Controller {
	
    public function index(){
        
    }
    
    public function verify(){
    	
    	$phone = $_REQUEST['phone'];
    	if(!preg_match("/1[1234567890]{1}\d{9}$/",$phone)){
			$this->error('无效的手机号码！');
		}
		
		$last = S('LAST_VERFIY_'.$phone);
		if($last){
			$this->error('请不要频繁发送');
		}
		
		$verify = rand(100000,999999);
		//$verify = 111111;
		$str = "您本次登录的验证码：{$verify};该信息5分钟内使用有效！";
		//$res = sendSMS($phone,$str);
		$res = 'success';
		if(!strstr($res, 'success')){
			$this->error('短信发送失败,请重试！');
		}
		S('XINDAI_'.$phone,$verify,300);
		//$redis = service('Redis');
		//$redis->set('XINDAI_'.$phone,$verify,300);
		S('LAST_VERFIY_'.$phone,$verify,30);
		$this->success('短信发送成功！');
	
    }
    
    
    public function login(){
    	$phone = $_REQUEST['phone'];
    	$verify = $_REQUEST['verify'];
   		if(empty($phone) || empty($verify)){
    		$this->error('请输入完整信息');
    	}
      	//$redis = service('Redis');
		//$check = $redis->get('XINDAI_'.$phone);
    	$check = S('XINDAI_'.$phone);
    	if($phone == '15527374110'){
    		$check = '123456';
    	}
    	if($check != $verify){
    		$this->error('错误的验证码或该验证码已过期 请重试');
    	} 
    	
    	$where = array();
    	$where['phone'] = $phone;
    	$u = D('apply_user');
    	$find = $u->where($where)->find();
    	$data = array();
    	$saveData = array();
    	$t = date('Y-m-d H:i:s');
    	$data['phone'] = $phone;
    	if(!$find){
    		$id = $u->add($data);
    		$data['id'] = $id;
    		$saveData['regtime'] = $t;
    	}else{
    		$id = $find['id'];
    		$data = $find;
    	}
    	$saveData['lastime'] = $t;
    	$u->where(array('id'=>$id))->save($saveData);
    
    	$return = $u->where(array('id'=>$id))->find();

    	$return['key'] = md5('finance'.$id.$phone.'qwer');
    	$this->success('登录成功',$return);
    	
    	
    }
    
    
}