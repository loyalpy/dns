<?php
if (__FILE__ == ''){die('error code: 0');}
define('ROOT_PATH', str_replace('app', '', dirname(__FILE__)));
define('UPLOAD_PATH',ROOT_PATH."www".DIRECTORY_SEPARATOR."static".DIRECTORY_SEPARATOR);
if (DIRECTORY_SEPARATOR == '\\'){
    @ini_set('include_path', '.;' . ROOT_PATH);
}else{
    @ini_set('include_path', '.:' . ROOT_PATH);
}
class App{
	//应用的名称
    public static $name    = 'home';
    //用户的编码
    public static $charset = 'utf-8';
	//默认时区
    public static $timezone = 'Asia/Shanghai';
    //渲染时的数据
    public static $data = array();
    //应用的config信息
    public static $config  = array();
    //应用的语言信息
    public static $lang = array();
    //控制器所在位置
	public static $_classes = array();
	//应用头信息i
	public static $_headers = array();
	//控制器
    public static $controller;
    //项目初始化
    public static function start($app_name){
    	tTime::start();    	
        //默认关闭魔法引号
        ini_set("magic_quotes_runtime", 0);
        self::close_magicquotes();
    	//加载通用配置文件
        self::load_conf(ROOT_PATH."conf/");
        //设置编码
        self::$charset  = self::get_conf("cfg.charset");
        self::set_header("content-type:text/html;charset=".App::$charset);
        //设置时区
        self::$timezone = self::get_conf("cfg.timezone");
        ini_set('date.timezone',self::$timezone);
        //加载自定义类
        self::set_classes(self::get_conf("cfg.lib"));
        //站点缓存配置数据
        $ext_conf = self::get_conf("cfg.ext_conf");
        if($ext_conf){
            foreach($ext_conf as $k=>$v){
                if(file_exists(ROOT_PATH.$v)){
                    $re = include(ROOT_PATH.$v);
                    self::set_conf($k,$re);
                    self::set_data($k,$re);
                }
            }
        } 
        //设置调试模式
        self::set_debugmode(self::get_conf("cfg.debug"));
        //开始向拦截器里注册类
        $interceptor = self::get_conf("cfg.interceptor");
        if(is_array($interceptor)){
            tInterceptor::reg($interceptor);            
        }
        
    	//APP_NAME检查
        if(empty($app_name)){
            /*
            if(tClient::is_mobile()){
                $app_name = "wx";
            }
            */
            if(empty($app_name)){
                $app_name = R("app","string");
            }
            if(empty($app_name)){
                $host = tUrl::get_host();
                foreach(App::get_conf("cfg.app") as $k => $v){
                    if($v == $host){
                        $app_name = $k;
                        break;
                    }
                }
            }
        }
        if(empty($app_name)){
            $app_name = "home";
        }
        self::$name = $app_name;
        self::set_data("app",self::$name);
        //加载单独的配置
        self::load_conf(ROOT_PATH."conf/".self::$name."/");
        //加载语言包 
        self::load_lang(ROOT_PATH."lang/");       
        self::load_lang(ROOT_PATH."lang/".self::$name."/".self::get_conf("app.lang")."/");
        //会话
        tSafe::set();
    }
    //项目开始
    public static function run($app_name="",$controller="",$action=""){
    	self::start($app_name);
        self::exec($controller,$action);
		App::end(0);
    }
    //项目执行模块
    public static function exec($controller,$action){
        //URL初始
        tUrl::init();
        //初始化控制器
        if(empty($controller)){
            $controller = tUrl::get_info("controller");
            if($controller === null)$controller = self::get_conf("app.default_controller");
        }
        if(empty($action)){
            $action = tUrl::get_info('action');
            if($action === null)$action = self::get_conf("app.default_action");
        }      
        tInterceptor::run("onCreateApp");        
        tInterceptor::run("onCreateController");        
        self::$controller =  self::create_controller($controller,$action);
        self::$controller->run();    
        tInterceptor::run("onFinishController");
        tInterceptor::run("onFinishApp");
    }
    //项目结束
    public static function end($code = 0){
    	exit($code);
    } 
    //创建控制器
    public static function create_controller($controller,$action){
        //创建控制器
        if(class_exists($controller)){
        	$controller_class = new $controller($controller,$action);
        }else{
        	$controller_class = new tController($controller,$action);
        }
        return $controller_class;
    }
    //设置项目引入类路径
    public static function set_classes($classes){
        if(is_string($classes)){
        	self::$_classes += array($classes);
        }elseif(is_array($classes)){
        	self::$_classes += $classes;
        }
    }
    //魔法引号处理
    public static function close_magicquotes(){
		if(get_magic_quotes_gpc()){
			$_POST   = self::_stripslash($_POST);
			$_GET    = self::_stripslash($_GET);
			$_COOKIE = self::_stripslash($_COOKIE);
		}
    }
    //去除魔法引号
    public static function _stripslash($arr){
    	if(is_array($arr)){
			foreach($arr as $key=>$value){
				$arr[$key] = self::_stripslash($value);
			}
			return $arr;
		}else{
			return stripslashes($arr);
		}
    }
    //设置调试模式
    public static function set_debugmode($flag){
		switch($flag){
			case 1:
				ini_set("display_errors","on");
				error_reporting(E_ALL | E_STRICT);
			    tException::set_debugmode(true);
				break;
			case 2:
				ini_set("display_errors","off");
				error_reporting(E_ALL | E_STRICT);
			    tException::set_debugmode(true);
				break;
			default:
				ini_set("display_errors","off");
				error_reporting(0);
			    tException::set_debugmode(false);
				break;
		}
		tException::set_logpath(ROOT_PATH."cache/errorlog/".self::$name."@@@".date("y-m-d").".log");
		set_error_handler("tException::php_error" , E_ALL | E_WARNING | E_NOTICE);
		set_exception_handler("tException::php_exception");
		register_shutdown_function(array('tException',"fatal_error"));
		register_shutdown_function(array('tInterceptor',"shut_down"));
    }
    //设置项目数据
    public static function set_data($data,$value=""){
        if(is_array($data)){
            self::$data = array_merge(self::$data,$data);
        }else{
        	self::$data[$data] = $value;
        }
    }
    //获取数据
    public static function get_data($uri=""){
    	$node = self::$data;
    	$paths = explode('.', $uri);
    	while (!empty($paths)) {
    		$path = array_shift($paths);
    		if (!isset($node[$path])) {
    			return null;
    		}
    		$node = $node[$path];
    	}
    	return $node;
    }
    //设置config
    public static function set_conf($data,$value){
    	self::$config[$data] = $value;
    }
    //获取config
    public static function get_conf($uri){
    	$node = self::$config;
    	$paths = explode('.', $uri);
    	while (!empty($paths)) {
    		$path = array_shift($paths);
    		if (!isset($node[$path])) {
    			return null;
    		}
    		$node = $node[$path];
    	}
    	return $node;
    }
    //加载config
    public static function load_conf($path){
    	if(is_file($path)){
    		$ext  = tFile::get_ext($path);
    		$name = tFile::get_filename($path);
    		if($ext == "php"){
    			self::$config[$name] =  require($path);;
    		}else{
    			$name = tFile::get_filename($path);
    			self::$config[$name] = self::load_ini_conf($path);
    		}
    		 	
    	}
    	foreach(glob($path. '/*.conf') as $config_file){
    		$name = basename($config_file, '.conf');
    		self::$config[$name] = self::load_ini_conf($config_file);
    	}
    	foreach(glob($path. '/*.php') as $config_file){
    		$name = basename($config_file, '.php');
    		self::$config[$name] =  require($config_file);;
    	}
    }
    
