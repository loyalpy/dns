<?php
class site extends tController{
	public $layout = "site2016";
	public function index(){
		global $uid;
		$this->setTgCookie();
		//内容分类
		$cls_cate = new cls_category("cms_forums");
		$return = $cls_cate->get(0,0);
		//内容列表
		$cms_list = tCache::read("cms_threads");
		if (!$cms_list) {
			$cms_list = M("@cms_threads")->get_list(array('order'=>"dateline DESC"));
			tCache::write("cms_threads",$cms_list);
		}
		$this->assign("cms",$return);
		$this->assign("cms_list",$cms_list);
		$this->display();
	}
	//设置推广cookie
	public function setTgCookie(){
		$tg_url = R("_u","string");
		if ($tg_url) {
			$uid = tUtil::numstr($tg_url,true,8);
			if (M("user")->get_one("uid = {$uid}","uid")) {
				tCookie::set("bjTg",$tg_url,2*86400);
			}
		}
	} 
	//测试专用，此方法可随意处理
	public function test()
	{
	}
}

?>