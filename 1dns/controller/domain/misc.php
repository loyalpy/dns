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
        	tAjax::json_error("该手机发送短信量已超出！");
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
	/**
    * @brief 支付回调测试[同步]
	* define ( "PAY_FAILED", - 1);支付失败
	* define ( "PAY_TIMEOUT", 0);支付超时
	* define ( "PAY_SUCCESS", 1);支付成功
	* define ( "PAY_CANCEL", 2);支付取消
	* define ( "PAY_ERROR", 3);支付错误
	* define ( "PAY_PROGRESS", 4);支付进行
	* define ( "PAY_INVALID", 5);支付无效
	* define ( "PAY_MANUAL", 0);手工支付
	*/
	public function callback(){
		//获取支付名
		$payment_name = is_array($payment_name = R('payment_name',"string")) ? tUtil::filter($payment_name[0]) :  tUtil::filter(R('payment_name'));
		//初始化参数
		$money   = null;
		$message = '支付失败';
		$tradeno = null;
		//获取支付payment的id值
		$pObj       = new tModel('payment as a,@pay_plugin as b');
		$payment_row = $pObj->get_row('b.file_path = "'.$payment_name.'" and a.plugin_id = b.id','a.id');
		//载入支付接口文件
		$payment_obj = new cls_payment();
		$pay_obj     = $payment_obj->load($payment_name);
		if(!is_object($pay_obj)){
			I('支付方式不存在');
			exit(0);
		}
		//执行接口回调函数
		$return  = $pay_obj->callback(array_merge($_POST,$_GET),$payment_row['id'],$money,$message,$tradeno);
		//判断返回状态
		if($return == 1){
			$recharge_no  =$tradeno;
			if(cls_payment::update_recharge($recharge_no)){
				I("充值成功",U("account@/finance/recharge?type=2"),"success");
				exit(0);
			}
		}
		I('充值失败',U("account@/finance/recharge?type=2"));
		exit(0);
	}
	public function show2code(){
		require_once ROOT_PATH.'lib/tQrcode.php';
		$s = R("s","string");
		$qrcode_obj = new tQrcode();
		header('Location:'.U().$qrcode_obj->get($s));
	}
	//支付回调[异步]
	public function server_callback()  {
		$payment_name = is_array($payment_name = R('payment_name',"string")) ? tUtil::filter($payment_name[0]) :  tUtil::filter(R('payment_name'));
        //日志对象
		$tlog = new tLog();
		//初始化参数
		$money   = null;
		$message = null;
		$tradeno = null;
		///获取支付payment的id值
		$pObj       = new tModel('payment as a,@pay_plugin as b');
		$payment_row = $pObj->get_row('b.file_path = "'.$payment_name.'" and a.plugin_id = b.id','a.id');
		//载入支付接口文件
		$payment_obj = new cls_payment();
		$pay_obj     = $payment_obj->load($payment_name);
		if(!is_object($pay_obj)){
			echo 'fail';
			exit;
		}
		//执行接口回调函数
		$return  = $pay_obj->server_callback(array_merge($_POST,$_GET),$payment_row['id'],$money,$message,$tradeno);
		//判断返回状态
		if($return == 1){
			$recharge_no  =$tradeno;
			if(cls_payment::update_recharge($recharge_no)){
				echo 'success';exit;
			}else{
				$tlog->write("error",array("订单号：{$recharge_no}"));
				echo 'fail';exit;
			}
		}else{
			$tlog->write("error",array("返回：{$return}"));
			echo 'fail';
		}
		exit;
	}
}

?>