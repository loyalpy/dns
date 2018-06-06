<?php
require_once('Socket_Beanstalk.php');
class BeanStalk{
	private static $conf = array(
			"host"       => "localhost",
			"port"       => "11311",
			"persistent" => true,
			"timeout"    => 2,
		);
	private static $enable_tube = array(
			"dns"    => array(),
			"email"  => array(),
			"sms"    => array(),
		);
    private static $instance = null;
	//construct
	public static function connect(){
		if(self::$instance == null){
			$host = App::get_conf("db.queue.server");
			$port = App::get_conf("db.queue.port");
			if($host){
				self::$conf['host'] = $host;
			}
			if($port){
				self::$conf['port'] = $port;
			}
			self::$instance = new Socket_Beanstalk(self::$conf);
			unset($host,$port);
		}
	}

	//use tube then insert a job
	public static function use_put($tube, $data, $pri=0, $delay=0, $ttr=30){
		self::connect();

		if(self::$instance && in_array($tube,array_keys(self::$enable_tube))){
			self::$instance->useTube($tube);

			$content = is_array($data)?json_encode($data):$data;
			$job_id = self::$instance->put($pri, $delay, $ttr, $content);
			unset($content);
			return $job_id;
		}
		return false;
	}

	//WATHCH
	public static function watch($tube){
		self::connect();
		if(self::$instance){
			return self::$instance->watch($tube);
		}
	}

	//reserve
	public static function reserve($timeout = 2){
		self::connect();
		if(self::$instance){
			return self::$instance->reserve($timeout);
		}
	}

	//release
	public static function release($job_id,$pri = 0, $delay = 100){
		self::connect();
		if(self::$instance){
			return self::$instance->release($job_id, $pri, $delay);
		}
	}

	//delete
	public static function delete($job_id){
		self::connect();
		if(self::$instance){
			return self::$instance->delete($job_id);
		}
	}

	//bury
	public static function bury($job_id,$pri = 0){
		self::connect();
		if(self::$instance){
			return self::$instance->bury($job_id,$pri);
		}
	}

	//touch
	public static function touch($job_id){
		self::connect();
		if(self::$instance){
			return self::$instance->touch($job_id);
		}
	}
}
?>