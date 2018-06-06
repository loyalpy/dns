<?php
class crond_domain_reg extends tController{
	public function index(){
		set_time_limit(7200);
		//剩余三个月到期
//		$this->___domain_expiry("+3 months");
		//剩余一个月到期
		$this->___domain_expiry("+1 months");
		//剩余七天到期
		$this->___domain_expiry("+7 day");
		//剩余一天到期
		$this->___domain_expiry("+1 day");
		//续费期，到期后1天到30天
		$this->___domain_expiry("-1 day",2);
		//偿还期,到期后30天到60天
		$this->___domain_expiry("-1 months",3);
		//定时检测域名状态结果
		$this->___register_domain_tpl();
		//删除已删除失效域名
		$this->__domain_gq_del();
		//批量发送推广邮件
		$this->__batch_email_send_tg();
		die();
	}
	//读取满足条件 day 到期提醒 $type 1 即将到期 2 续费期 3 偿还期
	private function ___domain_expiry($day = '',$type = 1){
		global $timestamp;
		set_time_limit(7200);

		//判断如果参数不存在，则直接退出
		if (empty($day)) {
			die();
		}

		//每页读取500条数据
		$pagesize = 500;

		//读取第day日到期 发送通知
		$cur_dateline = strtotime(tTime::get_datetime("Y-m-d",$timestamp));
		$start_dateline = strtotime($day,$cur_dateline);
		$end_dateline   = $start_dateline + 86400;
		$where          = "exp_time > 0  AND exp_time>= {$start_dateline} AND exp_time < {$end_dateline}";

		$c =array(
			"page"     => 1,
			"pagesize" => $pagesize,
			"fields"   => "domain,exp_time,uid",
			"where"    => $where,
		);
		//第一页
		$res = Q("register_domain")->get_list($c);
		$this->___domain_expiry_exec($res['list'],$type,$start_dateline);

		//多余第一页重复处理
		if($res['totalpage'] > 1){
			for($i = 2;$i<=$res['totalpage'];$i++){
				$c['page'] = $i;
				$res = Q("register_domain")->get_list($c);
				$this->___domain_expiry_exec($res['list'],$type,$start_dateline);
			}
		}
	}
	//批量处用户域名到期通知
	private function ___domain_expiry_exec($res = array(),$type = 1,$cur_date = 0){
		global $timestamp;
		$cur_date     = empty($cur_date)?$timestamp:$cur_date;
		$result = array();
		foreach ($res as $v) {
			if(!isset($result[$v['uid']])){
				$result[$v['uid']] = array();
			}
			$result[$v['uid']][] = $v['domain'];
		}
		if (count($result) > 0) {
			foreach ($result as $uid => $value) {
				$this->___send_notify($uid,$value,$cur_date,$type);
				usleep(500000);
			}
		}
	}
	//发送通知
	private function ___send_notify($uid,$domains= array(),$cur_date = 0,$type = 1){
		global $timestamp;
		$cur_date     = empty($cur_date)?$timestamp:$cur_date;
		//邮件通知
		if ($type == 2) {
			$info = "已经到期,到期时间为".date("Y-m-d",$cur_date).",现处于续费期阶段，续费期时长为一个月";
		}elseif ($type == 3) {
			$info = "已经到期,到期时间为".date("Y-m-d",$cur_date).",现处于赎回期阶段，赎回期时长为一个月";
		}else{
			$info = "即将到期,到期时间为".date("Y-m-d",$cur_date);
		}
		//邮箱通知
		C("user")->send_mail(array("type"=>"domainregexptime","info"=>$info,'domain'=>implode(",",$domains)),$uid);
		//微信通知
		C("user")->send_wx(array("type"=>"domainregexptime","time"=>$cur_date,'domain'=>implode(",",$domains)),$uid);
	}

