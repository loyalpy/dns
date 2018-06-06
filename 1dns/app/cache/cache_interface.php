<?php
//缓存接口
interface tCacheInterface{
	public function set($key,$data,$expire = '');
	public function get($key);
	public function del($key,$timeout = '');
	public function flush();
}