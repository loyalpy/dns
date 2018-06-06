<?php
header("content-type:text/html;charset=utf-8");
define('IN_MADM', TRUE);
error_reporting(E_ALL);
require_once('./include/class/memadmin.class.php');
$madmin=new MEMADMIN();
$madmin->checkmemsuport();
$madmin->show_views();
?>
