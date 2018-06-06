<?php
/**
 * 登录
* by Thinkhu 2014
*/
class DomainRecord extends API{
	public function __construct(){
		parent::__construct('Domain');
	}
	// 根据RRname获取记录列表
	public function GetAllByDomain(){
		$this->ChkUser();

		$domain 	= R("domain","string");
		$RRname 	= R("RRname","string","@");
		if (empty($domain)) {
			$this->respons_error("域名为空！");
		}
		if (empty($RRname)) {
			$this->respons_error("记录值为空！");
		}

		if(tValidate::is_cn($domain)){
			App::uselib("tools.idna_convert");
			$idna_convert_obj = new idna_convert();
			$domain = $idna_convert_obj->encode($domain);
		}

		$where   = "domain = '{$domain}' AND RRname='{$RRname}'";
		$where   .= " AND RRtype = 'A'";
		$data  = array(
			'where'     =>$where,
			'monitor' => 1,
		);
		$result = M("@domain_record")->get_list($data);
		$this->respons_success("ok",$result);

	}
	// 获取所有未监控记录
	public function GetAllNoMonitor(){
		global $uid;
		$this->ChkUser();
		
		$domain_sr 	= R("domain","string");
		$record 	= R("record","string","@");
		$islist       = R("islist","int");
		if (empty($domain_sr)) {
			$this->respons_error("域名为空！");
		}
		if (empty($record)) {
			$this->respons_error("记录值为空！");
		}
		if(tValidate::is_cn($domain_sr)){
			App::uselib("tools.idna_convert");
			$idna_convert_obj = new idna_convert();
			$domain = $idna_convert_obj->encode($domain_sr);
		}else{
			$domain = $domain_sr;
		}
		$sql  =    "SELECT RRname,count(RRname) AS total FROM @domain_record WHERE domain='{$domain}' GROUP BY RRname ";
		$tmp = Sq($sql);

		$sql = "SELECT b.RRname,count(b.RRname) AS total FROM @domain_monitor_record AS a LEFT JOIN @domain_monitor AS b ON a.monitor_id=b.monitor_id  WHERE b.domain='{$domain_sr}' GROUP BY  b.RRname";
		$tmp2 = Sq($sql);
		$tmp3 = array();
		if($tmp2){
			foreach($tmp2 as $v){
				$tmp3[$v['RRname']] = $v['total'];
			}
		}
		$recordArr = array();
		foreach ($tmp as $k=>$v) {
			$RRname =  $v['RRname'];
			if(isset($tmp3[$RRname])){
				if($v['total'] <= $tmp3[$RRname]){
						continue;
				}
			}
			$recordArr[] = $RRname.".".$domain_sr;
		}
		$this->respons_success("ok",array("list"=>$recordArr));
	}
	//记录列表
	public function GetListByDomain(){
		global $timestamp,$uid;
		$this->ChkUser();

		$page    	  = R("page","int");
		$domain     = R("domain","string");
		$pagesize   = R("pagesize","int");

		$pagesize = $pagesize?$pagesize:30;
		$pagesize = $pagesize>500?30:$pagesize;

		if(tValidate::is_cn($domain)){
			App::uselib("tools.idna_convert");
			$idna_convert_obj = new idna_convert();
			$domain = $idna_convert_obj->encode($domain);
		}

		//记录搜索
		$condition = array(
			"keyword"			 => R("keyword","string"),
			"RRname"   		     => R("RRname","string"),
			"RRvalue"		     => R("RRvalue","string"),
			"RRtype"				 => R("RRtype","string"),
			"status"				 => R("status","int"),
			"acl"						 => R("acl","string"),
		);
		$where   = "domain = '{$domain}'";

		foreach($condition as $k=>$v){
			switch($k){
				case "keyword":
					if ($v) {
						if(tValidate::is_int($v)){
							$where .= " AND (RRttl='{$v}' OR RRmx='{$v}' OR RRvalue LIKE '%{$v}%')";
						}else{
							$where .= $v?" AND (domain LIKE '%{$v}%' OR acl LIKE '%{$v}%' OR RRname LIKE '%{$v}%' OR RRtype LIKE '%{$v}%' OR RRvalue LIKE '%{$v}%')":"";
						}
					}
					break;
				case "RRname":
					$where .= $v?(" AND RRname = '{$v}'"):"";
					break;
				case "RRvalue":
					$where .= $v?(" AND RRvalue = '{$v}'"):"";
					break;
				case "RRtype":
					$where .= $v?(" AND RRtype = '{$v}'"):"";
					break;
				case "status":
					$where .= $v?(" AND status = ".($v-1)):"";
					break;
				case "acl":
					$where .= $v?(" AND acl like '%{$v}%'"):"";
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}

		$data  = array(
			'where'     =>$where,
			'page'		 =>$page,
			'pagesize' => $pagesize,
			'order'		 =>'RRname ASC',
			'monitor' => 1,
		);
		$result = M("@domain_record")->get_list($data,"__page__?");
		$result['pagebar'] = tFun::pagebar_js($result['pagebar'],"__page__?","get_records_list",array($condition['keyword'],$condition['RRname'],$condition['RRvalue'],$condition['RRtype'],$condition['status'],$condition['acl']));
		$this->respons_success("加载成功",$result);
	}
	//添加,修改记录
	public function EditByUid(){
		global $timestamp,$uid;
		$this->ChkUser();
        $domain_id 	= R("domain_id","int");
		$record_id    = R("record_id","int");
		$data = array(
			"acl"	 	        =>  R("acl","string"),
		   "acltype"		=>  R("acltype","string","DI"),
		   'RRname'		=>  R("RRname","string","@"),
		   'RRtype'		=>  R("RRtype","string"),
		   'RRvalue'		=>  R("RRvalue","string"),
		   'RRmx'			=>  R("RRmx","int"),
		   'RRttl'			=>  R("RRttl","int"),
		);
		$data['RRname'] = strtolower($data['RRname']);
		if (in_array($data['RRtype'],array("cname","ns","mx"))) {
			$data['RRvalue'] = strtolower($data['RRvalue']);
		}
		$data['status']  = R("status","int");
		if (strpos($data['acl'], "cust") !== false) {
			$data['acltype'] = "DT";
		}
		//域名合法性判断
		if($domain_id){
			$domain = M("domain")->get_row("domain_id={$domain_id} AND uid={$uid}");
		}
		if (!isset($domain['domain'])) {
			$this->respons_error(L("dns.2002"),array("tips"=>"RRname"));
		}
		//判断域名是否被绑定
		$domain_bind = $domain['is_cn'] == 1?$domain['domain_cn']:$domain['domain'];
		if (M("domain_bind")->get_one("uid = '{$uid}' AND domain_bind = '{$domain_bind}'","count(id)")) {
			$this->respons_error("该域名已被其它域名绑定，请解绑后再进行操作！",array("tips"=>"alert"));
		}
		//判断域名是否已锁定
		if ($domain['inlock'] == 1) {
			$this->respons_error("该域名处于锁定状态，请解锁后再进行操作！",array("tips"=>"alert"));
		}
		//判断域名解析是否处于锁定状态
		if(M("domain_record")->get_one("record_id = '{$record_id}' AND domain_id=$domain_id","inlock") == 1){
			$this->respons_error("域名解析处于锁定状态，无法修改",array('tips'=>"RRvalue"));
		}
		// 公共判断
		if (empty($data['RRvalue'])) {
			$this->respons_error("记录值不能为空",array('tips'=>"RRvalue"));
		}
		if (empty($data['RRname'])) {
			$this->respons_error("主机名不能为空",array('tips'=>"RRname"));
		}
		if (empty($data['acl'])) {
			$this->respons_error("线路不能为空",array('tips'=>"tselect-acl"));
		}
		if (empty($data['RRtype'])) {
			$this->respons_error("类型不能为空",array('tips'=>"tselect-type"));
		}
		//默认线路any判断
		if($data['acl'] !='any'){
			if(!M("domain_record")->get_one("domain_id={$domain['domain_id']} AND acl='any' AND RRname='{$data['RRname']}' AND RRtype IN('A','CNAME','AAAA','URLY','URLN')","count(*)")){
				$data['acl'] = 'any';
				$change_any = "域名{$data['RRname']}"."."."{$domain['domain']}没有默认线路,将添加为默认线路";
			}
		}
		if (!isset($domain['domain_id'])) {
			$this->respons_error("域名不存在",array('tips'=>"RRname"));
		}
		//服务器套餐
		$service_group = M("@domain_service")->get_cache($domain['service_group']);
		if (!isset($service_group['service_group'])) {
			$this->respons_error("域名套餐不存在",array('tips'=>"RRname"));
		}
        //线路判断
		if($data['acltype'] == 'DI') {//系统线路合法性判断
			if (strpos(";{$service_group['acls']};", ";{$data['acl']};") === false){
				//$this->respons_error("您未取得该线路权限");
				//$data['acl'] = "any"; //自定义线路控制
			}
		}else{
			if (!M("domain_acl_set")->get_one("id='".str_replace("cust","",$data['acl'])."'","count(id)")) {
				$this->respons_error("自定义线路不存在",array('tips'=>"acl"));
			}
		}
		//RRname 合法性判断
		if($data['RRtype'] == 'SRV'){
			if(!tValidate::is_srv_hostname($data['RRname'])){
				$this->respons_error("主机名非法",array('tips'=>"RRname"));
			}
		}else{
			if(!in_array($data['RRname'],array("@","*"))){
				//子级判断
				$tmp = explode(".",$data['RRname']);
				if(count($tmp)>$service_group['data']['subdomainLevel']['value']){
					$this->respons_error("子级数已超出套餐限制",array('tips'=>"RRname"));
				}
				if($tmp[0] == "*"){
					unset($tmp[0]);
					if(!tValidate::is_hostname(implode(".",$tmp))){
						$this->respons_error("主机名非法",array('tips'=>"RRname"));
					}
				}else{
					if(!tValidate::is_hostname($data['RRname'])){
						$this->respons_error("主机名非法",array('tips'=>"RRname"));
					}
				}
				unset($tmp);
			}
		}
		//重复性判断
		if(M("domain_record")->get_one("record_id <> '{$record_id}' AND domain_id=$domain_id  AND RRvalue='{$data['RRvalue']}' AND RRname='{$data['RRname']}' AND RRtype='{$data['RRtype']}' AND acl='{$data['acl']}'  AND acltype='{$data['acltype']}'","count(*)")){
			$this->respons_error("记录值已重复",array('tips'=>"RRvalue"));
		}
		//负载均衡判断
		if($service_group['data']['loadBalanc']['value'] <= M("domain_record")->get_one("record_id <> '{$record_id}' AND domain_id=$domain_id AND RRname='{$data['RRname']}' AND RRtype='{$data['RRtype']}' AND acl='{$data['acl']}' AND acltype='{$data['acltype']}'","count(*)")){
			$this->respons_error("负载均衡数已超出",array('tips'=>"RRname"));
		}
		//TTl判断
		if($data['RRttl'] < $service_group['data']['litTtl']['value']){
			$data['RRttl']  = $service_group['data']['litTtl']['value'];
		}
		switch($data['RRtype']){
			case "A":
				$data['RRmx'] = 0;
				//RRvalue合法性判断
				if(!tValidate::is_ip($data['RRvalue'])){
					$this->respons_error("A记录必须为IPv4",array('tips'=>"RRvalue"));
				}
               //记录冲突判断
				if(M("domain_record")->get_one("record_id <> '{$record_id}' AND domain_id=$domain_id AND RRname='{$data['RRname']}' AND RRtype IN('CNAME','URLY','URLN') AND acl='{$data['acl']}' AND acltype='{$data['acltype']}'","count(*)")>0){
					$this->respons_error("A记录与CNAME/转发记录冲突",array('tips'=>"RRvalue"));
				}
				break;
			case "AAAA":
				//记录冲突判断
				if(M("domain_record")->get_one("record_id <> '{$record_id}' AND domain_id=$domain_id AND RRname='{$data['RRname']}' AND RRtype IN('CNAME','URLY','URLN') AND acl='{$data['acl']}' AND acltype='{$data['acltype']}'","count(*)")>0){
					$this->respons_error("A记录与CNAME/转发记录冲突",array('tips'=>"RRvalue"));
				}
				break;
			case "URLY":
				//记录冲突判断
				if(M("domain_record")->get_one("record_id <> '{$record_id}' AND domain_id=$domain_id AND RRname='{$data['RRname']}' AND RRtype IN('A','AAAA','CNAME') AND acl='{$data['acl']}' AND acltype='{$data['acltype']}'","count(*)")>0){
					$this->respons_error("CNAME记录与A/转发记录冲突",array('tips'=>"RRvalue"));
				}
				//URL显性条数判断
				if($service_group['data']['urlType']['value'] <= M("domain_record")->get_one("record_id <> '{$record_id}' AND domain_id=$domain_id  AND RRtype IN('URLY','URLN')","count(*)")){
					$this->respons_error("URL转发条数超出,请查看套餐对应值",array('tips'=>"RRvalue"));
				}
				$data['RRmx'] = 0;
				if(!M("domain_record")->get_one("record_id = '{$record_id}' AND domain_id=$domain_id AND RRname='{$data['RRname']}' AND RRvalue='{$data['RRvalue']}' AND RRtype = '{$data['RRtype']}' AND acl='{$data['acl']}' AND acltype='{$data['acltype']}'","count(*)")){
					$data['inlock'] = 1;  //加入审核:如果是新加，或者更改，锁定。
				}
			case "URLN":
				//记录冲突判断
				if(M("domain_record")->get_one("record_id <> '{$record_id}' AND domain_id=$domain_id AND RRname='{$data['RRname']}' AND RRtype IN('A','AAAA','CNAME') AND acl='{$data['acl']}' AND acltype='{$data['acltype']}'","count(*)")>0){
					$this->respons_error("CNAME记录与A/转发记录冲突",array('tips'=>"RRvalue"));
				}
				//URL显性条数判断
				if($service_group['data']['urlType']['value'] <= M("domain_record")->get_one("record_id <> '{$record_id}' AND domain_id=$domain_id  AND RRtype IN('URLY','URLN')","count(*)")){
					$this->respons_error("URL转发条数超出,请查看套餐对应值",array('tips'=>"RRvalue"));
				}
				$data['RRmx'] = 0;
				if(!M("domain_record")->get_one("record_id = '{$record_id}' AND domain_id=$domain_id AND RRname='{$data['RRname']}' AND RRvalue='{$data['RRvalue']}' AND RRtype = '{$data['RRtype']}' AND acl='{$data['acl']}' AND acltype='{$data['acltype']}'","count(*)")){
					$data['inlock'] = 1;  //加入审核:如果是新加，或者更改，锁定。
				}
				break;
			case "MX":
				$data['RRmx'] = $data['RRmx']>0?$data['RRmx']:5;
				break;
			case "TXT":
				$data['RRmx'] = 0;
				break;
			case "SRV":
				$data['RRmx'] = 0;
				break;
			case "NS":
				break;
			case "CNAME":
				//记录冲突判断
				if(M("domain_record")->get_one("record_id <> '{$record_id}' AND domain_id=$domain_id AND RRname='{$data['RRname']}' AND RRtype IN('A','AAAA','URLY','URLN') AND acl='{$data['acl']}' AND acltype='{$data['acltype']}'","count(*)")>0){
					$this->respons_error("CNAME记录与A/转发记录冲突",array('tips'=>"RRvalue"));
				}
				break;
			default:
				$this->respons_error("添加记录类型失败",array('tips'=>"tselect-type"));
				break;
		}
		$acl_name = C("category","domain_acl")->json(0,'ident');
		$data_config = tCache::read("data_config");
		if($record_id  == 0){			//添加
			$data['domain_id'] = $domain['domain_id'];
			$data['domain']     = $domain['domain'];
			$data['status']     = 1;
			$res = M('domain_record')->set_data($data)->add();
 			$record_id =  $res;
			//记录值递增+1
			M('domain')->set_data(array("records"=>$domain['records']+1,"lastupdate"=>$timestamp))->update("domain_id='{$domain_id}'");
			//添加域名记录日志
			M("@domain")->log("添加新记录","记录名称：{$data['RRname']},记录类型：{$data_config['RRtype'][$data['RRtype']]},记录值：{$data['RRvalue']},线路：{$acl_name[$data['acl']]['name']}",array('domain_id'=>$domain['domain_id'],'domain'=>$domain['domain']));

			$new_data = M("domain_record")->get_row("record_id='{$record_id}'");
			$new_data['monitor'] = array();
		}else{							   //修改
			//默认线路判断
			if($data['acl'] != 'any' && !M("domain_record")->get_one("record_id <> '{$record_id}' AND domain_id=$domain_id AND RRname='{$data['RRname']}'  AND acl='any' AND RRtype='{$data['RRtype']}'","count(*)")){
				$this->respons_error("更改后无默认线路，不能修改",array('tips'=>"tselect-acl"));
			}
			$res = M('domain_record')->set_data($data)->update("record_id='{$record_id}'");

			$new_data = M("domain_record")->get_row("record_id='{$record_id}'");
			$new_data['monitor'] = array();

			//修改域名记录日志
			if($res){
				//更改域名最近操作时间为最新时间
				M('domain')->set_data(array("lastupdate"=>$timestamp))->update("domain_id='{$domain_id}'");

				//根据频率执行域名监控
				$domain_monitor 					= $domain['is_cn'] == 1?$domain['domain_cn']:$domain['domain'];
				$monitor_row       					= M("domain_monitor")->get_row("domain='{$domain_monitor}' AND uid='{$uid}'");
				$new_data['monitor_rate']      = isset($monitor_row['domain'])?$monitor_row['monitor_rate']:0;

				//更新记录后的更新监控
				$new_data['monitor'] = M("@domain_monitor")->update_new($record_id);

				M("@domain")->log("修改记录","记录名称：{$data['RRname']},记录类型：{$data_config['RRtype'][$data['RRtype']]},记录值：{$data['RRvalue']},线路：{$acl_name[$data['acl']]['name']}",array('domain_id'=>$domain['domain_id'],'domain'=>$domain['domain']));
			}
		}
		if($res){
			if($data['status'] == 1){
				M("@domain")->queue($domain['domain'],"update_record",0);
			}
		}
		if(isset($change_any)){
			$new_data['bz'] = $change_any;
		}
		//设置域名别名绑定记录
		M("@domain_record")->set_bind_domains($uid,$domain_id);
		$this->respons_success("保存记录成功",$new_data);
	}
	//删除记录
	public function Del(){
		global $timestamp,$uid;
		$this->ChkUser();

		$record_id = R("record_id","string");
		//记录值数量减一
		if($record_id){
			$record = M("domain_record")->get_row("record_id ={$record_id}");
			$domain = M("domain")->get_row("domain_id={$record['domain_id']} AND uid={$uid}");
		}
		if (!isset($domain['domain'])) {
			$this->respons_error(L("dns.2002"));
		}
		//判断域名是否被绑定
		$domain_bind = $domain['is_cn'] == 1?$domain['domain_cn']:$domain['domain'];
		if (M("domain_bind")->get_one("uid = '{$uid}' AND domain_bind = '{$domain_bind}'","count(id)")) {
			$this->respons_error("该域名已被其它域名绑定，请解绑后再进行操作！",array("tips"=>"RRname"));
		}
		//判断域名是否已锁定
		if ($domain['inlock'] == 1) {
			$this->respons_error("该域名处于锁定状态，请解锁后再进行操作！",array("tips"=>"RRname"));
		}
		$rst = M("domain_record")->del("record_id ={$record_id}");
		$acl_name = C("category","domain_acl")->json(0,'ident');
		$data_config = tCache::read("data_config");
		if ($rst) {
			//写入队列
			M("@domain")->queue($domain['domain'],"update_record");
			//更改域名最近操作时间为最新时间
			M('domain')->set_data(array("records"=>$domain['records']-$rst,"lastupdate"=>$timestamp))->update("domain_id='{$record['domain_id']}'");
			//删除域名记录日志
			M("@domain")->log("删除记录","记录名称：{$record['RRname']},记录类型：{$data_config['RRtype'][$record['RRtype']]},记录值：{$record['RRvalue']},线路：{$acl_name[$record['acl']]['name']}",array('domain_id'=>$domain['domain_id'],'domain'=>$domain['domain']));
			//根据频率执行域名监控
			$domain_monitor = $domain['is_cn'] == 1?$domain['domain_cn']:$domain['domain'];
			$monitor_row       = M("domain_monitor")->get_row("domain='{$domain_monitor}' AND uid='{$uid}'");
			$monitor_rate       = isset($monitor_row['domain'])?$monitor_row['monitor_rate']:0;
			//更新记录后的更新监控
			M("@domain_monitor")->update_new($record_id);
			//设置域名别名绑定记录
			M("@domain_record")->set_bind_domains($uid,$record['domain_id']);
			$this->respons_success("成功删除{$rst}条记录",$monitor_rate);
		} else {
			$this->respons_error(L("com.2003"));
		}
	}
	//暂停启用
	public function Status(){
		global $timestamp,$uid;
		$this->ChkUser();

		$record_id = R("record_id","string");
		$status       = R("status", "int");
		$record = M("domain_record")->get_row("record_id ={$record_id}");
		$domain = M("domain")->get_row("domain_id={$record['domain_id']} AND uid={$uid}");
		if (!isset($domain['domain'])) {
			$this->respons_error(L("dns.2002"));
		}
		//判断域名是否被绑定
		$domain_bind = $domain['is_cn'] == 1?$domain['domain_cn']:$domain['domain'];
		if (M("domain_bind")->get_one("uid = '{$uid}' AND domain_bind = '{$domain_bind}'","count(id)")) {
			$this->respons_error("该域名已被其它域名绑定，请解绑后再进行操作！",array("tips"=>"RRname"));
		}
		//判断域名是否已锁定
		if ($domain['inlock'] == 1) {
			$this->respons_error("该域名处于锁定状态，请解锁后再进行操作！",array("tips"=>"RRname"));
		}
		if(empty($record_id)){
			$this->respons_error("非法操作");
		}
		if(!in_array($status,array(0,1))){
			$this->respons_error("非法操作");
		}
		$num = M("domain_record")->set_data(array("status"=>$status))->update("record_id IN({$record_id}) AND status = ".($status ==1?0:1));

		if ($num == 0){
			$this->respons_error("操作失败");
		}else{
			//加入队列
			M("@domain")->queue($record['domain'],"update_record");
			//设置域名别名绑定记录
			M("@domain_record")->set_bind_domains($uid,$record['domain_id']);
			$this->respons_success("成功".($status == 1?"启用":"暂停")."记录");
		}
	}
	//记录扫描
	public function ScanImport(){
		set_time_limit(0);
		$domain      = R("domain","string");		
		$acl         = "any";
		$query_types = array("CNAME","MX","A");
		$result      = array();

		$scan_host    = array("","www");//,"bbs","mail","admin","oa","img");
		if(isset(App::$data['data_config']["scan_host"]) && is_array(App::$data['data_config']["scan_host"])){
            $scan_host = array_merge($scan_host,App::$data['data_config']["scan_host"]);
        }
		//先找泛解析 "CNAME" "A"
        $result[md5("*.A.{$acl}")] = $result[md5("*.CNAME.{$acl}")] = array();
        //找找泛解析A看
        $ret = DNSapi::a((md5(rand(1,999)).".".$domain));
		if(is_array($ret)){
	        foreach($ret as $v){
	        	$result[md5("*.A.{$acl}")][] = array(
	        		"name"   => "*",
	        		"type"   => "A",
	        		"acl"    =>  $acl,
	        		"val"    =>  $v,
	        		"ttl"    =>  600
	        	);
	        }
	     }
	     //找找泛解析CNAME看
	     /*
        $ret = DNSapi::cname((md5(rand(1,999)).".".$domain));
		if(is_array($ret)){
	        foreach($ret as $v){
	        	$result[md5("*.CNAME.{$acl}")][] = array(
	        		"name"   => "*",
	        		"type"   => "CNAME",
	        		"acl"    => $acl,
	        		"val"    => $v,
	        		"ttl"    => 600
	        	);
	        }
	     }
	     */
        //正式扫描
	    foreach($scan_host as $host_name){
		    $name = $host_name?$host_name:"@";
		    $question = $host_name?"{$host_name}.{$domain}":$domain;
		    foreach($query_types as $type){
		        if($type == "A" && isset($findresult[md5("{$name}.CNAME.{$acl}")])){
		            continue;
		        }	                
		        $ret = DNSapi::query($question,$type);
		        if(is_array($ret)){
			        foreach($ret as $v){
			        	$result[md5("{$name}.{$type}.{$acl}")][] = array(
			        		"name"   => $name,
			        		"type"   => $type,
			        		"acl"    => $acl,
			        		"val"    => $v,
			        		"ttl"    => 600
			        	);
			        }
			     }
		    }
		}
        //
		$result = array_filter($result);

		$return = array();
		foreach($result as $key => $v){
			foreach($v as $v1){
				$return[] = $v1;
			}
		}
		$this->respons_success("OK",$return);
	}
}
?>