<?php
//开户session
session_start();
/**
 * @brief tSession 处理类
 * @class tSession
 * @note
 */
class tSession{
	//session前缀
	private static $pre='t_';
	//获取配置的前缀
	private static function get_pre(){
		$pre = App::get_conf("cfg.safe_pre");
		return $pre?$pre:self::$pre;
	}
	/**
	 * @brief 设置session数据
	 * @param string $name 字段名
	 * @param mixed $value 对应字段值
	 */
	public static function set($name="",$value=''){
		$pre = self::get_pre();
		$sid = self::session_id();

        if($name){
        	$_SESSION[$pre.$name] = $value;
        }		
		$_SESSION[$pre.'sid'] = $sid;
	}
    /**
     * @brief 获取session数据
     * @param string $name 字段名
     * @return mixed 对应字段值
     */
	public static function get($name){
		$pre        = self::get_pre();
		return isset($_SESSION[$pre.$name])?$_SESSION[$pre.$name]:null;
	}
    /**
     * @brief 清空某一个Session
     * @param mixed $name 字段名
     */
	public static function uset($name){
		self::$pre = self::get_pre();
		unset($_SESSION[self::$pre.$name]);
	}
    /**
     * @brief 清空所有Session
     */
	public static function destroy(){
		return session_destroy();
	}
    /**
     * @brief 得到session安全码
     * @return String  session安全码
     */
	public static function session_id(){
		$sid = self::get("sid");
		if(empty($sid)){
			$sid = tSafe::unique_id();
		}
		return $sid;
	}
}
?>
