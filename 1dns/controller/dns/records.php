<?php
class records extends tController{
	public  $layout = "records";
	private $domain = "";
	private $domain_row = array();
    private $domain_uid = 0;
	public function __construct(){
		global $timestamp,$realip;
		parent::__construct("records");
		$domain         = tSafe::get("dns_domain");
		if(empty($domain)){
			$domain = tCookie::get("dns_domain");
		}
		if($domain){
			$domain_row = M("@domain")->get($domain);
		}
		if(isset($domain_row['domain_id'])){
			$this->domain_row = $domain_row;
			$this->domain     = $domain_row['domain'];
			$this->domain_uid = $domain_row['uid'];
		}else{
			$this->domain = "";
			$this->domain_row = array();
			$this->domain_uid =0;
		}
	}
	//登录
	public function index(){
		//已经登录直接编辑
		if($this->domain_uid){
			$this->redirect(U("/records/records"));
		}
		if(tUtil::check_hash()){
			$domain = R("domain","string");
			$dpass  = R("dpass","string");
            $domain = strtolower($domain);
            if(!tValidate::is_domain($domain)){
				tAjax::json_error("请输入合法的域名!");
			}
			if(strlen($dpass) < 6 || strlen($dpass)>18){
				tAjax::json_error("密码为6-18位!");
			}

			$domain_row = M("@domain")->get($domain);
			if(isset($domain_row['domain_id'])){
				$domain = $domain_row['domain'];
				if(empty($domain_row['password'])){
					tAjax::json_error("该域名不支持密码解析管理");
				}
				if(md5($dpass) !== $domain_row['password']){
					tAjax::json_error("管理密码出错！");
				}
				if($domain_row['indel'] == 1){
					tAjax::json_error("此域名已被系统锁定");
				}
				if($domain_row['inlock'] == 1){
					tAjax::json_error("此域名已临时锁定");
				}
				tSafe::set("dns_domain",$domain);
				//设置自动登录
				$remember = R("record","int");
				if($remember){					
					tCookie::set("dns_domain",$domain,30*86400);		
				}
				tAjax::json(array(
						"error"=>0,
						"message"=>"登录成功！",
						"callback"=>U("/records/records"),
					));
			}else{
				tAjax::json_error("域名不存在!");
			}
		}else{
			$this->display();
		}
	}
	public function records(){
		$this->__check();

		$service_group = M("@domain_service")->get_cache($this->domain_row['service_group']);	
		$this->assign("service_group",$service_group);
		$this->assign("my_acls",explode(";",$service_group['acls']));
		$this->assign("domain_row",$this->domain_row);
		$this->assign("domain",$this->domain);
		$this->assign("domain_cn",$this->domain_row['domain_cn']);
		$this->assign("domain_id",$this->domain_row['domain_id']);
		$this->display();
	}
	public function records_count(){
		$this->__check();
		//获取主机记录
		$records = M("@domain_record")->fetch($this->domain);
		$this->assign('RRname',$records);
		$this->assign("domain_row",$this->domain_row);
		$this->assign("domain",$this->domain);
		$this->assign("domain_cn",$this->domain_row['domain_cn']);
		$this->assign("domain_id",$this->domain_row['domain_id']);
		$this->display();
	}
	public function api(){
		$this->__check();

		$action = R("action","string");
		$action_list = array("Domain.Maps","DomainRecord.ScanImport","DomainRecord.EditByUid","DomainRecord.GetListByDomain","DomainRecord.Del","DomainRecord.Status");
		if(in_array($action,$action_list)){
			unset($_GET['action']);
			$ret = SDK::web_api("/".str_replace(".", "/", $action), $_GET, $this->domain_uid);
			$ret = JSON::encode($ret);
			die($ret);
		}else{
			die("no method");
		}
	}
	public function logout(){
		tSafe::uset("domain");
		tCookie::destroy(array('style'));
		$this->redirect("/records");
	}
	private function __check(){
		//未登录跳登录
		if(empty($this->domain_uid)){
			$this->redirect(U("/records"));
		}
		if(isset($this->domain_row['indel']) && $this->domain_row['indel']){
			tSafe::uset("domain");
			tCookie::destroy(array('style'));
			I('此域名已被系统锁定',U("dns@/records"));
		}
		if(isset($this->domain_row['indel']) && $this->domain_row['inlock']){
			tSafe::uset("domain");
			tCookie::destroy(array('style'));
			I('此域名已临时锁定',U("dns@/records"));
		}
	}	
}