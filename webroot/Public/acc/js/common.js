// JavaScript Document

$(function($){
   $("#tip .cancel").click(function(){
		  $("#tip").fadeOut(200);
	});
 	$("#tip .sure").click(function(){
		  $("#tip").fadeOut(200);
	});
	$("#tip a").click(function(){
	  $("#tip").fadeOut(200);
	});
/*	 $("body").append('<div id="tipNotice">数据提交中..</div>');*/
	 
	 var ths = $('.tablelist thead tr th');
	 
	 var cols = ths.length;
	
	 //$('body').css('min-width','1500px');
	 
	 if(cols>=14){
		 $('body').css('min-width','1500px');
	 }else if(cols>=10){
		 $('body').css('min-width','1150px');//1150
	 }else if(cols>7){
		 $('body').css('min-width','920px');//920
	 }else if(cols>=1){
		 $('body').css('min-width','850px');//850
	 }
	 
	
});

function getChildrenS(v,id){
	if(v == "" || v == "0"){
		$('#'+id).html("<option value=''>全部</option>");
		
		$('#country').html("<option value=''>全部</option>");
		$('#'+id).parent().find(".uew-select-text").text($('#'+id).find(":selected").text());
		$('#country').parent().find(".uew-select-text").text($('#country').find(":selected").text());
		return;
	}
	$.ajax({
	   type: "POST",
	   url: AREA_URL,
	   data: "area="+v+"&type="+id,
	   dataType : "json",
	   success: function(msg){
			$('#'+id).empty();
			var str = 
			$('#'+id).append(("<option value='' selected>全部</option>"))
			for(var i=0;i< msg.length;i++){
				$('#'+id).append("<option value='"+msg[i].RegionCode+"'>"+msg[i].RegionName+"</option>");
			}			
			$('#'+id).parent().find(".uew-select-text").text($('#'+id).find(":selected").text());
			
	   }
	 }
	);
	
}

//右侧框架页面 
document.onkeydown = function (e) {
	var ev = window.event || e;
	var code = ev.keyCode || ev.which;
	if (code == 116) {
	ev.keyCode ? ev.keyCode = 0 : ev.which = 0;
	cancelBubble = true;
	history.go(0);
	return false;
	}
}

function UsubFun(status,msg,fun,btnText,btnText2){
	if(status){
		layer.alert('操作成功！', {icon: 6},function(index){
			fun();
			layer.close(index);
		});
	}else{
		layer.alert('操作失败！'+msg, {icon: 5});
	}
}

var loadingdialog;
var dialog = null;
var dialog_alert_show = 0;
function ajaxForm(_data,_url,_subFun){
	$.ajax({
	   type: "POST",
	   url: _url,
	   data: _data,
	   dataType : "json",
	   beforeSend :function(XMLHttpRequest){
		  // UsubFun(true,"数据提交中..",function(){},"")
		  var top = $(document).scrollTop()
			$("#tipNotice").css("left",top + $(window).width()/2 - 125)
			$("#tipNotice").css("top",top )
			$("#tipNotice").show();
	   },
	   error:function(XMLHttpRequest, textStatus, errorThrown){

		    UsubFun(false,"数据请求错误..",function(){},"")

		   if(textStatus == 'parsererror'){
				 $("#tip p").html(errorThrown)
		   }
		   $("#tipNotice").hide();
		},	   
	   success: function(msg){
    	  $("#tipNotice").hide();
				 var result = msg;
				 if(result.status == 0){
				    error_show(new Array(result.info+''));
				 } else{
					success_show(new Array(result.info+''));
				 }
				 
				 if(_subFun)
				 eval('('+ _subFun + '(' + result.status + ',result.data,result.info)' + ')');
		}
	}); 
}

// 表单ID,提交处理页面路径,返回成功提交
function formcheck(formid,_url,_subFun){
    /*var frm = document.getElementById(formid);
	if( CheckForm(frm,1) ){		
		ajaxForm($("#"+formid).serialize(),url,subFun);
	
	}*/

	$.ajax({
		type:'POST',
		dataType:'json',
		url:_url,
		data:$("#"+formid).serialize(),
		success:function(data){
			if(data.status == 1){
				jump = data.url;
    			//layer.alert('操作成功',{icon: 6}, function(index){
    				//location.reload();
    				 if(_subFun){
    					 eval('('+ _subFun + '(1,1,"'+jump+'")' + ')');
    				 }
    			    //layer.close(index);
    			//});  
    		}else{
    			layer.alert(data.info,{icon: 5});  
    		}
		}
	});	
    return false;
}

