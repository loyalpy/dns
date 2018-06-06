<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">
  <title><?php echo isset($site['seo_title'])?$site['seo_title']:"";?></title>
  
  <meta name="keywords" content="<?php echo isset($site['seo_keyword'])?$site['seo_keyword']:"";?>" />
  <meta name="description" content="<?php echo isset($site['seo_description'])?$site['seo_description']:"";?>" />
      <link href="<?php echo U("/static/javascript/bootstrap/css/bootstrap.min.css");?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_admin.css";?>"  rel="stylesheet" type="text/css" />
    <?php if(isset($style) && $style != "default"){?>
    <link href="<?php echo U("")."skins/".$this->app."/".$this->skin."/css/style_admin_$style.css";?>"  rel="stylesheet" type="text/css" />
    <?php }?>
    
<style>
</style>
    <!--[if lt IE 9]><script src="<?php echo U("static@/javascript/html5/ie8-responsive-file-warning.js");?>"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo U("static@/javascript/html5/html5shiv.js");?>"></script>
      <script src="<?php echo U("static@/javascript/html5/respond.min.js");?>"></script>
    <![endif]-->
    <script language="javascript" src="<?php echo U("static@/javascript/jquery/jquery-1.10.2.min.js");?>"></script>
    <script language="javascript">var $ = jQuery.noConflict(),APP_URL = "<?php echo U("");?>",tUser={};
    <?php if($uid){?>
    tUser['uid'] = <?php echo isset($uid)?$uid:"";?>;
  <?php }?>
    </script>
</head>
<body class="ucenter">
<div class="topbar">
<div class="aps">
  <div class="in-left">
    <ul class="">
    <li><a href="<?php echo U("home@/");?>" target="_blank" class=" font-gray"><cite class="glyphicon glyphicon-home"></cite> 网站首页</a></li>
    <li class="dropdown">
     
    </li>
    </ul>
  </div>
  <div class="in-center">
      
  </div>
  <div class="in-right">
  <ul>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <?php echo isset($this->userinfo['name'])?$this->userinfo['name']:"";?> <b class="caret"></b></a>
    <ul class="dropdown-menu">
       <li><a href="<?php echo U("account@/ucenter/profile_basic");?>">个人资料修改</a></li>
       <!--<li><a href="<?php echo U("account@/ucenter/profile_avatar");?>" target="_blank">个人头像修改</a></li>-->
       <li><a href="<?php echo U("account@/ucenter/profile_passwd");?>">修改密码</a></li>
       <li class="divider"></li>
       <li><a href="<?php echo U("account@/login/logout");?>">退出</a></li>
     </ul>
     </li>
     
   </ul>
  </div>
  <div class="cl"></div>
</div>
</div>
<div class="header">
  <div class="aps">
      <div class="logo"><a href="/sys_manager" title=""><img src="<?php echo U("home@/")."skins/home/".$this->skin."/images/minilogo.png";?>" alt="八戒DNS" /></a></div>
      

      <div class="cl"></div>
    </div>
