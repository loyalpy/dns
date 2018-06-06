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
			<li><a href="<?php echo U("/finance/coupon");?>">代金券&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
			<li><a href="<?php echo U("/finance/tg");?>" class="cur">推广中心&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
		</ul>
	</div>
</div>
<div class="am-uc-right" style="margin-top: -10px;">
	<div class="records-nav profile_information" >
		<ul>
			<li class="alipay <?php if($type == 1){?>cur<?php }?>"><a href="<?php echo U("/finance/tg?type=1");?>">推广中心</a></li>
			<li class="ebank <?php if($type == 2){?>cur<?php }?>"><a href="<?php echo U("/finance/tg?type=2");?>">推广用户</a></li>
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
	<?php }else{?>
	<div class="dis30"></div>
	<?php }?>

	<div class="am-finance-content">	</div>
	<div id="setline-a">	</div>
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
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script type="text/javascript">
	$(function(){
		<?php if($type == 1){?>
		load_account_list();
		<?php }elseif($type == 2){?>
		load_account_list2(1);
		<?php }else{?>
		<?php }?>

		//搜索事件
		$(".finance-serch").unbind("click").bind("click",function(){
			var startdate = $("input[name='startdate']").val();
			var enddate  = $("input[name='enddate']").val();
			var keyword  = $("input[name='keyword']").val();
			load_account_list2(1,keyword,startdate,enddate);
		});
	});
</script>
<!--推广中心-->
<script type="text/template" id="tpl_accountlog_list">
	<#macro rowedit data>
		<div class="tg-center">
			<div class="tg-item1">欢迎您，<?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?></div>
			<?php if($rz_status == 0){?>
			<div class="tg-item2" ><i class="am-icon-hand-o-right"></i> 系统检测到您未认证，认证后即可使用推广业务以及获取分红等，<a href="javascript:void (0);" onclick="apply_rz();">立即申请认证</a></div>
			<?php }else{?>
			<div class="tg-item3">
				<div>
					<a href="javascript:void (0);" onclick="apply_rz();">认证资料</a>&nbsp;&nbsp;|&nbsp;&nbsp;
					<cite class="<?php if($rz_status== 3){?>rz<?php }else{?>norz<?php }?>"></cite>&nbsp;
					<?php if($rz_status == 1){?>
					<span>审核中</span>
					<?php }elseif($rz_status == 2){?>
					<span>审合失败</span>
					<?php }else{?>
					<span style="color: #609102">已认证</span>
					<?php }?>
				</div>
				<div>
					<span class="am-font-gray">160826550@qq.com</span>&nbsp;&nbsp;|&nbsp;&nbsp;
					<cite class="email-rz"></cite>&nbsp;
					<?php if($this->userinfo['emailrz'] == 1){?>
					<span style="color: #609102">邮箱已认证</span>
					<?php }else{?>
					<span>邮箱未认证</span>
					<?php }?>
				</div>
			</div>
			<?php }?>
		</div>
		<div class="dis30"></div>
		<div class="am-panel am-panel-success">
			<div class="am-panel-hd" style="font-size: 16px;font-weight: 500">推广链接</div>
			<div class="am-panel-bd" style="height: 120px;padding-left: 80px;padding-top: 30px;">
				<table style="width: 90%">
					<tr>
						<td><input class="am-input-lg am-form-field copylink" type="text" placeholder="推广链接" value="http://www.bajiedns.com?tg_url=<?php echo isset($tg_code)?$tg_code:"";?>"></td>
						<td>&nbsp;&nbsp;<button type="button" class="am-btn am-btn-lg am-btn-secondary am-radius dj-copy">复制</button></td>
					</tr>
				</table>
			</div>
		</div>
	</#macro>
</script>
<script type="text/javascript">
	var load_account_list = function(){
		var listhtml = ""+ easyTemplate($("#tpl_accountlog_list").html(),'');
		$(".am-finance-content").html(listhtml);

		$(".dj-copy").click(function(){
			var v = $(".copylink").val();
			$.dns.copy_clip(v);
		})
	}
