<?php
/**
 * @class pay_nepay
 * @brief 新付支付插件类
 */
class pay_nepay extends paymentPlugin{

	//支付插件名称
    var $name = '新付支付[即时到帐]';//支付宝（特别推荐！）
    //支付插件logo
    var $logo = 'NEPAY';
    //版本号
    var $version = 20130902;
    //支付插件字符集
    var $charset = 'gbk';
	//支付提交的地址
    var $submitUrl = 'https://www.nepay.com.cn/user/HC0001.tran';
    //支付提交按钮的图片
    var $submitButton = '';
    //支付插件所支持的货币单位
    var $supportCurrency = array("CNY"=>"01");
    //支付支持的地区
    var $supportArea =  array("AREA_CNY");
    //支付插件排序
    var $orderby = 3;
	//支付html头部的字符集
    var $head_charset='utf-8';
    /**
    * @brief 初始化支付宝类
    */
    function __construct(){
    	//初始化父类
        parent::__construct();
        //获取IP地址
        $regIp=isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:$_SERVER['HTTP_HOST'];
        //设置支付宝详细信息
        $this->intro='';
    }
    /**
    * @brief form提交事件
	* @param array 订单的详细信息
	× @return array 返回支付需提交的详细信息
    */
    function toSubmit($payment){
    	//合作者身份(parterID)-帐号
        $MerNo = $this->get_conf($payment['M_Paymentid'], 'member_id');
        //交易安全校验码(key)
        $MD5key = $this->get_conf($payment['M_Paymentid'], 'PrivateKey');
        $MD5key = $MD5key==''?'clP4fUaIzvGW':$MD5key;
		//订单总价
        $amount = number_format($payment['M_Amount'],2,".","");
		//初始化返回值
        $return = array();
        //交易接口名称
        $return['MerNo'] = $MerNo;
        $return['Signtype'] = "M";
        $return['Prdordno'] = $payment['M_OrderNO'];
        $return['bizType']  = "10";
        $return['Prdordnam']  = "zzb";//tUtil::iconv("增加宝-在线充值","gbk","utf-8");
        $return['Ordamt']  = $amount*100;
        $return['Orddate']  = tTime::get_datetime("Ymd",$payment ['M_Time']);
        $return['TranType'] = "2201";
        $return['Paytype'] = "01";
        /*
    	merNo&signType&Prdordno&Prdordnam&Ordamt&Orddate&tranType&paytype&notify_url&md5key
    	*/
		//付完款后跳转的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
        $return['Return_url'] = $this->callback_url;
		//交易过程中服务器通知的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
        $return['Notify_url'] = $this->server_callback_url;
        $md5str = "{$return['MerNo']}&{$return['Signtype']}&{$return['Prdordno']}&{$return['Prdordnam']}&{$return['Ordamt']}&{$return['Orddate']}&{$return['TranType']}&{$return['Paytype']}&{$return['Notify_url']}&".($MD5key);
        $return['inMsg'] = strtoupper(md5($md5str));//验证信息
        
		//unset($return['_input_charset']);
        return $return;
    }

    function callback($in,&$paymentId,&$money,&$message,&$tradeno){
        //合作者身份(parterID)-帐号
        $MerNo = $this->get_conf($paymentId, 'member_id');
        //交易安全校验码(key)
        $MD5key = $this->get_conf($paymentId, 'PrivateKey');
        $MD5key = $MD5key==''?'clP4fUaIzvGW':$MD5key;

		//Merno&Prdordno&settleDate&ordStatus&notifyTyp&payOrdNo&Ordamt&signType&md5key
        $md5Str = "{$MerNo}&{$in['Prdordno']}&{$in['settleDate']}&{$in['ordStatus']}&{$in['notifyTyp']}&{$in['payOrdNo']}&{$in['Ordamt']}&{$in['signType']}&".($MD5key);
        X("tLog","file")->write('operation',array($md5Str,md5($md5Str),$in['signature']));
        if($in['signature'] == md5($md5Str)){
            //支付单号
            $money   = $in['Ordamt'];
            $message = "ERROR";
            switch($in['ordStatus'])
            {
                case 1:
                case "1":
                case 'TRADE_SUCCESS':
                {
                	return PAY_SUCCESS;
                    break;
                }

                default:
                	return PAY_FAILED;
                	break;
            }
        }else{
            $message = 'Invalid Sign';
            return PAY_ERROR;
        }
    }

    function applyForm($agentfield){
      $tmp_form='<a href="javascript:void(0)" onclick="document.applyForm.submit();">立即申请支付宝</a>';
      $tmp_form.="<form name='applyForm' method='".$agentfield['postmethod']."' action='http://top.shopex.cn/recordpayagent.php' target='_blank'>";
      foreach($agentfield as $key => $val){
            $tmp_form.="<input type='hidden' name='".$key."' value='".$val."'>";
      }
      $tmp_form.="</form>";
      return $tmp_form;
    }

    function getfields(){
        return array(
                'member_id'=>array(
                        'label'=>'商户ID(MerNo)',
                        'type'=>'string'
                    ),
                'PrivateKey'=>array(
                        'label'=>'交易安全校验码(MD5key)',
                        'type'=>'string'
                ),
                'real_method'=>array(
                        'label'=>'选择接口类型',
                        'type'=>'select',
                        'options'=>array('1'=>'使用即时到帐交易接口')
                ),

            );
    }
}
?>
