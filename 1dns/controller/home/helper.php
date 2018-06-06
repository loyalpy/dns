<?php
class helper extends tController{
	public $layout = "site2016";
	public function index(){

		//内容分类
		$pid = 25;
		$res_level = C("category","cms_forums")->fetch($pid);
		sort($res_level);
		//文章标题
		$show_topic = array_keys(C("category","cms_forums")->get($pid));
		$res_title = M("@cms_threads")->get_list(array('where' => "fid IN('".implode("','",$show_topic)."')"));

		$this->assign("res_level",$res_level);
		$this->assign("res_title",$res_title['list']);
		$this->display();
	}
	public function cat(){

		$fid = R("fid","int");
		$page 		 = R("page","int");
		$keyword  = R("keyword","string");

		$page 		 = $page?$page:1;
		$pageurl    = U("/helper/cat?keyword={$keyword}");

		$where = "1";
		if ($keyword) {
			$where .= " AND (subject LIKE '%$keyword%')";
		}else{
			if ($fid) {
				$where .= " AND fid = '{$fid}'";
			}
		}

		//内容分类
		$res_level = C("category","cms_forums")->get_level(25);
		//标题列表
		$data = array(
			'where' => $where,
			'order'  => "tid ASC",
			'pagesize' => 20,
			'page'		 =>$page
		);
		$t_list = M("@cms_threads")->get_list($data,$pageurl);

		$this->assign("fid",$fid);
		$this->assign("pid",M("cms_forums")->get_one("id = '{$fid}'","pid"));
		$this->assign("t_list",$t_list);
		$this->assign("res_level",$res_level);
		$this->assign("keyword",$keyword);
		$this->display();
	}
	public function article(){

		$tid = R("tid","int");

		//内容分类
		$res_level = C("category","cms_forums")->get_level(25);

		$fid = M("cms_threads")->get_one("tid = '{$tid}'","fid");
		$pid = M("cms_forums")->get_one("id = '{$fid}'","pid");

		//标题列表
		$topic = M("cms_posts")->get_row("tid = '{$tid}'");
		if (isset($topic['message'])) {
			$topic['message'] = htmlspecialchars_decode($topic['message']);
		}else{
			I("文章不存在",U("/helper/index"),"error",3);
		}

		$this->assign("fid",$fid);
		$this->assign("pid",$pid);
		$this->assign("topic",$topic);
		$this->assign("res_level",$res_level);
		$this->display();
	}
}
?>