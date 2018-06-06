<?php
/**
 * 公司类
 *
 */
class cls_company{
	public function save($id = 0, $data = array(),$attachlist = array()){

	}
	public function get_list($c = array(),$pageurl=""){
		$result = array();
		$c['table'] = isset($c['table'])?$c['table']:"company";
		$c['where'] = isset($c['where'])?$c['where']:"1";
		$c['page'] = isset($c['page'])?$c['page']:1;
		$c['pagesize'] = isset($c['pagesize'])?$c['pagesize']:18;
		$c['order'] =  isset($c['order'])?$c['order']:"dateline DESC";
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
			foreach($result['list'] as $k=>$v){
				$_ids[] = $v["id"];
				//获取公司的IDC
				$result['list'][$k]['idc'] = array();
				$tmp_idc = M("company_idc")->query("company_id={$v['id']}","id,idc");
				if($tmp_idc){
					foreach($tmp_idc as $v){
						$result['list'][$k]['idc'][] = "{$v['idc']}";
					}
				}		
			}
		}
		return $result;
	}
	public function get_row($where = "",$update_hit=0){
		$result = M("company")->get_row($where);
		if(isset($result['id'])){
			if($update_hit == 1){
				//更新公司查看次数
				M("company")->set_data(array("hits"=>"hits+1"));
				M("company")->update("id={$result['id']}",array("hits"));
			}
			//获取公司的IDC
			$result['idc'] = array();
			$tmp_idc = M("company_idc")->query("company_id={$result['id']}","id,idc");
			if($tmp_idc){
				foreach($tmp_idc as $v){
					$result['idc'][] = "{$v['idc']}";
				}
			}			
		}
		return $result;
	}
	public function del($id=0){
		global $user_id;
		$row = $this->get_row("id=$id");
		if(isset($row['id'])){
			if(M("company")->del("id={$row['id']}")){
		  		//更新缓存
		  		M("company_idc")->del("company_id={$row['id']}");
				return 1;
			}
		}
		return 0;
	}
	//获取用户缓存
	public function get_cache_companyinfo($company) {
		if(empty($company)) {
			return array();
		}
		//缓存标识
		$cache_name = $this->get_cache_name($company);
		//获取userinfo
		$companyinfo = tCache::get($cache_name);
		if(empty($companyinfo)) {
			//获取初始用户信息
			$companyinfo = $this->get_row("id='{$company}'");
			if(isset($companyinfo['id'])){
				
			}
			tCache::set($cache_name,$companyinfo);
		}
		return $companyinfo;
	}
	//设置用户缓存
	public function set_cache_companyinfo($company, $companyinfo=array()) {
		if(empty($company)) {
			return false;
		}
		//缓存标识
		$cache_name = $this->get_cache_name($company);
		//如果为null则删除该缓存
		if(is_null($companyinfo)){
			tCache::del($cache_name);
			return true;
		}
		//如果$companyinfo为则重新设置
		if(!empty($companyinfo)){
			tCache::set($cache_name,$companyinfo);
			return true;
		}
	}
	//获取公司缓存name
	private function get_cache_name($company){
		return "companyinfo_".$company;
	}
}
?>