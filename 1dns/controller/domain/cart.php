<?php

/**
 * Class cart
 * Domain Register
 */
class cart extends tController{
    public $layout = "domain";
    public function __construct(){
        parent::__construct('cart');
    }
    //购物车首页
    public  function  cart(){
        $list = M("@domain_register_cart")->get_domain_cart();
        $this->assign("coupon",M("@coupon")->get_coupon(array(1,3)));//代金券1,通用2域名解析专用3域名注册专用
        $this->assign("list",$list);
        $this->display();
    }
    //购物车提交资料
    public function cart_sub(){
        global $uid,$timestamp;

        //判断是否已被注册
        $list = M("@domain_register_cart")->get_domain_cart();
        if (count($list['list']) > 0) {
            //判断域名是否已注册 TODO：转入域名已注册，暂时隐藏掉
//            foreach ($list['list'] as $k=>$v) {
//                $d = explode(".",$v['domain']);
//                $res = SDKdomain::check("check",$d[0],array(".".$d[1]));
//                if ($res['status'] == 1 && $res['list'][1]['chk'] == 0 && $v['type'] != 2) {
//                    M("domain_register_cart")->del("uid = '{$uid}' AND domain = '{$v['domain']}'");
//                    I("您购物车的域名已被注册，系统已自动删除此域名！",U("domain@/domain/lists"));
//                    exit;
//                }
//            }
            $tmp_arr = tFun::array_column($list['list'], 'type');
            //判断域名续费，新购不能同时处理
            if (in_array(1,$tmp_arr) && in_array(2,$tmp_arr)) {
                I("不能同时进行域名新购和续费，请分开处理！",U("domain@/cart/cart"));
            }
            //判断最多只能处理100个域名
            if (count($list['list']) > 100) {
                I("购物车域名上限为100！",U("domain@/cart/cart"));
            }
            //如果是续费则直接跳转到支付订单页面
            if (!in_array(1,$tmp_arr)) {
                $this->redirect(U("cart/cart_pay?protected_trace=on"));
            }
        }else{
            I("您的购物车为空，请先选择购买域名！",U("domain@/domain/index"));
        }

        $coupons  = R("coupons","string");
        //判断如果代金券存在是否过期等不能使用
        if (!empty($coupons)) {
            $res = M("coupon")->get_row("uid = '{$uid}' AND code = '{$coupons}'");
            if (isset($res['id'])) {
                if ($res['status'] == 1) {
                    I("代金券已使用",U("domain@/cart/cart"));
                }
                if ($res['expiry'] < $timestamp) {
                    I("代金券已过期",U("domain@/cart/cart"));
                }
            }else{
                I("非法参数",U("domain@/cart/cart"));
            }
        }

        $res = M("domain_register_info")->query("uid = '{$uid}'","","",500);

        $userinfo = C("user")->get_cache_userinfo($uid);
        $this->assign("info_list",$res);
        $this->assign("coupons",$coupons);
        $this->assign("userinfo",$userinfo);
        $this->display();
    }
    //购物车联系人模板
    public function cart_pay(){
        global $uid,$timestamp;
        $this->userinfo = C("user")->get_cache_userinfo($uid);
        if(!isset($this->userinfo['uid']) || empty($this->userinfo['uid'])){
            $this->redirect(U("account@/login?refer=").U("domain@/cart/cart_pay"));
        }

        if (tUtil::check_hash()) {
            $id                         = R("id","int");//模板id
            $utype                   = R("utype","int");
            $aller_name_cn      = R("aller_name_cn","string");
            $aller_name           = R("aller_name","string");
            $name_cn              = R("name_cn","string");
            $name                   = R("name","string");
            $email                   = R("email","string");

            $s_province            = R("s_province","stirng");
            $s_city                   = R("s_city","stirng");

            $addr_cn                = R("addr_cn","string");
            $addr                     = R("addr","string");
            $ub                        = R("ub","int");
            $mobile                  = R("mobile","string");
            $cz1                       = R("cz1","int");
            $cz2                       = R("cz2","int");
            $cz3                       = R("cz3","int");
            //验证输入内容不能为空
            if (empty($utype) || empty($aller_name_cn) || empty($aller_name) || empty($name_cn) || empty($name) || empty($email) || empty($s_province) || empty($s_city) || empty($addr_cn) || empty($addr) || empty($ub) || empty($mobile) || empty($cz1) || empty($cz2) || empty($cz3)) {
                tAjax::json_error("输入内容不为空！");
            }
            //验证用户类型是否合法
            if (!in_array($utype,array(1,2))) {
                tAjax::json_error("用户类型不正确！");
            }
            //验证邮箱格式是否正确
            if (!tValidate::is_email($email)) {
                tAjax::json_error("邮箱格式不正确！");
            }
            //验证手机格式是否正确
            if (!tValidate::is_phone($mobile)) {
                tAjax::json_error("手机格式不正确！");
            }
            $data = array(
                "uid"                     => $uid,
                "utype"                 => $utype,
                "aller_name_cn"    => $aller_name_cn,
                "aller_name"         => $aller_name,
                "name_cn"            => $name_cn,
                "name"                   => $name,
                "email"                 => $email,
                "area"                   => $s_province.",".$s_city,
                "addr_cn"               => $addr_cn,
                "addr"                   => $addr,
                "ub"                        => $ub,
                "mobile"                 => $mobile,
                "cz"                        => "0".$cz1.",".$cz2.",".$cz3,
                "template"              => 1, //当前使用模板

                "m_name_cn"            => $name_cn, //管理人
                "m_name"                   => $name,//管理人英文
                "m_email"                 => $email,//同上
                "m_area"                   => $s_province.",".$s_city,
                "m_addr_cn"             => $addr_cn,
                "m_addr"                  => $addr,
                "m_ub"                      => $ub,
                "m_mobile"               => $mobile,
            );
            if ($id) {//修改模板
                M("domain_register_info")->set_data($data)->update("uid = '{$uid}' AND id = '{$id}'");
                tAjax::json_success("保存成功");
            }else{//添加模板
                //判断添加模板信息不能超过20个
                if (M("domain_register_info")->get_one("uid = '{$uid}'","count(*)") > 20) {
                    tAjax::json_error("每个用户最多只能创建20个域名注册信息模板");
                }
                $res1 = M("domain_register_info")->set_data($data)->add();
                if ($res1) {
                    M("domain_register_info")->set_data(array("tpl_name"=>"新模板".date("Ymd",time()).$res1))->update("uid = '{$uid}' AND id = '{$res1}'");
                    M("domain_register_cart")->set_data(array("tpl"=>$res1))->update("status=0 AND uid=$uid AND type=1");
                    tAjax::json_success("提交成功");
                }else{
                    tAjax::json_error("提交失败");
                }
            }
        }else{
            $protected_trace = R("protected_trace","string");
            if ($protected_trace != "on") {
                I("参数错误！",U("domain@/domain/lists"));
            }
            $list = M("@domain_register_cart")->get_domain_cart();
            if (count($list['list']) == 0) {
                I("您的购物车为空，请先选择购买域名！",U("domain@/domain/index"));
            }

            //代金券
            $coupons  = R("coupons","string");
            //判断如果代金券存在是否过期等不能使用
            if (!empty($coupons)) {
                $res = M("coupon")->get_row("uid = '{$uid}' AND code = '{$coupons}'");
                if (isset($res['id'])) {
                    if ($res['status'] == 1) {
                        I("代金券已使用",U("domain@/cart/cart"));
                    }
                    if ($res['expiry'] < $timestamp) {
                        I("代金券已过期",U("domain@/cart/cart"));
                    }
                }else{
                    I("非法参数",U("domain@/cart/cart"));
                }
            }
            $this->assign("coupon_arr",M("@coupon")->get_coupon_row($coupons));
            
            $this->assign("userinfo",$this->userinfo);
            $this->assign("list",$list);
            $this->display();
        }
    }
    //购物车最后一步：支付页面
    public function pay(){
        global $uid;
        $order_no = R("order_no","string");

        $this->userinfo = C("user")->get_cache_userinfo($uid);
        if(!isset($this->userinfo['uid']) || empty($this->userinfo['uid'])){
            $this->redirect(U("account@/login?refer=").U("domain@/cart/pay?order_no=".$order_no));
        }
        
        $orderinfo = M("@register_order")->get($order_no);
        if(!isset($orderinfo['order_no'])){
            I("获取订单出错",U("domain@/ucenter/order"));
        }

        if($orderinfo['uid'] != $uid){
            I("非法查看",U("domain@/ucenter/order"));
        }
        $this->assign("orderinfo",$orderinfo);
        $this->display();
    }
    //添加订单列表
    public function order_add(){
        global $uid,$timestamp;
        $cart_ids0 = R("cart_ids0","string");
        if(empty($cart_ids0)){
            tAjax::json_error("获取购物车商品出错！");
        }
        //如果有未处理订单不能提交新订单
        if(M("register_order")->get_one("uid=$uid  AND indel=0 AND status IN(1,2,3)","count(*)") >0){
            tAjax::json_error("您有未完成订单，请先完成,再提交新订单");
        }
        $order_no = tUtil::create_numno(0);
        $order_data = array(
            "order_no" =>$order_no,
            "uid"          => $uid,
            "amount"   => 0,
            "amount_promation" =>0,
            "amount_coupon" =>0,
            "pay_status"   => 0,
            "status" =>2,
            "dateline" =>$timestamp,
            "cart_ids" => "",
            "point" =>0,
        );
        $order_item_data = array();
        //域名套餐订单处理
        
        $cart_ids  = "";
        if($cart_ids0){
            $cart_ids .= (($cart_ids == "")?"":",").$cart_ids0;
            $cartlist0 = M("@domain_register_cart")->get_domain_cart("cart_id IN({$cart_ids})");
            foreach($cartlist0['list'] as $key=>$v){
                $order_item_data[] = array(
                    "uid"                             => $uid,
                    "order_no"                   =>$order_no,
                    "type"                          => $v['type'],
                    "domain"                     => $v['domain'],
                    "amount"                    =>  sprintf("%.2f",$v['amount']),
                    "amount_promation" =>  sprintf("%.2f",$v['amount_promation']),
                    "amount_rate"            => $v['amount_rate'],
                    "price"                        =>  sprintf("%.2f",$v['price']),
                    "num"                         => $v['num'],
                    "dateline"                   =>$timestamp,
                    "tpl"                           => $v['tpl'],
                    "agent"                       =>$v['agent'],
                    "domain_type"           =>$v['domain_type'],
                    "status"                      => 0,
                );
            }

            $order_data['amount']                    += $cartlist0['amount'];
            $order_data['amount_promation'] += $cartlist0['amount_promation'];
        }

        if(count($order_item_data) == 0){
            tAjax::json_error("订单项有误");
        }
        //**********************************************************优惠券*********************************************************
        $coupon_code = R("coupon_code","string");
        if (!empty($coupon_code)) {
            $coupon_row = M("@coupon")->get_coupon_row($coupon_code);
            if (isset($coupon_row['id'])) {
                if ($coupon_row['expiry'] < $timestamp) {
                    tAjax::json_error("优惠券已过期，不能使用");
                }
                if ($coupon_row['status'] == 1 || $coupon_row['use_dateline'] != 0) {
                    tAjax::json_error("优惠券已使用");
                }
                if ($order_data['amount'] < $coupon_row['need_balance']) {
                    tAjax::json_error("总金额未满{$coupon_row['need_balance']}元,不能使用此优惠券");
                }
                $up_coupon = M("coupon")->set_data(array('status'=>1,'use_dateline'=>$timestamp,'order_no'=>$order_no))->update("uid = '{$uid}' AND code = '{$coupon_code}'");
                if (!$up_coupon) {
                    tAjax::json_error("优惠券使用失败");
                }
            }
        }
        $coupon = isset($coupon_row['id'])?intval($coupon_row['balance']):0;

        //订单提交
        $order_data['cart_ids'] = $cart_ids;
        $order_data['amount']                      =   sprintf("%.2f",$order_data['amount'] -$coupon);
        $order_data['amount_promation']   =   sprintf("%.2f",$order_data['amount_promation']);
        $order_data['amount_coupon']        =   sprintf("%.2f",$coupon);
        $order_data['point'] = intval($order_data['amount']);
        $ret = M("register_order")->set_data($order_data)->add();
        if($ret){
            $ret2 = M("register_order_item")->add_more($order_item_data);
            if($ret2 && $cart_ids ){
                M("domain_register_cart")->set_data(array("status"=>1))->update("cart_id IN({$cart_ids})");
            }
        }
        if ($ret) {
            tAjax::json_success($order_no);
        }else{
            tAjax::json_error("加入订单失败");
        }
    }
    //支付订单
    public function order_pay(){
        global $timestamp;
        $order_no = R("order_no","string");
        $pay_id = R("pay_id","int");
        $ret = 0;

        if($pay_id == 0){
            //更改支付方式
            M("register_order")->set_data(array("pay_type"=>$pay_id))->update("order_no='{$order_no}'");
            $ret = C("payment")->pay_account($order_no,"balance","register_order");
        }else{
            $order_row = M("register_order")->get_row("order_no='{$order_no}'");
            if(isset($order_row['order_no'])){
                if($order_row['pay_status']>0){
                    tAjax::json_error("该订单已支付");
                }
                M("register_order")->set_data(array("pay_type"=>$pay_id))->update("order_no='{$order_no}'");
                tAjax::json(array(
                    "error" => 0,
                    "callback" => U("account@/finance/recharge_submit?do=submit&pay_id={$pay_id}&inpay=1&order_no={$order_no}&order_type=register_order&recharge={$order_row['amount']}"),
                ));
            }
        }

        if($ret === 1){
            tAjax::json_success("支付成功！");
        }else{
            switch($ret){
                case -1:
                    tAjax::json_error("订单号为空");
                    break;
                case -2:
                    tAjax::json_error("订单不存在");
                    break;
                case -3:
                    tAjax::json_error("订单未确认");
                    break;
                default:;
                    tAjax::json_error("支付失败");
                    break;
            }
        }
    }
    //切换域名提交信息模板
    public function change_template(){
        global $uid;
        $id = R("id","int");
        if (empty($id)) {
            tAjax::json_error("模板不存在");
        }
        if(M("domain_register_info")->get_one("id='{$id}' AND uid='{$uid}'","count(id)")<=0){
            tAjax::json_error("模板不存在");
        }
        $ret = M("domain_register_cart")->set_data(array("tpl"=>$id))->update("status=0 AND uid=$uid AND type IN(1,3)");
        tAjax::json_success("提交成功！");
    }
    //加入购物车
    public function add_cart(){
        global $uid,$timestamp;
        $domain  = R("domain","string");
        $price      = R("price","int");
        if (empty($domain)) {
            tAjax::json_error("域名不存在！");
        }
        $num = M("domain_register_cart")->get_one("uid = '{$uid}' AND indel = 0 AND status = 0 AND domain = '{$domain}'","num");
        if ($num) { //更改购物车数量
            if ($num == 10) {
                tAjax::json_error("域名最长购买年限10年！");
            }
            $res = M("domain_register_cart")->set_data(array("num"=>$num+1))->update("uid = '{$uid}' AND domain = '{$domain}'");
            if ($res) {
                tAjax::json_success(1);
            }else{
                tAjax::json_error("加入购物车失败！");
            }
        }else{//添加购物车

            //获取空间服务商
            $agent  = App::get_conf("app.agent");
            if ($agent && $agent == "xinnet") {
                $agent = 1; //新网
            }else{
                $agent = 2;//万网
            }
            //读取域名后缀缓存
            $suffix_p = M("@domain_register_price")->get_cache_by_agent($agent);
            $suffix_arr = array();
            if (count($suffix_p) > 0) {
                foreach ($suffix_p as $k=>$v) {
                    if ($v['type'] == 4 || $v['type'] == 5) { //国内域名
                        $suffix_arr[] = ".".$v['name'];
                    }
                }
                $suffix_arr = array_unique($suffix_arr);
            }
            $reg_domain_type_tmp = substr($domain,strpos($domain,"."));
            $reg_domain_type = 0; //判断是0国际域名，1国内域名
            if (in_array($reg_domain_type_tmp,$suffix_arr)) {
                $reg_domain_type = 1;
            }

            $data = array(
                'uid'            => $uid,
                'type'           => 1, //1新购2,续费
                'num'           =>1,
                'price'          => $price,
                'domain'     => $domain,
                'dateline'     => $timestamp,
                'status'        =>0,
                "agent"        =>$agent,
                "domain_type" => $reg_domain_type,
                'indel'          =>0,
            );
            $res = M("domain_register_cart")->set_data($data)->add();
            if ($res) {
                tAjax::json_success($domain);
            }else{
                tAjax::json_error("加入购物车失败！");
            }
        }
    }
    //修改购物车
    public function edit_cart(){
        $cart_id = R("cart_id","int");
        $num = R("num","int");
        $cart_row = M("@domain_register_cart")->get($cart_id);
        if(!isset($cart_row['cart_id']) || $cart_row['indel'] == 1 || $cart_row['status'] == 1) {
            tAjax::json_error("购物车数据未找到!");
        }
        if($num <= 0){
            tAjax::json_error("购买时长必须选择");
        }
        if($num > 10){
            tAjax::json_error("域名最长续费年限10年");
        }
        $ret = M("domain_register_cart")->set_data(array("num"=>$num))->update("cart_id='{$cart_id}'");
        if ($ret) {
            tAjax::json_success("修改购物车成功");
        }else{
            tAjax::json_error("修改购物车失败");
        }
    }
    //删除购物车域名
    public function del_cart(){
        global $uid;
        $domain = R("domain","string");
        if (empty($domain)) {
            tAjax::json_error("域名不存在！");
        }
        $res = M("domain_register_cart")->del("uid = '{$uid}' AND domain = '{$domain}'"); //推荐直接删除
        if ($res) {
            tAjax::json_success("删除成功！");
        }else{
            tAjax::json_error("删除失败！");
        }
    }
}
?>