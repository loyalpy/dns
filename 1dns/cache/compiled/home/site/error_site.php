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
<link rel="stylesheet" href="<?php echo U("static@/css/style_page.css");?>"></head>
<body class="amz-index">
<header class="am-topbar am-topbar-fixed-top am-header-default">
  <div class="am-container">
    <h1 class="am-topbar-brand">
      <a href="#">八戒DNS</a>
    </h1>
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-secondary am-show-sm-only"
            data-am-collapse="{target: '#collapse-head'}"><span class="am-sr-only">导航切换</span> <span
        class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="collapse-head">
      <ul class="am-nav am-nav-pills am-topbar-nav">
        <li><a href="#">首页</a></li>
        <li class="am-dropdown" data-am-dropdown>
          <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
            产品 <span class="am-icon-caret-down"></span>
          </a>
          <ul class="am-dropdown-content">
            <li class="am-dropdown-header" style="display:none;">标题</li>
            <li><a href="#">解析</a></li>
            <li><a href="#">监控</a></li>
            <li><a href="#">加速</a></li>
          </ul>
        </li>
      </ul>

      <div class="am-topbar-right">
        <a href="<?php echo U("account@/register");?>" class="am-btn am-btn-secondary am-topbar-btn am-btn-sm"><span class="am-icon-pencil"></span> 注册</a>
      </div>

      <div class="am-topbar-right">
        <a href="<?php echo U("account@/login");?>" class="am-btn am-btn-primary am-topbar-btn am-btn-sm"><span class="am-icon-user"></span> 登录</a>
      </div>
    </div>
  </div>
</header>
<!-- cur nav -->
<div class="bar-line1 bar-line">
  <div class="aps">
  <h1></h1>
  <h2></h2>
  </div>
</div>
<!-- end cur nav -->
<!--content-->
<div class="aps">
<div class="dis30"></div>
<div class="msgbox msg-error">
 <div class="inner">
   <p class="p1">
   <font class="font-org f24"><?php echo isset($msg)?$msg:"";?>!</font>
   <span class="font-gray2 disnone">错误码：<?php echo isset($msgcode)?$msgcode:"";?></span>
   </p>
   <?php if($callback){?>
   <p class="p3" id="redirectionMsg">
   页面正在跳转中，请稍候......,&nbsp;&nbsp;&nbsp;将在 <span id="spanSeconds" class="font-org"><?php echo isset($seconds)?$seconds:"";?></span> 秒后跳转 <br/>
   <span class="font-gray2">如果您的浏览器没有自动跳转&nbsp;&nbsp;<a href="<?php echo isset($callback)?$callback:"";?>" class="font-blue" title="">[点击这里进入]</a></span>
   <script language="JavaScript">
		var seconds = <?php echo isset($seconds)?$seconds:"";?>;
		var defaultUrl = "<?php echo isset($callback)?$callback:"";?>";
		onload = function(){
		  if ("<?php echo isset($callback)?$callback:"";?>" == 'javascript:history.go(-1)' && window.history.length == 0){
		    $('#redirectionMsg').html("");
		    return;
		  }
		  window.setInterval(redirection, 1000);
		}
		function redirection(){
		  if (seconds <= 0){
		    window.clearInterval();
		    return;
		  }
		  seconds --;
		  $('#spanSeconds').html(seconds);
		  if (seconds == 0){
		    window.clearInterval();
		    location.href = defaultUrl;
		  }
		}
</script>
   </p>
   <?php }?>
   <div class="cl"></div>
 </div>
</div>
</div>
<!--content-->
<div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
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