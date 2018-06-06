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
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">

<link rel="stylesheet" href="<?php echo U("static/javascript/amazeui/css/amazeui.min.css");?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/account_style_uc.css";?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/account_style_uc_py.css";?>"></head>
<body>
<!-- topbar -->
<div class="topbar">
  <div class="aps">
    <div class="top-left-nav">
    <ul>
    <li>
    <a href="<?php echo U("home@/");?>" class="logo"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>" alt="八戒DNS" /></a>
    </li>
    <li><a href="javascript:void(0)" id="navSwitch" title="导航"><i class="am-icon-bars"></i> &nbsp;</a></li>
      <?php $tmplist_d =  M("register_domain")->query("uid = $uid","","dateline DESC",10)?>
      <?php if(count($tmplist_d) > 0){?>
      <li class="domain-li-d">
        <a href="javascript:void (0)" class="s">我的域名 <cite class="am-icon-caret-down"></cite></a>
        <div class="domain-li-dup">
          <table cellpadding="0" cellspacing="0" border="0" class="am-table am-table-hover">
            <thead>
            <tr>
              <th>域名</th>
              <th>操作</th>
              <th></th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($tmplist_d as $key => $item){?>
            <tr>
              <td><a href="<?php echo U("/ucenter/basic?domain=");?><?php echo isset($item['domain'])?$item['domain']:"";?>" class="tr-td-a"><?php echo isset($item['domain'])?$item['domain']:"";?></a></td>
              <td><a  href="javascript:;" class="a-a domain-renew" data-domain_id="<?php echo isset($item['id'])?$item['id']:"";?>">续费</a></td>
              <td><a  class="a-a" href="<?php echo U("account@/domains/dns/");?><?php echo isset($item['domain'])?$item['domain']:"";?>" target="_blank">解析</a></td>
              <td><a  class="a-a" href="<?php echo U("/ucenter/basic?domain=");?><?php echo isset($item['domain'])?$item['domain']:"";?>">管理</a></td>
            </tr>
            <?php }?>
            </tbody>
          </table>
          <p><a href="<?php echo U("/ucenter/index");?>">查看全部域名&gt;&gt;</a></p>
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
      <a href="<?php echo U("account@/ucenter/profile_msg");?>" style="padding:0 10px;"><span class="am-icon-envelope-o"></span>
      <span class="am-badge am-badge-warning am-round"><?php echo M("sys_information")->get_one("recieve_uid=$uid AND status=0","count(*)");?></span></a>
      </li>
      <li>
      <a href="<?php echo U("/cart/cart");?>" style="padding:0 10px;"><span class="am-icon-shopping-cart"></span>
      <span class="am-badge am-badge-warning am-round domain_register_tips"><?php echo M("domain_register_cart")->get_one("uid=$uid AND status=0 AND indel=0","count(*)");?></span>
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
<div class="mainnav mainnav-black" id="MainNav">
  <ul class="main-ul">
  <li><a href="<?php echo U("account@/domains/index");?>" <?php if(in_array($inc,array("domains","records"))){?>class="cur"<?php }?>><i class="am-icon-globe"></i> &nbsp;域名解析</a></li>
  <li><a href="<?php echo U("account@/monitor/monitor");?>" <?php if(in_array($inc,array("monitor"))){?>class="cur"<?php }?>><i class="am-icon-desktop "></i> &nbsp;域名监控</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("domain@/ucenter/index");?>" <?php if($app == "domain"){?>class="cur"<?php }?>><i class="am-icon-wordpress"></i> &nbsp;域名注册</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("account@/finance/index");?>"><i class="am-icon-user"></i> &nbsp;账户</a></li>
  <li><a href="<?php echo U("account@/order/order");?>"><i class="am-icon-reorder"></i> &nbsp;订单</a></li>
  <li><a href="<?php echo U("account@/ucenter/safety_center");?>"><i class="am-icon-gear"></i> &nbsp;设置</a></li>
  </ul>
</div>
<div class="am-uc-left">
    <div class="leftnav" id="Leftnav">
        <ul>
            <li><a href="<?php echo U("/ucenter/index");?>"  class="showtype ">全部域名&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/transfer");?>"  class="showtype cur">域名转入&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/template");?>"  class="showtype ">信息模板&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/order");?>"  class="showtype"> 我的订单&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right">
    <div class="am-alert am-alert-secondary basic-t-t" data-am-alert>
        <button type="button" class="am-close">&times;</button>
        <p style="color: #F68300">提示：域名转入前，请确保域名没有被设置禁止域名转移，更新，锁定，即处于正常状态！</p>
    </div>
    <div>
        <h1><span class="list_tit_name">转入域名</span> <span class="list_tit_count am-text-sm am-text-success">(0)</span></h1>
        <a type="button" href="<?php echo U("ucenter/transfer_submit");?>" class="am-btn am-btn-success am-btn-sm"><i class="am-icon-plus"></i> 添加域名转入</a>
    </div>
    <div class="dis20"></div>
    <div class="listbody" style="position: relative;"></div>
    <div class="dis30"></div>
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
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.min.js");?>"></script>
<script src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<?php if($uid){?>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<?php }?>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
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
<!--域名列表-->
<script type="text/template" id="tpl_domain_list">
    <#macro rowedit data>
        <table class="am-table am-table-hover domain-register-table">
            <col  width="200px"/>
            <col />
            <col width="180px" />
            <col width="200px"  />
            <thead>
            <tr>
                <th>域名</th>
                <th>转入状态</th>
                <th>提交时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody class="tpl am-form">
            <#if (data.list.length > 0)>
                <#list data.list as domain>
                    <tr>
                        <td><font color="#0095E2">${domain.domain}</font></td>
                        <td>
                            <#if (domain.status == 0)><font color="orange">待支付</font>
                                <#elseif (domain.status == 1)><font color="green">已支付</font>
                                <#elseif (domain.status == 2)><font color="gray">${domain.bz}</font>
                                <#elseif (domain.status == 3)><font color="gray">转移失败:${domain.bz}</font>
                                <#elseif (domain.status == 4)><font color="green">转移成功</font>
                                <#elseif (domain.status == 5)><font color="green">续费失败</font>
                                    <#else>
                            </#if>
                        </td>
                        <td>${$.time_to_string(domain.dateline,"Y-m-d")}</td>
                        <td>
                            <#if (domain.status == 0)><a href="<?php echo U("ucenter/transfer_pay?domain=");?>${domain.domain}"><font color="#0095E2">支付订单</font></a>
                                <#elseif (domain.status == 1)><a href="<?php echo U("ucenter/transfer_progress?domain=");?>${domain.domain}"><font color="#0095E2">提交处理</font></a>
                                <#elseif (domain.status == 2)> ------
                                 <#elseif (domain.status == 3)> <a href="<?php echo U("ucenter/transfer_progress?domain=");?>${domain.domain}"><font color="#0095E2">重新提交</font></a>
                                 <#elseif (domain.status == 4)> ------
                                 <#elseif (domain.status == 5)> <a href="<?php echo U("account@/finance/recharge_detail");?>"><font color="#0095E2">已退款</font></a>
                                     <#else>
                            </#if>
                        </td>
                    </tr>
                </#list>
                <#else>
                    <tr>
                        <td class="am-text-sm" colspan="6">
                            <a href="##" class="am-icon-exclamation-circle am-text-danger am-text-lg"></a> <a href="##" class="am-font-gray">您还没使用域名转入操作?</a>
                            <a href="<?php echo U("ucenter/transfer_submit");?>">添加域名转入</a>
                        </td>
                    </tr>
            </#if>
            </tbody>
        </table>
        <div class="pagebar">${data.pagebar}</div>
    </#macro>
</script>
<script type="text/javascript">
    $(function () {
        //加载域名列表
        load_tpl_list(1);
    });
    var load_tpl_list = function(page){
        $.ui.loading($(".listbody"),0);
        $.ajaxPassport({
            url:"<?php echo U("/ucenter/transfer");?>",
            success:function(res){
                //console.log(res);
                $.ui.close_loading($(".listbody"));
                var listhtml = ""+ easyTemplate($("#tpl_domain_list").html(),res);
                $(".listbody").html(listhtml);

                $("a").bind("focus",function(){
                    $(this).blur();
                });
                $(".list_tit_count").html("("+res['list'].length+")");
            },
            data:{page:page,do:"get"},
        });
    }
</script>
</body>
</html>