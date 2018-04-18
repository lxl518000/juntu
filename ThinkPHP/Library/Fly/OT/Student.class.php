<?php
/**
 * Created by Juanpi.com.
 * User: Sean.Song
 * Date: 13-9-25
 * Time: 下午22:58
 * 用户服务类文件--用户登录接口，统一的取得用户信息，用户ID的方法
 */
namespace OT;
use Huaqin\Model\StudentModel;
/**
 * SESSIOON类
 *
 * @class SESS
 */
class SESS{
    public static $init = 0;
    public static $preKey = 'JJSS';

    //初始化session
    public static function initSession(){
        if(self::$init==1) return;
        //没有开启session的时候开启session
        if(!isset($_SESSION)){
            session_start();
        }
        self::$init = 1;
    }

    //读取session
    public static function get($key){
        self::initSession();
        return $_SESSION[self::$preKey.$key];
    }

    //设置session
    public static function set($key,$val){
        self::initSession();
        $_SESSION[self::$preKey.$key] = $val;
    }

    //删除指定session
    public static function del($key){
        self::initSession();
        unset($_SESSION[self::$preKey.$key]);
    }

    //删除所有session
    public static function delall(){
        self::initSession();
        session_destroy();
    }
}
/**
 * 用户数据对象（保存在Session中的用户信息）
 *
 * @class User
 */
class UserInfo {
    public $UserId;//用户ID
    public $stuId;//学生学号
    public $NickName;//用户昵称
    public $Password;//密码
    public $ExpireTime;//验证时间，用户计算Session是否过期（通过与Cookies中相对应的值进行比较）
    public $HeadPic;//用户头像
    public $Email;//用户EMAIl
    public $Mobile;//用户手机号
    public $Addtime;//用户EMAIl

    public function __construct($uid,$stuId,$name,$head,$pwd,$email,$mobile,$addtime){
        $this->UserId = $uid;
        $this->stuId = $stuId;
        $this->NickName = $name;
        $this->HeadPic = $head;
        $this->Password = $pwd;
        $this->Email = $email;
        $this->Mobile = $mobile;
        $this->Addtime = $addtime;
    }
}

/**
 * 保存在Cookies中的用户信息
 */
class CookieUserInfo{
    public $User;//UserInfo对象（Session User）
    public $Sign;//验证码（Cookies临时验证码）
}

class Student{

    private static function setUserInfoInSession($user){
        if($user==null)
        {
            SESS::del("studentinfo");
            return;
        }
        SESS::set("studentinfo",$user);
    }

    private static function getUserInfoInSession(){
        return SESS::get("studentinfo");
    }
    private static function get_top_domain($url=''){
        //获取域名
        $info=empty($url)?array():parse_url($url);
        $host=empty($info['host'])?$url:$info['host'];
        $host=empty($host)?$_SERVER['HTTP_HOST']:$host;
        //获取顶级域名
        $domain=explode('.',$host);
        $length=count($domain);
        $data=array('com','net','org','gov');//国际顶级域名定义
        if($length>=3&&in_array($domain[$length-2],$data)){
            return implode('.',array($domain[$length-3],$domain[$length-2],$domain[$length-1]));
        }else if($length>=2){
            return implode('.',array($domain[$length-2],$domain[$length-1]));
        }
        return false;
    }

    private static function createLoginSign($user){
        $code = '@A#,^Oqimi';
        return md5($user->NickName.$user->UserId.$user->Password.$user->Email.$user->Mobile.$user->Token.$code);
    }


    /**
     * 直接登录，请谨慎使用该方法，该方法不会做任何登录验证
     *
     */
    private static function login($userinfo,$days){
        if(self::setUserInfoInSession($userinfo)){
            return true;
        }
        return false;
    }

    /**
     * 退出登录
     * 退出登录会清除所有session，这个地方可能有风险，如果需要用户退出后还保持一些类似session的数据，建议手动处理这类数据
     */
    public static function logout(){
        self::setUserInfoInSession(null);
        session_unset();
        session_destroy ();
    }

    /**
     * 取得登录用户信息，未登录返回null
     * @return UserInfo 用户Session信息
     */
    public static function getUser(){
        //检查Session
        $sessionuser = self::getUserInfoInSession();
        //如果Session不存在或者已经过期或者Session中uid和Cookie中uid不一致
        if(isset($sessionuser) == false){
            $sessionuser=null;
        }
        return $sessionuser;
    }

    /**
     * 取得登录用户的ID，返回long型值
     * @return number
     */
    public static function getUserId(){
        $u = self::getUser();
        if(!isset($u))return 0;
        return $u->UserId;
    }

    /**
     * 记录登录日志
     * @param int $uid 登录后的UID，登录失败为0
     * @param str $loginname 登录名（第三方登录为openid）
     * @param int $code 登录返回code
	 * @param arr $ext 扩展参数 $ext['referer'] 登录来源
     */
    private static  function loginLog($uid,$loginname,$code,$ext=array())
    {
        $data ['uid'] = $uid;
        $data ['loginname'] = $loginname;
        $data ['loginip'] = trim(IP_ADDR);
        $data ['logintime'] = date ( 'Y-m-d H:i:s', time() );
        $data ['rtcode'] = $code;
        foreach($data as $key=>$value){
            $data["`{$key}`"]="'{$value}'";
            unset($data[$key]);
        }
        $sql ='INSERT DELAYED INTO '.data('LoginLog')->getTableName().'('.implode(',',array_keys($data)).')'.' VALUES'.'('.implode(',',array_values($data)).')';
        data('LoginLog')->execute($sql);
    }

