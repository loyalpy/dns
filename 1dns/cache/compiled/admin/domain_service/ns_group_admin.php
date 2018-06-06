<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">
  <title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
  
  <meta name="keywords" content="<?php echo isset($site['seo_keyword'])?$site['seo_keyword']:"";?>" />
  <meta name="description" content="<?php echo isset($site['seo_description'])?$site['seo_description']:"";?>" />
      <link href="<?php echo U("/static/javascript/bootstrap/css/bootstrap.min.css");?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_admin.css";?>"  rel="stylesheet" type="text/css" />
    <?php if(isset($style) && $style != "default"){?>
    <link href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_admin_$style.css";?>"  rel="stylesheet" type="text/css" />
    <?php }?>
    
<link href="<?php echo U("static@/javascript/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css");?>" type="text/css" rel="stylesheet">
    <!--[if lt IE 9]><script src="<?php echo U("static@/javascript/html5/ie8-responsive-file-warning.js");?>"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo U("static@/javascript/html5/html5shiv.js");?>"></script>
      <script src="<?php echo U("static@/javascript/html5/respond.min.js");?>"></script>
    <![endif]-->
    <script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
    <script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};
    <?php if($uid){?>
    tUser['uid'] = <?php echo isset($uid)?$uid:"";?>;
  <?php }?>
    </script>
</head>
<body class="ucenter">
<div class="topbar">
<div class="aps">
  <div class="in-left">
    <ul class="">
    <li><a href="<?php echo U("home@/");?>" target="_blank" class=" font-gray"><cite class="glyphicon glyphicon-home"></cite> 网站首页</a></li>
    <li class="dropdown">
     
    </li>
    </ul>
  </div>
  <div class="in-center">
      
  </div>
  <div class="in-right">
  <ul>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?> <b class="caret"></b></a>
    <ul class="dropdown-menu">
       <li><a href="<?php echo U("account@/ucenter/profile_basic");?>">个人资料修改</a></li>
       <!--<li><a href="<?php echo U("account@/ucenter/profile_avatar");?>" target="_blank">个人头像修改</a></li>-->
       <li><a href="<?php echo U("account@/ucenter/profile_passwd");?>">修改密码</a></li>
       <li class="divider"></li>
       <li><a href="<?php echo U("account@/login/logout");?>">退出</a></li>
     </ul>
     </li>
     
   </ul>
  </div>
  <div class="cl"></div>
</div>
</div>
<div class="header">
  <div class="aps">
      <div class="logo"><a href="/sys_manager" title=""><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>" alt="八戒DNS" /></a></div>
      
<div class="menu">
    <ul>
        <?php if($this->check_purview("/domain_service/ns_group")){?>
        <li><a href="<?php echo U("/domain_service/ns_group?type=ns");?>" class="<?php if($type=='ns'){?>cur<?php }?>">NS服务器</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_service/ns_group_monitor")){?>
        <li><a href="<?php echo U("/domain_service/ns_group?type=monitor");?>" class="<?php if($type=='monitor'){?>cur<?php }?>">监控服务器</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_service/ns_group_web")){?>
        <li><a href="<?php echo U("/domain_service/ns_group?type=web");?>" class="<?php if($type=='web'){?>cur<?php }?>">WEB服务器</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_service/ns_group_database")){?>
        <li><a href="<?php echo U("/domain_service/ns_group?type=database");?>" class="<?php if($type=='database'){?>cur<?php }?>">DB服务器</a></li>
        <?php }?>
    </ul>
</div>
      <div class="cl"></div>
    </div>