function formcheckLogin(formid,_url,_subFun){
	
	$.ajax({
		type:'POST',
		dataType:'json',
		url:_url,
		data:$("#"+formid).serialize(),
		success:function(data){
			if(data.status == 1){
				jump = data.url;
    			//layer.alert('操作成功',{icon: 6}, function(index){
    				//location.reload();
    				 if(_subFun){
    					 eval('('+ _subFun + '(1,1,"'+jump+'")' + ')');
    				 }
    			    //layer.close(index);
    			//});  
    		}else{
    			layer.msg(data.info,{icon: 5});  
    			flush('#loginyzm');
    			//$('input[name="yzm"]').val('');
    		}
		}
	});	
    return false;
}


function success_show(errMsg){
  	var msg = "";
	for (i = 0; i < errMsg.length; i++) {
		msg += " " + errMsg[i] ;
		break;
	}
	//UsubFun(true,msg,function(){},"")		
	
	if($("#result")[0]){
		 $("#result").html('<span style="color:blue">'+msg+'</span>');
	     $("#result").show(); 
		 if(typeof(result_close) == "undefined" || result_close==1)
		 window.setTimeout(function () { $("#result").hide();   },1000);
	}else{
		;       // alert(errMsg);
	}
}
function error_show(errMsg,errName){

	var msg = "";
	for (i = 0; i < errMsg.length; i++) {
		msg += "- " + errMsg[i] ;
		break;
	}
	if( dialog_alert_show==1){
		alert(errMsg[0])
	  if(errName != undefined){
                       frt = document.getElementsByName(errName[0])[0];
					   			if (frt.type!='radio' && frt.type!='checkbox') {
									frt.focus();
								}
					}
	  return ;
	}
	
	if($("#result")[0]){
		$("#result").html('<span style="color:red">'+msg+'</span>');
		$("#result").show()
		if(typeof(result_close) == "undefined"  || result_close==1)
		window.setTimeout(function () { $("#result").hide();   },1000);
	}else{
		UsubFun(false,msg,function(){},"")		 
	}
}

function getSelectCheckboxValues(o){
	o = o || $('#ztable');
	var result = '';
	$(o).find('tr:has(td)').each(function(i,v){
	   var obj = $(v).find(':checkbox');
	   if(obj.attr('checked')){
		  result += obj.val()+",";
	   }
	});
	return result.substring(0, result.length-1);
}

function getSelectCheckboxValue(o){
	o = o || $('.tablelist');
	var id = '';
	$(o).find('tr:has(td)').each(function(i,v){
	   var obj = $(v).find(':checkbox');
	   if(obj.attr('checked') && id ==''){
		  id = obj.val();
	   }
	});
	return id;
}

function delall(url,o){
	o = o || $('#ztable');
	var id = getSelectCheckboxValues(o);
	if (!id){
		alert('请选择删除项！');
		return false;
	}
	del(url,id);
}
var _del_id;
function del(url,id,o,idkey){
	_del_id = id+'';
	idkey = idkey || "id"
	   
	UsubFun(false,"确定删除吗?",function(){ajaxForm(idkey+'='+id,url,'delsubFun');},"")

}

function delsubFun(status,data,info){
   if(status == 1){ 
      if(typeof(deletesubFunlocation) != "undefined" && deletesubFunlocation !=''){
		  document.location = deletesubFunlocation;
	      return false;
	  }
        var _tmp_del_id = _del_id.split(',');
		   for(var i =0 ;i<_tmp_del_id.length ;i++)
		   $('#tablelist_'+_tmp_del_id[i]).remove();
			
	      //UsubFun(true,info,function(){},"")
		  
		  
		
   }
}

function edit(url){	
	var id=getSelectCheckboxValue();
	if (!id){
		alert('请选修改项！');
		return false;
	}
	document.location.href= url+id;
}


