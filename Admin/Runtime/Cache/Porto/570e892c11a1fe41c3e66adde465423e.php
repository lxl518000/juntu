<?php if (!defined('THINK_PATH')) exit();?>﻿<style>
.b_ml ul li.lion p a{color:#0f44ae}
</style>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title><?php echo ($title); ?>-<?php echo ($config['config']['WEB_TITLE']); ?></title>
<meta name="keywords" content="<?php echo ((isset($keyword) && ($keyword !== ""))?($keyword):$config['config']['WEB_KEYWORD']); ?>" />
<meta name="description" content="<?php echo ((isset($description) && ($description !== ""))?($description):$config['config']['WEB_DESCRIPTION']); ?>" />
<meta name="author" content="450724951@qq.com" />
<link rel="stylesheet" type="text/css" href="/Public/proto/Css/base.css">
<link rel="stylesheet" type="text/css" href="/Public/proto/Css/common.css">
<link rel="stylesheet" type="text/css" href="/Public/Home/css/online2.css">
<script src="/Public/proto/Scripts/jquery-1.7.2.min.js"></script>
<script src="/Public/proto/Scripts/jquery.superslide.2.1.js"></script>
<script src="/Public/proto/Scripts/source.func.js"></script>
<script src="/Public/proto/Scripts/common.js"></script>
<link rel="icon" href="/favicon.ico" />
<script type="text/javascript" src="/Public/Home/js/online2.js"></script>
 <!--[if IE 6]>
<script src="/Public/proto/Scripts/dd_belatedpng.js"></script>
<script>
  DD_belatedPNG.fix('#');
</script>
<![endif]-->
</head>
<body data-curpageid="">
<div class="header">
	<div class="header-main wrapper clearfix">
		<a href="index.html" class="header-logo"  style='width:450px;;height:60px;'><img src="/logo.png"></a>
		<div class="header-right">
			<div class="header-right-sup clearfix">
				<div class="header-search clearfix">
					<input type="text" name="keyword" id="">
					<a href="javascript:void(0);" onclick="dosearch()" class="submit" id=""></a>
				</div>
				<script>
				function dosearch(){
					var k = $('input[name="keyword"]').val();
					window.location="<?php echo U('products',['act'=>'sear']);?>?keyword="+k
				}
				</script>
				
			</div>
			<p class="header-phone"><?php echo ($config['config']['WEB_PHONE_CONTACT']); ?></p>
		</div>
	</div>
</div>
<div class="main-nav">
	<ul class="main-nav-list wrapper">
		<?php if(is_array($config["menu"])): $i = 0; $__LIST__ = $config["menu"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if(($pmenu) == $vo["route"]): ?>class='on'<?php endif; ?> ><a href="<?php echo U($vo['route']);?>"><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		
		
	</ul>
</div>
<script>
$(function(){
	$('.par-li').on('mouseover',function(){
		var id = $(this).data('id');
		$('.par-li').removeClass('on');
		$(this).addClass('on');
		if(id==0){
			$('.son-li').show();
		}else{
			$('.son-li').hide();
			$('.son-li-'+id).show();
		}
	
	})
	
	var pid=$('.lion').data('pid');
	if(!pid){
		pid = $('.inside-nav-list li.on').data('id');
	}
	if(pid){
		$('.par-li').removeClass('on');
		$('.par-li-'+pid).addClass('on');
		$('.son-li').hide();
		$('.son-li-'+pid).show();
	}
	
})
</script>

<div class="incontenter">
	<div class="wrapper">
		<div class="inside-nav">
			<ul class="inside-nav-list clearfix">
					<li class='par-li <?php echo ($_REQUEST['cid']?'':'on'); ?>' data-id='0'><a href="<?php echo U('products',array('cid'=>0));?>">全部分类</a></li>
				<?php if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class='par-li <?php echo ($vo['on']); ?> par-li-<?php echo ($vo['id']); ?>' data-id="<?php echo ($vo['id']); ?>"><a href="<?php echo U('products',array('cid'=>$vo['id']));?>" ><?php echo ($vo['title']); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
			
			</ul>
		</div>
		   <div class="index-jinkou">
            <div class="wrapper">
                <div class="index-jinkou-loop b_ml" style='padding-bottom:0;'>
                   <ul class="son-ul son-ul-<?php echo ($vo['pid']); ?>" style='height:auto;'>
                            <?php if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(is_array($vo['_child'])): $i = 0; $__LIST__ = $vo['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><li class='son-li son-li-<?php echo ($vo1['pid']); ?> <?php echo ($vo1['id']==$_REQUEST['cid']?'lion':''); ?>' data-pid="<?php echo ($vo1['pid']); ?>"><p><a href="<?php echo U('products',array('cid'=>$vo1['id']));?>"><?php echo ($vo1['title']); ?></a></p> </li><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                  </ul>
                </div>
            </div>
        </div>
		
		<div class="pros-all-wrap">
			<div class="article" style='background:#fff'>
				<h2  style='text-align:center;font-size:16px;padding:10px;'><?php echo ($pinfo['name']); ?></h2>
			     <?php echo (htmlspecialchars_decode($pinfo['content'])); ?>
			      <div class="page" style='padding:20px;text-align:center;'>
                                         上一个<a href="<?php echo U('index/pinfo',array('pid'=>$prev['id']));?>" ><?php echo ((isset($prev['name']) && ($prev['name'] !== ""))?($prev['name']):'没有了'); ?></a>
                 &nbsp;&nbsp;
             	 下一个 <a href="<?php echo U('index/pinfo',array('pid'=>$next['id']));?>" ><?php echo ((isset($next['name']) && ($next['name'] !== ""))?($next['name']):'没有了'); ?></a>
             </div>
		</div>
		
		</div>
		
	</div>
</div>


<div id="online_qq_layer" onclick="javascript:changeOnline();">
    <div id="online_qq_tab">
        <div class="online_icon">
            <a title="" id="floatShow" style="display:block" href="javascript:void(0);">&nbsp;</a>
            <a title="" id="floatHide" style="display:none" href="javascript:void(0);">&nbsp;</a>
        </div>
    </div>
    <div id="onlineService" style="display: none;">
        <div class="online_windows overz">
            <div class="online_w_top"></div>
            <div class="online_w_c overz">
                <div class="online_bar expand" id="onlineSort1">
                    <h2>
                        <a target="_blank" >在线QQ</a>
                    </h2>
                    <div class="online_content overz" id="onlineType1">
                        <ul class="overz">
                           
                            <li>
                                <a target="_blank"  href="skype:juntuengine?call" on-click="return skypeCheck();" class="skype_icon">Skype在线客服</a>
                            </li>
                             <li>
                                <a target="_blank"  href="skype:hannibal.zhong?call" on-click="return skypeCheck();" class="skype_icon">Skype在线客服</a>
                            </li>
                            <li>
                                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=450758228&amp;site=qq&amp;menu=yes" class="qq_icon">QQ在线客服</a>
                            </li>
                            <li>
                                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=1833237522&amp;site=qq&amp;menu=yes" class="qq_icon">QQ在线客服</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="online_w_bottom"></div>
        </div>
    </div>
</div>
<!-- 在线客服 end -->

<div class="footer">
        <div class="f1 ">
            <div class="footer-nav">
                <div class="footer-logo">
                    <img src="/Public/proto/Images/logo80.png"></div>
            </div>
            <div class="footer-mess">
                <p>
               	电话 :<?php echo ($config['config']['WEB_PHONE_CONTACT']); ?> &nbsp;&nbsp; 邮箱: <?php echo ($config['config']['WEB_EMAIL']); ?></p>
               	<p>地址：<?php echo ($config['config']['WEB_ADDRESS']); ?></p>
                <p/>
              
                <p><?php echo ($config['config']['WEB_COPYRIGHT']); ?></p>
            </div>
        </div>
    </div>

</body>
</html>
<!-- -第三方统计代码 -->    
<?php echo (htmlspecialchars_decode($config['config']['WEB_THIRD_CODE'])); ?>