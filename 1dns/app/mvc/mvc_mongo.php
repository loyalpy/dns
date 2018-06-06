<?php
class tMongo{
	private static $conf = array(
		'dsn'  =>  'mongodb://localhost:27017/test',
        'persist'   =>  true,
        'persist_key'   =>  'mongoqb',
        'replica_set'   =>  false,
        'query_safety'  =>  'safe'
		);
	private static $connect  = null;
	private static $instance = null;
	private static $dsn      = "";
	private static $dbname   = "";

	private static $persist  = true;
	private static $persist_key = "mongodb";
	private static $replica_set = false;
	private static $query_safety= "safe";

	//实例key
	private static $ins_key = "";
	//获取连接对象
	private static function get_connect($dbinfo = array()){
		//单例模式		
		if(self::init_conf($dbinfo) && self::$connect != NULL){
			return self::$connect;
		}
		$options = array();
        if (self::$persist === true) {
            $options['persist'] = self::$persist_key;
        }

        if (self::$replica_set) {
            $options['replicaSet'] = self::$replica_set;
        }
        try {
            if (phpversion('Mongo') >= 1.3){
                unset($options['persist']);
                self::$connect = new MongoClient(self::$dsn, $options);
            }else{
                self::$connect = new Mongo(self::$dsn, $options);
            }
            return self::$connect;
        }catch (MongoConnectionException $Exception) {
            throw new Exception('Unable to connect to MongoDB: ' .$Exception->getMessage());
        }
	}
    //获取数据对象
    private static function get_db($dbinfo = array()){     
		if(self::init_conf($dbinfo) && self::$instance != NULL){
			return self::$instance;
		}
		try{
			self::$instance = self::get_connect($dbinfo)->{self::$dbname};
		}catch(MongoConnectionException $Exception){
			throw new Exception('Unable to select to db: ' .$Exception->getMessage());
		}
        return self::$instance;
    }
    // init conf
    private static function init_conf($dbinfo = array()){
    	$dbinfo = empty($dbinfo)?App::get_conf("db.mongo"):$dbinfo;
    	$ret = md5(implode("",$dbinfo)) === self::$ins_key;
    	if( !$ret ){
			//处理配置--------------------------------------
			self::$conf = $dbinfo;
	        self::$dsn  = self::$conf['dsn'];

	        if (empty(self::$dsn)) {
	            throw new Exception('The DSN is empty');
	        }

	        self::$persist      = self::$conf['persist'];
	        self::$persist_key  = self::$conf['persist_key'];
	        self::$replica_set  = self::$conf['replica_set'];
	        self::$query_safety = self::$conf['query_safety'];

	        $parts = parse_url(self::$dsn);
	        if (!isset($parts['path']) OR str_replace('/', '', $parts['path']) === '') {
	            throw new Exception('The database name must be set in the DSN string');
	        }
	        self::$dbname  = str_replace('/', '', $parts['path']);        
	        self::$ins_key = md5(implode("",$dbinfo));
	    }
        return $ret;      
    }

    //查询
	public static function get($collection = '',$where = array(),  $limit = 999999, $sort = array(), $fields = array(), $offset = 0, $return_cursor = false, $dbinfo = array()){
        if (empty($collection)) {
            return array();
        }
        $cursor = self::get_db($dbinfo)
                            ->{$collection}
                            ->find($where, $fields)
                            ->limit($limit)
                            ->skip($offset)
                            ->sort($sort);

        // Return the raw cursor if wanted
        if ($return_cursor === true) {
            return $cursor;
        }

        $result = array();

        while ($cursor->hasNext()) {
            try {
                $result[] = $cursor->getNext();
            }catch (MongoCursorException $Exception) {
                throw new Exception($Exception->getMessage());
            }
        }

        return $result;
    }

	//查询
	public static function get_one($collection = '',$where = array(), $fields = array(), $dbinfo = array()){
        if (empty($collection)) {
            return array();
        }
        $result = self::get_db($dbinfo)
                        ->{$collection}
                        ->findOne($where, $fields);

        return $result;
    }

