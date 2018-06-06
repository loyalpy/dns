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
        <h1><span class="list_tit_name"><?php if($do == 'edit'){?>编辑模板<?php }else{?>创建模板<?php }?></span></h1>
    </div>

    <div class="dis10"></div>
    <table class="am-table basic-edit-table template-info-table">
        <col width="200px"/>
        <col />
        <thead>
        </thead>
        <tbody>
        <tr>
            <td class="l">用户类型:</td>
            <td>
                <?php if(isset($info_row['utype'])){?>
                    <?php if($info_row['utype'] == 1){?>个人<?php }else{?>企业<?php }?>
                    <input class="com" type="radio" name="utype" value="<?php echo isset($info_row['utype'])?$info_row['utype']:"";?>" checked style="display: none;"/>
                <?php }else{?>
                <label class="radio-inline">
                    <input class="per" type="radio" name="utype" value="1" checked/> 个人用户
                </label>
                <label class="radio-inline">
                    <input class="com" type="radio" name="utype" value="2"/> 企业用户
                </label>
                <?php }?>
            </td>
        </tr>
        <tr>
            <td class="l">模板名称:<span>*</span></td>
            <td>
                <input type="text" name="tpl_name" value="<?php if(isset($info_row['tpl_name'])){?><?php echo isset($info_row['tpl_name'])?$info_row['tpl_name']:"";?><?php }?>" class="am-form-field am-radius i"  placeholder="模板名称"/>
                <i class="t">至少填写2个字符</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        <tr>
            <td class="l">域名所有者:<span>*</span></td>
            <td>
                <input type="text" name="aller_name_cn" <?php if(isset($info_row['aller_name_cn'])){?>disabled<?php }?> value="<?php if(isset($info_row['aller_name_cn'])){?><?php echo isset($info_row['aller_name_cn'])?$info_row['aller_name_cn']:"";?><?php }?>" class="am-form-field am-radius i"  placeholder="中文名"/>
                <i class="t">请填入32个汉字以内正确的中文名</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        <tr>
            <td class="l">域名所有者(英文)<span>*</span></td>
            <td>
                <input type="text" name="aller_name"  <?php if(isset($info_row['aller_name'])){?>disabled<?php }?> class="am-form-field am-radius i"  value="<?php if(isset($info_row['aller_name'])){?><?php echo isset($info_row['aller_name'])?$info_row['aller_name']:"";?><?php }?>" placeholder="英文名">
                <i class="t">请填写不少于两个字符的英文名称</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        <?php if(isset($info_row['is_use'])){?>
        <tr>
            <td class="l list-tips-1">资料认证状态<span>*</span></td>
            <td>
                <?php if($info_row['is_use'] == 0){?><span style="color:orange;">未提交实名资料</span>
                <?php }elseif($info_row['is_use'] == 1){?><span style="color:red;">审核不通过</span>
                <?php }elseif($info_row['is_use'] == 2){?><span style="color: green">审核通过</span>
                <?php }elseif($info_row['is_use'] == 3){?><span style="color:green;">实名资料审核中</span>
                <?php }elseif($info_row['is_use'] == 4){?><span style="color:red;">实名资料上传失败</span>
                <?php }else{?>
                <?php }?>
            </td>
        </tr>
        <?php }?>
        <tr <?php if(isset($info_row['is_use'])){?><?php if($info_row['is_use'] == 2 || $info_row['is_use'] == 3){?>style="display:none;"<?php }?><?php }?>>
            <td class="l list-tips-1">
                <?php if(isset($info_row['utype'])){?>
                <?php if($info_row['utype'] == 1){?>域名所有人身份证号码<?php }else{?>营业执照或组织机构代码<?php }?>
                <?php }else{?>
                域名所有人身份证号码
                <?php }?><span>*</span>
            </td>
            <td>
                <input type="text" name="cart" class="am-form-field am-radius i"  value="<?php if(isset($info_row['cart'])){?><?php echo isset($info_row['cart'])?$info_row['cart']:"";?><?php }?>">
                <i class="t">请填写正确的证件号码</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        <tr <?php if(isset($info_row['is_use'])){?><?php if($info_row['is_use'] == 2 || $info_row['is_use'] == 3){?>style="display:none;"<?php }?><?php }?>>
            <td class="l list-tips-2">
                <?php if(isset($info_row['utype'])){?>
                <?php if($info_row['utype'] == 1){?>  域名所有人身份证扫描件<?php }else{?>营业执照或组织机构代码证<?php }?>
                <?php }else{?>
                域名所有人身份证扫描件
                <?php }?>
                <span>*</span>
            </td>
            <td>
                <div class="am-form-group am-form-file">
                    <form action="<?php echo U("/ucenter/upload_cart");?>" class="uploadform" method="post" enctype="multipart/form-data">
                        <button type="button" class="am-btn am-btn-default am-btn-sm am-btn-uploadzj">
                            <i class="am-icon-cloud-upload"></i> 上传证件扫描件</button>
                        <input id="doc-form-file" type="file" name="attach_file" value="attach_file">
                        <input type="hidden" name="hash" value="<?php echo tUtil::hash();?>" />
                        <input type="hidden" name="tpl_id" value="" />
                        <input type="hidden" name="id" value="<?php if(isset($info_row['id'])){?><?php echo isset($info_row['id'])?$info_row['id']:"";?><?php }?>" />
                    </form>
                </div>

                <div id="file-list">
                    <img src="<?php echo U("static@$imgurl");?>" width="200" id="Dcompany_zj" <?php if($imgurl){?><?php }else{?>style="display:none"<?php }?> />
                </div>
                <a href="javascript:;" class="overbtn" <?php if($imgurl){?><?php }else{?>style="display: none;"<?php }?>>查看原图</a>
            </td>
        </tr>
        <tr>
            <td class="l">联系人<span>*</span></td>
            <td>
                <input type="text" name="name_cn" class="am-form-field am-radius i"  value="<?php if(isset($info_row['name_cn'])){?><?php echo isset($info_row['name_cn'])?$info_row['name_cn']:"";?><?php }?>" placeholder="中文名">
                <i class="t">请填入32个汉字以内正确的中文名</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        <tr>
            <td class="l">联系人(英文)<span>*</span></td>
            <td>
                <input type="text" name="name" value="<?php if(isset($info_row['name'])){?><?php echo isset($info_row['name'])?$info_row['name']:"";?><?php }?>" class="am-form-field am-radius i"  placeholder="英文名">
                <i class="t">域名联系人(英文名)为必填字段</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        <tr>
            <td class="l">邮箱:<span>*</span></td>
            <td>
                <input type="text" name="email" value="<?php if(isset($info_row['email'])){?><?php echo isset($info_row['email'])?$info_row['email']:"";?><?php }?>" class="am-form-field am-radius i"  placeholder="Email">
                <i class="t">请输入正确的电子邮件</i>
                <i class="am-icon-check f"></i>
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
                <input type="text" name="addr_cn" value="<?php if(isset($info_row['addr_cn'])){?><?php echo isset($info_row['addr_cn'])?$info_row['addr_cn']:"";?><?php }?>" class="am-form-field am-radius i"  placeholder="中文地址">
                <i class="t">请输入中文联系地址,必填字段</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        <tr>
            <td class="l">通讯地址(英文):<span>*</span></td>
            <td>
                <input type="text" name="addr" value="<?php if(isset($info_row['addr'])){?><?php echo isset($info_row['addr'])?$info_row['addr']:"";?><?php }?>" class="am-form-field am-radius i"  placeholder="英文地址">
                <i class="t">请输入英文联系地址,必填字段</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        <tr>
            <td class="l">邮编:<span>*</span></td>
            <td>
                <input type="text" name="ub" value="<?php if(isset($info_row['ub'])){?><?php echo isset($info_row['ub'])?$info_row['ub']:"";?><?php }?>" class="am-form-field am-radius i" >
                <i class="t">请输入6位数字邮编</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        <tr>
            <td class="l">手机:<span>*</span></td>
            <td>
                <input type="text" name="mobile" value="<?php if(isset($info_row['mobile'])){?><?php echo isset($info_row['mobile'])?$info_row['mobile']:"";?><?php }?>" class="am-form-field am-radius i" >
                <i class="t">请正确填写您的手机号码</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        <tr>
            <td class="l">传真<span>*</span></td>
            <td>
                <input type="text" name="cz1" class="am-form-field am-radius i" style="width: 60px;display: inline-block;" value="086"> - <input type="text" name="cz2" class="am-form-field am-radius i" value="<?php if(isset($info_row['cz'][1])){?><?php echo isset($info_row['cz'][1])?$info_row['cz'][1]:"";?><?php }?>" style="width: 60px;display: inline-block;"> - <input type="text" name="cz3" value="<?php if(isset($info_row['cz'][2])){?><?php echo isset($info_row['cz'][2])?$info_row['cz'][2]:"";?><?php }?>" class="am-form-field am-radius i"  style="width: 130px;display: inline-block;" >
                <i class="t">请输入正确数字号码，国家区号3位,区号4位,号码最多8位</i>
                <i class="am-icon-check f"></i>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="add-c-sub template-info-sub">
        <input type="hidden" name="id" value="<?php if(isset($info_row['id'])){?><?php echo isset($info_row['id'])?$info_row['id']:"";?><?php }?>">
        <button type="button" class="am-btn am-btn-warning add-sub">提交</button>
        <a href="<?php echo U("/ucenter/template");?>" type="button" class="am-btn am-btn-default" style="position: relative;left: 25px;top:9px;">返回</a>
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
        //查看原图，缩略图
        var isopen = false;
        $(".overbtn").bind("click", function(){
            var obj = this;
            if (!isopen) {
                var img_obj = $(obj).parent().parent().find("img");
                img_obj.attr("src",img_obj.attr("over"));
                img_obj.attr("width","100%");
                $(obj).html("查看缩略图");
                isopen = true;
            } else {
                var img_obj = $(obj).parent().parent().find("img");
                img_obj.attr("src",img_obj.attr("over"));
                img_obj.attr("width","200px");
                $(obj).html("查看原图");
                isopen = false;
            }
        });
        //模板名称判断
        $("input[name='tpl_name'],input[name='tpl_name']").keyup(function () {
            var pattern = /^[\u4E00-\u9FA50-9A-Za-z]{2,32}$/;
            pan_is_show(this,pattern);
        });
        //域名所有者中文判断
        $("input[name='aller_name_cn'],input[name='name_cn']").keyup(function () {
            var pattern = /^[\u4E00-\u9FA5.,-]{2,64}$/;
            pan_is_show(this,pattern);
        });
        //域名所有者英文判断
        $("input[name='aller_name'],input[name='name']").keyup(function () {
            var pattern=/^[a-zA-Z\s.,-]{2,64}$/g;
            pan_is_show(this,pattern);
        });
