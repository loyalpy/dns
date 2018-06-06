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
  <div class="name">数据配置</div>
  <div class="navbtn">
  <!--button-->
  <button type="button" data-url="/sys_manager/data_config?do=makecache" class="btn btn-default btn-sm t-ajax-button" data-loading-text="处理中……">&nbsp;<cite class="glyphicon glyphicon-refresh"></cite>&nbsp;</button>
  <!--end button-->
  </div>
  <div class="cl"></div>
</div>
<div class="com-box">
	<div class="leftnav2">
		<ul id="myTab">
			<?php if($this->check_purview('/sys_manager/data_config_dns_version')){?>
		<li><a href="javascript:void(0);" data-key="dns_version">DNS软件版本</a></li>
			<?php }?>
			<?php if($this->check_purview('/sys_manager/data_config_suport_domain')){?>
		<li><a href="javascript:void(0);" data-key="suport_domain">支持域名</a></li>
			<?php }?>
			<?php if($this->check_purview('/sys_manager/data_config_RRtype')){?>
		<li><a href="javascript:void(0);" data-key="RRtype">记录类型</a></li>
			<?php }?>
			<?php if($this->check_purview('/sys_manager/data_config_domain_group')){?>
		<li><a href="javascript:void(0);" data-key="domain_group">域名固定分组</a></li>
			<?php }?>
			<?php if($this->check_purview('/sys_manager/data_config_service_num')){?>
		<li><a href="javascript:void(0);" data-key="service_num">购买套餐时间</a></li>
			<?php }?>
			<?php if($this->check_purview('/sys_manager/data_config_scan_host')){?>
		<li><a href="javascript:void(0);" data-key="scan_host">扫描主机</a></li>
			<?php }?>
			<?php if($this->check_purview('/sys_manager/data_config_register_domain')){?>
		<li><a href="javascript:void(0);" data-key="register_domain">域名注册类型</a></li>
			<?php }?>
			<?php if($this->check_purview('/sys_manager/data_config_domain_agent')){?>
		<li><a href="javascript:void(0);" data-key="domain_agent">域名注册代理商</a></li>
			<?php }?>
			<li><a href="javascript:void(0);" data-key="domain_register_type">域名注册实名制审核</a></li>
			<li><a href="javascript:void(0);" data-key="record_import_type">记录导入模式</a></li>
		</ul>
	</div>
	<div class="dataconfig-content"></div>
	<div class="cl"></div>
</div>
<div class="cl"></div></div>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/layer/layer.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/easyTemplate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.init.admin.js");?>"></script>
<script language="javascript" src="<?php echo U("static@javascript/jquery.zclip/jquery.zclip.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static/cache/dataconfig.js");?>"></script>
<script type="text/template" id="tpl_tabcontent">
<#macro form data>
<form class="t-ajax-form form" action="<?php echo U("/sys_manager/data_config");?>" method="POST" >
<div class="dis5"></div>
<button type="button" class="btn btn-danger btn-xs additem"><span class="glyphicon glyphicon-plus"></span> 添加</button>
<div class="dis10"></div>
<table class='table  table-condensed table-bordered' cellpadding="0" cellspacing="0" style='width:600px;'>
			 <thead>
			 <tr>
			 <th>名称</th>
			 <th>编码</th>
			 <th>操作</th>
			 </tr>
			 </thead>
		     <tbody id='item_box_${data.name}'>
		     <#list data.datalist as list>
			  <tr>
			  <td><input type="text" name="name[]" class="nhigh" value="${list.value}" /></td>
			  <td><input type="text" name="code[]" class="low" value="${list.key}" /></td>
			  <td>
			  <p class='table-item-op'>
			  <span class="glyphicon glyphicon-arrow-up"></span>
			  <span class="glyphicon glyphicon-arrow-down"></span>
			  <span class="glyphicon glyphicon-remove"></span>
			  </p>
			  </td>
			  </tr>
			  </#list>
			 </tbody>
