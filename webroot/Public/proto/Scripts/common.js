/*JS Document
*author:cyy
*/

$(function(){

//	$(".jinkou-logos-list li img").imgCenter();
//	$(".pros-simple-one .pic img").imgCenter();
//	$(".pro-det-head .pic img").imgCenter();
//	$(".pro-det-head .pic-flat img").imgCenter();
//	

	
	//imgCenter($(".pros-simple-one .pic img"));

	//$(".pros-simple-one .pic img").onload(function(){})
	
})



function imgCenter(obj){
		
		/*参数为目标图片，使用时注意外层应为块级元素且有高度*/
		var $tar = $(obj);
		console.log($tar);
		var $this = $tar.parent();
		$img = $tar;
		//回复图片原始宽高（防止其他js干扰）
		$img.css({height:"auto",width:"auto"});
		/*先调整宽度，再调整高度*/
		var wrap_w = $this.width();
	
			img_w = $img.width();
		if(img_w > wrap_w){
			$img.width(wrap_w);
		}
		var wrap_h = $this.height(),
			img_h = $img.height();
		if(img_h > wrap_h){
			$img.height(wrap_h);
		}
		img_w = $img.width();
		
		img_h = $img.height();
		var this_pos = $this.css("position");
		if(this_pos != "absolute" && (this_pos != "relative") && (this_pos != "fixed")){
			$this.css({position:"relative"});
		}
		$img.css({position:"absolute",left:"50%",top:"50%",marginLeft:-img_w/2,marginTop:-img_h/2});
}