<!doctype html>
<html class="no-js">
<head>
<!--[if lt IE 10]>
<![endif]-->
<!--[if lt IE 7]>
<script>location.href="/ie.html"</script>
<![endif]-->
<!-- page head -->

<meta charset="UTF-8">
<meta http-equiv=”X-UA-Compatible” content=”IE=Edge,chrome=1″ >
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">

<link rel="stylesheet" href="<?php echo U("static/javascript/amazeui/css/amazeui.min.css");?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_uc.css";?>"></head>
<body>
<!-- topbar -->
<div class="topbar">
  <div class="aps">
    <div class="top-left-nav">
    <ul>
    <li>
    <a href="<?php echo U("home@/");?>" class="logo"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>" alt="八戒DNS" /></a>
    </li>
    <li>
      <a href="javascript:void(0)" id="navSwitch" title="帮助与支持"><i class="am-icon-bars"></i> &nbsp;</a>
    </li>
      <?php $tmplist_d =  M("domain")->query("uid = $uid","","lastupdate DESC",10)?>
      <?php if(count($tmplist_d) > 0){?>
      <li class="domain-li-d">
        <a href="javascript:void (0)" class="s">我的域名 <cite class="am-icon-caret-down"></cite></a>
        <div class="domain-li-dup">
          <table cellpadding="0" cellspacing="0" border="0" class="am-table am-table-hover">
            <thead>
            <tr>
              <th>域名</th>
              <th>是否指向</th>
              <th>统计</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($tmplist_d as $key => $item){?>
            <tr>
              <td><a href="<?php echo U("/domains/dns/");?><?php echo isset($item['domain'])?$item['domain']:"";?>" class="tr-td-a"><?php echo isset($item['domain'])?$item['domain']:"";?> <span style="color: #ccc;font-weight: 400;"><?php if($item['records'] > 0){?>(<?php echo isset($item['records'])?$item['records']:"";?>)<?php }?></span></a></td>
              <td><?php if($item['inns'] != 1){?><cite class="am-icon-exclamation-circle am-text-warning" title="域名DNS未指向我们"></cite><?php }else{?><cite class="am-icon-check-circle am-text-success" title="域名DNS已指向我们"></cite><?php }?></td>
              <td><a href="<?php echo U("/records/records_count?domain=");?><?php echo isset($item['domain'])?$item['domain']:"";?>"><cite  class="am-icon-line-chart am-icon-line-chart1"></cite></a></td>
            </tr>
            <?php }?>
            </tbody>
          </table>
          <p><a href="<?php echo U("/domains/index");?>">查看全部域名&gt;&gt;</a></p>
        </div>
      </li>
      <?php }?>
    </ul>
    </div>
    <div class="top-domain-search">
    <div class="in-search">
      <form  method="get" action="<?php echo U("domain@/domain/lists");?>" target="_blank" >
      <div class="domain-inp">
      <input type="text" class="search" name="reg_domain" value="" autocomplete="off" placeholder="" />
      </div>
      <div class="btn-buy"><button type="submit">查域名</button></div>
      </form>
    </div>
    </div>
    <div class="top-right-nav">
    <ul>
      <li>
      <a href="<?php echo U("/ucenter/profile_msg");?>" style="padding:0 10px;"><span class="am-icon-envelope-o"></span>
      <span class="am-badge am-badge-warning am-round"><?php echo M("sys_information")->get_one("recieve_uid=$uid AND status=0","count(*)");?></span></a>
      </li>
      <li>
      <a href="<?php echo U("/order/cart_shopping");?>" style="padding:0 10px;"><span class="am-icon-shopping-cart"></span>
      <span class="am-badge am-badge-warning am-round domain-parse-tips"><?php echo M("cart")->get_one("uid=$uid AND status=0 AND indel=0","count(*)");?></span>
      </a>
      </li>
      <li class="am-dropdown setting" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-user"></span> <?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?> <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="<?php if($this->userinfo['utype'] == 1){?><?php echo U("account@/ucenter/profile_basic");?><?php }else{?><?php echo U("account@/ucenter/profile_basic_com");?><?php }?>"><span class="am-icon-cog"></span> 资料</a></li>
          <li><a href="<?php echo U("account@/ucenter/safety_center");?>"><span class="am-icon-shield"></span> 安全</a></li>
          <?php if($this->userinfo['urole'] > 0){?>
          <li><a target="_blank" href="<?php echo U("admin@/");?>"><span class="am-icon-th-large"></span> 管理</a></li>
          <?php }?>
          <li><a href="<?php echo U("account@/login/logout");?>"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>
    </ul>
    </div>
  </div>
