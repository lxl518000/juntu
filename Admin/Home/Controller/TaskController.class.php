<?php
namespace Home\Controller;
use Think\Controller;


/**
 * 场所类型控制器
 * @author Administrator
 *
 */
class TaskController extends BackendController {
	
	public function __construct(){
		parent::__construct();
		$this->model = D('Task');
		
	}
	// 部门统计
	public function fstat(){
		if($role == C('ROLE_ZHIFA_ID')){
			$this->redirect(U('Task/stat'));	
		}
        $userlist = D('Adminuser')->getfield('id,depid',true);
        $deplist = D('Department')->where(array('pid'=>0))->getfield('id,path',true);
        $deppath = D('Department')->getfield('id,path',true);
		$depname = D('Department')->getfield('id,name',true);
        $dp = session('adminuser.dep_path');
		$depid = session('adminuser.depid');
		$reg = session('adminuser.region');
		if(strlen($reg)>=6){
			$this->redirect(U('Task/stat'));		
		}
		$tlist = D('Task')->select();
        $depinfo = array();
		
		$binfo = array();
        foreach ($tlist as $k => $v) {
           $userid =array_filter(explode(',',$v['userId']));
           $dep =array();
           foreach ($userid as $k1 => $v1) {
               $dep[] = $userlist[$v1];
           }
		  
		$dep = array_unique($dep);
		foreach ($dep as $k2 => $v2) {
				if($v['status']==1){
					$depinfo[$v2]['ok']+=1; 
				}else{
					$depinfo[$v2]['no']+=1;
				}
				$depinfo[$v2]['all'] = $depinfo[$v2]['ok']+$depinfo[$v2]['no'];
				$depinfo[$v2]['name']=$depname[$v2];
				$depinfo[$v2]['id'] = $v2;
            }
        }
		$list =array();
		// $list[$depid]['ok']=0;
        foreach ($depinfo as $key => $value) {
            $path = $deppath[$key];
			// dump($value);
			
			if(strstr($path,$dp)){
				// print($value['ok']);
				$list[$key] = $value;
				$list[$depid]['ok']+=(int)$value['ok'];
				// dump($list);
				// // die;
				$list[$depid]['no']+=$value['no'];
				
			
				$list[$depid]['name'] = $depname[$depid];
				$list[$depid]['id'] = $depid;
					
			}
        }
		$list[$depid]['all'] = $list[$depid]['ok']+$list[$depid]['no'];
		// dump($list);
		$lk = array_keys($list);
		foreach($depname as $k => $v){
			$path = $deppath[$k];
			if(strstr($path,$dp)){
				if(!in_array($k,$lk)){
					$binfo[$k]['ok'] = 0;
					$binfo[$k]['no'] = 0;
					$binfo[$k]['name'] = $v;
					$binfo[$k]['id'] = $k;
				}
			}
		
				
		}
		$list = array_merge($list,$binfo);
		$flag = array();
		foreach($list as $key => $v){
			$flag[]=$v["all"];
		}
		 array_multisort($flag,SORT_DESC,$list);
		
		$this->assign('list',$list);
		$this->display();
		
	
    }
	// 区域统计
	public function rstat(){
		$bid = session('adminuser.depid');
		if($role == C('ROLE_ZHIFA_ID')){
			$this->redirect(U('Task/stat'));	
		}
		if($role!=C('ROLE_ZHIFA_ID') && $bid!=0){
			$this->redirect(U('Task/fstat'));	
		}
		$userlist = D('Adminuser')->getfield('id,region',true);
		$relist =  D('Region')->getfield('RegionCode,RegionName',true);
		$reg = session('adminuser.region');
	
		if(strlen($reg)>=6){
	
			$this->redirect(U('Task/stats',array('rid',$reg)));	
		}
		$regl = strlen($reg)+2;
	
		$tlist = D('Task')->select();
        $reinfo = array();
		$list =array();
		$rinfo = array();
        foreach ($tlist as $k => $v) {
           $userid =array_filter(explode(',',$v['userId']));
           $dep =array();
           foreach ($userid as $k1 => $v1) {
               $region[] = $userlist[$v1];
           }
		  
		$region =array_filter(array_unique($region));
		foreach ($region as $k2 => $v2) {
				if($v['status']==1){
					$reinfo[$v2]['ok']+=1; 
				}else{
					$reinfo[$v2]['no']+=1;
				}
				$reinfo[$v2]['all'] = $reinfo[$v2]['ok']+$reinfo[$v2]['no'];
				$reinfo[$v2]['name']=$relist[$v2];
				$reinfo[$v2]['id'] = $v2;
            }
        }
		foreach ($reinfo as $key => $value) {
            // $path = $deppath[$key];
			$list[$reg]['ok'] = 0;
			$list[$reg]['no'] = 0;
			if(strstr($key,$reg) && strlen($key)<=$regl){
				$list[$key] = $value;
				$list[$reg]['ok']+= $value['ok'];
				$list[$reg]['no']+= $value['no'];
			}
        }
		
		$list[$reg]['all'] = $list[$reg]['ok']+$list[$reg]['no'];
		$list[$reg]['name'] = $relist[$reg];
		$list[$reg]['id'] = $reg;
	
		$lk = array_keys($list);
		foreach($relist as $k => $v){
			
			if(strstr($k,$reg) && strlen($k)<=$regl){
				if(!in_array($k,$lk)){
					$rinfo[$k]['ok'] = 0;
					$rinfo[$k]['no'] = 0;
					$rinfo[$k]['all'] = 0;
					$rinfo[$k]['name'] = $v;
					$rinfo[$k]['id'] = $k;
				}
			}
		
				
		}
		$list = array_merge($list,$rinfo);
		$flag = array();
		$sum = 0;
		foreach($list as $key => $v){
			$sum+=$v["all"];
		}
		if($sum ==0){
			foreach($list as $key => $v){
				$flag[]=$v["id"];
			}
			array_multisort($flag,SORT_ASC,$list);
		}else{
			foreach($list as $key => $v){
				$flag[]=$v["all"];
			}
			array_multisort($flag,SORT_DESC,$list);
		}
	
	
	
		$this->assign('list',$list);
		$this->display();
	}
	public  function stats(){
		$where = array();
		
		if(!empty($_REQUEST['username'])){
			
			$uid = D('Adminuser')->where(array('realname'=>$_REQUEST['username']))->getField('id');
			
			if($uid){
				$where['id'] = $uid;
			}
		}
		$code =  $_REQUEST['rid'];
        $code = empty($_REQUEST['province']) ? $code : I('province');
        $code = empty($_REQUEST['city']) ? $code : I('city');
        $code = empty($_REQUEST['country']) ? $code : I('country');
		
		if(!empty($code)){
			$where['region'] = array('like',"{$code}%");
		}
		if(!empty($_REQUEST['id'])){
			
			$path  = D('Department')->where(array('id'=>$_REQUEST['id']))->getfield('path');
			$depid['path']=array('like',"{$path}%");
			$bid = D('Department')->where($depid)->getfield('id',true);
			$bid = implode(',',$bid);
			$where['depid'] =  array('in',$bid);
		}
		if(!empty($_REQUEST['bid'])){
			$path  = D('Department')->where(array('id'=>$_REQUEST['bid']))->getfield('path');
			$depid['path']=array('like',"{$path}%");
			$bid = D('Department')->where($depid)->getfield('id',true);
			$bid = implode(',',$bid);
			$where['depid'] =  array('in',$bid);
			
		}
		$where['role_id'] =C('ROLE_ZHIFA_ID');
		$userid = D('Adminuser')->where($where)->getfield('id',true);
		

		$map['_string'] = '1=1';
		foreach ($userid as $key => $value) {
			
			if($key==0){
			
				$map['_string'].=" and find_in_set('{$value}',userId)";
			}else{
				$map['_string'].=" or find_in_set('{$value}',userId)";
			}
		}
		$list = D('Task')->where($map)->select();
		
		//   dump(D()->_sql());
	
		foreach($list as $k=>$v){
			
			$u = explode(',',trim($v['userId'],','));
			// dump($u);
			foreach($u as $user){
				if($uid && $user != $uid){
					continue;
				}
				if(!in_array($user,$userid)){
					continue;
				}
				if($v['status'] == 1){
					
					$res[$user]['ok'] ++;
					
				}else{
					$res[$user]['notok'] ++;
				}
				if(!empty(json_decode($v['result'],true)['option'])){
					$res[$user]['abnormal'] ++;
				}else{
					$res[$user]['normal'] ++;

				}
			}
		}
		// dump($res);
		$users = array_keys($res);
		
		// die;
		
		if($users){
		
			$uinfo = D('Adminuser')->where(array('id'=>array('in',$users)))->getField('id,realname,mobile,level,role_id,depid,region',true);
			// dump(D()->_sql());
			$branchModel = D('Department');
			//获取所有部门
			$branch = $branchModel->limit(1000)->getField('id,name',true);
			$this->assign('branch',$branch);
			$roles = C('GOV_LEVEL');
		}
	
		$list = array();
		foreach($res as $k=>$v){
			$tmp = array();
			$tmp['uid'] = $k;
			$tmp['username'] = $uinfo[$k]['realname'];
			if(!$uinfo[$k]){
				continue;
			}
			$tmp['abnormal']=(int)$v['abnormal'];
			$tmp['normal']=(int)$v['normal'];
			$tmp['allnormal'] = $tmp['abnormal']+$tmp['normal'];
			$tmp['norpro']=(int)round(($tmp['abnormal']/$tmp['allnormal'])*100).'%';
			$tmp['ok'] = (int) $v['ok'];
			$tmp['notok'] = (int) $v['notok'];
			$tmp['all'] = $tmp['ok'] + $tmp['notok'];
			$tmp['branch'] = $branch[$uinfo[$k]['depid']];
			$tmp['cellphone'] = $uinfo[$k]['mobile'];
			$tmp['roles'] = $roles[$uinfo[$k]['level']];
			$tmp['region'] = $uinfo[$k]['region'];
			$list[] = $tmp;
		}
	
		$count = count($list);
		$page = new \Think\Page($count,C('PAGE_LISTROWS'));
		$pages = $page->show();
		$this->assign('list',$list);
		$this->assign('_search_block','1');
		$this->display('stat');	
	}
	/**
	 * 任务统计
	 */
	public function stat(){
// 		$start = I('start',date('Y-m-d',strtotime('-1 year')));
// 		$end = I('end',date('Y-m-d'));
	
// 		$this->assign('start',$start);
// 		$this->assign('end',$end);
		// dump($_REQUEST);
		
		$map = array();
		$bid = session('adminuser.depid');
		$role =session('adminuser.role_id');
		$region = session('adminuser.region');
		$path = session('adminuser.dep_path');
		$id = session('adminuser.id');
		$table = 'tb_task t';
		$join = 'left join tb_task_object o on t.oid=o.id';
		$field ='*,t.status as ts';
		// $map['region'] = array('like',"{$region}%");
		
		if($role!=C('ROLE_ZHIFA_ID') && $bid!=0){
			$where['path'] =array('like',"{$path}%"); 
			$depid = D('Department')->where($where)->getfield('id',true);
			$depid = implode(',',$depid);
			$map['createdepid'] = array('in',"{$depid}");
		}
			
		if($role == C('ROLE_ZHIFA_ID')){
			$map['t.userId'] = array('like',"%,{$id},%");
		}
		
		if(!empty($_REQUEST['username'])){
		
			$uid = D('Adminuser')->where(array('realname'=>$_REQUEST['username']))->getField('id');
			
			if($uid){
				$where['userId'] = array('like',"%,{$uid},%");
			}
		}
		$uid = session('adminuser.id');
	/* 	$where['start'] = array('egt',$start);
		$where['end'] = array('elt',$end); */
	
		//创建分页对象
		$listRows = 20;
		// $page = new \Common\Util\NewPage($count, $listRows);
		$page = new \Think\Page($count,C('PAGE_LISTROWS'));
		
	
			
		$list = D()->table($table)->join($join)->where($map)->field($field)->order('t.id desc')->limit($page->firstRow . ',' . $page->listRows)->group($group)->select();
		// $list = $this->model->where($map)->select();
		// dump(D()->_sql());
		if($_REQUEST['debug']){
			 dump($this->model->_sql());
			 dump($list);
		}
		$res = array();

		foreach($list as $k=>$v){
			
			$u = explode(',',trim($v['userId'],','));
			// dump($u);
			
			foreach($u as $user){
				if($uid && $user != $uid){
					continue;
				}
				// if($uid!=$user){
				// 	continue;
				// }
				if($v['ts'] == 1){
					
					$res[$user]['ok'] ++;
					
				}else{
					$res[$user]['notok'] ++;
				}
				if(!empty(json_decode($v['result'],true)['option'])){
					$res[$user]['abnormal'] ++;
				}else{
					$res[$user]['normal'] ++;
				}
			}
		}
		// dump($res);
		$users = array_keys($res);

		if($users){
			$uinfo = D('Adminuser')->where(array('id'=>array('in',$users)))->getField('id,realname,mobile,level,role_id,depid,region',true);
			// dump(D()->_sql());
			$branchModel = D('Department');
			//获取所有部门
			$branch = $branchModel->limit(1000)->getField('id,name',true);
			$this->assign('branch',$branch);
			$roles = C('GOV_LEVEL');
		}
	
		$list = array();
		foreach($res as $k=>$v){
			$tmp = array();
			$tmp['uid'] = $k;
			$tmp['username'] = $uinfo[$k]['realname'];
			if(!$uinfo[$k]){
				continue;
			}
			$tmp['abnormal']=(int)$v['abnormal'];
			$tmp['normal']=(int)$v['normal'];
			$tmp['allnormal'] = $tmp['abnormal']+$tmp['normal'];
			$tmp['norpro']=(int)round(($tmp['abnormal']/$tmp['allnormal'])*100).'%';
			$tmp['ok'] = (int) $v['ok'];
			$tmp['notok'] = (int) $v['notok'];
			$tmp['all'] = $tmp['ok'] + $tmp['notok'];
			$tmp['branch'] = $branch[$uinfo[$k]['depid']];
			$tmp['cellphone'] = $uinfo[$k]['mobile'];
			$tmp['roles'] = $roles[$uinfo[$k]['level']];
			$tmp['region'] = $uinfo[$k]['region'];
			$list[] = $tmp;
		}
		// dump($list);
		$count = count($list);
		$page = new \Think\Page($count,C('PAGE_LISTROWS'));
		$pages = $page->show();
		
	
		$this->assign('list',$list);
		$this->assign('pages',$pages);// dump($list);
		$this->assign('_search_block','1');
		$this->display();
	}
	
