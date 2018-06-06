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
        <p style="color: #F68300">提示：八戒DNS与新网合作，您的域名将转入新网，八戒DNS将为您提供后续服务。</p>
    </div>
    <div class="domain-transfer">
        <div class="nav">
            <ul>
                <li class="un-use use1">
                    <div class="icon">
                        <span class="s cur">1</span>
                        <font class="cur">填写域名密码</font>
                        <span class="cart_icon_right"></span>
                    </div>
                </li>
                <li class="un-use use2">
                    <div class="icon">
                        <span  class="s">2</span>
                        <font class="">支付订单</font>
                        <span class="cart_icon_right"></span>
                    </div>
                </li>
                <li class="un-use use3">
                    <div class="icon">
                        <span class="s">3</span>
                        <font class="">转入处理</font>
                        <span class="cart_icon_right"></span>
                    </div>
                </li>
                <li class="un-use use4">
                    <div class="icon">
                        <span class="s">4</span>
                        <font >邮箱验证,转入成功</font>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="domain-transfer-box">
        <div class="am-input-group">
            <span class="am-input-group-label"><i class="am-icon-globe"></i></span>
            <input type="text" name="domain"  class="am-form-field"  placeholder="请您填写需要转入的域名" style="font-size: 14px;width: 276px;">
        </div>
        <div class="dis20"></div>
        <div class="am-input-group">
            <span class="am-input-group-label"><i class="am-icon-lock" style="width: 13px"></i></span>
            <input type="password" name="code"  class="am-form-field"  placeholder="请您填写域名转移密码" style="font-size: 14px;width:276px;">
        </div>
        <div class="dis30"></div>
        <input type="hidden" name="hash"  value="<?php echo tUtil::hash();?>">
        <button type="button" class="am-btn am-btn-primary am-radius transfer-submit" style="font-size: 15px;">提交</button>&nbsp;&nbsp;
        <a href="<?php echo U("/ucenter/transfer");?>" type="button" class="am-btn am-btn-default am-radius" style="font-size: 15px;">返回</a>
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
    var supper_domain = '<?php echo "(".implode(")|(",tValidate::support_domain()).")";?>';
    $(function () {
        $(".transfer-submit").unbind("click").bind("click",function () {
            var domain = $(".domain-transfer-box").find("input[name='domain']").val();
            var code = $(".domain-transfer-box").find("input[name='code']").val();
            var hash = $(".domain-transfer-box").find("input[name='hash']").val();
            if (!$.dns.is_domain2(domain) || $.is_empty(domain)) {
                $.ui.error("非法域名！");
                return false;
            }
            if ($.is_empty(code)) {
                $.ui.error("域名转移码不能为空！");
                return false;
            }
            $.ui.loading();
            $.ajaxPassport({
                url:"<?php echo U("/ucenter/transfer_submit");?>",
                type:"POST",
                success:function (res) {
                    $.ui.close_loading();
                    if (res.error == 0) {
                        setTimeout(function () {
                            window.location.replace("<?php echo U("/ucenter/transfer_pay?domain=");?>"+res.message);
                        },500);
                    }else{
                        $.ui.error(res.message);
                    }
                },
                data:{domain:domain,code:code,hash:hash}
            });
        });
    });

</script>
</body>
</html>