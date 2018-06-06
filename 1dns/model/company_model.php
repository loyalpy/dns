<?php
class company_model extends tModel{
	private $ext_names = array(
			"list" => array(),
			"arr"  => array("contact","company"),
			"one"  => array("about",'website','address','luxian','tag_my'),
	);
	public function __construct(){
		parent::__construct("company");
	}
	public function get($where){
		global $timestamp;
		$row = $this->get_row($where);
		return $row;
	}
	//保存
	public function save($company_id = 0,$data = array(),$company= array()){
		if($company_id == 0){
			$this->set_data($data);
			$ret = $this->add();
		}else{
			unset($data['company_id'],$data['company_name'],$data['uid']);
			$this->set_data($data);
			$ret = $this->update("company_id=$company_id");
		}
		return $ret;
	}
	//保存附加
	public function save_ext($ext_id,$name="",$company_id = 0,$c = ""){
		if($ext_id == 0){
			if(in_array($name,$this->ext_names['list'])){
				M("company_ext")->set_data(array(
					"name"       => $name,
					"company_id" => $company_id,
					"content"    => is_array($c)?tFun::arr2str($c):$c,
				));
				return M("company_ext")->add();
			}else{
				$ext_id = M("company_ext")->get_one("company_id='{$company_id}' AND name='{$name}'","id");
				if(empty($ext_id)){
					M("company_ext")->set_data(array(
						"name"       => $name,
						"company_id" => $company_id,
						"content"    => is_array($c)?tFun::arr2str($c):$c,
					));
					return M("company_ext")->add();
				}else{
					M("company_ext")->set_data(array(
						"content"   => is_array($c)?tFun::arr2str($c):$c,
					));
					return M("company_ext")->update("id='{$ext_id}'");
				}
			}
		}else{
			M("company_ext")->set_data(array(
				"content"   => is_array($c)?tFun::arr2str($c):$c,
			));
			return M("company_ext")->update("id='{$ext_id}' AND company_id='{$company_id}'");
		}
	}
	//保存地理坐标
	public function save_pos($company_id,$data = array()){
		$ret = M("company_pos")->get_one("company_id='{$company_id}'","company_id");
		if($ret){
			return M("company_pos")->set_data($data)->update("company_id='{$ret}'");
		}else{
			$data['company_id'] = $company_id;
			return M("company_pos")->set_data($data)->add();
		}
	}
	//保存联系方式
	public function save_contact($company_id,$data){
		global $timestamp;
		$row = $this->get_contact($company_id);
		if(isset($row['id']) && $row['id']){
			return M("company_contact")->set_data($data)->update("id='{$row['id']}'");
		}else{
			$data['company_id'] = $company_id;
			$data['dateline']   = $timestamp;
			return M("company_contact")->set_data($data)->add();
		}		
	}
	//获取坐标
	public function get_pos($company_id){
		return M("company_pos")->get_row("company_id='{$company_id}'");
	}
	//获取联系方式
	public function get_contact($company_id){
		$row = M("company_contact")->get_row("company_id='{$company_id}'");
		return $row;
	}
	//删除附加
	public function del_ext($id,$company_id,$ext){
		return M("company_ext")->del("id='{$id}' AND company_id='{$company_id}' AND name='{$ext}'");
	}
	//获取附加
	public function get_ext($company_id = 0,$ext=""){
		$re    = array();
		$where = "company_id='{$company_id}'".($ext?" AND name='{$ext}'":"");
		$list = M("company_ext")->query($where,"id,company_id,name,content,sort","sort ASC,id DESC");
		$pre  = "ext_";
		foreach($list as $k=>$v){
			if(in_array($v['name'],$this->ext_names['list'])){
				if(!isset($re[$pre.$v['name']])){
					$re[$pre.$v['name']] = array();
				}
				$ret = tFun::str2arr($v['content']);
				if(is_string($ret)){
					$re[$pre.$v['name']][] = array(
							"content" => $ret,
							"id"      => $v['id'],
					);
				}else{
					$ret['id'] = $v['id'];
					$re[$pre.$v['name']][] = $ret;
				}
			}elseif(in_array($v['name'],$this->ext_names['arr'])){
				$re[$pre.$v['name']] = tFun::str2arr($v['content']);
			}else{
				$re[$pre.$v['name']] = $v['content'];
			}
		}
		return $ext?(isset($re[$pre.$ext])?$re[$pre.$ext]:null):$re;
	}
	//获取信誉
	public function get_credit($company_id = 0,$item="",$add= false){
		$credit = M("company_credit")->get_row("company_id=$company_id");
		if(!isset($credit['company_id'])){
			$credit = array(
					"company_id" => $company_id,
					"views"     => 0,
					"invites"   => 0,
					"downloads" => 0,
					"applys"    => 0,
					"tuis"      => 0,
					"ms_nums"   => 0,
					"noms_nums" => 0,
					"ly_nums"   => 0,
					"noly_nums" => 0,
					"goods"     => 0,
					"bads"      => 0
			);
			M("company_credit")->set_data($credit)->add();
		}
		if($item && $add){
			M("company_credit")->set_data(array("{$item}"=>"{$item}+1"))->update("company_credit={$company_id}",array("{$item}"));
			return $credit[$item]+1;
		}
		return $credit;
	}
	//获取公司信息完成度
	public function get_completed($company_id,$company = array(),$company_contact=array()){
		if(empty($company)){
			$company = $this->get("company_id='{$company_id}'");
		}
		if(empty($company_contact)){
			$company_contact = $this->get_contact($company_id);
			if(isset($company_contact['company_id'])){
				$company = array_merge($company,$company_contact);
			}
		}
		$completed = 0;
		$c = array(
			"company_name" => 10,//
			"trade"        => 10,
			"company_tp"   => 10,
			"company_sp"   => 10,
			"money"        => 10,
			"city"         => 10,
			"tag_company"  => 10,
			"linker"       => 10,
			"mobile"       => 10,
			"email"        => 10,
		);
		foreach($c as $k=>$v){
			if(isset($company[$k]) && $company[$k]){
				$completed = $completed + $v;
			}
		}
		return $completed;
	}
	//获取附件
	public function get_attach($company_id,$type=""){
		$result = M("company_attach")->query("company_id='{$company_id}'".($type?" AND type='{$type}'":""),"*",'id ASC');
		return $result;
	}
	//完成度
	public function get_attach_num($company_id=0,$type=""){
		return M("company_attach")->get_one("company_id='{$company_id}'".($type?" AND type='{$type}'":""),"count(*)");
	}
	//更新企业附件数
	public function update_attach_num($company_id = 0){
		$num = $this->get_attach_num($company_id);
		return M("company")->set_data(array("attachs" => $num))->update("company_id='{$company_id}'");
	}
	//获取企业部门
	public function get_depart($company_id = 0){
		$re = array();
		$tmp = M("company_depart")->query("company_id='{$company_id}'","*");
		foreach($tmp as $v){
			$re[$v['id']] = $v;
		}
		return $re;
	}
}
?>