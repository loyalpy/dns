<script type="text/template" id="tpl_import_loading">
<div class="import_record_box">
   <h1 class="am-text-sm " style="padding:3px 0 3px 12px;border-bottom:solid 1px #efefef;font-size:18px">扫描记录导入</h1>
	<div class="am-progress am-progress-striped am-progress-sm am-active ">
	  <div class="am-progress-bar am-progress-bar-success"  style="width: 0%"></div>
	</div>
	<div class="am-text-center">正在扫描记录... 请稍后</div>
	<div class="dis10"></div>
	<div class="am-text-center">
	<button class="am-btn am-btn-default am-btn-xs am-btn-closeopen">我不需要扫描,跳过</button>
	</div>
	<div class="dis30"></div>
</div>
</script>
<script type="text/template" id="tpl_import_result">
<#macro rowedit data>
<div class="import_record_box">
<#if (data.status == 1)>
   <h1 class="am-text-sm " style="padding:3px 0 3px 12px;border-bottom:solid 1px #efefef;font-size:18px">记录自动导入</h1>
   <#if (data.data.length>0)>
   <div class="import_result" style="height:350px;padding:0 12px;">
   <table class="am-table am-table-striped am-text-xs">
        <col width="30px"/>
        <col width="180px" />
        <col width="70px" />
        <col width="70px"/>
        <col  />
        <col width="60px"/>
        <thead>
        <tr>
        <th><input type="checkbox" data-name="import[]" class="checkall"  checked="checked" /></th>
        <th>主机记录</th>
        <th>记录类型</th>
        <th>线路</th>
        <th>记录值</th>
        <th>TTL</th>
        </tr>
        </thead>
        <tbody class="list">
        <#list data.data as v2>
        <tr>
        <td><input type="checkbox" name="import[]" checked="checked" />
        <input type="hidden" name="RRname" value="${v2.name}" />
        <input type="hidden" name="RRtype" value="${v2.type}" />
        <input type="hidden" name="acl" value="${v2.acl}" />
        <input type="hidden" name="RRvalue" value="${v2.val}" />
        <input type="hidden" name="RRttl" value="${v2.ttl}" />
        </td>
        <td>${v2.name}</td>
        <td>${v2.type}</td>
        <td>默认</td>
        <td>${v2.val}</td>
        <td>${v2.ttl}</td>
        </tr>
        </#list>
        </tbody>
    </table>
   </div>
   <div class="import_result">
     <div class="am-fl am-u-sm-7 am-text-xs ">
	   扫描成功，您可以点击“确定”导入，也可以点击“取消”进行手动导入 <br/>
	共扫描到记录 <font class="am-text-danger">${data.data.length}</font> 条，若有漏掉的记录，可在记录导入后手动添加下
	</div>
	<div class="am-fr am-u-sm-4 am-text-right" >
		<button class="am-btn am-btn-success am-btn-sm am-btn-saveimport" type="button">确定</button>
	    <button class="am-btn am-btn-default am-btn-sm am-btn-closeopen" type="button" >取消</button>
	</div>
   <div class="am-cf"></div>
    <div class="dis30"></div>
   </div>
  <#else>
    <div class="dis10"></div>
	<div class="no am-text-center">未扫描到结果,请手动添加纪录</div>
	<div class="dis10"></div>
		<div class="am-text-center">
		<button class="am-btn am-btn-danger am-btn-xs am-btn-closeopen">手动添加</button>
		</div>
	<div class="dis30"></div>
  </#if>
<#else>
<div class="dis10"></div>
<div class="no am-text-center">扫描出错: ${data.msg}</div>
<div class="dis10"></div>
	<div class="am-text-center">
	<button class="am-btn am-btn-danger am-btn-xs am-btn-closeopen">关闭</button>
	</div>
