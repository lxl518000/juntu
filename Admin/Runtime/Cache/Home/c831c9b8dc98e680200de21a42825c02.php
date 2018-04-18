<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	
    <title><?php echo ($titlename); ?> </title>
  
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/Public/static/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/static/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/static/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <link href="/Public/static/css/animate.css" rel="stylesheet">
    <link href="/Public/static/css/style.css?v=4.1.0" rel="stylesheet">
    

    
<style>
#exampleToolbar .btn{margin-right:5px;}
.float-e-margins .btn{margin-bottom:0;}
.fixed-table-toolbar .bars, .fixed-table-toolbar .columns, .fixed-table-toolbar .search {margin-top:0px;}
</style>
</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeIn">


        <!-- Panel Other -->
        <div class="ibox float-e-margins">
         
            <div class="ibox-content">
                <div class="row row-lg">
                  
                    <div class="col-sm-12">
                        <!-- Example Toolbar -->
                        <div class="example-wrap ">
                           
                            
                          <!--     <?php echo getToolIcon('add','J_open btn-sm',U('add'));?>
                                <?php echo getToolIcon('delete','J_confirm btn-sm',U('delete'));?> -->
                                 <?php echo getToolIcon('on','J_multiple btn-sm',U('enable'));?>
                                 <?php echo getToolIcon('off','J_multiple btn-sm',U('disable'));?>

                            
                            
                            
                                    <div class="ibox-tools">
			                        <!--     <a class="collapse-link">
			                                <i class="fa fa-chevron-up"></i>
			                            </a> -->
			                            <a class="dropdown-toggle" onclick="window.location.reload();">
			                                <i class="fa fa-refresh"></i>
			                            </a>
			                           <!--  <a class="close-link">
			                                <i class="fa fa-times"></i>
			                            </a> -->
			                        </div>
                        
                          
                            <div class="example" style='margin-top:5px;'>
                                <div class=" btn-group hidden-xs" id="exampleToolbar" role="group">
                                
                                  <form role="form" id="searchForm" class="form-inline">
                                    
                           	 <div class="form-group">
                                <label for="exampleInputEmail2" class="sr-only">用户名</label>
                                <input type="text" name="username"  placeholder="用户名"  class="form-control">
                      </div>
                   <div class="form-group">
                      <label for="exampleInputEmail2" class="sr-only">联系电话</label>
                         <input type="text" name='mobile'  placeholder="联系电话"  class="form-control">
                          
                           		 
    
                            <button class="btn btn-white " type="button" onclick="formSearch()">搜索</button>
                       	 </form>
                   
                      </div>
                                
                     
                </div>
                                
                                <table id="exampleTableToolbar" data-mobile-responsive="true">
                                    <thead >
                                        <tr>
                                        
	
		  <th data-field="stat" data-formatter="setCheckbox" data-sortable='false'>
		  	<span data-ck='0' onclick="getall(this)">全选</span>
		  </th>
         <th data-field="id"  data-sortable="true">id</th>
         <th data-field="account"  data-sortable="true" >账号</th>
         <th data-field="ismobile"  data-sortable="true" >是否绑定</th>
         <th data-field="usertype"  data-sortable="true">用户类型</th>
         <th data-field="vip"  data-sortable="true">VIP</th>
         <th data-field="regtime"  data-sortable="true" >注册时间</th>
         <th data-field="logintime"  data-sortable="true">最后登录时间</th>
         <th data-field="operate"  data-sortable="true">操作</th>
 
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- End Example Toolbar -->
                    </div>

   


                </div>
            </div>
        </div>
        <!-- End Panel Other -->
    </div>

    <!-- 全局js -->
    <script src="/Public/static/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/static/js/bootstrap.min.js?v=3.3.6"></script>
	  <script src="/Public/static/js/plugins/layer/layer.min.js"></script>
     <!-- Peity -->
    <script src="/Public/static/js/plugins/iCheck/icheck.min.js"></script>
    <!-- 自定义js -->
    <script src="/Public/static/js/content.js?v=1.0.0"></script>


    <!-- Bootstrap table -->
    
    <script src="/Public/static/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
    <script src="/Public/static/js/plugins/bootstrap-table/bootstrap-table-mobile.min.js"></script>
    
    <script src="/Public/static/js/plugins/bootstrap-table/tableExport.min.js"></script>

    <script src="/Public/static/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
  
     <script src="/Public/static/js/plugins/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>

	<script>
	
	
 (function() {
		  
	 
	    $('#exampleTableToolbar').bootstrapTable({
	      url: "<?php echo U();?>",
	      search: false,
	      showRefresh: true,
	      uniqueId:'id',
	      showToggle: true,
	      showColumns: true,
	      toolbar: '#exampleToolbar',
		  pagination:true,
		  pageSize:15,
		  sortOrder:'desc',
		   pageList: [15, 30, 50, 100,300], //可供选择的每页的行数（*）  
		  sidePagination:'server',
		  searchText:'',
	      iconSize: 'outline',
	     // selectItemName:'chkid',
	     // cache:true,
	      //clickToSelect: true,//点击行即可选中单选/复选框  
	     // showFullscreen:true,
	      //detailView:true,
		  //cardView:true,
		  //detailView:true,
		  <?php if(allow(CONTROLLER_NAME.'/export')): ?>showExport: true,  //是否显示导出按钮
		    <?php else: ?>
	      showExport: false,  //<?php endif; ?>
		    exportDataType:'all',  
		   // exportTypes:['excel'],  //导出文件类型  
		  
		    exportOptions:{  
		           ignoreColumn: [0,1],  //忽略某一列的索引  
		           fileName: '<?php echo ((isset($exporttitle) && ($exporttitle !== ""))?($exporttitle):"数据导出"); ?>',  //文件名称设置  
		           worksheetName: 'sheet1',  //表格工作区名称  
		           tableName: '数据导出',  
		           //excelstyles: ['background-color', 'color', 'font-size', 'font-weight'],  
		           onMsoNumberFormat: DoOnMsoNumberFormat  
		       },  
		   
	      icons: {
	        refresh: 'glyphicon-repeat',
	        toggle: 'glyphicon-list-alt',
	        columns: 'glyphicon-list',
	        export: 'glyphicon-export icon-share'
	      },
	      queryParams:queryParams,
	      
	    })
	    
	  
	    
	    function queryParams(params){
	    	return {limit:params.limit,
	    			offset:params.offset,
	    			sort:params.sort,
	    			order:params.order,
	    			formfield:$('#searchForm').serialize(),
	    	}
	    }
	    
	    function DoOnMsoNumberFormat(cell, row, col) {  
	        var result = "";  
	        if (row > 0 && col == 0)  
	            result = "\\@";  
	        return result;  
	    }  
	 
	

  })();
  
 function formSearch(){
 	$('#exampleTableToolbar').bootstrapTable(('refresh'));
 }
 
 function setCheckbox(value, row, index) {
		var str = ' <input type="checkbox"  class="i-checks" value="'+row.id+'" name="chkid">';
		
  return [str].join('');
  return;
}
 
 function getall(obj){
	 var stat = $(obj).data('ck');
	 if(stat==0){
		 console.log('check');
		 $(obj).data('ck',1);
		 $('input[name="chkid"]').prop('checked',true);
	 }else{
		 console.log('uncheck');
		 $(obj).data('ck',0);
		 $('input[name="chkid"]').prop('checked',false);
		
	 }
	 
 }

	
	
  
	</script>
	
	
	
	
	

	
</body>

</html>