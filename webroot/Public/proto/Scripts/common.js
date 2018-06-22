/*JS Document
*author:cyy
*/

$(function(){
	
	$(".jinkou-logos-list li img").imgCenter();
	$(".pros-simple-one .pic img").imgCenter();
	$(".pro-det-head .pic img").imgCenter();
	$(".pro-det-head .pic-flat img").imgCenter();
	
	
	
	/*导航显示当前*/
	navShowCurrent(".main-nav-list li a","on");
	function navShowCurrent(tarCell,className){
		var curpageid = $("body").attr("data-curpageid");
		$(tarCell).each(function(){
			var pageid = $(this).attr("data-pageid");
			if(pageid === curpageid){
				$(this).parent().siblings().removeClass(className);
				$(this).parent().addClass(className);
			}
		});
	}
	
	
	
	
	
	
})
