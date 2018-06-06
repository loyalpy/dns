<?php
class import_manager extends UCAdmin{
	private $dbinfo = array(
			"pdo" => 1,
			"type" => "mysql",
			"port" => "3306",
			"host" => "115.2.24.55",
			"user" => "websql123132123user",
			"passwd"=>"we32132123",
			"name"=>"dnswebdb",
			"tablepre" => "rc_",
			"charset"  => "utf8",
			"timeout"  => 30,
		);
	function __construct(){
		parent::__construct('import_manager');
	}
	public function domain_refresh(){
		set_time_limit(7200);
		ob_end_clean();
		ob_implicit_flush(true);
		echo str_repeat(' ' ,1000);

		$query = new tQuery("domain"); 
		$c = array();
		$c['pagesize'] = 500;
		$c['page']     = 1;
		$result = $query->get_list($c);
		//dump($result);
		//exit(0);
		foreach($result['list'] as $key => $v){
			$this->queue_d($v);
		}
		//处理后面的页码
		for($page=2;$page<=$result['totalpage'];$page++){
			$c['page'] = $page;
			$result = $query->get_list($c);
			foreach($result['list'] as $key => $v){
				$this->queue_d($v);
			}
		}
	}
	public function import(){
		die("已导入");
		set_time_limit(7200);
		ob_end_clean();
		ob_implicit_flush(true);
		echo str_repeat(' ' ,1000);

		$query = new tQuery("members",$this->dbinfo);
		$c = array();
		$c['pagesize'] = 500;
		$c['page']     = 1;
		$result = $query->get_list($c);
		//dump($result);
		//exit(0);
		foreach($result['list'] as $key => $v){
			$this->import_u($v);
		}
		//处理后面的页码
		for($page=2;$page<=$result['totalpage'];$page++){
			$c['page'] = $page;
			$result = $query->get_list($c);
			foreach($result['list'] as $key => $v){
				$this->import_u($v);
			}
		}
	}
	public function queue_d($v){
		M("@domain")->queue($v['domain'],"update_record");
		echo "{$v['domain']}<br/>";
		ob_flush();
        flush();
		usleep(10*1000);//500ms
	}
	public function import_u($userinfo){
		static $fails,$success;
		/*userinfo
		id=>uid,password,
		email,
		mobile,
		username=>uname,
		ni=>nickname,
		realname,
		signname,
		birthday,
		sex,
		inlock,
		yesemail=>emailrz,
		yesmobile=>mobilerz,
		reg_time=>regdateline,
		reg_ip=>regip
		last_login_ip=>logip
		last_login_time=>logdateline
		login_num=>logtimes,
		--------------------
		//point,balance,payrate    utype,ulevel,urole
		*/
		if($userinfo['id'] < 1000){
			echo $userinfo['id']."<br/>";
			$fails ++;
		}else{
			$udata = array(
				"uid"      => $userinfo['id'],
				"password" => $userinfo['password'],
				"email"    => $userinfo['email'],
				"mobile"   => $userinfo['mobile'],
				"uname"    => $userinfo['username'],
				"nickname" => $userinfo['ni'],
				"realname" => $userinfo['realname'],
				"signname" => $userinfo['signname'],
				"sex"      => $userinfo['sex'],
				"inlock"   => $userinfo['inlock'],
				"emailrz"  => $userinfo['yesemail'],
				"mobilerz" => $userinfo['yesmobile'],

				"regdateline" => $userinfo['reg_time'],
				"regip" => $userinfo['reg_ip'],
				"logip" => $userinfo['last_login_ip'],
				"logdateline" => $userinfo['last_login_time'],
				"logtimes" => $userinfo['login_num'],

				"utype"  =>1,
				"ulevel" => 1,
				"urole"  => 0,
			);
			//账户数据
			$account_data = array(
				"balance" => "=".$userinfo['balance'],
				"point"   => "=".$userinfo['point'],
			);

			$domain_obj = new tModel("dns_domain",$this->dbinfo);
			$record_obj = new tModel("dns_records",$this->dbinfo);
            $uid = $userinfo['id'];

            $myuser = M("user")->get_row("uid='{$uid}'");

            if(isset($myuser['uid'])){
	            $uid = $myuser['uid'];
	            M("user")->set_data($udata);
	            $ret = M("user")->update("uid='{$uid}'");
	        }else{
	            M("user")->set_data($udata);
	            $ret = M("user")->add();
	        }
	        if($uid){
	        	$ret = M("@account")->update($uid,$account_data);
	        }


            $domains = $domain_obj->get_one("members_id = '{$userinfo['id']}'","count(id)");
            if($domains == 0){
            	echo "[<span style='color:red'>fail</span>] uid:{$uid}, domains is empty break;";
            	echo "<br/>";
            	$fails ++;
            }else{
	            if($uid){
	            	echo "[<span style='color:green'>success</span>] add uid:{$uid}, domains:";
	            	//套餐转换
	            	$service_groups = array(
	            		"0" => "free",
	            		"1" => "vip1",
	            		"2" => "vip2",
	            		"3" => "vip3",
	            		"4" => "vip9",
	            	);
	            	$ns_groups = array(
	            		"1" => "free",
	            		"2" => "vip11",
	            		"4" => "vip21",
	            		"8"  => "vip21",
	            		"5"   => "vip31",
	            		"9"    => "vip31",
	            	);
	            	$domainlist = $domain_obj->query("indel=0 AND members_id='{$uid}'");
	            	if($domainlist){
	            		//删除该用户下所有域名
	            		M("domain")->del("uid='{$uid}'");
	            		foreach ($domainlist as $key => $value) {
	            			/*members_id  => uid,
								domain,ttl,records,
								service_id => service_group,
								service_expiry,
								dateline,
								status,
								group_id = >0,
								sort,flag,
								inerror => inns,
								lastupdate,
								nsg_id => ns_group
								bz,
								cndomain =>
								iscn =>
				            	*/
				            //如果域名存在跳过
				            $domain_v = $value['domain'];
				            if(M("domain")->get_one("domain='{$domain_v}'","count(*)")>0){
				            	continue;
				            }

	            			$domain_data = array(
	            				"uid"=>$uid,
	            				"domain"=>$value['domain'],
	            				"domain_cn"=>$value['cndomain'],
	            				"is_cn"=>$value['iscn'],
	            				"ttl"=>$value['ttl'],
	            				"records" => $value['records'],
	            				"dateline"=> $value['dateline'],
	            				"lastupdate"=>$value['lastupdate'],
	            				"status"=>$value['status'],
	            				"inlock"=>0,
	            				"inflag"=>$value['flag'],
	            				"inns"=> ($value['inerror']?0:1),
	            				"service_group"=> isset($service_groups[$value['service_id']])?$service_groups[$value['service_id']]:"free",
	            				"service_expiry"=> $value['service_expiry'],
	            				"ns_group"=>isset($ns_groups[$value['nsg_id']])?$ns_groups[$value['nsg_id']]:"free",
	            				"bz"=>$value['bz'],
	            				"indel"=>0,
	            				);
	            			M("domain")->set_data($domain_data);
	            			$domain_id = M("domain")->add();
	            			$domain = $value['domain'];
	            			$old_domain_id = $value['id'];
	            			if($domain_id){
	            				echo $value['domain'];
	            				//读取所有记录
	            				M("domain_record")->del("domain='{$domain}'");
	            				$records = array();
	            				$recordlist = $record_obj->query("domain_id='{$old_domain_id}'");
	            				if($recordlist){
	            					$acls = array(
	            						"11"=>"any",
	            						"6" =>"fori",
	            						"76"=>"cn",
	            						"1" =>"tel",
	            						"2" =>"cnc",
	            						"4" =>"cmcc",
	            						"5" =>"ctc",
	            						"3" =>"edu",
	            						"43"=>"hktw",

	            						"7" =>"seo_baidu",
	            						"8" =>"seo_guge",
	            						"9" =>"seo_yahu",
	            						"10"=>"seo_sougou",

	            						"40"=> "tel_xb_xinjiang",
	            						"15"=>"tel_hd_fujian",
	            						"22"=>"tel_hz_henan",
	            						"29"=>"tel_db_liaoning",
	            						"36"=>"tel_hd_shanghai",
	            						"75"=>"tel_xn_chongqing",
	            						"18"=>"tel_hn_guangxi",
	            						"25"=>"tel_hz_hunan",
	            						"32"=>"tel_xb_qinghai",
	            						"39"=>"tel_xn_xizang",//西藏电信
	            						"14"=>"tel_hb_beijing",//北京电信
	            						"21"=>"tel_hb_hebei",//河北电信
	            						"28"=>"tel_hd_jiangxi",//江西电信
	            						"35"=>"tel_xb_shanxi",//陕西电信
	            						"42"=>"tel_hd_zhejiang",//浙江电信
	            						"17"=>"tel_hn_guangdong",//广东电信
	            						"24"=>"tel_hz_hubei",//湖北电信
	            						"31"=>"tel_xb_ningxia",//宁夏电信
	            						"38"=>"tel_hb_tianjin",//天津电信
	            						"13"=>"tel_hd_anhui",//安徽电信
	            						"20"=>"tel_hn_hainan",//海南电信
	            						"27"=>"tel_hd_jiangsu",//江苏电信
	            						"34"=>"tel_hb_shanxi",//山西电信
	            						"41"=>"tel_xn_yunnan",//云南电信
	            						"16"=>"tel_xb_gansu",//甘肃电信
	            						"23"=>"tel_db_heilongjiang",//黑龙江电信
	            						"30"=>"tel_hb_neimneg",//内蒙古电信
	            						"37"=>"tel_xn_sichuan",//四川电信
	            						"19"=>"tel_xn_guizhou",//贵州电信
	            						"26"=>"tel_db_jilin",//吉林电信
	            						"33"=>"tel_hd_shandong",//山东电信

	            						"72"=>"cnc_xn_yunnan",//云南联通
	            						"47"=>"cnc_xb_gansu",//甘肃联通
	            						"54"=>"cnc_db_heilongjiang",//黑龙江联通
	            						"61"=>"cnc_hb_neimenggu",//内蒙古联通
	            						"68"=>"cnc_xn_sichuan",//四川联通
	            						"50"=>"cnc_xn_guizhou",//贵州联通
	            						"57"=>"cnc_db_jilin",//吉林联通
	            						"64"=>"cnc_hd_shandong",//山东联通
	            						"71"=>"cnc_xb_xinjiang",//新疆联通
	            						"46"=>"cnc_hd_fujian",//福建联通
	            						"53"=>"cnc_hz_henan",//河南联通
	            						"60"=>"cnc_db_liaoning",//辽宁联通
	            						"67"=>"cnc_hd_shanghai",//上海联通
	            						"74"=>"cnc_xn_chongqing",//重庆联通
	            						"49"=>"cnc_hn_guangxi",//广西联通
	            						"56"=>"cnc_hz_hunan",//湖南联通
	            						"63"=>"cnc_xb_qinghai",//青海联通
	            						"70"=>"cnc_xn_xizang",//西藏联通
	            						"45"=>"cnc_hb_beijing",//北京联通
	            						"52"=>"cnc_hb_hebei",//河北联通
	            						"59"=>"cnc_hd_jiangxi",//江西联通
	            						"66"=>"cnc_xb_shanxi",//陕西联通
	            						"73"=>"cnc_hd_zhejiang",//浙江联通
	            						"48"=>"cnc_hn_guangdong",//广东联通
	            						"55"=>"cnc_hz_hubei",//湖北联通
	            						"62"=>"cnc_xb_ningxia",//宁夏联通
	            						"69"=>"cnc_hb_tianjin",//天津联通
	            						"44"=>"cnc_hd_anhui",//安徽联通
	            						"51"=>"cnc_hn_hainan",//海南联通
	            						"58"=>"cnc_hd_jiangsu",//江苏联通
	            						"65"=>"cnc_hb_shanxi",//山西联通

	            					);
	            					foreach($recordlist as $v2){
	            						$records[] = array(
	            								"domain_id"=>$domain_id,
	            								"domain" =>$domain,
	            								"acl"=>(isset($acls[$v2['aclid']])?$acls[$v2['aclid']]:"any"),
	            								"acltype"=>"DI",
	            								"RRname"=>$v2['RRname'],
	            								"RRtype"=>($v2['RRtype'] == 'URL显性'?'URLY':($v2['RRtype'] == 'URL隐性'?'URLN':$v2['RRtype'])),
	            								"RRvalue"=>$v2['RRip'],
	            								"RRmx"=>$v2['RRmx'],
	            								"RRttl"=>$v2['RRttl'],
	            								"status"=>$v2['status'],
	            								"inlock"=>$v2['insh'] ===0?1:0,
	            							);
	            					}
	            					$ret = 0;
	            					if($records){
	            						$ret = M("domain_record")->add_more($records);
	            					}
	            					echo "<font style='color:red'>(".count($records).")</font>";
	            				}
	            				echo ";";
	            			}
	            		}
	            	}
	            }
            	echo "<br/>";
            	$success++;
            }
		}
		ob_flush();
        flush();
		usleep(10*1000);//500ms
	}

	
	//更新线上数据：批量
	public function ImportRegister()
	{
		set_time_limit(7200);
		ob_end_clean();
		ob_implicit_flush(true);
		echo str_repeat(' ' ,1000);

		$c = array();
		$c['pagesize'] = 500;
		$c['page']     = 1;
		$Q = Q("register_domain");
		$result = $Q->get_list($c);
		foreach($result['list'] as $key => $v){
			if (count($v) > 0) {
				$this->ImportStart($v['domain']);
			}
		}
		//处理后面的页码
		for($page=2;$page<=$result['totalpage'];$page++){
			$c['page'] = $page;
			$result = $Q->get_list($c);
			foreach($result['list'] as $key => $v){
				if (count($v) > 0) {
					$this->ImportStart($v['domain']);
				}
			}
		}
	}
	//更新线上数据：单个
	public function ChangeOnlineDomain(){
		global $timestamp;
		$domain = R("domain","string");
		if (empty($domain)) {
			tAjax::json_error("域名不存在！");
		}
		$r_arr = $this->ImportStart($domain,1);
		if ($r_arr['status'] == 1) {
			tAjax::json_error($r_arr['info']);
		}else{
			tAjax::json_success($r_arr['info']);
		}
	}
	//更新线上数据
	public function ImportStart($val,$parameter = 0){
		global $timestamp;

		//读取联系人信息
		$regType = SDKdomain::check_info("queryDomainInfo",$val,"regType");
		//读取管理人信息
		$admType = SDKdomain::check_info("queryDomainInfo",$val,"admType");

		if (isset($regType['list']['ret']) &&  isset($admType['list']['ret']) && $regType['list']['ret'] == 100 && $admType['list']['ret'] == 100) {

			//读取域名后缀缓存 判断是0国际域名，1国内域名
			$suffix_p = M("@domain_register_price")->get_cache_by_agent(1);
			$suffix_arr = array();
			if (count($suffix_p) > 0) {
				foreach ($suffix_p as $k=>$v) {
					if ($v['type'] == 4 || $v['type'] == 5) { //国内域名
						$suffix_arr[] = ".".$v['name'];
					}
				}
				$suffix_arr = array_unique($suffix_arr);
			}
			$reg_domain_type_tmp = substr($val,strpos($val,"."));
			$reg_domain_type = 0;
			if (in_array($reg_domain_type_tmp,$suffix_arr)) {
				$reg_domain_type = 1;
			}

			//域名注册表 start*****************************************************************************************
			$ns1 = isset($regType['list']['dns1'])?$regType['list']['dns1']:"";
			$ns2 = isset($regType['list']['dns2'])?";".$regType['list']['dns2']:"";

			//用户uid
			$uid = M("register_domain")->get_one("domain='{$val}'","uid");
			if (!$uid) {
				$uid = 1741;
			}

			$domain_data = array(
				"domain"=>$val,
				"uid"=> $uid,
				"type"=>$reg_domain_type,
				"reg_type"=>1,
				"reg_time"=>strtotime($regType['list']['applyDate']),
				"exp_time"=>strtotime($regType['list']['expireDate']),
				"agent"=>1,
				"ns"=>$ns1.$ns2,
				"status"=>1,//导入
				"dateline"=>$timestamp
			);
			//域名注册表 end*****************************************************************************************

			//域名注册信息关联表表 start*****************************************************************************************
			$mobile = explode("-",$regType['list']['r_phone']);
			if ($mobile[2] == "null") {
				$mobile = 0;
			}else{
				if ($mobile[0] == "null" || $mobile[0] == 0) {
					$mobile = $mobile[2];
				}else{
					if (strlen($mobile[2]) == 11) {
						$mobile = $mobile[2];
					}else{
						$mobile = $mobile[0].$mobile[2];
					}
				}
			}
			$cz = explode("-",$regType['list']['r_fax']);
			$m_mobile = explode("-",$admType['list']['a_phone']);
			if ($m_mobile[2] == "null") {
				$m_mobile = 0;
			}else{
				if ($m_mobile[1] == "null" || $m_mobile[1] == 0) {
					$m_mobile = $m_mobile[2];
				}else{
					if (strlen($m_mobile[2]) == 11) {
						$m_mobile = $m_mobile[2];
					}else{
						$m_mobile = $m_mobile[1].$m_mobile[2];
					}
				}
			}
			if ($regType['list']['r_zip'] == "null") {
				$ub = 0;
			}else{
				$ub = $regType['list']['r_zip'];
			}

			if ($admType['list']['a_zip'] == "null") {
				$m_ub = 0;
			}else{
				$m_ub = $admType['list']['a_zip'];
			}

			$domain_data_info = array(
				"uid"=>$uid,
				"aller_name_cn"=>$regType['list']['r_organize_name_cn'],
				"aller_name"=>$regType['list']['r_organize_name_uk'],
				"name_cn"=>$regType['list']['r_user_name_cn'],
				"name"=>$regType['list']['r_user_name_uk'],
				"email"=>$regType['list']['r_email'],
				"area"=>$regType['list']['r_province_cn'].",".str_replace("'","",$regType['list']['r_city_cn']),
				"addr_cn"=>$regType['list']['r_street_cn'],
				"addr"=>$regType['list']['r_street_uk'],
				"ub"=>$ub,
				"mobile"=>(strlen($mobile) > 11)?0:$mobile,
				"cz"=>"0".$cz[1].",".$cz[0].",".$cz[2],
				"m_name_cn"=>$admType['list']['a_organize_name_cn'],
				"m_name"=>$admType['list']['a_organize_name_uk'],
				"m_email"=>$admType['list']['a_email'],
				"m_area"=>$admType['list']['a_province_cn'].",".str_replace("'","",$admType['list']['a_city_cn']),
				"m_addr_cn"=>$admType['list']['a_street_cn'],
				"m_addr"=>$admType['list']['a_street_uk'],
				"m_ub"=>$m_ub,
				"m_mobile"=>(strlen($m_mobile) > 11)?0:$m_mobile,
			);
			//域名注册信息关联表表 end*****************************************************************************************

			//判断域名是否已存在域名注册表中
			$domain_id = M("register_domain")->get_one("domain='{$val}'","id");
			if ($domain_id) {
				unset($domain_data['reg_type']);
				$res_update_domain = M("register_domain")->set_data($domain_data)->update("domain = '{$val}'");
				if ($res_update_domain) {
					$res_update_info_domain = M("register_domain_attachinfo")->set_data($domain_data_info)->update("did = '{$domain_id}'");
					if ($res_update_info_domain) {
						if ($parameter == 1) {
							return array("status"=>0,"info"=>"更新成功");
						}else{
							echo "{$val}"."&nbsp;";
							echo "<span style='color: green'>[ok]</span>"."&nbsp;&nbsp;";
							echo "<span style='color: green'>update  is ok . update domain id is {$domain_id}</span>"."<br/>";
						}
					}else{
						if ($parameter == 1) {
							return array("status"=>0,"info"=>"数据已同步，无需更新");
						}else{
							echo "{$val}"."&nbsp;";
							echo "<span style='color: orange'>[ok]</span>"."&nbsp;&nbsp;";
							echo "<span style='color: orange'>the info is same to online  . so no need update</span>"."<br/>";
						}
					}
				}else{
					if ($parameter == 1) {
						return array("status"=>1,"info"=>"更新失败");
					}else{
						echo "{$val}"."&nbsp;";
						echo "<span style='color: red'>[ok]</span>"."&nbsp;&nbsp;";
						echo "<span style='color: orange'>update is failed  . update id is {$domain_id}</span>"."<br/>";
					}
				}
			}else{
				if ($parameter == 1) {
					return array("status"=>1,"info"=>"域名不存在");
				}else{
					$res_add_domain = M("register_domain")->set_data($domain_data)->add();
					if ($res_add_domain) {
						$domain_data_info['did'] = $res_add_domain;
						$domain_info_add = M("register_domain_attachinfo")->set_data($domain_data_info)->add();
						if ($domain_info_add) {
							echo "{$val}"."&nbsp;";
							echo "<span style='color: green'>[ok]</span>"."&nbsp;&nbsp;";
							echo "<span style='color: green'>add is ok . add id is {$res_add_domain} . and attach info id is {$domain_info_add}</span>"."<br/>";
						}else{
							echo "{$val}"."&nbsp;";
							echo "<span style='color: red'>[failed]</span>"."&nbsp;&nbsp;";
							echo "<span style='color: red'>add is ok . add id is {$res_add_domain} . but attach  is failed</span>"."<br/>";
						}
					}else{
						echo "{$val}"."&nbsp;";
						echo "<span style='color: red'>[failed]</span>"."&nbsp;&nbsp;";
						echo "<span style='color: red'>add domain is faild</span>"."<br/>";
					}
				}
			}
		}else{
			if ($parameter == 1) {
				return array("status"=>1,"info"=>"获取线上信息失败");
			}else{
				echo "{$val}"."&nbsp;";
				echo "<span style='color: red'>[failed]</span>"."&nbsp;&nbsp;";
				echo "<span style='color: red'>domain check info is failed .  not import</span>"."<br/>";
			}
		}

		ob_flush();
		flush();
		usleep(10*1000);
	}
	//导入域名组
	public function domainArr()
	{
		return array(
			"psdcdrai.com",
			"wanligreening.com",
			"76afoot.com.cn",
			"76afoot.cn",
			"76afoot.com",
			"52afoot.com.cn",
			"52afoot.cn",
			"52afoot.com",
			"afootbike.cn",
			"afootbike.com",
			"76ifoot.com.cn",
			"76ifoot.cn",
			"76ifoot.com",
			"52ifoot.com.cn",
			"52ifoot.cn",
			"52ifoot.com",
			"ifootbike.cn",
			"ifootbike.com",
			"hzslcs.com",
			"faoin.cn",
			"faoin.com.cn",
			"turnsolebike.cn",
			"52turnsole.com",
			"576street.com",
			"76streets.com",
			"176mall.com",
			"576mall.com",
			"576rebate.com",
			"76rebate.com",
			"576jie.com",
			"921618.com",
			"goukee.com",
			"1516188.com",
			"760608.com",
			"761618.com",
			"1516118.com",
			"1516168.com",
			"hd199.cn",
			"sanbaoyuanlin.com",
			"offer.com.cn",
			"cnald.com",
			"57qc.com",
			"808cn.com",
			"syjghm.com",
			"usecn.com",
			"zjlcp.com",
			"76zp.com",
			"76px.com",
			"76work.com",
			"76renmai.com",
			"76rm.net",
			"76rm.com.cn",
			"76rm.cn",
			"qiliujie.com",
			"home163.com",
			"uu18.cn",
			"zjhtdz.com",
			"goldenbreads.com",
			"jzcake.cn",
			"ye-sheng.com",
			"bajiecdn.com",
			"8jcdn.com",
			"huangzejiayuan.com",
			"lifevt.com",
			"jingqingmiaomu.com",
			"justgoodad.com",
			"hengnuxizi.com",
			"18job.net",
			"xsglassware.com",
			"18job.com.cn",
			"18job.net.cn",
			"4008807676.cn",
			"4008807676.com",
			"yujingmm.com",
			"76jie.net",
			"76jie.cn",
			"76jie.com.cn",
			"hzcube.com",
			"dns8j.com",
			"jjcyzm.com",
			"57qy.net",
			"juyuanlin.cn",
			"57qy.cn",
			"57qy.com.cn",
			"yesbuy.cn",
			"zplgc.cn",
			"yuejiqiangwei.com",
			"zplgc.com",
			"zhejiangidc.com",
			"hangzhouidc.com",
			"wenzhouidc.com",
			"huzhouidc.com",
			"ningboidc.com",
			"shaoxingidc.com",
			"jinghidc.com",
			"lishuiidc.com",
			"taizhouidc.com",
			"linanidc.com",
			"quzhouidc.com",
			"zhoushanidc.com",
			"jiaxingidc.com",
			"sulianidc.com",
			"idcfuwuqi.com",
			"hzidcserver.com",
			"zjidcserver.com",
			"chinaidcserver.com",
			"haoidcserver.com",
			"idc88idc.com",
			"红网卫士.net",
			"红网卫士.com",
			"红网卫士.cn",
			"bbzc.net.cn",
			"score888.cn",
			"somepainting.com",
			"wzsbw.com",
			"semipv.com",
			"huamu35.com",
			"31hm.com",
			"yuanlin8.net",
			"zjtop8.com",
			"76bike.com",
			"76zd.com",
			"hongchunyl.com",
			"zjbike.com",
			"topddos.com",
			"soyuan.com",
			"zjmxrh.com",
			"hnqhyc.com",
			"76bike.net",
			"76bike.cn",
			"76bike.com.cn",
			"cntutors.org",
			"10too.com",
			"10too.net",
			"longhust.com",
			"sulidc.com",
			"111517.cn",
			"571h.com",
			"zrxfgf.com",
			"365qc.cn",
			"0576msd.com",
			"zjrcsc.com",
			"beihaijiaohui.com",
			"yuanlin8.cn",
			"hzhcxx.com",
			"571sy.com",
			"76wang.com",
			"76cun.com",
			"76cun.net",
			"zj918.com",
			"job558.com",
			"sihairencai.com",
			"web002.cn",
			"iis002.com",
			"dns002.com",
			"za88888.com",
			"zhongmiao.cn",
			"bbjie.cn",
			"57diy.com",
			"57showgo.com.cn",
			"57xiogo.com.cn",
			"57xiogo.cn",
			"57xigo.com.cn",
			"52xiaoguo.com.cn",
			"52xiaoguo.cn",
			"xiogo.com.cn",
			"xiogo.cn",
			"52showgo.com.cn",
			"52showgo.cn",
			"52xigo.com.cn",
			"52xigo.cn",
			"57xiaoguo.com",
			"57showgo.com",
			"52xiogo.com",
			"52xigo.com",
			"52showgo.com",
			"76xiogo.cn",
			"76xiogo.com.cn",
			"76xigo.com.cn",
			"76xiaoguo.com",
			"76xigo.com",
			"76showgo.com.cn",
			"76showgo.cn",
			"76showgo.com",
			"76xiogo.com",
			"57showgo.cn",
			"52xiogo.com.cn",
			"52xiogo.cn",
			"57xiogo.com",
			"57xigo.com",
			"76xigo.cn",
			"76xiaoguo.cn",
			"76xiaoguo.com.cn",
			"57xigo.cn",
			"52xiaoguo.com",
			"57xiaoguo.cn",
			"57xiaoguo.com.cn",
			"zjdun.com",
			"topdun.com",
			"tbdun.com",
			"yhdun.com",
			"yhdun.net",
			"yhdun.cn",
			"ttttzx.com",
			"quliujie.com",
			"quliujie.net",
			"bbjia.cn",
			"turnsole.cn",
			"16931.com",
			"well-king.com",
			"ajqsyy.com",
			"cdnidc.net",
			"e0e5.com.cn",
			"e0e5.cn",
			"e0e5.net",
			"e0e5.com",
			"t-best.net",
			"76home.com",
			"5shipping.com",
			"hzlazl.com",
			"yuquanmm.com",
			"contt.cn",
			"contt.net",
			"76contt.com",
			"76contt.com.cn",
			"76contt.cn",
			"76kentu.com",
			"76kentu.cn",
			"76kentu.com.cn",
			"76fein.com",
			"76fein.cn",
			"76fein.com.cn",
			"76fein.net",
			"76fengyi.com",
			"76fengyi.cn",
			"76fengyi.com.cn",
			"1015go.com",
			"1015go.cn",
			"1015go.net",
			"1015go.com.cn",
			"ecarchina.com",
			"hnlhsjd.com",
			"game360.cc",
			"hryl518.com",
			"hzformat.com",
			"zjformat.com",
			"xchtlh.com",
			"sooige.com",
			"sooige.cn",
			"sooige.com.cn",
			"sooig.cn",
			"sooig.com.cn",
			"sooigbike.com",
			"sooigbike.com.cn",
			"sooigbike.cn",
			"sooibe.com",
			"sooibe.cn",
			"sooibe.com.cn",
			"52shubi.com",
			"52shubi.com.cn",
			"52shubi.cn",
			"76shubi.com",
			"76shubi.com.cn",
			"76shubi.cn",
			"57shubi.com",
			"57shubi.com.cn",
			"57shubi.cn",
			"xiaogubike.com",
			"52xiaogu.com.cn",
			"52xiaogu.cn",
			"76xiaogu.com.cn",
			"76xiaogu.cn",
			"jxpx.cn",
			"18job.com",
			"igouke.com",
			"52qx.cn",
			"292ni.com",
			"92bike.com",
			"hzdahon.com",
			"57lx.com",
			"132ni.com",
			"57qx.com",
			"57hz.com",
			"jasonnb.com",
			"jcex.com.cn",
			"76hz.com",
			"homepageseo.com",
			"7sooig.com",
			"7sooig.cn",
			"7sooig.com.cn",
			"cxychm.com",
			"lvyummps.com",
			"symmyyc.com",
			"51bbg.com",
			"zjgsujds.com",
			"hongshigu.net",
			"cnzicai.net",
			"cnzicai.com",
			"zhilimiaopu.com",
			"zjdeli.com",
			"50hui.com",
			"zjsancai.com",
			"3939cn.com",
			"njyymp.com",
			"nhcsh.com",
			"miaomunet.com",
			"zhuzhuwu.com",
			"hongshigu.cn",
			"hongshigu.com.cn",
			"cnztb.cn",
			"zjrengong.com",
			"hk-hz.com",
			"21hd.cn",
			"55lady.com",
			"tangrenedu.cn",
			"wxdashan.com",
			"crpack.com",
			"skyidc.cn",
			"yuanxinquan.com",
			"gfnfs.com",
			"rm19.com",
			"bajieyun.cn",
			"zjqinganchem.com",
			"slcdn.com",
			"cdn002.com",
			"bbs19.com",
			"52xihu.com",
			"76jie.com",
			"xizijie.com",
			"dnss.com.cn",
			"57qy.com",
			"gamehao.net",
			"rghy.cn",
			"hzxsxjlh.com",
			"hostnet.cn",
			"76cube.com",
			"jcex.cn",
			"brighteyewear.com",
			"hzyqbpzx.com",
			"zpeca.org",
			"vipyundun.com",
			"zjyundun.com",
			"jianjunbayi.com",
			"ztmodel.com",
			"hondyn.com",
			"contt.com.cn",
			"cizhijie.com",
			"zytzkg.com",
			"jc-express.cn",
			"jc-express.com.cn",
			"pp58.com",
			"syhfmm.com",
			"rcst.cn",
			"bangongli.com",
			"zjbike.net",
			"zjbike.cn",
			"zjbike.com.cn",
			"goldtz.com",
			"808go.cn",
			"808go.com.cn",
			"808go.com",
			"yg009.com",
			"yg009.cn",
			"yg009.com.cn",
			"818yc.com",
			"600yc.com",
			"958go.cn",
			"600yc.com.cn",
			"600yc.cn",
			"818yc.com.cn",
			"818yc.cn",
			"958go.com.cn",
			"958go.com",
			"yc004.com.cn",
			"yc004.cn",
			"yc004.com",
			"108yc.com.cn",
			"108yc.cn",
			"108yc.com",
			"700yc.com.cn",
			"700yc.cn",
			"700yc.com",
			"m567.com",
			"goldhq.cn",
			"goldwh.cn",
			"goldwh.net",
			"goldwh.com.cn",
			"goldhq.com.cn",
			"meihaoshiguangyl.com",
			"cnzhuzi.com",
			"sjgold.com",
			"soleilad.com",
			"竹海一品.com",
			"cnzhyp.com",
			"ylw.so",
			"7bike.com",
			"suichanglib.com",
			"modernfeeling.com",
			"hztriace.com",
			"18job.cn",
			"icode888.xyz",
			"icode666.xyz",
			"bajiedns.xyz",
			"8jdns.xyz",
			"hzlanhong.com",
			"57lh.com",
			"bajiedns6.xyz",
			"bajiedns7.xyz",
			"bajiecloud.com",
			"hdyuanlin.com",
			"ce18.com",
			"2dns.xyz",
			"tangzhen.online",
			"ipingyuan.xyz",
			"ceshidns.xyz",
			"yuuuwer.cn",
			"myphp.xyz",
			"ycyljt.com",
			"ibjdns.xyz",
			"ttspace.xyz",
			"baiduww.top",
			"huzuoping.xyz",
			"bjdns.pw",
			"wiphp.xyz",
			"tangzhen8.xyz",
			"sulianyun.com",
			"dianpin.pw",
			"reddun.com",
			"sxyqmm.com",
			"76rm.com",
			"ycylgf.cn",
			"3rgo.cn",
			"ycyljt.cn",
			"ycgf.cn",
			"ycylgf.com",
			"hz-sancai.cn",
			"hz-sancai.com",
			"typhoonidea.com",
			"typhoon-idea.com",
			"souyu.cc",
			"taotaoju.net",
			"tddos.com",
			"sy1603.com",
			"vipcd.cn",
			"sdmedison.com",
			"yccm.com.cn",
			"feichaoshi.com",
			"hkgold.net",
			"gdfgfdg.com",
			"weewewee.net",
			"fuyangren.org",
			"199cn.com",
			"zhujian.com",
			"bajiedns.com",
			"zjlandscape.com",
			"hzqiyou.cn",
			"hzqiyou.com.cn",
			"57qiyou.com",
			"typhoon-design.com",
			"51hjwh.com",
			"gold108.com",
			"cnald.net",
			"cnald.com.cn",
			"cnald.cn",
			"messiah-design.com",
			"cnhotec.com",
			"ddjgcgq.com",
			"cyjgsj.com",
			"vod18.com",
			"cacecybkcom.cn",
			"57tb.cn",
			"hrpxsr.com",
			"hlzcn.com",
			"zjhxw.com",
			"4008838788.com",
			"51zp.com",
			"jiashanshiye.com",
			"chinaued.cn",
			"chinaued.com",
			"5yxiu.com",
			"5yxiu.net",
			"yule69.com",
			"yule69.net",
			"xinxinths.com",
			"lsbike.net",
			"xiepeilun.com",
			"zjlgy.com",
			"20051212.com",
			"051212.com",
			"dy-vision.com",
			"声崴.com",
			"tonysun.cn",
			"sw8800.com",
			"love998.com",
			"inmay.com",
			"xidao.net",
			"ycyuanlin.com",
			"yuanlinjie.cn",
			"01vb.com",
			"meanslife.com",
			"zftg.com",
			"zjzkhc.com",
			"easyshow.cc",
			"sxqiyou.com",
			"zjylw.com",
			"jxdahon.com",
			"ylec.org.cn",
			"fshmsr.com",
			"8jdns.com",
			"jxkaoyan.com",
			"zftg.net",
			"jxbike.com",
			"mxchw.com",
			"thzyy.cn",
			"ysjtgw.com",
			"okmyclub.com",
			"kwsa.com.cn",
			"suusty.com",
			"annsuu.net",
			"annsuu.com",
			"24k99.net",
			"hmdsm.com",
			"hzhnsh.com.cn",
			"gxking.com",
			"linsmath.com",
			"linbomath.com",
			"shakeland.com.cn",
			"rexen.cn",
			"rexen.com.cn",
			"ejiapay.cn",
			"taobaomaster.com",
			"niad2006.com",
			"zjedu.org.cn",
			"fyhome.com.cn",
			"jjzad.com",
			"wuzhishi.com",
			"nickyong.com",
			"wuzhishitiandao.net",
			"wuzhishitiandao.com",
			"yuanlin.com",
			"sinocables.com",
			"hzsoyu.com",
			"longshuolaw.com",
			"axsh2008.com",
			"ass2011.com",
			"kanhotel.cn",
			"wuzhishi.net",
			"coolingtower.cc",
			"ygain.com",
			"sdfm.cn",
			"gxtg.cn",
			"idc002.com",
			"5179.cn",
			"arttalk.com.cn",
			"gdzs365.com",
			"51song.cn",
			"annshow.com",
			"annshow.net",
			"dsabc.cn",
			"tfen.cn",
			"gokee.cn",
			"harborland.cn",
			"harborland.com.cn",
			"fengjingyx.com",
			"maoyecheng.com",
			"ytsjz.com",
			"lovel.cn",
			"shaiwuwang.com",
			"blyle.com",
			"yspzc.com",
			"chinaysptz.com.cn",
			"ysptrz.com.cn",
			"chinaysptz.cn",
			"chinaysptrz.com.cn",
			"ysptrz.cn",
			"chinaysptrz.cn",
			"chinaysyh.cn",
			"chinaysyh.com.cn",
			"chinayspyh.cn",
			"chinayspyh.com.cn",
			"yspyh.cn",
			"chinaysyh.com",
			"chinaysyh.net",
			"chinayspyh.com",
			"chinayspyh.net",
			"yspyh.net",
			"chinaysptrz.com",
			"ysptrz.com",
			"chinaysptz.com",
			"中国艺术银行网.com",
			"中国艺术银行网.cn",
			"中国艺术品银行网.com",
			"中国艺术品银行网.cn",
			"中国艺术品投融资网.com",
			"中国艺术品投融资网.cn",
			"中国艺术品投资网.com",
			"中国艺术品典当网.com",
			"中国艺术品典当网.cn",
			"yspdd.com",
			"zjtriace.com",
			"haomingsheng.net	",
			"fc312.net",
			"hzbjspa.com",
			"game4399.net.cn",
			"game4399.com.cn",
			"game4399.cn",
			"szzxmm.com",
			"wnssyl.com",
			"game4399.cc",
			"game4399.net",
		);
	}

