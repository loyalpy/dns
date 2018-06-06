<?php
class monitor extends UC{
	public function __construct($controller,$action = ""){
		parent::__construct($controller,$action);
	}
	public function update_rate_queue(){
		$rate = R("rate","int");
		M("@domain_monitor")->update_queue($rate);
		die("");
	}
	//域名监控，选择域名
	public  function monitor_option_domain(){
		global $uid;
		$domain  = R("domain","string");
		$RRname  = R("RRname","string");

		if ($domain) {
			if(tValidate::is_cn($domain)){
				App::uselib("tools.idna_convert");
				$idna_convert_obj = new idna_convert();
				$domain = $idna_convert_obj->encode($domain);
			}
			$res = M("domain")->get_row("domain='{$domain}' AND uid=$uid");
			if(!isset($res['domain_id'])){
				$this->_msg("此域名下的监控不存在!","此域名下的监控不存在","/monitor/monitor#返回");
			}
			if ($res['is_cn'] == 1) {
				$domain = $res['domain_cn'];
			}
		}

		$this ->assign("domain",$domain);
		$this ->assign("RRname",$RRname);
		$this->display();
	}
	//域名监控，查看记录
	public function monitor_option_record(){
		global $uid;

		$domain   = R("domain","string");
		$RRname  = R("RRname","string","@");

		if ($domain) {
			if(tValidate::is_cn($domain)){
				App::uselib("tools.idna_convert");
				$idna_convert_obj = new idna_convert();
				$domain = $idna_convert_obj->encode($domain);
			}
			$res = M("domain")->get_row("domain='{$domain}' AND uid=$uid");
			if(!isset($res['domain_id'])){
				$this->_msg("此域名下的监控不存在!","此域名下的监控不存在","/monitor/monitor#返回");
			}
			if ($res['is_cn'] == 1) {
				$domain = $res['domain_cn'];
			}
			//判断域名是否被绑定
			if (M("domain_bind")->get_one("uid = '{$uid}' AND domain_bind = '{$domain}'","count(id)")) {
				$this->_msg("此域名已被绑定，不能添加监控!","此域名已被绑定，不能添加监控","/domains/index#返回");
			}
		}

		$this ->assign("domain",$domain);
		$this ->assign("RRname",$RRname);
		$this->display();
	}
	//域名监控列表
	public function monitor(){
		global $uid;

		//实时监控
		$monitor = M("domain_monitor")->get_one("uid = {$uid}","count(*)");
		$this->assign("monitor",$monitor);
		$this->display();
	}
	//报警信息
	public function warning(){
		global $uid;

		$monitor_warning = M("domain_monitor_error")->get_one("uid = {$uid}","count(*)");
		$this->assign("monitor_warning",$monitor_warning);
		$this->display();
	}
	//监控设置
	public function monitor_set(){
		global $uid;

		$monitor_set = M("domain_monitor")->get_one("uid = {$uid}","count(*)");
		$this->assign("monitor_set",$monitor_set);
		$this->display();
	}
	//监控详情
	public function monitor_detail(){
		global $uid;

		$do 	          = R("do","string");
		$RRname   = R("RRname","string");
		$domain    = R("domain","string");
		$record_id = R("record_id","int");

		$monitor = M("domain_monitor")->get_row("domain='{$domain}' AND RRname='{$RRname}'");
		if (!isset($monitor['monitor_id'])) {
			$this->_msg("此域名下的监控不存在!","此域名下的监控不存在","/monitor/monitor#返回");
		}

		$monitor_records = M("domain_monitor_record")->get_row("record_id = '{$record_id}'");
		if (!isset($monitor_records['monitor_record_id'])) {
			$this->_msg("此记录下的监控不存在!","此记录下的监控不存在","/monitor/monitor#返回");
		}


		if ($domain) {
			if(tValidate::is_cn($domain)){
				App::uselib("tools.idna_convert");
				$idna_convert_obj = new idna_convert();
				$domain = $idna_convert_obj->encode($domain);
			}
			$res = M("domain")->get_row("domain='{$domain}' AND uid=$uid");
			if(!isset($res['domain_id'])){
				$this->_msg("此记录下的监控不存在!","此记录下的监控不存在","/monitor/monitor#返回");
			}
			if ($res['is_cn'] == 1) {
				$domain = $res['domain_cn'];
			}
		}

		$this->assign("do",$do);
		$this->assign("RRname",$RRname);
		$this->assign("domain",$domain);
		$this->assign("monitor",$monitor);
		$this->assign("record_id",$record_id);
		$this->display();
	}
}
?>