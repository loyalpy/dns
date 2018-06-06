<?php
class message extends UC{
    public $layout="ucenter";
    public function inweb(){
    	global $uid,$utype,$timestamp,$realip; 
    	$do = R("do","string");
        $pageurl = U("/message/inweb?do=get");
        $page = R("page","int");
        $page = $page?$page:1;
        $order= "dateline DESC";
        $pagesize = 20;
        $wherestr = "tuid={$uid}";
        switch($do){
        	case "view"://查看详情
        	    $id = R("id","int");
        	    $row = M("notify")->get_row("id='{$id}'");
        	    if(!isset($row['id'])){
        	    	tAjax::json_error("没有找到该数据！");
        	    }else{
        	    	//
        	    	if($row['inread'] == 0){
        	    		M("notify")->set_data(array("inread"=>1))->update("id='{$id}'");
        	    	}
        	    	//echo $row['message'];
        	    	tAjax::json($row);
        	    }
        		break;
        	default:
        		//获取查询条件
        		$condi = array(
                    "startdate"     => R("startdate","string"),
                    "enddate"       => R("enddate","string"),
                    "keyword"       => R("keyword","string")
        		
        		);
                //dump($condi);
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
		        $obj = new tQuery("notify");
		
		        $result = $obj->get_list(array(
		                "where"   => $wherestr,
		                "order"   => $order,
		                "page"    => $page,
		                "pagesize"=> $pagesize                           
		        ),$pageurl);
                //dump($result);
		        $this->assign("datalist",$result);
		        $this->display();
	        break;
        }
    }

    /*-------------------------------系统消息--------------------------------------------------*/
    public function insys(){
        global $uid;
        $do        =R('do','string');
        $pageurl   = U("/message/insys?do=get");
        $page      = R("page","int");
        $page      = $page?$page:1;
        $order     = "dateline DESC";
        $pagesize  = 20;
        $wherestr  = "(tuid=0 AND utype={$this->userinfo['utype']} AND ulevel={$this->userinfo['ulevel']})";
        switch($do){
            case "view":
                $id=R('id','int');
                $row = M("notify")->get_row("id='{$id}'");
        	    if(!isset($row['id'])){
        	    	tAjax::json_error("没有找到该数据！");
        	    }else{
        	    	
        	    	if($row['inread'] == 0){
        	    		M("notify")->set_data(array("inread"=>1))->update("id='{$id}'");
        	    	}        	    	
        	    	tAjax::json($row);
        	    }
                break;
            default:
                $condi = array(
                    "startdate"     => R("startdate","string"),
                    "enddate"       => R("enddate","string"),
                    "keyword"       => R("keyword","string")        		
        		);
                //dump($condi);
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
                $obj = new tQuery("notify");
                $result = $obj->get_list(array(
                       "where"     => $wherestr,
                        "page"     => $page,
                        "order"    => $order,
                        "pagesize" => $pagesize                                   
                ),$pageurl);                
                $this->assign("datalist",$result);
                $this->display();
                break;        
        }        
    }
}
?>