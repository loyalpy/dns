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
    <?php echo isset($domain)?$domain:"";?>
</div>
<div class="am-uc-left">
    <div class="leftnav" id="Leftnav" style="padding-top: 20px">
        <ul>
            <li><a href="<?php echo U("/ucenter/basic?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype ">基本信息&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_edit?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype">域名信息修改&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_rz?domain=");?><?php echo isset($domain)?$domain:"";?>"  class="showtype">域名实名认证&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_transfer?domain=");?><?php echo isset($domain)?$domain:"";?>"  class="showtype">域名过户&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_dns?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype cur">DNS修改创建&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_zs?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype">域名证书&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_status?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype">安全设置&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right" style="padding-top: 25px">
    <div class="basic-top">&nbsp;&nbsp;&nbsp;DNS修改创建</div>
    <div class="now-dns s-dns-1">
        <table class="am-table now-dns-table">
            <col width="200px"/>
            <col />
            <thead>
            </thead>
            <tbody>
            <tr>
                <td class="l">当前域名DNS为：</td>
                <td>
                    <span><?php echo isset($dns['dns1'])?$dns['dns1']:"";?></span><br/>
                    <span><?php echo isset($dns['dns2'])?$dns['dns2']:"";?></span><br/>
                    <a href="javascript:void (0);" class="change-dns">修改域名DNS</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="now-dns s-dns-2">
        <table class="am-table now-dns-table">
            <col width="200px"/>
            <col />
            <thead>
            </thead>
            <tbody>
            <tr>
                <td class="l">当前域名DNS为：</td>
                <td><?php echo isset($dns['dns1'])?$dns['dns1']:"";?>&nbsp;&nbsp;&nbsp;<?php echo isset($dns['dns2'])?$dns['dns2']:"";?></td>
            </tr>
            <tr>
                <td class="l" style="font-weight: bold">修改域名DNS</td>
                <td class="input-td">
                    <span style="font-weight: bold">请输入修改的域名DNS（注：域名DNS最少填写2个，最多填写6个 ）</span><br/>
                    <div>
                        <input type="text" name="ns1" value="" class="am-form-field am-radius" placeholder="如：dns1.8jdns.com"/><i class="s">请输入正确的DNS</i>
                    </div>
                    <div>
                        <input type="text" name="ns2" value="" class="am-form-field am-radius input-td-last" placeholder="如：dns2.8jdns.com"/><i class="s">请输入正确的DNS</i>
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button type="button" class="am-btn am-btn-default am-btn-xs am-radius add-input" style="display: none"><i class="am-icon-plus"></i> 添加DNS</button></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="now-dns-sub s-dns-2">
        <button type="button" class="am-btn am-btn-primary am-radius edit-sub">修改DNS</button>
    </div>
    <div class="dis30"></div>
    <div class="now-dns-bot">
        <div class="dis10"></div>
        <dl>
            <dt>温馨提示：</dt>
            <dd>• 修改DNS后，各地运营商递归解析服务器约需要24-48小时方可完全刷新同步，请耐心等待。</dd>
            <dd>• 请最少配置两个域名服务器以保证您的域名能够被正常解析！</dd>
        </dl>
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
    var domain ="<?php echo isset($domain)?$domain:"";?>";
    $(function () {
        $(".change-dns").click(function () {
            $(".s-dns-1").hide();
            $(".s-dns-2").show();
        });
        //添加输入框
        $(".add-input").click(function () {
            var len = $(".input-td").find("input").length;
            if (len > 4) {
                $(this).attr("disabled",true);
            }
            var html = "<div><input type='text' name='ns3' value='' class='am-form-field am-radius' placeholder='如：dns2.8jdns.com'/><i class='s'>请输入正确的DNS</i></div>";
            $(".input-td").append(html);
        })
        //修改dns
        $(".edit-sub").click(function () {
            var dns_s = new Array();
            var num = $(".input-td").find("input").length;
            $(".input-td").find("input").each(function () {
               if ($.is_empty($(this).val()) && !$.dns.is_domain($(this).val())) {
                   $(this).parent().find("i.s").show();
               }else{
                   $(this).parent().find("i.s").hide();
                   dns_s.push($(this).val());
               }
            });
            if (dns_s.length != num) {
                return false;
            }
            $.ui.loading();
            $.ajaxPassport({
                url:"<?php echo U("/ucenter/basic_dns");?>",
                success:function (res) {
                    $.ui.close_loading();
                    if (res.error == 1) {
                        $.ui.error(res.message);
                    }else{
                        setTimeout(function () {
                            window.location.replace("<?php echo U("/ucenter/basic_dns?domain=");?>"+domain);
                        },500);
                        $.ui.success(res.message);
                    }
                },
                data:{domain:domain,dns:dns_s.join(";"),do:"edit"}
            });
        });
    });
</script>
</body>
</html>