	//导入199cn用户
	public function Imprt199Cn(){
		set_time_limit(7200);
		ob_end_clean();
		ob_implicit_flush(true);
		echo str_repeat(' ' ,1000);

		$domainRes = Sq("select domain from wo_register_domain where uid = 1");
		if (count($domainRes) <= 0) {
			echo "<span style='color: red'>email check is failed</span>"."<br/>";
		}
		foreach ($domainRes as $domain) {
			if ($domain['domain'] && $domain['domain'] == 'wxdashan.com') {
				$this->ImportStartDomain($domain['domain']);
			}
		}
	}
	public function ImportStartDomain($val){

		$domain199 = M("199cndomainreg")->get_row("domain = '{$val}'");

		if (isset($domain199['username'])) {
			$userRes = M("199cnuser")->get_row("username = '{$domain199['username']}'","username,passowrd,cname,email,lastlogin,regtimes");

			if (isset($userRes['username'])) {
				$uidRow = M("user")->get_row("uname = '{$userRes['username']}'");
		
				if (isset($uidRow['uid'])) {
					//如果存在就把register_domain uid 更改为读出来的uid
					$res1 = M("register_domain")->set_data(array("uid"=>$uidRow['uid']))->update("domain = '{$val}'");
					if ($res1) {
						echo "<span style='color: green'>yonghu cunzai and genggai chegngong</span>"."<br/>";
					}else{
						echo "<span style='color: red'>yonghu cunzai and genggai shibai</span>"."<br/>";
					}
				}else{
					//如果不存在的话，添加uid,然后更改register_domain uid
					$dataArr = array(
						"uname" => $userRes['username'],
						"email" => $userRes['email'],
						"password" => md5($userRes['passowrd']),
						"realname" => $userRes['cname'],
						"emailrz" => 1,
						"regdateline" => strtotime($userRes['regtimes']),
						"logdateline" => strtotime($userRes['lastlogin']),
						"source" => "199cn",
						"ulevel" => 1,
						"utype" => 1,
					);
					if(M("user")->get_one("email='{$userRes['email']}'","count(*)")){
						$dataArr['email'] = "";
					}
					$res2 = M("user")->set_data($dataArr)->add();
					if ($res2) {
						M("register_domain")->set_data(array("uid"=>$res2))->update("domain = '{$val}'");
						echo "<span style='color: green'>yonghu bucunzai and add chegngong</span>"."<br/>";
					}else{
						echo "<span style='color: red'>yonghu bucunzai and add shibai</span>"."<br/>";
					}
				}
			}
		}
		ob_flush();
		flush();
		usleep(10*1000);
	}
}