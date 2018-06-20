<?php
namespace Home\Model;
use Think\Model;
class SiteModel extends CommonModel{
	
	protected $tableName = 'site';

    protected $_validate = array(
        array('host','checkHost','域名格式不正确',3,'function'),
    );

    protected function checkHost($host){
        $pattern = "/^(?=^.{3,255}$)[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+$/";
        if(!preg_match($pattern,$host)){
            return false;
        }
        return true;
    }
}            