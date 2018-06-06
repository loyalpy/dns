<?php 
class SMS{
	private static $instance = null;
	private static $islogin = false;
	public static function get_instance($smsinfo = array()){
		$smsinfo = empty($smsinfo)?App::get_conf("com.sms"):$smsinfo;		
		if(self::$instance != NULL && is_object(self::$instance)){
			return self::$instance;
		}
		switch($smsinfo['type']){
			case "ym":
				require_once 'YmClient.php';
				//$gwUrl,$serialNumber,$password,$sessionKey,$proxyhost,$proxyport,$proxyusername,$proxypassword,$connectTimeOut,$readTimeOut
				$gwUrl        = $smsinfo['gm_url'];
				$serialNumber = $smsinfo['user_no'];
				$password     = $smsinfo['user_pass'];
				$sessionKey   = $smsinfo['skey'];
				self::$instance = new YmClient($gwUrl,
					$serialNumber,
					$password,
					$sessionKey
				);
				self::$instance->setOutgoingEncoding("UTF-8");
				return self::$instance;
				break;
		}
	}
	public static function getError(){
	    return self::get_instance()->getError();
	}	
	public static function login(){
		if(self::$islogin == false){
			$statusCode = self::get_instance()->login();
			if($statusCode != null && $statusCode == "0"){
				self::$islogin = true;
			}
		}
		return self::$islogin;		
	}
	
	/**
	 * 注销登录 用例
	 */
	public static function logout(){
		$statusCode = self::get_instance()->logout();
		return $statusCode;
	}
	
	/**
	 * 获取版本号 用例
	 */
	public static function getVersion(){
		$statusCode =  self::get_instance()->getVersion();
		return $statusCode;
	}
		
	/**
	 * 取消短信转发 用例
	 */
	public static function cancelMOForward(){
		$statusCode = self::get_instance()->cancelMOForward();
		return $statusCode;
	}
	
	/**
	 * 短信充值 用例
	 */
	public static function chargeUp($cardId,$cardPass){
		/**
		 * $cardId [充值卡卡号]
		 * $cardPass [密码]
		 *
		 * 请通过亿美销售人员获取 [充值卡卡号]长度为20内 [密码]长度为6
		 *
		 */
		$statusCode = self::get_instance()->chargeUp($cardId,$cardPass);
		return $statusCode;
	}
	
	
	/**
	 * 查询单条费用 用例
	 */
	public static function getEachFee(){
		$fee = self::get_instance()->getEachFee();
		return $fee;
	}
	
	
	/**
	 * 企业注册 用例
	 */
	public static function registDetailInfo(){
		$eName = "xx公司";
		$linkMan = "陈xx";
		$phoneNum = "010-1111111";
		$mobile = "159xxxxxxxx";
		$email = "xx@yy.com";
		$fax = "010-1111111";
		$address = "xx路";
		$postcode = "111111";
	
		/**
		 * 企业注册  [邮政编码]长度为6 其它参数长度为20以内
		 *
		 * @param string $eName 	企业名称
		 * @param string $linkMan 	联系人姓名
		 * @param string $phoneNum 	联系电话
		 * @param string $mobile 	联系手机号码
		 * @param string $email 	联系电子邮件
		 * @param string $fax 		传真号码
		 * @param string $address 	联系地址
		 * @param string $postcode  邮政编码
		 *
		 * @return int 操作结果状态码
		 *
		 */
		$statusCode = self::get_instance()->registDetailInfo($eName,$linkMan,$phoneNum,$mobile,$email,$fax,$address,$postcode);
		return $statusCode;
	
	}
	
	/**
	 * 更新密码 用例
	 */
	public static function updatePassword($password){
		/**
		 * [密码]长度为6
		 *
		 * 如下面的例子是将密码修改成: 654321
		 */
		$statusCode = self::get_instance()->updatePassword($password);
		return $statusCode;
	}
	
	/**
	 * 短信转发 用例
	 */
	public static function setMOForward($mobile){
		$statusCode = self::get_instance()->setMOForward($mobile);
		return $statusCode;
	}
	
	/**
	 * 得到上行短信 用例
	 */
	public static function getMO(){
		global $client;
		$moResult = self::get_instance()->getMO();
		echo "返回数量:".count($moResult);
		foreach($moResult as $mo)
		{
			//$mo 是位于 Client.php 里的 Mo 对象
			// 实例代码为直接输出
			echo "发送者附加码:".$mo->getAddSerial();
			echo "接收者附加码:".$mo->getAddSerialRev();
			echo "通道号:".$mo->getChannelnumber();
			echo "手机号:".$mo->getMobileNumber();
			echo "发送时间:".$mo->getSentTime();
			 
			/**
			 * 由于服务端返回的编码是UTF-8,所以需要进行编码转换
			*/
			echo "短信内容:".iconv("UTF-8","GBK",$mo->getSmsContent());
			 
			// 上行短信务必要保存,加入业务逻辑代码,如：保存数据库，写文件等等
		}
	
	}
	
	/**
	 * 短信发送 用例
	 */
	public static function sendSMS($mobiles,$content,$sign="万才网"){
		if(self::login()){
			$content = "【{$sign}】{$content}";
			//$content = iconv("UTF-8","GB2312//IGNORE","【{$sign}】{$content}");
			$statusCode = self::get_instance()->sendSMS($mobiles,$content);
			return $statusCode;
		}else{
			return -3;
		}
		
	}
	
	/**
	 * 余额查询 用例
	 */
	public static function getBalance(){
		$balance = self::get_instance()->getBalance();
		return $balance;
	}
	
	/**
	 * 短信转发扩展 用例
	 */
	public static function setMOForwardEx($mobiles){
		global $client;
	
		/**
		 * 向多个号码进行转发短信
		 *array('159xxxxxxxx','159xxxxxxxx','159xxxxxxxx')
		 * 以数组形式填写手机号码
		 */
		$statusCode = self::get_instance()->setMOForwardEx($mobiles);
		return $statusCode;
	}
	
}
?>