<?php
class site extends tController{
	public $layout = "site";
	public function index(){
		$this->display();
	}
	public function about(){
		$catelist = C("cms")->get_onepage_cache("about");
		$return = array();
		foreach($catelist as $v){
			$v['content'] = htmlspecialchars_decode($v['content']);
			$return[] = $v;
		}
		tAjax::json($return);
	}
	public function helper_cate(){
		$res = C("category","cms_forums")->get_level(25,false);
		tAjax::json($res);
	}
	public function helper_detail(){

		$id = R("id","int");
		$keyword = R("keyword","string");
		$page = R("page","int");
		$page = $page?$page:1;

		$name = M("cms_forums")->get_one("id='{$id}'","name");
		if ($keyword) {
			$where = "subject like '%{$keyword}%'";
		}else{
			$where = "fid = '{$id}'";
		}
		$data['page']  = $page;
		$data['where'] = $where;
		$data['pagesize'] = 10;
		$data['order'] = "dateline DESC";
		$res = M("@cms_threads")->get_list($data);
		$res['next'] = $page+1;
		$res['previous'] = $page-1;

		$res['name'] = $name?$name:"";

		tAjax::json($res);
	}
	public function helper_topic(){

		$tid = R("tid","int");
		$cms_posts = M('cms_posts')->get_row("tid={$tid} AND first=1","message,subject");
		$res = array();
		if (isset($cms_posts['message'])) {
			$res['article_row'] = htmlspecialchars_decode($cms_posts['message']);
		}else{
			$res['article_row'] ='';
		}
		$res['article_row'] = htmlspecialchars_decode($res['article_row']);
		tAjax::json($res);
	}
}
?>