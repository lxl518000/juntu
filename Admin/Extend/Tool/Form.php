<?php 


class Form{
	
	public static function __callStatic($name, $arguments=[])
	{
		return call_user_func_array([FormBuilder::instance(), $name], $arguments);
	}
	
	
}


class FormBuilder{
	
	protected static $instance;
	
	

	public static function instance($options = [])
	{
		 if (!self::$instance instanceof self) {
            self::$instance = new self($options);
        }
		return self::$instance;
	}
	
	
	public static  $theme = array('start'=>'<div class="form-group i-checks" >
                          <label class="col-sm-3 control-label">%s：</label>
                               <div class="col-sm-8">',
						'end'=>'</div></div>'
	);
	

	/**
	 *
	 *单选/多选 按钮构建器
	 *@param array $config
	 * type=>'radio 或者checkbox' 默认为radio
	 * title=>'表单标题'
	 * name=> '表单名称'
	 * options=>array(1=>'xx',2=>'xx'); 单选值
	 * checked=>array() 默认选中的值
	 * disabled=>array() 不可选中的值
	 * hr=>true 默认为显示水平线 false为不显示
	 * 模板示例	{:formbuilder('radio',array(type=>'radio','title'=>'菜单类型','name'=>'type','options'=>array('1'=>'菜单',2=>'节点'),'checked'=>$list['type'],'class'=>''))}
	 *						
	 */
	public static function choose($config){
		$type = $config['type'] ? $config['type'] : 'radio';
		$opt = $config['options'];	
		$name = $config['name'];
		$str = '';
		foreach($opt as $k=>$v){
			$id = $config['name'].'_'.$k;
			$checked = in_array($k,$config['checked']);
			$checked = $checked ? 'checked' : '';
			 $disabled = $config['disabled'] && in_array($k,$config['disabled']);
			$disabled = $disabled ? 'disabled':'';			
			$str .= '<label class="checkbox-inline"><input type="'.$type.'" name="'.$name.'" ' .$checked.' '.$disabled.'  value="'.$k.'" id="'.$id.'">'.$v.'</label>';
		}
		
		return  self::output($str, $config);
	}
	
	public static function output($str,$config){
		$theme = self::$theme;
		$out = '';
		$out .= sprintf($theme['start'],$config['title']);
		$out .= $str;
		$out .= $theme['end'];
		if($config['hr']!==false){
			$out .= '<div class="hr-line-dashed"></div>';
		}
		return $out;
	}
	
	/**
	 * input输入框构建器
	 * @param array $config
	 * $title = 表头
	 * $name = 表单名称
	 * $value 默认值
	 * $placeholder placeholder
	 * $validate 表单验证规则
	 * $tip 额外显示附加提示
	 * 使用示例 {:formbuilder('input',array('title'=>'菜单名','name'=>'name','value'=>'','placeholder'=>'请输入菜单名','validate'=>'required','tip'=>''))}
     *              
	 */
	public static function input($config){
		$name = $config['name'] ? $config['name'] :'input_name';
		$placeholder = $config['placeholder'] ? $config['placeholder'] : '请按说明填写';
		$class = $config['class'] ? $config['class'] : 'form-control';
	
		$type = $config['type'] ? $config['type'] : 'text';
		$str = "<input id='{$name}' name='{$name}' value='{$config['value']}' {$config['validate']} class='{$class}' type='{$type}' title='{$placeholder}' placeholder='{$placeholder}'/>";
		if($config['tip']){
			$str .= " <span class='help-block m-b-none'><i class='fa fa-info-circle'></i>{$config['tip']}</span>";
		}
		
		return  self::output($str, $config);
	}
	
	/**
	 * 文本域构建器
	 * @param array $config
	 * title=>表头
	 * name=>表单名
	 * options=>可选项 
	 * 使用示例
	 *  {:formbuilder('select',array('title'=>'上级分类','name'=>'pid','options'=>array(0=>'顶级分类',1=>'aa',2=>'bb',3=>'cc'),'select'=>2))}
	 *
	 */
	public static function select($config){

		$str = "<select class='form-control' name='{$config['name']}'>";
		foreach($config['options'] as $k=>$v){
			if(is_array($v)){
				$selected = $config['select'] == $v['id'] ? 'selected' : '';
				$str .= "<option {$selected} value='{$v['id']}'>{$v['mark']}{$v['title']}</option>";
			}else{
				$selected = $config['select'] == $k ? 'selected' : '';
				$str .= "<option {$selected} value='{$k}'>{$v}</option>";
			}
			
		}
        $str .= ' </select>';
		return self::output($str,$config);
	}
	
	
	/**
	 * 文本域构建器
	 * @param array $config
	 * title=>表头
	 * name=>表单名
	 * validate=>表单验证
	 * title=>验证失败提示
	 * 使用示例
	 * {:formbuilder('textarea',array('title'=>'关键词','name'=>'keyword','validate'=>'required','title'=>'请输入关键词'))}
	 */
	public static function textarea($config){
		$str = " <textarea id='{$config['name']}' name='{$config['name']}' {$config['validate']} title='{$config['title']}' class='form-control'>{$config['value']}</textarea>";
		return self::output($str, $config);
	}
	
}	
	
	
