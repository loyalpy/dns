<?php
class tools extends tController{
   public $layout = "site";
   public function __construct(){
   		parent::__construct('tools');
   		App::set_debugmode(0);
   }
   public function index(){
   	   $this->result = "";
   	   $this->type = "A";
   	   $this->display();
   }
   public function ipwhois(){
   	   $this->result = '';
   	   if(tUtil::check_hash()){
   	   		$req_question = R("question","string");
   	   		$return = "";
   	   	    if($req_question){
   	   	    	$this->result = DNSapi::whois($req_question);
   	   	    }
   	   	    $this->question = $req_question;
   	   }
   	   $this->display();
   }
   public function dnsquery(){
   	   $server = isset(App::$data['site_config']['dnsquery'])?App::$data['site_config']['dnsquery']:"8.8.8.8";
   	   include_once(ROOT_PATH."lib/tools/DNSQuery.php");
   	   $this->result = "";
   	   $this->type = "A";
   	   if(tUtil::check_hash()){
   	   	    $req_server = R("server","string");
   	   	    $req_port = R("port","int");
   	   	    $req_udp = R("tcp","int");
   	   	    $req_timeout = R("timeout","int");
   	   	    $req_debug = R("debug","int");
   	   	    $req_extendanswer = R("extendanswer","int");
   	   	    $req_type = R("type","string");
   	   	    $req_question = R("question","string");
   	   	    
   	   	    $req_server = $req_server?$req_server:$server;
   	   	    $req_port = $req_port<=0?53:$req_port;
   	   	    $req_udp = $req_udp?false:true;
   	   	    $req_timeout = $req_timeout>30?30:$req_timeout;
   	   	    $req_debug = false;//$req_debug?true:false;
   	   	    $req_extendanswer = $req_extendanswer?true:false;
   	   	    $req_type = $req_type?$req_type:"A";
   	   	    $req_question = $req_question?$req_question:"";
   	   	    
   	   	    $return = "";
   	   	    if($req_question){
	   	   	    $query=new DNSQuery($req_server,$req_port,$req_timeout,$req_udp,$req_debug);
	   	   	    if ($req_type=="SMARTA"){
	   	   	    	$hostname=$query->SmartALookup($req_question);
					$return .= "查询域名： ".$req_question."<br/>";
					$return .= "结果： ".$hostname."<br/><br/>";
				}else{
					//$return .= "查询域名: ".$req_question." 类型：".$req_type." <br/><br/>";
					$result=$query->Query($req_question,$req_type);
					if($query->error){
						$return .= "<br/>查询出错: ".$query->lasterror."<br/><br/>";
					}else{
						if($result->count >0){
							//$return .= "Returned ".$result->count." Answers<br/><br/>";
							$return .= ShowSection($result,$req_extendanswer);
							if ($req_extendanswer){
								if($query->lastnameservers->count>0){
									//$return .=  "<br/>名字服务器记录: ".$query->lastnameservers->count."<br/>";
									$return .= ShowSection($query->lastnameservers,true);
								}
								if($query->lastadditional->count>0){
									//$return .=  "<br/>附加记录: ".$query->lastadditional->count."<br/>";
									$return .= ShowSection($query->lastadditional,true);
								}
							}
						}else{
							$return .= "没有检测到结果";
						}
					}
				}
   	   	    }
			$this->question = $req_question;
			$this->server = $req_server == $server?"":$req_server;
			$this->detail = $req_extendanswer?1:0;
			$this->type = $req_type;
			$this->result = $return;
   	   }
   	   $this->display();
   }
   public function httpstatus(){
   	   $this->result = array();
   	   $this->port = 80;
	   include_once(ROOT_PATH."lib/tools/HTTPStatus.php");
   	   if(tUtil::check_hash()){
   	   		$req_question = R("question","string");
   	   		$return = "";
   	   	    if($req_question){
   	   	    	$query=new HTTPstatus();
   	   	    	$this->result = $query->query("http://".$req_question);
   	   	    }
   	   	    $this->question = $req_question;
   	   }
   	   $this->display();
   }
   public function ico2png(){
   		include ROOT_PATH . 'lib/tools/Ico.class.php';
   		$domain = R("domain","string");
   		if($domain){
			/*
   			$query=new HTTPstatus();
   			$icofile = "http://www.{$domain}/favicon.ico";
   	   	    $this->result = $query->query($icofile,"get",1);
   	   	    if($this->result['status_code'] == "200"){
   	   	    	$ico = new Ico($icofile);
				$TotalIcons = $ico->TotalIcons();
				if($TotalIcons){
					$img = $ico->GetIcon(0);
					if($img && imagePng ($img)){
						imagePng ($img);
						imageDestroy($img);
						exit;
					}
				}
   	   	    }
			*/
   		}
   		header("Location:".U()."common/images/favicon.gif");
   }
}

function ShowSection($result,$extendanswer = false){
	$return = "";
	for ($i=0; $i<$result->count; $i++){
		if ($result->results[$i]->string==""){ 
			$return .= $result->results[$i]->typeid."(".$result->results[$i]->type.") => ".$result->results[$i]->data;
		}else{
			$return .= $result->results[$i]->string;
		}
		$return .= "<br/>";
		if ($extendanswer){
			$return .= "[ 记录类型 = ".$result->results[$i]->typeid." ]<br/>";//." (# ".$result->results[$i]->type.")<br/>";
			$return .= "[ 记录值 = ".$result->results[$i]->data." ]<br/>";
			//$return .= " - record ttl = ".$result->results[$i]->ttl."<br/>";
			if (count($result->results[$i]->extras)>0){ // additional data
				foreach($result->results[$i]->extras as $key => $val){
					//$return .= " + ".$key." = ".$val."\n";
				}
			}
		}
		$return .=  "<br/>";
	}
	return $return;
}
?>