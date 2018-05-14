<?php
namespace Home\Model;
use Think\Model;
class SysModel extends Model{
	
	protected $tableName = 'sys_config';

	
	 public function getConfig($module='Admin'){
	        $where="status=1 ";
	        $list=$this->field('name,value,type,extra')->where($where)->select();
	        $config=array();
	
	        foreach ($list as $v) {
	            switch ($v['type']) {
	                case 11:
	                    $config[$v['name']]=str2arr($v['value']);
	                    break;
	                case 9:
	                    $config[$v['name']]=(bool)$v['value'];
	                    break;
	                default:
	                    $config[$v['name']]=$v['value'];
	                    break;
	            }
	        }
	        return $config;
	    }


}