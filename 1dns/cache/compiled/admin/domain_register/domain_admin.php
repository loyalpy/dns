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
		<li><a href="<?php echo U("/domain_register/domain");?>" class="cur">域名列表</a></li>
		<li><a href="<?php echo U("/domain_register/domain_log");?>">域名操作日志</a></li>
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
	<div class="name">注册域名</div>
	<div class="navbtn0" style="width: 620px">
		<?php $time_s = array("1"=>"一周內","2"=>"一月內","3"=>"三月內","4"=>"续费期","5"=>"赎回期","6"=>"已删除")?>
		<a class="btn <?php if($expire == 0){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm"  href="<?php echo U("/domain_register/domain?expire=0");?>"><cite class="glyphicon glyphicon-th"></cite> 所有</a>&nbsp;<span class="badge badge-s"><?php echo isset($badge[0])?$badge[0]:"";?></span>
		<?php foreach($time_s as $key => $item){?>
		<a class="btn <?php if($expire == $key){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="<?php echo isset($item)?$item:"";?>" href="<?php echo U("/domain_register/domain?expire=$key");?>"><cite class="glyphicon glyphicon-th"></cite> <?php echo isset($item)?$item:"";?></a>&nbsp;<span class="badge badge-s"><?php echo isset($badge[$key])?$badge[$key]:"";?></span>
		<?php }?>
	</div>
	<?php if($this->check_purview("/domain_register/domain_batch")){?>
	<a type="button" target="_blank" href="javascript:void (0);" data-url="<?php echo U("/import_manager/ImportRegister");?>" class="btn btn-sm btn-danger batch-tb-data" style="float: right">批量同步线上数据</a>
	<a type="button" target="_blank" href="javascript:void (0);" data-url="<?php echo U("/import_register_domain/BatchEditNs");?>" class="btn btn-sm btn-warning batch-tb-data" style="float: right;margin-right: 8px">批量更改NS</a>
	<a type="button"  href="<?php echo U("/import_register_domain/register_domain_export");?>" class="btn btn-sm btn-primary" style="float: right;margin-right:8px"><i class="glyphicon glyphicon-download-alt"></i> 导出域名</a>
	<?php }?>
	<div class="cl"></div>
