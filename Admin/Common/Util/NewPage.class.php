<?php
namespace Common\Util;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: Page.class.php 2712 2012-02-06 10:12:49Z liu21st $
// 
// @see 修改config 在pagepre和pagenxt添加两个&nbsp;
// @author liufei

class NewPage {
    // 分页栏每页显示的页数
    public $rollPage = 5;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 默认列表每页显示行数
    public $listRows = 30;
    // 起始行数
    public $firstRow	;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    public $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制 
    public $config  =	array('header'=>'条记录','prev'=>'<span class="pagepre">上一页</span>','next'=>'<span class="pagenxt">下一页</span>','first'=>'第一页','last'=>'最后一页',
    		'theme'=>'
    <ul class="paginList">
      <li class="paginItem">%upPage%</li>
      <li class="paginItem">%first%</li>
    		%prePage%
      %linkPage%
    		%nextPage%
      <li class="paginItem"> %end%</li>
      <li class="paginItem">%downPage%</li>
    </ul>');
    // 默认分页变量名
    protected $varPage;
// %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%
    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
    public function __construct($totalRows,$listRows='',$parameter='') {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
        if(!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }
    
    public function sshow($url,$page){
    	$_html = '';
    	if(C('URL_MODEL')){
    		$_html = C('URL_HTML_SUFFIX');
    	}
    	$cfg_runtype = C('cfg_runtype');
    	if(stripos($url, '{p}')!==false || stripos($url, urlencode('{p}'))!==false){
    		if($page <= 1 && $cfg_runtype == 1){
    			$url = str_replace(array('_{p}','-{p}'),'',$url);
    			$url = str_replace(array('_'.urlencode('{p}'),'-'.urlencode('{p}')),'',$url);
    			$url = str_replace(array('{p}',urlencode('{p}')),'',$url);
    		}else{
    			$url = str_replace('{p}',$page,$url);
    			$url = str_replace(urlencode('{p}'),$page,$url);
    		}
    	}else {
    		$url = $url.$page;
    	}
    	if(stripos($url, $_html)===false)$url = $url.".".$_html;
    	return $url;
    }

    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p = $this->varPage;
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
//         $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
//         $parse = parse_url($url);
//         if(isset($parse['query'])) {
//             parse_str($parse['query'],$params);
//             unset($params[$p]);
//             $url   =  $parse['path'].'?'.http_build_query($params);
//         }
        $url = $this->parameter;
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a href='".$this->sshow($url,$upRow)."'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a href='".$this->sshow($url,$downRow)."'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<li class='paginItem'><a href='".$this->sshow($url,$preRow)."' >...</a></li>";
            $theFirst = "<li class='paginItem'><a href='".$this->sshow($url,1)."' >1</a></li>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<li class='paginItem'><a href='".$this->sshow($url,$nextRow)."' >...</a></li>";
            $theEnd = "<li class='paginItem'><a href='".$this->sshow($url,$theEndRow)."' >$theEndRow</a></li>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "<li class='paginItem'><a href='".$this->sshow($url,$page)."'>&nbsp;".$page."&nbsp;</a></li>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "<li class='paginItem current'><a>".$page."</a></li>";
                }
            }
        }
        
        $pageStr	 =	 str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }

}