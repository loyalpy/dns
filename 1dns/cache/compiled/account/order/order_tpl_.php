<!-- 域名注册购物车tips start-->
<script language="javascript">
    var domain_register_tips = function(){
        $.ajaxPassport({
            url:"<?php echo U("account@/domains/get_domain_tips");?>",
            success:function(res){
                if (res.error == 0) {
                    if (res.message >= 0) {
                        $(".domain-parse-tips").html(res.message);
                    }
                }
            },
        });

    }
</script>
<!-- 域名注册购物车tips  end-->