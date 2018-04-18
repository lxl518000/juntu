<?php
/**
 * 权限认证类
 * 功能特性：
 * 1，是对规则进行认证，不是对节点进行认证。用户可以把节点当作规则名称实现对节点进行认证。
 *      $auth=new Auth();  $auth->check('规则名称','用户id')
 * 2，可以同时对多条规则进行认证，并设置多条规则的关系（or或者and）
 *      $auth=new Auth();  $auth->check('规则1,规则2','用户id','and')
 *      第三个参数为and时表示，用户需要同时具有规则1和规则2的权限。 当第三个参数为or时，表示用户值需要具备其中一个条件即可。默认为or
 * 3，一个用户可以属于多个用户角色(hq_sys_user_role表 定义了用户所属用户角色)。我们需要设置每个用户角色拥有哪些规则(hq_sys_auth_role_rule 定义了用户角色的权限规则)
 *
 * 4，支持规则表达式。
 *      在hq_sys_auth_rule 表中定义一条规则时，如果type为1， condition字段就可以定义规则表达式。 如定义{score}>5  and {score}<100  表示用户的分数在5-100之间时这条规则才会通过。
 *
 * @version $Id: Auth.class.php 6 2016-01-12 01:35:23Z wangjin $
 *
 */

namespace Org\Util;

class Auth {

