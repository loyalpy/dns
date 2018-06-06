<?php
class ucenter extends UC{
	public function __construct(){
		parent::__construct('ucenter');
	}
	//域名管理首页,全部域名
	public function index(){
		global $uid;
        $this->display();
	}
	//域名管理首页,续费域名
	public function index_need(){
		global $uid;
		$this->display();
	}
	//域名管理首页,续费域名
	public function index_repay(){
		global $uid;

		$this->display();
	}
	//域名续费,加入购物车
	public function domain_renew(){
		global $uid,$timestamp;
		$domain  = R("domain","string");
		$domain_id  = R("domain_id","int");
		$type = R("type","int",1);

		if (empty($domain)) {
			$domain = M("register_domain")->get_one("id = '{$domain_id}' AND uid = '{$uid}'","domain");
		}

		if (!$domain) {
			tAjax::json_error("域名不存在！");
		}
		$num = M("domain_register_cart")->get_one("uid = '{$uid}' AND indel = 0 AND status = 0 AND domain = '{$domain}'","num");
		if ($num) { //更改购物车数量
			if ($type != 3) {
				if ($num == 9) {
					tAjax::json_error("域名最长续费年限9年！");
				}
				$res = M("domain_register_cart")->set_data(array("num"=>$num+1))->update("uid = '{$uid}' AND domain = '{$domain}'");
				if ($res) {
					tAjax::json_success(1);
				}else{
					tAjax::json_error("加入购物车失败！");
				}
			}
			tAjax::json_success(1);
		}else{//添加购物车
			$price = 0;
			$suffix_p_f = substr($domain,strpos($domain,".")+1);
			//获取空间服务商
			$agent  = App::get_conf("app.agent");
			if ($agent && $agent == "xinnet") {
				$agent = 1; //新网
			}else{
				$agent = 2;//万网
			}
			//读取域名后缀缓存
			$suffix_p = M("@domain_register_price")->get_cache_by_agent($agent);
			$suffix_arr = array();
			if (count($suffix_p) > 0) {
				foreach ($suffix_p as $k=>$v) {
					if ($v['type'] == 4 || $v['type'] == 5) { //国内域名
						$suffix_arr[] = ".".$v['name'];
					}
					if ($v['name'] == $suffix_p_f) {
						$price = $v['renew_price'];
					}
				}
				$suffix_arr = array_unique($suffix_arr);
			}

			$reg_domain_type_tmp = substr($domain,strpos($domain,"."));
			$reg_domain_type = 0; //判断是0国际域名，1国内域名
			if (in_array($reg_domain_type_tmp,$suffix_arr)) {
				$reg_domain_type = 1;
			}

			$data = array(
				'uid'            => $uid,
				'type'           =>$type, //1新购2,续费3,域名转入
				'num'           =>1,
				'price'          => $price,
				'domain'     => $domain,
				'dateline'     => $timestamp,
				'status'        =>0,
				"agent"       =>$agent,
				"domain_type" => $reg_domain_type,
				'indel'          =>0
			);
			$res = M("domain_register_cart")->set_data($data)->add();
			if ($res) {
				tAjax::json_success("加入购物车成功！");
			}else{
				tAjax::json_error("加入购物车失败！");
			}
		}
	}
	//订单列表
	public function order(){
		global $uid,$timestamp;
		$do 			 = R("do","string");
		$page 		 = R("page","int")?:1;
		$pageurl 	 = U("/ucenter/order?do=get");

		//查询搜索
		$where      = "1 AND uid='{$uid}' AND indel=0";
		$condi = array(
			"keyword"   => R("keyword","string"),
			"startdate"	=> R("startdate","string"),
			"enddate"   => R("enddate","string"),
		);
		foreach($condi as $k=>$v){
			$v = trim($v);
			switch($k){
				case "startdate":
					$where .= $v?" AND (dateline >= ".tTime::get_time($v).")":"";
					break;
				case "enddate":
					$where .= $v?" AND (dateline <= ".tTime::get_time($v).")":"";
					break;
				case "keyword":
					if ($v) {
						$domains = M("register_order_item")->query("domain LIKE '%{$v}%'","order_no","",500);
						$domains = implode("','",array_map('array_shift',$domains));
						if (empty($domains)) {
							$where .= " AND (order_no LIKE '%{$v}%') ";
						}else{
							$where .= " AND order_no IN('".$domains."')";
						}
					}else{
						$where .= "";
					}
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}

		if($do == "get"){
			$data['page']  = $page;
			$data['where'] = $where;
			$data['pagesize'] = 16;
			$data['order'] = "order_id DESC";
			$result = M("@register_order")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"load_order_list");
			tAjax::json(array("error"=>0,"msg"=>"加载成功","data"=>$result));
		}
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	public  function get_domain_tips(){
		global $uid;
		$num = M("domain_register_cart")->get_one("uid=$uid AND status=0 AND indel=0","count(*)");
		tAjax::json_success($num);
	}
	public function get_list_domain(){
		global $uid;
		$type			= R("type","string");
		$page     		= R("page","int");
		$keyword   	= R("keyword","string");
		$orderby 		= R("orderby","string","exp_time!DESC");

		$where   = "uid = '{$uid}'";
		if($keyword){
			$domain_did = M("register_domain_attachinfo")->query("aller_name_cn like '%{$keyword}%'","did",'',500);
			if (count($domain_did)>0) {
				$where .= " AND (id in('".implode("','",array_map('array_shift',$domain_did))."'))";
			}else{
				$where .= " AND (domain LIKE '%{$keyword}%')";
			}
		}

		//续费期，赎回期 1服务期 2续费期 3赎回期 4域名过期已删除
		//规则：国内域名 续费期：35天 赎回期 ：35天（续费期）+15天
		//规则：国际域名 续费期：30天 赎回期 ：30天（续费期）+30天
		if ($type == "need") {
			$startTime = time();
			$endTime = $startTime - 30*86400;

			$where .= " AND exp_time < '{$startTime}'";
			$where .= " AND exp_time > '{$endTime}'";
		} elseif ($type == "repay") {
			$startTime_s = time();
			$startTime = $startTime_s - 30*86400;
			$endTime = $startTime_s - 60*86400;

			$where .= " AND exp_time < '{$startTime}'";
			$where .= " AND exp_time > '{$endTime}'";
		}else{
			$startTime = time();
			$where .= " AND exp_time > '{$startTime}'";
		}

		$data  = array(
			'where'     =>$where,
			'page'		 =>$page,
			'order'      => str_replace("!"," ",$orderby),
			'pagesize' => 50,
		);
		$res = M("@register_domain")->get_list($data,"__page__?");

		$ns_group = M("domain_ns_group")->query("","ns","",500);
		foreach ($res['list'] as $key=>$list) {
			$res['list'][$key]['domain_name'] = M("register_domain_attachinfo")->get_one("did = {$list['id']}","aller_name_cn");
			foreach ($list as $k=>$v) {
				//格式化时间
				if (in_array($k,array("reg_time","exp_time"))) {
					$res['list'][$key][$k] = date("Y-m-d",$v);
				}
				//判断域名ns是否在我们这边 TODO::DNSapi太慢
				if ($k == "ns") {
					if(preg_match("/{$v}/i", implode(";",array_map('array_shift',$ns_group)), $matches)){
						$res['list'][$key]['is_ns'] = 1; //ns在我们这边
					}else{
						$res['list'][$key]['is_ns'] = 0;//ns不在我们这边
					}
				}
			}
		}
		$res['pagebar'] = tFun::pagebar_js($res['pagebar'],"__page__?","load_domains_list",array($keyword,$orderby));
		$res['orderby'] = $orderby;
		tAjax::json($res);
	}
	//取消订单
	public function cancel_order(){
		global $uid;
		$order_no              = R("order_no","string");
		if (empty($order_no)) {
			tAjax::json_error("请选择要取消的订单");
		}
		$order_row = M("register_order")->get_row("order_no='{$order_no}' AND uid={$uid}");

		if (!isset($order_row['order_no'])) {
			tAjax::json_error("订单不存在");
		}
		if($order_row['status']>2){
			tAjax::json_error("订单已支付,不能取消");
		}
		$rst = M("register_order")->set_data(array("status"=>0))->update("order_no ='{$order_no}' and uid={$uid}");
		if ($rst) {
			//如果优惠券存在，优惠券状态改为未使用
			$coupon_row = M("coupon")->get_row("uid = '{$uid}' AND order_no = '{$order_no}'");
			if (isset($coupon_row['id'])) {
				M("coupon")->set_data(array("status"=>0,"use_dateline"=>0,"order_no"=>0))->update("order_no ='{$order_no}' AND uid={$uid}");
			}
			tAjax::json_success("取消成功");
		} else {
			tAjax::json_error("取消失败");
		}
	}
	//基本信息
	public function basic(){
		global $uid;
		$domain = R("domain","string");

		//域名行
		$domain_row = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($domain_row['id'])) {
			I("域名不存在！",U("domain@/ucenter/index"));
		}else{
			$list_info_ns = isset($domain_row['ns'])?explode(";",$domain_row['ns']):array();
			$domain_row['dns1'] = $list_info_ns[0];
			$domain_row['dns2'] = isset($list_info_ns[1])?$list_info_ns[1]:'';
		}

		//域名注册信息
		$list_info = M("register_domain_attachinfo")->get_row("did = '{$domain_row['id']}'");
		if (!isset($list_info)) {
			I("域名信息非法！",U("domain@/ucenter/index"));
		}
		
		//判断是否已经实名审核
		$is_domain_sh = 1;
		if (M("register_domain_attach")->get_one("uid = '{$uid}' AND domain_id = '{$domain_row['id']}'","id")) {
			$is_domain_sh = 0;
		}

		$domain_register_type = tCache::read("data_config");
		if (isset($domain_register_type['domain_register_type'])) {
			$suffix = substr($domain,strpos($domain,"."));
			if (!in_array($suffix,$domain_register_type['domain_register_type'])) {
				$is_domain_sh = 0;
			}
		}

		$this->assign("list_info",$list_info);
		$this->assign("is_domain_sh",$is_domain_sh);
		$this->assign("domain_row",$domain_row);
		$this->display();
	}
	//获取域名状态
	public function get_domain_status(){
		global $uid;

		$domain = R("domain","string");
		$domain_row = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($domain_row['id'])) {
			tAjax::json_error("域名不存在");
		}

