var load_domains_group = function(type){
    var url = U("/api/Domain.GetGroup");
    $.ajaxPassport({
        url:url,
        success:function(res){
            if(res.status == 1){
                var group_leftnav_li = ""+easyTemplate($("#tpl_domain_group").html(),{list:res.data.list,type:"nav"});
                var group_set_li = ""+easyTemplate($("#tpl_domain_group").html(),{list:res.data.list,type:"set"});
                //左侧导航栏事件
                $("#Leftnav .li_group_id").empty().remove();
                $("#Leftnav .aftergroup").after(group_leftnav_li);
                $("#Leftnav").find("ul li a.showtype").unbind("click").bind("click",function(){
                    var type = $(this).data("type");
                    var list_tit_name = $(this).data("tit");
                    $(".list_tit_name").html(list_tit_name);
                    load_domains_list(1,type,0);
                });
                $("#Leftnav").find("button,a").bind("focus",function(){
                    $(this).blur();
                });
                $("#Leftnav").find("ul li a.group_id").unbind("click").bind("click",function(){
                    var group_id = $(this).data("group_id");
                    var list_tit_name = $(this).data("tit");
                    $(".list_tit_name").html(list_tit_name);
                    load_domains_list(1,"all",group_id);
                });
                //上部分组按钮事件
                $("#Usetgroup .li_set_group").empty().remove();
                $("#Usetgroup .aftergroup").after(group_set_li);
                $("#Usetgroup").find("a.set_group").unbind("click").bind("click",function(){
                    set_domain_group(this);
                });
                $("#Usetgroup").find("button,a").bind("focus",function(){
                    $(this).blur();
                });
                //删除域名组
                $("#Usetgroup").find("span#Dgroup").unbind("click").bind("click",function(){
                    var group_id = $(this).data("group_id");
                    var group_name = $(this).data("group_name");
                    if(confirm("确定要删除域名组:"+group_name+"吗？")){
                        $.ajaxPassport({
                            url: U("/api/Domain.DelGroup"),
                            success: function (res) {
                                if (res.status == 1) {
                                    $.ui.success(res.msg);
                                    setTimeout(function () {
                                        load_domains_group();
                                    }, 500);
                                } else {
                                    $.ui.error(res.msg);
                                }
                                $('#my-confirm').modal("close");
                            },
                            data:{group_id:group_id}
                        });
                    }
                    return false;
                });
            }
        },
    });
}
var load_domains_list = function(page,type,group_id,keyword){
    var url = U("/api/Domain.GetListByUid");
    var group_id = typeof group_id == "undefined"?0:group_id;
    var keyword  = $.is_empty(keyword)?'':keyword;
    $.ui.loading($(".listbody"),0);
    $.ajaxPassport({
        url:url,
        success:function(res){
            if(res.status == 1){
                $.ui.close_loading($(".listbody"));
                if(group_id > 0){
                    $("#Leftnav").find("ul li a").removeClass("cur");
                    $("#Leftnav").find("ul li a[data-group_id='"+group_id+"']").addClass("cur");
                }else{
                    $("#Leftnav").find("ul li a").removeClass("cur");
                    $("#Leftnav").find("ul li a[data-type='"+type+"']").addClass("cur");
                }

                var listhtml = ""+ easyTemplate($("#tpl_domain_list").html(),res.data);
                //

                $(".listbody").find("tbody.tpl").html(listhtml);
                if (type == "lastupdate") {
                    $(".pagebar").html("");
                    $(".list_tit_count").html("("+$(".listbody tbody").find("tr").length+")");
                }else{
                    $(".pagebar").html(res.data.pagebar);
                    $(".list_tit_count").html("("+res.data.total+")");
                }
                $(".listbody").find("input.checkall").unbind("click").bind("click",function(){
                    $.check_all(this);
                });
                $("a").bind("focus",function(){
                    $(this).blur();
                });
                /////----------check ns in
                var domains = [];
                for(var i in res.data['list']){
                    domains.push(res.data['list'][i]['domain']);
                }
                //升级套餐
                $(".listbody").find(".domian-upgrade").unbind("click").bind("click",function(){
                    var goods_name = $(this).attr("data-domain");
                    add_cart_step1(1,0,"",goods_name);
                });
                //check alias
                var domains_id = [];
                for(var i in res.data['list']){
                    domains_id.push(res.data['list'][i]['domain_id']);
                }
                check_alias_domain(domains_id.join(","));
                $.exeJS(check_nsdomain_url+"&domains="+domains.join(","));
                $.exeJS(check_expirydomain_url+"&domains="+domains.join(","));
            }else{
                $.ui.error(res.msg);
            }
        },
        data:{type:type,group_id:group_id,page:page,keyword:keyword},
    });
}
//get bind domain group
var check_alias_domain = function(domains){
    $.ajaxPassport({
        url:U("/api/Domain.GetDomainBind"),
        success:function (res) {
            if (res.status == 1) {
                for(var i in res.data){
                    var html_bind_str = "<div class='xpopover' data-msg='绑定别名："+res.data[i].join(";")+"' data-theme='' style='color: white;background-color: orange;cursor: text;font-size: 10px;border-radius: 5px;display: inline-block;padding: 0 2px;'>别</div>"
                    $(".domain_"+i).find("td .fb").append(html_bind_str);
                }
            }
            $.ui.popover(".listbody .xpopover");
        },
        data:{domains:domains}
    });
}
var batch_op = {
    url: U("/api/Domain."),
    exe_url:"",
    data:{},
};
var ids= new Array();
var batch_domain_op = function(obj) {
    var domain_do = $(obj).data("do");
    var ids_tmp  = $.fetch_ids("domainId[]");
    ids = ids_tmp.split(",");
    if (ids == "") {
        $.ui.error('请选择要操作的域名！')
        return;
    }
    var domain_confirm = "";
    if (domain_do == "stop") {
        domain_confirm = "你确定要暂停域名吗？";
        batch_op.exe_url =batch_op.url + "Status";
        batch_op.data.status = 0;
    } else if (domain_do == "start") {
        domain_confirm = "你确定要开启域名吗？";
        batch_op.exe_url =batch_op.url + "Status";
        batch_op.data.status = 1;
    } else if (domain_do == "lock") {
        domain_confirm = "你确定要锁定域名吗？";
        batch_op.exe_url =batch_op.url + "Locked";
        batch_op.data.inlock = 1;
    } else if (domain_do == "unlock") {
        var domain_confirm = "你确定要解锁域名吗？";
        batch_op.exe_url =batch_op.url + "Locked";
        batch_op.data.inlock = 0;
    } else if (domain_do == "del") {
        domain_confirm = "你确定要删除域名吗？";
        batch_op.exe_url =batch_op.url + "Del";
    }
    $.ui.confirm(function(){
        batch_domain_detial_op(0);
    },domain_confirm) ;
}
var num = 0;
var batch_domain_detial_op = function(i){
    batch_op.data.domain_id=ids[i];
    if( i >= ids.length || typeof ids[i] == "undefined"){
        num = 0;
        return false;
    }
    $.ui.loading($(".listbody"));
    $.ajaxPassport({
        url: batch_op.exe_url,
        success: function (res) {
            if (res.status == 1) {
                num++;
                var domainId = ".domain_"+ids[i];
                if (batch_op.exe_url.split(".")[1] == 'Del') {

                    //自定义线路
                    $.exeJS(U("/domains/refresh_line?do=all&domain="+res.data.domain+"&ns_group="+res.data.ns_group));
                    setTimeout(function () {
                        $(domainId).remove();
                    }, 50);
                }else if(batch_op.exe_url.split(".")[1] == 'Status'){
                    if(batch_op.data.status==1){
                        $(domainId).find(".am-icon-pause").removeClass("am-icon-pause").addClass("am-icon-play");
                        $(domainId).find("a.status").attr("title","域名已开启");
                    }else{
                        $(domainId).find(".am-icon-play").removeClass("am-icon-play").addClass("am-icon-pause");
                        $(domainId).find("a.status").attr("title","域名已暂停");
                    }
                }else{
                    if(batch_op.data.inlock==1){
                        $(domainId).find(".am-icon-unlock").removeClass("am-icon-unlock").addClass("am-icon-unlock-alt");
                        $(domainId).find("a.inlock").attr("title","域名已解锁");
                    }else{
                        $(domainId).find(".am-icon-unlock-alt").removeClass("am-icon-unlock-alt").addClass("am-icon-unlock");
                        $(domainId).find("a.inlock").attr("title","域名已锁定");
                    }
                }
            }
            //执行最后一次时提示操作结果
            if (i == (ids.length - 1)) {
                if (num >0) {
                    $.ui.close_loading($(".listbody"));
                    $.ui.success("成功操作"+num+"个域名");
                    //如果是删除，组中分配有域名，重新加载组
                    if (batch_op.exe_url.split(".")[1] == 'Del') {
                        load_domains_group();                        
                    }
                    load_domains_list(1,"all");
                }else{
                    $.ui.close_loading($(".listbody"));
                    $.ui.error(res.msg);
                }
            }
            batch_domain_detial_op(i+1);
        },
        data: batch_op.data,
    });
}
var add_domains_group = function(obj){
    var val  = $(obj).parent().find("#add-group").val();
    var url  =  U("/api/Domain.AddGroup");
    $.ajaxPassport({
        url:url,
        success:function(res){
            if(res.status==1){
                $.ui.success(res.msg);
                setTimeout(function () {
                    load_domains_group();
                }, 500);
            }else{
                $.ui.error(res.msg);
            }
        },
        data:{group:val},
    });
}
var set_domain_group = function(obj){
    var ids = $.fetch_ids("domainId[]");
    var group_id = $(obj).data("group_id");
    if (ids == "") {
        $.ui.error('请选择要操作的域名！')
        return;
    }
    var url  =  U("/api/Domain.SetGroup");
    $.ajaxPassport({
        url:url,
        success:function(res){
            if(res.status==1){
                $.ui.success(res.msg);
                setTimeout(function () {
                    load_domains_group();
                }, 500);
            }else{
                $.ui.error(res.msg);
            }
        },
        data:{group_id:group_id,domain_id:ids},
    });
}
