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
		<?php if($this->check_purview("/domain_monitor/monitor")){?>
		<li><a href="<?php echo U("/domain_monitor/monitor");?>" class="cur">监控域名</a></li>
		<?php }?>
		<?php if($this->check_purview("/domain_monitor/monitor_record")){?>
		<li><a href="<?php echo U("/domain_monitor/monitor_record");?>">域名监控线路队列</a></li>
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
  <div class="name">监控域名列表</div>
  <div class="cl"></div>
</div>
<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/domain_monitor/monitor?do=get_url");?>">
	<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
<div class="list-box">
<table class="list-table table table-striped table-condensed table-responsive table-checkall" cellpadding="0" cellspacing="0">
	<col width="50px"/>
	<col />
	<col width="80px"/>
	<col width="100px"/>
	<col width="110px"/>
	<col width="150px"/>
	<col width="80px"/>
	<col width="150px"/>
	<thead>
	<tr>
	<th><input type="checkbox" data-name="records[]" class="checkall"/></th>
	<th>监控主机</th>
	<th>监控记录</th>
	<th>监控节点</th>
	<th>监控频率</th>
	<th>时间</th>
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


<script type="text/template" id="tpl_list_row">
<#macro row data>
    <tr>
    <td><input type="checkbox" name="records[]" value=""/></td>
    <td class="font-blue"><a href="<?php echo U("/domain_monitor/monitor_record?keyword=");?>${data.domain}"><span class="keybox">${data.RRname}.${data.domain}:${data.monitor_port}</span></a></td>
    <td class="font-blue"><span class="keybox font-green">${data.monitor_item.length}条</span></a></td>
    <td class="font-blue"><span class="keybox font-gray">${data.monitor_node}</span></a></td>
    <td class="font-gray"><span class="keybox">${data.monitor_rate}分钟/次</span></td>
    <td>${data.dateline}</td>
    <td><#if (data.status==0)><span class="font-green">监控中</span><#else><span class="font-red">已暂停</span></#if></td>
    <td>
		<p class="table-item-op">
			<?php if($this->check_purview("/domain_monitor/monitor_record")){?>
			<a href="<?php echo U("/domain_monitor/monitor_record?keyword=");?>${data.domain}"  title="查看解析记录"><span class="glyphicon glyphicon-eye-open"></span></a>&nbsp;
			<?php }?>
			<?php if($this->check_purview("/domain_monitor/monitor_edit")){?>
			<a href="javascript:void(0);" class="editbtn"  data-url="<?php echo U("/domain_monitor/monitor_edit?monitor_id=");?>${data.monitor_id}" title="更改"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;
			<?php }?>
			<?php if($this->check_purview("/domain_monitor/monitor_del")){?>
			<a href="javascript:void(0);" class="delbtn" confirm="1" data-url="<?php echo U("/domain_monitor/monitor_del?monitor_id=");?>${data.monitor_id}" title="删除"><span class="glyphicon glyphicon-remove"></span></a>&nbsp;
			<?php }?>
		</p>
	</td>
	</tr>
</#macro>
</script>


<script language="javascript">
	var nodeArr = <?php echo JSON::encode($nodeArr);?>;
	var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
	var search_formdata = {
		node: {type: "select", label: "监控节点", name: "node", value: "", disabled: 0, data_sr:nodeArr, css: "", require: "", desc: "", item_css: ""},
		keyword:{type:"text",label:"关键词",name:"keyword",value: "",data_sr:[],css:"",require:"",desc:"",item_css:""},
		btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
	};
	var loadlist = function(page){
		$.ajaxLoadlist(page,pageurl,function(res){
			var keyword = $(".the_searchform input[name='keyword']").val();
			if(keyword != ""){
				$(".thelistform").find(".tpl .keybox").each(function(){
					var obj = this;
					var html = $(obj).html();
					$(obj).html($.replace_keyword(html,keyword))
				});
			};
			//删除域名监控
			$(".table-item-op").find("a.delbtn").click(function () {
				var url = $(this).attr("data-url");
				if(confirm("你确定要删除该监控吗？")) {
					$.ajaxPassport({
						url: url,
						success: function (res) {
							if (res.error == 1) {
								$.tips("删除失败","error");
							} else {
								$.tips("删除成功","success");
								loadlist();
							}
						},
					});
				}
			});
			//修改域名监控
			$(".table-item-op").find("a.editbtn").click(function () {
				var url = $(this).attr("data-url");
				edit_func(url);
			});
		});
		//全选，全不选
		$(".table-checkall").find("input.checkall").unbind("click").bind("click",function(){
			$.check_all(this);
		});
	}


	$(function(){
		//加载搜索
		$.loadform(search_formdata,"",function(res){
			pageurl = res.pageurl;
			loadlist();
			return true;
		},'',".the_searchform");
		//加载列表
		loadlist();
	})
</script>
<!--修改域名监控 start-->
<script type="text/template" id="tpl_domain_edit">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class="form-content">
			<form action="<?php echo U("/domain_monitor/monitor_edit");?>" method="post" class="theform form form-2" role="form">
				<div class="tpl"></div>
			</form>
		</div>
	</#macro>
</script>
<script language="javascript">
	var edit_domain_formdata = {
		domain:{type:"ivalue",label:"域名",name:"domain",value:"",data_sr:[],css:"font-gray",require:"",desc:"(默认不可修改)",item_css:"",disabled:1},
		monitor_rate:{type:"text",label:"监控频率",name:"monitor_rate",value:"",data_sr:[],css:"",require:"",desc:"分钟/次",item_css:""},
		monitor_port:{type:"text",label:"监控端口",name:"monitor_port",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
		monitor_id:{type:"hidden",label:"",name:"monitor_id",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
		btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
	};
	var edit_func = function(url){
		var edit_c = $("#tpl_domain_edit").html();
		edit_c = "" + easyTemplate(edit_c,{title:"修改域名监控"});
		$("#myModal").find(".modal-dialog").width(650);
		$("#myModal").find(".modal-content").html(edit_c);
		$.loadform(edit_domain_formdata,url,function(res){
			if(res.error == 0){
				$.tips(res.message,"success");
				$('#myModal').modal("hide");
				setTimeout(function(){
					loadlist();
				},500);
			}else{
				$.tips(res.message,"error");
			}
			return false;
		});
		$('#myModal').modal();
	}
</script>
<!--修改域名监控 end-->
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