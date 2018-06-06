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
<div class="am-uc-top">
    <i class="am-icon-wordpress"></i>&nbsp;
    <?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>
</div>
<div class="am-uc-left">
    <div class="leftnav" id="Leftnav" style="padding-top: 20px">
        <ul>
            <li><a href="<?php echo U("/ucenter/basic?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"   class="showtype cur">基本信息&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_edit?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"   class="showtype">域名信息修改&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_rz?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"  class="showtype">域名实名认证&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_transfer?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"  class="showtype">域名过户&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_dns?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"   class="showtype">DNS修改创建&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_zs?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"   class="showtype">域名证书&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_status?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"   class="showtype">安全设置&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right" style="padding-top: 23px">
    <?php if($is_domain_sh){?>
    <div class="am-alert am-alert-danger basic-t-t" data-am-alert>
        <button type="button" class="am-close">&times;</button>
        <?php $register_type = tCache::read("data_config")?>
        <?php $register_type =  isset($register_type['domain_register_type'])?implode("/",$register_type['domain_register_type']):'';?>
        <p>温馨提示：<?php echo isset($register_type)?$register_type:"";?>域名需及时提交实名制审核资料，实名制审核不通过将禁止解析。<a href="<?php echo U("/ucenter/basic_rz?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>" style="color:yellow;text-decoration: underline">立即认证</a></p>
    </div>
    <?php }?>
   <div class="basic-top">&nbsp;&nbsp;&nbsp;基本信息</div>
    <table class="am-table basic-table">
        <col width="200px"/>
        <col />
        <thead>
        </thead>
        <tbody>
        <tr>
            <td class="l">域名：</td>
            <td style="font-size: 16px"><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?></td>
        </tr>
        <tr>
            <td class="l">域名所有者（中文）：</td>
            <td><?php echo isset($list_info['aller_name_cn'])?$list_info['aller_name_cn']:"";?></td>
        </tr>
        <tr>
            <td class="l">联系人邮箱：</td>
            <td><span><?php echo isset($list_info['email'])?$list_info['email']:"";?></span><a href="<?php echo U("/ucenter/basic_edit?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>">修改</a></td>
        </tr>
        <tr>
            <td class="l">注册商：</td>
            <td><?php if($domain_row['agent'] == 1){?>Xin Net Technology Corp.<?php }elseif($domain_row['agent'] == 2){?>阿里云计算有限公司（原万网）<?php }else{?> - <?php }?></td>
        </tr>
        <tr>
            <td class="l">到期日期：</td>
            <td><span><?php echo date("Y-m-d",$domain_row['exp_time']);?></span><a href="javascript:;" class="domain-renew" data-domain="<?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>">续费</a></td>
        </tr>
        <tr>
            <td class="l">域名状态：</td>
            <td class="domain-statu"> <i class="am-icon-spinner am-icon-spin"></i> <span class="am-font-gray">(检测中...)</span></td>
        </tr>
        <tr>
            <td class="l">操作日志：</td>
            <td class=""><button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs domain-show" data-domain="<?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>">查看</button></td>
        </tr>
        <tr>
            <td class="l">DNS服务器：</td>
            <td>
                <p><?php echo isset($domain_row['dns1'])?$domain_row['dns1']:"";?><a href="<?php echo U("/ucenter/basic_dns?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>">修改DNS</a></p>
                <p style="position: relative;top: -8px;"><?php echo isset($domain_row['dns2'])?$domain_row['dns2']:"";?></p>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="my-domian-log1"></div>
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
<script type="text/javascript">
    var domain = "<?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>";
    $(function () {
        domain_status();
        //域名续费
        $(".domain-renew").click(function () {
            $.ajaxPassport({
                url:"<?php echo U("/ucenter/domain_renew");?>",
                success:function (res) {
                    if (res.error == 0) {
                        setTimeout(function () {
                            window.location.replace("<?php echo U("domain@/cart/cart");?>");
                        });
                    }else{
                        $.ui.error(res.message);
                    }
                },
                data:{domain:domain}
            });
        });
        //操作日志
        $(".domain-show").unbind("click").bind("click",function(){
            var domain = $(this).data("domain");
            edit_log_func(1,domain);
            return false;
        });
    });
    var domain_status = function () {
        $.ajaxPassport({
            url:"<?php echo U("ucenter/get_domain_status");?>",
            success:function (res) {
                if (res.error == 0) {
                    if (res.message == 2) {
                        html = "<span style='color: green'>clientTransferProhibited:注册商设置禁止转移</span><br/><span style='color: green'>clientUpdateProhibited:禁止更新</span>";
                    }else if (res.message == 3){
                        html = "<span style='color: green'>clientTransferProhibited:注册商设置禁止转移</span>";
                    }else if (res.message == 4){
                        html = "<span style='color: green'>clientUpdateProhibited:注册商设置禁止更新 </span>";
                    }else if (res.message == 5){
                        html = "<span style='color: green'>serverHold:注册局设置禁止解析 </span>";
                    }else if (res.message == 6){
                        html = "<span style='color: green'>serverHold:注册局设置禁止解析</span><br/><span style='color: green'>clientTransferProhibited:注册商设置禁止转移 </span>";
                    }else if (res.message == 7){
                        html = "<span style='color: green'>serverHold:注册局设置禁止解析</span><br/><span style='color: green'>clientUpdateProhibited:注册商设置禁止更新 </span>";
                    }else if (res.message == 8){
                        html = "<span style='color: green'>serverHold:注册局设置禁止解析</span><br/><span style='color: green'>clientTransferProhibited:注册商设置禁止转移</span><br/><span style='color: green'>clientUpdateProhibited:禁止更新 </span>";
                    }else{
                        html = "<span style='color: green'>ok(正常)</span>";
                    }
                }else{
                    html = "<span style='color: green'>ok(正常)</span>";
                }
                $(".domain-statu").html(html);
            },
            data:{domain:domain}
        });
    }
