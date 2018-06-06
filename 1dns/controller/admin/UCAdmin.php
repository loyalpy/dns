<?php
class UCAdmin extends tController{
	private $login_url = "/login";
	public $userinfo = array();
	public $nav = array();
	public $purview = "";
	public $purview_name = "";
	public $layout = "admin";
	public function __construct($controller_id){
		global $uid,$utype;
		parent::__construct($controller_id);
		if(empty($uid)){
			$in_ajax = R("in_ajax","int");
			if($in_ajax == 1){
				tAjax::json(array(
				 	"error"   => 1,
				 	"message" => "登录超时,请重新登录",
				 	"callback"=> U($this->login_url)
				));
			}else{
				$this->redirect(U("account@/login?refer=admin."));
			}
		}
		//获取用户信息
		$this->userinfo = C("user")->get_cache_userinfo($uid);
		//获取菜单
		if($this->userinfo['inlock'] == 1){
			C("user")->logout();
			I("账号已锁定,如有疑问请联系八戒DNS客服！",U("account@/login"));
		}
		$this->nav = $this->_menu();
		//检查区权限
		$tmp_purview = C("user")->get_urole($this->userinfo['urole']);
		$this->purview = $tmp_purview['purview'];
		if($this->purview != "@all@"){
			$this->purview .= "@/sys_manager/index@/sys_manager/api@/import_manager/ImportRegister@/import_manager/ChangeOnlineDomain@"; //非所有权限把通用权限连接上
		}
		if($this->userinfo['urole'] == 0 || $this->check_right($this->purview) === false){
			$in_ajax = R("in_ajax","int");
			if($in_ajax == 1 || tUtil::is_ajax()){
				tAjax::json_error("您的权限不足!");
			}else{
				$this->_msg("您的权限不足!","您的权限不足,请联系管理员","/ucenter#返回首页");
			}
		}
	}
	//访问模块控制
	//访问模块控制
	public function api(){
		$action = R("action","string");
		$action_list = array();
		if(!in_array($action,$action_list)){
			unset($_GET['action']);
			$ret = SDK::web_api("/".str_replace(".", "/", $action), $_GET);
			$ret = JSON::encode($ret);
			die($ret);
		}else{
			die("no method");
		}
	}
	//配置菜单
	protected function _menu(){
		$result = $purview = array();				
		$result['nav'] = C("category","sysmodule")->get_level(0);
		$result['top'] = $result['sub'] = '';
		$result['uri'] = tUtil::get_uri_path();
		
		foreach($result['nav'] as $k1=>$v1){
			$result['nav'][$k1]['url'] = empty($v1['url'])?"/{$v1['module']}/{$v1['action']}":$v1['url'];
			foreach($v1['childrens'] as $k2=>$v2){
				$result['nav'][$k1]['childrens'][$k2]['url'] = $v2['url'] = empty($v2['url'])?"/{$v2['module']}/{$v2['action']}":$v2['url'];
				if($result['sub'] == '' && $result['uri'] == $v2['url']){
					$result['sub'] = $k2;
					$result['top'] = $k1;
					break;
				}
			}
			if($result['top'] != '')break;
		}
		if($result['sub'] == ''){
			$tmp = str_replace(substr($result['uri'],strripos($result['uri'],"_")),"",$result['uri']);
			foreach($result['nav'] as $k1=>$v1){
				foreach($v1['childrens'] as $k2=>$v2){
					if($result['sub'] == '' && $tmp == $v2['url']){
						$result['sub'] = $k2;
						$result['top'] = $k1;
						break;
					}
				}
				if($result['top'] != '')break;
			}
		}
		return $result;
	}
	//访问用户类型,用户资料完成度权限控制
    protected function _qx_utype(){
    	global $uid,$utype;
    	
    }
    //角色访问权限控制
    protected function _qx_urole(){
    	global $uid,$utype;
    	
    }
    //检查权限
    protected function check_purview($mod_act=""){
    	//if(empty($mod_act))return false;
    	if($this->purview != "@all@" && strpos($this->purview,"@{$mod_act}@") === false){
			return false;
    	}
		return true;
    }
    //检查个人特殊权限
    protected function check_upurview($index=0){
    	return (substr($this->userinfo['upurview'],$index,1) === "Y")?true:false;
    }
    //会员中心错误提示
    public function _msg($info="",$extstr="",$btnstr="",$title="系统提示",$type="error",$exttype=""){
    	$return_url = U("account@/ucenter/msg?type={$type}&exttype={$exttype}");
    	$return_url.= "&title=".urlencode($title);
    	$return_url.= "&info=".urlencode($info);
    	$return_url.= "&extstr=".urlencode($extstr);
    	$return_url.= "&btnstr=".urlencode($btnstr);
    	$in_ajax = R("in_ajax","int");
    	if($in_ajax == 1){
    		tAjax::json_error($info);
    	}else{
    		$this->redirect($return_url);
    	}
    }
    //获取IDC
	public function _parse_idc(){
		$idc = R("idc","int");
		//$idc = in_array($idc,$this->userinfo['idc'])?$idc:current($this->userinfo['idc']);
		$this->assign("idc",$idc);
		return $idc;
	}
	//设置导航
	public function _parse_navs($navs = array()){
		$cr = 0;
		if(!empty($navs)){
			$cr = R("cr","string");
			$cr = in_array($cr,array_keys($navs))?$cr:current(array_keys($navs));
			$this->assign("cr",$cr);
	     	$this->assign("navs",$navs);
		}
		return $cr;
	}
}
?>