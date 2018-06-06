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
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">

<link rel="stylesheet" href="<?php echo U("/assets/css/amazeui.min.css");?>">
<link rel="stylesheet" href="<?php echo U("static@/css/style_login.css");?>"></head>
<body>
<header class="am-topbar am-topbar-inverse am-topbar-fixed-top">
    <div class="am-container m-width">
        <h1 class="am-topbar-brand">
            <a href="<?php echo U("home@/");?>" class="am-text-ir">八戒DNS</a>
        </h1>
    </div>
</header>
<div class="dis15"> </div>
<div class="am-container m-width">
    <div class="am-btn-group am-btn-group-justify">
      <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>1</b>填写账户名</a>
      <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>2</b>验证身份</a>
      <a class="am-btn am-btn-secondary am-disabled " href="javascript:void(0)" role="button"><b>3</b>设置新密码</a>
      <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>4</b>完成</a>
    </div>
    <div class="dis30"></div>
    <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
        <form method="post" class="am-form" id="Ffindpass">
        <fieldset>
          
         <div class="am-form-group am-form-icon">
          <i class="am-icon-lock"></i>
          <input type="password" class="am-form-field" id="Mupass" name="upass" minlength="6" minlength="60" data-validation-message="请输入正确的密码" placeholder="输入密码" required/>
          </div>
          
          <div class="am-form-group am-form-icon">
          <i class="am-icon-lock"></i>
          <input type="password" class="am-form-field" id="Mupass2" name="upass2" minlength="6" minlength="60" data-validation-message="请输入确认密码" placeholder="输入确认密码" required/>
         </div>
         <div class="am-cf"></div>

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
<script src="<?php echo U("/assets/js/amazeui.min.js");?>"></script>
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
/*-------------------------------------------*/
//提交
$('#Ffindpass').validator({
        onValid: function(validity) {
        },
        onInValid: function(validity) {
        },
        submit:function(){
          var formValidity = this.isFormValid();
          if(formValidity === true){
            $("#Msubmit").button('loading');
            $.ajaxPassport({
              url:"<?php echo U("/login/findpass3");?><?php echo isset($uri)?$uri:"";?>",
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
$(function(){

})
</script> 
</body>
</html>
