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
    <div class="logotit fl">注册</div>
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
	<div class="dis20"></div>
		<?php $ad = tCache::read("ad_4");?>
		<?php foreach($ad as $key => $item){?>
		<img src="<?php echo U("static@/$item[imgurl]");?>"/>
		<?php }?>
	</div>

	<div class="am-u-lg-4 am-u-md-8 am-u-sm-centered img-ad-right">
		<div class="am-panel">
			<div class="am-panel-bd">
				<form method="post" class="am-form" id="Fregister">
					<fieldset>
						<legend class="am-text-center" style="display:none;">欢迎注册……</legend>

						<div class="am-form-group">
							<label for="doc-ipt-email-1"><span class="am-text-sm am-text-fn" style="font-size: 14px">注册邮箱</span></label>
							<input type="email" class="js-email-validate" id="Muemail" style="padding: 6px" name="uname" data-validation-message="请输入正确可用的邮箱" minlength="3" maxlength="80" required  placeholder="输入电子邮件">
						</div>

						<div class="am-form-group">
							<label for="doc-ipt-email-1"><span class="am-text-sm am-text-fn" style="font-size: 14px">输入密码</span></label>
							<input type="password" class="js-eq0-validate" id="Mupass" style="padding: 6px" name="upass" minlength="6" maxlength="18" data-validation-message="请输入6~18位密码"  required placeholder="输入密码">
						</div>
						<div class="am-form-group">
							<label for="doc-ipt-email-1"><span class="am-text-sm am-text-fn" style="font-size: 14px">确认密码</span></label>
							<input type="password" class="js-eq1-validate" id="Mpass2" style="padding: 6px" name="upass2" minlength="6" maxlength="18" data-validation-message="请输入6~18位确认密码"  required  placeholder="输入确认密码">
						</div>


						<div class="am-cf"></div>
						<div class="am-text-left">
							<a href="<?php echo U("home@/cms/onepage/i/about/c/agreement");?>" class="am-text-sm" target="_blank" style="font-size: 14px">八戒DNS域名解析服务协议<i class="am-icon-angle-right"></i></a>
						</div>
						<div class="dis10"></div>
						<div class="am-cf">
							<input value="<?php echo tUtil::hash();?>" name="hash" type="hidden" />
							<button type="submit" data-am-loading="{loadingText: '注册中...等待反馈'}" id="Msubmit" class="am-btn am-btn-warning
        am-btn-block am-text-lg am-radius" style="font-size: 18px">
								<span class="am-text-lg" style="font-size: 18px">同意以上协议并</span><span class="am-text-lg" style="font-size: 18px">注册</span>
							</button>
						</div>
						<div class="dis10"></div>

					</fieldset>
				</form>
				<div class="am-text-right">
					<span class="am-text-xs" style="font-size: 12px">已有账号,</span> <a href="<?php echo U("/login");?>" class="am-text-xs"  style="font-size: 12px">
					立即登录&nbsp;<i class="am-icon-angle-right"></i></a>
				</div>
			</div>
		</div>
		<div class="dis30"></div>
	</div>
	<div class="clear"></div>
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
	 $('#Fregister').validator({
		 	validate: function(validity) {
		 		var v = $(validity.field).val();
		 		if ($(validity.field).is('.js-email-validate')) {
		            if(v.is_email()){
			        	// 异步操作必须返回 Deferred 对象
				        return $.ajax({
				          url: '<?php echo U("/misc/check?ctype=email");?>',
				          data:{param:v},
				          // cache: false, 实际使用中请禁用缓存
				          dataType: 'json'
				        }).then(function(data) {			         
				          if(data.status == 'n'){
				        	  validity.valid = false;
				        	  $(validity.field).data("validation-message",data.info);
				          }else{
				        	  validity.valid = true;
				          }
				          return validity;
				        }, function() {
				          return validity;
				        });
			        }else{
					   	$(validity.field).data("validation-message","请输入正确可用的邮箱");
			        	validity.valid = false;
			        }
		      }
		      // 本地验证，同步操作，无需返回值
		      else if ($(validity.field).is('.js-eq1-validate')) {
		        if (v != $("input.js-eq0-validate").val()) {
		          validity.valid = false;
		        }
		      }
		 	},
		    onValid: function(validity) {
		      $(validity.field).closest('.am-form-group').find('.am-alert').hide();
		    },
		    onInValid: function(validity) {
		      var $field = $(validity.field);
	          var $group = $field.closest('.am-form-group');
	          var $alert = $group.find('.am-alert');
	          // 使用自定义的提示信息 或 插件内置的提示信息
	          var msg = $field.data('validationMessage') || this.getValidationMessage(validity);

	          if (!$alert.length) {
	              //$alert = $('<div class="am-alert am-alert-danger"></div>').hide().appendTo($group);
	          }
	          $.ui.error(msg);
	          //$alert.html(msg).show();
		    },
		    submit:function(){		    	  
		    	  var formValidity = this.isFormValid();
		    	  var $formelement = $(this.$element);
		    	  if(formValidity === false){
		    	  	  return false;
		    	  }else{
			    	  $.when(formValidity).then(function() {
				      	 // 验证成功的逻辑
				      	 $("#Msubmit").button('loading');
				     	 $.ajaxPassport({
			    			url:"<?php echo U("/register");?>",
			    			data:$formelement.serialize(),
			    			type:"post",
			    			success:function(res){
			    				$("#Msubmit").button('reset');
			    				if(res.error == 1){
			    					$.ui.error(res.message);
			    				}else{
			    					setTimeout(function(){
			    						$.redirect("<?php echo U("account@/ucenter/index");?>")
			    					},500);
			    				}
			    			}
			    		});
				    }, function() {
				      	
				    });
			    	return false;
		    	}
		    }
	});
})
</script>
</body>
</html>
