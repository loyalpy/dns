<?php
class domain_acl_set_model extends tModel{
	public function __construct(){
		parent::__construct("domain_acl_set");
	}
	public function get_list($data = array(), $pageurl = ""){
		$data['table'] 		= isset($data['table']) ? $data['table'] : "domain_acl_set";
		$data['where'] 		= isset($data['where']) ? $data['where'] : "1";
		$data['page'] 		= isset($data['page']) ? $data['page'] : 1;
		$data['pagesize']   = isset($data['pagesize']) ? $data['pagesize'] : 20;
		$data['order'] 		= isset($data['order']) ? $data['order'] : "sort DESC";
		$query = new tQuery($data['table']);
		foreach ($data as $k => $v) {
			$query->$k = $v;
		}
		$list = $tmp_l = $t_arr = array();
		$list['list'] = $query->find();
//		//增加CIDR显示方式(如果数据库字段ipaddr只存CIDR格式)
//		$ip_str = '';
//		foreach ($list['list'] as $key=>$val) {
//			$t_arr = explode(";",$val['ipaddr']);
//			if (count($t_arr)>0) {
//				foreach ($t_arr as $k=>$v) {
//					if (!empty($v)) {
//						$tmp_l = $this->cidr2ip($v,false);
//						if (count($tmp_l)>0) {
//							$ip_str .= $tmp_l[0]."-".$tmp_l[1].";";
//						}
//					}
//				}
//			}
//			$list['list'][$key]['ipaddr_ex'] = $ip_str;
//			$ip_str = '';
//		}
		$list['pagebar'] = $query->get_pagebar($pageurl);
		$list['total'] = $query->total;
		$list['totalpage'] = $query->totalpage;
		return $list;
	}
	//域名下的自定义线路
	public function get_cust_list($type = 0) {
		global $uid;

		$where = ($type == 1)?"uid = '{$uid}'":"";
		$cust_line = array();
		$tmp_list = M("domain_acl_set")->query($where,"","",500);
		if (count($tmp_list)>0) {
			foreach ($tmp_list as $key=>$val) {
				$cust_line[$val['id']] = $val;
			}
			return $cust_line;
		}else{
			return array();
		}
	}

	public function write_romate($acl = '',$ns_group = "free",$delete = false){
		if (empty($acl)) return 0;
		$id = $acl;
		$acl = "cust".$acl;
		if($delete == false){
			$acl_row = $this->get_row("id='{$id}'");
			if (isset($acl_row['id']) && $acl_row['ipaddr'] && $acl_row['status'] == 1){
				$ips   = explode(";",$acl_row['ipaddr']);
				foreach($ips as $key=>$v){
					if(tValidate::is_ip($v)){
						$ips[$key] = $v."/32";
					}elseif (false !== ($pos = strpos($v, "-"))) {
						$tmp = explode("-",$v);
						$ips[$key] = tFun::ip2cidr($tmp[0],$tmp[1]);
					}				
				}
				$ips = implode("\n",$ips);
				$ret = tMongo::get_one("cust_acls", array("acl" => $acl));

				if (empty($ret)) {
					$ret = tMongo::add("cust_acls", array(
						"acl"      => $acl,
						"ns_group" => $ns_group,
						"ipdata"   => $ips,
					));
				}else{
					$ret = tMongo::update("cust_acls", array("acl"    => $acl),array(
						"acl"      => $acl,
						"ns_group" => $ns_group,
						"ipdata"   => $ips,
					));
				}
			}else{
				$delete = true;
			}
		}
		//如果删除为true
		if($delete == true){
			$this->del("id='{$id}'");
			$ret = tMongo::del("cust_acls", 
				array("acl" => $acl)
			);			
		}
		return $acl;
	}
}
?>