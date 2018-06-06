<?php
class register_order_model extends tModel{
	public function __construct(){
		parent::__construct("register_order");
	}
	public function get($order_no = ""){
		$return = array();
		if($order_no){
			$return = M("register_order")->get_row("order_no='{$order_no}'");
			if(isset($return['order_no'])){
				$return['order_item'] = $this->get_item($order_no);
			}
		}
		return $return;
	}
	public function get_item($order_no = ""){
		$return = array();
		if($order_no){
			$return = M("register_order_item")->query("order_no='{$order_no}'","*","type ASC");
		}
		return $return;
	}
	public function get_list($data = array(), $pageurl = "")
	{
		$data['table'] = isset($data['table']) ? $data['table'] : "register_order";
		$data['where'] = isset($data['where']) ? $data['where'] : "1";
		$data['page'] = isset($data['page']) ? $data['page'] : 1;
		$data['pagesize'] = isset($data['pagesize']) ? $data['pagesize'] : 15;
		$data['order'] = isset($data['order']) ? $data['order'] : "order_id DESC";
		$query = new tQuery($data['table']);
		foreach ($data as $k => $v) {
			$query->$k = $v;
		}
		$list['list'] = $query->find();
		foreach($list['list'] as $key =>$v){
			$list['list'][$key]['lsh'] = tTime::get_datetime("YmdHis",$v['pay_dateline']).sprintf("%09d",$v['order_id']);
			$list['list'][$key]['order_item'] = $this->get_item($v['order_no']);

			//删除订单创建三天后未支付订单
			if ((time() - $v['dateline']) > 86400*3) {
				if (in_array($v['status'],array(1,2))) {
					if (M("register_order")->set_data(array("indel"=>1))->update("order_no = '{$v['order_no']}'")) {
						M("register_order_item")->set_data(array("indel"=>1))->update("order_no = '{$v['order_no']}'");
						unset($list['list'][$key]);
					}
				}
			}

		}
		$list['list'] = array_values($list['list']);
		$list['pagebar'] = $query->get_pagebar($pageurl);
		$list['total'] = $query->total;
		$list['totalpage'] = $query->totalpage;
		return $list;
	}
}
?>