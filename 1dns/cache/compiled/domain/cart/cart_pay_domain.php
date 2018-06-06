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
                                                <font class="cur">查看购物车</font>
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
                                                <span class="s cur">3</span>
                                                <font class="cur">确认订单</font>
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
                        <div class="content-shop">
                                <div class="order-detail">
                                        <div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 20px;">域名购买(<?php echo count($list['list']);?>)</div>
                                        <div class="dis20"></div>
                                        <table class="table"  style="margin-left: 10px;">
                                                <col>
                                                <col width="220"/>
                                                <col width="220"/>
                                                <thead>
                                                <tr>
                                                        <th>域名</th>
                                                        <th>年限</th>
                                                        <th>金额(元)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($list['list'] as $key => $item){?>
                                                <tr>
                                                        <td>
                                                                <?php echo isset($item['domain'])?$item['domain']:"";?><span style="color: gray;font-size: 12px"> (<?php if($item['type'] == 1){?>新购<?php }else{?>续费<?php }?>)</span>
                                                                <input type="hidden" name="cart_ids0" value="<?php echo isset($item['cart_id'])?$item['cart_id']:"";?>" />
                                                        </td>
                                                        <td><?php echo isset($item['num'])?$item['num']:"";?>年</td>
                                                        <td><?php echo isset($item['amount'])?$item['amount']:"";?></td>
                                                </tr>
                                                <?php }?>
                                                </tbody>
                                        </table>
                                </div>

                                <?php if(!empty($list['list'])){?>
                                <?php if(isset($coupon_arr['id'])){?>
                                <div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 18px">代金券</div>
                                <div class="dis10"></div>
                                <div class="coupon-c">
                                        <?php echo isset($coupon_arr['name'])?$coupon_arr['name']:"";?><br/>
                                </div>
                                <?php }?>
                                <?php }?>


                                <div class="dis30"></div>
                        </div>
                        <div class="dis30"></div>
                        <div class="order-pay" style="border-top: 1px solid silver;text-align: right;">
                                <div class="dis10"></div>
                                <input type="hidden" name="coupon_code" value="<?php if(isset($coupon_arr['id'])){?><?php echo isset($coupon_arr['code'])?$coupon_arr['code']:"";?><?php }?>" />
                                <a type="button" href="<?php echo U("/cart/cart");?>" class="btn btn-default" style="position: relative;top:-12px;left: -500px;">购物车首页</a>
                                订单商品：<span style="color: #FA7821;font-size: 16px;"><?php echo isset($list['num'])?$list['num']:"";?> </span>件&nbsp;&nbsp;&nbsp;应付总价：
                                <span class="total-sum" style="color: #FA7821;font-size: 34px;">
                                        <?php if(isset($coupon_arr['id'])){?>
                                        <?php echo sprintf("%.2f",$list['amount'] - $coupon_arr['balance']);?>
                                        <?php }else{?>
                                        <?php echo isset($list['amount'])?$list['amount']:"";?>
                                        <?php }?></span>元&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="button" class="btn btn-info add-pay-1" style="position: relative;top:-12px;">确认订单</button>
                        </div>
                </div>
        </div>
        </div>
        <div class="dis60"></div>
        <!--添加订单失败-->
        <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="margin-top: 300px">
                <div class="modal-dialog" role="document">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel"> 温馨提示</h4>
                                </div>
                                <div class="modal-body" style="text-align: center;font-size: 18px;margin: 30px 0;">
                                        <div class="" style="font-size: 16px">您有未完成订单，请先完成订单再提交新订单</div>
                                        <div class="dis30"></div>
                                        <a href="" type="button" class="btn btn-default" data-dismiss="modal">关闭</a>
                                        <a href="<?php echo U("/ucenter/order");?>" type="button" class="btn btn-warning">查看订单列表</a>
                                </div>
                        </div>
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

<script type="text/javascript">
        $(function(){
                $(".add-pay-1").unbind("click").bind("click",function(){
                        var coupon_code = $("input[name='coupon_code']").val();
                        var cart_ids0 = [];
                        $("input[name='cart_ids0']").each(function(){
                                cart_ids0.push($(this).val());
                        });
                        var data = {"cart_ids0":cart_ids0.join(",")};
                        data.coupon_code = coupon_code;
                        //执行创建订单并支付请求
                        $.ui.loading();
                        $.ajaxPassport({
                                url:"<?php echo U("/cart/order_add");?>",
                                data:data,
                                success:function(res){
                                        $.ui.close_loading();
                                        if(res.error == 0){
                                                var order_no = res.message;
                                                $.ui.success("提交成功！");
                                                setTimeout(function(){
                                                        $.redirect("<?php echo U("/cart/pay?order_no=");?>"+order_no);
                                                },500);
                                        }else{
                                                setTimeout(function(){
                                                        $('#myModal').modal();
                                                        $("#myModal").find(".modal-dialog").width(500);
                                                },600);
                                        }
                                }
                        });
                })
        });
</script>

</body>
</html>