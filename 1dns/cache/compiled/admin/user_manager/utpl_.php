<!-- user balance point  -->
<script type="text/template" id="tpl_balance_point">
<#macro row data>
<form action="<?php echo U("/user_manager/userlist_recharge");?>" method="post" class="form form-2 t-ajax-form" role="form">
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">${data.title}</h4>
</div>
<div class="modal-body">
<div class="form-item form-item-text">
<label>余额增减：</label>
<div class="item-v">
<input type="text" class="low " name="balance" value="" maxlength="30" id="Mbalance" value="0"> <span class="font-gray Validform_checktip">正增加 负为减少</span></div>
<div class="cl"></div>

</div>

<div class="form-item form-item-text">
<label>短信量增减：</label>
<div class="item-v">
<input type="text" class="low " name="sms" value="" maxlength="30" id="Msms" value="0"> <span class="font-gray Validform_checktip">正增加 负为减少</span></div>
<div class="cl"></div>
</div>


<div class="form-item form-item-text">
<label>积分增减：</label>
<div class="item-v">
<input type="text" class="low " name="point" value="" maxlength="30" id="Mpoint" value="0"> <span class="font-gray Validform_checktip">正增加 负为减少</span></div>
<div class="cl"></div>
</div>

<div class="form-item form-item-text">
	<label>备注：</label>
	<div class="item-v">
		<textarea  cols="40" rows="6" name="note"></textarea>
	</div>
	<div class="cl"></div>
</div>

<div class="cl"></div>
</div>
<div class="modal-footer">
      <input type="hidden" value="${data.id}" name="id" />
	  <input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" />
      <button type="submit" class="btn btn-primary" data-loading-text="处理中……">确定</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>
</form>
</#macro>
</script>
<!--手动添加充值信息-->
<script type="text/template" id="tpl_add_recharge">
<#macro row data>
<form action="<?php echo U("/order_manager/recharge_add");?>" method="post" class="t-ajax-form form" role="form">
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">${data.title} <font class="font-red"></font></h4>
</div>
<div class="modal-body">
	<div class="form-item"><label class="fb">充值人UID：</label>
    <div class="item-v"><input type="text" value="${data.uid}" name="recharge_uid" class="hign" /></div><div class="cl"></div>
    </div>
	<div class="form-item"><label class="fb">充值金额：</label>
    <div class="item-v"><input type="text" value="${data.amount}" name="amount" class="hign" /></div><div class="cl"></div>
    </div>
	<div class="form-item"><label class="fb">是否开票：</label>
    <div class="item-v"><input name="kaipiao" type="checkbox" value="1" /> 开票</div><div class="cl"></div>
    </div>
	<div class="form-item"><label class="fb">税点：</label>
    <div class="item-v"><?php echo isset($site['shui'])?$site['shui']:"";?> %</div><div class="cl"></div>
    </div>
	
</div>
<div class="modal-footer">
	  <input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" />    
      <button type="submit" class="btn btn-primary" data-loading-text="处理中……">确定添加</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>
