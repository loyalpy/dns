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
        <div class="dis20"></div>
        <div class="order-shopping order-shopping1">
                <div class="nav">
                        <ul>
                                <li class="un-use use1">
                                        <div class="icon">
                                                <span class="s cur">1</span>
                                                <font  class="cur">查看购物车</font>
                                                <span class="cart_icon_right"></span>
                                        </div>
                                </li>
                                <li class="un-use use2">
                                        <div class="icon">
                                                <span  class="s cur">2</span>
                                                <font class="cur">提交资料</font>
                                                <span class="cart_icon_right"></span>
                                        </div>
                                </li>
                                <li class="un-use use3">
                                        <div class="icon">
                                                <span class="s">3</span>
                                                <font class="">确认订单</font>
                                                <span class="cart_icon_right"></span>
                                        </div>
                                </li>
                                <li class="un-use use4">
                                        <div class="icon">
                                                <span class="s">4</span>
                                                <font >支付</font>
                                        </div>
                                </li>
                        </ul>
                </div>
                <div class="content">
                        <div class="dis30"></div>
                        <?php if(count($info_list) >0){?>
                        <div id="old-table-info">
                                <div class="use-new-m"><strong>以下为您曾经使用过的域名信息，您可以直接使用：</strong><span style="color: #00a1cb;float: right;cursor: pointer"><i class="glyphicon glyphicon-plus"></i> 创建新的信息模板</span></div>
                                <div class="dis20"></div>
                                <table class="table table-b">
                                        <col width="50px"/>
                                        <col>
                                        <col width="260px"/>
                                        <col width="260px"/>
                                        <col width="60px"/>
                                        <?php foreach($info_list as $key => $item){?>
                                        <tr>
                                                <td><input type="radio" name="s-s" <?php if($item['is_tpl'] == 1){?>checked<?php }else{?><?php if($key == 0){?>checked<?php }?><?php }?> data-id="<?php echo isset($item['id'])?$item['id']:"";?>"/></td>
                                                <td><?php echo isset($item['aller_name_cn'])?$item['aller_name_cn']:"";?><td>
                                                <td><?php echo isset($item['aller_name'])?$item['aller_name']:"";?><td>
                                                <td><?php echo isset($item['email'])?$item['email']:"";?><td>
                                                <td ><a href="javascript:;"  class="btn-info-d" style="color: #00a1cb" data-id="<?php echo isset($item['id'])?$item['id']:"";?>">详情</a></td>
                                        </tr>
                                        <tr class="shows-tr">
                                                <td colspan="5" class="shows">
                                                        <table class="table i-<?php echo isset($item['id'])?$item['id']:"";?>" style="display: none">
                                                                <col width="150px">
                                                                <col />
                                                                <tr>
                                                                        <td class="l">用户类型:</td>
                                                                        <td><?php if($item['utype'] == 1){?>个人用户<?php }else{?>企业用户<?php }?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">域名所有者:</td>
                                                                        <td><?php echo isset($item['aller_name_cn'])?$item['aller_name_cn']:"";?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">域名所有者(英文):</td>
                                                                        <td><?php echo isset($item['aller_name'])?$item['aller_name']:"";?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">联系人:</td>
                                                                        <td><?php echo isset($item['name_cn'])?$item['name_cn']:"";?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">联系人(英文):</td>
                                                                        <td><?php echo isset($item['name'])?$item['name']:"";?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">邮箱:</td>
                                                                        <td><?php echo isset($item['email'])?$item['email']:"";?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">地区:</td>
                                                                        <td><?php echo str_replace(","," ",$item['area']);?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">通讯地址:</td>
                                                                        <td><?php echo isset($item['addr_cn'])?$item['addr_cn']:"";?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">通讯地址(英文):</td>
                                                                        <td><?php echo isset($item['addr'])?$item['addr']:"";?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">邮编:</td>
                                                                        <td><?php echo isset($item['ub'])?$item['ub']:"";?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">手机:</td>
                                                                        <td><?php echo isset($item['mobile'])?$item['mobile']:"";?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td class="l">传真:</td>
                                                                        <td><?php echo str_replace(",","-",$item['cz']);?></td>
                                                                </tr>
                                                        </table>
                                                        <div class="dis30"></div>
                                                </td>
                                        </tr>
                                        <?php }?>
                                </table>
                        </div>
                        <?php }?>
                        <table class="table table-c table-begin" <?php if(count($info_list) > 0){?>style="display:none"<?php }?>>
                                <col width="150px">
                                <col />
                                <tr>
                                        <td class="l">用户类型</td>
                                        <td>
                                                <label class="radio-inline">
                                                        <input class="per" type="radio" name="utype" value="1" checked/> 个人用户
                                                </label>
                                                <label class="radio-inline">
                                                        <input class="com" type="radio" name="utype" value="2"/> 企业用户
                                                        <span style="color: grey;display: none">&nbsp;&nbsp;(注册.cn等国内域名,企业用户需提交企业营业执照扫描件或组织机构代码扫描件)</span>
                                                </label>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">域名所有者<span>*</span></td>
                                        <td>
                                                <input type="text" name="aller_name_cn" class="i form-control"  placeholder="中文名"/>
                                                <span class="glyphicon glyphicon-question-sign gly" data-toggle="tooltip" data-placement="right" title="① 所有者信息一经填写无法随意修改; ② .cn等国内域名请填写与待审核证件完全相同的名字; ③ 请保持与提交备案时主办单位名称一致。"></span>&nbsp;&nbsp;
                                                <i class="t">请填入32个汉字以内正确的中文名</i>
                                                <i class="glyphicon glyphicon-ok f"></i>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">域名所有者(英文)<span>*</span></td>
                                        <td>
                                                <input type="text" name="aller_name" class="i form-control"  placeholder="英文名">
                                                <span class="glyphicon glyphicon-question-sign gly"  data-toggle="tooltip" data-placement="right" title="请于中文名一致以便备案时核对"></span>&nbsp;&nbsp;
                                                <i class="t">请填写不少于两个字符的英文名称</i>
                                                <i class="glyphicon glyphicon-ok f"></i>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">联系人<span>*</span></td>
                                        <td>
                                                <input type="text" name="name_cn" class="i form-control"  placeholder="中文名">
                                                <i class="t">请填入32个汉字以内正确的中文名</i>
                                                <i class="glyphicon glyphicon-ok f"></i>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">联系人(英文)<span>*</span></td>
                                        <td>
                                                <input type="text" name="name" class="i form-control"  placeholder="英文名">
                                                <i class="t">域名联系人(英文名)为必填字段</i>
                                                <i class="glyphicon glyphicon-ok f"></i>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">邮箱<span>*</span></td>
                                        <td>
                                                <input type="text" name="email" class="i form-control"  placeholder="Email">
                                                <i class="t">请输入正确的电子邮件</i>
                                                <i class="glyphicon glyphicon-ok f"></i>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">地区<span>*</span></td>
                                        <td>
                                                <div id="city" class="">
                                                        <select class="form-control"><option>中国</option></select>
                                                        <select class="form-control prov" name="s_province"></select>
                                                        <select class="form-control city" name="s_city"></select>
                                                </div>
                                                <span class="glyphicon glyphicon-question-sign gly" style="position: relative;top:-25px;left: 490px;" data-toggle="tooltip" data-placement="right" title="① 目前暂不支持国外地区; ② 香港，澳门等地区在二级联动的底部。"></span>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">通讯地址<span>*</span></td>
                                        <td>
                                                <input type="text" name="addr_cn" class="i form-control"  placeholder="中文地址">
                                                <i class="t">请输入中文联系地址,必填字段</i>
                                                <i class="glyphicon glyphicon-ok f"></i>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">通讯地址(英文)<span>*</span></td>
                                        <td>
                                                <input type="text" name="addr" class="i form-control"  placeholder="英文地址">
                                                <i class="t">请输入英文联系地址,必填字段</i>
                                                <i class="glyphicon glyphicon-ok f"></i>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">邮编<span>*</span></td>
                                        <td>
                                                <input type="text" name="ub" class="i form-control" >
                                                <i class="t">请输入6位数字邮编</i>
                                                <i class="glyphicon glyphicon-ok f"></i>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">手机<span>*</span></td>
                                        <td>
                                                <input type="text" name="mobile" class="i form-control" >
                                                <i class="t">请正确填写您的手机号码</i>
                                                <i class="glyphicon glyphicon-ok f"></i>
                                        </td>
                                </tr>
                                <tr>
                                        <td class="l">传真<span>*</span></td>
                                        <td>
                                                <input type="text" name="cz1" class="i form-control " style="width: 60px;display: inline-block;" value="086"> - <input type="text" name="cz2" class="i form-control"  style="width: 60px;display: inline-block;"> - <input type="text" name="cz3" class="i form-control"  style="width: 130px;display: inline-block;" >
                                                <!--<i class="glyphicon glyphicon-question-sign gly1"  data-toggle="tooltip" data-placement="right" title="如果没有，则不填写，默认为手机"></i>-->
                                                <i class="t">请输入正确数字号码，国家区号3位,区号4位,号码最多8位</i>
                                                <i class="glyphicon glyphicon-ok f"></i>
                                        </td>
                                </tr>
                        </table>
                        <div class="t-l"></div>
                        <div class="dis30"></div>
                        <div class="t-c-next">
                                <div class="t-c-l"><a type="button" class="btn btn-default" href="<?php echo U("/cart/cart");?>">上一步</a></div>
                                <div class="t-c-r">
                                        <input type="hidden" name="hash" value="<?php echo tUtil::hash();?>">
                                        <button type="submit" class="btn btn-info <?php if(count($info_list) > 0){?>add-sub-1<?php }else{?>add-sub<?php }?>">提交资料</button>
                                </div>
                        </div>
                        <div class="cl"></div>
                </div>
        </div>
        <div class="dis60"></div>
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

