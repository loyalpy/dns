<?php
/**
 * @class Renren
 * @brief Renren的oauth协议接口
 */
class oauth_renren extends OauthBase{
	private $apiKey = '';
	private $Secret = '';
	private $_need_request = array('code');
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
	//获取登录url地址
	public function getAuthorizeURL(){
		$loginUrl  = 'https://graph.renren.com/oauth/authorize?response_type=code';
		$loginUrl .= '&client_id='.$this->apiKey;
		$loginUrl .= '&display=page';
		$loginUrl .= '&redirect_uri='.parent::getReturnUrl();
		return $loginUrl;
	}

	//检查返回值
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
	//获取令牌数据
	public function getAccessToken($parm){
		$accessTokenUrl = $this->getAccessTokenUrl($parm);
		if($accessTokenUrl){
	    	$accessToken    = file_get_contents($accessTokenUrl);
	    	$tokenArray     = JSON::decode($accessToken);
	    	if(isset($tokenArray['access_token'])){
	    		return $tokenArray['access_token'];
	    	}else{
	    		die($accessToken);
	    	}
		}else{
			return false;
		}
	}
	//获取用户信息
	public function getUserInfo($request_args){
		$apiUrl   = 'http://api.renren.com/restserver.do';
		$token = $this->getAccessToken($request_args);
		$parms    = array(
			'access_token' => $token,
			'call_id'      => time(),
			'method'       => 'users.getInfo',
			'v'            => '1.0',
			'format'       => 'json',
		);
		$sign = $this->createSign($parms);
		$parms['sig'] = $sign;
		$userInfo = array();

		//模拟post提交
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

	//获取令牌url地址
	private function getAccessTokenUrl($parm){
		$accessTokenUrl  = 'http://graph.renren.com/oauth/token?';
		$accessTokenUrl .= 'client_id='.$this->apiKey;
		$accessTokenUrl .= '&client_secret='.$this->Secret;
		$accessTokenUrl .= '&redirect_uri='.parent::getReturnUrl();
		$accessTokenUrl .= '&grant_type=authorization_code';
		$accessTokenUrl .= '&code='.$parm['code'];

		return $accessTokenUrl;
	}

	//计算sign md5值
	private function createSign($parms){
		$sendStr = '';
		ksort($parms);
		foreach($parms as $key => $val){
			$sendStr .= $key.'='.$val;
		}
		$sendStr .= $this->Secret;
		return md5($sendStr);
	}
	public function NeedRequest() {
        return $this->_need_request;
    }
}