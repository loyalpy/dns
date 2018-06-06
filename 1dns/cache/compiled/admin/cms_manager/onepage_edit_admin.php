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
 <li><a href="<?php echo U("/cms_manager/onepage");?>" class="<?php if('/'.$inc.'/'.$act == '/cms_manager/onepage'){?>cur<?php }?>">单页管理</a></li>
 <li><a href="<?php echo U("/cms_manager/onepage_cate");?>" class="<?php if('/'.$inc.'/'.$act == '/cms_manager/onepage_cate'){?>cur<?php }?>">单页分类</a></li>
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
    <form id="form_edit" action="<?php echo U("/cms_manager/onepage_edit");?>" method="post" class="form-horizontal t-ajax-form" role="form">
    <div class="tab-content">
    <!--baseic info-->
    <div class="tab-pane fade in active" id="home">
        <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mfid">分类:</label>
		    <div class="col-sm-4">
		    <select name="cat_id" class="form-control" id="Mfid">
            <?php foreach($data_config['onepage'] as $key => $item){?>
            <option <?php if($data['cat_id'] == $key){?>selected="selected"<?php }?> value="<?php echo isset($key)?$key:"";?>"><?php echo isset($item['name'])?$item['name']:"";?></option>
            <?php }?>
			</select>
		    </div>
		</div>

        <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mname">标题:</label>
		    <div class="col-sm-6">
		    <input type="text" class="form-control" name="name" value="<?php if(isset($data['name']) ){?><?php echo $data['name'];?><?php }else{?><?php echo '';?><?php }?>" maxlength="30" id="Mname" placeholder="" />
		    </div>
		    <label class="col-sm-1 control-label" for="Msort">序号:</label>
		    <div class="col-sm-1">
		    <input type="text" class="form-control low" name="sort" value="<?php if(isset($data['sort']) ){?><?php echo $data['sort'];?><?php }else{?><?php echo '0';?><?php }?>" maxlength="30" id="Msort" placeholder="" />
		    </div>
	    </div>
	    <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mident">分类标识:</label>
		    <div class="col-sm-6">
		    <input type="text" class="form-control" name="ident" value="<?php if(isset($data['ident']) ){?><?php echo $data['ident'];?><?php }else{?><?php echo '';?><?php }?>" maxlength="30" id="Mident" placeholder="" />
		    </div>
	    </div>
	    <div class="form-group">
		    <label class="col-sm-2 control-label">是否外链:</label>
		    <div class="col-sm-10">
		    <label class="checkbox-inline">
			  <input name="hit" id="Mhit" type="checkbox" value="1" <?php if(isset($data['hit']) && $data['hit'] == 1){?>checked="checked"<?php }?>/> 外链
			</label>
		    </div>
		</div>
		 <div class="form-group">
		    <label class="col-sm-2 control-label">描述/外链地址:</label>
		    <div class="col-sm-10">
		    <div class="txt">
		    <textarea type="text" style="width:600px;height:60px;" class="form-control" name="description" placeholder="" rows="38"><?php echo isset($data['description'])?$data['description']:"";?></textarea>
		    </div>
		    </div>
		  </div>
   <!--end baseic info-->
   <!--seo info-->

        <div class="form-group">
		    <label class="col-sm-2 control-label" for="Mcontent">详情:</label>
		    <div class="col-sm-8">
		    <div class="txt">
		    <textarea type="text" style="width:100%;height:380px;" class="form-control" id="Mcontent" name="content" placeholder="" rows="38">
            <?php if(isset($data['content']) ){?><?php echo htmlspecialchars_decode($data['content']);?><?php }else{?><?php echo '';?><?php }?></textarea>
		    </div>
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
			<input type="hidden" name="id" value="<?php echo isset($data['id'])?$data['id']:"";?>" />
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
<?php echo tUtil::create_editor("content");?>


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