</div>
<div class="main">
<div class="leftnav" id="Lnav">
        <div class="quick_btn" style="display:none;"></div>
        <div class="dis10"></div>
        <?php foreach($this->nav['nav'] as $key => $item){?>
      <dl>
      <?php if($item['status'] == 1 && $this->check_purview($item['url'])){?>
        <dt><a href="javascript:void(0)" _key="<?php echo isset($key)?$key:"";?>" _href="<?php echo isset($item['url'])?$item['url']:"";?>"><cite class="glyphicon glyphicon-<?php echo isset($item['enname'])?$item['enname']:"";?>"></cite> &nbsp;<?php echo isset($item['name'])?$item['name']:"";?> <cite class="updown glyphicon glyphicon-chevron-<?php if($item['isopen'] == 1 || $this->nav['top'] == $key){?>down<?php }else{?>up<?php }?>"></cite></a></dt>
        <?php foreach($item['childrens'] as $key2 => $sub){?>
        <?php if($sub['status'] == 1 && $this->check_purview($sub['url'])){?>
        <dd class="<?php if($this->nav['sub'] == $key2){?>cur<?php }?>" <?php if($item['isopen'] == 1 || $this->nav['top'] == $key){?><?php }else{?>style="display:none;"<?php }?>><a href="<?php echo isset($sub['url'])?$sub['url']:"";?>"><cite class="glyphicon glyphicon-<?php echo isset($sub['enname'])?$sub['enname']:"";?>"></cite> &nbsp;<?php echo isset($sub['name'])?$sub['name']:"";?> </a></dd>
        <?php }?>
        <?php }?>
      <?php }?>
      </dl>
      <?php }?>
    <script language="javascript">
       $(function(){
           $("#Lnav dl dt").click(function(){
              var obj = this;
              if($(obj).find("cite.updown").hasClass("glyphicon-chevron-down")){
                 $(obj).find("cite.updown").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
                 $(obj).parent().find("dd").hide();
              }else{
                 $(obj).find("cite.updown").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
                 $(obj).parent().find("dd").show();
              }         
           });
       })
     </script>
     <div class="extnav">
     <dl>
     <?php if($this->check_purview('/sys_manager/module_config')){?>
     <dd class="<?php if(in_array($this->nav['uri'],array('/sys_manager/module_config'))){?>cur<?php }?>"><a href="/sys_manager/module_config">模块配置</a></dd>
     <?php }?>
     <dd class="<?php if(in_array($this->nav['uri'],array('/ucenter/profile_passwd'))){?>cur<?php }?>"><a href="<?php echo U("account@/ucenter/profile_passwd");?>">修改密码</a></dd>
         <dd><a href="<?php echo U("account@/login/logout");?>">退出 </a></dd>
     </dl>
     </div>
</div>
<div class="uc-index-row1">
   <div class="uc-userinfo">
       <!--<div class="avatar"><a class="tiptitle" data-content="点击修改头像" href="<?php echo U("account@/ucenter/profile_avatar");?>" target="_blank"><img class="myavatar_50" width="50" height="50" src="<?php echo tFun::avatar($uid);?>" /></a> </div>-->
       <div class="avatar"><a class="tiptitle"  href="javascript:void 0;" target="_blank"><img class="myavatar_50" width="50" height="50" src="<?php echo tFun::avatar($uid);?>" /></a> </div>
       <div class="info"><h1>Hi! <span class="font-org f24"><?php echo tUtil::substr($this->userinfo['name'],3);?></span>&nbsp;&nbsp;<a href="<?php echo U("account@/ucenter/profile_basic");?>" class="font-blue">修改</a></h1>
       <h2 class="f12 font-gray">问题联系QQ：415480171
       </h2>
       </div>
       <div class="op">
          <?php if($this->check_purview('/domain_manager/domain')){?>
          <a href="<?php echo U("/domain_manager/domain");?>" class="btn btn-default btn-xs">域名管理</a>&nbsp;
          <?php }?>
           <?php if($this->check_purview("/sys_manager/index_weixinport")){?>
           <a target="_blank" href="<?php echo U("/sys_manager/set_wxmenu");?>" target="_blank" class="btn btn-default btn-xs">微信菜单生成</a>&nbsp;
           <?php }?>
           <div class="dis5"></div>
           <?php if($this->check_purview("/sys_manager/index_import")){?>
           <a target="_blank" style="display:none;" href="<?php echo U("/import_manager/importsdsd");?>" target="_blank" class="btn btn-default btn-xs">老版数据导入</a>&nbsp;
           <?php }?>
           <?php if($this->check_purview("/sys_manager/index_batchrash")){?>
           <a target="_blank" style="display:none;" href="<?php echo U("/import_manager/domain_refresh");?>" target="_blank" class="btn btn-default btn-xs">域名批量刷新</a>&nbsp;
           <?php }?>

           <!--<a target="_blank" href="<?php echo U("/import_register_domain/ImportDnsRecords");?>" target="_blank" class="btn btn-default btn-xs">域名解析记录导入</a>&nbsp;-->

           </p>
       </div>
       <a type="button" href="<?php echo U("sys_manager/index_count");?>" class="btn btn-warning btn-sm" style="float: right"><cite class="glyphicon glyphicon-align-center"></cite> 切换统计图</a>
       <div class="cl"></div>
   </div>
   <div class="sysnotify">

   </div>
   <div class="cl"></div>
