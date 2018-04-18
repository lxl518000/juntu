<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BackendController {
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	* 上传微信图片
	*/
	public function upweixin(){
		$upService = service("Upfile");
		$upService->normal();
	}
	
	public function newinfo()
	{
		$userid = session('adminuser.id');
		$where['to'] = $userid;
		$where['status'] = 1;
		$this->order='status asc,addtime desc';
		$this->model = D('infomation');
		$totalCount = $this->model->where($where)->count();
		$this->assign('count',$totalCount);
		$this->display();
	}
	public function isinfo()
	{
		$userid = session('adminuser.id');
		$where['to'] = $userid;
		$where['status'] = 1;
		$this->order='status asc,addtime desc';
		$this->model = D('infomation');
		$totalCount = $this->model->where($where)->count();
		$this->ajaxReturn($totalCount);
	}
	
	
	
	public function getAreaChildren() {
		$area = p ( 'area' );
		$_RegionCodeList = gM ( "Region" )->where ( array (
				'ParentCode' => $area
		) )->select ();
		empty ( $_RegionCodeList ) && $_RegionCodeList = array ();
		$this->ajaxReturn ( $_RegionCodeList );
	}
	public function infomation(){
			$userid = session('adminuser.id');
			if($_REQUEST['status']){
				$where['status'] = $_REQUEST['status'];
			}
			$where['to'] = $userid;
			$this->order='status asc,addtime desc';
			$this->model = D('infomation');
			$totalCount = $this->model->where($where)->count();
			
			$pagesize = C('PAGE_LISTROWS') ? C('PAGE_LISTROWS') : 20;
			$page = new \Think\Page($totalCount,$pagesize);
			
			$pages = $page->show();
			$list = $this->model->where($where)->limit($page->firstRow.','.$page->listRows)->order($this->order)->select();
			//  print_r(D()->_sql());
			
			// $list = $this->_format($list);
			$this->assign('list',$list);
			
			$this->assign('pages', $pages);
			$this->display();
	}
	public function infoconfirm(){
			$where['id'] = I('id');
			$find = M('infomation')->where($where)->find();
			$my = session('adminuser');
			$placename = D('Place')->where(array('id'=>$find['placeid']))->getField('name');

			$data['fromuser'] = queryFullRegionInfo($my['region'])."[{$my['role_name']}]".$my['realname'];
			$data['from'] = $find['to'];
			$data['to'] = $find['from'];
			$data['sourceid'] = $find['sourceid'];
			$data['taskid'] = $find['taskid'];
			$data['placeid'] = $find['placeid'];
			$data['content'] ='已同意你关于'.$placename.'联合执法请求';
			$data['status'] =2;
			$data['addtime'] = date('Y-m-d H:i:s');
			$map['status'] =2;
			
			$joinstatus['joinstatus'] =2;
			$joinstatus['status'] = 1;
					
			$add = M('infomation')->add($data);
			$save = M('infomation')->where($where)->save($map);
			
			$sourceuser= D('Task')->where(array('id'=>$find['sourceid']))->getfield('userID');
			$taskuser =D('Task')->where(array('id'=>$find['taskid']))->getfield('userID');
			
			$sourceuser.=ltrim($taskuser,',');
			$sourceuser = explode(',',$sourceuser);
			
			$sourceuser = array_unique(array_filter($sourceuser));
			$sourceuser =','.implode(',',$sourceuser).',';
			
			$source = D('Task')->where(array('id'=>$find['sourceid']))->setField(array('userId'=>$sourceuser));
			$source = D('Task')->where(array('id'=>$find['taskid']))->save($joinstatus);
			
			$this->success('操作成功');
			// $res =D('Task')
	}
	public function refuse(){
			$where['id'] = I('id');
			$find = M('infomation')->where($where)->find();
			$fromuser = D('Adminuser')->where(array('id'=>$find['to']))->getfield('realname');
			$my = session('adminuser');
			$placename = D('Place')->where(array('id'=>$find['placeid']))->getField('name');
				
			$data['fromuser'] = queryFullRegionInfo($my['region'])."[{$my['role_name']}]".$my['realname'];
			$data['from'] = $find['to'];
			$data['to'] = $find['from'];
			$data['fromuser'] = $fromuser;
			$data['content'] =$find['content'];
			$data['sourceid'] = $find['sourceid'];
			$data['taskid'] = $find['taskid'];
			$data['content'] ='已同意你关于'.$placename.'联合执法请求';
			$data['addtime'] = date('Y-m-d H:i:s');
			$data['status'] =2;
			$map['status'] =2;
			$joinstatus['joinstatus'] =3;
			$add = M('infomation')->add($data);
			$save = M('infomation')->where($where)->save($map);
			$source = D('Task')->where(array('id'=>$find['taskid']))->save($joinstatus);

			$this->success('操作成功');
		
			// $res =D('Task')
	}
	
	/**
	 * 个人中心
	 */
	public function my(){
		 $my = session('adminuser');
		 
		 if(!$my){
		 	$this->redirect(U('Public/login'));
		 }
		 
		 if(IS_POST){
		 	$m = D('Adminuser');
			$rs = $m->create($_POST);
		 	if(!$rs){
		 		$this->error($m->getError());
		 	}
		 	$rs = $m->where(array('id'=>$my['id']))->save($data);
		 	
		 	if($rs){
		 		$my['realname'] = I('realname');
		 		$my['mobile'] = I('mobile');
		 		$my['email'] = I('email');
		 		$my['nps'] = I('nps');
		 		session('adminuser',$my);
		 		$this->success('修改成功');
		 	}else{
		 		$this->error('修改失败');
		 	}
		 }
		 
		 
		$this->assign('list',$my);
		$this->display();
	}
	
	
	
	/**
	 * 查询我的工单状态
	 * 显示最新5条工单和统计
	 */
	 public function mysheet(){
	 	$my = session('adminuser.id');
	 	$map = array();
	 	$map['passto'] = $my;
	 	$map['sure'] = 0;
	 	$rmodel = D('work_sheet_record');
	 	$unsure = $rmodel->where($map)->count();
	 	
		$map = array();
	 	$table = "tb_work_sheet w";
	 	$order = "w.status asc,w.plevel desc,w.id desc";
		$field = "sum(if(status=1,1,0)) as undos,sum(if(status=3,1,0)) as undate";
		$join = '(select wid,CONCAT(",",GROUP_CONCAT(fromuser,",",passto),",") as users from tb_work_sheet_record group by wid) r on r.wid=w.id';
	 	//$map['_string'] = "w.create_user={$my} or r.fromuser={$my} or r.passto={$my}";
	 	$map['r.users'] = array('like',"%,{$my},%");
	 	$count = D()->table($table)->join($join)->where($map)->field($field)->group()->select();
		
	 	$list['unsure'] = $unsure;
	 	$list['undate'] = $count[0]['undate'];
	 	$list['undos'] = $count[0]['undos'];
	 	
	 	
	 	//最新转交给我的工单
	 	$map = array();
	 	$map['sure'] = array('neq',2);
	 	$map['passto'] = $my;
	 	$group = "wid";
	 	$new = $rmodel->where($map)->limit(5)->group($group)->select();
		// echo D()->_sql();
		// dump($new);
		if($_REQUEST['debug']==1){
			dump($new);
			dump($list);
			echo D()->_sql();	
		}
	 	$this->assign('list',$list);
	 	$this->assign('new',$new);
		$this->display();
	}
	
	
	public function index(){
		$this->_checkLogin();// 检查登录
		$admin_menu = session('adminuser.admin_menu');
		$this->assign('admin_menu',$admin_menu);
		$this->display();
	}
	
	public function main(){
		echo "<pre>";
		//print_r(session('adminuser.admin_menu'));
		echo "</pre>";
		$this->display();
	}
	
	public function upfile(){
		
		$service = service('Upfile');
		$service->normal();
	}
	
	
	/**
	 * 网吧自动完成
	 */
	public function autobar(){
		
		$params['method'] = 'Barinfo/autoBar';
		$params['query'] = I('query');
		$rs = getApi($params);
		$return = array();
		$return['suggestions'] = $rs['data'];
		echo json_encode($return,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 公司自动完成
	 */
	public function autocompany(){
		$params['method'] = 'Company/autoCompany';
		$params['query'] = I('query');
		$rs = getApi($params);
		$return = array();
		$return['suggestions'] = $rs['data'];
		echo json_encode($return,JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 客服自动完成
	 */
	public function automen(){
		$name = I('query');
		$where = array();
		$where = ' 1=1 ';
	
		if(!empty($name)){
			$name = str_replace(' ', '', trim($name));
			$name = strtolower($name);
			$where .= " and (realname like '%{$name}%' or username like '%{$name}%' )";
		}
	
		//$where['Name'] = array('like',"%{$name}%");
		//print_r($where);
		$join = "left join tb_sys_role r on u.role_id=r.id";
		$field = "u.realname,u.id,r.title as rolename";
		$list =D()->table('tb_admin_user u')->join($join)->field($field)->where($where)->limit(20)->select();
		//print_r(D()->_sql());
		$res = '';
		if($list){
			$res = array();
			foreach($list as $v){
				$data['value'] =  $v['realname'];
				$data['rolename'] = $v['rolename'];
				$data['id'] = $v['id'];
	
				$res[] = $data;
			}
		}
		//print_r($res);
		$return = array();
		$return['suggestions'] = $res;
		echo json_encode($return);
	
	
	}

	
	


	public function server(){
		//检测访问
		if(empty($_GET['action'])) return false;
	
		//准备配置
		$config=$this->config;
		$action = $_GET['action'];
		$param=array(
				'water'=>intval($_GET['water']),
		);
	
		//事件处理
		switch ($action) {
			case 'config':
				$result =  json_encode($config);
				break;
				/* 上传图片 */
			case 'uploadimage':
				/* 上传涂鸦 */
			case 'uploadscrawl':
				/* 上传视频 */
			case 'uploadvideo':
				/* 上传文件 */
			case 'uploadfile':
	
				$upload = new \Think\Upload();// 实例化上传类
	
				if($file=$this->uploadOne($param)){
					$file_data=array(
							"state" => "SUCCESS",
							"url" => $file['url'],
							"title" => $file['title'],
							"original" => $file['title'],
							"type" => $file['ext'],
							"size" => $file['size'],
					);
					$result=json_encode($file_data);
				}
				break;
	
				/* 列出图片 */
			case 'listimage':
				$config['imageManagerListPath'] = __ROOT__.'/Uploads/';
				$result = include(ROOTPATH."Public/Vendor/ueditor/php/action_list.php");
				break;
				/* 列出文件 */
			case 'listfile':
				$config['fileManagerListPath'] = __ROOT__.'/Uploads/file/';
				$result = include(ROOTPATH."Public/Vendor/ueditor/php/action_list.php");
				
				break;
	
				/* 抓取远程文件 */
			case 'catchimage':
				$config['fileManagerListPath']=__ROOT__.'/Uploads/catcher/{yyyy}{mm}{dd}/{time}{rand:6}';
				$result = include(ROOTPATH."Public/Vendor/ueditor/php/action_crawler.php");
				break;
	
			default:
				$result = json_encode(array(
				'state'=> '请求地址出错'
						));
						break;
		}
	
		/* 输出结果 */
		if (isset($_GET["callback"])) {
			if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
				echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
			} else {
				echo json_encode(array(
						'state'=> 'callback参数不合法'
				));
			}
		} else {
			echo $result;
		}
	}
	
	protected $config = array
	(
			'imageActionName' => 'uploadimage',
			'imageFieldName' => 'upfile',
			'imageMaxSize' => 2048000,
			'imageAllowFiles' => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
			'imageCompressEnable' => true,
			'imageCompressBorder' => 1600,
			'imageInsertAlign' => 'none',
			'imageUrlPrefix' => '',
			'imagePathFormat' => 'Upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
	
			'scrawlActionName' => 'uploadscrawl',
			'scrawlFieldName' => 'upfile',
			'scrawlPathFormat' => 'Upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
			'scrawlMaxSize' => 2048000,
			'scrawlUrlPrefix' => '',
			'scrawlInsertAlign' => 'none',
	
			'snapscreenActionName' => 'uploadimage',
			'snapscreenPathFormat' => 'Upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
			'snapscreenUrlPrefix' => '',
			'snapscreenInsertAlign' => 'none',
	
			'catcherLocalDomain' => array('127.0.0.1', 'localhost', 'img.baidu.com'),
			'catcherActionName' => 'catchimage',
			'catcherFieldName' => 'source',
			'catcherPathFormat' => 'Upload/catcher/{yyyy}{mm}{dd}/{time}{rand:6}',
			'catcherUrlPrefix' => '',
			'catcherMaxSize' => 2048000,
			'catcherAllowFiles' => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
	
			'videoActionName' => 'uploadvideo',
			'videoFieldName' => 'upfile',
			'videoPathFormat' => 'Upload/video/{yyyy}{mm}{dd}/{time}{rand:6}',
			'videoUrlPrefix' => '',
			'videoMaxSize' => 102400000,
			'videoAllowFiles' => array('.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid'),
	
			'fileActionName' => 'uploadfile',
			'fileFieldName' => 'upfile',
			'filePathFormat' => 'Upload/file/{yyyy}{mm}{dd}/{time}{rand:6}',
			'fileUrlPrefix' => '',
			'fileMaxSize' => 51200000,
			'fileAllowFiles' => array('.png','.jpg','.jpeg','.gif','.bmp','.flv','.swf','.mkv','.avi','.rm','.rmvb','.mpeg','.mpg','.ogg','.ogv','.mov','.wmv','.mp4','.webm','.mp3','.wav','.mid','.rar','.zip','.tar','.gz','.7z','.bz2','.cab','.iso','.doc','.docx','.xls','.xlsx','.ppt','.pptx','.pdf','.txt','.md','.xml'),
	
			'imageManagerActionName' => 'listimage',
			'imageManagerListPath' => 'Uploads/',
			'imageManagerListSize' => 20,
			'imageManagerUrlPrefix' => '',
			'imageManagerInsertAlign' => 'none',
			'imageManagerAllowFiles' => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
	
			'fileManagerActionName' => 'listfile',
			'fileManagerListPath' => 'Upload/file/',
			'fileManagerUrlPrefix' => '',
			'fileManagerListSize' => 20,
			'fileManagerAllowFiles' => array('.png','.jpg','.jpeg','.gif','.bmp','.flv','.swf','.mkv','.avi','.rm','.rmvb','.mpeg','.mpg','.ogg','.ogv','.mov','.wmv','.mp4','.webm','.mp3','.wav','.mid','.rar','.zip','.tar','.gz','.7z','.bz2','.cab','.iso','.doc','.docx','.xls','.xlsx','.ppt','.pptx','.pdf','.txt','.md','.xml'),
	);
	
	public function uploadOne($config=array()){
		$path = './Uploads/';
		if(!is_dir($path)){
			mkdir($path, 0755, true);
		}elseif(!is_writeable($path)){
			$this->error='备份目录不存在或不可写，请检查后重试！';
			return false;
		}
		
		$file_data=reset($_FILES);
	
		//增加自定义条件
		$config['maxSize']=$this->allow_size;
		$config['exts']=$this->allow_ext;
	
		//上传服务器
		$Upload = new \Think\Upload();// 实例化上传类   
		$file = $Upload->uploadOne($file_data);
		if(!$file){
			$this->error = $Upload->getError();
			return false;
		}
	
		//使用第三方上传空间时不做管理直接返回
		if($this->upload_type != 'Local'){
			$data['title']=str_replace('.'.$file['ext'],'',$file['name']);
			if(isset($file['url'])){
				$data['url']=$file['url'];
			}else{
				$domain=C('UPLOAD_TYPE_CONFIG.domain');
				$data['url']=$domain.'/Uploads/'.$file['savepath'].$file['savename'];
			}
			$data['ext']=$file['ext'];
			$data['size']=$file['size'];
			return $data;
		}
	
		//图片处理(缩放|水印)
		if(in_array( $file['ext'], $this->image_ext )){
			$file['type']=1;//图片类型
			if($config['thumb'] || ( C('UPLOAD_WATER') && $config['water']) ){
				$file=$this->doImage($file,$config);
			}
		}
		$data['url']='/Uploads/'.$data['path'].'/'.$data['name'].'.'.$data['ext'];
		//缓存新增附件信息
	
		return $data;
	}
	
	//图片处理(缩放|水印)
	private function doImage($file=array(),$config=array()){
		$file_path='Uploads/'.$file['savepath'].$file['savename'];
	
		$Image = new \Think\Image();
		$Image->open($file_path);
		//指定缩放尺寸
		if($config['thumb']){
			if($config['height'] && $config['width']){
				$file_height=$config['height'];
				$file_width=$config['width'];
			}else{
				$file_height=C('UPLOAD_THUMB_HEIGHT');
				$file_width=C('UPLOAD_THUMB_WIDTH');
			}
			$Image->thumb($file_width,$file_height,\Think\Image::IMAGE_THUMB_FIXED);
			$file['type']=2;//图片缩略图类型
		}
		//添加水印
		if(C('UPLOAD_WATER') && $config['water']){
			$water=C('UPLOAD_WATER_PATH');
			if(is_file($water)){
				$Image->water($water,9);
			}
		}
	
		$Image->save($file_path);
		$file['size']=filesize($file_path);
		return $file;
	}
	
	
 	public function infocount(){
		 $id = session('adminuser.id');
		 $count = M('infomation')->where(array('to'=>$id))->count();
		 $count = '<i class="iconfont icon-youxiangzhaohui" ></i>我的消息('.$count.')';
		 $this->ajaxReturn($count);
	 }
    
    
    
    
    
    
}