var _resumeALLtype ;// 禁用恢复状态标识
function resumeALL(){
	var id=getSelectCheckboxValues();
	_resumeALLtype = 1;
	if (!id){
		alert('请选择待恢复项！');
		return false;
	}
	resume(id);
}
function foebidALL(){
	var id=getSelectCheckboxValues();
	_resumeALLtype = 0;
	if (!id){
		alert('请选择待禁用项！');
		return false;
	}
	forbid(id);
}

function passALL(){
	var id=getSelectCheckboxValues();
	_resumeALLtype = 2;
	if (!id){
		alert('请选择待审核项！');
		return false;
	}
	pass(id);
}

var _resume_id ;
function resume(id){
	_resume_id =id+'';
	_resumeALLtype = 1;
   ajaxForm('id='+id,_resume_href,'resumesubFun');
}

function forbid(id){
	_resume_id =id+'';
	_resumeALLtype = 0;
   ajaxForm('id='+id,_forbid_href,'resumesubFun');
}

function pass(id){
	_resume_id =id+'';
	_resumeALLtype = 2;
   ajaxForm('id='+id,_pass_href,'resumesubFun');
}

function resumesubFun(status,data,info){			
   if(status == 1){ 
      if(typeof(resumesubFunlocation) != "undefined" && resumesubFunlocation !=''){
		  document.location = resumesubFunlocation;
	      return false;
	  }
	  
	  
		var _resume_id_arr = _resume_id.split(',');
		for(var i=0; i<_resume_id_arr.length ;i++){
			
			_t_resume_id = _resume_id_arr[i];
			
			if(_resumeALLtype == 0){//禁用操作
				$("#_table_"+_t_resume_id).find('span').removeClass('okstatus').addClass('lockedstatus');
				$("#_table_"+_t_resume_id).find("a:contains('禁用')").replaceWith('<a href="javascript:resume(' +_t_resume_id+ ')" class="lockedstatus" title="正常锁定,点击恢复">恢复</a>');

			}else if(_resumeALLtype == 1){//恢复操作
				$("#_table_"+_t_resume_id).find('span').removeClass('lockedstatus').addClass('okstatus');	
				$("#_table_"+_t_resume_id).find("a:contains('恢复')").replaceWith('<a href="javascript:forbid(' +_t_resume_id+ ')" class="okstatus" title="正常显示,点击禁用">禁用</a>');			
			}else{//审核操作
			    $("#_table_"+_t_resume_id).find('span').removeClass('prectedstatus').addClass('okstatus');	
				$("#_table_"+_t_resume_id).find("a:contains('审核')").replaceWith('<a href="javascript:forbid(' +_t_resume_id+ ')" class="okstatus" title="正常显示,点击禁用">禁用</a>');
				
				/*$("#_table_"+_t_resume_id).find('span').removeClass('lockedstatus').addClass('okstatus');	
				$("#_table_"+_t_resume_id).find("a:contains('恢复')").replaceWith('<a href="javascript:forbid(' +_t_resume_id+ ')">禁用</a>');*/
			}
			if(_resume_id_arr.length >1)
			$("#_table_"+_t_resume_id).find(':checkbox').attr('checked','checked');
		}
             dialog.dialog({
				title: info,				
				buttons: {	
				   关闭:function(){$(this).dialog( 'close' );}					
				}
			   });			    
			  dialog.dialog('open');
             setTimeout(function(){
				 dialog.dialog('close');
				},1100);
   }
}


