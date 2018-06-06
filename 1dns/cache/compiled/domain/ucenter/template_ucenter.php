<!doctype html>
<html class="no-js">
<head>
<!--[if lt IE 10]>
<![endif]-->
<!--[if lt IE 7]>
<script>location.href="/ie.html"</script>
<![endif]-->
<!-- page head -->

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">

<link rel="stylesheet" href="<?php echo U("static/javascript/amazeui/css/amazeui.min.css");?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/account_style_uc.css";?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/account_style_uc_py.css";?>"></head>
<body>
<!-- topbar -->
<div class="topbar">
  <div class="aps">
    <div class="top-left-nav">
    <ul>
    <li>
    <a href="<?php echo U("home@/");?>" class="logo"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>" alt="八戒DNS" /></a>
    </li>
    <li><a href="javascript:void(0)" id="navSwitch" title="导航"><i class="am-icon-bars"></i> &nbsp;</a></li>
      <?php $tmplist_d =  M("register_domain")->query("uid = $uid","","dateline DESC",10)?>
      <?php if(count($tmplist_d) > 0){?>
      <li class="domain-li-d">
        <a href="javascript:void (0)" class="s">我的域名 <cite class="am-icon-caret-down"></cite></a>
        <div class="domain-li-dup">
          <table cellpadding="0" cellspacing="0" border="0" class="am-table am-table-hover">
            <thead>
            <tr>
              <th>域名</th>
              <th>操作</th>
              <th></th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($tmplist_d as $key => $item){?>
            <tr>
              <td><a href="<?php echo U("/ucenter/basic?domain=");?><?php echo isset($item['domain'])?$item['domain']:"";?>" class="tr-td-a"><?php echo isset($item['domain'])?$item['domain']:"";?></a></td>
              <td><a  href="javascript:;" class="a-a domain-renew" data-domain_id="<?php echo isset($item['id'])?$item['id']:"";?>">续费</a></td>
              <td><a  class="a-a" href="<?php echo U("account@/domains/dns/");?><?php echo isset($item['domain'])?$item['domain']:"";?>" target="_blank">解析</a></td>
              <td><a  class="a-a" href="<?php echo U("/ucenter/basic?domain=");?><?php echo isset($item['domain'])?$item['domain']:"";?>">管理</a></td>
            </tr>
            <?php }?>
            </tbody>
          </table>
          <p><a href="<?php echo U("/ucenter/index");?>">查看全部域名&gt;&gt;</a></p>
        </div>
      </li>
      <?php }?>
    </ul>
    </div>
    <div class="top-domain-search">
    <div class="in-search">
      <form  method="get" action="<?php echo U("domain@/domain/lists");?>" target="_blank" >
      <div class="domain-inp">
      <input type="text" class="search" name="reg_domain" value="" autocomplete="off" placeholder="" />
      </div>      
      <div class="btn-buy"><button type="submit">查域名</button></div>
      </form>
    </div>
    </div>
    <div class="top-right-nav">
    <ul>
      <li>
      <a href="<?php echo U("account@/ucenter/profile_msg");?>" style="padding:0 10px;"><span class="am-icon-envelope-o"></span>
      <span class="am-badge am-badge-warning am-round"><?php echo M("sys_information")->get_one("recieve_uid=$uid AND status=0","count(*)");?></span></a>
      </li>
      <li>
      <a href="<?php echo U("/cart/cart");?>" style="padding:0 10px;"><span class="am-icon-shopping-cart"></span>
      <span class="am-badge am-badge-warning am-round domain_register_tips"><?php echo M("domain_register_cart")->get_one("uid=$uid AND status=0 AND indel=0","count(*)");?></span>
      </a>
      </li> 
      <li class="am-dropdown setting" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-user"></span> <?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?> <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="<?php if($this->userinfo['utype'] == 1){?><?php echo U("account@/ucenter/profile_basic");?><?php }else{?><?php echo U("account@/ucenter/profile_basic_com");?><?php }?>"><span class="am-icon-cog"></span> 资料</a></li>
          <li><a href="<?php echo U("account@/ucenter/safety_center");?>"><span class="am-icon-shield"></span> 安全</a></li>
          <?php if($this->userinfo['urole'] > 0){?>
          <li><a target="_blank" href="<?php echo U("admin@/");?>"><span class="am-icon-th-large"></span> 管理</a></li>
          <?php }?>
          <li><a href="<?php echo U("account@/login/logout");?>"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>    
    </ul>
    </div>
  </div>
