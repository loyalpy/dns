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
            <li><a href="<?php echo U("/monitor/monitor");?>">实时监控&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/monitor/warning");?>" >报警信息&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/monitor/monitor_set");?>">监控设置&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/monitor/monitor_option_domain");?>"  class="cur">添加监控&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right" >
    <div class="fabu-stepbox" style="margin-left:80px;">
        <ul class="clear">
            <li class="current"><i class="i1">1</i><span>选择域名</span><b></b></li>
            <li><i class="i2">2</i><span>查看记录</span><b></b></li>
            <li ><i class="i3">3</i><span>监控设置</span></li>
        </ul>
    </div>
    <div class="check-domain"></div>
</div>
</div>
<div class="am-cf"></div>
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
<script language="javascript" src="<?php echo U("static@/javascript/mockjax/jquery.mockjax.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/mockjax/jquery.autocomplete.js");?>"></script>
<!--八戒监控 -- 选择域名-->
<script type="text/template" id="tpl_monitor_check_tpl">
    <#macro rowedit data>
        <div class="am-input-group">
            <span class="am-input-group-label"><i class="am-icon-globe"></i></span>
            <input type="text" name="domain" id="domain-monitor" class="am-form-field" <#if (data.domain)>value="${data.domain}"</#if> placeholder="请您填写需要监控的域名" style="font-size: 14px;width: 276px;">
        </div>
        <div class="dis20"></div>
        <div class="am-input-group">
            <span class="am-input-group-label"><i class="am-icon-at"></i></span>
            <input type="text" name="record" id="record-monitor" class="am-form-field" <#if (data.RRname && data.domain)>value="${data.RRname}.${data.domain}"</#if> placeholder="请您填写需要监控的主机记录" style="font-size: 14px;width:276px;">
        </div>
        <div class="dis20"></div>
        <button type="button" class="am-btn am-btn-default am-radius  monitor_option" style="font-size: 15px;width: 324px;">下一步</button>
        <button type="button" class="am-btn am-btn-warning am-radius  check-domain-next monitor_option_domain" style="display:none;font-size: 15px;width: 324px;">下一步</button>
    </#macro>
</script>
<script language="javaScript">
    var supper_domain = '<?php echo "(".implode(")|(",tValidate::support_domain()).")";?>';
    var domain = "<?php echo isset($domain)?$domain:"";?>";
    var RRname = "<?php echo isset($RRname)?$RRname:"";?>";
    $(function(){
        domain_list();
    });
    //获取域名列表
    var domain_get_records = function(domain){
        $.ajaxPassport({
            url:"<?php echo U("/api/DomainRecord.GetAllNoMonitor");?>",
            success:function(res1){
                if (res1.data.list.length) {
                    var htmlstr = "<div class='am-text-xs' style='padding:8px 12px;'>记录值不存在或已添加监控，请选择其他记录</div>";
                }else{
                    htmlstr = "<div class='am-text-xs' style='padding:8px 12px;'>您还没有添加此域名记录<a href='<?php echo U("/domains/dns/");?>"+domain+"'>点击添加</a></div>";
                }
                $('#record-monitor').autocomplete({
                    lookup: res1.data.list,
                    minChars:0,
                    showNoSuggestionNotice:true,
                    noSuggestionNotice:htmlstr,
                    onSelect:function(){
                        $(".monitor_option").hide();
                        $(".monitor_option_domain").show();
                    },
                });
            },
            data:{domain:domain}
        });
    }
    var domain_list = function(){
        //$.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("/api/Domain.GetAllByUid");?>",
            success:function(res){

                $.ui.close_loading();
                res.data.domain = domain;
                res.data.RRname = RRname;
                var listhtml = ""+ easyTemplate($("#tpl_monitor_check_tpl").html(),res.data);
                $(".check-domain").html(listhtml);

                //去除点击焦点
                $("a,button").bind("focus",function(){
                    $(this).blur();
                });

                if (!$.is_empty(domain) && !$.is_empty(RRname)) {
                    $(".monitor_option").hide();
                    $(".monitor_option_domain").show();
                }

                //jquery自动完成加载类
                $('#domain-monitor').autocomplete({
                    lookup: res.data.list,
                    minChars:0,
                    showNoSuggestionNotice:true,
                    noSuggestionNotice:"<div class='am-text-xs' style='padding:8px 12px;'>未添加该域名<a href='<?php echo U("/domains/add");?>'>点击添加</a></div>",
                    onNoSuggestionNotice:function(){
                        $('#record-monitor').autocomplete({
                            lookup: [],
                        });
                    },
                    lookupFilter:function(suggestion, originalQuery, queryLowerCase) {
                        var re = new RegExp($.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
                        return re.test(suggestion.value);
                    },
                    onSelect:function(res){
                        $("#record-monitor").val("");
                        domain_get_records(res.value);
                    },
                });

                //下一步,查看记录
                $(".check-domain-next").unbind("click").bind("click",function(){
                    var   domains = $(".check-domain input[name='domain']").val();
                    var   record = $(".check-domain input[name='record']").val();
                    if ($.is_empty(domains)) {
                        $.ui.error("请选择域名！");
                        return false;
                    }
                    if(!$.dns.is_domain2(domains)){
                        $.ui.error("非法域名！");
                        return false;
                    }
                    //判断域名是否存在
                    if (!$.in_array(domains,res.data.list)) {
                        $.ui.error("请先添加域名！");
                        return false;
                    }
                    if ($.is_empty(record)) {
                        $.ui.error("请选择主机记录！");
                        return false;
                    }
                    var recordArr = record.split(".");
                    if (recordArr.length <=1) {
                        $.ui.error("记录值非法");
                        return false;
                    }
                    if (recordArr.length >=4) {
                        $.ui.error("很抱歉，暂不支持三级以上域名的监控");
                        return false;
                    }
                    if(recordArr[0] === '@'){
                        recordArr[0] = '';
                    }
                    if(!$.in_array(recordArr[0],['','@','*']) && !$.dns.is_hostname(recordArr[0])){
                        $.ui.error("记录值非法");
                        return false;
                    }

                    var domainArr = domains.split(".");
                    for(i=0;i<domainArr.length;i++){
                        if(!$.in_array(domainArr[i],recordArr)){
                            $.ui.error("此域名下的主机记录值不正确");
                            return false;
                        }
                    }

                    //判断主机记录
                    //$.ui.loading();
                    $.ajaxPassport({
                        url:"<?php echo U("/api/DomainMonitor.CheckDomainRecord");?>",
                        success:function(res){
                            $.ui.close_loading();
                            if (res.status == 0) {
                                $.ui.error(res.msg);
                                return false;
                            }
                            domain = domains;
                            //添加监控第二步，查看记录
                            $.redirect("<?php echo U("/monitor/monitor_option_record?domain=");?>"+domains+"&RRname="+recordArr[0]);
                        },
                        data:{record:recordArr[0],domain:domains}
                    });
                });

                //初始化动作
                if(!$.is_empty(domain)){
                    domain_get_records(domain);
                }
            },
        });
    };
</script>
</body>
</html>