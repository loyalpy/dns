<?php
/**
 * @brief CMS系统
 * @class cls_cms
 */
class cls_cms{
	public $attach_types = array("jpg","gif","png","bmp","jpeg");
	public $attach_img_size = array(1=>array(50,50),2=>array(100,100),3=>array(180,180),4=>array(320,320));
	public $attach_size  = 2048;
	public $attach_path  = "cms";
	public $attach_table = "cms_attach";
	//保存
	public function save($tid = 0, $pid = 0, $data = array(),$attachlist = array()){
		global $uid,$timestamp,$realip; 
		//获取发布人信息
		$userinfo = C("user")->get_cache_userinfo($uid);
		//数据处理
		$data['description'] = (isset($data["description"]) && $data["description"])?$data["description"]:tUtil::clear_htmltag($data["message"]);
		$data['attachment']  = $attachlist?count($attachlist):0;
		$message             = htmlspecialchars($data['message']);
		unset($data['message']);
		//处理状态
		$ret_tid = $tid;
		$ret_pid = $pid;
		if($tid == 0){ 
			$data['dateline']    = $timestamp;
			$data['uid']         = $userinfo['uid'];
			$data['uname']       = $userinfo['name'];
			M("cms_threads")->set_data($data);
			$ret_tid = M("cms_threads")->add();
			tCache::delete("cms_threads");
		}else{
			M("cms_threads")->set_data($data);
			M("cms_threads")->update("tid=$tid");
			tCache::delete("cms_threads");
		}
		//处理post
		if($ret_tid){
			if($pid == 0){
				$ups = array(
						'fid'        => $data['fid'],
						'tid'        => $ret_tid,
						'uid'        => $userinfo['uid'],
						'uname'      => $userinfo['name'],
					    'first'      => 1,
						'subject'    => $data["subject"],
						'dateline'   => $timestamp,
						'attachment' => $data['attachment'],
						'status'     => $data['status'],
						'userip'     => $realip,
						'message'    => $message
				);
				M("cms_posts")->set_data($ups);
				$ret_pid = M("cms_posts")->add();
			}else{
				//处理帖子
				$ups = array(
					'subject'    => $data["subject"],
					'userip'     => $realip,
					'message'    => $message,
					'attachment' => $data['attachment']
				);
				M("cms_posts")->set_data($ups);
				M("cms_posts")->update("pid='{$pid}' AND tid='{$ret_tid}' AND first=1");
			}
		}
	
		//附件处理
		if(is_array($attachlist)){
			foreach($attachlist as $attach_id=>$v){
				if($attach_id){
					C("attach")->update(
					   array(
					     "table_name" => $this->attach_table,
					     "where"      => "id=$attach_id AND tid={$tid} AND pid={$pid}",
					     "data"       => ($tid && $pid)?array("description"=>$v):array('tid'=>$ret_tid,"pid"=>$ret_pid,'description'=>$v),
					   )
					);
				 }
			}
		}
		return $ret_tid;
	}
	//获取列表
	public function get_list($c = array(),$pageurl=""){
		$result = array();  
		$c['table'] = isset($c['table'])?$c['table']:"cms_threads";
		$c['where'] = isset($c['where'])?$c['where']:"1";
		$c['page'] = isset($c['page'])?$c['page']:1;
		$c['pagesize'] = isset($c['pagesize'])?$c['pagesize']:30;
		$c['order'] =  isset($c['order'])?$c['order']:"dateline DESC";
		
		$query = new tQuery($c['table']);
		foreach($c as $k=>$v){
			$query->$k = $v;
		}
		$result['list'] = array();
		$result['list'] = $query->find();
		$result['pagebar'] = $query->get_pagebar($pageurl);
		$result['total'] = $query->total;
		$result['totalpage'] = $query->totalpage;
		//列表处理
		$_ids = array();
		if($result['list']){
			foreach($result['list'] as $k=>$v){
				$_ids[] = $v["tid"];
			}
		}
		$result["ids"] = $_ids;
		return $result;
	}
	//获取主题
	public function get_topic($tid=0,$pageurl = "", $perpage = 30, $isv=0){
		$result = array();
		$threadsinfo = M("cms_threads")->get_row("tid=$tid");
		if(isset($threadsinfo['tid'])){
			//更新阅读次数
			if($isv == 1){
				M("cms_threads")->set_data(array("views"=>"views+1"));
				M("cms_threads")->update("tid={$tid}",array("views"));
				tCache::delete("cms_threads");
			}
			$result = $this->get_postlist($threadsinfo['tid'],$perpage,$pageurl);
			$result['threads'] = $threadsinfo;
		}
		return $result;
	}
	//获取主题
	public function get_top($wherestr="",$limit=5,$fields="tid,subject,fm,fm_id,description,uid,uname,dateline",$orderby="dateline DESC"){
		$result = array();
		$query = new tQuery("cms_threads");
		$query->where = $wherestr;
		$query->limit = $limit;
		$query->fields = $fields;
		$query->order = $orderby;
		$result = $query->find();
		return $result;
	}
	//获取主题上一篇下一篇
	//上一篇下一篇
	public function prevnext($threadsinfo){
		if(empty($threadsinfo))return array();
		$result = array();
		$result["prev"] =M("cms_threads")->get_row("tid<{$threadsinfo['tid']} AND fid={$threadsinfo['fid']} AND status=0","tid,fid,subject","tid DESC");
		$result["next"] =M("cms_threads")->get_row("tid>{$threadsinfo['tid']} AND fid={$threadsinfo['fid']} AND status=0","tid,fid,subject","tid ASC");
		return $result;
	}
	//处理广场回复列表
	public function get_postlist($tid,$perpage=30,$pageurl=""){
	    $sql = $condition = $orderby = "";
	    $result = $postlist = $tmplist = array();
		$orderby .= "ORDER BY first DESC,dateline ASC";
	    //处理页码
	    $page = R("page","int");
		$page = $page?$page:1;
		
	    $tquery = new tQuery("cms_posts");
	    $tquery->order = "first DESC,dateline ASC";
	    $tquery->pagesize = $perpage;
	    $tquery->where = "tid={$tid}";
	    $tquery->page = $page;
	    $tmplist = $tquery->find();
	    $result["count"] = $tquery->total;
	    
		$attachpids = array();//所有附件回复ID
		$insert_attach_arr = array();//所有附件回复ID
		$i = 0;
		foreach($tmplist as $post){
		  //解析帖子
		   $postlist[$post['pid']] = $this->thread_procpost($post);	   
		   $postlist[$post['pid']]['lou'] = $i+($page-1)*$perpage;//处理楼
		   if($postlist[$post['pid']]['attachment']){
		       $attachpids[] = $post['pid'];
			   $insert_attach_arr[$post['pid']] = $postlist[$post['pid']]['insert_attach'];
		   }
		   $i ++;
		}
		$result["sheng"] = $result["count"] - $page*$perpage;
		$result["sheng"] = $result["sheng"]<0?0:$result["sheng"];
		unset($tmplist);
	    //处理附件
		$postlist = $this->parse_attach($attachpids,$insert_attach_arr,$postlist);
		$result['list'] = $postlist;
		return $result;
	}
	//处理广场回复每个回复
	public function thread_procpost($post){
	   $post['message'] = htmlspecialchars_decode($post['message']);
	   $post['message'] = preg_replace('/<img\s+title\=""\s+alt\=""\s+src\="(.*?)"\s+aid\="(\d+)"\s+\/>/i', '[attach]\\2[/attach]', $post['message']);
	   $post['attachments'] = array();
	   if($post['attachment']) {
			if(preg_match_all("/\[attach\](\d+)\[\/attach\]/i", $post['message'], $matchaids)) {
				$post['insert_attach'] = $matchaids[1];
			}
		} else {
			$post['message'] = preg_replace("/\[attach\](\d+)\[\/attach\]/i", '', $post['message']);
		}
		$post['insert_attach'] = isset($post['insert_attach'])?$post['insert_attach']:array();
		
		return $post;
	}
	//处理回复附件
	public function parse_attach($attachpids,$insert_attach_arr,$postlist){
		$attachexists = FALSE;
		if(empty($attachpids))return $postlist;
		$attachlist = M("cms_attach")->query("pid IN('".implode("','",$attachpids)."')");
	    $attachlist = is_array($attachlist)?$attachlist:array();
	    $findattach = isset($findattach)?$findattach:array();
		foreach($attachlist as $k=>$val){
		    $attachexists = TRUE; 
			$file_path = $val['path'].'/'.$val['filename'];
		    $ext = $val['ext'];	
		    $val['icon'] = "";//file_icon($val['extension'],'filetype');
		    if($val['thumb']){
			  //$thumb_url = $file_path.$val['filename'].'_thumb.'.$val['ext'];
		      //$val['thumb'] = file_exists(ROOT_PATH."attach/cms/{$file_path}-50-50.{$ext}")?"{$file_path}-50-50.{$ext}":"";
		      $val['image'] = "{$file_path}.{$ext}";
		    }
		    $val['file_url'] = "{$file_path}.{$ext}";
		    $val['size'] = tFile::format_size($val['size']);
		    $val['dateline'] = tTime::format_dateline("Y-m-d",$val['dateline']);
			$val['attach_str'] = $this->parse_attachstr($val);
			$postlist[$val['pid']]['attachments'][$val['id']] = $val;
			//处理内容中的附件
			if(is_array($insert_attach_arr) && $insert_attach_arr[$val['pid']]){
				if(in_array($val['id'],$insert_attach_arr[$val['pid']])) {
					$findattach[$val['pid']][] = "/\[attach\]$val[id]\[\/attach\]/i";
					$replaceattach[$val['pid']][] = $postlist[$val['pid']]['attachments'][$val['id']]['attach_str'];
				}
			}
		}
		if($attachexists){
			foreach($insert_attach_arr as $pid => $aids) {
				if(isset($findattach[$pid]) && $findattach[$pid]) {
					$postlist[$pid]['message'] = preg_replace($findattach[$pid], $replaceattach[$pid], $postlist[$pid]['message']);
					$postlist[$pid]['message'] = preg_replace($findattach[$pid], '', $postlist[$pid]['message']);
				}
			}
		}
		return $postlist;
	}
	//附件显示
	public function parse_attachstr($attachments){
	   $str = "";
	   if($attachments['thumb']){
	      $str .= "<p class=\"attach\" style=\"text-align:center;\">";
	      $str .= "<a title='{$attachments['filename']}' rel='attachimg' href=\"/attach/cms/{$attachments['image']}\" target=\"_blank\">";
		  $str .= "<img width=".($attachments['width']>600?600:$attachments['width'])." alt='{$attachments['description']}' src=\"/attach/cms/{$attachments['image']}\" />";
		  $str .= "</a>";
		  $str .= "</p>";
	   }else{
	   	  $str .= "<p class=\"attach\" style=\"text-align:left;\">";
	      $str .= "<span>{$attachments['icon']}</span><a  href=\"/attach/square/{$attachments['file_url']}\" class=\"act3\" >{$attachments['description']}</a>";
	      $str .= "</p>";
	   }   
	   return $str;
	}
	//删除主题
	public function del_thread($tid = 0){
		if(empty($tid))return -1;
		$threaddata = M("cms_threads")->get_row("tid=$tid");
		if(!isset($threaddata['tid']))return -1;
		$tid = $threaddata['tid'];
		$fid = $threaddata['fid'];
	  	//删除附件
	  	if(M("cms_threads")->del("tid=$tid")){
	  		$rows = M("cms_posts")->del("tid='{$tid}'");//删除回复
	  		C("attach")->delete(array(
				   "table_name"  => $this->attach_table,
				   "where"       => "tid={$tid}",
				   "path"        => $this->attach_path,
				   "img_size"    => $this->attach_img_size,
				));
	  		M("cms_forums")->set_data(array("threads"=>"threads-1","posts"=>"posts-{$rows}"));
	  		M("cms_forums")->update("id=$fid",array("threads","posts"));
			tCache::delete("cms_threads");
	  	}
		return 1;
	}
	//删除帖子
	public function del_post($pid = 0){
		if(empty($pid))return -1;
		  $data = M("cms_posts")->get_row("id=$pid");
		  if(!isset($data['id']))return -1;
		  $tid = $data['tid'];
		  $pid = $data['id'];
		  $fid = $data['fid'];
          if(M("cms_posts")->del("id=$pid")){
	          	C("attach")->delete(array(
				   "table_name"  => $this->attach_table,
				   "where"       => "pid={$pid}",
				   "path"        => $this->attach_path,
				   "img_size"    => $this->attach_img_size,
				));
		  		//更新分类数量
		        M("cms_forums")->set_data(array("posts"=>"posts-1"));
			  	M("cms_forums")->update("id=$fid",array("posts"));
			  	//更新主题回复数字
			  	M("cms_threads")->set_data(array("replies"=>"replies-1"));
			  	M("cms_threads")->update("tid=$tid",array("replies"));
			    tCache::delete("cms_threads");
          }
		  return 1;
	}
	//获取单页缓存
	public function get_onepage_cache($cat_id = "about"){
		$key              = "onepage_{$cat_id}";
		$cache_onepage    = tCache::get($key);
		if($cache_onepage == null || empty($cache_onepage)){
			$tmp = M("cms_onepage")->query("cat_id='{$cat_id}'","id,ident,cat_id,sort,name,description,content,seo_title,seo_keyword,seo_description,hit","sort ASC,dateline DESC");
			if($tmp && is_array($tmp)){
				foreach($tmp as $k=>$v){
					$v["key"] = $k+1;
					$cache_onepage[$v['ident']] = $v;
				}
			}
			tCache::set($key,$cache_onepage,30*864000);
		}
		return $cache_onepage;
	}
	//写缓存
	public function write_static_cache($type,$fids){
	    $fid_arr = array();
	    if(is_string($fids)){
	    	$fid_arr = explode(",",$fids);
	    }else{
	    	$fid_arr = $fids;
	    }
	    $fid_arr = array_unique($fid_arr);
	    if(!in_array($type,array("ad","cms_friendlink")))return ;
	    foreach ($fid_arr as $v){
	    	if($v){
				$list = M($type)->query("status = 1 AND cat_id='{$v}'","*","sort ASC");
			    tCache::write("{$type}_".$v,$list);
	    	}
		}
	}
}
?>