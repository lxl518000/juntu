/*JS FUNCTIONS DOCUMENT
 *author:cyy
 *qq:419738633
 * */


(function(){
	
	/*瀑布流方法*/
	function waterFallFlow(opts){
		if(!(opts instanceof Object)) var opts={};
		var oneCell = opts.oneCell || ".waterfallflow-list li",
			colspan = opts.colspan || 4,
			marginX = opts.marginX || 15,
			marginY = opts.marginY || 15;
			
		return this.each(function(){
			
			var $wrap = $(this),
				$tar = $(oneCell,$wrap),
				$parent = $tar.parent(),
				$imgs = $tar.find("img");
			var exec = function(){
				$parent.css({paddingRight:marginX});
				var parent_pos = $parent.css("position");
				if(parent_pos != "absolute" && (parent_pos != "relative") && (parent_pos != "fixed")){
					$parent.css({position:"relative"});
				}
				var parent_w = $parent.width(),
					li_w = parent_w/colspan-marginX,
					allh_arr = [],lineh_arr = [],h_min,h_max,n;
				$tar.css({width:li_w});
				for(var i=0;i<$tar.length;i++){
					allh_arr[i] = $tar.eq(i).outerHeight();
					if(i <= colspan-1){
						lineh_arr.push(allh_arr[i]+marginY);
						$tar.eq(i).css({position:"absolute",top:marginY,left:li_w*i+marginX*(i+1)});
					}else{
						h_min = Math.min.apply(null,lineh_arr);
						/*indexOf方法兼容IE9以下*/
						if(!Array.indexOf){
						    Array.prototype.indexOf = function(el){
								for(var i=0,n=this.length; i<n; i++){
								  	if (this[i] === el) return i;
								}
								return -1;
							} 
						}
						n = lineh_arr.indexOf(h_min);
						lineh_arr[n] += allh_arr[i] + marginY;
						$tar.eq(i).css({position:"absolute",top:h_min+marginY,left:li_w*n+marginX*(n+1)});	
					}
				}
				h_max = Math.max.apply(null,lineh_arr);
				$tar.parent().css({height:h_max+marginY});
			}
			exec();
			$imgs.on("load",function(){
				exec();
			})
		})
	}
	$.fn.waterFallFlow = waterFallFlow;
	
	
	function pointGrade(evtFlag,tipFlag){
		var tipFlag = (tipFlag === true) ? true :false,
			evtFlag = (evtFlag === false) ? false :true;
		return this.each(function(){
			var $this = $(this),
				$cur = $this.children(),
				point_arr = [1,2,3,4,5],
				point = 0,
				cur_point = 0;
			var $tip = $(this).next();
			if(tipFlag){
				if($(".point-tip",$this.parent()).length === 0){
					$tip = $("<span class='point-tip'></span>");
					$tip.css({marginLeft:"5px"}).insertAfter($this);
				}
			}
			var pointShow = function(point){
				var tip = "";
				switch(point){
					case 1: 
						tip = "1分 非常不满意";
						break;
					case 2: 
						tip = "2分 不满意";
						break;
					case 3: 
						tip = "3分 一般";
						break;
					case 4: 
						tip = "4分 满意";
						break;
					case 5: 
						tip = "5分 非常满意";
						break;
					default:
					    tip = "尚未评分";
				}
				$tip.text(tip);
				$cur.css({width:point*20+"%"});
			};
			/*初始化*/
			var int = function(){
				point = parseInt($this.attr("data-point"));
				if($.inArray(point,point_arr) >= 0){
					pointShow(point);
				}else{
					pointShow(0);
				}
			};
			int();
			if(evtFlag){
				$this.attr("data-point",0);
				int();
				$this.on("mousemove",function(evt){
					var this_w = $this.innerWidth(),
						this_l = $this.offset().left;
					cur_pos = evt.pageX - this_l;
					cur_point = Math.ceil(cur_pos*5/this_w);
					pointShow(cur_point);
				});
				$this.on("mouseleave",function(evt){
					int();
				});
				$this.on("click",function(){
					point = cur_point;
					pointShow(point);
					$this.attr("data-point",point);
					_selected = true;
				});
			}
		});
	}
	$.fn.pointGrade = pointGrade;
	
	/*调整图片样式，置于外层元素的中心*/
	function imgCenter(){
		/*参数为目标图片，使用时注意外层应为块级元素且有高度*/
		return this.each(function(){
			var $tar = $(this);
			var exec = function(){
				var $this = $tar.parent();
				$img = $tar;
				//回复图片原始宽高（防止其他js干扰）
				$img.css({height:"auto",width:"auto"});
				/*先调整宽度，再调整高度*/
				var wrap_w = $this.width(),
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
			};
			$tar.on("load",function(){
				exec();
			})
		});
	}
	$.fn.imgCenter = imgCenter;
	
	
	
}(jQuery));



(function(){
	//剩余时间函数
	/*参数：截止日期字符串 "格式：2015-03-10 12:00:00"，返回一个对象，属性：剩余总毫秒数，剩余天数，剩余小时数，剩余分钟数，剩余秒数*/
	function surplusTime(endTime){
		var cur_time = Date.now() || +new Date(),
			endTime = endTime || cur_time,
			descTime = {};
		
		var endtimearr = endTime.replace(/[^\d]+/g,",").split(",");
		var endtime_y = endtimearr[0],
			endtime_m = endtimearr[1]-1,
			endtime_r = endtimearr[2],
			endtime_h = endtimearr[3],
			endtime_min = endtimearr[4],
			endtime_s = endtimearr[5];
		
		var end_time = new Date(endtime_y,endtime_m,endtime_r,endtime_h,endtime_min,endtime_s),surplus,surplus_allsecond;//2014-11-11 10:00:00
		end_time = end_time.getTime();
		surplus = end_time - cur_time;
		surplus_allsecond = Math.floor(surplus/1000);
		var formatTime = function(time,num){
			var result = "";
			switch(num){
				case 2 :
					if(time < 10){
						result = "0" + time;
					}else{
						result = time + "";
					}
					break;
				case 3 :
					if(time < 100 && (time > 10)){
						result = "0" + time;
					}else if(time < 10){
						result = "00" + time;
					}else{
						result = ""+time;
					}
					break;
				default :
					result = time + "";
					break;
			}
			return result;
		};
		var surplus_day,surplus_hour,surplus_minute,surplus_second;
		if(surplus_allsecond > 0){
			surplus_day = formatTime(Math.floor(surplus_allsecond/(60*60*24)));
			surplus_hour = formatTime(Math.floor(surplus_allsecond%(60*60*24)/(60*60)),2);
			surplus_minute = formatTime(Math.floor(surplus_allsecond%(60*60*24)%(60*60)/60),2);
			surplus_second = formatTime(Math.floor(surplus_allsecond%(60*60*24)%(60*60)%60),2);
			surplus_millisecond = formatTime(surplus-Math.floor(surplus/1000)*1000,3);
		}else{
			surplus_day = formatTime(0);
			surplus_hour = formatTime(0,2);
			surplus_minute = formatTime(0,2);
			surplus_second = formatTime(0,2);
			surplus_millisecond = formatTime(0,3);
		}
		
		return descTime = {
			surplus : surplus,
			day : surplus_day,
			hour : surplus_hour,
			minute : surplus_minute,
			second : surplus_second,
			millisecond : surplus_millisecond
		};
	}
	window.surplusTime = surplusTime;
}());






















