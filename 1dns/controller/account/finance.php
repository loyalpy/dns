<?php
class finance extends UC{
	public function __construct(){
		parent::__construct('finance');
	}
	//账户信息
	public function index(){
		global $uid,$timestamp;
		//域名监控数
		$domainJNum = M("domain_monitor")->get_one("uid={$uid}","count(*)");
		$this->assign("domainJNum",$domainJNum?:0);
		$this->display();
	}
	//充值
	public function recharge(){
		global $uid,$timestamp;
		tSafe::set('timestamp',0);
		//动作
		$do = R("do","string","index");
		$order_no    = R('order_no',"string");   //订单ID
		$order_type  = R("order_type","string"); //订单类型
		if($order_no){
			$order_tables = array("register_order","order");
			if(!in_array($order_type,$order_tables)){
				$order_type = "";
				$order_no   = "";
			}
		}else{
			$order_type = "";
		}

		$type  = R("type","int");
		$type  = $type?$type:1;                  //1充值2充值列表

		$inpay       = R("inpay","int");        //余额充值后是否直接支付
		$inpay       = !in_array($inpay,array(0,1))?0:$inpay;

		//初始化支付插件类
		$balance = R("balance","float");
		$payment = new cls_payment();
		//获取已配置支付列表
		$list = $payment->get_payment();
		$this->assign("payment_list",$list);
		$this->assign("balance",$balance);
		$this->assign("inpay",$inpay);
		$this->assign("order_no",$order_no);
		$this->assign("order_type",$order_type);
		$this->assign("do",$do);
		$this->assign("type",$type);
		$this->display();
	}
	//充值提交
	public function recharge_submit(){
		global $uid,$timestamp;
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
		header("Cache-Control: no-cache, must-revalidate" ); 
		header("Pragma:no-cache");
		//参数
		$do = R("do","string");
		//获取公共数据
		$recharge_id = R("recharge_id","int");   //充值ID
		$payment_id  = R('pay_id',"int");        //支付方式ID
		$order_no    = R('order_no',"string");   //订单ID
		$order_type  = R("order_type","string"); //订单类型
		$inpay       = R("inpay","int");        //余额充值后是否直接支付
		$inpay       = !in_array($inpay,array(0,1))?0:$inpay;
		//获取充值数据
		$recharge    = sprintf("%.2f",R('recharge',"float"));

		if($order_no){
			$order_tables = array("register_order","order");
			if(!in_array($order_type,$order_tables)){
				$order_type = "";
				$order_no   = "";
			}
		}else{
			$order_type = "";
		}
		//获取支付方式
		$payment_obj = new cls_payment();
		$payment_row = $payment_obj->get_payment_byid($payment_id);
		if(empty($payment_row)){
			$this->_msg("支付方式不存在!","支付方式不存在","/ucenter/index#返回");
		}
		//获得payment_id 获得相关参数
		//载入支付接口文件
		$pay_obj  = $payment_obj->load($payment_row['file_path']);
		//充值数据检查
		if($do == "submit"){
			//防刷新的
			$old_timestamp = tSafe::get('timestamp');
			if($old_timestamp>0 && ($timestamp-$old_timestamp) <10){
				$this->_msg("不要重复提交数据,该页禁止刷新!","不要重复提交数据,该页禁止刷新","/finance/recharge#返回");
			}else{
				tSafe::set('timestamp',$timestamp);
			}
			if($recharge <=0)$this->_msg("充值金额错误!","充值金额错误","/finance/recharge#返回");
		}
		$reData   = array(
		  'id'                => $recharge_id,
          'amount'            => $recharge,
          'r_amount'		  => $recharge,
          'payment_name'      => $payment_row['name'] ,
          'payment_type'      => $payment_row['type'] ,
          'payment_id'        => $payment_row['id'],
		  'trade_no'          => '',
		  'trade_bank'		  => '',
		  'trade_date'		  => 0,
          'order_no'          => $order_no,
          'order_table'       => $order_type,
		  'kaipiao'			  => 0,          
		  'kaipiao_tou'		  => "",
          'inpay'			  => $inpay,
		);
		$res = $payment_obj->get_payment_info($payment_id,$reData);
		if($do == "submit"){
			$result = $reData;
			$result['id']          = $res['M_OrderId'];
			$result['recharge_no'] = $res['M_OrderNO'];
			$result['amount']      = $res['M_Amount'];
			$result['payment_id']  = $res['M_Paymentid'];
			$result['error']       = 0;
			$this->redirect(U("/finance/recharge_submit?recharge_id={$result['id']}&pay_id={$result['payment_id'] }"));
		}else{
			$toSubmit = $pay_obj->toSubmit($res);
			//提交头部
			if(isset($pay_obj->head_charset)){header("Content-Type: text/html;charset=" . $pay_obj->head_charset);}
			//生成数据
			$html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en-US\" lang=\"en-US\" dir=\"ltr\">\n                <head>\n</header><body><div>Redirecting...</div>";
			//兼容站外站内的支付方式
			if(strtolower(substr($pay_obj->submitUrl,0,4)) != 'http'){
				$pay_obj->submitUrl = U($pay_obj->submitUrl);
			}
			$html .= "<form id=\"payment\" action=\"" . $pay_obj->submitUrl . "\" method=\"" . $pay_obj->method . "\">";
			$buffer = "";
			foreach ($toSubmit as $k => $v){
				if ($k != "ikey"){
					$html .= "<input  type=\"hidden\" name=\"" . urldecode($k) . "\" value=\"" . htmlspecialchars ( $v ) . "\" />";
					$buffer .= $k . "=" . urlencode($v) . "&";
				}
			}
			$html .= "\n</form>\n<script language=\"javascript\">\ndocument.getElementById('payment').submit();\n</script>\n</html>";
			die($html);
		}			
	}
	//充值记录查询
	public function recharge_list(){
		global $uid,$timestamp;
		$do 			 = R("do","string");
		$page 		 = R("page","int")?:1;
		$pageurl 	 = U("/finance/recharge_list?do=get");
		$condition = R("condition","string");

		//查询搜索
		$where      = "1 AND uid='{$uid}'";
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
					$where .= $v?" AND (recharge_no LIKE '%{$v}%') OR (amount LIKE '%{$v}%') OR (payment_name LIKE '%{$v}%')":"";
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}
		if($condition == 1){
			$where .= " AND status='{$condition}'";
		}elseif($condition == 2){
			$where .= " AND status=0";
		}

