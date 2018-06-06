<?php
/**
 * $Author: thinkhu $
*/
class session_model extends tModel{
    var $max_time  = 300; // SESSION 过期时间 900S 15分钟
    public function __construct()   {
    	parent::__construct("session");       
    }
    //开始
    public function init(){
    	register_shutdown_function(array(&$this, 'close_session'));
    }
    //获取DATA
    public function get_data($sid = ""){
    	$sid = empty($sid)?tSafe::get_id():$sid;
    	$res = $this->get_one("sid='{$sid}'","data");
    	return tUtil::unserialize($res);
    }
    //更新DATA
    public function update_data($sid = "",$data = ""){
    	$sid = empty($sid)?tSafe::get_id():$sid;
    	if($sid){
    		$this->set_data(array(
			   "data" => $data
			));
			$this->update("sid='{$sid}'");
    	} 	
    }
    //登录推出更新
    public function update_login($logtype = "login"){
    	global $uid,$timestamp,$realip;
    	$sid = tSafe::get_id();
    	if($logtype == "login"){
    		$this->set_data(array(
			    "uid"    => $uid,
				"expiry" => $timestamp,
				"ip"     => $realip,
				"data"   => ""
			));
		    $this->update("sid='{$sid}'");
    	}elseif($logtype == "logout"){
    		$this->del("sid='{$sid}'");
    	}
    }
    //随时更新会话
    function update_session(){ 
	    global $uid,$timestamp,$realip;
	    $sid = tSafe::get_id();
	    $ret = $this->get_one("sid='{$sid}'","sid");
	    if($ret){
	    	 $this->set_data(array(
	    	 	"ip"     => $realip,
		      	"expiry" => $timestamp
		     ));
		     $this->update("sid='{$sid}'");
	    }else{
	    	 $this->set_data(array(
	    	    "sid"        => $sid,
		      	"uid"        => $uid,
		      	"ip"         => $realip,
		      	"ie"         => tClient::get_browese(),
		      	"pc"         => tClient::get_pc(),
	    	 	"expiry"     => $timestamp,
	    	 	"data"       => ""
		     ));
		     $sid = $this->add();
	    }
	    return $sid;
    }
	function close_session(){
		global $timestamp;
		//先更新会话信息
	    $this->update_session();
	    //删除过期的会话
	    return $this->del("expiry < ".($timestamp-$this->max_time));
	}
}
?>