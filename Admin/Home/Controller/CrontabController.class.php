<?php
/**
 * 定时任务控制器
 * @author Administrator
 *
 */
namespace Home\Controller;
use Think\Controller;
class CrontabController extends Controller {
	
	/**
	 * 定时任务更新网吧状态 拉取通话记录
	 * 
	 * 	 * /30 * * * * /www/service/crontab/syncCall.php 
	 * @see 计划任务 每半小时执行一次
	 * 
	 */
	public function syncCall(){
		service('Crontab')->syncCall();
		echo 'success';
	}
	
	/**
	 * 定时任务 自动派发新安装回访
	 * 0 6 * * *  /www/service/crontab/autonewBar.php
	 * @see 计划任务 每天早上6点钟执行一次 获取截止当前 刚好是新装5天的网吧 派发装装回访
	 */
	public function autoNewSheet(){
		service('AutoSheet')->autoNewSheet();
		echo 'success';
	}

	
	/**
	 * 定时任务  擅停回访
	 * 0 5 * * *  /www/service/crontab/autostopBar.php
	 * @see 计划任务 每天早上6点钟执行一次 获取截止当前 刚好是新装3天的网吧 派发装装回访
	 */
	public function autoStopSheet(){
		service('AutoSheet')->autoStopSheet();
		echo 'success';
	}
	
	/**
	 * 定时任务 同步微信联系人和微信建议反馈 每6小时同步一次
	 * 0 * /6 * * *
	 */
	public function syncWeixin(){
		service('AutoSheet')->autoWeixin();
		echo 'success';
	}
	
	
	
	
	
}