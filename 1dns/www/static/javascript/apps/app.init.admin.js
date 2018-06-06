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
//根据data key -show
$.show_dataconfig = function(key,k){return ((typeof dataConfig[key][""+k] != "undefined")?dataConfig[key][""+k]:" - ");}
$.show_split_dataconfig = function(key,k_str){
	var re = "";
	k_str = (""+k_str).trim();
	if(!$.is_empty(k_str)){
		var tmp = k_str.split(",");
		for(var i in tmp){
			re += (re == ""?"":", ") + $.show_dataconfig(key,tmp[i])
		};
	}
	return re;
}
//uis
layer.config({
    extend: ['skin/seaning/style.css'], //加载新皮肤
    skin: 'layer-ext-seaning' //一旦设定，所有弹层风格都采用此主题。
});
$.ui = {
  layer:{},
  success:function(msg){
	  return layer.msg(msg,{icon: 100,"msg_type":"success",time:3000,offset:0,shift:5});
  },
  error:function(msg){
	  return layer.msg(msg,{icon: 101,"msg_type":"error",time:3000,offset:0,shift:5});
  },
  loading:function(){
	  return layer.load(1);
  },
  close_loading:function(){
	  setTimeout(function(){
		  layer.closeAll("loading");
	  }, 100);
  },
  tips:function(msg,obj,pos,color){
	  if($.is_empty(pos)){
		  pos = 1;
	  }
	  if($.is_empty(color)){
		  color = "#222222";
	  }
	  layer.tips(msg, obj, {
		   tips: [pos, color],
		   time:6000,
		   closeBtn: false,
		   shift: 5,
	  });  
  },
  confirm:function(ok_callback,msg,btn,cancel_callback){
	  if($.is_empty(msg)){
		  msg = '您确定要执行该操作吗?';
	  }
	  if($.is_empty(btn)){
		  btn = ['确定','取消'];
	  }
	  if(!$.is_function(cancel_callback)){
		  cancel_callback = null;
	  }
	  return layer.confirm(msg, {
		  btn:btn, //按钮
		  shade:false, //不显示遮罩,
		  title:false,
		  icon:4,
		  shift: 5,
	  },ok_callback,cancel_callback);
  },
  open:function(msg,title,size,offset){
	  if($.is_empty(size)){
		  size = "700px";
	  }
	  if($.is_empty(offset)){
		  offset = "15px";
	  }
	  return layer.open({
		  type: 1,
		  area:size,
		  content: "<div class='popbox'><div class='inbox'>"+msg+"</div></div>",
		  time:0,
		  shift: 5,
		  title:title,
		  offset:offset
	  });
  },
  alert:function(msg,icon,ok_callback){
	  //1 ok  2 3禁止 4 ? 5 - 6 i 7 锁 10 下箭头 11 x 12 discuss 13 email
	  if($.is_empty(icon)){
		  icon = 1;
	  }
	  var ok_callback = typeof ok_callback != "function"?null:ok_callback;
	  return layer.alert(msg, {
		 icon:icon,
		 title:false,
		 shift:5,
	  },ok_callback)
  },
  close:function(index){
	  layer.close(index);
  },
  closeall:function(index){
	  setTimeout(function(){
		  layer.closeAll(index);
	  }, 50);
  }
}

