/* initlize */
var U=function(_mstr){return APP_URL.substring(0,APP_URL.length-1)+_mstr;var mstr=typeof _mstr=="undefined"?"":_mstr;var s=new Array();var restr=APP_URL;if(mstr!=""){restr+="?";s=mstr.split("/");if(s.length>0){for(var i=1;i<s.length;i++){if(i==1){restr+="inc="+s[i];continue}if(i==2){restr+="&act="+s[i];continue}if(i%2==1){restr+="&"+s[i]+"="+s[i+1];continue}}}}return restr};
$.debugPrint = function(p){var html = "";for(var i in p){html += i+":"+p[i]+"\n"};alert(html);};
$.redirect = function(r){if(r == "reload"){window.location.reload();}else{window.location.replace(r);}};
$.exeJS = function(path){if(path == "" || typeof path == "undefined")return false;path=path.replace(/&amp;/g,"&")+"&jrand="+Math.random();setTimeout(function(){$.getScript(path);},50);};
$.is_object = function(o){return (typeof o=='object') && o.constructor==Object;}
$.is_array = function(o){return (typeof o=='object') && o.constructor==Array;}
$.is_string = function(o){return (typeof o=='string') && o.constructor==String;}
$.is_number = function(o){return (typeof o=='number') && o.constructor==Number;}
$.is_date = function(o){return (typeof o=='object') && o.constructor==Date;}
$.is_function = function(o){return (typeof o=='function') && o.constructor==Function;}
$.in_array = function(n,arr){if(typeof n == "string" || typeof n == "number"){for(var i in arr){if(arr[i] == n)return true;}}return false;}
$.replace_keyword = function(a,k){if($.is_empty(k))return a;var pa = new RegExp("("+k+")","ig");return a.replace(pa,"<b style='color:#f00;font-size:14px;'>"+k+"</b>");}
$.is_empty = function(val){switch (typeof(val)){case 'string':return this.trim(val).length == 0 ? true : (val == "0"?true:false); break;case 'number':return val == 0;break;case 'object':return val == null;break;case 'array':return val.length == 0; break;default: return true;}}
$.time_to_string = function(val,format){val=parseInt(val);var d = new Date(val*1000);if(val<=0)return "-"; var year = ((d.getYear()<1900)?(1900+d.getYear()):d.getYear()),month=(d.getMonth()+1),day=d.getDate(),hour=d.getHours(),minute=d.getMinutes(),second=d.getSeconds(); format = typeof format == "undefined"?"Y-m-d H:i:s":format;return format.replace("Y",year).replace("m",month).replace("d",day).replace("H",hour).replace("i",minute).replace("s",second).replace(" 0:0:0","");};
$.is_domain = function(val){var reg =/^(([-A-Za-z0-9]+\.)+)((com)|(net)|(org)|(gov.cn)|(info)|(me)|(cc)|(com.cn)|(net.cn)|(org.cn)|(name)|(biz)|(tv)|(cn)|(la)|(ml)|(asia)|(tel)|(co)|(cm)|(in)|(bz)|(vc)|(ag)|(mn)|(sc)|(us)|(ws)|(travel)|(tm)|(io)|(ac)|(sh)|(tw)|(mobi)|(hk)|(pw)|(con.hk)|(com.tw)|(so)|(xn--[-A-Za-z0-9]+))$/; return reg.test(val.toLowerCase());};
$.is_ip = function(ip){ipArray = ip.split(".");j = ipArray.length;if(j!=4){return false;}for(var i=0;i<4;i++){if (isNaN(Number(ipArray[i]))){return false;}if(ipArray[i].length==0 || ipArray[i]>255){return false;}}return true;};
$.to_float2 = function(value){value = Math.round(parseFloat(value) * 100) / 100;var xsd = value.toString().split(".");if(xsd.length==1){value = value.toString()+".00";return value;}if(xsd.length>1){if(xsd[1].length<2){value = value.toString()+"0";}return value;}}
$.remove_array_item = function(arry,item){var tmparr = new Array(); for(var i = 0; i < arry.length; i++){if(arry[i] != item)tmparr.push(arry[i]);}return tmparr;};
$.get_random_string = function(len) {len = len || 32;var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/var maxPos = $chars.length;var pwd = '';for (i = 0; i < len; i++) {pwd += $chars.charAt(Math.floor(Math.random() * maxPos));}return pwd;}
$.copy_obj = function(a){var r = {};for(var i in a){r[i] = a[i]}return r;}
$.get_dataconfig = function(uri){node = $.copy_obj(dataConfig);paths = uri.split(".");if(paths.length > 0){for(var i=0;i<paths.length;i++){if(typeof node[paths[i]] == "undefined"){return "";}node =  node[paths[i]];}}return node;}
$.get_cate = function(v,t){
	eval("var data_sr =" + t +"_C");
	var paths = v.split(",");
	var re = [];
	if(paths.length > 0){
		for(var i=0;i<paths.length;i++){
			if(!$.is_empty(paths[i]) && !$.is_empty(data_sr[paths[i]])){
				re.push(data_sr[paths[i]]['name']);
			}
		}
	}
	return re.join(" ");
}
//根据data key -show
$.show_dataconfig = function(key,k){return ((typeof dataConfig[key][""+k] != "undefined")?dataConfig[key][""+k]:" - ");}
$.show_split_dataconfig = function(key,k_str){
	var re = "";
	k_str = (""+k_str).trim();
	if(!$.is_empty(k_str)){
		var tmp = k_str.split(",");
		for(var i in tmp){
			re += (re == ""?"":", ") + $.get_dataconfig("" + key + "." + tmp[i]);
		};
	}
	return re;
}
//ui
$.ui = {
	layer:{},
	error:function(msg){
		$.ui.info('<div class="am-text-danger"><i class="am-danger am-icon-sm am-icon-times-circle"></i>&nbsp;&nbsp;&nbsp;'+msg+"</div>");
	},
	success:function(msg){
		$.ui.info('<div class="am-text-success"><i class="am-success am-icon-sm am-icon-check-circle"></i>&nbsp;&nbsp;&nbsp;'+msg+"</div>");
	},
	alert:function(msg){
		$.ui.info(msg,"提示信息");
	},
	loading:function(){
		var html = '<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="my-loading"><div class="am-modal-dialog"><div class="am-modal-hd">正在载入...</div><div class="am-modal-bd"><span class="am-icon-spinner am-icon-spin"></span></div></div></div>';
		if(!$("#my-loading").get(0)){
			$("body").append(html);
		}
		$('#my-loading').modal();
	},
    close_loading:function(){
    	$('#my-loading').modal("close");
	},
	tips:function(msg,obj,pos,color){ 
	},
	confirm:function(ok_callback,msg,btn,cancel_callback){
		if($.is_empty(msg)){
			msg = '您确定要执行该操作吗?';
		}
		if($.is_empty(btn)){
			btn = ['确定','取消'];
		}
	},
	open:function(msg,title,size,offset){
			  
	},
	info:function(msg,title){
		var title = $.is_empty(title)?0:title;
		var html = '<div class="am-modal am-modal-alert" tabindex="-1" id="my-popinfo"><div class="am-modal-dialog"><div class="am-modal-hd"></div><div class="am-modal-bd"></div><div class="am-modal-footer"><span class="am-modal-btn">关闭</span></div></div></div>';
		if(!$("#my-popinfo").get(0)){
			$("body").append(html);
		}
		if(title === 0){
			$("#my-popinfo .am-modal-hd").hide();
		}else{
			$("#my-popinfo .am-modal-hd").html(title);
		}
		
		$("#my-popinfo .am-modal-bd").html(msg);
		$('#my-popinfo').modal();
	}
}
//查看更多按钮
$.loadmore = function(obj,callback){
	var url  = $(obj).attr("data-url");
	var tpl  = $($(obj).attr("data-tpl")).html();
	var box  = $(obj).attr("data-box");
	var fix  = $(obj).attr("data-fix");
	fix = fix == ""?"记录":fix;
	if(!url || !tpl){
		return false;
	}
	$(obj).unbind("click").bind("click",function(){
		var theobj = this;
		var page = parseInt($(theobj).attr("data-page"));
		var url  = $(obj).attr("data-url");
		$(theobj).text("努力加载中，请稍候……").attr("disabled","disabled");
		$.ajaxPassport({
			url:url,
			data:{page:page+1},
			success:function(res){
				$(theobj).attr("data-page",page+1);
				if(res.error == 1){
					$(box).append("<div class='font-red'>"+res.message+"</div>");
				}else{
					$(box).append(""+easyTemplate(tpl,res));
				}
				if (parseInt(res.yu) > 0) {
		            $(theobj).html("还有<strong>" +res.yu + "</strong>个"+fix+"，点击加载更多...").attr("disabled",false);
		        } else {
		        	if(!$(theobj).parent().find("p.totallines").get(0)){
		        		$(theobj).after("<p class=\"totallines\"></p>");
		        	}
		        	$(theobj).parent().find("p.totallines").html("共<strong>" + res.total + "</strong>个"+fix+"，已加载完毕");
		            $(theobj).hide();
		        }
		    	if(typeof callback == "function"){
		    		callback(res);
		    	}					    	
			}
		});
	});
	$(obj).click();
}
//ajax method
$.ajaxPassport = function(option){
	var options = option || {};
	options.url = options.url || "";
	options.type = options.type || "GET";
	options.data = options.data || {};
	//options.contentType = options.contentType || "application/json";
	options.dataType = options.dataType || "json";
	if(options.url == "")return false;
	options.success = options.success || function(res){
		if(res.error == 0){
			$.ui.success(res.message);
		}else{
			if(res.error == 1){
				$.ui.error(res.message);
			}else{
				$.ui.open(res.message,false,"700px");
			}
			return false; 
		}
	}
	options.error = options.error || function(e){
		//$.ui.open(e.responseText,"700px");	
	}
	options.complete = options.complete || function(e){
	}
	//不缓存ajax结果
	options.cache  = false;
	$.ajax(options);
}
// ajax form submit
$.ajaxForm = function($that,i){
	var iframe_id = 'form_iframe'+((typeof i == "undefined")?parseInt(Math.random()*1000):i);
	//before function()
	if(typeof $($that).data("before") == "function"){
		if($($that).data("before")($that) == false){
			return false;
		}
	}
	//button
	var thebtn = $($that).find("button[type='submit']").get(0);
	$(thebtn).attr("disabled",true).addClass("btn-gray");
	if(!$("#"+iframe_id).get(0)){
		$("body").append("<iframe name='"+iframe_id+"' id='"+iframe_id+"'></iframe>");
		$("#"+iframe_id).hide();
	}
	$("#"+iframe_id).load(function(){
	   	var restr = $(window.frames[iframe_id].document.body).html();
	   	var res = {"error":1,"message":""};
	   	if(restr != ''){
	   		try{
	   			eval("res="+restr);
	   			if(typeof res.errors != "undefined"){
	   				res.message = "";
					for(var i in res.errors){
						res.message += "<span class='error-"+i+"'>"+res.errors[i]+"</span><br/>";
					}
				}
	   		}catch(ex){
	   			res = {"error":2,"message":restr};
	   		}
	   	}else{
	   		return false;
	   	}
	   	if(typeof $($that).data("success") == 'function'){
			if($($that).data("success")(res,$that)){
			}
		}else{
			if(res.callback == "reload"){
				setTimeout("window.location.reload()",500);
			}else if(res.callback){
				res.callback = res.callback.replace(/&amp;/g,"&");
				setTimeout("window.location.replace('"+res.callback+"')",500);	
			}
		}
		$($that).attr('target',"");
		$(thebtn).attr("disabled",false).removeClass("btn-gray");
		$("#"+iframe_id).empty();
		$("#"+iframe_id).unbind("load");
		setTimeout(function(){
			$("#"+iframe_id).remove();
		},200);
	});
	$($that).attr('target',iframe_id);
	return true;
}
$(function(){
	//$.ui.alert("达到阿斯顿");
});
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function checkIdcard(num){
	num = num.toUpperCase();
    //身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X。
    if (!(/(^\d{17}([0-9]|X)$)/.test(num))){//(/(^\d{15}$)|(^\d{17}([0-9]|X)$)/.test(num))
         $.ui.error('输入身份证格式有误');
         return false;
    }
    //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
    //下面分别分析出生日期和校验位
    var len, re;
    len = num.length;
    if (len == 18){
            re = new RegExp(/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/);
            var arrSplit = num.match(re);

            //检查生日日期是否正确
            var dtmBirth = new Date(arrSplit[2] + "/" + arrSplit[3] + "/" + arrSplit[4]);
            var bGoodDay;
            bGoodDay = (dtmBirth.getFullYear() == Number(arrSplit[2])) && ((dtmBirth.getMonth() + 1) == Number(arrSplit[3])) && (dtmBirth.getDate() == Number(arrSplit[4]));
            if (!bGoodDay){
                $.ui.error('输入的身份证号里出生日期不对！');
                return false;
            }else{
            //检验18位身份证的校验码是否正确。
            //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
            var valnum;
            var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            var nTemp = 0, i;
            for(i = 0; i < 17; i ++){
                nTemp += num.substr(i, 1) * arrInt[i];
            }
            valnum = arrCh[nTemp % 11];
            if (valnum != num.substr(17, 1)){
                $.ui.error("非法的身份证号")
            	//alert('18位身份证的校验码不正确！应该为：' + valnum);
                return false;
            }
            return true;
        }
    }
    return false;
}

