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
<meta name="keywords" content="<?php echo isset($site['seo_keyword'])?$site['seo_keyword']:"";?>">
<meta name="description" content="<?php echo isset($site['seo_description'])?$site['seo_description']:"";?>">
<link rel="stylesheet" href="<?php echo U("/static/javascript/bootstrap/css/bootstrap.min.css");?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style2016.css";?>"></head>
<body>
<!-- topbar -->
<div class="topbar">
  <div class="aps">
    <div class="top-left-nav">
    <ul>
    <?php if(in_array($inc,array("product","helper","cms")) || ($inc == "site" && $act == "msg")){?>
    <li><a href="<?php echo U("/");?>" class="logo"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>" alt="八戒DNS" /></a></li>
    <?php }?>
    <li><a href="<?php echo U("home@/");?>" <?php if($inc == "site" && $act == "index"){?>class="cur"<?php }?> title="八戒DNS首页">首页</a></li>
    <li><a href="<?php echo U("home@/product/index");?>" title="产品服务">产品服务</a>
    <div class="sub-nav">
      <ul>
      <li><a href="<?php echo U("home@/product/index");?>"> 域名解析</a></li>
      <li><a href="<?php echo U("account@/monitor/monitor");?>"> 域名监控</a></li>
      <li class="line"></li>
      <li><a href="<?php echo U("domain@/");?>">域名注册</a></li>
      </ul>
    </div>
    </li>
    <li><a href="<?php echo U("home@/product/buy");?>" title="八戒DNS套餐购买">购买</a></li>
    <li><a href="<?php echo U("home@/product/index");?>" title="介绍">介绍</a></li>
    <li><a href="<?php echo U("home@/helper/index");?>" title="帮助与支持">帮助/支持</a></li>
    </ul>
    </div>
    <div class="top-right-nav">
    <ul>
    <?php if(empty($uid)){?>
    <li><a href="<?php echo U("account@/");?>">管理控制台</a></li>
    <li><a href="<?php echo U("account@/register");?>">免费注册</a></li>
    <li><span>亲,请<a href="<?php echo U("account@/login");?>">登录</a></span></li>
    <?php }else{?>
    <?php $userinfo = C("user")->get_cache_userinfo($uid)?>
    <li><a href="<?php echo U("account@/");?>">管理控制台</a></li>
    <li><a href="<?php echo U("account@/");?>">购物车(0)</a></li>
    <li><span>亲,<a href="<?php echo U("account@/");?>"><?php echo isset($userinfo['name'])?$userinfo['name']:"";?></a>欢迎回来,<a href="<?php echo U("account@/login/logout");?>">退出</a></span></li>
    <?php }?>
    </ul>
    </div>
  </div>
</div>
<!-- end topbar -->
		<?php $type  = R("type","string");
		    $type  = $type?$type:"error";

		    $title = R("title","string");
		    $info    = R("info","string");

		    $callback = R("callback","string");

		    $info  = $info?$info:"系统提示,页面稍后自动跳转...";
		    $callback = $callback?$callback:U("home@/");
			$exejs  = R("exejs","string");
	    
	    ?>
	    
	    <div class="uc-msg msg-<?php echo isset($type)?$type:"";?>">
	    	   <div class="dis60"></div>
	     	   <!--提示信息-->
			   <div class="uc-msg-box">			      
			      <div class="title">
			      <cite class="icon"></cite>
			      <span class="<?php if($type == 'success'){?>success<?php }else{?>error<?php }?> f18 txt"><?php echo isset($title)?$title:"";?></span>
			      </div>
			      <?php if($info){?>
			      <div class="dis15"></div>
			      <div class="info"><font class="font-gray f12"><?php echo isset($info)?$info:"";?></font></div>
			      <?php }?>
			   </div>
			   <div class="cl"></div>
			   <?php if($callback){?>
			   <div class="dis30"></div>
			   <div class="uc-msg-btn">

				</div>
			   <?php }?>
			   <div class="dis60"></div>
			   <!--end提示信息-->
		</div>
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
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<!--[if lte IE 8 ]>
<script src="<?php echo U("static@/javascript/jquery/modernizr.js");?>"></script>
<script src="<?php echo U("/assets/amazeui.ie8polyfill.min.js");?>"></script>
<![endif]-->

<?php if($uid){?>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<?php }?>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>

$(function(){
    $(".js-domain-suffix-wrap ul li").unbind("click").bind("click",function(){
      var name = $(this).text();
      $(".js-domain-suffix-wrap").parent().find("input[name='suffix']").val(name);
      $(".js-domain-suffix-wrap").parent().find(".label span").html(name);
    });
});
</script>
<script language="javascript">
	$(function(){
		<?php if(!empty($exejs)){?>
		$.exeJS("<?php echo isset($exejs)?$exejs:"";?>");
		<?php }?>
		setTimeout(function(){
			window.location.replace("<?php echo isset($callback)?$callback:"";?>");
		},3000);
	});
</script>
<span style="display:none;">
<script language="javascript">
var _hmt = _hmt || [];
(function() {
  //百度统计
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?0084a037405ca6e864237ddd307086fc";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);

  //客服悬浮窗
  <?php if($inc == "site" && $act == "index"){?>
  $("#Dfloatdiv").css('display','none');
  $(window).scroll(function(){
    if($(window).scrollTop()>180){
      $("#Dfloatdiv").fadeIn();

    }else{
      $("#Dfloatdiv").fadeOut();
    }
  });
  <?php }else{?>
  $("#Dfloatdiv").fadeIn();
  <?php }?>
  $("#Dfloatdiv").find(".item").hover(function(){
            var sIn_obj = $(this).find(".in");
            $(this).addClass("item-over");
            sIn_obj.stop(true,false).animate({"width":sIn_obj.attr("_w")},300);
          },function(){
            $(this).removeClass("item-over");
            $(this).find(".in").stop(true,false).animate({"width":0},300);
          }
  );
})();
</script>
</span>
</body>
</html>