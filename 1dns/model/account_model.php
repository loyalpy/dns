<?php
class account_model extends tModel{
	private $item = array("balance" => "余额","sms"=>"短信","point" => "积分","exp" => "经验","domains"=>"解析域名","register_domains"=>"注册域名");
	public function __construct(){
		parent::__construct("user_account");
	}
	//获取用户ACCOUNT
	public function get($uid = 0){
		$re = $this->get_row("uid='{$uid}'");
		if(!isset($re['uid'])){
			$re = array(
					"uid"     => $uid,
					"balance" => "0.00",
				    "sms"   => 0,
					"point"   => 0,
					"exp"     => 0,
					"domains"=>0,
					"register_domains"=>0
			);
		}
		return $re;
	}
	//更新用户账户
	public function update($uid = 0,$data = array(),$note = array(),$auid = 0){
		$ret = 0;
		if($uid && !empty($data)){
			$userinfo = C('user')->get_cache_userinfo($uid);
			if(isset($userinfo['uid']) && $userinfo['uid']){
				$uid = $userinfo['uid'];
				if(!M("user_account")->get_one("uid='{$uid}'","uid")){
					M("user_account")->set_data(array("uid"=>$uid))->add();
				}
				//处理DATA
				$updata = $exp = array();
				foreach ($data as $key=>$value){
					if(in_array($key,array_keys($this->item))){
						$pre   = substr("{$value}",0,1);
						$value = abs(substr("{$value}",1));
						if($pre === "+"){
							$updata[$key] = "{$key}+{$value}";
							$exp[] = $key;
						}elseif($pre === "-"){
							$updata[$key] = "{$key}-{$value}";
							$exp[] = $key;
						}elseif($pre === "="){
							$updata[$key]  = "{$value}";
						}
					}
				}
				$ret = M("user_account")->set_data($updata)->update("uid='{$uid}'",$exp);
				//如果操作成功
				if($ret){
					//写账户日志
					$this->log($uid,$data,$note,$auid);
					//更新缓存
					$userinfo['account'] = $this->get($uid);
					C('user')->set_cache_userinfo($uid,$userinfo);
				}
			}
		}
		return $ret?1:0;
	}
	//写入用户账户日志
	public function log($uid,$data = array(),$note = array(),$auid = 0){
		global $timestamp;
		if($uid){
			$userinfo = C('user')->get_cache_userinfo($uid);
			$tb_account_log = new tModel("user_accountlog");
			foreach($data as $key => $value){
				if(!in_array($key,array_keys($this->item)))continue;
				$pre   = substr("{$value}",0,1);
				$value = abs(substr("{$value}",1));
				if(!is_numeric($value) || $value == 0){
					continue;
				}
				$arr = array(
						"auid"       => $auid,
						"uid"        => $uid,
						"ftype"      => $key,
						"note"       => isset($note[$key])?$note[$key]:"",
						"amount"     => $value,
						"amount_log" => $value,
						"dateline"   => $timestamp,
						"type"       => 0
				);
				if($pre === "+"){
					$arr['type'] = 2;
					$arr['amount_log'] = $userinfo['account'][$key] + $value;
					if(!$arr['note']){
						"增加 {$this->item[$key]}： {$value}";
					}
				}elseif($pre === "-"){
					$arr['type'] = 1;
					$arr['amount_log'] = $userinfo['account'][$key] - $value;
					if(!$arr['note']){
						"减少 {{$this->item[$key]}}： {$value}";
					}
				}elseif($pre === "="){
					$arr['type'] = 0;
					$arr['amount_log'] = $userinfo['account'][$key] - $value;
					if(!$arr['note']){
						"修改 {{$this->item[$key]}}： {$value}";
					}
				}
				$tb_account_log->set_data($arr);
				$tb_account_log->add();
			}
		}
		return 1;
	}
}
?>