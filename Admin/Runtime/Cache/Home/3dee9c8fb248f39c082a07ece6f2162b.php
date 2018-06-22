<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title><?php echo ($titlename); ?></title>


    <link rel="shortcut icon" href="favicon.ico"> <link href="/Public/static/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/static/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/static/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/Public/static/css/animate.css" rel="stylesheet">
    <link href="/Public/static/css/style.css?v=4.1.0" rel="stylesheet">


</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">


        <div class="example" style='margin-top:5px;'>
            <div class=" btn-group hidden-xs" id="exampleToolbar" role="group">

                <form role="form"  action="<?php echo U('upfile');?>" method="post" enctype="multipart/form-data" name="upload_form" class="form-inline">

                        <div class="form-group">
                            <label for="exampleInputEmail2" class="sr-only">上传新文件</label>
                            <input type="file" name=username  placeholder="用户名" id="exampleInputEmail2" class="form-control">
                        </div>


                    <button class="btn btn-primary " type="submit" >上传</button>
                </form>

            </div>


        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>根目录文件管理</h5>
                        <div class="ibox-tools">
                          <a class="dropdown-toggle" onclick="window.location.reload();">
			                                <i class="fa fa-refresh"></i>
			                            </a>
                        </div>
                    </div>

                    <div class="ibox-content">


                        <div class="table-responsive">
                            <table class="table  TreeTable">
                                <thead>
                                    <tr>


                                        <th>文件名称</th>
                                        <th>文件类型</th>
                                        <th>文件大小</th>
                                        <th>最近修改时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="<?php echo ($vo["id"]); ?>" pId="<?php echo ($vo["pid"]); ?>" >

                                        <td><?php echo ($vo["filename"]); ?></td>
                                        <td><?php echo ($vo["filetype"]); ?></td>
                                        <td><?php echo ($vo["filesize"]); ?></td>
                                        <td><?php echo ($vo["atime"]); ?></td>
                                        <td>


											<?php if(!in_array(($vo["filename"]), explode(',',"logo.png,favicon.ico"))): echo getToolIcon('delete','J_confirm btn-xs',U('delete',array('name'=>$vo['filename']))); endif; ?>
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

 





</body>

</html>