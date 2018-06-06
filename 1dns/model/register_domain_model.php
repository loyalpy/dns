<?php
class register_domain_model extends tModel{
	public function __construct(){
		parent::__construct("register_domain");
	}
	//获取行
	public function get($domain = ""){
		return M("register_domain")->get_row("domain='{$domain}'");
	}
	//获取域名详细信息
	public function get_info($domain_id = 0){
		if (empty($domain_id)) {
			return array();
		}
		return M("register_domain_attachinfo")->get_row("did = '{$domain_id}'");
	}
	public function get_list($data = array(), $pageurl = "",$map = false){
		$data['table'] 		= isset($data['table']) ? $data['table'] : "register_domain";
		$data['where'] 		= isset($data['where']) ? $data['where'] : "1";
		$data['page'] 		= isset($data['page']) ? $data['page'] : 1;
		$data['pagesize']   = isset($data['pagesize']) ? $data['pagesize'] : 20;
		$data['order'] 		= isset($data['order']) ? $data['order'] : "dateline DESC";
		$query = new tQuery($data['table']);
		foreach ($data as $k => $v) {
			$query->$k = $v;
		}
		$list = array();
		$list['list'] = $query->find();
		if ($map == true) {
			foreach ($list['list'] as $key=>$val) {
				$list['list'][$key]['info_data'] = $this->get_info($val['id']);
			}
		}
		$list['pagebar'] = $query->get_pagebar($pageurl);
		$list['total'] = $query->total;
		$list['totalpage'] = $query->totalpage;
		return $list;
	}
	//续费期，赎回期 1服务期 2续费期 3赎回期 4域名过期已删除
	//规则：国内域名 续费期：35天 赎回期 ：35天（续费期）+15天
	//规则：国际域名 续费期：30天 赎回期 ：30天（续费期）+30天
	public function get_type_by_exp($domain,$exp_time){
		//读取域名后缀缓存
		$new_time = time();
		$suffix_p = tCache::read("domain_register_price1");
		$suffix_arr = array();
		if (count($suffix_p) > 0) {
			foreach ($suffix_p as $k=>$v) {
				if ($v['type'] == 4 || $v['type'] == 5) { //国内域名
					$suffix_arr[] = ".".$v['name'];
				}
			}
			$suffix_arr = array_unique($suffix_arr);
		}
		$reg_domain_type = 0;//判断是0国际域名

		$reg_domain_type_tmp = explode(".",$domain);
		if (in_array(".".$reg_domain_type_tmp[1],$suffix_arr)) {
			$reg_domain_type = 1;//判断是1国内域名
		}

		if ($reg_domain_type == 1) { //规则：国内域名 续费期：35天 赎回期 ：35天（续费期）+15天
			$x_t = 35;
			$s_t = 15;
		}else{//规则：国际域名 续费期：30天 赎回期 ：30天（续费期）+30天
			$x_t = 30;
			$s_t = 30;
		}
		if ($exp_time >= $new_time) {
			$num =  1;//服务期
		}elseif ( $exp_time < $new_time && $new_time < ($exp_time+$x_t*86400) ) {
			$num=  2;//续费期
		}elseif ( ($exp_time+$x_t*86400) < $new_time && $new_time < ($exp_time+($x_t+$s_t)*86400) ) {
			$num = 3;//赎回期
		}else{
			$num = 4;//域名过期 已删除
		}
		return $num;
	}
	//写入域名操作日志 单例  $data = array(0=a,1=>b)
	public function log($action="",$msg="",$data=array()){
		global $uid,$timestamp;
		if(!isset($data['domain_id']) && !isset($data['domain'])){
			return false;
		}
		$data['domain']	    =	isset($data['domain'])?$data['domain']:"未知域名";
		$data['modi_from']	=	isset($data['modi_from'])?$data['modi_from']:"";
		$data['modi_to']	    =	isset($data['modi_to'])?$data['modi_to']:"";
		$logs = array(
			"domain_id"     	=> $data['domain_id'],
			"domain"  			=> $data['domain'],
			"uid"  				=> $uid?$uid:"0",
			"modi_item"  		=> $action,
			"modi_from"  		=> $data['modi_from'],
			"modi_to"			=> $data['modi_to'],
			"modi_log"  		=> $msg,
			"ipaddr"      		=> tClient::get_ip(0),
			"dateline" 			=> $timestamp,
		);
		M("register_domain_log")->set_data($logs)->add();
		return true;
	}
	//注册域名列表导出excel格式
	public function register_domain_excel($where = 1){
		Header( "Content-type:application/octet-stream");
		Header( "Accept-Ranges:bytes");
		Header( "Content-type:application/vnd.ms-excel");
		Header( "Content-Disposition:attachment;filename=domainlist.xls");

		echo "<table style='border: 1px solid #DADCDD'><tr  style='border: 1px solid #DADCDD'>";
		echo "<th>域名</th>";
		echo "<th>注册时间</th>";
		echo "<th>到期时间</th>";
		echo "<th>域名所有者名称</th>";
		echo "<th>管理员邮箱</th>";
		echo "<th>管理员电话</th>";
		echo "<th>通讯地址</th>";
		echo "</tr>";

		$c =array(
			"page"     	=> 1,
			"pagesize"   => 500,
			"fields" 		=> "a.*,b.*",
			"where"    	=> $where,
			"join"			=> "LEFT JOIN register_domain_attachinfo as b ON a.id = b.did",
			"order"		=> "b.aller_name_cn DESC"
		);
		$res = Q("register_domain as a")->get_list($c);
		foreach ($res['list'] as $k=>$v) {
			echo "<tr style='border: 1px solid #DADCDD'>";
			echo "<td>".$v['domain']."</td>";
			echo "<td>".date("Y-m-d",$v['reg_time'])."</td>";
			echo "<td>".date("Y-m-d",$v['exp_time'])."</td>";
			echo "<td>".$v['aller_name_cn']."</td>";
			echo "<td>".$v['email']."</td>";
			echo "<td>".$v['mobile']."</td>";
			echo "<td>".$v['area'].",".$v['addr_cn']."</td>";
			echo "</tr>";
		}
		if($res['totalpage'] > 1){
			for($i = 2;$i<=$res['totalpage'];$i++){
				$c['page'] = $i;
				$res = Q("register_domain as a")->get_list($c);
				foreach ($res['list'] as $k=>$v) {
					echo "<tr style='border: 1px solid #DADCDD'>";
					echo "<td>".$v['domain']."</td>";
					echo "<td>".date("Y-m-d",$v['reg_time'])."</td>";
					echo "<td>".date("Y-m-d",$v['exp_time'])."</td>";
					echo "<td>".$v['aller_name_cn']."</td>";
					echo "<td>".$v['email']."</td>";
					echo "<td>".$v['mobile']."</td>";
					echo "<td>".$v['area'].$v['addr_cn']."</td>";
					echo "</tr>";
				}
			}
		}
		echo "</table>";
	}
}
?>