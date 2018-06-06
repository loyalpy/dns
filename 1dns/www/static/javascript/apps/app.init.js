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
		  offset = "120px";
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
$.init_form = function(type,formobj){
	switch(type){
		case "input":
			formobj.find('.input-tips').each(function(){
				var obj = this;
				$(obj).find("a").unbind("click").bind("click",function(e){
					$(obj).parent().find("input[type='text']").val($(this).attr("data-v"));
					$(obj).hide();
					$(obj).parent().parent().css("z-index",0);
					e.stopPropagation();
				})
				$(obj).parent().find("input[type='text']").unbind("focus").bind("focus",function(){
					var obj1 = this;
					formobj.find(".tselect").parent().parent().css("z-index",0);
				    formobj.find(".tselect").find(".tselect-box").hide();
					formobj.find('.input-tips').hide().css("z-index",0);
					$(obj).show().parent().parent().css("z-index",99);
				})
				$(obj).parent().find("input[type='text']").unbind("click").bind("click",function(e){
					e.stopPropagation();
				});
			});
			break;
		case "job_cate":
		case "trade_cate":
		case "city":
			var set_select = function(o,v){			    
			    var cur_n = $(o).attr("data-name");
			    var num   = $(o).attr("data-num");
			    var init_l = $(o).attr("data-label");
				var cur_s = $(o).find("input[name='"+cur_n+"']").val();
				var cur_v = cur_s == ""?[]:cur_s.split(",");
				eval("var data_sr= "+type+"_C");
				//选择值				
				if(v > 0){
					if(num >1){						
						if(!$.in_array(v,cur_v)){
							for(var i in data_sr){
								if($.in_array(i,cur_v)){
									if(data_sr[i]['pid'] == v){
										cur_v = $.remove_array_item (cur_v,i);
									}else if(data_sr[i]['id'] == data_sr[v]['pid']){
										cur_v = $.remove_array_item (cur_v,i);
									}
								}						
							}							
							if(cur_v.length >= num){
								$.ui.error("最多选择 "+num+ "项","error");
								return false;
							}else{
								cur_v.push(v);
							}
						}else{
							cur_v = $.remove_array_item (cur_v,v);	
						}				
					}else{
						cur_v = [v];
					}					
				}else if(v == -9){//清空
					cur_v = [];
				}
				cur_v = $.remove_array_item (cur_v,'');
				$(o).find("input[name='"+cur_n+"']").val(cur_v.join(","));
				
				var label = [];
				var sel_v = [];
				for(var i in cur_v){
					if(typeof data_sr[cur_v[i]] != "undefined"){
						label.push(data_sr[cur_v[i]]['name']);
						sel_v.push("<a href='javascript:void(0)' data-v='"+cur_v[i]+"' class='e'>"+data_sr[cur_v[i]]['name']+"<cite></cite></a>");
					}
				}
				if(label.length > 0){
					$(o).find(".tselect-text").html(label.join(",&nbsp;"));
					$(o).find(".select-top .top-in").html(sel_v.join(""));
					$(o).find(".select-top .top-in").find("a").unbind("click").bind("click",function(e){
						set_select(o,$(this).attr("data-v"));
						e.stopPropagation();
					})
				}else{
					$(o).find(".tselect-text").html(init_l);
					$(o).find(".select-top .top-in").html("<span class='tip'>最多选择3项</span>");
				}
				if(num < 2){
					$(o).find(".tselect-box").hide();
				}
		     }
			
			formobj.find(".tcate-"+type).each(function(){
				eval("var data_sr= "+type+"_C");
				var obj = this;
				var data_html = "";
				var top = type == "city"?tCity:0;
				var cols = 5;
				var row  = 1;
				var auto_k = 1;
				var num = $(obj).attr("data-num");
				set_select(obj,0);//初始化
				
				if(num > 0){
					$(obj).find(".select-top .btn0").unbind("click").bind("click",function(e){
						set_select(obj,-9);
						e.stopPropagation();
					})
				}
				
				for(var i in data_sr){
					if(data_sr[i]['pid'] == top){
						data_html += "<a href='javascript:void(0);' title='"+data_sr[i]['name']+"' class='u0 e "+ (data_sr[i]['has_children'] > 0?"childs":"") +"' data-row='row"+row+"' data-v='"+i+"'>"+data_sr[i]['name']+""+ (data_sr[i]['has_children'] > 0?"<cite></cite>":"") +" </a>";
						if(auto_k % cols == 0){
							data_html += "<div class='cl'></div><div style='display:none;' class='row row"+row+"'></div>";
							row ++;
						}
						auto_k ++;
					}
				}
				data_html += "<div class='cl'></div><div style='display:none;' class='row row"+row+"'></div>";
				$(obj).find(".in .select-bod").html(data_html);
				$(obj).find(".in .select-bod a.u0 ").unbind("click").bind("click",function(e){
					var obj1 = this;
					var init_v = $(obj1).attr("data-v");
					var row    = $(obj1).attr("data-row");
					var data_html = "";
					if(num > 0){
						e.stopPropagation();
					}
					
					if(data_sr[init_v]['has_children'] >0 ){
						e.stopPropagation();
						data_html += "<a href='javascript:void(0);' title='所有' class='u1 e' data-row='row"+row+"' data-v='"+init_v+"'>不限</a>";
						for(var i in data_sr){							
							if(data_sr[i]['pid'] == init_v){
								data_html += "<a href='javascript:void(0);' title='"+data_sr[i]['name']+"' class='u1 e' data-row='row"+row+"' data-v='"+i+"'>"+data_sr[i]['name']+"</a>";
							}
						}						
					}else{
						set_select(obj,init_v);
					}
					$(obj).find(".in .select-bod .row").hide();
					$(obj).find(".in .select-bod a.active").removeClass("active");
					
					if(data_html != ""){					
						$(obj1).addClass("active");
						$(obj).find("."+row).html(data_html).show();
						$(obj).find("."+row).find("a.u1").unbind("click").bind("click",function(e){							
							if(num > 0){
								e.stopPropagation();
							}
							set_select(obj,$(this).attr("data-v"));
						});
					}					
				});
			});
			break;
		case "area":
			formobj.find(".form-item-area").each(function(){
				var obj = this;
				var init_v = $(obj).find("input.hid_area").val();
				var init_n = $(obj).find("input.hid_area").attr("name");
				var init_v_s = init_v.split(",");
				for(var i in init_v_s){
					$(obj).find("input[name='"+init_n+"s["+i+"]']").val(init_v_s[i]);
				}
			})
			formobj.find(".tarea-0").each(function(){
				var obj = this;
				var data_html = ["","",""];
				for(var i in area_C){
					if($.in_array(area_C[i]['pid'],[1])){
						data_html[0] += "<a href='javascript:void(0);' class='sel0 e' data-v='"+i+"'>"+area_C[i]['name']+"</a>";
					}else if($.in_array(area_C[i]['pid'],[2])){
						data_html[2] += "<a href='javascript:void(0);' class='sel0 e' data-v='"+i+"'>"+area_C[i]['name']+"</a>";
					}else if(area_C[i]['pid'] == 0 && i > 999){
						data_html[1] += "<a href='javascript:void(0);' title='"+area_C[i]['name']+"' class='sel0 e' data-v='"+i+"'>"+area_C[i]['name']+"</a>";
					}
				}
				$(obj).find(".in").html(data_html.join("<div class='cl'></div>"));
				$.init_form("select",formobj);				
				
				var init_n = $(obj).attr("data-name");
				var init_v = $(obj).find("input[name='"+init_n+"']").val();
				$(obj).find("input[name='"+init_n+"']").data("onchange",function(obj1){
					var top_v = $(obj1).find("input[name='"+init_n+"']").val();
					
					if($(obj1).parent().find(".tarea-1").get(0)){
						var data_html = "";
						for(var i in area_C){
							if(area_C[i]['pid'] == top_v){
								data_html += "<a href='javascript:void(0);' title='"+area_C[i]['name']+"' class='sel0 e' data-v='"+i+"'>"+area_C[i]['name']+"</a>";
							}
						}
						$(obj1).parent().find(".tarea-1").find(".in").html(data_html);
						$.init_form("select",formobj);
					    $(obj1).parent().find(".tarea-1").find(".tselect-text").click();

					    var inp_name = $(obj1).parent().find(".tarea-1").attr("data-name");
					    $(obj1).parent().find(".tarea-1").find("input[name='"+inp_name+"']").val("");
					    
					    var inp_name = $(obj1).parent().find(".tarea-2").attr("data-name");
					    $(obj1).parent().find(".tarea-2").find("input[name='"+inp_name+"']").val("");
					    return false;
					}
				});
				if(init_v){
					var data_html = "";
					for(var i in area_C){
						if(area_C[i]['pid'] == init_v){
							data_html += "<a href='javascript:void(0);' title='"+area_C[i]['name']+"' class='sel0 e' data-v='"+i+"'>"+area_C[i]['name']+"</a>";
						}
					}
					$(obj).parent().find(".tarea-1").find(".in").html(data_html);
				}
			})			
			formobj.find(".tarea-1").each(function(){
				var obj = this;
				var init_n = $(obj).attr("data-name");
				var init_v = $(obj).find("input[name='"+init_n+"']").val();
				
				$(obj).find("input[name='"+init_n+"']").data("onchange",function(obj1){
					var top_v = $(obj1).find("input[name='"+init_n+"']").val();
					if($(obj1).parent().find(".tarea-2").get(0)){
						var data_html = "";
						for(var i in area_C){
							if(area_C[i]['pid'] == top_v){
								data_html += "<a href='javascript:void(0);' title='"+area_C[i]['name']+"' class='sel0 e' data-v='"+i+"'>"+area_C[i]['name']+"</a>";
							}
						}
						$(obj1).parent().find(".tarea-2").find(".in").html(data_html);
						$.init_form("select",formobj);
					    $(obj1).parent().find(".tarea-2").find(".tselect-text").click();
					    return false;
					}
				});	
				
				if(init_v){
					var data_html = "";
					for(var i in area_C){
						if(area_C[i]['pid'] == init_v){
							data_html += "<a href='javascript:void(0);' title='"+area_C[i]['name']+"' class='sel0 e' data-v='"+i+"'>"+area_C[i]['name']+"</a>";
						}
					}
					$(obj).parent().find(".tarea-2").find(".in").html(data_html);
				}
			})
			break;
		case "date":
			formobj.find(".form-item-date").each(function(){
				var obj = this;
				var init_v = $(obj).find("input.hid_date").val();
				var init_n = $(obj).find("input.hid_date").attr("name");
				if(!isNaN(init_v) && init_v>10000){
					var d = new Date(init_v*1000);
					init_v = ""+((d.getYear()<1900)?(1900+d.getYear()):d.getYear())+","+(d.getMonth()+1)+","+d.getDate();
				}
				var init_v_s = init_v.split(",");
				for(var i in init_v_s){
					$(obj).find("input[name='"+init_n+"s["+i+"]']").val(init_v_s[i]);
				}
			})
			formobj.find(".tdate-year").each(function(){
				var obj = this;
				var init_n = $(obj).attr("data-name");
				var start_y = parseInt($(obj).attr("data-start"));
				var end_y = parseInt($(obj).attr("data-end"));
				var zj = parseInt($(obj).attr("data-zj"));
				var data_html = "";
				for(var i = start_y;i<=end_y;i++){
					data_html += "<a href='javascript:void(0);' class='sel0 e' data-v='"+i+"'>"+i+" 年</a>";
				}
				if(zj == 1){
					data_html += "<a href='javascript:void(0);' class='sel0 font-blue e' data-v='9999'>至今</a>";
				}
				$(obj).find(".in").html(data_html);	
				if(zj == 0){
					$(obj).find("input[name='"+init_n+"']").data("onchange",function(obj1){
						if($(obj1).parent().find(".tdate-month").get(0)){
						    $(obj1).parent().find(".tdate-month").find(".tselect-text").click();
						    return false;
						}
					})
				}
			})
			formobj.find(".tdate-month").each(function(){
				var obj = this;
				var init_n = $(obj).attr("data-name");
				var data_html = "";
				for(var i = 1;i<=12;i++){
					data_html += "<a href='javascript:void(0);' class='sel0 e' data-v='"+i+"'>"+i+" 月</a>";
				}
				$(obj).find(".in").html(data_html);				
				$(obj).find("input[name='"+init_n+"']").data("onchange",function(obj1){
					if($(obj1).parent().find(".tdate-day").get(0)){
					    $(obj1).parent().find(".tdate-day").find(".tselect-text").click();
					    return false;
					}
				})
			})
			
			formobj.find(".tdate-day").each(function(){
				var obj = this;
				var data_html = "";
				for(var i = 1;i<=31;i++){
					data_html += "<a href='javascript:void(0);' class='sel0 e' data-v='"+i+"'>"+i+" 日</a>";
				}
				$(obj).find(".in").html(data_html);
			});
			break;
		case "checkbox":
			formobj.find(".tcheckbox").each(function(){
				var obj = this;
				var init_n = $(obj).attr("data-name");
				var init_s = $(obj).find("input[name='"+init_n+"']").val();
				var init_v = init_s == ""?[]:init_s.split(",");
				$(obj).find("a").each(function(){
					var obj1 = this;
					var v = $(obj1).attr("data-v");
					if($.in_array(v,init_v)){
						$(obj1).addClass("active");
					}
				})
				$(obj).find("a").unbind("click").bind("click",function(){
					var obj1 = this;
					var cur_s = $(obj).find("input[name='"+init_n+"']").val();
					var cur_v = cur_s == ""?[]:cur_s.split(",");				
					var v = $(obj1).attr("data-v");

					if($.in_array(v,cur_v)){
						cur_v = $.remove_array_item (cur_v,v);
						cur_v = $.remove_array_item (cur_v,'');						
						$(obj1).removeClass("active");
					}else{
						cur_v.push(v);
						cur_v = $.remove_array_item (cur_v,'');
						$(obj1).addClass("active");
					}
					$(obj).find("input[name='"+init_n+"']").val(cur_v.join(","));
				});
				
			});
			break;
		case "radio":
			formobj.find(".tradio").each(function(){
				var obj = this;
				var init_n = $(obj).attr("data-name");
				var init_v = $(obj).find("input[name='"+init_n+"']").val();
				$(obj).find("a").each(function(){
					var obj1 = this;
					var v = $(obj1).attr("data-v");
					if(v == init_v){
						$(obj1).addClass("active");
					}
				})
				$(obj).find("a").unbind("click").bind("click",function(){
					var obj1 = this;					
					var v = $(obj1).attr("data-v");
					$(obj).find("a.active").removeClass("active");
					$(obj).find("input[name='"+init_n+"']").val(v);
					$(obj1).addClass("active");
				});
				
			});
			break;
		case "switch":
			formobj.find(".tswitch").each(function(){
				var obj = this;
				var init_n = $(obj).attr("data-name");
				var init_v = $(obj).find("input[name='"+init_n+"']").val();

				var label_on = $(obj).attr("data-label_on");
				var label_off = $(obj).attr("data-label_off");
				
				label_on  = label_on == "undefined"?"是":label_on;
				label_off = label_off == "undefined"?"否":label_off;
				
				$(obj).find("a").each(function(){
					var obj1 = this;
					var v = $(obj1).attr("data-v");
					if(1 == init_v){
						$(obj1).addClass("active");
						$(obj1).find("span").html(label_on);
					}else{
						$(obj1).find("span").html(label_off);
					}
				})
				$(obj).find("a").unbind("click").bind("click",function(){
					var obj1 = this;					
					var cur_v = $(obj).find("input[name='"+init_n+"']").val();
					
					if(cur_v == 1){
						$(obj).find("a.active").removeClass("active");
						$(obj).find("input[name='"+init_n+"']").val(0);
						$(obj1).find("span").html(label_off);
					}else{
						$(obj).find("a").addClass("active");
						$(obj).find("input[name='"+init_n+"']").val(1);
						$(obj1).find("span").html(label_on);
					}
					if(typeof $(obj).find("input[name='"+init_n+"']").data("onchange") == "function"){
						$(obj).find("input[name='"+init_n+"']").data("onchange")(obj);
					}					
				});
				
			});
			break;
		case "select":
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
			            	$(obj).parent().parent().css("z-index",99);
			            	$(obj).find(".tselect-box").show();
			            } else {
			            	$(obj).parent().parent().css("z-index",0);
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
						$(obj).find(".tselect-text").html($(obj1).html());
					}
					$(obj).find("input[name='"+init_n+"']").val(v);
					$(obj).find('.tselect-box').hide();
                	$(obj).parent().parent().css("z-index",0);
					if(v != cur_v && typeof $(obj).find("input[name='"+init_n+"']").data("onchange") == "function"){
						$(obj).find("input[name='"+init_n+"']").data("onchange")(obj);
					}
					e.stopPropagation();
				});
				//初始
				if($.is_empty(init_v)){
					$(obj).find(".tselect-text").html(init_l);
				}else{
					$(obj).find(".tselect-text").html($(obj).find("a.sel0[data-v='"+init_v+"']").html());
				}
			});
		default:
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
				        	$.ui.success(res.message);
						}else{
							if(res.error == 1){
								$.ui.error(res.message);
							}else{
								$.ui.open(res.message,false,"700px");
							}							
							return false;
						}
						if(res.callback == "reload"){
							setTimeout("window.location.reload()",500);
						}else if(res.callback == "close"){
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
		form_c += easyTemplate($("#tpl_form").html(),_datalist[i]);
	}
	formobj.find(".tpl").html(form_c+"<div class='cl'></div>");
	//加载后处理
	$.init_form("input",formobj);
	$.init_form("date",formobj);
	$.init_form("area",formobj);

	
	$.init_form("checkbox",formobj);
	$.init_form("radio",formobj);
	$.init_form("switch",formobj);	
	$.init_form("select",formobj);
	
	$.init_form("city",formobj);
	$.init_form("job_cate",formobj);
	$.init_form("trade_cate",formobj);
	
	$(document).unbind("click").bind("click",function (e) {
        if (formobj.find(".tselect-box").css('display') != 'none') {   
            
        }
        formobj.find(".tselect").parent().parent().css("z-index",0);
	    formobj.find(".tselect").find(".tselect-box").hide();
	    
    	formobj.find('.input-tips').hide();
    	formobj.find('.input-tips').parent().parent().css("z-index",0);
	 });
}
//加载表单
$.loadform = function(_datalist,_re,_callback,_callback2,_formstr,_callback3){
	var formstr = typeof _formstr == "undefined"?".theform":_formstr;
	var formobj = $(formstr);
    if(!$.is_empty(_re) && $.is_string(_re)){
    	$.ui.loading();
    	$.ajaxPassport({
    		url:_re,
    		success:function(res){
    			if(res.error == 1){
    				$.ui.error(res.message);  				
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
    			$.ui.close_loading();
    	   }
    	});
    }else{
    	if(typeof _callback3 == "function"){
			_callback3(_re,formobj);
		}
					
    	$.parseform(_datalist,((!$.is_empty(_re) && $.is_object(_re))?_re:{}),formobj);
		$.valid_form(formobj,_callback);
		
		if(typeof _callback2 == "function"){
			_callback2(_re,formobj);
		}
    }
}

///////////////////////// 加载列表对象
$.list = {
	o:{},
	index:0,
	init:function(url,page,orderby,callback,box){//box列表盒子,url列表请求URL,page 请求页码 orderby请求排序
		var box = $.is_empty(box)?"datalist":box;
		var boxstr = "."+box;
		if($.is_empty($.list.o[box])){
			$.list.o[box] = {};
		}
		$.list.set('url',url.replace(/&amp;/g,"&"),box);
		$.list.set('page',page,box);
		$.list.set('orderby',orderby,box);
		$.list.set('callback',callback,box);
		
		//排序点击操作
		$(boxstr).find("a.orderby").unbind("click").bind("click",function(){
			var obj     = this;
			var or_item = $(obj).attr("data-item");
			var or_desc = $(obj).attr("data-desc");
			var orderby = (or_item+"!"+(or_desc == "ASC"?"DESC":"ASC"));
			$.list.load_order(orderby,box);
		});
		//全选全不选
		$(boxstr).find("input.chkall").click(function(){
			var obj = this;
			var name = $(obj).attr("data-name")
			var flag = obj.checked; 
			$("input[name='"+name+"']").each(function() {
				this.checked = flag;
			});
		})
		//批量操作按钮;
		$(boxstr).find(".batch").unbind("click").bind("click",function(){
			var obj = this;
			var ids = $.list.fetch_ids(box);
			var url = $(obj).attr("data-url");
			var exttip = $(obj).attr("data-tip");
			exttip = $.is_empty(exttip)?"":exttip;
			if(ids == ""){
				$.ui.alert("请先选择操作项",3);
				return false;
			}else{
				$.ui.confirm(function(){
					$.ajaxPassport({
						url:url,
						data:{ids:ids},
						success:function(res){
							if(res.error){
								$.ui.error(res.message);
							}else{
								if(typeof res.data != "undefined"){
									
									if(res.data > 0){
										$.ui.alert("操作影响记录 <b class='font-red'> "+res.data+" </b>行"+"<br/>"+exttip);
										$.list.load(box);
									}else{
										$.ui.alert("操作影响记录 <b class='font-red'> 0 </b>行"+"<br/>"+exttip);
									}
								}else{
									$.ui.alert(res.message);
									$.list.load(box);
								}
								
							}							
						}
					});
				},"操作执行要一点时间,请耐心等待,")
			}
			
		})
		//加载
		$.list.load(box);
	},
	set:function(key,value,box){
		var box = $.is_empty(box)?"datalist":box;
		if($.is_empty($.list.o[box])){
			$.list.o[box] = {url:"",page:1,orderby:"",callback:null};
		}
		$.list.o[box][key] = value;
	},
	load_url:function(url,box){
		var box = $.is_empty(box)?"datalist":box;
		$.list.set('url',url.replace(/&amp;/g,"&"),box);
		$.list.load(box);	
	},
	load_page:function(page,box){
		var box = $.is_empty(box)?"datalist":box;
		$.list.set('page',page,box);
		$.list.load(box);	
	},
	load_order:function(orderby,box){
		var box = $.is_empty(box)?"datalist":box;
		$.list.set('orderby',orderby,box);
		$.list.load(box);
	},
	load:function(box,res){
		var box = $.is_empty(box)?"datalist":box;
		var boxstr = "."+box;
		if($.is_empty(res)){
			res = {"list":[],'pagebar':"","orderby":""};
		}
		if(!$.is_empty($.list.o[box]['url'])){
	    	$.ui.loading();
	    	$.ajaxPassport({
	    		url:$.list.o[box]['url'],
	    		data:{page:$.list.o[box]['page'],orderby:$.list.o[box]['orderby']},
	    		success:function(res){
	    			//此处演示关闭Loading
	    			$.ui.close_loading();
	    			if(res.error == 1){
						$.ui.error(res.message);
						return false; 
					}
	    			$(boxstr).find(".list").html($.list.parse_list(res.list,box));
	    			$(boxstr).find(".pagebar").html(res.pagebar);
	    			//初始化关键词替换
	    			$.list.init_keyword(box);
	    			//初始化排序
	    			$.list.init_order(box,res.orderby);
	    			//回应操作
	    			if($.is_function($.list.o[box]['callback'])){
	    				$.list.o[box]['callback'](res,box);
					}	    			
	    	  }
	    	});
	    }else{
	    	$(boxstr).find(".list").html(box,$.list.parse_list(res.list,box));
	    	$(boxstr).find(".pagebar").html(res.pagebar);
	    	//回应操作
			if($.is_function($.list.o[box]['callback'])){
				$.list.o[box]['callback'](res,box);
			}
	    }
	},
	parse_list:function(datalist,box){
		var boxstr = "."+box;		
		var html   = "";
		var tpl = $(boxstr).attr("data-tpl");
		for(var i in datalist){
			html += "" + easyTemplate($("#"+tpl).html(),datalist[i]);
		}
		if(html == ""){
			html = "<tr><td colspan='"+$(boxstr).find("thead tr th").length+"' style='padding:9px 0 22px 24px;'><div class='noresult f14 font-red'><cite></cite>&nbsp;暂无数据！</div></td></tr>"
		}
		return html;
	},
	init_keyword:function(box){
		var boxstr = "."+box;
		var keyword = $(boxstr).find("input[name='keyword']").val();
		if(!$.is_empty(keyword)){
			$(boxstr).find(".list .keyword").each(function(){
				var obj = this;
				var html = $(obj).html();
				$(obj).html($.replace_keyword(html,keyword))
			});
		};
	},
	init_order:function(box,orderby){
		var boxstr = "."+box;
		$(boxstr).find("a.orderby").each(function(){
			var obj     = this;
			var or_item = $(obj).attr("data-item");
			var or_v    = "ASC";			
			$(obj).attr("data-desc",or_v);
			$(obj).find("cite").attr("class","orderby-up");
			//默认排序
			if(!$.is_empty(orderby)){
				var orderby_arr = orderby.split("!");
				if(or_item == orderby_arr[0] && $.in_array(orderby_arr[1],['ASC','DESC'])){
				   	or_v =  orderby_arr[1];
				   	$(obj).attr("data-desc",or_v);
				   	 if(or_v == "DESC"){
				   	 	$(obj).find("cite").attr("class","orderby-down");
				   	 } 	
				}
			}
		});
	},
	fetch_ids:function(box,type){
		var boxstr = "."+box;
		var ids = [];
		var batchids = $(boxstr).find("input[name='ids[]']");
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
	},
	////add
	edit:function(_option){
		var option = {
			url:_option.url || "",
			submit:_option.submit || null,
			load:_option.load || null,
			init:_option.init || null,
			title:_option.title || "添加/编辑",
			size:_option.size || "800px",
			formdata:_option.formdata || {},
		}
		var form_html = "<form action='"+option.url+"' method='POST' enctype='multipart/form-data' class='theform'><div class='tpl'></div></form>";
		$.list.index = $.ui.open(form_html,option.title,option.size);
		$.loadform(option.formdata,option.url,option.submit,option.load,".theform",option.init);
	}
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
					$.ui.success(res.message);
				}else{
					if(res.error == 1){
						$.ui.error(res.message);
					}else{
						$.ui.open(res.message,false,"700px");
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
