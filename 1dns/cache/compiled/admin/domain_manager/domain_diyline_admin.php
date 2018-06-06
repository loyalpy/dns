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
        <?php if($this->check_purview("/domain_manager/domain")){?>
        <li><a href="<?php echo U("/domain_manager/domain");?>" >域名列表</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_qy")){?>
        <li><a href="<?php echo U("/domain_manager/domain_qy");?>">域名牵引</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_log")){?>
        <li><a href="<?php echo U("/domain_manager/domain_log");?>">域名操作日志</a></li>
        <?php }?>
        <?php if($this->check_purview("/domain_manager/domain_log_switch")){?>
        <li><a href="<?php echo U("/domain_manager/domain_log_switch");?>" >域名切换日志</a></li>
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
        <li><a href="<?php echo U("/domain_manager/domain_diyline");?>" class="cur">自定义线路</a></li>
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
    <div class="name">自定义线路</div>
    <div class="navbtn">
        <!--button-->
        <!--end button-->
    </div>
    <div class="cl"></div>
</div>
<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/domain_manager/domain_diyline?do=get_url");?>">
    <div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
    <div class="list-box">
        <table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
            <col width="40px"/>
            <col width="180px"/>
            <col />
            <col width="120px"/>
            <col width="150px"/>
            <col width="80px"/>
            <col width="100px"/>
            <thead>
            <tr>
                <th><input type="checkbox" data-name="domain_diy[]" class="checkall"/></th>
                <th>线路名称</th>
                <th>IP段</th>
                <th>线路域名</th>
                <th>用户</th>
                <th>状态</th>
                <th>操作</th>
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
<script type="text/template" id="tpl_list_row">
    <#macro row data>
        <tr>
            <td><input type="checkbox" name="domain_diy[]" value=""/></td>
            <td class="font-gray">[<span class="font-green">cust${data.id}</span>] 
            <span class="font-blue">${data.name}</span></td>
            <td class="font-gray">${data.ipaddr}</td>
            <td class="font-gray">${data.domain}</td>
            <td class="font-gray">${data.uid}</td>
            <td>
                <#if (data.status == 1)>
                    <span class="glyphicon glyphicon-ok font-green" title="正常"></span>
                    <#else>
                        <span class="glyphicon glyphicon-lock font-red" title="已锁定"></span>
                </#if>
            </td>
            <td>
                <?php if($this->check_purview("/domain_manager/domain_diyline_sh")){?>
                <button type="button" class="btn btn-default btn-xs btn-setting-sh" data-url="<?php echo U("/domain_manager/domain_diyline_sh?id=");?>${data.id}" data-id="${data.id}">审核</button>
                <?php }?>
                <?php if($this->check_purview("/domain_manager/domain_diyline_del")){?>
                <button type="button" class="btn btn-default btn-xs del-line" data-id="${data.id}">删除</button>
                <?php }?>
            </td>
        </tr>
    </#macro>
</script>
<!-- edit ddd -->
<script language="javascript">
    var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
    var search_formdata = {
    keyword:{type:"text",label:"关键词",name:"keyword",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
    btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
    };
    var loadlist = function(page){
        $.ajaxLoadlist(page,pageurl,function(){
            var keyword = $(".the_searchform input[name='keyword']").val();
            if(keyword != ""){
                $(".thelistform").find(".tpl .keybox").each(function(){
                    var obj = this;
                    var html = $(obj).html();
                    $(obj).html($.replace_keyword(html,keyword))
                });
            };
            //全选，全不选
            $(".list-table").find("input.checkall").unbind("click").bind("click",function(){
                $.check_all(this);
            });
            //自定义线路锁定
            $(".btn-setting-sh").click(function(){
                var url = $(this).data("url");
                edit_setting_check_func(url);
            });
            //自定义线路删除
            $(".del-line").unbind("click").bind("click",function () {
                var id = $(this).data("id");
                if(confirm("你确定要删除该自定义线路吗?删除后数据不可恢复！")){
                    $.ajaxPassport({
                        url:"<?php echo U("domain_manager/domain_diyline_del");?>",
                        success:function(res){
                            if(res.error == 1){
                                $.tips(res.message,"error");
                            }else{
                                $.tips(res.message,"success");
                                loadlist();
                            }
                        },
                        data:{id:id}
                    })
                }
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
        loadlist();
    })
</script>
<script type="text/template" id="tpl-setting-group">
    <#macro rowedit data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">${data.title}</h4>
        </div>
        <div class="form-content">
            <form action="<?php echo U("/domain_manager/domain_diyline_sh");?>" method="post" class="theform form form-2" role="form">
                <div class="tpl"></div>
            </form>
        </div>
    </#macro>
</script>
<script language="javascript">
    var setting_group_formdata = {
        domain:{type:"ivalue",label:"操作域名",name:"domain",value:'',data_sr:[],css:"font-gray",require:"",desc:"",item_css:""},
        status:{type:"select",label:"审核操作",name:"status",value:"1",data_sr:[{v: "解锁", key: "1"}, {v: "锁定", key: "2"}],css:"font-gray",require:"datatype='*'",desc:"",item_css:""},
        id:{type:"hidden",label:"",name:"id",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
        btn:{type:"button",label:"",value:"提交",desc:'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'}
    };
    var edit_setting_check_func = function(url){
        var edit_c = $("#tpl-setting-group").html();
        edit_c = "" + easyTemplate(edit_c,{title:"线路操作"});
        $("#myModal").find(".modal-dialog").width(500);
        $("#myModal").find(".modal-content").html(edit_c);
        $.loadform(setting_group_formdata,url,function(res){
            loadlist();
            return true;
        },".theform");
        $('#myModal').modal();
    }
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