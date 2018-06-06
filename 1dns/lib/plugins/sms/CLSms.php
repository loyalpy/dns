<?php
class CLSms{
	private static $api = "http://222.73.117.158/msg/HttpBatchSendSM?";
	private static $account = "vipwckj";
	private static $pass    = "#Axddd121#9";
	public static function send($mobile,$msg){
		$post_data = array(
			"account" => self::$account,
			"pswd"    => self::$pass,
			"mobile"  => $mobile,
			"msg"     => $msg,
		);
		$o = "";
		foreach ($post_data as $k=>$v){
			$o .= "$k=".urlencode($v)."&";
		}
		$post_data=substr($o,0,-1);		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,self::$api);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$re = curl_exec($ch);
		curl_close($ch);				
		if($re){
			$flag = intval(substr($re,14,1));
			return $flag;
		}else{
			return -1;
		}
	}
}