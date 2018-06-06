<!-- 解析域名 start-->
<script type="text/template" id="tpl_domain">
	<#macro rowedit data>
		<div class="modal-header" style="text-align: center">
			<h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
			<div class="dis10"></div>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
				<col width="110px" />
				<col width="140px" />
				<col width="100px" />
				<col width="130px" />
				<col width="130px" />
				<col />
				<col width="175px" />
				<col width="50px" />
				<thead>
				<tr class="active">
					<th>域名</th>
					<th>套餐</th>
					<th>最大QPS</th>
					<th>添加时间</th>
					<th>更新时间</th>
					<th>服务器组</th>
					<th>当前NS</th>
					<th>状态</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as domain>
						<tr>
							<td class="font-blue"><#if (domain.is_cn==1)>${domain.domain_cn}<#else>${domain.domain}</#if></td>
							<td >
								<#if (domain.service_group=='free')>
									<span class="font-gray">
											<#if  (!$.is_empty(service_groups[domain.service_group]))>${service_groups[domain.service_group].name}<#else> - </#if> <br/>[永久]
										</span>
														<#else>
															<span class="font-org" data-domain="${domain.domain}" style="cursor: pointer">[<#if  (!$.is_empty(service_groups[domain.service_group]))>${service_groups[domain.service_group].name}<#else> - </#if>]</span><br/>
											<span class="font-gray2 find-count-domain-t1" data-type="1">[
											<#if (domain.service_expiry_pass)>
												<span class="font-black">${domain.service_expiry}</span><span class="font-red">(${domain.service_expiry_pass})</span>
												<#else>
													<span class="font-green">${domain.service_expiry}</span>
											</#if>
											]</span>
								</#if>
							</td>
							<td>${domain.qps} QPS</td>
							<td class="font-gray">${$.time_to_string(domain.dateline)}</td>
							<td class="font-gray">${domain.lastupdate}</td>
							<td class="font-gray">
								<#if  (!$.is_empty(ns_groups[domain.ns_group]))>
									<a href="javascript:void (0);" data-type="2"  class="<#if (domain.ns_group=='free')>font-gray<#else>font-org</#if> link-alert-x">
															${ns_groups[domain.ns_group].name}
														</a><#else> -</#if><br/>
							<span class="font-gray call_link">
							<#if (!$.is_empty(domain.ns_group_ns))>
								${domain.ns_group_ns.replace(/;/g,"<br/>")}
							</#if>
							</span>
							</td>
							<td class="font-gray">
								<#if (data.inns == 1)>
									<font class="font-green call_link">已指向</font>
									<#else>
										<font class="font-gray2 call_link">${domain.ns.replace(/;/g,"<br/>")}</font>
								</#if>
							</td>
							<td class="font-gray">
								<#if (domain.indel == 1)>
									<cite class="glyphicon glyphicon-remove font-red call_link"></cite>
									<span class="font-red call_link">[锁]</span>
									<#else>
										<cite class="<#if (domain.status==1)>glyphicon glyphicon-ok font-green<#else>glyphicon glyphicon-pause</#if> call_link" title="正常"></cite>
								</#if>
							</td>
						</tr>
					</#list>
					<#else>
						<tr><td colspan="8"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无解析域名！</span></tr>
				</#if>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var ns_groups = <?php echo JSON::encode($ns_group);?>;
	var service_groups = <?php echo JSON::encode($service_group);?>;
	var domain_parse_count = function(page,uid,keyword){
		var keyword = typeof keyword == "undefined"?"":keyword;
		$.ajaxPassport({
			url:"<?php echo U("/domain_manager/domain?uid=");?>"+uid+"&do=get&pagesize=6&mark=1",
			success:function(res){
				var flist = new Array();
				for(var i in res.list){
					flist.push(res.list[i]);
				}
				res.list = flist;
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "解析域名("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_domain").html(),res);
					$("#user_reg_month_"+uid).html(edit_c);
				}
			},
			data:{page:page,keyword:keyword}
		});
	}
</script>
<!-- 解析域名 end-->

