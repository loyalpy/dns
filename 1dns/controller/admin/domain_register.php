<?php
class domain_register extends UCAdmin{
	//注册域名
	public function domain(){
		global $timestamp;
		
		$do = R("do","string");
		$page = R("page","int");
		$pagesize = R("pagesize","int");
		$page = $page?$page:1;
		$pagesize = $pagesize?$pagesize:20;
		$pageurl = U("/domain_register/domain?do=get");
		$orderby = R("orderby","string","reg_time!DESC");
		$mark = R("mark","int");
		$condi = array(
			"startdate" => R("startdate","string"),
			"enddate"   => R("enddate","string"),
			"keyword"   => R("keyword","string"),
			"expire"		=> R("expire","int"),
			"uid"				 => R("uid","int"),
		);
		$where = "1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".urlencode($v);
			switch($k){
				case "startdate":
					$where .= $v?(" AND reg_time>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND reg_time<=".strtotime($v)):"";
					break;
				case "keyword":
					if ($v) {
						$where .= $v?" AND (domain LIKE '%{$v}%' OR ns LIKE '%{$v}%')":"";
					}else{
						$where .= " ";
					}
					break;
				case "expire":
					$startTime_s = $startTime =  time();
					$endTime = 0;
					if ($v == 1) { //一周以内到期
						$endTime = strtotime("+1 weeks");
					}elseif ($v == 2) { //一个月以内到期
						$endTime = strtotime("+1 months");
					}elseif ($v == 3) { //三个月以内到期
						$endTime = strtotime("+3 months");
					}elseif ($v == 4) { //续费期
						$startTime = $startTime_s - 30*86400;
						$endTime = $startTime_s;
					}elseif ($v == 5) { //赎回期
						$startTime = $startTime_s - 60*86400;
						$endTime = $startTime_s - 30*86400;
					}elseif ($v == 6) { //已删除（失效域名）
						$startTime = 0;
						$endTime = $startTime_s - 60*86400;
					}
					$expStr = " AND exp_time < '{$endTime}'";
					$expStr .= " AND exp_time > '{$startTime}'";
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
		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['pagesize']  = $pagesize;
			$c['where'] = $where;
			$c['order'] = "dateline DESC";
			$c['order'] = str_replace("!"," ",$orderby);
			$result = M("@register_domain")->get_list($c,$pageurl,true);
			if ($mark) {
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"register_domain_fun",array($condi['uid'],$condi['keyword']));
			}else{
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist",array($orderby));
			}
			$result['orderby'] = $orderby;

			$now_time = time();
			foreach($result['list'] as $key=>$v){
				//用户，时间
				$tmp  = C("user")->get_cache_userinfo($v['uid']);
				$result['list'][$key]['uinfo'] = $tmp;
				$result['list'][$key]['reg_time'] = date("Y-m-d",$v['reg_time']);
				$result['list'][$key]['exp_time'] = date("Y-m-d",$v['exp_time']);
				$result['list'][$key]['renew_dateline'] = (!empty($v['renew_dateline']))?date("Y-m-d",$v['renew_dateline']):"-";
				//1正常2续费期3赎回期4已删除，域名无法使用
				if ($v['exp_time'] > $now_time) {
					$result['list'][$key]['exp_type'] = 1;
				}elseif ($now_time - 30*86400 < $v['exp_time'] &&  $v['exp_time'] < $now_time) {
					$result['list'][$key]['exp_type'] = 2;
				}elseif ($now_time - 60*86400 < $v['exp_time'] &&  $v['exp_time'] < $now_time - 30*86400) {
					$result['list'][$key]['exp_type'] = 3;
				}else{
					$result['list'][$key]['exp_type'] = 4;
				}
				//实名审核状态
				$result['list'][$key]['real_name'] = array(
					"status"  =>0,//实名状态,0无需认证，1未提交资料2待审核3审核成功4审核失败
					"cart"      =>'',//证件号码
					"cart_url"=>'',//证件URL
				);
				$domain_register_type = tCache::read("data_config");
				if (isset($domain_register_type['domain_register_type'])) {
					$suffix = substr($v['domain'],strpos($v['domain'],"."));
					if (in_array($suffix,$domain_register_type['domain_register_type'])) {
						$domain_attach_row = M("register_domain_attach")->get_row("domain_id = '{$v['id']}'");
						if (isset($domain_attach_row['id'])) {
							$result['list'][$key]['real_name']['cart'] = $domain_attach_row['cart'];
							$result['list'][$key]['real_name']['cart_url'] = $domain_attach_row['imgurl'];
							$result['list'][$key]['real_name']['status'] = 2;
						}else{
							$result['list'][$key]['real_name']['status'] = 1;
						}
					}
				}
			}
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}

