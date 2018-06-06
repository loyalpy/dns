<?php
class Register extends API{
	public function __construct(){
		parent::__construct('Register');
	}
	public function index(){
		global $uid,$utype,$timestamp;
		
		$ut = R("ut","int");
		$data = array(
			"utype" => $ut,
		);
		$ext_data = array();
		
		//判断密码
		$pass  = R("pass","string");
		$pass2 = R("pass2","string");
		if($pass == "" || strlen($pass)<6 || strlen($pass) >18){
			$this->result['msg'] = "密码为6-18位";
			$this->respons();
		}
		if($pass != $pass2){
			$this->result['msg'] = "确认密码与密码不一致";
			$this->respons();
		}
		$data['password'] = $pass;
		
		if(!in_array($ut,array(1))){
			$this->result['msg'] = "注册会员信息有误";
			$this->respons();
		}elseif($ut == 1){
			$data['uname']     = R("uname","string");
			$data['email']     = $data['uname'];
		}
		//用户类
		$result = C("user")->add_user($data,$ext_data);
		if($result["error"] == 1){
			$uid = $utype = 0;
			$this->result['msg'] = $result["message"];
			$this->respons();
		}else{
			//注册成功后-	
			$uid      = $result['uid'];	
			$utype    = $ut;						
            //返回       
			$this->result['status'] = 1; 
			$this->result['msg']  = "注册成功";
			$this->result['data'] = array(
				"uid"    => $uid,
				"utype"  => $utype,
				"name"   => $ut == 1?$data['email']:$data['uname'],
			);
			$this->respons();
		}
	}
}