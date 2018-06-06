<?php
class oauth_qq extends OauthBase{
	private $apiId  = '';
	private $apiKey = '';
	public function __construct($config){
		$this->apiId  = $config['apiId'];
		$this->apiKey = $config['apiKey'];
	}
	public function getFields(){
		return array(
			'apiId' => array(
				'label' => 'apiId',
				'type'  => 'string',
			),
			'apiKey'=> array(
				'label' => 'apiKey',
				'type'  => 'string',
			),
		);
	}
	//��ȡ��¼url��ַ
	public function getLoginUrl(){
		$redirect = "http://openapi.qzone.qq.com/oauth/qzoneoauth_authorize?oauth_consumer_key=".$this->apiId."&";
	    $result = array();
	    $request_token = $this->get_request_token();
	    parse_str($request_token, $result);
	    if(isset($result["oauth_token"]) && isset($result["oauth_token_secret"])){
		    tSession::set('token',$result["oauth_token"]);
		    tSession::set('secret',$result["oauth_token_secret"]);
	    }else{
	    	die($request_token);
	    }
	    //��������URL
	    $redirect .= "oauth_token=".$result["oauth_token"]."&oauth_callback=".rawurlencode(parent::getReturnUrl());
	    return $redirect;
	}
	//��ȡ��������
	public function getAccessToken($parms){
		$url    = "http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token?";
		$sigstr = "GET"."&".rawurlencode("http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token")."&";
		$params = array();
		$params["oauth_version"]          = "1.0";
		$params["oauth_signature_method"] = "HMAC-SHA1";
		$params["oauth_timestamp"]        = time();
		$params["oauth_nonce"]            = mt_rand();
		$params["oauth_consumer_key"]     = $this->apiId;
		$params["oauth_token"]            = $parms['oauth_token'];
		$params["oauth_vericode"]         = $parms['oauth_vericode'];
		//�Բ���������ĸ���������л�
		$normalized_str = $this->get_normalized_string($params);
		$sigstr        .= rawurlencode($normalized_str);
		//��2��������Կ
		$key = $this->apiKey."&".tSession::get('secret');
		//��3������oauth_signatureǩ��ֵ��������Ҫȷ��PHP�汾֧��hash_hmac����
		$signature = $this->get_signature($sigstr, $key);
		//��������url
		$url      .= $normalized_str."&"."oauth_signature=".rawurlencode($signature);
		$result = array();
		$access_str = file_get_contents($url);
		parse_str($access_str, $result);
		if(isset($result["oauth_token"]) && isset($result["oauth_token_secret"]) && isset($result["openid"])){
			tSession::set('token',$result["oauth_token"]);
			tSession::set('secret',$result["oauth_token_secret"]);
			tSession::set('openid',$result["openid"]);
		}else{
			die($access_str);
		}
	}
	//��ȡ�û�����
	public function getUserInfo(){
		//��ȡ�û���Ϣ�Ľӿڵ�ַ, ��Ҫ����!!
	    $url  = "http://openapi.qzone.qq.com/user/get_user_info";
	    $info = $this->do_get($url, $this->apiId, $this->apiKey, tSession::get('token'), tSession::get('secret'), tSession::get('openid'));
	    $arr  = JSON::decode($info);
	    $userInfo = array();

	    $userInfo['id']   = tSession::get('openid');
	    $userInfo['name'] = isset($arr['nickname']) ? $arr['nickname'] : '';
	    return $userInfo;
	}
	public function checkStatus($parms){
		return true;
	}
	//getͨ�÷���
	private function do_get($url, $appid, $appkey, $access_token, $access_token_secret, $openid){
	    $sigstr = "GET"."&".rawurlencode("$url")."&";

	    //��Ҫ����, ��Ҫ������!!
	    $params = $_GET;
	    $params["oauth_version"]          = "1.0";
	    $params["oauth_signature_method"] = "HMAC-SHA1";
	    $params["oauth_timestamp"]        = time();
	    $params["oauth_nonce"]            = mt_rand();
	    $params["oauth_consumer_key"]     = $appid;
	    $params["oauth_token"]            = $access_token;
	    $params["openid"]                 = $openid;
	    unset($params["oauth_signature"]);

	    //����������ĸ���������л�
	    $normalized_str = $this->get_normalized_string($params);
	    $sigstr        .= rawurlencode($normalized_str);

	    //ǩ��,ȷ��php�汾֧��hash_hmac����
	    $key = $appkey."&".$access_token_secret;
	    $signature = $this->get_signature($sigstr, $key);
	    $url      .= "?".$normalized_str."&"."oauth_signature=".rawurlencode($signature);

	    return file_get_contents($url);
	}
	//��ȡ��ʱ����
	private function get_request_token(){
	    $url = "http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token?";

	    //����oauth_signatureǩ��ֵ��ǩ��ֵ���ɷ��������http://wiki.opensns.qq.com/wiki/��QQ��¼��ǩ������oauth_signature��˵����
	    //��1�� ��������ǩ��ֵ��Դ����HTTP����ʽ & urlencode(uri) & urlencode(a=x&b=y&...)��
		$sigstr = "GET"."&".rawurlencode("http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token")."&";

		//��Ҫ����
	    $params = array();
	    $params["oauth_version"]          = "1.0";
	    $params["oauth_signature_method"] = "HMAC-SHA1";
	    $params["oauth_timestamp"]        = time();
	    $params["oauth_nonce"]            = mt_rand();
	    $params["oauth_consumer_key"]     = $this->apiId;

	    //�Բ���������ĸ���������л�
	    $normalized_str = $this->get_normalized_string($params);
	    $sigstr        .= rawurlencode($normalized_str);

		//��2��������Կ
	    $key = $this->apiKey."&";

	 	//��3������oauth_signatureǩ��ֵ��������Ҫȷ��PHP�汾֧��hash_hmac����
	    $signature = $this->get_signature($sigstr, $key);

		//��������url
	    $url      .= $normalized_str."&"."oauth_signature=".rawurlencode($signature);

	    return file_get_contents($url);
	}
	//sign���㷽��
	private function get_signature($str, $key){
	    $signature = "";
	    if (function_exists('hash_hmac')){
	        $signature = base64_encode(hash_hmac("sha1", $str, $key, true));
	    }else{
	        $blocksize	= 64;
	        $hashfunc	= 'sha1';
	        if (strlen($key) > $blocksize){
	            $key = pack('H*', $hashfunc($key));
	        }
	        $key	= str_pad($key,$blocksize,chr(0x00));
	        $ipad	= str_repeat(chr(0x36),$blocksize);
	        $opad	= str_repeat(chr(0x5c),$blocksize);
	        $hmac 	= pack(
	            'H*',$hashfunc(
	                ($key^$opad).pack(
	                    'H*',$hashfunc(
	                        ($key^$ipad).$str
	                    )
	                )
	            )
	        );
	        $signature = base64_encode($hmac);
	    }
	    return $signature;
	}
	//��������
	private function get_normalized_string($params){
	    ksort($params);
	    $normalized = array();
	    foreach($params as $key => $val){
	        $normalized[] = $key."=".$val;
	    }

	    return join("&", $normalized);
	}
}