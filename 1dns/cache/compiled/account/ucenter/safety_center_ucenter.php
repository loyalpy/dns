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
            <li><a href="<?php echo U("/ucenter/safety_center");?>" class="cur">安全设置&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_basic");?>">个人资料&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_basic_com");?>">企业资料&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_msg");?>">系统通知&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_passwd");?>">修改密码&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>

<div class="am-uc-right"></div>
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
<script type="text/template" id="tpl_safety_center">
    <#macro rowedit data>
        <div>
            <h1><span class="list_tit_name">安全中心</span></h1>
        </div>
        <hr/>
        <div class="dis20"></div>
        <div class="am-msg-content">
            <table class="am-table am-table-bordered am-table-radius am-table-striped am-table-safety">
                <col width="200px"/>
                <col />
                <tbody>
                <tr>
                    <td  class="td-left">
                        <?php if($userinfo['emailrz']==1){?><i class="am-icon-sm am-icon-check" style="color: #75BB4E;"></i><?php }else{?><i class="am-icon-sm am-icon-exclamation-triangle" style="color: #F37B1D;"></i><?php }?>&nbsp;&nbsp;邮箱验证
                    </td>
                    <td class="td-right">
                        <span class="span-left email">
                            <?php if($userinfo['emailrz']==1){?>
                            您已验证邮箱 &nbsp;<i><?php echo isset($this->userinfo['email'])?$this->userinfo['email']:"";?></i>
                            <?php }else{?>
                            验证邮箱，快速找回帐号密码，实时接收信息提示。
                            <?php }?>
                        </span>
                        <?php if($userinfo['emailrz'] == 0){?>
                        <span class="span-right">
                            <i style="display: none;"><?php echo isset($this->userinfo['email'])?$this->userinfo['email']:"";?></i>
                            <button class="am-btn am-btn-warning am-radius am-btn-xs lj-email">验证邮箱</button>
                        </span>
                        <?php }?>
                    </td>
                </tr>

                <tr>
                    <td  class="td-left">
                        <?php if($userinfo['mobilerz']==1){?><i class="am-icon-sm am-icon-check" style="color: #75BB4E;"></i><?php }else{?><i class="am-icon-sm am-icon-exclamation-triangle" style="color: #F37B1D;"></i><?php }?>&nbsp;&nbsp;手机验证
                    </td>
                    <td class="td-right">
                        <span class="span-left mobile">
                            <?php if($userinfo['mobilerz']==1){?>
                            您已验证手机：<i><?php echo isset($userinfo['mobile'])?$userinfo['mobile']:"";?></i> 若已丢失或停用，请立即更换
                            <?php }else{?>
                            验证手机，快速找回帐号密码，实时接收解析信息提示。
                            <?php }?>
                        </span>
                        <span class="span-right">
                            <button class="am-btn <?php if($userinfo['mobilerz']==1){?>am-btn-default<?php }else{?>am-btn-warning<?php }?> am-radius am-btn-xs safety-mobile"><?php if($userinfo['mobilerz']==1){?>修改<?php }else{?>验证手机<?php }?></button>
                        </span>
                    </td>
                </tr>
                <tr>
                <?php $wxbd = M("@user_bd")->get_wxbd($uid)?>
                    <td class="td-left">
                    <?php if($wxbd['status'] == 2){?>
                    <i class="am-icon-sm am-icon-check" style="color: #75BB4E;"></i>&nbsp;&nbsp;已经绑定微信
                    <?php }else{?>
                    <i class="am-icon-sm am-icon-sm am-icon-exclamation-triangle" style="color: #F37B1D;"></i>&nbsp;&nbsp;微信绑定
                    <?php }?>
                    </td>
                    <td class="td-right">
                        <span class="span-left">绑定微信账号，快速扫描登录。</span>
                        <span class="span-right">
                        <?php if($wxbd['status'] == 2){?>
                        <a class="am-btn am-btn-default am-radius am-btn-xs btn-unbdwx" href="javascript:void(0)}">解除</a>
                        <?php }else{?>
                        <a class="am-btn am-btn-warning am-radius am-btn-xs" href="<?php echo U("/ucenter/wxbd");?>">立即绑定</a>
                        <?php }?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="td-left">
                        <?php if(isset($ips['uid'])){?><i class="am-icon-sm am-icon-check" style="color: #75BB4E;"></i><?php }else{?><i class="am-icon-sm am-icon-exclamation-triangle" style="color: #F37B1D;"></i><?php }?>&nbsp;&nbsp;授权IP
                    </td>
                    <td class="td-right">
                        <span class="span-left">
                            <?php if(isset($ips['uid']) && !empty($ips['ips'])){?>
                            您已设置授权IP <font color="#75BB4E"><strong><?php echo isset($ips['ips'])?$ips['ips']:"";?></strong></font>
                            <?php }else{?>
                            设置授权IP后，只允许授权IP地址登陆用户中心。
                            <?php }?>
                        </span>
                        <span class="span-right">
                            <button class="am-btn <?php if(isset($ips['uid'])){?>am-btn-default<?php }else{?>am-btn-warning<?php }?> am-radius am-btn-xs safety-ip"><?php if(isset($ips['uid'])){?>修改<?php }else{?>立即授权<?php }?></button>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </#macro>
