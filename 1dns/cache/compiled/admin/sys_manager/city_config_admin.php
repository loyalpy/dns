<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">
  <title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
  
  <meta name="keywords" content="<?php echo isset($site['seo_keyword'])?$site['seo_keyword']:"";?>" />
  <meta name="description" content="<?php echo isset($site['seo_description'])?$site['seo_description']:"";?>" />
      <link href="<?php echo U("/static/javascript/bootstrap/css/bootstrap.min.css");?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo U("static@/css/style_admin.css");?>"  rel="stylesheet" type="text/css" />
    <?php if(isset($style) && $style != "default"){?>
    <link href="<?php echo U("static@/css/style_admin_$style.css");?>"  rel="stylesheet" type="text/css" />
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
    <li><a href="<?php echo U("/");?>" target="_blank" class=" font-gray"><cite class="glyphicon glyphicon-home"></cite> 首页</a></li>
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
    <img class="myavatar_30" width="15" height="15" style="position:relative;top:4px;" src="<?php echo U("/misc/avatar/uid/$uid/size/30");?>" /> 
    <?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?> <b class="caret"></b></a>
    <ul class="dropdown-menu">
       <li><a href="<?php echo U("/ucenter/profile_basic");?>">个人资料修改</a></li>
       <li><a href="<?php echo U("/ucenter/profile_avatar");?>">个人头像修改</a></li>
       <li><a href="<?php echo U("/ucenter/profile_passwd");?>">修改密码</a></li>
       <li class="divider"></li>
       <li><a href="<?php echo U("/login/logout");?>">退出</a></li>
     </ul>
     </li>
     
   </ul>
  </div>
  <div class="cl"></div>
</div>
</div>
<div class="header">
  <div class="aps">
      <div class="logo"><a href="/sys_manager" title=""><?php echo isset($site['site_name'])?$site['site_name']:"";?></a></div>
      
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
     <dd class="<?php if(in_array($this->nav['uri'],array('/ucenter/profile_passwd'))){?>cur<?php }?>"><a href="/ucenter/profile_passwd">修改密码</a></dd>
     <dd><a href="/login/logout">退出 </a></dd>
     </dl>
     </div>
</div>
<div class="page-inner">
<ol class="breadcrumb">
  <li><a href="<?php echo isset($url)?$url:"";?>?pid=0" class="txt-blue">顶级<?php echo isset($table_alias)?$table_alias:"";?></a></li>
  <?php if($catpaths){?>
  <?php foreach($catpaths as $key => $item){?>
  <li><a href="<?php echo isset($url)?$url:"";?>?pid=$key" class="txt-blue"><?php echo isset($item)?$item:"";?></a></li>
  <?php }?>
  <?php }?> 
  <li>&nbsp;&nbsp;&nbsp;
  <a href="<?php echo isset($url)?$url:"";?>?do=edit&pid=<?php echo isset($pid)?$pid:"";?>" class="btn btn-danger">
  <span class="glyphicon glyphicon-plus"></span> 添加</a> 
  <button type="button" data-url="<?php echo isset($url)?$url:"";?>?do=refresh" class="btn btn-success t-ajax-button" data-loading-text="处理中……">生成缓存</button></li>
</ol>
<div class="table-responsive" style='width:600px;'>
<table class='table table-bordered' cellpadding="0" cellspacing="0" >
				<col width="130px" />
				<col width="230px" />
				<col />
				<col width="80px" />
				<col width="80px" />
				<thead>
					<tr class="active">
					<th>序号</th>
					<th><?php echo isset($table_alias)?$table_alias:"";?>名称</th>
					<th>类型</th>
					<th>状态</th>
					<th>操作</th>
					</tr>
				</thead>
		<tbody>
        <?php foreach($catlist as $key => $item){?>
        <?php if($item['pid'] == $pid){?>
			<tr>
			<td><?php echo isset($item['id'])?$item['id']:"";?>&nbsp;&nbsp;<font class="font-gray">[<?php if($item['sort']){?><?php echo isset($item['sort'])?$item['sort']:"";?><?php }else{?>0<?php }?>]</font></td>
			<td><a href="<?php echo isset($url)?$url:"";?>?pid=<?php echo isset($item['id'])?$item['id']:"";?>" class="f18"><?php echo isset($item['name'])?$item['name']:"";?></a></td>
			<td><?php echo isset($item['type'])?$item['type']:"";?></td>
			<td><img title='' src="<?php echo U("static@/images/admin/");?>status<?php echo isset($item['status'])?$item['status']:"";?>.gif" /></td>
			<td>
			<p class='table-item-op'>
			<a href="<?php echo isset($url)?$url:"";?>?do=edit&id=<?php echo isset($item['id'])?$item['id']:"";?>"><img src="<?php echo U("static@/images/btn_icon/icon_edit.gif");?>" /></a>
			<a href="javascript:void(0);" data-url="<?php echo isset($url)?$url:"";?>?do=del&id=<?php echo isset($item['id'])?$item['id']:"";?>" class="t-ajax-button table-item-op-del"><img src="<?php echo U("static@/images/btn_icon/icon_del.gif");?>" /></a>
			</p>
			</td>
			</tr>
		<?php }?>
		<?php }?>
		</tbody>
</table>
</div>
</div>
<div class="cl"></div></div>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/layer/layer.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/easyTemplate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.init.admin.js");?>"></script>
<script language="javascript" src="<?php echo U("static@javascript/jquery.zclip/jquery.zclip.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<script language="javascript">
  $(".table-item-op-del").each(function(){
  	$(this).data("data-before",function(){
  		return confirm("您确定要删除该条信息吗?");
  	})
  })
</script>
<script language="javascript">
 $(function(){
     laydate.skin('molv');      
  };
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