<?php
/**
 * @brief 会员模块
 * @class user
 */
class cls_user{
	private $user_ext = array(
		1  => "",
		2  => "company",
		3  => ""
	);
	//获取用户
	public function get_user($where = false,$cols = '*'){
		$result = M("user")->get_row($where,$cols);
		return $result;
	}
	//获取用户列表
	public function get_userlist($c=array(),$pageurl=""){
		$result = array();
		$c['table'] = isset($c['table'])?$c['table']:"user";
		$c['where'] = isset($c['where'])?$c['where']:"1";
		$c['page'] = isset($c['page'])?$c['page']:1;
		$c['pagesize'] = isset($c['pagesize'])?$c['pagesize']:20;
		$c['order'] =  isset($c['order'])?$c['order']:"logdateline DESC";
		$query = new tQuery($c['table']);
		foreach($c as $k=>$v){
			$query->$k = $v;
		}
		$tmplist = $query->find();
		$result['list'] = array();
		$result['pagebar'] = $query->get_pagebar($pageurl);
		$result['total'] = $query->total;
		$result['totalpage'] = $query->totalpage;
		//列表处理
		$_ids = array();
		if($tmplist){
			foreach($tmplist as $k=>$v){
				$_ids[] = $v["uid"];
				//$v['idc'] = $v['idc']?explode(",",$v['idc']):array();
				$t_userinfo = $this->get_cache_userinfo($v['uid']);
				foreach ($t_userinfo['account'] as $key=>$v2){
					if($key == "uid")continue;
					$v[$key] = $v2;
				}
				$v['name'] = $t_userinfo['name'];
				$result['list'][$v['uid']] = $v;
			}
			$tmplist = M("session")->query("uid IN('".implode("','",$_ids)."')");
			if($tmplist){
				foreach($tmplist as $k=>$v){
					$result['list'][$v['uid']]['onlines'] = isset($result['list'][$v['uid']]['onlines'] )?($result['list'][$v['uid']]['onlines'] +1):1;
				}
			}
		}
		$result["ids"] = $_ids;
		//获取用户当前是否在线
		return $result;
	}
	//添加用户
	public function add_user($data = array(),$ext_data = array()){
		global $timestamp,$realip;
		$return = array("error"=>0,"message"=>"");
		//检测邮箱,昵称,用户名,手机
		foreach (array("email","nickname","uname","mobile") as $v){
			if(isset($data[$v]) && $data[$v]){
				$return["message"] = $this->check_user($data[$v],"{$v}");
				if($return["message"]){
					$return["error"] = 1;
					break;
				}
			}
		}		
		//如果验证成功将写入数据库
		if($return["error"] == 0){
			$data['password']    = md5($data["password"]);
			$data['regdateline'] = $data['logdateline'] = $timestamp;
			$data['regip']       = $data['logip'] = $realip;
			$data['logtimes']    = 1;
			$data['utype']       = (isset($data['utype']) && in_array($data['utype'],array(1,2,3)))?$data['utype']:1;
			//写注册数据库
			$u_obj = new tModel("user");
			$u_obj->set_data($data);
			$return['uid'] = $u_obj->add();
			if(empty($return['uid'])){
				$return['error'] = 1;
				$return['message'] = "服务器正忙,请稍后再试";
			}else{
				if($ext_data && isset($this->user_ext[$data['utype']])){
					$ext_data['uid'] 		= $return['uid'];
					$ext_data['dateline']   = $timestamp;
					$return['ext_id'] = M($this->user_ext[$data['utype']])->set_data($ext_data)->add();
				}
			}
		}
		return $return;
	}
	//删除用户
	public function del_user($uid = 0){
		$userinfo = $this->get_cache_userinfo($uid);
		if(empty($userinfo['uid']) || $userinfo['uid'] == 1 || in_array($userinfo['urole'],array(1,2)))return -1;

		$ret = M("user")->del("uid=$uid");
		if($ret){
			C("user")->log("删除用户","用户名：{$userinfo['name']},邮箱:{$userinfo['email']},手机：{$userinfo['mobile']},用户ID:{$userinfo['uid']};");
			$this->set_cache_userinfo($uid,null);
			return 1;
		}
		return 0;
	}
	//修改用户
	public function update_user($uid,$data = array(),$where="",$clear_cache = true){
		if(isset($data["password"])){
			$data['password'] = md5($data["password"]);
		}
		//写注册数据库
		$ret = M("user")->set_data($data)->update("uid='{$uid}'".$where,array("logtimes"));
		if($ret && $clear_cache){
			C('user')->set_cache_userinfo($uid,null);
		}
		return $ret;
	}
	//检查进入数据库项目的合法性
	public function check_user($val,$type,$where= ""){
		$errmsg = "";
		$u_obj = new tModel("user");
		switch ($type){
			case "mobile":
	            if(!tValidate::is_mobile($val)){
	            	$errmsg = "出错了! 手机格式非法";
	            }else{
	            	if($u_obj->get_one('mobile = "'.$val.'"'.($where?" AND {$where}":""),"count(uid)")>0){
	            		$errmsg = "出错了! 该手机已存在";
	            	}
	    		}
				break;
			case "email":
	            if(!tValidate::is_email($val)){
	            	$errmsg = "出错了! 邮件格式非法";
	            }else{
	            	if($u_obj->get_one('email = "'.$val.'"'.($where?" AND {$where}":""),"count(uid)")>0){
	            		$errmsg = "出错了! 该电子邮箱已存在";
	            	}
	    		}
				break;
			case "nickname":
			case "uname":
				if(strlen($val)<=6){
	            	$errmsg = "出错了! 用户名不能少于6位";
	            }else{
	            	if($u_obj->get_one($type.' = "'.$val.'"'.($where?" AND {$where}":""),"count(uid)")>0){
	            		$errmsg = "出错了! 用户名/昵称已存在";
	            	}
	    		}
				break;
			default :
				$errmsg = "非法操作！";
				break;
		}
		return $errmsg;
	}
	//获取用户缓存
	public function get_cache_userinfo($uid,$uri="") {
		if(empty($uid)) {
			return array("uid"=>0,"utype"=>0);
		}
		//缓存标识
		$cache_name = "userinfo_".$uid;
		//获取userinfo
		$userinfo = tCache::get($cache_name);
		if(empty($userinfo)) {
			//获取初始用户信息
			$userinfo = $this->get_user("uid='{$uid}'");
			if(isset($userinfo['uid'])){
				//获取账户信息
				$userinfo['account'] = M("@account")->get($uid);
				//获取账户APPid
				//$userinfo['appid']   = M("api_user")->get_one("uid='{$uid}'","appid");
				//获取账户等级
				$userinfo['setting'] = $this->get_setting($userinfo['uid'],$userinfo['utype'],$userinfo['ulevel']);
				//会员显示名
				$userinfo['name'] = $userinfo['nickname']?$userinfo['nickname']:$userinfo['uname'];
				if(empty($userinfo['name'])){
					$userinfo['name'] = $userinfo['email']?$userinfo['email']:$userinfo['mobile'];
				}
				//用户绑定
				$userinfo['bd'] = M("@user_bd")->get_wxbd($uid);
				///读取相关扩展数据
				if($userinfo['utype'] == 1){//个人
					
				}elseif($userinfo['utype'] == 2){//公司

				}
			}else{
				$userinfo = array(
					"uid"    => 0,
					"name"   => "",
				);
			}
			tCache::set($cache_name,$userinfo);
		}
		//按需求获取数据
		$node = $userinfo;
		if($uri){
			$paths = explode('.', $uri);
			while (!empty($paths)) {
				$path = array_shift($paths);
				if (!isset($node[$path])) {
					return null;
				}
				$node = $node[$path];
			}
		}		
		return $node;
	}
	//设置用户缓存
	public function set_cache_userinfo($uid, $userinfo = null) {
		//缓存标识
		$cache_name = "userinfo_".$uid;
		//如果为null则删除该缓存
		if(is_null($userinfo)){
			tCache::del($cache_name);
			return true;
		}
		//如果userinfo为则重新设置
		if(!empty($userinfo)){
			tCache::set($cache_name,$userinfo);
			return true;
		}
	}
	//更新用户缓存
	public function update_cache_userinfo($uid,$new = null){
		if($new === null){
			$this->set_cache_userinfo($uid,null);
		}else{
			$userinfo = $this->get_cache_userinfo($uid);
			foreach($new as $k=>$v){
				if(isset($userinfo[$k])){
					$userinfo[$k] = $v;
				}
			}	
			$this->set_cache_userinfo($uid,$userinfo);
		}
	}
	//写入登录日志
	public function login_write_record($config = array()){
		global $uid,$realip,$timestamp;
		$config['log_ip']		= empty($config['log_ip'])?tClient::get_ip(1):$config['log_ip'];
		$config['log_place']	= tClient::convert_ip($realip);
		$config['dateline']	= $timestamp;		
		M("login_record")->set_data($config);
		return M("login_record")->add();
	}
	//写入用户操作日志
	public function log($action="",$msg="",$log_type="db"){
		global $uid,$realip,$timestamp;
		$userinfo = $this->get_cache_userinfo($uid);
		$tLog = new tLog($log_type);
		$logs = array(
			"uid"     => $uid,
			"author"  => $userinfo['name'],
			"action"  => $action,
			"content" => $msg,
			"ip"      => tClient::get_ip(1)
		);
		return $tLog->write("operation",$logs);
	}
	//获取用户权限
	public function get_urole($urole=0){
		$result = tCache::get("urole".$urole);
		if($result == null || empty($result)){
			 $result = M("user_role")->get_row("id=$urole","id,name,purview,inlock");
			 $result = isset($result['id'])?$result:array("name"=>"","purview"=>"","id"=>0,"inlock"=>0);
			 tCache::set("urole".$urole,$result);
		}
		return $result;
	}
	//获取用户等级   
	public function get_ulevel($utype = 0,$uri=""){
		$node = tCache::read("user_ulevel_{$utype}");
		if($uri !== ''){	
			$paths = explode('.', $uri);
			while (!empty($paths)) {
				$path = array_shift($paths);
				if(!isset($node[$path])) {
					return null;
				}
				$node = $node[$path];
			}
		}
		return $node;
	}
	//获取用户类型   
	public function get_utype($uri = "data_config.utype"){
		return App::get_conf($uri);
	}
	//获取设置
	public function get_setting($uid,$utype,$ulevel){
		$setting = array();
		$query_list = M("user_setting")->query("uid='{$uid}'","name,value");
        $ulevel = $this->get_ulevel($utype,"{$ulevel}");
        if($query_list){
        	foreach($query_list as $key=>$v){
        		$setting[$v['name']] = $v['value'];
        	}
        }
        if(isset($ulevel['id'])){
        	if(is_array($ulevel['data'])){
	        	foreach($ulevel['data'] as $k=>$v){
	        		if(!isset($setting[$k])){
	        			$setting[$k] = $v['value'];
	        		}
	        	}
        	}
        	$setting['ulevel_name']   = $ulevel['alias'];
        	$setting['ulevel_minv']   = $ulevel['minv'];
        	$setting['ulevel_maxv']   = $ulevel['maxv'];
        	$setting['ulevel_inlock'] = $ulevel['inlock'];
        	$setting['ulevel_data']   = $ulevel['data'];
        }
		return $setting;
	}
	//登录之后
	public function login_after($uuid = 0, $log_type = "login"){
		global $uid,$utype,$timestamp,$realip;

		$userinfo = $this->get_cache_userinfo($uuid);
		if(!isset($userinfo['uid']) || empty($userinfo['uid']) || $userinfo['inlock'] > 0){
			return 0;
		}

		$uid      = $userinfo['uid'];
		$utype    = $userinfo['utype'];
		//记住登录状态
		tSafe::set('uid',$uid);
		tSafe::set('utype',$utype);

		$log_type_arr = array(
			"login"  => "通过LOGIN页正常登录",
			"cookie" => "通过记住密码Cookie登录",
			"refer"  => "通过引用auth登录",
			"index"  => "通过首页登录",
			"other"  => "未知途径登录",
			"pop"    => "弹出框登录",
			"weixin" => "通过微信扫描登录",
			"weixin_scan"=>"微信公众号登陆",
			"local"  => "客户端本地登陆",
		);
		if(!in_array($log_type,array_keys($log_type_arr))){
			$log_type = "other";
		}
		//更新最后登录时间,ip
		$this->update_user($uid,
			array(
			"logtimes"    => "logtimes+1",
			"logdateline" => $timestamp,
			"logip"       => $realip,
		));

		$ie = tClient::get_browese();
		$pc = tClient::get_pc();
		$this->login_write_record(array("uid"      => $uid,
			"log_data" => $log_type_arr[$log_type],
			"log_type" => $log_type,
			"utype"    => $userinfo['utype'],
			"log_ie"   => $ie,
			"log_pc"   => $pc));
		//更新会话
		M("@session")->update_login();
		//后续操作
		if($userinfo['utype'] == 1){//个人
					
		}elseif($userinfo['utype'] == 2){//企业
			
		}
		//发送登录通知
		if(!in_array($log_type,array("weixin","weixin_scan"))){
			C("user")->send_wx(array("type"=>"logininfo","email"=>$userinfo['email'],'pc'=>$pc,'ie'=>$ie),$uid);
		}		
		return 1;
	}
	//注册之后
	public function reg_after($uuid = 0) {
		global $uid,$utype,$timestamp,$realip;
		
		$userinfo = $this->get_cache_userinfo($uuid);
		if(!isset($userinfo['uid']) || empty($userinfo['uid']) || $userinfo['inlock'] > 0){
			return 0;
		}
        $uid      = $userinfo['uid'];
		$utype    = $userinfo['utype'];
		//记住登录状态
		tSafe::set('uid',$uid);
		tSafe::set('utype',$utype);
		
		//更新最后登录时间,ip
		C("user")->login_write_record(array("uid" => $uid,
			"log_data" => "注册登录",
			"log_type" => 9,
			"utype"    => $userinfo['utype'],
			"log_ie"   => tClient::get_browese(),
			"log_pc"   => tClient::get_pc()));

		M("@session")->update_login();
		//活动相关
		//注册后赠送积分，余额，代金券相关
		M("@coupon")->account_give_set("reg",$uid);
		//发送注册邮件
		if($userinfo["email"]){
			C('user')->send_mail(array("type"=>"rz"),$uid,$userinfo["email"]);
		}
		//推广注册
		$bajiednsTg = tCookie::get("bjTg");
		if($bajiednsTg){
			$fromid = tUtil::numstr($bajiednsTg,true,8);
			if($fromid){
				$tg_data = array(
					'myid'	=>$uid,
					'fromid'	=>$fromid,
					'step'	=>0,
					'inlock'=>0,
					'dateline'=>$timestamp
				);
				M("tg")->set_data($tg_data)->add();
			}
		}
	}
	//session_bd
	public function session_bd($uuid){
		$wxdata = @M("@session")->get_data();
		if(isset($wxdata['wx_openid']) && $wxdata['wx_openid']){
			M("@user_bd")->wxbd($uuid,$wxdata['wx_openid'],$wxdata);
		}
	}
	//退出操作
	public function logout(){
		global $uid;
		$uid = 0;
		M("@session")->update_login("logout");
		tCookie::uset("bjTg");
		tCookie::destroy(array('style'));
	}
	//发邮件：模板发送
	public function send_mail($data = array(),$send_uid = 0,$email= ""){
		global $uid,$timestamp;
		$send_uid = empty($send_uid)?$uid:$send_uid;
		if(empty($email)){
			$email = $this->get_cache_userinfo($send_uid,"email");
			if(empty($email))return 0;
		}
		$find = array();
	    $find['dateline'] = $timestamp;
	    $find['uid']        = $send_uid;
	    $find['email']    = $email;
	    $data['type'] = isset($data['type'])?$data['type']:"rz";

		//sendclound模板公共配置
		$data['%tel%'] 				     = array(M("site_config")->get_one("name='tel'","value"));
		$data['%copyright%']	     = array(M("site_config")->get_one("name='copyright'","value"));
		$data['%username%']  	 = array($email);

	    switch($data['type']){
	    	case "rz":   //邮箱认证，激活
				$tpl = '8jdns_template_active';
				$title = "八戒DNS注册激活邮件";

				$rz_url = U("account@/misc/email_verify").tHash::uri($find);
				$data['%url%'] 			= array("<a href='".$rz_url."'>点击此链接激活</a>");
	    		break;
	    	case "findpass":  //找回密码
				$tpl = '8jdns_template_findpsw';
				$title = "八戒DNS密码找回邮件";

	    		$find['name'] = isset($data['name'])?$data['name']:$email;
	    		$find['type'] = 'email';
				$rz_url = U("account@/login/findpass3").tHash::uri($find);

				$data['%url%'] 			= array("<a href='".$rz_url."'>点击此链接找回</a>");
	    		break;
			case "downtime":  //宕机检测
				$tpl = '8jdns_template_downtime';
				$title = isset($data['server'])?"八戒DNS服务器{$data['server']}宕机提醒":"";

				$data['%server%'] 		= array(isset($data['server'])?$data['server']:"");
				$data['%domain%'] 	= array(isset($data['domain'])?$data['domain']:"");
				$data['%time%'] 			= array(isset($data['time'])?date("Y-m-d H:i",$data['time']):"");
				unset($data['server']);
				unset($data['domain']);
				unset($data['time']);
				break;
			case "renew":  //宕机恢复正常
				$tpl = '8jdns_template_renew';
				$title = isset($data['server'])?"八戒DNS服务器{$data['server']}恢复正常":"";

				$data['%server%'] 		= array(isset($data['server'])?$data['server']:"");
				$data['%domain%'] 	= array(isset($data['domain'])?$data['domain']:"");
				$data['%time%'] 			= array(isset($data['time'])?date("Y-m-d H:i",$data['time']):"");
				unset($data['server']);
				unset($data['domain']);
				unset($data['time']);
				break;
			case "check":  //URL转发审核
				$tpl = '8jdns_template_check';
				$title = isset($data['domain'])?"八戒DNS域名{$data['domain']}转发记录通过审核":"";

				$data['%domain%'] 		= array(isset($data['domain'])?$data['domain']:"");
				$data['%RRtype%'] 		= array(isset($data['RRtype'])?$data['RRtype']:"");
				unset($data['domain']);
				unset($data['RRtype']);
				break;
			case "upgrade":  //升级套餐
				$tpl = '8jdns_template_upgrade';
				$title = isset($data['domain'])?"域名续费成功":"购买域名套餐成功";

				$data['%content%'] 	= array(isset($data['content'])?$data['content']:"");
				$data['%domain%'] 	= array(isset($data['domain'])?$data['domain']:"域名套餐");
				unset($data['content']);
				if (isset($data['domain'])) {
					unset($data['domain']);
				}
				break;
			case "domainfind":  //域名找回
				$tpl = '8jdns_template_domainfind';
				$title = isset($data['domain'])?"八戒DNS域名{$data['domain']}找回邮件":"";

				$find['domain'] = isset($data['domain'])?$data['domain']:"";
				$find['id'] = isset($data['id'])?$data['id']:"";
				$find_url = U("account@/domains/domain_find2").tHash::uri($find);

				$data['%domain%'] 	= array(isset($data['domain'])?$data['domain']:"");
				$data['%url%'] 			= array("<a href='".$find_url."'>点击此链接找回域名</a>");
				unset($data['domain']);
				unset($data['id']);
				break;
			case "domaintransfer":  //域名过户
				$tpl = '8jdns_template_domaintransfer';
				$title = isset($data['domain'])?"八戒DNS域名{$data['domain']}过户通知":"";

				$data['%tranUser%'] 	= array(isset($data['tranUser'])?$data['tranUser']:"");
				$data['%domain%'] 	= array(isset($data['domain'])?$data['domain']:"");
				$data['%time%'] 			= array(isset($data['time'])?date("Y-m-d H:i",$data['time']):"");
				unset($data['tranUser']);
				unset($data['domain']);
				unset($data['time']);
				break;
			case "domainexptime":  //域名套餐到期提醒
				$tpl = '8jdns_template_exptime';
				$title = isset($data['domain'])?"八戒DNS域名{$data['domain']}套餐即将到期提醒":"";

				$url = U("home@/product/buy");
				$data['%domain%'] 	= array(isset($data['domain'])?$data['domain']:"");
				$data['%time%'] 			= array(isset($data['time'])?date("Y-m-d",$data['time']):"");
				$data['%url%'] 			= array("<a href='".$url."'>立即升级套餐</a>");
				unset($data['domain']);
				unset($data['time']);
				break;
			case "domainregexptime":  //注册域名到期提醒
				$tpl = '8jdns_template_domainregexp';
				if (isset($data['domain'])) {
					if (strlen($data['domain']) > 80) {//限制提醒域名个数为5个
						$data['domain'] = implode(",",array_slice(explode(",",$data['domain']), 0, 5));
					}
				}
				$title = isset($data['domain'])?"八戒DNS注册域名{$data['domain']}到期提醒":"";

				$data['%domain%'] 	= array(isset($data['domain'])?$data['domain']:"");
				$data['%info%'] 			= array(isset($data['info'])?$data['info']:"");
				unset($data['domain']);
				unset($data['info']);
				break;
			case "domainexpinfor":  //域名套餐到期切换通知
				$tpl = '8jdns_template_domainswtinfor';
				$title = isset($data['domain'])?"八戒DNS域名{$data['domain']}套餐到期切换通知":"";

				$data['%domain%'] 	= array(isset($data['domain'])?$data['domain']:"");
				$data['%ns%'] 				= array(isset($data['ns'])?$data['ns']:"");
				$data['%day%'] 			= array(isset($data['day'])?$data['day']:"");
				$data['%time%'] 			= array(isset($data['time'])?date("Y-m-d",$data['time']):"");
				unset($data['domain']);
				unset($data['ns']);
				unset($data['time']);
				unset($data['day']);
				break;
	    	default:
	    		return 0;
	    		break;
	    }
		unset($data['type']);
		$ret = SendCloud::send_mail($email,$tpl,$data);
		if($ret && $ret['statusCode'] == 200){
			//加入邮件发送表
			C("mail")->email_add($email,$tpl,$title,"");
		}
		return $ret;
	}
	//发邮件：自定义发送 返回0：失败1 成功
	public function send_meail_usual($email,$title = "",$content = ""){
		if (empty($email) || empty($title) || empty($content)) {
			return 0;
		}
		$tel 				     = M("site_config")->get_one("name='tel'","value");
		$copyright	     = M("site_config")->get_one("name='copyright'","value");
		$html = "<style type=\"text/css\">html,
								body {
									margin: 0;
									padding: 0;
									font-size:14px;
								}
							</style>
							<center>&nbsp;</center>
							<center>&nbsp;</center>
							<div style=\"border:1px solid #C8CFDA;width:800px;padding:50px 30px;margin-left:30px;\">
							<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" style=\"width: 750px;line-height:35px;font-size:14px;\">
								<tbody>
									<tr>
										<td>尊敬的八戒DNS用户：{$email}</td>
									</tr>
									<tr>
										<td>您好！</td>
									</tr>
									<tr>
										<td>
										<p>{$content}</p>
										<p style=\"font-size:12px;\">八戒DNS官网：<a href=\"https://www.bajiedns.com/\" style=\"font-size: 14px; line-height: 35px;\" target=\"_blank\">立即访问</a></p>
										</td>
									</tr>
								</tbody>
							</table>
							<center>
							<p style=\"color:#317BD7;font-family:microsoft yahei;\"><strong><span style=\"color:#317bcf;\"><span style=\"font-family:microsoft yahei;\">八戒DNS运营团队</span></span></strong></p>
							<div style=\"width:790px;border-top:1px solid silver;\">&nbsp;</div>
							<div>
							<div style=\"display: inline-block\"><img alt=\"\" height=\"106px\" src=\"https://www.bajiedns.com/skins/home/default/images/weixin.jpg\" style=\"vertical-align: top;margin-top:20px;\" width=\"106px\" /></div>
							<div style=\"display: inline-block;text-align:left;margin-left:20px;vertical-align: top;\">
							<p><span style=\"font-family: 'microsoft yahei';\">此邮件由八戒DNS系统自动发出，请勿回复</span></p>
							<p><span style=\"font-family: 'microsoft yahei';\">如有问题，您可以写信给我们：service@bajiedns.com 或请拨联系：{$tel}</span></p>
							<p><span style=\"font-family: 'microsoft yahei';\"><strong style=\"line-height: 20.7999992370605px;\">八戒DNS官方网站:</strong> <span style=\"line-height: 20.7999992370605px;\"><a href=\"http://www.bajiedns.com\" target=\"_blank\">www.bajiedns.com</a></span></span></p>
							<p><span style=\"font-family: 'microsoft yahei';\">{$copyright}</span></p>
							</div>
							</div>
							</center>
							</div>";
		$res = SendCloud::send_mail_usual($email,$title,$html);
		if ($res && $res['statusCode'] == 200) {
			//加入邮件发送表
			C("mail")->email_add($email,"普通发送",$title,$content);
			return 1;
		}else{
			return 0;
		}
	}
	//发短信
	public function send_sms($data = array(),$mobile= "",$send_uid = 0){
		global $uid,$timestamp,$realip;
		$ret  = 0;
		//发送类型
		$data['type'] = isset($data['type'])?$data['type']:"rz";
		//发送用户信息
		$uuid     = $send_uid?$send_uid:$uid;
		$userinfo = $this->get_cache_userinfo($uuid);

		if(empty($mobile)){
			$mobile = (isset($userinfo['mobile']) && $userinfo['mobile'])?$userinfo['mobile']:
			"";
			if(empty($mobile)){
				return 0;
			}
		}
		$is = 0;
		if(in_array($data['type'],array("downtime","renew"))){
			if(!isset($userinfo['uid']) || $userinfo['uid']<=0){
				return -1;
			}
			if($userinfo['account']['sms']<=0){//短信余额不足
				return -2;
			}
			$is = 1;
		}
		
		switch($data['type']){
			case "reg": //通用下面modify方法
			case "findpass":
			case "rz":
			case "modify":	 //手机验证码
				$tpl_id = "1199";
				$code  = rand(100000,999999);
				$map = array(
					'%code%' => $code,
				);
				$res = SendCloud::send_sms($mobile,$tpl_id,$map);
				if ($res && $res['statusCode'] == 200) {
					$sys_sms_data = array(
						"mobile"    		 => $mobile,
						"tpl"       		 => $data['type'],
						"content"   		 => "手机验证，验证码：".$code,
						"code"      		 => $code
					);
					M("@sys_sms")->sms_add($sys_sms_data);
					tSafe::set('rzcode',$code);
					$ret = 1; // 发送成功
				}else{
					$ret = 0; //发送失败
				}
				break;
			case "downtime": //宕机检测
				$tpl_id = "1201";
				$map = array(
					'%server%'	=> isset($data['server'])?$data['server']:"",
					'%time%'		=> isset($data['time'])?date("Y-m-d",$data['time']):"",
				);
				$res = SendCloud::send_sms($mobile,$tpl_id,$map);
				if ($res && $res['statusCode'] == 200) {
					$sys_sms_data = array(
						"mobile"  => $mobile,
						"tpl"     => $data['type'],
						"content" => "服务器宕机通知",
						"code"    => 0
					);
					M("@sys_sms")->sms_add($sys_sms_data);
					$ret = 1; // 发送成功
				}else{
					$ret = 0; //发送失败
				}
				break;
			case "renew": //宕机恢复
				$tpl_id = "1202";
				$map = array(
					'%server%'	=> isset($data['server'])?$data['server']:"",
					'%time%'    => isset($data['time'])?date("Y-m-d",$data['time']):"",
				);
				$res = SendCloud::send_sms($mobile,$tpl_id,$map);
				if ($res && $res['statusCode'] == 200) {
					$sys_sms_data = array(
						"mobile"  => $mobile,
						"tpl"     => $data['type'],
						"content" => "服务器宕机恢复通知",
						"code"    => 0
					);
					M("@sys_sms")->sms_add($sys_sms_data);
					$ret = 1; // 发送成功
				}else{
					$ret = 0; //发送失败
				}
				break;
			default:				
				break;
		}
		//发送成功并且需要扣费
		if($ret == 1 && $is == 1){
			M("@account")->update($userinfo['uid'],array("sms"=>"-1"),array("sms"=>"发送宕机监控短信1条,手机号：{$mobile}"));
		}
		return $ret;
	}
	//发微信
	public function send_wx($data = array(),$send_uid = 0){
		global $uid,$timestamp,$realip;

		$send_uid = empty($send_uid)?$uid:$send_uid;
		$userinfo = C("user")->get_cache_userinfo($send_uid);

		if(isset($userinfo['bd']) && $userinfo['bd']['status'] == 2){
			switch($data['type']){
				case "logininfo":  //用户登录提醒
					$tpl_id = 'NImYbku1wBWSIECRSNGCv99zIFPw5C9tO-x-kXEeGpQ';

					$email = isset($data['email'])?$data['email']:$userinfo['email'];
					$map = array(
						"first"  => array("value"=>"您好，您的帐号 {$email}被登录",'color'=>'#0A0A0A'),
						"time"   => array("value"=>tTime::get_datetime("Y-m-d H:i:s",$timestamp),'color'=>'#CCCCCC'),
						"ip"     => array("value"=>$realip,'color'=>'#CCCCCC'),
						"reason" => array("value"=>"登录环境：{$data['pc']},{$data['ie']},如果本次登录不是您本人所为，说明您的帐号已经被盗！为减少您的损失，请点击本条消息，立即锁定帐号。",'color'=>'#CCCCCC'),
					);
					break;
				case "downtime":  //宕机检测
					$tpl_id = 'e1d4TKkYTRsIBiN2U8PjzvS2XKIAaeSmbHhIk9X76kw';

					$server = isset($data['server'])?$data['server']:"";
					$domain = isset($data['domain'])?$data['domain']:"";
					$map = array(
						"first"  				=> array("value"=>"您好，您的服务器{$server}({$domain})已经宕机",'color'=>'#0A0A0A'),
						"time"     			=> array("value"=>tTime::get_datetime("Y-m-d H:i:s",isset($data['time'])?$data['time']:""),'color'=>'#CCCCCC'),
						"reason"     		=> array("value"=>isset($data['reason'])?$data['reason']:"",'color'=>'#CCCCCC'),
						"remark" 			=> array("value"=>"请您检查详细信息，以免影响网站的正常访问。",'color'=>'#CCCCCC'),
					);
					break;
				case "renew":  //宕机恢复正常
					$tpl_id = 'D72IRFG4JeGhl78jtlIWoObkch3ar5K17HjgwOzSVus';

					$server = isset($data['server'])?$data['server']:"";
					$domain = isset($data['domain'])?$data['domain']:"";
					$map = array(
						"first"  				=> array("value"=>"您好，您的服务器{$server}({$domain})已恢复正常",'color'=>'#0A0A0A'),
						"time"     			=> array("value"=>tTime::get_datetime("Y-m-d H:i:s",isset($data['time'])?$data['time']:""),'color'=>'#CCCCCC'),
						"last"     			=> array("value"=>isset($data['continue_time'])?$data['continue_time']:"",'color'=>'#CCCCCC'),
						"reason" 			=> array("value"=>"请您登录网站查看详细信息，八戒DNS祝您生活愉快。",'color'=>'#CCCCCC'),
					);
					break;
				case "check":  //URL转发审核
					$tpl_id = 'KBPrVasX4yHNlVnzPIlpiVR9ZpssqTLvPhM25lFwhkM';

					$domain = isset($data['domain'])?$data['domain']:"";
					$type = isset($data['RRtype'])?$data['RRtype']:"";
					$map = array(
						"first"  				=> array("value"=>"您好，您的审核结果如下",'color'=>'#0A0A0A'),
						"keyword1"     => array("value"=>"您添加的域名{$domain}({$type})转发服务已经通过我们的审核",'color'=>'#CCCCCC'),
						"keyword2"     => array("value"=>tTime::get_datetime("Y-m-d H:i:s",isset($data['time'])?$data['time']:""),'color'=>'#CCCCCC'),
						"keyword3"     => array("value"=>"您现在已经可以使用我们的转发服务。",'color'=>'#CCCCCC'),
						"remark" 			 => array("value"=>"八戒NDS祝您生活愉快",'color'=>'#CCCCCC'),
					);
					break;
				case "upgrade":  //升级套餐
					$tpl_id = 'U9ay9TZ7SuBV9TjiX-V0wSwyGhVu8G8oWAlplnlU1EI';

					$map = array(
						"first"  				=> array("value"=>"恭喜您，成功购买域名套餐",'color'=>'#0A0A0A'),
						"keyword1"     => array("value"=>tTime::get_datetime("Y-m-d H:i:s",$timestamp),'color'=>'#CCCCCC'),
						"keyword2"     => array("value"=>"见下套餐期限。",'color'=>'#CCCCCC'),
						"remark" 			 => array("value"=>isset($data['content'])?$data['content']:"",'color'=>'#CCCCCC'),
					);
					break;
				case "domaintransfer":  //域名过户
					$tpl_id = 'OhOAmLg0svGxrVGy69-MWV2cwq4xiN5VMuCP930HnJY';

					$domain = isset($data['domain'])?$data['domain']:"";
					$tranuser = isset($data['tranUser'])?$data['tranUser']:"";
					$map = array(
						"ip"					=> array("value"=>$realip,'color'=>'#CCCCCC'),
						"action"  			=> array("value"=>"用户{$tranuser}过户到您的账下，过户域名{$domain}",'color'=>'#0A0A0A'),
						"time"     			=> array("value"=>tTime::get_datetime("Y-m-d H:i:s",isset($data['time'])?$data['time']:""),'color'=>'#CCCCCC'),
						"reason" 			=> array("value"=>"请您登录网站查看详细信息，八戒DNS祝您生活愉快。",'color'=>'#CCCCCC'),
					);
					break;
				case "domainexptime":  //域名套餐到期提醒
					$tpl_id = 'lEK9McqjezYO4q6BQBqYfPQhldXm_EAbo9oNpmIisa4';

					$map = array(
						"first"  				=> array("value"=>"您好，您的域名VIP服务套餐即将到期",'color'=>'#0A0A0A'),
						"keyword1"     => array("value"=>isset($data['domain'])?$data['domain']:"",'color'=>'#CCCCCC'),
						"keyword2"     => array("value"=>tTime::get_datetime("Y-m-d H:i:s",isset($data['time'])?$data['time']:""),'color'=>'#CCCCCC'),
						"keyword3"     => array("value"=>"套餐到期可能会影响域名的部分使用功能",'color'=>'#CCCCCC'),
						"remark" 			 => array("value"=>"为保证域名的正常使用，请及时续费，如不及时续费，系统到时将自动转为免费版套餐。",'color'=>'#CCCCCC'),
					);
					break;
				case "domainregexptime":  //注册域名到期提醒
					$tpl_id = 'lEK9McqjezYO4q6BQBqYfPQhldXm_EAbo9oNpmIisa4';

					$map = array(
						"first"  				=> array("value"=>"您好，您的注册域名即将到期",'color'=>'#0A0A0A'),
						"keyword1"     => array("value"=>isset($data['domain'])?$data['domain']:"",'color'=>'#CCCCCC'),
						"keyword2"     => array("value"=>tTime::get_datetime("Y-m-d H:i:s",isset($data['time'])?$data['time']:""),'color'=>'#CCCCCC'),
						"keyword3"     => array("value"=>"域名到期可能会影响域名的部分使用功能",'color'=>'#CCCCCC'),
						"remark" 			 => array("value"=>"为保证域名的正常使用，请及时续费。",'color'=>'#CCCCCC'),
					);
					break;
				case "domainexpinfor":  //域名套餐到期切换通知
					$tpl_id = 'lEK9McqjezYO4q6BQBqYfPQhldXm_EAbo9oNpmIisa4';

					$domain   = isset($data['domain'])?$data['domain']:"";
					$day 		= isset($data['day'])?$data['day']:"";
					$ns 			= isset($data['ns'])?$data['ns']:"";
					$map = array(
						"first"  				=> array("value"=>"您好，您的域名{$domain}已经到期",'color'=>'#0A0A0A'),
						"keyword1"     => array("value"=>isset($data['domain'])?$data['domain']:"",'color'=>'#CCCCCC'),
						"keyword2"     => array("value"=>tTime::get_datetime("Y-m-d H:i:s",isset($data['time'])?$data['time']:""),'color'=>'#CCCCCC'),
						"keyword3"     => array("value"=>"我们已将您的域名套餐切换为免费版套餐",'color'=>'#CCCCCC'),
						"remark" 			 => array("value"=>"为了不影响您域名的正常使用，请您于{$day}内将域名NS修改为免费版NS：{$ns}。",'color'=>'#CCCCCC'),
					);
					break;
				default:
					return 0;
					break;
			}
			$ret = SDKwx::send_tpl($map,$userinfo['bd']['wx_openid'],$tpl_id);
			return $ret;
		}else{
			return 0;
		}
	}

}
?>