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
<!--header 开始-->
<div class="header">
  <div class="aps">
    <div class="logo"><a href="<?php echo U("home@/");?>"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/mainlogo.png";?>"></a></div>
    <div class="search-domain-form">
      <div class="in-search">
        <form method="get" action="<?php echo U("domain@/domain/lists");?>">
          <div class="domain-inp">
            <input type="text" class="search" name="domain" value="" autocomplete="off" placeholder="输入您想注册的域名, 例如: bajiedns"/>
          </div>
          <div class="btn-buy">
            <button type="submit">查域名</button>
          </div>
          <div class="domain-type">
            <div class="label"><span>.com</span><i class="icon-arr-down"></i>
              <input type="hidden" name="suffix" value=".com"/>
            </div>
            <div class="search-extension js-domain-suffix-wrap" style="display: none;">
              <ul class="search-extension-list">
                <?php $suffixs = array(".com",".net",".cn",".org",".info",".me",".cc",".com.cn",".net.cn",
                ".name",".mobi",".pw",".tv",".wang",".xyz",".club",".la",".ac.cn",".asia",
                ".在线",".中国",".中文网");?>
                <?php foreach($suffixs as $key => $item){?>
                <li><?php echo isset($item)?$item:"";?></li>
                <?php }?>
              </ul>
            </div>
          </div>
        </form>
        <div class="in-search-link">
          <?php $link2 = tCache::read("cms_friendlink_link_domain_price");?>
          <?php foreach($link2 as $key => $item){?>
          <?php if($key > 0){?>
          <span class="line"></span>
          <?php }?>
          <?php if(in_array($item['link'],array("","#"))){?>
          <span class="a"><?php echo isset($item['name'])?$item['name']:"";?></span>
          <?php }else{?>
          <a href="<?php echo isset($item['link'])?$item['link']:"";?>" title="<?php echo isset($item['name'])?$item['name']:"";?>" target="_blank"><?php echo isset($item['name'])?$item['name']:"";?></a>
          <?php }?>
          <?php }?>
        </div>
      </div>
    </div>
    <div class="cl"></div>
  </div>
  <div class="dis40"></div>
</div>
<!--header 结束-->
<!-- banner -->
<?php $ad = tCache::read("ad_1");?>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
  	<?php foreach($ad as $key => $item){?>
    <li data-target="#carousel-example-generic" data-slide-to="<?php echo isset($key)?$key:"";?>" class="<?php if($key == 0){?>active<?php }?>"></li>
    <?php }?>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <?php foreach($ad as $key => $item){?>
    <div class="item <?php if($key == 0){?>active<?php }?>" style="background:<?php echo isset($item['bgcolor'])?$item['bgcolor']:"";?> url(<?php echo U("static@/$item[imgurl]");?>) center top no-repeat;">
      <?php if($item['linkurl']){?><a href="<?php echo isset($item['linkurl'])?$item['linkurl']:"";?>" alt="<?php echo isset($item['name'])?$item['name']:"";?>" target="_blank">&nbsp;</a><?php }?>
      <div class="carousel-caption"></div>
    </div>
    <?php }?>
  </div>
  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
<!-- end banner -->

