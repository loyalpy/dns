<?php
class domain_service_model extends tModel{
	public function __construct(){
		parent::__construct("domain_service");
	}
	public function get_list($utype = 1,$ukey = 0){
		$where = $utype?"utype=$utype":"1";
		$tmp_list = M("domain_service")->query($where,"*","utype ASC,sort ASC");
		$tmp_list_item = Sq("SELECT * FROM @domain_serviceitem WHERE service_group IN (SELECT service_group FROM @domain_service WHERE {$where}) ORDER BY sort ASC");
			
		$list = array();
		foreach($tmp_list as $key=>$value){
			$list[$value['service_group']] = $value;
			$list[$value['service_group']]['data'] = array();
		}
		foreach($tmp_list_item as $key=>$value){
			if($ukey == 1){
				$list[$value['service_group']]['data'][$value['name']] = $value;
			}else{
				$list[$value['service_group']]['data'][] = $value;
			}
		}
		unset($tmp_list,$tmp_list_item);
		return $list;       
	}

	//获取个人，企业缓存，多条
	public function get_cache_list($utype = 1,$rewrite = false){
		$cacheName = "service_group_utype" . $utype;
		$service_group_list = tCache::read($cacheName);
		if (empty($service_group_list) || $rewrite === true) {
			$tmp = $this->get_list($utype);
			$service_group_list = array();
			foreach($tmp as $key=>$v){
				$service_group_list[$key] = array();
				//basic
				foreach($v as $k2=>$v2){
					if(in_array($k2,array("cost1","service_group","name","sort","ns_group","utype"))){
						$service_group_list[$key][$k2] = $v2;
					}
				}
				//data
				$service_group_list[$key]['data'] = array();
				foreach($v['data'] as $k3=>$v3) {
						if(in_array($v3['name'],array("loadBalanc","QPS","SLA","DDOS","monitorTask"))){
							$service_group_list[$key]['data'] [$v3['name']] = $v3['value'];
						}
					}
			}
			tCache::write($cacheName, $service_group_list);
		}
		return $service_group_list;
	}
	//获取缓存
	//通过服务器组标识获取缓存
	public function get_cache($service_group,$rewrite = false)
	{
		$cacheName = "service_group_" . $service_group;
		$service_group_row = tCache::read($cacheName);
		if (empty($service_group_row) || $rewrite === true) {
			try{
				$service_group_row = M("domain_service")->get_row("service_group='{$service_group}'");
				$service_group_row['data']  = array();
				if(isset($service_group_row['service_group'])){
					$tmp1 = M("domain_serviceitem")->query("service_group='{$service_group}'","*","sort ASC");
					$tmp  = array();
					foreach($tmp1 as $v){
						$tmp[$v['name']] = $v;
					}
					$service_group_row['data'] = $tmp;
					unset($tmp,$tmp1);
				}
				tCache::write($cacheName, $service_group_row);
			}catch(Exception $e){
				$service_group_row = array();
			}
		}
		return $service_group_row;
	}
}
?>