<?php
 /**
 * @class pay_offline
 * @brief 线下支付插件类
 */
class pay_offline extends paymentPlugin{
	//插件名称
    var $name    = '线下支付';
    //插件logo
    var $logo    = '';
    //版本号
    var $version = 0.6;
    //插件字符集
    var $charset = 'gbk';
	//提交的地址
    var $submitUrl = '';
	//html头部的字符集
    var $head_charset='gbk';
    //支持的地区
    var $supportArea     =  array("AREA_CNY");
    var $supportCurrency = array("CNY"=>"01");

    function getfields(){
        return array();
    }
}
?>
