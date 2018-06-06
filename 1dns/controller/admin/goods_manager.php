<?php
/**
 * 产品管理
 * by Thinkhu 2014 
 */
class goods_manager extends UCAdmin{
	function __construct(){
		parent::__construct('goods_manager');
	}
	//订单产品列表
	public function goodslist(){
		global $timestamp;
		$do = R("do","string");
		$goods_cat = R("goods_cat","string");
		$this->assign("goods_cat",$goods_cat);
		$catlist = C('category',"goods_cate")->get(0,0);
		
		$idc = $this->_parse_idc();
		$page = R("page","int");
	    $page = $page?$page:1;	
		$pageurl = U("/goods_manager/goodslist?do=get");
		$condi = array(
			"keyword"   => R("keyword","string"),
			"fid"       => R("fid","int")
		);
		$where = "1";
		foreach($condi as $k=>$v){
			$v = trim($v);
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					$where .= $v?" AND (keywords LIKE '%{$v}%' OR attrs LIKE '%{$v}%' OR goods_no LIKE '%{$v}%' OR name LIKE '%{$v}%')":"";
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
			$c['order'] = "sort ASC,dateline DESC";	
			$c['pagesize'] = 30;	
			$result = C("goods")->get_list($c,$pageurl);
			foreach($result['list'] as $k=>$v){
				$result['list'][$k]['catname'] = isset($catlist[$v['fid']])?$catlist[$v['fid']]['name']:" - ";
			}
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
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
		$this->assign("catlist",C("category","goods_cate")->json_tpl());
		$this->display();
	}
	//产品编辑修改
	public function goodslist_edit(){
		global $uid,$timestamp,$realip;
		$id = R("id","int");
		$fid = R("fid","int");
		$old_fid   = R("old_fid","int");
		$do = R("do","string");
		if($do == "execount"){
			$fids = R("fids","string");
			$sql = "update `@goods_cate` as a set goods=(select count(id) from `@goods` as b  where b.status=0 AND b.fid=a.id) ".(!empty($fids)?"where id IN({$fids})":"");
			Sq($sql);
			exit("");
		}
		if(tUtil::check_hash()){//发帖提交
			$data = R("data","string");//获取POST过来的数据
			$attachlist = R("attach_ids");//获取附件
			$data['fm_id'] = R("fm_id","int");
			$data['fm']    = R("fm","string");
			$data['fid']   = $fid;
			
			$this->cate = C("category","goods_cate")->get_level(0);
			if(isset($this->cate[$fid])){
				$cate = $this->cate[$fid];
			}else{
				$cate = array("name"=>"","id"=>0);
			}
			if(empty($cate['id'])){
				tAjax::json_error("分类不可用");
			}
			
			$keywords = array();			
			$rel_data = array();
			if(isset(App::$data['data_config']['goodsrel'])){
				foreach(App::$data['data_config']['goodsrel'] as $k=>$v){
					$rel_data[$k] = R("{$k}","string");
				}
			}
			/* 表单验证 */
			if(!isset($data['name']) || empty($data['name'])){
				tAjax::json_error("产品名称必须填写!");
			}
			if($id == 0){
				if(!isset($data['goods_no']) || empty($data['goods_no']) || M("goods")->get_one("id<>{$id} AND goods_no='{$data['goods_no']}'","count(*)") > 0){
					//tAjax::json_error("产品编号必须填写并且唯一!");
				}
				if(!isset($data['idc']) || empty($data['idc'])){
					//tAjax::json_error("请选择所在机房!");
				}
			}
			if(!isset($data['fid']) || empty($data['fid'])){
				tAjax::json_error("产品分类必须选择!");
			}
			
			$goods_cate_row = C("goods")->get_cate($fid);
			if(!isset($goods_cate_row['id']) || $goods_cate_row['status'] != 1){
				tAjax::json_error("产品分类不可用!");
			}
			//获取属性
			$attrs = R("attrs","string");
			if(!empty($attrs)){
				$tmp = array();
				foreach($attrs as $ident=>$v){
					if(is_array($v)){
						foreach ($v as $v2){
							$tmp[] = "{$ident}@{$v2}";
						}
					}else{
						$tmp[] = "{$ident}@{$v}";
					}
				}
				$data['attrs'] = implode(",",$tmp);
			}
			//获取规格
			$specs = R("specs","string");
			if(!empty($specs)){
				foreach($specs as $k=>$v){
					foreach($v as $k2=>$v2){
						if(!isset($v2['label'])){
							unset($specs[$k][$k2]);
						}
					}
				}
			}else{
				$specs = array();
			}
			$data['specs'] = serialize($specs);
			//处理查询关键词
			$keywords[] = $data['name'].",".$data['goods_no'].",".$cate['name'];
			
			$model = C("goods")->get_model($cate['model_id']);
		 	if($model){
    	        foreach($model as $k=>$v){
    	        	if(isset($attrs[$v['ident']])){
	    	        	if($v['type'] == 4){//输入
	    	        		$keywords[] = $attrs[$v['ident']];
	    	        	}elseif($v['type'] == 2){
	    	        		foreach($attrs[$v['ident']] as $v2){
	    	        			$keywords[] = $v['val'][$v2];
	    	        		}
	    	        	}else{
	    	        		$keywords[] = $v['val'][$attrs[$v['ident']]];
	    	        	}
    	        	}
    	        }
		 	}
			
			$data['keywords'] = implode(",",$keywords);			
			$data['status'] = (isset($data['status']) && $data['status'])?1:0;
			$data['intui']  = (isset($data['intui']) && $data['intui'])?1:0;
			$data['inhot']  = (isset($data['inhot']) && $data['inhot'])?1:0;
			$data['inxu']   = (isset($data['inxu']) && $data['inxu'])?1:0;
			$ret = C("goods")->save($id,$data,$attachlist,$rel_data);
			if($ret >0 ){
				tAjax::json(array("error"=>0,"message"=>"发布成功！","callback"=>U("/goods_manager/goodslist?fid={$data['fid']}"),"idc"=>(isset($data['idc'])?$data['idc']:0),"fid"=>$fid,"goods_id"=>$ret,"goods_no"=>(isset($data['goods_no'])?$data['goods_no']:""),"js"=>U("/goods_manager/goodslist_edit?do=execount&fids={$fid},{$old_fid}")));
			}else{
				tAjax::json_error("发布失败！");
			}
		}else{
			$data = array();
			if($id >0){
				$data = C("goods")->get_row("id=$id");
			}
			if(!isset($data['id'])){
				$data = array(
				   'fid'      => $fid,
				   'goods_no' => C("goods")->create_no(),
				   'id'       => 0,
				   'name'     => '',
				   'content'  => '',
				   "fm_id"    => 0,
				   "fm"       => "",
				   "ptype"    => 2,
				   "rel_goods" => ""
				);
			}
			$rel_goods = array();
			if(isset($data['rel_goods']) && $data['rel_goods']){
				$rel_goods = M("goods")->query("id IN({$data['rel_goods']})","id,name");
			}
			$this->assign("rel_goods",$rel_goods);
			$this->assign("data",$data);
			$this->assign("pageurl",U("/goods_manager/goodsserver?do=get&goods_no={$data['goods_no']}"));
			$this->display();
		}
	}
	//产品删除
	public function goodslist_del(){
		$id = R("id","int");
		$ret = 0;
		if($id >0){
			$row = C("goods")->get_row("id=$id");
			if(isset($row['id'])){
				if($row['nums'])tAjax::json_error("该产品下有服务器库存,不能删除");
				$ret = C("goods")->del($id);
			}
		}
		if($ret>0){
			tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>"reload","js"=>U("/goods_manager/goodslist_edit?do=execount&fids={$row['fid']}")));
		}else{
			tAjax::json_error("删除失败！");
		}
	}
	//产品分类
	public function goodslist_cate(){
		global $uid;
		//管理员登录检查
		$do = R("do","string");
		$cls_cate = new cls_category("goods_cate");
		switch($do){
			case "refresh":
				$cls_cate->clear();
				tAjax::json_success("刷新成功！");
				break;
			case "del":
				$id = R("id","int");
				if(M("goods")->get_one("fid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
					tAjax::json_error("该分类下存在产品！不能删除");
				}
				if(M("goods_cate")->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
					tAjax::json_error("该分类下有子分类！不能删除");
				}
				if(M("goods_cate")->del("id='{$id}'")){
					$cls_cate->clear();
					tAjax::json_success("删除成功！");
				}else{
					tAjax::json_error("删除失败！");
				}
				break;
			case "copy":
				$id = R("id","int");
				if($id){
					$ret = M("goods_cate")->get_row("id='{$id}'");
					if(isset($ret['id'])){
						unset($ret['id']);
						M("goods_cate")->set_data($ret)->add();
						$cls_cate->clear();
					}
				}
				tAjax::json_success("操作成功！");
				break;
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$data = array(
						"pid"         => R("pid","int"),
						"name"        => R("name","string"),
						"ident"       => R("ident","string"),
						"status"      => R("status","int"),
						"model_id"    => R("model_id","int"),
						"description" => R("description","string"),
						"sort"        => R("sort","int")
					);
					
					if(empty($data['name'])){//！
						tAjax::json_error("分类不能为空！");
					}
					if($data['pid'] && empty($data['model_id'])){
						$pid_row = M("goods_cate")->get_row("id='{$data['pid']}'");
						if(isset($pid_row['id'])){
							$data['model_id'] = $pid_row['model_id'];
						}
					}
					if($id == 0){
						M("goods_cate")->set_data($data);
						$ret = $id = M("goods_cate")->add();
					}else{
						if($data['pid'] == $id){
							tAjax::json_error("上级不能为自己！");
						}
						M("goods_cate")->set_data($data);
						$ret = M("goods_cate")->update("id=$id");
					}
					$cls_cate->clear();
		    		tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));			    	
				}else{
					$ret = array();
					if($id){
						$ret = M("goods_cate")->get_row("id='{$id}'");
					}
					if(!isset($ret['id'])){
						$ret = array("name"=>"","id"=>0,"status"=>1,'pid'=>'');
					}
					$ret['status'] = array($ret['status']);
					tAjax::json($ret);
				}
				break;
			case "get":
				$return = array();
				$return['list'] = $cls_cate->get(0);
				$return['list'] = array_merge($return['list']);
				tAjax::json($return);
				break;
			default:
				//获取产品Model
				$modellist = M("goods_model")->query("");
				$modellist_json = array();
				if($modellist){
					foreach($modellist as $k=>$v){
						$modellist_json[] = array("key"=>$v['id'],"v"=>$v['name']);
					}
				}
				$this->assign("catlist",$cls_cate->json_tpl());
				$this->assign("modellist_json",$modellist_json);
				$this->display();
				break;
		}
	}
	
	//产品服务器
	public function goodsserver(){
		global $timestamp;
		$do = R("do","string");
		$expiry = R("expiry","int");
		$status = R("status","int");
		$page = R("page","int");
	    $page = $page?$page:1;	
		$pageurl = U("/goods_manager/goodsserver?do=get&expiry=$expiry");
		$condi = array(
			//"uid"       => R("uid","int"),
		    "server_st" => R("server_st","int"),
			"keyword"   => R("keyword","string"),
			"startdate" => R("startdate","string"),
		    "enddate"   => R("enddate","string"),
		    "idc"       => R("idc","int"),
		    "goods_no"  => R("goods_no","string")
		);
		if(!$this->check_rpurview(1)){
			//$condi['uid'] = $this->userinfo['uid'];
		}
		$where = "indel=0".($expiry>1?" AND expiry > $timestamp AND expiry < ".($timestamp+$expiry*86400):($expiry == 1?" AND expiry < {$timestamp}":""));
		foreach($condi as $k=>$v){
			$v = trim($v);
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "startdate":
					$where .= $v?(" AND dateline>=".strtotime($v)):"";
					break;
				case "enddate":
					$where .= $v?(" AND dateline<=".strtotime($v)):"";
					break;
				case "keyword":
					$where .= $v?" AND (goods_no LIKE '%{$v}%' OR server_ips LIKE '%{$v}%' OR server_no LIKE '%{$v}%')":"";
					break;
				case "server_st":
					$where .= $v?" AND server_st='$v'":"";
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
			$c['order'] = "server_st ASC,expiry ASC";
			$c['pagesize'] = 50;		
			$result = Q("goods_server")->get_list($c,$pageurl);
			if($result['list']){
				foreach($result['list'] as $k=>$v){
					$_ids[] = $v["id"];
					$v['is_expiry'] = $v['expiry'] < $timestamp?1:0;
					$result['list'][$k] = $v;
				}
			}			
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
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
		$this->assign("expiry",$expiry);
		$this->display();
	}
	//产品服务器同步
	public function goodsserver_rsync(){
		$id = R("id","int");
        $res = M("goods_server")->get_row("id='{$id}'");
        if(!isset($res['id'])){
        	tAjax::json_error("参数错误！");
        }else{
        	if(empty($res["server_no"]) || empty($res['idc'])){
        		tAjax::json_error("服务器信息错误,无法同步！");
        	}
        	$apidata = array(
        	    "server_no" => $res['server_no'],
        	    //"expiry"    => $res['expiry']
        	);
        	$ret = JSON::decode(tFun::api($res['idc'],App::$config['appuser'],"server_rsync",$apidata));
        	$ret = tUtil::iconv_s($ret);
        	if(isset($ret['id'])){
        		$rsdata = array(
					"server_us"     => $ret['server_us'],
					"server_os"     => $ret['server_os'],
					"bandwidth"     => $ret['bandwidth'],
	
					"jiguino"       => $ret['jiguino'],
					"netno"         => $ret['netno'],
					
					"server_ips"    => $ret['server_ips'],
					"server_in_ips" => $ret['server_in_ips'],
					"server_cfg"    => $ret['server_cfg'],
        		);
        		if(M("goods_server")->set_data($rsdata)->update("id='{$res['id']}'")){
        			C("user")->log("同步产品服务器","服务器编号：{$res['server_no']}");
        		}
        		tAjax::json_success("ok");
        	}else{
        		tAjax::json_error("同步失败！");
        	}
        }
	}
	//产品服务器编辑
	public function goodsserver_edit(){
		global $uid,$timestamp,$realip;
		$do = R("do","string");
		$id = R("id","int");
		if(tUtil::check_hash()){//发帖提交
			$server_data = array(
				"server_no"     => R("server_no","string"),
				"goods_no"      => R("goods_no","string"),
				"idc"           => R("idc","int"),
				"server_us"     => R("server_us","string"),
				"server_os"     => R("server_os","string"),
				"bandwidth"     => R("bandwidth","string"),

				"jiguino"       => R("jiguino","string"),
				"netno"         => R("netno","string"),
				
				"server_ips"    => R("server_ips","string"),
				"server_in_ips" => R("server_in_ips","string"),
				"server_cfg"    => R("server_cfg","string"),
				
				"inlock"        => R("inlock","int"),
				"inlock_expiry" => R("inlock_expiry","int"),
					
				"bz"            => R("bz","string"),
			);
			if($this->check_rpurview(1)){
				$server_data['server_st'] = R("server_st","int");
				$server_data['start_dateline'] = R("start_dateline","string");
				$server_data['expiry'] = R("expiry","string");
				$server_data['amount'] = R("amount","float");
				$server_data['buy_uid'] = R("buy_uid","int");
				$server_data['uid'] = R("uid","int");
				
				$server_data['start_dateline'] = strtotime($server_data['start_dateline']);
				$server_data['expiry'] = strtotime($server_data['expiry']);
				
				if($server_data['uid']){
					$uinfo = C("user")->get_cache_userinfo($server_data['uid']);
					$server_data['uname'] = $uinfo['name'];
				}
				
				if($server_data['buy_uid']){
					$uinfo = C("user")->get_cache_userinfo($server_data['buy_uid']);
					$server_data['buy_uname'] = $uinfo['buy_uname'];
				}
			}
			if($this->check_rpurview(2)){
				$server_data['server_user'] = R("server_user","string");
				$server_data['server_pass'] = R("server_pass","string");
				$server_data['server_port'] = R("server_port","string");
			}
			if($server_data['inlock'] == 1){
				$server_data['inlock_expiry'] = $server_data['inlock_expiry']?strtotime($server_data['inlock_expiry']):($timestamp+1800);
			}else{
				$server_data['inlock_expiry'] = 0;
			}
			if(empty($server_data['server_no']) || M("goods_server")->get_one("indel=0 AND id<>'{$id}' AND server_no='{$server_data['server_no']}'","count(*)")>0){
				tAjax::json_error("服务器编号必须存在并且唯一");
			}
			$id = R("id","int");
			
			$ret = C("goods")->save_goodsserver($id,$server_data);
			if($ret >0 ){
				if(!empty($server_data['goods_no'])){
					C("goods")->update_goods_nums("a.goods_no='{$server_data['goods_no']}'");
				}
				$row = M("goods_server")->get_row("server_no='{$server_data['server_no']}'");
				$row["error"] = 0;
				$row['message'] = "操作成功！";
				$row['callback'] = "close";
				tAjax::json($row);
			}else{
				tAjax::json_error("保存失败！{$id},{$ret}");
			}
		}else{
			$server_no = R("server_no","string");
			$goods_no = R("goods_no","string");
			$idc = R("idc","int");
			if($id){
				$res = M("goods_server")->get_row("id='{$id}'");
			}
			if(!isset($res['id'])){
				$res = array("idc"=>$idc,"goods_no"=>$goods_no,"server_no"=>$server_no,"amount"=>"0.00","start_dateline"=>$timestamp,"expiry"=>($timestamp+31*86400));
			}
			$res['start_dateline'] = tTime::get_datetime("Y-m-d",$res['start_dateline']);
			$res['expiry'] = tTime::get_datetime("Y-m-d",$res['expiry']);
			tAjax::json($res);
		}
	}
	//产品服务器删除
	public function goodsserver_del(){
		$id = R("id","int");
		$server_row = M("goods_server")->get_row("id='{$id}'");
		if(!isset($server_row['id']))tAjax::json_error("此服务器不存在!");
		if($server_row['server_st'] > 5 || $server_row['buy_uid'])tAjax::json_error("此服务器正在使用中!");
		$ret = M("goods_server")->del("id='{$id}' AND buy_uid=0 AND server_st<5");
		if(!empty($server_row['goods_no'])){
			C("goods")->update_goods_nums("a.goods_no='{$server_row['goods_no']}'");
		}
		if($ret >0 ){
			C("user")->log("删除产品服务器","服务器编号：{$server_row['server_no']}");
			tAjax::json(array("id"=>$id,"error"=>0,"message"=>"删除成功！"));
		}else{
			tAjax::json_error("删除失败！{$id},{$ret}");
		}
	}
	//产品服务器获取
	public function goodsserver_get(){
		$idc = $this->_parse_idc();
		$keyword = R("keyword","string");
		$page = R("page","int");
		$fun = R("fun","string");
		echo tFun::api($idc,App::$config['appuser'],"server_getlist",array("keyword"=>$keyword,"idc"=>$idc,"page"=>$page,"fun"=>$fun));
	}
	
	//产品模型
	public function goodslist_model(){
		$list = M("goods_model")->query("");
		if(!empty($list)){
			foreach($list as $k=>$v){
				$list[$k]['attrs'] = tUtil::unserialize($v['attrs']);
				$list[$k]['specs'] = tUtil::unserialize($v['specs']);
			}
		}
		$this->assign("list",$list);
		$this->display();
	}
	//产品模型编辑
	public function goodslist_model_edit(){
		$id = R("id","int");
		if(tUtil::check_hash()){
			$data = Ra("name","string","post");
			/* 表单验证 */
			$errors = array();
			if(!isset($data['name']) || empty($data['name'])){
				tAjax::json_error("模型名称必须填写!");
			}
			M("goods_model")->set_data($data);
			if($id>0){
				$ret = M("goods_model")->update("id='{$id}'");
			}else{
				$ret = M("goods_model")->add();
				$id = $ret;
			}
			if($ret){
				C("goods")->model_makecache($id);
			}
			tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>U("/goods_manager/goodslist_model")),"json");
		}else{
			$data = M("goods_model")->get_row("id=$id");
			$this->assign("data",$data);
			$this->display();
		}
	}
	//产品模型删除
	public function goodslist_model_del(){
		$return = array("error"=>0,"message"=>"删除成功！");
		$id = R("id","int");
		$data = M("goods_model")->get_row("id='{$id}'");
		$datacount = M("goods_cate")->get_one("model_id='{$id}'","count(*)");
		if(!isset($data['id']) || $datacount>0){
			tAjax::json_error("当前模型有产品分类在使用,不能删除！");
		}else{
			M("goods_model")->del("id='{$id}'");
			$return['callback'] = U("/goods_manager/goodslist_model");
			tAjax::json($return);
		}
	}
	//产品模型-规格与属性配置
	public function goodslist_model_attrspec(){
		global $uid;
		//管理员登录检查
		$do = R("do","string");
		$model_id = R("model_id","int");
		$this->assign("model_id",$model_id);
		if(empty($model_id))$this->_msg("模型ID不能为空！","模型ID不能为空,请联系管理员","/goods_manager/goodslist_model#返回产品模型列表");
		$cls_cate = new cls_category("goods_model_attrspec","c.model_id='{$model_id}'");
		switch($do){
			case "refresh":
				$cls_cate->clear();
				tAjax::json_success("刷新成功！");
				break;
			case "del":
				$id = R("id","int");
				if(M("goods_attrspec")->get_one("attr_spec_id='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
					tAjax::json_error("该属性/规格下存在产品！不能删除");
				}
				if(M("goods_model_attrspec")->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
					tAjax::json_error("该组下有子属性/规格！不能删除");
				}
				if(M("goods_model_attrspec")->del("id='{$id}'")){
					$cls_cate->clear();
					tAjax::json_success("删除成功！");
				}else{
					tAjax::json_error("删除失败！");
				}
				break;
			case "copy":
				$id = R("id","int");
				if($id){
					$ret = M("goods_model_attrspec")->get_row("id='{$id}'");
					if(isset($ret['id'])){
						unset($ret['id'],$ret['ident']);
						M("goods_model_attrspec")->set_data($ret)->add();
						$cls_cate->clear();
					}
				}
				tAjax::json_success("操作成功！");
				break;
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$data = array(
							"pid"         => R("pid","int"),
							"name"        => R("name","string"),
							"ident"       => R("ident","string"),
							"model_id"    => R("model_id","int"),
							"description" => R("description","string"),
							"sort"        => R("sort","int"),
							
							"is_only"     => R("is_only","int"),
							"is_attr"     => R("is_attr","int"),
							"is_spec"     => R("is_spec","int"),
							"is_search"   => R("is_search","int"),
							
							"type"        => R("type","int"),
							"unit"        => R("unit","string"),
							
							"min_val"     => R("min_val","int"),
							"max_val"     => R("max_val","int"),
							"add_val"     => R("add_val","int"),							
					);
						
					if(empty($data['name'])){//！
						tAjax::json_error("属性/规格不能为空！");
					}
					if(empty($data['ident'])){//！
						tAjax::json_error("属性/规格标识不能为空！");
					}
					if(!empty($data['pid']) && empty($data['type'])){//！
						tAjax::json_error("类型必须选择！");
					}
					if(M("goods_model_attrspec")->get_one("id <> '{$id}' AND ident='{$data['ident']}'","count(id)") > 0){
						tAjax::json_error("属性/规格标识唯一！");
					}
					if($data['pid']){
						$pid_row = M("goods_model_attrspec")->get_row("id='{$data['pid']}'");
						if(!isset($pid_row['id'])){
							tAjax::json_error("未找到上级分类");
						}
						if($pid_row['pid'] != 0){
							tAjax::json_error("最多二级分类");
						}
						$data['model_id'] = $pid_row['model_id'];
					}else{
						//顶级分类,组
					}
					//值
					$iv = R("vals","string");
					$ik = R("keys","string");
					$aval = array();
					if($ik){
						foreach($ik as $k=>$v){
							if($v){
								$aval[$v] = "{$v}@{$iv[$k]}";
							}
						}
					}
                    $data['all_val'] = implode(",",$aval);
					if($id == 0){
						M("goods_model_attrspec")->set_data($data);
						$ret = $id = M("goods_model_attrspec")->add();
					}else{
						if($data['pid'] == $id){
							tAjax::json_error("上级组不能为自己！");
						}
						M("goods_model_attrspec")->set_data($data);
						$ret = M("goods_model_attrspec")->update("id=$id");
					}
					$cls_cate->clear();
					tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));
				}else{
					$ret = array();
					if($id){
						$ret = M("goods_model_attrspec")->get_row("id='{$id}'");
					}
					if(!isset($ret['id'])){
						$ret = array("name"=>"","id"=>0,"sort"=>0,'pid'=>'',"is_only"=>0,"is_attr"=>1,"is_spec"=>0,"all_val"=>"");
					}
					$ret['is_attr'] = array($ret['is_attr']);
					$ret['is_spec'] = array($ret['is_spec']);
					$ret['is_only'] = array($ret['is_only']);
					
					 
					$all_val = explode(",",$ret['all_val']);
					$ret['all_val'] = array();
					if($all_val){
						foreach($all_val as $v){
							$tmp = explode("@",$v);
							$ret['all_val'][$tmp[0]] = isset($tmp[1])?$tmp[1]:"";
						}
					}
					tAjax::json($ret);
				}
				break;
			case "get":
				$return = array();
				$return['list'] = $cls_cate->get(0);
				$return['list'] = array_merge($return['list']);
				tAjax::json($return);
				break;
			default:
				$this->assign("catlist",$cls_cate->json_tpl());
				$this->display();
				break;
		}
	}
	
	//根据分类找出属性与规格
	public function goodslist_edit_getmodel(){
		$fid = R("fid","int");
		$gid = R("gid","int");
		$cate_row = C("goods")->get_cate($fid);
		
		if(!isset($cate_row['id']))tAjax::error("");
		$model_id = $cate_row['model_id'];
		
		if(empty($model_id))tAjax::error("");
		$cls_catesss = new cls_category("goods_model_attrspec","c.model_id='{$model_id}'");
		$model_cate = $cls_catesss->get(0);
		$attrs_html = $specs_html = "";
		//获取产品的信息
		$goods_row = M("goods")->get_row("id='{$gid}'");
		$myattr = $myspec = array();		
		if(isset($goods_row['id'])){
			$tmp_attr = $goods_row['attrs']?explode(",",$goods_row['attrs']):array();
			if(!empty($tmp_attr)){
				foreach($tmp_attr as $v){
					$tmp = explode("@",$v);
					if(!isset($myattr[$tmp[0]])){
						$myattr[$tmp[0]] = array();
					}
					$myattr[$tmp[0]][] = $tmp[1];
				}
			}
		}
		if(empty($model_cate)){
			return false;
		}
		$pname = "";
		foreach($model_cate as $k => $v){
			if($v['has_children'] > 0){
				if($v['pid'] == 0){
					$pname = $v['name'];
				}
				continue;
			}else{
				if($v['pid'] == 0){
					$pname = "";
				}
			}
			$ident       = $v['ident'];
			$attrs_html .= "<div class=\"form-group\">";
			$attrs_html .= "<label class=\"col-sm-1 control-label\">".($pname?"[{$pname}]":"")."{$v['name']}:</label>";
			$attrs_html .= "<div class=\"col-sm-8\">";
			$all_val = explode(",",$v['all_val']);
			$new_val = array();
			if($all_val){
				foreach($all_val as $v0){
					$tmp = explode("@",$v0);
					$new_val[$tmp[0]] = isset($tmp[1])?$tmp[1]:"";
				}
			}
			switch($v['type']){
				case 1:// radio 单选
					if($new_val){
						foreach($new_val as $k2=>$v2){
							$attrs_html .= "<label class='radio checkbox-inline'><input ".((isset($myattr[$ident]) && in_array($k2,$myattr[$ident]))?"checked=\"checked\"":"")." type='radio' name='attrs[$ident]' value=\"{$k2}\" class=\"reset\" />{$v2}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>";
								
						}
					}
					break;
				case 2://checkbox 多选
					if($new_val){
						foreach($new_val as $k2=>$v2){
							$attrs_html .= "<label class='checkbox checkbox-inline'><input ".((isset($myattr[$ident]) && in_array($k2,$myattr[$ident]))?"checked=\"checked\"":"")." type='checkbox' name='attrs[{$ident}][]' value=\"{$k2}\" class=\"reset\" /> {$v2}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>";
						}
					}
					break;
				case 3:
					if($new_val){
						$attrs_html .= "<select name='attrs[{$ident}]'>";
						foreach($new_val as $k2=>$v2){
							$attrs_html .= "<option ".((isset($myattr[$ident]) && in_array($k2,$myattr[$ident]))?"selected=\"selected\"":"")." value='{$k2}'>{$v2}</option>";
						}
						$attrs_html .= "</select>";
					}
					break;
				default:
					$attrs_html .= "<input type='text' style='width:300px;' name='attrs[{$ident}]' value='".(isset($myattr[$ident])?implode("",$myattr[$ident]):"")."' />";
					break;
			}
			$attrs_html .= "</div></div>";
		}
		/*$specs_html .= "<div class=\"form-group\">";
					$specs_html .= "<label class=\"col-sm-1 control-label\">{$v['name']}:</label>";
					$specs_html .= "<div class=\"col-sm-5\">";
					$tmp = explode(",",$v['value']);
					if($tmp){
						$specs_html .= "<table class=\"table table-bordered\" style=\"width:400px;\"><col /><col width=\"60px\" /><col width=\"100px\" /><col width=\"100px\" /><th>规格</th><th>可用</th><th>价格</th><th>剩余</th>";
						foreach($tmp as $k2=>$v2){
							$specs_html .= "<tr>";
							$specs_html .= "<input type='hidden' name='specs[{$ident}][$k2][name]' value=\"{$v['name']}\" />";
							$specs_html .= "<td>{$v2}</td>";
							$specs_html .= "<td><input ".((isset($myspec[$ident][$k2]['label']))?"checked=\"checked\"":"")." type='checkbox' name='specs[{$ident}][$k2][label]' class=\"reset\" value=\"{$v2}\" /></td>";
							$specs_html .= "<td><input name='specs[{$ident}][$k2][price]' value=\"".(isset($myspec[$ident][$k2]['price'])?$myspec[$ident][$k2]['price']:"")."\" type=\"text\" class=\"low\" /> </td>";
							$specs_html .= "<td><input name='specs[{$ident}][$k2][nums]' value=\"".(isset($myspec[$ident][$k2]['price'])?$myspec[$ident][$k2]['nums']:"")."\" type=\"text\" class=\"low\" /> </td>";
							$specs_html .= "</tr>";
						}
						$specs_html .= "</table>";
					}
					$specs_html .= "</div></div>";*/
		tAjax::respons(array("error"=>0,"attrs"=>$attrs_html,"specs"=>$specs_html));
	}
}
?>