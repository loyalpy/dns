<?php
 class tAjax{
 	public static function respons($append=array(),$type = "xml"){
 		$res = "";
 		switch($type){
 			default:
 				$UA = $_SERVER['HTTP_USER_AGENT'];
				if(strpos($UA,'MSIE 9.0') && tGpc::get("inform") == 1){
		            if($append['content']){
		            	$res .= "<div class=\"htmlcontent\">".$append['content']."</div>";
		            	unset($append['content']);
		            }
		            if($append["toplogin_info"]){
		            	$res .= "<div class=\"toplogin_info\">".$append['toplogin_info']."</div>";
		            	$append["toplogin_info"] = "";
		            }
		            if($append["midlogin_info"]){
		            	$res .= "<div class=\"midlogin_info\">".$append['midlogin_info']."</div>";
		            	$append["midlogin_info"] = "";
		            }
		            $res .= "<div class=\"jsoncontent\">".JSON::encode($append)."</div>";
				}else{
					@header("Expires: -1");
	        		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
	        		@header("Pragma: no-cache");
	       			@header("Content-type: application/xml; charset=gb2312");
	       			$res .= "<?xml version=\"1.0\" encoding=\"gb2312\" ?><root>";
	        		foreach($append as $k=>$v){
	        			$res .= "<{$k}><![CDATA[{$v}]]></{$k}>";
	        		}
	        		$res .= "</root>";
				}
        		break;
 			case "json":
 				foreach ($append AS $key => $val){
	              $append[$key] = $val;
	            }
	            $res .= JSON::encode($append);
	            break;
 		}
        exit($res);
 	}
 	public static function error($content=''){
 		self::respons(array("error"=>1,"message"=>$content));
 	}
 	public static function success($content=''){
 		self::respons(array("error"=>0,"message"=>$content));
 	}
 	public static function json($data=array()){
 		self::respons($data,"json");
 	}
 	public static function json_error($content){
 		self::respons(array("error"=>1,"message"=>$content),"json");
 	}
 	public static function json_success($content){
 		self::respons(array("error"=>0,"message"=>$content),"json");
 	}
 }
 ?>