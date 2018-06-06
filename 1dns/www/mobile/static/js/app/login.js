mui.init();
app.user.state(1);
if(app.ldb.st() == 0){
	app.ui.tip("不支持本地存储");
}
//初始化单页view
var viewApi = mui('#app').view({
	defaultPage: '#login'
});
//初始化单页的区域滚动
mui('.mui-scroll-wrapper').scroll();

app.on("#btnlogin","tap",function(){
	var userinfo = {uname:mui("#login_uname")[0].value,upass:mui("#login_upass")[0].value};
	app.user.login(userinfo);
});
app.on("#btnregister","tap",function(){
	var userinfo = {email:mui("#reg_email")[0].value,upass:mui("#reg_upass")[0].value,upass2:mui("#reg_upass2")[0].value};
	app.user.register(userinfo);
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

app.getJSON(app.u('/login'))