<?php
class tHttpException extends Exception{
	//构造函数
	public function __construct($message = null, $code = 0){
		$bt = debug_backtrace();
		//抛弃这一层，用上一层的
		foreach($bt as $key=>$value){
			//unset($bt[$key]);
			break;
		}
		$info = reset($bt);
		if($info !== false){
			$this->message = $message;
			$this->code = $code;
			$this->file = $info['file'];
			$this->line = $info['line'];
		}
		$this->backtrace = $bt;
	}
	/**
	 * @brief 报错 [适合在逻辑(非视图)中使用,此方法支持数据渲染]
	 * @param string $httpNum   HTTP错误代码
	 * @param array  $errorData 错误数据
	 */
	public function show(){
		$http_num = $this->getCode();
		$error_data = $this->getMessage();
		$controller = App::$controller;
		//初始化页面数据
		$show_data   = array(
			'title'   => null,
			'heading' => null,
			'message' => null,
			'http_num'=> $this->getCode()
		);
		if(is_array($error_data)){
			$show_data['title']   = isset($error_data['title'])   ? $error_data['title']   : null;
			$show_data['heading'] = isset($error_data['heading']) ? $error_data['heading'] : null;
			$show_data['message'] = isset($error_data['message']) ? $error_data['message'] : null;
		}else{
			$show_data['message'] = urlencode($error_data);
		}
		
		//检查用户是否定义了error处理类
		$exception_handler  = App::get_conf("cfg.exception_handler");
		$config = $exception_handler?$exception_handler: 'httperror' ;

		if( class_exists($config) && method_exists($config,"error{$http_num}") ){//自定义类是否存在
			$error_obj = new $config($config);			
			call_user_func(array($error_obj,'error'.$http_num),$show_data);
		}
		//是系统内置的错误机制
		else if(is_object($controller) && file_exists($controller->get_viewpath()."httperror".DIRECTORY_SEPARATOR."error{$http_num}".$controller->extend)){
			$controller->assign($show_data);
			echo $controller->fetch("httperror/error{$http_num}");
		}
		//启动框架错误机制
		else if(file_exists(ROOT_PATH."app/tpl/httperror.tpl")){
			ob_clean();
			include(ROOT_PATH."app/tpl/httperror.tpl");
		}else if(is_object($controller)){//输出错误信息
			echo $controller->__tag($show_data['message']);
		}else{
			dump($show_data);
		}
		App::end(0);
	}
}
?>