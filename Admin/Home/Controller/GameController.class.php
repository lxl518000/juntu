<?php
namespace Home\Controller;
use Think\Controller;


/**
 * 游戏管理
 * @author Administrator
 *
 */
class GameController extends BackendController {
	
	public function __construct(){
		parent::__construct();
		$this->model = D('Game');
		
	}
	
	protected function _filter(){
		$where = array();
		if(!empty($_REQUEST['name'])){
			$where['name'] = array('like',"%{$_REQUEST['name']}%");
		}
		if(!empty($_REQUEST['process'])){
			$where['process'] = array('like',"%{$_REQUEST['process']}%");
		}
		if(!empty($_REQUEST['pmd5'])){
			$where['pmd5'] = array('like',"%{$_REQUEST['pmd5']}%");
		}
		
		if(IS_ROOT){
			if(!empty($_REQUEST['cid'])){
				$where['cid'] = I('cid');
			}
		}else{
			$where['cid'] = CID;
		}
		
		return $where;
	}
	
	protected function _format($list){
		
		$this->_checkAuth();
		return $list;
	}
	
	
	protected function _before_insert($data){
		//获取授权码
		
		$code = $this->getscode();
		
		$data['scode'] = $code;
		$data['scodemd5'] = md5($code);
		//获取授权码
		
		return $data;
	}
	
	protected function _before_update($data){
		return $data;
	}
	
	protected function _after_update($data){
		$find = $this->model->where(array('id'=>$data['id']))->find();
		$redis = service('Redis');
		$redis->hSet('SOCK5_GAME_LIST',$find['scode'],json_encode($find));
		return true;
	}
	
	protected function _after_insert($id){
		$find = $this->model->where(array('id'=>$id))->find();
		$redis = service('Redis');
		$redis->hSet('SOCK5_GAME_LIST',$find['scode'],json_encode($find));
		return true;
	}
	

	protected function _before_add(){
		//获取授权码
		
		$code = $this->getscode();
		
		$data['scode'] = $code;
		$data['scodemd5'] = md5($code);
		$this->_checkAuth();
	}
	
	protected function _after_edit(){
		$this->_checkAuth();
	}
	
	
	protected function _checkAuth(){
		if(IS_ROOT){
			$company = D('Company')->getField('id,id,name',true);
			$this->assign('company',$company);
			$this->assign('isroot',1);
		}
	}


	
	public function down(){
		$id = I('id');
		$scope = D('game_list')->where(array('id'=>$id))->getField('scode');
		header('Content-type: application/octet-stream');
		header('Content-Disposition: attachment; filename="authcode.txt"');
		echo $scope;
		exit();
		 
	}
	

	function getscode($len=64){
		$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$t = strlen($chars);
	
		$string =(string) uniqid();
	
		$n = strlen($string);
	
		for($i=$n;$i<64;$i++){
			$string .= $chars[rand(0,$t)];
		}
	
		 
		return $string;
	}
    
    
}