</div>

<?php if($this->check_purview("/sys_manager/index_count")){?>
<!-- 今日统计 -->
<div class="uc-index-row1 today-count">
  <h1>今日统计</h1>
  <?php $start_dateline = strtotime(tTime::get_datetime("Y-m-d",$timestamp))?>
  <?php $end_dateline   = ($start_dateline + 86400);?>
  <div class="item">
  <div class="in">
  <h1>今日新注册用户</h1>
  <h2>
  <a href="<?php echo U("/user_manager/userlist");?>" class="count"><?php echo M("user")->get_one("regdateline>$start_dateline AND regdateline<$end_dateline","count('uid')");?></a>
  <span class="line">/</span>
  <span class="total font-green"><?php echo M("user")->get_one("logdateline>$start_dateline AND logdateline<$end_dateline","count('uid')");?></span>
  <span class="line">/</span>
  <span class="total"><?php echo M("user")->get_one("1","count('uid')");?></span>
  </h2>
  </div>
  </div>

  <div class="item">
  <div class="in">
  <h1>今日新加域名解析</h1>
  <h2>
  <a href="<?php echo U("/domain_manager/domain");?>" class="count"><?php echo M("domain")->get_one("dateline>$start_dateline AND dateline<$end_dateline","count('domain_id')");?></a>
  <span class="line">/</span>
  <span class="total font-green"><?php echo M("domain")->get_one("service_group<>'free' AND inns=1","count('domain_id')");?></span>
  <span class="line">/</span>
  <span class="total font-blue"><?php echo M("domain")->get_one("inns=1","count('domain_id')");?></span>
  <span class="line">/</span>
  <span class="total"><?php echo M("domain")->get_one("1","count('domain_id')");?></span>
  </h2>

  </div>
  </div>

  <div class="item">
  <div class="in">
  <h1>今日新加域名注册</h1>
  <h2>
  <a href="<?php echo U("/domain_register/domain");?>" class="count"><?php echo M("register_domain")->get_one("reg_time>=$start_dateline AND reg_time<=$end_dateline","count('id')");?></a>
  <span class="line">/</span>
  <span class="total"><?php echo M("register_domain")->get_one("1","count('id')");?></span>
  </h2>
  </div>
  </div>

  <div class="item">
  <div class="in">
  <h1>今日会员充值</h1>
  <h2>
    <a href="<?php echo U("/order_manager/recharge");?>" class="count"><?php $a = M("recharge")->get_one("dateline>$start_dateline AND dateline<$end_dateline AND status=1 AND indel=0","sum(amount)")?>
    <?php echo $a?$a:"0.00";?>
    </a>
  <span class="line"></span><br/>
  <span class="total"><?php echo M("recharge")->get_one("status=1 AND indel=0","sum(amount)");?></span>
  </h2>
  </div>
  </div>

    <br/>
    <div class="dis30"></div>

  <div class="item">
  <div class="in">
  <h1>今日域名解析订单</h1>
  <h2>
      <a href="<?php echo U("/order_manager/parser");?>" class="count">
          <?php $a = M("order")->get_one("dateline>$start_dateline AND dateline<$end_dateline AND indel=0 AND status <> 0","count(order_id)")?>
          <?php echo $a?$a:"0";?>
      </a>
      <span class="line"></span><br/>
      <span class="total"><?php echo M("order")->get_one("indel=0 AND status <> 0","count(order_id)");?></span>
  </h2>
  </div>
  </div>


    <div class="item">
        <div class="in">
            <h1>今日域名注册订单</h1>
            <h2>
                <a href="<?php echo U("/order_manager/register");?>" class="count">
                    <?php $a = M("register_order")->get_one("dateline>$start_dateline AND dateline<$end_dateline AND indel=0 AND status <> 0","count(order_id)")?>
                    <?php echo $a?$a:"0";?>
                </a>
                <span class="line"></span><br/>
                <span class="total"><?php echo M("register_order")->get_one("indel=0 AND status <> 0","count(order_id)");?></span>
            </h2>
        </div>
    </div>

    <div class="item">
        <div class="in">
            <h1>今日管理员充值</h1>
            <h2>
                <a href="<?php echo U("/user_manager/user_accountlog");?>" class="count">
                    <?php $a = M("user_accountlog")->get_one("dateline>$start_dateline AND dateline<$end_dateline AND type=2 AND ftype = 'balance' AND auid <>0","sum(amount)")?>
                    <?php echo $a?$a:"0";?>
                </a>
                <span class="line"></span><br/>
                <span class="total"><?php echo M("user_accountlog")->get_one("type=2 AND ftype = 'balance' AND auid > 0","sum(amount)");?></span>
            </h2>
        </div>
    </div>


  <div class="cl"></div>
</div>
<!-- end 今日统计 -->
<?php }?>

