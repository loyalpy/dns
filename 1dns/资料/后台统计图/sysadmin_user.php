<?php
class sysadmin_user extends tController{
	public $layout = "sysadmin";
	function __construct(){
		global $user_id;
		parent::__construct('sysadmin_user');
		$admin_id = tSafe::get("admin_id");
		if(!$admin_id){
			if($user_id)$userinfo = C("member")->get_cache_userinfo($user_id);
			if(isset($userinfo) && $userinfo['inadmin']>0){
				tSafe::set("admin_id",$user_id);
				$this->redirect("/sysadmin");
			}
			$this->redirect("/login?refer=".urlencode(U('/sysadmin')));
		}else{
			$userinfo = C("member")->get_cache_userinfo($admin_id);
			$user_id = $userinfo['id'];
			$user_name = $userinfo['ni'];
			$user_type = $userinfo['utype'];
			tSafe::set('user_name',$user_name);
			tSafe::set('user_id',$user_id);
			tSafe::set('user_type',$user_type);
		}
		//获取权限
		$this->groupinfo = tCache::read("admingroup_".$userinfo['inadmin']);
		if($this->check_right($this->groupinfo['purview']) === false){
			$inajax = R("inajax","int");
			if($inajax == 1){
				tAjax::error("您的权限不足!");
			}else{
				$this->redirect("/site/error?msg=".urlencode("您的权限不足!将跳到网站首页")."&callback=".urlencode("/"));
			}
		}
	}
	//用户充值与退款
	function user_recharge(){
        global $user_id;
		$id = R('id',"int");
	    $balance = R('balance',"int");
	    $type = R('type','int');
	    $order_no = R('order_no',"string");
	    $note = R("note","string");
	    $even = '';
	    if($type=='3'){
	    	$balance = '-'.abs($balance);
	    	$even = 'withdraw';
	    	tAjax::error("您非法了吧");
	    }else{
	    	$balance = abs($balance);
	    	if($type=='1'){
	    		$even = 'recharge';
	    	}elseif($type=="4"){
	    		$even = 'drawback';
	    	}elseif($type=="2"){
	    		$balance = - $balance;
	    		$even = 'withdraw';
	    	}
	    }
	    if($balance == 0){
	    	tAjax::error("不能操作 0");
	    }
		if(!empty($id)){
			$obj = new tModel('members');
			//按用户id数组查询出用户余额，然后进行充值
			$member_info = $obj->get_row('id= '.$id);
			if(isset($member_info['id'])){
				$balance_bak = $member_info['balance']+$balance;
				if($balance_bak>=0){
					$obj->set_data(array('balance'=>$balance_bak));
					$obj->update('id = '.$id);
					//用户余额进行的操作记入account_log表
					$log = new cls_accountlog();
					$config=array(
						'user_id'   => $id,
						'admin_id'  => $user_id, //如果需要的话
						'event'     => $even, //withdraw:提现,pay:余额支付,recharge:充值,drawback:退款到余额
						'num'       => $balance, //整形或者浮点，正为增加，负为减少
						'order_no'  => $order_no, // drawback类型的log需要这个值
						'note'      => $note
					);
					$re = $log->write($config);
				}
				C("member")->set_cache_userinfo($member_info['id'],null);
			}
			tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>"reload"));
			return;
		}else{
			tAjax::error('请选择要充值的会员');
			return;
		}
	}
	//用户积分充值与退款
	function user_point(){
        global $user_id;
		$id = R('id',"int");
	    $point = R('point',"int");
	    $type = R('type','int');
	    $order_no = R('order_no',"string");
	    $note = R("note","string");
	    $even = '';
	    if($type=='2'){
	    	$point = '-'.abs($point);
	    }else{
	    	$point = abs($point);
	    }
	    if($point == 0){
	    	tAjax::error("不能操作 0");
	    }
		if(!empty($id)){
			$ret = C("point")->admin_op($point,$id,$user_id,$note);
			if($ret){
				tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>"reload"));
			}else{
				tAjax::error("操作失败！");
			}
			return;
		}else{
			tAjax::error('请选择要充值的会员');
			return;
		}
	}
	//会员列表
	public function userlist(){
		if(tUtil::check_hash()){
		  $ids = R("delete","int","post");
		  if(count($ids)>0){
				$do = R("do","string","post");
				switch($do){
					case "xxx":
						tAjax::json_error("Asdfasdf");
					default:
						break;
				}
		  }
		}else{
		  $this->display();
		}
	}
	//会员编辑
	public function userlist_edit(){
		$id = tUtil::filter(tGpc::get("id"),"int");
		if(tUtil::check_hash()){
			$do = tUtil::filter(tGpc::get('do'));
			switch($do){
				case "purview":
					if(empty($id)){
						tAjax::error("请先保存登录信息！");
					}
					
					$result = array("error"=>0,"message"=>"修改成功","callback"=>tUrl::create('/sysadmin_user/userlist'));
					$data = Ra("inadmin,inlock,ugroup,yesemail,yesmobile","int,int,int,int,int","post");
					$data["payrate"] = R("payrate","int");
					$data["payrate"] = ($data["payrate"]>=1 && $data["payrate"]<=100)?$data["payrate"]:100;
					$data["inadmin"] = $data["inadmin"]?$data["inadmin"]:0;
					$data["inlock"] = $data["inlock"]?1:0;
					$data["yesemail"] = $data["yesemail"]?1:0;
					$data["yesmobile"] = $data["yesmobile"]?1:0;
					$data["ugroup"] = $data["ugroup"]?$data["ugroup"]:0;
					
					M("members")->set_data($data);
					if(M("members")->update("id='{$id}'")){
			    		 //清除该数据缓存
					    $members_obj = new cls_member();
					    $members_obj->set_cache_userinfo($id,null);
			    	}else{
			    		tAjax::error("你确定已经修改？");
			    	}
					tAjax::respons($result);
					break;
				case "logininfo":
					$result = array("error"=>0,"message"=>"","callback"=>tUrl::create('/sysadmin_user/userlist'));
					$data = Ra("email,mobile,ni,pass,pass2,utype","string,string,string,string,int","post");
					//判断邮箱
					if($data['email'] == "" && $data['mobile'] == ""){
						tAjax::error("邮箱和手机不能同时为空！");
					}
					//判断密码
					if($data["pass"] && strlen($data['pass']) >= 6){
						if(strlen($data['pass'])<6 || strlen($data['pass']) >18 || $data['pass'] != $data['pass2']){
							tAjax::error("用户密码设置错误！");
						}else{
							$data['password'] = md5($data['pass']);
						}
					}
					unset($data['pass'],$data['pass2']);
					
					if($data['email'] && M("members")->get_one("email='{$data['email']}' AND id<>'{$id}'","COUNT(*)") >0){
						tAjax::error("该邮箱已经存在！");
					}
					if($data['mobile'] && M("members")->get_one("mobile='{$data['mobile']}' AND id<>'{$id}'","COUNT(*)") >0){
						tAjax::error("该手机已经存在！");
					}
					if($data['ni'] && M("members")->get_one("ni='{$data['ni']}' AND id<>'{$id}'","COUNT(*)") >0){
						tAjax::error("该昵称已经存在！");
					}
					M("members")->set_data($data);
					if($id>0){
					   M("members")->update("id='{$id}'");
					   $result['message'] = "修改成功！";
					   //清除该数据缓存
					   $members_obj = new cls_member();
					   $members_obj->set_cache_userinfo($id,null);
					}else{
					   $insert_id = M("members")->add();
					   $result['message'] = "添加成功！";
					   $result['callback'] = U("/sysadmin_user/userlist_edit/id/{$insert_id}");
					}
					tAjax::respons($result);
					break;
				case "baseinfo":
					if(empty($id)){
						tAjax::error("请先保存登录信息！");
					}
					$data = Ra("sex,birth_year,birth_month,birth_day,signname","int,int,int,int,string","post");
			    	$areas = Ra("sheng,shi,area","int,int,int","post");
			    	$data['area'] = $areas['area']?$areas['area']:$areas['shi'];
			    	$data['area'] = $data['area']?$data['area']:$areas['sheng'];
			    	M("members")->set_data($data);
			    	if(M("members")->update("id='{$id}'")){
			    		 //清除该数据缓存
					    C('member')->set_cache_userinfo($id,null);
			    		tAjax::success("修改成功！");
			    	}else{
			    		tAjax::error("修改失败！");
			    	}
				default:
					break;
			}
			
		}else{
			if($id > 0){
				$data = M("members")->get_row("id='{$id}'");
				if(!isset($data['id'])){
					I("用户信息没有找到",U("/sysadmin_user/userlist"));
				}
			}else{
				$data = array(
				 'id'=>0,
				 'ni'=>'',
				 'company'=>'',
				 'post'=>'',
				 'sex'=>0,
				 'area'=>33010000,
				 'birth_year'=>0,
				 'birth_month'=>0,
				 'birth_day'=>0,
				 'email'=>"",
				 'inlock'=>0,
				 'inadmin'=>0,
				 'ugroup'=>0,
				 'utype'=>1
				);
			}
			$this->assign("data",$data);
			$this->display();
		}
	}
	//会员积分设置
	public function pointset(){
		$do = R("do","string");
		switch($do){
			case "makecache":
				$_datalist = $grouplist = array();
				$_datalist = M("pointset")->query("","*","id ASC");
				if(!empty($_datalist)){
					foreach($_datalist as $k=>$v){
						$grouplist[$v['utype']][$v['ident']] = $v;
					}
				}
				$return = array("error"=>0,"message"=>"清除缓存成功！");
				if(!empty($grouplist)){
					foreach($grouplist as $k=>$v){
						tCache::write("pointset_{$k}",$v);
					}
					tAjax::respons($return);
				}else{
					tAjax::error("清除缓存失败！");
				}
				break;
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！");
				$id = R("id","int");
				M("pointset")->del("id='{$id}'");
				$return['callback'] = U("/sysadmin_user/pointset");
				tAjax::respons($return);
				break;
			case "edit":
					if(tUtil::check_hash()){
						$id = R("id","int");
						$data = Ra("ident,alias,utype,exp,info,type,status","string,string,int,int,int,int,int","post");
						$data['status'] = (isset($data['status']) && $data['status'])?1:0;
						if(M("pointset")->get_one("ident='{$data['ident']}' AND id<>$id AND utype={$data['utype']}","count(*)")>0){
							tAjax::error("该标识已被使用,不能重复");
						}
						M("pointset")->set_data($data);
						if($id>0){
							M("pointset")->update("id='{$id}'");
						}else{
							M("pointset")->add();
						}
						tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin_user/pointset")));
					}else{
						tAjax::error("非法提交！");
					}
					break;
			default:
				$_datalist = $grouplist = array();
				$_datalist = M("pointset")->query("","*","id ASC");
				if(!empty($_datalist)){
					foreach($_datalist as $k=>$v){
						$grouplist[$v['utype']][$v['id']] = $v;
					}
				}
				$this->assign("grouplist",$grouplist);
				$this->display();
				break;
		}
	}
	//会员组
	public function usergroup(){
		$do = R("do","string");
		switch($do){
			case "makecache":
				$_datalist = $grouplist = array();
				$_datalist = M("userlevel_setting")->query("","*","sort ASC");
				if(!empty($_datalist)){
					foreach($_datalist as $k=>$v){
						$v['data'] = unserialize($v['data']);
						$grouplist[$v['utype']][$v['name']] = $v;
					}
				}
				$return = array("error"=>0,"message"=>"清除缓存成功！");
				if(!empty($grouplist)){
					foreach($grouplist as $k=>$v){
						tCache::write("usergroup_{$k}",$v);
					}
					tAjax::respons($return);
				}else{
					tAjax::error("清除缓存失败！");
				}
				break;
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！");
				$id = R("id","int");
				$data = M("userlevel_setting")->get_row("id='{$id}'");
				$datacount = 0;
				$datacount = M("members")->get_one("ugroup='{$id}'","count(*)");
				if(!isset($data['id']) || $datacount>0){
					tAjax::error("该组用户不为空！");
				}else{
					M("userlevel_setting")->del("id='{$id}'");
					$return['callback'] = U("/sysadmin_user/usergroup");
					tAjax::respons($return);
				}
				break;
			case "edit":
					if(tUtil::check_hash()){
						$id = R("id","int");
						$data = Ra("sort,name,alias,utype,day,discount,urate,urate1,balance,minexp,maxexp,info","int,string,string,int,int,int,int,int,string,int,int,string","post");
						$data['data'] = serialize(R('purview'));
						$data['urate'] = $data['urate']>100?100:($data['urate']<0?0:$data['urate']);
						$data['urate1'] = $data['urate1']>100?100:($data['urate1']<0?0:$data['urate1']);
						$data['balance'] = floatval($data['balance']);
						M("userlevel_setting")->set_data($data);
						if($id>0){
							M("userlevel_setting")->update("id='{$id}'");
						}else{
							M("userlevel_setting")->add();
						}
						tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin_user/usergroup")));
					}else{
						tAjax::error("非法提交！");
					}
					break;
			default:
				$_datalist = $grouplist = array();
				$_datalist = M("userlevel_setting")->query("","*","sort ASC");
				if(!empty($_datalist)){
					foreach($_datalist as $k=>$v){
						$v['data'] = unserialize($v['data']);
						$grouplist[$v['utype']][$v['id']] = $v;
					}
				}
				$this->assign("grouplist",$grouplist);
				$this->display();
				break;
		}
	}
	//管理组
	public function admingroup(){
		$do = R("do","string");
		switch($do){
			case "makecache":
				$_datalist = $grouplist = array();
				$_datalist = M("admin_groups")->query("");
				if(!empty($_datalist)){
					foreach($_datalist as $k=>$v){
						$grouplist[$v['id']] = $v;
					}
				}
				$return = array("error"=>0,"message"=>"清除缓存成功！");
				if(!empty($grouplist)){
					foreach($grouplist as $k=>$v){
						tCache::write("admingroup_{$k}",$v);
					}
					tAjax::respons($return);
				}else{
					tAjax::error("清除缓存失败！");
				}
				break;
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！");
				$id = R("id","int");
				$data = M("admin_groups")->get_row("id='{$id}'");
				$datacount = M("members")->get_one("inadmin='{$id}'","count(*)");
				if($data['id'] == 1){
					tAjax::error("超级管理员不能删除！");
				}
				if(!isset($data['id']) || $datacount>0){
					tAjax::error("该组用户不为空！");
				}else{
					M("admin_groups")->del("id='{$id}'");
					$return['callback'] = U("/sysadmin_user/admingroup");
					tAjax::respons($return);
				}
				break;
			case "edit":
					if(tUtil::check_hash()){
						$id = R("id","int");
						$data = Ra("group_name,content","string,string","post");
						$purview = R('purview','post');
						$purview = is_array($purview)?$purview:array();
						$data['purview'] = "@".implode("@",$purview)."@";
						if($id == 1){
							$data['purview'] = "@all@";
						}
						M("admin_groups")->set_data($data);
						if($id>0){
							M("admin_groups")->update("id='{$id}'");
						}else{
							M("admin_groups")->add();
						}
						tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin_user/admingroup")));
					}else{
						tAjax::error("非法提交！");
					}
					break;
			default:
				$grouplist = M("admin_groups")->query("");
				$this->assign("grouplist",$grouplist);
				$this->display();
				break;
		}
	}
	//服务项目
	public function userservice(){
		$do = R("do","string");
		switch($do){
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！");
				$id = R("id","int");
				$data = M("user_services")->get_row("id='{$id}'");
				if(!isset($data['id'])){
					tAjax::error("该数据已经不存在！");
				}else{
					M("user_services")->del("id='{$id}'");
					/* 生成缓存 */
					$_datalist = $makelist = array();
					$_datalist = M("user_services")->query();
					if(!empty($_datalist)){
						foreach($_datalist as $k=>$v){
							$makelist[$v['ident']] = $v;
						}
					}
					tCache::write("user_services",$makelist);
					unset($_datalist,$makelist);
					/* 生成缓存 end*/
				    
					$return['callback'] = U("/sysadmin_user/userservice");
					tAjax::respons($return);
				}
				break;
			case "edit":
					if(tUtil::check_hash()){
						$id = R("id","int");
						$data = Ra("ident,name,cost1,cost2,cost3,point,utype,nsg_id","string,string,float,float,float,int,int,int","post");
						$data['cost1'] = sprintf("%.2f",$data['cost1']);
						$data['cost2'] = sprintf("%.2f",$data['cost2']);
						$data['cost3'] = sprintf("%.2f",$data['cost3']);
						//处理线路
						$acl = $tmp = array();
						$tmp = R("acl","int");
						if(is_array($tmp)){
							foreach($tmp as $v){
								$acl[$v]['id']=$v;
								$acl[$v]['children'] = R("acl_{$v}");
							}
						}
						$parm = R("data","string");
						$data['data'] = serialize(is_array($parm)?$parm:array());
						$data['acls'] = serialize($acl);
						M("user_services")->set_data($data);
						if($id>0){
							if(M("user_services")->get_one("id<>'{$id}' AND ident='{$data['ident']}'")>0){
								tAjax::error("标识必须唯一！");
							}
							M("user_services")->update("id='{$id}'");
						}else{
							if(M("user_services")->get_one("ident='{$data['ident']}'")>0){
								tAjax::error("标识必须唯一！");
							}
							M("user_services")->add();
						}
						/* 生成缓存 */
						$_datalist = $makelist = array();
						$_datalist = M("user_services")->query();
						if(!empty($_datalist)){
							foreach($_datalist as $k=>$v){
								$makelist[$v['ident']] = $v;
							}
						}
						tCache::write("user_services",$makelist);
						unset($_datalist,$makelist);
						/* 生成缓存 end*/
						tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin_user/userservice")),"json");
					}else{
						tAjax::error("非法提交！");
					}
					break;
			default:
				$datalist = M("user_services")->query("");
				$this->assign("datalist",$datalist);
				$this->display();
				break;
		}
	}
	//公司列表
	public function company(){
		if(tUtil::check_hash()){
			$ids = R("delete","int","post");
			if(count($ids)>0){
				$do = R("do","string","post");
				switch($do){
					case "delete":
						//获取用户信息
						break;
					case "edit":
						break;
					case "zhizhao_pass3":
						M("company")->set_data(array("zhizhao_pass"=>3));
						M("company")->update("id IN('".implode("','",$ids)."')");
						tAjax::respons(array("error"=>0,"message"=>"审核成功！","callback"=>"reload"),"json");
						break;
					default:
						break;
				}
				tAjax::json_error("请选择操作项！");
			}else{
				tAjax::json_error("请选择操作项！");
			}
		}else{
			$this->display();
		}
	}
	//公司删除
	public function company_del(){
		tAjax::error("sorry!暂时不提供删除！");
	}
	//公司审核
	public function company_sh(){
		global $user_id,$user_name,$realip,$timestamp;
		$id = R("id","int");
		if(tUtil::check_hash()){
			$row = M("company")->get_row("id=$id");
			if(!isset($row['id']))tAjax::json_error("该数据已经不存在！");
			$to_userinfo = C("member")->get_cache_userinfo($row['members_id']);
			
			$data = R("data","string");
			$update_data = array();
			foreach($data as $k=>$v){
				if($row[$k] != $data[$k])$update_data[$k] = $data[$k];
			}
			//处理可查看简历数
			$resumes = abs(R("resumes","int"));
			if($resumes > 0){
				$resumes_act = R("resumes_act","int");
				$update_data['resumes'] = $resumes_act==2?($row['resumes']-$resumes):($row['resumes']+$resumes);
			}
			
			if(isset($update_data['name']) && M("company")->get_one("id<>{$row['id']} AND name='{$data['name']}'")>0)tAjax::json_error("名称不能重复！");
			
            if(count($update_data)>0){
            	M("company")->set_data($update_data);
            	if(M("company")->update("id={$row['id']}")){
            		    //如果审核执照则把所有项目刷新一遍
            		    if(isset($update_data['ugroup']) && $update_data['ugroup'] >0){
            		    	M("company_post")->set_data(array("status"=>2,"lastupdate"=>$timestamp));
            		    	M("company_post")->update("members_id={$row['members_id']} AND status=0");
            		    }
            		    
            		    $logdata = array();
						$logdata['admin_id'] = $user_id;
						$logdata['admin_name'] = $user_name;
						$logdata['members_id'] = $row["members_id"];
						$logdata['olddata'] = serialize(array("name"=>$row['name'],"ugroup"=>$row['ugroup'],"sort"=>$row['sort'],"zhizhao_pass"=>$row['zhizhao_pass'],"resumes"=>$row['resumes']));
						$logdata['newdata'] = serialize($update_data);
						$logdata['description'] = "审核公司";
						$logdata['ip'] = $realip;
						$logdata['dateline'] = $timestamp;
						$logdata['company'] = $row['name'];
						$logdata['optype'] = "审核公司";
						
						M("company_openlog")->set_data($logdata);
						M("company_openlog")->add();
						
						C("member")->set_cache_userinfo($row['members_id'],null);
						tAjax::respons(array("error"=>0,"message"=>"审核成功！","callback"=>"reload"),"json");
            	}
            }
			tAjax::json_error("审核失败！");
		}else{
			$data = M("company")->get_row("id=$id");
			if(!isset($data['id']))tAjax::error("该数据已经不存在！");
			
			$to_userinfo = C("member")->get_cache_userinfo($data['members_id']);
			$this->assign("to_userinfo",$to_userinfo);
			$this->assign("data",$data);
			tAjax::success($this->fetch("sysadmin_user/company_sh"));
		}
	}
	//公司修改
	public function company_edit(){
		global $user_id,$user_name,$realip,$timestamp;
		$id = R("id","int");
		if(tUtil::check_hash()){
			//读取用户缓存信息
			$row = M("company")->get_row("id=$id");
			if(!isset($row['id']))tAjax::json_error("该数据已经不存在！");
			$to_userinfo = C("member")->get_cache_userinfo($row['members_id']);
			
			$return = array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin_user/company"));
			$data = R("data","string");
			//处理修改的地区
			$data["area"] = R("area");
			$data["area"]  = $data["area"] ?$data["area"] :R("shi");
			$data["area"]  = $data["area"] ?$data["area"] :R("sheng");
			//处理一些设置
			$data["hid_phone"] = isset($data["hid_phone"])?1:0;
			$data["hid_mobile"] = isset($data["hid_mobile"])?1:0;
			$data["hid_email"] = isset($data["hid_email"])?1:0;
			$data["hid_fax"] = isset($data["hid_fax"])?1:0;
			$data["set_refresh"] = isset($data["set_refresh"])?1:1;
            $data["set_email"] = isset($data["set_email"])?1:1;
			
		    $data["lng"] = $data["lng"]*1000000;
		    $data["lat"] = $data["lat"]*1000000;
            
		    if($id>0){
		    	if($id != $to_userinfo['extend']['id'])tAjax::json_error("非法编辑!");
		    	if(isset($data['name']) && M("company")->get_one("id<>'{$id}' AND name='{$data['name']}'","count(*)")>0){
			    	tAjax::json_error("该公司名称已经存在！");
			    }
		    	M("company")->set_data($data);
		    	M("company")->update("id='{$id}'");
		    }elseif(!isset($to_userinfo['extend']['id']) || $to_userinfo['extend']['id'] == 0){
		    	/*
		    	if(M("company")->get_one("name='{$data['name']}'","count(*)")>0){
			    	tAjax::json_error("该公司名称已经存在！");
			    }
		    	$data["members_id"] = $user_id;
				$data["dateline"] = $timestamp;
				$data["set_logo"] = file_exists(C("member")->get_avatar($user_id,"200_120","logo"))?1:0;
				$data["zhizhao_pass"] = isset($this->userinfo['extend']['zhizhao_pass'])?$this->userinfo['extend']['zhizhao_pass']:0;
				$data["zhizhao_path"] = isset($this->userinfo['extend']['zhizhao_path'])?$this->userinfo['extend']['zhizhao_path']:"";
				
				M("company")->set_data($data);
		    	$id = M("company")->add();
		    	*/
		    }
		    $return['id'] = $id;
		    C("member")->set_cache_userinfo($row['members_id'],null);
		    tAjax::respons($return,"json");
		}else{
			$data = M("company")->get_row("id=$id");
			if(!isset($data['id']))I("该数据已经不存在！",U("/sysadmin_user/company"));
			$to_userinfo = C("member")->get_cache_userinfo($data['members_id']);
			
			if((isset($data["lng"]) && $data["lng"]) && (isset($data["lat"]) && $data['lat'])){
				$data["lng"] = $data["lng"]/1000000;
				$data["lat"] = $data["lat"]/1000000;
			}
			
			$this->assign("to_userinfo",$to_userinfo);
			$this->assign("data",$data);
			$this->display();
		}
	}
	//服务商列表
	public function servicer(){
		if(tUtil::check_hash()){
			$ids = R("delete","int","post");
			if(count($ids)>0){
				$do = R("do","string","post");
				switch($do){
					case "delete":
						//获取用户信息
						break;
					case "edit":
						break;
					case "zhizhao_pass3":
						M("servicer")->set_data(array("zhizhao_pass"=>3));
						M("servicer")->update("id IN('".implode("','",$ids)."')");
						tAjax::respons(array("error"=>0,"message"=>"审核成功！","callback"=>"reload"),"json");
						break;
					default:
						break;
				}
				tAjax::json_error("请选择操作项！");
			}else{
				tAjax::json_error("请选择操作项！");
			}
		}else{
			$this->display();
		}
	}
	//服务商删除
	public function servicer_del(){
		tAjax::error("sorry!暂时不提供删除！");
	}
	//服务商审核
	public function servicer_sh(){
		global $user_id,$user_name,$realip,$timestamp;
		$id = R("id","int");
		if(tUtil::check_hash()){
			$row = M("servicer")->get_row("id=$id");
			if(!isset($row['id']))tAjax::json_error("该数据已经不存在！");
			$to_userinfo = C("member")->get_cache_userinfo($row['members_id']);
			
			$data = R("data","string");
			$data['inindex'] = isset($data['inindex'])?intval($data['inindex']):0;
			$data['intop'] = isset($data['intop'])?intval($data['intop']):0;
			$data['intui'] = isset($data['intui'])?intval($data['intui']):0;
			$data['inhide'] = isset($data['inhide'])?intval($data['inhide']):0;
			
			$update_data = array();
			foreach($data as $k=>$v){
				if($row[$k] != $data[$k])$update_data[$k] = $data[$k];
			}
			if(isset($update_data['name']) && M("servicer")->get_one("id<>{$row['id']} AND name='{$data['name']}'")>0)tAjax::json_error("名称不能重复！");
            if(count($update_data)>0){
            	M("servicer")->set_data($update_data);
            	if(M("servicer")->update("id={$row['id']}")){
            		    //如果审核执照则把所有项目刷新一遍
            		    if(isset($update_data['zhizhao_pass']) && $update_data['zhizhao_pass'] == 3){
            		    	M("servicer_s")->set_data(array("status"=>1,"lastupdate"=>$timestamp));
            		    	M("servicer_s")->update("members_id={$row['members_id']} AND status=0");
            		    }
            		    $logdata = array();
						$logdata['admin_id'] = $user_id;
						$logdata['admin_name'] = $user_name;
						$logdata['members_id'] = $row["members_id"];
						$logdata['olddata'] = serialize(array("name"=>$row['name'],"ugroup"=>$row['ugroup'],"sort"=>$row['sort'],"zhizhao_pass"=>$row['zhizhao_pass']));
						$logdata['newdata'] = serialize($update_data);
						$logdata['description'] = "审核服务商";
						$logdata['ip'] = $realip;
						$logdata['dateline'] = $timestamp;
						$logdata['company'] = $row['name'];
						$logdata['optype'] = "审核服务商";
						
						M("company_openlog")->set_data($logdata);
						M("company_openlog")->add();
						
						C("member")->set_cache_userinfo($row['members_id'],null);
						tAjax::respons(array("error"=>0,"message"=>"审核成功！","callback"=>"reload"),"json");
            	}
            }
			tAjax::json_error("审核失败！");
		}else{
			$data = M("servicer")->get_row("id=$id");
			if(!isset($data['id']))tAjax::error("该数据已经不存在！");
			
			$to_userinfo = C("member")->get_cache_userinfo($data['members_id']);
			$this->assign("to_userinfo",$to_userinfo);
			$this->assign("data",$data);
			tAjax::success($this->fetch("sysadmin_user/servicer_sh"));
		}
	}
	//服务商修改
	public function servicer_edit(){
		global $user_id,$user_name,$realip,$timestamp;
		$id = R("id","int");
		if(tUtil::check_hash()){
			//读取用户缓存信息
			$row = M("servicer")->get_row("id=$id");
			if(!isset($row['id']))tAjax::json_error("该数据已经不存在！");
			$to_userinfo = C("member")->get_cache_userinfo($row['members_id']);
			
			$return = array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin_user/servicer"));
			$data = R("data","string");
			//处理修改的地区
			$data["area"] = R("area");
			$data["area"]  = $data["area"] ?$data["area"] :R("shi");
			$data["area"]  = $data["area"] ?$data["area"] :R("sheng");
			//处理一些设置
			$data["hid_phone"] = isset($data["hid_phone"])?1:0;
			$data["hid_mobile"] = isset($data["hid_mobile"])?1:0;
			$data["hid_email"] = isset($data["hid_email"])?1:0;
			$data["hid_fax"] = isset($data["hid_fax"])?1:0;
			$data["set_refresh"] = isset($data["set_refresh"])?1:1;
            $data["set_email"] = isset($data["set_email"])?1:1;
			
		    $data["lng"] = $data["lng"]*1000000;
		    $data["lat"] = $data["lat"]*1000000;
            
		    if($id>0){
		    	if($id != $to_userinfo['extend']['id'])tAjax::json_error("非法编辑!");
		    	if(isset($data['name']) && M("servicer")->get_one("id<>'{$id}' AND name='{$data['name']}'","count(*)")>0){
			    	tAjax::json_error("该名称已经存在！");
			    }
		    	M("servicer")->set_data($data);
		    	M("servicer")->update("id='{$id}'");
		    }elseif(!isset($to_userinfo['extend']['id']) || $to_userinfo['extend']['id'] == 0){
		    	/*
		    	if(M("company")->get_one("name='{$data['name']}'","count(*)")>0){
			    	tAjax::json_error("该公司名称已经存在！");
			    }
		    	$data["members_id"] = $user_id;
				$data["dateline"] = $timestamp;
				$data["set_logo"] = file_exists(C("member")->get_avatar($user_id,"200_120","logo"))?1:0;
				$data["zhizhao_pass"] = isset($this->userinfo['extend']['zhizhao_pass'])?$this->userinfo['extend']['zhizhao_pass']:0;
				$data["zhizhao_path"] = isset($this->userinfo['extend']['zhizhao_path'])?$this->userinfo['extend']['zhizhao_path']:"";
				
				M("company")->set_data($data);
		    	$id = M("company")->add();
		    	*/
		    }
		    $return['id'] = $id;
		    C("member")->set_cache_userinfo($row['members_id'],null);
		    tAjax::respons($return,"json");
		}else{
			$data = M("servicer")->get_row("id=$id");
			if(!isset($data['id']))I("该数据已经不存在！",U("/sysadmin_user/servicer"));
			$to_userinfo = C("member")->get_cache_userinfo($data['members_id']);
			
			if((isset($data["lng"]) && $data["lng"]) && (isset($data["lat"]) && $data['lat'])){
				$data["lng"] = $data["lng"]/1000000;
				$data["lat"] = $data["lat"]/1000000;
			}
			
			$this->assign("to_userinfo",$to_userinfo);
			$this->assign("data",$data);
			$this->display();
		}
	}
	//缓存更新
	public function cache_refresh(){
		$id = R("id","int");
		if(empty($id)){
			tAjax::error("ID错误");
		}else{
			C("member")->set_cache_userinfo($id,null);
			tAjax::success("已更新！");
		}
	}
}
?>