	/**
	 * 导出任务
	 */
	public function export(){

		$oid = I('oid');
		$addTime = I('addTime');

		if(!empty($oid)){
			$map['oid'] = $oid;
		}
		if(!empty($addTime)){
			$map['addTime'] = urldecode($addTime);
		}
		$list = $this->_list($this->model, $map);
		if($list){
			//获取用户
			$pids = array_column($list, 'placeid');
			$places = D('Place')->where(array('id'=>array('in',$pids)))->getField('id,name,address,faren,cellphone',true);
			$this->assign('places',$places);
			$uids = array_column($list, 'userId');
			$uids = implode(',', $uids);
			$uids = array_unique(array_filter(explode(',', $uids)));
			$uinfos = D('Adminuser')->where(array('id'=>array('in',$uids)))->getField('id,realname',true);
			$myId = $this->agent['id'];
			$out = array();
			foreach($list as $k=>$v){
				$tmp = '';
				$u = explode(',', trim($v['userId'],','));
				foreach($u as $v1){
					$tmp .= ','.$uinfos[$v1];
				}
		
				$v['receive'] = trim($st,',');
				$v['username'] = trim($tmp,',');
				$list[$k] = $v;
				$a = array();
				$a[] = $v['id'];
				$a[] = $v['start'];
				$a[] = $v['end'];
				$a[] = $places[$v['placeid']]['name'];
				$a[] = $places[$v['placeid']]['faren'];
				$a[] = $places[$v['placeid']]['cellphone'];
				$a[] = $v['username'];
				$a[] = $v['status'] == 1 ?'已完成':'未完成';
				$a[] = $v['status'] == 1 ?$v['realTime']:'-';
				$out[] = $a;
			}
		}
		$th = array('序号','任务开始时间','任务结束时间','场所','法人','联系电话','执法人','状态','实际完成时间');
		$this->doexport('导出任务数据-'.date('Y-m-d H:i'),$th,$out);
		
	}
	
	
	/**
	 * 任务列表
	 */
	public function index(){
		
		$agent = session('adminuser');
		$bid = session('adminuser.depid');
		$role =session('adminuser.role_id');
		$region = session('adminuser.region');
		$path = session('adminuser.dep_path');
		$table = 'tb_task t';
		$join = 'left join tb_task_object o on t.oid=o.id';
		$map['region'] = array('like',"{$region}%");
		
		if($role!=C('ROLE_ZHIFA_ID') && $bid!=0){
			$where['path'] =array('like',"{$path}%"); 
			$depid = D('Department')->where($where)->getfield('id',true);
			$depid = implode(',',$depid);
			$map['createdepid'] = array('in',"{$depid}");
		}
			
		if($role == C('ROLE_ZHIFA_ID')){
			$this->redirect(U('Task/detail'));
		}
			
		if(!empty($_REQUEST['Barname'])){
			$map['name'] =array('like',"%{$_REQUEST['Barname']}%");  
		}
	
		
		
		$group = "t.addTime";
		
		$alltask = D()->table($table)->join($join)->where($map)->group($group)->select();
		$count = count($alltask);
		
		if($count>0){
			import("@.ORG.Page");
			//创建分页对象
			$listRows = 20;
			// $page = new \Common\Util\NewPage($count, $listRows);
			$page = new \Think\Page($count,C('PAGE_LISTROWS'));
			$pages = $page->show();
			$field = "*,count(t.id) as tnum,sum(if(t.status=1,1,0)) as finish,sum(if(t.status=0,1,0)) as unfinish";
			
			$list = D()->table($table)->join($join)->where($map)->field($field)->order('t.id desc')->limit($page->firstRow . ',' . $page->listRows)->group($group)->select();
			
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			$this->assign('list', $voList);
			$this->assign('sort', $sort);
			$this->assign('order', $order);
			$this->assign('sortImg', $sortImg);
			$this->assign('sortType', $sortAlt);
			$this->assign("pages", $pages);
			$this->assign('qparams',$this->qparams);
			$this->assign('_search_block','1');
		}

		$oids = array_column($list, 'oid');
		if($oids){
			$oinfos = D('TaskObject')->where(array('id'=>array('in',$oids)))->getField('id,id,name',true);
		}
		$this->assign('oinfos',$oinfos);
		
		$this->assign('list',$list);
		$this->display();
	}
	
