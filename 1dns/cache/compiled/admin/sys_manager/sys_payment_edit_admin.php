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
    <?php foreach($navs as $key => $item){?>
	<li><a href="<?php echo isset($item['url'])?$item['url']:"";?>" class="<?php if($cr == $key){?>cur<?php }?>"><?php echo isset($item['label'])?$item['label']:"";?></a></li>
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
  <div class="name">支付方式编辑</div>
  <div class="navbtn0"></div>
  <div class="navbtn"></div>
  <div class="cl"></div>
</div>
<form class="form t-ajax-form" action="<?php echo U("/sys_manager/sys_payment_edit");?>" method="post" enctype="multipart/form-data">
<div class="form-content">
			<table class='formtable' cellpadding="0" cellspacing="0">
				<col width="220px" />
				<col />
				<tr>
					<th>支付方式名称：</th>
					<td><input class="normal" name="name" type="text" value="<?php echo isset($name)?$name:"";?>" check="1" alt="支付方式名称不能为空！" />
						<label>*</label>
					</td>
				</tr>
				<tr>
					<th>支持交易货币：</th>
					<td><?php echo isset($SupportCurrency)?$SupportCurrency:"";?></td>
				</tr>
				 <?php foreach($attr_list as $key => $item){?>
					<tr>
						<th><?php echo isset($item['label'])?$item['label']:"";?>：</th>
						<td>
							<?php if($item['type']=='string'){?>
								<input class="normal" name="setting[<?php echo isset($key)?$key:"";?>]" type="text" value="<?php echo isset($config[$key])?$config[$key]:"";?>" alt="<?php echo isset($item['label'])?$item['label']:"";?>名称不能为空！" />
							<?php }elseif($item['type']=='radio'){?>
							    <span class="reset-inputs">
								<?php foreach($item['options'] as $value => $option){?>
									<label><input type="radio" class="reset" name="setting[<?php echo isset($key)?$key:"";?>]" value="<?php echo isset($value)?$value:"";?>" <?php if($config[$key]==$value){?> checked='checked' <?php }?> onclick="<?php echo isset($item['event'])?$item['event']:"";?>(this)" /><?php echo isset($option)?$option:"";?></label>
								<?php }?>
								</span>
							<?php }elseif($item['type']=='select'){?>
								<select name="setting[<?php echo isset($key)?$key:"";?>]">
								<?php foreach($item['options'] as $value => $option){?>
									<option value="<?php echo isset($value)?$value:"";?>" <?php if($config[$key]==$value){?> 'selected' <?php }?>><?php echo isset($option)?$option:"";?></option>
								<?php }?>
								</select>
							<?php }elseif($item['type']=='file'){?>
								<input type="file" name="config[<?php echo isset($key)?$key:"";?>]" />
								<input type="hidden" name="setting[<?php echo isset($key)?$key:"";?>]" value="<?php echo isset($config[$key])?$config[$key]:"";?>" />
							<?php }?>
							<?php echo isset($item['eventscripts'])?$item['eventscripts']:"";?>
						</td>
					</tr>
					<?php if(isset($item['extendcontent'])){?>
						<?php foreach($item['extendcontent'] as $key => $eitem){?>
							<tr id="<?php echo isset($eitem['property']['extconId'])?$eitem['property']['extconId']:"";?>">
								<th></th>
								<td class="reset-inputs">
									<ul>
									<?php foreach($eitem['value'] as $key => $evitem){?>
									<li style="width:10em;float:left;">
										<input class="reset" type="<?php echo isset($eitem['property']['type'])?$eitem['property']['type']:"";?>" name="setting[<?php echo isset($eitem['property']['name'])?$eitem['property']['name']:"";?>][]" value="<?php echo isset($evitem['value'])?$evitem['value']:"";?>" <?php echo isset($evitem['checked'])?$evitem['checked']:"";?> />
										<img src="<?php echo U("static@/public/images/payments/images/");?><?php echo isset($evitem['imgname'])?$evitem['imgname']:"";?>" />
									</li>
									<?php }?>
									</ul>
								</td>
							</tr>
						<?php }?>
					<?php }?>
				 <?php }?>
				<tr>
					<th>手续费设置：</th>
					<td class="reset-inputs">
						<label><input name="poundage_type" type="radio" class="reset" value="1" <?php if($poundage_type==1){?> checked="checked" <?php }?>  onclick="radioselect(this)" />按百分比收费</label>
						<label><input name="poundage_type" type="radio" class="reset" value="2" <?php if($poundage_type==2){?> checked="checked" <?php }?> onclick="radioselect(this)" />按固定额度收费</label>
					</td>
				</tr>
				<tr id="poundage_rate"  <?php if($poundage_type==2){?>  style="display:none" <?php }?>>
					<th></th>
					<td>
						费率：<input class="tiny" name="poundage_rate" type="text" value="<?php echo isset($poundage_rate)?$poundage_rate:"";?>" alt="费率不能为空！" />% &nbsp;&nbsp; 说明：顾客将支付订单总金额乘以此费率作为手续费；
					</td>
				</tr>
				<tr id="poundage_fix" <?php if($poundage_type==1){?>  style="display:none" <?php }?>>
					<th></th>
					<td>
						金额：<input class="tiny" name="poundage_fix" type="text" value="<?php echo isset($poundage_fix)?$poundage_fix:"";?>" alt="金额不能为空！" />元 &nbsp;&nbsp; 说明：顾客每笔订单需要支付的手续费；
					</td>
				</tr>
				<tr>
					<th>类型：</th>
					<td>
						<select name="type" pattern='required'>
							<?php if($file_path != 'offline'){?>
							<option <?php if($type=='1'){?>selected=selected<?php }?> value="1">线上支付</option>
							<?php }?>
							<option <?php if($type=='2'){?>selected=selected<?php }?> value="2">线下支付</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>排序：</th><td><input class="tiny" name="order" type="text" value="<?php echo isset($order)?$order:"";?>" alt="排序不能为空！" /></td>
				</tr>
				<tr>
					<th valign="top">描述：</th>
					<td><textarea name="description"  style="width:530px;height:60px;"><?php echo isset($description)?$description:"";?></textarea></td>
				</tr>
				<tr>
					<th>支付说明：</th>
					<td><textarea id="note" name="note" style="width:530px;height:60px;"><?php echo isset($note)?$note:"";?></textarea><br/>
						<label>此信息会展示在用户的支付页面，尤其是线下支付时可以填写收款账号等信息</label>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input type="hidden" name="pay_type" value="<?php echo isset($file_path)?$file_path:"";?>" />
						<input type="hidden" name="id" value="<?php echo isset($plugin_id)?$plugin_id:"";?>" />
						<input type="hidden" name="pay_id" value="<?php echo isset($pay_id)?$pay_id:"";?>" />
						<input type="hidden" name="hash" value="<?php echo tUtil::hash();?>" />
						<button  class="btn btn-primary btn-lg" type='submit'><span>确定</span></button>
					</td>
				</tr>
			</table>
 </div>
</form>
<script language="javascript">
	function radioselect(obj){
		if(obj.value==1){
			$('#poundage_rate').show();
			$('#poundage_fix').hide();
		}else{
			$('#poundage_fix').show();
			$('#poundage_rate').hide();
		}
	}
</script>
<div class="cl"></div></div>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/layer/layer.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/easyTemplate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.init.admin.js");?>"></script>
<script language="javascript" src="<?php echo U("static@javascript/jquery.zclip/jquery.zclip.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static/cache/dataconfig.js");?>"></script><script language="javascript">
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