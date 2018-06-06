<!--域名组列表-->
<script type="text/template" id="tpl_domain_group">
    <#macro rowedit data>
        <#list data.list as nav>
            <#if (data.type =="nav")>
                <#if (nav.count >0)>
                    <li class="li_group_id" ><a href="javascript:void(0)" data-tit="${nav.name}" class="group_id" data-group_id="${nav.group_id}">${nav.name} <span class="am-badge am-round am-badge-warning">${nav.count}</span>&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
                </#if>
            <#else>
                <#if (nav.group_id >1000)>
                    <li class="li_set_group" id="li_set_group">
                        <a href="javascript:void(0)" class="set_group" data-group_id="${nav.group_id}" hidefocus="false">
                            <span style="font-size:12px;color:#999;">移动到</span> ${nav.name}
                            <span class="am-icon-trash"  id="Dgroup"  data-group_id="${nav.group_id}" data-group_name="${nav.name}" ></span>
                        </a>
                    </li>
                </#if>
            </#if>
        </#list>
    </#macro>
</script>
<!--域名列表-->
<script type="text/template" id="tpl_domain_list">
    <#macro rowedit data>
            <#if (data.list.length > 0)>
            <#list data.list as domain>
                <tr class="domain_${domain.domain_id} ">
                    <td><input type="checkbox" name="domainId[]" value="${domain.domain_id}" data-status="${domain.status}"/></td>
                    <td>
                    <img src="<?php echo U("/domains/favicon?domain=");?>${domain.domain}" class="favicon" />
                        <a href="<?php echo U("/domains/dns/");?>${domain.domain}" class="fb"><#if (domain.is_cn == 1)>${domain.domain_cn}<#else>${domain.domain}</#if>
                            <#if (domain.records>0)><span style="color: #ccc;font-weight: 400;">&nbsp;(${domain.records})</span></#if>
                        </a>
                        <#if (domain.indel==1)>
                        <font class="am-text-danger" title="系统锁定">[<i class="am-icon-lock"></i> 已锁]</font>
                        </#if>
                    </td>
                    <td>
                    <#if (!$.is_empty(service_group[domain.service_group]) && domain.service_group != 'free')>
                       <div class="texticon texticon-${domain.service_group} domian-upgrade" data-domain="${domain.domain}">              
                                 ${cutstr(service_group[domain.service_group].name,1)}                
                        </div>
                        <#if (domain.service_expiry>0)>&nbsp;
<span class="am-text-sm <#if (""+domain.service_expiry > '<?php echo isset($timestamp)?$timestamp:"";?>')>am-text-success<#else>am-text-danger</#if>">${$.time_to_string(domain.service_expiry,'Y-m-d')}</span></#if>
                        <#else>
                        <span class="am-font-gray am-text-sm">免费</span>
                        </#if>

                    </td>
                    <td class="am-text-right">

                        <#if (domain.status == 0)>
                            <cite class="am-icon-minus-circle am-text-danger" title="域名解析已暂停"></cite>
                        </#if>&nbsp;

                        <#if (domain.inlock == 1)>
                            <cite class="am-icon-lock am-font-black" title="域名已锁定"></cite>
                        </#if>&nbsp;

                        <#if (domain.inns != 1)>
                            <cite class="am-icon-exclamation-circle am-text-warning" title="域名DNS未指向我们"></cite>
                        </#if>&nbsp;

                        <button class="am-ext-btn domian-upgrade" data-service="${domain.service_group}" data-domain="${domain.domain}" title="升级套餐">升级</button>

                        <a class="am-ext-btn" href="<?php echo U("/monitor/monitor_option_domain?domain=");?>${domain.domain}">监控</a>
                    </td>
                </tr>
            </#list>
            <#else>
                <tr class="d-t-intor">
                <td class="am-text-sm" colspan="5">
                <a href="##" class="am-icon-exclamation-circle am-text-danger am-text-lg"></a> <a href="##" class="am-font-gray">未找到域名?</a> &nbsp;<a href="<?php echo U("/domains/add");?>">添加域名</a></td>
                </tr>
            </#if>
    </#macro>
</script>
<!--域名列表-->
<script type="text/template" id="tpl_domain_add">
<#macro rowedit domain>
<#if ($.is_empty(domain.domain_id))>
    <td><input type="checkbox" name="domainId[]" value="${domain.domain_id}" data-status="${domain.status}"/></td>
    <td>
    <img src="<?php echo U("static@/public/images/favicon.gif");?>" class="favicon" />
    <input type="text" style="width:290px;display: inline-block;" name="domain" />&nbsp;
    <button type="button" class="am-btn am-btn-secondary btn-add-save">确定</button>
    <button type="button" class="am-btn am-btn-default btn-add-cancel">取消</button>
    </td>
    <td colspan="2" class="domain-add-exists"></td>
