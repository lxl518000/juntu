/*
 * IE Alert! jQuery plugin
 * version 1
 * author: David Nemes http://nmsdvid.com
 * http://nmsdvid.com/iealert/
 */

(function($){
$("#goon").live("click", function(){
		$("#ie-alert-overlay").hide();	
		$("#ie-alert-panel").hide();						  
});
function initialize($obj, support, title, text){

		var panel = "<span>"+ title +"</span>"
				  + "<p> "+ text +"</p>"
			      + "<div class='browser'>"
			      + "<ul>"
			      + "<li><a class='chrome' href='http://dlsw.baidu.com/sw-search-sp/soft/9d/14744/ChromeStandalone_49.0.2623.110_Setup.1459233395.exe' target='_blank'></a></li>"
			      + "<li><a class='firefox' href='http://www.mozilla.org/en-US/firefox/new/' target='_blank'></a></li>"
			      + "<li><a class='ie9' href='http://windows.microsoft.com/en-US/internet-explorer/downloads/ie/' target='_blank'></a></li>"
			      + "<li><a class='safari'  href='http://ie.sogou.com/' target='_blank'></a></li>"
			      + "<li><a class='opera'  href='http://chrome.360.cn/' target='_blank'></a></li>"
			      + "<ul>"
			      + "</div>"; 

		var overlay = $("<div id='ie-alert-overlay'></div>");
		var iepanel = $("<div id='ie-alert-panel'>"+ panel +"</div>");

		var docHeight = $(document).height();

		overlay.css("height", docHeight + "px");



			     
		
		if (support === "ie8") { 			// shows the alert msg in IE8, IE7, IE6
		
			if ($.browser.msie  && parseInt($.browser.version, 10) < 9) {
				
				$obj.prepend(iepanel);
				$obj.prepend(overlay);
				
			}

			if ($.browser.msie  && parseInt($.browser.version, 10) === 6) {

				
				$("#ie-alert-panel").css("background-position","-626px -116px");
				$obj.css("margin","0");
  
			}
			
			
		} else if (support === "ie7") { 	// shows the alert msg in IE7, IE6
			
			if ($.browser.msie  && parseInt($.browser.version, 10) < 8) {
				
				$obj.prepend(iepanel);
				$obj.prepend(overlay);
			}
			
			if ($.browser.msie  && parseInt($.browser.version, 10) === 6) {
				
				$("#ie-alert-panel").css("background-position","-626px -116px");
				$obj.css("margin","0");
  
			}
			
		} else if (support === "ie6") { 	// shows the alert msg only in IE6
			
			if ($.browser.msie  && parseInt($.browser.version, 10) < 7) {
				
				$obj.prepend(iepanel);
				$obj.prepend(overlay);
				
  				$("#ie-alert-panel").css("background-position","-626px -116px");
				$obj.css("margin","0");
				
			}
		}

}; //end initialize function


	$.fn.iealert = function(options){
		var defaults = { 
			support: "ie7",  // ie8 (ie6,ie7,ie8), ie7 (ie6,ie7), ie6 (ie6)
			title: "<span style='font-size:24px;color:red'>提示</span>", // title text
			text: "<span style='font-size:18px;line-height:26px;'>您正在使用的浏览器版本过低！请升级至IE9以上或下载下方推荐浏览器进行访问</span>"
		};
		
		
		var option = $.extend(defaults, options);

		
		

			return this.each(function(){
				if ( $.browser.msie ) {
					var $this = $(this);  
					initialize($this, option.support, option.title, option.text);
				} //if ie	
			});		       
	
	};
})(jQuery);