</div>
<!-- end topbar -->
<div class="mainnav <?php if(in_array($inc,array("order","finance","ucenter"))){?>mainnav-<?php }?>" id="MainNav">
  <ul class="main-ul">
  <li><a href="<?php echo U("/domains/index");?>" <?php if(in_array($inc,array("domains","records"))){?>class="cur"<?php }?>>
  <i class="am-icon-globe"></i> &nbsp;域名解析</a></li>
  <li><a href="<?php echo U("/monitor/monitor");?>" <?php if(in_array($inc,array("monitor"))){?>class="cur"<?php }?>><i class="am-icon-desktop "></i> &nbsp;域名监控</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("domain@/ucenter/index");?>"><i class="am-icon-wordpress"></i> &nbsp;域名注册</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("account@/finance/index");?>" <?php if(in_array($inc,array("finance"))){?>class="cur"<?php }?>><i class="am-icon-user"></i> &nbsp;账户</a></li>
  <li><a href="<?php echo U("account@/order/order");?>" <?php if(in_array($inc,array("order"))){?>class="cur"<?php }?>><i class="am-icon-reorder"></i> &nbsp;订单</a></li>
  <li><a href="<?php echo U("account@/ucenter/safety_center");?>" <?php if(in_array($inc,array("ucenter"))){?>class="cur"<?php }?>><i class="am-icon-gear"></i> &nbsp;设置</a></li>
  </ul>
</div>
<div class="combox">
    <div class="records-nav">
        <ul>
            <li class="domain">
                <a href="<?php echo U("/domains/mylist");?>"><i class="am-icon-globe"></i></a>
                <span><?php if($domain_cn){?><?php echo isset($domain_cn)?$domain_cn:"";?><?php }else{?><?php echo isset($domain)?$domain:"";?><?php }?></span>
            </li>
            <li><a href="<?php echo U("/records/records_setline?domain=$domain");?>">自定义线路</a></li>
            <li><a href="<?php echo U("/records/records_set?domain=$domain");?>">域名设置</a></li>
            <li class="cur"><a href="<?php echo U("/records/records_count?domain=$domain");?>">解析量统计</a></li>
            <li><a href="<?php echo U("/domains/dns/");?><?php echo isset($domain)?$domain:"";?>">记录设置</a></li>
        </ul>
    </div>
    <div class="dis20"></div>
    <div class="records-content">
        <div class="img-header" style="font-size: 14px;">
            <div class="serchtime" style="position: relative;left:30px;">
                <span>查询时间</span>&nbsp;&nbsp;
                <select style="width: 150px;" id="Mtimetype"><option value="day" selected="selected">今天</option><option value="week">最近一周</option><option value="month">最近一个月</option></select>
            </div>
            <div class="dis10"></div>
            <div class="serchname" style="position: relative;left:30px;">
                <span>查看域名</span>&nbsp;&nbsp;
                <select style="width: 150px;" id="MRRname">
                    <option value="0" selected="selected">所有</option>
                    <?php foreach($RRname as $key => $item){?>
                    <?php $kk = str_replace('#','.',$key);?>
                    <option value="<?php echo isset($key)?$key:"";?>"><?php echo isset($kk)?$kk:"";?>.<?php echo isset($domain)?$domain:"";?></option>
                    <?php }?>
                </select>
            </div>
            <div class="dis20"></div>
        </div>
        <div class="countimage-box" id="user_reg_month" style="width: 1080px; height:320px; margin: 0 auto"></div>
    </div>
    </div>
</div>
<div class="floatdiv" id="Dfloatdiv" style="display:none;">
  <div class="item" style="border-top: solid 1px #ddd;"><cite class="fedit" title="扫码关注"></cite>
    <div class="in" style="width: 0px;height: 100px; overflow: hidden;" _w="100"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/weixin.jpg";?>" width="100px" height="100px"/></div>
  </div>
  <div class="line"></div>
  <div class="item"><cite class="fqq" title="联系客服"></cite>
    <div class="in" style="width:0px;" _w="85"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo isset($site['qq'])?$site['qq']:"";?>&site=qq&menu=yes">联系客服</a></div>
  </div>
  <div class="line"></div>
  <div class="item" style="border-bottom: solid 1px #ddd;"><cite class="ftel" title="联系电话"></cite>
    <div class="in" style="width:0px;" _w="115"><a href="javascript:void(0);"><?php echo isset($site['tel'])?$site['tel']:"";?></a></div>
  </div>
</div>
<div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
    <a href="#top" title="">
          <i class="am-gotop-icon am-icon-chevron-up"></i>
    </a>
</div>
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<!--[if lte IE 8 ]>
<script src="<?php echo U("static/javascript/amazeui/js/modernizr.js");?>"></script>
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.ie8polyfill.min.js");?>"></script>
<![endif]-->
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.min.js");?>"></script>
<script src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<script language="javascript">
  var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>
