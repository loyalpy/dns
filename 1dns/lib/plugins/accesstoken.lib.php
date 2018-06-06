<?php
namespace LaneWeChat\Core;
class AccessToken{

    /**
     * 获取微信Access_Token
     */
    public static function getAccessToken(){
        $cache_name = "weixin_access_token";
        $access_token_data = tCache::read($cache_name);
        if(empty($access_token_data)){
           $re = true;
        }else{
           if(($access_token_data['dateline']+$access_token_data['expires_in'])<($timestamp-300)){
              $re = true;
           }
        }
        if($re){
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WECHAT_APPID.'&secret='.WECHAT_APPSECRET;
            $access_token_data = Curl::callWebServer($url, '', 'GET');
            if(!isset($access_token_data['access_token'])){
                return Msg::returnErrMsg(MsgConstant::ERROR_GET_ACCESS_TOKEN, '获取ACCESS_TOKEN失败');
            }else{
                $access_token_data = array();
            }
            $access_token_data['dateline'] = time();
            tCache::write($cache_name, $access_token_data);
          }
          return $access_token_data['access_token'];
    }
}
?>