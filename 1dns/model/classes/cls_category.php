<?php
class cls_category{
	public    $space = '&nbsp;';
	public    $field = array('id','pid','name');
	public    $fetch_fields = "c.*";
	//变量
	public $order = '';
	public $table_name = '';
	public $where = "1";
	public $cache_name = "";
	//构造函数
    function __construct($table_name,$where = "1", $fetchfields = 'c.*',$order = 'sort ASC', $fields = array('id','pid','name')){
        $this->table_name = $table_name;
        $this->order = $order;
        $this->field = $fields;
        $this->fetch_fields = $fetchfields;
        $this->where = $where;
        
        $this->cache_name = "@cate_{$this->table_name}_".md5($where);
        $this->get(-9);
        $this->fetch(-9);
        return $this;
    }
	/*
	获得指定分类下的子分类的数组
	$cat_id     分类的ID   
	$selected   当前选中分类的ID  
	$re_type    返回的类型: 值为真时返回下拉列表,否则返回数组   
	$level      限定返回的级数。为0时返回所有级数  
	$is_show_all 如果为true显示所有分类，如果为false隐藏不可见分类。
	*/
	function get($cat_id = 0, $selected = 0, $re_type = false, $level = 0){
	    $res = NULL;
	    if ($res === NULL){
	        $data = tCache::read($this->cache_name);
	        if ($data === false){
	            $cat_query = new tQuery($this->table_name." as c");
	            $cat_query ->fields = "{$this->fetch_fields},COUNT(s.{$this->field[0]}) AS has_children";
	            $cat_query ->join = "left join ".$this->table_name." as s ON s.{$this->field[1]}=c.{$this->field[0]}";
	            $cat_query ->group = "c.{$this->field[0]}";
	            $cat_query ->order = "c.{$this->field[1]},c.{$this->order},id ASC";
	            $cat_query ->where = $this->where;
	            $res = $cat_query->find();
	            //如果数组过大，不采用静态缓存方式
	            if (count($res) <= 1000){
	            	$cache_res = array();
	            	foreach($res as $k=>$v){
	            		$cache_res[$v['id']] = $v;
	            	}
	                tCache::write($this->cache_name, $cache_res);
	            }
	        }else{
	            $res = $data;
	        }
	    }
	    if (empty($res) == true){
	        return $re_type ? '' : array();
	    }
	    // 获得指定分类下的子分类的数组
	    $this->fetch(-9);
	    $options = $this->fetch($cat_id, $res); 
	    /* 截取到指定的缩减级别 */
	    if ($level > 0){
	        if ($cat_id == 0){
	            $end_level = $level;
	        }else{
	            $first_item = reset($options); // 获取第一个元素
	            $end_level  = $first_item['level'] + $level;
	        }
	        /* 保留level小于end_level的部分 */
	        foreach ($options AS $key => $val){
	            if ($val['level'] >= $end_level){
	                unset($options[$key]);
	            }
	        }
	    }
	   if ($re_type == true){
	        $select = '';
	        foreach ($options AS $var){
	            $select .= '<option value="' . $var[$this->field[0]] . '" ';
	            $select .= ($selected == $var[$this->field[0]]) ? "selected='ture'" : '';
	            $select .= '>';
	            if ($var['level'] > 0){
	                $select .= $var['space'];
	            }
	            $select .= htmlspecialchars(addslashes($var[$this->field[2]]), ENT_QUOTES) . '</option>';
	        }
		    return $select;
	    }else{
	        return $options;
	    }
	}
	//过滤和排序所有分类，返回一个带有缩进级别的数组
	function fetch($spec_cat_id, $arr = array()){
	    static $cat_options = array();
	    if($spec_cat_id === -9){
	    	$cat_options = array();
	    	return ;
	    }	    
	    if (isset($cat_options[$spec_cat_id])) {
	        return $cat_options[$spec_cat_id];
	    }
	    if (!isset($cat_options[0])) {
	        $level = $last_cat_id = 0;
	        $options = $cat_id_array = $level_array = array();
	        $data = tCache::read($this->cache_name.'_fetch');
	        if ($data === false){
	            while (!empty($arr)){
	                foreach ($arr AS $key => $value){
	                    $cat_id = $value[$this->field[0]];
	                    //处理第一条数据
	                    if ($level == 0 && $last_cat_id == 0){
	                    	//如果第一条数据的父级ID不为0退出循环
	                        if ($value[$this->field[1]] > 0){
	                            break;
	                        }
	                        //数据赋值
	                        $options[$cat_id]          = $value;
	                        $options[$cat_id]['level'] = $level;
	                        $options[$cat_id]['id']    = $cat_id;
	                        $options[$cat_id]['name']  = $value[$this->field[2]];
	                        $options[$cat_id]['space'] = str_repeat($this->space, $level * 4);
	                        //解析后清除该条数据
	                        unset($arr[$key]);
	                        //如果子级没有直接跳出此轮循环
	                        if ($value['has_children'] == 0){
	                            continue;
	                        }
	                        $last_cat_id  = $cat_id;
	                        $cat_id_array = array($cat_id);
	                        $level_array[$last_cat_id] = ++$level;
	                        continue;
	                    }
	                    //如果最后找到
	                    if ($value[$this->field[1]] == $last_cat_id){
	                        $options[$cat_id]          = $value;
	                        $options[$cat_id]['level'] = $level;
	                        $options[$cat_id]['id']    = $cat_id;
	                        $options[$cat_id]['name']  = $value[$this->field[2]];
	                        $options[$cat_id]['space'] = str_repeat($this->space, $level * 4);
	                        //解析后清除该条数据
	                        unset($arr[$key]);
	                        if ($value['has_children'] > 0){
	                            if (end($cat_id_array) != $last_cat_id){
	                                $cat_id_array[] = $last_cat_id;
	                            }
	                            $last_cat_id    = $cat_id;
	                            $cat_id_array[] = $cat_id;
	                            $level_array[$last_cat_id] = ++$level;
	                        }
	                    }elseif($value[$this->field[1]] > $last_cat_id){
	                        break;
	                    }
	                }
	                //清空处理
	                $count = count($cat_id_array);
	                if ($count > 1){
	                    $last_cat_id = array_pop($cat_id_array);
	                }elseif ($count == 1){
	                    if ($last_cat_id != end($cat_id_array)){
	                        $last_cat_id = end($cat_id_array);
	                    } else {
	                        $level = 0;
	                        $last_cat_id = 0;
	                        $cat_id_array = array();
	                        continue;
	                    }
	                }
	                //处理层级
	                if ($last_cat_id && isset($level_array[$last_cat_id])){
	                    $level = $level_array[$last_cat_id];
	                }else{
	                    $level = 0;
	                }
	            }
	            //如果数组过大，不采用静态缓存方式
	            if (count($options) <= 2000){
	                tCache::write($this->cache_name.'_fetch', $options);
	            }
	        } else{
	            $options = $data;
	        }
	        $cat_options[0] = $options;
	    }else{
	        $options = $cat_options[0];
	    }
	    if (!$spec_cat_id){
	        return $options;
	    }else{
	        if (empty($options[$spec_cat_id])){
	            return array();
	        }
	        $spec_cat_id_level = $options[$spec_cat_id]['level'];
	        foreach ($options AS $key => $value){
	            if ($key != $spec_cat_id){
	                unset($options[$key]);
	            }else{
	                break;
	            }
	        }
	
	        $spec_cat_id_array = array();
	        foreach ($options AS $key => $value){
	            if (($spec_cat_id_level == $value['level'] && $value[$this->field[0]] != $spec_cat_id) ||
	                ($spec_cat_id_level > $value['level'])){
	                break;
	            }else{
	                $spec_cat_id_array[$key] = $value;
	            }
	        }
	        $cat_options[$spec_cat_id] = $spec_cat_id_array;
	        return $spec_cat_id_array;
	    }
	}
	//返回easytemplate json格式
	function json_tpl($cat_id = 0, $selected = 0){
		$tmplist = $catlist = array();
		$tmplist = $this->get($cat_id,$selected);
		if($tmplist){
			foreach($tmplist as $k=>$v){
				$catlist[] = array("v"=>"{$v['space']}{$v['name']}","key"=>$v['id']);
			}
		}
		return $catlist;
	}
	//返回easytemplate json格式
	function json($cat_id=0,$key = 0){
		$tmp = $this->get($cat_id);
		$data = array();
		if($key){
			foreach($tmp as $v){
				$data[$v[$key]] = $v;
			}
		}else{
			$data = $tmp;
		}
		return $data;
	}
	//返回层级格式
	function get_level($cat_id = 0,$key_id = true){
		return $this->fetch_level($cat_id,$key_id);
	}
	//返回等级
	function fetch_level($cat_id = 0,$key_id = true){
		$tmplist = $catlist = array();
		$tmplist = $this->get($cat_id);
		$cur = 0;
		if($tmplist){
			foreach($tmplist as $k=>$v){
				if($v['pid'] == $cat_id){
					$cur = $v['id'];
					$v['childrens'] = array();
					if($v['has_children'] > 0){
						$v['childrens'] = $this->fetch_level($cur,$key_id);
					}
					if($key_id == true){
						$catlist[$cur] = $v;
					}else{
						$catlist[] = $v;
					}
				}
			}
		}
		return $catlist;
	}
	//根据ID获取path
	function get_path($id,$field = "name"){
		$return  = array();
		$tmplist = $this->get(0);
		if(isset($tmplist[$id])){
			$pid         =  $tmplist[$id]['pid'];
			$return[$id] = $tmplist[$id][$field];
			while($pid > 0){
				$find = 0;
				foreach($tmplist as $v){
					if($v['id'] == $pid){
						$pid      = $v['pid'];
						$return[$v['id']] = $v[$field];
						$find     = 1;
						break;
					}
				}
				if($find == 0){
					$pid = 0;
				}
			}
		}
		return array_reverse($return,true);
	}
	//根据PID找出所有 Child_id
	function get_child_ids($pid){
		$return = $this->get($pid);
		return array_keys($return);
	}
	//清除缓存
	function clear(){
		return tCache::delete($this->cache_name) && tCache::delete($this->cache_name.'_fetch');
	}
}
?>