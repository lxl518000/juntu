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
                        
							
	<?php echo formbuilder('input',array('title'=>'站点名称','name'=>'name','value'=>$list['name'],'placeholder'=>'请输入站点名称','validate'=>'required','tip'=>''));?>
	<?php echo formbuilder('input',array('title'=>'站点域名','name'=>'host','value'=>$list['host'],'placeholder'=>'请输入站点域名','validate'=>'required','tip'=>''));?>
	<?php echo formbuilder('input',array('title'=>'备注','name'=>'remark','value'=>$list['remark'],'placeholder'=>'请输入备注','validate'=>'','tip'=>''));?>



							
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