//        //证件判断
//        $("input[name='cart'],input[name='cart']").keyup(function () {
//            var pattern=/^[0-9]{8,32}$/g;
//            pan_is_show(this,pattern);
//        });
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
        //传真判断
        $("input[name='cz1']").keyup(function () {
            var pattern = /^[0-9]{3}$/;
            pan_is_show(this,pattern);
        });
        //传真判断
        $("input[name='cz2']").keyup(function () {
            var pattern = /^[0-9]{4}$/;
            pan_is_show(this,pattern);
        });
        //传真判断
        $("input[name='cz3']").keyup(function () {
            var pattern = /^[0-9]{7,8}$/;
            pan_is_show(this,pattern);
        });
        //提交并支付无模板
        $(".add-sub").unbind("click").bind("click",function () {
            sub_add_info();
        });
        $(".per").click(function () {
            $(".list-tips-1").html("域名所有人身份证号码");
            $(".list-tips-2").html("域名所有人身份证扫描件");
        });
        $(".com").click(function () {
            $(".list-tips-1").html("营业执照或组织机构代码");
            $(".list-tips-2").html("营业执照或组织机构代码证");
        });
        //上传认证附件
        $("form.uploadform").data("success",function(res,formobj){
            if(res.error == 1){
                $.ui.error(res.message);
            }else{
                var imgobj = $("#Dcompany_zj");
                imgobj.show();
                imgobj.attr("src",res.path+"?"+Math.random());
                $(".overbtn").show();
                $("input[name='tpl_id']").val(res.tpl_id);
            }
            $.ui.close_loading($("#Dcompany_zj").parent());
        }).submit(function(){
            $.ui.loading($("#Dcompany_zj").parent());
            return $.ajaxForm(this,100);
        }).find("input[type='file']").unbind("change").bind("change",function(){
            var obj = this;
            $(obj).parent().submit();
        });
    });
    //判断是否显示错误
    var pan_is_show = function (obj,pattern) {
        var v = $(obj).val();
        if (!pattern.test(v)) {
            $(obj).parent().find("i.t").show();
            $(obj).parent().find("i.f").hide();
        }else{
            $(obj).parent().find("i.t").hide();
            $(obj).parent().find("i.f").show();
        }
    }
    //提交资料
    var sub_add_info = function () {
        var num = 0;
        $(".template-info-table td input").each(function () {
            var c_name = $(this).attr("name")
            if (c_name != "cart" && c_name != "attach_file" && c_name != "hash" && c_name != "id" && c_name != "tpl_id") {
                if ((!$(this).parent().find("i.t").is(":hidden") && $(this).val() != 1 && $(this).val() != 2) || $.is_empty($(this).val())) {
                    num++;
                    $(this).parent().find("i.t").show();
                }
            }
        });
        if (num > 0) {
            return false;
        }
        var utype = $("input[name='utype']:checked").val();
        var tpl_name = $("input[name='tpl_name']").val();
        var aller_name_cn = $("input[name='aller_name_cn']").val();
        var aller_name = $("input[name='aller_name']").val();
        var name_cn = $("input[name='name_cn']").val();
        var name = $("input[name='name']").val();
        var email = $("input[name='email']").val();

        var s_province = $("select[name='s_province']").val();
        var s_city = $("select[name='s_city']").val();

        var addr_cn = $("input[name='addr_cn']").val();
        var addr = $("input[name='addr']").val();
        var ub = $("input[name='ub']").val();
        var mobile = $("input[name='mobile']").val();
        var cart = $("input[name='cart']").val();
        var tpl_id = $("input[name='tpl_id']").val();

        var cz1 = $("input[name='cz1']").val();
        var cz2 = $("input[name='cz2']").val();
        var cz3 = $("input[name='cz3']").val();

        var id = $("input[name='id']").val();
        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/ucenter/template_info");?>",
            type:"POST",
            success:function (res) {
                $.ui.close_loading();
                if (res.error == 1) {
                    $.ui.error(res.message);
                }else{
                    $.ui.success("保存成功!");
                    if ($.is_empty(id)) {
                        $(".template-info-sub").find("button.add-sub").attr("disabled","disabled");
                        setTimeout(function () {
                            window.location.replace("<?php echo U("/ucenter/template");?>");
                        },500);
                    }
                }
            },
            data:{id:id,utype:utype,cart:cart,tpl_id:tpl_id,tpl_name:tpl_name,aller_name_cn:aller_name_cn,aller_name:aller_name,name_cn:name_cn,name:name,email:email,s_province:s_province,s_city:s_city,addr_cn:addr_cn,addr:addr,ub:ub,mobile:mobile,cz1:cz1,cz2:cz2,cz3:cz3,hash:"<?php echo tUtil::hash();?>"}
        });
    }
</script>
<!--三级地区联动-->
<script type="text/javascript">

    var prov = $.is_empty('<?php echo isset($info_row['area'][0])?$info_row['area'][0]:"";?>')?"浙江省":"<?php echo isset($info_row['area'][0])?$info_row['area'][0]:"";?>";
    var city = $.is_empty('<?php echo isset($info_row['area'][1])?$info_row['area'][1]:"";?>')?"杭州":"<?php echo isset($info_row['area'][1])?$info_row['area'][1]:"";?>";

    $("#city").citySelect({
        url:"<?php echo U("/static/javascript/area/city.min.js");?>",
        prov:prov, //省份
        city:city, //城市
        nodata:"none" //当子集无数据时，隐藏select
    });
</script>
</body>
</html>