	public function detail(){
		$this->assign('actname','index');
		$agent = session('adminuser');
		if($agent['isadmin'] != 1){
			$map['userId'] = array('like',",%{$agent['id']}%,");
			// dump($map);
		}
		
		$oid = I('oid');
		$addTime = I('addTime');
		
		if(!empty($oid)){
			$map['oid'] = $oid;
		}
		if(!empty($addTime)){
			$map['addTime'] = urldecode($addTime);
		}
		$list = $this->_list($this->model, $map);
		
		
		// dump($map);
		if($list){
			//获取用户
			$pids = array_column($list, 'placeid');
			$places = D('Place')->where(array('id'=>array('in',$pids)))->getField('id,name,address,faren,cellphone',true);
			$this->assign('places',$places);
			$uids = array_column($list, 'userId');
			// dump($uids);
			$uids = implode(',', $uids);
			$uids = array_unique(array_filter(explode(',', $uids)));
			$uinfos = D('Adminuser')->where(array('id'=>array('in',$uids)))->getField('id,realname',true);
			$myId = $agent['id'];
			// dump($myId);
			foreach($list as $k=>$v){
				$tmp = '';
				$u = explode(',', trim($v['userId'],','));
				foreach($u as $v1){
					$color = 'green';
					$tmp .= ',<span style="color:'.$color.'">'.$uinfos[$v1].'</span>';
		
				}
				// dump($u);
				
				if(in_array($myId,$u)){
					// echo '1';
					$v['isMine'] = 1;
				}else{
					// echo '0';
					$v['isMine'] = 0;
				}
		
				$v['receive'] = trim($st,',');
				$v['username'] = trim($tmp,',');
				$list[$k] = $v;
					
			}
				
		}
		// dump($list);
		$this->assign('list',$list);
		
		$this->display();
	}
	