<?php if($this->check_purview("/sys_manager/index_serverstatus") && $this->check_purview("/domain_service/ns_group_btnopra")){?>
<!-- 服务器状态统计 -->
<div class="uc-index-row1 server-status">
<h1>DNS服务器状态</h1>
<?php $nsservers = M("@domain_ns_group")->get_list(true,"type='ns'")?>
<?php foreach($nsservers as $key => $item){?>
    <div class="item">
        <div class="in">
            <div class="inner">
                <h1>
                    <?php echo isset($item['name'])?$item['name']:"";?>(<?php echo isset($item['ns_group'])?$item['ns_group']:"";?>)&nbsp;
                    <a  type="button" href="javascript:void (0);" class="btn btn-default btn-xs find-count-ns" data-mac1="<?php echo isset($item['data'][0]['mac'])?$item['data'][0]['mac']:"";?>" data-mac2="<?php echo isset($item['data'][1]['mac'])?$item['data'][1]['mac']:"";?>" data-ip2="<?php echo isset($item['data'][1]['ip'])?$item['data'][1]['ip']:"";?>" data-ip1="<?php echo isset($item['data'][0]['ip'])?$item['data'][0]['ip']:"";?>">统计图</a>
                </h1>
                <?php foreach($item['data'] as $key => $server){?>
                <h3>
                    <font class="f14 font-blue"><?php echo isset($server['ip'])?$server['ip']:"";?></font> /<?php echo isset($server['domain'])?$server['domain']:"";?>
                </h3>
                <h2>
                    <a href="javascript:void (0);" class="count count-query-ns" data-mac="<?php echo isset($server['mac'])?$server['mac']:"";?>">0</a>
                    <font class="font-gray2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
                      <span class="interface-status">
                      <cite class="glyphicon status_img glyphicon-remove font-red"></cite>
                      <button type="button" class="btn btn-xs btn-default glyphicon glyphicon-refresh refresh-stat" data-mark="reload" data-port="<?php echo isset($server['port'])?$server['port']:"";?>" data-host="<?php echo isset($server['ip'])?$server['ip']:"";?>">
                      </button>
                      </span>
                    &nbsp;&nbsp;&nbsp;
                      <span class="dns-status">
                    <cite data-content="" class="tiptitle glyphicon status_img glyphicon-remove font-red"></cite>
                    <button type="button" class="btn btn-xs btn-default glyphicon glyphicon-refresh refresh-dns-stat" data-mark="reload" data-port="<?php echo isset($server['port'])?$server['port']:"";?>" data-host="<?php echo isset($server['ip'])?$server['ip']:"";?>">
                    </button>
                      </span>&nbsp;&nbsp;
                </h2>
                <div class="dis10"></div>
                <?php }?>
            </div>
        </div>
    </div>
<?php }?>
<div class="cl"></div>
</div>
<!-- end 服务器状态统计 -->
<?php }?>


