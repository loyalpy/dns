<?php
class tCurl{
	public static function post($url, $data, $timeout=30) {
        $return = array(
            "http_code" => 0,
            "content"   => ""
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        $return['content']   = curl_exec($ch);
        $return['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $return;
    }
    public static function get($url,$timeout=30) {
        $return = array(
            "http_code" => 0,
            "content"   => ""
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $return['content']   =  curl_exec($ch);
        $return['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $return;
    }
    //加载目标网站验证码
    public static function get_authcode($authcode_url){
    	global $uid;
    	$cookie_file = ROOT_PATH."cache/curl_cookie/cookie{$uid}.tmp";
	    $ch = curl_init($authcode_url);
	    curl_setopt($ch,CURLOPT_COOKIEJAR, $cookie_file); // 把返回来的cookie信息保存在文件中
	    curl_exec($ch);
	    curl_close($ch);
    }
    //通过目标网站cookie获取内容
    public static function get_byauthcode($url,$data_params="",$cookie_file=""){
    	global $uid;
    	$cookie_file = empty($cookie_file)?(ROOT_PATH."cache/curl_cookie/cookie{$uid}.tmp"):$cookie_file;
	    $ch = curl_init($url);
	    curl_setopt($ch,CURLOPT_COOKIEFILE, $cookie_file); //同时发送Cookie
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch,CURLOPT_POST, TRUE);
	    curl_setopt($ch,CURLOPT_POSTFIELDS, $data_params); //提交查询信息
	    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	    $content = curl_exec($ch);
	    curl_close($ch);
	    return $content;
	}


}

?>