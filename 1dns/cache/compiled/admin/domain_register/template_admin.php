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
        <li><a href="<?php echo U("/domain_register/template");?>" class="cur">域名注册模板</a></li>
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
    <div class="name">模板列表</div>
    <div class="navbtn0">
        <a class="btn <?php if($c == 0){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm" title="" href="<?php echo U("/domain_register/template");?>"><cite class="glyphicon glyphicon-th"></cite> 所有</a>
        <a class="btn <?php if($c == 1){?>btn-info<?php }else{?>btn-default<?php }?> btn-sm"  href="<?php echo U("/domain_register/template?c=1");?>"><cite class="glyphicon glyphicon-th"></cite> 待审核</a>
    </div>
    <div class="cl"></div>
</div>
<!--search box-->
<form class="the_searchform form" method="POST" action="<?php echo U("/domain_register/template?do=get_url");?>">
    <div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
    <div class="list-box">
        <table class="list-table table table-striped table-condensed table-responsive table-checkall" cellpadding="0" cellspacing="0">
            <col width="50px"/>
            <col width="150px" />
            <col width="100px" />
            <col width="100px" />
            <col width="150px" />
            <col width="155px"/>
            <col width="155px"/>
            <col  />
            <col width="140px"/>
            <thead>
            <tr>
                <th><input type="checkbox" data-name="tpls[]" class="checkall"/></th>
                <th>模板名称</th>
                <th>域名所有者</th>
                <th>类型</th>
                <th>域名所有者(英文)</th>
                <th>联系人邮箱</th>
                <th>用户</th>
                <th>详细</th>
                <th>状态</th>
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
            <td><input type="checkbox" name="tpls[]" value="${data.id}"/></td>
            <td><span class="font-blue ">${data.tpl_name}</span></td>
            <td><span class=" ">${data.m_name_cn}</span>&nbsp;
                <#if (data.is_tpl == 1)>
                    <span class="label label-success label-sm" data-toggle="tooltip" data-placement="top" title="默认注册模板">默认</span>
                </#if>
            </td>
            <td> <span class="font-green">[<#if (data.utype == 1)>个人<#else>企业</#if>]</span></td>
            <td> ${data.m_name}</td>
            <td>${data.email}</td>
            <td>${data.name}</td>
            <td>
                <?php if($this->check_purview("/domain_register/template_sh")){?>
                <button type="button" class="btn btn-primary btn-xs find-info" data-id="${data.id}">详细</button>
                <?php }?>
            </td>
            <td>
                <#if (data.is_use == 0)><span style="color:orange;">未提交实名资料</span>
                    <#elseif (data.is_use == 1)><span style="color:red;">审核不通过</span>
                        <#elseif (data.is_use == 2)><span style="color: green">审核通过</span>
                            <#elseif (data.is_use == 3)><span style="color:green;">实名资料审核中</span>
                                <#elseif (data.is_use == 4)><span style="color:red;">实名资料上传失败</span>
                </#if>
            </td>
        </tr>
    </#macro>
</script>

<!--查看详细信息-->
<script type="text/template" id="tpl_info">
    <#macro row data>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">注册模板详细信息</h4>
        </div>
        <div class="modal-body">
            <div class="dis15"></div>
            <table class="table domain-template-table">
                <col width="100px" />
                <col width="180px"/>
                <tr>
                    <td class="l">域名所有者中文名称：</td>
                    <td>${data.m_name_cn}</td>
                </tr>
                <tr>
                    <td class="l">域名所有者英文名称：</td>
                    <td>${data.m_name}</td>
                </tr>
                <tr>
                    <td  class="l">域名所有者类型：</td>
                    <td><#if (data.utype == 1)>个人<#else>企业</#if></td>
                </tr>
                <tr>
                    <td  class="l">联系人英文名称：</td>
                    <td>${data.name}</td>
                </tr>
                <tr>
                    <td class="l">联系人中文名称：</td>
                    <td>${data.name_cn}</td>
                </tr>
                <tr>
                    <td  class="l">联系人邮箱：</td>
                    <td>${data.email}</td>
                </tr>
                <tr>
                    <td class="l">联系人地区：</td>
                    <td>${data.area}</td>
                </tr>
                <tr>
                    <td  class="l">联系人中文通讯地址：</td>
                    <td>${data.addr_cn}</td>
                </tr>
                <tr>
                    <td  class="l">联系人英文通讯地址：</td>
                    <td>${data.addr}</td>
                </tr>
                <tr>
                    <td  class="l">注册邮编：</td>
                    <td>${data.ub}</td>
                </tr>
                <tr>
                    <td  class="l">注册手机号：</td>
                    <td>${data.mobile}</td>
                </tr>
                <tr>
                    <td class="l">传真：</td>
                    <td>${data.cz}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <!--<label class="fb text-danger">请选择审核结果&nbsp;</label>-->
            <!--<select class="form-control" name="status" style="display: inline-block;width: 130px">-->
                <!--<option value="1">审核通过</option>-->
                <!--<option value="0">取消审核</option>-->
            <!--</select>&nbsp;&nbsp;-->
            <!--<input type="hidden" name="tpl_id" value="${data.id}"/>-->
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <!--<button type="button" class="btn btn-primary btn-setting-check">审核</button>-->
        </div>
    </#macro>
</script>
<script language="javascript">
    var edit_setting_check_func = function(obj){
        var id = $(obj).data("id");
        $.ajaxPassport({
            url:"<?php echo U("/domain_register/template_sh");?>",
            type:"POST",
            success:function (res) {
                var edit_c = $("#tpl_info").html();
                edit_c = "" + easyTemplate(edit_c,res);
                $("#myModal").find(".modal-dialog").width(600);
                $("#myModal").find(".modal-content").html(edit_c);
                $('#myModal').modal();

                //URL审核
                $(".btn-setting-check").click(function(){
                    var tpl_id = $(this).parent().find("input[name='tpl_id']").val();
                    var status = $(this).parent().find("select[name='status']").val();
                    if ($.is_empty(tpl_id)) {
                        $.ui.error("模板ID不存在");
                    }
                    $.ajaxPassport({
                        url:"<?php echo U("/domain_register/template_sh");?>",
                        type:"POST",
                        success:function (res) {
                            if (res.error == 0) {
                                $.ui.success(res.message);
                                loadlist();
                                $('#myModal').modal("hide");
                            }else{
                                $.ui.error(res.message);
                            }
                        },
                        data:{id:tpl_id,status:status,hash:hash}
                    });
                });
            },
            data:{id:id}
        });
    }
</script>
<script language="javascript">
    var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
    var hash = "<?php echo tUtil::hash();?>";
    var c = "<?php echo isset($c)?$c:"";?>";
    var search_formdata = {
        email: {type: "uid", label: "用户", name: "email", value: "", disabled: 0, data_sr:[], css: "", require: "", desc: "", item_css: ""},
        keyword:{type:"text",label:"关键词",name:"keyword",value: "",data_sr:[],css:"",require:"",desc:"",item_css:""},
        c:{type:"hidden",label:"关键词",name:"c",value:c,data_sr:[],css:"",require:"",desc:"",item_css:""},
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

            //查看信息
            $(".find-info").click(function () {
                edit_setting_check_func(this);
            });
            $('[data-toggle="tooltip"]').tooltip();
        });

    }
    $(function(){
        //加载搜索
        $.loadform(search_formdata,"",function(res){
            pageurl = res.pageurl;
            loadlist();
            return true;
        },function(){
            get_userlist(1,'',"",".the_searchform");
        },".the_searchform");
        //加载列表
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