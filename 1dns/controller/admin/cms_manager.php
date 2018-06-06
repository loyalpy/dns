<?php
/**
 * CMS系统管理
 * by Thinkhu 2014 
 */
class cms_manager extends UCAdmin{
    public $layout="admin";
	/******************************** 文章 *********************************************/
	//帖子列表
	public function threads(){
	    global $timestamp;                                  //$timestamp    int(1412990623)
        //dump($timestamp);
		$do = R("do","string");
		$page = R("page","int");                //$page
	    $page = $page?$page:1;	
		$pageurl= U("/cms_manager/threads?do=get");   //dump($pageurl);       string(27) "/cms_manager/threads?do=get"
		$condi = array(
			"keyword" => R("keyword","string"),
            "fid"     => R("fid","int")
		);
		$catlist = C('category',"cms_forums")->get(0,0);
		$where = "1";
 		foreach($condi as $k=>$v){
			$v = trim($v);
			$pageurl .= "&{$k}=".$v;
			switch($k){
			    case "keyword":
                    $where .= $v?" AND (subject LIKE '%{$v}%')":"";
                    break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}        
		if($do == "get"){
            //获取cms_threads 表所有数据   返回一个3维数组    list[0,1,2,3,4]下的2维数组
            $result = Q("cms_threads")->get_list(array("page"=>$page,"where"=>$where,"order"=>"intop DESC,sort ASC","pagesize"=>15),$pageurl);     
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");  //替换分页JS
            foreach($result['list'] as $k=>$v){
                //如果$catlist[$v['fid']]存在 输出$catlist[$v['fid']]['name']否则输出-      循环追加
				$result['list'][$k]['catname'] = isset($catlist[$v['fid']])?$catlist[$v['fid']]['name']:" - ";
 			}
			tAjax::json($result);
		}elseif($do == "get_url"){
			$result = $condi;    
			$result['pageurl'] = $pageurl;
			$result['error']   = 0;
			$result['message'] = "处理成功！";
			tAjax::json($result);
		}else{
            //print_r($condi);exit;
            $this->assign("pageurl",$pageurl);//分配数据到视图
            $this->assign("condi",$condi);//分配数据到视图
            $this->assign("catlist",C("category","cms_forums")->json_tpl());
            $this->display();
        }        
	}
	//帖子分类管理
	public function threads_forums(){
        	$do = R("do");
			$cls_cate = new cls_category("cms_forums");
            //print_r($cls_cate);exit;

            
			switch($do){
				case "refresh":
					$cls_cate->clear();
					tAjax::json_success("刷新成功！");
					break;
				case "del":
					$id = R("id","int");
					if(M("cms_forums")->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
						tAjax::json_error("该模块下有子模块！不能删除");
					}
					if(M("cms_forums")->del("id='{$id}'")){
						$cls_cate->clear();
						tAjax::json_success("删除成功！");
					}else{
						tAjax::json_error("删除失败！");
					}
					break;
				case "copy":
					$id = R("id","int");
					if($id){
						$ret = M("cms_forums")->get_row("id='{$id}'");
						if(isset($ret['id'])){
							unset($ret['id']);
							M("cms_forums")->set_data($ret)->add();
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
							"description" => R("description","string"),
							"sort"        => R("sort","int"),
							"banzhu"      => R("banzhu","string"),
						);						
						if(empty($data['name'])){//！
							tAjax::json_error("分类名不能为空！");
						}					
						if($id == 0){
							M("cms_forums")->set_data($data);
							$ret = $id = M("cms_forums")->add();
						}else{
							if($data['pid'] == $id){
								tAjax::json_error("上级不能为自己！");
							}
							M("cms_forums")->set_data($data);
							$ret = M("cms_forums")->update("id=$id");
						}
						$cls_cate->clear();
			    		tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));			    	
					}else{
						$ret = array();
						if($id){
							$ret = M("cms_forums")->get_row("id='{$id}'");
						}
						if(!isset($ret['id'])){
							$ret = array("name"=>"","id"=>0,"status"=>1,"isopen"=>0,'pid'=>'');
						}
						$ret['status'] = array($ret['status']);
						
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
           

		
	}
	//帖子编辑
	public function threads_edit(){
		global $uid;
        $tid = R("tid","int");
		$pid = R("pid","int");
        $do  = R("do","string");
        //更改threads字段 记录文章数
		if($do == "execount"){
			$fids = R("fids","string");
			$sql = "update `@cms_forums` as a set threads=(select count(*) from `@cms_threads` as b  where b.fid=a.id) ".(!empty($fids)?"where id IN({$fids})":"");
			Sq($sql);
            C("category","cms_forums")->clear();
			exit("");
		}
		if(tUtil::check_hash()){//发帖提交  表单提交检查
             $data = array(
                 "fid"             => R("fid","int"),
                 "subject"         => R("subject","string"),
                 "description"     => R("description","string"),
                 "message"         => R("message","string"),
                 "seo_keyword"     => R("seo_keyword","string"),
                 "soure"           => R("soure","string"),
                 "seo_title"       => R("seo_title","string"),
                 "seo_description" => R("seo_description","string"),
             		
             	 "sort"            => R("sort","int"),
             		
                 "status"  => R("status","int"),
                 "intui"   => R("intui","int"),
                 "intop"   => R("intop","int"),
                 "inhot"   => R("inhot","int"),
                 
                 "fm_id"   => R("fm_id","int"),
                 "fm"      => R("fm","string")
             );
             
             //获取附件
             $attchlist = R("attach_ids","string");
             
             //分类检测
             if(empty($data['fid'])){
             	tAjax::json_error("分类不能为空！");
             }
             //标题检测
             if(empty($data['subject'])){
             	tAjax::json_error("标题不能为空！");
             }
             $old_fid = R("old_fid","int");
             //保存数据
             $ret = C("cms")->save($tid,$pid,$data,$attchlist);
             if($ret){
                 tAjax::json(array("message"=>"操作成功","error"=>0,"callback"=>U("/cms_manager/threads"),"js"=>U("/cms_manager/threads_edit?do=execount&fids={$data['fid']},{$old_fid}")));
             }else{
                 tAjax::json_error("错误了，你乱提交啊！");
             }

        }else{
            $data = array();
			if($tid >0){
				$data = M("cms_threads")->get_row("tid=$tid");
			}
			if(!isset($data['tid'])){
				$data = array(
				   'fid'=>$this->fid,
				   'tid'=>0,
				   'pid'=>0,
				   'subject'=>'',
				   'message'=>'',
				   'uid'=>$uid,
				   "fm"=>'',
				   "fm_id"=>0
				);
			}
			$post = M('cms_posts')->get_row("tid={$data['tid']} AND first=1","pid,message");
			if(!isset($post['pid'])){
				$post =array(
					 'pid'=>0,
					 'message'=>''
				);
			}
			$this->assign("post",$post);
			$this->assign("data",$data);
			$this->assign("catlist",C("category","cms_forums")->json_tpl());
        	$this->display();
        }
	}
	//帖子删除
	public function threads_del(){
        $id = R("tid","int");
		$ret = 0;
		if($id >0){
			$ret = M("cms_threads")->del("tid='{$id}'");
		}
		if($ret>0){
			tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>"reload"));
		}else{
			tAjax::json_error("删除失败！");
		}
	
	}
	//帖子回复管理
	public function posts(){
		
	}
	//帖子回复删除
	public function posts_del(){
		
	}
	/******************************** 单页 *********************************************/
	//单页管理
	public function onepage(){
        global $timestamp;
		$do = R("do","string");
		$page = R("page","int");
	    $page = $page?$page:1;	
		$pageurl = U("/cms_manager/onepage?do=get");
		$condi = array(
			"keyword" => R("keyword","string"),
            "cat_id"  => R("cat_id","string")
		);
		
		$where = "1";
        $catlist = App::get_conf('data_config.onepage');
        $catlist = is_array($catlist)?$catlist:array();
		foreach($condi as $k=>$v){
			$v = trim($v);
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
		if($do == "get"){
			$c = array();
			$c['page']  = $page;
			$c['where'] = $where;
			$c['order'] = "sort ASC";		
            $c['pagesize'] = 20;	
            $result = Q("cms_onepage")->get_list($c,$pageurl);
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
            $this->assign('catlist',$catlist);
		$this->display();
	}
	//单页分类编辑
	public function onepage_cate(){
		$do = R("do");
		$item = "onepage";
		$str = M('site_config')->get_one("name='dataconfig_{$item}'","value");
		
		switch($do){
			case "del":
				$id = R("id","string");
				$str = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $str?tUtil::unserialize($str):array();
				if(isset($datalist[$id])){
					unset($datalist[$id]);
					M('site_config')->set_data(array("value"=>serialize($datalist)));
			    	M('site_config')->update("name='dataconfig_{$item}'");
				}
				tAjax::json_success("删除成功！");
				break;
			case "edit":
				$id = R("id","string");
				$str = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $str?tUtil::unserialize($str):array();
				if(tUtil::check_hash()){
					$data = array(
						"name"       => R("name","string"),
						"code"       => R("code","string"),
					);
			    	if(empty($id) && isset($datalist[$data['code']])){
			    		tAjax::json_error("该类型编号已经存在");
			    	}else{
			    		if(isset($datalist[$data['code']]) && $data['code'] != $id){
			    			tAjax::json_error("该类型编号已经存在,会造成数据覆盖");
			    		}
			    		unset($datalist[$id]);
			    		$datalist[$data['code']] = $data;
			    	}
			    	ksort($datalist);
			    	reset($datalist);
			    	if(empty($str)){
			    		M('site_config')->set_data(array("name"=>"dataconfig_{$item}","value"=>serialize($datalist)));
			    		M('site_config')->add();
			    	}else{
			    		M('site_config')->set_data(array("value"=>serialize($datalist)));
			    		M('site_config')->update("name='dataconfig_{$item}'");
			    	}
		    		tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));			    	
				}else{
					$ret = array("name"=>"","code"=>"","isp"=>0,"address"=>"","id"=>0,"area"=>"33010000","dbtablepre"=>"t_","dbtype"=>"mysql");
					if(isset($datalist[$id])){
						$ret = $datalist[$id];
						$ret['id'] = $ret['code'];
					}
					tAjax::json($ret);
				}
				break;
			case "get":
				$datalist = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $datalist?tUtil::unserialize($datalist):array();
				if($datalist){
					
				}
				tAjax::json(array("list"=>$datalist));
				break;
			default:
				$this->display();
				break;
		}
	}
	//单页编辑
	public function onepage_edit(){
		global $timestamp;
        $id = R("id","int",0);
		if(tUtil::check_hash()){//发帖提交
             $data = array(
                 "cat_id"         => R("cat_id","string"),
                 "name"           => R("name","string"),
                 "ident"          => R("ident","string"),
                 "sort"           => R("sort","int"),
                 "seo_title"      => R("seo_title","string"),//
                 "seo_keyword"    => R("seo_keyword","string"),
                 "seo_description"=> R("seo_description","string"),
                 "description"    => R("description","string"),
                 "content"        => R("content","string"),
             	 "hit"            => R("hit","int"),
             );
             
             if(empty($data['name'])){
             	tAjax::json_error("单页标题不能为空！");
             	
             }
             if(empty($data['ident'])){
             	tAjax::json_error("单页标识不能为空！");
             }
             if(M("cms_onepage")->get_one("id<>$id AND ident='{$data['ident']}' AND cat_id='{$data['cat_id']}'","count(*)")>0){
             	tAjax::json_error("本分类标识必须唯一！");
             }
             if($id == 0){
             	 $data["dateline"] = $timestamp;
                 $ret = M("cms_onepage")->set_data($data)->add();
             }else{
                 $ret = M("cms_onepage")->set_data($data)->update("id='{$id}'");
             }
             if($ret){
             	 tCache::del("onepage_{$data['cat_id']}");
                 //tAjax::json_success("操作成功");
                 tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>U("/cms_manager/onepage?cat_id={$data['cat_id']}")));		
             }else{
                 tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>U("/cms_manager/onepage?cat_id={$data['cat_id']}")));
             }

        }else{
            $data = array();
            if($id >0 ){
                $data = M("cms_onepage")->get_row("id='{$id}'");
            }
            if(empty($data)){
                $data = array(
                 "sort"        => 0,
                 "id"          => 0,
                 "name"        =>'',
                 "description" =>'',
                 "cat_id"      => R("cat_id","int"),
                );
            }
            $this->assign("data",$data);
            $this->display();
        }
	}
	//单页删除
	public function onepage_del(){
		$id = R("id","int");
		$ret = 0;
		if($id >0){
			$data = M("cms_onepage")->get_row("id='{$id}'");
			if(isset($data['id'])){
				$ret = M("cms_onepage")->del("id='{$id}'");
			}
		}
		if($ret>0){
			tCache::del("onepage_{$data['cat_id']}");
			tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>"reload"));
		}else{
			tAjax::json_error("删除失败！");
		}
	}
	
	/******************************** 广告管理 *********************************************/
	//广告管理
	public function adlist(){
        global $timestamp;
		$do = R("do","string");
		$page = R("page","int");
	    $page = $page?$page:1;	
		$pageurl = U("/cms_manager/adlist?do=get");
		$condi = array(
			"keyword" => R("keyword","string"),
            "cat_id"  => R("cat_id","int")
		);
		
		$where = "1";
        $catlist =App::get_data('data_config.adlist_cate');
        $catlist = $catlist?$catlist:array();
		foreach($condi as $k=>$v){
			$v = trim($v);
			$pageurl .= "&{$k}=".$v;
			switch($k){
			    case "keyword":
                    $where .= $v?" AND (name LIKE '%{$v}%' or content LIKE '%{$v}%')":"";
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
			$c['order'] = "sort ASC";		
            
            $result = Q("ad")->get_list($c,$pageurl);
			//$result = C("cms")->get_onepagelist($c,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"loadlist");
            //print_r($result);exit;
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
		$this->assign("catlist",$catlist);
		$this->display();
		
      
	}
	//广告分类编辑
	public function adlist_cate(){
        $do = R("do");
		$item = "adlist_cate";
		switch($do){
			case "refresh":
		
				$id = R("id","string");
				C("cms")->write_static_cache("ad","{$id}");
				tAjax::json(array("message"=>"刷新缓存成功！","error"=>0));                 
				break;
			case "del":
				$id = R("id","string");
				$str = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $str?tUtil::unserialize($str):array();
				if(isset($datalist[$id])){
					unset($datalist[$id]);
					M('site_config')->set_data(array("value"=>serialize($datalist)));
			    	M('site_config')->update("name='dataconfig_{$item}'");
				}
				tAjax::json_success("删除成功！");
				break;
			case "edit":
				$id = R("id","string");
				$str = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $str?tUtil::unserialize($str):array();
				if(tUtil::check_hash()){
					$data = array(
                        
						"name"       => R("name","string"),
						"code"       => R("code","string"),
					);
			    	if(empty($id) && isset($datalist[$data['code']])){
			    		tAjax::json_error("该类型编号已经存在");
			    	}else{
			    		if(isset($datalist[$data['code']]) && $data['code'] != $id){
			    			tAjax::json_error("该类型编号已经存在,会造成数据覆盖");
			    		}
			    		unset($datalist[$id]);
			    		$datalist[$data['code']] = $data;
			    	}
			    	ksort($datalist);
			    	reset($datalist);
			    	if(empty($str)){
			    		M('site_config')->set_data(array("name"=>"dataconfig_{$item}","value"=>serialize($datalist)));
			    		M('site_config')->add();
			    	}else{
			    		M('site_config')->set_data(array("value"=>serialize($datalist)));
			    		M('site_config')->update("name='dataconfig_{$item}'");
			    	}
		    		tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));			    	
				}else{
					$ret = array("name"=>"","code"=>"","isp"=>0,"address"=>"","id"=>0,"area"=>"33010000","dbtablepre"=>"t_","dbtype"=>"mysql");
					if(isset($datalist[$id])){
						$ret = $datalist[$id];
						$ret['id'] = $ret['code'];
					}
					tAjax::json($ret);
				}
				break;
			case "get":
				$datalist = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $datalist?tUtil::unserialize($datalist):array();
				
				tAjax::json(array("list"=>$datalist));
				break;
			default:

				$this->display();
				break;
		}              
        
    }
	//广告编辑
	public function adlist_edit(){
		global $timestamp;
        $id = R("id","int");
		if(tUtil::check_hash()){//发帖提交
            $data = R("data","string");
			$id = R("id","int");
			$data['start_dateline'] = tTime::get_time($data['start_dateline']);
			$data['end_dateline'] = tTime::get_time($data['end_dateline']);
			$data['status'] = $data['status']?1:0;
			$data['uid'] = isset($data['uid'])?intval($data['uid']):0;
			
		
			/* 表单验证 */
			if(empty($data['cat_id'])){
				tAjax::json_error("广告分类必须选择！");
			}
			//上传附件
			$attach_name = "upimgs";
			$attach_thumb_name = "upimgs_thumb";
			$error_message = "";
			
			if($data['type'] != 'txt' && empty($_FILES[$attach_name]) === false){
				//$data['thumburl'] = $data['imgurl'] = "";
				$up_obj = new tUpload(2048,array("jpg","gif","png","bmp","jpeg"));
				$file_date_path = tTime::get_datetime("Y/m/d");
				$file_path = "attach/ad/".$file_date_path."/";
				$file_store_path = UPLOAD_PATH.$file_path;
				
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
							tImage::fixthumb($data['imgurl'],$thumb_w,$thumb_h,"_s");
							$data['thumburl'] = $file_path.$upstate[$attach_name][0]['name']."_s.".$upstate[$attach_name][0]['ext'];
						}
					}
				}
				if(isset($upstate[$attach_thumb_name])){
					if($upstate[$attach_thumb_name][0]['flag']==-1){
						$error_message = '上传的小图文件类型不符合';
					}else if($upstate[$attach_thumb_name][0]['flag']==-2){
						$error_message = '小图大小超过限度';
					}else if($upstate[$attach_thumb_name][0]['flag']==1){
						$data['thumburl'] = $file_path.$upstate[$attach_thumb_name][0]['name'].".".$upstate[$attach_thumb_name][0]['ext'];
					}
				}
			}

			M("ad")->set_data($data);
			if($id>0){//
				$old_data = M("ad")->get_row("id='{$id}'");
				if(isset($data['imgurl'])){
					$old_data['imgurl'] && tFile::unlink(UPLOAD_PATH .$old_data['imgurl']);
					$old_data['thumburl'] && tFile::unlink(UPLOAD_PATH .$old_data['thumburl']);
				}
				M("ad")->update("id='{$id}'");
				
			}else{
				M("ad")->add();
			}
			//生成缓存
			C("cms")->write_static_cache("ad","{$data['cat_id']}".(isset($old_data['cat_id'])?",{$old_data['cat_id']}":""));
			tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>U("/cms_manager/adlist?cat_id={$data['cat_id']}")));
        }else{
            $data = array();
            if($id >0 ){
                $data = M("ad")->get_row("id='{$id}'");
            }
            if(!isset($data['id'])){
            	$data = array(
            		'type'             => 'img',
					'start_dateline'   => $timestamp,
					'end_dateline'     => $timestamp + 360*86400,
					'status'           => 1,
            			'cat_id'           => R("cat_id","int"),
            			"uid"=>0,
            			"sort"=>0,
            			"width"=>0,
            			"height"=>0,
   				);
            }
            $this->assign("data",$data);
            //获取分类
            $catlist = App::get_data("data_config.adlist_cate");
            $catlist = $catlist?$catlist:array();
	        $this->assign("catlist",$catlist);
	        $this->display();
        }
	}
	//广告删除
	public function adlist_del(){
		$id = R("id","int");
		$old_data = M("ad")->get_row("id='{$id}'");
		if(M("ad")->del("id='{$id}'")){
	    	(isset($old_data['id']) && $old_data['imgurl']) && tFile::unlink(UPLOAD_PATH. $old_data['imgurl']);
	    	(isset($old_data['id']) && $old_data['thumburl']) && tFile::unlink(UPLOAD_PATH. $old_data['thumburl']);
		}
		/* 重新生成缓存 */
		C("cms")->write_static_cache("ad","{$old_data['cat_id']}");
	    tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>U("/cms_manager/adlist")));
	}
	/******************************** 友情链接 *********************************************/
	//friendlink管理
	public function friendlink(){
	    global $timestamp;
		$do = R("do","string");
		$page = R("page","int");
	    $page = $page?$page:1;	
		$pageurl = U("/cms_manager/friendlink?do=get");
		$condi = array(
			"keyword" => R("keyword","string"),
            "cat_id"  => R("cat_id","string")
		);
		$where = "1";
        $catlist =App::get_data('data_config.friendlink');
        $catlist = is_array($catlist)?$catlist:array();
		foreach($condi as $k=>$v){
			$v = trim($v);
			$pageurl .= "&{$k}=".$v;
			switch($k){
			    case "keyword":
                    $where .= $v?" AND (name LIKE '%{$v}%' or link LIKE '%{$v}%')":"";
                    break;
				default:
					$where .= $v?" AND {$k}='{$v}'":"";
					break;
			}
		}


		if($do == "get"){
            $query = new tQuery("cms_friendlink");
            $query->page = $page;
            $query->where = $where;
            $query->order = "sort ASC";
            $query->pagesize = 30;
            $result = array();
            $result['list'] = $query->find();
            $result['pagebar'] = $query->get_pagebar($pageurl);
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
		$this->assign("catlist",$catlist);
		$this->display();
	}
	//friendlink分类编辑
	public function friendlink_cate(){
		$do = R("do");
		$item = "friendlink";
		switch($do){
			case "refresh":
				$id = R("id","string");
				C("cms")->write_static_cache("cms_friendlink","{$id}");
                 tAjax::json(array("message"=>"刷新成功","error"=>0));                 
				break;
			case "del":
				$id = R("id","string");
				$str = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $str?tUtil::unserialize($str):array();
				if(isset($datalist[$id])){
					unset($datalist[$id]);
					M('site_config')->set_data(array("value"=>serialize($datalist)));
			    	M('site_config')->update("name='dataconfig_{$item}'");
				}
				tAjax::json_success("删除成功！");
				break;
			case "edit":
				$id = R("id","string");
				$str = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $str?tUtil::unserialize($str):array();
				if(tUtil::check_hash()){
					$data = array(
						"name"       => R("name","string"),
						"code"       => R("code","string"),
					);
			    	if(empty($id) && isset($datalist[$data['code']])){
			    		tAjax::json_error("该类型编号已经存在");
			    	}else{
			    		if(isset($datalist[$data['code']]) && $data['code'] != $id){
			    			tAjax::json_error("该类型编号已经存在,会造成数据覆盖");
			    		}
			    		unset($datalist[$id]);
			    		$datalist[$data['code']] = $data;
			    	}
			    	ksort($datalist);
			    	reset($datalist);
			    	if(empty($str)){
			    		M('site_config')->set_data(array("name"=>"dataconfig_{$item}","value"=>serialize($datalist)));
			    		M('site_config')->add();
			    	}else{
			    		M('site_config')->set_data(array("value"=>serialize($datalist)));
			    		M('site_config')->update("name='dataconfig_{$item}'");
			    	}
		    		tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));			    	
				}else{
					$ret = array("name"=>"","code"=>"","isp"=>0,"address"=>"","id"=>0,"area"=>"33010000","dbtablepre"=>"t_","dbtype"=>"mysql");
					if(isset($datalist[$id])){
						$ret = $datalist[$id];
						$ret['id'] = $ret['code'];
					}
					tAjax::json($ret);
				}
				break;
			case "get":
				$datalist = M('site_config')->get_one("name='dataconfig_{$item}'","value");
				$datalist = $datalist?tUtil::unserialize($datalist):array();
				tAjax::json(array("list"=>$datalist));
				break;
			default:
				$this->display();
				break;
		}
	}
	//friendlink编辑
	public function friendlink_edit(){
		$id = R("id","int",0);
		if(tUtil::check_hash()){//发帖提交
             $data = array(
                 "cat_id"=> R("cat_id","string"),
                 "name"=> R("name","string"),
                 "link"=> R("link","string"),
             	 "sort"=> R("sort","int")
             );
             
             //上传附件
			$attach_name ='logo';
			$error_message = "";
			//$data['thumburl'] = $data['imgurl'] = "";
			$up_obj = new tUpload(2048,array("jpg","gif","png","bmp","jpeg"));
			$file_date_path = tTime::get_datetime("Y/m/d");
			$file_path = "attach/linklogo/".$file_date_path."/";
			$file_store_path = UPLOAD_PATH.$file_path;
			
			
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
			
             if($id == 0){
                 $ret = M("cms_friendlink")->set_data($data)->add();
             }else{
             	$old_data = M("cms_friendlink")->get_row("id='{$id}'");
				if(isset($data['logo'])){
					$old_data['logo'] && tFile::unlink(UPLOAD_PATH .$old_data['logo']);
				}
                 $ret = M("cms_friendlink")->set_data($data)->update("id='{$id}'");
             }
             //上传附件结束
             
             if($ret){
             	 C("cms")->write_static_cache("cms_friendlink","{$data['cat_id']}".($id>0?",{$old_data['cat_id']}":""));
                 tAjax::json(array("message"=>"操作成功1","error"=>0,"callback"=>"close"));
             }else{
                 tAjax::json_error("错误了，你乱提交啊！");
             }

        }else{
            $data = array();
            if($id >0 ){
                $data = M("cms_friendlink")->get_row("id='{$id}'");
            }
            if(empty($data)){
                $data = array(
                	'id'=>0,
                	'name'=>'',
                		'sort'=>0,
                		"link"=>''
                );
            }
            tAjax::json($data);
        }
	}
	//friendlink删除
	public function friendlink_del(){
		$id = R("id","int");
		$cat_id = R("cat_id","int");
		$ret = 0;
		if($id >0){
			C("cms")->write_static_cache("cms_friendlink","{$cat_id}");
			$ret = M("cms_friendlink")->del("id='{$id}'");
		}
		if($ret>0){
			tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>"reload"));
		}else{
			tAjax::json_error("删除失败！");
		}
	}
}
?>