<!--产品服务-->
<div class="bg-white">
<div class="dis60"></div>
<div class="aps">
    <h1 class="section_h1">域名解析<i class="h1_line"></i></h1>
	<div class="dis30"></div>
    <div class="box-main pros tac">
		<ul class="aps">
			<li class="pro s_f">
				<h3><span>分网络智能解析</span> <small></small></h3>
				<p>根据用户上网的环境，自动将用户解析到不同机房的服务器，让您的网站更具竞争力。</p>
				<i class="i-pro i-pro1"></i>
				<a class="trans" href="<?php echo U("/product/index");?>"><span>分网络智能解析</span></a>
			</li>
			<li class="pro">
				<h3><span>服务器宕机检测</span> <small></small></h3>
				<p>自动检测服务器的状态，一旦您的服务器出现故障宕机，则域名将自动解析到备用服务器上。</p>
				<i class="i-pro i-pro2"></i>
				<a class="trans" href="<?php echo U("/product/index");?>"><span>服务器宕机检测</span></a>
			</li>
			<li class="pro">
				<h3><span>服务器负载均衡</span> <small></small></h3>
				<p>通过DNS轮询的方式，将不同用户均衡的解析到不同服务器上，减少单台服务器的负载压力。</p>
				<i class="i-pro i-pro3"></i>
				<a class="trans" href="<?php echo U("/product/index");?>"><span>创宇盾</span></a>
			</li>
			<li class="pro">
				<h3><span>URL转发</span> <small></small></h3>
				<p>URL转发功能可通过访问您的域名后直接转向到另外一个网址。</p>
				<i class="i-pro i-pro4"></i>
				<a class="trans" href="<?php echo U("/product/index");?>"><span>URL转发</span></a>
			</li>

			<li class="pro s_f">
				<h3><span>支持更多解析类型</span> <small></small></h3>
				<p>八戒DNS提供A、MX、CName、TXT、NS类型的解析，并且对解析记录的数目没有限制。</p>
				<i class="i-pro i-pro5"></i>
				<a class="trans" href="<?php echo U("/product/index");?>"><span>支持更多解析类型</span></a>
			</li>
			<li class="pro">
				<h3><span>分省分国家解析</span> <small></small></h3>
				<p>针对大型的企业用户，您可以选择分省解析服务，将您的用户解析到不同地区国家的服务器上。</p>
				<i class="i-pro i-pro6"></i>
				<a class="trans" href="<?php echo U("/product/index");?>"><span>分省分国家解析</span></a>
			</li>
			<li class="pro">
				<h3><span>方便好用DNS功能</span> <small></small></h3>
				<p>一键保存解析修改记录,快捷的域名查找,批量的添加域名。</p>
				<i class="i-pro i-pro7"></i>
				<a class="trans" href="<?php echo U("/product/index");?>"><span>方便好用DNS功能</span></a>
			</li>
			<li class="pro">
				<h3><span>良好的操作体验</span> <small></small></h3>
				<p>八戒DNS不断的提高用户的体验，获得了让用户称赞的操作体验</p>
				<i class="i-pro i-pro8"></i>
				<a class="trans" href="<?php echo U("/product/index");?>"><span>良好的操作体验</span></a>
			</li>
			<div class="cl"></div>
		</ul>
	</div>
	<div class="pro-back" style="display: none;">
		<a class="register" href="<?php echo U("/product/index");?>">全部功能</a>
	</div>