<!-- 注册域名 start-->
<script type="text/template" id="tpl_register_domain">
	<#macro rowedit data>
		<div class="modal-header" style="text-align: center">
			<h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
			<div class="dis10"></div>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
				<col />
				<col width="170px"/>
				<col width="190px" />
				<col width="140px">
				<col width="110px" />
				<col width="110px" />
				<col width="110px" />
				<thead>
				<tr>
					<th>注册域名</th>
					<th>用户名/邮箱/手机</th>
					<th>域名所有者/电话/邮箱</th>
					<th>域名NS</th>
					<th>实名状态</th>
					<th>注册</th>
					<th>续费时间</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as domain>
						<tr>
							<td>
								<span class="f16 font-blue">${domain.domain}</span>
								<#if (domain.renew_type == 1)>
									<span class="font-green" title="新买">[新]</span>
									<#else>
										<span class="font-org" title="续费">[续]</span>
								</#if>&nbsp;
								<br/>
								<span class="font-gray"> [<#if (domain.type == 0)>国际域名<#else>国内域名</#if>]</span>
							</td>
							<td>
								<span class="font-green">${domain.uinfo.uname}</span><br/>
								<span class="font-gray">${domain.uinfo.email}</span><br/>
								<span class="font-gray2">${domain.uinfo.mobile}</span>
							</td>
							<td>
								${domain.info_data.aller_name_cn}<br/>
								${domain.info_data.email}<br/>
								<#if (!$.is_empty(domain.info_data.mobile))>
									${domain.info_data.mobile}<br/>
								</#if>
							</td>
							<td class="font-gray">${domain.ns.replace(";","<br/>")}</td>
							<td class="domain_${domain.domain.split('.')[0]}">
								<#if (domain.real_name.status == 0)>
									<span class="font-green" data-toggle="tooltip" data-placement="right" title="无需实名认证"><cite class="glyphicon glyphicon-ok"></cite></span>
									<#else>
										<#if (domain.real_name.status == 2  || domain.real_name.status == 3 || domain.real_name.status == 4)>
											<a target="_blank" href="<?php echo U("static@");?>${domain.real_name.cart_url}">证件已传</a>
											<#if (domain.real_name.status == 3 || domain.real_name.status == 4)>
												<span class="font-green">[已审]</span>
												<#else><span class="font-gray">[待审]</span>
											</#if>
											<#else>
												<font class="">未提交资料</font><br/>
										</#if>
								</#if>
							</td>
							<td>
								${domain.reg_time}<br/>
								<span title="<#if (domain.exp_type == 1)>服务期<#elseif (domain.exp_type == 2)>续费期<#elseif (domain.exp_type == 3)>赎回期<#else>已删除，域名已失效</#if>" class="<#if (domain.exp_type == 1)>font-green<#elseif (domain.exp_type == 2)>font-org<#elseif (domain.exp_type == 3)>font-red<#else>font-red</#if>">${domain.exp_time}</span>
							</td>
							<td class="">${domain.renew_dateline}</td>
						</tr>
					</#list>
					<#else>
						<tr><td colspan="7"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无解析域名！</span></tr>
				</#if>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var register_domain_fun = function(page,uid,keyword){
		var keyword = typeof keyword == "undefined"?"":keyword;
		$.ajaxPassport({
			url:"<?php echo U("/domain_register/domain?uid=");?>"+uid+"&do=get&pagesize=6&mark=1",
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "注册域名("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_register_domain").html(),res);
					$("#user_reg_month_"+uid).html(edit_c);
				}
			},
			data:{page:page,keyword:keyword}
		});
	}
</script>
<!-- 注册域名 end-->

