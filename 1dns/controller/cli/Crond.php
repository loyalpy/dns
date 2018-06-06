<?php
class Crond extends tController{
	public function dns(){		
		while (true) {
			try{
				BeanStalk::watch('dns');
				$job      = BeanStalk::reserve(2);
				if(isset($job['id']) && $job['id']){
					$job_id   = $job['id'];
					//X("tLog","file")->write("operation",array($job_id,$job['body']));
					$data     = json_decode($job['body'], true); //解析
					$action   = $data['action'];
					$ret = 1;
					switch($action){
						case "add_domain":
							$domain = $data['data']['domain'];
							$ret    = SDKdns::add_zone($domain); 
							break;
						case "del_domain":
						    $domain = $data['data']['domain'];
							$ret    = SDKdns::del_zone($domain);
							break;
						case "update_record":
							$domain = $data['data']['domain'];
							$ret    = SDKdns::update_zone($domain);		
							break;
						default:
							break;
					}
					if ($ret == 1) {
					    BeanStalk::delete($job_id);
					} else {
					    BeanStalk::release($job_id);
					    if($ret == -9){
					    	tCli::stop();
						}
					}
				    unset($job_id,$job,$data,$ret);
			    }
			}catch(Exception $e){
				X("tLog","file")->write("error",array("beanstalk connect fail!"));
			}
			usleep(1000);
		}
		return ;
	}
}
?>