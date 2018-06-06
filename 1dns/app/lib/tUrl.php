<?php
/**
 * @class Url
 * @brief URL处理类
 * @note
 */
class tUrl{
	const URL_NATIVE		= 1; //原生的Url形式,指从index.php，比如index.php?controller=blog&action=read&id=100
	const URL_PATHINFO   	= 2; //pathinfo格式的Url,指的是：/blog/read/id/100
	const URL_DIY		= 3; //经过urlRoute后的Url,指的是:/blog-100.html
	const CONTROLLER_NAME	= 'inc';
	const ACTION_NAME	= 'act';
	const MODULE_NAME	= 'module';
	const ANCHOR = "/#&"; //urlArray中表示锚点的索引
	const MARKKEY = "?";// /site/abc/?callback=/site/login callback=/site/login部分在UrlArray里的key
	private static $url_route = array(); //路由规则的缓存
    //获取当前controller、action、module的信息
	public static function get_info($key){
		$arr = array(
			'controller'=>self::CONTROLLER_NAME,
			'action'=>self::ACTION_NAME,
			'module'=>self::MODULE_NAME
		);
		if(isset($arr[$key])){
			return tGpc::get($arr[$key]);
		}
		return null;
	}

	/**
	 * @brief 
	 * @param string $url 想转换的url
	 * @param int $from tUrl::URL_NATIVE或者.....
	 * @param int $to tUrl::URL_PATHINFO或者.....
	 * @return string 如果转换失败则返回false
	 */
	public static function convert($url,$from,$to){
		if($from == $to){
			return $url;
		}
		$url_array = "";
		$fun_re = false;
		switch($from){
			case self::URL_NATIVE :
				$temp_url = parse_url($url);
				$url_array = self::querystring2array($temp_url);
				break;
			case self::URL_PATHINFO :
				$url_array = self::pathinfo2array($url);
				break;
			case self::URL_DIY :
				$url_array = self::diy2array($url);
				break;
			default:
				return $fun_re;
				break;
		}
		switch($to){
			case self::URL_NATIVE :
				$fun_re = self::urlarray2native($url_array);
				break;
			case self::URL_PATHINFO :
				$fun_re = self::urlarray2pathinfo($url_array);
				break;
			case self::URL_DIY:
				$fun_re = self::urlarray2diy($url_array);
				break;
		}
		return $fun_re;
	}
	/**
	 * @brief 将controller=blog&action=read&id=100类的query转成数组的形式
	 * @param string $url
	 * @return array
	 */
	public static function querystring2array($url){
		if(!is_array($url)){
			$url = parse_url($url);
		}
		$query = isset($url['query'])?explode("&",$url['query']):array();
		$re = array();
		foreach($query as $value){
			$tmp = explode("=",$value);
			if( count($tmp) == 2 ){
				$re[$tmp[0]] = $tmp[1];
			}
		}
		$re = self::sort_urlarray($re);
		isset($url['fragment']) && ($re[self::ANCHOR] = $url['fragment'] );
		return $re;
	}

	/**
	 * @brief 将/blog/read/id/100形式的url转成数组的形式
	 * @param string $url
	 * @return array
	 */
	public static function pathinfo2array($url){
		//blog/read/id/100
		//blog/read/id/100?comment=true#abcde
		$data = array();
		preg_match("!^(.*?)?(\\?[^#]*?)?(#.*)?$!",$url,$data);
		$re = array();
		if( isset($data[1]) && trim($data[1],"/ ")){
			$string = explode("/", trim($data[1],"/ ") );
			$key = null;
			$i = 1;
			//前两个是ctrl和action，后面的是参数名和值
			foreach($string as $value){
				if($i <= 2  ){
					$tmp_key = ($i==1) ? self::CONTROLLER_NAME : self::ACTION_NAME;
					$re[$tmp_key] = $value;
					$i ++ ;
					continue;
				}
				if($key === null){
					$key = $value;
					$re[$key]="";
				}else{
					$re[$key] = $value;
					$key = null;
				}
			}
		}
		if( isset($data[2]) || isset($data[3]) ){
			$re[ self::MARKKEY] = ltrim($data[2],"?");
		}

		if(isset($data[3])){
			$re[ self::ANCHOR] = ltrim($data[3],"#");
		}
		$re = self::sort_urlarray($re);
		return $re;
	}

