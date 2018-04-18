<?php
namespace Home\Controller;

class SysController extends BackendController {
	
	protected $name = "系统设置";
	
	protected function loadModel(){
		$this->model = D('Sys');
		return $this->model;
	}
	
	public function logo(){
		
		if(IS_POST){
			$upfile = $_REQUEST['pic'];
			
			//	$upfile = "./Upload/file/20160721/111.xlsx";
			if(!$upfile){
				$this->error('请上传文件');
			}
			$file = $upfile[0];
			
			//rename(ROOTPATH.$file,ROOTPATH.'Public/random/img/loginlogo.png');
			rename(ROOTPATH.$file,ROOTPATH.'Public/young/images/loginlogo.png');
			
			$this->success('上传成功');
		}
		
		$this->display();
	}
	
	

	public function addone(){
		$type = I('type');
		$service = service('AutoSheet');
		$rs = getCronCfg('lastAutoNewSheetDate');

		$end = date('Y-m-d',strtotime('+5 day'));
		if($rs>=$end){
			$this->error('已追加超过5天不能继续追加');
		}
		$service->handleNewSheet();
		$this->success('追加成功');
	}
	
	
	protected function _before_add(){
		$this->_initData();
		
		$list['type'] = 1;
		$list['status'] = 1;
		$list['groups'] = 1;
		$this->assign('list',$list);		
	}
	
	protected function _after_edit(){
		$this->_initData();
	}
	
	protected function _format($list){
		
		$group = C('CONFIG_GROUPS');
		$status = [1=>"<span style='color:green'>正常</span>",2=>'<span style="color:red">禁用</span>'];
		foreach($list as $k=>$v){
			$v['groupname'] = $group[$v['groups']];
		
			$op = '';
	 		
	 		$op .=  getToolIcon('edit','J_open btn-xs ',U('edit',['id'=>$v['id']]),'','','','J_open')."&nbsp;";
	 		
	 		$op .= getToolIcon('delete','J_confirm btn-xs ',U('delete',['id'=>$v['id']]),'','','','J_confirm')."&nbsp;";
	 		
	 		if($v['status']==1){
	 			$op .= getToolIcon('off','J_confirm btn-xs ',U('disable',['id'=>$v['id']]),'','','','J_confirm')."&nbsp;";
	 		}else{
	 			$op .= getToolIcon('on','J_confirm btn-xs ',U('enable',['id'=>$v['id']]),'','','','J_confirm')."&nbsp;";
	 		}
	 		
	 		$v['status'] = $status[$v['status']];
	 		
	 		$v['operate'] = $op;
	 		
			
			$list[$k] = $v;
		}
		
		return $list;
	}
	
	protected function _after_index(){
		$this->_initData();
	}
    
	protected function _initData(){
			$type = array(1=>'字符串',2=>'长文本',3=>'数值',10=>'选择',9=>'开关',11=>'数组参数');
		$this->assign('type',$type);
		$groups = C('CONFIG_GROUPS');
		
		$this->assign('groups',$groups);
	}
	
	/**
	 * 更新后更新配置缓存
	 */
    protected function _after_update($list){
		$this->_flushConfig();
    }
    
    /**
     * 添加后更新配置缓存
     */
    protected function _after_insert($list){
    	$this->_flushConfig();
    }
    
    
    
    public function info(){
    	
    	if (function_exists('gd_info')) {
    		$gd = gd_info();
    		$gd = $gd ['GD Version'];
    	} else {
    		$gd = "不支持";
    	}
    	$able = get_loaded_extensions();
    	$extensions_list = "";
    	foreach ($able as $key => $value) {
    		if ($key != 0 && $key % 20 == 0) {
    			$extensions_list = $extensions_list . '<br />';
    		}
    		$extensions_list = $extensions_list . "{$value}&nbsp;&nbsp;";
    	}
    	$server_info = array(
    			'操作系统' => PHP_OS,
    			'主机名IP端口' => $_SERVER ['SERVER_NAME'] . ' (' . $_SERVER ['SERVER_ADDR'] . ':' . $_SERVER ['SERVER_PORT'] . ')',
    			'运行环境' => $_SERVER ["SERVER_SOFTWARE"],
    			'服务器语言' => getenv("HTTP_ACCEPT_LANGUAGE"),
    			'PHP运行方式' => php_sapi_name(),
    			'管理员邮箱' => $_SERVER['SERVER_ADMIN'],
    			'程序目录' => WEB_PATH,
    			'MYSQL版本' => function_exists("mysql_close") ? mysql_get_client_info() : '不支持',
    			'GD库版本' => $gd,
    			'上传附件限制' => ini_get('upload_max_filesize'),
    			'POST方法提交限制' => ini_get('post_max_size'),
    			'脚本占用最大内存' => ini_get('memory_limit'),
    			'执行时间限制' => ini_get('max_execution_time') . "秒",
    			'浮点型数据显示的有效位数' => ini_get('precision'),
    			'内存使用状况' => round((@disk_free_space(".") / (1024 * 1024)), 5) . 'M/',
    			'可用/总磁盘' => round((@disk_free_space(".") / (1024 * 1024 * 1024)), 3) . 'G/' . round(@disk_total_space(".") / (1024 * 1024 * 1024), 3) . 'G',
    			'服务器时间' => date("Y年n月j日 H:i:s 秒"),
    			'北京时间' => gmdate("Y年n月j日 H:i:s 秒", time() + 8 * 3600),
    			'显示错误信息' => ini_get("display_errors") == "1" ? '√' : '×',
    			'register_globals' => get_cfg_var("register_globals") == "1" ? '√' : '×',
    			'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? '√' : '×',
    			'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? '√' : '×',
    			'phpinfo' => '<a href="'.U('Sys/pinfo').'">PHP详细信息</a>',
    	);
    	$this->assign('server_info', $server_info);
    	$this->assign('extensions_list', $extensions_list);
    	$this->display();
    }
    
    public function pinfo(){
    		phpinfo();
    }
    
    
    
   
}