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
<div class="combox">
<div class="records-nav">
    <ul>
        <li class="domain"><a href="<?php echo U("/domains/mylist");?>"><i class="am-icon-globe"></i></a> <span><?php if($domain['is_cn']==1){?><?php echo isset($domain['domain_cn'])?$domain['domain_cn']:"";?><?php }else{?><?php echo isset($domain['domain'])?$domain['domain']:"";?><?php }?></span></li>
        <li><a href="<?php echo U("/records/records_setline?domain=");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>">自定义线路</a></li>
        <li class="cur"><a href="<?php echo U("/records/records_set?domain=");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>">域名设置</a></li>
        <li ><a href="<?php echo U("/records/records_count?domain=");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>">解析量统计</a></li>
        <li><a href="<?php echo U("/domains/dns/");?><?php echo isset($domain['domain'])?$domain['domain']:"";?>">记录设置</a></li>
    </ul>
</div>
<div class="dis20"></div>
<div class="records-set-group am-form">
    <div class="records-set">
        <div class="records-set-header">
            <h4>基本资料</h4>
        </div>
        <div class="records-set-content">
            <div class="list list-show">
                <div class="title">别名绑定</div>
                <div class="intro bind">
                    <?php if(count($domain_bind) <= 0){?>未设置域名别名<?php }else{?>
                    <?php $a =implode(",",$domain_bind)?><?php echo $a;?>
                    <?php }?>
                </div>
                <div class="edit"><a href="javascript:void(0);" class="domain-bz">添加</a></div>
            </div>
            <div class="list list-edit" style="display: none;">
                <div class="title">别名绑定</div>
                <div class="intro">
                    <?php if(count($domain_bind) > 0){?>
                        <?php foreach($domain_bind as $key => $item){?>
                        <div>
                            <input type="text" name="domainBind" value="<?php echo isset($item)?$item:"";?>" style="width:46%;display: inline-block" placeholder="请填写域名"/>&nbsp;
                            <a class="del-domain-bind" style="cursor:pointer;">删除</a>
                            <div class="dis10"></div>
                        </div>
                        <?php }?>
                    <?php }else{?>
                    <div>
                        <input type="text" name="domainBind" value="" style="width:46%;" placeholder="请填写域名"/>
                        <div class="dis10"></div>
                    </div>
                    <?php }?>
                    <span class="am-badge am-badge-success am-radius add-domain-bind" style="cursor:pointer;"><i class="am-icon-plus"></i> 添加</span>
                    <div class="dis10"></div>
                    <span class="am-font-gray">什么是<a href="<?php echo U("home@/helper/article?tid=96");?>" target="_blank">别名绑定？</a></span><br/>
                    <div class="dis10"></div>
                    <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs domain-save-bind">保存</button>&nbsp;&nbsp;
                    <button type="button" class="am-btn am-btn-default am-radius am-btn-xs domain-cancel">取消</button>
                </div>
            </div>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-dotted" />
            <div class="list list-show">
                <div class="title">备注</div>
                <div class="intro bz">
                    <?php if(empty($domain['bz'])){?>点击添加备注<?php }else{?><?php echo isset($domain['bz'])?$domain['bz']:"";?><?php }?>
                </div>
                <div class="edit"><a href="javascript:void(0);" class="domain-bz">修改</a></div>
            </div>
            <div class="list list-edit" style="display: none;">
                <div class="title">备注</div>
                <div class="intro">
                    <textarea><?php echo isset($domain['bz'])?$domain['bz']:"";?></textarea>
                    <span>例如：这个域名在 8jdns 注册，是静态文件域名</span><br/>
                    <div class="dis10"></div>
                    <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs domain-save">保存</button>&nbsp;&nbsp;
                    <button type="button" class="am-btn am-btn-default am-radius am-btn-xs domain-cancel">取消</button>
                </div>
            </div>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-dotted" />
        </div>
    </div>
    <div class="dis10"></div>
    <div class="records-set">
        <div class="records-set-header">
            <h4>功能设置</h4>
        </div>
        <div class="records-set-content">
            <div class="list list-show">
                <div class="title">TTL默认值</div>
                <div class="intro ttl"><?php echo isset($domain['ttl'])?$domain['ttl']:"";?>/s &nbsp;&nbsp;&nbsp;<i class="am-icon-eye"></i> 域名默认ttl,TTL不能设置低于套餐最低ttl</div>
                <div class="edit"><a href="javascript:void (0);" class="domain-ttl">修改</a></div>
            </div>
            <div class="list list-edit" style="display: none;">
                <div class="title">TTL默认值</div>
                <div class="intro">
                    <input type="text" name="ttl" value="<?php echo isset($domain['ttl'])?$domain['ttl']:"";?>" style="width:30%;" onkeyup="value=value.replace(/[^\d]/g,'')"/> <span class="am-text-gray">(秒/s) 域名默认ttl,TTL不能设置低于套餐最低ttl</span>
                    <div class="dis10"></div>
                    <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs domain-save">保存</button>&nbsp;&nbsp;
                    <button type="button" class="am-btn am-btn-default am-radius am-btn-xs domain-cancel">取消</button>
                </div>
            </div>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-dotted" />
            <?php $ns_group = M("@domain_ns_group")->get_cache_by_ns($domain['ns_group']);?>
            <div class="list">
                <div class="title">套餐</div>
                <div class="intro"><?php echo isset($service_group['name'])?$service_group['name']:"";?></div>
                <div class="edit"><a href="<?php echo U("home@/product/buy");?>" target="_blank">升级套餐</a></div>
            </div>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-dotted" />
            <div class="list">
                <div class="title">NS组</div>
                <div class="intro">
                    <?php echo str_replace(';'," | ",$ns_group['ns']);?>
                </div>
                <div class="edit"></div>
            </div>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-dotted" />
        </div>
    </div>
    <div class="dis10"></div>
    <div class="records-set">
        <div class="records-set-header">
            <h4>安全设置</h4>
        </div>
        <div class="records-set-content">
            <div class="list list-show">
                <div class="title">域名解析管理密码</div>
                <div class="intro password">******* &nbsp;&nbsp;&nbsp;设置用于单独管理解析</div>
                <div class="edit"><a href="javascript:void (0);" class="domain-ttl">修改</a></div>
            </div>
            <div class="list list-edit" style="display: none;">
                <div class="title">域名解析管理密码</div>
                <div class="intro">
                    <input type="password" name="password" value="" style="width:30%;" minlength="6" maxlength="18" /> 
                    <div class="dis10"></div>
                    <button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs domain-save">保存</button>&nbsp;&nbsp;
                    <button type="button" class="am-btn am-btn-default am-radius am-btn-xs domain-cancel">取消</button>
                </div>
            </div>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-dotted" />
            <div class="list">
                <div class="title">解析状态</div>
                <div class="intro" id="intro"><?php if($domain['status']==1){?><font color="#f37b1d"><i class="am-icon-unlock"></i> 已开启</font><?php }else{?><i class="am-icon-unlock-alt"></i> 已锁定<?php }?></div>
                <div class="edit" id="edit"><a href=""><button type="button" data-status="<?php echo isset($domain['status'])?$domain['status']:"";?>" class="am-btn <?php if($domain['status']==1){?>am-btn-secondary<?php }else{?>am-btn-success<?php }?> am-radius am-btn-xs domain-status"><?php if($domain['status']==1){?>暂停<?php }else{?>启用<?php }?></button></a></div>
            </div>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-dotted" />
            <div class="list">
                <div class="title">操作日志</div>
                <div class="intro">查看域名操作日志</div>
                <div class="edit"><a href=""><button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs domain-show" data-domain_id="<?php echo isset($domain['domain_id'])?$domain['domain_id']:"";?>">查看</button></a></div>
            </div>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-dotted" />
            <div class="list">
                <div class="title">记录导出</div>
                <div class="intro">将所有的记录导出为文本格式</div>
                <div class="edit"><a href="<?php echo U("/records/Export?domain");?>=<?php echo isset($domain['domain'])?$domain['domain']:"";?>" target="_blank"><button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs">导出</button></a></div>
            </div>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-dotted" />
        </div>
    </div>
    <div class="dis20"></div>
    <button type="button" class="am-btn am-btn-default am-radius am-btn-xs domain-del">删除域名</button>
