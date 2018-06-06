<?php
/**
 * @SDKdomain 域名注册代理接口
 */
class SDKdomain {
	private static $agent = "xinnet";
    private static function get_obj(){
		$mapping_conf = array('xinnet'=>'SDKxinnet','net'=>'SDKnet');
		$agent  = App::get_conf("app.agent");
		if($agent){
			self::$agent = $agent;
		}
		return $mapping_conf[self::$agent ];
	}
	//检测域名是否注册
    public static function check($method,$name,$data = array()){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'Check'),$method,$name,$data);
    }
	//检测域名注册信息
	public static function check_info($method,$name,$type){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'CheckInfo'),$method,$name,$type);
	}
	//域名注册接口
	public static function domain_register($method,$name,$data = array()){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'domainRegister'),$method,$name,$data);
	}
	//域名注册状态接口
	public static function status($method,$name){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'Status'),$method,$name);
	}
	//域名注册信息修改接口
	public static function modify($method,$name,$data){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'domainModify'),$method,$name,$data);
	}
	//修改域名DNS
	public static function modify_dns($method,$name,$data){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'modifyDns'),$method,$name,$data);
	}
	//域名审核状态查询接口
	public static function domain_status($method,$name){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'domainVerifyStatus'),$method,$name);
	}
	//域名实名制资料上传接口
	public static function up_domain_rz($method,$name,$data,$extdata=array()){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'uploadCnDomain'),$method,$name,$data,$extdata);
	}
	//域名续费接口
	public static function domain_renew($method,$name,$data){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'DomainRenew'),$method,$name,$data);
	}
	//域名转入状态查询接口
	public static function domain_transfer_query($method,$name){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'QueryTransferInState'),$method,$name);
	}
	//域名转入接口
	public static function domain_transfer($method,$name,$data){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'DomainTransferIn'),$method,$name,$data);
	}
	//取消域名转入接口
	public static function domain_transfer_cancel($method,$name){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'CancelTransferIn'),$method,$name);
	}
	//转入通知信重发接口
	public static function resend_transfer_email($method,$name){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'resendTransferInEmail'),$method,$name);
	}
	//信息模板关联域名查询
	public static function get_tpl_dn_name($method,$name){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'getTplDnList'),$method,$name);
	}
	//信息模板创建接口
	public static function create_tpl_info($method,$name,$data,$extdata){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'createTemplate'),$method,$name,$data,$extdata);
	}
	//修改信息模板接口
	public static function mod_template($method,$name,$data,$extdata){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'modTemplate'),$method,$name,$data,$extdata);
	}
	//查询信息模板接口状态
	public static function query_template($method,$name){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'templateInfo'),$method,$name);
	}
	//删除信息模板
	public static function del_template($method,$name,$data){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'delTemplate'),$method,$name,$data);
	}
	//域名过户
	public static function registrant_change($method,$name,$data){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'registrantChange'),$method,$name,$data);
	}
	//上传信息模板实名制资料
	public static function rf_upload($method,$name,$data,$extdata){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'rfUpload'),$method,$name,$data,$extdata);
	}
	//域名密码管理接口
	public static function get_domain_key($method,$name){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'getProductKey'),$method,$name);
	}
	//域名状态设置
	public static function set_domain_status($method,$name,$data){
		$class_name = self::get_obj();
		return call_user_func(array($class_name, 'setDomainStatus'),$method,$name,$data);
	}
}