</div>
<div class="main">
<div class="leftnav" id="Lnav">
        <div class="quick_btn" style="display:none;"></div>
        <div class="dis10"></div>
        <?php foreach($this->nav['nav'] as $key => $item){?>
      <dl>
      <?php if($item['status'] == 1 && $this->check_purview($item['url'])){?>
        <dt><a href="javascript:void(0)" _key="<?php echo isset($key)?$key:"";?>" _href="<?php echo isset($item['url'])?$item['url']:"";?>"><cite class="glyphicon glyphicon-<?php echo isset($item['enname'])?$item['enname']:"";?>"></cite> &nbsp;<?php echo isset($item['name'])?$item['name']:"";?> <cite class="updown glyphicon glyphicon-chevron-<?php if($item['isopen'] == 1 || $this->nav['top'] == $key){?>down<?php }else{?>up<?php }?>"></cite></a></dt>
        <?php foreach($item['childrens'] as $key2 => $sub){?>
        <?php if($sub['status'] == 1 && $this->check_purview($sub['url'])){?>
        <dd class="<?php if($this->nav['sub'] == $key2){?>cur<?php }?>" <?php if($item['isopen'] == 1 || $this->nav['top'] == $key){?><?php }else{?>style="display:none;"<?php }?>><a href="<?php echo isset($sub['url'])?$sub['url']:"";?>"><cite class="glyphicon glyphicon-<?php echo isset($sub['enname'])?$sub['enname']:"";?>"></cite> &nbsp;<?php echo isset($sub['name'])?$sub['name']:"";?> </a></dd>
        <?php }?>
        <?php }?>
      <?php }?>
      </dl>
      <?php }?>
    <script language="javascript">
       $(function(){
           $("#Lnav dl dt").click(function(){
              var obj = this;
              if($(obj).find("cite.updown").hasClass("glyphicon-chevron-down")){
                 $(obj).find("cite.updown").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
                 $(obj).parent().find("dd").hide();
              }else{
                 $(obj).find("cite.updown").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
                 $(obj).parent().find("dd").show();
              }         
           });
       })
     </script>
     <div class="extnav">
     <dl>
     <?php if($this->check_purview('/sys_manager/module_config')){?>
     <dd class="<?php if(in_array($this->nav['uri'],array('/sys_manager/module_config'))){?>cur<?php }?>"><a href="/sys_manager/module_config">模块配置</a></dd>
     <?php }?>
     <dd class="<?php if(in_array($this->nav['uri'],array('/ucenter/profile_passwd'))){?>cur<?php }?>"><a href="<?php echo U("account@/ucenter/profile_passwd");?>">修改密码</a></dd>
         <dd><a href="<?php echo U("account@/login/logout");?>">退出 </a></dd>
     </dl>
     </div>
</div>
<div class="main-nav">
    <div class="name">域名服务器组</div>
    <div class="navbtn">
        <!--button-->
        <?php if($this->check_purview("/domain_service/ns_group_edit")){?>
        <a href="javascript:void(0)" class="btn btn-primary btn-sm addbtn">
            <cite class="glyphicon glyphicon-plus"></cite>
            新增服务器组</a>
        <?php }?>
        <!--end button-->
    </div>
    <div class="cl"></div>
</div>
<!--search box-->
<form action=""></form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
    <div class="list-box">
        <table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
            <col width="150px"/>
            <col/>
            <col width="100px"/>
            <thead>
            <tr>
                <th>服务器标识</th>
                <th>服务器组</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody class="tpl"></tbody>
        </table>
    </div>