</div>
<!--search box-->
<form enctype="multipart/form-data" class="the_searchform form" method="POST" action="<?php echo U("/domain_register/domain?do=get_url");?>">
	<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
	<div class="list-box">
		<table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
			<col />
			<col width="170px"/>
			<col width="190px" />
			<col width="140px">
			<col width="110px" />
			<col width="110px" />
			<col width="110px" />
			<col width="155px"/>
			<thead>
			<tr>
				<th>注册域名&nbsp;<a href="javascript:void(0)" class="orderby" data-item="domain" data-desc=""><cite></cite></a></th>
				<th>用户名/邮箱/手机&nbsp;<a href="javascript:void(0)" class="orderby" data-item="uid" data-desc=""><cite></cite></a></th>
				<th>域名所有者/电话/邮箱</th>
				<th>域名NS&nbsp;<a href="javascript:void(0)" class="orderby" data-item="ns" data-desc=""><cite></cite></a></th>
				<th>实名状态</th>
				<th>注册&nbsp;<a href="javascript:void(0)" class="orderby" data-item="reg_time" data-desc=""><cite></cite></a>&nbsp;到期&nbsp;<a href="javascript:void(0)" class="orderby" data-item="exp_time" data-desc=""><cite></cite></a></th>
				<th>续费时间&nbsp;<a href="javascript:void(0)" class="orderby" data-item="renew_dateline" data-desc=""><cite></cite></a></th>
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
<script type="text/template" id="tpl_list_row">
	<#macro row data>
		<tr>
			<td>
				<span class="f16 font-blue">${data.domain}</span>
				<#if (data.renew_type == 1)>
						<span class="font-green" title="新买">[新]</span>
					<#else>
						<span class="font-org" title="续费">[续]</span>
				</#if>&nbsp;
				<!--<a href="http://${data.domain}" target="_blank" class="font-gray" title="访问网站"><cite class="glyphicon glyphicon-send"></cite></a>-->
				<br/>
				<span class="font-gray"> [<#if (data.type == 0)>国际域名<#else>国内域名</#if>]</span>
			</td>
			<td>
				<a href="<?php echo U("domain_register/domain?uid=");?>${data.uinfo.uid}"><span class="font-green">${data.uinfo.uname}</span></a><br/>
				<span class="font-gray">${data.uinfo.email}</span><br/>
				<span class="font-gray2">${data.uinfo.mobile}</span>
			</td>
			<td>
				${data.info_data.aller_name_cn}&nbsp;<cite class="glyphicon glyphicon-envelope font-gray diy-send-email" data-email="${data.info_data.email}" title="自定义邮件发送" style="cursor: pointer"></cite><br/>
				${data.info_data.email}<br/>
				<#if (!$.is_empty(data.info_data.mobile))>
				${data.info_data.mobile}<br/>
				</#if>
			</td>
			<td class="font-gray">${data.ns.replace(";","<br/>")}</td>
			<td class="domain_${data.domain.split('.')[0]}">
				<#if (data.real_name.status == 0)>
						<span class="font-green" data-toggle="tooltip" data-placement="right" title="无需实名认证"><cite class="glyphicon glyphicon-ok"></cite></span>
					<#else>
						<#if (data.real_name.status == 2  || data.real_name.status == 3 || data.real_name.status == 4)>
							<a target="_blank" href="<?php echo U("static@");?>${data.real_name.cart_url}">证件已传</a>
							<#if (data.real_name.status == 3 || data.real_name.status == 4)>
								<span class="font-green">[已审]</span>
								<#else><span class="font-gray">[待审]</span>
							</#if>
							<#else>
								<font class="">未提交资料</font><br/>
						</#if>

						<#if (data.real_name.status == 2  || data.real_name.status == 3 || data.real_name.status == 4)>
							<button type="button"  data-cart="${data.real_name.cart}"  data-domain="${data.domain}" data-url="<?php echo U("static@");?>${data.real_name.cart_url}" class="btn btn-xs <#if (data.real_name.status == 4)>btn-danger<#else>btn-success</#if> btn-rzsh">
								<#if (data.real_name.status == 2)>
									审核中
									<#elseif (data.real_name.status == 3)>
										审核通过
										<#elseif (data.real_name.status == 4)>
											审核失败
											<#else>
												-
								</#if>
							</button>
							<#else>
								-
						</#if>
				</#if>
			</td>
			<td>
				${data.reg_time}<br/>
				<span title="<#if (data.exp_type == 1)>服务期<#elseif (data.exp_type == 2)>续费期<#elseif (data.exp_type == 3)>赎回期<#else>已删除，域名已失效</#if>" class="<#if (data.exp_type == 1)>font-green<#elseif (data.exp_type == 2)>font-org<#elseif (data.exp_type == 3)>font-red<#else>font-red</#if>">${data.exp_time}</span>
			</td>
			<td class="">${data.renew_dateline}</td>
			<td style="line-height: 28px">
				<button type="button" class="btn btn-default btn-xs syn-data" title="更新线上数据" data-domain="${data.domain}">同步</button>&nbsp;
				<button type="button" class="btn btn-default btn-xs domain-renew" data-domain="${data.domain}">续费</button>&nbsp;
				<a href="javascript:void (0);" class="btn btn-default btn-xs link-alert-x"  data-type="1" data-url="<?php echo U("/user_manager/userlist_quicklogin?uid=");?>${data.uid}"  target="_blank" title="快速登录">快登</a>&nbsp;<br/>

				<div class="btn-group">
					<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						更多 <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" class="ns-change"  data-domain="${data.domain}" data-ns="${data.ns}" >更改NS</a></li>
						<li class="divider"></li>
						<li><a href="javascript:void(0);" class="find-order" data-domain="${data.domain}">续费历史</a></li>
						<li><a href="javascript:void(0);" class="doamin-log" data-domain="${data.domain}">操作日志</a></li>
						<li class="divider"></li>
						<li><a href="javascript:void(0);" class="doamin-diy-admin" data-domain="${data.domain}">域名自助管理平台</a></li>
					</ul>
				</div>&nbsp;
				<button type="button" class="btn btn-primary btn-xs find-info" data-id="${data.info_data.id}">详细信息</button>
			</td>
		</tr>
	</#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<script language="javascript">
	var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
	var formdata = {
		email: {type: "uid", label: "用户", name: "email", value: "<?php echo isset($condi['uid'])?$condi['uid']:"";?>", uname:"<?php echo isset($condi['uname'])?$condi['uname']:"";?>",disabled: 0, data_sr:[], css: "", require: "", desc: "", item_css: ""},

		startdate:{type:"date",label:"注册时间",name:"startdate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:""},
		enddate:{type:"date",label:"-",name:"enddate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:"date-dis"},
		keyword:{type:"text",label:"关键词",name:"keyword",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
		btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
	};
	var loadlist = function (page, orderby) {
		$.ajaxLoadlist(page, pageurl + (typeof orderby == "undefined" ? "" : ("&orderby=" + orderby)), function (res) {
			$('[data-toggle="tooltip"]').tooltip();
			//查看信息
			$(".find-info").click(function () {
				edit_setting_check_func(this);
			});

			$(".find-order").unbind("click").bind("click", function () {
				var domain = $(this).data("domain");
				show_domain_order(1, domain);
			});
			$(".doamin-log").unbind("click").bind("click", function () {
				var domain = $(this).data("domain");
				show_domain_log(1, domain);
			})
			$(".syn-data").unbind("click").bind("click", function () {
				var domain = $(this).data("domain");
				$.ui.loading();
				$.ajaxPassport({
					url:"<?php echo U("import_manager/ChangeOnlineDomain");?>",
					success:function (res) {
						$.ui.close_loading();
						if (res.error == 0) {
							$.ui.success(res.message);
						}else{
							$.ui.error(res.message);
						}
					},
					data:{domain:domain}
				});
			});


			//实名状态list后查询
			var domains = [];
			for(var i in res['list']){
				domains.push(res['list'][i]['domain']);
			}
			$.ajaxPassport({
				url:"<?php echo U("domain_register/domain_rz_status");?>",
				success:function (res) {
					if (res.error == 0) {
						for(var i in res.message){
							if (res.message[i] == 3) {
								$(".domain_"+i.split('.')[0]).find("span").attr("class","font-green");
								$(".domain_"+i.split('.')[0]).find("span").html("[已审]");
								$(".domain_"+i.split('.')[0]).find("button").html("审核通过");
							}else if (res.message[i] == 4){
								$(".domain_"+i.split('.')[0]).find("button").attr("class","btn btn-xs btn-danger");
								$(".domain_"+i.split('.')[0]).find("span").html("[已审]");
								$(".domain_"+i.split('.')[0]).find("button").html("审核失败");
							}
						}
					}
				},
				data:{domains:domains.join(",")}
			});


			//ns更改
			$(".ns-change").click(function () {
				domain_ns_edit(this);
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
			//域名续费
			$(".domain-renew").click(function () {
				domain_renew(this);
			});
			//域名自助管理平台
			$(".doamin-diy-admin").click(function () {
				domain_admin(this);
			});
			//自定义邮箱发送
			$(".diy-send-email").click(function () {
				send_email_usual(this);
				return false;
			});
			//实名审核状态
			$(".btn-rzsh").click(function () {
				domain_rz(this);
			});
			//批量同步线上数据
			$(".batch-tb-data").click(function () {
				var url = $(this).data("url");
				$.ui.confirm(function(){
					setTimeout(function () {
						window.open(url);
					},200);
					$(".layui-layer-btn1").click();
				},"批量操作需要执行一段时间，请耐心等待！") ;
			});
			//排序
			var orderby_arr = res.orderby.split("!");
			$(".thelistform").find("a.orderby").each(function () {
						var obj = this;
						var or_item = $(obj).attr("data-item");
						var or_v = "ASC";
						$(obj).attr("data-desc", or_v);
						$(obj).find("cite").attr("class", "glyphicon glyphicon-chevron-up");
						if (or_item == orderby_arr[0]) {
							or_v = orderby_arr[1];
							$(obj).attr("data-desc", or_v);
							if (or_v == "DESC") {
								$(obj).find("cite").attr("class", "glyphicon glyphicon-chevron-down");
							}
						}
						$(obj).unbind("click").bind("click", function () {
							loadlist(1, (or_item + "!" + (or_v == "ASC" ? "DESC" : "ASC")));
						});
			});
			//批量同步新网线上域名注册数据
			var uid = "<?php echo isset($uid)?$uid:"";?>";
			var ajaxSubmit = "<?php echo isset($ajaxSubmit)?$ajaxSubmit:"";?>";
			if (uid == 1736 && ajaxSubmit == 0) {
				$.exeJS("<?php echo tUtil::js('admin@/import_manager/ImportRegister');?>");
			}
		});
	};
	$(function(){
		//加载搜索
		$.loadform(formdata,"",function(res){
			pageurl = res.pageurl;
			loadlist(1);
			return true;
		},function () {
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
		loadlist(1);
		$(".refresh-btn").click(function(){
			loadlist(1);
		});

	})
</script>
<!--查看详细信息-->
<script type="text/template" id="tpl_info">
	<#macro row data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">注册详细信息</h4>
		</div>
		<div class="modal-body">
			<div class="dis15"></div>
			<table class="table domain-template-table">
				<col width="100px" />
				<col width="180px"/>
				<tr>
					<td class="l">域名所有者中文名称：</td>
					<td>${data.aller_name_cn}</td>
				</tr>
				<tr>
					<td class="l">域名所有者英文名称：</td>
					<td>${data.aller_name}</td>
				</tr>
				<tr>
					<td class="l">联系人中文名称：</td>
					<td>${data.name_cn}</td>
				</tr>
				<tr>
					<td  class="l">联系人邮箱：</td>
					<td>${data.email}</td>
				</tr>
				<tr>
					<td class="l">联系人地区：</td>
					<td>${data.area}</td>
				</tr>
				<tr>
					<td  class="l">联系人中文通讯地址：</td>
					<td>${data.addr_cn}</td>
				</tr>
				<tr>
					<td  class="l">联系人英文通讯地址：</td>
					<td>${data.addr}</td>
				</tr>
				<tr>
					<td  class="l">注册邮编：</td>
					<td>${data.ub}</td>
				</tr>
				<tr>
					<td  class="l">注册手机号：</td>
					<td>${data.mobile}</td>
				</tr>
				<tr>
					<td  class="l">管理员手机号：</td>
					<td>${data.m_mobile}</td>
				</tr>
				<tr>
					<td  class="l">管理员邮箱：</td>
					<td>${data.m_email}</td>
				</tr>
				<tr>
					<td class="l">传真：</td>
					<td>${data.cz}</td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
		</div>
	</#macro>
</script>
<script language="javascript">
	var edit_setting_check_func = function(obj){
		var id = $(obj).data("id");
		$.ajaxPassport({
			url:"<?php echo U("/domain_register/domain_info");?>",
			type:"POST",
			success:function (res) {
				var edit_c = $("#tpl_info").html();
				edit_c = "" + easyTemplate(edit_c,res);
				$("#myModal").find(".modal-dialog").width(600);
				$("#myModal").find(".modal-content").html(edit_c);
				$('#myModal').modal();
			},
			data:{id:id}
		});
	}
</script>

<!-- 域名套餐购买续费记录 start-->
<script type="text/template" id="tpl_domain_order">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed" cellpadding="0" cellspacing="0">
				<col width="80px" />
				<col />
				<col width="80px" />
				<col width="80px" />
				<col width="110px" />
				<col width="120px" />
				<col width="120px" />
				<col width="80px" />
				<col width="150px" />
				<thead>
				<tr class="active">
					<th>ID</th>
					<th>购买域名</th>
					<th>购买类型</th>
					<th>购买数量</th>
					<th>购买价格</th>
					<th>共优惠</th>
					<th>购买总金额</th>
					<th>支付状态</th>
					<th>支付时间</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as order>
						<tr>
							<td class="font-gray">${order.item_id}</td>
							<td><span class="font-blue">${order.domain}</span></td>
							<td class="font-gray"><#if (order.type == 1)>新买<#else>续费</#if></td>
							<td class="font-gray2">${order.num}年</td>
				<td class="font-gray">${order.price}元</td>
				<td class="font-gray">${order.youhui}元</td>
				<td class="font-org">${order.amount}元</td>
				<td><#if (order.status == 0)><span  class="font-gray">未完成</span><#else><span class="font-green">已完成</span></#if></td>
				<td class="font-gray">${$.time_to_string(order.status_dateline,"Y-m-d H:i:s")}</td>
				</tr>
				</#list>
				<#else>
					<tr><td colspan="9"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无域名注册记录！</span></tr>
					</#if>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var show_domain_order = function(page,domain){
		$.ajaxPassport({
			url:"<?php echo U("/domain_register/domain_order?domain=");?>"+domain,
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "域名注册记录("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_domain_order").html(),res);
					$("#myModal").find(".modal-dialog").width("1010");
					$("#myModal").find(".modal-content").html(edit_c);
					$('#myModal').modal();
				}
			},
			data:{page:page}
		});
	}
</script>
<!-- 域名套餐购买续费记录 end-->

<!-- 更改ns start-->
<script type="text/template" id="tpl_ns">
	<#macro row data>
		<div  class="form form-2 t-ajax-form" role="form">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="">${data.title}</h4>
			</div>
			<div class="modal-body">
				<div class="form-item form-item-text">
					<label>当前NS：</label>
					<div class="item-v" style="position: relative;top:3px;">${data.ns}</div>
					<div class="cl"></div>
				</div>

				<div class="form-item form-item-text">
					<label>NS1：</label>
					<div class="item-v">
						<input type="text" class=" " name="ns1" value="<#if (data['ns_g'][0])>${data.ns_g[0]}</#if>" size="29">
						<span style="font-size: 12px;color: darkgray"> 请至少填写两个NS</span>
					</div>
					<div class="cl"></div>
				</div>

				<div class="form-item form-item-text">
					<label>NS2：</label>
					<div class="item-v">
						<input type="text" class=" " name="ns2" value="<#if (data['ns_g'][1])>${data.ns_g[1]}</#if>" size="29"></div>
					<div class="cl"></div>
				</div>

				<div class="cl"></div>
			</div>
			<div class="modal-footer">
				<input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" />
				<button type="submit" class="btn btn-primary change-sub" data-loading-text="处理中……">提交</button>
				<button type="button" class="btn btn-default close-sub" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var domain_ns_edit = function(obj){
		var name = $(obj).attr("data-domain");
		var ns = $(obj).attr("data-ns");
		var tpl_s = $("#tpl_ns").html();
		var tpl_v = {ns:ns,title:"域名："+name};

		$.ajaxPassport({
			url:"<?php echo U("/domain_register/domain_ns_edit");?>",
			success:function (res) {
				if (res.error == 0) {
					tpl_v.ns_g = res.message;
					$("#myModal").find(".modal-dialog").width(560);
					$("#myModal").find(".modal-content").html("" + easyTemplate(tpl_s,tpl_v));
					$('#myModal').modal();

					$(".change-sub").click(function () {
						var ns1 = $("input[name='ns1']").val();
						var ns2 = $("input[name='ns2']").val();
						$.ui.loading();
						$.ajaxPassport({
							url:"<?php echo U("/domain_register/domain_ns_edit");?>",
							type:"POST",
							success:function (res1) {
								$.ui.close_loading();
								if (res1.error == 0) {
									$.ui.success(res1.message);
									setTimeout(function () {
										$(".close-sub").click();
									},1000)
								}else{
									$.ui.error(res1.message);
								}
							},
							data:{ns1:ns1,ns2:ns2,domain:name,ns:ns,hash:"<?php echo tUtil::hash();?>"}
						});
					});
				}else{
					$.ui.error(res.message);
				}
			},
			data:{domain:name}
		});

	}
</script>
<!-- 更改ns end-->

<!-- 域名续费 start-->
<script type="text/template" id="tpl_renew">
	<#macro row data>
		<div  class="form form-2 t-ajax-form" role="form">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="">域名续费</h4>
			</div>
			<div class="modal-body">
				<div class="form-item form-item-text">
					<label>续费域名：</label>
					<div class="item-v" style="position: relative;top:6px;">${data.domain}</div>
					<div class="cl"></div>
				</div>

				<div class="form-item form-item-text">
					<label>续费价格：</label>
					<div class="item-v renew-price" style="position: relative;top:5px;color:orange;font-size: 14px;">${data.price}元</div>
					<div class="cl"></div>
				</div>

				<div class="form-item form-item-text">
					<label>续费年限：</label>
					<div class="item-v">
						<select name="num">
							<option value="0">请选择</option>
							<?php $num = array(1,2,3,4,5,6,7,8,9);?>
							<?php foreach($num as $key => $item){?>
							<option value="<?php echo isset($item)?$item:"";?>"><?php echo isset($item)?$item:"";?>年</option>
							<?php }?>
						</select>
						<span class="font-gray">&nbsp;&nbsp; 请选择续费年限</span>
					</div>
					<div class="cl"></div>
				</div>

				<div class="cl"></div>
			</div>
			<div class="modal-footer">
				<input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" />
				<input type="hidden" value="${data.price}" name="renew_price" />
				<button type="submit" class="btn btn-primary renew-sub" data-loading-text="处理中……">提交</button>
				<button type="button" class="btn btn-default close-sub" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var domain_renew = function(obj){
		var name = $(obj).attr("data-domain");
		var tpl_s = $("#tpl_renew").html();
		var tpl_v = {domain:name};

		$.ajaxPassport({
			url:"<?php echo U("/domain_register/domain_renew");?>",
			success:function (res) {
				if (res.error == 0) {

					tpl_v.price = res.message;
					$("#myModal").find(".modal-dialog").width(500);
					$("#myModal").find(".modal-content").html("" + easyTemplate(tpl_s,tpl_v));
					$('#myModal').modal();

					$("select[name='num']").change(function () {
						var n = $(this).val();
						var p = $("input[name='renew_price']").val();
						$(".renew-price").html(n*p+"元");
					});

					$(".renew-sub").click(function () {
						var num = $("select[name='num']").val();
						$.ui.loading();
						$.ajaxPassport({
							url:"<?php echo U("/domain_register/domain_renew");?>",
							type:"POST",
							success:function (res1) {
								$.ui.close_loading();
								if (res1.error == 0) {
									$.ui.success(res1.message);
									setTimeout(function () {
										$(".close-sub").click();
									},1000)
								}else{
									$.ui.error(res1.message);
								}
							},
							data:{domain:name,num:num,hash:"<?php echo tUtil::hash();?>"}
						});
					});
				}else{
					$.ui.error(res.message);
				}
			},
			data:{domain:name}
		});

	}
</script>
<!-- 域名续费 end-->

<!-- 域名套餐购买续费记录 start-->
<script type="text/template" id="tpl_domain_log">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0">
				<col width="130px" />
				<col width="190px" />
				<col width="130px" />
				<col />
				<thead>
				<tr class="active">
					<th>操作时间</th>
					<th>操作IP</th>
					<th>操作项</th>
					<th>说明</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as list>
						<tr>
							<td class="font-gray">${$.time_to_string(list.dateline,'Y-m-d H:i')}</td>
							<td class="font-gray">${list.ipaddr}</td>
							<td class="font-green">${list.modi_item}</td>
							<td class="font-gray">${list.modi_log}</td>
						</tr>
					</#list>
					<#else>
						<tr><td colspan="4"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无操作日志！</span></tr>
				</#if>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var show_domain_log = function(page,domain){
		$.ajaxPassport({
			url:"<?php echo U("/domain_register/domain_log_get?domain=");?>"+domain,
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "域名操作日志("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_domain_log").html(),res);
					$("#myModal").find(".modal-dialog").width("1010");
					$("#myModal").find(".modal-content").html(edit_c);
					$('#myModal').modal();
				}
			},
			data:{page:page}
		});
	}
</script>
<!-- 域名套餐购买续费记录 end-->

<!-- 域名自助管理平台 start-->
<script type="text/template" id="tpl_domain_admin">
	<#macro row data>
		<div  class="form form-2 t-ajax-form" role="form">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">域名自助管理平台</h4>
			</div>
			<div class="modal-body">
				<div class="form-item form-item-text">
					<label>管理员账号：</label>
					<div class="item-v" style="position: relative;top:6px;">${data.domain}</div>
					<div class="cl"></div>
				</div>

				<div class="form-item form-item-text">
					<label>管理员密码：</label>
					<div class="item-v renew-price" style="position: relative;top:5px;color:green;font-size: 14px;">${data.psw}</div>
					<div class="cl"></div>
				</div>

				<div class="form-item form-item-text">
					<label>管理后台地址：</label>
					<div class="item-v renew-price" style="position: relative;top:7px;color:orange;font-size: 14px;">
						<a href="http://dcp.xinnet.com/Modules/agent/domain/domain_manage.jsp" target="_blank" title="直达">http://dcp.xinnet.com/domain/domain_manage <cite class="glyphicon glyphicon-send"></cite></a>
					</div>
					<div class="cl"></div>
				</div>

				<div class="cl"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default close-sub" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var domain_admin = function(obj){
		var name = $(obj).attr("data-domain");
		var tpl_s = $("#tpl_domain_admin").html();
		var tpl_v = {domain:name};

		$.ajaxPassport({
			url:"<?php echo U("/domain_register/domain_self_admin");?>",
			success:function (res) {
				if (res.error == 0) {
					tpl_v.psw = res.message;
					$("#myModal").find(".modal-dialog").width(500);
					$("#myModal").find(".modal-content").html("" + easyTemplate(tpl_s,tpl_v));
					$('#myModal').modal();
				}else{
					$.ui.error(res.message);
				}
			},
			data:{domain:name}
		});

	}
</script>
<!-- 域名自助管理平台 end-->

<!--域名实名审核状态查询 start-->
<script type="text/template" id="tpl_domain_rz">
	<#macro row data>
		<div  class="form form-2 t-ajax-form" role="form">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">域名实名审核</h4>
			</div>
			<div class="modal-body">
				<div class="form-item form-item-text">
					<label>实名域名：</label>
					<div class="item-v" style="position: relative;top:6px;">${data.domain}</div>
					<div class="cl"></div>
				</div>

				<div class="form-item form-item-text">
					<label>证件号码：</label>
					<div class="item-v renew-price" style="position: relative;top:5px;color:green;font-size: 14px;">${data.cart}</div>
					<div class="cl"></div>
				</div>

				<div class="form-item form-item-text">
					<label></label>
					<div class="item-v renew-price" style="position: relative;top:7px;color:orange;font-size: 14px;">
						<a href="${data.url}" target="_blank">查看上传图片</a>
					</div>
					<div class="cl"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default close-sub" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var domain_rz= function(obj){
		var domain = $(obj).attr("data-domain");
		var cart = $(obj).attr("data-cart");
		var url = $(obj).attr("data-url");
		var tpl_s = $("#tpl_domain_rz").html();
		var tpl_v = {domain:domain};

		tpl_v.cart = cart;
		tpl_v.url = url;
		$("#myModal").find(".modal-dialog").width(500);
		$("#myModal").find(".modal-content").html("" + easyTemplate(tpl_s,tpl_v));
		$('#myModal').modal();
	}
</script>
<!--域名实名审核状态查询 end-->
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