<?php if($this->check_purview("/sys_manager/index_beanstalk")){?>
<div class="uc-index-row1">
<h1>消息队列</h1>
<iframe scrolling="no" src="<?php echo U("admin@/admin/beanstalk/public/?server=115.231.26.213:11300");?>" style="border:none;" frameborder="0" width="100%" height="150" ></iframe>
</div>
<?php }?>


<?php if($this->check_purview("/sys_manager/index_online")){?>
<!--serverinfo-->
<div class="uc-index-row1">
   <div class="uc-rowbox">
    <div class="head"><cite class="glyphicon glyphicon-user f14"></cite> &nbsp;当前在线:<b class="font-org f18"> <?php echo M("session")->get_one('1',"count('sid')");?></b>人&nbsp;&nbsp;
会员：<b class="font-org f18"> <?php echo M("session")->get_one('uid>0',"count('sid')");?></b>人
    </div>
    <div class="bod">
      <div class="onlines">
      <?php $onlines = M("session AS a LEFT JOIN @user as b ON a.uid=b.uid")->query("","a.expiry,a.uid,a.ip,a.pc,a.ie,b.uname","uid DESC,expiry DESC")?>
      <?php if(!empty($onlines)){?>
      <?php foreach($onlines as $key => $item){?>
       <div class="item e">
       <?php if($item['uid']){?>
       <a href="javacript:void(0)" class="e" title="<?php echo isset($item['uname'])?$item['uname']:"";?> - <?php echo isset($item['ip'])?$item['ip']:"";?>/<?php echo isset($item['pc'])?$item['pc']:"";?>/<?php echo isset($item['ie'])?$item['ie']:"";?>"><?php echo isset($item['uname'])?$item['uname']:"";?></a>
       <?php }else{?>
       <a href="javacript:void(0)" class="e" title="<?php echo isset($item['ip'])?$item['ip']:"";?>/<?php echo isset($item['pc'])?$item['pc']:"";?>/<?php echo isset($item['ie'])?$item['ie']:"";?>"><?php echo isset($item['ip'])?$item['ip']:"";?>/<?php echo isset($item['pc'])?$item['pc']:"";?>/<?php echo isset($item['ie'])?$item['ie']:"";?></a>
       <?php }?>
       </div>
      <?php }?>
      <?php }?>
      </div>
      <div class="cl"></div>
    </div>
   </div>
</div>
<!--end serverinfo-->
<?php }?>