</div>
<div class="dis60"></div>
</div>
<!--end 产品服务-->
<!-- 技术支持 -->
<div class="bg-gray">
<div class="dis30"></div>
<div class="aps">
	<h1 class="section_h1">技术支持<i class="h1_line"></i></h1>
	<div class="dis30"></div>
	<div class="ind-article">
		<div class="list">
		<h1><a href="<?php echo U("home@/cms/news/");?>"><span class="right">更多>></span></a>最新消息</h1>
		<ul>
						<?php $num = 0;?>
						<?php foreach($cms as $key => $item){?>
							<?php if($item['ident'] == 'news'){?>
								<?php foreach($cms as $key => $item1){?>
									<?php if($item1['pid'] == $item['id']){?>
										<?php foreach($cms_list['list'] as $key => $item2){?>
											<?php if($item2['fid'] == $item1['id'] && $item2['intui'] == 1){?>
												<?php $num=$num+1;?>
												<?php if($num <= 6){?>
													<li><span class="right"></span><a href="<?php echo U("/cms/news_topic/tid/$item2[tid]");?>"><?php echo isset($item2['subject'])?$item2['subject']:"";?></a></li>
												<?php }?>
											<?php }?>
										<?php }?>
									<?php }?>
								<?php }?>
							<?php }?>
						<?php }?>
		</ul>
		</div>
		<div class="list">
		<h1><a href="<?php echo U("home@/helper/index");?>"><span class="right">更多>></span></a>常见问题</h1>
		<ul>
						<?php $num2 = 0;?>
						<?php foreach($cms as $key => $item){?>
							<?php if($item['ident'] == 'helper'){?>
								<?php foreach($cms as $key => $item1){?>
									<?php if($item1['pid'] == $item['id']){?>
										<?php foreach($cms as $key => $item2){?>
											<?php if($item2['pid'] == $item1['id']){?>
												<?php foreach($cms_list['list'] as $key => $item3){?>
													<?php if($item3['fid'] == $item2['id'] && $item3['intui']==1){?>
													<?php $num2=$num2+1;?>
														<?php if($num2 <= 6){?>
														<li><span class="right"></span><a href="<?php echo U("/helper/article?tid=");?><?php echo isset($item3['tid'])?$item3['tid']:"";?>"><?php echo isset($item3['subject'])?$item3['subject']:"";?></a></li>
														<?php }?>
													<?php }?>
												<?php }?>
											<?php }?>
										<?php }?>
									<?php }?>
								<?php }?>
							<?php }?>
						<?php }?>
					</ul>
		</div>
		<div class="list list-nomarg">
		<h1><a href="<?php echo U("home@/cms/blog/");?>"><span class="right">更多>></span></a>系统博客</h1>
		<ul>
						<?php $num3 = 0;?>
						<?php foreach($cms as $key => $item){?>
							<?php if($item['ident'] == 'blog'){?>
								<?php foreach($cms as $key => $item1){?>
									<?php if($item1['pid'] == $item['id']){?>
										<?php foreach($cms_list['list'] as $key => $item2){?>
											<?php if($item2['fid'] == $item1['id'] && $item2['intui'] == 1){?>
												<?php $num3=$num3+1;?>
												<?php if($num3 <= 6){?>
													<li><span class="right"></span><a href="<?php echo U("/cms/topic/tid/$item2[tid]");?>"><?php echo isset($item2['subject'])?$item2['subject']:"";?></a></li>
												<?php }?>
											<?php }?>
										<?php }?>
									<?php }?>
								<?php }?>
							<?php }?>
						<?php }?>
					</ul>
		</div>
		<div class="dis30"></div>
	</div>
</div>
<div class="dis30"></div>
</div>
<!-- end 技术支持 -->

<!--合作伙伴-->
<div class="bg-white">
<div class="dis30"></div>
<div class="aps900">
	<h1 class="section_h1">合作伙伴<i class="h1_line"></i></h1>
	<div class="dis30"></div>
	<div class="ind-huoban">
	<?php $link2 = tCache::read("cms_friendlink_link2");?>
	<?php foreach($link2 as $key => $item){?>
	<a <?php if($key%4 == 0){?>class="nomrg"<?php }?> href="<?php echo isset($item['link'])?$item['link']:"";?>" target="_blank"><img src="<?php echo U("static@/");?><?php echo isset($item['logo'])?$item['logo']:"";?>"/><span></span></a>
	<?php }?>
	</div>

</div>
<div class="dis60"></div>
</div>
<!--合作伙伴结束-->

<!-- sla 100% -->
<div class="bg-blue">
<div class="dis30"></div>
<div class="aps">
 <div class="ind-slabox">
 	<div class="item nomrg">
 		<div class="icon icon1"></div>
 		<div class="txt">300G超高防护</div>
 	</div>
 	<div class="item">
 		<div class="icon icon2"></div>
 		<div class="txt">7 X 24小时服务</div>
 	</div>
 	<div class="item">
 		<div class="icon icon3"></div>
 		<div class="txt">100% SLA稳定运行</div>
 	</div>
 	<div class="item">
 		<div class="icon icon4"></div>
 		<div class="txt">无限域名无限记录</div>
 	</div>
 </div>
</div>
<div class="dis30"></div>
</div>
<!-- end sla 100% -->
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
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
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