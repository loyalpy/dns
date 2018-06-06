<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">
	<title><?php echo isset($site_config['seo_title'])?$site_config['seo_title']:"";?></title>
	<meta name="keywords" content="<?php echo isset($site_config['seo_keyword'])?$site_config['seo_keyword']:"";?>" />
	<meta name="description" content="<?php echo isset($site_config['seo_description'])?$site_config['seo_description']:"";?>" />
	    <link href="<?php echo U("static/javascript/bootstrap/css/bootstrap.min.css");?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo U("static@/css/style_admin_new.css");?>"  rel="stylesheet" type="text/css" />
    
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
<!--header-->
<div class="header">
  <div class="in-header" id="InHeader">
  <!--logo-->
  <div class="logo">
  <a href="<?php echo U("/");?>" title="">八戒DNS</a>
  <span class="v"></span>
  </div>
  <!--end logo-->
  <!--icon nav-->
  <div class="icon-nav">
     <ul>
     <?php if($this->check_purview('/rc_manager/tuijob')){?>
     <li class="order"><cite></cite><a href="<?php echo U("/rc_manager/tuijob");?>" title="">域名管理</a>
     <span title="求职招聘" inover="0" class="notify user_notify_ordersh" style="display:none;"><font>0</font>
     	<div class="notify_detail" style="display:none;"></div>
     </span>
     </li>
     <?php }?>
     <?php if($this->check_purview('/order_manager/orderserver')){?>
     <li class="server" style="display:none;"><cite></cite><a href="<?php echo U("/order_manager/orderserver");?>" title="">职介联盟</a>
     <span title="职介联盟" inover="0"  class="notify user_notify_orderserver_logout" style="display:none;"><font>0</font>
     	<div class="notify_detail" style="display:none;"></div>
     </span>
     </li>
     <?php }?>
     <?php if($this->check_purview('/order_manager/recharge')){?>
     <li class="finance"><cite></cite><a href="<?php echo U("/order_manager/recharge");?>" title="">充值管理</a>
     <span title="订单管理"  inover="0" class="notify user_notify_finance_in" style="display:none;"><font>0</font>
     	<div class="notify_detail" style="display:none;"></div>
     </span>
     </li>
     <?php }?>
     <?php if($this->check_purview('/user_manager/userlist')){?>
     <li class="message"><cite></cite><a href="<?php echo U("/user_manager/userlist");?>" title="">会员管理</a>
     <span title="会员管理" href="<?php echo U("/ucenter/notify");?>" class="notify sys_notify_count" style="display:none;"><font>0</font></span>
     </li>
     <?php }?>
     </ul>
  </div>
  <!--end icon nav-->
  <!--right nav-->
  <div class="right-nav">
     <ul>
     <li class="setting">
     <div class="btn-group">
	  <button type="button" data-toggle="dropdown"></button>
	  <ul class="dropdown-menu top-setting-menu" role="menu">
	   <li><a href="<?php echo U("account@/ucenter/profile_basic");?>">个人资料修改</a></li>
       <li><a href="<?php echo U("account@/ucenter/profile_avatar");?>">个人头像修改</a></li>
       <li><a href="<?php echo U("account@/ucenter/profile_passwd");?>">修改密码</a></li>
       <li class="divider"></li>
       <?php if($this->userinfo['urole'] == 1){?>
		 <li><a href="/sys_manager/module_config">模块配置</a></li>
		 <?php }?>
       <li><a href="<?php echo U("account@/login/logout");?>">退出</a></li>
	  </ul>
	 </div>
     </li>
     <li class="avatar"><img class="myavatar" src="<?php echo tFun::avatar($uid);?>" width="30" height="30" />
     <span class="mask-30"></span>
     </li>
     <li class="name"><?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?></li> 
     </ul>
  </div>
  <!--right nav-->
  <div class="cl"></div>
  </div>
</div>
<!--end header-->
<!--main content -->
<div class="main-content">
 <div class="in-c">