	/**
	 * 回执任务
	 */
	public function feed(){

		if(IS_POST){
			//提交
			$id = I('id');
			if(empty($id)){
				$this->error('参数错误');
			}
			$mark = I('mark');
			$option = I('optionId');
			$realTime = I('realTime');
			$barcode = I('barcode');
			if(empty($realTime)){
				$this->error('请选择实际完成时间');
			}
			$myId = $this->agent['id'];
			if(!empty($option)){
				$data['abnormal'] = 1;
			}
			$data['result'] = json_encode(array('option'=>$option,'mark'=>$mark,'create_user'=>$myId,'create_time'=>time()));
			$data['status'] = 1;
			$data['id'] = $id;
			$data['realTime'] = $realTime;
			$data['feedpic'] = json_encode($_REQUEST['pic']);
			$data['feedvedio'] = I('feedvedio');
			
			$res = D('Task')->save($data);
			$map['starttime'] = array('ELT',"{$realTime}"); 
			$map['endtime'] = array('EGT',"{$realTime}");
			$region = session('adminuser.region');
			$depid = session('adminuser.depid');
			$path = session('adminuser.dep_path');
		
			$keys =array(0,2,4,6);
			foreach($keys as $k=>$v){
				if($k==0){
					$map['_string'].=' region = '.$region;
				}elseif(strlen(substr($region,0,-$v))>0){
					$map['_string'].=' or region = '.substr($region,0,-$v);
				}
			}
			if($depid!=0){
				$map['depid'] = array('in',"{$path}");
			}
	
			$count = M('plan')->where($map)->getfield('id,okcount',true);
		
			foreach($count as $k=>$v){
				$cdata['okcount']=$v+1;
				$save = M('plan')->where(array('id'=>$k))->save($cdata);
			}
		
		
			if($res){
				$this->success('提交成功');
			}else{   
				$this->error('提交失败');
			}
			
			exit;
		}
		
		$id = I('id');
		if(empty($id)){
			$this->error('非法请求！');
		}
		$info =D('Task')->where(array('id'=>$id))->find();
		if(empty($info)){
			$this->error('该任务不存在或已失效！');
		}
		$placeinfo = D('Place')->where(array('id'=>$info['placeid']))->find();
		// dump($placeinfo);
		$con = D('Option')->getCacheAll($placeinfo['typeid']);
			
		$custom = D('TaskObject')->where(array('id'=>$info['oid']))->getfield('customs');	
		if($cutom!=0){
			$con  = D('OptionCustom')->getCacheAll($custom);
		}
	
		
	
		
		// $con = D('Option')->getCacheAll($placeinfo['typeid']);
		$this->assign('con',$con);
		$this->assign('info',$info);
		$this->assign('placeinfo',$placeinfo);
		$this->display();
	}
	
