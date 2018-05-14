<?php
namespace Home\Model;
use Think\Model;
class EnProductModel extends CommonModel{
	
	protected $tableName = 'en_product';

	protected $_validate = array(
			array('name','require','请填写产品名称'),
		
	);
	

}            