<?php if(isset($this->nav['nav'][$this->nav['top']])){?>
<div class="menu">
  <ul>
  <?php foreach($this->nav['nav'][$this->nav['top']]['childrens'] as $key => $item){?>
  <?php if($this->check_purview($item['url']) && $item['status'] == 1){?>
  <?php if(count($this->nav['nav'][$this->nav['top']]['childrens']) < 4){?>
  <li><a href="<?php echo isset($item['url'])?$item['url']:"";?>" class="<?php if($this->nav['sub'] == $key){?>cur<?php }?>"><?php echo isset($item['name'])?$item['name']:"";?></a></li>
  <?php }else{?>
     <?php if($this->nav['sub'] == $key){?>
     <li><a href="<?php echo isset($item['url'])?$item['url']:"";?>" class="<?php if($this->nav['sub'] == $key){?>cur<?php }?>"><?php echo isset($item['name'])?$item['name']:"";?></a></li>
     <?php }?>
  <?php }?>
  <?php }?>
  <?php }?>
  </ul>
</div>
<?php }?>
  
<div class="main-nav">
  <div class="name">岗位列表</div>
  <div class="navbtn0"></div>
  <div class="navbtn">
   <!--button-->
  <button type="button" class="btn btn-default btn-sm refresh-btn" data-loading-text="处理中……">&nbsp;<cite class="glyphicon glyphicon-refresh"></cite>&nbsp;</button> 
  <!--end button-->
  <!--button-->
 
  <!--end button-->
  </div>
  <div class="cl"></div>
</div>

<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/rc_manager/tuijob?do=get_url");?>">
<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
<div class="list-box">
<table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
	<col width="30px" />
	<col />
	<col width="150px" />
	<col width="150px" />
	<col width="130px" />
	<col width="90px" />
	<col width="90px" />
	<col width="130px" />
	<col width="80px" />	
	<col width="100px" />
	<thead>
	<tr>
	<th><input type="checkbox" class="chkall" data-name="ids[]"  /></th>
	<th>岗位名称</th>
	<th>岗位详情</th>
	<th>地区</th>
	<th>悬赏金额</th>
	<th>账户余额</th>
	<th>收/邀/查</th>
	<th>刷新时间</th>
	<th>状态</th>
	<th>操作</th>
	</tr>
	</thead>
	<tbody class="tpl"></tbody>
	<tr>
	<th><input type="checkbox" class="chkall" data-name="ids[]" /></th>
	<th colspan="9">
	<button type="button" class="btn btn-default btn-xs batch-operation" data-url="<?php echo U("/rc_manager/joblist_refresh");?>" data-type="job_id">刷新</button>
	<button type="button" class="btn btn-default btn-xs batch-operation" data-url="<?php echo U("/rc_manager/tuijob_status?status=0");?>">待审</button>
	<button type="button" class="btn btn-default btn-xs batch-operation" data-url="<?php echo U("/rc_manager/tuijob_status?status=1");?>">暂停</button>
	<button type="button" class="btn btn-default btn-xs batch-operation" data-url="<?php echo U("/rc_manager/tuijob_status?status=2");?>">正常</button>
	<button type="button" class="btn btn-default btn-xs batch-operation" data-url="<?php echo U("/rc_manager/tuijob_status?status=3");?>">结束</button>&nbsp;&nbsp;
	<button type="button" class="btn btn-default btn-xs batch-operation" data-url="<?php echo U("/rc_manager/tuijob_del?indel=1");?>">删除</button>
	<button type="button" class="btn btn-default btn-xs batch-operation" data-url="<?php echo U("/rc_manager/tuijob_del?indel=0");?>">恢复</button>
	</th>
	</tr>
