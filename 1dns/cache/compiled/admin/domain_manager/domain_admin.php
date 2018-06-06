<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">
  <title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
  
  <meta name="keywords" content="<?php echo isset($site['seo_keyword'])?$site['seo_keyword']:"";?>" />
  <meta name="description" content="<?php echo isset($site['seo_description'])?$site['seo_description']:"";?>" />
      <link href="<?php echo U("/static/javascript/bootstrap/css/bootstrap.min.css");?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_admin.css";?>"  rel="stylesheet" type="text/css" />
    <?php if(isset($style) && $style != "default"){?>
    <link href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_admin_$style.css";?>"  rel="stylesheet" type="text/css" />
    <?php }?>
    
<link href="<?php echo U("static@/javascript/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css");?>" type="text/css" rel="stylesheet">
    <!--[if lt IE 9]><script src="<?php echo U("static@/javascript/html5/ie8-responsive-file-warning.js");?>"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo U("static@/javascript/html5/html5shiv.js");?>"></script>
      <script src="<?php echo U("static@/javascript/html5/respond.min.js");?>"></script>
    <![endif]-->
    <script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
    <script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};
    <?php if($uid){?>
    tUser['uid'] = <?php echo isset($uid)?$uid:"";?>;
  <?php }?>
    </script>
</head>
<body class="ucenter">
<div class="topbar">
<div class="aps">
  <div class="in-left">
    <ul class="">
    <li><a href="<?php echo U("home@/");?>" target="_blank" class=" font-gray"><cite class="glyphicon glyphicon-home"></cite> 网站首页</a></li>
    <li class="dropdown">
     
    </li>
    </ul>
  </div>
  <div class="in-center">
      
  </div>
  <div class="in-right">
  <ul>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?> <b class="caret"></b></a>
    <ul class="dropdown-menu">
       <li><a href="<?php echo U("account@/ucenter/profile_basic");?>">个人资料修改</a></li>
       <!--<li><a href="<?php echo U("account@/ucenter/profile_avatar");?>" target="_blank">个人头像修改</a></li>-->
       <li><a href="<?php echo U("account@/ucenter/profile_passwd");?>">修改密码</a></li>
       <li class="divider"></li>
       <li><a href="<?php echo U("account@/login/logout");?>">退出</a></li>
     </ul>
     </li>
     
   </ul>
  </div>
  <div class="cl"></div>
</div>
</div>
<div class="header">
  <div class="aps">
      <div class="logo"><a href="/sys_manager" title=""><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>" alt="八戒DNS" /></a></div>
      
    <div class="menu">
    <ul>
		<?php if($this->check_purview("/domain_manager/domain")){?>
		<li><a href="<?php echo U("/domain_manager/domain");?>" class="cur">域名列表</a></li>
		<?php }?>
		<?php if($this->check_purview("/domain_manager/domain_qy")){?>
		<li><a href="<?php echo U("/domain_manager/domain_qy");?>">域名牵引</a></li>
		<?php }?>
		<?php if($this->check_purview("/domain_manager/domain_log")){?>
		<li><a href="<?php echo U("/domain_manager/domain_log");?>">域名操作日志</a></li>
		<?php }?>
		<?php if($this->check_purview("/domain_manager/domain_log_switch")){?>
        <li><a href="<?php echo U("/domain_manager/domain_log_switch");?>" >域名切换日志</a></li>
		<?php }?>
		<?php if($this->check_purview("/domain_manager/domain_black")){?>
		<li><a href="<?php echo U("/domain_manager/domain_black");?>" >黑白名单</a></li>
		<?php }?>
		<?php if($this->check_purview("/domain_manager/domain_deleted")){?>
		<li><a href="<?php echo U("/domain_manager/domain_deleted");?>" >已删除域名</a></li>
		<?php }?>
		<?php if($this->check_purview("/domain_manager/domain_find")){?>
		<li><a href="<?php echo U("/domain_manager/domain_find");?>" >域名找回</a></li>
		<?php }?>
		<?php if($this->check_purview("/domain_manager/domain_diyline")){?>
		<li><a href="<?php echo U("/domain_manager/domain_diyline");?>" >自定义线路</a></li>
		<?php }?>
		<?php if($this->check_purview("/domain_manager/domain_bind")){?>
		<li><a href="<?php echo U("/domain_manager/domain_bind");?>">别名绑定</a></li>
		<?php }?>
    </ul>
    </div>
      <div class="cl"></div>
    </div>
