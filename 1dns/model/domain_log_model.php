<?php
class domain_log_model extends tModel{
	public function __construct(){
		parent::__construct("domain_log");
	}
	public function get_list($data = array(), $pageurl = "")
	{
		$data['table'] 		= isset($data['table']) ? $data['table'] : "domain_log";
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
	//域名查询统计
//	public function querylog($host = "",$domain="",$start_dateline = 0,$end_dateline = 0){
//		$mongo_dbinfo = App::get_conf("db.mongo_log");
//		$count = 0;
//		if($domain){
//			$condition = array(
//				"dateline"=>array('$gte'=>$start_dateline,'$lte'=>$end_dateline)
//			);
//			$condition['domain']   = $domain;
//			if($host){
//				$condition['host'] = $host;
//			}
//			$tmp = tMongo::get("query_log",$condition,10000,
//					array("dateline"=>-1),
//					array("_id"=>0,"count"=>1),
//					0,false,$mongo_dbinfo);
//			if($tmp){
//				foreach($tmp as $key => $v){
//					$count += $v['count'];
//				}
//			}
//		}
//		return $count;
//	}
}
?>