<!-- 充值订单 start-->
<script type="text/template" id="tpl_cz_order">
	<#macro rowedit data>
		<div class="modal-header" style="text-align: center">
			<h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
			<div class="dis10"></div>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
				<col width="260px" />
				<col width="150px" />
				<col />
				<col width="230px" />
				<col width="70px" />
				<col width="130px" />
				<thead>
				<tr class="active">
					<th>充值编号</th>
					<th>充值人/创建时间</th>
					<th>交易信息</th>
					<th>开票信息</th>
					<th>金额</th>
					<th>状态</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as order>
						<tr>
							<td class="font-gray">
								<cite></cite>
								<font class="font-green">${order.recharge_no}<font class="font-gray2">[${order.id}]</font></font><br/>
								${order.payment_name}
							</td>
							<td class="e">
								<a href="javascript:void(0);" class="font-blue">${order.uname}</a><font class="font-gray">(${order.uid})</font><br/>
								${$.time_to_string(order.dateline)}</td>
							<td>
								<#if (order.order_no)>
						<span class="font-gray">
						<#if (order.order_no)>订单号：${order.order_no}<br/></#if>
						支付方式：<#if (order.inpay == 1)>充值并支付<#else>仅充值,不支付</#if></span>
								<#else>
								-
								</#if>
							</td>
							<td>
								不要开票
							</td>
							<td class="font-org">${order.amount}
								<br/><font class="font-gray">${order.r_amount}</font>
							</td>
							<td>
								<#if (order.status == 1)><span class="font-green">成功</span>
								<#elseif (order.status == -1)><span class="font-red">失败</span>
								<#elseif (order.status == 0)><span class="font-gray">待审</span>
								</#if>
							</td>
						</tr>
					</#list>
					<#else>
						<tr><td colspan="6"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无充值订单！</span></tr>
				</#if>
				</tbody>
				</table>
				<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var show_order = function(page,uid,keyword){
		var keyword = typeof keyword == "undefined"?"":keyword;
		$.ajaxPassport({
			url:"<?php echo U("/order_manager/recharge_action?uid=");?>"+uid+"&do=get&pagesize=6&mark=1",
			success:function(res){
				console.log(res);
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "充值订单("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_cz_order").html(),res);
					$("#user_reg_month_"+uid).html(edit_c);
				}
			},
			data:{page:page,keyword:keyword}
		});
	}
</script>
<!-- 充值订单 end-->

<!-- 解析订单 start-->
<script type="text/template" id="tpl_parse_order">
	<#macro rowedit data>
		<div class="modal-header" style="text-align: center">
			<h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
			<div class="dis10"></div>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
				<col width="150px" />
				<col width="150px" />
				<col width="90px" />
				<col width="140px" />
				<col />
				<thead>
				<tr class="active">
					<th>订单编号&nbsp;<a href="javascript:void(0)" class="orderby" data-item="order_no" data-desc=""><cite></cite></a></th>
					<th>用户&nbsp;<a href="javascript:void(0)" class="orderby" data-item="uid" data-desc=""><cite></cite></a></th>
					<th>订单总金额&nbsp;<a href="javascript:void(0)" class="orderby" data-item="amount" data-desc=""><cite></cite></a></th>
					<th>创建时间&nbsp;<a href="javascript:void(0)" class="orderby" data-item="dateline" data-desc=""><cite></cite></a></th>
					<th>订单详情</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as order>
						<tr>
							<td>${order.order_no}</td>
							<td class="font-blue">${order.uid}</td>
							<td class="font-org">${order.amount}元</td>
							<td class="font-gray">${$.time_to_string(order.dateline)}</td>
							<td>
								<table class="list-table table table-striped table-condensed table-responsive table-bordered" cellpadding="0" cellspacing="0">
									<col  />
									<col width="60px" />
									<col width="80px" />
									<col width="100px" />
									<col width="100px" />
									<col width="100px"/>
									<tbody class="tpl">
									<#list order.order_item as order_item>
										<tr style="text-align: center">
											<td>
												<#if (order_item.goods_name)>
													${order_item.goods_name}
													<#else>
														${order_item.goods_no}
												</#if>
											</td>
											<td><#if (order_item.buy_type == 1)><span class="font-green">新买</span><#else><span class="font-org">续费</span></#if></td>
											<td>
												<#if (order_item.type != 0)>短信套餐</#if>
												<#if (order_item.goods_name)> ${service_group[order_item.goods_no].name}</#if>
											</td>
											<td><#if (order_item.goods_name)><#if (order_item.num < 10 )>${order_item.num}个月<#else>${order_item.num/10}年</#if><#else>${order_item.num}</#if></td>
											<td ><font color="<#if (order.status != 4)> red <#else> #F37B1D </#if>">${order_item.amount}元</font></td>
											<td class="status">
												<#if (order.status >= 4)>
													<#if (order.pay_status == 2)>
														<font color="red">已退款</font>
														<#else>
															<font color="green">已完成</font>
													</#if>
													<#elseif (order.status == 3)>
														<font color="green">已支付</font>
														<#elseif (order.status == 0)>
															已取消
															<#else>
																<font color="red">待付款</font>
												</#if>
											</td>
											</tr>
											</#list>
											</tbody>
										</table>
										</td>
										</tr>
					</#list>
					<#else>
						<tr><td colspan="7"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无解析订单！</span></tr>
				</#if>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var service_group = <?php echo JSON::encode(M("@domain_service")->get_cache_list(0));?>;
	var show_parse_order = function(page,uid,keyword){
		var keyword = typeof keyword == "undefined"?"":keyword;
		$.ajaxPassport({
			url:"<?php echo U("/order_manager/parser?uid=");?>"+uid+"&do=get&pagesize=6&mark=1",
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "解析订单("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_parse_order").html(),res);
					$("#user_reg_month_"+uid).html(edit_c);
				}
			},
			data:{page:page,keyword:keyword}
		});
	}
