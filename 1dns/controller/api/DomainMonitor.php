<?php
class DomainMonitor extends API{
	public function __construct(){
		parent::__construct('DomainMonitor');
	}
	//实时监控列表
	public function Monitor(){
		global $uid;
		$this->ChkUser();
		
		$page     		= R("page","int");
		$page 		 	= $page?$page:1;
		$keyword   	= R("keyword","string");
		$do 				= R("do","string");

		//查询搜索
		$where   = "uid = '{$uid}'";
		$where .= " AND (monitor_http LIKE '%{$keyword}%')";

		if ($do == 'edit') {
			$domain  = R("domain","string");
			$RRname = R("RRname","string","@");
			if($domain){
				$where .= " AND (domain = '{$domain}')";
			}
			if($RRname){
				$where .= " AND (RRname ='{$RRname}')";
			}
		}

		$data  = array(
			'where'     =>$where,
			'page'		 =>$page,
			'order'      => "monitor_id DESC",
			'pagesize' => 16,
		);
		$res = M("@domain_monitor")->get_list($data,"__page__?");

		$res['pagebar'] = tFun::pagebar_js($res['pagebar'],"__page__?","load_monitor_list");

		$this->respons_success("加载成功",$res);
	}
	//添加域名监控
	public function Add(){
		global $uid,$timestamp;
		$this->ChkUser();

		$domain    = R("domain","string");
		$domain_en = $domain;
		if(tValidate::is_cn($domain)){
			App::uselib("tools.idna_convert");
			$idna_convert_obj = new idna_convert();
			$domain_en = $idna_convert_obj->encode($domain);
		}
		$notify_otheremail = trim(R("notify_otheremail","string"),";");
		$notify_othermobile = trim(R("notify_othermobile","string"),";");
		$userinfo = C("user")->get_cache_userinfo($uid);
		$data = array(
			"uid"								=>$uid,
			'dateline'							=>$timestamp,
			'monitor_node'				=>"monitor_free",
			"RRname" 						=>R("RRname","string","@"),
			"monitor_type" 				=>R("monitor_type","int"),
			"monitor_port" 				=>R("monitor_port","int"),
			"monitor_path" 				=>R("monitor_path","string"),
			"monitor_node_type" 		=>R("monitor_node_type","int"),
			"monitor_rate" 				=>R("monitor_rate","int"),
			"monitor_switch" 			=>R("monitor_switch","int"),
			"notify_email" 				=>R("notify_email","int"),
			"notify_mobile" 				=>R("notify_mobile","int"),
			"notify_weixin" 				=>R("notify_weixin","int"),
			"notify_otheremail" 		=>$notify_otheremail,
			"notify_othermobile" 		=>$notify_othermobile,
		);
		$data['monitor_http'] = "http";

		//判断监控频率是否合法
		$service_group = M("domain")->get_one("domain = '{$domain}'","service_group");
		if ($service_group) {
			$monitor_pl = tCache::read("service_group_".$service_group);
			if ($data['monitor_rate'] < $monitor_pl['data']['monitorFreq']['value']) {
				$this->respons_error("非法的监控频率,请查看套餐对应值！");
			}
			$monitor_num = M("domain_monitor")->get_one("uid = '{$uid}' AND domain = '{$domain}'","count('monitor_id')");
			if ($monitor_num >= $monitor_pl['data']['monitorTask']['value']) {
				$this->respons_error("监控任务数超出,请查看套餐对应值！");
			}
		}

		//判断域名是否被绑定
		if (M("domain_bind")->get_one("uid = '{$uid}' AND domain_bind = '{$domain}'","count(id)")) {
			$this->respons_error("此域名已被绑定，不能添加监控！");
		}

		//其它邮箱，手机判断,判断邮箱格式是否正确
		if ($notify_otheremail) {
			$tmp_o_email = explode(";",$notify_otheremail);
			if (count($tmp_o_email) > 5) {
				$this->respons_error("其它邮箱最多添加五个！");
			}
			foreach ($tmp_o_email as $key=>$val) {
				if(!empty($val) && !tValidate::is_email($val)){
					$this->respons_error("非法的邮箱地址");
				}
				//判断其它邮件中是否包含注册邮件
				if (!empty($val)) {
					if (isset($userinfo['email']) && $userinfo['email'] == $val) {
						$this->respons_error("其它邮件中不能包含账户邮件");
					}
				}
			}
		}
		//判断手机格式是否正确
		if ($notify_othermobile) {
			$tmp_o_mobile = explode(";",$notify_othermobile);
			if (count($tmp_o_mobile) > 5) {
				$this->respons_error("其它手机最多添加五个！");
			}
			foreach ($tmp_o_mobile as $key=>$val) {
				if(!empty($val) && !tValidate::is_mobile($val)){
					$this->respons_error("非法的手机格式");
				}
				//判断其它手机中是否包含认证手机
				if (!empty($val)) {
					if (isset($userinfo['mobile']) && $userinfo['mobile'] == $val) {
						$this->respons_error("其它手机中不能包含账户手机");
					}
				}
			}
		}

		$monitor_item = array();
		$ips          = R("ips","string");
		$acls         = R("acls","string");
		$beiyong_ips  = R("beiyong_ips","string");
		$record_ids   = R("record_ids","string");
		if($ips && $acls){
			$tem_ips   = explode(",", $ips);
			$tem_acls = explode(",", $acls);
			$tem_ip2  = explode(",", $beiyong_ips);	//备用ip，可选项
			$tem_record_id = explode(",", $record_ids);
			if (count($tem_ips) != count($tem_acls)) {
				$this->respons_error("域名线路，IP地址有误");
			}
			foreach ($tem_ips as $key => $val) {
				$monitor_item[] = array(
					"record_id" 		=> $tem_record_id[$key],
					"acl" 				=> $tem_acls[$key],
					"ip" 			    => $val ,
					"ip2" 				=> $tem_ip2[$key],
					"status" 			=> 1,
				);
			}
			if(count($monitor_item) == 0){
				$this->respons_error("域名监控记录错误");
			}

			$data['domain'] = R("domain","string");
			if(empty($data['domain'])){
				$this->respons_error("添加监控域名丢失！");
			}
			if(M("domain")->get_one("domain='{$data['domain']}' AND uid='{$uid}'","count(domain_id)") <=0){
				$this->respons_error("非法添加！");
			}
			//提交监控,如果监控域名已经存在，则直接添加监控记录
			$monitor_row = M("domain_monitor")->get_row("domain='{$data['domain']}' AND RRname='{$data['RRname']}' AND uid='{$uid}'");
			if (!isset($monitor_row['monitor_id'])) {
				$ret = M("domain_monitor")->set_data($data)->add();
				if($ret) {
					foreach ($monitor_item as $k=>$v) {
						$monitor_item[$k]['monitor_id'] = $ret;
					}
				}
			}else{
				foreach ($monitor_item as $k=>$v) {
					$monitor_item[$k]['monitor_id'] = $monitor_row['monitor_id'];
				}
				
			}
			//提交监控记录项
			$ret2 = M("domain_monitor_record")->add_more($monitor_item);
		}
		if ($ret2) {
			$this->respons_success("添加监控成功");
		}else{
			$this->respons_error("添加监控失败");
		}
	}
	//编辑域名监控
	public function Edit(){
		global $uid,$timestamp;
		$do         = R("do","string");
		$monitor_id = R("monitor_id","int");
		if (!$monitor_id) {
			$this->respons_error("监控项目不存在");
		}
		$userinfo = C("user")->get_cache_userinfo($uid);
		switch($do){
			case  "node":
				$data = array(
					'monitor_node_type' =>R("monitor_node_type","int")
				);
				break;
			case "rate":
				$data = array(
					'monitor_rate' =>R("monitor_rate","int")
				);
				//判断监控频率是否合法
				$domain = M("domain_monitor")->get_one("monitor_id = '{$monitor_id}'","domain");
				$service_group = M("domain")->get_one("domain = '{$domain}'","service_group");
				if ($service_group) {
					$monitor_pl = tCache::read("service_group_".$service_group);
					if ($data['monitor_rate'] < $monitor_pl['data']['monitorFreq']['value']) {
						$this->respons_error("非法的监控频率！");
					}
				}
				break;
			case "task":
				$domain = R("domain","string");
				$RRname = R("RRname","string","@");
				$data = array(
					'monitor_type' =>R("monitor_type","int"),
					'monitor_http' =>$RRname.".".$domain,
					'monitor_port' =>R("monitor_port","int"),
					'monitor_path' =>R("monitor_path","string")
				);
				break;
			case "rule":
				$monitor_switch = R("monitor_switch","int");
				$data = array(
					'monitor_switch' =>$monitor_switch,
				);
				if ($monitor_switch == 4) {//智能切换
					//获取record_id 备用IP
					$record_ids  = R("record_ids","string");
					$beiyong_ips = R("beiyong_ips","string");
					$tem_ips  	 = explode(",", $beiyong_ips);
					$tem_record_id = explode(",", $record_ids);

					//判断备用IP合法性
					foreach ($tem_ips as $key => $val) {
						if(!empty($val) && !tValidate::is_ip($val)){
							$this->respons_error("非法的ip地址");
						}
					}


                    //修改主监控
					$ret = M("domain_monitor")
							->set_data($data)
							->update("uid = '{$uid}' AND monitor_id='{$monitor_id}'");
					//修改主监控记录									
					foreach($tem_record_id as $key=>$v){
						M("domain_monitor_record")
						->set_data(array("ip2"=>$tem_ips[$key]))
						->update("monitor_id='{$monitor_id}' AND record_id = '{$v}'");
					}										
					$this->respons_success("保存成功");
				}else{
					M("domain_monitor")
					->set_data($data)
					->update("uid = '{$uid}' AND monitor_id='{$monitor_id}'");
					$this->respons_success("保存成功");
				}
				break;
			case "info":
				$notify_otheremail = trim(R("notify_otheremail","string"),";");
				$notify_othermobile = trim(R("notify_othermobile","string"),";");
				//判断邮箱格式是否正确
				$tmp_o_email = explode(";",$notify_otheremail);
				if (count($tmp_o_email) > 5) {
					$this->respons_error("其它邮箱最多添加五个！");
				}
				foreach ($tmp_o_email as $key=>$val) {
					if(!empty($val) && !tValidate::is_email($val)){
						$this->respons_error("非法的邮箱地址");
					}
					//判断其它邮件中是否包含注册邮件
					if (!empty($val)) {
						if (isset($userinfo['email']) && $userinfo['email'] == $val) {
							$this->respons_error("其它邮件中不能包含账户邮件");
						}
					}
				}
				//判断手机格式是否正确
				$tmp_o_mobile = explode(";",$notify_othermobile);
				if (count($tmp_o_mobile) > 5) {
					$this->respons_error("其它手机最多添加五个！");
				}
				foreach ($tmp_o_mobile as $key=>$val) {
					if(!empty($val) && !tValidate::is_mobile($val)){
						$this->respons_error("非法的手机格式");
					}
					//判断其它手机中是否包含认证手机
					if (!empty($val)) {
						if (isset($userinfo['mobile']) && $userinfo['mobile'] == $val) {
							$this->respons_error("其它手机中不能包含账户手机");
						}
					}
				}
				$data = array(
					'notify_email' 				=> R("notify_email","int"),
					"notify_mobile" 			=> R("notify_mobile","int"),
					"notify_weixin" 			=> R("notify_weixin","int"),
					"notify_otheremail" 	    => $notify_otheremail,
					"notify_othermobile"        => $notify_othermobile,
				);

				break;
			default:
				$this->respons_error("非法操作");
		}
		M("domain_monitor")->set_data($data)->update("uid = '{$uid}' AND monitor_id='{$monitor_id}'");
		$this->respons_success("保存成功");
	}
	//删除域名监控
	public function Del(){
		global $timestamp,$uid;
		$this->ChkUser();

		$monitor_id = R("monitor_id","int");
		$res  = M("domain_monitor")->get_row("monitor_id='{$monitor_id}' AND uid={$uid}");
		if (!isset($res['domain'])) {
			$this->respons_error("域名不存在");
		}
		$rst   = M("domain_monitor")->del("monitor_id={$monitor_id}");
		if ($rst) {
			//同时删除域名记录
			M("domain_monitor_record")->del("monitor_id={$monitor_id}");
			//同时删除报警信息
			M("domain_monitor_error")->del("monitor_id={$monitor_id}");
			$this->respons_success("删除成功",$res['monitor_rate']);
		} else {
			$this->respons_error("删除失败");
		}
	}
	//域名监控详情
	public function MonitorDetail(){
		global $uid;
		$this->ChkUser();
		//查询搜索
		$where   = "uid = '{$uid}'";
		$domain  = R("domain","string");
		$RRname = R("RRname","string","@");
		if($domain){
			$where .= " AND (domain = '{$domain}')";
		}
		if($RRname){
			$where .= " AND (RRname ='{$RRname}')";
		}

		$data  = array(
			'where'     =>$where,
			'order'      => "monitor_id DESC",
			'pagesize' => 16,
		);
		$res = M("@domain_monitor")->get_list($data);
		$res['list']	= $res['list']?:"无监控详情";
		$this->respons_success("加载成功",$res);
	}
	//暂停启用域名监控
	public function MonitorOption(){
		global $timestamp,$uid;
		$this->ChkUser();

		$monitor_id   = R("monitor_id","int");
		$status       = R("status", "int");
		$res  = M("domain_monitor")->get_row("monitor_id='{$monitor_id}' AND uid={$uid}");
		if (!isset($res['domain'])) {
			$this->respons_error("域名不存在");
		}
		if(!in_array($status,array(0,1))){
			$this->respons_error("非法操作");
		}
		$num = M("domain_monitor")->set_data(array("status"=>$status))->update("uid=$uid  AND monitor_id ={$monitor_id}");
		if ($num == 0){
			$this->respons_error("操作失败");
		}else{
			$this->respons_success("成功".($status == 1?"启用":"暂停")."域名",$res['monitor_rate']);
		}
	}
	//报警信息
	public function MonitorWarning(){
		global $uid;
		$page     		= R("page","int");
		$page 		 	= $page?$page:1;
		$keyword   	= R("keyword","string");

		//查询搜索
		$where  = "uid = {$uid}";
		$where .= " AND ((RRname LIKE '%{$keyword}%') OR (domain LIKE '%{$keyword}%') OR (reason LIKE '%{$keyword}%') OR (ip LIKE '%{$keyword}%'))";

		$data  = array(
			'where'     =>$where,
			'page'		 =>$page,
			'order'      => "startdateline DESC",
			'pagesize' => 16,
		);
		$res = M("@domain_monitor_error")->get_list($data,"__page__?");
		$ns_group = M("@domain_ns_group")->get_cache_by_ns("monitor_free");
		if (is_array($res['list'])) {
			foreach($res['list'] as $key=>$v){
				$res['list'][$key]['distime_str'] = ($v['enddateline'] > $v['startdateline'])?tTime::format_distime($v['enddateline'] - $v['startdateline']):"-";
				$res['list'][$key]['monitor_nodecn'] = $ns_group['servers'][($v['monitor_node_id']-1)]['domain'];
			}
		}
		$res['pagebar'] = tFun::pagebar_js($res['pagebar'],"__page__?","load_monitor_warning_list");

		$this->respons_success("加载成功",$res);
	}
	//删除报警信息
	public function WarningDel(){
		global $timestamp,$uid;

		$id = R("id","int");

		$res  = M("domain_monitor_error")->get_row("id='{$id}' AND uid={$uid}");
		if (!isset($res['monitor_id'])) {
			$this->respons_error("报警信息不存在");
		}
		$rst   = M("domain_monitor_error")->del("id={$id}");
		if ($rst) {

			$this->respons_success("删除成功");
		} else {
			$this->respons_error("删除失败");
		}
	}
	//清空报警信息
	public function WarningClear(){
		global $uid;

		$res = M("domain_monitor_error")->del("uid='{$uid}'");
		if ($res) {
			$this->respons_success("清空报警信息成功");
		}else{
			$this->respons_error("清空报警信息失败");
		}
	}
	//检查域名记录
	public function CheckDomainRecord(){
		global $uid;

		$domain = R("domain","string");
		$record = R("record","string","@");
		$record = strtolower($record);
		if (empty($domain)) {
			$this->respons_error("域名不能为空！");
		}

		if(tValidate::is_cn($domain)){
			$ret = M("domain")->get_row("domain_cn='{$domain}' AND uid=$uid");
			if(!isset($ret['domain_id'])){
				$this->_msg("域名不存在！");
			}
			$domain = $ret['domain'];
		}

		if (!(M("domain_record")->get_one("domain='{$domain}'","count(*)"))) {
			$this->respons_error("该域名下没有可以监控的记录，请先添加记录");
		}
		if (empty($record)) {
			$this->respons_error("主机记录值不能为空！");
		}

		$res = M("domain_record")->get_row("domain='{$domain}' AND RRname='{$record}'");
		if (!isset($res['domain_id'])) {
			$this->respons_error("您还没有添加此域名的主机记录值，请先添加！");
		}

		$this->respons_success("ok");
	}
	//监控日志详情
	public function GetMapsData(){
		global $uid;
		$record_id = R("record_id","int");
		if (empty($record_id)) {
			$this->respons_error("false");
		}
		//监控节点
		$monitor_record = M("domain_monitor_record")->get_row("record_id='{$record_id}'");
		if (!isset($monitor_record['monitor_id'])) {
			$this->respons_error("false");
		}
		$monitor = M("domain_monitor")->get_row("monitor_id = '{$monitor_record['monitor_id']}' AND uid = '{$uid}'");
		if (!isset($monitor['domain'])) {
			$this->respons_error("false");
		}
		$ns_group = M("@domain_ns_group")->get_cache_by_ns($monitor['monitor_node']);

		$node_ids   = array(1,2,3);
		$result = array();
		foreach($node_ids as $node_id){
			$result[$node_id] = M("@domain_monitor")->querylog($record_id,$node_id);
		}
		$result['monitor_node'] = $ns_group['servers'];
		$this->respons_success("ok",$result);
	}

}
?>
