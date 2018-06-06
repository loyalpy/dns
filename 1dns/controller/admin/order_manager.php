<?php
/**
 * 订单管理
 * by Thinkhu 2014 
 */
class order_manager extends UCAdmin{
	//在线充值
	public function recharge(){
		global $user_id,$timestamp;
		//获取参数
		$t = R("t","string");
    	$t = $t?$t:"all";
    	$t_a = array(
    	   "ing"  => 0,
    	   "success"  => 1,
    	   "fail" => -1,
    	);
    	$status = isset($t_a[$t])?$t_a[$t]:false;
    	$this->assign("t",$t);
    	
    	//处理条件
		$startdate = R('startdate','string');
		$enddate = R("enddate","string");
		$keyword = R("keyword","string");
		$startdate = empty($startdate)?tTime::get_datetime("Y-m-d",($timestamp-30*86400)):$startdate;
		$this->assign("startdate",$startdate);
		$this->assign("enddate",$enddate);
		$this->assign("keyword",$keyword);

		if (is_numeric($keyword)) {
			$where = "uid={$keyword}";
		}else{
			if ($keyword) {
				$uid = M("user")->get_one("uname LIKE '%{$keyword}%' OR email LIKE '{$keyword}' OR mobile LIKE '%{$keyword}%' OR nickname LIKE '%{$keyword}%'","uid");
				$where = "(recharge_no LIKE '%$keyword%' OR trade_no LIKE '%$keyword%' OR uid = '{$uid}')";
			}else{
				$where = "1";
			}
		}
		$where .= ($status === false)?"":" AND status=$status";
		$where .= ($startdate)?(" AND dateline>=".tTime::get_time($startdate)):"";
		$where .= ($enddate)?(" AND dateline<=".tTime::get_time($enddate)):"";
		//开始查询
		$page = R('page','int');
		$pageurl = U("/order_manager/recharge?startdate={$startdate}&enddate={$enddate}&keyword=".urlencode($keyword));

		$query = new tQuery("recharge");
		$query->where = $where;
		$query->pagesize = 30;
		$query->page = $page;
		$query->fields = "*";
		$query->order = "dateline DESC";

		$this->datalist = $query->find();
		$this->pagebar = $query->get_pagebar($pageurl);
		$this->total = $query->total;
		$this->totalpage = $query->totalpage;
		$this->display();
	}
	//在线充值 add
	public function recharge_add(){
		global $uid,$timestamp;
		$id = R("id","int");
		$recharge_uid = R("recharge_uid","string");
		$amount = R("amount","float");
		$kaipiao = R("kaipiao","int");
		if(tUtil::check_hash()){
			//获取充值数据
			$recharge    = sprintf("%.2f",R('amount',"float"));
			$trade_no    = R("trade_no","string");
			$trade_bank  = R("trade_bank","string");
			$trade_date  = R("trade_date","string");
			$kaipiao     = R("kaipiao","int");
			$trade_date  = $timestamp;//0;//strtotime($trade_date);
			
			//获取支付方式
			$payment_obj = new cls_payment();
			$payment_row = $payment_obj->get_payment_byid(7);
			if(empty($payment_row)){
				tAjax::json_error("线下充值方式已经关闭");
			}
			//获得payment_id 获得相关参数
			//载入支付接口文件
			$pay_obj  = $payment_obj->load($payment_row['file_path']);
			if($recharge < 0){
				tAjax::json_error("充值金额有误!");
			}
			if(empty($recharge_uid)){
				tAjax::json_error("充值对象有错误!");
			}
	
			$reData   = array('id'           => 0,
					'uid'          => $recharge_uid,
					'amount'       => $recharge ,
					'payment_name' => $payment_row['name'] ,
					'payment_type' => $payment_row['type'] ,
					'payment_id'   => $payment_row['id'],
					'trade_no'     => $trade_no,
					'trade_date'   => $trade_date,
					'trade_bank'   => $trade_bank,
					'order_no'     => "",
			);
			if($kaipiao == 1){
				$reData['kaipiao']  = 1;
				$reData['r_amount'] = $recharge;
				$reData['amount']   = sprintf("%.2f",$recharge+($recharge*floatval(App::get_conf("site.shui")))/100);
				$reData['kaipiao_tou'] = R("kaipiao_tou","string");
					
			}else{
				$reData['kaipiao']  = 0;
				$reData['r_amount'] = $recharge;
				$reData['kaipiao_tou'] = "";
			}
			$res = $payment_obj->get_payment_info(7,$reData);
	
				$result = $reData;
				$result['id']          = $res['M_OrderId'];
				$result['recharge_no'] = $res['M_OrderNO'];
				$result['amount']      = $res['M_Amount'];
				$result['payment_id']  = $res['M_Paymentid'];
				$result['error']       = 0;
				$result['callback']    = "reload";
				$result['message']     = "添加成功！";
				tAjax::json($result);
		}
	}
	//在线充值审核
	public function recharge_sh(){
		global $uid;
		$id = R("id","int");
		$recharge_no = R("recharge_no","string");
		if(tUtil::check_hash()){
			$status = R("status","int");
			$status = in_array($status,array(-1,0,1))?$status:0;
			$row = M("recharge")->get_row("id='{$id}'");
			if(!isset($row['id'])){
				tAjax::json_error("该充值订单不存在！");
			}
			if($row['status'] == 1){
				tAjax::json_error("该充值已经完成,不可操作！");
			}
			if($status == 1){
				if(cls_payment::update_recharge($recharge_no)){
					//判断是否有充值并支付的订单
					$recharge_row = M("recharge")->get_row("recharge_no='{$recharge_no}'");
					if($recharge_row['inpay'] == 1){//充值并需要支付
						if($recharge_row['order_no']){
							$ret = C("payment")->pay_account($recharge_row['order_no'],"balance",$recharge_row['order_table']);
							if($ret === 1){
								if($recharge_row['order_table'] == "order"){
									SDK::web_api("/Order/Send", array("order_no"=>$recharge_row['order_no']),$recharge_row['uid']);
								}elseif($recharge_row['order_table'] == "register_order"){
									SDK::web_api("/Order/SendRegDomain", array("order_no"=>$recharge_row['order_no']),$recharge_row['uid']);
								}
							}
						}
					}
					C("user")->log('审核充值',"审核成功！充值单号：{$recharge_no}");
					tAjax::json(array("error"=>0,"message"=>"充值审核成功！","callback"=>"reload"));
				}else{
					tAjax::json_error("操作失败！可能余额不足");
				}
			}else{
				M("recharge")->set_data(array("status"=>$status));
				if(M("recharge")->update("id=$id")){
					tAjax::json(array("error"=>0,"message"=>"操作审核成功！","callback"=>"reload"));
				}else{
					tAjax::json_error("操作失败！");
				}
			}
		}else{
			$row = M("recharge")->get_row("id='{$id}'");
			if(!isset($row['id'])){
				tAjax::json_error("该充值订单不存在！");
			}
			if($row['status'] == 1){
				tAjax::json_error("该充值已经完成,不可操作！");
			}
			$to_userinfo = C("user")->get_cache_userinfo($row['uid']);
			$row['to_name'] = $to_userinfo['name'];
			$row['to_balance'] = $to_userinfo['account']['balance'];
			$row['date'] = tTime::get_datetime("Y-m-d H:i:s",$row['dateline']);
			$row['trade_date'] = tTime::get_datetime("Y年m月d日",$row['trade_date']);
			tAjax::json($row);
		}
	}
	//在线开票
	public function recharge_kaipiao(){
	global $uid;
		$id = R("id","int");
		$recharge_no = R("recharge_no","string");
		if(tUtil::check_hash()){
			$kaipiao = R("kaipiao","int");
			$kaipiao = in_array($kaipiao,array(0,1,2,3,4))?$kaipiao:1;
			$row = M("recharge")->get_row("id='{$id}'");
			if(!isset($row['id'])){
				tAjax::json_error("该充值订单不存在！");
			}
			$kaipiao_ship = R("kaipiao_ship","string");
			$kaipiao_post = R("kaipiao_post","string");
			
			$data = array(
				"kaipiao" => $kaipiao,
				"kaipiao_tou" => R("kaipiao_tou","string"),
				"kaipiao_ship" => $kaipiao_ship?tFun::arr2str($kaipiao_ship):"",
				"kaipiao_post" => $kaipiao_post?tFun::arr2str($kaipiao_post):"",
			);
			$ret = M("recharge")->set_data($data)->update("id=$id");
			if($ret){
				C("user")->log('操作发票',"操作成功！充值单号：{$recharge_no},开票状态[{$kaipiao}]");
				tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"reload"));
			}else{
				tAjax::json_error("操作失败！");
			}
		}else{
			$row = M("recharge")->get_row("id='{$id}'");
			if(!isset($row['id'])){
				tAjax::json_error("该充值订单不存在！");
			}
			$to_userinfo = C("user")->get_cache_userinfo($row['uid']);
			$row['kaipiao_ship'] = $row['kaipiao_ship']?tFun::str2arr($row['kaipiao_ship']):array();
			$row['kaipiao_post'] = $row['kaipiao_post']?tFun::str2arr($row['kaipiao_post']):array();
			$row['to_name'] = $to_userinfo['name'];
			$row['to_balance'] = $to_userinfo['account']['balance'];
			$row['date'] = tTime::get_datetime("Y-m-d H:i:s",$row['dateline']);
			$row['trade_date'] = tTime::get_datetime("Y年m月d日",$row['trade_date']);
			tAjax::json($row);
		}
	}
	//在线提现
	public function withdraw(){
		global $user_id,$timestamp;
		//获取参数
		//获取参数
		$t = R("t","string");
    	$t = $t?$t:"all";
    	$t_a = array(
    	   "ing"  => 0,
    	   "success"  => 1,
    	   "fail" =>2,
    	   "concel"=>-1,
    	);
    	$status = isset($t_a[$t])?$t_a[$t]:false;
    	$this->assign("t",$t);
    	
		$startdate = R('startdate','string');
		$enddate = R("enddate","string");
		$keyword = R("keyword","string");
		$startdate = empty($startdate)?tTime::get_datetime("Y-m-d",($timestamp-360*86400)):$startdate;
		
		$this->assign("startdate",$startdate);
		$this->assign("enddate",$enddate);
		$this->assign("keyword",$keyword);
		
		//处理条件
		$where = is_int($keyword)?"uid={$keyword}":($keyword?"(withdraw_no LIKE '%$keyword%' OR uname LIKE '%$keyword%')":"1");
		$where .= ($status === false)?"":" AND status=$status";
		$where .= ($startdate)?(" AND dateline>=".tTime::get_time($startdate)):"";
		$where .= ($enddate)?(" AND dateline<=".tTime::get_time($enddate)):"";
		//开始查询
		$page = R('page','int');
		$pageurl = U("/order_manager/withdraw?startdate={$startdate}&enddate={$enddate}&keyword=".urlencode($keyword));
		
		$query = new tQuery("withdraw");
		$query->where = $where;
		$query->pagesize = 30;
		$query->page = $page;
		$query->fields = "*";
		$query->order = "dateline DESC";
		
		$this->datalist = $query->find();
		$this->pagebar = $query->get_pagebar($pageurl);
		$this->total = $query->total;
		$this->totalpage = $query->totalpage;
		$this->display();
	}
	//在线提现审核
	public function withdraw_sh(){
		global $uid;
		$id = R("id","int");
		$trade_no = R("trade_no","string");
		if(tUtil::check_hash()){
			$status = R("status","int");
			$bank_trade_no = R("bank_trade_no","string");
			$status = in_array($status,array(-1,0,1,2))?$status:0;
			
			if(empty($bank_trade_no)){
				tAjax::json_error("交易编号请认真填写！");
			}
			
			$row = M("withdraw")->get_row("id='{$id}'");
			if(!isset($row['id'])){
				tAjax::json_error("该申请提现不存在！");
			}
			if($row['status'] > 0){
				tAjax::json_error("该申请提现已经执行审核操作,不可再操作！");
			}				
			if($status == 1){
				if(cls_payment::update_withdraw($id,array("bank_trade_no"=>$bank_trade_no))){
					C("user")->log('审核提现',"审核成功！提现单号：{$bank_trade_no}");
					tAjax::json(array("error"=>0,"message"=>"提现审核成功！","callback"=>"reload"));
				}else{
					tAjax::json_error("操作失败！可能余额不足");
				}
			}else{
				M("withdraw")->set_data(array("status"=>$status));
				if(M("withdraw")->update("id=$id")){
					tAjax::json(array("error"=>0,"message"=>"操作审核成功！","callback"=>"reload"));
				}else{
					tAjax::json_error("操作失败！");
				}
			}
		}else{
			$row = M("withdraw")->get_row("id='{$id}'");
			if(!isset($row['id'])){
				tAjax::json_error("该提现订单不存在！");
			}
			if($row['status'] == 1){
				tAjax::json_error("该提现已经完成,不可操作！");
			}
			
			$to_userinfo = C("user")->get_cache_userinfo($row['uid']);
			$row['to_name'] = $to_userinfo['name'];
			$row['to_balance'] = $to_userinfo['account']['balance'];
			$row['date'] = tTime::get_datetime("Y-m-d H:i:s",$row['dateline']);
			$row['bank'] = $row['bank_no'] = $row['bank_name'] = "";
			$tmp = explode("\t",$row['bankinfo']);
			if(isset($tmp[0]) && isset($tmp[1]) && isset($tmp[2])){
				$row['bank'] = tFun::dataconfig("pay_bank",$tmp[0]);
				$row['bank_no'] = $tmp[1];
				$row['bank_name'] = $tmp[2];
			}
			tAjax::json($row);
		}
	}	
	
	//订单产品列表
	public function orderlist(){
		global $timestamp;
		$do = R("do","string");
		$status = R("status","int");
		$expiry = R("expiry","int");
		
		$page = R("page","int");
	    $page = $page?$page:1;	
		$pageurl = U("/order_manager/orderlist?do=get&status=$status");
		$condi = array(
		    "status"    => $status,
			"keyword"   => R("keyword","string"),
			"startdate" => R("startdate","string"),
		    "enddate"   => R("enddate","string"),
		);

		$where = "indel=0";
		foreach($condi as $k=>$v){
			$v = trim($v);
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND dateline<=".strtotime($v)):"";
					break;
				case "status":
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
				case "keyword":
					$where .= $v?" AND (buy_uname LIKE '%{$v}%' OR order_no LIKE '%{$v}%')":"";
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
			$result = C("order")->get_list($c,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
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
		$this->assign("status",$status);
		$this->display();
	}
	//订单产品列表
	public function orderlist_item(){
		global $timestamp;
		$do = R("do","string");
		$status = R("status","int");
		$expiry = R("expiry","int");
		
		$page = R("page","int");
	    $page = $page?$page:1;	
		$pageurl = U("/order_manager/orderlist_item?do=get&status=$status");
		$condi = array(
		    "status"    => $status,
			"keyword"   => R("keyword","string"),
			"startdate" => R("startdate","string"),
		    "enddate"   => R("enddate","string"),
		);

		$where = "1".($expiry>1?" AND b.expiry > $timestamp AND b.expiry < ".($timestamp+$expiry*86400):($expiry == 1?" AND b.expiry < {$timestamp}":""));
		foreach($condi as $k=>$v){
			$v = trim($v);
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND b.dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND b.dateline<=".strtotime($v)):"";
					break;
				case "keyword":
					$where .= $v?" AND (b.buy_uname LIKE '%{$v}%' OR b.order_no LIKE '%{$v}%' OR a.goods_no LIKE '%{$v}%')":"";
					break;
				default:
					$where .= $v?" AND b.{$k}='{$v}'":"";
					break;
			}
		}
		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$result = C("order")->get_orderserver($c);
			if($result['list']){
				foreach($result['list'] as $k=>$v){
					$result['list'][$k]['specs_str'] = $result['list'][$k]['new_specs_str'] = "";
					$v['specs'] = $v['specs']?tUtil::unserialize($v['specs']):array();
					$v['new_specs'] = $v['new_specs']?tUtil::unserialize($v['new_specs']):array();
					$v['specs_str'] = $v['new_specs_str'] = "";
					foreach($v['specs'] as $k2=>$v2){
						$v['specs_str'] = $v['specs_str']. "{$v2['name']}+{$v2['label']} /";
					}
					foreach($v['new_specs'] as $k2=>$v2){
						$v['new_specs_str'] = $v['new_specs_str']. "{$v2['name']}+{$v2['label']} /";
					}
					$result['list'][$k] = $v;
				}
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
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
		$this->assign("expiry",$expiry);
		$this->display();
	}
	
	//订单提交
	public function orderlist_view(){
		if(tUtil::check_hash()){
		
		}else{
			//配置
			$order_no = R("order_no","string");
	        $row = C("order")->get_byno($order_no);
		    if(!isset($row['id'])){
		    	$this->_msg("未找到该订单信息！","","/order/orderlist#返回");
		    }
		    $serverlist = C("order")->get_orderserver(array(
		    	"where" => "order_no = '{$row['order_no']}'",
		    ));
		    //订单产品信息
		    $goods = $row['order_goods'];
		    
		    $cates = C("category","goods_cate")->get(1);
		    $this->cate = $cates[$goods['fid']];
		    $this->cate_model = tCache::read("goods_model_{$this->cate['model_id']}");
		    
		    $specs = $goods['myspecs'];
		    //失效时间
		    $order_expiry = isset(App::$data['site_config']['order_expiry'])?App::$data['site_config']['order_expiry']:0;
		    $order_expiry = $order_expiry?$order_expiry:45;
		    
		    //分配数据
		    $this->assign("serverlist",$serverlist);
		    $this->assign("orderinfo",$row);
		    $this->assign("goods",$goods);
		    $this->assign("specs",$specs);
		    $this->assign("order_expiry",$order_expiry);
		    $this->display();
		}
	}
	//订单删除
	public function orderlist_del(){
		global $uid,$timestamp,$realip;
		$order_no = R("order_no","string");
		$data = array();
		if($order_no){
			$data = C("order")->get_byno($order_no);
		}
		if(!isset($data['id'])){
			tAjax::json_error("订单错误！");
		}
		if($data['status'] >2){
			tAjax::json_error("订单状态为/作废/生效中/结束 不容许删除！");
		}
		//跨权限删除
		if($uid != $data['uid'] && !$this->check_rpurview(5)){
			tAjax::json_error("您无权进行此项操作！");
		}
		$ret = C("order")->del($data['id']);
		if($ret>0){
			C("user")->log("删除订单","ID:{$data['id']};客户ID：{$data['client']},{$data['client_name']},订单号：{$data['order_no']},金额：{$data['amount']}");
			tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>"reload"));
		}else{
			tAjax::json_error("删除失败！");
		}
	}
	//订单状态变更
	public function orderlist_status(){
		global $uid,$timestamp,$realip;
		$order_no = R("order_no","string");
		$status   = R("status","int");
		if(!in_array($status,array(1,3,5))){
			tAjax::json_error("状态错误！");
		}
		$data = array();
		if($order_no){
			$data = C("order")->get_byno($order_no);
		}
		if(!isset($data['id'])){
			tAjax::json_error("订单错误！");
		}
		$ret = M("order")->set_data(array("status"=>$status))->update("id='{$data['id']}'");
		if($ret>0){
			C("user")->log("改变订单状态","ID:{$data['id']};订单号：{$data['order_no']},金额：{$data['amount']},状态：".($status == 5?"完成":($status == 3?"作废":"创建")));
			tAjax::json(array("error"=>0,"message"=>"改变订单状态成功！","callback"=>"reload"));
		}else{
			tAjax::json_error("改变订单状态失败！");
		}
	}
	//订单日志
	public function orderlist_log(){
		$order_no = R("order_no","string");
		$c = array();
		$page = R("page","int");
		$pageurl = U("/order_manager/orderlist_log?order_no={$order_no}");
		$c['page']  = $page;
		$c['pagesize'] = 20;
		$c['where'] = "order_no='{$order_no}'";
		$result = C('order')->get_loglist($c,$pageurl);
		$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"view_loglist",array($order_no));
		tAjax::json($result);
	}
	//订单收入
	public function order_finance(){
		$this->redirect(U("/finance_manager/finance_in"),false);
	}
	//域名解析订单
	public function parser(){
		$do = R("do","string");
		$ftype = R("ftype","string");
		$pagesize = R("pagesize","string");
		$pagesize = $pagesize?$pagesize:"40";
		$orderby = R("orderby","string","dateline!DESC");
		$mark = R("mark","int");
		if(!in_array($ftype,array(1,2,3,4))){
			$ftype = 1;
		}
		$this->assign("ftype",$ftype);

		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/order_manager/parser?do=get&ftype={$ftype}");
		$condi = array(
			"keyword"   => R("keyword","string"),
			"startdate"  => R("startdate","string"),
			"enddate"   => R("enddate","string"),
			"uid"   			=> R("uid","int"),
			"status"       => $ftype,
		);
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
					if ($v) {
						$uid = M("user")->get_one("uname LIKE '%{$v}%' OR email LIKE '{$v}' OR mobile LIKE '%{$v}%' OR nickname LIKE '%{$v}%'","uid");
						$domains = M("order_item")->query("goods_name LIKE '%{$v}%'","order_no","",500);
						$domains = implode("','",array_map('array_shift',$domains));
						if (empty($domains)) {
							$where .= $v?" AND (order_no LIKE '%{$v}%' OR amount LIKE '%{$v}%' OR uid = '{$uid}')":"";
						}else{
							$where .= " AND order_no IN('".$domains."')";
						}
					}else{
						$where .= " ";
					}

					break;
				case "status":
					if ($v == 1) {
						$where .= " AND status IN(3,4,5)";
					}elseif ($v == 2) {
						$where .= " AND status IN(1,2) AND indel = 0";
					}elseif ($v == 3) {
						$where .= " AND status = 0";
					}else{
						$where .= " AND indel IN(1,2)";
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
			$c['order'] = str_replace("!"," ",$orderby);
			$c['pagesize'] = $pagesize;
			$result = M("@order")->get_list($c,$pageurl);
			if ($mark) {
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"show_parse_order",array($condi['uid'],$condi['keyword']));
			}else{
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist",array($orderby));
			}
			$result['orderby'] = $orderby;
			if($result['list']){
				foreach ($result['list'] as $k=>$v){
					$tmp = C("user")->get_cache_userinfo($v['uid']);
					$result['list'][$k]['uid'] = isset($tmp['name'])?$tmp['name']:"-";
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
		$this->assign("pageurl",$pageurl);
		$this->assign("condi",$condi);
		$this->display();
	}
	//域名注册订单
	public function register(){
		$do = R("do","string");
		$ftype = R("ftype","string");
		$pagesize = R("pagesize","int");
		$pagesize = $pagesize?$pagesize:20;
		$orderby = R("orderby","string","dateline!DESC");
		$mark = R("mark","int");
		if(!in_array($ftype,array(1,2,3,4))){
			$ftype = 1;
		}
		$this->assign("ftype",$ftype);

		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/order_manager/register?do=get&ftype={$ftype}");
		$condi = array(
			"keyword"   => R("keyword","string"),
			"startdate" => R("startdate","string"),
			"enddate"   => R("enddate","string"),
			"uid"   			=> R("uid","int"),
			"status"       => $ftype,
		);
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
					if ($v) {
						$uid = M("user")->get_one("uname LIKE '%{$v}%' OR email LIKE '{$v}' OR mobile LIKE '%{$v}%' OR nickname LIKE '%{$v}%'","uid");
						$domains = M("register_order_item")->query("domain LIKE '%{$v}%'","order_no","",500);
						$domains = implode("','",array_map('array_shift',$domains));
						if (empty($domains)) {
							$where .= $v?" AND (order_no LIKE '%{$v}%' OR amount LIKE '%{$v}%' OR uid = '{$uid}')":"";
						}else{
							$where .= " AND order_no IN('".$domains."')";
						}
					}else{
						$where .= " ";
					}
					break;
				case "status":
					if ($v == 1) {
						$where .= " AND status IN(3,4,5,6)";
					}elseif ($v == 2) {
						$where .= " AND status IN(1,2) AND indel = 0";
					}elseif ($v == 3) {
						$where .= " AND status = 0";
					}else{
						$where .= " AND indel IN(1,2)";
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
			$c['order'] = str_replace("!"," ",$orderby);
			$c['pagesize'] = $pagesize;
			$result = M("@register_order")->get_list($c,$pageurl);
			if($result['list']){
				foreach ($result['list'] as $k=>$v){
					$tmp = C("user")->get_cache_userinfo($v['uid']);
					$result['list'][$k]['uid'] = isset($tmp['name'])?$tmp['name']:"-";
				}
			}
			if ($mark) {
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"show_register_order",array($condi['uid'],$condi['keyword']));
			}else{
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist",array($orderby));
			}
			$result['orderby'] = $orderby;
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
	//用户充值记录
	public function recharge_action(){
		$do = R("do","string");
		$page = R("page","int");
		$pagesize = R("pagesize","int");
		$page = $page?$page:1;
		$pagesize = $pagesize?$pagesize:30;
		$mark = R("mark","int");
		$pageurl = U("/order_manager/recharge_action?do=get");
		$condi = array(
			"startdate" => R("startdate","string"),
			"enddate"   => R("enddate","string"),
			"keyword"   => R("keyword","string"),
			"uid"   => R("uid","int"),
		);
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
					if ($v) {
						$uid = M("user")->get_one("uname LIKE '%{$v}%' OR email LIKE '{$v}' OR mobile LIKE '%{$v}%' OR nickname LIKE '%{$v}%'","uid");
						$where .= " AND uid='{$uid}'";
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
			$result = Q('recharge')->get_list($c,$pageurl);
			if($result['list']){
				foreach ($result['list'] as $k=>$v){
					$tmp = C("user")->get_cache_userinfo($v['uid']);
					$result['list'][$k]['uname'] = isset($tmp['name'])?$tmp['name']:"unknow";
				}
			}
			if ($mark) {
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"show_order",array($condi['uid'],$condi['keyword']));
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
		$this->assign("condi",$condi);
		$this->display();
	}
}
?>
