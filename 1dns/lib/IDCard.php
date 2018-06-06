<?php
class IDCard{
	public static function check($id_card){
		if(strlen($id_card) == 18){
			return self::idcard_checksum18($id_card);
		}elseif((strlen($id_card) == 15)){
			$id_card = self::idcard_15to18($id_card);
			return self::idcard_checksum18($id_card);
		}else{
			return false;
		}
	}
	public static function get_sex($id_card) { //根据身份证号，自动返回性别		
		if((strlen($id_card) == 15)){
			$id_card = self::idcard_15to18($id_card);
		}
		if(!self::check($id_card))return '';
		$sexint = (int)substr($id_card,16,1);		 
		return $sexint % 2 === 0 ? 2:1;
	}
	public static function get_birth($id_card,$format='Y,n'){
		if((strlen($id_card) == 15)){
			$id_card = self::idcard_15to18($id_card);
		}
		return tTime::get_datetime($format,strtotime(substr($id_card,6,8)));
	}
	//获取籍贯
	public static function get_from($id_card,$len = 6){
		return M("city_from")->get_one("code=".substr($id_card,0,$len),"name");
	}
	// 计算身份证校验码，根据国家标准GB 11643-1999
	public static function idcard_verify_number($idcard_base){
		if(strlen($idcard_base) != 17){
			return false;
		}
		//加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
		$checksum = 0;
		for ($i = 0; $i < strlen($idcard_base); $i++){
			$checksum += substr($idcard_base, $i, 1) * $factor[$i];
		}
		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];
		return $verify_number;
	}
	// 将15位身份证升级到18位
	public static function idcard_15to18($idcard){
		if (strlen($idcard) != 15){
			return false;
		}else{
			// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
			if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
				$idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
			}else{
				$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
			}
		}
		$idcard = $idcard . self::idcard_verify_number($idcard);
		return $idcard;
	}
	// 18位身份证校验码有效性检查
	public static function idcard_checksum18($idcard){
		if (strlen($idcard) != 18){return false;}
		$idcard_base = substr($idcard, 0, 17);
		if (self::idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
			return false;
		}else{
			return true;
		}
	}
	//获取身份证号前6位对应的数据库
	public static function init_fromdata($inmysql = 1){
		$file_name = ROOT_PATH."lib/IDCard.txt";
		$handle    = fopen($file_name, "r");
        $re        = array();
		if ($handle) {
			while (!feof($handle)) {
				$s = fgets($handle);
				$s  = trim(preg_replace("/[ ]{1,}/"," ",str_replace("\t"," ",$s)));
				$s_arr = explode(" ", $s);
				if(count($s_arr) == 2){
					$re[trim($s_arr[0])] = trim($s_arr[1]);
				}				
			}
			fclose($handle);
		}
		if($re && $inmysql){
			M("city_from")->del(1);
			$data = array();
			foreach($re as $k=>$v){
				$data[] = array(
					'code' => $k,
					'name' => $v,
				);
			}
			M("city_from")->add_more($data);
		}
		return $re;
	}
} 
?>