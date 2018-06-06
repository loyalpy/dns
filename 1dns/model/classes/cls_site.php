<?php
class cls_site {
   public function write_cache_dataconfig(){
   	    global $timestamp;
   		$datalist = $make_arr = array();
		$datalist = M("site_config")->query("name LIKE 'dataconfig_%'","*");
		$jscontent = "var dataConfig ={},dataConfig_A = {};\n";
		if(isset($datalist[0])){
			foreach($datalist as $k=>$v){
				$data_key = str_replace("dataconfig_","",$v['name']);
				$make_arr[$data_key] = tUtil::unserialize($v['value']);
				if(!empty($make_arr[$data_key])){
					$jscontent .= "dataConfig['{$data_key}'] = ".JSON::encode($make_arr[$data_key]).";\n";
					$jscontent .= "dataConfig_A['{$data_key}'] = [];\n";
					foreach($make_arr[$data_key] as $k2=>$v2){
						$jscontent .= "dataConfig_A['{$data_key}'].push({".(is_array($v2)?("v:'{$v2['name']}',a:".JSON::encode($v2)):"v:'{$v2}'").",key:'{$k2}'});\n";
					}
					$jscontent .= "\n";
				}
			
			}
		}
		//一些cate_gory
		$cate = array("area","city");
		foreach($cate as $v){
			C("category",$v)->get(-9);
			C("category",$v)->fetch(-9);
			C("category",$v)->get(0);
			$tmp = C("category",$v)->fetch(0);
			$result = array();
			foreach($tmp as $v2){
				$result["{$v2['id']}"] = array(
					"id"           => $v2['id'],
					"pid"          => $v2['pid'],
					"name"         => $v2['name'],
					"status"       => $v2['status'],
					"has_children" => $v2['has_children'],
					"level"        => $v2['level'],
				);
			}
			$jscontent .= "\nvar {$v}_C=".JSON::encode($result)."";
		}
		if(tCache::write('data_config',$make_arr)){
			$jscache_name = ROOT_PATH."www/static/cache/dataconfig.js";
		    file_put_contents($jscache_name, $jscontent, LOCK_EX);
			return 1;
		}else{
			return 0;
		}
   }
   public function write_cache_category(){
   	
   }
}
?>