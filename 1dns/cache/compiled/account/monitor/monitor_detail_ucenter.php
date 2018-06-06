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
<div class="am-uc-left">
    <div class="leftnav" id="Leftnav">
        <ul>
            <li><a href="<?php echo U("/monitor/monitor");?>" <?php if($do=='detail'){?>class="cur"<?php }?>>实时监控&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/monitor/warning");?>">报警信息&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/monitor/monitor_set");?>" <?php if($do=='set'){?>class="cur"<?php }?>>监控设置&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/monitor/monitor_option_domain");?>">添加监控&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right">
    <div class="records-nav">
        <ul>
            <li class="domain"><i class="am-icon-desktop am-font-black"></i>&nbsp;&nbsp;<span><?php echo isset($RRname)?$RRname:"";?>.<?php echo isset($domain)?$domain:"";?></span>
                    <?php if(($monitor['monitor_type'] == 2)){?>
                    <span style="font-size:12px;" class="am-badge am-round am-badge-warning f12" title="SOCKET监控">S</span>
                    <?php }elseif(($monitor['monitor_type'] == 3)){?>
                    <span style="font-size:12px;" class="am-badge am-round am-badge-warning f12" title="PING监控">P</span>
                    <?php }else{?>
                    <?php }?>
            </li>
            <li <?php if($do=='set'){?>class="cur"<?php }?>><a href="<?php echo U("/monitor/monitor_detail?do=set&domain=$domain");?>&RRname=<?php echo isset($RRname)?$RRname:"";?>&record_id=<?php echo isset($record_id)?$record_id:"";?>" class="nav-set">监控设置</a></li>
            <li <?php if($do=='detail'){?>class="cur"<?php }?>><a href="<?php echo U("/monitor/monitor_detail?do=detail&domain=$domain");?>&RRname=<?php echo isset($RRname)?$RRname:"";?>&record_id=<?php echo isset($record_id)?$record_id:"";?>" class="nav-detail">监控详情</a></li>
        </ul>
    </div>
    <div class="monitor_detail" <?php if($do=='set'){?>style="display:none;"<?php }?>></div>
    <div class="monitor_set" <?php if($do=='detail'){?>style="display:none;"<?php }?> style="padding:30px 0;"></div>
</div>
<div class="am-cf"></div>
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
<?php echo $this->fetch('monitor/monitor_tpl')?>
<script src="<?php echo U("static@/javascript/echarts/echarts.common.min.js");?>" language="javascript"></script>
<script language="javaScript">
    var domain  = "<?php echo isset($domain)?$domain:"";?>";
    var RRname = "<?php echo isset($RRname)?$RRname:"";?>";
    var record_id = "<?php echo isset($record_id)?$record_id:"";?>";
    $(function(){
        var action    = "<?php echo isset($do)?$do:"";?>";
        if (action == 'set') {
            domain_monitor_set();
        }else{
            domain_monitor_detail();
        }
        //监控详情
        $(".nav-detail").unbind("click").bind("click",function(){
            domain_monitor_detail();
        });
        //监控设置
        $(".nav-set").unbind("click").bind("click",function(){
            domain_monitor_set();
        });
    });
    var acls = <?php echo JSON::encode(C("category","domain_acl")->json(0,'ident'));?>;
    var domain_monitor_set = function(){
        if (RRname === "@") {
            RRname = '';
        }
        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/api/DomainMonitor.Monitor?do=edit");?>",
            success:function(res){
                $.ui.close_loading();
                //当前类型为修改
                res.data.domain = domain;
                res.data.RRname = RRname;
                res.data.type = "edit";
                var listhtml = ""+ easyTemplate($("#tpl_monitor_tpl").html(),res.data);
                $(".monitor_set").html(listhtml);

                monitor_tpl_set();
            },
            data:{domain:domain,RRname:RRname}
        });
    }
