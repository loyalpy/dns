mui.init({statusBarBackground: '#f7f7f7'});
//初始化单页view
var viewApi = mui('#app').view({
	defaultPage: '#about_nav'
});
//初始化单页的区域滚动
mui('.mui-scroll-wrapper').scroll();
 //请求成功后
app.ui.loading();
app.getJSON(app.u("/site/about"),{},function(res){
	if(res.error == 1){
        app.alert("请求出错");
     }else{
     	app.ui.close_loading();
		var navhtml = app.tpl("#tpl_aboutnav",res);
		$("#navcontent").html(navhtml);
        $("#navcontent li").each(function(index){
        	var obj = this;
        	$(obj).data("content",res[index]['content']);
        	app.on("#"+$(obj).attr("id"),"tap",function(){
        		var obj2 = this;
        		$("#about_detail .mui-title").html($(obj2).text());
        		$("#about_detail .c").html($(obj2).data("content"));
        		viewApi.go("#about_detail");
        	});
        })
    }
});
//处理view的后退与webview后退
var oldBack = mui.back;
mui.back = function() {
	if (viewApi.canBack()) { //如果view可以后退，则执行view的后退
		viewApi.back();
	} else { //执行webview后退
		oldBack();
	}
};
//监听页面切换事件方案1,通过view元素监听所有页面切换事件，
//目前提供pageBeforeShow|pageShow|pageBeforeBack|pageBack四种事件
//(before事件为动画开始前触发)
//第一个参数为事件名称，第二个参数为事件回调，其中e.detail.page为当前页面的html对象
viewApi.view.addEventListener('pageBeforeShow', function(e) {
	//app.ui.loading();
	//console.log(e.detail.page.id + ' beforeShow');
});
viewApi.view.addEventListener('pageShow', function(e) {
	//app.ui.close_loading();
});
viewApi.view.addEventListener('pageBeforeBack', function(e) {
	
});
viewApi.view.addEventListener('pageBack', function(e) {
	
});	

mui.scrollTo(0,1000);