<?php
class Login extends tController{
	public  $result = array(
			"status" => "0",
			"msg"    => "",
			"data" => array()
	);
	public function index(){
		global $uid,$utype,$timestamp;
		$req = array(
			"dateline"  => R("dateline","int"),
			"uname"     => R("uname","string"),
			"upass"     => R("upass","string")
		);
		$thash = R("sign","string");
		if(!$this->_chk($thash,$req)){//检查签名
			$this->result['msg']    = "非法登陆";
		}else{
			if(empty($req['uname']) || strlen($req['uname']) < 3){
				$this->result['msg']    = "登录名为空/太短";
			}
			if(empty($req['upass']) || strlen($req['upass']) !== 32){
				$this->result['msg']    = "登录密码格式错误";
			}
			if($this->result['msg'] == ""){
				//登录类型判断
				$find_where = "";
				if(tValidate::is_email($req['uname'])){
					$find_where = "email='{$req['uname']}'";
				}elseif(tValidate::is_mobile($req['uname'])){
					$find_where = "mobile='{$req['uname']}'";
				}else{
					$find_where = "uname='{$req['uname']}'";
				}			
				$user_obj = new cls_user();
				$userinfo = $user_obj->get_user("{$find_where}");				
				if(isset($userinfo['uid'])){
					if($userinfo['password'] === $req['upass']){
						if($userinfo['inlock'] == 0){
								$uid           = $userinfo['uid'];
								$utype         = $userinfo['utype'];
								$appuser = M("api_user")->get_row("uid='{$uid}'");
								if(!isset($appuser['appid'])){
									$appuser = array(
										"appid"  => 0,
										"appkey" => App::get_conf("cfg.api.api_key"),
										"status" => 1,
									);
								}
								$this->result['status'] = 1; 
								$this->result['msg']  = "登录成功";
								$this->result['data'] = array(
									"uid"    => $uid,
									"utype"  => $utype,
									"appid"  => $appuser['appid'],
									"appkey" => $appuser['appkey'],
									"name"   => $req['uname'],
								);								

								if($utype == 1){
									
								}elseif($utype == 2){
									
								}elseif($utype == 3){
									
								}
						}elseif($userinfo['inlock'] == 1){
							$this->result['msg'] = "用户已临时锁定";
						}elseif($userinfo['inlock'] == 2){
							$this->result['msg'] = "用户已被系统锁定";
						}
					}else{
						$this->result['msg'] = "密码错误";
					}
				}else{
					$this->result['msg'] = "用户不存在";
				}
			}
		}
		tAjax::json($this->result);
	}
	private function _chk($hash,$data = array()){
    	$encrypt_key = App::get_conf("cfg.api.api_key");
    	ksort($data);
    	$uri = "";
    	foreach($data as $key => $value){
    		$uri      .= ($uri == ""?"":"&")."{$key}={$value}";
    	}
    	return $hash === tHash::md5("{$encrypt_key}{$uri}{$encrypt_key}");    	
    }
}