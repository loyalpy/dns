<?php
class UC extends tController{
	private $login_url = "/login";
	public $userinfo = array();
	public $nav = array();
	public $purview = "";
	public $purview_name = "";
	public $layout = "ucenter";
	public $ext_id = 0;
	public function __construct($controller_id,$action=""){
		global $uid,$utype,$realip;
		parent::__construct($controller_id,$action);
		$userinfo = array();
		if($uid){
			$userinfo = C("user")->get_cache_userinfo($uid);
		}
		if(empty($uid) || !isset($userinfo['uid']) || $userinfo['uid'] == 0) {
			C("user")->logout();
			$in_ajax = R("in_ajax","int");
			if($in_ajax == 1 || tUtil::is_ajax()){
				tAjax::json(array(
				 	"error"   => 1,
				 	"message" => "登录超时,请重新登录",
				 	"callback"=> U($this->login_url)
				));
			}else{
				$this->redirect(U($this->login_url));
			}
		}
		//获取用户信息
		$this->userinfo = $userinfo;
		//获取菜单
		if($this->userinfo['inlock'] == 1){
			C("user")->logout();
			I("账号已锁定,如有疑问请联系八戒DNS客服！",U("account@/login"));
		}
		//判断用户是否已经授权
//		$cIP = M("rz_ip")->get_one("uid='{$uid}'","ips");
//		if (isset($cIP) && $cIP != 0) {
//			if (!in_array($realip,explode(";",$cIP))) {
//				I("您已授权指定IP,非授权IP地址不能登录！",U("account@/login/logout"));
//			}
//		}
		//判断用户是否验证手机/邮箱
		if(in_array($controller_id,array("domains","monitor","records"))){
			if($this->userinfo['emailrz'] == 0 && $this->userinfo['mobilerz'] == 0){
				$this->_msg("注册后必须认证","邮箱/手机必须验证一项",U("/ucenter/safety_center#安全中心认证"));
			}
		}
	}
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
	protected function _menu($utype = 0){
		$result = $purview = array();
		$cls_cate = new cls_category("user_module","c.utype={$utype}");
					
		$result['nav'] = $cls_cate->get_level(0);
		$result['top'] = $result['sub'] = '';
		$result['uri'] = tUtil::get_uri_path();
		
		foreach($result['nav'] as $k1=>$v1){
			if($v1['status'] != 1){
				unset($result['nav'][$k1]);
				continue;
			}
			$v1['url'] = empty($v1['url'])?"/{$v1['module']}/{$v1['action']}":$v1['url'];
			$purview[] = $v1['url'];
			$result['nav'][$k1]['url'] = $v1['url'];
            if($result['uri'] == $v1['url'] || ($v1['module'] == $this->ctrl_id && $v1['action'] == $this->action_id)){
            	$result['top'] = $k1;
            }
			foreach($v1['childrens'] as $k2=>$v2){
				$v2['url'] = empty($v2['url'])?"/{$v2['module']}/{$v2['action']}":$v2['url'];
				$purview[] = $v2['url'];
				
				$tmp = explode("<br />",nl2br($v2['extaction']));
				if($tmp){
					foreach($tmp as $tk2=>$tv2){
						$tmp2 = explode(",",$tv2);
						if(isset($tmp2[0]) && isset($tmp2[1])){
							$purview[] = $v2['url']."_".trim($tmp2[0]);
						}
					}
				}
				
				if($v2['status'] != 1){
					unset($result['nav'][$k1]['childrens'][$k2]);
					continue;
				}				
				$result['nav'][$k1]['childrens'][$k2]['url'] = $v2['url'];
				
				if($result['sub'])continue;
				if($result['sub'] == '' && $result['uri'] == $v2['url']){
					$result['sub'] = $k2;
					$result['top'] = $k1;
				}
			}
			
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
		$this->purview = "@".implode("@",$purview)."@/ucenter/index@/ucenter/index_tui@/ucenter/msg@/ucenter/auto_exec@";
		unset($cls_cate,$purview,$tmp);
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
    //会员中心错误提示
    public function _msg($title="",$info="",$btnstr="",$type="error"){
    	$return_url = U("/ucenter/msg?type={$type}");
    	$return_url.= "&title=".urlencode($title);
    	$return_url.= "&info=".urlencode($info);
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
		$idc = in_array($idc,$this->userinfo['idc'])?$idc:current($this->userinfo['idc']);
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