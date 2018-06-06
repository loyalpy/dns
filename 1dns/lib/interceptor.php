<?php
if(!defined('__ERROR_HANDLE_LEVEL__'))  define('__ERROR_HANDLE_LEVEL__', E_ALL ^ E_WARNING ^ E_NOTICE);
class interceptor{
	public static function onPhpShutDown(){
		//sql执行日志		
		$queries = tDB::get_db()->queries();
		if($queries){
			$logstr = "----------------- queries:".count($queries) ." -----------------\r\n";
			foreach($queries as $v){
				$logstr .= "[{$v['time']}]\t{$v['sql']}\r\n";
			}
			$logstr .= "\r\n";
			$sql_log_path = ROOT_PATH."cache/weblog/sql/".(App::get_conf("db.main.pdo") == 1?"pdo_":"").App::get_conf("db.main.type")."_".date("y-m-d").".log";
			$logfile = new tFile($sql_log_path,"a");
			$logfile->write($logstr);
		}
	}
	public static function onCreateApp() {
		global $timestamp,$realip,$refer;
		//获取客户端IP，当前时间
		$realip    = tClient::get_ip();
		$timestamp = tTime::get_now();
		$refer     = tUrl::get_refer();

		App::set_data("realip",$realip);
		App::set_data("timestamp",$timestamp);
		App::set_data("refer",$refer);
		//
		if(App::get_conf("cfg.cli")){

		}else{
			app_insert::set_user();
		}
	}
	public static function onFinishApp(){
		/*$page_log_path = ROOT_PATH."cache/weblog/operation/"."page_executetime_".date("y-m-d").".log";
		$logstr = "[".tTime::end()."]\t ".tUrl::get_uri();
		$logstr .= "\r\n";
		$logfile = new tFile($page_log_path,"a");
		$logfile->write($logstr);*/
	}
}
//////////////////////////////////////////////////////////////////
class app_insert{
	//项目会员登录设置
	public static function set_user(){
		global $uid,$utype;
		//检查登录状态
		//获取用户登录信息
		$uid       = tSafe::get("uid");
		$utype     = tSafe::get("utype");

		if(empty($uid)){
			$authstr       = tCookie::get("auth");
			$authstr_byurl = R("auth","string");
			$authstr = $authstr?$authstr:tCode::decode(str_replace(" ","+",$authstr_byurl));
			$userinfo = array();

			if($authstr){
				list($password,$log_name,$uid) = ($authstr?(explode("\t",$authstr)):array('','',0,0));
				if($password && $uid){
					$userinfo = C("user")->get_user("uid='{$uid}' AND password='{$password}'","");
				}
				if($userinfo){
					$uid   = $userinfo['uid'];
					$utype = $userinfo['utype'];
					C("user")->login_after($uid,$utype);
					tCookie::set("log_name",$log_name,1*86400);
				}else{
					$uid = $utype = 0;
				}
			}
		}
		$uid   = intval($uid);
		$utype = intval($utype);
		//设置其他公共信息ip  timestamp
		App::set_data("uid",$uid);
		App::set_data("utype",$utype);	
		//session
		if(tClient::get_browese() != "none"){
			M("@session")->init();
		}		
	}	
	//项目城市站点
	public static function set_city(){
		
	}
	//站点样式设置
	public static function set_style(){
		
	}
	//获取城市站点
	public static function get_citylist($by = 0){

	}
}
?>