</script>
<script type="text/javascript">
    $(function(){
        safety_center();
        $(".lj-email").unbind("click").bind("click",function(){
            safety_center_email();
        });
        $(".safety-ip").unbind("click").bind("click",function(){
            safety_center_ip();
        });
        $(".safety-mobile").unbind("click").bind("click",function(){
            safety_center_mobile();
        });
        $(".safety-weixin").unbind("click").bind("click",function(){
            safety_center_weixin();
        });
        $(".btn-unbdwx").unbind("click").bind("click",function(){
            unbdwx_op();
        });
    });
    //安全中心
    function safety_center(){
        var edit_c = "" + easyTemplate($("#tpl_safety_center").html());
        $(".am-uc-right").html(edit_c);
        plusemailX(3,2);
        plusphoneX(3,4);
    }
    //设置邮箱部分显示以星号代替
    function plusemailX (frontLen,endLen) {
        var str = $(".email i").html();
        if (typeof str=="undefined") {

        }else{
            str = str.split("@");
            var len = str[0].length-frontLen-endLen;
            var xing = '';
            for (var i=0;i<len;i++) {
                xing+='*';
            }
            var newStr = str[0].substr(0,frontLen)+xing+str[0].substr(str[0].length-endLen)+"@"+str[1];
            $(".email i").html(newStr);
        }
    }
    //设置手机部分显示以星号代替
    function plusphoneX (frontLen,endLen) {
        var str = $(".mobile i").html();
        if (typeof str=="undefined") {

        }else{
            var len = str.length-frontLen-endLen;
            var xing = '';
            for (var i=0;i<len;i++) {
                xing+='*';
            }
            var newStr = str.substr(0,frontLen)+xing+str.substr(str.length-endLen);
            $(".mobile i").html(newStr);
        }
    }
</script>
<!--安全中心授权mobile-->
<script type="text/template" id="tpl_safety_center_email">
    <#macro rowedit data>
        <div class="am-cf am-padding">
            <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">邮箱验证</strong></div>
        </div>
        <hr/>
        <div class="dis20"></div>
        <div class="am-msg-content">
            <div class="am-u-lg-6 am-u-md-9">
                <form class="am-form am-form-horizontal">
                    <div class="am-form-group">
                        <div class="am-form-group">
                            <label class="am-u-sm-4">验证邮箱:</label>
                            <div class="am-u-sm-8">
                                <input type="text" name="email" class="am-form-field am-radius" <?php if(!empty($userinfo['email'])){?>disabled="disabled"<?php }?> value="<?php echo isset($userinfo['email'])?$userinfo['email']:"";?>" placeholder="请输入需要验证的邮箱"/>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-sm-4"></label>
                        <div class="am-u-sm-8">
                            <span class="span-right">
                                <button class="am-btn am-btn-warning am-radius safety-email">立即验证</button>
                            </span>
                            <a href="<?php echo U("/ucenter/safety_center");?>"><button type="button" class="am-btn am-btn-default am-radius">返回</button></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </#macro>
