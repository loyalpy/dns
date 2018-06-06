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
		<?php if($this->check_purview("/domain_manager/records")){?>
		<li><a href="<?php echo U("/domain_manager/records");?>" <?php if($c != "lock"){?>class="cur"<?php }?>>域名解析记录</a></li>
		<?php }?>
		<?php if($this->check_purview("/domain_manager/domain_record_check")){?>
		<li><a href="<?php echo U("/domain_manager/records?c=lock");?>" <?php if($c=="lock"){?>class="cur"<?php }?>>URL审核</a></li>
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
	<?php if($this->check_purview("/domain_manager/domain_record_check_batch")){?>
  <div class="name">记录列表</div><button type="button" class="btn btn-sm btn-default batch-p" style="float: right;">批量审核</button>
	<?php }?>
  <div class="cl"></div>
</div>
<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/domain_manager/records?do=get_url");?>">
	<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
<div class="list-box">
<table class="list-table table table-striped table-condensed table-responsive table-checkall" cellpadding="0" cellspacing="0">
	<col width="50px"/>
	<col width="60px" />
	<col width="100px"/>
	<col width="140px"/>
	<col  />
	<col width="100px"/>
	<col width="80px"/>
	<col width="80px"/>
	<col width="150px"/>
	<thead>
	<tr>
	<th><input type="checkbox" data-name="records[]" class="checkall"/></th>
	<th>ID</th>
	<th>主机名</th>
	<th>域名</th>
	<th>记录值</th>
	<th>类型</th>
	<th>解析状态</th>
	<th>审核状态</th>
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
    <tr class="record_${data.record_id}">
    <td><input type="checkbox" name="records[]" value="${data.record_id}"/></td>
    <td class="font-gray"><span class="keybox">${data.record_id}</span></td>
    <td class="font-blue"><span class="keybox">${data.RRname}</span></td>
    <td class="font-blue">
		<a href="<?php echo U("/domain_manager/records?keyword=");?>${data.domain}"><span class="keybox">${data.domain}</span><br/>
		<span class="keybox font-gray"><#if ($.is_empty(acls[data.acl]))><a href="<?php echo U("domain_manager/domain_diyline?keyword=");?>${cust_line[data.acl.replace('cust','')].name}" class="font-gray">自定义线路:${cust_line[data.acl.replace('cust','')].name}</a><#else>${acls[data.acl].name}</#if></span></a>
	</td>
    <td class="font-green"><span class="keybox">${data.RRvalue}</span></td>
    <td><span class="keybox <#if (data.RRtype=='URLN' || data.RRtype=='URLY')>font-org</#if>">${RRtypeArr[data.RRtype]}</span></td>
		<td class="font-gray"><cite class="<#if (data.status==1)>glyphicon glyphicon-ok font-green<#else>glyphicon glyphicon-remove font-red</#if>" title="<#if (data.status==1)>已开启<#else>已暂停</#if>"></cite></td>

		<td>
		<#if (data.inlock == 0)>
			<span class="glyphicon glyphicon-ok font-green" data-toggle="tooltip" data-placement="right" title="审核通过"></span>
			<#else>
				<span class="glyphicon glyphicon-lock font-red" data-toggle="tooltip" data-placement="right" title="待审核"></span>
		</#if>
	</td>
    <td>
		<?php if($this->check_purview("/domain_manager/domain_record_check")){?>
		<button type="button" class="btn btn-default btn-sm btn-setting-check" data-url="<?php echo U("/domain_manager/domain_record_check?record_id=");?>${data.record_id}" data-record_id="${data.record_id}">记录操作</button>
		<?php }?>
	</td>
	</tr>
</#macro>
</script>


<script language="javascript">
	var acls = <?php echo JSON::encode(C("category","domain_acl")->json(0,'ident'));?>;
	var cust_line = <?php echo JSON::encode(M("@domain_acl_set")->get_cust_list());?>;
	var RRtypev = <?php echo JSON::encode($RRtype);?>;
	var RRtypeArr = <?php echo JSON::encode($RRtypeArr);?>;
	var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
	var hash = "<?php echo tUtil::hash();?>";
	var c = "<?php echo isset($c)?$c:"";?>";
	var search_formdata = {
		domain: {type: "uid", label: "域名", name: "domain", value: "<?php echo isset($condi['domain'])?$condi['domain']:"";?>", disabled: 0, data_sr:[], css: "", require: "", desc: "", item_css: ""},
		RRtype: {type: "select", label: "记录类型", name: "RRtype", value: "", disabled: 0, data_sr:RRtypev, css: "", require: "", desc: "", item_css: ""},
		keyword:{type:"text",label:"关键词",name:"keyword",value: "",data_sr:[],css:"",require:"",desc:"",item_css:""},
		c:{type:"hidden",label:"关键词",name:"c",value:c,data_sr:[],css:"",require:"",desc:"",item_css:""},
		btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
	};
	var loadlist = function(page){
		$.ajaxLoadlist(page,pageurl,function(){
			var keyword = $(".the_searchform input[name='keyword']").val();
			if(keyword != ""){
				$(".thelistform").find(".tpl .keybox").each(function(){
					var obj = this;
					var html = $(obj).html();
					$(obj).html($.replace_keyword(html,keyword))
				});
			};
			//URL审核
			$(".btn-setting-check").click(function(){
				var url = $(this).data("url");
				edit_setting_check_func(url);
			});
			$('[data-toggle="tooltip"]').tooltip();
		});
		//全选，全不选
		$(".table-checkall").find("input.checkall").unbind("click").bind("click",function(){
			$.check_all(this);
		});

		//批量审核
		var ids= new Array();
		$(".batch-p").unbind("click").bind("click",function(){
			var ids_tmp  = $.fetch_ids("records[]");
			ids = ids_tmp.split(",");
			if (ids == "") {
				$.ui.error('请选择要批量审核的域名记录！')
				return;
			}
			$.ui.confirm(function(){
				batch_s_domain_op(0);
			},"你确定要批量审核域名记录吗？") ;
		});
		var num = 0;
		var batch_s_domain_op = function(i){
			var id=ids[i];
			if( i >= ids.length || typeof ids[i] == "undefined"){
				num = 0;
				return false;
			}
			$.ui.loading();
			$.ajaxPassport({
				url: "<?php echo U("/domain_manager/domain_record_check");?>",
				type:"POST",
				success: function (res) {
					if (res.error == 0) {
						num++;
						var domainId = ".record_"+ids[i];
						setTimeout(function () {
							$(domainId).remove();
						}, 50);
					}
					//执行最后一次时提示操作结果
					if (i == (ids.length - 1)) {
						if (num >0) {
							$.ui.close_loading();
							$.tips("成功审核"+num+"个域名记录","success");
							//如果是删除,重新加载
							loadlist();
						}else{
							$.ui.close_loading();
							$.tips("批量审核域名失败","error");
						}
					}
					batch_s_domain_op(i+1);
				},
				data: {record_id:id,send_email:1,inlock:1,hash:hash},
			});
		}

	}
	$(function(){
		//加载搜索
		$.loadform(search_formdata,"",function(res){
			pageurl = res.pageurl;
			loadlist();
			return true;
		},function(){
			get_domainlist(1,'',"",".the_searchform");
		},".the_searchform");
		//加载列表
		loadlist();
	})
</script>
<!-- URL审核 start-->
<script type="text/template" id="tpl-setting-group">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class="form-content">
			<form action="<?php echo U("/domain_manager/domain_record_check");?>" method="post" class="theform form form-2" role="form">
				<div class="tpl"></div>
			</form>
		</div>
	</#macro>
</script>
<script language="javascript">
	var setting_group_formdata = {
		domain:{type:"ivalue",label:"操作域名",name:"domain",value:'',data_sr:[],css:"font-gray",require:"",desc:"",item_css:""},
		inlock:{type:"select",label:"审核操作",name:"inlock",value:"1",data_sr:[{v: "审核通过", key: "1"}, {v: "取消审核", key: "2"}],css:"font-gray",require:"datatype='*'",desc:"",item_css:""},
		record_id:{type:"hidden",label:"",name:"record_id",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
		btn:{type:"button",label:"",value:"提交审核",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
	};
	var edit_setting_check_func = function(url){
		var edit_c = $("#tpl-setting-group").html();
		edit_c = "" + easyTemplate(edit_c,{title:"审核记录"});
		$("#myModal").find(".modal-dialog").width(500);
		$("#myModal").find(".modal-content").html(edit_c);
		$.loadform(setting_group_formdata,url,function(res){
			loadlist();
			return true;
		},".theform");
		$('#myModal').modal();
	}
</script>
<!-- URL审核 end-->
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