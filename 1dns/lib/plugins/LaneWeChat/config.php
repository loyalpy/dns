<?php
namespace LaneWeChat;
/**
 * 系统主配置文件.
 * @Created by Lane.
 * @Author: lane
 * @Mail lixuan868686@163.com
 * @Date: 14-8-1
 * @Time: 下午1:00
 * @Blog: Http://www.lanecn.com
 */
//版本号
define('LANEWECHAT_VERSION', '1.4');
define('LANEWECHAT_VERSION_DATE', '2014-11-05');

/*
 * 服务器配置，详情请参考@link http://mp.weixin.qq.com/wiki/index.php?title=接入指南
 */
define("WECHAT_URL", 'https://api.bajiedns.com/WeiXin');
define('WECHAT_TOKEN', '201605058899');
define('ENCODING_AES_KEY', "yGlvbhUmMsgdp7UOmycsoLCfEi1U6PmlLHuo17ZiCjr");

/*
 * 开发者配置
 */
define("WECHAT_APPID", 'wx5e8370aa438a29d6');
define("WECHAT_APPSECRET", '4f80359cfbbe71a7a91d58f509c2792d');

/*
 * SAE平台配置
 */
define("HTTP_ACCESSKEY", '');
define("HTTP_APPNAME", '');
?>