<?php
class WeiXin extends tController{
	public function index(){
		SDKwx::init();
		//SDKwx::chk_setting();
		SDKwx::init_data();
		SDKwx::exe_req();
	}
	public function Login(){
		echo "<script lanugage='text/javascript'>WeixinJSBridge.invoke('closeWindow',{},function(res){

    alert('授权成功');

});</script>";
	}
	public function test(){
		SDKwx::init();
		$res = \LaneWeChat\Core\Menu::getMenu();
		dump($res);
	}
}
?>