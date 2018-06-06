{block head_menu}
<div class="menu">
	<ul>
		<li><a href="<?php echo U("/coupon_manager/coupon");?>" class="cur">域名列表</a></li>
	</ul>
</div>
{/block}
{block main}
<div class="main-nav">
	<div class="name">注册域名</div>
	<div class="cl"></div>
</div>
<!--search box-->
<form enctype="multipart/form-data" class="the_searchform form" method="POST" action="<?php echo U("/coupon_manager/coupon?do=get_url");?>">
	<div class="tpl"></div>
</form>
<!--end search box -->
<!-- list box -->
<form action="" class="thelistform">
	<div class="list-box">
		<table class="list-table table table-striped table-condensed table-responsive" cellpadding="0" cellspacing="0">
			<col width="180px"/>
			<col  width="180px"/>
			<col  />
			<col width="150px" />
			<col width="200px" />
			<thead>
			<tr>
				<th>注册域名</th>
				<th>用户</th>
				<th>域名NS</th>
				<th>注册时间</th>
				<th>到期时间</th>
			</tr>
			</thead>
			<tbody class="tpl"></tbody>
		</table>
	</div>
	<div class="pagebar"></div>
</form>
<!-- end list box -->
{/block}

{block css}
<link href="<?php echo U("static@/javascript/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css");?>" type="text/css" rel="stylesheet">
{/block}

{block javascript}
<?php echo $this->fetch('tpl/form')?>
<script type="text/template" id="tpl_list_row">
	<#macro row data>
		<tr>
			<td>
				${data.domain}<span class="font-org"> [<#if (data.reg_type == 1)>个人<#else>企业</#if>]</span><br/>
				<span class="font-gray"><#if (data.type == 0)>国际域名<#else>国内域名</#if></span>
			</td>
			<td class="font-green">${data.name}</td>
			<td class="font-gray">${data.ns.replace(";","<br/>")}</td>
			<td class="">${data.reg_time}</td>
			<td class="">${data.exp_time}</td>

		</tr>
	</#macro>
</script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js#utf-8");?>"></script>
<script language="javascript">
	var pageurl = "<?php echo isset($pageurl)?$pageurl:"";?>";
	var formdata = {
		startdate:{type:"date",label:"注册时间",name:"startdate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:""},
		enddate:{type:"date",label:"-",name:"enddate",value:"",data_sr:[],css:"shigh",require:"",desc:"",item_css:"date-dis"},
		keyword:{type:"text",label:"关键词",name:"keyword",value:"",data_sr:[],css:"",require:"",desc:"",item_css:""},
		btn:{type:"button",label:"",value:"搜索",desc:'',css:"btn-sm"}
	};
	var loadlist = function(page){
		$.ajaxLoadlist(page,pageurl,function(res){
			var keyword = $(".the_searchform input[name='keyword']").val();
			if(keyword != ""){
				var listhtml = $(".thelistform").find(".tpl").html();
				$(".thelistform").find(".tpl").html($.replace_keyword(listhtml,keyword));
			}
		});
	}
	$(function(){
		//加载搜索
		$.loadform(formdata,"",function(res){
			pageurl = res.pageurl;
			loadlist(1);
			return true;
		},null,".the_searchform");
		$(".date-ymd").datetimepicker({
			language:  'zh-CN',
			autoclose: 1,
			startView: 2,
			minView: 2,
			maxView: 4,
			format:"yyyy-mm-dd",
			pickerPosition: "bottom-right"
		});
		loadlist(1);
		$(".refresh-btn").click(function(){
			loadlist(1);
		});

	})
</script>
{/block}