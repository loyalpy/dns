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
            <li><a href="<?php echo U("/order/order");?>" class="cur">域名解析订单&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("domain@/ucenter/order");?>">域名注册订单&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right">
    <div>
        <h1><span class="list_tit_name">我的订单</span></h1>
    </div>
    <div class="am-form-group am-finance-list-key" style="margin: 0px;">
        <div class="am-form-group am-form-icon">
            <input type="text"  class="am-form-field am-input-sm" name="startdate" onclick="laydate()" class="am-form-field" placeholder=" 开始日期">
            <i class="am-icon-calendar"></i>
        </div>
    </div>
    <div class="am-finance-list-key">
        <div class="am-form-group am-form-icon">
            <input type="text"  class="am-form-field am-input-sm" name="enddate" onclick="laydate()" class="am-form-field" placeholder=" 结束日期">
            <i class="am-icon-calendar"></i>
        </div>
    </div>
    <div class="am-finance-list-key" style="margin-left: 80px;">
        <input type="text"  name="keyword" class="am-form-field infor-keyword am-input-sm" placeholder="请输入关键词" size="35" />
    </div>
    <div class="am-finance-list-key">
        <button type="button" class="am-btn am-btn-success am-radius am-btn-sm account-serch">搜索</button>
    </div>
    <div class="dis10"></div>
    <div class="am-finance-content">
        <div class="listbody"></div>
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
<script type="text/template" id="tpl_order_list">
    <#macro rowedit data>
        <#list data.list as order>
            <div style="color: gray;line-height: 32px;background-color: #F6F8FA;">&nbsp;&nbsp;订单编号：${order.order_no} &nbsp;&nbsp;&nbsp;创建时间：${$.time_to_string(order.dateline,"Y-m-d H:i:s")}&nbsp;&nbsp;&nbsp;订单总金额：<font color="#F37B1D">${order.amount}元</font><#if (order.amount_coupon > 0)>&nbsp;&nbsp;&nbsp;代金券优惠：<font color="#F37B1D">${order.amount_coupon}元</font></#if></div><hr/>
            <table class="am-table am-table-bordered">
                <col />
                <col width="180"/>
                <col  width="100"/>
                <col width="100"/>
                <col width="120"/>
                <col width="130"/>
                <tbody>
                <#list order.order_item as order_item>
                    <tr>
                        <td>
                            <#if (order_item.goods_name)>
                                ${order_item.goods_name}
                                <font color="gray"> (<#if (order_item.buy_type == 1)>新买<#else>续费</#if>)</font>
                                <#else>
                                    ${order_item.goods_no}
                            </#if>
                        </td>
                        <td>
                            <#if (order_item.type == 0)>域名套餐<#else>短信套餐</#if>
                            <#if (order_item.goods_name)>: ${service_group[order_item.goods_no].name}</#if>
                        </td>
                        <td><#if (order_item.goods_name)><#if (order_item.num < 10 )>${order_item.num}个月<#else>${order_item.num/10}年</#if><#else>${order_item.num}</#if></td>
                        <td ><font color="<#if (order.status != 4)> red <#else> #F37B1D </#if>">${order_item.amount}元</font></td>
                        <td class="status">
                            <#if (order.status >= 4)>
                                <#if (order.pay_status == 2)>
                                <font color="red">已退款</font>
                                <#else>
                                <font color="green">已完成</font>
                                </#if>
                            <#elseif (order.status == 3)>
                                <font color="green">已支付</font>
                            <#elseif (order.status == 0)>
                                已取消
                            <#else>
                                <font color="red">待付款</font>
                            </#if>
                        </td>
                        <#if (order_item_index == 0)>
                        <td rowspan="${order.order_item.length}" class="am-text-middle option">
                            <#if ($.in_array(order.status,[3,4,5]))>
                                <a href="<?php echo U("/order/order_view?order_no=");?>${order.order_no}" class="am-btn am-btn-default am-radius am-btn-xs pay-order">查看</a>
                            <#elseif (order.status == 0)>
                            -
                            <#else>
                                <input type="hidden" name="order_no" data-order_no="${order.order_no}"/>
                                <a href="<?php echo U("/order/order_view?order_no=");?>${order.order_no}" class="am-btn am-btn-warning am-radius am-btn-xs pay-order">支付</a>&nbsp;&nbsp;
                                <button type="button" class="am-btn am-btn-default am-radius am-btn-xs cancel-order">取消</button>
                            </#if>
                        </td>
                        </#if>
                    </tr>
                </#list>
                </tbody>
            </table>
            <div class="dis5"></div>
        </#list>
        <#if (data.list.length <= 0)>
        <a href="##" class="am-icon-exclamation-circle am-text-danger am-text-lg"></a> <a href="##" class="am-font-gray">没有符合条件的结果?</a>
        </#if>
        <div class="pagebar">${data.pagebar}</div>
    </#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script type="text/javascript">
    var service_group = <?php echo JSON::encode(M("@domain_service")->get_cache_list(0));?>;
    $(function(){
        //搜索事件
        $(".account-serch").unbind("click").bind("click",function(){
            var startdate = $("input[name='startdate']").val();
            var enddate  = $("input[name='enddate']").val();
            var keyword  = $("input[name='keyword']").val();
            load_order_list(1,keyword,startdate,enddate);
        });
        load_order_list(1);
    });
    var load_order_list = function(page,keyword,startdate,enddate){
        var url = "<?php echo U("/order/order?do=get");?>";
        var keyword  = $.is_empty(keyword)?'':keyword;
        var startdate  = $.is_empty(startdate)?'':startdate;
        var enddate  = $.is_empty(enddate)?'':enddate;
        $.ui.loading($("#tpl_order_list"));
        $.ajaxPassport({
            url:url,
            success:function(res){
                $.ui.close_loading($("#tpl_order_list"));
                var listhtml = ""+ easyTemplate($("#tpl_order_list").html(),res.data);
                $(".listbody").html(listhtml);

                $("button,a").bind("focus",function(){
                    $(this).blur();
                });
                //取消订单
                $(".cancel-order").unbind("click").bind("click",function(){
                    var obj = this;
                    $.ui.confirm(function(){
                        var order_no = $(obj).parent().find("input[name='order_no']").data("order_no");
                        $.ui.loading();
                        $.ajaxPassport({
                            url:U("/api/Order.Cancel"),
                            success:function(res){
                                $.ui.close_loading();
                                if (res.status == 1) {
                                    $.ui.success(res.msg);
                                    $(obj).parent().parent().find(".status").html("不可交易(交易取消)");
                                    $(obj).parent().parent().find(".option").html('');
                                }else{
                                    $.ui.error(res.msg);
                                }
                            },
                            data:{order_no:order_no}
                        })
                    },"您确定要取消订单吗？")
                });
            },
            data:{page:page,keyword:keyword,startdate:startdate,enddate:enddate},
        });
    }
</script>
</body>
</html>