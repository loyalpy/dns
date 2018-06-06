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
			<li><a href="<?php echo U("/finance/recharge");?>" class="cur">在线充值&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="javascript:void(0);"  class="sms_recharge_btn">短信充值&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/recharge_detail");?>"  >收支明细&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/coupon");?>">代金券&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/tg");?>">推广中心&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
		</ul>
	</div>
</div>
<div class="am-uc-right" style="margin-top: -10px;">
	<div class="records-nav profile_information" >
		<ul>
			<li class="alipay <?php if($type == 1){?>cur<?php }?>"><a href="<?php echo U("/finance/recharge?type=1");?>">在线充值</a></li>
			<li class="ebank <?php if($type == 2){?>cur<?php }?>"><a href="<?php echo U("/finance/recharge?type=2");?>">充值记录</a></li>
		</ul>
	</div>
	<?php if($type == 2){?>
	<div class="dis30"></div>
	<div class="am-form-group am-finance-list-key" style="margin: 0px;">
		<div class="am-form-group am-form-icon">
			<input type="text"  class="am-form-field am-input-sm" name="startdate" onclick="laydate()" class="am-form-field" placeholder=" 开始日期">
			<i class="am-icon-calendar"></i>
		</div>
	</div>
	<div class="am-finance-list-key">
		<div class="am-form-group am-form-icon">
			<input type="text"  class="am-form-field am-input-sm" name="enddate" onclick="laydate()" class="am-form-field" placeholder=" 结束日期">
			<i class="am-icon-calendar"></i>
		</div>
	</div>
	<div class="am-finance-list-key" style="margin-left: 80px;">
		<input type="text"  name="keyword" class="am-form-field  am-input-sm" placeholder="请输入关键词" size="35"/>
	</div>
	<div class="am-finance-list-key">
		<button type="button" class="am-btn am-btn-success am-radius am-btn-sm finance-serch">搜索</button>
	</div>
	<div class="dis10" style="margin-bottom: -10px;"></div>
	<?php }?>
	<div class="am-finance-content">	</div>
</div>
<!--支付提示-->
<div class="am-modal am-modal-no-btn" id="my-recharge-show" tabindex="-1" id="doc-modal-1">
	<div class="am-modal-dialog">
		<div class="am-modal-hd" style="border-bottom: 1px solid silver;text-align: left;font-size: 15px;padding-bottom: 5px;">
			<span>在线充值</span>
			<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
		</div>
		<div class="am-modal-bd" style="margin-top: 10px;">
			<div style="float: left;margin-left: 30px;margin-top: 45px;">
				<a href="##" class="am-icon-btn am-warning am-icon-warning"></a>
			</div>
			<div style="position:relative;left:-30px;line-height: 30px;text-align: center;">
				<span>请您在新打开的网上银行页面进行支付</span><br/>
				<span style="font-size: 13px;color: gray;position: relative;left: -45px;">付款完成前，请不要关闭此窗口</span><br/>
				<span style="font-size: 13px;color: gray;position: relative;left: -6px;">付款完成后，请根据您的情况点击下面的按钮</span><br/>
				<a href="<?php echo U("finance/recharge?type=2");?>"><button type="button" class="am-btn am-btn-success am-btn-sm" style="position: relative;left: -35px;">已完成支付</button></a>&nbsp;&nbsp;
				<a href="<?php echo U("finance/recharge");?>"><button type="button" class="am-btn am-btn-default am-btn-sm" style="position: relative;left: -10px;">稍后支付</button></a><br/>
				<a href="<?php echo U("finance/recharge");?>" style="font-size: 13px;position: relative;left: -32px;">返回选择其他支付方式</a>
			</div>
		</div>
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
	var inpay    = "<?php echo isset($inpay)?$inpay:"";?>";
	var order_no = "<?php echo isset($order_no)?$order_no:"";?>";
	var order_type = "<?php echo isset($order_type)?$order_type:"";?>";
	$(function(){
		$(".am-close-spin").click(function(){
			window.location.reload();
		});
		<?php if($type == 1){?>
		payByAlipay();
		<?php }else{?>
		load_recharge_list(1);
		<?php }?>
	});
