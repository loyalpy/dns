//app.new.js
/* ext $ function */
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
$.time_to_string = function(val,format){val=parseInt(val);var d = new Date(val*1000);if(val<=0)return "-"; var year = ((d.getYear()<1900)?(1900+d.getYear()):d.getYear()),month=(d.getMonth()+1),day=d.getDate(),hour=d.getHours(),minute=d.getMinutes(),second=d.getSeconds(); format = typeof format == "undefined"?"Y-m-d H:i:s":format;return format.replace("Y",year).replace("m",month).replace("d",day).replace("H",hour).replace("i",minute).replace("s",second).replace(" 0:0:0","");}
$.is_cn = function(val){var re = /[\u4e00-\u9fa5]/; return (re.test(val))}
$.dns = {
	start_with:function(str,v){var reg=/^-/;return reg.test(str);},
	end_with:function(str,v){var reg=/-$/;return reg.test(str);},
	get_supper_domain:function(){if(!$.is_empty(supper_domain)){return supper_domain}else{return "((ee)|(pe)|(sc.cn)|(pk)|(top)|(wang)|(pt)|(hl.cn)|(ph)|(to)|(fm)|(su)|(com.au)|(au)|(bg)|(ru)|(jp)|(co.jp)|(eu)|(de)|(com)|(net)|(net.cn)|(org)|(gov.cn)|(gov)|(info)|(me)|(cc)|(com.cn)|(edu.cn)|(gd.cn)|(com.hk)|(tw.cn)|(org.cn)|(cn)|(name)|(biz)|(tv)|(la)|(ml)|(asia)|(tel)|(co)|(cm)|(in)|(bz)|(vc)|(ag)|(mn)|(sc)|(us)|(ws)|(travel)|(tm)|(io)|(ac)|(sh)|(tw)|(mobi)|(hk)|(pw)|(so)|(com.hk)|(com.tw)|(hk.cn)|(中国)|(公司)|(网络)|(通用网址)|(白金词)";}},
	is_request_url:function(v){
		var reg1 = /^http(s)?:\/\/([\w-\.\/]+)((:\d)?)/;
		return reg1.test( v );
	},
    is_domain_node:function(domain){
        if($.dns.start_with(domain,"-") || $.dns.end_with(domain,"-")){
        	return false;
        }
        var reg =/^[-A-Za-z0-9_\u4e00-\u9fa5]+$/;
        return reg.test(domain);
    },
    is_domain:function(domain){
        var tmp = domain.split(".");
        for(var i = 0;i<tmp.length;i++){
        	if(!$.dns.is_domain_node(tmp[i])){
        		return false;
        	}
        }
        var reg = eval("/^([-A-Za-z0-9\u4e00-\u9fa5]+)\.("+$.dns.get_supper_domain()+")"+"$/i");
        return reg.test(domain);
    },
    is_domain2:function(domain){
       	var tmp = domain.split(".");
       	for(var i = 0;i<tmp.length;i++){
       		if(!$.dns.is_domain_node(tmp[i])){
       			return false;
       		}
       	}
       	var reg = eval("/^([-A-Za-z0-9\u4e00-\u9fa5\.]+)\.("+$.dns.get_supper_domain()+")"+"$/i");
        return reg.test(domain);
    },
    is_hostname:function(v){
		var tmp = v.split(".");
       	for(var i = 0;i<tmp.length;i++){
       		if(i == 0 && tmp[i] == "*")continue;
       		if(!$.dns.is_domain_node(tmp[i])){
       			return false;
       		}
       	}  
		var reg1 = /^([-A-Za-z0-9_\*\.]+)$/;
		return reg1.test(v);
	},
    is_ip:function(ip){
       	ipArray = ip.split(".");
        j = ipArray.length
	    if(j!=4){
	        return false;
	    }
	    for(var i=0;i<4;i++){
			if (isNaN(Number(ipArray[i]))){
			   return false;
			}
	        if(ipArray[i].length==0 || ipArray[i]>255){
	            return false;
	        }
	    }
        return true;
    },
    is_ipv6:function(ip){
       	var reg = /^\s*((([0-9A-Fa-f]{1,4}:){7}(([0-9A-Fa-f]{1,4})|:))|(([0-9A-Fa-f]{1,4}:){6}(:|((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})|(:[0-9A-Fa-f]{1,4})))|(([0-9A-Fa-f]{1,4}:){5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){4}(:[0-9A-Fa-f]{1,4}){0,1}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){3}(:[0-9A-Fa-f]{1,4}){0,2}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){2}(:[0-9A-Fa-f]{1,4}){0,3}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)(:[0-9A-Fa-f]{1,4}){0,4}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(:(:[0-9A-Fa-f]{1,4}){0,5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})))(%.+)?\s*$/;
       	return reg.test(ip);
    },
	// 复制剪贴板
	copy_clip:function(copy){
		if (window.clipboardData) {
			window.clipboardData.clearData();
			window.clipboardData.setData("Text", copy);
		} else if (window.netscape) {
			try {
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			} catch (e) {
				alert("你的浏览器不支持脚本复制或你拒绝了浏览器安全确认，请尝试手动[Ctrl+C]复制。");
				return false;
			}
			var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
			if (!clip) {
				return false;
			}
			var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
			if (!trans) {
				return false;
			}
			trans.addDataFlavor('text/unicode');
			var str = new Object();
			var len = new Object();
			var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
			var copytext = copy;
			str.data = copytext;
			trans.setTransferData("text/unicode",str,copytext.length*2);
			var clipid = Components.interfaces.nsIClipboard;
			if (!clip) {
				return false;
			}
			clip.setData(trans,null,clipid.kGlobalClipboard);
		}
		alert("复制成功！请Ctrl+V键粘贴到要加入的页面。");
		return true;
	},
}

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
//全选
$.check_all = function(obj){
	var name = $(obj).attr("data-name")
	var flag = obj.checked;
	$("input[name='"+name+"']").each(function() {
		this.checked = flag;
	});
}
$.fetch_ids = function(name,type){
	var ids = [];
	var batchids = $("input[name='"+name+"']");
	if(batchids.length>0){
		batchids.each(function(){
			var obj = this;
			var flag = obj.checked;
			if($.is_empty(type)){
				var id = $(obj).val();
			}else{
				var id = $(obj).attr("data-"+type);
			}
			if(flag && typeof id != "undefined" && id){
				ids.push(id);
			}
		});
	}
	ids = $.remove_array_item(ids, '');
	return (ids.length>0)?ids.join(","):"";
}
/* ui tips */
$.ui = {
	layer:{},
	tooltip:function(msg,_class,_timeout){
		var $tooltip = $('<div id="vld-tooltip">提示信息！</div>');
		var timeout = $.is_empty(_timeout)?1000:_timeout;
		$tooltip.appendTo(document.body);
		var offset = $(_class).offset();
		$tooltip.text(msg).show().css({
			left: offset.left,
			top: offset.top + $(_class).outerHeight() + 10
		});
		setTimeout(function(){
			$tooltip.hide();
		},timeout);
	},
	error:function(msg,_t,_pos,_timeout){
		var t  = $.is_empty(_t)?"body":_t;
		var timeout = $.is_empty(_timeout)?5000:_timeout;
		if(t == "_pop"){
			$.ui.info('<div class="dis15"></div><div class="am-text-danger"><i class="am-danger am-icon-sm am-icon-times-circle"></i>&nbsp;&nbsp;&nbsp;'+msg+"</div>");
		}else if(t == "body"){
			var html = '<div class="page-tips" id="my-tips"><div class="inner"></div></div>';
			if(!$("#my-tips").get(0)){
				$("body").append(html);
				if(!$.is_empty(_pos)){
					$("#my-tips").css({"top":_pos+"px"})
				}
			}
			$("#my-tips").removeClass("success").removeClass("error").addClass("error").show().find(".inner").html(msg);
			setTimeout('$("#my-tips").fadeOut()',timeout);
		}else{
			var html = '<div class="page-next-tips page-next-tips-error" ><div class="inner">'+msg+'</div></div>';
			$(t).after(html);
			setTimeout(function(){
				$(t).parent().find(".page-next-tips-error").remove();
			},timeout);
		}		
	},
	success:function(msg,_t,_pos,_timeout){
		var t  = $.is_empty(_t)?"body":_t;
		var timeout = $.is_empty(_timeout)?3000:_timeout;
		if(t == "_pop"){
			$.ui.info('<div class="dis15"></div><div class="am-text-success"><i class="am-success am-icon-sm am-icon-check-circle"></i>&nbsp;&nbsp;&nbsp;'+msg+"</div>");
		}else if(t == "body"){
			var html = '<div class="page-tips" id="my-tips"><div class="inner"></div></div>';
			if(!$("#my-tips").get(0)){
				$("body").append(html);
				if(!$.is_empty(_pos)){
					$("#my-tips").css({"top":_pos+"px"})
				}
			}
			$("#my-tips").removeClass("success").removeClass("error").addClass("success").show().find(".inner").html(msg);
			setTimeout('$("#my-tips").fadeOut()',timeout);
		}else{
			var html = '<div class="page-next-tips page-next-tips-success" ><div class="inner">'+msg+'</div></div>';
			$(t).after(html);
			setTimeout(function(){
				$(t).parent().find(".page-next-tips-success").remove();
			},timeout);
		}
	},
	alert:function(msg){
		$.ui.info(msg,"提示信息");
	},
	close_loading:function(box){
		var box     = (typeof box == "undefined" || box == '')?$("html"):box;
	 	var loading_img  = box.find(".loading_img");
	 	var loading_mask = box.find(".loading_mask");
	 	loading_img.empty().remove();
		loading_mask.empty().remove();
	},
	loading:function(box,ismask,pos,img){
	    var IMGS    = ["loading2.gif","_loading3.gif","loading_s.gif"];
		var WIDTHS  = [32,24,12];
	 	var img     = (typeof img == "undefined")?0:img;
	 	var imgpath = "/static/public/images/"+IMGS[img];
	 	var ismask  = (typeof ismask == "undefined")?1:ismask;
        var isbody  = (typeof box == "undefined" || box == '')?1:0; 
	 	var box     = isbody?$("html"):box;
	 	var loading_img  = box.find(".loading_img");
	 	var loading_mask = box.find(".loading_mask");
	 	
	 	if(isbody == 1){
	 		var top  = parseInt($(document).scrollTop()+(($(window).height()-WIDTHS[img])/2));
	 	    var left = parseInt(($(window).width()-WIDTHS[img])/2);
	 	}else{
	 		var top  = parseInt((box.height()-WIDTHS[img])/2);
	 		var left = parseInt((box.width()-WIDTHS[img])/2);
	 	}
	 	if(typeof pos != "undefined" && typeof pos.top != "undefined"){
	 		top = pos.top;
	 	}
	 	
	 	
	 	if(loading_img[0]){
	 		loading_img.css({"top":top,"left":left}).show();
	 	}else{
	 		var tmp_loading_mask = $("<div class='loading_mask' style='background:#000;display:none;position:absolute;top:0;left:0;z-index:99998'></div>");
	 		var tmp_loading_img = $("<div class=\"loading_img\"><img src=\""+imgpath+"\"></div>").css({"z-index":997,"position":"absolute","left":left,"top":top});
	 	    box.append(tmp_loading_mask).append(tmp_loading_img);
	 	}
	 	if(ismask == 1){
	 		box.find(".loading_mask").css({"width":box.width(),"height":box.height(),"opacity":"0.06"}).show();
	 	}else{
	 		box.find(".loading_mask").hide();
	 	}
	},
	confirm:function(ok_callback,msg,cancel_callback){
		if($.is_empty(msg)){
			msg = '您确定要执行该操作吗?';
		}
		var html = '<div class="am-modal" tabindex="-1" id="my-confirm"><div class="am-modal-dialog"><a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a><div class="am-modal-bd am-modal-html"></div><div class="am-modal-footer"><span class="am-modal-btn" data-am-modal-cancel>取消</span><span class="am-modal-btn" data-am-modal-confirm>确定</span></div></div></div>';
		if(!$("#my-confirm").get(0)){
			$("body").append(html);
		}
		$('#my-confirm').find(".am-modal-html").html(msg);
		$('#my-confirm').modal({
			//relatedTarget: this,
			onConfirm: ok_callback,
			// closeOnConfirm: false,
			onCancel: cancel_callback,
			width:300,
		});
	},
	open:function(inhtml,closefun,width,height){
		var html = '<div class="am-modal" tabindex="-1" id="my-popopen"><div class="am-modal-dialog"><a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-cancel>&times;</a><div class="am-modal-html"></div></div></div>';
		if(!$("#my-popopen").get(0)){
			$("body").append(html);	
		}else{
			$.ui.open_close();
		}		
        $("#my-popopen").find(".am-modal-html").html(inhtml);
        var options = {closeViaDimmer:false};
        if(typeof closefun == "function"){
        	options.onCancel = closefun;
        }
        if(!$.is_empty(width)){
        	options.width = width;
        }
        if(!$.is_empty(height)){
        	options.height = height;
        }
        $('#my-popopen').modal(options);
		$('#my-popopen').modal("open");
	},
	open_show:function(){
		$('#my-popopen').modal("open");
	},
	open_close:function(){
		$('#my-popopen').modal("close");
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
	},
	popover:function(obj){
		$(obj).each(function(){
			var msg   = $(this).data("msg");
			var theme = $(this).data("theme");
			$(this).popover({theme: theme+' sm', content: msg,trigger: 'hover focus'});
		})
	},

	form:{
		select:function(formobj){
			formobj.find(".tselect").each(function(){
				var obj = this;
				var init_n = $(obj).attr("data-name");
				var init_l = $(obj).attr("data-label");
				var init_v = $(obj).find("input[name='"+init_n+"']").val();

				$(obj).find(".tselect-text").unbind("click").click(function (e) {
					formobj.find(".tselect").parent().parent().css("z-index",0);
					formobj.find(".tselect").find(".tselect-box").hide();
					formobj.find('.input-tips').hide();
					formobj.find('.input-tips').parent().parent().css("z-index",0);

					if ($(obj).find(".tselect-box").css('display') == 'none') {
						$(".tselect ").css("z-index",0).find(".tselect-box").hide();
						$(obj).css("z-index",99).find(".tselect-box").show();
					} else {
						$(obj).find(".tselect-box").hide();
					}
					e.stopPropagation();
					return false;
				});

				$(obj).find("a.sel0").unbind("click").click(function(e){
					var obj1 = this;
					var v = $(obj1).attr("data-v");
					var cur_v = $(obj).find("input[name='"+init_n+"']").val();
					if($.is_empty(v)){
						$(obj).find(".tselect-text").html(init_l);
					}else{
						$(obj).find(".tselect-text").html($(obj1).text());
					}
					$(obj).find("input[name='"+init_n+"']").val(v);
					$(obj).find('.tselect-box').hide();
					if(v != cur_v && typeof $(obj).find("input[name='"+init_n+"']").data("onchange") == "function"){
						$(obj).find("input[name='"+init_n+"']").data("onchange")(obj);
					}
					e.stopPropagation();
				});
				//初始
				if($.is_empty(init_v)){
					$(obj).find(".tselect-text").html(init_l);
				}else{
					$(obj).find(".tselect-text").html($(obj).find("a.sel0[data-v='"+init_v+"']").text());
				}
			});
			$('body').click(function (e) {
					$('.tselect-box').hide();

			});
		}
	}
}
$.autoTextarea = function (elem, extra, maxHeight) {
        extra = extra || 0;
        var isFirefox = !!document.getBoxObjectFor || 'mozInnerScreenX' in window,
        isOpera = !!window.opera && !!window.opera.toString().indexOf('Opera'),
                addEvent = function (type, callback) {
                        elem.addEventListener ?
                                elem.addEventListener(type, callback, false) :
                                elem.attachEvent('on' + type, callback);
                },
                getStyle = elem.currentStyle ? function (name) {
                        var val = elem.currentStyle[name];
                        
                        if (name === 'height' && val.search(/px/i) !== 1) {
                                var rect = elem.getBoundingClientRect();
                                return rect.bottom - rect.top -
                                        parseFloat(getStyle('paddingTop')) -
                                        parseFloat(getStyle('paddingBottom')) + 'px';        
                        };
                        
                        return val;
                } : function (name) {
                                return getComputedStyle(elem, null)[name];
                },
                minHeight = parseFloat(getStyle('height'));
        
        
        elem.style.resize = 'none';
        
        var change = function () {
                var scrollTop, height,
                        padding = 0,
                        style = elem.style;
                
                if (elem._length === elem.value.length) return;
                elem._length = elem.value.length;
                
                if (!isFirefox && !isOpera) {
                        padding = parseInt(getStyle('paddingTop')) + parseInt(getStyle('paddingBottom'));
                };
                scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
                
                elem.style.height = minHeight + 'px';
                if (elem.scrollHeight > minHeight) {
                        if (maxHeight && elem.scrollHeight > maxHeight) {
                                height = maxHeight - padding;
                                style.overflowY = 'auto';
                        } else {
                                height = elem.scrollHeight - padding;
                                style.overflowY = 'hidden';
                        };
                        style.height = height + extra + 'px';
                        scrollTop += parseInt(style.height) - elem.currHeight;
                        document.body.scrollTop = scrollTop;
                        document.documentElement.scrollTop = scrollTop;
                        elem.currHeight = parseInt(style.height);
                };
        };
        
        addEvent('propertychange', change);
        addEvent('input', change);
        addEvent('focus', change);
        change();
};
/* ajax request */
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


