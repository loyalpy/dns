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
        <?php if($this->check_purview("/domain_register/price")){?>
        <?php foreach($r_type as $key => $item){?>
        <li><a href="<?php echo U("/domain_register/price?t=$key");?>" class="<?php if($t == $key){?>cur<?php }?>"><?php echo isset($item)?$item:"";?></a></li>
        <?php }?>
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
    <div class="name">域名代理价格</div>
    <div class="navbtn">
        <?php if($this->check_purview("/domain_register/price_cache_set")){?>
        <button type="button" data-url="/domain_register/price_cache_set?agent=<?php echo isset($t)?$t:"";?>" class="btn btn-default btn-sm t-ajax-button" data-loading-text="处理中……">&nbsp;<cite class="glyphicon glyphicon-refresh"></cite>&nbsp;</button>
        <?php }?>
        <!--button-->
        <?php if($this->check_purview("/domain_register/price_edit")){?>
        <a href="javascript:void(0)" class="btn btn-primary btn-sm addbtn">
            <cite class="glyphicon glyphicon-plus"></cite>
            新增<?php echo ($t == 1?"新网":"万网");?>域名</a>
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
            <col width="50px">
            <col width="150px">
            <col >
            <col width="140px">
            <thead>
            <tr>
                <th>序号</th>
                <th>域名类型(个)</th>
                <th>域名价格(元/年)</th>
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
<script type="text/template" id="tpl_list_row">
    <#macro row data>
        <tr>
            <td class="font-gray">${data.type}</td>
            <td><font class="f14 font-black">${data.type_cn}<span class="font-org">(${data.data.length})</span></font><br/><font class="font-gray">${data.agent}</font> </td>
            <td>
                <?php if($this->check_purview("/domain_register/price_show")){?>
                <button type="button" class="btn btn-primary btn-xs" onclick="citeShow(this)">产品域名价格 <cite class="glyphicon glyphicon-chevron-down"></cite></button> <span class="font-org">&nbsp;&nbsp;&nbsp;<#if (data.type != 1)><i class="glyphicon glyphicon-flag" data-toggle="tooltip" data-placement="right" title="前台使用"></i></#if></span>
                <?php }else{?>
                -
                <?php }?>
                <div class="detail" style="display:none;">
                    <table class='table table-bordered' cellpadding='0' cellspacing='0'>
                        <col />
                        <col width="120px" />
                        <col width="100px" />
                        <col width="100px" />
                        <col width="100px" />
                        <col width="100px" />
                        <col width="120px" />
                        <thead>
                        <tr class="success">
                            <th>产品名称</th>
                            <th>代理新开价</th>
                            <th>代理续费价</th>
                            <th>新开价格</th>
                            <th>续费价格</th>
                            <th>状态</th>
                            <th>备注</th>
                        </tr>
                        </thead>
                        <tbody>
                        <#list data.data as list>
                            <tr>
                                <td class="warning">${list.name}</td>
                                <td>${list.agent_price}</td>
                                <td>${list.agent_re_price}</td>
                                <td>${list.new_price}</td>
                                <td>${list.renew_price}</td>
                                <td><#if (list.status == 0)><cite class="glyphicon glyphicon-play font-green" title="开启中"></cite><#else><cite class="glyphicon glyphicon-pause font-gray" title="已暂停"></cite></#if></td>
                                <td>${list.bz}</td>
                            </tr>
                        </#list>
                        </tbody>
                    </table>
                </div>
            </td>
            <td>
                <p class="table-item-op">
                    <?php if($this->check_purview("/domain_register/price_edit")){?>
                    <button type="button" data-url="<?php echo U("/domain_register/price_edit?type_s=");?>${data.type}&id=${data.id}" class="btn btn-xs btn-success btn-setting editbtn">价格设置</button>&nbsp;
                    <?php }?>
                    <?php if($this->check_purview("/domain_register/price_del")){?>
                    <button type="button" data-url="<?php echo U("/domain_register/price_del?type_s=");?>${data.type}&id=${data.id}" class="btn btn-xs btn-default btn-setting delbtn">删除</button>&nbsp;
                    <?php }?>
                </p>
            </td>
        </tr>
    </#macro>
</script>
<!--点击隐藏显示切换小图标-->
<script type="text/javascript">
    function citeShow(obj){
        if($(obj,'.btn').parent().find('.detail').css('display') == 'none'){
            $(obj,'.btn').parent().parent().parent().find('.detail').hide();
            $(obj,'.btn').parent().find('.detail').show();
            $(obj,'.btn').parent().find('.glyphicon-chevron-down').removeClass().addClass('glyphicon glyphicon-chevron-up');
        }else{
            $(obj,'.btn').parent().find('.detail').hide();
            $(obj ,'.btn').parent().find('.glyphicon-chevron-up').removeClass().addClass('glyphicon glyphicon-chevron-down');
        }
    }
</script>
<script type="text/template" id="tpl_list_edit">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="form-content">
            <form action="<?php echo U("/domain_register/price_edit");?>" method="post" class="theform form form-2" role="form">
                <div class="tpl"></div>
            </form>
        </div>
    </#macro>
</script>
<script language="javascript">
    var formdata = {
        type:{type:"select",label:"域名类型",name:"type",value:"",data_sr:<?php echo JSON::encode($p_type);?>,css:"",require:"datatype='*'",desc:"",item_css:"col-md-6"},
        agent:{type:"select",label:"域名代理商",name:"agent",value:"<?php echo isset($t)?$t:"";?>",data_sr:<?php echo JSON::encode($reg_type);?>,css:"",require:"datatype='*'",desc:"",item_css:"col-md-6"},
        id:{type:"hidden",label:"id",name:"id",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
        type_s:{type:"hidden",label:"type_s",name:"type_s",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
        html_space0:{type:"html",value:"<h5>&nbsp;</h5>"},
        html_space0:{type:"html",value:"<h5>&nbsp;</h5>"},
        iname: {type: "ivalue", label: "域名价格组", name: "", value: "", data_sr: [], css: "f14", require: "", desc: "", item_css: ""},
        html_space6: {
            type: "html",
            value: "<div style='padding-left:120px;'>" +
                            "<p style='padding-bottom:8px;margin-top: -40px;'><button type='button' class='btnadd_item btn btn-xs btn-primary'><cite class='glyphicon glyphicon-plus'></cite>增加新值</button><span class='font-gray' style='margin-left: 37px'>*价格(元/年) — 如果折扣百分比为空，价格则为输入框价格，否则为备注百分比</span></p>" +
                            "<div class='attrspec_valbox'>" +
                                "<table class='table table-bordered' cellpadding='0' cellspacing='0' style='width:770px;'>" +
                                "<col>" +
                                "<col width='100px'>" +
                                "<col width='100px'>" +
                                "<col width='100px'>" +
                                "<col width='100px'>" +
                                "<col width='100px'>" +
                                "<col width='90px'>" +
                                "<col width='70px'>" +
                                "<thead>" +
                                "<tr class='active'>" +
                                "<th>产品名称</th>" +
                                "<th>代理新开价</th>" +
                                "<th>代理续费价</th>" +
                                "<th>新开价格</th>" +
                                "<th>续费价格</th>" +
                                "<th>折扣百分比</th>" +
                                "<th>状态</th>" +
                                "<th>操作</th>" +
                                "</tr>" +
                                "</thead>" +
                                "<tbody></tbody>" +
                                "</table>" +
                            "</div>" +
                        "</div>"
        },
        btn:{type:"button",label:"",value:"保存修改",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
    };
    var load_list = function(){
        $.loadlist([],function(res){
            $(".editbtn").click(function(){
                edit_func(this);
            });
            //添加属性按钮(点击绑定)
            $(".theform").find('.btnadd_item').unbind().bind('click',
                    function () {
                        add_trrow('', '', '', '', '',"","");
                    }
            );
            $(".delbtn").click(function(){
                del_func(this);
            });
            $('[data-toggle="tooltip"]').tooltip();
        },"<?php echo isset($pageurl)?$pageurl:"";?>");
    }
    var del_func = function(obj){
        if(confirm("你确定要删除该数据吗?删除后数据不可恢复！")){
            var url = $(obj).attr("data-url");
            $.ajaxPassport({
                url:url,
                success:function(res){
                    if(res.error == 1){
                        $.tips(res.message,"error");
                    }else{
                        $.tips(res.message,"success");
                        load_list();
                    }
                }
            })
        }
    }
    var edit_func = function(obj){
        var edit_c = $("#tpl_list_edit").html();
        var url = $(obj).attr("data-url");
        edit_c = "" + easyTemplate(edit_c,{title:"添加/修改"});
        $("#myModal").find(".modal-dialog").width(950);
        $("#myModal").find(".modal-content").html(edit_c);
        $.loadform(formdata,url,function(res){
            load_list();
            return true;
        },function (res) {
            for (var i in res.data_arr) {
                add_trrow(res.data_arr[i]['name'], res.data_arr[i]['agent_price'], res.data_arr[i]['agent_re_price'], res.data_arr[i]['new_price'], res.data_arr[i]['renew_price'], res.data_arr[i]['bz'], res.data_arr[i]['status']);
            }
            //添加属性按钮(点击绑定)
            $(".theform").find('.btnadd_item').unbind().bind('click',
                    function () {
                        add_trrow('', '', '', '', '',"","");
                    }
            );
        });
        $('#myModal').modal();
    }
    var add_trrow = function (name, agent_price, agent_re_price, new_price, renew_price,bz,status) {
        var size = $('.attrspec_valbox tbody tr').size();
        var status = parseInt(status);
        var row = '<tr class="td_c">'
                + '<td><input name="name[]" class="form-control" type="text" value="' + name + '" /></td>'
                + '<td><input name="agent_price[]" class="form-control" type="text" value="' + agent_price + '" /></td>'
                + '<td><input name="agent_re_price[]" class="form-control" type="text" value="' + agent_re_price + '" /></td>'
                + '<td><input name="new_price[]" class="form-control" type="text" value="' + new_price + '" /></td>'
                + '<td><input name="renew_price[]" class="form-control" type="text" value="' + renew_price + '" /></td>'
                + '<td><textarea name="bz[]" class="form-control" style="height: 30px;" >' + bz + '</textarea></td>'
                + '<td><select name="status[]" class="form-control"><option value="0" ' + (status == 0 ? "selected='selected'" : "") + '>开启</option><option value="1"' + (status == 1 ? "selected='selected'" : "") + '>暂停</option></select></td>'
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
    $(function(){
        load_list();
        $(".addbtn").click(function(){
            edit_func(this);
            $("input[name='id']").val("");
            add_trrow('', '', '', '', '',"","");
        });
    })
</script>

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