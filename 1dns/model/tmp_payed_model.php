<?php
class tmp_payed_model extends tModel{
	public function __construct(){
		parent::__construct("tmp_payed");
	}
	public function save($pay_no = 0,$data = array()){
		if($pay_no == ""){
			$data['pay_no'] = tUtil::create_no("DJ");
			$this->set_data($data);
			$ret = $this->add();
		}else{
			unset($data['pay_no'],$data['order_no'],$data['amount'],$data['uid'],$data['dateline']);
			$this->set_data($data);
			$ret = $this->update("pay_no = '{$pay_no}'");
		}
		return $ret;
	}
}
?>