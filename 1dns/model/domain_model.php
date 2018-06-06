<?php
class domain_model extends tModel{
	public function __construct(){
		parent::__construct("domain");
	}
	public function get($domain = ""){
		return M("domain")->get_row("domain='{$domain}'");
	}
	public function get_list($data = array(), $pageurl = ""){
		$data['table'] 		= isset($data['table']) ? $data['table'] : "domain";
		$data['where'] 		= isset($data['where']) ? $data['where'] : "1";
		$data['page'] 		= isset($data['page']) ? $data['page'] : 1;
		$data['pagesize']   = isset($data['pagesize']) ? $data['pagesize'] : 20;
		$data['order'] 		= isset($data['order']) ? $data['order'] : "DOMAIN_ID DESC";
		$query = new tQuery($data['table']);
		foreach ($data as $k => $v) {
			$query->$k = $v;
		}
		$list = array();
		$list['list'] = $query->find();
		$list['pagebar'] = $query->get_pagebar($pageurl);
		$list['total'] = $query->total;
		$list['totalpage'] = $query->totalpage;
		return $list;
	}
    //域名套餐升级续费 type 0  用户升级  1管理员直接修改
	public function trans($domain,$new_service_group,$expiry = 0,$type=0){
		global $timestamp;
		if(empty($new_service_group)){
			return false;
		}
		$new_service_group_info = M("@domain_service")->get_cache($new_service_group);
		if(!isset($new_service_group_info['service_group'])){
			return false;
		}

		$domain_info = $this->get($domain);
		if(isset($domain_info['domain'])){
			$updata = array();
			$expiry_type = 1;
			if($expiry > 1000000000){
				$expiry_type = 0;
			}

			if($expiry && $expiry_type == 1){
				$month = ($expiry<10)?$expiry:(ceil($expiry/10)*12);
				$updata['service_expiry'] = strtotime("+{$month} month");
			}else{
				$updata['service_expiry'] = $expiry;
			}
			if($domain_info['service_group'] != $new_service_group){//升级
				if($new_service_group == "free"){
					$updata['service_group']  = "free";
					$updata['service_expiry'] = 0;
					$updata['qps']            = isset($new_service_group_info['data']['QPS']['value'])?$new_service_group_info['data']['QPS']['value']:1000;
				}else{
					$updata['service_group'] = $new_service_group;
				}
				//升级为最新QPS
				if(isset($new_service_group_info['data']['QPS']['value']) && 
					$new_service_group_info['data']['QPS']['value'] > $domain_info['qps']){
					$updata['qps'] = $new_service_group_info['data']['QPS']['value'];
				}
			}else{//续费
				$updata['service_expiry']  = $expiry_type?($domain_info['service_expiry'] + $updata['service_expiry'] - $timestamp):$updata['service_expiry'];
			}
			if($updata){
				$ret = M("domain")->set_data($updata)->update("domain='{$domain}'");
			}
			//切换成功
			if($ret){
				$modi_log = ("到期时间：".($updata['service_expiry']?tTime::get_datetime("Y-m-d",$updata['service_expiry']):"永久"));
				$this->log("更换套餐",$modi_log,array(
					"modi_from" => $domain_info['service_group'],
					"modi_to"     => $new_service_group,
					"domain_id"  => $domain_info['domain_id'],
					"domain"      => $domain_info['domain'],
				));
				//切换NS
				$this->trans_ns($domain,$new_service_group_info['ns_group'],$type);
				return true;
			}
		}
		return false;
	}
	//域名切换NS
	public function trans_ns($domain,$new_ns_group,$type=0){
		global $timestamp;
		if(empty($new_ns_group)){
			return false;
		}
		$new_ns_group_info = M("@domain_ns_group")->get_cache_by_ns($new_ns_group);
		if(!isset($new_ns_group_info['ns_group'])){
			return false;
		}

		$domain_info = $this->get($domain);
		if(isset($domain_info['domain'])){
			$updata = array();
			if($domain_info['ns_group'] != $new_ns_group){
				$updata['ns_group'] = $new_ns_group;
			}

			if($updata){
				$ret = M("domain")->set_data($updata)->update("domain='{$domain}'");
				if($ret){
					$switch_ns_data = array(
							"uid" =>$domain_info['uid'],
							"domain" =>$domain_info['domain'],
							"old_ns_group" =>$domain_info['ns_group'],
							"new_ns_group" =>$new_ns_group,
						    "type" => $type,
							 "status" => 0,
							"dateline" => $timestamp,

					);
					M("domain_nsswitch")->set_data($switch_ns_data)->add();

					//新的生成
					SDKdns::update_zone($domain_info['domain']);
					//更新自定义线路
					if($domain_info['ns_group'] != $new_ns_group){
						SDKdns::update_cust_acl($domain_info['domain'],$domain_info['ns_group']);
					}
				}
			}
		}
		return true;
	}

