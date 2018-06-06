<?php
class DNSapi{
	private static $key   = "d3352d339e245e44c684da4bb94cdc5b";
	private static $https = "http://";
	private static $timeout = 20;
	private static function sign($url = "",$data = array(),$encrypt_key = ""){
		$encrypt_key = empty($encrypt_key)?self::$key:$encrypt_key;
		ksort($data);
		$data['timestamp'] = time()+7200;
		$datastr = "";
		foreach($data as $k=>$v){
			$datastr .= "&{$k}={$v}";
		}
		$datastr  = substr($datastr,1);
		$datastr .= "&checkcode=".md5($encrypt_key.$datastr.$encrypt_key);
		$datastr .= "&rand=".rand(10000,99999);

		return $url.($datastr?"?{$datastr}":"");
	}
	public static function get($host,$uri = "/",$data = array(), $encrypt_key = ""){
		$url  = self::$https.$host.$uri;
		$url  = self::sign($url,$data,$encrypt_key);
		$ret  = tCurl::get($url,self::$timeout);

		if($ret['http_code'] == 200){
			return $ret['content'];
		}else{
			return "{$ret['http_code']}";
		}
	}
	public static function post($host,$uri = "/",$data = array(),$req_data = array(),$encrypt_key = ""){
		$url  = self::$https.$host.$uri;
		$url  = self::sign($url,$data,$encrypt_key);
		$ret  = tCurl::post($url,$req_data,self::$timeout);
		if($ret['http_code'] == 200){
			return $ret['content'];
		}else{
			return "{$ret['http_code']}";
		}
	}
	//判断域名NS是否在我们这边
	public static function ns_in($domain,$ns_group = "free"){
		$result = array("status"=>0,"ns"=>"");
		$ret = self::ns($domain);
		$result['ns'] = is_array($ret)?implode(";",$ret):$ret;

		$ns_group_row = M("@domain_ns_group")->get_cache_by_ns($ns_group);
		if(!isset($ns_group_row['ns_group'])){
			$result['status'] = -1;
		}else{
			$chk_ns = explode(";",$ns_group_row['ns']);
			$chk_ns = array_filter($chk_ns);
			if(is_array($ret) && count($ret) > 0){
				foreach($ret as $v){
					if(in_array($v,$chk_ns)){
						$result['status'] = 1;
					}
				}
			}else{
				$result['status'] = -1;
			}
		}
		return $result;	
	}
	//查询NS
	public static function ns($domain){
		return self::query($domain,"ns");
	}
	//查询A
	public static function a($domain){
		return self::query($domain,"a");
	}
	//查询CNAME记录
	public static function cname($domain){
		return self::query($domain,"cname");
	}
	//查询MX记录
	public static function mx($domain){
		return self::query($domain,"mx");
	}
	//dns查询
	public static function query($domain="",$qtype="ns",$ns_server = "8.8.8.8",$host="47.88.13.153:8000"){		
		$uri = "/CheckDNS";
		if(empty($domain)){
			return 0;
		}
		$qtype     = strtolower($qtype);
		//确定参数
		$exec_parm = array();
		$exec_parm['domain']     = $domain;
		$exec_parm['dopt']       = ($qtype == "ns")?"trace":"notrace";        
        $exec_parm['qtype']      = $qtype;
        $exec_parm['qopt']       = "";
        $exec_parm['qclass']     = "";
        $exec_parm['ns_server']  = $ns_server;
        $exec_parm['timeout']    = 15;//15秒
        
		#执行主服务器组程序
		$ret = self::get($host,$uri,$exec_parm);		
		if(in_array($ret,array("timeout",""))){
			return -1;
		}else{
			$result = array();
			switch($qtype){
				case "ns":
					$tmp_arr = array();
					$tmp_arr = explode("\n",$ret);
					$tmp_arr = array_filter($tmp_arr);
					if($tmp_arr){
						foreach($tmp_arr as $k=>$v){
							if($v{0} == ";"){
								unset($tmp_arr[$k]);
								continue;
							}else{
								$tmp2 = explode(" ",trim(preg_replace("/[ ]{1,}/"," ",str_replace("\t"," ",$v))));
								if(isset($tmp2[0]) && $tmp2[0] != "{$domain}."){
									unset($tmp_arr[$k]);
									continue;
								}
								$tmp3 = $tmp2?array_pop($tmp2):"";
								$tmp4 = $tmp2?array_pop($tmp2):"";
								if(strtoupper($qtype) == $tmp4){
									$len = strlen($tmp3);
									$result[] = substr($tmp3,$len-1) === "."?substr($tmp3,0,$len-1):$tmp3;
								}
							}
						}
					}
					break;
				case "cname":
				case "mx":
				case "a":
					$tmp_arr = array();
					$tmp_arr = explode("\n",$ret);
					$tmp_arr = array_filter($tmp_arr);
					if($tmp_arr){
						foreach($tmp_arr as $k=>$v){
							if($v{0} == ";"){
								unset($tmp_arr[$k]);
								continue;
							}else{
								$tmp2 = explode(" ",trim(preg_replace("/[ ]{1,}/"," ",str_replace("\t"," ",$v))));
								if(isset($tmp2[0]) && $tmp2[0] != "{$domain}."){
									unset($tmp_arr[$k]);
									continue;
								}
								$tmp3 = $tmp2?array_pop($tmp2):"";
								$tmp4 = $tmp2?array_pop($tmp2):"";
								if(strtoupper($qtype) == $tmp4){
									$len = strlen($tmp3);
									$result[] = substr($tmp3,$len-1) === "."?substr($tmp3,0,$len-1):$tmp3;
								}
							}
						}
					}
					break;
				default:
					break;
			}
			return array_unique($result);
		}
	}