</script>
<!--申请认证 start-->
<script type="text/template" id="tpl_add_apply">
	<#macro rowedit data>
		<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1-t">
			<div class="am-modal-dialog">
				<div class="am-modal-hd" style="border-bottom: 1px solid silver;text-align: left;color:black;padding-bottom: 16px;">
					<i class="am-icon-edit" style="color: #EB8500;"></i>&nbsp;&nbsp;申请认证&nbsp;&nbsp;
					<span style="font-size: 14px;color: gray;">(温馨提示：申请成功后需要一个工作日内审核 )</span>
					<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
				</div>
				<div class="am-modal-bd" style="font-size: 14px;padding: 30px 30px 60px 30px;text-align: center;">
					<table class="tg-table">
						<tbody>
						<tr class="">
							<td class="tg-l"><span>*</span> 联系人：</td>
							<td><input type="text" name="name" class="am-input-sm am-form-field" value="${data.name}" <#if (data.status == 3)>disabled</#if>></td>
						</tr>
						<tr>
							<td class="tg-l"><span>*</span> 手机号：</td>
							<td><input type="text" name="mobile" class="am-input-sm am-form-field" value="${data.mobile}" <#if (data.status == 3)>disabled</#if>></td>
						</tr>
						<tr>
							<td class="tg-l">邮箱：</td>
							<td><input type="text" name="email" class="am-input-sm am-form-field" value="<#if (data.email)>${data.email}<#else><?php echo isset($this->userinfo['email'])?$this->userinfo['email']:"";?></#if>" <#if (data.status == 3)>disabled</#if>></td>
						</tr>
						<tr>
							<td class="tg-l">QQ：</td>
							<td><input type="text" name="qq_ww" class="am-input-sm am-form-field" value="${data.qq_ww}" <#if (data.status == 3)>disabled</#if>></td>
						</tr>
						<tr>
							<td class="tg-l">联系地址：</td>
							<td><input type="text" name="address" class="am-input-sm am-form-field" value="${data.address}"></td>
						</tr>
						<tr>
							<td class="tg-l">结算方式：</td>
							<td>
								<div class="am-form-group" style="float: left;margin-bottom: 0px">
									<select id="doc-select-1" name="pay_type" style="width: 180px;height: 35px;line-height: 35px;" <#if (data.status == 3)>disabled</#if>>
										<option value="1" <#if (data.pay_type == 1)>selected</#if>>支付宝(alipay)</option>
										<option value="2" <#if (data.pay_type == 2)>selected</#if>>农业银行(ABC)</option>
										<option value="3" <#if (data.pay_type == 3)>selected</#if>>工商银行(ICBC)</option>
										<option value="4" <#if (data.pay_type == 4)>selected</#if>>招商银行</option>
										<option value="5" <#if (data.pay_type == 5)>selected</#if>>建设银行</option>
									</select>
									<span class="am-form-caret"></span>
								</div>
							</td>
						</tr>
						<tr>
							<td class="tg-l"><span>*</span> 结算账号：</td>
							<td><input type="text" name="pay_no" class="am-input-sm am-form-field"  value="<#if (data.pay_no)>${data.pay_no}<#else><?php echo isset($this->userinfo['email'])?$this->userinfo['email']:"";?></#if>" <#if (data.status == 3)>disabled</#if>></td>
						</tr>
						<tr>
							<td class="tg-l"><span>*</span> 收款人：</td>
							<td><input type="text" name="pay_name" class="am-input-sm am-form-field"  value="${data.pay_name}" <#if (data.status == 3)>disabled</#if>></td>
						</tr>
						<tr class="kh" <#if (data.pay_type == 1)>style="display: none"</#if>>
							<td class="tg-l">开户行：</td>
							<td><input type="text" name="pay_bank" class="am-input-sm am-form-field"  value="${data.pay_bank}" <#if (data.status == 3)>disabled</#if>></td>
						</tr>
						<tr>
							<td class="tg-l">说明：</td>
							<td>
								<textarea class="am-form-field am-radius" name="mybz" rows="3" name="ips" style="font-size: 14px">${data.mybz}</textarea>
							</td>
						</tr>
						</tbody>
					</table>
					<div class="dis20"></div>
					<input type="hidden" name="id" value="${data.id}"/>
					<button type="button" class="am-btn am-btn-secondary set-apply"><#if (data.id >0)>修改资料<#else>立即申请</#if></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="am-btn am-btn-default" data-am-modal-close>返回</button>
				</div>
			</div>
		</div>
	</#macro>
