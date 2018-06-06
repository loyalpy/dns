<?php
class domain_record_model extends tModel{
	public function __construct(){
		parent::__construct("domain_record");
	}

	public function get_list($data = array(), $pageurl = "")
	{
		$list = Q("domain_record")->get_list($data,$pageurl);
		$list['ids'] = array();
		foreach($list['list'] as $v){
			$list['ids'][] = $v['record_id'];
		}
		//record relative  monitor
		if(isset($data['monitor']) && $data['monitor'] == 1){
			$monitor_list = array();
			$tmp = M("domain_monitor_record")->query("record_id IN('".implode("','",$list['ids'])."')");
			if($tmp){
				foreach($tmp as $key=>$v){
					$monitor_list[$v['record_id']] = $v;
				}
			}
			foreach($list['list'] as $k => $v){
				$list['list'][$k]['monitor'] = array();
				if(isset($monitor_list[$v['record_id']])){
					$list['list'][$k]['monitor'] = $monitor_list[$v['record_id']];
				}
			}
			unset($list['ids']);
		}
		return $list;
	}

	//A 记录与cname记录
	public function get_cname_count($domain_id, $data = array()){
		if ($domain_id) {
			$exeStr = "domain_id=$domain_id  AND RRvalue='{$data['RRvalue']}' AND RRname='{$data['RRname']}' AND RRtype='{$data['RRtype']}' AND acl='{$data['acl']}'  AND acltype='{$data['acltype']}'";
			$recordCount = M("domain_record")->get_one($exeStr, "count(*)");
			return $recordCount;
		} else {
			return 0;
		}
	}

	//获取域名所有记录
	public function fetch($domain = "",$fetch = true){
		$total = $this->get_one("domain='{$domain}'","COUNT('record_id')");
		$total_page = ceil($total/500);
		$return = $record = array();
		for($page = 1;$page<=$total_page;$page++){
			$c = array(
					"where" => "domain='{$domain}' AND status=1 AND inlock=0",
					"page"  => $page,
					"pagesize" => 500,
					"order" => "RRname ASC"
				);
			$tmp = Q("domain_record")->get_list($c);
			if(isset($tmp['list']) && $tmp['list']){
				$record = array_merge($record,$tmp['list']);
			}			
		}
		if($fetch){
			foreach($record as $v1){
				$v1['RRname'] = preg_replace('/\./i','#',$v1['RRname']);
				//RRname
				if(!isset($return[$v1['RRname']])){
					$return[$v1['RRname']] = array();
				}
				//RRtype
				if(!isset($return[$v1['RRname']][$v1['RRtype']])){
					$return[$v1['RRname']][$v1['RRtype']] = array();
				}
				//acl
				if(!isset($return[$v1['RRname']][$v1['RRtype']][$v1['acl']])){
					$return[$v1['RRname']][$v1['RRtype']][$v1['acl']] = array();
				}
				$return[$v1['RRname']][$v1['RRtype']][$v1['acl']][]  = array(
					"{$v1['RRvalue']}",
					"{$v1['RRttl']}",
					"{$v1['RRmx']}",
					"{$v1['acltype']}",
				);
			}
			return $return;
		}else{
			return $record;
		}
		
	}
	//设置域名别名绑定记录
	public function set_bind_domains($uid = 0,$domain_id = 0){
		//判断是否合法
		if ($uid == 0 || $domain_id == 0) {
			return 0;
		}
		//获取域名行
		$domain_row = M("domain")->get_row("domain_id={$domain_id} AND uid={$uid}");
		if (isset($domain_row['domain'])) {
			//获取域名绑定行
			$domain_bind_arr = M("domain_bind")->query("domain_id = '{$domain_id}' AND uid = '{$uid}'","domain_bind");
			$domain_bind_arr = array_map('array_shift',$domain_bind_arr);
			if (count($domain_bind_arr)) {
				//获取域名所有记录
				$total = $this->get_one("domain='{$domain_row['domain']}'","COUNT('record_id')");
				$total_page = ceil($total/500);
				$domain_records = array();
				for($page = 1;$page<=$total_page;$page++){
					$tmp = $this->query("domain='{$domain_row['domain']}'");
					$domain_records = array_merge($domain_records,$tmp);
				}
				//处理绑定域名
				foreach ($domain_bind_arr as $key_bind => $bind_domain) {
					if ($bind_domain) {
						//获取绑定域名的域名行
						if(tValidate::is_cn($bind_domain)){
							App::uselib("tools.idna_convert");
							$idna_convert_obj = new idna_convert();
							$bind_domain = $idna_convert_obj->encode($bind_domain);
							unset($idna_convert_obj);
						}
						$domain_bind_row_s = M("domain")->get_row("domain = '{$bind_domain}' AND uid={$uid}");
						if (isset($domain_bind_row_s['domain_id'])) {
							//添加前清空域名记录
							M("domain_record")->del("domain_id = '{$domain_bind_row_s['domain_id']}'");
							//更改域名行记录数
							M('domain')->set_data(array("records"=>0))->update("domain_id='{$domain_bind_row_s['domain_id']}'");
							//添加绑定域名记录
							if (count($domain_records)) {
								$record_nums = 0;
								foreach ($domain_records as $key_record => $record) {
									//设置项
									$data = array(
										"domain_id"=>$domain_bind_row_s['domain_id'],
										"domain"=>$bind_domain,
										"acl"=>$record['acl'],
										"acltype"=>$record['acltype'],
										"RRname"=>$record['RRname'],
										"RRtype"=>$record['RRtype'],
										"RRvalue"=>$record['RRvalue'],
										"RRmx"=>$record['RRmx'],
										"RRttl"=>$record['RRttl'],
										"bz"=>$record['bz'],
										"status"=>$record['status'],
										"inlock"=>$record['inlock'],
										"is_199cn"=>$record['is_199cn'],
									);
									$res = M("domain_record")->set_data($data)->add();
									if ($res) {
										//记录值递增+1
										M('domain')->set_data(array("records"=>$record_nums+1))->update("domain_id='{$domain_bind_row_s['domain_id']}'");
										//加入队列
										if($record['status'] == 1){
											M("@domain")->queue($bind_domain,"update_record");
										}
										$record_nums++;
									}
								}
							}
						}
					}
				}
			}
		}
		return 1;
	}
}
?>