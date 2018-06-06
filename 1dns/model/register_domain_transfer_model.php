<?php
class register_domain_transfer_model extends tModel{
	public function __construct(){
		parent::__construct("register_domain_transfer");
	}
	public function get_list($data = array(), $pageurl = ""){
		global $uid,$timestamp;
		$data['table'] 		= isset($data['table']) ? $data['table'] : "register_domain_transfer";
		$data['where'] 		= isset($data['where']) ? $data['where'] : "1";
		$data['page'] 		= isset($data['page']) ? $data['page'] : 1;
		$data['pagesize']   = isset($data['pagesize']) ? $data['pagesize'] : 20;
		$data['order'] 		= isset($data['order']) ? $data['order'] : "id DESC";
		$query = new tQuery($data['table']);
		foreach ($data as $k => $v) {
			$query->$k = $v;
		}
		$list = array();
		$list['list'] = $query->find();
		$userinfo = C("user")->get_cache_userinfo($uid);
		foreach ($list['list'] as $k=>$v) {
			if ($v['status'] == 2) { //域名转入转移中，检测转移状态
				$check_res = SDKdomain::domain_transfer_query("QueryTransferInState",$v['domain']);
				if ($check_res['status'] == 1 && $check_res['list']['ret'] == 100) {
					if (preg_match("/pending-transfer/is", $check_res['list']['info'],$matched)) { //待转移
						$list['list'][$k]['bz'] = "待转移(等待邮箱确认)";
					}
					if (preg_match("/transferring/is", $check_res['list']['info'],$matched)) {//转移中
						$list['list'][$k]['bz'] = "转移中";
					}
					if (preg_match("/(authcode-invalid)|(Registrant-reject)|(Registry-failure)|(transferring-process)|(xinnet-cancelled)|(FOA-overdue)|(system-error)/is", $check_res['list']['info'],$matched)) {//转移失败,更改状态为转移失败
						$list['list'][$k]['bz'] = "转移失败:{$check_res['list']['info']}";
						M("register_domain_transfer")->set_data(array("status"=>3,"bz"=>"{$check_res['list']['info']}"))->update("uid = '{$uid}' AND domain = '{$v['domain']}'");
					}
					if (preg_match("/transfer-success/is", $check_res['list']['info'],$matched)) {//转移成功，更改状态为转移成功
						$list['list'][$k]['bz'] = "转移成功";
						M("register_domain_transfer")->set_data(array("status"=>4))->update("uid = '{$uid}' AND domain = '{$v['domain']}'");
					}
				}
			}elseif($v['status'] == 3){
				//返回账户金额
				$order_item_row = M("register_order_item")->get_row("domain = '{$v['domain']}' AND type = 3 AND status <> 2");
				if (isset($order_item_row['amount'])) {
					$order_row = M("register_order")->get_row("order_no = '{$order_item_row['order_no']}' AND status <> 5 AND send_status <> 2");
					if (isset($order_row['order_id'])) {
						//将订失败的订单子项目状态更改
						M("register_order_item")->set_data(array(
							"status" => 2,
							"status_dateline"=>$timestamp,
						))->update("status = 0 AND domain = '{$v['domain']}' AND type = 3");
						//退款
						M("@account")->update($uid,array("balance"=>"+{$order_item_row['amount']}"),array("balance"=>"域名转入续费，返回账户金额{$order_item_row['amount']}元"));
						//更改订单状态为已退款
						M("register_order")->set_data(array(
							"send_status"=>2,
							"send_dateline" =>$timestamp,
							"status"=>5,
							"pay_status"=>2
						))->update("order_no='{$order_item_row['order_no']}'");
						C("user")->send_meail_usual($userinfo['email'],"域名转入续费失败","域名转入续费失败,您的账户金额已返还，请查看，八戒DNS祝您生活愉快");
					}
				}
			}elseif ($v['status'] == 4){
				//加入域名注册表
				if (!M("register_domain")->get_one("domain='{$v['domain']}'","count('id')")) {
					$ns_group = M("@domain_ns_group")->get_cache_by_ns("free");
					$ns_group_ns = isset($ns_group['ns'])?$ns_group['ns']:"";

					$order_row = M("register_order_item")->get_row("uid = '{$uid}' AND domain = '{$v['domain']}'");
					if (isset($order_row['order_no'])) {
						$add_register_domain = array(
							"domain"     => $v['domain'],
							"type"          => $order_row['domain_type'], //判断是0国际域名，1国内域名
							"reg_type" => 1,
							"reg_time"  => strtotime($v['creattime']),
							"exp_time" => strtotime($v['exptime']),
							"dateline" => $timestamp,
							"uid"        => $uid,
							"ns"         =>$ns_group_ns, //域名ns
							"agent"   =>1, //域名代理服务商
							"status"  => 1,
						);
						$domain_reg_id = M("register_domain")->set_data($add_register_domain)->add();
						//更改用户注册域名数
						M("@account")->update($uid,array("register_domains"=>"+1"),array("register_domains"=>"域名转入,增加一个"));
						$attachinfo_arr = array(
							"did"                       => $domain_reg_id,
							"uid"                       => $uid,
						);
						M("register_domain_attachinfo")->set_data($attachinfo_arr)->add();
						//加入域名解析表
						SDK::web_api("/Domain/AddByUid",array("domain"=>$v['domain']));
						//发送邮件通知
						C("user")->send_meail_usual($userinfo['email'],"域名转入续费成功","域名转入成功,八戒DNS祝您生活愉快");
					}
				}
			}
		}
		$list['pagebar'] = $query->get_pagebar($pageurl);
		$list['total'] = $query->total;
		$list['totalpage'] = $query->totalpage;
		return $list;
	}
}
?>