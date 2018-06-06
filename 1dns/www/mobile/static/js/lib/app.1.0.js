var app = {};
app.url = "";
app.u = function(uri){
	return app.url+uri;
}
app.on = function(obj, event, func){
	$(document).off(event, obj).on(event, obj, func);
};
//URL相关
app.req = function(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null){
    	r = unescape(r[2]);
    }
    if(r !=null && r.toString().length>0){
    	return r;
    }else{
    	return "";
    }
}
//UI相关操作
app.ui = {};
app.ui.loading = function(box,ismask,pos){
 	var ismask  = (typeof ismask == "undefined")?1:ismask;
    var isbody  = (typeof box == "undefined" || box == '')?1:0; 
 	var box     = isbody?$("body"):box;
 	var loading_img  = box.find(".loading_img");
 	var loading_mask = box.find(".loading_mask");
 	
 	var width = height = 50;
 	if(isbody == 1){
 		var top  = parseInt($(document).scrollTop()+(($(window).height()-width)/2));
 	    var left = parseInt(($(window).width()-width)/2);
 	}else{
 		var top  = parseInt((box.height()-width)/2);
 		var left = parseInt((box.width()-width)/2);
 	}
 	if(typeof pos != "undefined" && typeof pos.top != "undefined"){
 		top = pos.top;
 	}
	
 	if(loading_img[0]){
 		loading_img.css({"top":top,"left":left}).show();
 	}else{
 		var tmp_loading_mask = $("<div class='loading_mask' style='background:#000;display:none;position:absolute;top:0;left:0;z-index:990'></div>");
 		var tmp_loading_img = $("<div class='loading_img' style='position:absolute;top:0;left:0;z-index:991;'><div class='imgmask' style='position:absolute;top:0;left:0;width:50px;height:50px;background:#000;border-radius:11px;z-index:0;'></div><span class='mui-spinner' style='position:absolute;top:11px;left:11px;z-index:1;width:28px;height:28px;'></span></div>").css({"width":width,"height":height,"left":left,"top":top});

 		
 	    box.append(tmp_loading_mask).append(tmp_loading_img);
 	    box.find(".loading_img .imgmask").css({"opacity":"0.79"});
 	}
 	if(ismask == 1){
 		box.find(".loading_mask").css({"width":box.width(),"height":box.height(),"opacity":"0.06"}).show();
 	}else{
 		box.find(".loading_mask").hide();
 	}
};
app.ui.close_loading = function(box){
	var box     = (typeof box == "undefined" || box == '')?$("body"):box;
	var loading_img  = box.find(".loading_img");
	var loading_mask = box.find(".loading_mask");
	loading_img.empty().remove();
    loading_mask.empty().remove();
};
// popover
app.ui.pop = function(){
	mui('.mui-popover').popover('toggle');
};

// actionsheet
app.ui.sheet = function(title, btns,func){
	if(title && btns && btns.length > 0){
		var btnArray = [];
		for(var i=0; i<btns.length; i++){
			btnArray.push({title:btns[i]});
		}
		
		plus.nativeUI.actionSheet({
			title : title,
			cancel : '取消',
			buttons : btnArray
		}, function(e){
			if(func) func(e);
		});
	}
};

