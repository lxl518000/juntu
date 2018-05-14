<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 后台控制器基类
 *
 */
class BackendController extends Controller {
 
	 protected $name;
	
	  /** 保存禁止通过url访问的公共方法,例如定义在控制器中的工具方法 ;deny优先级高于allow **/
	  public static  $deny  = array();

	  /** 保存允许访问的公共方法  **/
	  public static  $allow = array();

	  protected static $openController = array('Public','Index');

	  protected function _initialize(){
	  	
	  
	  	$this->_setConfig(); // 设置配置
	  	
	  	// 是否是超级管理员
	  	define('IS_ROOT',   $this->_isAdministrator());
	  	
	  	
	  	if(in_array(CONTROLLER_NAME, self::$openController)){
	  		return true;
	  	}
	  	
	  	$res = $this->_checkLogin();// 检查登录
	  
		//   dump(session('adminuser.region'));
	
		// 检测访问权限
	    $access = $this->_accessControl();
	    

		if ( $access === false ) {
			echo 'auth ';exit;
	    		$this->display('Common/403');
	    		exit;
	    	} 
	    		
	    	$this->assign('titlename',$this->name);
		
	 }

	 public function _empty($name){   
	 	var_dump($name);     
	 	echo '即将上线';
	  }


/**
	 * 加载 Model 
	 */
	protected function loadModel()
	{
		if(empty($this->model) )
		{
			$name  = CONTROLLER_NAME;
			$this->model = D($name);
		}
		return $this->model;
	}
	
	protected function makeTimeBetween($time1,$time2,$flag=false){
		if($flag == true){
			$format = "Y-m-d H:i:s";
		}else{
			$format = "Y-m-d";
		}

		if(!empty($time1)){
			$time1 = date($format,strtotime($time1)+86399);
		}
		if(!empty($time2)){
			$time2= date($format,strtotime($time2)+86399);
		}
		
		$map = array();
		if(!empty($time1) && !empty($time2)){
			if($time1 == $time2){
				$map = array('egt',$time1);
			}else{
				$map= array(array('egt',$time1),array('elt',$time2),'and');
			}
		}
	
		if(!empty($time1) && empty($time2)){
			$map = array('egt',$time1);
		}
			
		if(empty($time1) && !empty($time2)){
			$map= array('elt',$time2);
		}
		$map = empty($map)?array('gt',0):$map;
		
		return $map;
	}
	
	public function index(){
		$this->loadModel();
		//默认Ajax获取列表
		if (IS_AJAX){

			if (method_exists ( $this, '_ajaxData' )) {
				$this->_ajaxData ();
				exit;
			}
			
			$where = $this->_search();
		
			//过滤搜索条件
			if (method_exists ( $this, '_filter' )) {
				$where = $this->_filter ( $where );
			}
				
			$model = $this->model;
				
			$sort = I('sort',$model->getPk());
			
			
			$order = I('order','DESC');
				
			$offset = I('offset',0);
			$limit = I('limit',C('DEFAULT_PATE_SIZE'));
			$total = $model
			->where($where)
			->count();
			
	
			$sort = $sort." ".$order;
			
			$list = $this->model
			->where($where)
			->order($sort)
			->limit($offset, $limit)
			->select();

			//echo $model->_sql();
			
			$list = (array) $this->_format($list);
			
			
			$result = array("total" => $total, "rows" => $list);
	
			
			echo json_encode($result);
			
			return;
		}
		
		//过滤搜索条件
			if (method_exists ( $this, '_after_index' )) {
				 $this->_after_index ( );
			}
				
		$this->display();
	}
	

// 	 public function index(){
	 	
// 	 	$this->loadModel();
// 	 	$this->setJumpUrl();
// 		//  $where=array();
		
// 		$where = $this->_search();
		
// 		//过滤搜索条件
// 		if (method_exists($this,'_filter')) {
			
// 			$where = $this->_filter ( $where );
// 		}
		
// 		$totalCount = $this->model->where($where)->count();
		
// 		$pagesize = C('PAGE_LISTROWS') ? C('PAGE_LISTROWS') : 20;   
// 		$page = new \Think\Page($totalCount,$pagesize);

// 		$pages = $page->show();
// 		$list = $this->model->where($where)->limit($page->firstRow.','.$page->listRows)->order($this->order)->select();
//  	   	$list = $this->_format($list);
//      	$this->assign('list',$list);
	
//         $this->assign('pages', $pages);
//         $this->display();
// 	 }

