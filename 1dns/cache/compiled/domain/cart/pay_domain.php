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
                                                <span class="s cur">4</span>
                                                <font class="cur" >支付</font>
                                        </div>
                                </li>
                        </ul>
                </div>
                <div class="content">
                        <div class="dis30"></div>
                        <div class="content-shop">
                                <div class="order-detail">
                                        <div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 20px;">订单详情</div>
                                        <div class="dis20"></div>
                                        <table class="table table-bordered"  style="margin-left: 10px;">
                                                <col width="220"/>
                                                <col>
                                                <col width="220"/>
                                                <col width="220"/>
                                                <thead>
                                                <tr>
                                                        <th>订单编号</th>
                                                        <th>域名</th>
                                                        <th>年限</th>
                                                        <th>金额(元)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($orderinfo['order_item'] as $key => $item){?>
                                                <tr>
                                                        <?php if($key == 0){?>
                                                        <td rowspan="<?php echo count($orderinfo['order_item']);?>" class="am-text-middle"><?php echo isset($orderinfo['order_no'])?$orderinfo['order_no']:"";?></td>
                                                        <?php }?>
                                                        <td>
                                                                <?php echo isset($item['domain'])?$item['domain']:"";?><span style="color: gray;font-size: 12px"> (<?php if($item['type'] == 1){?>新购<?php }else{?>续费<?php }?>)</span>
                                                            <input type="hidden" name="domain_type" value="<?php echo isset($item['type'])?$item['type']:"";?>"/>
                                                        </td>
                                                        <td><?php echo isset($item['num'])?$item['num']:"";?>年</td>
                                                        <td><?php echo isset($item['amount'])?$item['amount']:"";?></td>
                                                </tr>
                                                <?php }?>
                                                </tbody>
                                        </table>
                                </div>

                                <?php if($orderinfo['amount_coupon'] > 0){?>
                                <div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 18px">代金券</div>
                                <div class="dis10"></div>
                                <div class="coupon-c">
                                    代金券共优惠<font class="am-text-warning"><?php echo sprintf("%.2f",$orderinfo['amount_coupon']);?></font>元
                                </div>
                                <?php }?>


                                <div class="dis30"></div>
                                <div class="order-type">
                                        <div class="cart-type" style="border-left: 2px solid #5D9FD6;padding-left: 10px;font-size: 20px;display:none;">支付方式</div>

                                        <div class="records-nav profile_information" >
                    <ul>
                        <li class="yu cur"><a href="javascript:void (0);">支付方式</a></li>
                        <li class="outline"><a href="javascript:void (0);" style="display:none">支付宝在线支付</a></li>
                    </ul>
                </div>
                            <div class="dis10"></div>
                            <div class="dis10"></div>
                            <div style="margin-left: -5px;" class="type-yu">
                            <div class="dis10"></div>
                            <div>
                            <ul class="paysel">
                        <?php $payment_list = C("payment")->get_payment();?>
                        <?php if($orderinfo['pay_status'] == 0){?>
                            <?php if($orderinfo['status'] == 0){?>
                            <span class="text-danger">已取消订单</span>
                            <?php }else{?>
                            <li><input type="radio"  value="0" name="paytype"  <?php if($this->userinfo['account']['balance'] < $orderinfo['amount']){?>disabled<?php }else{?>checked="checked"<?php }?> /> 余额支付   <span style="color:#999;">您当前余额:<font class="text-danger"><?php echo isset($this->userinfo['account']['balance'])?$this->userinfo['account']['balance']:"";?></font></span> 

                            <?php if($this->userinfo['account']['balance'] < $orderinfo['amount']){?>
                            &nbsp;&nbsp;您的余额不足,请<a href="<?php echo U("account@/finance/recharge?");?>order_no=<?php echo isset($orderinfo['order_no'])?$orderinfo['order_no']:"";?>&order_type=register_order&balance=<?php echo ($orderinfo['amount']-$this->userinfo['account']['balance']);?>"> <b>立即充值</b></a>
                            <?php }?>
                            </li>
                                                    
                            <?php $kkey = 0;?>
                            <?php foreach($payment_list as $key => $item){?>
                            <?php if($item['id']!=7){?>
                            <li><input type="radio" <?php if($this->userinfo['account']['balance'] < $orderinfo['amount'] && $kkey==0){?>checked="checked"<?php }?> value="<?php echo isset($item['id'])?$item['id']:"";?>" name="paytype"  /> <img src="<?php echo U("static@/public/images");?><?php echo isset($item['logo'])?$item['logo']:"";?>" alt="<?php echo isset($item['name'])?$item['name']:"";?>" /></li>
                            <?php }?>
                            <?php $kkey = $kkey +1;?>
                            <?php }?>
                            <?php }?>
                        <?php }elseif($orderinfo['pay_status'] == 2){?>
                            <li><span class="text-danger">已退款余额</span> <a href="<?php echo U("account@/finance/recharge_detail");?>" class="text-primary">查看资金</a></li>
                        <?php }else{?>
                           <li>
                           <?php if(empty($orderinfo['pay_type'])){?>
                            <span class="text-success">余额支付</span>
                           <?php }else{?>
                                   <?php foreach($payment_list as $key => $item){?>
                                   <?php if($orderinfo['pay_type'] == $item['id']){?>
                                  <img src="<?php echo U("static@/public/images");?><?php echo isset($item['logo'])?$item['logo']:"";?>" /> <span class="text-success"><?php echo isset($item['name'])?$item['name']:"";?></span>
                                   <?php }?>
                                   <?php }?>
                           <?php }?>
                           </li>
                        <?php }?>
                        </ul>
                        </div>

                            </div>
                            <div class="dis10"></div>
                    </div>
                        </div>
                        <div class="dis30"></div>
                        <div class="order-pay" style="border-top: 1px solid silver;text-align: right;">
                                <div class="dis10"></div>
                                订单商品：<span style="color: #FA7821;font-size: 16px;"><?php echo count($orderinfo['order_item']);?> </span>件&nbsp;&nbsp;&nbsp;应付总价：<span class="total-sum" style="color: #FA7821;font-size: 34px;"><?php echo isset($orderinfo['amount'])?$orderinfo['amount']:"";?></span>元&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php if($orderinfo['pay_status'] == 0){?>
                                    <?php if($orderinfo['status'] == 0){?>
                                    <span class="am-text-danger">已经取消</span>
                                    <?php }else{?>
                                        <button type="button" class="btn btn-info add-pay" style="position: relative;top:-12px;">立即支付</button>
                                    <?php }?>
                                <?php }elseif($orderinfo['pay_status'] == 1){?>
                                        <span style="color:green;">订单已支付,支付时间:<?php echo tTime::get_datetime('Y-m-d H:i:s',$orderinfo['pay_dateline']);?></span>
                                        <?php if(($orderinfo['send_status'] == 0)){?>
                                        <script src="" language="JavaScript"></script>
                                        <?php }else{?>
                                        <span class="text-primary">订单已完成</span>
                                        <?php }?>
                                <?php }else{$orderinfo['pay_status'] == 2?>
                                    <span class="text-danger">已退款余额</span> <a href="<?php echo U("account@/finance/recharge_detail");?>" class="text-primary">查看资金</a>
                                <?php }?>
                        </div>
                        <div class="my-pay-success"></div>
                </div>
        </div>
        </div>
        <div class="dis60"></div>
