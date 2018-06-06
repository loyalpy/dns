<?php

class import_register_domain extends tController
{
    //导入域名,域名记录程序
    public function ImportDnsRecords()
    {
        App::uselib("tools.idna_convert");
        set_time_limit(7200);
        ob_end_clean();
        ob_implicit_flush(true);
        echo str_repeat(' ' ,1000);

        $c = array();
        $c['pagesize'] = 500;
        $c['page']     = 1;
        $Q = Q("199cndomaindns");
        $result = $Q->get_list($c);
        foreach($result['list'] as $key => $v){
            if (count($v) > 0) {
                $this->ImportStart($v);
            }
        }
        //处理后面的页码
        for($page=2;$page<=$result['totalpage'];$page++){
            $c['page'] = $page;
            $result = $Q->get_list($c);
            foreach($result['list'] as $key => $v){
                if (count($v) > 0) {
                    $this->ImportStart($v);
                }
            }
        }
    }
    //开始执行导入程序
    public function ImportStart($records){
        global $timestamp;

        //根据域名id读取域名和用户名
        $domain_reg_row = M("199cndomainreg")->get_row("id = '{$records['DomainId']}'");
        if (isset($domain_reg_row['domain'])) {
            //判断域名ns在我们这边导入
            $register_domain_row = M("register_domain")->get_row("domain = '{$domain_reg_row['domain']}'");
            if (isset($register_domain_row['ns'])) {
//                if ($register_domain_row['ns'] == "ns1.dns002.com;ns2.dns002.com") {
                if (strpos($register_domain_row['ns'],"ns1.dns002.com") !== false) {
                    //添加域名到域名表:wo_domain
                    $domain = strtolower($domain_reg_row['domain']);
                    $domain_cn = $domain;
                    $is_cn     = 0;
                    //判断域名是否合法
                    if(tValidate::is_domain($domain)){
                        //中英文转换
                        if(tValidate::is_cn($domain)){
                            $is_cn = 1;
                            $idna_convert_obj = new idna_convert();
                            $domain = $idna_convert_obj->encode($domain);
                            unset($idna_convert_obj);
                        }else{
                            $domain_cn = "";
                        }
                        //域名是否已经存在
                        $domain_row = M("domain")->get_row("domain = '{$domain}'");
                        $domain_id = 0;
                        if(!isset($domain_row['domain_id'])){
                            //判断域名是否在八戒DNS官网注册
                            $is_our_reg = 0;
                            $is_our_reg_time = strtotime('-2 months',$timestamp);
                            if (M("register_domain")->get_one("domain = '{$domain}' AND exp_time > $is_our_reg_time","id")) {
                                $is_our_reg = 1;
                            }
                            //插入数据到域名表
                            $domain_data = array(
                                "domain"     => $domain,
                                "is_cn"      => $is_cn,
                                "domain_cn"  => $domain_cn,
                                "dateline"   => $timestamp,
                                "lastupdate" => $timestamp,
                                "uid"        => $register_domain_row['uid'],
                                "service_group" => "free",
                                "ns_group"      => "free",
                                "is_our_reg"		=>$is_our_reg,
                                "bz"            => "",
                                "sysbz"         => "",
                                "qps"           => 10000,
                                "ttl"           => 600,
                                "is_199cn" =>1
                            );
                            $domain_id = M("domain")->set_data($domain_data)->add();
                            if($domain_id){
                                echo "{$domain}"."&nbsp;";
                                echo "<span style='color: green'>[ok]</span>"."&nbsp;&nbsp;";
                                echo "<span style='color: green'>add domain is ok . add id is {$domain_id}  . and </span>"."&nbsp;&nbsp;";
                            }else{
                                echo "{$domain}"."&nbsp;";
                                echo "<span style='color: red'>[failed]</span>"."&nbsp;&nbsp;";
                                echo "<span style='color: red'>add is failed . failed domain is {$domain} . and </span>"."&nbsp;&nbsp;";
                            }
                        }
                        //插入数据到域名解析记录表
                        $domain_id = empty($domain_id)?$domain_row['domain_id']:$domain_id;
                        $acl = "any";
                        $RRname = empty($records['Name'])?"@":$records['Name'];
                        $RRtype = '';
                        $RRvalue = $records['Tel'];
                        $RRmx = 0;
                        $RRttl = $records['TTL'];
                        switch ($records['Type']) {
                            case "1":
                                $RRtype = "A";
                                if ($records['Tel'] != $records['Cnc']) {
                                    $domain_records_data1 = array(
                                        "domain_id"=>$domain_id,
                                        "domain" =>$domain,
                                        "acl"=>"cnc",
                                        "acltype"=>"DI",
                                        "RRname"=>$RRname,
                                        "RRtype"=>$RRtype,
                                        "RRvalue"=>$records['Cnc'],
                                        "RRmx"=>0,
                                        "RRttl"=>$RRttl,
                                        "status"=>1,
                                        "inlock"=>0,
                                        "is_199cn"=>1,
                                    );
                                    //判断是否已经存在
                                    if (!M("domain_record")->get_one("domain_id = '{$domain_id}' AND domain = '{$domain}' AND acl = 'cnc' AND RRname = '{$RRname}' AND RRtype = '{$RRtype}' AND RRvalue = '{$records['Cnc']}' AND RRmx = '0' AND RRttl = '{$RRttl}' AND is_199cn = 1","count(record_id)")) {
                                        $domain_records_id = M("domain_record")->set_data($domain_records_data1)->add();
                                        if($domain_records_id){

                                            //记录值递增+1
                                            $records_num = M("domain")->get_row("domain_id='{$domain_id}'");
                                            M('domain')->set_data(array("records"=>$records_num['records']+1,"lastupdate"=>$timestamp))->update("domain_id='{$domain_id}'");

                                            echo "{$domain}"."&nbsp;";
                                            echo "<span style='color: green'>[ok]</span>"."&nbsp;&nbsp;";
                                            echo "<span style='color: green'>add domain record is ok . record id is {$domain_records_id} .</span>"."<br/>";
                                        }else{
                                            echo "{$domain}"."&nbsp;";
                                            echo "<span style='color: red'>[failed]</span>"."&nbsp;&nbsp;";
                                            echo "<span style='color: red'>add domain record is failed . failed domain is {$domain} .</span>"."<br/>";
                                        }
                                    }
                                }
                                break;
                            case "2":
                                $RRtype = "CNAME";
                                break;
                            case "3":
                                $RRtype = "MX";
                                $RRmx = $records['Cnc'];
                                break;
                            case "4":
                                if ($records['TTL'] == 0) {
                                    $RRtype = "URLY";
                                }else{
                                    $RRtype = "URLN";
                                }
                                $RRttl = 0;
                                $RRvalue = strtolower($records['Cnc']);
                                break;
                            default:
                                ;
                        }
                        $domain_records_data2 = array(
                            "domain_id"=>$domain_id,
                            "domain" =>$domain,
                            "acl"=>$acl,
                            "acltype"=>"DI",
                            "RRname"=>$RRname,
                            "RRtype"=>$RRtype,
                            "RRvalue"=>$RRvalue,
                            "RRmx"=>$RRmx,
                            "RRttl"=>$RRttl,
                            "status"=>1,
                            "inlock"=>0,
                            "is_199cn"=>1,
                        );
                        //判断是否已经存在
                        if (!M("domain_record")->get_one("domain_id = '{$domain_id}' AND domain = '{$domain}' AND acl = '{$acl}' AND RRname = '{$RRname}' AND RRtype = '{$RRtype}' AND RRvalue = '{$RRvalue}' AND RRmx = '{$RRmx}' AND RRttl = '{$RRttl}' AND is_199cn = 1","count(record_id)")) {
                            $domain_records_id = M("domain_record")->set_data($domain_records_data2)->add();
                            if($domain_records_id){

                                //记录值递增+1
                                $records_num = M("domain")->get_row("domain_id='{$domain_id}'");

                                M('domain')->set_data(array("records"=>$records_num['records']+1,"lastupdate"=>$timestamp))->update("domain_id='{$domain_id}'");

                                echo "{$domain}"."&nbsp;";
                                echo "<span style='color: green'>[ok]</span>"."&nbsp;&nbsp;";
                                echo "<span style='color: green'>add domain record is ok . record id is {$domain_records_id} .</span>"."<br/>";
                            }else{
                                echo "{$domain}"."&nbsp;";
                                echo "<span style='color: red'>[failed]</span>"."&nbsp;&nbsp;";
                                echo "<span style='color: red'>add domain record is failed . failed domain is {$domain} .</span>"."<br/>";
                            }
                        }
                    }
                }else{
                    echo "<span style='color: orange'>[records id is "."{$records['Id']}]"." domai ns is not our;</span>"."<br/>";
                }
            }else{
                echo "<span style='color: orange'>[records id is "."{$records['Id']}]"." register domain is not exist;</span>"."<br/>";
            }
        }else{
            echo "<span style='color: orange'>[records id is "."{$records['Id']}]"." only have records and no domain;</span>"."<br/>";
        }

        ob_flush();
        flush();
        usleep(10*1000);
    }
    //批量更改NS
    public function BatchEditNs(){
        set_time_limit(7200);
        ob_end_clean();
        ob_implicit_flush(true);
        echo str_repeat(' ' ,1000);

        $c = array();
        $c['pagesize'] = 500;
        $c['page']     = 1;
        $Q = Q("register_domain");
        $result = $Q->get_list($c);
        foreach($result['list'] as $key => $v){
            if (count($v) > 0) {
                $this->ImportNsStart($v);
            }
        }
        //处理后面的页码
        for($page=2;$page<=$result['totalpage'];$page++){
            $c['page'] = $page;
            $result = $Q->get_list($c);
            foreach($result['list'] as $key => $v){
                if (count($v) > 0) {
                    $this->ImportNsStart($v);
                }
            }
        }
    }
    //开始执行批量更改NS
    public function ImportNsStart($domain){

        if (strpos($domain['ns'],"ns1.dns002.com") !== false) {
            if (!in_array($domain['domain'],array("idc002.com","199cn.com"))) {
                $ns1 = "g1ns1.8jdns.net";
                $ns2 = "g1ns2.8jdns.net";
                $data = array(
                    "dns1" =>$ns1,
                    "dns2" =>$ns2,
                );
                $res = SDKdomain::modify_dns("ModDns",$domain['domain'],$data);
                if ($res['status'] == 1) {
                    M("register_domain")->set_data(array('ns'=>$ns1.";".$ns2))->update("domain = '{$domain['domain']}'");
                    echo "{$domain['domain']}"."&nbsp;";
                    echo "<span style='color: green'>[ok]</span>"."&nbsp;&nbsp;";
                    echo "<span style='color: green'>update domain ns is ok . </span>"."<br/>";
                }else{
                    $t_str = isset($res['list']['err'])?$res['list']['err']:"sys error ！";
                    echo "{$domain['domain']}"."&nbsp;";
                    echo "<span style='color: red'>[failed]</span>"."&nbsp;&nbsp;";
                    echo "<span style='color: red'>update domain ns is failed . failed reason is {$t_str} .</span>"."<br/>";
                }
            }
        }

        ob_flush();
        flush();
        usleep(10*1000);
    }
    //域名列表导出
    public function register_domain_export(){
        M("@register_domain")->register_domain_excel();
    }
}

?>