</table>
<input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" />
<input name="item" value="${data.name}" type="hidden" />
<input name="do" value="edit" type="hidden" />
<button type="submit" class="btn btn-success" tabindex="4" data-loading-text="处理中……">完成修改</button>
</form>
</#macro>
</script>
<script type="text/template" id="tpl_tabcontent_item">
<#macro item mydata>
<#list mydata.datalist as list>
			  <tr>
			  <td><input type="text" name="name[]" class="nhigh" value="${list.value}" /></td>
			  <td><input type="text" name="code[]" class="low" value="${list.key}" /></td>
			  <td>
			  <p class='table-item-op'>
			  <span class="glyphicon glyphicon-arrow-up"></span>
			  <span class="glyphicon glyphicon-arrow-down"></span>
			  <span class="glyphicon glyphicon-remove"></span>
			  </p>
			  </td>
			  </tr>
</#list>
</#macro>
</script>
<script type='text/javascript'>
//按钮(点击绑定)
	function init_button(indexValue,idstr){
		//功能操作按钮
		$('.c_'+idstr+' tbody tr:eq('+indexValue+') .table-item-op .glyphicon').each(
			function(i){
				var tr_obj = $(this).parent().parent().parent();
				switch(i){
					//向上排序
					case 0:
					$(this).click(
						function(){
							var insertIndex = tr_obj.prev().index();
							if(insertIndex >= 0){
								$('.c_'+idstr+' tbody tr:eq('+insertIndex+')').before(tr_obj);
							}
						}
					)
					break;
					//向下排序
					case 1:
					$(this).click(
						function(){
							var insertIndex = tr_obj.next().index();
							$('.c_'+idstr+' tbody tr:eq('+insertIndex+')').after(tr_obj);
						}
					)
					break;
					//删除排序
					case 2:
					$(this).click(
						function(){
							tr_obj.remove();
						}
					)
					break;
				}
			}
		)
	}
  $(function(){
  	$('#myTab a').click(function (e) {
	  e.preventDefault();
	  var obj = this;
	  var is = parseInt($(obj).attr("loaded"));
	  var item = $(obj).attr("data-key").replace("#","");
	  if(is != 1){
	  	    $.ajaxPassport({
		  	    "url":("<?php echo U("/sys_manager/data_config?do=get&item=");?>"+item),
		  	    "success":function(res){
		  	    	            var tpl_s = $("#tpl_tabcontent").html();
					  	    	var tpl_v = {name:item,datalist:[]};
					  	    	if(res.data){
					  	    		for(var i in res.data){
					  	    			tpl_v.datalist.push({"key":i,"value":res.data[i]});
					  	    		}
					  	    	}
					  	    	var html = "" + easyTemplate(tpl_s,tpl_v);
					  	    	
					  	    	if(!$(".c_"+item).get(0)){
					  	    		$(".dataconfig-content").append("<div class='c c_"+item+"'></div>");
					  	    	}
					  	    	$(".c_"+item).html(html);
						  	    //初始按钮
						  	    $(".c_"+item).find("table tbody tr").each(
									function(i){
										init_button(i,item);
									}
								);
						  	    //添加按钮
						  	    $(".c_"+item).find("button.additem").click(function(){
						  	    	var tpl_s = $("#tpl_tabcontent_item").html();
					  	    		var tpl_v = {"datalist":[{"key":"","value":""}]};
					  	    		var size = $(".c_"+item).find("table tbody tr").size();
					  	    		$(".c_"+item).find("table tbody").append(""+easyTemplate(tpl_s,tpl_v));
					  	    		init_button(size,item);
						  	    });
						  	    //初始化表单
						  	   $(".c_"+item).find("form.t-ajax-form").each(function(i){
									$(this).submit(function(){
										return $.ajaxForm(this,i);
									});
								});
							  	$(obj).attr("loaded",1);
		  	    },
		  	    "loading_txt":"正在加载数据……"
	  	    });
	  }
	  $('#myTab a').removeClass("cur");
	  $(obj).addClass("cur");
	  
	  $(".dataconfig-content .c").hide();
	  $(".c_"+item).show();
	});
	$('#myTab a:first').click();
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