$(function(){
  $("#navSwitch").bind("click",function(){
    if($("#MainNav").is(':hidden')){
        $("body").css({"padding-left":"198px"});
        $("#MainNav").show();
    }else{
        $("body").css({"padding-left":"10px"});
        $("#MainNav").hide();
    }
  });
  //鼠标经过显示多个域名
  $("li.domain-li-d").find("a.s,.domain-li-dup").hover(function(){
    $(this).addClass("hover");
    $(".domain-li-d").find(".domain-li-dup").show();
  }, function(){
    $(this).removeClass("hover");
    $(".domain-li-d").find(".domain-li-dup").hide();
  });
})
</script>
<script type="text/javascript">
  $(function(){
    $("#Dfloatdiv").fadeIn();
    $("#Dfloatdiv").find(".item").hover(function(){
            var sIn_obj = $(this).find(".in");
            $(this).addClass("item-over");
            sIn_obj.stop(true,false).animate({"width":sIn_obj.attr("_w")},300);
          },function(){
            $(this).removeClass("item-over");
            $(this).find(".in").stop(true,false).animate({"width":0},300);
          }
    );
  })

</script>
<script language="javascript" src="<?php echo U("static@/javascript/highcharts/highcharts.js");?>"></script>
<script type="text/javascript">
    var show_map = function(RRname,timetype){
        var RRname =  (RRname == "@")?"_at_":RRname;    // 特殊标记
        var timetype = typeof timetype == "undefined"?"day":timetype;

        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/api/Domain.Maps");?>",
            data:{timetype:timetype,RRname:RRname,domain:"<?php echo isset($domain)?$domain:"";?>"},
            success:function(res){
                $.ui.close_loading();
                if(res.status == 1){

                    if (RRname == 0) {
                        RRname = "";
                    }else if (RRname == "_at_") {
                        RRname = "@.";
                    }else{
                        RRname = RRname+".";
                    }

                    if (timetype == "week") {
                        var day_t1 = "周";
                        var day_t2 = "最近一周";
                        var day_t3 = "上周";
                    }else if (timetype == "month") {
                        day_t1 = "月";
                        day_t2 = "最近一月";
                        day_t3 = "上月";
                    }else{
                        day_t1 = "日";
                        day_t2 = "今日";
                        day_t3 = "昨日";
                    }

                    var total1 = 0;
                    var g1ns1 = new Array();
                    var g1ns2 = new Array();
                    for(var x in res.data['domain1']){
                        g1ns1.push(x);
                        g1ns2.push(res.data['domain1'][x]);
                        total1 = total1+ parseInt(res.data['domain1'][x]);
                    }

                    var total2 = 0;
                    var g1ns3 = new Array();
                    var g1ns4 = new Array();
                    for(var x in res.data['domain2']){
                        g1ns3.push(x);
                        if (timetype == "day") {
                            g1ns4.push(res.data['domain2'][x]);
                        }
                        total2 = total2+ parseInt(res.data['domain2'][x]);
                    }

                    $("#user_reg_month").highcharts({
                        chart: {
                            type: "line",
                            marginRight: 130,
                            marginBottom: 25
                        },
                        title: {
                            text: "域名"+RRname+"<?php echo isset($domain)?$domain:"";?>"+day_t1+"统计图",
                            x: -20 //center
                        },
                        subtitle: {
                            text: day_t2+"请求数:"+total1+"人次 | "+day_t3+"请求数:"+total2+"人次",
                            x: -20
                        },
                        xAxis: {
                            categories: g1ns1,
                            tickInterval:3
                        },
                        yAxis: {
                            title: {
                                text: "请求数"
                            },
                            plotLines: [{
                                value: 0,
                                width: 1,
                                color: "#808080"
                            }]
                        },
                        tooltip: {
                            valueSuffix: "次",
                            shadow:false,
                            shared: true,
                            crosshairs: true,
                            borderColor: "#bbbbbb",
                            borderRadius: 0,
                            borderWidth: 1
                        },
                        legend: {
                            layout: "vertical",
                            align: "right",
                            verticalAlign: "top",
                            x: -10,
                            y: 100,
                            borderWidth: 0
                        },
                        series: [{
                            name: day_t2+"请求数",
                            data: g1ns2,
                            color:"#48BEF4",
                            index:1,
                        },{
                            name: day_t3+"请求数",
                            data: g1ns4,
                            color:"#90ED7D",
                            marker: {
                                symbol: "diamond"
                            }
                        }
                        ]
                    });
                }else{
                    $("#user_reg_month").html("<p style='padding:60px 0 0 0;'>当前无可用图像</p>");
                }
            }
        });
    }
    show_map("");
    $("#Mtimetype").bind("change",function(){
        var timetype = $(this).val();
        show_map("",timetype);
    });
    $("#MRRname").bind("change",function(){
        var RRname = $(this).val();
        var timetype = $("#Mtimetype").val();
        show_map(RRname,timetype);
    })
</script>
</body>
</html>