<?php
class sys_manager extends UCAdmin{
	function __construct(){
		parent::__construct('sys_manager');
	}
	//微信菜单生成
	public function set_wxmenu(){
		global $uid;
		$res = SDKwx::set_menu();
		dump($res);
	}
	//网站配置
	public function sys_config(){
		if(tUtil::check_hash()){
			$data = R("data",'string','post');
		    foreach($data as $k=>$v){
		    	if($k){
		    		$is = M('site_config')->get_one("name='{$k}'","count(*)");
		    		if( $is == 0){
		    		    M('site_config')->set_data(array("name"=>"{$k}","value"=>$v));
		    		    M('site_config')->add();
		    		}elseif($is == 1){
		    			M('site_config')->set_data(array("value"=>$v));
		    			M('site_config')->update("name='{$k}'");
		    		}
		    		$data[$k] = htmlspecialchars_decode($data[$k]);
		    	}
		    }
		    tCache::write('site',$data);
			tAjax::json_success("修改配置成功!");
		}else{
			$do = R("do","string");
			if($do == "get"){
			    $data = array();
				foreach(M("site_config")->query() as $v){
					$data[$v['name']] = htmlspecialchars_decode($v['value']);
				}
				tAjax::json($data);
			}
			$this->display();
		}
	}
	//模块配置
	public function module_config(){
		if($this->userinfo['urole'] == 1){
			//管理员登录检查
			$do = R("do");
			$cls_cate = new cls_category("sysmodule");
			switch($do){
				case "refresh":
					$cls_cate->clear();
					tAjax::json_success("刷新成功！");
					break;
				case "del":
					$id = R("id","int");
					if(M("sysmodule")->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
						tAjax::json_error("该模块下有子模块！不能删除");
					}
					if(M("sysmodule")->del("id='{$id}'")){
						$cls_cate->clear();
						tAjax::json_success("删除成功！");
					}else{
						tAjax::json_error("删除失败！");
					}
					break;
				case "copy":
					$id = R("id","int");
					if($id){
						$ret = M("sysmodule")->get_row("id='{$id}'");
						if(isset($ret['id'])){
							unset($ret['id']);
							M("sysmodule")->set_data($ret)->add();
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
							"enname"      => R("enname","string"),

							"module"      => R("module","string"),
							"action"      => R("action","string","index"),

							"status"      => R("status","int"),
							"isopen"      => R("isopen","int",0),

							"extaction"   => R("extaction","string"),
							"description" => R("description","string"),

							"sort"        => R("sort","int")
						);
						$data['url'] = R("url","string","/{$data['module']}/{$data['action']}");
						if(empty($data['name'])){//！
							tAjax::json_error("模块名不能为空！");
						}
						if($id == 0){
							M("sysmodule")->set_data($data);
							$ret = $id = M("sysmodule")->add();
						}else{
							$catlist = $cls_cate->get(0);
							if($data['pid'] && !isset($catlist[$data['pid']])){
								tAjax::json_error("上级已经不存在！");
							}
							if($data['pid'] == $id){
								tAjax::json_error("上级不能为自己！");
							}
							M("sysmodule")->set_data($data);
							$ret = M("sysmodule")->update("id=$id");
						}
						$cls_cate->clear();
			    		tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));
					}else{
						$ret = array();
						if($id){
							$ret = M("sysmodule")->get_row("id='{$id}'");
						}
						if(!isset($ret['id'])){
							$ret = array("name"=>"","id"=>0,"status"=>1,"isopen"=>0,'pid'=>'');
						}
						$ret['status'] = array($ret['status']);
						$ret['isopen'] = array($ret['isopen']);
						$cls_cate->clear();
						tAjax::json($ret);
					}
					break;
				case "get":
					$return = array();
					$return['list'] = $cls_cate->get(0,0);
					$return['list'] = array_merge($return['list']);
					tAjax::json($return);
					break;
				default:
					$this->assign("catlist",$cls_cate->json_tpl());
					$this->display();
					break;
			}
		}else{
			$this->_msg("您的权限不足!","您的权限不足,请联系管理员","/ucenter#返回首页");
		}
	}
	//数据配置
	public function data_config(){
		//管理员登录检查
		$do = R("do");
		switch($do){
			case "makecache":
				$ret = C("site")->write_cache_dataconfig();
				if($ret == 1){
					tAjax::json_success("生成缓存成功！");
				}else{
					tAjax::json_error("生成缓存失败！");
				}
				break;
			case "edit":
				$item = R("item","string");
				$names = R('name',"string");
				$codes = R('code',"string");
				$data = array();
				if(is_array($codes) && count($codes) > 0){
					foreach($codes as $k=>$v){
						if($names[$k])$data[$v]=$names[$k];
					}
				}
				$is = M('site_config')->get_one("name='dataconfig_{$item}'","count(*)");
		    	if( $is == 0){
		    		M('site_config')->set_data(array("name"=>"dataconfig_{$item}","value"=>serialize($data)));
		    		M('site_config')->add();
		    	}elseif($is == 1){
		    		M('site_config')->set_data(array("value"=>serialize($data)));
		    		M('site_config')->update("name='dataconfig_{$item}'");
		    	}

		    	$ret = C("site")->write_cache_dataconfig();
				tAjax::json_success("保存成功！");
				break;
			case "get":
				$item = R("item","string");
				$data = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $data?tUtil::unserialize($data):array();
				tAjax::json(array("error"=>0,"item"=>$item,"data"=>$datalist));
			default:
				$this->display();
				break;
		}
	}
	//行业数据配置
	public function trade_cate_config(){
	    $do          = R("do","string");
		$pid         = R("pid","int");
		$table       = "trade_cate";
		$table_alias = "行业";
		$cls_cate    = new cls_category($table);
		$url         = U("/sys_manager/{$table}_config");
		$this->assign("table",$table);
		$this->assign("url",$url);
		$this->assign("do",$do);
		$this->assign("pid",$pid);
		$this->assign("table_alias",$table_alias);
		switch($do){
			case "refresh":
				$cls_cate->clear();
				tAjax::json_success("刷新成功！");
				break;
			case "del":
				$id = R("id","int");
				if($id < 1000){
					tAjax::json_error("该{$table_alias}为保留{$table_alias}不能删除");
				}
				if(M($table)->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
					tAjax::json_error("该{$table_alias}下有子地区！不能删除");
				}
				if(M($table)->del("id='{$id}'")){
					$cls_cate->clear();
					tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>$url));
				}else{
					tAjax::json_error("删除失败！");
				}
				break;
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$id = R("id","int");
					$errors = array();
					$data = R("data","string");
					$reljob = R("reljob","int");
					if($reljob && is_array($reljob)){
						$data['jobs'] = implode(",",$reljob);
					}
					if(isset($data['sort'])){
						$data['sort'] = intval($data['sort']);
					}
					if(isset($data['status'])){
						$data['status'] = intval($data['status']);
					}else{
						$data['status'] = 0;
					}
					if(isset($data['pid']) && $data['pid'] != 0){
						tAjax::json_error("{$table_alias}当前只支持一级");
					}
					/* 表单验证 */
					if(empty($data['name'])){
						$errors['name'] = "{$table_alias}名不能为空!";
					}
					if(count($errors)){
						tAjax::json(array("error"=>1,"errors"=>$errors));
					}
					M($table)->set_data($data);
					if($id>0){
						$catlist = $cls_cate->get(0);
						if($data['pid'] && !isset($catlist[$data['pid']])){
							tAjax::json_error("上级已经不存在！");
						}
						if($data['pid'] == $id){
							tAjax::json_error("上级不能为自己！");
						}
						M($table)->update("id='{$id}'");
					}else{
						M($table)->add();
					}
					$cls_cate->clear();
					tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"{$url}?pid={$data['pid']}"));
				}else{
					$id  = R("id","int");
					if($id > 0){
						$data = M($table)->get_row("id='{$id}'");
					}
					if(!isset($data['id'])){
						$data = array(
							'id'   => 0,
							"pid"  => $pid,
							"name" => "",
							"status"=>1,
							"sort"=> 0,
								"jobs" =>array(),
						);
					}else{
						$data['jobs'] = explode(",",$data['jobs']);
					}
					$catpaths = $cls_cate->get_path($id?$id:$pid);
					$this->assign("catpaths",$catpaths);
					$this->assign("data",$data);
					$this->display("sys_manager/{$table}_config_edit");
				}
				break;
			default:
				$catelist = $cls_cate->get($pid);
				$catpaths = $cls_cate->get_path($pid);
				$this->assign("catlist",$catelist);
				$this->assign("catpaths",$catpaths);
				$this->display();
		}
	}
	//岗位数据配置
	public function job_cate_config(){
		$do          = R("do","string");
		$pid         = R("pid","int");
		$table       = "job_cate";
		$table_alias = "岗位";
		$cls_cate    = new cls_category($table);
		$url         = U("/sys_manager/{$table}_config");
		$this->assign("table",$table);
		$this->assign("url",$url);
		$this->assign("do",$do);
		$this->assign("pid",$pid);
		$this->assign("table_alias",$table_alias);
		switch($do){
			case "refresh":
				$cls_cate->clear();
				tAjax::json_success("刷新成功！");
				break;
			case "del":
				$id = R("id","int");
				if($id < 1000){
					tAjax::json_error("该{$table_alias}为保留{$table_alias}不能删除");
				}
				if(M($table)->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
					tAjax::json_error("该{$table_alias}下有子地区！不能删除");
				}
				if(M($table)->del("id='{$id}'")){
					$cls_cate->clear();
					tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>$url));
				}else{
					tAjax::json_error("删除失败！");
				}
				break;
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$id = R("id","int");
					$errors = array();
					$data = R("data","string");
					if(isset($data['sort'])){
						$data['sort'] = intval($data['sort']);
					}
					if(isset($data['status'])){
						$data['status'] = intval($data['status']);
					}else{
						$data['status'] = 0;
					}

					/* 表单验证 */
					if(empty($data['name'])){
						$errors['name'] = "{$table_alias}名不能为空!";
					}
					if(count($errors)){
						tAjax::json(array("error"=>1,"errors"=>$errors));
					}
					M($table)->set_data($data);
					if($id>0){
						$catlist = $cls_cate->get(0);
						if($data['pid'] && !isset($catlist[$data['pid']])){
							tAjax::json_error("上级已经不存在！");
						}
						if($data['pid'] == $id){
							tAjax::json_error("上级不能为自己！");
						}
						M($table)->update("id='{$id}'");
					}else{
						M($table)->add();
					}
					$cls_cate->clear();
					tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"{$url}?pid={$data['pid']}"));
				}else{
					$id  = R("id","int");
					if($id > 0){
						$data = M($table)->get_row("id='{$id}'");
					}
					if(!isset($data['id'])){
						$data = array(
							'id'   => 0,
							"pid"  => $pid,
							"name" => "",
							"status"=>1,
							"sort"=> 0,
							"zb_type"=>0,
							"zb_balance"=>10,
						);
					}
					$catpaths = $cls_cate->get_path($id?$id:$pid);
					$this->assign("catpaths",$catpaths);
					$this->assign("data",$data);
					$this->display("sys_manager/{$table}_config_edit");
				}
				break;
			default:
				$catelist = $cls_cate->get($pid);
				$catpaths = $cls_cate->get_path($pid);
				$this->assign("catlist",$catelist);
				$this->assign("catpaths",$catpaths);
				$this->display();
		}
	}
	//地区数据配置
	public function area_config(){
		$do          = R("do","string");
		$pid         = R("pid","int");
		$table       = "area";
		$table_alias = "地区";
		$cls_cate    = new cls_category($table);
		$url         = U("/sys_manager/{$table}_config");
		$this->assign("table",$table);
		$this->assign("url",$url);
		$this->assign("do",$do);
		$this->assign("pid",$pid);
		$this->assign("table_alias",$table_alias);
		switch($do){
			case "refresh":
				$cls_cate->clear();
				tAjax::json_success("刷新成功！");
				break;
			case "del":
				$id = R("id","int");
				if($id < 1000){
					tAjax::json_error("该{$table_alias}为保留{$table_alias}不能删除");
				}
				if(M($table)->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
					tAjax::json_error("该{$table_alias}下有子地区！不能删除");
				}
				if(M($table)->del("id='{$id}'")){
					$cls_cate->clear();
					tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>$url));
				}else{
					tAjax::json_error("删除失败！");
				}
				break;
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$id = R("id","int");
					$errors = array();
					$data = R("data","string");
					if(isset($data['sort'])){
						$data['sort'] = intval($data['sort']);
					}
					if(isset($data['status'])){
						$data['status'] = intval($data['status']);
					}else{
						$data['status'] = 0;
					}
					/* 表单验证 */
					if(empty($data['name'])){
						$errors['name'] = "{$table_alias}名不能为空!";
					}
					if(count($errors)){
						tAjax::json(array("error"=>1,"errors"=>$errors));
					}
					M($table)->set_data($data);
					if($id>0){
						$catlist = $cls_cate->get(0);
						if($data['pid'] && !isset($catlist[$data['pid']])){
							tAjax::json_error("上级已经不存在！");
						}
						if($data['pid'] == $id){
							tAjax::json_error("上级不能为自己！");
						}
						M($table)->update("id='{$id}'");
					}else{
						M($table)->add();
					}
					$cls_cate->clear();
					tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"{$url}?pid={$data['pid']}"));
				}else{
					$id  = R("id","int");
					if($id > 0){
						$data = M($table)->get_row("id='{$id}'");
					}
					if(!isset($data['id'])){
						$data = array(
							'id'   => 0,
							"pid"  => $pid,
							"name" => "",
							"status"=>1,
							"sort"=> 0,
						);
					}
					$catpaths = $cls_cate->get_path($id?$id:$pid);
					$this->assign("catpaths",$catpaths);
					$this->assign("data",$data);
					$this->display("sys_manager/{$table}_config_edit");
				}
				break;
			default:
				$catelist = $cls_cate->get($pid);
				$catpaths = $cls_cate->get_path($pid);
				$this->assign("catlist",$catelist);
				$this->assign("catpaths",$catpaths);
				$this->display();
		}
	}
	//城市站点数据配置
	public function city_config(){
		$do          = R("do","string");
		$pid         = R("pid","int");
		$table       = "city";
		$table_alias = "城市";
		$cls_cate    = new cls_category($table);
		$url         = U("/sys_manager/{$table}_config");
		$this->assign("table",$table);
		$this->assign("url",$url);
		$this->assign("do",$do);
		$this->assign("pid",$pid);
		$this->assign("table_alias",$table_alias);
		switch($do){
			case "refresh":
				$cls_cate->clear();
				tAjax::json_success("刷新成功！");
				break;
			case "del":
				$id = R("id","int");
				if($id < 1000){
					tAjax::json_error("该{$table_alias}为保留{$table_alias}不能删除");
				}
				if(M($table)->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
					tAjax::json_error("该{$table_alias}下有子地区！不能删除");
				}
				if(M($table)->del("id='{$id}'")){
					$cls_cate->clear();
					tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>$url));
				}else{
					tAjax::json_error("删除失败！");
				}
				break;
			case "edit":
				$id = R("id","int");
				if(tUtil::check_hash()){
					$id = R("id","int");
					$errors = array();
					$data = R("data","string");
					if(isset($data['sort'])){
						$data['sort'] = intval($data['sort']);
					}
					if(isset($data['status'])){
						$data['status'] = intval($data['status']);
					}else{
						$data['status'] = 0;
					}
					/* 表单验证 */
					if(empty($data['name'])){
						$errors['name'] = "{$table_alias}名不能为空!";
					}
					if(count($errors)){
						tAjax::json(array("error"=>1,"errors"=>$errors));
					}
					M($table)->set_data($data);
					if($id>0){
						$catlist = $cls_cate->get(0);
						if($data['pid'] && !isset($catlist[$data['pid']])){
							tAjax::json_error("上级已经不存在！");
						}
						if($data['pid'] == $id){
							tAjax::json_error("上级不能为自己！");
						}
						M($table)->update("id='{$id}'");
					}else{
						M($table)->add();
					}
					$cls_cate->clear();
					tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"{$url}?pid={$data['pid']}"));
				}else{
					$id  = R("id","int");
					if($id > 0){
						$data = M($table)->get_row("id='{$id}'");
					}
					if(!isset($data['id'])){
						$data = array(
							'id'   => 0,
							"pid"  => $pid,
							"name" => "",
							"status"=>1,
							"sort"=> 0,
						);
					}
					$catpaths = $cls_cate->get_path($id?$id:$pid);
					$this->assign("catpaths",$catpaths);
					$this->assign("data",$data);
					$this->display("sys_manager/{$table}_config_edit");
				}
				break;
			default:
				$catelist = $cls_cate->get($pid);
				$catpaths = $cls_cate->get_path($pid);
				$this->assign("catlist",$catelist);
				$this->assign("catpaths",$catpaths);
				$this->display();
		}
	}
	//支付管理
	public function sys_payment(){
		$navs = array(
			"my" => array("label"=>"支付方式","url"=>U("/sys_manager/sys_payment?cr=my")),
			"all" => array("label"=>"所有支付方式","url"=>U("/sys_manager/sys_payment?cr=all")),
		);
		$cr = $this->_parse_navs($navs);
		$do = R("do","string");
		//初始化支付插件类
	    $payment = new cls_payment();
	    //获取已配置支付列表
	    $list = $payment->get_payment();
	    $this->assign("payment_list",$list);
		$this->display();
	}
	//添加/修改支付方式插件
    public function sys_payment_edit(){
    	$navs = array(
			"my" => array("label"=>"支付方式","url"=>U("/sys_manager/sys_payment?cr=my")),
			"all" => array("label"=>"所有支付方式","url"=>U("/sys_manager/sys_payment?cr=all")),
		);
		$cr = $this->_parse_navs($navs);;

    	if(tUtil::check_hash()){
    		//获取Post数据
	    	$pay_id                 = R("pay_id","int");
	    	$field['name']          = R("name","string");
	    	$field['type']          = R("type","string");
	    	$field['description']   = R("description",'string');
	    	$field['poundage_type'] = R("poundage_type","string");
	    	$poundage_rate          = R("poundage_rate","string");
	    	$poundage_fix           = R("poundage_fix","string");

	    	if($field['poundage_type']==1){
	    		$field['poundage'] = $poundage_rate;
	    	}else{
	    		$field['poundage'] = $poundage_fix;
	    	}

	        $field['plugin_id']    = R("id","int");
	        $field['order']        = R("order","int");
	        $pay_type              = R("pay_type","string");
	        $setting               = R("setting","string");
	        $field['note']         = R('note','string');

	        //上传文件处理
	        $field['config'] = serialize($setting);
			//添加、修改已配置的支付插件
			$payment = new cls_payment();
			$result = $payment->update($field,$pay_id);
			$url = '/sys_manager/sys_payment';
	    	if($result===false){
				if($payId)
					$url = 'sys_payment_edit/payid/'.$pay_id;
				else
					$url = 'sys_payment_edit/id/'.$field['plugin_id'];
				//$this->redirect($url);
			}
			tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>$url));
    	}else{
	        //支付方式插件编号
	        $plugin_id = R("id","int");
	        //支付方式配置编号
	        $pay_id = R("payid","int");
	        //初始化支付插件类
	        $payment = new cls_payment();
	        $pay_info = array('type'=>1,'poundage_rate'=>0,'poundage_fix'=>0,'poundage_type'=>1,'config'=>'','description'=>' ');

	        //如果支付配置编号已存在，查找支付方式配置表
	        if($pay_id!=null){
	        	$pay_info = M('payment')->get_row("id = ".$pay_id);
	        	$plugin_id = $pay_info['plugin_id'];
	        	if($pay_info['poundage_type']==1){
	        		$pay_info['poundage_rate'] = $pay_info['poundage'];
	        		$pay_info['poundage_fix'] = 0;
	        	}else{
	        		$pay_info['poundage_fix'] = $pay_info['poundage'];
	        		$pay_info['poundage_rate'] = 0;
	        	}
	        }
	        //根据支付插件编号 获取该插件的详细信息
	        $plugin_info = M('pay_plugin')->get_row("id = ".$plugin_id);
	        //根据支付插件file_path路径获取该支付插件的类
	        $pay_obj = $payment->load($plugin_info['file_path']);
	        if(!isset($pay_info['name'])){
	        	$pay_info['name'] = $plugin_info['name'];
	        }
	        $config = isset($pay_info['config']) ? unserialize($pay_info['config']) : array();
	         //获取支付插件字段
		    $aField = $pay_obj->getfields();
		    //支持货币
		    $pay_info['SupportCurrency'] = $payment->get_supportcur($pay_obj->supportCurrency);
		    if($aField){
			    //处理支付插件扩展属性
			    if(isset($config['ConnectType']))
			    {
			    	foreach($aField['ConnectType']['extendcontent'] as $key=>$val)
			    	{
					    foreach($val['value'] as $ekey => $eval)
					    {
					    	if(isset($config['bankId']))
					    	{
								foreach($config['bankId'] as $eitem)
								{
									if($eval['value']==$eitem)
									{
										$aField['ConnectType']['extendcontent'][$key]['value'][$ekey]['checked'] = 'checked';
										break;
									}
									else
									{
										$aField['ConnectType']['extendcontent'][$key]['value'][$ekey]['checked'] = '';
									}
								}
					    	}
					    }
			    	}
			    }
		    }

		    //插件类型
		    $pay_info['file_path'] = $plugin_info['file_path'];
	        $pay_info['config']    = $config;
	        $pay_info['attr_list'] = $aField;
	        $pay_info['plugin_id'] = $plugin_id;
	        $pay_info['pay_id']    = $pay_id;
	        //把数据渲染到视图
	        $this->assign($pay_info);
	        $this->display();
    	}
    }
	//删除配置的支付插件
    public function sys_payment_del(){
    	$return = array("error"=>0,"message"=>"删除成功！","callback"=>U("/sys_manager/sys_payment"));
        //支付方式配置编号
        $pay_id = R("payid");
        //支付方式配置表
        $pay_row     = M('payment')->get_row('id = '.$pay_id,'name');
		if(M('payment')->del('id = '.$pay_id)){
			tAjax::json($return);
		}
    }

	/*************************************************************************************************************/
	//ajaxedit
	public function sys_ajaxedit(){
		$table_arr = array('city','area','postcate',"ad","members",'ad_pos','friendslink','square_forums','square_threads');
		$key_arr = array('city','area','postcate',"ad","members",'ad_pos','friendslink','square_forums','square_threads');
		$field_arr = array('name','code','isindex','status','ishot','inhot','intui','intop','isbold','sort','isable');
		$id = R("id","int");
		$table_name = R("t","string");
		$field = R("f","string");
		$value = R("v","string");
		$key = R("k","string");
		$key = $key?$key:"id";
		if(empty($id) || !in_array($table_name,$table_arr) || !in_array($field,$field_arr))tAjax::error("您没有权限操作！");
        M($table_name)->set_data(array("{$field}"=>$value));
		if(M($table_name)->update("{$key}='{$id}'")){
			tAjax::success($value);
		}else{
			tAjax::error($value);
		}
	}
	//多平台登录管理
	public function sys_oauthor(){
		//管理员登录检查
		$this->display();
	}
	//多平台登录编辑
	public function sys_oauthor_edit(){
		//管理员登录检查
		$id = tUtil::filter(tGpc::get("id"),"int");
		if(tUtil::check_hash()){
			$result = array("error"=>0,"message"=>"修改成功","callback"=>tUrl::create('/sysadmin/sys_oauthor'));
			$data = array(
				'name'        => tUtil::filter(tGpc::get('name')),
				'is_close'    => tUtil::filter(tGpc::get('is_close'),"int"),
				'description' => tUtil::filter(tGpc::get('description')),
				'config'      => array(),
			);

			//获取字段数据
			$oauth_obj    = new cls_oauth($id);
			$oauth_fields = $oauth_obj->getFields();

			if(!empty($oauth_fields)){
				$parms = array_keys($oauth_fields);
				foreach($parms as $val){
					$data['config'][$val] = tUtil::filter(tGpc::get($val));
				}
			}

			$data['config'] = serialize($data['config']);
			M("oauth")->set_data($data);

			if($id >0){
				M("oauth")->update('id = '.$id);
			}else{
				M("oauth")->add();
			}
			tAjax::respons($result);
		}else{
			if($id > 0){
				$data = M("oauth")->get_row("id='{$id}'");
				if(!isset($data['id'])){
					$this->redirect("/site/error?msg=".urlencode("该信息没有找到")."&callback=".tUrl::create('/photo'));
				}
				$data['config'] = isset($data['config'])?unserialize($data['config']):"";
			}else{
				$data = array(
				 'id'=>0,
				 'name'=>'',
				 'config'=>array(),
				 'file'=>'',
				 'description'=>0,
				 'is_close'=>0
				);
			}
			$this->assign("data",$data);
			$this->display();
		}
	}
	//后台文件上传
	public function sys_editorupload(){
		$attach_name = "filedata";
		if(empty($_FILES[$attach_name]) === false){
			    $up_obj = new tUpload(2048,array("jpg","gif","png","bmp","jpeg"));
				$file_date_path = tTime::get_datetime("Y/m/d");
				$file_store_path = ROOT_PATH."attach/editor/".$file_date_path."/";
				$file_path = "attach/editor/".$file_date_path."/";

				$return_file = "";
				$error_message = "";
				$up_obj->set_dir($file_store_path);
				$upstate = $up_obj->execute();
				if(!isset($upstate[$attach_name])){
					 $error_message = '没有上传文件';
				}else{
					if($upstate[$attach_name][0]['flag']==-1){
						$error_message = '上传的文件类型不符合';
					}else if($upstate[$attach_name][0]['flag']==-2){
						$error_message = '大小超过限度';
					}else if($upstate[$attach_name][0]['flag']==1){
						$return_file = $file_path.'/'.$upstate[$attach_name][0]['name'].".".$upstate[$attach_name][0]['ext'];
					}
				}
				if($return_file != ''){
					tAjax::respons(array("err"=>$error_message,"msg"=>U("").$return_file),"json");
				}else{
					tAjax::respons(array("err"=>$error_message,"msg"=>""),"json");
				}
	    }
	}
	//快速登录
	public function quick_login(){
		if(!$id = intval(tGpc::get("id")))$this->msg("ID错误！");
		$userinfo = M("members")->get_row("id='{$id}'");
		if(isset($userinfo['id'])){
			 $user_name = $userinfo['ni']?$userinfo['ni']:($userinfo['email']?$userinfo['email']:$userinfo['mobile']);
		 	 tSafe::set('user_name',$user_name);
	    	 tSafe::set('user_id',$userinfo['id']);
	    	 tSafe::set('user_type',$userinfo['utype']);

		     $authstr  = $userinfo['password']."\t".$user_name."\t".$userinfo['id'];
			 tCookie::set("auth",$authstr);
			 $this->redirect('/ucenter');
		}else{
		 	$this->redirect("/site/error?msg=".urlencode("快登失败！")."&callback=".tUrl::create('/photo'));
		}
	}


	//反馈信息删除
	public function sys_fankui_del(){
		$id = R("id","int");
		if(M("client_guessbook")->del("id='{$id}'")){
		}
	    tAjax::respons(array("error"=>0,"message"=>"删除成功！","callback"=>U("/sysadmin/sys_fankui")));
	}
	//给指定用户发送邮件
	public function sys_toemail(){
		global $user_id;
		$email_address = R("email_address","string");
		if(tUtil::check_hash()){
			$email_content = R("email_content","string");
			$email_title = R("email_title","string");
			$this->message = nl2br($email_content);
			if(C('mail')->send($email_address,$email_title,$this->fetch("email/email_com"))){
				X('tLog')->write('operation',array("管理员({$user_id})","发送邮件","给[{$email_address}]发送了一封{$email_title}邮件"));
				tAjax::respons(array("error"=>0,"message"=>"发送成功！"),"json");
			}
			tAjax::respons(array("error"=>1,"message"=>"发送失败！"),"json");
		}else{
			$this->assign("email_address",$email_address);
			tAjax::success($this->fetch("sysadmin/sys_toemail"));
		}
	}
	//系统首页提醒通知
	public function sys_tips(){
		$t = R("t","string");
		$ehtml = "";
		switch($t){
			case "index_r_b_fix":
				//URL审核
				$url_sh_count = M("dns_records")->get_one("RRtype IN('URL显性','URL隐性') AND insh = 0","count(*)");
				if($url_sh_count>0){
					$ehtml .= "<div id='Dsystip' class='systip' style='right:-180px;'><div class='sys-tip-box'>";
					$ehtml .= "<div class='tip-item'><label>URL待审核：</label><b class='txt-red'>{$url_sh_count}</b> 个&nbsp;&nbsp;<a href='".U("/sysadmin_dns/dnsrecord?url_sh=1")."' class='txt-blue'>查看</a></div>";
					$ehtml .= "</div><div class='sys-tip-close'><a class='txt-gray' onclick='$(this).parent().parent().hide();' href='javascript:void(0);'><img src='".U('/themes/default/images/admin/icon_close.gif')."'/>&nbsp; 关闭</a></div></div>";
				}
				break;
			default:
				break;
		}
		echo "document.write(\"{$ehtml}\");";
		echo "$('#Dsystip').animate({'right':10},300);";
		echo "setTimeout(\"$('#Dsystip').animate({'right':-180},300);\",5000);";
	}
	//系统统计图
	public function index_sys_count_image(){
		$t = R("t","string");
		$w = R("w","int");
		$h = R("h","int");
		$ehtml = "";
		//年份,月份
		$month = R("month","int");
		$year = R("year","int");
		$month = $month?$month:intval(tTime::get_datetime("m"));
		$year = $year?$year:intval(tTime::get_datetime("Y"));

		switch($t){
			case "user_reg_month":
				$month_day_count = tTime::get_month_day($month,$year);
				$data_month = $tgdata_month = array();
				$total = $tgtotal = 0;
				for($i=1;$i<=$month_day_count;$i++){
					$start_time = $end_time = 0;
					//确定开始时间
					$start_time = tTime::get_time("{$year}-{$month}-{$i}");
					//确定结束时间
					if($i == $month_day_count){
						if($month == 12){
							$end_time = tTime::get_time(($year+1)."-1-1");
						}else{
							$end_time = tTime::get_time("{$year}-".($month+1)."-1");
						}
					}else{
						$end_time = tTime::get_time("{$year}-{$month}-".($i+1));
					}
					$data_month[$i] = M("user")->get_one("regdateline >{$start_time} AND regdateline < {$end_time}","count(uid)");
					$start_time = strtotime("-1 months",$start_time);
					$end_time = strtotime("-1 months",$end_time);
					$tgdata_month[$i] = M("user")->get_one("regdateline >{$start_time} AND regdateline < {$end_time}","count(uid)");
					$total  = $total +$data_month[$i] ;
					$tgtotal = $tgtotal +$tgdata_month[$i] ;
				}
				ksort($data_month);

				$ehtml .= "document.write('<div class=\"countimage-box\" id=\"{$t}\" style=\"width: {$w}px; height:{$h}px; margin: 0 auto\"><img alt=\"正在加载中\" class=\"loading\" src=\" / common / images / loading2 . gif\" /></div>');";
				$ehtml .= '$("#'.$t.'").highcharts({
					            chart: {
					                type: "line",
					                marginRight: 130,
					                marginBottom: 25
					            },
					            title: {
					                text: "八戒DNS注册会员月统计",
					                x: -20 //center
					            },
					            subtitle: {
					                text: "'.$month.'月份总注册'.$total.'人,'.($month - 1).'月份总注册'.$tgtotal.'人",
					                x: -20
					            },
					            xAxis: {
					                categories: ['.implode(',',array_keys($data_month)).'],
					            	
					            },
					            yAxis: {
					                title: {
					                    text: "注册量"
					                },
					                plotLines: [{
					                    value: 0,
					                    width: 1,
					                    color: "#808080"
					                }]
					            },
					            tooltip: {
					                valueSuffix: "个",
//					                formatter:  function() {  
//					                    return "<p style=\'line-height:24px;\'>'.("{$year}年").'</p><p style=\'line-height:24px;\'>"+this.series.name+"["+this.x+"日]:<strong class=\"txt-org\">"+this.y+"</strong> 人</p>";  
//					                },
					                shadow:false,
					                shared: true,
					                crosshairs: true,
					                borderColor: "#bbbbbb",
								    borderRadius: 0,
								    borderWidth: 1
					            },
					            legend: {
					                layout: "vertical",
					                align: "right",
					                verticalAlign: "top",
					                x: -10,
					                y: 100,
					                borderWidth: 0
					            },
					            series: [{
					                name: "'.$month.'月注册量",
					                data: ['.implode(',',$data_month).'],
					                color:"#48BEF4",
					                 index:1,
					                 marker: {
                						symbol: "diamond"
            						}
					            },
					            {
					                name: "'.($month-1).'月注册量",
					                data: ['.implode(',',$tgdata_month).'],
					                color:"#F9CDC3",
					            }]
					        });';
				break;
			case "user_reg_year":
				$data_month = $tgdata_month = array();
				$total = $tgtotal = 0;
				for($i=1;$i<=12;$i++){
					$start_time = $end_time = 0;
					//确定开始时间
					$start_time = tTime::get_time("{$year}-{$i}-1");
					//确定结束时间
					if($i == 12){
						$end_time = tTime::get_time(($year+1)."-1-1");
					}else{
						$end_time = tTime::get_time("{$year}-".($i+1)."-1");
					}
					$data_month[$i] = M("user")->get_one("regdateline >{$start_time} AND regdateline < {$end_time}","count(uid)");
					$start_time = strtotime("-1 years",$start_time);
					$end_time = strtotime("-1 years",$end_time);
					$tgdata_month[$i] = M("user")->get_one("regdateline >{$start_time} AND regdateline < {$end_time}","count(uid)");
					$total  = $total +$data_month[$i] ;
					$tgtotal = $tgtotal +$tgdata_month[$i] ;
				}
				ksort($data_month);

				$ehtml .= "document.write('<div class=\"countimage-box\" id=\"{$t}\" style=\"width: {$w}px; height:{$h}px; margin: 0 auto\"><img alt=\"正在加载中\" class=\"loading\" src=\" / common / images / loading2 . gif\" /></div>');";
				$ehtml .= '$("#'.$t.'").highcharts({
					            chart: {
					                marginRight: 130,
					                marginBottom: 25
					            },
					            title: {
					                text: "八戒DNS注册会员年统计",
					                x: -20 //center
					            },
					            subtitle: {
					                 text: "'.$year.'年总注册'.$total.'人,'.($year - 1).'年总注册'.$tgtotal.'人",
					                x: -20
					            },
					            xAxis: {
					                categories: ['.implode(',',array_keys($data_month)).'],
					            },
					            yAxis: {
					                title: {
					                    text: "注册量"
					                },
					                plotLines: [{
					                    value: 0,
					                    width: 1,
					                    color: "#808080"
					                }]
					            },
					            tooltip: {
					                valueSuffix: "个",
//					                formatter:  function() {  
//					                    return "<p style=\'line-height:24px;\'>"+this.series.name+"<p style=\'line-height:24px;\'>'.("[").'"+this.x+"月]</p>:<strong class=\"txt-org\">"+this.y+"</strong> 人</p>";  
//					                },
					                shadow:false,
					                shared: true,
					                crosshairs: true,
					                borderColor: "#bbbbbb",
								    borderRadius: 0,
								    borderWidth: 1
					            },
					            legend: {
					                layout: "vertical",
					                align: "right",
					                verticalAlign: "top",
					                x: -10,
					                y: 100,
					                borderWidth: 0
					            },
					            series: [{
					                name: "'.$year.'年注册量",
					                data: ['.implode(',',$data_month).'],
					                color:"#48BEF4",
					                index:1,
					                marker: {
                						symbol: "diamond"
            						}
					            },
					            {
					                name: "'.($year-1).'年注册量",
					                data: ['.implode(',',$tgdata_month).'],
					                color:"#F9CDC3",
					            }]
					        });';
				break;
			case "user_account_month":
				$month_day_count = tTime::get_month_day($month,$year);
				$data_month = $data_month_1 = $data_month_2 = array();
				$total = $total_1 = $total_2 = $total_3 = 0;
				for($i=1;$i<=$month_day_count;$i++){
					$start_time = $end_time = 0;
					//确定开始时间
					$start_time = tTime::get_time("{$year}-{$month}-{$i}");
					//确定结束时间
					if($i == $month_day_count){
						if($month == 12){
							$end_time = tTime::get_time(($year+1)."-1-1");
						}else{
							$end_time = tTime::get_time("{$year}-".($month+1)."-1");
						}
					}else{
						$end_time = tTime::get_time("{$year}-{$month}-".($i+1));
					}
					//用户充值
					$data_month_1[$i] = intval(M("recharge")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND status=1","sum(amount)"));
					$start_time = strtotime("-1 months",$start_time);
					$end_time = strtotime("-1 months",$end_time);
					//用户充值上一月
					$data_month_2[$i] = intval(M("recharge")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND status=1","sum(amount)"));

					//用户总充值
					$total_1  = $total_1 + abs($data_month_1[$i]);
					//上一月
					$total_2 =  $total_2 + abs($data_month_2[$i]);
				}

				$ehtml .= "document.write('<div class=\"countimage-box\" id=\"{$t}\" style=\"width: {$w}px; height:{$h}px; margin: 0 auto\"><img alt=\"正在加载中\" class=\"loading\" src=\" / common / images / loading2 . gif\" /></div>');";
				$ehtml .= '$("#'.$t.'").highcharts({
					            chart: {
					                type: "line",
					                marginRight: 130,
					                marginBottom: 25
					            },
					            title: {
					                text: "八戒DNS充值金额月统计",
					                x: -20 //center
					            },
					            subtitle: {
					                text: "'.$month.'月份总充值'.$total_1.'元,'.($month-1).'月份总充值'.$total_2.'元",
					                x: -20
					            },
					            xAxis: {
					                categories: ['.implode(',',array_keys($data_month_1)).'],
					            	
					            },
					            yAxis: {
					                title: {
					                    text: "金额"
					                },
					                plotLines: [{
					                    value: 0,
					                    width: 1,
					                    color: "#808080"
					                }]
					            },
					            tooltip: {
					                valueSuffix: "元",
//					                formatter:  function() {  
//					                    return "<p style=\'line-height:24px;\'>'.("{$year}年").'</p><p style=\'line-height:24px;\'>"+this.series.name+"["+this.x+"日]:<strong class=\"txt-org\">"+this.y+"</strong> 元</p>";  
//					                },
					                shadow:false,
					                shared: true,
					                crosshairs: true,
					                borderColor: "#bbbbbb",
								    borderRadius: 0,
								    borderWidth: 1
					            },
					            legend: {
					                layout: "vertical",
					                align: "right",
					                verticalAlign: "top",
					                x: -10,
					                y: 100,
					                borderWidth: 0
					            },
					            series: [{
					                name: "'.$month.'月总充值",
					                data: ['.implode(',',$data_month_1).'],
					                color:"#48BEF4",
					                index:1,
					                marker: {
                						symbol: "diamond"
            						}
					            },
					            {
					                name: "'.($month-1).'月总充值",
					                data: ['.implode(',',$data_month_2).'],
					                color:"#F9CDC3",
					            }]
					        });';
				break;
			case "user_account_year":
				$data_month = $data_month_1 = $data_month_2 = array();
				$total = $total_1 = $total_2 = $total_3 = 0;
				for($i=1;$i<=12;$i++){
					$start_time = $end_time = 0;
					//确定开始时间
					$start_time = tTime::get_time("{$year}-{$i}-1");
					//确定结束时间
					if($i == 12){
						$end_time = tTime::get_time(($year+1)."-1-1");
					}else{
						$end_time = tTime::get_time("{$year}-".($i+1)."-1");
					}

					//用户充值
					$data_month_1[$i] = intval(M("recharge")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND status=1","sum(amount)"));
					$start_time = strtotime("-1 years",$start_time);
					$end_time = strtotime("-1 years",$end_time);
					//用户充值上一月
					$data_month_2[$i] = intval(M("recharge")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND status=1","sum(amount)"));

					//用户总充值
					$total_1  = $total_1 + abs($data_month_1[$i]);
					//用户总充值
					$total_2 =  $total_2 + abs($data_month_2[$i]);
				}


				$ehtml .= "document.write('<div class=\"countimage-box\" id=\"{$t}\" style=\"width: {$w}px; height:{$h}px; margin: 0 auto\"><img alt=\"正在加载中\" class=\"loading\" src=\" / common / images / loading2 . gif\" /></div>');";
				$ehtml .= '$("#'.$t.'").highcharts({
					            chart: {
					                marginRight: 130,
					                marginBottom: 25
					            },
					            title: {
					                text: "八戒DNS充值金额年统计",
					                x: -20 //center
					            },
					            subtitle: {
					                text: "'.$year.'年总充值'.$total_1.'元,'.($year - 1).'年总充值'.$total_2.'元",
					                x: -20
					            },
					            xAxis: {
					                categories: ['.implode(',',array_keys($data_month_1)).'],
					            	
					            },
					            yAxis: {
					                title: {
					                    text: "金额"
					                },
					                plotLines: [{
					                    value: 0,
					                    width: 1,
					                    color: "#808080"
					                }]
					            },
					            tooltip: {
					                valueSuffix: "元",
//					                formatter:  function() {  
//					                    return "<p style=\'line-height:24px;\'>"+this.series.name+"<p style=\'line-height:24px;\'>'.("[").'"+this.x+"月]</p>:<strong class=\"txt-org\">"+this.y+"</strong> 元</p>";  
//					                },
					                shadow:false,
					                shared: true,
					                crosshairs: true,
					                borderColor: "#bbbbbb",
								    borderRadius: 0,
								    borderWidth: 1
					            },
					            legend: {
					                layout: "vertical",
					                align: "right",
					                verticalAlign: "top",
					                x: -10,
					                y: 100,
					                borderWidth: 0					  
					            },
					            series: [{
					                name: "'.$year.'年充值",
					                data: ['.implode(',',$data_month_1).'],
					                color:"#48BEF4",
					                index:1,
					                marker: {
                						symbol: "diamond"
            						}
					            },
					            {
					            	name: "'.($year-1).'年充值",
					                data: ['.implode(',',$data_month_2).'],
					                color:"#F9CDC3",
					            }]
					        });';
				break;
			default:
				break;
		}
		echo $ehtml;
	}
	//清除整站缓存
	public function flush_cache(){
		//清除模版缓存
		if(tFile::clear_dir(ROOT_PATH."cache/front/")){
			C("user")->log("清除模版缓存成功！","目录/CACHE/FRONT");
		}
		if(C("site")->write_cache_dataconfig()){
			C("user")->log("清除基础数据缓存成功！","DATACONFIG");
		}
		if(tCache::flush()){
			C("user")->log("清除数据缓存成功！","MEMCACHE CACHE");
		}
		tAjax::json_success("操作成功!");
	}

	//系统短信
	public function sys_sms(){
		global $uid;
		//配置
		$do=R('do','string');
		$pageurl = U("/sys_manager/sys_sms?do=get");
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
						$wherestr .= $v?(" AND (dateline >= '".strtotime($v)."')"):"";
						break;
					case "enddate":
						$wherestr .= $v?(" AND (dateline <= '".strtotime($v)."')"):"";
						break;
					case 'keyword':
						$v = ($v == '关键词')?'':$v;
						$wherestr .= $v?" AND (tpl LIKE '%$v%' OR content LIKE '%$v%' OR mobile LIKE '%$v%')":"";
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
				$c['order']    = "dateline DESC";
				$c['fields']   = "*";
				$result = Q("sys_sms")->get_list($c,$pageurl);
				$uuinfo = array();
				if($result['list']){

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
	//系统邮件
	public function sys_email(){
		global $uid;
		//配置
		$do=R('do','string');
		$pageurl = U("/sys_manager/sys_email?do=get");
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
						$wherestr .= $v?(" AND (dateline >= '".strtotime($v)."')"):"";
						break;
					case "enddate":
						$wherestr .= $v?(" AND (dateline <= '".strtotime($v)."')"):"";
						break;
					case 'keyword':
						$v = ($v == '关键词')?'':$v;
						$wherestr .= $v?" AND (tpl LIKE '%$v%' OR content LIKE '%$v%' OR email LIKE '%$v%')":"";
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
				$c['order']    = "dateline DESC";
				$c['fields']   = "*";
				$result = Q("sys_email")->get_list($c,$pageurl);
				$uuinfo = array();
				if($result['list']){

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
	//DNS服务器状态请求次数
	public function index_mac_query(){
		$mac = R("mac","string");
		$count = SDKdns::query_log($mac);
		if ($count) {
			tAjax::json_success($count);
		}else{
			tAjax::json_error(0);
		}
	}
	//dns服务器请求图
	public function index_find_ask(){
		global $timestamp;
		$mac1 = R("mac1","string");
		$mac2 = R("mac2","string");
//		if (empty($mac1) || empty($mac2)) {
//			tAjax::json_error("请求出错");
//		}
		$count1 = $count2 = array();
		$curr_month_dateline = strtotime(tTime::get_datetime("Y-m-d",$timestamp))+86400;
		for($day= 31;$day > 0;$day--){
			$start_dateline = $curr_month_dateline - $day*86400;
			$end_dateline   = $start_dateline + 86400;
			$count1 = SDKdns::query_log($mac1,array(),$start_dateline,$end_dateline);
			$count2 = SDKdns::query_log($mac2,array(),$start_dateline,$end_dateline);
			$res['mac1'][tTime::get_datetime("m-d",$start_dateline)] = $count1;
			$res['mac2'][tTime::get_datetime("m-d",$start_dateline)] = $count2;
		}
		tAjax::json(array("error"=>0,"msg"=>$res));
	}
}
?>