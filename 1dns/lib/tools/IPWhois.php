<?php
class IPWhois{
	public $servers = array(
	    "biz" => "whois.neulevel.biz",
		"com" => "whois.internic.net",
        "us" => "whois.nic.us",
        "coop" => "whois.nic.coop",
        "info" => "whois.nic.info",
        "name" => "whois.nic.name",
        "net" => "whois.internic.net",
        "gov" => "whois.nic.gov",
        "edu" => "whois.internic.net",
        "mil" => "rs.internic.net",
        "int" => "whois.iana.org",
        "ac" => "whois.nic.ac",
        "ae" => "whois.uaenic.ae", 
        "at" => "whois.ripe.net",
        "au" => "whois.aunic.net",
        "be" => "whois.dns.be",
        "bg" => "whois.ripe.net",
        "br" => "whois.registro.br",
        "bz" => "whois.belizenic.bz",
        "ca" => "whois.cira.ca",
        "cc" => "whois.nic.cc",
        "ch" => "whois.nic.ch",
        "cl" => "whois.nic.cl",
        "cn" => "whois.cnnic.net.cn",
        "cz" => "whois.nic.cz",
        "de" => "whois.nic.de",
        "fr" => "whois.nic.fr", 
        "hu" => "whois.nic.hu",
        "ie" => "whois.domainregistry.ie",
        "il" => "whois.isoc.org.il", 
        "in" => "whois.ncst.ernet.in",
        "ir" => "whois.nic.ir",
        "mc" => "whois.ripe.net",
        "to" => "whois.tonic.to",
        "tv" => "whois.tv",
        "ru" => "whois.ripn.net",
        "org" => "whois.pir.org",
        "aero" => "whois.information.aero",
        "nl" => "whois.domain-registry.nl" 
	);
	public function query($question){
		if(tValidate::is_ip($question)){//查IP
			$ip = $question;
			$w = $this->get_whois_from_server('whois.iana.org' , $ip);  
			preg_match('@whois\.[\w\.]*@si' , $w , $data);  
			$whois_server = $data[0];  
			$whois_data = $this->get_whois_from_server($whois_server , $ip);  
			return $whois_data;  
		}else{//查域名
			$domain = strtolower(trim($question));  
	    	$domain = preg_replace('/^http:\/\//i', '', $domain);  
		    $domain = preg_replace('/^www\./i', '', $domain);  
		    $domain = explode('/', $domain);  
			$domain = trim($domain[0]);  
	    	$_domain = explode('.', $domain);  
			$lst = count($_domain)-1;  
			$ext = $_domain[$lst];
			if(!isset($this->servers[$ext])){
				return "no server!";
			}
			$whois_server = $this->servers[$ext];  
	    	$data = $this->get_whois_from_server($whois_server,$domain);
			preg_match('@Whois Server:(.*?)Referral URL:@si' , $data , $match); 
			if(isset($match[1])){
				$whois_server = trim($match[1]);
				$data .=  preg_replace('/<a (.*?)<\/a>/','',$this->get_whois_from_server($whois_server,$domain));
			}
			return $data;
		} 
	}
	public function get_whois_from_server($server , $import){  
	    $data = '';
	    try{
        	$f = @fsockopen($server, 43, $errno, $errstr, 30);    //Open a new connection 
	    }catch (Exception $e) {
	    	return $e->getMessage();
	    }   
		if(!$f)return 'soket error !';
		//不能设置超时
        if(!stream_set_timeout($f , 30))return ('Unable to set set_timeout');
        //输入
        if($f)fputs($f, "$import\r\n");
        if(!stream_set_timeout($f , 30)){return('Unable to stream_set_timeout');}  
        stream_set_blocking ($f, 0 );
        if($f){
	        while (!feof($f)){
	        	$data .= fread($f , 128);  
	        }
	        fclose($f);
        }
        return $data;
   }
}
?>