var ispicupload = false;
function picupload(src,mksmallpic,subfun,picsize){
	 if(ispicupload == false){
		 $( "#uploaddialog" ).dialog({
				autoOpen:true,
				title: '上传图片',
				height: 250,
				width: 400,
				modal: true	           
		});
        //w_h_des
		$( "#uploaddialog p" ).html('<form method="post" id="uploadpicform" name="uploadpicform" action="" enctype="multipart/form-data" target="_uploaddialog_iframe_id" target="_blank" style="display:block" ><table  class="rtable" cellpadding=0 align="left" cellspacing=0 width="100%" > <tr><td colspan="2"><input type="hidden" name="mksmallpic" id="mksmallpic" value="0" /><input type="hidden" name="subFun" id="subFun" value="" />图片:<input type="file" size="20" name="image" id="image" /> <br />上传允许：gif png jpg</td></tr><tr><td colspan="2" align="left" style="height:auto" id="uploaddialog_log">图片缩略尺寸:<input value="0*0" id="uploaddialog_w_h" type="text" name="w_h" /></td></tr><tr><td colspan="2" align="left" style="height:auto" id="uploaddialog_log_des">描述:宽*高,0*0代表不缩略,可选择尺寸或自由输入</td></tr><tr><td colspan="2" align="center"><input type="submit" value="上 传" class="small submit"  >  </td></tr> <iframe width="0" height="0" id="_iframe_id" border="0" style="display:none" name="_uploaddialog_iframe_id" src="'+PUBLIC+'/index.htm"></iframe></table></form>'); 
		 ispicupload = true;
	}else{
		 $( "#uploaddialog" ).dialog('open'); 
	}
	 if(picsize != undefined && picsize != '')
	 	$('#uploaddialog_w_h').val(picsize);
	 $('#uploaddialog_w_h').attr("readonly",'readonly');	
	 $('#uploadpicform').attr('action',src);
	 $('#subFun').val(subfun);
	 $('#mksmallpic').val(mksmallpic);
	
}	
var uploadpicdialog;
//图片上传
function uploadpic(){
   
   uploadpicdialog = $('#uploadpic').dialog({
		autoOpen:true,
		height: ($(window).height()-20),
		width:800,
		modal: true,
		draggable : true,
		resizable : false,
		title: '图片上传'
	   });
   uploadpicdialog.dialog('option','height',$(window).height()-20 );  
   uploadpicdialog.dialog('open');
  // alert($(window).height())
  // alert($('#uploadpic').height())
}

function _pic_fun(status,info,data){
	if(status==0){
	  alert(info);	
	  return false;
	}

	var file = data[0][0]; 
	if(file == ''){
	  alert('图片地址不能为空')
	  return false;
	}
	$('#filename').val(file);
	var img = new Image();
	img.src='/'+file;
	
    small_w = small_w || 240;
	small_h = small_h || 180; 
	$('#_smallpicdiv').css({
			 width :small_w,
             height:small_h
	});
	
	if(img.complete){
			p_imgSize(img);
	}else{
			img.onload=function(){
				p_imgSize(img);
			}
	}
}
var scalexy=1;
function p_imgSize(img){
   var w = img.width;
   var h = img.height;
   var mw = $('#rightuploadpic').width()-4;
   var mh = $('#uploadpic').height();
       mh = mh>500?500:mh;
   var newWidth;
   var newHeight;
   var wb =  (mw/w );
   var hb =(mh/h );
   
    if(w>mw && h>mh){
					
		if(wb<hb){
		   var newWidth = mw;	
		   var newHeight = h*wb;
		   scalexy = wb;
		}else{
		   var newWidth = w*hb;	
		   var newHeight = mh;
		   scalexy = hb;
		}					
   }else if(w>mw){
		  var wb = mw/w;
		   var newWudth = mw;	
		   var newHeight = h*wb;
		   scalexy =wb;
   }else if(h>mh){
			var hb = mh/h;
			 var newWidth = w*hb;	
		   var newHeight = mh;
			scalexy =hb;
   }else{
	  var newWidth = w;	 
	  var newHeight = h;
	  scalexy = 1;	
   }
    // alert(newWidth+','+newHeight)  
	newWidth = Math.round(newWidth),
    newHeight = Math.round(newHeight),
	// alert(newWidth+','+newHeight) 
	 
    $('#_pic').attr('src',img.src);
	$('#_pic').css( {
	    width :newWidth,
        height:newHeight
	});
	$('#_pic').show();
	$('#rightuploadpic').height(newHeight);
	
	 small_w = small_w || 240;
	small_h = small_h || 180; 
    $('#_pic').imgAreaSelect( 
	{
		 handles:true, 
	     onSelectEnd : preview,
	     aspectRatio: small_w+':'+small_h
	 });
	 $('#truewh').html(w+'*'+h);
}

