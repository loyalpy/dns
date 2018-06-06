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
    <li><a href="<?php echo U("/user_manager/userlist?ut=0");?>" class="<?php if($ut == 0){?>cur<?php }?>">所有会员</a></li>
    <?php foreach($utypes as $key => $item){?>
	<li><a href="<?php echo U("/user_manager/userlist?ut=$key");?>" class="<?php if($ut == $key){?>cur<?php }?>"><?php echo isset($item['name'])?$item['name']:"";?></a></li>
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
  <div class="name">用户列表</div>
  <div class="navbtn0"></div>
  <div class="navbtn">
	  <!--<a href="<?php echo U("/import_manager/Imprt199Cn");?>" target="_blank" class="btn btn-warning btn-sm">-->
		  <!--199cn导入系统</a>-->
	  <button type="button" class="btn btn-sm btn-warning batch-send-email">批量发送邮件</button>&nbsp;&nbsp;&nbsp;
	  <!--button-->
	  <?php if($this->check_purview("/user_manager/userlist")){?>
  		<button type="button" class="btn btn-default btn-sm refresh-btn" data-loading-text="处理中……">&nbsp;<cite class="glyphicon glyphicon-refresh"></cite>&nbsp;</button>
	  <?php }?>
	  <!--end button-->

  <!--button-->
	  <?php if($this->check_purview("/user_manager/userlist_edit")){?>
  <a href="javascript:void(0)" data-utype="1" data-url="<?php echo U("/user_manager/userlist_edit?uid=0");?>" class="btn btn-primary btn-sm addbtn">
  <cite class="glyphicon glyphicon-plus"></cite> 
  新增用户</a>
	  <?php }?>
  <!--end button-->

	  <!--<a href="<?php echo U("user_manager/userlist_account_set");?>" target="_blank" class="btn btn-success btn-sm">域名解析注册统计</a>-->

  </div>
  <div class="cl"></div>
</div>

<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/user_manager/userlist?do=get_url");?>">
<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
<div class="list-box">
<table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
	<col width="30px"/>
	<col />
	<col width="230px" />
	<col width="90px" />
	<col width="90px" />
	<col width="100px" />
	<col width="100px" />
	<col width="130px" />
	<col width="37px" />	
	<col width="100px" />
	<thead>
	<tr>
		<th><input type="checkbox" data-name="uids[]" class="checkall"/></th>
	<th>用户名[ID]
&nbsp;&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="a.uid" data-desc=""><cite></cite></a>
	</th>
	<th>认证&nbsp;&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="a.emailrz" data-desc=""><cite></cite></a></th>
	<th>解析域名 <a href="javascript:void(0)" class="orderby" data-item="b.domains" data-desc=""><cite></cite></a></th>
	<th>注册域名 <a href="javascript:void(0)" class="orderby" data-item="b.register_domains" data-desc=""><cite></cite></a></th>
		<th>余额&nbsp;&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="b.balance" data-desc=""><cite></cite></a>
	</th>
		<th>积分<a href="javascript:void(0)" class="orderby" data-item="b.point" data-desc=""><cite></cite></a>&nbsp;短信<a href="javascript:void(0)" class="orderby" data-item="b.sms" data-desc=""><cite></cite></a></th>
		<th>注册&nbsp;&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="a.regdateline" data-desc=""><cite></cite></a>
登录&nbsp;&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="a.logdateline" data-desc=""><cite></cite></a>
	</th>
	<th>状态</th>
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
<script language="javascript">
var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
var roleList = <?php echo JSON::encode($role_res);?>;
var roleList_A = <?php echo JSON::encode($rolelist);?>;
var ulevel = <?php echo JSON::encode($ulevel_res);?>;
var ulevel_A = <?php echo JSON::encode($ulevel);?>;
var utypes = <?php echo JSON::encode($utypes);?>;
</script>
<?php echo $this->fetch('tpl/form')?>
<?php echo $this->fetch('user_manager/utpl')?>
<?php echo $this->fetch('user_manager/utpl_list')?>
<?php echo $this->fetch('user_manager/email_tpl')?>
<script type="text/template" id="tpl_list_row">
<#macro row data>
    <tr>
		<td><input type="checkbox" name="uids[]" value="${data.uid}" data-email="${data.email}"/></td>
    <td class="find-count-uid-t <#if (data.urole == 1)>font-red<#elseif (data.urole > 1)>font-blue</#if>" data-toggle="collapse"
		href="#collapseExample_${data.uid}" data-uid="${data.uid}" data-type="7" style="cursor: pointer" title="<#if (data.urole>0)>${roleList_A[data.urole]['name']}</#if>">
    <#if (data.bd.status == 2)>
    <a class="font-green tiptitle" data-content="<p><img width='150' src='${data.bd.wx_avatar}' /></p><p class='font-gray'>${data.bd.wx_nickname} ${data.bd.wx_city}</p>"><img width="15" height="15" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/wx.png";?>"  /></a>
    <#else>
    <cite title="当前离线" class="glyphicon glyphicon-user font-gray"></cite>
    </#if>

    <span class="keybox">${data.name}</span><span class="font-gray">[${data.uid}]</span>
		&nbsp;<cite class="glyphicon glyphicon-envelope font-gray diy-send-email call_link" data-email="${data.email}" title="自定义邮件发送" style="cursor: pointer"></cite>
    <#if (data.urole>0)>
    <a style="display:none;" class="glyphicon glyphicon-question-sign font-red"></a>
    </#if>

    <br/>
    <font class="font-org">[<#if (data.utype > 0)>${utypes[data.utype]['name']}</#if>]</font>
		<font class="<#if (data.ulevel>0)>font-green<#else>font-gray</#if>"><#if (data.utype > 0)>${ulevel_A[data.utype][data.ulevel]['alias']}</#if></font>
		<span class="font-gray2">${data.realname}</span>
    </td>
    <td class="find-count-uid-t" data-toggle="collapse" href="#collapseExample_${data.uid}" data-uid="${data.uid}" data-type="7" style="cursor: pointer">
    <#if (!$.is_empty(data.email))>
    ${data.email}
    <#if (data.emailrz==1)><cite title="已认证" class="glyphicon glyphicon-ok-sign font-green"></cite><#else><cite class="glyphicon glyphicon-remove-sign font-gray" title="未认证"></cite></#if>
		<br/>
    </#if>
    <#if (!$.is_empty(data.mobile))>
    ${data.mobile}
    <#if (data.mobilerz==1)><cite title="已认证" class="glyphicon glyphicon-ok-sign font-green"></cite><#else><cite class="glyphicon glyphicon-remove-sign font-gray" title="未认证"></cite></#if>
    <br/>
    </#if>
    </td>
    <td class="find-count-uid-t" data-toggle="collapse" href="#collapseExample_${data.uid}" data-uid="${data.uid}" data-type="2" style="cursor: pointer"><span class=" font-green f14">${data.account.domains}</span></td>
    <td  class="find-count-uid-t" data-toggle="collapse" href="#collapseExample_${data.uid}" data-uid="${data.uid}"  data-type="7" style="cursor: pointer"><span  class="font-green f14">${data.account.register_domains}</span></td>
    <td class="find-count-uid-t" data-toggle="collapse" href="#collapseExample_${data.uid}" data-uid="${data.uid}"  style="cursor: pointer"  data-type="1"><font class="f14 font-red">${data.account.balance}</font></td>
	<td class="f12"  style="cursor: pointer" >
		<span class="find-count-uid-t" data-toggle="collapse" href="#collapseExample_${data.uid}" data-uid="${data.uid}"   data-type="6">${data.account.point}</span>/
		<span class="find-count-uid-t" data-toggle="collapse" href="#collapseExample_${data.uid}" data-uid="${data.uid}"   data-type="5"><#if ($.is_empty(data.account.sms))> 0 <#else> ${data.account.sms}</#if></span>
	</td>
	<td class="find-count-uid-t" data-toggle="collapse" href="#collapseExample_${data.uid}" data-uid="${data.uid}" data-type="8" style="cursor: pointer">${data.regdateline}<br/>${data.logdateline}</td>
    <td class="font-gray find-count-uid-t"  data-toggle="collapse" href="#collapseExample_${data.uid}" data-type="7" data-uid="${data.uid}" style="cursor: pointer"><#if (data.inlock==1)><cite title="已锁" class="glyphicon glyphicon-exclamation-sign font-red"></cite><#else><cite class="glyphicon glyphicon-ok-sign font-green" title="正常"></cite></#if></td>
    <td class="font-gray">
    <p class="table-item-op">
	<?php if($this->check_purview('/user_manager/userlist_edit')){?>
	<a href="javascript:void(0);" class="editbtn" data-utype="${data.utype}" data-url="<?php echo U("/user_manager/userlist_edit?uid=");?>${data.uid}"><span class="glyphicon glyphicon-edit"></span></a>
	<?php }?>&nbsp;
	<?php if($this->check_purview('/user_manager/userlist_refresh')){?>
	<a href="javascript:void(0);" class="t-ajax-button" data-url="<?php echo U("/user_manager/userlist_refresh?uid=");?>${data.uid}"><span class="glyphicon glyphicon-refresh"></span></a>
	<?php }?>
	<?php if($this->check_purview('/user_manager/userlist_del')){?>
	<a href="javascript:void(0);" class="t-ajax-button" confirm="1" data-url="<?php echo U("/user_manager/userlist_del?uid=");?>${data.uid}"><span class="glyphicon glyphicon-remove"></span></a>
	<?php }?>
	<?php if($this->check_purview('/user_manager/userlist_quicklogin')){?>
	<a href="<?php echo U("/user_manager/userlist_quicklogin?uid=");?>${data.uid}" target="_blank" title="快速登录"><span class="glyphicon glyphicon-user"></span></a>
	<?php }?><br/>
	<?php if($this->check_purview('/user_manager/userlist_recharge')){?>
	<button data-id="${data.uid}" data-name="${data.name}" type="button" class="btn btn-danger btn-xs table-item-op-recharge">账户</button>
	<?php }?>
	<?php if($this->check_purview('/user_manager/userlist_setting')){?>
    <button data-id="${data.uid}" data-name="${data.name}" type="button" class="btn btn-danger btn-xs btn-setting" data-url="<?php echo U("/user_manager/userlist_setting?uid=");?>${data.uid}">设置</button>
	<?php }?>
	</p>
    </td>
	</tr>
	<tr class="count_uid_${data.uid} hide">
		<td colspan="10">
			<div class="collapse" id="collapseExample_${data.uid}">
				<div class="" style="padding: 10px 10px 0px 30px;">
					<button type="button" class="btn btn-default btn-sm find-register-domain" data-type="7" data-uid="${data.uid}">注册域名</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm find-domain" data-type="2" data-uid="${data.uid}">解析域名</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm find-order" data-type="10" data-uid="${data.uid}">充值订单</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm find-register-order"  data-type="4" data-uid="${data.uid}">注册订单</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm find-parse-order"  data-type="3" data-uid="${data.uid}" style="margin-right: 20px">解析订单</button>&nbsp;


					<button type="button" class="btn btn-default btn-sm find-rechart"  data-type="1" data-uid="${data.uid}">余额</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm find-rechart-sms"  data-type="5" data-uid="${data.uid}">短信</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm find-rechart-ji"  data-type="6" data-uid="${data.uid}" style="margin-right: 20px">积分</button>&nbsp;

					<button type="button" class="btn btn-default btn-sm find-login-log"  data-type="8" data-uid="${data.uid}">登陆日志</button>&nbsp;
					<button type="button" class="btn btn-default btn-sm find-cz-log"  data-type="9" data-uid="${data.uid}" style="margin-right: 20px">操作日志</button>&nbsp;

					<input type="text" class="form-control input-sm auto-search" data-uid="${data.uid}" placeholder="请输入关键词" style="width: 220px;display: inline-block"/>

					<button type="button" style="float: right" class="btn btn-default btn-sm find-count-uid-t" data-uid="${data.uid}" data-domain="${data.domain}" role="button" data-toggle="collapse" href="#collapseExample_${data.uid}" aria-expanded="false" aria-controls="collapseExample">关闭</button>
				</div>
				<div class="" style="padding: 15px 30px 0px 30px;width:1080px;">
					<div class="countimage-box" id="user_reg_month_${data.uid}" style="width: 1080px; height:100%; margin: 0 auto"><img alt="正在加载中" class="loading" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/loading2.gif";?>" /></div>
					<div class="dis30"></div>
				</div>
			</div>
		</td>
	</tr>
	<tr class="hide"><td colspan="100"></td></tr>
</#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<script language="javascript">
	var alert_type = 0;
var search_formdata = {
	startdate:{type:"date",label:"时间",name:"startdate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:""},
	enddate:{type:"date",label:"-",name:"enddate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:"date-dis"},
    keyword:{type:"text",label:"UID/关键词",name:"keyword",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
    company:{type:"hidden",name:"company",value:"<?php echo isset($condi['company'])?$condi['company']:"";?>"},
	btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
};
var initpage = 1;
var loadlist = function(page,orderby){
	$.ajaxLoadlist(page,pageurl+(typeof orderby=="undefined"?"":("&orderby="+orderby)),function(res){
		initpage = page;
		//关键词
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
		$(".editbtn").click(function(){
			edit_userinfo(this);
		 });
		$(".t-ajax-button").each(function(i){
			$.t_ajax_button(this);
		});
		$(".btn-setting").click(function(){
			edit_user_setting_func(this);
		});
		$(".table-item-op-recharge").click(function(){
			edit_user_balance_point(this);
		});
		//自定义邮箱发送
		$(".diy-send-email").click(function () {
			send_email_usual(this);
		});
		//批量自定义邮件发送
		var ids= new Array();
		$(".batch-send-email").unbind("click").bind("click",function(){
			var ids_tmp  = $.fetch_ids("uids[]","email");
			ids = ids_tmp.split(",");
			if (ids == "") {
				$.ui.error('请选择要批量发送的对象！')
				return;
			}
			var reg = new RegExp(",","g");//g,表示全部替换。
			send_email_usual(this,ids_tmp.replace(reg,";"));
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

		//展示列表开始*************************************************************************
		//注册域名历史
		$(".find-register-domain").unbind("click").bind("click",function () {
			count_js(this);
			var uid = $(this).data("uid");
			register_domain_fun(1,uid);
		})
		$(".find-domain").click(function () {
			count_js(this);
			var uid = $(this).data("uid");
			domain_parse_count(1,uid);
		});
		$(".find-rechart").click(function () {
			count_js(this);
			var uid = $(this).data("uid");
			show_rechart_order(1,uid);
		});
		$(".find-order").click(function () {
			count_js(this);
			var uid = $(this).data("uid");
			show_order(1,uid);
		});
		$(".find-rechart-sms").click(function () {
			count_js(this);
			var uid = $(this).data("uid");
			show_rechart_sms_order(1,uid);
		});
		$(".find-rechart-ji").click(function () {
			count_js(this);
			var uid = $(this).data("uid");
			show_rechart_ji_order(1,uid);
		});
		$(".find-parse-order").click(function () {
			count_js(this);
			var uid = $(this).data("uid");
			show_parse_order(1,uid);
		});
		$(".find-register-order").click(function () {
			count_js(this);
			var uid = $(this).data("uid");
			show_register_order(1,uid);
		});
		$(".find-login-log").click(function () {
			count_js(this);
			var uid = $(this).data("uid");
			show_login_log(1,uid);
		});
		$(".find-cz-log").click(function () {
			count_js(this);
			var uid = $(this).data("uid");
			show_cz_log(1,uid);
		});
		//查看统计图1
		$(".find-count-uid-t").click(function () {
			var type = $(this).data("type");
			if (typeof type != "undefined") {
				alert_type = type;
			}
			$(".auto-search").attr("data-type",type);
			show_map_list(this,alert_type);
			alert_type = 0;
			return true;
		});
		$(".call_link").click(function () {
			return false;
		});
		//自动搜索功能
		$("input.auto-search").keyup(function(){
			var type = $(this).data("type");
			var uid = $(this).data("uid");
			var keyword = $(this).val();
			switch (type)
			{
				case 1:
					show_rechart_order(1,uid,keyword);
					break;
				case 2:
					domain_parse_count(1,uid,keyword);
					break;
				case 3:
					show_parse_order(1, uid,keyword);
					break;
				case 4:
					show_register_order(1,uid,keyword);
					break;
				case 5:
					show_rechart_sms_order(1,uid,keyword);
					break;
				case 6:
					show_rechart_ji_order(1,uid,keyword);
					break;
				case 7:
					register_domain_fun(1,uid,keyword);
					break;
				case 8:
					show_login_log(1,uid,keyword);
					break;
				case 9:
					show_cz_log(1,uid,keyword);
					break;
				case 10:
					show_order(1,uid,keyword);
					break;
			}
		});
	});
}
var show_map_list = function (obj,type) {
	var uid = $(obj).data("uid");
	var uid_c = $(".count_uid_"+uid);
	var is_hide = uid_c.hasClass("hide");
	if (is_hide) {
		if (type == 1) {//余额
			$(".find-rechart").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
			$(".find-rechart").removeClass("btn-default").addClass("btn-info");
			show_rechart_order(1,uid);
		}else if (type == 2){//解析域名
			$(".find-domain").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
			$(".find-domain").removeClass("btn-default").addClass("btn-info");
			domain_parse_count(1,uid);
		}else if (type == 3){//解析订单
			$(".find-parse-order").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
			$(".find-parse-order").removeClass("btn-default").addClass("btn-info");
			show_parse_order(1, uid);
		}else if (type == 4){//注册订单
			$(".find-register-order").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
			$(".find-register-order").removeClass("btn-default").addClass("btn-info");
			show_register_order(1,uid);
		}else if (type == 5){//短信
			$(".find-rechart-sms").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
			$(".find-rechart-sms").removeClass("btn-default").addClass("btn-info");
			show_rechart_sms_order(1,uid);
		}else if (type == 6){//积分
			$(".find-rechart-ji").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
			$(".find-rechart-ji").removeClass("btn-default").addClass("btn-info");
			show_rechart_ji_order(1,uid);
		}else if (type == 7) {//注册域名
			$(".find-register-domain").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
			$(".find-register-domain").removeClass("btn-default").addClass("btn-info");
			register_domain_fun(1,uid);
		}else if (type == 8) {//登陆日志
			$(".find-login-log").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
			$(".find-login-log").removeClass("btn-default").addClass("btn-info");
			show_login_log(1,uid);
		}else if (type == 9) {//操作日志
			$(".find-cz-log").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
			$(".find-cz-log").removeClass("btn-default").addClass("btn-info");
			show_cz_log(1,uid);
		}else if (type == 10) {//充值订单
			$(".find-order").parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
			$(".find-order").removeClass("btn-default").addClass("btn-info");
			show_order(1,uid);
		}
		uid_c.removeClass("hide");
	}else{
		uid_c.addClass("hide");
	}
}
var count_js = function (obj) {
	var type = $(obj).data("type");
	$(".auto-search").attr("data-type",type);
	$(".auto-search").val("");
	$(obj).parent().find(".btn-info").removeClass("btn-info").addClass("btn-default");
	$(obj).removeClass("btn-default").addClass("btn-info");
}
$(function(){
	//加载搜索
	 $.loadform(search_formdata,"",function(res){
	 	pageurl = res.pageurl;
	 	loadlist(1);
		return true;
	 },null,".the_searchform");
	 //加载列表
	 loadlist(1);
     //增加按钮
	 $(".addbtn").click(function(){
		edit_userinfo(this);
	 });
	 //刷新按钮
	 $(".refresh-btn").click(function(){
	 	 loadlist(1);
	 });	 
	 $(".date-ymd").datetimepicker({
		language:  'zh-CN',
		autoclose: 1,
		startView: 2,
		minView: 2,
		maxView: 4,
		format:"yyyy-mm-dd",
		pickerPosition: "bottom-right"   
	});
})
</script>
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