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
<meta http-equiv=”X-UA-Compatible” content=”IE=Edge,chrome=1″ >
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">

<link rel="stylesheet" href="<?php echo U("static/javascript/amazeui/css/amazeui.min.css");?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_uc.css";?>"></head>
<body>
<!-- topbar -->
<div class="topbar">
  <div class="aps">
    <div class="top-left-nav">
    <ul>
    <li>
    <a href="<?php echo U("home@/");?>" class="logo"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>" alt="八戒DNS" /></a>
    </li>
    <li>
      <a href="javascript:void(0)" id="navSwitch" title="帮助与支持"><i class="am-icon-bars"></i> &nbsp;</a>
    </li>
      <?php $tmplist_d =  M("domain")->query("uid = $uid","","lastupdate DESC",10)?>
      <?php if(count($tmplist_d) > 0){?>
      <li class="domain-li-d">
        <a href="javascript:void (0)" class="s">我的域名 <cite class="am-icon-caret-down"></cite></a>
        <div class="domain-li-dup">
          <table cellpadding="0" cellspacing="0" border="0" class="am-table am-table-hover">
            <thead>
            <tr>
              <th>域名</th>
              <th>是否指向</th>
              <th>统计</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($tmplist_d as $key => $item){?>
            <tr>
              <td><a href="<?php echo U("/domains/dns/");?><?php echo isset($item['domain'])?$item['domain']:"";?>" class="tr-td-a"><?php echo isset($item['domain'])?$item['domain']:"";?> <span style="color: #ccc;font-weight: 400;"><?php if($item['records'] > 0){?>(<?php echo isset($item['records'])?$item['records']:"";?>)<?php }?></span></a></td>
              <td><?php if($item['inns'] != 1){?><cite class="am-icon-exclamation-circle am-text-warning" title="域名DNS未指向我们"></cite><?php }else{?><cite class="am-icon-check-circle am-text-success" title="域名DNS已指向我们"></cite><?php }?></td>
              <td><a href="<?php echo U("/records/records_count?domain=");?><?php echo isset($item['domain'])?$item['domain']:"";?>"><cite  class="am-icon-line-chart am-icon-line-chart1"></cite></a></td>
            </tr>
            <?php }?>
            </tbody>
          </table>
          <p><a href="<?php echo U("/domains/index");?>">查看全部域名&gt;&gt;</a></p>
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
      <a href="<?php echo U("/ucenter/profile_msg");?>" style="padding:0 10px;"><span class="am-icon-envelope-o"></span>
      <span class="am-badge am-badge-warning am-round"><?php echo M("sys_information")->get_one("recieve_uid=$uid AND status=0","count(*)");?></span></a>
      </li>
      <li>
      <a href="<?php echo U("/order/cart_shopping");?>" style="padding:0 10px;"><span class="am-icon-shopping-cart"></span>
      <span class="am-badge am-badge-warning am-round domain-parse-tips"><?php echo M("cart")->get_one("uid=$uid AND status=0 AND indel=0","count(*)");?></span>
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
<div class="mainnav <?php if(in_array($inc,array("order","finance","ucenter"))){?>mainnav-<?php }?>" id="MainNav">
  <ul class="main-ul">
  <li><a href="<?php echo U("/domains/index");?>" <?php if(in_array($inc,array("domains","records"))){?>class="cur"<?php }?>>
  <i class="am-icon-globe"></i> &nbsp;域名解析</a></li>
  <li><a href="<?php echo U("/monitor/monitor");?>" <?php if(in_array($inc,array("monitor"))){?>class="cur"<?php }?>><i class="am-icon-desktop "></i> &nbsp;域名监控</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("domain@/ucenter/index");?>"><i class="am-icon-wordpress"></i> &nbsp;域名注册</a></li>
  <li class="line"></li>
  <li><a href="<?php echo U("account@/finance/index");?>" <?php if(in_array($inc,array("finance"))){?>class="cur"<?php }?>><i class="am-icon-user"></i> &nbsp;账户</a></li>
  <li><a href="<?php echo U("account@/order/order");?>" <?php if(in_array($inc,array("order"))){?>class="cur"<?php }?>><i class="am-icon-reorder"></i> &nbsp;订单</a></li>  
  <li><a href="<?php echo U("account@/ucenter/safety_center");?>" <?php if(in_array($inc,array("ucenter"))){?>class="cur"<?php }?>><i class="am-icon-gear"></i> &nbsp;设置</a></li>
  </ul>
</div>
<div class="am-uc-left">
    <div class="leftnav" id="Leftnav">
        <ul>
            <li><a href="<?php echo U("/ucenter/safety_center");?>"  >安全设置&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_basic");?>">个人资料&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_basic_com");?>">企业资料&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_msg");?>" class="cur">系统通知&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_passwd");?>"  >修改密码&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right">
    <div>
        <h1><span class="list_tit_name">系统通知</span></h1>
    </div>
    <div class="am-btn-group" style="float: left">
        <button type="button" class="am-btn am-btn-default am-radius am-btn-sm viewInformation all-infor" data-status="0">全部信息(<?php echo isset($all_infor)?$all_infor:"";?>)</button>
        <button type="button" class="am-btn am-btn-default am-radius am-btn-sm viewInformation view-infor" data-status="1" view-infor="<?php echo isset($view_infor)?$view_infor:"";?>">已读信息(<?php echo isset($view_infor)?$view_infor:"";?>)</button>
        <button type="button" class="am-btn am-btn-default am-radius am-btn-sm viewInformation no-view-infor" data-status="2" no-view-infor="<?php echo $all_infor-$view_infor?>">未读信息(<?php echo $all_infor-$view_infor?>)</button>
    </div>
    <div class="batch_information" style="position: relative;float: left;left: 30px;">
        <button type="button" class="am-btn am-btn-success am-radius am-btn-sm inforOption" data-do="mark">批量标记已读</button>
        <button type="button" class="am-btn am-btn-danger am-radius am-btn-sm inforOption" data-do="del">删除</button>
    </div>
    <div class="am-u-lg-6" style="float: right;width: 300px;margin-right: -15px;">
        <div class="am-input-group am-input-group-sm">
            <input type="text"  class="am-form-field infor-keyword" placeholder="请输入关键词">
            <span class="am-input-group-btn">
                <button class="am-btn keyword" type="button"><span class="am-icon-search"></span></button>
            </span>
        </div>
    </div>
    <div class="dis10"></div>
    <div class="dis10"></div>
    <div class="am-information-content">
        <div class="listbody"></div>
    </div>
</div>
<div class="floatdiv" id="Dfloatdiv" style="display:none;">
  <div class="item" style="border-top: solid 1px #ddd;"><cite class="fedit" title="扫码关注"></cite>
    <div class="in" style="width: 0px;height: 100px; overflow: hidden;" _w="100"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/weixin.jpg";?>" width="100px" height="100px"/></div>
  </div>
  <div class="line"></div>
  <div class="item"><cite class="fqq" title="联系客服"></cite>
    <div class="in" style="width:0px;" _w="85"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo isset($site['qq'])?$site['qq']:"";?>&site=qq&menu=yes">联系客服</a></div>
  </div>
  <div class="line"></div>
  <div class="item" style="border-bottom: solid 1px #ddd;"><cite class="ftel" title="联系电话"></cite>
    <div class="in" style="width:0px;" _w="115"><a href="javascript:void(0);"><?php echo isset($site['tel'])?$site['tel']:"";?></a></div>
  </div>
</div>
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
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<script language="javascript">
  var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
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
<script type="text/javascript">
  $(function(){
    $("#Dfloatdiv").fadeIn();
    $("#Dfloatdiv").find(".item").hover(function(){
            var sIn_obj = $(this).find(".in");
            $(this).addClass("item-over");
            sIn_obj.stop(true,false).animate({"width":sIn_obj.attr("_w")},300);
          },function(){
            $(this).removeClass("item-over");
            $(this).find(".in").stop(true,false).animate({"width":0},300);
          }
    );
  })

</script>
<script type="text/template" id="tpl_information_list">
    <#macro rowedit data>
        <table class="am-table">
            <col width="30"/>
            <col width="20"/>
            <col />
            <col width="200"/>
            <thead>
            <tr>
                <th><input type="checkbox" data-name="inforId[]" class="checkall"/></th>
                <th></th>
                <th>详情</th>
                <th>创建时间</th>
            </tr>
            </thead>
            <tbody>
            <#if (data.list.length > 0)>
            <#list data.list as infor>
                <tr class="click-d information_${infor.id}" <#if (infor.status==1)>style="color: gray;"</#if> data-infor="${infor.id}">
                    <td><input type="checkbox" name="inforId[]" value="${infor.id}"/></td>
                    <td><#if (infor.status==0)><i class="am-icon-bell" style="color: #3BB4F2"></i></#if></td>
                    <td>
                        ${infor.title}<br/>
                        <span style="font-size: 14px">${infor.content}</span>
                    </td>
                    <td>${$.time_to_string(infor.dateline,'Y-m-d H:i')}</td>
                </tr>
            </#list>
                <#else>
                    <tr><td colspan="4"><a href="##" class="am-icon-exclamation-circle am-text-danger am-text-lg"></a> <a href="##" class="am-font-gray">没有符合条件的结果?</a></td></tr>
            </#if>
            </tbody>
        </table>
        <div class="pagebar">${data.pagebar}</div>
    </#macro>
</script>
<script type="text/javascript">
    $(function(){
        // 搜索内容事件
        $("button.keyword").click(function(){
            var keyword = $(".infor-keyword").val();
            if (!$.is_empty(keyword)){
                load_information_list(1,keyword);
            }else{
                load_information_list(1);
            }
        });
        // 未读信息，已读信息，全部信息
        $(".viewInformation").click(function(){
            var condition = $(this).attr("data-status");
            load_information_list(1,'',condition);
        });
        //批量操作
        $(".inforOption").click(function(){
            batch_infromation_op(this);
        });
        load_information_list(1);
    });
    //加载通知列表
    var load_information_list = function(page,keyword,condition){
        var url = "<?php echo U("/ucenter/profile_msg?do=get");?>";
        var keyword  = $.is_empty(keyword)?'':keyword;
        var condition  = $.is_empty(condition)?'':condition;
        $.ajaxPassport({
            url:url,
            success:function(res){
                if (!$.is_empty(keyword) && !$.is_array(res.data.list)) {
                    res.data.list = "没有包含"+" '"+keyword+"' "+"的消息，请重新输入搜索内容";
                }
                var listhtml = ""+ easyTemplate($("#tpl_information_list").html(),res.data);

                $(".listbody").html(listhtml);
                $(".listbody").find("input.checkall").unbind("click").bind("click",function(){
                    $.check_all(this);
                });
                $("button,a").bind("focus",function(){
                    $(this).blur();
                });
                //单击已读
                $(".click-d").click(function () {
                    var infor_id = $(this).data("infor");
                    $.ajaxPassport({
                        url:"<?php echo U("ucenter/profile_msg_batch");?>",
                        success:function (res) {
                            if (res.error == 0) {
                                $.ui.success("已读");
                                load_information_list(1);
                            }else{
                                return false;
                            }
                        },
                        data:{id:infor_id,do:"mark"}
                    });
                });
            },
            data:{page:page,keyword:keyword,condition:condition},
        });
    }
    //批量操作
    var batch_op = {
        url: "<?php echo U("ucenter/profile_msg_batch");?>",
        data:{},
    };
    var ids= new Array();
    var batch_infromation_op = function(obj) {
        var infor_do = $(obj).data("do");
        var ids_tmp = $.fetch_ids("inforId[]");
        ids = ids_tmp.split(",");
        if (ids == "") {
            $.ui.error('请选择要操作的信息！')
            return;
        }
        var infor_confirm = "";
        if (infor_do == "mark") {
            infor_confirm = "你确定要标记为已读吗？";
            batch_op.data.do = infor_do;
        }else if (infor_do == "del") {
            infor_confirm = "你确定要删除通知吗？";
            batch_op.data.do = infor_do;
        }
        $.ui.confirm(function(){
            batch_infor_detial_op(0);
            load_information_list(1);
        },infor_confirm) ;
    }
    var num = 0;
    var batch_infor_detial_op = function(i){
        batch_op.data.id=ids[i];
        if( i>=ids.length || typeof ids[i] == "undefined"){
            num = 0;
            return false;
        }
        $.ui.success("操作中..");
        $.ajaxPassport({
            url: batch_op.url,
            success: function (res) {
                if (res.error == 0) {
                    num++;
                    var informationId = ".information_"+ids[i];
                    if (batch_op.data.do == 'del') {
                        setTimeout(function () {
                            $(informationId).remove();
                        }, 50);
                    }else{
                        //改变状态为已读信息
                        $(informationId).find("i").remove();
                        $(informationId).attr("style","color:gray");
                    }
                }
                //执行最后一次时提示操作结果
                if (i == (ids.length - 1)) {
                    if (num >0) {
                        $.ui.success("成功操作"+num+"条信息");
                        load_information_list(1);
                    }else{
                        $.ui.error(res.message);
                    }
                }
                $('#my-confirm').modal("close");
                batch_infor_detial_op(i+1);
            },
            data: batch_op.data,
        });
    }
</script>
</body>
</html>