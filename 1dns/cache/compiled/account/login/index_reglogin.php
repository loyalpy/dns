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
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."//css/style_login.css";?>">
<style type="text/css">
	body{background: #4c97ed;}
</style>
</head>
<body>
<!--header 开始-->
<div class="header" >
  <div class="header_main mt0 aps">
    <div class="logo fl"><a href="<?php echo U("home@/");?>"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>"></a></div>
    <div class="logotit fl">登录</div>
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
<div class="dis30"></div>
<div id="loginpage">

	<div class="img-ad-left">
		<?php $ad = tCache::read("ad_3");?>
		<?php foreach($ad as $key => $item){?>
		<img src="<?php echo U("static@/$item[imgurl]");?>"/>
		<?php }?>
	</div>

	<div class="am-u-lg-4 am-u-md-8 am-u-sm-centered img-ad-right">
		<div class="am-panel">
			<div class="am-panel-bd">
				<form method="post" class="am-form" id="Flogin">
					<fieldset>
						<div class="am-text-center am-form-title lagend-login">
							<a href="javascript:void(0)" class="l cur" data-type="user">账号登陆</a>
							<a href="javascript:void(0)" class="r" data-type="wx">微信登陆</a>
						</div>
						<div class="utips" style="display:none;"></div>
						<div class="loginbox">
							<div class="user-box">
								<div class="am-input-group" >
									<span class="am-input-group-label" style="font-size: 16px;"><i class="am-icon-user am-icon-fw"></i></span>
									<input type="text" class="am-form-field" style="height: 39px;" id="Muname" name="uname" minlength="2" minlength="60" placeholder="输入用户名/手机/邮箱" required/>
								</div>
								<div class="dis15"></div>
								<div class="am-input-group">
									<span class="am-input-group-label" style="font-size: 16px;"><i class="am-icon-lock am-icon-fw"></i></span>
									<input type="password" class="am-form-field" style="height: 39px;" id="Mupass" name="upass" minlength="6" minlength="60" data-validation-message="请输入正确的密码" placeholder="输入密码" required/>
								</div>

								<div class="am-checkbox">
									<label>
										<input id="Mrecord" name="record" value="1" type="checkbox" /> 一个月内自动登录
									</label>
								</div>
								<div class="dis20"></div>
								<div class="am-cf">
									<input value="<?php echo tUtil::hash();?>" name="hash" type="hidden" />
									<input value="<?php echo isset($login_refer)?$login_refer:"";?>" name="refer" type="hidden" />
									<input type="submit" data-am-loading="{loadingText: '登录中...'}" style="font-size: 16px" id="Msubmit" value="登 录" class="am-btn am-btn-warning am-btn-block am-radius">
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
						<span  style="font-size: 12px">还没账号,</span> <a href="<?php echo U("/register");?>" style="font-size: 12px">
						立即注册&nbsp;<i class="am-icon-angle-right"></i></a><br/>
						<a href="<?php echo U("/login/findpass");?>" style="font-size: 12px">忘记密码&nbsp;<i class="am-icon-angle-right"></i></a>
					</div>
				</form>
			</div>
		</div>
	</div>

</div><div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
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
		    			url:"<?php echo U("/login");?>",
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
var InterValObj;
    $(function(){
        InterValObj = window.setInterval(function(){
            $.ajaxPassport({
                url:"<?php echo U("/login/checklogin?do=check");?>",
                success:function(res){
                    if(res.error == 0){
                        if(res.message == "bdsuccess"){
                        	$(" .utips").addClass("utips-success").html("微信已扫描登陆成功!跳转中...").show();

                        	setTimeout(function(){
                        		$.redirect("/ucenter");
                        	},1000);	
                        }else if(res.message == "nobd"){
                        	$(" .utips").addClass("utips-error").html("微信已扫描,尚未绑定帐号,请先登录").show();

                        	$(".am-form-title").find("a").eq(0).click();
                        }else if(res.message == "bdlock"){
                        	$(" .utips").addClass("utips-error").html("微信已扫描,账号存在异常被系统锁定").show();
                        	$(".am-form-title").find("a").eq(0).click();
                        }
                    }
                }
            });
        }, 3000);
    })
</script>
</body>
</html>
