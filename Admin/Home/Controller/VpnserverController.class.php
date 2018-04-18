<?php
namespace Home\Controller;
use Think\Controller;


class VpnserverController extends BackendController {
	
	protected $name = "服务器管理";
	
	public function __construct(){
		parent::__construct();
		$this->model = D('VpnServer');
		
		//连接模式
		$this->mode = C('VPN_ROUTE_MODE');
		$this->assign('mode',$this->mode);
		
		$this->userlevel = [1=>'普通用户',2=>'黄金用户',3=>'钻石用户'];
		
		$this->assign('userlevel',$this->userlevel);
		
	}
	
	
	

	protected function _makePostData($data){
		if($_POST['checkall'] == 1){
			$data['region'] = 0;
			$data['reigonname'] = '全国';
		}else{
			$names = D('region')->where(array('RegionCode'=>array('in',$_POST['region'])))->getField('RegionName',true);
			$data['regionname'] = implode('-', $names);
			$data['region'] = implode(',', $_POST['region']);
		}
		
		$modes = $this->mode;
		$connmode = '';
		$modename = '';
		if($_POST['connmode']){
			foreach($_POST['connmode'] as $v){
				$modename[] = $modes[$v];
			}
			$modename = implode('-', $modename);
			$connmode = ','.implode(',', $_POST['connmode']).',';
		}
	
		$data['modename'] = (string) $modename;
		$data['connmode'] = (string) $connmode; 
		return $data;
	}
	
	protected function _after_index(){
		$rooms = D('VpnRoom')->getField('id,name',true);
		$this->assign('rooms',$rooms);
	}
	
	
	public function cacheserver(){
		
	
		$this->success('操作成功');
	}
	
	protected function _ajaxData(){
		
		
		$table = "tb_vpn_server s";
		$join = "left join tb_vpn_roomset r on s.roomset=r.id ";
		
		$field = "s.*,r.name as roomname";

		
		$sort = I('sort','addtime');
			
		$order = I('order','DESC');
		if($sort == 'roomname'){
			$sort = "s.roomset";
		}else{
			$sort = "s.{$sort}";
		}
		
		
		$where = array();
		
		if($_REQUEST['formfield']){
			parse_str($_REQUEST['formfield'],$formField);
			if($formField['name']){
				$where['s.name'] = array('like',"%{$formField['name']}%");
			}
			if($formField['serverip']){
				$where['s.serverip'] = array('like',"%{$formField['serverip']}%");
			}
			if($formField['roomset']){
				$where['s.roomset'] = $formField['roomset'];
 			}
		}
		
		
		$offset = I('offset',0);
		$limit = I('limit',C('DEFAULT_PATE_SIZE'));
		
		$total =  D()->table($table)->join($join)->where($where)->count();
		
		$sort = $sort." ".$order;
			
		$list = D()->table($table)->join($join)->field($field)->where($where)->order($sort)->limit($offset, $limit)->select();
		
		$list = (array) $this->_format($list);
			
		$result = array("total" => $total, "rows" => $list);
		
		echo json_encode($result);
			
		return;
	}
	
	
	public function add($tpFile = '') {
		if(IS_POST) {
			$this->loadModel();
                if (false === $data = $this->model->create ()) {
                   $this->error($this->model->getError());
                }
                $data = $this->_makePostData($data);
                $list=$this->model->add ($data);

			if ($list!==false) { //保存成功
				$sql = $this->model->_sql();
				$this->syslog('添加'.$this->name.'成功',$sql);
				$this->success("添加成功");
			} else {
				$this->error('添加失败!'.$this->model->getError());
			}
		} 
		
		$list['status'] = 1;
		$list['vpnport'] = 1723;
		$list['userlevel'] = 1;
		$list['conn'] = 100;
		$list['isp'] = 1;
		$this->assign('list',$list);
		$province = D('region')->where(array('ParentCode'=>''))->select();
		$this->assign('province',$province);
		$roomset = D('vpn_roomset')->getField('id,name',true);
		$this->assign('roomset',$roomset);
		$this->display ();
	}
	
	public function edit() {
		$this->loadModel();
		
		if(IS_POST){
			if (false === $data = $this->model->create ()) {
				$this->error($this->model->getError());
			}
			
			$data = $this->_makePostData($data);
			$list=$this->model->save ($data);
			if (false !== $list) {
				if (method_exists ( $this, '_after_update' )) {
					$this->_after_update ( $data );
				}
				//成功提示
				$sql = $this->model->_sql();
				$this->syslog('编辑'.$this->name.'成功',$sql);
				$this->success("编辑成功");
			} else {
				$this->error('编辑失败!'.$this->model->getError());
			}
		}
		
		$pk = $this->model->getPk();
		$id = $_REQUEST [$this->model->getPk ()];
		$list = $this->model->where(array($this->model->getPk () => $id))->find();
		$roomset = D('vpn_roomset')->getField('id,name',true);
		$this->assign('roomset',$roomset);
		$province = D('region')->where(array('ParentCode'=>''))->select();
		$this->assign('province',$province);
		$this->assign('pkvalue',$id);
		$this->assign('pk',$pk);
		$this->assign('isupdate',1);
		$list['connmode'] = explode(',', $list['connmode']);
		$this->assign ( 'list', $list );
		$this->display ('add');
	}
	
		
	
 	protected function _format($list){
 		
	 	foreach($list as $k=>$vo){
	 		$op = '';
	 		
		 	$op .=  getToolIcon('edit','J_open btn-xs ',U('edit',['id'=>$vo['id']]),'','','','J_open')."&nbsp;";
	 		
	 		$op .= getToolIcon('delete','J_confirm btn-xs ',U('delete',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	 		
	 		if($vo['status']==1){
	 			$op .= getToolIcon('off','J_confirm btn-xs ',U('disable',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	 			$vo['status'] = "<span style='color:green'>启用</span>";
	 		}else{
	 			$op .= getToolIcon('on','J_confirm btn-xs ',U('enable',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	 			$vo['status'] = "<span style='color:#ccc'>禁用</span>";
	 		}
	 		$regionname = msubstr($vo['reigonname'],0,4);
	 		$vo['regionname'] = "<span title='{$vo['reigonname']}'>".$regionname."</span>";
	 		$vo['operate'] = $op;
	 		$list[$k] = $vo;
	 	}
	 	return $list;
	 }
	
	

	
	

}