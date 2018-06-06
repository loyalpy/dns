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
<div class="combox">
    <div class="records-nav">
        <ul>
            <li class="domain"><a href="<?php echo U("/domains/mylist");?>"><i class="am-icon-globe"></i></a> <span><?php if($domain['is_cn']==1){?><?php echo isset($domain['domain_cn'])?$domain['domain_cn']:"";?><?php }else{?><?php echo isset($domain['domain'])?$domain['domain']:"";?><?php }?></span></li>
            <li class="cur"><a href="<?php echo U("/records/records_setline?domain=");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>">自定义线路</a></li>
            <li><a href="<?php echo U("/records/records_set?domain=");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>">域名设置</a></li>
            <li><a href="<?php echo U("/records/records_count?domain=");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>">解析量统计</a></li>
            <li><a href="<?php echo U("/domains/dns/");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>">记录设置</a></li>
        </ul>
    </div>
    <div class="dis20"></div>
    <div id="setline-s"></div>
    <div id="setline-a"></div>
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
<script type="text/javascript">
$(function(){
    show_set_line(1);
});
</script>
<!--自定义线路列表 start-->
<script type="text/template" id="tpl_records_setline">
    <#macro rowedit data>
        <div class="setline-list">
            <div class="list-up-l">
               自定义线路<span>(${data.list.length})</span>
            </div>

            <div class="list-up-r">
                <button type="button" class="am-btn am-btn-success am-btn-sm am-radius add-line"><span class="am-icon-plus"></span> 添加</button>
            </div>
            <div class="cl"></div>
        </div>
        <div class="dis10"></div>
        <div class="setline-list">
            <table class="am-table">
                <col width="50px"/>
                <col width="120px" />
                <col />
                <col width="120px"/>
                <thead>
                <tr>
                    <th><input type="checkbox" data-name="lineId[]" class="checkall"/></th>
                    <th>线路名称</th>
                    <th>IP段</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <#if (data.list.length>0)>
                    <#list data.list as line>
                        <tr>
                            <td><input type="checkbox" name="lineId[]" value=""/></td>
                            <td>
                                ${line.name}
                                <#if (line.status == 2)><font class="am-text-danger" title="线路已锁定，待审核" style="cursor: pointer">&nbsp;&nbsp;<i class="am-icon-lock"></i></font></#if>
                            </td>
                            <td class="line-gray">
                                <span>${line.ipaddr}</span><br/>
                            </td>
                            <td>
                                <span class="edit-line" data-id="${line.id}"><i class="am-icon-edit" title="编辑线路"></i></span>
                                <span class="del-line" data-id="${line.id}"><i class="am-icon-close" title="删除线路"></i></span>
                            </td>
                        </tr>
                    </#list>
                    <#else>
                        <tr><td colspan="4"><i class="am-icon-exclamation-triangle" style="color: orange"></i> 暂无自定义线路，立即 <a href="javascript:;" class="add-line">添加</a></td></tr>
                </#if>
                </tbody>
            </table>
            <div class="pagebar">${data.pagebar}</div>
        </div>
    </#macro>
</script>
<script type="text/javascript">
    var global_id = 0;
    var show_set_line = function (page) {
        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/api/Domain.SetLineList");?>",
            success:function (res) {
                $.ui.close_loading();
                if (res.status==1) {
                    var edit_c = "" + easyTemplate($("#tpl_records_setline").html(),res.data);
                    $("#setline-s").html(edit_c);
                    //全选，全不选
                    $("#setline-s").find("input.checkall").unbind("click").bind("click",function(){
                        $.check_all(this);
                    });
                    $(".add-line").click(function () {
                        add_set_line_op();
                    });
                    //删除自定义IP,global_id设置全局变量
                    $(".del-line").click(function () {
                        global_id = $(this).data("id");
                        $.ui.confirm(function () {
                            $.ajaxPassport({
                                url:"<?php echo U("api/Domain.DelSetLine");?>",
                                success:function (res) {
                                    if (res.status == 1) {
                                        $.ui.success(res.msg);
                                        $.exeJS("<?php echo U("/domains/refresh_line?domain=");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>&acl="+global_id);
                                        show_set_line(1);
                                    }else{
                                        $.ui.error(res.msg);
                                    }
                                },
                                data:{id:global_id}
                            });
                        },"你确定要删除自定义IP吗？");
                    });
                    //编辑自定义线路IP
                    $(".edit-line").click(function () {
                        var id = $(this).data("id");
                        $.ajaxPassport({
                            url:"<?php echo U("api/Domain.getRowLineById");?>",
                            success:function (res) {
                                if (res.status == 1) {
                                    res.data.do = 'edit';
                                    var html = "" + easyTemplate($("#tpl_add_setline").html(),res.data);
                                    $("#setline-a").html(html);
                                    $("#setline-a").find('#doc-modal-1-t').modal({width: 750});

                                    $(".set-diyline").click(function () {
                                        var domain = "<?php echo isset($domain['domain'])?$domain['domain']:"";?>";
                                        var name = $(this).parent().find("input[name='name']").val();
                                        var ips = $(this).parent().find("textarea[name='ips']").val();
                                        if ($.is_empty(name)) {
                                            $.ui.error("线路名称不能为空！");
                                            return false;
                                        }
                                        if (name.length>10) {
                                            $.ui.error("线路名称不能大于10个字符！");
                                            return false;
                                        }
                                        var z= /^[0-9A-Za-z\u4e00-\u9fa5]{1,14}$/;
                                        if(!z.test(name)){
                                            $.ui.error("线路名称不符合规则！");
                                            return false;
                                        }
                                        if ($.is_empty(ips)) {
                                            $.ui.error("IP段不能为空！");
                                            return false;
                                        }
                                        $.ui.loading($("#doc-modal-1-t"));
                                        $.ajaxPassport({
                                            url:"<?php echo U("api/Domain.AddSetLine");?>",
                                            success:function (res) {
                                                $.ui.close_loading($("#doc-modal-1-t"));
                                                if (res.status == 1) {
                                                    $.ui.success(res.msg);
                                                    $.exeJS("<?php echo U("/domains/refresh_line?domain=");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>&acl="+res.data.id);
                                                    setTimeout(function () {
                                                        $("#setline-a").find('#doc-modal-1-t').modal("close");
                                                        show_set_line(1);
                                                    },500);
                                                }else{
                                                    $.ui.error(res.msg);
                                                }
                                            },
                                            data:{domain:domain,name:name,ips:ips,id:id}
                                        });
                                    });
                                }else{
                                    $.ui.error(res.msg);
                                }
                            },
                            data:{id:id}
                        });
                    });
                }else{
                    //企业套餐专用提示
                    var htmlstr = "<span style='font-size:14px ;margin-left: 20px;'>"+res.msg+"，<a href='<?php echo U("home@/product/buy?type=company");?>' target='_blank'>立即升级</a></span>";
                    var edit_c = "" + easyTemplate(htmlstr);
                    $("#setline-s").html(edit_c);
                }
            },
            data:{page:page,domain:"<?php echo isset($domain['domain'])?$domain['domain']:"";?>"}
        });
    }
