<?php
class tUtil{
	//分页条函数
	public static function pagebar($num, $perpage, $curpage, $mpurl, $maxpages = 0, $page = 7, $autogoto = TRUE, $simple = FALSE){
		$ajaxtarget = false;
		$shownum = $showkbd = TRUE;
		$lang['prev'] = '&lsaquo;&nbsp;上一页';//'&lsaquo;&lsaquo;'; //上一页 <<
		$lang['next'] = '下一页&nbsp;&rsaquo;';//'&rsaquo;&rsaquo;'; //下一页 >>
		$multipage = '';
		$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
		$realpages = 1;
		if($num > $perpage){
			$offset = 5;
			$realpages = @ceil($num / $perpage);
			$pages = ($maxpages && $maxpages < $realpages) ? $maxpages : $realpages;
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
					if($to > $pages) {
						//$from = $pages - $page + 1;
						$to = $pages;
					}
				} elseif($to > $pages) {
					//$from = $pages - $page + 1;
					$to = $pages;
				}
			}
			
			$multipage = ($curpage > 1 && !$simple ? '<a hidefocus="true" href="'.$mpurl.'page='.($curpage - 1).'" class="prev"'.$ajaxtarget.'>'.$lang['prev'].'</a>' : '');
			$multipage .= ($curpage - $offset > 1 && $pages > $page ? '<a hidefocus="true" href="'.$mpurl.'page=1" class="first"'.$ajaxtarget.'>1 ...</a>' : '');
			
