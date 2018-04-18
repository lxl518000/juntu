<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="/Public/jquery.js"></script>
<script  type="text/javascript" src="/Public/Vendor/layer-v3.0.1/layer/layer.js"></script>
<link rel="stylesheet" href="/Public/Css/reset.css" type="text/css" />
<script src="/Public/static/js/content.js?v=1.0.0"></script>
<table>
 <form id="signupForm" data-par='close' action="<?php echo U();?>">
                        
<tr>
    <th>权限列表:</th>
    <td>
        <ul id="CheckTree" class="ztree"></ul>
        <input type="hidden" name="rules" id="RulesValue" value="<?php echo ($user_group); ?>">
        <input type="hidden" name="id" value="<?php echo ($id); ?>">
    </td>
    
     <tr>
       	<th></th>
       	 	<td><input type="button"  onclick="ajaxForm()" id="btn_sub" class="btn-big btn-blue" value="授权"></td>
        </tr>
</tr>
</form>
</table>
<link rel="stylesheet" href="/Public/Vendor/Ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">

  <script type="text/javascript" src="/Public/Vendor/Ztree/jquery.ztree.all-3.5.min.js"></script>
  <script type="text/javascript">
	  var setting = {
	        check: {enable: true,
	        	chkboxType:{ "Y" : "ps", "N" : "s" }
	  		},
	        data:{
	            simpleData: {enable: true},
	        },
	        callback:{
                onCheck:onCheck
            },
            
	    };
	    var zNodes = <?php echo ($node); ?>;
	    var tree = $.fn.zTree.init($("#CheckTree"), setting, zNodes);
	    
	    function onCheck(e,treeId,treeNode){
	        var node = tree.getCheckedNodes(true);
	        var data='';
	        for (var i = 0; i < node.length; i++) {
	            data += node[i].id+',';
	        }
	        data=data.substr(0,data.length-1);
	        $('#RulesValue').val(data);
		 }
  
</script>