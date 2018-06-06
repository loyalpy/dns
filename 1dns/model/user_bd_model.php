<?php
class user_bd_model extends tModel{
	public function __construct(){
		parent::__construct("user_bd");
	}
	//检测是否绑定
	public function get_wxbd($uid){
		$row = $this->get_row("uid={$uid}");
		if(!isset($row['id'])){
			$row = array("status"=> 0);
		}else{
			if($row['wx_openid']){
				$row['status'] = 2;
			}else{
				$row['status'] = 1;
			}			
		}
		return $row;
	}
	//绑定
	public function wxbd($uid,$openid = "",$data = array()){
		global $timestamp;
		$ret = 0;
		$data = $data?$data:array();
		$userinfo = C("user")->get_cache_userinfo($uid);
        if(isset($userinfo['uid']) && $userinfo['uid'] && $openid){
        	$row0 = $this->get_row("wx_openid='{$openid}'");
        	if(isset($row0['uid'])){
        		$ret = intval($row0['uid']);
        	}else{
	        	$row  = $this->get_row("uid='{$userinfo['uid']}'");
	        	if(!isset($row['id'])){
	        		$data['uid']       = $userinfo['uid'];
	        		$data['wx_openid'] = $openid;
	        		$data['dateline']  = $timestamp;
	        		
	        		$this->set_data($data);
	        		$ret = $this->add();
	        		if($ret){
			        	$ret = $openid;
			        	C("user")->set_cache_userinfo($uid,null);
			        }
	        	}else{
	        		$bd_id = $row['id'];
	        		if(empty($row['wx_openid'])){
	        			$data['wx_openid'] = $openid;
	        			$this->set_data($data);
	        			$ret = $this->update("id={$bd_id}");
	        			if($ret){
			        		$ret = $openid;
			        		C("user")->set_cache_userinfo($uid,null);
			        	}
	        		}else{
	        			$ret = $row['wx_openid'];
	        		}
	        	} 
        	}       	
        }
        return $ret;
	}
	//解除绑定
	public function unwxbd($uid){
		global $timestamp;
		$ret = 1;
		$userinfo = C("user")->get_cache_userinfo($uid);
        if(isset($userinfo['uid']) && $userinfo['uid']){
        	$row = $this->get_row("uid='{$userinfo['uid']}'");
        	if(isset($row['id'])){
        		$bd_id = $row['id'];
        		$this->set_data(array("wx_openid"=>''));
        		$ret = $this->update("id={$bd_id}");
        		if($ret){
        			C("user")->set_cache_userinfo($uid,null);
        		}
        	}
        }
		return $ret;
	}
}