<?php
class sysadmin extends tController{
	public $layout = "sysadmin";
	function __construct(){
		global $user_id;
		parent::__construct('sysadmin');
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
	public function index(){
		//管理员登录检查
		$this->display();
	}
	//登录
	public function login(){
		if(tUtil::check_hash()){
			
		}else{
			$this->display();
		}
	}
	//ajaxedit
	public function sys_ajaxedit(){
		$table_arr = array('area','postcate',"ad","members",'ad_pos','friendslink','square_forums','square_threads');
		$key_arr = array('area','postcate',"ad","members",'ad_pos','friendslink','square_forums','square_threads');
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
	//删除友情管理
    public function sys_friendslink_del(){
    	$return = array("error"=>0,"message"=>"删除成功！","callback"=>U("/sysadmin/sys_friendslink"));
        //支付方式配置编号
        $id = R("id","int");
        //支付方式配置表
        $data     = M('friendslink')->get_row('id = '.$id);
		if(M('friendslink')->del('id = '.$id)){
			//删除LOGO
			(isset($data['id']) && $data['logo']) && tFile::unlink(ROOT_PATH . $data['logo']);
			
			if($data['cat_id']>0){
				$datalist = M("friendslink")->query("status = 1 AND cat_id='{$data['cat_id']}'","*","sort ASC");
				if($datalist){
					$content = "";
					foreach($datalist as $k=>$v){
						$content .= "<a href='{$v['link']}' title='{$v['title']}' target='_blank'>".(($v['logo'] && file_exists(ROOT_PATH.$v['logo']))?"<img src='".U('/')."{$v['logo']}' title='{$v['name']}' />":$v['name'])."</a>";
					}
					tCache::write_static("friendslink_".$data['cat_id'],$content);
				}
			}
			tAjax::respons($return);
		}
    }
	//友情连接管理
	public function sys_friendslink_edit(){
        if(tUtil::check_hash()){
        	$data = R("data","string");
			$id = R("id","int");
			$data['status'] = $data['status']?1:0;
			
			//上传附件
			$attach_name = "upimgs";
			$error_message = "";
			if(empty($_FILES[$attach_name]) === false){
				$up_obj = new tUpload(2048,array("jpg","gif","png","bmp","jpeg"));
				$file_date_path = tTime::get_datetime("Y/m/d");
				$file_store_path = ROOT_PATH."attach/ad/".$file_date_path."/";
				$file_path = "attach/ad/".$file_date_path."/";
				
				$return_file = "";
				$up_obj->set_dir($file_store_path);
				$upstate = $up_obj->execute();
				if(isset($upstate[$attach_name])){
					if($upstate[$attach_name][0]['flag']==-1){
						$error_message = '上传的文件类型不符合';
					}else if($upstate[$attach_name][0]['flag']==-2){
						$error_message = '大小超过限度';
					}else if($upstate[$attach_name][0]['flag']==1){
						$data['logo'] = $file_path.$upstate[$attach_name][0]['name'].".".$upstate[$attach_name][0]['ext'];
					}
				}
			}
			M("friendslink")->set_data($data);
			if($id>0){
				$old_data = M("friendslink")->get_row("id='{$id}'");
				(isset($data['logo']) && $data['logo'] && $old_data['logo']) && tFile::unlink(ROOT_PATH . $old_data['logo']);
				
				M("friendslink")->update("id='{$id}'");
			}else{
				M("friendslink")->add();
			}
			if($data['cat_id']>0){
				$datalist = M("friendslink")->query("status = 1 AND cat_id='{$data['cat_id']}'","*","sort ASC");
			    if($datalist){
					$content = "";
					foreach($datalist as $k=>$v){
						$content .= "<a href='{$v['link']}' title='{$v['title']}' target='_blank'>".(($v['logo'] && file_exists(ROOT_PATH.$v['logo']))?"<img src='".U('/')."{$v['logo']}' title='{$v['name']}' />":$v['name'])."</a>";
					}
					tCache::write_static("friendslink_".$data['cat_id'],$content);
				}
			}
			tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin/sys_friendslink")),"json");
        }else{
        	$friendslink_cate = tCache::read("friendscate");
        	$this->assign("friendslink_cate",$friendslink_cate);
        	$this->display();
        }
	}
	//友情链接分类管理
	public function sys_friendslink_cate(){
		$do = R("do");
		switch($do){
			case "makecache":
				$datalist = $make_arr = array();
				$datalist = M("config")->query("name LIKE '%friendscate_%'","*");
				if(isset($datalist[0])){
					foreach($datalist as $k=>$v){
						$make_arr[str_replace("friendscate_","",$v['name'])] = unserialize($v['value']);
					}
				}
				if(tCache::write('friendscate',$make_arr['cate'])){
					tAjax::success("生成缓存成功！");
				}else{
					tAjax::error("生成缓存失败！");
				}
				break;
			case "edit":
				$item = R("item","string");
				$names = R('name',"string");
				$codes = R('code',"string");
				$data = array();
				if(count($codes) > 0){
					foreach($codes as $k=>$v){
						if($v && $names[$k])$data[$v]=$names[$k];
					}
					$is = M('config')->get_one("name='friendscate_{$item}'","count(*)");
		    		if( $is == 0){
		    		    M('config')->set_data(array("name"=>"friendscate_{$item}","value"=>serialize($data)));
		    		    M('config')->add();
		    		}elseif($is == 1){
		    			M('config')->set_data(array("value"=>serialize($data)));
		    			M('config')->update("name='friendscate_{$item}'");
		    		}
					tAjax::success("保存成功！");
				}
				tAjax::error("保存失败");
				break;
			case "get":
				$item = R("item","string");
				$data = M('config')->get_one("name='friendscate_{$item}'","value");
				$datalist = $data?unserialize($data):array();
				$this->assign("item",$item);
				$this->assign("datalist",$datalist);
				tAjax::success($this->fetch('sysadmin/sys_friendslink_cate_edit'));
			default:
				$this->display();
				break;
		}
	}
	//支付管理
	public function sys_payment(){
		//初始化支付插件类
     	$payment = new cls_payment();
     	//获取已配置支付列表
     	$list = $payment->get_payment();
     	$this->assign("payment_list",$list);
		$this->display();
	}
    //添加/修改支付方式插件
    public function sys_payment_edit(){
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
	    	if($result===false){
				if($payId)
					$url = 'sys_payment_edit/payid/'.$pay_id;
				else
					$url = 'sys_payment_edit/id/'.$field['plugin_id'];
				$this->redirect($url);
			}else{
				$this->redirect('/sysadmin/sys_payment');
			}
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
    	$return = array("error"=>0,"message"=>"清除缓存成功！","callback"=>U("/sysadmin/sys_payment"));
        //支付方式配置编号
        $pay_id = R("payid");
        //支付方式配置表
        $pay_row     = M('payment')->get_row('id = '.$pay_id,'name');
		if(M('payment')->del('id = '.$pay_id)){
			tAjax::respons($return);
		}
    }
	//广告添加与修改
	public function sys_ad_edit(){
		if(tUtil::check_hash()){
			$data = R("data","string");
			$id = R("id","int");
			$data['starttime'] = tTime::get_time($data['starttime']);
			$data['endtime'] = tTime::get_time($data['endtime']);
			$data['status'] = $data['status']?1:0; 
			$adpos = M("ad_pos")->get_row("id='{$data['typeid']}'");
			
            if(!isset($adpos['id']))tAjax::json_error("请选择广告位！");
			$data['typenote'] = $adpos['adtype'];
			//上传附件
			$attach_name = "upimgs";
			$error_message = "";
			if($data['adtype'] != 'txt' && empty($_FILES[$attach_name]) === false){
				$up_obj = new tUpload(2048,array("jpg","gif","png","bmp","jpeg"));
				$file_date_path = tTime::get_datetime("Y/m/d");
				$file_store_path = ROOT_PATH."attach/ad/".$file_date_path."/";
				$file_path = "attach/ad/".$file_date_path."/";
				
				$return_file = "";
				$up_obj->set_dir($file_store_path);
				$upstate = $up_obj->execute();
				if(isset($upstate[$attach_name])){
					if($upstate[$attach_name][0]['flag']==-1){
						$error_message = '上传的文件类型不符合';
					}else if($upstate[$attach_name][0]['flag']==-2){
						$error_message = '大小超过限度';
					}else if($upstate[$attach_name][0]['flag']==1){
						$data['imgurl'] = $file_path.$upstate[$attach_name][0]['name'].".".$upstate[$attach_name][0]['ext'];
						$thumb_w = R("thumb_w","int");
						$thumb_h = R("thumb_h","int");
						if($thumb_w>0 && $thumb_h>0){
							//tImage::fixthumb($data['imgurl'],$thumb_w,$thumb_h,"_s");
						}
					}
				}
			}
			if($error_message != '')tAjax::json_error($error_message);
			M("ad")->set_data($data);
			if($id>0){
				$old_data = M("ad")->get_row("id='{$id}'");
				(isset($data['imgurl']) && $data['imgurl'] && $old_data['imgurl']) && tFile::unlink(ROOT_PATH . $old_data['imgurl']);
				//$old_data['thumburl'] && @unlink(ROOT_PATH . "attach/ad/".$old_data['thumburl']);
				M("ad")->update("id='{$id}'");
			}else{
				M("ad")->add();
			}
		    $datalist = M("ad")->query("status = 1 AND typeid='{$data['typeid']}'","*","adsort DESC",$adpos['nums']);
		    tCache::write("ad_".$data['typeid'],$datalist);
			tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin/sys_ad?condition[typeid]={$data['typeid']}")),"json");
		}else{
			$this->display();
		}
	}
	//广告删除
	public function sys_ad_del(){
		$id = R("id","int");
		$old_data = M("ad")->get_row("id='{$id}'");
		if(M("ad")->del("id='{$id}'")){
	    	(isset($old_data['id']) && $old_data['imgurl']) && tFile::unlink(ROOT_PATH . $old_data['imgurl']);
		}
	    tAjax::respons(array("error"=>0,"message"=>"删除成功！","callback"=>U("/sysadmin/sys_ad?condition[typeid]={$old_data['typeid']}")));
	}
	//广告添加与修改
	public function sys_adpos_edit(){
		if(tUtil::check_hash()){
			$data = R("data","string");
			$id = R("id","int");
			$data['isable'] = $data['isable']?1:0;
			M("ad_pos")->set_data($data);
			if($id>0){
				M("ad_pos")->update("id='{$id}'");
			}else{
				M("ad_pos")->add();
			}
			tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin/sys_adpos")),"json");
		}else{
			$this->display();
		}
	}
	//广告删除
	public function sys_adpos_del(){
		$id = R("id","int");
		if($id>0 && M("ad")->get_one("typeid='{$id}'","count(*)")>0){
			 tAjax::error("该分类有广告不能删除！");
		}
		if(M("ad_pos")->del("id='{$id}'")){
	    	  tAjax::respons(array("error"=>0,"message"=>"删除成功！","callback"=>U("/sysadmin/sys_adpos")));
		}
	}
	//广告位刷新
	public function sys_adpos_refresh(){
		$id = R("id","int");
		if($id>0 && M("ad")->get_one("typeid='{$id}'","count(*)") == 0){
			 tAjax::error("该分类没有广告，无法刷新！");
		}
		$adpos = M("ad_pos")->get_row("id='{$id}'");
		if(isset($adpos['id'])){
			  $datalist = M("ad")->query("status = 1 AND typeid='{$adpos['id']}'","*","adsort DESC",$adpos['nums']);
	          tCache::write("ad_".$adpos['id'],$datalist);
	    	  tAjax::respons(array("error"=>0,"message"=>"刷新成功！"));
		}
	}
	//技能数据配置
	public function sys_techconfig(){
		$do = R("do");
		switch($do){
			case "makecache":
				$return = array("error"=>0,"message"=>"清除缓存成功！");
				if(tCache::delete('@cate_tech') && tCache::delete('@cate_tech_fetch')){
					$return['callback'] = U("/sysadmin/sys_techconfig");
					tAjax::respons($return);
				}else{
					tAjax::error("清除缓存失败！");
				}
				break;
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！");
				$id = R("id","int");
				$data = M("tech")->get_row("id='{$id}'");
				if(!isset($data['id']) || M("tech")->get_one("pid='{$id}'","count(*)")>0){
					tAjax::error("子分类不为空！");
				}else{
					M("tech")->del("id='{$id}'");
					$return['callback'] = U("/sysadmin/sys_techconfig");
					tAjax::respons($return);
				}
				break;
			case "edit":
					if(tUtil::check_hash()){
						$id = R("id","int");
						$data = Ra("pid,name,code,sort","int,string,string,int","post");
						M("tech")->set_data($data);
						if($id>0){
							M("tech")->update("id='{$id}'");
						}else{
							M("tech")->add();
						}
						tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin/sys_techconfig")));
					}else{
						tAjax::error("非法提交！");
					}
					break;
			default:
					$this->display();
					break;
		}
	}
	//岗位数据配置
	public function sys_postconfig(){
		//管理员登录检查
		$do = R("do");
		switch($do){
			case "makecache":
				$return = array("error"=>0,"message"=>"清除缓存成功！");
				if(tCache::delete('@cate_postcate') && tCache::delete('@cate_postcate_fetch')){
					$return['callback'] = U("/sysadmin/sys_postconfig");
					tAjax::respons($return);
				}else{
					tAjax::error("清除缓存失败！");
				}
				break;
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！");
				$id = R("id","int");
				$data = M("postcate")->get_row("id='{$id}'");
				if(!isset($data['id']) || M("postcate")->get_one("pid='{$id}'","count(*)")>0){
					tAjax::error("子分类不为空！");
				}else{
					M("postcate")->del("id='{$id}'");
					$return['callback'] = U("/sysadmin/sys_postconfig");
					tCache::del("seo_cate_".$data['code']);
					tAjax::respons($return);
				}
				break;
			case "edit":
					if(tUtil::check_hash()){
						$id = R("id","int");
						$data = Ra("pid,name,code,sort,isindex,ishot,isbold,status,seo_title,seo_keyword,seo_description","int,string,string,int,int,int,int,int,string,string,string","post");
						M("postcate")->set_data($data);
						if($id>0){
							M("postcate")->update("id='{$id}'");
						}else{
							M("postcate")->add();
						}
						tCache::del("seo_cate_".$data['code']);
						tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin/sys_postconfig")));
					}else{
						tAjax::error("非法提交！");
					}
					break;
			default:
					$this->display();
					break;
		}
	}
	//地区数据配置
	public function sys_areaconfig(){
		$do = R("do");
		switch($do){
			case "makecache":
				$return = array("error"=>0,"message"=>"清除缓存成功！");
				/* area */
				$datalist = M('area')->query("status=1","id,code,name,isindex,ishot,color,isbold,status","code ASC",900);
				$jscontent = "var areaData = [];";
				$data_all = $data = array();
				foreach($datalist as $k=>$v){
					$data[$v["code"]] = $v["name"];
					$data_all[$v["code"]] = $v;						
					$jscontent .= "areaData[{$v["code"]}] = '{$v["name"]}';\n";
				}
				tCache::write("area_config",$data,"_data");
				tCache::write("area_all_config",$data_all,"_data");
				unset($data,$datalist,$data_all);

				$jscache_name = ROOT_PATH."cache/static/appdata.js";
			    file_put_contents($jscache_name, $jscontent, LOCK_EX);
			    
				unset($data,$datalist,$data_all,$jsfile_obj);
				tAjax::respons($return);
				break;
			case "del":
				$return = array("error"=>0,"message"=>"删除成功！");
				$id = R("id","int");
				$data = M("area")->get_row("id='{$id}'");
				if(!isset($data['id']) || M("area")->get_one("pid='{$id}'","count(*)")>0){
					tAjax::error("子分类不为空！");
				}else{
					M("area")->del("id='{$id}'");
					$return['callback'] = U("/sysadmin/sys_areaconfig?pid={$data['pid']}");
					tAjax::respons($return);
				}
				break;
			case "edit":
					if(tUtil::check_hash()){
						$id = R("id","int");
						$data = Ra("pid,name,code,sort,isindex,ishot,isbold,status,seo_title,seo_keyword,seo_description","int,string,string,int,int,int,int,int,string,string,string","post");

						M("area")->set_data($data);
						if($id>0){
							M("area")->update("id='{$id}'");
						}else{
							M("area")->add();
						}
						tAjax::respons(array("error"=>0,"message"=>"操作成功！","callback"=>U("/sysadmin/sys_areaconfig?pid={$data['pid']}")));
					}else{
						tAjax::error("非法提交！");
					}
					break;
			default:
					$this->display();
					break;
		}
	}
	//网站配置
	public function sys_config(){
		if(tUtil::check_hash()){
			$data = R("data",'string','post');

		    foreach($data as $k=>$v){
		    	if($k){
		    		$is = M('config')->get_one("name='{$k}'","count(*)");
		    		if( $is == 0){
		    		    M('config')->set_data(array("name"=>"{$k}","value"=>$v));
		    		    M('config')->add();
		    		}elseif($is == 1){
		    			M('config')->set_data(array("value"=>$v));
		    			M('config')->update("name='{$k}'");
		    		}
		    	}
		    }
		    $data['copyright'] = htmlspecialchars_decode($data['copyright']);
		    isset($data['utypes']) && $data['utypes'] = explode(",",$data['utypes']);
		    isset($data['ugroups']) && $data['ugroups'] = explode(",",$data['ugroups']);
		    tCache::write('site_config',$data);
			tAjax::json_success("修改配置成功！");
		}else{
			$data = array();
			foreach(M("config")->query() as $v){
				$data[$v['name']] = $v['value'];	
			}
			$this->assign("data",$data);
			$this->display();
		}
	}
	//品牌数据配置
	public function sys_brandconfig(){
		//管理员登录检查
		$do = R("do");
		switch($do){
			case "makecache":
				$datalist = $make_arr = array();
				$datalist = M("config")->query("name LIKE '%brandconfig_%'","*");
				if(isset($datalist[0])){
					foreach($datalist as $k=>$v){
						$make_arr[str_replace("brandconfig_","",$v['name'])] = unserialize($v['value']);
					}
				}
				if(tCache::write('brand_config',$make_arr)){
					tAjax::success("生成缓存成功！");
				}else{
					tAjax::error("生成缓存失败！");
				}
				break;
			case "edit":
				$item = R("item","string");
				$names = R('name',"string");
				$codes = R('code',"string");
				$data = array();
				if(count($codes) > 0){
					foreach($codes as $k=>$v){
						if($v && $names[$k])$data[$v]=$names[$k];
					}
					$is = M('config')->get_one("name='brandconfig_{$item}'","count(*)");
		    		if( $is == 0){
		    		    M('config')->set_data(array("name"=>"brandconfig_{$item}","value"=>serialize($data)));
		    		    M('config')->add();
		    		}elseif($is == 1){
		    			M('config')->set_data(array("value"=>serialize($data)));
		    			M('config')->update("name='brandconfig_{$item}'");
		    		}
					tAjax::success("保存成功！");
				}
				tAjax::error("保存失败");
				break;
			case "get":
				$item = R("item","string");
				$data = M('config')->get_one("name='brandconfig_{$item}'","value");
				$datalist = $data?unserialize($data):array();
				$this->assign("item",$item);
				$this->assign("datalist",$datalist);
				tAjax::success($this->fetch('sysadmin/sys_brandconfig_edit'));
			default:
				$this->display();
				break;
		}
	}
	//数据配置
	public function sys_dataconfig(){
		//管理员登录检查
		$do = R("do");
		switch($do){
			case "makecache":
				$datalist = $make_arr = array();
				$datalist = M("config")->query("name LIKE '%dataconfig_%'","*");
				if(isset($datalist[0])){
					foreach($datalist as $k=>$v){
						$make_arr[str_replace("dataconfig_","",$v['name'])] = unserialize($v['value']);
					}
				}
				if(tCache::write('data_config',$make_arr)){
					tAjax::success("生成缓存成功！");
				}else{
					tAjax::error("生成缓存失败！");
				}
				break;
			case "edit":
				$item = R("item","string");
				$names = R('name',"string");
				$codes = R('code',"string");
				$data = array();
				if(is_array($codes) && count($codes) > 0){
					foreach($codes as $k=>$v){
						if($v && $names[$k])$data[$v]=$names[$k];
					}
				}
				$is = M('config')->get_one("name='dataconfig_{$item}'","count(*)");
		    	if( $is == 0){
		    		M('config')->set_data(array("name"=>"dataconfig_{$item}","value"=>serialize($data)));
		    		M('config')->add();
		    	}elseif($is == 1){
		    		M('config')->set_data(array("value"=>serialize($data)));
		    		M('config')->update("name='dataconfig_{$item}'");
		    	}
				tAjax::success("保存成功！");
				break;
			case "get":
				$item = R("item","string");
				$data = M('config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $data?unserialize($data):array();
				$this->assign("item",$item);
				$this->assign("datalist",$datalist);
				tAjax::success($this->fetch('sysadmin/sys_dataconfig_edit'));
			default:
				$this->display();
				break;
		}
	}
	//清除缓存
	public function sys_clearcache(){
		//管理员登录检查	
		if(tUtil::check_hash()){
			$result = array("error"=>0,"message"=>"");
			$templete_cache = R("templete_cache","int");
			$data_cache = R("data_cache","int");
			
			if($templete_cache == 1){
				if(tFile::clear_dir(ROOT_PATH."cache/front/")){
					$result['message'] .= "清除模版缓存成功！<br/>";
				}
			}
			if($data_cache == 1){
				if(tCache::flush()){
					$result['message'] .= "清除数据缓存成功！<br/>";
				}
			}
			tAjax::respons($result);
		}else{
			$this->display();
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
	//获取用户
	public function getmembers(){
		$hid = R("hid","string");
		$page = R("page","int");
		$toplist = array(
		 
		);
		$keyword = trim(R("keyword","string"));
		$utype = intval(R("utype","int"));

		$wherestr = "inlock=0";
		$wherestr .= $utype?(" AND utype=".($utype-1)):"";
		$wherestr .= $keyword?" AND (ni LIKE '%{$keyword}%' OR id LIKE '%{$keyword}%' OR email LIKE '%{$keyword}%' OR mobile LIKE '%{$keyword}%')":"";
		$pageurl = U("/sysadmin/getmembers?utype={$utype}&keyword=".urlencode($keyword));
		$query = new tQuery("members");
		$query->where = $wherestr;
		$query->page = $page;
		$query->order = "last_login_time DESC";
		$query->pagesize = 80;
		$datalist = $query->find();
		$pagebar = $query->get_pagebar($pageurl);
		
		$this->assign("keyword",$keyword);
		$this->assign("utype",$utype);
		$this->assign("datalist",$datalist);
		$this->assign("pagebar",tFun::pagebar_js($pagebar,$pageurl,"go_memberspage"));
		
		$this->assign("hid",$hid);
		$this->assign("toplist",$toplist);
		tAjax::success($this->fetch("sysadmin/getmembers"));
	}
	//IP原始库国家
	public function sys_iplibcountry(){
		$keyword = R("keyword","string");
		$keyword3 = R("keyword3","string");
		
		$where = $keyword?((substr($keyword,0,3) == "NO_")?("code2 <> '".substr($keyword,3)."'"):"code2='{$keyword}'"):"1";
		$where .= $keyword3?" AND (start_ip1 LIKE '{$keyword3}%' OR start_ip1 LIKE '{$keyword3}%')":"";
		
		$this->where = $where;
		$this->page = R("page","int");
		$this->assign("keyword",$keyword);
		$this->assign("keyword3",$keyword3);
		$this->display();
	}
	public function sys_iplibcountry_export(){
		$keyword = R("keyword","string");
		$keyword3 = R("keyword3","string");
		$db = tDB::get_db();
		$wherestr = $keyword?((substr($keyword,0,3) == "NO_")?("code2 <> '".substr($keyword,3)."'"):"code2='{$keyword}'"):"1";
		$where .= $keyword3?" AND (start_ip1 LIKE '{$keyword3}%' OR start_ip1 LIKE '{$keyword3}%')":"";
		
		$by = $keyword?"GROUP BY code2":"";
		$sql = "SELECT group_concat(duan SEPARATOR ';\n') AS ipdata FROM `rc_dns_iplibcountry` WHERE {$wherestr} $by";
        $db->query("SET group_concat_max_len = 3088000");
		$result = $db->query($sql);
		header('Content-Type: application/txt'); 
        header('Content-Disposition: attachment; filename="rz_'.($keyword?$keyword:"all").'.txt"'); 
        file_put_contents('php://output',$result[0]['ipdata']);  
	}
	//IP原始库城市
	public function sys_iplibcity(){
		$keyword1 = R("keyword1","string");
		$keyword2 = R("keyword2","string");
		$keyword3 = R("keyword3","string");
		$where = "1";
		$where .= $keyword1?" AND isp LIKE '{$keyword1}%'":"";
		$where .= $keyword2?" AND province LIKE '{$keyword2}%'":"";
		$where .= $keyword3?" AND (start_ip1 LIKE '{$keyword3}%' OR start_ip1 LIKE '{$keyword3}%')":"";
		$this->where = $where;
		$this->page = R("page","int");
		$this->assign("keyword1",$keyword1);
		$this->assign("keyword2",$keyword2);
		$this->assign("keyword3",$keyword3);
		$this->display();
	}
	public function sys_iplibcity_export(){
		$keyword1 = R("keyword1","string");
		$keyword2 = R("keyword2","string");
		$keyword3 = R("keyword3","string");
		$where = "1";
		$where .= $keyword1?" AND isp LIKE '{$keyword1}%'":"";
		$where .= $keyword2?" AND province LIKE '{$keyword2}%'":"";
		$where .= $keyword3?" AND (start_ip1 LIKE '{$keyword3}%' OR start_ip1 LIKE '{$keyword3}%')":"";
		
		$db = tDB::get_db();
		$sql = "SELECT group_concat(duan SEPARATOR ';\n') AS ipdata FROM `rc_dns_iplibcity` WHERE {$where}";
        $db->query("SET group_concat_max_len = 3088000");
		$result = $db->query($sql);
		header('Content-Type: application/txt'); 
        header('Content-Disposition: attachment; filename="rz_'.($keyword1?$keyword1:"all")."_".($keyword2?$keyword2:"all").'.txt"'); 
        file_put_contents('php://output',$result[0]['ipdata']);  
	}
	//ip转换
	public function sys_iplibcountry_convert(){
		set_time_limit(3600);
		$do = R("do","string");
		switch($do){
			case "country":
				$this->table_name = "dns_iplibcountry";
				break;
			case "city":
				$this->table_name = "dns_iplibcity";
				break;
			default:
				$this->table_name = "dns_iplibcountry";
				break;
		}
		//$this->display();
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
	public function sys_count_image(){
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
					$data_month[$i] = M("members")->get_one("reg_time >{$start_time} AND reg_time < {$end_time}","count(id)");
					$tgdata_month[$i] = M("members")->get_one("reg_time >{$start_time} AND reg_time < {$end_time} AND utype=2","count(id)");
					$total  = $total +$data_month[$i] ;
					$tgtotal = $tgtotal +$tgdata_month[$i] ;
				}
				ksort($data_month);
				
				$ehtml .= "document.write('<div class=\"countimage-box\" id=\"{$t}\" style=\"width: {$w}px; height:{$h}px; margin: 0 auto\"><img alt=\"正在加载中\" class=\"loading\" src=\"/common/images/loading2.gif\" /></div>');";
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
					                text: "总注册'.$total.'人,其中推广员'.$tgtotal.'人",
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
					                formatter:  function() {  
					                    return "<p style=\'line-height:24px;\'>'.("{$year}年{$month}月").'"+this.x+"日</p><p style=\'line-height:24px;\'>总注册<strong class=\"txt-org\">"+this.y+"</strong> 人</p>";  
					                },
					                shadow:false,
					                shared: false,
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
					                name: "总注册量",
					                data: ['.implode(',',$data_month).'],
					                color:"#4774a7",
					            },
					            {
					                name: "推广员注册量",
					                data: ['.implode(',',$tgdata_month).'],
					                color:"#89a54f",
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
					$data_month[$i] = M("members")->get_one("reg_time >{$start_time} AND reg_time < {$end_time}","count(id)");
					$tgdata_month[$i] = M("members")->get_one("reg_time >{$start_time} AND reg_time < {$end_time} AND utype=2","count(id)");
					$total  = $total +$data_month[$i] ;
					$tgtotal = $tgtotal +$tgdata_month[$i] ;
				}
				ksort($data_month);
				
				$ehtml .= "document.write('<div class=\"countimage-box\" id=\"{$t}\" style=\"width: {$w}px; height:{$h}px; margin: 0 auto\"><img alt=\"正在加载中\" class=\"loading\" src=\"/common/images/loading2.gif\" /></div>');";
				$ehtml .= '$("#'.$t.'").highcharts({
					            chart: {
					                type: "column",
					                marginRight: 130,
					                marginBottom: 25
					            },
					            title: {
					                text: "八戒DNS注册会员年统计",
					                x: -20 //center
					            },
					            subtitle: {
					                text: "总注册'.$total.'人,其中推广员'.$tgtotal.'人",
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
					                formatter:  function() {  
					                    return "<p style=\'line-height:24px;\'>'.("{$year}年{$month}月").'"+this.x+"日</p><p style=\'line-height:24px;\'>总注册<strong class=\"txt-org\">"+this.y+"</strong> 人</p>";  
					                },
					                shadow:false,
					                shared: false,
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
					                name: "总注册量",
					                data: ['.implode(',',$data_month).'],
					                color:"#4774a7",
					            },
					            {
					                name: "推广员注册量",
					                data: ['.implode(',',$tgdata_month).'],
					                color:"#89a54f",
					            }]
					        });';
				break;
			case "user_account_month":
				$month_day_count = tTime::get_month_day($month,$year);
				$data_month = $data_month_1 = $data_month_2 = array();
				$total = $total_1 = $total_2 = 0;
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
					$data_month_1[$i] = intval(M("account_log")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND event=1","sum(amount)"));
					$data_month_2[$i] = intval(M("account_log")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND event=3","sum(amount)"));
					
					$total_1  = $total_1 + $data_month_1[$i] ;
					$total_2 =  $total_2 + abs($data_month_2[$i]);
				}
				//确定开始时间
				$start_time = tTime::get_time("{$year}-{$month}-1");
				$end_time = tTime::get_time("{$year}-{$month}-{$month_day_count}")+86400;
				$tmp1 = intval(M("account_log")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND event=1 AND admin_id=0","sum(amount)"));//充值利润
				$tmp2 = intval(M("account_log")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND event=1 AND admin_id>0","sum(amount)"));
				$total = intval($tmp1*988/1000+$tmp2);
				$ehtml .= "document.write('<div class=\"countimage-box\" id=\"{$t}\" style=\"width: {$w}px; height:{$h}px; margin: 0 auto\"><img alt=\"正在加载中\" class=\"loading\" src=\"/common/images/loading2.gif\" /></div>');";
				$ehtml .= '$("#'.$t.'").highcharts({
					            chart: {
					                type: "line",
					                marginRight: 130,
					                marginBottom: 25
					            },
					            title: {
					                text: "八戒DNS账户金额'."{$year}年{$month}".'月统计",
					                x: -20 //center
					            },
					            subtitle: {
					                text: "总'.$total_1.'元,自助'.$tmp1.'元,管理员'.$tmp2.'元,总收入'.$total.'元,总消费'.$total_2.'元",
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
					                valueSuffix: "个",
					                formatter:  function() {  
					                    return "<p style=\'line-height:24px;\'>'.("{$year}年{$month}月").'"+this.x+"日</p><p style=\'line-height:24px;\'>金额<strong class=\"txt-org\">"+this.y+"</strong> 人</p>";  
					                },
					                shadow:false,
					                shared: false,
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
					                name: "总充值",
					                data: ['.implode(',',$data_month_1).'],
					                color:"#89a54f",
					            },
					            {
					                name: "消费",
					                data: ['.implode(',',$data_month_2).'],
					                color:"#aa4845",
					            }]
					        });';
				break;
			case "user_account_year":
				$data_month = $data_month_1 = $data_month_2 = array();
				$total = $total_1 = $total_2 = 0;
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
					$data_month_1[$i] = intval(M("account_log")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND event=1","sum(amount)"));
					$data_month_2[$i] = intval(M("account_log")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND event=3","sum(amount)"));
					
					$total_1  = $total_1 + $data_month_1[$i] ;
					$total_2 =  $total_2 + abs($data_month_2[$i]);
				}
				//确定开始时间
				$start_time = tTime::get_time("{$year}-1-1");
				$end_time = tTime::get_time("{$year}-12-31")+86400;
				$tmp1 = intval(M("account_log")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND event=1 AND admin_id=0","sum(amount)"));//充值利润
				$tmp2 = intval(M("account_log")->get_one("dateline >{$start_time} AND dateline < {$end_time} AND event=1 AND admin_id>0","sum(amount)"));
				$total = intval($tmp1*988/1000+$tmp2);
				
				$ehtml .= "document.write('<div class=\"countimage-box\" id=\"{$t}\" style=\"width: {$w}px; height:{$h}px; margin: 0 auto\"><img alt=\"正在加载中\" class=\"loading\" src=\"/common/images/loading2.gif\" /></div>');";
				$ehtml .= '$("#'.$t.'").highcharts({
					            chart: {
					                type: "column",
					                marginRight: 130,
					                marginBottom: 25
					            },
					            title: {
					                text: "八戒DNS账户金额'."{$year}年".'统计",
					                x: -20 //center
					            },
					            subtitle: {
					                text: "总'.$total_1.'元,自助'.$tmp1.'元,管理员'.$tmp2.'元,总收入'.$total.'元,总消费'.$total_2.'元",
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
					                valueSuffix: "个",
					                formatter:  function() {  
					                    return "<p style=\'line-height:24px;\'>'.("{$year}年").'"+this.x+"月</p><p style=\'line-height:24px;\'>金额<strong class=\"txt-org\">"+this.y+"</strong> 人</p>";  
					                },
					                shadow:false,
					                shared: false,
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
					                 name: "总充值",
					                data: ['.implode(',',$data_month_1).'],
					                color:"#89a54f",
					            },
					            {
					                name: "消费",
					                data: ['.implode(',',$data_month_2).'],
					                color:"#aa4845",
					            }]
					        });';
				break;			
			default:
				break;
		}
		echo $ehtml;
	}
}
?>