</script>
<script type="text/template" id="tpl_monitor_detail">
    <#macro rowedit data>
        <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
            <h2 class="am-titlebar-title ">
                监控详情
            </h2>
        </div>
        <div class="domain-monitor-detail">
            <#list data.list as record>
                <#list record.monitor_item as item>
                    <#if (item.record_id == data.record_id)>
                        <div class="domain-monitor-detail-a">
                            <a href="javascript:void (0);"
                               class="am-icon-btn <#if (item.status == 0)>am-danger am-icon-close<#else><#if (item.status_code == 200 || item.status_code == 1)>am-success am-icon-check<#else>am-warning am-icon-warning</#if></#if>">
                            </a>
                        </div>
                        <div class="domain-monitor-detail-span">
                            <span class="span-up">
                                <#if (item.status == 0)>
                                    您的服务器已宕机
                                    <#else>
                                        <#if (item.status_code == 200 || item.status_code == 1)>
                                                您的服务器完全正常
                                            <#else>
                                                ${item.reason}
                                        </#if>
                                </#if>
                            </span><br/>
                            <span class="span-down">
                                <#if (item.status == 0)>
                                    八戒监控尝试连接到您的服务器时发生了未知错误，这一般是网络故障导致的
                                    <#else>
                                        <#if (item.status_code == 200 || item.status_code == 1)>
                                            八戒监控当前检测到您的服务器一切正常
                                            <#else>
                                                请检查网络或相关问题
                                        </#if>
                                </#if>
                            </span>
                        </div>
                    </#if>
                </#list>
            </#list>
        </div>
        <div class="dis20"></div>
        <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
            <h2 class="am-titlebar-title ">
                监控统计图
            </h2>
        </div>
        <div class="domain-monitor-count">
            <div class="count-select">
                <span>当前线路</span>&nbsp;&nbsp;
                <select id="monitorIP">
                    <#list data.list as record>
                        <#list record.monitor_item as item>
                                <option value="${item.record_id}" <#if (data.record_id == item.record_id)>selected="selected"</#if> >${item.ip}</option>
                        </#list>
                    </#list>
                </select>
            </div>
            <div class="count">
                <div id="Dimg" style="width: 800px;height:400px;margin:0 auto"></div>
            </div>
        </div>
        <div class="dis20"></div>
        <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
            <h2 class="am-titlebar-title ">
                线路详情
            </h2>
        </div>
        <div class="domain-acl-detail">
            <table class="am-table am-table-bordered am-table-radius">
                <thead>
                <tr>
                    <th>线路类型</th>
                    <th>服务器IP地址</th>
                    <th>当前状态</th>
                </tr>
                </thead>
                <tbody>
                <#list data.list as record>
                    <#list record.monitor_item as item>
                        <tr>
                            <td><#if ($.is_empty(acls[item.acl]))>${cust_line[item.acl.replace('cust','')].name}<#else>${acls[item.acl].name}</#if></td>
                            <td>${item.ip}</td>
                            <td>
                                <#if (item.status == 1)><#if (item.status_code==200 || item.status_code == 1)><font color="#5EB95E">您的服务器完全正常</font><#else><font color="red">服务器其他故障：${item.reason}</font></#if><#else><font color="red">您的服务器已宕机</font></#if>
                            </td>
                        </tr>
                    </#list>
                </#list>
                </tbody>
            </table>
        </div>
    </#macro>
