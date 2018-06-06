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
<div class="am-uc-top">
    <i class="am-icon-wordpress"></i>&nbsp;
    <?php echo isset($domain)?$domain:"";?>
</div>
<div class="am-uc-left">
    <div class="leftnav" id="Leftnav" style="padding-top: 20px">
        <ul>
            <li><a href="<?php echo U("/ucenter/basic?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype ">基本信息&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_edit?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype cur">域名信息修改&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_rz?domain=");?><?php echo isset($domain)?$domain:"";?>"  class="showtype">域名实名认证&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_transfer?domain=");?><?php echo isset($domain)?$domain:"";?>"  class="showtype">域名过户&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_dns?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype ">DNS修改创建&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_zs?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype">域名证书&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_status?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype">安全设置&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>

<div class="am-uc-right" style="padding-top: 25px">
    <div class="am-alert am-alert-secondary basic-t-t" data-am-alert>
        <button type="button" class="am-close">&times;</button>
        <p>域名所有者与管理联系人地址，邮编，邮箱相同。</p>
    </div>
    <!--<div class="basic-top">&nbsp;&nbsp;&nbsp;域名信息修改</div>-->
    <!--<div class="dis10"></div>-->
    <div class="records-nav profile_information" >
        <ul>
            <li class="<?php if($type == 1){?>cur<?php }?>"><a href="<?php echo U("/ucenter/basic_edit?domain=");?><?php echo isset($domain)?$domain:"";?>&type=1">域名所有者信息修改</a></li>
            <li class="<?php if($type == 2){?>cur<?php }?>"><a href="<?php echo U("/ucenter/basic_edit?domain=");?><?php echo isset($domain)?$domain:"";?>&type=2">管理联系人信息修改</a></li>
        </ul>
    </div>
    <div class="dis10"></div>

    <table class="am-table basic-edit-table">
        <col width="200px"/>
        <col />
        <thead>
        </thead>
        <tbody>
        <tr>
            <td class="l"><?php if($type == 1){?>域名所有者<?php }else{?>管理联系人<?php }?>:<span>*</span></td>
            <td>
                <?php if($type == 1){?>
                <?php echo isset($list_info['aller_name_cn'])?$list_info['aller_name_cn']:"";?>
                <?php }else{?>
                <input type="text" name="aller_name_cn" value="<?php echo isset($list_info['m_name_cn'])?$list_info['m_name_cn']:"";?>" class="am-form-field am-radius i"  placeholder="中文名"/>
                <i class="t">请填入32个汉字以内正确的中文名</i>
                <?php }?>
            </td>
        </tr>
        <tr>
            <td class="l"><?php if($type == 1){?>域名所有者(英文)<?php }else{?>管理联系人(英文)<?php }?>:<span>*</span></td>
            <td>
                <?php if($type == 1){?>
                <?php echo isset($list_info['aller_name'])?$list_info['aller_name']:"";?>
                <?php }else{?>
                <input type="text" name="aller_name" value="<?php echo isset($list_info['m_name'])?$list_info['m_name']:"";?>" class="am-form-field am-radius i"  placeholder="英文名">
                <i class="t">请填写不少于两个字符的英文名称</i>
                <?php }?>
            </td>
        </tr>
        <tr>
            <td class="l">邮箱:<span>*</span></td>
            <td>
                <input type="text" name="email" value="<?php if($type == 1){?><?php echo isset($list_info['email'])?$list_info['email']:"";?><?php }else{?><?php echo isset($list_info['m_email'])?$list_info['m_email']:"";?><?php }?>" class="am-form-field am-radius i"  placeholder="Email">
                <i class="t">请输入正确的电子邮件</i>
            </td>
        </tr>
        <tr>
            <td class="l">地区:<span>*</span></td>
            <td>
                <div id="city" class="">
                    <select class="i1 am-form-field am-radius "><option>中国</option></select>
                    <select class="i1 am-form-field am-radius prov" name="s_province"></select>
                    <select class="i1 am-form-field am-radius city" name="s_city"></select>
                </div>
            </td>
        </tr>
        <tr>
            <td class="l">通讯地址:<span>*</span></td>
            <td>
                <input type="text" name="addr_cn" value="<?php if($type == 1){?><?php echo isset($list_info['addr_cn'])?$list_info['addr_cn']:"";?><?php }else{?><?php echo isset($list_info['m_addr_cn'])?$list_info['m_addr_cn']:"";?><?php }?>" class="am-form-field am-radius i"  placeholder="中文地址">
                <i class="t">请输入中文联系地址,必填字段</i>
            </td>
        </tr>
        <tr>
            <td class="l">通讯地址(英文):<span>*</span></td>
            <td>
                <input type="text" name="addr" value="<?php if($type == 1){?><?php echo isset($list_info['addr'])?$list_info['addr']:"";?><?php }else{?><?php echo isset($list_info['m_addr'])?$list_info['m_addr']:"";?><?php }?>" class="am-form-field am-radius i"  placeholder="英文地址">
                <i class="t">请输入英文联系地址,必填字段</i>
            </td>
        </tr>
        <tr>
            <td class="l">邮编:<span>*</span></td>
            <td>
                <input type="text" name="ub" value="<?php if($type == 1){?><?php echo isset($list_info['ub'])?$list_info['ub']:"";?><?php }else{?><?php echo isset($list_info['m_ub'])?$list_info['m_ub']:"";?><?php }?>" class="am-form-field am-radius i" >
                <i class="t">请输入6位数字邮编</i>
            </td>
        </tr>
        <tr>
            <td class="l">手机:<span>*</span></td>
            <td>
                <input type="text" name="mobile" value="<?php if($type == 1){?><?php echo isset($list_info['mobile'])?$list_info['mobile']:"";?><?php }else{?><?php echo isset($list_info['m_mobile'])?$list_info['m_mobile']:"";?><?php }?>" class="am-form-field am-radius i" >
                <i class="t">请正确填写您的手机号码</i>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="add-c-sub">
        <button type="button" class="am-btn am-btn-warning <?php if($type == 1){?>edit-aller<?php }else{?>edit-m<?php }?>">提交</button>
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
<script type="text/javascript" src="<?php echo U("/static/javascript/area/jquery.cityselect.js");?>"></script>
<script type="text/javascript">
    $(function () {
        //域名所有者联系人
        $(".edit-aller").click(function () {
            sub_add_info(1);
        });
        //管理所有者联系人
        $(".edit-m").click(function () {
            sub_add_info(2);
        });
        //域名所有者中文判断
        $("input[name='aller_name_cn'],input[name='name_cn']").keyup(function () {
            var pattern = /^[\u4E00-\u9FA5]{2,32}$/;
            pan_is_show(this,pattern);
        });
        //域名所有者英文判断
        $("input[name='aller_name'],input[name='name']").keyup(function () {
            var pattern=/^[a-zA-Z\s]{2,32}$/g;
            pan_is_show(this,pattern);
        });
        //邮箱判断
        $("input[name='email']").keyup(function () {
            var pattern =  /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
            pan_is_show(this,pattern);
        });
        //匹配中文通讯地址
        $("input[name='addr_cn']").keyup(function () {
            var pattern = /^[\u4E00-\u9FA5(0-9)\s]{2,64}$/;
            pan_is_show(this,pattern);
        });
        //匹配英文通讯地址
        $("input[name='addr']").keyup(function () {
            var pattern=/^[0-9a-zA-Z\s]{2,64}$/g;
            pan_is_show(this,pattern);
        });
        //手机判断
        $("input[name='mobile']").keyup(function () {
            var pattern = /^1[34578]\d{9}$/;
            pan_is_show(this,pattern);
        });
        //邮编判断
        $("input[name='ub']").keyup(function () {
            var pattern = /^[0-9][0-9]{5}$/;
            pan_is_show(this,pattern);
        });
    });
    //判断是否显示错误
    var pan_is_show = function (obj,pattern) {
        var v = $(obj).val();
        if (!pattern.test(v)) {
            $(obj).parent().find("i.t").show();
        }else{
            $(obj).parent().find("i.t").hide();
        }
    }
    //提交修改
    var sub_add_info = function (type) {
        var num = 0;
        $(".basic-edit-table td input").each(function () {
            if (!$(this).parent().find("i.t").is(":hidden") || $.is_empty($(this).val())) {
                num++;
                $(this).parent().find("i.t").show();
            }
        });
        if (num > 0) {
            return false;
        }
        var aller_name_cn = $("input[name='aller_name_cn']").val();
        var aller_name = $("input[name='aller_name']").val();
        var email = $("input[name='email']").val();

        var s_province = $("select[name='s_province']").val();
        var s_city = $("select[name='s_city']").val();

        var addr_cn = $("input[name='addr_cn']").val();
        var addr = $("input[name='addr']").val();
        var ub = $("input[name='ub']").val();
        var mobile = $("input[name='mobile']").val();
        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/ucenter/basic_edit_sub");?>",
            success:function (res) {
                $.ui.close_loading();
                if (res.error == 1) {
                    $.ui.error(res.message);
                }else{
                    $.ui.success(res.message);
                    setTimeout(function () {
                        //window.location.replace("<?php echo U("/cart/cart_pay");?>");
                    },500);
                }
            },
            data:{type:type,aller_name_cn:aller_name_cn,aller_name:aller_name,email:email,s_province:s_province,s_city:s_city,addr_cn:addr_cn,addr:addr,ub:ub,mobile:mobile,domain:"<?php echo isset($domain)?$domain:"";?>"}
        });
    }
