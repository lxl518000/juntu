<?php if (!defined('THINK_PATH')) exit();?>﻿
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<title>关于我们-<?php echo ($config['web_title']); ?></title>

<meta name="description" content="<?php echo ($config['config']['WEB_DESCRIPTION']); ?>" />
<meta name="keywords" content="<?php echo ($config['config']['WEB_KEYWORD']); ?>" />
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
			<p class="header-phone"><?php echo ($config['web_phone']); ?></p>
		</div>
	</div>
</div>
<div class="main-nav">
	<ul class="main-nav-list wrapper">
		<?php if(is_array($config["menu"])): $i = 0; $__LIST__ = $config["menu"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if(($pmenu) == $vo): ?>class='on'<?php endif; ?> ><a href="<?php echo U($vo);?>"><?php echo ($key); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		
		
	</ul>
</div>
<div class="inbanner">
	<div class="inbanner-pic" style="background-image:url(/Public/proto/Images/pic_banner_about.jpg);"></div>
	<div class="inbanner-text">
		<h3 class="title">关于我们</h3>
		<p class="exp">十堰星鸿机械有限公司是一家有康明斯授权的代理销售公司。</p>
	</div>
</div>

<div class="incontenter">
	<div class="wrapper">
		
<!-- <div class="inside-nav">
	<ul class="inside-nav-list clearfix">
 		<?php if(is_array($ncate)): $i = 0; $__LIST__ = $ncate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li  <?php if(($vo['id']) == $cid): ?>class="on"<?php endif; ?> ><a  href="<?php echo U('news',array('cid'=>$vo['id']));?>"><?php echo ($vo['c_name']); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
</div> -->
		<div class="about-cont-one clearfix">
			<div class="pic"><img src="/Public/proto/Images/about1.jpg"></div>
			<div class="text">
				<p class="exp"><b>香港骏图工程机械有限公司（国内:十堰星鸿机械有限公司）</b>,康明斯授权的代理销售公司，位于堪称“东方底特律”之称的-中国湖北省十堰市</p>
			</div>
		</div>
		<div class="about-cont-one about-cont-one-oop clearfix">
		<div class="pic"><img src="/Public/proto/Images/about2.jpg"></div>
			<div class="text">
				<p class="exp"><b>优势的地理位置</b>--我公司距离武当山机场约5公里，便于您从中国的主要城市来参观我公司。从西安的咸阳机场到我公司约45分钟，从武汉天河机场到我公司仅需1个小时，从广州的白云机场到我公司只需2个小时时间。在此，我公司诚挚的欢迎各国朋友到我公司参观访问。</p>
			</div>
		</div>
		<div class="about-cont-one clearfix">
				<div class="pic"><img src="/Public/proto/Images/about3.jpg"></div>
			<div class="text">
				<p class="exp"><b>优质的产品</b>--我公司主要出口产品包含 4BT3.9, 6BT5.9, 6CT8.3, 6L8.9, ISBe3.9L, ISBe4.5L, ISDe4.5L, ISLe8.9L,ISLe9.3L,QSB,QSZ,X15,ISF2.8s, ISF3.8s,ISG,NTA855, M11, KTA19, KTA38, KTA50系列的发动机、滤芯、涡轮增压器及发动机零配件。
我公司代理销售东风、中国重汽、三环专汽等卡车。</p>
			</div>
		</div>
		<div class="about-cont-one about-cont-one-oop clearfix">
				<div class="pic"><img src="/Public/proto/Images/about4.jpg"></div>
			<div class="text">
				<p class="exp"><b>全球市场</b>--我公司在全球拥有广泛的市场
非洲市场： 埃及、加纳、利比亚,赞比亚等。
中东市场： 伊朗、伊拉克，阿联酋，沙特等。
东南亚市场：韩国、日本，印度尼西亚、马来西亚、新加坡、泰国、越南等。
美洲市场：美国、加拿大、哥伦比亚、智利等。</p>
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