</div>
<!-- end topbar -->
<div class="mainnav mainnav-black" id="MainNav">
  <ul class="main-ul">
  <li><a href="<?php echo U("account@/domains/index");?>" <?php if(in_array($inc,array("domains","records"))){?>class="cur"<?php }?>><i class="am-icon-globe"></i> &nbsp;域名解析</a></li>
  <li><a href="<?php echo U("account@/monitor/monitor");?>" <?php if(in_array($inc,array("monitor"))){?>class="cur"<?php }?>><i class="am-icon-desktop "></i> &nbsp;域名监控</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("domain@/ucenter/index");?>" <?php if($app == "domain"){?>class="cur"<?php }?>><i class="am-icon-wordpress"></i> &nbsp;域名注册</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("account@/finance/index");?>"><i class="am-icon-user"></i> &nbsp;账户</a></li>
  <li><a href="<?php echo U("account@/order/order");?>"><i class="am-icon-reorder"></i> &nbsp;订单</a></li>
  <li><a href="<?php echo U("account@/ucenter/safety_center");?>"><i class="am-icon-gear"></i> &nbsp;设置</a></li>
  </ul>
</div>
<div class="am-uc-left">
    <div class="leftnav" id="Leftnav">
        <ul>
            <li><a href="<?php echo U("/ucenter/index");?>"  class="showtype ">全部域名&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/transfer");?>"  class="showtype">域名转入&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/template");?>"  class="showtype cur">信息模板&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/order");?>"  class="showtype"> 我的订单&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right">
    <div>
        <h1 style="float: left"><span class="list_tit_name">域名信息管理模板</span> <span class="list_tit_count am-text-sm am-text-success">(0)</span></h1>
        <a type="button" href="<?php echo U("/ucenter/template_info?do=add");?>" class="am-btn am-btn-success am-btn-sm" style="float: right"><i class="am-icon-plus"></i> 创建新的模板信息</a>
    </div>
    <div class="clear"></div>
    <div class="dis10"></div>
    <div class="listbody" style="position: relative;"></div>
    <div class="dis30"></div>
    <div class="now-dns-bot">
        <div class="dis10"></div>
        <dl>
            <dt>温馨提示：</dt>
            <!--<?php $register_type = tCache::read("data_config")?>-->
            <!--<?php $register_type =  isset($register_type['domain_register_type'])?implode("/",$register_type['domain_register_type']):'';?>-->
            <!--<dd>• <?php echo isset($register_type)?$register_type:"";?>域名需选择实名认证的信息模板。</dd>-->
            <dd>• 上传实名制资料后的模板需等待1-2个工作日的审核时间，有关联域名的模板不能删除。</dd>
            <dd>• 信息模版必须填写完整，才可在域名注册、域名过户等功能中使用。</dd>
            <dd>• 模版区分为个人、企业两种类型，均为通用模版，即无论是国内域名还是国际域名，进行域名注册、域名过户等均可使用。</dd>
            <dd>• 为了便于您快速注册域名等，您可设置并维护相关模版信息，每个账户ID下最多可维护20个信息模版。</dd>
        </dl>
    </div>
</div>
<div class="my-domian-transfer"></div>
<div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
    <a href="#top" title="">
          <i class="am-gotop-icon am-icon-chevron-up"></i>
    </a>