</form>
<!-- end list box -->
<div class="cl"></div></div>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/layer/layer.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/easyTemplate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.init.admin.js");?>"></script>
<script language="javascript" src="<?php echo U("static@javascript/jquery.zclip/jquery.zclip.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static/cache/dataconfig.js");?>"></script>
<?php echo $this->fetch('tpl/form')?>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<script type="text/template" id="tpl_list_row">
    <#macro row data>
        <tr>
            <td class="font-green">
            <font class="font-blue">${data.name}</font><font class="font-gray">(${data.type})</font><br/>
            <#if (!$.is_empty(data.ns))>
                <font class="font-gray">
                 ${data.ns.replace(/;/g,"<br/>")}
                </font>
            </#if>
            <br/>
            <font class="font-green f16">[${data.ns_group}]</font> 
            </td>
            <td>
                <table class='table table-bordered' cellpadding='0' cellspacing='0'>
                    <col width="150px"/>
                    <col width="70px"/>
                    
                    <?php if($type == "ns"){?>
                    <col width="70px"/>
                    <col width="180px"/>
                    <col width="70px"/>
                    <col width="70px"/>
                    <col width="90px"/>
                    <?php }?>
                    <col/>
                    <thead>
                    <tr class="success">
                        <th>接口</th>
                        <th>接口状态</th>
                        <?php if($type == "ns"){?>
                        <th>DNS状态</th>
                        <th>DNS版本</th>
                        <th>ZONES</th>
                        <th>ISPS</th>
                        <th>CUSTOMS</th>
                        <?php }?>
                        <th>
                            操作&nbsp;
                            <?php if($this->check_purview("/domain_service/ns_group_restartallline")){?>
                                <?php if($type == "ns"){?>
                                <button type="button" class="btn btn-default btn-xs restartallline" data-ns="${data.ns_group}" data-loading-text="处理中……">重载所有线路</button>
                                <?php }?>
                            <?php }?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <#list data.data as list>
                        <tr>
                            <td class="font-org">${list.ip}<font class="font-gray">:${list.port}</font></td>
                            <td><cite class="glyphicon status_img glyphicon-remove font-red"></cite>
                                <?php if($this->check_purview("/domain_service/ns_group_btnopra")){?>
                            <button type="button" class="btn btn-xs btn-default glyphicon glyphicon-refresh refresh-stat" data-mark="reload" data-port="${list.port}" data-host="${list.ip}">
                            </button>
                                <?php }?>
                            </td>
                            <?php if($type == "ns"){?>
                            <td>
                            <cite data-content="" class="tiptitle glyphicon status_img glyphicon-remove font-red"></cite>
                                <?php if($this->check_purview("/domain_service/ns_group_btnopra")){?>
                            <button type="button" class="btn btn-xs btn-default glyphicon glyphicon-refresh refresh-dns-stat" data-mark="reload" data-port="${list.port}" data-host="${list.ip}">
                            </button>
                                <?php }?>
                            </td>
                            <td><span class="font-gray version">${list.version}</span></td>
                            <td><span class="font-gray zones">${list.zones}</span></td>
                            <td><span class="font-gray isps">${list.isps}</span></td>
                            <td><span class="font-gray customs">${list.customs}</span></td>
                            <?php }?>
                            <td>
                            <?php if($type == "ns"){?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       日志 <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if($this->check_purview("/domain_service/ns_group_query_log")){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-parm="" data-mark="query_log" data-port="${list.port}" data-host="${list.ip}">查询日志</a></li>
                                        <?php }?>
                                        <?php if($this->check_purview("/domain_service/ns_group_start_log")){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-parm="" data-mark="start_log" data-port="${list.port}" data-host="${list.ip}">启动日志</a></li>
                                        <?php }?>

                                        <?php if($this->check_purview("/domain_service/ns_group_black_log")){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-parm="" data-mark="black_log" data-port="${list.port}" data-host="${list.ip}">牵引日志</a></li>
                                        <?php }?>
                                    </ul>
                                </div>
                                
                                <?php if($this->check_purview("/domain_service/ns_group_update_dns")){?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       版本 <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                    <?php foreach($data_config['dns_version'] as $key => $item){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-mark="update_dns" data-parm="<?php echo isset($item)?$item:"";?>" data-port="${list.port}" data-host="${list.ip}"><?php echo isset($item)?$item:"";?></a></li>
                                    <?php }?>                          
                                    </ul>
                                </div>
                                <?php }?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       更多 <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if($this->check_purview("/domain_service/ns_group_reloadzone")){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-mark="reloadzone~all" data-parm="" data-port="${list.port}" data-host="${list.ip}">重载 ZONE</a></li>
                                        <?php }?>
                                        <?php if($this->check_purview("/domain_service/ns_group_reloadisp")){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-mark="reloadisp~all" data-parm="" data-port="${list.port}" data-host="${list.ip}">重载 ISP</a></li>
                                        <?php }?>
                                        <?php if($this->check_purview("/domain_service/ns_group_reloadcustom")){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-mark="reloadcustom~all" data-parm="" data-port="${list.port}" data-host="${list.ip}">重载 CUST</a></li>
                                        <?php }?>
                                        <li class="divider"></li>
                                        <?php if($this->check_purview("/domain_service/ns_group_restartline")){?>
                                        <li><a href="javascript:void(0);" class="restartline" data-port="${list.port}" data-host="${list.ip}">重载线路</a></li>
                                        <?php }?>
                                        <li class="divider"></li>
                                        <?php if($this->check_purview("/domain_service/ns_group_status")){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-parm="" data-mark="status" data-port="${list.port}" data-host="${list.ip}">启动状态</a></li>
                                        <?php }?>
                                        <li class="divider"></li>
                                        <?php if($this->check_purview("/domain_service/ns_group_start")){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-parm="" data-mark="start" data-port="${list.port}" data-host="${list.ip}">启动</a></li>
                                        <?php }?>
                                        <?php if($this->check_purview("/domain_service/ns_group_stop")){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-parm="" data-mark="stop" data-port="${list.port}" data-host="${list.ip}"><span class="font-red">停止</span></a></li>
                                        <?php }?>
                                        <?php if($this->check_purview("/domain_service/ns_group_restart")){?>
                                        <li><a href="javascript:void(0);" class="cmd-8jdnsm" data-parm="" data-mark="restart" data-port="${list.port}" data-host="${list.ip}">重启</a></li>
                                        <?php }?>
                                    </ul>
                                </div>
                            <?php }else{?>
                            -
                            <?php }?>
                            </td>
                        </tr>
                    </#list>
                    </tbody>
                </table>
            </td>
            <td>
                <p class="table-item-op">
                    <?php if($this->check_purview("/domain_service/ns_group_edit")){?>
                    <a href="javascript:void(0);" class="editbtn" data-url="<?php echo U("/domain_service/ns_group_edit?mark=edit&id=");?>${data.id}"><span
                            class="glyphicon glyphicon-edit"></span></a>&nbsp;
                    <?php }?>
                    <?php if($this->check_purview("/domain_service/ns_group_refresh")){?>
                    <a href="javascript:void(0);" class="refreshbtn" data-url="<?php echo U("/domain_service/ns_group_refresh?");?>ns_group=${data.ns_group}"><span
                            class="glyphicon glyphicon-refresh"></span></a>&nbsp;
                    <?php }?>
                    <?php if($this->check_purview("/domain_service/ns_group_del")){?>
                    <a href="javascript:void(0);" class="delbtn" data-url="<?php echo U("/domain_service/ns_group_del?id=");?>${data.id}&ns_group=${data.ns_group}"><span
                            class="glyphicon glyphicon-remove"></span></a>&nbsp;
                    <?php }?>
                </p>
            </td>
        </tr>
    </#macro>
