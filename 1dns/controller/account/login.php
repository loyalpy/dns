<?php
/**
 * 登录
 * by Thinkhu 2014 
 */
class login extends tController{
	public  $layout = "reglogin";
	private $mistake_times = 10;//出错次数
	private $mistake_dis = 15;//15分钟后登录
	private $redirect_url = "/ucenter/index";
	private function _login_check(){//已经登录检查
		global $uid;
		if($uid){
			$in_ajax = R("in_ajax","int");
			if($in_ajax == 1){
				tAjax::json(array(
				 	"error"   => 0,
				 	"message" => "登录成功",
				 	"callback"=> U($this->redirect_url)
				));
			}else{
				$this->redirect($this->redirect_url);
			}
		}
	}
	//登录
	public function index(){
		global $uid,$utype,$timestamp,$realip;
		//如果已经登陆,直接跳转用户中心
		$this->_login_check();
		//未登录
		if(tUtil::check_hash()){
			//判断是否是指定授权IP
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
                C("user")->login_after($uid,'login');
                //其他
				tCookie::set("log_name",$data['uname'],7*86400);
				$remember = R("record","int");
				$userinfo = C("user")->get_cache_userinfo($uid);
				//设置自动登录
				if($remember){					
				  	$authstr  = $userinfo['password']."\t".$data['uname']."\t".$uid;
				    tCookie::set("auth",$authstr,30*86400);		
				}
				$login_refer = R("refer","string");
				$login_refer = urldecode($login_refer);
				if(strpos($login_refer,".") === false){
					$login_refer = $login_refer? $login_refer: U($this->redirect_url);
				}else{
					$refer_2 = explode(".",$login_refer);
					$login_refer = U($refer_2[0]."@".$refer_2[1]);
				}
				tAjax::json(array("error"=>0,"message"=>"登录成功！","callback"=>$login_refer));
			}else{
				tAjax::json_error($res['msg']);
			}
		}else{
			$login_refer = R("refer","string");
			$this->assign("login_refer",urlencode($login_refer));
			$this->display();
		}
	}
	//检查WEIXIN登录
	public function checklogin(){
		global $uid,$utype;
		$do = R("do","string");
		if($do == "bdsuccess"){

		}elseif($do == "nobd"){

		}elseif($do == "check"){
			$wxdata = M("@session")->get_data();
			if(isset($wxdata['wx_openid']) && $wxdata['wx_openid']){
				$openid = $wxdata['wx_openid'];
				$row = M("@user_bd")->get_row("wx_openid='{$openid}'");
				if(isset($row['uid'])){
					$ret = C("user")->login_after($row['uid'],'weixin_scan');

					if($ret){
						tAjax::json(array("error"=>0,"message"=>"bdsuccess"));
					}else{
						tAjax::json(array("error"=>0,"message"=>"bdlock"));
					}
				}
				tAjax::json(array("error"=>0,"message"=>"nobd"));
			}
			tAjax::json_error("noscan");
		}
	}
	//找回密码
    public function findpass(){
    	global $uid,$timestamp;
    	if(tUtil::check_hash()){
			$name = R("uname","string");
			$type = tValidate::is_email($name)?"email":(tValidate::is_mobile($name)?"mobile":"");
			if(empty($type)){
				tAjax::json_error("账户不存在");
			}
			$captcha = R("captcha","string");
			if($captcha != tSafe::get('captcha')){
				//tAjax::json_error("验证码输入不正确");
			}
			$user_obj = new cls_user();
			if($type == "email"){
				$userinfo = $user_obj->get_user("email='{$name}'");
			}else{
				$userinfo = $user_obj->get_user("mobile='{$name}'");
			}
			if(!isset($userinfo['uid'])){
				tAjax::json_error("账户不存在");
			}
			if($userinfo['inlock'] == 1){
				tAjax::json_error("账户已锁定");
			}
			//邮箱验证
			if ($type == "email") {
				$ret = $user_obj->send_mail(array("type"=>"findpass"),$userinfo['uid'],$userinfo['email']);
				$ret = ($ret && $ret['statusCode'] == 200)?1:0;
			}elseif ($type == 'mobile') {
				$ret = 1;//$user_obj->send_sms(array("type"=>"findpass"),$userinfo['mobile']);
			}
			if($ret){
				$uri = tHash::uri(array(
					"uid"       => $userinfo['uid'],
					"name"      => $name,
					"type"      => $type,
					"dateline" => $timestamp,
				));
				tAjax::json(array("error"=>0,"message"=> ($type == "email"?"邮件发送成功，请查收":"获取信息成功"),"callback"=>U("account@/login/findpass2").$uri));
			}else{
				tAjax::json_error("验证失败！");
			}
    	}else{
    		$this->display();
    	}
    }
    //找回密码第二步
    public function findpass2(){
    	global $timestamp;
    	$find = array(
    		"uid"      => R("uid","int"),
    		"name"     => R("name","string"),
    		"type"     => R("type","string"),
    		"dateline" => R("dateline","int"),
    	);
    	// $find['type'] = "mobile";
    	$uri  = tHash::uri($find);
    	$thash = R("thash","string");
    	if(!tHash::chk_uri($thash,$find)){
    		I("未通过验证请重新找回",U("account@/login/findpass"));
			die();
    	}
    	if(($timestamp - $find['dateline']) > 30*60){//30分钟有效期
    		I("找回密码已过期",U("account@/login/findpass"));
			die();
    	}
    	
    	if(tUtil::check_hash()){
    		$rzcode          = R("code","string");
    		if(empty($rzcode)){
    			tAjax::json_error("认证码不能为空！");
    		}else{
    			if(C("sms")->chk_sms($rzcode,$find['name'],"findpass")){
    				tAjax::json(array(
		    			"error"    => 0,
		    			"message"  => "认证码正确",
		    			"callback" => U("/login/findpass3{$uri}"),
		    			));
    			}else{
    				tAjax::json_error("出错了! 认证码不正确");
    			}
    		}
    	}else{
    		if($find['type'] == "email"){
    			$data = $find;
    			$data['type'] = "findpass";

    			//$ret = BeanStalk::use_put("email",$data);
    			$ret = 1;

    			$this->assign("ret",$ret);
    			$this->layout = "reglogin";
    		}
    		$this->assign("uri",$uri);
    		$this->assign("find",$find);
    		$this->display();
    	}
    }
    //找回密码重置密码
    public function findpass3(){ 	
    	global $timestamp;
    	$find = array(
    		"uid"      => R("uid","int"),
    		"name"     => R("name","string"),
    		"type"     => R("type","string"),
    		"dateline" => R("dateline","int"),
    	);
       	if($find['type'] == "email"){
    		$find['email'] = R("email","string");
    	}
    	$uri  = tHash::uri($find);
    	$thash = R("thash","string");

    	if(!tHash::chk_uri($thash,$find)){
    		I("未通过验证请重新找回",U("/login/findpass"));
			die();
    	}
    	if(($timestamp - $find['dateline']) > 30*60){//30分钟有效期
    		I("找回密码已过期",U("/login/findpass"));
			die();
    	}    	   	
    	if(tUtil::check_hash()){
			$pwd    = R("upass","string");
			$repwd  = R("upass2","string");
			if($pwd == null || strlen($pwd) < 6){
				tAjax::json_error("新密码至少六位");
			}
			if($repwd != $pwd){
				tAjax::json_error("两次输入密码必须一致");
			}

			$re = C("user")->update_user($find['uid'],array("password"=>$pwd));
			tAjax::json(array("error"=>0,"message"=>"重置密码成功！","callback"=>U("/login/findpass4")));
    	}else{
			$this->assign("uri",$uri);
    		$this->assign("find",$find);
			$this->display();
    	}
    }
    //找回密码成功
    public function findpass4(){
    	$this->display();
    }
	//退出登录
	function logout(){
		C("user")->logout();	
		$this->redirect("/login");
	}
}
?>