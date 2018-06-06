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
		<li><a href="<?php echo U("/coupon_manager/coupon");?>" class="cur">代金券列表</a></li>
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
	<div class="name">代金券</div>
	<div class="navbtn0">
		<a class="btn <?php if($c == 0){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="" href="<?php echo U("/coupon_manager/coupon?c=0");?>"><cite class="glyphicon glyphicon-th"></cite> 全部</a>
		<a class="btn <?php if($c == 2){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="" href="<?php echo U("/coupon_manager/coupon?c=2");?>"><cite class="glyphicon glyphicon-th"></cite> 已使用</a>
		<a class="btn <?php if($c == 1){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm"  href="<?php echo U("/coupon_manager/coupon?c=1");?>"><cite class="glyphicon glyphicon-th"></cite> 未使用</a>
	</div>
	<div class="cl"></div>
</div>
<!--search box-->
<form enctype="multipart/form-data" class="the_searchform form" method="POST" action="<?php echo U("/coupon_manager/coupon?do=get_url");?>">
	<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
	<div class="list-box">
		<table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
			<col width="160px"/>
			<col  width="150px"/>
			<col  width="70px"/>
			<col  />
			<col  width="100px"/>

			<col width="140px" />
			<col width="140px" />
			<col width="140px" />
			<col  width="70px"/>
			<thead>
			<tr>
				<th>代金券编码</th>
				<th>用户</th>
				<th>金额</th>
				<th>代金券名称</th>
				<th>类型</th>

				<th>生成时间</th>
				<th>使用时间</th>
				<th>到期时间</th>
				<th>状态</th>
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
<script type="text/template" id="tpl_list_row">
	<#macro row data>
		<tr>
			<td>${data.code}</td>
			<td class="font-green">${data.uid}</td>
			<td class="font-green">${data.balance}</td>
			<td class="font-blue ">${data.name}</td>
			<td class="font-gray"><#if (data.type == 1)>通用<#elseif (data.type == 2)>域名解析专用<#elseif (data.type == 3)>域名注册专用<#else></#if></td>

			<td class="">${data.dateline}</td>
			<td class="">${data.use_dateline}</td>
			<td class="">${data.expiry}</td>
			<td class="">
				<#if (data.status==0)><span class="font-gray">未使用</span><#else><span class="font-green">已使用</span> </#if>
			</td>
		</tr>
	</#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<script language="javascript">
	var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
	var formdata = {
		startdate:{type:"date",label:"到期时间",name:"startdate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:""},
		enddate:{type:"date",label:"-",name:"enddate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:"date-dis"},
		keyword:{type:"text",label:"关键词",name:"keyword",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
		btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
	};
	var loadlist = function(page){
		$.ajaxLoadlist(page,pageurl,function(res){
			var keyword = $(".the_searchform input[name='keyword']").val();
			if(keyword != ""){
				var listhtml = $(".thelistform").find(".tpl").html();
				$(".thelistform").find(".tpl").html($.replace_keyword(listhtml,keyword));
			}
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	$(function(){
		//加载搜索
		$.loadform(formdata,"",function(res){
			pageurl = res.pageurl;
			loadlist(1);

			return true;
		},null,".the_searchform");
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