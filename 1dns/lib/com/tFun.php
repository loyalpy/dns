<?php
class tFun{
	//创建订单号
	public static function create_no($pre="",$nre=""){
		return $pre.substr(date('YmdHis'),2).rand(10,99).sprintf("%09d", $nre);
	}
	//地区
	public static function area_path($area,$a){
		$a = intval($a);
		if(empty($a)){
			return "";
		}
		if($a%1000000 == 0){
			return $area[$a];
		}elseif($a%10000 == 0){
			return (isset($area[floor($a/1000000)*1000000])?$area[floor($a/1000000)*1000000]:"")." ".$area[$a];
		}elseif($a%100 == 0){
			return (isset($area[floor($a/1000000)*1000000])?$area[floor($a/1000000)*1000000]:"")." ".(isset($area[floor($a/10000)*10000])?$area[floor($a/10000)*10000]:"")." ".$area[$a];
		}
	}
	//显示地区
	public static function area_show($area,$a,$len = 0,$path=1){
		$path = $path?1:0;
		$arr = explode(",",str_replace("A","",$a));
		$r = "";
		if($arr){
			foreach($arr as $v){
				if(trim($v)){
					$vl = ($path == 1)?self::area_path($area,$v):(isset($area[$v])?$area[$v]:"");
					$r .= ($r == "")?$vl:" + ".$vl;
				}
			}
		}
		$r = $r == ""?"不限":$r;
		if($len>0){
			$r = tUtil::substr($r,$len,1);
		}
		return $r;
	}
	//2级转换全路径显示
	public static function lv2_path($datas,$a){
		$a = intval($a);
		if(empty($a)){
			return "";
		}
		if($a%100 == 0){
			return $datas[$a];
		}else{
			return $datas[floor($a/100)*100]." ".$datas[$a];
		}
	}
	//2级转换
	public static function lv2_show($datas,$a,$len=0,$path=1,$split=" + ",$pre="P"){
		$arr = explode(",",str_replace($pre,"",$a));
		$r = "";
		if($arr){
			foreach($arr as $v){
				if(trim($v)){
					$vl = ($path == 1)?self::lv2_path($datas,$v):$datas[$v];
					$r .= ($r == "")?$vl:$split.$vl;
				}
			}
		}
		if($len>0){
			$r = tUtil::substr($r,$len,1);
		}
		return $r;
	}
	//获取相关地区
	public static function likesql_area($v,$fieldname="area",$pre="A"){
		$_arr = !is_array($v)?explode(",",$v):(is_array($v)?$v:array());
		$tmpstr = "";
		if($_arr){
			$tmpstr .= " AND (";
			foreach($_arr as $k2=>$v2){
				if($v2 && $v2 % 1000000 == 0){
					$tmpstr .= ($tmpstr == " AND (")?"{$fieldname} LIKE '%{$pre}".floor($v2/1000000)."%'":" OR {$fieldname} LIKE '%{$pre}".floor($v2/1000000)."%'";
					continue;
				}elseif($v2 && $v2 % 10000 == 0){
					$tmpstr .= ($tmpstr == " AND (")?"{$fieldname} LIKE '%{$pre}".floor($v2/10000)."%'":" OR {$fieldname} LIKE '%{$pre}".floor($v2/10000)."%'";
					continue;
				}elseif($v2 && $v2 % 100 == 0){
					$tmpstr .= ($tmpstr == " AND (")?"{$fieldname} LIKE '%{$pre}".floor($v2/100)."%'":" OR {$fieldname} LIKE '%{$pre}".floor($v2/100)."%'";
					continue;
				}else{
					$tmpstr .= ($tmpstr == " AND (")?"{$fieldname} LIKE '%{$pre}".$v2."%'":" OR {$fieldname} LIKE '%{$pre}".$v2."%'";
					continue;
				}
			}
			$tmpstr .= ")";
		}
		return  $tmpstr;
	}
	//获取相关岗位
	public static function likesql_post($v,$fieldname="post",$pre="P"){
		$_arr = !is_array($v)?explode(",",$v):(is_array($v)?$v:array());
		$tmpstr = "";
		if($_arr){
			$tmpstr .= " AND (";
			foreach($_arr as $k2=>$v2){
				if($v2 && $v2 % 100 == 0){
					$tmpstr .= ($tmpstr == " AND (")?"{$fieldname} LIKE '%{$pre}".floor($v2/100)."%'":" OR {$fieldname} LIKE '%{$pre}".floor($v2/100)."%'";
					continue;
				}else{
					$tmpstr .= ($tmpstr == " AND (")?"{$fieldname} LIKE '%{$pre}".$v2."%'":" OR {$fieldname} LIKE '%{$pre}".$v2."%'";
					continue;
				}
			}
			$tmpstr .= ")";
		}
		return  $tmpstr;
	}
	//更新搜索关键词
	public static function update_user_searchkeyword($keyword="",$type=""){
		global $user_id,$user_type;
		if(empty($keyword) || empty($user_id) || empty($type) || intval(R("page"))>1)return false;
		$keyword = is_array($keyword)?$keyword:explode(',', str_replace(" ",",",$keyword));
		foreach($keyword as $k=>$v){
			$v = trim($v);
			$id = M('searchkeyword')->get_one("keyword='{$v}' AND type='{$type}'","id");
			if($id > 0){
				M('searchkeyword')->set_data(array("count"=>"count+1"));
				M('searchkeyword')->update("id=$id",array("count"));
			}else{
				$data["keyword"] = $v;
				$data["count"] = 1;
				$data["type"] = $type;
				M('searchkeyword')->set_data($data);
				M('searchkeyword')->add();
			}
		}
		return true;
	}
	//输出热门地区
	public static function hotarea($type = "ishot"){
		static $areas;
		$areas = empty($areas)?tCache::read("area_all_config"):$areas;
		$htmlstr = "";//"<a href='javascript:void(0);' _code='0'>取消</a>";
		foreach($areas as $k=>$v){
			if($v[$type] == 1){
				$htmlstr .= "<a style='".($v["color"]?"color:{$v['color']};":"").($v["isbold"]?"font-weight:700;":"")."' onclick='sel_miniarea(this)' _code='{$v['code']}' href='javascript:void(0)'>{$v['name']}</a>";
			}
		}
		$htmlstr .= "";
		return $htmlstr;
	}
	//关键词变色
	public static function keyword_replace($keyword,$str,$classname = "replacekeyword",$encode=true){
	  $funname = "str_replace";//"mb_ereg_replace";
	  $soure = $keyword;
	  $return = "<strong class=\"{$classname}\">$soure</strong>";
	  return $funname($soure,$return,$str);
	}
	//获取广告
	public static function get_ad($adpos=0,$type="",$len=1,$w=0,$h=0){
		global $timestamp;
		if($adpos == 0)return "";
		$ad = tCache::read("ad_".$adpos);
		if(empty($ad))return "";
		$resstr = "";
		$auto_cur = 0;
		switch($type){
			case "banner":
				$imgstr = "<ul class=\"slide-pic\">";
				$opstr = "<ul class=\"slide-li op\">";
				$txtstr = "<ul class=\"slide-li slide-txt\">";
				foreach($ad as $k=>$v){
					if($v['start_dateline']>$timestamp || $v['end_dateline']<$timestamp)continue;
					$imgstr .= "<li class=\"".($k==0?"cur":"")."\"><a href='{$v['linkurl']}' title='{$v['name']}' target=\"_blank\" style=\"background:{$v['bgcolor']} url(".U("static@/").$v['imgurl'].") center top no-repeat;\">&nbsp;&nbsp;</a></li>";
					$opstr .= "<li class=\"".($k==0?"cur":"")."\"></li>";
					$txtstr .= "<li class=\"".($k==0?"cur":"")."\"><a href='{$v['linkurl']}' title='{$v['name']}' target=\"_blank\">{$v['name']}</a></li>";
					$auto_cur ++;
					if($auto_cur>=$len)break;
				}
				$imgstr .= "</ul>";
				$opstr .= "</ul>";
				$txtstr .= "</ul>";
				return $imgstr.$opstr.$txtstr;
				break;
			case "a":
				$resstr = "";
				foreach($ad as $k=>$v){
					if($v['start_dateline']>$timestamp || $v['end_dateline']<$timestamp)continue;
					$resstr .= "<a href='{$v['linkurl']}' title='{$v['name']}' target=\"_blank\"><img src='".U().$v['imgurl']."' class='lazyload' alt='{$v['name']}' ".($w?"width='{$w}'":"")." ".($h?"height='{$h}'":"")." /></a>";
					$auto_cur ++;
					if($auto_cur>=$len)break;
				}
				$resstr .= "";
				break;
			default:
				$resstr = "<ul>";
				foreach($ad as $k=>$v){
					if($v['start_dateline']>$timestamp || $v['end_dateline']<$timestamp)continue;
					$resstr .= "<li title='{$v['name']}' desc='".tUtil::substr($v['content'],150,1)."'><a href='{$v['linkurl']}' title='{$v['name']}' target=\"_blank\"><img class='' src='".U().$v['imgurl']."' alt='{$v['name']}' ".($w?"width='{$w}'":"")." ".($h?"height='{$h}'":"")." /></a></li>";
					if($auto_cur>=$len)break;
				}
				$resstr .= "</ul>";
				
				break;
		}
		return $resstr;
	}
	//创建pagebar
	public static function pagebar($num, $perpage, $curpage, $mpurl='', $maxpages = 0, $page = 8){
		$shownum = $showkbd = false;
		$lang['prev'] = '&lt; 上一页';//'&lsaquo;&lsaquo;'; //上一页 <<
		$lang['next'] = '下一页 &gt;';//'&rsaquo;&rsaquo;'; //下一页 >>
		$multipage = '';
		$realpages = 1;//总页数
		if($num > $perpage){
			$offset = 5;
			$realpages = @ceil($num / $perpage);
			$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;
			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				$from = $curpage - $offset;
				$to = $curpage + $offset;//$from + $page - 1;
				if($from < 1) {
					$to = $curpage + 1 - $from;
					$from = 1;
					if($to - $from < $page) {
						$to = $page;
					}
					if($to > $pages){
						$to = $pages;
					}
				} elseif($to > $pages) {
					//$from = $pages - $page + 1;
					$to = $pages;
				}
			}
			$multipage = ($curpage > 1 ? '<a hidefocus="true" href="'.str_replace('_PG_',$curpage-1,$mpurl).'" class="prev">'.$lang['prev'].'</a>' : '');
			$multipage .= ($curpage - $offset > 1 && $pages > $page ? '<a hidefocus="true" href="'.str_replace('_PG_',1,$mpurl).'" class="first">1 ...</a>' : '');
			for($i = $from; $i <= $to; $i++) {
				$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :'<a hidefocus="true" href="'.str_replace('_PG_',$i,$mpurl).'">'.$i.'</a>';
			}
			//是否显示更多页
			//$multipage .= ($to < $pages ? '<a hidefocus="true" href="javascript:void(0);" class="last">... </a>' : '');
			//下一页
			$multipage .= ($curpage < $pages ? '<a hidefocus="true" href="'.str_replace('_PG_',$curpage + 1,$mpurl).'" class="next">'.$lang['next'].'</a>' : '');
			//最后归总
			$multipage = $multipage ? '<div class="pages">'.($shownum? '<em style="display:none;">&nbsp;'.$num.'&nbsp;</em>&nbsp;' : '').$multipage.'</div>' : '';
		}
		$maxpage = $realpages;
		return $multipage;
	}
	//替换分页 JS
    public static function pagebar_js($str,$pageurl,$funname,$parm=array()){
   	     $str = preg_replace("/href=\"".str_replace('/','\/',str_replace('?','\?',$pageurl))."&amp;page=(\d+)#{0,1}\"/","href=\"javascript:void(0)\" onclick=\"return $funname(\\1".($parm?(",'".implode("','",$parm)."'"):"").")\"", $str);
	     $str = str_replace("window.location='{$pageurl}&amp;page='+this.value", "$funname(this.value".($parm?(",'".implode("','",$parm)."'"):"").")", $str);
	     return $str;
    }
	//split_array
    public static function str2arr($str,$split1="[,]",$split2="[@]"){
    	if(strpos($str,$split2) === false && strpos($str,$split1) === false){
    		return $str;
    	}
    	$re = array();
    	$tmp = explode($split1,$str);
    	foreach($tmp as $v){
    		$tmp1 = explode($split2,$v);
    		$re[$tmp1[0]] = isset($tmp1[1])?$tmp1[1]:"";
    	}
    	return $re;
    }
    //split_array
    public static function arr2str($arr,$split1="[,]",$split2="[@]"){
    	$str = "";
    	$tmp = array();
    	foreach($arr as $k=>$v){
    		$k = str_replace($split1,"",str_replace($split2,"",$k));
    		$v = str_replace($split1,"",str_replace($split2,"",$v));
    		$tmp[] = "{$k}{$split2}{$v}";
    	}
    	return implode($split1,$tmp);
    }
    //编号转ID
    public static function no2id($no){
    	return intval(substr($no,3));
    }
    //ID转编号
    public static function id2no($id,$pre="WCR"){
    	return $pre.sprintf("%07d",$id);
    }
    //二维数组排序
    public static function array_multisort($data,$f = array(),$bykey = true){
    	$return = $sorts = array();
    	$f = is_string($f)?explode(",",$f):$f;
    	foreach($data as $k => $v){
    		foreach($f as $k2 => $v2){
    			$sorts[$k2][] = isset($v[$v2])?$v[$v2]:0;
    		}
    		$sorts["__key"][] = $k;
    	}
    	array_multisort($sorts[0],$sorts["__key"],$data);
    	if($bykey === true){
	        foreach($sorts["__key"] as $k => $key){
	        	$return[$key] = $data[$k];
	        }
    	}else{
    		$return = $data;
    	}
        return $return;
    }
    
    //输出头像
    public static function avatar($uid,$path="avatar",$size=""){
    	$avatar = self::get_avatar($uid,$path,$size);
    	if(file_exists(ROOT_PATH."www/static/".$avatar)) {
    		$avatar_url = $avatar;
    	}else{
    		$avatar_url = "public/images/no_{$path}.gif";
    	}
    	return U("static@/{$avatar_url}");
    }
    //输出简历头像
    public static function resume_avatar($idcard,$uid){
    	$avatar = self::get_idcard_avatar($idcard);
    	if(!file_exists(ROOT_PATH."www/static/".$avatar)) {
    		$avatar = self::get_avatar($uid);
    	}
    	if(file_exists(ROOT_PATH."www/static/".$avatar)) {
    		$avatar_url = $avatar;
    	}else{
    		$avatar_url = "images/no_avatar.gif";
    	}    	
    	return U("static@/public/{$avatar_url}");
    }
    
    //获取头像
    public static function get_avatar($uid,$path="avatar",$size="") {
    	$filepath = "attach/{$path}/".self::get_avatar_path($uid).'/'.self::get_avatar_name($uid,$path,$size).".gif";
    	return $filepath;
    }
    //获取头像文件名
    public static function get_avatar_name($uid,$path="avatar",$size="") {
    	return substr($uid,-2)."_{$path}{$size}";
    }
    //获取头像目录
    public static function get_avatar_path($uid) {
    	$uid = abs(intval($uid));
    	$uid = sprintf("%09d", $uid);
    	$dir1 = substr($uid, 0, 3);
    	$dir2 = substr($uid, 3, 2);
    	$dir3 = substr($uid, 5, 2);
    	return $dir1.'/'.$dir2.'/'.$dir3;
    }
    
    //获取身份证头像
    public static function get_idcard_avatar($idcard) {
    	$filepath = "attach/idcard/".self::get_idcard_avatar_path($idcard).'/'.md5($idcard).".gif";
    	return $filepath;
    }
    //获取身份证目录
    public static function get_idcard_avatar_path($idcard) {
    	$dir1 = substr($idcard, 0, 6);
    	$dir2 = substr($idcard, 6, 2);
    	$dir3 = substr($idcard, 8, 2);
    	return $dir1.'/'.$dir2.'/'.$dir3;
    }
    
    //获取所有城市
    public static function get_city(){
    	C("category","city")->get(-9);
    	C("category","city")->fetch(-9);
    	$citylist = C("category","city")->get_level(0);
    	return $citylist;
    }
    //根据城市获取商圈
    public static function get_city_quan($city){
    	C("category","city")->get(-9);
    	C("category","city")->fetch(-9);
    	$quanlist = C("category","city")->get_level($city);
    	return $quanlist;
    }
    
    //根据uri获取网站一些配置
    public static function get_conf($type,$code,$level=2,$field="name"){
    	switch($type){
    		case "trade_cate":
    			C("category","trade_cate")->get(-9);
    			C("category","trade_cate")->fetch(-9);
    			$data = C("category","trade_cate")->get(0);
    			return isset($data[$code])?$data[$code][$field]:"";
    			break;
    		case "job_cate":
    			C("category","job_cate")->get(-9);
    			C("category","job_cate")->fetch(-9);
    			$data = C("category","job_cate")->get(0);
    			if(isset($data[$code])){
    				$re = $data[$code][$field];
    				if($level == 2){
    					$pid = $data[$code]['pid'];
    					if(isset($data[$pid])){
    						$re = ($data[$pid][$field]." ").$re;
    					}
    				}
    				return $re;
    			}else{
    				return "";
    			}
    			break;
    		case "city":
    			C("category","city")->get(-9);
    			C("category","city")->fetch(-9);
    			$data = C("category","city")->get(0);
    			if(isset($data[$code])){
    				$re = $data[$code][$field];
    				if($level == 2){
    					$pid = $data[$code]['pid'];
    					if(isset($data[$pid])){
    						$re = ($data[$pid][$field]." ").$re;
    					}
    				}
    				return $re;
    			}else{
    				return "";
    			}
    			break;
    	}
    }
    //获取多个
    public static function get_conf_more($type,$v,$split=",",$level = 1,$field="name"){
    	$rstr = "";
    	if($v){
    		$tmp = array();
    		$v_arr = explode(",",$v);
    		if($v_arr){
    			foreach($v_arr as $v2){
    				$tmp[] = self::get_conf($type,$v2,$level,$field);
    			}
    		}
    		$rstr = implode($split,$tmp);
    	}
    	return $rstr;
    }
    //获取多个标签
    public static function get_tag_more($type,$v,$split=","){
    	$tmp = App::get_conf("data_config.$type");
    	$rstr = "";
    	if($v){
    		$tmp1 = array();
    		$v_arr = explode(",",$v);
    		if($v_arr){
    			foreach($v_arr as $v2){
    				if(isset($tmp[$v2])){
    					$tmp1[] = $tmp[$v2];
    				}
    			}
    		}
    		$rstr = implode($split,$tmp1);
    	}
    	return $rstr;
    }
    //创建搜索URL
    public static function build_url($url,$condi = array(),$newcondi = array()){
    	if(!empty($newcondi)){
    		foreach($newcondi as $k=>$v){
    			$condi[$k] = $v;
    		}
    	}
    	$uri = "";
    	foreach($condi as $key=>$v){
    		$uri .= "/{$key}/".($v?urlencode($v):0);
    	}
    	return U($url.$uri);
    }
    //打印码
    public static function print_code($code){
    	return substr($code,0,4)." ".substr($code,4,4)." ".substr($code,8,4);
    }
    //--------------------------------
    public static function get_cur_onlineqq($ctime){
    	//根据当前时间戳获取时间段
    	$timeduan = App::$data['data_config']['timeduan'];
    	$cdate = tTime::get_datetime("Y-m-d",$ctime);
    	$time_duan_key = 0;
    	foreach($timeduan as $key=>$v){
    		if($v){
    			list($start,$end) = explode("-",$v);
    			$start_dateline = tTime::get_time("{$cdate} {$start}");
    			$end_dateline = tTime::get_time("{$cdate} {$end}");
    			 
    			if($end_dateline > $start_dateline){
    				if($ctime>= $start_dateline && $ctime <= $end_dateline){
    					$time_duan_key = $key;
    				}
    			}else{
    				$end_24_dateline = tTime::get_time("{$cdate} 23:59:59");
    				if(($ctime>= $start_dateline && $ctime <= $end_24_dateline) || ($ctime >= strtotime($cdate) && $ctime <=$end_dateline)){
    					$time_duan_key = $key;
    				}
    			}
    		}
    	}
    	//查找方案
    	$weekday = date("w",$ctime);
    	$weekday = $weekday == 0?7:$weekday;
    	$plan_id = 0;
    	$online_qq = array();
    	if($timeduan){
    		$plan_id = M("onlineqq_plan")->get_one("timeduan={$time_duan_key} AND FIND_IN_SET($weekday,`weekday`)","id","id DESC");
    	}
    	if(empty($plan_id)){
    		$plan_id = M("onlineqq_plan")->get_one("is_default=1","id");
    	}
    
    	//查找QQ
    	$tmp = M("onlineqq_planitem")->query("plan_id=$plan_id","*","qqtype ASC");
    	foreach($tmp as $k=>$v){
    		$v['qqs'] = array();
    		$tmp2 = explode("@@",$v['content']);
    		unset($v['content'],$v['qqids'],$v['qqids'],$v['id']);
    		if($tmp2){
    			foreach($tmp2 as $v2){
    				$tmp3 = explode("/",$v2);
    				$tmp4 = array();
    				$tmp4['qq'] = isset($tmp3[0])?trim($tmp3[0]):"";
    				$tmp4['name'] = isset($tmp3[1])?trim($tmp3[1]):"";
    				$tmp4['desc'] = isset($tmp3[2])?trim($tmp3[2]):"";
    				$v['qqs'][] = $tmp4;
    			}
    		}
    		$v['name'] = App::$data['data_config']['qqtype'][$v['qqtype']];
    		$online_qq[$v['qqtype']] = $v;
    	}
    	return $online_qq;
    }
	//1.1.1.1-1.1.1.2 IP 转CIDR格式
	public static function ip2cidr($ip_start, $ip_end)
	{
		if (long2ip(ip2long($ip_start)) != $ip_start or long2ip(ip2long($ip_end)) != $ip_end) return NULL;
		$ipl_start = bindec(decbin(ip2long($ip_start)));
		$ipl_end = bindec(decbin(ip2long($ip_end)));
		if ($ipl_start > 0 && $ipl_end < 0) $delta = ($ipl_end + 4294967296) - $ipl_start;
		else $delta = $ipl_end - $ipl_start;
		$netmask = str_pad(decbin($delta), 32, "0", STR_PAD_LEFT);
		if (bindec(decbin(ip2long($ip_start))) == 0 && substr_count($netmask, "1") == 32) return "0.0.0.0/0";
		//if ($delta < 0 or ($delta > 0 && $delta % 2 == 0)) return NULL;
		for ($mask = 0; $mask < 32; $mask++) if ($netmask[$mask] == 1) break;
		//if (substr_count($netmask, "0") != $mask) return NULL;
		return "$ip_start/$mask";
	}
	//CIDR格式转1.1.1.1-1.1.1.2
	public static function cidr2ip($cidr, $isint = true)
	{
		$range = array();
		$cidr = explode('/', $cidr);
		//$range[0] = $cidr[0];//long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
		//$range[1] = long2ip((ip2long($cidr[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
		$range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
		$range[1] = long2ip((ip2long($cidr[0])) + pow(2, (32 - (int)$cidr[1])) - 1);

		if ($isint == true) {
			$range[0] = bindec(decbin(ip2long($range[0])));
			$range[1] = bindec(decbin(ip2long($range[1])));
		}
		return $range;
	}
	//图片转base64编码
	public static function base64EncodeImage ($image_file) {
		$base64_image = '';
		$image_info = getimagesize($image_file);
		$image_data = fread(fopen($image_file, 'r'), filesize($image_file));
		$base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
		return base64_encode($image_data);
	}
	//php 获取多维数组中的某一列的值的集合
	public static function array_column($input, $columnKey, $indexKey=null){
		$columnKeyIsNumber      = (is_numeric($columnKey)) ? true : false;
		$indexKeyIsNull         = (is_null($indexKey)) ? true : false;
		$indexKeyIsNumber       = (is_numeric($indexKey)) ? true : false;
		$result                 = array();
		foreach((array)$input as $key=>$row){
			if($columnKeyIsNumber){
				$tmp            = array_slice($row, $columnKey, 1);
				$tmp            = (is_array($tmp) && !empty($tmp)) ? current($tmp) : null;
			}else{
				$tmp            = isset($row[$columnKey]) ? $row[$columnKey] : null;
			}
			if(!$indexKeyIsNull){
				if($indexKeyIsNumber){
					$key        = array_slice($row, $indexKey, 1);
					$key        = (is_array($key) && !empty($key)) ? current($key) : null;
					$key        = is_null($key) ? 0 : $key;
				}else{
					$key        = isset($row[$indexKey]) ? $row[$indexKey] : 0;
				}
			}
			$result[$key]       = $tmp;
		}
		return $result;
	}
}
?>