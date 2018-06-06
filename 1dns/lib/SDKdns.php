<?php
class SDKdns{
	private static $key   = "d3352d339e245e44c684da4bb94cdc5b";
	private static $https = "http://";
	private static $timeout = 20;
	private static function sign($url = "",$data = array(),$encrypt_key = ""){
		$encrypt_key = empty($encrypt_key)?self::$key:$encrypt_key;
		ksort($data);
		$data['timestamp'] = time()+7200;
		$datastr = "";
		foreach($data as $k=>$v){
			$datastr .= "&{$k}={$v}";
		}
		$datastr  = substr($datastr,1);
		$datastr .= "&checkcode=".md5($encrypt_key.$datastr.$encrypt_key);
		$datastr .= "&rand=".rand(10000,99999);

		return $url.($datastr?"?{$datastr}":"");
	}
	public static function get($host,$uri = "/",$data = array(), $encrypt_key = ""){
		$url  = self::$https.$host.$uri;
		$url  = self::sign($url,$data,$encrypt_key);
		try{
			$ret  = tCurl::get($url,self::$timeout);
		}catch(Exception $e){
			$ret = array("http_code"=>200,"content"=>"");
		}
		if($ret['http_code'] == 200){
			return $ret['content'];
		}else{
			return "HTTPerror {$ret['http_code']}";
		}
	}
	public static function post($host,$uri = "/",$data = array(),$req_data = array(),$encrypt_key = ""){
		$url  = self::$https.$host.$uri;
		$url  = self::sign($url,$data,$encrypt_key);
		try{
			$ret  = tCurl::post($url,$req_data,self::$timeout);
		}catch(Exception $e){
			$ret = array("http_code"=>200,"content"=>"");
		}

		if($ret['http_code'] == 200){
			return $ret['content'];
		}else{
			return "HTTPerror {$ret['http_code']}";
		}
	}
	//
	public static function dns_server($host,$cmd = "reload"){
		$uri = "/DnsServer/Cmd";
		$ret = JSON::decode(self::post($host,$uri,array("cmd"=>$cmd)));
		if(isset($ret['status'])){
			return $ret;
		}else{
			return array("status"=>0,"msg"=>$ret);
		}
	}
	public static function dns_mserver($host,$mcmd = "reload",$parm = ""){
		$uri = "/DnsServer/MCmd";
		$ret = JSON::decode(self::post($host,$uri,array("mcmd"=>$mcmd,"parm"=>$parm)));
		if(isset($ret['status'])){
			return $ret;
		}else{
			return array("status"=>0,"msg"=>$ret);
		}
	}
	public static function dns_mac($host){
		$uri = "/DnsServer/GetMac";
		$ret = JSON::decode(self::get($host,$uri,array("host"=>$host)));
		if(isset($ret['status'])){
			if($ret['status'] == 1 && strlen($ret['msg']) == 17){
				//M("@domain_ns_group")->update_server_mac($host,$ret['msg']);
			}
			return $ret;
		}else{
			return array("status"=>0,"msg"=>$ret);
		}
	}
	public static function dns_server_shcmd($host,$cmd,$parm=""){
		$uri = "/DnsServer/ShCmd";
		$ret = JSON::decode(self::get($host,$uri,array("cmd"=>$cmd,"parm"=>$parm)));
		if(isset($ret['status'])){
			return $ret;
		}else{
			return array("status"=>0,"msg"=>$ret);
		}
	}
	//获取查询日志
	public static function dns_log($host,$flog="8jdns.log",$line=500){
		$uri = "/Dns/QueryLog";
		$ret = JSON::decode(self::get($host,$uri,array("flog"=>$flog,"line"=>$line)));
		if(isset($ret['status'])){
			return $ret;
		}else{
			return array("status"=>0,"msg"=>$ret);
		}
	}
	public static function dns($host,$domain = "",$ns_group = "free"){
		$uri = "/Dns/Zone";
		$ret = JSON::decode(self::post($host,$uri,array("domain"=>$domain,"ns_group"=>$ns_group)));
		if(isset($ret['status'])){
			return $ret;
		}else{
			return array("status"=>0,"msg"=>$ret);
		}
	}
	public static function acl($host,$acl,$iptype = 0){
		$uri = "/DnsServer/Acl";
		$ret = JSON::decode(self::post($host,$uri,array("acl"=>$acl,"iptype"=>$iptype),10));
		if(isset($ret['status'])){
			return $ret;
		}else{
			return array("status"=>0,"msg"=>$ret);
		}
	}
	//根据单个主机生成所有线路
	public static function acl_by_host($host,$iptype = 0){
		$return = array();
		$cls_cate = new cls_category("domain_acl");
		$acls = $cls_cate->get(0,0,0,0);
		foreach($acls as $key=>$v){
			M("@domain_aclip")->write_romate_ipv4($v['ident']);
			$return[] = array(
				"acl" => $v['ident'],
				"ret" => self::acl($host,$v['ident'],$iptype)
			);
		}
		return $return;		
	}
	//根据线路生成所有服务器组线路
	public static function acl_by_acl($acl,$iptype = 0){
		$return = array();
		//获取所有服务器组
		$ns_group_list = M("@domain_ns_group")->get_list();
		foreach($ns_group_list as $key=>$v){
			if($v['type'] === 'NS'){
				foreach($v['data'] as $server){
					$host = "{$server['ip']}:{$server['port']}";
					$return[] = array(
						"ns_group" => $v['ns_group'],
						"name"     => $v['name'],
						"ns"     => $v['ns'],
						"host" => $host,
						"ret"  => self::acl($host,$acl,$iptype)
					);
				}
			}			
		}
		return $return;
	}
	//根据服务器组生成所有线路
	public static function acl_by_nsgroup($ns_group,$iptype = 0){
		$return = array();
		$ns_group_row = M("@domain_ns_group")->get_cache_by_ns($ns_group);
		if(isset($ns_group_row['servers'])){
			$ns_group_server = $ns_group_row['servers'];
			if($ns_group_server){
				$cls_cate = new cls_category("domain_acl");
				$acls = $cls_cate->get(0,0,0,0);
				foreach($ns_group_server as $server){
					$host = "{$server['ip']}:{$server['port']}";
					$tmp  = array(
						"host" => $host,
						"rets" => array()
					);
					foreach($acls as $key=>$v){
						$tmp['rets'][] = array(
							"acl"  => $v['ident'],
							"ret"  => self::acl($host,$v['ident'],$iptype)
						);
					}
					$return[] = $tmp;
				}
			}
		}
		return $return;		
	}

