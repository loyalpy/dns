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
  <div class="name">站点配置</div>
  <div class="navbtn"><?php if($this->userinfo['urole'] == 1){?>
  <!--button-->
  <button type="button" data-url="/sys_manager/flush_cache" class="btn btn-danger btn-sm t-ajax-button" data-loading-text="清除整站缓存……">&nbsp;<cite class="glyphicon glyphicon-refresh"></cite>&nbsp;清除整站缓存</button>
  <!--end button-->
  <?php }?></div>
  <div class="cl"></div>
</div>
<div class="form-content">
<form action="<?php echo U("/sys_manager/sys_config");?>" method="post" class="theform form" role="form">
<div class="tpl"></div>
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
<?php echo $this->fetch('tpl/form')?>
<script language="javascript">
var formdata = {
	site_name:{type:"text",label:"站点名称",name:"data[site_name]",value:"",data_sr:[],css:"",require:"datatype='*'",desc:"站点名称必须填写",item_css:""},
	site_domain:{type:"text",label:"站点域名",name:"data[site_domain]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	site_dir:{type:"text",label:"站点目录",name:"data[site_dir]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	seo_title:{type:"text",label:"站点标题",name:"data[seo_title]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	seo_keyword:{type:"text",label:"站点关键词",name:"data[seo_keyword]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	seo_description:{type:"text",label:"站点描述",name:"data[seo_description]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	style:{type:"text",label:"默认样式",name:"data[style]",value:"default",data_sr:[],css:"",require:"",desc:"",item_css:""},
	top_tel:{type:"text",label:"联系电话",name:"data[top_tel]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	tel:{type:"text",label:"咨询电话/400电话",name:"data[tel]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	qq:{type:"text",label:"咨询QQ",name:"data[qq]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	qq1:{type:"text",label:"售前QQ",name:"data[qq1]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	qq2:{type:"text",label:"售后QQ",name:"data[qq2]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	kh:{type:"textarea",label:"口号",name:"data[kh]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	shui:{type:"text",label:"充值开票税点",name:"data[shui]",value:"",data_sr:[],css:"",require:"",desc:"1-100之间",item_css:""},
	//order_expiry:{type:"text",label:"订单失效时间",name:"data[order_expiry]",value:"0",data_sr:[],css:"low",require:"",desc:" 分钟",item_css:""},
	copyright:{type:"textarea",label:"版权信息",name:"data[copyright]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	icp:{type:"text",label:"备案号",name:"data[icp]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	licence:{type:"text",label:"许可证",name:"data[licence]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	online_qq:{type:"textarea",label:"在线QQ",name:"data[online_qq]",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	reg_captcha:{type:"radio",label:"注册验证码",name:"data[reg_captcha]",value:"",data_sr:[{v:"否",key:0},{v:"是",key:1}],css:"",require:"",desc:"",item_css:""},
	log_captcha:{type:"radio",label:"登录验证码",name:"data[log_captcha]",value:"",data_sr:[{v:"否",key:0},{v:"是",key:1}],css:"",require:"",desc:"",item_css:""},
	is_close:{type:"radio",label:"临时关闭",name:"data[is_close]",value:"",data_sr:[{v:"否",key:0},{v:"是",key:1}],css:"",require:"",desc:"",item_css:""},
	//log_captcha:{type:"radio",label:"登录验证码",name:"data[log_captcha]",value:"",data_sr:[{v:"否",key:0},{v:"是",key:1}],css:"",require:"",desc:"",item_css:""},
	//reg_captcha:{type:"radio",label:"注册验证码",name:"data[reg_captcha]",value:"",data_sr:[{v:"否",key:0},{v:"是",key:1}],css:"",require:"",desc:"",item_css:""},
	<?php if($this->check_purview('/sys_manager/sys_config_save')){?>
	btn:{type:"button",label:"",value:"保存修改",desc:""}
	<?php }?>
};
$(function(){
	$.loadform(formdata,"<?php echo U("/sys_manager/sys_config?do=get");?>");
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