</div>
<div class="dis20"></div>
</div>
<div class="my-domian-log1"></div>
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
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
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
<!--域名升级套餐-->
<script type="text/template" id="tpl_domain_log">
    <#macro rowedit data>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" style="text-align: left;margin-left: 8px;"><i class="am-icon-book" style="color: olivedrab"></i> 域名日志
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a></div>
                <div class="am-modal-bd">
                    <table class="am-table am-table-striped" style="font-size: 14px;text-align: left">
                        <col width="150px"/>
                        <col width="200px"/>
                        <col width="120px"/>
                        <col/>
                        <thead>
                        <tr>
                            <th>操作时间</th>
                            <th>操作IP</th>
                            <th>操作项</th>
                            <th>说明</th>
                        </tr>
                        </thead>
                        <tbody>
                        <#if (data.list.length > 0)>
                            <#list data.list as domain>
                            <tr>
                                <td class="font-gray">${domain.dateline}</td>
                                <td class="font-gray">${domain.ipaddr}</td>
                                <td class="font-gray">${domain.modi_item}</td>
                                <td class="font-gray">${domain.modi_log}</td>
                            </tr>
                            </#list>
                            <#else>
                                <tr><td colspan="4">暂无操作记录！</td></tr>
                        </#if>
                        </tbody>
                    </table>
                </div>
                <div class="pageber">${data.pagebar}</div>
                <div class="dis30"></div>
            </div>
        </div>
    </#macro>
