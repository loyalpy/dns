<?php
/**
 * 已删除域名
 * Created by PhpStorm.
 * User: py
 * Date: 2015/12/22
 * Time: 15:12
 */
class domain_del_model extends tModel{
	public function __construct(){
		parent::__construct("domain_del");
	}

	public function get_list($data = array(), $pageurl = "")
	{
		$data['table'] = isset($data['table']) ? $data['table'] : "domain_del";
		$data['where'] = isset($data['where']) ? $data['where'] : "1";
		$data['page'] = isset($data['page']) ? $data['page'] : 1;
		$data['pagesize'] = isset($data['pagesize']) ? $data['pagesize'] : 15;
		$data['order'] = isset($data['order']) ? $data['order'] : "dateline DESC";
		$query = new tQuery($data['table']);
		foreach ($data as $k => $v) {
			$query->$k = $v;
		}
		$list['list'] = $query->find();
		$list['pagebar'] = $query->get_pagebar($pageurl);
		$list['total'] = $query->total;
		$list['totalpage'] = $query->totalpage;
		return $list;
	}
}
?>