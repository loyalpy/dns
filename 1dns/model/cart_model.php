<?php
class cart_model extends tModel{
	public function __construct(){
		parent::__construct("cart");
	}
	public function get($cart_id = 0){
		$return = array();
		if($cart_id){
			$return = M("cart")->get_row("cart_id=$cart_id");
		}
		return $return;
	}
	//获取域名套餐购物车列表
	public function get_mycart_domain($other_where = ""){
		global $uid,$timestamp ;
		$userinfo = C("user")->get_cache_userinfo($uid);
		$return = array(
			"amount"                    => 0,
			"amount_promation" => 0,
			"amount_other"          => 0,
			"num"                          => 0,
			"list"                            => array(),
		);
		$where = "a.indel=0 AND a.status=0 AND a.type=0 AND a.uid=$uid";
		$where .= $other_where?(" AND ".$other_where):"";
		$tmp = M("cart AS a LEFT JOIN @domain AS b ON a.goods_name=b.domain")->query($where,"a.*,b.service_group,b.service_expiry");
		if($tmp){
			foreach($tmp as $key=>$v){
				$v['amount_other'] = "0.00";
				$old_service_group   = $v['service_group'];
				$new_service_group = $v['goods_no'];
				$old_service_group_info     = M("@domain_service")->get_cache($old_service_group);
				$new_service_group_info   = M("@domain_service")->get_cache($new_service_group);

				$v['service_group_name'] = $old_service_group_info['name'];
				$v['goods_no_name']       = $new_service_group_info['name'];
				$v['price']                         = $new_service_group_info['cost1'];
				//促销优惠折扣率
				$v['amount_rate'] = isset($userinfo['setting']['rate'])?$userinfo['setting']['rate']:100;
				//计算套餐余额
				if($timestamp< $v['service_expiry'] && $new_service_group_info['sort'] >$old_service_group_info['sort']){
					//套餐余额
					$v['amount_other'] =sprintf("%.2f",(($old_service_group_info['cost1']*$v['amount_rate'])/(100*30)) *  (($v['service_expiry'] - $timestamp)/86400));
				}
				//总价
				$v['amount'] =  sprintf("%.2f",$v['price'] * $v['num']);
				//促销优惠折扣价
				$v['amount_promation']    =  sprintf("%.2f",($v['amount']*(100-$v['amount_rate'])  /100));
				//总计
				$v['amount'] = sprintf("%.2f",($v['amount']  - $v['amount_promation'] - $v['amount_other'] ));
				if($v['amount'] < 0){
					$v['amount']  = "0.00";
				}
				//汇总
				$return['num']                         ++;
				$return['amount']                    += $v['amount'] ;
				$return['amount_promation'] += $v['amount_promation'] ;
				$return['amount_other']          += $v['amount_other'] ;
				$return['list'][] = $v;
			}
			$return['amount'] =   sprintf("%.2f",($return['amount'] < 0)?0:$return['amount']);
			$return['amount_promation'] =   sprintf("%.2f",$return['amount_promation']);
			$return['amount_other'] =   sprintf("%.2f",$return['amount_other']);
		}
		return $return;
	}
	//获取短信增值服务套餐列表
	public function get_mycart_goods($other_where = ""){
		global $uid ;

		$userinfo = C("user")->get_cache_userinfo($uid);

		$return = array(
			"amount"                    => 0,
			"amount_promation" => 0,
			"num"                          => 0,
			"list"                            => array(),
		);
		$where = "indel=0 AND status=0 AND type=1 AND uid=$uid";
		$where .= $other_where?(" AND ".$other_where):"";
		$res =  M("cart")->query($where,"");
		if ($res) {
			foreach ($res as $k=>$v) {
				//促销优惠折扣率
				$v['amount_rate'] = isset($userinfo['setting']['rate'])?$userinfo['setting']['rate']:100;
				//总价
				$v['amount'] =  sprintf("%.2f",$v['price'] * $v['num']);
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
			$return['amount'] =   sprintf("%.2f",($return['amount'] < 0)?0:$return['amount']);
			$return['amount_promation'] =   sprintf("%.2f",$return['amount_promation']);
		}
		return $return;
	}
}
?>