</script>
<script language="JavaScript">
    function safety_center_email(){
        var edit_c = "" + easyTemplate($("#tpl_safety_center_email").html());
        $(".am-uc-right").html(edit_c);

        $(".safety-email").click(function(){
            var obj = $(this);
            var email = $("input[name='email']").val();
            if ($.is_empty(email)) {
                $.ui.error("请输入正确的邮箱格式");
                return false;
            }

            $.ui.loading();
            $.ajaxPassport({
                type:"POST",
                url:"<?php echo U("/ucenter/rz_email?hash=");?><?php echo tUtil::hash();?>",
                success:function(res){
                    $.ui.close_loading();
                    if(res.error == 1){
                        $.ui.error(res.message);
                    }else{
                        $.ui.success("邮件已发送至您的邮箱，请验证！");
                        obj.attr("disabled",true);
                    }
                },
                data:{email:email}
            });
            return false;
        });
    }
</script>
<!--安全中心授权ip-->
<script type="text/template" id="tpl_safety_center_ip">
    <#macro rowedit data>
        <div class="am-cf am-padding">
            <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">授权IP</strong></div>
        </div>
        <hr/>
        <div class="dis20"></div>
        <div class="am-msg-content">
            <form class="am-form am-form-horizontal">
                <div class="am-form-group">
                    <label  class="am-u-sm-1" style="margin-right: -30px;">授权IP:</label>
                    <div class="am-u-sm-9">
                        <textarea  id="ips" rows="5"><?php if(isset($ips['uid'])){?><?php echo str_replace(";",'\n',$ips['ips']);?><?php }?></textarea>
                        <small>授权IP列表，多IP以一行一个IP隔开;设置后只允许列表中IP登陆此帐号</small>
                    </div>
                </div>
                <div class="am-form-group">
                    <label class="am-u-sm-1" style="margin-right: -30px;"></label>
                    <div class="am-u-sm-9">
                        <button type="button" class="am-btn am-btn-warning am-radius ip-save">立即授权</button>
                        <a href="<?php echo U("/ucenter/safety_center");?>"><button type="button" class="am-btn am-btn-default am-radius">返回</button></a>
                    </div>
                </div>
            </form>
        </div>
    </#macro>
</script>
<script language="JavaScript">
    function safety_center_ip(){
        var edit_c = "" + easyTemplate($("#tpl_safety_center_ip").html());
        $(".am-uc-right").html(edit_c);
        $(".ip-save").click(function(){
            var ips = $('#ips').val();
            $.ajaxPassport({
                url:"<?php echo U("/ucenter/rz_ip");?>",
                success:function(res){
                    if (res.error == 1) {
                        $.ui.error(res.message);
                    } else {
                        $.ui.success(res.message);
                        window.location.replace("<?php echo U("ucenter/safety_center");?>");
                    }
                },
                data:{ips:ips}
            });
        });
    }
