<?php
 /**
  * @brief tTag 系统标签处理文件
  * @class tTag
  */
class tTag{
    //视图路径
	private $viewpath;
    //解析给定的字符串
	public function parse($str,$path=null){
		$this->viewpath = $path;
		return preg_replace_callback('/{(\/?)(\$|url|ramote|skin|echo|query|widget|foreach|set|include|require|if|elseif|else|while|for|js|javascript|code|hash|refer|staticfile)(\s*)([^}]*)}/i', array($this,'translate'), $str);
	}
    //处理设定的每一个标签
	public function translate($matches){
		if($matches[1]!=='/'){
			switch($matches[2]){
				case '$':
					{
                    $str = trim($matches[4]);
                    $first = substr($str,0,1);
					if($first != '.' && $first != '('){
						if(strpos($str,'(')===false)return '<?php echo isset($'.$str.')?$'.$str.':"";?>';
						else return '<?php echo $'.$str.';?>';
					}
                    else return $matches[0];
                }
				case 'echo': return '<?php echo '.rtrim($matches[4],';/').';?>';
				case 'url': return '<?php echo U("'.$matches[4].'");?>';
                case 'skin': {
                	$tmp = explode("@",$matches[4]);
                    if(!isset($tmp[1])){
                    	$tmp[1] = $tmp[0];
                    	$tmp[0] = "";
                    }
                	return '<?php echo U("'.($tmp[0]?($tmp[0]."@/"):"").'")."skins/'.($tmp[0]?$tmp[0]:'".$this->app."').'/".$this->skin."/'.$tmp[1].'";?>';
                }
                case 'lang': return '<?php ""?>';
				case 'if': return '<?php if('.$matches[4].'){?>';
				case 'elseif': return '<?php }elseif('.$matches[4].'){?>';
				case 'else': return '<?php }else{'.$matches[4].'?>';
				case 'set':
                {
                    return '<?php '.$matches[4].'?>';
                }
				case 'while': return '<?php while('.$matches[4].'){?>';
				case 'foreach':
				{
					$attr = $this->get_attrs($matches[4]);
					if(!isset($attr['items'])) $attr['items'] = '$items';
					else $attr['items'] = $attr['items'];
					if(!isset($attr['key'])) $attr['key'] = '$key';
					else $attr['key'] = $attr['key'];
					if(!isset($attr['item'])) $attr['item'] = '$item';
					else $attr['item'] = $attr['item'];

					return '<?php foreach('.$attr['items'].' as '.$attr['key'].' => '.$attr['item'].'){?>';
				}
				case 'for':
				{
					$attr = $this->get_attrs($matches[4]);
					if(!isset($attr['item'])) $attr['item'] = '$i';
					else $attr['item'] = $attr['item'];
					if(!isset($attr['from'])) $attr['from'] = 0;

                    if(!isset($attr['upto']) && !isset($attr['downto'])) $attr['upto'] = 10;
                    if(isset($attr['upto']))
                    {
                        $op = '<=';
                        $end = $attr['upto'];
                        if($attr['upto']<$attr['from']) $attr['upto'] = $attr['from'];
                        if(!isset($attr['step'])) $attr['step'] = 1;
                    }
                    else
                    {
                        $op = '>=';
                        $end = $attr['downto'];
                        if($attr['downto']>$attr['from'])$attr['downto'] = $attr['from'];
                        if(!isset($attr['step'])) $attr['step'] = -1;
                    }
					return '<?php for('.$attr['item'].' = '.$attr['from'].' ; '.$attr['item'].$op.$end.' ; '.$attr['item'].' = '.$attr['item'].'+'.$attr['step'].'){?>';
				}
				case 'query':
				{
					$endchart=substr(trim($matches[4]),-1);
					$attrs = $this->get_attrs(rtrim($matches[4],'/'));
                    if(!isset($attrs['id'])) $id = '$query';
                    else $id = $attrs['id'];
                    if(!isset($attrs['items'])) $items = '$items';
                    else $items = $attrs['items'];
					$tem = "$id".' = new tQuery("'.$attrs['name'].'");';
					//实现属性中符号表达式的问题
					$old_char=array(' eq ',' l ',' g ',' le ',' ge ', 'neq');
					$new_char=array(' = ',' < ',' > ',' <= ',' >= ', ' != ');
					foreach($attrs as $k => $v){
						if($k != 'name' && $k != 'id' && $k != 'items' && $k != 'item') $tem .= "{$id}->".$k.' = "'.str_replace($old_char,$new_char,$v).'";';
					}
					$tem .= $items.' = '.$id.'->find();';
					if(!isset($attrs['key'])) $attrs['key'] = '$key';
					else $attrs['key'] = $attrs['key'];
					if(!isset($attrs['item'])) $attrs['item'] = '$item';
					else $attrs['item'] = $attrs['item'];
					if($endchart=='/') return '<?php '.$tem.'?>';
					else return '<?php '.$tem.' foreach('.$items.' as '.$attrs['key'].' => '.$attrs['item'].'){?>';
				}
				case 'code': return '<?php '.$matches[4];
				case 'require':
				case 'include':
				{
					return '<?php echo $this->fetch(\''.trim($matches[4]).'\')?>';
				}
				case 'hash':return '<?php echo tUtil::hash();?>';
				case 'refer':return '<?php echo tClient::get_refer();?>';					
				case 'staticfile':
					if(file_exists(ROOT_PATH.'cache/static/'.$matches[4])){
						return '<?php @include(ROOT_PATH."'.'cache/static/'.$matches[4].'");?>';
					}else{
						return '';
					}
					return;
				default:
				{
					 return $matches[0];
				}
			}
		}else{
			if($matches[2] =='code') return '?>';
			else return '<?php }?>';
		}
	}
    //分析标签属性
	public function get_attrs($str){
		preg_match_all('/([a-zA-Z0-9_]+)\s*=([^=]+?)(?=(\S+\s*=)|$)/i', trim($str), $attrs);
		$attr = array();
		foreach($attrs[0] as $value){
			$tem = explode('=',$value);
			$attr[trim($tem[0])] = trim($tem[1]);
		}
		return $attr;
	}
}
?>
