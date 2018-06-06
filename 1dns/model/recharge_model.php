<?php
class recharge_model extends tModel{
	public function __construct(){
		parent::__construct("recharge");
	}
	public function get_list($data = array(), $pageurl = "")
	{
		$data['table'] = isset($data['table']) ? $data['table'] : "recharge";
		$data['where'] = isset($data['where']) ? $data['where'] : "1";
		$data['page'] = isset($data['page']) ? $data['page'] : 1;
		$data['pagesize'] = isset($data['pagesize']) ? $data['pagesize'] : 15;
		$data['order'] = isset($data['order']) ? $data['order'] : "id DESC";
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