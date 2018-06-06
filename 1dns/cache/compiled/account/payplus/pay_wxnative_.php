<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>微信扫码支付</title>
<link rel="stylesheet" type="text/css" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/WeixinPay.css";?>">
<script language="javascript" type="text/javascript" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/js/JQuery132.js";?>"></script>
<script language="javascript" type="text/javascript">
var is_tips = true;
$(function() {
    var e = $("#qr_box");
    var c = $("#guide");
    c.css({
        left: "50%",
        opacity: 0
    });
    e.hover(function() {
        c.css("display", "block").stop().animate({
            marginLeft: "+156px",
            opacity: 1
        },
        900, "swing", 
        function() {
            c.animate({
                marginLeft: "+143px"
            },
            300)
        })
    },
    function() {
        c.stop().animate({
            marginLeft: "-101px",
            opacity: 0
        },
        "400", "swing", 
        function() {
            c.hide()
        })
    });
    window.onbeforeunload = function(){
        if(is_tips){
            return '您还没有支付成功，确定要关闭页面吗？';
        }
    }
    var InterValObj;
    InterValObj = window.setInterval(function(){
            $.ajaxSetup({
                async: false
            });
            $.getJSON("<?php echo U("/payplus/pay_wxnative_check");?>",
                {"Ordno":"<?php echo isset($recharge_row['recharge_no'])?$recharge_row['recharge_no']:"";?>"},
                function(res){
                    if(res.error == 0 && res.callback){
                        is_tips = false;
                        setTimeout(function(){
                            window.location.replace(res.callback);
                        },500);                        
                    }
                }
            );
        }, 2000);
});
</script>
</head>
<body>﻿
    <div class="wx_header">
        <div class="wx_logo"><img title="微信支付" alt="微信支付标志" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/wxlogo_pay.png";?>"></div>
    </div>
    <div class="weixin">
        <div class="weixin2">
            <b class="wx_box_corner left pngFix"></b><b class="wx_box_corner right pngFix"></b>
            <div class="wx_box pngFix">
                <div class="wx_box_area">
                    <div class="pay_box qr_default">
                        <div class="area_bd">
                        <div class="wx_img_wrapper" id="qr_box">
                           <div align="center" id="qrcode" class="ewm_wrapper"></div>
                        </div>
                        <img style="left: 50%; opacity: 0; display: none; margin-left: -101px;" class="guide pngFix" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/wxwebpay_guide.png";?>" alt="" id="guide">
                        
                            <div class="msg_default_box"><i class="icon_wx pngFix"></i>
                                <p>
                                    请使用微信扫描<br>
                                    二维码以完成支付
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wx_hd">
                    <div class="wx_hd_img icon_wx"></div>
                </div>
                <div class="wx_money"><span>￥</span><?php echo isset($recharge_row['amount'])?$recharge_row['amount']:"";?></div>
                <!--支付订单号-->
                <div class="wx_pay">
                    <p><span class="wx_left">支付订单号</span>
					<span id="dingdan" ddcode="<?php echo isset($recharge_row['recharge_no'])?$recharge_row['recharge_no']:"";?>" class="wx_right"><?php echo isset($recharge_row['recharge_no'])?$recharge_row['recharge_no']:"";?></span></p>
					 <p><span class="wx_left">订单时间</span>
					<span class="wx_right"><?php echo tTime::get_datetime('Y-m-d H:i:s',$recharge_row['dateline']);?></span></p>
                    <p><span class="wx_left">商品名称</span>
                    <?php if($recharge_row['order_no'] && $recharge_row['inpay'] == 1){?>
                    <span class="wx_right">支付订单:<?php echo isset($recharge_row['order_no'])?$recharge_row['order_no']:"";?></span>
                    <?php }else{?>
                    <span class="wx_right">在线充值</span>
                    <?php }?>
                    </p>
                </div>
                <div class="wx_kf">
                    <div class="wx_kf_img icon_wx"></div>
                    <div class="wx_kf_wz">
                        <p><?php echo isset($site['site_name'])?$site['site_name']:"";?></p>
                        <p><?php echo isset($site['tel'])?$site['tel']:"";?></p>
                    </div>
                </div>
            </div>
        </div>
    </div> 

	<script type="text/javascript" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/js/qrcode.js";?>"></script>
	<script type="text/javascript">
		if(1)
		{
			var url = "<?php echo isset($code_url)?$code_url:"";?>";
			//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
			var qr = qrcode(10, 'M');
			qr.addData(url);
			qr.make();
			var wording=document.createElement('p');
			wording.innerHTML = "支付完成前，请勿关闭此页面！";
			var code=document.createElement('DIV');
			code.innerHTML = qr.createImgTag();
			var element=document.getElementById("qrcode");
			element.appendChild(wording);
			element.appendChild(code);
		}
	</script>


<?php echo isset($code_url)?$code_url:"";?>
</body>
</html>