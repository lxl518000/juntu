/***** 充值 *****/

jQuery(function(){
	// 折叠展开
	function show(attr1,attr2){
		jQuery(attr1).click(function(){
		
			jQuery(attr2).toggle(function(){
				jQuery(this).animate({'display':'block'},500);
			},function(){
				jQuery(this).animate({'display':'none'},500)
			})
			
		})
	}

	show('.gamePay #show', '.bankMore');
	show('.accountPay #show', '.bankMore');

   	jQuery('a#show').click(function(){
		if(this.className == 'show'){
			jQuery(this).removeClass('show').addClass('hide')
		}else if(this.className == 'hide'){
			jQuery(this).removeClass('hide').addClass('show')
		}
	})



	// TAB切换
	var btns = jQuery('.pay-title a,.Account-title a')
	var cont = jQuery('#pay-cont,#Account-cont').children('ul,.con-bc')
	for(var i = 0; i < btns.length; i++){
		btns[i].id = i;
		btns[i].onclick = function(){
			for(var j = 0; j < cont.length; j++){
				btns[j].className = ' ';
				cont[j].style.display = 'none';
			}
			this.className = 'on';
			cont[this.id].style.display = 'block';
		}
	}

})