	//定时检测域名状态结果
	private function ___register_domain_tpl(){
		global $timestamp;
		set_time_limit(7200);

		$c =array(
			"page"     => 1,
			"pagesize" => 500,//每页读取500条数据
			"fields"   => "uid,tpl_name,is_use",
			"where"    => " 1 ",
		);
		//第一页
		$res = Q("domain_register_info")->get_list($c);
		$this->___domain_tpl_dn($res['list']);

		//多余第一页重复处理
		if($res['totalpage'] > 1){
			for($i = 2;$i<=$res['totalpage'];$i++){
				$c['page'] = $i;
				$res = Q("domain_register_info")->get_list($c);
				$this->___domain_tpl_dn($res['list']);
			}
		}
	}
	//定时检测域名状态结果
	private function ___domain_tpl_dn($list)
	{
		global $timestamp;
		foreach ($list as $key => $val) {
			//查看域名实名审核状态
			$up_res = SDKdomain::query_template("templateInfo", $val['tpl_name']);
			if ($up_res['status'] == 1 && isset($up_res['list']['status'])) {
				if ($up_res['list']['status'] == "04") {
					$set_status = 3;
				} elseif ($up_res['list']['status'] == "05") {
					$set_status = 4;
				} elseif ($up_res['list']['status'] == "06") {
					$set_status = 2;
				} elseif ($up_res['list']['status'] == "07") {
					$set_status = 1;
				} else {
					$set_status = 0;
				}
				if ($val['is_use'] != 2) {
					M("domain_register_info")->set_data(array("is_use" => $set_status))->update("uid = '{$val['uid']}' AND tpl_name = '{$val['tpl_name']}'");
				}
			}
		}
	}
	//删除已删除失效域名
	private function __domain_gq_del()
	{
		global $timestamp;
		set_time_limit(7200);

		$endTime = time() - 61*86400;
		$del_domains = M("register_domain")->query("exp_time > 0 AND exp_time < {$endTime}","","",500);
		if (count($del_domains) > 0) {
			foreach ($del_domains as $key=>$val) {
				if (isset($val['domain'])) {
					//删除域名注册表
					$reg_res = M("register_domain")->del("uid = '{$val['uid']}' AND domain = '{$val['domain']}'");
					if ($reg_res) {
						//删除域名注册详细信息表
						M("register_domain_attachinfo")->del("did = '{$val['id']}'");
						//删除域名注册日志表
						M("register_domain_log")->del("uid = '{$val['uid']}' AND domain = '{$val['domain']}'");
						//更改用户注册域名数
						M("@account")->update($val['uid'],array("register_domains"=>"-1"),array("register_domains"=>"注册域名删除,减少一个"));
					}
				}
			}
		}
	}
	//邮件推广
	private function __batch_email_send_tg()
	{
		global $timestamp;
		//批量发送邮件任务
		$email_res = M("email_tpl_set")->get_row("ident = 'tuiguang'");
		if (isset($email_res['id'])) {
			//小于结束时间内发送
			if ($timestamp <= $email_res['end_dateline']) {
				//执行任务条件
				$where = " 1 ";
				//会员类型
				$where .= ($email_res['u_type'] == 0)?"":"AND utype = '{$email_res['u_type']}'";
				//会员等级
				$where .= ($email_res['u_level'] === '')?"":(" AND ulevel IN(".$email_res['u_level'].")");
				//锁定状态
				$where .= " AND inlock = {$email_res['inlock']}";
				//发送页page
				$cur_date     = strtotime(tTime::get_datetime("Y-m-d",$timestamp));
				$start_date   = strtotime(tTime::get_datetime("Y-m-d",$email_res['start_dateline']));
				$page    = ($cur_date - $start_date)/86400;
				//执行批量发送邮件任务
				$map =array(
					"page"     => ($page <= 0)?1:$page,
					"pagesize" => $email_res['num'],//数量已限制，最多500封
					"fields"   => "email",
					"order"  => "uid DESC",
					"where"    => $where,
				);
				$user_res = Q("user")->get_list($map);
				//定义每隔一秒钟发送一次邮件
				$email_arr = array_filter(array_map('array_shift',$user_res['list']));
				$e_content = M("email_tpl")->get_row("id = '{$email_res['tpl_id']}'");
				if (isset($e_content['email_title'])) {
					foreach ($email_arr as $val){
						if ($val) {
							//检测禁止重复发送
							if (!M("sys_email")->get_one("email = '{$val}' AND title = '{$e_content['email_title']}'","id")) {
								//发送邮件
								C("user")->send_meail_usual($val,$e_content['email_title'],$e_content['email_content']);
								usleep(500000);
							}
						}
					}
				}
			}
		}
		exit;
	}
}
?>