</table>
</div>
<div class="pagebar"></div>
</form>
<!-- end list box -->
 </div>
 <!--utype1-->
 <div class="left-nav" id="LeftBOX">
   <div class="city-select">
      <span><?php echo isset($this->userinfo['post_name'])?$this->userinfo['post_name']:"";?></span>
      <a href="javascript:void(0);" class="rl name"><?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?></a>
   </div>
   <!--nav-->
   <div class="nav" id="Lnav">
      <?php foreach($this->nav['nav'] as $key => $item){?>
        <?php if(in_array($key,array("sys_manager")) && $this->userinfo['urole']<=0){?>
        <?php }else{?>
	        <?php if($item['status'] == 1 && $this->check_purview($item['url'])){?>
	        <div class="item">
	        <div class="h <?php if($this->nav['top'] == $key){?>h-active<?php }?>"><span><font class="glyphicon glyphicon-<?php echo isset($item['enname'])?$item['enname']:"";?>"></font> &nbsp;<i><?php echo isset($item['name'])?$item['name']:"";?></i></span></div>
	        <div class="b" <?php if($this->nav['top'] == $key){?><?php }else{?>style="display:none;"<?php }?>>
	          <ul>
	          <?php foreach($item['childrens'] as $key2 => $sub){?>
		      <?php if($sub['status'] == 1 && $this->check_purview($sub['url'])){?>
	          <li class="<?php if($this->nav['sub'] == $key2){?>c<?php }?>"><a href="<?php echo isset($sub['url'])?$sub['url']:"";?>"><cite class="glyphicon glyphicon-<?php echo isset($sub['enname'])?$sub['enname']:"";?>"></cite> &nbsp;<?php echo isset($sub['name'])?$sub['name']:"";?> </a></li>
	          <?php }?>
		      <?php }?>
	          </ul>
	        </div>
	        </div>
	        <?php }?>
	    <?php }?>
	    <?php }?>
    </div>
   <!--end nav-->
 </div>
 <!--end utype1-->
</div>
<!--end main content--> 
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/layer/layer.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/easyTemplate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.init.admin.js");?>"></script>
<script language="javascript" src="<?php echo U("static@javascript/jquery.zclip/jquery.zclip.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<script language="javascript">
var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
</script>
<?php echo $this->fetch('tpl/form')?>
<script type="text/template" id="tpl_list_row">
<#macro row data>
    <tr>
    <td><input value="${data.tui_id}" data-job_id="${data.job_id}" type="checkbox" name="ids[]" /></td>
    <td class="" title=">">
    <#if (data.indel == 1)><strike></#if>
    <a href="<?php echo U("home@/");?>job/view/${data.tui_id_str}.html" target="_blank" class="font-blue">${data.tui_name}</a>
