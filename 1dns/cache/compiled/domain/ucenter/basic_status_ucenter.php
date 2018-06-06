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
            <li><a href="<?php echo U("/ucenter/basic?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"   class="showtype ">基本信息&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_edit?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"   class="showtype">域名信息修改&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_rz?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"  class="showtype">域名实名认证&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_transfer?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"  class="showtype ">域名过户&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_dns?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"   class="showtype">DNS修改创建&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_zs?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"   class="showtype">域名证书&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_status?domain=");?><?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>"   class="showtype cur">安全设置&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right basic-safty-set">
    <div class="basic-top">&nbsp;&nbsp;&nbsp;安全设置</div>
    <div class="dis30"></div>
    <table class="am-table">
        <col width="200px"/>
        <col width="400px"/>
        <col />
        <thead>
        <tr style="font-size: 14px;color: gray;">
            <th>设置项</th>
            <th>设置说明</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody style="font-size: 13px;">
        <tr >
            <td class="l">域名禁止转移 <i class="am-icon-question-circle-o am-font-gray" data-am-popover="{theme: 'default sm', content: '设置后域名状态为:clientTransferProhibited', trigger: 'hover focus'}"></i></td>
            <td>保护您的域名不被恶意转出</td>
            <td class="transfer-sh">
                <div id="transferLockDiv" class="w-onoff <?php if(in_array($domain_status,array(1,4))){?>w-onoff-off<?php }else{?>w-onoff-on<?php }?>">
                    <div id="transferLock" class="w-onoff-handle" data-status="clientTransferProhibited" data-kg="<?php if(in_array($domain_status,array(1,4))){?>1<?php }else{?>2<?php }?>"></div>
                </div>&nbsp;
                <div id="transferLockExplain" style="color:#999;font-weight:normal;display:inline;"><?php if(in_array($domain_status,array(1,4))){?>已关闭<?php }else{?>已开启<?php }?></div>
            </td>
        </tr>
        <tr >
            <td class="l">域名禁止更新 <i class="am-icon-question-circle-o am-font-gray" data-am-popover="{theme: 'default sm', content: '设置后域名状态为:clientUpdateProhibited', trigger: 'hover focus'}"></i></td>
            <td>
                保护您的域名注册信息不被随意篡改<br/>
                保护您的域名DNS不被恶意修改<br/>
                保护您域名的其他安全设置不被随意修改<br/>
            </td>
            <td class="update-sh">
                <div id="safetyLockDiv" class="w-onoff <?php if(in_array($domain_status,array(1,3))){?>w-onoff-off<?php }else{?>w-onoff-on<?php }?>">
                    <div id="safetyLock" class="w-onoff-handle"  data-status="clientUpdateProhibited"  data-kg="<?php if(in_array($domain_status,array(1,3))){?>1<?php }else{?>2<?php }?>"></div>
                </div>&nbsp;
                <div id="safetyLockExplain" style="color:#999;font-weight:normal;display:inline;"><?php if(in_array($domain_status,array(1,3))){?>已关闭<?php }else{?>已开启<?php }?></div>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="dis30"></div>
    <div class="now-dns-bot">
        <div class="dis10"></div>
        <dl>
            <dd style="color: red">• 域名状态设置成功后，需要十分钟左右的生效时间，请耐心等待,此期间请勿重复操作。</dd>
            <dd>• 禁止更新及禁止转移下，域名正常解析续费；禁止更新状态下，无法对域名信息或DNS进行修改；禁止转移状态下，无法操作域名转移注册商。</dd>
            <dd>• 禁止更新状态下，无法进行转移设置操作。</dd>
        </dl>
    </div>
</div>
<div class="my-domian-transfer"></div>
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
<script type="text/template" id="tpl_domain_tips">
    <#macro rowedit domain>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1-t">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" style="border-bottom: 1px solid silver;text-align: left;color:black;padding-bottom: 16px;font-size: 14px">
                    <i class="am-icon-bullhorn" style="color: #EB8500;"></i>&nbsp;&nbsp;温馨提示&nbsp;&nbsp;
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                </div>
                <div class="dis10"></div>
                <div class="am-modal-bd" style="text-align: center;">
                    <div>设置成功后需十分钟左右生效时间，在此期间请耐心等待！</div>
                    <div class="dis30"></div>
                </div>
                <button type="button" class="am-btn am-btn-warning" data-am-modal-close>知道了</button>
                <div class="dis30"></div>
            </div>
        </div>
    </#macro>
</script>
<script type="text/javascript">
    var domain = "<?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?>";
    var status_tips1= "<?php echo isset($log_tips1)?$log_tips1:"";?>";
    var status_tips2 = "<?php echo isset($log_tips2)?$log_tips2:"";?>";
    $(function () {

        //判断如果十分钟内修改提示请勿操作
        if (status_tips1 == 1 || status_tips2 == 1) {
            var html = "" + easyTemplate($("#tpl_domain_tips").html());
            $(".my-domian-transfer").html(html);
            $(".my-domian-transfer").find('#doc-modal-1-t').modal({width: 500});

            if (status_tips1 == 1) {
                $(".update-sh").html("<span style='color: green'>正在设置中...</span>");
            }
            if (status_tips2 == 1) {
                $(".transfer-sh").html("<span style='color: green'>正在设置中...</span>");
            }
        }

        $(".w-onoff-handle").click(function () {
            var obj = this;
            var addstatus = '';
            var delstatus = '';
            var status = $(obj).data("status");
            var kg = $(obj).data("kg");
            if (kg == 1) {//1已关闭2已开启
                addstatus = status;
            }else{
                delstatus = status;
            }

            $.ui.loading();
            $.ajaxPassport({
                url:"<?php echo U("ucenter/basic_status");?>",
                type:"POST",
                success:function (res) {
                    $.ui.close_loading();
                    if (res.error == 0) {
                        $.ui.success(res.message);
                        onoff($(obj).parent());
                    }else{
                        $.ui.error(res.message);
                    }
                },
                data:{domain:domain,hash:"<?php echo tUtil::hash();?>",addstatus:addstatus,delstatus:delstatus}
            });

        });
    });
    var onoff = function (obj) {
        if (obj.hasClass("w-onoff-on") == true) {
            obj.removeClass("w-onoff-on").addClass("w-onoff-off");
            obj.parent().find("#transferLockExplain").html("已关闭");
            obj.parent().find("#safetyLockExplain").html("已关闭");
        }else{
            obj.removeClass("w-onoff-off").addClass("w-onoff-on");
            obj.parent().find("#transferLockExplain").html("已开启");
            obj.parent().find("#safetyLockExplain").html("已开启");
        }
    }
</script>
</body>
</html>