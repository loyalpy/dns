<?php
class tCache{
	private static $cache     = null;    //缓存对象
	private static $cache_type = 'file';  //默认缓存类型
	private static $expire    = 2592000; //默认缓存过期时间,单位:秒,默认：1个月
	private static $timeout   = 0;       //默认缓存删除延迟时间,单位:秒
	public static function init(){
		//当前系统支持的缓存类型
		if(self::$cache === null){
			$caceh_type = App::get_conf("cfg.cache.type");
			$expire     = App::get_conf("cfg.cache.expire");
			$timeout    = App::get_conf("cfg.cache.timeout");
			
			self::$cache_type = $caceh_type?$caceh_type:self::$cache_type;
			self::$expire     = $expire?$expire:self::$expire;
		    self::$timeout    = $timeout?$timeout:self::$timeout;
			switch(self::$cache_type){
				case "memcache":
				self::$cache = new tMemCache();
				break;
				default:
				self::$cache = new tFileCache();
				break;
			}
		}
	}
    //设置
	public static function set($key,$data,$expire = ''){
		self::init();
		if($expire === ''){
			$expire = self::$expire;
		}
		$data = serialize($data);
		return self::$cache->set($key,$data,$expire);
	}
	//获取
	public static function get($key){
		self::init();
		$data = self::$cache->get($key);
		if($data){
			return unserialize($data);
		}else{
			return null;
		}
	}
	//删除
	public static function del($key,$timeout = ''){
		self::init();
		if($timeout === ''){
			$timeout = self::$timeout;
		}
		return self::$cache->del($key,$timeout);
	}
    //清空
	public static function flush(){
		self::init();
		return self::$cache->flush();
	}
	//生成 静态HTML 文件
	public static function write_static($cache_name,$data){
		$phpcache_name = ROOT_PATH."cache/static/{$cache_name}.html";
		if(!file_exists($dirname=dirname($phpcache_name))){
			tFile::mkdir($dirname);
		}
	    $content = $data;
	    return file_put_contents($phpcache_name, $content, LOCK_EX);
	}
	//生成 PHP文件
	public static function write($cache_name,$data){
		$phpcache_name = ROOT_PATH."cache/static/{$cache_name}.php";
		if(!file_exists($dirname=dirname($phpcache_name))){
			tFile::mkdir($dirname);
		}
	    $content = "<?php\r\n";
	    $content .= "return " . var_export($data, true) . ";\r\n";
	    $content .= "?>";
	    return file_put_contents($phpcache_name, $content, LOCK_EX);
	}
	//获取 PHP文件
	public static function read($cache_name){
		 $phpcache_name = ROOT_PATH."cache/static/{$cache_name}.php";
		 if (file_exists($phpcache_name)){
             return require($phpcache_name);
		 }else{
		     return false;
		 }
	}
	//删除 PHP文件
	public static function delete($cache_name){
		 $phpcache_name = ROOT_PATH."cache/static/{$cache_name}.php";
		 return tFile::unlink($phpcache_name);
	}
}