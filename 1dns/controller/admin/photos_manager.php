<?php
/**
 * 产品管理
 * by Thinkhu 2014 
 */
class photos_manager extends UCAdmin{
	//订单产品列表
	public function photoslist(){
		global $timestamp;
		$do = R("do","string");
		$catlist = C('category',"photos_cate")->get(0,0);
		$page = R("page","int");
	    $page = $page?$page:1;	
		$pageurl = U("/photos_manager/photoslist?do=get");
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
					$where .= $v?" AND (name LIKE '%{$v}%' OR bz LIKE '%{$v}%')":"";
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
			$c['pagesize'] = 30;		
			$result = C("photos")->get_list($c,$pageurl);
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
		$this->assign("catlist",C("category","photos_cate")->json_tpl());
		$this->display();
	}
	//图片编辑修改
	public function photoslist_edit(){
		global $uid,$timestamp,$realip;
		$id  = R("id","int");
		$fid = R("fid","int");
		$old_fid   = R("old_fid","int");
		$do = R("do","string");
		if($do == "execount"){
			$fids = R("fids","string");
			$sql = "update `@photos_cate` as a set goods=(select count(id) from `@photos` as b  where b.fid=a.id) ".(!empty($fids)?"where id IN({$fids})":"");
			Sq($sql);
			exit("");
		}
		if(tUtil::check_hash()){//发帖提交
			$data = R("data","string");//获取POST过来的数据
			$attachlist = R("attach_ids");//获取附件
			$data['fm_id'] = R("fm_id","int");
			$data['fm']    = R("fm","string");
			$data['fid']   = $fid;
			
			/* 表单验证 */
			if(!isset($data['name']) || empty($data['name'])){
				tAjax::json_error("名称必须填写!");
			}
			if($id == 0){
			}
			if(!isset($data['fid']) || empty($data['fid'])){
				tAjax::json_error("分类必须选择!");
			}
			
			$cate_row = C("photos")->get_cate($fid);
			if(!isset($cate_row['id']) || $cate_row['status'] != 1){
				tAjax::json_error("分类不可用!");
			}
			$ret = C("photos")->save($id,$data,$attachlist);
			if($ret >0 ){
				tAjax::json(array("error"=>0,"message"=>"发布成功！","callback"=>U("/photos_manager/photoslist?fid={$data['fid']}"),"fid"=>$fid,"js"=>U("/photos_manager/photoslist_edit?do=execount&fids={$fid},{$old_fid}")));
			}else{
				tAjax::json_error("发布失败！");
			}
		}else{
			$data = array();
			if($id >0){
				$data = C("photos")->get_row("id=$id");
			}
			if(!isset($data['id'])){
				$data = array(
				   'fid'      => $fid,
				   'id'       => 0,
				   'name'     => '',
				   'bz'  => '',
				   "fm_id"    => 0,
				   "fm"       => "",
				);
			}
			$this->assign("data",$data);
			$this->display();
		}
	}
	//图片删除
	public function photoslist_del(){
		$id = R("id","int");
		$ret = 0;
		if($id >0){
			$row = C("photos")->get_row("id=$id");
			if(isset($row['id'])){				
				$ret = C("photos")->del($id);
			}
		}
		if($ret>0){
			tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>"reload","js"=>U("/photos_manager/photoslist_edit?do=execount&fids={$row['fid']}")));
		}else{
			tAjax::json_error("删除失败！");
		}
	}
	//图片分类
	public function photoslist_cate(){
		global $uid;
		//管理员登录检查
		$do = R("do","string");
		$cls_cate = new cls_category("photos_cate");
		switch($do){
			case "refresh":
				$cls_cate->clear();
				tAjax::json_success("刷新成功！");
				break;
			case "del":
				$id = R("id","int");
				if(M("photos")->get_one("fid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
					tAjax::json_error("该分类下存在图片！不能删除");
				}
				if(M("photos_cate")->get_one("pid='{$id}'","count(*)")>0){//如果存在服务器设备删除失败！
					tAjax::json_error("该分类下有子分类！不能删除");
				}
				if(M("photos_cate")->del("id='{$id}'")){
					$cls_cate->clear();
					tAjax::json_success("删除成功！");
				}else{
					tAjax::json_error("删除失败！");
				}
				break;
			case "copy":
				$id = R("id","int");
				if($id){
					$ret = M("photos_cate")->get_row("id='{$id}'");
					if(isset($ret['id'])){
						unset($ret['id']);
						M("photos_cate")->set_data($ret)->add();
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
					if($id == 0){
						M("photos_cate")->set_data($data);
						$ret = $id = M("photos_cate")->add();
					}else{
						if($data['pid'] == $id){
							tAjax::json_error("上级不能为自己！");
						}
						M("photos_cate")->set_data($data);
						$ret = M("photos_cate")->update("id=$id");
					}
					$cls_cate->clear();
		    		tAjax::json(array("error"=>0,"message"=>"操作成功！","callback"=>"close"));			    	
				}else{
					$ret = array();
					if($id){
						$ret = M("photos_cate")->get_row("id='{$id}'");
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
				$this->assign("catlist",$cls_cate->json_tpl());
				$this->display();
				break;
		}
	}
	//所有图片附件列表
	public function photoslist_attach(){
		global $timestamp;
		$do      = R("do","string");
		$catlist = C('category',"photos_cate")->get(0,0);
		$page    = R("page","int");
		$page    = $page?$page:1;
		$pageurl = U("/photos_manager/photoslist_attach?do=get");
		$condi = array(
				"keyword"   => R("keyword","string"),
				"fid"       => R("fid","int")
		);
		$where = "1";
		foreach($condi as $k=>$v){
			$v = trim($v);
			$pageurl .= "&{$k}=".$v;
			switch($k){
				case "fid":
					$where .= $v?" AND b.fid='{$v}'":"";
					break;
				case "keyword":
					$where .= $v?" AND (b.name LIKE '%{$v}%' OR b.bz LIKE '%{$v}%' OR a.description LIKE '%{$v}%')":"";
					break;
				default:
					$where .= $v?" AND a.{$k}='{$v}'":"";
					break;
			}
		}
		if($do == "get"){
			$c = array();
			$c['table']  = "photos_attach AS a";
			$c['page']  = $page;
			$c['where'] = $where;
			$c['order'] = "a.description ASC,a.dateline ASC";
			$c['pagesize'] = 40;
			$c['join'] = " LEFT JOIN photos AS b ON a.photos_id = b.id";
			$c['fields'] = "a.*,b.fid,b.name,b.bz";
			$result = C("photos")->get_attachlist($c,$pageurl);
			$result['all_imgpath'] = "";
			foreach($result['list'] as $k=>$v){
				$result['list'][$k]['catname'] = isset($catlist[$v['fid']])?$catlist[$v['fid']]['name']:" - ";
				$result['all_imgpath'] .= "<img src='http://".tServer::get_host().U("/attach/photos/")."{$v['path']}/{$v['filename']}.{$v['ext']}' /><br/>";
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
		$this->assign("catlist",C("category","photos_cate")->json_tpl());
		$this->display();
	}
}
?>