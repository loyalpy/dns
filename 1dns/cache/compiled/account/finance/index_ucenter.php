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
<meta http-equiv=”X-UA-Compatible” content=”IE=Edge,chrome=1″ >
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">

<link rel="stylesheet" href="<?php echo U("static/javascript/amazeui/css/amazeui.min.css");?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_uc.css";?>"></head>
<body>
<!-- topbar -->
<div class="topbar">
  <div class="aps">
    <div class="top-left-nav">
    <ul>
    <li>
    <a href="<?php echo U("home@/");?>" class="logo"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>" alt="八戒DNS" /></a>
    </li>
    <li>
      <a href="javascript:void(0)" id="navSwitch" title="帮助与支持"><i class="am-icon-bars"></i> &nbsp;</a>
    </li>
      <?php $tmplist_d =  M("domain")->query("uid = $uid","","lastupdate DESC",10)?>
      <?php if(count($tmplist_d) > 0){?>
      <li class="domain-li-d">
        <a href="javascript:void (0)" class="s">我的域名 <cite class="am-icon-caret-down"></cite></a>
        <div class="domain-li-dup">
          <table cellpadding="0" cellspacing="0" border="0" class="am-table am-table-hover">
            <thead>
            <tr>
              <th>域名</th>
              <th>是否指向</th>
              <th>统计</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($tmplist_d as $key => $item){?>
            <tr>
              <td><a href="<?php echo U("/domains/dns/");?><?php echo isset($item['domain'])?$item['domain']:"";?>" class="tr-td-a"><?php echo isset($item['domain'])?$item['domain']:"";?> <span style="color: #ccc;font-weight: 400;"><?php if($item['records'] > 0){?>(<?php echo isset($item['records'])?$item['records']:"";?>)<?php }?></span></a></td>
              <td><?php if($item['inns'] != 1){?><cite class="am-icon-exclamation-circle am-text-warning" title="域名DNS未指向我们"></cite><?php }else{?><cite class="am-icon-check-circle am-text-success" title="域名DNS已指向我们"></cite><?php }?></td>
              <td><a href="<?php echo U("/records/records_count?domain=");?><?php echo isset($item['domain'])?$item['domain']:"";?>"><cite  class="am-icon-line-chart am-icon-line-chart1"></cite></a></td>
            </tr>
            <?php }?>
            </tbody>
          </table>
          <p><a href="<?php echo U("/domains/index");?>">查看全部域名&gt;&gt;</a></p>
        </div>
      </li>
      <?php }?>
    </ul>
    </div>
    <div class="top-domain-search">
    <div class="in-search">
      <form  method="get" action="<?php echo U("domain@/domain/lists");?>" target="_blank" >
      <div class="domain-inp">
      <input type="text" class="search" name="reg_domain" value="" autocomplete="off" placeholder="" />
      </div>
      <div class="btn-buy"><button type="submit">查域名</button></div>
      </form>
    </div>
    </div>
    <div class="top-right-nav">
    <ul>
      <li>
      <a href="<?php echo U("/ucenter/profile_msg");?>" style="padding:0 10px;"><span class="am-icon-envelope-o"></span>
      <span class="am-badge am-badge-warning am-round"><?php echo M("sys_information")->get_one("recieve_uid=$uid AND status=0","count(*)");?></span></a>
      </li>
      <li>
      <a href="<?php echo U("/order/cart_shopping");?>" style="padding:0 10px;"><span class="am-icon-shopping-cart"></span>
      <span class="am-badge am-badge-warning am-round domain-parse-tips"><?php echo M("cart")->get_one("uid=$uid AND status=0 AND indel=0","count(*)");?></span>
      </a>
      </li>
      <li class="am-dropdown setting" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-user"></span> <?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?> <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="<?php if($this->userinfo['utype'] == 1){?><?php echo U("account@/ucenter/profile_basic");?><?php }else{?><?php echo U("account@/ucenter/profile_basic_com");?><?php }?>"><span class="am-icon-cog"></span> 资料</a></li>
          <li><a href="<?php echo U("account@/ucenter/safety_center");?>"><span class="am-icon-shield"></span> 安全</a></li>
          <?php if($this->userinfo['urole'] > 0){?>
          <li><a target="_blank" href="<?php echo U("admin@/");?>"><span class="am-icon-th-large"></span> 管理</a></li>
          <?php }?>
          <li><a href="<?php echo U("account@/login/logout");?>"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>
    </ul>
    </div>
  </div>
