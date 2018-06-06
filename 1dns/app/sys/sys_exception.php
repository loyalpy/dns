<?php
class tException {
	private static $log_path = false;
	private static $debug_mode = false;
	//直接输出异常信息，不用catch
	public static function php_exception($e){
		if($e instanceof tHttpException){
			$e->show();
		}elseif($e instanceof Exception){
			$trace = $e->getTrace();
			$trace = array_reverse($trace);
			$class    = isset($trace[0]['class'])?$trace[0]['class']:'';
			$function = isset($trace[0]['function'])?$trace[0]['function']:'';
			
			$error['message']   = $e->getMessage();
			$error['type']      = get_class($e);;				
			$error['file']      = $trace[0]['file'];
			$error['line']      = $trace[0]['line'];
			$error['content']   = ' ['.$error['type'].']' . $error['message'].' in '.$error['file'].' on line '.$error['line'];
			$error['trace'] = "";
			foreach ((array)$trace as $k => $v) {
				array_walk($v['args'], function (&$item, $key) {
					$item = str_replace("\n", "", var_export($item, true));
				});
				if(isset($v['file']))
					$error['trace'] .= '#' . $k . ' ' . $v['file'] . '(' . $v['line'] . '): ' .
					(isset($v['class']) ? $v['class'] . '->' : '') . $v['function'] . '(' . implode(', ', $v['args']) . ')' . "\n";
			}
			//分析出错文件内容
			$file_content       = self::get_content_fileline_error($error['file'], $error['line']);
			$file_content       = highlight_string("<?php \n". $file_content . "...*/", true);
			$error['detail']    = $file_content;
			//出错时变量
			$error['vars']      = array();
			self::show_error($error);
		}else{
			App::end(0);
		}
	}
	//错误信息
	public static function php_error($errno,$errstr,$errfile=false,$errline=false,$errcontext=false){
		$trace    = debug_backtrace();
		$class    = isset($trace[0]['class'])?$trace[0]['class']:'';
		$function = isset($trace[0]['function'])?$trace[0]['function']:'';			
		
		$error['type']      = self::get_friend_errortype($errno);			
		$error['file']      = $errfile;
		$error['line']      = $errline;
		$error['message']   = $errstr;
		$error['content']   = ' ['.$error['type'].']' . $error['message'].' in '.$error['file'].' on line '.$error['line'];
		
		//追踪
		$error['trace']     = "";
		/*foreach ((array)$trace as $k => $v) {
			array_walk($v['args'], function (&$item, $key) {
				$item = str_replace("\n", "", var_export($item, true));
			});
			if(isset($v['file']))
				$error['trace'] .= '#' . $k . ' ' . $v['file'] . '(' . $v['line'] . '): ' .
				(isset($v['class']) ? $v['class'] . '->' : '') . $v['function'] . '(' . implode(', ', $v['args']) . ')' . "\n";
		}	*/	
		//分析出错文件内容
		$file_content       = self::get_content_fileline_error($error['file'], $error['line']);
		$file_content       = highlight_string("<?php \n". $file_content . "...*/", true);
		$error['detail']    = $file_content;
		//出错时变量
		$error['vars']      = array();//$errcontext;
		self::show_error($error);
	}
	//格式化显示致命错误
	public static function fatal_error(){
		$error = error_get_last();
		if ($error !== NULL){
		   	$error['type']      = isset($error['type'])?$error['type']:"";
		   	$error['file']      = $error['file'];
		   	$error['line']      = $error['line'];
		   	$error['message']   = $error['message'];
		   	$error['content']   = ' ['.$error['type'].']' . $error['message'].' in '.$error['file'].' on line '.$error['line'];
		   	//$error['trace']     = "";
		   	//分析出错文件内容
		   	$file_content       = self::get_content_fileline_error($error['file'], $error['line']);
		   	$file_content       = highlight_string("<?php \n". $file_content . "...*/", true);
		   	$error['detail']    = $file_content;
		   	//出错时变量
		   	$error['vars']      = array();
		   	self::show_error($error);	    	
		}
	}
	//打印显示显示错误
	public static function show_error($e = array()){
		$error = $e;
		if(self::$debug_mode ){
			if(App::get_conf("cfg.cli")){
				echo $error['content'];
			}else{
				ob_clean();
				include(ROOT_PATH."app/tpl/exception.tpl");
			}
			exit;
		}else{
			self::write_log($error['content']);
		}
	}
	//设置错误日志路径
	public static function set_logpath($path){
		self::$log_path = $path;
	}
	//设置调试模式
	public static function set_debugmode($mode){
		self::$debug_mode = $mode;
	}
	//直接写错误日志
	public static function write_log($str){
		if( self::$log_path)	{
			$logfile = new tFile(self::$log_path,"a");
			$str = $str."[".rand(0,99999)."]\r\n";
			$logfile->write($str);
		}
	}
	//过滤显示路径
	public static function path_filter($path){
		return str_replace(ROOT_PATH , "ROOT_PATH".DIRECTORY_SEPARATOR,$path);
	}
	//获取错误文件内容
	public static function get_content_fileline_error($filepath, $line, $include_linenumbers = true) {
		if(!is_file($filepath)) return "";
		$file_content = file($filepath);
		$file_content[$line-1] = rtrim($file_content[$line-1]) . " /*the problem is here!!!*/\n";
		$file_content = array_slice($file_content, ($line - 5), 10);
		$k = $line - 5;
		foreach ($file_content as $key => $line_content) {
			$file_content[$key] = str_replace("\n", ' ', $file_content[$key]);
			if ($include_linenumbers) {
				$k++;
				if ($k == $line ) {
					$file_content[$key] = sprintf("%s:\t%s", $k, $line_content);
				} else {
					$file_content[$key] = sprintf("%s:\t%s", $k, $line_content);
				}
			} else {
				$file_content[$key] = sprintf("%s", $line_content);
			}
		}
	
		return implode("", $file_content);
	}
	//显示错误级别
	public static function get_friend_errortype($type){
		switch($type) {
			case E_ERROR: // 1 //
				return 'E_ERROR';
			case E_WARNING: // 2 //
				return 'E_WARNING';
			case E_PARSE: // 4 //
				return 'E_PARSE';
			case E_NOTICE: // 8 //
				return 'E_NOTICE';
			case E_CORE_ERROR: // 16 //
				return 'E_CORE_ERROR';
			case E_CORE_WARNING: // 32 //
				return 'E_CORE_WARNING';
			case E_CORE_ERROR: // 64 //
				return 'E_COMPILE_ERROR';
			case E_CORE_WARNING: // 128 //
				return 'E_COMPILE_WARNING';
			case E_USER_ERROR: // 256 //
				return 'E_USER_ERROR';
			case E_USER_WARNING: // 512 //
				return 'E_USER_WARNING';
			case E_USER_NOTICE: // 1024 //
				return 'E_USER_NOTICE';
			case E_STRICT: // 2048 //
				return 'E_STRICT';
			case E_RECOVERABLE_ERROR: // 4096 //
				return 'E_RECOVERABLE_ERROR';
			case E_DEPRECATED: // 8192 //
				return 'E_DEPRECATED';
			case E_USER_DEPRECATED: // 16384 //
				return 'E_USER_DEPRECATED';
		}
		return "E_NO";
	}
}
?>