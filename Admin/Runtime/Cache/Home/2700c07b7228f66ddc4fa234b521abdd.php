<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="favicon.ico">
	<link href="/Public/static/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/static/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/static/css/animate.css" rel="stylesheet">
    <link href="/Public/static/css/style.css?v=4.1.0" rel="stylesheet">
	<link href="/Public/static/css/plugins/iCheck/custom.css" rel="stylesheet">
	
    <link rel="stylesheet" type="text/css" href="/Public/static/css/plugins/webuploader/webuploader.css">
    <link rel="stylesheet" type="text/css" href="/Public/static/css/demo/webuploader-demo.css">
    <link rel="stylesheet" href="/Public/static/css/style.css?v=4.1.0" >

	
	  <!-- 全局js -->
    <script src="/Public/static/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/static/js/bootstrap.min.js?v=3.3.6"></script>

    <!-- 自定义js -->
    <script src="/Public/static/js/content.js?v=1.0.0"></script>
	
	
    <!-- jQuery Validation plugin javascript-->
    <script src="/Public/static/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/Public/static/js/plugins/validate/messages_zh.min.js"></script>
 <script src="/Public/static/js/plugins/layer/layer.min.js"></script>
    <script src="/Public/static/js/demo/form-validate-demo.js"></script>
	
   
    <!-- iCheck -->
    <script src="/Public/static/js/plugins/iCheck/icheck.min.js"></script>
    
    
 <style>
		.wrapper-content{padding-top:0;}
		.form-group{margin-bottom:0px;}
		.hr-line-dashed{margin:10px 0;}





</style>

</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeIn">
       
        <div class="row">
          
            <div class="col-sm">
                <div class="ibox float-e-margins">
                   
                    <div class="ibox-content">
                        <form class="form-horizontal m-t" id="signupForm" data-par='close' action="<?php echo U();?>" data-callback='<?php echo ((isset($callback) && ($callback !== ""))?($callback):''); ?>'>
                        
							

    <?php echo formbuilder('select',array('title'=>'所属站点','name'=>'sid','options'=>$host,'select'=>$list['sid']));?>
    <?php echo formbuilder('choose',array(type=>'radio','title'=>'配置类型','name'=>'type','options'=>$types,'checked'=>[$list['type']]));?>
    <?php echo formbuilder('input',array('title'=>'配置名称','name'=>'name','value'=>$list['name'],'placeholder'=>'请输入配置名称','validate'=>'required','tip'=>''));?>
    <?php echo formbuilder('input',array('title'=>'配置KEY','name'=>'key','value'=>$list['key'],'placeholder'=>'请输入配置KEY','validate'=>'required','tip'=>''));?>

    <div class="s1 showtype">
        <?php echo formbuilder('textarea',array('title'=>'配置Value','name'=>'value','value'=>$list['value'],'placeholder'=>'请输入配置Value','validate'=>'','tip'=>'','row'=>5));?>

    </div>

    <div class="s2 showtype"  >
        <div class="form-group i-checks">
            <label class="col-sm-3 control-label">配置Value：</label>
            <div class="col-sm-8" >

				<?php $height=200; ?>
                

<div class="form-group i-checks">
       <script src="/Public/Vendor/ueditor/ueditor.config.js"></script>
<script src="/Public/Vendor/ueditor/ueditor.all.min.js"></script>
<script type="text/plain" id="editor_container" name="<?php echo ((isset($name) && ($name !== ""))?($name):'content'); ?>">
<?php echo (htmlspecialchars_decode($list['content'])); ?>
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
            </div>
         </div>
        <br/>
    </div>

    <div class="s3 showtype">
                   <div class="form-group ">
                           		<label class="col-sm-3 control-label">缩略图：</label>
                           	  <div class="col-sm-8" style='padding-left:5px;'>
                            	 <button class="btn btn-success " type="button" onclick="webupload()"><i class="fa fa-upload"></i>&nbsp;&nbsp;<span class="bold">上传缩略图</span>
                      			</button>
                      			<div class="showstr" style='width:100%'>
                      			
                      			</div>
                             </div>
                             
                             <input type="hidden" name="pic" value="<?php echo ($list["pic"]); ?>">
                          </div>
                          
                            <script>
	                          function webupload(){
	                        	    var url = '<?php echo U('Public/webupload');?>';
	                        	  	var pic = $('input[name="pic"]').val(); 
	                        		var url = url+"?pic="+pic;
	                        	
	                        	  	layer.open({
	                      			  type: 2,
	                      			  area: ['80%','80%'],
	                      			  title : '选择缩略图',
	                      			  fix: false, //不固定
	                      			  maxmin: true,
	                      			  content: url
	                      			});
	                          }
	                          
	                          function setThumb(showstr,thumb){
	                        	  $('input[name="pic"]').val(thumb);
	                        	  $('.showstr').html(showstr);
	                          }
	                          
	                          </script>
	                          
	                          
	                          
        <br/>
    </div>


    <?php echo formbuilder('input',array('title'=>'备注','name'=>'remark','value'=>$list['remark'],'placeholder'=>'','validate'=>'','tip'=>''));?>



    <script>

        $(function () {
            showtype();
            $('input[name="type"]').on('ifChecked', function(event){
                showtype();
            });
        })

        function showtype(){
            $('.showtype').hide();
            var val = $('input[name="type"]:checked').val();
            console.log(val);
            $('.s'+val).show();
        }
        
    </script>
    
    

							
							<?php if(($isupdate) == "1"): ?><input type='hidden' name='<?php echo ($pk); ?>' value="<?php echo ($pkvalue); ?>"/><?php endif; ?>
							
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-3">
                                    <button class="btn btn-primary" type="submit">提交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

 <script>
 
 function subfun(data){
	 	if(data.status!=1){
	 		layer.msg(data.info);
	 		return false;
	 	}
	 	layer.confirm()
	 	var sg = layer.confirm(data.info+'是否保留此页并继续添加', {
		  btn: ['继续添加','不了'] //按钮
		}, function(){
			   var button = $('#signupForm').find('button[type="submit"]');
				unlockButton(button);
				layer.close(sg);
				
		}, function(){
           	  setTimeout(function () {
           		  parent.location.reload();
               }, 1000);
		});
	
 }
 </script>

  
 	
	

	



</body>

</html>