</script>
<!--安全中心授权mobile-->
<script type="text/template" id="tpl_safety_center_mobile">
    <#macro rowedit data>
        <div class="am-cf am-padding">
            <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">手机验证</strong></div>
        </div>
        <hr/>
        <div class="dis20"></div>
        <div class="am-msg-content">
            <div class="am-u-lg-6 am-u-md-9">
            <form class="am-form am-form-horizontal">
                <div class="am-form-group" id="valid-after" style="display: none">
                    <div class="am-form-group">
                        <label class="am-u-sm-4">旧号码:</label>
                        <div class="am-u-sm-8">
                            <span id="old-mobile" style="color:gray;"></span>&nbsp;<font color="gray">验证成功</font>
                        </div>
                    </div>
                </div>
                <div class="am-form-group">
                    <div class="am-form-group">
                        <label class="am-u-sm-4">手机号码:</label>
                        <div class="am-u-sm-8 ld-mobile">
                            <input type="text" name="mobile" value="<?php echo isset($userinfo['mobile'])?$userinfo['mobile']:"";?>"  <?php if(!empty($userinfo['mobile'])){?>disabled="disabled"<?php }?> class="am-form-field am-radius"/>
                            <small id="small-show"><?php if($userinfo['mobilerz']==1){?>请先验证原手机号码<?php }else{?>验证手机号码<?php }?></small>
                        </div>
                    </div>
                </div>
                <div class="am-form-group" id="btnSendCodeShow" style="display: none;">
                    <div class="am-form-group">
                        <label class="am-u-sm-4">验证码:</label>
                        <div class="am-u-sm-3">
                            <input type="text" name="rzcode" class="am-form-field am-radius" />         
                        </div>
                        <div class="am-u-sm-5">
                        <small id="btnSendCode"></small>
                        </div>
                    </div>
                </div>
                <div class="am-form-group">
                    <label class="am-u-sm-4"></label>
                    <div class="am-u-sm-8">
                        <?php if($userinfo['mobilerz']==1){?><input type="hidden" name="oldmobile" value="<?php echo isset($userinfo['mobile'])?$userinfo['mobile']:"";?>"/><?php }?>
                        <button type="button" class="am-btn am-btn-warning am-radius mobile-sh <?php if($userinfo['mobilerz']==1){?>mobile-send-old<?php }else{?>mobile-send<?php }?>">发送手机验证码</button>
                        <button type="button" class="am-btn am-btn-warning am-radius three-mobile" style="display: none">发送手机验证码</button>
                        <button type="button" class="am-btn am-btn-warning am-radius save-sh <?php if($userinfo['mobilerz']==1){?>mobile-save-change<?php }else{?>mobile-save-add<?php }?>" style="display: none">立即验证</button>
                        <button type="button" class="am-btn am-btn-warning am-radius three-save" style="display: none">立即验证</button>
                        <a href="<?php echo U("/ucenter/safety_center");?>"><button type="button" class="am-btn am-btn-default am-radius">返回</button></a>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </#macro>
