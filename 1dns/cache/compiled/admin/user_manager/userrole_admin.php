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
      
      <?php if(isset($this->nav['nav'][$this->nav['top']])){?>
      <div class="menu">
      <ul>
      <?php foreach($this->nav['nav'][$this->nav['top']]['childrens'] as $key => $item){?>
      <?php if($this->check_purview($item['url']) && $item['status'] == 1){?>
    <li><a href="<?php echo isset($item['url'])?$item['url']:"";?>" class="<?php if($this->nav['sub'] == $key){?>cur<?php }?>"><?php echo isset($item['name'])?$item['name']:"";?></a></li>
    <?php }?>
      <?php }?>
      </ul>
      </div>
      <?php }?>
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
  <div class="name">用户角色</div>
  <div class="navbtn">
  <!--button-->
	  <?php if($this->check_purview('/user_manager/userrole_edit')){?>
  <a href="javascript:void(0)" data-url="<?php echo U("/user_manager/userrole_edit?id=0");?>" class="btn btn-primary btn-sm addbtn">
  <cite class="glyphicon glyphicon-plus"></cite> 
  新增角色</a>
	  <?php }?>
  <!--end button-->
  </div>
  <div class="cl"></div>
</div>
<!-- list box -->
<form action="" class="thelistform">
<div class="list-box">
<table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
	<col width="70px" />
	<col width="180px" />
	<col />
	<col width="220px" />
	<col width="70px" />
	<col width="100px" />
	<thead>
	<tr>	
	<th>角色ID</th>
	<th>角色名</th>
	<th>角色描述</th>
	<th>所属公司</th>
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
<?php echo $this->fetch('tpl/form')?>
<script type="text/template" id="tpl_list_edit">
<#macro rowedit data>
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">${data.title}</h4>
</div>
<div class="form-content">
<form action="<?php echo U("/user_manager/userrole_edit");?>" method="post" class="theform form form-2" role="form">
<div class="tpl"></div>
</form>
</div>
</#macro>
</script>
<script type="text/template" id="tpl_list_row">
<#macro row data>
    <tr>
    <td>${data.id}</td>
    <td class="font-gray">${data.name}</td>
    <td>${data.content}</td>
    <td></td>
    <td class="font-gray">
    <#if (data.inlock==1)><cite class="glyphicon glyphicon-exclamation-sign font-red"></cite> 已锁<#else><cite class="glyphicon glyphicon-ok-sign font-green"></cite> 正常</#if>
    </td>
    
    <td class="font-gray">
    <p class="table-item-op">
    <#if (data.id == 1)> -
    <#else>
		<?php if($this->check_purview('/user_manager/userrole_edit')){?>
	<a href="javascript:void(0);" class="editbtn" data-url="<?php echo U("/user_manager/userrole_edit?id=");?>${data.id}"><span class="glyphicon glyphicon-edit"></span></a> 
		<?php }?>
		<#if (data.id == 2)> <#else>
			<?php if($this->check_purview('/user_manager/userrole_del')){?>
	<a href="javascript:void(0);" class="delbtn" data-url="<?php echo U("/user_manager/userrole_del?id=");?>${data.id}"><span class="glyphicon glyphicon-remove"></span></a> 
			<?php }?>
		</#if>
	</#if>
	</p>
    </td>
	</tr>
