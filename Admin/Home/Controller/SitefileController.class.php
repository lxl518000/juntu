<?php
namespace Home\Controller;
use Think\Controller;


class SitefileController extends BackendController {
	
	public function __construct(){
		parent::__construct();
		$this->model = D('Adminuser');
	}

	public function index(){
        $glob = glob('*.*');
       // dump($glob);
        $list = array();
        foreach($glob as $v){
            if($v=='index.php'){
                continue;
            }

            $list[] = $this->_getFileInfo($v);
        }

        $this->assign('list',$list);
	    $this->display();
    }

    public function upfile(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->saveName = '';
        $upload->savePath  =      './'; // 设置附件上传目录    // 上传文件
        $upload->rootPath = ROOTPATH;
        $upload->replace = true;
        $upload->autoSub = false;
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $this->success('上传成功！');
        }
    }

    public function delete(){
	    $name = I('name');
	    unlink($name);
	    $this->success('删除成功');

    }

    protected function _getFileInfo($fileInfo){
	    $return['filename'] = $fileInfo;
	    $return['filetype'] = filetype($fileInfo);
	    $return['filesize'] = toSize(filesize($fileInfo));
	    $return['atime'] = date("Y-m-d H:i",fileatime($fileInfo));
	    $return['mtime'] = date("Y-m-d H:i",filemtime($fileInfo));
	    $return['filetype'] = filetype($fileInfo);
	    $return['filetype'] = filetype($fileInfo);

	    return $return;
    }
	

	
	
	
	
	
    
    
    
    
}