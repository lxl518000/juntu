<?php
namespace Home\Controller;
use Think\Controller;


/**
 * 场所类型控制器
 * @author Administrator
 *
 */
class MyController extends BackendController {
	
	public function __construct(){
		parent::__construct();
		$this->model = D('Adminuser');
	}
	
	
	
	public function edit(){
        $agent = session('adminuser');
        if(IS_POST){
		
			if(!empty($_REQUEST['password'])){
				$data['password'] = md5(I('password'));
			}
			 
			$res = $this->model->where(array('id'=>$agent['id']))->save($data);
			if($res){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		}
        // dump($agent);
		$this->assign('list',$agent);
		$this->display('add');
	}
	
	
	
	
	
	
	
    
    
    
    
}