    //默认配置
    protected $_config = array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 2, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_ROLE' => 'sys_role', //用户角色表名
        'AUTH_ROLE_ACCESS' => 'sys_user_role', //用户关联用户角色表名
    	'AUTH_ROLE_RULE' => 'sys_auth_role_rule', //用户角色规则表名
        'AUTH_RULE' => 'sys_auth_rule', //权限规则表
        'AUTH_USER' => 'user'//用户信息表
    );

    public function __construct() {
    	$prefix = C('DB_PREFIX');
    	$this->_config['AUTH_ROLE'] = $prefix.$this->_config['AUTH_ROLE'];
    	$this->_config['AUTH_RULE'] = $prefix.$this->_config['AUTH_RULE'];
    	$this->_config['AUTH_USER'] = $prefix.$this->_config['AUTH_USER'];
    	$this->_config['AUTH_ROLE_ACCESS'] = $prefix.$this->_config['AUTH_ROLE_ACCESS'];
    	$this->_config['AUTH_ROLE_RULE'] = $prefix.$this->_config['AUTH_ROLE_RULE'];
    	if (C('AUTH_CONFIG')) {
    		//可设置配置项 AUTH_CONFIG, 此配置项为数组。
    		$this->_config = array_merge($this->_config, C('AUTH_CONFIG'));
    	}
    }

    /**
      * 检查权限
      * @param name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
      * @param uid  int           认证用户的id
      * @param integer $type      规则类型
      * @param string mode        执行check的模式
      * @param relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
      * @return boolean           通过验证返回true;失败返回false
     */
    public function check($name, $uid, $type=1, $mode='url', $relation='or') {
        if (!$this->_config['AUTH_ON'])
            return true;
        $authList = $this->getAuthList($uid,$type); //获取用户需要验证的所有有效规则列表
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }
        $list = array(); //保存验证通过的规则名
        if ($mode=='url') {
            $REQUEST = unserialize( strtolower(serialize($_REQUEST)) );
        }
        foreach ( $authList as $auth ) {
            $query = preg_replace('/^.+\?/U','',$auth);
            if ($mode=='url' && $query!=$auth ) {
                parse_str($query,$param); //解析规则中的param
                $intersect = array_intersect_assoc($REQUEST,$param);
                $auth = preg_replace('/\?.*$/U','',$auth);
                if ( in_array($auth,$name) && $intersect==$param ) {  //如果节点相符且url参数满足
                    $list[] = $auth ;
                }
            }else if (in_array($auth , $name)){
                $list[] = $auth ;
            }
        }
        if ($relation == 'or' and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation == 'and' and empty($diff)) {
            return true;
        }
        return false;
    }

    /**
     * 根据用户id获取用户角色,返回值为数组
     * @param  uid int     用户id
     * @return array       用户所属的用户角色ID array(
     *                                         array('用户角色id1','用户角色id2'),
     *                                         ...)   
     */
    public function getRoles($uid) {
        static $roles = array();
        if (isset($roles[$uid]))
            return $roles[$uid];
        $user_roles = M()
            ->table($this->_config['AUTH_ROLE_ACCESS'] . ' a')
            ->where("a.userId='$uid'")->select();
        if(count($user_roles) > 0 && is_array($user_roles)) {
        	foreach ($user_roles AS $key => $value) {
        		$roleIdArr[] = $value['roleId'];
        	}
        	unset($value);
        }
        $roles[$uid]=$roleIdArr?$roleIdArr:array();
        return $roles[$uid];
    }
    
    /**
     * 根据用户角色ID查询角色关联的权限规则ID数组
     *
     * @param array   $roleId 用户角色数组ID
     *
     */
    public function getRoleRuleIdsByRoleId($roleId) {
    	if(!$roleId) {
    		return false;
    	}
    	if(!is_array($roleId)) {
    		$roleId = array($roleId);
    	} 	
    	$data = M()
            ->table($this->_config['AUTH_ROLE_RULE'] . ' a')
    		->where(array('roleId' => array('in', $roleId)))->select();
   
    	$idArr = array();
    	if(count($data) > 0 && is_array($data)) {
    		foreach ($data AS $key => $value) {
    			$idArr[] = $value['ruleId'];
    		}
    		unset($value);
    		unset($data);
    		$idArr = array_unique($idArr);
    	}
    	return $idArr;
    }

    /**
     * 获得权限列表
     * @param integer $uid  用户id
     * @param integer $type 规则类型
     */
    protected function getAuthList($uid,$type) {
        static $_authList = array(); //保存用户验证通过的权限列表
        $t = implode(',',(array)$type);
        if (isset($_authList[$uid.$t.TNT_ID])) {
            return $_authList[$uid.$t.TNT_ID];
        }
        if( $this->_config['AUTH_TYPE']==2 && isset($_SESSION['_AUTH_LIST_'.$uid.$t.TNT_ID])){
            return $_SESSION['_AUTH_LIST_'.$uid.$t.TNT_ID];
        }
        //读取用户所属用户组
        $roles = $this->getRoles($uid);
        $ids = array();//保存用户所属用户组设置的所有权限规则id
        $ids = $this->getRoleRuleIdsByRoleId($roles);
        if (empty($ids)) {
            $_authList[$uid.$t.TNT_ID] = array();
            return array();
        }

        $map=array(
            'id'=>array('in',$ids),
            'type'=>$type,
            'status'=>1,
        );
        //读取用户组所有权限规则
        $rules = M()->table($this->_config['AUTH_RULE'])->where($map)->field('condition,name')->select();

        //循环规则，判断结果。
        $authList = array();   //
        foreach ($rules as $rule) {
            if (!empty($rule['condition'])) { //根据condition进行验证
                $user = $this->getUserInfo($uid);//获取用户信息,一维数组

                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule['condition']);
                //dump($command);//debug
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $authList[] = strtolower($rule['name']);
                }
            } else {
                //只要存在就记录
                $authList[] = strtolower($rule['name']);
            }
        }
        $_authList[$uid.$t.TNT_ID] = $authList;
        if($this->_config['AUTH_TYPE']==2){
            //规则列表结果保存到session
            $_SESSION['_AUTH_LIST_'.$uid.$t.TNT_ID]=$authList;
        }
        return array_unique($authList);
    }

    /**
     * 获得用户资料
     */
    protected function getUserInfo($uid) {
        static $userinfo=array();
        if(!isset($userinfo[$uid])){
             $userinfo[$uid]=M()->where(array('id'=>$uid))->table($this->_config['AUTH_USER'])->find();
        }
        return $userinfo[$uid];
    }

}