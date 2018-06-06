<?php
/**
 * @brief 模块
*/
class tQrcode{
	private $cache_path      = 'static/cache/qrcode'; //默认文件缓存存放路径
	private $cache_ext       = '.png';         //默认文件缓存扩展名
	private $directory_level = 2;               //目录层级,基于$cache_path
	private $errorCorrectionLevel = "L";        // 纠错级别：L、M、Q、H
	private $matrixPointSize = 5;               // 点的大小：1到10
	//构造函数
	public function __construct(){
		require_once (ROOT_PATH.'lib/plugins/phpqrcode/phpqrcode.php');
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
	//读取二维码
	public function get($val){
		$key = md5($val);
		$filename = $this->get_filename($key);
		if(file_exists($filename)){
			return $filename;
		} else {
			if(!file_exists($dirname=dirname($filename))){
				tFile::mkdir($dirname);
			}
			QRcode::png($val, $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);
			if(file_exists($filename)){
				return $filename;
			} else {
				return "";
			}
		}
	}
	//删除缓存
	public function del($val){
		$key = md5($val);
		$filename = $this->get_filename($key);
		if(file_exists($filename)){
			return unlink($filename);
		}else{
			return true;
		}
	}
	//删除全部缓存
	public function flush(){
		return tFile::clear_dir($this->cache_path);
	}
}