</script>
<script type="text/template" id="tpl_list_edit">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="form-content">
            <form action="<?php echo U("/domain_service/ns_group_edit");?>" method="post" class="theform form form-1" role="form">
                <div class="tpl"></div>
            </form>
        </div>
    </#macro>
</script>
<script language="javascript">
    var formdata = {
        name: {type: "text", label: "服务器组名称", name: "name", value: "", data_sr: [], css: "", require: "datatype='*'", desc: "名称不能为空", item_css: ""},
        ns_group: {type: "text", label: "服务器组标识", name: "ns_group", value: "", data_sr: [], css: "", require: "datatype='*'", desc: "标识唯一", item_css: "font-red"},
        ns: {type: "textarea", label: "NS", name: "ns", value: "", data_sr: [], css: "", desc: " 多个用;分割", item_css: ""},
        type: {
            type: "select",
            label: "服务器类型",
            name: "type",
            value: "",
            disabled: 0,
            data_sr: [{v: "NS", key: "NS"}, {v: "WEB", key: "WEB"}, {v: "DATABASE", key: "DATABASE"}, {v: "MONITOR", key: "MONITOR"}],
            css: "",
            require: "",
            desc: "",
            item_css: ""
        },
        iname: {type: "ivalue", label: "服务器组", name: "", value: "", data_sr: [], css: "f14", require: "", desc: "", item_css: ""},
        html_space6: {
            type: "html",
            value: "<div style='padding-left:160px;'><p style='padding-bottom:8px;margin-top: -40px;'><button type='button' class='btnadd_item btn btn-xs btn-primary'>增加新值</button></p><div class='attrspec_valbox'><table class='table table-bordered' cellpadding='0' cellspacing='0' style='width:680px;'><thead><tr class='active'><th style='width:300px;'>IP</th><th style='width:300px;'>域名</th><th style='width:180px;'>端口</th><th style='width:300px;'>说明</th><th style='width:150px;'>是否开启</th><th style='width:160px;'>操作</th></tr></thead><tbody></tbody></table></div></div>"
        },
        id: {type: "hidden", label: "", name: "id", value: "", data_sr: [], css: "", require: "", desc: "", item_css: ""},
        mark: {type: "hidden", label: "", name: "mark", value: "<?php echo isset($mark)?$mark:"";?>", data_sr: [], css: "", require: "", desc: "", item_css: ""},
        btn: {type: "button", label: "", value: "保存修改", desc: '<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
    };
    var load_list = function () {
        $.loadlist([], function (res) {
            $(".editbtn").click(function () {
                edit_func(this);
            });
            $(".delbtn").click(function () {
                del_func(this);
            });
             $(".refreshbtn").click(function () {
                    var obj = this;
                    var url = $(obj).attr("data-url");
                    $.ajaxPassport({
                        url: url,
                        success: function (res) {
                            if (res.error == 1) {
                                $.tips(res.message, "error");
                            } else {
                                $.tips(res.message, "success");
                            }
                        }
                    })
            });
            $(".refresh-stat").click(function () {
                check_status(this);
            });
            $(".refresh-dns-stat").click(function () {
                check_dns_status(this);
            });
            //重载配置，重启，刷新
            $(".cmd-8jdnsm").click(function(){
                cmd_8jdnsm(this);
            })
            // 重载线路
            $(".restartline").click(function(){
                var host = $(this).attr("data-host");
                var port = $(this).attr("data-port");
                restartline(host,port);
            });
            //重载所有线路
            $(".restartallline").click(function(){
                var ns_group = $(this).attr("data-ns");
                restartallline(ns_group);
            });
            //DNS服务器状态
           

            //初始话操作            
            $(".refresh-dns-stat").click();
            $(".refresh-stat").click();
        }, "<?php echo isset($pageurl)?$pageurl:"";?>");
    }
    var check_status = function (obj) {
        var host = $(obj).data("host");
        var port = $(obj).data("port");
        $.ui.loading();
        $.ajaxPassport({
            url: "<?php echo U("/domain_service/ns_group_btnopra?do=chkstat");?>",
            success: function (res) {
                if (res.error == 0 && $.in_array(res.status_code,[200,404])) {
                    $(obj).parent().find("cite.status_img").removeClass("glyphicon-remove font-red").addClass("glyphicon-ok font-green");
                } else {
                    $(obj).parent().find("cite.status_img").removeClass("glyphicon-ok font-green").addClass("glyphicon-remove font-red");
                }
                $.ui.close_loading();
            },
            data: {port: port, host: host}
        })
    }

    var check_dns_status = function (obj) {
        var host = $(obj).data("host");
        var port = $(obj).data("port");
        $.ui.loading();
        $.ajaxPassport({
            url: "<?php echo U("/domain_service/ns_group_btnopra?do=status");?>",
            success: function (res) {
                if (res.error == 0 && !$.is_empty(res.message) && res.message.indexOf("8JDNS") > 0) {
                    $(obj).parent().find("cite.status_img").removeClass("glyphicon-remove font-red").addClass("glyphicon-ok font-green");

                    if(!$.is_empty(res.data)){
                        for(var i in res.data){
                            $(obj).parent().parent().find("td span."+i).html(res.data[i]);
                        }
                    }
                } else {
                    $(obj).parent().find("cite.status_img").removeClass("glyphicon-ok font-green").addClass("glyphicon-remove font-red");
                }
                $(obj).parent().find("cite.status_img").data("content",res.message);
                $(obj).parent().find("cite.status_img").popover({html:true,trigger:"hover",});

                $.ui.close_loading();
            },
            data: {port: port, host: host}
        })
    }


    var cmd_8jdnsm = function (obj) {
        var host = $(obj).data("host");
        var port = $(obj).data("port");
        var mark = $(obj).data("mark");
        var parm = $(obj).data("parm");
        if($.in_array(mark,['stop','start','restart','update_dns']) && !confirm("您确定要执行命令 "+mark+" 吗?")){
            return false;
        }    
        $.ui.loading();
        $.ajaxPassport({
            url: "<?php echo U("/domain_service/ns_group_btnopra?do=");?>"+mark,
            success: function (res) {
                if (res.error == 0) {
                    var html = '<div class="bs-callout bs-callout-success">\
                                <h4 class="font-green"><span style="position:relative;top:5px;" class="font-green glyphicon glyphicon glyphicon-ok-circle f24" aria-hidden="true"></span>&nbsp;&nbsp;命令执行成功</h4><div class="dis15"></div>\
                                <p class="font-gray" style="font-weight:400;">'+res.message+'</p>\
                                </div>\
                                ';
                } else {
                    var html = '<div class="bs-callout bs-callout-danger">\
                                <h4 class="font-red"><span style="position:relative;top:5px;" class="font-red glyphicon glyphicon glyphicon-remove-circle f24" aria-hidden="true"></span>&nbsp;&nbsp;命令执行失败</h4><div class="dis15"></div>\
                                <p class="font-gray " style="font-weight:400;">'+res.message+'</p>\
                                </div>\
                                ';
                }
                $.ui.open(html,false,["800px","580px"]);
                $.ui.close_loading();
            },
            data: {port: port, host: host,parm:parm}
        })
        
    }
    var edit_func = function (obj) {
        var edit_c = $("#tpl_list_edit").html();
        var url = $(obj).attr("data-url");
        edit_c = "" + easyTemplate(edit_c, {title: "添加/修改"});
        $("#myModal").find(".modal-dialog").width(860);
        $("#myModal").find(".modal-content").html(edit_c);
        $.loadform(formdata, url, function (res) {
            load_list();
            return true;
        }, function (res) {
            setTimeout(function () {
                $("#myModal").find(".modal-content").find(".tpl").find("input.date-ymd").datetimepicker({
                    language: 'zh-CN',
                    autoclose: 1,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    format: "yyyy-mm-dd",
                    pickerPosition: "bottom-right"
                });
            }, 50);
            for (var i in res.data_arr) {
                add_trrow(res.data_arr[i]['ip'], res.data_arr[i]['domain'], res.data_arr[i]['port'], res.data_arr[i]['status'], res.data_arr[i]['bz']);
            }
            //添加属性按钮(点击绑定)
            $(".theform").find('.btnadd_item').unbind().bind('click',
                    function () {
                        add_trrow('', '', '', '', '');
                    }
            );
        });
        $('#myModal').modal();
    }
    var del_func = function (obj) {
        if (confirm("你确定要删除该数据吗?删除后数据不可恢复！")) {
            var url = $(obj).attr("data-url");
            $.ajaxPassport({
                url: url,
                success: function (res) {
                    if (res.error == 1) {
                        $.tips(res.message, "error");
                    } else {
                        $.tips(res.message, "success");
                        load_list();
                    }
                }
            })
        }
    }
    var add_trrow = function (ip, domain, port, status, bz) {
        var size = $('.attrspec_valbox tbody tr').size();
        var status = parseInt(status);
        var row = '<tr class="td_c">'
                + '<td><input name="ip[]" class="form-control" type="text" value="' + ip + '" /></td>'
                + '<td><input name="domain[]" class="form-control" type="text" value="' + domain + '" /></td>'
                + '<td><input name="port[]" class="form-control" type="text" value="' + port + '" /></td>'
//			+ '<td><input name="status[]" class="form-control" type="text" value="'+status+'" /></td>'
                + '<td><textarea name="bz[]" class="form-control" style="height: 30px;" >' + bz + '</textarea></td>'
                + '<td><select name="status[]" class="form-control"><option value="0" ' + (status == 0 ? "selected='selected'" : "") + '>暂停</option><option value="1"' + (status == 1 ? "selected='selected'" : "") + '>启用</option></select></td>'
                + '<td><p class="table-item-op"><span class="glyphicon glyphicon-arrow-up"></span><span class="glyphicon glyphicon-arrow-down"></span><span class="glyphicon glyphicon-remove"></span></p>'
                + '</td></tr>';
        $('.attrspec_valbox tbody').append(row);
        init_button(size, '.attrspec_valbox');
    }
    var init_button = function (indexValue, idstr) {
        //功能操作按钮
        $(idstr).find('tbody tr:eq(' + indexValue + ') .table-item-op .glyphicon').each(
                function (i) {
                    var tr_obj = $(this).parent().parent().parent();
                    switch (i) {
                        //向上排序
                        case 0:
                            $(this).click(
                                    function () {
                                        var insertIndex = tr_obj.prev().index();
                                        if (insertIndex >= 0) {
                                            $(idstr).find('tbody tr:eq(' + insertIndex + ')').before(tr_obj);
                                        }
                                    }
                            )
                            break;
                        //向下排序
                        case 1:
                            $(this).click(
                                    function () {
                                        var insertIndex = tr_obj.next().index();
                                        $(idstr).find('tbody tr:eq(' + insertIndex + ')').after(tr_obj);
                                    }
                            )
                            break;
                        //删除排序
                        case 2:
                            $(this).click(
                                    function () {
                                        tr_obj.remove();
                                    }
                            )
                            break;
                    }
                }
        )
    }
    $(function () {
        load_list();
        $(".addbtn").click(function () {
            edit_func(this);
        });
    })
</script>
<!-- 重载线路  start-->
<script type="text/template" id="tpl_server_reload">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="recordlist ip-admin">
            <table class="list-table table table-bordered table-condensed" cellpadding="0" cellspacing="0">
                <col width="100px"/>
                <col width="100px" />
                <thead>
                <tr class="active">
                    <th>线路标识</th>
                    <th>状态</th>
                </tr>
                </thead>
                <tbody>
                <#list data.list as list>
                    <tr>
                        <td class="font-gray">${list.acl}</td>
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
    var restartline = function(host,port){
        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/domain_service/ns_group_btnopra?host=");?>"+ host + "&do=restartline" + "&port=" +port,
            success:function(res){
                if(res.error == 1){
                    $.tips(res.message,"error");
                }else{
                    res.title = "命令执行成功";
                    var edit_c = "" + easyTemplate($("#tpl_server_reload").html(),res);
                    $("#myModal").find(".modal-dialog").width("400");
                    $("#myModal").find(".modal-content").html(edit_c);
                    $('#myModal').modal();
                    $.ui.close_loading();
                }
            },
        });
    }
