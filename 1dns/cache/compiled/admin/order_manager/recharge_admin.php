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
  <div class="name">在线充值</div>
  <div class="navbtn0">
  <?php $status_s = array("ing"=>"待审核","fail"=>"失败","success"=>"成功")?>
  <a class="btn <?php if($t == 'all'){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="" href="<?php echo U("/order_manager/recharge?t=all");?>"><cite class="glyphicon glyphicon-th"></cite> 所有</a> 
  <?php foreach($status_s as $key => $item){?>
  <a class="btn <?php if($t == $key){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="<?php echo isset($item)?$item:"";?>" href="<?php echo U("/order_manager/recharge?t=$key");?>"><cite class="glyphicon glyphicon-th"></cite> <?php echo isset($item)?$item:"";?></a> 
  <?php }?>
  </div>
  <div class="navbtn">
  <!--button-->
	  <?php if($this->check_purview("/order_manager/recharge_add")){?>
  <!--<a href="javascript:void(0)" data-utype="1" class="btn btn-primary btn-sm addbtn">-->
  <!--<cite class="glyphicon glyphicon-plus"></cite> -->
  <!--新增充值</a>-->
	  <?php }?>
  <!--end button-->
  </div>
  <div class="cl"></div>
</div>
<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/order_manager/recharge?t=$t");?>">
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
				<col width="260px" />
				<col width="150px" />
				<col />
				<col width="230px" />
				<col width="70px" />
				<col width="130px" />
				<thead>
					<tr class="active">
					<th>充值编号</th>
					<th>充值人/创建时间</th>
					<th>交易信息</th>
					<th>开票信息</th>
					<th>金额</th>
					<th>状态</th>
					</tr>
				</thead>
		<tbody id="conth">
		   <?php foreach($this->datalist as $key => $item){?>
				<?php $u = C("user")->get_cache_userinfo($item['uid']);?>
				    <tr>
					<td class="font-gray">
	                <cite></cite>
					<font class="font-green"><?php echo tFun::keyword_replace($keyword,$item['recharge_no']);?><font class="font-gray2">[<?php echo isset($item['id'])?$item['id']:"";?>]</font></font><br/>
					<?php echo isset($item['payment_name'])?$item['payment_name']:"";?>
					</td>
					<td class="e">
					<a href="<?php echo U("/order_manager/recharge?t=$t&keyword=");?><?php echo isset($item['uid'])?$item['uid']:"";?>" class="font-blue"><?php echo isset($u['name'])?$u['name']:"";?></a><font class="font-gray">(<?php echo isset($item['uid'])?$item['uid']:"";?>)</font><br/>
					<?php echo tTime::get_datetime("Y-m-d H:i",$item['dateline']);?></td>
					<td>
					<?php if($item['order_no']){?>
						<span class="font-gray">
						<?php if($item['trade_date']){?>转账日期：<?php echo tTime::get_datetime('Y-m-d',$item['trade_date']);?><br/><?php }?>
						<?php if($item['order_no']){?>订单号：<?php echo isset($item['order_no'])?$item['order_no']:"";?><br/><?php }?>
						支付方式：<?php if($item['inpay'] == 1){?>充值并支付<?php }else{?>仅充值,不支付<?php }?></span>
					<?php }else{?>
					-
					<?php }?>
					</td>
					<td>
					<?php if($item['kaipiao'] == 0){?>
						不要开票
					<?php }else{?>
						抬头:<?php echo isset($item['kaipiao_tou'])?$item['kaipiao_tou']:"";?><br/>
						<?php if(empty($item['kaipiao_ship'])){?>
							<font class="font-red">发票邮寄信息欠缺</font><br/>
						<?php }else{?>
							<?php echo isset($item['kaipiao_ship']['linker'])?$item['kaipiao_ship']['linker']:"";?>/<?php echo isset($item['kaipiao_ship']['mobile'])?$item['kaipiao_ship']['mobile']:"";?>/<?php echo isset($item['kaipiao_ship']['area'])?$item['kaipiao_ship']['area']:"";?>/<?php echo isset($item['kaipiao_ship']['zcode'])?$item['kaipiao_ship']['zcode']:"";?><br/>
							<?php echo isset($item['address'])?$item['address']:"";?>
						<?php }?>
						
						<?php if(!empty($item['kaipiao_post'])){?>
						    <?php echo isset($item['kaipiao_post']['name'])?$item['kaipiao_post']['name']:"";?>/单号:<?php echo isset($item['kaipiao_post']['no'])?$item['kaipiao_post']['no']:"";?>
						<?php }?>
					<?php }?>
					</td>
					<td class="font-org"><?php echo isset($item['amount'])?$item['amount']:"";?>
					<br/><font class="font-gray"><?php echo isset($item['r_amount'])?$item['r_amount']:"";?></font>
					</td>
					<td>
					<?php if($item['status'] == 1){?><span class="font-green">成功</span>
					<?php }elseif($item['status'] == -1){?><span class="font-red">失败</span>
						<?php if($this->check_purview("/order_manager/recharge_sh")){?>
					<button type="button" class="btn btn-danger btn-xs table-item-op-sh" _name="" _id="<?php echo isset($item['id'])?$item['id']:"";?>">审核</button>
						<?php }?>
					<?php }elseif($item['status'] == 0){?><span class="font-gray">待审</span>
						<?php if($this->check_purview("/order_manager/recharge_sh")){?>
					<button type="button" class="btn btn-danger btn-xs table-item-op-sh" _name="" _id="<?php echo isset($item['id'])?$item['id']:"";?>">审核</button>
						<?php }?>
					<?php }?>
					<?php if($item['kaipiao'] > 0){?>
						<?php if($this->check_purview("/order_manager/recharge_kaipiao")){?>
					<button type="button" class="btn btn-success btn-xs table-item-op-kaipiao" _name="" _id="<?php echo isset($item['id'])?$item['id']:"";?>">发票</button>
						<?php }?>
					<?php }?>
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
<form action="<?php echo U("/order_manager/recharge_sh");?>" method="post" class="t-ajax-form form" role="form">
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">${data.title} <font class="font-red"></font></h4>
</div>
<div class="modal-body">
    <div class="form-item"><label class="fb">充值人:</label><div class="item-v"><span class="ivalue">${data.to_name} - 当前余额：${data.to_balance}</span></div></div>
	<div class="form-item"><label class="fb">充值金额:</label><div class="item-v"><span class="ivalue"><font class="f18 font-org">${data.amount}</font> /<font class="f18 font-green">${data.r_amount}</font></span></div></div>
	<div class="form-item"><label class="fb">创建时间:</label><div class="item-v"><span class="ivalue">${data.date}</span></div></div>
	<div class="form-item"><label class="fb">银行转账日期:</label><div class="item-v"><span class="ivalue">${data.trade_date}</span></div></div>
	<div class="form-item"><label class="fb">转出银行:</label><div class="item-v"><span class="ivalue">${data.trade_bank}</span></div></div>
	<div class="form-item"><label class="fb">银行交易流水单号:</label><div class="item-v"><span class="ivalue">${data.trade_no}</span></div></div>
	<div class="form-item"><label class="fb">充值方式:</label><div class="item-v"><span class="ivalue">${data.payment_name}</span></div></div>
</div>
<div class="modal-footer">
      <input type="hidden" value="${data.id}" name="id" />
      <input type="hidden" value="${data.recharge_no}" name="recharge_no" />
	  <input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" />
      <font class="font-red">审核成功后不可取消,请谨慎操作</font>&nbsp;&nbsp;&nbsp;
      <label class="fb text-danger">选择&nbsp;</label><select name="status"><option class="text-danger" value="-1">审核失败</option><option class="font-gray"  value="0">待审核</option><option class="text-success"  value="1">审核成功</option></select>&nbsp;&nbsp;
      <button type="submit" class="btn btn-primary" data-loading-text="处理中……">确定审核</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>
</form>
</#macro>
</script>
<!--end查看并审核-->

<!--修改开票信息-->
<script type="text/template" id="tpl_kaipiao">
<#macro row data>
<form action="<?php echo U("/order_manager/recharge_kaipiao");?>" method="post" class="t-ajax-form form" role="form">
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">${data.title} <font class="font-red"></font></h4>
</div>
<div class="modal-body">
    <div class="form-item"><label class="fb">充值人:</label><div class="item-v"><span class="ivalue">${data.to_name} - 当前余额：${data.to_balance}</span></div></div>
	<div class="form-item"><label class="fb">发票抬头：</label>
    <div class="item-v"><input type="text" value="${data.kaipiao_tou}" name="kaipiao_tou" class="hign" /></div><div class="cl"></div>
    </div>
	<div class="form-item"><label class="fb">快递联系人：</label>
    <div class="item-v"><input type="text" value="<#if (!$.is_empty(data.kaipiao_ship.linker))>${data.kaipiao_ship.linker}</#if>" name="kaipiao_ship[linker]" class="hign" /></div><div class="cl"></div>
    </div>
	<div class="form-item"><label class="fb">快递联系手机：</label>
    <div class="item-v"><input type="text" value="<#if (!$.is_empty(data.kaipiao_ship.mobile))>${data.kaipiao_ship.mobile}</#if>" name="kaipiao_ship[mobile]" class="hign" /></div><div class="cl"></div>
    </div>
	<div class="form-item"><label class="fb">快递地区：</label>
    <div class="item-v"><input type="text" value="<#if (!$.is_empty(data.kaipiao_ship.area))>${data.kaipiao_ship.area}</#if>" name="kaipiao_ship[area]" class="hign" /></div><div class="cl"></div>
    </div>
	<div class="form-item"><label class="fb">快递详细地址：</label>
    <div class="item-v"><input type="text" value="<#if (!$.is_empty(data.kaipiao_ship.address))>${data.kaipiao_ship.address}</#if>" name="kaipiao_ship[address]" class="hign" /></div><div class="cl"></div>
    </div>
	<div class="form-item"><label class="fb">快递邮编：</label>
    <div class="item-v">
	<input type="text" name="kaipiao_ship[zcode]" value="<#if (!$.is_empty(data.kaipiao_ship.zcode))>${data.kaipiao_ship.zcode}</#if>" class="hign" />
    </div>
    <div class="cl"></div>
</div>
<div class="form-item"><label class="fb">物流名称：</label>
    <div class="item-v">
	<input type="text" name="kaipiao_post[name]" value="<#if (!$.is_empty(data.kaipiao_post.name))>${data.kaipiao_post.name}</#if>" class="hign" />
    </div>
    <div class="cl"></div>
</div>
<div class="form-item"><label class="fb">物流编号：</label>
    <div class="item-v">
	<input type="text" name="kaipiao_post[no]" value="<#if (!$.is_empty(data.kaipiao_post.no))>${data.kaipiao_post.no}</#if>" class="hign" />
    </div>
    <div class="cl"></div>
</div>
</div>
<div class="modal-footer">
      <input type="hidden" value="${data.id}" name="id" />
      <input type="hidden" value="${data.recharge_no}" name="recharge_no" />
	  <input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" />
      <font class="font-red">请谨慎操作</font>&nbsp;&nbsp;&nbsp;
      <label class="fb text-danger">选择&nbsp;</label><select name="kaipiao"><option <#if (data.kaipiao == 0)>selected="selected"</#if> class="text-danger" value="0">不开票了</option><option <#if (data.kaipiao == 1)>selected="selected"</#if> class="text-danger" value="1">开票(未开票)</option><option <#if (data.kaipiao == 2)>selected="selected"</#if> class="font-gray"  value="2">开票(已开票)</option><option <#if (data.kaipiao == 3)>selected="selected"</#if> class="text-success"  value="3">开票(已邮票)</option><option class="text-success" <#if (data.kaipiao == 4)>selected="selected"</#if>  value="4">已完成</option></select>&nbsp;&nbsp;
      <button type="submit" class="btn btn-primary" data-loading-text="处理中……">确定修改</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>
</form>
</#macro>
</script>
<!--end查看并修改开票信息-->

<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<?php echo $this->fetch('tpl/form')?>
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
				url:"<?php echo U("/order_manager/recharge_sh");?>",
				success:function(res){
					if(res.error == 1){
						$.ajaxTips(obj,res.message,"error");
					}else{
						var tpl_s = $("#tpl_sh").html();
					  	var tpl_v = res;
					  	tpl_v['title'] = "审核 "+res.to_name+ "的充值订单";
					  	$("#myModal").find(".modal-content").html(""+easyTemplate(tpl_s,tpl_v));
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
		//发票操作
		$(".table-item-op-kaipiao").click(function(){
			var obj = this;
			$.ajaxPassport({
				url:"<?php echo U("/order_manager/recharge_kaipiao");?>",
				success:function(res){
					if(res.error == 1){
						$.ajaxTips(obj,res.message,"error");
					}else{
						var tpl_s = $("#tpl_kaipiao").html();
					  	var tpl_v = res;
					  	tpl_v['title'] = "操作 "+res.to_name+ "开票";
					  	$("#myModal").find(".modal-dialog").width(760);
					  	$("#myModal").find(".modal-content").html(""+easyTemplate(tpl_s,tpl_v));
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
		
		
		///手动给用户充值
		$(".addbtn").unbind("click").bind("click",function(){
			edit_user_recharge(this);
		})
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