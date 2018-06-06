<?php
/**
 * @class Payment
 * @brief 支付方式 操作类
 */
//支付状态：支付失败
define ( "PAY_FAILED", - 1);
//支付状态：支付超时
define ( "PAY_TIMEOUT", 0);
//支付状态：支付成功
define ( "PAY_SUCCESS", 1);
//支付状态：支付取消
define ( "PAY_CANCEL", 2);
//支付状态：支付错误
define ( "PAY_ERROR", 3);
//支付状态：支付进行
define ( "PAY_PROGRESS", 4);
//支付状态：支付无效
define ( "PAY_INVALID", 5);
//支付状态：手工支付
define ( "PAY_MANUAL", 0);

class cls_payment{
	//加载支付方式插件类
	public function load($pay_plugin){
		$_plugin_path =  ROOT_PATH.'lib/plugins/payments/'.'pay_'.$pay_plugin.'/';
		$path = $_plugin_path . 'pay_' . $pay_plugin . '.php';
		if(file_exists ($path)){
			require_once ($path);
			$class_name = "pay_" . $pay_plugin;
			$pay_obj = new $class_name;
			return $pay_obj;
		}
	}
	//获取所有已添加的支付插件
	public function get_payment(){
		$query = new tQuery('payment as a');
     	$query->join = " join pay_plugin as b on a.plugin_id = b.id";
     	$query->fields = " a.id,a.plugin_id,a.name,a.status,b.description,b.logo,a.type";
     	$query->order = "a.order ASC,a.id DESC";
     	$list = $query->find();
     	return $list;
	}
    // 获取系统支付插件支持货币单位
	public function get_syscur( ){
		$CON_CURRENCY['CNY'] = ("人民币");
		$CON_CURRENCY['USD'] = ("美元");
		$CON_CURRENCY['EUR'] = ("欧元");
		$CON_CURRENCY['GBP'] = ("英磅");
		$CON_CURRENCY['CAD'] = ("加拿大元");
		$CON_CURRENCY['AUD'] = ("澳元");
		$CON_CURRENCY['RUB'] = ("卢布");
		$CON_CURRENCY['HKD'] = ("港币");
		$CON_CURRENCY['TWD'] = ("新台币 ");
		$CON_CURRENCY['KRW'] = ("韩元");
		$CON_CURRENCY['SGD'] = ("新加坡元");
		$CON_CURRENCY['NZD'] = ("新西兰元");
		$CON_CURRENCY['JPY'] = ("日元");
		$CON_CURRENCY['MYR'] = ("马元");
		$CON_CURRENCY['CHF'] = ("瑞士法郎");
		$CON_CURRENCY['SEK'] = ("瑞典克朗");
		$CON_CURRENCY['DKK'] = ("丹麦克朗");
		$CON_CURRENCY['PLZ'] = ("兹罗提");
		$CON_CURRENCY['NOK'] = ("挪威克朗");
		$CON_CURRENCY['HUF'] = ("福林");
		$CON_CURRENCY['CSK'] = ("捷克克朗");
		$CON_CURRENCY['MOP'] = ("葡币");
		return $CON_CURRENCY;
	}
	//据支付插件  获取该支付插件所支持的货币单位
	public function get_supportcur($input){
		$cur_name = "";
    	if(isset($input['DEFAULT'])){
        	$cur_name = ('商店默认货币');
        }else{
            $curlang = $this->get_syscur();
            if(isset($input['ALL'])){
            	$input = $curlang;
            }
           foreach($input as $k=>$v){
           		$cur_name .= $curlang[$k].",&nbsp;";
           }
           $cur_name = $cur_name ?rtrim($cur_name,',&nbsp;'):'';
        }
        return $cur_name;
    }
	//更新支付方式插件
	public function update($data,$pay_id){
		//初始化payment支付插件类
		$payment_obj = new tModel('payment');
		$payment_obj->set_data($data);
		if($pay_id){
			return $payment_obj->update(" id = ".$pay_id);
		}else{
			return $payment_obj->add();
		}
	}
	//根据支付方式配置编号  获取该插件的详细配置信息
	public function get_payment_byid($id){
		return M('payment as a,@pay_plugin as b')->get_row('a.id = '.$id.' and a.plugin_id = b.id',"a.*,b.logo,b.file_path,b.version,b.visibility");
	}
	//获取支付,并加入充值列表
	public function get_payment_info($payment_id,$argument){
		global $uid,$timestamp;
		$recharge_obj = new tModel('recharge');
		$insert_data = array();
		if($argument['id']>0){
			$insert_data = $recharge_obj->get_row("id={$argument['id']} AND status=0");
		}
		if(!isset($insert_data['id'])){
			$insert_data      = array(
			'uid'           => (isset($argument['uid']) && $argument['uid'])?$argument['uid']:$uid,
			'recharge_no'   => tUtil::create_numno(),
			'amount'        => $argument['amount'],
			'r_amount'      => $argument['r_amount'],
			'dateline'      => $timestamp,
			'payment_name'  => $argument['payment_name'],
			'payment_id'    => $argument['payment_id'],
			'trade_no'      => $argument['trade_no'],
			'trade_bank'    => $argument['trade_bank'],
			'trade_date'    => $argument['trade_date'],
			'kaipiao'       => $argument['kaipiao'],
			'kaipiao_tou'   => $argument['kaipiao_tou'],
			'status'        => 0,
			'order_no'      => $argument['order_no'],
			'order_table'   => $argument['order_table'],
			'inpay'		    => $argument['inpay'],
			);
			$recharge_obj->set_data($insert_data);
			$insert_data['id'] = $recharge_obj->add();
		}
		//充值时用户id跟随交易号一起发送,以"_"分割
		$payment = array();
		$payment ['M_OrderNO']   = $insert_data['recharge_no'];
		$payment ['M_OrderId']   = $insert_data['id'];
		$payment ['M_Amount']    = $insert_data['amount'];
		//交易信息
		$payment ['M_Def_Amount']= 0.01;
		$payment ['M_Time']      = time ();
		$payment ['M_Goods']     = '';
		$payment ['M_Language']  = "zh_CN";
		$payment ['M_Paymentid'] = $payment_id;

		//店铺信息
		$payment ['R_Address']   = App::get_data("site.site_name");
		$payment ['R_Name']      = App::get_data("site.site_domain");
		$payment ['R_Mobile']    = App::get_data("site.site_name");
		$payment ['R_Telephone'] = App::get_data("site.site_name");
		$payment ['R_Postcode']  = '';
		$payment ['R_Email']     = '';
		return $payment;
	}
	//设置支付状态
	public function set_paystatus($payment_id, $status, &$payinfo){
		return true;
	}
	//用余额支付服务
	public static function pay_account($order_no = "",$type = "balance",$order_table = "order"){
		global $timestamp;
		//return 0 无效  1支付成功  -3 余额不足  -2 支付人错误  -1 订单错误
		if(empty($order_no)){
			return -1;//订单不存在
		}
		$order_o = new tModel($order_table);
		$order_row = $order_o->get_row("order_no='{$order_no}'");
				
		if(!isset($order_row['order_no'])){
			return -2;
		}
		//如果已经支付
		if($order_row['pay_status'] == 1){
			return 1;
		}


		if($order_row['status'] != 2){
			return -3;
		}




		$uid       = $order_row['uid'];
        $money     = $order_row['amount'];   //冻结
		$point     = $order_row['point'];       //赠送积分
		
		$user_account = M("@account")->get($uid);
		if(!isset($user_account['uid'])){
			return -2;
		}
	    if($user_account[$type] < $money){
	    	return -3;
	    }
		/* 付钱了 */
		$is_success = M("@account")->update($uid,array("{$type}"=>"-{$money}"),array("{$type}"=>"支付订单,订单号：{$order_no}"));
		if($is_success == 1){
			/* 更新订单 */
			$data = array(
				'pay_status'         => 1,//支付完成
				'pay_dateline'      => $timestamp,
				'status'                => 1,//订单已支付
			);
			$ret = $order_o->set_data($data)->update("order_no='{$order_no}'");
			//积分操作
			if($point > 0){
				$is_success = M("@account")->update($uid,array("point"=>"+{$point}"),array("point"=>"支付订单奖励积分 {$point}"));
		   }
           return 1;
	  }
	  return 0;
	}
	//用余额支付续费
	public static function pay_account_forexpiry($order_no = "",$type = "balance",$order_table = "order"){
		global $timestamp;
		//return 0 无效 1支付成功  -3 余额不足  -2 支付人错误  -1 订单错误
		if(empty($order_no))return -1;//订单不存在
		$order_o = new tModel($order_table);
		$order_row = $order_o->get_row("order_no='{$order_no}'");
		
		if(!isset($order_row['id']) || $order_row['status'] != 5)return -1;
		$uid      = $order_row['uid'];
        $money    = $order_row['amount'];
		$point    = $order_row['point'];//赠送
		$order_id = $order_row['id'];
		
		$user_account = C("user")->get_account($uid);
		if(!isset($user_account['uid']))return -2;
	    if($user_account[$type] < $money)return -3;
		/* 付钱了 */
		$is_success = C("user")->update_account($uid,array("{$type}"=>"-{$money}"),array("{$type}"=>"在线花费 {$money}"));
		
		if($is_success == 1){
			/* 更新订单 */
			$data = array(
				'expiry'         => "expiry+".(($order_row['pay_type']*30+ceil($order_row['pay_type']/2))*86400),
			);
			$order_o->set_data($data)->update("id={$order_id}",array("expiry"));
			//积分操作
			if($point > 0){
				$is_success = C("user")->update_account($uid,array("point"=>"+{$point}"),array("point"=>"在线花费赠送 {$point}"));
		   }
           return 1;
	  }
	  return 0;
	}
	//更新在线充值
	public static function update_recharge($recharge_no){
		global $realip,$timestamp;
		$recharge_obj = new tModel('recharge');
		$recharge_row = $recharge_obj->get_row('recharge_no = "'.$recharge_no.'"');
		if(empty($recharge_row)){
			return false;
		}
		if($recharge_row['status'] == 1){//如果已经充值成功直接返回
			return true;
		}
		$data = array(
			'status' => 1
		);
		$recharge_obj->set_data($data);
		$result = $recharge_obj->update('recharge_no = "'.$recharge_no.'"');
		if($result == ''){
			return false;
		}
		$money     = $recharge_row['r_amount'];
		$uid       = $recharge_row['uid'];
		
		$is_success = M("@account")->update($uid,array("balance"=>"+{$money}"),array("balance"=>"在线充值 {$money}元"));
		if($is_success){//如果充值成功！
			
		}
		return $is_success;
	}
	//更新在线提现
	public static function update_withdraw($withdraw_id,$extdata = array()){
		$row = M("withdraw")->get_row("id='{$withdraw_id}'");
		$money    = $row['amount'];
		$uid      = $row['uid'];
		$trade_no = $row['trade_no'];
		
		if(!isset($row['id'])){
			return false;
		}
		if($row['status'] == 1){
			return true;
		}
		//检查余额是否充足！
		$userinfo = C("user")->get_cache_userinfo($uid);
		if($money > $userinfo['account']['balance']){
			return false;
		}
		$data = array(
			'status' => 1
		);
		if($extdata){
			foreach($extdata as $k=>$v){
				if(in_array($k,array("bank_trade_no"))){
					$data[$k] = $v;
				}
			}
		}
		$is_success = M("withdraw")->set_data($data)->update("id='{$row['id']}'");
		if($is_success){
			$is_success = C("account")->update($uid,array("balance"=>"-{$money}"),array("balance"=>"在线提现 {$money}元,交易编号：{$trade_no}"));
			if($is_success){//如果充值成功！
				
			}
		}
		return $is_success;
	}
}
/**
 * @class PaymentPlugin
 * @brief 支付插件基类
 */
