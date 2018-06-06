<?php

class tMemCache implements tCacheInterface{
	private $cache       = null;        //缓存对象
	private $default_host = '127.0.0.1:11211'; //默认服务器地址
	private $pre = "";
	//构造函数
	public function __construct(){
		if(!extension_loaded('memcache')){
			throw new tHttpException('can not find the memcache extension',403);
			exit;
		}
		$this->cache = new Memcache;
		$server  = App::get_conf("db.memcache");
		$mem_pre = App::get_conf("cfg.cache.pre");
		if(is_array($server)){
			foreach($server as $key => $val){
				$this->add_server($val);
			}
		}else{
			$this->add_server($server);
		}
		$this->pre = $mem_pre?$mem_pre: "";
	}
    //添加服务器到连接池
	private function add_server($address){
		$addressArray = explode(':',$address);
		$host         = $addressArray[0];
		$port         = isset($addressArray[1]) ? $addressArray[1] : 11211;
		return $this->cache->addServer($host,$port);
	}
    //写入缓存
	public function set($key,$data,$expire = ''){
		return $this->cache->set($this->pre.$key,$data,MEMCACHE_COMPRESSED,$expire);
	}
    //读取缓存
	public function get($key){
		return $this->cache->get($this->pre.$key);
	}
    //删除缓存
	public function del($key,$timeout = ''){
		return $this->cache->delete($this->pre.$key,$timeout);
	}
    //删除全部缓存
	public function flush(){
		return $this->cache->flush();
	}
}
