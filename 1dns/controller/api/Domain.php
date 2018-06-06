<?php
/**
 * 登录
* by Thinkhu 2014
*/
class Domain extends API{
	public function __construct(){
		parent::__construct('Domain');
	}
	//获取用户所有的域名
	public function GetAllByUid(){
		global $uid;
		//自动完成加载类域名
		$domainsArr = array();
		$tmp1 = M("domain")->query("uid=$uid AND status=1 AND inlock=0","domain,domain_cn,is_cn","dateline DESC",500);
		foreach ($tmp1 as $key=>$val) {
			$domains = $val['is_cn'] == 1?$val['domain_cn']:$val['domain'];
			$domainsArr['list'][$key] = $domains;
			//判断域名是否被绑定
			if (M("domain_bind")->get_one("uid = '{$uid}' AND domain_bind = '{$domains}'","count(id)")) {
				unset($domainsArr['list'][$key]);
			}
		}
		$domainsArr['list'] = array_values($domainsArr['list']);
		$this->respons_success("ok",$domainsArr);
	}
	//域名列表
	public function GetListByUid(){
		global $uid;
		$type      	= R("type","string");
		$group_id   = R("group_id","int");
		$page     	= R("page","int");
		$keyword   	= R("keyword","string");
		$uid        		= $this->userinfo['uid'];
		if(!in_array($type,array("error","lastupdate","nogroup"))){
			$type = "all";
		}

		$where   = "uid = '{$uid}'";
		if ($type == 'error') {
			$where .= " AND inns = 0";
		} elseif ($type == 'nogroup') {
			$where .= " AND service_group = 'free'";
		}else {
			$where .= " AND 1";
		}
		if($group_id > 0){
			$where .= " AND group_id=$group_id";
		}
		if($keyword){
			$where .= " AND (domain LIKE '%{$keyword}%' OR domain_cn LIKE '%{$keyword}%')";
		}
		$data  = array(
			'where'     =>$where,
			'page'		 =>$page,
			'order'      => ($type == "lastupdate")?"lastupdate DESC":"dateline DESC",
			'pagesize' => 16,
		);
		$res = M("@domain")->get_list($data,"__page__?");

		$res['pagebar'] = tFun::pagebar_js($res['pagebar'],"__page__?","load_domains_list",array($type,$group_id,$keyword));
        //tSlog::log($res);
		$this->respons_success("加载成功",$res);
	}
	//添加域名
	public function AddByUid(){
		global $timestamp,$uid;
		$this->ChkUser();
		
		$domain = R("domain","string");
		$domain = strtolower($domain);
		$domain_cn = $domain;
		$is_cn     = 0;

		if(!tValidate::is_domain($domain)){
			$this->respons_error(L("dns.1000"),array('domain'=>$domain));
		}
		if(tValidate::is_cn($domain)){
			$is_cn = 1;
			App::uselib("tools.idna_convert");
			$idna_convert_obj = new idna_convert();
			$domain = $idna_convert_obj->encode($domain);
			unset($idna_convert_obj);
		}else{
			$domain_cn = "";
		}
		//黑白名单
		if(M("domain_whiteblack")->get_one("domain='{$domain}'","count(id)") > 0){
			$this->respons_error(L("dns.1002"),array('domain'=>$is_cn==1?$domain_cn:$domain));
		}
		//用户添加域名总数判断
		if(M("domain")->get_one("uid='{$uid}'","count(domain)")>=500){
			$this->respons_error("最多添加500个域名",array('domain'=>$is_cn==1?$domain_cn:$domain));
		}
		//域名是否已经被其他人注册,可触发点击取回事件.
		$domain_id = M("domain")->get_one("domain='{$domain}' AND uid <> '{$uid}'","domain_id");
		if($domain_id){
			$exists_email = '';
			$exists_uid = M("domain")->get_one("domain='{$domain}'","uid");
			if ($exists_uid) {
				$exists_email = M("user")->get_one("uid='{$exists_uid}'","email");
				if ($exists_email && strpos($exists_email, '@')) {//设置邮箱部分已星号代替
					$email_array = explode("@", $exists_email);
					$count = 0;
					$str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $exists_email, -1, $count);
					$exists_email = (strlen($email_array[0]) < 4) ? "" : substr($exists_email, 0, 3) . $str;
				}
			}
			$this->respons_error("域名已存在",array("domain_id"=>$domain_id,'domain'=>$is_cn==1?$domain_cn:$domain,"email"=>$exists_email));
		}

		//域名是否已经被自己注册
		$domain_id = M("domain")->get_one("domain='{$domain}' AND uid = '{$uid}'","domain_id");
		if($domain_id){
			$this->respons_error("您已添加此域名",array("domain_id"=>$domain_id,'domain'=>$is_cn==1?$domain_cn:$domain));
		}


		//判断域名是否在八戒DNS官网注册
		$is_our_reg = 0;
		$is_our_reg_time = strtotime('-2 months',$timestamp);
		if (M("register_domain")->get_one("domain = '{$domain}' AND exp_time > $is_our_reg_time","id")) {
			$is_our_reg = 1;
		}

