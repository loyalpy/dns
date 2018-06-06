<script type="text/template" id="tpl_form">
<#macro form data>
<#if (data.type == "hidden")>
<input type="hidden" name="${data.name}" value="${data.value}" />
<#elseif (data.type == "html")>
${data.value}
<#else>
<div class="form-item form-item-${data.type} ${data.item_css}">
    <label>${data.label}<#if (!$.in_array(data.label,['','-']))>：</#if></label>
    <div class="item-v">
	<#if (data.type == "text")>
		<input  <#if (data.disabled == 1)>disabled="disabled"</#if> type="text" class="nhigh ${data.css}" ${data.require} name="${data.name}" value="${data.value}" /> 
		<#if (data.data_sr.length>0 && data.disabled == 0)>
		<div class="btn-group">
		  <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
		    选择 <span class="caret"></span>
		  </button><ul class="dropdown-menu" role="menu">
		    <#list data.data_sr as list>
		    <li><a href="javascript:void(0);" onclick="$(this).parent().parent().parent().parent().find('input[type=text]').val($(this).text())"><#if (list.v)>${list.v}<#else>${list}</#if></a></li>
		    </#list>
		  </ul>
		</div>
		</#if>
	<#elseif (data.type == "password")>
		<input  <#if (data.disabled == 1)>disabled="disabled"</#if> type="password" class="nhigh ${data.css}" ${data.require} name="${data.name}" value="${data.value}" />
	<#elseif (data.type == "file")>
		<input type="file" class="nhigh ${data.css}" ${data.require} name="${data.name}" value="" />
	<#elseif (data.type == "textarea")>
		<textarea class="high ${data.css}" ${data.require} name="${data.name}">${data.value}</textarea>
	<#elseif (data.type == "select")>
	    <div class="dropdown dropdown-select">
            <a role="button" data-toggle="dropdown" class="btn btn-default" data-target="#" href="javascript:void(0);">
            <font data-name="${data.name}" data-txt="请选择${data.label}" class="selname"></font>&nbsp;&nbsp; <span class="caret"></span>
            <input type="hidden" ${data.require} name="${data.name}" value="${data.value}" />
            </a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
            <li><a class="sel<#if (data.disabled == 1)>1<#else>0</#if>" data-v="" href="javascript:void(0);">不选</a></li>
            <li class="divider"></li>
            <#list data.data_sr as list>
			<li><a data-v="${list.key}" class="sel<#if (data.disabled == 1 && data.value != list.key)>1<#else>0</#if>" href="javascript:void(0);">${list.v}</a></li>
			</#list>
            </ul>
		</div>
	<#elseif (data.type == "radio")>
	    <#if (data.nonone)><#else>
	    <span class="input-item"><input <#if (data.disabled == 1)>disabled="disabled"</#if> class="reset ${data.css}" type="radio" ${data.require} name="${data.name}" value="0" <#if (0 == data.value)>checked="checked"</#if> /> 不选&nbsp;&nbsp;</span>
		</#if>
	    <#list data.data_sr as list>
		<span class="input-item"><input <#if (data.disabled == 1)>disabled="disabled"</#if> class="reset ${data.css}" type="radio" ${data.require} name="${data.name}" value="${list.key}" <#if (list.key == data.value)>checked="checked"</#if> /> ${list.v}&nbsp;&nbsp;</span>
		</#list>
	<#elseif (data.type == "checkbox")>
		<#list data.data_sr as list>
		<span class="input-item"><input <#if (data.disabled == 1)>disabled="disabled"</#if> class="reset ${data.css}" type="checkbox" ${data.require} name="${data.name}" value="${list.key}" <#if ($.in_array(list.key,data.value))>checked="checked"</#if> /> ${list.v}&nbsp;&nbsp;</span>
		</#list>
	<#elseif (data.type == "area")>
		<span><select id="frm_province" name="sheng"></select></span> 
		<span><select id="frm_city" name="shi"></select></span> 
		<span><select id="frm_area" name="area"></select></span>
	<#elseif (data.type == "ip")>
		<input  <#if (data.disabled == 1)>disabled="disabled"</#if> type="text" class="xlow ${data.css}" ${data.require} name="${data.name}" value="${parseInt(data.value.substr(0,3),10)}" /> . 
		<input  <#if (data.disabled == 1)>disabled="disabled"</#if> type="text" class="xlow ${data.css}" ${data.require} name="${data.name}" value="${parseInt(data.value.substr(3,3),10)}" /> .  
		<input  <#if (data.disabled == 1)>disabled="disabled"</#if> type="text" class="xlow ${data.css}" ${data.require} name="${data.name}" value="${parseInt(data.value.substr(6,3),10)}" /> .  
		<input  <#if (data.disabled == 1)>disabled="disabled"</#if> type="text" class="xlow ${data.css}" ${data.require} name="${data.name}" value="${parseInt(data.value.substr(9,3),10)}" />
	<#elseif (data.type == "ipmask")>
		255 . 255 . 255 .  
		<input  <#if (data.disabled == 1)>disabled="disabled"</#if> type="text" class="xlow ${data.css}" ${data.require} name="${data.name}" value="${parseInt(data.value.substr(9,3),10)}" />
	<#elseif (data.type == "ihtml")>
		${data.value}
		<input type="hidden" name="${data.name}" value="<#if ($.is_empty(data.hid_value))>${data.value}<#else>${data.hid_value}</#if>" />
	<#elseif (data.type == "ivalue")>
		<span class="ivalue ${data.css}">${data.value}
		<input type="hidden" name="${data.name}" value="<#if ($.is_empty(data.hid_value))>${data.value}<#else>${data.hid_value}</#if>" />
		</span>
	<#elseif (data.type == "date")>
		<input type="text" class="nhigh date-ymd ${data.css}" ${data.require} name="${data.name}" value="${data.value}" readonly /> <cite class="glyphicon glyphicon-calendar"></cite>
	<#elseif (data.type == "uid")>
	    <div class="dropdown dropdown-uidbox">
            <a role="button" data-toggle="dropdown" class="btn btn-default" data-target="#" href="javascript:void(0);">
            <font data-name="${data.name}" data-txt="请选择${data.label}" class="selname"><#if (!$.is_empty(data.uname))>${data.uname}<#else>请选择${data.label}</#if></font>&nbsp;&nbsp; <span class="glyphicon glyphicon-user"></span>
            <input type="hidden" name="uid" value="${data.value}" />
            <input type="hidden" name="uname" value="${data.uname}" />
            </a>
            <div class="dropdown-menu tpl" role="menu" aria-labelledby="dropdownMenu"></div>
		</div>
	<#elseif (data.type == "client")>
	    <div class="dropdown dropdown-clientbox">
            <a role="button" data-toggle="dropdown" class="btn btn-default" data-target="#" href="javascript:void(0);">
            <font data-name="${data.name}" data-txt="请选择${data.label}" class="selname"><#if (!$.is_empty(data.client_name))>${data.client_name}<#else>请选择${data.label}</#if></font>&nbsp;&nbsp; <span class="glyphicon glyphicon-user"></span>
            <input type="hidden" name="client" value="${data.value}" />
            <input type="hidden" name="client_name" value="${data.client_name}" />
            </a>
            <div class="dropdown-menu tpl" role="menu" aria-labelledby="dropdownMenu"></div>
		</div>
	<#elseif (data.type == "server")>
	    <div class="dropdown dropdown-serverbox">
            <a role="button" data-toggle="dropdown" class="btn btn-default" data-target="#" href="javascript:void(0);">
            <font data-name="${data.name}" data-txt="请选择${data.label}" class="selname"><#if (!$.is_empty(data.value))>${data.value}<#else>请选择${data.label}</#if></font>&nbsp;&nbsp; <span class="glyphicon glyphicon-hdd"></span>
            <input type="hidden" name="${data.name}" value="${data.value}" />
            </a>
            <div class="dropdown-menu tpl" role="menu" aria-labelledby="dropdownMenu"></div>
		</div>
	<#elseif (data.type == "ipmore")>
	    <div class="dropdown dropdown-ipmorebox">
            <a role="button" data-toggle="dropdown" class="btn btn-default" data-target="#" href="javascript:void(0);">
            <font data-name="${data.name}" data-txt="请选择${data.label}" class="selname"><#if (!$.is_empty(data.value))>${data.value}<#else>请选择${data.label}</#if></font>&nbsp;&nbsp; <span class="glyphicon glyphicon-map-marker"></span>
            <input type="hidden" name="ipmore" data-source="${data.value}" value="${data.value}" />
            </a>
            <div class="dropdown-menu tpl" role="menu" aria-labelledby="dropdownMenu"></div>
		</div>
	<#elseif (data.type == "storefrom")>
	    <div class="dropdown dropdown-storefrombox">
            <a role="button" data-toggle="dropdown" class="btn btn-default" data-target="#" href="javascript:void(0);">
            <font data-name="${data.name}" data-txt="请选择${data.label}" class="selname"><#if (!$.is_empty(data.value))>${data.value}<#else>请选择${data.label}</#if></font>&nbsp;&nbsp; <span class="glyphicon glyphicon-globe"></span>
            <input type="hidden" name="storefrom" value="${data.value}" />
            </a>
            <div class="dropdown-menu tpl" role="menu" aria-labelledby="dropdownMenu"></div>
		</div>
	<#elseif (data.type == "jigui")>
		<div class="dropdown sel_jiguibox">
            <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-default" data-target="#" href="javascript:void(0);">
            <font class="selname">选择${data.label}</font>&nbsp;&nbsp; <span class="caret"></span>
            <input type="hidden" data-source="" ${data.require} name="jigui" value="" />
            <input type="hidden" data-source="" name="jiguius" value="" />
            <input type="hidden" data-source="" name="jiguino" value="" />
            </a>
            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu"></ul>
		</div>
	<#elseif (data.type == "net")>
		<div class="dropdown sel_netbox">
            <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-default" data-target="#" href="javascript:void(0);">
            <font class="selname">选择${data.label}</font>&nbsp;&nbsp; <span class="caret"></span>
            <input type="hidden" data-source="" ${data.require} name="net" value="" />
            <input type="hidden" data-source="" name="netport" value="" />
            <input type="hidden" data-source="" name="netno" value="" />
            </a>
            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu"></ul>
		</div>
	<#elseif (data.type == "switch")>
		<a role="button" onfocus="this.blur()" class="btn-switch <#if (parseInt(data.value)==0)>btn-switch-on</#if>" onclick="$.form_ui.switch_onoff(this)" data-target="#" href="javascript:void(0);">
		    <cite class="switch-bg "><#if (parseInt(data.value)==0)>${data.label_on}<#else>${data.label_off}</#if></cite>
            <input type="hidden" name="${data.name}" data-on="${data.label_on}" data-off="${data.label_off}" value="${data.value}" />
        </a>
	<#elseif (data.type == "button")>
		<input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" /><button type="submit" class="btn btn-success ${data.css}" ${data.require}>${data.value}</button>
	</#if>
	<#if (data.desc != '')>
	&nbsp;&nbsp;&nbsp;<span class="font-gray Validform_checktip">${data.desc}</span>
	</#if>
	</div>
	<div class="cl"></div>
</div>
</#if>
<#if (data.cl === true)>
	<div class="cl"></div>
</#if>
</#macro>
</script>