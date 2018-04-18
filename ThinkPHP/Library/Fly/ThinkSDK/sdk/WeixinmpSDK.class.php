<?php
/**--------------------------------------------
 * 微信公众帐号 网页授权
 * @author tianfei
 * @create on 2014-11-13
 * --------------------------------------------
 */
use Org\ThinkSDK\ThinkOauth;
class WeixinmpSDK extends ThinkOauth{

    //oauth登陆获取code
    protected $GetRequestCodeURL = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    //oauth登陆通过code换取网页授权access_token
    protected $GetAccessTokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    //oauth登陆刷新access_token（如果需要）
    protected $OauthRefreshTokenURL = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';

    //oauth 用户信息（scope为snsapi_userinfo）
    protected $GetUserinfoURL = 'https://api.weixin.qq.com/sns/userinfo';

    //获取request_code的额外参数,可在配置中修改 URL查询字符串格式
    protected $Authorize = 'scope=snsapi_base&state=2014';

    //API根路径
    protected $ApiBase = 'https://api.weixin.qq.com/';

    /**
     * 初始化配置
     */
    public function __construct($token = null){
        parent::__construct($token);
        $config = C("THINK_SDK_WEIXINMP");
        //默认设置授权域为 scope=snsapi_base
        if(!empty($config['AUTHORIZE']))
            $this->Authorize = $config['AUTHORIZE'];
        if(!empty($config['CALLBACK']))
            $this->Callback = $config['CALLBACK'];
        else
            throw new Exception('请配置回调页面地址');
    }


    /**
     * 设置授权域
     */
    public function setAuthorize($userinfo = false){
        //回调地址标识授权域
        if($userinfo)
            $this->Authorize = 'scope=snsapi_userinfo&state=2014';

        return true;
    }

    /*
     *设置callback
     */
    public function setCallBack($callbackUrl){
        if(!empty($callbackUrl))
            $this->Callback = $callbackUrl;

        return true;
    }

    /**
     * 重写获取CodeURL
     */
    public function getRequestCodeURL(){

        //Oauth 标准参数
        $params = array(
            'appid'       => $this->AppKey,
            'redirect_uri'  => $this->Callback,
            'response_type' => $this->ResponseType,
        );
        $lastParam ='#wechat_redirect';

        //获取额外参数
        if($this->Authorize){
            parse_str($this->Authorize, $_param);
            if(is_array($_param)){
                $params = array_merge($params, $_param);
            } else {
                throw new Exception('AUTHORIZE配置不正确！');
            }
        }
        return $this->GetRequestCodeURL . '?' . http_build_query($params).$lastParam;
    }


    /**
     * 获取access_token
     * @param string $code 上一步请求到的code
     */
    public function getAccessToken($code, $extend = null){
        $params = array(
            'appid'       => $this->AppKey,
            'secret'      => $this->AppSecret,
            'code'        => $code,
            'grant_type'  => $this->GrantType,
        );

        $data = $this->http($this->GetAccessTokenURL, $params, 'POST');
        $this->Token = $this->parseToken($data, $extend);
        return $this->Token;
    }

    /**
     * 获取userinfo
     */
    public function getUserinfo($accesstoken,$openid){
        $params = array(
            'access_token'=> $accesstoken,
            'openid'      => $openid,
            'lang'        => 'zh_CN',
        );

        $data = $this->http($this->GetUserinfoURL, $params, 'POST');
        return json_decode($data, true);
    }
    /**
     * 组装接口调用参数 并调用接口
     * @param  string $api    微信API
     * @param  string $param  调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @return json
     */
    public function call($api, $param = '', $method = 'GET', $multi = false){
        /* 腾讯QQ调用公共参数 */
        $params = array(
            'access_token'       => $this->Token['access_token'],
            'openid'             => $this->openid(),
        );
        $data = $this->http($this->url($api), $this->param($params, $param), $method);
        return json_decode($data, true);
    }

    /**
     * 解析access_token方法请求后的返回值
     * @param string $result 获取access_token的方法的返回值
     */
    protected function parseToken($result, $extend){
        $data = json_decode($result, true);
        if($data['access_token'] && $data['expires_in']){
            $this->Token    = $data;
            $data['openid'] = $this->openid();
            return $data;
        } else
            throw new Exception("获取ACCESS_TOKEN 出错：{$result}");
    }

    /**
     * 获取当前授权应用的openid
     * @return string
     */
    public function openid(){
        $data = $this->Token;
        if(isset($data['openid']))
            return $data['openid'];
        elseif($data['access_token']){
            $data = $this->http($this->url('oauth2.0/me'), array('access_token' => $data['access_token']));
            $data = json_decode(trim(substr($data, 9), " );\n"), true);
            if(isset($data['openid']))
                return $data['openid'];
            else
                throw new Exception("获取用户openid出错：{$data['error_description']}");
        } else {
            throw new Exception('没有获取到openid！');
        }
    }
}