</script>
<!-- 解析订单 end-->

<!-- 注册订单 start-->
<script type="text/template" id="tpl_register_order">
	<#macro rowedit data>
		<div class="modal-header" style="text-align: center">
			<h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
			<div class="dis10"></div>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
				<col width="160px" />
				<col width="170px" />
				<col width="100px" />
				<col width="150px" />
				<col />
				<thead>
				<tr class="active">
					<th>订单编号&nbsp;<a href="javascript:void(0)" class="orderby" data-item="order_no" data-desc=""><cite></cite></a></th>
					<th>用户&nbsp;<a href="javascript:void(0)" class="orderby" data-item="uid" data-desc=""><cite></cite></a></th>
					<th>订单总金额&nbsp;<a href="javascript:void(0)" class="orderby" data-item="amount" data-desc=""><cite></cite></a></th>
					<th>创建时间&nbsp;<a href="javascript:void(0)" class="orderby" data-item="dateline" data-desc=""><cite></cite></a></th>
					<th>订单详情</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as order>
						<tr>
							<td>${order.order_no}</td>
							<td class="font-blue">${order.uid}</td>
							<td class="font-org">${order.amount}元</td>
							<td class="font-gray">${$.time_to_string(order.dateline)}</td>
							<td>
								<table class="list-table table table-striped table-condensed table-responsive table-bordered" cellpadding="0" cellspacing="0">
									<col  />
									<col width="60px" />
									<col width="100px" />
									<col width="120px" />
									<col width="120px"/>
									<tbody class="tpl">
									<#list order.order_item as order_item>
										<tr style="text-align: center">
											<td>${order_item.domain} </td>
											<td><#if (order_item.type == 1)><span class="font-green">新买</span><#else><span class="font-org">续费</span></#if></td>
											<td>${order_item.num}年</td>
											<td ><font color="<#if (order.status != 4)> red <#else> #F37B1D </#if>">${order_item.amount}元</font></td>
											<td class="status">
												<#if (order.status >= 4)>
													<#if (order.pay_status == 2)>
														<font color="red">已退款</font>
														<#else>
															<font color="green">已完成</font>
													</#if>
													<#elseif (order.status == 3)>
														<font color="green">已支付</font>
														<#elseif (order.status == 0)>
															已取消
															<#else>
																<font color="red">待付款</font>
												</#if>
											</td>
										</tr>
									</#list>
									</tbody>
								</table>
							</td>
						</tr>
					</#list>
					<#else>
						<tr><td colspan="7"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无注册订单！</span></tr>
				</#if>
				</tbody>
				</table>
				<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var show_register_order = function(page,uid,keyword){
		var keyword = typeof keyword == "undefined"?"":keyword;
		$.ajaxPassport({
			url:"<?php echo U("/order_manager/register?uid=");?>"+uid+"&do=get&pagesize=6&mark=1",
			success:function(res){
				console.log(res);
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "注册订单("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_register_order").html(),res);
					$("#user_reg_month_"+uid).html(edit_c);
				}
			},
			data:{page:page,keyword:keyword}
		});
	}
</script>
<!-- 注册订单 end-->

