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
<div class="dis10"></div>
<div class="order-shopping">
	<div class="nav">
		<ul>
			<li class="un-use use1">
				<div class="icon">
					<span style="border: 1px solid #1BA208"><i class="am-icon-check" style="color:#1BA208;"></i></span>
					<font style="color:#1BA208;">查看购物车</font>
					<i class="am-icon-angle-right am-icon-lg" style="color:#E2E2E3;"></i>
				</div>
			</li>
			<li class="un-use use2">
				<div class="icon">
					<span style="border: 1px solid #1BA208"><i class="am-icon-check" style="color:#1BA208;"></i></span>
					<font style="color: #1BA208;">确认订单</font>
					<i class="am-icon-angle-right am-icon-lg" style="color:#E2E2E3;"></i>
				</div>
			</li>
			<li class="un-use use3">
				<div class="icon">
					<span style="border: 1px solid #1BA208;"><i class="am-icon-check" style="color:#1BA208;"></i></span>
					<font style="color:#1BA208;">支付</font>
					<i class="am-icon-angle-right am-icon-lg" style="color:#E2E2E3;"></i>
				</div>
			</li>
			<?php if($orderinfo['pay_status'] == 1){?>
			<li class="un-use use4">
				<div class="icon">
					<span style="border: 1px solid #1BA208;"><i class="am-icon-check" style="color:#1BA208;"></i></span>
					<font style="position: relative;top:3px;color:#1BA208;">支付完成</font>
				</div>
			</li>
			<?php }else{?>
			<li class="un-use use4">
				<div class="icon">
					<span style="color:gray;">4</span>
					<font style="position: relative;top:3px;color:gray;">支付完成</font>
				</div>
			</li>
			<?php }?>

		</ul>
		<div class="dis10"></div>
	</div>
	<div class="content">
		<div class="dis30"></div>
		<div class="content-shop">
			<div class="order-detail">
				<div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 20px;">订单详情</div>
				<div class="dis10"></div>
				<span style="color: gray;margin-left: 12px;">购买域名解析等 <?php echo count($orderinfo['order_item']);?> 件商品 共<font style="color: #FA7821;font-size: 18px;"><?php echo isset($orderinfo['amount'])?$orderinfo['amount']:"";?></font>元</span><span style="color: #0077CC;margin-left: 50px;cursor:pointer" class="detail-show">查看详情 <i class="am-icon-angle-down"></i></span>
				<div class="dis10"></div>
				<table class="am-table am-table-bordered table-detail"  style="margin-left: 10px;">
					<thead>
					<tr style="background-color: #F6F8FA">
						<th>订单编号</th>
						<th>产品名称</th>
						<th>产品内容</th>
						<th>数量</th>
						<th>金额</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($orderinfo['order_item'] as $key => $item){?>
					<tr>
						<?php if($key == 0){?>
						<td rowspan="<?php echo count($orderinfo['order_item']);?>" class="am-text-middle"><?php echo isset($orderinfo['order_no'])?$orderinfo['order_no']:"";?></td>
						<?php }?>
						<td>
							<?php if($item['type'] == 0){?>域名服务套餐
							<?php }else{?>短信服务套餐
							<?php }?>
						</td>
						<td><?php if(empty($item['goods_name'])){?><?php echo isset($item['goods_no'])?$item['goods_no']:"";?><?php }else{?><?php echo isset($item['goods_name'])?$item['goods_name']:"";?> (<?php echo isset($domain_service[$item['goods_no']])?$domain_service[$item['goods_no']]['name']:"免费版";?>)<?php }?></td>
						<td><?php if(empty($item['goods_name'])){?><?php echo isset($item['num'])?$item['num']:"";?><?php }else{?><?php if($item['num']<10){?><?php echo isset($item['num'])?$item['num']:"";?>个月<?php }else{?><?php echo $item['num']/10;?>年<?php }?><?php }?></td>
						<td><font class="am-text-warning"><?php echo isset($item['amount'])?$item['amount']:"";?> </font> 元</td>
					</tr>
					<?php }?>
					</tbody>
				</table>
			</div>

			<?php if($orderinfo['amount_coupon'] > 0){?>
			<div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 18px">代金券</div>
			<div class="dis10"></div>
			<div class="coupon-c">
				代金券共优惠<font class="am-text-warning"><?php echo isset($orderinfo['amount_coupon'])?$orderinfo['amount_coupon']:"";?></font>元
			</div>
			<?php }?>

			<div class="dis30"></div>
			<div class="order-type">
				<div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 20px;display: none;">支付方式</div>
				<div class="dis10"></div>
				<div class="records-nav profile_information" >
					<ul>
						<li class="yu cur"><a href="javascript:void (0);">支付方式</a></li>
						<li class="outline"><a href="javascript:void (0);" style="display:none">支付宝在线支付</a></li>
					</ul>
				</div>
				<div class="dis10"></div>
				<div style="margin-left: -5px;" class="type-yu">
					<div class="dis10"></div>
					<div>
						<ul class="paysel">
						<?php $payment_list = C("payment")->get_payment();?>
						<?php if($orderinfo['pay_status'] == 0){?>
							<?php if($orderinfo['status'] == 0){?>
                            <span class="am-text-danger">订单已取消</span>
                            <?php }else{?>
							<li><input type="radio"  value="0" name="paytype"  <?php if($this->userinfo['account']['balance'] < $orderinfo['amount']){?>disabled<?php }else{?>checked="checked"<?php }?> /> 余额支付   <span style="color:#999;">您当前余额:<font class="am-text-warning"><?php echo isset($this->userinfo['account']['balance'])?$this->userinfo['account']['balance']:"";?></font></span> 

							<?php if($this->userinfo['account']['balance'] < $orderinfo['amount']){?>
							&nbsp;&nbsp;您的余额不足,请<a href="<?php echo U("/finance/recharge?");?>order_no=<?php echo isset($orderinfo['order_no'])?$orderinfo['order_no']:"";?>&order_type=order&balance=<?php echo ($orderinfo['amount']-$this->userinfo['account']['balance']);?>"> <b>立即充值</b></a>
							<?php }?>
							</li>

							<?php $kkey = 0;?>
							<?php foreach($payment_list as $key => $item){?>
							<?php if($item['id']!=7){?>
							<li><input type="radio" <?php if($this->userinfo['account']['balance'] < $orderinfo['amount'] && $kkey==0){?>checked="checked"<?php }?> value="<?php echo isset($item['id'])?$item['id']:"";?>" name="paytype"  /> <img src="<?php echo U("static@/public/images");?><?php echo isset($item['logo'])?$item['logo']:"";?>" alt="<?php echo isset($item['name'])?$item['name']:"";?>" /> </li>
							<?php }?>
							<?php $kkey = $kkey +1;?>
							<?php }?>
							<?php }?>
						<?php }elseif($orderinfo['pay_status'] == 2){?>
                            <li><span class="text-danger">已退款余额</span> <a href="/finance/recharge_detail" class="text-primary">查看资金</a></li>
						<?php }else{?>
						   <li>
						   <?php if(empty($orderinfo['pay_type'])){?>
						   <span class="am-text-success">余额支付</span>
						   <?php }else{?>
							   <?php foreach($payment_list as $key => $item){?>
							   <?php if($orderinfo['pay_type'] == $item['id']){?>
							   <img src="<?php echo U("static@/public/images");?><?php echo isset($item['logo'])?$item['logo']:"";?>" />  <span class="am-text-success"><?php echo isset($item['name'])?$item['name']:"";?></span>
							   <?php }?>
							   <?php }?>
						   <?php }?>
						   </li>
						<?php }?>
						</ul>
					</div>
				</div>
				<div class="dis10"></div>
			</div>
			</div>
			<div class="dis30"></div>
			<div class="order-pay" style="border-top: 1px solid silver;text-align: right;">
				<div class="dis10"></div>
				订单商品：<span style="color: #FA7821;font-size: 16px;"><strong>  <?php echo count($orderinfo['order_item']);?> </strong></span>件&nbsp;&nbsp;&nbsp;应付总价：<span class="total-sum" style="color: #FA7821;font-size: 34px;"><strong><?php echo isset($orderinfo['amount'])?$orderinfo['amount']:"";?></strong></span>元&nbsp;&nbsp;&nbsp;&nbsp;
				<?php if($orderinfo['pay_status'] == 0){?>
					<?php if($orderinfo['status'] == 0){?>
					<span class="am-text-danger">已取消订单</span>
					<?php }else{?>
					<button type="button" class="am-btn am-btn-warning am-radius am-btn-sm btn-pay" style="position: relative;top:-12px;">立即支付</button>
					<?php }?>
				<?php }elseif($orderinfo['pay_status'] == 1){?>
				    <span class="am-text-success" style="">订单已支付,支付时间:<?php echo tTime::get_datetime('Y-m-d H:i:s',$orderinfo['pay_dateline']);?></span>
				    <?php if(($orderinfo['send_status'] == 0)){?>
					<script src="<?php echo U("/order/order_retry_send?order_no");?>=<?php echo isset($orderinfo['order_no'])?$orderinfo['order_no']:"";?>" language="JavaScript"></script>
					<?php }else{?>
                      <span class="am-text-primary">订单已完成</span>
					<?php }?>
				<?php }else{$orderinfo['pay_status'] == 2?>
                    <span class="am-text-danger">已退款余额</span> <a href="/finance/recharge_detail" class="font-blue">查看资金</a>
                <?php }?>
				<br/>
			</div>
			<div class="my-pay-success"></div>
		</div>
	</div>