//tips
$.tips = function(msg,type,obj){
	switch(type){
		case "success":
			layer.msg(msg,{icon: 100,"msg_type":"success",time:3000,offset:0,shift:5});
			break;
		case "error":
			layer.msg(msg,{icon: 101,"msg_type":"error",time:3000,offset:0,shift:5});
			break;
		case "debug":
			layer.open({
			    type: 1,
			    area:["900px"],
			    content: msg,
			    time:0,
			    shift: 5,
			    title:false
			});
			break;
		default:
			layer.msg(msg);
			break;
	}
}
$.valid_form = function(obj,_callback){
	obj.Validform({
		tiptype:3,
		label:".label",
		showAllError:true,
		datatype:{
			"zh1-6":/^[\u4E00-\u9FA5\uf900-\ufa2d]{1,6}$/
		},
		beforeSubmit:function(curform){
			var thebtn = curform.find("button[type='submit'],input[type='submit']").get(0);
			curform.data("data-success",function(res,that){
				        $.exeJS(res.js);
						if(typeof _callback == "function"){
							var _c_Ret = _callback(res,thebtn);
							if(_c_Ret === false) return false;
						}
						//$.debugPrint(res);
				        if(res.error == 0){
				        	$.tips(res.message,"success",obj);
						}else{
							if(res.error == 1){
								$.tips(res.message,"error",obj);
							}else{
								$.tips(res.message,"debug",obj);
							}							
							return false;
						}
						if(res.callback == "reload"){
							setTimeout("window.location.reload()",500);
						}else if(res.callback == "close"){
							setTimeout('$("#myModal").modal("hide")',500);
						}else if(res.callback){
							res.callback = res.callback.replace(/&amp;/g,"&");
							setTimeout("window.location.replace('"+res.callback+"')",500);	
						}	
					    return false;
			});
			 //初始化表单
			return $.ajaxForm(curform.get(0),0);
		}
	});
}
//解析表单
$.parseform = function(_datalist,res,formobj){
	var form_c = area = "";
	formobj.find(".tpl").empty().html("");
	for(var i in _datalist){
		if(typeof res[i] != "undefined"){
			_datalist[i]['value'] = res[i];
			if(typeof res[i+"_sr"] != "undefined"){
				_datalist[i]['data_sr'] = res[i+"_sr"];
			}
		}
		if(_datalist[i]['type'] == "area"){
			area = i;
		}
		if(_datalist[i]['type'] == "client"){
			if(typeof _datalist[i]['client_name']  == "undefined"){
				_datalist[i]['client_name'] = typeof res['client_name'] != "undefined" ?res['client_name']:"";
			}
		}
		if(_datalist[i]['type'] == "uid"){
			if(typeof _datalist[i]['uname']  == "undefined"){
				_datalist[i]['uname'] = typeof res['uid'] != "undefined"?res['uname']:"";
			}
		}
		if(_datalist[i]['disabled'] == 2 && !$.is_empty(_datalist[i]['value'])){
			_datalist[i]['disabled'] = 1;
		}
		form_c += easyTemplate($("#tpl_form").html(),_datalist[i]);
	}
	formobj.find(".tpl").html(form_c+"<div class='cl'></div>");
	if(area != ""){
		 $.selectArea('frm_province', 'frm_city', 'frm_area',_datalist[area]['value']);
	}
	//加载后处理
	formobj.find(".dropdown-select").each(function(){
		var obj = this;
		var init_n = $(obj).find(".selname").attr("data-name");
		var init_v = $(obj).find("input[name='"+init_n+"']").val();
		
		$(obj).find("a.sel0").click(function(){
			var obj1 = this;
			var v = $(obj1).attr("data-v");
			if($.is_empty(v)){
				$(obj).find(".selname").html($(obj).find(".selname").attr("data-txt"));
			}else{
				$(obj).find(".selname").html($(obj1).html());
			}
			$(obj).find("input[name='"+init_n+"']").val(v);
			if(v != init_v && typeof $(obj).find("input[name='"+init_n+"']").data("onchange") == "function"){
				$(obj).find("input[name='"+init_n+"']").data("onchange")(obj1);
			}
		});
		//初始
		if($.is_empty(init_v) || !$(obj).find("a.sel0[data-v='"+init_v+"']").get(0)){
			$(obj).find(".selname").html($(obj).find(".selname").attr("data-txt"));
		}else{
			$(obj).find(".selname").html($(obj).find("a.sel0[data-v='"+init_v+"']").html());
		}
	});
}
//解析列表行
$.parselist = function(datalist){
	var list_c = "";
	for(var i in datalist){
		for(var j in datalist[i]){
			if($.in_array(j,['goods_cat']) && typeof dataConfig != "undefined"){
				datalist[i][j] = typeof dataConfig[j][""+datalist[i][j]] == "undefined"?datalist[i][j]:dataConfig[j][""+datalist[i][j]];
			}else if($.in_array(j,['idc']) && typeof dataConfig != "undefined"){
				var tmp_v = "";
				if(datalist[i][j]){
					datalist[i][j] = typeof datalist[i][j] == "string"?[datalist[i][j]]:datalist[i][j];
					for(var k in datalist[i][j]){
						tmp_v += dataConfig[j][datalist[i][j][k]]?(", " + dataConfig[j][datalist[i][j][k]].name):"";
					}
				}
				datalist[i][j] =tmp_v != ''?tmp_v.substring(2):datalist[i][j];
			}else if($.in_array(j,["dateline","logdateline","regdateline","lastdateline","start_dateline","cfm_dateline","work_dateline"])){
				if(datalist[i][j]>0){
					var format = "Y-m-d H:i:s";
					datalist[i][j] = $.time_to_string(datalist[i][j],format);// * 1000).toLocaleString().replace(/:\d{1,2}$/,""); 
				}else{
					datalist[i][j] = '-';
				}
				//replace(/年|月/g, "-").replace(/日/g, " ");
			}
		}
		list_c += easyTemplate($("#tpl_list_row").html(),datalist[i]);
	}
	if(list_c == ""){
		list_c = "<tr><td colspan='99' style='padding:9px 0 22px 24px;'><div class=' f14'> <cite class='glyphicon glyphicon-exclamation-sign font-org f18' style='position:relative;top:4px;'></cite> &nbsp;暂无数据！</div></td></tr>"
	}
	return list_c;
}
//加载表单
$.loadform = function(_datalist,_url,_callback,_callback2,_formstr,_callback3){
	var formstr = typeof _formstr == "undefined"?".theform":_formstr;
	var formobj = $(formstr);
    if(typeof _url != "undefined" && _url != ""){
    	$.ajaxPassport({
    		url:_url,
    		success:function(res){
    			if(res.error == 1){
    				$.tips(res.message,"error",formobj);
    			}else{
    				if(typeof _callback3 == "function"){
    					_callback3(res,formobj);
    				}
    				
        			$.parseform(_datalist,res,formobj);
    				$.valid_form(formobj,_callback);
    				
    				if(typeof _callback2 == "function"){
    					_callback2(res,formobj);
    				}    				
    			}
    	   }
    	});
    }else{
    	if(typeof _callback3 == "function"){
			_callback3(res,formobj);
		}

    	$.parseform(_datalist,{},formobj);
		$.valid_form(formobj,_callback);

		if(typeof _callback2 == "function"){
			_callback2(formobj);
		}
    }
}
//加载列表
$.loadlist = function(_datalist,_callback,_url,_data,_formstr){
	var formstr = typeof _formstr == "undefined"?".thelistform":_formstr;
	var formobj = $(formstr);
    if(typeof _url != "undefined" && _url != ""){
    	layer.load(2);
    	$.ajaxPassport({
    		url:_url,
    		success:function(res){
    			if(res.error == 1){
					$.tips(res.message,"error");
					return false;
				}
    			formobj.find(".tpl").html($.parselist(res.list));
    			formobj.find(".pagebar").html(res.pagebar);
    			if(typeof _callback == "function"){
					_callback(res,formobj);
				}
    			//此处演示关闭
    			setTimeout(function(){
    			    layer.closeAll('loading');
    			}, 100);
    	  },
    	  data:_data
    	});
    }else{
    	formobj.find(".tpl").html($.parselist(_datalist.list));
    	formobj.find(".pagebar").html(_datalist.pagebar);
		if(typeof _callback == "function"){
			_callback(formobj);
		}
    }
}
//包装通用 AJAX
$.ajaxLoadlist = function(page,url,callback){
	url = url.replace(/&amp;/g,"&");
	$.loadlist([],callback,url,{page:page});
}
//insert flash
$.insert_flash = function(parastr,idstr,swfname,width,height){//页面插入FLASH
		   var  objstr = "";
		   objstr += "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\" width=\""+width+"\" height=\""+height+"\" id=\"fl_"+idstr+"\" align=\"middle\"\>\n"; 
		   objstr += "<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n"; 
		   objstr += "<param name=\"movie\" value=\""+swfname+".swf\" />"; 
		   objstr += "<param name=\"FlashVars\" value=\""+ parastr +"\" />"; 
		   objstr += "<param name=\"quality\" value=\"high\" />"; 
		   objstr += "<param name=\"wmode\" value=\"transparent\" />";
		   objstr += "<embed wmode=\"transparent\" src=\""+swfname+".swf\" quality=\"high\" width=\""+width+"\" height=\""+height+"\" name=\""+idstr+"\" align=\"middle\" allowScriptAccess=\"sameDomain\" FlashVars=\""+ parastr +"\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />"; 
		   objstr += "</object\>";
		   $("#"+idstr).html(objstr);
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
			$.tips(res.message,"success");
		}else{
			if(res.error == 1){
				$.tips(res.message,"error",obj);
			}else{
				$.tips(res.message,"debug",obj);
			}
			return false; 
		}
	}
	options.error = options.error || function(e){
		$.tips(e.responseText,"debug");
	}
	options.complete = options.complete || function(e){
	}
	//不缓存ajax结果
	options.cache  = false;
	$.ajax(options);
}
$.ajaxForm = function($that,i){
	var iframe_id = 'form_iframe'+((typeof i == "undefined")?parseInt(Math.random()*1000):i);
	//before function()
	if(typeof $($that).data("data-before") == "function"){
		if($($that).data("data-before")($that) == false){
			return false;
		}
	}
	//button
	var thebtn = $($that).find("button[type='submit']").get(0);
	//$(thebtn).attr("disabled",true).addClass("btn-gray");
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
	   	var show_tip = function(){
		   	var tpl_s = "<#macro tip data><div class='app-callout app-callout-${data.type} f16'>${data.message}</div></#macro>";
			var tpl_v = {
				message:(""+res.message).replace("[br]","<br/>"),
				type:(res.error == 0?"success":"error")
			}
			$that_tip = $($that).find(".ajax_form_tipbox");
			if(!$that_tip.get(0)){
				$($that).prepend("<div class='ajax_form_tipbox'></div>");
				$that_tip = $($that).find(".ajax_form_tipbox");
			}
			$that_tip.html(""+easyTemplate(tpl_s,tpl_v));
			$that_tip.fadeIn();
			$('html,body').animate({scrollTop: 0}, 500);
			setTimeout(function(){
				$that_tip.fadeOut("fast");	
			},2000);
	   	}
	   	if(typeof $($that).data("data-success") == 'function'){
			if($($that).data("data-success")(res,$that)){
			}
		}else{
			show_tip();
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
$.t_ajax_button = function(obj){
	$(obj).click(function(e){
			var options = {};
			var obj = this;
			//执行之前
			if(typeof $(obj).data("data-before") == "function"){
				if($(obj).data("data-before")(obj) == false){
					return false;
				}
			}
			//if
			if(parseInt($(obj).attr("confirm")) == 1){
				if(confirm("您确定要执行该操作吗?") === false){
					return false;
				}
			}
			//设置参数
			options.url = U($(obj).attr("data-url")) || "";
			options.type = $(obj).attr("data-type")|| "GET";
			options.data = $(obj).attr("data-data") || {};
			options.contentType = $(obj).attr("data-contentType") || "application/json";
			options.dataType = $(obj).attr("data-dataType") || "json";
			options.success = $(obj).data("data-success") || function(res){
				if(res.error == 0){
					$.tips(res.message,"success");
				}else{
					if(res.error == 1){
						$.tips(res.message,"error",obj);
					}else{
						$.tips(res.message,"debug",obj);
					}
    				return false;
				}
				if(res.callback == "reload"){
					setTimeout("window.location.reload()",500);
				}else if(res.callback){
					res.callback = res.callback.replace(/&amp;/g,"&");
					setTimeout("window.location.replace('"+res.callback+"')",500);	
				}
				$(obj).attr("disabled",false).removeClass("btn-gray");
			}
			//开始
			$(obj).attr("disabled",true).addClass("btn-gray");
			$.ajaxPassport(options);
			return false;
		});
}
$(function(){
	//AJAX FORM
	$(".t-ajax-form").each(function(i){
		$.valid_form($(this));
	});
	//AJAX BUTTON
	$(".t-ajax-button").each(function(i){
		$.t_ajax_button(this);
	});
	//A HOVER OUT
	$("a").focus(function(){$(this).blur();});
	//SELECT ALL
	$("input.select-all").click(function(){
		    var obj = this;
		    var attr_s = $(obj).attr("data-sel");
			var selected = false;
			var names = $(obj).attr("data-ids");
			attr_s = typeof attr_s == "undefined"?"":attr_s;
			if(attr_s == ""){
				selected = "checked";
			}
			$(obj).attr("data-sel",((attr_s == "")?"selected":""));
		    $("input[name='"+names+"[]']").each(function(){
		    	$(this).attr("checked",selected);
		    });
	});
	//tab hover
	$("ul.data-toggle-hover li").hover(function(){
		var index = $(this).index();
		$("ul.data-toggle-hover li").removeClass("c");
		$(this).addClass("c");
		$(this).parent().parent().parent().find("div.c").hide();
		$(this).parent().parent().parent().find("div.c").eq(index).show();
	});
})


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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


/* 选择用户 **/
var get_userlist = function(page,utype,keyword,formstr,callback){
	var formstr = typeof formstr == "undefined"?".theform":formstr;
	var tpl = "<#macro uid data>\
	<div class='pop_sel_mybox pop_sel_uidbox'>\
	   <div class='mybox-c'>\
	   <div class='serch-uid'><input id='Dget_userlistkeyword' type='text' placeholder='请输入邮箱关键词' value=\"${data.condi.keyword}\"> <button type='button' id='Bget_userlistbtn' class='btn btn-sm btn-success'>搜索</button></div>\
	   <div class='abc-v'>\
	   <a href=\"javascript:void(0);\" data-uid=\"0\" data-uname=\"所有\" class=\"uid e\">清除</a>\
	   <#list data.list as list>\
		   <a href=\"javascript:void(0);\" data-uid=\"${list.uid}\" data-uname=\"${list.email}\" class=\"uid e\" title=\"<#if (!$.is_empty(list.uname))>用户名:${list.uname}</#if> <#if (!$.is_empty(list.mobile))>手机:${list.mobile}</#if>\">\
		   <#if (!$.is_empty(list.email))>${list.email}<#elseif (!$.is_empty(list.mobile))>${list.mobile}<#else>${list.uname}</#if></a>\
	   </#list>\
	   <div class=\"cl\"></div>\
	   </div>\
		</div>\
	</div>\
	<div class='pagebar'>${data.pagebar}</div>\
	</#macro>";

	$.ajaxPassport({
		url:U("/user_manager/userlist_get"),
		success:function(res){
			if(res.error == 1){

			}else{
				var html = "" + easyTemplate(tpl,res);
				var box = $(formstr+" .dropdown-uidbox");
				var selname = box.find(".selname").attr("data-txt");
				var set_show = function(uid,uname){
					var box = $(formstr+" .dropdown-uidbox");
					var default_uid = 0;
					if(uid == -1){
						default_uid =box.find("input[name='uid']").val();
						box.find("input[name='uname']").val("");
					}else{
						box.find("input[name='uid']").val(uid);
						box.find("input[name='uname']").val(uname);
						default_uid = uid;
					}
					var show_name = uname?uname:selname;
					var len = 8;
					var ext_str = show_name.length<len?"":"...";
					box.find(".selname").html(show_name.substr(0,len)+ext_str).attr("title",show_name);
				}

				box.find(".tpl").html(html);
				box.find(".tpl").find("div").bind("click",function(e){
					//e.stopPropagation();
				});

				box.find(".tpl").find("a.uid").click(function(){
					var obj = this;
					var uid = $(obj).attr("data-uid");
					var uname = $(obj).attr("data-uname");


					set_show(uid,uname);//$.show_company(company,"name").substr(0,2)+"-"+

					if(typeof callback == "function"){
						callback(uid,uname);
					}
					return true;
				});

				box.find(".tpl").find("#Bget_userlistbtn").click(function(e){
					var obj = this;
					var keyword = $(obj).parent().find("#Dget_userlistkeyword").val();
					return get_userlist(1,0,keyword,formstr);
				});

				box.find(".pagebar a").bind("click",function(e){
					e.stopPropagation();
				});
				box.find(".serch-uid input").bind("click",function(e){
					e.stopPropagation();
				});
				box.find(".serch-uid button").bind("click",function(e){
					e.stopPropagation();
				});

				box.find(".pagebar input").bind("click",function(e){
					e.stopPropagation();
				});

			}
		},
		data:{page:page,utype:utype,keyword:keyword,formstr:formstr},
	});
}

/* 选择域名 **/
var get_domainlist = function(page,keyword,formstr,callback){
	var formstr = typeof formstr == "undefined"?".theform":formstr;
	var tpl = "<#macro uid data>\
	<div class='pop_sel_mybox pop_sel_uidbox'>\
	   <div class='mybox-c'>\
	   <div class='serch-uid'><input id='Dget_userlistkeyword' type='text' placeholder='请输入域名关键词' value=\"${data.condi.keyword}\"> <button type='button' id='Bget_userlistbtn' class='btn btn-sm btn-success'>搜索</button></div>\
	   <div class='abc-v'>\
	   <#list data.list as list>\
		   <a href=\"javascript:void(0);\" data-domain-id=\"${list.domain_id}\" data-domain=\"${list.domain}\" class=\"uid\">${list.domain}</a>\
	   </#list>\
	   <div class=\"cl\"></div>\
	   </div>\
		</div>\
	</div>\
	<div class='pagebar'>${data.pagebar}</div>\
	</#macro>";

	$.ajaxPassport({
		url:U("/domain_manager/domain_get"),
		success:function(res){
			if(res.error == 1){

			}else{
				var html = "" + easyTemplate(tpl,res);
				var box = $(formstr+" .dropdown-uidbox");
				var selname = box.find(".selname").attr("data-txt");
				var set_show = function(uid,uname){
					var box = $(formstr+" .dropdown-uidbox");
					var default_uid = 0;
					if(uid == -1){
						default_uid =box.find("input[name='uid']").val();
						box.find("input[name='uname']").val("");
					}else{
						box.find("input[name='uid']").val(uid);
						box.find("input[name='uname']").val(uname);
						default_uid = uid;
					}
					var show_name = uname?uname:selname;
					var len = 8;
					var ext_str = show_name.length<len?"":"...";
					box.find(".selname").html(show_name.substr(0,len)+ext_str).attr("title",show_name);
				}

				box.find(".tpl").html(html);
				box.find(".tpl").find("div").bind("click",function(e){
					//e.stopPropagation();
				});

				box.find(".tpl").find("a.uid").click(function(){
					var obj = this;
					var uid = $(obj).attr("data-domain-id");
					var uname = $(obj).attr("data-domain");


					set_show(uid,uname);//$.show_company(company,"name").substr(0,2)+"-"+

					if(typeof callback == "function"){
						callback(uid,uname);
					}
					return true;
				});

				box.find(".tpl").find("#Bget_userlistbtn").click(function(e){
					var obj = this;
					var keyword = $(obj).parent().find("#Dget_userlistkeyword").val();
					return get_domainlist(1,keyword,formstr);
				});

				box.find(".pagebar a").bind("click",function(e){
					e.stopPropagation();
				});
				box.find(".serch-uid input").bind("click",function(e){
					e.stopPropagation();
				});
				box.find(".serch-uid button").bind("click",function(e){
					e.stopPropagation();
				});

				box.find(".pagebar input").bind("click",function(e){
					e.stopPropagation();
				});

			}
		},
		data:{page:page,keyword:keyword,formstr:formstr},
	});
}