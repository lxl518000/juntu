<?php
namespace Vpnapi\Controller;
use Think\Controller;
class WebApiController extends BaseApiController {

	
	
	public function _initialize(){
		
	}
	
	


	/**
	 * 获取可用服务器
	 * @param string $deviceid 设备id
	 * @param string $istest 默认为0 
	 */
	public function getser(){
		$deviceid = $this->deviceid;
		$redis = service('Redis');
		$vip = $redis->hGet('SPEED_VIP_USER');
		$test = I('istest',0);
		$server = [];
		if($test == 1){
			//获取测试使用服务器 30分钟	
			$hastest = $redis->hGet("SPEED_DEVICE_TEST",$deviceid);
			if($hastest == 1){
				$this->setRes('该账号已试用过');
			}
			
			$redis->hSet("SPEED_DEVICE_TEST",$deviceid,1);
			D('vpn_user')->where(array('deviceid'=>$deviceid))->setField('hastest',1);
			
		}else{
			$vip = $redis->hGet('SPEED_VIP_USER',$deviceid);	
			$date = date('Y-m-d');
			if(!$vip || $vip<$date){
				$this->setRes('您的会员已过期');
			}
		}
		
		$server['ip'] = "127.0.0.1";
		$server['account'] = "admin";
		$server['pwd'] = "123456";
		
		$this->setRes('success',1,$server);
		
		
	}
	
	/**
	 * 修改账户密码
	 * @param string $cellphone 电话号码
	 * @param string $userid 用户id
	 * @param string $verify 验证码
	 * @param string $password 密码
	 */
	public function repass(){
		$userid = I('userid');
		$phone = I('cellphone');
		$this->_checkPhone($phone);
		$password = I('password');
		if(!$password){
			$this->setRes('请输入密码');
		}
		
		$verify = I('verify');
		if(!$verify){
			$this->setRes('请输入验证码');
		}
		$redis = service('Redis');
		$verifyCode = $redis->get("SPEED:VRY_REPASS".$phone);
		if($verifyCode != $verify){
			$this->setRes('无效的验证码，请重试!');
		}

		$m = D('vpn_user');
		$data = array();
		$data['password'] = md5($password);
		$rs = $m->where(array('account'=>$phone))->save($data);
		
		$this->_addUserLog(2,'修改账户密码成功');
		
		$this->setRes('success',1);
	}

	
	/**
	 * 绑定手机号
	 * @param string $cellphone 电话号码
	 * @param string $userid 用户id
	 * @param string $verify 验证码
	 */
	public function bind(){
		$phone = I('cellphone');
		$this->_checkPhone($phone);
		
		$verify = I('verify');
		if(!$verify){
			$this->setRes('请输入验证码');
		}
		
		$userid = I('userid');
		if(!$userid){
			$this->setRes('无交的用户');
		}
		
		$redis = service('Redis');
		$verifyCode = $redis->get("SPEED:VRY_".$phone);
		if($verifyCode != $verify){
			$this->setRes('无效的验证码，请重试!');
		}
		
		//查找该手机号是否已有绑定过设备
		$m = D('vpn_user');
	
		$find = $m->where(array('account'=>$phone))->find();
		
		if($find){
			$this->setRes('该手机号已绑定过');
		}
		
		//如果没有找到则直接绑定
		$save = array();
		$save['account'] = $phone;
		if(!$userinfo['oldcount']){
			$save['oldcount'] = $userinfo['account'];
		}
		$save['ismobile'] = 1;
		$m->where(array('id'=>$userid))->save($save);
		$return['account'] = $phone;
		$this->_addUserLog(1,'绑定手机号');
			
		$this->setRes('绑定成功',1,$return);
	}
	
	
	/**
	 * 获取验证码
	 * 1个设备一天只能发送3次验证码 5分钟有效
	 * @param string $cellphone 电话号码
	 * @param string $userid 用户id
	 * @param string $type 'bind | repass'
	 */
	public function verify(){
		$phone = I('cellphone');
		$userid = I('userid');
		$this->_checkPhone($phone);
		
		$type = I('type');
		if($type == 'bind'){
			$this->_verify_bind($phone,$userid);
		}elseif($type == 'repass'){
			$this->_verify_repass($phone,$userid);
		}else{
			$this->setRes('无效的验证类型');
		}
	}