	/**
	 * @brief 将用户请求的url进行路由转换，得到urlArray
	 * @param string  $url
	 * @return array
	 */
	public static function diy2array($url){
		return self::decode_routeurl($url);
	}

	/**
	 * @brief 对Url数组里的数据进行排序
	 * ctrl和action最靠前，其余的按key排序
	 * @param array $re
	 * @access private
	 */
	private static function sort_urlarray($re){
		$fun_re=array();
		isset( $re[self::CONTROLLER_NAME] ) && ($fun_re[self::CONTROLLER_NAME]=$re[self::CONTROLLER_NAME]);
		isset( $re[self::ACTION_NAME ] ) && ($fun_re[self::ACTION_NAME]=$re[self::ACTION_NAME]);
		unset($re[self::CONTROLLER_NAME],$re[self::ACTION_NAME]);
		ksort($re);
		$fun_re = array_merge($fun_re,$re);
		return $fun_re;
	}
	/**
	 * @brief 将urlArray用pathinfo的形式表示出来
	 * @access private
	 */
	private static function urlarray2pathinfo($arr){
		$re = "";
		$ctrl	= isset($arr[self::CONTROLLER_NAME])   ? $arr[self::CONTROLLER_NAME]   : '';
		$action	= isset($arr[self::ACTION_NAME ]) ? $arr[self::ACTION_NAME ] : '';
		$ctrl   != "" && ($re.="/{$ctrl}");
		$action != "" && ($re.="/{$action}");
		$fragment = isset($arr[self::ANCHOR]) ? $arr[self::ANCHOR] : "";
		$question_mark = isset($arr[self::MARKKEY]) ? $arr[self::MARKKEY] : "";
		unset($arr[self::CONTROLLER_NAME],$arr[self::ACTION_NAME],$arr[self::ANCHOR]);
		foreach($arr as $key=>$value){
			$re.="/{$key}/{$value}";
		}
		if($question_mark != ""){
			$re .= "?". $question_mark;
		}
		$fragment != "" && ($re .= "#{$fragment}");
		return $re;
	}

	/**
	 * @brief 将urlArray用原生url形式表现出来
	 * @access private
	 */
	private static function urlarray2native($arr){
		$re = "/";
		$re .= self::get_index();
		$fragment = isset($arr[self::ANCHOR]) ? $arr[self::ANCHOR] : "";
		$question_mark = isset($arr[self::MARKKEY]) ? $arr[self::MARKKEY] : "";
		unset($arr[self::ANCHOR] , $arr[self::MARKKEY]  );
		if(count($arr)){
			$tmp = array();
			foreach($arr as $key=>$value){
				$tmp[] ="{$key}={$value}";
			}
			$tmp = implode("&",$tmp);
			$re .= "?{$tmp}";
		}
		if( count($arr) && $question_mark!="" ){
			$re .= "&".$question_mark;
		}elseif($question_mark!=""){
			$re .= "?".$question_mark;
		}
		if($fragment != ""){
			$re .= "#{$fragment}";
		}
		return $re;
	}

	/**
	 * @brief 获取路由缓存
	 * @return array
	 */
	private static function get_routecache(){
		//配置文件中不存在路由规则
		if(self::$url_route === false){
			return null;
		}
		//存在路由的缓存信息
		if(self::$url_route){
			return self::$url_route;
		}
		//第一次初始化
		$route_list = App::get_conf("route");
		$route_list = $route_list?$route_list:array();
		if(empty($route_list)){
			self::$url_route = false;
			return null;
		}
		$cache_route = array();
		foreach($route_list as $key => $val){
			if(is_array($val)){
				continue;
			}
			$temp_arr = explode('/',trim($val,'/'),3);
			if($temp_arr < 2){
				continue;
			}
			//进行路由规则的级别划分,$level越低表示匹配优先
			$level = 3;
			if    ( ($temp_arr[0] != '<'.self::CONTROLLER_NAME.'>') && ($temp_arr[1] != '<'.self::ACTION_NAME.'>') ) $level = 0;
			elseif( ($temp_arr[0] == '<'.self::CONTROLLER_NAME.'>') && ($temp_arr[1] != '<'.self::ACTION_NAME.'>') ) $level = 1;
			elseif( ($temp_arr[0] != '<'.self::CONTROLLER_NAME.'>') && ($temp_arr[1] == '<'.self::ACTION_NAME.'>') ) $level = 2;

			$cache_route[$level][$key] = $val;
		}

		if(empty($cache_route)){
			self::$url_route = false;
			return null;
		}
		ksort($cache_route);
		self::$url_route = $cache_route;
		return self::$url_route;
	}

