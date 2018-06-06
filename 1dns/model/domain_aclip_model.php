<?php
/**
 * aclip 库
 * Created by PhpStorm.
 */
class domain_aclip_model extends tModel{
	public function __construct(){
		parent::__construct("domain_aclip");
	}

	public function get_list($data = array(), $pageurl = ""){
		$data['table']    = isset($data['table']) ? $data['table'] : "domain_aclip";
		$data['where']    = isset($data['where']) ? $data['where'] : "1";
		$data['page']     = isset($data['page']) ? $data['page'] : 1;
		$data['pagesize'] = isset($data['pagesize']) ? $data['pagesize'] : 15;
		$data['order'] = isset($data['order']) ? $data['order'] : "addr DESC";
		$query = new tQuery($data['table']);
		foreach ($data as $k => $v) {
			$query->$k = $v;
		}
		$list = $query->find();
		$list['pagebar']   = $query->get_pagebar($pageurl);
		$list['total']     = $query->total;
		$list['totalpage'] = $query->totalpage;
		return $list;
	}

	public function write_romate_ipv4($acl = '')
	{
		if (empty($acl)) return 0;
		$acl_row = M("domain_acl")->get_row("ident='{$acl}'");
		if (isset($acl_row['id'])) {
			$aclid = $acl_row['id'];
			$acl = $acl_row['ident'];
			$status = $acl_row['status'];
			//ipv4
			$sql = "SELECT aclid,group_concat(DISTINCT addr ORDER BY addr ASC SEPARATOR '\n') AS ipdata FROM `@domain_aclip` WHERE aclid='{$aclid}' AND iptype=0 GROUP BY aclid";
			Sq("SET group_concat_max_len = 3088000");

			$res = Sq($sql);
			$ips = "";
			if (isset($res[0]['ipdata'])) {
				$ips = $res[0]['ipdata'];
			}
			$ret = tMongo::get_one("acls", array("acl" => $acl, "iptype" => 0));
			if (empty($ret)) {
				$ret = tMongo::add("acls", array(
					"acl"    => $acl,
					"ipdata" => $ips,
					"status" => $status,
					"iptype" => 0
				));
			} else {
				$ret = tMongo::update("acls", array("acl" => $acl, "iptype" => 0), array(
					"acl"    => $acl,
					"ipdata" => $ips,
					"status" => $status,
					"iptype" => 0
				));
			}
			return $ret;
		}
		return 0;
	}
}
?>