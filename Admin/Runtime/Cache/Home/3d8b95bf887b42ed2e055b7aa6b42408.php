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
                        
							

<?php echo formbuilder('input',array('title'=>'Server名称','name'=>'name','value'=>$list['name'],'placeholder'=>'请输入机器名称域名或者ip','validate'=>'required','tip'=>''));?>
<?php echo formbuilder('input',array('title'=>'ServerIP','name'=>'serverip','value'=>$list['serverip'],'placeholder'=>'请输入机器ip或者域名','validate'=>'required','tip'=>''));?>
<?php echo formbuilder('select',array('title'=>'来源机房','name'=>'roomset','options'=>$roomset,'select'=>$list['roomset']));?>

<?php echo formbuilder('input',array('title'=>'最大连接数','name'=>'conn','value'=>$list['conn'],'placeholder'=>'请输入最大连接数','validate'=>'required','tip'=>''));?>
<?php echo formbuilder('input',array('title'=>'服务器密码','name'=>'password','value'=>$list['password'],'placeholder'=>'请输入密码','validate'=>'required','tip'=>''));?>

<?php echo formbuilder('choose',array(type=>'radio','title'=>'用户级别','name'=>'userlevel','options'=>$userlevel,'checked'=>[$list['userlevel']]));?>

<?php echo formbuilder('choose',array(type=>'checkbox','title'=>'连接模式','name'=>'connmode[]','options'=>$mode,'checked'=>$list['connmode']));?>

<div class="form-group">
                <label class="col-sm-3 control-label">可用区域：</label>
                 <div class="col-sm-8">
                		 <input type='checkbox'  id='pall' name='checkall' value='1' <?php echo ($list['region']==0?'checked':''); ?>><label for='pall'>全选</label>
				       	<?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?><label for="J_<?php echo ($vos["RegionCode"]); ?>" style="margin-right:5px;">
				       			<input type="checkbox" <?php if(in_array($vos['RegionCode'],explode(',',$list['region'])) || $list['region']==0): ?>checked<?php endif; ?> name="region[]" id="J_<?php echo ($vos["RegionCode"]); ?>" value="<?php echo ($vos["RegionCode"]); ?>" />
				       			<?php echo ($vos["RegionName"]); ?>
				       			</label><?php endforeach; endif; else: echo "" ;endif; ?>
                 </div>
                </div>
                 
                 
<?php echo formbuilder('choose',array(type=>'radio','title'=>'状态','name'=>'status','options'=>[1=>'启用',2=>'禁用'],'checked'=>[$list['status']]));?>


<?php echo formbuilder('input',array('title'=>'端口号','name'=>'vpnport','value'=>$list['vpnport'],'placeholder'=>'请输入端口号','validate'=>'required','tip'=>''));?>

<?php echo formbuilder('input',array('title'=>'IPSEC','name'=>'ipsec','value'=>$list['ipsec'],'placeholder'=>'','validate'=>'','tip'=>''));?>


	
<?php echo formbuilder('input',array('title'=>'备注','name'=>'mark','value'=>$list['mark'],'placeholder'=>'','validate'=>'','tip'=>''));?>

							
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
 	
	

	
<script>

$(function(){
	
	$('#pall').on('click',function(){
			$('input[name="region[]"]').prop('checked',this.checked);
		}
	)
	
	$('input[name="region[]"]').on('click',function(){
		if(!this.checked){
			$('#pall').attr('checked',false);
		}
	})
})
</script>


</body>

</html>