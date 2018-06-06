<?php
class domain_monitor_model extends tModel{
	public function __construct(){
		parent::__construct("domain_monitor");
	}
	public function get_item($monitor_id = ""){
		$return = array();
		if($monitor_id){
			$return = M("domain_monitor_record")->query("monitor_id='{$monitor_id}'","*","monitor_record_id desc");
		}
		return $return;
	}
	public function get_list($data = array(), $pageurl = "")
	{
		$data['table'] = isset($data['table']) ? $data['table'] : "domain_monitor";
		$data['where'] = isset($data['where']) ? $data['where'] : "1";
		$data['page'] = isset($data['page']) ? $data['page'] : 1;
		$data['pagesize'] = isset($data['pagesize']) ? $data['pagesize'] : 15;
		$data['order'] = isset($data['order']) ? $data['order'] : "monitor_id DESC";
		$query = new tQuery($data['table']);
		foreach ($data as $k => $v) {
			$query->$k = $v;
		}
		$list['list'] = $query->find();
		foreach($list['list'] as $key =>$v){
			$list['list'][$key]['monitor_item'] = $this->get_item($v['monitor_id']);
		}
		$list['pagebar'] = $query->get_pagebar($pageurl);
		$list['total'] = $query->total;
		$list['totalpage'] = $query->totalpage;
		return $list;
	}
	//更新监控列表
	public function update_queue($rate){
		if(!in_array($rate,array(1,3,6,10))){
			return false;
		}
		$where    = "b.monitor_rate={$rate} AND b.status=0";
		$pagesize = 500;
		$total    = M("domain_monitor_record AS a LEFT JOIN @domain_monitor AS b ON a.monitor_id=b.monitor_id")->get_one($where,"count(record_id)");
		$totalpage = ceil($total/$pagesize);

		$result = array();
		for($page = 1;$page<=$totalpage;$page++){
			$fields = "a.monitor_id,a.record_id,a.ip,
					   b.RRname,b.domain,b.monitor_type,b.monitor_http,b.monitor_port,b.monitor_path";

			$sql    = "SELECT {$fields} FROM @domain_monitor_record AS a 
					   LEFT JOIN @domain_monitor AS b ON a.monitor_id=b.monitor_id 
					   WHERE {$where} 
					   LIMIT ".(($page-1)*$pagesize).",{$pagesize}";
			$res = Sq($sql);
			if($res){
				foreach($res as $v){
					$result[] = implode("|",$v);
				}
			}
		}
		file_put_contents(ROOT_PATH."cache/static/monitor_queue_{$rate}.data",implode("\n",$result));
	}
	//记录变动更新监控信息
	public function update_new($record_id){
		$monitor_row = M("domain_monitor_record AS a LEFT JOIN @domain_monitor AS b ON a.monitor_id = b.monitor_id")->get_row("a.record_id='{$record_id}'","a.*,b.RRname,b.domain,b.records");
		if(isset($monitor_row['monitor_record_id'])){
			$record = M("domain_record")->get_row("record_id ={$record_id}");
			if(!isset($record['record_id'])){
				M("domain_monitor_record")->del("record_id='{$record_id}'");
				if(M("domain_monitor_record")->get_one("monitor_id='{$monitor_row['monitor_id']}'","count(*)") == 0){
					M("domain_monitor")->del("monitor_id='{$monitor_row['monitor_id']}'");
				}
			}else{
				//主机名未改
				if($monitor_row['RRname'] == $record['RRname']){
						$updata = array();
						if($monitor_row['ip']  != $record['RRvalue']){
							$updata['ip'] = $record['RRvalue'];
						}
						if($monitor_row['acl'] != $record['acl']){
							$updata['acl'] = $record['acl'];
						}
						if($updata){
							M("domain_monitor_record")->set_data($updata)->update("record_id='{$record['record_id']}'");
						}
				}else{
					M("domain_monitor_record")->del("record_id='{$record_id}'");
					if(M("domain_monitor_record")->get_one("monitor_id='{$monitor_row['monitor_id']}'","count(*)") == 0){
						M("domain_monitor")->del("monitor_id='{$monitor_row['monitor_id']}'");
					}
				}
			}
		}
		return $monitor_row;
	}

	// 获取监控日志
	public function querylog($record_id,$node_id){
		$mongo_dbinfo = App::get_conf("db.mongo_log");
		$result = array();
		if($node_id && $record_id){
			$tmp = tMongo::get("monitor_log",array("record_id"=>$record_id,"node_id"=>$node_id),60,
				array("dateline"=>-1),
				array("_id"=>0),
				0,false,$mongo_dbinfo);
			if($tmp){
				foreach($tmp as $v){
					$v['dateline'] = tTime::get_datetime("H:i",$v['dateline']);
					$result[] = $v;
				}
			}
		}
		$result = array_reverse($result);
		return $result;
	}
}
?>