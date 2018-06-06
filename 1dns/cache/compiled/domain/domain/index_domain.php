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
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_domain.css";?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_domain_py.css";?>">
<link href="<?php echo U("/static/javascript/bootstrap/css/bootstrap.min.css");?>" type="text/css" rel="stylesheet" /></head>
<body class="domain">
<!--header 开始-->
<div class="header">
    <div class="dis5"></div>
    <div class="top <?php if($act == 'index'){?>aps<?php }else{?>aps1<?php }?>">
      <div class="header-left">
        <a href="<?php echo U("home@/");?>" target="_blank"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>"/></a>
        <a class="l-a" href="<?php echo U("domain@/");?>">域名注册</a>
      </div>
      <div class="header-right">
        <ul>
          <li><a href="<?php echo U("/cart/cart");?>"><i class="glyphicon glyphicon-shopping-cart"></i><em class="num"><?php if(empty($uid)){?>0<?php }else{?><?php echo M("domain_register_cart")->get_one("uid=$uid AND status=0 AND indel=0","count(*)");?><?php }?></em></a><i class="line"></i></li>
          <?php if(empty($uid)){?>
          <li>
            <a href="<?php echo U("account@/login");?>">登陆</a>&nbsp;&nbsp;
            <a href="<?php echo U("account@/register");?>">注册</a>
            <i class="line"></i>
          </li>
          <?php }else{?>
          <?php $userinfo= C("user")->get_cache_userinfo($uid)?>
          <li>
              <?php if(!empty($uid)){?> <?php echo isset($userinfo['name'])?$userinfo['name']:"";?> 欢迎回来<?php }else{?> 欢迎来到八戒DNS<?php }?>
              <a href="<?php echo U("account@/login/logout");?>">退出</a>
            <i class="line"></i>
          </li>
          <?php }?>
          <li>
            <?php if(empty($uid)){?>
            <a href="<?php echo U("account@/login?refer=domain./ucenter/index");?>">注册域名管理</a>
            <?php }else{?>
            <a href="<?php echo U("/ucenter/index");?>">注册域名管理</a>
            <?php }?>
          </li>
        </ul>
      </div>
      <div class="cl"></div>
    </div>
  <div class="dis5"></div>
</div>
<!--header 结束-->
<div class="home">
<div class="main clr" id="inner-main">
    <div class="branding">
        <div class="text">
        <h1>域名注册服务</h1>
        <h2>八戒DNS&nbsp;&nbsp;•&nbsp;&nbsp;新网</h2>
        <h3>强势打造&nbsp;&nbsp;&nbsp;&nbsp;最可信赖</h3>
        </div>
    </div>
    <div class="search-row">
        <form  method="get" action="<?php echo U("/domain/lists");?>" >
            <div class="search-area">
                <div class="single-search">
                <input type="text" class="search" name="domain" value="" autocomplete="off" placeholder="输入您想注册的域名, 例如: 8jdns" />
                </div>
                <input type="submit" class="search-btn" value=""/>
            </div>
        </form>
    </div>
    <div class="benefits-wrap">
        <div class="prices-entry"><a target="_blank" href="<?php echo U("/domain/price_info");?>">全部价格后缀表</a><cite class="glyphicon glyphicon-paperclip"></cite></div>
        <ul class="benefits sr-only">
        </ul>

</div>
</div>
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="margin-top: 300px">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"> 温馨提示</h4>
            </div>
            <div class="modal-body" style="text-align: center;font-size: 18px;margin: 30px 0;">
                <div class="" style="font-size: 16px"> 请输入想要注册的域名,如:8jdns</div>
                <div class="dis30"></div>
                <a href="" type="button" class="btn btn-warning" data-dismiss="modal">确定</a>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="footer-bottom">
    <span><?php echo isset($site['copyright'])?$site['copyright']:"";?></span>&nbsp;&nbsp;
    <a href="<?php echo U("static@/public/images/xkz.jpg");?>" target="_blank"><span> 互联网经营许可证：<?php echo isset($site['licence'])?$site['licence']:"";?></span></a>&nbsp;&nbsp;
    <a href="<?php echo U("static@/public/images/zz.jpg");?>" target="_blank"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/zhebei.gif";?>" width="20px" height="20px"> <?php echo isset($site['icp'])?$site['icp']:"";?></a>&nbsp;&nbsp;
    <a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=33010802003217" target="_blank"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/batb.png";?>" width="20px" height="20px"> 浙公网安备 33010802003217号</a>
  </div>
</footer>
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
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
    $(".search-btn").click(function () {
        var domain = $("input[name='domain']").val();
        if ($.is_empty(domain)) {
            $('#myModal').modal();
            $("#myModal").find(".modal-dialog").width(500);
            return false;
        }
    });
</script>
</body>
</html>