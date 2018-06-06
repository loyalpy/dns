<!--域名升级套餐-->
<script type="text/template" id="tpl_domain_upgrade">
    <#macro rowedit data>
        <div class="cartbox">
        <div class="bd-top" style="display: none;">小贴士: 可以支持多个域名购买,请先加入购物车</div>
        <div class="bd-middle ">
            <h1>购买域名<#if ($.is_empty(data.goods_name))>  (<font class="am-text-xs">套餐:</font> <font class="am-text-warning">${data.service_group_name}</font> <font class="am-text-xs">价格:</font><font class="am-text-sm am-text-warning service_group_price">${data.service_price}<input style="display:none;" type="checkbox" checked="checked" name="service_group" data-price="${data.service_price}" value="${data.service_group}" /> </font><font class="am-text-xs">/月</font>)</#if> </h1>
            <table class="am-table am-table-hover">
                <col width="30px;"/>
                <col width="200px;"/>
                <col width="185px;"/>
                <col width="50px;"/>
                <col />
                <tbody class="add-cart-domainlist">
                <#list data.domainlist as domain>
                    <tr>
                        <td>
                            <input type="checkbox" checked="checked" name="domains" value="${domain.domain}" />
                        </td>
                        <td class="am-text-left"><font class="am-text-sm am-text-primary"> ${domain.domain}</font><font class="am-text-gray am-text-xs "></font> </td>
                        <td class="am-text-left">
                            <select name="num" style="padding:7px 9px;width:75px;font-size:12px;">
                                <?php foreach($data_config['service_num'] as $key => $item){?>
                                <#if (data.utype == 2)>
                                    <?php if($key>9){?>
                                    <option <#if (domain.num == '<?php echo isset($key)?$key:"";?>')>selected="selected"</#if> value="<?php echo isset($key)?$key:"";?>"><?php echo isset($item)?$item:"";?></option>
                                    <?php }?>
                                    <#else>
                                        <?php if($key<=10){?>
                                        <option <#if (domain.num == '<?php echo isset($key)?$key:"";?>')>selected="selected"</#if> value="<?php echo isset($key)?$key:"";?>"><?php echo isset($item)?$item:"";?></option>
                                        <?php }?>
                                </#if><?php }?>
                            </select>
                        </td>
                        <td><font class="am-text-warning am-text-lg amount">0.00</font><span class="am-text-sm am-text-gray">元</span> </td>
                        <td class="amount_promation_tr"><#if (parseInt(amount_rate_set) != 100)><font class="am-text-sm am-text-gray">(共优惠</font><font class="am-text-success amount_promation">0.00</font><span class="am-text-sm am-text-gray">元)</span></#if> </td>
                    </tr>
                </#list>
                </tbody>
            </table>
            <#if ($.is_empty(data.goods_name))><div class="pagebar">${data.pagebar}</div></#if>
            <#if (!$.is_empty(data.goods_name))>
                <div class="service_group_nav profile_information records-nav" >
                    <ul class="">
                        <#if (data.utype == 1)>
                        <li><a href="javascript:void (0);">个人套餐</a></li>
                        <li><a href="javascript:void (0);">企业套餐</a></li>
                        <#else>
                        <li><a href="javascript:void (0);">企业套餐</a></li>
                        </#if>
                    </ul>
                    <#if (data.utype == 1)>
                    <span id="com_show" style="font-size: 12px;color: gray;position:relative;top:8px;left:80px;">(系统检测为个人用户，请先<a href="<?php echo U("/ucenter/profile_basic_com");?>">完善</a>企业资料，再进行升级！)</span>
                    </#if>
                </div>
                <div class="service_group_nav_content">
                        <table class="am-table am-table-hover" style="display:none;">
                            <col width="30px;"/>
                            <col width="110px;"/>
                            <col width="110px;"/>
                            <col />
                            <col width="120px;"/>
                            <col width="100px;"/>
                            <col width="100px;"/>
                            <col width="50px;"/>
                            <thead>
                            <tr>
                                <th></th>
                                <th>版本</th>
                                <th>负载均衡</th>
                                <th>域名攻击防护 QPS</th>
                                <th>流量攻击防护</th>
                                <th>监控任务数</th>
                                <th>价格(元/月)</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <#list data.person_service_list as domain_service>
                                <#if (data.utype ==1 && domain_service.sort > 0 && domain_service.sort >= data.sort)>
                                    <tr>
                                        <td>
                                            <input type="radio" name="service_group" data-price="${domain_service.cost1}"  value="${domain_service.service_group}"/>
                                        </td>
                                        <td><a href="javascript:void(0)" class="am-text-sm service_group_name" data-service_group="${domain_service.service_group}">${domain_service.name}</a> <#if (data.service_group ==domain_service.service_group )>(续费)<#else>(升级)</#if></td>
                                        <td>${domain_service.data.loadBalanc}</td>
                                        <td>${domain_service.data.QPS} Q/s</td>
                                        <td>${domain_service.data.DDOS} G</td>
                                        <td>${domain_service.data.monitorTask} 个</td>
                                        <td>${domain_service.cost1}</td>
                                        <td><a href="<?php echo U("home@/product/buy");?>" target="_blank">详情</a></td>
                                    </tr>
                                </#if>
                            </#list>
                            </tbody>
                        </table>
                        <table class="am-table am-table-hover" style="display:none;">
                            <col width="30px;"/>
                            <col width="110px;"/>
                            <col width="110px;"/>
                            <col />
                            <col width="120px;"/>
                            <col width="100px;"/>
                            <col width="100px;"/>
                            <col width="50px;"/>
                            <thead>
                            <tr>
                                <th></th>
                                <th>版本</th>
                                <th>负载均衡</th>
                                <th>域名攻击防护 QPS</th>
                                <th>流量攻击防护</th>
                                <th>监控任务数</th>
                                <th>价格(元/月)</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <#list data.company_service_list as domain_service_com>
                                <#if ((data.utype ==2 && parseInt(domain_service_com.sort) > 0 && parseInt(domain_service_com.sort) >= parseInt(data.sort)) || (data.utype==1))>
                                    <tr>
                                        <td>
                                            <input type="radio" <#if (data.utype== 1)>disabled="disabled"<#else>name="service_group"</#if> data-price="${domain_service_com.cost1}"  value="${domain_service_com.service_group}" />
                                        </td>
                                        <td>${domain_service_com.name} <#if (data.service_group ==domain_service_com.service_group )>(续费)<#else>(升级)</#if></td>
                                        <td>${domain_service_com.data.loadBalanc}</td>
                                        <td>${domain_service_com.data.QPS} Q/s</td>
                                        <td>${domain_service_com.data.DDOS} G</td>
                                        <td>${domain_service_com.data.monitorTask} 个</td>
                                        <td>${domain_service_com.cost1}</td>
                                        <td><a href="<?php echo U("home@/product/buy?type=company");?>" target="_blank">详情</a></td>
                                    </tr>
                                </#if>
                            </#list>
                            </tbody>
                        </table>
                    </div>
            </#if>
            <div class="dis10"></div>

            <table class="am-table am-table-hover buy-sms-table">
                <col />
                <col width="100px;"/>
                <col width="150px;"/>
                <col width="120px;"/>
                <thead>
                <tr>
                    <th>增值服务</th>
                    <th>单价</th>
                    <th>数量</th>
                    <th>总费用</th>
                </tr>
                </thead>
                <tbody class="add-sms-list">
                <tr>
                    <td><input type="checkbox" name="sms-goods" /><span class="zz">短信100条</span></td>
                    <td><span class="sms-price">20</span>元</td>
                    <td>
                        <select name="sms-num" style="width: 60px">
                            <?php foreach(array(1,2,3,4,5,6,7,8,9,10) as $key => $item){?>
                            <option><?php echo isset($item)?$item:"";?></option>
                            <?php }?>
                        </select>
                    </td>
                    <td><span class="sms-total">20.00</span>元</td>
                </tr>
                </tbody>
            </table>
            <div class="am-text-center">
                <button type="button" class="am-btn am-btn-warning btn-sumit-add-cart">加入购物车</button>&nbsp;&nbsp;&nbsp;
                <button type="button" class="am-btn am-btn-default " onclick="$.ui.open_close()">返回</button>
            </div>
            <div class="dis15"></div>
        </div>
        </div>
    </#macro>
</script>
<!--加入购物车成功-->
<script type="text/template" id="tpl_domain_cart">
    <#macro rowedit data>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
            <div class="am-modal-dialog">
                <div class="am-modal-hd">
                    <i class="am-icon-check-circle" style="color: #5EB95E;"></i>&nbsp;&nbsp;加入购物车成功！
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                </div>
                <div class="dis20"></div>
                <div class="am-modal-bd">
                    <a href="javascript: void(0)" data-am-modal-close><button type="button" class="am-btn am-btn-default">继续购买</button></a>&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo U("/order/cart_shopping");?>"><button type="button" class="am-btn am-btn-warning">去购物车结算</button></a>
                </div>
                <div class="dis15"></div>
            </div>
        </div>
    </#macro>
</script>
<?php echo $this->fetch('order/order_tpl')?>
<script language="JavaScript">
    var person_service_list =  <?php echo JSON::encode(array_values(M("@domain_service")->get_cache_list(1,true)));?>;
    var company_service_list =  <?php echo JSON::encode(array_values(M("@domain_service")->get_cache_list(2,true)));?>;
    //判断是否有折扣
    var amount_rate_set = <?php echo isset($this->userinfo['setting']['rate'])?$this->userinfo['setting']['rate']:100;?>;
    //价格计算
    var acc_amount = function(trobj){
        var obj = $(".cartbox").find("input[name='service_group']:checked");
        if(!obj.get(0)){
            $.ui.error("请先选择套餐");
            return false;
        }
        var service_group =  $(obj).val();
        var service_price   = $(obj).data("price");

        if($.is_empty(trobj)){
            $(".add-cart-domainlist tr").each(function(){
                var obj = this;
                var num = $(obj).find("select[name='num']").val();
                var total  = $.to_float2(parseInt(num) *parseFloat(service_price));

                //判断是否有折扣
                if (parseInt(amount_rate_set) == 100) {
                    $(obj).find(".amount").html(total);
                    $(obj).find(".amount_promation_tr").html("");
                }else{
                    var amount_promation = $.to_float2(total*(100-amount_rate_set)/100);
                    $(obj).find(".amount").html($.to_float2(total-amount_promation));
                    $(obj).find(".amount_promation").html(amount_promation);
                }
            })
        }else{
            var num = $(trobj).find("select[name='num']").val();
            var total  = $.to_float2(parseInt(num) *parseFloat(service_price));

            //判断是否有折扣
            if (parseInt(amount_rate_set) == 100) {
                $(trobj).find(".amount").html(total);
                $(trobj).find(".amount_promation_tr").html("");
            }else{
                var amount_promation = $.to_float2(total*(100-amount_rate_set)/100);
                $(trobj).find(".amount").html($.to_float2(total-amount_promation));
                $(trobj).find(".amount_promation").html(amount_promation);
            }
        }
    }
    //加入购物车 type 0,域名套餐  1短信
    var add_cart_step1 = function(page,type,goods_no,goods_name){
        var url = "";
        var tpl = "";
        if(type ==1){

        }else{
            url = U("/api/DomainService.GetPList");
            tpl = "tpl_domain_upgrade";
        }
        $.ui.loading();
        $.ajaxPassport({
            url:url,
            data:{goods_name:goods_name,goods_no:goods_no,page:page},
            success:function(res){
                $.ui.close_loading();
                res.data.goods_no  = goods_no;
                res.data.goods_name = goods_name;
                res.data.person_service_list    = person_service_list;
                res.data.company_service_list = company_service_list;
                var html = ""+ easyTemplate($("#"+tpl).html(),res.data);
                $.ui.open(html,function(){
                    $.ui.open_close();
                    return false;
                },800,620);
                if($.is_empty(goods_name)){
                    acc_amount();
                }else {
                    $(".service_group_nav").find("li").unbind("click").bind("click", function () {
                        var obj = this;
                        if (res.data.utype == 1) {
                            var index = $(obj).index();
                        }else{
                            index = 1;
                        }
                        $(".service_group_nav").find("li").removeClass("cur");
                        $(".service_group_nav").find("li").eq(index).addClass("cur");
                        $(".service_group_nav_content").find("table").hide();
                        $(".service_group_nav_content").find("table").eq(index).show();

                        $(".service_group_nav_content").find("table").eq(index).find("input[name='service_group']").eq(0).attr("checked", true);
                        setTimeout(function () {
                            acc_amount();
                        }, 500);
                    });
                    $(".service_group_nav").find("li").eq(0).click();
                    $(".service_group_nav_content").find("table").find("input[name='service_group']").unbind("click").bind("click", function () {
                        acc_amount();
                    });
                    $(".service_group_nav_content").find("table").find("a.service_group_name").unbind("click").bind("click", function () {
                        var obj = this;
                        var goods_no = $(obj).data("service_group");
                        add_cart_step1(1,0, goods_no, "");
                    })
                }
                //改变域名套餐时间事件
                $(".add-cart-domainlist tr select").unbind("change").bind("change",function(){
                    acc_amount(this.parent);
                });
                //改变短信套餐数量事件
                $(".add-sms-list tr select").unbind("change").bind("change",function(){
                    var obj = $(".cartbox").find("input[name='service_group']:checked");
                    if(!obj.get(0)){
                        $.ui.error("请先选择套餐");
                        return false;
                    }
                    var num = $(".add-sms-list tr").find("select[name='sms-num']").val();
                    var service_price = $(".add-sms-list tr").find(".sms-price").text();
                    var total  = $.to_float2(parseInt(num) *parseFloat(service_price));

                    $(".add-sms-list tr").find(".sms-total").html(total);
                });
                //提交购物车事件
                $(".btn-sumit-add-cart").unbind("click").bind("click",function(){
                    add_cart_step2();
                })
            },
        });
    }
    var success_num = 0;
    //加入购物车
    var add_cart_step2 = function(type){
        var type = typeof type=="undefined"?0:type;
        if(type == 0){

            if($(".cartbox").find('input[name="domains"]:checked').length <=0){
                $.ui.error("请选择需要购买的服务");
            }
            batch_cart_add_domain(0);
        }
    };
    //批量加入购物车
    var batch_cart_add_domain = function(i){
        var obj = $(".add-cart-domainlist").find("tr").eq(i);
        if( i >= $(".add-cart-domainlist").find("tr").length || !obj){
            return false;
        }

        var ischeck_obj = $(obj).find('input[name="domains"]:checked').get(0);
        if(!ischeck_obj){
            $(obj).addClass("am-danger");
            batch_cart_add_domain(i+1);
            //执行最后一次时提示操作结果
            if (i == ($(".add-cart-domainlist").find("tr").length - 1)) {
                if (success_num > 0) {//如果有一个成功的，就提示跳转成功。

                    $.ui.close_loading();
                    $.ui.open_close();
                    var html = "" + easyTemplate($("#tpl_domain_cart").html());
                    $(".my-domian-upgrade").html(html);
                    $(".my-domian-upgrade").find('#doc-modal-1').modal({width: 300});
                }
            }
        }else {
            var domain = $(ischeck_obj).val();
            var num = $(obj).find("select[name='num']").val();
            var service_group = $(".cartbox").find("input[name='service_group']:checked").val();
            var price = $(".cartbox").find("input[name='service_group']:checked").data("price");
            $.ui.loading();
            $.ajaxPassport({
                url: U("/api/Cart.Add"),
                success: function (res) {
                    if (res.status == 1) {
                        success_num++;
                        $(obj).addClass("am-success");
                    }else{
                        $(obj).addClass("am-danger");
                    }
                    //执行最后一次时提示操作结果
                    if (i == ($(".add-cart-domainlist").find("tr").length - 1)) {

                        //添加增值短信服务,批量域名套餐添加成功后操作
                        if($(".cartbox").find('input[name="sms-goods"]:checked').length > 0){
                            var sms_service_group = $(".add-sms-list tr").find(".zz").text();
                            var sms_num = $(".add-sms-list tr").find("select[name='sms-num']").val();
                            var sms_price = $(".add-sms-list tr").find(".sms-price").text();
                            $.ajaxPassport({
                                url:U("/api/Cart.Add"),
                                success: function (res) {
                                },
                                data:{type:1,service_group:sms_service_group,num:sms_num,price:sms_price}
                            });
                        }

                        $.ui.close_loading();
                        if (success_num > 0) {
                            $.ui.open_close();
                            var html = "" + easyTemplate($("#tpl_domain_cart").html());
                            $(".my-domian-upgrade").html(html);
                            $(".my-domian-upgrade").find('#doc-modal-1').modal({width: 300});

                            domain_register_tips();
                        } else {
                            $.ui.error(res.msg);
                        }
                    }
                    batch_cart_add_domain(i + 1);
                },
                data: {domain:domain,num:num,service_group:service_group,price:price},
            });
        }
    }
</script>