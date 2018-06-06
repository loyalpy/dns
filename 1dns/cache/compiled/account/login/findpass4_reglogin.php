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
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">

<link rel="stylesheet" href="<?php echo U("/assets/css/amazeui.min.css");?>">
<link rel="stylesheet" href="<?php echo U("static@/css/style_login.css");?>"></head>
<body>
<header class="am-topbar am-topbar-inverse am-topbar-fixed-top">
    <div class="am-container m-width">
        <h1 class="am-topbar-brand">
            <a href="<?php echo U("home@/");?>" class="am-text-ir">八戒DNS</a>
        </h1>
    </div>
</header>
<div class="dis15"> </div>
<div class="am-container m-width">
    <div class="am-btn-group am-btn-group-justify">
      <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>1</b>填写账户名</a>
      <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>2</b>验证身份</a>
      <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>3</b>设置新密码</a>
      <a class="am-btn am-btn-secondary am-disabled " href="javascript:void(0)" role="button"><b>4</b>完成</a>
    </div>
    <div class="dis30"></div>
    <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
        <h2 class="am-center am-text-center"><a href="javascript:void(0);" class="am-icon-btn am-icon-btn-sm am-success am-icon-check"></a><span class="am-text-lg">&nbsp;&nbsp;新密码设置成功！请牢记</span></h2>
        <div class="dis30"></div>
        <div class="am-center am-text-center">
        <a href="<?php echo U("account@/login");?>" class="am-btn am-btn-warning">立即登录</a>&nbsp;
        <a href="<?php echo U("home@/");?>" class="am-btn am-btn-default">返回首页</a>
        </div>
    </div>
</div><div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
    <a href="#top" title="">
          <i class="am-gotop-icon am-icon-chevron-up"></i>
    </a>
</div>
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<!--[if lte IE 8 ]>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="<?php echo U("/assets/amazeui.ie8polyfill.min.js");?>"></script>
<![endif]-->
<script src="<?php echo U("/assets/js/amazeui.min.js");?>"></script>
<script src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<?php if($uid){?>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<?php }?>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>
</script></body>
</html>