	/**
	 * @brief 将urlArray转成路由后的url
	 * @access private
	 */
	private static function urlarray2diy($arr){
		if(!isset( $arr[self::CONTROLLER_NAME] ) || !isset($arr[self::ACTION_NAME ]) || !($route_list = self::get_routecache()) ){
			return false;
		}
		foreach($route_list as $level => $regarray){
			foreach($regarray as $regpattern => $value){
				$url_array = explode('/',trim($value,'/'),3);
				if($level == 0 && ($arr[self::CONTROLLER_NAME].'/'.$arr[self::ACTION_NAME] != $url_array[0].'/'.$url_array[1]) ){
					continue;
				}else if($level == 1 && ($arr[self::ACTION_NAME] != $url_array[1]) ){
					continue;
				}else if($level == 2 && ($arr[self::CONTROLLER_NAME] != $url_array[0]) ){
					continue;
				}
				$url = self::parse_regpattern($arr,array($regpattern => $value));
				if($url){
					return $url;
				}
			}
		}
		return false;
	}

	/**
	 * @brief 根据规则生成URL
	 * @param $url_array array url信息数组
	 * @param $regPattern array 路由规则
	 * @return string or false
	 */
	private static function parse_regpattern($url_array,$reg_array){
		$regpattern = key($reg_array);
		$value      = current($reg_array);
		//存在自定义正则式
		if(preg_match_all("%<\w+?:.*?>%",$regpattern,$custom_regmatch)){
			$reginfo = array();
			foreach($custom_regmatch[0] as $val){
				$val     = trim($val,'<>');
				$reg_temp = explode(':',$val,2);
				$reginfo[$reg_temp[0]] = $reg_temp[1];
			}

			//匹配表达式参数
			$replace_array = array();
			foreach($reginfo as $key => $val){
				if(strpos($val,'%') !== false){
					$val = str_replace('%','\%',$val);
				}
				if(isset($url_array[$key]) && preg_match("%$val%",$url_array[$key])){
					$replace_array[] = $url_array[$key];
					unset($url_array[$key]);
				}else{
					return false;
				}
			}
			$url = str_replace($custom_regmatch[0],$replace_array,$regpattern);
		}else{
			$url = $regpattern;
		}

		//处理多余参数
		$param_array      = self::pathinfo2array($value);
		$question_markkey = isset($url_array[self::MARKKEY]) ? $url_array[self::MARKKEY] : '';
		$anchor          = isset($url_array[self::ANCHOR])          ? $url_array[self::ANCHOR]          : '';
		unset($url_array[self::CONTROLLER_NAME],$url_array[self::ACTION_NAME],$url_array[self::ANCHOR],$url_array[self::MARKKEY]);
		foreach($url_array as $key => $rs){
			if(!isset($param_array[$key])){
				$question_markkey .= '&'.$key.'='.$rs;
			}
		}
		$url .= ($question_markkey) ? '?'.trim($question_markkey,'&') : '';
		$url .= ($anchor)          ? '#'.$anchor                    : '';
		return $url;
	}
	/**
	 * @brief 将请求的url通过路由规则解析成$url_array
	 * @param $url string 要解析的url地址
	 */
	private static function decode_routeurl($url){
		$url       = trim($url,'/');
		$url_array  = array();//url的数组形式
		$route_list = self::get_routecache();
		if(!$route_list){
			return $url_array;
		}
		foreach($route_list as $level => $reg_array){
			foreach($reg_array as $pattern => $value){
				//解析执行规则的url地址
				$exeurl_array = explode('/',$value);
				//判断当前url是否符合某条路由规则,并且提取url参数
				$patternreplace = preg_replace("%<\w+?:(.*?)>%","($1)",$pattern);
				if(strpos($patternreplace,'%') !== false){
					$patternreplace = str_replace('%','\%',$patternreplace);
				}
				if(preg_match("%$patternreplace%",$url,$matchvalue)){
					//是否完全匹配整个完整url
					$matchall = array_shift($matchvalue);
					if($matchall != $url){
						continue;
					}
					//如果url存在动态参数，则获取到$url_array
					if($matchvalue){
						preg_match_all("%<\w+?:.*?>%",$pattern,$matchreg);
						foreach($matchreg[0] as $key => $val){
							$val                     = trim($val,'<>');
							$temp_array               = explode(':',$val,2);
							$url_array[$temp_array[0]] = isset($matchvalue[$key]) ? $matchvalue[$key] : '';
						}
						//检测controller和action的有效性
						if( (isset($url_array[ self::CONTROLLER_NAME ]) && !preg_match("%^\w+$%",$url_array[ self::CONTROLLER_NAME ]) ) || (isset($url_array[ self::ACTION_NAME]) && !preg_match("%^\w+$%",$url_array[ self::ACTION_NAME ]))){
							$url_array  = array();
							continue;
						}
						//对执行规则中的模糊变量进行赋值
						foreach($exeurl_array as $key => $val){
							$param_name = trim($val,'<>');
							if( ($val != $param_name) && isset($url_array[$param_name]) ){
								$exeurl_array[$key] = $url_array[$param_name];
							}
						}
					}
					//分配执行规则中指定的参数
					$param_array = self::pathinfo2array(join('/',$exeurl_array));
					$url_array   = array_merge($url_array,$param_array);
					return $url_array;
				}
			}
		}
		return $url_array;
	}

