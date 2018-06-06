<?php
class domains extends UC{
	public function __construct(){
		parent::__construct('domains');
	}
	public function favicon(){
		global $timestamp;
		header("Location:".U("static@/public/images/favicon.gif"));
		exit(0);
		include ROOT_PATH . 'lib/tools/Ico.php';
   		$domain      = R("domain","string");
   		if(empty($domain)){
   			header("Location:".U("static@/public/images/favicon.gif"));
   			exit(0);
   		}

   		$domain_md5  = md5($domain);
   		$filename    = $domain_md5.".png";
   		$path_dir    = "attach/favicons/".substr($domain_md5,0,1)."/".substr($domain_md5,1,1)."/";
   		$path        = $path_dir.$filename;
   		$store_dir   = ROOT_PATH."www/static/".$path_dir;
   		$store_file  = $store_dir.$filename;
   		$file_exists = file_exists($store_file);
   		$re_request  = 0;

   		if($file_exists){
   			if(filemtime($store_file) < ($timestamp - 86400)){
   				$re_request = 1;
   			}
   		}else{
   			$re_request = 1;
   		}
   		if($re_request){
			if($store_dir != '' && !is_dir($store_dir)){
		    	tFile::mkdir($store_dir);
		    }
	   		$icofile = "http://www.{$domain}/favicon.ico";
	   		$ch = curl_init();
		    curl_setopt ($ch, CURLOPT_URL, $icofile);
		    curl_setopt($ch, CURLOPT_HEADER, 1);
		    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		    $contents = curl_exec($ch);
		    curl_close($ch);
	   	   	if ($contents && !preg_match("/(404|463)/", $contents)){
	   	   	   	$ico = new Ico($icofile); 
				$TotalIcons = $ico->TotalIcons();
				if($TotalIcons){
					$img = $ico->GetIcon(0);
					if($img && imagePng ($img,$store_file)){
						$file_exists = 1;
					}
					imageDestroy($img);
				}
	   	   	}
   		}
   		if($file_exists){
   			header("Location:".U("static@/".$path));
   		}else{
   			tFile::copy(ROOT_PATH."www/static/public/images/favicon.gif",$store_file);
   			header("Location:".U("static@/public/images/favicon.gif"));
   		}
	}
	public  function get_domain_tips(){
		global $uid;
		$num = M("cart")->get_one("uid=$uid AND status=0 AND indel=0","count(*)");
		tAjax::json_success($num);
	}
	//快速添加域名记录 @ www
	public function quick_addrecord(){
		$domain_ids = R("domain_ids","string");
		$domain_id_arr = explode(",",$domain_ids);
		$RRtype  = R("RRtype","string");
		$RRvalue = R("RRvalue","string");
		foreach($domain_id_arr as $domain_id){
			 foreach(array("","www") as $RRname){
				 $res = SDK::web_api("/DomainRecord/EditByUid",array(
					 "RRtype"      => $RRtype,
					 "RRname"     =>$RRname,
					 "RRvalue"     =>$RRvalue,
					 "acl"             => "any",
					 "acltype"      => "DI",
					 "domain_id" => $domain_id,
					 "record_id"   => 0,
				 ));
				 usleep(500000);
			 }
		}
		echo "";
	}
	//刷新域名记录
	public function refresh(){
		global $uid;
		$domain = R("domain","string");
		$ret = SDK::web_api("/Domain/Refresh",array("domain"=>$domain,"uid"=>$uid));
		die("");
	}
	//刷新域名自定义线路
	public function refresh_line(){
		global $uid;
		$domain = R("domain","string");
		$ns_group = R("ns_group","string");
		$acl    = R("acl","int");
		$do     = R("do","string");
		if($do == "all"){
			$ret = SDK::web_api("/Domain/RefreshCustLine",array("domain"=>$domain,"ns_group"=>$ns_group));
		}else{
			$ret = SDK::web_api("/Domain/RefreshCustLineOne",array("domain"=>$domain,"acl"=>$acl));
		}
		die("");
	}
	//域名找回第一步
	public function domain_find(){
		global $uid,$timestamp;
		$domain = R("domain","string");
		if (tUtil::check_hash()) {
			//设置最大执行时间300秒
			set_time_limit(300);
			//验证域名合法性
			if (empty($domain) || !tValidate::is_domain($domain)) {
				tAjax::json_error("请输入合法域名！");
			}
			//验证域名是否已经属于自己
			$domainRow = M("domain")->get_row("domain = '{$domain}'");
			if (!isset($domainRow['domain_id'])) {
				tAjax::json_error("该域名不在我们这边！");
			}
			//验证是否在30分钟内重复提交
			$domainFind = M("domain_find")->get_row("domain = '{$domainRow['domain']}' AND status = 0");
			if (isset($domainFind['domain_id']) && (time() - $domainFind['dateline'] <= 60*30)) {
				tAjax::json_error("两次找回域名时间太短！");
			}
			//验证域名是否存在八戒NDS
			if ($domainRow['uid'] == $uid) {
				tAjax::json_error("该域名已经属于您,不需要找回！");
			}
			//dns工具find邮箱
			//$query=new IPWhois();
			//$res = $query->query($domain);
			$res =  DNSapi::whois_email($domain);
			if (count($res)>0) {
				$email = current($res);
				//域名找回表添加数据
				$data = array(
					'domain_id' => $domainRow['domain_id'],
					'domain'   	=> $domainRow['domain'],
					'email' 	=> $email,
					'status' 	=> 0,
					'dateline' 	=> $timestamp,
					'ouid'  	=> $domainRow['uid'],
				);
				$addResult = M("domain_find")->set_data($data)->add();
				if ($addResult) {
					//发送邮件进行认证
					$sendRes = C("user")->send_mail(array("type"=>"domainfind",'domain'=>$domainRow['domain'],'id'=>$addResult),$uid,$email);
					if ($sendRes && $sendRes['statusCode'] == 200) {
						tAjax::json_success($email);
					}else{
						tAjax::json_error("邮箱发送失败");
					}
				}else{
					tAjax::json_error("服务器返回错误");
				}				
			}else{
				tAjax::json_error("未找到注册邮箱,域名可能已设置隐私保护！");
			}
		}else{
			//邮件发送成功提示页面
			$email = R("email","string");
			if ($email) {
				I("邮件已发送到域名注册邮箱 {$email}，请打开邮箱操作完成域名找回",U("account@/domains/index"),"success",10);
			}
		}
	}
	//域名找回第二步
	public function domain_find2(){
		global $uid,$timestamp;

		$thash = R("thash","string");
		$find = array(
			'id'					 => R("id","int"),
			"uid"         		 => R("uid","int"),
			"email"     		 => R("email","string"),
			"domain"         => R("domain","string"),
			"dateline"   		 => R("dateline","int"),
		);
		$uri  = tHash::uri($find);
		if(!tHash::chk_uri($thash,$find)){
			I("未通过验证请重新操作域名找回",U("account@/domains/index"));die();
		}
		//验证一天有效期
		if(($timestamp - $find['dateline']) > 24*60*60){
			I("找回域名邮件已过期",U("account@/domains/index"),"error",5);die();
		}
		$domainRow = M("domain")->get_row("domain = '{$find['domain']}'");
		if (!isset($domainRow['domain_id'])) {
			I("域名不存在",U("account@/domains/index"),"error",5);die();
		}
		if ($uid == $domainRow['uid']) {
			I("域名已经找回",U("account@/domains/index"),"success",8);die();
		}
		$data = array(
			'uid'						=>$find['uid'],
			'lastupdate'			=>$timestamp,
			'records'				=>($domainRow['inns'] == 0)?0:$domainRow['records'],
			'service_group'	=>'free',
			"ns_group"			=>'free'
		);
		$res = M("domain")->set_data($data)->update("domain = '{$find['domain']}'");
		if ($res) {
			//删除域名下的记录
			if ($domainRow['inns'] == 0) {
				M("domain_record")->del("domain = '{$domainRow['domain']}'");
			}
			//删除域名下的监控以及监控记录
			$monitorRow = M("domain_monitor")->get_row("domain = '{$domainRow['domain']}'");
			if (isset($monitorRow['monitor_id'])) {
				M("domain_monitor")->del("domain = '{$domainRow['domain']}'");
				M("domain_monitor_error")->del("monitor_id = '{$monitorRow['monitor_id']}'");
				M("domain_monitor_record")->del("monitor_id = '{$monitorRow['monitor_id']}'");
			}
			//更改域名找回表
			M("domain_find")->set_data(array('status'=>1,'uid'=>$find['uid'],'fhash'=>$uri))->update("id = '{$find['id']}'");

			//写入域名日志
			M("@domain")->log("域名找回","找回域名：{$domainRow['domain']}",array('domain_id'=>$domainRow['domain_id'],'domain'=>$domainRow['domain']));

			I("恭喜您，找回域名成功",U("account@/domains/index"),"success",5);
		}else{
			I("找回域名失败，请重新找回",U("account@/domains/index"),"error",5);
		}
	}
	//域名过户提示
	public function domain_transfer(){
		I("域名过户操作完成，请稍后刷新",U("account@/domains/index"),"success",5);
	}
}
?>