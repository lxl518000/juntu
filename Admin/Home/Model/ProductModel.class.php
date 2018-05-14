<?php
namespace Home\Model;
use Think\Model;
class ProductModel extends CommonModel{
	
	protected $tableName = 'product';

	protected $_validate = array(
			array('name','require','请填写产品名称'),
		
	);
	

}            