</div>
<!-- 登录框弹出 -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="margin-top: 250px">
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel" style="text-align: center"><i class="glyphicon glyphicon-ok" style="color: #5EB95E"></i> 恭喜您支付成功</h4>
                        </div>
                        <div class="modal-body" style="text-align: center;font-size: 18px;margin: 30px 0;">
                                <a href="<?php echo U("/domain/lists");?>" type="button" class="btn btn-default">继续购买域名 </a>
                                <a href="<?php echo U("/ucenter/index");?>" type="button" class="btn btn-warning">查看域名列表</a>
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
                $(".add-pay").unbind("click").bind("click",function(){
                        var order_no ="<?php echo isset($orderinfo['order_no'])?$orderinfo['order_no']:"";?>";
                        var paytype = $("input[name='paytype']:checked").val();
                        if (typeof paytype == "undefined") {
                                $.ui.error("请选择支付方式！");
                                return false;
                        }
                        $.ui.loading();
                        $.ajaxPassport({
                                url:"<?php echo U("/cart/order_pay");?>",
                                data:{order_no:order_no,pay_id:paytype},
                                success:function (res) {
                                        $.ui.close_loading();
                                        if (res.error == 0) {//支付成功
                                                if(!$.is_empty(res.callback)){
                                                    $.ui.success("正在跳转至支付...");
                                                    setTimeout(function(){
                                                        $.redirect(res.callback);
                                                    },1000);
                                                    return false;
                                                }else{
                                                        var domain_type = $("input[name='domain_type']").val();
                                                        if (domain_type == 3) {//域名转入
                                                            setTimeout(function(){
                                                                $.redirect("<?php echo U("/ucenter/transfer");?>");
                                                            },3000);
                                                            $('#myModal').modal();
                                                            $("#myModal").find(".modal-dialog").width(400);
                                                            var html = "<a href='<?php echo U("/ucenter/transfer");?>' type='button' class='btn btn-default'>继续添加 </a>"+" "+
                                                                             "<a href='<?php echo U("/ucenter/transfer");?>' type='button' class='btn btn-warning'>查看域名转入</a>";
                                                            $("#myModal").find(".modal-dialog .modal-body").html(html);
                                                        }else{//域名注册，续费
                                                            setTimeout(function(){
                                                                $.redirect("<?php echo U("/ucenter/index");?>");
                                                            },5000);
                                                            $('#myModal').modal();
                                                            $("#myModal").find(".modal-dialog").width(400);
                                                        }
                                                        $.exeJS("<?php echo U("domain@/api/Order.SendRegDomain?order_no=");?>"+order_no);
                                                }
                                        }else{//支付失败
                                                $.ui.error(res.message);
                                        }
                                }
                        });
                })
        });
</script>

</body>
</html>