	/**
	 * 分配账号并登录
	 * 客户端初次打开则向系统获取一次账号和密码并保存 
	 * @param string $method account 
	 * @param string $channelid 来源渠道ID 没有可以不传 
	 */
	public function account(){
		
		$account = uniqid();
		$password = mt_rand(100000, 999999);
		
		$data = array();
		$data['account'] = $account;
		$data['password'] = $password;
		$data['ismobile'] = 0;
		$data['password'] = md5($password);
		$data['regtime'] = date('Y-m-d H:i:s');
		$data['logintime'] = $data['regtime'];
		$data['hastest'] = 0;
		$data['channelid'] =I('channelid',0);
		$data['oldcount'] = $account;
		$m = D('vpn_user');
		$id = $m->add($data);
		if(!$id){
			$this->setRes('db error');
		}
		
		$return = [];
		$return['userid'] = $id;
		$return['account'] = $account;
		$return['password'] = $password;
		$return['hastest'] = 0;
		$return['vip'] = '';
		
		$this->setRes('success',1,$return);
		
	}
	
	
	/**
	 * 用户登录接口
	 * @param string $account  用户账号 
	 * @param string $password  用户密码
	 * @return 返回用户id 设备id vip日期 联系电话 是否已经使用
	 */
	public function login(){
		
		$account = I('account');
		$password = I('password');
		
		if(!$account || !$password){
			$this->setRes('参数错误');
		}
		
		$where = array();
		$where['account'] = $account;
		$where['password'] = md5($password);
		$m = D('vpn_user');
		$find = $m->where($where)->find();
		
		if(!$find){
			$this->setRes('用户不存在');
		}
		
		if($find['status']==2){
			$this->setRes('该账户已被禁用');
		}
		
		$now = date('Y-m-d H:i:s');
		
		$return = [];
		$data = [];
		$data['logintime'] = $now;
		$data['loginnum'] = $find['loginnum']+1;
		$rs = $m->where($where)->save($data);
		
		$return['userid'] = $find['id'];
		$return['vip'] = (string) $find['vip'];
		$return['hastest'] = $find['hastest'];
		
		//添加登录日志
		$log = ['uid'=>$find['id'],'logintime'=>$now];
		D('vpn_login_log')->add($log);
		
		//$redis = service('Redis');
		//$redis->hSet('SPEED_VIP_USER',$find['userid'],$return['vip']);
		
		$this->setRes('登录成功',1,$return);
	}
	
	
	public function route(){
		$redis = service('Redis');
		$pub = $redis->get("SPEED:ROUTE_PUB");
		if(!$pub){
			$this->setRes('暂无版本');
		}
		$pub = json_decode($pub,true);
		$this->setRes('success',1,$pub);
	}


	protected function _addUserLog($optype=1,$opinfo=''){
		$uid = I('userid');
		if(!$uid){
			return;
		}
		$logData = [];
		$logData['uid'] = $uid;
		$logData['optype'] = $optype;
		$logData['opinfo'] = (string) $opinfo;
		$logData['optime'] = date('Y-m-d H:i:s');
		D('vpn_user_op_log')->add($logData);
		
		return true;
	}
	
	protected function _checkPhone($phone){
		if(!preg_match("/1[1234567890]{1}\d{9}$/",$phone)){
			$this->setRes('无效的手机号码');
		}
		return true;
	}

	protected function _verify_bind($phone,$userid){
	
		//查找该手机号是否已绑定过手机
		$where = array();
		$where['account'] = $phone;
		$find = D('vpn_user')->where($where)->find();
	
		if($find){
			$this->setRes('该手机号已绑定');
		}
	
		$redis = service('Redis');
		
		
		
		//查找设备发送验证码次数
		$key =  'SPEED:D_VERIFY_'.$userid;
		$num = $redis->get($key);
		if($num>10){
			$this->setRes('今日发送验证码次数已用完！');
		}
	
		if($num){
			$redis->incr($key);
		}else{
			$t = strtotime(date('Y-m-d',strtotime('+1 day')));
			$exp = $t-time();
			$res = $redis->set($key,1,$exp);
		}
	
		$verify = rand(100000,999999);
		$verify = '111111';
		$str = "您本次登录的验证码：{$verify};该信息5分钟内登陆有效！";
		//$res = sendSMS($phone,$str,'',19);
		$res = 'success';
		if(!strstr($res, 'success')){
			$this->setRes('短信发送失败');
		}
		$redis->set("SPEED:VRY_".$phone,$verify,300);
		$this->setRes('短信发送成功',1);
	}
	
	
	protected function _verify_repass($phone,$userid){
		//查找该手机号是否已绑定过手机
		$where = array();
		$where['id'] = $userid;
		$where['ismobile'] = 1;
		$find = D('vpn_user')->where($where)->find();
		if(!$find){
			$this->setRes('该账户暂不能修改密码');
		}
	
		$redis = service('Redis');
		//查找设备发送验证码次数
		$key =  'SPEED:D_VERIFY_REPASS'.$userid;
		$num = $redis->get($key);
		if($num>10){
			$this->setRes('今日发送验证码次数已用完！');
		}
	
		if($num){
			$redis->incr($key);
		}else{
			$t = strtotime(date('Y-m-d',strtotime('+1 day')));
			$exp = $t-time();
			$res = $redis->set($key,1,$exp);
		}
	
		$verify = rand(100000,999999);
		$verify = '111111';
		$str = "您本次登录的验证码：{$verify};该信息5分钟内登陆有效！";
		//$res = sendSMS($phone,$str,'',19);
		$res = 'success';
		if(!strstr($res, 'success')){
			$this->setRes('短信发送失败');
		}
		$redis->set("SPEED:VRY_REPASS".$phone,$verify,300);
		$this->setRes('短信发送成功',1);
	}
	
	
	
    
}