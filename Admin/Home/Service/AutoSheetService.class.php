<?php
namespace Home\Service;
use Home\Service\Service;
class AutoSheetService extends Service{
	
	/**
	 * 生成
	 */
	public function feed(){
		$params['method'] = 'Barinfo/callBack';
		$rs = getApi($params);
		
		if($rs['status'] == 1){
			$this->_addSheet($rs['data'],3,301,1);
			exit;
		}
		
		echo 'no data<br/>';
	}
	
	
	/**
	 * 手动提前追加一天的工单
	 * @param unknown $startDay
	 */
	public function handleNewSheet(){
		$rs = getCronCfg('lastAutoNewSheetDate');
		$moniToday = date('Y-m-d',strtotime('+1 day',strtotime($rs)));
		$startDay = Date('Y-m-d',strtotime('-4 day',strtotime($rs)));
		//$rs = setCronCfg('lastAutoNewSheetDate',date('Y-m-d'));
		$params['method'] = 'Barinfo/getNewBar';
		$params['startDay'] = $startDay;		
		
		$rs = getApi($params);
	
		if($rs['status'] == 1){
			$this->_addSheet($rs['data'],1,101,1);
			setCronCfg('lastAutoNewSheetDate', $moniToday);
		}
		
	}
	
	
	/**
	 * 获取安装后第5天的新装网吧 生成工单
	 *  根据配置文件  lastAutoNewSheetDate 检测今日是否已执行 执行了就不再执行 去重
	 */
	public function autoNewSheet(){
		
		
		$rs = getCronCfg('lastAutoNewSheetDate');
	
		if($rs && $rs >= date('Y-m-d')){
			echo "has done";
			exit;
		}
			//$rs = setCronCfg('lastAutoNewSheetDate',date('Y-m-d'));
		
		$params['method'] = 'Barinfo/getNewBar';
		$params['day'] = 5;
		
		$rs = getApi($params);
		
		if($rs['status'] == 1){
			$this->_addSheet($rs['data'],1,101,1);
			setCronCfg('lastAutoNewSheetDate', date('Y-m-d'));
			exit;
		}
		
		echo 'no data<br/>';
	}
	
	
	/**
	 * 获取擅停3天网吧 生成工单
	 */
	public function autoStopSheet(){
		$rs = getCronCfg('lastAutoStopSheetDate');
		if($rs && $rs >= date('Y-m-d')){
			echo "has done";
			exit;
		}
		//$rs = setCronCfg('lastAutoNewSheetDate',date('Y-m-d'));
		
		
		$params['method'] = 'Barinfo/getStopBar';
		$params['day'] = 3;
		
		$rs = getApi($params);
		
		if($rs['status'] == 1){
			$this->_addSheet($rs['data'],2,299,1);
			setCronCfg('lastAutoStopSheetDate', date('Y-m-d'));
			exit;
		}
		
		echo 'no data<br/>';
	}
	
	
	
	
	
	
	/**
	 * 添加工单
	 * @param array  $list 网吧列表
	 * @param int    $stype 工单类型 1为新装 2为擅停 3为定期回访
	 * @param int 	 $ptype 故障类型
	 * @param int 	 $plevel 优先级
	 * 
	 */
	protected function _addSheet($list,$stype,$ptype,$plevel=1){
		if(!$list){
			return;
		}
		
		switch($stype){
			case 1:
				$mark = "系统生成新装回访任务";
				break;
			case 2:
				$mark = "系统生成擅停回访任务";
				break;
			case 3:
				$mark = "系统生成定期回访任务";	
				break;
			case 6:
				$mark = "系统生成投诉建议任务";
				break;	
				
		}
		
		$smodel = D('Wsheet');
		$rmodel = D('Wrecord');
		$fromUser =  0;
		$passto = 26;
		$i = 0 ;
		foreach($list as $k=>$v){
			$data = array();
			$now = date('Y-m-d H:i:s');
		
			$data['create_user'] = $fromUser;
			$data['create_time'] = $now;
			$data['barcode'] = $v['Barcode'] ? $v['Barcode'] : '';
			$data['barname'] = $v['Name'] ? $v['Name'] : '';
			$data['stype'] = $stype; //工单类型
			$data['ptype'] = $ptype; //故障码
			$data['optype'] = 2; //自动工单
			$data['plevel'] = $plevel; //优先级 默认为一般
			$data['vtype'] = 1;
			$data['person'] = $v['FaRenName'] ? $v['FaRenName'] : '';
			$data['phone'] = $v['Phone'] ? $v['Phone'] : '';
			$data['email'] = '';
			$data['qq'] = '';
			$data['cid'] = '';
			$data['cname'] = '';
			$data['uniqueid'] = '';
			//$data['mark'] = $mark;
			
			$smodel->startTrans();
			$wid = $smodel->add($data);
			//print_r($smodel->_sql());
			
			$data = array();
			$data['wid'] = $wid; //工单id
			$data['fromuser'] = 0;//创建人
			$data['passto'] = $passto;//转交人
			$data['addtime'] = $now;//转交时间
			$data['op'] = "[系统]"."&nbsp;&nbsp;新建工单,转交给 &nbsp;&nbsp;[客服主管-邹信]";
			if($stype == 6){
				$mark = "系统生成投诉建议任务:<br/>".$v['msg'];
			}
			
			$data['mark'] = $mark;
		
			//dump($data);
			$rid = $rmodel->add($data);
		//	print_r($rmodel->_sql());
			if($wid && $rid){
				$i++;
				$smodel->commit(); 
			}else{
				$smodel->rollback();
			} 
		
		}
		
	}
	
	
	/**
	 * 同步微信用户和建议反馈
	 */
	public function autoWeixin(){
		
		$this->_addYezhu(); //添加业主
		$this->_addYunwei(); //添加运维
		$this->_addFeed();//添加反馈
		
	}
	
