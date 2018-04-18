function OpenTab(title, url, icon){
	 
	if($("#tabs").tabs('exists', title)){
		$("#tabs").tabs('select', title);
	}else{
		$("#tabs").tabs('add',{
			title: title,
			content: createTabContent(url),
			closable: true,
			icon: icon
		});
	}	
}

function createTabContent(url){
	return '<iframe style="width:100%;height:100%;" scrolling="auto" frameborder="0" src="' + url + '"></iframe>';
}

$(function(){

	$("#m-refresh").click(function(){
		var currTab = $('#tabs').tabs('getSelected');
		var url = $(currTab.panel('options').content).attr('src');
		currTitle = currTab.panel('options').title;	
		
		$('#tabs').tabs('update',{
			tab:currTab,
			options:{
				content: createTabContent(url)
			}
		})
	});
	
	$("#m-closeall").click(function(){
		$(".tabs li").each(function(i, n){
			var title = $(n).text();
			$('#tabs').tabs('close',title);	
		});
	});
	
	$("#m-closeother").click(function(){
		var currTab = $('#tabs').tabs('getSelected');
		currTitle = currTab.panel('options').title;	
		
		$(".tabs li").each(function(i, n){
			var title = $(n).text();
			
			if(currTitle != title){
				$('#tabs').tabs('close',title);			
			}
		});
	});
	
	$("#m-close").click(function(){
		var currTab = $('#tabs').tabs('getSelected');
		currTitle = currTab.panel('options').title;	
		$('#tabs').tabs('close', currTitle);
	});
});

