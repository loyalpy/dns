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
	<li><a href="<?php echo U("/user_manager/userlist_com?ut=2");?>" class="cur">企业会员</a></li>
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
   <!--button-->
	  <?php if($this->check_purview("/user_manager/userlist")){?>
  <button type="button" class="btn btn-default btn-sm refresh-btn" data-loading-text="处理中……">&nbsp;<cite class="glyphicon glyphicon-refresh"></cite>&nbsp;</button>
	  <?php }?>
	  <!--end button-->
  <!--button-->
  <!--end button-->
  </div>
  <div class="cl"></div>
</div>

<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/user_manager/userlist_com?do=get_url");?>">
<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
<div class="list-box">
<table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
	<col />
	<col width="220px" />
	<col width="180px" />
	<col width="90px" />
	<col width="90px" />
	<col width="100px" />
	<col width="100px" />
	<col width="130px" />
	<col width="37px" />
	<col width="100px" />
	<thead>
	<tr>
	<th>公司名[ID]
&nbsp;&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="a.company_id" data-desc=""><cite></cite></a>
	</th>
		<th>联系信息&nbsp;&nbsp;
			<a href="javascript:void(0)" class="orderby" data-item="d.emailrz" data-desc=""><cite></cite></a></th>
	<th>认证&nbsp;&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="a.company_id" data-desc=""><cite></cite></a></th>
		<th>解析域名 <a href="javascript:void(0)" class="orderby" data-item="e.domains" data-desc=""><cite></cite></a></th>
		<th>注册域名 <a href="javascript:void(0)" class="orderby" data-item="e.register_domains" data-desc=""><cite></cite></a></th>
		<th>余额&nbsp;&nbsp;
			<a href="javascript:void(0)" class="orderby" data-item="e.balance" data-desc=""><cite></cite></a>
		</th>
		<th>积分<a href="javascript:void(0)" class="orderby" data-item="e.point" data-desc=""><cite></cite></a>&nbsp;短信<a href="javascript:void(0)" class="orderby" data-item="e.sms" data-desc=""><cite></cite></a></th>
	<th>注册&nbsp;&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="d.regdateline" data-desc=""><cite></cite></a>
登录&nbsp;&nbsp;
	<a href="javascript:void(0)" class="orderby" data-item="d.logdateline" data-desc=""><cite></cite></a>
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
<script type="text/template" id="tpl_list_row">
<#macro row data>
    <tr>
    <td class="<#if (data.urole == 1)>font-red<#elseif (data.urole > 1)>font-blue</#if>" title="<#if (data.urole>0)>${roleList_A[data.urole]['name']}</#if>">
    <#if (data.bd.status == 2)>
    <a class="font-green tiptitle" data-content="<p><img width='150' src='${data.bd.wx_avatar}' /></p><p class='font-gray'>${data.bd.wx_nickname} ${data.bd.wx_city}</p>"><img width="15" height="15" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/wx.png";?>"  /></a>
    <#else>
    <cite title="当前离线" class="glyphicon glyphicon-user font-gray"></cite>
    </#if>
    <span class="keybox">${data.company_name}</span><span class="font-gray">[${data.company_id}]</span>
    <#if (data.urole>0)>
    <a style="display:none;" class="glyphicon glyphicon-question-sign font-red"></a>
    </#if>

    <br/>
    <font class="font-green">[${utypes[data.utype]['name']}]</font><font class="font-gray">${ulevel_A[data.utype][data.ulevel]['alias']}</font><span class="font-gray2">${data.realname}</span>
    </td>
		<td>
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
    <td>
		<?php if($this->check_purview('/user_manager/userlist_com_edit')){?>
		<#if (!$.is_empty(data.rzs.shenfenzheng))><a target="_blank" href="<?php echo U("static@");?>${data.rzs.shenfenzheng.path}">营业执照已传</a>
			<#if (data.rzs.shenfenzheng.status == 3)><span class="font-green">[已审]</span>
				<#elseif (data.rzs.shenfenzheng.status == 2)><span class="font-red">[失败]</span>
					<#elseif (data.rzs.shenfenzheng.status == 1)><span class="font-org">[变更]</span>
						<#else><span class="font-gray">[待审]
			</#if>
			<#else>
				<font class="font-red">营业执照未传</font><br/>
		</#if>

		<#if (!$.is_empty(data.rzs.jigou))>
			<br/><a target="_blank" href="<?php echo U("static@");?>${data.rzs.jigou.path}">组织机构已传</a>
			<#if (data.rzs.jigou.status == 3)><span class="font-green">[已审]</span>
				<#elseif (data.rzs.jigou.status == 2)><span class="font-red">[失败]</span>
					<#elseif (data.rzs.jigou.status == 1)><span class="font-org">[变更]</span>
						<#else><span class="font-gray">[待审]
			</#if>
			<#else>
				<font class="font-red">组织机构未传</font><br/>
		</#if>

		<#if (!$.is_empty(data.rzs.shenfenzheng) || !$.is_empty(data.rzs.jigou))><br/>
			<button type="button" data-uuid="${data.uid}" data-utype="${data.utype}" data-rzs_s="${data.rzs.shenfenzheng.path}" data-rzs_j="${data.rzs.jigou.path}"  data-url="<?php echo U("/user_manager/userlist_com_edit?uuid=");?>${data.uid}" class="btn btn-xs btn-success btn-rzsh"><#if (data.ulevel == 0)>待认证<#else>已认证</#if></button>
			<#else>
				<button type="button"  class="btn btn-xs btn-danger">待上传</button>
		</#if>
		<?php }else{?>

		<?php }?>
    </td>
		<td><a href="<?php echo U("domain_manager/domain?uid=");?>${data.uid}" class="font-green f14">${data.account.domains}</a></td>
		<td><a href="<?php echo U("domain_register/domain?keyword=");?>${data.email}" class="font-green f14">${data.account.register_domains}</a></td>
		<td><font class="f14 font-red">${data.account.balance}</font></td>
		<td class="f12">${data.account.point}/<#if ($.is_empty(data.account.sms))> 0 <#else> ${data.account.sms}</#if></td>
		<td>${data.regdateline}<br/>${data.logdateline}</td>
    <td class="font-gray"><#if (data.inlock==1)><cite title="已锁" class="glyphicon glyphicon-exclamation-sign font-red"></cite><#else><cite class="glyphicon glyphicon-ok-sign font-green" title="正常"></cite></#if></td>
    <td class="font-gray">
    <p class="table-item-op">
	<?php if($this->check_purview('/user_manager/userlist_edit')){?>
	<a href="javascript:void(0);" class="editbtn" data-utype="${data.utype}" data-url="<?php echo U("/user_manager/userlist_edit?uid=");?>${data.uid}"><span class="glyphicon glyphicon-edit"></span></a>
	<?php }?>
	<?php if($this->check_purview('/user_manager/userlist_refresh')){?>
	<a href="javascript:void(0);" class="t-ajax-button" data-url="<?php echo U("/user_manager/userlist_refresh?uid=");?>${data.uid}"><span class="glyphicon glyphicon-refresh"></span></a>
	<?php }?>
	<?php if($this->check_purview('/user_manager/userlist_del')){?>
	<a href="javascript:void(0);" class="t-ajax-button" confirm="1" data-url="<?php echo U("/user_manager/userlist_del?uid=");?>${data.uid}"><span class="glyphicon glyphicon-remove"></span></a>
	<?php }?>
	<?php if($this->check_purview('/user_manager/userlist_quicklogin')){?>
	<a href="<?php echo U("/user_manager/userlist_quicklogin?uid=");?>${data.uid}" target="_blank" title="快速登录"><span class="glyphicon glyphicon-user"></span></a>
	<?php }?>&nbsp;
	<?php if($this->check_purview('/user_manager/userlist_recharge')){?>
	<button data-id="${data.uid}" data-name="${data.name}" type="button" class="btn btn-danger btn-xs table-item-op-recharge">账户</button>
	<?php }?>
	<?php if($this->check_purview('/user_manager/userlist_setting')){?>
    <button data-id="${data.uid}" data-name="${data.name}" type="button" class="btn btn-danger btn-xs btn-setting" data-url="<?php echo U("/user_manager/userlist_setting?uid=");?>${data.uid}">设置</button>
	<?php }?>
	</p>
    </td>
	</tr>
