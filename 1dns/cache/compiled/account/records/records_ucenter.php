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
<?php $ns_group = M("@domain_ns_group")->get_cache_by_ns($domain_row['ns_group']);?>
<?php $ns_group_ns = isset($ns_group['ns'])?$ns_group['ns']:"";?>
<div class="records combox">
<?php if($domain_row['inns'] == 0){?>
    <div class="page-tip">
        <strong>还差最后一步，即可开始使用 八戒DNS,将当前NS改成八戒DNS对应套餐的NS</strong>
        <div style="width:500px;margin:8px 0 0 0;">
        <table class="am-table ">
            <tbody>
             <tr>
             <td class="am-warning">
                 <?php if($domain_row['ns'] && $domain_row['ns'] != -1){?>
                 <?php echo str_replace(';',"<br/>",$domain_row['ns']);?>
                 <?php }else{?>
                 未检测到NS
                 <?php }?>
             </td>
             <td class="am-warning"><cite class="am-icon-angle-double-right am-text-warning"></cite></td>
             <td class="am-success">
                <?php if($ns_group_ns){?>
                <?php echo str_replace(';',"<br/>",$ns_group_ns);?>
                <?php }else{?>
                f1g1ns1.dnspig.com<br/>
                f1g1ns2.dnspig.com
                <?php }?>
             </td>   
            </tbody>
        </table>
        </div>

        <b>
            为避免多次修改您的DNS,请先选好域名套餐,我们提供稳定的收费套餐：
            <a class="txt-blue" target="_blank" href="<?php echo U("home@/product/buy");?>">标准版,豪华版,旗舰版</a>
        </b>
        <br>
        注意不能同时和其他 DNS 混用，会导致解析混乱哦～
        <span id="transmark" style="display: none; width: 0px; height: 0px;"></span>
        <br>
        修改 DNS 服务器需要最长 48 小时的全球生效时间，请耐心等待
        <br>
        遇到困难？
        <a class="txt-blue" target="_blank" href="<?php echo U("home@/helper/index");?>">
            寻找技术支持
            <span class="f9">>></span>
        </a>
        <br>
        <button class="close" onclick="$(this.parentNode).hide();"></button>
    </div>
    <div class="dis10"></div>
<?php }?>

    <?php if($domain_bind){?>
        <div class="am-alert am-alert-secondary basic-t-t" data-am-alert style="background-color: #FDEFE4;color: #F37B1D">
            <button type="button" class="am-close">&times;</button>
            <p style="font-size: 14px">已绑定域名<strong> <?php echo isset($domain_bind)?$domain_bind:"";?></strong>，绑定期间不能进行添加，修改，暂停，删除操作。如需操作，请先
                <a href="<?php echo U("/records/records_set?domain=$domain_bind");?>" style="color: #0E90D2">解绑</a>
            </p>
        </div>
    <?php }?>

    <div class="records-nav">
        <ul>
            <li class="domain">
                <a href="javascript:void (0);"><i class="am-icon-globe"></i></a>
                <span><?php if($domain_cn){?><?php echo isset($domain_cn)?$domain_cn:"";?><?php }else{?><?php echo isset($domain)?$domain:"";?><?php }?></span>
                <span style="color: darkgray;font-size: 12px;" title="共<?php echo isset($domain_row['records'])?$domain_row['records']:"";?>条记录">(<?php echo isset($domain_row['records'])?$domain_row['records']:"";?>)</span>

                <span class="texticon texticon-<?php echo isset($domain_row['service_group'])?$domain_row['service_group']:"";?> domian-upgrade" data-domain="<?php echo isset($domain)?$domain:"";?>" style="width:18px;height: 20px;line-height: 20px;color: white;"><?php echo tUtil::substr($service_group['name'],1,'');?></span>
                <?php if($domain_row['status'] == 0){?>
                    <cite class="am-icon-minus-circle am-text-danger" title="域名解析已暂停"></cite>
                <?php }?>

                <?php if($domain_row['inlock'] == 1){?>
                    <cite class="am-icon-lock am-font-black" title="域名已锁定"></cite>
                <?php }?>

            </li>
            <li><a href="<?php echo U("/records/records_setline?domain=$domain");?>">自定义线路</a></li>
            <li><a href="<?php echo U("/records/records_set?domain=$domain");?>">域名设置</a></li>
            <li><a href="<?php echo U("/records/records_count?domain=$domain");?>">解析量统计</a></li>
            <li class="cur"><a href="<?php echo U("/domains/dns/");?><?php echo isset($domain)?$domain:"";?>">记录设置</a></li>
        </ul>
    </div>
    <div class="dis10"></div>
    <div class="record-op" id="accordion">
        <div class="record-op-l">
            <?php if(!$domain_bind){?>
                <button type="button" class="am-btn am-btn-success am-dropdown-toggle am-radius am-btn-sm am-add-records"><span class="am-icon-plus"></span> 添加记录 </button>&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="am-btn am-btn-default am-btn-sm am-radius recordsOption" data-do="stop">暂停</button>
                <button type="button" class="am-btn am-btn-default am-btn-sm am-radius recordsOption" data-do="start">启用</button>
                <button type="button" class="am-btn am-btn-default am-btn-sm am-radius recordsOption" data-do="del">删除</button>
                <?php if($domain_row['records'] == 0){?>
                <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius am-import-btn">自动导入</button>
                <?php }?>
                <?php if($batch_import_records == 1){?>
                <button type="button" class="am-btn am-btn-default am-btn-sm am-radius batch-import-records" data-domain="<?php echo isset($domain)?$domain:"";?>">批量导入</button>
                <?php }?>
            <?php }?>
        </div>
        <a href="#do-not-say-1" data-am-collapse="{parent: '#accordion'}" class="record-op-r2">高级搜索</a>
        <div class="record-op-r">
            <input type="text" class="am-form-field am-radius am-input-sm am-serch-records" placeholder="快速查找记录" />
        </div>
    </div>
    <div class="clear"></div>
    <div id="do-not-say-1" class="am-collapse">
        <div class="am-panel-bd">
            <table class="am-table">
                <col width="40%"/>
                <col width="30%"/>
                <col/>
                <tbody>
                <tr>
                    <td class="o">主机名：<input type="text" name="name-ser" class="am-form-field am-radius am-input-sm" placeholder="请输入主机记录"/></td>
                    <td class="o">
                        <span>类型：</span>
                        <div class="tselect tselect-type am-input-sm" data-explain="rrtype-ser" data-name="rrtype-ser" data-label="请选择类型">
                            <a href="javascript:void(0)" class="tselect-text e"></a>
                            <input type="hidden" name="rrtype-ser" class="rrtype-ser" value="" />
                            <div class="tselect-box" style="display:none">
                                <div class="in">
                                    <a class="sel0" data-v="" href="javascript:void(0);">不选</a>
                                    <?php foreach($data_config['RRtype'] as $key => $item){?>
                                    <a data-v="<?php echo isset($key)?$key:"";?>" title="<?php echo isset($item)?$item:"";?>" class="sel0 e" href="javascript:void(0);"><?php echo isset($item)?$item:"";?></a>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span>状态：</span>
                        <div class="tselect tselect-type am-input-sm" data-explain="status-ser" data-name="status-ser" data-label="请选择状态">
                            <a href="javascript:void(0)" class="tselect-text e"></a>
                            <input type="hidden" name="status-ser" class="status-ser" value="" />
                            <div class="tselect-box" style="display:none">
                                <div class="in">
                                    <a class="sel0" data-v="" href="javascript:void(0);">不选</a>
                                    <a data-v="2" class="sel0 e" href="javascript:void(0);">正常</a>
                                    <a data-v="1" class="sel0 e" href="javascript:void(0);">暂停</a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="o">记录值：<input type="text" name="value-ser" class="am-form-field am-radius am-input-sm" placeholder="请输入记录值"/></td>
                    <td class="o">
                        <span>线路：</span>
                        <div class="tselect tselect-acl am-input-sm" data-explain="acl-ser" data-name="acl-ser" data-label="请选择线路">
                            <a href="javascript:void(0)" class="tselect-text e"></a>
                            <input type="hidden" name="acl-ser" class="acl-ser" value="" />
                            <div class="tselect-box" style="display:none">
                                <div class="in">
                                    <div class="level0"><a class="sel<#if (data.disabled == 1)>1<#else>0</#if>" data-v="" href="javascript:void(0);">不选</a></div>
                                    <?php $aclist = C("category","domain_acl")->get_level(0)?>
                                    <?php foreach($aclist as $key => $item){?>
                                    <div class="level0">
                                        <?php if($item['childrens']){?>
                                        <a data-v="<?php echo isset($item['ident'])?$item['ident']:"";?>" title="<?php echo isset($item['name'])?$item['name']:"";?>" class=" <?php if(in_array($item['ident'],$my_acls)){?>sel0<?php }else{?>sel1<?php }?> e" href="javascript:void(0);"><?php echo isset($item['name'])?$item['name']:"";?> <cite class="am-icon-angle-right"></cite></a>
                                        <div class="sub2" style="">
                                            <?php foreach($item['childrens'] as $key => $item2){?>
                                            <div class="level1">
                                                <?php if($item2['childrens']){?>
                                                <a data-v="<?php echo isset($item2['ident'])?$item2['ident']:"";?>" title="<?php echo isset($item2['name'])?$item2['name']:"";?>" class="level1 <?php if(in_array($item2['ident'],$my_acls)){?>sel0<?php }else{?>sel1<?php }?>  e" href="javascript:void(0);"><?php echo isset($item2['name'])?$item2['name']:"";?> <cite class="am-icon-angle-right"></cite></a>
                                                <div class="sub3" style="">
                                                    <?php foreach($item2['childrens'] as $key => $item3){?>
                                                    <a data-v="<?php echo isset($item3['ident'])?$item3['ident']:"";?>" title="<?php echo isset($item3['name'])?$item3['name']:"";?>" class="level1 <?php if(in_array($item3['ident'],$my_acls)){?>sel0<?php }else{?>sel1<?php }?>  e" href="javascript:void(0);"><?php echo isset($item3['name'])?$item3['name']:"";?></a>
                                                    <?php }?>
                                                </div>
                                                <?php }else{?>
                                                <a data-v="<?php echo isset($item2['ident'])?$item2['ident']:"";?>" title="<?php echo isset($item2['name'])?$item2['name']:"";?>" class="level1 <?php if(in_array($item2['ident'],$my_acls)){?>sel0<?php }else{?>sel1<?php }?> e" href="javascript:void(0);"><?php echo isset($item2['name'])?$item2['name']:"";?></a>
                                                <?php }?>
                                            </div>
                                            <?php }?>
                                        </div>
                                        <?php }else{?>
                                        <a data-v="<?php echo isset($item['ident'])?$item['ident']:"";?>" title="<?php echo isset($item['name'])?$item['name']:"";?>" class="  <?php if(in_array($item['ident'],$my_acls)){?>sel0<?php }else{?>sel1<?php }?> e" href="javascript:void(0);"><?php echo isset($item['name'])?$item['name']:"";?></a>
                                        <?php }?>
                                    </div>
                                    <?php }?>
                                    <?php $serverR = tCache::read("service_group_".$domain_row['service_group']);?>
                                    <?php if($serverR['data']['diyLine']['value'] == 1){?>
                                    <?php $diyIp = M("domain_acl_set")->query("domain='$domain' AND uid=$uid AND status=1","name,id,uid","id DESC",500);?>
                                    <?php if(count($diyIp)>0){?>
                                    <hr/>
                                    <div class="level1"><a class="sel0" style="color:darkgray;">自定义线路</a></div>
                                    <?php foreach($diyIp as $key => $item){?>
                                    <div class="level0"><a class="sel0" data-v="cust<?php echo isset($item['id'])?$item['id']:"";?>" href="javascript:void(0);"><?php echo isset($item['name'])?$item['name']:"";?></a></div>
                                    <?php }?>
                                    <?php }?>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><button type="button" class="am-btn am-btn-warning am-btn-sm am-radius vance-serch">搜索</button></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="dis10"></div>
    <div class="record-list" style="position: relative;">
        <form method="post" id="setRecords form-with-tooltip">
            <table class="am-table am-table-striped">
                <col width="30px"/>
                <col width="180px" />
                <col width="130px" />
                <col width="150px"/>
                <col  />
                <col width="90px"/>
                <col width="70px" />
                <col width="150px"/>
                <thead>
                <tr>
                    <th><input type="checkbox" data-name="recordId[]" class="checkall"/></th>
                    <th>主机名</th>
                    <th>类型</th>
                    <th>线路</th>
                    <th>记录值</th>
                    <th>MX优先级</th>
                    <th>TTL</th>
                    <th class="am-text-right">操作</th>
                </tr>
                </thead>
                <tbody class="add_tpl"></tbody>
                <tbody class="add_tips" style="display:none;"><tr><td colspan="8"></td></tr></tbody>
                <tbody class="list_tpl"></tbody>
                <?php if($ns_group_ns){?>
                <?php $ns_group_ns_arr = explode(';',$ns_group_ns);?>                
                <tbody class="list_tpl_m">
                <?php foreach($ns_group_ns_arr as $key => $item){?>
                <tr class="am-disabled am-active">
                    <td></td>
                    <td>@</td>
                    <td>NS</td>
                    <td>默认</td>
                    <td><?php echo isset($item)?$item:"";?></td>
                    <td>_</td>
                    <td>600</td>
                    <td></td>
                </tr>
                <?php }?>
                </tbody>
                <?php }?>
            </table>            
           </form>
    </div>
    <div class="pagebar"></div>
    <div class="dis20"></div>
</div>
<div class="my-domian-upgrade"></div>
<div class="tpl_setting_import"></div>
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
<?php echo $this->fetch('records/records_tpl')?>
<?php echo $this->fetch('order/order_cart')?>
<?php echo $this->fetch('records/records_import')?>
<?php echo $this->fetch('tpl/form')?>
<!--解析记录列表  start-->
<script type="text/javascript">
    $(function(){
        $.ui.form.select($("#do-not-say-1"));
        $("a").bind("focus",function(){
            $(this).blur();
        });
        $(".record-list").find("input.checkall").unbind("click").bind("click",function(){
            $.check_all(this);
        });
        $(".recordsOption").click(function(){
            batch_records_op(this);
        });
        $(".batch-import-records").click(function () {
            batch_import_records(this);
        });
        //普通搜索
        $("input.am-serch-records").keyup(function(){
            var keyword = $(this).val();
            if (!$.is_empty(keyword)){
                get_records_list(1,keyword);
            }else{
                get_records_list(1);
            }
        });
        //高级搜索
        $("#do-not-say-1 .vance-serch").click(function () {
            var _obj = $(this).parent().parent().parent();
            var name = _obj.find("input[name='name-ser']").val();
            var value = _obj.find("input[name='value-ser']").val();
            var type = _obj.find("input[name='rrtype-ser']").val();
            var status = _obj.find("input[name='status-ser']").val();
            var acl = _obj.find("input[name='acl-ser']").val();
            get_records_list(1,'',name,value,type,status,acl);
        });
        $('.am-add-records').click(function(){
            add_edit_record();
        });
        //升级套餐
        $(".domian-upgrade").unbind("click").bind("click",function(){
            var goods_name = $(this).attr("data-domain");
            add_cart_step1(1,0,"",goods_name);
        });

        <?php if($domain_row['records'] > 0){?>
        get_records_list(1);
        <?php }?>
    });
</script>
<!--解析记录列表 end-->
</body>
</html>