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
					<span style="color:gray;">2</span>
					<font style="color:gray;">确认订单</font>
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
				<div class="step1_empty" <?php if(!empty($cartlist0['list']) || !empty($cartlist1['list'])){?>style="display:none;"<?php }?>>
					<a href="javascript:void (0);" class="am-icon-btn am-default am-icon-cart-plus"></a>
					<span>购物车空空的哦~，去看看心仪的商品吧~</span><br/>
					<button type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="$.redirect('<?php echo U("home@/product/buy");?>')">立即选购</button>
				</div>
				<div class="step1_list">
					<form class="the_searchform form" method="POST" action="<?php echo U("/order/cart_shopping");?>">
					<div class="dis10"></div>
						<?php if(count($cartlist0['list'])>0){?>
						<div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 18px">域名套餐</div>
						<div class="dis10"></div>
						<hr/>
						<div class="cart-list">
							<table class="am-table am-table-hover">
								<col width="30px;"/>
								<col />
								<col width="120px"/>
								<col width="100px"/>
								<col width="120px"/>
								<col width="90px"/>
								<col width="90px"/>
								<col width="90px"/>
								<col width="60px"/>
								<thead>
								<tr>
									<th><input type="checkbox" checked="checked" data-name="domain" class="checkall" data-type="0"/></th>
									<th>域名</th>
									<th>套餐</th>
									<th>单价(月)</th>
									<th>时长</th>
									<th>优惠</th>
									<th>套餐余额</th>
									<th>总计</th>
									<th>操作</th>
								</tr>
								</thead>
								<tbody class="add-cart">
								<?php foreach($cartlist0['list'] as $key => $item){?>
								<tr>
									<td><input type="checkbox" checked="checked" name="domain" value="<?php echo isset($item['goods_name'])?$item['goods_name']:"";?>" data-price="<?php echo isset($item['price'])?$item['price']:"";?>" data-cart_id="<?php echo isset($item['cart_id'])?$item['cart_id']:"";?>" data-num="<?php echo isset($item['num'])?$item['num']:"";?>" data-zk="<?php echo isset($item['amount_promation'])?$item['amount_promation']:"";?>" data-yu="<?php echo isset($item['amount_other'])?$item['amount_other']:"";?>" data-total="<?php echo isset($item['amount'])?$item['amount']:"";?>"/></td>
									<td class="am-text-primary"><?php echo isset($item['goods_name'])?$item['goods_name']:"";?></td>
									<td><font class="am-text-success"> <?php echo isset($item['goods_no_name'])?$item['goods_no_name']:"";?></font><font class="am-text-gray"> <?php if(($item['service_group'] == $item['goods_no'])){?>(续费)<?php }else{?>(升级)<?php }?></font></td>
									<td class="price"><font class="am-text-gray"><span><?php echo isset($item['price'])?$item['price']:"";?></span>元</font></td>
									<td>
										<select name="num"  data-type="0" style="width: 80px;height: 30px;" class=" am-text-xs">
											<?php foreach($data_config['service_num'] as $key2 => $item2){?>
												<?php if($this->userinfo['utype'] == 2){?>
													<?php if($key2>9){?>
													<option <?php if($item['num'] == $key2){?>selected="selected"<?php }?> value="<?php echo isset($key2)?$key2:"";?>"><?php echo isset($item2)?$item2:"";?></option>
													<?php }?>
												<?php }else{?>
													<?php if($key2<=10){?>
													<option <?php if($item['num'] == $key2){?>selected="selected"<?php }?> value="<?php echo isset($key2)?$key2:"";?>"><?php echo isset($item2)?$item2:"";?></option>
													<?php }?>
												<?php }?>
											<?php }?>
										</select>
									</td>
									<td class="zk"><font class="am-text-success"><?php echo isset($item['amount_promation'])?$item['amount_promation']:"";?></font></td>
									<td class="yu"><font class="am-text-success"><?php echo isset($item['amount_other'])?$item['amount_other']:"";?></font></td>
									<td class="total"><font class="am-text-warning"><?php echo isset($item['amount'])?$item['amount']:"";?></font></td>
									<td><a href="javascript:void (0);" class="Del" data-type="0" data-domain="<?php echo isset($item['goods_name'])?$item['goods_name']:"";?>">删除</a></td>
								</tr>
								<?php }?>
								</tbody>
							</table>
						</div>
						<?php }?>
						<?php if(count($cartlist1['list'])>0){?>
						<div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 18px">增值服务</div>
						<div class="dis10"></div>
						<hr/>
						<div class="cart-list">
							<table class="am-table am-table-hover">
								<col width="30px;"/>
								<col />
								<col width="100px"/>
								<col width="120px"/>
								<col width="90px"/>
								<col width="90px"/>
								<col width="60px"/>
								<thead>
								<tr>
									<th><input type="checkbox" checked="checked" data-name="sms" class="checkall" data-type="1"/></th>
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
									<td><input type="checkbox" checked="checked" name="sms" data-price="<?php echo isset($item['price'])?$item['price']:"";?>" data-cart_id="<?php echo isset($item['cart_id'])?$item['cart_id']:"";?>" data-num="<?php echo isset($item['num'])?$item['num']:"";?>" data-zk="<?php echo isset($item['amount_promation'])?$item['amount_promation']:"";?>" data-total="<?php echo isset($item['amount'])?$item['amount']:"";?>"/></td>
									<td class="am-text-primary"><?php echo isset($item['goods_no'])?$item['goods_no']:"";?></td>
									<td class="price"><font class="am-text-gray"><span><?php echo isset($item['price'])?$item['price']:"";?></span>元</font></td>
									<td>
										<select name="sms-num"  data-type="1" style="width: 80px;height: 30px;" class="am-text-xs">
											<?php foreach(array(1,2,3,4,5,6,7,8,9,10) as $key => $item2){?>
												<option <?php if($item['num'] == $item2){?>selected="selected"<?php }?> value="<?php echo isset($item2)?$item2:"";?>"><?php echo isset($item2)?$item2:"";?></option>
											<?php }?>
										</select>
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

						<?php if(!empty($cartlist0['list'])){?>
							<?php if(count($coupon) > 0){?>
								<div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 18px">代金券</div>
								<div class="dis10"></div>
								<div class="coupon-c">
									<?php foreach($coupon as $key => $item){?>
									<?php if($key == 0){?>
									<input type="checkbox" name="coupon" <?php if(intval($cartlist0['amount']+$cartlist1['amount']) > $item['need_balance']){?>checked="checked"<?php }else{?>disabled<?php }?> data-code="<?php echo isset($item['code'])?$item['code']:"";?>"  value="<?php echo isset($item['balance'])?$item['balance']:"";?>"/>&nbsp;&nbsp;<?php echo isset($item['name'])?$item['name']:"";?>&nbsp;<?php if(intval($cartlist0['amount']+$cartlist1['amount']) <= $item['need_balance']){?>(未满<?php echo isset($item['need_balance'])?$item['need_balance']:"";?>元,不能使用)<?php }?><br/>
									<?php }?>
									<?php }?>
								</div>
							<?php }?>
						<?php }?>

					<div class="dis30"></div>

					<div class="cart-add-order" <?php if(empty($cartlist0['list']) && empty($cartlist1['list'])){?>style="display:none;"<?php }?>>
						<div class="hadcheck">已选商品<span style="color: #FA7821;font-size: 16px;"><strong><?php echo ($cartlist0['num']+$cartlist1['num']);?></strong></span>件</div>
						<div class="go-add">
							<input type="hidden" name="hash" value="<?php echo tUtil::hash();?>" />
							<input type="hidden" name="cart_ids" value="" />
							<input type="hidden" name="coupons" value="" />
							套餐总价：<span class="total"><?php echo sprintf("%.2f",($cartlist0['amount_promation']+$cartlist0['amount_other']+$cartlist0['amount']+$cartlist1['amount_promation']+$cartlist1['amount']));?></span>元<br/>
							- 共优惠：<span class="coupon"><?php echo sprintf("%.2f",($cartlist0['amount_promation']+$cartlist1['amount_promation']));?></span>元<br/>
							<div class="coupon-console">- 代金券：<span class="coupon-m">0.00</span>元<br/></div>
							<div style="border-bottom: 2px solid silver;width: 300px;float: right;">- 套餐余额：<span class="total_yu"><?php echo isset($cartlist0['amount_other'])?$cartlist0['amount_other']:"";?></span>元</div><br/>
							<div class="dis10"></div>
							应付总价：<span class="total-sum" style="color: #FA7821;font-size: 34px;"><strong><?php echo sprintf("%.2f",($cartlist0['amount']+$cartlist1['amount']));?></strong></span>元&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="am-btn am-btn-warning am-radius am-btn-sm go-account">去结算</button><br/>
						</div>
					</div>
					</form>
				</div>
			</div>
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
<?php echo $this->fetch('order/order_tpl')?>
<script type="text/javascript">
	$(function () {
		//删除操作,域名套餐
		$(".add-cart").find(".Del").unbind("click").bind("click",function(){
			var obj = this;
			var type = $(obj).data("type");
			if (type == 0) { //域名服务套餐
				var goods_name = $(obj).data("domain");
				var data = {goods_name:goods_name,type:0};
			}else if (type == 1) {//短信增值服务
				var cart_id = $(obj).data("id");
				data = {cart_id:cart_id,type:1};
			}
			$.ui.loading();
			$.ajaxPassport({
				url:U("/api/Cart.Del"),
				success:function(res){
					if (res.status == 1) {
						$.ui.success(res.msg);
						$.ui.close_loading();
						$(obj).parent().parent().remove();
						//商品总数减一
						var n = $(".cart-add-order").find(".hadcheck strong").html();
						if (n >0) {
							n--;
							$(".cart-add-order").find(".hadcheck strong").html(n);
							checked_no(type);
						}

						if ($(".cart-list").find("table tbody tr").length == 0) {
							$(".step1 .step1_empty").show();
							$(".step1 .step1_list").hide();
						}

						domain_register_tips();
					}else{
						$.ui.error(res.msg);
					}
				},
				data:data
			})
		});
		//点击时间按钮触发事件
		$(".add-cart tr select").unbind("change").bind("change",function(){
			var obj=this;
			var type = $(obj).data("type");
			if (type == 0) {//域名服务套餐
				var cart_id = $(obj).parent().parent().find("input[name='domain']").data("cart_id");
			}else if (type == 1){//增值服务套餐
				cart_id = $(obj).parent().parent().find("input[name='sms']").data("cart_id");
			}
			var num = $(obj).val();
			$.ui.loading();
			$.ajaxPassport({
				url:"<?php echo U("account@/api/Cart.Edit");?>",
				success:function(res){
					if(res.status == 1){

					}else{

					}
					$.ui.close_loading();
					account_total($(obj).parent().parent(),type);
				},
				data:{cart_id:cart_id,num:num},
			});
		});
		//全选，全不选
		$(".content-shop").find("input.checkall").unbind("click").bind("click",function(){
			$.check_all(this);
			var type = $(this).data("type");
			checked_no(type);
		});
		//已选商品个数域名套餐
		$(".content-shop").find("input[name='domain']").unbind("click").bind("click",function(){
			checked_no(0);
		});
		//已选商品个数短信增值服务
		$(".content-shop").find("input[name='sms']").unbind("click").bind("click",function(){
			checked_no(1);
		});
		//去结算
		$(".go-account").click(function(){
			var cart_ids = new Array();
			var coupon_codes = new Array();
			//域名服务套餐
			$(".content-shop").find("tr input[name='domain']:checked").each(function(){
				var obj = this;
				cart_ids.push($(obj).data('cart_id'));
			});
			//短信增值服务
			$(".content-shop").find("tr input[name='sms']:checked").each(function(){
				var obj = this;
				cart_ids.push($(obj).data('cart_id'));
			});
			//优惠代金券
			$(".content-shop").find("input[name='coupon']:checked").each(function(){
				var obj = this;
				coupon_codes.push($(obj).data('code'));
			});
			if (coupon_codes.length > 1) {
				$.ui.error("一次性只能消费一张代金券！");
				return false;
			}
			if (cart_ids.length <= 0) {
				$.ui.error("请选择需要购买的服务");
				return false;
			}

			$("input[name='cart_ids']").val(cart_ids.join(","));
			$("input[name='coupons']").val(coupon_codes.join(","));
			return true;
		});
		//代金券计算
		coupon_fun();
		//点击时触发代金券函数
		$(".content-shop").find(".coupon-c input[name='coupon']").unbind("click").bind("click",function(){
			coupon_fun("c");
		});
	})
	//计算总价,域名服务套餐
	var account_total = function(dobj,type){
		var obj = dobj;
		if (type == 0) {//域名服务套餐
			var s = "select[name='num']";
			var n = "input[name='domain']";
			obj.find(n).attr("checked",true);
			var yu = obj.find(n).data("yu");
		}else if (type == 1) {//短信增值服务
			var s = "select[name='sms-num']";
			var n = "input[name='sms']";
			obj.find(n).attr("checked",true);
		}

		var num = obj.find(s).val();
		var service_price   = obj.find(n).data("price");
		var be_num = obj.find(n).data("num");
		var zk = obj.find(n).data("zk");

		//优惠价
		var youhui = parseFloat(num) * (parseFloat(zk)/parseFloat(be_num));
		obj.find("td.zk font").html($.to_float2(youhui));
		//总计
		if (type == 0) {//域名服务套餐
			var total  = $.to_float2(parseInt(num) *parseFloat(service_price)-youhui-yu);
		}else if (type == 1) {//短信增值服务
			var total  = $.to_float2(parseInt(num) *parseFloat(service_price)-youhui);
		}

		obj.find("td.total font").html($.to_float2((total < 0)?0:total));
		//已选商品,套餐总价,优惠价,应付总价
		checked_no(type);
	}
	//已选商品个数,套餐总价,优惠价,应付总价
	var checked_no = function(type){
		var len = $(".content-shop").find("tr input[name='domain']:checked").length; //域名套餐
		var len1 = $(".content-shop").find("tr input[name='sms']:checked").length; //短信服务
		$(".cart-add-order").find(".hadcheck strong").html(len+len1);

		//套餐总价,优惠价,应付总价
		var service_total = 0;
		var service_zk = 0;
		var total_yu = 0;
		if (len > 0) {//域名套餐
			$(".content-shop").find("tr input[name='domain']:checked").each(function(){
				var obj=this;
				var tmp1_price = $(obj).parent().parent().find("td.price span").html();
				var tmp1_num = $(obj).parent().parent().find("select[name='num']").val();
				var tmp2 = $(obj).parent().parent().find("td.zk font").html();
				var tmp3 =$(obj).parent().parent().find("td.yu font").html();
				service_total = service_total + parseFloat(tmp1_price) * parseInt(tmp1_num);
				service_zk = service_zk + parseFloat(tmp2);
				total_yu = total_yu + parseFloat(tmp3);
			});
		}
		if (len1 > 0) {//短信增值服务
			$(".content-shop").find("tr input[name='sms']:checked").each(function(){
				var obj=this;
				var s_tmp1_price = $(obj).parent().parent().find("td.price span").html();
				var s_tmp1_num = $(obj).parent().parent().find("select[name='sms-num']").val();
				var s_tmp2 = $(obj).parent().parent().find("td.zk font").html();
				service_total = service_total + parseFloat(s_tmp1_price) * parseInt(s_tmp1_num);
				service_zk = service_zk + parseFloat(s_tmp2);
			});
		}
		$(".go-add span.total").html($.to_float2(service_total));
		$(".go-add span.coupon").html($.to_float2(service_zk));
		$(".go-add span.total_yu").html($.to_float2(total_yu));
		$(".go-add strong").html($.to_float2((service_total-service_zk-total_yu) < 0?0:service_total-service_zk-total_yu));
		//代金券计算
		coupon_fun();
	}
	//如果代金券存在，计算代金券价格todo:优惠值大于套餐 解决方法，显示隐藏法 套餐总价算法
	var coupon_fun = function (type) {
		var len = $(".content-shop").find("tr input[name='domain']:checked").length; //域名套餐
		if (len > 0) {
			var coupon_m =  0;
			var c_num = 0;
			$(".content-shop").find(".coupon-c input[name='coupon']").each(function () {
				var obj = this;
				coupon_m += parseInt($(obj).val());

				if ($(obj).is(':checked')) {
					c_num ++;
				}
			});
			if (c_num > 0) {
				$(".cart-add-order").find(".coupon-console").show();
				$(".cart-add-order").find("span.coupon-m").html($.to_float2(coupon_m));
				$("input[name='coupon_m']").val(coupon_m);

				$(".go-add strong").html($.to_float2($(".go-add strong").html() - $.to_float2(coupon_m) <= 0?0:$(".go-add strong").html() - $.to_float2(coupon_m)));
			}else{
				$(".cart-add-order").find(".coupon-console").hide();
				//仅点击checkbox恢复
				if (type == "c") {
					$(".go-add strong").html($.to_float2(parseInt($(".go-add strong").html()) + parseInt(coupon_m)));
				}
				//如果代金券金额大于套餐金额
				if (parseInt($(".go-add span.total").html()) < coupon_m) {
					$(".go-add strong").html($.to_float2(parseInt($(".go-add span.total").html()) - parseInt($(".go-add span.coupon").html()) - parseInt($(".go-add span.total_yu").html())));
				}
			}
			c_num = 0;
		}
	}
</script>
</body>
</html>