</form>
</#macro>
</script>
<!--end查看并审核-->
<script language="javascript">
var edit_user_balance_point = function(obj){
	var id = $(obj).attr("data-id");
  	var name = $(obj).attr("data-name");
  	var tpl_s = $("#tpl_balance_point").html();
  	var tpl_v = {id:id,title:"修改"+name+"的账户"};
  	$("#myModal").find(".modal-dialog").width(560);
  	$("#myModal").find(".modal-content").html("" + easyTemplate(tpl_s,tpl_v));
  	 //初始化表单
	$("#myModal form.t-ajax-form").each(function(i){
		$(this).submit(function(){
				return $.ajaxForm(this,i);
			});
	});
	//弹出
	$('#myModal').modal();
}
var edit_user_recharge = function(obj){
	var uid = $(obj).attr("data-uid");
  	var uname = $(obj).attr("data-uname");
  	var amount = $(obj).attr("data-amount");
	var tpl_s = $("#tpl_add_recharge").html();
	
	uid = $.is_empty(uid)?0:uid;
	uname = $.is_empty(uname)?0:uname;
	amount = $.is_empty(amount)?0:amount;
  	var tpl_v = {uid:uid,uname:uname,amount:amount};
  	tpl_v['title'] = "手动给<b class='f18'>"+uname+"</b>充值";
  	$("#myModal").find(".modal-dialog").width(760);
  	$("#myModal").find(".modal-content").html(""+easyTemplate(tpl_s,tpl_v));
  	$('#myModal').modal();
  	 //初始化表单
	$("#myModal form.t-ajax-form").each(function(i){
		$(this).submit(function(){
			return $.ajaxForm(this,i);
		});
	});
}
</script>
<!-- end user balance point -->
<!-- user setting -->
<script type="text/template" id="tpl_user_setting_edit">
<#macro rowedit data>
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">${data.title}</h4>
</div>
<div class="form-content">
<form action="<?php echo U("/user_manager/userlist_setting");?>" method="post" class="theform form form-2" role="form">
<div class="tpl"></div>
</form>
</div>
</#macro>
</script>
<script language="javascript">
var setting_formdata = {
	ulevel_name:{type:"ivalue",label:"会员等级名称",name:"ulevel_name",value:"",data_sr:[],css:"f14",require:"",desc:"",item_css:" font-red"},
	html_space2:{type:"html",value:"<h5>&nbsp;</h5>"},
	html_space6:{type:"html",value:"<div style='padding-left:120px;'><p style='padding-bottom:8px;'></p><div class='attrspec_valbox'><table class='table table-bordered' cellpadding='0' cellspacing='0' style='width:580px;'><thead><tr class='active'><th>索引项目</th><th style='width:180px;'>值</th><th style='width:180px;'>说明</th><th style='width:80px;'>-</th></tr></thead><tbody></tbody></table></div></div>"},
	html_space7:{type:"html",value:"<h5>&nbsp;</h5>"},
    uid:{type:"hidden",label:"-",name:"uid",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
};
var add_trrow = function(key,val,name){
	var size = $('.attrspec_valbox tbody tr').size();
	var row = '<tr class="td_c"><td><input name="keys[]" class="form-control" type="hidden" value="'+key+'" /> '+key+'</td>'
	        + '<td><input name="vals[]" class="form-control" type="text" value="'+val+'" /></td>'
	        + '<td>'+name+'</td>'
			+ '<td>-'
			+ '</td></tr>';
	$('.attrspec_valbox tbody').append(row);
	init_button(size,'.attrspec_valbox');
}
var init_button = function(indexValue,idstr){
	//功能操作按钮
	$(idstr).find('tbody tr:eq('+indexValue+') .table-item-op .glyphicon').each(
		function(i){
			var tr_obj = $(this).parent().parent().parent();
			switch(i){
				//向上排序
				case 0:
				$(this).click(
					function(){
						var insertIndex = tr_obj.prev().index();
						if(insertIndex >= 0){
							$(idstr).find('tbody tr:eq('+insertIndex+')').before(tr_obj);
						}
					}
				)
				break;
				//向下排序
				case 1:
				$(this).click(
					function(){
						var insertIndex = tr_obj.next().index();
						$(idstr).find('tbody tr:eq('+insertIndex+')').after(tr_obj);
					}
				)
				break;
				//删除排序
				case 2:
				$(this).click(
					function(){
						tr_obj.remove();
					}
				)
				break;
			}
		}
	)
}
var edit_user_setting_func = function(obj){
	var edit_c = $("#tpl_user_setting_edit").html();
	var url = $(obj).attr("data-url");
	var uid = $(obj).attr("data-id");
	edit_c = "" + easyTemplate(edit_c,{title:"添加/修改用户设置"});
	$("#myModal").find(".modal-dialog").width(960);
	$("#myModal").find(".modal-content").html(edit_c);
	$.loadform(setting_formdata,url,function(res){
		 return true;
	},function(res){
		for(var i in res.setting['ulevel_data']){
			//if(!$.in_array(i,['ulevel_minv','ulevel_maxv','ulevel_data','ulevel_inlock','ulevel_name'])){
				add_trrow(res.setting['ulevel_data'][i]['item'],(!$.is_empty(res.setting[i])?res.setting[i]:res.setting['ulevel_data'][i]['value']),res.setting['ulevel_data'][i]['name']);
			//}
		}
	},".theform");
	$('#myModal').modal();
}
</script>
<!-- end user setting -->

<!-- 编辑用户信息 -->
<script type="text/template" id="tpl_user_edit">
<#macro rowedit data>
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">${data.title}</h4>
</div>
<div class="form-content">
<form action="<?php echo U("/user_manager/userlist_edit");?>" method="post" class="theform form form-2" role="form">
<div class="tpl"></div>
</form>
</div>
</#macro>
</script>
<script language="javascript">
var userinfo_formdata = {
		uname:{type:"text",label:"用户名",name:"uname",value:"",disabled:0,data_sr:[],css:"",require:"datatype='*'",desc:"用户名必须填写",item_css:"col-md-6"},
		pass:{type:"password",label:"登录密码",name:"pass",value:"",data_sr:[],css:"",require:"datatype='*6-18' ignore=\"ignore\"",desc:"",item_css:"col-md-6"},
	    email:{type:"text",label:"邮箱",name:"email",value:"",disabled:0,data_sr:[],css:"",require:"",desc:"邮箱也可以登录",item_css:"col-md-6"},
	    emailrz:{type:"checkbox",label:"邮箱认证",name:"emailrz",value:"",disabled:0,data_sr:[{v:"认证",key:1}],css:"",require:"",desc:"",item_css:"col-md-6"},
	    mobile:{type:"text",label:"手机",name:"mobile",value:"",disabled:0,data_sr:[],css:"",require:"",desc:"手机也可以登录",item_css:"col-md-6"},
	    mobilerz:{type:"checkbox",label:"手机认证",name:"mobilerz",value:"",disabled:0,data_sr:[{v:"认证",key:1}],css:"",require:"",desc:"",item_css:"col-md-6"},
	    utype:{type:"select",label:"会员类型",name:"utype",value:"",data_sr:dataConfig_A['utype'],css:"",require:"",desc:"",item_css:"col-md-6"},
	    realname:{type:"text",label:"真实姓名",name:"realname",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	    //sex:{type:"radio",label:"性别",name:"sex",value:"",data_sr:[{v:"男",key:1},{v:"女",key:2}],css:"",require:"",desc:"",item_css:""},
	    <?php if($this->check_upurview(1)){?>
	    //company:{type:"select",label:"公司",name:"company",value:"",disabled:0,data_sr:companyConfig_A,css:"",require:"",desc:"",item_css:""},
	    <?php }?>
	    //depart:{type:"select",label:"部门",name:"depart",value:"",disabled:0,data_sr:dataConfig_A['depart'],css:"",require:"",desc:"",item_css:""},
	    //post:{type:"select",label:"职位",name:"post",value:"",disabled:0,data_sr:dataConfig_A['post'],css:"",require:"",desc:"",item_css:""},
	    //idc:{type:"checkbox",label:"锁定机房",name:"idc[]",value:"1,2",disabled:0,data_sr:dataConfig_A['idc'],css:"",require:"",desc:"",item_css:""},
	    html_space1:{type:"html",value:"<h5>&nbsp;</h5>"},
	    appstatus:{type:"checkbox",label:"APP STATUS",name:"appstatus",value:"1",data_sr:[{key:1,v:"可用"}],css:"",require:"",desc:"",item_css:"col-md-3"},
		appid:{type:"ivalue",label:"APPID",name:"appid",value:"",disabled:0,data_sr:[],css:"",require:"",desc:"",item_css:"col-md-3"},
		appkey:{type:"text",label:"APPKEY",name:"appkey",value:"",data_sr:[],css:"nhigh",require:"",desc:"",item_css:"col-md-6"},
		
		html_space:{type:"html",value:"<h5>&nbsp;</h5>"},
		ulevel:{type:"select",label:"会员等级",name:"ulevel",value:"",disabled:0,data_sr:[],css:"",require:"",desc:"",item_css:"col-md-6"},
		expiry:{type:"date",label:"会员期限",name:"expiry",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:"col-md-6"},
		urole:{type:"select",label:"权限角色",name:"urole",value:"",disabled:0,data_sr:roleList,css:"",require:"",desc:"",item_css:"col-md-6"},
		inlock:{type:"radio",label:"锁定",name:"inlock",value:"",disabled:0,data_sr:[{v:"临时锁定",key:1},{v:"永久锁定",key:2}],css:"",require:"",desc:"",item_css:"col-md-6"},
		
		
	    uid:{type:"hidden",label:"",name:"uid",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
	    btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
};
var edit_userinfo = function(obj){
		var edit_c = $("#tpl_user_edit").html();
		var url = $(obj).attr("data-url");
		var utype = $(obj).attr("data-utype");
		edit_c = "" + easyTemplate(edit_c,{title:"添加/修改用户"});
		$("#myModal").find(".modal-dialog").width(960);
		$("#myModal").find(".modal-content").html(edit_c);
		userinfo_formdata.ulevel.data_sr = ulevel[utype];
		$.loadform(userinfo_formdata,url,function(res){
			 loadlist(1);
			 return true;
		},function(res){
			setTimeout(function(){
			$("#myModal").find(".modal-content").find(".tpl").find("input.date-ymd").datetimepicker({
					language:  'zh-CN',
					autoclose: 1,
					startView: 2,
					minView: 2, 
					maxView: 4,
					format:"yyyy-mm-dd",
					pickerPosition: "bottom-right"   
			});
			},50);
		});
		$('#myModal').modal();
	}
</script>
<!-- end编辑用户信息结束 -->