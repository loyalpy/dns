mui.init();
//初始化单页view
(function($) {
	$('#scroll').scroll({
		indicators: true //是否显示滚动条
	});
})(mui);
//初始化单页的区域滚动
mui('.mui-scroll-wrapper').scroll();
//请求成功后
app.ui.loading();
// 帮助首页
(function($){
	//搜素事件
	app.on(".mui-search","tap",function () {
		var keyword =$("input[name='keyword']").val();
		if (!keyword) {
			return false;
		}
		app.redirect("/mobile/views/helpdetail.html?keyword="+ encodeURI(encodeURI(keyword)));
	})
	var get_nav = function(){
		app.getJSON(app.u("/site/helper_cate"),{},function(res){
			
			if(res.error == 1){
			}else{
				app.ui.close_loading();
				$(".mui-content").html(app.tpl("#tpl_helpnav",res));

				$("#segmentedControl").find("a").each(function(){
					var obj = this;

					app.on("#"+$(obj).attr("id"),"tap",function(){
						var obj  = this;
						var id = $(this).attr("id");
						$("#segmentedControl").find("a").removeClass("mui-active");
						$(obj).addClass("mui-active");

						$(".navlisth-content >div").hide();
						$("#content_"+id).show();
					})
				});
			}
		});
	}
	var get_subnav = function(){

	}
	//读取导航
	get_nav();
})($);
