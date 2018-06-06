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
 <li><a href="<?php echo U("/cms_manager/threads");?>" class="<?php if('/'.$inc.'/'.$act == '/cms_manager/threads_edit'){?>cur<?php }?>">内容列表</a></li>
 <li><a href="<?php echo U("/cms_manager/threads_forums");?>" class="<?php if('/'.$inc.'/'.$act == '/cms_manager/threads_forums'){?>cur<?php }?>">内容分类</a></li>
 
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
<div class="form-content">
	<!-- edit nav-->
	<div class="tabnav">
	<ul class="nav nav-tabs" id="myTab" style="display:none;">
	  <li class="active"><a href="#home" data-toggle="tab">基本信息</a></li>
	  <li><a href="#detail" data-toggle="tab">详情/咨询</a></li>
	</ul>
	</div>
	<!-- end edit nav-->
    <form id="form_edit" action="<?php echo U("/cms_manager/threads_edit");?>" method="post" class="form-horizontal t-ajax-form" role="form">
    <div class="tab-content">
    <!--baseic info-->
    <div class="tab-pane fade in active" id="home">
        <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mfid">分类:</label>
		    <div class="col-sm-4">
		    <select name="fid" class="form-control" id="Mfid">

		 	<?php echo C('category','cms_forums')->get(0,$data['fid'],1);?>
			</select>
		    </div>
		</div>

        <div class="form-group">
		    <label class="col-sm-2 control-label" for="Msubject">标题:</label>
		    <div class="col-sm-6">
		    <input type="text" class="form-control" name="subject" value="<?php if(isset($data['subject']) ){?><?php echo $data['subject'];?><?php }else{?><?php echo '';?><?php }?>" maxlength="30" id="Msubject" placeholder="" />
		    </div>
		    <label class="col-sm-1 control-label" for="Msort">序号:</label>
		    <div class="col-sm-1">
		    <input type="text" class="form-control low" name="sort" value="<?php if(isset($data['sort']) ){?><?php echo $data['sort'];?><?php }else{?><?php echo '0';?><?php }?>" maxlength="30" id="Msort" placeholder="" />
		    </div>
	    </div>
	    <div class="form-group">
		    <label class="col-sm-2 control-label">附加属性:</label>
		    <div class="col-sm-10">

		    <label class="checkbox-inline">
			  <input name="status" id="Mstatus" type="checkbox" value="1" <?php if(isset($data['status']) && $data['status'] == 1){?>checked="checked"<?php }?>/>状态
			</label>
			
			<label class="checkbox-inline">
			  <input name="intui" id="Mintui" type="checkbox" value="1" <?php if(isset($data['intui']) && $data['intui'] == 1){?>checked="checked"<?php }?>/>推荐
			</label>

			<label class="checkbox-inline">
			  <input name="intop" id="Mintop" type="checkbox" value="1" <?php if(isset($data['intop']) && $data['intop'] == 1){?>checked="checked"<?php }?>/>置顶
			</label>
			<label class="checkbox-inline">
			  <input name="inhot" id="Minhot" type="checkbox" value="1" <?php if(isset($data['inhot']) && $data['inhot'] == 1){?>checked="checked"<?php }?>/>热门
			</label>

		    </div>
		  </div>
   <!--end baseic info-->
   <!--seo info-->

        <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mcontent">详情:</label>
		    <div class="col-sm-8">
		    <div class="txt">
		    <textarea type="text" style="width:100%;height:380px;" class="form-control" id="Mmessage" name="message" placeholder="" rows="38">
            <?php if(isset($post['message']) ){?><?php echo htmlspecialchars_decode($post['message']);?><?php }else{?><?php echo '';?><?php }?></textarea>
		    </div>
		    </div>
		  </div>
           <!--产品图片-->
		  <div class="form-group">
          <label class="col-sm-2 control-label"></label>
		  <div class="col-sm-8">
		  <?php echo X('front_attach')->cms_upload(0,array("tid"=>$data['tid'],"pid"=>$post['pid'],"fm"=>$data['fm'],"fm_id"=>$data['fm_id']),"KE_message.focus();KE_message.insertHtml('[attach]'+file_id+'[/attach]')");?>
		  </div>
		  </div>
		  <!--end 产品图片-->
		  
		  
		   <div class="form-group">
		    <label class="col-sm-2 control-label">描述:</label>
		    <div class="col-sm-10">
		    <div class="txt">
		    <textarea type="text" style="width:600px;height:80px;" class="form-control" name="description" placeholder="" rows="38"><?php echo isset($data['description'])?$data['description']:"";?></textarea>
		    </div>
		    </div>
		  </div>
		  
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="Msoure">发布者:</label>
		    <div class="col-sm-5">
		    <input type="text" class="form-control" name="soure" value="<?php if(isset($data['soure']) ){?><?php echo isset($data['soure'])?$data['soure']:"";?><?php }?>" maxlength="30" id="Msoure" placeholder="" />
		    </div>
		 </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mseo_title">优化标题:</label>
		    <div class="col-sm-8">
		    <input type="text" class="form-control" name="seo_title" value="<?php if(isset($data['seo_title']) ){?><?php echo $data['seo_title'];?><?php }else{?><?php echo '';?><?php }?>" maxlength="30" id="Mseo_title" placeholder="" />
		    </div>
	    </div>
	    
	    <div class="form-group">
		    <label class="col-sm-2 control-label text-danger" for="Mseo_title">关键词:</label>
		    <div class="col-sm-8">
		    <input type="text" class="form-control" name="seo_keyword" value="<?php if(isset($data['seo_keyword']) ){?><?php echo $data['seo_keyword'];?><?php }else{?><?php echo '';?><?php }?>" maxlength="30" id="Mseo_keyword" placeholder="" />
		    </div>
	    </div>

        <div class="form-group">
		    <label class="col-sm-2 control-label text-danger" for="Mseo_description">优化描述:</label>
		    <div class="col-sm-8">
		    <input type="text" class="form-control" name="seo_description" value="<?php if(isset($data['seo_description']) ){?><?php echo $data['seo_description'];?><?php }else{?><?php echo '';?><?php }?>" maxlength="30" id="Mseo_description" placeholder="" />
		    </div>
	    </div>

   </div>
   <!--end seo info-->
   <div class="form-group">
		    <label class="col-sm-2 control-label"></label>
		    <div class="col-sm-6">
		    <input name="hash" value="<?php echo tUtil::hash();?>" type="hidden" />
			<input type="hidden" name="tid" value="<?php echo isset($data['tid'])?$data['tid']:"";?>" />
			<input type="hidden" name="pid" value="<?php echo isset($post['pid'])?$post['pid']:"";?>" />
			<input type="hidden" name="old_fid" value="<?php echo isset($data['fid'])?$data['fid']:"";?>" />
            <button type="submit" class="btn btn-success btn-lg" tabindex="4" data-loading-text="处理中……" ><?php if(isset($data['tid']) && $data['tid']){?>修改<?php }else{?>发表</a><?php }?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-default btn-lg" tabindex="4" data-loading-text="处理中……" >清空</button>          
		    </div>
  </div>
  </div>
  </form>
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
<?php echo tUtil::create_editor("message");?>
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