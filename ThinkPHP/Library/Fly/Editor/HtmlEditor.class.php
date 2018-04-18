<?php
/**
 * HtmlEditor类 封装ewebeditor编辑器
 *
 * @version $Id: HtmlEditor.class.php 6 2016-01-12 01:35:23Z wangjin $
 */

namespace Org\Editor;
require_once('Abstract.class.php');
class HtmlEditor extends Editor_Abstract
{

	 /**
     * 标识是否开启session
     *
     * @var boolean
     */
    protected $_isInitSession = true;
    
    /**
     * ewebeditor类型
     * ewebeditor通用编辑器,kceditor教案制作专用编辑器
     *
     * @var string
     */
    protected $_type = 'htmleditor'; 
    
    /**
     * 风格样式
     *
     * @var string
     */
    protected $_style = 'huaqin'; 

     /**
     * 编辑器路径
     *
     * @var string
     */
    protected $_path = '';
    
    /**
     * Session关键字
     *
     * @var string
     */
    protected $_skey = 'news'; 
    
    /**
     * 允许服务器端文件浏览
     *
     * @var string
     */
    protected $_fileBrowse = '0'; 
    
    /**
     * 限制上传空间最大允许100MB	
     *
     * @var string
     */
    protected $_spaceSize = '10000'; 
    
    /**
     * 上传路径
     *
     * @var string
     */
    protected $_pathUpload = '../../'; 
    
    /**
     * 实际上传的文件夹
     *
     * @var string
     */
    protected $_pathCusDir = ''; 
    
    /**
     * 默认使用绝对根路径
     *
     * @var string
     */
    protected $_pathMode = '1'; 
    
    /**
     * 设置其他配置
     *
     * @var array
     */
    protected $_otherConfig = array();
    
 	/**
     * 初始化方法
     *
     * @param  array  $config
     * @return $this
     */
    public function init(array $config = array())
    {
    	parent::init($config);
    	if($this->_isInitSession) {
    		$_SESSION["eWebEditor_".$this->_skey."_FileBrowse"] = $this->_fileBrowse;      //允许服务器端文件浏览
			$_SESSION["eWebEditor_".$this->_skey."_SpaceSize"]  = $this->_spaceSize;       //限制上传空间最大允许100MB	
    		$_SESSION["eWebEditor_".$this->_skey."_PathMode"]   = $this->_pathMode;        
       	 	$_SESSION["eWebEditor_".$this->_skey."_PathUpload"] = $this->_pathUpload; 
        	$_SESSION["eWebEditor_".$this->_skey."_PathCusDir"] = $this->_pathCusDir; 
    	}
        return $this;
    }
    
 	/**
     * 设定编辑器其他配置
     *
     * @param  array  $otherConfig
     * @return $this
     */
    public function setOtherConfig(array $otherConfig)
    {
        $this->_otherConfig = $otherConfig;
        return $this;
    }
    
	/**
     * 设定是否初始化上传的session
     *
     * @return $this
     */
    public function setIsInitSession($isInitSession)
    {
        $this->_isInitSession = $isInitSession;
        return $this;
    }
    
    /**
     * 设定编辑器类型
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->_type = (string) $type;
        return $this;
    }
    
 	/**
     * 设定Session关键字
     *
     * @return $this
     */
    public function setSkey($skey)
    {
        $this->_skey = (string) $skey;
        return $this;
    }
    
 	/**
     * 允许服务器端文件浏览
     *
     * @return $this
     */
    public function setFileBrowse($fileBrowse)
    {
        $this->_fileBrowse = (string) $fileBrowse;
        return $this;
    }
    
 	/**
     * 设定编辑器类型
     *
     * @return $this
     */
    public function setSpaceSize($spaceSize)
    {
        $this->_spaceSize = (string) $spaceSize;
        return $this;
    }
    
	/**
     * 设置编辑器路径
     *
     * @param  string  $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->_path = (string) $path;
        return $this;
    }
    
	/**
     * 设定上传文件路径
     *
     * @return $this
     */
    public function setPathUpload($pathUpload)
    {
        $this->_pathUpload = (string) $pathUpload;
        return $this;
    }
    
	/**
     * 设定实际上传的文件夹
     *
     * @return $this
     */
    public function setPathCusDir($pathCusDir)
    {
        $this->_pathCusDir = (string) $pathCusDir;
        return $this;
    }
    
    /**
     * 输出 HTML 代码
     *
     * @return string
     */
    public function __toString()
    {
		$this->_path =  '/public/' . $this->_type.'/ewebeditor.htm';
		$this->_path .= '?id='.$this->_name.'&style='.$this->_style.'&skey='.$this->_skey;
        $html = <<<EOF
<textarea id="{$this->_name}" name="{$this->_name}" style="display:none">{$this->_value}</textarea>
<iframe id="eWebEditor_{$this->_name}" src="{$this->_path}" frameborder="0" scrolling="no" width="{$this->_width}" HEIGHT="{$this->_height}"></iframe>
EOF;
		$html = preg_replace("'([\r\n])[\s]+'", "", $html); //除去空白字符
        return $html;
    }
}
