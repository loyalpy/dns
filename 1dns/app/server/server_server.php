<?php
/**
 * @class tServer
 * @brief 获取服务器环境的信息
 */
class tServer{
	//获取服务器主机
	public static function get_host(){
		return isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] :$_SERVER['SERVER_NAME']);
	}
	//获取服务器端口
	public static function get_port(){
		return $_SERVER['SERVER_PORT'];
	}
	//获取服务器版本
	public static function is_geversion($version){
		//获取当前php版本号
		$loc_version = phpversion();
		$result=version_compare($loc_version,$version);
		return ($result >= 0) ? true : false;
	}
}



?>