		/**
		 * 域名状态$domain_status
		 * 0,没有查到结果
		 * 1,域名正常
		 * 2,注册商设置禁止转移，禁止更新
		 * 3,注册商设置禁止转移
		 * 4,注册商设置禁止更新
		 * 5,注册局设置禁止解析
		 * 6,注册局设置禁止解析,注册商设置禁止转移
		 * 7,注册局设置禁止解析,注册商设置禁止更新
		 * 8,注册局设置禁止解析,注册商设置禁止转移，禁止更新
		 */
		$domain_status = 0;
		$zc_set = array();//为空禁止转移，禁止更新,1禁止转移,2禁止更新,3注册局设置禁止解析
		$domain_whois =  DNSapi::whois($domain);
		//判断如果本地whois查询不到，读取gitwhois:6s
		if ($domain_whois == -1) {
			require_once(ROOT_PATH.'lib/tools/whois/Whois.php');
			$fw = new Whois();
			$domain_whois = $fw->info($domain);
		}
		if($domain_whois != -1){
			if(preg_match('/No match for domain"/i', $domain_whois)){
				$domain_status = 1;
			}
			if(preg_match("/Domain Status:([0-9a-zA-Z\-: \/.,#-]+)/is", $domain_whois,$matched)){
				if(isset($matched[1]) && trim($matched[1])){
					if (preg_match("/ok/is", substr(trim($matched[1]),0,80))) {
						$domain_status = 1;
					}
				}
			}
			if(preg_match("/clientTransferProhibited/is", $domain_whois)){
				array_push($zc_set,1);
			}
			if(preg_match("/clientUpdateProhibited/is", $domain_whois)){
				array_push($zc_set,2);
			}
			if(preg_match("/serverHold/is", $domain_whois)){
				array_push($zc_set,3);
			}
		}
		$ac_arr = count($zc_set);
		if ($ac_arr <= 0) {
			$domain_status = 1;
		}else{
			if ($ac_arr == 1) {
				if (in_array(1,$zc_set)) {
					$domain_status = 3;
				}elseif(in_array(2,$zc_set)){
					$domain_status = 4;
				}else{
					$domain_status = 5;
				}
			}else{
				if (in_array(1,$zc_set) && in_array(2,$zc_set)) {
					$domain_status = 2;
				}elseif(in_array(1,$zc_set) && in_array(3,$zc_set)){
					$domain_status = 6;
				}elseif(in_array(2,$zc_set) && in_array(3,$zc_set)){
					$domain_status = 7;
				}else{
					$domain_status = 8;
				}
			}
		}
		tAjax::json_success($domain_status);
	}
	//域名信息修改页面
	public function basic_edit(){
		global $uid;
		$domain = R("domain","string");
		$type = R("type","string");
		$type = empty($type)?1:$type;

		//域名行
		$domain_row = M("@register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($domain_row['id'])) {
			I("域名不存在！",U("domain@/ucenter/index"));
		}
		//域名注册信息
		$list_info = M("register_domain_attachinfo")->get_row("did = '{$domain_row['id']}'");
		if (!isset($list_info)) {
			I("域名信息非法！",U("domain@/ucenter/index"));
		}else{
			$list_info_ns = isset($list_info['area'])?explode(",",$list_info['area']):array();
			$list_info['r_province_cn'] = $list_info_ns[0];
			$list_info['r_city_cn'] = $list_info_ns[1];

			$list_info_ns1 = isset($list_info['m_area'])?explode(",",$list_info['m_area']):array();
			$list_info['a_province_cn'] = $list_info_ns1[0];
			$list_info['a_city_cn'] = $list_info_ns1[1];
		}

		$this->assign("type",$type);
		$this->assign("domain",$domain);
		$this->assign("list_info",$list_info);
		$this->display();
	}
	//域名信息修改
	public function basic_edit_sub(){
		global $uid;

		App::uselib("tools.Zh2ZyCode");

		$type = R("type","int");
		$domain = R("domain","string");

		$aller_name_cn      = R("aller_name_cn","string");
		$aller_name           = R("aller_name","string");
		$email                   = R("email","string");

		$s_province            = R("s_province","stirng");
		$s_city                    = R("s_city","stirng");

		$addr_cn                = R("addr_cn","string");
		$addr                     = R("addr","string");
		$ub                        = R("ub","int");
		$mobile                  = R("mobile","string");
		//验证输入内容不能为空
		if (empty($email) || empty($s_province) || empty($s_city) || empty($addr_cn) || empty($addr) || empty($ub) || empty($mobile)) {
			tAjax::json_error("输入内容不为空！");
		}
		//验证邮箱格式是否正确
		if (!tValidate::is_email($email)) {
			tAjax::json_error("邮箱格式不正确！");
		}
		//验证手机格式是否正确
		if (!tValidate::is_phone($mobile)) {
			tAjax::json_error("手机格式不正确！");
		}

		//域名行
		$domain_row = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($domain_row['id'])) {
			tAjax::json_error("域名不存在！");
		}
		//域名注册信息
		$list_info = M("register_domain_attachinfo")->get_row("did = '{$domain_row['id']}'");
		if (!isset($list_info)) {
			tAjax::json_error("域名信息非法！");
		}

		$r_fax_arr = explode(",",$list_info['cz']);

		$data_d = array(

			"uname1"        =>$list_info['aller_name_cn'], //注册人中文单位名称
			"uname2"        =>$list_info['aller_name'], //注册人英文单位名称
			"rname1"        =>$list_info['name_cn'], //注册人|单位负责人中文名称
			"rname2"        =>$list_info['name'], //注册人|单位负责人英文名称'

			"ust"                => "CN", //注册人英文国家名称两个字母'
			"uprov"           => str_replace(' ', '',($s_province == "重庆市")?"chong qing shi":Zh2PyCode::encode($s_province,"all")), //注册人英文省份名称'
			"ucity1"           => $s_city,//注册人中文城市名称
			"ucity2"           => str_replace(' ', '',Zh2PyCode::encode($s_city,"all")), //注册人英文城市名称'

			"uaddr1"         => $addr_cn, //注册人中文地址'
			"uaddr2"         => $addr, //注册人英文地址'

			"uzip"              => $ub,//注册人邮政编码
			"uteln"            => ($type == 1)?$mobile:$list_info['mobile'],//注册人电话号码'
			"uemail"          => $email,//注册人 email 地址
			"ufaxc"            => $r_fax_arr[0],//注册人传真国家码，可以不填默认为 86，长度不能大于 3 位
			"ufaxa"            => $r_fax_arr[1],//注册人传真区号/.如果为手机可以不填，此项不可为 0
			"ufaxn"            => $r_fax_arr[2],//注册人传真号码/.必须为数字 (区号+传真号码长度必须小于 12

			"aname1"        => ($type == 2)?$aller_name_cn:$list_info['m_name_cn'],//管理联系人中文名称 [国内域名必填]
			"aname2"        => ($type == 2)?$aller_name:$list_info['m_name'],//管理联系人英文名称 [国际域名必填] '
			"ateln"            =>  ($type == 2)?$mobile:$list_info['m_mobile'],//管理联系人电话号码 [区号+电话号码长度必须小于 12]

			"aemail"          => $email,//管理联系人电子邮件地址 [必须]

		);
		$res  = SDKdomain::modify("ModifyContactor",$domain,$data_d);
		if ($res['status'] == 1) {
			$attachinfo_arr = array(
				"aller_name_cn"      => $list_info['aller_name_cn'],
				"aller_name"            => $list_info['aller_name'],
				"name_cn"               => $list_info['name_cn'],
				"name"                    => $list_info['name'],
				"email"                     => $email,
				"area"                      => $s_province.",".$s_city,
				"addr_cn"                => $addr_cn,
				"addr"                      => $addr,
				"ub"                         => $ub,
				"mobile"                  => ($type == 1)?$mobile:$list_info['mobile'],
				"cz"                          => $list_info['cz'],
				"m_name_cn"           => ($type == 2)?$aller_name_cn:$list_info['m_name_cn'],
				"m_name"                => ($type == 2)?$aller_name:$list_info['m_name'],
				"m_email"                => $email,
				"m_area"                  => $s_province.",".$s_city,
				"m_addr_cn"            => $addr_cn,
				"m_addr"                 => $addr,
				"m_ub"                     => $ub,
				"m_mobile"             => ($type == 2)?$mobile:$list_info['m_mobile'],
			);
			M("register_domain_attachinfo")->set_data($attachinfo_arr)->update("uid = '{$uid}' AND id = '{$list_info['id']}'");
			//修改域名信息日志
			M("@register_domain")->log("域名信息修改","域名名称：{$domain},联系人：{$data_d['uname1']},邮箱:{$data_d['aemail']}",array('domain_id'=>$domain_row['id'],'domain'=>$domain));
			tAjax::json_success("修改成功！");
		}else{
			tAjax::json_error("修改失败！");
		}
	}
	//域名过户
	public function basic_transfer(){
		global $uid;
		$domain = R("domain","string");

		//域名行
		$domain_row = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($domain_row['id'])) {
			I("域名不存在！",U("domain@/ucenter/index"));
		}

		$this->assign("domain_row",$domain_row);
		$this->display();
	}
	//域名实名制认证
	public function basic_rz(){
		global $uid;
		$do = R("do","string");
		$domain = R("domain","string");
		$ch = R("ch","string");

		$result = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($result['id'])) {
			I("域名不存在！",U("domain@/ucenter/index"));
		}
		$attach_data = M("register_domain_attach")->get_row("uid = '{$uid}' AND domain_id = '{$result['id']}'");
		
		if (!empty($do)) {//提交实名制资料认证
			$cart = R("cart_id","string");

			if (!isset($attach_data['id'])) {
				tAjax::json_error("身份证件不存在！");
			}
			$file_reg =  tFun::base64EncodeImage(ROOT_PATH."www/static/".$attach_data['imgurl']);
			$data = array(
				'file_reg' => $file_reg,//域名所有人身份证明 jpg 图片的 base64 编码
				'organ_code'=>$cart,//所有人证件号码
			);
			$ret = SDKdomain::up_domain_rz("uploadCnDomain",$domain,array(),$data);
			if ($ret['status'] == 1) {
				//添加证件号码
				M("register_domain_attach")->set_data(array("cart"=>$cart))->update("uid = '{$uid}' AND domain_id = '{$result['id']}'");
				//添加域名实名认证日志
				M("@register_domain")->log("域名实名认证","域名名称：{$domain},证件号码:{$data['organ_code']}",array('domain_id'=>$result['id'],'domain'=>$domain));
				tAjax::json_success("ok");
			}elseif ($ret['status'] == 2) {
				tAjax::json_error($ret['list']['info']);
			}else{
				tAjax::json_error("请求失败！");
			}
		}else{
			
			//域名注册信息
			$list_info = M("register_domain_attachinfo")->get_row("did = '{$result['id']}'");
			if (!isset($list_info)) {
				I("域名信息非法！",U("domain@/ucenter/index"));
			}
			
			//imgurl
			if (!isset($attach_data['imgurl'])) {
				$imgurl = "";
			}else{
				$imgurl = $attach_data['imgurl'];
			}

			$type = 1;//1上传认证资料 2,等待审核 3，认证成功 4，审核失败 5,系统配置外域名
			$domain_register_type = tCache::read("data_config");
			if (isset($domain_register_type['domain_register_type'])) {
				$suffix = substr($domain,strpos($domain,"."));
				if (in_array($suffix,$domain_register_type['domain_register_type'])) {
					$res = SDKdomain::domain_status("queryDomainVerifyStatus",$domain);
					if ($res['status'] == 1) {
						//审核状态
						if (in_array($res['list']['info'],array("01","04"))) {
							$type =2;//2,等待审核
						}elseif (in_array($res['list']['info'],array("02","06"))) {
							$type =3;//认证成功
						}elseif (in_array($res['list']['info'],array("03","05","07"))) {
							$type =4;//审核失败
						}
					}
				}else{
					$type = 5;//5,系统配置外域名,无需认证
				}
			}
			if (!empty($ch)) { //审核失败，重新上传资料进行实名认证
				$type = 1;
			}

			$this->assign("domain",$domain);
			$this->assign("result",$result);
			$this->assign("list_info",$list_info);
			$this->assign("type",$type);
			$this->assign("imgurl",$imgurl);
			$this->display();
		}
	}
	//删除已上传身份证
	public function del_upload_sfz(){
		global $uid;

		$domain = R("domain","string");
		$res_row = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($res_row['id'])) {
			tAjax::json_error("域名不存在");
		}
		$attach_data = M("register_domain_attach")->get_row("uid = '{$uid}' AND domain_id = '{$res_row['id']}'");
		if (!isset($attach_data['id'])) {
			tAjax::json_error("上传信息不存在");
		}
		$res = M("register_domain_attach")->del("uid = '{$uid}' AND domain_id = '{$res_row['id']}'");
		if ($res) {
			tAjax::json_success("已删除");
		}else{
			tAjax::json_error("删除失败");
		}
	}
	//域名证书
	public function basic_zs(){
		global $uid;
		$domain = R("domain","string");

		$res = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($res['id'])) {
			I("域名不存在！",U("domain@/ucenter/index"));
		}
		$domain_info = M("register_domain_attachinfo")->get_row("did = '{$res['id']}'");
		if (!isset($domain_info['id'])) {
			I("域名信息不存在！",U("domain@/ucenter/index"));
		}

		$this->assign("type",$res['type']);
		$this->assign("res",$res);
		$this->assign("domain_info",$domain_info);
		$this->display();
	}
	//DNS修改
	public function basic_dns(){
		global $uid;
		$do = R("do","string");
		$domain = R("domain","string");
		//域名行
		$domain_row = M("@register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!empty($do)) {//修改dns
			if (!isset($domain_row['id'])) {
				tAjax::json_error("域名不存在！");
			}
			$dns = R("dns","string");
			$dns_arr = explode(";",str_replace(' ', '',$dns));
			$dns_arr = array_unique($dns_arr);
			if (count($dns_arr) <2) {
				tAjax::json_error("最少填写两个不同的域名dns！");
			}
			$data = array(
				"dns1" =>$dns_arr[0],
				"dns2" =>$dns_arr[1],
			);
			$res = SDKdomain::modify_dns("ModDns",$domain,$data);
			if ($res['status'] == 1) {
				M("register_domain")->set_data(array('ns'=>str_replace(' ', '',$dns)))->update("uid = '{$uid}' AND domain = '{$domain}'");
				//域名DNS修改日志
				M("@register_domain")->log("域名DNS修改","域名名称：{$domain},修改DNS:{$dns}",array('domain_id'=>$domain_row['id'],'domain'=>$domain));
				tAjax::json_success("修改成功！");
			}else{
				tAjax::json_error("修改失败，请检查域名状态！");
			}

		}else{//页面显示
			if (!isset($domain_row['id'])) {
				I("域名不存在！",U("domain@/ucenter/index"));
			}else{
				$list_info_ns = isset($domain_row['ns'])?explode(";",$domain_row['ns']):array();
				$domain_row['dns1'] = $list_info_ns[0];
				$domain_row['dns2'] = isset($list_info_ns[1])?$list_info_ns[1]:'';
			}

			$this->assign("domain",$domain);
			$this->assign("dns",$domain_row);
			$this->display();
		}
	}
	//域名模板实名制认证资料上传
	public  function upload_cart(){
		global $uid;
		$save_dir  		= ROOT_PATH."www/static/";
		if(tUtil::check_hash()) {
			$path = "cart";
			$post_name = 'attach_file';
			$id = R("id","int");
			if (!empty($id)) {
				$domain_row = M("domain_register_info")->get_row("uid = '{$uid}' AND id = '{$id}'");
				if (!isset($domain_row['id'])) {
					tAjax::json_error("模板不存在！");
				}
			}
			if (empty($_FILES[$post_name]) === false) {
				$file_avatar_path = tTime::get_datetime("Y/m/d");
				$file_store_path = $save_dir . "attach/{$path}/" . $file_avatar_path . "/";
				$file_path = "attach/{$path}/" . $file_avatar_path . "/";
				$return_file = "";
				$up_obj = new tUpload(1024, array("jpg"));
				$up_obj->set_dir($file_store_path);
				$upstate = $up_obj->execute();

				//获取用户信息
				$is_array = null;
				if (!isset($upstate[$post_name])) {
					tAjax::json_error("上传失败！");
				}
				$upfile_result = $upstate[$post_name][0];
				if ($upfile_result['flag'] == 1) {
					foreach($upstate as $key => $rs){
						foreach($rs as $inner_key => $val){
							//上传成功后图片信息
							$file_name   = $val['dir'].$val['name'].".".$val['ext'];
							$file_md5    = md5_file($file_name);
							$rs[$inner_key]['path']  = $file_name;
							//插入数据
							$insert_data = array(
								'filemd5'    => $file_md5,//判断文件唯一标准
								'ext'        => $val['ext'],
								'thumb'      => 0,//如果为 0 则不是图片附件
								"imgurl"		=>$file_path.$val['name'].".{$val['ext']}",
								'path'       => $file_avatar_path,
								'filename'   => $val['name'],
								'size'       => $val['size'],
								'width'      => $val['width'],
								'height'     => $val['height'],
								'dateline'   => tTime::get_time(),
								'description'=> $val['ininame'],
								"uid"        =>$uid,
								"type"		=>0,
								"info_id" =>$id,
							);
							$tpl_row = M("register_domain_tplattach")->get_row("uid = '{$uid}' AND info_id = '{$id}'");
							if (isset($tpl_row['id'])) {
								M("register_domain_tplattach")->set_data($insert_data)->update("uid = '{$uid}' AND info_id = '{$id}'");
								$tpl_id = $tpl_row['id'];
							}else{
								$tpl_id = M("register_domain_tplattach")->set_data($insert_data)->add();
							}
							$return_file = U("static@").$file_path.$val['name'].".{$val['ext']}";
							tAjax::json(array("error"=>0,"path"=>$return_file,"tpl_id"=>$tpl_id));
						}
					}
				} elseif ($upfile_result['flag'] == -1) {
					tAjax::json_error("上传必须为jpg图片");
				} elseif ($upfile_result['flag'] == -2) {
					tAjax::json_error("上传图片文件太大");
				}
			}
		}
	}
	//身份证上传
	public function upload_sfz(){
		global $uid;
		$save_dir  		= ROOT_PATH."www/static/";
		if(tUtil::check_hash()) {
			$path = "cart";
			$post_name = 'attach_file';
			$domain = R("domain","string");
			$domain_row = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
			if (!isset($domain_row['id'])) {
				tAjax::json_error("上传域名不存在！");
			}
			if (empty($_FILES[$post_name]) === false) {
				$file_avatar_path = tTime::get_datetime("Y/m/d");
				$file_store_path = $save_dir . "attach/{$path}/" . $file_avatar_path . "/";
				$file_path = "attach/{$path}/" . $file_avatar_path . "/";
				$return_file = "";
				$up_obj = new tUpload(1024, array("jpg"));
				$up_obj->set_dir($file_store_path);
				$upstate = $up_obj->execute();

				//获取用户信息
				$is_array = null;
				if (!isset($upstate[$post_name])) {
					tAjax::json_error("上传失败！");
				}
				$upfile_result = $upstate[$post_name][0];
				if ($upfile_result['flag'] == 1) {
					foreach($upstate as $key => $rs){
						foreach($rs as $inner_key => $val){
							//上传成功后图片信息
							$file_name   = $val['dir'].$val['name'].".".$val['ext'];
							$file_md5    = md5_file($file_name);
							$rs[$inner_key]['path']  = $file_name;
							//插入数据
							$insert_data = array(
								'filemd5'    => $file_md5,//判断文件唯一标准
								'ext'        => $val['ext'],
								'thumb'      => 0,//如果为 0 则不是图片附件
								"imgurl"		=>$file_path.$val['name'].".{$val['ext']}",
								'path'       => $file_avatar_path,
								'filename'   => $val['name'],
								'size'       => $val['size'],
								'width'      => $val['width'],
								'height'     => $val['height'],
								'dateline'   => tTime::get_time(),
								'description'=> $val['ininame'],
								"uid"        =>$uid,
								"type"		=>0,
								"domain_id" =>$domain_row['id'],
							);
							if (M("register_domain_attach")->get_one("uid = '{$uid}' AND domain_id = '{$domain_row['id']}'","count(id)")) {
								M("register_domain_attach")->set_data($insert_data)->update("uid = '{$uid}' AND domain_id = '{$domain_row['id']}'");
							}else{
								M("register_domain_attach")->set_data($insert_data)->add();
							}
							$return_file = U("static@").$file_path.$val['name'].".{$val['ext']}";
							tAjax::json(array("error"=>0,"path"=>$return_file));
						}
					}
				} elseif ($upfile_result['flag'] == -1) {
					tAjax::json_error("上传必须为jpg图片");
				} elseif ($upfile_result['flag'] == -2) {
					tAjax::json_error("上传图片文件太大");
				}
			}
		}
	}
	//域名操作日志
	public function domain_log(){
		$domain = R("domain","string");
		if ($domain) {
			$c = array(
				"where"		=> "domain='{$domain}'",
				"page"  		=> R("page","int")?:1,
				"pagesize"   => 12,
				"order"		=> "id DESC"
			);
			$res = Q("register_domain_log")->get_list($c,"__page__?");
			$res['pagebar'] = tFun::pagebar_js($res['pagebar'],"__page__?","edit_log_func",array($domain));
			tAjax::json($res);
		}
	}
	//信息模板管理列表
	public function template(){
		global $uid;
		$do = R("do","string");
		$page     		= R("page","int");

		if ($do == 'get') {
			$where   = "uid = '{$uid}'";
			$data  = array(
				'where'     =>$where,
				'page'		 =>$page,
				'order'      => "id DESC",
				'pagesize' => 16,
			);
			$res = M("@domain_register_info")->get_list($data,"__page__?");
			foreach ($res['list'] as $key=>$val) {
				//查看域名实名审核状态
				$up_res = SDKdomain::query_template("templateInfo",$val['tpl_name']);
				if ($up_res['status'] == 1 && isset($up_res['list']['status'])) {
					if ($up_res['list']['status'] == "04") {
						$set_status = 3;
					}elseif ($up_res['list']['status'] == "05") {
						$set_status = 4;
					}elseif ($up_res['list']['status'] == "06") {
						$set_status = 2;
					}elseif ($up_res['list']['status'] == "07") {
						$set_status = 1;
					}else{
						$set_status = 0;
					}
					if ($val['is_use'] != 2) {
						M("domain_register_info")->set_data(array("is_use"=>$set_status))->update("uid = '{$uid}' AND id = '{$val['id']}'");
						$res['list'][$key]['is_use'] = $set_status;
					}
				}
				//查看关联域名
				$lian_res = SDKdomain::get_tpl_dn_name("templateDnList",$val['tpl_name']);
				if ($lian_res['status'] == 1 && isset($lian_res['list']['info'])) {
					$res['list'][$key]['dnlists'] = $lian_res['list']['info'];
				}else{
					$res['list'][$key]['dnlists'] = 0;
				}
			}
			$res['pagebar'] = tFun::pagebar_js($res['pagebar'],"__page__?","load_tpl_list");
			tAjax::json($res);
		}
		$this->display();
	}
	//信息模板编辑,添加
	public function template_info(){

		global $uid;
		App::uselib("tools.Zh2ZyCode");
		$do = R("do","string");
		$info_row = array();
		if (tUtil::check_hash()) {
			$id = R("id", "int");//模板id
			$utype = R("utype", "int");
			$tpl_name= R("tpl_name", "string");
			$cart = R("cart", "string");
			$tpl_id = R("tpl_id", "int");
			$aller_name_cn = R("aller_name_cn", "string");
			$aller_name = R("aller_name", "string");
			$name_cn = R("name_cn", "string");
			$name = R("name", "string");
			$email = R("email", "string");

			$s_province = R("s_province", "stirng");
			$s_city = R("s_city", "stirng");

			$addr_cn = R("addr_cn", "string");
			$addr = R("addr", "string");
			$ub = R("ub", "int");
			$mobile = R("mobile", "string");
			$cz1 = R("cz1", "int");
			$cz2 = R("cz2", "int");
			$cz3 = R("cz3", "int");
			//验证输入内容不能为空
			if (empty($cart) || empty($utype) || empty($tpl_name) || empty($aller_name_cn) || empty($aller_name) || empty($name_cn) || empty($name) || empty($email) || empty($s_province) || empty($s_city) || empty($addr_cn) || empty($addr) || empty($ub) || empty($mobile) || empty($cz1) || empty($cz2) || empty($cz3)) {
				tAjax::json_error("输入内容不为空！");
			}
			//验证用户类型是否合法
			if (!in_array($utype, array(1, 2))) {
				tAjax::json_error("用户类型不正确！");
			}
			//验证邮箱格式是否正确
			if (!tValidate::is_email($email)) {
				tAjax::json_error("邮箱格式不正确！");
			}
			//验证手机格式是否正确
			if (!tValidate::is_phone($mobile)) {
				tAjax::json_error("手机格式不正确！");
			}
			//判断模板名称是否已经存在
			if ($id) {
				$row = M("domain_register_info")->get_row("tpl_name='{$tpl_name}' AND id<>'{$id}'");
				if(!empty($row)){
					tAjax::json_error("模板名称已经存在");
				}
			}else{
				if (M("domain_register_info")->get_one("tpl_name = '{$tpl_name}'","count(*)")) {
					tAjax::json_error("模板名称已经存在");
				}
			}
			//判断证件号存在时是否合法
			if ($cart && (strlen($cart) < 8 || strlen($cart) > 18)) {
				tAjax::json_error("证件号格式不正确！");
			}

			//本地模板信息
			$data = array(
				"uid" => $uid,
				"utype" => $utype,
				"tpl_name" => $tpl_name,
				"cart" => $cart,
				"aller_name_cn" => $aller_name_cn,
				"aller_name" => $aller_name,
				"name_cn" => $name_cn,
				"name" => $name,
				"email" => $email,
				"area" => $s_province . "," . $s_city,
				"addr_cn" => $addr_cn,
				"addr" => $addr,
				"ub" => $ub,
				"mobile" => $mobile,
				"cz" => "0" . $cz1 . "," . $cz2 . "," . $cz3,
				"template" => 1, //当前使用模板

				"m_name_cn" => $name_cn, //管理人
				"m_name" => $name,//管理人英文
				"m_email" => $email,//同上
				"m_area" => $s_province . "," . $s_city,
				"m_addr_cn" => $addr_cn,
				"m_addr" => $addr,
				"m_ub" => $ub,
				"m_mobile" => $mobile,
			);

			//远程模板信息
			$attach_data = M("register_domain_tplattach")->get_row("uid = '{$uid}' AND id = '{$tpl_id}'");
			if (isset($attach_data['imgurl'])) {
				$file_reg =  tFun::base64EncodeImage(ROOT_PATH."www/static/".$attach_data['imgurl']);
			}else{
				$file_reg = "";
			}
			$tpl_data = array(
				"utype"=>($utype == 1)?"P":"C",//用户类型P：个人，C：企业
				"uname1"=>$aller_name_cn,//注册所有人中文
				"uname2"=>$aller_name,//注册所有人英文
				"rname1"=>$name_cn,//注册联系人中文
				"rname2"=>$name,//注册联系人英文（姓）
				"rname3"=>$name,//注册联系人英文（名）
				"ust"=>"CN",//注册人英文国家名称
				"uprov"=>str_replace(' ', '',($s_province == "重庆市")?"chong qing shi":Zh2PyCode::encode($s_province,"all")), //注册人英文省份名称
				"ucity2"=>str_replace(' ', '',Zh2PyCode::encode($s_city,"all")."shi"), //注册人英文城市名称
				"uaddr1"=>$addr_cn,//注册人中文地址
				"uaddr2"=>$addr,//注册人英文地址
				"uzip"=>$ub,//注册人邮政编码
				"utela"=>$cz2,//注册人电话区号
				"uteln"=>$cz3,//注册人电话号码
				"ufaxa"=>$cz2,//注册人传真号码
				"ufaxn"=>$cz3,//注册人传真号码
				"uemail"=>$email,//注册人email地址
			);
			$extdata = array(
				"organ_code"=>$cart,//证件号码
				"file_reg"=>$file_reg,//证件号码扫描件
			);
			if ($id) {

				//判断证件号和证件必须同步
				$tplattachs = M("register_domain_tplattach")->get_one("uid = '{$uid}' AND info_id = '{$id}'","count(*)");
				 if (($cart && !$tplattachs) || ($tplattachs && !$cart)) {
					 tAjax::json_error("请同时提交证件号和扫描件");
				 }

				//******************************上传实名制资料 start************************************************************
				if ($cart && $tplattachs && $tpl_id) {
					$rf_res = SDKdomain::rf_upload("rfUpload",$tpl_name,array("uname1"=>$aller_name_cn),$extdata);
					if ($rf_res['status'] == 2) {
						if (isset($rf_res['list']['err'])) {
							if ($rf_res['list']['err'] == "tname-exist") {
								$tt_info = "模板名称已存在";
							}elseif ($rf_res['list']['err'] == "upload-failure") {
								$tt_info = "上传实名资料失败";
							}elseif ($rf_res['list']['err'] == "tinfo-repeat") {
								$tt_info = "注册人信息重复，创建或修改失败";
							}elseif ($rf_res['list']['err'] == "file_reg-null") {
								$tt_info = "域名所有人与证件扫描件信息不一致";
							}elseif ($rf_res['list']['err'] == "tname-noexist") {
								$tt_info = "模板不存在，请重新创建模板";
							}else{
								$tt_info = $rf_res['list']['err'];
							}
						}else{
							$tt_info = "提交失败";
						}
						tAjax::json_error($tt_info);
					}
					if ($rf_res['status'] == 0) {
						tAjax::json_error("请求失败！");
					}
				}
				//*****************************上传实名制资料 end***************************************************************

				//******************************远程创建模板信息 update start************************************************************
				$up_res = SDKdomain::mod_template("modTemplate",$tpl_name,$tpl_data,$extdata);
				if ($up_res['status'] == 2) {
					if (isset($up_res['list']['err'])) {
						if ($up_res['list']['err'] == "tname-noexist") {
							//******************************远程创建模板信息 add start（如果模板不存在则添加次模板）************************************************************
							$up_res_a = SDKdomain::create_tpl_info("createTemplate",$tpl_name,$tpl_data,$extdata);
							if ($up_res_a['status'] == 2) {
								if (isset($up_res_a['list']['err'])) {
									if ($up_res_a['list']['err'] == "tname-exist") {
										$tt_info = "模板名称已存在";
									}elseif ($up_res_a['list']['err'] == "upload-failure") {
										$tt_info = "上传实名资料失败";
									}elseif ($up_res_a['list']['err'] == "tinfo-repeat") {
										$tt_info = "注册人信息重复，创建或修改失败";
									}elseif ($up_res_a['list']['err'] == "file_reg-null") {
										$tt_info = "域名所有人与证件扫描件信息不一致";
									}elseif ($up_res_a['list']['err'] == "tname-noexist") {
										$tt_info = "模板不存在，请重新创建模板";
									}else{
										$tt_info = $up_res_a['list']['err'];
									}
								}else{
									$tt_info = "提交失败";
								}
								tAjax::json_error($tt_info);
							}elseif($up_res_a['status'] == 0){
								tAjax::json_error("请求失败！");
							}else{
								//更改本地信息模板
								M("domain_register_info")->set_data($data)->update("uid = '{$uid}' AND id = '{$id}'");
								tAjax::json_success("保存成功");
							}
							//*****************************远程创建模板信息 add end***************************************************************
						}else{
							$tt_info = $up_res['list']['err'];
						}
					}else{
						$tt_info = "提交失败";
					}
					tAjax::json_error($tt_info);
				}
				if ($up_res['status'] == 0) {
					tAjax::json_error("请求失败！");
				}
				//*****************************远程创建模板信息 update end***************************************************************

				//更改本地信息模板
				M("domain_register_info")->set_data($data)->update("uid = '{$uid}' AND id = '{$id}'");
				tAjax::json_success("保存成功");
			}else{//添加模板

				//判断添加模板信息不能超过20个
				if (M("domain_register_info")->get_one("uid = '{$uid}'","count(*)") > 20) {
					tAjax::json_error("每个用户最多只能创建20个域名注册信息模板");
				}
				//判断证件号和证件必须同步
				if (($cart && !$tpl_id) || (!$cart && $tpl_id)) {
					tAjax::json_error("请同时提交证件号和扫描件");
				}

				//******************************远程创建模板信息 add start************************************************************
				$up_res = SDKdomain::create_tpl_info("createTemplate",$tpl_name,$tpl_data,$extdata);
				if ($up_res['status'] == 2) {
					if (isset($up_res['list']['err'])) {
						if ($up_res['list']['err'] == "tname-exist") {
							$tt_info = "模板名称已存在";
						}elseif ($up_res['list']['err'] == "upload-failure") {
							$tt_info = "上传实名资料失败";
						}elseif ($up_res['list']['err'] == "tinfo-repeat") {
							$tt_info = "注册人信息重复，创建或修改失败";
						}elseif ($up_res['list']['err'] == "file_reg-null") {
							$tt_info = "域名所有人与证件扫描件信息不一致";
						}elseif ($up_res['list']['err'] == "tname-noexist") {
							$tt_info = "模板不存在，请重新创建模板";
						}else{
							$tt_info = $up_res['list']['err'];
						}
					}else{
						$tt_info = "提交失败";
					}
					tAjax::json_error($tt_info);
				}
				if ($up_res['status'] == 0) {
					tAjax::json_error("请求失败！");
				}
				//*****************************远程创建模板信息 add end***************************************************************

				$res1 = M("domain_register_info")->set_data($data)->add();
				if ($res1) {
					//添加成功后，更改附件表info_id
					if ($tpl_id) {
						M("register_domain_tplattach")->set_data(array("info_id"=>$res1))->update("id = '{$tpl_id}'");
					}

					tAjax::json_success("提交成功");
				}else{
					tAjax::json_error("提交失败");
				}
			}

		}else{
			$imgurl = "";
			if ($do == 'edit') {
				$id = R("id","int");
				$info_row = M("domain_register_info")->get_row("uid = '{$uid}' AND id = '{$id}'");
				if (!isset($info_row['id'])) {
					I("模板信息不存在！",U("domain@/ucenter/index"));
				}
				$info_row['area'] = explode(",",$info_row['area']);
				$info_row['cz'] = explode(",",$info_row['cz']);

				$attach_data = M("register_domain_tplattach")->get_row("uid = '{$uid}' AND info_id = '{$info_row['id']}'");
				if (!isset($attach_data['imgurl'])) {
					$imgurl = "";
				}else{
					$imgurl = $attach_data['imgurl'];
				}
			}
		}
		$this->assign("info_row",$info_row);
		$this->assign("imgurl",$imgurl);
		$this->assign("do",$do);
		$this->display();
	}
	//获取信息模板关联域名
	public function get_tpl_dn(){
		global $uid;
		$name = R("tpl_name","string");
		if (empty($name)) {
			tAjax::json_error("模板名称不存在！");
		}
		$res = SDKdomain::get_tpl_dn_name("templateDnList",$name);
		if ($res['status'] == 1) {
			$data = array();
			foreach ($res['list'] as $key=>$val) {
				if (preg_match("/dn/",$key,$result)) {
					$row = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$val}'");
					if (isset($row['id'])) {
						$data[] = array(
							"domain"=>$val,
							"exp_time"=>isset($row['exp_time'])?date("Y-m-d",$row['exp_time']):0,
							"reg_time"=>isset($row['reg_time'])?date("Y-m-d",$row['reg_time']):0,
						);
					}
				}
			}
			tAjax::json_success($data);
		}else{
			tAjax::json_error("请求失败");
		}
	}
	//删除模板信息
	public function template_del(){
		global $uid;
		$id = R("id","int");
		$info_row = M("domain_register_info")->get_row("id = '{$id}'");
		if (!isset($info_row['id'])) {
			tAjax::json_error("模板信息不存在！");
		}

		//判断是否已有使用中未完成的购物车
		$r = M("domain_register_cart")->get_row("tpl = '{$id}' AND status = 0 AND uid = '{$uid}'");
		if (isset($r['item_id'])) {
			tAjax::json_error("您有未完成购物车使用此模板，请先完成再删除模板！");
		}

		//判断是否已有使用中未完成的订单
		$r = M("register_order_item")->get_row("tpl = '{$id}' AND status = 0 AND uid = '{$uid}'");
		if (isset($r['item_id'])) {
			$r1 = M("register_order")->get_row("order_no = '{$r['order_no']}'");
			if (in_array($r1['status'],array(1,2))) {
				tAjax::json_error("您有未完成订单使用此模板，请先完成订单再删除模板！");
			}
		}

		$res_local = M("domain_register_info")->del("id = '{$id}'");
		if ($res_local) {
			//删除附件表
			M("register_domain_tplattach")->del("info_id = '{$info_row['id']}'");
			//删除远程新网模板
			$res = SDKdomain::del_template("delTemplate",$info_row['tpl_name'],array("email"=>$info_row['email']));
			tAjax::json_success("删除成功!");
		}else{
			tAjax::json_error("删除失败！");
		}
	}
	//域名过户（模板）
	public function registrant_change(){
		global $uid,$timestamp;
		$domain = R("domain","string");
		$name = R("name","string");

		//判断模板是否存在
		if (empty($name)) {
			tAjax::json_error("请选择模板！");
		}

		//判断域名是否存在
		$domain_row = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($domain_row['id'])) {
			tAjax::json_error("域名不存在！");
		}

		//判断域名原关联模板是否存在
		if (empty($domain_row['domain_tpl'])) {
			$old_tname = "NoTemplate";
		}else{
			$old_tname = $domain_row['domain_tpl'];
		}

		//域名过户
		$data = array(
			"old_tname" => $old_tname,
			"new_tname" => $name,
		);
		$res = SDKdomain::registrant_change("registrantChange",$domain,$data);
		if ($res['status'] == 1) {
			if (isset($res['list']['ret']) && $res['list']['ret'] == "100") {

				$tpl_row = M("domain_register_info")->get_row("uid = '{$uid}' AND tpl_name = '{$name}'");
				if (isset($tpl_row['id'])) {
					$local_data = array(
						"aller_name_cn" => $tpl_row['aller_name_cn'],
						"aller_name" => $tpl_row['aller_name'],
						"name_cn" => $tpl_row['name_cn'],
						"name" => $tpl_row['name'],
						"email" => $tpl_row['email'],
						"area" => $tpl_row['area'],
						"addr_cn" => $tpl_row['addr_cn'],
						"addr" => $tpl_row['addr'],
						"ub" => $tpl_row['ub'],
						"mobile" => $tpl_row['mobile'],
						"cz" => $tpl_row['cz'],
					);

					//更改本地域名联系人信息
					M("register_domain_attachinfo")->set_data($local_data)->update("did = '{$domain_row['id']}' AND uid = '{$uid}'");

					//更改域名关联模板
					M("register_domain")->set_data(array("domain_tpl"=>$name,"reg_type"=>$tpl_row['utype']))->update("uid = '{$uid}' AND domain = '{$domain}'");
				}
				tAjax::json_success("过户成功!");
			}
		}elseif ($res['status'] == 2) {
			$tips_info = "系统错误";
			if (isset($res['list']['err'])) {
				if ($res['list']['err'] == "rstatus-error") {
					$tips_info = "国内域名模板实名制还未通过，禁止过户";
				}elseif ($res['list']['err'] == "not_related") {
					$tips_info = "域名与原模板为关联";
				}elseif ($res['list']['err'] == "pending-delete") {
					$tips_info = "域名在删除期，不允许过户";
				}elseif ($res['list']['err'] == "pending-resore") {
					$tips_info = "域名在偿还期，不允许过户";
				}elseif ($res['list']['err'] == "pending-renew") {
					$tips_info = "域名在续费期，不允许过户";
				}elseif ($res['list']['err'] == "old_tname-noexist") {
					$tips_info = "原模板名称不存在";
				}elseif ($res['list']['err'] == "system-error") {
					$tips_info = "系统错误";
				}else{
					$tips_info = $res['list']['err'];
				}
			}
			tAjax::json_error($tips_info);
		}else{
			tAjax::json_error("过户失败");
		}
	}
	//设置为默然模板
	public function template_set() {
		global $uid;
		$id = R("id","int");
		$info_row = M("domain_register_info")->get_row("id = '{$id}'");
		if (!isset($info_row['id'])) {
			tAjax::json_error("模板信息不存在！");
		}
		$res = M("domain_register_info")->set_data(array("is_tpl"=>1))->update("uid = '{$uid}' AND id = '{$id}'");
		if ($res) {
			M("domain_register_info")->set_data(array("is_tpl"=>0))->update("uid = '{$uid}' AND id <> '{$id}'");
			tAjax::json_success("设置成功!");
		}else{
			tAjax::json_error("设置失败！");
		}
	}
	//域名转入
	public function transfer(){
		global $uid;
		$do = R("do","string");
		$page     		= R("page","int");

		if ($do == 'get') {
			$where   = "uid = '{$uid}'";
			$data  = array(
				'where'     =>$where,
				'page'		 =>$page,
				'order'      => "id DESC",
				'pagesize' => 16,
			);
			$res = M("@register_domain_transfer")->get_list($data,"__page__?");
			$res['pagebar'] = tFun::pagebar_js($res['pagebar'],"__page__?","load_tpl_list");
			tAjax::json($res);
		}
		$this->display();
	}
	//域名转入
	public function transfer_submit(){
		global $uid,$timestamp;
		if (tUtil::check_hash()) {
			$domain = R("domain","string");
			$code = R("code","string");

			//判断转入域名，域名转移码是否为空
			if (empty($domain) || empty($code)) {
				tAjax::json_error("提交内容不能为空，请输入");
			}

			//判断域名是否合法
			if (!tValidate::is_domain($domain)) {
				tAjax::json_error("非法域名");
			}

			//判断是否已加入域名转入数据表..转移失败可以继续提交转移申请
			if (M("register_domain_transfer")->get_one("uid = '{$uid}' AND domain = '{$domain}' AND status <> 3 AND status <> 5","count(id)")) {
				tAjax::json_error("域名{$domain}转入申请已提交。请查看转入列表");
			}

			//判断域名是否已注册
			$domain_str = substr($domain,0,strpos($domain,"."));
			$suffix_str = substr($domain,strpos($domain,"."));
			$check_res = SDKdomain::check("check",$domain_str,array($suffix_str));
			if ($check_res['status'] == 1) {
				if (isset($check_res['list'][1]['chk'])) {
					if ($check_res['list'][1]['chk'] == 100) {
						tAjax::json_error("域名尚未注册，不能转入");
					}
				}
			}elseif ($check_res['status'] == 2) {
				tAjax::json_error("新网接口认证失败！");
			}elseif ($check_res['status'] == 0) {
				tAjax::json_error("CURL post请求失败！");
			}

			//判断域名是否已在新网，即域名注册商是否为新网
			$is_exit_innet = SDKdomain::check_info("queryDomainInfo",$domain,"");
			$domain_whois =  DNSapi::whois($domain);//TODO:.xyz查不到
			if ($is_exit_innet['status'] == 1) {
				tAjax::json_error("您的域名已在新网，无需转入！");
			}elseif ($is_exit_innet['status'] == 2){
				if($domain_whois){
					if(preg_match("/XIN NET/is", $domain_whois,$matched)){
						tAjax::json_error("您的域名已在新网，无需转入！");
					}
					if(preg_match("/XINNET/is", $domain_whois,$matched)){
						tAjax::json_error("您的域名已在新网，无需转入！");
					}
					if(preg_match("/新网/is", $domain_whois,$matched)){
						tAjax::json_error("您的域名已在新网，无需转入！");
					}
					//判断域名是否处于注册商禁止转移，更新等保护状态
					if(preg_match("/Domain Status:([0-9a-zA-Z\-: \/.,#-]+)/is", $domain_whois,$matched)){
						if(isset($matched[1]) && trim($matched[1])){
							if (!preg_match("/ok/is", $matched[1],$matched)) {
								tAjax::json_error("您的域名处于保护状态,不可转入.");
							}
						}
					}
				}
			}elseif ($is_exit_innet['status'] == 0) {
				tAjax::json_error("CURL post请求失败！");
			}

			//域名,注册时间。到期时间 whois查询有三种到期时间展示
			$exptime = $creattime ="";
			if($domain_whois){
				if(preg_match("/Registrar Registration Expiration Date:([0-9a-zA-Z\-: ]+)/is", $domain_whois,$matched)){
					if(isset($matched[1]) && trim($matched[1])){
						$exptime = substr(trim($matched[1]),0,10);
					}
				}
				if(preg_match("/Creation Date:([0-9a-zA-Z\-: ]+)/is", $domain_whois,$matched)){
					if(isset($matched[1]) && trim($matched[1])){
						$creattime = substr(trim($matched[1]),0,10);
					}
				}
				if(preg_match("/Registry Expiry Date:([0-9a-zA-Z\-: ]+)/is", $domain_whois,$matched)){
					if(isset($matched[1]) && trim($matched[1])){
						$exptime = substr(trim($matched[1]),0,10);
					}
				}
				if(preg_match("/Expiration Time:([0-9a-zA-Z\-: ]+)/is", $domain_whois,$matched)){
					if(isset($matched[1]) && trim($matched[1])){
						$exptime = substr(trim($matched[1]),0,10);
					}
				}
				if(preg_match("/Registration Time:([0-9a-zA-Z\-: ]+)/is", $domain_whois,$matched)){
					if(isset($matched[1]) && trim($matched[1])){
						$creattime = substr(trim($matched[1]),0,10);
					}
				}
			}

			if (empty($exptime)) {
				tAjax::json_error("无法查询域名到期时间,提交失败.");
			}

			//如果已经存在转移失败的转入域名，则先删除转移失败域名
			M("register_domain_transfer")->del("uid = '{$uid}' AND domain = '{$domain}' AND status = 3");

			//加入域名转入数据表
			$data = array(
				"uid" 			=> $uid,
				"domain" 	=> $domain,//转入域名
				"code"			=> $code,//转移码
				"dateline" 	=> $timestamp,//提交时间
				"creattime"	=> $creattime,
				"exptime"	=> $exptime,
				"status"		=> 0//转入状态0，待支付
			);
			$res = M("register_domain_transfer")->set_data($data)->add();
			if ($res) {
				tAjax::json_success($domain);
			}else{
				tAjax::json_error("提交失败");
			}
		}else{
			$this->display();
		}
	}
	//域名转入订单支付
	public function transfer_pay(){
		global $uid;
		$domain = R("domain","string");

		$this->userinfo = C("user")->get_cache_userinfo($uid);
		if(!isset($this->userinfo['uid']) || empty($this->userinfo['uid'])){
			$this->redirect(U("account@/login?refer=").U("domain@/ucenter/transfer"));
		}
		
		//判断域名是否合法
		$domain_row = M("register_domain_transfer")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($domain_row['id'])) {
			I("非法操作！",U("domain@/ucenter/transfer"));
		}

		//域名费用
		$domain_price = 0;
		$suffix_p = M("@domain_register_price")->get_cache_by_agent(1);
		foreach ($suffix_p as $key=>$val) {
			if ( substr($domain,strpos($domain,".")+1) == $val['name']) {
				$domain_price = (int)$val['renew_price'];
			}
		}

		$this->assign("domain",$domain);
		$this->assign("domain_price",$domain_price);
		$this->display();
	}
	//域名转入提交处理
	public function transfer_progress(){
		global $uid;
		$domain = R("domain","string");

		$this->userinfo = C("user")->get_cache_userinfo($uid);
		if(!isset($this->userinfo['uid']) || empty($this->userinfo['uid'])){
			$this->redirect(U("account@/login?refer=").U("domain@/ucenter/transfer"));
		}

		//判断域名是否合法
		$domain_row = M("register_domain_transfer")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($domain_row['domain'])) {
			I("非法操作！",U("domain@/ucenter/transfer"));
		}

		if (in_array($domain_row['status'],array(0,2,4,5))) {
			I("非法操作！",U("domain@/ucenter/transfer"));
		}

		if (tUtil::check_hash()) {
			//提交域名转入申请
			$code = R("code","string");

			//判断是否是域名转入失败后重新提交
			if ($code) {
				$map = array("password"=>$code);
				M("register_domain_transfer")->set_data(array("code"=>$code))->update("uid = '{$uid}' AND domain = '{$domain_row['domain']}'");
			}else{
				$map = array("password"=>$domain_row['code']);
			}

			$res = SDKdomain::domain_transfer("DomainTransferIn",$domain_row['domain'],$map);
			if ($res['status'] == 1 && $res['list']['ret'] == 100) {
				M("register_domain_transfer")->set_data(array("status"=>2))->update("uid = '{$uid}' AND domain = '{$domain_row['domain']}'");
				tAjax::json_success("ok");
			}elseif ($res['status'] == 2) {//转入失败
				//*******************************************************************************************************************
				$info = isset($res['list']['err'])?$res['list']['err']:'';
				tAjax::json_error("提交失败:{$info}");
			}elseif ($res['status'] == 0) {
				tAjax::json_error("系统错误");
			}else{
				tAjax::json_error("其它错误");
			}
		}
		$this->assign("domain_row",$domain_row);
		$this->display();
	}
	//域名转入重新发送
	public function transfer_succ(){
		global $uid;
		$domain = R("domain","string");
		//判断域名是否合法
		$domain_row = M("register_domain_transfer")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		if (!isset($domain_row['domain'])) {
			I("非法操作！",U("domain@/ucenter/transfer"));
		}
		if (in_array($domain_row['status'],array(0,1,3,4,5))) {
			I("非法操作！",U("domain@/ucenter/transfer"));
		}
		if (tUtil::check_hash()) {
			$domain = R("domain","string");
			$res = SDKdomain::resend_transfer_email("resendTransferInEmail",$domain);
			if ($res['status'] == 1 && $res['list']['ret'] == 100) {
				if (preg_match("/resend-success/is", $res['list']['info'],$matched)) {//转移中
					tAjax::json_success("邮件重发成功！");
				}else{
					tAjax::json_error("邮件重发失败！失败原因:{$res['list']['info']}");
				}
			}else{
				tAjax::json_error("邮件重发失败");
			}
		}else{
			$this->assign("domain",$domain);
			$this->display();
		}
	}
	//域名用户转移
	public function domain_transfer(){
		global $uid,$timestamp;

		$domain_ids  	= R("domain_id","string");
		$email 		    	= R("email","string");
		$psw					= R("password","string");
		//验证输入内容是否为空
		if (empty($email)) {
			tAjax::json_error("邮箱不能为空！");
		}
		//验证邮箱格式是否正确
		if (!tValidate::is_email($email)) {
			tAjax::json_error("邮箱格式不正确！");
		}
		//验证转移对象是否存在
		$user = M("user")->get_row("email = '{$email}'");
		if (!isset($user['uid'])) {
			tAjax::json_error("域名转移对象不存在！");
		}
		//验证域名转移对象不能为自己
		if ($email == $this->userinfo['email']) {
			tAjax::json_error("域名转移对象不能为自己！");
		}
		if (empty($psw)) {
			tAjax::json_error("账户登录密码不能为空！");
		}
		//验证账户登录密码是否正确
		if ($this->userinfo['password'] != md5($psw)) {
			tAjax::json_error("登录密码错误！");
		}

		$domain_arr = explode(",",$domain_ids);
		$userId = M("user")->get_one("email = '{$email}'","uid");
		if ($userId && count($domain_arr) > 0) {
			foreach($domain_arr as $domain_id){
				//更新用户uid
				$register_row = M("register_domain")->get_row("id = '{$domain_id}'");
				if (isset($register_row['domain'])) {
					$res = M("register_domain")->set_data(array("uid"=>$userId))->update("domain = '{$register_row['domain']}'");
					if ($res) {
						M("register_domain_attachinfo")->set_data(array("uid"=>$userId))->update("did = '{$domain_id}'");
						M("@account")->update($register_row['uid'],array("domains"=>"-1"),array("domains"=>"转出域名一个"));
						M("@account")->update($userId,array("domains"=>"+1"),array("domains"=>"转入域名一个"));
					}
				}
			}
		}
		tAjax::json_success("转移用户成功");
	}
	//域名列表导出
	public function register_domain_export(){
		global $uid;
		M("@register_domain")->register_domain_excel("a.uid = '{$uid}'");
	}
	//域名状态设置
	public function basic_status(){
		global $uid;

		$domain = R("domain","string");
		$res_row = M("register_domain")->get_row("uid = '{$uid}' AND domain = '{$domain}'");
		$time = time() - 600;
		if (tUtil::check_hash()){
			if (!isset($res_row['id'])) {
				tAjax::json_error("域名不存在!");
			}

			$addstatus = R("addstatus","string");
			$delstatus = R("delstatus","string");
			$data = array();
			if (!empty($addstatus)) {
				$data['addStatus'] = $addstatus;
			}
			if (!empty($delstatus)) {
				$data['delStatus'] = $delstatus;
			}

			//判断修改之后十分钟内不能重复修改操作
			$logTips = "设置需十分钟左右生效时间，请勿重复操作";
			if ($addstatus == "clientTransferProhibited" || $delstatus == "clientTransferProhibited") {
				if (M("register_domain_log")->get_one("domain = '{$domain}' AND uid = '{$uid}' AND modi_log LIKE '%clientTransferProhibited%' AND dateline > '{$time}'","count('id')")) {
					tAjax::json_error($logTips);
				}
			}else{
				if (M("register_domain_log")->get_one("domain = '{$domain}' AND uid = '{$uid}' AND modi_log LIKE '%clientUpdateProhibited%' AND dateline > '{$time}'","count('id')")) {
					tAjax::json_error($logTips);
				}
			}

			$res = SDKdomain::set_domain_status("setDomainStatus",$domain,$data);
			if ($res['status'] == 1 && $res['list']['ret'] == 100) {
				//添加域名状态设置修改日志
				$cz = empty($addstatus)?"delStatus":"addStatus";
				$cz_r = empty($addstatus)?$delstatus:$addstatus;
				M("@register_domain")->log("域名状态设置","域名名称：{$domain},设置状态:({$cz}:{$cz_r})",array('domain_id'=>$res_row['id'],'domain'=>$domain));
				tAjax::json_success("域名状态设置成功！");
			}else{
				$err = "域名状态设置失败!";
				if (isset($res['list']['err'])) {
					$err = $res['list']['err'];
					$err = $err == "."?"禁止更新状态下不能设置转移项!":$err;
				}
				tAjax::json_error($err);
			}
		}else{
			if (!isset($res_row['id'])) {
				I("域名不存在！",U("domain@/ucenter/index"));
			}

			//域名状态
			$domain_status = 0; //0没有查到结果1域名正常2注册商设置禁止转移，禁止更新3,注册商设置禁止转移4,注册商设置禁止更新
			$zc_set = array();//为空禁止转移，禁止更新1禁止转移2禁止更新
			$domain_whois = DNSapi::whois($domain);
			//判断如果本地whois查询不到，读取gitwhois:6s
			if ($domain_whois == -1) {
				require_once(ROOT_PATH.'lib/tools/whois/Whois.php');
				$fw = new Whois();
				$domain_whois = $fw->info($domain);
			}
			if($domain_whois != -1){
				if(preg_match('/No match for domain"/i', $domain_whois)){
					$domain_status = 1;
				}
				if(preg_match("/Domain Status:([0-9a-zA-Z\-: \/.,#-]+)/is", $domain_whois,$matched)){
					if(isset($matched[1]) && trim($matched[1])){
						if (preg_match("/ok/is", substr(trim($matched[1]),0,80))) {
							$domain_status = 1;
						}
					}
				}
				if(preg_match("/clientTransferProhibited/is", $domain_whois)){
					array_push($zc_set,1);
				}
				if(preg_match("/clientUpdateProhibited/is", $domain_whois)){
					array_push($zc_set,2);
				}

				$ac_arr = count($zc_set);
				if ($ac_arr <= 0) {
					$domain_status = 1;
				}else{
					if ($ac_arr == 1) {
						if (in_array(1,$zc_set)) {
							$domain_status = 3;
						}else{
							$domain_status = 4;
						}
					}else{
						$domain_status = 2;
					}
				}
			}else{
				I("域名状态查询失败！",U("domain@/ucenter/index"));
			}

			//判断修改之后十分钟内不能重复修改操作
			$logTips1 = $logTips2 = 0;
			if (M("register_domain_log")->get_one("domain = '{$domain}' AND uid = '{$uid}' AND modi_log LIKE '%clientUpdateProhibited%' AND dateline > '{$time}'","count('id')")) {
				$logTips1 = 1;
			}
			if (M("register_domain_log")->get_one("domain = '{$domain}' AND uid = '{$uid}' AND modi_log LIKE '%clientTransferProhibited%' AND dateline > '{$time}'","count('id')")) {
				$logTips2 = 1;
			}
			$this->assign("log_tips1",$logTips1);
			$this->assign("log_tips2",$logTips2);
			$this->assign("domain_row",$res_row);
			$this->assign("domain_status",$domain_status);
			$this->display();
		}
	}
}
?>