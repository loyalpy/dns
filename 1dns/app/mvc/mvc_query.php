<?php
class tQuery{
	private $db;
	private $sql=array('table'=>'','fields'=>'*','where'=>'','join'=>'','group'=>'','having'=>'','order'=>'','limit'=>'limit 500','distinct'=>'');
	private $table_pre='';
	public $total;
    public $totalpage;
    //构造函数
	public function __construct($name,$dbinfo=array()){
		$dbinfo = empty($dbinfo)?App::get_conf("db.main"):$dbinfo;
	
		$this->table_pre = isset($dbinfo['tablepre'])?$dbinfo['tablepre']:'';
		$this->table = $name;
		$this->db = tDB::get_db($dbinfo);
	}
    //设置表名
	public function set_table($name){
		if(strpos($name,',') === false){
			$this->sql['table']= $this->table_pre.$name;
		}else{
			$tables = explode(',',$name);
			foreach($tables as $key=>$value){
				$tables[$key] = $this->table_pre.trim($value);
			}
			$this->sql['table'] = implode(',',$tables);
		}
	}
    //获取表前缀
    public function get_tablepre(){
        return $this->table_pre;
    }
    //设置条件
    public function set_where($str){
        $this->sql['where']= 'where '.preg_replace('/from\s+(\S+)/i',"from {$this->table_pre}$1 ",$str);
    }
    //设置Join查询
    private function set_join($str){
		$this->sql['join'] = preg_replace('/(\w+)(?=\s+as\s+\w+(,|\)|\s))/i',"{$this->table_pre}$1 ",$str);
    }
    //魔法设置
	public function __set($name,$value){
		switch($name){
			case 'table':$this->set_table($value);break;
			case 'fields':$this->sql['fields'] = $value;break;
			case 'where':$this->set_where($value);break;
			case 'join':$this->set_join($value);break;
			case 'group':$this->sql['group'] = 'GROUP BY '.$value;break;
			case 'having':$this->sql['having'] = 'having '.$value;break;
			case 'order':$this->sql['order'] = 'order by '.$value;break;
			case 'limit':$value == 'all' ? '' : ($this->sql['limit'] = 'limit '.$value);break;
            case 'page':$this->sql['page'] =intval($value); break;
            case 'pagesize':$this->sql['pagesize'] =intval($value); break;
            case 'pagelength':$this->sql['pagelength'] =intval($value); break;
			case 'distinct':
			{
				if($value)$this->sql['distinct'] = 'distinct';
				else $this->sql['distinct'] = '';
				break;
			}
		}
	}
    //实现属性的直接取
	public function __get($name){
		if(isset($this->sql[$name]))return $this->sql[$name];
	}
    public function __isset($name){
        if(isset($this->sql[$name]))return true;
    }
    //取得查询结果
	public function find(){
        if(is_int($this->page) ){
            $sql="select $this->distinct $this->fields from $this->table $this->join $this->where $this->group $this->having $this->order";     
            
            $this->page = isset($this->page)?$this->page:1;
            $this->pagesize = isset($this->pagesize)?$this->pagesize:30;
            
            return $this->get_limit($sql,$this->page,$this->pagesize);
		}else{
            $sql="select $this->distinct $this->fields from $this->table $this->join $this->where $this->group $this->having $this->order $this->limit";
            return $this->db->query($sql);
        }
	}
	//取得分页查询
	public function get_limit($sql,$page = 1,$pagesize = 30){
		//当前页码
		$page = ($page<=0)?1:$page;
		$this->page = $page;
		//页面尺寸
		$pagesize = $pagesize?$pagesize:30;
		$this->pagesize = $pagesize;
		//查询总数据
        if(strpos($sql,'GROUP BY') === false) {
	        $endstr = strstr($sql,' from');
	        $endstr = preg_replace('/^(.*)order\s+by.+$/i','$1',$endstr);
        	$count=$this->db->query("select count(*) as total ".$endstr);
        }else{
        	$count=$this->db->query("select count(*) as total from (".$sql.") as G");
        }
        //获取总数据条数和总页码
		$this->total = isset($count[0]['total']) ? $count[0]['total'] : 0;
		$this->totalpage = floor(($this->total-1)/$pagesize)+1;
		//去查询        
		if($this->totalpage > 0){
			return $this->db->query($sql." limit ".($page-1)*$pagesize.",".$pagesize);
		}else{
			return array();
		}
	}
	//获取分页列表
    public function get_list($c = array(),$pageurl=""){
    	$result = array();
    	$c['where'] = isset($c['where'])?$c['where']:"1";
		$c['page'] = isset($c['page'])?$c['page']:1;
		$c['pagesize'] = isset($c['pagesize'])?$c['pagesize']:20;
	
		foreach($c as $k=>$v){
			$this->$k = $v;
		}
			
		$result['list'] = $this->find();
		$result['pagebar']   = $this->get_pagebar($pageurl);
		$result['total']     = $this->total;
		$result['totalpage'] = $this->totalpage;
		$result['yu']        = $result['total'] - ($c['pagesize']*($c['page']-1)+count($result['list']));
		//列表处理
		$_ids = array();
		if($result['list']){
			foreach($result['list'] as $k=>$v){
			}
		}
		return $result;
    }
	//分页展示
    public function get_pagebar($url=''){
        return tUtil::pagebar($this->total,$this->pagesize,$this->page,$url,0,3);
    }
    
}
?>
