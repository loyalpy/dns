<?php
/**
 * @sendCloud 发送邮件模块
 */
class SendCloud {

	//邮件api调用方式：模板发送
	private static $api_url = "http://api.sendcloud.net/apiv2/mail/sendtemplate";
	//邮件api调用方式：普通发送
	private static $api_url_usual = "http://api.sendcloud.net/apiv2/mail/send";
	//邮件发信人，用正确邮件地址替代，及发信域名
	private static $send_from = 'service@bajiedns.com';
	//发件人名称
	private static $from_name = '八戒DNS';
	//是否返回emailID
	private static $resp_email_id = "false";
	//默认的回复地址
	private static $reply_to = '415480171@qq.com';
	//用户自定义邮件头部
	private static $header = 'Content-Type: application/x-www-form-urlencoded';
	//邮件api用户
	private static $api_user = 'slkjbajiednshuacc';
	//邮件api秘钥
	private static $api_key = 'gqtu7H4OldnZSCBn';
	//http请求方式
	private  static $put_type = 'POST';


	//短信api调用方式
	private static $api_url_sms = "http://sendcloud.sohu.com/smsapi/send";
	//服务器端sendCloud时间戳url
	private static $api_url_t = "http://sendcloud.sohu.com/smsapi/timestamp/get";
	//短信api用户
	private static $api_user_sms = "bajiedns_sms_user";
	//短信api秘钥
	private static $api_key_sms = "KoMO1mtEUy7SJzkKlNcjd0a7gnHzONIu";
	//短信发送模板0表示短信模板，1表示彩信模板
	private static $api_sms_rule = 0;

	//通用https请求
	private static function get($url,$param){
		$options = array(
			'http' => array(
				'method' => self::$put_type,
				'header'   => self::$header,
				'content' => http_build_query($param)
			));
		$context  = stream_context_create($options);
		$result    = file_get_contents($url, FILE_TEXT, $context);
		if($result){
			return JSON::decode($result);
		}else{
			return array("statusCode"=>0,"info"=>"api请求失败");
		}
	}
	/**
	 * 邮件发送 模板发送
	 * @param $to 邮件发送对象：邮箱
	 * @param $tpl 发送模板
	 * @param array $vars 邮件模板变量
	 * @return array|mixed 返回数组格式
	 */
	public static function send_mail($to,$tpl,$vars = array()){
		$data = array(
			'templateInvokeName' 		    => $tpl,  //邮件模板调用名称
			'apiUser' 								    => self::$api_user,
			'apiKey' 									=> self::$api_key,
			'from' 										=> self::$send_from,
			'fromName' 							=> self::$from_name,
			'respEmailId' 						    => self::$resp_email_id,
			'replyTo'									=> self::$reply_to,
			'xsmtpapi'					            => array(
																	"to"    => array($to), //收件人，多个收件人用；隔开
																	"sub"  => array(       //每次发送邮件，随机生成验证码
																		"%code%" => Array(rand(100000,999999)),
																	)
			)
		);
		if($vars){
			$data['xsmtpapi']['sub'] = array_merge($data['xsmtpapi']['sub'],$vars);
		}
		$data['xsmtpapi'] = JSON::encode($data['xsmtpapi']);
		$url = self::$api_url;
		return self::get($url,$data);
	}
	/**
	 * 邮件发送 普通发送
	 * @param $to 邮件发送对象：邮箱
	 * @param array $vars 邮件模板变量
	 * @return array|mixed 返回数组格式
	 */
	public static function send_mail_usual($to,$title,$content){
		if (empty($to) || empty($title) || empty($content)) {
			return array("statusCode"=>0,"info"=>"非法请求");
		}
		$data = array(
			'apiUser' 								    => self::$api_user,
			'apiKey' 									=> self::$api_key,
			'from' 										=> self::$send_from,
			'subject' 									=> $title,
			"html"										=> $content,
			'fromName' 							=> self::$from_name,
			'replyTo'									=> self::$reply_to,
			"to"    										=> $to,
			'respEmailId' 							=> 'false'
		);
		$url = self::$api_url_usual;
		return self::get($url,$data);
	}
	/**
	 * 短信发送
	 * @param $to 发送对象：短信
	 * @param $tpl 发送模板ID
	 * @param array $vars 发送短信变量
	 * @return array|mixed 返回数组格式
	 */
	public static function send_sms($to,$tpl,$vars = array()) {
		$param = array(
			'smsUser' 		=> self::$api_user_sms,
			'templateId' 	=> $tpl,
			'msgType' 		=> self::$api_sms_rule,
			'phone' 			=> $to,
			'timestamp' 	=> self::_getTimestamp(), //检查 timestamp 和 sd服务器当前时间, 如果两者相差大于60秒, 则请求会被拒绝.
			'vars' 				=> JSON::encode($vars) //json编码
		);

		//签名，合法性验证
		$sParamStr = "";
		ksort($param);
		foreach ($param as $sKey => $sValue) {
			$sParamStr .= $sKey . '=' . $sValue . '&';
		}
		$sParamStr   			= trim($sParamStr, '&');
		$smskey 					= self::$api_key_sms;
		$sSignature  				= md5($smskey."&".$sParamStr."&".$smskey);
		$param['signature'] = $sSignature;

		$url = self::$api_url_sms;
		return self::get($url,$param);
	}

	//短信发送获取sendCloud服务器端timestamp
	private static function _getTimestamp()
	{
		$url  = self::$api_url_t;
		$res  = self::get($url, array());
		if ($res && $res['statusCode'] == 200) {
			return $res['info']['timestamp'];
		} else {
			return 0;
		}
	}
}