function preview(img, selection){
   if(!selection.width || !selection.height)
            return;
        $('#_smallpic').attr('src',img.src);
        small_w = small_w || 240;
		small_h = small_h || 180;  
        var scaleX = small_w / (selection.width);
        var scaleY = small_h / (selection.height);
		
      // alert(selection.width+','+selection.height+','+selection.x1+','+selection.y1+','+img.width+','+img.height)
        $('#_smallpic').css( {
            width : Math.round(img.width*scaleX),
            height: Math.round(img.height*scaleY),
            marginLeft: -Math.round( selection.x1*scaleX ),
            marginTop: -Math.round( selection.y1*scaleY )
        });
       
        $('#x1').val(selection.x1/scalexy);
        $('#y1').val(selection.y1/scalexy);
        $('#x2').val(selection.x2/scalexy);
        $('#y2').val(selection.y2/scalexy);
        $('#w').val(selection.width/scalexy);
        $('#h').val(selection.height/scalexy);
		$('#sw').val(small_w);
		$('#sh').val(small_h);
		
		$('#_x1_y1').html(selection.x1+','+selection.y1+'('+Math.round(selection.x1/scalexy)+','+Math.round(selection.y1/scalexy)+')');
		$('#_x2_y2').html(selection.x2+','+selection.y2+'('+Math.round(selection.x1/scalexy)+','+Math.round(selection.y1/scalexy)+')');
		$('#_w_h').html(selection.width+','+selection.height+'('+Math.round(selection.width/scalexy)+','+Math.round(selection.height/scalexy)+')');
		
		$('#trueselectwh').html(Math.round(selection.width/scalexy) + '*'+  Math.round(selection.height/scalexy));
		
}

