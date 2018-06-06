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
<div class="news-wrap">
	<div class="artview">
		<!--sidebar-->
		<div class="sidebar">
			<h1><?php echo isset($data['threads']['subject'])?$data['threads']['subject']:"";?></h1>
			<div class="review">
				<?php foreach($data['list'] as $key => $item){?>
				<?php if($item['first'] == 1){?>
				<div class="threads_html_box"><?php echo htmlspecialchars_decode($item['message']);?></div>
				<?php }?>
				<?php }?>
			</div>
			<div class="dis20"></div>
			<div class="artinfo">
				<!--<span>来源：<?php echo isset($site['site_name'])?$site['site_name']:"";?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span>时间：<?php echo tTime::get_datetime('Y年m月d日 H:i',$data['threads']['dateline']);?></span>-->
				<span>来源：<?php echo isset($site['site_name'])?$site['site_name']:"";?></span>
				<?php if($data['list']){?>
					<?php foreach($data['list'] as $key => $item){?>
					<?php if($item['first'] == 1){?>
					<ul class="share">
						<li>分享到：</li>
						<li class="sina"><a href="javascript:void(0);"  onclick="return go_share(this,'sina');" target="_blank" title="<?php echo isset($item['subject'])?$item['subject']:"";?>"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/index/sina.jpg";?>"></a></li>
						<li class="tengx"><a  href="javascript:void(0);"  onclick="return go_share(this,'tengx');" target="_blank" title="<?php echo isset($item['subject'])?$item['subject']:"";?>"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/index/tengx.jpg";?>"></a></li>
						<li class="kaixin"><a  href="javascript:void(0);"  onclick="return go_share(this,'kaixin');" target="_blank" title="<?php echo isset($item['subject'])?$item['subject']:"";?>"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/index/kaixin.jpg";?>"></a></li>
						<li class="douban"><a  href="javascript:void(0);"  onclick="return go_share(this,'douban');" target="_blank" title="<?php echo isset($item['subject'])?$item['subject']:"";?>"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/index/douban.jpg";?>"></a></li>
						<li class="sohu"><a  href="javascript:void(0);" onclick="return go_share(this,'sohu');" target="_blank" title="<?php echo isset($item['subject'])?$item['subject']:"";?>"><img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/index/sohu.jpg";?>"></a></li>
					</ul>
					<?php }?>
					<?php }?>
				<?php }?>
			</div>
			<div class="dis20"></div>
			<div class="pnlink">
				<?php if($prevnext['prev'] || $prevnext['next']){?>
				<div class="prevnext">
					<?php if($prevnext['prev']){?>
					<p class="f14"><span class="txt-gray">下一篇：</span><a class="font-blue" href="<?php echo U('/cms/topic/tid/'.$prevnext['prev']['tid']);?>" title="<?php echo isset($prevnext['prev']['subject'])?$prevnext['prev']['subject']:"";?>"><?php echo isset($prevnext['prev']['subject'])?$prevnext['prev']['subject']:"";?></a></p>
					<?php }?>
					<?php if($prevnext['next']){?>
					<p class="f14"><span class="txt-gray">上一篇：</span><a class="font-blue" href="<?php echo U('/cms/topic/tid/'.$prevnext['next']['tid']);?>" title="<?php echo isset($prevnext['next']['subject'])?$prevnext['next']['subject']:"";?>"><?php echo isset($prevnext['next']['subject'])?$prevnext['next']['subject']:"";?></a></p>
					<?php }?>
				</div>
				<?php }?>
			</div>
			<div class="dis40"></div>
			<div class="reply">
				<h5><span></span>(<?php echo count($blog);?>)最新回复</h5>
				<?php if(count($blog)>0){?>
					<?php foreach($blog as $key => $item){?>
						<div class="con">
							<div class="advator"><img src="<?php if($item['attachment']){?><?php echo isset($item['attachment'])?$item['attachment']:"";?><?php }else{?><?php echo U("")."skins/".$this->app."/".$this->skin."/images/index/advator.jpg";?><?php }?>"/></div>
							<div class="contenta">
								<div class="t1">
									<div class="t1l"><span class="name"><?php echo isset($item['uname'])?$item['uname']:"";?></span> <span class="del" data-pid="<?php echo isset($item['pid'])?$item['pid']:"";?>">删除</span></div>
									<div class="t1r"><?php echo $key+1;?>F</div>
								</div>
								<div class="dis10"></div>
								<div class="t2"><?php echo isset($item['message'])?$item['message']:"";?></div>
								<div class="dis10"></div>
								<div class="t3"><?php echo isset($item['dateline'])?$item['dateline']:"";?></div>
							</div>
							<div class="dis20"></div>
						</div>
					<?php }?>
				<?php }?>
				<div class="appen"></div>
			</div>
			<div class="dis20"></div>
			<div class="bott">
				<p>走过路过，留下点干粮 (请登录发言，并遵守相关规定) <span class="n"><?php if($uid){?><?php echo M("user")->get_one("uid=$uid","uname");?><?php }?></span></p>
				<p>
					<textarea name="message" id="message"></textarea>
					<input type="hidden" name="pid" value="<?php if($data['list']){?><?php foreach($data['list'] as $key => $item){?><?php if($item['first'] == 1){?><?php echo isset($item['pid'])?$item['pid']:"";?><?php }?><?php }?><?php }?>">
				</p>
				<p class="h">回复 </p>
				<div class="dis10"></div>
			</div>
		</div>
		<!--sidebar end-->
		<!--mainbar-->
		<div class="mainbar">
			<div class="search-form">
				<form action="<?php echo U("/cms/blog/fid/$fid");?>" method="POST">
					<input type="hidden" name="hash" value="<?php echo tUtil::hash();?>" />
					<img class="img" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/helper/search.jpg";?>">
					<input type="text" name="keyword" value="<?php if(isset($keyword)){?><?php echo isset($keyword)?$keyword:"";?><?php }else{?><?php }?>"  class="key" />
					<button type="submit" style="display: none">提交</button>
				</form>
			</div>
			<div class="dis30"></div>
			<h1>官方博客目录</h1>
			<div class="dis10"></div>
			<div class="cmslist-nav">
				<ul>
					<?php foreach($cates as $key => $item){?>
					<li><a class="<?php if(isset($cate['id']) && $cate['id'] == $item['id']){?>curr<?php }?>" href="<?php echo U("/cms/blog/fid/$item[id]");?>"><?php echo isset($item['name'])?$item['name']:"";?><font class="font-org">(<?php echo isset($item['threads'])?$item['threads']:"";?>)</font></font></a></li>
					<?php }?>
				</ul>
			</div>
			<div class="dis30"></div>
			<h1>推荐</h1>
			<div class="dis10"></div>
			<div class="siderand">
				<?php if($tuinews){?>
				<ul>
					<?php foreach($tuinews as $key => $item){?>
					<li><a href="<?php echo U("/cms/topic/tid/$item[tid]");?>" title="<?php echo isset($item['subject'])?$item['subject']:"";?>" ><?php echo isset($item['subject'])?$item['subject']:"";?></a></li>
					<?php }?>
				</ul>
				<?php }?>
			</div>
		</div>
		<!--mainbar end-->
