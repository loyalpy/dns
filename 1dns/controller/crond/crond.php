<?php
class crond extends tController{
	public function index(){
		global $timestamp;
		set_time_limit(7200);
		//剩余7天到期 //只发通知
		$this->___domain_expiry(7);
		//剩余3天到期 //只发通知
		$this->___domain_expiry(3);
		//已经到期1天 //切换并通知
        $this->___domain_expiry(-1,2);
		//已经到期7天 //不通知
		$this->___domain_nsswitch(-7);
		die();
	}
	//从切换日志里自动删除7天以后的远程记录
	private function ___domain_nsswitch($day){
		global $timestamp;
		$pagesize = 500; //每页读取 500条数据
		
		$cur_date     = tTime::get_datetime("Y-m-d",$timestamp);
		$cur_dateline = strtotime($cur_date);
		//读取第day日到期 发送通知
		$start_dateline = ($cur_dateline+($day)*86400);
		$end_dateline   = $start_dateline + 86400;


		$where          = "status=0 AND dateline < {$end_dateline}";		
		$c =array(
			"page"     => 1,
			"pagesize" => $pagesize,				
			"fields"   => "id,domain,old_ns_group,new_ns_group,type,status,uid",
			"where"    => $where,
		);
		//第一页
		$res = Q("domain_nsswitch")->get_list($c);
		$this->___domain_nsswitch_exec($res['list']);

		//多余第一页重复处理
		if($res['totalpage'] > 1){
			for($i = 2;$i<=$res['totalpage'];$i++){
				$c['page'] = $i;
				$res = Q("domain_nsswitch")->get_list($c);
				$this->___domain_nsswitch_exec($res['list']);
			}
		}


	}
	//读取满足条件 day 到期提醒 里到期 res_type  0 只发通知到期提醒   1 只切换   2 通知并切换 9 什么都不做
	private function ___domain_expiry($day = 7, $res_type = 0){
		global $timestamp;
		set_time_limit(7200);
		$pagesize = 500; //每页读取 500条数据
		
		$cur_date     = tTime::get_datetime("Y-m-d",$timestamp);
		$cur_dateline = strtotime($cur_date);
		//读取第day日到期 发送通知
		$start_dateline = ($cur_dateline+($day)*86400);
		$end_dateline   = $start_dateline + 86400;
         
        //echo tTime::get_datetime("Y-m-d",$start_dateline).' - '.tTime::get_datetime("Y-m-d",$end_dateline);
        //echo "<hr/>";

		$where          = "service_expiry > 0  AND service_expiry>= {$start_dateline} AND service_expiry < {$end_dateline}";		
		$c =array(
			"page"     => 1,
			"pagesize" => $pagesize,				
			"fields"   => "domain,service_expiry,uid",
			"where"    => $where,
		);
		//第一页
		$res = Q("domain")->get_list($c);
		$this->___domain_expiry_exec($res['list'],$res_type,$start_dateline);

		//多余第一页重复处理
		if($res['totalpage'] > 1){
			for($i = 2;$i<=$res['totalpage'];$i++){
				$c['page'] = $i;
				$res = Q("domain")->get_list($c);
				$this->___domain_expiry_exec($res['list'],$res_type,$start_dateline);
			}
		}
	}
	//批量处用户域名到期通知还是切换
	private function ___domain_expiry_exec($res = array(),$res_type = 0,$cur_date = 0){
		global $timestamp;
		$cur_date     = empty($cur_date)?$timestamp:$cur_date;
		$result = array();
		foreach ($res as $v) {
			if(!isset($result[$v['uid']])){
				$result[$v['uid']] = array();
			}
			$result[$v['uid']][] = $v['domain'];
		}
		foreach ($result as $uid => $value) {
			if($res_type == 0){
				$this->___send_notify($uid,$value,$cur_date);
			}elseif($res_type == 1){

			}elseif($res_type == 2){
				$this->___switch_ns_group($uid,$value,$cur_date);
			}
			usleep(500000);
		}
	}
    //批量处理切换日志
    private function ___domain_nsswitch_exec($res){
    	global $timestamp;
    	foreach($res as $list){
    		//老的服务器删除
    		$ret = SDKdns::make_record($list['domain'],$list['old_ns_group']);
			if(intval($list['type']) === 9 && $list['new_ns_group'] == "free"){//套餐切换
				$cur_ns_group = M("domain")->get_one("domain='{$list['domain']}'","ns_group");
				if($cur_ns_group == "free"){//如果当前套餐已经免费，切换到免费套餐
					M("@domain")->trans($list['domain'],"free",0);
				}				
			}
    		if($ret){
    			M("domain_nsswitch")->set_data(array("status"=>1,"status_dateline"=>$timestamp))->update("id='{$list['id']}'");
    		}
    		//新的服务器再生成一次
    		SDKdns::make_record($list['domain'],$list['new_ns_group']);
    		usleep(500000);
    	}
    }

	//发送通知
	private function ___send_notify($uid,$domains= array(),$cur_date = 0){
		global $timestamp;
		$cur_date     = empty($cur_date)?$timestamp:$cur_date;
		//邮件通知
		C("user")->send_mail(array("type"=>"domainexptime","time"=>$cur_date,'domain'=>implode(",",$domains)),$uid);
		//微信通知
		C("user")->send_wx(array("type"=>"domainexptime","time"=>$cur_date,'domain'=>implode(",",$domains)),$uid);
	}
	//切换
	private function ___switch_ns_group($uid,$domains = array(),$cur_date = 0){
		global $timestamp;
		$cur_date     = empty($cur_date)?$timestamp:$cur_date;
		$nsGroup = M("domain_ns_group")->get_row("ns_group = 'free'");
		if (isset($nsGroup['ns'])) {
			foreach ($domains as $k => $v) {
				M("@domain")->trans_ns($v,"free",9);
			}
			//邮件通知
			C("user")->send_mail(array("type" => "domainexpinfor", "ns" =>$nsGroup['ns'],"day" =>"七天","time" => $cur_date,'domain' =>implode(",",$domains)), $uid);
			//微信通知
			C("user")->send_wx(array("type" => "domainexpinfor", "ns" =>$nsGroup['ns'],"day" =>"七天","time" => $cur_date,'domain' =>implode(",",$domains)), $uid);
		}
	}
}
?>