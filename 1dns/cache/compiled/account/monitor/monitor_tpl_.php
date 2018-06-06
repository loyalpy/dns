<script type="text/template" id="tpl_monitor_tpl">
    <#macro rowedit data>
        <div class="am-panel-group" id="accordion">
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent:true, target: '#do-not-say-1'}">
                        <span><strong>监控节点</strong></span><span class="node-title" style="margin-left: 100px;color: gray;"><#if (data.list[0].monitor_node_type==1 || data.type=='set')>我们将随机三个节点进行监控<#else>系统指定节点进行监控</#if></span><span class="am-icon-chevron-down am-fr"></span>
                    </h4>
                </div>
                <div id="do-not-say-1" class="am-panel-collapse am-collapse">
                    <div class="am-panel-bd">
                        <span>监控节点</span>
                        <div class="am-panel-monitor-r">
                            <input type="checkbox" name="monitor_node_type" <#if (data.list[0].monitor_node_type==1 || (data.type=='set'))>checked="checked"</#if> value="<#if (data.type=='set')>1<#else>${data.list[0].monitor_node_type}</#if>"/>&nbsp;&nbsp;<span style="position: relative;top: -2px;">随机</span><br/>
                            <input type="hidden" name="monitor_id" value="<#if (data.type=='edit')>${data.list[0].monitor_id}</#if>">
                            <div class="dis20"></div>
                            <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs <#if (data.type=='set')>am-monitor-set<#else>am-monitor-edit-node</#if>">确认</button>
                            <button type="button" class="am-btn am-btn-default am-radius am-btn-xs am-cancel">取消</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: true, target: '#do-not-say-2'}">
                        <span><strong>监控频率</strong></span><span class="rate-title" style="margin-left: 100px;color: gray;"><#if (data.list[0].monitor_rate==1)>每一分钟一次<#elseif (data.list[0].monitor_rate==3)>每三分钟一次<#elseif (data.list[0].monitor_rate==6)>每六分钟一次<#elseif (data.list[0].monitor_rate==10  || data.type=='set')>每十分钟一次</#if></span><span class="am-icon-chevron-down am-fr"></span>
                    </h4>
                </div>
                <div id="do-not-say-2" class="am-panel-collapse am-collapse">
                    <div class="am-panel-bd">
                        <span>监控频率</span>
                        <div class="am-panel-monitor-r">
                            <input type="hidden" name="monitor_old_rate" value="${data.list[0].monitor_rate}" />
                            <?php $service_group = M("domain")->get_one("domain = '$domain'","service_group");?>
                            <?php $jkpl = tCache::read("service_group_".$service_group)?>
                            <?php $req_arr = array(1,3,6,10);?>
                            <?php foreach($req_arr as $key => $item){?>
                            <?php if($item >= $jkpl['data']['monitorFreq']['value']){?>
                            <input type="radio" name="monitor_rate" <#if (data.list[0].monitor_rate== <?php echo isset($item)?$item:"";?>)>checked="checked"</#if> <#if (data.type=='set')>checked="checked"</#if> value="<?php echo isset($item)?$item:"";?>"/><span> <?php echo isset($item)?$item:"";?>分钟/次</span><br/>
                            <?php }?>
                            <?php }?>
                            <div class="dis20"></div>
                            <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs <#if (data.type=='set')>am-monitor-set<#else>am-monitor-edit-rate</#if>">确认</button>
                            <button type="button" class="am-btn am-btn-default am-radius am-btn-xs am-cancel">取消</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: true, target: '#do-not-say-3'}">
                        <span><strong>监控任务</strong></span><span class="task-title" style="margin-left: 100px;color: gray;"><#if (data.list[0].monitor_type==1 || data.type=='set')>测试http://<#if (data.RRname=='')>@<#else>${data.RRname}</#if>.${data.domain}:<#if (data.type=='set')>80<#else>${data.list[0].monitor_port}</#if>是否正常<#elseif (data.list[0].monitor_type==2)>测试各线路IP ${data.list[0].monitor_port} 端口是否正常<#else>ping测试：测试IP是否通畅</#if></span><span class="am-icon-chevron-down am-fr"></span>
                    </h4>
                </div>
                <div id="do-not-say-3" class="am-panel-collapse am-collapse">
                    <div class="am-panel-bd">
                        <span>监控任务</span>
                        <div class="am-panel-monitor-r">
                            <div class="monitor-right-socket " style="<#if (data.list[0].monitor_type==2 || data.list[0].monitor_type==3)>display:none</#if>">
                                <span style="margin-right: 25px;">检测主机：</span>
                                <input type="text" name="monitor_http" value="<#if (data.RRname=='')>@<#else>${data.RRname}</#if>.${data.domain}" readonly/><br/>
                                <input type="hidden" name="RRname" value="<#if (data.RRname=='')>@<#else>${data.RRname}</#if>"/>
                                <input type="hidden" name="domain" value="${data.domain}"/>
                                <span style="margin-right: 25px;">检测路径：</span>
                                <input type="text" name="monitor_path" value="<#if (data.type=='set')>/<#else>${data.list[0].monitor_path}</#if>"/><br/>
                            </div>
                            <div class="monitor-right-port" style="<#if (data.list[0].monitor_type==3)>display:none</#if>">
                                <span style="margin-right: 25px;">检测端口：</span><input type="text" name="monitor_port" value="<#if (data.type=='set')>80<#else>${data.list[0].monitor_port}</#if>"/><br/>
                            </div>
                            <span style="margin-right: 21px;">检测类型：</span>
                            <input type="radio" name="monitor_type" value="1" <#if (data.list[0].monitor_type==1 || (data.type=='set'))>checked="checked"</#if>/> 页面监控&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="monitor_type" value="2" <#if (data.list[0].monitor_type==2)>checked="checked"</#if>/> 端口(Socket)监控&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="monitor_type" value="3" <#if (data.list[0].monitor_type==3)>checked="checked"</#if>/> Ping监控<br/>
                            <div class="dis20"></div>
                            <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs <#if (data.type=='set')>am-monitor-set<#else>am-monitor-edit-task</#if>">确认</button>
                            <button type="button" class="am-btn am-btn-default am-radius am-btn-xs am-cancel">取消</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: true, target: '#do-not-say-4'}">
                        <span><strong>切换规则</strong></span>
                        <span class="rule-title" style="margin-left: 100px;color: gray;"><#if (data.list[0].monitor_switch==1)>不对域名记录做任何修改<#elseif (data.list[0].monitor_switch==2 || data.type=='set')>智能切换<#elseif (data.list[0].monitor_switch==3)>只暂停<#else>当服务器无法正常访问时，将切换至指定的IP</#if> </span>
                        <span class="am-icon-chevron-down am-fr"></span>
                    </h4>
                </div>
                <div id="do-not-say-4" class="am-panel-collapse am-collapse">
                    <div class="am-panel-bd">
                        <span>切换规则</span>
                        <div class="am-panel-monitor-r">
                            <span>当域名记录无法访问时：</span><br/>
                            <input type="radio" name="monitor_switch" value="1" <#if (data.list[0].monitor_switch==1)>checked="checked"</#if>/><span> 不对域名记录做任何修改 (不切换)</span><br/>
                            <input type="radio" name="monitor_switch" value="2" <#if (data.list[0].monitor_switch==2 || (data.type=='set'))>checked="checked"</#if>/><span> 智能切换</span><br/>
                            <input type="radio" name="monitor_switch" value="3" <#if (data.list[0].monitor_switch==3)>checked="checked"</#if>/><span> 只暂停</span><br/>
                            <input type="radio" name="monitor_switch" value="4" <#if (data.list[0].monitor_switch==4)>checked="checked"</#if>/><span> 切换到您备用的IP (自定义切换)</span><br/>
                            <table class="am-table am-table-bordered am-table-radius" style="width: 600px;display: <#if (data.list[0].monitor_switch!=4)>none</#if>;">
                                <thead>
                                <tr>
                                    <th>线路类型</th>
                                    <th>服务器IP地址</th>
                                    <th>备用IP</th>
                                </tr>
                                </thead>
                                <tbody>
                                <#if (data.type=='set')>
                                    <#list data.list as record>
                                            <tr>
                                                <td><#if ($.is_empty(acls[record.acl]))>${cust_line[record.acl.replace('cust','')].name}<#else>${acls[record.acl].name}</#if></td>
                                                <td class="j-s-ip">${record.RRvalue}</td>
                                                <td>
                                                    <input type="text" name="beiyong_ips" size="36"/>
                                                    <input type="hidden" name="ips" value="${record.RRvalue}"/>
                                                    <input type="hidden" name="acls" value="${record.acl}"/>
                                                    <input type="hidden" name="record_ids" value="${record.record_id}"/>
                                                </td>
                                            </tr>
                                    </#list>
                                <#else>
                                    <#list data.list as record>
                                        <#list record.monitor_item as item>
                                            <tr>
                                                <td><#if ($.is_empty(acls[item.acl]))>${cust_line[item.acl.replace('cust','')].name}<#else>${acls[item.acl].name}</#if></td>
                                                <td class="j-k-ip">${item.ip}</td>
                                                <td>
                                                    <input type="text" name="beiyong_ips" size="36" value="${item.ip2}"/>
                                                    <input type="hidden" name="ips" value="${item.ip}"/>
                                                    <input type="hidden" name="acls" value="${item.acl}"/>
                                                    <input type="hidden" name="record_ids" value="${item.record_id}"/>
                                                </td>
                                            </tr>
                                        </#list>
                                    </#list>
                                </#if>
                                </tbody>
                            </table>
                            <div class="dis20"></div>
                            <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs <#if (data.type=='set')>am-monitor-set<#else>am-monitor-edit-rule</#if>">确认</button>
                            <button type="button" class="am-btn am-btn-default am-radius am-btn-xs am-cancel">取消</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: true, target: '#do-not-say-5'}">
                        <span><strong>通知设置</strong></span><span class="info-title" style="margin-left: 100px;color: gray;"><#if (data.list[0].notify_email==1 || data.type=='set')>宕机时会通过邮箱<#if (data.list[0].notify_mobile==1)>，短信</#if><#if (data.list[0].notify_weixin==1)>，微信</#if>通知您<#else>宕机时不通知您</#if> </span><span class="am-icon-chevron-down am-fr"></span>
                    </h4>
                </div>
                <div id="do-not-say-5" class="am-panel-collapse am-collapse">
                    <div class="am-panel-bd">
                        <?php $userinfo = C("user")->get_cache_userinfo($uid);?>
                        <span>通知设置</span>
                        <div class="am-panel-monitor-r">
                            <span style="margin-right: 21px;">通知方式：</span>
                            <input type="checkbox" name="notify_email" value="1" <#if (data.list[0].notify_email == 1 || data.type=='set')>checked="checked"</#if>/> 邮件通知&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="notify_mobile" value="1" <#if (data.list[0].notify_mobile ==1)>checked="checked"</#if>/> 短信通知&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" <?php if(isset($userinfo['bd']) && $userinfo['bd']['status'] == 2){?><?php }else{?>disabled <?php }?> name="notify_weixin" value="1" <#if (data.list[0].notify_weixin == 1)>checked="checked"</#if>/> <?php if(isset($userinfo['bd']) && $userinfo['bd']['status'] == 2){?>微信通知<?php }else{?><font color="red">尚未绑定微信</font> <?php }?><br/>
                            <hr width="70%"/>
                            <div class="dis10"></div>
                            <span style="margin-right: 21px;">其它邮件：</span><input type="text" name="notify_otheremail" size="35" value="<#if (data.type=='edit')>${data.list[0].notify_otheremail}</#if>"/> <span style="color: gray;">多个邮箱请用; 隔开</span><br/>
                            <span style="margin-right: 21px;">其它手机：</span><input type="text" name="notify_othermobile" size="35" value="<#if (data.type=='edit')>${data.list[0].notify_othermobile}</#if>"/> <span style="color: gray;">多个手机请用; 隔开</span><br/>
                            <div class="dis20"></div>
                            <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs <#if (data.type=='set')>am-monitor-set<#else>am-monitor-edit-info</#if>">确认</button>
                            <button type="button" class="am-btn am-btn-default am-radius am-btn-xs am-cancel">取消</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <#if (data.type=='set')>
            <div class="dis10"></div>
            <a href="<?php echo U("/monitor/monitor_option_record?domain=");?>${data.domain}&RRname=${data.RRname}"><button type="button" class="am-btn am-btn-default am-radius" style="font-size: 15px;width: 240px;margin-left: 125px;">上一步</button></a>&nbsp;&nbsp;&nbsp;
            <button type="button" class="am-btn am-btn-warning am-radius add-monitor aaa" style="font-size: 15px;width: 240px;">开始监控</button>
            <div class="dis30"></div>
        </#if>
    </#macro>
