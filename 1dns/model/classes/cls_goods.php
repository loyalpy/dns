<?php
/**
 * @brief 商品类
 * @class Goods
 */
class cls_goods{
	//array("jpg","gif","png","bmp","jpeg","rar","tar.gz","zip","doc","docx","ppt");
	public $attach_types = array("jpg","gif","png","bmp","jpeg");
	public $attach_img_size = array(1=>array(50,50),2=>array(100,100),3=>array(166,126),4=>array(420,340));
	public $attach_size  = 2048;
	public $attach_path  = "goods";
	public $attach_table = "goods_attach";
	public function save($id = 0, $data = array(),$attachlist = array(),$rel_data = array()){
		global $uid,$timestamp;
		$userinfo = C("user")->get_cache_userinfo($uid);
		//处理状态
		$ret_id = $id;
		if($id == 0){
			$data['uid'] = $uid;
			$data['uname'] = $userinfo['name'];
			$data['dateline'] = $timestamp;
			M("goods")->set_data($data);
			$ret_id = M("goods")->add();
		}else{
			unset($data['uid'],$data['uname'],$data['dateline']);
			M("goods")->set_data($data);
			M("goods")->update("id=$id");

			C("goods")->update_goods_nums("a.id='{$id}'");
		}
		if($ret_id && !empty($rel_data)){
			M("goods_relative")->del("goods_id='{$ret_id}'");
			foreach($rel_data as $key=>$v){
				$rel_data = array(
					"goods_id" => $ret_id,
					"name"     => $key,
					"value"    => $v,
				);
				M("goods_relative")->set_data($rel_data);
				M("goods_relative")->add();
			}
		}
		//分类处理
		//附件处理
		if(is_array($attachlist)){
			foreach($attachlist as $attach_id=>$v){
				if($attach_id){
					C("attach")->update(
					   array(
					     "table_name" => $this->attach_table,
					     "where"      => "id=$attach_id AND goods_id={$id}",
					     "data"       => $id?array("description"=>$v):array('goods_id'=>$ret_id,'description'=>$v),
					   )
					);
				 }
			}
		}
		return $ret_id;
	}
	public function get_list($c = array(),$pageurl=""){
		$result = array();
		$c['table'] = isset($c['table'])?$c['table']:"goods";
		$c['where'] = isset($c['where'])?$c['where']:"1";
		$c['page'] = isset($c['page'])?$c['page']:1;
		$c['pagesize'] = isset($c['pagesize'])?$c['pagesize']:18;
		$c['order'] =  isset($c['order'])?$c['order']:"dateline DESC";
		$query = new tQuery($c['table']);
		foreach($c as $k=>$v){
			$query->$k = $v;
		}
		
		$result['list'] = $query->find();
		$result['pagebar'] = $query->get_pagebar($pageurl);
		$result['total'] = $query->total;
		$result['totalpage'] = $query->totalpage;
		//列表处理
		$_ids = array();
		if($result['list']){
			foreach($result['list'] as $k=>$v){
				$_ids[] = $v["id"];
				//处理规格
				$result['list'][$k]['specs'] = $v['specs']?tUtil::unserialize($v['specs']):array();
				//处理属性
				$result['list'][$k]['attrs'] = array();
				$tmp_attr = $v['attrs']?explode(",",$v['attrs']):array();
				if(!empty($tmp_attr)){
					foreach($tmp_attr as $v2){
						$tmp = explode("@",$v2);
						if(!isset($myattr[$tmp[0]])){
							$myattr[$tmp[0]] = array();
						}
						$result['list'][$k]['attrs'][$tmp[0]][] = $tmp[1];
					}
				}
			}
		}
		$result["ids"] = $_ids;
		return $result;
	}
	public function get_row($where = ""){
		$result = M("goods")->get_row($where);
		if(isset($result['id'])){
			//处理规格
			$result['specs'] = $result['specs']?tUtil::unserialize($result['specs']):array();
			//处理属性
			$tmp = $result['attrs'];
			$result['attrs'] = array();
			if($tmp){
				$tmp1 = explode(",",$tmp);
				if($tmp1){
					foreach($tmp1 as $v){
						$tmp2 = explode("@",$v);
						if(isset($tmp2[0]) && isset($tmp2[1])){
							$result['attrs'][$tmp2[0]][] = $tmp2[1];
						}
					}
				}
				unset($tmp,$tmp1,$tmp2);
			}
			//读取所有附件
			$attachlist = M("goods_attach")->query("goods_id={$result['id']}","id,filemd5,thumb,goods_id,ext,path,filename,dateline");
			$result['attachlist'] = $result['imglist'] = array();
			if($result['fm_id']){
				$result['imglist'][$result['fm_id']] = array();
			}
			if($attachlist){
				foreach($attachlist as $v){
					if($v['thumb'] >0){
						$result['imglist'][$v['id']] = $v;
					}else{
						$result['attachlist'][$v['id']] = $v;
					}
				}
			}
			//读取所有产品
			//$result['items'] = M("goods_server")->query("goods_no='{$result['goods_no']}'");
		}
		$tmp = array();
		$tmp = M('goods_relative')->query("goods_id='{$result['id']}'");
		if($tmp){
			foreach($tmp as $k=>$v){
				$result[$v['name']] = $v['value'];
			}
		}
		$result['content'] = isset($result['content'])?$result['content']:"暂无详情";
		return $result;
	}
	public function get_top($wherestr="",$limit=5,$fields="*",$orderby="dateline DESC"){
		$result = array();
		$query = new tQuery("goods");
		$query->where = $wherestr;
		$query->limit = $limit;
		$query->fields = $fields;
		$query->order = $orderby;
		$result = $query->find();
		return $result;
	}
	public function del($id=0){
		if(M("goods")->del("id={$id}")){
			C("attach")->delete(array(
				"table_name"  => $this->attach_table,
				"where"       => "goods_id={$id}",
				"path"        => $this->attach_path,
				"img_size"    => $this->attach_img_size,
			));
			return 1;
		}
		return 0;
	}
	//更新产品总数
	public function update_goods_nums($where=""){
		if($where){
			$sql = "update `@goods` as a set nums=(select count(id) from `@goods_server` as b  where b.indel=0 AND inlock=0 AND b.goods_no=a.goods_no) where 1 AND {$where}";
			Sq($sql);
		}
	}
	//更新产品总数
	public function update_goods_used($where=""){
		if($where){
			$sql = "update `@goods` as a set used=(select count(id) from `@goods_server` as b  where b.server_st<5 AND b.indel=0 AND inlock=0 AND b.goods_no=a.goods_no) where 1 AND {$where}";
			Sq($sql);
		}
	}
	//保存产品服务器数据
	public function save_goodsserver($id=0,$data=array(),$rsync=0){
		global $uid,$timestamp,$realip;
		$userinfo = C("user")->get_cache_userinfo($uid);
		if($data['buy_uid']){
           $buy_userinfo = C("user")->get_cache_userinfo($data['buy_uid']);
           $data['buy_uname'] = $buy_userinfo['name'];
        }
        if($data['uid']){
           $sell_userinfo = C("user")->get_cache_userinfo($data['uid']);
           $data['uname'] = $sell_userinfo['name'];
        }
        $server_no = $data['server_no'];
		if($id == 0){
			$data['dateline'] = $timestamp;
			$ret = M("goods_server")->set_data($data)->add();
			if($ret){
                $log = "服务器编号：{$server_no}";
                $log .= (isset($data['expiry']) && $data['expiry'])?("期限：".tTime::get_datetime("Y-m-d",$data['expiry'])):"";
                $log .= (isset($data['start_dateline']) && $data['start_dateline'])?("开始：".tTime::get_datetime("Y-m-d",$data['start_dateline'])):"";
                $log .= (isset($data['server_st']) && $data['server_st'])?("状态：".tFun::dataconfig("server_st",$data['server_st'])):"";
                $log .= (isset($data['amount']) && $data['amount'])?("金额：{$data['amount']}"):"";
                $log .= (isset($data['buy_uname']) && $data['buy_uname'])?("购买人：{$data['buy_uname']}"):"";
                $log .= (isset($data['uname']) && $data['uname'])?("销售：{$data['uname']}"):"";
                
				C("user")->log("增加产品服务器",$log);
			}
		}else{
			$row = M("goods_server")->get_row("id='{$id}'");
			unset($data['server_no']);
			$ret = M("goods_server")->set_data($data)->update("id='{$row['id']}'");
			if($ret){
                $log = "服务器编号：{$server_no}";
                $log .= (isset($data['expiry']) && $data['expiry'])?("期限：".tTime::get_datetime("Y-m-d",$data['expiry'])):"";
                $log .= (isset($data['start_dateline']) && $data['start_dateline'])?("开始：".tTime::get_datetime("Y-m-d",$data['start_dateline'])):"";
                $log .= (isset($data['server_st']) && $data['server_st'])?("状态：".tFun::dataconfig("server_st",$data['server_st'])):"";
                $log .= (isset($data['amount']) && $data['amount'])?("金额：{$data['amount']}"):"";
                $log .= (isset($data['buy_uname']) && $data['buy_uname'])?("购买人：{$data['buy_uname']}"):"";
                $log .= (isset($data['uname']) && $data['uname'])?("销售：{$data['uname']}"):"";
                
				C("user")->log("修改产品服务器",$log);
			}
		}
		return $ret?$ret:1;
	}
	//获取订单服务器行
    public function get_goodsserver_row($server_no = ""){
    	if(empty($id))return array();
    	$res = M("goods_server")->get_row("server_no='{$server_no}'");
    	return $res;
    }
	//获取分类
	public function get_cate($id =''){
		$re = array();
		$key = "id";
		if(!is_int($id)){
			$key = "ident";
		}
		$tmp = C("category","goods_cate")->get(0);
		foreach($tmp as $k=>$v){
			if($v[$key] == $id){
				$re = $v;
				break;
			}
		}
		return $re;
	}
	//创建货物编号
	public function create_no(){
		return tUtil::create_no("GP");
	}
	//获取评论列表
	public function get_comments($c=array(),$pageurl=""){
		$result = array();
		$c['table'] = isset($c['table'])?$c['table']:"goods_comment";
		$c['where'] = isset($c['where'])?$c['where']:"1";
		$c['page'] = isset($c['page'])?$c['page']:1;
		$c['pagesize'] = isset($c['pagesize'])?$c['pagesize']:18;
		$c['order'] =  isset($c['order'])?$c['order']:"dateline DESC";
		$query = new tQuery($c['table']);
		foreach($c as $k=>$v){
			$query->$k = $v;
		}
		
		$result['list'] = $query->find();
		$result['pagebar'] = $query->get_pagebar($pageurl);
		$result['total'] = $query->total;
		$result['totalpage'] = $query->totalpage;
		//列表处理
		$_ids = array();
		if($result['list']){
			$i = 1;
			foreach($result['list'] as $k=>$v){
				$_ids[] = $v["id"];
				$result['list'][$k]['lou'] = ($i)+($c['page']-1)*$c['pagesize'];//处理楼
				$i++;
			}
		}
		$result["ids"] = $_ids;
		return $result;
	}
	//生成模型缓存
	public function model_makecache($id = 0){
		$data = $this->get_model($id,false);
		tCache::write("goods_model_{$id}",$data);
	}
	//获取模型缓存
	public function get_model($model_id=0){
		if(empty($model_id))return array();
		$cls_catesss = new cls_category("goods_model_attrspec","c.model_id='{$model_id}'");
		$model_cate = $cls_catesss->get(0);
		if($model_cate){
			foreach($model_cate as $k => $v){
				$all_val = explode(",",$v['all_val']);
				$new_val = array();
				$model_cate[$k]['val'] = array();
				if($all_val){
					foreach($all_val as $v0){
						$tmp = explode("@",$v0);
						$model_cate[$k]['val'][$tmp[0]] = isset($tmp[1])?$tmp[1]:"";
					}
				}
			}
		}
		return $model_cate;
	}
	
	
	//更新
	public function update_orderserver($id,$data){
		
	}
}
?>