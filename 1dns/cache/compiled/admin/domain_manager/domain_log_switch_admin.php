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
        <?php if($this->check_purview("/domain_manager/domain")){?>
        <li><a href="<?php echo U("/domain_manager/domain");?>" >域名列表</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_qy")){?>
        <li><a href="<?php echo U("/domain_manager/domain_qy");?>">域名牵引</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_log")){?>
        <li><a href="<?php echo U("/domain_manager/domain_log");?>" >域名操作日志</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_log_switch")){?>
        <li><a href="<?php echo U("/domain_manager/domain_log_switch");?>" class="cur">域名切换日志</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_black")){?>
        <li><a href="<?php echo U("/domain_manager/domain_black");?>" >黑白名单</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_deleted")){?>
        <li><a href="<?php echo U("/domain_manager/domain_deleted");?>" >已删除域名</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_find")){?>
        <li><a href="<?php echo U("/domain_manager/domain_find");?>" >域名找回</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_diyline")){?>
        <li><a href="<?php echo U("/domain_manager/domain_diyline");?>">自定义线路</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_bind")){?>
        <li><a href="<?php echo U("/domain_manager/domain_bind");?>">别名绑定</a></li>
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
    <div class="name">域名切换日志</div>
    <div class="navbtn">
        <!--button-->
        <!--end button-->
    </div>
    <div class="navbtn0" style="width: 800px">
        <a class="btn <?php if($c == 100){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="" href="<?php echo U("/domain_manager/domain_log_switch");?>"><cite class="glyphicon glyphicon-th"></cite> 全部</a>
        <a class="btn <?php if($c == 10){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="" href="<?php echo U("/domain_manager/domain_log_switch?c=10");?>"><cite class="glyphicon glyphicon-th"></cite> 用户套餐升级切换</a>
        <a class="btn <?php if($c == 1){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm"  href="<?php echo U("/domain_manager/domain_log_switch?c=1");?>"><cite class="glyphicon glyphicon-th"></cite> 管理员套餐切换</a>
        <a class="btn <?php if($c == 2){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm"  href="<?php echo U("/domain_manager/domain_log_switch?c=2");?>"><cite class="glyphicon glyphicon-th"></cite> 管理员直接切换 </a>
        <a class="btn <?php if($c == 9){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm"  href="<?php echo U("/domain_manager/domain_log_switch?c=9");?>"><cite class="glyphicon glyphicon-th"></cite> 系统自动切换 </a>
    </div>
    <div class="cl"></div>
</div>
<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/domain_manager/domain_log_switch?do=get_url");?>">
    <div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
    <div class="list-box">
        <table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
            <col width="230px"/>
            <col width="150px"/>
            <col width="150px"/>
            <col width="100px"/>
            <col width="160px"/>
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
            <tbody class="tpl"></tbody>
        </table>
    </div>
    <div class="pagebar"></div>
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
            <td class="font-blue f18">${data.domain}</td>
            <td class="font-gray">${data.dateline}</td>
            <td>
                <#if ($.is_empty(data.status_dateline))>
                    <#if (data.status == 0)>
                    <span class='font-red'>待处理</span>
                    <#else> -
                    </#if>
                <#else>
                <span class='font-green'>${$.time_to_string(data.status_dateline)}</span>
                </#if>

            </td>
            <td><#if (data.status == 0)><span class="font-gray">未处理</span><#else><span class="font-green">已处理</span></#if></td>
            <td class="font-gray"><#if (data.type==0)>用户套餐升级切换<#elseif (data.type==1)>管理员套餐切换<#elseif (data.type==2)>管理员直接切换<#else>系统自动切换</#if></td>
            <td><span class="<#if (data.old_ns_group=='free')><#elseif (data.old_ns_group=='vip11')>font-org<#elseif (data.old_ns_group=='vip21')>font-green<#else></#if>">${ns_groups[data.old_ns_group].name}</span> <site class="glyphicon glyphicon-arrow-right font-gray"></site> <span class="<#if (data.new_ns_group=='free')>font-gray<#elseif (data.new_ns_group=='vip11')>font-org<#elseif (data.new_ns_group=='vip21')>font-green<#else></#if>">${ns_groups[data.new_ns_group].name}</span></td>
        </tr>
    </#macro>
</script>
<!-- edit ddd -->
<script language="javascript">
    var ns_groups = <?php echo JSON::encode($ns_group);?>;
    var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
    var search_formdata = {
    startdate:{type:"date",label:"时间",name:"startdate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:""},
    enddate:{type:"date",label:"-",name:"enddate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:"date-dis"},
    keyword:{type:"text",label:"关键词",name:"keyword",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
    btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
    };
    var loadlist = function(page){
        $.ajaxLoadlist(page,pageurl,function(res){
            var keyword = $(".the_searchform input[name='keyword']").val();
            if(keyword != ""){
                $(".thelistform").find(".tpl .keybox").each(function(){
                    var obj = this;
                    var html = $(obj).html();
                    $(obj).html($.replace_keyword(html,keyword))
                });
            };
            $(".clean-ns").click(function () {
                var url = $(this).attr("data-url");
                $.ajaxPassport({
                    url: url,
                    success: function (res) {
                        if (res.error == 1) {
                            $.tips(res.message, "error");
                        } else {
                            $.tips(res.message, "success");
                            loadlist();
                        }
                    }
                })
            });
            //全选，全不选
            $(".list-table").find("input.checkall").unbind("click").bind("click",function(){
                $.check_all(this);
            });
        });
    }
    $(function(){
        //加载搜索
        $.loadform(search_formdata,"",function(res){
            pageurl = res.pageurl;
            loadlist();
            return true;
        },null,".the_searchform");
        //加载列表
        $(".date-ymd").datetimepicker({
            language:  'zh-CN',
            autoclose: 1,
            startView: 2,
            minView: 2,
            maxView: 4,
            format:"yyyy-mm-dd",
            pickerPosition: "bottom-right"
        });
        loadlist();
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