</div>
<div class="main">
<div class="leftnav" id="Lnav">
        <div class="quick_btn" style="display:none;"></div>
        <div class="dis10"></div>
        <?php foreach($this->nav['nav'] as $key => $item){?>
      <dl>
      <?php if($item['status'] == 1 && $this->check_purview($item['url'])){?>
        <dt><a href="javascript:void(0)" _key="<?php echo isset($key)?$key:"";?>" _href="<?php echo isset($item['url'])?$item['url']:"";?>"><cite class="glyphicon glyphicon-<?php echo isset($item['enname'])?$item['enname']:"";?>"></cite> &nbsp;<?php echo isset($item['name'])?$item['name']:"";?> <cite class="updown glyphicon glyphicon-chevron-<?php if($item['isopen'] == 1 || $this->nav['top'] == $key){?>down<?php }else{?>up<?php }?>"></cite></a></dt>
        <?php foreach($item['childrens'] as $key2 => $sub){?>
        <?php if($sub['status'] == 1 && $this->check_purview($sub['url'])){?>
        <dd class="<?php if($this->nav['sub'] == $key2){?>cur<?php }?>" <?php if($item['isopen'] == 1 || $this->nav['top'] == $key){?><?php }else{?>style="display:none;"<?php }?>><a href="<?php echo isset($sub['url'])?$sub['url']:"";?>"><cite class="glyphicon glyphicon-<?php echo isset($sub['enname'])?$sub['enname']:"";?>"></cite> &nbsp;<?php echo isset($sub['name'])?$sub['name']:"";?> </a></dd>
        <?php }?>
        <?php }?>
      <?php }?>
      </dl>
      <?php }?>
    <script language="javascript">
       $(function(){
           $("#Lnav dl dt").click(function(){
              var obj = this;
              if($(obj).find("cite.updown").hasClass("glyphicon-chevron-down")){
                 $(obj).find("cite.updown").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
                 $(obj).parent().find("dd").hide();
              }else{
                 $(obj).find("cite.updown").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
                 $(obj).parent().find("dd").show();
              }         
           });
       })
     </script>
     <div class="extnav">
     <dl>
     <?php if($this->check_purview('/sys_manager/module_config')){?>
     <dd class="<?php if(in_array($this->nav['uri'],array('/sys_manager/module_config'))){?>cur<?php }?>"><a href="/sys_manager/module_config">模块配置</a></dd>
     <?php }?>
     <dd class="<?php if(in_array($this->nav['uri'],array('/ucenter/profile_passwd'))){?>cur<?php }?>"><a href="<?php echo U("account@/ucenter/profile_passwd");?>">修改密码</a></dd>
         <dd><a href="<?php echo U("account@/login/logout");?>">退出 </a></dd>
     </dl>
     </div>
</div>

