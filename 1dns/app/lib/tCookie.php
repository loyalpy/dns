<?php
class tCookie{
	//cookie前缀
	private static $pre        = 't_';
	//默认cookie密钥
	private static $default_key = 'thinkhu';
	//获取配置的前缀
	private static function get_pre(){
		$pre = App::get_conf("cfg.safe_pre");
		return $pre?$pre:self::$pre;
	}
	//获取当前的域
	private static function get_domain(){
		$domain = App::get_conf('cfg.cookie_domain');
		return $domain?$domain:null;
	}
    /**
     * @brief 设置cookie的方法
     * @param string $name 字段名
     * @param string $value 对应的值
     * @param string $time 有效时间
     * @param string $path 工作路径
     * @param string $domain 作用域
     */
	public static function set($name="",$value='',$time='3600',$path='/',$domain=null){
		$pre    = self::get_pre();
		$key    = self::get_key();
		$domain = self::get_domain();

		if($name){
			if($time <= 0) $time = -100;
			else $time = time() + $time;
			
			if(is_array($value) || is_object($value)) $value=serialize($value);
			$value = tCode::encode($value , $key);
			setCookie($pre.$name,$value,$time,$path,$domain);
		}

		$sid = self::cookie_id();
		@setCookie($pre.'sid',  tCode::encode($sid, $key),time()+86400,$path,$domain);
	}
    /**
     * @brief 取得cookie字段值的方法
     * @param string $name 字段名
     * @return mixed 对应的值
     */
	public static function get($name){
		$pre        = self::get_pre();
		$key        = self::get_key();

		if(isset($_COOKIE[$pre.$name])){
			$cookie = tCode::decode($_COOKIE[$pre.$name],$key);
			$tem    = substr($cookie,0,10);
			if(preg_match('/^[Oa]:\d+:.*/',$tem)) return unserialize($cookie);
			else return $cookie;
		}
		return null;
	}

    /**
     * @brief 清除cookie值的方法
     * @param string $name 字段名
     */
	public static function uset($name)	{
		self::set($name,'',0);
	}
    /**
     * @brief 清除所有的cookie数据
     */
	public static function destroy($exp = array()){
		$pre     = self::get_pre();
		$pre_len = strlen($pre);

		foreach($_COOKIE as $name => $val){
			$nopre_name = substr($name,$pre_len);
			if(strpos($name,$pre) === 0 && !in_array($nopre_name,$exp)){
				self::uset($nopre_name);
			}
		}
	}
	/**
	 * @brief 取得密钥
	 * @return string 返回密钥值
	 */
	private static function get_key(){
		$encrypt_key = App::get_conf("cfg.encrypt_key");
		$encrypt_key = $encrypt_key?$encrypt_key:self::$default_key;
		return $encrypt_key;
	}
    /**
     * @brief 取得cookie的安全码
     * @return String cookie的安全码
     */
	public static function cookie_id(){
		$sid    = self::get("sid");
		if(empty($sid)){
			$sid = tSafe::unique_id();
		}
		return $sid;
	}
}
?>