</script>
<script language="javaScript">
    //开始监控
    var domain_monitor_set = function(){

        var RRname = $("input[name='RRname']").val();
        if(RRname === '@'){
            RRname = '';
        }
        var domain = $("input[name='domain']").val();

        var monitor_type = $("input[name='monitor_type']:checked").val();
        var monitor_port = $("input[name='monitor_port']").val();
        var monitor_path = $("input[name='monitor_path']").val();
        var z= /^[0-9]*$/;
        if(!z.test(monitor_port)){
            $.ui.error("监控端口只能为数字");
            return false;
        }

        var monitor_node_type = $("input[name='monitor_node_type']").val();
        var monitor_rate = $("input[name='monitor_rate']:checked").val();
        var monitor_switch = $("input[name='monitor_switch']:checked").val();

        var notify_email = $("input[name='notify_email']:checked").val();
        var notify_mobile = $("input[name='notify_mobile']:checked").val();
        var notify_weixin = $("input[name='notify_weixin']:checked").val();
        if (typeof notify_email == "undefined") {
            notify_mobile = 0;
        }
        if (typeof notify_mobile == "undefined") {
            notify_mobile = 0;
        }
        if (typeof notify_weixin == "undefined") {
            notify_weixin = 0;
        }
        var notify_otheremail = $("input[name='notify_otheremail']").val();
        if (!$.is_empty(notify_otheremail)) {
            if (notify_email != 1) {
                $.ui.error("您未选中邮箱通知!");
                return false;
            }
            var reg = /\w+[@]{1}\w+[.]\w+/;
            var email_arr = notify_otheremail.split(";");
            for(i=0;i<email_arr.length;i++){
                if(!reg.test(email_arr[i]) && !$.is_empty(email_arr[i])){
                    $.ui.error("请您填写正确的邮箱格式");
                    return false;
                }
            }
        }
        var notify_othermobile = $("input[name='notify_othermobile']").val();
        if (!$.is_empty(notify_othermobile)) {
            if (notify_mobile != 1) {
                $.ui.error("您未选中短信通知!");
                return false;
            }
            var reg = /^1[34578]\d{9}$/;
            var mobile_arr = notify_othermobile.split(";");
            for(i=0;i<mobile_arr.length;i++){
                if(!reg.test(mobile_arr[i]) && !$.is_empty(mobile_arr[i])){
                    $.ui.error("请您填写正确的手机格式");
                    return false;
                }
            }
        }
        var ips = new Array();
        $("input[name='ips']").each(function(){
            ips.push($(this).val());
        });
        var ipsLength = ips.length;
        if (ips.length <= 0) {
            $.ui.error("请选择监控IP");
            return false;
        }
        ips = ips.join(",");

        var acls = new Array();
        $("input[name='acls']").each(function(){
            acls.push($(this).val());
        });
        acls = acls.join(",");


        var record_ids = new Array();
        $("input[name='record_ids']").each(function(){
            record_ids.push($(this).val());
        });
        record_ids = record_ids.join(",");

        var beiyong_ips = new Array();
        if (monitor_switch == 4) {
            $("input[name='beiyong_ips']").each(function(){
                var ip_bei = $(this).val();
                //判断备用IP不能与服务器IP相同
                var ip_old = $(this).parent().parent().find(".j-s-ip").html();
                if (ip_bei == ip_old) {
                    $.ui.error("备用IP地址不能与服务器IP地址相同");
                    return false;
                }
                if($.is_empty(ip_bei)){
                    $.ui.error("备用IP地址不能为空");
                    return false;
                }
                if (!$.dns.is_ip(ip_bei)) {
                    $.ui.error("非法的备用ip地址");
                    return false;
                }
                beiyong_ips.push(ip_bei);
            });
            if (beiyong_ips.length != ipsLength) {
                return false;
            }
        }else{
            $("input[name='beiyong_ips']").each(function(){
                beiyong_ips.push($(this).val());
            });
        }

        beiyong_ips = beiyong_ips.join(",");

        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/api/DomainMonitor.Add");?>",
            success:function(res){
                $.ui.close_loading();
                if (res.status == 0) {
                    $.ui.error(res.msg);
                }else{
                    setTimeout(function(){
                        var html = "" + easyTemplate($("#tpl_monitor_success").html());
                        $(".my-monitor-success").html(html);
                        $(".my-monitor-success").find('#doc-modal-1').modal({width: 350,closeViaDimmer:false});
                        $.exeJS("<?php echo U("/monitor/update_rate_queue?rate=");?>"+monitor_rate);
                    },600);
                }
            },
            data:{RRname:RRname,domain:domain,monitor_type:monitor_type,monitor_port:monitor_port,monitor_path:monitor_path,monitor_node_type:monitor_node_type,monitor_rate:monitor_rate,monitor_switch:monitor_switch,notify_email:notify_email,notify_mobile:notify_mobile,notify_weixin:notify_weixin,notify_otheremail:notify_otheremail,notify_othermobile:notify_othermobile,ips:ips,beiyong_ips:beiyong_ips,acls:acls,record_ids:record_ids}
        });
    };
    var monitor_tpl_set = function(){
        $(".am-cancel").unbind("click").bind("click",function(){
            $(this).parent().parent().parent().collapse('close');
        })
        $(".am-monitor-set").unbind("click").bind("click",function(){
            $(this).parent().parent().parent().collapse('close');
        })
        //监控任务切换组
        $(".am-panel-monitor-r input[name='monitor_type']").change(function(){
            var task_val = $(this).val();
            if (task_val == 2) {
                $(".monitor-right-socket").hide();
                $(".monitor-right-port").show();
            }else if(task_val == 3){
                $(".monitor-right-socket").hide();
                $(".monitor-right-port").hide();
            }else{
                $(".monitor-right-socket").show();
                $(".monitor-right-port").show();
            }
        });
        //切换规则切换组
        $(".am-panel-monitor-r input[name='monitor_switch']").change(function(){
            var rule_val = $(this).val();
            if (rule_val == 4) {
                $(this).parent().find("table").show();
            }else{
                $(this).parent().find("table").hide();
            }
        });
        //开始监控
        $(".add-monitor").unbind("click").bind("click",function(){
            domain_monitor_set();
        });
        //监控节点
        $(".am-monitor-edit-node").unbind("click").bind("click",function(){
            var monitor_id = $("input[name='monitor_id']").val();
            var monitor_node_type = $("input[name='monitor_node_type']:checked").val();
            monitor_node_type = typeof monitor_node_type == "undefined"?0:1;
            $.ui.loading();
            $.ajaxPassport({
                url:"<?php echo U("/api/DomainMonitor.Edit?do=node");?>",
                success:function(res){
                    $.ui.close_loading();
                    if (res.status == 1) {
                        $.ui.success(res.msg);
                    }else{
                        $.ui.error(res.msg);
                    }
                },
                data:{monitor_node_type:monitor_node_type,monitor_id:monitor_id}
            });
        });
        //监控频率
        $(".am-monitor-edit-rate").unbind("click").bind("click",function(){
            var monitor_id = $("input[name='monitor_id']").val();
            var monitor_rate = $("input[name='monitor_rate']:checked").val();
            var monitor_old_rate = $("input[name='monitor_old_rate']").val();
            $.ui.loading();
            $.ajaxPassport({
                url:"<?php echo U("/api/DomainMonitor.Edit?do=rate");?>",
                success:function(res){
                    $.ui.close_loading();
                    if (res.status == 1) {
                        $.ui.success(res.msg);
                        $.exeJS("<?php echo U("/monitor/update_rate_queue?rate=");?>"+monitor_rate);
                        if(monitor_rate != monitor_old_rate){
                            $.exeJS("<?php echo U("/monitor/update_rate_queue?rate=");?>"+monitor_old_rate);
                        }                        
                    }else{
                        $.ui.error(res.msg);
                    }
                },
                data:{monitor_rate:monitor_rate,monitor_id:monitor_id}
            });
        });
        //监控任务
        $(".am-monitor-edit-task").unbind("click").bind("click",function(){
            var monitor_id = $("input[name='monitor_id']").val();
            var monitor_type = $("input[name='monitor_type']:checked").val();
            var monitor_rate = $("input[name='monitor_rate']:checked").val();

            var RRname = $("input[name='RRname']").val();
            if(RRname === '@'){
                RRname = '';
            }
            var domain = $("input[name='domain']").val();

            var monitor_port = $("input[name='monitor_port']").val();
            var monitor_path = $("input[name='monitor_path']").val();
            var z= /^[0-9]*$/;
            if(!z.test(monitor_port)){
                $.ui.error("监控端口只能为数字");
                return false;
            }
            $.ui.loading();
            $.ajaxPassport({
                url:"<?php echo U("/api/DomainMonitor.Edit?do=task");?>",
                success:function(res){
                    $.ui.close_loading();
                    if (res.status == 1) {
                        $.ui.success(res.msg);
                        $.exeJS("<?php echo U("/monitor/update_rate_queue?rate=");?>"+monitor_rate);
                    }else{
                        $.ui.error(res.msg);
                    }
                },
                data:{monitor_type:monitor_type,RRname:RRname,domain:domain,monitor_port:monitor_port,monitor_path:monitor_path,monitor_id:monitor_id}
            });
        });
        //监控规则
        $(".am-monitor-edit-rule").unbind("click").bind("click",function(){
            var monitor_id = $("input[name='monitor_id']").val();
            var monitor_switch = $("input[name='monitor_switch']:checked").val();
            var monitor_old_rate = $("input[name='monitor_old_rate']").val();
            if (monitor_switch == 4) {
                var record_ids = new Array();
                $("input[name='record_ids']").each(function(){
                    record_ids.push($(this).val());
                });
                var record_rules = record_ids.length;
                record_ids = record_ids.join(",");

                var beiyong_ips = new Array();
                $("input[name='beiyong_ips']").each(function(){
                    var ipb = $(this).val();
                    //判断备用IP与服务器IP地址是否相同
                    var ipOld = $(this).parent().parent().find(".j-k-ip").html();
                    if (ipb == ipOld) {
                        $.ui.error("备用IP不能与服务器IP地址相同！");
                        return false;
                    }
                    if($.is_empty(ipb)){
                        $.ui.error("备用IP不能为空！");
                        return false;
                    }
                    if (!$.dns.is_ip(ipb)) {
                        $.ui.error("非法的备用ip地址");
                        return false;
                    }
                    beiyong_ips.push(ipb);
                });
                if (beiyong_ips.length != record_rules) {
                    return false;
                }
                beiyong_ips = beiyong_ips.join(",");
            }
            $.ui.loading();
            $.ajaxPassport({
                url:"<?php echo U("/api/DomainMonitor.Edit?do=rule");?>",
                success:function(res){
                    $.ui.close_loading();
                    if (res.status == 1) {
                        $.ui.success(res.msg);
                        $.exeJS("<?php echo U("/monitor/update_rate_queue?rate=");?>"+monitor_old_rate);
                    }else{
                        $.ui.error(res.msg);
                    }
                },
                data:{monitor_id:monitor_id,monitor_switch:monitor_switch,beiyong_ips:beiyong_ips,record_ids:record_ids}
            });
        });
        //通知设置
        $(".am-monitor-edit-info").unbind("click").bind("click",function(){
            var monitor_id = $("input[name='monitor_id']").val();
            var notify_email = $("input[name='notify_email']:checked").val();
            var notify_mobile = $("input[name='notify_mobile']:checked").val();
            var notify_weixin = $("input[name='notify_weixin']:checked").val();
            if (typeof notify_email == "undefined") {
                notify_mobile = 0;
            }
            if (typeof notify_mobile == "undefined") {
                notify_mobile = 0;
            }
            if (typeof notify_weixin == "undefined") {
                notify_weixin = 0;
            }
            var notify_otheremail = $("input[name='notify_otheremail']").val();
            if (!$.is_empty(notify_otheremail)) {
                if (notify_email != 1) {
                    $.ui.error("您未选中邮件通知!");
                    return false;
                }
                var reg = /\w+[@]{1}\w+[.]\w+/;
                var email_arr = notify_otheremail.split(";");
                for(i=0;i<email_arr.length;i++){
                    if(!reg.test(email_arr[i]) && !$.is_empty(email_arr[i])){
                        $.ui.error("请您填写正确的邮箱格式");
                        return false;
                    }
                }
            }
            //判断邮箱格式是否正确
            var notify_othermobile = $("input[name='notify_othermobile']").val();
            if (!$.is_empty(notify_othermobile)) {
                if (notify_mobile != 1) {
                    $.ui.error("您未选中短信通知!");
                    return false;
                }
                var reg = /^1[34578]\d{9}$/;
                var mobile_arr = notify_othermobile.split(";");
                for(i=0;i<mobile_arr.length;i++){
                    if(!reg.test(mobile_arr[i]) && !$.is_empty(mobile_arr[i])){
                        $.ui.error("请您填写正确的手机格式");
                        return false;
                    }
                }
            }
            $.ui.loading();
            $.ajaxPassport({
                url:"<?php echo U("/api/DomainMonitor.Edit?do=info");?>",
                success:function(res){
                    $.ui.close_loading();
                    if (res.status == 1) {
                        $.ui.success(res.msg);
                    }else{
                        $.ui.error(res.msg);
                    }
                },
                data:{monitor_id:monitor_id,notify_email:notify_email,notify_mobile:notify_mobile,notify_weixin:notify_weixin,notify_otheremail:notify_otheremail,notify_othermobile:notify_othermobile}
            });
        });
        //js控制切换按钮改变文字显示效果
        //监控节点
        $("input[name='monitor_node_type']").unbind("click").bind("click",function(){
            if ($(this).is(':checked')) {
                $(".node-title").html("我们将随机三个节点进行监控");
            }else{
                $(".node-title").html("系统指定节点进行监控");
            }
        });
        //监控频率
        $("input[name='monitor_rate']").unbind("click").bind("click",function(){
            var val = $(this).val();
            if (val == 1) {
                $(".rate-title").html("每一分钟一次");
            }else if(val == 3){
                $(".rate-title").html("每三分钟一次");
            }else if(val == 6){
                $(".rate-title").html("每六分钟一次");
            }else if(val == 10){
                $(".rate-title").html("每十分钟一次");
            }
        });
        //监控任务
        $("input[name='monitor_type']").unbind("click").bind("click",function(){
            var RRname = $("input[name='RRname']").val();
            var domain = $("input[name='domain']").val();
            var monitor_port = $("input[name='monitor_port']").val();
            var val = $(this).val();
            if (val == 1) {
                $(".task-title").html("测试http://"+RRname+"."+domain+":"+monitor_port+"是否正常");
            }else if (val == 2) {
                $(".task-title").html("测试各线路IP "+monitor_port+" 端口是否正常");
            }else{
                $(".task-title").html("ping测试：测试IP是否通畅");
            }
        });
        //切换规则
        $("input[name='monitor_switch']").unbind("click").bind("click",function(){
            var val = $(this).val();
            if (val == 1) {
                $(".rule-title").html("不对域名记录做任何修改");
            }else if (val == 2) {
                $(".rule-title").html("智能切换");
            }else if(val == 3){
                $(".rule-title").html("只暂停");
            }else{
                $(".rule-title").html("当服务器无法正常访问时，将切换至指定的IP ");
            }
        });
        //通知设置
        $("input[name='notify_email']").unbind("click").bind("click",function(){
            if ($(this).is(':checked')) {
                $(".info-title").html("宕机时会通过邮箱通知您");
            }
        })
        $("input[name='notify_mobile']").unbind("click").bind("click",function(){
            if ($(this).is(':checked')) {
                $(".info-title").html("增加短信通知方式");
            }
        })
        $("input[name='notify_weixin']").unbind("click").bind("click",function(){
            if ($(this).is(':checked')) {
                $(".info-title").html("增加微信通知方式");
            }
        })
    }
</script>