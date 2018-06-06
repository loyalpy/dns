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
            <li><a href="<?php echo U("/ucenter/profile_basic");?>" class="cur">个人资料&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_basic_com");?>">企业资料&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_msg");?>" >系统通知&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
            <li><a href="<?php echo U("/ucenter/profile_passwd");?>"  >修改密码&nbsp;&nbsp;<cite class="am-icon-btn am-icon-angle-right"></cite></a></li>
        </ul>
    </div>
</div>
<div class="am-uc-right">
    <div>
        <h1><span class="list_tit_name">个人管理<span style="font-size: 14px;color: #5EB95E">(当前类型：<?php if(($userinfo['utype']==2)){?>企业账户<?php }else{?>个人账户<?php }?>)</span></span></h1>
    </div>
    <hr/>
    <div class="dis30"></div>
    <div class="am-msg-content">
        <form class="am-form am-form-horizontal" name="reg_testdate">
            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;">真实姓名</label>
                <div class="am-u-sm-2">
                    <input type="text" class="am-form-field am-radius" name="realname" value="<?php echo isset($userinfo['realname'])?$userinfo['realname']:"";?>">
                    <small>请填写真实姓名</small>
                </div>
                <div class="am-u-sm-1"></div>
            </div>

            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;">性别</label>
                <div class="am-u-sm-9">
                    <input type="radio" name="sex" value="0" <?php if(($userinfo['sex']==0)){?>checked="checked"<?php }?>/> 保密&nbsp;&nbsp;
                    <input type="radio" name="sex" value="2" <?php if(($userinfo['sex']==2)){?>checked="checked"<?php }?>/> 男&nbsp;&nbsp;
                    <input type="radio" name="sex" value="1" <?php if(($userinfo['sex']==1)){?>checked="checked"<?php }?>/> 女
                </div>
            </div>

            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;">生日</label>
                <div class="am-u-sm-9 birthday">
                    <select class=" am-input-sm" name="year" onChange="YYYYDD(this.value)">
                        <option value="">请选择 年</option>
                    </select>
                    <select class=" am-input-sm" name="month" onChange="MMDD(this.value)">
                        <option value="">选择 月</option>
                    </select>
                    <select class=" am-input-sm" name="day">
                        <option value="">选择 日</option>
                    </select>
                </div>
            </div>

            <!--<div class="am-form-group">-->
                <!--<label class="am-u-sm-2" style="margin-right: -50px;">电子邮件</label>-->
                <!--<div class="am-u-sm-9">-->
                    <!--<input type="email" class="am-form-field am-radius" name="email" value="<?php echo isset($userinfo['email'])?$userinfo['email']:"";?>">-->
                    <!--<small>请输入您的电子邮件</small>-->
                <!--</div>-->
            <!--</div>-->

            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;">公司名称</label>
                <div class="am-u-sm-5">
                    <input type="text" class="am-form-field am-radius" name="company" value="<?php echo isset($userinfo['company'])?$userinfo['company']:"";?>">
                    <small>请输入您的公司名称</small>
                </div>
                <div class="am-u-sm-1"></div>
            </div>

            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;">岗位</label>
                <div class="am-u-sm-5">
                    <input type="text" class="am-form-field am-radius" name="depart" value="<?php echo isset($userinfo['depart'])?$userinfo['depart']:"";?>">
                    <small>请输入您的岗位</small>
                </div>
                <div class="am-u-sm-1"></div>
            </div>

            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;">地区</label>
                <div id="city" class="am-u-sm-9 birthday">
                    <select class="prov am-input-sm" name="s_province"></select>
                    <select class="city am-input-sm" name="s_city"></select>
                    <select class="dist am-input-sm" name="s_county"></select>
                </div>
            </div>

            <div class="am-form-group">
                <label  class="am-u-sm-2" style="margin-right: -50px;">签名</label>
                <div class="am-u-sm-5">
                    <textarea class="" rows="5" name="signname"><?php echo isset($userinfo['signname'])?$userinfo['signname']:"";?></textarea>
                    <small>请用一句话表述您的个性</small>
                </div>
                <div class="am-u-sm-1"></div>
            </div>

            <div class="am-form-group">
                <label class="am-u-sm-2" style="margin-right: -50px;"></label>
                <div class="am-u-sm-9">
                    <button type="button" class="am-btn am-btn-success am-radius msg-save">保存修改</button>
                </div>
            </div>
        </form>
    </div>
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
<script type="text/javascript" src="<?php echo U("/static/javascript/area/jquery.cityselect.js");?>"></script>
<!--提交修改事件-->
<script type="text/javascript">
    $(".am-msg-content").find("button,a").bind("focus",function(){
        $(this).blur();
    });
    $(".msg-save").unbind("click").bind("click",function(){
        var realname = $("input[name='realname']").val();
        var sex = $("input[name='sex']:checked").val();

        //出生日期
        var year = $("select[name='year']").val();
        var month = $("select[name='month']").val();
        var day = $("select[name='day']").val();
        if ($.is_empty(year) || $.is_empty(month) || $.is_empty(day)) {
            alert("请选择日期");
            return false;
        }
        var birthdays = year+"-"+(month>10?month:"0"+month)+"-"+(day>10?day:"0"+day);
        //地区
        var county = $.is_empty($("select[name='s_county']").val())?'':","+$("select[name='s_county']").val();
        var areas = $("select[name='s_province']").val()+","+$("select[name='s_city']").val()+county;

//        var email = $("input[name='email']").val();
        var company = $("input[name='company']").val();
        var depart = $("input[name='depart']").val();
        var signname = $("textarea").val();
        $.ajaxPassport({
            url:"<?php echo U("/ucenter/profile_basic");?>",
            success:function(res){
                if (res.error == 1) {
                    $.ui.error(res.message);
                } else {
                    $.ui.success(res.message);
                }
            },
            data:{realname:realname,sex:sex,company:company,depart:depart,signname:signname,birthdays:birthdays,areas:areas,id:1}
        });
    });
