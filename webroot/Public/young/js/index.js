/***** 首页 *****/

$(function(){

	/* top */
	$(".topbar ul li.top_r a span").mouseenter(function() {
		$(this).find("i").stop(true,true).animate({
			 margin: '0px 0 0 120px'	
		}, 500);
	    $(this).find("b").stop(true,true).animate({
			 left: '20px'	
		}, 500);
	});
	$(".topbar ul li.top_r a span").mouseleave(function() {
		$(this).find("i").stop(true,true).animate({
			margin: '0px 0 0 0px'
		}, 500);
		$(this).find("b").stop(true,true).animate({
			left: '-100px'
		}, 500);
	});

	/* nav */
	$(".nav ul li a").mouseenter(function() {
		$(this).find("i").stop(true,true).animate({
			 padding: '0px 25px 0 60px'	
		}, 600);

	});
	$(".nav ul li a").mouseleave(function() {
		$(this).find("i").stop(true,true).animate({
			padding: '0px 25px 0 25px'
		}, 600);

	});
	
})






////弹出层
//jQuery(document).ready(function($){
//	//open popup
//	$('.cd-popup-trigger').on('click', function(event){
//		event.preventDefault();
//		$('.cd-popup').addClass('is-visible');
//	});
//	
//	//close popup
//	$('.cd-popup').on('click', function(event){
//		if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
//			event.preventDefault();
//			$(this).removeClass('is-visible');
//		}else if( $(event.target).is('.bd-popup-open') || $(event.target).is('.cd-popup') ){
//			event.preventDefault();
//			$(this).removeClass('is-visible');
//			$('.bd-popup').addClass('is-visible');
//		}
//	});
//
//});

