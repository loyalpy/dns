<?php
class payplus extends tController{
    public $layout = "ucenter";
    //支付微信订单支付检测
    public function pay_wxnative_check(){
    	$recharge_no  = R("Ordno","string");
		$recharge_row = M("recharge")->get_row("recharge_no = '{$recharge_no}'");
		if(!isset($recharge_row['recharge_no'])){
			tAjax::json_error("Ordno IS empty!");
		}
		if($recharge_row['status'] == 1){
			$callback = U("/finance/recharge?type=2");
			if($recharge_row['inpay'] == 1){
				if($recharge_row['order_table'] == "order"){
					$callback = U("account@/domains");
				}elseif ($recharge_row['order_table'] == "register_order"){
					$callback = U("domain@/ucenter/index");
				}
			}else{
				if($recharge_row['order_table'] == "order"){
					$callback = U("account@/order/order_view?order_no=".$recharge_row['order_no']);
				}elseif ($recharge_row['order_table'] == "register_order"){
					$callback = U("domain@/cart/pay?order_no=".$recharge_row['order_no']);
				}
			}

			tAjax::json(array("error"=>0,"message"=>"success","callback"=>$callback));
		}else{
			tAjax::json_error("fail");
		}

    }
	//支付微信订单生成
	public function pay_wxnative_call(){
		$payment_obj = new cls_payment();
		$pay_obj  = $payment_obj->load("weixin");
		$xml =  $pay_obj->native_call();
		echo $xml;
		exit(0);
	}
	//支付微信扫码
	public function pay_wxnative(){
		$recharge_no  = R("Ordno","string");
		$recharge_row = M("recharge")->get_row("recharge_no = '{$recharge_no}'");
		if(!isset($recharge_row['recharge_no'])){
			I('支付单不存在',U("account@/finance"),"error");
		}
		if($recharge_row['status'] == 1){
			I('已充值成功',U("account@/finance/recharge?type=2"),"success");
		}
        //获取支付方式
        $payment_id  = $recharge_row['payment_id'];
		$payment_obj = new cls_payment();
		$payment_row = $payment_obj->get_payment_byid($payment_id);
		if(empty($payment_row)){
			I("支付方式不存在",U("account@/finance"),"error");
		}
		$pay_obj  = $payment_obj->load($payment_row['file_path']);
        $code_url = $pay_obj->get_url($recharge_no);

		$this->assign("recharge_row",$recharge_row);
        $this->assign("code_url",$code_url);
		$this->layout = "";
		$this->display();
	}