<#else>
    <td><input type="checkbox" name="domainId[]" value="${domain.domain_id}" data-status="${domain.status}"/></td>
    <td><img src="<?php echo U("/domains/favicon?domain=");?>${domain.domain}" class="favicon" />
    <a href="<?php echo U("/domains/dns/");?>${domain.domain}">
    <#if (domain.is_cn == 1)>${domain.domain_cn}<#else>${domain.domain}</#if>
    </a>
    </td>
    <td><span class="am-font-gray am-text-sm">免费</span></td>
    <td class="am-text-right">
    <button class="am-ext-btn domian-upgrade" data-service="free" data-domain="${domain.domain}" title="升级套餐">升级</button>
    <a class="am-ext-btn" href="<?php echo U("/monitor/monitor_option_domain?domain=");?>${domain.domain}">监控</a>
    </td>
</#if>
</#macro>
</script>
<script language="javascript">
    var service_group = <?php echo JSON::encode(M("@domain_service")->get_cache_list(0));?>;
    var supper_domain = '<?php echo "(".implode(")|(",tValidate::support_domain()).")";?>';
    var add_domain = function(cur_row){
         if($.is_empty(cur_row)){     //添加
            var data = {domain_id:0,status:1};
            formhtml = "" + easyTemplate($("#tpl_domain_add").html(),data);
            $(".listbody tbody.tpl").prepend("<tr>"+formhtml+"</tr>");
            cur_row = $(".listbody tbody.tpl tr:first");
            cur_row.find("input[name='domain']").focus();
         }
         //增加域名解析提交记录操作
        cur_row.find("input").keypress(function(e){
            var obj  = this;
            var new_new_cur_row = $(obj).parent().parent();
            if(e.keyCode == 13){
                new_new_cur_row.find(".btn-add-save").click();
            }
        });
         //取消
         cur_row.find("button.btn-add-cancel").unbind("click").bind("click",function(){
            var obj = this;
            var cur_cur_row = $(obj).parent().parent();
            cur_cur_row.empty().remove();
         });
         cur_row.find("button.btn-add-save").unbind("click").bind("click",function(){
            var obj = this;
            var domain = $(obj).parent().find("input[name='domain']").val();
            if($.is_empty(domain)){
                $.ui.error("请输入合法的域名");
                return false;
            }
            if(!$.dns.is_domain(domain)){
                $.ui.error("域名非法/不支持");
                return false;
            }
            $.ajaxPassport({
                url:U("/api/Domain.AddByUid"),
                success:function(res){
                    if(res.status == 1){
                        $.ui.success("添加成功");

                        var data = {domain_id:res.data.domain_id,status:1,domain:res.data.domain,domain_cn:res.data.domain_cn,is_cn:res.data.is_cn};
                        var cur_cur_row = $(obj).parent().parent();
                        cur_cur_row.addClass("domain_"+res.data.domain_id);
                        cur_cur_row.empty().html("" + easyTemplate($("#tpl_domain_add").html(),data));

                        //升级套餐
                        cur_cur_row.find(".domian-upgrade").unbind("click").bind("click",function(){
                            var goods_name = $(this).attr("data-domain");
                            add_cart_step1(1,0,"",goods_name);
                        });

                    }else{
                        if (!$.is_empty(res.data.email)) {
                            var hl = "<span style=\"font-size: 12px;color: red\">域名已被其他用户添加，点击<a href=\"javascript:;\" class=\"domainFind\">取回</a></span>";
                            cur_row.find(".domain-add-exists").html(hl);
                            //域名找回
                            cur_row.find(".domain-add-exists .domainFind").click(function () {
                                domain_find_op(res.data.domain);
                            });
                        }else{
                            $.ui.error(res.msg);
                        }
                    }
                },
                data:{domain:domain}
            });
         });
    }
</script>
<!--域名找回开始-->
<script type="text/template" id="tpl_domain_find">
    <#macro rowedit domain>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" style="border-bottom: 1px solid silver;text-align: left;color:black;padding-bottom: 16px;">
                    <i class="am-icon-facebook-official" style="color: #EB8500;"></i>&nbsp;&nbsp;域名找回
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                </div>
                <div class="dis10"></div>
                <div class="am-modal-bd" style="font-size: 14px;text-align: left;padding: 30px 30px 60px 30px;">
                    <p >如果您在添加自己的域名时提示该域名已存在，可尝试使用此功能找回您的域名，找回域名需要向您域名的Whois信息的联系人邮箱里发送一封邮件作为所有权验证，请检查要找回域名的邮箱是不是您的邮箱，如邮箱不正确可先到域名注册商处修改Whois联系邮箱后再来执行此找回操作。</p>
                    <div class="dis30"></div>
                    <div>
                        <span>请输入要找回的域名：</span><strong style="font-size: 28px;">www.</strong>
                        <input type="text" class="am-form-field am-radius am-input-sm" name="domain" style="width: 240px;display: inline-block"/>
                        <button type="button" class="am-btn am-btn-secondary am-radius domain-find">下一步</button>
                    </div>
                </div>
                <div class="dis30"></div>
            </div>
        </div>
    </#macro>
