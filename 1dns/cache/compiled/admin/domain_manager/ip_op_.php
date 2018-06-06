<!-- ip show start-->
<script type="text/template" id="tpl_server_iplist">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class='serch-ip'>
			<input  type='text' placeholder='请输入ip地址' id="get-ser-ip" data-url="${data.id}" value="${data.keywords}"/>&nbsp;
			<button type='button'  class='btn btn-xs btn-success ip-ser-btn' id="put-ser-ip">搜索</button>
		</div>
		<div class="recordlist ip-admin">
			<table class="list-table table table-bordered table-condensed" cellpadding="0" cellspacing="0">
				<col />
				<col width="130px" />
				<col width="70px" />

				<thead>
				<tr class="active">
					<th>IP地址</th>
					<th>线路</th>
					<th>操作项</th>
				</tr>
				</thead>
				<tbody>
				<#list data.ip.list as list>
					<tr>
						<td class="font-blue">${list.addr}</td>
						<td class="font-gray">${list.line}</td>
						<td>
							<a href="javascript:void(0);" class="ip-del-btn" data-url="<?php echo U("/domain_manager/line_aclip?do=del&id=");?>${list.id}"><span class="glyphicon glyphicon-remove font-red"></span></a>
						</td>
					</tr>
				</#list>
				</tbody>
			</table>
			<div class="pagebar">${data.pagebar}</div>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var edit_ip_func = function(page,line_id,keyword){
		$.ajaxPassport({
			url:"<?php echo U("/domain_manager/line_aclip?line_id=");?>"+line_id +"&keyword="+keyword,
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "ip库";
					res.id = line_id;
					res.keywords = keyword;
					var edit_c = "" + easyTemplate($("#tpl_server_iplist").html(),res);
					$("#myModal").find(".modal-dialog").width("680");
					$("#myModal").find(".modal-content").html(edit_c);
					$('#myModal').modal();
					$("#myModal").find(".modal-content").find(".ip-del-btn").click(function(){
						if(confirm("你确定要删除该IP吗?删除后IP不可恢复！")){
							var url = $(this).attr("data-url");
							$.ajaxPassport({
								url:url,
								success:function(res){
									if(res.error == 1){
										$.tips(res.message,"error");
									}else{
										$('#myModal').modal('hide');
										$.tips(res.message,"success");

									}
								}
							});
						}
					});
					$("#myModal").find(".modal-content").find(".ip-ser-btn").click(function(){
						var keyword = $(this).parent().find("#get-ser-ip").val();
						var line_id = $(this).parent().find("#get-ser-ip").attr("data-url");
						edit_ip_func(1,line_id,keyword);
					});
				}
			},
			data:{page:page}
		});
	}
</script>
<!-- ip show end-->
<!-- ip add  start-->
<script type="text/template" id="tpl-setting-add">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class="form-content">
			<form action="<?php echo U("/domain_manager/line_aclip?do=edit");?>" method="post" class="theform form form-2" role="form">
				<div class="tpl"></div>
			</form>
		</div>
	</#macro>
</script>
<script language="javascript">
	var setting_add_formdata = {
	acl: {type: "ivalue", label: "所属线路", name: "acl", value: "", disabled: 0, data_sr:[], css: "", require: "", desc: "", item_css: ""},
	addr:{type:"text",label:"ip段",name:"addr",value:"",data_sr:[],css:"",require:"",desc:"如：10.10.10.10/24",item_css:""},
	aclid:{type:"hidden",name:"aclid",value:"0"},
	btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
	};
	var ip_add_fun = function(obj){
		var edit_c = $("#tpl-setting-add").html();
		var url = $(obj).attr("data-url");
		var aclid = $(obj).attr("data-aclid");
		var acl = $(obj).attr("data-acl");
		edit_c = "" + easyTemplate(edit_c,{title:"添加"+acl+"IP"});
		$("#myModal").find(".modal-dialog").width(560);
		$("#myModal").find(".modal-content").html(edit_c);
		setting_add_formdata.acl.value = acl;
		setting_add_formdata.aclid.value = aclid;
		$.loadform(setting_add_formdata,url,function(res){
			return true;
		},function(res){
		},".theform");
		$('#myModal').modal();
	}
