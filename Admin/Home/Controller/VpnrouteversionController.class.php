<?php
namespace Home\Controller;

class VpnrouteversionController extends BackendController {
	
	protected $name = "路由表版本";
	
	protected function loadModel(){
		$this->model = D('VpnRouteVersion');
		return $this->model;
	}
	
	
	
	protected function _before_add(){
		$list['status'] = 1;
		$list['ver'] = date('YmdHi');
		$this->assign('list',$list);		
		
		
		$count = D('vpn_route')->count();
		$this->assign('count',$count);
	}
	
	
	protected function _before_insert($data){
		$ips = D('vpn_route')->getField('ip',true);
		$data['content'] = implode(',', $ips);
		return $data;
	}
	
	
	protected function _after_insert($data){
		$ver = $data['ver'];
		
		$str = bin2hex(encryptStr($data['content']));
		
		$rs = file_put_contents(ROOTPATH.'pub/'.$ver.'.txt',$str);
		return true;
	}
	
	public function preview(){
		$ver = I('ver');
		
		$rs = file_get_contents(ROOTPATH.'pub/'.$ver.'.txt');
		
		if(!$rs){
			echo '文件未找到';exit;
		}
		//$rs = $this->hex2asc(encryptStr($rs));
		$rs = encryptStr($this->hex2asc($rs));
		$rs = explode(',', $rs);
		echo "<pre>";
		print_r($rs);
		echo "</pre>";
	}
	
	public function pub(){
		$id = I('id');
		$m = $this->loadModel();
		$m->where(array('pub'=>1))->setField('pub',0);
		$find = $m->where(array('id'=>$id))->find();
		$ver = $find['ver'];
		$user = $m->getUser();
		$save = array('pubtime'=>date('Y-m-d H:i:s'),
					'pubuser'=>$user,
					'pub'=>1
		);
		$m->where(array('id'=>$id))->save($save);
		$redis = service('Redis');
		$json = array('ver'=>$ver,'url'=>C('CDN_ADDRESS').'/'.$ver.".txt");
		$redis->set("SPEED:ROUTE_PUB",json_encode($json));
		$this->success('操作成功');
		
	}
	
	function hex2asc($str) {
		$data = '';
		$str = join('',explode('\x',$str));
		$len = strlen($str);
		for ($i=0;$i<$len;$i+=2) $data.=chr(hexdec(substr($str,$i,2)));
		return $data;
	}
	

	
	protected function _format($list){
		
		$group = C('CONFIG_GROUPS');
		$status = [1=>"<span style='color:green'>已发布</span>",0=>'<span style="color:#ccc">未发布</span>'];
		foreach($list as $k=>$v){
		
			$op = '';
	 		
	 		
			$op .= getToolIcon('','J_confirm btn-xs ',U('preview',['ver'=>$v['ver']]),'预览','eye-open','info','J_open')."&nbsp;";
	 		
	 		if($v['pub']==1){
	 			$op .= getToolIcon('off','J_confirm btn-xs ',U('pub',['id'=>$v['id']]),'发布','','','J_confirm')."&nbsp;";
	 		
	 		}else{
	 			$op .= getToolIcon('on','J_confirm btn-xs ',U('pub',['id'=>$v['id']]),'发布','','','J_confirm')."&nbsp;";
	 		}
	 		
	 		$v['pubstatus'] = $status[$v['pub']];
	 		
	 		$v['operate'] = $op;
	 		
			
			$list[$k] = $v;
		}
		
		return $list;
	}
	

	
	
	
 
    
    
    
    
   
}