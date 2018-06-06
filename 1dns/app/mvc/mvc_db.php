<?php
class tDB{
	//数据库对象
	public static $instance   = NULL;
	//实例key
	private static $ins_key = "";
	//获取数据库对象
	public static function get_db($dbinfo = array()){
		//dbinfo 
		$dbinfo  = empty($dbinfo)?App::get_conf("db.main"):$dbinfo;
		$key     = md5(implode("",$dbinfo));
		//数据库类型
		$pdo     = isset($dbinfo['pdo']) ? $dbinfo['pdo'] : 1;

		//单例模式
		if(self::$instance != NULL && is_object(self::$instance) && $key === self::$ins_key){
			return self::$instance;
		}else{			
			self::$ins_key = $key;
			if($pdo == 1){
				self::$instance = new tPdo($dbinfo);
			}else{
				$db_type = isset($dbinfo['type']) ? $dbinfo['type'] : "mysql";
				switch($db_type){
					case "mysql":
					default:
						self::$instance = new tMysql($dbinfo);
					break;
				}
			}
			return self::$instance;
		}
	}
    private function __construct(){}
    private function __clone(){}
}
?>