</script>
<!-- ip add  end-->
<!-- ip import  start-->
<script type="text/template" id="tpl-setting-import">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class="form-content">
			<form action="<?php echo U("/domain_manager/line_aclip?do=import");?>" enctype="multipart/form-data" method="post" class="theform form form-2" role="form">
				<div class="tpl"></div>
			</form>
		</div>
	</#macro>
</script>
<script language="javascript">
	var setting_import_formdata = {
	acl_name: {type: "ivalue", label: "导入线路", name: "acl_name", value: "", disabled: 0, data_sr:[], css: "", require: "", desc: "", item_css: ""},
	ipdatafile: {type: "file", label: "IP文件", name: "ipdatafile", value: "", disabled: 0, data_sr:[], css: "", require: "", desc: "", item_css: ""},
	mode: {type: "select", label: "导入模式", name: "mode", value: "", data_sr:[{v: "清空导入", key: "2"}, {v: "追加导入", key: "1"}], css: "", require: "", desc: "", item_css: ""},
	aclid:{type:"hidden",name:"aclid",value:"0"},
		acl:{type:"hidden",name:"acl",value:"0"},
	btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
	};
	var ip_import_fun = function(obj){
		var edit_c = $("#tpl-setting-import").html();
		var url = $(obj).attr("data-url");
		var aclid = $(obj).attr("data-aclid");
		var acl = $(obj).attr("data-acl");
		var acl_name = $(obj).attr("data-acl_name");
		edit_c = "" + easyTemplate(edit_c,{title:"导入"+acl_name+" 线路IP"});
		$("#myModal").find(".modal-dialog").width(560);
		$("#myModal").find(".modal-content").html(edit_c);
		setting_import_formdata.acl.value = acl;
		setting_import_formdata.aclid.value = aclid;
		setting_import_formdata.acl_name.value = acl_name;
		$.loadform(setting_import_formdata,url,function(res){
			return true;
		},function(res){
		},".theform");
		$('#myModal').modal();
	}
</script>
<!-- ip import  end-->
<!-- ip fresh start-->
<script type="text/template" id="tpl_server_fresh">
	<#macro rowedit data>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">${data.title}</h4>
		</div>
		<div class="recordlist ip-admin">
			<table class="list-table table table-bordered table-condensed" cellpadding="0" cellspacing="0">
				<col width="200px"/>
				<col width="100px" />
				<col width="230px" />
				<col width="70px" />
				<thead>
				<tr class="active">
					<th>服务器组</th>
					<th>标识</th>
					<th>主机地址</th>
					<th>状态</th>
				</tr>
				</thead>
				<tbody>
				<#list data.list as list>
					<tr>
						<td class="font-blue">${list.name}</td>
						<td class="font-gray">${list.ns_group}</td>
						<td class="font-gray">${list.host}</td>
						<td>
							<#if (list.ret.status==0)><span class="font-red">失败</span><#else><span class="font-green">成功</span></#if>
						</td>
					</tr>
				</#list>
				</tbody>
			</table>
		</div>
		<div class="dis20"></div>
		</div>
	</#macro>
</script>
<script language="JavaScript">
	var ip_refresh_fun = function(id){
		$.ajaxPassport({
			url:"<?php echo U("/domain_manager/line_refresh?id=");?>"+ id + "&do=fresh",
			success:function(res){
				if(res.error == 1){
					$.tips(res.message,"error");
				}else{
					res.title = "命令执行成功";
					var edit_c = "" + easyTemplate($("#tpl_server_fresh").html(),res);
					$("#myModal").find(".modal-dialog").width("600");
					$("#myModal").find(".modal-content").html(edit_c);
					$('#myModal').modal();
				}
			},
		});
	}
</script>
<!-- ip fresh end-->