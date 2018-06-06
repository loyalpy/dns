<?php
class tTime{
	private static $st = 0;
	private static $et = 0;
	/**
	 * @brief 获取当前时间
	 * @param String  $format  返回的时间格式，默认返回当前时间的时间戳
	 * @return String $time    时间
	 */
	public static function get_now($format=''){
		if(!$format){
			return self::get_time();
		}else{
			return self::get_datetime($format);
		}
	}

	/**
	 * @brief  根据指定的格式输出时间
	 * @param  String  $format 格式为年-月-日 时:分：秒,如‘Y-m-d H:i:s’
	 * @param  String  $time   输入的时间
	 * @return String  $time   时间
	 */
	public static function get_datetime($format='',$time=''){
		$time   = !empty($time)  ? $time  : time();
		$format = !empty($format)? $format: 'Y-m-d H:i:s';
		return date($format,$time);
	}

	/**
	 * @brief  根据输入的时间返回时间戳
	 * @param  $time String 输入的时间，格式为年-月-日 时:分：秒,如2010-01-01 00:00:00
	 * @return $time Int 指定时间的时间戳
	 */
	public static function get_time($time=''){
		if($time){
			return $time = strtotime($time);
		}else{
			return $time = time();
		}
	}

	/**
	 * @brief 获取第一个时间与第二个时间之间相差的秒数
	 * @param $first_time  String 第一个时间 格式为英文时间格式，如2010-01-01 00:00:00
	 * @param $second_time String 第二个时间 格式为英文时间格式，如2010-01-01 00:00:00
	 * @return $difference Int 时间差，单位是秒
	 * @note  如果第一个时间早于第二个时间，则会返回负数
	 */
	public static function get_diffsec($first_time,$second_time=''){
		$second_time = !empty($second_time) ? $second_time : self::get_datetime();
		$difference  = strtotime($first_time) - strtotime($second_time);
		return $difference;
	}
	/**
	 * @brief 格式化时间
	 */
	/* 格式化时间 */
	public static function format_dateline($format, $time){
        $timestamp = self::get_now();
		$lang = array(
			'format_dateline_0' => '刚刚',
			'format_dateline_1' => '秒前',
			'format_dateline_2' => '分钟前',
			'format_dateline_3' => '小时前',
			'format_dateline_4' => '昨天',
			'format_dateline_5' => '前天',
			'format_dateline_6' => '天前'
		);
		$s = $timestamp - $time;
		if($s < 0){
			return $lang['format_dateline_0'];
		}
		if($s < 60){
			return $s.$lang['format_dateline_1'];
		}
		$m = $s / 60;
		if($m < 60){
			return floor($m).$lang['format_dateline_2'];
		}
		$h = $m / 60;
		if($h < 24){
			return floor($h).$lang['format_dateline_3'];
		}
		$d = $h / 24;
		if($d < 2){
			return $lang['format_dateline_4'].date("H:i", $time);
		}
		if($d <3){
			return $lang['format_dateline_5'].date("H:i", $time);
		}
		if($d <= 30){
			return floor($d).$lang['format_dateline_6'];
		}
		return self::get_datetime($format, $time);
	}
	/**
	 * @brief 
	 */
	public static function format_distime($s){
		if($s < 0){
			return "0秒";
		}
		if($s < 60){
			return $s."秒";
		}
		$m = $s / 60;
		if($m < 60){
			return floor($m)."分钟";
		}
		$h = $m / 60;
		$f = $m % 60; 
		if($h < 24){
			return floor($h)."小时".floor($f)."分钟";
		}
		$d = $h / 24;
		$f = $h % 24;
		if($d < 365){
			return floor($d)."天".floor($f)."小时";
		}
		$y = $d/365;
		$i = $d%365;
		return floor($y)."年".floor($i)."天";
		
	}
	/**
	 * 获取当月有多少天
	 */
	public static function get_month_day($month, $year){
		switch ($month) {
			case 4 :
			case 6 :
			case 9 :
			case 11 :
				$days = 30;
				break;
			case 2 :
				if ($year % 4 == 0) {
					if ($year % 100 == 0) {
						$days = $year % 400 == 0 ? 29 : 28;
					} else {
						$days = 29;
					}
				} else {
					$days = 28;
				}
				break;
			default :
				 $days = 31;
				 break;    
		}    
		return $days;
	}
	/**
	 * 获取微秒
	 */
	public static function get_microtime(){
		$mtime = explode(' ', microtime());
		return (float)$mtime[1] + (float)$mtime[0];
	}
	/**
	 * 获取星期
	 */
	public static function get_wday($time,$pre="周",$html=true){
		 $xq = array(($html?"<font class='font-red'>日</font>":"日"),"一","二","三","四","五",($html?"<font class='font-red'>六</font>":"六"));
    	 return $pre.$xq[date("w",$time)];
	}
	//测试程序执行时间
	public static function start(){//获取开始时间
		 self::$st = self::get_microtime();
		 return self::$st;
	}
	public static function stop(){//获取结束时间
		 self::$et = self::get_microtime();
		 return self::$et;
	}
	public static function end(){//计算程序持续时间
		 self::$et = self::get_microtime();
		 return round((self::$et - self::$st), 7);//获取秒数
	}
} 
?>
