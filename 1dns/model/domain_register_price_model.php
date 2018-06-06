<?php
class domain_register_price_model extends tModel{
	public function __construct(){
		parent::__construct("domain_register_price");
	}
	public function get_item($type = "",$agent = ""){
		$return = array();
		if($type){
			$return = M("domain_register_price")->query("type='{$type}' AND agent = '{$agent}'","*","sort ASC");
		}
		return $return;
	}
	public function get_list($t){
		$tmp_list = Sq("SELECT id,type,agent from wo_domain_register_price where agent = '{$t}' GROUP BY type ORDER BY type ASC");
		$list = array();
		$r_type = tCache::read("data_config");
		foreach ($tmp_list as $key => $value) {
			$list['list'][$key]['id'] 		= $value['id'];
			$list['list'][$key]['type'] 	= $value['type'];
			$list['list'][$key]['type_cn'] 	= $r_type['register_domain'][$value['type']];
			$list['list'][$key]['agent']   = $r_type['domain_agent'][$value['agent']];
			$list['list'][$key]['data']		= $this->get_item($value['type'],$value['agent']);
		}
		unset($tmp_list);
		return $list;
	}
	//通过服务器组标识获取缓存
	public function get_cache_by_agent($agent,$rewrite = false)
	{
		$cacheName = "domain_register_price" . $agent;
		$domain_register_price = tCache::read($cacheName);
		if (empty($domain_register_price) || $rewrite === true) {
			$domain_register_price = M("domain_register_price")->query("agent = '{$agent}'","id,type,name,new_price,renew_price","sort ASC");
			tCache::write($cacheName, $domain_register_price);
		}
		return $domain_register_price;
	}
}
?>