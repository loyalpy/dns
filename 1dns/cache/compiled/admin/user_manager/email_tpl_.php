<!-- 自定义邮件发送start -->
<script type="text/template" id="tpl_send_email">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class="form-content diy-email-admin">
			<div class="dis20"></div>
			<table class="table">
				<col width="80px"/>
				<col />
				<tr>
					<td><strong>发送对象：</strong></td>
					<td>
						<textarea class="form-control" rows="6" name="email" placeholder="111@qq.com\n222@qq.com">${data.email}</textarea>
						<span class="font-gray">多个邮箱用换行，或者逗号，或者分号隔开</span>
					</td>
				</tr>
				<tr>
					<td><strong>发件模板：</strong></td>
					<td>
						<select id="Msel" class="form-control">
							<option value="0">[八戒DNS]八戒DNS推广</option>
							<option value="1">[八戒DNS]未提交邮箱认证通知</option>
							<option value="2">[八戒DNS]八戒DNS技术发来的调查邮件</option>
							<option value="3">[八戒DNS]还差最后一步,就能使用八戒智能DNS</option>
							<option value="4">[八戒DNS]你好，由于您的域名是私服性质可能存在攻击，标准套餐不提供高防</option>
							<option value="5">[八戒DNS]您好，您在八戒DNS有部分域名套餐已经到期，为不影响您的使用请及时续费</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><strong>发送标题：</strong></td>
					<td>
						<input type="text" name="title" class="form-control" value=""/>
					</td>
				</tr>
				<tr>
					<td><strong>发送内容：</strong></td>
					<td><textarea class="form-control" name="content" rows="8" placeholder=""></textarea></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button type="button" class="btn btn-warning send-email-diy">立即发送</button>&nbsp;&nbsp;&nbsp;
						<button type="button" class="btn btn-default send-close" data-dismiss="modal">关闭</button>
					</td>
				</tr>
			</table>
			<div class="dis30"></div>
		</div>
	</#macro>
</script>
<script language="javascript">
	var send_email_usual = function(obj,batch_email){
		var email = $(obj).data("email");
		if (typeof batch_email != "undefined") {
			email = batch_email;
		}
		var edit_c = $("#tpl_send_email").html();
		edit_c = "" + easyTemplate(edit_c,{title:"自定义邮件发送",email:email});
		$("#myModal").find(".modal-dialog").width(850);
		$("#myModal").find(".modal-content").html(edit_c);
		$('#myModal').modal();

		//模板切换
		select_c("0");
		$("#Msel").change(function () {
			var tpl = $(this).val();
			select_c(tpl);
		});

		//发送邮件：自定义
		$(".send-email-diy").click(function () {
			var email = $("textarea[name='email']").val();
			var title = $("input[name='title']").val();
			var content = $("textarea[name='content']").val();

			$.ui.loading();
			$.ajaxPassport({
				url:"<?php echo U("user_manager/userlist_send_email");?>",
				success:function (res) {
					$.ui.close_loading();
					if (res.error == 0) {
						$.ui.success(res.message);
						setTimeout(function () {
							$(".send-close").click();
						},1000)
					}else{
						$.ui.error(res.message);
					}
				},
				data:{email:email,title:title,content:content}
			});
		});
	}
	var select_c = function (tpl) {
		switch (tpl){
			case "0":
				var $t = "[八戒DNS]免费智能DNS解析新推域名注册服务";
				var $c = "八戒DNS新推域名注册，pw 域名只需4元，部分域名首年免费。\r\n立即登录    详情咨询QQ  613988298";
				break;
			case "1":
				var $t = "[八戒DNS]未提交邮箱认证通知";
				var $c = "我们高兴的通知您，您还差最后一步就能使用八戒DNS,请您前往八戒DNS官网完成邮箱认证即可！";
				break;
			case "2":
				$t = "[八戒DNS]八戒DNS技术发来的调查邮件";
				$c = "您好，我们是八戒DNS的技术部，您以前有使用过我们的DNS，但是后来又转走了不知道你是由于什么原因转走，希望能跟交流沟通下，我们也好听取您的意见改善提升我们的产品，在此先谢谢；有问题请加QQ:613988298";
				break;
			case "3":
				$t = "[八戒DNS]还差最后一步,就能使用八戒智能DNS";
				$c = "您好，感谢您支持八戒DNS，还差最后一步您就能使用我们的安全稳定的DNS服务，请到您的域名注册商修改您的DNS设置";
				break;
			case "4":
				$t = "[八戒DNS]你好，由于您的域名是私服性质可能存在攻击，标准套餐不提供高防";
				$c = "你好，由于您的域名是私服性质的网站，可能存在攻击，我们标准套餐不提供高防，如果攻击严重，我将会对你的域名做（停止解析）处理，如需购买高防套餐请联系我们的八戒DNS客服QQ:613988298";
				break;
			case "5":
				$t = "[八戒DNS]您好，您在八戒DNS的域名套餐已经到期，为不影响您的使用请及时续费";
				$c = "您好,您在八戒DNS有部分域名套餐已经到期,我们会在一定时间自动切换到免费套餐,为不影响您的使用请及时续费";
				break;
			default:
				$t = "";
				$c = "";
				break;
		}
		$("input[name='title']").val($t);
		$("textarea[name='content']").val($c);
	}
</script>
<!-- 自定义邮件发送end -->