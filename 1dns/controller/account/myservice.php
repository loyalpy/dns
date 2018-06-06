<?php
class myservice extends UC{
	public function __construct(){
		parent::__construct('myservice');
	}
	public function index(){
		$this->display();
	}
	
	public function serverlist(){
		global $uid;
        $do=R('do','string');
        $pageurl = U("/myservice/serverlist?do=get");
        $page = R("page","int");
        $page = $page?$page:1;
        $pagesize = 20;
        $wherestr = "buy_uid = ".$uid ;
        
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
                        $wherestr .= $v?(" AND (expiry >= '".strtotime($v)."')"):"";
                        break;
                    case "enddate":
                        $wherestr .= $v?(" AND (expiry <= '".strtotime($v)."')"):"";
                        break;
                    case 'keyword':
                        $v = ($v == '关键词')?'':$v;
                        $wherestr .= $v?" AND (server_no LIKE '%$v%' OR amount LIKE '%$v%')":"";
                        break;
                    default:
                        break;
                 }
            }
        }
        
        //取数据
        $obj = new tQuery("goods_server");
        $result = $obj->get_list(array(
            "where" => $wherestr,    
            "page"  => $page,
            "order" => "expiry DESC",                 
        ),$pageurl);
        //print_r($result);
        //分配数据
        $this->assign("datalist",$result);
		$this->display();
	}
	//服务器信息查看
	public function serverlist_view(){
		global $uid,$timestamp;
		$server_no = R("server_no","string");
		$server_row = M("goods_server")->get_row("server_no='{$server_no}'");

		if(!isset($server_row['id']))tAjax::json_error("没找到该服务器撒打算！");
		if($server_row['buy_uid'] != $uid)tAjax::json_error("非法获取！");
		//if($server_row['expiry'] < $timestamp)tAjax::json_error("服务器已经到期！");
		$server_row["specs"] = $server_row["specs"]?tUtil::unserialize($server_row["specs"]):array();
		$server_row['specs_str'] = "";
		if($server_row['specs']){
			foreach($server_row['specs'] as $k2=>$v2){
				$server_row['specs_str'] = $server_row['specs_str']. "{$v2['name']}+{$v2['label']} /";
			}
		}
		//读出最近续费记录
		$serverlist_order = M("order_item")->query("server_no='{$server_no}' AND buy_uid='{$uid}' AND start_dateline <> 0 AND expiry <> 0","*","start_dateline DESC",8);
		$server_row['order'] = array();
		if($serverlist_order){
			foreach($serverlist_order as $k=>$v){
				$v['specs'] = $v['specs']?tUtil::unserialize($v['specs']):array();
				$v['specs_str'] = "";
				if($v['specs']){
					foreach($v['specs'] as $k2=>$v2){
						$v['specs_str'] = $v['specs_str']. "{$v2['name']}+{$v2['label']} /";
					}
				}
				$server_row['order'][] = $v;
			}
		}
		tAjax::json($server_row);
	}
	//购买提交
	public function serverlist_buy(){
		global $uid,$timestamp;
		if(tUtil::check_hash()){
			 $order_expiry = isset(App::$data['site_config']['order_expiry'])?App::$data['site_config']['order_expiry']:0;
	         $order_expiry = $order_expiry?$order_expiry:45;
			//如果该用户有订单未做处理不能提交新订单
			//获取产品信息
			if(M("order")->get_one("buy_uid='{$uid}' AND pay_status<2 AND status<3 AND indel=0","count(id)") >0){
				$this->_msg("您有未完成订单,请先完成未处理订单","","/order/orderlist#返回处理订单");
			}
			$goods_no = R("goods_no","string");
			$goods = C("goods")->get_row("goods_no='{$goods_no}'");
			
			if(!isset($goods['goods_no'])){
				I(404);
			}
			if($goods['indel'] == 1 || $goods['status'] != 0){
				$this->_msg("该产品已下架,或已删除！","","/goods#返回");
			}			
			$cates            = C("category","goods_cate")->get(1);
	        $this->cate       = $cates[$goods['fid']];
	        $this->cate_model = tCache::read("goods_model_{$this->cate['model_id']}");
			$tmp_specs        = R("specs","string");
			
			//计算钱
			$balance = $price = $specs_balance = $num = $month = 0;
			$specs = array();
			//先计算规格附加值的费用
			if($tmp_specs && $goods['specs']){
				foreach($tmp_specs as $key=>$v){
					if(isset($goods['specs'][$key])){
						foreach($goods['specs'][$key] as $key2=>$v2){
							if($v2['label'] == $v){
								$specs[$key]['label'] = $v2['label'];
								$specs[$key]['price'] = $v2['price'];
								$specs[$key]['name']  = $v2['name'];
								break;
							}
						}
					}
				}
			}
			//
			foreach($specs as $v){
				$specs_balance = floatval($v['price'])+$specs_balance;
			}
			$num = R("num","int");
			$num = $num?$num:1;
			if($num > 15){
				$this->_msg("一次性最多购买15台机器！","","/goods#返回");
			}	
			//处理时间
			$month = R("month","int");
			$month = $month?$month:12;
			if($month > 120){
				$this->_msg("最多购买10年！","","/goods#返回");
			}	
			
			$goods['price'] = 0;
			if(in_array($month,array(1,3,6))){
				$price = ($goods["price{$month}"] + $specs_balance*$month);
			}else{
				$month = ceil($month/12)*12;
				$price = (($goods["price12"] + $specs_balance*12)*($month/12));
			}
			$balance = $price*$num;
			//*************************************** 订单生成
			//首选把锁定的机器自动解锁
			$inlock_expiry = $order_expiry;
			M("goods_server")->set_data(array("inlock"=>0,"inlock_expiry"=>0))->update("server_st < 9 AND indel=0 AND inlock=1 AND inlock_expiry<".($timestamp-$inlock_expiry*60));
			//找空闲机器
			$serverlist = $order_items = array();
			$tmp = M("goods_server")->query("indel=0 AND buy_uid=0 AND server_st < 9 AND inlock=0","id,server_no,idc,inlock,inlock_expiry,server_st,indel,server_ips,server_in_ips",false,$num);
			if(empty($tmp) || count($tmp) != $num){
				C("user")->log('购买服务器失败',"由于服务器数量不足,订单生成失败！");
				$this->_msg("由于服务器数量不足,订单生成失败！请稍后再试！","","/goods#返回");
			}else{
				foreach ($tmp as $k=>$v){
					$serverlist[$v['server_no']] = $v;
				}
				//开始锁定服务器
				$affect_num = M("goods_server")->set_data(array(
					"inlock" =>1,
					"inlock_expiry" => $timestamp+$inlock_expiry*60,
				))->update("indel=0 AND buy_uid=0 AND server_st < 9 AND inlock=0 AND server_no IN('".implode("','",array_keys($serverlist))."')");
				if($affect_num != $num){
					C("user")->log('购买服务器失败',"由于服务器数量不足,锁定服务器失败,订单生成失败！");
					$this->_msg("由于服务器数量不足,锁定服务器失败！","","/goods#返回");
				}
			}
			//写入订单
			foreach ($serverlist as $key => $v){
				$order_items[$v['server_no']] = array(
					"goods_no"  => $goods['goods_no'],
					"server_no" => $v['server_no'],
					"amount"    => $price,
					"specs"     => $specs,
				);
			}
			$order_data = array(
				"amount" => $balance,
				"price"  => $price,
				"nums"   => $num,
				"type"   => 0,
				"pay_type" => $month,
				"pay_status" => 0,
				"forno"      => $goods['goods_no'],
				"forname"    => $goods['name'],
			);
            $order_data['order_goods'] = serialize(array(
            		"goods_no" => $goods['goods_no'],
            		"name"     => $goods['name'],
            		"fid"      => $goods['fid'],
            		"idc"      => $goods['idc'],
            		"goods_no" => $goods['goods_no'],
            		"ptype"    => $goods['ptype'],
            		"price12"  => $goods['price12'],
            		"price6"   => $goods['price6'],
            		"price3"   => $goods['price3'],
            		"price1"   => $goods['price1'],
            		"price0"   => $goods['price0'],
            		
            		"fm_id"   => $goods['fm_id'],
            		"fm"      => $goods['fm'],
            		"specs"   => $goods['specs'],
            		"attrs"   => $goods['attrs'],
            		
            		"myspecs" => $specs,
            	));
			$ret = C("order")->save(0,$order_data,$order_items);
			if($ret){
				$this->redirect("/order/orderlist_view?order_no={$ret}");
			}else{
				$this->_msg("生成订单失败,请重试","","/goods#返回");
			}			
		}else{
			I(404);
		}
	}
	//续费
	public function serverlist_rebuy(){
		global $uid,$timestamp;
		if(tUtil::check_hash()){
			
		}else{//未提交
			$server_nos = R("server_nos","string");
			$server_no_arr = $server_nos?explode(",",$server_nos):array();
			$server_no_arr = array_unique($server_no_arr);
			
			if(empty($server_no_arr)){
				$this->_msg("续费服务器输入有误！","","/myservice/serverlist#返回");
			}
			if(count($server_no_arr) > 30){
				$this->_msg("一次续费服务器,太多,请重选！","","/myservice/serverlist#返回");
			}
			
			$serverlist = M("goods_server")->query("buy_uid='{$uid}' AND server_no IN('".implode("','",$server_no_arr)."')","*",30);
		}
	}
}