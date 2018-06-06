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
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/account_style_uc_py.css";?>">
<style type="text/css">
    a img, img {  border: 0;  vertical-align: middle;  }
    a {  text-decoration: none;  color: #666;  }
    a:hover {  color: #e53639;  }
    a:focus {  outline-style: none;  }
    .blue {  color: #18499d;  }
    .red {  color: #bf1a20;  }
    .int {  width: 616px;  height: 876px;  margin: 20px auto 0;  background: url("<?php echo U("")."skins/".$this->app."/".$this->skin."/images/bg1.png";?>") no-repeat 0 0;  position: relative;  }

    /*international*/
    .int-logo {  display: block;  margin: 0 auto;  padding: 80px 0px 40px 0px;  }
    .int-title {  display: block;  margin: 0 auto;  }
    .int-text {  width: 505px;  margin: 0 auto;  position: relative;  }
    .int-p {  font-size: 12px;  line-height: 18px;  margin: 8px 0 0;  }
    .int-p2 {  font-size: 12px;  line-height: 18px;  text-align: center;  padding: 10px 0 0;  }
    .int-p span {  padding: 0 3px;  }
    .int-tab {  margin: 10px auto;  }
    .int-tab td {  padding: 0 0 0 3px; font-size: 14px }
    .int-seal {  position: absolute;  bottom: 20px;  right: 0px;  }
    .int-p3 {  width: 616px;  position: absolute;  bottom: 35px;  color: #bf1a20;  text-align: center;  }
    .int-p3 a {  color: #bf1a20;  }

    .dom{width:616px;height:876px;margin:20px auto 0;background:url("<?php echo U("")."skins/".$this->app."/".$this->skin."/images/bg1.png";?>") no-repeat 0 0;position:relative;}/*domestic*/
    .dom-logo{display:block;margin:0 auto;padding:80px 0 30px 0px;}
    .dom-title{display:block;margin:0 auto;}
    .dom-text{width:505px;margin:60px auto 0;position:relative;}
    .dom-p{font-size:14px;line-height:24px;margin:15px 0 0;}
    .dom-p2{font-size:12px;line-height:24px;text-align:center;padding:10px 0 0;}
    .dom-p span{padding:0 3px;}
    .dom-tab{margin:50px auto;}
    .dom-tab td{padding:0 0 0 3px; font-size: 14px}
    .dom-seal{position:absolute;bottom:20px;right:0px;}
    .dom-p3{width:616px;position:absolute;bottom:35px;color:#bf1a20;text-align:center;}
    .dom-p3 a{color:#bf1a20;}
</style>
</head>
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
    <?php echo isset($res['domain'])?$res['domain']:"";?>
</div>
<div class="am-uc-left">
    <div class="leftnav" id="Leftnav" style="padding-top: 20px">
        <ul>
            <li><a href="<?php echo U("/ucenter/basic?domain=");?><?php echo isset($res['domain'])?$res['domain']:"";?>" class="showtype ">基本信息&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_edit?domain=");?><?php echo isset($res['domain'])?$res['domain']:"";?>" class="showtype ">域名信息修改&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_rz?domain=");?><?php echo isset($res['domain'])?$res['domain']:"";?>" class="showtype ">域名实名认证&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_transfer?domain=");?><?php echo isset($res['domain'])?$res['domain']:"";?>"  class="showtype">域名过户&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_dns?domain=");?><?php echo isset($res['domain'])?$res['domain']:"";?>" class="showtype ">DNS修改创建&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_zs?domain=");?><?php echo isset($res['domain'])?$res['domain']:"";?>" class="showtype cur">域名证书&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_status?domain=");?><?php echo isset($res['domain'])?$res['domain']:"";?>"   class="showtype">安全设置&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right" style="padding-top: 25px">
    <div class="basic-top">&nbsp;&nbsp;&nbsp;域名证书</div>
    <div class="int" <?php if($type == 1){?>style="display:none;"<?php }?>>
        <img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/logo-xinnet_icann.gif";?>" alt="" class="int-logo">
        <img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/int.png";?>"  alt="" class="int-title">
        <div class="int-text">
            <p class="int-p"><b>国际顶级域名权威机构ICANN</b>(The Internet Corporation for Assigned Names and Numbers)授权新网Xinnet.com制作并颁发此证。<b>证明：</b></p>
            <p class="int-p">域名<span class="blue"><?php echo isset($res['domain'])?$res['domain']:"";?></span>已通过<span class="blue">北京新网数码信息技术有限公司</span>完成注册，并已在国际顶级域名数据库中备案。</p>
            <p class="int-p">This certificate was issued by xinnet.com, which is authorized by ICANN(The Internet Corporation for Assigned Names and Numbers).</p>
            <p class="int-p">This is to certify that <span class="red">Xin Net Technology Corporation</span> has registered the domain name <span class="red">bajiedns7.xyz</span>
                and the registration has taken effect since it was put on records in the database of gTLD(global Top Level Domain) in ICANN.</p>
            <table class="int-tab" width="500" cellspacing="0" cellpadding="2" border="1" bordercolordark="#FFFFFF" bordercolorlight="#666666">
                <tr>
                    <td align="right" width="30%">域名：</td>
                    <td><?php echo isset($res['domain'])?$res['domain']:"";?></td>
                </tr>
                <tr>
                    <td align="right">Domain Name：</td>
                    <td><?php echo isset($res['domain'])?$res['domain']:"";?></td>
                </tr>
                <tr>
                    <td align="right">注册所有人：</td>
                    <td><?php echo isset($domain_info['aller_name_cn'])?$domain_info['aller_name_cn']:"";?></td>
                </tr>
                <tr>
                    <td align="right">Registrant：</td>
                    <td><?php echo isset($domain_info['aller_name'])?$domain_info['aller_name']:"";?></td>
                </tr>
                <tr>
                    <td align="right">注册时间：</td>
                    <td><?php echo date("Y-m-d",$res['reg_time']);?></td>
                </tr>
                <tr>
                    <td align="right">Registration Date：</td>
                    <td><?php echo date("Y-m-d",$res['reg_time']);?></td>
                </tr>
                <tr>
                    <td align="right">到期时间：</td>
                    <td><?php echo date("Y-m-d",$res['exp_time']);?></td>
                </tr>
                <tr>
                    <td align="right">Expiration Date：</td>
                    <td><?php echo date("Y-m-d",$res['exp_time']);?></td>
                </tr>
                <tr>
                    <td align="right">通信地址：</td>
                    <td><?php echo isset($domain_info['addr_cn'])?$domain_info['addr_cn']:"";?></td>
                </tr>
                <tr>
                    <td align="right">Address：</td>
                    <td><?php echo isset($domain_info['addr'])?$domain_info['addr']:"";?></td>
                </tr>
            </table>
            <p class="int-p2">本证书仅用于证明该域名是通过北京新网数码信息技术有限公司注册，且信息为当时域名信息。本证书并不做为其他证明文件之用！<br/>北京新网数码信息技术有限公司是北京首家获ICANN授权的国际域名注册服务商</p>
            <img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/zhang.gif";?>" height="144" width="200" alt="" class="int-seal">
        </div>
        <p class="int-p3">鉴别本证书的真伪，请访问<a href="http://www.xinnet.com" target="blank">http://www.xinnet.com</a>进行查询！</p>
    </div>
    <div class="dom" <?php if($type == 0){?>style="display:none;"<?php }?>>
        <img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/cnnic.png";?>" alt="" class="dom-logo" />
        <img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/domestic.png";?>" alt="" class="dom-title">
        <div class="dom-text">
            <p class="dom-p"><b>国内顶级域名权威机构CNNIC</b>(China Internet Network Information Center)授权新网Xinnet.com制作并颁发此证。<b>证明：</b></p>
            <p class="dom-p">域名<span class="blue"><?php echo isset($res['domain'])?$res['domain']:"";?></span>已通过<span class="blue">北京新网数码信息技术有限公司</span>完成注册，并已在国内顶级域名数据库中备案。</p>
            <table class="dom-tab" width="500" cellspacing="0" cellpadding="2" border="1" bordercolordark="#FFFFFF" bordercolorlight="#666666">
                <tr>
                    <td align="right" width="30%">域名：</td>
                    <td><?php echo isset($res['domain'])?$res['domain']:"";?></td>
                </tr>
                <tr>
                    <td align="right">注册所有人：</td>
                    <td><?php echo isset($domain_info['aller_name_cn'])?$domain_info['aller_name_cn']:"";?></td>
                </tr>
                <tr>
                    <td align="right">注册时间：</td>
                    <td><?php echo date("Y-m-d",$res['reg_time']);?></td>
                </tr>
                <tr>
                    <td align="right">到期时间：</td>
                    <td><?php echo date("Y-m-d",$res['exp_time']);?></td>
                </tr>
                <tr>
                    <td align="right">通信地址：</td>
                    <td><?php echo isset($domain_info['addr_cn'])?$domain_info['addr_cn']:"";?></td>
                </tr>
            </table>
            <p class="dom-p2">本证书仅用于证明该域名是通过北京新网数码信息技术有限公司注册，且信息为当时域名信息。本证书并不做为其他证明文件之用！<br />北京新网数码信息技术有限公司是获CNNIC授权的国家顶级域名注册服务商</p>
            <img src="http://dcp.xinnet.com/Modules/agent/domain/images/zhang.gif" height="144" width="200" alt="" class="dom-seal">
        </div>
        <p class="dom-p3">鉴别本证书的真伪，请访问<a href="http://www.xinnet.com" target="blank">http://www.xinnet.com</a>进行查询！</p>
    </div>
</div>
<div class="dis30"></div>
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

</script>
</body>
</html>