    /**
     * 1001:登录成功； 1002：用户不存在；1003：密码错误；1004：验证码错误；1005：用户被锁定
     * @param arr $ext 扩展参数 $ext['referer'] 登录来源
     */
    public static function loginWithName($loginName,$password,$days,$md5=false){
        if(!isset($_COOKIE)){
            $ajaxback ['status'] ['code'] = '1005';
            $ajaxback ['status'] ['msg'] = "user doesn't exist or locked!";
            return $ajaxback;
        }
        $_m_users = new StudentModel();
        //获取用户uid
        if(strpos($loginName,"@")>0) {
            //email登录
            $uid = $_m_users->where(array('email'=>$loginName))->getField('id');
        }else if(preg_match(C('regex.mobile'),$loginName)){
            //手机号登录
            $uid = $_m_users->where(array('mobile'=>$loginName))->getField('id');
        }else{
            //昵称登录
            $uid = $_m_users->where(array('account'=>$loginName))->getField('id');
        }
        //判断用户是否存在
        if(!$uid){
            $ajaxback ['status'] ['code'] = '1002';
            $ajaxback ['status'] ['msg'] = "user doesn't exist!";
        }else{
            $field = "id as uid,nickname,stuId,account,email,mobile,password,avatar,createTime as addtime";
            $info = $_m_users->where(array('id'=>$uid))->field($field)->find();
            $md5 or $password = md5($password);
            if(intval($info['isLock'])==1){
                $ajaxback ['status'] ['code'] = '1005';
                $ajaxback ['status'] ['msg'] = "user been locked!";
            }elseif($password !== $info['password']){
                $ajaxback ['status'] ['code'] = '1003';
                $ajaxback ['status'] ['msg'] = "wrong password!";
            }else{
                //无验证直接登录
                $userinfo = new UserInfo($info['uid'],$info['stuId'],$info['nickname'],$info['avatar'],$info['password'],$info['email'],$info['mobile'],$info['addtime']);
                $rt = self::login($userinfo,$days);//登录并设置session
                if($rt){
                    $data['lastLoginIP'] = IP_ADDR_INT;
                    $data['lastLoginTime'] = __TIMESTAMP__;
                    $data['loginCount'] = array ('exp', 'loginCount+1' );
                    $_m_users->where(array('id'=>$info['uid']))->save($data);
                    $data ['uid'] = $info ['uid'];
                    $info['sign'] = self::createLoginSign($userinfo);
                    $info['exp'] = $days;
                    $ajaxback ['status'] ['code'] = '1000';
                    $ajaxback ['status'] ['msg'] = 'login success!';
                    $ajaxback ['result'] ['data']['uid']=encrypt($data['uid']);
                    $ajaxback ['status'] ['userinfo'] = $info;
                    $ajaxback ['status'] ['referer'] = $_COOKIE['referer'] ? $_COOKIE['referer'] : "";
                    
                }else{
                    $ajaxback ['status'] ['code'] = '1002';
                    $ajaxback ['status'] ['msg'] = "user doesn't exist!";
                }
            }
        }
        $uid = 0;
        if($info)$uid=$info['uid'];
        //self::loginLog($uid,$loginName,0,$ajaxback ['status'] ['code'], $ext);
        return $ajaxback;
    }

    /**
     * 1001:登录成功； 1002：用户不存在；1005：用户被锁定
	 * @param arr $ext 扩展参数 $ext['referer'] 登录来源
     *  第三方登录
     */
    public static function loginWithOpenid($openuid){
        $day = 14;
        $_m_users = new StudentModel();
        $uid = $_m_users->where(array('openid'=>$openuid))->getField('id');
        //判断第三方登录帐号uid是否存在
        if(!$uid){
            $ajaxback ['status'] ['code'] = '1002';
            $ajaxback ['status'] ['msg'] = "user doesn't exist or locked!";
        }else{
            $field = "id as uid,nickname,stuId,account,email,mobile,password,avatar,createTime as addtime";
            $info = $_m_users->where(array('id'=>$uid))->field($field)->find();
            //判断users表中是否存在此uid的用户
            if(empty($info)){
                $ajaxback ['status'] ['code'] = '1002';
                $ajaxback ['status'] ['msg'] = "user doesn't exist or locked!";
            }else{
                $userinfo = new UserInfo($info['uid'],$info['stuId'],$info['nickname'],$info['avatar'],$info['password'],$info['email'],$info['mobile'],$info['addtime']);
                $rt = self::login($userinfo,$day);//登录并设置session
                if($rt){
                    $data['lastLoginIP'] = IP_ADDR_INT;
                    $data['lastLoginTime'] = __TIMESTAMP__;
                    $data['loginCount'] = array ('exp', 'loginCount+1' );
                    $_m_users->where(array('id'=>$info['uid']))->save($data);
                    $data ['uid'] = $info ['uid'];
                    $info['sign'] = self::createLoginSign($userinfo);
                    $info['exp'] = $day;
                    $ajaxback ['status'] ['code'] = '1000';
                    $ajaxback ['status'] ['msg'] = 'login success!';
                    $ajaxback ['result'] ['data']['uid']=encrypt($data['uid']);
                    $ajaxback ['status'] ['userinfo'] = $info;
                    $ajaxback ['status'] ['referer'] = $_COOKIE['referer'] ? $_COOKIE['referer'] : "";
                }else{
                    $ajaxback ['status'] ['code'] = '1002';
                    $ajaxback ['status'] ['msg'] = "user doesn't exist!";
                }
            }
        }
        $uid=0;
        if($info)$uid=$info['uid'];
        //self::loginLog($uid,$openuid,$site,$ajaxback ['status'] ['code'],$ext);
        return $ajaxback;
    }
}
?>