<div class="main-nav">
  <div class="name">域名列表</div>
	<?php if($this->check_purview("/domain_manager/domain_expserch")){?>
	<div class="navbtn0" style="width: 600px">
		<?php $time_s = array("9"=>"已到期","1"=>"一周內","2"=>"半月內","3"=>"一月內")?>
		<a class="btn <?php if($expire == '0'){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="" href="<?php echo U("/domain_manager/domain?expire=0");?>"><cite class="glyphicon glyphicon-th"></cite> 所有</a>&nbsp;<span class="badge badge-s"><?php echo isset($badge[0])?$badge[0]:"";?></span>
		<?php foreach($time_s as $key => $item){?>
		<a class="btn <?php if($expire == $key){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="<?php echo isset($item)?$item:"";?>" href="<?php echo U("/domain_manager/domain?expire=$key");?>"><cite class="glyphicon glyphicon-th"></cite> <?php echo isset($item)?$item:"";?></a>&nbsp;<span class="badge badge-s"><?php echo isset($badge[$key])?$badge[$key]:"";?></span>
		<?php }?>
	</div>
	<?php }?>
  <div class="navbtn">
	  <!--button-->
	  <?php if($this->check_purview("/domain_manager/domain_refreshall")){?>
	  <div class="btn-group">
		  <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  刷新纪录 <span class="caret"></span>
		  </button>
		  <ul class="dropdown-menu" style="text-align: left">
			  <?php foreach($service_group_v as $key => $item){?>
				  <li><a href="javascript:void(0);" data-service_group="<?php echo isset($item['key'])?$item['key']:"";?>"  class="batch-refresh"><?php echo isset($item['v'])?$item['v']:"";?></a></li>
				  <?php if($key == 4){?>
				  <li role="separator" class="divider"></li>
				  <?php }?>
			  <?php }?>
		  </ul>
	  </div>
	  <?php }?>
	  <?php if($this->check_purview("/domain_manager/domain_batchdel")){?>
	  <button type="button" class="btn btn-sm btn-default del-domain">批量删除</button>
	  <?php }?>
	  <a href="javascript:void(0);" style="display: none;" class="btn btn-success btn-sm addbtn"><cite class="glyphicon glyphicon-plus"></cite>新增域名</a>
	  <?php if($this->check_purview("/domain_manager/domain_checkall")){?>
	  <button type="button" class="btn btn-sm btn-sm btn-danger batch-checkns">检测NS</button>
	  <button type="button" class="btn btn-sm btn-sm btn-default batch-checkexpiry">检测Expiry</button>
	  <?php }?>
	  <!--end button-->
  </div>
  <div class="cl"></div>
</div>
<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/domain_manager/domain?do=get_url");?>">
	<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
<div class="alert alert-info" role="alert" style="height: 35px;line-height: 5px;margin: 10px 10px;background-color: #ffffff">
	<span>已指向我们：<strong class="font-org" style="font-size: 16px"><?php echo isset($in_our_ns)?$in_our_ns:"";?></strong></span>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php foreach($service_group_v as $key => $item){?>
	<span><?php echo isset($item['v'])?$item['v']:"";?>：<strong class="font-org" style="font-size: 16px"><?php echo isset($badge_service[$item['key']])?$badge_service[$item['key']]:"";?></strong></span>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php }?>
</div>
<div class="list-box">
<table class="list-table table table-condensed table-responsive" cellpadding="0" cellspacing="0">
	<col width="30px"/>
	<col />
	<col width="200px" />
	<col width="180px" />
	<col width="180px" />
	<col width="78px"/>
	<col width="150px"/>
	<thead>
	<tr>
	<th><input type="checkbox" data-name="domains[]" class="checkall"/></th>
	<th>域名
&nbsp;<a href="javascript:void(0)" class="orderby" data-item="domain" data-desc=""><cite></cite></a>
&nbsp;记录数&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="records" data-desc=""><cite></cite></a>
	&nbsp;套餐&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="service_group" data-desc=""><cite></cite></a>
	</th>
	<th>用户&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="uid" data-desc=""><cite></cite></a>
	&nbsp;添加&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="dateline" data-desc=""><cite></cite></a>
		&nbsp;更新&nbsp;
		<a href="javascript:void(0)" class="orderby" data-item="lastupdate" data-desc=""><cite></cite></a>
	</th>
	<th>服务器组&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="ns_group" data-desc=""><cite></cite></a></th>
	<th>当前NS</th>
	<th>状态&nbsp;<a href="javascript:void(0)" class="orderby" data-item="status" data-desc=""><cite></cite></a></th>
	<th>操作</th>
	</tr>
	</thead>
	<tbody class="tpl"></tbody>
</table>
</div>
<div class="pagebar"></div>
</form>
<!-- end list box -->
<div class="cl"></div></div>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/layer/layer.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/easyTemplate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.init.admin.js");?>"></script>
<script language="javascript" src="<?php echo U("static@javascript/jquery.zclip/jquery.zclip.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static/cache/dataconfig.js");?>"></script>
<?php echo $this->fetch('tpl/form')?>
<?php echo $this->fetch('user_manager/email_tpl')?>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>