</div>
<!-- end topbar -->
<div class="mainnav <?php if(in_array($inc,array("order","finance","ucenter"))){?>mainnav-<?php }?>" id="MainNav">
  <ul class="main-ul">
  <li><a href="<?php echo U("/domains/index");?>" <?php if(in_array($inc,array("domains","records"))){?>class="cur"<?php }?>>
  <i class="am-icon-globe"></i> &nbsp;域名解析</a></li>
  <li><a href="<?php echo U("/monitor/monitor");?>" <?php if(in_array($inc,array("monitor"))){?>class="cur"<?php }?>><i class="am-icon-desktop "></i> &nbsp;域名监控</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("domain@/ucenter/index");?>"><i class="am-icon-wordpress"></i> &nbsp;域名注册</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("account@/finance/index");?>" <?php if(in_array($inc,array("finance"))){?>class="cur"<?php }?>><i class="am-icon-user"></i> &nbsp;账户</a></li>
  <li><a href="<?php echo U("account@/order/order");?>" <?php if(in_array($inc,array("order"))){?>class="cur"<?php }?>><i class="am-icon-reorder"></i> &nbsp;订单</a></li>
  <li><a href="<?php echo U("account@/ucenter/safety_center");?>" <?php if(in_array($inc,array("ucenter"))){?>class="cur"<?php }?>><i class="am-icon-gear"></i> &nbsp;设置</a></li>
  </ul>
</div>
<div class="am-uc-left">
	<div class="leftnav" id="Leftnav">
		<ul>
			<li><a href="<?php echo U("/finance/index");?>"  class="cur">账户首页&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/recharge");?>">在线充值&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="javascript:void(0);" class="sms_recharge_btn"  >短信充值&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/recharge_detail");?>" >收支明细&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/coupon");?>">代金券&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/tg");?>">推广中心&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
		</ul>
	</div>
</div>
<div class="am-uc-right am-uc-right-index">

	<?php $ad = tCache::read("ad_5");$time = time();?>
	<?php if(count($ad) > 0){?>
		<?php foreach($ad as $key => $item){?>
		<?php if($time > $item['start_dateline'] && $time < $item['end_dateline']){?>
		<?php if(M("domain")->get_one("uid  = '$uid'","count('domain')") > 0){?>
		<a href="<?php echo isset($item['linkurl'])?$item['linkurl']:"";?>" ><img src="<?php echo U("static@/$item[imgurl]");?>"  style="cursor: pointer"/></a>
		<?php }else{?>
		<a href="<?php echo U("/domains/add");?>"><img src="<?php echo U("static@/$item[imgurl]");?>"/></a>
		<?php }?>
		<?php }?>
	<?php }?>
	<?php }?>
	<div class="dis10"></div>
	<div>
		<h1><a href="<?php if($this->userinfo['utype'] >1){?>/ucenter/profile_basic_com<?php }else{?>/ucenter/profile_basic<?php }?>" class="list_tit_name">我是<?php if($this->userinfo['utype'] == 3){?>代理会员
		<?php }elseif($this->userinfo['utype'] == 2){?>企业<?php }else{?>个人<?php }?></a>
		<font class="font-org f14">(<?php echo isset($this->userinfo['setting']['ulevel_name'])?$this->userinfo['setting']['ulevel_name']:"";?>)</font>
		</h1>
	</div>
	<hr/>
	<div class="dis10"></div>
	<div class="am-finance-content">
		<div class="finance-new">
			<a href="<?php echo U("account@/domains/index");?>">
			<i class="am-icon-lg am-icon-globe am-text-primary"></i>
			<h3>域名解析数</h3>
			<p class="finance-new-num">
				<span class="am-text-warning"><?php echo isset($this->userinfo['account']['domains'])?$this->userinfo['account']['domains']:"";?></span> 个
			</p>
			</a>
		</div>
		<div class="finance-new">
			<a href="<?php echo U("domain@/ucenter/index");?>">
			<i class=" am-icon-wordpress am-text-primary am-icon-lg"></i>
			<h3>域名注册数</h3>
			<p class="finance-new-num">
				<span class="am-text-warning"><?php echo isset($this->userinfo['account']['register_domains'])?$this->userinfo['account']['register_domains']:"";?></span> 个
			</p>
			</a>
		</div>
		<div class="finance-new">
			<a href="<?php echo U("account@/monitor/monitor");?>">
			<i class="am-icon-lg am-icon-desktop  am-text-primary"></i>
			<h3>监控数</h3>
			<p class="finance-new-num ">
				<span class="am-text-warning"><?php echo isset($domainJNum)?$domainJNum:"";?></span> 条
			</p>
			</a>
		</div>

		<div class="finance-new">
			<a href="<?php echo U("account@/order/order");?>">
			<i class="am-icon-lg am-icon-th-list am-text-primary"></i>
			<h3>域名解析订单<?php if(isset($this->userinfo['setting']['rate'])){?><span class="font-org">(<?php if($this->userinfo['setting']['rate']>=0 && $this->userinfo['setting']['rate'] < 100){?><?php echo $this->userinfo['setting']['rate']/10;?>折<?php }else{?>无折扣<?php }?>)</span><?php }?></h3>
			<p class="finance-new-num">
				<span class="am-text-warning"><?php echo M("order")->get_one("uid=$uid AND status>0","count(order_no)");?></span> 个
			</p>
			</a>
		</div>
		<div class="finance-new">
			<a href="<?php echo U("domain@/ucenter/order");?>">
			<i class=" am-icon-th am-text-primary am-icon-lg"></i>
			<h3>域名注册订单<?php if(isset($this->userinfo['setting']['regrate'])){?><span class="font-org">(<?php if($this->userinfo['setting']['regrate']>=0 && $this->userinfo['setting']['regrate'] < 100){?><?php echo $this->userinfo['setting']['regrate']/10;?>折<?php }else{?>无折扣<?php }?>)</span><?php }?></h3>
			<p class="finance-new-num">
				<span class="am-text-warning"><?php echo M("register_order")->get_one("uid=$uid AND status=4","count(order_no)");?></span> 个
			</p>
			</a>
		</div>
		<div class="finance-new">
			<a href="<?php echo U("account@/finance/recharge?type=2");?>">
			<i class="am-icon-lg am-icon-dollar  am-text-primary"></i>
			<h3>充值订单</h3>
			<p class="finance-new-num ">
				<span class="am-text-warning"><?php echo M("recharge")->get_one("uid=$uid AND status=1","count(id)");?></span> 条
			</p>
			</a>
		</div>

		<div class="finance-new">
			<a href="<?php echo U("/finance/recharge");?>">
				<i class="am-icon-lg am-icon-yen am-text-primary"></i>
				<h3>账户余额</h3>
				<p class="finance-new-num">
					<span class="am-text-warning"><?php echo isset($this->userinfo['account']['balance'])?$this->userinfo['account']['balance']:"";?></span>元
				</p>
			</a>
		</div>
		<div class="finance-new">
			<a href="<?php echo U("/finance/recharge_detail?type=2");?>">
			<i class="am-icon-lg am-icon-envelope-o am-text-primary"></i>
			<h3>短信条数</h3>
			<p class="finance-new-num">
				<span class="am-text-warning"><?php echo isset($this->userinfo['account']['sms'])?$this->userinfo['account']['sms']:"";?></span>条
			</p>
			</a>
		</div>
		<div class="finance-new">
			<a href="<?php echo U("/finance/recharge_detail?type=3");?>">
			<i class="am-icon-lg am-icon-bookmark-o am-text-primary"></i>
			<h3>账户积分</h3>
			<p class="finance-new-num">
				<span class="am-text-warning"><?php echo isset($this->userinfo['account']['point'])?$this->userinfo['account']['point']:"";?></span>
			</p>
			</a>
		</div>
		
		<div class="clear"></div>
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
<div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
    <a href="#top" title="">
          <i class="am-gotop-icon am-icon-chevron-up"></i>
    </a>
