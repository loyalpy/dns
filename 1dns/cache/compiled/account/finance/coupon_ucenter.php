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
			<li><a href="<?php echo U("/finance/index");?>">账户首页&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/recharge");?>" >在线充值&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="javascript:void(0);"  class="sms_recharge_btn">短信充值&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/recharge_detail");?>">收支明细&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/coupon");?>"  class="cur">代金券&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/tg");?>">推广中心&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
		</ul>
	</div>
</div>
<div class="am-uc-right">
	<div>
		<h1><span class="list_tit_name">我的代金券</span></h1>
	</div>
	<div class="am-finance-list-key" style="margin-left: 0px;">
		<span>状态：</span>
		<select name="status" class="s-status" style="width: 100px;height: 33px;font-size: 14px;line-height: 33px;">
			<option value="0">全部</option>
			<option value="2">已使用</option>
			<option value="1">未使用</option>
		</select>
	</div>
	<div class="am-finance-list-key" style="margin-left: 80px;">
		<input type="text"  name="keyword" class="am-form-field  am-input-sm" placeholder="请输入关键词" size="35"/>
	</div>
	<div class="am-finance-list-key">
		<button type="button" class="am-btn am-btn-success am-radius am-btn-sm finance-serch">搜索</button>
	</div>
	<div class="dis20" style="margin-bottom: -10px;"></div>
	<div class="am-finance-content">	</div>
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
		load_account_list(1);
		//搜索事件
		$(".finance-serch").unbind("click").bind("click",function(){
			var keyword  = $("input[name='keyword']").val();
			var status = $(".s-status").val();
			load_account_list(1,keyword,status);
		});
	});
</script>
<!--代金券明细-->
<script type="text/template" id="tpl_accountlog_list">
	<#macro rowedit data>
		<table class="am-table">
			<col width="180"/>
			<col />
			<col width="110"/>
			<col width="130"/>
			<col width="100"/>
			<col width="130"/>
			<col width="80"/>
			<thead>
			<tr>
				<th>代金券编码</th>
				<th>名称</th>
				<th>面值(元)</th>
				<th>使用范围</th>
				<th>状态</th>
				<th>有效期至</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<#if (data.list.length > 0)>
				<#list data.list as account>
					<tr>
						<td><font style="color: darkgray">${account.code}</font></td>
						<td>${account.name}</td>
						<td><font color="green">${account.balance}</font></td>
						<td ><#if (account.type==1)>通用<#elseif (account.type == 2)>域名解析专用<#elseif (account.type == 3)>域名注册专用<#else> - </#if></td>
						<td><#if (account.status==0)><font style="color: green">未使用</font><#else><font style="color: gray;">已使用</font></#if></td>
						<td><font style="color: gray">${$.time_to_string(account.expiry,"Y-m-d")}</font></td>
						<td><font style="color: gray"> - </font></td>
					<tr>
				</#list>
				<#else>
					<tr><td colspan="6"><a href="##" class="am-icon-exclamation-circle am-text-danger am-text-lg"></a> <a href="##" class="am-font-gray">没有符合条件的结果?</a></td></tr>
			</#if>
			</tbody>
		</table>
		<div class="pagebar">${data.pagebar}</div>
	</#macro>
</script>
<script type="text/javascript">
	var load_account_list = function(page,keyword,status){
		var url = "<?php echo U("/finance/coupon?do=get");?>";
		var keyword  = $.is_empty(keyword)?'':keyword;
		var status      = $.is_empty(status)?0:status;
		$.ui.loading($(".am-table"));
		$.ajaxPassport({
			url:url,
			success:function(res){
				$.ui.close_loading($(".am-table"));
				var listhtml = ""+ easyTemplate($("#tpl_accountlog_list").html(),res.data);
				$(".am-finance-content").html(listhtml);

				$("button,a").bind("focus",function(){
					$(this).blur();
				});

			},
			data:{page:page,keyword:keyword,status:status},
		});
	}
</script>
</body>
</html>