<?php
class coupon_model extends tModel{
	public function __construct(){
		parent::__construct("coupon");
	}
	public function get_coupon_row($code){
		global $uid;
		$return = '';
		if (!empty($code)) {
			$return = M("coupon")->get_row("uid = '{$uid}' AND code = '{$code}'");
		}
		return $return;
	}
	public function get_list($data = array(), $pageurl = ""){
		$data['table'] 		= isset($data['table']) ? $data['table'] : "coupon";
		$data['where'] 		= isset($data['where']) ? $data['where'] : "1";
		$data['page'] 		= isset($data['page']) ? $data['page'] : 1;
		$data['pagesize']   = isset($data['pagesize']) ? $data['pagesize'] : 20;
		$data['order'] 		= isset($data['order']) ? $data['order'] : "dateline DESC";
		$query = new tQuery($data['table']);
		foreach ($data as $k => $v) {
			$query->$k = $v;
		}
		$list = array();
		$list['list'] = $query->find();
		$list['pagebar'] = $query->get_pagebar($pageurl);
		$list['total'] = $query->total;
		$list['totalpage'] = $query->totalpage;
		return $list;
	}
	//会员活动设置
	public function account_give_set($ident,$uid = 0){
		global $timestamp;
		//判断用户是否注册成功，标识是否存在
		if (empty($uid) || empty($ident)) {
			return 0;
		}
		//获取用户缓存
		$userinfo = C("user")->get_cache_userinfo($uid);
		if (isset($userinfo['uid'])) {
			//获取活动配置项
			$account_set = tCache::read("user_accountset_".$userinfo['utype']);
			//判断缓存是否存在
			if (!isset($account_set[$ident])) {
				return 0;
			}
			$account_set_row = $account_set[$ident];
			if (!isset($account_set_row['id'])) {
				return 0;
			}
			//判断是否支持用户类型配置，默认注册用户为0时可以使用
			if (!in_array($userinfo['ulevel'],count($account_set_row['ulevels'])>0?$account_set_row['ulevels']:array(0))) {
				return 0;
			}
			//判断会员是否锁定
			if ($account_set_row['inlock'] == 1) {
				return 0;
			}
			//判断是否在活动开始日期结束日期内
			if ($timestamp < $account_set_row['start_expiry'] || $timestamp > $account_set_row['end_expiry']) {
				return 0;
			}

			//添加方式：1，短信积分余额
			if (!empty($account_set_row['balance'])) {//余额
				M("@account")->update($uid,array("balance"=>"+{$account_set_row['balance']}"),array("balance"=>"{$account_set_row['alias']}，赠送余额 {$account_set_row['balance']}元"));
			}
			if (!empty($account_set_row['sms'])) {//短信
				M("@account")->update($uid,array("sms"=>"+{$account_set_row['sms']}"),array("sms"=>"{$account_set_row['alias']}，赠送短信 {$account_set_row['sms']}条"));
			}
			if (!empty($account_set_row['point'])) {//积分
				M("@account")->update($uid,array("point"=>"+{$account_set_row['point']}"),array("point"=>"{$account_set_row['alias']}，赠送积分 {$account_set_row['point']}个"));
			}

			//添加方式：2，代金券
			if (!empty($account_set_row['coupon_name']) || $account_set_row['coupon'] > 0) {
				//有效期
				$expiry_time = intval($timestamp) + intval($account_set_row["coupon_expiry"])*86400;
				//代金券编码：19位
				$coupon_code = strtoupper(substr(md5($uid.$timestamp),8,16)).rand(10,99);
				//添加用户代金券
				$data = array(
					"aid"				=> $account_set_row['id'],
					"uid"				=> $uid,
					"code"				=> $coupon_code,
					"name"			=> $account_set_row['coupon_name'],
					"balance"			=> $account_set_row['coupon'],
					"need_balance"=> $account_set_row['coupon_need'],
					"type"				=> $account_set_row['coupon_type'],
					"status"			=> 0,//未使用
					"dateline"		=> $timestamp,//生成日期
					"use_dateline" => 0,//使用日期
					"expiry"			=> $expiry_time,
				);
				M("coupon")->set_data($data)->add();
			}
		}
	}
	//获取用户可用代金券
	public function get_coupon($type = array()){
		global $uid,$timestamp;
		if (count($type) <= 0) {
			return 0;
		}
		$coupon = array();
		foreach ($type as $key=>$val) {
			$coupon = array_merge($coupon,M("coupon")->query("uid = $uid AND type = {$val} AND status = 0 AND expiry > $timestamp","","dateline DESC"));
		}
		return $coupon;
	}
}
?>