<!-- 余额 start-->
<script type="text/template" id="tpl_rechart_order">
	<#macro rowedit data>
		<div class="modal-header" style="text-align: center">
			<h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
			<div class="dis10"></div>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
				<col width="180px" />
				<col width="150px" />
				<col width="180px" />
				<col width="100px" />
				<col width="100px" />
				<col />
				<col width="170px" />
				<thead>
				<tr>
					<th>流水号</th>
					<th>时间</th>
					<th>用户</th>
					<th>变数</th>
					<th>变后</th>
					<th>描述</th>
					<th>操作者</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as fina>
						<tr>
							<td>${fina.lsh}</td>
							<td>${$.time_to_string(fina.dateline)}</td>
							<td>${fina.uname}</td>
							<td><#if (fina.type == 2)><font class="font-green">+ ${fina.amount}</font>
								<#elseif (fina.type == 1)><font class="font-red">- ${fina.amount}</font>
									<#else>
										<font class="font-gray">= ${fina.amount}</font>
							</#if>
							</td>
							<td>${fina.amount_log}</td>
							<td>${fina.note}</td>
							<td> ${fina.czz} </td>
						</tr>
					</#list>
					<#else>
						<tr><td colspan="7"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无资金流水！</span></tr>
				</#if>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var show_rechart_order = function(page,uid,keyword){
		var keyword = typeof keyword == "undefined"?"":keyword;
		$.ajaxPassport({
			url:"<?php echo U("/user_manager/user_accountlog?uid=");?>"+uid+"&do=get&pagesize=10&mark=1",
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "余额("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_rechart_order").html(),res);
					$("#user_reg_month_"+uid).html(edit_c);
				}
			},
			data:{page:page,keyword:keyword}
		});
	}
</script>
<!-- 余额 end-->

<!-- 短信 start-->
<script type="text/template" id="tpl_rechart_sms_order">
	<#macro rowedit data>
		<div class="modal-header" style="text-align: center">
			<h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
			<div class="dis10"></div>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
				<col width="180px" />
				<col width="150px" />
				<col width="180px" />
				<col width="100px" />
				<col width="100px" />
				<col />
				<col width="170px" />
				<thead>
				<tr>
					<th>流水号</th>
					<th>时间</th>
					<th>用户</th>
					<th>变数</th>
					<th>变后</th>
					<th>描述</th>
					<th>操作者</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as fina>
						<tr>
							<td>${fina.lsh}</td>
							<td>${$.time_to_string(fina.dateline)}</td>
							<td>${fina.uname}</td>
							<td><#if (fina.type == 2)><font class="font-green">+ ${fina.amount}</font>
								<#elseif (fina.type == 1)><font class="font-red">- ${fina.amount}</font>
									<#else>
										<font class="font-gray">= ${fina.amount}</font>
							</#if>
							</td>
							<td>${fina.amount_log}</td>
							<td>${fina.note}</td>
							<td> ${fina.czz} </td>
						</tr>
					</#list>
					<#else>
						<tr><td colspan="7"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无资金流水！</span></tr>
				</#if>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var show_rechart_sms_order = function(page,uid,keyword){
		var keyword = typeof keyword == "undefined"?"":keyword;
		$.ajaxPassport({
			url:"<?php echo U("/user_manager/user_accountlog?uid=");?>"+uid+"&do=get&pagesize=10&mark=2&ftype=sms",
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "短信("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_rechart_sms_order").html(),res);
					$("#user_reg_month_"+uid).html(edit_c);
				}
			},
			data:{page:page,keyword:keyword}
		});
	}
</script>
<!-- 短信  end-->

