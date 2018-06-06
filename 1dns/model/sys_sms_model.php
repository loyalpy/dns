<?php
class sys_sms_model extends tModel{
	public function __construct(){
		parent::__construct("sys_sms");
	}
	public function sms_add($data){
		global $timestamp,$realip;
		$data['session_id'] = tSafe::get_id();
		$data['dateline']   = $timestamp;
		$data['ip']         = $realip;
		$data['bz']         =(isset($data['bz'])?$data['bz']:"").tClient::get_pc()." ".tClient::get_browese();
		$data['status']     = 1;
		$this->set_data($data);
		return $this->add();
	}
	//获取最后手机短信验证码
	public function get_lastcode($mobile,$tpl,$distime = 1800){
		global $timestamp;
		$code = M("sys_sms")->get_one("mobile='{$mobile}' AND tpl='{$tpl}' AND dateline>".($timestamp-$distime),"code","dateline DESC");
		return $code;
	}
	//同一手机短信间隔
	public function chk_sms_send($mobile,$tpl){
		return $this->get_lastcode($mobile,$tpl,120);
	}
	//检查手机验证码正确性质
	public function chk_sms($code,$mobile,$tpl = "rz"){
		return $this->get_lastcode($mobile,$tpl) === $code;
	}

	//获取同一个 session_id 发送短信数 当天
	public function get_sid_num(){
		global $timestamp;
		$start = strtotime(date("Y-m-d",$timestamp));
		$end   = strtotime(date('Y-m-d',$timestamp+86400));
		$sid   = tSafe::get_id();
		return M("sys_sms")->get_one("session_id='{$sid}' AND dateline>{$start} AND dateline<{$end}","count(id)");
	}
	//获取单个手机当天手机发送量
	public function get_mobile_num($mobile = ""){
		global $timestamp;

		$start = strtotime(date("Y-m-d",$timestamp));
		$end   = strtotime(date('Y-m-d',$timestamp+86400));
		return M("sys_sms")->get_one("mobile='{$mobile}' AND dateline>{$start} AND dateline<{$end}","count(id)");
	}
	//获取平台当天发送短信总数
	public function get_day_num(){
		global $timestamp;
		$start = strtotime(date("Y-m-d",$timestamp));
		$end   = strtotime(date('Y-m-d',$timestamp+86400));
		return M("sys_sms")->get_one("dateline>{$start} AND dateline<{$end}","count(id)");
	}
}
?>