</script>
<!-- 重载线路 end-->
<!-- 重载所有线路  start-->
<script type="text/template" id="tpl_server_reload_all">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="recordlist ip-admin">
            <table class="list-table table table-bordered table-condensed" cellpadding="0" cellspacing="0">
                <col width="150px"/>
                <col  />
                <thead>
                <tr class="active">
                    <th>IP地址</th>
                    <th>线路</th>
                </tr>
                </thead>
                <tbody>
                <#list data.list as list1>
                    <tr>
                        <td class="font-gray">${list1.host}</td>
                        <td>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>线路标识</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <#list list1.rets as list2>
                                <tr>
                                    <td>${list2.acl}<td>
                                    <td><#if (list2.ret.status==0)><span class="font-red">失败</span><#else><span class="font-green">成功</span></#if><td>
                                </tr>
                                </#list>
                                </tbody>
                            </table>
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
    var restartallline = function(ns_group){
        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/domain_service/ns_group_btnopra?ns_group=");?>"+ ns_group +  "&do=restartallline",
            success:function(res){
                if(res.error == 1){
                    $.tips(res.message,"error");
                }else{
                    res.title = "命令执行成功";
                    var edit_c = "" + easyTemplate($("#tpl_server_reload_all").html(),res);
                    $("#myModal").find(".modal-dialog").width("600");
                    $("#myModal").find(".modal-content").html(edit_c);
                    $('#myModal').modal();

                    $.ui.close_loading();
                }
            },
        });
    }
</script>
<!-- 重载所有线路  end-->
<script language="javascript">
 $(function(){
     laydate.skin('molv');
 })
</script>
<!-- Modal -->
<div style="z-index:1033;" class="modal fade" id="myModal" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>