</div>
	<div class="dis60"></div>
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
<script language="javascript" src="<?php echo U("static@/javascript/utils/share.js");?>"></script>
<script type="text/javascript">
	var uid = "<?php echo isset($uid)?$uid:"";?>";
	$(function(){
		$(".img").click(function(){
			$("button").click();
		});
		var frm_submit=false;   //纪录提交状态,js端防止重复提交
		//回复博客
		$(".h").click(function(){
			var msg = $(this).parent().find("#message").val();
			var pid = $("input[name='pid']").val();

			if (uid == 0) {
				alert("您未登录，请先登录！")
				return false;
			}
			if (frm_submit==true) {
				alert("请勿重复提交!");
				return false;
			}
			if ($.is_empty(msg) || msg.length<2) {
				alert("内容不能为空或者太短！");
				return false;
			}
			$.ajaxPassport({
				url:"<?php echo U("cms/blog_reply");?>",
				success:function(res){
					if (res.error == 1) {
						alert(res.message);
					}else{
						var html = "<div class='con'> " +
												"<div class='advator'><img src='<?php echo U("")."skins/".$this->app."/".$this->skin."/images/index/advator.jpg";?>'/></div>" +
												"<div class='contenta'>" +
													"<div class='t1'>" +
														"<div class='t1l'><span class='name'><?php echo isset($uname)?$uname:"";?></span> </div>" +
														"<div class='t1r'></div>" +
													"</div>" +
													"<div class='dis10'></div>" +
													"<div class='t2'>"+msg+"</div>" +
													"<div class='dis10'></div>" +
													"<div class='t3'>1秒前</div>" +
												"</div>" +
												"<div class='dis20'></div>" +
										"</div>";
						$(".appen").append(html);
						$("#message").val('');
						frm_submit=true;  //改变提交状态
					}
				},
				data:{message:msg,pid:pid}
			});
		});
		//删除博客
		$(".del").click(function(){
			var pid = $(this).data("pid");
			var obj = this;
			$.ajaxPassport({
				url:"<?php echo U("cms/blog_del");?>",
				success:function(res){
					if (res.error == 1) {
						alert(res.message);
					}else{
						$(obj).parent().parent().parent().parent().remove();
					}
				},
				data:{pid:pid}
			});
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