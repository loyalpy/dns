<?php
class SDK{
	private static function sign($url = "",$data = array(),$encrypt_key = ""){
    	ksort($data);
    	$uri = "";
    	foreach($data as $key => $value){
    		$uri      .= ($uri == ""?"":"&")."{$key}={$value}";
    	}
    	$hash = tHash::md5("{$encrypt_key}{$uri}{$encrypt_key}");
    	$url = $url.((strpos($url,'?') !== false) ?(substr($url,-1) == "?"?"":'&'):'?')."sign={$hash}&".$uri;
    	return $url;
	}
	private static function get($uri,$data = array(),$encrypt_key = ""){
		$url  = U("api@".$uri);
		$url  = self::sign($url,$data,$encrypt_key);
		$ret = tCurl::get($url,120);
		if($ret['http_code'] == 200){
			return $ret['content'];
		}else{
			return "HTTPerror{$ret['http_code']}";
		}
	}
	private static function post($uri,$data = array(),$req_data = array(),$encrypt_key = ""){
		$url  = U("api@".$uri);
		$url  = self::sign($url,$data,$encrypt_key);

		$ret = 	tCurl::post($url,$req_data,120);
		if($ret['http_code'] == 200){
			return $ret['content'];
		}else{
			return "HTTPerror{$ret['http_code']}";
		}
	}	
	public static function login($data = array()){
		$uri = "/Login";
		$encrypt_key = App::get_conf("cfg.api.api_key");
		$res = self::get($uri,$data,$encrypt_key);
		$ret = JSON::decode($res);
		if(isset($ret['status'])){
			return $ret;
		}else{
			return array("status"=>0,"msg"=>$res);
		}
	}
	//站内API
	public static function web_api($uri,$data = array(),$uuid = 0,$type="post"){
		global $uid,$timestamp;
		$uuid = empty($uuid)?$uid:$uuid;
		$userinfo = C("user")->get_cache_userinfo($uuid);
		if(isset($userinfo['uid'])){
			$data['uid'] = $userinfo['uid'];
			if($type == "post"){
				$res = self::post($uri,array(
						"appid"    => 0,
						"dateline" => $timestamp,
					),$data,App::get_conf("cfg.api.api_key"));
			}else{
				$res = self::get($uri,array(
						"appid"    => 0,
						"dateline" => $timestamp,
				),$data,App::get_conf("cfg.api.api_key"));
			}
		    $ret = JSON::decode($res);
		    if(isset($ret['status'])){
		    	return $ret;
		    }else{
		    	return array("status"=>0,"msg"=>$res);
		    }
		}else{
			return "nouser";
		}
	}
}
?>