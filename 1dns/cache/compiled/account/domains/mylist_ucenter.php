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
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">

<link rel="stylesheet" href="<?php echo U("/assets/css/amazeui.min.css");?>">
<link rel="stylesheet" href="<?php echo U("static@/css/style_uc.css");?>">
<link rel="stylesheet" href="<?php echo U("static@/css/style_uc_py.css");?>"></head>
<body>
<header class="am-topbar am-topbar-inverse">
  <div class="am-container">
    <h1 class="am-topbar-brand">
    <a href="<?php echo U("home@/");?>" class="logo"><img src="<?php echo U("static@/images/logo.png");?>" alt="" /></a>
      <span class="logotit"></span>
    </h1>
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-secondary am-show-sm-only"
            data-am-collapse="{target: '#collapse-head'}"><span class="am-sr-only">导航切换</span> <span
        class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="collapse-head">
      <ul class="am-nav am-nav-pills am-topbar-nav">
        <li><a href="<?php echo U("/domains/mylist");?>">我的域名</a></li>
        <li class="am-dropdown setting" data-am-dropdown>
          <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
           更多 <span class="am-icon-caret-down"></span>
          </a>
          <ul class="am-dropdown-content">
            <li><a href="#">八戒监控</a></li>
            <li><a href="#">购买套餐</a></li>
            <li><a href="#">帮助中心</a></li>
          </ul>
        </li>        
      </ul>
      <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
      <li class="am-dropdown setting" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-user"></span> <?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?> <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="<?php echo U("/ucenter/profile_basic");?>"><span class="am-icon-user"></span> 我的资料</a></li>
          <li><a href="<?php echo U("/ucenter/profile_passwd");?>"><span class="am-icon-cog"></span> 修改密码</a></li>
          <li><a href="<?php echo U("/ucenter/safety_center");?>"><span class="am-icon-flask"></span> 安全中心</a></li>
          <?php if($this->userinfo['urole'] > 0){?>
          <li><a target="_blank" href="<?php echo U("admin@/");?>"><span class="am-icon-th-large"></span> 后台</a></li>
          <?php }?>
          <li><a href="<?php echo U("/login/logout");?>"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>
      <li>
      <a href="javascript:;" style="padding:0 10px;"><span class="am-icon-cart-plus"></span> 
      <span class="am-badge am-badge-warning am-round">5</span> 购物车
      </a>
      </li>
      <li>
      <a href="<?php echo U("/ucenter/profile_msg");?>" style="padding:0 10px;"><span class="am-icon-envelope-o"></span>
      <span class="am-badge am-badge-warning am-round"><?php echo isset($this->userinfo['no_view_infor'])?$this->userinfo['no_view_infor']:"";?></span></a>
      </li>
    </ul>      
    </div>
  </div>
</header>
<div class="am-container">
<div class="dis30"></div>
<div class="am-uc-left">
  <div class="leftnav" id="Leftnav">
	  <ul>
		  <li><a href="javascript:void(0)" data-type="all"  class="showtype">全部域名&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
		  <li><a href="javascript:void(0)" data-type="lastupdate"  class="showtype">最近操作&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
		  <li class="aftergroup"><a href="javascript:void(0)" data-type="error"  class="showtype">错误域名&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
		  <li><a href="javascript:void(0)" data-type="nogroup"  class="showtype">未分组&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
	  </ul>
  </div>
</div>
<div class="am-uc-right">
	<div class="am-g am-uc-right-tit">
		<span class="btn-g-name">我的域名</span>
		<a href="<?php echo U("/domains/add");?>" class="am-btn am-btn-success am-dropdown-toggle am-radius am-btn-sm"><span class="am-icon-plus"></span> 添加域名 </a>&nbsp;&nbsp;&nbsp;&nbsp;
		<div class="am-dropdown" data-am-dropdown>
			<button class="am-btn am-btn-secondary am-dropdown-toggle am-radius am-btn-sm" data-am-dropdown-toggle>操作 <span class="am-icon-caret-down"></span></button>
			<ul class="am-dropdown-content">
				<li class="am-dropdown-header">选择后操作</li>
				<li><a href="javascript:void (0)" class="domainOption" data-do="stop">暂停</a></li>
				<li><a href="javascript:void (0)" class="domainOption" data-do="start">启用</a></li>
				<li class="am-divider"></li>
				<li><a href="javascript:void (0)" class="domainOption" data-do="lock">锁定</a></li>
				<li><a href="javascript:void (0)" class="domainOption" data-do="unlock">解锁</a></li>
				<li class="am-divider"></li>
				<li><a href="javascript:void (0)" class="domainOption" data-do="del">删除</a></li>
				<li><a href="javascript:void (0)" class="domainOption" data-do="change">过户</a></li>
			</ul>
		</div>

		<div class="am-dropdown" data-am-dropdown>
			<button class="am-btn am-btn-warning am-dropdown-toggle am-radius am-btn-sm" data-am-dropdown-toggle>分组 <span class="am-icon-caret-down"></span></button>
			<ul class="am-dropdown-content" style="width:270px;" id="Usetgroup">
				<li class="am-dropdown-header">添加分组</li>
				<li>
					<div style="padding:3px 18px;">
						<input type="text" style="width:150px;" id="add-group"/>&nbsp;<button class="am-btn am-btn-xs am-btn-success btn-add-group" type="button">添加</button>
					</div>
				</li>
				<?php foreach($data_config['domain_group'] as $key => $item){?>
				<li ><a href="javascript:void(0)" class="set_group" data-group_id="<?php echo isset($key)?$key:"";?>"><span style="font-size:12px;color:#999;">移动到</span> <?php echo isset($item)?$item:"";?></a></li>
				<?php }?>
				<li class="am-divider aftergroup"></li>
			</ul>
		</div>
		<div class="quickserch" style="float: right;width: 200px;">
			<input type="text" class="am-form-field am-radius am-input-sm am-serch-domains" placeholder="快速查找域名" />
		</div>
	</div>
 	<div class="listbody" style="position: relative;"></div>
 	<div class="pagebar"></div>
</div>
<div class="am-cf"></div>
</div>
<footer class="footer">
  <p>© 2012-2016 <a href="http://www.8jdns.com" target="_blank">8JDNS.com.</a> by the 8JDNS Team.</p>
</footer>
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
<script src="<?php echo U("/assets/js/amazeui.min.js");?>"></script>
<script src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<?php if($uid){?>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<?php }?>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>
</script>
<?php echo $this->fetch('domains/domains_tpl')?>
<script type="text/javascript">
	var check_nsdomain_url = "<?php echo tUtil::js('api@/Common/CheckNS');?>";
	$(function(){
		$("a").bind("focus",function(){
			$(this).blur();
		});
		$(".btn-add-group").click(function(){
			add_domains_group(this);
		});
		$(".domainOption").click(function(){
			batch_domain_op(this);
		});
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
		load_domains_group("all");
		load_domains_list(1,"all");
	})
</script>
</body>
</html>