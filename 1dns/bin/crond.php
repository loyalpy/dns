<?php
if(0 === strpos(strtolower(PHP_OS), 'win')){
	exit("can not run on Windows operating system\n");
}
//控制器
$action = explode("/",trim((isset($argv[1])?$argv[1]:'crond/index'),"/"));
if(empty($action) || count($action) != 2){
	die("controller requere : crond/index");
}
$_GET['inc'] = $action[0];
$_GET['act'] = $action[1];
//参数
$params = isset($argv[2])?$argv[2]:'';
$params_arr = explode("/", trim($params, "/"));
if(count($params_arr) > 1){
	// 解析剩余参数 并采用GET方式获取
	preg_replace('@(\w+),([^,\/]+)@e', '$_GET[\'\\1\']="\\2";', implode(',', $params_arr));
}
require(str_replace('bin', '', dirname(__FILE__))."app/App.php");
App::run("crond");
?>