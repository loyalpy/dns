<?php
class cls_notify{
	public function get_list($c = array(),$pageurl="",$mark_is_read=1){
		$result = array();
		$c['table'] = isset($c['table'])?$c['table']:"notify";
		$c['where'] = isset($c['where'])?$c['where']:"1";
		$c['page'] = isset($c['page'])?$c['page']:1;
		$c['pagesize'] = isset($c['pagesize'])?$c['pagesize']:20;
		$query = new tQuery($c['table']);
		foreach($c as $k=>$v){
			$query->$k = $v;
		}
		
		$result['list'] = $query->find();
		$result['pagebar'] = $query->get_pagebar($pageurl);
		$result['total'] = $query->total;
		$result['totalpage'] = $query->totalpage;
		//列表处理
		$_ids = array();
		if($result['list']){
			foreach($result['list'] as $key=>$v){
				$_ids[] = $v["id"];
			}
		}
		$result["ids"] = $_ids;
		if ($mark_is_read){
			M("notify")->set_data(array('inread'=>1));
			M("notify")->update($c['where']." AND inread=0");
		}
		return $result;
	}
	public function get_count($uid,$type="",$is_read = 0){
		$wherestr = "receive=$uid";
		$wherestr .= $type?" AND type='{$type}'":"";
		$wherestr .= " AND inread={$is_read}";
		return M("notify")->get_one($wherestr,"count(*)");
	}
	public function get($wherestr,$limit=20,$mark_is_read = true) {
		$notify_list = M('notify')->query($wherestr,"*","dateline DESC",$limit);
		if ($mark_is_read){
			M("notify")->set_data(array('inread'=>1));
			M("notify")->update($wherestr." AND inread=0");
		}
		return $notify_list;
	}
	public function del($ids) {
		$ids = is_array($ids) ? $ids : explode(',', $ids);
		if ( empty($ids) )
			return false;
		return M('notify')->del("id IN ('".implode("','",$ids)."')");
	}
	public function del_msg($ids) {
		$ids = is_array($ids) ? $ids : explode(',', $ids);
		if ( empty($ids) )
			return false;
		return M('notify_msg')->del("id IN ('".implode("','",$ids)."')");
	}
	public function send( $receive , $config, $from ) {
		return $this->__put( $receive , $config , $from );
	}	
	private function __put($receive,$config = array(),$from = false) {
		global $uid,$timestamp;
		$receive = $this->__parse_user( $receive ); if(!$receive) return false;
		$muid = ($from === false)?$uid:$from;
		$utype = isset($config['utype'])?$config['utype']:0;
		$ulevel = isset($config['ulevel'])?$config['ulevel']:0;
		$insys = isset($config['insys'])?$config['insys']:0;
		$inemail = isset($config['inemail'])?$config['inemail']:0;
		$insms = isset($config['insms'])?$config['insms']:0;
		$subject = isset($config['subject'])?$config['subject']:"";
		$message = isset($config['message'])?$config['message']:"";
		//优化大批量发送通知，讲数据切割处理，每次插入100条
		$receive	=	array_chunk($receive, 100)  ;
		foreach ($receive as $receive_chunck){
			foreach ($receive_chunck as $k=>$v){
				if($v && $v==$from) continue;
				$sql_arr[] = "($muid,$v,'{$utype}','{$ulevel}','{$insys}','{$inemail}','{$insms}','{$subject}','{$message}',$timestamp)";
			}
			if( $sql_arr ){
				$sql = "INSERT INTO @notify (`muid`,`tuid`,`utype`,`ulevel`,`insys`,`inemail`,`insms`,`subject`,`message`,`dateline`) values ".implode(',',$sql_arr);
				$result[] = Sq($sql);
			}
			unset($sql,$sql_arr,$receive_chunck);
		}
		return $result;
	}
	private function __parse_user($touid){
		if( is_numeric($touid) ){
			$sendto[] = $touid;
		}elseif ( is_array($touid) ){
			$sendto = $touid;
		}elseif (strpos($touid,',') !== false){
			$touid = array_unique(explode(',',$touid));
			foreach ($touid as $key=>$value){
				$sendto[] = $value;
			}
		}else{
			$sendto = false;
		}
		return $sendto;
	}
}
?>