// 提示框相关
app.ui.alert = function(msg, ok,title){
	var title = typeof title == "undefined"?"八戒DNS":title;
	mui.alert(msg, title, function() {
		if(ok) ok();
	});
};
app.ui.confirm = function(msg, ok, cancel,title){
	var btnArray = ['是', '否'];
	var title = typeof title == "undefined"?"八戒DNS":title;
	mui.confirm(msg, title, btnArray, function(e) {
		var i = e.index;
		if(i == 0 && ok) ok(e.value);
		if(i == 1 && cancel) cancel(e.value);
	});
};
app.ui.prompt = function(msg, ok, cancel,content,title){
	var btnArray = ['确定', '取消'];
	var title   = typeof title == "undefined"?"八戒DNS":title;
	var content = typeof content == "undefined"?"请输入":content;
	mui.prompt(msg,content,title, btnArray, function(e) {
		var i = e.index;
		if(i == 0 && ok) ok(e.value);
		if(i == 1 && cancel) cancel(e.value);
	})
};
app.ui.tip = function(msg){
	mui.toast(msg);
}
app.tpl = function(tpl,data){
	return ""+easyTemplate(mui(tpl)[0].innerHTML,data);
}
//
/* easyTemplate.js */
var easyTemplate = function(s,d){
	if(!s){return '';}
	if(s!==easyTemplate.template){
		easyTemplate.template = s;
		easyTemplate.aStatement = easyTemplate.parsing(easyTemplate.separate(s));
	}
	var aST = easyTemplate.aStatement;
	var process = function(d2){
		if(d2){d = d2;}
		return arguments.callee;
	};
	process.toString = function(){
		return (new Function(aST[0],aST[1]))(d);
	};
	return process;
};
easyTemplate.separate = function(s){
	var r = /\\'/g;
	var sRet = s.replace(/(<(\/?)#(.*?(?:\(.*?\))*)>)|(')|([\r\n\t])|(\$\{([^\}]*?)\})/g,function(a,b,c,d,e,f,g,h){
		if(b){return '{|}'+(c?'-':'+')+d+'{|}';}
		if(e){return '\\\'';}
		if(f){return '';}
		if(g){return '\'+('+h.replace(r,'\'')+')+\'';}
	});
	return sRet;
};
easyTemplate.parsing = function(s){
	var mName,vName,sTmp,aTmp,sFL,sEl,aList,aStm = ['var aRet = [];'];
	aList = s.split(/\{\|\}/);
	var r = /\s/;
	while(aList.length){
		sTmp = aList.shift();
		if(!sTmp){continue;}
		sFL = sTmp.charAt(0);
		if(sFL!=='+'&&sFL!=='-'){
			sTmp = '\''+sTmp+'\'';aStm.push('aRet.push('+sTmp+');');
			continue;
		}
		aTmp = sTmp.split(r);
		switch(aTmp[0]){
			case '+macro':mName = aTmp[1];vName = aTmp[2];aStm.push('aRet.push("<!--'+mName+' start--\>");');break;
			case '-macro':aStm.push('aRet.push("<!--'+mName+' end--\>");');break;
			case '+if':aTmp.splice(0,1);aStm.push('if'+aTmp.join(' ')+'{');break;
			case '+elseif':aTmp.splice(0,1);aStm.push('}else if'+aTmp.join(' ')+'{');break;
			case '-if':aStm.push('}');break;
			case '+else':aStm.push('}else{');break;
			case '+list':aStm.push('if('+aTmp[1]+'&&'+aTmp[1]+'.constructor === Array){with({i:0,l:'+aTmp[1]+'.length,'+aTmp[3]+'_index:0,'+aTmp[3]+':null}){for(i=l;i--;){'+aTmp[3]+'_index=(l-i-1);'+aTmp[3]+'='+aTmp[1]+'['+aTmp[3]+'_index];');break;
			case '-list':aStm.push('}}}');break;
			default:break;
		}
	}
	aStm.push('return aRet.join("");');
	if(!vName){aStm.unshift('var data = arguments[0];');}
	return [vName,aStm.join('')];
};
// page相关

