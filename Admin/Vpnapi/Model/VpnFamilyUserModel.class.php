<?php

namespace Vpnapi\Model;

use Think\Model;

/**
 * 
 *
 * @author litc
 */
class VpnFamilyUserModel extends Model {

    protected $_validate = [
        
        array('phone', 'require', '手机号必须！',1,'regex',1),
        array('phone', 'checkPhone', '手机号格式不正确！',1,'callback',1),
        array('phone', '', '手机号已经存在！', 1, 'unique', 1), 
        
        array('password', 'require', '密码必须填写！','regex',1),
        array('password', 'checkPwd', '密码长度至少6位!', 1, 'callback',1), 
        
        array('repassword', 'password', '确认密码不正确', 1, 'confirm',1), 
        
        array('verify', 'require', '验证码必须！',1,'regex',1),
        array('phone,verify', 'checkVerify', '验证码不正确！',1,'callback',1),
        
        //忘记密码
        array('phone', 'require', '手机号必须！',1,'regex',4),
        array('phone', 'checkPhone', '手机号格式不正确！',1,'callback',4),
        array('verify', 'require', '验证码必须！',1,'regex',4),
        array('phone,verify', 'checkVerify', '验证码不正确！',1,'callback',4),
        
        array('phone', 'require', '手机号必须！',1,'regex',5),
        array('phone', 'checkPhone', '手机号格式不正确！',1,'callback',5),
        array('password', 'require', '密码必须填写！','regex',5),
        array('password', 'checkPwd', '密码长度至少6位!', 1, 'callback',5), 
        array('repassword', 'password', '确认密码不正确', 1, 'confirm',5), 
        
        //发送验证码
        array('phone', 'require', '手机号必须！',1,'regex',6),
        array('phone', 'checkPhone', '手机号格式不正确！',1,'callback',6),
        array('stype', '1,2', '发送类型不正确！',1,'in',6),
        
        array('phone', 'require', '手机号必须！',1,'regex',7),
        array('phone', 'checkPhone', '手机号格式不正确！',1,'callback',7),
        
        array('password', 'require', '密码必须填写！','regex',7),
        array('password', 'checkPwd', '密码长度至少6位!', 1, 'callback',7),
        array('gw', 'require', '网关参数为空！',1,'regex',7),
        array('mac', 'require', '机器mac地址为空！',1,'regex',7),
    ];
    
    protected $_auto = [
        ['created_at', 'getDate', 1, 'callback'],
        ['uuid', 'createUid', 1, 'callback'],
        ['updated_at', 'getDate', 3, 'callback'],
    ];

    public function getDate() {
        return date('Y-m-d H:i:s');
    }
    
    public function fillpassword($password) {
        return password_hash($password,PASSWORD_DEFAULT);
    }
    
    public function checkPwd($password) {
        return strlen($password)>=6;
    }
    
    public function checkPhone($phone) {
        return preg_match('/^1[3456789]\d{9}$/',trim($phone));
    }
    
    public function checkVerify($param) {
        $phone=$param['phone'];
        $code=$param['verify'];
        $redis=service('Redis');
        $key="vpn_sms:".$phone;
        $verifyCode='';
        $getcode=$redis->get($key);
        if($getcode){
            $verifyCode=current(explode('_', $getcode));
        }
        if($verifyCode==$code){
            $redis->remove($key);
            return true;
        }
        return false;
    }
    
    public function createUid() {
        $info=$this->query('select uuid() guid');
        return str_replace('-', '', current($info)['guid']);
    }

}
