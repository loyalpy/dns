<?php
//安全处理
class tSafe{
	/**
	 * @brief 设置数据
	 * @param string $key  键名;
	 * @param mixed  $val  值;
	 * @param string $type 安全方式:cookie or session;
	 */
	public static function set($key="",$val="",$type = ''){
		$class_name = self::get_safeclass($type);
		call_user_func(array($class_name, 'set'),$key,$val);
	}
	/**
	 * @brief 获取数据
	 * @param string $key  要获取数据的键名
	 * @param string $type 安全方式:cookie or session;
	 * @return mixed 键名为$key的值;
	 */
	public static function get($key,$type = ''){
		$class_name = self::get_safeclass($type);
		$value = call_user_func(array($class_name, 'get'),$key);
		//cookie续写
		if($value != null && $class_name == 'tCookie'){
			self::set($key,$value);
		}
		return $value;
	}
	/**
	 * @brief 清除safe数据
	 * @param string $name 要删除的键值
	 * @param string $type 安全方式:cookie or session;
	 */
	public static function uset($name = null,$type = ''){
		$class_name = self::get_safeclass($type);
		call_user_func(array($class_name, 'uset'),$name);
	}
   /**
	 * @brief 获取session_id或者cookie id
	 */
	public static function get_id($type=''){
		$class_name = self::get_safeclass($type);
		if($class_name == 'tCookie'){
			$sid = call_user_func(array($class_name, 'cookie_id'));
		}elseif($class_name == 'tSession'){
			$sid = call_user_func(array($class_name, 'session_id'));
		}else{
			$sid = "";
		}
		return $sid;
	}
	/**
	 * @brief 清除所有的cookie或者session数据
	 * @param string $type 安全方式:cookie or session;
	 */
	public static function destroy($type = '')	{
		$class_name = self::get_safeclass($type);
		call_user_func(array($class_name, 'destroy'));
	}
	/**
	 * @brief 获取cookie或者session对象
	 * @param  string $type 安全方式:cookie or session;
	 * @return object cookie或者session操作对象
	 */
	public static function get_safeclass($type = ''){
		$mapping_conf = array('cookie'=>'tCookie','session'=>'tSession');
		$cfg_type = App::get_conf("cfg.safe");
		if($type != '' && isset($mapping_conf[$type])){
			return $mapping_conf[$type];
		}else if($cfg_type == 'session'){
			return $mapping_conf['session'];
		}else{
			return $mapping_conf['cookie'];
		}
	}
	//创建唯一标识
	public static function unique_id() {   
    	$data = "";
    	$data .= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"";
    	$data .= isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"";  
    	$data .= time() . rand();   
    	return sha1($data);
	}  
}
?>