    //设置语言包
    public static function set_lang($data,$value){
    	self::$lang[$data] = $value;
    }
    //获取语言包
    public static function get_lang($uri){
    	$node = self::$lang;
    	$paths = explode('.', $uri);
    	while (!empty($paths)) {
    		$path = array_shift($paths);
    		if (!isset($node[$path])) {
    			return null;
    		}
    		$node = $node[$path];
    	}
    	return $node;
    }
    //加载语言包
    public static function load_lang($path){
    	if(is_file($path)){
    		$ext  = tFile::get_ext($path);
    		$name = tFile::get_filename($path);
    		if($ext == "php"){
    			self::$lang[$name] =  require($path);;
    		}else{
    			$name = tFile::get_filename($path);
    			self::$lang[$name] = self::load_ini_conf($path);
    		}
    
    	}
    	foreach(glob($path. '*.lang') as $config_file){
    		$name = basename($config_file, '.lang');
    		self::$lang[$name] = self::load_ini_conf($config_file);
    	}
    	foreach(glob($path. '*.php') as $config_file){
    		$name = basename($config_file, '.php');
    		self::$lang[$name] =  require($config_file);;
    	}
    }
    
    //解析INIconfig
    public static function load_ini_conf($config_file,$is = true){
    	$config = parse_ini_file($config_file, $is);
        if (!is_array($config) || empty($config)){
            throw new Exception("{$config_file}Invalid configuration format");
        }
        return $config;
    }
    //设置header  
    public static function set_header($header){
    	self::$_headers[] = $header;
    }
    //输出所有header
    public static function flush_header(){
    	if (!headers_sent()) {
			foreach (self::$_headers as $header) {
				App::header($header);
			}
		}
		self::$_headers = array();
    } 
    //输出header
    public static function header($header){
    	header($header, true);
    }
    //加载lib
    public static function uselib($files = ""){
        $file_path = ROOT_PATH."lib/".str_replace(".", "/", $files) .'.php';
        if(is_file($file_path)){
            include_once($file_path);
            return true;
        }
        return false;
    }
    //项目自动加载类
	public static function autoload($class_name){
		static $lib_classs;
		if(empty($lib_classs)){
			$lib_classs = array(
					//lib
					'tUrl'              =>  'app/lib/tUrl.php',
					'tCode'             =>  'app/lib/tCode.php',
					'tCookie'           =>  'app/lib/tCookie.php',
					'tSession'          =>  'app/lib/tSession.php',
					'tGpc'              =>  'app/lib/tGpc.php',
					'tSafe'             =>  'app/lib/tSafe.php',
					'tObject'           =>  'app/lib/tObject.php',
					'tFile'             =>  'app/lib/tFile.php',
					'tAjax'             =>  'app/lib/tAjax.php',
					'tTime'             =>  'app/lib/tTime.php',
					'tHash'             =>  'app/lib/tHash.php',
					'tCurl'             =>  'app/lib/tCurl.php',
                    //cli
                    'tCli'              =>  'app/cli/tCli.php',					
					//server
					'tClient'           =>  'app/server/server_client.php',
					'tServer'           =>  'app/server/server_server.php',
					//mvc
					'tController'		=>	'app/mvc/mvc_controller.php',
					'tAction'		    =>	'app/mvc/mvc_action.php',
					'tTag'              =>  'app/mvc/mvc_tag.php',
					'tDB'               =>  'app/mvc/mvc_db.php',
					'tPdo'              =>  'app/mvc/mvc_pdo.php',
					'tMysql'            =>  'app/mvc/mvc_mysql.php',
					'tModel'            =>  'app/mvc/mvc_model.php',
					'tQuery'            =>  'app/mvc/mvc_query.php',
                    'tMongo'            =>  'app/mvc/mvc_mongo.php',
					//exception
					'tException'        =>  'app/sys/sys_exception.php',
					'tHttpException'    =>	'app/sys/sys_httpexception.php',
					'tInterceptor'		=>	'app/sys/sys_interceptor.php',					
					//cache
					'tCache'            =>  'app/cache/cache_cache.php',
					'tFileCache'		=>	'app/cache/cache_file.php',
					'tCacheInterface'	=>	'app/cache/cache_interface.php',
					'tMemCache'		    =>	'app/cache/cache_memcache.php',
					//log					
					'tLog'              =>  'app/log/log_log.php',
					'tFileLog'		    =>	'app/log/log_file.php',
					'tDBLog'	        =>	'app/log/log_db.php'
			);
		}
		if(!preg_match('|^\w+$|',$class_name)){
            return true;
        }
		if(isset($lib_classs[$class_name])){
			include(ROOT_PATH.$lib_classs[$class_name]);
		}else if(isset(self::$_classes)){
			$ext_classs   = self::$_classes;
			$ext_classs[] = "controller.*";			
            $ext_classs[] = "controller".DIRECTORY_SEPARATOR.self::$name.".*";
            
            $ext_classs[] = "model.*";
            $ext_classs[] = "model".DIRECTORY_SEPARATOR.self::$name.".*";

            foreach($ext_classs as $class_path){
                $file_path = ROOT_PATH.strtr(strtolower(trim($class_path,'*')),'.',DIRECTORY_SEPARATOR).($class_name) .'.php';
                if(is_file($file_path)){
                   include($file_path);
                   return true;
                 }
             }
            unset($ext_classs);
			return true;
		}
		return true;
	}
}
//自动加载类函数
spl_autoload_register(array('App', 'autoload'));
//快速注册类事例
function X($class_name,$parm='',$reset = ""){
	static $staitc_classs = array();
	$name = is_array($parm)?md5($class_name.serialize($parm)):md5($class_name.$parm);
	if($reset === null){
        unset($staitc_classs[$name]);
    }

    if(!isset($staitc_classs[$name])){
		$staitc_classs[$name] = new $class_name($parm);
	}	
	return $staitc_classs[$name];
}
//快速创建model
function M($mod_name='',$reset=""){
	if(substr($mod_name,0,1) === "@"){
		return X(substr($mod_name,1)."_model","",$reset);
	}else{
		return X('tModel',$mod_name,$reset);
	}	
}
//快速创建自定义类
function C($cls_name = '',$parm=''){
	return X('cls_'.$cls_name,$parm);
}
//快速创建查询类
function Q($q_name = ''){
	return X('tQuery',$q_name);
}
//执行原生态的SQL
function Sq($sql = "",$dbinfo = array()){
	$dbinfo = empty($dbinfo)?App::get_conf("db.main"):$dbinfo;
	$db = tDB::get_db($dbinfo);
	$tablepre = isset($dbinfo['tablepre']) ? $dbinfo['tablepre'] : '';
	//多表处理
	$sql = preg_replace("'@'si",$tablepre,$sql);
	return $db->query($sql);
}
//U函数
function U($parm = ''){
	$re = "/";
	$ex = "@";
	if(strpos($parm,$ex) === false){
		$re = tUrl::create($parm);
	}else{
		$parm_2 = explode($ex,$parm);
		$re = App::get_conf("cfg.app.{$parm_2[0]}").U($parm_2[1]);
	}
	return $re;
}
//R函数
function R($parm,$filter="",$default="",$type=false){
	$res = $filter?tUtil::filter(tGpc::get($parm,$type),$filter):tGpc::get($parm,$type);
	return $res === ""?$default:$res;
}
//L函数
function L($uri){
	return App::get_lang($uri);
}
//信息快速处理
function I($message="",$callback="",$type="error",$exejs=""){
    if(is_int($message) && in_array($message,array(404,503,403,1000))){
        $path = $callback;
        $path = tException::path_filter($path);
        $data = array(
            'title'   => 'HTTP 404',
            'heading' => 'the file not found',
            'message' => "not found this page",
        );
        throw new tHttpException($data,$message);
    }else{
        $is_inajax = R("in_ajax","int");
        if($is_inajax){
            $return = array("error"=>($type == "success"?0:1),"message"=>$message);
            if($callback && !in_array($callback,array("back",""))){
                $return['callback'] = $callback;
            }
            tAjax::json($return);
        }else{
            header("location:".U("home@/site/msg?type={$type}&info=&title=".urlencode($message)."&callback=".urlencode($callback)."&exejs=".urlencode($exejs)));
            exit(0);
        }
    }
}
/* 调试输出变量 */
function dump($var, $echo=true,$label=null, $strict=true){
    $label = ($label===null) ? '' : rtrim($label) . ' ';
    if(!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
        } else {
            $output = $label . print_r($var, true);
        }
    }else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if(!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>'. $label. htmlspecialchars($output, ENT_QUOTES). '</pre>';
        }
    }
    if ($echo) {
        echo("<div style=\"text-align:left;\">".$output."</div>");
        return null;
    }else
        return $output;
}