</div>
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<!--[if lte IE 8 ]>
<script src="<?php echo U("static/javascript/amazeui/js/modernizr.js");?>"></script>
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.ie8polyfill.min.js");?>"></script>
<![endif]-->
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.min.js");?>"></script>
<script src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<script language="javascript">
  var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>
$(function(){
  $("#navSwitch").bind("click",function(){
    if($("#MainNav").is(':hidden')){
        $("body").css({"padding-left":"198px"});
        $("#MainNav").show();
    }else{
        $("body").css({"padding-left":"10px"});
        $("#MainNav").hide();
    }
  });
  //鼠标经过显示多个域名
  $("li.domain-li-d").find("a.s,.domain-li-dup").hover(function(){
    $(this).addClass("hover");
    $(".domain-li-d").find(".domain-li-dup").show();
  }, function(){
    $(this).removeClass("hover");
    $(".domain-li-d").find(".domain-li-dup").hide();
  });
})
</script>
<script type="text/javascript">
  $(function(){
    $("#Dfloatdiv").fadeIn();
    $("#Dfloatdiv").find(".item").hover(function(){
            var sIn_obj = $(this).find(".in");
            $(this).addClass("item-over");
            sIn_obj.stop(true,false).animate({"width":sIn_obj.attr("_w")},300);
          },function(){
            $(this).removeClass("item-over");
            $(this).find(".in").stop(true,false).animate({"width":0},300);
          }
    );
  })

</script>
<?php echo $this->fetch('finance/sms_recharge')?>
<script type="text/javascript">
	$(function(){
		$("a,button").bind("focus",function(){
			$(this).blur();
		});
	});
</script>
</body>
</html>