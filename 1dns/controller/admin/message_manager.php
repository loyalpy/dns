<?php
/**
 * 产品管理
 * by Thinkhu 2014 
 */
class message_manager extends UCAdmin{
	//系统通知信息
	public function notify(){
		global $timestamp;
		$condi = Ra('startdate,enddate,keyword',"string,string,string");
		$c = array();
		$wherestr = "1";
		$orderstr = "dateline DESC";
		$pageurl = U("/message_manager/notify?");
		$condi['startdate'] = $condi['startdate']?$condi['startdate']:tTime::get_datetime('Y-m-d',$timestamp-30*86400);
		if(!empty($condi)){
			  foreach($condi as $k=>$v){
			     $pageurl .= "&{$k}=".urlencode($v);
			     $this->assign($k,$v);
			     switch($k){
			     	case "startdate":
			     		$wherestr .= $v?(" AND (dateline >= '".strtotime($v)."')"):"";
			     	    break;
			     	case "enddate":
			     		$wherestr .= $v?(" AND (dateline <= '".strtotime($v)."')"):"";
			     	    break;
			        case 'keyword':
				        $v = ($v == '关键词')?'':$v;
				        $wherestr .= $v?" AND (message  LIKE '%$v%' OR subject LIKE '%$v%')":"";
				        break;
			        default:
			            break;
			     }
			  }
		}
		$c['where'] = $wherestr;
		$c['order'] = $orderstr;
	    $c['page'] = R("page","int");
	    $c['pagesize'] = 30;
	    
		$this->datalist = Q("notify")->get_list($c,$pageurl);
		$this->display();
	}
	//notify
	public function notify_del(){
		$id = R("id","string");
		if(empty($id))tAjax::json_error("删除ID不能为空！");
		$ret = C("notify")->del($id);
		if($ret){
			tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>"reload"));
		}else{
			tAjax::json_error("删除失败！");
		}
	}
	//notify
	public function notify_send(){
		if(tUtil::check_hash()){
			$receives = R("receives","string");
			$config = array(
				"subject"  =>  R("subject","string"),
				"message"  =>  R("message","string"),
				"insys"    =>  R("insys","int",0),
				"inemail"  =>  R("inemail","int",0),
				"insms"    =>  R("insms","int",0)
			);
			if(empty($config['subject']))tAjax::json_error("消息主题不能为空！");
			if(empty($receives)){
				$receives = 0;
				$utl = R("utl","string");
				if(empty($utl)){
					$config['utype'] = $config['ulevel'] = 0;
				}else{
					$tmp = explode(":",$utl);
					$config['utype']  = $tmp[0];
					$config['ulevel'] = $tmp[1];
				}
			}else{
				$config['utype'] = $config['ulevel'] = 0;
			}
			$is = C("notify")->send($receives,$config,0);
			if($is){
				tAjax::json(array("error"=>0,"message"=>"发送成功！","callback"=>"reload"));
			}else{
				tAjax::json_error("发送消息失败！");
			}
		}
	}
	//私信信息
	public function notify_msg(){
		global $timestamp;
		$condi = Ra('startdate,enddate,keyword',"string,string,string");
		$c = array();
		$wherestr = "1";
		$orderstr = "dateline DESC";
		$pageurl = U("/message_manager/notify_msg?");
		$condi['startdate'] = $condi['startdate']?$condi['startdate']:tTime::get_datetime('Y-m-d',$timestamp-30*86400);
		if(!empty($condi)){
			  foreach($condi as $k=>$v){
			     $pageurl .= "&{$k}=".urlencode($v);
			     $this->assign($k,$v);
			     switch($k){
			     	case "startdate":
			     		$wherestr .= $v?(" AND (dateline >= '".strtotime($v)."')"):"";
			     	    break;
			     	case "enddate":
			     		$wherestr .= $v?(" AND (dateline <= '".strtotime($v)."')"):"";
			     	    break;
			        case 'keyword':
				        $v = ($v == '关键词')?'':$v;
				        $wherestr .= $v?" AND (data LIKE '%$v%')":"";
				        break;
			        default:
			            break;
			     }
			  }
		}
		$c['where'] = $wherestr;
		$c['order'] = $orderstr;
	    $c['page'] = R("page","int");
	    $c['pagesize'] = 30;
	    
		$this->datalist = Q("notify_msg")->get_list($c,$pageurl);
		$this->display();
	}
	//私信删除
	public function notify_msg_del(){
		$id = R("id","string");
		if(empty($id))tAjax::json_error("删除ID不能为空！");
		$ret = C("notify")->del_msg($id);
		if($ret){
			tAjax::json(array("error"=>0,"message"=>"删除成功！","callback"=>"reload"));
		}else{
			tAjax::json_error("删除失败！");
		}
	}
}
?>