</script>
<!--支付宝充值-->
<script type="text/template" id="tpl_alipay">
	<#macro rowedit data>
		<div class="dis20"></div>
		<div class="finance-tip">
			<span>账户余额：</span><span style="font-size:28px;color: #FF6600;font-weight: 600"><?php echo isset($this->userinfo['account']['balance'])?$this->userinfo['account']['balance']:"";?></span> 元
			<br><div class="dis10"></div>
			<span style="color: gray;font-size: 13px;">小贴士：充值成功后仅账户余额增多，不会自动结算订单，如果您有未付款订单，请结算。<a href="<?php echo U("account@/order/order");?>">我的订单</a></span>
		</div>
		<div class="dis20"></div>
		<div class="am-cf am-padding">
			<!--<form id="Frecharge_form" action="<?php echo U("/finance/recharge_submit");?>" method="GET"  class="form" >-->
			<div class="am-form-group">
				<label class="am-u-sm-2" style="margin-right: -70px;">充值金额：</label>
				<div class="am-u-sm-7">
					<div class="am-input-group" style="width: 230px;">
						<input type="text" class="am-form-field" id="Mrecharge" name="recharge" value="<?php echo isset($balance)?$balance:"";?>">
						<span class="am-input-group-label">元</span>
					</div>
					<small class="am-font-gray">1,填写时请核对您的充值金额是否正确,以防充值失败</small><br/>
					<small class="am-font-gray">2,通过信用卡的快捷支付有500元限制，超过500元时您可以选择其他方式支付。</small>
				</div>
				<div class="am-cf"></div>
			</div>
			<div class="am-form-group">
				<label class="am-u-sm-2" style="margin-right: -70px;margin-top: 12px">支付方式：</label>
				<div class="am-u-sm-7">
					<?php $payment_list = C("payment")->get_payment();?>
					<ul class="paysel">
					<?php foreach($payment_list as $key => $item){?>
					<?php if($item['id']!=7){?>
					<li><input type="radio" value="<?php echo isset($item['id'])?$item['id']:"";?>" name="pay_id"  <?php if($item['id'] == 10){?>checked="checked"<?php }?>/> &nbsp;&nbsp;<img src="<?php echo U("static@/public/images");?><?php echo isset($item['logo'])?$item['logo']:"";?>" alt="<?php echo isset($item['name'])?$item['name']:"";?>" /> </li>
					<?php }?>
					<?php }?>
					</ul>
				</div>
				<div class="am-cf"></div>
			</div>
			<div class="am-form-group">
				<label class="am-u-sm-2" style="margin-right: -70px;"></label>
				<div class="am-u-sm-7 am-text-left">
					<input name="hash"  value="<?php echo tUtil::hash();?>" type="hidden" />
					<input name="recharge_id" type="hidden" value="0" />
					<input name="type" id="Mrecharge_type" type="hidden" value="1" />
					<input name="timestamp" value="<?php echo isset($timestamp)?$timestamp:"";?>" type="hidden" />
					<input name="order_no" value="<?php echo isset($order_no)?$order_no:"";?>" type="hidden" />
					<input name="inpay" value="<?php echo isset($inpay)?$inpay:"";?>" type="hidden" />
					<input name="in_ajax" value="1" type="hidden" />
					<input name="do" value="submit" type="hidden" />
					<a class="am-btn am-btn-warning am-radius company-save" href="###">充值</a>
				</div>
				<div class="am-cf"></div>
			</div>
			<div class="dis20"></div>
			<!--</form>-->
		</div>
	</#macro>
