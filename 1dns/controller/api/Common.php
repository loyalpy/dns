<?php
class Common extends tController{
	private $_key = "a3352d339e245e44c684da4bb94cdc5b";
	//监控回调用
	public function MonitorCallBack(){
		global $timestamp;
		$mongo_dbinfo = App::get_conf("db.mongo_log");
	
		$record_id = R("id","int");
        $node_id   = R("node_id","int");
        #获取检查结果
        $status      = R("status","int");
        $status_code = R("status_code","int");
        $reason      = R("reason","string");
        $restime     = R("restime","int");
        $status      = $status < 0?0:$status;
        if($status == 0){
        	$reason = 'Request time out';
        }
        $monitor_row = M("domain_monitor_record AS a LEFT JOIN @domain_monitor AS b ON a.monitor_id = b.monitor_id")->get_row("a.record_id='{$record_id}'","a.*,b.RRname,b.domain,b.records,b.uid,b.monitor_switch,b.monitor_rate,b.notify_email,b.notify_mobile,b.notify_weixin,b.notify_otheremail,b.notify_othermobile");

        if(!isset($monitor_row['monitor_id'])){
        	die("no monitor_row");
        }
        //添加日志
        tMongo::add("monitor_log",array("record_id"   => $record_id,
					"node_id"     => $node_id,
					"status"      => $status,
					"status_code" => $status_code,
					"reason"      => $reason,
					"restime"     => $restime,
					"dateline"    => $timestamp,
			),array(),$mongo_dbinfo);
        /*********************************************************************************/
        //状态码不一样
        $updata_monitor_record = array();
        //判断出错与恢复
        $error_num = 0;
        $basewhere="monitor_id='{$monitor_row['monitor_id']}' AND record_id='{$monitor_row['record_id']}'";
        $lasterror_row = M("domain_monitor_error")->get_row("{$basewhere} AND enddateline=0 AND monitor_node_id='{$node_id}'");

        if($status == 0){//单节点宕机
	        if(!isset($lasterror_row['id'])){
	        	M("domain_monitor_error")->set_data(array(
                                        "uid"          => $monitor_row['uid'],
                                        "monitor_id"   => $monitor_row['monitor_id'],
                                        "record_id"    => $monitor_row['record_id'],
                                        "RRname"       => $monitor_row['RRname'],
                                        "domain"       => $monitor_row['domain'],
                                        "acl"          => $monitor_row['acl'],
                                        "ip"           => $monitor_row['ip'],
                                        "status_code"  => $status_code,
                                        "reason"       => $reason,
                                        "startdateline"=> $timestamp,
                                        "enddateline"  => 0,
                                        "monitor_node_id" => $node_id,
                                ))->add();
	        }
	        $error_num = M("domain_monitor_error")->get_one("{$basewhere} AND enddateline=0","count(id)");
        }elseif($status == 1){//单节点恢复
        	if(isset($lasterror_row['id'])){
        		M("domain_monitor_error")->set_data(array(
        			"enddateline" => $timestamp
        			))->update("id='{$lasterror_row['id']}'");
        	}
        }
        //宕机状态变更
        if($monitor_row['status'] == 1 && $status == 0 && $error_num >= 3){
        	$updata_monitor_record['status'] = 0;
        	$updata_monitor_record["status_code"] = $status_code;
        	$updata_monitor_record["reason"]      = $reason;
        }elseif($monitor_row['status'] == 0 && $status == 1){
        	$updata_monitor_record['status'] = 1;
        	$updata_monitor_record["status_code"] = $status_code;
        	$updata_monitor_record["reason"]      = $reason;
        }

        $ret = 0;
		if($updata_monitor_record){
        	$ret = M("domain_monitor_record")
	        	->set_data($updata_monitor_record)
	        	->update("monitor_record_id='{$monitor_row['monitor_record_id']}'");
        }

        if($ret && $monitor_row['status'] == 1 && $status == 0 && $error_num >= 3){
			/******************************************************宕机后处理*************************************/
			//切换规则
			$switch_ip = '';
			$switch_data = array();
			switch($monitor_row['monitor_switch']){
				case 1: //不对域名记录做任何修改 (不切换)
					break;
				case 2: //智能切换
					$tmp = M("domain_monitor_record")->query("monitor_id = '{$monitor_row['monitor_id']}' AND status = 1 AND ip <> '{$monitor_row['ip']}'","ip","acl DESC");
					if(!empty($tmp) && count($tmp)>0){
						$switch_ip = $tmp[0]['ip'];
					}else{
						$tmp_record = M("domain_record")->query("RRname='{$monitor_row['RRname']}' AND domain = '{$monitor_row['domain']}' AND RRtype = 'A' AND RRvalue <> '{$monitor_row['ip']}'","RRvalue","acl DESC");
						if(!empty($tmp_record) && count($tmp_record)>0){
							$switch_ip = $tmp_record[0]['RRvalue'];
						}
					}
					if ($switch_ip) {
						$switch_data = array("RRvalue"=>$switch_ip);
					}
					break;
				case 3: //只暂停
					$switch_data = array("status"=>0);
					break;
				case 4: //切换到您备用的IP (自定义切换)
					if ($monitor_row['ip2'] && tValidate::is_ip($monitor_row['ip2']) && $monitor_row['ip'] != $monitor_row['ip2']) {
						$switch_data = array("RRvalue"=>$monitor_row['ip2']);
					}
					break;
				default:
					break;
			}
			if($switch_data){
				M("domain_record")->set_data($switch_data)->update("record_id='{$monitor_row['record_id']}'");
				M("@domain")->queue($monitor_row['domain'],"update_record");
			}

			//宕机通知:邮箱
			if ($monitor_row['notify_email'] == 1) {
				C("user")->send_mail(array("type"=>"downtime","server"=>$monitor_row['ip'],"time"=>$timestamp,'domain'=>$monitor_row['domain']),$monitor_row['uid']);
				//如果其它邮件存在
				if (!empty($monitor_row['notify_otheremail'])) {
					$tmp_o_email = explode(";",$monitor_row['notify_otheremail']);
					foreach ($tmp_o_email as $key=>$val) {
						C("user")->send_mail(array("type"=>"downtime","server"=>$monitor_row['ip'],"time"=>$timestamp,'domain'=>$monitor_row['domain']),$monitor_row['uid'],$val);
					}
				}
			}
			//宕机通知:手机
			if ($monitor_row['notify_mobile'] == 1) {
				C("user")->send_sms(array('type'=>"downtime","server"=>$monitor_row['ip']."({$monitor_row['domain']})","time"=>$timestamp),"",$monitor_row['uid']);
				//如果其它手机存在
				if (!empty($monitor_row['notify_othermobile'])) {
					$tmp_o_mobile = explode(";",$monitor_row['notify_othermobile']);
					foreach ($tmp_o_mobile as $key=>$val) {				
						C("user")->send_sms(array('type'=>"downtime","server"=>$monitor_row['ip']."({$monitor_row['domain']})","time"=>$timestamp),"{$val}",$monitor_row['uid']);
					}
				}
			}
			//宕机通知:微信
			if ($monitor_row['notify_weixin'] == 1) {
				C("user")->send_wx(array("type"=>"downtime","server"=>$monitor_row['ip'],"time"=>$timestamp,'domain'=>$monitor_row['domain'],"reason"=>$reason),$monitor_row['uid']);
			}
			/******************************************************宕机后处理结束*************************************/
        }elseif($ret &&$monitor_row['status'] == 0 && $status == 1){//恢复后处理
			/******************************************************恢复后处理******************************************/
			//切换规则，改回原IP
			$old_switch_data = array();
			$record_row = M("domain_record")->get_row("record_id='{$monitor_row['record_id']}'");
			if (isset($record_row['RRvalue']) && $record_row['RRvalue'] != $monitor_row['ip']) {
				$old_switch_data['RRvalue'] = $monitor_row['ip'];
				M("domain_record")->set_data($old_switch_data)->update("record_id='{$monitor_row['record_id']}'");
				M("@domain")->queue($monitor_row['domain'],"update_record");
			}

			//宕机恢复通知:邮箱
			if ($monitor_row['notify_email'] == 1) {
				C("user")->send_mail(array("type"=>"renew","server"=>$monitor_row['ip'],'domain'=>$monitor_row['domain'],'time'=>$timestamp),$monitor_row['uid']);
				//如果其它邮件存在
				if (!empty($monitor_row['notify_otheremail'])) {
					$tmp_o_email = explode(";",$monitor_row['notify_otheremail']);
					foreach ($tmp_o_email as $key=>$val) {
						C("user")->send_mail(array("type"=>"renew","server"=>$monitor_row['ip'],'domain'=>$monitor_row['domain'],'time'=>$timestamp),$monitor_row['uid'],$val);
					}
				}
			}
			//宕机恢复通知:手机
			if ($monitor_row['notify_mobile'] == 1) {
				C("user")->send_sms(array('type'=>"renew","server"=>$monitor_row['ip']."({$monitor_row['domain']})","time"=>$timestamp),"",$monitor_row['uid']);
				//如果其它手机存在
				if (!empty($monitor_row['notify_othermobile'])) {
					$tmp_o_mobile = explode(";",$monitor_row['notify_othermobile']);
					foreach ($tmp_o_mobile as $key=>$val) {				
						C("user")->send_sms(array('type'=>"renew","server"=>$monitor_row['ip']."({$monitor_row['domain']})","time"=>$timestamp),"{$val}",$monitor_row['uid']);
					}
				}
			}
			//宕机恢复通知:微信
			if ($monitor_row['notify_weixin'] == 1) {
				C("user")->send_wx(array("type"=>"renew","server"=>$monitor_row['ip'],'domain'=>$monitor_row['domain'],'time'=>$timestamp,'continue_time'=>($monitor_row['monitor_rate']."分钟")),$monitor_row['uid']);
			}
			/******************************************************恢复后处理结束******************************************/
        }

        //删除1天以前的日志
        if($timestamp %1000 == 0){
        	tMongo::del("monitor_log",array("dateline"=>array('$lte'=>($timestamp-86400))),$mongo_dbinfo);
        }
	}
	//获取转发URL信息
	public function GetURLYN(){
		$checkcode = R("checkcode","string");
		$data = array(
			"domain" => R("domain","string"),
			"host"   => R("host","string"),
		);
    	if($this->_rz($data,$checkcode)){
    		$data['host'] = $data['host']?$data['host']:"@";

    		$where = "RRname='{$data['host']}' AND domain='{$data['domain']}' AND RRtype IN('URLY','URLN') AND status=1";
    		$return = M("domain_record")->get_row($where,"RRtype,RRvalue");
    		if(empty($return)){
    			$where = "RRname LIKE '*%' AND domain='{$data['domain']}' AND RRtype IN('URLY','URLN') AND status=1";
    			$return = M("domain_record")->get_row($where,"RRtype,RRvalue");
    		}
    		if(isset($return['RRtype']) && tValidate::is_request_url($return['RRvalue'])){
    			$return['error'] = 0;
    			tAjax::json($return);
    		}else{
    			tAjax::json_error("未审核");
    		}
    	}
    	tAjax::json_error("非法请求");
	}
	//获取可支持的域名类型
	public function GetSupportDomain(){
		$arr = tValidate::support_domain();
		tAjax::json($arr);
	}
	//检查DNS
	public function CheckNS(){
		//验证
		$ret =  tUtil::js("api@/Common/CheckNS","de");
		if($ret == 1){
			set_time_limit(7200);
			$domains = R("domains","string");
			$domain_arr = explode(",",$domains);
			foreach($domain_arr as $domain){
				M("@domain")->check_ns($domain);
			}
		}
		exit();
	}
	//检查DNS
	public function CheckExpiry(){
		//验证
		$ret =  tUtil::js("api@/Common/CheckExpiry","de");
		if($ret == 1){
			set_time_limit(7200);
			$domains = R("domains","string");
			$domain_arr = explode(",",$domains);
			foreach($domain_arr as $domain){
				M("@domain")->check_expiry($domain);
			}
		}
		exit();
	}
	//增加域名牵引回调
	public function DNSqy(){
		global $timestamp;
		$checkcode = R("checkcode","string");
		$domain    = R("domain","string");
		$data = array(
			"domain"     => $domain,
			"dateline"   => R("dateline","int"),
			"total0"     => R("total0","int"),
			"total1"     => R("total1","int"),
		);
    	if($this->_rz($data,$checkcode)){
    		if(empty($domain)){
    			die("no domain");
    		}
    		//锁定域名操作
    		$ret0   = M("domain")->set_data(array("indel"=>1))->update("domain='{$domain}'");
    		$expiry = $timestamp + 6*3600; //十分钟自动解封
            if($ret0){
            	//检查牵引是否存在,存在则修改牵引时间，否则新加牵引队列
	    		$qy_id = M("domain_qy")->get_one("domain='{$domain}' AND status=0","qy_id");
	    		if($qy_id){
	    			$qy_data = array(
	    				"expiry" => $expiry,
	    				"total0" => $data['total0'],
	    				"total1" => $data['total1'],
	    			);
	    			$ret = M("domain_qy")->set_data($qy_data)->update("qy_id='{$qy_id}'");
	    		}else{
	    			$qy_data = array(
	    				"domain"   => $data['domain'],
	    				"total0"   => $data['total0'],
	    				"total1"   => $data['total1'],
	    				"status"   => 0,
	    				"uid"      => 0,
	    				"author"   => "SYS",
	    				"dateline" => $timestamp,
	    				"expiry"   => $expiry,
	    				"undateline" => 0,
	    			);
	    			$ret = M("domain_qy")->set_data($qy_data)->add();
	    		}

	    		//重新生成记录
				M("@domain")->queue($domain,"update_record");
    		}
    		$ret0 && $ret && die("success");
    	}else{
    		die("no sign");
    	}
	}
	//签名认证
	private function _rz($parm=array(),$checkcode="",$timestamp=0){
		ksort($parm);
		$parm['timestamp'] = $timestamp?$timestamp:R('timestamp',"int");
		$parmstr = "";
		foreach($parm as $k=>$v){
			$parmstr .= "&{$k}={$v}";
		}
		$parmstr = substr($parmstr,1);
		$md5value = md5($this->_key.$parmstr.$this->_key);
	    if($md5value == $checkcode && $parm['timestamp'] > time()){
	        return true;
	    }
	    return false;
	}
}
?>