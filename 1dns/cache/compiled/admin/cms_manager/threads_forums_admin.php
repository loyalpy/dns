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
	 <?php if($this->check_purview('/cms_manager/threads')){?>
 <li><a href="<?php echo U("/cms_manager/threads");?>" class="<?php if('/'.$inc.'/'.$act == '/cms_manager/threads'){?>cur<?php }?>">内容列表</a></li>
	 <?php }?>
	 <?php if($this->check_purview('/cms_manager/threads_forums')){?>
 <li><a href="<?php echo U("/cms_manager/threads_forums");?>" class="<?php if('/'.$inc.'/'.$act == '/cms_manager/threads_forums'){?>cur<?php }?>">内容分类</a></li>
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
  <div class="name">内容分类</div>
  <div class="navbtn0">
  </div>
  <div class="navbtn">
  <!--button-->
	  <?php if($this->check_purview('/cms_manager/threads_forums_refresh')){?>
  <button type="button" data-url="<?php echo U("/cms_manager/threads_forums?do=refresh");?>" class="btn btn-default btn-sm t-ajax-button" data-loading-text="处理中……">&nbsp;<cite class="glyphicon glyphicon-refresh"></cite>&nbsp;</button>
  	<?php }?>
	  <!--end button-->
  <!--button-->
	  <?php if($this->check_purview('/cms_manager/threads_forums_edit')){?>
  <a href="javascript:void(0)" data-url="<?php echo U("/cms_manager/threads_forums?do=edit");?>" class="btn btn-primary btn-sm addbtn">
  <cite class="glyphicon glyphicon-plus"></cite> 
  新增分类</a>
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
<table class="list-table table table-striped table-condensed" cellpadding="0" cellspacing="0">
    <col width="30px" />	
	<col />
	<col width="100px" />
	<col width="100px" />
	<col width="100px" />
	<thead>
	<tr>
	<th>ID</th>
	<th>分类名称</th>
	<th>标识</th>
	<th>文章数</th>
	<th>操作</th>
	</tr>
	</thead>
	<tbody class="tpl"></tbody>
</table>
</div>
</form>
<!-- end list box       http://127.0.0.100/cms_manager/threads?do=get&keyword=&fid=26&page=1&_=1411958130248 <?php echo U("/cms_manager/threads?do=get&fid=");?>${data.id}--> 
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
    <td class="font-gray">${data.id}</td>
    <td><a href="<?php echo U("/cms_manager/threads?fid");?>=${data.id}" class="font-blue f14">${data.space}${data.name}<#if ($.is_empty(data.enname))> <#else><font class="font-gray">(${data.enname})</font></#if> </a></td>
    <td>${data.ident}</td>    
    <td class="font-gray"><#if ($.is_empty(data.threads))> 0 <#else><font class="font-org f14 ">${data.threads}</font></#if></td>
    <td>
	<p class="table-item-op">
		<?php if($this->check_purview('/cms_manager/threads_forums_edit')){?>
    	<a href="javascript:void(0);" class="editbtn" data-url="<?php echo U("/cms_manager/threads_forums?do=edit&id=");?>${data.id}"><span class="glyphicon glyphicon-edit"></span></a>
		<?php }?>
		<?php if($this->check_purview('/cms_manager/threads_forums_copy')){?>
		<a href="javascript:void(0);" class="copybtn" data-url="<?php echo U("/cms_manager/threads_forums?do=copy&id=");?>${data.id}"><span class="glyphicon glyphicon-repeat"></span></a>
		<?php }?>
		<?php if($this->check_purview('/cms_manager/threads_forums_del')){?>
		<a href="javascript:void(0);" class="delbtn" data-url="<?php echo U("/cms_manager/threads_forums?do=del&id=");?>${data.id}"><span class="glyphicon glyphicon-remove"></span></a>
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
<form action="<?php echo U("/cms_manager/threads_forums?do=edit");?>" method="post" class="theform form form-2" role="form">
<div class="tpl"></div>
</form>
</div>
</#macro>
</script>
<script language="javascript">
var formdata = {
	pid:{type:"select",label:"上级模块",name:"pid",value:"",data_sr:<?php echo JSON::encode($catlist);?>,css:"",require:"",desc:"",item_css:"col-md-6"},
	sort:{type:"text",label:"显示序号",name:"sort",value:"",data_sr:[],css:"",require:"",desc:"",item_css:"col-md-6"},
	name:{type:"text",label:"分类名",name:"name",value:"",data_sr:[],css:"",require:"datatype='*'",desc:"分类名必须",item_css:"col-md-6"},
	ident:{type:"text",label:"标识",name:"ident",value:"",data_sr:[],css:"",require:"",desc:"",item_css:"col-md-6"},
        html_space0:{type:"html",value:"<h5>&nbsp;</h5>"},

	
    description:{type:"textarea",label:"描述",name:"description",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
    
    id:{type:"hidden",label:"-",name:"id",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
};
var load_list = function(){
	$.loadlist([],function(res){
		$(".editbtn").click(function(){
			edit_func(this);
		});
		$(".copybtn").click(function(){
			copy_func(this);
		});
		$(".delbtn").click(function(){
			del_func(this);
		});
	},"<?php echo U("/cms_manager/threads_forums?do=get");?>");
}
var copy_func = function(obj){
	var url = $(obj).attr("data-url");
		$.ajaxPassport({
			url:url,
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					$.tips(res.message,"success");
					load_list();
				}
			}
		});
}

var edit_func = function(obj){
	var edit_c = $("#tpl_list_edit").html();
	var url = $(obj).attr("data-url");
	edit_c = "" + easyTemplate(edit_c,{title:"添加/修改文章分类"});
	$("#myModal").find(".modal-dialog").width(920);
	$("#myModal").find(".modal-content").html(edit_c);
	$.loadform(formdata,url,function(res){
		 load_list();
		 return true;
	},function(res){
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
					$.tips(res.message,"error");
				}else{
					$.tips(res.message,"success");
					load_list();
				}
			}
		});
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