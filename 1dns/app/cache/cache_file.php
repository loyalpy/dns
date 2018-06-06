<?php
//文件级缓存类
class tFileCache implements tCacheInterface{
	private $cache_path      = 'cache/temp/'; //默认文件缓存存放路径
	private $cache_ext       = '.data';         //默认文件缓存扩展名
	private $directory_level = 2;               //目录层级,基于$cache_path
	//构造函数
	public function __construct(){
		$cache_path = App::get_conf("cfg.cache.path");
		$ext =  App::get_conf("cfg.cache.ext");
		$this->cache_path = ROOT_PATH.($cache_path?$cache_path:$this->cache_path);
		$this->cache_ext  = $ext?$ext:$this->cache_ext;
	}
	//根据key值计算缓存文件名
	private function get_filename($key){
		$key      = str_replace(' ','',$key);
		$cache_dir = rtrim($this->cache_path,'\\/').'/';
		if($this->directory_level > 0){
			$hash      = abs(crc32($key));
			$cache_dir .= $hash % 1024;
			for($i = 1;$i < $this->directory_level;++$i){
				if(($prefix = substr($hash,$i,2)) !== false){
					$cache_dir .= '/'.$prefix;
				}
			}
		}
		return $cache_dir.'/'.md5($key).$this->cache_ext;
	}
	//设置缓存
	public function set($key,$data,$expire = ''){
		$filename = $this->get_filename($key);
		if(!file_exists($dirname=dirname($filename))){
			tFile::mkdir($dirname);
		}
		$write_len = file_put_contents($filename,$data);
		if($write_len == 0){
			return false;
		}else{
			chmod($filename,0777);
			$expire = time() + $expire;
			touch($filename,$expire);
			return true;
		}
	}
    //读取缓存
	public function get($key){
		$filename = $this->get_filename($key);
		if(file_exists($filename)){
			if(time() > filemtime($filename)){
				$this->del($key,0);
				return null;
			}else{
				return file_get_contents($filename);
			}
		}else{
			return null;
		}
	}
    //删除缓存
	public function del($key,$timeout = ''){
		$filename = $this->get_filename($key);
		if(file_exists($filename)){
			if($timeout > 0){
				$timeout = time() + $timeout;
				return touch($filename,$timeout);
			}else{
				return unlink($filename);
			}
		}else{
			return true;
		}
	}
    //删除全部缓存
	public function flush(){
		return tFile::clear_dir($this->cache_path);
	}
}