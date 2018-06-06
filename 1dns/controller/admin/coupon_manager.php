<?php
class coupon_manager extends UCAdmin{
	//活动代金券
	public function coupon(){
		$do = R("do","string");
		$page = R("page","int");
		$c   = R("c","int");
		$page = $page?$page:1;
		$c = $c?$c:0;
		$pageurl = U("/coupon_manager/coupon?do=get");
		$condi = array(
			"startdate" => R("startdate","string"),
			"enddate"   => R("enddate","string"),
			"keyword"   => R("keyword","string"),
			"c"				=>$c,
		);
		$where = "1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".urlencode($v);
			switch($k){
				case "startdate":
					$where .= $v?(" AND expiry>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND expiry<=".strtotime($v)):"";
					break;
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND uid='{$v}'";
					}else{
						$where .= $v?" AND (code LIKE '%{$v}%' OR name LIKE '%{$v}%')":"";
					}
					break;
				case "c":
					$where .= ($v != 0)?(" AND status = ".($v-1)):"";
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}

		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['order'] = "dateline DESC";
			$result = M("@coupon")->get_list($c,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			foreach($result['list'] as $key=>$v){
				$tmp  = C("user")->get_cache_userinfo($v['uid']);
				$result['list'][$key]['uid'] = isset($tmp['email'])?$tmp['email']:"-";
				$result['list'][$key]['use_dateline'] = $v['use_dateline']?date("Y-m-d H:i:s",$v['use_dateline']):'-';
				$result['list'][$key]['expiry'] = $v['expiry']?date("Y-m-d H:i:s",$v['expiry']):'-';
			}
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		$this->assign("pageurl",$pageurl);
		$this->assign("page",$page);
		$this->assign("condi",$condi);
		$this->assign("c",$c);
		$this->display();
	}
	//批量发送邮件模板配置
	public function email(){
		global $timestamp;
		$do = R("do");
		switch($do){
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！","callback"=>"reload");
				$id = R("id","int");
				//删除前判断是否已被调用
				if (M("email_tpl_set")->get_one("tpl_id = '{$id}'","id")) {
					tAjax::json_error("此邮件模板已被调用，请解绑后操作");
				}
				M("email_tpl")->del("id='{$id}'");
				tAjax::json($return);
				break;
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$data = array(
						"tpl_name"  	=> R("tpl_name","string"),
						"email_title"  	=> R("email_title","string"),
						"email_content"   => R("email_content","string"),
						"dateline"   		=> $timestamp,
					);
					if(M("email_tpl")->get_one("tpl_name='{$data['tpl_name']}' AND id<>'{$id}'","count(*)")>0){
						tAjax::json_error("模板名称已存在，不能重复添加");
					}
					if($id>0){
						unset($data['dateline']);
						M("email_tpl")->set_data($data)->update("id='{$id}'");
					}else{
						M("email_tpl")->set_data($data)->add();
					}
					tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));
				}else{
					if($id > 0){
						$ret = M("email_tpl")->get_row("id='{$id}'");
					}
					if(!isset($ret['id'])){
						$ret = array("tpl_name"=>"","email_title"=>"","email_content"=>"","id"=>0,"dateline"=>0);
					}
					tAjax::json($ret);
				}
				break;
			case "get":
				$datalist = M('email_tpl')->query("","*","id DESC");
				tAjax::json(array("list"=>$datalist));
				break;
			default:
				$this->display();
				break;
		}
	}
	//批量发送邮件配置
	public function email_set(){
		global $timestamp;
		$do = R("do");
		$userlevel_per = C("user")->get_ulevel(1);
		$userlevel_com = C("user")->get_ulevel(2);
		switch($do){
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！","callback"=>"reload");
				$id = R("id","int");
				M("email_tpl_set")->del("id='{$id}'");
				tAjax::json($return);
				break;
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$data = array(
						"name" 				=> R("name","string"),
						"ident" 				=> R("ident","string"),
						"tpl_id" 				=> R("tpl_id","int"),
						"num" 					=> R("num","int"),
						"u_type" 				=> R("u_type","int"),
						"u_level"   			=> R("u_level","string"),
						"u_level_com"   	=> R("u_level_com","string"),
						"ids"     				=> R("ids","string"),
						"inlock"     			=> R("inlock","int"),
						"start_dateline" 	=> R("start_dateline","string"),
						"end_dateline" 	=> R("end_dateline","string"),
						"dateline"  			=> $timestamp,
					);
					$data['inlock'] = ($data['inlock']?1:0);
					if (in_array($data['u_type'],array(1,2))) {
						if (!empty($data['u_level'])) {
							$data['u_level'] = implode(",",$data['u_level']);
						}
						if (!empty($data['u_level_com'])) {
							$data['u_level'] = implode(",",$data['u_level_com']);
						}
					}else{
						$data['u_level'] = '';
					}
					unset($data['u_level_com']);
					$data['start_dateline'] = strtotime($data['start_dateline']);
					$data['end_dateline']   = strtotime($data['end_dateline']);
					//判断标识是否已使用
					if(M("email_tpl_set")->get_one("ident='{$data['ident']}' AND id<>'{$id}'","count(*)")>0){
						tAjax::json_error("该标识已被使用,不能重复");
					}
					//判断邮件模板id是否存在
					if (!M("email_tpl")->get_one("id = '{$data['tpl_id']}'","tpl_name")) {
						tAjax::json_error("邮件模板不存在");
					}
					//判断ID段是否合法
					if (!preg_match("/[0-9]+\-[1-9]+/i",$data['ids'])) {
						tAjax::json_error("ID段非法");
					}
					$ids = explode("-",$data['ids']);
					if (count($ids) != 2) {
						tAjax::json_error("ID段非法");
					}
					if (isset($ids[0]) && isset($ids[1]) && $ids[1] <= $ids[0]) {
						tAjax::json_error("ID段非法");
					}
					//判断发送数量合法性
					if (empty($data['num']) || $data['num'] < 10 || $data['num'] > 500) {
						tAjax::json_error("邮件数量非法");
					}
					//判断活动开始日期结束日期正确性
					if (empty($data['start_dateline']) || empty($data['end_dateline'])) {
						tAjax::json_error("开始时间或结束时间不能为空！");
					}
					if ($data['end_dateline'] <= $data['start_dateline']) {
						tAjax::json_error("结束时间必须大于开始时间！");
					}

					if($id>0){
						unset($data['dateline']);
						M("email_tpl_set")->set_data($data)->update("id='{$id}'");
					}else{
						//如果是修改的话，开始日期不做判断
						if ($data['start_dateline'] < strtotime(date("Y-m-d"))) {
							tAjax::json_error("开始时间不能小于当前时间！");
						}
						M("email_tpl_set")->set_data($data)->add();
					}
					tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));
				}else{
					if($id > 0){
						$ret = M("email_tpl_set")->get_row("id='{$id}'");
						$ret['start_dateline'] = empty($ret['start_dateline'])?'':tTime::get_datetime("Y-m-d H:i:s",$ret['start_dateline']);
						$ret['end_dateline'] = empty($ret['end_dateline'])?'':tTime::get_datetime("Y-m-d H:i:s",$ret['end_dateline']);
					}
					if(!isset($ret['id'])){
						$ret = array("name"=>"","ident"=>"","tpl_id"=>0,"point"=>0,"num"=>100,"u_type"=>0,"u_level"=>"0,1,2,3","ids"=>"0-100","u_level_com"=>"0,1,2,3");
					}
					$ret['inlock'] = array(isset($ret['inlock'])?$ret['inlock']:0);
					$u_level =  $ret['u_level'];
					$ret['u_level'] = explode(",",$u_level);
					$ret['u_level_com'] = explode(",",$u_level);

					tAjax::json($ret);
				}
				break;
			case "get":
				$datalist = M('email_tpl_set')->query("");
				if($datalist){
					foreach($datalist as $k=>$v){
						$datalist[$k]['ulevels_name'] = "";
						$datalist[$k]['tpl_id'] = M("email_tpl")->get_one("id = '{$v['tpl_id']}'","tpl_name");
						if(isset($v['u_level'])){
							$tmp = explode(",",$v['u_level']);
							foreach($tmp as $v2){
								if ($v['u_type'] == 2) {
									$datalist[$k]['ulevels_name'] .= (isset($userlevel_com[$v2])?$userlevel_com[$v2]['alias']."<br/>":"");
								}elseif($v['u_type'] == 1){
									$datalist[$k]['ulevels_name'] .= (isset($userlevel_per[$v2])?$userlevel_per[$v2]['alias']."<br/>":"");
								}else{
									$datalist[$k]['ulevels_name'] = "全部";
								}
							}
						}
					}
				}
				tAjax::json(array("list"=>$datalist));
				break;
			default:
				//处理会员等级
				$utypes = C("user")->get_utype();
				$this->assign("utypes",$utypes);
				
				$this->assign("userlevel_per",$userlevel_per?array_merge($userlevel_per):array());
				$this->assign("userlevel_com",$userlevel_com?array_merge($userlevel_com):array());
				$tpl_name = array();
				$email_tpl = M("email_tpl")->query("");
				foreach($email_tpl as $key => $v){
					$tpl_name[] = array("key"=>$v['id'],"v"=>$v['tpl_name']);
				}
				$this->assign("tpl_name",$tpl_name);
				$this->display();
				break;
		}
	}
}
?>
