<?php
require_once dirname(__FILE__) . '/taobao.class.php';
class oauth_taobao extends OauthBase{
    private $_need_request = array('code');
    public function __construct($setting) {
        $this->redirect_uri = parent::getReturnUrl();
        $this->setting = $setting;
    }
    public function getAuthorizeURL() {
      $oauth = new TaobaoTOAuthV2($this->setting['apiKey'], $this->setting['apiSecret'] );
      return $oauth->getAuthorizeURL($this->redirect_uri);
    }
    public function getUserInfo($request_args) {
        $oauth = new TaobaoTOAuthV2($this->setting['apiKey'], $this->setting['apiSecret'] );
        $keys = array('code'=>$request_args['code'], 'redirect_uri'=>$this->redirect_uri);
        $token = $oauth->getAccessToken($keys);
        $result['id'] = $token['taobao_user_id'];
        $result['name'] = $token['taobao_user_nick'];
        $result['avatar_small'] = '';
        $result['avatar_big'] = '';
        $result['bind_info'] = $token;
        return $result;
    }
    public function getFriends($bind_user, $page, $count) {
        
    }
    public function send($bind_user, $data) {
        //淘宝不发送
    }
    public function follow($bind_user, $uid) {
        
    }
    public function NeedRequest() {
        return $this->_need_request;
    }
    public function CheckTaoBaoSign($top_secret,$top_parameters,$top_sign) {
        $sign = base64_encode(md5($top_parameters.$top_secret,true));
        return $sign == $top_sign;
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
}