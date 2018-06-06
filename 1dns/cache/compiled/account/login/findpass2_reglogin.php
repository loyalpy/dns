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
      <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>1</b>填写账户名</a>
      <a class="am-btn am-btn-warning " href="javascript:void(0)" role="button"><b>2</b>验证身份</a>
      <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>3</b>设置新密码</a>
      <a class="am-btn am-btn-default am-disabled " href="javascript:void(0)" role="button"><b>4</b>完成</a>
    </div>
    <div class="dis30"></div>
    <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <?php if($find['type'] == 'email'){?>
    <?php $go_email = array(
        "163.com"      => "mail.163.com",
        "vip.163.com"  => "vip.163.com",
        "126.com"      => "mail.126.com",
        "126.com"      => "mail.126.com",
        
        'qq.com'       => "mail.qq.com",
        'vip.qq.com'   => "mail.qq.com",
        'foxmail.com'  => "mail.qq.com",
        
        'gmail.com'    => "mail.google.com",
        'sohu.com'     => "mail.sohu.com",
        'tom.com'      => "mail.tom.com",
        'vip.sina.com' => "vip.sina.com",
        'sina.com'     => "mail.sina.com.cn",
        'sina.com.cn'  => "mail.sina.com.cn",
        
        'yahoo.com.cn' => "mail.cn.yahoo.com",
        'yahoo.cn'     => "mail.cn.yahoo.com",
        'yeah.net'     => "www.yeah.net",
        '21cn.com'     => "mail.21cn.com",
        
        'hotmail.com'  => "www.hotmail.com",
        'sogou.com'    => "mail.sogou.com",
        '188.com'      => "www.188.com",
        '139.com'      => "mail.10086.cn",
        '189.cn'       => "webmail15.189.cn/webmail",
        'wo.com.cn'    => "mail.wo.com.cn/smsmail",
    );?>
    <?php $etype = explode("@",$find['name']);?>
    <?php $gourl = isset($go_email[$etype[1]])?$go_email[$etype[1]]:("www.".$etype[1])?>
    <div class="phone_verifica_wrap">
        <h2 id="text_change">我们已将验证邮件发动到邮箱：<span><?php echo substr($find['name'],0,3);?>**@**<?php echo substr($find['name'],-4);?></span> <br/>请查看并点击验证, 
                    <a href="http://<?php echo isset($gourl)?$gourl:"";?>" class="font-blue f14 am-btn am-btn-success am-btn-xs" target="_blank">前往邮箱验证</a></h2>
        <div class="regist_form phone_verifica_form">
        </div>
    </div>    
    <?php }else{?>
    <div class="phone_verifica_wrap">
        <h2 id="text_change" class="am-text-sm">请点击获取验证码并在手机：<span><?php echo substr($find['name'],0,3);?>****<?php echo substr($find['name'],-4);?></span> 中查看短信，并填写验证码</h2>
        <div class="dis20"></div>
        <form method="post" class="am-form" id="Ffindpass">
          <fieldset>
          <div class="am-form-inline">
          <div class="am-input-group am-fl" style="margin-right:12px;">
          <input type="text" style="width:150px;" class="am-form-field" id="Mcode" name="code" minlength="6" minlength="60" placeholder="请输入验证码" required/>
          </div>
          <button type="button" id="btnSendCode" class="am-btn am-btn-default am-fr">获取验证码</button>
          <div class="am-cf"></div>        
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
    <?php }?>
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
/*-------------------------------------------*/
<?php if($find['type'] == 'mobile'){?>
var InterValObj; //timer变量，控制时间  
var count = 120; //间隔函数，1秒执行  
var curCount;    //当前剩余秒数  
function sendMessage() {    
    //设置button效果，开始计时  
    $("#btnSendCode").attr("disabled", "true").addClass("font-gray");
    //向后台发送处理数据  
    $.ajaxPassport({
    	url:"<?php echo U("/misc/send_sms");?><?php echo tHash::uri(array('dateline'=>$timestamp,'tpl'=>'findpass'));?>",
    	data:{"mobile":"<?php echo isset($find['name'])?$find['name']:"";?>"},//
    	success:function(res){
    		if(res.error == 0){
          $.ui.success("发送成功!");
    			curCount = count;  
    			$("#btnSendCode").html("重获验证码(" + curCount + ")").addClass("font-gray");//;  
    		    InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次  
    		}else{
          $("#btnSendCode").removeAttr("disabled").removeClass("font-gray");
    			$.ui.error(res.message);
    		}
    	}
    });
}  
//timer处理函数  
function SetRemainTime() {  
    if (curCount == 0) {                  
        window.clearInterval(InterValObj);//停止计时器  
        $("#btnSendCode").removeAttr("disabled").removeClass("font-gray");//启用按钮  
        $("#btnSendCode").html("获取验证码");  
        code = ""; //清除验证码。如果不清除，过时间后，输入收到的验证码依然有效      
    } else {  
        curCount--;  
        $("#btnSendCode").html("重获验证码(" + curCount + ")"); 
    }  
}

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
              url:"<?php echo U("/login/findpass2");?><?php echo isset($uri)?$uri:"";?>",
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
    $("#btnSendCode").bind("click",function(){
        sendMessage();
    });
})
<?php }?>
</script> 
</body>
</html>