	/**
	 * 查看任务
	 * @see \Think\Controller::show()
	 */
	public function show(){
		$id = I('id');
		if(empty($id)){
			$this->error('非法请求！');
		}
		
		$info = D('Task')->where(array('id'=>$id,'status'=>1))->find();
		
		
		if(empty($info)){
			$this->error('该任务不存在或已失效！');
		}
		$placeinfo = D('Place')->where(array('id'=>$info['placeid']))->find();
		$con = D('Option')->getCacheAll($placeinfo['typeid']);
		$custom = D('TaskObject')->where(array('id'=>$info['oid']))->getfield('customs');	
		if($cutom!=0){
			$con  = D('OptionCustom')->getCacheAll($custom);
		}
		$res = json_decode($info['result'],true);
		$mark = $res['mark'];
		$option = $res['option'];
		$createUser = $res['create_user'];
		$createTime = $res['create_time'];
		$info['feedpic'] = json_decode($info['feedpic'],true);
		$this->assign('con',$con);
		$this->assign('info',$info);
		$this->assign('option',$option);
		$this->assign('mark',$mark);
		$this->assign('createUser',query_user($createUser,'username'));
		$this->assign('createTime',date('Y-m-d H:i:s',$createTime));
		$this->display();
	}
	
	
	/**
	 * 添加检查
	 * @see \Home\Controller\BaseController::add()
	 */
	public function add(){
		
		$this->assign('ptype',D('Ptype')->getCacheAll());
		$this->display();
	}
	
	
	//获取项目信息
	public function getObject(){
		$oid = I('oid');
		$list = D('TaskObject')->where(array('id'=>$oid))->find();
		$uids = explode(',',trim($list['userid'],','));
		$typeids = explode(',',trim($list['ptypeid'],','));
		$types = D('Ptype')->where(array('id'=>array('in',$typeids)))->getField('id,name',true);
		
		$list['usercount'] = count($uids);
		$str = '';
		$bid = $list['bid'];
		$deps = D('Department')->where(array('id'=>array('in',$bid)))->getField('id,name',true);

		$where = array();
		
		$where['id'] = array('in',$uids);
		$uinfos = D('Adminuser')->where($where)->order('depid asc')->getField('id,realname,role_id,level,depid',true);
		$set = array();
		$level = array();
		foreach($uinfos as $k=>$v){
			$level[] = $v['level'];
			if(!$set[$v['depid']]){
				$v['mark'] =  "<span style='color:#999;font-size:12px'>[{$deps[$v['depid']]}]</span>";
				$set[$v['depid']] = 1;
				$uinfos[$k] = $v;
			}
		}
		
		foreach($uinfos as $n){
			$str .= "{$n['mark']}<a data-id='' style='margin-right:5px;background:#ecfaff;padding:2px;'>{$n['realname']}</a>";
		}
		//$list['usernames'] = implode(',',$uinfos);
		$list['usernames'] = $str;
		$str = '';
		foreach($types as $n){
			$str .= "<a data-id='' style='margin-right:5px;background:#ecfaff;padding:2px;'>{$n}</a>";
		}
		$list['ptypes'] = $str;
		//$list['ptypes'] = implode(',',$types);
		$bid = $list['bid'];
		
		$str = "";
		foreach($deps as $n){
			$str .= "<a data-id='' style='margin-right:5px;background:#ecfaff;padding:2px;'>{$n}</a>";
		}
		
		$list['bname'] = $str;
	    $list['types'] = $types;
		$this->ajaxReturn($list);
	}
	
	
	//随机场所
	//如果是单个部门抽取 一年内可以抽取2次 超过2次提醒
	//跨部门任务 一年内抽取2次 超出提醒
	public function getBarRand(){
		$pnum = I('pnum') ? I('pnum') : 5;
		$oid = I('oid');
		$ptypeid = trim(I('ptypeid'),',');
		// dump(I('ptypeid'));
		$oinfo = D("TaskObject")->where(array('id'=>$oid))->find();
		$bar = array();
		$count = 0;

		$bid = $oinfo['bid'];
		$barr = explode(',', $bid);
		//高优先级
		if(!empty($oinfo['placeid'])){
			$ps = explode(',', $oinfo['placeid']);
			$arr = D('Place')->where(array('id'=>array('in',$ps)))->select();
			foreach($arr as $k=>$v){
				$arr[$k]['check'] = 1;
			}
			if($arr){
				$bar = array_merge($arr,$bar);
			}else{
				$arr = array();
			}
			$count = count($arr);
		}
		
		
		$ptypeid = $_REQUEST['ptypeid'];
		$ptypeid = explode(',', $ptypeid);
		
		foreach($ptypeid as $k=>$v){
			$num = $_REQUEST['p_'.$v];
			if($num){
				$set[$v] = $num;
			}
		}
		$pt = array_keys($set);
		
		$region = session('adminuser.region');
		
		$year = date('Y');
		
		//查找每个场所的今年历史记录
		$where = array();
		$where['p.region'] = array('in',$oinfo['rid']);
		$where['t.joinstatus'] = array('neq',2);//联合执法通过 
		$join = "left join tb_place p on p.id=t.placeid left join tb_task_object o on o.id=t.oid";
		$group = "t.placeid";
		
		
		//单个部门
		if(count($barr)==1){
			$where['o.bid'] = $bid;
		}else{
			$where['o.bid'] = array('like','%,%');
		}
		
		
		$field = "p.typeid,t.placeid,sum(if(t.start like '{$year}%' and t.end like '{$year}%',1,0)) as ynum";

		
		$history = D()->table('tb_task t')->field($field)->join($join)->where($where)->group($group)->select();
		
		
		$notice = array(); //一年内未超过3次的 警告抽取
		
		
		foreach($history as $v){
			if( $v['ynum']>=2){
				$notice[$v['typeid']][] = $v['placeid'];
			}
		}
		$rid = array();
		$rids = array();
		$orid = explode(',',$oinfo['rid']);
		// dump($oinfo['rid']);
		foreach ($orid as $key => $v) {
			$map['RegionCode'] = array('like',"$v%");
			$rids = $rid;
			$rid  = D('region')->where($map)->getfield('RegionCode',true);
			// print(D()->_sql());
			$rid = array_merge($rid,$rids);

		}
		// dump($rid);
		$rid =implode(',',array_unique($rid));
		// print_r($rid);
		//获取指定的场所类型
		//先抽取排除高优先级和当月排除的场所 如果不够 则从警告抽取里面拉取
		foreach($set as $k=>$v){
			$arr1 = array();
			$bid  = $oinfo['bid'];
			$where = array();
			
			$ext = array();
			
			//从高优先级里面排除
			if($ps){
				$ext = $ps;
			}
			
			$noticePlace = $notice[$k];
			if($noticePlace){
				$ext = array_merge($noticePlace,$ext);
			}

		
			//先排除超过2次的和高优先级
			if($ext){
				$where['id'] = array('not in',$ext);
			}
			$where['typeid'] = $k;
		
		
			$where['region'] = array('in',$rid);
			
			
			$field = "*,1 as checked, 0 as noticed"; //选中并且不提醒
			//先查找一年内未抽过的
			$arr1 =  D('Place')->join()->where($where)->field($field)->limit($v)->order('rand()')->select();
			// print(D()->_sql());
			if($arr1){
				$bar = array_merge($bar,$arr1);
			}
			
			//如果个数不够 并且有类型下有警告可抽取（年>2次 月没有） 从警告里面抽取  v-count($arr1);
			$arr2 = array();
			$count = count($arr1);
			if($count <= $v && $notice[$k]){
				$where = array();
				$where['region'] = array('in',$rid);
				$where['id'] = array('in',$notice[$k]);
				$field = "*,1 as checked ,1 as noticed"; //选中并且提醒
				$li = $v-$count;
				$arr2 =  D('Place')->where($where)->field($field)->limit($li)->order('rand()')->select();
			}
			
			if($arr2){
				$bar = array_merge($bar,$arr2);
			}
		
			
		}
		
		$ok = count($bar);
		
		//获取干扰项
		$ids = array_column($bar, 'id');
		$arr1 = array();
		$where = array();
		if($ids){
			$where['p.id'] = array('not in',$ids);
		}
		$where['p.region'] = array('in',$rid);
		$where['p.typeid'] = array('in',$pt);
		$field = "p.*";
		$arr1 =  D()->table('tb_place p')->join()->where($where)->field($field)->limit(100)->order('rand()')->select();
		foreach($arr1 as $k=>$v){	
			$arr1[$k]['checked'] = 0;
			$arr1[$k]['noticed'] = 0;
		}
		
		
		
		if($arr1){
			$bar = array_merge($bar,$arr1);
		}
		
	 	
		//$bar = array_merge($bar,$arr1);
		

		//print_r($bar);
		shuffle($bar);
		
	
		$this->ajaxReturn(array('list'=>$bar,'ok'=>$ok));
	}
	
	
	public function _getUserRandByDep(){
		$unum = I('unum') ? I('unum') : 2; //一组人员
		$userid = explode(',',trim(I('userid'),','));
		$ud['id'] = array('in',$userid);
		$uinfo = D('Adminuser')->where($ud)->getField('id,realname,level,depid',true);
		
		
		$uArr = array();
		foreach($uinfo as $k=>$v){
			$uArr[$v['depid']][] = $v;
		}
		$userid =  array_keys($uinfo);
		$data = array();
		//随机获取抽取10倍以上
		// dump(I('gnum'));
		$pnum = I('pnum') ? I('pnum') : 5;
		
	
		$n = $pnum*10;
		//$n = 1;
		for($i=0;$i<$n;$i++){
			$tmp = array();
			$tmpU = array();
			$idStr = '';
			$nameStr = '';
			$keys = array_rand($userid,$unum);
		
			$uid = array();
			$uname = array();
			
			//封装uid
			foreach($uArr as $dep){
				//如果不够 则取所有
				if(count($dep)<=$unum){
					$t = array_column($dep, 'id');
					if($t){
						$uid = array_merge($t,$uid);
					}
				}else{
					//如果超出 则随机个数
					$keys = array_rand($dep,$unum);
					//如果只有一个 给出的不是数组
					if(is_array($keys)){
						foreach($keys as $kk){
							$uid[] = $dep[$kk]['id'];
						}
					}else{
						$uid[] = $dep[$keys]['id'];
					}
				
				}
			}
		
			shuffle($uid);
			
			//封装用户
			foreach($uid as $kk){
				$uname[] = $uinfo[$kk]['realname'];
					
			}
			$tmpU['names'] = implode(',', $uname);
			$tmpU['ids'] = implode(',', $uid);
				
			$data[] = $tmpU;
				
		}
		$this->ajaxReturn($data);
	}
	