	/**
    * @brief 支付回调测试[同步]
	* define ( "PAY_FAILED", - 1);支付失败
	* define ( "PAY_TIMEOUT", 0);支付超时
	* define ( "PAY_SUCCESS", 1);支付成功
	* define ( "PAY_CANCEL", 2);支付取消
	* define ( "PAY_ERROR", 3);支付错误
	* define ( "PAY_PROGRESS", 4);支付进行
	* define ( "PAY_INVALID", 5);支付无效
	* define ( "PAY_MANUAL", 0);手工支付
	*/
	public function callback(){
		//获取支付名
		$payment_name = is_array($payment_name = R('payment_name',"string")) ? tUtil::filter($payment_name[0]) :  tUtil::filter(R('payment_name'));
		//初始化参数
		$money   = null;
		$message = null;
		$tradeno = null;
		//获取支付payment的id值
		$pObj        = new tModel('payment as a,@pay_plugin as b');
		$payment_row = $pObj->get_row('b.file_path = "'.$payment_name.'" and a.plugin_id = b.id','a.id');
		//载入支付接口文件
		$payment_obj = new cls_payment();
		$pay_obj     = $payment_obj->load($payment_name);
		if(!is_object($pay_obj)){
			I('支付方式不存在');
			exit(0);
		}
		//执行接口回调函数
		$return  = $pay_obj->callback(array_merge($_POST,$_GET),$payment_row['id'],$money,$message,$tradeno);
		//判断返回状态
		if($return == 1){
			$recharge_no  =$tradeno;
			if(cls_payment::update_recharge($recharge_no)){
				//获取充值行 充值行分2种类型 1. 仅充值,充值成功根据订单号跳转到指定订单支付页面  2. 有订单号根据订单号进行支付 
                $recharge_row = M("recharge")->get_row("recharge_no='{$recharge_no}'");
                if($recharge_row['inpay'] == 1){//充值并需要支付
                	if($recharge_row['order_no']){
                		$ret = C("payment")->pay_account($recharge_row['order_no'],"balance",$recharge_row['order_table']);
                		if($ret === 1){
                			if($recharge_row['order_table'] == "order"){
								$url = U("account@/domains");
								$exejs = U("account@/api/Order.Send?order_no=".$recharge_row['order_no']);
								I("域名解析套餐购买成功",$url,"success",$exejs);
								exit(0);
							}elseif($recharge_row['order_table'] == "register_order"){
								//判断域名注册，域名续费，域名转入
								$url   =  U("domain@/ucenter/index");
								$exejs =  U("domain@/api/Order.SendRegDomain?order_no=".$recharge_row['order_no']);
								I("域名(购买/续费/转入)购买成功",$url,"success",$exejs);
								exit(0);
							}
                		}
                	}
                }else{
                	if($recharge_row['order_table'] == "order"){
                		I("充值成功,正跳转至支付...",U("account@/order/order_view?order_no=".$recharge_row['order_no']),"success");
                		exit(0);
                	}elseif($recharge_row['order_table'] == "register_order"){
                		I("充值成功,正跳转至支付...",U("domain@/cart/pay?order_no=".$recharge_row['order_no']),"success");
                		exit(0);
                	}              	
                }
                I("充值成功",U("account@/finance/recharge?type=2"),"success");
				exit(0);
			}
		}
		I('充值失败',U("account@/finance/recharge?type=2"));
		exit(0);
	}

	//支付回调[异步]
	public function server_callback()  {
		$payment_name = is_array($payment_name = R('payment_name',"string")) ? tUtil::filter($payment_name[0]) :  tUtil::filter(R('payment_name',"string"));
        //日志对象
		$tlog = new tLog();
		//初始化参数
		$money   = null;
		$message = "fail";
		$tradeno = null;
		///获取支付payment的id值
		$pObj       = new tModel('payment as a,@pay_plugin as b');
		$payment_row = $pObj->get_row('b.file_path = "'.$payment_name.'" and a.plugin_id = b.id','a.id');
		//如果支付方式不存在
		if(isset($payment_row['id'])){
			//载入支付接口文件
			$payment_obj = new cls_payment();
			$pay_obj     = $payment_obj->load($payment_name);
			if(is_object($pay_obj)){
				//执行接口回调函数
				$return  = $pay_obj->server_callback(array_merge($_POST,$_GET),$payment_row['id'],$money,$message,$tradeno);
				//判断返回状态
				if($return == 1){
					$recharge_no  =$tradeno;
					if(cls_payment::update_recharge($recharge_no)){
						$recharge_row = M("recharge")->get_row("recharge_no='{$recharge_no}'");
						if($recharge_row['inpay'] == 1){//充值并需要支付
							if($recharge_row['order_no']){
		                		$ret = C("payment")->pay_account($recharge_row['order_no'],"balance",$recharge_row['order_table']);
		                		if($ret === 1){
		                			$uid = $recharge_row['uid'];
		                			if($recharge_row['order_table'] == "order"){
										$ret = SDK::web_api("/Order/Send", array("order_no"=>$recharge_row['order_no']),$uid);
									}elseif($recharge_row['order_table'] == "register_order"){
										$tlog->write("error",$recharge_row);
										$ret = SDK::web_api("/Order/SendRegDomain", array("order_no"=>$recharge_row['order_no']),$uid);
									}
		                		}
		                	}					
						}
					}else{
						$tlog->write("error",array("充值订单号：{$recharge_no}"));
					}
				}else{
					$tlog->write("error",array("返回：{$return}"));
				}
			}
		}	
		echo $message;
		exit;
	}
}
?>