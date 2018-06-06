<?php
/**
 * @class tClient
 * @brief tClient 获取客户端信息
 */
class tClient{
	/**
	 * @brief 获取客户端ip地址
	 * @return string 客户端的ip地址
	 */
	public static function get_ip($path = 0){
	    $real_ip = $proxy = '';
	    if (isset($_SERVER)){
	        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
	            $real_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	            $proxy = $_SERVER["REMOTE_ADDR"];
	        }elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
	            $real_ip = $_SERVER["HTTP_CLIENT_IP"];
	            $proxy = $_SERVER["REMOTE_ADDR"];
	        }elseif (isset($_SERVER["HTTP_X_CLIENT_ADDRESS"])) {
	            $real_ip = $_SERVER["HTTP_X_CLIENT_ADDRESS"];
	            $proxy = $_SERVER["REMOTE_ADDR"];
	        }else {
	            $real_ip = isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:"0.0.0.0";
	        }
	    }else{
	        if (getenv("HTTP_X_FORWARDED_FOR")){
	            $real_ip = getenv("HTTP_X_FORWARDED_FOR");
	            $proxy = $_SERVER["REMOTE_ADDR"];
	        }elseif (getenv("HTTP_CLIENT_IP")) {
	            $real_ip = getenv("HTTP_CLIENT_IP");
	            $proxy = $_SERVER["REMOTE_ADDR"];
	        }elseif (getenv("HTTP_X_CLIENT_ADDRESS")) {
	            $real_ip = getenv("HTTP_X_CLIENT_ADDRESS");
	            $proxy = $_SERVER["REMOTE_ADDR"];
	        }else{
	            $real_ip = getenv("REMOTE_ADDR");
	        }
	    }
	    preg_match("/[\d\.]{7,15}/", $real_ip, $match);
	    $real_ip = !empty($match[0]) ? $match[0] : '0.0.0.0';
	    return $real_ip.(($path == 1 && $proxy)?"-{$proxy}":"");
	}
	/**
	 * @brief 获取客户端浏览的上一个页面的url地址
	 * @return string 客户端上一个访问的url地址
	 */
	public static function get_refer(){
		return $_SERVER['HTTP_REFERER'];
	}
	/**
	 * @brief 获取客户端当前访问的时间戳
	 * @return int 时间戳
	 */
	public static function get_time(){
		if(tServer::is_geversion('5.1.0'))
			return $_SERVER['REQUEST_TIME'];
		else
			return time();
	}
	/**
	 * @brief 获取客户端浏览器信息
	 * 
	 */
	public static function get_browese(){
		global $_SERVER;
		if(!isset($_SERVER['HTTP_USER_AGENT']))return "none";
	    $agent = $_SERVER['HTTP_USER_AGENT'];
	    $browser = "other";
	    $rpos = array(
	    	'Maxthon'    => 'Maxthon',
	    	'MSIE 12.0'  => 'IE12.0',
	    	'MSIE 11.0'  => 'IE11.0',
	    	'MSIE 10.0'  => 'IE10.0',
	    	'MSIE 9.0'   => 'IE9.0',
	    	'MSIE 8.0'   => 'IE8.0',
	    	'MSIE 7.0'   => 'IE7.0',
	    	'MSIE 6.0'   => 'IE6.0',
	    	"MSIE"       => 'IE',
	    	'NetCaptor'  => 'NetCaptor',
	    	'Lynx'       => 'Lynx',
	    	'Opera'      => 'Opera',
	    	'Chrome'     => 'Google',	    	
	    	'Safari'     => 'Safari',
	    	'Firefox'    => 'Firefox',
	    	'iphone'     => 'Iphone',
	    	'ipod'       => 'Ipod',
	    	'ipad'       => 'Ipad',
	    	'android'    => 'android',
	    	'BlackBerry' => 'BlackBerry',
	    	'Nokia'          => 'Nokia',
	    	"MicroMessenger" => "WeiXin"
	    );
	    foreach($rpos as $key=>$v){
	    	if(strpos($agent,$key)){
	    		$browser = $v;
	    		break;	
	    	}
	    }
		return $browser; 
	}
	/**
	 * @brief 获取客户端操作系统信息
	 * 
	 */
	public static function get_pc(){
		global $_SERVER;
		if(!isset($_SERVER['HTTP_USER_AGENT']))return "none";
	    $agent = $_SERVER['HTTP_USER_AGENT'];
	    $os = $agent;
	    $rpos = array(
	    	'Fedora'          => 'Fedora',
	    	'FreeBSD'         => 'FreeBSD',
	    	'SunOS'           => 'SunOS',
	    	'OpenBSD'         => 'OpenBSD',
	    	'NetBSD'          => 'NetBSD',
	    	'DragonFly'       => 'DragonFly',
	    	'IRIX'            => 'IRIX',
	    	'Windows CE'      => 'Windows CE',
	    	'PalmOS'          => 'PalmOS',
	    	'Linux'           => 'Linux',
	    	'DragonFly'       => 'DragonFly',
	    	
	    	'Android'         => 'Android',
	    	'Mac OS X'        => 'Mac OS X',
	    	'iPhone'          => 'iPhone OS',
	    	'Symbian OS'      => 'Symbian',
	    	'SymbianOS'       => 'SymbianOS',
	    	'webOS'           => 'webOS',
	    	'PalmSource'      => 'PalmSource',

	    	'Windows NT 10.0' => 'Windows 10',
	        'Windows NT 6.3'  => 'Windows 9',
	        'Windows NT 6.2'  => 'Windows 8',
	    	'Windows NT 6.1'  => 'Windows 7',
	    	'Windows NT 6.0'  => 'Windows Vista',
	    	'Windows NT 5.2'  => 'Windows 2003',
	    	'Windows NT 5.1'  => 'Windows XP',
	    	'Windows NT 5.0'  => 'Windows 2000',	    	
	    	'Windows ME'      => 'Windows ME',
	    	'Windows'         => 'Windows Other',
	    	'PPC Mac OS X'    => 'OS X PPC',
	    	'Intel Mac OS X'  => 'OS X Intel',
	    	'Win98'           => 'Windows 98',
	    	'Win95'           => 'Windows 95',
	    	'WinNT4.0'        => 'Windows NT4.0',
	    	'Mac OS X Mach-O' => 'OS X Mach',
	    	'Ubuntu'          => 'Ubuntu',
	    	'Debian'          => 'Debian',
	    	'AppleWebKit'     => 'AppleWebKit',
	    	'Mint/8'          => 'Mint 8',
	    	'Minefield'       => 'Minefield Alpha',
	    	'gentoo'          => 'Gentoo',
	    	'Kubuntu'         => 'Kubuntu',
	    	'Slackware/13.0'  => 'Slackware 13',	    	
	    );
	    foreach($rpos as $key=>$v){
	    	if(strpos($agent,$key)){
	    		$os = $v;
	    		break;
	    	}
	    }
		return $os; 
	}
	/**
	 * 获取给定IP的物理地址
	 *
	 * @param string $ip
	 * @return string
	 */
	public static function convert_ip($ip) {
		$return = '';
		if(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
			$iparray = explode('.', $ip);
			if($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
				$return = '- LAN';
			} elseif($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
				$return = '- Invalid IP Address';
			} else {
				$tinyipfile = ROOT_PATH . '/common/misc/tinyipdata.dat';
				$fullipfile = ROOT_PATH . '/common/misc/wry.dat';
				if(@file_exists($tinyipfile)) {
					$return = self::convert_ip_tiny($ip, $tinyipfile);
				} elseif(@file_exists($fullipfile)) {
					$return = self::convert_ip_full($ip, $fullipfile);
				}
			}
		}
		return $return;
	}
	/**
	 * @see convert_ip_tiny()
	 */
	public static function convert_ip_tiny($ip, $ipdatafile) {
			static $fp = NULL, $offset = array(), $index = NULL;
	
			$ipdot = explode('.', $ip);
			$ip    = pack('N', ip2long($ip));
	
			$ipdot[0] = (int)$ipdot[0];
			$ipdot[1] = (int)$ipdot[1];
	
			if($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
				$offset = unpack('Nlen', fread($fp, 4));
				$index  = fread($fp, $offset['len'] - 4);
			} elseif($fp == FALSE) {
				return  '- Invalid IP data file';
			}
	
			$length = $offset['len'] - 1028;
			$start  = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);
	
			for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {
	
				if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
					$index_offset = unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
					$index_length = unpack('Clen', $index{$start + 7});
					break;
				}
			}
	
			fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
			if($index_length['len']) {
				return '- '.fread($fp, $index_length['len']);
			} else {
				return '- Unknown';
			}
	
	}
	
	/**
	 * @see convert_ip_full()
	 */
	public static function convert_ip_full($ip, $ipdatafile) {
		if (! $fd = @fopen ( $ipdatafile, 'rb' )) {
			return '- Invalid IP data file';
		}
	
		$ip = explode ( '.', $ip );
		$ipNum = $ip [0] * 16777216 + $ip [1] * 65536 + $ip [2] * 256 + $ip [3];
	
		if (! ($DataBegin = fread ( $fd, 4 )) || ! ($DataEnd = fread ( $fd, 4 )))
			return;
		@$ipbegin = implode ( '', unpack ( 'L', $DataBegin ) );
		if ($ipbegin < 0)
			$ipbegin += pow ( 2, 32 );
		@$ipend = implode ( '', unpack ( 'L', $DataEnd ) );
		if ($ipend < 0)
			$ipend += pow ( 2, 32 );
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;
	
		$BeginNum = $ip2num = $ip1num = 0;
		$ipAddr1 = $ipAddr2 = '';
		$EndNum = $ipAllNum;
	
		while ( $ip1num > $ipNum || $ip2num < $ipNum ) {
			$Middle = intval ( ($EndNum + $BeginNum) / 2 );
	
			fseek ( $fd, $ipbegin + 7 * $Middle );
			$ipData1 = fread ( $fd, 4 );
			if (strlen ( $ipData1 ) < 4) {
				fclose ( $fd );
				return '- System Error';
			}
			$ip1num = implode ( '', unpack ( 'L', $ipData1 ) );
			if ($ip1num < 0)
				$ip1num += pow ( 2, 32 );
	
			if ($ip1num > $ipNum) {
				$EndNum = $Middle;
				continue;
			}
	
			$DataSeek = fread ( $fd, 3 );
			if (strlen ( $DataSeek ) < 3) {
				fclose ( $fd );
				return '- System Error';
			}
			$DataSeek = implode ( '', unpack ( 'L', $DataSeek . chr ( 0 ) ) );
			fseek ( $fd, $DataSeek );
			$ipData2 = fread ( $fd, 4 );
			if (strlen ( $ipData2 ) < 4) {
				fclose ( $fd );
				return '- System Error';
			}
			$ip2num = implode ( '', unpack ( 'L', $ipData2 ) );
			if ($ip2num < 0)
				$ip2num += pow ( 2, 32 );
	
			if ($ip2num < $ipNum) {
				if ($Middle == $BeginNum) {
					fclose ( $fd );
					return '- Unknown';
				}
				$BeginNum = $Middle;
			}
		}
	
		$ipFlag = fread ( $fd, 1 );
		if ($ipFlag == chr ( 1 )) {
			$ipSeek = fread ( $fd, 3 );
			if (strlen ( $ipSeek ) < 3) {
				fclose ( $fd );
				return '- System Error';
			}
			$ipSeek = implode ( '', unpack ( 'L', $ipSeek . chr ( 0 ) ) );
			fseek ( $fd, $ipSeek );
			$ipFlag = fread ( $fd, 1 );
		}
	
		if ($ipFlag == chr ( 2 )) {
			$AddrSeek = fread ( $fd, 3 );
			if (strlen ( $AddrSeek ) < 3) {
				fclose ( $fd );
				return '- System Error';
			}
			$ipFlag = fread ( $fd, 1 );
			if ($ipFlag == chr ( 2 )) {
				$AddrSeek2 = fread ( $fd, 3 );
				if (strlen ( $AddrSeek2 ) < 3) {
					fclose ( $fd );
					return '- System Error';
				}
				$AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
				fseek ( $fd, $AddrSeek2 );
			} else {
				fseek ( $fd, - 1, SEEK_CUR );
			}
	
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr2 .= $char;
	
			$AddrSeek = implode ( '', unpack ( 'L', $AddrSeek . chr ( 0 ) ) );
			fseek ( $fd, $AddrSeek );
	
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr1 .= $char;
		} else {
			fseek ( $fd, - 1, SEEK_CUR );
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr1 .= $char;
	
			$ipFlag = fread ( $fd, 1 );
			if ($ipFlag == chr ( 2 )) {
				$AddrSeek2 = fread ( $fd, 3 );
				if (strlen ( $AddrSeek2 ) < 3) {
					fclose ( $fd );
					return '- System Error';
				}
				$AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
				fseek ( $fd, $AddrSeek2 );
			} else {
				fseek ( $fd, - 1, SEEK_CUR );
			}
			while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
				$ipAddr2 .= $char;
		}
		fclose ( $fd );
	
		if (preg_match ( '/http/i', $ipAddr2 )) {
			$ipAddr2 = '';
		}
		$ipaddr = "$ipAddr1 $ipAddr2";
		$ipaddr = preg_replace ( '/CZ88\.NET/is', '', $ipaddr );
		$ipaddr = preg_replace ( '/^\s*/is', '', $ipaddr );
		$ipaddr = preg_replace ( '/\s*$/is', '', $ipaddr );
		if (preg_match ( '/http/i', $ipaddr ) || $ipaddr == '') {
			$ipaddr = '- Unknown';
		}
	
		return '- ' . $ipaddr;
	
	}
	//判断是否手机访问
	public static function is_mobile(){
		if(!isset($_SERVER['HTTP_USER_AGENT']))return false;
    	$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
		$is_mobile = false;
		foreach ($mobile_agents as $device) {
			if (stristr($user_agent, $device)) {
					$is_mobile = true;
            		break;
        	}
    	}
    	return $is_mobile;
	}
}
?>