</div>
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<!--[if lte IE 8 ]>
<script src="<?php echo U("static/javascript/amazeui/js/modernizr.js");?>"></script>
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.ie8polyfill.min.js");?>"></script>
<![endif]-->
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.min.js");?>"></script>
<script src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<?php if($uid){?>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<?php }?>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>
$(function(){
  $("#navSwitch").bind("click",function(){
    if($("#MainNav").is(':hidden')){
        $("body").css({"padding-left":"198px"});
        $("#MainNav").show();
    }else{
        $("body").css({"padding-left":"10px"});
        $("#MainNav").hide();
    }
  });
  //鼠标经过显示多个域名
  $("li.domain-li-d").find("a.s,.domain-li-dup").hover(function(){
    $(this).addClass("hover");
    $(".domain-li-d").find(".domain-li-dup").show();
  }, function(){
    $(this).removeClass("hover");
    $(".domain-li-d").find(".domain-li-dup").hide();
  });
})
</script>
<!--域名列表-->
<script type="text/template" id="tpl_domain_list">
    <#macro rowedit data>
        <table class="am-table am-table-bordered am-table-hover domain-register-table">
            <col />
            <col  width="70px"/>
            <col  width="130px"/>
            <col width="190px" />
            <col width="120px" />
            <col width="200px" />
            <thead>
            <tr>
                <th>模板名称</th>
                <th>类型</th>
                <th>域名所有人</th>
                <th>联系邮箱</th>
                <th>实名审核状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody class="tpl am-form">
            <#if (data.list.length > 0)>
                <#list data.list as info>
                        <tr>
                            <td>${info.tpl_name}(<span class="getwith" title="关联域名" data-name="${info.tpl_name}" style="cursor:pointer;color: blue">${info.dnlists}</span>)</td>
                            <td><#if (info.utype == 1)>个人<#else>企业</#if></td>
                            <td>${info.aller_name_cn}</td>
                            <td>${info.email}</td>
                            <td>
                                <#if (info.is_use == 0)><span style="color:orange;">未提交实名资料</span>
                                    <#elseif (info.is_use == 1)><span style="color:red;">审核不通过</span>
                                    <#elseif (info.is_use == 2)><span style="color: green">审核通过</span>
                                    <#elseif (info.is_use == 3)><span style="color:green;">实名资料审核中</span>
                                    <#elseif (info.is_use == 4)><span style="color:red;">实名资料上传失败</span>
                                </#if>
                            </td>
                            <td>
                                <div class="set-m-m">
                                    <#if (info.is_tpl == 1)>
                                        <a type="button" class="am-ext-btn" style="color: green">注册默认</a>&nbsp;
                                        <#else>
                                            <a type="button" class="am-ext-btn set-m-tpl" data-id="${info.id}" style="cursor: pointer">设为默认</a>&nbsp;
                                    </#if>
                                </div>
                                <a type="button" href="<?php echo U("/ucenter/template_info?do=edit&id=");?>${info.id}" class="am-ext-btn">修改</a>&nbsp;
                                <#if (info.dnlists == 0)>
                                <button type="button"  class="am-ext-btn Del" data-id="${info.id}" >删除</button>
                                </#if>
                            </td>
                        </tr>
                </#list>
                <#else>
                    <tr>
                        <td class="am-text-sm" colspan="6">
                            <a href="##" class="am-icon-exclamation-circle am-text-danger am-text-lg"></a> <a href="##" class="am-font-gray">没有域名模板?</a></td>
                    </tr>
            </#if>
            </tbody>
        </table>
        <div class="pagebar">${data.pagebar}</div>
    </#macro>