			for($i = $from; $i <= $to; $i++) {
				$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
					'<a hidefocus="true" href="'.$mpurl.'page='.$i.($ajaxtarget && $i == $pages && $autogoto ? '#' : '').'"'.$ajaxtarget.'>'.$i.'</a>';
			}
			$multipage .= ($to < $pages ? '<a hidefocus="true" href="'.$mpurl.'page='.$pages.'" class="last"'.$ajaxtarget.'>... '.$realpages.'</a>' : '').
				($curpage < $pages && !$simple ? '<a hidefocus="true" href="'.$mpurl.'page='.($curpage + 1).'" class="next"'.$ajaxtarget.'>'.$lang['next'].'</a>' : '').
				($showkbd && !$simple && $pages > $page && !$ajaxtarget ? '<kbd>&nbsp;跳转至<input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.'page=\'+this.value; return false;}" /> 页</kbd>' : '');
			$multipage = $multipage ? '<div class="pages">'.($shownum && !$simple ? '<em>共&nbsp;'.$num.'&nbsp;条记录</em>&nbsp;' : '')."<i>共{$pages}页</i>".$multipage.'</div>' : '';
		}
		$maxpage = $realpages;
		return $multipage;
   }
    //字符串截取
    public static function substr($str, $length = 0, $append = true,$strart_pos = 0){
		    $str = trim($str);
		    $strlength = strlen($str);
		    if ($length == 0 || $length >= $strlength){
		        return $str;
		    }elseif ($length < 0) {
		        $length = $strlength + $length;
		        if ($length < 0){
		            $length = $strlength;
		        }
		    }
		    if (function_exists('mb_substr')){
		        $newstr = mb_substr($str, $strart_pos, $length, App::$charset);
		    } elseif (function_exists('iconv_substr')){
		        $newstr = iconv_substr($str, $strart_pos, $length, App::$charset);
		    }else {
		        $newstr = substr($str, $strart_pos, $length);
		    }
		    if ($append && $str != $newstr){
		        $newstr .= '...';
		    }
		    return $newstr;
  }
    //字符串中间截取
    public static function msubstr($string,$len=3, $rep = '*****'){
    	$mid = floor(strlen($string)/2);
    	$max = $mid>$len?$len:($mid-1);
		return substr($string, 0, $max) . $rep . substr($string, -$max); 
    }
    //是否为UTF-8
    public static function is_utf8($str){
    	if(preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$str) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$str) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$str) == true){ 
    		return true; 
		}else{ 
			return false; 
		}
	}
	//iconv
	public static function iconv($string,$soure="utf-8",$target="gbk"){
		$soure = strtoupper($soure);
		$target = strtoupper($target);
		if($soure != $target){
			if (function_exists('mb_convert_encoding')) {
		        $conv = @mb_convert_encoding($string, $target, $soure);
		        if ($conv) return $conv;
		    }
		    if (function_exists('iconv')) {
		        $conv = @iconv($soure, $target, $string);
		        if ($conv) return $conv;
		    }
		    if (function_exists('libiconv')) {
		        $conv = @libiconv($soure,$target, $string);
		        if ($conv) return $conv;
		    }
		}
		return $string;
	}
	//ICONV STR
	public static function iconv_s($string,$soure="utf-8",$target="gbk"){
		if(is_array($string)){
			$result_str = array();
			foreach($string as $key => $val){
				$result_str[$key] = self::iconv_s($val,$soure,$target);
			}
			return $result_str;
		}else{
			return self::iconv($string,$soure,$target);
		}
	}
	//获取字符串长度
	public static function strlen($str){
		$byte   = 0;
		$amount = 0;
		$str    = trim($str);
		//获取字符串总字节数
		$strlength = strlen($str);
		//检测是否为utf8编码
		$is_utf8=self::is_utf8($str);
		//utf8编码
		if($is_utf8 == true){
			while($byte < $strlength){
				if(ord($str{$byte}) >= 224){
					$byte += 3;
					$amount++;
				}else if(ord($str{$byte}) >= 192){
					$byte += 2;
					$amount++;
				}else{
					$byte += 1;
					$amount++;
				}
			}
		}else{//非utf8编码
			while($byte < $strlength){
				if(ord($str{$byte}) > 160){
					$byte += 2;
					$amount++;
				}else{
					$byte++;
					$amount++;
				}
			}
		}
		return $amount;
	}
	//字符限制
	public static function limit_len($str,$length){
		if($length !== false){
			$count = self::strlen($str);
			if($count > $length){
				return '';
			}else{
				return $str;
			}
		}
		return $str;
	}
	//过滤
	public static function filter($str,$type = 'string',$limit_len = false){
		if(is_array($str)){
			$result_str = array();
			foreach($str as $key => $val){
				$result_str[$key] = self::filter($val, $type, $limit_len);
			}
			return $result_str;
		}else{
			switch($type){
				case "int":
					return intval($str);
				break;
				case "float":
					return floatval($str);
				break;
				case "text":
					return self::text($str,$limit_len);
				break;
				case "bool":
					return (bool)$str;
				break;
				default:
					return self::string($str,$limit_len);
				break;
			}
		}
	}
	//字符过滤
	public static function string($str,$limit_len = false){
		$str = self::limit_len($str,$limit_len);
		$str = trim($str);
		$except = array('　');
		$str = str_replace($except,'',htmlspecialchars($str,ENT_QUOTES));
		return self::addslash($str);
	}
	//对字符串进行普通的过滤处理
	public static function text($str,$limit_len = false){
		$str = self::limit_len($str,$limit_len);
		$str = trim($str);
		require_once(dirname(__FILE__)."/htmlpurifier-4.3.0/HTMLPurifier.standalone.php");
		$cache_dir=ROOT_PATH."/cache/htmlpurifier/";
		if(!file_exists($cache_dir)){
			tFile::mkdir($cache_dir);
		}
		$config = HTMLPurifier_Config::createDefault();
		
		//配置 允许flash
		$config->set('HTML.SafeEmbed',true);
		$config->set('HTML.SafeObject',true);
		$config->set('Output.FlashCompat',true);
		
		//配置 缓存目录
		$config->set('Cache.SerializerPath',$cache_dir); //设置cache目录

		//允许<a>的target属性
		$def = $config->getHTMLDefinition(true);
		$def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');

		$purifier = new HTMLPurifier($config);//过略掉所有<script>，<i?frame>标签的on事件,css的js-expression、import等js行为，a的js-href
		$str = $purifier->purify($str);

		return self::addslash($str);
	}
	//过滤html标签
	public static function clear_htmltag($content){
		$content = trim($content);
		 if (strlen($content) <= 0) {
		  	return $content;
		 }
		 /*
		   "'<[\/\!]*?[^<>]*?>'si",          // 去掉 HTML 标记
		   "'&(quot|#34);'i",                // 替换 HTML 实体
		      "'&(amp|#38);'i",
		      "'&(lt|#60);'i",
		      "'&(gt|#62);'i",
		      "'&(nbsp|#160);'i"
		 */
		 $search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
		                  "'([\r\n])[\s]+'",                // 去掉空白字符
		                  "'<[\/\!]*?[^<>]*?>'si",
		                  "'&nbsp;'si",
		                  );                    // 作为 PHP 代码运行 
		 $replace = array ("",
		                   "",
		                   "",
		                   ""); 
		 return @preg_replace ($search, $replace, htmlspecialchars_decode($content));
	}
	//增加转义斜线
    public static function addslash($str){
		if(is_array($str)){
			$result_str = array();
			foreach($str as $key => $val){
				$result_str[$key] = self::addslash($val);
			}
			return $result_str;
		}else{
			if(R('in_ajax','int') == 1 && self::is_utf8($str) && App::$charset != "utf-8"){
				$str = self::iconv($str);
			}
			return addslashes($str);
		}
	}
	//去除增加转义斜线
	public static function stripslash($str){
		if(is_array($str)){
			$result_str = array();
			foreach($str as $key => $val){
				$result_str[$key] = self::stripslash($val);
			}
			return $result_str;
		}else{
			return stripslashes($str);
		}
	}
	//表单提交的formhash
	public static function hash(){
		return substr(md5(tClient::get_ip()."T-HinkHu"), 8, 8);
	}
	//表单提交检查
	public static function check_hash(){
		if(($_SERVER['REQUEST_METHOD'] == 'POST') && tGpc::get('hash') == self::hash() && empty($_SERVER['HTTP_X_FLASH_VERSION'])){
			   return true;
		}
	    return false;
	}
	//检查表单是否重复提交
	public static function check_formsubmit(){
		if(R('timestamp',"int") != 0){
	    	if(tSafe::get('timestamp') == R('timestamp',"int")){
	    		return 1;
	    	}else{
	    		tSafe::set('timestamp',R('timestamp',"int"));
	    	}
	    }
	    return 0;
	}
	//创建编辑器
	public static function create_editor($idstr,$edname = "kindeditor"){
		$ks = $htmls = "";
		$vars = "";
		if(is_string($idstr)){
			$idarr = explode(",",$idstr);
		}else{
			$idarr = $idstr;
		}
		unset($idstr);
		switch($edname){
			default:
				$UP_LOAD_URL = U("/interface_editor/upload_kindeditor");
				$FILE_MANAGER_URL = U("/interface_editor/file_manager_kindeditor");
				if(!empty($idarr)){
					foreach($idarr as $v){
						$vars .= $vars == ""?"KE_$v":",KE_$v";
						$ks .= "KE_$v = KindEditor.create('textarea[name=\"{$v}\"]',{
											uploadJson : '{$UP_LOAD_URL}',
											fileManagerJson : '{$FILE_MANAGER_URL}',
											allowFileManager : true
											});";
					}
				}
				$htmls = '<script language="javascript" src="'.U('static@/javascript/kindeditor-4.1.7/kindeditor-all-min.js').'" type="text/javascript" ></script>';
				$htmls .= '<script language="javascript">
				                var '.$vars.';
								$(function(){
									KindEditor.basePath = \''.U("static@/javascript/kindeditor-4.1.7/").'\';
									'.$ks.'
								})
							</script>';
				break;
		}
		return $htmls;
	}
	//数组反序列化
	public static function unserialize($string=""){
		return $string?unserialize($string):array();
	}
	//根据数组创建URL
	public static function uri($baseurl="",$condi=array(),$new_condi=array()){
		//用新的值去替换老的值
		$tmp = array();
		foreach($condi as $k=>$v){
			if(isset($new_condi[$k])){
				if($new_condi[$k]){
					$v = $new_condi[$k];
				}else{
					continue;
				}
			}
			if($v){
				$tmp[] = "{$k}=".urlencode($v);
			}
		}
		$baseurl=strpos($baseurl,"?") === false?($baseurl."?"):$baseurl;
		return $baseurl.((substr($baseurl,-1) === "?")?"":"&").implode("&",$tmp);
	}
	//是否是Ajax请求
	public static function is_ajax(){
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	}
	//返回一个子域名的顶级域名
	public static function get_domain($str){
		$str_a = explode(".",$str);
		//com)|(net)|(org)|(gov.cn)|(info)|(me)|(cc)|(com.cn)|(net.cn)|(org.cn)|(name)|(biz)|(tv)|(cn)|(la)|(ml)|(asia)|(tel)|(co)|(cm)|(in)|(bz)|(vc)|(ag)|(mn)|(sc)|(us)|(ws)|(travel)|(tm)|(io)|(ac)|(sh)|(tw)|(mobi)|(hk)|(pw)|(com.hk)|(com.tw)|(so
		$key_a = array("com","net","org","gov.cn","gov","info","me","cc","com.cn","org.cn","cn","name","biz","tv","la","ml","asia","tel","co","cm","in","bz","vc","ag","mn","sc","us","ws","travel","tm","io","ac","sh","tw","mobi","hk","pw","so","com.hk","com.tw");
		$return = "";
		$str_a = array_reverse($str_a);
		if($str_a){
			foreach($str_a as $v){
				$return = ".{$v}".$return;
				if(in_array(substr($return,1),$key_a)){
					continue;
				}else{
					break;
				}
			}
		}
		return $return?substr($return,1):"";
	}
	//创建trade_no
	public static function create_no($pre=""){
		return $pre.substr(date('Y-md-'),3).substr(implode(NULL, array_map('ord', str_split(substr(uniqid(mt_rand(), true), 7, 13), 1))), 0, 8);
	}
	//创建trade_no 纯数字
	public static function create_numno($type = 1){
		global $timestamp;
		if($type == 1){
			return substr(date('YmdHis'),0).substr(implode(NULL, array_map('ord', str_split(substr(uniqid(mt_rand(), true), 7, 13), 1))), 0, 8);
		}else{
			return $timestamp.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(mt_rand(), true), 7, 13), 1))), 0, 8);
		}

	}
	//创建唯一码
	public static function create_code(){
		return date('md').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(mt_rand(), true), 7, 13), 1))), 0, 8);
	}
	//将数字转字母
	public static function numstr($in, $to_num = false, $pad_up = 6, $passKey = "THINKHU!"){
		$index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if($passKey !== null){
			// Although this function's purpose is to just make the
			// ID short - and not so much secure,
			// with this patch by Simon Franz (http://blog.snaky.org/)
			// you can optionally supply a password to make it harder
			// to calculate the corresponding numeric ID
			for ($n = 0; $n<strlen($index); $n++) {
				$i[] = substr( $index,$n ,1);
			}		
			$passhash = hash('sha256',$passKey);
			$passhash = (strlen($passhash) < strlen($index))
			? hash('sha512',$passKey)
			: $passhash;		
			for ($n=0; $n < strlen($index); $n++) {
				$p[] =  substr($passhash, $n ,1);
			}		
			array_multisort($p,  SORT_DESC, $i);
			$index = implode($i);
		}		
		$base  = strlen($index);		
		if ($to_num) {
			// Digital number  <<--  alphabet letter code
			$in  = strrev($in);
			$out = 0;
			$len = strlen($in) - 1;
			for ($t = 0; $t <= $len; $t++) {
				$bcpow = bcpow($base, $len - $t);
				$out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
			}		
			if (is_numeric($pad_up)) {
				$pad_up--;
				if ($pad_up > 0) {
					$out -= pow($base, $pad_up);
				}
			}
			$out = sprintf('%F', $out);
			$out = substr($out, 0, strpos($out, '.'));
		}else{
			// Digital number  -->>  alphabet letter code
			if (is_numeric($pad_up)) {
				$pad_up--;
				if ($pad_up > 0) {
					$in += pow($base, $pad_up);
				}
			}		
			$out = "";
			for ($t = floor(log($in, $base)); $t >= 0; $t--) {
				$bcp = bcpow($base, $t);
				$a   = floor($in / $bcp) % $base;
				$out = $out . substr($index, $a, 1);
				$in  = $in - ($a * $bcp);
			}
			$out = strrev($out); // reverse
		}		
		return $out;
	}
	//返回汉字拼音第一个字母
	public static function pinyin($str){
		$asc=ord(substr($str,0,1));
	    if ($asc<160){ //非中文
	        if ($asc>=48 && $asc<=57){
	            return '1';  //数字
	        }elseif ($asc>=65 && $asc<=90){
	            return chr($asc);   // A--Z
	        }elseif ($asc>=97 && $asc<=122){
	            return chr($asc-32); // a--z
	        }else{
	            return '~'; //其他
	        }
	    }else{   //中文
	        $asc=$asc*1000+ord(substr($str,1,1));
	        //获取拼音首字母A--Z
	        if ($asc>=176161 && $asc<176197){
	            return 'a';
	        }elseif ($asc>=176197 && $asc<178193){
	            return 'b';
	        }elseif ($asc>=178193 && $asc<180238){
	            return 'c';
	        }elseif ($asc>=180238 && $asc<182234){
	            return 'd';
	        }elseif ($asc>=182234 && $asc<183162){
	            return 'e';
	        }elseif ($asc>=183162 && $asc<184193){
	            return 'f';
	        }elseif ($asc>=184193 && $asc<185254){
	            return 'g';
	        }elseif ($asc>=185254 && $asc<187247){
	            return 'h';
	        }elseif ($asc>=187247 && $asc<191166){
	            return 'j';
	        }elseif ($asc>=191166 && $asc<192172){
	            return 'k';
	        }elseif ($asc>=192172 && $asc<194232){
	            return 'l';
	        }elseif ($asc>=194232 && $asc<196195){
	            return 'm';
	        }elseif ($asc>=196195 && $asc<197182){
	            return 'n';
	        }elseif ($asc>=197182 && $asc<197190){
	            return 'o';
	        }elseif ($asc>=197190 && $asc<198218){
	            return 'p';
	        }elseif ($asc>=198218 && $asc<200187){
	            return 'q';
	        }elseif ($asc>=200187 && $asc<200246){
	            return 'r';
	        }elseif ($asc>=200246 && $asc<203250){
	            return 's';
	        }elseif ($asc>=203250 && $asc<205218){
	            return 't';
	        }elseif ($asc>=205218 && $asc<206244){
	            return 'w';
	        }elseif ($asc>=206244 && $asc<209185){
	            return 'x';
	        }elseif ($asc>=209185 && $asc<212209){
	            return 'y';
	        }elseif ($asc>=212209){
	            return 'z';
	        }else{
	            return '~';
	        }
	    }
	}
	//返回随机字符串
	public static function random_str($length = 20){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = '';
		for ( $i = 0; $i < $length; $i++ ){
			$str .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		return $str;
	}
	//返回当前URI
	public static function get_uri_path(){
		$the_uri = explode("?",tUrl::get_uri());
		if(strrpos($the_uri[0],"/") === 0){
			return $the_uri[0]."/"."index";
		}else{
			return $the_uri[0];
		}
	}
	//创建JS执行
    public static function js($uri="",$entype = "en",$ext_uri=""){
    	global $timestamp;
    	$encrypt_key= "d2352d339e245e44c684da4bb94cdc5b";
    	$distime = 2*60*60; //2小时
    	if($entype === "en"){
    		return U($uri)."?dateline={$timestamp}&sign=".md5($encrypt_key.$uri.$timestamp.$encrypt_key).($ext_uri?"&{$ext_uri}":"");
    	}elseif($entype === "de"){
    		$dateline = R("dateline","int");
    		$sign     = R("sign","string");
    		if(md5($encrypt_key.$uri.$dateline.$encrypt_key) === $sign){
    			if(($dateline + $distime)>=$timestamp){
    				return 1;
    			}else{
    				return -1;
    			}
    		}else{
    			return 0;
    		}
    	}
    }
}