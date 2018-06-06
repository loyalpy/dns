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
					<span style="color:gray;">3</span>
					<font style="color:gray;">支付</font>
					<i class="am-icon-angle-right am-icon-lg" style="color:#E2E2E3;"></i>
				</div>
			</li>
			<li class="un-use use4">
				<div class="icon">
					<span style="color:gray;">4</span>
					<font style="position: relative;top:3px;color:gray;">支付完成</font>
				</div>
			</li>
		</ul>
		<div class="dis10"></div>
	</div>
	<div class="content">
		<div class="dis10"></div>
		<div class="content-shop">
			<div class="step1" style="display: block;">
				<?php if(empty($cartlist0['list']) &&  empty($cartlist1['list'])){?>
				<div class="step1_empty">
					<a href="javascript:void (0);" class="am-icon-btn am-default am-icon-cart-plus"></a>
					<span>购物车空空的哦~，去看看心仪的商品吧~</span><br/>
					<button type="button" class="am-btn am-btn-secondary am-radius am-btn-sm" onclick="alert('跳转到前台套餐详细页面...');">立即选购</button>
				</div>
				<?php }else{?>
				<div class="step1_list">
					<form class="the_searchform form" method="POST" action="<?php echo U("/order/cart_shopping_step3");?>">
					<div class="dis10"></div>
						<?php if(count($cartlist0['list'])>0){?>
							<div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;">域名套餐</div>
							<div class="dis10"></div>
							<hr/>
							<div class="cart-list">
								<table class="am-table am-table-hover">
									<col />
									<col width="120px"/>
									<col width="100px"/>
									<col width="120px"/>
									<col width="90px"/>
									<col width="90px"/>
									<col width="90px"/>
									<thead>
									<tr>
										<th>域名</th>
										<th>套餐</th>
										<th>单价</th>
										<th>时长</th>
										<th>优惠</th>
										<th>套餐余额</th>
										<th>总计</th>
									</tr>
									</thead>
									<tbody class="add-cart">
									<?php foreach($cartlist0['list'] as $key => $item){?>
									<tr>
										<td class="am-text-primary"><?php echo isset($item['goods_name'])?$item['goods_name']:"";?><input type="hidden" name="cart_ids0" value="<?php echo isset($item['cart_id'])?$item['cart_id']:"";?>" /></td>
										<td><font class="am-text-success"> <?php echo isset($item['goods_no_name'])?$item['goods_no_name']:"";?></font><font class="am-text-gray"> <?php if(($item['service_group'] == $item['goods_no'])){?>(续费)<?php }else{?>(升级)<?php }?></font></td>
										<td class="price"><font class="am-text-gray"><span><?php echo isset($item['price'])?$item['price']:"";?></span>元</font></td>
										<td><?php if($item['num']<10){?><?php echo isset($item['num'])?$item['num']:"";?>个月<?php }else{?><?php echo $item['num']/10;?>年<?php }?></td>
										<td class="zk"><font class="am-text-success"><?php echo isset($item['amount_promation'])?$item['amount_promation']:"";?></font></td>
										<td class="yu"><font class="am-text-success"><?php echo isset($item['amount_other'])?$item['amount_other']:"";?></font></td>
										<td class="total"><font class="am-text-warning"><?php echo isset($item['amount'])?$item['amount']:"";?></font></td>
									</tr>
									<?php }?>
									</tbody>
								</table>
							</div>
						<?php }?>
						<div class="dis20"></div>
						<?php if(count($cartlist1['list'])>0){?>
							<div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 18px">增值服务</div>
							<div class="dis10"></div>
							<hr/>
							<div class="cart-list">
								<table class="am-table am-table-hover">
									<col />
									<col width="100px"/>
									<col width="120px"/>
									<col width="90px"/>
									<col width="90px"/>
									<col width="60px"/>
									<thead>
									<tr>
										<th>增值服务</th>
										<th>单价</th>
										<th>数量</th>
										<th>优惠</th>
										<th>总计</th>
										<th>操作</th>
									</tr>
									</thead>
									<tbody class="add-cart">
									<?php foreach($cartlist1['list'] as $key => $item){?>
									<tr>
										<td class="am-text-primary"><?php echo isset($item['goods_no'])?$item['goods_no']:"";?><input type="hidden" name="cart_ids1" value="<?php echo isset($item['cart_id'])?$item['cart_id']:"";?>" /></td>
										<td class="price"><font class="am-text-gray"><span><?php echo isset($item['price'])?$item['price']:"";?></span>元</font></td>
										<td>
											<?php echo isset($item['num'])?$item['num']:"";?>
										</td>
										<td class="zk"><font class="am-text-success"><?php echo isset($item['amount_promation'])?$item['amount_promation']:"";?></font></td>
										<td class="total"><font class="am-text-warning"><?php echo isset($item['amount'])?$item['amount']:"";?></font></td>
										<td><a href="javascript:void (0);" class="Del" data-type="1" data-id="<?php echo isset($item['cart_id'])?$item['cart_id']:"";?>">删除</a></td>
									</tr>
									<?php }?>
									</tbody>
								</table>
							</div>
						<?php }?>

						<?php if(!empty($cartlist0['list']) || !empty($cartlist1['list'])){?>
							<?php if(isset($coupon_arr['id'])){?>
									<div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 18px">代金券</div>
									<div class="dis10"></div>
									<div class="coupon-c">
										<?php echo isset($coupon_arr['name'])?$coupon_arr['name']:"";?><br/>
									</div>
							<?php }?>
						<?php }?>

						<div class="dis20"></div>
					<div class="cart-add-order">
						<div class="hadcheck" style="position: relative;left:14px;">已选商品：<span style="color: #FA7821;font-size: 16px;"><strong><?php echo ($cartlist0['num']+$cartlist1['num']);?></strong></span>件</div>
						<div class="go-add">
							<input type="hidden" name="hash" value="<?php echo tUtil::hash();?>" />
							<input type="hidden" name="coupon_code" value="<?php if(isset($coupon_arr['id'])){?><?php echo isset($coupon_arr['code'])?$coupon_arr['code']:"";?><?php }?>" />
							套餐总价：<span class="total"><?php echo sprintf("%.2f",($cartlist0['amount_promation']+$cartlist0['amount_other']+$cartlist0['amount']+$cartlist1['amount_promation']+$cartlist1['amount']));?></span>元<br/>
							- 共优惠：<span class="coupon"><?php echo sprintf("%.2f",($cartlist0['amount_promation']+$cartlist1['amount_promation']));?></span>元<br/>
							<?php if(isset($coupon_arr['id'])){?><div class="coupon-console">- 代金券：<span class="coupon-m"><?php echo sprintf("%.2f",$coupon_arr['balance']);?></span>元<br/></div><?php }?>
							<div style="border-bottom: 2px solid silver;width: 300px;float: right;">- 套餐余额：<span class="total_yu"><?php echo isset($cartlist0['amount_other'])?$cartlist0['amount_other']:"";?></span>元</div><br/>
							<div class="dis10"></div>
							应付总价：<span class="total-sum" style="color: #FA7821;font-size: 34px;">
							<strong>
								<?php if(isset($coupon_arr['id'])){?>
								<?php echo sprintf("%.2f",($cartlist0['amount']+$cartlist1['amount']-$coupon_arr['balance']));?>
								<?php }else{?>
								<?php echo sprintf("%.2f",($cartlist0['amount']+$cartlist1['amount']));?>
								<?php }?>
							</strong>
						</span>元&nbsp;&nbsp;&nbsp;&nbsp;
							<button type="button" class="am-btn am-btn-warning am-radius am-btn-sm submit-order">提交订单</button><br/>
							<a href="<?php echo U("/order/cart_shopping");?>"><button type="button" class="am-btn am-btn-default am-radius am-btn-sm goback">返回上一步</button></a>
						</div>
					</div>
					</form>
				</div>
				<?php }?>
			</div>
			<div class="my-add-order"></div>
		</div>
	</div>
