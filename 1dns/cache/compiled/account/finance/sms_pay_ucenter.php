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
    <li><a href="javascript:void(0)" id="navSwitch" title="帮助与支持"><i class="am-icon-bars"></i> &nbsp;导航</a></li>
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
      <span class="am-badge am-badge-warning am-round"><?php echo M("cart")->get_one("uid=$uid AND status=0 AND indel=0","count(*)");?></span>
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
<div class="mainnav <?php if(in_array($inc,array("order","finance","ucenter"))){?>mainnav-white<?php }?>" id="MainNav">
  <ul class="main-ul">
  <li><a href="<?php echo U("/domains/index");?>" <?php if(in_array($inc,array("domains","records"))){?>class="cur"<?php }?>>
  <i class="am-icon-globe"></i> &nbsp;域名解析</a></li>
  <li><a href="<?php echo U("/monitor/monitor");?>" <?php if(in_array($inc,array("monitor"))){?>class="cur"<?php }?>><i class="am-icon-desktop "></i> &nbsp;域名监控</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("domain@/ucenter/index");?>"><i class="am-icon-wordpress"></i> &nbsp;域名注册</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("account@/order/order");?>" <?php if(in_array($inc,array("order"))){?>class="cur"<?php }?>><i class="am-icon-reorder"></i> &nbsp;订单</a></li>
  <li><a href="<?php echo U("account@/finance/index");?>" <?php if(in_array($inc,array("finance"))){?>class="cur"<?php }?>><i class="am-icon-user"></i> &nbsp;账户</a></li>
  <li><a href="<?php echo U("account@/ucenter/safety_center");?>" <?php if(in_array($inc,array("ucenter"))){?>class="cur"<?php }?>><i class="am-icon-gear"></i> &nbsp;设置</a></li>
  </ul>
</div>
<div class="am-uc-left">
	<div class="leftnav" id="Leftnav">
		<ul>
			<li><a href="<?php echo U("/finance/index");?>">账户首页&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/recharge");?>">在线充值&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/sms_pay");?>"   class="cur">短信充值&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/recharge_detail");?>"  >收支明细&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
		</ul>
	</div>
</div>
<div class="am-uc-right">
	<div>
		<h1><span class="list_tit_name">短信充值</span></h1>
	</div>
	<button type="button" class="am-btn am-btn-success am-radius am-btn-sm buy-sms"><span class="am-icon-plus"></span> 短信充值</button><br/>
	<div class="dis10"></div>
</div>
<div class="my-domian-upgrade"></div>
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
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.min.js");?>"></script>
<script src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
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
})
</script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script type="text/javascript">
	$(function(){
		//升级套餐
		add_cart_step1();
		$(".buy-sms").unbind("click").bind("click",function(){
			var goods_name = $(this).attr("data-domain");
			add_cart_step1(1,0,"",goods_name);
		});
	});
</script>
<!--域名升级套餐-->
<script type="text/template" id="tpl_domain_upgrade">
	<#macro rowedit data>
		<div class="cartbox">
			<div class="bd-middle am-form">
				<div class="dis10"></div>
				<h1>短信充值</h1>
				<div class="dis10"></div>
				<table class="am-table am-table-hover buy-sms-table">
					<col />
					<col width="100px;"/>
					<col width="150px;"/>
					<col width="120px;"/>
					<thead>
					<tr>
						<th>增值服务</th>
						<th>单价</th>
						<th>数量</th>
						<th>总费用</th>
					</tr>
					</thead>
					<tbody class="add-sms-list">
					<tr>
						<td><span class="zeng" style="color: #0E90D2">短信100条</span></td>
						<td><span class="sms-price">20</span>元</td>
						<td>
							<select name="sms-num" >
								<?php foreach(array(1,2,3,4,5,6,7,8,9,10) as $key => $item){?>
								<option value="<?php echo isset($item)?$item:"";?>"><?php echo isset($item)?$item:"";?></option>
								<?php }?>
							</select>
						</td>
						<td><span class="sms-total">20.00</span>元</td>
					</tr>
					</tbody>
				</table>
				<div class="dis30"></div>
				<div class="am-text-center">
					<button type="button" class="am-btn am-btn-warning btn-sumit-add-cart">加入购物车</button>&nbsp;&nbsp;&nbsp;
					<a type="button" class="am-btn am-btn-default " href="<?php echo U("/finance/index");?>">取消</a>
				</div>
				<div class="dis15"></div>
			</div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	//加入购物车
	var add_cart_step1 = function(){
		var html = ""+ easyTemplate($("#tpl_domain_upgrade").html());
		$.ui.open(html,function(){
			$.ui.open_close();
			window.location.replace("<?php echo U("/finance/index");?>");
			return false;
		},650,400);
		//改变短信套餐数量事件
		$(".add-sms-list tr select").unbind("change").bind("change",function(){
			var num = $(".add-sms-list tr").find("select[name='sms-num']").val();
			var service_price = $(".add-sms-list tr").find(".sms-price").text();
			var total  = $.to_float2(parseInt(num) *parseFloat(service_price));

			$(".add-sms-list tr").find(".sms-total").html(total);
		});
		//提交购物车事件
		$(".btn-sumit-add-cart").unbind("click").bind("click",function(){
			add_cart_step2();
		})
	}
	//添加增值短信服务
	var add_cart_step2 = function(){
		var sms_service_group = $(".add-sms-list tr").find(".zeng").text();
		var sms_num = $(".add-sms-list tr").find("select[name='sms-num']").val();
		var sms_price = $(".add-sms-list tr").find(".sms-price").text();
		$.ajaxPassport({
			url:U("/api/Cart.Add"),
			success: function (res) {
				if (res.status == 1) {
					$.ui.open_close();
					window.location.replace("<?php echo U("account@/order/cart_shopping");?>");
				}else{
					$.ui.error(res.msg);
				}
			},
			data:{type:1,service_group:sms_service_group,num:sms_num,price:sms_price}
		});
	};
</script>
</body>
</html>