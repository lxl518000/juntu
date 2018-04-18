<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>添加</title>
    <meta name="keywords" content="添加">
    <meta name="description" content="添加">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/Public/static/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
	<link href="/Public/static/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/static/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/static/css/animate.css" rel="stylesheet">
    <link href="/Public/static/css/style.css?v=4.1.0" rel="stylesheet">
	<link href="/Public/static/css/plugins/iCheck/custom.css" rel="stylesheet">
	
	<style>
	.wrapper-content{padding:0}
	</style>
	
</head>

<script src="/Public/static/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/static/js/bootstrap.min.js?v=3.3.6"></script>
        <!-- iCheck -->
    <script src="/Public/static/js/plugins/iCheck/icheck.min.js"></script>

    <!-- 自定义js -->
    <script src="/Public/static/js/content.js?v=1.0.0"></script>
	
	
    <!-- jQuery Validation plugin javascript-->
    <script src="/Public/static/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/Public/static/js/plugins/validate/messages_zh.min.js"></script>
 <script src="/Public/static/js/plugins/layer/layer.min.js"></script>
    <script src="/Public/static/js/demo/form-validate-demo.js"></script>
	
   
 
   

 <form class="form-horizontal " id="signupForm" data-par='close' action="<?php echo U();?>">
                        
<body class="gray-bg">
    <div class="wrapper wrapper-content">
        <div class="row animated fadeInRight">
            <div class="col-sm-4" style='padding-right:5px;'>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>基本信息</h5>
                    </div>
                         <div class="ibox float-e-margins">
                    <div class="ibox-content form-horizontal">
                    	<block name='left-field'>
                        	<?php echo formbuilder('input',array('title'=>'菜单名','name'=>'name','value'=>'','placeholder'=>'请输入菜单名','validate'=>'required','tip'=>''));?>
                            <?php echo formbuilder('input',array('title'=>'菜单名','name'=>'name','value'=>'','placeholder'=>'请输入菜单名','validate'=>'required','tip'=>''));?>
                           <?php echo formbuilder('input',array('title'=>'菜单名','name'=>'name','value'=>'','placeholder'=>'请输入菜单名','validate'=>'required','tip'=>''));?>
                           <?php echo formbuilder('input',array('title'=>'菜单名','name'=>'name','value'=>'','placeholder'=>'请输入菜单名','validate'=>'required','tip'=>''));?>
                              
							<?php echo formbuilder('choose',array(type=>'radio','title'=>'菜单类型','name'=>'type','options'=>array('1'=>'菜单',2=>'节点',3=>'式'),'checked'=>$list['type']));?>
							
							 <?php echo formbuilder('select',array('title'=>'上级分类','name'=>'pid','options'=>$parent,'select'=>2));?>
	
							<?php echo formbuilder('textarea',array('title'=>'关键词','name'=>'keyword','validate'=>'required','title'=>'关键词'));?>
                  
                          
                                     <div class="form-group ">
                           		<label class="col-sm-3 control-label">缩略图：</label>
                           	  <div class="col-sm-8" style='padding-left:5px;'>
                            	 <button class="btn btn-success " type="button" onclick="webupload()"><i class="fa fa-upload"></i>&nbsp;&nbsp;<span class="bold">上传缩略图</span>
                      			</button>
                      			<div class="showstr">
                      			
                      			</div>
                             </div>
                             
                             <input type='hidden' name="pic" value="">
                          </div>
                          
                            <script>
	                          function webupload(){
	                        		layer.open({
	                      			  type: 2,
	                      			  area: ['80%','80%'],
	                      			  title : '选择缩略图',
	                      			  fix: false, //不固定
	                      			  maxmin: true,
	                      			  content: "<?php echo U('Public/webupload',array('pic'=>$list['pic']));?>"
	                      			});
	                          }
	                          
	                          function setThumb(show,thumb){
	                        	  $('input[name="hidden"]').val(thumb);
	                        	  $('showstr').html(show);
	                          }
	                          
	                          </script>
	                          
	                          
	                          
             
	                          
	                          
                          <block>
                        
                          
                          
                    </div>
                </div>
                   
                     
                </div>
            </div>
            <div class="col-sm-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>详细信息</h5>
                        
                    </div>
                       <div class="ibox float-e-margins">
                    <div class="ibox-content">
                       
                        	
                        	
                  
								

<div class="form-group i-checks">
       <script src="/Public/Vendor/ueditor/ueditor.config.js"></script>
<script src="/Public/Vendor/ueditor/ueditor.all.min.js"></script>
<script type="text/plain" id="editor_container" name="<?php echo ((isset($name) && ($name !== ""))?($name):'content'); ?>">
<?php echo (htmlspecialchars_decode($content)); ?>
 </script>
						
<script type="text/javascript">

var editor_a = UE.getEditor('editor_container',{
				serverUrl: '<?php echo U("Index/server");?>',
				initialFrameWidth :'100%', //初始化编辑器宽度，默认1000
				initialFrameHeight :<?php echo ((isset($height) && ($height !== ""))?($height):400); ?>, //初始化编辑器高度，默认320
				wordCount : false,
				elementPathEnabled :false,
				plugins: "charmap",
				 allow_resize: "both",
				 allow_toggle: false,
				 charmap_default: "arrows" 
				}
		);

</script>
        </div>
							
							
							<?php if(($isupdate) == "1"): ?><input type='hidden' name='<?php echo ($pk); ?>' value="<?php echo ($pkvalue); ?>"/><?php endif; ?>
							
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-3">
                                    <button class="btn btn-primary" type="submit">提交</button>
                                </div>
                            </div>
                     
                    </div>
                </div>
           
                </div>

            </div>
        </div>
    </div>
    </form>



</body>

</html>