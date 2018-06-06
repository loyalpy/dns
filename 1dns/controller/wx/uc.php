<?php
class uc extends tController{
    public function __construct(){
        global $uid, $utype;
        parent::__construct("uc");
        $userinfo = array();
        if ($uid) {
            $userinfo = C("user")->get_cache_userinfo($uid);
        }
        if (empty($uid) || !isset($userinfo['uid']) || $userinfo['uid'] == 0) {
            
        }
    }
    public function api() {
        $action = R("action", "string");
        $action_list = array();
        if (!in_array($action, $action_list)) {
            unset($_GET['action']);
            $ret = SDK::web_api("/" . str_replace(".", "/", $action), $_GET);
            $ret = JSON::encode($ret);
            die($ret);
        } else {
            die("no method");
        }
    }
}?>