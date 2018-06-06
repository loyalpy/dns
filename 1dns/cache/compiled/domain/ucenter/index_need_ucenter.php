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
            <li><a href="<?php echo U("/ucenter/index");?>"  class="showtype cur">全部域名&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/transfer");?>"  class="showtype">域名转入&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/template");?>"  class="showtype">信息模板&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/order");?>"  class="showtype"> 我的订单&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right">
    <div>
        <h1><span class="list_tit_name">我的域名</span> <span class="list_tit_count am-text-sm am-text-success">(0)</span></h1>
    </div>
    <div class="am-g">
        <div class="am-btn-group">
            <a href="<?php echo U("/ucenter/index");?>" type="button" class="am-btn am-btn-default am-btn-sm am-radius"><i class="am-icon-align-justify"></i> 服务期域名</a>
            <a href="<?php echo U("/ucenter/index_need");?>" type="button" class="am-btn  am-btn-success am-btn-sm am-radius"><i class="am-icon-align-center"></i> 续费期域名</a>
            <a href="<?php echo U("/ucenter/index_repay");?>" type="button" class="am-btn am-btn-default am-btn-sm am-radius"><i class="am-icon-align-right"></i> 赎回期域名</a>
        </div>

        <div class="quickserch" style="float: right;width: 200px;">
            <input type="text" class="am-form-field am-radius am-input-sm am-serch-domains" placeholder="快速查找域名" />
        </div>

        <div class="am-dropdown" data-am-dropdown style="margin-left: 20px">
            <button class="am-btn am-btn-default am-dropdown-toggle am-radius am-btn-sm" data-am-dropdown-toggle>更多操作 <span class="am-icon-caret-down"></span></button>
            <ul class="am-dropdown-content">
                <li class="am-dropdown-header">选择后操作</li>
                <li><a href="javascript:void (0)" class="domainTransfer">转移用户</a></li>
                <li><a href="javascript:void (0)" class="domainBatch">批量续费</a></li>
                <li><a href="<?php echo U("/ucenter/register_domain_export");?>">域名列表导出</a></li>
            </ul>
        </div>

    </div>
    <div class="dis10"></div>
    <div class="listbody" style="position: relative;">
    </div>
</div>
<div class="my-domian-transfer"></div>
<div class="my-domian-upgrade"></div>
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
        <table class="am-table am-table-hover domain-register-table">
            <col width="30px"/>
            <col  />
            <col width="100px" />
            <col width="100px" />
            <col width="130px" />
            <col width="130px" />
            <col width="130px" />
            <col width="180px" />
            <thead>
            <tr>
                <th><input type="checkbox" data-name="domainId[]" class="checkall"/></th>
                <th>域名</th>
                <th>域名所有者</th>
                <th>域名类型</th>
                <th>注册日&nbsp;<a href="javascript:void(0)" class="orderby" data-item="reg_time" data-desc=""><cite></cite></a></th>
                <th>到期日&nbsp;<a href="javascript:void(0)" class="orderby" data-item="exp_time" data-desc=""><cite></cite></a></th>
                <th>服务状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody class="tpl am-form">
            <#if (data.list.length > 0)>
                <#list data.list as domain>
                        <tr>
                            <td><input type="checkbox" name="domainId[]"  value="${domain.id}"/></td>
                            <td class="d"><span>${domain.domain}</span></td>
                            <td class="d">${domain.domain_name}</td>
                            <td class="am-font-gray"><#if (domain.type == 0)>国际域名<#else>国内域名</#if></td>
                            <td>${domain.reg_time}</td>
                            <td>${domain.exp_time}</td>
                            <td class="am-font-gray">急需续费</td>
                            <td class="c">
                                <a href="javascript:;" class="domain-renew" data-domain_id="${domain.id}">续费</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                </#list>
                <#else>
                    <tr>
                        <td class="am-text-sm" colspan="7">
                            <a href="##" class="am-icon-exclamation-circle am-text-danger am-text-lg"></a> <a href="#" class="am-font-gray">没有符合条件的结果?</a></td>
                    </tr>
            </#if>
            </tbody>
        </table>
        <div class="pagebar">${data.pagebar}</div>
    </#macro>
</script>
<!--域名列表-->
<!--加入购物车成功-->
<script type="text/template" id="tpl_domain_cart">
    <#macro rowedit data>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-2">
            <div class="am-modal-dialog">
                <div class="am-modal-hd">
                    <i class="am-icon-check-circle" style="color: #5EB95E;"></i>&nbsp;&nbsp;加入购物车成功！
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                </div>
                <div class="dis20"></div>
                <div class="am-modal-bd">
                    <a href="javascript: void(0)" data-am-modal-close><button type="button" class="am-btn am-btn-default">继续续费</button></a>&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo U("domain@/cart/cart");?>"><button type="button" class="am-btn am-btn-warning">去购物车结算</button></a>
                </div>
                <div class="dis15"></div>
            </div>
        </div>
    </#macro>
