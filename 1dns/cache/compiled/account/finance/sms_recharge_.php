<!--域名升级套餐-->
<script type="text/template" id="tpl_domain_upgrade">
    <#macro rowedit data>
        <div class="cartbox">
            <div class="bd-middle am-form">
                <div class="dis10"></div>
                <h1>短信充值</h1>
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
                        <td><span class="zeng" style="color: #0E90D2">短信100条</span></td>
                        <td><span class="sms-price">20</span>元</td>
                        <td>
                            <select name="sms-num" style="width: 55px">
                                <?php foreach(array(1,2,3,4,5,6,7,8,9,10) as $key => $item){?>
                                <option value="<?php echo isset($item)?$item:"";?>"><?php echo isset($item)?$item:"";?></option>
                                <?php }?>
                            </select>
                        </td>
                        <td><span class="sms-total">20.00</span>元</td>
                    </tr>
                    </tbody>
                </table>
                <div class="dis30"></div>
                <div class="am-text-center">
                    <button type="button" class="am-btn am-btn-warning btn-sumit-add-cart">加入购物车</button>&nbsp;&nbsp;&nbsp;
                    <a type="button" class="am-btn am-btn-default " onclick="$.ui.open_close();">取消</a>
                </div>
                <div class="dis15"></div>
            </div>
        </div>
    </#macro>
</script>
<script language="JavaScript">
    //加入购物车
    var add_cart_step1 = function(){
        var html = ""+ easyTemplate($("#tpl_domain_upgrade").html());
        $.ui.open(html,function(){
            $.ui.open_close();
            return false;
        },650,400);
        //改变短信套餐数量事件
        $(".add-sms-list tr select").unbind("change").bind("change",function(){
            var num = $(".add-sms-list tr").find("select[name='sms-num']").val();
            var service_price = $(".add-sms-list tr").find(".sms-price").text();
            var total  = $.to_float2(parseInt(num) *parseFloat(service_price));

            $(".add-sms-list tr").find(".sms-total").html(total);
        });
        //提交购物车事件
        $(".btn-sumit-add-cart").unbind("click").bind("click",function(){
            add_cart_step2();
        })
    }
    //添加增值短信服务
    var add_cart_step2 = function(){
        var sms_service_group = $(".add-sms-list tr").find(".zeng").text();
        var sms_num = $(".add-sms-list tr").find("select[name='sms-num']").val();
        var sms_price = $(".add-sms-list tr").find(".sms-price").text();
        $.ajaxPassport({
            url:U("/api/Cart.Add"),
            success: function (res) {
                if (res.status == 1) {
                    $.ui.open_close();
                    window.location.replace("<?php echo U("account@/order/cart_shopping");?>");
                }else{
                    $.ui.error(res.msg);
                }
            },
            data:{type:1,service_group:sms_service_group,num:sms_num,price:sms_price}
        });
    };

    $(function(){
        $(".sms_recharge_btn").unbind("click").bind("click",function(){
            add_cart_step1();
        })
    })
</script>