</script>
<script language="JavaScript">
    function safety_center_mobile(){
        var edit_c = "" + easyTemplate($("#tpl_safety_center_mobile").html());
        $(".am-uc-right").html(edit_c);

        var mobileOld = $("input[name='oldmobile']").val();
        if (typeof mobileOld=="undefined") {
            mobileOld = 0;
        }
        //发送旧手机验证码
        $(".mobile-send-old").click(function(){
            var mobile = $("input[name='mobile']").val();
            var valid = validMobile(mobile,mobileOld,'modify');
            if (valid) {
                sendMessage(mobile,"modify",1);
            }
        });
        // 发送新手机验证码
        $(".mobile-send").click(function(){
            var mobile = $("input[name='mobile']").val();
            var valid = validMobile(mobile,mobileOld,"reg");
            if (valid) {
                sendMessage(mobile,"reg",2);
            }
        });
        //第三次发送更改后的手机验证码
        $(".three-mobile").click(function(){
            var mobile = $("input[name='mobile']").val();
            var valid = validMobile(mobile,mobileOld,"three-modify");
            if (valid) {
                sendMessage(mobile,"modify",3);
            }
        });
        //添加手机验证
        $(".mobile-save-add").click(function(){
            var mobile = $("input[name='mobile']").val();
            var rzcode = $("input[name='rzcode']").val();
            if(!rzcode.match(/^\d{6}$/)) {
                $.ui.error("验证码格式错误！");
                return false;
            }
            saveMobile(mobile,rzcode,'reg',1);
        });
        //修改手机验证
        $(".mobile-save-change").click(function(){
            var mobile = $("input[name='mobile']").val();
            var rzcode = $("input[name='rzcode']").val();
            if(!rzcode.match(/^\d{6}$/)) {
                $.ui.error("验证码格式错误！");
                return false;
            }
            saveMobile(mobile,rzcode,'modify',2);
        });
        //修改新手机验证
        $(".three-save").click(function(){
            var mobile = $("input[name='mobile']").val();
            var rzcode = $("input[name='rzcode']").val();
            if(!rzcode.match(/^\d{6}$/)) {
                $.ui.error("验证码格式错误！");
                return false;
            }
            saveMobile(mobile,rzcode,'modify',1);
        });
    }
    //向后台发送处理数据，保存验证后的手机
    function saveMobile(mobile,rzcode,type,num){
        $.ajaxPassport({
            url:"<?php echo U("/ucenter/rz_mobile");?><?php echo tHash::uri(array('dateline'=>$timestamp));?>",
            success:function(res){
                if (res.error == 1) {
                    $.ui.error(res.message);
                } else {
                    if (num == 1) {
                        $.ui.success(res.message);
                        window.location.replace("<?php echo U("ucenter/safety_center");?>");
                    }else{
                        $.ui.success("验证成功");
                        $("#valid-after").show();
                        $("#old-mobile").html(mobile);
                        $("#small-show").html("请输入新手机号码");
                        $("#btnSendCodeShow").hide();
                        $("input[name='mobile']").val('');
                        $(".am-form-field").focus();
                        $(".save-sh").hide();
                        $(".mobile-sh").hide();
                        $(".three-mobile").show();

                        $(".ld-mobile input").attr("disabled",false);
                        window.clearInterval(InterValObj);//停止计时器
                    }
                }
            },
            data:{mobile:mobile,rzcode:rzcode,type:type}
        });
    }
    //验证手机号码正确性
    function validMobile(mobile,mobileOld,type){
        if ($.is_empty(mobile)) {
            $.ui.error("请输入手机号码！");
            $(".am-form-field").focus();
            return false;
        }
        if (!mobile.match(/^1[3|4|5|8][0-9]\d{4,8}$/)) {
            $.ui.error("手机号码格式不正确！");
            $(".am-form-field").focus();
            return false;
        }
        if (type == "modify") {
            if (mobile != mobileOld) {
                $.ui.error("原手机号码不正确！");
                $(".am-form-field").focus();
                return false;
            }
        }
        if (type == "three-modify") {
            if (mobile == mobileOld) {
                $.ui.error("新手机号码与原手机号码相同！");
                $(".am-form-field").focus();
                return false;
            }
        }
        return true;
    }
    var InterValObj; //timer变量，控制时间
    var count = 120; //间隔函数，1秒执行
    var curCount;//当前剩余秒数
    //发送短信验证码
    function sendMessage(mobile,type,num) {
        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/ucenter/send_mobile");?>",
            success:function(res){
                $.ui.close_loading();
                if (res.error == 1) {
                    $.ui.error(res.message);
                } else {
                    curCount = count;
                    //设置button效果，开始计时
                    $("input[name='rzcode']").val('');
                    $("#btnSendCodeShow").show();
                    $(".mobile-sh").hide();
                    $(".three-mobile").hide();
                    $(".save-sh").show();
                    $("#btnSendCode").html("请在" + curCount + "秒内输入验证码");
                    InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                    if (num == 3){
                        $(".save-sh").hide();
                        $(".three-save").show();
                    }
                }
            },
            data:{mobile:mobile,type:type}
        });
    }
    //timer处理函数
    function SetRemainTime() {
        if (curCount == 0) {
            window.clearInterval(InterValObj);//停止计时器
            setTimeout(function () {
                window.location.replace("<?php echo U("ucenter/safety_center");?>");
            },1000)
            $("#btnSendCode").html("请重新发送验证码");
            $(".mobile-sh").show();
            $(".save-sh").hide();
            $(".three-save").hide();
        }
        else {
            curCount--;
            $("#btnSendCode").html("请在" + curCount + "秒内输入验证码");
        }
    }