<script type="text/javascript">
        var coupons = "<?php echo isset($coupons)?$coupons:"";?>";
        $(function () {
                if (window.history && window.history.pushState) {
                        $(window).on('popstate', function () {
                                var hashLocation = location.hash;
                                var hashSplit = hashLocation.split("#!/");
                                var hashName = hashSplit[1];
                                if (hashName !== '') {
                                        var hash = window.location.hash;
                                        if (hash === '') {
                                                window.location.replace("<?php echo U("/cart/cart");?>");
                                        }
                                }
                        });
                        window.history.pushState('forward', null, './cart_sub');
                }
                //tips初始化
                $('[data-toggle="tooltip"]').tooltip();
                //个人企业切换提示
                $(".table-c td .per").click(function () {
                      $(".radio-inline span").hide();
                });
                $(".table-c td .com").click(function () {
                      $(".radio-inline span").show();
                });
                $(".btn-info-d").unbind("click").bind("click",function () {
                        var s = ".i-"+$(this).data("id");
                        if ($(s).css("display") == "none") {
                                $(s).show();
                        }else{
                                $(s).hide();
                        }
                });
                $(".use-new-m span").click(function () {
                        $("table.table-begin").show();
                        $("#old-table-info").hide();

                        $(".t-c-next button").removeClass("add-sub-1").addClass("add-sub");
                        //提交并支付
                        $(".add-sub").unbind("click").bind("click",function () {
                                sub_add_info();
                        });
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
                //传真判断
                $("input[name='cz1']").keyup(function () {
                        var pattern = /^[0-9]{3}$/;
                        pan_is_show(this,pattern);
                });
                //传真判断
                $("input[name='cz2']").keyup(function () {
                        var pattern = /^[0-9]{3,4}$/;
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
                //提交并支付模板
                $(".add-sub-1").unbind("click").bind("click",function () {
                        //use模板
                        var id = $("input[name='s-s']:checked").data("id");
                        if (id <= 0) {
                                $.ui.error("请选择模板！");
                                return false;
                        }
                        $.ui.loading();
                        $.ajaxPassport({
                                url:"<?php echo U("/cart/change_template");?>",
                                success:function (res) {
                                        $.ui.close_loading();
                                        if (res.error == 1) {
                                                $.ui.error(res.message);
                                        }else{
                                                setTimeout(function () {
                                                        if ($.is_empty(coupons)) {
                                                                window.location.replace("<?php echo U("/cart/cart_pay?protected_trace=on");?>");
                                                        }else{
                                                                window.location.replace("<?php echo U("/cart/cart_pay?protected_trace=on&coupons=");?>"+coupons);
                                                        }
                                                },500);
                                        }
                                },
                                data:{id:id}
                        });
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
                $(".table-c td input").each(function () {
                        if ((!$(this).parent().find("i.t").is(":hidden") && $(this).val() != 1 && $(this).val() != 2) || $.is_empty($(this).val())) {
                                num++;
                                if ($(this).attr("name") == "cz2" || $(this).attr("name") == "cz3") {
                                        $(this).parent().find("i.f").hide();
                                }
                                $(this).parent().find("i.t").show();
                        }
                });
                if (num > 0) {
                        return false;
                }
                var utype = $("input[name='utype']:checked").val();
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

                var cz1 = $("input[name='cz1']").val();
                var cz2 = $("input[name='cz2']").val();
                var cz3 = $("input[name='cz3']").val();

                var pattern1 = /^[0-9]{3,4}$/;
                var pattern2 = /^[0-9]{7,8}$/;
                if ( !pattern1.test(cz2)) {
                        $.ui.error("区号为3-4位!");
                        return false;
                }
                if ( !pattern2.test(cz3)) {
                        $.ui.error("区号+传真号码长度必须小于 12!");
                        return false;
                }
                $.ui.loading();
                $.ajaxPassport({
                        url:"<?php echo U("/cart/cart_pay");?>",
                        type:"POST",
                        success:function (res) {
                                $.ui.close_loading();
                                if (res.error == 1) {
                                        $.ui.error(res.message);
                                }else{
                                        $.ui.success(res.message);
                                        setTimeout(function () {
                                                if ($.is_empty(coupons)) {
                                                        window.location.replace("<?php echo U("/cart/cart_pay?protected_trace=on");?>");
                                                }else{
                                                        window.location.replace("<?php echo U("/cart/cart_pay?protected_trace=on&coupons=");?>"+coupons);
                                                }
                                        },500);
                                }
                        },
                        data:{utype:utype,aller_name_cn:aller_name_cn,aller_name:aller_name,name_cn:name_cn,name:name,email:email,s_province:s_province,s_city:s_city,addr_cn:addr_cn,addr:addr,ub:ub,mobile:mobile,cz1:cz1,cz2:cz2,cz3:cz3,hash:"<?php echo tUtil::hash();?>"}
                });
        }
</script>
<!--三级地区联动-->
<script type="text/javascript" src="<?php echo U("/static/javascript/area/jquery.cityselect.js");?>"></script>
<script type="text/javascript">
        var prov = "浙江省";
        var city = "杭州";
        $("#city").citySelect({
                url:"<?php echo U("/static/javascript/area/city.min.js");?>",
                prov:prov, //省份
                city:city, //城市
                nodata:"none" //当子集无数据时，隐藏select
        });
</script>
</body>
</html>