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



// 表单ID,提交处理页面路径,返回成功提交
function formcheck(formid,_url){
    /*var frm = document.getElementById(formid);
	if( CheckForm(frm,1) ){		
		ajaxForm($("#"+formid).serialize(),url,subFun);
	}*/
	alert('1');
	$.ajax({
		type:'POST',
		dataType:'json',
		url:_url,
		data:$("#"+formid).serialize(),
		success:function(data){
			//console.log(data);return;
				if(data.status == 1){
				//	console.log(data);return;
					jump = data.url;
					layerTips(data.info,'#09C1FF');
				//	layerTips(data.info,'#09C1FF');
	    			setTimeout(function(){
	    				window.location=jump
	    			},1000)
    			
    		}else{
    			layerInfo(data.info)
    			
    		}
    			
		}
	});	
    return false;
}

//layer信息提示
function layerInfo(content){
	 layer.open({
	    content: content,
	    btn: ['OK']
	});
 /* layer.open({
             title: title,
            content: content
    });*/
}

//layer layer
function layerTips(info,color){
	layer.open({
	    content: info,
	    style: 'background-color:'+color+';color:#fff;border:none;',
	    time: 2
	});
}