class paymentPlugin{
	public $method      = "post";//form提交模式
	public $charset     = "utf-8";//字符集
	public $name        = null;//支付插件名称
	public $version     = 0.6;//版本
	public $callback_url = null;//支付完成后，回调地址
	public $_config     = array();//支付插件配置信息
	/**
	* @brief 构造函数
	*/
	public function __construct(){
		$pay_name = str_replace('pay_','',get_class($this));
		//获取域名地址
		$sUrl = tUrl::get_host().U();
		//回调函数地址
		$this->callback_url = U("account@/payplus/callback/payment_name/{$pay_name}");
		//回调业务处理地址
		$this->server_callback_url = U("account@/payplus/server_callback/payment_name/{$pay_name}");
	}
	/**
	* @brief 获取支付插件配置详细信息
	* @param $payment_id int    支付方式id值
	* @param $key       string 插件配置项
	*/
	public function get_conf($payment_id,$key = ''){
		if(empty($this->_config)){
			$payment       = new cls_payment();
			$payment_cfg   = $payment->get_payment_byid($payment_id);
			$this->_config = unserialize($payment_cfg['config']);
		}

		if($key != '' && isset($this->_config[$key])){
			return $this->_config[$key];
		}else{
			return $this->_config;
		}
	}
	//同步支付回调
	public function callback($in,&$paymentId,&$money,&$message,&$tradeno){

	}
	//异步支付回调
	public function server_callback($in,&$paymentId,&$money,&$message,&$tradeno){
		return $this->callback($in,$paymentId,$money,$message,$tradeno);
	}
}
?>
