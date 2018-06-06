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
<link rel="stylesheet" href="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/css/style.css";?>">
<link rel="stylesheet" href="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/css/style_page.css";?>">
<link rel="stylesheet" href="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/css/style_page_py.css";?>"></head>
<body class="index_body">
<!--header 开始-->
<div class="header">
  <div class="header_bar">
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
          <li><a href="<?php echo U("home@/");?>" class=""><?php if(!empty($uid)){?> <?php if($utype == 1){?><?php $uname = M("user")->get_one("uid = $uid","uname");echo $uname;?><?php }else{?> <?php $com=M("company")->get_row("uid=$uid");echo $com['company_name'];?> <?php }?> 欢迎回来<?php }else{?> 欢迎来到八戒DNS<?php }?></a></li>
        </ul>
    </div>
    </div>
  </div>
  <div class="header_main mt0 aps">
    <div class="logo fl"><a href="<?php echo U("home@/");?>"><img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/mainlogo.png";?>"></a></div>
    <div class="login fr">
      <?php if(empty($uid)){?>
      <a class="button fl" href="<?php echo U("account@/login");?>">登录</a>      
      <a class="button fl" href="<?php echo U("account@/register");?>">注册</a>
      <?php }else{?>
      <a class="button fl" href="<?php echo U("account@/order/cart_shopping");?>"><img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/gouwuche.jpg";?>" style="position: relative;top: 4px"/>购物车</a>
      <a class="button fl" href="<?php echo U("account@/ucenter");?>">管理中心</a>
      <?php }?>
    </div>
    <div class="menu fr">
      <ul>
        <li><a class="<?php if($inc == "site" && $act == 'index'){?>cur<?php }?>" href="<?php echo U("home@/");?>">首页</a></li>
        <li><a class="<?php if($inc == "product" && $act == 'index'){?>cur<?php }?>" href="<?php echo U("home@/product/index");?>">产品介绍</a></li>
        <li><a class="<?php if($inc == "product" && $act == 'buy'){?>cur<?php }?>" href="<?php echo U("home@/product/buy");?>">购买套餐</a></li>
        <li><a class="<?php if($inc == "helper" && ($act == 'index' || $act == 'article')){?>cur<?php }?>" href="<?php echo U("home@/helper/index");?>">技术支持</a></li>
      </ul>
    </div>
  </div>
</div>
<!--header 结束-->

<div id="dowebok">
	<div class="slide-main" id="touchMain">
		<a class="prev" href="javascript:;" stat="prev1001"><img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/focus/l-btn.png";?>" /></a>
		<div class="slide-box" id="slideContent">
			<?php $ad = tCache::read("ad_1");?>
			<?php foreach($ad as $key => $item){?>
				<div class="slide" style="background: <?php echo isset($item['bgcolor'])?$item['bgcolor']:"";?>;">
					    <?php $e = explode(',',$item['enname'])?>
						<a stat="sslink-<?php echo ($key+1);?>" href="<?php echo isset($item['linkurl'])?$item['linkurl']:"";?>" target="_blank">
							<div class="obj-<?php echo isset($e[0])?$e[0]:"";?>"><img src="<?php echo U("static@/");?><?php echo isset($item['imgurl'])?$item['imgurl']:"";?>" /></div>
							<div class="obj-<?php echo isset($e[1])?$e[1]:"";?>"><img src="<?php echo U("static@/");?><?php echo isset($item['thumburl'])?$item['thumburl']:"";?>" /></div>
						</a>
				</div>
			<?php }?>
		</div>
		<a class="next" href="javascript:;" stat="next1002"><img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/focus/r-btn.png";?>" /></a>
		<div class="item">
			<a class="cur" stat="item1001" href="javascript:;"></a><a href="javascript:;" stat="item1002"></a><a href="javascript:;" stat="item1003"></a>
		</div>
	</div>

	</div>

