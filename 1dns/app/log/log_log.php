<?php
/**
 * @class log
 * @brief 日志记录类
 */
class tLog{
	private $log_type = 'file';//默认日志类型
	private $log     = null;
	private $log_info = array(
		'error'     => array('table' => 'log_error',    'cols' => array('file','line','content')),
		'sql'       => array('table' => 'log_sql',      'cols' => array('content','runtime')),
		'operation' => array('table' => 'log_operation','cols' => array('uid','author','action','content','ip')),
	);
    private $log_class = array('file' => 'tFileLog' , 'db' => 'tDBLog');
	//获取日志对象
	public function __construct($log_type = ''){
    	if($log_type === ''){
    		$log_type = isset(App::$config['logs']['type']) ?App::$config['logs']['type'] : $this->log_type;
    	}
		$this->_instance($log_type);
	}
	/*
	  * @brief   生成日志处理对象，包换各种介质的日志处理对象,单例模式
     * @logType string $logType 日志类型
     * @return  object 日志对象
     */
    public function _instance($log_type = ''){
    	$class_name = isset($this->log_class[$log_type]) ? $this->log_class[$log_type] : '';
    	if(!class_exists($class_name)){
    		throw new tException('the log class is not exists',403);
    	}

    	if(!$this->log instanceof $class_name){
    		$this->log = new $class_name;
    	}
    	return $this->log;
    }
	//写入日志
	public function write($type,$logs = array()){
		$log_info = $this->log_info;
		if(!isset($log_info[$type])){
			return false;
		}
		$class_name = get_class($this->log);
		switch($class_name){
			//文件日志
			case "tFileLog":
			{
				//设置路径
				$path = App::get_conf('com.logs.path');
				$path     = $path? $path: 'cache/weblog';
				$file_name = rtrim($path,'\\/').'/'.$type.'/'.date('Y/m').'/'.date('d').'.log';
				$this->log->set_path($file_name);

				$logs     = array_merge(array(tTime::get_datetime()),$logs);
				return $this->log->write($logs);
			}
			break;
			//数据库日志
			case "tDBLog":
			{
				$content['dateline'] = tTime::get_now();
				$table_name          = $log_info[$type]['table'];

				foreach($log_info[$type]['cols'] as $key => $val){
					$content[$val] = isset($logs[$val]) ? $logs[$val] : (isset($logs[$key]) ? $logs[$key] : '');
				}
				$this->log->set_tablename($table_name);
				return $this->log->write($content);
			}
			break;

			default:
			return false;
			break;
		}
	}
}
//日志接口
interface tILog{
    /**
     * @brief 实现日志的写操作接口
     * @param array  $logs 日志的内容
     */
    public function write($logs = array());
}