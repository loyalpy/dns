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
<?php $domain=R('domain','string');?>
<div class="add-domain-box">
	<div class="am-container-domain">
		<div class="fabu-stepbox">
        <ul class="clear">
		<li class="current"><i class="i1">1</i><span>添加域名</span><b></b></li>
		<li><i class="i2">2</i><span>设置解析</span><b></b></li>
		<li><i class="i3">3</i><span>完成</span></li>
		</ul>
        </div>

		<div class="dis30"></div>
		<!--添加域名-->
		<div class="am-centered-diy1">
			<form method="post" class="am-form" id="Fadddomain">
				<fieldset>
					<div class="am-form-group">
						<textarea placeholder="使用回车键，添加多个域名，一次最多允许100个" rows="3" id="Mdomains" name="domains" class="add_domain" style="height:32px;"><?php echo isset($domain)?$domain:"";?></textarea>
					</div>
					<div class="dis15"></div>
					<table class="new_add_domainlist am-table-bordered am-table-radius am-table-striped">
					    <col width="20px"  />
	            		<col width="180px"  />
	            		<col />
						<thead></thead><tbody></tbody>
					</table>
					<div class="dis15"></div>
					<div class="am-cf am-text-right">
						<input value="<?php echo tUtil::hash();?>" name="hash" type="hidden"/>
						<button type="submit" data-am-loading="{loadingText: '处理中...'}" id="Msubmit" class="am-btn  am-radius am-btn-success">添加</button>
						<button type="button" data-am-loading="{loadingText: '处理中...'}" id="Dsubmit" class="am-btn  am-radius am-btn-success">下一步</button>
					</div>
					<div class="dis60"></div>
				</fieldset>
			</form>
		</div>

		<!--设置域名-->
		<div class="am-centered-diy2">
			<form method="post" class="am-form" id="Fsetdomain">
				<fieldset>
					<div class="am-g">
						<div class="am-diy-acion">
							<a href="javascript:void (0);" class="am-icon-btn am-icon-globe am-font-gray"></a>
						</div>
						<div class="am-diy-domains"></div>
					</div>
					<div class="am-g">
						<div class="am-diy-acion">
							<a href="javascript:void (0);" class="am-icon-btn am-icon-book am-font-gray"></a>
						</div>
						<div class="am-diy-record">
							<div class="am-diy-record-l"><span>记录值</span></div>
							<div class="am-diy-record-m">
								<input type="text" class="am-form-field am-radius am-input-sm" id="domain-ip"/>
								<p class="am-font-gray">系统新增域名添加'www'及'@'记录。</p>
							</div>
							<div class="am-diy-record-m2"><span>类型</span></div>
							<div class="am-diy-record-r ">
								<select id="domain-type">
									<option value="A" selected>A</option>
									<option value="CNAME">CNAME</option>
								</select>
							</div>
							<div class="am-cf"></div>
							<div class="dis15"></div>
						</div>
						<div class="am-cf"></div>
						<div class="dis15"></div>
					</div>
					<div class="am-cf am-text-right">
						<input value="<?php echo tUtil::hash();?>" name="hash" type="hidden"/>
						<a href="<?php echo U("/domains");?>" class="">返回域名列表</a>&nbsp;&nbsp;
						<button type="submit" data-am-loading="{loadingText: '处理中...'}" 
						class="am-btn am-radius am-btn-success">添加</button>
					</div>
					<div class="dis10"></div>
				</fieldset>
			</form>
		</div>
		<!--完成-->
		<div class="am-centered-diy3">
			<div class="domain-left">
				<i class="am-icon-btn am-success am-icon-check"></i><br/>
				<span class="am-text-success">添加成功</span>
			</div>
			<div class="domain-right">
				<div class="domain-right-text">
					<p >
						<span>还差最后一步，即可使用bajiedns.com</span><br/>
						请到域名注册的地方，将dns修改为<br/>
						<div class="domain-ns-group">
					    <?php $ns_group = M("@domain_ns_group")->get_cache_by_ns('free');?>
                        <?php if(isset($ns_group['ns'])){?>
					    <?php echo str_replace(';',"<br/>",$ns_group['ns']);?>
						<?php }else{?>
						g1ns1.8jdns.net<br/>
						g1ns2.8jdns.net
						<?php }?>
				       </div>
						<div class="dis20"></div>
						<span class="am-text-xs am-font-gray">修改 DNS 服务器需要最长 72 小时的全球生效时间，<br/>请耐心等待
						遇到困难？<a href="<?php echo U("home@/helper/index");?>" class="am-text-xs">寻找技术支持 »</a>
						</span>
					</p>
				</div>
				<div class="dis30"></div>
				<div class="am-cf am-text-left">
					<input value="<?php echo tUtil::hash();?>" name="hash" type="hidden"/>					
					<a type="button" onclick="javascript:window.location='<?php echo U("/domains/add");?>';" value="" class="am-btn am-radius am-btn-default ">继续添加</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a  href='<?php echo U("/domains");?>'class="am-btn am-radius am-btn-success ">返回域名列表</a>					
				</div>
			</div>
			<div class="am-cf"></div>
			
		</div>
		<div class="dis60"></div>
		<div class="dis60"></div>
	</div>
