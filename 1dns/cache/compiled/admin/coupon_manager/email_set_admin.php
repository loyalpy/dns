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
		<li><a href="<?php echo U("/coupon_manager/email_set");?>" class="cur">发送配置</a></li>
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
  <div class="name">发送配置</div>
  <div class="navbtn">
  <!--button-->
	  <?php if($this->check_purview('/coupon_manager/email_set')){?>
	    <a href="javascript:void(0)" data-url="<?php echo U("/coupon_manager/email_set?do=edit&id=0");?>" class="btn btn-primary btn-sm addbtn">
  <cite class="glyphicon glyphicon-plus"></cite> 
  新增发送配置</a>
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
	<col />
	<col width="150px" />
	<col width="80px"/>
	<col width="130px"/>
	<col width="150px" />
	<!--<col width="110px" />-->
	<col width="120px" />
	<col width="50px" />
	<col width="80px" />
	<thead>
	<tr>
	<th>任务名称</th>
	<th>模板名称</th>
	<th>发送数量</th>
	<th>发送会员类型</th>
	<th>开始/结束日期</th>
	<!--<th>开始ID段</th>-->
	<th>发送会员等级</th>
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
		<td class="font-black">${data.name}<span class="font-green"> [${data.ident}]</span></td>
		<td>${data.tpl_id}</td>
		<td>${data.num}封</td>
		<td>
			<#if (data.u_type == 0)>
				全部
				<#elseif (data.u_type == 1)> 个人
				<#elseif (data.u_type == 2)> 企业
					<#else>代理
			</#if>
		</td>
		<td>${data.start_dateline}<br/>${$.time_to_string(data.end_dateline,"Y-m-d H:i:s")}</td>
		<td><#if ($.is_empty(data.ulevels_name))> - <#else> ${data.ulevels_name} </#if></td>
		<td class="font-gray"><#if (data.inlock==1)><cite class="glyphicon glyphicon-exclamation-sign font-red"></cite>锁<#else><cite class="glyphicon glyphicon-ok-sign font-green"></cite> </#if></td>
		<td>
		<p class="table-item-op">
			<?php if($this->check_purview('/coupon_manager/email_set')){?>
		<a href="javascript:void(0);" class="editbtn" data-url="<?php echo U("/coupon_manager/email_set?do=edit&id=");?>${data.id}"><span class="glyphicon glyphicon-edit"></span></a>
			<?php }?>
			<?php if($this->check_purview('/coupon_manager/email_set')){?>
			<a href="javascript:void(0);" class="delbtn" data-url="<?php echo U("/coupon_manager/email_set?do=del&id=");?>${data.id}"><span class="glyphicon glyphicon-remove"></span></a>
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
<form action="<?php echo U("/coupon_manager/email_set?do=edit");?>" method="post" class="theform form form-1" role="form">
<div class="tpl"></div>
</form>
</div>
</#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<script language="javascript">
	var tpl_name = <?php echo JSON::encode($tpl_name);?>;

	var userLevel_per = <?php echo JSON::encode($userlevel_per);?>;
	var userLevel_J_per = new Array();
	for(var i in userLevel_per){
		userLevel_J_per.push({"key":userLevel_per[i]['ident'],"v":userLevel_per[i]['alias']});
	}

	var userLevel_com = <?php echo JSON::encode($userlevel_com);?>;
	var userLevel_J_com = new Array();
	for(var i in userLevel_com){
		userLevel_J_com.push({"key":userLevel_com[i]['ident'],"v":userLevel_com[i]['alias']});
	}

	var userType = <?php echo JSON::encode($utypes);?>;
	var userType_J = new Array();
	for(var i in userType){
		userType_J.push({"key":userType[i]['code'],"v":userType[i]['name']});
	}

	var formdata = {
		name:{type:"text",label:"任务名称",name:"name",value:"",data_sr:[],css:"",require:"datatype='*'",desc:"任务名称必须填写",item_css:""},
		ident:{type:"text",label:"任务标识",name:"ident",value:"",data_sr:[],css:"",require:"datatype='*'",desc:"任务标识不能为空且唯一",item_css:""},

		html_space7:{type:"html",value:"<h5>&nbsp;</h5>"},
		num:{type:"text",label:"发送数量",name:"num",value:"0",data_sr:[],css:"",require:"datatype='*'",desc:"发送数量10-500",item_css:"font-org"},
		ids:{type:"hidden",label:"发送ID段",name:"ids",value:"0",data_sr:[],css:"",require:"datatype='*'",desc:"格式：0-100",item_css:"font-org"},
		tpl_id: {type: "select", label: "邮件模板", name: "tpl_id", value: "", disabled: 0, data_sr:tpl_name, css: "", require: "datatype='*'", desc: "", item_css: "font-green"},
		html_space6:{type:"html",value:"<h5>&nbsp;</h5>"},

		start_dateline:{type:"date",label:"任务开始日期",name:"start_dateline",value:"",data_sr:[],css:"shigh date-ymdhi",require:"",desc:"",item_css:""},
		end_dateline:{type:"date",label:"任务结束日期",name:"end_dateline",value:"",data_sr:[],css:"shigh date-ymdhi",require:"",desc:"",item_css:""},

		u_type: {type: "select", label: "会员类型", name: "u_type", value: "", disabled: 0, data_sr:userType_J, css: "", require: "", desc: "", item_css: "click_sj"},
		u_level:{type:"checkbox",label:"参与会员等级",name:"u_level[]",value:"",data_sr:userLevel_J_per,css:"",require:"",desc:"",item_css:"u_per"},
		u_level_com:{type:"checkbox",label:"参与会员等级",name:"u_level_com[]",value:"",data_sr:userLevel_J_com,css:"",require:"",desc:"",item_css:"u_com"},
		inlock:{type:"checkbox",label:"锁定",name:"inlock",value:"",disabled:0,data_sr:[{v:"锁定",key:1}],css:"",require:"",desc:"",item_css:""},
		id:{type:"hidden",label:"",name:"id",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
		btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>',item_css:"submits"}
	};
	var load_list = function(){
		$.loadlist([],function(res){
			$(".editbtn").click(function(){
				edit_func(this);
			});
			$(".delbtn").click(function(){
				del_func(this);
			});
		},"<?php echo U("/coupon_manager/email_set?do=get");?>");
	}
	var edit_func = function(obj){
		var edit_c = $("#tpl_list_edit").html();
		var url = $(obj).attr("data-url");
		edit_c = "" + easyTemplate(edit_c,{title:"添加/修改"});
		$("#myModal").find(".modal-dialog").width(760);
		$("#myModal").find(".modal-content").html(edit_c);
		$.loadform(formdata,url,function(res){
			 load_list();
			 return true;
		},function (res) {
			$("#myModal").find(".modal-content").find(".tpl").find("input.date-ymdhi").unbind("click").bind("click",function(){
				laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})
			});
			hide_show_ch(res.u_type);
			$(".click_sj").find(".item-v ul li .sel0").click(function () {
				hide_show_ch($(this).data("v"));
			});
			$(".submits").find(".item-v button[type='submit']").click(function () {
				var vl = $("input[name='u_type']").val();
				if (vl == 1) {
					$(".u_com").remove();
				}else if (vl == 2){
					$(".u_per").remove();
				}
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
						$.ui.error(res.message);
					}else{
						$.ui.success(res.message);
						load_list();
					}
				}
			})
		}
	}
	var hide_show_ch = function (type) {
		if (type == 1) {
			$(".u_per").show();
			$(".u_com").hide();
		}else if (type == 2){
			$(".u_per").hide();
			$(".u_com").show();
		}else{
			$(".u_per").hide();
			$(".u_com").hide();
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