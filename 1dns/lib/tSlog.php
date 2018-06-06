<?php
require ROOT_PATH.'lib/slog/slog.php';
use think\org\Slog;
class tSlog{
    private static $init_stat = 0;
    public static function init(){
        if(self::$init_stat == 0){
            self::_log(array(
                    'enable'=>true,//是否打印日志的开关
                    'host'=>'115.231.24.235',//websocket服务器地址，默认localhost
                    'optimize'=>true,//是否显示利于优化的参数，如果运行时间，消耗内存等，默认为false
                    'show_included_files'=>false,//是否显示本次程序运行加载了哪些文件，默认为false
                    'error_handler'=>true,//是否接管程序错误，将程序错误显示在console中，默认为false
                    'force_client_id'=>'hzp_zfH5NbLn',//日志强制记录到配置的client_id,默认为空
                    'allow_client_ids'=>array("hzp_zfH5NbLn")////限制允许读取日志的client_id，默认为空,表示所有人都可以获得日志。
                ),'config');
            self::$init_stat = 1;
        }
    }
    public static function log($log,$type="log",$css=''){
        self::init();
        self::_log($log,$type,$css);
    }
    private static function _log($log,$type='log',$css=''){
        if(is_string($type)){
            $type=preg_replace_callback('/_([a-zA-Z])/',create_function('$matches', 'return strtoupper($matches[1]);'),$type);
            if(method_exists('\think\org\Slog',$type) || in_array($type,Slog::$log_types)){
               return  call_user_func(array('\think\org\Slog',$type),$log,$css);
            }
        }

        if(is_object($type) && 'mysqli'==get_class($type)){
               return Slog::mysqlilog($log,$type);
        }

        if(is_resource($type) && ('mysql link'==get_resource_type($type) || 'mysql link persistent'==get_resource_type($type))){
               return Slog::mysqllog($log,$type);
        }


        if(is_object($type) && 'PDO'==get_class($type)){
               return Slog::pdolog($log,$type);
        }

        throw new Exception($type.' is not SocketLog method');
    }
}