</script>
<script language="javascript">
    var cust_line = <?php echo JSON::encode(M("@domain_acl_set")->get_cust_list(1));?>;
    var domain_monitor_detail = function(record_ids){
        if (RRname === "@") {
            RRname = '';
        }
        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/api/DomainMonitor.MonitorDetail");?>",
            success:function(res){
                $.ui.close_loading();
                if (res.status == 1) {
                    res.data.record_id = typeof record_ids=="undefined"?record_id:record_ids;
                    var listhtml = ""+ easyTemplate($("#tpl_monitor_detail").html(),res.data);
                    $(".monitor_detail").html(listhtml);

                    //监控统计图
                    var myChart = echarts.init($("#Dimg").get(0));
                    var option = {
                        title : {
                            text: '',
                            subtext: ''
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:[],
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: true},
                                dataView : {show: true, readOnly: false},
                                magicType : {show: true, type: ['line', 'bar']},
                            }
                        },
                        calculable : true,
                        xAxis : [
                            {
                                type : 'category',
                                boundaryGap : false,
                                data : [],
                                splitLine:{
                                    show:false, // 是否显示x轴标线
                                },
                            }
                        ],
                        yAxis : [
                            {
                                name:'响应时间波动(ms)',
                                type : 'value',
                                axisLabel : {
                                    formatter: '{value} ms'
                                },
                                splitLine:{
                                    interval:2,
                                },
                            }
                        ],
                        series : [
                            {
                                name:'',
                                type:'line',
                                //smooth:true,
                                data:[],
                                markLine : {
                                    data : [
                                     //   {type : 'average', name : '平均值'}
                                    ]
                                },
                                lineStyle: {
                                    normal: {
                                        color:'#2F7ED8',
                                        width:2,
                                        //shadowColor: 'rgba(0, 0, 0, 0.5)',
                                        shadowBlur: 10
                                    }
                                },
                                itemStyle: {
                                    normal: {
                                        color:'#2F7ED8',
                                    }
                                },
                                hoverAnimation: false,
                            },
                            {
                                name:'',
                                type:'line',
                                //smooth:true,
                                data:[],
                                markLine : {
                                    data : [
                                        //{type : 'average', name : '平均值'}
                                    ]
                                },
                                lineStyle: {
                                    normal: {
                                        color:'#5EB95E',
                                        width:2,
                                        //shadowColor: 'rgba(0, 0, 0, 0.5)',
                                        shadowBlur: 10
                                    }
                                },
                                itemStyle: {
                                    normal: {
                                        color:'#5EB95E',
                                    }
                                },
                                hoverAnimation: false,
                            },
                            {
                                name:'',
                                type:'line',
                                //smooth:true,
                                data:[],
                                markLine : {
                                    data : [
                                      //  {type : 'average', name : '平均值'}
                                    ]
                                },
                                lineStyle: {
                                    normal: {
                                        color:'#425663',
                                        width:2,
                                       // shadowColor: 'rgba(0, 0, 0, 0.5)',
                                        shadowBlur: 10
                                    }
                                },
                                itemStyle: {
                                    normal: {
                                        color:'#425663',
                                    }
                                },
                                hoverAnimation: false,
                            }
                        ]
                    };
                    var show_map = function(record_id){
                        $.ui.loading();
                        $.ajaxPassport({
                            url:"<?php echo U("/api/DomainMonitor.GetMapsData");?>",
                            data:{record_id:record_id},
                            success:function(res){
                                $.ui.close_loading();
                                if(res.status == 1){
                                    option.xAxis[0].data = new Array();
                                    option.series[0].data = new Array();
                                    option.legend.data   =new Array();
                                    for(var monitor_node =  0; monitor_node< res.data['monitor_node'].length;monitor_node ++){
                                        option.legend.data.push(res.data['monitor_node'][monitor_node]['domain']);
                                        option.series[monitor_node].name = res.data['monitor_node'][monitor_node]['domain'];
                                    }

                                    if (res.data.length == 0) {
                                        option.xAxis[0].data.push(0);
                                        option.series[0].data.push(0);
                                        option.series[1].data.push(0);
                                        option.series[2].data.push(0);
                                    }else{
                                        for(var node_id =  1; node_id<=3;node_id ++){
                                            for(var x =0 in res.data[node_id]){
                                                if(node_id   == 1) {
                                                    option.xAxis[node_id - 1].data.push(res.data[node_id][x]['dateline']);
                                                }
                                                option.series[node_id-1].data.push(parseInt(res.data[node_id][x]['restime']));
                                            }
                                        }
                                    }
                                    myChart.setOption(option);
                                }else{
                                    $("#Dimg").html("<p style='padding:60px 0 0 0;'>当前无可用图像</p>");
                                }
                            }
                        });
                    }
                    //时间戳转日期
                    var getLocalTime = function(nS) {
                        return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');
                    }

                    $("#monitorIP").bind("change",function(){
                        var record_ids = $(this).val();
                        domain_monitor_detail(record_ids);
                    });

                    if (!$.is_empty(record_ids)) {
                        var cur_id = record_ids;
                    }else{
                        cur_id = record_id;
                    }
                   show_map(cur_id);
                }
            },
            data:{domain:domain,RRname:RRname}
        });
    }
</script>
</body>
</html>