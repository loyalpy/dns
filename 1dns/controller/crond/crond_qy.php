<?php
class crond_qy extends tController{
	public function index(){
		$timestamp = time();
		//自动解封牵引函数
		$list = M("domain_qy")->query("status=0 AND expiry<'{$timestamp}'");
		foreach($list as $v){ //每次取500条数据解封
			$domain = $v['domain'];
			//解封
			$ret0 = M("domain")->set_data(array("indel"=>0))->update("domain='{$domain}'");
			if($ret0){
				//解封成功更新牵引队列
				M("domain_qy")->set_data(array(
					"status"     => 1,
					"undateline" => $timestamp,
				))->update("qy_id='{$v['qy_id']}'");
				//重新生成记录
				M("@domain")->queue($v['domain'],"update_record");
			}
			usleep(100000);
		}
		die();
	}
}
?>