	public static function tidy($url){
		return preg_replace("![/\\\\]{2,}!","/",$url);
	}

	/**
	 * @brief  接收基准格式的URL，将其转换为Config中设置的模式
	 * @param  String $url      传入的url
	 * @return String $finalUrl url地址
	 */
	public static function create($url=''){
		if(preg_match("!^[a-z]+://!i",$url)){
			return $url;
		}
		$base_url = self::get_phpself();
		if($url == ""){
			return self::get_script();
		}elseif($url == "/"){
			return self::get_script();//.$base_url;
		}
		$rewrite_rule = App::get_conf("cfg.rewrite_rule");
		$rewrite_rule = $rewrite_rule?$rewrite_rule:'native';
		//判断是否需要返回绝对路径的url
		$base_dir = self::get_script();
		$base_url = self::tidy($base_url);
		$url = self::tidy($url);
		$tmp_url = false;
		if($rewrite_rule == 'pathinfo' )	{
			$tmp_url = self::convert($url,self::URL_PATHINFO , self::URL_DIY);
		}
		if($tmp_url!==false){
			$url = $tmp_url;
		}else{
			switch($rewrite_rule){
				case 'url': // 兼容以前的
				case 'get': //config文件里叫get
					$url = self::convert($url,self::URL_PATHINFO,self::URL_NATIVE);
					break;
				case 'url-pathinfo':
					$url = "/".self::get_index().$url;
					break;
			}
		}
		$url = self::tidy($base_dir.$url);
		return $url;
	}

	/**
	 * @brief 获取网站根路径
	 * @param  string $protocol 协议  默认为http协议，不需要带'://'
	 * @return String $baseUrl  网站根路径
	 *
	 */
	public static function get_host($protocol='http'){
		$port    = $_SERVER['SERVER_PORT'] == 80 ? '' : ':'.$_SERVER['SERVER_PORT'];
		$host	 = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		$base_url = $protocol.'://'.$host.$port;
		return $base_url;
	}
	/**
	 * @brief 获取当前执行文件名
	 * @return String 文件名
	 */
	public static function get_phpself(){
		$re = explode("/",$_SERVER['SCRIPT_NAME']);
		return end($re);
	}
	/**
	 * @brief 返回入口文件URl地址
	 * @return string 返回入口文件URl地址
	 */
	public static function get_entryurl(){
		return self::get_host().$_SERVER['SCRIPT_NAME'];
	}

	/**
	 * @brief 获取入口文件名
	 */
	public static function get_index(){
		if(!isset($_SERVER['SCRIPT_NAME'])){
			return 'index.php';
		}else{
			return basename($_SERVER['SCRIPT_NAME']);
		}
	}

