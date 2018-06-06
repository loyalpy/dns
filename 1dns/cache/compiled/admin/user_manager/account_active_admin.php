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
    <?php foreach($utypes as $key => $item){?>
	<li><a href="<?php echo U("/user_manager/account_active?ut=$key");?>" class="<?php if($ut == $key){?>cur<?php }?>"><?php echo isset($item['name'])?$item['name']:"";?></a></li>
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
  <div class="name">活动配置</div>
  <div class="navbtn">
  <!--button-->
	  <?php if($this->check_purview('/user_manager/account_active_makecache')){?>
  <button type="button" data-url="/user_manager/account_active?do=makecache" class="btn btn-default btn-sm t-ajax-button" data-loading-text="处理中……">&nbsp;<cite class="glyphicon glyphicon-refresh"></cite>&nbsp;</button>
  <?php }?>
	  <?php if($this->check_purview('/user_manager/account_active_add')){?>
	    <a href="javascript:void(0)" data-url="<?php echo U("/user_manager/account_active?do=edit&id=0");?>" class="btn btn-primary btn-sm addbtn">
  <cite class="glyphicon glyphicon-plus"></cite> 
  新增用户类型活动</a>
	  <?php }?>
  <!--end button-->
  </div>
  <div class="cl"></div>
</div>
<!--search box-->
<form action=""></form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
<div class="list-box">
<table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
	<col width="120px" />
	<col width="150px" />
	<col width="160px"/>
	<col  />
	<col width="150px" />
	<col width="150px" />
	<col width="120px" />
	<col width="50px" />
	<col width="60px" />
	<thead>
	<tr>
	<th>活动名称</th>
	<th>余额/短信/积分</th>
	<th>代金券金额/期限/限制</th>
	<th>代金券名称/类型</th>
	<th>活动开始日期</th>
	<th>活动结束日期</th>
	<th>参与会员等级</th>
	<th>锁定</th>
	<th>操作</th>
	</tr>
	</thead>
	<tbody class="tpl"></tbody>
</table>
</div>
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
<script type="text/template" id="tpl_list_row">
<#macro row data>
    <tr>
		<td class="font-black">${data.alias}<span class="font-green"> (${data.ident})</span></td>
		<td>
			<#if (parseFloat(data.balance)<0)><font class="font-red">${data.balance}</font><#else><font class="font-green">+${data.balance}</font></#if>/
			<#if (parseFloat(data.sms)<0)><font class="font-red">${data.sms}</font><#else><font class="font-green">+${data.sms}</font></#if>/
			<#if (parseInt(data.point)<0)><font class="font-red">${data.point}</font><#else><font class="font-green">+${data.point}</font></#if>
		</td>
		<td>
			<#if (parseFloat(data.coupon)<=0)><font class="font-gray">${data.coupon}</font><#else><font class="font-green">${data.coupon}</font></#if>/
			<#if (parseFloat(data.coupon_expiry)<=0)><font class="font-gray">${data.coupon_expiry}</font><#else><font class="font-green">${data.coupon_expiry}</font></#if>/
				<font class="font-green">${data.coupon_need}</font>
		</td>
		<td>
			<#if (parseFloat(data.coupon) > 0)>
				<span class="font-blue">${data.coupon_name}</span><br/>
				<span class="font-gray"><#if (data.coupon_type == 1)>通用<#elseif (data.coupon_type == 2)>域名解析专用<#elseif (data.coupon_type == 3)>域名注册专用<#else> - </#if></span>
				<#else> -
			</#if>
		</td>
		<td>${$.time_to_string(data.start_expiry,"Y-m-d H:i:s")}</td>
		<td>${$.time_to_string(data.end_expiry,"Y-m-d H:i:s")}</td>
		<td>${data.ulevels_name}</td>
		<td class="font-gray"><#if (data.inlock==1)><cite class="glyphicon glyphicon-exclamation-sign font-red"></cite>锁<#else><cite class="glyphicon glyphicon-ok-sign font-green"></cite> </#if></td>
		<td>
		<p class="table-item-op">
			<?php if($this->check_purview('/user_manager/account_active_edit')){?>
		<a href="javascript:void(0);" class="editbtn" data-url="<?php echo U("/user_manager/account_active?do=edit&id=");?>${data.id}"><span class="glyphicon glyphicon-edit"></span></a>
			<?php }?>
			<?php if($this->check_purview('/user_manager/account_active_del')){?>
			<a href="javascript:void(0);" class="delbtn" data-url="<?php echo U("/user_manager/account_active?do=del&id=");?>${data.id}"><span class="glyphicon glyphicon-remove"></span></a>
			<?php }?>
		</p>
		</td>
	</tr>
</#macro>
</script>
<script type="text/template" id="tpl_list_edit">
<#macro rowedit data>
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">${data.title}</h4>
</div>
<div class="form-content">
<form action="<?php echo U("/user_manager/account_active?do=edit");?>" method="post" class="theform form form-1" role="form">
<div class="tpl"></div>
</form>
</div>
</#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<script language="javascript">
var userLevel = <?php echo JSON::encode($userlevel);?>;
var userLevel_J = new Array();
for(var i in userLevel){
	userLevel_J.push({"key":userLevel[i]['ident'],"v":userLevel[i]['alias']});
}
var formdata = {
	alias:{type:"text",label:"活动名称",name:"alias",value:"",data_sr:[],css:"",require:"datatype='*'",desc:"活动名称必须填写",item_css:""},
	ident:{type:"text",label:"活动标识",name:"ident",value:"",data_sr:[],css:"",require:"datatype='*'",desc:"活动标识不能为空且唯一",item_css:""},

	html_space7:{type:"html",value:"<h5>&nbsp;</h5>"},
	balance:{type:"text",label:"余额",name:"balance",value:"0.00",data_sr:[],css:"",require:"",desc:"",item_css:"font-org"},
	point:{type:"text",label:"积分",name:"point",value:"0",data_sr:[],css:"",require:"",desc:"",item_css:"font-org"},
	sms:{type:"text",label:"短信数",name:"sms",value:"0",data_sr:[],css:"",require:"",desc:"",item_css:"font-org"},
	coupon:{type:"text",label:"代金券",name:"coupon",value:"0",data_sr:[],css:"",require:"",desc:"优惠金额",item_css:"font-green"},
	coupon_name:{type:"text",label:"代金券名称",name:"coupon_name",value:"0",data_sr:[],css:"",require:"",desc:"",item_css:"font-green"},
	coupon_expiry:{type:"text",label:"代金券期限",name:"coupon_expiry",value:"0",data_sr:[],css:"",require:"",desc:"/天",item_css:"font-green"},
	coupon_need:{type:"text",label:"使用金额限制",name:"coupon_need",value:"0",data_sr:[],css:"",require:"",desc:"购买套餐价格不能低于此",item_css:"font-green"},
	coupon_type: {type: "select", label: "代金券类型", name: "coupon_type", value: "2", disabled: 0, data_sr:[{v: "通用", key: "1"},{v: "域名解析专用", key: "2"}, {v: "域名注册专用", key: "3"}], css: "", require: "datatype='*'", desc: "", item_css: "font-green"},
	html_space6:{type:"html",value:"<h5>&nbsp;</h5>"},

	start_expiry:{type:"date",label:"活动开始日期",name:"start_expiry",value:"",data_sr:[],css:"shigh date-ymdhi",require:"",desc:"",item_css:""},
	end_expiry:{type:"date",label:"活动结束日期",name:"end_expiry",value:"",data_sr:[],css:"shigh date-ymdhi",require:"",desc:"",item_css:""},

	ulevels:{type:"checkbox",label:"参与会员等级",name:"ulevels[]",value:"普通会员",data_sr:userLevel_J,css:"",require:"",desc:"",item_css:""},
	inlock:{type:"checkbox",label:"锁定",name:"inlock",value:"",disabled:0,data_sr:[{v:"锁定",key:1}],css:"",require:"",desc:"",item_css:""},
	bz:{type:"textarea",label:"备注",name:"bz",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	ut:{type:"hidden",label:"",name:"ut",value:"<?php echo isset($ut)?$ut:"";?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
    id:{type:"hidden",label:"",name:"id",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
};
var load_list = function(){
	$.loadlist([],function(res){
		$(".editbtn").click(function(){
			edit_func(this);
		});
		$(".delbtn").click(function(){
			del_func(this);
		});
	},"<?php echo U("/user_manager/account_active?do=get&ut=$ut");?>");
}
var edit_func = function(obj){
	var edit_c = $("#tpl_list_edit").html();
	var url = $(obj).attr("data-url");
	edit_c = "" + easyTemplate(edit_c,{title:"添加/修改"});
	$("#myModal").find(".modal-dialog").width(760);
	$("#myModal").find(".modal-content").html(edit_c);
	$.loadform(formdata,url,function(res){
		 load_list();
		$(".t-ajax-button").click();
		 return true;
	},function (res) {
		$("#myModal").find(".modal-content").find(".tpl").find("input.date-ymdhi").unbind("click").bind("click",function(){
			laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})
		});
	});
	$('#myModal').modal();
}
var del_func = function(obj){
	if(confirm("你确定要删除该数据吗?删除后数据不可恢复！")){
		var url = $(obj).attr("data-url");
		$.ajaxPassport({
			url:url,
			success:function(res){
				if(res.error == 1){
					$.tips(obj,res.message,"error");
				}else{
					$.tips(obj,res.message,"success");
					$(".t-ajax-button").click();
					load_list();
				}
			}
		})
	}
}
$(function(){
	 load_list();
	 $(".addbtn").click(function(){
		edit_func(this);
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