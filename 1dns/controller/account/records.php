<?php
class records extends UC{
	public function __construct(){
		parent::__construct('records');
	}
	//域名解析记录列表
	public function records(){
		global $uid;
		$domain     = R("domain","string");
		$res 		  	  = M("domain")->get_row("domain='{$domain}' AND uid=$uid");
        if(!isset($res['domain_id'])){
			//判断如果域名ns在我们这边注册，并且已指向我们，则添加域名
			$register_domain_row = M("register_domain")->get_row("domain = '{$domain}'");
			if (isset($register_domain_row['ns'])) {
				$ns_group = M("domain_ns_group")->query("1","ns","",500);
				$str = "";
				foreach ($ns_group as $k=>$v) {
					$str .= $v['ns'].";";
				}
				if (strpos($str,$register_domain_row['ns']) !== false) {
					$res_add = SDK::web_api("/Domain/AddByUid",array("domain"=>$domain));
					if ($res_add['status'] == 1) {
						$res = M("domain")->get_row("domain='{$domain}' AND uid=$uid");
					}else{
						$this->_msg("域名不存在","请先添加域名{$domain}","/domains/add?domain={$domain}#返回");
					}
				}else{
					$this->_msg("域名不存在","请先添加域名{$domain}","/domains/add?domain={$domain}#返回");
				}
			}else{
				$this->_msg("域名不存在","请先添加域名{$domain}","/domains/add?domain={$domain}#返回");
			}
		}
		$service_group = M("@domain_service")->get_cache($res['service_group']);

		//域名别名绑定
		$domain_bind_row = M("domain_bind")->get_row("uid = '{$uid}' AND domain_bind = '{$domain}'");
		$domain_bind = '';
		if (isset($domain_bind_row['domain_id'])) {
			$domain_bind = M("domain")->get_one("uid = '{$uid}' AND domain_id = '{$domain_bind_row['domain_id']}'","domain");
		}
		//批量导入功能
		$batch_import_records = isset($service_group['data']['batchImportR']['value'])?$service_group['data']['batchImportR']['value']:0;
		$this->assign("batch_import_records",$batch_import_records);

		$this->assign("domain",$domain);
		$this->assign("domain_cn",$res["domain_cn"]);
		$this->assign("domain_id",$res['domain_id']);
		$this->assign("domain_row",$res);
		$this->assign("service_group",$service_group);
		$this->assign("domain_bind",$domain_bind);
		$this->assign("my_acls",explode(";",$service_group['acls']));
		$this->display();
	}
	//域名解析量统计
	public function records_count(){
		global $uid;
		$domain = R("domain","string");

		$res 		  	  = M("domain")->get_row("domain='{$domain}' AND uid=$uid");
		if(!isset($res['domain_id'])){
			$this->_msg("域名不存在","域名不存在","/domains/index#返回");
		}
		//获取主机记录
		$records = M("@domain_record")->fetch($domain);
		$this->assign('RRname',$records);

		$this->assign("domain",$domain);
		$this->assign("domain_cn",$res["domain_cn"]);
		$this->display();
	}
	//域名设置
	public function records_set(){
		global $uid;
		
		$domain = R("domain","string");
		$res = M("domain")->get_row("domain='{$domain}' AND uid=$uid");
		if(!isset($res['domain_id'])){
			$this->_msg("域名不存在","域名不存在","/domains/index#返回");
		}
		$service_group = M("@domain_service")->get_cache($res['service_group']);
		//绑定域名
		$domain_bind = M("domain_bind")->query("uid = '{$uid}' AND domain_id = '{$res['domain_id']}'","domain_bind","id ASC");
		$this->assign("service_group",$service_group);
		$this->assign("domain_bind",array_map('array_shift',$domain_bind));
		$this->assign("domain",$res);
		$this->display();
	}
	//域名解析自定义线路
	public function records_setline(){
		global $uid;

		$domain = R("domain","string");
		$res = M("domain")->get_row("domain='{$domain}' AND uid=$uid");
		if(!isset($res['domain_id'])){
			$this->_msg("域名不存在","域名不存在","/domains/index#返回");
		}
		$this->assign("domain",$res);
		$this->display();
	}
	//导出
	public function Export(){
		$domain = R("domain","string");
		$records = M("@domain_record")->fetch($domain);
		$acllist = C("category","domain_acl")->json(0,'ident');
		$return = "主机名(RRname)\t值(RRvalue)\t记录类型(RRtype)\t线路(acl)\tTTL\r\n";
		foreach($records as $RRname=>$v1){
			foreach($v1 as $RRtype =>$v2){
				foreach($v2 as $acl =>$v3){
					foreach($v3 as $v4){
						$return .= "{$RRname}\t{$v4[0]}\t{$RRtype}\t".(isset($acllist[$acl])?$acllist[$acl]['name']:"默认")."\t{$v4[1]}\r\n";
					}
				}
			}
		}

		header('Content-Type: application/txt;charset=utf-8');
		header('Content-Disposition: attachment; filename="'.$domain.'.txt"');
		file_put_contents('php://output',$return);
	}
	//导入
	public function Import(){
		global $uid;
		$domain = R("domain","string");
		$do = R("do","string");
		$moshi = R("moshi","int");
		switch($do){
			case "import":
				set_time_limit(1800);
				$domain_row = M("domain")->get_row("domain = '{$domain}'");
				if(!isset($domain_row['domain_id'])){
					tAjax::json_error("域名为空，不能导入");
				}
				//判断域名是否被绑定
				$domain_bind = $domain_row['is_cn'] == 1?$domain_row['domain_cn']:$domain_row['domain'];
				if (M("domain_bind")->get_one("uid = '{$uid}' AND domain_bind = '{$domain_bind}'","count(id)")) {
					tAjax::json_error("该域名已被其它域名绑定，请解绑后再进行操作！");
				}
				//判断域名是否已锁定
				if ($domain_row['inlock'] == 1) {
					tAjax::json_error("该域名处于锁定状态，请解锁后再进行操作！");
				}
				//上传附件
				$attach_name = "ipdatafile";
				$error_message = "";
				if(empty($_FILES[$attach_name]) === false){
					$up_obj = new tUpload(10240,array("txt"));
					$file_store_path = ROOT_PATH."attach/tmp/";
					$return_file = "";
					$up_obj->set_dir($file_store_path);
					$upstate = $up_obj->execute();
					if(isset($upstate[$attach_name])){
						if($upstate[$attach_name][0]['flag']==-1){
							$error_message = '上传的文件类型不符合';
						}else if($upstate[$attach_name][0]['flag']==-2){
							$error_message = '大小超过限度';
						}else if($upstate[$attach_name][0]['flag']==1){
							$file_path = $file_store_path.$upstate[$attach_name][0]['name'].".".$upstate[$attach_name][0]['ext'];
							$import_content = file_get_contents($file_path);
							if($import_content == ""){
								$error_message = '文件内容为空！';
							}else{
								$import_arr = explode(";",$import_content);
								if(count($import_arr)<2){
									$import_content = str_replace("\n",";",$import_content);
									$import_arr = explode(";",$import_content);
								}
								if(empty($import_arr) || count($import_arr) == 0){
									$error_message = '没有可用的记录导入！';
								}
							}
							tFile::unlink($file_path);
						}
					}else{
						$error_message = '上传失败！';
					}
				}else{
					$error_message = "上传合法的txt文件";
				}
				//如果有错误则输出错误
				if($error_message){
					tAjax::json_error($error_message);
				}
				//处理导入数据处理
				$inserts = 0;
				$import_arr = array_unique($import_arr);
				foreach($import_arr as $k=>$v){
					$v = trim($v);
					if($k != 0 && $v){
						$data_tmp = explode(" ",preg_replace("/\s+/",' ',$v));
						//导入模式
						if (in_array($moshi,array(1,3))) {
							if (count($data_tmp) == 6) {
								//处理线路
								if (isset($data_tmp[0]) && isset($data_tmp[1]) && isset($data_tmp[2]) && isset($data_tmp[3]) && isset($data_tmp[4]) && isset($data_tmp[5])) {
									switch ($data_tmp[2]){
										case '电信':
											$data_tmp[2] = "中国电信";
											break;
										case '移动':
											$data_tmp[2] = "中国移动";
											break;
										case '联通':
											$data_tmp[2] = "中国联通";
											break;
										case '铁通':
											$data_tmp[2] = "中国铁通";
											break;
									}
									$RRname = $data_tmp[0];
									$RRtype = $data_tmp[1];
									$RRvalue = $data_tmp[3];
									$RRttl = $data_tmp[5];
									$RRmx = $data_tmp[4];
									$acl_name = $data_tmp[2];
								}
							}else{
								tAjax::json_error("文件格式不匹配,请选择正确的导入模式");
							}
						}elseif ($moshi == 2) {
							if (count($data_tmp) == 5) {
								//处理线路
								if (isset($data_tmp[0]) && isset($data_tmp[1]) && isset($data_tmp[2]) && isset($data_tmp[3]) && isset($data_tmp[4])) {
									$RRname = str_replace("#",".",$data_tmp[0]);
									$RRtype = $data_tmp[2];
									$RRvalue = $data_tmp[1];
									$RRttl = $data_tmp[4];
									$RRmx = 0;
									$acl_name = $data_tmp[3];
								}
							}else{
								tAjax::json_error("文件格式不匹配,请选择正确的导入模式");
							}
						}else{
							tAjax::json_error("非法导入模式");
						}

						//RRname 合法性判断
						if($RRtype == 'SRV'){
							if(!tValidate::is_srv_hostname($RRname)){
								break;
							}
						}else{
							if(!in_array($RRname,array("@","*"))){
								//子级判断
								$tmp = explode(".",$RRname);
								if($tmp[0] == "*"){
									unset($tmp[0]);
									if(!tValidate::is_hostname(implode(".",$tmp))){
										break;
									}
								}else{
									if(!tValidate::is_hostname($RRname)){
										break;
									}
								}
								unset($tmp);
							}
						}
						//过滤掉NS
						if ($RRname == "@" && $RRtype == "NS") {
							continue;
						}

						$acl = M("domain_acl")->get_one("name = '{$acl_name}'","ident");
						if ($acl && !M("domain_record")->get_one("domain = '{$domain}' AND domain_id = '{$domain_row['domain_id']}' AND acl = '{$acl}'
								 AND RRname = '{$RRname}' AND RRtype = '{$RRtype}' AND RRvalue = '{$RRvalue}' AND RRttl = '{$RRttl}' AND RRmx = '{$RRmx}'","record_id")) {
							$data = array(
								"acl"	 	        =>  $acl,
								"acltype"		=>  "DI",
								'RRname'		=>  $RRname,
								'RRtype'		=>  $RRtype,
								'RRvalue'		=>  $RRvalue,
								'RRmx'			=>  $RRmx,
								'RRttl'			=>  $RRttl,
								"domain"    => $domain,
								"domain_id"=>$domain_row['domain_id'],
							);
							$res = M('domain_record')->set_data($data)->add();
							//记录值递增+1
							$records = M("domain")->get_one("domain = '{$domain}'","records");
							M('domain')->set_data(array("records"=>$records+1))->update("domain_id='{$domain_row['domain_id']}'");
							if($res){
								$inserts++;
							}
						}
					}
				}
				M("@domain")->queue($domain_row['domain'],"update_record",0);
				tAjax::json(array("error"=>0,"message"=>"成功导入".$inserts."条记录!","callback"=>"reload"));
				break;
			default:
				tAjax::json(array("error"=>0,"message"=>"操作成功","callback"=>"reload"));
				break;
		}
	}
}
?>