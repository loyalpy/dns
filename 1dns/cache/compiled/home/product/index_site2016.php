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
<div class="site-content">
    <div class="product-intro">
        <div class="intro-title">八戒DNS智能解析让你优化多网互通;八戒DNS提供超高DNS防护;八戒DNS监控自动监控服务器故障并自动智能切换，让你的网站更快更稳定！</div>
        <div class="dis20"></div>
        <div class="item">
            <div class="left" style="width: 470px;">
                <h1>超高防护</h1>
                <div class="txt">八戒DNS提供<strong>10G、20G、30G、40G、60G、300G</strong>的超高DNS防护，为网站提供 7x24 小时保驾护航。</div>
            </div>
            <div class="right">
                <img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/highdun.gif";?>"/>
            </div>
            <div class="dis3"></div>
        </div>
        <div class="item">
            <div class="left">
                <img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/highip.gif";?>"/>
            </div>
            <div class="right" style="width: 410px;">
                <h1>准确IP地址库，才能更智能！</h1>
                <div class="txt">实现智能解析需要拥有完整准确的全球IP地址库，八戒DNS与著名网络机构合 作，拥有最完善的IP地址库。涵盖国内各地ISP和海外各个国家的IP地址段。 让您的解析更加智能准确！</div>
            </div>
            <div class="dis3"></div>
        </div>
        <div class="item">
            <div class="left" style="width: 420px;">
                <h1>宕机智能监控</h1>
                <div class="txt">宕机监控程序使用超过多种独有的算法，一旦网站服务器失去 响应，立刻切换到备用服务器上，主服务器恢复后，再自动切 换回去。让网站平稳运行。 </div>
            </div>
            <div class="right">
                <img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/highmonitor.jpg";?>"/>
            </div>
            <div class="dis3"></div>
        </div>
        <div class="item">
            <div class="left high">
                <img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/highdomain.gif";?>"/>
            </div>
            <div class="right" style="width: 410px;">
                <h2 class="top">无限域名，无限记录</h2>
                <p>我们认为，基本的域名解析功能是不应受到任何限制的。你可以将所有的域名放在8jdns统一管理。我们允许添加所有类型的解析记录，包括A、CNAME、NS、MX、TXT、SRV、AAAA（IPv6）……，甚至还有免费的 URL 转发.</p>
                <div class="dis20"></div>
                <h2>实时生效</h2>
                <p>8jdns专有的DNS同步引擎，仅仅几秒就可以将 DNS 记录同步到8jdns全国的云DNS集群，快如闪电。</p>
                <div class="dis20"></div>
                <h2>99.99% 稳定运行时间</h2>
                <p>8jdns采用分布式DNS服务器架构。一个节点发生故障，其他节点不会受到影响，请求自动分配到可用服务器，对网站完全透明。</p>
                <div class="dis20"></div>
                <h2>最细致的区域划分</h2>
                <p>我们拥有最权威最精确的地址库，用来准确定位请求来源，为之分配最佳解析结果。电信？联通？移动？教育网？这些还不够，八戒DNS能给你各个大洲、国家，全国各种线路、各个省份的细致区域划分。</p>
                <div class="dis20"></div>
                <h2>搜索引擎优化</h2>
                <p>
                    新站想被马上被收录？想收录更多页面？8jdns完成你的心愿！我们与百度、搜搜、搜狗有着深入密切的合作关系。网站一旦开始使用 8jdns，数据将会被准确迅速地推送到各个搜索引
                    <span id="transmark" style="display: none; width: 0px; height: 0px;"></span>
                    擎.
                </p>
            </div>
            <div class="dis3"></div>
        </div>
        <div class="item">
            <div class="left" style="width: 380px;">
                <h1>简单易用</h1>
                <div class="txt">八戒DNS的管理界面简单易用、操作方便，大量人性化设计， 即使数千个域名的操作，也可以在几分钟内轻松完成。 </div>
            </div>
            <div class="right">
                <img class="img" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/simple.jpg";?>"/>
            </div>
            <div class="dis3"></div>
        </div>
        <div class="intro-free">
            <h1>我们一直在免费</h1>
            <div class="free-text">八戒DNS自上线来，一直致力于带给更多人免费的优质DNS解析服务。我们设计高性能的DNS软件，用更少的资源满足更多的用户需求； 我们从来没有，也永远不会对基本的DNS功能收费。八戒DNS还有额外的套餐，以合理的价格提供了更好的服务，满足大中型网站和企业的需求。</div>
        </div>
        <div class="dis30"></div>
        <div class="intro-bottom">
            <h1>DNS解析，我们只选八戒DNS！</h1>
            <div class="intro-bottom-txt">如果您对TA还有其他疑问，请查看 <a href="<?php echo U("home@/helper/index");?>" style="color: #00a1cb">帮助中心</a></div>
            <a href="<?php echo U("account@/register");?>" type="button" class="am-btn am-radius am-btn-success"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/index/rigister.gif";?>"></a>
        </div>
    </div>