	/**
	 * 添加业主
	 */
	protected function _addYezhu(){
	//上次同步到 运维7411 和 业主2592
		$params['method'] = 'Weixin/getUser';
		$params['type'] = 2;
		$last = getCronCfg('lastWeixinYunweiId');
		$last = empty($last) ? 7879 : $last;
		$params['limit'] = $last;
		$rs = getApi($params);
		$data = $rs['data'];
	
		$now = date('Y-m-d H:i:s');
		$m = D('Customer');
		$i = 0;
		foreach($data as $k=>$v){
			$tmp = array();
			$tmp['Barcode'] = $v['barcode'];
			$tmp['Barname'] = $v['Name'];
			$tmp['name'] = $v['truename'];
			$tmp['phone'] = $v['phone'];
			$tmp['type'] = 2;
			$tmp['addtime'] = $now;
			$rs = $m->add($tmp);
			$i++;
		}
	
		if(!empty($data)){
			setCronCfg('lastWeixinYunweiId', $data[$k]['id']);
		}
		echo 'done:'.$i.'-'.$data[$k]['id'].'-<br/>';
	}
	
	/**
	 * 添加运维
	 */
	protected function _addYunwei(){
		//上次同步到 运维7411 和 业主2592
		$params['method'] = 'Weixin/getUser';
		$params['type'] = 1;
		$last = getCronCfg('lastWeixinYezhuId');
		$last = empty($last) ? 2797 : $last;
		$params['limit'] = $last;
		$rs = getApi($params);
	
		$data = $rs['data'];
		$now = date('Y-m-d H:i:s');
		$m = D('Customer');
		$i = 0;
		foreach($data as $k=>$v){
			$tmp = array();
			$tmp['Barcode'] = $v['barcode'];
			$tmp['Barname'] = $v['Name'];
			$tmp['name'] = $v['truename'];
			$tmp['phone'] = $v['phone'];
			$tmp['type'] = 1;
			$tmp['addtime'] = $now;
			$rs = $m->add($tmp);
			$i++;
		}
	
		if(!empty($data)){
			setCronCfg('lastWeixinYezhuId', $data[$k]['id']);
		}
		
		echo 'done:'.$i.'-'.$data[$k]['id'].'-<br/>';
	}
	
	/**
	 * 添加建议反馈
	 */
	protected function _addFeed(){
		//上次同步到 运维7411 和 业主2592
		$params['method'] = 'Weixin/getFeed';
		$last = getCronCfg('lastWeixinFeedId');
		$last = empty($last) ? 0 : $last;
		$params['limit'] = $last;
		$rs = getApi($params);
		
		if(!empty($rs['data'])){
			$data = $rs['data'];
			$key = $this->_addSheet($data,6,601,1);
			setCronCfg('lastWeixinFeedId',$data[0]['id']);
		}
		echo 'done:'.$i.':-'.$data[0]['id'].'-<br/>';
		
	}
	
	
	
	
	
	
	
	
	
	
}