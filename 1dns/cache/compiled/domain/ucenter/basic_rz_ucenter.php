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
            <li><a href="<?php echo U("/ucenter/basic_edit?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype ">域名信息修改&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_rz?domain=");?><?php echo isset($domain)?$domain:"";?>"  class="showtype cur">域名实名认证&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_transfer?domain=");?><?php echo isset($domain)?$domain:"";?>"  class="showtype">域名过户&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_dns?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype ">DNS修改创建&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_zs?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype">域名证书&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/basic_status?domain=");?><?php echo isset($domain)?$domain:"";?>"   class="showtype">安全设置&nbsp;&nbsp;<cite class="am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right basic-rz" style="padding-top: 25px">
    <div class="basic-top">&nbsp;&nbsp;&nbsp;域名实名认证</div>
    <div class="dis20"></div>
    <div class="fabu-stepbox" style="margin-left:80px;">
        <ul class="clear">
            <li class="<?php if($type == 1){?>current<?php }?>"><i class="i1">1</i><span>上传认证资料</span><b></b></li>
            <li class="<?php if(in_array($type,array(2,4))){?>current<?php }?>"><i class="i2">2</i><span>等待审核(两个工作日完成)</span><b></b></li>
            <li  class="<?php if(in_array($type,array(3,5))){?>current<?php }?>"><i class="i3">3</i><span>查看实名认证结果</span></li>
        </ul>
    </div>
    <div class="dis10"></div>
    <!--实名认证第一步 -->
    <table class="am-table basic-rz-table basic-rz-1" <?php if($type != 1){?>style="display:none"<?php }?>>
        <col <?php if($result['reg_type'] == 1){?>width="200px"<?php }else{?>width="300px"<?php }?>/>
        <col />
        <thead>
        </thead>
        <tbody>
        <tr>
            <td class="l">域名所有者：</td>
            <td><?php echo isset($list_info['aller_name_cn'])?$list_info['aller_name_cn']:"";?></td>
        </tr>
        <tr>
            <td class="l">用户类型：</td>
            <td><?php if($result['reg_type'] == 1){?>个人用户<?php }else{?>企业用户<?php }?></td>
        </tr>
        <tr>
            <td class="l"><?php if($result['reg_type'] == 1){?>所有者身份证号码：<?php }else{?>企业营业执照或组织机构代码证号：<?php }?></td>
            <td>
                <input type="text" name="sfz" value="" class="am-form-field am-radius"  placeholder=""><i class="i">请填写正确的身份证号码</i>
                <div class="am-font-gray sfz-tips"><?php if($result['reg_type'] == 1){?>请填写和域名所有者一致的身份证号码<?php }else{?>请填写企业营业执照或组织机构代码证号<?php }?></div>
            </td>
        </tr>
        <tr>
            <td class="l"><?php if($result['reg_type'] == 1){?>上传实名认证资料：<?php }else{?>企业营业执照或组织机构代码扫面件：<?php }?></td>
            <td>
                <div class="am-form-group am-form-file">
                    <form action="<?php echo U("/ucenter/upload_sfz");?>" class="uploadform" method="post" enctype="multipart/form-data">
                        <button type="button" class="am-btn am-btn-default am-btn-sm am-btn-uploadzj">
                            <i class="am-icon-cloud-upload"></i> 上传证件扫描件</button>
                        <input id="doc-form-file" type="file" name="attach_file" value="">
                        <input type="hidden" name="hash" value="<?php echo tUtil::hash();?>" />
                        <input type="hidden" name="domain" value="<?php echo isset($domain)?$domain:"";?>" />
                    </form>
                </div>

                <div id="file-list" style="position:relative;width: 200px" >
                    <img src="<?php echo U("static@$imgurl");?>" width="200" id="Dcompany_zj" <?php if($imgurl){?><?php }else{?>style="display:none"<?php }?> />
                    <a href="javascript:void (0);" class="del-domain-ups" <?php if($imgurl){?><?php }else{?>style="display:none"<?php }?>>删除</a>
                </div>


                <?php if($result['reg_type'] == 1){?>
                <span id="exm" class="am-font-gray sfz-tips <?php if($imgurl){?>sfz-tips-1<?php }?>">域名所有者身份证正面扫描件 <i class="am-icon-twitch" data-am-popover="{theme: 'default sm',content: '需上传不大于1M清晰，完整JPG格式图片', trigger: 'hover focus'}"></i></span>
                <div id="exm1" class="<?php if($imgurl){?>sfz-tips-1<?php }?>">
                    <span>如图实例：</span><br/>
                    <img src="<?php echo U("")."skins/".$this->app."/".$this->skin."/images/card_id.png";?>">
                </div>
                <?php }?>

            </td>
        </tr>
        </tbody>
    </table>
    <div class="basic-rz-sub basic-rz-1" <?php if($type != 1){?>style="display:none"<?php }?>>
        <button type="button" class="am-btn am-btn-warning am-radius edit-sub">提交</button>
    </div>
    <!--实名认证第二步 -->
    <div class="basic-rz-sub-2" <?php if(!in_array($type,array(2,4))){?>style="display:none"<?php }?>>
        <div class="sub-2-l">
            <?php if($type == 4){?>

            <?php }else{?>
            <i class="am-icon-check-circle-o"></i>
            <?php }?>

        </div>
        <div class="sub-2-r">
            <?php if($type == 4){?>
            <p style="font-size: 16px">审核失败</p>
            <p><a href="<?php echo U("/ucenter/basic_rz?domain=");?><?php echo isset($domain)?$domain:"";?>&ch=1">重新上传认证资料</a></p>
            <?php }else{?>
            <p style="font-size: 16px">上传资料成功</p>
            <p>域名所有者实名认证上传资料成功，我们将在2个工作日内完成审核，请您耐心等待审核结果。</p>
            <?php }?>
        </div>
        <div class="dis60"></div>
    </div>
    <!--实名认证第三步 -->
    <div class="basic-rz-sub-3" <?php if(!in_array($type,array(3,5))){?>style="display:none"<?php }?>>
        <div class="sub-3-l">
            <i class="am-icon-check-circle-o"></i>
        </div>
        <div class="sub-3-r">
            <?php if($type == 5){?>
            <p style="font-size: 16px">国际域名，无需认证，域名所有者已认证成功</p>
            <p>国内域名注册成功后，必须在5天内完成域名所有者实名认证后才能正常使用</p>
            <?php }else{?>
            <p style="font-size: 16px">认证成功</p>
            <p>恭喜您，您域名所有者实名认证成功！     </p>
            <?php }?>
        </div>
        <div class="dis60"></div>
    </div>

    <div class="dis30"></div>
    <div class="now-dns-bot">
        <div class="dis10"></div>
        <dl>
            <?php $register_type = tCache::read("data_config")?>
            <?php $register_type =  isset($register_type['domain_register_type'])?implode("/",$register_type['domain_register_type']):'';?>
            <dt>温馨提示：</dt>
            <dd>• 根据注册局规定，<?php echo isset($register_type)?$register_type:"";?>域名注册成功后，必须实名认证才能正常使用，否则域名将被注册局锁定。</dd>
            <dd>• 如域名所有者为公司，请提交组织机构代码证；如域名所有者为个人，请提交身份证正面扫描件。</dd>
            <dd>• 上传图片须为清晰、完整，无遮挡、涂抹，图片格式为：JPG，图片大小不超过1M。</dd>
        </dl>
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
<script type="text/javascript">
    var domain = "<?php echo isset($domain)?$domain:"";?>";
    var reg_type = "<?php echo isset($result['reg_type'])?$result['reg_type']:"";?>";
    $(function() {
        //提交认证资料
        $(".edit-sub").click(function () {
            var cart_id = $("input[name='sfz']").val();

            //正则匹配身份证号码
            if (reg_type == 1) {
                var pattern = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/i;
                if (!pattern.test(cart_id)) {
                    $("input[name='sfz']").parent().find("i.i").show();
                    return false;
                }else{
                    $("input[name='sfz']").parent().find("i.i").hide();
                }
            }else{

            }

            $.ui.loading();
            $.ajaxPassport({
                url:"<?php echo U("/ucenter/basic_rz");?>",
                success:function (res) {
                    $.ui.close_loading();
                    if (res.error == 1) {
                       $.ui.error(res.message);
                    }else{
                        $.ui.success("上传资料成功！");
                        $(".basic-rz-1").hide();
                        $(".basic-rz-sub-2").show();
                        $(".fabu-stepbox").find(".i2").parent().addClass("current");
                    }
                },
                data:{cart_id:cart_id,do:"edit",domain:domain}
            });
        });

        //删除已上传照片
        $(".del-domain-ups").click(function () {
            $.ui.loading();
            $.ajaxPassport({
                url:"<?php echo U("/ucenter/del_upload_sfz");?>",
                success:function (res) {
                    $.ui.close_loading();
                    if (res.error == 1) {
                        $.ui.error(res.message);
                    }else{
                        $.ui.success(res.message);
                        window.location.reload();
                    }
                },
                data:{domain:domain}
            });
        });

        //上传认证附件
        $("form.uploadform").data("success",function(res,formobj){
            if(res.error == 1){
                $.ui.error(res.message);
            }else{
                var imgobj = $("#Dcompany_zj");
                imgobj.show();
                $(".del-domain-ups").show();
                $("#exm1").hide();
                $("#exm").hide();
                imgobj.attr("src",res.path+"?"+Math.random());
                $(".sfz-tips-1").hide();
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
</script>
</body>
</html>