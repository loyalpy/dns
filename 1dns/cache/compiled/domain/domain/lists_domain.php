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
    <div class="domain-content">
        <div class="domain-c-nav" style="display: none;">
            <ul>
                <li class="active"><a href="<?php echo U("home@/");?>">八戒DNS首页</a></li>
                <li class="active"><a href="<?php echo U("domain@/");?>">域名注册</a></li>
                <li>域名查询结果</li>
            </ul>
            <div class="cl"></div>
        </div>
        <div class="dis10"></div>
        <div class="domain-c">
            <div class="domain-m">
            <ul>
                <li class="c1 <?php if($type == 0){?>active<?php }?>" ><a href="javascript:;">单选模式</a></li>
                <li class="c2 <?php if($type == 1){?>active<?php }?>" ><a href="#collapseExample" class="more-check-m" aria-expanded="false" aria-controls="collapseExample" data-toggle="collapse">多选模式</a></li>
            </ul>
            </div>
            <div class="domain-p"><a href="<?php echo U("/domain/price_info");?>" target="_blank">价格预览</a> <cite class="glyphicon glyphicon-paperclip"></cite>
            </div>
            <div class="cl"></div>
        </div>
        <div class="domain-s-box">
            <form  method="post" action="<?php echo U("/domain/lists");?>" >
                <div class="ser">
                    <input type="text" name="domain" class="search" value="<?php echo isset($domain)?$domain:"";?>" autocomplete="off" placeholder="输入您想注册的域名，例如：8jdns">
                    <input type="hidden" name="suffix" value="<?php echo isset($suffix)?$suffix:"";?>"/>
                    <input type="hidden" name="type" value="<?php echo isset($type)?$type:"";?>"/>
                    <input type="hidden" name="suffixs" value=""/>
                    <a href="javascript:void(0)" class="show-multiple"><span class="suffix"><?php echo isset($suffix)?$suffix:"";?></span> <span class="caret"></span></a>
                </div>
                <div class="collapse" id="collapseExample" >
                    <div class="well">
                        <ul>
                            <?php foreach($suffix_arr as $key => $item){?>
                            <li><input type="checkbox" name="suffix-more" value="<?php echo isset($item)?$item:"";?>" <?php if(in_array($item,array(".com",".net",".cn")) || in_array($item,$suffixs)){?>checked<?php }?>/><span><?php echo isset($item)?$item:"";?></span></li>
                            <?php }?>
                        </ul>
                        <div class="cl"></div>
                    </div>
                </div>
                <div class="ser-btn">
                    <input type="submit" class="search-btn" value=""/>
                </div>
            </form>
            <div class="search-extension">
                <ul class="search-extension-list">
                    <?php foreach($suffix_arr as $key => $item){?>
                    <li class="select-list hot_tlds"><?php echo isset($item)?$item:"";?></li>
                    <?php }?>
                </ul>
                <div class="cl"></div>
            </div>
        </div>
        <div class="dis20"></div>
        <div class="serch-result-box">
            <div class="serch-result">
                <div class="serch-re-title"><strong>查询结果</strong></div>
                <div class="serch-re-1" <?php if(!empty($domain)){?>style="display:none"<?php }?>>查询内容为空，请输入您要查询的内容！</div>
                <div class="mod-box-bd" <?php if(empty($domain)){?>style="display:none"<?php }?>>
                    <div class="mod-sub-box hot-list">
                        <div class="mod-sub-box-bd">
                            <ul class="result-list select-rs-list">
                                <?php if(count($suffixs)>0){?>
                                    <?php foreach($suffixs as $key => $item){?>
                                    <li>
                                        <div class="d-ib domain-cell">
                                            <div class="domain sold"><span class="d"><?php echo isset($domain)?$domain:"";?><?php echo isset($item)?$item:"";?></span><span class="s" style="display: none;"><?php echo isset($item)?$item:"";?></span></div>
                                        </div>
                                        <div class="mod-select-else-fr">
                                            <div class="d-ib price-cell">正在查询</div>
                                            <div class="d-ib action-cell"><a href="/domain/check_info?domain=<?php echo isset($domain)?$domain:"";?><?php echo isset($item)?$item:"";?>" target="_blank">查询注册信息</a></div>
                                        </div>
                                    </li>
                                    <?php }?>
                                <?php }else{?>
                                <li>
                                    <div class="d-ib domain-cell">
                                        <div class="domain sold"><span class="d"><?php echo isset($domain)?$domain:"";?><?php echo isset($suffix)?$suffix:"";?></span><span class="s" style="display: none;"><?php echo isset($suffix)?$suffix:"";?></span></div>
                                    </div>
                                    <div class="mod-select-else-fr">
                                        <div class="d-ib price-cell">正在查询</div>
                                        <div class="d-ib action-cell"><a href="/domain/check_info?domain=<?php echo isset($domain)?$domain:"";?><?php echo isset($item)?$item:"";?>" target="_blank">查询注册信息</a></div>
                                    </div>
                                </li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="serch-re-title" <?php if(empty($domain)){?>style="display:none"<?php }?>><strong>您还可以选择</strong></div>
                <div class="mod-box-bd" <?php if(empty($domain)){?>style="display:none"<?php }?>>
                    <div class="mod-sub-box hot-list">
                        <div class="mod-sub-box-bd">
                            <ul class="result-list select-rs-list">
                                <?php foreach($suffix_arr as $key => $item){?>
                                    <?php if(count($suffixs)>0){?>
                                        <?php if(!in_array($item,$suffixs)){?>
                                        <li <?php if($key > 18-(count($suffixs)-3)){?>style="display:none" class="domain-more-h"<?php }?>>
                                            <div class="d-ib domain-cell">
                                                <div class="domain sold"><span class="d"><?php echo isset($domain)?$domain:"";?><?php echo isset($item)?$item:"";?></span><span class="s" style="display: none;"><?php echo isset($item)?$item:"";?></span></div>
                                            </div>
                                            <div class="mod-select-else-fr">
                                                <div class="d-ib price-cell">正在查询</div>
                                                <div class="d-ib action-cell"><a href="/domain/check_info?domain=<?php echo isset($domain)?$domain:"";?><?php echo isset($item)?$item:"";?>" target="_blank">查询注册信息</a></div>
                                            </div>
                                        </li>
                                        <?php }?>
                                    <?php }else{?>
                                        <?php if($item != $suffix){?>
                                        <li <?php if($key > 12){?>style="display:none" class="domain-more-h"<?php }?>>
                                            <div class="d-ib domain-cell">
                                                <div class="domain sold"><span class="d"><?php echo isset($domain)?$domain:"";?><?php echo isset($item)?$item:"";?></span><span class="s" style="display: none;"><?php echo isset($item)?$item:"";?></span></div>
                                            </div>
                                            <div class="mod-select-else-fr">
                                                <div class="d-ib price-cell">正在查询</div>
                                                <div class="d-ib action-cell"><a href="/domain/check_info?domain=<?php echo isset($domain)?$domain:"";?><?php echo isset($item)?$item:"";?>" target="_blank">查询注册信息</a></div>
                                            </div>
                                        </li>
                                        <?php }?>
                                    <?php }?>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                    <div class="domain-more">查看更多后缀 <i class="glyphicon glyphicon-chevron-down"></i></div>
                </div>
            </div>
            <div class="serch-cart">
                <div class="serch-c-title"><strong>购物车(<span class="cart-num"><?php echo count($cart);?></span>)</strong></div>
                <div class="serch-c-1 a-c-1" <?php if(count($cart) > 0){?>style="display: none"<?php }?>>
                    <cite class="glyphicon glyphicon-shopping-cart"></cite>
                    <span>尚未选择域名</span>
                </div>
                <div class="pending-buy a-c-2" <?php if(count($cart) <= 0){?>style="display: none"<?php }?>>
                    <ul class="pen-d" style="display: block;">
                        <?php foreach($cart as $key => $item){?>
                        <li><span class="pen-d-s"><span class="ellipsis"><?php echo isset($item['domain'])?$item['domain']:"";?></span></span><a href="javascript: void(0);" class="btn-del"><i class="glyphicon glyphicon-remove"></i></a></li>
                        <?php }?>
                    </ul>
                    <div class="buy-aciton" style="display: block;"><a href="#" role="button" class=" orange">
                        <a type="button" class="btn btn-warning" href="/cart/cart">立即购买</a></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="cl"></div>
        <div class="dis10"></div>
        <button type="button" class="btn btn-warning batch-op-add">全部加入清单</button>
    </div>
</div>
<div class="dis60"></div>
<!-- 登录框弹出 -->
<!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" style="display: none">-->
    <!--Launch demo modal-->
<!--</button>-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">请登录</h4>
            </div>
            <div class="modal-body" style="text-align: center;font-size: 18px;margin: 30px 0;">
                <i class="glyphicon glyphicon-question-sign" style="font-size: 20px;color: #FF8800"></i>&nbsp;&nbsp;亲！请先登录再加入购物车!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a type="button" class="btn btn-primary" href="<?php echo U("account@/login?refer=domain./domain/lists?domain=");?><?php echo isset($domain)?$domain:"";?>">登录</a>
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
    var type = "<?php echo isset($type)?$type:"";?>";
    var uid = "<?php echo isset($uid)?$uid:"";?>";
    if (type == 1) {
        $("#collapseExample").addClass("in");
        $(".domain-s-box .ser").addClass("ser-more");
        $(".domain-s-box .ser").find(".show-multiple").hide();
    }
    //域名查询时
    $(".search-btn").click(function(){
        if (type == 1) {
            var suffixs = new Array();
            $("#collapseExample").find("li input[name='suffix-more']:checked").each(function(){
                var obj = this;
                suffixs.push($(obj).val());
            });
            if (suffixs.length <= 0) {
                alert("请选择域名需要搜索的域名后缀！");
                return false;
            }
            $("input[name='suffixs']").val(suffixs.join(","));
            return true;
        }else{
            $("input[name='suffixs']").val('');
            return true;
        }
    });
</script>
<script language="javascript" src="<?php echo U("")."skins/".$this->app."/".$this->skin."/js/domain_register.js";?>"></script>
</body>
</html>