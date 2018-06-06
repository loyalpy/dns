<?php
/*
status = true  状态
controller  = ""  控制器
action = ""  动作
persistent = false  是否永久执行
time_long = 1;;多长时间运行一次
parm 参数
*/
return array(
	array("status"=>true,"controller"=>"Crond","action"=>"dns","persistent"=>false,"time_long"=>1,"parm"=>array()),
	array("status"=>true,"controller"=>"Crond","action"=>"email","persistent"=>false,"time_long"=>1,"parm"=>array()),
)
?>