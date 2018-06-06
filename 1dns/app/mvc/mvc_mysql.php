<?php
class tMysql{
	//数据库连接资源
	private $link_res = false;
	private $dbinfo = array();
	private $queries = array();
	public function __construct($dbinfo = array()){
		$this->dbinfo = empty($dbinfo)?App::get_conf("db.main"):$dbinfo;
	}
	//数据库链接
	public function connect(){
	  	$this->link_res = mysql_connect(($this->dbinfo['host'].":".$this->dbinfo['port']),$this->dbinfo['user'],$this->dbinfo['passwd']);
	  	if(is_resource($this->link_res)){
		  	mysql_select_db($this->dbinfo['name'],$this->link_res);
		  	//字符集
		  	$db_charset = isset($this->dbinfo['charset'])?$this->dbinfo['charset'] : 'utf-8';
		  	mysql_query("SET NAMES '".$db_charset."'");
		  	if(isset($this->dbinfo['db_mode']) && $this->dbinfo['db_mode'] != ''){
		  		mysql_query("SET SESSION sql_mode = '".$this->dbinfo['db_mode']."' ");
		  	}else{
		  		mysql_query("SET SESSION sql_mode = '' ");
		  	}
	  	}else{
	  		return false;
	  	}
	}
	//数据查询类型
	private function get_sqltype($sql){
		$str_array = explode(' ',trim($sql),2);
		return strtolower($str_array[0]);
	}
	//数据查询
	public function query($sql){
		$queries = array(
			"sql"    => $sql,
			"start"  => tTime::start(),
		);
		//取得SQL类型
        $sqltype = $this->get_sqltype($sql);
		if($this->link_res === false || !is_resource($this->link_res)){
			$this->connect();
        }
        if(is_resource($this->link_res)){
        	//读操作
			$readyconf = array('select','show','describe');
			if(in_array($sqltype,$readyconf)){
				$res = $this->read($sql,MYSQL_ASSOC);
			}else{//写操作
				$res = $this->write($sql,$sqltype);
			}
        }else{
        	$res = false;
        }
        $queries['end']  = tTime::stop();
        $queries['time'] = tTime::end();
        $this->queries[] = $queries;
        return $res;
    }
    //读SQL
	private function read($sql,$type=MYSQL_BOTH){
		$result = array();
		$resource = mysql_query($sql,$this->link_res);
		if($resource){
			while($data = mysql_fetch_array($resource,$type)){
				$result[] = $data;
			}
			return $result;
		}else{
			throw new Exception("{$sql}\n -- ".mysql_error($this->link_res));
			return $result;
		}
	}
    //写SQL
	private function write($sql,$sqltype="update"){
		$result = mysql_query($sql,$this->link_res);
		if($result==true){
			switch($sqltype){
				case "insert":
				return mysql_insert_id();
				break;
				default:
				return mysql_affected_rows();
				break;
			}
		}else{
			throw new Exception($sql."\n -- ".mysql_error($this->link_res));
			return false;
		}
	}
	//获取执行的SQL,
	public function queries(){
		return $this->queries;
	}
}
?>