String.prototype.trim=function(){return this.replace(/(^\s*)|(\s*$)/g,"");}
String.prototype.ltrim=function(){return this.replace(/(^\s*)/g,"");}
String.prototype.rtrim=function(){return this.replace(/(\s*$)/g,"");}
String.prototype.is_mobile = function() {return (/^(?:13\d|14[0-9]|15[0-9]|18[0-9])-?\d{5}(\d{3}|\*{3})$/.test(this.trim())); } 
String.prototype.is_tel = function(){var reg = /^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/; return reg.test(this.trim()); } 
	
	
Date.prototype.dateDiff = function (interval, objDate2) { var d = this, i = {}, t = d.getTime(), t2 = objDate2.getTime(); i['y'] = objDate2.getFullYear() - d.getFullYear(); i['q'] = i['y'] * 4 + Math.floor(objDate2.getMonth() / 4) - Math.floor(d.getMonth() / 4); i['m'] = i['y'] * 12 + objDate2.getMonth() - d.getMonth(); i['ms'] = objDate2.getTime() - d.getTime(); i['w'] = Math.floor((t2 + 345600000) / (604800000)) - Math.floor((t + 345600000) / (604800000)); i['d'] = Math.floor(t2 / 86400000) - Math.floor(t / 86400000); i['h'] = Math.floor(t2 / 3600000) - Math.floor(t / 3600000); i['n'] = Math.floor(t2 / 60000) - Math.floor(t / 60000); i['s'] = Math.floor(t2 / 1000) - Math.floor(t / 1000); return i[interval]; }
Date.prototype.DateAdd=function(strInterval,Number){var dtTmp=this;switch(strInterval){case's':return new Date(Date.parse(dtTmp)+(1000*Number));case'n':return new Date(Date.parse(dtTmp)+(60000*Number));case'h':return new Date(Date.parse(dtTmp)+(3600000*Number));case'd':return new Date(Date.parse(dtTmp)+(86400000*Number));case'w':return new Date(Date.parse(dtTmp)+((86400000*7)*Number));case'q':return new Date(dtTmp.getFullYear(),(dtTmp.getMonth())+Number*3,dtTmp.getDate(),dtTmp.getHours(),dtTmp.getMinutes(),dtTmp.getSeconds());case'm':return new Date(dtTmp.getFullYear(),(dtTmp.getMonth())+Number,dtTmp.getDate(),dtTmp.getHours(),dtTmp.getMinutes(),dtTmp.getSeconds());case'y':return new Date((dtTmp.getFullYear()+Number),dtTmp.getMonth(),dtTmp.getDate(),dtTmp.getHours(),dtTmp.getMinutes(),dtTmp.getSeconds());}}
Date.prototype.DateToParse=function(){var d=this;return Date.parse(d.getFullYear()+'/'+(d.getMonth()+1)+'/'+d.getDate());}
//common function
function URLencode(sStr){return escape(sStr).replace(/\+/g, '%2B').replace(/\"/g,'%22').replace(/\'/g, '%27').replace(/\//g,'%2F');}
function Search(t,c){var b,d1,d2,u=$(t).attr('url'),a=c.split(',');u+=u.indexOf('?')==-1?'?':'&';for(var i=0;i<a.length;i++){d1=a[i];d2=d1;b=d1.indexOf('=');if(b!=-1){d2=d1.substring(b+1);d1=d1.substring(0,b)}b=encodeURIComponent($('#'+d2).val());if(u.indexOf(d2+'00')!=-1){u=u.replace(d2+'00',b)}else{if(b!=encodeURIComponent($('#'+d2).attr('default')))u+=(d1+'='+b+'&')}}b=u.substring(u.length-1);if(b=='&'||b=='?'){u=u.substring(0,u.length-1)}location=u;return false}
//js截取字符串，中英文都能用
//如果给定的字符串大于指定长度，截取指定长度返回，否者返回源字符串。
function cutstr(str, len, c) {var str_length = 0, str_len = 0, str_cut = new String(); str_len = str.length; for (var i = 0; i < str_len; i++) { a = str.charAt(i); str_length++; if (escape(a).length > 4) { str_length++; } str_cut = str_cut.concat(a); if (str_length >= len) { if (typeof c != 'undefined') str_cut=str_cut.concat(c);return str_cut;} }if (str_length < len) {return str;}}
function formatpic(p,n){if(p!=''){var l,s;l=p.lastIndexOf('/');s=p.lastIndexOf('\\');l=l>s?l:s;s=p.substring(l+1);return p.substring(0,l+1)+n+(s.substring(0,1)=='2'?s:s.substring(1))}return''}
function ListUrl(id){var url=location.href;var a=id.split('_');var tmp;if(a[0]=='tid'){if(!url.match(/(\d+)\-(\d+)\.html/ig)==false){location.href=url.replace(/(\d+)\-(\d+)\.html/ig,a[1]+"-$2.html");return}}else if(a[0]=='cid'){if(!url.match(/(\d+)\-(\d+)\-(\d+)\.html/ig)==false){location.href=url.replace(/(\d+)\-(\d+)\-(\d+)\.html/ig,a[1]+"-$2-$3.html");return}}var s=url.indexOf("?");var Par=s==-1?'&':'&'+url.substring(s+1)+'&';for(var i=0;i<a.length;i+=2){tmp=Par.match(new RegExp("\&"+a[i]+"\=-?(\\d+)?",'i'));if(!tmp){Par+=a[i]+'='+a[i+1]+'&'}else{Par=Par.replace(tmp[0]+'&','&'+a[i]+'='+a[i+1]+'&')}}Par=Par.substring(1,Par.length-1);if(s==-1){url+='?'+Par}else{url=url.substring(0,s+1)+Par}location=url};String.prototype.Trim=function(){return this.replace(/^\s+/g,"").replace(/\s+$/g,"")}
function XMLEscape(v){return v.replace('&apos;','\'').replace('&quot;','"').replace('&gt;','>').replace('&lt;','<').replace('&amp;','&')}
function SetCookie(key, value, expire, domain, path) { var cookie = ""; if (key != null && value != null) cookie += key + "=" + encodeURIComponent(value) + ";"; if (expire != null) cookie += "expires=" + expire.toGMTString() + ";"; if (domain != null) cookie += "domain=" + domain + ";"; if (path != null) cookie += "path=" + path + ";"; document.cookie = cookie };
function GetCookie(key){var c=document.cookie,a=c.split(';'),b;for(var i=0;i<a.length;i++){a[i]=a[i].Trim();if(a[i].substring(0,3)=='te='){b=a[i].Trim().substr(3).split('&');for(var j=0;j<b.length;j++){if(b[j].Trim().substr(0,key.length+1)==key+'='){return decodeURIComponent(b[j].Trim().substr(key.length+1))}}}else if(a[i].substring(0,key.length+1)==key+'='){return decodeURIComponent(a[i].substring(key.length+1))}}return''};

//倒计时
function DisFormate_timer(val,val2){  
    var ts = (new Date(val*1000)) - (new Date(val2*1000));//计算剩余的毫秒数  
    //console.log(ts);  
    var dd = parseInt(ts / 1000 / 60 / 60 / 24, 10);//计算剩余的天数  
    var hh = parseInt(ts / 1000 / 60 / 60 % 24, 10);//计算剩余的小时数  
    var mm = parseInt(ts / 1000 / 60 % 60, 10);//计算剩余的分钟数  
    var ss = parseInt(ts / 1000 % 60, 10);//计算剩余的秒数  
    return dd + "天" + hh + "时" + mm + "分" + ss + "秒";  
}  
//setInterval(timer,1000);
//模拟输入验证码
function InputCode (BankNo){

}