</script>
<script type="text/javascript">
    var supper_domain = '<?php echo "(".implode(")|(",tValidate::support_domain()).")";?>';
    var domain_id = <?php echo isset($domain['domain_id'])?$domain['domain_id']:"";?>;
    $(function(){
        //编辑
        $(".list-show").unbind("click").bind("click",function(){
            $(this).hide();
            var hideStr = $(this).find(".title").html();
            if (hideStr == '别名绑定') {
                $(this).parent().find(".list-edit:first").show();
            }else{
                $(this).parent().find(".list-edit:last").show();
            }
        });
        //取消编辑
        $(".domain-cancel").unbind("click").bind("click",function(){
            $(this).parent().parent().hide();
            var hideStr = $(this).parent().parent().find(".title").html();
            if (hideStr == '别名绑定') {
                $(this).parent().parent().parent().find(".list-show:first").show();
            }else{
                $(this).parent().parent().parent().find(".list-show:last").show();
            }
        });
        //保存
        $(".domain-save").unbind("click").bind("click",function(){
            var bz = $(".intro textarea").val();
            var ttl = $('.intro input[name="ttl"]').val();
            var password = $('.intro input[name="password"]').val();
            var obj = $(this).parent().parent();
            $.ajaxPassport({
                url:U("/api/Domain.Save"),
                success:function(res){
                    if(res.status!=1){
                        $.ui.error(res.msg);
                    }else{
                        $.ui.success(res.msg);
                        obj.hide();
                        if ($.is_empty(bz)) {
                            obj.parent().find(".list-show .bz").html("点击添加备注");
                        }else{
                            obj.parent().find(".list-show .bz").html(bz);
                        }
                        obj.parent().find(".list-show .ttl").html(ttl+"/s"+"&nbsp;&nbsp;&nbsp;<i class=\"am-icon-eye\"></i> 域名默认ttl,TTL不能设置低于套餐最低ttl");
                        obj.parent().find(".list-show .password").html("******* &nbsp;&nbsp;&nbsp;设置用于单独管理解析");
                        obj.parent().find(".list-show:last").show();
                    }
                },
                data:{domain_id:domain_id,bz:bz,ttl:ttl,password:password}
            })
        });
        //点击添加多个域名别名绑定事件
        $(".add-domain-bind").unbind("click").bind("click",function () {
            //判断最多添加十条输入框
            var len = $("input[name='domainBind']").length;
            if (len == 10) {
                $.ui.error("输入个数已达上限！");
                return false;
            }
            var html =  "<div>" +
                              "<input type=\"text\" name=\"domainBind\" value=\"\" style=\"width:46%;display: inline-block;\"  placeholder=\"请填写域名\"/>&nbsp;&nbsp;" +
                              "<a class=\"del-domain-bind\" data-status=\"1\" style=\"cursor:pointer;\">删除</a>" +
                              "<div class=\"dis10\"></div>" +
                              "</div>";
            $(this).before(html);
            $(".del-domain-bind").unbind("click").bind("click",function () {
                del_domain_bind(this);
            });
            enter_bind_domain();
        });
        //删除域名别名
        $(".del-domain-bind").unbind("click").bind("click",function () {
            del_domain_bind(this);
        });
        //域名别名绑定
        $(".domain-save-bind").unbind("click").bind("click",function(){
            //域名别名
            var domainBind = new Array;
            $("input[name='domainBind']").each(function () {
                var bindVal = $(this).val();
                if (!$.is_empty(bindVal) && $.dns.is_domain(bindVal)) {
                    domainBind.push(bindVal);
                }
            });
            //判断域名别名是否合法
            if (domainBind.length != $("input[name='domainBind']").length) {
                $.ui.error("域名不能为空或非法域名！");
                return false;
            }
            //过滤重复域名
            if (domainBind.length != $.unique(domainBind).length) {
                $.ui.error("不能重复添加域名！");
                return false;
            }
            domainBind = $.unique(domainBind).join(",");
            var obj = $(this).parent().parent();
            $.ajaxPassport({
                url:U("/api/Domain.DomainBind"),
                success:function(res){
                    if(res.status!=1){
                        $.ui.error(res.msg);
                    }else{
                        $.ui.success(res.msg);
                        obj.hide();
                        if ($.is_empty(domainBind)) {
                            obj.parent().find(".list-show .bind").html("未设置域名别名");
                        }else{
                            obj.parent().find(".list-show .bind").html(domainBind);
                        }
                        obj.parent().find(".list-show:first").show();
                    }
                },
                data:{domain_id:domain_id,domainBind:domainBind}
            })
        });
        //更换解析状态
        $(".domain-status").unbind("click").bind("click",function(){
            if(confirm("你确定执行该操作吗？")){
                var status =$(this).data("status")==1?0:1;
                $.ajaxPassport({
                    url:U("/api/Domain.Status"),
                    success:function(res){
                        if(res.status!=1){
                            $.ui.error(res.msg);
                        }else{
                            $.ui.success(res.msg);
                            if(status ==1){
                                //改为暂停
                                $("#intro").html('<font color="#f37b1d"><i class="am-icon-unlock"></i> 已开启</font>');
                                $("#edit").html('<a href=""><button type="button" data-status="1" class="am-btn am-btn-secondary am-radius am-btn-xs domain-status">暂停</button></a>');
                            }else{
                                //改为启用
                                $("#intro").html('<font color="#f37b1d"><i class="am-icon-unlock-alt"></i> 已锁定</font>');
                                $("#edit").html('<a href=""><button type="button" data-status="0" class="am-btn am-btn-success am-radius am-btn-xs domain-status">启用</button></a>');
                            }
                            $(".domain-status").click();
                        }
                    },
                    data:{status:status,domain_id:domain_id}
                })
                return false;
            }
        });
        //删除域名
        $(".domain-del").unbind("click").bind("click",function(){
            if(confirm("你确定要删除该域名吗？删除该域名的同时将删除该域名下的所有的域名记录！")){
                $.ajaxPassport({
                    url:U("/api/Domain.Del"),
                    success:function(res){
                        if(res.status != 1){
                            $.ui.error(res.msg);
                        }else{
                            $.ui.success(res.msg);
                            setTimeout(function(){
                                window.location='<?php echo U("/domains");?>';
                            },500);
                        }
                    },
                    data:{domain_id:domain_id}
                });
                return false;
            }
        });
        //操作日志
        $(".domain-show").unbind("click").bind("click",function(){
            var domain_id = $(this).data("domain_id");
            edit_log_func(1,domain_id);
            return false;
        });
        enter_bind_domain();
    });
    //删除域名别名
    var del_domain_bind = function (obj) {
        var len = $("input[name='domainBind']").length;
        var domainBind = $(obj).parent().find("input[name='domainBind']").val();
        var status = $(obj).data("status");
        if (!$.is_empty(domainBind) && status != 1) {
            $.ajaxPassport({
                url:"<?php echo U("/api/Domain.DelDomainBind");?>",
                success:function(res){
                    if(res.status == 1){
                        $.ui.success(res.msg);
                        $(obj).parent().remove();
                    }else{
                        $.ui.error(res.msg);
                    }
                },
                data:{domain_id:domain_id,domainBind:domainBind}
            });
        }else{
            $(obj).parent().remove();
        }
        //如果删除后没有域名别名，则刷新本页面
        if (len == 1) {
            setTimeout(function () {
                location.reload();
            },600);
        }
    }
    //域名操作日志
    var edit_log_func = function (page,domain_id) {
        $.ajaxPassport({
            url:"<?php echo U("/api/Domain.Log");?>",
            success:function(res){
                if(res.status == 1){
                    var html = "" + easyTemplate($("#tpl_domain_log").html(),res.data);
                    $(".my-domian-log1").html(html);
                    $(".my-domian-log1").find('#doc-modal-1').modal({width: 1100,height:650});
                }
            },
            data:{page:page,domain_id:domain_id}
        });
    }
    //ENTER键触发保存提交事件
    var enter_bind_domain = function () {
        $("input").unbind("keypress").bind("keypress",function (e) {
            var obj  = this;
            var now_t = $(obj).parent().parent();
            if(e.keyCode == 13){
                now_t.find(".domain-save-bind").click();
            }
        })
    }
</script>
</body>
</html>