<?php
class tAction{
	protected $id;
	protected $controller;
	//初始化
	function __construct($controller, $id){
		$this->controller = $controller;
		$this->id = $id;
	}
	//开始
	function run(){
		tInterceptor::run("onCreateView");
		$controller = $this->controller;
		//获取视图显示
		if(method_exists($controller,$this->id)){
			$method_name = $this->id;
			$controller->$method_name();
		}else{
			$controller->display();
		}
		tInterceptor::run("onFinishView");
	}
}
?>