	 protected function _format($list){
	 	foreach($list as $k=>$vo){
	 		$op = '';
	 		
	 		$op .=  getToolIcon('edit','J_open btn-xs ',U('edit',['id'=>$vo['id']]),'','','','J_open')."&nbsp;";
	 		
	 		$op .= getToolIcon('delete','J_confirm btn-xs ',U('delete',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	 		
	 		if($vo['status']==1){
	 			$op .= getToolIcon('off','J_confirm btn-xs ',U('disable',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	 			$vo['status'] = "<span style='color:green'>启用</span>";
	 		}else{
	 			$op .= getToolIcon('on','J_confirm btn-xs ',U('enable',['id'=>$vo['id']]),'','','','J_confirm')."&nbsp;";
	 			$vo['status'] = "<span style='color:#ccc'>禁用</span>";
	 		}
	 		
	 		
	 		
	 		$vo['operate'] = $op;
	 		$list[$k] = $vo;
	 	}
	 	return $list;
	 }

	 protected function _search($name = '') {
		//加载model
	
	 	$this->assign('_search_block',1);
        $this->loadModel(); 
       
        
        $params = array();
        if($_REQUEST['formfield']){
        	 parse_str($_REQUEST['formfield'],$formField);
        }
        
        
       $dbFields = $this->model->getDbFields();
       
		$map =array();
		foreach ( $dbFields as $key => $val ) {
			if (!empty($formField[$val])) {
				if(in_array($val, array('username','realname','name','mobile','ip','title'))){
					$map [$val] = array('like','%'.$formField[$val].'%');
				}else{
					$map [$val] = $formField[$val];
				}
			}
		}
		return $map;
	}
	



	public function add($tpFile = '') {
		if(IS_POST) {
			$this->loadModel();
            if(method_exists($this->model, 'insert')) {
                $list = $this->model->insert();
            } else {
            //	dump($_POST);exit;
            //  $list=$this->model->add ($data);
			//  print_r($this->model->_sql());exit;
                if (false === $data = $this->model->create ()) {
                	
                   $this->error($this->model->getError());
                }
                if (method_exists ( $this, '_before_insert' )) {
                    $data = $this->_before_insert ( $data );
                }
                $list=$this->model->add ($data);
				
            }

			if ($list!==false) { //保存成功
	
				//自定义方法声明为protected类型
				if (method_exists ( $this, '_after_insert' )) {
					$id = $this->model->getLastInsID ();
					$data['id'] = $id;
					$this->_after_insert ( $data );
				}
				$sql = $this->model->_sql();
				$this->syslog('添加'.$this->name.'成功',$sql);
				$i = $this->getJumpUrl();
				$this->success("添加成功", $this->getJumpUrl());
			} else {
				//失败提示
				$this->error('添加失败!'.$this->model->getError());
			}
		} else {
			if (method_exists ( $this, '_before_add' )) {
				$data = $this->_before_add ();
			}
			
			$this->display ($tpFile);
		}
		
	}
	
	
	
	public function edit() {
		$this->loadModel();
		if(!IS_POST) {
			
			$pk = $this->model->getPk();
			$id = $_REQUEST [$this->model->getPk ()];
				
			$vo = $this->model->where(array(
					$this->model->getPk () => $id
			))->find();
			//编辑后执行的操作,自定义方法声明为protected类型
			if (method_exists ( $this, '_after_edit' )) {
				$this->_after_edit ( $vo );
			}
			$this->assign('pkvalue',$id);
			$this->assign('pk',$pk);
			$this->assign('isupdate',1);
			$this->assign ( 'list', $vo );
			$this->display ('add');
		} else {
			if (false === $data = $this->model->create ()) {
				$this->error($this->model->getError());
			}
			//自定义方法声明为protected类型
			if (method_exists ( $this, '_before_update' )) {
				$data = $this->_before_update ( $data );
			}
			// 更新数据
		
			$list=$this->model->save ($data);

			if (false !== $list) {
	
				if (method_exists ( $this, '_after_update' )) {
					$this->_after_update ( $data );
				}

				//成功提示
				$sql = $this->model->_sql();
				$this->syslog('编辑'.$this->name.'成功',$sql);
				$this->success("编辑成功", $this->getJumpUrl());
			} else {
				//错误提示
				$this->error('编辑失败!'.$this->model->getError());
			}
		}
	}
	



	protected function setJumpUrl($url = null) {
    	if($url == null) {
    		cookie('_currentUrl_', $_SERVER['REQUEST_URI']);
    	} else {
    		cookie('_currentUrl_', $url);
    	}
    }
	    
    protected function getJumpUrl() {
    	if(isset($_POST['forward'])) {
    		$jumpUrl = $_POST['forward'];
    	} elseif(cookie('_currentUrl_')) {
    		$jumpUrl = cookie ( '_currentUrl_' );
    	} else {
    		$jumpUrl = U(CONTROLLER_NAME.'/index');
    	}
    	return $jumpUrl;
    }


	 /**
	  * 检测login
	  * @return 
	  */
	  protected function _checkLogin(){
	  		define('UID',$this->_isLogin());
	    	if( !UID){// 还没登录 跳转到登录页面
	            if(isset($_REQUEST['redirect_uri']) ) {
					$this->redirect_uri = $_REQUEST['redirect_uri'];
				} else {
					$this->redirect_uri = base64_encode(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] :  '/');
				}
				redirect(U('Home/Public/login').'?redirect_uri='.$this->redirect_uri);
	    	}
	  }

	 /**
	  *设置全局配置 
	 */
	  protected function _setConfig(){
			/* 读取数据库中的配置 */
			$config =   S('DB_CONFIG_DATA');
			if(!$config){
				$config =  D('Sys')->getConfig();
				//封装数据库配置并文件加入缓存
				S('DB_CONFIG_DATA',$config);
			}
			//dump($config);
			
		    C($config); //添加配置
		    return true;
	  }
	  
	  protected function _flushConfig(){
	  		S('DB_CONFIG_DATA',null);
	  		$this->_setConfig();
	  }

 
 	/**
	  * 检测是否登录
	  * @return boolean [description]
	  */
	  protected function _isLogin(){
		$userId = session('adminuser.id');
		if (empty($userId)) {
			return 0;
		} else {
			return $userId;
		}
	  }

	 /**
	  * 检测是否超级管理员
	  * @return boolean [description]
	  */
	  protected function _isAdministrator(){
	  	if(session('adminuser.isadmin') == 1){
	  		return true;
	  	}else{
	  		return false;
	  	}
	  }

	  /**
	   * 检测菜单权限
	   */
	 protected function _accessControl(){
	 	if(IS_ROOT){
			return true;//管理员允许访问任何页面
		}
		
//dump(session('adminuser'));		

		$allow = self::getAllow();
		$act = CONTROLLER_NAME.'/'.ACTION_NAME;
		
		if(!in_array($act,$allow)){
			return false;
		}
		
		return true;

	 }
	 
	 static protected function getAllow(){
	 		
	 	$allow = session('adminuser.allow');
	 	return $allow;
	 }
	 
	 
	 /**
	  *
	  * protected 操作日志写入
	  *
	  * */
	 protected function syslog($content,$sql='', $username = '') {
	 	$authuser = session("adminuser");
	 	if ($username == '')
	 		$username = $authuser ['username'] . '(用户ID:' . $authuser ['id'] . ')';
	 	$data = array ('username' => $username, 'content' => $content,'sqlstr'=>$sql, 'ip' => get_client_ip (), 'addtime' => date('Y-m-d H:i:s') );
	 	$M = D ( 'sys_log' );
	 	if ($M->create ( $data )) {
	 		if ($M->add ()) {//数据库操作写入成功,返回TRUE
	 			return true;
	 		}
	 	}
	 	//数据库操作写入失败,将数据写入文本日志
	 	return false;
	 }
	
	 
    

    /**
     * 检测权限
     * @return [type] [description]
     */
	protected function _checkRule(){
		
		return true;
	}
	
	



    /**
     *
     * @return bool
     */
	public function delete() {
		//加载model
        $this->loadModel();  
		//删除指定记录
	
		$pk = $this->model->getPk ();
		$id = $_REQUEST [$pk];
		if($_REQUEST['ids']){
			$id = I('ids');
		}
		if (isset ( $id )) {
			$ids = explode ( ',', $id );
			$condition = array ($pk => array ('in', $ids ) );
			if (false !== $this->model->where ( $condition )->delete ()) {
				if (method_exists ( $this, '_after_delete' )) {
					$data = $this->_after_delete ( $ids );
				}
				$sql = $this->model->_sql();
				$this->syslog('删除'.$this->name.'成功',$sql);
				$this->success('删除成功',$this->getJumpUrl());
			} else {
				$this->error('删除失败!'.$this->model->getDbError());
			}
		} else {
			$this->error('请至少选择一条可操作的数据');
		}
	}

 /**
     * 假删除
     *
     * @return bool
     */
    public function del() {
        //加载model
        $this->loadModel();
        //删除指定记录
        if (empty ( $this->model )) {
            $this->error('请至少选择一条可操作的数据');
            return false;
        }
        $pk = $this->model->getPk ();
        $id = $_REQUEST [$pk];
        if($ids){
        	$id = I('ids');
        }
        if (isset ( $id )) {
            $ids = explode ( ',', $id );
            $condition = array ($pk => array ('in', $ids ) );
            if (false !== $this->model->where ( $condition )->save (array('isDel'=>1))) {
                $this->success('删除成功',$this->getJumpUrl());
            } else {
                $this->error('删除失败!'.$this->model->getDbError());
            }
        } else {
            $this->error('请至少选择一条可操作的数据');
        }
    }
	
    /**
     +----------------------------------------------------------
	 * 禁用操作
     +----------------------------------------------------------
	 */
	public function disable() {
		//加载model
        $this->loadModel();  
		$pk = $this->model->getPk ();
		$id = $_REQUEST [$pk];
		if($_REQUEST['ids']){
			$id = I('ids');
		}
		
		if(empty($id)) {
			$this->error('请至少选择一条可操作的数据!');
		}
		
		$condition = array ($pk => array ('in', explode(',', $id) ));
		$results = $this->model->where($condition)->setField('status',2);
		if ($results!==false) {
			$this->success ('操作成功!',$this->getJumpUrl());
		} else {
			$this->success ('操作失败!');
		}
	} 


	public function delpic() {
		//加载model
        $this->loadModel();  
		$pk = $this->model->getPk ();
		$id = $_REQUEST [$pk];
		if(empty($id)) {
			$this->error('请至少选择一条可操作的数据!');
		}
		
		$condition = array ($pk => array ('in', explode(',', $id) ));
		$results = $this->model->where($condition)->setField('pic','');
		if ($results!==false) {
			$this->success ('操作成功!',$this->getJumpUrl());
		} else {
			$this->success ('操作失败!');
		}
	} 
	
    /**
     +----------------------------------------------------------
	 * 启用操作
     +----------------------------------------------------------
	 */
	public function enable() {
		//加载model
        $this->loadModel();  
		$pk = $this->model->getPk ();
		$id = $_REQUEST [$pk];
		if($_REQUEST['ids']){
			$id = I('ids');
		}
		if(empty($id)) {
			$this->error('请至少选择一条可操作的数据!');
		}
		
		$condition = array ($pk => array ('in', explode(',', $id) ));
		$results = $this->model->where($condition)->setField('status',1);
		if ($results!==false) {
			$this->success ('操作成功!',$this->getJumpUrl());
		} else {
			$this->success ('操作失败!');
		}
	}

	protected function _list($model, $map, $sortBy = '', $asc = false) {
		$totalCount = $model->where($map)->count();
		$pagesize = C('PAGE_LISTROWS') ? C('PAGE_LISTROWS') : 20;
		$page = new \Think\Page($totalCount,$pagesize);
		
		$pages = $page->show();
		$voList = $model->where($map)->limit($page->firstRow.','.$page->listRows)->order($this->order)->select();
	
		$this->assign('pages',$pages);
		
		return $voList;
		

	}
	protected function _insert($M, $map, $log,$successfuntion='') {
	
		if (! empty ( $M )) {
			$pk = $M->getPk ();
			if(isset($map[$pk])){
				$list = $M->field($pk)->where(array($pk=>$map[$pk]))->find();
				if($list){
					$this->error ( '添加失败！编号(主键)重复');
				}
			}
	
			if (! $M->create ( $map )) {
				$this->error ( $M->getError ().( (defined('APP_DEBUG') && APP_DEBUG) ? $M->getdberror():''));
			} else { // 写入帐号数据
				if (($result = $M->add ())!=false) {
					$map[$pk] = $result;
					if($successfuntion !='')$this->$successfuntion($map);
					$this->success ( '添加成功！' );
				} else {
					$this->error ( '错误！'.str_replace('成功','失败',$log));
				}
			}
		}
	}
	
	protected function _save($M, $map, $log,$successfuntion='') {
		if (! empty ( $M )) {
			if (! $M->create ( $map )) {
				$this->error ( $M->getError ().( (defined('APP_DEBUG') && APP_DEBUG) ? $M->getdberror():''));
			} else {
				if (($result = $M->save ())!==false) {
					if($successfuntion !='')$this->$successfuntion($map,$result);
					$this->success ( '修改成功！');
				} else {
					$this->error ( '修改失败！' );
				}
			}
		}
	}
	
}