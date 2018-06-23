<?php if (!defined('THINK_PATH')) exit();?>
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

    <div class="banner">
        <div class="banner-pics">
            <ul class="banner-pics-list">
             <?php if(is_array($ppt)): $i = 0; $__LIST__ = $ppt;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="background-image: url(<?php echo ($vo); ?>)"></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="banner-apply">
          <?php echo (htmlspecialchars_decode($config['config']['WEB_PPT_MARK'])); ?>
        
            <ul class="banner-apply-list clearfix">
                <li><a href="#">
                    <img src="/Public/proto/Images/pic_banner_pro_1.png"></a></li>
                <li><a href="#">
                    <img src="/Public/proto/Images/pic_banner_pro_2.png"></a></li>
                <li><a href="#">
                    <img src="/Public/proto/Images/pic_banner_pro_3.png"></a></li>
            </ul>
        </div>
        <div class="banner-turn">
            <a href="javascript:void(0);" class="banner-prev"></a><a href="javascript:void(0);"
                class="banner-next"></a>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(".banner").slide({ mainCell: ".banner-pics-list", prevCell: ".banner-prev", nextCell: ".banner-next", effect: "fold", autoPlay: true, interTime: 4000, delayTime: 1000 })
</script>
    <div class="contenter">
        <div class="index-product">
            <div class="wrapper clearfix">
                <div class="index-product-sidebar">
                    <div class="product-nav">
                        <h2 class="product-nav-tit">
                            产品中心</h2>
                        <ul class="product-nav-list">
                           <?php if(is_array($topcate)): $i = 0; $__LIST__ = $topcate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('products',array('cid'=>$vo['id']));?>"><?php echo ($vo['title']); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                          
                        </ul>
                    </div>
                </div>
                <script type="text/javascript">
                    $(function () {
                        jQuery(".index-product-main").slide({ mainCell: ".index-product-main-cont", prevCell: ".turn-prev", nextCell: ".turn-next", effect: "left", autoPlay: true, interTime: 3000, delayTime: 1000 })
                    });
                </script>
                <div class="index-product-main">
                    <div class="index-product-main-tit">
                        <a href="javascript:void(0);" class="turn-btn turn-prev"></a>
                        <h3 class="title">
                            产品展示</h3>
                        <a href="javascript:void(0);" class="turn-btn turn-next"></a>
                    </div>
                    <div class="index-product-main-cont">
                    	
                    	<?php if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="index-pros-list-one">
	                            <ul class="pros-show-list row-list-4 clearfix">
	                            	<?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('pinfo',['pid'=>$vo1['id']]);?>" class="pros-show-one">
	                                    <div class="pic"><img src="<?php echo ($vo1['pic']); ?>"  onload="imgCenter(this)"></div>
	                                    <h4 class="title"><?php echo ($vo1['name']); ?>
	                                    </h4>
	                                    <div class="tip">查看详情&gt;&gt;</div>
	                             		</a>
	                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
	                            </ul>
	                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        
                 
                    </div>
                </div>
            </div>
        </div>
        <div class="index-column">
            <div class="wrapper clearfix">
                <div class="index-about">
                    <div class="index-common-tit">
                        <h3 class="title">
                            公司简介</h3>
                    </div>
                    <div class="index-about-cont clearfix ">
                        <div class="pic">
                            <img src="<?php echo ((isset($config['config']['WEB_ABOUT_IMG']) && ($config['config']['WEB_ABOUT_IMG'] !== ""))?($config['config']['WEB_ABOUT_IMG']):'/Public/proto/Images/pic_about_index.jpg'); ?>"></div>
                        <div class="text">
                            <p>
                            <?php echo (htmlspecialchars_decode($config['config']['WEB_ABOUT_US'])); ?>
                            </p>
                          
                        </div>
                       
                    </div>
                </div>
               <!--  <div class="index-prodata">
                    <div class="index-common-tit">
                        <h3 class="title">
                            产品知识</h3>
                        <a href="<?php echo U('news',['cid'=>43]);?>" class="see-more">+More</a>
                    </div>
                    <div class="index-prodata-first clearfix">
                        <span class="date"><em><?php echo (date('d',$news1['n_time'])); ?></em><br>
                            <?php echo (date('Y-m',$news1['n_time'])); ?></span> <a href="<?php echo U('ninfo',['nid'=>$news1['id']]);?>" class="text">
                                <h4 class="title">
                                  <?php echo ($news1['n_title']); ?></h4>
                                <p class="exp">
                                   <?php echo (msubstr(killHtml($news1['n_content']),0,30)); ?></p>
                            </a>
                    </div>
                    <ul class="prodata-list ellipsis-list">
                    	<?php if(is_array($news)): $i = 0; $__LIST__ = $news;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('ninfo',['nid'=>$vo['id']]);?>"><?php echo ($vo['n_title']); ?></a><span class="time"><?php echo (date('m-d',$vo['n_time'])); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        
                    </ul>
                </div> -->
            </div>
        </div>
        <div class="index-jinkou">
            <div class="wrapper">
                <div class="index-common-tit">
                    <h3 class="title">热门分类</h3>
                </div>
                <div class="index-jinkou-loop b_ml">
                  
                        <ul class="">
                            
                            <?php if(is_array($allcate)): $i = 0; $__LIST__ = $allcate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><p><a href="<?php echo U('products',['cid'=>$vo['id']]);?>"><?php echo ($vo['title']); ?></a></p> </li><?php endforeach; endif; else: echo "" ;endif; ?>
                           
                        </ul>
              
                  
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