    //add
	public static function add($collection = '', $insert = array(), $options = array(), $dbinfo = array()){
        if (empty($collection)) {
            return false;
        }
        if (count($insert) === 0 OR  !is_array($insert)) {
            return false;
        }
        $options = array_merge(
            //array($this->_querySafety => true),
            array(),
            $options
        );
        try {
            self::get_db($dbinfo)
                ->{$collection}
                ->insert($insert, $options);

            if (isset($insert['_id'])) {
                return $insert['_id'];
            }else{
                return false;
            }
        }catch (MongoCursorException $Exception) {
            throw new Exception('Insert of data into MongoDB failed: ' .$Exception->getMessage());
        }
    }

    //addmore
	public static function add_more($collection = '', $insert = array(), $options = array(), $dbinfo = array()){
        if (empty($collection)) {
            return false;
        }
        if (count($insert) === 0 OR  !is_array($insert)) {
            return false;
        }
        $options = array_merge(
            //array($this->_querySafety => true),
            array(),
            $options
        );
        try {
            self::get_db($dbinfo)
                ->{$collection}
                ->batchInsert($insert, $options);

            if (isset($insert['_id'])) {
                return $insert['_id'];
            }else{
                return false;
            }
        }catch (MongoCursorException $Exception) {
            throw new Exception('Insert of data into MongoDB failed: ' .$Exception->getMessage());
        }
    }

	//save
	public static function save($collection = '', $insert = array(), $options = array(), $dbinfo = array()){
        if (empty($collection)) {
            return false;
        }
        if (count($insert) === 0 OR  !is_array($insert)) {
            return false;
        }
        $options = array_merge(
            //array($this->_querySafety => true),
            array(),
            $options
        );
        try {
            self::get_db($dbinfo)
                ->{$collection}
                ->save($insert, $options);

            if (isset($insert['_id'])) {
                return $insert['_id'];
            }else{
                return false;
            }
        }catch (MongoCursorException $Exception) {
            throw new Exception('Insert of data into MongoDB failed: ' .$Exception->getMessage());
        }
    }

    //更新
	public static function update($collection = '', $where = array(), $updata = array(), $options = array(), $dbinfo = array()){
        if (empty($collection)) {
            return false;
        }
        if (count($updata) === 0) {
            return false;
        }
        try {
            $options = array_merge(array(self::$query_safety => true,'multiple' => false), $options);
            $result = self::get_db($dbinfo)->{$collection}->update($where,$updata,$options);
           
            if ($result['updatedExisting'] > 0) {
                return $result['updatedExisting'];
            }
            return false;
        }catch (MongoCursorException $Exception) {
            throw new Exception('Update of data into MongoDB failed: ' . $Exception->getMessage());
        }
    }

     //更新所有
    public static function update_all($collection = '', $where = array(), $updata = array(), $options = array(), $dbinfo = array()){
        if (empty($collection)) {
           return false;
        }
        if (count($updata) === 0) {
           return false;
        }
        try {
            $options = array_merge(array(self::$query_safety => true,'multiple' => true), $options);
            $result = self::get_db($dbinfo)->{$collection}->update($where,$updata,$options);

            if ($result['updatedExisting'] > 0) {
                return $result['updatedExisting'];
            }
            return false;
        }catch (MongoCursorException $Exception) {
            throw new Exception('Update of data into MongoDB failed: ' . $Exception->getMessage());
        }
    }

    //删除
 	public static function del($collection = '',$where = array(), $dbinfo = array()){
        if (empty($collection)) {
            return false;
        }
        try {
            self::get_db($dbinfo)->{$collection}->remove($where,
             array(self::$query_safety => true, 'justOne' => true));
            return true;
        }catch (MongoCursorException $Exception) {
            throw new Exception('Delete of data into MongoDB failed: ' .$Exception->getMessage());
        }
    }
	//删除所有
 	public static function del_all($collection = '',$where = array(), $dbinfo = array()){
        if (empty($collection)) {
            return false;
        }
        try {
            self::get_db($dbinfo)->{$collection}->remove($where,
             array(self::$query_safety => true, 'justOne' => false));
            return true;
        }catch (MongoCursorException $Exception) {
            throw new Exception('Delete of data into MongoDB failed: ' .$Exception->getMessage());
        }
    }

