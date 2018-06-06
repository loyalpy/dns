<?php
class order extends UC{
	public function __construct(){
		parent::__construct('order');
	}
	public function order_retry_send(){
		$order_no = R("order_no","string");
		$ret = SDK::web_api("/Order/Send",array("order_no"=>$order_no));
		die("");
	}
	public function order_view(){
		global $uid;
		$order_no = R("order_no","string");
		$orderinfo = M("@order")->get($order_no);
		if(!isset($orderinfo['order_no'])){
			$this->_msg("获取订单出错");
		}
		if($orderinfo['uid'] != $uid){
            I("非法查看",U("account@/order/order"));
        }
		$this->assign("orderinfo",$orderinfo);
		$this->assign("domain_service",M("@domain_service")->get_cache_list(0));
		$this->display();
	}
	//购物车
	public function cart_shopping(){
		global $uid,$timestamp;
		if(tUtil::check_hash()) {
			$cart_ids 		= R("cart_ids","string");
			$coupons  = R("coupons","string");
			//判断如果代金券存在是否过期等不能使用
			if (!empty($coupons)) {
				$res = M("coupon")->get_row("uid = '{$uid}' AND code = '{$coupons}'");
				if (isset($res['id'])) {
					if ($res['status'] == 1) {
						I("代金券已使用",U("account@/order/cart_shopping"));
					}
					if ($res['expiry'] < $timestamp) {
						I("代金券已过期",U("account@/order/cart_shopping"));
					}
				}
			}
			$cartlist0 = $cartlist1 =  array();
			if($cart_ids){
				$cartlist0 = M("@cart")->get_mycart_domain("a.cart_id IN($cart_ids)");
				$cartlist1 = M("@cart")->get_mycart_goods("cart_id IN($cart_ids)");
			}
			$this->assign("cartlist0",$cartlist0);
			$this->assign("cartlist1",$cartlist1);
			$this->assign("coupon_arr",M("@coupon")->get_coupon_row($coupons));
			$this->display("/order/cart_shopping_step2");
		}else{
			$this->assign("cartlist0",M("@cart")->get_mycart_domain());//域名解析
			$this->assign("cartlist1",M("@cart")->get_mycart_goods());//短信
			$this->assign("coupon",M("@coupon")->get_coupon(array(1,2)));//代金券1,通用2域名解析专用
			$this->display();
		}
	}
	//我的订单
	public function order(){
		global $uid,$timestamp;
		$do 			 = R("do","string");
		$page 		 = R("page","int")?:1;
		$pageurl 	 = U("/order/order?do=get");

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
						$domains = M("order_item")->query("goods_name LIKE '%{$v}%'","order_no","",500);
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
			$result = M("@order")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"load_order_list");
			tAjax::json(array("error"=>0,"msg"=>"加载成功","data"=>$result));
		}
		$this->assign("pageurl",$pageurl);
		$this->assign("domain_service",M("@domain_service")->get_cache_list(0));
		$this->display();
	}
}
?>