<div class="section section1 bg1">
	<div class="function aps">
		<h1 class="section_h1">产品功能介绍<i class="h1_line"></i></h1>
		<div class="fun-t"></div>
		<div class="fun-c">
			<div class="intro">
				<img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/fun-1.jpg";?>"/>
				<div class="intro-right">
					<a href="<?php echo U("/product/index");?>"><h3>分网络智能解析</h3></a>
					<p>根据用户上网的环境，自动将用户解析到不同机房的服务器，让您的网站更具竞争力。</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="intro">
				<img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/fun-3.jpg";?>"/>
				<div class="intro-right">
					<a href="<?php echo U("/product/index");?>"><h3>服务器宕机检测</h3></a>
					<p>自动检测服务器的状态，一旦您的服务器出现故障宕机，则域名将自动解析到备用服务器上。</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="intro">
				<img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/fun-2.jpg";?>"/>
				<div class="intro-right">
					<a href="<?php echo U("/product/index");?>"><h3>服务器负载均衡</h3></a>
					<p>通过DNS轮询的方式，将不同用户均衡的解析到不同服务器上，减少单台服务器的负载压力。</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="intro intro-nomrg">
				<img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/fun-4.jpg";?>"/>
				<div class="intro-right">
					<a href="<?php echo U("/product/index");?>"><h3>URL转发</h3></a>
					<p>URL转发功能可通过访问您的域名后直接转向到另外一个网址。</p>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="dis30"></div>
			<div class="intro">
				<img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/fun-5.jpg";?>"/>
				<div class="intro-right">
					<a href="<?php echo U("/product/index");?>"><h3>支持更多解析类型</h3></a>
					<p>8JDNS提供A、MX、CName、TXT、NS类型的解析，并且对解析记录的数目没有限制。</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="intro">
				<img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/fun-6.jpg";?>"/>
				<div class="intro-right">
					<a href="<?php echo U("/product/index");?>"><h3>分省分国家解析</h3></a>
					<p>针对大型的企业用户，您可以选择分省解析服务，将您的用户解析到不同地区国家的服务器上。</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="intro">
				<img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/fun-7.jpg";?>"/>
				<div class="intro-right">
					<a href="<?php echo U("/product/index");?>"><h3>方便好用DNS功能</h3></a>
					<p>一键保存解析修改记录,快捷的域名查找,批量的添加域名。</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="intro intro-nomrg">
				<img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/fun-8.jpg";?>"/>
				<div class="intro-right">
					<a href="<?php echo U("/product/index");?>"><h3>良好的操作体验</h3></a>
					<p>八戒DNS不断的提高用户的体验，获得了让用户称赞的操作体验</p>
				</div>
			</div>
		</div>
		<div class="dis10"></div>
		<div class="nav">
			<a class="register" href="<?php echo U("/product/index");?>">全部功能</a>
		</div>
		<div class="dis30"></div>
	</div>
</div>


<div class="section section2 bg2">
		<div class="function aps">
			<h1 class="section_h1">技术支持<i class="h1_line"></i></h1>
			<div class="fun-t"></div>
			<div class="fun-c">
				<div class="list">
					<h1><a href="<?php echo U("home@/cms/news/");?>"><span class="right">more>></span></a>最新消息</h1>
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
													<li><span class="right"><?php echo date("m-d",$item2['dateline']);?></span><a href="<?php echo U("/cms/news_topic/tid/$item2[tid]");?>"><?php echo isset($item2['subject'])?$item2['subject']:"";?></a></li>
												<?php }?>
											<?php }?>
										<?php }?>
									<?php }?>
								<?php }?>
							<?php }?>
						<?php }?>
					</ul>
					<a href="<?php echo U("home@/cms/news/");?>"><p>更多信息</p></a>
				</div>
				<div class="list">
					<h1><a href="<?php echo U("home@/helper/article");?>"><span class="right">more>></span></a>常见问题</h1>
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
														<li><span class="right"><?php echo date("m-d",$item3['dateline']);?></span><a href="<?php echo U("/helper/article?id=");?><?php echo isset($item3['fid'])?$item3['fid']:"";?>&article_id=<?php echo isset($item3['tid'])?$item3['tid']:"";?>"><?php echo isset($item3['subject'])?$item3['subject']:"";?></a></li>
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
					<a href="<?php echo U("home@/helper/article");?>"><p>更多问题</p></a>
				</div>
				<div class="list list-nomarg">
					<h1><a href="<?php echo U("home@/cms/blog/");?>"><span class="right">more>></span></a>系统博客</h1>
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
													<li><span class="right"><?php echo date("m-d",$item2['dateline']);?></span><a href="<?php echo U("/cms/topic/tid/$item2[tid]");?>"><?php echo isset($item2['subject'])?$item2['subject']:"";?></a></li>
												<?php }?>
											<?php }?>
										<?php }?>
									<?php }?>
								<?php }?>
							<?php }?>
						<?php }?>
					</ul>
					<a href="<?php echo U("home@/cms/blog/");?>"><p>更多博客</p></a>
				</div>
				<div class="dis30"></div>
			</div>
		</div>
	<div class="dis60"></div>
