<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
  <meta name="description" content="会员中心">
  <meta name="keywords" content="index">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="assets/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="<?php echo isset($site['site_name'])?$site['site_name']:"";?>" />  
  <link rel="stylesheet" href="<?php echo U("/assets/css/amazeui.min.css");?>">
  <link rel="stylesheet" href="<?php echo U("static@/css/style_uc.css");?>">
  <link rel="stylesheet" href="<?php echo U("static@/css/style_u1.css");?>">
  </head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->
<header class="am-topbar admin-header">
  <div class="am-topbar-brand">
    <strong>八戒DNS</strong> <small>用户中心</small>
  </div>
  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>
  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">
    <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
      <li><a href="javascript:;"><span class="am-icon-envelope-o"></span> 收件箱 <span class="am-badge am-badge-warning">5</span></a></li>
      <li class="am-dropdown" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-users"></span> 管理员 <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="#"><span class="am-icon-user"></span> 资料</a></li>
          <li><a href="#"><span class="am-icon-cog"></span> 设置</a></li>
          <?php if($this->userinfo['urole'] > 0){?>
		  <li><a target="_blank" href="<?php echo U("admin@/");?>" class="t-combtn"><cite class="icon-topren"></cite> 管理中心</a></li>
		  <?php }?>
          <li><a href="<?php echo U("/login/logout");?>"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>
      <li class="am-hide-sm-only"><a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>
    </ul>
  </div>
</header>

<div class="am-cf admin-main">
  <!-- sidebar start -->
  <div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
    <div class="am-offcanvas-bar admin-offcanvas-bar">
            
      
      <div class="am-panel am-panel-default admin-sidebar-panel" style="display:none;">
        <div class="am-panel-bd">
          <p><span class="am-icon-bookmark"></span> 公告</p>
          <p></p>
        </div>
      </div>
          </div>
  </div>
  <!-- sidebar end -->

  <!-- content start -->
  <div class="admin-content">
   
<div class="aps">
<?php $title = R("title","string");
		    $type = R("type","string");
		    $type = $type?$type:"error";
		    
		    $exttype = R("exttype","string");
		    
		    $info = R("info","string");
		    $extstr = R("extstr","string");
		    $extstr = $extstr?$extstr:"如有疑问，请联系".App::get_conf('site.tel');
		    $extinfo = $extstr?explode("###",$extstr):array();
		    
		    $btnstr = R("btnstr","string");
		    $btninfo = $btnstr?explode("###",$btnstr):array();
	    
	    ?>
	    
	    <div class="uc-msg" style="min-height:600px;">
	    
	     <b class="f14 font-green" style="display:none;"><?php echo isset($title)?$title:"";?></b>
	     <!--提示信息-->
	       <?php if($type == "success"){?>
			   <div class="tipmsg3">
			      <div class="dis60"></div>
			      <p class="tc"><cite class="icon-success " style="position:relative;top:3px;"></cite>  &nbsp;&nbsp;<font class="font-org f18 yh"><?php echo isset($info)?$info:"";?></font></p>
			      <?php if($extinfo){?>
			      <div class="dis15"></div>
			      <?php foreach($extinfo as $key => $item){?>
			      <p class="tc"><font class="font-black f12"><?php echo isset($item)?$item:"";?></font></p>
			      <?php }?>
			      <?php }?>
			   </div>
			   <div class="dis30"></div>
			   
			      <?php if($btninfo){?>
			      <div class="uc-msg-btn">
			      <?php foreach($btninfo as $key => $item){?>
			      <?php $tmp = explode("#",$item);?>
			      <a href="<?php echo isset($tmp[0])?$tmp[0]:"";?>" class="btn btn-org"><?php echo isset($tmp[1])?$tmp[1]:"";?></a><div class="dis5"></div>
			      <?php }?>
			      </div>
			      <?php }?>	
			   
			   <div class="dis60"></div>
			   <!--end提示信息-->
		   <?php }else{?>
		       <div class="tipmsg3">
		       <div class="dis60"></div>
			   <p class="tc"><cite class="icon-warning glyphicon glyphicon-exclamation-sign f18 font-org" style="position:relative;top:3px;"></cite> &nbsp;&nbsp;<font class="font-org f18 yh"><?php echo isset($info)?$info:"";?></font></p>
			   <?php if($extinfo){?>
			   <div class="dis15"></div>
			   <?php foreach($extinfo as $key => $item){?>
			   <p class="tc "><font class="font-gray f12"><?php echo isset($item)?$item:"";?></font></p>
			   <?php }?>
			   <?php }?>
			   </div>
			   <div class="dis30"></div>
			   
			      <?php if($btninfo){?>
			      <div class="uc-msg-btn">
			      <?php foreach($btninfo as $key => $item){?>
			      <?php $tmp = explode("#",$item);?>
			      <a href="<?php echo isset($tmp[0])?$tmp[0]:"";?>" class="btn btn-org"><?php echo isset($tmp[1])?$tmp[1]:"";?></a><div class="dis5"></div>
			      <?php }?>
			      </div>
			      <?php }?>	
			   
			   <div class="dis60"></div>
		   <?php }?>
		</div>
</div>
   
  </div>
  <!-- content end -->
</div>

<a href="#" class="am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}">
  <span class="am-icon-btn am-icon-th-list"></span>
</a>
<footer>
  <hr>
  <p class="am-padding-left"><?php echo htmlspecialchars_decode($site['copyright']);?></p>
</footer>
<div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
    <a href="#top" title=""><i class="am-gotop-icon am-icon-chevron-up"></i></a>
</div>
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<!--[if lte IE 8 ]>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="<?php echo U("/assets/amazeui.ie8polyfill.min.js");?>"></script>
<![endif]-->
<script src="<?php echo U("/assets/js/amazeui.min.js");?>"></script>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>
(function($) {
  'use strict';
  $(function() {
    var $fullText = $('.admin-fullText');
    $('#admin-fullscreen').on('click', function() {
      $.AMUI.fullscreen.toggle();
    });
    $(document).on($.AMUI.fullscreen.raw.fullscreenchange, function() {
      $fullText.text($.AMUI.fullscreen.isFullscreen ? '退出全屏' : '开启全屏');
    });
  });
})(jQuery);
</script>

<script language="javascript" src="<?php echo U("static@/javascript/layer/layer.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/easyTemplate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.init.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script></body>
</html>
