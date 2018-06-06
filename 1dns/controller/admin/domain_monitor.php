<?php
class domain_monitor extends UCAdmin{

	//域名解析记录
	public function  monitor(){
		$do         = R("do","string");
		$page     = R("page","int");
		$page     = $page?$page:1;
		$pageurl = U("/domain_monitor/monitor?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"node"   				=> R("node","string"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND monitor_id ='{$v}' OR  monitor_port LIKE '%{$v}%' OR monitor_rate LIKE '%{$v}%' ";
					}else{
						$where .= $v?" AND (RRname LIKE '%{$v}%' OR  domain LIKE '%{$v}%')":"";
					}
					break;
				case "node":
//					$where .= $v?(" AND monitor_node_type = '{$v}'"):"";
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}
		//查询结束
		if($do == "get"){
			$data['page']  = $page;
			$data['where'] = $where;
			$data['pagesize'] = 20;
			$data['order'] = "dateline DESC";
			$result = M("@domain_monitor")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$nodeArr = array();
		$ns_group = M("@domain_ns_group")->get_cache_by_ns("monitor_free");
		foreach ($ns_group['servers'] as $k=>$v) {
			$nodeArr[] = array("key"=>$k+1,"v"=>$v['domain']);
		}
		$this->assign("nodeArr",$nodeArr);
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
	//修改域名监控
	public function monitor_edit(){
		$monitor_id = R("monitor_id","int");
		$res  = M("domain_monitor")->get_row("monitor_id='{$monitor_id}'");
		if (!isset($res['domain'])) {
			tAjax::json_error("域名不存在");
		}
		if (tUtil::check_hash()) {
			$monitor_port  = R("monitor_port","int");
			$monitor_rate  = R("monitor_rate","int");

			if (!tValidate::is_int($monitor_port)) {
				tAjax::json_error("监控端口只能为数字");
			}
			if (!tValidate::is_int($monitor_rate)) {
				tAjax::json_error("监控频率只能为数字");
			}

			$data = array(
				'monitor_port' => $monitor_port,
				'monitor_rate'  => $monitor_rate
			);
			M("domain_monitor")->set_data($data)->update("monitor_id='{$monitor_id}'");
			tAjax::json_success("保存成功");
		}else{
			tAjax::json($res);
		}
	}
	//删除域名监控
	public function monitor_del(){
		$monitor_id = R("monitor_id","int");
		$res  = M("domain_monitor")->get_row("monitor_id='{$monitor_id}'");
		if (!isset($res['domain'])) {
			tAjax::json_error("域名不存在");
		}
		$rst   = M("domain_monitor")->del("monitor_id={$monitor_id}");
		if ($rst) {
			//同时删除域名记录
			M("domain_monitor_record")->del("monitor_id={$monitor_id}");
			//同时删除报警信息
			M("domain_monitor_error")->del("monitor_id={$monitor_id}");
			tAjax::json_success("删除成功");
		} else {
			tAjax::json_error("删除失败");
		}
	}
	//域名解析记录
	public function  monitor_record(){
		$do         = R("do","string");
		$page     = R("page","int");
		$page     = $page?$page:1;
		$pageurl = U("/domain_monitor/monitor_record?do=get");
		//查询开始
		$condition = array(
			"keyword"			=> R("keyword","string"),
			"uname"				=> R("uname","string"),
		);
		$where = "1";
		foreach($condition as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND monitor_id ='{$v}' OR  monitor_port LIKE '%{$v}%' OR monitor_rate LIKE '%{$v}%'";
					}else{
						$where .= $v?" AND (RRname LIKE '%{$v}%' OR  domain LIKE '%{$v}%')":"";
					}
					break;
				case "uname":
					$where .= $v?(" AND domain = '{$v}'"):"";
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}
		//查询结束
		if($do == "get"){
			$data['page']  = $page;
			$data['where'] = $where;
			$data['pagesize'] = 20;
			$data['order'] = "dateline DESC";
			$result = M("@domain_monitor")->get_list($data,$pageurl);
			$list = array();
			foreach ($result['list'] as $key=>$val){
				if (isset($val['monitor_item'])) {
					foreach ($val['monitor_item'] as $k=>$v) {
						$list[] = array(
							'RRname' => $val['RRname'],
							'domain'  => $val['domain'],
							'monitor_port' => $val['monitor_port'],
							'monitor_rate'  => $val['monitor_rate'],
							'dateline'          => $val['dateline'],
							'status'             => $v['status'],
							'reason'            => $v['reason'],
							'ip'                   => $v['ip'],
							'acl'					 => $v['acl'],
							'status_code'    => $v['status_code']
						);
					}
				}
			}
			$result['list'] = $list;
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condition;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		
		$this->assign("condi",$condition);
		$this->assign("pageurl",$pageurl);
		$this->display();
	}
}
?>
