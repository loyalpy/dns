<?php
class domain_manager extends UCAdmin{
	//域名管理列表
	public function domain(){
		$do = R("do","string");
		$page = R("page","int");
		$pagesize = R("pagesize","int");
		$page = $page?$page:1;
		$pagesize = $pagesize?$pagesize:20;
		$pageurl = U("/domain_manager/domain?do=get");
		$mark = R("mark","int");
		//获取排序
		$orderby = R("orderby","string","lastupdate!DESC");

		//查询开始
		$condition = array(
			"keyword"			 => R("keyword","string"),
			"startdate"  		 => R("startdate","string"),
			"enddate"    		 => R("enddate","string"),
			"service_group"      => R("service_group","string"),
			"ns_group"           => R("ns_group","string"),
			"inns"				 => R("inns","int"),
			"expire"			 => R("expire","int"),
			"uid"				 => R("uid","int"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND service_expiry>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND service_expiry<=".strtotime($v)):"";
					break;
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
				case "inns":
					$where .= $v?(" AND inns = ".($v-1)):"";
					break;
				case "expire":
					$startTime = time();
					$endTime = 0;
					if ($v == 1) { //一周以内到期
						$endTime = strtotime("+1 weeks");
					}elseif ($v == 2) { //半个月以内到期
						$endTime = strtotime("+2 weeks");
					}elseif ($v == 3) { //一个月以内到期
						$endTime = strtotime("+1 months");
					}elseif ($v == 4) { //半年以内到期
						$endTime = strtotime("+6 months");
					}elseif ($v == 5) { //一年以内到期
						$endTime = strtotime("+1 years");
					}elseif ($v == 9) {
						$startTime = 1;
						$endTime = time();
					}
					$expStr = " AND service_expiry <='{$endTime}'";
					$expStr .= " AND service_expiry >='{$startTime}'";
					$where .= $v?($expStr):"";
					break;
				case "uid":
					if($v){
						$search_userinfo = C("user")->get_cache_userinfo($v);
						$condition['uname'] = $search_userinfo['name'];
					}					
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
			$data['pagesize'] = $pagesize;
			$data['order'] = str_replace("!"," ",$orderby);
			$result = M("@domain")->get_list($data,$pageurl);
			$result['list'] = $this->chg_string_by_arr($result['list']);
			if ($mark) {
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"domain_parse_count",array($condition['uid'],$condition['keyword']));
			}else{
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist",array($orderby));
			}
			$result['orderby'] = $orderby;
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		//套餐组中文名显示开始
		$ns_group = $ns_group_v = $service_group = $service_group_v = array();
		$ns_group = M("@domain_ns_group")->get_list(false);
		foreach($ns_group as $key => $v){
			//判断只有NS服务器组可以切换
			if (!in_array($key,array("monitor_free","web1"))) {
				$ns_group_v[] = array("key"=>$key,"v"=>$v['name']);
			}
		}
		$service_group = M("@domain_service")->get_list(0);
		foreach($service_group as $key => $v){
			if ($v['utype'] == 2) {
				$service_group_v[] = array("key"=>$key,"v"=>$v['name']."(企业)");
			}else{
				$service_group_v[] = array("key"=>$key,"v"=>$v['name']);
			}
			$badge_service[$key] = M("domain")->get_one("service_group = '{$key}'","count('domain_id')");
		}
		$this->assign("ns_group",$ns_group);
		$this->assign("ns_group_v",$ns_group_v);
		$this->assign("service_group",$service_group);
		$this->assign("service_group_v",$service_group_v);

		//统计类
		$startTime = time();
		$badge[0] = M("domain")->get_one("1","count('domain_id')");
		$badge[9] = M("domain")->get_one("service_expiry >= 1 AND service_expiry <= '{$startTime}'","count('domain_id')");//已到期
		$badge[1] = M("domain")->get_one("service_expiry >= '{$startTime}' AND service_expiry <= '".strtotime("+1 weeks")."'","count('domain_id')");//一周内
		$badge[2] = M("domain")->get_one("service_expiry >= '{$startTime}' AND service_expiry <= '".strtotime("+2 weeks")."'","count('domain_id')");//半月内
		$badge[3] = M("domain")->get_one("service_expiry >= '{$startTime}' AND service_expiry <= '".strtotime("+1 months")."'","count('domain_id')");//一月内
		$this->assign("in_our_ns",M("domain")->get_one("inns = 1","count('domain_id')"));//已指向统计
		$this->assign("badge",$badge);//期限统计
		$this->assign("badge_service",$badge_service);//套餐统计

		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->assign("expire",$condition['expire']);
		$this->display();
	}
	//domain_refreshall
	public function domain_refreshall(){
		$do            = R("do","string");
		$service_group = R("service_group","string");
		$service_group = $service_group?$service_group:"free";
		$query = new tQuery("domain"); 

		$c = array();
		$c['where']    = "service_group = '{$service_group}'";
		$c['pagesize'] = 500;
		$c['page']     = 1;
		$result = $query->get_list($c);
		foreach($result['list'] as $key => $v){
			switch ($do) {
				case 'refresh':
					M("@domain")->queue($v['domain'],"update_record");
					break;
				default:
					break;
			}
		}
		//处理后面的页码
		for($page=2;$page<=$result['totalpage'];$page++){
			$c['page'] = $page;
			$result = $query->get_list($c);
			foreach($result['list'] as $key => $v){
				switch ($do) {
					case 'refresh':
						M("@domain")->queue($v['domain'],"update_record");
						break;
					default:
						break;
				}
			}
		}
		die("");
	}
	//domain_checkall
	public function domain_checkall(){
		$do = R("do","string");
		$query = new tQuery("domain"); 
		$c = array();
		$c['pagesize'] = 500;
		$c['page']     = 1;
		$result = $query->get_list($c);
		foreach($result['list'] as $key => $v){
			$domain = $v['domain'];
			switch ($do) {
				case 'ns':
					M("@domain")->check_ns($domain);
					break;
				case "expiry":
					M("@domain")->check_expiry($domain);
					break;
				default:
					# code...
					break;
			}
		}
		//处理后面的页码
		for($page=2;$page<=$result['totalpage'];$page++){
			$c['page'] = $page;
			$result = $query->get_list($c);
			foreach($result['list'] as $key => $v){
				switch ($do) {
					case 'ns':
						M("@domain")->check_ns($domain);
						break;
					case "expiry":
						M("@domain")->check_expiry($domain);
						break;
					default:
						# code...
						break;
				}
			}
		}
		die("");
	}
	//域名检查
	public function domain_checkns(){
		$domain = R('domain',"string");
		$res = M("@domain")->check_ns($domain);
		tAjax::json(array("error"=>0,"ns"=>$res['ns']));
	}
	//域名管理新增，更改
	public function domain_edit(){
		$domain_id = R("domain_id","int");
		if (tUtil::check_hash()) {
			$domain = R("domain","string");
			if ($domain_id <= 0) {						//新增
				$res = SDK::web_api("/Domain/AddByUid", array(
					"domain" => $domain
				));
				tAjax::json($res);
			} else {												//更改
				$ttl  = R("ttl","int");
				$qps  = R("qps","int");
				$bz   = R("bz","string");
				if($ttl <1){
					tAjax::json(array("status"=>0,"msg"=>"TTL 最低为1"));
				}
				$data = array(
					"ttl" => $ttl,
					"qps" => $qps,
					"bz"  => $bz,
				);
				$ret = M("domain")->set_data($data)->update("domain_id='{$domain_id}'");
				if($ret){
					M("@domain")->queue($domain,"update_record");
					C("user")->log("修改域名","域名:{$domain},TTL:{$ttl},QPS:{$qps}");
					tAjax::json(array("status"=>1,"msg"=>"修改成功"));
				}else{
					tAjax::json(array("status"=>0,"msg"=>"修改失败"));
				}
			}
		} else {
			$res = M("domain")->get_row("domain_id='{$domain_id}'");
			$res['domain'] = ($res['is_cn'] == 0) ?$res['domain']: $res['domain_cn'];
			tAjax::json($res);
		}
	}
	//域名管理删除
	public function domain_del(){
		$domain_id  = R("domain_id","int");
		$domain_row = M("domain")->get_row("domain_id = {$domain_id}");
		if (!isset($domain_row['domain'])) {
			tAjax::json_error("域名不存在");
		}
		//域名列表：删除之后放进回收站,伪删除
		$res = SDK::web_api("/Domain/Del", array(
			"domain_id"    => $domain_id
		));			
		if($res['status'] == 1){
			//删除的域名同时加入黑名单
			$data = array(
				"domain" => $domain_row['domain'],
				"type"		=> 0
			);
			M("domain_whiteblack")->set_data($data)->add();
		}
		tAjax::json($res);
	}
	//域名增加牵引
	public function domain_qy_lock(){
		global $uid,$timestamp;
		$domain = R("domain","string");
		$domain_row = M("domain")->get_row("domain='{$domain}'");
		if(!isset($domain_row['domain_id'])){
			tAjax::json_error("域名不存在!");
		}
		if(tUtil::check_hash()){

			$status = R("status","int");
			$bz 		= R("bz","string");
			$expiry = R("expiry","string");
			$expiry = empty($expiry)?0:strtotime($expiry);

			//永久锁定不需要预计解封时间
			if ($status == 1 && $expiry == 0) {
				tAjax::json_error("请填写预计解封时间");
			}
			//永久解封时间,不能小于当前时间
			if ($status == 1 && $expiry <= $timestamp) {
				tAjax::json_error("预计解封时间不能小于当前时间");
			}

			//更改状态为锁定状态，等待解锁
			$ret = M("domain")->set_data(array("indel"=>1))->update("domain='{$domain}'");
			if($ret){
				usleep(10*1000);
				//判断为0解封表是否存在
				if (M("domain_qy")->get_one("domain = '{$domain}' AND status = 0","count('qy_id')") > 0) {
					tAjax::json_error("该域名已添加牵引，待解封");
				}
				//添加到牵引队列
				$qy_data = array(
					"domain"   			=> $domain,
					"status"   			=> ($status == 1)?0:$status,//0待解封2，永久牵引，可解封
					"uid"      				=> $this->userinfo['uid'],
					"author"   			=> $this->userinfo['name'],
					"dateline" 			=> $timestamp,
					"expiry"   			=>  $expiry,
					"undateline"		=> 0,
					"bz"      				=>$bz,
				);
				$res = M("domain_qy")->set_data($qy_data)->add();
				if ($res) {
					tAjax::json(array("error"=>0,"message"=>"锁定成功！","callback"=>"close"),"json");
				}else{
					tAjax::json_error("锁定失败");
				}
				SDK::web_api("/Domain/Refresh",array("domain"=>$domain));
			}
			tAjax::json_error("操作失败!");
		}else{
			$data['domain'] = $domain;
			tAjax::json($data);
		}
	}
	//域名解封牵引
	public function domain_qy_unlock(){
		global $uid,$timestamp;
		$domain = R("domain","string");
		$domain_row = M("domain")->get_row("domain='{$domain}'");
		if(isset($domain_row['domain_id'])){
			$qy_id = R("qy_id","int");
			//更改状态为已解封状态
			M("domain")->set_data(array("indel"=>0))->update("domain='{$domain}'");
			usleep(10*1000);
			//判断牵引表行是否存在,牵引ID存在,解封
			if ($qy_id) {
				$qy_row = M("domain_qy")->get_row("qy_id = '{$qy_id}'");
				if (isset($qy_row['domain'])) {
					$data['status'] = 1;
					$data['undateline'] = $timestamp;
					if ($qy_row['status'] == 2) {
						$data['expiry'] = $timestamp;
					}
					$res = M("domain_qy")->set_data($data)->update("qy_id = '{$qy_id}'");
					if ($res) {
						SDK::web_api("/Domain/Refresh",array("domain"=>$domain));
						tAjax::json_success("解锁成功");
					}else{
						tAjax::json_error("解锁失败");
					}
				}else{
					tAjax::json_error("牵引ID不存在");
				}
			}else{
				tAjax::json_error("牵引ID不存在");
			}
		}

		tAjax::json_error("操作失败!");
	}
	//刷新纪录
	public function domain_refresh(){
		$domain = R("domain","string");
		$ret = SDK::web_api("/Domain/Refresh",array("domain"=>$domain));
		if($ret['status'] == 1){
			tAjax::json_success("刷新成功");
		}else{
			tAjax::json_error("刷新失败");
		}
	}
	//域名操作日志
	public function domain_log(){
		$do         = R("do","string");
		$page     = R("page","int");
		$page     = $page?$page:1;
		$pageurl = U("/domain_manager/domain_log?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"uname"				=> R("uname","string"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND domain_id ='{$v}' OR  uid LIKE '%{$v}%' OR ipaddr LIKE '%{$v}%'";
					}else{
						$where .= $v?" AND (domain LIKE '%{$v}%' OR  modi_item LIKE '%{$v}%' OR modi_log LIKE '%{$v}%')":"";
					}
					break;
				case "uname":
					$where .= $v?(" AND domain = '{$v}'"):"";
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
			$data['order'] = "dateline DESC";
			$result = M("@domain_log")->get_list($data,$pageurl);
			foreach ($result['list'] as $key=>$val){
				$userinfo = C("user")->get_cache_userinfo($val['uid']);
				if (isset($userinfo['email'])) {
					$result['list'][$key]['email'] = $userinfo['email'];
				}else{
					$result['list'][$key]['email'] = "系统";
				}
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//域名切换日志
	public function domain_log_switch(){
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$c           = R("c","int");
		$c     		= $c?$c:100;

		$pageurl = U("/domain_manager/domain_log_switch?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"startdate"  		=> R("startdate","string"),
			"enddate"    		=> R("enddate","string"),
			"domain_id"		=> R("domain_id","int"),
			"c"						=> $c,
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND dateline<=".strtotime($v)):"";
					break;
				case "keyword":
					$where .= $v?" AND (domain LIKE '%{$v}%')":"";
					break;
				case "domain_id":
					$domain_row = M("domain")->get_row("domain_id = '{$v}'");
					if (isset($domain_row)) {
						$where .= $v?" AND (domain = '{$domain_row['domain']}')":"";
					}else{
						$where .= $v?" AND {$k}='{$v}'":"";
					}
					break;
				case "c":
					if ($v != 100) {
						$v= ($v == 10)?0:$v;
						$where .= " AND type = '{$v}'";
					}
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}
		//查询结束
		if($do == "get"){
			$data['page']   = $page;
			$data['where'] = $where;
			$data['order']  = "dateline desc";
			if ($condition['domain_id']) {
				$ajaxlist = "exchenge_log";
				$data['pagesize']  = "10";
			}else{
				$ajaxlist = "loadlist";
			}
			$result = M("@domain_nsswitch")->get_list($data,"__page__?");
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],"__page__?",$ajaxlist,array($condition['domain_id']));
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}

		$ns_group = M("@domain_ns_group")->get_list(false);
		$this->assign("ns_group",$ns_group);

		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->assign("c",$c);
		$this->display();
	}
	//域名切换清理
	public function domain_clean_ns(){
		
	}
	//域名管理黑白名单
	public function domain_black(){
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/domain_manager/domain_black?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			if($k=="keyword"){
				$where .= $v?" AND (domain LIKE '%{$v}%')":"";
			}else{
				$where .= $v?" AND {$k}='{$v}'":"";
			}
		}
		//查询结束
		if($do == "get"){
			$data['page']       = $page;
			$data['where']     = $where;
			$data['pagesize'] = 20;
			$result = M("@domain_whiteblack")->get_list($data,$pageurl);
			$list = array();
			foreach ($result as $key => $value) {
				if (is_array($value)) {
					$list[$value['domain']] = $value;
				}
			}
			$result['list'] = $list;
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	// 域名管理黑白名单添加
	public function domain_blackadd()
	{
		$data['domain'] = R("domain","string");
		$data['type']		  = R("type","int")?R("type","int")-1:"0";
		if(!tValidate::is_domain($data['domain'])){
			tAjax::json_error("非法域名");
		}
		$rst = M("domain_whiteblack")->set_data($data)->add();
		if ($rst) {
			tAjax::json(array("error" => 0, "message" => "新增成功！", "callback" => "close"));
		}
	}
	// 域名管理黑白名单删除
	public function domain_blackdel()
	{
		$id = R("id","int");
		$rst = M("domain_whiteblack")->del("id='{$id}'");
		if ($rst) {
			tAjax::json(array("error" => 0, "message" => "删除成功！", "callback" => "close"));
		}
	}
	//域名管理已删除域名
	public function domain_deleted()
	{
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/domain_manager/domain_deleted?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"startdate"  		=> R("startdate","string"),
			"enddate"    		=> R("enddate","string"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND dateline<=".strtotime($v)):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND uid='{$v}' OR domain LIKE '%{$v}%' OR domain_cn LIKE '%{$v}%' ";
					}else{
						$where .= $v?" AND (domain LIKE '%{$v}%' OR domain_cn LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}
		//查询结束
		if($do == "get"){
			$data['page']   = $page;
			$data['where'] = $where;
			$data['order']  = "dateline desc";
			$result = M("@domain_del")->get_list($data,$pageurl);
			foreach ($result['list'] as $k=>$v) {
				if ($v['uid']) {
					$userInfo2 = C("user")->get_cache_userinfo($v['uid']);
					if (isset($userInfo2['email'])) {
						$result['list'][$k]['uid'] = $userInfo2['email'];
					}
				}
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//域名管理之域名找回
	public function domain_find()
	{
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/domain_manager/domain_find?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"startdate"  		=> R("startdate","string"),
			"enddate"    		=> R("enddate","string"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND dateline<=".strtotime($v)):"";
					break;
				case "keyword":
					$where .= $v?" AND (domain LIKE '%{$v}%' OR email LIKE '%{$v}%')":"";
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}
		//查询结束
		if($do == "get"){
			$data['page']   = $page;
			$data['where'] = $where;
			$data['order']  = "dateline desc";
			$result = M("@domain_find")->get_list($data,$pageurl);
			foreach ($result['list'] as $k=>$v) {
				if ($v['ouid']) {
					$userInfo = C("user")->get_cache_userinfo($v['ouid']);
					if (isset($userInfo['email'])) {
						$result['list'][$k]['ouid'] = $userInfo['email'];
					}
				}
				if ($v['uid']) {
					$userInfo2 = C("user")->get_cache_userinfo($v['uid']);
					if (isset($userInfo2['email'])) {
						$result['list'][$k]['uid'] = $userInfo2['email'];
					}
				}
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//域名自定义线路列表
	public function domain_diyline(){
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/domain_manager/domain_diyline?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
		);
		$where = "1 AND indel = 0";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					$where .= $v?" AND (domain LIKE '%{$v}%' OR name LIKE '%{$v}%' OR ipaddr LIKE '%{$v}%')":"";
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}
		//查询结束
		if($do == "get"){
			$data['page']   = $page;
			$data['where'] = $where;
			$data['order']  = "id desc";
			$result = M("@domain_acl_set")->get_list($data,$pageurl);
			foreach ($result['list'] as $k=>$v) {
				if ($v['uid']) {
					$userInfo2 = C("user")->get_cache_userinfo($v['uid']);
					$result['list'][$k]['uid'] = $userInfo2['email'];
				}
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//域名别名绑定
	public function domain_bind(){
		$do         = R("do","string");
		$page     = R("page","int");
		$page     = $page?$page:1;
		$pageurl = U("/domain_manager/domain_bind?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"uname"				=> R("uname","string"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			$domain_id = M("domain")->get_one("domain = '{$v}'","domain_id");
			switch($k){
				case "keyword":
					if ($domain_id) {
						$where .= $v?" AND (domain_id = '{$domain_id}' OR domain_bind = '{$v}')":"";
					}else{
						if(tValidate::is_int($v)){
							$where .= $v?" AND (uid = '{$v}')":"";
						}else{
							$uid = M("user")->get_one("email = '{$v}'","uid");
							if ($uid) {
								$where .= $v?" AND (uid = '{$uid}')":"";
							}else{
								$where .= $v?" AND (domain_bind = '{$v}')":"";
							}
						}
					}
					break;
				case "uname":
					$where .= $v?(" AND domain_id = '{$domain_id}'"):"";
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
			$data['order'] = "domain_id DESC";
			$result = M("@domain_bind")->get_list($data,$pageurl);
			foreach ($result['list'] as $key=>$val){
				$userinfo = C("user")->get_cache_userinfo($val['uid']);
				if (isset($userinfo['email'])) {
					$result['list'][$key]['email'] = $userinfo['email'];
				}else{
					$result['list'][$key]['email'] = "系统";
				}
				$result['list'][$key]['domain'] = M("domain")->get_one("domain_id = '{$val['domain_id']}'","domain");
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	// 域名自定义线路删除
	public function domain_diyline_del(){
		$id = R("id","int");
		if (empty($id)) {
			tAjax::json_error("线路不存在！");
		}
		$lineRow = M("domain_acl_set")->get_row("id = '{$id}'");
		if (!isset($lineRow['name'])) {
			tAjax::json_error("线路不存在！");
		}
		//验证自定义线路是否已被使用
		$custStr = "cust".$id;
		if (M("domain_record")->get_one("acl='{$custStr}' AND domain='{$lineRow['domain']}'","count(*)")) {
			tAjax::json_error("此线路已被域名记录使用，请更改其它线路后删除！");
		}
		$res = M("domain_acl_set")->set_data(array("indel"=>1))->update("id = '{$id}'");
		if ($res) {
			tAjax::json_success("删除成功!");
		}else{
			tAjax::json_error("删除失败！");
		}
	}
	//域名自定义线路锁定
	public function domain_diyline_sh(){
		$status     = R("status","int");
		$id  = R("id","int");

		$diyline_row = M("domain_acl_set")->get_row("id = '{$id}'");
		if (!isset($diyline_row['domain'])) {
			tAjax::json_error("自定义线路不存在！");
		}
		if (tUtil::check_hash()) {
			M("domain_acl_set")->set_data(array("status"=>$status))->update("id = '$id'");
			tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"),"json");
		}else{
			tAjax::json($diyline_row);
		}
	}
	//域名管理切换组
	public function domain_change(){
		global $timestamp;
		$domain_id = R("domain_id","int");
		$do = R("do","string");
		if(tUtil::check_hash()){
			switch ($do) {
				case "change_user":			//切换用户
					$res = SDK::web_api("/Domain/Trance", array(
						"uidd"     		 => R("uid", "int"),
						"domain_id" =>$domain_id,
					));
					tAjax::json($res);
					break;
				case "change_service":    //切换套餐
					$domainStr = M("domain")->get_one("domain_id = '{$domain_id}'","domain");
					if ($domainStr) {
						$newServer = R("service_group", "string");
						$service_expiry = strtotime(R("service_expiry", "string"));
						M("@domain")->trans($domainStr,$newServer,$service_expiry,1);
					}
					tAjax::json(array("error"=>0,"message"=>"保存成功！","callback"=>"close"),"json");
					break;
				case "change_group":    //切换服务器组
					$domainStr = M("domain")->get_one("domain_id = '{$domain_id}'","domain");
					$ns_group = R("ns_group", "string");
					if ($domainStr) {
						M("@domain")->trans_ns($domainStr,$ns_group,2);
					}
					tAjax::json(array("error"=>0,"message"=>"保存成功！","callback"=>"close"),"json");
					break;
				default:;
			}
		}else{
			if ($domain_id) {
				$res = M("domain")->get_row("domain_id='{$domain_id}'");
				$res['domain'] = ($res['is_cn'] == 0) ?$res['domain']: $res['domain_cn'];
				$res['service_expiry'] = $res['service_expiry']?date("Y-m-d", $res['service_expiry']):date("Y-m-d",($timestamp+31*86400));

				$uidStr = C("user")->get_cache_userinfo($res['uid']);
				$res['email'] = $uidStr['uid'] > 0 ? $uidStr['email'] : '';

				$domain_ns_group_res = M("domain_ns_group")->get_row("ns_group='{$res['ns_group']}'");
				$res['ns_group_show'] = $domain_ns_group_res['name'];
				//域名操作日志
				$c = array(
					"where"		=> "domain_id='{$domain_id}'",
					"order"		=>  "dateline DESC",
					"page"  		=> R("page","int")?:1,
					"pagesize"   => 10
				);
				$res['log'] = Q("domain_log")->get_list($c,"__page__?");
				$res['pagebar'] = tFun::pagebar_js($res['log']['pagebar'],"__page__?","edit_setting_log_func",array($domain_id));

			}
			tAjax::json($res);
		}
	}
	//域名管理更改锁定状态
	public function domain_change_status(){
		$domain_id = R("domain_id", "int");
		$status = R("status", "int");
		$res = SDK::web_api("/Domain/Status", array(
			"domain_id"    => $domain_id,
			"status"			=> $status,
			"type"				=> "admin",
		));
		tAjax::json($res);
	}
	//域名管理对表的查询结果进行处理
	public function chg_string_by_arr($arr)
	{
		$list = array();
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				$list[$value['domain']] = $value;

				$uidString = C("user")->get_cache_userinfo($value['uid']);
				$list[$value['domain']]['user'] = $uidString['uid'] > 0 ? $uidString['name'] : '用户不存在';
				$list[$value['domain']]['email'] = isset($uidString['email']) ? $uidString['email'] : '';

				$list[$value['domain']]['lastupdate'] = date("Y-n-j G:i:s", $value['lastupdate']);
				$list[$value['domain']]['service_expiry'] = date("Y-m-d", $value['service_expiry']);
				$list[$value['domain']]['service_expiry_pass'] = $value['service_expiry'] >= time() ? "" : "已过期";

				$ns_groups = M("@domain_ns_group")->get_cache_by_ns($value['ns_group']);
				if(isset($ns_groups['ns'])){
					$list[$value['domain']]['ns_group_ns'] = $ns_groups ? $ns_groups['ns'] : array();
				}
			}
		}
		return $list;
	}
	//域名管理选择域名操作
	public function domain_get(){
		$page = R("page","int");
		$formstr = R("formstr","string");
		$page = $page?$page:1;
		$condi = array(
			"keyword"   => R("keyword","string"),
		);
		$where = "1";
		foreach($condi as $k=>$v){
			if($k=="keyword"){
				$where .= $v?" AND (domain LIKE '%{$v}%')":"";
			}else{
				$where .= $v?" AND {$k}='{$v}'":"";
			}
		}

		$c = array(
			"where"		=> $where,
			"page"  		=> $page,
			"pagesize"   => 99
		);
		$result = Q("domain")->get_list($c,"__page__?");
		$result['condi'] = $condi;
		$result['pagebar'] = tFun::pagebar_js($result['pagebar'],"__page__?","get_domainlist",array($condi['keyword'],$formstr));
		tAjax::json($result);
	}

	//域名解析记录
	public function  records(){
		$do         = R("do","string");
		$page     = R("page","int");
		$c           = R("c","string");
		$page     = $page?$page:1;
		$pageurl = U("/domain_manager/records?do=get");
		$mark     = R("mark","string");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"RRtype"   			=> R("RRtype","string"),
			"domain_id"		=> R("domain_id","int"),
			"uname"				=> R("uname","string"),
			"c"						=> R("c","string"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND record_id ='{$v}' OR  domain LIKE '%{$v}%' OR RRvalue LIKE '%{$v}%' ";
					}else{
						$where .= $v?" AND (domain LIKE '%{$v}%' OR  RRname LIKE '%{$v}%' OR RRtype LIKE '%{$v}%' OR acl LIKE '%{$v}%')":"";
					}
					break;
				case "RRtype":
					$where .= $v?(" AND RRtype = '{$v}'"):"";
					break;
				case "domain_id":
					$where .= $v?(" AND domain_id = '{$v}'"):"";
					break;
				case "uname":
					$where .= $v?(" AND domain = '{$v}'"):"";
					break;
				case "c":
					$where .= $v?(" AND inlock = 1"):"";
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
			$data['pagesize'] = $mark?10:30;
			$data['monitor'] = 1;
			$data['order'] = "record_id DESC";
			$result = M("@domain_record")->get_list($data,$pageurl);
			if ($mark) {//后台域名列表弹框
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"show_domain_records",array($condition['domain_id']));
			}else{//后台域名解析记录列表，非弹框
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			}
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}

		$RRtype_v = array();
		$RRtypeArr =tCache::read('data_config');
		foreach($RRtypeArr['RRtype'] as $key => $v){
			$RRtype_v[] = array("key"=>$key,"v"=>$v);
		}
		$this->assign("RRtype",$RRtype_v);
		$this->assign("RRtypeArr",$RRtypeArr["RRtype"]);

		$this->assign("c",$c);
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//域名URL记录审核
	public function domain_record_check(){
		$inlock     = R("inlock","int");
		$record_id  = R("record_id","int");

		$record_row = M("domain_record")->get_row("record_id = '{$record_id}'");
		if (!isset($record_row['domain'])) {
			tAjax::json_error("域名记录不存在！");
		}
		$domain_row = M("domain")->get_row("domain_id = '{$record_row['domain_id']}'");
		if (!isset($domain_row['domain'])) {
			tAjax::json_error("域名不存在！");
		}
		if (tUtil::check_hash()) {
			$RRtypeArr =tCache::read('data_config');
			//更改记录状态
			$res = M("domain_record")->set_data(array("inlock"=>($inlock-1)))->update("record_id = '$record_id'");
			if ($res) {
				//域名记录状态更新
				M("@domain")->queue($domain_row['domain'],"update_record");

				if ($inlock == 1) {//审核成功，发送邮件
					//发送邮件提醒
					C("user")->send_mail(array("type"=>"check","domain"=>$record_row['domain'],"RRtype"=>$RRtypeArr['RRtype'][$record_row['RRtype']]),$domain_row['uid']);
					//发送微信提醒
					C("user")->send_wx(array("type"=>"check","domain"=>$record_row['domain'],"RRtype"=>$RRtypeArr['RRtype'][$record_row['RRtype']]),$domain_row['uid']);
				}
				tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"),"json");
			}else{
				tAjax::json_error("操作失败,审核结果未变");
			}
		}else{
			$record_row['inlock'] = $record_row['inlock'] + 1;
			tAjax::json($record_row);
		}

	}
	//域名服务线路列表
	public function line(){
		$do = R("do");
		$cls_cate = new cls_category("domain_acl");
		switch($do){
			case "refresh":
				$cls_cate->clear();
				$list = $cls_cate->get(0,0,0,0);
				foreach($list as $key=>$v){
					M("@domain_aclip")->write_romate_ipv4($v['ident']);
				}
				C("user")->log("批量刷新线路","操作命令：{$do}");
				tAjax::json_success("刷新成功！");
				break;
			case "copy":
				$id = R("id","int");
				if($id){
					$ret = M("domain_acl")->get_row("id='{$id}'");
					if(isset($ret['id'])){
						unset($ret['id']);
						M("domain_acl")->set_data($ret)->add();
						$cls_cate->clear();
					}
				}
				tAjax::json_success("操作成功！");
				break;
			case "get":
				$return = array();
				$return['list'] = $cls_cate->get(0,0,0,0);
				$return['list'] = array_merge($return['list']);
				tAjax::json($return);
				break;
			default:
				$this->assign("catlist",$cls_cate->json_tpl());
				$this->display();
				break;
		}
	}
	//域名线路删除
	public function line_del(){
		$id = R("id","int");
		$cls_cate = new cls_category("domain_acl");
		if(M("domain_acl")->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
			tAjax::json_error("该模块下有子模块！不能删除");
		}
		if(M("domain_acl")->del("id='{$id}'")){
			$cls_cate->clear();
			tAjax::json_success("删除成功！");
		}else{
			tAjax::json_error("删除失败！");
		}
	}
	//域名线路修改
	public function line_edit(){
		$id = R("id","int");
		$cls_cate = new cls_category("domain_acl");
		if(tUtil::check_hash()){
			$data = array(
				"pid"         		=> R("pid","int"),
				"name"        	=> R("name","string"),
				"ident"       		=> R("ident","string"),
				"sort"        		=> R("sort","int"),
//						"ipdata"   		=> R("ipdata","string"),
				"status"			=> R("status","int"),
			);
			if(empty($data['name'])){//！
				tAjax::json_error("分类名不能为空！");
			}
			if($id == 0){
				M("domain_acl")->set_data($data);
				$ret = $id = M("domain_acl")->add();
			}else{
				if($data['pid'] == $id){
					tAjax::json_error("上级不能为自己！");
				}

				M("domain_acl")->set_data($data);
				$ret = M("domain_acl")->update("id=$id");
			}
			$cls_cate->clear();
			tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));
		}else{
			$ret = array();
			if($id){
				$ret = M("domain_acl")->get_row("id='{$id}'");
			}
			if(!isset($ret['id'])){
				$ret = array("name"=>"","id"=>0,"status"=>1,"isopen"=>0,'pid'=>'');
			}
			$ret['status'] = array($ret['status']);

			tAjax::json($ret);
		}
	}
	//域名服务线路IP库
	public function line_aclip()
	{
		$line_id = R("line_id","int");
		$do = R("do","string");
		switch($do){
			//ip导入
			case "import":
				if(tUtil::check_hash()){
					set_time_limit(1800);
					//获取线路ID
					$aclid = R("aclid","int");
					$acl    = R("acl","string");
					if(empty($aclid)){
						tAjax::json_error("请选择控制器");
					}
					$acl_row = M("domain_acl")->get_row("id='{$aclid}'");
					if(!isset($acl_row['id'])){
						tAjax::json_error("线路数据未找到");
					}
					$acl = $acl_row['ident'];


					//上传附件
					$attach_name = "ipdatafile";
					$error_message = "";
					if(empty($_FILES[$attach_name]) === false){
						$up_obj = new tUpload(2048,array("txt","conf"));
						$file_store_path = ROOT_PATH."attach/tmp/";
						$return_file = "";
						$up_obj->set_dir($file_store_path);
						$upstate = $up_obj->execute();
						if(isset($upstate[$attach_name])){
							if($upstate[$attach_name][0]['flag']==-1){
								$error_message = '上传的文件类型不符合';
							}else if($upstate[$attach_name][0]['flag']==-2){
								$error_message = '大小超过限度';
							}else if($upstate[$attach_name][0]['flag']==1){
								$file_path = $file_store_path.$upstate[$attach_name][0]['name'].".".$upstate[$attach_name][0]['ext'];
								$import_content = file_get_contents($file_path);
								if($import_content == ""){
									$error_message = '文件内容为空！';
								}else{
									$import_arr = explode(";",$import_content);
									if(count($import_arr)<2){
										$import_content = str_replace("\n",";",$import_content);
										$import_arr = explode(";",$import_content);
									}
									if(empty($import_arr) || count($import_arr) == 0){
										$error_message = '没有可用的IP导入！';
									}
								}
								tFile::unlink($file_path);
							}
						}else{
							$error_message = '上传失败！';
						}
					}else{
						$error_message = "上传合法的txt,conf文件";
					}
					//如果有错误则输出错误
					if($error_message){
						tAjax::json_error($error_message);
					}
					//处理导入数据处理
					$mode = R("mode","int")?:2;
					$inserts = 0;
					$import_arr = array_unique($import_arr);
					if($mode == 1){
						foreach($import_arr as $v){
							$v = trim($v);
							if($v && M("domain_aclip")->get_one("aclid=$aclid AND addr='{$v}'","count(*)") == 0){
								$ins = array(
									"addr"=>$v,
									"aclid"=>$aclid
								);
								M("domain_aclip")->set_data($ins);
								if(M("domain_aclip")->add())$inserts++;
							}
						}
					}elseif($mode == 2){
						M("domain_aclip")->del("aclid=$aclid");
						$db = tDB::get_db();
						$sql = "";
						$chunk_arr = array_chunk($import_arr,15000);
						foreach($chunk_arr as $v){
							if(count($v)>0){
								$sql = "INSERT INTO `wo_domain_aclip`(`addr`,`aclid`) VALUES";
								foreach($v as $v2){
									$v2 = trim($v2);
									if($v2){
										$insert_val[] = "('{$v2}','{$aclid}')";
										$inserts++;
									}
								}
								$sql .= implode(",",$insert_val);
								unset($insert_val);
								$db->query($sql);
							}
						}
					}
					M("@domain_aclip")->write_romate_ipv4($acl);
					tAjax::json(array("error"=>0,"message"=>"成功导入".$inserts."条IP!","callback"=>"close"));
				}else{
					tAjax::success($this->fetch("domain_manager/line_aclip"));
				}
				break;
			//ip导出
			case "export":
				$aclid = R("aclid","int");
				$ident= R("ident","string");
				$sql = "SELECT aclid,group_concat(DISTINCT addr ORDER BY addr ASC SEPARATOR ';\n') AS ipdata FROM `@domain_aclip` WHERE aclid='{$aclid}' GROUP BY aclid";
				Sq("SET group_concat_max_len = 3088000");
				$result = Sq($sql);

				header('Content-Type: application/txt');
				header('Content-Disposition: attachment; filename="ipdata_'.$ident.'.txt"');
				//$output = fopen('php://output','w') or die("Can't open php://output");
				file_put_contents('php://output',$result[0]['ipdata']);
				//fclose($output) or die("Can't close php://output");
				/*
                $cls_file = new tFile(ROOT_PATH."attach/tmp/ipdata_".$aclid.".ipdata","w");
                $cls_file->write($result[0]['ipdata']);
                $cls_file->save();
                header("Location:".tUrl::get_host().U("/attach/tmp/ipdata_".$aclid.".ipdata"));
                */
				break;
			case "del":
				$id = R("id","int");
				$data = M("domain_aclip")->get_row("id='{$id}'");
				if(isset($data['id'])){
					M("domain_aclip")->del("id='{$id}'");
					$aclid = $data['aclid'];
					$acl_row = M("domain_acl")->get_row("id='{$aclid}'");
					$acl = $acl_row['ident'];
					M("@domain_aclip")->write_romate_ipv4($acl);
					tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>"close"));
				}
				break;
			case "clear":
				$return = array("error"=>0,"message"=>"清除成功！");
				$id = R("id","int");
				M("domain_aclip")->del("aclid='{$id}'");

				$acl_row = M("domain_acl")->get_row("id='{$id}'");
				$acl = $acl_row['ident'];
				M("@domain_aclip")->write_romate_ipv4($acl);

				tAjax::json($return);
				break;
			case "edit":
				if(tUtil::check_hash()){
					$id = R("id","int");
					$data = array(
						"aclid" => R("aclid","int"),
						"addr" => R("addr","string"),
					);
					$acl_row = M("domain_acl")->get_row("id='{$data['aclid']}'");
					if(!isset($acl_row['id'])){
						tAjax::json_error("线路数据未找到");
					}
					$acl = $acl_row['ident'];

					M("domain_aclip")->set_data($data);
					if($id == 0){
						if(M("domain_aclip")->get_one("addr='{$data['addr']}' AND aclid='{$data['aclid']}'","count(*)")>0){
							tAjax::json_error("该地址段已经存在，请不要重复添加");
						}
						M("domain_aclip")->add();
					}elseif($id>0){
						if(M("domain_aclip")->get_one("addr='{$data['addr']}' AND aclid='{$data['aclid']}' AND id<>{$id}","count(*)")>0){
							tAjax::json_error("该地址段已经存在");
						}
						M("domain_aclip")->update("id='{$id}'");
					}
					$ret = M("@domain_aclip")->write_romate_ipv4($acl);
					tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));
				}else{
					$id = R("id","int");
					$aclid = R("aclid","int");
					$data = array();
					if($id>0){
						$data = M("dns_ipdata")->get_row("id=$id");
					}
					if(!isset($data['id'])){
						$data = array(
							'aclid'=>$aclid,
						);
					}
					$this->assign("data",$data);
					tAjax::success($this->fetch("sysadmin_dns/namedconf_ipdata_edit"));
				}
				break;
			default:
				if ($line_id) {
					$condi = array(
						"keyword"   => R("keyword","string"),
					);
					$where = "aclid='{$line_id}'";
					foreach($condi as $k=>$v){
						if($k=="keyword"){
							$where .= $v?" AND (addr LIKE '%{$v}%')":"";
						}else{
							$where .= $v?" AND {$k}='{$v}'":"";
						}
					}
					$c = array(
						"where"		=> $where,
						"page"  		=> R("page","int")?:1,
						"pagesize"   => 100,
						"order"        => "addr ASC"
					);
					$res['ip'] = Q("domain_aclip")->get_list($c,"__page__?");
					foreach($res['ip']['list'] as $key=>$val){
						$res['ip']['list'][$key]['line']=M("domain_acl")->get_one("id={$val['aclid']}","name");
					}
					$res['pagebar'] = tFun::pagebar_js($res['ip']['pagebar'],"__page__?","edit_ip_func",array($line_id,$condi['keyword']));
				}
				tAjax::json($res);
				break;
		}
	}
	//ip线路刷新
	public function line_refresh(){
		$id = R("id","int");
		$acl_row = M("domain_acl")->get_row("id='{$id}'");
		$acl = $acl_row['ident'];
		if(!isset($acl)){
			tAjax::json_error("线路标识未找到");
		}
		M("@domain_aclip")->write_romate_ipv4($acl);
		C("user")->log("刷新线路","线路标识：{$acl}");
		$res['list']=SDKdns::acl_by_acl($acl);
		tAjax::json($res);
	}
	//域名牵引
	public function domain_qy(){
		$do         = R("do","string");
		$page     = R("page","int");
		$c           = R("c","int");
		$page     = $page?$page:1;
		$c     		= $c?$c:0;
		$pageurl = U("/domain_manager/domain_qy?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"uname"				=> R("uname","string"),
			"c"						=> $c,
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND qy_id ='{$v}' OR  uid LIKE '%{$v}%' OR total0 LIKE '%{$v}%' OR total1 LIKE '%{$v}%'";
					}else{
						$where .= $v?" AND (domain LIKE '%{$v}%' OR  author LIKE '%{$v}%')":"";
					}
					break;
				case "uname":
					$where .= $v?(" AND domain = '{$v}'"):"";
					break;
				case "c":
					$where .= " AND status = '{$v}'";
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
			$data['pagesize'] = 30;
			$data['order'] = "qy_id DESC";
			$result = M("@domain_qy")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->assign("c",$c);
		$this->display();
	}
	//域名解析记录续费历史
	public function  domain_order(){
		$domain_id = R("domain_id","int");
		$page     = R("page","int");
		$page     = $page?$page:1;

		$domain_row = M("domain")->get_row("domain_id = '{$domain_id}'");
		if (!isset($domain_row)) {
			tAjax::json_error("非法域名");
		}
		$data['page']  = $page;
		$data['where'] = "goods_name = '{$domain_row['domain']}'";
		$data['pagesize'] = 10;
		$data['order'] = "dateline DESC";
		$result = Q("order_item")->get_list($data,"__page__?");
		foreach ($result['list'] as $key=>$val) {
			$result['list'][$key]['youhui'] = sprintf("%.2f",($val['amount_promation'] + $val['amount_other']));
			if ($val['num'] >= 10) {
				$result['list'][$key]['price'] = sprintf("%.2f",($val['price']*$val['num']));
			}
		}
		$result['pagebar'] = tFun::pagebar_js($result['pagebar'],"__page__?","show_domain_order",array($domain_id));
		tAjax::json($result);
	}
}
?>
