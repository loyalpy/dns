<?php
class product extends tController{
	public $layout = "site2016";
	public function index(){
		$this->display();
	}
	public function buy(){
		$type = R("type","string");
		$utype = 1;
		if($type == "company"){
			$utype = 2;
		}
		$domain_service = M("@domain_service")->get_list($utype,1);
		$this->assign("domain_service",$domain_service);
		$this->assign("type",$type);
		$this->display();
	}
}
?>