</div>

<div class="section section3 bg1" >
		<div class="function aps">
			<h1 class="section_h1">合作伙伴<i class="h1_line"></i></h1>
			<div class="fun-t"></div>
			<div class="fun-c">
				<div class="dis40"></div>
				<div id="wrapper">
					<div id="carousel">
						<ul>
							<?php $link2 = tCache::read("cms_friendlink_link2");?>
							<?php foreach($link2 as $key => $item){?>
							<li>
								<a href="<?php echo isset($item['link'])?$item['link']:"";?>" target="_blank"><img src="<?php echo U("static@/");?><?php echo isset($item['logo'])?$item['logo']:"";?>"/><span></span></a>
							</li>
							<?php }?>
						</ul>
						<div class="clearfix"></div>
						<!--<a id="prev" class="prev" href="#">&lt;</a>-->
						<!--<a id="next" class="next" href="#">&gt;</a>-->
					</div>
				</div>
				<div class="link-intro">
					友情链接：
					<?php $link1 = tCache::read("cms_friendlink_link1");?>
					<?php foreach($link1 as $key => $item){?>
					<?php if($item['cat_id'] == 'link1'){?>
					<a href="<?php echo isset($item['link'])?$item['link']:"";?>" target="_blank"><?php echo isset($item['name'])?$item['name']:"";?></a>&nbsp;&nbsp;&nbsp;
					<?php }?>
					<?php }?>
				</div>
			</div>
			<div class="dis20"></div>
		</div>
</div>

<div class="section section4 bg2">
	<div class="aps">
		<span><img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/gaofang.jpg";?>"/>&nbsp;300G超高防护</span>
		<i class="spanline"></i>
		<span><img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/24hour.jpg";?>"/>&nbsp;7x24小时服务</span>
		<i class="spanline"></i>
		<span><img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/wendi.jpg";?>"/>&nbsp;100%SLA稳定运行</span>
		<i class="spanline"></i>
		<span><img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/index/duoduo.jpg";?>"/>&nbsp;无限域名无限记录</span>
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
    <a href="http://www.8jdns.com/common/images/zz.jpg" target="_blank"><img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/zhebei.gif";?>" width="20px" height="20px"> <?php echo isset($site['icp'])?$site['icp']:"";?></a>&nbsp;&nbsp;
    <a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=33010802003217" target="_blank"><img src="<?php echo tUrl::create("")."skins/".$this->app."/".$this->skin."/images/batb.png";?>" width="20px" height="20px"> 浙公网安备 33010802003217号</a>
  </div>
</footer>
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
</script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/jquery.carouFredSel-6.0.4-packed.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/jqeury.focus.js");?>"></script>
<script>
$(function(){
	//图片轮播
	$('#carousel ul').carouFredSel({
		prev: '#prev',
		next: '#next',
		pagination: "#pager",
		scroll: 1000
	});
});
</script>
</body>
</html>