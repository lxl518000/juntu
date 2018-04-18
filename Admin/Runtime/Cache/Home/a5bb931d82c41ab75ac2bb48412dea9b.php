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
                        <form class="form-horizontal m-t" id="signupForm" data-par='close' action="<?php echo U();?>">
                        
							
	<?php echo formbuilder('input',array('title'=>'分类名称','name'=>'title','value'=>$list['title'],'placeholder'=>'请输入分类名称','validate'=>'required','tip'=>''));?>
	<?php echo formbuilder('select',array('title'=>'上级分类','name'=>'pid','options'=>$parent,'select'=>$list['pid']));?>
	 <?php echo formbuilder('input',array('title'=>'关键字','name'=>'keyword','value'=>$list['keyword'],'placeholder'=>'','validate'=>'','tip'=>''));?>
	<?php echo formbuilder('textarea',array('title'=>'分类描述','name'=>'desc','value'=>$list['name'],'placeholder'=>'如果有则请填写路由地址','validate'=>'','tip'=>''));?>
   	<?php echo formbuilder('input',array('title'=>'排序','name'=>'sort','value'=>$list['sort'],'placeholder'=>'请输入排序','validate'=>'required','tip'=>''));?>
	<?php echo formbuilder('choose',array(type=>'radio','title'=>'分类状态','name'=>'status','options'=>array('2'=>'禁用',1=>'启用'),'checked'=>[$list['status']]));?>
	
	
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


  
 	
	

	
 


</body>

</html>