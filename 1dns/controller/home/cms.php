<?php
class cms extends tController{
	public $layout = "site2016";
    //帮助
    public function helper(){
    	$page    = R('page','int');
    	$pid     = 5;
    	$fid     = R("fid","int");
    	$keyword = R("keyword","string");
    	$cates = C("category","cms_forums")->get_level($pid);

    	$cate  = array();
    	if(!in_array($fid,array_keys($cates))){
    		$fid = 0;
    	}else {
    		$cate = $cates[$fid];
    	}
    		
    	$page = $page?$page:1;
    	$where = "status=0";
    	$where .= $fid?" AND fid=$fid":" AND fid IN('".implode("','",C("category","cms_forums")->get_child_ids($pid))."')";
    	$where .= $keyword?" AND (subject LIKE '%$keyword%' OR description LIKE '%$keyword%')":"";
    	$pageurl = tUrl::create("/cms/news/fid/$fid".($keyword?"?keyword=".urlencode($keyword):""));
    	
    	
    	$c = array(
    			"page" => $page,
    			"where" => $where,
    			"pagesize" => 30,
    			"order"    => "intop DESC,sort ASC,dateline DESC"
    	);
    	$data = Q("cms_threads")->get_list($c,$pageurl);
    	$this->assign("fid",$fid);
    	$this->assign("data",$data);
    	$this->assign("cates",$cates);
    	$this->assign("cate",$cate);
    	$this->assign("keyword",$keyword);
    	
    	//获取推荐的
    	$tuinews = M("cms_threads")->query("intui=0 AND status=0","*","intop DESC,sort ASC,dateline DESC",8);
    	$this->assign("tuinews",$tuinews);
    	$this->display();
    }
    //官方博客
	public function blog(){
		$page    = R('page','int');
		$pid     = 24;
		$fid     = R("fid","int");
		$keyword = R("keyword","string");
		$cates = C("category","cms_forums")->get_level($pid);

		$cate  = array();
		if(!in_array($fid,array_keys($cates))){
			$fid = 0;
		}else {
			$cate = $cates[$fid];
		}
			
		$page = $page?$page:1;
		$where = "status=0";
		if ($keyword) {
			$where .= $keyword?" AND (subject LIKE '%$keyword%' OR description LIKE '%$keyword%')":"";
		}else{
			$where .= $fid?" AND fid=$fid":" AND fid IN('".implode("','",C("category","cms_forums")->get_child_ids($pid))."')";
		}
		$pageurl = tUrl::create("/cms/blog/fid/$fid".($keyword?"?keyword=".urlencode($keyword):""));

		$c = array(
			"page" => $page,
			"where" => $where,
			"pagesize" => 30,
			"order"    => "intop DESC,sort ASC,dateline DESC"
		);
		$data = Q("cms_threads")->get_list($c,$pageurl);

		$this->assign("fid",$fid);
		$this->assign("data",$data);
		$this->assign("cates",$cates);
		$this->assign("cate",$cate);
		$this->assign("keyword",$keyword);
		
		//获取推荐的
		$tuinews = M("cms_threads")->query("intui=0 AND status=0","*","intop DESC,sort ASC,dateline DESC",8);
		$this->assign("tuinews",$tuinews);
		$this->display();
	}
    //博客详情
	public function topic(){
		global $uid;
		$pageurl   = U("/blog/topic?do=get");
		$page      = R("page","int");

		$pid     = 24;
		$cates = C("category","cms_forums")->get_level($pid);

		$user = M("user")->get_row("uid = 1");
		if (!isset($user['uname'])) {
			I("用户不存在",U("/cms/blog"),"error",3);
			$uname = $user['uname'];
		}else{
			$uname = '';
		}
		//获取帖子相关信息
		$tid = R("tid","int");
		$data = C("cms")->get_topic($tid,"",2,1);
		if(!isset($data['threads'])){
			I("您要找的新闻信息不存在!",U("/cms/blog"),"error",3);
		}
		$fid = $data['threads']['fid'];
		$cate_a= C("category","cms_forums")->get(0);
		if(isset($cate_a[$fid])){
			$cate = $cate_a[$fid];
		}else{
			$cate = current($cate_a);
		}
		$topcate = array();
		if(isset($cate['pid']) && $cate['pid']){
			$topcate = $cate_a[$cate['pid']];
		}
		$this->cate = $cate_a;
		$this->assign("fid",$fid);
		$this->assign("data",$data);
		$this->assign("cate",$cate);
		$this->assign("cates",$cates);
		$this->assign("topcate",$topcate);
		$this->assign("uname",$uname);
		$this->assign("prevnext",C("cms")->prevnext($data['threads']));
		
		//获取推荐的
		$tuinews = M("cms_threads")->query("intui=0 AND status=0","*","intop DESC,sort ASC,dateline DESC",8);
		$this->assign("tuinews",$tuinews);

		//博客回复内容
		$blog = M("cms_posts")->query("tid = '{$tid}' AND first=0","","dateline ASC",500);
		foreach ($blog as $k=>$v) {
			foreach ($v as $key=>$val) {
				if ($key=='dateline') {
					$blog[$k][$key] = $this->time_tran($val);
				}
			}
		}
		$this->assign("blog",$blog);
		$this->display();	
	}
	//博客回复
	public function blog_reply(){
		global $uid,$timestamp;

		$message = R("message","string");
		$pid = R("pid","int");
		$res = M("cms_posts")->get_row("pid = '{$pid}'");
		if (!isset($res['tid'])) {
			tAjax::json_error("文章不存在");
		}
		$ret = M("cms_threads")->get_row("tid = '{$res['tid']}'");
		if (!isset($ret['tid'])) {
			tAjax::json_error("文章不存在");
		}
		$userinfo = M("user")->get_row("uid = '{$uid}'");
		if (!isset($userinfo['email'])) {
			tAjax::json_error("用户不存在");
		}
		//更改回复数量值
		M("cms_threads")->set_data(array('replies'=>$ret['replies']+1))->update("tid='{$ret['tid']}'");
		//增加回复内容
		$data = array(
			'fid' 				=>$res['fid'],
			'tid' 				=>$res['tid'],
			'first'			=> 0,
			'uid' 			=>$userinfo['uid'],
			'uname' 		=> $userinfo['uname'],
			'dateline' 	=>$timestamp,
			'subject' 		=>$res['subject'],
			'message' 	=>$message,
			'userip' 		=>$res['userip'],
		);
		M("cms_posts")->set_data($data)->add();
		tAjax::json_success("ok");
	}
	//删除博客回复内容
	public function blog_del(){

		$pid = R("pid","int");
		$res  = M("cms_posts")->get_row("pid='{$pid}'");
		if (!isset($res['pid'])) {
			tAjax::json_error("内容不存在");
		}

		$ret = M("cms_threads")->get_row("tid = '{$res['tid']}'");
		if (!isset($ret['tid'])) {
			tAjax::json_error("文章不存在");
		}

		$rst   = M("cms_posts")->del("pid={$pid}");
		if ($rst) {
			//更改回复数量值
			M("cms_threads")->set_data(array('replies'=>$ret['replies']-1))->update("tid='{$ret['tid']}'");

			tAjax::json_success("ok");
		} else {
			tAjax::json_error("删除失败！");
		}
	}
    //单页
	public function onepage(){
		$i = $c = $t = "";
		$i = R("i","string");
		$c = R("c","string");

		$i = empty($i)?"about":$i;
		
		
		$catelist = C("cms")->get_onepage_cache($i);
		if(empty($c) || !isset($catelist[$c])){
			$curr_c = current($catelist);
			$c = $curr_c['ident'];
		}else{
			$curr_c = $catelist[$c];
		}
	
		$this->assign("catlist",$catelist);
		$this->assign("data",$curr_c);
		$this->assign("c",$c);
		$this->assign("i",$i);
		$this->display();
	}
	//友情链接
	public function links(){
		$i = $c = $t = "";
		$i = R("i","string");
		$c = R("c","string");

		$i = empty($i)?"about":$i;


		$catelist = C("cms")->get_onepage_cache($i);
		if(empty($c) || !isset($catelist[$c])){
			$curr_c = current($catelist);
			$c = $curr_c['ident'];
		}else{
			$curr_c = $catelist[$c];
		}

		$this->assign("catlist",$catelist);
		$this->assign("data",$curr_c);
		$this->assign("c",$c);
		$this->assign("i",$i);
		$this->display();
	}
	//新闻
	public function news(){
		$page    = R('page','int');
		$pid     = 23;
		$fid     = R("fid","int");
		$keyword = R("keyword","string");
		$cates = C("category","cms_forums")->get_level($pid);

		$cate  = array();
		if(!in_array($fid,array_keys($cates))){
			$fid = 0;
		}else {
			$cate = $cates[$fid];
		}

		$page = $page?$page:1;
		$where = "status=0";
		$where .= $fid?" AND fid=$fid":" AND fid IN('".implode("','",C("category","cms_forums")->get_child_ids($pid))."')";
		$where .= $keyword?" AND (subject LIKE '%$keyword%' OR description LIKE '%$keyword%')":"";
		$pageurl = tUrl::create("/cms/news/fid/$fid".($keyword?"?keyword=".urlencode($keyword):""));


		$c = array(
			"page" => $page,
			"where" => $where,
			"pagesize" => 30,
			"order"    => "intop DESC,sort ASC,dateline DESC"
		);
		$data = Q("cms_threads")->get_list($c,$pageurl);

		$this->assign("fid",$fid);
		$this->assign("data",$data);
		$this->assign("cates",$cates);
		$this->assign("cate",$cate);
		$this->assign("keyword",$keyword);

		//获取推荐的
		$tuinews = M("cms_threads")->query("intui=0 AND status=0","*","intop DESC,sort ASC,dateline DESC",8);
		$this->assign("tuinews",$tuinews);
		$this->display();
	}
	//新闻文章
	public function news_topic(){
		$pageurl   = U("/news/topic?do=get");
		$page      = R("page","int");

		$pid     = 23;
		$cates = C("category","cms_forums")->get_level($pid);

		//获取帖子相关信息
		$tid = R("tid","int");
		$data = C("cms")->get_topic($tid,"",2,1);
		if(!isset($data['threads'])){
			I("您要找的新闻信息不存在",U("cms/news"),"error",5);
		}
		$fid = $data['threads']['fid'];
		$cate_a= C("category","cms_forums")->get(0);
		if(isset($cate_a[$fid])){
			$cate = $cate_a[$fid];
		}else{
			$cate = current($cate_a);
		}
		$topcate = array();
		if(isset($cate['pid']) && $cate['pid']){
			$topcate = $cate_a[$cate['pid']];
		}
		$this->cate = $cate_a;
		$this->assign("fid",$fid);
		$this->assign("data",$data);
		$this->assign("cate",$cate);
		$this->assign("cates",$cates);
		$this->assign("topcate",$topcate);
		$this->assign("prevnext",C("cms")->prevnext($data['threads']));

		//获取推荐的
		$tuinews = M("cms_threads")->query("intui=0 AND status=0","*","intop DESC,sort ASC,dateline DESC",8);
		$this->assign("tuinews",$tuinews);
		$this->display();
	}
	//计算几天前方法
	function time_tran($the_time){
		global $timestamp;

		$dur = $timestamp - $the_time;
		if ($dur < 0) {
			return $the_time;
		} else {
			if ($dur < 60) {
				return $dur . '秒前';
			} else {
				if ($dur < 3600) {
					return floor($dur / 60) . '分钟前';
				} else {
					if ($dur < 86400) {
						return floor($dur / 3600) . '小时前';
					} else {
						return floor($dur / 86400) . '天前';
					}
				}
			}
		}
	}
}
?>