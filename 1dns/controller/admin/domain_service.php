<?php
/**
 * 域名管理
 * by Thinkhu 2014 
 */
class domain_service extends UCAdmin{
	//域名服务套餐列表
	public function service_group(){
		$do = R("do","string");
		$ut = R("ut","int");
		$utypes = C("user")->get_utype();
		$ut = in_array($ut,array_keys($utypes))?$ut:current(array_keys($utypes));
		$pageurl = U("/domain_service/service_group?ut=$ut&do=get");
		if($do == "get"){
			$result['list'] = M("@domain_service")->get_list($ut);
			$result['error']   = 0;
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$ns_group = $ns_group_v = array();
		$ns_group = M("@domain_ns_group")->get_list(false);
		foreach($ns_group as $key => $v){
			$ns_group_v[] = array("key"=>$key,"v"=>$v['name']);
		}
		$this->assign("ns_group",$ns_group);
		$this->assign("ns_group_v",$ns_group_v);
		$this->assign("utypes",$utypes);
		$this->assign("ut",$ut);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//域名服务套餐添加
	public function service_group_edit()
	{
		$service_group = R("service_group","string");
		$service_group_row = array();		
		if($service_group){
			$service_group_row = M("domain_service")->get_row("service_group='{$service_group}'");			
		}
		$service_group_row['old_service_group'] = $service_group;
		if (tUtil::check_hash()) {
			$data = array(
				"name" 			=> R("name", "string"),
				"ns_group" 		=> R("ns_group", "string"),
				"cost1" 		=> R("cost1", "float"),
//				"cost2" 		=> R("cost2", "float"),
				"iscost1"		=> R("iscost1","int"),
				"sort"          => R("sort","int"),
				"user_desc" 	=> R("user_desc", "string"),
				"description" 	=> R("description", "string"),
				"bz" 			=> R("bz", "string"),
				"utype" 		=> R("utype", "int"),
			);
			$data['service_group'] = $service_group;
			$old_service_group = R("old_service_group","string");
			if (empty($data['name'])) {
				tAjax::json_error("套餐名称不能为空！");
			}
			if (!isset($service_group_row['service_group'])) {
				//新增套餐
				if(empty($data['service_group'])){
					tAjax::json_error("组key必须填写");
				}
				$rst = M("domain_service")->set_data($data)->add();
				if ($rst) {
					if($old_service_group && $old_service_group != $service_group){
						M("domain_serviceitem")->set_data(array("service_group"=>$service_group))->update("service_group='{$old_service_group}'");
						M("domain_service")->del("service_group='{$old_service_group}'");
						C("user")->log("替换域名服务套餐","套餐名称：{$data['name']},套餐标识:{$data['service_group']};老套餐标识:{$old_service_group}");
					}else{
						C("user")->log("新增域名服务套餐","套餐名称：{$data['name']},套餐标识:{$data['service_group']};");
					}
					M("@domain_service")->get_cache($service_group,true);
					M("@domain_service")->get_cache_list(0,true);
					M("@domain_service")->get_cache_list(1,true);
					M("@domain_service")->get_cache_list(2,true);
				}				
				tAjax::json(array("error" => 0, "message" => "保存套餐成功！", "callback" => "close"));
			} else {
				if($old_service_group == $service_group){
					M("domain_service")->set_data($data);
					$rst = M("domain_service")->update("service_group='{$service_group}'");
					if ($rst) {
						C("user")->log("更改域名服务套餐","套餐名称：{$data['name']},套餐标识:{$data['service_group']};");
						M("@domain_service")->get_cache($service_group,true);
						M("@domain_service")->get_cache_list(0,true);
						M("@domain_service")->get_cache_list(1,true);
						M("@domain_service")->get_cache_list(2,true);					
					}
					tAjax::json(array("error" => 0, "message" => "保存套餐成功！", "callback" => "close"));
				}else{
					tAjax::json(array("error" =>1, "message" => " 该套餐已存在！", "callback" => "close"));
				}
			}
		} else {

			tAjax::json($service_group_row);
		}
	}
	//域名服务套餐刷新
	public function service_group_refresh(){
		$service_group = R("service_group","string");
		if($service_group){
			M("@domain_service")->get_cache($service_group,true);
		}
		
		M("@domain_service")->get_cache_list(0,true);
		M("@domain_service")->get_cache_list(1,true);
		M("@domain_service")->get_cache_list(2,true);
		tAjax::json_success("刷新缓存成功！");
	}
	//域名服务套餐删除
	public function service_group_del()
	{
		$service_group = R("service_group", "string");
		$ut = R("ut","int");
		$rst = M("domain_service")->del("service_group='{$service_group}'");
		if ($rst) {
			M("domain_serviceitem")->del("service_group='{$service_group}'");
			tAjax::json(array("error" => 0, "message" => "删除成功！", "callback" => "reload"));
		} else {
			tAjax::json_error("删除失败！可能权限不够!");
		}
	}
	//域名服务套餐设置项
	public function service_group_item(){
		$service_group = R("service_group","string");
		if(tUtil::check_hash()){
			$iname   = R("name","string");
			$icnname = R("cnname","string");
			$ivalue  = R("value","string");
			$ibz     = R("bz","string");
			$data = array();
			$iname = is_array($iname) ?$iname: array();
			if($iname){
				foreach($iname as $k=>$v){
					if($v){
						$data[] = array(
							'name'  => $v,
							'bz'  => $ibz[$k],
							'cnname' => $icnname[$k],
							'value' => $ivalue[$k],
							'service_group' => $service_group,
							'sort' =>$k,
						);
					}
				}
			}
			M("domain_serviceitem")->del("service_group='{$service_group}'");
			if(count($data) > 0){
				$ret = M("domain_serviceitem")->add_more($data);
				if ($ret) {
					M("@domain_service")->get_cache($service_group,true);
					tAjax::json(array("error" => 0, "message" => "操作成功！", "callback" => "close"));
				}else{
					tAjax::json_error("操作失败！");
				}
			}
			tAjax::json(array("error"=>0,"message"=>"保存成功！","callback"=>"close"),"json");
		}else{
			if($service_group){
				$res = M("domain_service")->get_row("service_group='{$service_group}'");
			}
			$res['data_arr'] = M("domain_serviceitem")->query("service_group='{$service_group}'","*","sort asc");
			tAjax::json($res);
		}
	}
	//域名服务套餐线路配置
	public function service_group_line(){
		$service_group        = R("service_group","string");
		$service_group_row = M("domain_service")->get_row("service_group='{$service_group}'");

		if(!isset($service_group_row['service_group'])){
			tAjax::json_error("服务套餐没有找到");
		}
		if(tUtil::check_hash()){
			$lines = R("lines","string");
			if (is_array($lines)) {
				$data['acls'] = implode(";",array_unique($lines));
				M("domain_service")->set_data($data)->update("service_group='{$service_group}'");
			}
			tAjax::json(array("error"=>0,"message"=>"保存成功！","callback"=>"close"));
		}else{
			$res = array();
			$res['acls']  =  C("category","domain_acl")->get_level(0,false);
			$res['service_group'] = $service_group;
			$res['in_acls']             = explode(";",$service_group_row['acls']);
			$res['title']                 = "编辑{$service_group_row['name']}线路";
			tAjax::json($res);
		}
	}

	//域名服务器组列表
	public function ns_group(){
		$do = R("do","string");
		$type = R("type","string");
		$type = !in_array($type,array("ns","monitor","web","database"))?"ns":$type;
		$pageurl = U("/domain_service/ns_group?do=get&type={$type}");
		if($do == "get"){
			$result['list'] = M("@domain_ns_group")->get_list(true,"type='".strtoupper($type)."'");
			$result['error']   = 0;
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("type",$type);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//域名服务器组添加
	public function ns_group_edit(){
		$mark = R("mark", "string");
		$id = R("id","int");
		if (tUtil::check_hash()) {
			$data = array(
				"name" 			=> R("name", "string"),
				"ns_group" 		=> R("ns_group", "string"),
				"ns" 			=> R("ns", "string"),
				"type" 			=> R("type", "string"),
			);
			$ip 	 = R("ip", "string");
			$domain  = R("domain", "string");
			$port 	 = R("port", "int");
			$status  = R('status', "int");
			$bz 	 = R('bz', "string");
			$mac     = R("mac","string");
			$map = array();
			$ip = is_array($ip) ? $ip : array();
			if ($ip) {
				foreach ($ip as $k => $v) {
					if ($v) {
						$map[] = array(
							'ip'   	  => $v,
							'port' 	  => $port[$k],
							'domain'  => $domain[$k],
							'ns_group'=> $data['ns_group'],
							'status'  => $status[$k],
							'bz'      => $bz[$k],
							'mac'     => $mac[$k],
							'sort'    => (99-$k),
						);
					}
				}
			}
			if (empty($data['name'])) {
				tAjax::json_error("服务器组名称不能为空！");
			}
			if(empty($data['ns_group'])){
				tAjax::json_error("服务器组标识不能为空！");
			}
			if (empty($mark)) {
				//新增域名服务器组
				if(M("domain_ns_group")->get_one("ns_group='{$data['ns_group']}'","ns_group")){
					tAjax::json_error("服务器组标识已经存在！");
				}
				$rst = M("domain_ns_group")->set_data($data)->add();
				if ($rst) {
					if (count($map) > 0) {
						M("domain_ns_item")->del("ns_group='{$data['ns_group']}'");
						$res = M('domain_ns_item')->add_more($map);
						if ($res) {
							M("@domain_ns_group")->get_cache_by_ns($data['ns_group'],true);
							C("user")->log("新增域名服务器组","组名称：{$data['name']},组标识:{$data['ns_group']};");
							tAjax::json(array("error" => 0, "message" => "新增套餐成功！", "callback" => "close"));
						}
					}
				}
			} else {
				//更改服务器组
				$rst = M("domain_ns_group")->set_data($data)->update("id='{$id}'");
				M("domain_ns_item")->del("ns_group='{$data['ns_group']}'");
				if (count($map) > 0) {
					$res = M('domain_ns_item')->add_more($map);
					if ($res) {
						M("@domain_ns_group")->get_cache_by_ns($data['ns_group'],true);
						C("user")->log("更改域名服务器组","组名称：{$data['name']},组标识:{$data['ns_group']};");
						tAjax::json(array("error" => 0, "message" => "保存成功！", "callback" => "close"));
					}
				}
			}
			tAjax::json(array("error"=>0,"message"=>"保存成功！","callback"=>"close"),"json");
		} else {
			$res = M("domain_ns_group")->get_row("id='{$id}'");
			$res['mark'] = $mark;
			$res['data_arr'] = M("domain_ns_item")->query("ns_group='{$res['ns_group']}'","*","sort asc");
			tAjax::json($res);
		}
	}
	//域名服务器组删除
	public function ns_group_del(){
		tAjax::json_error("暂时不提供删除功能");
		$id = R("id", "int");
		$ns_group = R("ns_group","string");
		M("domain_ns_item")->del("ns_group='{$ns_group}'");
		$rst = M("domain_ns_group")->del("id='{$id}'");
		if ($rst) {
			M("@domain_ns_group")->get_cache_by_ns($ns_group,true);
			tAjax::json(array("error" => 0, "message" => "删除成功！", "callback" => "reload"));
		} else {
			tAjax::json_error("删除失败！可能权限不够!");
		}
	}
	//域名服务器组刷新操作
	public function ns_group_refresh(){
		$ns_group = R("ns_group","string");
		M("@domain_ns_group")->get_cache_by_ns($ns_group,true);
		tAjax::json_success("刷新成功！");
	}
	//域名服务器组操作项
	public function ns_group_btnopra(){
		set_time_limit(3600);
		$host    = R("host", "string");
		$port    = R("port", "int");
		$do 	 = R("do", "string");
		$stringIp = $host . ":" . $port;

		if (!in_array($do, array("start","stop","restart","status","chkstat","restartline","restartallline", "reloadzone~all","reloadisp~all","reloadcustom~all","start_log","query_log","black_log","update_dns"))) {
			tAjax::json_error("非法命令");
		}
		if ($do == "chkstat") {  				//刷新
			$httpStr = "http://" . $stringIp;
			$res = tCurl::get($httpStr, 7);
			
			tAjax::json(array("error" => "0", "status_code" => $res['http_code'], "message" => $res['content']));
		} elseif ($do == "restartline") {	   //重载线路
			$res['list'] = SDKdns::acl_by_host($stringIp);
			tAjax::json($res);
		}elseif ($do == "start_log") {	   //查询启动日志
			$res = SDKdns::dns_log($stringIp,"8jdns.log",2000);
			if($res['status'] == 1){
				$tmp = explode("<br />",nl2br(strtolower($res['msg'])));
				$tmp = array_filter($tmp);
				$tmp = array_reverse($tmp);
				tAjax::json_success(implode("<br />",$tmp));
			}else{
				tAjax::json_error($res['msg']);
			}
		}elseif ($do == "query_log") {	   //查询启动日志
			$ret = SDKdns::dns_log($stringIp,"stat/query.log",2000);
			if($ret['status'] == 1){ 
				$tmp = explode("<br />",nl2br(strtolower($ret['msg'])));
				$tmp = array_filter($tmp);
				$tmp = array_reverse($tmp);
				foreach($tmp as $key => $val){
					$tmp1 = explode("	",$val);
					if(count($tmp1)  >=4){
						$tmp[$key] = "<font class='font-blue'>[{$tmp1[0]}]</font>&nbsp;&nbsp;<font class='font-gray'>查询 <font class='font-black'>{$tmp1[1]}</font> &nbsp;<b class='font-org'>{$tmp1[3]}</b>次</font> ";
					}else{
						unset($tmp[$key]);
					}
				}
				tAjax::json_success(implode("<br />",$tmp));
			}else{
				tAjax::json_error($ret['msg']);
			}
		}elseif ($do == "black_log") {	   //查询启动日志
			$ret = SDKdns::dns_log($stringIp,"stat/black.log",2000);
			if($ret['status'] == 1){ 
				$tmp = explode("<br />",nl2br(strtolower($ret['msg'])));
				$tmp = array_filter($tmp);
				$tmp = array_reverse($tmp);
				foreach($tmp as $key => $val){
					$tmp1 = explode("	",$val);
					if(count($tmp1)  >=4){
						$tmp[$key] = "<font class='font-blue'>[{$tmp1[0]}]</font>&nbsp;&nbsp;<font class='font-green'>{$tmp1[1]} <font class='font-gray2'>{$tmp1[2]}</font> /<b class='font-org'>{$tmp1[3]}</b>次</font> ";
					}else{
						unset($tmp[$key]);
					}
				}
				tAjax::json_success(implode("<br />",$tmp));
			}else{
				tAjax::json_error($ret['msg']);
			}
		}elseif($do == "update_dns"){
			$parm  = R("parm","string");
			$dns_versions = App::get_conf("data_config.dns_version");
			if(!in_array($parm,$dns_versions)){
				tAjax::json_error("非法更新!");
			}
			$ret = SDKdns::dns_server_shcmd($stringIp,"update_dns",$parm);
			if ($ret['status'] == 1) {
				tAjax::json(array("error" => "0", "message" => $ret['msg']?(nl2br(is_array($ret['msg'])?$ret['msg'][1]:$ret['msg'])):""));
			} else {
				tAjax::json(array("error" => "1", "message" => $ret['msg']));
			}
		}elseif ($do == "restartallline") {    //重载所有线路
			$ns_group = R("ns_group", "string");
			$res['list'] = SDKdns::acl_by_nsgroup($ns_group);
			tAjax::json($res);
		} else {
			$tmp  = explode("~",$do);
			$tmp[1] = isset($tmp[1])?$tmp[1]:""; //重载配置 ,
			$ret = SDKdns::dns_mserver($stringIp, $tmp[0],$tmp[1]);
			//针对不同命令执行不同操作
			if(in_array($tmp[0],array("start","stop","restart","reloadzone","reloadisp","reloadcustom"))){
				C("user")->log("8JDNSM操作","操作命令:{$do},{$stringIp}");
			}elseif($tmp[0] == "status"){
				$updata = array();
				$mac = SDKdns::dns_mac($stringIp);

				$item_row = M("domain_ns_item")->get_row("ip='{$host}'");
				if($mac['status'] == 1 && strlen($mac['msg']) == 17 && isset($item_row['ip']) && empty($item_row['mac'])){
					$updata['mac'] = $mac['msg'];
				}

				if($ret['status'] == 1){
					$msg = is_array($ret['msg'])?$ret['msg'][1]:$ret['msg'];
					$tmp = explode("<br />",nl2br(strtolower($msg)));
					$tmp = array_filter($tmp);
					foreach($tmp as $val){
						$val  = strtolower($val);
						$tmp1 = explode(":",$val);
						if(count($tmp1)>1){
							$tmp1[0] = trim(str_replace("num", "", $tmp1[0]));
							if (in_array($tmp1[0],array('zone','isp','custom'))) {
								$updata[$tmp1[0]."s"] = trim($tmp1[1]);
								$t_tmp = explode("/",$tmp1[1]);
								if (isset($t_tmp[1])) {
									$t_tmp2 = explode("_",$t_tmp[1]);
									if (isset($t_tmp2[0]) && is_numeric($t_tmp2[0])) {
										$updata[$tmp1[0]."s"] = $t_tmp2[0];
									}
								}
							}elseif (in_array($tmp1[0],array("version"))){
								$updata[$tmp1[0]] = trim($tmp1[1]);
							}
						}
					}
				}
				if($updata){
					M("domain_ns_item")->set_data($updata)->update("ip='{$host}'");
				}
			}
			
			if ($ret['status'] == 1) {
				$return = array(
					"error" => 0,
					"message" => ($ret['msg']?(nl2br(is_array($ret['msg'])?$ret['msg'][1]:$ret['msg'])):""),
					"data" => (isset($updata)?$updata:array()),
				);
				tAjax::json($return);
			} else {
				tAjax::json(array("error" => "1", "message" => $ret['msg']));
			}
		}		
	}
	//域名解析记录列表
	public function dns_record(){
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/domain_service/dns_record?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"rrtype"   			=> R("rrtype","string"),
			"domain_id"				=> R("domain_id","int"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND uid='{$v}' OR domain LIKE '%{$v}%' OR domain_cn LIKE '%{$v}%' ";
					}else{
						$where .= $v?" AND (domain LIKE '%{$v}%' OR domain_cn LIKE '%{$v}%')":"";
					}
					break;
				case "service_group":
					$where .= $v?(" AND service_group = '{$v}'"):"";
					break;
				case "uid":
					$where .= $v?(" AND uid = ".$v):"";
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}
		//查询结束
		if($do == "get"){
			$data['page']  = $page;
			$data['where'] = $where;
			$data['pagesize'] = 20;
			$data['order'] = "lastupdate DESC";
			$result = M("@domain_record")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		//套餐组中文名显示开始
		$rrtype = array();
		$config_site = tCache::read("data_config_site");
		foreach($config_site as $key => $val){
			if ($key == 'RRtype') {
				foreach($val as $k=>$v){
					$rrtype[] = array("key"=>$k,"v"=>$v);
				}
			}
		}
		$this->assign("rrtype",$rrtype);
		//套餐组中文名显示结束
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
}
?>
