<script type="text/template" id="tpl_wxbd">
<div class="weixin-bd-pop">
	<div class="weixin-body">
		<div class="weixin-img"><img src="<?php echo U("/ucenter/qrcode");?>" width="300" height="300"></div>
		<div class="weixin-intro">
			<div class="weixin-title">绑定微信,更方便,更安全</div>
		    请使用微信扫描图中二维码<br/>
		    关注后账号即自动绑定成功<br/>
		    如果您已经关注“八戒DNS”，请再次扫描二维码完成绑定<br/>
		    如果您的微信号已经绑定平台其他账号,请先解除绑定，或者重新关注
		</div>
		<div class="am-cf"></div>
	</div>
</div>
</script>
<script type="text/javascript">
$(function(){
	var wx_bd_interid = 0;
	$(".am-uc-right").append("<button type='button' class='wx-bd-btn' title='微信绑定'></button>");
	$(".am-uc-right").find(".wx-bd-btn").unbind("click").bind("click",function(){
		$.ui.open($("#tpl_wxbd").html(),function(){
			$.ui.open_close();
			window.clearInterval(wx_bd_interid);
		},790,340);
		//clearInterval
		wx_bd_interid = window.setInterval(function(){
            $.ajaxPassport({
                url:"<?php echo U("/ucenter/wxbd?do=check");?>",
                success:function(res){
                    if(res.error == 0){
                        $.ui.open_close();
                        $.redirect("reload");
                    }
                }
            });
        }, 2000);
	});
})
	
</script>