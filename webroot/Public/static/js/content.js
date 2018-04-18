var $parentNode = window.parent.document;

function $childNode(name) {
    return window.frames[name]
}

// tooltips
/*$('.tooltip-demo').tooltip({
    selector: "[data-toggle=tooltip]",
    container: "body"
});
*/
// 使用animation.css修改Bootstrap Modal
$('.modal').appendTo("body");

//$("[data-toggle=popover]").popover();

//折叠ibox
$('.collapse-link').click(function () {
    var ibox = $(this).closest('div.ibox');
    var button = $(this).find('i');
    var content = ibox.find('div.ibox-content');
    content.slideToggle(200);
    button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
    ibox.toggleClass('').toggleClass('border-bottom');
    setTimeout(function () {
        ibox.resize();
        ibox.find('[id^=map-]').resize();
    }, 50);
});

//关闭ibox
$('.close-link').click(function () {
    var content = $(this).closest('div.ibox');
    content.remove();
});

//判断当前页面是否在iframe中
if (top == this) {
    var gohome = '<div class="gohome"><a class="animated bounceInUp" href="index.html?v=4.0" title="返回首页"><i class="fa fa-home"></i></a></div>';
    $('body').append(gohome);
}

//animation.css
function animationHover(element, animation) {
    element = $(element);
    element.hover(
        function () {
            element.addClass('animated ' + animation);
        },
        function () {
            //动画完成之前移除class
            window.setTimeout(function () {
                element.removeClass('animated ' + animation);
            }, 2000);
        });
}

//拖动面板
function WinMove() {
    var element = "[class*=col]";
    var handle = ".ibox-title";
    var connect = "[class*=col]";
    $(element).sortable({
            handle: handle,
            connectWith: connect,
            tolerance: 'pointer',
            forcePlaceholderSize: true,
            opacity: 0.8,
        })
        .disableSelection();
};


function J_open(obj){
	var that = $(obj);

		var url = that.data('url');
		var mul = that.data('multiple');
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

		var title = that.data('title') || that.text();	
		var sindex = layer.load(2);
	
		_area =  ['80%','90%'];
		if(that.data('area')){
			_arr = that.data('area').split(',');
		
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
}

$(function(){
	$('.J_open').on('click',function(){
		J_open(this);
	})
	
	$('.J_confirm').on('click',function(){
		J_confirm(this);
	})
	
	//绑定多选按钮
	$('.J_multiple').on('click',function(e){
		e.preventDefault();
		J_multiple(this);
	});
	
	
	if($('.i-checks').length>0 || $('.check-all').length>0){
		//应用i-checks 样式
	    $('.i-checks').iCheck({
	        checkboxClass: 'icheckbox_square-green',
	        radioClass: 'iradio_square-green',
	    });
	    
	    //全反选i-checks实现
	    $('.check-all').iCheck({
	    	  checkboxClass: 'icheckbox_square-green',
	          radioClass: 'iradio_square-green',
	    }).on('ifChecked',function(){
	    	$('.i-checks').iCheck("check");
	    }).on('ifUnchecked',function(){
	    	$('.i-checks').iCheck("uncheck");
	    })
	}

    
   
	
})

function lockButton(obj,str){
	str = str ? str : '提交中...'
	obj.attr('disabled',true).addClass("btn-default").html(str);
}
function unlockButton(obj,str){
	str = str ? str : '提交';
	obj.attr('disabled',false).removeClass('btn-default').html(str);
}

//AJAX提交表单
 function ajaxForm (form) {
    if (undefined == form || '' == form) form = '#signupForm';
    var $form = $(form);
    var cb = $(form).data('callback');
    var par = $(form).data('par');
    
    var button = $form.find('button[type="submit"]');
    
    lockButton(button);
    $.post($form.attr('action'), $($form).serialize(), function (response) {
    
        //弹出提示消息
        if (response.status==1) {
        	
        	if(cb && cb != 'undefined'){
        		data = JSON.stringify(response);
        		// eval('('+cb+ '()' + ')');
        		eval( cb+"("+data+")");
				return;
        	}
        	
        	//如果需要跳转的话，消息的末尾附上即将跳转字样
            response.info += '，页面即将跳转～!';
            layer.msg(response.info);
          
            if(par){
            	  setTimeout(function () {
            		  parent.location.reload();
                }, 1000);
            	return;
            }
            
            if (response.url) {
                //需要跳转的话就跳转
                setTimeout(function () {
                  	    location.href = response.url;
                  }, 1000);
            }else{
            	setTimeout(function(){
            		var sindex = parent.layer.getFrameIndex(window.name);
                	parent.layer.close(sindex);
            	},1000)
            	
            }
              
        } else {
        	layer.msg(response.info,{icon:5});
        	unlockButton(button);
        }
        
    
      
    }, 'json');
}
	


function J_confirm(obj){
		event.preventDefault();
		
		var _this = $(obj),url = _this.data('url'),title = _this.data('title')||'确定执行此操作么？';
		
		var mode = _this.data('mode') || 1;
		
		layer.confirm(title, {icon: 3}, function(index){
		    layer.close(index);
		    $.ajax({
		    	type:'GET',
		    	url:url,
		    	dataType:'json',
		    	success:function(data){
		    		if(data.status == 1){
		    			var info = data.info || '操作成功';
						layer.msg(info);
						if(mode==2){
							_this.parent().parent().hide(600);
						}else{
							setTimeout(function(){
							location.reload();
						},600)
						}
						
						
					
		    		}else{
		    			layer.msg(data.info,{icon: 5});  
		    		}
		    	}
		    });
		});
}

function J_multiple(obj){
	var _this = $(obj);
	var length = $('input[name="chkid"]:checked').length;
	if(length < 1){
		layer.msg('请至少选择1条数据');return;
	}
	var arr=[];
	$('input[name="chkid"]:checked').each(function(i){
		//arr += ','+$(this).val();
		arr.push($(this).val());
	})
	
	//console.log(_this.data('url'));return;
	
	var url = _this.data('url'),title = _this.data('title')||'确定要执行此操作吗？';
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
}


