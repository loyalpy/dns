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
   <!--左边导航-->
    <div class="am-uc-left">
	  <div class="leftnav" id="Leftnav">
		  <ul>
			  <li class="aftergroup"><a href="javascript:void(0)" data-type="all" data-tit="全部域名" class="showtype">全部域名&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
			  <li><a href="javascript:void(0)" data-type="lastupdate" data-tit="最近操作"  class="showtype">最近操作&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
			  <li><a href="javascript:void(0)" data-type="error" data-tit="错误域名"  class="showtype">错误域名&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
			  <li><a href="javascript:void(0)" data-type="nogroup"  data-tit="未分组" class="showtype">未分组&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
		  </ul>
	  </div>
	</div>
    <!--左边导航结束-->
    <!--右边内容 -->
    <div class="am-uc-right">

		<?php $ad = tCache::read("ad_5");$time = time();?>
		<?php if(count($ad) > 0){?>
			<?php foreach($ad as $key => $item){?>
				<?php if($time > $item['start_dateline'] && $time < $item['end_dateline']){?>
					<?php if(M("domain")->get_one("uid  = '$uid'","count('domain')") > 0){?>
					<img src="<?php echo U("static@/$item[imgurl]");?>" class="img-ad"  style="cursor: pointer"/>
					<?php }else{?>
					<a href="<?php echo U("/domains/add");?>"><img src="<?php echo U("static@/$item[imgurl]");?>"/></a>
					<?php }?>
				<?php }?>
			<?php }?>
		<?php }?>
		<div class="dis10"></div>
	    <div>
	    	<h1><span class="list_tit_name">全部域名</span> <span class="list_tit_count am-text-sm am-text-success">(0)</span></h1>
	    </div>
		<div class="am-g">
			<a href="javascript:void(0)" class="am-btn am-btn-success am-dropdown-toggle am-radius am-btn-sm btn-adddomain">
			<span class="am-icon-plus"></span> 添加域名 
			</a>&nbsp;&nbsp;&nbsp;&nbsp;

			<div class="am-dropdown" data-am-dropdown>
				<button class="am-btn am-btn-default am-dropdown-toggle am-radius am-btn-sm" data-am-dropdown-toggle>分组 <span class="am-icon-caret-down"></span></button>
				<ul class="am-dropdown-content" style="width:200px;" id="Usetgroup">
					<li class="am-dropdown-header">添加分组</li>
					<li>
						<div style="padding:3px 18px;">
							<input type="text" style="width:100px;" id="add-group"/>&nbsp;<button class="am-btn am-btn-xs am-btn-success btn-add-group" type="button">添加</button>
						</div>
					</li>
					<?php foreach($data_config['domain_group'] as $key => $item){?>
					<li ><a href="javascript:void(0)" class="set_group" data-group_id="<?php echo isset($key)?$key:"";?>"><span style="font-size:12px;color:#999;">移动到</span> <?php echo isset($item)?$item:"";?></a></li>
					<?php }?>
					<li class="am-divider aftergroup"></li>
				</ul>
			</div>

			<div class="am-dropdown" data-am-dropdown>
				<button class="am-btn am-btn-default am-dropdown-toggle am-radius am-btn-sm" data-am-dropdown-toggle>更多操作 <span class="am-icon-caret-down"></span></button>
				<ul class="am-dropdown-content">
					<li class="am-dropdown-header">选择后操作</li>
					<li><a href="javascript:void (0)" class="domainOption" data-do="stop">暂停</a></li>
					<li><a href="javascript:void (0)" class="domainOption" data-do="start">启用</a></li>
					<li class="am-divider"></li>
					<li><a href="javascript:void (0)" class="domainOption" data-do="lock">锁定</a></li>
					<li><a href="javascript:void (0)" class="domainOption" data-do="unlock">解锁</a></li>
					<li class="am-divider"></li>
					<li><a href="javascript:void (0)" class="domainOption" data-do="del">删除</a></li>
					<li><a href="javascript:void (0)" class="domainTransfer">过户</a></li>
					<li class="am-divider"></li>
					<li><a href="<?php echo U("/domains/add");?>" data-do="stop">批量添加域名</a></li>
					<li><a href="javascript:void (0)" class="domainFind">域名找回</a></li>
				</ul>
			</div>&nbsp;&nbsp;&nbsp;

			<a href="<?php echo U("/domains/add");?>" class="am-text-sm">批量添加域名</a>


			<div class="quickserch" style="float: right;width: 200px;">
				<input type="text" class="am-form-field am-radius am-input-sm am-serch-domains" placeholder="快速查找域名" />
			</div>
		</div>
		<div class="dis10"></div>
	 	<div class="listbody" style="position: relative;">
	 		<table class="am-table am-table-hover">
	            <col width="30px"/>
	            <col  />
	            <col width="150px" />
	            <col width="200px" />
	            <thead>
	            <tr>
	                <th><input type="checkbox" data-name="domainId[]" class="checkall"/></th>
	                <th>域名</th>
	                <th></th>
	                <th class="am-text-right"></th>
	            </tr>
	            </thead>
	            <tbody class="tpl am-form">
	            </tbody>
	         </table>
	 	</div>
	 	<div class="pagebar"></div>
		<div class="my-domian-upgrade"></div>
		<div class="my-domian-find"></div>
		<div class="my-domian-transfer"></div>
    </div>
    <!--右边内容结束 -->
    <div class="am-cf"></div>
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
<?php echo $this->fetch('domains/domains_tpl')?>
<?php echo $this->fetch('order/order_cart')?>
<?php if($this->userinfo['bd']['status'] != 2){?>
<?php echo $this->fetch('misc/wxbd')?>
<?php }?>
<script type="text/javascript">
	var check_nsdomain_url = "<?php echo tUtil::js('api@/Common/CheckNS');?>";
	var check_expirydomain_url = "<?php echo tUtil::js('api@/Common/CheckExpiry');?>";

	$(function(){
		$("a").bind("focus",function(){
			$(this).blur();
		});
		//添加域名
		$(".btn-adddomain").click(function(){
			var strId = $(".listbody").find("input[name='domainId[]']").val();
			if (typeof strId == "undefined") {
				$(".listbody").find("tr.d-t-intor").remove();
			}
			add_domain();
		});
		//添加分组
		$(".btn-add-group").click(function(){
			add_domains_group(this);
		});
		//域名列表批量操作
		$(".domainOption").click(function(){
			batch_domain_op(this);
		});
		//域名找回
		$(".domainFind").click(function () {
			domain_find_op();
		});
		//域名过户
		$(".domainTransfer").click(function(){
			domain_transfer_op();
		});
		//搜索功能
		$("input.am-serch-domains").keyup(function(){
			var keyword = $(this).val();
			var type = $("#Leftnav").find(".cur").attr("data-type");
			var group_id = $("#Leftnav").find(".cur").attr("data-group_id");
			if (typeof  type == "undefined") {
				type = "all";
			}
			if (typeof group_id == "undefined") {
				group_id = 0;
			}
			if (!$.is_empty(keyword)){
				load_domains_list(1,type,group_id,keyword);
			}else{
				load_domains_list(1,type,group_id);
			}
		});

		//点击广告升级套餐
		$(".img-ad").unbind("click").bind("click",function(){
			var utype = "<?php echo isset($utype)?$utype:"";?>";
			if (utype == 2) {
				add_cart_step1(1,0, "vvip1", "");
			}else{
				add_cart_step1(1,0, "vip1", "");
			}
		});

		//加载分组列表
		load_domains_group("all");
		//加载域名列表
		load_domains_list(1,"all");
	})
</script>
</body>
</html>