</script>
<script language="JavaScript">
	function payByAlipay(){
		var edit_c = "" + easyTemplate($("#tpl_alipay").html());
		$(".am-finance-content").html(edit_c);
		//提交充值
		$(".company-save").unbind("click").bind("click",function(){
			var v = $("#Mrecharge").val();
			var do1 = $("input[name='do']").val();
			var recharge_id = $("input[name='recharge_id']").val();
			var pay_id = $("input[name='pay_id']:checked").val();
			return payRecharge(v,do1,recharge_id,pay_id);
		});
	}
	var payRecharge = function(v,do1,recharge_id,pay_id){
		if (isNaN(v) || !$.isNumeric(v)) {
			$.ui.error("请您输入正确的金额");
			$("#Mrecharge").focus();
			return false;
		}
		if( v <= 0 ){
			$.ui.error("充值金额不能少于0");
			$("#Mrecharge").focus();
			return false;
		}
		if (typeof pay_id == "undefined") {
			$.ui.error("请选择支付方式！");
			return false;
		}
		$("#my-recharge-show").modal({closeViaDimmer:false});

		var url = "<?php echo U("finance/recharge_submit?do=submit&recharge_id=");?>"+recharge_id+"&pay_id="+pay_id+"&recharge="+parseFloat(v)+"&inpay="+inpay+"&order_no="+order_no+"&order_type="+order_type;
		$(".company-save").attr("href",url);
		$(".company-save").attr("target","_blank");
		return true;
	};
</script>
<!--充值记录-->
<script type="text/template" id="tpl_ebank">
	<#macro rowedit data>
		<table class="am-table">
			<col width="200"/>
			<col width="120"/>
			<col />
			<col width="180"/>
			<col width="120"/>
			<thead>
			<tr>
				<th>充值单号</th>
				<th>充值金额(元)</th>
				<th>充值类型</th>
				<th>充值时间</th>
				<th>状态</th>
			</tr>
			</thead>
			<tbody>
			<#if (data.list.length > 0)>
			<#list data.list as rechar>
				<tr>
					<td style="color: gray;">${rechar.recharge_no}</td>
					<td style="color: <#if (rechar.status==1)>green<#else>red</#if>">
					<span class="am-text-warning">${rechar.amount}</span></td>
					<td  style="color: gray;">${rechar.payment_name}</td>
					<td style="color: gray;">${$.time_to_string(rechar.dateline,"Y-m-d H:i:s")}</td>
					<td style="color: <#if (rechar.status==1)>green<#else>red</#if>"><#if (rechar.status==1)>充值成功<#else>未成功</#if></td>
				<tr>
			</#list>
				<#else>
					<tr>
						<td colspan="5">
							<a href="##" class="am-icon-exclamation-circle am-text-danger am-text-lg"></a> <a href="##" class="am-font-gray">没有符合条件的结果?</a>
						</td>
					</tr>
			</#if>

			</tbody>
		</table>
		<div class="pagebar">${data.pagebar}</div>
	</#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script language="JavaScript">
	var load_recharge_list = function(page,keyword,startdate,enddate,condition){
		var url = "<?php echo U("/finance/recharge_list?do=get");?>";
		var keyword  = $.is_empty(keyword)?'':keyword;
		var startdate  = $.is_empty(startdate)?'':startdate;
		var enddate  = $.is_empty(enddate)?'':enddate;
		var condition  = $.is_empty(condition)?'':condition;
		$.ui.loading($(".am-table"));
		$.ajaxPassport({
			url:url,
			success:function(res){
				$.ui.close_loading($(".am-table"));

				var edit_c = "" + easyTemplate($("#tpl_ebank").html(),res.data);
				$(".am-finance-content").html(edit_c);

				$("button,a").bind("focus",function(){
					$(this).blur();
				});

				//搜索事件
				$(".finance-serch").unbind("click").bind("click",function(){
					var startdate = $("input[name='startdate']").val();
					var enddate  = $("input[name='enddate']").val();
					var keyword  = $("input[name='keyword']").val();
					load_recharge_list(1,keyword,startdate,enddate);
				});

			},
			data:{page:page,keyword:keyword,startdate:startdate,enddate:enddate,condition:condition},
		});
	}
</script>
</body>
</html>