</script>
<!--三级地区联动-->
<script type="text/javascript">
    var prov = $.is_empty('<?php echo isset($userinfo['area'][0])?$userinfo['area'][0]:"";?>')?"浙江省":"<?php echo isset($userinfo['area'][0])?$userinfo['area'][0]:"";?>";
    var city = $.is_empty('<?php echo isset($userinfo['area'][1])?$userinfo['area'][1]:"";?>')?"杭州":"<?php echo isset($userinfo['area'][1])?$userinfo['area'][1]:"";?>";
    var dist = $.is_empty('<?php echo isset($userinfo['area'][2])?$userinfo['area'][2]:"";?>')?"西湖区":"<?php echo isset($userinfo['area'][2])?$userinfo['area'][2]:"";?>";
    $("#city").citySelect({
        url:"<?php echo U("/static/javascript/area/city.min.js");?>",
        prov:prov, //省份
        city:city, //城市
        dist:dist, //区县
        nodata:"none" //当子集无数据时，隐藏select
    });
</script>
<!--三级日期联动-->
<script type="text/javascript">
    function YYYYMMDDstart(){
        MonHead = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        //先给年下拉框赋内容
        if (<?php echo isset($userinfo['birthday'][0])?$userinfo['birthday'][0]:"";?> != 1970) {
            var y  = <?php echo isset($userinfo['birthday'][0])?$userinfo['birthday'][0]:"";?>;
            var ym = <?php echo isset($userinfo['birthday'][1])?$userinfo['birthday'][1]:"";?>-1;
            var ymd = <?php echo isset($userinfo['birthday'][2])?$userinfo['birthday'][2]:"";?>;
        }else{
            var y  = new Date().getFullYear();
            var ym  = new Date().getMonth();
            var ymd  = new Date().getDate();
        }
        for (var i = (y-30); i < (y+30); i++) //以今年为准，前30年，后30年
            document.reg_testdate.year.options.add(new Option(" "+ i +" 年", i));

        //赋月份的下拉框
        for (var i = 1; i < 13; i++)
            document.reg_testdate.month.options.add(new Option(" " + i + " 月", i));

        document.reg_testdate.year.value = y;
        document.reg_testdate.month.value = ym + 1;
        var n = MonHead[ym];
        var YYYYvalue = '';
        if (new Date().getMonth() ==1 && IsPinYear(YYYYvalue)) n++;
        writeDay(n); //赋日期下拉框
        document.reg_testdate.day.value = ymd;
    }
    if(document.attachEvent)
        window.attachEvent("onload", YYYYMMDDstart);
    else
        window.addEventListener('load', YYYYMMDDstart, false);
    function YYYYDD(str) //年发生变化时日期发生变化(主要是判断闰平年)
    {
        var MMvalue = document.reg_testdate.month.options[document.reg_testdate.month.selectedIndex].value;
        if (MMvalue == ""){ var e = document.reg_testdate.day; optionsClear(e); return;}
        var n = MonHead[MMvalue - 1];
        if (MMvalue ==2 && IsPinYear(str)) n++;
        writeDay(n)
    }
    function MMDD(str)   //月发生变化时日期联动
    {
        var YYYYvalue = document.reg_testdate.year.options[document.reg_testdate.year.selectedIndex].value;
        if (YYYYvalue == ""){ var e = document.reg_testdate.day; optionsClear(e); return;}
        var n = MonHead[str - 1];
        if (str ==2 && IsPinYear(YYYYvalue)) n++;
        writeDay(n)
    }
    function writeDay(n)   //据条件写日期的下拉框
    {
        var e = document.reg_testdate.day; optionsClear(e);
        for (var i=1; i<(n+1); i++)
            e.options.add(new Option(" "+ i + " 日", i));
    }
    function IsPinYear(year)//判断是否闰平年
    {
        return(0 == year%4 && (year%100 !=0 || year%400 == 0));
    }
    function optionsClear(e)
    {
        e.options.length = 1;
    }
</script>
</body>
</html>