	//随机用户
	public function getUserRand(){
		$utype = I('utype',1);
		if($utype==2){
			$this->_getUserRandByDep();
			exit;			
		}
		$unum = I('unum') ? I('unum') : 2; //一组人员
		$userid = explode(',',trim(I('userid'),','));
		$count = count($userid);
		$unum = $count < $unum ? $count : $unum;//实际可执法人员数量 
		//$ud['level'] = array('in',$_REQUEST['gov']);
		$ud['id'] = array('in',$userid);
		$uinfo = D('Adminuser')->where($ud)->getField('id,realname,level',true);
		$userid =  array_keys($uinfo);
		// dump(array_keys($uinfo));
		// dump($uinfo);
		// print_r(D()->_sql());
		$data = array();
		//随机获取抽取10倍以上
			// dump(I('gnum'));
		$pnum = I('pnum') ? I('pnum') : 5;
		for($i=0;$i<$pnum*10;$i++){
			$tmp = array();
			$tmpU = array();
			$idStr = '';
			$nameStr = '';
			$keys = array_rand($userid,$unum);
		
			if(is_array($keys)){
				foreach($keys as $kk){
					$uid = $userid[$kk];
					$idStr .= ",".$uid;
					$nameStr .= ",".$uinfo[$uid]['realname'];
					
				
					// dump($uinfo[$uid]['realname']);
					// dump($uinfo);
					// dump($uid);
					$tmpU['ids'] = trim($idStr,',');
					$tmpU['names'] = trim($nameStr,',');
				}
			}else{
				$uid = $userid[$keys];
				$idStr .= ",".$uid;
				$nameStr .= ",".$uinfo[$uid]['realname'];
					// dump($uinfo);
					// dump($keys);
				$tmpU['ids'] = trim($idStr,',');
				$tmpU['names'] = trim($nameStr,',');
			}
		
			
			$data[] = $tmpU;
			
		}
		
		$this->ajaxReturn($data);
	}
	
	
	//抽取页面
	public function start(){
		$ptype = I('ptypeid');
	
		foreach($ptype as $k=>$v){
			$arr['p_'.$v] = $_REQUEST['per_'.$v];
		}
		$query = http_build_query($arr);
		$gov = implode(',',I('gov'));
		// dump($gov);
		$num = I('num') ? I('num') : 5;
		$this->assign('num',$num);
		$ptypid = implode(',', $ptype);
		$this->assign('ptypeid',$ptypid);
		$this->assign('query',$query);
		$this->assign('gov',$gov);
		$this->display();
	}
	
	
	//预览界面
	public function preview(){
	
		$this->assign('actname','add');
		if(empty($_REQUEST['start']) || empty($_REQUEST['end'])){
			$this->error('请选择执法开始和结束时间');
		}
		
		if($_REQUEST['endTime'] < $_REQUEST['startTime']){
			$this->error('结束时间不能小于开始时间！');
		}
		
		$usernum = empty(I('gnum')) ? 2 : I('gnum');
		$this->assign('usernum',$usernum);
		
		$userId = I('userId');
		$userName = I('userName');
	
		$oid = I('oid'); //项目id
		
	
		
		//获取所有可用用户id  以切换用户使用
		$allUserId = trim(I('userid'),',');
		
		$allUser = D('Adminuser')->where(array('id'=>array('in',$allUserId)))->order('depid')->getField('id,id,realname,depid',true);
		$bids = array_column($allUser, 'depid');
		

		if($bids){
			$bs = D('Department')->where(array('id'=>array('in',$bids)))->getField('id,name',true);
		}
		$this->assign('bs',$bs);
		$this->assign('allUser',$allUser);
		// dump(D()->_sql());
		// dump($allUserId);
		$data = array();
		$commonData = array();//公共数据
		$commonData['start'] = I('start'); 
		$commonData['end'] = I('end'); 
		$commonData['mark'] = I('mark');
		$commonData['oid'] = I('oid');
		
		$snotice = $_REQUEST['snotice'];
		
		
		$placeId = I('placeId');//场所id
		$start = I('start');
		$end = I('end');
		$pinfo = D('Place')->where(array('id'=>array('in',$placeId)))->getField('id,name,address,faren,cellphone,license',true);
		$law = C('JOIN_LAW_ENF');
		foreach($placeId as $k=>$v){
			$tmp = array();
			// dump($k);
			$tmp['placeid'] = $v;
			$map = array();
			$map['_string'] = "(start>='{$start}' and start<='{$end}') or (end>='{$start}' and end<='{$end}') or (start>='($start}' and end<='{$end}') or (start<='{$start}' and end>='{$end}')";
			$map['placeid'] = $v;
			if($law==1){
				$hast = D('task')->where($map)->order('addTime desc')->find();
			}else{
				$hast = false;
			}
			$tmp['snotice'] =$snotice[$k];
			$tmp['joinstatus'] = 0;
			$tmp['jointo'] = 0;
			$tmp['joinuser'] = 0;
		
			if($hast){
				$tmp['joinstatus'] = 1;
				$tmp['jointo'] = $hast['id'];
				$tmp['joinuser'] = $hast['createUserId'];
				$tmponame = D('TaskObject')->where(array('id'=>$hast['oid']))->getField('name');
				$tmp['joiname'] = "该场所已在{$tmponame}项目中被抽查,请问是否需要联合执法";
			}

			$us =  explode(',', $userId[$k]);
			$userName = '';
			foreach($us as $uid){
				$userName .= "<span style='color:#999;font-size:12px;'>[{$bs[$allUser[$uid]['depid']]}]</span>".$allUser[$uid]['realname'].',';
			}
		
			$tmp['placeInfo'] = $pinfo[$v];
			$tmp['userId'] = ','.$userId[$k].','; 
			$tmp['userName'] = trim($userName,',');
			$data[] = $tmp;
			// dump($tmp);
		}
		
	
		$this->assign('commonData',$commonData);
		$this->assign('list',$data);
		// dump($data);
		// dump($commonData);
		$this->display();
	}
	
