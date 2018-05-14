<?php
namespace Home\Controller;
use Think\Controller;


/**
 * 场所类型控制器
 * @author Administrator
 *
 */
class QrcodeController extends BackendController {
	
	public function __construct(){
		parent::__construct();
		$this->model = D('Qrcode');
	}
	
	public function updateToken(){
		 S('WX_PLAT_TOKEN','');
		 $this->success('更新成功');
	}
	
	public function qrimg(){
		$id = $_REQUEST['id'];
		$img = getQrImg($id);
		echo "<img src='{$img}'/>";
	}
	
	public function qrurl(){
		$id = $_REQUEST['id'];
		$url= getQrImg($id,'url');
		//02LxLXJXNGcA410000w03_
		//02FvB3JCNGcA410000g03J
		//02yaExITNGcA410000M03A
		$this->assign('url',$url);
		$this->display();
	}
	
	
	public function tongji(){
		$start = I('start',date('Y-m-d',strtotime('-1 month')));
		$end = I('end',date('Y-m-d'));
		$types = D('qrcode')->getField('id,title,pid',true);
		$types[0] = array('title'=>'官方扫描','pid'=>0,'id'=>0);
	
		$this->assign('types',$types);
		$mapPar = array();
	
		$m = D('qrcode_tongji');
		$field = 'qid,sum(num) as num';
		$group = 'qid';
		
		
		$list = $m->field($field)->where($where)->group($group)->select();		
		$typeArr = array();
		$typeArr[0]['value'] = 0;
		$typeArr[0]['name'] = '官方扫描';
		foreach($types as $v){
			$typeArr[$v['id']]['value'] = 0;
			$typeArr[$v['id']]['name'] = $v['title'];
		}
		
		foreach($list as $v){
			$typeArr[$v['qid']]['value'] =(int) $v['num'];
		}
		
	
		$this->assign('list',$list);
		
		$names = array();
		$names = array_column($types, 'title');
		$typeArr = array_values($typeArr);
		$this->assign('datas',json_encode($typeArr));
		$this->assign('names',json_encode($names));
		$this->display();
	}
	
	public function user(){
		
		
		$this->display();
	}
	
	
	protected function _before_add(){
		$this->loadModel();
		$parent = $this->model->getParent();
		$this->assign('parent',$parent);
		
	}
	
	public function index(){
		$list = $this->loadModel()->select();
		$list = $this->_format($list);
		$this->assign('list',$list);
		$this->display();
	}
	
	protected function _format($list){
		return listLevel($list);
	}
    
    protected function _after_edit($list){
    	$_GET['pid'] = $list['pid'];
    	$this->loadModel();
    	$parent = $this->model->getParent();
    	$this->assign('parent',$parent);
    	
    }
    
	
	
	
	
	
	
    
    
    
    
}