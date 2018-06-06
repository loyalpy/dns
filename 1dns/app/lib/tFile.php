<?php
/**
 * @class tFile
 * @brief tFile 文件处理类
 */
class tFile{
	private $resource = null; //文件资源句柄
	public static $except = array('.','..','.svn'); //无效文件或目录名
	/**
	 * @brief 构造函数，打开资源流，并独占锁定
	 * @param String $fileName 文件路径名
	 * @param String $mode     操作方式，默认为读操作，可供选择的项为：r,r+,w+,w+,a,a+
	 * @note $mod，'r'  只读方式打开，将文件指针指向文件头
	 *             'r+' 读写方式打开，将文件指针指向文件头
	 * 			   'w'  写入方式打开，将文件指针指向文件头并将文件大小截为零。如果文件不存在则尝试创建之。
	 * 			   'w+' 读写方式打开，将文件指针指向文件头并将文件大小截为零。如果文件不存在则尝试创建之。
	 * 			   'a'  写入方式打开，将文件指针指向文件末尾。如果文件不存在则尝试创建之。
	 * 			   'a+' 读写方式打开，将文件指针指向文件末尾。如果文件不存在则尝试创建之。
	 */
	function __construct($file_name,$mode='r'){
		$dir_name  = dirname($file_name);
		$base_name = basename($file_name);

		//检查并创建文件夹
		self::mkdir($dir_name);

		$this->resource = fopen($file_name,$mode.'b');
		if($this->resource){
			flock($this->resource,LOCK_EX);
		}
	}
    //文件读操作
	public function read(){
		$content = null;
		while(!feof($this->resource)){
			$content.= fread($this->resource,1024);
		}
		return $content;
	}
    //文件写入操作
	public function write($content){
		$worldsnum = fwrite($this->resource,$content);
		return is_bool($worldsnum) ? false : $worldsnum;
	}
    //清空目录下的所有文件
	public static function clear_dir($dir){
		if(!in_array($dir,self::$except) && is_dir($dir) && is_writable($dir)){
			$dir_res = opendir($dir);
			while($file_name = readdir($dir_res)){
				if(!in_array($file_name,self::$except)){
					$fullpath = $dir.'/'.$file_name;
					if(is_file($fullpath)){
						self::unlink($fullpath);
					}else{
						self::clear_dir($fullpath);
						rmdir($fullpath);
					}
				}
			}
			closedir($dir_res);
			return true;
		}else{
			return false;
		}
	}
    //获取文件信息
	public static function get_info($file_name){
		if(is_file($file_name))
			return stat($file_name);
		else
			return null;
	}
    //创建文件夹
	public static function mkdir($path,$chmod=0777){
		return is_dir($path) or (self::mkdir(dirname($path),$chmod) and mkdir($path,$chmod));
	}
    //复制文件
	public static function copy($from,$to,$mode = 'c'){
		if(is_file($from)){
			$dir = dirname($to);
			//创建目录
			self::mkdir($dir);
			copy($from,$to);
			if(is_file($to)){
				if($mode == 'x'){
					self::unlink($from);
				}
				return true;
			}else{
				return false;
			}
		}
		else
			return false;
	}
    //删除文件
	public static function unlink($file_name){
		if(is_file($file_name) && is_writable($file_name)){
			unlink($file_name);
			return true;
		}else
			return false;
	}
    //删除$dir文件夹 或者 其下所有文件
	public static function rmdir($dir,$recursive = false){
		if(is_dir($dir) && is_writable($dir)){
			//强制删除
			if($recursive == true){
				self::clear_dir($dir);
			}else{//非强制删除
				if(rmdir($dir))
					return true;
				else
					return false;
			}
		}
	}
    //获取文件类型
	public static function get_filetype($file_name){
		$filetype = null;
		if(!is_file($file_name)){
			return false;
		}

		$fileres = fopen($file_name,"rb");
	    if(!$fileres){
			return false;
		}
        $bin= fread($fileres, 2);
        fclose($fileres);
        if($bin != null){
        	$strinfo  = unpack("C2chars", $bin);
	        $typecode = intval($strinfo['chars1'].$strinfo['chars2']);
			$typelist = self::get_typelist();
			foreach($typelist as $val){
				if(strtolower($val[0]) == strtolower($typecode)){
					if($val[0] == 8075)	{
						return array('zip','docx','xlsx');
					}else{
						return $val[1];
					}
				}
			}
        }
		return $filetype;
	}
    // 获取文件类型映射关系
    public static function get_typelist(){
    	return array(
	    	array('255216','jpg'),
			array('13780','png'),
			array('7173','gif'),
			array('6677','bmp'),
			array('6063','xml'),
			array('60104','html'),
			array('208207','xls/doc'),
			array('8075','zip'),
			array('8075','docx'),
			array('8075','xlsx'),
			array("8297","rar"),
    	);
    }
    //获取文件大小
	public static function get_filesize($file_name){
		return is_file($file_name) ? filesize($file_name):null;
	}
    //检测文件夹是否为空
	public static function is_emptydir($dir){
		if(is_dir($dir)){
			$is_empty = true;
			$dir_res  = opendir($dir);
			while($file_name = readdir($dir_res)){
				if($file_name!='.' && $file_name!='..'){
					$is_empty = false;
					break;
				}
			}
			closedir($dir_res);
			return $is_empty;
		}
	}
    //释放文件锁定
	public function save(){
		flock($this->resource,LOCK_UN);
	}
    //获取文件扩展名
	public static function get_ext($file_name){
		if(substr($file_name,-6) == "tar.gz"){
			return "tar.gz";
		}else{
			$fileinfo_arr = pathinfo($file_name);
			return strtolower($fileinfo_arr['extension']);
		}
	}
    //获取文件名
    public static function get_filename($file_name){
    	return strtolower(basename($file_name,".".self::get_ext($file_name)));
    }
    //析构函数，释放文件连接句柄
	function __destruct(){
		if(is_resource($this->resource)){
			fclose($this->resource);
		}
	}
    //文件对拷贝
	public static function xcopy($source, $dest ,$oncemore = true){
		if(!file_exists($source)){
			return "error: $source is not exist!";
		}
		if(is_dir($source)){
			if(file_exists($dest) && !is_dir($dest)){
				return "error: $dest is not a dir!";
			}
			if(!file_exists($dest)){
				mkdir($dest,0777);
			}
			$od = opendir($source);
			while($one = readdir($od)){
				if(in_array($one,self::$except)){
					continue;
				}
				$result = self::xcopy($source.DIRECTORY_SEPARATOR.$one, $dest.DIRECTORY_SEPARATOR.$one, $oncemore);
				if($result !== true){
					return $result;
				}
			}
			closedir($od);
		}else{
			if(file_exists($dest) || is_dir($dest) ){
				if( func_num_args()>2 || $oncemore===true )	{
					return "error: $dest is a dir!";
				}
				$result = self::xcopy($source, $dest.DIRECTORY_SEPARATOR.basename($source), $oncemore);
				if( $result !== true )	{
					return $result;
				}
			}else{
				if(!file_exists(dirname($dest))) self::mkdir(dirname($dest));
				copy($source, $dest);
				chmod($dest,0777) and touch($dest, filemtime($source));
			}
		}
		return true;
	}
    //soket
	public static function socket($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 20, $block = TRUE)
	{
		$return = '';
		$matches = parse_url($url);
		!isset($matches['host']) && $matches['host'] = '';
		!isset($matches['path']) && $matches['path'] = '';
		!isset($matches['query']) && $matches['query'] = '';
		!isset($matches['port']) && $matches['port'] = '';
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		if($post){
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		}else{
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		if(!$fp){
			return '';
		}else{
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']){
				while (!feof($fp)){
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n"))break;
				}
				$stop = false;
				while(!feof($fp) && !$stop){
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit){
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			return $return;
		}
	}
	/**
	 * @brief 格式化文件尺寸
	 */
	public static function format_size($s,$u='B',$p=1){
		$us = array('B'=>'K','K'=>'M','M'=>'G','G'=>'T');
		return (($u!=='B')&&(!isset($us[$u]))||($s<1024))?(number_format($s,$p)."$u"):(self::format_size($s/1024,$us[$u],$p));
	}
	/**
	 * @brief 获取字节
	 */
	public static function get_byte_value($v){
		$v = trim($v);
		$l = strtolower($v[strlen($v) - 1]);
		switch($l){
		  case 'g':
			$v *= 1024;
	
		  case 'm':
			$v *= 1024;
	
		  case 'k':
			$v *= 1024;
		}
		return $v;
	}
}
?>
