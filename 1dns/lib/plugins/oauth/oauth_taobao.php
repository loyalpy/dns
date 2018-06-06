<?php
/**
 * @class Taobao
 * @brief taobao��oauthЭ��ӿ�
 */
class oauth_taobao extends OauthBase{
	private $apiKey    = '';
	private $apiSecret = '';

	public function __construct($config){
		$this->apiKey    = $config['apiKey'];
		$this->apiSecret = $config['apiSecret'];
	}

	public function getFields(){
		return array(
			'apiKey' => array(
				'label' => 'apiKey',
				'type'  => 'string',
			),
			'apiSecret'=>array(
				'label' => 'apiSecret',
				'type'  => 'string',
			),
		);
	}

	//��ȡ��¼url��ַ
	public function getLoginUrl(){
		$url  = 'https://oauth.taobao.com/authorize?response_type=code';
		$url .= '&client_id='.$this->apiKey;
		$url .= '&redirect_uri='.parent::getReturnUrl();
		return $url;
	}

	//��ȡ��������
	public function getAccessToken($parms){
		$url           = 'https://oauth.taobao.com/token';
		$urlParmsArray = array(
			'grant_type'   => 'authorization_code',
			'code'         => $parms['code'],
			'redirect_uri' => parent::getReturnUrl(),
			'client_id'    => $this->apiKey,
			'client_secret'=> $this->apiSecret,
		);

		//ģ��post�ύ
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($urlParmsArray));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$tokenInfo = JSON::decode(curl_exec($ch));

		if(!isset($tokenInfo['access_token'])){
			die(var_export($tokenInfo));
		}
		tSession::set('access_token',$tokenInfo['access_token']);
	}

	//��ȡ�û�����
	public function getUserInfo(){
		$url = 'http://gw.api.taobao.com/router/rest?';

		$paramArr = array(
			/* APIϵͳ��������� Start */
			'method' => 'taobao.user.get',              //API����
			'session' => tSession::get('access_token'), //session
			'timestamp' => date('Y-m-d H:i:s'),
			'format' => 'json',                         //���ظ�ʽ
			'app_key' => $this->apiKey,                 //Appkey
			'v' => '2.0',                               //API�汾��
			'sign_method'=> 'md5',                      //ǩ����ʽ

			/* APIӦ�ü�������� Start*/
			'fields' => 'user_id,nick,sex,email',       //�����ֶ�
		);

		$sign = $this->createSign($paramArr,$this->apiSecret);

		//��֯����
		$strParam = $this->createStrParam($paramArr);
		$strParam .= 'sign='.$sign;

		//����Url
		$urls = $url.$strParam;

		$userInfo     = array();
		$results      = file_get_contents($urls);
		$resultsArray = JSON::decode($results);

		if(isset($resultsArray['user_get_response']['user'])){
			$userArray = $resultsArray['user_get_response']['user'];
			$userInfo['id']   = isset($userArray['user_id']) ? $userArray['user_id'] : '';
			$userInfo['name'] = isset($userArray['nick'])    ? $userArray['nick']    : '';
			$userInfo['sex']  = isset($userArray['sex'])     ? ($userArray['sex'] == 'm' ? 1 : 2) : '';
		}

		return $userInfo;
	}

	public function checkStatus($parms){
		if(isset($parms['error'])){
			return false;
		}else{
			return true;
		}
	}

	//����ǩ������
	private function createSign($paramArr,$appSecret){
	    $sign = $appSecret;
	    ksort($paramArr);
	    foreach ($paramArr as $key => $val){
	       if ($key !='' && $val !=''){
	           $sign .= $key.$val;
	       }
	    }
	    $sign = strtoupper(md5($sign.$appSecret));
	    return $sign;
	}

	//��κ���
	private function createStrParam ($paramArr)	{
	    $strParam = '';
	    foreach ($paramArr as $key => $val){
	       if ($key != '' && $val !=''){
	           $strParam .= $key.'='.urlencode($val).'&';
	       }
	    }
	    return $strParam;
	}
}