</div>
<div class="dis30"></div>
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
<!--支付成功-->
<script type="text/template" id="tpl_pay_success">
	<#macro rowedit data>
		<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
			<div class="am-modal-dialog">
				<div class="am-modal-hd">
					<i class="am-icon-check-circle" style="color: #5EB95E;"></i>&nbsp;&nbsp;恭喜您，支付成功！
					<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
				</div>
				<div class="dis20"></div>
				<div class="am-modal-bd">
					<a href="<?php echo U("/domains");?>" ><button type="button" class="am-btn am-btn-default">返回域名列表</button></a>&nbsp;&nbsp;&nbsp;
					<a href="<?php echo U("/order/order");?>"><button type="button" class="am-btn am-btn-warning">查看订单列表</button></a>
				</div>
				<div class="dis30"></div>
			</div>
		</div>
	</#macro>
</script>
<script type="text/javascript">
	$(function(){
			$(".btn-pay").unbind("click").bind("click",function(){
				var order_no =" <?php echo isset($orderinfo['order_no'])?$orderinfo['order_no']:"";?>";
				var paytype = $("input[name='paytype']:checked").val();
				if (typeof paytype == "undefined") {
					$.ui.error("请选择支付方式！");
					return false;
				}
				$.ui.loading();
				$.ajaxPassport({
					url:"<?php echo U("/api/Order.Pay");?>",
					data:{order_no:order_no,pay_id:paytype},
					success:function(res){
						$.ui.close_loading();
						if (res.status == 1) {
							if(!$.is_empty(res.data.surl)){
								$.ui.success(res.msg);
								setTimeout(function(){
									$.redirect(res.data.surl);
								},300);								
								return false;
							}else{
								setTimeout(function(){
									var html = "" + easyTemplate($("#tpl_pay_success").html());
									$(".my-pay-success").html(html);
									$(".my-pay-success").find('#doc-modal-1').modal({width: 350,closeViaDimmer:false});
								},600);

								$.exeJS(res.data.sendurl);
							}
						}else{
							$.ui.error(res.msg);
						}
					}
				})
			});
	});
	//点击图标隐藏显示
	$(".detail-show").unbind("click").bind("click",function(){
		var obj = this;
		if($(".table-detail").css('display') == 'none'){
			$(".table-detail").show();
			$(obj).find("i").removeClass("am-icon-angle-down").addClass("am-icon-angle-up");
		}else{
			$(".table-detail").hide();
			$(obj).find("i").removeClass("am-icon-angle-up").addClass("am-icon-angle-down");
		}
	});
</script>
</body>
</html>