</div>
<div class="dis30"></div>
<!--添加订单失败-->
<script type="text/template" id="tpl_add_order">
	<#macro rowedit data>
		<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
			<div class="am-modal-dialog">
				<div class="am-modal-hd">
					<i class="am-icon-exclamation-triangle" style="color: orange;"></i>&nbsp;&nbsp;${data.msg}
					<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
				</div>
				<div class="dis20"></div>
				<div class="am-modal-bd">
					<a href="javascript: void(0)" data-am-modal-close><button type="button" class="am-btn am-btn-default">关闭</button></a>&nbsp;&nbsp;&nbsp;
					<a href="<?php echo U("/order/order");?>" ><button type="button" class="am-btn am-btn-warning">查看订单列表</button></a>
				</div>
				<div class="dis30"></div>
			</div>
		</div>
	</#macro>
</script>
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
<script language="JavaScript">

	$(function(){
		$("button.submit-order").bind("click",function(){
			var coupon_code = $("input[name='coupon_code']").val();
			//域名服务套餐
			var cart_ids0 = [];
			$("input[name='cart_ids0']").each(function(){
				cart_ids0.push($(this).val());
			});
			//短信服务套餐
			var cart_ids1 = [];
			$("input[name='cart_ids1']").each(function(){
				cart_ids1.push($(this).val());
			});
			if(cart_ids0.length > 0 || cart_ids1.length > 0){
				if (cart_ids0.length > 0) {
					var data = {"cart_ids0":cart_ids0.join(",")};
				}
				if (cart_ids1.length > 0) {
					data = {"cart_ids1":cart_ids1.join(",")};
				}
				if (cart_ids0.length > 0 && cart_ids1.length > 0) {
					data = {"cart_ids0":cart_ids0.join(","),"cart_ids1":cart_ids1.join(",")};
				}
				data.coupon_code = coupon_code;
				$.ui.loading();
				$.ajaxPassport({
					url:"<?php echo U("/api/Order/Add");?>",
					data:data,
					success:function(res){
						$.ui.close_loading();
						if(res.status == 1){
							var order_no = res.data.order_no;
							$.ui.success("提交成功！");
							setTimeout(function(){
								$.redirect("<?php echo U("/order/order_view?order_no=");?>"+order_no);
							},1000);
						}else{
							setTimeout(function(){
								var html = "" + easyTemplate($("#tpl_add_order").html(),res);
								$(".my-add-order").html(html);
								$(".my-add-order").find('#doc-modal-1').modal({width: 450,closeViaDimmer:false});
							},600);
						}
					}
				});
			}
		})
	})
</script>
</body>
</html>