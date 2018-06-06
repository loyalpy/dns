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
		<li>
			<a class="cur" title="推广用户" href="<?php echo U("/tg_manager/tg_user");?>">推广用户管理</a>
		</li>
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
  <div class="name">推广用户管理</div>
  <div class="navbtn0">

  </div>
  <div class="navbtn">
  <!--button-->
  <!--end button-->
  </div>
  <div class="cl"></div>
</div>
<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/tg_manager/tg_user?do=get_url");?>">
<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
<div class="list-box">
    <table class="list-table table table-striped table-condensed" cellpadding="0" cellspacing="0">
        <col  width="35px"/>
        <col  />
        <col width="180px" />
        <col width="140px"/>
        <col width="180px" />
        <col width="100px" />
        <col width="100px" />
        <col width="80px" />
        <col width="100px" />
        <col width="100px" />
        <col width="100px" />
        <thead>
        <tr>
        <th>ID</th>
        <th>名称</th>
         <th>邮箱</th>
        <th>手机</th>
        <th>时间</th>
        <th>已支付</th>
        <th>已领取</th>
		<th>锁定</th>
		<th>状态</th>
        <th>操作</th>
        </tr>
        </thead>
        <tbody class="tpl"></tbody>
    </table>
    <div class="pagebar"></div>
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
		<td class="f14">${data.id}</td>
		<td class="font-blue f14 rl">${data.name}</td>
		<td class="font-green">${data.email}</td>
        <td>${data.mobile}</td>
		<td class="font-gray">${data.dateline}</td>
		<td>${data.mytotal}</td>
		<td class="text-warning">${data.balance}</td>
		<td>
			<#if (data.inlock == 1)><span class="text-danger">锁定</span>
				<#else> 开启
			</#if>
		</td>
		<td>
			<#if (data.status == 1)><span class="text-warning">待审核</span>
				<#elseif (data.status == 2)>审核不通过
					<#elseif (data.status == 3)><span class="font-green">审核通过</span>
						<#else> -
			</#if>
		</td>
		<td>
            <button  type="button" class="btn btn-default btn-xs btn-setting-check" data-url="<?php echo U("/tg_manager/tg_user_check?id=");?>${data.id}" data-id="${data.id}">审核</button>
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
<form action="<?php echo U("/tg_manager/tg_user");?>" method="post" class="theform form form-1" role="form">
<div class="tpl"></div>
</form>
</div>
</#macro>
</script>
<script language="javascript">
var search_formdata = {
	email: {type: "uid", label: "用户", name: "email", value: "", uname:"",disabled: 0, data_sr:[], css: "", require: "", desc: "", item_css: ""},
	status:{type:"select",label:"审核状态",name:"status",value:"<?php echo isset($status)?$status:"";?>",data_sr:[{v: "审核不通过", key: "2"},{v: "审核通过", key: "3"}, {v: "待审核", key: "1"}],css:"",require:"",desc:"",item_css:""},
    keyword:{type:"text",label:"关键词",name:"keyword",value:"<?php echo isset($condi['keyword'])?$condi['keyword']:"";?>",data_sr:[],css:"",require:"",desc:"",item_css:"col-md-3"},
	btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
};
var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
var load_list = function(page){
	$.ajaxLoadlist(page,pageurl,function(res){
		var keyword = $(".the_searchform input[name='keyword']").val();
		if(keyword != ""){
			var listhtml = $(".thelistform").find(".tpl").html();
			$(".thelistform").find(".tpl").html($.replace_keyword(listhtml,keyword));
		};
		$(".editbtn").click(function(){
			edit_func(this);
		});
		$(".delbtn").click(function(){
			del_func(this);
		});
		//审核
		$(".btn-setting-check").click(function(){
			var url = $(this).data("url");
			edit_setting_check_func(url);
		});
	});
}

$(function(){
	//加载搜索
	 $.loadform(search_formdata,"",function(res){
	 	pageurl = res.pageurl;
	 	load_list(1);
		return true;
	 },function () {
		 get_userlist(1,0,"",".the_searchform");
	 },".the_searchform");
	 load_list(1);
	 $(".addbtn").click(function(){
		edit_func(this);
	 });
})
</script>
<!-- 审核 start-->
<script type="text/template" id="tpl-setting-group">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class="form-content">
			<form action="<?php echo U("/tg_manager/tg_user_check");?>" method="post" class="theform form form-2" role="form">
				<div class="tpl"></div>
			</form>
		</div>
	</#macro>
</script>
<script language="javascript">
	var setting_group_formdata = {
		name:{type:"ivalue",label:"用户名",name:"name",value:'',data_sr:[],css:"font-gray",require:"",desc:"",item_css:""},
		urate:{type:"text",label:"分红比例",name:"urate",value:"",data_sr:[],css:"",require:"",desc:"%",item_css:""},
		urate1:{type:"text",label:"下级分红比例",name:"urate1",value:"",data_sr:[],css:"",require:"",desc:"%",item_css:""},
		status:{type:"select",label:"审核操作",name:"status",value:"2",data_sr:[{v: "审核不通过", key: "2"},{v: "审核通过", key: "3"}, {v: "取消审核", key: "1"}],css:"font-gray",require:"datatype='*'",desc:"",item_css:""},
		info:{type:"ivalue",label:"申请信息",name:"info",value:"",disabled:0,data_sr:[],css:"",require:"",desc:"",item_css:""},
		inlock:{type:"checkbox",label:"是否锁定",name:"inlock",value:"",disabled:0,data_sr:[{v:"锁定",key:1}],css:"",require:"",desc:"",item_css:""},
		up_mail:{type:"checkbox",label:"发送邮件",name:"up_mail",value:"",disabled:0,data_sr:[{v:"发送",key:1}],css:"",require:"",desc:"",item_css:""},
		id:{type:"hidden",label:"",name:"id",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
		btn:{type:"button",label:"",value:"提交审核",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
	};
	var edit_setting_check_func = function(url){
		var edit_c = $("#tpl-setting-group").html();
		edit_c = "" + easyTemplate(edit_c,{title:"推广用户审核"});
		$("#myModal").find(".modal-dialog").width(550);
		$("#myModal").find(".modal-content").html(edit_c);
		$.loadform(setting_group_formdata,url,function(res){
			load_list();
			return true;
		},function (res) {
		},".theform");
		$('#myModal').modal();
	}
</script>
<!-- 审核 end-->
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