</div>
<div class="my-domian-find"></div>
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
<?php echo $this->fetch('domains/domains_tpl')?>
<!--第一步 添加域名-->
<script type="text/javascript">
	var supper_domain = '<?php echo "(".implode(")|(",tValidate::support_domain()).")";?>';
	var get_domains = function() {
		var inputArr = $('#Mdomains').val().trim().split('\n');
		var domains  = [];
		inputArr = $.remove_array_item(inputArr,'');
		for (i = 0; i < inputArr.length; i++) {
			inputArr[i] = inputArr[i].trim();
			if (inputArr[i] && $.dns.is_domain(inputArr[i])) {
				domains.push(inputArr[i]);
			}
		}
		return domains;
	};
	var domains = new Array();
	$.autoTextarea($("#Mdomains").get(0),6,640);
	$('#Fadddomain').validator({
			submit:function(){
				var formValidity = this.isFormValid();
				if(formValidity === true){
					domains = get_domains();
					if(domains.length == 0){
						$.ui.error("请检查添加域名的合法性");
						$('#Mdomains').focus();
						return false;
					}
					if(domains.length > 10){
						$.ui.error("一次最多添加10个域名");
						$('#Mdomains').focus();
						return false;
					}
					$("#Msubmit").button('loading');
					setTimeout(function(){
						$('.new_add_domainlist tbody').empty();
						add_domain_step(0);
					},100)
				}
				return false;
			}
		});
	var num = 0;
	var add_domain_step = function(i){
		if( i>=domains.length || typeof domains[i] == "undefined"){
			return false;
		}
		$.ajaxPassport({
			url:U("/api/Domain.AddByUid"),
			success:function(res){
				$("#Msubmit").button('reset');
				$(".am-table-striped").addClass('am-table');
				if(res.status == 1){
					var html = '<tr class="success" data-domain="'+res.data.domain+'" data-domain_id="'+res.data.domain_id+'">'
						    + '<td><i class="am-icon-check-square-o am-text-success"></i></td>'
							+ '<td id="am-domain-domains">'+res.data.domain+'</td>'
							+ '<td class="am-text-success am-text-sm">添加成功</td>'
							+ '</tr>'
					$('.new_add_domainlist tbody').append(html);
					num++;
				}else{
					if (!$.is_empty(res.data.email)) {
						var tips_error = "域名已被其他用户添加，点击<a href=\"javascript:;\" class=\"domainFind\">取回</a>";
					}else{
						tips_error = res.msg;
					}
					var html = '<tr class="danger '+res.data.domain.split(".")[0]+'">'
							+ '<td><i class="am-text-danger am-icon-exclamation-circle"></i></td>'
							+ '<td>'+res.data.domain+'</td>'
							+ '<td class="am-text-danger am-text-sm">'+'添加失败,'+tips_error+'</td>'
							+ '</tr>'
					$('.new_add_domainlist tbody').append(html);
					//域名找回
					$('.new_add_domainlist tbody').find("."+res.data.domain.split(".")[0]+" .domainFind").unbind("click").bind("click",function () {
						domain_find_op(res.data.domain);
					});
				}
				if(num >= 1){
					$('#Msubmit').hide();
					$('#Dsubmit').show();
				}
				add_domain_step(i+1);
			},
			data:{domain:domains[i]},
		})
	}
</script>
<!--第二步 设置域名-->
<script type="text/javascript">
	$('#Dsubmit').click(function(){
		var arrDomain    =  [];
		var arrDomainId =  [];
		$(this).parent().parent().find('tbody tr.success').each(function(){
			var obj=this;
			arrDomain.push($(obj).data("domain"));
			arrDomainId.push($(obj).data("domain_id"))
		});
		for (i = 0; i < arrDomain.length; i++) {
			if(i==6){
				var brr = '<br/>';
			}else{
				var brr = '';
			}
			$('.am-diy-domains').append(
					'<span>' +arrDomain[i]+'</span>&nbsp;&nbsp;'+brr
					+ '<div id="domain-id" style="display: none">'+arrDomainId[i]+'</div>'
			);
		}
		//设置引导步骤
		$(".i1").parent().parent().find(".current").removeClass("current");
		$(".i2").parent().addClass("current");

		$('.am-centered-diy1').hide();
		$('.am-centered-diy2').show();
		$('.am-btn-add').removeClass('am-btn-warning').addClass('am-btn-default am-disabled');
		$('.am-btn-set').removeClass('am-btn-default am-disabled').addClass('am-btn-warning');
	})
	$('#Fsetdomain').validator({
		submit:function(){
			var formValidity = this.isFormValid();
			if(formValidity === true){
				var domainIdArr = [];
				$('.am-diy-domains #domain-id').each(function(){
					var obj=this;
					domainIdArr.push($(obj).html());
				});
				var ip = $('#domain-ip').val().trim();
				var type = $('#domain-type').val().trim();
				var count = 0;
				if(type == "A"){
					if(!$.dns.is_ip(ip)){
						$.ui.error("IP非法！");
						return false;
					}
				}else if(type == "CNAME"){
					if(!$.dns.is_domain2(ip)){
						$.ui.error("CNAME 值非法！");
						return false;
					}
				}

				//设置引导步骤
				$(".i2").parent().parent().find(".current").removeClass("current");
				$(".i3").parent().addClass("current");

				$('.am-centered-diy1').hide();
				$('.am-centered-diy2').hide();
				$('.am-centered-diy3').show();
				$('.am-btn-set').removeClass('am-btn-warning').addClass('am-btn-default am-disabled');
				$('.am-btn-finish').removeClass('am-btn-default am-disabled').addClass('am-btn-warning');
				$.exeJS(U("/domains/quick_addrecord?domain_ids="+ domainIdArr.join(",")+"&RRvalue="+ip+"&RRtype="+type));
			}
			return false;
		}
	});
</script>
</body>
</html>