<!-- 积分 start-->
<script type="text/template" id="tpl_rechart_ji_order">
	<#macro rowedit data>
		<div class="modal-header" style="text-align: center">
			<h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
			<div class="dis10"></div>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
				<col width="180px" />
				<col width="150px" />
				<col width="180px" />
				<col width="100px" />
				<col width="100px" />
				<col />
				<col width="170px" />
				<thead>
				<tr>
					<th>流水号</th>
					<th>时间</th>
					<th>用户</th>
					<th>变数</th>
					<th>变后</th>
					<th>描述</th>
					<th>操作者</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as fina>
						<tr>
							<td>${fina.lsh}</td>
							<td>${$.time_to_string(fina.dateline)}</td>
							<td>${fina.uname}</td>
							<td><#if (fina.type == 2)><font class="font-green">+ ${fina.amount}</font>
								<#elseif (fina.type == 1)><font class="font-red">- ${fina.amount}</font>
									<#else>
										<font class="font-gray">= ${fina.amount}</font>
							</#if>
							</td>
							<td>${fina.amount_log}</td>
							<td>${fina.note}</td>
							<td> ${fina.czz} </td>
						</tr>
					</#list>
					<#else>
						<tr><td colspan="7"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无资金流水！</span></tr>
				</#if>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var show_rechart_ji_order = function(page,uid,keyword){
		var keyword = typeof keyword == "undefined"?"":keyword;
		$.ajaxPassport({
			url:"<?php echo U("/user_manager/user_accountlog?uid=");?>"+uid+"&do=get&pagesize=10&mark=3&ftype=point",
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "积分("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_rechart_ji_order").html(),res);
					$("#user_reg_month_"+uid).html(edit_c);
				}
			},
			data:{page:page,keyword:keyword}
		});
	}
</script>
<!-- 积分 end-->

<!-- 登陆日志 start-->
<script type="text/template" id="tpl_login_log">
	<#macro rowedit data>
		<div class="modal-header" style="text-align: center">
			<h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
			<div class="dis10"></div>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
				<col width="150px" />
				<col width="260px" />
				<col />
				<col />
				<col width="190px" />
				<thead>
				<tr>
					<th>时间</th>
					<th>用户ID</th>
					<th>详情</th>
					<th>登录地区/设备/浏览器</th>
					<th>登录IP</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as log>
						<tr>
							<td>${$.time_to_string(log.dateline)}</td>
							<td class="font-green">${log.name}<span class="font-gray">(<#if (log.uid>0)>${log.uid}<#else> - </#if>)</span></td>

							<td class="font-gray">${log.log_data}</td>
							<td class="font-gray">${log.log_place} /
								${log.log_pc} /
								${log.log_ie}
							</td>
							<td class="font-gray">${log.log_ip}</td>
						</tr>
					</#list>
					<#else>
						<tr><td colspan="5"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无登陆日志！</span></tr>
				</#if>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var show_login_log = function(page,uid,keyword){
		var keyword = typeof keyword == "undefined"?"":keyword;
		$.ajaxPassport({
			url:"<?php echo U("/user_manager/loginlog?uid=");?>"+uid+"&do=get&pagesize=10&mark=1",
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "登陆日志("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_login_log").html(),res);
					$("#user_reg_month_"+uid).html(edit_c);
				}
			},
			data:{page:page,keyword:keyword}
		});
	}
</script>
<!-- 登陆日志 end-->

<!-- 操作日志 start-->
<script type="text/template" id="tpl_cz_log">
	<#macro rowedit data>
		<div class="modal-header" style="text-align: center">
			<h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
			<div class="dis10"></div>
		</div>
		<div class="recordlist">
			<table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
				<col width="150px" />
				<col width="230px" />
				<col width="150px" />
				<col />
				<col width="190px" />
				<thead>
				<tr>
					<th>时间</th>
					<th>用户</th>
					<th>操作类型</th>
					<th>操作详情</th>
					<th>操作IP</th>
				</tr>
				</thead>
				<tbody>
				<#if (data.list.length>0)>
					<#list data.list as log>
						<tr>
							<td>${$.time_to_string(log.dateline)}</td>
							<td class="font-gray">${log.author}<span class="font-gray2">({<#if (log.uid>0)>${log.uid}<#else> - </#if>})</span></td>
							<td>${log.action}</td>
							<td class="font-gray">${log.content}</td>
							<td class="font-gray">${log.ip}</td>
						</tr>
					</#list>
					<#else>
						<tr><td colspan="5"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无操作日志！</span></tr>
				</#if>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var show_cz_log = function(page,uid,keyword){
		var keyword = typeof keyword == "undefined"?"":keyword;
		$.ajaxPassport({
			url:"<?php echo U("/user_manager/userlog?uid=");?>"+uid+"&do=get&pagesize=10&mark=1",
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "操作日志("+res.total+")";
					var edit_c = "" + easyTemplate($("#tpl_cz_log").html(),res);
					$("#user_reg_month_"+uid).html(edit_c);
				}
			},
			data:{page:page,keyword:keyword}
		});
	}
</script>
<!-- 操作日志 end-->