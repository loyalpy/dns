<!doctype html>
<html class="no-js">
<head>
<!--[if lt IE 10]>
<![endif]-->
<!--[if lt IE 7]>
<script>location.href="/ie.html"</script>
<![endif]-->
<!-- page head -->

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">

<link rel="stylesheet" href="<?php echo U("/static/javascript/amazeui/css/amazeui.min.css");?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."//css/style_login.css";?>"></head>
<body>
<!--header 开始-->
<div class="header" >
  <div class="header_main mt0 aps">
    <div class="logo fl"><a href="<?php echo U("home@/");?>"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>"></a></div>
    <div class="logotit fl">找回密码</div>
    <div class="login fr">
      <?php if(empty($uid)){?>
      <a class="button fl" href="<?php echo U("account@/login");?>">登录</a>
      <a class="button fl" href="<?php echo U("account@/register");?>"> 注册</a>
      <?php }else{?>
      <a class="button fl" href="">购物车</a>
      <a class="button fl" href="<?php echo U("account@/ucenter");?>">管理中心</a>
      <?php }?>
    </div>
    <div class="menu fr"> 
      <ul>
        <li><a class="<?php if($inc == "site" && $act == 'index'){?>cur<?php }?>" href="<?php echo U("home@/");?>">首页</a></li>
        <li><a class="<?php if($inc == "product" && $act == 'index'){?>cur<?php }?>" href="<?php echo U("home@/product/index");?>">产品中心</a></li>
        <li><a class="<?php if($inc == "product" && $act == 'buy'){?>cur<?php }?>" href="<?php echo U("home@/product/buy");?>">购买套餐</a></li>
        <li><a href="<?php echo U("home@/");?>">技术支持</a></li>
      </ul>
    </div>
  </div>
</div>
<!--header 结束-->
<div class="dis60"></div>
<div class="dis60"></div>
<div class="am-container m-width">
	<div class="am-btn-group am-btn-group-justify">
	  <a class="am-btn am-btn-warning" href="javascript:void(0)" role="button"><b>1</b>填写账户名</a>
	  <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>2</b>验证身份</a>
	  <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>3</b>设置新密码</a>
	  <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>4</b>完成</a>
	</div>
	<div class="dis30"></div>
    <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
	  <form method="post" class="am-form" id="Ffindpass">
      <fieldset>
      <div class="am-input-group">
      <span class="am-input-group-label"><i class="am-icon-user am-icon-fw"></i></span>
      <input type="text" class="am-form-field" id="Muname" name="uname" minlength="6" minlength="60" placeholder="输入用户名/手机/邮箱" required/>
      </div>
      <div class="dis15"></div>
   
      <div class="am-cf">
        <input value="<?php echo tUtil::hash();?>" name="hash" type="hidden" />
        <input type="submit" data-am-loading="{loadingText: '处理中...'}" id="Msubmit" value="下一步" class="am-btn am-btn-warning am-btn-block">
      </div>
      <div class="dis10"></div>
      <div class="am-cf am-text-right">
        <a href="<?php echo U("/login");?>" class="am-text-xs">
    返回登陆&nbsp;<i class="am-icon-angle-right"></i>&nbsp;&nbsp;
        </a>
      </div>
      </fieldset>
    </form>
    </div>
</div><div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
    <a href="#top" title="">
          <i class="am-gotop-icon am-icon-chevron-up"></i>
    </a>
</div>
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<!--[if lte IE 8 ]>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="<?php echo U("/assets/amazeui.ie8polyfill.min.js");?>"></script>
<![endif]-->
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.min.js");?>"></script>
<script src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<?php if($uid){?>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<?php }?>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>
</script>
<script type="text/javascript">
$('#Ffindpass').validator({
        onValid: function(validity) {
          // $(validity.field).closest('.am-form-group').find('.am-alert').hide();
        },
        onInValid: function(validity) {
          /*var $field = $(validity.field);
          var $group = $field.closest('.am-form-group');
          var $alert = $group.find('.am-alert');
          // 使用自定义的提示信息 或 插件内置的提示信息
          var msg = $field.data('validationMessage') || this.getValidationMessage(validity);

          if (!$alert.length) {
              $alert = $('<div class="am-alert am-alert-danger"></div>').hide().
              appendTo($group);
          }
          $alert.html(msg).show();*/
        },
        submit:function(){
          var formValidity = this.isFormValid();
          if(formValidity === true){
            $("#Msubmit").button('loading');
            $.ajaxPassport({
              url:"<?php echo U("/login/findpass");?>",
              data:$(this.$element).serialize(),
              type:"post",
              success:function(res){
                $("#Msubmit").button('reset');
                if(res.error == 1){
                  $.ui.error(res.message);
                }else{
                  $.ui.success(res.message);
                  if(res.callback == "reload"){
                    setTimeout("window.location.reload()",500);
                  }else if(res.callback == "close"){
                  }else if(res.callback){
                    res.callback = res.callback.replace(/&amp;/g,"&");
                    setTimeout("window.location.replace('"+res.callback+"')",500);  
                  }
                }
              }
            })
          }
            return false;
        }
  });
</script>
</body>
</html>
