<?php
/**
 * 资金管理
 * by Thinkhu 2014 
 */
class finance_manager extends UCAdmin{
	public function dongjie(){
		global $uid;
		//配置
		$do=R('do','string');
		$pageurl = U("/finance_manager/dongjie?do=get");
		$page = R("page","int");
		$page = $page?$page:1;
		$wherestr = "1";
		$pagesize = 30;
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
						$wherestr .= $v?(" AND (a.dateline >= '".strtotime($v)."')"):"";
						break;
					case "enddate":
						$wherestr .= $v?(" AND (a.dateline <= '".strtotime($v)."')"):"";
						break;
					case 'keyword':
						$v = ($v == '关键词')?'':$v;
						$wherestr .= $v?" AND (a.order_no LIKE '%$v%' OR a.note LIKE '%$v%' OR a.pay_no LIKE '%$v%')":"";
						break;
					default:
						break;
				}
			}
		}
		switch($do){
			case "get_url":
				tAjax::json(array("error"=>0,"message"=>"","pageurl"=>$pageurl));
				break;
			case "get":
				//取数据
				$c = array();
				$c['page']     = $page;
				$c['where']    = $wherestr;
				$c['pagesize'] = $pagesize;
				$c['order']    = "a.dateline DESC";
				$c['fields']   = "a.*,b.order_st,b.status AS order_status";
				$c['join']     = " LEFT JOIN job_tuiorder AS b ON a.order_no=b.order_no";
				$result = Q("tmp_payed AS a")->get_list($c,$pageurl);
				$uuinfo = array();
				if($result['list']){
					foreach($result['list'] as $key=>$v){
						if(!isset($uuinfo[$v['uid']])){
							$uuinfo[$v['uid']] = C("user")->get_cache_userinfo($v['uid']);
						}
						$result['list'][$key]['company_name'] = isset($uuinfo[$v['uid']]['company']['company_name'])?$uuinfo[$v['uid']]['company']['company_name']:"";
					}
				}
				tAjax::json($result);
				break;
			default:
				$this->assign("pageurl",$pageurl);
				$this->display();
				break;
		}
	}
}
?>