<?php $s_record = M("domain_record")->get_one("inlock = 1","count(record_id)")?>
<?php $u_record = M("user")->get_one("ulevel = 0 AND utype = 2","count(uid)")?>
<?php $domain_qy = M("domain_qy")->get_one("status = 0","count(qy_id)")?>
<?php $tg_user = M("tg_user")->get_one("status = 1","count(id)")?>
<?php if(!empty($s_record) || !empty($u_record) || !empty($domain_qy)){?>
<div id="Dsystip" class="systip" style="right: -180px;">
    <div class="sys-tip-box">
        <?php if(!empty($s_record)){?>
        <div class="tip-item">
            <label>待审核URL：</label>
            <b class="font-org"><?php echo isset($s_record)?$s_record:"";?></b> 个&nbsp;&nbsp;
            <a href="<?php echo U("domain_manager/records?c=lock");?>" class="txt-blue">查看</a>
        </div>
        <?php }?>
        <?php if(!empty($u_record)){?>
        <div class="tip-item">
            <label>待审核企业：</label>
            <b class="font-org"><?php echo isset($u_record)?$u_record:"";?></b> 个&nbsp;&nbsp;
            <a href="<?php echo U("user_manager/userlist_com");?>" class="txt-blue">查看</a>
        </div>
        <?php }?>
        <?php if(!empty($tg_user)){?>
        <div class="tip-item">
            <label>审核推广用户：</label>
            <b class="font-org"><?php echo isset($tg_user)?$tg_user:"";?></b> 个&nbsp;&nbsp;
            <a href="<?php echo U("tg_manager/tg_user");?>" class="txt-blue">查看</a>
        </div>
        <?php }?>
        <?php if(!empty($domain_qy)){?>
        <div class="tip-item">
            <label>待解封域名：</label>
            <b class="font-org"><?php echo isset($domain_qy)?$domain_qy:"";?></b> 个&nbsp;&nbsp;
            <a href="<?php echo U("domain_manager/domain_qy");?>" class="txt-blue">查看</a>
        </div>
        <?php }?>
    </div>
    <div class="sys-tip-close"><a class="txt-gray" onclick="$(this).parent().parent().hide();" href="javascript:void(0);">
        <i class="glyphicon glyphicon-remove"></i>&nbsp;
        关闭</a></div>
</div>
<?php }?>
<div class="cl"></div></div>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap/js/bootstrap.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/laydate/laydate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/layer/layer.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/validform/validform.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/easyTemplate.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/apps/app.init.admin.js");?>"></script>
<script language="javascript" src="<?php echo U("static@javascript/jquery.zclip/jquery.zclip.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static/cache/dataconfig.js");?>"></script>
<?php echo $this->fetch('tpl/form')?>
<script language="javascript" src="<?php echo U("static@/javascript/highcharts/highcharts.js");?>"></script>
<script language="javascript">
$(function(){
    //右下角审核弹框提醒
    $('#Dsystip').animate({'right': 10}, 300);
    setTimeout(function () {
        $('#Dsystip').animate({'right':-180},300);
    } , 5000);
    //刷新请求
    $(".refresh-stat").click(function () {
        check_status(this);
    });
    $(".refresh-dns-stat").click(function () {
        check_dns_status(this);
    });
    //初始话操作
    $(".refresh-dns-stat").click();
    $(".refresh-stat").click();
	$("a.tiptitle").popover({html:true,trigger:"hover",});
    //域名解析查询量统计
    $(".count-query-ns,.query-ns-total,.query-ns-totals").click(function () {
        query_ask(this);
    });
    $(".count-query-ns,.query-ns-total,.query-ns-totals").click();
    //查看统计域名解析查询量统计图
    $(".find-count-ns").unbind("click").bind("click",function () {
        find_ns_function(this);
    });
})
var check_status = function (obj) {
    var host = $(obj).data("host");
    var port = $(obj).data("port");
    $.ui.loading();
    $.ajaxPassport({
        url: "<?php echo U("/domain_service/ns_group_btnopra?do=chkstat");?>",
        success: function (res) {
            if (res.error == 0 && res.status_code == 200) {
                $(obj).parent().find("cite.status_img").removeClass("glyphicon-remove font-red").addClass("glyphicon-ok font-green");
            } else {
                $(obj).parent().find("cite.status_img").removeClass("glyphicon-ok font-green").addClass("glyphicon-remove font-red");
            }
            $.ui.close_loading();
        },
        data: {port: port, host: host}
    })
}
var check_dns_status = function (obj) {
    var host = $(obj).data("host");
    var port = $(obj).data("port");
    $.ui.loading();
    $.ajaxPassport({
        url: "<?php echo U("/domain_service/ns_group_btnopra?do=status");?>",
        success: function (res) {
            if (res.error == 0 && !$.is_empty(res.message) && res.message.indexOf("8JDNS") > 0) {
                $(obj).parent().find("cite.status_img").removeClass("glyphicon-remove font-red").addClass("glyphicon-ok font-green");
            } else {
                $(obj).parent().find("cite.status_img").removeClass("glyphicon-ok font-green").addClass("glyphicon-remove font-red");
            }
            $(obj).parent().find("cite.status_img").data("content",res.message);
            $(obj).parent().find("cite.status_img").popover({html:true,trigger:"hover",});
            $.ui.close_loading();
        },
        data: {port: port, host: host}
    })
}
var query_ask = function (obj) {
    var mac = $(obj).data("mac");
    mac = typeof mac == "undefined"?0:mac;
    $.ui.loading($(obj));
    $.ajaxPassport({
        url:"<?php echo U("sys_manager/index_mac_query");?>",
        success:function (res) {
            $.ui.close_loading($(obj));
            $(obj).html(res.message);
        },
        data:{mac:mac}
    });
}
</script>
<script type="text/template" id="tpl_find_ns_t">
    <#macro rowedit data>
        <div class="dis30"></div>
        <div class="" style="padding: 15px 30px 0px 70px">
            <div class="list content">
                <div class="fl" style="width:1080px;">
                    <div class="countimage-box" id="user_reg_month" style="width: 1080px; height:320px; margin: 0 auto"><img alt="正在加载中" class="loading" src="" /></div>
                </div>
                <div class="dis30"></div>
            </div>
        </div>
        <div class="dis30"></div>
    </#macro>
