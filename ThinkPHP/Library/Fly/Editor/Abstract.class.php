<?php

/**
 * HTML 编辑器抽像类
 *
 * @version $Id: Abstract.class.php 6 2016-01-12 01:35:23Z wangjin $
 * 
 */

namespace Org\Editor;

abstract class Editor_Abstract 
{

    /**
     * 编辑器名称 [name&id]
     *
     * @var string
     */
    protected $_name = 'content';

    /**
     * 显示宽度
     *
     * @var string
     */
    protected $_width = '92%';

    /**
     * 显示高度
     *
     * @var string
     */
    protected $_height = '220';

    /**
     * 编辑器值
     *
     * @var string
     */
    protected $_value = '';

    /**
     * 风格样式
     *
     * @var string
     */
    protected $_style = '';

    /**
     * 编辑器根路径
     *
     * @var string
     */
    protected $_basePath = '/';

	/**
     * 构造方法
     *
     * @param $config array
     */
    public function __construct(array $config = array())
    { 
        if (!method_exists($this, 'init')) {
        	throw_exception('必须实现 init 方法');
         
        }
		$this->init($config);
    }
    
    /**
     * 初始化方法
     *
     * @param  array  $config
     * @return $this
     */
    public function init(array $config = array())
    {
    	if(!empty($config)) {
	    	foreach ($config as $key => $value) {
	            $method = 'set' . ucfirst($key);
	            if (method_exists($this, $method)) {
	                $this->$method($value);
	            }
	        }
    	}
        return $this;
    }

    /**
     * 设置编辑器名称
     *
     * @param  string  $name
     * @return $this
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    /**
     * 设置编辑器高度
     *
     * @param  string  $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    /**
     * 设置编辑器高度
     *
     * @param  string  $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    /**
     * 设置编辑器值
     *
     * @param  string  $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->_value = htmlspecialchars($value);
        return $this;
    }

    /**
     * 设置编辑器显示风格/样式
     *
     * @param  string  $style
     * @return $this
     */
    public function setStyle($style)
    {
        $this->_style = (string) $style;
        return $this;
    }

    /**
     * 设置编辑器根路径
     *
     * @param  string  $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->_basePath = (string) $basePath;
        return $this;
    }

    /**
     * 输出 HTML 代码
     *
     * @return string
     */
    abstract public function __toString();

}