<font class="font-gray">(<#if (data.sex == 1)>男<#elseif (data.sex == 2)>女<#else>性别不限</#if>,<#if (!$.is_empty(data.age_max) && !$.is_empty(data.age_max))>${data.age_min}-${data.age_max}岁<#else>年龄不限</#if>)</font>
<br/>
${data.company_name} 
<#if (data.indel == 1)></strike><font class="font-red">已删除</font></#if>
 </td>
    
    <td>
<#if ($.is_empty(data.job_age))>不限<#else>${$.get_dataconfig('job_age.'+data.job_age)}</#if>,
<#if ($.is_empty(data.job_edu))>不限<#else>${$.get_dataconfig('job_edu.'+data.job_edu)}</#if>,
<#if ($.is_empty(data.job_salary))>面议<#else>${$.get_dataconfig('job_salary.'+data.job_salary)}</#if>
<br/>
${$.get_cate(data.job_area,"city")}
</td>
    <td class="f12">
    <font class="font-blue">悬赏 ${data.tui_num}人</font><br/>
<font class="font-gray">预计面试 ${data.plan_invites}人</font>
    </td>
<td class="font-gray">
<font class="font-org f14">${data.amount}</font><font class="font-gray f12">元/人</font><br/>
<font class="font-gray">信息费 ${data.wc_amount}元</font><br/>
<font class="font-gray">余额至少 ${$.to_float2(data.wc_amount*data.plan_invites + (data.amount-data.wc_amount))}元</font>
</td>
<td><font class="font-org f14">${data.balance}元</font></td>
    <td>
    <font class='font-org'>${data.applys}</font>/<font class='font-org'>${data.invites}</font>/${data.views}
	</td>
    <td>${$.time_to_string(data.startdate)}<br/>
${$.time_to_string(data.enddate)}<br/>
${$.time_to_string(data.lastupdate)}</td>
    <td class="font-gray">
    <#if (data.status == 0)><font class="font-red">待审</font>
    <#elseif (data.status == 1)><font class="font-gray">已暂停</font>
    <#elseif (data.status == 2)><font class="font-green">正常</font>
	<#elseif (data.status == 3)><font class="font-gray2">已结束</font>
    </#if>
	</td>
    <td class="font-gray">
    <p class="table-item-op">
	<button type="button" class="btn btn-success btn-xs t-ajax-button" data-url="<?php echo U("/rc_manager/joblist_refresh?ids=");?>${data.job_id}">刷新</button> 
	<#if (data.status == 0)><button type="button" class="btn btn-default btn-xs t-ajax-button" data-url="<?php echo U("/rc_manager/tuijob_status?status=2&ids=");?>${data.tui_id}">审核</button> 
    <#elseif (data.status == 1)><button type="button" class="btn btn-default btn-xs t-ajax-button" data-url="<?php echo U("/rc_manager/tuijob_status?status=2&ids=");?>${data.tui_id}">发布</button> 
    <#elseif (data.status == 2)><button type="button" class="btn btn-default btn-xs t-ajax-button" data-url="<?php echo U("/rc_manager/tuijob_status?status=1&ids=");?>${data.tui_id}">暂停</button> 
    </#if>	
	</p>
    </td>
	</tr>
</#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<script language="javascript">
var initpage = 1;
var search_formdata = {
	startdate:{type:"date",label:"时间",name:"startdate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:""},
	enddate:{type:"date",label:"-",name:"enddate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:"date-dis"},
    keyword:{type:"text",label:"UID/关键词",name:"keyword",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
    company:{type:"hidden",name:"company",value:"<?php echo isset($condi['company'])?$condi['company']:"";?>"},
	btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
};
var loadlist = function(page){
	initpage = page;
	$.ajaxLoadlist(page,pageurl,function(){
		var keyword = $(".the_searchform input[name='keyword']").val();
		if(keyword != ""){
			var listhtml = $(".thelistform").find(".tpl").html();
			$(".thelistform").find(".tpl").html($.replace_keyword(listhtml,keyword));
		};
		$(".editbtn").click(function(){
			edit_userinfo(this);
		 });
		$(".t-ajax-button").each(function(i){
			$.t_ajax_button(this);
		});
		$(".btn-setting").click(function(){
			edit_user_setting_func(this);
		});
		$(".table-item-op-recharge").click(function(){
			edit_user_balance_point(this);
		});
		$(".table-item-add-recharge").click(function(){
			edit_user_recharge(this);
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
	 //加载列表
	 loadlist(1);
     //增加按钮
	 $(".addbtn").click(function(){
		 edit_userinfo(this);
	 });
	 //刷新按钮
	 $(".refresh-btn").click(function(){
	 	 loadlist(1);
	 });	 
	 $(".date-ymd").datetimepicker({
		language:  'zh-CN',
		autoclose: 1,
		startView: 2,
		minView: 2,
		maxView: 4,
		format:"yyyy-mm-dd",
		pickerPosition: "bottom-right"   
	});
	 
	 
	//批量查看
	$.check_all("thelistform");
	$("button.batch-operation").unbind("click").bind("click",function(){
			var obj = this;
			var ids = $.fetch_ids("thelistform",$(obj).attr("data-type"));
			var url = $(obj).attr("data-url");			
			if(ids == ""){
				$.ui.alert("请先选择操作项",3);
				return false;
			}else{
				$.ui.confirm(function(){
					$.ajaxPassport({
						url:url,
						data:{ids:ids},
						success:function(res){
							if(res.error){
								$.ui.alert(res.message);
							}else{
								if(typeof res.data != "undefined"){
									if(res.data > 0){
										$.ui.alert("操作影响记录 <b class='font-red'> "+res.data+" </b>行");
										loadlist(initpage);
									}else{
										$.ui.alert("操作影响记录 <b class='font-red'> 0 </b>行")
									}
								}else{
									$.ui.alert(res.message);
									loadlist(initpage);
								}								
							}							
						}
					});
				},"操作执行要一点时间,请耐心等待,")
			}
	});
})
</script>
<script language="javascript">
 var header_height = 55;
 $("#btnCloseHeader").click(function(){
 	if($(this).hasClass("hid_up")){
 		$(this).removeClass("hid_up").addClass("hid_down");
 		$("#InHeader").hide();
 		$("body").css({"padding-top":0});
 		$("#LeftBOX").css({"top":0});
 	}else{
 		$(this).removeClass("hid_down").addClass("hid_up");
 		$("#InHeader").show();
 		$("body").css({"padding-top":header_height+"px"});
 		$("#LeftBOX").css({"top":header_height+"px"});
 	}
 });
 $(function(){
	   laydate.skin('molv');
   	   /* 菜单 */
   	   $("#Lnav .item .h").click(function(){
   	   	  $("#Lnav .item .h").removeClass("h-active");
   	   	  $("#Lnav .item .b").slideUp();
   	   	  
   	   	  $(this.parentNode).find(".b").slideDown();
   	   	  $(this.parentNode).find(".h").addClass("h-active");
   	   });
   	   /* 最新信息通知 */
   	   var sys_get_nodify = function(){
   	   	$.ajaxPassport({
   	   		url:U('/user_manager/user_notify?do=count'),
   	   		success:function(res){
   	   			if(res){
   	   				for(var i in res){
   	   					var count = parseInt(res[i]);
   	   				    $(".user_notify_"+i).attr("data-item",i);
   	   					if(count > 0){
   	   						var inover = $(".user_notify_"+i).attr("inover");
   	   						$(".user_notify_"+i).attr("inover",1).show().find("font").html(count);
   	   						if(parseInt(inover) == 0){
   	   							$(".user_notify_"+i).hover(function(){
   	   	   							var obj = this;
   	   	   							var item = $(obj).attr("data-item");
   	   	   							var isload  = $(obj).attr("data-isload");   	   	   							
   	   	   							if(isload == 1){
   	   	   								$(obj).find(".notify_detail").show();
   	   	   							}else{
		   	   	   						$(obj).find(".notify_detail").html("<div class='tc' style='padding-top:60px;'><img src='"+U('/common/images/loading2.gif')+"' /></div>").show();
		   	   					 		$.ajaxPassport({
		   	   			   	   				url:U('/user_manager/user_notify?do=detail&go='+item),
		   	   			   	   			    success:function(xres){
		   	   			   	   					if(xres.error == 0){	   	   			   	   			
		   	   			   	   						$(obj).find(".notify_detail").html(xres.message);
		   	   			   	   						$(obj).attr("data-isload",1);
		   	   			   	   					}
		   	   			   	   				}
		   	   			   	   			});   	   	   								
   	   	   							}
   		   						    
   	   	   						},function(){
   	   	   							var obj = this;
   		   							$(obj).find(".notify_detail").hide();
   	   	   						});
   	   							$(".user_notify_"+i).find("*").bind("mouseover",function(){
   	   								e.stopPropagation();
   	   							})
   	   						}
   	   						
   	   					}else{
   	   						$(".user_notify_"+i).html(0).hide();
   	   					}
   	   					
   	   				}
   	   			}
   	   		}
   	   	});
	};
	//var inter_get_sysnotify = window.setInterval(sys_get_nodify, 10000);
	//sys_get_nodify();
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