</script>
<!--自定义线路列表 end-->

<!--自定义线路添加 start-->
<script type="text/template" id="tpl_add_setline">
    <#macro rowedit data>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1-t">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" style="border-bottom: 1px solid silver;text-align: left;color:black;padding-bottom: 16px;">
                    <i class="am-icon-edit" style="color: #EB8500;"></i>&nbsp;&nbsp;自定义线路添加&nbsp;&nbsp;
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                </div>
                <div class="dis10"></div>
                <div class="am-modal-bd" style="font-size: 14px;text-align: left;padding: 30px 30px 60px 30px;text-align: center;">
                    <span style="font-weight: 600;font-size: 16px">线路名称：</span>
                    <input type="text" class="am-form-field am-radius am-input-sm" name="name" value="<#if (data.do=='edit')>${data.name}</#if>" placeholder="如：杭州移动或者hz_yidong" style="width: 400px;display: inline-block;height: 32px"/><br/>
                    <small style="color: gray;margin-left: -20px">可以是字母、数字、汉字的组合(长度不能大于10个字符)</small><br/>
                    <div class="dis15"></div>
                    <span style="font-weight: 600;font-size: 16px;margin-left: 33px;position: relative;top: -57px;">IP段：</span>
                    <textarea class="am-form-field am-radius" rows="10" name="ips" placeholder="如：1.1.1.1/24\n2.2.2.2-3.3.3.3\n4.4.4.4-6.6.6.6" style="width: 400px;display: inline-block;font-size: 14px"><#if (data.do=='edit')>${data.ipaddr.replace(/;/ig,'\n')}</#if></textarea><br/>
                    <small style="color: gray;margin-left: 52px;">1，IP段用"-"分割，如(1.1.1.1-2.2.2.2).或者CIRD方式，如(1.120.0.0/28)</small><br/>
                    <small style="color: gray;margin-left: -214px;">2，每行一个IP段。</small><br/>
                    <small style="color: red;margin-left: -133px;">3，多个IP段之间不允许有IP交叉。</small>
                    <div class="dis30"></div>
                    <input type="hidden" name="domain" value="<#if (data.do=='edit')>${data.domain}</#if>"/>
                    <button type="button" class="am-btn am-btn-secondary set-diyline">确定</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="am-btn am-btn-default" data-am-modal-close>返回</button>
                </div>
            </div>
        </div>
    </#macro>
</script>
<script language="javascript">
    var add_set_line_op = function () {

        var html = "" + easyTemplate($("#tpl_add_setline").html(),{do:'add'});
        $("#setline-a").html(html);
        $("#setline-a").find('#doc-modal-1-t').modal({width: 750});

        $(".set-diyline").click(function () {
            var domain = "<?php echo isset($domain['domain'])?$domain['domain']:"";?>";
            var name = $(this).parent().find("input[name='name']").val();
            var ips = $(this).parent().find("textarea[name='ips']").val();
            if ($.is_empty(name)) {
                $.ui.error("线路名称不能为空！");
                return false;
            }
            if (name.length>10) {
                $.ui.error("线路名称不能大于10个字符！");
                return false;
            }
            var z= /^[0-9A-Za-z\u4e00-\u9fa5]{1,14}$/;
            if(!z.test(name)){
                $.ui.error("线路名称不符合规则！");
                return false;
            }
            if ($.is_empty(ips)) {
                $.ui.error("IP段不能为空！");
                return false;
            }
            //添加自定义线路
            $.ui.loading($("#doc-modal-1-t"));
            $.ajaxPassport({
                url:"<?php echo U("api/Domain.AddSetLine");?>",
                success:function (res) {
                    $.ui.close_loading($("#doc-modal-1-t"));
                    if (res.status == 1) {
                        $.ui.success(res.msg);
                        $.exeJS("<?php echo U("/domains/refresh_line?domain=");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>&acl="+res.data.id);
                        setTimeout(function () {
                            $("#setline-a").find('#doc-modal-1-t').modal("close");
                            show_set_line(1);
                        },500);
                    }else{
                        $.ui.error(res.msg);
                    }
                },
                data:{name:name,ips:ips,domain:domain}
            });
        });
    }
</script>
<!--自定义线路添加 end-->
</body>
</html>