		if($do == "get"){
			$data['page']  = $page;
			$data['where'] = $where;
			$data['pagesize'] = 16;
			$data['order'] = "dateline DESC";
			$result = M("@recharge")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"load_recharge_list");
			tAjax::json(array("error"=>0,"msg"=>"加载成功","data"=>$result));
		}
		$this->display();
	}
	//提现
	public function withdraw(){
		global $uid,$timestamp;
		if(tUtil::check_hash()){
			    $old_timestamp = tSafe::get('timestamp');
				$old_timestamp = intval($old_timestamp);
				if($old_timestamp>0 && ($timestamp-$old_timestamp) <180){
					tAjax::json_error("不要重复提交数据,该页禁止刷新");
				}else{
					tSafe::set('timestamp',$timestamp);
				}
		   		$balance = R("balance","string");	   		
		   		$balance = sprintf("%.2f",$balance);
		   		if($balance <= 0){
		   			tAjax::json_error("提现金额错误");
		   		}
		   		//获取正在提现审核的金额
		   		$withdraw_ing = M("withdraw")->get_one("uid=$uid AND status=0","amount");
		   		//可用余额
		   		$withdraw_pass = sprintf("%.2f",($this->userinfo['account']['balance']-$withdraw_ing));
		   		if($balance > $withdraw_pass){
		   			tAjax::json_error("提现金额不能大于可用余额");
			   	}
			   	//pay_id
			   	$pay_id = R("pay_id","int");
			   	$pay_row = M("user_pay")->get_row("uid=$uid AND id=$pay_id");
			   	if(!isset($pay_row['id'])){
			   		tAjax::json_error("提现账户不存在");
			   	}
			   	if($pay_row['status'] != 2){
			   		tAjax::json_error("提现账户尚未认证,不能提现");
			   	}			   
		   		$data = array(
		   		    "withdraw_no"     => tUtil::create_no("TX"),
		   			"uid"             => $uid,
		   			"amount"          => $balance,
		   			"shui"            => App::get_conf("site.shui"),
		   			"dateline"        => $timestamp,
		   			"pay_id"          => $pay_id,
		   			"pay_status"      => 0,
		   			"pay_dateline"    => 0,
		   			"status"          => 0
		   		);
		   		M("withdraw")->set_data($data);
		   		if(M("withdraw")->add()){
		   			tAjax::json_success("提现申请成功！");
		   		}else{
		   			tAjax::json_error("提现失败");
		   		}
		}else{
			tSafe::set('timestamp',0);
			$this->assign("balance","0.00");
			$mypaylist = M("user_pay")->query("uid=$uid AND status IN(0,2)");
			$withdraw_ing  = M("withdraw")->get_one("uid=$uid AND status=0","amount");
			$this->assign("withdraw_ing",$withdraw_ing);
			$this->assign("mypaylist",$mypaylist);			
			$this->display();
		}
	}
	//提现记录查询
	public function withdraw_log(){
		 global $user_id,$timestamp;
		 $this->__qx(1);
		 $page = R("page");
		 $size = R("size","int");
		 $size = $size?$size:15;
		 $startdate = R("startdate","string");
		 $enddate = R("enddate","string");
		 $startdate = empty($startdate)?tTime::get_datetime("Y-m-d",($timestamp-30*86400)):$startdate;
		 $trade_no = R("trade_no","string");
		 $bank_trade_no = R("bank_trade_no","string");
	
		 $balance = R("balance","string");
		 $balance = sprintf("%.2f",$balance);
		 
		 $pageurl = U("/ucenter/withdraw_log?startdate=$startdate&enddate=$enddate&trade_no=$trade_no&balance=$balance&bank_trade_no=$bank_trade_no");
		 
		 
		 $where = "members_id=$user_id AND indel=0";
		 $where .= ($startdate)?(" AND dateline>".tTime::get_time($startdate)):"";
		 $where .= ($enddate)?(" AND dateline<".tTime::get_time($enddate)):"";
		 $where .= ($bank_trade_no)?(" AND bank_trade_no='$bank_trade_no'"):"";
		 $where .= ($trade_no)?(" AND trade_no='$trade_no'"):"";
		 $where .= ($balance>0)?(" AND balance='$balance'"):"";
		
		 $query = new tQuery('withdraw');
		 $query->where = $where;
		 $query->pagesize = $size;
		 $query->page = $page;
		 $query->fields = "*";
		 $query->order = "dateline DESC";
		 $datalist = $query->find();
		 $pagebar = $query->get_pagebar($pageurl);
		 
		 $in_ajax = R("inajax","int");
		 if($in_ajax == 1){
		 	if($datalist){
		 		$status_t = array(
				    "-1"  => "失败",
					"0" => "审核中",
					"1" => "成功",
				);
				foreach($datalist as $k=>$v){
					$datalist[$k]['date'] = tTime::get_datetime("Y-m-d",$v["dateline"]);
					$datalist[$k]['bank_trade_no'] = $v['bank_trade_no']?$v['bank_trade_no']:"-";
					$datalist[$k]['status_text'] = isset($status_t[$v['status']])?$status_t[$v['status']]:"-";
				}
		 	}
		 	tAjax::json($datalist);
		 }else{
		 	$this->assign("balance",$balance);
		 	$this->assign("startdate",$startdate);
		 	$this->assign("enddate",$enddate);
		 	$this->assign("trade_no",$trade_no);
		 	$this->assign("bank_trade_no",$bank_trade_no);
		 	$this->assign("datalist",$datalist);
		 	$this->assign("pagebar",$pagebar);
		 	$this->display();
		 }
	}
	//提现撤销
	public function withdraw_concel(){
		global $user_id,$user_name;
		$withdraw_id = R("id","int");
		$row = M("withdraw")->get_row("id='{$withdraw_id}' AND members_id=$user_id");
		if(!isset($row['id']) && $row['status'] != 0){
			tAjax::json_error("不能撤销该提现!");
		}
		M("withdraw")->set_data(array("status"=>-1));
		if(M("withdraw")->update("id=$withdraw_id")){
			X('tLog')->write('operation',array("{$user_name}({$user_id})","撤销提现","撤销提现成功！提现单号：{$row['trade_no']}"));
			tAjax::json(array("error"=>0,"message"=>"撤销提现成功！","callback"=>"reload"));
		}
	}
	//提现记录查询
	public function withdraw_list(){
		global $uid,$timestamp;
		$company_id = $this->company_id;
		$do         = R("do","string","list");
		if(in_array($do,array('list','get','url'))){
			$page       = R("page","int");
			$pageurl    = U("/finance/withdraw_list?do=get");
			$orderby    = R("orderby","string","a.dateline!DESC");
			$where      = "a.uid='{$uid}'";
			$condi = array(
					"keyword"   => R("keyword","string"),
					"startdate"	=> R("startdate","string"),
					"enddate"   => R("enddate","string"),
			);
			$condi['startdate'] = empty($condi['startdate'])?tTime::get_datetime("Y-m-d",($timestamp-30*86400)):$condi['startdate'];
			foreach($condi as $k=>$v){
				$v = trim($v);
				$pageurl .= $v?("&{$k}=".$v):"";
				switch($k){
					case "startdate":
						$where .= $v?" AND (a.dateline >= ".tTime::get_time($v).")":"";
						break;
					case "enddate":
						$where .= $v?" AND (a.dateline <= ".tTime::get_time($v).")":"";
						break;
					case "keyword":
						$where .= $v?" AND (a.withdraw_no LIKE '%{$v}%')":"";
						break;
					default:
						$where .= $v?" AND a.{$k}='{$v}'":"";
						break;
				}
			}
			$this->assign("pageurl",$pageurl);
			$this->assign("orderby",$orderby);
		}
		switch($do){
			case "del"://作废
				$id = R("id","int");
				$row = M("withdraw")->get_row("id=$id AND uid=$uid");
				if(!isset($row['id'])){
					tAjax::json_error("数据未找到！");
				}
				if($row['status'] != 0){
					tAjax::json_error("该提现已经处理！");
				}
				M("withdraw")->set_data(array("status"=>1));
				$ret = M("withdraw")->update("id=$id");
				if($ret){
					tAjax::json_success("操作成功！");
				}else{
					tAjax::json_error("操作失败！");
				}
				break;
			case "url":
				tAjax::json(array("error"=>0,"message"=>"","url"=>$pageurl));
				break;
			case "get":
				$c = array();
				$c['page']  = $page;
				$c['pagesize']  = 30;
				$c['where']     = $where;
				$c['join']      = " LEFT JOIN user_pay AS b ON a.pay_id=b.id";
				$c['fields']    = "a.*,b.pay_name,b.pay_no,b.pay_user,b.pay_bank,b.status AS pay_no_st";
				//获取排序
				$c['order'] = str_replace("!"," ",$orderby);
				$result = Q("withdraw AS a")->get_list($c,$pageurl);
				$result['orderby'] = $orderby;
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"$.list.load_page");
				tAjax::json($result);
				break;
			default:
				$this->display();
				break;
		}
	}
	//收支明细
	public function recharge_detail(){
		global $uid,$timestamp;
		$type = R("type","int");
		$type = $type?$type:1;//1资金明细2短信明细3积分明细
		$this->assign("type",$type);
		$this->display();
	}
	//推广中心
	public function tg(){
		global $uid,$timestamp;
		$type = R("type","int");
		$type = $type?$type:1;
		//认证状态0:未申请1，审合中，2审合通过，3审合成功
		$rz_status = 0;
		$rz_row = M("tg_user")->get_row("user_id = {$uid}");
		if (isset($rz_row['id'])) {
			$rz_status = $rz_row['status'];
		}
		//推广码
		$tg_id = tUtil::numstr($uid,false,8);

		$this->assign("type",$type);
		$this->assign("tg_code",$tg_id);
		$this->assign("rz_status",$rz_status);
		$this->display();
	}
	//申请资料
	public function apply_tg(){
		global $uid,$timestamp;
		if(tUtil::check_hash()) {
			$id 			= R("id","int");
			$name 		= R("name","string");
			$mobile 	= R("mobile","string");
			$email 		= R("email","string");
			$qq_ww 	= R("qq_ww","string");
			$address 	= R("address","string");
			$pay_no 	= R("pay_no","string");
			$pay_name 	= R("pay_name","string");
			$pay_bank 	= R("pay_bank","string");
			$pay_type 	= R("pay_type","string");
			$mybz 			= R("mybz","string");
			//判断联系人不能为空
			if (empty($name)) {
				tAjax::json_error("联系人不能为空");
			}
			//判断联系电话不能为空
			if (empty($mobile)) {
				tAjax::json_error("联系电话不能为空");
			}
			//判断结算账号不能为空
			if (empty($pay_no)) {
				tAjax::json_error("结算账号不能为空");
			}
			//判断收款人不能为空
			if (empty($pay_name)) {
				tAjax::json_error("收款人不能为空");
			}
			//判断邮箱是否合法
			if (!empty($email) && !tValidate::is_email($email)) {
				tAjax::json_error("非法邮箱");
			}
			//判断手机是否合法
			if (!empty($mobile) && !tValidate::is_mobile($mobile)) {
				tAjax::json_error("非法手机号");
			}
			$data = array(
				"user_id"					=>$uid,
				"name"					=>$name ,
				"mobile"					=>$mobile ,
				"email"					=>$email ,
				"qq_ww"					=>$qq_ww ,
				"address"					=>$address ,
				"pay_no"					=>$pay_no ,
				"pay_name"				=>$pay_name ,
				"pay_bank"				=>$pay_bank ,
				"pay_type"				=>$pay_type ,
				"mybz"					=>$mybz ,
				"dateline"				=>$timestamp,
				"status"					=>1,
			);
			if ($id > 0) {
				$rz_row = M("tg_user")->get_row("id = {$id}");
				if (isset($rz_row['id'])) {
					if ($rz_row['status'] == 3) {
						$data = array("mybz"=>$mybz,"address"=>$address);
					}
				}
				$res = M("tg_user")->set_data($data)->update("id = {$id}");
				if ($res) {
					tAjax::json_success("修改成功");
				}else{
					tAjax::json_error("修改失败");
				}
			}else{
				$res = M("tg_user")->set_data($data)->add();
				if ($res) {
					tAjax::json_success("申请成功");
				}else{
					tAjax::json_error("申请失败");
				}
			}
		}else{
			$rz_row = M("tg_user")->get_row("user_id = {$uid}");
			if (isset($rz_row['id'])) {
				tAjax::json(array("error"=>0,"data"=>$rz_row,"message"=>"ok"));
			}else{
				tAjax::json(array("error"=>0,"data"=>array("id"=>0,"name"=>'',"mobile"=>'',"qq_ww"=>'',"address"=>'',"pay_no"=>'',"pay_name"=>'',"pay_bank"=>'',"mybz"=>'',"pay_type"=>1),"message"=>"ok"));
			}
		}
	}
	public function get_tg(){
		global $uid;
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/finance/get_tg?do=get");
		if ($do == "get") {
			$where = "fromid = {$uid}";
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
							$uid = M("user")->get_one("uname like '%{$v}%' OR mobile like '%{$v}%' OR email like '%{$v}%'","uid");
						}
						$where .= $v?" AND myid = {$uid}":"";
						break;
					default:
						$where .= $v?" AND {$k}='{$v}'":"";
						break;
				}
			}
			$c = array();
			$c['page']      = $page;
			$c['pagesize']  = 30;
			$c['where'] = $where;
			//获取排序
			$result = M("@tg")->get_list($c,$pageurl);
			foreach($result['list'] as $k => $v){
				$userinfo = C("user")->get_cache_userinfo($v['myid']);
				$result['list'][$k]['name'] = empty($userinfo['uname'])?"-":$userinfo['uname'];
				$result['list'][$k]['email'] =empty($userinfo['email'])?"-":$userinfo['email'];
				$result['list'][$k]['mobile'] =empty($userinfo['mobile'])?"-":$userinfo['mobile'];
//				$result['list'][$k]['dateline'] =empty($userinfo['regdateline'])?0:$userinfo['regdateline'];
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"load_account_list2");
			tAjax::json($result);
		}
	}
	//------------------
	//我的支付账号设置
	public function mypay(){
		global $uid,$timestamp;
		$do         = R("do","string","list");
		if(in_array($do,array('list','get','url'))){
			$page       = R("page","int");
			$pageurl    = U("/finance/mypay?do=get");
			$orderby    = R("orderby","string","dateline!ASC");
			$where      = "1 AND uid='{$uid}'";
			$condi = array(
				"keyword"   => R("keyword","string"),
			);
			foreach($condi as $k=>$v){
				$v = trim($v);
				$pageurl .= "&{$k}=".$v;
				switch($k){
					case "keyword":
						$where .= $v?" AND (pay_name LIKE '%{$v}%' OR pay_no LIKE '%{$v}%' OR pay_user LIKE '%{$v}%')":"";
						break;
					default:
						$where .= $v?" AND {$k}='{$v}'":"";
						break;
				}
			}
			$this->assign("pageurl",$pageurl);
			$this->assign("orderby",$orderby);
		}
		switch($do){
			case "del":
				$id = R("id","int");
				$row = M("user_pay")->get_row("id=$id AND uid=$uid");
				if(!isset($row['id'])){
					tAjax::json_error("数据未找到！");
				}
				if($row['status'] == 2){
					tAjax::json_error("该账号已审核,不能删除");
				}
				$ret = M("user_pay")->del("id=$id");
				if($ret){
					tAjax::json_success("操作成功！");
				}else{
					tAjax::json_error("操作失败！");
				}
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$data = array(
						"pay_name" => R("pay_name","string"),
						"pay_no"   => R("pay_no","string"),
						"pay_user" => R("pay_user","string"),
						"pay_bank" => R("pay_bank","string"),
						"pay_desc" => R("pay_desc","string"),
					);
					if(empty($data['pay_name'])){
						tAjax::json_error("支付名称不能为空！");
					}
					if(empty($data['pay_no'])){
						tAjax::json_error("支付账号不能为空！");
					}
					if(empty($data['pay_user'])){
						tAjax::json_error("支付用户不能为空！");
					}
					if($id == 0){
						if(M("user_pay")->get_one("uid=$uid","count(id)") > 30){
							tAjax::json_error("创建账户太多了!");
						}
						$data['uid']  = $uid;
						$data['dateline']   = $timestamp;
						$ret = M("user_pay")->set_data($data)->add();
					}else{
						M("user_pay")->set_data($data);
						$ret = M("user_pay")->update("id=$id");
					}
					if($ret){
						tAjax::json_success("操作成功！");
					}else{
						tAjax::json_error("操作失败！");
					}
				}else{
					$data = array();
					if($id){
						$data = M("user_pay")->get_row("id=$id AND uid='{$uid}'");
					}
					if(!isset($data['id'])){
						$data = array(
								"id"   => 0,
								"pay_name" => "",
								"pay_no" => "",
						);
					}else{
						if($data['status'] == 2){
							tAjax::json_error("已审核账户不可编辑");
						}
					}
					$data['error'] = 0;
					tAjax::json($data);
				}
				break;
			case "url":
				tAjax::json(array("error"=>0,"message"=>"","url"=>$pageurl));
				break;
			case "get":
				$c = array();
				$c['page']      = $page;
				$c['pagesize']  = 1;
				$c['where'] = $where;
				//获取排序
				$c['order'] = str_replace("!"," ",$orderby);
				$result = Q("user_pay")->get_list($c,$pageurl);
				foreach($result['list'] as $k => $v){
					$result['list'][$k]['no'] = tFun::id2no($v['id'],"PY");
				}
				$result['orderby'] = $orderby;
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"$.list.load_page");
				tAjax::json($result);
				break;
			default:
				$pay_names = array(
					array("key"=>"中国建设银行","v"=>"中国建设银行"),
					array("key"=>"中国工商银行","v"=>"中国工商银行"),
					array("key"=>"中国农业银行","v"=>"中国农业银行"),
					array("key"=>"交通银行","v"=>"交通银行"),
					array("key"=>"招商银行","v"=>"招商银行"),
					array("key"=>"支付宝","v"=>"支付宝"),
					array("key"=>"财付通","v"=>"财付通"),
				);
				$this->assign("pay_names",$pay_names);
				$this->display();
				break;
		}
	}
	//积分操作
	public function point(){
		$do = R("do","string");
    	$do = !in_array($do,array("log","order"))?"order":$do;
    	$this->redirect("/ucenter/point_{$do}",false);
	}
	//积分订单
	public function point_order(){
		global $user_id,$timestamp;
		$this->assign("do","order");
		//删除近期没有处理的订单
	    M("order_point")->del("members_id=$user_id AND pay_status=0 AND status=1 AND dateline<".($timestamp-86400*2));
		//7天前的自动收货
		$ups = array(
			'status'   => 5,
			'completed_dateline' => $timestamp
		);
		M("order_point")->set_data($ups);
		M("order_point")->update("members_id=$user_id AND send_status=1 AND status=2 AND dateline<".($timestamp-86400*7));
	     
	 	//获取参数
		$startdate = R('startdate','string');
		$enddate = R("enddate","string");
		$startdate = empty($startdate)?tTime::get_datetime("Y-m-d",($timestamp-30*86400)):$startdate;
		$page = R('page','int');
		//处理条件
		$where = "members_id=$user_id";
		$where .= ($startdate)?(" AND dateline>=".tTime::get_time($startdate)):"";
		$where .= ($enddate)?(" AND dateline<=".tTime::get_time($enddate)):"";
		//开始查询
		$query = new tQuery("order_point");
		$query->where = $where;
		$query->pagesize = 30;
		$query->page = $page;
		$query->fields = "*";
		$query->order = "dateline DESC";
		
		$this->datalist = $query->find();
		$this->pagebar = $query->get_pagebar(U("/ucenter/point?do=order&startdate=$startdate&enddate=$enddate"));
		$this->total = $query->total;
		$this->totalpage = $query->totalpage;
		$this->assign("startdate",$startdate);
		$this->assign("enddate",$enddate);
   		$this->display();
	}
	//积分日志
    public function point_log(){
		global $uid,$timestamp;
		$do 			 = R("do","string");
		$page 		 = R("page","int")?:1;
		$pageurl 	 = U("/finance/point_log?do=get");

		//查询搜索
		$where      = "1 AND uid='{$uid}' AND ftype='point'";
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
					$where .= $v?" AND ((note LIKE '%{$v}%') OR (amount LIKE '%{$v}%') OR (amount_log LIKE '%{$v}%'))":"";
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
			$data['order'] = "id DESC";
			$result = M("@user_accountlog")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"load_account_list3");
			tAjax::json(array("error"=>0,"msg"=>"加载成功","data"=>$result));
		}
		$this->display();
    }
	//短信日志
	public function smslog(){
		global $uid,$timestamp;
		$do 			 = R("do","string");
		$page 		 = R("page","int")?:1;
		$pageurl 	 = U("/finance/smslog?do=get");

		//查询搜索
		$where      = "1 AND uid='{$uid}' AND ftype='sms'";
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
					$where .= $v?" AND ( (note LIKE '%{$v}%') OR (amount LIKE '%{$v}%') OR (amount_log LIKE '%{$v}%'))":"";
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
			$data['order'] = "dateline DESC";
			$result = M("@user_accountlog")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"load_account_list2");
			tAjax::json(array("error"=>0,"msg"=>"加载成功","data"=>$result));
		}
		$this->display();
	}
	//积分状态改变
	public function point_order_status(){
		global $user_id,$timestamp;
		$id = R("id","int");
		$data = M("order_point")->get_row("id=$id");
		if(!isset($data['id']))tAjax::json_error("该订单已经不存在！");
		if($data['status'] <2)tAjax::json_error("该订单尚未支付,不能发货");
		if($data['status'] == 5)tAjax::json_error("该订单已经完成,操作失败");
		if($data['indel'] == 1)tAjax::json_error("该订单已经作废,操作失败");
		$ups = array(
			'status'   => 5,
			'completed_dateline' => $timestamp
		);
		M("order_point")->set_data($ups);
		M("order_point")->update("id={$data['id']}");
		tAjax::json(array("error"=>0,"message"=>"操作成功!","callback"=>"reload"));
	}
	
	//收支明细
	public function accountlog(){
		global $uid,$timestamp;
		$do 			 = R("do","string");
		$page 		 = R("page","int")?:1;
		$pageurl 	 = U("/finance/accountlog?do=get");

		//查询搜索
		$where      = "1 AND uid='{$uid}' AND ftype='balance'";
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
					$where .= $v?" AND ((note LIKE '%{$v}%') OR (amount LIKE '%{$v}%') OR (amount_log LIKE '%{$v}%'))":"";
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
			$data['order'] = "id DESC";
			$result = M("@user_accountlog")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"load_account_list");
			tAjax::json(array("error"=>0,"msg"=>"加载成功","data"=>$result));
		}
		$this->display();
	}
	//冻结明细
	public function dongjie(){
		global $uid;
		//配置
		$do=R('do','string');
		$pageurl = U("/finance/dongjie?do=get");
		$page = R("page","int");
		$page = $page?$page:1;
		$wherestr = "uid = ".$uid;
		$pagesize = 30;
		$condi = array(
				"startdate"     => R("startdate","string"),
				"enddate"       => R("enddate","string"),
				"keyword"       => R("keyword","string")
		);
		//dump($condi);
		if(!empty($condi)){
			foreach($condi as $k=>$v){
				$pageurl .= "&{$k}=".urlencode($v);
				$this->assign($k,$v);
				switch($k){
					case "startdate":
						$wherestr .= $v?(" AND (dateline >= '".strtotime($v)."')"):"";
						break;
					case "enddate":
						$wherestr .= $v?(" AND (dateline <= '".strtotime($v)."')"):"";
						break;
					case 'keyword':
						$v = ($v == '关键词')?'':$v;
						$wherestr .= $v?" AND (order_no LIKE '%$v%' OR note LIKE '%$v%' OR pay_no LIKE '%$v%')":"";
						break;
					default:
						break;
				}
			}
		}
		switch($do){
			case "url":
				tAjax::json(array("error"=>0,"message"=>"","url"=>$pageurl));
				break;
			case "get":
				//取数据
				$c = array();
				$c['page']     = $page;
				$c['where']    = $wherestr;
				$c['pagesize'] = $pagesize;
				$c['order']    = "dateline DESC";
				$result = Q("tmp_payed")->get_list($c,$pageurl);;
				if($result['list']){

				}
				tAjax::json($result);
				break;
			default:
				$this->assign("pageurl",$pageurl);
				$this->display();
				break;
		}
	}
	/*--------------------------------------积分日志--------------------------------------------------*/
	public function pointlog(){
		global $uid;
		//配置
        $do=R('do','string');
        $pageurl = U("/account/account_log?do=get");
        $page = R("page","int");
        $page = $page?$page:1;
        $wherestr = "ftype='point' AND uid = ".$uid;
        $pagesize = 8;
        
        $condi = array(
                    "startdate"     => R("startdate","string"),
                    "enddate"       => R("enddate","string"),
                    "keyword"       => R("keyword","string")        		
        		);
        //dump($condi);
        if(!empty($condi)){
            foreach($condi as $k=>$v){
                 $pageurl .= "&{$k}=".urlencode($v);
                 $this->assign($k,$v);
                 switch($k){
                    case "startdate":
                        $wherestr .= $v?(" AND (dateline >= '".strtotime($v)."')"):"";
                        break;
                    case "enddate":
                        $wherestr .= $v?(" AND (dateline <= '".strtotime($v)."')"):"";
                        break;
                    case 'keyword':
                        $v = ($v == '关键词')?'':$v;
                        $wherestr .= $v?" AND (amount LIKE '%$v%' OR amount_log LIKE '%$v%')":"";
                        break;
                    default:
                        break;
                 }
            }
        }
        //取数据
        $c = array();
		$c['page']     = $page;
		$c['where']    = $wherestr;
		$c['pagesize'] = $pagesize;
        $result = Q("user_accountlog")->get_list($c,$pageurl);;        
        if($result['list']){
			foreach ($result['list'] as $k=>$v){
				$tmp = C("user")->get_cache_userinfo($v['uid']);
				$result['list'][$k]['uname'] = $tmp['name'];
				$result['list'][$k]['lsh']   = tTime::get_datetime("YmdHis",$v['dateline']).sprintf("%09d",$v['uid']);
			}
		}
        
        //分配数据
        $this->assign("datalist",$result);
        $this->display(); 
	
	}
	//代金券管理
	public function coupon(){
		global $uid,$timestamp;
		$do 			 = R("do","string");
		$page 		 = R("page","int")?:1;
		$pageurl 	 = U("/finance/coupon?do=get");

		//查询搜索
		$where      = "1 AND uid='{$uid}'";
		$condi = array(
			"keyword"   => R("keyword","string"),
			"status"		=> R("status","int"),
		);
		foreach($condi as $k=>$v){
			$v = trim($v);
			switch($k){
				case "keyword":
					$where .= $v?" AND ((code LIKE '%{$v}%') OR (name LIKE '%{$v}%') OR (balance LIKE '%{$v}%'))":"";
					break;
				case "status":
					$where .= ($v != 0)?(" AND status = ".($v-1)):"";
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
			$data['order'] = "expiry DESC";
			$result = M("@coupon")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"load_account_list");
			tAjax::json(array("error"=>0,"msg"=>"加载成功","data"=>$result));
		}
		$this->display();
	}
}
?>