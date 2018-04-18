<?php
namespace Wx\Controller;
use Think\Controller;
class UserController extends Controller {
	
	public function __construct(){
		parent::__construct();
		$uid = $_REQUEST['uid'];
		$phone = $_REQUEST['phone'];
		$sign = $_REQUEST['key'];
		$ck = md5('finance'.$uid.$phone.'qwer');
        if($ck != $sign){
        	$this->error('100');
        }
        $this->uid = $uid;
        $this->key = $sign;
	}
	
   
	public function setinfo(){

			$uid = $this->uid;
   
    		$m = D('apply_user');
    		$code = '';
    		$code = !empty($_POST['province']) ? I('province') : $code;
    		$code = !empty($_POST['city']) ? I('city') : $code;
    		$code = !empty($_POST['country']) ? I('country') : $code;
    	
    		$data = array();
    		$data['money'] = I('money');
    		$data['term'] = I('term');
    		$data['useto'] = I('useto');
    		$data['education'] = I('education');
    		$data['incometype'] = I('incometype'); 
    		$data['income'] = I('income'); 
    		$data['credit'] = I('credit');
    		$data['gongjijin'] = I('gongjijin');
    		$data['marriage'] = I('marriage');
    		$data['shebao'] = I('shebao');
    		$data['car'] = I('car');
    		$data['hourse'] = I('hourse');
    		$m->where(array('id'=>$uid))->save($data);
    		$info = $m->where(array('id'=>$uid))->find();
    		$info['key'] = $this->key;
    		$this->success('资料保存成功',$info);
		
	}
	
	public function datum(){
		$uid = $this->uid;
		$name = I('realname');
		$card = I('idcard');
		if(!isChinaName($name) || !isCardNo($card)){
			$this->error('无效的姓名或者证件号');
		}
		$data = array();
		$data['realname'] = $name;
		$data['idcard'] = $card;
		$m = D('apply_user');
		$where['id'] = $this->uid;
		$rs = $m->where($where)->save($data);
		$info = $m->where(array('id'=>$uid))->find();
		$info['key'] = $this->key;
		$this->success('资料保存成功',$info);
		
		
	}
	
}