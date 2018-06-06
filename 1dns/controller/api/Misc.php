<?php
/**
 * by Thinkhu 2014 
 */
class Misc extends API{
	public function __construct(){
		parent::__construct('Misc');
	}
	//登录验证
	public function GetDataConfig(){
		$uri = R("uri","string");
		$inget = array("job_type","job_time","job_edu","job_age","job_salary","job_zy","job_gov","marry",
		"mingzu","company_tp","company_sp","medier_tp","medier_sp","tag_resume",
		);
		if(empty($uri)){
			$this->result['msg'] = "获取参数必须";
			$this->respons();
		}else{
			$uri_arr = explode(",",$uri);
			$re = array();
			$data_config = App::get_conf("data_config");
			foreach($uri_arr as $v){
				if(in_array($v,$inget)){
					$re[$v] = isset($data_config[$v])?$data_config[$v]:array();
				}
			}
			$this->result['msg'] = "获取成功！";
			$this->result["status"] = 1;
			$this->result["data"] = $re;
			$this->respons();
		}		
	}
	//获取二级分类
	public function GetCate(){
		$type = R("type","string");
		if(!in_array($type,array('city','job_cate','trade_cate'))){
			$this->result['msg'] = "获取参数不正确";
			$this->respons();
		}else{
			if($type == 'city'){
				$re = C("category",$type)->get(1002);
			}else{
				$re = C("category",$type)->get(0);
			}
			/*
			$ret = array();
			$k = 0;
			if($re){
				foreach($re as $key=>$v){
					$ret["a".$k] = $v;
					$k = $k+1;
				}
			}
			*/
			$this->result['msg']    = "获取成功！";
			$this->result["status"] = 1;
			$this->result["data"]   = $re;
			$this->respons();
		}
	}
	
	//发送短信验证码
	public function SendSms(){
		$mobile = R("mobile","string");
		$type   = R("type","string","reg");
		if(!in_array($type,array('reg'))){
			$type = "reg";
		}
		if(C("sms")->chk_sms_send($mobile,$type)){
			$this->result['msg'] = "发送太频繁";
			$this->respons();
		}
		if($type == "reg"){
			$errmsg     = C("user")->check_user($mobile,'mobile');
			if($errmsg != ""){
				$this->result['msg'] = $errmsg;
				$this->respons();
			}
		}
		$ret = C("user")->send_sms(array("type"=>$type),$mobile);
		if(!$ret){
			$this->result['msg'] = "发送失败";
			$this->respons();
		}
		$this->result['msg'] = "发送成功! ";
		$this->result["status"] = 1;
		$this->respons();
	}
	//根据手机号检测最后验证码正确性
	public function CheckSms(){
		$mobile = R("mobile","string");
		$type   = R("type","string","reg");
		$code   = R("code","string");
		if(!in_array($type,array('reg'))){
			$type = "reg";
		}	
		if(C("sms")->chk_sms($code,$mobile,$type)){
			$this->result['msg'] = "验证成功! ";
			$this->result["status"] = 1;
			$this->respons();
		}else{
			$this->result['msg'] = "出错了! 验证失败";
			$this->respons();
		}
	}
}
?>