</script>
<?php echo $this->fetch('ucenter/order_tpl')?>
<script type="text/javascript">
    $(function () {
        //加载域名列表
        load_domains_list(1,"","need",'');
        //搜索功能
        $("input.am-serch-domains").keyup(function(){
            var keyword = $(this).val();
            if (!$.is_empty(keyword)){
                load_domains_list(1,keyword,"need",'');
            }
        });

        //域名用户转移
        $(".domainTransfer").click(function(){
            domain_transfer_op();
        });
        //域名批量续费
        $(".domainBatch").click(function () {
            domain_batch_op();
        });
    });
    var load_domains_list = function(page,keyword,type,orderby){
        var keyword  = $.is_empty(keyword)?'':keyword;
        var orderby  = $.is_empty(orderby)?'':orderby;
        $.ui.loading($(".listbody"),0);
        $.ajaxPassport({
            url:"<?php echo U("/ucenter/get_list_domain");?>",
            success:function(res){
                $.ui.close_loading($(".listbody"));
                var listhtml = ""+ easyTemplate($("#tpl_domain_list").html(),res);
                $(".listbody").html(listhtml);

                $(".listbody").find("input.checkall").unbind("click").bind("click",function(){
                    $.check_all(this);
                });
                $("a").bind("focus",function(){
                    $(this).blur();
                });
                $(".list_tit_count").html("("+res.total+")");
                //域名续费
                $(".domain-renew").click(function () {
                    var domain_id = $(this).data("domain_id");
                    batch_domain_detial_op(0,domain_id);
                });
                //排序
                var orderby_arr = res.orderby.split("!");
                $(".domain-register-table").find("a.orderby").each(function () {
                    var obj = this;
                    var or_item = $(obj).attr("data-item");
                    var or_v = "DESC";
                    $(obj).attr("data-desc", or_v);
                    $(obj).find("cite").attr("class", "am-icon-arrow-down");
                    if (or_item == orderby_arr[0]) {
                        or_v = orderby_arr[1];
                        $(obj).attr("data-desc", or_v);
                        if (or_v == "ASC") {
                            $(obj).find("cite").attr("class", "am-icon-arrow-up");
                        }
                    }
                    $(obj).unbind("click").bind("click", function () {
                        load_domains_list(1, keyword,"need",(or_item + "!" + (or_v == "ASC" ? "DESC" : "ASC")));
                    });
                });
            },
            data:{page:page,keyword:keyword,type:type,orderby:orderby},
        });
    }
    var batch_op = {data:{type:2}};
    var ids= new Array();
    var domain_batch_op = function() {
        var ids_tmp  = $.fetch_ids("domainId[]");
        ids = ids_tmp.split(",");
        if (ids == "") {
            $.ui.error('请选择要续费的域名！');
            return false;
        }
        batch_domain_detial_op(0,'');
    }
    num = 0;
    var batch_domain_detial_op = function(i,domain_id){
        if (domain_id) {
            ids[0] = domain_id;
            batch_op.data.domain_id = domain_id;
        }else{
            batch_op.data.domain_id=ids[i];
            if( i >= ids.length || typeof ids[i] == "undefined"){
                num = 0;
                return false;
            }
        }
        $.ajaxPassport({
            url: "<?php echo U("/ucenter/domain_renew");?>",
            success: function (res) {
                if (res.error == 0) {
                    num++;
                }
                //执行最后一次时提示操作结果
                if (i == (ids.length - 1)) {
                    if (num >0) {
                        setTimeout(function () {
                            var html3 = "" + easyTemplate($("#tpl_domain_cart").html());
                            $(".my-domian-upgrade").html(html3);
                            $(".my-domian-upgrade").find('#doc-modal-2').modal({width: 300});

                            domain_register_tips();
                        },100);
                    }else{
                        $.ui.error(res.message);
                    }
                }
                batch_domain_detial_op(i+1,'');
            },
            data: batch_op.data,
        });
    }
</script>
<!--域名转移用户开始-->
<script type="text/template" id="tpl_domain_transfer">
    <#macro rowedit domain>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1-t">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" style="border-bottom: 1px solid silver;text-align: left;color:black;padding-bottom: 16px;">
                    <i class="am-icon-share-square-o" style="color: #EB8500;"></i>&nbsp;&nbsp;域名用户转移&nbsp;&nbsp;<span style="font-size: 12px;color: grey">小贴士：转移后域名将不再属于您，请选择操作！</span>
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                </div>
                <div class="dis10"></div>
                <div class="am-modal-bd" style="font-size: 14px;text-align: left;padding: 30px 30px 60px 30px;text-align: center;">
                    <span style="font-weight: 600;font-size: 16px">转移对象：</span>
                    <input type="text" class="am-form-field am-radius am-input-sm" name="email" style="width: 280px;display: inline-block;height: 32px"/><br/>
                    <small style="color: gray;margin-left: 15px">请输入域名转移的对象,对象格式为邮箱</small><br/>
                    <div class="dis15"></div>
                    <span style="font-weight: 600;font-size: 16px">登录密码：</span>
                    <input type="password" class="am-form-field am-radius am-input-sm" name="password" placeholder="" style="width: 280px;display: inline-block;height: 32px"/><br/>
                    <small style="color: gray;margin-left: -85px;">请输入账户登录密码</small>
                    <div class="dis30"></div>
                    <button type="button" class="am-btn am-btn-warning transfer-domain">确定</button>&nbsp;&nbsp;&nbsp;&nbsp;
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
            $.ui.error('请选择要转移的域名！');
            return false;
        }
        var html = "" + easyTemplate($("#tpl_domain_transfer").html());
        $(".my-domian-transfer").html(html);
        $(".my-domian-transfer").find('#doc-modal-1-t').modal({width: 600});

        $(".transfer-domain").click(function () {
            var email = $(this).parent().find("input[name='email']").val();
            var password = $(this).parent().find("input[name='password']").val();
            $.ui.loading($("#doc-modal-1-t"));
            $.ajaxPassport({
                url:"<?php echo U("/ucenter/domain_transfer");?>",
                success:function (res) {
                    $.ui.close_loading($("#doc-modal-1-t"));
                    if (res.error == 0) {
                        $.ui.success(res.message);
                        setTimeout(function () {
                            $.redirect("<?php echo U("ucenter/index");?>");
                        },500)
                    }else{
                        $.ui.error(res.message);
                    }
                    return false;
                },
                data:{email:email,password:password,domain_id:ids.join(",")}
            });
        });
    }
</script>
<!--域名转移用户结束-->
</body>
</html>