var share_title = '';
var pic = '';
var share_url = location.href;
  
var sina_url = "http://v.t.sina.com.cn/share/share.php";
var sina_appkey = '2401400785';
var sina_share_id = '#万才网#';
  
var tengx_appkey = '';
var tengx_url = "http://v.t.qq.com/share/share.php";
var tengx_share_id = '@万才网';
  
var sohu_url = "http://t.sohu.com/third/post.jsp";
var kaixin_url = "http://www.kaixin001.com/repaste/share.php";
var douban_url = "http://www.douban.com/recommend/";
  
var comparm = "url="+encodeURIComponent(share_url);
function go_share(obj,type){
	    share_title = obj.title;
	    share_title = share_title == ""?$("title").html():share_title;
	    pic = $(obj).attr("_pic");
		switch(type){
			case "sina":
				sina_url  += "?"+comparm+"&title="+encodeURIComponent(sina_share_id+share_title)+"";
				sina_url += (pic == "")?"":("&pic="+encodeURIComponent(pic));
			    sina_url += "&appkey="+sina_appkey;
			    obj.href = sina_url;
				return true;
				break;
			case "tengx":
			    tengx_url += "?"+comparm+"&title="+encodeURIComponent(tengx_share_id+share_title)+"";
			    tengx_url += (pic == "")?"":("&pic="+encodeURIComponent(pic));
				tengx_url += "&appkey="+tengx_appkey;
				obj.href = tengx_url;
				return true;
				break;
			case "sohu":
			    sohu_url += "?"+comparm+"&title="+encodeURIComponent(share_title)+"&content=utf-8";
			    sohu_url += (pic == "")?"":("&pic="+encodeURIComponent(pic));
				obj.href = sohu_url;
				return true;
				break;
			case "kaixin":
			    kaixin_url += "?rurl"+encodeURIComponent(share_url)+"&rtitle="+encodeURIComponent(share_title);
			    kaixin_url += (pic == "")?"":("&pic="+encodeURIComponent(pic));
				obj.href = kaixin_url;
				return true;
				break;
			case "douban":
			    douban_url += "url"+encodeURIComponent(share_url)+"&rtitle="+encodeURIComponent(share_title);
			    douban_url += (pic == "")?"":("&pic="+encodeURIComponent(pic));
				obj.href = douban_url;
				return true;
				break;
			default:
				return false;
				break;
		}
 }