	/**
	 * @brief 返回页面的前一页路由地址
	 * @return string 返回页面的前一页路由地址
	 */
	public static function get_refroute(){
		if(isset($_SERVER['HTTP_REFERER']) && (self::get_entryurl() & $_SERVER['HTTP_REFERER']) == self::get_entryurl()){
			return substr($_SERVER['HTTP_REFERER'],strlen(self::get_entryurl()));
		}else
			return '';
	}
	
	/**
	 * @brief 返回页面的前一页地址
	 * @return string 返回页面的前一页路由地址
	 */
	public static function get_refer(){
		if(isset($_SERVER['HTTP_REFERER'])){
			return $_SERVER['HTTP_REFERER'];
		}else
			return '';
	}
	
	/**
	 * @brief  获取当前脚本所在文件夹
	 * @return 脚本所在文件夹
	 */
	public static function get_script(){
		$re=trim(dirname($_SERVER['SCRIPT_NAME']),'\\');
		if($re!='/'){
			$re = $re."/";
		}
		return $re;
	}

	/**
	 * @brief 获取当前url地址[经过RewriteRule之后的]
	 * @return String 当前url地址
	 */
	public static function get_url(){
		if (isset($_SERVER['HTTP_X_REWRITE_URL'])){
			// check this first so IIS will catch
			$request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
		}elseif(isset($_SERVER['IIS_WasUrlRewritten']) && $_SERVER['IIS_WasUrlRewritten'] == '1' && isset($_SERVER['UNENCODED_URL'])       && $_SERVER['UNENCODED_URL'] != '')	{
			// IIS7 with URL Rewrite: make sure we get the unencoded url (double slash problem)
			$request_uri = $_SERVER['UNENCODED_URL'];
		}elseif (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],"Apache")!==false ){
			$request_uri = $_SERVER['PHP_SELF'];
		}elseif(isset($_SERVER['REQUEST_URI']))	{
			$request_uri = $_SERVER['REQUEST_URI'];
		}elseif(isset($_SERVER['ORIG_PATH_INFO'])){
			// IIS 5.0, PHP as CGI
			$request_uri = $_SERVER['ORIG_PATH_INFO'];
			if (!empty($_SERVER['QUERY_STRING'])){
				$request_uri .= '?' . $_SERVER['QUERY_STRING'];
			}
		}else{
			die("get_url is error");
		}
		return self::get_host().$request_uri;
	}
	/**
	 * @brief 获取当前URI地址
	 * @return String 当前URI地址
	 */
	public static function get_uri(){
		if( !isset($_SERVER['REQUEST_URI']) ||  $_SERVER['REQUEST_URI'] == "" ){
			// IIS 的两种重写
			if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])){
				$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
			}else if (isset($_SERVER['HTTP_X_REWRITE_URL'])){
				$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
			}else{
				//修正pathinfo
				if ( !isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO']) )
					$_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];
				if ( isset($_SERVER['PATH_INFO']) ) {
					if ( $_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME'] )
						$_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
					else
						$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
				}else{
					$_SERVER['REQUEST_URI'] = "";
				}
				//修正query
				if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])){
					$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
				}
			}
 		}
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * @brief 获取url参数
	 * @param String url 需要分析的url，默认为当前url
	 */
	public static function init($url=''){
		//四种
		//native： /index.php?controller=blog&action=read&id=100
		//pathinfo:/blog/read/id/100
		//native-pathinfo:/index.php/blog/read/id/100
		//diy:/blog-100.html
		$url  = !empty($url)?$url:self::server_var(true);
		preg_match('/\.php(.*)/',$url,$phpurl);
		if(!isset($phpurl[1]) || !$phpurl[1]){
			if($url != "" ){
				//强行赋值
				//todo：检测是否有bug
				$phpurl = array(1=>"?");
			}else{
				return;
			}
		}
		$url = $phpurl[1];
		$url_array = array();
		$rewrite_rule = App::get_conf("cfg.rewrite_rule");
		$rewrite_rule = $rewrite_rule?$rewrite_rule:'native';
		if($rewrite_rule!='native'){
			$url_array = self::decode_routeurl($url);
		}
		if($url_array == array()){
			if( $url[0] == '?' ){
				$url_array = $_GET;
			}else{
				$url_array = self::pathinfo2array($url);
			}
		}
		if( isset($url_array[self::CONTROLLER_NAME]) ){
			$tmp = explode('-',$url_array[self::CONTROLLER_NAME]);
			if( count($tmp) == 2 ){
				tGpc::set('module',$tmp[0]);
				tGpc::set(self::CONTROLLER_NAME , $tmp[1]);
			}else{
				tGpc::set(self::CONTROLLER_NAME , $url_array[self::CONTROLLER_NAME] );
			}
		}
		if( isset($url_array[self::CONTROLLER_NAME])  ){
			tGpc::set(self::CONTROLLER_NAME,$url_array[self::CONTROLLER_NAME]);
			if(tGpc::get('action')=='run'){
				tGpc::set('action',null);
			}
		}
		unset($url_array[self::CONTROLLER_NAME] , $url_array[self::CONTROLLER_NAME] , $url_array[self::ANCHOR] );
		foreach($url_array as $key=>$value){
			tGpc::set($key,$value);
		}
	 }
	/**
	 * @brief  获取拼接两个地址
	 * @param  String $path_a
	 * @param  String $path_b
	 * @return string 处理后的URL地址
	 */
	public static function get_relative($path_a,$path_b){
		$path_a = strtolower(str_replace('\\','/',$path_a));
		$path_b = strtolower(str_replace('\\','/',$path_b));
		$arr_a = explode("/" , $path_a) ;
		$arr_b = explode("/" , $path_b) ;
		$i = 0 ;
		while (true){
			if($arr_a[$i] == $arr_b[$i]) $i++ ;
			else break ;
		}
		$len_b = count($arr_b) ;
		$len_a = count($arr_a) ;
		if(!$arr_b[$len_b-1])$len_b = $len_b - 1;
		if(!$len_a[$len_a-1])$len_a = $len_a - 1;
		$len = ($len_b>$len_a)?$len_b:$len_a ;
		$str_a = '' ;
		$str_b = '' ;
		for ($j = $i ;$j<$len ;$j++){
			if(isset($arr_a[$j])){
				$str_a .= $arr_a[$j].'/' ;
			}
			if(isset($arr_b[$j])) $str_b .= "../" ;
		}
		return $str_b . $str_a ;
	}
	/**
	 * @brief 获取服务器变量
	 */
	public static function server_var($real = false){
		$software = isset($_SERVER['SERVER_SOFTWARE'])?$_SERVER['SERVER_SOFTWARE']:"apache";
		$softs = array("nginx","apache","iis");
		foreach($softs as $value){
			if(stripos($software,$value) !== false ){
				$soft_type = $value;
				break;
			}
		}
		$re = "";//返回
		switch($soft_type){
			case "nginx":
				if($real){
					if(isset($_SERVER['DOCUMENT_URI']) ){
						$re = $_SERVER['DOCUMENT_URI'];
					}elseif( isset($_SERVER['REQUEST_URI']) ){
						$re = $_SERVER['REQUEST_URI'];
					}
				}else{
					if(isset($_SERVER['REQUEST_URI']))$re = $_SERVER['REQUEST_URI'];
				}
				break;
			case "apache":
				$re = $real?$_SERVER['PHP_SELF']:$_SERVER['REQUEST_URI'];
				break;
			case "iis":
				if($real){
					if( isset($_SERVER['HTTP_X_REWRITE_URL'])  ){
						$re = isset($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['HTTP_X_REWRITE_URL'];
					}elseif(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != "" ){
						$re = $_SERVER['PATH_INFO'];
					}elseif(isset($_SERVER["SCRIPT_NAME"] ) && isset($_SERVER['QUERY_STRING']) ){
						$re = $_SERVER["SCRIPT_NAME"] .'?'. $_SERVER['QUERY_STRING'];
					}
				}else{
					if(isset($_SERVER['REQUEST_URI'])){
						$re = $_SERVER['REQUEST_URI'];
					}elseif( isset($_SERVER['HTTP_X_REWRITE_URL']) ){
						//不取HTTP_X_REWRITE_URL
						$re = $_SERVER['HTTP_X_REWRITE_URL'];
					}elseif(isset($_SERVER["SCRIPT_NAME"] ) && isset($_SERVER['QUERY_STRING']) ){
						$re = $_SERVER["SCRIPT_NAME"] .'?'. $_SERVER['QUERY_STRING'];
					}
				}
				break;
			default:
				$re = $_SERVER['REQUEST_URI'];
				break;
		}
		return $re;
	}
}

