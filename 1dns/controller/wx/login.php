<?php
class login extends tController{
	public $layout = "site";
	public function index(){
		$sid  = R("sid","string");
		$code = R("code","string");
		//tSlog::log($code.$sid);
		if($code){
			$wxdata = SDKwx::get_userinfo_bycode($code);
			$openid = $wxdata['wx_openid'];
			if($openid){
				$data = serialize($wxdata);
				if($sid){
					$ret = M("@session")->update_data($sid,$data);
				}
				$ret = M("@session")->update_data(tSafe::get_id(),$data);
				$row = M("@user_bd")->get_row("wx_openid='{$openid}'");

				if(isset($row['uid'])){//已经绑定登录操作
					C("user")->login_after($row['uid'],'weixin');
					$this->redirect(U("/mobile/views/ucenter.html"));
				}else{
					$this->redirect(U("/mobile/views/login.html"));
				}				
			}
		}
		$this->display();
	}
	//注册操作
	public function do_register(){
		global $uid;
		if($uid){
			$userinfo = C("user")->get_cache_userinfo($uid);
			tAjax::json(array("error"=>0,"uid"=>$uid,"upass"=>$userinfo['password']));
		}
		$data = array(
			"ut"     => 1,
			"uname"  => R("uname","string"),
			"rzcode" => R("rzcode","string"),
			"pass"   => R("upass","string"),
			"pass2"  => R("upass2","string"),
			
			"name"   => R("name","string"),
			"email"  => R("email","string"),
			"mobile" => R("mobile","string"),
		);
		$res = SDK::web_api("/Register",$data);
		if($res['status'] == 1){
			$uid = $res['data']['uid'];
			$utype = $res['data']['utype'];
			//如果是微信登陆
			C("user")->session_bd($uid);
			//注册之后
        	C("user")->reg_after($uid);

        	$return = array(
            	"error"=>0,
            	"uid"=>$uid,
            	"upass"=>md5($data['upass']),
            	"utype"=>$utype,
            	);
            //其他
			tAjax::json($return);
		}else{
			tAjax::json_error($res['msg']);
		}
	}
	//登录操作
	public function do_login(){
		global $uid,$utype,$timestamp;
		if($uid){
			$userinfo = C("user")->get_cache_userinfo($uid);
			tAjax::json(array("error"=>0,"uid"=>$uid,"upass"=>$userinfo['password']));
		}
		$data = array(
			"dateline"  => $timestamp,
			"uname"     => R("uname","string"),
			"upass"     => R("upass","string")
		);
		$data['upass'] = md5($data['upass']);
		$res = SDK::login($data);
		if($res['status'] == 1){
			$uid   = $res['data']['uid'];
			$utype = $res['data']['utype'];
			//如果是微信登陆
			C("user")->session_bd($uid);
		 	//记住登录状态
            C("user")->login_after($uid,'weixin');
            $return = array(
            	"error"=>0,
            	"uid"=>$uid,
            	"upass"=>$data['upass'],
            	"utype"=>$utype,
            	);
            //其他
			tAjax::json($return);
		}else{
			tAjax::json_error($res['msg']);
		}
	}
	//检查状态操作
	public function state(){
		global $uid,$utype;
		$ret = 1;
		if(empty($uid)){
			$uid   = R("uid","int");
			$upass = R("upass","string");
			$userinfo = C("user")->get_user("uid='{$uid}' AND password='{$upass}'","");
			if($userinfo){
				$uid   = $userinfo['uid'];
				$utype = $userinfo['utype'];
				$ret = C("user")->login_after($uid,"local");
			}else{
				$uid = $utype = $ret = 0;
			}
		}
		if($ret){
			tAjax::json_success("ok");
		}else{
			tAjax::json_error("fail");
		}
	}
	//退出
	public function logout(){
		C("user")->logout();
		tAjax::json_success("ok");
	}
}
?>