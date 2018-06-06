<?php
class crond_service_group extends tController{
	public function index(){
		global $timestamp;
		set_time_limit(3600);
		//自定义服务器组，端口
		$port = '9001';
		$ipArr = array(
			"115.231.175.89".":".$port,
			"115.231.26.217".":".$port,
			"117.27.250.110".":".$port,
			"122.226.163.197".":".$port,
			"115.231.174.58".":".$port,
			"115.231.26.218".":".$port,
		);
		foreach ($ipArr as $ipStr){
			//检测服务器返回状态
			$server_ret = SDKdns::dns_mserver($ipStr, "status","");
			$return = 0;
			$reason = '';
			if ($server_ret['status'] == 1 && $server_ret['msg']) {//服务器故障
				$msg = $server_ret['msg']?(nl2br(is_array($server_ret['msg'])?$server_ret['msg'][1]:$server_ret['msg'])):"";
				if (preg_match("/VERSION/",$msg)) {
					$return = 1;
				}
			}else{//网络故障
				$reason = 'net';
			}
			if ($return == 0) {
				switch($reason){
					case "net":
						$name = md5($ipStr);
						$times = tCache::get($name);
						if(empty($times)){
							$times = 1;
						}else{
							$times = $times +1;
						}
						if($times < 3){
							$this->__send("downtime",$ipStr,"-网络故障");
						}
						if($times % 21 === 0){
							$this->__send("downtime",$ipStr,"-网络故障".$times."次");
						}
						if($times % 99 === 0){
							$times = 0;
						}
						tCache::set($name,$times);
						break;
					default:
						$this->__send("downtime",$ipStr,"-非网络故障");
						sleep(6);
						$server_restart_ret = SDKdns::dns_mserver($ipStr, "restart","-重启");
						if ($server_restart_ret['status'] == 1) {
							$this->__send("renew",$ipStr,"-非网络故障");
						}
						break;
				}
			}
		}
	}
	//发送通知
	private function __send($type ="downtime",$ipStr="",$reason=""){
		//定义接收人UID
		$idArr = array(1,1726);
		foreach ($idArr as $receive_uid){
			//邮件通知
			C("user")->send_mail(array("type"=>$type,"server"=>$ipStr,"time"=>time(),'domain'=>"haved down".$reason),$receive_uid);
			//微信通知
			C("user")->send_wx(array("type"=>$type,"server"=>$ipStr,"time"=>time(),'domain'=>"haved down".$reason,"reason"=>"server is down".$reason),$receive_uid);
		}
   }
}
?>