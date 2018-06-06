<?php
/**
 * @class tFileLog
 * @brief 文本格式日志处理类
 */
class tFileLog implements tILog{
	//默认文件日志存放目录
	private $path = '';
	/**
	 * @brief 文件日志类的构造函数
	 */
	function __construct($path = ''){
		$this->path = $path;
	}
	/**
	 * @brief  写日志
	 * @param  array  $content loginfo数组
	 * @return bool   操作结果
	 */
	public function write($logs = array()){
		if(!is_array($logs) || empty($logs)){
			throw new tException('the $logs parms must be array');
		}

		if($this->path == ''){
			throw new tException('the file path is undefined');
		}
		$content = join("\t",$logs)."\t\r\n";
		//生成路径
		$file_name = ROOT_PATH.$this->path;
		if(!file_exists($dirname = dirname($file_name))){
			tFile::mkdir($dirname);
		}
		$result = error_log($content, 3 ,$file_name);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * @brief  设置路径
	 * @param  String $path 设置日志文件路径
	 */
	public function set_path($path){
		$this->path = $path;
	}
}