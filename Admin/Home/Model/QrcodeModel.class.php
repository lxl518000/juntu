<?php
namespace Home\Model;
use Think\Model;
class QrcodeModel extends Model{
	protected $tableName = 'qrcode';
	
	
	protected $_validate = array(
			array('title','require','请填写二维码名称'), 
			//array('faren','require','请填写法人名称'), 
			//array('phone','require','请填写法人联系电话'), 
			//array('registerCount','require','请填写注册数量'), 
			//array('address','require','请填写网吧地址'), 
			//array('license','require','请填写网吧许可证号'), 
				
	);
	
	
	public function getCacheAll($type){
		//cache this
		return $this->where(array('pid'=>$type))->select();
	}
	
	
	
	public function getParent(){
		$list = $this->where($where)->getField('id,pid,title',true);
		$list = listLevel($list);
		$list_root=array('id'=>0,'title'=>'顶级类型','level'=>0,'mark'=>'');
		array_unshift($list, $list_root);
		return $list;
	}
	
	

	
}