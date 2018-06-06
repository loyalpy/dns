<?php
class tModel{
	//数据库操作对象
	private $db = NULL;
	//数据表名称
	private $table_name = '';
	//要更新的表数据,key:对应表字段; value:数据;
	private $table_data = array();
	//构造函数,创建数据库对象
	public function __construct($table_name,$dbinfo=array()){
		$dbinfo = empty($dbinfo)?App::get_conf("db.main"):$dbinfo;
		
		$this->db = tDB::get_db($dbinfo);
		$tablepre = isset($dbinfo['tablepre']) ? $dbinfo['tablepre'] : '';
		//多表处理
		if(stripos($table_name,'@')){
			$this->table_name = $tablepre.preg_replace("'@'si",$tablepre,$table_name);
		}else{//单表处理
			$this->table_name = $tablepre.$table_name;
		}
	}
    //设置要更新的表数据
	public function set_data($data){
		if(is_array($data)){
			$this->table_data = $data;
			return $this;
		}else{
			return false;
		}
	}
	//更新
	public function update($where,$except=array()){
		$except = is_array($except) ? $except : array($except);
		//获取更新数据
		$table_obj  = $this->table_data;
		$update_str = '';
		$where     = (strtolower($where) == 'all') ? '' : ' WHERE '.$where;
		foreach($table_obj as $key => $val){
			if($update_str != '') $update_str.=' , ';
			if(!in_array($key,$except))
				$update_str.= '`'.$key.'` = \''.$val.'\'';
			else
				$update_str.= '`'.$key.'` = '.$val;
		}
		$sql = 'UPDATE '.$this->table_name.' SET '.$update_str.$where;
		return $this->db->query($sql);
	}
	//添加
	public function add(){
		//获取插入的数据
		$table_obj = $this->table_data;
		$insert_col = array();
		$insert_val = array();
		foreach($table_obj as $key => $val){
			$insert_col[] = '`'.$key.'`';
			$insert_val[] = '\''.$val.'\'';
		}
		$sql = 'INSERT INTO '.$this->table_name.' ( '.join(',',$insert_col).' ) VALUES ( '.join(',',$insert_val).' ) ';
		return $this->db->query($sql);
	}
	//添加多条记录
	public function add_more($data = array()){
		$insert_col = array();
		$insert_val = array();
		foreach($data as $k1 => $v1){
			if($k1 == 0){
				foreach($v1 as $k2 => $v2){
					$insert_col[] = '`'.$k2.'`';
				}
			}
    		$insert_val[] = "('".implode("','",$v1)."')";
		}
		$sql = 'INSERT INTO '.$this->table_name.' ( '.join(',',$insert_col).' ) VALUES'.join(",",$insert_val);
		return $this->db->query($sql);
	}
	//删除
	public function del($where){
		$where = (strtolower($where) == 'all') ? '' : ' WHERE '.$where;
		$sql   = 'DELETE FROM '.$this->table_name.$where;
		return $this->db->query($sql);
	}
	//获取单条数据
	public function get_row($where = false,$cols = '*',$order_by=false){
		$result = $this->query($where,$cols,$order_by,1);
		if(empty($result)){
			return array();
		}else{
			return $result[0];
		}
	}
	//获取单个字段数据
	public function get_one($where = false,$cols = '*',$order_by=false){
		$result = $this->get_row($where,$cols,$order_by);
		if(empty($result)){
			return 0;
		}else{
			return current($result);
		}
	}
	//获取多条数据
	public function query($where=false,$cols='*',$order_by=false,$limit=500){
		//字段拼接
		if(is_array($cols)){
			$colstr = join(',',$cols);
		}else{
			$colstr = ($cols=='*' || !$cols) ? '*' : $cols;
		}
		$sql = 'SELECT '.$colstr.' FROM '.$this->table_name;
		//条件拼接
		if($where != false) $sql.=' WHERE '.$where;
		//排序拼接
		if($order_by != false){
			$sql.= ' ORDER BY '.$order_by;
		}
		//条数拼接
		if(tValidate::is_int($limit)){
			$limit = intval($limit);
			$limit = $limit ? $limit : 500;
			$sql.=' limit ' . $limit;
		}else{
			$sql.=' limit ' . $limit;
		}
		return $this->db->query($sql);
	}
}
?>