	//写入域名操作日志 单例  $data = array(0=a,1=>b)
	public function log($action="",$msg="",$data=array()){
		global $uid,$timestamp;
		if(!isset($data['domain_id']) && !isset($data['domain'])){
			return false;
		}
		$data['domain']	    =	isset($data['domain'])?$data['domain']:"未知域名";
		$data['modi_from']	=	isset($data['modi_from'])?$data['modi_from']:"";
		$data['modi_to']	    =	isset($data['modi_to'])?$data['modi_to']:"";
		$logs = array(
			"domain_id"     	=> $data['domain_id'],
			"domain"  			=> $data['domain'],
			"uid"  				=> $uid?$uid:"0",
			"modi_item"  		=> $action,
			"modi_from"  		=> $data['modi_from'],
			"modi_to"			=> $data['modi_to'],
			"modi_log"  		=> $msg,
			"ipaddr"      		=> tClient::get_ip(0),
			"dateline" 			=> $timestamp,
		);
		M("domain_log")->set_data($logs)->add();
		return true;
	}

	//写入域名操作日志 多例 $data = array(0=>array(1=a,2=b),1=>array(1=a,2=b))
	public function log_more($action="",$data=array()){
		global $uid,$timestamp;
		if($uid){
			$domain_log = new tModel("domain_log");
			foreach($data as $key => $value){
				$value['modi_from']	=	isset($value['modi_from'])?$value['modi_from']:"";
				$value['modi_to']	    =	isset($value['modi_to'])?$value['modi_to']:"";
				if (is_array($value)) {
					$arr = array(
						"domain_id"     	=> $value['domain_id'],
						"domain"  			=> $value['domain'],
						"uid"  					=> $uid,
						"modi_item"  		=> $action,
						"modi_from"  		=> $value['modi_from'],
						"modi_to"			=> $value['modi_to'],
						"modi_log"  		=> $value['modi_log'],
						"ipaddr"      		=> tClient::get_ip(1),
						"dateline" 			=> $timestamp,
					);
					$domain_log->set_data($arr);
					$domain_log->add();
				}
			}
		}
		return true;
	}
	//查询日志
	public function querylog($host = "",$domain="",$start_dateline = 0,$end_dateline = 0){
		$count = 0;
		if($domain){
			$domain_row = $this->get_row("domain='{$domain}'");
			if(isset($domain_row['domain_id'])){
				$ns_group_row = M("@domain_ns_group")->get_cache_by_ns($domain_row['ns_group']);
				if(isset($ns_group_row['servers'])){
					foreach($ns_group_row['servers'] as $key=>$v){
						$condition['domain']   = $domain;
						if($host){
							$condition['host'] = $host;
						}
						$count += SDKdns::query_log($v['mac'],$condition,$start_dateline,$end_dateline);
					}
				}
			}				
		}
		return $count;
		
	}
	//检查NS
	public function check_ns($domain = ""){
		$res = array("status"=>0,"ns"=>"");
		$domain_row = M("@domain")->get($domain);
		if(isset($domain_row['domain'])){
			$ns_group  = $domain_row['ns_group'];
			$inns      = $domain_row['inns'];
			$domain_id = $domain_row['domain_id'];
			$domain    = $domain_row['domain'];

			$res    = DNSapi::ns_in($domain,$ns_group);
			$updata = array();
			if($res['ns']){
				$updata['ns'] = $res['ns'];
			}
			if($res['status'] == 1){
				if($inns == 0){
					$updata['inns'] = 1;
				}
			}elseif($res['status'] == 0){
				if($inns == 1){
					$updata['inns'] = 0;
				}
			}
			if($updata){
				M("domain")->set_data($updata);
				M("domain")->update("domain_id={$domain_id}");
			}
		}
		return $res;
	}
	//检查Expiry
	public function check_expiry($domain = ""){
		$res = array("status"=>0,"expiry"=>"");
		$domain_row = M("@domain")->get($domain);
		if(isset($domain_row['domain'])){
			$domain_id = $domain_row['domain_id'];
			$domain    = $domain_row['domain'];
			$res    = DNSapi::whois_expiry($domain);
			$updata = array();
			if($res['status'] === 1){
				$updata['expiry'] = $res['expiry']?$res['expiry']:"0000-00-00";
				$updata['noreg'] = 0;				
			}elseif($res['status'] === -1){
				$updata['expiry'] = "0000-00-00";
				$updata['noreg'] = 1;
			}
			if($updata){
				M("domain")->set_data($updata);
				M("domain")->update("domain_id={$domain_id}");
			}
		}
		return $res;
	}
	//添加域名队列
	public function queue($domain, $action = 'add_domain',$pri=512, $delay=0, $ttr=30){
		$data = array(
			"action" => $action,
			"data"    => array(
				"domain"    => $domain,
				"dateline"   => time(),
			)
		);
		try{
			$ret = BeanStalk::use_put("dns",$data,$pri,$delay,$ttr);
		}catch(Exception $e){
			
		}
		return $ret;
	}
}
?>