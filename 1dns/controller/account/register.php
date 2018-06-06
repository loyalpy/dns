<?php
/**
 * 注册管理
 * by Thinkhu 2014 
 */
class register extends tController{
	public $layout = "reglogin";
	function init(){
		global $uid;
		if($uid){
			$userinfo = C("user")->get_cache_userinfo($uid);
			$in_ajax  = R("in_ajax","int");
			if($in_ajax == 1){
				tAjax::json_error("您已经登录,请刷新页面！");
			}else{
				$this->redirect("/ucenter/index");
			}
		}
	}
	public function index(){
		global $uid,$utype,$timestamp;

		$this->init();
		$ut      = 1;		
		if(tUtil::check_hash()){
			$data = array(
				"ut"     => $ut,
				"uname"  => R("uname","string"),
				"rzcode" => R("rzcode","string"),
				"pass"   => R("upass","string"),
				"pass2"  => R("upass2","string"),
				
				"name"   => R("name","string"),
				"email"  => R("email","string"),
				"mobile" => R("mobile","string"),
			);
			$res = SDK::web_api("/Register",$data);
			if($res['status'] == 1){
				$uid = $res['data']['uid'];
				//如果是微信登陆
				C("user")->session_bd($uid);
				//注册之后
            	C("user")->reg_after($uid);
				tAjax::json_success("注册成功");
			}else{
				tAjax::json_error($res['msg']);
			}
			tAjax::json($res);
		}else{
			$this->assign("ut",$ut);
			$this->display();
		}
	}
}
?>