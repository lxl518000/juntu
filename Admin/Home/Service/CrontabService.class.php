<?php
namespace Home\Service;
use Home\Service\Service;
class CrontabService extends Service{
	
	
	
	/**
	 * 同步通话记录
	 * 
	 * 
	 * @see 接口一次最多返回100条数据 这里先请求一次 获取总条数 然后进行分页 再循环拉取数据
	 * @see 开始时间以数据库最近一条时间为主 开始拉取  如果没有 则默认取3个月
	 * 
	 */
	public function syncCall(){
		
		$pageno = 1;
		$pageSize = 100;
		$m = D('call_log');
		//最近一条记录时间
		
		//因为接口返回数据有误差 这里比较最近10次以内的uniqueid 进行最后一次去重比较
		$last = $m->order('id desc')->limit(10)->select();
		$lastone = $last[0]['ctime'];
		$last10Uniqueid = array_column($last, 'uniqueid');
		if(!$lastone){
			$lastone = date('Y-m-d',strtotime('-3 month'));
			$pageSize = 100;
		}else{
			$lastone = date("Y-m-d H:i:s",strtotime($lastone));
		}
		
		$startTime = $lastone;
		$endTime = date('Y-m-d H:i:s');
		
		$s = service('Kcall');
		$rs =  $s->getList($startTime,$endTime,$pageno,100);
		$rs = json_decode($rs,true);
		
		$count = $rs['count'];
		
		$pages = ceil($count/$pageSize);
		
		
		
		for($pageno = 1;$pageno<=$pages;$pageno++){
			$rs =  $s->getList($startTime,$endTime,$pageno,100);
			$rs = json_decode($rs,true);
			$list = $rs['info'];
			if(!$list){
				return false;
			}
			$tmp = array();
			foreach($list as $k=>$v){
				if(in_array($v['uniqueid'], $last10Uniqueid)){
					continue;
				}
				
				$data = array();
				$data['ctime'] = $v['calldate'];
				$data['fromuser'] = $v['caller'];
				$data['touser'] = $v['callee'];
				$data['htime'] = $v['billsec'];
				$data['atime'] = $v['duration'];
				$data['direct'] = $v['direct'] == 'IN' ? 1 : 2;
				switch($v['disposition']){
					case "ANSWERED":
						$data['status'] = 1;
						break;
					case "NO ANSWER":
						$data['status'] = 2;
						break;
					case "FAILED":
						$data['status'] = 3;
						break;
				}
				$data['uniqueid'] = $v['uniqueid'];
				$data['monitor'] = $v['monitor'];
				$tmp[] = $data;
			}
			if($tmp){
				$m->addAll($tmp);
			}
		
		}
		
	}
	
	
	
}