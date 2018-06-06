<?php
class tController extends tObject{
	//主题皮肤
	public $theme                 = 'default';          //主题方案
	public $skin                  = 'default';          //风格方案
	public $lang                  = 'zh';               //主题语言
	public $layout                = '';                 //布局文件
	public $extend                = '.html';            //模板扩展名
	public $app                   = '';
	//控制器
	public $ctrl_id               = null;               //控制器ID标识符
	public $action_id             = null;               //当前方法ID标识符
    //默认视图
    protected $default_action     = 'index';            //默认执行的action动作
	protected $default_viewpath   = 'view';             //默认视图目录
	protected $default_layoutpath = 'layouts';          //默认布局目录
	protected $default_skinpath   = 'skin';             //默认皮肤目录
	public $default_executeext    = '.php';             //默认编译后文件扩展名
    //渲染的数据
	private $data    = array();                         //渲染的数据
	//权限
	protected $__right        = array();                //需要校验的action动作
	//初始化
	public function __construct($controller_id,$action_id = ""){
		$this->ctrl_id = $controller_id;
		if(empty($action_id)){
			$action_id = tUrl::get_info('action');
			$action_id = ($action_id === null )?$this->default_action:$action_id;
		}
		$this->action_id = $action_id;
		
		//初始化theme方案
		$this->theme = App::get_conf("app.theme");
		//初始化skin方案
		$this->skin = App::get_conf("app.skin");
		//初始化lang方案
		$this->lang = App::get_conf("app.lang");
		//APPname
		$this->app  = App::$name;

		App::set_data("inc",$controller_id);
		App::set_data("act",$this->action_id);
	}
	//控制开始
	public function run(){
		//开启缓冲区
		ob_start();
		ob_implicit_flush(false);
		//创建action对象
		tInterceptor::run("onCreateAction");
		//获取action_id
		if(method_exists($this,$this->action_id)){
			$method_name = $this->action_id;
			$this->$method_name();
		}else{
			$action_obj = new tAction($this,$this->action_id);
			$action_obj->run();
		}
		tInterceptor::run("onFinishAction");
		flush();
	}
	//检查权限
	public function check_right($own_right){
		$inc = $this->get_id();
		$act = $this->get_action();

		if($own_right != "@all@" && strpos($own_right,"@/$inc/$act@") === false){
			return false;
		}
		return true;
	}
	//获取控制器ID
	public function get_id(){
		return $this->ctrl_id;
	}
	//获取当前action
	public function get_action(){
		return $this->action_id;
	}
	//获取视图路径
	public function get_viewpath(){
		if(!isset($this->_viewpath)){
			$view_path        = App::get_conf("cfg.view_path");
			$view_path        = $view_path?$view_path:$this->default_viewpath;
			$this->_viewpath  = ROOT_PATH.$view_path.DIRECTORY_SEPARATOR.$this->app.DIRECTORY_SEPARATOR.($this->theme?($this->theme.DIRECTORY_SEPARATOR):"");
		}
		return $this->_viewpath;
	}
	//获取编译路径
	public function get_compilepath(){
		return ROOT_PATH.App::get_conf("cfg.compile_dir").$this->app.
		DIRECTORY_SEPARATOR.($this->theme?($this->theme.DIRECTORY_SEPARATOR):"");
	}
	//获取皮肤路径
	public function get_skinpath(){
		if(!isset($this->_skinpath)){
			$skin_path        =  $this->default_skinpath;
			$this->_skinpath = $this->get_viewpath().$skin_path.DIRECTORY_SEPARATOR.$this->skin.DIRECTORY_SEPARATOR;
		}
		return $this->_skinpath;
	}
	//获取视图文件
	public function get_view(){
		return strtolower($this->ctrl_id)."/".strtolower($this->action_id);
	}
	//获取layout文件路径(无扩展名)
	public function get_layoutfile(){
		if($this->layout == null || $this->layout == '')
			return false;
		return $this->get_viewpath().$this->default_layoutpath.DIRECTORY_SEPARATOR.$this->layout;
	}
	//获取渲染数据
	public function get_data(){
		return $this->data;
	}
	//分配数据
	public function assign($data,$value=""){
		if(is_array($data)){
			$this->data = array_merge($this->data,$data);
		}elseif(is_string($data)){
			$this->data[$data] = $value;
		}
	}
	//跳转
	public function redirect($next_url = "", $location = true,$data = null){
		if((strtolower(substr($next_url,0,7)) == "http://") || (strtolower(substr($next_url,0,8)) == "https://")){
			App::header('location: '.$next_url);
			return false;
		}
		$next_url = $next_url?$next_url:$this->action_id;
		if($next_url{0} != '/'){
			//重定跳转定向
			if($this->action_id != $next_url && $location == true){
				$location_url = tUrl::create('/'.$this->ctrl_id.'/'.$next_url);
				App::header('location: '.$location_url);
				App::end(0);
			}else{//非重定向
				$action_obj = new tAction($this,$next_url);
				$action_obj->run();
				App::end(0);
			}
		}else{
			$url_array   = explode('/',$next_url,4);
			$ctrl_id     = isset($url_array[1]) ? $url_array[1] : '';
			$next_action = isset($url_array[2]) ? $url_array[2] : '';
			//重定跳转定向
			if($location == true){
				//url参数
				if(isset($url_array[3])){
					$next_action .= '/'.$url_array[3];
				}
				$location_url = tUrl::create('/'.$ctrl_id.($next_action?('/'.$next_action):""));
				App::header('location: '.$location_url);
				App::end(0);
			}else{//非重定向
				$next_ctrl = new $ctrl_id($ctrl_id);
				//跨控制器渲染数据
				if($data != null){
					$next_ctrl->assign($data);
				}
				$next_ctrl->init();
				$next_action = $next_ctrl->action_id = empty($next_action)?$this->default_action:$next_action;
				
				$next_view = new tAction($next_ctrl,$next_action);
				$next_view->run();
				App::end(0);
			}
		}
	}
	//直接显示视图内容
	public function display($view = ""){
		App::flush_header();
		$view = empty($view)?$this->get_view():$view;
		echo $this->fetch($view,true,$this->layout);
	}
	//获取视图内容
	public function fetch($view,$return = true,$layout = ''){
		$this->layout = $layout;
		$output = $this->__parse_view($view);
		if($return){
			return $output;
		}else{
			echo $output;
		}
	}
	//解析视图
	public function __parse_view($view){
		//要渲染的视图
		$file_name  = $this->get_viewpath().$view.$this->extend;
		//生成文件路径
		$cache_file = $this->get_compilepath().$view."_".($this->layout?$this->layout:"").$this->default_executeext;
		//检查视图文件是否存在
		if(file_exists($file_name)){
			//layout文件
			$layout_file = $this->get_layoutfile().$this->extend;
			$in_layout = file_exists($layout_file);	
			if(!file_exists($cache_file) || (filemtime($file_name) > filemtime($cache_file))|| ($in_layout && (filemtime($layout_file) > filemtime($cache_file)))){
				//获取view内容
				$viewcontent = file_get_contents($file_name);
				//解析模块
				$block_arr = array();
				//解析内容模块
				preg_match_all("/[\n\r\t]*\{block\s+([a-z0-9_]+)\}([\s\S]*?)\{\/block\}[\n\r\t]*/i", $viewcontent, $matchs);
				if(isset($matchs[1]) && $matchs[1]){
					foreach($matchs[1] as $k=>$v){
						$block_arr[$v] = isset($matchs[2][$k])?$matchs[2][$k]:"";
					}
				}
				//在layout中替换view
				if($in_layout){
					$layout_content = file_get_contents($layout_file);
					$search = $replace = array();
					//解析模版模块
				    preg_match_all("/[\n\r\t]*\{block\s+([a-z0-9_]+)\}([\s\S]*?)\{\/block\}[\n\r\t]*/i", $layout_content, $matchs);
				    if(isset($matchs[1]) && $matchs[1]){
						foreach($matchs[1] as $k=>$v){
							$search[] = "/[\n\r\t]*\{block\s+".$v."\}([\s\S]*?)\{\/block\}[\n\r\t]*/";
							$replace[] = isset($block_arr[$v])?$block_arr[$v]:(isset($matchs[2][$k])?$matchs[2][$k]:"");
						}
					}
					if($search){
						$viewcontent = preg_replace($search,$replace,$layout_content);
						unset($search,$replace,$block_arr);
					}else{
						$viewcontent = str_replace('{viewcontent}',$viewcontent,$layout_content);
					}
				}
				//标签编译
				$inputcontent = $this->__tag($viewcontent);
				//创建文件
				$fileobj  = new tFile($cache_file,'w+');
				$fileobj->write($inputcontent);
				$fileobj->save();
				unset($fileobj);
			}
			return $this->__require($cache_file);
		}else{
			$path = $file_name;
			$data = array(
				'title'   => 'HTTP 404',
				'heading' => 'Templete file not found',
				'message' => "not found this view page($path)",
			);
			throw new tHttpException($data,404);
			return false;
		}
	}
	//包含解析后文件
	public function __require($filename){
		//渲染控制器数据
		$__controller_data = $this->get_data();
		extract($__controller_data,EXTR_OVERWRITE);
		unset($__controller_data);
		//渲染App数据
		$__app_data = App::$data;
		extract($__app_data,EXTR_OVERWRITE);
		unset($__app_data);
		ob_start();
		require($filename);
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
    }
	//解析视图标签
    public function __tag($content){
		$tag_obj = new tTag();
		return $tag_obj->parse($content);
	}
}

?>