	//一些应用
	public static function en_slow_js($data){

	}
	//查询Whois到期时间
	public static function whois_expiry($querystr){
		if(tValidate::is_cn($querystr)){
			App::uselib("tools.idna_convert");
			$idna_convert_obj = new idna_convert();
			$querystr = $idna_convert_obj->encode($querystr);
			unset($idna_convert_obj);
		}
		$res = self::whois($querystr);
		$preg_no = '/No match for domain "'.$querystr.'"/i';
		$pregstr = "/Registrar Registration Expiration Date:([0-9a-zA-Z\-: ]+)/is";
		if($res){
			if(preg_match($preg_no, $res,$matched)){
				return array("status"=>-1,"expiry"=>0);
			}
			if(preg_match($pregstr, $res,$matched)){
				if(isset($matched[1]) && trim($matched[1])){
					return array("status"=>1,"expiry"=>substr(trim($matched[1]),0,10));
				}				
			}
		}
		
		return array("status"=>0,"expiry"=>0,"msg"=>$res);
	}
	//查询whois邮箱
	public static function whois_email($querystr){
		if(tValidate::is_cn($querystr)){
			App::uselib("tools.idna_convert");
			$idna_convert_obj = new idna_convert();
			$querystr = $idna_convert_obj->encode($querystr);
			unset($idna_convert_obj);
		}

		$res = self::whois($querystr);
		$exp = array("xinnet","abuse","yinsibaohu");
		$pregstr = "/([a-z0-9_\-\.]+)@(([a-z0-9]+[_\-]?)\.)+[a-z]{2,3}/i";
		if (preg_match_all($pregstr,$res,$matched)) {
			$result = $matched[0];
			foreach($result as $key=>$v){
				$v = strtolower($v);
				if(!tValidate::is_email($v)){
					unset($result[$key]);
				}
				foreach($exp as $v2){
					if(strpos($v,$v2) === false){
						continue;
					}else{
						unset($result[$key]);
					}
				}
			}
			return $result;
		}else{
			return array();
		}
	}
	//查询whois
	public static function whois($querystr){
		$host="47.88.13.153:8000";
		$uri = "/Whois";
		if(empty($querystr)){
			return 0;
		}
		//确定参数
		if(tValidate::is_cn($querystr)){
			App::uselib("tools.idna_convert");
			$idna_convert_obj = new idna_convert();
			$querystr = $idna_convert_obj->encode($querystr);
			unset($idna_convert_obj);
		}
		$exec_parm = array();
		$exec_parm['querystr']     = $querystr;
        $exec_parm['timeout']      = 6;//15秒

        #执行主服务器组程序
		$ret = self::get($host,$uri,$exec_parm);
		if(in_array($ret,array("timeout",""))){
			return -1;
		}else{
			return $ret;
		}
	}
}
?>