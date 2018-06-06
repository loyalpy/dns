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
<meta http-equiv="X-UA-Compatible"content="IE=8"><!--以IE8模式渲染--> 
<!--<metahttp-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style.css";?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_page.css";?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_page_py.css";?>"></head>
<body class="">
<!--header 开始-->
<div class="header">
  <div class="header_bar" style="display: none;">
      <div class="aps">
      <div class="topnav">
        <ul class="">
          <!--<li><a href="<?php echo U("account@/monitor/monitor");?>" class="line">监控中心</a></li>-->
          <!--<li><a href="<?php echo U("account@/ucenter/profile_basic");?>" class="line">用户资料</a></li>-->
          <li><a href="<?php echo U("account@/domains");?>" class="line">域名管理</a></li>
          <?php if(empty($uid)){?>
          <li><a href="<?php echo U("account@/register");?>" class="line yellow">免费注册</a></li>
          <li><a href="<?php echo U("account@/login");?>" class="login">您好，请登录</a></li>
          <?php }else{?>
          <li><a href="<?php echo U("account@/login/logout");?>" class="line">退出</a></li>
          <li><a href="<?php echo U("account@/register");?>" class="line yellow">管理中心</a></li>
          <?php }?>
          <li><a href="<?php echo U("home@/");?>" class=""><?php if(!empty($uid)){?> <?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?> 欢迎回来<?php }else{?> 欢迎来到八戒DNS<?php }?></a></li>
        </ul>
    </div>
    </div>
  </div>
  <div class="header_main mt0 aps">
    <div class="logo fl"><a href="<?php echo U("home@/");?>"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/mainlogo.png";?>"></a></div>
    <div class="login fr">
      <?php if(empty($uid)){?>
      <a class="button fl" href="<?php echo U("account@/login");?>">登录</a>      
      <a class="button fl" href="<?php echo U("account@/register");?>">注册</a>
      <?php }else{?>
      <a class="button fl" href="<?php echo U("account@/order/cart_shopping");?>"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/index/gouwuche.jpg";?>" style="position: relative;top: 4px"/>购物车</a>
      <a class="button fl" href="<?php echo U("account@/ucenter");?>">管理中心</a>
      <?php }?>
    </div>
    <div class="menu fr">
      <ul>
        <li><a class="<?php if($inc == "site" && $act == 'index'){?>cur<?php }?>" href="<?php echo U("home@/");?>">首页</a></li>
        <li><a class="<?php if($inc == "product" && $act == 'index'){?>cur<?php }?>" href="<?php echo U("home@/product/index");?>">产品介绍</a></li>
        <li><a class="<?php if($inc == "product" && $act == 'buy'){?>cur<?php }?>" href="<?php echo U("home@/product/buy");?>">购买套餐</a></li>
        <li><a class="<?php if($inc == "helper" && ($act == 'index' || $act == 'article' || $act == 'cat')){?>cur<?php }?>" href="<?php echo U("home@/helper/index");?>">技术支持</a></li>
      </ul>
    </div>
  </div>