jQuery.cookie = function(name, value, options) {
	 if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

Array.prototype.indexOf = function(val) {
	for (var i = 0; i < this.length; i++) {
		if (this[i] == val) return i;
	}
	return -1;
};
Array.prototype.remove = function(val) {
	var index = this.indexOf(val);
	if (index > -1) {
		this.splice(index, 1);
	}
};


$(document).ready(function(){
	$('.J_confirm').on('click',function(e){
		e.preventDefault();
		var _this = $(this),url = _this.data('url'),title = _this.data('title')||'确定执行此操作么？';
		
		layer.confirm(title, {icon: 3}, function(index){
		    layer.close(index);
		    $.ajax({
		    	type:'GET',
		    	url:url,
		    	dataType:'json',
		    	success:function(data){
		    		if(data.status == 1){
		    			layer.alert('操作成功',{icon: 6}, function(index){
		    				location.reload();
		    			    layer.close(index);
		    			});  
		    		}else{
		    			layer.alert(data.info,{icon: 5});  
		    		}
		    	}
		    });
		});
	});
});


//兼容页面中废弃的js ~fuck ued~
$.fn.extend({
	uedSelect:function(){}
});

//JS 导出不带分页的excel表格
function sExport(title){
	var thArr = new Array();
	var ths = $('table>thead>tr>th');
	if(ths.length == 0){
		layer.alert('请将展示方式切换为“表格”再导出！',{icon: 0,title:'系统提示'});
		return false;
	}
	
	ths.each(function(i){
		thname = $(this).text().replace(/[\*:\,\/\?\[\]]/,'');
		thArr.push(thname)
	})
	var trs = $('table>tbody>tr')
	
	if(trs.length == 0){
		layer.alert('表格无数据无法导出！',{icon: 0,title:'系统提示'});
		return false;
	}
	
	//dialog start 
	$start = '<div style="padding:10px;"><p>请选择要导出的列</p><div id="J_title_list" style="padding:5px;">';
	var $middle = '<input style="margin:0 5px;" id="J_all"  type="checkbox" class="dis_ib_vm" /><label for="J_all" class="dis_ib_vm" style="margin:0 5px;cursor:pointer;">全选</label>';
	for(var i=0;i<thArr.length;i++){
		$middle += '<input style="margin:0 5px;" id="J_'+i+'" type="checkbox" class="dis_ib_vm" name="title[]" data-index="'+i+'" value="'+thArr[i]+'" /><label for="J_'+i+'" class="dis_ib_vm" style="margin:0 5px;cursor:pointer;">'+thArr[i]+'</label>';
	}
	
	$end = '</div></div>';
	
	$content = $start+$middle+$end;
	
	$('body').on('click','#J_all',function(){
		$("input[name='title[]']").attr("checked",this.checked);
	});
	
	var thArr = [];
	var index = [];
	layer.open({
		  type: 1,
		  btn:['确定','取消'],
		  yes:function(idx){
			  var l = $('#J_title_list').find('input[name="title[]"]:checked').length;
			  if(l == 0){
				  layer.alert('请选择导出的列',{icon:0,title:'系统提示'});
				  return false;
			  }else{
				  $('#J_title_list').find('input[name="title[]"]:checked').each(function(){
					  thArr.push($(this).val());
					  index.push($(this).data('index'));
				  });
				  
				  
				  var tdArr = new Array();
					for(var i=0;i<trs.length;i++){
						var tds = $(trs[i]).find('td');
						var tmpTrArr = new Array();
						for(var j=0;j<tds.length;j++){
							if($.inArray(j,index)>-1){
								td = $(tds[j]).text();
								tmpTrArr.push(td);
							}
						}
						tdArr.push(tmpTrArr.join('EXP'));
					}
					var thead = thArr.join('_'),tdata = tdArr.join('_');
					var url = "index.php?m=Home&c=Public&a=exportdata&title="+title+'&thead='+thead+'&tdata='+tdata;
					location.href = encodeURI(url);
					
					layer.close(idx);
			  }
		  },	
		  title: '选择导出的列',
		  area:['400px','200px'],
		  content: $content
	});
	//dialog end 
}




//通过服务端查询导出数据
function exportByquery(title,Jform){
	var thArr = new Array();
	var arrField = [];
	var ths = $('table>thead>tr>th');
	if(ths.length == 0){
		layer.alert('请将展示方式切换为“表格”再导出！',{icon: 0,title:'系统提示'});
		return false;
	}
	
	if(parseInt($('#J_listTotal').text())>1000){
		layer.alert('单次导出数据条数不能大于1000',{icon: 0,title:'系统提示'});
		return false;
	}
	
	ths.each(function(i){
		thname = $(this).text().replace(/[\*:\,\/\?\[\]]/,'');
		if($(this).data('field')  != 'undefined'){
			thArr.push(thname)
			arrField.push($(this).data('field'));
		}
	})
	var trs = $('table>tbody>tr')
	
	if(trs.length == 0){
		layer.alert('表格无数据无法导出！',{icon: 0,title:'系统提示'});
		return false;
	}
	
	//dialog start 
	$start = '<div style="padding:10px;"><p>请选择要导出的列</p><div id="J_title_list" style="padding:5px;">';
	var $middle = '<input style="margin:0 5px;" id="J_all" type="checkbox" class="dis_ib_vm" /><label for="J_all" class="dis_ib_vm" style="margin:0 5px;cursor:pointer;">全选</label>';
	for(var i=0;i<thArr.length;i++){
		$middle += '<input style="margin:0 5px;" data-field="'+arrField[i]+'" id="J_'+i+'" type="checkbox" class="dis_ib_vm" name="title[]" value="'+thArr[i]+'" /><label for="J_'+i+'" class="dis_ib_vm" style="margin:0 5px;cursor:pointer;">'+thArr[i]+'</label>';
	}
		
	$end = '</div></div>';
	
	$content = $start+$middle+$end;
	
	$('body').on('click','#J_all',function(){
		$("input[name='title[]']").attr("checked",this.checked);
	});
	
	var thArr = [];
	var goField = [];
	layer.open({
		  type: 1,
		  btn:['确定','取消'],
		  yes:function(idx){
			  var l = $('#J_title_list').find('input[name="title[]"]:checked').length;
			  if(l == 0){
				  layer.alert('请选择导出的列',{icon:0,title:'系统提示'});
				  return false;
			  }else{
				  $('#J_title_list').find('input[name="title[]"]:checked').each(function(){
					  thArr.push($(this).val());
					  if($(this).data('field') != 'undefined'){
						  goField.push($(this).data('field'));
					  }
				  });
				var url = $('#'+Jform).attr('action')+'?'+$('#'+Jform).serialize()+'&export=1'+"&title="+title+'&thead='+thArr.join(',')+'&fld='+goField.join(',');
				location.href = encodeURI(url);
				
				layer.close(idx);
			  }
		  },	
		  title: '选择导出的列',
		  area:['400px','200px'],
		  content: $content
	});
	//dialog end
	
}




