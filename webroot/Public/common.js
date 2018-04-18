

$(function(){
    //全选的实现
    $(".check-all").click(function(){
        $(".ids").prop("checked", this.checked);
    });
    $(".ids").click(function(){
        var option = $(".ids");
        option.each(function(i){
            if(!this.checked){
                $(".check-all").prop("checked", false);
                return false;
            }else{
                $(".check-all").prop("checked", true);
            }
        });
    });
    // highlight_subnav();
});


function getChildrenS(v,id){
	if(v == "" || v == "0"){
		$('#'+id).html("<option value=''>全部</option>");
		
		$('#town').html("<option value=''>全部</option>");
		$('#'+id).parent().find(".uew-select-text").text($('#'+id).find(":selected").text());
		$('#town').parent().find(".uew-select-text").text($('#town').find(":selected").text());
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


$(document).ready(function(){
	
	//绑定删除确认按钮
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
		    			var info = data.info || '操作成功';
		    			layer.alert(info,{icon: 6}, function(index){
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
	
	
	//绑定多选按钮
	$('.J_multiple').on('click',function(e){
		e.preventDefault();
		var length = $('input[name="chkid"]:checked').length;
		if(length < 1){
			layer.msg('请至少选择1条数据');return;
		}
		var arr=[];
		$('input[name="chkid"]:checked').each(function(i){
			//arr += ','+$(this).val();
			arr.push($(this).val());
		})
		
		var _this = $(this),url = _this.data('url'),title = _this.data('title')||'确定要执行此操作吗？';
		layer.confirm(title, {icon: 3}, function(index){
		    layer.close(index);
		    $.ajax({
		    	type:'POST',
		    	url:url,
		    	dataType:'json',
		    	data:'ids='+arr,
		    	success:function(data){
		    		if(data.status == 1){
		    			var info = data.info || '操作成功';
		    			layer.alert(info,{icon: 6}, function(index){
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
	
	
	
	
	//绑定弹层按钮
	$('.J_open').on('click',function(e){
		
		var url=$(this).data('url');
		
		var mul = $(this).data('multiple');
		if(mul){
			var length = $('input[name="chkid"]:checked').length;
			if(length < 1){
				layer.msg('请至少选择1条数据');return;
			}
			var arr=[];
			$('input[name="chkid"]:checked').each(function(i){
				//arr += ','+$(this).val();
				arr.push($(this).val());
			})
			url += "&ids="+arr;
			
		}
		
		var	title=$(this).text();
		var sindex = layer.load(2);
	
		
		_area =  ['700px','550px'];
		if($(this).data('area')){
			_arr = $(this).data('area').split(',');
		
			$(_arr).each(function(i){
				_area[i] = _arr[i];
			})
		}

		layer.open({
			  type: 2,
			  area: _area,
			  title : title,
			  fix: false, //不固定
			  maxmin: true,
			  content: url
			});
		layer.close(sindex);
		
	})
	
	

});


//AJAX提交表单
 function ajaxForm (form,par) {
    if (undefined == form || '' == form) form = '#pageForm';
    var $form = $(form);
    var cb = $(form).data('callback');
  //  $(":submit", $form).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
    $.post($form.attr('action'), $($form).serialize(), function (response) {
     
    
        //弹出提示消息
        if (response.status) {
        	
        	if(cb && cb != 'undefined'){
        		data = JSON.stringify(response);
        		// eval('('+cb+ '()' + ')');
        		eval( cb+"("+data+")");
        		 return;
        	}
            if (response.url) {
            	//如果需要跳转的话，消息的末尾附上即将跳转字样
                response.info += '，页面即将跳转～';
            }
        	layer.msg(response.info,{icon:6});

        	//console.log(response);return;
        
        
        	
            //需要跳转的话就跳转
            var interval = 1000;
                setTimeout(function () {
                	if(par==true){
                		parent.location.reload();
                	}else{
                	    location.href = response.url;
                	}
                
                }, interval);
        } else {
        	layer.msg(response.info,{icon:5})
        	if(cb && cb != 'undefined'){
        		data = JSON.stringify(response);
        		// eval('('+cb+ '()' + ')');
        		eval( cb+"("+data+")");
        		 return;
        	}
        	
        //    $(":submit", $form).removeClass('disabled').prop('disabled',false);
        }
        
    
      
    }, 'json');
}
 
 var tips = {};
 

//成功提示
tips.success = function (msg, title) {
   if (undefined == title) title = '成功提示';
   layer.msg(msg,{icon:6})
}


//失败提示
tips.error = function (msg, title) {
   if (undefined == title) title = '失败提示';
   layer.msg(title,{icon:5})
}

//信息提示
tips.info = function (msg, title) {
   if (undefined == title) title = '提示';
   layer.msg(title,{icon:4})
}


function getCookie(name) 
{ 
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
 
    if(arr=document.cookie.match(reg))
 
        return unescape(arr[2]); 
    else 
        return null; 
} 

function setCookie(name,value) 
{ 
    var Days = 30; 
    var exp = new Date(); 
    exp.setTime(exp.getTime() + Days*24*60*60*1000); 
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
} 


function kcall(phone){
	var url = K_CALL_URL+"&phone="+phone;
	var _area =  ['700px','550px'];
	crm = top.layer.open({
		  type: 2,
		  skin: 'demo-class',
		  area: _area,
		  title : '拨打电话',
		
		  content: url
		});
}


function sendMail(){
	var url = K_EMAIL_URL;
	var _area =  ['700px','550px'];
	crm = top.layer.open({
		  type: 2,
		  skin: 'demo-class',
		  area: _area,
		  title : '发送短信',
		  //shadeClose:true,
		
		  content: url
		});
}



