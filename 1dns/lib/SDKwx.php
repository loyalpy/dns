<?php
class SDKwx{
   private static $path = "lib/plugins/LaneWeChat/";
   private static $data = array();
   //初始加载
	public static function init(){
		//引入配置文件
      include_once ROOT_PATH.self::$path.'config.php';
      //引入自动载入函数
      include_once ROOT_PATH.self::$path.'autoloader.php';
      //调用自动载入函数
      LaneWeChat\AutoLoader::register();
	}
   //处理数据
   public static function init_data(){
      if(isset($GLOBALS['HTTP_RAW_POST_DATA'])){
         //接受并解析微信中心POST发送XML数据
         $xml = (array) simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);
         //将数组键名转换为小写
         self::$data = array_change_key_case($xml, CASE_LOWER);

     }
   }
   //执行请求
   public static function exe_req(){
      include_once ROOT_PATH."lib/SDKwxreq.php";
      if(self::$data){
         echo LaneWeChat\Core\SDKwxreq::switchType(self::$data);
      }
   }
   //验证请求
   public static function chk_setting(){
         $signature = R("signature","string");
         $timestamp = R("timestamp","string");
         $nonce     = R("nonce","string");        
                                
         $token  = WECHAT_TOKEN;
         $tmpArr = array($token, $timestamp, $nonce);
         sort($tmpArr, SORT_STRING);
         $tmpStr = implode($tmpArr);
         $tmpStr = sha1( $tmpStr );
                
         if( $tmpStr == $signature ){
            echo $_GET['echostr'];
            return true;
         }else{
            return false;
         }
   }

   //生成二维码
   public static function qrcode($scene_id){
      header('Content-type: image/jpg');
      self::init();
      $ticket = LaneWeChat\Core\Popularize::createTicket(1, 1800, $scene_id);
      $ticket = $ticket['ticket'];
      $qrcode = LaneWeChat\Core\Popularize::getQrcode($ticket);
      return $qrcode;
   }
   //获取code URL
   public static function get_login_url($sid="",$uri="",$qrcode = true){
      self::init();
      $uri = "/login?sid={$sid}".($uri?"&{$uri}":"");      
      $s = LaneWeChat\Core\WeChatOAuth::getCode("https://wx.bajiedns.com{$uri}", 1,'snsapi_userinfo');
      if($qrcode == true){
         require_once ROOT_PATH.'lib/tQrcode.php';
         $qrcode_obj = new tQrcode();
         return U().$qrcode_obj->get($s);
      }else{
         return $s;
      }
      
   }
   //获取授权用户基本信息

   //获取用户基本信息
   public static function get_userinfo($openid){
      self::init();
      $wx_userinfo = LaneWeChat\Core\UserManage::getUserInfo($openid);
      $data = array(
         "wx_openid"   => $openid,
         "wx_unionid"  => "",
         "wx_nickname" => "",
         "wx_avatar"   => "",
         "wx_sex"      => 0,
         "wx_city"     => ""
      );
      if(isset($wx_userinfo['openid'])){
         $data['wx_unionid']  = isset($wx_userinfo['unionid'])?$wx_userinfo['unionid']:"";
         $data['wx_nickname'] = $wx_userinfo['nickname'];
         $data['wx_avatar']   = $wx_userinfo['headimgurl'];
         $data['wx_sex']      = $wx_userinfo['sex'];
         $data['wx_city']     = "{$wx_userinfo['country']} {$wx_userinfo['province']} {$wx_userinfo['city']}";
      }
      return $data;
   }
   //获取open_id
   public static function get_userinfo_bycode($code){
      self::init();
      $res = LaneWeChat\Core\WeChatOAuth::getAccessTokenAndOpenId($code);
      $data = array(
         "wx_openid"   => isset($res['openid'])?$res['openid']:"",
         "wx_unionid"  => "",
         "wx_nickname" => "",
         "wx_avatar"   => "",
         "wx_sex"      => 0,
         "wx_city"     => ""
      );
      $access_token = isset($res['access_token'])?$res['access_token']:"";
      $openid = $data['wx_openid'];
      if($openid && $access_token){
            $wx_userinfo = LaneWeChat\Core\WeChatOAuth::getUserInfo($access_token,$openid);
            if(isset($wx_userinfo['openid'])){
               $data['wx_unionid']  = isset($wx_userinfo['unionid'])?$wx_userinfo['unionid']:"";
               $data['wx_nickname'] = $wx_userinfo['nickname'];
               $data['wx_avatar']   = $wx_userinfo['headimgurl'];
               $data['wx_sex']      = $wx_userinfo['sex'];
               $data['wx_city']     = "{$wx_userinfo['country']} {$wx_userinfo['province']} {$wx_userinfo['city']}";
            }
      }
      return $data;
   }
   //发送模板信息
   public static function send_tpl($data, $touser, $templateId, $url="http://wx.bajiedns.com", $topcolor='#FF0000'){
      self::init();
      $res = LaneWeChat\Core\TemplateMessage::sendTemplateMessage($data, $touser, $templateId, $url, $topcolor='#FF0000');
      return $res;
   }
   //设置菜单
   public static function set_menu(){
      self::init();
      /**
       * 自定义菜单
       */
      //设置菜单
      $menuList = array(
         array('id'=>'1', 'pid'=>'',  'name'=>'监控', 'type'=>'', 'code'=>'key_1'),
         array('id'=>'2', 'pid'=>'',  'name'=>'官网', 'type'=>'', 'code'=>'key_2'),
         array('id'=>'3', 'pid'=>'',  'name'=>'关于', 'type'=>'', 'code'=>'key_3'),

         array('id'=>'11', 'pid'=>'1',  'name'=>'产品介绍', 'type'=>'view', 'code'=>'http://www.bajiedns.com/product/index'),
         //array('id'=>'12', 'pid'=>'1',  'name'=>'监控报警', 'type'=>'view', 'code'=>'http://account.bajiedns.com/monitor/warning'),

         array('id'=>'21', 'pid'=>'2',  'name'=>'官网', 'type'=>'view', 'code'=>'http://www.bajiedns.com/'),
         array('id'=>'22', 'pid'=>'2',  'name'=>'一键锁定账号', 'type'=>'click', 'code'=>'LOCK_ACCOUNT'),
         array('id'=>'23', 'pid'=>'2',  'name'=>'一键解锁账号', 'type'=>'click', 'code'=>'UNLOCK_ACCOUNT'),
         array('id'=>'23', 'pid'=>'2',  'name'=>'域名工具', 'type'=>'view', 'code'=>'http://www.bajiedns.com/tools/index'),

         array('id'=>'31', 'pid'=>'3',  'name'=>'关于八戒DNS', 'type'=>'view', 'code'=>'http://wx.bajiedns.com/mobile/views/about.html'),
         array('id'=>'32', 'pid'=>'3',  'name'=>'八戒DNS帮助', 'type'=>'view', 'code'=>'http://wx.bajiedns.com/mobile/views/helper.html'),
         array('id'=>'33', 'pid'=>'3',  'name'=>'绑定账号', 'type'=>'view', 'code'=>self::get_login_url("","",false)),
         array('id'=>'34', 'pid'=>'3',  'name'=>'最新活动', 'type'=>'view', 'code'=>'http:/www.bajiedns.com/cms/blog/'),
    
          //array('id'=>'11', 'pid'=>'1', 'name'=>'发送位置', 'type'=>'location_select', 'code'=>'key_11'),
      );
      dump($menuList);
      return LaneWeChat\Core\Menu::setMenu($menuList);
   }
}
?>