    //添加索引
    public static function add_index($collection = '', $fields = array(), $options = array(),$dbinfo = array()){
        if (empty($collection)) {
            return false;
        }

        if (empty($fields) OR ! is_array($fields)) {
            return false;
        }

        foreach ($fields as $field => $value) {
            if($value === -1 OR $value === false OR
             strtolower($value) === 'desc') {
                $keys[$field] = -1;
            } elseif($value === 1 OR $value === true OR
             strtolower($value) === 'asc') {
                $keys[$field] = 1;
            } else {
                $keys[$field] = $value;
            }
        }

        try {
            self::get_db($dbinfo)->{$collection}->ensureIndex($keys, $options);
            return true;
        }catch (Exception $e) {
            throw new Exception('An error occurred when trying to add an index to MongoDB Collection: ' . $e->getMessage());
        }
    }

    //移除索引
    public static function remove_index($collection = '', $keys = array(), $dbinfo = array()){
        if (empty($collection)) {
            return false;
        }
        if (empty($keys) OR ! is_array($keys)) {
            return false;
        }

        $ret = self::get_db($dbinfo)->{$collection}->deleteIndex($keys);
       	return $ret;
    }

    //移除所有索引
    public static function remove_all_index($collection = '',$dbinfo = array()){
        if (empty($collection)) {
            return false;
        }
        return self::get_db($dbinfo)->{$collection}->deleteIndexes();
    }

    public static function get_index($collection = '',$dbinfo = array()){
        if (empty($collection)) {
            return false;
        }

        return self::get_db($dbinfo)->{$collection}->getIndexInfo();
    }
    //计算命令
    public static function group($collection = '',$groups=array(),$where1 = array(),$where2 = array(),$fields = array(),$sorts = array(),$dbinfo = array()){
         if (empty($collection)) {
            return array();
        }
        $ops = array();        
        if($where1){
            $ops[] = array('$match'=>$where1);
        }
        if($groups){
            $ops[] = array('$group'=>$groups);
        }
        if($where2){
            $ops[] = array('$match'=>$where2);
        }
        if($fields){
            $ops[] = array('$project'=>$fields);
        }
        if($sorts){
            $ops[] = array('$sort'=>$sorts);
        }
        return self::get_db($dbinfo)->{$collection}->aggregate($ops);
    }

    //执行命令
    public static function command($query = array(),$dbinfo = array()) {
        try {
            $execute = self::get_db($dbinfo)->command($query);
            return $execute;
        }catch (MongoCursorException $Exception) {
            throw new Exception('MongoDB command failed to execute: ' .$Exception->getMessage());
        }
    }

    //count
    public static function count($collection = '', $where = array(), $limit = 999999, $offset = 0, $dbinfo = array()){
    	if (empty($collection)) {
            throw new Exception('In order to retrieve a count of
             documents from MongoDB, a collection name must be passed');
        }
        $count = self::get_db($dbinfo)
                     ->{$collection}
                     ->find($where)
                     ->limit($limit)
                     ->skip($offset)
                     ->count();
        return $count;
    }


    //drop db
    public static function drop_db($dbinfo = array()){
        if (empty($database)) {
           throw new Exception('Failed to drop MongoDB database because name is empty');
        }else{
            try {
                //self::get_connect($dbinfo)->{self::$dbname}->drop();
                return true;
            }catch(Exception $Exception) {
                throw new Exception('Unable to drop Mongo database `' .self::$dbname . '`: ' . $Exception->getMessage());
            }
        }
    }

    //drop collection
    public static function drop_collection($collection = '',$dbinfo = array()){
        if (empty($collection)) {
            throw new Exception('Failed to drop MongoDB collection because
             collection name is empty', 500);
        } else {
            try {
                //self::get_connect($dbinfo)->{self::$dbname}->{$collection}->drop();
                return true;
            }catch (Exception $Exception) {
                throw new Exception('Unable to drop Mongo collection `' .$collection . '`: ' . $Exception->getMessage(), 500);
            }
        }
    }

}
?>