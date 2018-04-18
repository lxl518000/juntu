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


*{padding:0;margin:0;}
a{text-decoration:none;}
ul,li{list-style:none;}
.box{width:600px;}
.box_l,.box_r{width:260px;height:360px;border:1px solid #ccc;overflow: auto;}
.box_l  li,.box_r li{line-height:35px;font-size:14px;padding-left:10px;border-bottom:1px solid #CCCCCC;cursor:pointer;}
.box_l li:last-child,.box_r li:last-child{border:none;}
.box_l li:hover,.box_r li:hover,.box_l li.on,.box_r li.on{background:#3672a0;color:#FFFFFF;}
.box_l{float:left;}
.box_m{width:76px;float:left;text-align:center;}
.box_m a{padding:5px 10px;background:#fff;border:1px solid #ccc;margin:40px auto;display:block;width:50px;}
.box_r{float:right;}


</style>

</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
       
        <div class="row">
          
            <div class="col-sm">
                <div class="ibox float-e-margins">
                   
                    <div class="ibox-content">
                        <form class="form-horizontal m-t" id="signupForm" data-par='close' action="<?php echo U();?>">
                        
							
	<?php echo formbuilder('input',array('title'=>'机房名称','name'=>'name','value'=>$list['name'],'placeholder'=>'请输入机房名称','validate'=>'required','tip'=>''));?>
	<?php echo formbuilder('choose',array(type=>'radio','title'=>'ISP','name'=>'isp','options'=>$isp,'checked'=>[$list['isp']]));?>
	<?php echo formbuilder('choose',array(type=>'radio','title'=>'状态','name'=>'status','options'=>array('1'=>'启用',2=>'停用'),'checked'=>[$list['status']]));?>
	<?php echo formbuilder('input',array('title'=>'备注','name'=>'mark','value'=>$list['mark'],'placeholder'=>'','validate'=>'','tip'=>''));?>


<div class="form-group i-checks">
                          <label class="col-sm-3 control-label">优先级：</label>
                               <div class="col-sm-8">
                               
                                <div class="box">
 
									<div class="box_l">
										<?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(!in_array(($key), is_array($plevel)?$plevel:explode(',',$plevel))): ?><li data-p="<?php echo ($key); ?>" data-n="<?php echo ($vo); ?>"><?php echo ($vo); ?></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
									
									</div>
									<div class="box_m">
										<a href="javascript:" class='c_0' id="top">上移</a>
										<a href="javascript:"  class='c_0'id="left">向左</a>
										<a href="javascript:" class='c_0' id="right">向右</a>
										<a href="javascript:"  class='c_0' id="bottom">下移</a>
										<a href="javascript:"  class='c_0' id="allleft">全部向左</a>
									</div>
									<div class="box_r">
										<?php if(is_array($plevel)): $i = 0; $__LIST__ = $plevel;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li data-p="<?php echo ($vo); ?>" data-n="<?php echo ($province[$vo]); ?>"><?php echo ($province[$vo]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
									</div>
								</div>
								                               
                               </div>
                               
                               
   </div>
 		<input type='hidden' name='plevel' value=""/>
       <input type='hidden' name='pmark' value=''/>
 


							
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
 	
	

	
<script src="/Public/js/leftright.js"></script>
<script>
$(document).ready(function(){
	$(".box").orso({
		boxl:".box_l",//左边大盒子
		boxr:".box_r",//右边大盒子
		boxlrX:"li",//移动小盒子
		multiselect:true,
		boxon:"on",//点击添加属性
		idclass:true,//添加的属性是否为class//true=class; false=id;
		boxlan:"#left",//单个向左移动按钮
		boxran:"#right",//单个向右移动按钮
		boxtan:"#top",//单个向上移动按钮
		boxban:"#bottom",//单个向下移动按钮
		boxalllan:"#allleft",//批量向左移动按钮
		boxallran:"#allright",//批量向右移动按钮
		boxalltan:"#alltop",//移动第一个按钮
		boxallban:"#allbottom"//移动最后一个按钮
	})
});
</script>


</body>

</html>