</script>
<!--安全中心授权weixin-->
<script type="text/template" id="tpl_safety_center_weixin">
    <#macro rowedit data>
        <div class="am-cf am-padding">
            <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">微信绑定</strong></div>
        </div>
        <hr/>
        <div class="dis20"></div>
        <div class="am-msg-content">
            <div class="weixin-img"><img src="<?php echo U("/ucenter/qrcode");?>" width="300"></div>
            <div class="weixin-intro">请使用微信扫描图中二维码<br/>
                关注后账号即自动绑定成功<br/>
                如果您已经关注“八戒DNS”，请再次扫描二维码完成绑定<br/>
                如果您的微信号已经绑定平台其他账号,请先解除绑定，或者重新关注
            </div>
        </div>
    </#macro>
</script>
<script language="JavaScript">
    function safety_center_weixin(){
        var edit_c = "" + easyTemplate($("#tpl_safety_center_weixin").html());
        $(".am-uc-right").html(edit_c);
        $(".weixin-save").click(function(){
            $.ajaxPassport({
                url:"<?php echo U("/ucenter/weixinRz");?>",
                success:function(res){
                    if (res.error == 1) {
                        $.ui.error(res.message);
                    } else {
                        $.ui.success(res.message);
                    }
                },
                data:{ips:ips}
            });
        });
    }
</script>


<!--微信绑定解除-->
<script type="text/template" id="tpl_unbdwx">
    <#macro rowedit domain>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1-t">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" style="border-bottom: 1px solid silver;text-align: left;color:black;padding-bottom: 16px;">
                    <i class="am-icon-share-square-o" style="color: #EB8500;"></i>&nbsp;&nbsp;解除绑定&nbsp;&nbsp;<span style="font-size: 12px;color: grey">小贴士：解除绑定后您的微信将不再接收我们的信息！</span>
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                </div>
                <div class="dis10"></div>
                <div class="am-modal-bd" style="font-size: 14px;text-align: left;padding: 30px 30px 60px 30px;text-align: center;">
                    
                    <div class="dis15"></div>
                    <span style="font-weight: 600;font-size: 16px">密码：</span>
                    <input type="password" class="am-form-field am-radius am-input-sm" name="password" placeholder="" style="width: 280px;display: inline-block;height: 32px"/><br/>
                    <small style="color: gray;margin-left: -85px;">请输入账户登录密码</small>
                    <div class="dis30"></div>
                    <button type="button" class="am-btn am-btn-warning unbdwx-submit">确定</button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="am-btn am-btn-default" data-am-modal-close>返回</button>
                </div>
                <div class="dis30"></div>
            </div>
        </div>
    </#macro>
</script>
<div class="my-unbdwx"></div>
<script language="javascript">
    var unbdwx_op = function () {
        var html = "" + easyTemplate($("#tpl_unbdwx").html());
        $(".my-unbdwx").html(html);
        $(".my-unbdwx").find('.am-modal').modal({width: 600});

        $(".unbdwx-submit").unbind("click").click(function () {
            var password = $(this).parent().find("input[name='password']").val();
            $.ui.loading($(".my-unbdwx").find('.am-modal'));
            $.ajaxPassport({
                url:"<?php echo U("/ucenter/wxbd");?>",
                success:function (res) {
                    $.ui.close_loading($(".my-unbdwx").find('.am-modal'));
                    if (res.error == 0) {
                        $.ui.success(res.message);
                        setTimeout(function () {
                            $.redirect("<?php echo U("/ucenter/safety_center");?>");
                        },500)
                    }else{
                        $.ui.error(res.message);
                    }
                },
                data:{"do":"unbd","password":password}
            });
        });
    }
</script>
<!--域名过户结束-->
</body>
</html>