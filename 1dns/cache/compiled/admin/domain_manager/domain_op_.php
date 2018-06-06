<!--新增域名 start-->
<script type="text/template" id="tpl_domain_add">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="form-content">
            <form action="<?php echo U("/domain_manager/domain_edit");?>" method="post" class="theform form form-2" role="form">
                <div class="tpl"></div>
            </form>
        </div>
    </#macro>
</script>
<script language="javascript">
    var add_domain_formdata = {
        domain:{type:"text",label:"新增域名",name:"domain",value:"",data_sr:[],css:"",require:"",desc:"格式(8jdns.com)",item_css:""},
        domain_id:{type:"hidden",label:"",name:"domain_id",value:"<?php echo isset($domain_id)?$domain_id:"";?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
        btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
    };
    var edit_func_add = function(obj){
        var edit_c = $("#tpl_domain_add").html();
        var url = $(obj).attr("data-url");
        edit_c = "" + easyTemplate(edit_c,{title:"新增域名"});
        $("#myModal").find(".modal-dialog").width(580);
        $("#myModal").find(".modal-content").html(edit_c);
        $.loadform(add_domain_formdata,url,function(res){
            if(res.status == 1){
                $.tips(res.msg,"success");
                $('#myModal').modal("hide");
                setTimeout(function(){
                    loadlist();
                },500);
            }else{
                $.tips(res.msg,"error");
            }
            return false;
        });
        $('#myModal').modal();
    }
</script>
<!--新增域名 end-->

<!--修改域名 start-->
<script type="text/template" id="tpl_domain_edit">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="form-content">
            <form action="<?php echo U("/domain_manager/domain_edit");?>" method="post" class="theform form form-2" role="form">
                <div class="tpl"></div>
            </form>
        </div>
    </#macro>
</script>
<script language="javascript">
    var edit_domain_formdata = {
        domain:{type:"ivalue",label:"域名(顶级)",name:"domain",value:"",data_sr:[],css:"font-gray",require:"",desc:"(默认不可修改)",item_css:"",disabled:1},
        ttl:{type:"text",label:"默认TTL",name:"ttl",value:"",data_sr:[],css:"",require:"datatype='n'",desc:"单位:S(秒)",item_css:""},
        qps:{type:"text",label:"最大QPS",name:"qps",value:"",data_sr:[],css:"",require:"datatype='n'",desc:"Qps(秒)",item_css:""},
        bz:{type:"textarea",label:"备注",name:"bz",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
        domain_id:{type:"hidden",label:"",name:"domain_id",value:"<?php echo isset($domain_id)?$domain_id:"";?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
        btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
    };
    var edit_func = function(obj){
        var edit_c = $("#tpl_domain_edit").html();
        var url = $(obj).attr("data-url");
        edit_c = "" + easyTemplate(edit_c,{title:"修改"});
        $("#myModal").find(".modal-dialog").width(760);
        $("#myModal").find(".modal-content").html(edit_c);
        $.loadform(edit_domain_formdata,url,function(res){
            if(res.status == 1){
                $.tips(res.msg,"success");
                $('#myModal').modal("hide");
                setTimeout(function(){
                    loadlist();
                },500);
            }else{
                $.tips(res.msg,"error");
            }
            return false;
        });
        $('#myModal').modal();
    }

  // locked
    var recharge_btn = function(obj){
        if(confirm("你确定执行该操作吗？")){
            var url = $(obj).attr("data-url");
            var status =$(obj).data("status")==1?0:1;
            $.ajaxPassport({
                url:url,
                success:function(res){
                    if(res.status!=1){
                        $.tips(res.msg,"error");
                    }else{
                        $.tips(res.msg,"success");
                        loadlist();
                    }
                },
                data:{status:status}
            })
        }
    }
    //delete
    var del_func = function(obj){
        if(confirm("你确定要删除该数据吗?删除后数据不可恢复！")){
            var url = $(obj).attr("data-url");
            $.ajaxPassport({
                url:url,
                success:function(res){
                    if(res.status != 1){
                        $.tips(res.msg,"error");
                    }else{
                        $.tips(res.msg,"success");
                        loadlist();
                    }
                }
            })
        }
    }
    //batch delete
    var ids= new Array();
    $(".del-domain").unbind("click").bind("click",function(){
        var ids_tmp  = $.fetch_ids("domains[]");
        ids = ids_tmp.split(",");
        if (ids == "") {
            $.ui.error('请选择要批量删除的域名！')
            return;
        }
        $.ui.confirm(function(){
            batch_del_domain_op(0);
        },"你确定要批量删除域名吗？") ;
    });
    var num = 0;
    var batch_del_domain_op = function(i){
        var id=ids[i];
        if( i >= ids.length || typeof ids[i] == "undefined"){
            num = 0;
            return false;
        }
        $.ui.loading();
        $.ajaxPassport({
            url: "<?php echo U("/domain_manager/domain_del");?>",
            success: function (res) {
                if (res.status == 1) {
                    num++;
                    var domainId = ".domain_"+ids[i];
                    setTimeout(function () {
                        $(domainId).remove();
                    }, 50);
                }
                //执行最后一次时提示操作结果
                if (i == (ids.length - 1)) {
                    if (num >0) {
                        $.ui.close_loading();
                        $.tips("成功删除"+num+"条域名","success");
                        //如果是删除,重新加载
                        loadlist();
                    }else{
                        $.ui.close_loading();
                        $.tips("批量删除域名失败","error");
                    }
                }
                batch_del_domain_op(i+1);
            },
            data: {domain_id:id},
        });
    }
    //refresh
    var refre_btn =function(obj){
        var url = $(obj).attr("data-url");
        $.ajaxPassport({
            url:url,
            success:function(res){
                if(res.error == 1){
                    $.tips(res.message,"error");
                }else{
                    $.tips(res.message,"success");
                }
            }
        })
    }
</script>
<!--修改域名 end-->

<!--切换用户 start-->
<script type="text/template" id="tpl_setting_edit">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="form-content">
            <form action="<?php echo U("/domain_manager/domain_change?do=change_user");?>" method="post" class="theform form form-2" role="form">
                <div class="tpl"></div>
            </form>
        </div>
    </#macro>
</script>
<script language="javascript">
    var setting_formdata = {
        uid:{type:"uid",label:"域名管理者",name:"uid",value:"",data_sr:[],css:"",require:"",desc:'',item_css:""},
        domain_id:{type:"hidden",label:"",name:"domain_id",value:"<?php echo isset($domain_id)?$domain_id:"";?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
        btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
    };
    var edit_setting_change_func = function(obj){
        var edit_c = $("#tpl_setting_edit").html();
        var url = $(obj).attr("data-url");
        edit_c = "" + easyTemplate(edit_c,{title:"切换用户"});
        $("#myModal").find(".modal-dialog").width(550);
        $("#myModal").find(".modal-content").html(edit_c);
        $.loadform(setting_formdata,url,function(res){
            if(res.status == 1){
                $.tips(res.msg,"success");
                $('#myModal').modal("hide");
                setTimeout(function(){
                    loadlist();
                },500);
            }else{
                $.tips(res.msg,"error");
            }
            return false;
        },function(){
            get_userlist(1,0,"",".theform");
        },".theform");
        $('#myModal').modal();

    }
</script>
<!--切换用户 end-->

<!-- 切换套餐  start-->
<script type="text/template" id="tpl-setting-service">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="form-content">
            <form action="<?php echo U("/domain_manager/domain_change?do=change_service");?>" method="post" class="theform form form-2" role="form">
                <div class="tpl"></div>
            </form>
        </div>
    </#macro>
</script>
<script language="javascript">
    var service_groups = <?php echo JSON::encode($service_group);?>;
    var setting_service_formdata = {
        service_group: {type: "select", label: "服务套餐", name: "service_group", value: "", disabled: 0, data_sr:<?php echo JSON::encode($service_group_v);?>, css: "", require: "", desc: "", item_css: ""},
        service_expiry:{type:"date",label:"套餐时间",name:"service_expiry",value:"",data_sr:[],css:"",require:"",desc:"为空表示永久",item_css:""},
        domain_id:{type:"hidden",label:"",name:"domain_id",value:"<?php echo isset($domain_id)?$domain_id:"";?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
        btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
    };
    var edit_setting_service_func = function(obj){
        var edit_c = $("#tpl-setting-service").html();
        var url = $(obj).attr("data-url");
        edit_c = "" + easyTemplate(edit_c,{title:"切换套餐"});
        $("#myModal").find(".modal-dialog").width(560);
        $("#myModal").find(".modal-content").html(edit_c);
        $.loadform(setting_service_formdata,url,function(res){
            loadlist();
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
        },".theform");
        $('#myModal').modal();
    }
</script>
<!-- 切换套餐  end-->

<!-- 切换服务器组 start-->
<script type="text/template" id="tpl-setting-group">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="form-content">
            <form action="<?php echo U("/domain_manager/domain_change?do=change_group");?>" method="post" class="theform form form-1" role="form">
                <div class="tpl"></div>
            </form>
        </div>
    </#macro>
</script>
<script language="javascript">
    var ns_groups = <?php echo JSON::encode($ns_group);?>;
    var setting_group_formdata = {
        domain:{type:"ivalue",label:"更改NS域名",name:"domain",value:"",data_sr:[],css:"font-org",require:"",desc:"",item_css:""},
        ns_group_show:{type:"ivalue",label:"当前服务器组",name:"ns_group_show",value:"<?php echo isset($domain_id)?$domain_id:"";?>",data_sr:[],css:"font-gray",require:"",desc:"",item_css:""},
        ns_group:{type:"select",label:"请选择服务器组",name:"ns_group",value:"",disabled:0,data_sr:<?php echo JSON::encode($ns_group_v);?>,css:"",require:"",desc:"",item_css:"font-red"},
        domain_id:{type:"hidden",label:"",name:"domain_id",value:"<?php echo isset($domain_id)?$domain_id:"";?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
        btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
    };
    var edit_setting_group_func = function(obj){
        var edit_c = $("#tpl-setting-group").html();
        var url = $(obj).attr("data-url");
        edit_c = "" + easyTemplate(edit_c,{title:"切换服务器组"});
        $("#myModal").find(".modal-dialog").width(710);
        $("#myModal").find(".modal-content").html(edit_c);
        $.loadform(setting_group_formdata,url,function(res){
            loadlist();
            return true;
        },".theform");
        $('#myModal').modal();
    }

</script>
<!-- 切换服务器组 end-->

<!-- 域名操作日志 start-->
<script type="text/template" id="tpl_server_loglist">
    <#macro rowedit data>
        <div class="modal-header" style="text-align: center">
            <h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
            <div class="dis10"></div>
        </div>
        <div class="recordlist">
            <table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
                <col width="130px" />
                <col width="190px" />
                <col width="130px" />
                <col />
                <thead>
                <tr class="active">
                    <th>操作时间</th>
                    <th>操作IP</th>
                    <th>操作项</th>
                    <th>说明</th>
                </tr>
                </thead>
                <tbody>
                <#if (data.log.list.length>0)>
                    <#list data.log.list as list>
                        <tr>
                            <td class="font-gray">${$.time_to_string(list.dateline,'Y-m-d H:i')}</td>
                            <td class="font-gray">${list.ipaddr}</td>
                            <td class="font-green">${list.modi_item}</td>
                            <td class="font-gray">${list.modi_log}</td>
                        </tr>
                    </#list>
                    <#else>
                        <tr><td colspan="4"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无操作日志！</span></tr>
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
    var edit_setting_log_func = function(page,domain_id){
        $.ajaxPassport({
            url:"<?php echo U("/domain_manager/domain_change?domain_id=");?>"+domain_id,
            success:function(res){
                if(res.error == 1){
                    $.tips(res.message,"error");
                }else{
                    res.title = "域名操作日志("+res.log.total+")";
                    var edit_c = "" + easyTemplate($("#tpl_server_loglist").html(),res);
                    $("#user_reg_month_"+domain_id).html(edit_c);
                }
            },
            data:{page:page}
        });
    }
</script>
<!-- 域名操作日志 end-->

<!-- 域名切换日志 start-->
<script type="text/template" id="tpl_exchange_loglist">
    <#macro rowedit data>
        <div class="modal-header" style="text-align: center">
            <h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
            <div class="dis10"></div>
        </div>
        <div class="recordlist">
            <table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
                <col width="230px"/>
                <col width="150px"/>
                <col width="150px"/>
                <col width="100px"/>
                <col width="150px"/>
                <col />
                <thead>
                <tr>
                    <th>域名</th>
                    <th>切换时间</th>
                    <th>处理时间</th>
                    <th>状态</th>
                    <th>切换类型</th>
                    <th>旧服务器组/新服务器组</th>
                </tr>
                </thead>
                <tbody>
                <#if (data.list.length>0)>
                    <#list data.list as list>
                        <tr>
                            <td class="font-blue">${list.domain}</td>
                            <td class="font-gray">${$.time_to_string(list.dateline)}</td>
                            <td>
                                <#if ($.is_empty(list.status_dateline))>
                                    <#if (list.status == 0)>
                                        <span class='font-red'>待处理</span>
                                        <#else> -
                                    </#if>
                                    <#else>
                                        <span class='font-green'>${$.time_to_string(list.status_dateline)}</span>
                                </#if>
                            </td>
                            <td><#if (list.status == 0)><span class="font-gray">未处理</span><#else><span class="font-green">已处理</span></#if></td>
                            <td class="font-gray"><#if (list.type==0)>用户套餐升级切换<#elseif (list.type==1)>管理员套餐切换<#elseif (list.type==2)>管理员直接切换<#else>系统自动切换</#if></td>
                            <td><span class="<#if (list.old_ns_group=='free')><#elseif (list.old_ns_group=='vip11')>font-org<#elseif (list.old_ns_group=='vip21')>font-green<#else></#if>">${ns_groups[list.old_ns_group].name}</span> <site class="glyphicon glyphicon-arrow-right font-gray"></site> <span class="<#if (list.new_ns_group=='free')>font-gray<#elseif (list.new_ns_group=='vip11')>font-org<#elseif (list.new_ns_group=='vip21')>font-green<#else></#if>">${ns_groups[list.new_ns_group].name}</span></td>
                        </tr>
                    </#list>
                    <#else>
                        <tr><td colspan="6"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无切换日志！</span></tr>
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
    var exchenge_log = function(page,domain_id){
        $.ajaxPassport({
            url:"<?php echo U("/domain_manager/domain_log_switch");?>",
            success:function(res){
                if(res.error == 1){
                    $.tips(res.message,"error");
                }else{
                    res.title = "域名切换日志("+res.total+")";
                    var edit_c = "" + easyTemplate($("#tpl_exchange_loglist").html(),res);
                    $("#user_reg_month_"+domain_id).html(edit_c);
                }
            },
            data:{page:page,domain_id:domain_id,do:"get"}
        });
    }
</script>
<!-- 域名切换日志 end-->

<!-- 域名解析记录 start-->
<script type="text/template" id="tpl_domain_records">
    <#macro rowedit data>
        <div class="modal-header" style="text-align: center">
            <h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
            <div class="dis10"></div>
        </div>
        <div class="recordlist">
            <table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
                <col width="200px" />
                <col />
                <col width="80px" />
                <col width="130px" />
                <col width="80px" />
                <col width="75px" />
                <col width="75px" />
                <col width="50px" />
                <thead>
                <tr class="active">
                    <th>域名</th>
                    <th>记录值</th>
                    <th>类型</th>
                    <th>线路</th>
                    <th>TTL</th>
                    <th>解析状态</th>
                    <th>审核状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <#if (data.list.length>0)>
                        <#list data.list as records>
                            <tr>
                                <td class="font-blue">${records.RRname}.${records.domain}</td>
                                <td >${records.RRvalue}</td>
                                <td>${RRtypeArr['RRtype'][records.RRtype]}</td>
                                <td class="font-gray"><#if ($.is_empty(acls[records.acl]))><a href="<?php echo U("domain_manager/domain_diyline?keyword=");?>${cust_line[records.acl.replace('cust','')].name}" class="font-gray">自定义线路:${cust_line[records.acl.replace('cust','')].name}</a><#else>${acls[records.acl].name}</#if> <#if (records['monitor'].acl)><a href="<?php echo U("/domain_monitor/monitor?keyword=");?>${records.domain}"><cite class="glyphicon glyphicon-phone font-green" title="监控中"></cite></a></#if></td>
                                <td>${records.RRttl}</td>
                                <td class="font-gray"><cite class="<#if (records.status==1)>glyphicon glyphicon-ok font-green<#else>glyphicon glyphicon-remove font-red</#if>" title="<#if (records.status==1)>已开启<#else>已暂停</#if>"></cite></td>
                                <td class="font-gray"><cite class="<#if (records.inlock==0)>glyphicon glyphicon-ok font-green<#else>glyphicon glyphicon-lock font-red</#if>" title="<#if (records.status==0)>审核通过<#else>待审核</#if>"></cite></td>
                                <td class="font-gray"><a href="http://<#if (records.RRname == '@')><#elseif (records.RRname == '*')><#else>${records.RRname}.</#if>${records.domain}"  target="_blank" title="域名查看"><span class="glyphicon glyphicon-eye-open font-org"></span></a></td>
                            </tr>
                        </#list>
                    <#else>
                        <tr><td colspan="7"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无域名解析！</span></tr>
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
    var acls = <?php echo JSON::encode(C("category","domain_acl")->json(0,'ident'));?>;
    var cust_line = <?php echo JSON::encode(M("@domain_acl_set")->get_cust_list());?>;
    var RRtypeArr = <?php echo JSON::encode(tCache::read('data_config'));?>;
    var show_domain_records = function(page,domain_id){
        $.ajaxPassport({
            url:"<?php echo U("/domain_manager/records?domain_id=");?>"+domain_id+"&do="+"get&mark="+1,
            success:function(res){
                if(res.error == 1){
                    $.tips(res.message,"error");
                }else{
                    res.title = "域名解析记录("+res.total+")";
                    var edit_c = "" + easyTemplate($("#tpl_domain_records").html(),res);
                    $("#user_reg_month_"+domain_id).html(edit_c);
                }
            },
            data:{page:page}
        });
    }
</script>
<!-- 域名解析记录 end-->
<!-- 域名牵引  start-->
<script type="text/template" id="tpl-setting-qy">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="form-content">
            <form action="<?php echo U("/domain_manager/domain_qy_lock");?>" method="post" class="theform form form-2" role="form">
                <div class="tpl"></div>
            </form>
        </div>
    </#macro>
</script>
<script language="javascript">
    var setting_service_formdata4 = {
        domain:{type:"ivalue",label:"锁定域名",name:"domain",value:"<?php echo isset($domain)?$domain:"";?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
        status: {type: "select", label: "锁定状态", name: "status", value: "1", disabled: 0, data_sr:[{v: "临时锁定", key: "1"}, {v: "永久锁定", key: "2"}], css: "", require: "datatype='*'", desc: "", item_css: ""},
        expiry:{type:"date",label:"预计解封时间",name:"expiry",value:"",data_sr:[],css:"date-ymdhi",require:"",desc:"永久锁定不填",item_css:""},
        bz:{type:"textarea",label:"备注",name:"bz",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
        hash:{type:"hidden",label:"",name:"hash",value:"<?php echo tUtil::hash();?>",data_sr:[],css:"",require:"",desc:"",item_css:""},
        btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
    };
    var edit_setting_qy = function(obj){
        var edit_c = $("#tpl-setting-qy").html();
        var url = $(obj).attr("data-url");
        edit_c = "" + easyTemplate(edit_c,{title:"域名锁定"});
        $("#myModal").find(".modal-dialog").width(560);
        $("#myModal").find(".modal-content").html(edit_c);
        $.loadform(setting_service_formdata4,url,function(res){
            loadlist();
            return true;
        },function(res){
            setTimeout(function(){
                $("#myModal").find(".modal-content").find(".tpl").find("input.date-ymdhi").unbind("click").bind("click",function(){
                    laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})
                });
            },50);
        },".theform");
        $('#myModal').modal();
    }
</script>
<!-- 域名牵引  end-->

<!-- 域名套餐购买续费记录 start-->
<script type="text/template" id="tpl_domain_order">
    <#macro rowedit data>
        <div class="modal-header" style="text-align: center">
            <h4 style="border-left: 0px;font-size: 16px">${data.title}</h4>
            <div class="dis10"></div>
        </div>
        <div class="recordlist">
            <table class="list-table table table-bordered table-condensed table-hover" cellpadding="0" cellspacing="0" style="width: 1080px">
                <col />
                <col width="80px" />
                <col width="80px" />
                <col width="110px" />
                <col width="120px" />
                <col width="120px" />
                <col width="80px" />
                <col width="150px" />
                <thead>
                <tr class="active">
                    <th>购买域名/套餐</th>
                    <th>购买类型</th>
                    <th>购买数量</th>
                    <th>购买价格</th>
                    <th>共优惠</th>
                    <th>购买总金额</th>
                    <th>支付状态</th>
                    <th>支付时间</th>
                </tr>
                </thead>
                <tbody>
                <#if (data.list.length>0)>
                    <#list data.list as order>
                        <tr>
                            <td><span class="font-blue">${order.goods_name}</span><span class="font-gray"> [${service_groups[order.goods_no].name}]</span></td>
                            <td class="font-gray"><#if (order.type == 0)>域名套餐<#else>短信套餐</#if></td>
                            <td class="font-gray2"><#if (order.goods_name)><#if (order.num < 10 )>${order.num}个月<#else>${order.num/10}年</#if><#else>${order.num}</#if></td>
                            <td class="font-gray">${order.price}元</td>
                            <td class="font-gray">${order.youhui}元</td>
                            <td class="font-org">${order.amount}元</td>
                            <td><#if (order.status == 0)><span  class="font-gray">未完成</span><#else><span class="font-green">已完成</span></#if></td>
                            <td class="font-gray">${$.time_to_string(order.status_dateline,"Y-m-d H:i:s")}</td>
                        </tr>
                    </#list>
                    <#else>
                        <tr><td colspan="8"><cite class="glyphicon glyphicon-info-sign font-org"></cite> <span class="font-gray">无套餐购买记录！</span></tr>
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
    var show_domain_order = function(page,domain_id){
        $.ajaxPassport({
            url:"<?php echo U("/domain_manager/domain_order?domain_id=");?>"+domain_id,
            success:function(res){
                if(res.error == 1){
                    $.tips(res.message,"error");
                }else{
                    res.title = "套餐购买记录("+res.total+")";
                    var edit_c = "" + easyTemplate($("#tpl_domain_order").html(),res);
                    $("#user_reg_month_"+domain_id).html(edit_c);
                }
            },
            data:{page:page}
        });
    }
</script>
<!-- 域名套餐购买续费记录 end-->

<!-- 域名解析统计图 start-->
<script language="javascript" src="<?php echo U("static@/javascript/highcharts/highcharts.js");?>"></script>
<script language="JavaScript">
    var domain_parse_count = function(domain_id,domain){
        $.ajaxPassport({
            url:"<?php echo U("/aapi/Domain.Maps");?>",
            success:function(res){
                if(res.status == 0){
                    $("#user_reg_month_"+domain_id).html("<p style='padding:60px 0 0 0;'>当前无可用图像</p>");
                }else{

                    var total1 = 0;
                    var g1ns1 = new Array();
                    var g1ns2 = new Array();
                    var pjz_jt = 0;
                    for(var x in res.data['domain1']){
                        g1ns1.push(x);
                        g1ns2.push(res.data['domain1'][x]);
                        pjz_jt += res.data['domain1'][x];
                        total1 = total1+ parseInt(res.data['domain1'][x]);
                    }

                    var total2 = 0;
                    var g1ns3 = new Array();
                    var g1ns4 = new Array();
                    var pjz_zt = 0;
                    for(var x in res.data['domain2']){
                        g1ns3.push(x);
                        g1ns4.push(res.data['domain2'][x]);
                        pjz_zt += res.data['domain2'][x];
                        total2 = total2+ parseInt(res.data['domain2'][x]);
                    }

                    $("#user_reg_month_"+domain_id).highcharts({
                        chart: {
                            type: "line",
                            marginRight: 130,
                            marginBottom: 25
                        },
                        title: {
                            text: "域名"+domain+"日统计图",
                            x: -20 //center
                        },
                        subtitle: {
                            text: "今日请求数:"+total1+"人次 | 昨日请求数:"+total2+"人次",
                            x: -20
                        },
                        xAxis: {
                            categories: g1ns1,
                            tickInterval:3,
                        },
                        yAxis: {
                            title: {
                                text: "请求数"
                            },
//                            plotLines: [{
//                                value: parseInt(pjz_jt/g1ns1.length),
//                                dashStyle:'longdashdot',//标示线的样式，默认是solid（实线），这里定义为长虚线
//                                width: 2,
//                                color: "#48BEF4"
//                            },{
//                                value: parseInt(pjz_zt/g1ns3.length),
//                                dashStyle:'longdashdot',//标示线的样式，默认是solid（实线），这里定义为长虚线
//                                width: 2,
//                                color: "#90ED7D"
//                            }],
                        },
                        tooltip: {
                            valueSuffix: "次",
                            shadow:false,
                            shared: true,
                            crosshairs: true,
                            borderColor: "#bbbbbb",
                            borderRadius: 0,
                            borderWidth: 1
                        },
                        legend: {
                            layout: "vertical",
                            align: "right",
                            verticalAlign: "top",
                            x: -10,
                            y: 100,
                            borderWidth: 0
                        },
                        series: [{
                            name: "今日请求数",
                            data: g1ns2,
                            color:"#48BEF4",
                            index:1,
                        },{
                            name: "昨日请求数",
                            data: g1ns4,
                            color:"#90ED7D",
                            marker: {
                                symbol: "diamond"
                            }
                        }
                        ]
                    });
                }
            },
            data:{domain:domain,timetype:"day",RRname:"",}
        });
    }
</script>
<!-- 域名解析统计图 end-->