// ajax相关
app.getJSON = function(url,data,func){
	mui.getJSON(url,data,func);
};
app.postJSON = function(url,data,func){
	mui.post(url,data,func,'json');
};
//存储系统
app.ldb = {
	db:window.localStorage,
	st:function(){
		if(app.ldb.db){
		 	return 1;
		}else{
		    return 0;
		}
	},
	set:function(name,value){
		return app.ldb.db.setItem("$"+name,value);
	},
	get:function(name){
		return app.ldb.db.getItem("$"+name);
	},
	set_json:function(name,value){
		value = JSON.stringify(value);
		return app.ldb.set(name,value);
	},
	get_json:function(name){
		var value = app.ldb.get(name);
		return JSON.parse(value);
	},
	unset:function(name){return app.ldb.db.removeItem("$"+name);},
	clear:function(){return app.ldb.db.clear();}
};
//会员系统
app.user = {};
app.user.state = function(loginpage){
	var userinfo = app.ldb.get_json("userinfo") || {};
    //console.log(userinfo);
	userinfo.uid = userinfo.uid || '';
	userinfo.upass = userinfo.upass || '';
	app.postJSON(app.u('/login/state'),userinfo,function(res){
		if(res.error == 1){
			if(loginpage === 1){

			}else{
				app.redirect('login.html');
			}			
		}else{
			if(loginpage === 1){
				app.redirect('ucenter.html');
			}
		}
	});
	return userinfo;
}
app.user.login = function(userinfo){
	userinfo = userinfo || {};
	userinfo.uname = userinfo.uname || '';
	userinfo.upass = userinfo.upass || '';
	if (userinfo.uname.length < 5) {
		app.ui.tip('请输入合法的邮箱');
		return false;
	}
	if (userinfo.upass.length < 6) {
		app.ui.tip('密码最短为 6 个字符');
		return false;
	}

	app.postJSON(app.u('/login/do_login'),userinfo,function(res){
		if(res.error == 1){
			app.ui.tip(res.message);
		}else{
			var luserinfo = {uid:res.uid,upass:res.upass,utype:res.utype,uname:userinfo.uname,email:userinfo.uname};
			app.ldb.set_json("userinfo",luserinfo);
			app.ui.tip("登录成功！");
			setTimeout(function(){
				app.redirect('ucenter.html');
			},500);			
		}
	})
}
app.user.register = function(){
	userinfo = userinfo || {};
	userinfo.email = userinfo.email || '';
	userinfo.upass = userinfo.upass || '';
	userinfo.upass2 = userinfo.upass2 || '';

	if (userinfo.email.length < 5) {
		app.ui.tip('请输入合法的邮箱');
		return false;
	}
	if (userinfo.upass.length < 6) {
		app.ui.tip('密码最短为 6 个字符');
		return false;
	}
	if (userinfo.upass2 != userinfo.upass) {
		app.ui.tip('两次密码不一致');
		return false;
	}

	app.postJSON(app.u('/login/do_register'),userinfo,function(res){
		if(res.error == 1){
			app.ui.tip(res.message);
		}else{
			var luserinfo = {uid:res.uid,upass:res.upass,utype:res.utype,uname:res.uname,email:userinfo.email};
			app.ldb.set_json("userinfo",luserinfo);
			app.ui.tip("注册成功！");
			setTimeout(function(){
				app.redirect('ucenter.html');
			},500);			
		}
	})

}
app.user.logout = function(){
	app.getJSON(app.u('/login/logout'),{},function(res){
		if(res.error == 1){
			app.ui.tip(res.message);
		}else{
			app.ldb.unset("userinfo");
			app.ui.tip("退出成功！")
			setTimeout(function(){
				app.redirect('login.html');
			},500);	
		}
	})
}

$.time_to_string = function(val,format){val=parseInt(val);var d = new Date(val*1000);if(val<=0)return "-"; var year = ((d.getYear()<1900)?(1900+d.getYear()):d.getYear()),month=(d.getMonth()+1),day=d.getDate(),hour=d.getHours(),minute=d.getMinutes(),second=d.getSeconds(); format = typeof format == "undefined"?"Y-m-d H:i:s":format;return format.replace("Y",year).replace("m",month).replace("d",day).replace("H",hour).replace("i",minute).replace("s",second).replace(" 0:0:0","");}

//其他方法
app.redirect = function(r){if(r == "reload"){window.location.reload();}else{window.location.replace(r);}};
app.exeJS = function(path){if(path == "" || typeof path == "undefined")return false;path=path.replace(/&amp;/g,"&")+"&jrand="+Math.random();setTimeout(function(){$.getScript(path);},50);};