	/**
	 * 发送任务
	 */
	public function doadd(){
	
		$saveData = array();
		$commonData = array();
		$commonData = array();//公共数据
		$commonData['start'] = I('startTime');
		$commonData['end'] = I('endTime');
		$commonData['mark'] = I('mark');	
		$commonData['oid'] = I('oid');
		// dump($commonData['start']);
		$placeid = I('placeid');
		$user = I('userId');
		$setjoin = I('setingore');
		$addtime = date('Y-m-d H:i:s');//添加时间
		$uid = session('adminuser.id');
		$bid = session('adminuser.depid');
		
		$oname = D('TaskObject')->where(array('id'=>$commonData['oid']))->getField('name');
		
		$info = array();
		$my = session('adminuser');
		$info['from'] = $my['id'];
		$info['fromuser'] = queryFullRegionInfo($my['region'])."[{$my['role_name']}]".$my['realname'];
		$m = D('Task');
		
		
		$places = D('Place')->where(array('id'=>array('in',$placeid)))->getField('id,name,typeid',true);
		
		$types = D('Ptype')->getField('id,name',true);
		
		$userid = implode(',', $_REQUEST['userId']);
		$userid = array_filter(array_unique(explode(',', $userid)));
	
		if($userid){
			$users = D('Adminuser')->where(array('id'=>array('in',$userid)))->getField('id,realname,mobile',true);
		}
		
		$start = date('Y年m月d日',strtotime($commonData['start']));
		$end = date('Y年m月d日',strtotime($commonData['end']));
		$low = C('JOIN_LAW_ENF');
		
	
		foreach($placeid as $k=>$v){
			/* if(empty($user[$k])){
				continue; //没有执法人员 跳过
			} */
			
			$tmp = array();
			$tmp['oid'] =  $commonData['oid'];
			$tmp['start'] =  $commonData['start']; //公共数据
			$tmp['end']   =  $commonData['end'];
			$tmp['mark']      =  $commonData['mark'];
			$tmp['addTime']   =  $addtime;
			$tmp['userId']    =  $user[$k];
			$tmp['placeid']   =  $v;
			$tmp['createUserId'] = $uid;
			$saveData[] = $tmp;
			$set = $setjoin[$k];
			$set = explode('_', $set);
			if($set[0]==0){
				$tmp['joinstatus'] = 0;
			}else{
				$tmp['joinstatus'] = 1;
			}
			
		
			$res = $m->add($tmp);
			if($low == 1){ //开启了联合执法
				if($tmp['joinstatus'] == 1){
					$sourceid = $set[1];
					$info['placeid'] = $v;
					$info['sourceid'] = $sourceid;
					$info['taskid'] = $res;
					$info['to'] = $set[2];
					$info['addtime'] = date('Y-m-d H:i:s');
					$placename = $places[$v]['name'];
					$info['content'] = "{$placename}在{$oname}的检查中与您所检查的场所时间接近，检查时间[{$tmp['start']}]-[{$tmp['end']}]，现发起联合执法请求，同意将合并他的检查到您的检查任务中，拒绝他将重新检查该场所";
					D('infomation')->add($info);
					if(C('SEND_MSG_FLAG')==1){
						$tophone = D('Adminuser')->where(array('id'=>$info['to']))->getField('mobile');
						$msg = "您有新的联合执法任务请求待确认,检查场所{$placename},任务发起者：{$info['fromuser']},请及时处理！";
						if($tophone){
							sendSMS($tophone,$msg);
						}
					}
				}
			}
			
			$tmpu = explode(',', trim($user[$k],','));
			$pname = $places[$v]['name'];
			$ptype = $types[$places[$v]['typeid']];
			$unames = array();
			
			foreach($tmpu as $uid){
				$unames[$uid] = $users[$uid]['realname'];
			}
			if(C('SEND_MSG_FLAG')==1){
				foreach($unames as $uid=>$uname){
						unset($unames[$uid]);
						$msgu = implode(',', $unames);
						$uphone = $users[$uid]['mobile'];
						if($uphone){
							$msg = "执法队员{$uname}:您好，双随机抽查工作管理系统已为您分配检查任务。任务名称：{$oname};类型：{$ptype};检查对象:{$pname};检查搭档：{$msgu};任务时限{$start}-{$end}";
							//$uphone = '15527374110';
							//echo $msg;
							sendSMS($uphone, $msg);
						}
						$unames[$uid] = $uname;
					}
			}
		}
		
		if($res){
			$this->success('添加成功！');
		}else{
			$this->error('添加失败！');
		}
		
	}

	public function info(){
		$commonid = D('Task')->getfield('id,placeid,start,createUserId,oid',true);
		// dump($commonid);
		// return;
		$depid = session('adminuser.depid');
		// dump($depid);
		$placeid = I('placeid');
		$starttime = I('startTime');
		
		$data=array();
		if($depid!=0){
			
			foreach($placeid as $k=>$v){
			
			if(null!=($v)){
				foreach ($commonid as $k1 => $v1) {
					// dump($v1['placeid']);
					// dump($k1);
					
					if($v==$v1['placeid']&&$v=$v1['start']){
						
						$depid = D('Adminuser')->where(array('id'=>$v1['createUserId']))->getfield('depid');
						$dname =D('Department')->where(array('id'=>$depid))->getfield('name');
						$data[$k1]['to'] = $dname;
						$data[$k1]['placeid'] =$v;
						$data[$k1]['info'] =1;
						$data[$k1]['depid'] = $depid;
						$data[$k1]['taskid'] =$v1['id'];
						// dump($data);
						
					}
			}
				
				
		   }
				
		}
			
	}
	
		// return;
		// var_dump($data);
		$this->ajaxReturn($data);
}
	
