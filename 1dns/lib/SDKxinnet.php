<?php
/**
 * @XinNet 新网代理接口
 */
class SDKxinnet {
	//新网api调用接口地址
	private static $api_url    = "http://api.xinnet.com/domain/api.gb";
	//新网代理号
	private static $api_user = 'agent15562';
	//代理编码格式
	private static $api_encode = "E";
	//代理认证密码
	private static $password = "py910414";
	//代理字符集
	private static $api_charset = "UTF-8";

	//通用https请求
	private static function Get($url,$param){
		$options = array(
			'http' => array(
				'method' => "POST",
				'header'   => "Content-Type: application/x-www-form-urlencoded",
				'content' => http_build_query($param)
			));
		$context  = stream_context_create($options);
		$result    = file_get_contents($url, FILE_TEXT, $context);
		if($result){
			return array("http_code"=>200,"content"=>$result);
		}else{
			return array("http_code"=>0,"content"=>"请求失败");
		}
	}
	//URL编码处理
	private static function UrlSet($method,$name,$data = array()){
		$str   = "";
		$str  .= "charset=".self::$api_charset;
		$str  .= "&client=".self::$api_user;
		$str  .= "&enc=".self::$api_encode;
		$str  .= "&method=".$method;
		if  ($method == "check") {
			$str  .= "&name=".$name;
			foreach($data as $key=>$val){
				$str .= "&suffix="."{$val}";
			}
		}elseif (in_array($method,array("queryDomainInfo","Register","Status","ModifyContactor","ModDns","queryDomainVerifyStatus","uploadCnDomain","DomainRenew","QueryTransferInState","DomainTransferIn","CancelTransferIn","resendTransferInEmail","registrantChange","setDomainStatus"))) {
			$str  .= "&dn=".$name;
			if (count($data) > 0) {
				foreach($data as $key=>$val){
					$str .= "&{$key}=".urlencode($val);
				}
			}
		}elseif (in_array($method,array("templateDnList","createTemplate","modTemplate","templateInfo","delTemplate","rfUpload"))){
			$str  .= "&tname=".$name;
			if (count($data) > 0) {
				foreach($data as $key=>$val){
					$str .= "&{$key}=".urlencode($val);
				}
			}
		}elseif (in_array($method,array("GetProductKey"))){
			$str  .= "&name=".$name;
			if (count($data) > 0) {
				foreach($data as $key=>$val){
					$str .= "&{$key}=".urlencode($val);
				}
			}
		}

		return self::$api_url."?".$str;
	}
	//签名
	private static function Sign($method,$data = array()){
		$checksum = (($method == "")?"":$method).self::$api_user.self::$password;
		if (count($data) > 0) {
			foreach($data as $key => $value){
				$checksum  .= $value;
			}
		}
		$checksum = MD5($checksum);
		return $checksum;
	}
	/**
	 * 新网调用接口:check接口
	 * @param $method 请求方法
	 * @param $name  请求域名
	 * @param $data  请求后缀 数组格式 array("net","cn","com")
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功(0 不可注册 100 可以注册) ,2,新网认证失败
	 */
	public static function Check($method,$name,$data = array()){
		//公用返回格式 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
		$return  = array("status"   => 0, "list"  	=> "",);
		if (empty($method) || empty($name) || (count($data) <= 0)) {
			$return['status'] = 0;
			return $return;
		}
		//Curl,POST > charset  ,  client  ,  enc
		$url	= self::UrlSet($method,$name,$data);
		$res	= tCurl::post($url,array(),"");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr = $r_arr = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			for ($i = 1;$i <= $t_arr['num'];$i++) {
				$n = "name".$i;
				$c = "chk".$i;
				foreach ($t_arr as $k=>$v){
					if ($n == $k) {
						$r_arr[$i]["domain"] = $name; //域名
						$r_arr[$i]["suffix"] = $data[$i-1]; //后缀
						$r_arr[$i]["chk"] = (int)$t_arr[$c]; //是否可以注册
					}
				}
			}
			if (isset($t_arr['err'])) {
				$return['status']   = 2; //失败
				$return['list']		= $r_arr;
			}else{
				$return['status']   = 1; //成功
				$return['list']		= $r_arr;
			}
			unset($e_str);
			unset($str_tmp);
			unset($t_arr);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:checkinfo接口
	 * @param $method 请求方法
	 * @param $name  请求域名
	 * @param $type  请求联系人 管理联系人(admType)/.注册联系人(regType)
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function CheckInfo($method,$name,$type){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name)) {
			$return['status'] = 0;
			return $return;
		}
		$queryType = empty($type)?"admType":$type; //管理联系人(admType)/.注册联系人(regType)
		$checksum = self::Sign($method,array($name,$queryType));
		$data = array("queryType" => $queryType, "checksum" => $checksum);
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 0) {
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 1; //成功
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:domainregister接口
	 * @param $method 请求方法
	 * @param $name 域名
	 * @param $data 域名注册详细参数
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function domainRegister($method,$name,$data){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name) || (count($data) <= 0)) {
			$return['status'] = 0;
			return $return;
		}
		if (empty($data['aemail']) || empty($data['uname2'])) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method,array($name,$data['aemail'],$data['uname2']));
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:status接口
	 * @param $method 请求方法
	 * @param $name 域名
	 * @return array 数组格式 status 》0表示URL请求失败，1表示注册成功 ,2,新网认证失败
	 */
	public static function status($method,$name){
		$return = array("status" => 0, "list" => array(),);
		if (empty($method) || empty($name)) {
			$return['status'] = 0;
			return $return;
		}
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name ,array());
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$str_tmp =  explode("=",$res['content']);
			if ($str_tmp[1] == 100) {
				$return['status']   = 1; //成功
			}else{
				$return['status']   = 2; //失败
			}
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:domainModify 域名注册信息修改
	 * @param $method 请求方法
	 * @param $name 域名
	 * @param $data 域名注册详细参数
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function domainModify($method,$name,$data){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name) || (count($data) <= 0)) {
			$return['status'] = 0;
			return $return;
		}
		if (empty($data['uemail']) || empty($data['aemail'])) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign("",array($name,$data['aemail'],$data['uemail']));
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");

		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:ModSelfDNS
	 * @param $method 请求方法
	 * @param $name 域名
	 * @return array 数组格式 status 》0表示URL请求失败，1表示注册成功 ,2,新网认证失败
	 */
	public static function modifyDns($method,$name,$data){
		$return = array("status" => 0, "list" => array(),);
		if (empty($method) || empty($name) || (count($data) <= 0)) {
			$return['status'] = 0;
			return $return;
		}
		if (empty($data['dns1']) || empty($data['dns2'])) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method,array($name,$data['dns1'],$data['dns2']));
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name ,$data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:queryDomainVerifyStatus
	 * @param $method 请求方法
	 * @param $name 域名
	 * @return array 数组格式 status 》0表示URL请求失败，1表示成功 ,2,新网认证失败
	 */
	public static function domainVerifyStatus($method,$name){
		$return = array("status" => 0, "list" => array(),);
		if (empty($method) || empty($name)) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method,array($name));
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name ,$data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = $t_arr2= array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //成功
				foreach ($e_str as $key=>$val) {
					$str_tmp =  explode("=",$val);
					if ($key == 1) {
						$t_arr2[$str_tmp[0]] = $str_tmp[2];
					}else{
						$t_arr2[$str_tmp[0]] = $str_tmp[1];
					}
				}
				$return['list']		= $t_arr2;

			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:uploadCnDomain上传域名实名制资料接口
	 * @param $method 请求方法
	 * @param $name 域名
	 * @return array 数组格式 status 》0表示URL请求失败，1表示成功 ,2,新网认证失败
	 */
	public static function uploadCnDomain($method,$name,$data,$extdata=array()){
		$return = array("status" => 0, "list" => array(),);
		if (empty($method) || empty($name)) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method,array($name));
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name ,$data);
        $res = self::Get($url,$extdata);
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = $t_arr2= array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				if ($key == 1) {
					$t_arr['info'] = $str_tmp[1];
				}else{
					$t_arr[$str_tmp[0]] = $str_tmp[1];
				}
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //成功
				$return['list']		= $t_arr2;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:DomainRenew  域名续费
	 * @param $method 请求方法
	 * @param $name 域名
	 * @param $data 域名注册详细参数
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function DomainRenew($method,$name,$data){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name) || (count($data) <= 0)) {
			$return['status'] = 0;
			return $return;
		}
		if (empty($data['begindate']) || empty($data['period'])) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method,array($name,self::$api_encode,$data['begindate']));
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:QueryTransferInState  域名转入状态查询接口
	 * @param $method 请求方法
	 * @param $name 域名
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function QueryTransferInState($method,$name){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name)) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$name,array());
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:DomainTransferIn  域名转入接口
	 * @param $method 请求方法
	 * @param $name 域名
	 * @param $data 域名转移密码
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function DomainTransferIn($method,$name,$data){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name) || count($data) <= 0) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$name,array());
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //转移成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:CancelTransferIn   取消域名转入接口
	 * @param $method 请求方法
	 * @param $name 域名
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function CancelTransferIn($method,$name){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name)) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$name,array());
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //转移成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:resendTransferInEmail    转入通知信重发接口
	 * @param $method 请求方法
	 * @param $name 域名
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function resendTransferInEmail($method,$name){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name)) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$name,array());
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //转移成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:templateDnList    查询信息列表下关联域名接口
	 * @param $method 请求方法
	 * @param $tname 模板名称
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function getTplDnList($method,$tname){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($tname)) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$tname,array());
		$data['testFlag'] = "新网数码";
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $tname, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = $t_arr2= array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				if ($key == 1) {
					$t_arr['info'] = isset($str_tmp[2])?$str_tmp[2]:0;
				}else{
					$t_arr[$str_tmp[0]] = $str_tmp[1];
				}
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //转移成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:createTemplate    创建信息模板接口
	 * @param $method 请求方法
	 * @param $tname 模板名称
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function createTemplate($method,$tname,$data,$extdata = array()){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($tname) || count($data) <= 0) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$tname,array());
		$data['testFlag'] = "新网数码";
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $tname, $data);
		$res = self::Get($url,$extdata);
		
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //转移成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:modTemplate    修改信息模板接口
	 * @param $method 请求方法
	 * @param $tname 模板名称
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function modTemplate($method,$tname,$data,$extdata = array()){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($tname) || count($data) <= 0) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$tname,array());
		$data['testFlag'] = "新网数码";
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $tname, $data);
		$res = self::Get($url,$extdata);
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //转移成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:templateInfo    查询信息模板接口
	 * @param $method 请求方法
	 * @param $tname 模板名称
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function templateInfo($method,$tname){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($tname)) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$tname,array());
		$data['testFlag'] = "新网数码";
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $tname, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //转移成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:delTemplate    删除信息模板接口
	 * @param $method 请求方法
	 * @param $tname 模板名称
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function delTemplate($method,$tname,$setdata){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($tname) || count($setdata) < 0) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$tname,array());
		$data['testFlag'] = "新网数码";
		$data['uemail'] = isset($setdata['email'])?$setdata['email']:"";
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $tname, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //转移成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:registrantChange    域名模板过户正式版
	 * @param $method 请求方法
	 * @param $tname 模板名称
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function registrantChange($method,$name,$setdata){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name) || count($setdata) < 0) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$name,array());
		$data['testFlag'] = "新网数码";
		$data['old_tname'] =  isset($setdata['old_tname'])?$setdata['old_tname']:"";
		$data['new_tname'] = isset($setdata['new_tname'])?$setdata['new_tname']:"";
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //转移成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:rfUpload    上传信息模板实名制资料接口
	 * @param $method 请求方法
	 * @param $tname 模板名称
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function rfUpload($method,$tname,$data,$extdata = array()){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($tname) || count($data) <= 0) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$tname,array());
		$data['testFlag'] = "新网数码";
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $tname, $data);
		$res = self::Get($url,$extdata);

		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:GetProductKey  获取域名管理密码
	 * @param $method 请求方法
	 * @param $name 域名
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function getProductKey($method,$name){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name)) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = MD5($method.self::$api_user.self::$password.$name."E");
		$data['keyname'] = "Password";
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
	/**
	 * 新网调用接口:setDomainStatus    域名状态设置接口
	 * @param $method 请求方法
	 * @param $tname 模板名称
	 * @return array 数组格式 status 》0表示URL请求失败，1表示RUL请求成功 ,2,新网认证失败
	 */
	public static function setDomainStatus($method,$name,$setdata){
		$return = array("status" => 0, "list" => "",);
		if (empty($method) || empty($name) || count($setdata) < 0) {
			$return['status'] = 0;
			return $return;
		}
		$data['checksum'] = self::Sign($method.$name,array());
		if (isset($setdata['addStatus'])) {
			$data['addStatus'] =  $setdata['addStatus'];
		}
		if (isset($setdata['delStatus'])) {
			$data['delStatus'] =  $setdata['delStatus'];
		}
		//Curl,POST > charset  ,  client  ,  enc
		$url = self::UrlSet($method, $name, $data);
		$res = tCurl::post($url, array(), "");
		if($res['http_code'] == 200){
			//处理返回结果格式
			$t_arr  = array();
			$e_str = explode("&",$res['content']);
			foreach ($e_str as $key=>$val) {
				$str_tmp =  explode("=",$val);
				$t_arr[$str_tmp[0]] = $str_tmp[1];
			}
			if ($t_arr['ret'] == 100) {
				$return['status']   = 1; //转移成功
				$return['list']		= $t_arr;
			}else{
				$return['status']   = 2; //失败
				$return['list']		= $t_arr;
			}
			unset($e_str);
			unset($str_tmp);
		}else{
			$return['status'] =0;//请求失败
		}
		return $return;
	}
}