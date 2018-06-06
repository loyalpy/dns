<?php
/**
 * 客户管理
 * by Thinkhu 2014 
 */
class client_manager extends UCAdmin{
	//客户列表
	public function clientlist(){
		$idc = $this->_parse_idc();
		$do = R("do","string");
	    $page = R("page","int");
	    $page = $page?$page:1;	
	    $st = R("st","int");
		$pageurl = U("/client_manager/clientlist?do=get&st=$st");
		$condi = array(
			"keyword"   => R("keyword","string"),
			"uid"       => R("uid","int"),
		);
		//$st = !in_array($st,array_keys(App::$data['data_config']['client_st']))?9:$st;
		$this->assign("st",$st);
		$where = $st?"client_st=$st":"1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					if(tValidate::is_int($v)){
						$where .= " AND uid='{$v}'";
					}else{
						$where .= $v?" AND (contacter LIKE '%{$v}%' OR name LIKE '%{$v}%')":"";
					}
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
			$result = C('client')->get_list($c,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}
		//处理分类
        //获取供应商
		$this->assign("pageurl",$pageurl);
		$this->assign("condi",$condi);
		$this->display();
	}
	//客户删除
	public function clientlist_del(){
		tAjax::json_error("暂时不提供库存删除功能！");
	}
	//客户编辑
	public function clientlist_edit(){
		global $timestamp;
		$id = R("id","int");
		if(tUtil::check_hash()){			
			$data = array(
	    		"name"        => R("name","string"),
	    		"address"     => R("address","string"),
	    		"client_tp"   => R("client_tp","int"),
	    		"goods_cat"   => R("goods_cat","int"),
	    		"client_sr"   => R("client_sr","int"),
	    		"client_st"   => R("client_st","int"),
	    		"client_lv"   => R("client_lv","int"),
	    		"fax"         => R("fax","string"),
	    		"homepage"    => R("homepage","string"),
	    		"ip"          => R("ip","string"),
	    		"zcode"       => R("zcode","string"),
	    		"bz"          => R("bz","string"),
	    	);
	    	if(!$this->check_upurview(1) || empty($data['company'])){
				$data['company'] = $this->userinfo['company'];
			}
	    	if(empty($id)){
	    		//$
	    		$linker = array(
	    			"name"     => R("linker","string"),
	    			"mobile"   => R("linker_mobile","string"),
	    		    "tel"      => R("linker_tel","string"),
	    		    "qq"       => R("linker_qq","string"),
	    		    "email"    => R("linker_email","string"),
	    		);
	    		//检查客户名称
		    	if(empty($data['name'])){
		    		tAjax::json_error("客户名必须填写！");
		    	}	    	
	    	    if(empty($linker['name'])){
		    		tAjax::json_error("客户联系人必须填写！");
		    	}
		    	if(M("client")->get_one("name LIKE '%{$data['name']}%'","count(id)")>0){
		    		tAjax::json_error("该客户已经存在,请不要重复添加！");
		    	}
		    	$data['contacter'] = implode(",",$linker);
	    		$data['uid'] = $this->userinfo['uid'];
	    		$data['uname'] = $this->userinfo['name'];
	    		$data['author'] = $this->userinfo['name'];
	    		$data['dateline'] = $timestamp;
	    		M("client")->set_data($data);
	    		$ret = M("client")->add();
	    		if($ret){
	    			$linker['client'] = $ret;
	    			$linker['one'] = 1;
	    			M("client_linker")->set_data($linker)->add();
	    			C("user")->log("添加客户","客户ID：{$ret},{$data['name']},联系人:{$linker['name']},{$linker['tel']},{$linker['mobile']}");
	    		}
	    	}else{
	    		unset($data['name']);
		    	M("client")->set_data($data);
	    		$ret = M("client")->update("id='{$id}'");
	    		if($ret){
	    			
	    		}
	    	}
	    	tAjax::json(array("error"=>0,"message"=>"保存成功！","id"=>$ret,"callback"=>"close"));
		}else{
			$res = array("id"=>0,"name"=>"","linker"=>array(),"records"=>array(),"client_st"=>1,"client_tp"=>1,"client_sr"=>1);
			if(!empty($id)){
				$tmp = M("client")->get_row("id='$id'");
				if(isset($tmp['id'])){
					$res = $tmp;
					$res['linker'] = C("client")->get_linker($id);
				}else{
					$res['linker'] = "";
				}
			}
			tAjax::json($res);			
		}
	}
	//客户联系人添加/编辑
	public function clientlist_linker_edit(){
		$id = R("id","int");
		$client = R("client","int");
		$linker = array(
	    	"name"     => R("linker","string"),
	    	"mobile"   => R("linker_mobile","string"),
	    	"tel"      => R("linker_tel","string"),
	    	"qq"       => R("linker_qq","string"),
	    	"email"    => R("linker_email","string"),
 	    );
	    		
		if($id == 0){
			$linker['client'] = $client;
			$linker['one'] = 0;
			$ret = M("client_linker")->set_data($linker)->add();
		}else{
			$row = M("client_linker")->get_row("id=$id");
			if(!isset($row['id'])){
				tAjax::json_error("该数据已经不存在！");
			}else{
				M("client_linker")->set_data($linker);
				$ret = M("client_linker")->update("id=$id");
				if($ret && $row['one'] == 1){
					M("client")->set_data(array(
						"contacter" => implode(",",$linker)
					));
					M("client")->update("id={$row['client']}");
				}
			}
		}
		$result = array("message"=>"操作成功！","id"=>$client);
		if($ret){
			$result['linker'] = C("client")->get_linker($client);
			tAjax::json($result);
		}else{
			tAjax::json_error("您确定已做修改？");
		}
	}
	//客户联系人删除
	public function clientlist_linker_del(){
		$id = R("id","int");
		$client = R("client","int");
		$row = M("client_linker")->get_row("id=$id");
		if(!isset($row['id'])){
			tAjax::json_error("该数据已经不存在！");
		}else{
			if($row['one'] == 1){
				tAjax::json_error("主联系人不能删除！");
			}
			$result = array("message"=>"操作成功！","id"=>$client);
			$ret = M("client_linker")->del("id=$id");
			if($ret){
				$result['linker'] = C("client")->get_linker($client);
				tAjax::json($result);
			}else{
				tAjax::json_error("删除失败！");
			}
		}
	}
	//客户跟踪记录列表
	public function clientlist_record(){
	    $page = R("page","int");
	    $client = R("client","int");
	    
	    $page = $page?$page:1;
		$pageurl = U("/client_manager/clientlist_record?");
		$where = "client=$client";
		
		$c = array();
		$c['page']  = $page;
		$c['pagesize'] = 60;
		$c['where'] = $where;
		$result = C('client')->get_record($c,$pageurl);
		$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadrecord",array($client));
		tAjax::json($result);
	}
	//客户跟踪记录
	public function clientlist_record_edit(){
		global $timestamp;
		$client = R("client","int");
		$record = array(
	    	"content"     => R("content","string")
 	    );
	    		
		$record['client'] = $client;
		$record['dateline'] = $timestamp;
		$record['uid'] = $this->userinfo['uid'];
		$record['author'] = $this->userinfo['name'];;
		$ret = M("client_record")->set_data($record)->add();
		
		$result = array("message"=>"操作成功！","id"=>$client);
		if($ret){
			M("client")->set_data(array(
				"lastdateline"  => $timestamp,
				"records"       => $record['content']))
				->update("id=$client");
			
			tAjax::json($result);
		}else{
			tAjax::json_error("您确定已做修改？");
		}
	}
	//客户联系人删除
	public function clientlist_record_del(){
		$id = R("id","int");
		$client = R("client","int");
		
		if($this->userinfo['urole'] != 1){
			tAjax::json_error("您没有权限执行该操纵！");
		}else{
			$ret = M("client_record")->del("id=$id");
			if($ret){
				tAjax::json_success("删除成功！");
			}else{
				tAjax::json_error("删除失败！");
			}
		}
	}
	//检查客户名是否重复
	public function check_client_name(){
		$val =  R("param","string");
        $errmsg = "未通过验证";
        $ret = M("client")->get_one("name LIKE '%{$val}%'","count(*)");
        if($ret == 0){
        	$errmsg = "";
        }else{
        	$errmsg = "此客户已经存在！";
        }
		if($errmsg){
	    	tAjax::json(array("status"=>"n","info"=>$errmsg));
	    }else{
	    	tAjax::json(array("status"=>"y","info"=>"通过验证"));
	    }
	}
	//获取所有公司客户列表
	public function clientlist_get(){
		$companylist = tCache::read("company_config");

	    $page = R("page","int");
	    $page = $page?$page:1;	
		$pageurl = U("/client_manager/clientlist_get?");
		$condi = array(
		    "client_st" => R("client_st","int"),
			"keyword"   => R("keyword","string"),
		);
		$condi['client_st'] = $condi['client_st']?$condi['client_st']:9;
		
		if(!$this->check_upurview(1) || empty($condi['company'])){//除了跨公司权限外
			$condi['company'] = $this->userinfo['company'];
		}
		if(!$this->check_rpurview(0)){//除了跨公司权限外
			$condi['uid'] = $this->userinfo['uid'];
		}
		
		$where = "1";
		foreach($condi as $k=>$v){
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "keyword":
					$where .= $v?" AND (name LIKE '%{$v}%')":"";
					break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}
		$c = array();
		$c['page']  = $page;
		$c['where'] = $where;
		$c['pagesize'] = 20;
		$result = C('client')->get_list($c,$pageurl);
		$result['condi']   = $condi;
		$formstr = R("formstr","string");
		$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"get_clientlist",array($condi["client_st"],"",$formstr));
	    tAjax::json($result);
	}
}
?>