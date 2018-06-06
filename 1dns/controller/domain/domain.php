<?php

/**
 * Class domain
 * Domain Register
 */
class domain extends tController{
    public $layout = "domain";
    public function __construct(){
        parent::__construct('domain');
    }
    public function index(){
        $this->display();
    }
    public function lists(){
        global $uid;

        //判断用户是否锁定，是否进行邮箱认证
        if($uid){
            $userinfo = C("user")->get_cache_userinfo($uid);
            if (isset($userinfo['emailrz']) && isset($userinfo['mobilerz']) && $userinfo['emailrz'] == 0 && $userinfo['mobilerz'] == 0) {
                I("注册后必须认证",U("account@/ucenter/safety_center#安全中心认证"));
            }
        }

        $type       = R("type","int",0); // 0单后缀模式,1，多后缀模式
        $domain     = R("domain","string");
        $suffix     = R("suffix","string",".com");
        $suffixs    = R("suffixs","string");
        $type       = in_array($type,array(0,1))?$type:0;

        if(empty($domain)){
            $domain = R("reg_domain","string");
        }

        //读取域名后缀缓存
        $suffix_p = M("@domain_register_price")->get_cache_by_agent(1);
        $suffix_arr = array();
        foreach ($suffix_p as $k=>$v) {
            if ($v['type'] == 3) { //默认搜索国际中文域名
                $suffix_arr[] = ".".$v['name'];
            }
        }

        //默认搜索域名后缀
        if (!empty($domain)) {

            //判断域名是否合法,过滤非法特殊字符
            $domain = preg_replace("/[',:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\" | \| /","",$domain);

            $domain_str = substr($domain,0,strpos($domain,"."));
            $suffix_str = substr($domain,strpos($domain,"."));
            if (!empty($domain_str)) {
                $domain = $domain_str;
                if (in_array(substr($suffix_str,strpos($suffix_str,".")+1),tFun::array_column($suffix_p, 'name'))) { //如果搜索不存在域名则默认搜索.com
                    $suffix = $suffix_str;
                }else{
                    $suffix = ".com";
                }
            }
        }
        //多域名后缀
        if (!empty($suffixs)) {
            $suffixs = explode(",",$suffixs);
        }else{
            $suffixs = array();
        }
        
        $suffix_arr[] = ".com.cn";
        $suffix_arr[] = ".net.cn";
        //购物车
        $cart = M("@domain_register_cart")->get_domain_cart();
        $this->assign("cart",$cart['list']);
        $this->assign("domain",$domain);
        $this->assign("suffix",$suffix);
        $this->assign("suffixs",$suffixs);
        $this->assign("suffix_arr",$suffix_arr);
        $this->assign("type",$type);
        $this->display();
    }
    //域名查询
    public function check(){

        $domain  = R("domain","string");
        $suffix     = R("suffix","string");

        //判断域名后缀是否为空
        if (empty($domain) || empty($suffix)) {
            tAjax::json_error("请输入您需要搜索的域名!");
        }
        //读取域名后缀缓存
        $suffix_p = M("@domain_register_price")->get_cache_by_agent(1);
        $suffix_arr = array();
        foreach ($suffix_p as $k=>$v) {
                if ($v['type'] == 3) { //默认搜索国际中文域名
                    $suffix_arr[] = ".".$v['name'];
                }
        }
        //域名查询
        $res = SDKdomain::check("check",$domain,array($suffix));
        $res['list'] = array_values($res['list']);
        //域名价格
        foreach ($res['list'] as $k=>$v) {
            foreach ($suffix_p as $key=>$val) {
                if ($v['suffix'] == ".".$val['name']) {
                    $res['list'][$k]['new_price'] = (int)$val['new_price'];
                    $res['list'][$k]['renew_price'] = (int)$val['renew_price'];
                }
            }
        }
        tAjax::json($res);
    }
    //查询注册信息
    public function check_info(){
       global $uid,$timestamp;
        $domain = R("domain","string");
        $do = R("do","string");

        if ($do == "get") {
            $check = R("check","string");
            $time = time() - 30*86400;
            $register_domain_checkinfo = M("register_domain_checkinfo")->get_row("domain = '{$domain}' AND lastdateline > '{$time}'");
            if (isset($register_domain_checkinfo['id']) && $register_domain_checkinfo['domain_detailinfo'] && $check != "fresh") {//读取缓存
                $domain_info1 = array(
                    "r_organize_name_uk"    =>$register_domain_checkinfo['domain_alluser'],
                    "r_user_name_cn"            =>$register_domain_checkinfo['domain_user'],
                    "r_email"                         => $register_domain_checkinfo['domain_allemail'],
                    "applyDate"                    =>$register_domain_checkinfo['reg_time'] == 0?'':date("Y-m-d",$register_domain_checkinfo['reg_time']),
                    "expireDate"                   => $register_domain_checkinfo['exp_time'] == 0?'':date("Y-m-d",$register_domain_checkinfo['exp_time']),
                    "dns"                               => explode(",",isset($register_domain_checkinfo['dns'])?$register_domain_checkinfo['dns']:''),
                    "domain_status"             => explode(";",isset($register_domain_checkinfo['domain_status'])?$register_domain_checkinfo['domain_status']:''),
                    "registrant"                     =>$register_domain_checkinfo['domain_register'],
                );
                $aj_arr = array(
                    'check_time'      => date("Y-m-d H:i:s",$register_domain_checkinfo['lastdateline']),
                    'domain_info1' => $domain_info1,
                    'domain_info2' => $register_domain_checkinfo['domain_detailinfo'],
                );
            }else{//读取远程数据
                $domain_info1 = SDKdomain::check_info("queryDomainInfo",$domain,"regType");
                $domain_info2 = DNSapi::whois($domain);
                //判断如果本地whois查询不到，读取gitwhois:6s
                if ($domain_info2 == -1) {
                    require_once(ROOT_PATH.'lib/tools/whois/Whois.php');
                    if (class_exists("Whois")){
                        $wc = new Whois();
                        $domain_info2 = $wc->info($domain);
                    }
                }
                if ($domain_info1['status'] == 1) {
                    $domain_info1 = $domain_info1['list'];
                }else{
                    if ($domain_info2 == -1) {
                        $aj_arr = array(
                            'check_time'      => date("Y-m-d H:i:s",time()),
                            'domain_info1' => array(),
                            'domain_info2' => $domain_info2,
                        );
                        tAjax::json(array("error"=>0,"message"=>$aj_arr));
                    }else{
                        $a_user_name_cn         = $this->_check_info_detail("a_user_name_cn",$domain,$domain_info2);
                        $a_organize_name_uk  = $this->_check_info_detail("a_organize_name_uk",$domain,$domain_info2);
                        $email                          = $this->_check_info_detail("a_email",$domain,$domain_info2);
                        $expireDate                  = $this->_check_info_detail("expireDate",$domain,$domain_info2);
                        $applyDate                  = $this->_check_info_detail("applyDate",$domain,$domain_info2);
                        $dns                            = $this->_check_info_detail("name_server",$domain,$domain_info2);
                        $domain_info1 = array(
                            'r_organize_name_uk'    =>$a_organize_name_uk['status'] == 1?$a_organize_name_uk['name']:'',//域名所有者
                            'r_user_name_cn'    =>$a_user_name_cn['status'] == 1?$a_user_name_cn['name']:'',//联系人
                            'r_email'                 => isset($email[0])?$email[0]:(isset($email[1])?$email[1]:''),//域名注册邮箱
                            'applyDate'             =>$applyDate['status'] == 1?$applyDate['expiry']:'',//域名注册日期
                            'expireDate'            =>$expireDate['status'] == 1?$expireDate['expiry']:'',//域名到期日期
                            'dns'                        =>$dns['status'] == 1?$dns['dns']:array(),//域名dns
                        );
                    }
                }
                //域名状态
                $domain_status = $this->_check_info_detail("domain_status",$domain,$domain_info2);
                if ($domain_status['status'] == 1) {
                    foreach ($domain_status['name'] as $key=>$val) {
                        if (preg_match("/ok/",$val)) {
                            $domain_status['name'][$key] = str_replace("ok ","<i class=\"glyphicon glyphicon-ok-circle\" style=\"color: #A1CF14\"></i> 正常 ",$val);
                        }
                        if (preg_match("/clientTransferProhibited/",$val)) {
                            $domain_status['name'][$key] = "注册商设置禁止转移(clientTransferProhibited)"."<br/>"."https://icann.org/epp#clientTransferProhibited";
                        }
                        if (preg_match("/clientUpdateProhibited/",$val)) {
                            $domain_status['name'][$key] = "注册商设置禁止更新(clientUpdateProhibited)"."<br/>"."https://icann.org/epp#clientUpdateProhibited";
                        }
                        if (preg_match("/serverHold/",$val)) {
                            $domain_status['name'][$key] ="注册局设置禁止解析(serverHold)"."<br/>";
                        }
                        if (preg_match("/clientDeleteProhibited/",$val)) {
                            $domain_status['name'][$key] ="注册商设置禁止删除(clientDeleteProhibited)"."<br/>"."https://icann.org/epp#clientDeleteProhibited";
                        }
                        if (preg_match("/pendingTransfer/",$val)) {
                            $domain_status['name'][$key] ="转移中(pendingTransfer)"."<br/>";
                        }
                        if (preg_match("/redemptionPeriod/",$val)) {
                            $domain_status['name'][$key] ="偿还期(redemptionPeriod)"."<br/>";
                        }
                        if (preg_match("/pendingDelete/",$val)) {
                            $domain_status['name'][$key] ="删除期(pendingDelete)"."<br/>";
                        }
                    }
                    $domain_info1['domain_status']  = $domain_status['name'];
                }else{
                    $domain_info1['domain_status'] = array();
                }
                //域名注册商
                $domain_registrant = $this->_check_info_detail("registrant",$domain,$domain_info2);
                $domain_info1['registrant'] = $domain_registrant['status'] == 1?$domain_registrant['name']:'';

                $aj_arr = array(
                    'check_time'      => date("Y-m-d H:i:s",time()),
                    'domain_info1' => $domain_info1,
                    'domain_info2' => $domain_info2,
                );

                //加入数据库缓存
                if (isset($domain_info1['dns1'])) {
                    $dns = $domain_info1['dns1'].(isset($domain_info1['dns2'])?(','.$domain_info1['dns2']):'');
                }else{
                    $dns = implode(",",isset($domain_info1['dns'])?$domain_info1['dns']:'');
                }
                $data_arr = array(
                    "uid"=>$uid,
                    "domain"=>$domain,
                    "dateline"=>$timestamp,
                    "lastdateline"=>$timestamp,
                    "domain_alluser"=>$domain_info1['r_organize_name_uk'],
                    "domain_user"=>$domain_info1['r_user_name_cn'],
                    "domain_allemail"=>$domain_info1['r_email'],
                    "domain_register"=>$domain_info1['registrant'],
                    "reg_time"=>empty($domain_info1['applyDate'])?0:strtotime($domain_info1['applyDate']),
                    "exp_time"=>empty($domain_info1['expireDate'])?0:strtotime($domain_info1['expireDate']),
                    "domain_status"=>implode(";",$domain_info1['domain_status']),
                    "dns"=>$dns,
                    "domain_detailinfo"=>str_replace("'","\'",$domain_info2),
                );
                if (M("register_domain_checkinfo")->get_one("domain = '{$domain}'","id")) {
                    unset($data_arr['dateline']);
                    M("register_domain_checkinfo")->set_data($data_arr)->update("domain = '{$domain}'");
                }else{
                    M("register_domain_checkinfo")->set_data($data_arr)->add();
                }
            }
            tAjax::json(array("error"=>0,"message"=>$aj_arr));
        }else{
            $this->assign("domain",$domain);
            $this->display();
        }
    }
    //域名后缀价格表
    public function price_info(){
        $price = array();
        $price_tmp = tCache::read("domain_register_price1");
        //展示英文国际域名
        $show_domain = array("com","net","name","org","biz","cc","tv","info","site","bz","la","cl","me","tel","car","vip","today","gift","lol","online","company");
        //中文域名
        $show_domain_cn = array("cn","在线","集团","网店","网址","中文网","我爱你");
        foreach ($price_tmp as $key=>$val) {
            $arr_tmp_v = array(
                "name"=>$val['name'],
                "new_price"=>$val['new_price'],
                "renew_price"=>$val['renew_price']
            );
           if ($val['type'] == 2 && in_array($val['name'],$show_domain)){
               $price[$key] = $arr_tmp_v;
           }
            if ($val['type'] == 3 && in_array($val['name'],$show_domain_cn)) {
                $price[$key] = $arr_tmp_v;
            }
        }
        $this->assign("price",$price);
        $this->display();
    }
    //查询注册信息详细信息(节省时间)
    public function _check_info_detail($type,$domain,$domain_info2){
        switch ($type) {
            case "a_organize_name_uk";
                $preg_no = '/No match for domain "'.$domain.'"/i';
                $pregstr = "/Registrant Organization:([0-9a-zA-Z ,.\/@-]+)/is";
                if($domain_info2){
                    if(preg_match($preg_no, $domain_info2,$matched)){
                        return array("status"=>-1,"name"=>0);
                    }
                    if(preg_match($pregstr, $domain_info2,$matched)){
                        if(isset($matched[1]) && trim($matched[1])){
                            return array("status"=>1,"name"=>substr(trim($matched[1]),0,80));
                        }
                    }
                    if(preg_match("/Registrant:([0-9a-zA-Z ,.\/@-]+)/is", $domain_info2,$matched)){
                        if(isset($matched[1]) && trim($matched[1])){
                            return array("status"=>1,"name"=>substr(trim($matched[1]),0,80));
                        }
                    }
                    if(preg_match("/(Registrant:).*/", $domain_info2,$matched)){
                        if(isset($matched[0]) && trim($matched[0])){
                            return array("status"=>1,"name"=>substr(trim($matched[0]),11,80));
                        }
                    }
                }
                return array("status"=>0,"name"=>0,"msg"=>$domain_info2);
                break;
            case "a_user_name_cn";
                $preg_no = '/No match for domain "'.$domain.'"/i';
                $pregstr = "/Registrant Name:([0-9a-zA-Z ,.\/@-]+)/is";
                if($domain_info2){
                    if(preg_match($preg_no, $domain_info2,$matched)){
                        return array("status"=>-1,"name"=>0);
                    }
                    if(preg_match($pregstr, $domain_info2,$matched)){
                        if(isset($matched[1]) && trim($matched[1])){
                            return array("status"=>1,"name"=>substr(trim($matched[1]),0,80));
                        }
                    }
                    if(preg_match("/(Registrant Name:).*/", $domain_info2,$matched)){
                        if(isset($matched[0]) && trim($matched[0])){
                            return array("status"=>1,"name"=>substr(trim($matched[0]),16,80));
                        }
                    }
                }
                return array("status"=>0,"name"=>0,"msg"=>$domain_info2);
                break;
            case "registrant";
                $preg_no = '/No match for domain "'.$domain.'"/i';
                $pregstr = "/Registrar:([0-9a-zA-Z ,.\/@-]+)/is";
                if($domain_info2){
                    if(preg_match($preg_no, $domain_info2,$matched)){
                        return array("status"=>-1,"name"=>0);
                    }
                    if(preg_match($pregstr, $domain_info2,$matched)){
                        if(isset($matched[1]) && trim($matched[1])){
                            return array("status"=>1,"name"=>substr(trim($matched[1]),0,80));
                        }
                    }
                }
                return array("status"=>0,"name"=>0,"msg"=>$domain_info2);
                break;
            case "domain_status";
                $preg_no = '/No match for domain "'.$domain.'"/i';
                $pregstr = "/Domain Status:([0-9a-zA-Z\-: \/.,#-]+)/is";
                if($domain_info2){
                    if(preg_match($preg_no, $domain_info2,$matched)){
                        return array("status"=>-1,"name"=>0);
                    }
                    if(preg_match_all($pregstr, $domain_info2,$matched)){
                        if(isset($matched[1]) && $matched[1]){
                            return array("status"=>1,"name"=>$matched[1]);
                        }
                    }
                }
                return array("status"=>0,"name"=>0,"msg"=>$domain_info2);
                break;
            case "a_email";
                $exp = array("xinnet","abuse","yinsibaohu");
                $pregstr = "/([a-z0-9_\-\.]+)@(([a-z0-9]+[_\-]?)\.)+[a-z]{2,3}/i";
                if (preg_match_all($pregstr,$domain_info2,$matched)) {
                    $result = $matched[0];
                    foreach($result as $key=>$v){
                        $v = strtolower($v);
                        if(!tValidate::is_email($v)){
                            unset($result[$key]);
                        }
                        foreach($exp as $v2){
                            if(strpos($v,$v2) === false){
                                continue;
                            }else{
                                unset($result[$key]);
                            }
                        }
                    }
                    return $result;
                }else{
                    return array();
                }
                break;
            case "applyDate";
                $preg_no = '/No match for domain "'.$domain.'"/i';
                $pregstr = "/Creation Date:([0-9a-zA-Z\-: ]+)/is";
                if($domain_info2){
                    if(preg_match($preg_no, $domain_info2,$matched)){
                        return array("status"=>-1,"expiry"=>0);
                    }
                    if(preg_match($pregstr, $domain_info2,$matched)){
                        if(isset($matched[1]) && trim($matched[1])){
                            return array("status"=>1,"expiry"=>substr(trim($matched[1]),0,10));
                        }
                    }
                    if(preg_match("/Registration Time:([0-9a-zA-Z\-: ]+)/is", $domain_info2,$matched)){
                        if(isset($matched[1]) && trim($matched[1])){
                            return array("status"=>1,"expiry"=>substr(trim($matched[1]),0,10));
                        }
                    }
                }
                return array("status"=>0,"expiry"=>0,"msg"=>$domain_info2);
                break;
            case "expireDate";
                $preg_no = '/No match for domain "'.$domain.'"/i';
                $pregstr = "/Registrar Registration Expiration Date:([0-9a-zA-Z\-: ]+)/is";
                if($domain_info2){
                    if(preg_match($preg_no, $domain_info2,$matched)){
                        return array("status"=>-1,"expiry"=>0);
                    }
                    if(preg_match($pregstr, $domain_info2,$matched)){
                        if(isset($matched[1]) && trim($matched[1])){
                            return array("status"=>1,"expiry"=>substr(trim($matched[1]),0,10));
                        }
                    }
                    if(preg_match("/Registry Expiry Date:([0-9a-zA-Z\-: ]+)/is", $domain_info2,$matched)){
                        if(isset($matched[1]) && trim($matched[1])){
                            return array("status"=>1,"expiry"=>substr(trim($matched[1]),0,10));
                        }
                    }
                    if(preg_match("/Expiration Time:([0-9a-zA-Z\-: ]+)/is", $domain_info2,$matched)){
                        if(isset($matched[1]) && trim($matched[1])){
                            return array("status"=>1,"expiry"=>substr(trim($matched[1]),0,10));
                        }
                    }
                }
                return array("status"=>0,"expiry"=>0,"msg"=>$domain_info2);
                break;
            case "name_server";
                $preg_no = '/No match for domain "'.$domain.'"/i';
                $pregstr = "/Name Server:([0-9a-zA-Z ,.-]+)/is";
                if($domain_info2){
                    if(preg_match($preg_no, $domain_info2,$matched)){
                        return array("status"=>-1,"dns"=>0);
                    }
                    if(preg_match_all($pregstr, $domain_info2,$matched)){
                        if(isset($matched[1]) && $matched[1]){
                            return array("status"=>1,"dns"=>$matched[1]);
                        }
                    }
                }
                return array("status"=>0,"name"=>0,"dns"=>$domain_info2);
                break;
            default:
                return 0;
        }
    }
}
?>