</#macro>
</script>
<script language="javascript">
var formdata = {
	name:{type:"text",label:"角色名",name:"name",value:"",disabled:0,data_sr:[],css:"",require:"datatype='*'",desc:"角色名必须填写",item_css:""},
	content:{type:"textarea",label:"角色描述",name:"content",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
    inlock:{type:"checkbox",label:"锁定",name:"inlock",value:"",disabled:0,data_sr:[{v:"锁定",key:1}],css:"",require:"",desc:"",item_css:""},
	html4:{type:"html",value:"<div class=\"rpurview\"></div>"},
	html1:{type:"html",value:"<h4>模块权限</h4><div class=\"dis10\"></div>"},
	html2:{type:"html",value:"<div class=\"purview\"></div>"},
    id:{type:"hidden",label:"",name:"id",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
    btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
};
//分组权限全选
var checkGroupAll = function(obj){
	var ischecked = parseInt($(obj).attr("data-s"));
	var name = $(obj).attr("data-g");
	if(ischecked == 1){
		$('input[data-g="'+name+'"]').attr('checked',"checked");
		$(obj).attr("data-s",0);
		$(obj).html("全不选");
	}else{
		$('input[data-g="'+name+'"]').attr('checked',false);
		$(obj).attr("data-s",1);
		$(obj).html("全选");
	}
}
var edit_func = function(obj){
	var edit_c = $("#tpl_list_edit").html();
	var url = $(obj).attr("data-url");
	edit_c = "" + easyTemplate(edit_c,{title:"添加/修改角色"});
	$("#myModal").find(".modal-dialog").width(1010);
	$("#myModal").find(".modal-content").html(edit_c);
	$.loadform(formdata,url,function(res){
		 loadlist(1);
		 return true;
	},function(res){
		 var rhtml = html = "";
		 for(var i in res.all_purview){
		 	if(parseInt(res.all_purview[i]['status']) != 1)continue;
		 	html += "<div class='form-item purview-item'>";
		 	html += "<label>"+res.all_purview[i]['name']+"<br/> <font class=\"font-gray tr\"><button type=\"button\" data-s=\"1\" data-g=\""+res.all_purview[i]['url']+"\" class=\"btn btn-default btn-xs selall\" type=\"checkbox\">全选</button></font></label>";
		 	html += "<div class=\"item-v\">";
		 	for(var j in res.all_purview[i]['childrens']){
		 		if(parseInt(res.all_purview[i]['childrens'][j]['status']) != 1)continue;
		 		var url = res.all_purview[i]['childrens'][j].url;
		 		html += "<div class=\"submenu\"><div class=\"sub0\"><input class=\"reset\" "+(res.purview.indexOf("@"+url+"@")>=0?"checked=\"checked\"":"")+" data-g=\""+res.all_purview[i]['url']+"\" name=\"purview["+res.all_purview[i]['url']+"][]\" value=\""+url+"\" type=\"checkbox\" /> "+res.all_purview[i]['childrens'][j].name+"</div>";
		 		html += "<div class=\"sub1\">";
		 		for(var k in res.all_purview[i]['childrens'][j]['purview']){
		 			var url = res.all_purview[i]['childrens'][j].url+"_"+k;
		 			html += "<span class=\"font-gray\"><input class=\"reset\" "+(res.purview.indexOf("@"+url+"@")>=0?"checked=\"checked\"":"")+" name=\"purview["+res.all_purview[i]['url']+"][]\" data-g=\""+res.all_purview[i]['url']+"\" value=\""+url+"\" type=\"checkbox\" /> "+res.all_purview[i]['childrens'][j]['purview'][k]+"</span>";
		 		}
		 		html += "<div class=\"cl\"></div></div>";
		 		html += "<div class=\"cl\"></div></div>";
		 	}
		 	html += "<div class=\"cl\"></div></div>";
		 	html += "<div class=\"cl\"></div></div>";
		 }
		 var rhtml = "<div class='form-item purview-item'><label></label><div class=\"item-v\"><div class=\"submenu rpurview\">";
		  for(var i in res.all_rpurview){
		  	  rhtml += "<span><input class=\"reset\" "+((res.rpurview.substr(i,1) == "Y")?"checked=\"checked\"":"")+" name=\"rpurview"+i+"\" value=\"Y\" type=\"checkbox\" /> "+res.all_rpurview[i]+"["+i+"]&nbsp;&nbsp;&nbsp;&nbsp;</span>";
		  	  
		  }
		 rhtml += "<div class=\"cl\"></div></div>";
		 rhtml += "<div class=\"cl\"></div><div class=\"dis20\"></div></div>";
		 rhtml += "<div class=\"cl\"></div></div>";
		 $("#myModal").find(".modal-content").find(".purview").html(html);
		  $("#myModal").find(".modal-content").find(".rpurview").html(rhtml);
		 $("#myModal").find(".modal-content").find("button.selall").click(function(){
		 	checkGroupAll(this);
		 })
	});
	$('#myModal').modal();
}
var del_func = function(obj){
	if(confirm("你确定要删除该数据吗?删除后数据不可恢复！关联用户将失去权限")){
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
var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
var loadlist = function(page){
	$.ajaxLoadlist(page,pageurl,function(){
		var keyword = $(".the_searchform input[name='keyword']").val();
		if(keyword != ""){
			var listhtml = $(".thelistform").find(".tpl").html();
			$(".thelistform").find(".tpl").html($.replace_keyword(listhtml,keyword));
		}
		$(".editbtn").click(function(){
			edit_func(this);
		 });
		$(".delbtn").click(function(){
			del_func(this);
		});
	});
}
$(function(){
	loadlist(1);
	//增加按钮
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