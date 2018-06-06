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
	 <?php if($this->check_purview('/cms_manager/friendlink')){?>
 <li><a href="<?php echo U("/cms_manager/friendlink");?>" class="<?php if('/'.$inc.'/'.$act == '/cms_manager/friendlink'){?>cur<?php }?>">链接管理</a></li>
	 <?php }?>
	 <?php if($this->check_purview('/cms_manager/friendlink_cate')){?>
 <li><a href="<?php echo U("/cms_manager/friendlink_cate");?>" class="<?php if('/'.$inc.'/'.$act == '/cms_manager/friendlink_cate'){?>cur<?php }?>">链接分类</a></li>
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
  <div class="name">链接列表</div>
  <div class="navbtn">
  <!--button-->
	  <?php if($this->check_purview('/cms_manager/friendlink_edit')){?>
  <a href="javascript:void(0)" data-url="<?php echo U("/cms_manager/friendlink_edit?id=0");?>" class="btn btn-primary btn-sm addbtn">
  <cite class="glyphicon glyphicon-plus"></cite> 
  新增链接</a>
	  <?php }?>
  <!--end button-->
  </div>
  <div class="cl"></div>
</div>

<!--search box-->
<form class="the_searchform form" method="POST" action="?do=get_url">
<div class="tpl"></div>
</form>
<!--end search box -->

<!-- list box -->
<form action="" class="thelistform">
<div class="list-box">
<table class="list-table table table-striped table-condensed" cellpadding="0" cellspacing="0">
	<col width="50px" />
	<col width="100px" />

    <col width="260px" />
	<col />
	<col width="200px" />
	<col width="100px" />
	<thead>
	<tr>	
	<th>ID</th>
	<th>logo</th>
    <th>链接名</th>
	<th>链接地址</th>
	<th>分类</th>
	
	<th>操作</th>
	</tr>
	</thead>
	<tbody class="tpl"></tbody>
</table>
</div>

<!-- -->
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
<!--友情链接编辑按钮-->
<script type="text/template" id="tpl_list_row">
<#macro row data>
    <tr>
    <td class="font-gray">${data.id}</td>
    <td class="font-gray"><#if ($.is_empty(data.logo))> - <#else><img src="<?php echo U("static@/");?>${data.logo}" width="120" height="60" /></#if></td>
    <td class="font-blue f14">${data.name}</td>
    <td class="font-black">${data.link}</td>
    <td class="font-black">${$.show_dataconfig('friendlink',data.cat_id).name}</td>
    <td>
	<p class="table-item-op">
		<?php if($this->check_purview('/cms_manager/friendlink_edit')){?>
	<a href="javascript:void(0);" class="editbtn" data-url="<?php echo U("/cms_manager/friendlink_edit?id=");?>${data.id}"><span class="glyphicon glyphicon-edit"></span></a>
		<?php }?>
		<?php if($this->check_purview('/cms_manager/friendlink_del')){?>
		<a href="javascript:void(0);" class="delbtn" data-url="<?php echo U("/cms_manager/friendlink_del?id=");?>${data.id}&cat_id=${data.cat_id}"><span class="glyphicon glyphicon-remove"></span></a>
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
<form action="<?php echo U("/cms_manager/friendlink_edit");?>" enctype="multipart/form-data" method="post" class="theform form form-1" role="form">
<div class="tpl"></div>

</form>
</div>
</#macro>
</script>


<script language="javascript">
var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
var cat_list_v = [];
<?php foreach($catlist as $key => $item){?>
cat_list_v.push({key:'<?php echo isset($item['code'])?$item['code']:"";?>',v:'<?php echo isset($item['name'])?$item['name']:"";?>'});
<?php }?>
var search_formdata = {
	cat_id:{type:"select",label:"分类",name:"cat_id",value:"<?php echo isset($condi['cat_id'])?$condi['cat_id']:"";?>",disabled:0,data_sr:cat_list_v,css:"low",require:"",desc:"",item_css:"item-100"},
    keyword:{type:"text",label:"关键词",name:"keyword",value:"<?php echo isset($condi['keyword'])?$condi['keyword']:"";?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
	btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
};
var formdata = {
    cat_id:{type:"select",label:"分类",name:"cat_id",value:"",data_sr:dataConfig_A['friendlink'],css:"",require:"datatype='*'",desc:"",item_css:""},
	name:{type:"text",label:"链接名称",name:"name",value:"",data_sr:[],css:"",require:"datatype='*'",desc:"名称不可不填",item_css:""},
	link:{type:"text",label:"链接URL",name:"link",value:"",data_sr:[],css:"",require:"",desc:"链接不可不填",item_css:""},
	sort:{type:"text",label:"序号",name:"sort",value:"0",data_sr:[],css:"",require:"",desc:"",item_css:""},
    logo:{type:"file",label:"logo" ,name:"logo",value:"",data_sr:[],css:"",desc:"",item_css:"",desc:"请上传120px*60px图片文件" },
    id:{type:"hidden",label:"-",name:"id",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
};
var edit_func = function(obj){
	var edit_c = $("#tpl_list_edit").html();
	var url = $(obj).attr("data-url");
	edit_c = "" + easyTemplate(edit_c,{title:"添加/修改链接"});
	$("#myModal").find(".modal-dialog").width(760);
	$("#myModal").find(".modal-content").html(edit_c);
	$.loadform(formdata,url,function(res){
		 loadlist(1);
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
					loadlist();
				}
			}
		});
	}
}

var loadlist = function(page){
	$.ajaxLoadlist(page,pageurl,function(){
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
	});
}
$(function(){
	  //加载搜索
	 $.loadform(search_formdata,"",function(res){
	 	pageurl = res.pageurl;
	 	loadlist(1);
		return true;
	 },null,".the_searchform");
	 
	 loadlist(1);
	 $(".addbtn").click(function(){
		edit_func(this);
	 });
})

//文档加载后执行
$(function(){
     //加载所有单页
	 loadlist();

     //新增单页按钮
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