	//根据域名刷新组服务器的
	public static function make_record($domain = "",$ns_group = "free"){
		$return = array();
		$ns_group = $ns_group;
		$ns_group_row = M("@domain_ns_group")->get_cache_by_ns($ns_group);
		if(isset($ns_group_row['servers'])){
			$ns_group_server = $ns_group_row['servers'];
			if($ns_group_server){
				foreach($ns_group_server as $server){
					$host = "{$server['ip']}:{$server['port']}";
					$return[$host] = self::dns($host,$domain,$ns_group);
				}
			}
		}
		return $return;
	}
	//添加远程zone
	public static function add_zone($domain = ""){
		$ret = 1;
		try{
			$domain_row = tMongo::get_one("domains",array("domain"=>$domain));
		}catch(Exception $e){
			$domain_row = array();
		}
		if(!isset($domain_row['domain'])){
			$ns_group = "free";
			$ns_group_info  = M("@domain_ns_group")->get_cache_by_ns($ns_group);
			//找到服务器组
			if(isset($ns_group_info['ns'])){
				$ns = $ns_group_info['ns'];
	            try{
					$res = tMongo::add("domains",array(
									    "domain"   => $domain,
										"ttl"      => 600,
										"qps"      => 50000,
										"records"  => array(),
										"ns_group" => $ns_group,
										"ns"       => $ns
					));
					if(!$res){
						$ret = 0;
					}
				}catch(Exception $e){
					$ret = 0;
				}
			}
		}
		return $ret;
	}
	//删除远程zone
	public static function del_zone($domain = ""){
		$ret = 1;
		try{
			$domain_row = tMongo::get_one("domains",array("domain"=>$domain));
		}catch(Exception $e){
			$domain_row = array();
			return -9;
		}
		if(isset($domain_row['domain'])){
			$ns_group = $domain_row['ns_group'];
			try{
				$res = tMongo::del("domains",array("domain"=>$domain));
				if(!$res){
					$ret = 0;
				}
			}catch(Exception $e){
				$ret = 1;
			}
			$result   = self::make_record($domain,$ns_group);
			if($result){
				foreach($result as $v){
					$ret = 1 & $v['status'];
				}
			}
		}
		return $ret;
	}
	//更新远程zone
	public static function update_zone($domain = ""){
		$ret        = 1;
		try{
			$domain_row = M("@domain")->get($domain);
		}catch(Exception $e){
			X("tLog","file")->write("operation",array("get_domain_row '{$domain}' error:".$e->getMessage()));
			return -9;
		}
		if(isset($domain_row['domain_id']) && $domain_row['status'] == 1 && $domain_row['indel'] == 0){
			$ns_group       = $domain_row['ns_group'];
			$ttl            = $domain_row['ttl'];
			$qps            = $domain_row['qps'];
			$service_group  = $domain_row['service_group'];
			$ns_group_info  = M("@domain_ns_group")->get_cache_by_ns($ns_group);
			//域名所属服务器组找到
			if(isset($ns_group_info['ns'])){
				$ns          = $ns_group_info['ns'];
				//解析记录
				try{
	            	$records = M("@domain_record")->fetch($domain);
	            }catch(Exception $e){
	            	X("tLog","file")->write("operation",array("fetch_record '{$domain}' error:".$e->getMessage()));
					return -9;
				}
	            //mongo处理
	            try{
					$ret     = tMongo::get_one("domains",array("domain"=>$domain));
					if(!isset($ret['domain'])){							
						$ret = tMongo::add("domains",array(
							"domain"   => $domain,
							"ttl"      => $ttl,
							"qps"      => $qps,
							"records"  => $records,
							"ns_group" => $ns_group,
							"ns"       => $ns
						));
					}else{
						$ret = tMongo::update("domains",array("domain" => $domain),array(
						    "domain"   => $domain,
							"ttl"      => $ttl,
							"qps"      => $qps,
							"records"  => $records,
							"ns_group" => $ns_group,
							"ns"       => $ns
						));
					}
				}catch(Exception $e){
					$ret = 0;
				}
				//生成远程记录
				try{
					$result = self::make_record($domain,$ns_group);
					if($result){
						foreach($result as $v){
							$ret = 1 & $v['status'];
						}
					}
				}catch(Exception $e){
					$ret = 0;
				}
			}				
		}else{
			$ret = self::del_zone($domain);
		}
		return $ret;
	}
	//单个服务器的线路刷新
	public static function cust_acl($host,$acl,$ns_group){
		$uri = "/DnsServer/CustAcl";
		$ret = JSON::decode(self::post($host,$uri,array("acl"=>$acl,"ns_group"=>$ns_group)));
		if(isset($ret['status'])){
			return $ret;
		}else{
			return array("status"=>0,"msg"=>$ret);
		}
	}
	//根据域名刷新组服务器的自定义线路
	public static function make_cust_acl($acl = "",$ns_group = "free"){
		$return = array();
		$ns_group = $ns_group;
		$ns_group_row = M("@domain_ns_group")->get_cache_by_ns($ns_group);
		if(isset($ns_group_row['servers'])){
			$ns_group_server = $ns_group_row['servers'];
			if($ns_group_server){
				foreach($ns_group_server as $server){
					$host = "{$server['ip']}:{$server['port']}";
					$return[$host] = self::cust_acl($host,$acl,$ns_group);
				}
			}
		}
		return $return;
	}
	//刷新自定义线路
	public static function update_cust_acl($domain = "",$ns_group=""){
		$domain_row = M("@domain")->get($domain);
		$delete     = false;
		if(isset($domain_row['domain_id'])){
			$domain_ns_group       = $domain_row['ns_group'];
		}else{
			$domain_ns_group = $ns_group;
			$delete = true;
		}

		$acllist = M("@domain_acl_set")->query("domain='{$domain}'","id,status");
		if($acllist){
			foreach($acllist as $key=>$v){
				$acl = M("@domain_acl_set")->write_romate($v['id'],$domain_ns_group,$delete);
                
                //删除老ns 上自定义线路
				if($ns_group){
					self::make_cust_acl($acl,$ns_group);
				}

				//生成新ns 上自定义线路
				if($domain_ns_group != $ns_group){
					self::make_cust_acl($acl,$domain_ns_group);
				}	
			}
		}
		return 1;
	}
	//获取查询日志
	public static function query_log($mac="",$where=array(),$start_dateline = 0,$end_dateline = 0,$groupby=null){
		global $timestamp;
		if(empty($mac)){return 0;}
		$mongo_dbinfo = App::get_conf("db.mongo_log");
		$collect_name = "qlog".str_replace(":", "_", $mac);

		$start_dateline = $start_dateline?$start_dateline:strtotime(tTime::get_datetime("Y-m-d",$timestamp));
		$end_dateline = $end_dateline?$end_dateline:($start_dateline+86400);
		//条件
		$condition = array(
			"dateline"=>array('$gte'=>$start_dateline,'$lt'=>$end_dateline)
		);
		if($where){
			$condition = array_merge($condition,$where);
		}
		//集合
		$groups = array(
			"_id" => $groupby,
			"sums"=> array('$sum'=>'$querytimes'),
		);
		//字段
		$fields = array("_id"=>1,"sums"=>1);
		//排序
		$sorts  = array("sums"=>-1);
		$ret = tMongo::group($collect_name,$groups,
					$condition,
					array(),				
					$fields,
					$sorts,$mongo_dbinfo);
		if($groupby === null){
			$num = isset($ret['result'][0]['sums'])?$ret['result'][0]['sums']:0;
		}else{
			$num = array();
			if(isset($ret['result']) && $ret['result']){
				foreach($ret['result'] as $v){
					$num[$v['_id']] = $v['sums'];
				}
			}
		}
		return $num;
	}
}
?>