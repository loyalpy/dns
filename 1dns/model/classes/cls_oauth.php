<?php
/**
 * @class Oauth
 * @brief oauth协议接口
 */
class cls_oauth{
	private $oauth_obj = null;
    //构造函数
	public function __construct($id){
		$oauth_row = M("oauth")->get_row("id='{$id}'");
		if(isset($oauth_row['id']) && $oauth_row['is_close'] == 0){
			if($this->_require($oauth_row['file'])){
				$config   = unserialize($oauth_row['config']);
				$class_name = "oauth_{$oauth_row['file']}";
				$this->oauth_obj = new $class_name($config);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//获取字段数据
	public function getFields(){
		return $this->oauth_obj->getFields();
	}
	//获取平台的用户信息
	public function getUserInfo($request_args){
		return $this->oauth_obj->getUserInfo($request_args);
	}
	//获取登录url地址
	public function getAuthorizeURL(){
		return $this->oauth_obj->getAuthorizeURL();
	}
	//获取必须数据参数
	public function NeedRequest(){
		return $this->oauth_obj->NeedRequest();
	}
	//引入平台接口文件
	private function _require($file_name){
		$class_file = ROOT_PATH.'lib/plugins/oauth2/'.$file_name.'/oauth_'.$file_name.'.php';
		if(file_exists($class_file)){
			include_once($class_file);
			return true;
		}else{
			return false;
		}
	}
}
//oauth基类
abstract class OauthBase{
	//获取回调URL地址
	protected function getReturnUrl(){
		return tUrl::get_host().tUrl::create('/login/oauth_callback');
	}
	abstract public function getAuthorizeURL();
	abstract public function getUserInfo($request_args);
	abstract public function getFields();
	abstract public function NeedRequest();
}
?>