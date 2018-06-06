<?php
class domain_ns_group_model extends tModel{
	public function __construct(){
		parent::__construct("domain_ns_group");
	}
	public function get_list($item= true,$where="1"){
		$tmp_list = M("domain_ns_group")->query($where, "*", "ns_group ASC");
		$list = array();
		foreach ($tmp_list as $key => $value) {
			$list[$value['ns_group']] = $value;
			$list[$value['ns_group']]['data'] = array();
		}
		if ($item == true) {
			$tmp_list_item = Sq("SELECT * FROM @domain_ns_item WHERE status=1 and ns_group IN (SELECT ns_group FROM @domain_ns_group WHERE $where)");
		    foreach ($tmp_list_item as $key => $value) {
				$list[$value['ns_group']]['data'][] = $value;
		    }
			unset($tmp_list_item);
	   }
		unset($tmp_list);
		return $list;
	}
	//通过服务器组标识获取缓存
	public function get_cache_by_ns($ns,$rewrite = false){
		$cacheName = "ns_group_" . $ns;
		$ns_group = tCache::read($cacheName);
		if (empty($ns_group) || $rewrite === true) {
			try{
				$ns_group = M("domain_ns_group")->get_row("ns_group='{$ns}'");
				$ns_group['servers'] = M("domain_ns_item")->query("ns_group='{$ns}' AND status=1","ip,port,domain,mac","sort ASC");
				tCache::write($cacheName, $ns_group);
			}catch(Exception $e){
				$ns_group = array();
			}
		}
		return $ns_group;
	}
	//更新服务器MAC
	public function update_server_mac($host="",$mac=""){
		list($ip,$port) = explode(":",$host);
		return ($ip && $mac) && M("domain_ns_item")->set_data(array("mac"=>$mac))->update("ip='{$ip}'");
	}
}
?>