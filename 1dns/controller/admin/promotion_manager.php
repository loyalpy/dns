<?php
/**
 * 促销管理
 * by Thinkhu 2014 
 */
class promotion_manager extends UCAdmin{
	function __construct(){
		parent::__construct('promotion_manager');
	}
	public function sms_sendbyfile(){
		$content = "邮政招男工100名25-48岁，致电85132239，13456832795，地址：建设四路风情大道交叉口三益线杭州邮件处理中心，找工作上万才www.10000rc.com";
		$content = "邮政招男工100名25-48岁，致电85132239，13456832795，地址：建设四路风情大道交叉口三益线杭州邮件处理中心 www.10000rc.com";
		
		//$ret = C("sms")->send('13735835575',$content);
		//if($ret){echo "发送成功！";}
		//die
		//die("ok");
		$file_name = ROOT_PATH."cache/sms.txt";
		$handle    = fopen($file_name, "r");
		$re        = array();
		if ($handle) {
			while (!feof($handle)) {
				$s = fgets($handle);
				$s  = trim(preg_replace("/[ ]{1,}/"," ",str_replace("\t"," ",$s)));
				$s_arr = explode(" ", $s);
				foreach($s_arr as $v){
					if(tValidate::is_mobile($v)){
						$re[$v] = $s_arr[1].((isset($s_arr[2]) && $s_arr[2]!=$v)?"/{$s_arr[2]}":"");
					}
				}
			}
			fclose($handle);
		}
		$re = array_unique($re);
		$re['13857127088'] = "老田";
		$ttt = array_chunk($re,500,true);
		$content = "邮政招男工100名25-48岁，致电85132239，13456832795，地址：建设四路风情大道交叉口三益线杭州邮件处理中心 www.10000rc.com";
		foreach($ttt as $v){
			$re = C("sms")->send(implode(",",array_keys($v)),$content);
			if($re){
				echo " send ok!";
			}
			sleep(1);
		}
		return $re;
	}
}
?>