</script>
<!--域名升级套餐-->
<script type="text/template" id="tpl_domain_log">
    <#macro rowedit data>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" style="text-align: left;margin-left: 8px;"><i class="am-icon-book" style="color: olivedrab"></i> 域名日志
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a></div>
                <div class="am-modal-bd">
                    <table class="am-table am-table-striped" style="font-size: 14px;text-align: left">
                        <col width="150px"/>
                        <col width="100px"/>
                        <col width="120px"/>
                        <col/>
                        <thead>
                        <tr>
                            <th>操作时间</th>
                            <th>操作IP</th>
                            <th>操作项</th>
                            <th>说明</th>
                        </tr>
                        </thead>
                        <tbody>
                        <#if (data.list.length > 0)>
                            <#list data.list as domain>
                                <tr>
                                    <td class="font-gray">${$.time_to_string(domain.dateline,"Y-m-d H:i:s")}</td>
                                    <td class="font-gray">${domain.ipaddr}</td>
                                    <td class="font-gray">${domain.modi_item}</td>
                                    <td class="font-gray">${domain.modi_log}</td>
                                </tr>
                            </#list>
                            <#else>
                                <tr><td colspan="4">暂无操作记录！</td></tr>
                        </#if>
                        </tbody>
                    </table>
                </div>
                <div class="pageber">${data.pagebar}</div>
                <div class="dis30"></div>
            </div>
        </div>
    </#macro>
</script>
<script type="text/javascript">
    var edit_log_func = function (page,domain) {
        $.ajaxPassport({
            url:"<?php echo U("/ucenter/domain_log");?>",
            success:function(res){
                if (res.list.length > 0) {
                    var html = "" + easyTemplate($("#tpl_domain_log").html(),res);
                    $(".my-domian-log1").html(html);
                    $(".my-domian-log1").find('#doc-modal-1').modal({width: 1000,height:650});
                }else{
                    $.ui.success("暂无操作日志");
                }
            },
            data:{page:page,domain:domain}
        });
    }
</script>
</body>
</html>