</script>
<script language="javascript">
    var domain_find_op = function (find_domain) {
        var html = "" + easyTemplate($("#tpl_domain_find").html());
        $(".my-domian-find").html(html);
        $(".my-domian-find").find('#doc-modal-1').modal({width: 650});

        if (find_domain) {
            $(".my-domian-find").find("#doc-modal-1 input[name='domain']").val(find_domain);
        }
        $(".domain-find").click(function () {
            var domain = $(this).parent().find("input[name='domain']").val();
            if ($.is_empty(domain) || !$.dns.is_domain(domain)) {
                $.ui.error("请输入合法域名！");
                return false;
            }
            $.ui.loading($("#doc-modal-1"));
            $.ajaxPassport({
                url:"<?php echo U("domains/domain_find?hash=");?><?php echo tUtil::hash();?>",
                type:'POST',
                success:function (res) {
                    $.ui.close_loading($("#doc-modal-1"));
                    if (res.error == 0) {
                        setTimeout(function () {
                            $.redirect("<?php echo U("domains/domain_find?email=");?>"+res.message);
                        },300)
                    }else{
                        $.ui.error(res.message);
                    }
                },
                data:{domain:domain}
            });
        });
    }
</script>
<!--域名找回结束-->
<!--域名过户开始-->
<script type="text/template" id="tpl_domain_transfer">
    <#macro rowedit domain>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1-t">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" style="border-bottom: 1px solid silver;text-align: left;color:black;padding-bottom: 16px;">
                    <i class="am-icon-share-square-o" style="color: #EB8500;"></i>&nbsp;&nbsp;域名过户&nbsp;&nbsp;<span style="font-size: 12px;color: grey">小贴士：过户后域名将不再属于您，请选择操作！</span>
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                </div>
                <div class="dis10"></div>
                <div class="am-modal-bd" style="font-size: 14px;text-align: left;padding: 30px 30px 60px 30px;text-align: center;">
                    <span style="font-weight: 600;font-size: 16px">过户对象：</span>
                    <input type="text" class="am-form-field am-radius am-input-sm" name="email" style="width: 280px;display: inline-block;height: 32px"/><br/>
                    <small style="color: gray;margin-left: 15px">请输入域名过户的对象,对象格式为邮箱</small><br/>
                    <div class="dis15"></div>
                    <span style="font-weight: 600;font-size: 16px">登录密码：</span>
                    <input type="password" class="am-form-field am-radius am-input-sm" name="password" placeholder="" style="width: 280px;display: inline-block;height: 32px"/><br/>
                    <small style="color: gray;margin-left: -85px;">请输入账户登录密码</small>
                    <div class="dis30"></div>
                    <button type="button" class="am-btn am-btn-warning domain-transfer">确定</button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="am-btn am-btn-default" data-am-modal-close>返回</button>
                </div>
                <div class="dis30"></div>
            </div>
        </div>
    </#macro>
</script>
<script language="javascript">
    var domain_transfer_op = function () {
        var ids_tmp  = $.fetch_ids("domainId[]");
        ids = ids_tmp.split(",");
        if (ids == "") {
            $.ui.error('请选择要过户的域名！');
            return false;
        }
        var html = "" + easyTemplate($("#tpl_domain_transfer").html());
        $(".my-domian-transfer").html(html);
        $(".my-domian-transfer").find('#doc-modal-1-t').modal({width: 600});

        $(".domain-transfer").click(function () {
            var email = $(this).parent().find("input[name='email']").val();
            var password = $(this).parent().find("input[name='password']").val();
            $.ui.loading($("#doc-modal-1-t"));
            $.ajaxPassport({
                url:"<?php echo U("/api/Domain.DomainTransfer");?>",
                success:function (res) {
                    $.ui.close_loading($("#doc-modal-1-t"));
                    if (res.status == 1) {
                        setTimeout(function () {
                            $.redirect("<?php echo U("domains/domain_transfer");?>");
                            //JS后台执行域名过户
                            $.exeJS("<?php echo U("/api/Domain.DomainTransfer?domain_id=");?>"+ids.join(",")+"&email="+email);
                        },500)
                    }else{
                        $.ui.error(res.msg);
                    }
                },
                data:{email:email,password:password}
            });
        });
    }
</script>
<!--域名过户结束-->
<script language="javascript" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/js/domains.js";?>"></script>















