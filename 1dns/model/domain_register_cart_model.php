<?php
class domain_register_cart_model extends tModel{
	public function __construct(){
		parent::__construct("domain_register_cart");
	}
	public function get($cart_id = 0){
		$return = array();
		if($cart_id){
			$return = M("domain_register_cart")->get_row("cart_id=$cart_id");
		}
		return $return;
	}
	//获取域名注册购物车列表
	public function get_domain_cart($other_where = ""){
		global $uid ;

		$return = array(
			"amount"                    => 0,
			"amount_promation" => 0,
			"num"                          => 0,
			"list"                            => array(),
		);
		if ($uid) {
			//读取用户信息
			$userinfo = C("user")->get_cache_userinfo($uid);

			//读取购物车域名
			$where = "indel=0 AND status=0 AND uid=$uid";
			$where .= $other_where?(" AND ".$other_where):"";
			$tmp = M("domain_register_cart")->query($where,"","dateline ASC","500");
			if($tmp){
				foreach($tmp as $key=>$v){

					//读取域名后缀缓存
					if ($v['type'] == 1) {
						$suffix_p = M("@domain_register_price")->get_cache_by_agent($v['agent']);
						foreach ($suffix_p as $su=>$fix) {
							$reg_domain_type_tmp = substr($v['domain'],strpos($v['domain'],".")+1);
							if ($fix['name'] == $reg_domain_type_tmp) {
								$v['renew_price'] = $fix['renew_price'];
							}
						}
					}

					//促销优惠折扣率
					$v['amount_rate'] = isset($userinfo['setting']['regrate'])?$userinfo['setting']['regrate']:100;
					//总价
					if ($v['type'] == 1 && $v['num'] > 1) {
						$v['amount'] =  sprintf("%.2f",$v['price'] + ($v['renew_price'] * ($v['num'] - 1)));
					}else{
						$v['amount'] =  sprintf("%.2f",$v['price'] * $v['num']);
					}

					//促销优惠折扣价
					$v['amount_promation']    =  sprintf("%.2f",($v['amount']*(100-$v['amount_rate'])  /100));
					//总计
					$v['amount'] = sprintf("%.2f",($v['amount']  - $v['amount_promation']));
					if($v['amount'] < 0){
						$v['amount']  = "0.00";
					}
					//汇总
					$return['num']                         ++;
					$return['amount']                    += $v['amount'] ;
					$return['amount_promation'] += $v['amount_promation'] ;
					$return['list'][] = $v;
				}
				$return['amount'] =   sprintf("%.2f",$return['amount']);
				$return['amount_promation'] =   sprintf("%.2f",$return['amount_promation']);
			}
		}
		return $return;
	}
}
?>