/* ext string date */
String.prototype.trim=function(){return this.replace(/(^\s*)|(\s*$)/g,"");}
String.prototype.ltrim=function(){return this.replace(/(^\s*)/g,"");}
String.prototype.rtrim=function(){return this.replace(/(\s*$)/g,"");}
String.prototype.is_mobile = function() {return (/^(?:13\d|14[0-9]|15[0-9]|18[0-9])-?\d{5}(\d{3}|\*{3})$/.test(this.trim())); } 
String.prototype.is_tel = function(){var reg = /^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/; return reg.test(this.trim()); } 
String.prototype.is_domain = function(){var reg =/^(([-A-Za-z0-9]+\.)+)((com)|(net)|(org)|(gov.cn)|(info)|(me)|(cc)|(com.cn)|(net.cn)|(org.cn)|(name)|(biz)|(tv)|(cn)|(la)|(ml)|(asia)|(tel)|(co)|(cm)|(in)|(bz)|(vc)|(ag)|(mn)|(sc)|(us)|(ws)|(travel)|(tm)|(io)|(ac)|(sh)|(tw)|(mobi)|(hk)|(pw)|(con.hk)|(com.tw)|(so)|(xn--[-A-Za-z0-9]+))$/; return reg.test(this.trim().toLowerCase());};
String.prototype.is_ip = function(){ipArray = this.trim().split(".");j = ipArray.length;if(j!=4){return false;}for(var i=0;i<4;i++){if (isNaN(Number(ipArray[i]))){return false;}if(ipArray[i].length==0 || ipArray[i]>255){return false;}}return true;};
String.prototype.is_email = function(){ var reg = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;
			  return reg.test( this.trim());}
//date function
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