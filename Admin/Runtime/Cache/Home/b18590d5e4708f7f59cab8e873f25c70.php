<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<title><?php echo ($titlename); ?></title>
    <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
    <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/Public/static/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/static/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/static/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/Public/static/css/animate.css" rel="stylesheet">
    <link href="/Public/static/css/style.css?v=4.1.0" rel="stylesheet">


</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
       
      
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo ($titlename); ?></h5>
                        <div class="ibox-tools">
                          <a class="dropdown-toggle" onclick="window.location.reload();">
			                                <i class="fa fa-refresh"></i>
			                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            
                            <div class="col-sm-8 m-b-xs">
                              <?php echo getToolIcon('add','J_open btn-sm',U('add'));?>
                                <?php echo getToolIcon('delete','J_confirm btn-sm',U('delete'));?>
                                 <?php echo getToolIcon('on','J_multiple btn-sm',U('enable'));?>
                                 <?php echo getToolIcon('off','J_multiple btn-sm',U('disable'));?>
                            </div>
                            <div class="col-sm-3">
                               <form class="AjaxBatch" data-action="/Home/Sysrole" method="post">
                                <div class="input-group">
                                    
                                    <input type="text" name='title' value="<?php echo ($_REQUEST['title']); ?>" placeholder="请输入关键词" class="input-sm form-control"> <span class="input-group-btn">
                                    
                                        <button type="submit" class="btn btn-sm btn-primary"> 搜索</button> </span>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table  TreeTable">
                                <thead>
                                    <tr>

                                        <th><input type='checkbox' name='' class="check-all" ></th>
                                        <th>角色ID</th>
                                        <th>角色名称</th>
                                        <th>角色描述</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="<?php echo ($vo["id"]); ?>" pId="<?php echo ($vo["pid"]); ?>" >
                                        <td>
                                            <input type="checkbox"  class="i-checks" value="<?php echo ($vo["id"]); ?>" name="chkid">
                                        </td>
                                        <td><?php echo ($vo["id"]); ?></td>
                                        <td><?php echo ($vo["title"]); ?></td>
                                        <td><?php echo ($vo["remark"]); ?></td>
                                         <td><?php echo ($vo['status'] == 1?'<span style="color:green">启用</span>':'<span style="coolor:#ccc">禁用</span>'); ?>
                                         </td>
                                        <td> 
                                          		<?php echo getToolIcon('add','J_open btn-xs',U('add',array('pid'=>$vo['id'])),'添加子菜单');?>
                                      	   
                                    	   <?php echo getToolIcon('edit','J_open btn-xs',U('edit',array('id'=>$vo['id'])));?>
                                       
                                            <?php echo getToolIcon('delete','J_confirm btn-xs',U('delete',array('id'=>$vo['id'])));?>
                                            
                                            <?php if(($vo["status"]) == "1"): echo getToolIcon('off','J_confirm btn-xs',U('disable',['id'=>$vo['id']]));?>
                                            <?php else: ?>
                                         		<?php echo getToolIcon('on','J_confirm btn-xs ',U('enable',['id'=>$vo['id']])); endif; ?>
                                  			<?php echo getToolIcon('grant','J_open btn-xs ',U('authrule',['id'=>$vo['id']]));?>
                                        </td>
                                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
       
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 全局js -->
    <script src="/Public/static/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/static/js/bootstrap.min.js?v=3.3.6"></script>
    
    <script src="/Public/static/js/plugins/layer/layer.min.js"></script>
    <script src="/Public/static/js/content.js?v=1.0.0"></script>
    
    <!-- iCheck -->
    <script src="/Public/static/js/plugins/iCheck/icheck.min.js"></script>

 
<script src="/Public/static/Vendor/TableTree/jquery.treeTable.js"></script>
<link rel="stylesheet" href="/Public/static/Vendor/TableTree/vsStyle/jquery.treeTable.css" type="text/css" />


    <script>
        $(document).ready(function () {
          	  var option = {
                        theme:'vsStyle',
                        expandLevel :3,
                        column : 2,
                    };
              $('.TreeTable').treeTable(option);
        });
        
        
    </script>



</body>

</html>