</script>
<script type="text/javascript">
    var find_ns_function = function (obj) {
        var mac1 = $(obj).data("mac1");
        var mac2 = $(obj).data("mac2");

        var ip1 = $(obj).data("ip1");
        var ip2 = $(obj).data("ip2");
        $.ui.loading();
        $.ajaxPassport({
            url:"<?php echo U("sys_manager/index_find_ask");?>",
            success:function (res) {
                $.ui.close_loading();
                if (res.error == 0) {
                    var edit_c = "" + easyTemplate($("#tpl_find_ns_t").html());
                    $("#myModal").find(".modal-dialog").width("1200");
                    $("#myModal").find(".modal-content").html(edit_c);
                    $('#myModal').modal();


                    var total1 = 0;
                    var g1ns1 = new Array();
                    var g1ns2 = new Array();
                    for(var x in res.msg['mac1']){
                        g1ns1.push(x);
                        g1ns2.push(res.msg['mac1'][x]);
                        total1 = total1+ parseInt(res.msg['mac1'][x]);
                    }

                    var total2 = 0;
                    var g1ns3 = new Array();
                    var g1ns4 = new Array();
                    for(var x in res.msg['mac2']){
                        g1ns3.push(x);
                        g1ns4.push(res.msg['mac2'][x]);
                        total2 = total2+ parseInt(res.msg['mac2'][x]);
                    }



                    $("#user_reg_month").highcharts({
                        chart: {
                            type: "line",
                            marginRight: 130,
                            marginBottom: 25
                        },
                        title: {
                            text: "八戒DNS服务器("+ip1+"/"+ip2+")近一个月统计图",
                            x: -20 //center
                        },
                        subtitle: {
                            text: ip1+"请求数:"+total1+"人次 | "+ip2+"请求数:"+total2+"人次",
                            x: -20
                        },
                        xAxis: {
                            categories: g1ns1,
                            tickInterval:3
                        },
                        yAxis: {
                            title: {
                                text: "请求数"
                            },
                            plotLines: [{
                                value: 0,
                                width: 1,
                                color: "#808080"
                            }]
                        },
                        tooltip: {
                            valueSuffix: "次",
                            shadow:false,
                            shared: true,
                            crosshairs: true,
                            borderColor: "#bbbbbb",
                            borderRadius: 0,
                            borderWidth: 1
                        },
                        legend: {
                            layout: "vertical",
                            align: "right",
                            verticalAlign: "top",
                            x: -10,
                            y: 100,
                            borderWidth: 0
                        },
                        series: [{
                            name: ip1,
                            data: g1ns2,
                            color:"#48BEF4",
                            index:1,
                        },{
                            name: ip2,
                            data: g1ns4,
                            color:"#90ED7D",
                            marker: {
                                symbol: "diamond"
                            }
                        }
                        ]
                    });
                }else{
                    $.ui.error(res.message);
                }
            },
            data:{mac1:mac1,mac2:mac2}
        });
    }
</script>
<script language="javascript">
 $(function(){
     laydate.skin('molv');
 })
</script>
<!-- Modal -->
<div style="z-index:1033;" class="modal fade" id="myModal" tabindex="9999" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>