<?php
/**
 * 登录
* by Thinkhu 2014
*/
class DomainService extends API{
	public function __construct(){
		parent::__construct('DomainService');
	}
	//获取所有套餐
	public function GetPList(){
		global $uid,$utype;
		$page     		= R("page","int");
        $goods_no     = R("goods_no","string");
		$goods_name = R("goods_name","string");
		$res = array(
			"domainlist" => array(),
		);
		if($goods_name){ //如果是传参数域名
			$domain_row = M("@domain")->get($goods_name);
			if(!isset($domain_row['domain_id'])){
				$this->respons_error("未找到该域名");
			}
			$cur_service_group = M("@domain_service")->get_cache($domain_row['service_group']);
			$res = array(
				"sort" => $cur_service_group['sort'],
				"service_group" => $cur_service_group['service_group'],
				"utype" =>$utype,
				"iscost1" => $cur_service_group['iscost1'],
			);
			$res['domainlist'][] = array(
				"num" => 1,
				"utype" =>   $utype,
				"domain" => $domain_row['domain'],
				"service_group" => $cur_service_group['service_group'],
				"service_group_name" => $cur_service_group['name']
			);
		}else {//如果是传参数服务器组
			$cur_service_group = M("@domain_service")->get_cache($goods_no);
			if(!isset($cur_service_group['service_group'])){
				$this->respons_error("套餐没有找到");
			}

			$res = array(
				"sort" => $cur_service_group['sort'],
				"service_group" => $cur_service_group['service_group'],
				"service_group_name" => $cur_service_group['name'],
				"service_price" => $cur_service_group['cost1'],
				"utype" =>$utype,
				"domainlist" => array()
			);

			$enable_service_group = array();
			if($utype  == 1){
				$service_group_utype1 = M("@domain_service")->get_cache_list(1);
				foreach($service_group_utype1 as $key=>$v){
					if($v['sort']<$cur_service_group['sort']){
						$enable_service_group[] = $key;
					}
				}
			}elseif($utype == 2){
				$service_group_utype2 = M("@domain_service")->get_cache_list(1);
				foreach($service_group_utype2 as $key=>$v){
					if($v['sort']<$cur_service_group['sort']){
						$enable_service_group[] = $key;
					}
				}
			}
			$c = array(
				'where' =>"uid=$uid AND service_group IN('".implode("','",$enable_service_group)."')",
				'page'		 =>$page,
				"pagesize" => 6,
			);
			$domain_list = M("@domain")->get_list($c,"__page__?");
			foreach($domain_list['list'] as $key=>$domain_row){
				$res['domainlist'][] = array(
					"num" => 1,
					"utype" =>   $utype,
					"domain" => $domain_row['domain'],
					"service_group" => $domain_row['service_group'],
				);
			}
			$res['total'] = $domain_list['total'];
			$res['totalpage'] = $domain_list['totalpage'];
			$res['pagebar'] = tFun::pagebar_js($domain_list['pagebar'],"__page__?","add_cart_step1",array(0,$cur_service_group['service_group']),"");
		}
		$this->respons_success("加载成功",$res);
	}
}
?>