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
  <div class="name">解析订单</div>
  <div class="navbtn0">
	  <a class="btn <?php if($ftype == 1){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm"  href="<?php echo U("/order_manager/parser?ftype=1");?>"><cite class="glyphicon glyphicon-th"></cite>已支付</a>
	  <a class="btn <?php if($ftype == 2){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm"  href="<?php echo U("/order_manager/parser?ftype=2");?>"><cite class="glyphicon glyphicon-th"></cite>待支付</a>
	  <a class="btn <?php if($ftype == 3){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm"  href="<?php echo U("/order_manager/parser?ftype=3");?>"><cite class="glyphicon glyphicon-th"></cite>已取消</a>
	  <a class="btn <?php if($ftype == 4){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm"  href="<?php echo U("/order_manager/parser?ftype=4");?>"><cite class="glyphicon glyphicon-th"></cite>已删除</a>
  </div>
  <div class="navbtn">
  </div>
  <div class="cl"></div>
</div>

<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/order_manager/parser?do=get_url");?>">
<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
<div class="list-box">
<table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
	<col width="150px" />
	<col width="150px" />
	<col width="90px" />
	<col width="140px" />
	<col />
	<thead>
	<tr class="active">
		<th>订单编号&nbsp;<a href="javascript:void(0)" class="orderby" data-item="order_no" data-desc=""><cite></cite></a></th>
		<th>用户&nbsp;<a href="javascript:void(0)" class="orderby" data-item="uid" data-desc=""><cite></cite></a></th>
		<th>订单总金额&nbsp;<a href="javascript:void(0)" class="orderby" data-item="amount" data-desc=""><cite></cite></a></th>
		<th>创建时间&nbsp;<a href="javascript:void(0)" class="orderby" data-item="dateline" data-desc=""><cite></cite></a></th>
		<th>订单详情</th>
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
<#macro row order>
	<tr>
		<td>${order.order_no}</td>
		<td class="font-blue">${order.uid}</td>
		<td class="font-org">${order.amount}元</td>
		<td class="font-gray">${order.dateline}</td>
		<td>
				<table class="list-table table table-striped table-condensed table-responsive table-bordered" cellpadding="0" cellspacing="0">
					<col  />
					<col width="60px" />
					<col width="80px" />
					<col width="100px" />
					<col width="100px" />
					<col width="100px"/>
					<tbody class="tpl">
					<#list order.order_item as order_item>
					<tr style="text-align: center">
						<td>
							<#if (order_item.goods_name)>
								${order_item.goods_name}
								<#else>
									${order_item.goods_no}
							</#if>
						</td>
						<td><#if (order_item.buy_type == 1)><span class="font-green">新买</span><#else><span class="font-org">续费</span></#if></td>
						<td>
							<#if (order_item.type != 0)>短信套餐</#if>
							<#if (order_item.goods_name)> ${service_group[order_item.goods_no].name}</#if>
						</td>
						<td><#if (order_item.goods_name)><#if (order_item.num < 10 )>${order_item.num}个月<#else>${order_item.num/10}年</#if><#else>${order_item.num}</#if></td>
						<td ><font color="<#if (order.status != 4)> red <#else> #F37B1D </#if>">${order_item.amount}元</font></td>
						<td class="status">
							<#if (order.status >= 4)>
								<#if (order.pay_status == 2)>
									<font color="red">已退款</font>
									<#else>
										<font color="green">已完成</font>
								</#if>
								<#elseif (order.status == 3)>
									<font color="green">已支付</font>
									<#elseif (order.status == 0)>
										已取消
										<#else>
											<font color="red">待付款</font>
							</#if>
						</td>
					</tr>
					</#list>
					</tbody>
				</table>
		</td>
	</tr>
</#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js");?>"></script>
<script language="javascript">
var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
var service_group = <?php echo JSON::encode(M("@domain_service")->get_cache_list(0));?>;
var search_formdata = {
	startdate:{type:"date",label:"时间",name:"startdate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:""},
	enddate:{type:"date",label:"-",name:"enddate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:"date-dis"},
	keyword:{type:"text",label:"关键词",name:"keyword",value:"",data_sr:[],css:"",require:"",desc:"",item_css:"item-200"},
	btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
};
var loadlist = function(page,orderby){
	$.ajaxLoadlist(page,pageurl+ (typeof orderby == "undefined" ? "" : ("&orderby=" + orderby)),function(res){
		var keyword = $(".the_searchform input[name='keyword']").val();
		if(keyword != ""){
			var listhtml = $(".thelistform").find(".tpl").html();
			$(".thelistform").find(".tpl").html($.replace_keyword(listhtml,keyword));
		};
		$(".editbtn").click(function(){
			edit_func(this);
		 });
		$(".t-ajax-button").each(function(i){
			$.t_ajax_button(this);
		});
		//排序
		var orderby_arr = res.orderby.split("!");
		$(".thelistform").find("a.orderby").each(function () {
			var obj = this;
			var or_item = $(obj).attr("data-item");
			var or_v = "ASC";
			$(obj).attr("data-desc", or_v);
			$(obj).find("cite").attr("class", "glyphicon glyphicon-chevron-up");
			if (or_item == orderby_arr[0]) {
				or_v = orderby_arr[1];
				$(obj).attr("data-desc", or_v);
				if (or_v == "DESC") {
					$(obj).find("cite").attr("class", "glyphicon glyphicon-chevron-down");
				}
			}
			$(obj).unbind("click").bind("click", function () {
				loadlist(1, (or_item + "!" + (or_v == "ASC" ? "DESC" : "ASC")));
			});
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