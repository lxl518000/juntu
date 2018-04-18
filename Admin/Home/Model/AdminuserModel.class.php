<?php
namespace Home\Model;
use Think\Model;
class AdminuserModel extends Model{
	protected $tableName = 'admin_user';
	
	//自动验证(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
	//验证条件(0:存在字段验证|默认,1:必须验证,2:值不为空验证)
	//验证时间(1:新增验证,2:修改验证,3:全部验证|默认)
	protected $_validate = array(
			//添加
			//array('username', '2,20', '账号长度不符,请保持在2-20个字符之内',1, 'length',3),
			//array('username','','该账号已被使用',1,'unique',1),
			array('password', '6,50', '密码长度不符,请保持在6-50个字符之内',1,'length',1),
			
			//修改
			array('password', '6,50', '密码长度不符,请保持在6-50个字符之内',2,'length',2),
	
			//通用
			array('realname','2,20', '真实姓名长度不符,请保持在2-20个字符之内',1,'length',3),
			array('mobile','/^\d{11}$/','请正确填写手机号码',2,'regex'),
		    array('mobile','','手机号已经存在！',2,'unique',3), // 在新增的时候验证name字段是否唯一
			array('mobile','','该手机号已被使用',1,'unique',1),
			array('email','email','请正确填写邮箱地址',2,'regex'),
	);


	//内容过滤/填充(完成字段1,完成规则,[完成条件,附加规则,函数参数])
	//完成条件(1:新增时候处理,2:修改时候处理,3:全部时候处理|默认)
	protected $_auto = array (
			array('password','getPassword',3,'callback'),
	);
	
	
	//自动完成所需回调方法
	public function getPassword($str=''){
		if(empty($str)){
			 return false;
		}
		return md5($str);
	}
	
	
	/**
	 * 登录管理员
	 * @param unknown $res
	 */
	public function checkLogin($res){
		$saveData['loginip'] = get_client_ip();
		$saveData['logintime'] = date('Y-m-d H:i:s',time());
		$saveData['logins'] = $res['logins'] + 1;
		$data['logins'] = $saveData['logins'];
		$this->where(array('id'=>$res['id']))->save($saveData);
		
		$roleInfo = D('Role')->where(array('id'=>$res['role_id']))->find();
		
		$where = array();
		
		$dpath = 0;
		if($res['depid']){
			$dpath = D('department')->where(array('id'=>$res['depid']))->getField('path');
		}
		
		
		
		$where['status'] = 1;
		if($roleInfo['isadmin'] == 1){
			$res['isadmin'] = 1;
		}else{
			$res['isadmin'] = 0;
			if($roleInfo['rules']){
				$where['id'] = array('in',$roleInfo['rules']);
			}else{
				return false;
			}
		}
		
			
		$list = D('Menu')->field('id,pid,name,title,icon,type')->where($where)->order('sort desc,id desc')->select();
		$allow = array();	
		$menu = array();
		$func = array();
		foreach($list as $k=>$v){
			if(in_array($v['type'], array(2,3))){
				$menu[] = $v;
			}
			
			if(!empty($v['name'])){
				$arr = explode(';', $v['name']);
				foreach($arr as $k=>$v1){
					array_push($allow, trim($v1));
				}
			}
			
		}
		array_unique(array_filter($allow));

	//	print_r($menu);
		
		$admin_menu=self::getTree($menu,0);
		$res['admin_menu'] = $admin_menu;
		$res['allow'] = $allow;
		$res['role_name'] = $roleInfo['title'];
		$res['role_path'] = $roleInfo['path'];
		$res['role_len'] = $roleInfo['len'];
		$res['dep_path'] = $dpath;
		$res['region'] = !$res['region'] ? C('DEFAULT_REGION'):$res['region'];
		$res['regionname'] = queryFullRegionInfo($res['region']);
		session('adminuser',$res);
		return true;
	}
	
	//递归生成无限级后台菜单
	private static function getTree($arr,$pid){
		$result=array();
		 
		foreach ($arr as $v) {
			if($v['pid'] == $pid){
				$r['url']=$v['name'];
				$r['name']=$v['title'];
				if(!empty($v['icon'])) $r['icon']=$v['icon'];
	
				$r['item']=self::getTree($arr,$v['id']);
				if(empty($r['item'])) unset($r['item']);
	
				$result[]=$r;
			}
		}
		return $result;
	}
	

	
	
}