</script>
<script type="text/javascript">
    $(function () {
        //加载域名列表
        load_tpl_list(1);
    });
    var load_tpl_list = function(page){
        $.ui.loading($(".listbody"),0);
        $.ajaxPassport({
            url:"<?php echo U("/ucenter/template");?>",
            success:function(res){
                $.ui.close_loading($(".listbody"));
                var listhtml = ""+ easyTemplate($("#tpl_domain_list").html(),res);
                $(".listbody").html(listhtml);

                $("a").bind("focus",function(){
                    $(this).blur();
                });
                $(".list_tit_count").html("("+res['list'].length+")");

                //删除模板
                $(".Del").click(function () {
                    var id = $(this).data('id');
                    $.ui.confirm(function () {
                        if ($.is_empty(id)) {
                            $.ui.error("模板不存在！");
                            return false;
                        }
                        $.ui.loading();
                        $.ajaxPassport({
                            url:"<?php echo U("/ucenter/template_del");?>",
                            success:function(res){
                                $.ui.close_loading();
                                if (res.error == 1) {
                                    $.ui.error(res.message);
                                }else{
                                    $.ui.success(res.message);
                                    setTimeout(function () {
                                        window.location.replace("<?php echo U("/ucenter/template");?>");
                                    },500);
                                }
                            },
                            data:{id:id},
                        });
                    },"你确定要删除此模板吗？");
                });
                //设置为默认模板
                $(".set-m-tpl").click(function () {
                    var tpl_id = $(this).data('id');
                    var obj = this;
                    $.ui.confirm(function () {
                        if ($.is_empty(tpl_id)) {
                            $.ui.error("模板不存在！");
                            return false;
                        }
                        $.ui.loading();
                        $.ajaxPassport({
                            url:"<?php echo U("/ucenter/template_set");?>",
                            success:function(res){
                                $.ui.close_loading();
                                if (res.error == 1) {
                                    $.ui.error(res.message);
                                }else{
                                    $.ui.success(res.message);
                                    setTimeout(function () {
                                        window.location.replace("<?php echo U("/ucenter/template");?>");
                                    },500);
                                }
                            },
                            data:{id:tpl_id},
                        });
                    },"你确定要设置为默认模板吗？");
                });
                $(".getwith").click(function () {
                    var obj = this;
                    var name = $(obj).data("name");

                    $.ajaxPassport({
                        url:"<?php echo U("ucenter/get_tpl_dn");?>",
                        success:function (res) {
                            var result = {name:name};
                            if (res.error == 1) {
                                result.dn = 0;
                            }else{
                                result.dn = res.message;
                            }
                            var html = "" + easyTemplate($("#tpl_domain_cart").html(),result);
                            $(".my-domian-transfer").html(html);
                            $(".my-domian-transfer").find('#doc-modal-1-t').modal({width: 600});
                        },
                        data:{tpl_name:name}
                    });

                });
            },
            data:{page:page,do:"get"},
        });
    }
</script>
<!--关联域名-->
<script type="text/template" id="tpl_domain_cart">
    <#macro rowedit data>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1-t">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" style="border-bottom: 1px solid silver;text-align: left;color:black;padding-bottom: 16px;">
                    <i class="am-icon-code-fork" style="color: #EB8500;"></i>&nbsp;&nbsp;关联域名&nbsp;&nbsp;<span style="font-size: 12px;color: grey">小贴士：有关联域名的模板不能做删除操作！</span>
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                </div>
                <div class="dis10"></div>
                <div class="am-modal-bd" style="font-size: 14px;padding: 10px 30px 80px 30px;text-align: center;">
                    <table class="am-table">
                        <col width="200px"/>
                        <col/>
                        <thead>
                        </thead>
                        <tbody>
                        <tr >
                            <td style="border: 0px;text-align: right">模板名称：</td>
                            <td style="border: 0px;text-align: left">${data.name}</td>
                        </tr>
                        <tr >
                            <td style="border: 0px;text-align: right">关联域名：</td>
                            <td style="border: 0px;text-align: left">
                                <#if (data.dn == 0)>0<#else>${data.dn.length}</#if>个
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="dis10"></div>
                    <table class="am-table am-table-bordered">
                        <col width="200px"/>
                        <col width="200px"/>
                        <col/>
                        <thead>
                        <tr >
                            <th style="text-align: center">域名</th>
                            <th style="text-align: center">注册时间</th>
                            <th style="text-align: center">到期时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <#if (data.dn != 0)>
                            <#list data.dn as dn>
                            <tr>
                                <td>${dn.domain}</td>
                                <td>${dn.reg_time}</td>
                                <td>${dn.exp_time}</td>
                            </tr>
                            </#list>
                            <#else>
                                <tr><td colspan="3" style="text-align: left;color: darkgray;">&nbsp;&nbsp;无关联域名！</td></tr>
                        </#if>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </#macro>
</script>
</body>
</html>