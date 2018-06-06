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

<link rel="stylesheet" href="<?php echo U("static/javascript/amazeui/css/amazeui.min.css");?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_records.css";?>"></head>
<body>
<!-- topbar -->
<div class="topbar">
  <div class="aps">
    <div class="top-left-nav">
    <ul>
    <li>
    <a href="<?php echo U("home@/");?>" class="logo"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>" alt="八戒DNS" /></a>
    </li>
    <li><a href="#">域名解析管理平台</a></li>
    </ul>
    </div>
    <div class="top-domain-search">
    </div>
    <div class="top-right-nav">
    <ul>

      <li class="am-dropdown setting" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-globe"></span> <?php echo isset($domain_row['domain'])?$domain_row['domain']:"";?> <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="<?php echo U("/records/logout");?>"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>

    </ul>
    </div>
  </div>
</div>
<!-- end topbar -->
<div class="dis60"></div>
  <div class="am-u-lg-4 am-u-md-8 am-u-sm-centered">
    <section class="am-panel">
  	<main class="am-panel-bd">
    <form method="post" class="am-form" id="Flogin">
      <fieldset>
      <legend class="am-text-center am-form-title">
      <a href="javascript:void(0)" class="l cur" data-type="user">域名解析管理平台</a>
      </legend>
      <div class="utips" style="display:none;"></div>
      <div class="loginbox">        
        <div class="user-box">          
	      <div class="am-input-group">
	      <span class="am-input-group-label"><i class="am-icon-globe am-icon-fw"></i></span>
	      <input type="text" class="am-form-field" id="Mdomain" name="domain" minlength="3" minlength="60" placeholder="请输入域名" required/>
	      </div>
	      <div class="dis15"></div>
	      <div class="am-input-group">
	      <span class="am-input-group-label"><i class="am-icon-lock am-icon-fw"></i></span>
	      <input type="password" class="am-form-field" id="Mupass" name="dpass" minlength="6" minlength="60" data-validation-message="请输入域名管理密码" placeholder="输入密码" required/>
	      </div>
	      
	      <div class="am-checkbox">
	      <label>
	        <input id="Mrecord" name="record" value="1" type="checkbox" /> 一个月内自动登录
	      </label>
	      </div> 
	    <div class="dis20"></div>
	      <div class="am-cf">
	        <input value="<?php echo tUtil::hash();?>" name="hash" type="hidden" />
	        <input type="submit" data-am-loading="{loadingText: '登录中...'}" id="Msubmit" value="登 录" class="am-btn am-btn-warning am-btn-block am-radius">
	      </div>
	      <div class="dis10"></div>
	      <div class="am-cf">
	        
	      </div>
	   </div>
	   <div class="wx-box" style="display:none;text-align: center;">
	      <img src="<?php echo SDKwx::get_login_url(tSafe::get_id());?>" /> 
	   </div>

	   </div>
      </fieldset>
      <div class="am-text-right">
		<span class="am-text-xs"></span> <a href="<?php echo U("account@/register");?>" class="am-text-xs">
	        注册八戒DNS&nbsp;<i class="am-icon-angle-right"></i></a>		
	  </div>
    </form>
	</main>
	</section>
	
</div>
<div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
    <a href="#top" title="">
          <i class="am-gotop-icon am-icon-chevron-up"></i>
    </a>
</div>
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<!--[if lte IE 8 ]>
<script src="<?php echo U("static/javascript/amazeui/js/modernizr.js");?>"></script>
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.ie8polyfill.min.js");?>"></script>
<![endif]-->
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.min.js");?>"></script>
<script src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>
$(function(){
  $("#navSwitch").bind("click",function(){
    if($("#MainNav").is(':hidden')){
        $("body").css({"padding-left":"198px"});
        $("#MainNav").show();
    }else{
        $("body").css({"padding-left":"10px"});
        $("#MainNav").hide();
    }
  });
})
</script>
<script type="text/javascript">
$(function(){
	 $(".am-form-title").find("a").unbind("click").bind("click",function(){
	 	var obj = this;
	 	var type = $(obj).data("type");
	 	$(".am-form-title").find("a.cur").removeClass("cur");
	 	$(obj).addClass("cur");
	 	$(".loginbox > div").hide();
        $(".loginbox > div."+type+"-box").show();
	 })
	 $('#Flogin').validator({
		    onValid: function(validity) {
		     
		    },
		    onInValid: function(validity) {
		      
		    },
		    submit:function(){
		    	var formValidity = this.isFormValid();
		    	if(formValidity === true){
		    		$("#Msubmit").button('loading');
		    		$.ajaxPassport({
		    			url:"<?php echo U("/records");?>",
		    			data:$(this.$element).serialize(),
		    			type:"post",
		    			success:function(res){
		    				$("#Msubmit").button('reset');
		    				if(res.error == 1){
		    					$.ui.error(res.message);
		    				}else{
		    					$.ui.success(res.message);
								if(res.callback){
									res.callback = res.callback.replace(/&amp;/g,"&");
									setTimeout("window.location.replace('"+res.callback+"')",100);
								}
		    				}
		    			}
		    		})
		    	}
		        return false;
		    }
	});
})
</script>
</body>
</html>