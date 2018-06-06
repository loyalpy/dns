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
        <div class="cart-top-tip aps1">提醒：.cn 域名注册后需要提交身份资料审核，目前只支持大陆身份证，其它类型证件的用户请谨慎购买</div>
        <div class="dis10"></div>
        <div class="order-shopping">
                <div class="nav">
                        <ul>
                                <li class="un-use use1">
                                        <div class="icon">
                                                <span class="s cur">1</span>
                                                <font class="cur">查看购物车</font>
                                                <span class="cart_icon_right"></span>
                                        </div>
                                </li>
                                <li class="un-use use2">
                                        <div class="icon">
                                                <span  class="s">2</span>
                                                <font class="">提交资料</font>
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
                        <div class="dis10"></div>
                        <div class="content-shop">
                                <div class="step1" style="display: block;">
                                        <div class="step1_empty" <?php if(!empty($list['list'])){?>style="display:none;"<?php }?>>
                                                <span>购物车空空的哦~，去首页<a href="<?php echo U("/domain/index");?>">搜索</a>心仪的域名吧~ </span>
                                        </div>
                                        <div class="step1_list">
                                                <form class="the_searchform form" method="POST" action="<?php echo U("/cart/cart_sub");?>">
                                                        <div class="dis20"></div>
                                                        <?php if(count($list['list'])>0){?>
                                                        <div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 18px">域名注册</div>
                                                        <hr/>
                                                        <div class="cart-list">
                                                                <table class="table">
                                                                        <col />
                                                                        <col width="120px"/>
                                                                        <col width="120px"/>
                                                                        <col width="140px"/>
                                                                        <col width="110px"/>
                                                                        <col width="110px"/>
                                                                        <col width="90px"/>
                                                                        <thead>
                                                                        <tr>
                                                                                <th>域名</th>
                                                                                <th>类型</th>
                                                                                <th>单价</th>
                                                                                <th>时长</th>
                                                                                <th>优惠</th>
                                                                                <th>总计</th>
                                                                                <th>操作</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="add-cart">
                                                                        <?php foreach($list['list'] as $key => $item){?>
                                                                        <tr>
                                                                                <td>
                                                                                        <?php echo isset($item['domain'])?$item['domain']:"";?>
                                                                                        <input type="hidden" name="domain" <?php if(isset($item['renew_price'])){?>data-renew_price="<?php echo isset($item['renew_price'])?$item['renew_price']:"";?>"<?php }?> data-cart_id="<?php echo isset($item['cart_id'])?$item['cart_id']:"";?>" data-price="<?php echo isset($item['price'])?$item['price']:"";?>" data-num="<?php echo isset($item['num'])?$item['num']:"";?>" data-zk="<?php echo isset($item['amount_promation'])?$item['amount_promation']:"";?>">
                                                                                </td>
                                                                                <td><?php if($item['type'] == 1){?>新购<?php }else{?>续费<?php }?></td>
                                                                                <td class="price"><span><?php echo isset($item['price'])?$item['price']:"";?></span>元</td>
                                                                                <td>
                                                                                        <select class="form-control" name="num"  style="width: 80px;">
                                                                                                <?php if($item['type'] == 3){?>
                                                                                                        <?php $num_arr = array(1);?>
                                                                                                <?php }else{?>
                                                                                                        <?php $num_arr = array(1,2,3,4,5,6,7,8,9,10);?>
                                                                                                <?php }?>
                                                                                                <?php foreach($num_arr as $key2 => $item2){?>
                                                                                                <option <?php if($item['num'] == $item2){?>selected="selected"<?php }?> value="<?php echo isset($item2)?$item2:"";?>"><?php echo isset($item2)?$item2:"";?>年</option>
                                                                                                <?php }?>
                                                                                        </select>
                                                                                </td>
                                                                                <td class="zk"><font><?php echo isset($item['amount_promation'])?$item['amount_promation']:"";?></font></td>
                                                                                <td class="total"><font><?php echo isset($item['amount'])?$item['amount']:"";?></font></td>
                                                                                <td><a href="javascript:void (0);" class="Del" data-type="0" data-domain="<?php echo isset($item['domain'])?$item['domain']:"";?>">删除</a></td>
                                                                        </tr>
                                                                        <?php }?>
                                                                        </tbody>
                                                                </table>
                                                        </div>

                                                        <?php if(!empty($list['list'])){?>
                                                                <?php if(count($coupon) > 0){?>
                                                                <div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 18px">代金券</div>
                                                                <div class="dis10"></div>
                                                                <div class="coupon-c">
                                                                        <?php foreach($coupon as $key => $item){?>
                                                                        <?php if($key == 0){?>
                                                                        <input type="checkbox" name="coupon" <?php if(intval($list['amount']) > $item['need_balance']){?>checked="checked"<?php }else{?>disabled<?php }?> data-code="<?php echo isset($item['code'])?$item['code']:"";?>"  value="<?php echo isset($item['balance'])?$item['balance']:"";?>"/>&nbsp;&nbsp;<?php echo isset($item['name'])?$item['name']:"";?>&nbsp;<?php if(intval($list['amount']) <= $item['need_balance']){?>(未满<?php echo isset($item['need_balance'])?$item['need_balance']:"";?>元,不能使用)<?php }?><br/>
                                                                        <?php }?>
                                                                        <?php }?>
                                                                </div>
                                                                <?php }?>
                                                        <?php }?>

                                                        <div class="dis30"></div>
                                                        <div class="cart-add-order">
                                                                <a type="button" class="btn btn-default back-tape" href="<?php echo U("/domain/lists");?>">继续购物</a>
                                                                <div class="go-add">
                                                                        <input type="hidden" name="hash" value="<?php echo tUtil::hash();?>" />
                                                                        <input type="hidden" name="cart_ids" value="" />
                                                                        <input type="hidden" name="coupons" value="" />
                                                                        套餐总价：<span class="total"><?php echo sprintf("%.2f",($list['amount']+$list['amount_promation']));?></span>元<br/>
                                                                        <div class="coupon-console">- 代金券：<span class="coupon-m">0.00</span>元<br/></div>
                                                                        <div style="border-bottom: 1px solid silver;width: 300px;float: right;">- 共优惠：<span class="coupon"><?php echo isset($list['amount_promation'])?$list['amount_promation']:"";?></span>元</div><br/>
                                                                        <div class="dis20"></div>
                                                                        应付总价：<span class="total-sum" ><strong style="color: #FA7821;font-size: 34px;"><?php echo isset($list['amount'])?$list['amount']:"";?></strong></span>元&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-info go-account">立即购买</button><br/>
                                                                </div>
                                                        </div>
                                                        <?php }?>
                                                </form>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
        <div class="dis60" style="margin-bottom: 220px"></div>
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
<?php echo $this->fetch('ucenter/order_tpl')?>
<script type="text/javascript">
        $(function () {
                domain_register_tips();
                //点击时间按钮触发事件
                $(".add-cart tr select").unbind("change").bind("change",function(){
                        var obj=this;
                        var cart_id = $(obj).parent().parent().find("input[name='domain']").data("cart_id");
                        var num = $(obj).val();
                        $.ui.loading();
                        $.ajaxPassport({
                                url:"<?php echo U("/cart/edit_cart");?>",
                                success:function(){
                                        $.ui.close_loading();
                                        account_total($(obj).parent().parent());
                                },
                                data:{cart_id:cart_id,num:num},
                        });
                });
                //删除购物车
                $(".Del").unbind("click").bind("click",function () {
                        var obj = this;
                        var domain = $(obj).data("domain");
                        $.ui.loading();
                        $.ajaxPassport({
                                url:U("/cart/del_cart"),
                                success:function (res) {
                                        $.ui.close_loading();
                                        $.ui.success(res.message);
                                        if (res.error == 0) { //ok
                                                $(obj).parent().parent().remove();
                                                checked_no();

                                                if ($(".cart-list").find("table tbody tr").length == 0) {
                                                        $(".step1 .step1_empty").show();
                                                        $(".step1 .step1_list").hide();
                                                }

                                                domain_register_tips();
                                        }else{//false
                                                $.ui.error(res.message);
                                        }
                                },
                                data:{domain:domain}
                        });
                })
                //去结算
                $(".go-account").click(function(){
                        var coupon_codes = new Array();
                        //优惠代金券
                        $(".content-shop").find("input[name='coupon']:checked").each(function(){
                                var obj = this;
                                coupon_codes.push($(obj).data('code'));
                        });
                        if (coupon_codes.length > 1) {
                                $.ui.error("一次性只能消费一张代金券！");
                                return false;
                        }
                        $("input[name='coupons']").val(coupon_codes.join(","));
                        return true;
                });
                //代金券计算
                coupon_fun();
                //点击时触发代金券函数
                $(".content-shop").find(".coupon-c input[name='coupon']").unbind("click").bind("click",function(){
                        coupon_fun("c");
                });
        });
        //计算总价,域名服务套餐
        var account_total = function(dobj){
                var obj = dobj;
                var num = obj.find("select[name='num']").val();
                var price   = obj.find("input[name='domain']").data("price");
                var renew_price = obj.find("input[name='domain']").data("renew_price");
                var be_num = obj.find("input[name='domain']").data("num");
                var zk = obj.find("input[name='domain']").data("zk");

                //优惠价
                var youhui = parseFloat(num) * (parseFloat(zk)/parseFloat(be_num));
                obj.find("td.zk font").html($.to_float2(youhui));
                //总计
                if (typeof renew_price == "undefined") {//续费
                        var total  = $.to_float2(parseInt(num) *parseFloat(price)-youhui);
                }else{//新买
                        total = $.to_float2(parseFloat(price)+(parseInt(num-1) *parseFloat(renew_price))-youhui);
                }

                obj.find("td.total font").html($.to_float2(total));

                //套餐总价,优惠价,应付总价
                checked_no();
        }
        //套餐总价,优惠价,应付总价
        var checked_no = function(){

                //套餐总价,优惠价,应付总价
                var service_total = 0;
                var service_zk = 0;

                $(".content-shop").find("tr input[name='domain']").each(function(){
                        var obj=this;
                        var tmp1_price = $(obj).parent().parent().find("td.price span").html();
                        var renew_price = $(obj).data("renew_price");
                        var tmp1_num = $(obj).parent().parent().find("select[name='num']").val();
                        var tmp2 = $(obj).parent().parent().find("td.zk font").html();
                        if (typeof renew_price == "undefined") {//续费
                                service_total = service_total + parseFloat(tmp1_price) * parseInt(tmp1_num);
                        }else{//新买
                                service_total = service_total + (parseFloat(tmp1_price) +parseFloat(renew_price) * parseInt(tmp1_num-1));
                        }
                        service_zk = service_zk + parseFloat(tmp2);
                });

                $(".go-add span.total").html($.to_float2(service_total));
                $(".go-add span.coupon").html($.to_float2(service_zk));
                $(".go-add strong").html($.to_float2(service_total-service_zk));

                //代金券计算
                coupon_fun();
        }
        //如果代金券存在，计算代金券价格todo:优惠值大于套餐 解决方法，显示隐藏法 套餐总价算法
        var coupon_fun = function (type) {
                var coupon_m =  0;
                var c_num = 0;
                $(".content-shop").find(".coupon-c input[name='coupon']").each(function () {
                        var obj = this;
                        coupon_m += parseInt($(obj).val());

                        if ($(obj).is(':checked')) {
                                c_num ++;
                        }
                });
                if (c_num > 0) {
                        $(".cart-add-order").find(".coupon-console").show();
                        $(".cart-add-order").find("span.coupon-m").html($.to_float2(coupon_m));
                        $("input[name='coupon_m']").val(coupon_m);

                        $(".go-add strong").html($.to_float2($(".go-add strong").html() - $.to_float2(coupon_m) <= 0?0:$(".go-add strong").html() - $.to_float2(coupon_m)));
                }else{
                        $(".cart-add-order").find(".coupon-console").hide();
                        //仅点击checkbox恢复
                        if (type == "c") {
                                $(".go-add strong").html($.to_float2(parseInt($(".go-add strong").html()) + parseInt(coupon_m)));
                        }
                        //如果代金券金额大于套餐金额
                        if (parseInt($(".go-add span.total").html()) < coupon_m) {
                                $(".go-add strong").html($.to_float2(parseInt($(".go-add span.total").html()) - parseInt($(".go-add span.coupon").html()) - parseInt($(".go-add span.total_yu").html())));
                        }
                }
                c_num = 0;
        }
</script>
</body>
</html>