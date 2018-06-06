<?php
/**
 * @class Oauth
 * @brief oauth协议接口
 */
class cls_sms{
	private $sms_obj = null;
	private $tpl    = array(
			"reg"       => "手机注册验证,验证码：%s",
			"findpass"  => "密码找回,验证码：%s",
			"rz"        => "手机验证,验证码：%s",
			"modify"    => "修改手机验证,验证码：%s",
	);
	//%b - 二进制数 %c - 依照 ASCII 值的字符 %d - 带符号十进制数 %u - 无符号十进制数%f - 浮点数(local settings aware) %o - 八进制数%s - 字符串
    //构造函数
	public function __construct(){
		
	}
	//获取字段数据
	public function send($mobile_number,$content){
		require_once ROOT_PATH."lib/plugins/sms/CLSms.php";
		$ret = CLSms::send($mobile_number,$content);
		if($ret === 0){
			return true;
		}else{
			return false;
		}
	}
	//根据模板发送
	public function send_tpl($mobile_number,$tpl,$data = array()){
		global $timestamp;
		if(in_array($tpl,array_keys($this->tpl))){
			$code  = rand(100000,999999);
			$sdata = array(
				"code" => $code,
			);
			if($data){
				foreach($data as $k=>$v){
					$sdata[$k] = $v;
				}
			}
			$content = vsprintf($this->tpl[$tpl],$sdata);
			$ret = $this->send($mobile_number,$content);
			
			M("sys_sms")->set_data(array(
				"session_id" => tSafe::get_id(),
				"mobile"     => $mobile_number,
				"tpl"        => $tpl,
				"content"    => $content,
				"code"       => $code,
				"status"     => $ret?1:0,
				"dateline"   => $timestamp,
			))->add();			
			if($ret){
				tSafe::set('rzcode',$code);
			}
			return true;
		}else{
			return false;
		}
	}
	//根据模板发送2
	public function send_tpl2($mobile_number,$tpl,$data){
		global $timestamp;
		if(in_array($tpl,array_keys($this->tpl))){
			$content = vsprintf($this->tpl[$tpl],$data);
			$ret = $this->send($mobile_number,$content);
			M("sys_sms")->set_data(array(
				"session_id" => tSafe::get_id(),
				"mobile"     => $mobile_number,
				"tpl"        => $tpl,
				"content"    => $content,
				"code"       => isset($data['code'])?$data['code']:"",
				"status"     => $ret?1:0,
				"dateline"   => $timestamp,
			))->add();
			return true;
		}else{
			return false;
		}
	}
	//获取字段数据
	public function get_smsinfo(){
		return $this->sms_obj->get_smsinfo();
	}
	//获取最后手机短信验证码
	public function get_lastcode($mobile,$tpl,$distime = 1800){
		global $timestamp;
		$code = M("sys_sms")->get_one("mobile='{$mobile}' AND tpl='{$tpl}' AND dateline>".($timestamp-$distime),"code","dateline DESC");
		return $code;
	}
	//同一手机短信间隔
	public function chk_sms_send($mobile,$tpl){
		return $this->get_lastcode($mobile,$tpl,90);
	}
	//检查手机验证码正确性质
	public function chk_sms($code,$mobile,$tpl = "rz"){
		return $this->get_lastcode($mobile,$tpl) === $code;
	}
	//获取同一个 session_id 发送短信数 当天
	public function get_sid_num($mobile = ""){
		global $timestamp;

		$start = strtotime(date("Y-m-d",$timestamp));
		$end   = strtotime(date('Y-m-d',$timestamp+86400));
		$sid   = tSafe::get_id();
		return M("sys_sms")->get_one("mobile='{$mobile}' AND dateline>{$start} AND dateline<{$end}","count(id)");
	}
	//引入平台接口文件
	private function _require($file_name){
		$class_file = ROOT_PATH.'lib/plugins/sms/sms_'.$file_name.'.php';
		if(file_exists($class_file)){
			include_once($class_file);
			return true;
		}else{
			return false;
		}
	}
}
?>