<?php
/**
 * @class tDBLog
 * @brief 数据库格式日志
 */
class tDBLog implements tILog{
	//记录的数据表名
	private $table_name = '';
	/**
	 * @brief 构造函数
	 * @param string 要记录的数据表
	 */
	public function __construct($table_name = ''){
		$this->table_name = $table_name;
	}
	/**
	 * @brief 向数据库写入log
	 * @param array  log数据
	 * @return bool  操作结果
	 */
	public function write($logs = array()){
		if(!is_array($logs) || empty($logs)){
			throw new tException('the $logs parms must be array');
		}

		if($this->table_name == ''){
			throw new tException('the tableName is undefined');
		}

		$logObj = new tModel($this->table_name);
		$logObj->set_data($logs);
		$result = $logObj->add();
		if($result){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * @brief 设置要写入的数据表名称
	 * @param string $table_name 要记录的数据表
	 */
	public function set_tablename($table_name){
		$this->table_name = $table_name;
	}
}