</div>
<!--header 结束-->
<div class="dns-tools">
    <div class="content">

        <div class="tools-top">
            <p class="p1">WHOIS查询工具</p>
            <p class="p2">此工具可以协助您查询域名注册商相关问题</p>
        </div>

        <div class="row">
            <form class="the_searchform form" method="POST" action="<?php echo U("/tools/ipwhois");?>" onsubmit="return loadresult();">
                <div class="row-content c1">
                    <span>请输入域名或IP:</span><input type="text" name="question" class="text" value="<?php echo isset($this->question)?$this->question:"";?>"/><span class="submit">提交查询</span><br/>
                    <input type="hidden" name="hash" value="<?php echo tUtil::hash();?>" />
                    <button type="submit" style="display: none"></button>
                </div>
            </form>
            <div class="dis10"></div>
            <div class="row-top"></div>
            <div class="dis40"></div>
            <div class="result">
                <?php if($this->result){?>
                    <p>查询结果</p>
                    <div class="query-res" style="width: 480px;"><pre><?php echo isset($this->result)?$this->result:"";?></pre></div>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
  <div class="footer-intro">
    <div class="column">
      <ul>
        <li class="t"><a href="<?php echo U("home@/product/index");?>">产品介绍</a></li>
        <li><a href="<?php echo U("home@/product/buy");?>">套餐服务</a></li>
        <li><a href="<?php echo U("home@/product/index");?>">智能DNS</a></li>
        <li><a href="<?php echo U("account@/monitor/monitor");?>">监控中心</a></li>
        <li><a href="<?php echo U("account@/domains");?>">域名管理</a></li>
      </ul>
    </div>
    <div class="column">
      <ul>
        <li class="t"><a href="<?php echo U("home@/helper/index");?>">帮助中心</a></li>
        <li><a href="<?php echo U("home@/tools/index");?>">DNS工具</a></li>
        <li><a href="<?php echo U("home@/helper/article?id=26&pid=25");?>">功能介绍</a></li>
        <li><a href="<?php echo U("home@/helper/article?id=27&pid=25");?>">销售问题</a></li>
        <li><a href="<?php echo U("home@/helper/article?id=28&pid=25");?>">技术问题</a></li>
      </ul>
    </div>
    <div class="column">
      <ul >
        <li class="t"><a href="<?php echo U("home@/cms/onepage/i/about");?>">关于我们</a></li>
        <li><a href="<?php echo U("home@/cms/onepage/i/about");?>">关于我们</a></li>
        <li><a href="<?php echo U("home@/cms/onepage/i/about/c/contact");?>">联系我们</a></li>
        <li><a href="<?php echo U("home@/cms/blog/");?>">官方博客</a></li>
        <li><a href="<?php echo U("home@/cms/onepage/i/about/c/agreement");?>">服务协议</a></li>
      </ul>
    </div>
    <div class="column">
      <ul>
        <li class="t"><a href="<?php echo U("home@/cms/onepage/i/about/c/contact");?>">联系我们</a></li>
        <li>企业QQ：<?php echo isset($site['qq'])?$site['qq']:"";?></li>
        <li>联系电话：<?php echo isset($site['tel'])?$site['tel']:"";?></li>
        <li>&nbsp;</li>
        <li>&nbsp;</li>
      </ul>
    </div>
    <div class="column">
      <ul>
        <li class="t"><a href="<?php echo U("home@/cms/onepage/i/about");?>">关注我们</a></li>
        <li><img src="<?php echo U("static@/attach/avatar/000/00/00/weixin.jpg");?>" width="86px" height="86px"></li>
      </ul>
    </div>
    <div class="dis10"></div>
  </div>
  <div class="footer-bottom">
    <span><?php echo isset($site['copyright'])?$site['copyright']:"";?></span>&nbsp;&nbsp;
    <a href="http://www.8jdns.com/common/images/xkz.jpg" target="_blank"><span> 互联网经营许可证：<?php echo isset($site['licence'])?$site['licence']:"";?></span></a>&nbsp;&nbsp;
    <a href="http://www.8jdns.com/common/images/zz.jpg" target="_blank"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/zhebei.gif";?>" width="20px" height="20px"> <?php echo isset($site['icp'])?$site['icp']:"";?></a>&nbsp;&nbsp;
    <a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=33010802003217" target="_blank"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/batb.png";?>" width="20px" height="20px"> 浙公网安备 33010802003217号</a>
  </div>
</footer>
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<!--[if lte IE 8 ]>
<script src="<?php echo U("static/javascript/amazeui/js/modernizr.js");?>"></script>
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.ie8polyfill.min.js");?>"></script>
<![endif]-->

<?php if($uid){?>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<?php }?>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>
</script>
<script type="text/javascript">
    $(function(){
        $(".submit").click(function(){
            $(this).parent().find("button").click();
        });
    });
    var loadresult = function(){
        $.ui.loading();
        return true;
    }
</script>
</body>
</html>