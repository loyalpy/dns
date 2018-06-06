<?php
/**
 * 用户管理
 * by Thinkhu 2014
 */
class user_manager extends UCAdmin{
	function __construct(){
		parent::__construct('user_manager');
	}
	//用户列表
	public function userlist(){
		$do = R("do","string");
	    $page = R("page","int");
	    $page = $page?$page:1;

		$condi = array(
			"keyword"   => R("keyword","string"),
			"startdate" => R("startdate","string"),
			"enddate"   => R("enddate","string"),
			"inwx"      => R("inwx","int"),
		);
		//获取排序
		$orderby = R("orderby","string","a.logdateline!DESC");

		$ut = R("ut","int");
		$where = $ut?"utype={$ut}":"1";
		$pageurl = U("/user_manager/userlist?do=get&ut={$ut}");
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND a.regdateline >=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND a.regdateline <=".strtotime($v)):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND (a.uid='{$v}' OR a.email LIKE '%{$v}%' OR mobile LIKE '%{$v}%')";
					}else{
						$where .= $v?" AND (a.uname LIKE '%{$v}%' OR a.email LIKE '%{$v}%' OR a.mobile LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND a.{$k}='{$v}'":"";
					break;
			}
		}

		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['table'] = "user AS a";
			$c['order'] = str_replace("!"," ",$orderby);
			$c['fields'] = "a.*";

			$lefttable = array("LEFT|JOIN|user_account|AS|b|ON|a.uid=b.uid"=>array("b.balance","b.point","b.sms","b.domains","b.register_domains"));
			foreach($lefttable as $key => $v){
				foreach($v as $v2){
					if(strpos($orderby,$v2) === 0){
						$c['join'] = str_replace("|", " ", $key);
						break;
					}
				}
				if(isset($c['join'])){
					break;
				}
			}

			$result = Q("user")->get_list($c,$pageurl);
			if($result['list']){
				foreach ($result['list'] as $k => $v){
					$cuid = $v['uid'];
					$cuserinfo = C("user")->get_cache_userinfo($cuid);
					$result['list'][$k]['name']    = isset($cuserinfo['name'])?$cuserinfo['name']:"数据已丢失";
					$result['list'][$k]['account'] = isset($cuserinfo['account'])?$cuserinfo['account']:array("balance"=>0,"point"=>0,"sms"=>0,"domains"=>0,"register_domains"=>0);
					$result['list'][$k]['bd']      = isset($cuserinfo['bd'])?$cuserinfo['bd']:array("status"=>0);
				}
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist",array($orderby));
			$result['error']   = 0;
			$result['orderby'] = $orderby;
 			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		//处理角色
		$tmplist =  M("user_role")->query("","id,company,name","company ASC");
		$role_res = $rolelist = array();
		foreach($tmplist as $v){
			$rolelist[$v['id']] = $v;
			if($v['id'] > 1){
				$role_res[] = array("v"=>"{$v['name']}","key"=>$v['id']);
			}
		}
		//处理会员等级
		$ulevel = $ulevel_res = array();
		$utypes = C("user")->get_utype();
		foreach($utypes as $k=>$v){
			$ulevel[$k] = array_values(C("user")->get_ulevel($k));
			$ulevel_res[$k] = array();
			if($ulevel[$k]){
				foreach($ulevel[$k] as $k2=>$v2){
					$ulevel_res[$k][] = array("v"=>"{$v2['alias']}","key"=>$v2['ident']);
				}
			}
		}
		
		$ns_group = $service_group = array();
		$ns_group = M("@domain_ns_group")->get_list(false);
		$service_group = M("@domain_service")->get_list(0);
		$this->assign("ns_group",$ns_group);
		$this->assign("service_group",$service_group);


		$this->assign("pageurl",$pageurl);
		$this->assign("rolelist",$rolelist);
		$this->assign("role_res",$role_res);
		$this->assign("utypes",$utypes);
		$this->assign("ulevel",$ulevel);
		$this->assign("ulevel_res",$ulevel_res);
		$this->assign("condi",$condi);
		$this->assign("ut",$ut);
		$this->display();
	}
	//企业用户列表
	public function userlist_com(){
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;

		$condi = array(
			"keyword"   => R("keyword","string"),
			"startdate" => R("startdate","string"),
			"enddate"   => R("enddate","string"),
			"inwx"      => R("inwx","int"),
		);
		//获取排序
		$orderby = R("orderby","string","d.logdateline!DESC");

		$ut = 2;
		$where = $ut?"utype={$ut}":"1";
		$pageurl = U("/user_manager/userlist_com?do=get&ut={$ut}");
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND d.regdateline >=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND d.regdateline <=".strtotime($v)):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND d.uid='{$v}'";
					}else{
						$where .= $v?" AND (a.company_name LIKE '%{$v}%' OR d.email LIKE '%{$v}%' OR d.mobile LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND a.{$k}='{$v}'":"";
					break;
			}
		}

		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['fields'] = "a.*";
			$c['fields'] .= ",d.*";
			$c['fields'] .= ",e.balance,e.point,e.sms,e.domains,e.register_domains";
			$c['join'] = "LEFT JOIN user AS d ON a.uid = d.uid LEFT JOIN user_account AS e ON a.uid = e.uid";
			$c['order'] = str_replace("!"," ",$orderby);
			$result = Q("company AS a")->get_list($c,$pageurl);

			//读出所有认证
			$uids = array();
			foreach($result['list'] as $key => $v){
				$uids[] = $v['uid'];
			}
			if($uids){
				$rzs = array();
				$tmps = M("rz")->query("uid IN('".implode("','",$uids)."')");
				foreach($tmps as $k2=>$v2){
					if(!isset($rzs[$v2['uid']])){
						$rzs[$v2['uid']] = array();
					}
					$rzs[$v2['uid']][$v2['name']] = $v2;
				}
			}

			if($result['list']){
				foreach ($result['list'] as $k => $v){
					$cuid = $v['uid'];
					$cuserinfo = C("user")->get_cache_userinfo($cuid);
					$result['list'][$k]['name']    = isset($cuserinfo['name'])?$cuserinfo['name']:"数据已丢失";
					$result['list'][$k]['account'] = isset($cuserinfo['account'])?$cuserinfo['account']:array("balance"=>0,"point"=>0,"sms"=>0,"domains"=>0,"register_domains"=>0);
					$result['list'][$k]['bd']      = isset($cuserinfo['bd'])?$cuserinfo['bd']:array("status"=>0);
					$result['list'][$k]['rzs'] 		= isset($rzs[$v['uid']])?$rzs[$v['uid']]:array();
				}
			}



			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist",array($orderby));
			$result['error']   = 0;
			$result['orderby'] = $orderby;
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		//处理角色
		$tmplist =  M("user_role")->query("","id,company,name","company ASC");
		$role_res = $rolelist = array();
		foreach($tmplist as $v){
			$rolelist[$v['id']] = $v;
			if($v['id'] > 1){
				$role_res[] = array("v"=>"{$v['name']}","key"=>$v['id']);
			}
		}
		//处理会员等级
		$ulevel = $ulevel_res = array();
		$utypes = C("user")->get_utype();
		foreach($utypes as $k=>$v){
			$ulevel[$k] = array_values(C("user")->get_ulevel($k));
			$ulevel_res[$k] = array();
			if($ulevel[$k]){
				foreach($ulevel[$k] as $k2=>$v2){
					$ulevel_res[$k][] = array("v"=>"{$v2['alias']}","key"=>$v2['ident']);
				}
			}
		}
		$this->assign("pageurl",$pageurl);
		$this->assign("rolelist",$rolelist);
		$this->assign("role_res",$role_res);
		$this->assign("utypes",$utypes);
		$this->assign("ulevel",$ulevel);
		$this->assign("ulevel_res",$ulevel_res);
		$this->assign("condi",$condi);
		$this->assign("ut",$ut);
		$this->display();
	}
	//企业用户审核
	public function userlist_com_edit(){
		global $timestamp;
		$uuid = R("uuid","int");
		if(empty($uuid)){
			tAjax::json_error("请选择要认证的用户");
		}
		$items = array("shenfenzheng","jigou");
		if(tUtil::check_hash()){
			foreach($items as $v){
				$status = R("{$v}","int");
				if($status){
					M("rz")->set_data(array("status"=>$status))->update("uid={$uuid} AND name='{$v}'");
				}else{
					tAjax::json_error("请选择审核结果");
				}
			}
			C("user")->update_user($uuid,array("ulevel"=>R("ulevel","int")));
			tAjax::json(array("error"=>0,"message"=>"操作成功","callback"=>"reload"));
		}else{
			$result = array("uuid"   => $uuid);

			$rzs = array();
			$tmps = M("rz")->query("uid = {$uuid}");
			foreach($tmps as $k2=>$v2){
				$rzs[$v2['name']] = $v2;
			}
			foreach($items as $v){
				$result[$v] = isset($rzs[$v])?($rzs[$v]['status']>1?$rzs[$v]['status']:0):0;
			}
			$result['ulevel'] = M("user")->get_one("uid = '{$uuid}'","ulevel");
			//证件号码
			$company_id = M("company")->get_one("uid = '{$uuid}'","company_id");
			$result['carts'] = M("company_ext")->get_one("company_id = '{$company_id}'","content");

			tAjax::json($result);
		}
	}
	//用户删除
	public function userlist_del(){
		$uid = R("uid","int");
		$ret = C("user")->del_user($uid);
		if($ret == 1){
			tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>"reload"));
		}else{
			tAjax::json_error("删除失败！可能权限不够!");
		}
	}
	//用户编辑
	public function userlist_edit(){
		global $timestamp;
		$edit_id = R("uid","int");
		if(tUtil::check_hash()){
			$data = array(
	    		"uname"      => R("uname","string"),
	    		"realname"   => R("realname","string"),
	    		"password"   => R("pass","string"),
	    		//"area"       => 0,
	    		//"sex"        => R("sex","int"),
	    		//"birthday"   => R("birthday","string"),
	    		"company"  => R("company","int"),
	    		"depart"   => R("depart","string"),
	    		"post"     => R("post","string"),
	    		//"phone"      => R("phone","string"),
	    		"email"      => R("email","string"),
	    		"mobile"     => R("mobile","string"),
	    		"emailrz"    => R("emailrz","int"),
	    		"mobilerz"   => R("mobilerz","int"),
	    		"inlock"     => R("inlock","int"),
	    		"expiry"     => R("expiry","string"),
	    		"urole"      => R("urole","int"),
	    		"ulevel"     => R("ulevel","int"),
				"utype"	 => R("utype","int"),
	    		"bz"         => R("bz","string")
	    	);
	    	$data['expiry'] = strtotime($data['expiry']);
	    	//处理特殊权限
	    	$upurviews = array();
	    	for($i=0;$i<=20;$i++){
	    		$tmp = R("upurview{$i}","string");
	    		$tmp = empty($tmp)?"N":$tmp;
	    		$upurviews[] = $tmp;
	    	}
	    	$data['upurview'] = implode("",$upurviews);
	    	if($this->userinfo['urole'] == 1){

	    	}else{
	    		if(in_array($data['urole'],array(1,2))){
	    			tAjax::json_error("非法设置角色！");
	    		}
	    	}

	    	//检查手机邮箱用户名不能同时为空
	    	if(empty($data['uname']) && empty($data['email']) && empty($data['mobile'])){
	    		tAjax::json_error("用户名/手机/邮箱不能同时为空！");
	    	}
	    	if($data['uname']){
	    		$row = M("user")->get_row("uname='{$data['uname']}' AND uid<>'{$edit_id}'");
	            if(!empty($row)){
	            	tAjax::json_error("该用户名已经存在");
	            }
	    	}

	    	//检查手机
	    	if($data['mobile'] && tValidate::is_mobile($data['mobile'])){
	    		$row = M("user")->get_row("mobile='{$data['mobile']}' AND uid<>'{$edit_id}'");
	            if(!empty($row)){
	            	tAjax::json_error("该手机已经存在");
	            }
	    	}

	    	//检查邮箱
	    	if($data['email'] && tValidate::is_email($data['email'])){
	    		$row = M("user")->get_row("email='{$data['email']}' AND uid<>'{$edit_id}'");
	            if(!empty($row)){
	            	tAjax::json_error("该邮箱已经存在");
	            }
	    	}

	    	//检查密码
	    	if($data['password'] && strlen($data['password'])>5 && strlen($data['password'])<19){
	    		$data['password'] = md5($data['password']);
	    	}else{
	    		unset($data['password']);
	    	}
	    	$appkey = R("appkey","string");
	    	$appstatus = R("appstatus","int");
	    	$appstatus = $appstatus?1:0;
	    	if(strlen($appkey) !== 32){
	    		tAjax::json_error("App KEY 必须为32位字符");
	    	}
	    	if($edit_id == 0){
	    		M("user")->set_data($data);
	    		$ret = M("user")->add();
	    		if($ret){
	    			M("api_user")->set_data(array(
	    				"appkey" => $appkey,
	    				"uid"    => $ret,
	    				"status" => $appstatus
	    			))->add();
	    			C("user")->log("添加用户","用户名：{$data['uname']},用户ID:$ret;");
	    		}
	    	}else{
	    		$uname = $data['uname'];
		    	if($edit_id == 1){
		    		unset($data['uname'],$data['password'],$data['email'],$data['mobile'],$data['expiry'],$data['urole'],$data['upurview'],$data['inlock']);
		    	}
		    	M("user")->set_data($data);
	    		if(M("user")->update("uid='{$edit_id}'")){
		    		C('user')->set_cache_userinfo($edit_id,null);
		    		C("user")->log("修改用户","用户名：{$uname},用户ID:{$edit_id}");
		    	}

		    	$appid = M("api_user")->get_one("uid=$edit_id","appid");
		    	if(empty($appid)){
		    		M("api_user")->set_data(array(
		    			"appkey" => $appkey,
		    			"uid"    => $edit_id,
		    			"status" => $appstatus
		    		))->add();
		    	}else{
		    		M("api_user")->set_data(array(
		    			"appkey" => $appkey,
		    			"status" => $appstatus
		    		))->update("appid=$appid");

		    		tCache::del("appuser_{$appid}");
		    	}

	    	}
	    	tAjax::json(array("error"=>0,"message"=>"保存成功！","callback"=>"close"));
		}else{
			$res = array("uid"=>0,"uname"=>"","realname"=>"","email"=>"","mobile"=>"","emailrz"=>0,"mobilerz"=>0,"appid"=>"未生成","appkey"=>md5($timestamp));
			if($edit_id >0 ){
				$res = C("user")->get_user("uid='{$edit_id}'");
				$res['inlock'] = array($res['inlock']);
				$res['emailrz'] = array($res['emailrz']);
				$res['mobilerz'] = array($res['mobilerz']);
				$res['expiry'] = tTime::get_datetime("Y-m-d",$res['expiry']);
				for($i=0;$i<strlen($res['upurview']);$i++){
					$res["upurview{$i}"] = $this->check_upurview($i)?"Y":"N";
				}
				$appuser = M("api_user")->get_row("uid='{$edit_id}'");
				if(isset($appuser['appid'])){
					$res['appid']  = $appuser['appid'];
					$res['appkey'] = $appuser['appkey'];
					$res['appstatus'] = $appuser['status'];
				}else{
					$res['appid']  = "未生成";
					$res['appkey'] = md5($timestamp);
				}
			}
			tAjax::json($res);
		}
	}
	//用户设置编辑
	public function userlist_setting(){
		global $uid;
		$edit_uid = R("uid","int");
		$userinfo = C("user")->get_cache_userinfo($edit_uid);
		if(tUtil::check_hash()){
			$ret = 0;
			if(!isset($userinfo['uid']) || empty($userinfo['uid'])){
				tAjax::json_error("错误的会员ID");
			}
			//值
			$ik = R("keys","string");
			$iv = R("vals","string");
			$insert_data = array();
			if($ik){
				foreach($ik as $k=>$v){
					if($v && in_array($v,array_keys($userinfo['setting']['ulevel_data']))){
						$insert_data[] = array(
							'uid'   => $userinfo['uid'],
							"name"  => $ik[$k],
							"value" => $iv[$k]
						);
					}
				}
			}
			$ret = M("user_setting")->del("uid={$userinfo['uid']}");
			if(count($insert_data) > 0){
				$ret = M("user_setting")->add_more($insert_data);
			}
			C("user")->set_cache_userinfo($edit_uid,null);
			C("user")->log("更新用户配置","[管理员[{$uid}] 用户ID[{$userinfo['uid']}]");
			tAjax::json(array("error"=>0,"message"=>"操作成功!","callback"=>'close'));
		}else{
			$userinfo['ulevel_name'] = $userinfo['setting']['ulevel_name'];
			tAjax::json($userinfo);
		}
	}
	//用户缓存刷新
	public function userlist_refresh(){
		$uid = R("uid","int");
		C("user")->set_cache_userinfo($uid,null);
		tAjax::json_success("刷新缓存成功！");
	}
	//用户充值与退款
	public function userlist_recharge(){
        global $uid;
		$id      = R('id',"int");
	    $balance = R('balance',"float");
	    $sms     = R("sms","int");
	    $point   = R("point","int");
	    $exp     = R("exp","int");
		$note = R("note","string");

	    $updata = $upnote = array();
	    $comnote = "管理员";
	    if(is_numeric($balance) && $balance != 0){
	    	$pre = $balance >0?"+":"-";
	    	$balance = abs($balance);
	    	$updata['balance'] = "{$pre}{$balance}";
			$upnote['balance'] = !empty($note)?$note:$comnote.($pre == "+"?"增加":"减少")."余额 {$balance}";
	    }
	    if(is_numeric($sms) && $sms != 0){
	    	$pre = $sms >0?"+":"-";
	    	$point = abs($sms);
	    	$updata['sms'] = "{$pre}{$sms}";
			$upnote['sms'] = !empty($note)?$note:$comnote.($pre == "+"?"增加":"减少")."短信量 {$sms}";
	    }
	    if(is_numeric($point) && $point != 0){
	    	$pre = $point >0?"+":"-";
	    	$point = abs($point);
	    	$updata['point'] = "{$pre}{$point}";
			$upnote['point'] = !empty($note)?$note:$comnote.($pre == "+"?"增加":"减少")."积分 {$point}";
	    }
	    if(is_numeric($exp) && $exp != 0){
	    	$pre = $exp >0?"+":"-";
	    	$exp = abs($exp);
	    	$updata['exp'] = "{$pre}{$exp}";
			$upnote['exp'] = !empty($note)?$note:$comnote.($pre == "+"?"增加":"减少")."经验 {$exp}";
	    }
		if(!empty($id)){
			$ret = M("@account")->update($id,$updata,$upnote,$uid);
			if($ret){
				C("user")->log('操作用户账户',"[管理员{$uid}][用户{$id}][操作：".implode(",",$upnote)."]");
				tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"reload"));
			}else{
				tAjax::json_error("操作失败");
			}
			return;
		}else{
			tAjax::json_error('请选择要操作的会员');
			return;
		}
	}
	//快登
	public function userlist_quicklogin(){
		$uid = R("uid","int");
		if(empty($uid)){
			$this->_msg("用户为空!","用户UID不能为空,请联系管理员","/ucenter#返回首页");
			die;
		}
		$userinfo = M("user")->get_row("uid='{$uid}'");
		if(isset($userinfo['uid'])){
			$uid = $userinfo['uid'];
			$utype = $userinfo['utype'];

			//记住登录状态
			tSafe::set('uid',$uid);
			tSafe::set('utype',$utype);
			tCookie::set("log_name",$userinfo['uname'],1*86400);
			$authstr  = $userinfo['password']."\t".$userinfo['uname']."\t".$uid;

			tCookie::set("auth",$authstr,1*86400);
			$this->redirect(U('account@/ucenter/index'));
		}else{
		 	$this->_msg("用户不存在!","UID对应的用户信息不存在,请联系管理员","/ucenter#返回首页");
		}
	}
	//模块配置
	public function module_config(){
		$ut = R("ut","int");
		$utypes = C("user")->get_utype();
		$ut = in_array($ut,array_keys($utypes))?$ut:current(array_keys($utypes));
		$this->assign("ut",$ut);
		$this->assign("utypes",$utypes);
		if($this->userinfo['urole'] == 1){
			//管理员登录检查
			$do = R("do");
			$cls_cate = new cls_category("user_module","c.utype={$ut}");
			switch($do){
				case "refresh":
					$cls_cate->clear();
					tAjax::json_success("刷新成功！");
					break;
				case "del":
					$id = R("id","int");
					if(M("user_module")->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
						tAjax::json_error("该模块下有子模块！不能删除");
					}
					if(M("user_module")->del("id='{$id}'")){
						$cls_cate->clear();
						tAjax::json_success("删除成功！");
					}else{
						tAjax::json_error("删除失败！");
					}
					break;
				case "copy":
					$id = R("id","int");
					if($id){
						$ret = M("user_module")->get_row("id='{$id}'");
						if(isset($ret['id'])){
							unset($ret['id']);
							M("user_module")->set_data($ret)->add();
							$cls_cate->clear();
						}
					}
					tAjax::json_success("操作成功！");
					break;
				case "edit":
					$id = R("id","int");
					if(tUtil::check_hash()){
						$data = array(
							"pid"         => R("pid","int"),
							"name"        => R("name","string"),
							"enname"      => R("enname","string"),

							"module"      => R("module","string"),
							"action"      => R("action","string","index"),

							"status"      => R("status","int"),
							"isopen"      => R("isopen","int",0),

							"extaction"   => R("extaction","string"),
							"description" => R("description","string"),

							"sort"        => R("sort","int")
						);
						$data['url'] = R("url","string","/{$data['module']}/{$data['action']}");
						if(empty($data['name'])){//！
							tAjax::json_error("模块名不能为空！");
						}
						if($id == 0){
							$data['utype'] = $ut;
							M("user_module")->set_data($data);
							$ret = $id = M("user_module")->add();
						}else{
							$catlist = $cls_cate->get(0);
							if($data['pid'] && !isset($catlist[$data['pid']])){
								tAjax::json_error("上级已经不存在！");
							}

							if($data['pid'] == $id){
								tAjax::json_error("上级不能为自己！");
							}
							M("user_module")->set_data($data);
							$ret = M("user_module")->update("id=$id");
						}
						$cls_cate->clear();
			    		tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));
					}else{
						$ret = array();
						if($id){
							$ret = M("user_module")->get_row("id='{$id}'");
						}
						if(!isset($ret['id'])){
							$ret = array("name"=>"","id"=>0,"status"=>1,"isopen"=>0,'pid'=>'');
						}
						$ret['status'] = array($ret['status']);
						$ret['isopen'] = array($ret['isopen']);
						$cls_cate->clear();
						tAjax::json($ret);
					}
					break;
				case "get":
					$return = array();
					$return['list'] = $cls_cate->get(0,0);
					$return['list'] = array_merge($return['list']);
					$result['error']= 0;
					tAjax::json($return);
					break;
				default:
					$this->assign("catlist",$cls_cate->json_tpl());
					$this->display();
					break;
			}
		}else{
			$this->_msg("您的权限不足!","您的权限不足,请联系管理员","/ucenter#返回首页");
		}
	}
    //用户角色列表
	public function userrole(){
		$do = R("do","string");
		$pageurl = U("/user_manager/userrole?do=get");
		if($do == "get"){
			$result = array();
			$where = "1";
			if($this->userinfo['urole'] != 1){//除了超级管理员之外
				$where .= " AND company='{$this->userinfo['company']}'";
			}
			$result['list'] = M("user_role")->query($where);
			$result['pagebar'] = "";
			tAjax::json($result);
		}
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//用户角色删除
	public function userrole_del(){
		$id = R("id","int");
		if(in_array($id,array(1,2))){
			tAjax::json_error("您越权了吧！");
		}else{
			if($this->userinfo['urole'] == 1){
				M("user_role")->del("id='{$id}'");
			}else{
				//M("user_role")->del("id='{$id}' AND company='{$this->userinfo['company']}'");
			}
			tCache::del("urole{$id}");
			C("user")->log("删除角色","角色ID：{$id}");
			tAjax::json_success("删除成功！该角色组将不再拥有权限");
		}
	}
	//用户角色编辑
	public function userrole_edit(){
		$edit_id = R("id","int");
		if(tUtil::check_hash()){
			$data = array(
	    		"name"      => R("name","string"),
	    		"content"   => R("content","string"),
	    		"company"  => R("company","string"),
	    		"inlock"     => R("inlock","int")
	    	);
	    	if($this->userinfo['urole'] != 1){//除了超级管理员之外
				$data['company'] = $this->userinfo['company'];
			}
			//权限处理
			$purview = $tmp = array();
			$tmp = R("purview","string");
			if($tmp){
				foreach($tmp as $k=>$v){
					foreach($v as $v2){
						$purview[] = $v2;
					}
					$purview[] = $k;
				}
			}
			$purview = array_unique($purview);
			$data['purview'] = "@".implode("@",$purview)."@";
			//处理特殊权限
	    	$rpurviews = array();
	    	for($i=0;$i<30;$i++){
	    		$tmp = R("rpurview{$i}","string");
	    		$tmp = empty($tmp)?"N":$tmp;
	    		$rpurviews[] = $tmp;
	    	}
	    	//$data['rpurview'] = implode("",$rpurviews);

	    	M("user_role")->set_data($data);
	    	if($edit_id == 0){
	    		M("user_role")->set_data($data);
	    		$edit_id = M("user_role")->add();
	    		C("user")->log("添加角色","角色名：{$data['name']};角色ID：{$edit_id}");
	    	}else{
	    		if($edit_id == 1){
	    			unset($data['name'],$data['company'],$data['inlock']);
	    		}
	    		if(M("user_role")->update("id='{$edit_id}'")){
	    			C("user")->log("修改角色","角色名：{$data['name']};角色ID：{$edit_id}");
		    		tCache::del("urole{$edit_id}");
		    	}
	    	}
	    	tAjax::json(array("error"=>0,"message"=>"保存成功！","callback"=>"close"));
		}else{
			$res = array("id"=>0,"company"=>"","name"=>"","purview"=>"","rpurview"=>"","content"=>"");
			if($edit_id > 0){
				$res = M("user_role")->get_row("id='{$edit_id}'");
				if(isset($res['inlock'])){
					$res['inlock'] = array($res['inlock']);
				}
			}
			$res['all_purview'] = $this->nav['nav'];
			if($res['all_purview']){
				foreach($res['all_purview'] as $k=>$v){
					foreach($v['childrens'] as $k1=>$v1){
						$tmp = explode("<br />",nl2br($v1['extaction']));
						$res['all_purview'][$k]['childrens'][$k1]['purview'] = array();
						if($tmp){
							foreach($tmp as $k2=>$v2){
								$tmp2 = explode(",",$v2);
								if(isset($tmp2[0]) && isset($tmp2[1])){
									$res['all_purview'][$k]['childrens'][$k1]['purview'][trim($tmp2[0])] = trim($tmp2[1]);
								}
							}
						}
					}
				}
			}
			//$res['all_rpurview'] = App::$data['data_config']['rpurview'];
			tAjax::json($res);
		}
	}
	//登录日志
	public function loginlog(){
		$do = R("do","string");
	    $page = R("page","int");
	    $pagesize = R("pagesize","int");
	    $page = $page?$page:1;
		$pagesize = $pagesize?$pagesize:30;
	    $companylist = tCache::read("company_config");
		$pageurl = U("/user_manager/loginlog?do=get");
		$mark = R("mark","int");
		$condi = array(
		    "startdate" => R("startdate","string"),
		    "enddate"   => R("enddate","string"),
			"keyword"   => R("keyword","string"),
			"uid"   			=> R("uid","int"),
			//"company"   => R("company","string"),
		);
		if(!$this->check_upurview(1) || empty($condi['company'])){//除了超级管理员之外
			//$condi['company'] = $this->userinfo['company'];
		}
		$where = "1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".urlencode($v);
			switch($k){
				case "startdate":
					$where .= $v?(" AND dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND dateline<=".strtotime($v)):"";
					break;
				case "uid":
					$where .= $v?(" AND uid=$v"):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND uid='{$v}'";
					}else{
						$where .= $v?" AND log_data LIKE '%{$v}%'":"";
					}
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}

		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['order'] = "dateline DESC";
			$c['pagesize'] = $pagesize;
			$result = Q("login_record")->get_list($c,$pageurl);
			if ($mark) {
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"show_login_log",array($condi['uid'],$condi['keyword']));
			}else{
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			}
			foreach($result['list'] as $key=>$v){
				$tmp  = C("user")->get_cache_userinfo($v['uid']);
				$result['list'][$key]['name'] = isset($tmp['email'])?$tmp['email']:"-";
			}

			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("pageurl",$pageurl);
		$this->assign("page",$page);
		$this->assign("companylist",$companylist);
		$this->assign("condi",$condi);
		$this->display();
	}
	//用户操作日志
	public function userlog(){
		$do = R("do","string");
	    $page = R("page","int");
	    $pagesize = R("pagesize","int");
	    $page = $page?$page:1;
	    $pagesize = $pagesize?$pagesize:30;
	    $companylist = tCache::read("company_config");
		$mark = R("mark","int");
		$pageurl = U("/user_manager/userlog?do=get");
		$condi = array(
		    "startdate" => R("startdate","string"),
		    "enddate"   => R("enddate","string"),
			"keyword"   => R("keyword","string"),
			"uid"   => R("uid","int"),
			//"company"   => R("company","string"),
		);
		if(!$this->check_upurview(1) || empty($condi['company'])){//除了超级管理员之外
			//$condi['company'] = $this->userinfo['company'];
		}
		$where = "1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND dateline<=".strtotime($v)):"";
					break;
				case "uid":
					$where .= $v?(" AND uid=$v"):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND uid='{$v}'";
					}else{
						$where .= $v?" AND (content LIKE '%{$v}%' OR author LIKE '%{$v}%' OR action LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}

		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['pagesize'] = $pagesize;
			$c['order'] = "dateline DESC";
			$result = Q('log_operation')->get_list($c,$pageurl);
			if ($mark) {
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"show_cz_log",array($condi['uid'],$condi['keyword']));
			}else{
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			}
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("pageurl",$pageurl);
		$this->assign("page",$page);
		$this->assign("companylist",$companylist);
		$this->assign("condi",$condi);
		$this->display();
	}
	//会员账户日志
	public function user_accountlog(){
		$do = R("do","string");
		$ftype = R("ftype","string");
		$pagesize = R("pagesize","int");
		$pagesize = $pagesize?$pagesize:30;
		$mark = R("mark","int");
		if(!in_array($ftype,array("balance","point","exp","sms"))){
			$ftype = "balance";
		}
		$this->assign("ftype",$ftype);

	    $page = R("page","int");
	    $page = $page?$page:1;
		$pageurl = U("/user_manager/user_accountlog?do=get&ftype={$ftype}");
		$condi = array(
			"keyword"   => R("keyword","string"),
			"startdate" => R("startdate","string"),
		    "enddate"   => R("enddate","string"),
			"auid"			=> R("auid","int"),
			"uid"			=> R("uid","int"),
		);
		$where = "ftype='{$ftype}'";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND dateline<=".strtotime($v)):"";
					break;
				case "uid":
					$where .= $v?(" AND uid=$v"):"";
					break;
				case "keyword":
					$uid = M("user")->get_one("uname LIKE '%{$v}%' OR email LIKE '{$v}' OR mobile LIKE '%{$v}%' OR nickname LIKE '%{$v}%'","uid");
					$where .= $v?" AND (note LIKE '%{$v}%' OR amount LIKE '%{$v}%' OR uid = '{$uid}')":"";
					break;
				case "auid":
					if ($v) {
						if (($v - 1) >0) {
							$where .= " AND auid > 0";
						}else{
							$where .= " AND auid = 0";
						}
					}
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}
		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['order'] = "id DESC";
			$c['pagesize'] = $pagesize;
			$result = Q("user_accountlog")->get_list($c,$pageurl);
			if($result['list']){
				foreach ($result['list'] as $k=>$v){
					$tmp = C("user")->get_cache_userinfo($v['uid']);
					$result['list'][$k]['uname'] = isset($tmp['name'])?$tmp['name']:"unknow";
					$result['list'][$k]['lsh'] = tTime::get_datetime("YmdHis",$v['dateline']).sprintf("%09d",$v['uid']);

					$tmp2 = C("user")->get_cache_userinfo($v['auid']);
					$result['list'][$k]['czz'] =  isset($tmp2['email'])?$tmp2['email']:"-";
				}
			}
			switch ($mark){
				case "1":
					$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"show_rechart_order",array($condi['uid'],$condi['keyword']));
					break;
				case "2":
					$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"show_rechart_sms_order",array($condi['uid'],$condi['keyword']));
					break;
				case "3":
					$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"show_rechart_ji_order",array($condi['uid'],$condi['keyword']));
					break;
				default:
					$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
					break;
			}
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("pageurl",$pageurl);
		$this->assign("condi",$condi);
		$this->display();
	}
	//会员类型配置
	public function utype_set(){
		//管理员登录检查
		$do = R("do");
		$item = "utype";
		switch($do){
			case "del":
				$id = R("id","int");
				$str = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $str?tUtil::unserialize($str):array();
				if(isset($datalist[$id])){
					unset($datalist[$id]);
					M('site_config')->set_data(array("value"=>serialize($datalist)));
			    	M('site_config')->update("name='dataconfig_{$item}'");
				}
				tAjax::json_success("删除成功！");
				break;
			case "edit":
				$id = R("id","int");
				$str = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $str?tUtil::unserialize($str):array();
				if(tUtil::check_hash()){
					$data = array(
						"name"       => R("name","string"),
						"code"       => R("code","int"),
					);
			    	if(empty($id) && isset($datalist[$data['code']])){
			    		tAjax::json_error("该类型编号已经存在");
			    	}else{
			    		if(isset($datalist[$data['code']]) && $data['code'] != $id){
			    			tAjax::json_error("该类型编号已经存在,会造成数据覆盖");
			    		}
			    		unset($datalist[$id]);
			    		$datalist[$data['code']] = $data;
			    	}
			    	ksort($datalist);
			    	reset($datalist);
			    	if(empty($str)){
			    		M('site_config')->set_data(array("name"=>"dataconfig_{$item}","value"=>serialize($datalist)));
			    		M('site_config')->add();
			    	}else{
			    		M('site_config')->set_data(array("value"=>serialize($datalist)));
			    		M('site_config')->update("name='dataconfig_{$item}'");
			    	}
		    		tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));
				}else{
					$ret = array("name"=>"","code"=>"");
					if(isset($datalist[$id])){
						$ret = $datalist[$id];
						$ret['id'] = $ret['code'];
					}
					tAjax::json($ret);
				}
				break;
			case "get":
				$datalist = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $datalist?tUtil::unserialize($datalist):array();
				tAjax::json(array("list"=>$datalist));
				break;
			default:
				$this->display();
				break;
		}
	}
	//会员类型积分配置
	public function ulevel_set(){
		//管理员登录检查
		$do = R("do");
		$ut = R("ut","int");
		$utypes = C("user")->get_utype();
		$ut = in_array($ut,array_keys($utypes))?$ut:current(array_keys($utypes));
		switch($do){
			case "makecache":
				tCache::flush();

				$_datalist = $grouplist = array();
				$_datalist = M("user_ulevel")->query("","*","ident ASC");
				if(!empty($_datalist)){
					foreach($_datalist as $k=>$v){
						$v['data'] = tUtil::unserialize($v['data']);
						$grouplist[$v['utype']][$v['ident']] = $v;
					}
				}
				$return = array("error"=>0,"message"=>"清除缓存成功！");
				if(!empty($grouplist)){
					foreach($grouplist as $k=>$v){
						tCache::write("user_ulevel_{$k}",$v);
					}
					tAjax::json($return);
				}else{
					tAjax::json_error("清除缓存失败！");
				}
				break;
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！","callback"=>"reload");
				$id = R("id","int");
				M("user_ulevel")->del("id='{$id}'");
				tAjax::json($return);
				break;
			case "edit_data":
				$id = R("id","int");
				if(tUtil::check_hash()){
					//值
					$ik = R("keys","string");
					$iv = R("vals","string");
					$in = R("names","string");
					$data = array();
					if($ik){
						foreach($ik as $k=>$v){
							if($v){
								$data[$v] = array(
									'item'  => $v,
									'name'  => $in[$k],
									'value' => $iv[$k],
								);
							}
						}
					}
					$ret = M("user_ulevel")->set_data(array(
						"data" => serialize($data),
					))->update("id='{$id}'");
					if($ret){
						tAjax::json(array("error"=>0,"message"=>"操作成功!","callback"=>"close"));
					}else{
						tAjax::json_error("保存失败！");
					}
				}else{
					if($id > 0){
						$ret = M("user_ulevel")->get_row("id='{$id}'");
					}
					if(!isset($ret['id'])){
						tAjax::json_error("未找到该会员等级");
					}
					$ret['data_arr'] = $ret['data']?tUtil::unserialize($ret['data']):array();
					tAjax::json($ret);
				}
				break;
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$data = array(
							"ident"  => R("ident","int"),
							"alias"  => R("alias","string"),
							"minv"   => R("minv","int"),
							"maxv"   => R("maxv","int"),
							"bz"     => R("bz","string"),
							"inlock" => R("inlock","int"),
						);
					$data['inlock'] = ($data['inlock']?1:0);
					$data['utype'] = $ut;
					if(M("user_ulevel")->get_one("ident='{$data['ident']}' AND id<>'{$id}' AND utype={$data['utype']}","count(*)")>0){
						tAjax::json_error("该标识已被使用,不能重复");
					}
					M("user_ulevel")->set_data($data);
					if($id>0){
						unset($data['utype']);
						M("user_ulevel")->update("id='{$id}'");
					}else{
						M("user_ulevel")->add();
					}
					tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));
				}else{
					if($id > 0){
						$ret = M("user_ulevel")->get_row("id='{$id}'");
					}
					if(!isset($ret['id'])){
						$ret = array("name"=>"","ident"=>"","minv"=>0,"inlock"=>0,"id"=>0);
					}
					$ret['inlock'] = array($ret['inlock']);
					tAjax::json($ret);
				}
				break;
			case "get":
				$datalist = M('user_ulevel')->query("utype='{$ut}'","*","ident ASC");
				tAjax::json(array("list"=>$datalist));
				break;
			default:
				$this->assign("utypes",$utypes);
				$this->assign("ut",$ut);
				$this->display();
				break;
		}
	}
	//会员类型积分配置
	public function account_active(){
		//管理员登录检查
		$do = R("do");
		$ut = R("ut","int");
		$utypes = C("user")->get_utype();
		$ut = in_array($ut,array_keys($utypes))?$ut:current(array_keys($utypes));
		$userlevel = C("user")->get_ulevel($ut);
		switch($do){
			case "makecache":
				$_datalist = $grouplist = array();
				$_datalist = M("user_accountset")->query("","*","ident ASC");
				if(!empty($_datalist)){
					foreach($_datalist as $k=>$v){
						$v['ulevels'] = explode(",",$v['ulevels']);
						$grouplist[$v['utype']][$v['ident']] = $v;
					}
				}
				$return = array("error"=>0,"message"=>"清除缓存成功！");
				if(!empty($grouplist)){
					foreach($grouplist as $k=>$v){
						tCache::write("user_accountset_{$k}",$v);
					}
					tAjax::json($return);
				}else{
					tAjax::json_error("清除缓存失败！");
				}
				break;
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！","callback"=>"reload");
				$id = R("id","int");
				M("user_accountset")->del("id='{$id}'");
				tAjax::json($return);
				break;
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$data = array(
						"ident" 				=> R("ident","string"),
						"alias" 					=> R("alias","string"),
						"balance" 			=> R("balance","float"),
						"point"   				=> R("point","int"),
						"sms"     				=> R("sms","int"),
						"bz"      				=> R("bz","string"),
						"ulevels" 				=> R("ulevels","string"),
						"coupon" 			=> R("coupon","float"),
						"coupon_name" 	=> R("coupon_name","string"),
						"coupon_expiry" 	=> R("coupon_expiry","int"),
						"coupon_need" 	=> R("coupon_need","int"),
						"coupon_type" 	=> R("coupon_type","int"),
						"start_expiry" 		=> R("start_expiry","string"),
						"end_expiry" 		=> R("end_expiry","string"),
						"inlock"  				=> R("inlock","int"),
					);
					$data['inlock'] = ($data['inlock']?1:0);
					$data['ulevels'] = empty($data['ulevels'])?'':implode(",",$data['ulevels']);
					$data['utype'] = $ut;
					$data['start_expiry'] = strtotime($data['start_expiry']);
					$data['end_expiry']   = strtotime($data['end_expiry']);

					//判断标识是否已使用
					if(M("user_accountset")->get_one("ident='{$data['ident']}' AND id<>'{$id}' AND utype={$data['utype']}","count(*)")>0){
						tAjax::json_error("该标识已被使用,不能重复");
					}

					//判断代金券等一系列操作
					if (!empty($data['coupon'])) {
						if (empty($data['coupon_name'])) {
							tAjax::json_error("请输入代金券名称！");
						}
						if (empty($data['coupon_expiry'])) {
							tAjax::json_error("优惠券期限不能为空！");
						}
						if (empty($data['coupon_need'])) {
							tAjax::json_error("金额限制不能为空！");
						}
					}

					//判断活动开始日期结束日期正确性
					if (empty($data['start_expiry']) || empty($data['end_expiry'])) {
						tAjax::json_error("开始时间或结束时间不能为空！");
					}
					if ($data['end_expiry'] <= $data['start_expiry']) {
						tAjax::json_error("结束时间必须大于开始时间！");
					}

					M("user_accountset")->set_data($data);
					if($id>0){
						unset($data['utype']);
						M("user_accountset")->update("id='{$id}'");
					}else{
						//如果是修改的话，开始日期不做判断
						if ($data['start_expiry'] < strtotime(date("Y-m-d"))) {
							tAjax::json_error("开始时间不能小于当前时间！");
						}
						M("user_accountset")->add();
					}
					tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));
				}else{
					if($id > 0){
						$ret = M("user_accountset")->get_row("id='{$id}'");
						$ret['start_expiry'] = empty($ret['start_expiry'])?'':tTime::get_datetime("Y-m-d H:i:s",$ret['start_expiry']);
						$ret['end_expiry'] = empty($ret['end_expiry'])?'':tTime::get_datetime("Y-m-d H:i:s",$ret['end_expiry']);
					}
					if(!isset($ret['id'])){
						$ret = array("name"=>"","ident"=>"","balance"=>"0.00","point"=>0,"sms"=>0,"inlock"=>0,"ulevels"=>"");
					}
					$ret['inlock'] = array($ret['inlock']);
					$ret['ulevels'] = explode(",",$ret['ulevels']);

					tAjax::json($ret);
				}
				break;
			case "get":
				$datalist = M('user_accountset')->query("utype='{$ut}'");
				if($datalist){
					foreach($datalist as $k=>$v){
						$datalist[$k]['ulevels_name'] = "";
						if(isset($v['ulevels'])){
							$tmp = explode(",",$v['ulevels']);
							foreach($tmp as $v2){
								$datalist[$k]['ulevels_name'] .= (isset($userlevel[$v2])?$userlevel[$v2]['alias']:"")."<br/>";
							}
						}
					}
				}
				tAjax::json(array("list"=>$datalist));
				break;
			default:
				$this->assign("userlevel",$userlevel?array_merge($userlevel):array());
				$this->assign("utypes",$utypes);
				$this->assign("ut",$ut);
				$this->display();
				break;
		}
	}
	//选择用户操作
	public function userlist_get(){
	    $page = R("page","int");
		$formstr = R("formstr","string");
	    $page = $page?$page:1;
		$condi = array(
			"keyword"   => R("keyword","string"),
			"utype"        => R("utype","int"),
		);
		$where = "1";
		foreach($condi as $k=>$v){
			switch($k){
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND uid='{$v}' OR uname LIKE '%{$v}%' OR email LIKE '%{$v}%' OR mobile LIKE '%{$v}%'";
					}else{
						$where .= $v?" AND (uname LIKE '%{$v}%' OR email LIKE '%{$v}%' OR mobile LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}

		$c = array(
			"where"		=> $where,
			"page"  		=> $page,
			"pagesize"   => 99
		);
		$result = Q("user")->get_list($c,"__page__?");
		$result['condi'] = $condi;
		$result['pagebar'] = tFun::pagebar_js($result['pagebar'],"__page__?","get_userlist",array($condi['utype'],$condi['keyword'],$formstr));
	    tAjax::json($result);
	}

	//自定义邮件发送
	public function userlist_send_email(){
		global $timestamp,$uid;

		$email = R("email","string");
		$title = R("title","string");
		$content = R("content","string");

		//判断邮件是否存在
		if (empty($email)) {
			tAjax::json_error("收件人不能为空");
		}

		//处理多邮箱
		$email = str_replace(array(" ","　"),"",$email);
		$search  = array("、",",","，",";",";","。","\n");
		$replace = "<br />";
		$email    = nl2br(str_replace($search, $replace, $email));
		$email  = explode($replace,$email);
		$email  = array_filter($email);
		$email_arr  = array_unique($email);

		//判断邮箱格式是否非法
		if (count($email_arr) > 0) {
			foreach ($email_arr as $val) {
				if ($val && !tValidate::is_email($val)) {
					tAjax::json_error("输入邮箱含有非法邮箱格式!");
				}
			}
		}else{
			tAjax::json_error("邮箱不存在!");
		}

		//判断标题或者内容是否为空
		if (empty($title) || empty($content)) {
			tAjax::json_error("标题或内容不能为空");
		}

		//发送邮件 两种发送方式，一种循环单个发，一种批量多个发
		$res = $t =  0;
		foreach ($email_arr as $k=>$v) {
			if ($v) {
				$res = C("user")->send_meail_usual($v,$title,$content);
				if ($res) {
					$t++ ;
				}
			}
		}
		if ($res) {
			tAjax::json_success("成功发送{$t}条邮箱");
		}else{
			tAjax::json_error("发送失败");
		}
	}
	//域名解析注册user_account统计
	public function userlist_account_set(){
		$c =array(
			"page"     => 1,
			"pagesize" => 500,//每页读取500条数据
			"fields"   => "uid",
			"where"    => " 1 ",
		);
		//第一页
		$res = Q("user")->get_list($c);
		foreach ($res['list'] as $k=>$v){
			//读取解析域名数
			$domains = M("domain")->get_one("uid = '{$v['uid']}'","count(domain_id)");
			//读取注册域名数
			$register_domains = M("register_domain")->get_one("uid = '{$v['uid']}'","count(id)");
			//更改user_account表数据
			if (M("user_account")->get_one("uid = '{$v['uid']}'","uid")) {//更改
				$updates = M("user_account")->set_data(array("domains"=>$domains,"register_domains"=>$register_domains))->update("uid = '{$v['uid']}'");
				if ($updates) {
					$html = "<font color='green'>update is ok and update uid is {$v['uid']}</font>";
				}else{
					$html = "<font color='orange'>hava update and update uid is {$v['uid']}</font>";
				}
				echo $html."-----------------------"."update domains is {$domains}"."------------------------"."update register_domain is {$register_domains}"."<br/>";
			}else{//添加
				$adds = M("user_account")->set_data(array("uid"=>$v['uid'],"domains"=>$domains,"register_domains"=>$register_domains))->add();
				if ($adds) {
					$html = "<font color='green'>add is ok and add uid is {$v['uid']}</font>";
				}else{
					$html = "<font color='red'>add is failed and failed uid is {$v['uid']}</font>";
				}
				echo $html."-----------------------"."add domains is {$domains}"."------------------------"."add register_domain is {$register_domains}"."<br/>";
			}
		}
		//多余第一页重复处理
		if($res['totalpage'] > 1){
			for($i = 2;$i<=$res['totalpage'];$i++){
				$c['page'] = $i;
				$res = Q("user")->get_list($c);
				foreach ($res['list'] as $k=>$v){
					//读取解析域名数
					$domains = M("domain")->get_one("uid = '{$v['uid']}'","count(domain_id)");
					//读取注册域名数
					$register_domains = M("register_domain")->get_one("uid = '{$v['uid']}'","count(id)");
					//更改user_account表数据
					if (M("user_account")->get_one("uid = '{$v['uid']}'","uid")) {//更改
						$updates = M("user_account")->set_data(array("domains"=>$domains,"register_domains"=>$register_domains))->update("uid = '{$v['uid']}'");
						if ($updates) {
							$html = "<font color='green'>update is ok and update uid is {$v['uid']}</font>";
						}else{
							$html = "<font color='orange'>hava update and update uid is {$v['uid']}</font>";
						}
						echo $html."-----------------------"."update domains is {$domains}"."------------------------"."update register_domain is {$register_domains}"."<br/>";
					}else{//添加
						$adds = M("user_account")->set_data(array("uid"=>$v['uid'],"domains"=>$domains,"register_domains"=>$register_domains))->add();
						if ($adds) {
							$html = "<font color='green'>add is ok and add uid is {$v['uid']}</font>";
						}else{
							$html = "<font color='red'>add is failed and failed uid is {$v['uid']}</font>";
						}
						echo $html."-----------------------"."add domains is {$domains}"."------------------------"."add register_domain is {$register_domains}"."<br/>";
					}
				}
			}
		}
	}
}
?>