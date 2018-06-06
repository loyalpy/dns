<script type="text/template" id="tpl_form">
<#macro form data>
<#if (data.type == "hidden")>
	<input type="hidden" name="${data.name}" value="${data.value}" />
<#elseif (data.type == "html")>
	${data.html}
<#else>
	<div class="form-item form-item-${data.type} form-${data.name} ${data.item_css}">
    <label><#if (!$.is_empty(data.require))><font class='font-red'>*</font> </#if>${data.label}<#if (!$.in_array(data.label,['','-']))>：</#if></label>
    <div class="item-v">
    <#if ($.in_array(data.type,['text','password','file']))>
    	<input <#if (data.disabled == 1)>disabled="disabled"</#if> type="${data.type}" class="${data.css}" ${data.require} name="${data.name}" id="M${data.name}" value="${data.value}" />
        <#if (!$.is_empty(data.data_sr) && data.data_sr.length>0)>
        <div class="input-tips" style="display:none">
        <#list data.data_sr as list>
		   <a data-v="${list.v}" href="javascript:void(0);">${list.v}</a>
		</#list>
        </div>
        </#if>
	<#elseif (data.type == "textarea")>
		<textarea <#if (data.disabled == 1)>disabled="disabled"</#if> class="${data.css}" ${data.require} name="${data.name}" id="M${data.name}">${data.value}</textarea>	
	<#elseif (data.type == "sdate")>
		<input onclick="laydate()" readonly <#if (data.disabled == 1)>disabled="disabled"</#if> type="text" class="low ${data.css}" ${data.require} name="${data.name}" value="${data.value}" /> 
        <span class="laydate-icon"></span> 
	<#elseif (data.type == "radio")>
		<div class="tradio" data-name="${data.name}">
           <input type="hidden" name="${data.name}" value="${data.value}" ${data.require} />
           <#list data.data_sr as list>
		   <a data-v="${list.key}" href="javascript:void(0);">
           <cite></cite> ${list.v}
           </a>
		   </#list>
        </div>
	<#elseif (data.type == "checkbox")>
		<div class="tcheckbox" data-name="${data.name}">
           <input type="hidden" name="${data.name}" value="${data.value}" ${data.require} />
           <#list data.data_sr as list>
		   <a data-v="${list.key}" href="javascript:void(0);">${list.v}</a>
           </#list>
        </div>
	<#elseif (data.type == "value")>
		<span class="tvalue ${data.css}">${data.value}</span>
		<input type="hidden" name="${data.name}" value="<#if ($.is_empty(data.hid_value))>${data.value}<#else>${data.hid_value}</#if>" />
	<#elseif (data.type == "select")>
        <div class="tselect" data-name="${data.name}" data-label="请选择${data.label}">
           <a href="javascript:void(0)" class="tselect-text e"></a>
           <input type="hidden" name="${data.name}" value="${data.value}" ${data.require} />
		   <input type="hidden" name="${data.name}_text" value="${data.value}" />           
           <div class="tselect-box" style="display:none">
           <div class="in">
           <a class="sel<#if (data.disabled == 1)>1<#else>0</#if>" data-v="" href="javascript:void(0);">不选</a>
           <#list data.data_sr as list>
		   <a data-v="${list.key}" title="${list.v}" class="sel<#if (data.disabled == 1 && data.value != list.key)>1<#else>0</#if> e" href="javascript:void(0);">${list.v}</a>
		   </#list>
           </div>
           </div>
		</div>		
	<#elseif (data.type == "date")>
        <#if (!$.is_empty(data.data_year) && data.data_year.length>0)>
		<div class="tselect tdate tdate-year" data-name="${data.name}s[0]" data-start="${data.data_year[0]}" data-end="${data.data_year[1]}" data-zj="<#if ($.is_empty(data.data_zj))>0<#else>${data.data_zj}</#if>" data-label="年">
           <a href="javascript:void(0)" class="tselect-text e"></a>           
           <div class="tselect-box" style="display:none">
           <div class="in"></div>
           </div>
           <input type="hidden" name="${data.name}s[0]" value="" />
        </div>
        </#if>
        <#if (!$.is_empty(data.data_month))>
        <div class="tselect tdate tdate-month" data-name="${data.name}s[1]" data-label="月">
           <a href="javascript:void(0)" class="tselect-text e"></a>           
           <div class="tselect-box" style="display:none">
           <div class="in"></div>
           </div>
           <input type="hidden" name="${data.name}s[1]" value="" />
        </div>
        </#if>
        <#if (!$.is_empty(data.data_day))>
		<div class="tselect tdate tdate-day" data-name="${data.name}s[2]" data-label="日">
           <a href="javascript:void(0)" class="tselect-text e"></a>           
           <div class="tselect-box" style="display:none">
           <div class="in"></div>
           </div>
           <input type="hidden" name="${data.name}s[2]" value="" />
        </div>
        </#if>
        <input type="hidden" name="${data.name}" value="${data.value}" class="hid_date" ${data.require} />
	<#elseif (data.type == "area")>
		<div class="tselect tarea tarea-0" data-name="${data.name}s[0]" data-label="省/市">
           <a href="javascript:void(0)" class="tselect-text e"></a>           
           <div class="tselect-box" style="display:none">
           <div class="in"></div>
           </div>
           <input type="hidden" name="${data.name}s[0]" value="" />
        </div>
        <div class="tselect tarea tarea-1" data-name="${data.name}s[1]" data-label="市/区">
           <a href="javascript:void(0)" class="tselect-text e"></a>           
           <div class="tselect-box" style="display:none">
           <div class="in"></div>
           </div>
           <input type="hidden" name="${data.name}s[1]" value="" />
        </div>
		<div class="tselect tarea tarea-2" data-name="${data.name}s[2]" data-label="县/市">
           <a href="javascript:void(0)" class="tselect-text e"></a>           
           <div class="tselect-box" style="display:none">
           <div class="in"></div>
           </div>
           <input type="hidden" name="${data.name}s[2]" value="" />
        </div>
        <input type="hidden" name="${data.name}" value="${data.value}" class="hid_area" ${data.require} />
	<#elseif ($.in_array(data.type,['city','trade_cate','job_cate']))>
        <div class="tselect tcate tcate-${data.type}" data-name="${data.name}" data-num="${data.num}"  data-label="请选择${data.label}">
           <a href="javascript:void(0)" class="tselect-text e"></a>
           <input type="hidden" name="${data.name}" value="${data.value}" ${data.require} />  
           <div class="tselect-box" style="display:none">
           <div class="in">
                <#if (data.num > 1)>
                <div class="select-top">
                   <div class="top-label">已选择:</div>                  
                   <div class="top-btn">
                    <button class="btn0" type="button">清空</button>
                    <button class="btn1" type="button">确认</button>
                   </div>
                   <div class="top-in"></div>
                </div>
                </#if>
                <div class="select-bod"></div>
           </div>
           </div>
		</div>	
	<#elseif (data.type == "switch")>
        <div class="tswitch" data-name="${data.name}" data-value="0" data-label_on="${data.label_on}" data-label_off="${data.label_off}">
           <input type="hidden" name="${data.name}" value="${data.value}" ${data.require} />
           <a data-v="1" href="javascript:void(0);">
           <cite></cite> <span></span>
           </a>
        </div>
	<#elseif (data.type == "button")>
		<input type="hidden" value="<?php echo tUtil::hash();?>" name="hash" />
        <button type="submit" class="${data.css}" ${data.require}>${data.value}</button>
	</#if>
	<#if (!$.is_empty(data.desc))>
		<span class="font-gray Validform_checktip">${data.desc}&nbsp;</span>
	</#if>

	<#if (!$.is_empty(data.afer))>
	${data.afer}
	</#if>
	</div>	

	<div class="cl"></div>
	</div>

    <#if (data.cl)>
	<div class="cl"></div>
	</#if>
</#if>
</#macro>
</script>