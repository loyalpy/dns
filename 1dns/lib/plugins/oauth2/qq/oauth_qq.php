<?php
require_once dirname(__FILE__) . '/qq.class.php'; //导入腾讯SDK
class oauth_qq extends OauthBase {
    private $_need_request = array('code', 'state');
    public function __construct($setting) {
        //组装回调地址
        $this->redirect_uri = parent::getReturnUrl();
        $this->setting = $setting;
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
    /**
     * 获取授权地址
     */
    function getAuthorizeURL() {
        $oauth = new QqTOAuthV2($this->setting['apiId'], $this->setting['apiKey']);
        return $oauth->getAuthorizeURL($this->redirect_uri);
    }
    /**
     * 获取用户信息
     */
    public function getUserInfo($request_args) {
        $oauth = new QqTOAuthV2($this->setting['apiId'], $this->setting['apiKey']);
        $keys = array('code'=>$request_args['code'], 'state'=>$request_args['state'], 'redirect_uri'=>$this->redirect_uri);
        $token = $oauth->getAccessToken($keys);

        $openid = $oauth->getOpenid($token["access_token"]);
        $user = $oauth->getUserInfo($token["access_token"], $openid);
        $result['id'] = $openid;
        $result['name'] = $user['nickname'];
        $result['avatar_small'] = $user['figureurl'];
        $result['avatar_big'] = $user['figureurl_2'];
        $result['avatar_small'] = '';
        $result['avatar_big'] = '';
        $result['bind_info'] = $token;
        return $result;
    }
    /**
     * 推送信息
     */
    public function getAccessToken($request_args){
    	$oauth = new QqTOAuthV2($this->setting['apiId'], $this->setting['apiKey']);
    	$keys = array('code'=>$request_args['code'], 'state'=>$request_args['state'], 'redirect_uri'=>$this->redirect_uri);
    	return $oauth->getAccessToken($keys);
    }
    public function send($bind_user, $data) {
        $token = unserialize($bind_user['info']);
        $client = new QqTOAuthV2($this->setting['apiId'], $this->setting['apiKey']);
        try {
            $return = $client->add_topic($token['access_token'], $bind_user['keyid'], array(
                'format' => '',
                'richtype' => '2',
                'richval' => $data['url'],
                'con' => $data['content'],
                'lbs_nm' => '',
                'lbs_x' => '',
                'lbs_y' => '',
                'third_source' => '',
            ));
        }catch(Exception $e){}
    }
    public function getFriends($bind_user, $page, $count) {
        
    }
    public function follow($bind_user, $uid) {
        
    }
    public function NeedRequest() {
        return $this->_need_request;
    }
}