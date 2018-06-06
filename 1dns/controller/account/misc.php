<?php
class misc extends tController{
	public $layout = "";
	public function get_tpl(){
		$tpl_name = R("tpl","string");
	}
	//验证码
	public function captcha(){
		//清空布局
		$this->layout = '';
		//配置参数
		$width      = intval(tGpc::get('w')) == 0 ? 122 : tGpc::get('w');
		$height     = intval(tGpc::get('h')) == 0 ? 50  : tGpc::get('h');
		$wordLength = intval(tGpc::get('l')) == 0 ? 5   : tGpc::get('l');
		$fontSize   = intval(tGpc::get('s')) == 0 ? 25  : tGpc::get('s');
	
		//创建验证码
		$validateObj = new Captcha();
		$validateObj->width  = $width;
		$validateObj->height = $height;
		$validateObj->maxWordLength = $wordLength;
		$validateObj->minWordLength = $wordLength;
		$validateObj->fontSize      = $fontSize;
		$validateObj->CreateImage($text);
	
		//设置验证码
		tSafe::set('captcha',$text);
		App::end(0);
	}
	//认证码
	public function send_sms(){
		global $timestamp;
		$mobile = R("mobile","string");
		if(!tValidate::is_mobile($mobile)){
			tAjax::json_error("非法手机号！");
		}
		$find = array(
			"dateline"  => R("dateline","int"),
			"tpl"       => R("tpl","string")
		);
		$thash = R("thash","string");
		if(!tHash::chk_uri($thash,$find)){
			tAjax::json_error("非法请求");
		}
		if(($timestamp - $find['dateline']) > 30*60){//30分钟有效期
			tAjax::json_error("发送验证码URL已过期");
		}
        //检查用户设备端短信发送总条数
        $num = M("@sys_sms")->get_mobile_num($mobile);
        if($num > 3){
        	tAjax::json_error("该手机今天已限制发送！");
        }
		//检查系统每天发送邮件总数
        $num = M("@sys_sms")->get_day_num($mobile);
        if($num > 200){
        	tAjax::json_error("发送短信失败");
        }
		
		if($find['tpl'] == 'reg'){
			$errmsg = C("user")->check_user($mobile,'mobile');
			if($errmsg != ""){
				tAjax::json_error($errmsg);
			}
		}

		if($code = M("@sys_sms")->chk_sms_send($mobile,$find['tpl'])){
			tAjax::json_error("此手机号发送太频繁！稍后再试");
		}else{
			$ret = C("user")->send_sms(array("type"=>$find['tpl']),$mobile);
			if($ret){
				tAjax::json_success("发送成功");
			}else{
				tAjax::json_error("发送失败！");
			}
		}
	}
	//邮箱认证确认
	public function email_verify(){
		global $timestamp;
    	$find = array(
    		"uid"      => R("uid","int"),
    		"dateline" => R("dateline","int"),
    		"email"    => R("email","string")
    	);
     	$uri  = tHash::uri($find);
    	$thash = R("thash","string");
    	if(!tHash::chk_uri($thash,$find)){
    		I("签证未通过,邮箱认证失败");
    	}else{
    		if($timestamp - $find['dateline'] > 30*86400){
    			I("验证邮件已过期！");
    		}
    		$res = C("user")->update_user($find['uid'],array(
						"emailrz" => 1,
					));
    		if($res){
    			I("邮箱认证成功",U("account@/ucenter/safety_center"),"success");
    		}else{
    			I("验证邮件失败！");
    		}
    		
    	}
	}
	//检查验证
	public function check(){
		$type = R("ctype","string");
		$val =  R("param","string");
		
		$errmsg = "";
		if(in_array($type,array("email","mobile","uname"))){
			$errmsg = C("user")->check_user($val,$type);
		}elseif($type == "captcha"){
			if(tSafe::get('captcha') != $val){
				$errmsg = "验证码不正确";
			}
		}elseif(in_array($type,array("rzcode"))){
			if(tSafe::get('rzcode') != $val){
				$errmsg = "认证码不正确";
			}
		}
		if($errmsg){
			tAjax::json(array("status"=>"n","info"=>$errmsg));
		}else{
			$s_msg = array(
				"email"   => "恭喜您,该邮箱可以注册",
				"captcha" => "通过验证",
				'rzcode'  => "认证码正确"
			);
			tAjax::json(array("status"=>"y","info"=>(isset($s_msg[$type])?$s_msg[$type]:"通过验证")));
		}
	}
	
	public function show2code(){
		require_once ROOT_PATH.'lib/tQrcode.php';
		$s = R("s","string");
		$qrcode_obj = new tQrcode();
		header('Location:'.U().$qrcode_obj->get($s));
	}
}

?>