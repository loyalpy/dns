<?php
/**
 * @class Renren
 * @brief Renren��oauthЭ��ӿ�
 */
class oauth_renren extends OauthBase{
	private $apiKey = '';
	private $Secret = '';
	public function __construct($config){
		$this->apiKey = isset($config['APIKey']) ? $config['APIKey'] : '';
		$this->Secret = isset($config['Secret']) ? $config['Secret'] : '';
	}
	public function getFields(){
		return array(
			'APIKey' => array(
				'label' => 'APIKey',
				'type'  => 'string',
			),
			'Secret' => array(
				'label' => 'Secret',
				'type'  => 'string',
			),
		);
	}
	//��ȡ��¼url��ַ
	public function getLoginUrl(){
		$loginUrl  = 'https://graph.renren.com/oauth/authorize?response_type=code';
		$loginUrl .= '&client_id='.$this->apiKey;
		$loginUrl .= '&display=page';
		$loginUrl .= '&redirect_uri='.parent::getReturnUrl();
		return $loginUrl;
	}

	//��鷵��ֵ
	public function checkStatus($parm){
		if(isset($parm['error'])){
			switch($parm['error']){
				case 'login_denied':
				return -1;
				break;
			}
		}else{
			return true;
		}
	}
	//��ȡ��������
	public function getAccessToken($parm){
		$accessTokenUrl = $this->getAccessTokenUrl($parm);
		if($accessTokenUrl){
	    	$accessToken    = file_get_contents($accessTokenUrl);
	    	$tokenArray     = JSON::decode($accessToken);
	    	if(isset($tokenArray['access_token'])){
	    		tSession::set('access_token',$tokenArray['access_token']);
	    	}else{
	    		die($accessToken);
	    	}
		}else{
			return false;
		}
	}
	//��ȡ�û���Ϣ
	public function getUserInfo(){
		$apiUrl   = 'http://api.renren.com/restserver.do';
		$parms    = array(
			'access_token' => tSession::get('access_token'),
			'call_id'      => time(),
			'method'       => 'users.getInfo',
			'v'            => '1.0',
			'format'       => 'json',
		);
		$sign = $this->createSign($parms);
		$parms['sig'] = $sign;
		$userInfo = array();

		//ģ��post�ύ
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$apiUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parms));
		$renrenUser = JSON::decode(curl_exec($ch));

		$userInfo['id']   = isset($renrenUser[0]['uid'])  ? $renrenUser[0]['uid']  : '';
		$userInfo['name'] = isset($renrenUser[0]['name']) ? $renrenUser[0]['name'] : '';
		$userInfo['sex']  = isset($renrenUser[0]['sex'])  ? ($renrenUser[0]['sex'] == 1 ? 1 : 2) : '';

		return $userInfo;
	}

	//��ȡ����url��ַ
	private function getAccessTokenUrl($parm){
		$accessTokenUrl  = 'http://graph.renren.com/oauth/token?';
		$accessTokenUrl .= 'client_id='.$this->apiKey;
		$accessTokenUrl .= '&client_secret='.$this->Secret;
		$accessTokenUrl .= '&redirect_uri='.parent::getReturnUrl();
		$accessTokenUrl .= '&grant_type=authorization_code';
		$accessTokenUrl .= '&code='.$parm['code'];

		return $accessTokenUrl;
	}

	//����sign md5ֵ
	private function createSign($parms){
		$sendStr = '';
		ksort($parms);
		foreach($parms as $key => $val){
			$sendStr .= $key.'='.$val;
		}
		$sendStr .= $this->Secret;
		return md5($sendStr);
	}
}