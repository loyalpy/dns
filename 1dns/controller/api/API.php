<?php
class API extends tController{
	public $userinfo = array();
	public $result = array(
			"status" => "0",
			"msg"    => "",
			"data" => array()
	);
	public function __construct($controller_id){
		global $uid,$utype;
		parent::__construct($controller_id);		
		$sign = R("sign","string");
		$data = array(
			"appid"    => R("appid","int"),
			"dateline" => R("dateline","int"),
		);
		$checked = $this->_check_sign($sign,$data);
		
		if(!$checked){
			$this->respons_error("未通过签名");
		}
		//获取用户信息		
		if(empty($uid)){
			$uid = R("uid","int");
		}
		$this->userinfo = C("user")->get_cache_userinfo($uid);		
		if(!isset($this->userinfo['uid']) || empty($this->userinfo['uid'])){
			$uid = $utype = 0;
		}else{
			//其他公共变量
			$utype = $this->userinfo['utype'];	
		}        
	}
	public function respons(){
		tAjax::json($this->result);
	}
	public function respons_error($msg = "",$data = array()){
		$this->result['msg']  = $msg?$msg:L("com.2009");
		$this->result['data'] = $data;
		$this->result['status'] = 0;
		tAjax::json($this->result);
	}
	public function respons_success($msg = "",$data = array()){
		$this->result['msg']  = $msg?$msg:L("com.1009");
		$this->result['data'] = $data;
		$this->result['status'] = 1;
		tAjax::json($this->result);
	}
	//检查用户是否存在
	public function ChkUser(){
		if(!isset($this->userinfo['uid']) || empty($this->userinfo['uid'])){
			$this->respons_error(L("com.8000"));
		}
	}
	private function _check_sign($sign,$data = array()){
		$appuser = $this->_get_appuser($data['appid'],true);
		if(empty($appuser)){
			return false;
		}
		if($appuser['status'] === 0){
			return false;
		}
		ksort($data);
		$uri = "";
		foreach($data as $key => $value){
			$uri      .= ($uri == ""?"":"&")."{$key}={$value}";
		}
		$ret = ($sign === tHash::md5("{$appuser['appkey']}{$uri}{$appuser['appkey']}"));
		return $ret;
	}
	private function _get_appuser($appid,$cache=false){
		global $uid;		
		$cache_name = "appuser_{$appid}";
		$appuser    = tCache::get($cache_name);
		if(!$cache || empty($appuser)) {
			if($appid){
				$appuser = M("api_user")->get_row("appid='{$appid}'","appid,appkey,uid,status");
			}
			if($appid === 0 || empty($appuser) || !isset($appuser['appid'])){
				$appuser = array(
					"appid"  => 0,
					"appkey" => App::get_conf("cfg.api.api_key"),
					"status" => 1,
					"uid"    => 0
				);
			}
			tCache::set($cache_name,$appuser);
		}
		$uid  = $appuser['uid'];
		return $appuser;
	}
}
?>