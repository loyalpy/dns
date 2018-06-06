<?php
/**
 * 求职招聘管理
 * by Thinkhu 2014 
 */
class rc_manager extends UCAdmin{
	//企业
	public function company(){
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/rc_manager/company?do=get");
		$condi = array(
				"keyword"   => R("keyword","string"),
				"startdate" => R("startdate","string"),
				"enddate"   => R("enddate","string"),
		);
		
		$where = "1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND a.dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND a.dateline<=".strtotime($v)):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND a.uid='{$v}'";
					}else{
						$where .= $v?" AND (a.company_name LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND a.{$k}='{$v}'":"";
					break;
			}
		}
		
		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['fields'] = "a.*";
			$c['fields'] .= ",b.linker,b.mobile,b.email,b.tel";
			$c['fields'] .= ",c.views,c.tuis";
			$c['fields'] .= ",d.regdateline,d.logdateline,d.utype,d.ulevel,d.urole,d.inlock,d.source";
			$c['fields'] .= ",e.balance,e.point,e.sms,e.resumes";
			$c['join'] = " LEFT JOIN company_contact AS b ON a.company_id=b.company_id LEFT JOIN company_credit AS c ON a.company_id=c.company_id LEFT JOIN user AS d ON a.uid = d.uid LEFT JOIN user_account AS e ON a.uid = e.uid";
			$c['order'] = "a.dateline DESC";
			$result = Q("company AS a")->get_list($c,$pageurl);
			$uids = array();
			foreach($result['list'] as $key => $v){
				$uids[] = $v['uid'];
			}
			//读出所有认证
			if($uids){
				$tmps = $rzs = array();
				$tmps = M("rz")->query("uid IN('".implode("','",$uids)."')");
				foreach($tmps as $k2=>$v2){
					if(!isset($rzs[$v2['uid']])){
						$rzs[$v2['uid']] = array();
					}
					$rzs[$v2['uid']][$v2['name']] = $v2;
				}
			}

			foreach($result['list'] as $key => $v){
				$result['list'][$key]['rzs'] = isset($rzs[$v['uid']])?$rzs[$v['uid']]:array();
			}

			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			$result['error']   = 0;
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		//处理角色
		$tmplist =  M("user_role")->query("","id,company,name","company ASC");
		$role_res = $rolelist = array();
		foreach($tmplist as $v){
			$rolelist[$v['id']] = $v;
			if($v['id'] > 1){
				$role_res[] = array("v"=>"{$v['name']}","key"=>$v['id']);
			}
		}
		//处理会员等级
		$ulevel = $ulevel_res = array();
		$utypes = C("user")->get_utype();
		foreach($utypes as $k=>$v){
			$ulevel[$k] = C("user")->get_ulevel($k);
			$ulevel_res[$k] = array();
			if($ulevel[$k]){
				foreach($ulevel[$k] as $k2=>$v2){
					$ulevel_res[$k][] = array("v"=>"{$v2['alias']}","key"=>$v2['ident']);
				}
			}
		}
		$this->assign("pageurl",$pageurl);
		$this->assign("utypes",$utypes);
		$this->assign("rolelist",$rolelist);
		$this->assign("role_res",$role_res);
		$this->assign("ulevel",$ulevel);
		$this->assign("ulevel_res",$ulevel_res);
		$this->assign("condi",$condi);
		$this->display();
	}
	//职介
	public function medier(){
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/rc_manager/medier?do=get");
		$condi = array(
				"keyword"   => R("keyword","string"),
				"startdate" => R("startdate","string"),
				"enddate"   => R("enddate","string"),
		);
	
		$where = "1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND a.dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND a.dateline<=".strtotime($v)):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND a.uid='{$v}'";
					}else{
						$where .= $v?" AND (a.medier_name LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND a.{$k}='{$v}'":"";
					break;
			}
		}
	
		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['fields'] = "a.*";
			$c['fields'] .= ",b.linker,b.mobile,b.email,b.tel";
			$c['fields'] .= ",c.resumes,c.views,c.tuis";
			$c['fields'] .= ",d.regdateline,d.logdateline,d.utype,d.ulevel,d.urole,d.inlock,d.source";
			$c['fields'] .= ",e.balance,e.point";
			$c['join'] = " LEFT JOIN medier_contact AS b ON a.medier_id=b.medier_id LEFT JOIN medier_credit AS c ON a.medier_id=c.medier_id LEFT JOIN user AS d ON a.uid = d.uid LEFT JOIN user_account AS e ON a.uid = e.uid";
			$c['order'] = "a.dateline DESC";
			$result = Q("medier AS a")->get_list($c,$pageurl);

			$uids = array();
			foreach($result['list'] as $key => $v){
				$uids[] = $v['uid'];
			}
			//读出所有认证
			if($uids){
				$tmps = $rzs = array();
				$tmps = M("rz")->query("uid IN('".implode("','",$uids)."')");
				foreach($tmps as $k2=>$v2){
					if(!isset($rzs[$v2['uid']])){
						$rzs[$v2['uid']] = array();
					}
					$rzs[$v2['uid']][$v2['name']] = $v2;
				}
			}

			foreach($result['list'] as $key => $v){
				$result['list'][$key]['rzs'] = isset($rzs[$v['uid']])?$rzs[$v['uid']]:array();
			}



			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			$result['error']   = 0;
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		//处理角色
		$tmplist =  M("user_role")->query("","id,company,name","company ASC");
		$role_res = $rolelist = array();
		foreach($tmplist as $v){
			$rolelist[$v['id']] = $v;
			if($v['id'] > 1){
				$role_res[] = array("v"=>"{$v['name']}","key"=>$v['id']);
			}
		}
		//处理会员等级
		$ulevel = $ulevel_res = array();
		$utypes = C("user")->get_utype();
		foreach($utypes as $k=>$v){
			$ulevel[$k] = C("user")->get_ulevel($k);
			$ulevel_res[$k] = array();
			if($ulevel[$k]){
				foreach($ulevel[$k] as $k2=>$v2){
					$ulevel_res[$k][] = array("v"=>"{$v2['alias']}","key"=>$v2['ident']);
				}
			}
		}
		$this->assign("pageurl",$pageurl);
		$this->assign("utypes",$utypes);
		$this->assign("rolelist",$rolelist);
		$this->assign("role_res",$role_res);
		$this->assign("ulevel",$ulevel);
		$this->assign("ulevel_res",$ulevel_res);
		$this->assign("condi",$condi);
		$this->display();
	}
	//企业职介认证审核
	public function rz_sh(){
		global $timestamp;
		$uuid = R("uuid","int");
		if(empty($uuid)){
			tAjax::json_error("请选择要认证的用户");
		}
		$items = array("idcard","zhizhao","xukezheng","chengruoshu");
		if(tUtil::check_hash()){
			foreach($items as $v){
				$status = R("{$v}","int");
				if($status){
					M("rz")->set_data(array("status"=>$status))->update("uid={$uuid} AND name='{$v}'");
				}
			}
			tAjax::json(array("error"=>0,"message"=>"操作成功","callback"=>"reload"));
		}else{
			$result = array("uuid"   => $uuid);
			

			
			$rzs = array();
			$tmps = M("rz")->query("uid = {$uuid}");
			foreach($tmps as $k2=>$v2){
				$rzs[$v2['name']] = $v2;
			}
			foreach($items as $v){
				$result[$v] = isset($rzs[$v])?($rzs[$v]['status']>1?$rzs[$v]['status']:3):0;
				$result[$v."_path"] = isset($rzs[$v])?("{$rzs[$v]['name_no']}&nbsp;<a href='".U("static@").$rzs[$v]['path']."' target='_blank'>查看上传文件</a>"):"";
			}
			tAjax::json($result);
		}
	}
	//简历
	public function resume(){
		global $timestamp;
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/rc_manager/resume?do=get");
		$condi = array(
				"keyword"   => R("keyword","string"),
				"startdate" => R("startdate","string"),
				"enddate"   => R("enddate","string"),
				"job_cate"  => R("job_cate","int"),
				"job_age"   => R("job_age","int"),
				"sex"       => R("sex","int"),
				"job_salary"=> R("job_salary","int"),
				"job_area"  => R("job_area","int"),
				"job_edu"   => R("job_edu","int"),
				"age"       => R("age","string"),
				"tag_resume"  => R("tag_resume","int"),
				"inoa"      => R("inoa","int"),
		);
	
		$where = "1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "inoa":
					$where .= $v?(" AND a.inoa = ".($v-1).""):"";
					break;
				case "age":
					if($v){
						$v_arr = explode("_",$v);
						$v_arr[0] = intval(tTime::get_datetime('Y',$timestamp)) - $v_arr[0];
						$v_arr[1] = intval(tTime::get_datetime('Y',$timestamp)) - $v_arr[1];
						$where .= (" AND a.birth >={$v_arr[1]},0 AND a.birth>={$v_arr[0]}");
					}
					break;
				case "job_cate":
					$where .= $v?(" AND FIND_IN_SET('{$v}',a.job_cate)"):"";
					break;
				case "job_area":
					$where .= $v?(" AND FIND_IN_SET('{$v}',a.job_area)"):"";
					break;
				case "tag_resume":
					$where .= $v?(" AND FIND_IN_SET('{$v}',a.tag_resume)"):"";
					break;
				case "startdate":
					$where .= $v?(" AND a.dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND a.dateline<=".strtotime($v)):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND a.add_uid='{$v}'";
					}else{
						$where .= $v?" AND (a.name LIKE '%{$v}%' OR a.job_name LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND a.{$k}='{$v}'":"";
					break;
			}
		}
	
		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['fields'] = "a.*";
			$c['fields'] .= ",c.views,c.tuis";
			$c['fields'] .= ",d.emailrz,d.mobilerz,d.mobile,d.uname,d.email,d.regdateline,d.logdateline,d.utype,d.ulevel,d.urole,d.inlock,d.source";
			$c['fields'] .= ",e.uid AS medier_uid,e.medier_name";
			$c['join'] = "LEFT JOIN resume_credit AS c ON a.resume_id=c.resume_id LEFT JOIN user AS d ON a.uid = d.uid";// LEFT JOIN user_account AS e ON a.uid = e.uid";
			$c['join'] .= " LEFT JOIN medier AS e ON a.add_uid=e.uid";
			$c['order'] = "a.dateline DESC";
			
			$exdo = R("exdo","string");
			if($exdo == "export"){
				set_time_limit(1800);
				
				$c['pagesize'] = 1000;
				$result = Q("resume AS a")->get_list($c,$pageurl);
				if($result['list']){
					foreach($result['list'] as $key=>$v){
						$result['list'][$key]['age'] = intval(tTime::get_datetime("Y")) - intval(substr($v['birth'],0,4));
						$result['list'][$key]['avatar'] = tFun::resume_avatar($v['idcard'],$v['uid']);
						$result['list'][$key]['resume_id_str'] = tUtil::numstr($v['resume_id']);						
						$result['list'][$key]['add_userinfo'] = $v['add_uid']?C("user")->get_cache_userinfo($v['add_uid']):array();
					}
				}
				$excel = new tExcel(array(
						"A" => array("name"=>"姓名","width"=>15,"bold"=>1,"color"=>"black"),
						"B" => array("name"=>"身份证号","width"=>25,"bold"=>1,"color"=>"black"),
						"C" => array("name"=>"性别","width"=>10,"bold"=>1,"color"=>"black"),
						"D" => array("name"=>"年龄","width"=>10,"bold"=>1,"color"=>"black"),
						"E" => array("name"=>"籍贯","width"=>20,"bold"=>1,"color"=>"black"),
						"F" => array("name"=>"手机","width"=>15,"bold"=>1,"color"=>"black"),
						"G" => array("name"=>"工种","width"=>30,"bold"=>1,"color"=>"black"),
						"H" => array("name"=>"地区","width"=>20,"bold"=>1,"color"=>"black"),
						"I" => array("name"=>"薪资要求","width"=>15,"bold"=>1,"color"=>"black"),
						"J" => array("name"=>"录入职介","width"=>22,"bold"=>1,"color"=>"black"),
						"K" => array("name"=>"录入账号","width"=>20,"bold"=>1,"color"=>"black"),
						"L" => array("name"=>"录入时间","width"=>20,"bold"=>1,"color"=>"black"),
				),array());
				$excel_data = array();
				foreach($result['list'] as $key=>$v){
					$excel_data[] = array(
							"{$v['name']} ",
							""."{$v['idcard']} ",
							"".($v['sex'] == 1?"男":"女"),
							"{$v['age']} ",
							"{$v['city_from']} ",
							"{$v['mobile']} ",
							"{$v['job_name']} /".(tFun::get_conf("job_cate","{$v['job_cate']}")),//g
							"".(tFun::get_conf("city","{$v['job_area']}")),//h
							"".(isset(App::$data['data_config']['job_salary'][$v['job_salary']])?App::$data['data_config']['job_salary'][$v['job_salary']]:"未定义"),//i
							"".($v['add_uid']?$v['medier_name']:""),//k
							"".($v['add_uid']?$v['add_userinfo']['uname']:"自动注册"),//l
							"".tTime::get_datetime("Y-m-d",$v['dateline']),
							);
				};
				$excel->write_sheet($excel_data,0);
				if($result['totalpage'] > 1){
					for($page = 2;$page<=$result['totalpage'];$page=$page+1){
						$c['pagesize'] = 1000;
						$c['page']     = $page;
						$result = Q("resume AS a")->get_list($c,$pageurl);
						if($result['list']){
							foreach($result['list'] as $key=>$v){
								$result['list'][$key]['age'] = intval(tTime::get_datetime("Y")) - intval(substr($v['birth'],0,4));
								$result['list'][$key]['avatar'] = tFun::resume_avatar($v['idcard'],$v['uid']);
								$result['list'][$key]['resume_id_str'] = tUtil::numstr($v['resume_id']);
								$result['list'][$key]['add_userinfo'] = $v['add_uid']?C("user")->get_cache_userinfo($v['add_uid']):array();
							}
						}
						$excel_data = array();
						foreach($result['list'] as $key=>$v){
							$excel_data[] = array(
									"{$v['name']} ",
									""."{$v['idcard']} ",
									"".($v['sex'] == 1?"男":"女"),
									"{$v['age']} ",
									"{$v['city_from']} ",
									"{$v['mobile']} ",
									"{$v['job_name']} /".(tFun::get_conf("job_cate","{$v['job_cate']}")),//g
									"".(tFun::get_conf("city","{$v['job_area']}")),//h
									"".(isset(App::$data['data_config']['job_salary'][$v['job_salary']])?App::$data['data_config']['job_salary'][$v['job_salary']]:"未定义"),//i
									"".($v['add_uid']?$v['medier_name']:""),//k
									"".($v['add_uid']?$v['add_userinfo']['uname']:"自动注册"),//l
									"".tTime::get_datetime("Y-m-d",$v['dateline']),
									);
							
						}
						$excel->write_sheet($excel_data,($page-1));
					}
				}
				$excel->export("export_resume");
				exit();
			}else{
				$result = Q("resume AS a")->get_list($c,$pageurl);
				if($result['list']){
					foreach($result['list'] as $key=>$v){
						$result['list'][$key]['age'] = intval(tTime::get_datetime("Y")) - intval(substr($v['birth'],0,4));
						$result['list'][$key]['avatar'] = tFun::resume_avatar($v['idcard'],$v['uid']);
						$result['list'][$key]['resume_id_str'] = tUtil::numstr($v['resume_id']);						
						$result['list'][$key]['add_userinfo'] = $v['add_uid']?C("user")->get_cache_userinfo($v['add_uid']):array();
					}
				}
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
				$result['error']   = 0;
				tAjax::json($result);
			}
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		//处理角色
		$tmplist =  M("user_role")->query("","id,company,name","company ASC");
		$role_res = $rolelist = array();
		foreach($tmplist as $v){
			$rolelist[$v['id']] = $v;
			if($v['id'] > 1){
				$role_res[] = array("v"=>"{$v['name']}","key"=>$v['id']);
			}
		}
		//处理会员等级
		$ulevel = $ulevel_res = array();
		$utypes = C("user")->get_utype();
		foreach($utypes as $k=>$v){
			$ulevel[$k] = C("user")->get_ulevel($k);
			$ulevel_res[$k] = array();
			if($ulevel[$k]){
				foreach($ulevel[$k] as $k2=>$v2){
					$ulevel_res[$k][] = array("v"=>"{$v2['alias']}","key"=>$v2['ident']);
				}
			}
		}
		$this->assign("pageurl",$pageurl);
		$this->assign("utypes",$utypes);
		$this->assign("rolelist",$rolelist);
		$this->assign("role_res",$role_res);
		$this->assign("ulevel",$ulevel);
		$this->assign("ulevel_res",$ulevel_res);
		$this->assign("condi",$condi);
		$this->display();
	}
	//岗位
	public function joblist(){
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/rc_manager/joblist?do=get");
		$condi = array(
				"keyword"   => R("keyword","string"),
				"startdate" => R("startdate","string"),
				"enddate"   => R("enddate","string"),
				"job_cate"  => R("job_cate","int"),
				"job_age"   => R("job_age","int"),
				"sex"       => R("sex","int"),
				"job_salary"=> R("job_salary","int"),
				"job_area"  => R("job_area","int"),
				"job_edu"   => R("job_edu","int"),
				"age"       => R("age","string"),
				"tag_post"  => R("tag_post","int"),
		);
		
		$where = "1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "age":
					if($v){
						$v_arr = explode("_",$v);
						$where .= (" AND a.age_min <={$v_arr[0]} AND a.age_max>={$v_arr[1]}");
					}
					break;
				case "tag_post":
					$where .= $v?(" AND FIND_IN_SET('{$v}',a.tag_post)"):"";
					break;
				case "startdate":
					$where .= $v?(" AND a.dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND a.dateline<=".strtotime($v)):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND a.uid='{$v}'";
					}else{
						$where .= $v?" AND (a.job_name LIKE '%{$v}%' OR a.company_name LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND a.{$k}='{$v}'":"";
					break;
			}
		}
		
		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['fields'] = "a.*";
			$c['order'] = "a.dateline DESC";
			$result = Q("job AS a")->get_list($c,$pageurl);
			if($result['list']){
				foreach($result['list'] as $key=>$v){
					$result['list'][$key]['job_id_str'] = tUtil::numstr($v['job_id']);
				}
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			$result['error']   = 0;
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		
		$this->assign("pageurl",$pageurl);
		$this->assign("condi",$condi);
		$this->display();
	}
	//服务岗位
	public function servicejob(){
		$do = R("do","string");
		$tp = R("tp","int");

		$page = R("page","int");
		$page = $page?$page:1;

		$tp = in_array($tp,array(1,2))?$tp:1;
		$pageurl = U("/rc_manager/servicejob?do=get&tp=$tp");
		$condi = array(
				"keyword"   => R("keyword","string"),
				"startdate" => R("startdate","string"),
				"enddate"   => R("enddate","string"),
				"job_cate"  => R("job_cate","int"),
				"job_age"   => R("job_age","int"),
				"sex"       => R("sex","int"),
				"job_salary"=> R("job_salary","int"),
				"job_area"  => R("job_area","int"),
				"job_edu"   => R("job_edu","int"),
				"age"       => R("age","string"),
				"tag_post"  => R("tag_post","int"),
		);
		
		$where = "a.tp=$tp";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND a.st>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND a.et<=".strtotime($v)):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND b.uid='{$v}'";
					}else{
						$where .= $v?" AND (a.name LIKE '%{$v}%' OR b.company_name LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND b.{$k}='{$v}'":"";
					break;
			}
		}
		
		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['fields'] = "a.*,b.job_name,b.company_name,b.sex,b.applys,b.invites,b.views,b.job_age,b.job_edu,b.job_salary,b.job_area,b.lastupdate";
			$c['order'] = "a.sort DESC,a.dateline DESC";
			$c['join']  = "LEFT JOIN job AS b ON a.job_id = b.job_id";
			$result = Q("job_service AS a")->get_list($c,$pageurl);
			if($result['list']){
				foreach($result['list'] as $key=>$v){
					$result['list'][$key]['job_id_str'] = tUtil::numstr($v['job_id']);
				}
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			$result['error']   = 0;
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		
		$this->assign("pageurl",$pageurl);
		$this->assign("condi",$condi);
		$this->assign("tp",$tp);
		$this->display();
	}
	//服务岗位编辑与修改
	public function servicejob_edit(){
		global $timestamp;
		$job_id = R("job_id","int");
		$id     = R("id","int");
		$tp     = R("tp","int");
		if(!in_array($tp,array(1,2))){
			tAjax::json_error("非法推广");
		}
		if(tUtil::check_hash()){
			if($id){
				$data = M("job_service")->get_row("id=$id");
				if(!isset($data['id'])){
					tAjax::json_error("数据未找到!");
				}
				if($data['et'] && $data['et']<$timestamp){
					tAjax::json_error("推广已结束,不能修改");
				}
				$new_data = array(
						"name" => R("name","string"),
						"st"     => R("st","string"),
						"et"     => R("et","string"),
						"status" => R("status","int"),
						"sort"   => R("sort","int"),
				);
				if(empty($new_data['name'])){
					tAjax::json_error("推广名不能为空");
				}
				$new_data['st'] = $new_data['st']?strtotime($new_data['st']):$timestamp;
				$new_data['et'] = $new_data['et']?strtotime($new_data['et']):0;

				if($new_data['et'] && $new_data['et'] < $new_data['st']){
					tAjax::json_error("推广结束时间必须大于开始时间!");
				}

				M("job_service")->set_data($new_data)->update("id='{$id}'");
				tAjax::json(array("error"=>0,"message"=>"保存成功！","callback"=>"reload"));
			}else{
				$jobdata = M("job")->get_row("job_id='{$job_id}'");
				if(!isset($jobdata['job_id'])){
					tAjax::json_error("数据未找到!");
				}
				$lastservice_id = M("job_service")->get_one("job_id='{$job_id}' AND tp='{$tp}' AND (et>'{$timestamp}' OR et = 0)","id");
				if($lastservice_id){
					tAjax::json_error("正在推广服务中,不能添加!");
				}
				$new_data = array(
						"name"   => R("name","string"),
						"company_id" => $jobdata['company_id'],
						"company_name" => $jobdata['company_name'],
						"tp"     => $tp,
						"st"     => R("st","string"),
						"et"     => R("et","string"),
						"status" => R("status","int"),
						"sort"   => R("sort","int"),
						"job_id" => $jobdata['job_id'],
						"dateline" => $timestamp,
				);
				if(empty($new_data['name'])){
					tAjax::json_error("推广名不能为空");
				}
				$new_data['st'] = $new_data['st']?strtotime($new_data['st']):$timestamp;
				$new_data['et'] = $new_data['et']?strtotime($new_data['et']):0;

				if($new_data['et'] && $new_data['et'] < $new_data['st']){
					tAjax::json_error("推广结束时间必须大于开始时间!");
				}
				$ret = M("job_service")->set_data($new_data)->add();
				if($ret){
					tAjax::json(array("error"=>0,"message"=>"添加成功！","callback"=>"close"));
				}else{
					tAjax::json_error("添加失败！");
				}
			}
		}else{
			if($id){
				$data = M("job_service")->get_row("id=$id");
				if(!isset($data['id'])){
					tAjax::json_error("数据未找到!");
				}
				if($data['et'] && $data['et']<$timestamp){
					tAjax::json_error("推广已结束,不能修改");
				}
				$data['st'] = $data['st']?tTime::get_datetime("Y-m-d H:i:s",$data['st']):"";
				$data['et'] = $data['et']?tTime::get_datetime("Y-m-d H:i:s",$data['et']):"";
				tAjax::json($data);
			}elseif($job_id){
				$jobdata = M("job")->get_row("job_id='{$job_id}'");
				if(!isset($jobdata['job_id'])){
					tAjax::json_error("数据未找到!");
				}
				$lastservice_id = M("job_service")->get_one("job_id='{$job_id}' AND tp='{$tp}' AND (et>'{$timestamp}' OR et = 0)","id");
				if($lastservice_id){
					$data = M("job_service")->get_row("id=$lastservice_id");
				}
				if(!isset($data['id'])){
					$data = array(
							"id"  => 0,
							"st"  => tTime::get_datetime("Y-m-d H:i:s",$timestamp),
							"et"  => "",
							"status" => 1,
							"tp"     => $tp,
							"sort"   => 0,
							"name"   => $jobdata['job_name'],
							"job_id" => $jobdata['job_id'],
						);
				}else{
					$data['st'] = $data['st']?tTime::get_datetime("Y-m-d H:i:s",$data['st']):"";
					$data['et'] = $data['et']?tTime::get_datetime("Y-m-d H:i:s",$data['et']):"";
				}
				tAjax::json($data);
			}
		}
	}
	//服务岗位暂停与启用
	public function servicejob_status(){
		$ids = $this->__batch_ids();
		$status = R("status","int");
		if(!in_array($status,array(0,1))){
			tAjax::json_error("错误的状态选项！");
		}
		$where = "id IN(".implode(",",$ids).")";
		$sql = "UPDATE `@job_service` SET status = '{$status}' WHERE {$where}";
		$count = Sq($sql);
		if($count){
			tAjax::json(array("error"=>0,"message"=>"本次操作影响 {$count} 行","data"=>$count,"callback"=>"reload"));
		}else{
			tAjax::json_error("本次操作未作任何影响");
		}
	}
	//岗位操作
	public function joblist_status(){
		$ids = $this->__batch_ids();
		$status = R("status","int");
		if(!in_array($status,array(0,1,2))){
			tAjax::json_error("错误的状态选项！");
		}
		$where = "job_id IN(".implode(",",$ids).")";
		$sql = "UPDATE `@job` SET status = '{$status}' WHERE {$where}";
		$count = Sq($sql);
		if($count){
			tAjax::json(array("error"=>0,"message"=>"本次操作影响 {$count} 行","data"=>$count,"callback"=>"reload"));
		}else{
			tAjax::json_error("本次操作未作任何影响");
		}
	}
	//刷新
	public function joblist_refresh(){
		global $timestamp;
		$ids = $this->__batch_ids();
		$status = R("status","int");
		$where = "status=2 AND job_id IN(".implode(",",$ids).")";
		$sql = "UPDATE `@job` SET lastupdate = '{$timestamp}' WHERE {$where}";
		$count = Sq($sql);
		if($count){
			tAjax::json(array("error"=>0,"message"=>"本次为您刷新 {$count} 行","data"=>$count,"callback"=>"reload"));
		}else{
			tAjax::json_error("本次操作未作任何影响");
		}
	}
	//删除
	public function joblist_del(){
		$ids = $this->__batch_ids();
		$indel = R("indel","int");
		if(!in_array($indel,array(0,1))){
			tAjax::json_error("错误的删除选项！");
		}
		$where = "job_id IN(".implode(",",$ids).")";
		$sql = "UPDATE `@job` SET indel = '{$indel}' WHERE {$where}";
		$count = Sq($sql);
		if($count){
			tAjax::json(array("error"=>0,"message"=>"本次操作影响 {$count} 行","data"=>$count,"callback"=>"reload"));
		}else{
			tAjax::json_error("本次操作未作任何影响");
		}
	}
	
	//悬赏岗位
	public function tuijob(){
		$do = R("do","string");
		$page = R("page","int");
		$page = $page?$page:1;
		$pageurl = U("/rc_manager/tuijob?do=get");
		$condi = array(
				"keyword"   => R("keyword","string"),
				"startdate" => R("startdate","string"),
				"enddate"   => R("enddate","string"),
		);
	
		$where = "1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND a.dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND a.dateline<=".strtotime($v)):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND a.uid='{$v}'";
					}else{
						$where .= $v?" AND (a.tui_name LIKE '%{$v}%' OR b.company_name LIKE '%{$v}%')":"";
					}
					break;
				default:
					$where .= $v?" AND a.{$k}='{$v}'":"";
					break;
			}
		}
	
		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['fields'] = "a.*";
			$c['fields'] .= ",b.views,b.sex,b.age_min,b.age_max,b.job_age,b.job_salary,b.job_area,b.job_edu,b.company_name,b.uid,b.lastupdate";
			$c['fields'] .= ",c.balance";
			$c['order'] = "a.dateline DESC";
			$c['join'] = "LEFT JOIN job AS b ON a.job_id=b.job_id LEFT JOIN user_account AS c ON b.uid = c.uid";
			$result = Q("job_tui AS a")->get_list($c,$pageurl);
			if($result['list']){
				foreach($result['list'] as $key=>$v){
					$result['list'][$key]['tui_id_str'] = tUtil::numstr($v['tui_id']);
				}
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			$result['error']   = 0;
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
	
		$this->assign("pageurl",$pageurl);
		$this->assign("condi",$condi);
		$this->display();
	}
	//悬赏操作
	public function tuijob_status(){
		$ids = $this->__batch_ids();
		$status = R("status","int");
		if(!in_array($status,array(0,1,2,3))){
			tAjax::json_error("错误的状态选项！");
		}
		$where = "tui_id IN(".implode(",",$ids).")";
		$sql = "UPDATE `@job_tui` SET status = '{$status}' WHERE {$where}";
		$count = Sq($sql);
		if($count){
			tAjax::json(array("error"=>0,"message"=>"本次操作影响 {$count} 行","data"=>$count,"callback"=>"reload"));
		}else{
			tAjax::json_error("本次操作未作任何影响");
		}
	}
	//悬赏删除
	public function tuijob_del(){
		$ids = $this->__batch_ids();
		$indel = R("indel","int");
		if(!in_array($indel,array(0,1))){
			tAjax::json_error("错误的删除选项！");
		}
		$where = "tui_id IN(".implode(",",$ids).")";
		$sql = "UPDATE `@job_tui` SET indel = '{$indel}' WHERE {$where}";
		$count = Sq($sql);
		if($count){
			tAjax::json(array("error"=>0,"message"=>"本次操作影响 {$count} 行","data"=>$count,"callback"=>"reload"));
		}else{
			tAjax::json_error("本次操作未作任何影响");
		}
	}
	
	
	//企业简历库
	public function resumelib(){
		
	}
	//职介简历库
	public function medier_resume(){
		
	}
	
	//批量处理IDC
	public function __batch_ids(){
		$ids_str    = R("ids","string");
		$ids = $ids_str?explode(",",$ids_str):array();
		$ids = array_unique($ids);
		if(empty($ids)){
			tAjax::json_error("错误的ID选项");
		}
		return $ids;
	}
	
	//职介简历录入统计
	public function medier_resume_count(){
		global $uid;
		//配置
		$do=R('do','string');
		$pageurl = U("/rc_manager/medier_resume_count?do=get");
		$page = R("page","int");
		$page = $page?$page:1;
		$wherestr = "1";
		$pagesize = 30;
		$condi = array(
				"startdate"     => R("startdate","string"),
				"enddate"       => R("enddate","string"),
				"keyword"       => R("keyword","string")
		);
		//dump($condi);
		if(!empty($condi)){
			foreach($condi as $k=>$v){
				$pageurl .= "&{$k}=".urlencode($v);
				$this->assign($k,$v);
				switch($k){
					case "startdate":
						$wherestr .= $v?(" AND (a.date_key >= '".($v)."')"):"";
						break;
					case "enddate":
						$wherestr .= $v?(" AND (a.date_key <= '".($v)."')"):"";
						break;
					case 'keyword':
						$v = ($v == '关键词')?'':$v;
						if(tValidate::is_int($v)){
							$wherestr .= " AND b.uid='{$v}'";
						}else{
							$wherestr .= $v?" AND (b.medier_name LIKE '%{$v}%')":"";
						}
						break;
					default:
						break;
				}
			}
		}
		switch($do){
			case "get_url":
				tAjax::json(array("error"=>0,"message"=>"获取成功","pageurl"=>$pageurl));
				break;
			case "get":
				//取数据
				$c = array();
				$c['page']     = $page;
				$c['where']    = $wherestr;
				$c['pagesize'] = $pagesize;
				$c['order']    = "a.date_key DESC";
				$c['fields']   = "a.*";
				$c['fields']   .= ",b.medier_name,b.uid";
				$c['fields']   .= ",c.resumes AS total_resumes";
				$c['join']     = " LEFT JOIN medier AS b ON a.medier_id=b.medier_id";
				$c['join']     .= " LEFT JOIN medier_credit AS c ON a.medier_id=c.medier_id";
				$result = Q("medier_countday AS a")->get_list($c,$pageurl);
				$uuinfo = array();
				if($result['list']){
						foreach($result['list'] as $key=>$v){
							$result['list'][$key]['next_date'] = tTime::get_datetime("Y-m-d",strtotime("+1 day",strtotime($v['date_key'])));
							$result['list'][$key]['userinfo'] = C("user")->get_cache_userinfo($v['uid']);
						}
				}
				$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
				tAjax::json($result);
				break;
			default:
				$this->assign("pageurl",$pageurl);
				$this->display();
				break;
		}
	}

}
?>
