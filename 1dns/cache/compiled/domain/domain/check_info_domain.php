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
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="format-detection" content="telephone=no">
<meta name="generator" content="">

<title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
<meta name="keywords" content="">
<meta name="description" content="">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_domain.css";?>">
<link rel="stylesheet" href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_domain_py.css";?>">
<link href="<?php echo U("/static/javascript/bootstrap/css/bootstrap.min.css");?>" type="text/css" rel="stylesheet" /></head>
<body class="domain">
<!--header 开始-->
<div class="header">
    <div class="dis5"></div>
    <div class="top <?php if($act == 'index'){?>aps<?php }else{?>aps1<?php }?>">
      <div class="header-left">
        <a href="<?php echo U("home@/");?>" target="_blank"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>"/></a>
        <a class="l-a" href="<?php echo U("domain@/");?>">域名注册</a>
      </div>
      <div class="header-right">
        <ul>
          <li><a href="<?php echo U("/cart/cart");?>"><i class="glyphicon glyphicon-shopping-cart"></i><em class="num"><?php if(empty($uid)){?>0<?php }else{?><?php echo M("domain_register_cart")->get_one("uid=$uid AND status=0 AND indel=0","count(*)");?><?php }?></em></a><i class="line"></i></li>
          <?php if(empty($uid)){?>
          <li>
            <a href="<?php echo U("account@/login");?>">登陆</a>&nbsp;&nbsp;
            <a href="<?php echo U("account@/register");?>">注册</a>
            <i class="line"></i>
          </li>
          <?php }else{?>
          <?php $userinfo= C("user")->get_cache_userinfo($uid)?>
          <li>
              <?php if(!empty($uid)){?> <?php echo isset($userinfo['name'])?$userinfo['name']:"";?> 欢迎回来<?php }else{?> 欢迎来到八戒DNS<?php }?>
              <a href="<?php echo U("account@/login/logout");?>">退出</a>
            <i class="line"></i>
          </li>
          <?php }?>
          <li>
            <?php if(empty($uid)){?>
            <a href="<?php echo U("account@/login?refer=domain./ucenter/index");?>">注册域名管理</a>
            <?php }else{?>
            <a href="<?php echo U("/ucenter/index");?>">注册域名管理</a>
            <?php }?>
          </li>
        </ul>
      </div>
      <div class="cl"></div>
    </div>
  <div class="dis5"></div>
</div>
<!--header 结束-->
<div class="aps1">
        <div class="mod-nav-bar"><span class="nav-title-text"><?php echo isset($domain)?$domain:"";?>的whois信息</span></div>
        <div class="main" style="min-height: 640px">
            <div class="mod-wating" style="margin: 10px 0px 0px 15px;">
                <img alt="正在加载中" class="loading" src="<?php echo U("domain@/")."skins/domain/".$this->skin."/images/loading2.gif";?>" style="width: 22px;height: 22px;"> <strong>正在查询详细信息，请稍等。</strong>
            </div>
        </div>
</div>
<footer class="footer">
    <div class="footer-bottom">
    <span><?php echo isset($site['copyright'])?$site['copyright']:"";?></span>&nbsp;&nbsp;
    <a href="<?php echo U("static@/public/images/xkz.jpg");?>" target="_blank"><span> 互联网经营许可证：<?php echo isset($site['licence'])?$site['licence']:"";?></span></a>&nbsp;&nbsp;
    <a href="<?php echo U("static@/public/images/zz.jpg");?>" target="_blank"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/zhebei.gif";?>" width="20px" height="20px"> <?php echo isset($site['icp'])?$site['icp']:"";?></a>&nbsp;&nbsp;
    <a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=33010802003217" target="_blank"><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/batb.png";?>" width="20px" height="20px"> 浙公网安备 33010802003217号</a>
  </div>
</footer>
<script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.new.js");?>"></script>
<!--[if lte IE 8 ]>
<script src="<?php echo U("static/javascript/amazeui/js/modernizr.js");?>"></script>
<script src="<?php echo U("static/javascript/amazeui/js/amazeui.ie8polyfill.min.js");?>"></script>
<![endif]-->