</div>
<div class="dis20"></div>
<div class="footer">
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
        <li><a href="<?php echo U("dns@");?>">DNS解析控制台</a></li>
        <li><a href="/helper/cat/29">功能介绍</a></li>
        <li><a href="/helper/cat/33">销售问题</a></li>
        <li><a href="/helper/cat/39">技术问题</a></li>
      </ul>
    </div>
    <div class="column">
      <ul >
        <li class="t"><a href="<?php echo U("home@/cms/onepage/i/about/c/intro");?>">关于我们</a></li>
        <li><a href="<?php echo U("home@/cms/onepage/i/about/c/intro");?>">关于我们</a></li>
        <li><a href="<?php echo U("home@/cms/onepage/i/about/c/contact");?>">联系我们</a></li>
        <li><a href="<?php echo U("home@/cms/blog/");?>">官方博客</a></li>
        <li><a href="<?php echo U("home@/cms/onepage/i/about/c/agreement");?>">服务协议</a></li>
      </ul>
    </div>
    <div class="column">
      <ul>
        <li class="t"><a href="<?php echo U("home@/cms/onepage/i/about/c/contact");?>">联系我们</a></li>
        <li class="qq"><?php echo isset($site['qq'])?$site['qq']:"";?></li>
        <li class="tel"><?php echo isset($site['tel'])?$site['tel']:"";?></li>
        <li>&nbsp;</li>
        <li>&nbsp;</li>
      </ul>
    </div>
    <div class="column column-weixin">
      <ul>
        <li class="t"><a href="<?php echo U("home@/cms/onepage/i/about");?>">微信扫码加关注</a></li>
        <li><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/weixin.jpg";?>" width="100px" height="100px"></li>
      </ul>
    </div>
    <div class="dis10"></div>
  </div>
  <?php if($inc == "site" &&  $act == "index"){?>
  <div class="footer-link-intro">
          友情链接：
          <?php $link1 = tCache::read("cms_friendlink_link1");?>
          <?php foreach($link1 as $key => $item){?>
          <?php if($item['cat_id'] == 'link1'){?>
          <a href="<?php echo isset($item['link'])?$item['link']:"";?>" target="_blank"><?php echo isset($item['name'])?$item['name']:"";?></a>&nbsp;&nbsp;&nbsp;
          <?php }?>
          <?php }?>
  </div>
  <?php }?>
  <div class="footer-bottom">
    <span><?php echo isset($site['copyright'])?$site['copyright']:"";?></span>&nbsp;&nbsp;
    <a href="<?php echo U("static@/public/images/xkz.jpg");?>" target="_blank"><span> 互联网经营许可证：<?php echo isset($site['licence'])?$site['licence']:"";?></span></a>&nbsp;&nbsp;
    <a href="<?php echo U("static@/public/images/zz.jpg");?>" target="_blank"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/zhebei.gif";?>" width="20px" height="20px"> <?php echo isset($site['icp'])?$site['icp']:"";?></a>&nbsp;&nbsp;
    <a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=33010802003217" target="_blank"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/batb.png";?>" width="20px" height="20px"> 浙公网安备 33010802003217号</a>
  </div>
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