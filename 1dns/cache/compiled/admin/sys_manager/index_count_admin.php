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
    
<style>
</style>
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
<div class="uc-index-row1">
   <div class="uc-userinfo">
       <!--<div class="avatar"><a class="tiptitle" data-content="点击修改头像" href="<?php echo U("account@/ucenter/profile_avatar");?>" target="_blank"><img class="myavatar_50" width="50" height="50" src="<?php echo tFun::avatar($uid);?>" /></a> </div>-->
       <div class="avatar"><a class="tiptitle"  href="javascript:void 0;" target="_blank"><img class="myavatar_50" width="50" height="50" src="<?php echo tFun::avatar($uid);?>" /></a> </div>
       <div class="info"><h1>Hi! <span class="font-org f24"><?php echo tUtil::substr($this->userinfo['name'],3);?></span>&nbsp;&nbsp;<a href="<?php echo U("account@/ucenter/profile_basic");?>" class="font-blue">修改</a></h1>
       <h2 class="f12 font-gray">问题联系QQ：415480171
       </h2>
       </div>
       <div class="op">
          <?php if($this->check_purview('/domain_manager/domain')){?>
          <a href="<?php echo U("/domain_manager/domain");?>" class="btn btn-default btn-xs">域名管理</a>&nbsp;
          <?php }?>
           <?php if($this->check_purview("/sys_manager/index_weixinport")){?>
           <a target="_blank" href="<?php echo U("/sys_manager/set_wxmenu");?>" target="_blank" class="btn btn-default btn-xs">微信菜单生成</a>&nbsp;
           <?php }?>
           <div class="dis5"></div>
           <?php if($this->check_purview("/sys_manager/index_import")){?>
           <a target="_blank" style="display:none;" href="<?php echo U("/import_manager/importsdsd");?>" target="_blank" class="btn btn-default btn-xs">老版数据导入</a>&nbsp;
           <?php }?>
           <?php if($this->check_purview("/sys_manager/index_batchrash")){?>
           <a target="_blank" style="display:none;" href="<?php echo U("/import_manager/domain_refresh");?>" target="_blank" class="btn btn-default btn-xs">域名批量刷新</a>&nbsp;
           <?php }?>
          </p>
       </div>
       <a type="button" href="<?php echo U("sys_manager/index");?>" class="btn btn-warning btn-sm" style="float: right"><cite class="glyphicon glyphicon-align-justify"></cite> 切换统计数据</a>
       <div class="cl"></div>
   </div>
   <div class="sysnotify">

   </div>
   <div class="cl"></div>
</div>

<?php //年份,月份
$month = R("month","int");
$year = R("year","int");
$month = $month?$month:intval(tTime::get_datetime("m"));
$year = $year?$year:intval(tTime::get_datetime("Y"));

?>
<script language="javascript" src="<?php echo U("static@/javascript/highcharts/highcharts.js");?>"></script>
<div class="" style="padding: 15px 30px 0px 30px">
<div class="headbar">
    <div class="operating">
	<span class="form">
	<select name="year" id="Myear" class="form-control" style="width: 70px;display: inline-block">
        <option value="2016" <?php if($year == 2016){?>selected="selected"<?php }?>>2016</option>
        <option value="2017" <?php if($year == 2017){?>selected="selected"<?php }?>>2017</option>
        <option value="2018" <?php if($year == 2018){?>selected="selected"<?php }?>>2018</option>
        <option value="2019" <?php if($year == 2019){?>selected="selected"<?php }?>>2019</option>
        <option value="2020" <?php if($year == 2020){?>selected="selected"<?php }?>>2020</option>
    </select>&nbsp;
	<select name="month" id="Mmonth" class="form-control"  style="width: 70px;display: inline-block">
        <option value="1" <?php if($month == 1){?>selected="selected"<?php }?>>1</option>
        <option value="2" <?php if($month == 2){?>selected="selected"<?php }?>>2</option>
        <option value="3" <?php if($month == 3){?>selected="selected"<?php }?>>3</option>
        <option value="4" <?php if($month == 4){?>selected="selected"<?php }?>>4</option>
        <option value="5" <?php if($month == 5){?>selected="selected"<?php }?>>5</option>
        <option value="6" <?php if($month == 6){?>selected="selected"<?php }?>>6</option>
        <option value="7" <?php if($month == 7){?>selected="selected"<?php }?>>7</option>
        <option value="8" <?php if($month == 8){?>selected="selected"<?php }?>>8</option>
        <option value="9" <?php if($month == 9){?>selected="selected"<?php }?>>9</option>
        <option value="10" <?php if($month == 10){?>selected="selected"<?php }?>>10</option>
        <option value="11" <?php if($month == 11){?>selected="selected"<?php }?>>11</option>
        <option value="12" <?php if($month == 12){?>selected="selected"<?php }?>>12</option>
    </select>&nbsp;
	<button class="btn btn-default btn-sm operating_btn" onclick="$.redirect('<?php echo U("/sys_manager/index_count?year=");?>'+$('#Myear').val()+'&month='+$('#Mmonth').val())" type="button">查看</button>
	</span>
    </div>
</div>
<hr/>
<div class="list content">
    <div class="fl" style="width:1050px;margin-left: 25px">
        <script type="text/javascript" src="<?php echo U("/sys_manager/index_sys_count_image?t=user_reg_month&w=1000&h=260&month=$month&year=$year");?>"></script>
    </div>
    <div class="dis40"></div>
    <div class="fl" style="width:1050px;margin-left: 25px">
        <script type="text/javascript" src="<?php echo U("/sys_manager/index_sys_count_image?t=user_reg_year&w=1000&h=260&month=$month&year=$year");?>"></script>
    </div>
    <div class="dis40"></div>
    <div class="cl"></div>
    <div class="fl" style="width:1050px;margin-left: 25px">
        <script type="text/javascript" src="<?php echo U("/sys_manager/index_sys_count_image?t=user_account_month&w=1000&h=260&month=$month&year=$year");?>"></script>
    </div>
    <div class="dis40"></div>
    <div class="fl" style="width:1050px;margin-left: 25px">
        <script type="text/javascript" src="<?php echo U("/sys_manager/index_sys_count_image?t=user_account_year&w=1000&h=260&month=$month&year=$year");?>"></script>
    </div>
    <div class="dis10"></div>
    <div class="dis30"></div>
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
<script language="javascript" src="<?php echo U("static/cache/dataconfig.js");?>"></script>
<?php echo $this->fetch('tpl/form')?>

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