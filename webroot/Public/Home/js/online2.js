$(document).ready(function(){
	//搜索
	if($(".fine .search_txt").length > 0){
		$(".fine .search_txt").val("请输入关键词");
	}
	
	$(".fine .search_txt").focus(function(){
		if($(".fine .search_txt").val()=="请输入关键词" || $(".header .search_txt").val()==""){
			$(".fine .search_txt").val("");
			$(".fine .search_txt").css({color:"#7A7A7A"});
		}
	});
	
	$(".fine .search_txt").blur(function(){
		if($(".fine .search_txt").val()==""){
			$(".fine .search_txt").val("请输入关键词");
			$(".fine .search_txt").css({color:"#727272"});
		}
	});
	$("#search_btn").bind("click",function(){
		
		if($("#header_kw").val()=='' || $("#header_kw").val()=='请输入关键词')
		{
			$("#header_kw").focus();
		}
		else{
			var url =$("#header_search_box").attr("action")+"?ctl=case&act=index&kw="+$("#header_search_box .search_txt").val();
			$("#header_search_box").attr("action",url);
			$("#header_search_box").submit();
		}
	});
	//分页input填写返回链接
	$("#J_page_go").bind("click",function(){
		var p=$(".page_input").attr("value");
		var tatolpage=$(this).attr("did");
		if(p<=tatolpage && p>0){
			window.location.href=APP_ROOT+"/index.php?ctl=case&p="+p;
		}else if(p=='' || p<=0){
			alert("页索不是有效的数值");
		}else{
			alert("页索引超出范围");
		}
	});
	//返回顶部
	init_gotop()
	//弹出微信二维码
	$("#J_link_weixi #J_link_fuwu").toggle(function () {
		$(".footer_weixi_fuwu").css({"display":"block"});
	},function(){
		$(".footer_weixi_fuwu").css({"display":"none"});
	});
	$("#J_link_weixi #J_link_dy").toggle(function () {
		$(".footer_weixi_dy").css({"display":"block"});
	},function(){
		$(".footer_weixi_dy").css({"display":"none"});
	});

	 $("#floatShow").bind("click",function(){
            $('#onlineService').animate({width: 'show', opacity: 'show'}, 'normal',function(){ $('#onlineService').show(); });$('#floatShow').attr('style','display:none');$('#floatHide').attr('style','display:block');
            return false;
        });
        $("#floatHide").bind("click",function(){
            $('#onlineService').animate({width: 'hide', opacity: 'hide'}, 'normal',function(){ $('#onlineService').hide(); });$('#floatShow').attr('style','display:block');$('#floatHide').attr('style','display:none');
        });
        $(document).bind("click",function(event){
            if ($(event.target).isChildOf("#online_qq_layer") == false)
            {
                $('#onlineService').animate({width: 'hide', opacity: 'hide'}, 'normal',function(){ $('#onlineService').hide(); });$('#floatShow').attr('style','display:block');$('#floatHide').attr('style','display:none');
            }
        });
        jQuery.fn.isChildAndSelfOf = function(b){
            return (this.closest(b).length > 0);
        };
        jQuery.fn.isChildOf = function(b){
            return (this.parents(b).length > 0);
        };

});

function setScollMenu(){
	$(".J_Scoll_menu .xuqiu_toll .toll_img").each(function(){
		var div_height = $(this).outerHeight();
		var img_height = $(this).find("img").outerHeight();
		$(this).find("img").css({"margin-top":(div_height - img_height - 34)/2 +"px"});	
	});
	
	$('.J_Scoll_menu').hover(function(){
		var div_height = $(this).outerHeight();
		var img_height = $(this).find("img").outerHeight();
		var height=(div_height-34)/2+(img_height/2)+34;
		$(this).find(".xuqiu_toll").stop().animate({"top":-height+"px"}, 200); 
	},function(){
		$(this).find(".xuqiu_toll").stop().animate({"top":"0%"}, 200); 
	});
}
function init_gotop()
{
	
	
	$(window).scroll(function(){
		
		var s_top = $(document).scrollTop()+$(window).height()-70;
		if($.browser.msie && $.browser.version =="6.0")
		{
			$("#gotop").css("top",s_top);
			if($(document).scrollTop()>0)
			{				
				$("#gotop").css("visibility","visible");	
			}
			else
			{
				$("#gotop").css("visibility","hidden");	
			}
		}	
		else
		{
			if($(document).scrollTop()>0)
			{
				if($("#gotop").css("display")=="none")
				$("#gotop").fadeIn();	
			}
			else
			{
				if($("#gotop").css("display")!="none")
				$("#gotop").fadeOut();
			}
		}
		
		
	});		
	
	$("#gotop").bind("click",function(){		
		$("html,body").animate({scrollTop:0},"fast","swing",function(){});		
	});
	var top = $(document).scrollTop()+$(window).height()-70;
	if($.browser.msie && $.browser.version =="6.0")
	{
		$("#gotop").css("top",top);
		if($(document).scrollTop()>0)
		{	
			$("#gotop").css("visibility","visible");
		}
		else
		{
			$("#gotop").css("visibility","hidden");
		}
	}
	else
	{
		if($(document).scrollTop()>0)
		{	
			if($("#gotop").css("display")=="none")
			$("#gotop").show();	
		}
		else
		{
			if($("#gotop").css("display")!="none")
			$("#gotop").hide();
		}
	}
	

}
