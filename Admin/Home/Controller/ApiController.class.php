<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 超级盾对外接口
 * @author admin
 *
 */
class ApiController extends Controller {
	
	protected $gw;
	protected $mac;
	protected $ip;
	protected $appkey = "Eqd4jRDrmYKUrlre";
	protected $v;
	protected $vmd5;
	protected $type;
	protected $authtype;
	protected $scode;
	protected $return = array('info'=>'','data'=>'','status'=>0);
	
	public function __construct(){
		parent::__construct();
		C('SESSION_AUTO_START',false);
		$authtype = I('authtype',1);		
		$this->_checkAuth();
		

		//获取类型 空为初次 1为重新
		$type = I('type',0);
		
		$this->redis = service('Redis');
		
		$this->_cachePlist();		
		
		
		
	}
	
	
	/**
	 * 获取一组服务器
	 */
	public function getServer(){
		
	
		//获取最新下载
		$redis = $this->redis;
		$new = $redis->get('SOCK5_LOADER_NEWEST');
		$new = explode('-', $new);
		$data = array();
		if($new[0] != $this->v){
			$data['update'] = array('ver'=>$new[0],'url'=>$new[1],'md5'=>$new[2]);
			$data['status'] = 2;
			$this->setRes('update',$data,2);
		}else{
			$data['update'] = 0;
		}
		
	
		
		//如果重连 则不返回上一次
		if($_REQUEST['type'] == 1){
			$last = $redis->hGet('SOCK5_CONN',$this->gw);
			if($last){
				$where['id'] = array('neq',$last);
			}
		}
		$find = D('sock5_server')->where($where)->order('rand()')->find();
		
		if(!$find){
			$this->setRes('no available server',array(),0);
		}
		
		$account = $find['serverip'].'-'.$find['username'].'-'.$find['password'].'-'.$find['port'];
		$account = encryptStr($account);
		$account = bin2hex($account);
		
		//print_r($account);
		//echo "<br/>";
		//print_r(decryptStr($account));exit;
		
		//账号
		$data['account'] = $account;
		
		$scode = $this->scode;
		$gameinfo = $redis->hGet('SOCK5_GAME_LIST',$scode);
		$ginfo = array();
		if($gameinfo){
			$game = json_decode($gameinfo,true);
			$ginfo['name'] = $game['name'];
			$ginfo['process'] = $game['process'];
			$ginfo['pmd5'] = $game['pmd5'];
			$ginfo['path'] = $game['path'];
		}else{
			$ginfo = array();
		}
		
		$data['game'] = $ginfo;
		
		$this->setRes('ok',$data,1);
		
		
		
	}
    
	/**
	 * 校验类型
	 * 1为普通类型
	 * 
	 */
    protected function _checkAuth($type=1){
    	if($type == 1){
    		$this->_checkNormal();
    		return ;
    	}
    	
    	return ;
    }
    
    /**
     * 请求地址:ttp://rule.51wzhs.com:9000/getserver.php
     * 普通校验 	
     * 秘钥			appkey		 Eqd4jRDrmYKUrlre
     * 校验网吧网关 	gw 			 11:22:33:44:55:66
     * 客户机mac		mac			 15:25:35:45:55:66
     * 客户机	ip		ip  		 192.168.0.1
     * 请求时间戳		t			 1501830366
     * 进程列表   		plist		 lol<1234234324<123|dota2<1231233|234
     * 当前版本		v			 1.0
     * 授权码			scode		  59891e620a0f0TqYSEZ8RCc34MDsSBoz63346IfHbZKhjWQEVcfbwO9G2qhxX6R	
     * 校验码			sign		 9be53422525d1737760028a09f1fc773
     * 校验方式 md5(appkey.gw.mac.ip.v.appkey.t.plist.scode); (点代表字符串连接)
     * 形如       md5(Eqd4jRDrmYKUrlre11:22:33:44:55:6615:25:35:45:55:66192.168.0.11.0Eqd4jRDrmYKUrlre1501830366lol<1234234324<123|dota2<1231233|23459891e620a0f0TqYSEZ8RCc34MDsSBoz63346IfHbZKhjWQEVcfbwO9G2qhxX6R)
   	*http://superd.com/server.php?ip=192.168.0.1&gw=11:22:33:44:55:66&mac=15:25:35:45:55:66&sign=fd632f51e28e21bbf30dd15db8661338&v=1.0&plist=lol%3C1234234324%3C123|dota2%3C1231233|234&scode=59891e620a0f0TqYSEZ8RCc34MDsSBoz63346IfHbZKhjWQEVcfbwO9G2qhxX6R&ip=192.168.0.1&t=1501830366
     */
    protected function _checkNormal(){
    	$gw = $_REQUEST['gw'];
    	$mac = $_REQUEST['mac'];
    	$ip = $_REQUEST['ip'];
    	$sign = $_REQUEST['sign'];
    	$t = $_REQUEST['t'];
    	$scode = $_REQUEST['scode'];
    	$v = $_REQUEST['v'];
    	$plist = $_REQUEST['plist'];
    	
    	//print_r($_REQUEST);
    	
    	if(!$gw || !$mac || !$ip || !$sign || !$t || !$v || !$scode){
    		//$this->setRes('param error');
    	}
    	
    	$chkSign = md5($this->appkey.$gw.$mac.$ip.$v.$this->appkey.$t.$scode);
    	if($_REQUEST['debug']){
    		
    		print_r($_REQUEST);
    		
    	print_r($this->appkey.$gw.$mac.$ip.$v.$this->appkey.$t.$scode);
		echo '<hr/>';
			print_r($chkSign);
    		exit;
    	}
    	if($sign != $chkSign){
    		
    		$this->setRes('check faild');
    	}
    	
    	$this->gw = self::_formatGw($gw);
    	$this->mac = $mac;
    	$this->ip = $ip;
    	$this->scode = $scode;
    	$this->v = $v;
    	$this->plist = $plist;
    	
    	return true;
    	
    }
    
    protected function _cachePlist(){
    	$this->redis->hSet('SOCK5_PROGESS_LIST',$this->gw,$this->plist);
    	return true;
    }
	
    //兼容各种格式网关
    protected function _formatGw($gw){
    	$gw = str_replace(array('-',':'), '', $gw);
    	$gw = strtoupper($gw);
    	$gw = str_split($gw, 2); //分割6等份
    	$gw = implode(':',$gw); //拼接成常规
    	return $gw;
    
    }
    
    protected function setRes($info,$data=array(),$status=0){
    	$this->return['info'] = $info;
    	$this->return['data'] = $data;
    	$this->return['status'] = $status;
    	$this->ajaxReturn($this->return);
    }
    
    
	
    
}