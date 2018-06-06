$(function () {
    //加载查询结果，购物车
    load_check_ret();
    //加载更多域名查询结果
    $(".mod-box-bd .domain-more").click(function () {
        load_check_ret_more();
    });
    //删除购物车
    cart_del();
    domain_public_fun();
    //全部加入清单
    $(".batch-op-add").click(function () {
        if ($.is_empty(uid)) {
            $('#myModal').modal();
            $("#myModal").find(".modal-dialog").width(500);
            return false;
        }
        $(".serch-result-box .mod-box-bd ul li").each(function (i) {
            var obj = this;
            if ($(obj).css("display") == "list-item") {
                var domain = $(obj).find(".s-domain").html();
                var price = $(obj).find(".s-price").html();
                if (typeof domain != "undefined") {
                    setTimeout(function () {
                        batch_op_add(obj,domain,price);
                    },500);
                }
            }
        });
        $(this).attr("disabled","disabled");
    });
    //点击域名后缀显示隐藏事件
    $(".show-multiple").click(function () {
        var s_show = $(".search-extension").css("display");
        if (s_show == "none") {
            $(".search-extension").show();
        } else {
            $(".search-extension").hide();
        }
    });
    //点击选择域名后缀
    $(".hot_tlds").click(function () {
        $(".search-extension").hide();
        var suffix = $(this).html();
        $("input[name='suffix']").val(suffix);
        $(".show-multiple").html("<span class='suffix'>"+suffix+"</span>"+" <span class='caret'></span>");
    });
    //多选模式c1
    $(".domain-c .c1").click(function () {
        type = 0;
        $(this).addClass("active");
        $(".domain-c .c2").removeClass("active");
        $("#collapseExample").removeClass("in").addClass("collapse");
        $("input[name='type']").val(0);
        $("input[name='suffixs']").val('');

        $(".domain-s-box .ser").removeClass("ser-more");
        $(".domain-s-box .ser").find(".show-multiple").show();
    });
    //多选模式c2
    $(".domain-c .c2").click(function () {
        type = 1;
        $(this).addClass("active");
        $(".domain-c .c1").removeClass("active");
        $("input[name='type']").val(1);
        
        $(".domain-s-box .ser").addClass("ser-more");
        $(".domain-s-box .ser").find(".show-multiple").hide();
    });
})
//删除购物车
var cart_del = function () {
    $(".btn-del").unbind("click").bind("click",function () {
        var obj = this;
        var domain = $(this).parent().find(".ellipsis").html();

        $.ui.loading();
        $.ajaxPassport({
            url:U("/cart/del_cart"),
            success:function (res) {
                $.ui.close_loading();
                if (res.error == 0) { //ok
                    var cart_num = $(".serch-cart").find(".cart-num").html();
                    $(".serch-cart").find(".cart-num").html(cart_num-1);
                    $(obj).parent().remove();

                    if ($(".serch-cart").find("ul li").length == 0) {
                        $(".serch-cart .a-c-1").show();
                        $(".serch-cart .a-c-2").hide();
                    }
                    $(".hot-list").find("li .s-domain").each(function () {
                        if ($(this).html() == domain ) {
                            $(this).parent().parent().parent().find(".add-cart-btn button").attr("disabled",false);
                            $(this).parent().parent().parent().find(".add-cart-btn button").html("加入购物车");
                        }
                    });
                }else{//false
                    $.ui.error(res.message);
                }
            },
            data:{domain:domain}
        });
    })
}
var domain_public_fun = function () {
    //鼠标经过更多价格显示价格对比
    $(".select-more-price-link").mousemove(function () {
        $(this).parent().parent().find(".tc-15-bubble-top").show();
    });
    $(".select-more-price-link").mouseleave(function () {
        $(this).parent().parent().find(".tc-15-bubble-top").hide();
    });
    //加入购物车
    $(".add-cart-btn").unbind("click").bind("click",function () {
        var obj = this;
        if ($.is_empty(uid)) {
            $('#myModal').modal();
            $("#myModal").find(".modal-dialog").width(500);
            return false;
        }
        var domain = $(obj).parent().parent().parent().find(".s-domain").html();
        var price = $(obj).parent().parent().parent().find(".s-price").html();
        batch_op_add(obj,domain,price);
    })
}
//加载查询结果
var load_check_ret = function () {
    //查询结果显示
    var domain = $("input[name='domain']").val();
    if ($.is_empty(domain)) {
        // $.ui.error("请输入要查询的域名！");
        return false;
    }
    $(".mod-box-bd .sold").each(function (i) {
        if (i <= 18) {
            var obj = this;
            var suffix = $(obj).find("span.s").html();
            check_domain_post(obj,domain,suffix);
        }
    });
}
//加载更多查询结果
var load_check_ret_more = function () {
    //查询结果显示
    $(".domain-more-h").show();
    var domain = $("input[name='domain']").val();
    $(".mod-box-bd .sold").each(function (i) {
        var obj = this;
        var suffix = $(obj).find("span.s").html();
        check_domain_post(obj,domain,suffix);
    });
    $(".mod-box-bd .domain-more").addClass("back-domain-more");
    $(".mod-box-bd .domain-more").html("收起 <i class='glyphicon glyphicon-chevron-up'></i>");

    //点击收起
    $(".back-domain-more").click(function () {
        $(".domain-more-h").hide();
        $(".mod-box-bd .domain-more").removeClass("back-domain-more");
        $(".mod-box-bd .domain-more").html("查看更多后缀 <i class='glyphicon glyphicon-chevron-down'></i>");
        $(".mod-box-bd .domain-more").click(function () {
            load_check_ret_more();
        });
    });
}
//公用加入购物车
var batch_op_add = function (obj,domain,price) {
    $.ajaxPassport({
        url:U("/cart/add_cart"),
        success:function (res) {
            if (res.error == 0) {
                $('.serch-cart .a-c-1').hide();
                $('.serch-cart .a-c-2').show();

                if (res.message != 1) {
                    var cart_num = $(".serch-cart").find(".cart-num").html();
                    var html = "<li><span class='pen-d-s' ><span class='ellipsis'>"+res.message+"</span></span><a href='javascript:void(0);' class='btn-del'><i class='glyphicon glyphicon-remove'></i></a></li>";
                    $(".serch-cart").find(".cart-num").html(parseInt(cart_num)+1);
                    $(".pending-buy ul").append(html);
                }

                $(obj).find("button").attr("disabled","disabled");
                $(obj).find("button").html("已加入清单");

                cart_del();
            }else{
                $.ui.error(res.message);
            }
        },
        data:{domain:domain,price:price}
    });
}
//公用域名查询check
var check_domain_post = function (obj,domain,suffix) {
    $.ajaxPassport({
        url:U("/domain/check"),
        success:function (res) {
            if (res.status != 1) {
                $(obj).parent().parent().parent().html("<div>认证失败</div>");
                return false;
            }
            if (res.list[0]['chk'] == 0) { //不可注册
                var html = "<div class='d-ib domain-cell'>"+
                    "<div class='domain'>"+res.list[0]['domain']+res.list[0]['suffix']+"</div>"+
                    "</div>"+
                    "<div class='mod-select-else-fr'>"+
                    "<div class='d-ib price-cell'>已注册</div>"+
                    "<div class='d-ib action-cell'><a href="+"/domain/check_info?domain="+res.list[0]['domain']+res.list[0]['suffix']+" target='_blank'>查询注册信息</a></div>"+
                    "</div>";
            }else{ //可以注册
                var html = "<div class='d-ib domain-cell'>"+
                    "<div class='domain'><span style='color: #000000' class='s-domain'>"+res.list[0]['domain']+res.list[0]['suffix']+"</span></div>"+
                    "</div>"+
                    "<div class='mod-select-else-fr'>"+
                    "<div class='d-ib price-cell'>"+
                    "<div class='td-inner'><span class='special-offer'>¥<strong class='s-price'>"+res.list[0]['new_price']+"</strong></span></div>"+
                    "</div>"+
                    "<div class='select-more-price'><a href='javascript:;' class='select-more-price-link'>更多价格对比</a></div>"+
                    "<div class='d-ib action-cell'><a href='javascript:;' class='add-cart-btn'><button type='button' class='btn btn-default btn-sm'>加入购物车</button></a></div>"+
                    "<div class='tc-15-bubble  tc-15-bubble-top' style='margin: 15px 5px; display: none;'>"+
                    "<div class='tc-15-bubble-inner'>"+
                    "<table class='tc-15-bubble-inner-cont' cellpadding='0' cellspacing='0'>"+
                    "<tbody>"+
                    "<tr>"+
                    "<td class='table-title' colspan='4'>新购</td>"+
                    "</tr>"+
                    "<tr>"+
                    "<td>1年</td>"+
                    "<td>3年</td>"+
                    "<td>5年</td>"+
                    "<td>10年</td>"+
                    "</tr>"+
                    "<tr class='price'>"+
                    "<td>¥"+res.list[0]['new_price']+"</td>"+
                    "<td>¥"+parseInt(res.list[0]['new_price']+res.list[0]['renew_price']*2)+"</td>"+
                    "<td>¥"+parseInt(res.list[0]['new_price']+res.list[0]['renew_price']*4)+"</td>"+
                    "<td>¥"+parseInt(res.list[0]['new_price']+res.list[0]['renew_price']*9)+"</td>"+
                    "</tr>"+
                    "<tr>"+
                    "<td class='table-title' colspan='4'>续费</td>"+
                    "</tr>"+
                    "<tr>"+
                    "<td>1年</td>"+
                    "<td>3年</td>"+
                    "<td>5年</td>"+
                    "<td>10年</td>"+
                    "</tr>"+
                    "<tr class='price price-1'>"+
                    "<td>¥"+res.list[0]['renew_price']+"</td>"+
                    "<td>¥"+res.list[0]['renew_price']*3+"</td>"+
                    "<td>¥"+res.list[0]['renew_price']*5+"</td>"+
                    "<td>¥"+res.list[0]['renew_price']*10+"</td>"+
                    "</tr>"+
                    "</tbody>"+
                    "</table>"+
                    "</div>"+
                    "</div>"+
                    "</div>";
            }
            $(obj).parent().parent().html(html);
            domain_public_fun();
        },
        data:{domain:domain,suffix:suffix}
    });
}