<div class="dis30"></div>
</#if>
</div>
</#macro>
</script>
<script type="text/javascript">
	var domain_import = {
		scan:{
			timer:0,
			pluss:0,
			cancel:0,
			run:function(){
				//初始
				domain_import.scan.cancel = 0;
				domain_import.scan.pluss = 0;
				window.clearInterval(domain_import.scan.timer);
				//打开loading
				$.ui.open($("#tpl_import_loading").html(),function(){
					$.ui.open_close();
					domain_import.scan.cancel = 1;
					return false;
				},700);
				$(".am-btn-closeopen").bind("click",function(){					
					domain_import.scan.cancel = 1;
					$.ui.open_close();
					return false;
				});						
				domain_import.scan.timer = window.setInterval(function(){
					var completed = 0;
					domain_import.scan.pluss = domain_import.scan.pluss + 1;
					completed = domain_import.scan.pluss; //parseInt(Math.random()*5);
				    completed = completed>=100?100:completed;
					$(".import_record_box .am-progress-bar").width(completed+"%");
				},200);

				$.ajaxPassport({
	            	url:U("/api/DomainRecord.ScanImport"),
	            	success:function(res){
	            		window.clearInterval(domain_import.scan.timer);
	            		$(".import_record_box .am-progress-bar").animate({"width":"100%"},"fast");
	            		if(domain_import.scan.cancel == 1){
	            			domain_import.scan.cancel = 0;
	            			return false;
	            		}
	            		setTimeout(function(){
	            			var pophtml = ""+easyTemplate($("#tpl_import_result").html(),res);
	            			$.ui.open(pophtml,null,800);

	            			$(".am-btn-closeopen").bind("click",function(){
								$.ui.open_close();
								domain_import.scan.cancel = 1;
							});
							$(".import_record_box").find("input.checkall").unbind("click").bind("click",function(){
					            $.check_all(this);
					        });
							//保存扫描
							$(".import_record_box").find("button.am-btn-saveimport").unbind("click").bind("click",function(){
								    var length = $(".import_record_box").find("tbody.list tr").length;
								    var success = 0;
								    var fails = 0;
								    var len = 0;
									$(".import_record_box").find("tbody.list tr").each(function(i){
										var obj = this;
										var ischecked = $(obj).find("input[name='import[]']").get(0).checked;
										if(ischecked){
											var data = {};
											data.RRname = $(obj).find("input[name='RRname']").val();
											data.RRvalue = $(obj).find("input[name='RRvalue']").val();
											data.RRtype = $(obj).find("input[name='RRtype']").val();
											data.RRttl = $(obj).find("input[name='RRttl']").val();
											data.acl = $(obj).find("input[name='acl']").val();
											data.RRname = data.RRname == "@"?"":data.RRname;
											data.domain_id = "<?php echo isset($domain_id)?$domain_id:"";?>";
											data.record_id = 0;
											setTimeout(function () {
												$.ajaxPassport({
													url:U("/api/DomainRecord.EditByUid"),
													success: function(res){
														if(res.status == 1){
															success ++;
															$(obj).attr("class","am-success");
														}else{
															fails ++;
															$(obj).attr("class","am-danger");
														}
														len++;
														if(len == length){
															$(".import_record_box .import_result .am-fl").html("成功导入"+success+"条,失败"+fails+"条");
															$(".import_record_box .import_result .am-fr").html('<button class="am-btn am-btn-default am-btn-sm am-btn-closeopen" type="button" >完成</button>');
															$(".am-btn-closeopen").bind("click",function(){
																$.ui.open_close();
																get_records_list();
																$(".am-import-btn").hide();
															});
														}
													},
													data:data,
												})
											},i*200);
										}else{
											len++;
										}
									});

							});
	            		},1000);	            		
	            	},
	            	data:{domain:"<?php echo isset($domain)?$domain:"";?>"}
	        	});
			}
		},
	}
	$(function(){
		 //加载完成后是否扫描记录
         if(parseInt("<?php echo isset($domain_row['records'])?$domain_row['records']:"";?>") === 0){
            domain_import.scan.run();
              $(".am-import-btn").bind("click",function(){
                domain_import.scan.run();
               });
         }
	})
    
</script>