		//统计类
		$startTime = time();
		$badge[0] = M("register_domain")->get_one("1","count('id')");
		$badge[1] = M("register_domain")->get_one("exp_time >= '{$startTime}' AND exp_time <= '".strtotime("+1 weeks")."'","count('id')");//一周内
		$badge[2] = M("register_domain")->get_one("exp_time >= '{$startTime}' AND exp_time <= '".strtotime("+1 months")."'","count('id')");//一月内
		$badge[3] = M("register_domain")->get_one("exp_time >= '{$startTime}' AND exp_time <= '".strtotime("+3 months")."'","count('id')");//三月内
		$badge[4] = M("register_domain")->get_one("exp_time >= '".($startTime - 30*86400)."' AND exp_time <= '{$startTime}'","count('id')");//已到期
		$badge[5] = M("register_domain")->get_one("exp_time >= '".($startTime - 60*86400)."' AND exp_time <= '".($startTime - 30*86400)."'","count('id')");//已到期
		$badge[6] = M("register_domain")->get_one("exp_time >= 0 AND exp_time <= '".($startTime - 60*86400)."'","count('id')");//已到期
		$this->assign("badge",$badge);


		//防刷新,设置最多1小时执行一次线上同步新网数据
		$ajaxSubmit = 0;
		$old_timestamp = tSafe::get('synchronization');
		if($old_timestamp>0 && ($timestamp-$old_timestamp) < 3600){
			$ajaxSubmit = 1;
		}else{
			tSafe::set('synchronization',$timestamp);
		}
		$this->assign("ajaxSubmit",$ajaxSubmit);
		
		
		$this->assign("pageurl",$pageurl);
		$this->assign("page",$page);
		$this->assign("condi",$condi);
		$this->assign("expire",$condi['expire']);
		$this->display();
	}
	//域名审核状态查询
	public function domain_rz_status(){
		$domain_str = R("domains","string");
		if (empty($domain_str)) {
			tAjax::json_error("false");
		}
		$domain_arr = explode(",",$domain_str);
		$new_domain_arr = array();
		foreach ($domain_arr as $key=>$val){
			$res = SDKdomain::domain_status("queryDomainVerifyStatus",$val);
			if ($res['status'] == 1) {
				//审核状态//实名状态,0无需认证，1未提交资料2待审核3审核成功4审核失败
				if (in_array($res['list']['info'],array("01","04"))) {
					$new_domain_arr[$val] = 2;
				}elseif (in_array($res['list']['info'],array("02","06"))) {
					$new_domain_arr[$val] = 3;
				}elseif (in_array($res['list']['info'],array("03","05","07"))) {
					$new_domain_arr[$val] = 4;
				}
			}
		}
		tAjax::json_success($new_domain_arr);
	}
	//域名价格
	public function price(){

		$do = R("do","string");
		$t = R("t","int");
		//代理商
		$r_type = tCache::read("data_config");
		$t = in_array($t,array_keys($r_type['domain_agent']))?$t:current(array_keys($r_type['domain_agent']));
		$pageurl = U("/domain_register/price?t=$t&do=get");

		if($do == "get"){
			$result = M("@domain_register_price")->get_list($t);
			tAjax::json($result);
		}

		//域名类型,代理商
		$p_type =  $reg_type = array();
		foreach($r_type['register_domain'] as $key => $v){
			$p_type[] = array("key"=>$key,"v"=>$v);
		}
		foreach($r_type['domain_agent'] as $key => $v){
			$reg_type[] = array("key"=>$key,"v"=>$v);
		}
		$this->assign("p_type",$p_type);
		$this->assign("reg_type",$reg_type);
		$this->assign("r_type",$r_type['domain_agent']);
		$this->assign("t",$t);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//域名价格添加修改
	public function price_edit(){
		$id = R("id","int");
		$type_s = R("type_s","int");
		if (tUtil::check_hash()) {
			$type					= R("type","int"); // 域名类型
			$agent 	 				= R("agent", "int"); //域名代理商1,新网，2万网
			$name  				= R("name", "string"); //产品名称
			$agent_price 		= R("agent_price", "float"); //代理续费价
			$agent_re_price 	= R("agent_re_price", "float"); //代理新开价
			$new_price 	 		= R("new_price", "float"); //新开价格
			$renew_price 	 	= R("renew_price", "float"); //续费价格
			$status 				 	= R('status', "int");	//状态
			$bz 	 					= R('bz', "string");	//备注
			//判断域名类型是否为空
			if (empty($type)) {
				tAjax::json_error("域名类型不能为空！");
			}
			//判断代理商是否是否存在
			if (empty($agent)) {
				tAjax::json_error("请选择域名代理商！");
			}
			//暂不支持万网代理功能
			if ($agent == 2) {
				tAjax::json_error("暂不支持万网代理功能！");
			}
			//添加时判断域名类型是否已经存在
			if (!$id) {
				if (M("domain_register_price")->get_one("type = '{$type}' AND agent = '{$agent}'","id")) {
					tAjax::json_error("域名类型已经存在，不能重复添加！");
				}
			}
			$map = array();
			$name = is_array($name) ? $name : array();
			if ($name) {
				$name = array_unique($name);
				foreach ($name as $k => $v) {
					if ($v) {
						if (!empty($bz[$k])) {
							$new_price_tmp = round($agent_price[$k] + $agent_price[$k]*intval($bz[$k])/100);
							$renew_price_tmp = round($agent_re_price[$k] + $agent_re_price[$k]*intval($bz[$k])/100);
						}else{
							$new_price_tmp = $new_price[$k];
							$renew_price_tmp = $renew_price[$k];
						}
						$map[] = array(
							'type'   	  			=> $type,
							'agent'					=> $agent,
							'name' 					=> $v,
							'agent_price'		=> $agent_price[$k],
							'agent_re_price'  	=> $agent_re_price[$k],
							'new_price'  		=> $new_price_tmp,
							'renew_price'  		=> $renew_price_tmp,
							'status'  				=> $status[$k],
							'bz'      					=> $bz[$k],
							'sort'    				=> $k,
						);
					}
				}
			}else{
				tAjax::json_error("产品名称不能为空！");
			}
			if (empty($id)) { //新增域名组价格
				//判断同类型下的产品名称是否已经存在
				if(M("domain_register_price")->get_one("type = '{$type}' AND agent = '{$agent}' AND name IN('".implode("','",$name)."')","id")){
					tAjax::json_error("产品名称已经存在，不能重复添加！");
				}
				if (count($map) > 0) {
					M("domain_register_price")->del("type='{$type}' AND agent = '{$agent}'");
					$res = M('domain_register_price')->add_more($map);
					if ($res) {
						//加入缓存
						M("@domain_register_price")->get_cache_by_agent($agent,true);
						tAjax::json(array("error" => 0, "message" => "新增套餐成功！", "callback" => "close"));
					}
				}else{
					tAjax::json_error("产品名称不能为空！");
				}
			} else { //更改域名组价格
				//判断同类型下的产品名称是否已经存在,不允许直接更改产品类型
				if ($type != $type_s) {
					if(M("domain_register_price")->get_one("type = '{$type}' AND agent = '{$agent}' AND id <> '{$id}'","id")){
						tAjax::json_error("产品名称已经存在，不能更改！");
					}
				}
				if (count($map) > 0) {
					M("domain_register_price")->del("type='{$type}' AND agent = '{$agent}'");
					$res = M('domain_register_price')->add_more($map);
					if ($res) {
						//加入缓存
						M("@domain_register_price")->get_cache_by_agent($agent,true);
						tAjax::json(array("error" => 0, "message" => "保存成功！", "callback" => "close"));
					}
				}else{
					tAjax::json_error("产品名称不能为空！");
				}
			}
		} else {
			$res = M("domain_register_price")->get_row("type = '{$type_s}'  AND id = '{$id}'");
			$res['type_s'] = $type_s;
			$res['data_arr'] = M("domain_register_price")->query("type = '{$res['type']}' AND agent = '{$res['agent']}'","*","sort asc");
			tAjax::json($res);
		}
	}
	//域名价格删除
	public function price_del(){

		$id = R("id","int");
		$type = R("type_s","int");
		if (empty($type)) {
			tAjax::json_error("非法删除!");
		}
		$agent = M("domain_register_price")->get_one("type = '{$type}' AND id = '{$id}'","agent");
		$rst = M("domain_register_price")->del("type = '{$type}' AND agent = '{$agent}'");
		if ($rst) {
			//加入缓存
			M("@domain_register_price")->get_cache_by_agent($agent,true);
			tAjax::json(array("error" => 0, "message" => "删除成功！", "callback" => "reload"));
		} else {
			tAjax::json_error("删除失败!");
		}
	}
	//生成缓存
	public function price_cache_set(){
		$agent = R("agent","int");
		$ret = M("@domain_register_price")->get_cache_by_agent($agent,true);
		if($ret){
			tAjax::json_success("生成缓存成功！");
		}else{
			tAjax::json_error("生成缓存失败！");
		}
	}
	//域名注册信息模板
	public function template(){
		$do         = R("do","string");
		$page     = R("page","int");
		$c           = R("c","string");
		$page     = $page?$page:1;
		$pageurl = U("/domain_register/template?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"uname"				=> R("uname","string"),
			"c"						=> R("c","string"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND id ='{$v}' OR  mobile LIKE '%{$v}%' OR uid LIKE '%{$v}%' ";
					}else{
						$where .= $v?" AND (name_cn LIKE '%{$v}%' OR  email LIKE '%{$v}%' OR m_name_cn LIKE '%{$v}%' OR mobile LIKE '%{$v}%')":"";
					}
					break;
				case "uname":
					$where .= $v?(" AND email = '{$v}'"):"";
					break;
				case "c":
					$where .= $v?(" AND is_use = 0"):"";
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
			$data['pagesize'] = 40;
			$data['order'] = "uid DESC";
			$result = M("@domain_register_info")->get_list($data,$pageurl);
			foreach($result['list'] as $key=>$v){
				$tmp  = C("user")->get_cache_userinfo($v['uid']);
				$result['list'][$key]['name'] = isset($tmp['email'])?$tmp['email']:"-";
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


		$this->assign("c",$c);
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//域名注册模板审核
	public function template_sh(){
		global $uid;
		$id = R("id","int");
		$status = R("status","int");
		$info_row = M("domain_register_info")->get_row("id = '{$id}'");
		if (!isset($info_row['uid'])) {
			tAjax::json_error("模板不存在！");
		}
		if (tUtil::check_hash()) {
			//更改记录状态
			$res = M("domain_register_info")->set_data(array("is_use"=>$status))->update("id = '{$id}'");
			if ($res) {
				tAjax::json_success("提交成功");
			}
			tAjax::json_success("保存成功");
		}else{
			tAjax::json($info_row);
		}
	}
	//域名注册信息AJAX请求
	public function domain_info(){
		global $uid;
		$id = R("id","int");
		$info_row = M("register_domain_attachinfo")->get_row("id = '{$id}'");
		if (!isset($info_row['uid'])) {
			tAjax::json_error("信息不存在！");
		}
		tAjax::json($info_row);
	}
	//域名解析记录
	public function  domain_order(){
		$domain = R("domain","string");
		$page     = R("page","int");
		$page     = $page?$page:1;
		
		$data['page']  = $page;
		$data['where'] = "domain = '{$domain}'";
		$data['pagesize'] = 30;
		$data['order'] = "dateline DESC";
		$result = Q("register_order_item")->get_list($data,"__page__?");
		foreach ($result['list'] as $key=>$val) {
			$result['list'][$key]['youhui'] = sprintf("%.2f",($val['amount_promation']));
			if ($val['num'] >= 10) {
				$result['list'][$key]['price'] = sprintf("%.2f",($val['price']*$val['num']));
			}
		}
		$result['pagebar'] = tFun::pagebar_js($result['pagebar'],"__page__?","show_domain_order",array($domain));
		tAjax::json($result);
	}
	//域名操作日志全部
	public function domain_log(){
		$do         = R("do","string");
		$page     = R("page","int");
		$page     = $page?$page:1;
		$pageurl = U("/domain_register/domain_log?do=get");
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
			$result = M("@register_domain_log")->get_list($data,$pageurl);
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
	//域名操作日志单个域名
	public function domain_log_get(){
		$domain = R("domain","string");
		$page     = R("page","int");
		$page     = $page?$page:1;

		$data['page']  = $page;
		$data['where'] = "domain = '{$domain}'";
		$data['pagesize'] = 30;
		$data['order'] = "id DESC";
		$result = Q("register_domain_log")->get_list($data,"__page__?");
		$result['pagebar'] = tFun::pagebar_js($result['pagebar'],"__page__?","show_domain_log",array($domain));
		tAjax::json($result);
	}
	//修改域名NS
	public function domain_ns_edit(){
		$domain = R("domain","string");
		$domain_row = M("register_domain")->get_row("domain = '{$domain}'");
		if (!isset($domain_row['id'])) {
			tAjax::json_error("域名不存在");
		}
		if (tUtil::check_hash()) {
			$ns1 = R("ns1","string");
			$ns2 = R("ns2","string");
			$ns = R("ns","string");

			if (empty($ns1) || empty($ns2)) {
				tAjax::json_error("域名NS不能为空！");
			}
			if ($ns1 == $ns2) {
				tAjax::json_error("请填写两个不同的域名NS！");
			}
			$data = array(
				"dns1" =>$ns1,
				"dns2" =>$ns2,
			);
			$ns_str = $ns1.";".$ns2;

			if ($ns == $ns_str) {
				tAjax::json_error("与原NS相同！");
			}

			$res = SDKdomain::modify_dns("ModDns",$domain,$data);
			if ($res['status'] == 1) {
				M("register_domain")->set_data(array('ns'=>$ns_str))->update("domain = '{$domain}'");
				//域名DNS修改日志
				M("@register_domain")->log("域名DNS修改","域名名称：{$domain},修改DNS:{$ns_str}",array('domain_id'=>$domain_row['id'],'domain'=>$domain));
				tAjax::json_success("修改成功！");
			}else{
				tAjax::json_error("修改失败，请检查域名状态！");
			}
		}else{
			$ns = array();
			if (M("domain")->get_one("domain = '{$domain}'","count('domain_id')")) {
				$ns_row = M("@domain_ns_group")->get_cache_by_ns("free");
				if (isset($ns_row['ns'])) {
					$ns = explode(";",$ns_row['ns']);
				}
			}
			tAjax::json_success($ns);
		}
	}
	//域名续费
	public function domain_renew(){
		global $timestamp;
		$domain = R("domain","string");
		
		//判断域名是否存在
		$domain_row = M("register_domain")->get_row("domain = '{$domain}'");
		if (!isset($domain_row['id'])) {
			tAjax::json_error("域名不存在！");
		}
		
		if (tUtil::check_hash()) {
			$num = R("num","int");
			
			// 判断年限是否为空
			if (empty($num)) {
				tAjax::json_error("请选择续费年限！");
			}

			//域名到期时间,续费年限
			$renew_data = array(
				'begindate' => date("Y-m-d",$domain_row['exp_time']),
				"period" => $num,
			);
 			$renew_res = SDKdomain::domain_renew("DomainRenew",$domain,$renew_data);
			if ($renew_res['status'] != 1) {//续费失败
				$t_str = "续费失败！";
				if (isset($renew_res['list']['err'])) {
					if ($renew_res['list']['err'] == "invalid-renew") {
						$t_str = "续费超过最长期限";
					}elseif ($renew_res['list']['err'] == "credit-fail") {
						$t_str = "余额不足";
					}elseif ($renew_res['list']['err'] == "clintId-error") {
						$t_str = "没有域名的管理权限";
					}elseif ($renew_res['list']['err'] == "system-error") {
						$t_str = "系统错误";
					}elseif ($renew_res['list']['err'] == "pending-resore") {
						$t_str = "域名已经在偿还期，不能续费";
					}
				}
				tAjax::json_error($t_str);
			}else {//续费成功
				//更新数据表到期时间
				M("register_domain")->set_data(array("exp_time"=>strtotime("+{$num} years",$domain_row['exp_time']),"renew_type"=>2,"renew_dateline"=>$timestamp))->update("uid = '{$domain_row['uid']}' AND domain = '{$domain}'");
				//域名续费日志
				M("@register_domain")->log("域名续费","域名名称：{$domain},续费年限:{$num}年",array('domain_id'=>$domain_row['id'],'domain'=>$domain));
				tAjax::json_success("续费成功");
			}
		}else{
			$price = 0;
			$suffix_str = substr($domain,strpos($domain,".")+1);
			$price_total = tCache::read("domain_register_price1");
			foreach ($price_total as $k=>$v) {
				if ($suffix_str == $v['name']) {
					$price = Intval($v['renew_price']);
				}
			}
			if ($price == 0) {
				tAjax::json_error("域名价格未找到！");
			}
			tAjax::json_success($price);
		}
	}
	//获取域名管理密码，域名自助管理平台
	public function domain_self_admin(){
		$domain = R("domain","string");

		//判断域名是否存在
		$domain_row = M("register_domain")->get_row("domain = '{$domain}'");
		if (!isset($domain_row['id'])) {
			tAjax::json_error("域名不存在！");
		}
		$psw_row = SDKdomain::get_domain_key("GetProductKey",$domain);
		if ($psw_row['status'] != 1) {
			tAjax::json_error("系统错误！");
		}else {
			if (isset($psw_row['list']['key'])) {
				tAjax::json_success($psw_row['list']['key']);
			}else{
				tAjax::json_error("获取管理密码失败！");
			}
		}
	}
}
?>
