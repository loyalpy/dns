<?php
/**
 * @brief 系统统验证类文件
 * @class IValidate
 */
class tValidate{
	// ** 开头验证
	public static function end_with($str,$v="-"){
		return (bool)preg_match('/'.$v.'$/i',$str);
	}
	// ** 结尾验证
	public static function start_with($str,$v="-"){
		return (bool)preg_match('/^'.$v.'/i',$str);
	}
	//检查字符串是否包含中文
	public static function is_cn($str = ''){
		//return (bool)preg_match('/([u4e00-u9fa5])/',$str);
		return (bool)preg_match('/([\x80-\xff])/',$str);
	}
    //Email格式验证
    public static function is_email($str=''){
        return (bool)preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)+$/i',$str);
    }
    //QQ号码验证
    public static function is_qq($str=''){
        return (bool)preg_match('/^[1-9][0-9]{4,}$/i',$str);
    }
    //身份证验证包括一二代身份证
    public static function is_idcard($str=''){
        return (bool)preg_match('/^\d{15}(\d{2}[0-9x])?$/i',$str);
    }
    //判断相对域名合法性质
    public static function is_domain_node($str=''){
        if(self::start_with($str,"-") || self::end_with($str,"-")){
        	return false;
        }
        return (bool)preg_match('/^[-A-Za-z0-9_\x80-\xff]+$/i',$str);
     }
    //判断是否是顶级域名
    public static function is_domain($str=''){
    	$tmp = explode(".",$str);
    	foreach($tmp as $v){
    		if(!self::is_domain_node($v)){
    			return false;
    		}
    	}
    	$tmp = self::support_domain();
        if(in_array($str,$tmp)){
            return false;
        }

        $tmp = "(".implode(")|(",$tmp).")";
        return (bool)preg_match('/^([-A-Za-z0-9\x80-\xff]+)\.('.$tmp.')$/i',$str);
    }
    //判断是否是合法的二(多)级域名
    public static function is_domain2($str=''){
    	$tmp = explode(".",$str);
        foreach($tmp as $v){
    		if(!self::is_domain_node($v)){
    			return false;
    		}
    	}
    	$tmp = self::support_domain();
        if(in_array($str,$tmp)){
            return false;
        }
        $tmp = "(".implode(")|(",$tmp).")";
        return (bool)preg_match('/^([-A-Za-z0-9\x80-\xff\.]+)\.('.$tmp.')$/i',$str);
    }
    //判断是否是合法的SRV hostname
    public static function is_srv_hostname($str){
        return (bool)preg_match('/^(_([-A-Za-z0-9\x80-\xff]+))\.(_([-A-Za-z0-9\x80-\xff]+))$/i',$str);
    }
    //判断是否是hostname
    public static function is_hostname($str=''){
    	$tmp = explode(".",$str);
    	foreach($tmp as $v){
            if(!self::is_domain_node($v)){
                return false;
            }
        }
    	return (bool)preg_match('/^([-A-Za-z0-9_\.]+)$/i',$str);
    }
    //判断是否是合法的URL请求
    public static function is_request_url($str=''){
    	return (bool)preg_match('/^http(s)?:\/\/([\w-\.\/]+)((:\d)?)/i',$str);
    }
    //判断是否为整数
    public static function is_int($str=''){
    	return (bool)preg_match('/^\+?[1-9][0-9]*$/i',$str);
    }
    //此IP验证只是对IPV4进行验证。
    public static function is_ip($str=''){
        return (bool)preg_match('/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/i',$str);
    }
    //此为IPV 6验证
    public static function is_ipv6($str = ""){
        return (bool)preg_match('/^\s*((([0-9A-Fa-f]{1,4}:){7}(([0-9A-Fa-f]{1,4})|:))|(([0-9A-Fa-f]{1,4}:){6}(:|((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})|(:[0-9A-Fa-f]{1,4})))|(([0-9A-Fa-f]{1,4}:){5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){4}(:[0-9A-Fa-f]{1,4}){0,1}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){3}(:[0-9A-Fa-f]{1,4}){0,2}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){2}(:[0-9A-Fa-f]{1,4}){0,3}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)(:[0-9A-Fa-f]{1,4}){0,4}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(:(:[0-9A-Fa-f]{1,4}){0,5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})))(%.+)?\s*$/i', $str);
    	//return filter_var($str,FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }
    //邮政编码验证
    public static function is_zip($str=''){
        return (bool)preg_match('/^\d{6}$/i',$str);
    }
    //验证字符串的长度，和数值的大小。$str 为字符串时，判定长度是否在给定的$min到$max之间的长度，为数值时，判定数值是否在给定的区间内。
    public static function is_len($str, $min, $max){
        if(is_int($str)) return $str >= $min && $str <= $max;
        if(is_string($str))return IString::getStrLen($str) >= $min && IString::getStrLen($str) <= $max;
        return false;
    }
    //电话号码验证
    public static function is_phone($str=''){
        return (bool)preg_match('/^((\d{3,4})|\d{3,4}-)?\d{7,8}(-\d+)*$/i',$str);
    }
    //手机号码验证
    public static function is_mobile($str=''){
		return (bool)preg_match("!^(?:13\d|14[0-9]|15[0-9]|17[0-9]|18[0-9])-?\d{5}(\d{3}|\*{3})$!",$str);
    }
    //匹配帐号是否合法(字母开头，默认允许4-16字节【有效位数可自由定制】，允许字母数字下划线)
    public static function is_account($str, $minlen=4, $maxlen=16){
        return (bool)preg_match('/^[a-zA-Z][a-zA-Z0-9_]{'.$minlen.','.$maxlen.'}$/i',$str);
    }
    //Url地址验证
    public static function is_url($str=''){
        return (bool)preg_match('/^[a-zA-z]+:\/\/(\w+(-\w+)*)(\.(\w+(-\w+)*))+(\/?\S*)?$/i',$str);
    }
    //正则验证接口
    public static function reg_check($reg, $str=''){
        return (bool)preg_match('/^'.$reg.'$/i',$str);
    }
	//判断字符串是否为空
    public static function is_empty($str){
         return (bool)preg_match('/\S+/i',$str);
    }
    //支持的域名种类
    public static function support_domain(){
        $tmp = array("ee","pe","sc.cn","pk","top","wang","pt","hl.cn","ph","to","fm","su","com.au","au","bg","ru","jp","co.jp","eu","de","com","net","net.cn","org","gov.cn","gov","info","me","cc","com.cn","edu.cn","gd.cn","com.hk","tw.cn","org.cn","cn","name","biz","tv","la","ml","asia","tel","co","cm","in","bz","vc","ag","mn","sc","us","ws","travel","tm","io","ac","sh","tw","mobi","hk","pw","so","com.hk","com.tw","hk.cn","中国","公司","网络","通用网址","白金词");
        if(isset(App::$data['data_config']["suport_domain"]) && is_array(App::$data['data_config']["suport_domain"])){
            $tmp = array_merge($tmp,App::$data['data_config']["suport_domain"]);
        }
        return $tmp;
    }
        
}
?>