</script>
<script language="javascript">
	var apply_rz = function () {
		$.ajaxPassport({
			url:"<?php echo U("account@/finance/apply_tg");?>",
			success:function (res) {
				if (res.error == 0) {
					var html = "" + easyTemplate($("#tpl_add_apply").html(),res.data);
					$("#setline-a").html(html);
					$("#setline-a").find('#doc-modal-1-t').modal({width: 700});

					$("#doc-select-1").change(function () {
						if ($(this).val() == 1) {
							$(".kh").hide();
						}else{
							$(".kh").show();
						}
					});
					$(".set-apply").click(function () {
						var id = $("input[name='id']").val();
						var name = $("input[name='name']").val();
						var mobile = $("input[name='mobile']").val();
						var email = $("input[name='email']").val();
						var qq_ww = $("input[name='qq_ww']").val();
						var address = $("input[name='address']").val();
						var pay_no = $("input[name='pay_no']").val();
						var pay_name = $("input[name='pay_name']").val();
						var pay_bank = $("input[name='pay_bank']").val();
						var pay_type = $("#doc-select-1").val();
						var mybz = $("textarea[name='mybz']").val();
						if ($.is_empty(name)) {
							$.ui.error("联系人不能为空！");
							return false;
						}
						if ($.is_empty(mobile)) {
							$.ui.error("手机号不能为空！");
							return false;
						}
						if ($.is_empty(pay_no)) {
							$.ui.error("结算账号不能为空！");
							return false;
						}
						if ($.is_empty(pay_name)) {
							$.ui.error("收款人不能为空！");
							return false;
						}
						var preg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
						if (!$.is_empty(email) && !preg.test(email)) {
							$.ui.error("邮箱格式不正确！");
							return false;
						}
						$.ajaxPassport({
							url:"<?php echo U("account@/finance/apply_tg");?>",
							type:"POST",
							success:function (res) {
								if (res.error == 0) {
									$.ui.success(res.message);
									setTimeout(function () {
										$("#setline-a").find('#doc-modal-1-t').modal("close");
										window.location.replace("<?php echo U("account@/finance/tg");?>");
									},1000);
								}else{
									$.ui.error(res.message);
								}
							},
							data:{id:id,name:name,mobile:mobile,email:email,qq_ww:qq_ww,address:address,pay_no:pay_no,pay_name:pay_name,pay_bank:pay_bank,pay_type:pay_type,mybz:mybz,hash:"<?php echo tUtil::hash();?>"}
						});
					});
				}else{
					$.ui.error(res.message);
				}
			},
			data:{}
		});
	}
</script>
<!--申请认证 end-->
<!--推广用户-->
<script type="text/template" id="tpl_accountlog_list2">
	<#macro rowedit data>
		<table class="am-table">
			<col width="270"/>
			<col width="230"/>
			<col width="230"/>
			<col />
			<thead>
			<tr>
				<th>注册用户</th>
				<th>注册邮箱</th>
				<th>注册手机号</th>
				<th>注册时间</th>
			</tr>
			</thead>
			<tbody>
			<#if (data.list.length > 0)>
				<#list data.list as account>
					<tr>
						<td>${account.name}</td>
						<td ><font color="green">${account.email}</font></td>
						<td>${account.mobile}</td>
						<td><font style="color: gray">${$.time_to_string(account.dateline,"Y-m-d H:i:s")}</font></td>
					<tr>
				</#list>
				<#else>
				<tr><td colspan="4"><a href="##" class="am-icon-exclamation-circle am-text-danger am-text-lg"></a> <a href="##" class="am-font-gray">没有符合条件的结果?</a></td></tr>
			</#if>
			</tbody>
		</table>
		<div class="pagebar">${data.pagebar}</div>
	</#macro>
</script>
<script type="text/javascript">
	var load_account_list2 = function(page,keyword,startdate,enddate){
		var url = "<?php echo U("/finance/get_tg?do=get");?>";
		var keyword  = $.is_empty(keyword)?'':keyword;
		var startdate  = $.is_empty(startdate)?'':startdate;
		var enddate  = $.is_empty(enddate)?'':enddate;
		$.ajaxPassport({
			url:url,
			success:function(res){
				var listhtml = ""+ easyTemplate($("#tpl_accountlog_list2").html(),res);
				$(".am-finance-content").html(listhtml);
			},
			data:{page:page,keyword:keyword,startdate:startdate,enddate:enddate},
		});
	}
</script>
</body>
</html>