</#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<script language="javascript">
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
		$(".btn-rzsh").click(function () {
			rz_op(this);
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
	});
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
<!-- 编辑用户信息 -->
<script type="text/template" id="tpl_rz_edit">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class="form-content">
			<form action="<?php echo U("/user_manager/userlist_com_edit");?>" method="post" class="theform form form-2" role="form">
				<div class="tpl"></div>
			</form>
		</div>
	</#macro>
</script>
<script language="javascript">
	var userinfo_formdata3 = {


		shenfenzheng:{type:"select",label:"身份证审核",name:"shenfenzheng",value:"",disabled:0,data_sr:[{v: "审核通过", key: "3"}, {v: "审核不通过", key: "2"}],css:"",require:"datatype='*'",desc:"",item_css:""},
		jigou:{type:"select",label:"组织机构审核",name:"jigou",value:"",disabled:0,data_sr:[{v: "审核通过", key: "3"}, {v: "审核不通过", key: "2"}],css:"",require:"datatype='*'",desc:"",item_css:""},
		ulevel:{type:"select",label:"会员等级",name:"ulevel",value:"",disabled:0,data_sr:[],css:"",require:"",desc:"",item_css:""},
		html_space1:{type:"html",value:"<h5>&nbsp;</h5>"},
		carts:{type:"ivalue",label:"证件号码",name:"carts",value:"",data_sr:[],css:"font-gray",require:"",desc:"",item_css:""},
		uuid:{type:"hidden",label:"",name:"uuid",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
		hash:{type:"hidden",label:"",name:"hash",value:"<?php echo tUtil::hash();?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
		btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
	};
	var rz_op = function(obj){
		var edit_c = $("#tpl_rz_edit").html();
		var url = $(obj).data("url");
		var utype = $(obj).data("utype");
		var rzs_s = $(obj).data("rzs_s");
		var rzs_j = $(obj).data("rzs_j");
		edit_c = "" + easyTemplate(edit_c,{title:"企业认证"});
		$("#myModal").find(".modal-dialog").width(520);
		$("#myModal").find(".modal-content").html(edit_c);
		userinfo_formdata3.ulevel.data_sr = ulevel[utype];
		userinfo_formdata3.shenfenzheng.desc = "<a href='<?php echo U("static@");?>"+rzs_s+"' style='margin-left: -12px;' target='_blank'>查看上传图片</a>";
		userinfo_formdata3.jigou.desc = "<a href='<?php echo U("static@");?>"+rzs_j+"' style='margin-left: -12px;'  target='_blank'>查看上传图片</a>";
		$.loadform(userinfo_formdata3,url,function(res){
			return true;
		},'');
		$('#myModal').modal();
	}
</script>
<!-- end编辑用户信息结束 -->
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