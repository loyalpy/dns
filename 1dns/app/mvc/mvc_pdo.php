<?php
class tPdo extends PDO{
	private $dbinfo = array();
	private $queries = array();
	public function __construct($dbinfo = array()){
		$this->dbinfo = empty($dbinfo)?App::get_conf("db.main"):$dbinfo;
	  	try {
	  		$option = array();
	  		$option = $this->dbinfo['charset'] ? array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$this->dbinfo['charset']) : null;
	  		$option[PDO::ATTR_TIMEOUT]    = $this->dbinfo['timeout'];
	  		
	  		//是否持久化连接
	  		if(isset($this->dbinfo['persistent']) && $this->dbinfo['persistent'] == 0){
	  			$option[PDO::ATTR_PERSISTENT] = false;
	  		}else{
	  			$option[PDO::ATTR_PERSISTENT] = true;
	  		}
	  				  		
	  		parent::__construct(
	  			$this->get_dsn(),
	  			$this->dbinfo['user'],
	  			$this->dbinfo['passwd'],
	  			$option
	  		);
	  		parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  	}catch(Exception $e) {
	  		throw new Exception("Error NO:".$e->getCode().",Error:".$e->getMessage());
	  	}
	}
	//创建DSN
	private function get_dsn(){
		return $this->dbinfo['type'] . ':host=' . $this->dbinfo['host'] . ';dbname=' . $this->dbinfo['name'] . ';port=' . $this->dbinfo['port'];
	}
	//数据查询类型
	private function get_sqltype($sql){
		$str_array = explode(' ',trim($sql),2);
		return strtolower($str_array[0]);
	}
	//数据查询
	public function query($sql,$data = array()){
		$queries = array(
			"sql"    => $sql,
			"start"  => tTime::start(),
		);
		//取得SQL类型
		$sqltype = $this->get_sqltype($sql);		
		$readyconf = array('select','show','describe');
		//查询
		if(in_array($sqltype,$readyconf)){
			$res = $this->read($sql,$data);
		}else{//写操作
			$res = $this->write($sql,$data,$sqltype);
		}
		$queries['end']  = tTime::stop();
		$queries['time'] = tTime::end();
		//$this->queries[] = $queries;
		return $res;
    }
    //读SQL
	private function read($sql,$data = array()){
		$stmt = parent::prepare($sql);		
		$stmt->execute($data);			
		if($stmt->execute($data)){
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}else{
			throw new Exception("Query Error:{$sql}");
		}
	}
    //写SQL
	private function write($sql,$data = array(),$sqltype="update"){
		$stmt = parent::prepare($sql);		
		$result = $stmt->execute($data);
		if($result==true){
			switch($sqltype){
				case "insert":
				return (parent::lastInsertId()? parent::lastInsertId():true);
				break;
				default:
				return $stmt->rowCount();
				break;
			}
		}else{
			throw new Exception("Query {$sqltype} Error:{$sql}");
		}
	}
	//获取执行的SQL,
	public function queries(){
		return $this->queries;
	}
}
?>