<script type="text/template" id="tpl_list_row">
<#macro row data>
    <tr class="domain_${data.domain_id}" <#if (data.inns == 0)>style="background-color:#FFF0F0;"</#if>>
    <td class="font-black"><input type="checkbox" name="domains[]" value="${data.domain_id}"/></td>
    <td class="font-blue find-count-domain-t" data-domain_id="${data.domain_id}" data-domain="${data.domain}"  data-toggle="collapse"
		href="#collapseExample_${data.domain_id}" style="cursor: pointer">
			<span class="keybox f16"><#if (data.is_cn==1)>${data.domain_cn}<#else>${data.domain}</#if></span>
			<#if (data.is_our_reg == 1)><span class="font-green" title="官网注册域名" style="cursor: pointer">&nbsp;[官]</span><#else></#if>
			&nbsp;<span class="font-gray"><font class="font-gray2">(<font class="font-org find-count-domain-t1" data-type="3">${data.records}</font>)</font></span>&nbsp;&nbsp;
		<br/>
		<#if (data.service_group=='free')>
				<span class="font-gray find-count-domain-t1" data-type="1" style="cursor: pointer">
						<#if  (!$.is_empty(service_groups[data.service_group]))>${service_groups[data.service_group].name}<#else> - </#if> [永久]
					</span>
				<#else>
					<span class="font-org find-count-domain-t1" data-type="1" data-domain="${data.domain}" style="cursor: pointer">[<#if  (!$.is_empty(service_groups[data.service_group]))>${service_groups[data.service_group].name}<#else> - </#if>]</span>
						<span class="font-gray2 find-count-domain-t1" data-type="1">[
						<#if (data.service_expiry_pass)>
							<span class="font-black">${data.service_expiry}</span><span class="font-red">(${data.service_expiry_pass})</span>
							<#else>
								<span class="font-green">${data.service_expiry}</span>
						</#if>
						]</span>
			</#if>&nbsp;<br/>
			<#if (data.noreg === 1)><font class="font-red">[未注册]</font></#if>
			<#if (data.expiry != "0000-00-00")><span class="font-gray2 find-count-domain-t1" data-type="1">(${data.expiry})</span></#if>
			<span class="font-gray">[${data.qps} QPS]</span>&nbsp;
	</td>
    <td class="font-gray find-count-domain-t" data-domain_id="${data.domain_id}" data-domain="${data.domain}"  data-toggle="collapse"
		href="#collapseExample_${data.domain_id}">
		<a href="javascript:void (0);" data-type="2" data-url="<?php echo U("/domain_manager/domain?uid=");?>${data.uid}"  class="link-alert-x">${data.user}</a>&nbsp;<cite class="glyphicon glyphicon-envelope font-gray diy-send-email" data-email="${data.email}" title="自定义邮件发送" style="cursor: pointer"></cite><br/>
		<span class="timestemp-stat f11 font-gray2 call_link">${data.dateline}</span></br>
		<span class="timestemp-stat f11 font-gray2 call_link">${data.lastupdate}</span>
	</td>
	<td class="font-org find-count-domain-t" data-domain_id="${data.domain_id}" data-domain="${data.domain}"  data-toggle="collapse"
		href="#collapseExample_${data.domain_id}">
		<#if  (!$.is_empty(ns_groups[data.ns_group]))>
			<a href="javascript:void (0);" data-type="2" data-url="<?php echo U("/domain_manager/domain?ns_group=");?>${data.ns_group}"  class="<#if (data.ns_group=='free')>font-gray<#else>font-org</#if> link-alert-x">
				${ns_groups[data.ns_group].name}
			</a><#else> -</#if><br/>
		<span class="font-gray call_link">
		<#if (!$.is_empty(data.ns_group_ns))>
             ${data.ns_group_ns.replace(/;/g,"<br/>")}
        </#if>
		</span>
	</td>
	<td class="find-count-domain-t" data-domain_id="${data.domain_id}" data-domain="${data.domain}"  data-toggle="collapse"
		href="#collapseExample_${data.domain_id}">
		<#if (data.inns == 1)>
			<font class="font-green call_link">已指向</font>
			<#else>
		 <font class="font-gray2 call_link">${data.ns.replace(/;/g,"<br/>")}</font>
		 </#if>
		<?php if($this->check_purview("/domain_manager/domain_checkns")){?>
		 <cite data-domain="${data.domain}" class="glyphicon glyphicon-eye-open font-black btn-view-ns" title="查询NS"></cite>
		<?php }?>
	</td>
	<td class="find-count-domain-t" data-domain_id="${data.domain_id}" data-domain="${data.domain}"  data-toggle="collapse"
		href="#collapseExample_${data.domain_id}">
	   <#if (data.indel == 1)>
	   	<cite class="glyphicon glyphicon-remove font-red call_link"></cite>
	   	<span class="font-red call_link">[锁]</span>
	   <#else>
		<cite class="<#if (data.status==1)>glyphicon glyphicon-ok font-green<#else>glyphicon glyphicon-pause</#if> call_link" title="正常"></cite>
	   </#if>
	</td>
	<td class="find-count-domain-t" data-domain_id="${data.domain_id}" data-domain="${data.domain}"  data-toggle="collapse"
		href="#collapseExample_${data.domain_id}">
		<p class="table-item-op">
			<?php if($this->check_purview("/domain_manager/records")){?>
			<a href="javascript:void (0);"  class="link-alert-x" data-type="2" data-url="<?php echo U("/domain_manager/records?keyword=");?>${data.domain}"  title="查看域名解析"><span class="glyphicon glyphicon-eye-open"></span></a>&nbsp;
			<?php }?>
			<?php if($this->check_purview("/domain_manager/domain_refresh")){?>
			<a href="javascript:void(0);" class="refrebtn" title="刷新域名记录" data-url="<?php echo U("/domain_manager/domain_refresh?domain=");?>${data.domain}"><span class="glyphicon glyphicon-refresh"></span></a>&nbsp;
			<?php }?>
			<?php if($this->check_purview("/domain_manager/domain_edit")){?>
			<a href="javascript:void(0);" class="editbtn"  data-url="<?php echo U("/domain_manager/domain_edit?mark=edit&domain_id=");?>${data.domain_id}" title="更改"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;
			<?php }?>
			<?php if($this->check_purview("/domain_manager/domain_del")){?>
			<a href="javascript:void(0);" class="delbtn" confirm="1" data-url="<?php echo U("/domain_manager/domain_del?domain_id=");?>${data.domain_id}" title="删除"><span class="glyphicon glyphicon-remove"></span></a>&nbsp;
			<?php }?>
			<?php if($this->check_purview("/user_manager/userlist_quicklogin")){?>
			<a href="javascript:void (0);" class="link-alert-x"  data-type="1" data-url="<?php echo U("/user_manager/userlist_quicklogin?uid=");?>${data.uid}"  target="_blank" title="快速登录"><span class="glyphicon glyphicon-user" title="快速登录"></span></a>&nbsp;
			<?php }?>
			<a class="btn btn-xs btn-default" type="button" href="javascript:void (0);">更多操作 <span class="caret"></span></a>&nbsp;
		<?php if($this->check_purview("/domain_manager/domain_qy_lock")){?>
			<#if (data.indel == 0)>
			<button type="button" class="btn btn-xs btn-danger btn-op-indel" data-url="<?php echo U("/domain_manager/domain_qy_lock?domain=");?>${data.domain}" data-domain="${data.domain}" data-indel="1">锁定</button>
			</#if>
		<?php }?>
		</p>
	</td>
	</tr>
	<tr class="count_domain_${data.domain_id} hide">
		<td colspan="7">
			<div class="collapse" id="collapseExample_${data.domain_id}">
				<div class="" style="padding: 10px 10px 0px 30px;">
					<button type="button" class="btn btn-default btn-sm find-liuliang" data-domain="${data.domain}" data-domain_id="${data.domain_id}">流量图</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm find-order" data-domain_id="${data.domain_id}">续费历史</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm find-record"  data-domain_id="${data.domain_id}">解析记录</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm btn-setting-log" data-domain_id="${data.domain_id}">操作日志</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm btn-exchange-log"  data-domain_id="${data.domain_id}" style="margin-right: 80px">切换日志</button>


					<button type="button" class="btn btn-default btn-sm table-item-op-recharge" data-status="${data.status}"  data-url="<?php echo U("/domain_manager/domain_change_status?domain_id=");?>${data.domain_id}"><#if (data.status==1)>暂停<#else>启用</#if></button>&nbsp;
					<button type="button" class="btn btn-default btn-sm btn-setting-change"  data-url="<?php echo U("/domain_manager/domain_change?domain_id=");?>${data.domain_id}">切换用户</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm btn-setting-service"  data-url="<?php echo U("/domain_manager/domain_change?domain_id=");?>${data.domain_id}">切换套餐</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm btn-setting-group"  data-url="<?php echo U("/domain_manager/domain_change?domain_id=");?>${data.domain_id}">切换服务器组</button>&nbsp;

					<button type="button" style="float: right" class="btn btn-default btn-sm find-count-domain-t" data-domain_id="${data.domain_id}" data-domain="${data.domain}" role="button" data-toggle="collapse" href="#collapseExample_${data.domain_id}" aria-expanded="false" aria-controls="collapseExample">关闭</button>
				</div>
				<div class="" style="padding: 15px 30px 0px 30px;width:1080px;">
					<div class="countimage-box" id="user_reg_month_${data.domain_id}" style="width: 1080px; height:100%; margin: 0 auto"><img alt="正在加载中" class="loading" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/loading2.gif";?>" /></div>
					<div class="dis30"></div>
				</div>
			</div>
		</td>
	</tr>
</#macro>
</script>

<?php echo $this->fetch('domain_manager/domain_op')?>
<!--搜索框 start-->
<script language="javascript">
	var alert_type = 0;
	var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
	var expire = "<?php echo isset($expire)?$expire:"";?>";
	var initpage = 1;
	var check_nsdomain_url = "<?php echo tUtil::js('api@/Common/CheckNS');?>";
    var check_expirydomain_url = "<?php echo tUtil::js('api@/Common/CheckExpiry');?>";
	var search_formdata = {
		email: {type: "uid", label: "用户", name: "email", value: "<?php echo isset($condi['uid'])?$condi['uid']:"";?>", uname:"<?php echo isset($condi['uname'])?$condi['uname']:"";?>",disabled: 0, data_sr:[], css: "", require: "", desc: "", item_css: ""},
		service_group: {type: "select", label: "服务套餐", name: "service_group", value: "<?php echo isset($condi['service_group'])?$condi['service_group']:"";?>", disabled: 0, data_sr:<?php echo JSON::encode($service_group_v);?>, css: "", require: "", desc: "", item_css: ""},
		inns: {type: "radio", label: "NS指向", name: "inns", value: "<?php echo isset($condi['inns'])?$condi['inns']:"";?>", data_sr:[{v: "NS指向", key: "2"}, {v: "未指向", key: "1"}], css: "", require: "", desc: "", item_css: "item-200",cl:true},
		startdate:{type:"date",label:"到期",name:"startdate",value:"<?php echo isset($condi['startdate'])?$condi['startdate']:"";?>",data_sr:[],css:"shigh",require:"",desc:"",item_css:""},
		enddate:{type:"date",label:"-",name:"enddate",value:"<?php echo isset($condi['enddate'])?$condi['enddate']:"";?>",data_sr:[],css:"shigh",require:"",desc:"",item_css:"date-dis"},
		keyword:{type:"text",label:"关键词",name:"keyword",value:"<?php echo isset($condi['keyword'])?$condi['keyword']:"";?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
		expire:{type:"hidden",label:'',name:"expire",value:expire},
		btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
	};
	var loadlist = function(page,orderby){
		$.ajaxLoadlist(page,pageurl+(typeof orderby=="undefined"?"":("&orderby="+orderby)),function(res){
			initpage = page;
			var keyword = $(".the_searchform input[name='keyword']").val();
			if(keyword != ""){
				$(".thelistform").find(".tpl .keybox").each(function(){
					var obj = this;
					var html = $(obj).html();
					$(obj).html($.replace_keyword(html,keyword))
				});
			};
			//全选，全不选
			$(".list-table").find("input.checkall").unbind("click").bind("click",function(){
				$.check_all(this);
			});
			$(".addbtn").click(function(){
				edit_func_add(this);
			});
			$(".editbtn").click(function(){
				edit_func(this);
				return false;
			});
			$(".delbtn").click(function(){
				del_func(this);
				return false;
			});
			$(".refrebtn").click(function(){
				refre_btn(this);
				return false;
			});
			$(".table-item-op-recharge").click(function(){
				count_js(this);
				recharge_btn(this);
			});
			$(".btn-setting-change").click(function(){
				count_js(this);
				edit_setting_change_func(this);
			});
			$(".btn-setting-service").click(function(){
				count_js(this);
				edit_setting_service_func(this);
			});
			$(".btn-setting-group").click(function(){
				count_js(this);
				edit_setting_group_func(this);
			});
			$(".link-alert-x").click(function(){
				var url = $(this).attr("data-url");
				var type = $(this).attr("data-type");
				if (type == 1) {
					window.open(url);
				}else{
					window.location.replace(url);
				}
				return false;
			});
			$(".call_link").click(function () {
				return false;
			});
			//域名操作日志
			$(".btn-setting-log").click(function(){
				count_js(this);
				var domain_id = $(this).attr("data-domain_id");
				edit_setting_log_func(1,domain_id);
			});
			$(".find-record").unbind("click").bind("click",function () {
				count_js(this);
				var domain_id = $(this).data("domain_id");
				show_domain_records(1,domain_id);
			});
			//域名续费历史
			$(".find-order").unbind("click").bind("click",function () {
				count_js(this);
				var domain_id = $(this).data("domain_id");
				show_domain_order(1,domain_id);
			})
			//锁定牵引
			$(".btn-op-indel").click(function(){
				edit_setting_qy(this);
				return false;
			});
			//自定义邮箱发送
			$(".diy-send-email").click(function () {
				send_email_usual(this);
				return false;
			});
			//域名切换日志
			$(".btn-exchange-log").click(function () {
				count_js(this);
				var domain_id = $(this).attr("data-domain_id");
				exchenge_log(1,domain_id);
			});
			//查看统计图1
			$(".find-count-domain-t").click(function () {
				show_map_list(this,alert_type);
				alert_type = 0;
				return true;
			});
			$(".find-count-domain-t1").click(function () {
				var type = $(this).data("type");
				alert_type = type;
			});
			$(".find-liuliang").click(function () {
				count_js(this);
				var domain = $(this).attr("data-domain");
				var domain_id = $(this).attr("data-domain_id");
				domain_parse_count(domain_id,domain);
			});

			//查看域名NS
            $(".btn-view-ns").unbind("click").bind("click",function(){
            	var domain = $(this).data("domain");
            	$.ui.loading();
            	$.ajaxPassport({
            		url:"<?php echo U("/domain_manager/domain_checkns");?>",
            		success:function(res){
            			$.ui.close_loading();
            			if(res.error == 1){
            				$.tips(res.message,"error");
            			}else{
            				var html = '<div class="modal-header">\
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">当前NS</h4></div>\
        <div class="form-content"><div style="padding:5px;line-height:29px;color:blue;">'+res.ns.replace(/;/g,"<br/>")+"</div></div>";
            				$("#myModal").find(".modal-dialog").width(580);
        					$("#myModal").find(".modal-content").html(html);
        					$('#myModal').modal();
            			}
            			loadlist(initpage);
            		},
            		data:{domain:domain}
            	});
				return false;
            });

            //排序
			var orderby_arr = res.orderby.split("!"); 
			$(".thelistform").find("a.orderby").each(
			   function(){
			      var obj = this;
			      var or_item = $(obj).attr("data-item");
				  var or_v = "ASC";
				  $(obj).attr("data-desc",or_v);
				  $(obj).find("cite").attr("class","glyphicon glyphicon-chevron-up");	
			      if(or_item == orderby_arr[0]){
			     	or_v =  orderby_arr[1];
			      	$(obj).attr("data-desc",or_v);
			     	 if(or_v == "DESC"){
			     	 	$(obj).find("cite").attr("class","glyphicon glyphicon-chevron-down");
			     	 }     	 	
			      }
				  $(obj).unbind("click").bind("click",function(){
				  	loadlist(1,(or_item+"!"+(or_v == "ASC"?"DESC":"ASC")));
				  });
			});
	        //鼠标移上显示
			$(".thelistform").find("a.tiptitle").popover({html:true,trigger:"hover",});

			var domains = [];
                for(var i in res['list']){
                    domains.push(res['list'][i]['domain']);
                }
                $.exeJS(check_nsdomain_url+"&domains="+domains.join(","));
                $.exeJS(check_expirydomain_url+"&domains="+domains.join(","));
		});
	}
	var show_map_list = function (obj,type) {
		var domain_id = $(obj).data("domain_id");
		var domain = $(obj).data("domain");
		var domain_c = $(".count_domain_"+domain_id);
		var is_hide = domain_c.hasClass("hide");
		if (is_hide) {
			if (type == 1) {
				$(".find-order").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
				$(".find-order").removeClass("btn-default").addClass("btn-info");
				show_domain_order(1,domain_id);
			}else if (type == 2){
				$(".btn-exchange-log").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
				$(".btn-exchange-log").removeClass("btn-default").addClass("btn-info");
				exchenge_log(1,domain_id);
			}else if (type == 3){
				$(".find-record").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
				$(".find-record").removeClass("btn-default").addClass("btn-info");
				show_domain_records(1, domain_id);
			}else if (type == 4){
				$(".btn-setting-log").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
				$(".btn-setting-log").removeClass("btn-default").addClass("btn-info");
				edit_setting_log_func(1,domain_id);
			}else {
				$(".find-liuliang").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
				$(".find-liuliang").removeClass("btn-default").addClass("btn-info");
				domain_parse_count(domain_id,domain);
			}
			domain_c.removeClass("hide");
		}else{
			domain_c.addClass("hide");
		}
	}
	var count_js = function (obj) {
		$(obj).parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
		$(obj).removeClass("btn-default").addClass("btn-info");
	}
	$(function(){
		//加载搜索
		$.loadform(search_formdata,"",function(res){
			pageurl = res.pageurl;
			loadlist();
			return true;
		},function(){
			get_userlist(1,0,"",".the_searchform");
		},".the_searchform");
		$(".date-ymd").datetimepicker({
			language:  'zh-CN',
			autoclose: 1,
			startView: 2,
			minView: 2,
			maxView: 4,
			format:"yyyy-mm-dd",
			pickerPosition: "bottom-right"
		});
		//加载列表
		loadlist();
		<?php if($this->check_purview("/domain_manager/domain_checkall")){?>
		$(".batch-checkexpiry").unbind("click").bind("click",function(){
			$.tips("正在后台检测Expiry,需要一定的时间","success");
			$.exeJS("/domain_manager/domain_checkall?do=expiry");
		});
		$(".batch-checkns").unbind("click").bind("click",function(){
			$.tips("正在后台检测NS,需要一定的时间","success");
			$.exeJS("/domain_manager/domain_checkall?do=ns");
		});
		<?php }?>

		<?php if($this->check_purview("/domain_manager/domain_refreshall")){?>
		$(".batch-refresh").unbind("click").bind("click",function(){
			$.tips("正在后台刷新,需要一定的时间","success");
			var service_group = $(this).data("service_group");
			$.exeJS("/domain_manager/domain_refreshall?do=refresh&service_group="+service_group);
		});
		<?php }?>
	})
</script>
<!--搜索框 end-->
<script language="javascript">
 $(function(){
     laydate.skin('molv');
 })
</script>
<!-- Modal -->
<div style="z-index:1033;" class="modal fade" id="myModal" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>