		//插入数据
		$data = array(
			"domain"     => $domain,
			"is_cn"      => $is_cn,
			"domain_cn"  => $domain_cn,
			"dateline"   => $timestamp,
			"lastupdate" => $timestamp,
			"uid"        => $uid,
			"service_group" => "free",
			"ns_group"      => "free",
			"is_our_reg"		=>$is_our_reg,
			"bz"            => "",
			"sysbz"         => "",
			"qps"           => 10000,
			"ttl"           => 600,
		);
		$service_group = M("@domain_service")->get_cache($data['service_group']);
		if (!isset($service_group['service_group'])) {
			$this->respons_error("暂不支持免费套餐");
		}
		if(isset($service_group['data']['QPS']['value'])){
			$data['qps'] = $service_group['data']['QPS']['value'];
		}

		$domain_id = M("domain")->set_data($data)->add();
		if($domain_id){
			M("@domain")->queue($domain,"add_domain");
			//更改用户解析域名数
			M("@account")->update($uid,array("domains"=>"+1"),array("domains"=>"添加域名,增加一个"));
			$this->respons_success(L("dns.1003"),array("domain_id"=>$domain_id,'domain'=>$domain,"is_cn"=>$is_cn,"domain_cn"=>$domain_cn));
		}else{
			$this->respons_error(L("dns.1004"));
		}
	}
	//保存域名设置
	public function Save(){
		global $timestamp,$uid;
		$this->ChkUser();
		$domain_id = R("domain_id","int");

		$data = array(
			'ttl' => R("ttl","int"),
			"bz"  => R("bz","string"),
			"lastupdate" => $timestamp
		);
		//设置TTL
		$ttl = R("ttl","int");
		if($ttl){
			$data['ttl'] = $ttl;
		}
		//设置域名管理密码
		$password = R("password","string");
		if($password){
			if(strlen($password) < 6 || strlen($password) > 18){
				tAjax::json_error("管理密码为6-18位!");
			}
			$data['password'] = md5($password);
		}

		$res = M("domain")->get_row("domain_id='{$domain_id}'");
		if (!isset($res['domain'])) {
			$this->respons_error(L("dns.2002"));
		}
		if ($res['inlock'] == 1) {
			$this->respons_error("该域名处于锁定状态，请解锁后再进行操作！");
		}
		//TTl判断
		$service_group = M("@domain_service")->get_cache($res['service_group']);
		if (!isset($service_group['service_group'])) {
			$this->respons_error("域名套餐不存在");
		}
		if(isset($data['ttl']) && $data['ttl'] < $service_group['data']['litTtl']['value']){
			$this->respons_error("TTL值超出了限制");
		}
		if (isset($data['ttl']) && $data['ttl']!=$res['ttl']) {
			M("@domain")->log("更改ttl","域名：{$res['domain']}",array('domain_id'=>$res['domain_id'],'domain'=>$res['domain'],'modi_from'=>$res['ttl'],'modi_to'=>$data['ttl'],));
		}
		if (isset($data['bz']) && $data['bz']!=$res['bz']) {
			M("@domain")->log("更改备注","域名：{$res['domain']}",array('domain_id'=>$res['domain_id'],'domain'=>$res['domain'],'modi_from'=>$res['bz'],'modi_to'=>$data['bz'],));
		}
		if (isset($data['password']) && $data['password']!=$res['password']) {
			M("@domain")->log(($res['password'] == ""?"新设置":"更改")."解析管理密码","域名：{$res['domain']}",array('domain_id'=>$res['domain_id'],'domain'=>$res['domain'],'modi_from'=>"",'modi_to'=>"{$password}"));
		}
		$ret = M("domain")->set_data($data)->update("domain_id='{$domain_id}'");
		$this->respons_success(L("com.1001"));
	}
	//域名别名绑定
	public function DomainBind(){
		global $timestamp,$uid;
		$this->ChkUser();

		$domain_id = R("domain_id","int");
		$domain_bind = R("domainBind","string");
		//判断原域名是否存在
		$domain_row = M("domain")->get_row("domain_id='{$domain_id}'");
		if (!isset($domain_row['domain'])) {
			$this->respons_error("域名不存在！");
		}
		//判断域名套餐是否存在
		$service_group = M("@domain_service")->get_cache($domain_row['service_group']);
		if (!isset($service_group['service_group'])) {
			$this->respons_error("域名套餐不存在");
		}
		//判断绑定域名是否为空
		if (empty($domain_bind)) {
			$this->respons_error("绑定域名不存在！");
		}
		//判断添加域名合法性
		$domain_arr = explode(",",$domain_bind);
		$data = array();
		if (count($domain_arr) > 0) {
			$domain_arr = array_unique($domain_arr);
			//判断域名套餐是否满足别名添加条数
			if(count($domain_arr) > $service_group['data']['domainBind']['value']){
				$this->respons_error("添加域名条数超出套餐限制");
			}
			foreach ($domain_arr as $domain) {
				if ($domain) {
					if (!M("domain_bind")->get_one("uid = '{$uid}' AND domain_id = '{$domain_id}' AND domain_bind = '{$domain}'","count(id)")) {
						//判断域名是否已经被自己添加
						if (M("domain")->get_one("domain = '{$domain}' AND uid = '{$uid}'","count('domain_id')")) {
							$this->respons_error("域名{$domain}已添加，请删除后再进行绑定！");
						}
						//判断域名是否已被其他会员添加
						if (M("domain")->get_one("domain = '{$domain}' AND uid <> '{$uid}'","count('domain_id')")) {
							$this->respons_error("域名{$domain}已被其他会员添加,请先进行域名取回！");
						}
					}
					$data[] = array(
						"domain_id" 	  => $domain_id,
						"uid" 			 	  => $uid,
						"domain_bind"  => $domain,
						"dateline" 		  => $timestamp,
					);
				}
			}
		}
		if (count($data) > 0) {
			//获取原绑定域名
			$domain_bind_arr = M("domain_bind")->query("uid = '{$uid}' AND domain_id = '{$domain_id}'","domain_bind","id ASC");
			if (count($domain_bind_arr)) {
				$domain_bind_arr = implode(",",array_map('array_shift',$domain_bind_arr));
			}
			//添加之前清空域名id数据
			M("domain_bind")->del("uid = '{$uid}' AND domain_id = '{$domain_id}'");
			//添加操作
			$res = M("domain_bind")->add_more($data);
			if ($res) {
				//写入日志列表
				if ($domain_bind != $domain_bind_arr) {
					M("@domain")->log("设置域名别名","域名：{$domain_row['domain']},域名别名：{$domain_bind}",array('domain_id'=>$domain_row['domain_id'],'domain'=>$domain_row['domain'],'modi_from'=>"",'modi_to'=>"{$domain_bind}"));
				}
				//加入域名列表
				foreach ($domain_arr as $domain) {
					if ($domain) {
						SDK::web_api("/Domain/AddByUid",array("domain"=>$domain));
					}
				}
			}
		}
		$this->respons_success("保存成功！");
	}
	//获取域名绑定组
	public function GetDomainBind(){
		global $uid,$timestamp;
		$this->ChkUser();
		$domain_str = R("domains","string");

		if (empty($domain_str)) {
			$this->respons_error("false");
		}
		//传过来的一页数据30行
		$res = M("domain_bind")->query("uid = '{$uid}' AND domain_id IN({$domain_str})","domain_id,domain_bind");
		$tmp = array();
		if (count($res)) {
			foreach ($res as $k=>$v){
				$tmp[$v['domain_id']][] = $v['domain_bind'];
			}
		}
		$this->respons_success("success",$tmp);
	}
	//删除域名
	public function Del(){
		global $timestamp,$uid;
		$this->ChkUser();		
		$domain_id = R("domain_id","int");

		$res  = M("domain")->get_row("domain_id='{$domain_id}'");
		if (!isset($res['domain'])) {
			$this->respons_error(L("dns.2002"));
		}
		if ($res['inlock'] == 1) {
			$this->respons_error("该域名处于锁定状态，请解锁后再进行操作！");
		}
		if ($res['indel'] == 1) {
			$this->respons_error("该域名已被系统锁定，不能删除！");
		}
		$rst   = M("domain")->del("domain_id={$domain_id}");
		if ($rst) {
			//写入队列
			M("@domain")->queue($res['domain'],"del_domain");

			//同时删除域名记录
			M("domain_record")->del("domain_id={$domain_id}");

			//添加到删除表
			$data = array(
				'domain_id'			=> $res['domain_id'],
				'domain'			=> $res['domain'],
				'is_cn'				=> $res['is_cn'],
				'domain_cn'			=> $res['domain_cn'],
				'dateline'			=> $timestamp,
				'inns'				=> $res['inns'],
				'group_id'			=> $res['group_id'],
				'service_group'	    => $res['service_group'],
				'service_expiry'	=> $res['service_expiry'],
				'ns_group'			=> $res['ns_group'],
				'uid'				=> $uid
			);
			M("domain_del")->set_data($data)->add();

			//删除域名日志表
			M("domain_log")->del("domain='{$res['domain']}'");

			//删除此域名下的监控
			$domains = $res['is_cn'] == 1?$res['domain_cn']:$res['domain'];
			$monitor_row = M("domain_monitor")->get_row("domain='{$domains}'");
			if (isset($monitor_row['monitor_id'])) {
				M("domain_monitor_record")->del("monitor_id='{$monitor_row['monitor_id']}'");
				M("domain_monitor")->del("domain='{$domains}' AND uid='{$uid}'");
			}

            // 删除域名下的自定义线路
			M("domain_acl_set")->del("domain='{$res['domain']}'");

			//删除此域名已加入的购物车
			M("cart")->del("goods_name = '{$res['domain']}'");

			//删除域名牵引状态为0的牵引
			M("domain_qy")->del("domain = '{$res['domain']}' AND status = 0");

			//删除别名绑定域名（被绑定）
			$domain_bind_row_b = M("domain_bind")->get_row("uid = '{$uid}' AND domain_id = '{$domain_id}'");
			if (isset($domain_bind_row_b['id'])) {
				M("domain_bind")->del("domain_id = '{$domain_id}' AND uid = '{$uid}'");
			}
			//删除别名绑定域名（绑定）
			$domain_bind_row = M("domain_bind")->get_row("uid = '{$uid}' AND domain_bind = '{$domains}'");
			if (isset($domain_bind_row['id'])) {
				M("domain_bind")->del("uid = '{$uid}' AND domain_bind =  '{$domains}'");
			}

			//更改用户解析域名数
			M("@account")->update($uid,array("domains"=>"-1"),array("domains"=>"删除域名,减少一个"));

			$this->respons_success(L("com.1003"),array("domain"=>$res['domain'],"ns_group"=>$res['ns_group']));
		} else {
			$this->respons_error(L("com.2003"));
		}
	}
	//删除绑定域名
	public function DelDomainBind(){
		global $timestamp,$uid;
		$this->ChkUser();

		$domain_id 		 = R("domain_id","int");
		$domain_bind   = R("domainBind","string");
		//判断原域名是否存在
		$domain_row = M("domain")->get_row("domain_id='{$domain_id}' AND uid = '{$uid}'");
		if (!isset($domain_row['domain'])) {
			$this->respons_error("域名不存在！");
		}
		//判断绑定域名是否为空
		if (empty($domain_bind)) {
			$this->respons_error("绑定域名不存在！");
		}
		//读取绑定域名行
		$domain_bind_row = M("domain_bind")->get_row("uid = '{$uid}' AND domain_id = '{$domain_id}'");
		if (!isset($domain_bind_row['id'])) {
			$this->respons_error("域名别名不存在！");
		}
		//读取被绑定域名行
		$domain_bind_row_b = M("domain_bind")->get_row("uid = '{$uid}' AND domain_bind = '{$domain_bind}'");
		if (!isset($domain_bind_row_b['id'])) {
			$this->respons_error("域名别名不存在！");
		}
		$res = M("domain_bind")->del("uid = '{$uid}' AND domain_bind = '{$domain_bind}'");
		if ($res) {
			//写入日志列表
			M("@domain")->log("删除域名别名","域名：{$domain_row['domain']},删除域名别名：{$domain_bind}",array('domain_id'=>$domain_row['domain_id'],'domain'=>$domain_row['domain'],'modi_from'=>"",'modi_to'=>""));
			$this->respons_success("删除成功！");
		}else{
			$this->respons_error("删除失败！");
		}
	}
	//暂停启用
	public function Status(){
		global $timestamp,$uid;
		$this->ChkUser();

		$domain_id = R("domain_id","int");
		$status       = R("status", "int");
		$type = R("type","string");//判断前台后台调用
		$res  = M("domain")->get_row("domain_id='{$domain_id}'");
		if (!isset($res['domain'])) {
			$this->respons_error(L("dns.2002"));
		}
		if ($res['inlock'] == 1) {
			$this->respons_error("该域名处于锁定状态，请解锁后再进行操作！");
		}
        if(empty($domain_id)){
			$this->respons_error("非法操作");
		}
		if(!in_array($status,array(0,1))){
			$this->respons_error("非法操作");
		}
		if ($type == "admin") {
			$where = " 1 ";
		}else{
			$where = " uid = '{$uid}'";
		}
		$where .= " AND inlock=0  AND domain_id ={$domain_id} AND status = ".($status ==1?0:1);
		$num = M("domain")->set_data(array("status"=>$status))->update($where);
		if ($num == 0){
			$this->respons_error("操作失败");
		}else{
			//写入队列
			if ($status==1) {
				M("@domain")->queue($res['domain'],"update_record");
			}else{
				M("@domain")->queue($res['domain'],"del_domain");
			}
			M("@domain")->log("域名".($status == 1?"启用":"暂停"),"域名名称：{$res['domain']}",array('domain_id'=>$domain_id,'domain'=>$res['domain']));
			$this->respons_success("成功".($status == 1?"启用":"暂停")."域名");
		}
	}
	//锁定解锁
	public function Locked(){
		global $timestamp,$uid;
		$this->ChkUser();

		$domain_id = R("domain_id","string");
		$inlock       = R("inlock", "int");
		if(empty($domain_id)){
			$this->respons_error("非法操作");
		}
		if(!in_array($inlock,array(0,1))){
			$this->respons_error("非法操作");
		}

		$domain_row = M("domain")->get_row("domain_id='{$domain_id}'");
		if (!isset($domain_row['domain'])) {
			$this->respons_error(L("dns.2002"));
		}

		$num = M("domain")->set_data(array("inlock"=>$inlock))->update("uid=$uid AND domain_id IN({$domain_id}) AND inlock = ".($inlock ==1?0:1));
		if ($num == 0){
			$this->respons_error("操作失败");
		}else{
			M("@domain")->log("域名".($inlock == 1?"锁定":"解锁"),"域名名称：{$domain_row['domain']}",array('domain_id'=>$domain_id,'domain'=>$domain_row['domain']));
			$this->respons_success("成功".($inlock == 1?"锁定":"解锁")."个域名");
		}

	}
	//域名转移
	public function Trance(){
		global $timestamp,$uid;
		$this->ChkUser();

		$domain_id = R("domain_id","int");
		$uidd = R("uidd","int");
		if (empty($uidd)) {
			$this->respons_success(L("com.1001"));
		}
		$data = array(
			'uid' => R("uidd","int"),
			"lastupdate"=>$timestamp
		);
		$res = M("domain")->get_row("domain_id='{$domain_id}'");
		if ($data['uid']!=$res['uid']) {
			M("@domain")->log("域名转移","域名名称：{$res['domain']}",array('domain_id'=>$res['domain_id'],'domain'=>$res['domain'],'modi_from'=>$res['uid'],'modi_to'=>$data['uid'],));
		}
		$ret = M("domain")->set_data($data)->update("domain_id='{$domain_id}'");
		//更改用户域名个数
		if ($ret) {
			M("@account")->update($res['uid'],array("domains"=>"-1"),array("domains"=>"后台转出域名一个"));
			M("@account")->update($data['uid'],array("domains"=>"+1"),array("domains"=>"后台转入域名一个"));
		}
		$this->respons_success(L("com.1001"));
	}
	//获取组
	public function GetGroup(){
		global $uid;
		$data['where'] = "uid = '{$uid}'";
		$res = M("@domain_group")->get_list($data);
		if (empty($res)) {
			$this->respons_error("no data");
		}else{
			//加入配置域名组
			$groupArr    = tCache::read("data_config");
			if (!empty($groupArr['domain_group'])) {
				foreach($groupArr['domain_group'] as $key=>$val){
					$newarray = array(
						'group_id' =>$key,
						'name'		  =>$val,
					);
					array_push($res['list'],$newarray);
				}
			}
			//统计组所属域名个数
			$ret = Sq("select count(domain_id) as num,group_id from wo_domain where uid=$uid group by group_id");
			foreach ($ret as $key => $val) {
				foreach ($res['list'] as $k => $v) {
					if (intval($val['group_id']) == intval($v['group_id'])) {
						$res['list'][$k]["count"] = intval($val['num']);
					}
				}
			}
			$this->respons_success("获取成功",$res);
		}
	}
	//组设定
	public function SetGroup(){
		global $uid;
		$this->ChkUser();
		$domain_id = R("domain_id","string");
		$group_id       = R("group_id", "int");
		if(empty($domain_id)){
			$this->respons_error("非法操作");
		}
		if(empty($group_id)){
			$this->respons_error("分组不能为空");
		}
		if($group_id>1000 && !M("domain_group")->get_one("uid=$uid AND group_id=$group_id","count(group_id)")){
			$this->respons_error("该分组不属于您");
		}
		$ret = M("domain")->set_data(array("group_id"=>$group_id))->update("uid=$uid AND domain_id IN({$domain_id})");
		if ($ret){
			$this->respons_success("设置成功");
		}else{
			$this->respons_error("设置失败");
		}
	}
	//域名组添加
	public function AddGroup(){
		global $timestamp,$uid;
		$this->ChkUser();

		$group = R("group", "string");
		if (empty($group)) {
			$this->respons_error("域名组为空！");
		}

		$groupId = M("domain_group")->get_one("name='{$group}'","group_id");
		if($groupId){
			$this->respons_error("域名组已存在");
		}

		$groupArr    = tCache::read("data_config");
		if (is_array($groupArr['domain_group'])) {
			if (in_array($group,$groupArr['domain_group'])) {
				$this->respons_error("域名组已存在");
			}
		}

		$data = array(
			'name'	=> $group,
			'uid'		=> $uid,
		);
		$group_id = M("domain_group")->set_data($data)->add();

		if ($group_id) {
			$this->respons_success("域名组添加成功");
		}else{
			$this->respons_error("域名组添加失败");
		}
	}
	//域名组删除
	public function DelGroup(){
		global $timestamp,$uid;
		$this->ChkUser();

		$group_id = R("group_id","int");
		if (empty($group_id)) {
			$this->respons_error("请选择域名组");
		}
		$res = M("domain")->set_data(array("group_id"=>0))->update("group_id='{$group_id}'");
		$ret = M("domain_group")->del("group_id='{$group_id}'");
		if ($ret) {
			$this->respons_success(L("com.1003"));
		}else{
			$this->respons_error(L("com.2003"));
		}
	}
	//日志
	public function Log(){
		$domain_id = R("domain_id","int");
		if ($domain_id) {
			$c = array(
				"where"		=> "domain_id='{$domain_id}'",
				"page"  		=> R("page","int")?:1,
				"pagesize"   => 12,
				"order"		=> "dateline DESC"
			);
			$res = Q("domain_log")->get_list($c,"__page__?");
			if (count($res['list'])>0) {
				foreach ($res['list'] as $k=>$v) {
					$res['list'][$k]['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
				}
			}
			$res['pagebar'] = tFun::pagebar_js($res['pagebar'],"__page__?","edit_log_func",array($domain_id));
			$this->respons_success("加载成功",$res);
		}
	}
	//访问统计图形显示
	public function Maps(){
		global $timestamp;
		$domain       = R("domain","string");
		$timetype     = R("timetype","string");
		$RRname      = R("RRname","string");
		if(empty($RRname)){
			$host = "";
		}else{
			if ($RRname == '_at_') {
				$host = $domain;
			}else{
				$host = "{$RRname}.{$domain}";
			}
		}

		$res = array();
		if($timetype == "week") {
			$curr_week_dateline1 = strtotime(date('Y-m-d',strtotime('-6 days'))); //当周时间
			$curr_week_dateline2 = strtotime(date('Y-m-d',strtotime('-12 days'))); //上周时间
			for ($week = 1; $week <=7; $week++) {
				$start_dateline1 = $curr_week_dateline1 + (($week-1)*86400);
				$end_dateline1   = $start_dateline1 + 86400;

				$start_dateline2 = $curr_week_dateline2 + (($week-1)*86400);
				$end_dateline2   = $start_dateline2 + 86400;


				$res['domain1'][tTime::get_datetime("Y-m-d",$start_dateline1)] = M("@domain")->querylog($host,$domain,$start_dateline1,$end_dateline1);
				$res['domain2'][tTime::get_datetime("Y-m-d",$start_dateline2)] = M("@domain")->querylog($host,$domain,$start_dateline2,$end_dateline2);
			}
		}elseif ($timetype == "month") {
			$curr_month_dateline1 = strtotime(tTime::get_datetime("Y-m-d",$timestamp))+86400;
			$curr_month_dateline2 = strtotime(tTime::get_datetime("Y-m-d",$timestamp))+86400-86400*30;
			for($day= 31;$day > 0;$day--){
				$start_dateline1 = $curr_month_dateline1 - $day*86400;
				$end_dateline1   = $start_dateline1 + 86400;

				$start_dateline2 = $curr_month_dateline2 - $day*86400;
				$end_dateline2   = $start_dateline2 + 86400;


				$count1 = M("@domain")->querylog($host,$domain,$start_dateline1,$end_dateline1);
				$count2 = M("@domain")->querylog($host,$domain,$start_dateline2,$end_dateline2);

				$res['domain1'][tTime::get_datetime("m-d",$start_dateline1)] = $count1;
				$res['domain2'][tTime::get_datetime("m-d",$start_dateline2)] = $count2;
			}
		}else{
			$curr_day_dateline1 = strtotime(tTime::get_datetime("Y-m-d",$timestamp)); //当天时间
			$curr_day_dateline2 = strtotime(tTime::get_datetime("Y-m-d",$timestamp)) - 86400; //昨天时间
			
			$curr_hour = intval(tTime::get_datetime("H",$timestamp));
			for($hour=1;$hour<=24;$hour++){
				if($hour <= ($curr_hour+1)){
					$start_dateline1 = $curr_day_dateline1 + (($hour-1)*3600);
					$end_dateline1   = $start_dateline1 + 3600;
					$count1 = M("@domain")->querylog($host,$domain,$start_dateline1,$end_dateline1);//当天
				}else{
					$count1 = 0;
				}
				$start_dateline2 = $curr_day_dateline2 + (($hour-1)*3600);
				$end_dateline2   = $start_dateline2 + 3600;
				$count2 = M("@domain")->querylog($host,$domain,$start_dateline2,$end_dateline2);

				$key = $hour == 24?"00:00":(($hour-1).":00");
				$res['domain1'][$key] = $count1;
				$res['domain2'][$key] = $count2;
			}
		}
		$this->respons_success("ok",$res);
	}
	//更新纪录
	public function Refresh(){
		global $timestamp;
		$domain = R("domain","string");
		$ret = SDKdns::update_zone($domain);
		if($ret){
			$this->respons_success("ok");	
		}else{
			$this->respons_error("fail");
		}
	}
	//更新域名下所有自定义线路
	public function RefreshCustLine(){
		global $timestamp;
		$domain = R("domain","string");
		$ns_group = R("ns_group","string");
		$ret = SDKdns::update_cust_acl($domain,$ns_group);
		if($ret){
			$this->respons_success("ok");	
		}else{
			$this->respons_error("fail");
		}
	}
    //更新域名下单个自定义线路
	public function RefreshCustLineOne(){
		global $timestamp;
		$acl      = R("acl","int");
		$domain   = R("domain","string");
		$domain_row = M("@domain")->get($domain);
		if(isset($domain_row['domain_id'])){
			$ns_group       = $domain_row['ns_group'];
			$acl = M("@domain_acl_set")->write_romate($acl,$ns_group,false);
			$res = SDKdns::make_cust_acl($acl,$ns_group);
		}
		$this->respons_success("ok");
	}
	//域名过户
	public function DomainTransfer(){
		global $uid,$timestamp;

		$domain_ids  	= R("domain_id","string");
		$email 		    	= R("email","string");
		$psw					= R("password","string");
		if (!$domain_ids) {//提交验证
			//验证输入内容是否为空
			if (empty($email)) {
				$this->respons_error("邮箱不能为空！");
			}
			//验证邮箱格式是否正确
			if (!tValidate::is_email($email)) {
				$this->respons_error("邮箱格式不正确！");
			}
			//验证过户对象是否存在
			$user = M("user")->get_row("email = '{$email}'");
			if (!isset($user['uid'])) {
				$this->respons_error("域名过户对象不存在！");
			}
			if (!M("user")->get_one("email = '{$email}'","count(*)")) {
				$this->respons_error("域名过户对象不存在！");
			}
			//验证域名过户对象不能为自己
			if ($email == $this->userinfo['email']) {
				$this->respons_error("域名过户对象不能为自己！");
			}
			if (empty($psw)) {
				$this->respons_error("账户登录密码不能为空！");
			}
			//验证账户登录密码是否正确
			if ($this->userinfo['password'] != md5($psw)) {
				$this->respons_error("登录密码错误！");
			}
			$this->respons_success("ok");
		}else{//后台JS执行更新用户等一系列操作
			set_time_limit(7200);
			$domain_arr = explode(",",$domain_ids);
			$userId = M("user")->get_one("email = '{$email}'","uid");
			if ($userId && count($domain_arr) > 0) {
				$domainStr = '';
				foreach($domain_arr as $domain_id){
					$domain_row = M("domain")->get_row("domain_id = '{$domain_id}'");
					if(isset($domain_row['domain'])){
						//更新用户uid
						$data = array(
							'uid'						=>$userId,
							'lastupdate'			=>$timestamp,
//							'records'				=>($domain_row['inns'] == 0)?0:$domain_row['records'],
						);
						$res = M("domain")->set_data($data)->update("domain_id = '{$domain_id}'");
						if ($res) {
							//邮件发送域名拼接
							$domainStr .= "[".$domain_row['domain']."]";
							//删除域名下的记录
							if ($domain_row['inns'] == 0) {
								//M("domain_record")->del("domain = '{$domain_row['domain']}'");
							}
							//删除域名下的监控以及监控记录
							$monitorRow = M("domain_monitor")->get_row("domain = '{$domain_row['domain']}'");
							if (isset($monitorRow['monitor_id'])) {
								M("domain_monitor")->del("domain = '{$domain_row['domain']}'");
								M("domain_monitor_error")->del("monitor_id = '{$monitorRow['monitor_id']}'");
								M("domain_monitor_record")->del("monitor_id = '{$monitorRow['monitor_id']}'");
							}
							//写入域名日志
							M("@domain")->log("域名过户","过户域名：{$domain_row['domain']}，{$this->userinfo['email']}->{$email}",array('domain_id'=>$domain_row['domain_id'],'domain'=>$domain_row['domain']));
						}
					}
				}
				if (!empty($domainStr)) {
					//发送域名过户邮件通知
					C("user")->send_mail(array("type"=>"domaintransfer",'domain'=>$domainStr,'time'=>$timestamp,"tranUser"=>$this->userinfo['email']),$uid,$email);
					//发送域名过户微信通知
					$u_uid = M("user")->get_one("email = '{$email}'","uid");
					if ($u_uid) {
						C("user")->send_wx(array("type"=>"domaintransfer",'domain'=>$domainStr,'time'=>$timestamp,"tranUser"=>$this->userinfo['email']),$u_uid);
					}
				}
				
			}
			die();
		}
	}
	//自定义线路列表
	public function SetLineList(){
		global $uid;

		$domain = R("domain","string");
		$page     = R("page","int");
		$page     = $page?$page:1;
		$pageurl = U("/records/records_setline?do=get");

		//购买套餐专用功能
		$domainRow = M("domain")->get_row("domain='{$domain}'");
		if (isset($domainRow['service_group'])) {
			$server = tCache::read("service_group_".$domainRow['service_group']);
			if (count($server)) {
				if ($server['data']['diyLine']['value'] == 0) {
					$this->respons_error("当前域名套餐不支持此功能");
				}
			}
		}

		$w = array(
			"where" =>"uid = '{$uid}' AND domain='$domain' AND indel = 0",
			'pagesize' =>10,
			'order' => "id DESC",
			'page' =>$page
		);
		$res = M("@domain_acl_set")->get_list($w,$pageurl);
		$res['pagebar'] = tFun::pagebar_js($res['pagebar'],$pageurl,"show_set_line");
		$this->respons_success("加载成功！",$res);
	}
	//自定义线路单行调取
	public function getRowLineById(){
		$id = R("id","int");
		if (empty($id)) {
			$this->respons_error("自定义线路不存在！");
		}
		$lineRow = M("domain_acl_set")->get_row("id = '{$id}'");
		if (!isset($lineRow['name'])) {
			$this->respons_error("自定义线路不存在！");
		}
		$this->respons_success("ok",$lineRow);
	}
	//自定义线路添加,修改
	public function AddSetLine(){
		global $uid;

		$id		   = R("id","int");
		$domain  = R("domain","string");
		$name 	   = R("name","string");
		$ips         = R("ips","stirng");
//		$ips         = $ips?preg_split('/\r\n/',$ips):array();

		$domainRow = M("domain")->get_row("domain='{$domain}'");
		//验证域名是否存在
		if (!isset($domainRow['domain_id'])) {
			$this->respons_error("域名不存在");
		}
		//验证线路名称不能为空
		if (empty($name)) {
			$this->respons_error("线路名称不能为空！");
		}
		//去除所有空格
		$ips = str_replace(array(" ","　"),"",$ips);
		//多条处理
		$search  = array("、",",","，",";",";","。");
		$replace = "<br />";
		$ips    = nl2br(str_replace($search, $replace, $ips));
		$ips  = explode($replace,$ips);
		$ips  = array_filter($ips);
		$ips  = array_unique($ips);
        //验证IP段不能为空
		if (empty($ips)) {
			$this->respons_error("IP段不能为空！");
		}
       //处理过滤IP
		$ip_block_arr = $this->_filterIp($ips);

       //检查过滤后的IP
		if(count($ip_block_arr) == 0){
			$this->respons_error("线路IP段不存在");
		}else if(count($ip_block_arr) > 1000){
			$this->respons_error("IP段太多");
		}
        //检测输入IP是否有重复
		$chk_res = $this->_checkIp($ip_block_arr);
		if($chk_res['error'] == 1){
			$this->respons_error($chk_res['message']);
		}

		//验证自定义IP名称是否已存在
		if ($id) {//编辑验证
			if (M("domain_acl_set")->get_one("name='{$name}' AND id<>'{$id}'","count(*)")) {
				$this->respons_error("线路名称已存在！");
			}
		}else{ // 添加验证
			if (M("domain_acl_set")->get_one("name='{$name}'","count(*)")) {
				$this->respons_error("线路名称已存在！");
			}
		}
		//限制最多只能添加5个自定义IP
		if (M("domain_acl_set")->get_one("uid='{$uid}'","count(*)") > 4) {
			$this->respons_error("您目前的套餐最多只能添加5个自定义线路！");
		}

		$tmp = array();
		foreach($ip_block_arr as $v){
			$tmp[] = $v['ip_block'];
		}
		$ips_str = implode(";",$tmp);
		if ($id) {//编辑自定义线路IP
			$data = array(
				'name'        		=> $name,
				'ipaddr'      		=> $ips_str
			);
			M("domain_acl_set")->set_data($data)->update("id='{$id}'");
			$this->respons_success("保存成功！",array("id"=>$id));
		}else{//添加自定义线路IP
			$data = array(
				'name'        		=> $name,
				'uid'			  		=> $uid,
				'ipaddr'      		=> $ips_str,
				'domain'	   		=> $domainRow['domain'],
				"domain_id"	=> $domainRow['domain_id'],
				'status'				=> 1
			);
			$res = M("domain_acl_set")->set_data($data)->add();
			if ($res) {
				$this->respons_success("添加自定义线路成功！",array("id"=>$res));
			}else{
				$this->respons_error("添加自定义线路失败！");
			}
		}
	}
	//处理过滤IP
	public function _filterIp($ips){
		$ip_block_arr = array();
		foreach ($ips as $v) {
			$v = trim($v);
			$from = $to = 0;
			if (tValidate::is_ip($v)) {// 单个IP
				$from = $to =  bindec(decbin(ip2long($v)));
			} elseif (false !== ($pos = strpos($v, "-"))) { //exp:1.1.1.1-2.2.2.2
				$sip = trim(substr($v, 0, $pos));
				$eip = trim(substr($v, $pos + 1));
				if (tValidate::is_ip($sip) && tValidate::is_ip($eip)) {
					$from = bindec(decbin(ip2long($sip)));
					$to = bindec(decbin(ip2long($eip)));
					if (($to - $from) >= pow(2, 24)) {
						$this->respons_error($v . " 范围太大");
					}
				} else {
					$this->respons_error($v . " 为不正确IPv4地址");
				}
			} else if (false !== ($pos = strpos($v, "/"))) {//exp:127.0.0.1/24
				$sip     = trim(substr($v, 0, $pos));
				$mask = intval(substr($v, $pos + 1));
				if (tValidate::is_ip($sip) && $mask >= 24 && $mask <= 32) {
					$cidr2ip = tFun::cidr2ip($v, false);
					list ($a, $b) = $cidr2ip;
					$ip2cidr = tFun::ip2cidr($a, $b);
					if ($ip2cidr != NULL) {
						list ($from, $to) = tFun::cidr2ip($v);
					} else {
						$this->respons_error($v . " IP段地址不正确");
					}
				} else {
					$this->respons_error($v . " 错误的CIDR格式");
				}
			} else {
				$this->respons_error($v . " 错误的IP段");
			}
			//拼接成新的数组
			if($from && $to){
				$ip_block_arr[] = array(
					'ip_block'      	   => $v,
					"ip_block_start" => $from,
					"ip_block_end"   => $to,
				);
			}
		}
		return $ip_block_arr;
	}
	//检测输入IP是否有重复
	private function _checkIp($arr){
		$return  = array(
			"error"    => 0,
			"message"  => "",
		);
		foreach($arr as $k1 =>$v1){
			//验证结束IP不能小于开始IP
			if($v1['ip_block_start'] > $v1['ip_block_end']){
				$return['error'] = 1;
				$return['message'] = "{$v1['ip_block']}结束IP不能小于开始IP";
				return $return;
			}
			//验证不能包含局域网IP
			if(in_array(strtok(long2ip($v1['ip_block_start']), '.'), array('10', '127', '168', '192')) || in_array(strtok(long2ip($v1['ip_block_end']), '.'), array('10', '127', '168', '192'))){
				$return['error'] = 1;
				$return['message'] = "{$v1['ip_block']}不能包含局域网IP";
				return $return;
			}
			//验证是否存在重叠IP
			foreach($arr as $k2 => $v2){
				if($k1 != $k2){
					if($v1['ip_block_start'] >= $v2['ip_block_start'] && $v1['ip_block_start'] <= $v2['ip_block_end']){
						$return['error'] = 1;
						$return['message'] = "{$v1['ip_block']} 与 {$v2['ip_block']} 存在重叠IP";
						return $return;
					}
					if($v1['ip_block_end'] >= $v2['ip_block_start'] && $v1['ip_block_end'] <= $v2['ip_block_end']){
						$return['error'] = 1;
						$return['message'] = "{$v1['ip_block']} 与 {$v2['ip_block']} 存在重叠IP";
						return $return;
					}
				}
			}
		}
		return $return;
	}
	//自定义线路删除
	public function DelSetLine(){

		$id = R("id","int");
		if (empty($id)) {
			$this->respons_error("线路不存在");
		}
		$lineRow = M("domain_acl_set")->get_row("id = '{$id}'");
		if (!isset($lineRow['name'])) {
			$this->respons_error("线路不存在");
		}
		//验证自定义线路是否已被使用
		$custStr = "cust".$id;
		if (M("domain_record")->get_one("acl='{$custStr}' AND domain='{$lineRow['domain']}'","count(*)")) {
			$this->respons_error("此线路已被域名记录使用，请更改其它线路后删除！");
		}
		$res = M("domain_acl_set")->del("id = '{$id}'");
		if ($res) {
			$this->respons_success("删除成功!");
		}else{
			$this->respons_error("删除失败!");
		}
	}
}