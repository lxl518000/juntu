<?php
/**
 * HTML 编辑器封装
 *
 * @version $Id: Editor.class.php 6 2016-01-12 01:35:23Z wangjin $
 * 
 */

namespace Org\Editor;
class Editor 
{

	/**
     * 编辑器对象
     *
     * @var object
     */
    protected static $_editor;
    
    /**
     * 编辑器配置
     *
     * @var array
     */
    protected static $_config = array();
    
    /**
     * 初始化方法
     *
     * @param  string  $adapter
     * @param  array   $config
     * @return Editor_Abstract
     * @throws Exception
     */
    public static function factory($adapter, array $config = array())
    {
    	$editorName = ucfirst($adapter);
    	if($editorName == 'HtmlEditor') {
    		$editor = new \Org\Editor\HtmlEditor($config);
    	}    
        if (!$editor instanceof Editor_Abstract) {
            throw_exception('无效的编辑器');
        }
        return $editor;
    }

 	/**
     * 读取编辑器
     *
     * @param  string  $adapter
     * @param  array   $config
     * @return string
     * 
     */
    public static function getEditor($adapter, array $config = array())
    {
    	if(!empty($config)) {
			foreach ($config AS $key => $value) {
				self::$_config[$key] =  $value;
			}
		}
    	if(empty($config['pathCusDir'])) {
    		//读取编辑上传路径
    		$baseDir = 'upload/'.TNT_ID.'/files/'.date('Ymd');
    		self::$_config['pathCusDir'] = $baseDir; 
    	}
    	self::$_editor = self::factory($adapter, self::$_config);
    	return self::$_editor->__toString();
    }
}
