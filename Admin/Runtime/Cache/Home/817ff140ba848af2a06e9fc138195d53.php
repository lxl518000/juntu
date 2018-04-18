<?php if (!defined('THINK_PATH')) exit();?>
<script type="text/javascript" src="/Public/jquery.js"></script>
<script  type="text/javascript" src="/Public/Vendor/layer-v3.0.1/layer/layer.js"></script>
<link rel="stylesheet" href="/Public/Css/reset.css" type="text/css" />
<script src="/Public/static/js/content.js?v=1.0.0"></script>

<div class="panel-box mtp10 mlf20 mrt20">
	<div class="tit">系统信息</div>
	<div class="con">
		<table class="bordered color ">
		<tr >
		<?php if(is_array($server_info)): $i = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><td><?php echo ($key); ?></td>
			<td><?php echo ($value); ?></td>
		<?php if(($mod) == "1"): ?></tr><tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		<td colspan="2"></td>
		</tr>
		</table>
	</div>
</div>
<div class="panel-box mtp10 mlf20 mrt20">
	<div class="tit">PHP已编译模块</div>
	<div class="con">
		<?php echo ($extensions_list); ?>
	</div>
</div>