<?php if($uid){?>
<script language="javascript" src="<?php echo U("static@/cache/dataconfig.js");?>"></script>
<?php }?>
<script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};tCity="<?php echo isset($city)?$city:"";?>";
<?php if($uid){?>
tUser['uid'] = "<?php echo tUtil::numstr($uid);?>";tUser['utype'] = "<?php echo isset($utype)?$utype:"";?>";
<?php }else{?>
tUser['uid'] = 0;tUser['utype'] = 0;<?php }?>
</script>
<script type="text/template" id="tpl_domain_info">
    <#macro rowedit data>
        <div class="mod-box">
            <div class="mod-box-hd">
                <h3>以下信息获取时间：${data.check_time}&nbsp;&nbsp;&nbsp;<span class="btn btn-xs btn-info get-real-info">获取最新信息</span></h3>
            </div>
            <div class="mod-box-bd">
                <table class="form-table tb-whois-info">
                    <colgroup>
                        <col style="width:180px">
                        <col>
                    </colgroup>
                    <tbody>
                    <#if (data.domain_info2 != -1)>
                        <tr>
                            <th>域名所有者</th>
                            <td>
                                <#if (data.domain_info1.r_organize_name_uk)>
                                        ${data.domain_info1.r_organize_name_uk}
                                    <#else>
                                        ${data.domain_info1.r_user_name_cn}
                                </#if>
                            </td>
                        </tr>
                        <tr>
                            <th>联系人</th>
                            <td>
                                <#if (data.domain_info1.r_user_name_cn)>
                                    ${data.domain_info1.r_user_name_cn}
                                    <#else>
                                        ${data.domain_info1.r_organize_name_uk}
                                </#if>
                            </td>
                        </tr>
                        <tr>
                            <th>所有者联系邮箱</th>
                            <td>${data.domain_info1.r_email}</td>
                        </tr>
                        <#if (data.domain_info1.registrant)>
                            <tr>
                                <th>域名注册商</th>
                                <td>${data.domain_info1.registrant}</td>
                            </tr>
                        </#if>
                        <tr>
                            <th>注册日期</th>
                            <td>${data.domain_info1.applyDate}</td>
                        </tr>
                        <tr>
                            <th>到期日期</th>
                            <td>${data.domain_info1.expireDate}</td>
                        </tr>
                        <#if (data.domain_info1.domain_status)>
                            <tr>
                                <th>域名状态</th>
                                <td>
                                    <ul>
                                        <#if (data.domain_info1.domain_status && data.domain_info1.domain_status.length > 0)>
                                            <#list data.domain_info1.domain_status as domain_status>
                                                <li>${domain_status}</li>
                                            </#list>
                                        </#if>
                                    </ul>
                                </td>
                            </tr>
                        </#if>
                        <tr>
                            <th>DNS服务器</th>
                            <td>
                                <ul>
                                    <#if (data.domain_info1.dns && data.domain_info1.dns.length > 0)>
                                            <#list data.domain_info1.dns as dns>
                                                <li>${dns}</li>
                                            </#list>
                                        <#else>
                                            <#if (data.domain_info1.dns1)>
                                                <li>${data.domain_info1.dns1}</li>
                                            </#if>
                                            <#if (data.domain_info1.dns2)>
                                                <li>${data.domain_info1.dns2}</li>
                                            </#if>
                                    </#if>
                                </ul>
                            </td>
                        </tr>
                        <#else>
                            查询超时！
                    </#if>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mod-box">
            <div class="mod-box-hd"><h3>详细注册信息&nbsp;&nbsp;&nbsp;<a class="btn btn-xs btn-info" href="<?php echo U("home@/helper/article?tid=97");?>" target="_blank">专业词汇中/英文对照</a></h3></div>
            <div class="mod-box-bd">
                <pre><#if (data.domain_info2 == -1)>查询超时！<#else>${data.domain_info2}</#if></pre>
                <table class="form-table tb-whois-info">
                    <colgroup>
                        <col style="width:180px">
                        <col>
                    </colgroup>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </#macro>
</script>
<script type="text/javascript">
    $(function () {
        $.ajaxPassport({
            url:"<?php echo U("domain@domain/check_info");?>",
            success:function (res) {
                if (res.error == 0) {
                    var html = "" + easyTemplate($("#tpl_domain_info").html(),res.message);
                    $(".aps1 .main").html(html);

                    //获取最新信息
                    $(".get-real-info").click(function () {
                        var h = "<div class=\"mod-wating\" style=\"margin: 10px 0px 0px 15px;\"> " +
                                "<img alt=\"正在加载中\" class=\"loading\" src=\"<?php echo U("domain@/")."skins/domain/".$this->skin."/images/loading2.gif";?>\" style=\"width: 22px;height: 22px;\"> <strong>正在查询详细信息，请稍等。</strong> " +
                                "</div>";
                        $(".aps1 .main").html(h);

                        $.ajaxPassport({
                            url:"<?php echo U("domain@domain/check_info");?>",
                            success:function (res) {
                                if (res.error == 0) {
                                    var html = "" + easyTemplate($("#tpl_domain_info").html(),res.message);
                                    $(".aps1 .main").html(html);
                                    $(".aps1 .main").find(".get-real-info").hide();
                                }else{
                                    $(".aps1 .main").html("<span style='margin-left:12px;'>查询超时！</span>");
                                }
                            },
                            data:{domain:"<?php echo isset($domain)?$domain:"";?>",do:"get",check:"fresh"}
                        });
                    });
                }else{
                    $(".aps1 .main").html("<span style='margin-left:12px;'>查询超时！</span>");
                }
            },
            data:{domain:"<?php echo isset($domain)?$domain:"";?>",do:"get"}
        });
    });
</script>
</body>
</html>