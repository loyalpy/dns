<?php
/**
 * 登录
 * by Thinkhu 2014
 */
class Order extends API{
    public function __construct(){
        parent::__construct('Order');
    }
    //加入订单
    public function Add(){
        global $uid,$timestamp;
        //如果有未处理订单不能提交新订单
        if(M("order")->get_one("uid=$uid  AND indel=0 AND status IN(1,2,3)","count(*)") >0){
            $this->respons_error("您有未完成订单，请先完成,再提交新订单");
        }
        $order_no = tUtil::create_numno(0);
        $order_data = array(
            "order_no" =>$order_no,
            "uid"          => $uid,
            "amount"   => 0,
            "amount_promation" =>0,
            "amount_other"  => 0,
            "amount_coupon" =>0,
            "pay_status"   => 0,
            "status" =>2,
            "dateline" =>$timestamp,
            "cart_ids" => "",
            "point" =>0,
        );
        $order_item_data0 = array();
        //域名套餐订单处理
        $cart_ids0 = R("cart_ids0","string");
        $cart_ids  = "";
        if($cart_ids0){
            $cart_ids .= (($cart_ids == "")?"":",").$cart_ids0;
            $cartlist0 = M("@cart")->get_mycart_domain("a.cart_id IN({$cart_ids})");
            foreach($cartlist0['list'] as $key=>$v){
                $old_goods_no = M("domain")->get_one("domain = '{$v['goods_name']}'","service_group");
                $order_item_data0[] = array(
                    "uid"                            => $uid,
                    "order_no"                  =>$order_no,
                    "type"                          => 0,
                    "buy_type"                  => ($old_goods_no == $v['goods_no'])?2:1,
                    "goods_no"                 => $v['goods_no'],
                    "goods_name"            => $v['goods_name'],
                    "amount"                    =>  sprintf("%.2f",$v['amount']),
                    "amount_promation" =>  sprintf("%.2f",$v['amount_promation']),
                    "amount_other"          =>  sprintf("%.2f",$v['amount_other']),
                    "amount_rate"            => $v['amount_rate'],
                    "price"                        =>  sprintf("%.2f",$v['price']),
                    "num"                         => $v['num'],
                    "dateline"                   =>$timestamp,
                    "status"                      => 0,
                );
            }
            $order_data['amount']                    += $cartlist0['amount'];
            $order_data['amount_promation'] += $cartlist0['amount_promation'];
            $order_data['amount_other']          += $cartlist0['amount_other'];
        }
        //短信增值服务套餐处理
        $order_item_data1 = array();
        $cart_ids1 = R("cart_ids1","string");
        if($cart_ids1){
            $cart_ids .= (($cart_ids == "")?"":",").$cart_ids1;
            $cartlist1 = M("@cart")->get_mycart_goods("cart_id IN({$cart_ids})");
            foreach($cartlist1['list'] as $key=>$v){
                $order_item_data1[] = array(
                    "uid"                             => $uid,
                    "order_no"                   =>$order_no,
                    "type"                          => 1,
                    "buy_type"                   => 1,
                    "goods_no"                 => $v['goods_no'],
                    "goods_name"            => "",
                    "amount"                    =>  sprintf("%.2f",$v['amount']),
                    "amount_promation" =>  sprintf("%.2f",$v['amount_promation']),
                    "amount_other"          =>  0,
                    "amount_rate"            => $v['amount_rate'],
                    "price"                        =>  sprintf("%.2f",$v['price']),
                    "num"                         => $v['num'],
                    "dateline"                   =>$timestamp,
                    "status"                      => 0,
                );
            }
            $order_data['amount']                    += $cartlist1['amount'];
            $order_data['amount_promation'] += $cartlist1['amount_promation'];
            $order_data['amount_other']          += 0;
        }

        if(count($order_item_data0) == 0 && count($order_item_data1) == 0){
            $this->respons_error("订单项有误");
        }
        $order_item_data = array_merge($order_item_data0,$order_item_data1);
        //**********************************************************优惠券*********************************************************
        $coupon_code = R("coupon_code","string");
        if (!empty($coupon_code)) {
            $coupon_row = M("@coupon")->get_coupon_row($coupon_code);
            if (isset($coupon_row['id'])) {
                if ($coupon_row['expiry'] < $timestamp) {
                    $this->respons_error("优惠券已过期，不能使用");
                }
                if ($coupon_row['status'] == 1 || $coupon_row['use_dateline'] != 0) {
                    $this->respons_error("优惠券已使用");
                }
                if ($order_data['amount'] < $coupon_row['need_balance']) {
                    $this->respons_error("总金额未满{$coupon_row['need_balance']}元,不能使用此优惠券");
                }
                $up_coupon = M("coupon")->set_data(array('status'=>1,'use_dateline'=>$timestamp,'order_no'=>$order_no))->update("uid = '{$uid}' AND code = '{$coupon_code}'");
                if (!$up_coupon) {
                    $this->respons_error("优惠券使用失败");
                }
            }
        }
        $coupon = isset($coupon_row['id'])?intval($coupon_row['balance']):0;

        //订单提交
        $order_data['cart_ids'] = $cart_ids;
        $order_data['amount']                      =   sprintf("%.2f",$order_data['amount']-$coupon);
        $order_data['amount_promation']   =   sprintf("%.2f",$order_data['amount_promation']);
        $order_data['amount_other']            =   sprintf("%.2f",$order_data['amount_other']);
        $order_data['amount_coupon']        =   sprintf("%.2f",$coupon);
        $order_data['point'] = intval($order_data['amount']);
        $ret = M("order")->set_data($order_data)->add();
        if($ret){
            $ret2 = M("order_item")->add_more($order_item_data);
            if($ret2 && $cart_ids ){
                M("cart")->set_data(array("status"=>1))->update("cart_id IN({$cart_ids})");
            }
        }
        if ($ret) {
            $this->respons_success("加入订单成功",array("order_no"=>$order_no));
        }else{
            $this->respons_error("加入订单失败");
        }
    }
    //修改订单
    public function Edit(){

    }
    //支付订单
    public function Pay(){
        global $uid,$timestamp;
        $order_no = R("order_no","string");
        $pay_id   = R("pay_id","int");
        $ret = 0;

        //防刷新
        $old_timestamp = tSafe::get('timestamp');
        if($old_timestamp>0 && ($timestamp-$old_timestamp) <10){
            I("不要重复提交数据,该页面禁止刷新!",U("account@/order/order"));
        }else{
            tSafe::set('timestamp',$timestamp);
        }

        if($pay_id == 0){
            //更改支付方式
            M("order")->set_data(array("pay_type"=>$pay_id))->update("order_no='{$order_no}'");
            $ret = C("payment")->pay_account($order_no);
        }else{
            $order_row = M("order")->get_row("order_no='{$order_no}'");
            if(isset($order_row['order_no'])){
                if($order_row['pay_status']>0){
                    $this->respons_error("该订单已支付");
                }
                M("order")->set_data(array("pay_type"=>$pay_id))->update("order_no='{$order_no}'");
                $this->respons_success("跳转至支付接口",array("surl" => U("account@/finance/recharge_submit?do=submit&pay_id={$pay_id}&order_no={$order_no}&order_type=order&inpay=1&recharge={$order_row['amount']}")));
            }
        }

        if($ret === 1){
            $this->respons_success("支付成功",array("sendurl"=>U("/api/Order.Send?order_no=".$order_no)));
        }else{
            switch($ret){
                case -1:
                    $this->respons_error("订单号为空");
                    break;
                case -2:
                    $this->respons_error("订单不存在");
                    break;
                case -3:
                    $this->respons_error("余额不足,请刷新页面");
                    break;
                default:;
                    $this->respons_error("支付失败");
                    break;
            }
        }
    }
    //订单发货
    public function Send(){
        global $uid,$timestamp;
        set_time_limit(7200);
        $order_no  = R("order_no","string");
        $orderinfo = M("@order")->get($order_no);
        if(isset($orderinfo['order_no']) && $orderinfo['pay_status'] == 1){
            if($orderinfo['send_status'] == 1){
                $this->respons_success("处理成功");
            }elseif($orderinfo['send_status'] == 9){//正在发货
                die("");
            }else{//未发货
                //首先更新发货状态为发货中
                M("order")->set_data(array(
                    "send_status"     =>9,
                    "send_dateline"  =>$timestamp,
                ))->update("send_status=0 AND order_no='{$order_no}'");

                if($orderinfo['order_item']){
                    $item_ids = array();
                    $info   = '';
                    $s_ink = $sms_link = 0;//发送邮箱短信标识
                    $is_sj   = 1;//判断升级续费
                    foreach($orderinfo['order_item'] as $item){
                        if($item['type'] == 0){//服务器升级续费
                            if($item['status'] == 0) {
                                $ret = M("@domain")->trans($item['goods_name'], $item['goods_no'], $item['num']);
                                if ($ret) {
                                    $item_ids[] = $item['item_id'];
                                }
                            }else{
                                $item_ids[] = $item['item_id'];
                            }
                        }elseif($item['type'] == 1){//短信套餐

                            if($item['status'] == 0) {
                                $sms_num =  $item['num'] * 100; //TODO:后台配置
                                $ret = M("@account")->update($uid,array("sms"=>"+{$sms_num}"),array("sms"=>"购买短信 {$sms_num}条"));
                                if ($ret) {
                                    $sms_link = 1;
                                    $item_ids[] = $item['item_id'];
                                }
                            }else{
                                $item_ids[] = $item['item_id'];
                            }
                        }
                        //加入系统通知变量设置
                        if ($item['goods_name']) {
                            $s_ink = 1;
                            $domain_service = M("@domain_service")->get_cache($item['goods_no']);
                            $domain = M("@domain")->get($item['goods_name']);
                            $info .= $item['goods_name']."[".$domain_service['name']."]"."[套餐期限:".date('Y-m-d H:i:s',$domain['service_expiry'])."]"."  ";
                        }
                        //判断升级续费
                        if ($item['buy_type'] == 2) {
                            $is_sj = 2;
                        }
                    }
                    ///更新$item status
                    if($item_ids){
                        //将订单子项目状态更改
                        M("order_item")->set_data(array(
                            "status" => 1,
                            "status_dateline"=>$timestamp,
                        ))->update("status = 0 AND item_id IN('".implode("','",$item_ids)."')");
                        //所有子项目状态更改后   订单状态更改
                        if(count($item_ids) === count($orderinfo['order_item'])){
                            M("order")->set_data(array(
                                "send_status"=>1,
                                "send_dateline" =>$timestamp,
                                "status"=>4,
                            ))->update("send_status=9 AND order_no='{$order_no}'");
                        }
                    }
                    if ($s_ink == 1) {//购买域名套餐，短信增值
                        if ($sms_link == 0) {//仅域名套餐通知
                            $s_title = ($is_sj == 1)?"购买域名套餐成功":"域名套餐续费成功";
                            $s_content = (($is_sj == 1)?"您成功购买了域名套餐":"您成功续费了域名套餐").$info;
                        }else{//域名套餐，短信增值双通知
                            $s_title = ($is_sj == 1)?"购买域名套餐,增值服务成功":"域名套餐续费,增值服务成功";
                            $s_content = (($is_sj == 1)?"您成功购买了域名套餐":"您成功续费了域名套餐").$info."和短信增值服务。";
                        }
                    }else{//仅购买短信增值通知
                        $s_title = "购买短信增值服务成功";
                        $s_content = "您成功购买了短信增值服务。";
                    }
                    //购买短信服务套餐邮件发送,普通发送
                    C("user")->send_meail_usual($this->userinfo['email'],$s_title,$s_content."八戒DNS祝您生活愉快！");
                    //购买短信服务套餐微信发送
                    C("user")->send_wx(array("type"=>"upgrade","content"=>$s_content),$uid);
                    //加入系统通知
                    $data = array(
                        "title"                => $s_title,
                        "content"          => $s_content,
                        "recieve_uid"     =>$uid,
                        "status"             =>0,
                        "dateline"          =>$timestamp
                    );
                    M("sys_information")->set_data($data)->add();
                }
            }
        }
        $this->respons_success("处理成功");
    }
    //域名注册订单
    public function SendRegDomain(){
        global $uid,$timestamp;
        set_time_limit(7200);
        //日志对象
        $tlog = new tLog();
        $order_no  = R("order_no","string");
        $orderinfo = M("@register_order")->get($order_no);
        if(isset($orderinfo['order_no']) && $orderinfo['pay_status'] == 1){
            App::uselib("tools.Zh2ZyCode");
            if($orderinfo['send_status'] == 1){//已经发货
                    $tlog->write("error",array("已经发货"));
                    die("");
            }elseif($orderinfo['send_status'] == 9){//正在发货
                     $tlog->write("error",array("正在发货"));
                    die("");
            }else{//未发货
                $tlog->write("error",array("准备发货:".time()));
                //首先更新发货状态为发货中
                M("register_order")->set_data(array(
                    "send_status"     =>9,
                    "send_dateline"  =>$timestamp,
                ))->update("send_status=0 AND order_no='{$order_no}'");

                //执行发货（耗时）
                if($orderinfo['order_item']){
                    $item_ids = $item_ids_err =array();
                    $info = $info_err = '';

                    $ns_group = M("@domain_ns_group")->get_cache_by_ns("free");
                    $ns_group_ns = isset($ns_group['ns'])?$ns_group['ns']:"";
                    $ns_group_ns_arr = $ns_group_ns?explode(';',$ns_group_ns):array();

                    foreach($orderinfo['order_item'] as $key => $item){

                        if($key == 0) {
                            $domain_reg_info = M("domain_register_info")->get_row("uid = '{$uid}' AND id = '{$item['tpl']}'");
                            if(isset($domain_reg_info['id'])){
                                $tmp_addr = explode(",",isset($domain_reg_info['area'])?$domain_reg_info['area']:"");
                                $tmp_cz = explode(",",isset($domain_reg_info['cz'])?$domain_reg_info['cz']:"");
                            }
                        }

                        if($item['status'] == 0) {
                            if ($item['type'] == 1) {//新买
                                //***************************************************加入会员中心注册域名列表 start***************************************
                                $add_register_domain = array(
                                    "domain"     => $item['domain'],
                                    "type"          => $item['domain_type'], //判断是0国际域名，1国内域名
                                    "reg_type" => $domain_reg_info['utype'],//域名注册类型，1个人用户2,企业用户
                                    "reg_time"  => $timestamp,
                                    "exp_time"   => strtotime("+1 year"),
                                    "dateline" => $timestamp,
                                    "uid"        => $uid,
                                    "ns"         =>$ns_group_ns, //域名ns
                                    "agent"   =>$item['agent'], //域名代理服务商
                                    "status"  => 0,
                                    "renew_type" => 1,
                                );
                                $domain_reg_id  = M("register_domain")->set_data($add_register_domain)->add();
                                //***************************************************加入会员中心注册域名列表 end***************************************
                                if($domain_reg_id){
                                    $data_d = array(
                                        "period"          =>$item['num'], //注册年数
                                        "uname1"        =>$domain_reg_info['aller_name_cn'], //注册人中文单位名称
                                        "uname2"        =>$domain_reg_info['aller_name'], //注册人英文单位名称
                                        "rname1"        =>$domain_reg_info['name_cn'], //注册人|单位负责人中文名称
                                        "rname2"        =>$domain_reg_info['name'], //注册人|单位负责人英文名称
                                        "ust"                => "CN", //注册人英文国家名称两个字母
                                        "uprov"           => str_replace(' ', '',($tmp_addr[0] == "重庆市")?"chong qing shi":Zh2PyCode::encode($tmp_addr[0],"all")), //注册人英文省份名称
                                        "ucity1"           => $tmp_addr[1],//注册人中文城市名称
                                        "ucity2"           => str_replace(' ', '',Zh2PyCode::encode($tmp_addr[1],"all")), //注册人英文城市名称
                                        "uaddr1"         => $domain_reg_info['addr_cn'], //注册人中文地址
                                        "uaddr2"         => $domain_reg_info['addr'], //注册人英文地址
                                        "uzip"              => $domain_reg_info['ub'],//注册人邮政编码
                                        "uteln"            => $domain_reg_info['mobile'],//注册人电话号码
                                        "ufaxc"            => $tmp_cz[0],//注册人传真国家码，可以不填默认为 86，长度不能大于 3 位
                                        "ufaxa"            => $tmp_cz[1],//注册人传真区号/.如果为手机可以不填，此项不可为 0
                                        "ufaxn"            => $tmp_cz[2],//注册人传真号码/.必须为数字 (区号+传真号码长度必须小于 12
                                        "uemail"          => $domain_reg_info['email'],//注册人 email 地址

                                        "aname1"        => $domain_reg_info['name_cn'],//管理联系人中文名称 [国内域名必填]
                                        "aname2"        => $domain_reg_info['name'],//管理联系人英文名称 [国际域名必填]
                                        "ateln"             => $domain_reg_info['mobile'],//管理联系人电话号码 [区号+电话号码长度必须小于 12]
                                        "aemail"          => $domain_reg_info['email'],//管理联系人电子邮件地址 [必须]
                                        "dns1"             => isset($ns_group_ns_arr[0])?$ns_group_ns_arr[0]:'',//域名主服务器名字 [如果不填默认为 ns.xinnetdns.com]
                                        "dns2"             => isset($ns_group_ns_arr[1])?$ns_group_ns_arr[1]:'',//域名辅服务器名字 [如果不填默认为 ns.xinnet.cn]
                                    );
                                    $d_r = SDKdomain::domain_register("Register",$item['domain'],$data_d);
                                    if ($d_r['status'] != 1) {//注册失败
                                        $item_ids_err[] = $item['item_id'];
                                        $info_err .= $item['domain'];
                                        if ($key == 0) {
                                            $s_title = "域名注册失败";
                                        }
                                        //返回账户金额
                                        $ret = M("@account")->update($uid,array("balance"=>"+{$item['amount']}"),array("balance"=>"域名注册失败，返回账户金额{$item['amount']}元"));
                                        //积分操作
                                        M("@account")->update($uid,array("point"=>"-".intval($item['amount'])),array("point"=>"注册域名失败,扣除积分".intval($item['amount'])));


                                        /***************************************************  删除注册失败的域名*************************************************************/
                                        M("register_domain")->del("id='{$domain_reg_id}'");

                                        tCache::write("domain_renew_reason",$d_r);

                                    }else{//注册成功
                                        $item_ids[] = $item['item_id'];
                                        $info .= $item['domain']."[域名时长:".$item['num']."年]"."  ";
                                        if ($key == 0) {
                                            $s_title = "域名注册成功";
                                        }
                                        //更改域名注册表状态
                                        M("register_domain")->set_data(array("status"=>1))->update("id='{$domain_reg_id}'");
                                        //域名注册信息加入注册域名附件表
                                        $attachinfo_arr = array(
                                            "did"                       => $domain_reg_id,
                                            "uid"                       => $uid,
                                            "aller_name_cn"      => $domain_reg_info['aller_name_cn'],
                                            "aller_name"            => $domain_reg_info['aller_name'],
                                            "name_cn"               => $domain_reg_info['name_cn'],
                                            "name"                    => $domain_reg_info['name'],
                                            "email"                     => $domain_reg_info['email'],
                                            "area"                      => $domain_reg_info['area'],
                                            "addr_cn"                => $domain_reg_info['addr_cn'],
                                            "addr"                      => $domain_reg_info['addr'],
                                            "ub"                         => $domain_reg_info['ub'],
                                            "mobile"                  => $domain_reg_info['mobile'],
                                            "cz"                          => $domain_reg_info['cz'],
                                            "m_name_cn"           => $domain_reg_info['m_name_cn'],
                                            "m_name"                => $domain_reg_info['m_name'],
                                            "m_email"                => $domain_reg_info['m_email'],
                                            "m_area"                  => $domain_reg_info['m_area'],
                                            "m_addr_cn"            => $domain_reg_info['m_addr_cn'],
                                            "m_addr"                 => $domain_reg_info['m_addr'],
                                            "m_ub"                     => $domain_reg_info['m_ub'],
                                            "m_mobile"             => $domain_reg_info['m_mobile'],
                                        );
                                        M("register_domain_attachinfo")->set_data($attachinfo_arr)->add();
                                        //***************************************************加入会员中心域名列表 api 添加域名 start***************************************
                                        SDK::web_api("/Domain/AddByUid",array("domain"=>$item['domain']));
                                        //域名添加日志
                                        M("@register_domain")->log("域名注册","域名名称：{$item['domain']},联系人:{$attachinfo_arr['aller_name_cn']},邮箱:{$attachinfo_arr['m_email']}",array('domain_id'=>$domain_reg_id,'domain'=>$item['domain']));
                                        //更改用户注册域名数
                                        M("@account")->update($uid,array("register_domains"=>"+1"),array("register_domains"=>"注册域名,增加一个"));
                                        //***************************************************加入会员中心域名列表 end***************************************
                                        
                                        
                                    }
                                }
                            }elseif ($item['type'] == 2) {//续费
                                //域名到期时间
                                $re_exp_time = M("register_domain")->get_one("uid = '{$uid}' AND domain = '{$item['domain']}'","exp_time");
                                $renew_data = array(
                                    'begindate' => date("Y-m-d",$re_exp_time),
                                    "period" => $item['num'],
                                );
                                $renew_res = SDKdomain::domain_renew("DomainRenew",$item['domain'],$renew_data);
                                if ($renew_res['status'] != 1) {//续费失败
                                    $item_ids_err[] = $item['item_id'];
                                    $info_err .= $item['domain'];
                                    if ($key == 0) {
                                        $s_title = "域名续费失败";
                                    }
                                    //返回账户金额
                                    $reret =  M("@account")->update($uid,array("balance"=>"+{$item['amount']}"),array("balance"=>"域名续费失败，返回账户金额{$item['amount']}元"));
                                    //积分操作
                                    M("@account")->update($uid,array("point"=>"-".intval($item['amount'])),array("point"=>"域名续费失败,扣除积分".intval($item['amount'])));

                                }else {//续费成功
                                    $item_ids[] = $item['item_id'];
                                    $info .= $item['domain']."[续费时长:".$item['num']."年]"."  ";
                                    if ($key == 0) {
                                        $s_title = "域名续费成功";
                                    }
                                    //更新数据表到期时间
                                    M("register_domain")->set_data(array("exp_time"=>strtotime("+{$item['num']} years",$re_exp_time),"renew_type"=>2,"renew_dateline"=>$timestamp))->update("uid = '{$uid}' AND domain = '{$item['domain']}'");
                                    //域名续费日志
                                    $d_domainid = M("register_domain")->get_one("uid = '{$uid}' AND domain = '{$item['domain']}'","id");
                                    M("@register_domain")->log("域名续费","域名名称：{$item['domain']},续费年限:{$item['num']}年",array('domain_id'=>$d_domainid,'domain'=>$item['domain']));
                                }
                            }elseif ($item['type'] == 3) {//域名转入
                                //域名到期时间
                                $re_exp_time = M("register_domain_transfer")->get_one("uid = '{$uid}' AND domain = '{$item['domain']}'","exptime");
                                $renew_data = array(
                                    'begindate' => $re_exp_time,
                                    "period" => 1,
                                );
                                $renew_res = SDKdomain::domain_renew("DomainRenew",$item['domain'],$renew_data);
                                if ($renew_res['status'] != 1) {//续费失败
                                    $item_ids_err[] = $item['item_id'];
                                    $info_err .= $item['domain'];
                                    if ($key == 0) {
                                        $s_title = "域名转入续费失败";
                                    }
                                    //返回账户金额
                                    $reret =  M("@account")->update($uid,array("balance"=>"+{$item['amount']}"),array("balance"=>"域名转入续费失败，返回账户金额{$item['amount']}元"));
                                    //积分操作
                                    M("@account")->update($uid,array("point"=>"-".intval($item['amount'])),array("point"=>"域名转入续费失败,扣除积分".intval($item['amount'])));

                                    //更新数据表到期时间，域名转入表状态 续费失败，已退款
                                    M("register_domain_transfer")->set_data(array("status"=>5))->update("uid = '{$uid}' AND domain = '{$item['domain']}'");
                                    tCache::write("domain_renew_reason",$renew_res);

                                }else {//续费成功
                                    $item_ids[] = $item['item_id'];
                                    $info .= $item['domain']."[续费时长:".$item['num']."年]"."  ";
                                    if ($key == 0) {
                                        $s_title = "域名转入续费成功";
                                    }
                                    //更新数据表到期时间，域名转入表状态
                                    M("register_domain_transfer")->set_data(array("exptime"=>date("Y-m-d",strtotime("+1 years",strtotime($re_exp_time))),"status"=>1))->update("uid = '{$uid}' AND domain = '{$item['domain']}'");
                                }
                            }
                            usleep(50000);
                        }
                    }
                    ///更新$item status
                    if (count($item_ids) > 0) { //成功
                        //将成功的订单子项目状态更改
                        M("register_order_item")->set_data(array(
                            "status" => 1,
                            "status_dateline"=>$timestamp,
                        ))->update("status = 0 AND item_id IN('".implode("','",$item_ids)."')");
                        //所有子项目状态更改后   订单状态更改

                        //************************************************************通知类 start**********************************
                        $s_content = "您".$s_title.",".$info;
                        //购买短信服务套餐邮件发送
                        C("user")->send_meail_usual($this->userinfo['email'],$s_title,$s_content."八戒DNS祝您生活愉快");
                        //购买短信服务套餐微信发送
                        C("user")->send_wx(array("type"=>"upgrade","content"=>$s_content),$uid);
                        //加入系统通知
                        $t_data_1 = array(
                            "title"                => $s_title,
                            "content"          => $s_content,
                            "recieve_uid"     =>$uid,
                            "status"             =>0,
                            "dateline"          =>$timestamp
                        );
                        M("sys_information")->set_data($t_data_1)->add();
                        //************************************************************通知类 end**********************************
                    }
                    if (count($item_ids_err) > 0) { //失败

                        //将订失败的订单子项目状态更改
                        M("register_order_item")->set_data(array(
                            "status" => 2,
                            "status_dateline"=>$timestamp,
                        ))->update("status = 0 AND item_id IN('".implode("','",$item_ids_err)."')");

                        $s_content = "失败域名".$info_err;
                        //购买短信服务套餐邮件发送
                        C("user")->send_meail_usual($this->userinfo['email'],$s_title,$s_content.",您的账户金额已返还，请查看，八戒DNS祝您生活愉快");
                        //购买短信服务套餐微信发送
                        C("user")->send_wx(array("type"=>"upgrade","content"=>$s_content),$uid);
                        //加入系统通知
                        $t_data_2 = array(
                            "title"                => $s_title,
                            "content"          => $s_content,
                            "recieve_uid"     =>$uid,
                            "status"             =>0,
                            "dateline"          =>$timestamp
                        );
                        M("sys_information")->set_data($t_data_2)->add();
                        //************************************************************通知类 end**********************************
                    }
                    //处理完订单子项目后更新订单状态
                    if(count($item_ids) === count($orderinfo['order_item'])){
                        M("register_order")->set_data(array(
                            "send_status"=>1,
                            "send_dateline" =>$timestamp,
                            "status"=>4
                        ))->update("send_status=9 AND order_no='{$order_no}'");
                    }elseif(count($item_ids_err) === count($orderinfo['order_item'])){
                        M("register_order")->set_data(array(
                            "send_status"=>2,
                            "send_dateline" =>$timestamp,
                            "status"=>5,
                            "pay_status"=>2
                        ))->update("send_status=9 AND order_no='{$order_no}'");
                    }elseif(count($item_ids_err) >0 && count($item_ids_err) < count($orderinfo['order_item'])){
                        M("register_order")->set_data(array(
                            "send_status"=>3,
                            "send_dateline" =>$timestamp,
                            "status"=>6,
                            "pay_status"=>3
                        ))->update("send_status=9 AND order_no='{$order_no}'");
                    }
                }

                $tlog->write("error",array("发货完成:".time()));
            }
        }
    }
    //取消订单
    public function Cancel(){
        global $uid;
        $order_no              = R("order_no","string");
        if (empty($order_no)) {
            $this->respons_error("请选择要取消的订单");
        }
        $order_row = M("order")->get_row("order_no='{$order_no}' AND uid={$uid}");

        if (!isset($order_row['order_no'])) {
            $this->respons_error("订单不存在");
        }
        if($order_row['status']>2){
            $this->respons_error("订单已支付,不能取消");
        }
        $rst = M("order")->set_data(array("status"=>0))->update("order_no ='{$order_no}' AND uid={$uid}");
        if ($rst) {
            //如果优惠券存在，优惠券状态改为未使用
            $coupon_row = M("coupon")->get_row("uid = '{$uid}' AND order_no = '{$order_no}'");
            if (isset($coupon_row['id'])) {
                M("coupon")->set_data(array("status"=>0,"use_dateline"=>0,"order_no"=>0))->update("order_no ='{$order_no}' AND uid={$uid}");
            }
            $this->respons_success("取消成功");
        } else {
            $this->respons_error("取消失败");
        }
    }
    //删除订单
    public function Del(){
        global $uid;
        $order_no              = R("order_no","string");
        if (empty($order_no)) {
            $this->respons_error("请选择要删除的订单");
        }
        $order_row = M("order")->get_row("order_no={$order_no} AND uid={$uid}");
        if (!isset($order_row['order_no'])) {
            $this->respons_error("订单不存在");
        }
        if(!in_array($order_row['status'],array(0,1,2,5))){
            $this->respons_error("订单已支付但是未完成,不能删除");
        }
        $rst = M("order")->set_data(array("indel"=>1))->update("order_no ='{$order_no}' and uid={$uid}");
        if ($rst) {
            $this->respons_success("删除成功");
        } else {
            $this->respons_error("删除失败");
        }
    }
}