	protected function doexport($title,$th,$tdata){
		$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
		
		 
		$expTitle = $title;
		$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
		 

		/* dump($title);
		dump($th);
		dump($tdata);exit; */
		
		$fileName = $expTitle;//or $xlsTitle 文件名称可根据自己情况设定
		 
		//$cellNum = count($expCellName);
		//$dataNum = count($expTableData);
		vendor('PHPExcel1.PHPExcel');
		$objPHPExcel = new \PHPExcel();
		//获取表头名称
		$expCellName = array();
		$i = 0;
		foreach($th as $k=>$v){
			$expCellName[$cellName[$k]] = $v;
			$i++; //为合并标题所用
		}
		$dataCell = array();
		foreach($tdata as $k=>$v){
			foreach($v as $k1=>$v){
				$dataCell[$k][$cellName[$k1]] = $v;
			}
		}
		 
		/* 设置当前的sheet */
		$objActSheetIndex = $objPHPExcel->setActiveSheetIndex(0);
		
		$objActSheet = $objPHPExcel->getActiveSheet(0);
		/* sheet标题 */
		$objActSheet->setTitle($expTitle);
		
		$ascii = 65;
		$cv = '';
		$me = 'A1:'.$cellName[$i-1].'1';
		 
		$objActSheet->mergeCells($me);//合并单元格 组合表头列 得到表名
		$objActSheetIndex->setCellValue('A1', $area.$expTitle);
		 
		//设置第一行居中对齐
		$objActSheet->getStyle('A1')->getAlignment()->applyFromArray(array(
				'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
		));
		 
		
		foreach($expCellName as $k=>$v){
			$width = 25;
			$objActSheet->getColumnDimension($k)->setWidth($width);
			$objActSheet->setCellValueExplicit($k.'2',$v);
			$objActSheet->getStyle($k.'2')->getAlignment()->applyFromArray(array(
					'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
			));
		}
		
		
		$i = 3;
		
		foreach($dataCell as $v){
			foreach($expCellName as $k=>$v1){
				 
				$objActSheet->setCellValueExplicit($k.$i,$v[$k]);
				$objActSheet->getStyle($k.$i)->getAlignment()->applyFromArray(array(
						'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
				));
			}
			$i ++;
		}
		
 
		/* 生成到浏览器，提供下载 */
		ob_end_clean();  //清空缓存
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
		header("Content-Type:application/force-download");
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header("Content-Disposition:attachment;filename=$fileName.xlsx");//attachment新窗口打印inline本窗口打印
		header("Content-Transfer-Encoding:binary");
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		
		
	}
	public function map(){
		
		$idx=  I('idx',1);
		$this->assign('idx',$idx);
	
		cookie('idx',1);
		$auth =  session('adminuser.region');
		$areacode = $_REQUEST['acode'] ? $_REQUEST['acode'] : substr(cookie('currArea'),0,4);
		$areacode = $areacode ? $areacode : $auth;
		$areacodes = $_REQUEST['acode'] ? $_REQUEST['acode'] : $auth;
	
		if($idx>1){
		
			cookie('idx',2);
			cookie('currArea',$areacode);
			redirect(U('nmap',array('areacode'=>$areacode,'idx'=>$idx)));
		}
	
		if(strlen($areacode)<strlen($auth)){
		
			$areacode = $auth;
		}
		// dump($areacodes);
		// die;
		$cur = $areacode;
		$curname = queryFullRegionInfo($cur);
	
		cookie('idx',1);
		// cookie('currArea',$areacode);
		// cookie('curName',$curname);
		// dump(cookie('idx'));
		$this->assign('curname',$curname);
		
		$this->assign('curcode',$cur);
		
		$m = D('Task');
		$len = strlen($auth)+2;
		$join = "left join tb_place p on p.id=t.placeid";
		
		$field = "substr(p.region,1,{$len}) as areacode,count(t.id) as tot,sum(if(t.status=0,1,0)) as undo1,sum(if(t.status=1,1,0)) as hasdo,sum(if(t.joinstatus=2,1,0)) as joins";
		
		$group = "areacode";
		
		$where = array();
		$where['p.region'] = array('like',"{$auth}%");
		
		$list = D()->table('tb_task t ')->field($field)->join($join)->where($where)->group($group)->select();
	
		$data = array();
		$red = '#dF65B0';
		$yellow = '#FFD700';
		$blue = "#AFE7FE";
		$green = "#90ED7D";
		foreach($list as $k=>$v){
			$data[$v['areacode']]['total'] = $v['tot'];
			$data[$v['areacode']]['hasdo'] = $v['hasdo'];
			$data[$v['areacode']]['undo1'] = $v['undo1'];
			$data[$v['areacode']]['joins'] = $v['joins'];
			$per = round($v['hasdo']*100/$v['tot']);
			if($per>=80){
				$color = $green;
			}elseif($per>=60){
				$color = $yellow;
			}else{
				$color = $red;
			}
			$data[$v['areacode']]['per'] = $per.'%';
			$data[$v['areacode']]['color'] =  $color;
			$data[$v['areacode']]['name'] = queryFullRegionInfo($v['areacode']);
		}
		
		
		$this->assign('areacode',$cur);
		$this->assign('tjsonData',json_encode($data));
		$this->display();
	}
	
	public function nmap(){
		$data = array();
		
		$areacode = $_REQUEST['areacode'];
		$areaname = D('region')->where(array('RegionCode'=>$areacode))->getField('RegionName');
		$cxy = D('region')->where(array('RegionCode'=>$areacode))->getField('cxy');
		
		if($areacode=='320405'){
			$areaname = '戚墅堰区';
		}
		$this->assign('tjsonData',json_encode($data));

		$where = array();
		$where['x'] = array('neq','');
		$where['y'] = array('neq','');
		$where['region'] = array('like',"{$areacode}%");
		$places = D('place')->where($where)->select();
		$places = !$places ? array():$places;
		
		
		$where = array();
		$where['p.region'] = array('like',"{$areacode}%");
		$join = "left join tb_place p on p.id=t.placeid";
		$field = "placeid,sum(if(status=0,1,0)) as s0 ,sum(if(status=1,1,0)) as s1";
	
		$group = 'placeid';
		$list = D()->table('tb_task t ')->field($field)->join($join)->where($where)->group($group)->select();
		
		$task = array();
		foreach($list as $k=>$v){
			$task[$v['placeid']] = $v;
			if($v['s0']>0){
				$undo[] = $v['placeid'];
			}else{
				$do[] = $v['placeid'];
			}
		}
		foreach($places as $k=>$v){
			if(in_array($v['id'], $undo)){
				$v['dostatus'] =1;
			}elseif(in_array($v['id'], $do)){
				$v['dostatus'] = 2;
			}else{
				$v['dostatus'] = 0;
			}
			$v['hasdo'] =(int) $task[$v['id']]['s1'];
			$v['undo'] = (int) $task[$v['id']]['s0'];
			$places[$k] = $v;
		}
		$cname=queryFullRegionInfo($areacode);
		// dump($places);
		// die;
		$this->assign('places',json_encode($places,JSON_UNESCAPED_UNICODE));		
		$this->assign('areacode',$areacode);
		$this->assign('areaname',$areaname);
		$this->assign('cxy',$cxy);
		$this->assign('cname',$cname);
		$this->display();
	}
    

    
    
    
}