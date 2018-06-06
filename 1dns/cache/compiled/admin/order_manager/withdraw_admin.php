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
    
<link href="<?php echo U("static/javascript/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css");?>" type="text/css" rel="stylesheet">
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
  <div class="name">在线提现</div>
  <div class="navbtn0">
  <?php $status_s = array("ing"=>"待审核","fail"=>"失败","concel"=>"已撤销","success"=>"成功")?>
  <a class="btn <?php if($t == 'all'){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="" href="<?php echo U("/order_manager/withdraw?t=all");?>"><cite class="glyphicon glyphicon-th"></cite> 所有</a> 
  <?php foreach($status_s as $key => $item){?>
  <a class="btn <?php if($t == $key){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="<?php echo isset($item)?$item:"";?>" href="<?php echo U("/order_manager/withdraw?t=$key");?>"><cite class="glyphicon glyphicon-th"></cite> <?php echo isset($item)?$item:"";?></a> 
  <?php }?>
  </div>
  <div class="navbtn">
  <!--button-->
  <!--end button-->
  </div>
  <div class="cl"></div>
</div>
<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/order_manager/withdraw?t=$t");?>">
<div class="tpl">
<!--form start-->
<div class="form-item form-item-date ">
	<label>时间：</label> 
	<div class="item-v">
	<input class="nhigh date-ymd shigh" value="<?php echo isset($startdate)?$startdate:"";?>" readOnly type="text" name="startdate" /> <cite class="glyphicon glyphicon-calendar"></cite>
	</div><div class="cl"></div>
</div>
<!--form end-->
<!--form start-->
<div class="form-item form-item-date ">
	<label>-</label> 
	<div class="item-v">
	<input class="nhigh date-ymd shigh" value="<?php echo isset($enddate)?$enddate:"";?>" readOnly type="text" name="enddate" /> <cite class="glyphicon glyphicon-calendar"></cite>
	</div><div class="cl"></div>
</div>
<!--form end-->
<!--form start-->
<div class="form-item form-item-text "><label>UID/关键词：</label>
	<div class="item-v"><input class="nhigh" value="<?php echo isset($keyword)?$keyword:"";?>" type="text" name="keyword" /></div>
	<div class="cl"></div>
</div>
<!--form end-->
<!--form start-->
<div class="form-item form-item-button undefined"><label></label>
	<div class="item-v"><input value="<?php echo tUtil::hash();?>" type="hidden" name="hash"><button class="btn btn-success btn-sm" type="submit">搜索</button></div>
	<div class="cl"></div>
</div>
<!--form end-->
<div class=cl></div>
</div>
</form>
<!--end search box -->
<form action="" class="thelistform">
<div class="list-box">
<table class='table table-bordered table-responsive' cellpadding="0" cellspacing="0">
		        <col width="90px" />
				<col width="152px" />
					<col width="190px" />
					<col />
					<col width="200px" />
					<col width="110px" />
				<thead>
					<tr class="active">
					<th>申请人</th>
					<th class="tl">申请时间</th>
					<th class="tl">提现编号</th>
					<th class="tl">银行流水单号</th>
					<th class="tl">结算金额</th>
					<th class="tl">结算状态</th>
					</tr>
				</thead>
		<tbody>
		<?php foreach($this->datalist as $key => $item){?>
				<?php $u = C("user")->get_cache_userinfo($item['uid']);?>
				    <tr class="list-item" id="list_<?php echo isset($item['id'])?$item['id']:"";?>">
					<td><span class="font-blue"><?php echo isset($u['name'])?$u['name']:"";?></span></td>
					<td><?php echo tTime::get_datetime("Y-m-d H:i",$item['dateline']);?></td>
					<td><?php echo tFun::keyword_replace($keyword,$item['withdraw_no']);?></td>
					<td>-</td>
					<td class="font-org"><?php echo isset($item['amount'])?$item['amount']:"";?></td>
					<td>
						-			
					</td>
					</tr>
				<?php }?>
		</tbody>
</table>
</div>
<div class="pagebar">
<?php echo isset($this->pagebar)?$this->pagebar:"";?>
</div>
</form>
<div class="cl"></div></div>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/layer/layer.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/easyTemplate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.init.admin.js");?>"></script>
<script language="javascript" src="<?php echo U("static@javascript/jquery.zclip/jquery.zclip.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static/cache/dataconfig.js");?>"></script>
<!--查看并审核-->
<script type="text/template" id="tpl_sh">
<#macro row data>
<form action="<?php echo U("/order_manager/withdraw_sh");?>" method="post" class="t-ajax-form form" role="form">
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">${data.title} <font class="font-red"></font></h4>
</div>
<div class="modal-body">
    <div class="form-item"><label class="fb">申请人:</label><div class="item-v"><span class="ivalue">${data.to_name} - 当前余额：<font class="font-org">${data.to_balance}</font></span></div></div>
	<div class="form-item"><label class="fb">申请金额:</label><div class="item-v"><span class="ivalue"><font class="f18 font-org">${data.amount}</font></span></div></div>
	<div class="form-item"><label class="fb">申请时间:</label><div class="item-v"><span class="ivalue">${data.date}</span></div></div>
	<div class="form-item"><label class="fb">交易编号:</label><div class="item-v"><span class="ivalue">${data.trade_no}</span></div></div>
	<div class="form-item"><label class="fb">银行交易流水单号:</label><div class="item-v"><span class="ivalue"><input type="text" name="bank_trade_no" class="high" /></span></div></div>
	<div class="form-item"><label class="fb">银行卡信息:</label><div class="item-v"><span class="ivalue"><font class="f12">${data.bank}(${data.bank_no})/${data.bank_name}</font></span></div></div>
	<div class="form-item"><label class="fb text-danger">选择&nbsp;</label><div class="item-v"><span class="ivalue"><select name="status"><option class="text-danger" value="2">审核失败</option><option class="text-success"  value="1">审核成功</option></select></span></div></div>
</div>
<div class="modal-footer">
      <input type="hidden" value="${data.id}" name="id" />
      <input type="hidden" value="${data.trade_no}" name="trade_no" />
	  <input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" />
      <font class="font-red">审核不可取消,请谨慎操作</font>&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;
      <button type="submit" class="btn btn-primary" data-loading-text="处理中……">确定审核</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>
</form>
</#macro>
</script>
<!--end查看并审核-->
<script language="javascript" src="<?php echo U("/static/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("/static/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>

<script language="javascript" >
$(".date-ymd").datetimepicker({
	language:  'zh-CN',
		autoclose: 1,
		startView: 2,
		minView: 2,
		maxView: 4,
		format:"yyyy-mm-dd",
		pickerPosition: "bottom-right"   
}); 
$(function(){
	//查看证件操作
		$(".table-item-op-sh").click(function(){
			var obj = this;
			$.ajaxPassport({
				url:"<?php echo U("/order_manager/withdraw_sh");?>",
				success:function(res){
					if(res.error == 1){
						$.ajaxTips(obj,res.message,"error");
					}else{
						var tpl_s = $("#tpl_sh").html();
					  	var tpl_v = res;
					  	tpl_v['title'] = "审核 "+res.to_name+ "的提现申请";
					  	$("#myModal").find(".modal-content").html(""+easyTemplate(tpl_s,tpl_v)).width(780);
					  	$('#myModal').modal();
					  	 //初始化表单
						$("#myModal form.t-ajax-form").each(function(i){
							$(this).submit(function(){
									return $.ajaxForm(this,i);
								});
						});
					}
				},
				data:{"id":$(this).attr("_id")}
			});
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