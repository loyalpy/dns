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
      
<div class="menu">
 <ul>
 <li><a href="<?php echo U("/cms_manager/adlist");?>" class="<?php if('/'.$inc.'/'.$act == '/cms_manager/adlist_edit'){?>cur<?php }?>">广告列表</a></li>
 <li><a href="<?php echo U("/cms_manager/adlist_cate");?>" class="<?php if('/'.$inc.'/'.$act == '/cms_manager/adlist_cate'){?>cur<?php }?>">广告类目</a></li>
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
  <div class="name">添加/修改广告</div>
  <div class="navbtn0"></div>
  <div class="navbtn">
  <!--button-->
  <?php if($this->check_purview("/cms_manager/adlist_edit")){?>
  <a href="<?php echo U("/cms_manager/adlist_edit");?>" class="btn btn-danger btn-sm">
  添加广告</a>
  <?php }?>
  <!--end button-->
  </div>
  <div class="cl"></div>
</div>

<form enctype="multipart/form-data"  action="<?php echo U("/cms_manager/adlist_edit");?>" method="post" class="form-horizontal t-ajax-form" role="form">
<div class="form-content">
   <div class="form-group">
       <label class="col-sm-2 control-label" for="Madpos">广告分类:</label>
       <div class="col-sm-4">
            <select name="data[cat_id]" class="form-control" id="Madpos">
		 	<?php foreach($catlist as $key => $item){?>
		    <option value="<?php echo isset($item['code'])?$item['code']:"";?>" <?php if(isset($data['cat_id']) && $data['cat_id'] == $item['code']){?>selected="selected"<?php }?>><?php echo isset($item['name'])?$item['name']:"";?></option>
		    <?php }?>
			</select>
       </div>
   </div>
   <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mname">广告名称:</label>
		    <div class="col-sm-4">
		    <input type="text" class="form-control" name="data[name]" value="<?php echo isset($data['name'])?$data['name']:"";?>" maxlength="180" id="Mname" placeholder="" />
		    </div>
		    <label class="col-sm-1 control-label" for="Msort">序号:</label>
		    <div class="col-sm-2">
		    <input type="text" class="form-control" name="data[sort]" value="<?php echo isset($data['sort'])?$data['sort']:"";?>" maxlength="30" id="Msort" placeholder="" />
		    </div>
   </div>
    <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mname">广告英文名称:</label>
		    <div class="col-sm-4">
		    <input type="text" class="form-control" name="data[enname]" value="<?php echo isset($data['enname'])?$data['enname']:"";?>" maxlength="180" id="Menname" placeholder="" />
		    </div>
		    
   </div>
   <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mlinkurl">广告链接:</label>
		    <div class="col-sm-4">
		    <input type="text" class="form-control" name="data[linkurl]" value="<?php echo isset($data['linkurl'])?$data['linkurl']:"";?>" maxlength="180" id="Mlinkurl" placeholder="" />
		    </div>
   </div>
   <div class="form-group">
    <label class="col-sm-2 control-label" for="Mmintime">广告时间:</label>
    <div class="input-group date form_datetime col-sm-3 clear-padding">
    <input size="16" type="text" id="Mmintime" name="data[start_dateline]" value="<?php echo tTime::get_datetime('Y-m-d H:i:s',$data['start_dateline']);?>" placeholder="" class="form-control" readonly /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    </div> 
    <div class="input-group date form_datetime col-sm-3 clear-padding">
    <input size="16" type="text" class="form-control" id="Mmaxtime" name="data[end_dateline]" value="<?php echo tTime::get_datetime('Y-m-d H:i:s',$data['end_dateline']);?>" readonly /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
   </div>
   <div class="form-group">
       <label class="col-sm-2 control-label" for="Madtype">广告类型:</label>
       <div class="col-sm-8">
           <select name="data[type]" id="Madtype" class="form-control" onchange="change_adtype(this.value)">
		   <option value="img" <?php if($data['type'] == 'img'){?> selected="selected"<?php }?>>图片广告</option>
		   <option value="txt" <?php if($data['type'] == 'txt'){?> selected="selected"<?php }?>>文字广告</option>
		   <option value="tan" <?php if($data['type'] == 'tan'){?> selected="selected"<?php }?>>弹窗</option>
		   <option value="dui" <?php if($data['type'] == 'dui'){?> selected="selected"<?php }?>>对联</option>
		   </select>
       </div>
   </div>
   <div id="imgcontent" style="<?php if($data['type'] == 'txt'){?>display:none;<?php }?>">
	   <div class="form-group" >
	       <label class="col-sm-2 control-label" for="Mupimgs">上传图片:</label>
	       <div class="col-sm-8">
	            <input type="file" class="form-control" id="Mupimgs" name="upimgs" size="30" /> <br/>
			 	生成小图尺度&nbsp; 宽：<input name="thumb_w" class="form-control" style="width:60px;display:inline;" value="0" /> 高：<input class="form-control" name="thumb_h" style="width:60px;display:inline;" value="0" />
			 	<span class="txt-red">0 即不生成小图</span><br/>
			 	<?php if(isset($data['imgurl']) && $data['imgurl']){?>
			 	<img title="<?php echo isset($data['name'])?$data['name']:"";?>" width="230" height="50" src="<?php echo U("static@$data[imgurl]");?>" />
			 	<?php }?>
	       </div>
	   </div>
	   <div class="form-group" >
	       <label class="col-sm-2 control-label" for="Mupimgs_thumb">上传小图片:</label>
	       <div class="col-sm-8">
	            <input type="file" class="form-control" id="Mupimgs_thumb" name="upimgs_thumb" size="30" />
	       </div>
	   </div>
   </div>
   <div class="form-group" id="txtcontent">
		    <label class="col-sm-2 control-label" for="Mcontent">详细文字:</label>
		    <div class="col-sm-8">
		    <textarea type="text" class="form-control" rows="3" name="data[content]" id="Mcontent"><?php echo isset($data['content'])?$data['content']:"";?></textarea>
		    </div>
   </div>
   <div class="form-group" id="imgcontent" style="<?php if($data['type'] == 'txt'){?>display:none;<?php }?>">
       <label class="col-sm-2 control-label" for="Madwidth">广告规格:</label>
       <div class="col-sm-8">
            宽：<input id="Madwidth" class="form-control"  name="data[width]"  value="<?php echo isset($data['width'])?$data['width']:"";?>" style="width:80px;display:inline;" size="15" />
		 	&nbsp;&nbsp;&nbsp;&nbsp;高：<input class="form-control" name="data[height]" value="<?php echo isset($data['height'])?$data['height']:"";?>" style="width:80px;display:inline;" size="15" />
       </div>
   </div>
   <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mbgcolor">背景颜色:</label>
		    <div class="col-sm-4">
		    <input type="text" class="form-control" name="data[bgcolor]" value="<?php echo isset($data['bgcolor'])?$data['bgcolor']:"";?>" maxlength="30" id="Mbgcolor" placeholder="" />
		    </div>
   </div>
    <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mmembers_id">关联用户ID:</label>
		    <div class="col-sm-4">
		    <input type="text" class="form-control" name="data[uid]" value="<?php echo isset($data['uid'])?$data['uid']:"";?>" maxlength="30" id="Muid" placeholder="" />
		    </div>
   </div>
   <div class="form-group">
	    <label class="col-sm-2 control-label">状态:</label>
	    <div class="col-sm-6">
	    <label class="radio-inline">
		  <input type="radio" <?php if(isset($data['status']) && $data['status']==1){?>checked<?php }?> value="1" name="data[status]" />开启
		</label>
		<label class="radio-inline">
		 <input type="radio" <?php if(isset($data['status']) && $data['status']==0){?>checked<?php }?> value="0" name="data[status]"/>关闭
		</label>
	    </div>
	</div>
	<div class="form-group">
		    <label class="col-sm-2 control-label"></label>
		    <div class="col-sm-6">
		    <input type="hidden" value="<?php echo isset($data['id'])?$data['id']:"";?>" name="id" />
			<input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" />
            <button type="submit" class="btn btn-primary btn-lg" tabindex="4" data-loading-text="处理中……" >完成修改</button>
		    </div>
  </div>
</div>
<div class="dis30"></div>
</form>
<script language="javascript">
   function change_adtype(t){
   	   if(t=='txt'){ 
			$('#imgcontent').hide();
	   }else{
			$('#imgcontent').show();
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
<script language="javascript" src="<?php echo U("static/cache/dataconfig.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<script language="javascript" >
$(".form_datetime").datetimepicker({
	language:  'zh-CN',
    weekStart: 1,
    todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 0,
	//maxView: 1,
	forceParse: 1,
	showMeridian: 1,
	pickerPosition: "bottom-left"   
}); 
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