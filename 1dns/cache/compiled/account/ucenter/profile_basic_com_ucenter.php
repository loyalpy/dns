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
            <li><a href="<?php echo U("/ucenter/profile_basic_com");?>"  class="cur">企业资料&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_msg");?>" >系统通知&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_passwd");?>"  >修改密码&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right">
    <div>
        <h1><span class="list_tit_name">企业管理<span style="font-size: 14px;color: #5EB95E">(当前类型：<?php if(($userinfo['utype']==2)){?>企业账户<?php }else{?>个人账户<?php }?>)</span></span></h1>
    </div>
    <hr/>
    <div class="dis30"></div>
    <div class="am-msg-content">
            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;">企业名称</label>
                <div class="am-u-sm-7">
                    <input type="text" class="am-form-field am-radius" name="company_name" value="<?php echo isset($company['company_name'])?$company['company_name']:"";?>">
                    <small class="am-font-gray">请填写企业名称</small>
                </div>
                <div class="am-cf"></div>
            </div>

            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;">证件号码</label>
                <div class="am-u-sm-7">
                    <input type="name" class="am-form-field am-radius" name="com_card" value="<?php echo isset($com_card['content'])?$com_card['content']:"";?>">
                    <small class="am-font-gray">请填写证件号码</small>
                </div>
                <div class="am-cf"></div>
            </div>
            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;">营业执照</label>
                <div class="am-u-sm-7">
                    <div class="am-form-group am-form-file">
                        <form action="<?php echo U("/ucenter/rz_upload");?>" class="uploadform" method="post" enctype="multipart/form-data">
                        <button type="button" class="am-btn am-btn-default am-btn-sm am-btn-uploadzj">
                            <i class="am-icon-cloud-upload"></i> 上传营业执照扫描件</button>
                            <?php if(isset($rz['shenfenzheng'])){?>
                                <?php if($rz['shenfenzheng']['status'] == 0){?>
                                <span class="sc-tips">上传待认证</span>
                                <?php }elseif($rz['shenfenzheng']['status'] == 1){?>
                                <span class="sc-tips">变更待认证</span>
                                <?php }elseif($rz['shenfenzheng']['status'] == 2){?>
                                <span class="sc-tips">认证失败</span>
                                <?php }elseif($rz['shenfenzheng']['status'] == 3){?>
                                <span class="sc-tips am-text-success">已认证</span>
                                <?php }?>
                            <?php }else{?>
                                <span class="sc-tips">未上传</span>
                            <?php }?>
                            <input  type="file" name="attach_file" value="<?php if(isset($rz['shenfenzheng'])){?>1<?php }?>">
                            <input type="hidden" name="name" value="shenfenzheng" />
                            <input type="hidden" name="hash" value="<?php echo tUtil::hash();?>" />
                        </form>
                    </div>
                    <div class="file-list" <?php if(!isset($rz['shenfenzheng'])){?>style="display: none;position:relative;"<?php }?>>
                        <img src="<?php if(isset($rz['shenfenzheng'])){?><?php echo U("static@/");?><?php echo isset($rz['shenfenzheng']['path'])?$rz['shenfenzheng']['path']:"";?><?php }?>" width="200" class="Dcompany_zj" />
                    </div>
                    <a href="javascript:;" class="overbtn" <?php if(!isset($rz['shenfenzheng'])){?>style="display: none;"<?php }?>>查看原图</a>
                </div>
                <div class="am-cf"></div>
            </div>
            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;">组织机构证件</label>
                <div class="am-u-sm-7">
                    <div class="am-form-group am-form-file">
                        <form action="<?php echo U("/ucenter/rz_upload");?>" class="uploadform" method="post" enctype="multipart/form-data">
                            <button type="button" class="am-btn am-btn-default am-btn-sm am-btn-uploadzj">
                                <i class="am-icon-cloud-upload"></i> 上传组织机构证件扫描件</button>
                                <?php if(isset($rz['jigou'])){?>
                                    <?php if($rz['jigou']['status'] == 0){?>
                                    <span class="sc-tips">上传待认证</span>
                                    <?php }elseif($rz['jigou']['status'] == 1){?>
                                    <span class="sc-tips">变更待认证</span>
                                    <?php }elseif($rz['jigou']['status'] == 2){?>
                                    <span class="sc-tips">认证失败</span>
                                    <?php }elseif($rz['jigou']['status'] == 3){?>
                                    <span class="sc-tips am-text-success">已认证</span>
                                    <?php }?>
                                <?php }else{?>
                                    <span class="sc-tips">未上传</span>
                                <?php }?>
                            <input  type="file" name="attach_file" value="<?php if(isset($rz['jigou'])){?>1<?php }?>">
                            <input type="hidden" name="name" value="jigou" />
                            <input type="hidden" name="hash" value="<?php echo tUtil::hash();?>" />
                        </form>
                    </div>
                    <div class="file-list" <?php if(!isset($rz['jigou'])){?>style="display: none;position:relative;"<?php }?>>
                        <img src="<?php if(isset($rz['jigou'])){?><?php echo U("static@/");?><?php echo isset($rz['jigou']['path'])?$rz['jigou']['path']:"";?><?php }?>" width="200" class="Dcompany_zj"  over="<?php if(isset($rz['jigou'])){?><?php echo U("static@/");?><?php echo isset($rz['jigou']['path'])?$rz['jigou']['path']:"";?><?php }?>"/>
                    </div>
                    <a href="javascript:;" class="overbtn" <?php if(!isset($rz['jigou'])){?>style="display: none;"<?php }?>>查看原图</a>
                </div>
                <div class="am-cf"></div>
            </div>
           <div class="dis20"></div>
            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;"></label>
                <div class="am-u-sm-7 am-text-left">
                    <input type="hidden" name="company_id" value="<?php echo isset($company['company_id'])?$company['company_id']:"";?>"/>
                    <button type="button" class="am-btn am-btn-success am-radius company-save"><?php if(isset($company['company_id'])){?>保存修改<?php }else{?>升级企业用户<?php }?></button>
                </div>
                <div class="am-cf"></div>
            </div>
            <div class="dis20"></div>
    </div>
    <div class="dis30"></div>
    <div class="now-dns-bot">
        <div class="dis10"></div>
        <dl>
            <dt>温馨提示：</dt>
            <dd>• 上传图片须为清晰、完整，无遮挡、涂抹，图片格式为：JPG,GIF,PNG,JPEG，图片大小不超过2M。</dd>
            <dd>• 请提交组织机构代码证和营业执照扫描件。</dd>
            <dd>• 以上认证信息仅作为八戒DNS审核使用，不会被其他用户查看！上传成功后客服会在最短时间审核并反馈审核结果。</dd>
        </dl>
    </div>
    <div class="dis30"></div>
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
<!--提交修改事件-->
<script type="text/javascript">
    $(".am-msg-content").find("button,a").bind("focus",function(){
        $(this).blur();
    });
    $(".company-save").unbind("click").bind("click",function(){
        var company_name = $("input[name='company_name']").val();
        if ($.is_empty(company_name)) {
            $.ui.error("企业名称不能为空!");
            return false;
        }
        var com_card = $("input[name='com_card']").val();
        var pattern = /^[0-9A-Za-z]*$/i;
        if ($.is_empty(com_card) || !pattern.test(com_card) || com_card.length < 8) {
            $.ui.error("请填写正确证件号码!");
            return false;
        }
        $.ajaxPassport({
            url:"<?php echo U("/ucenter/profile_basic_com");?>",
            success:function(res){
                if (res.error == 1) {
                    $.ui.error(res.message);
                } else {
                    $.ui.success(res.message);
                }
            },
            data:{company_name:company_name,com_card:com_card,id:1}
        });
    });
    $(function() {
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

        //上传认证附件
        $("form.uploadform").data("success",function(res,formobj){
            if(res.error == 1){
                $.ui.error(res.message);
            }else{
                $.ui.success("上传企业证件成功!");

                if(res.status == 0){
                    $(formobj).find(".sc-tips").html("上传待认证");
                }else if(res.status == 1){
                    $(formobj).find(".sc-tips").html("变更待认证");
                }else if(res.status == 2){
                    $(formobj).find(".sc-tips").html("认证失败");
                }else if(res.status == 2){
                    $(formobj).find(".sc-tips").html("认证成功");
                }
                $(formobj).parent().parent().find(".file-list").show();
                $(formobj).parent().parent().find(".overbtn").show();

                var imgobj = $(formobj).parent().parent().find(".Dcompany_zj");
                imgobj.attr("src",res.path+"?"+Math.random());
                imgobj.attr("over",res.path);
            }
            $.ui.close_loading($(".Dcompany_zj").parent());
        }).submit(function(){
            $.ui.loading($(".Dcompany_zj").parent());
            return $.ajaxForm(this,100);
        }).find("input[type='file']").unbind("change").bind("change",function(){
            var obj = this;
            $(obj).parent().submit();
        });
    });
</script>
</body>
</html>