</script>
<!--三级地区联动-->
<script type="text/javascript">

    <?php if($type == 1){?>
    var prov = $.is_empty('<?php echo isset($list_info['r_province_cn'])?$list_info['r_province_cn']:"";?>')?"浙江省":"<?php echo isset($list_info['r_province_cn'])?$list_info['r_province_cn']:"";?>";
    var city = $.is_empty('<?php echo isset($list_info['r_city_cn'])?$list_info['r_city_cn']:"";?>')?"杭州":"<?php echo isset($list_info['r_city_cn'])?$list_info['r_city_cn']:"";?>";
    <?php }else{?>
    var prov = $.is_empty('<?php echo isset($list_info['a_province_cn'])?$list_info['a_province_cn']:"";?>')?"浙江省":"<?php echo isset($list_info['a_province_cn'])?$list_info['a_province_cn']:"";?>";
    var city = $.is_empty('<?php echo isset($list_info['a_city_cn'])?$list_info['a_city_cn']:"";?>')?"杭州":"<?php echo isset($list_info['a_city_cn'])?$list_info['a_city_cn']:"";?>";
    <?php }?>

    $("#city").citySelect({
        url:"<?php echo U("/static/javascript/area/city.min.js");?>",
        prov:prov, //省份
        city:city, //城市
        nodata:"none" //当子集无数据时，隐藏select
    });
</script>
</body>
</html>