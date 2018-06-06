<?php
/**
 * 前台附件
 * by Thinkhu 2014 
 */
class front_attach extends tController{
	public $layout = "site";
	public function __construct(){
		parent::__construct('front_attach');
	}
	//产品附件上传
	public function goods_upload($up = 1,$data = array()){
		$table_name = C("goods")->attach_table;
		$file_types = C("goods")->attach_types;
		$max_size   = C("goods")->attach_size;
		$path       = C("goods")->attach_path;
		$img_size   = C("goods")->attach_img_size;  //array(1=>array(50,50),2=>array(100,100),3=>array(180,180),4=>array(320,320));
		if($up == 0){//创建上传组件
			$attachlist  = M($table_name)->query("goods_id=".$data['id'],"*","description ASC,dateline ASC",500);
			$fm_id   = (isset($data['fm_id']) && $data['fm_id'])?$data["fm_id"]:0;
			$fm      = (isset($data['fm']) && $data['fm'])?$data["fm"]:"";
			$goods_id    = $data['id'];
			$options = array(
		      "btn_insert"    => 0,
		      "upload_url"    => U("/front_attach/".__FUNCTION__),
		      "delete_url"    => U("/front_attach/".__FUNCTION__."?do=del"),
		      "post_params"   => array("goods_id"=>$goods_id),
		      "attachlist"    => $attachlist,
		      "upload_types"  => $file_types,
		      "max_size"      => $max_size,
		      "path"          => $path,
		      "fm_id"         => $fm_id,
			  "fm"            => $fm,
		      "inp_fm"        => "fm",
		      "inp_fm_id"     => "fm_id",
		  	);
		    return C("attach")->create_upload($options);
		}elseif($up == 1){
			$do        = R("do","string");
			$goods_id  = R("goods_id","int");
			$uid       = R("uid","int");
			if($do === "del"){//删除附件
				$attach_id = R("attach_id","int");
				$option = array(
				    "table_name" => $table_name,
					"path"       => $path,
					"img_size"   => $img_size,
					"where"      => "id = {$attach_id}"
				);
				if($attach_id){
					$ret = C("attach")->delete($option);
				}
				if($ret){
					tAjax::json(array("error"=>0,"message"=>"删除成功！"));
				}
				tAjax::json(array("error"=>1,"message"=>"删除失败！"));
			}else{//上传附件
				$option = array(
				    "table_name" => $table_name,
					"path"       => $path,
					"ext_insert" => array("goods_id" => $goods_id,"uid"=>$uid),
					"file_types" => $file_types,
					"max_size"   => $max_size,
					"img_size"   => $img_size
				);
				$return = C("attach")->upload($option);
				tAjax::json($return);
			}
		}
	}
	//附件上传
	public function photos_upload($up = 1,$data = array()){
		$table_name = C("photos")->attach_table;
		$file_types = C("photos")->attach_types;
		$max_size   = C("photos")->attach_size;
		$path       = C("photos")->attach_path;
		$img_size   = C("photos")->attach_img_size;  //array(1=>array(50,50),2=>array(100,100),3=>array(180,180),4=>array(320,320));
		if($up == 0){//创建上传组件
			$attachlist  = M($table_name)->query("photos_id=".$data['id'],"*","description ASC,dateline ASC",500);
			$fm_id   = (isset($data['fm_id']) && $data['fm_id'])?$data["fm_id"]:0;
			$fm      = (isset($data['fm']) && $data['fm'])?$data["fm"]:"";
			$photos_id   = $data['id'];
			$options = array(
					"btn_insert"    => 0,
					"upload_url"    => U("/front_attach/".__FUNCTION__),
					"delete_url"    => U("/front_attach/".__FUNCTION__."?do=del"),
					"post_params"   => array("photos_id"=>$photos_id),
					"attachlist"    => $attachlist,
					"upload_types"  => $file_types,
					"max_size"      => $max_size,
					"path"          => $path,
					"fm_id"         => $fm_id,
					"fm"            => $fm,
					"inp_fm"        => "fm",
					"inp_fm_id"     => "fm_id",
			);
			return C("attach")->create_upload($options);
		}elseif($up == 1){
			$do        = R("do","string");
			$photos_id = R("photos_id","int");
			$uid       = R("uid","int");
			if($do === "del"){//删除附件
				$attach_id = R("attach_id","int");
				$option = array(
						"table_name" => $table_name,
						"path"       => $path,
						"img_size"   => $img_size,
						"where"      => "id = {$attach_id}"
				);
				if($attach_id){
					$ret = C("attach")->delete($option);
				}
				if($ret){
					tAjax::json(array("error"=>0,"message"=>"删除成功！"));
				}
				tAjax::json(array("error"=>1,"message"=>"删除失败！"));
			}else{//上传附件
				$option = array(
						"table_name" => $table_name,
						"path"       => $path,
						"ext_insert" => array("photos_id" => $photos_id,"uid"=>$uid),
						"file_types" => $file_types,
						"max_size"   => $max_size,
						"img_size"   => $img_size
				);
				$return = C("attach")->upload($option);
				tAjax::json($return);
			}
		}
	}
	//CMS附件上传
	public function cms_upload($up = 1,$data = array(),$insert_js=""){
		$table_name = C("cms")->attach_table;
		$file_types = C("cms")->attach_types;
		$max_size   = C("cms")->attach_size;
		$path       = C("cms")->attach_path;
		$img_size   = C("cms")->attach_img_size;  //array(1=>array(50,50),2=>array(100,100),3=>array(180,180),4=>array(320,320));
		if($up == 0){//创建上传组件
			$tid     = (isset($data['tid']) && $data['tid'])?$data['tid']:0;
			$pid     = (isset($data['pid']) && $data['pid'])?$data['pid']:0;
			$fm_id   = (isset($data['fm_id']) && $data['fm_id'])?$data["fm_id"]:0;
			$fm      = (isset($data['fm']) && $data['fm'])?$data["fm"]:"";
			//获取附件
			$attachlist  = M($table_name)->query("tid={$tid} AND pid={$pid}","*","description ASC,dateline ASC",500);
			$options = array(
		      "btn_insert"    => 0,
		      "upload_url"    => U("admin@/front_attach/".__FUNCTION__),
		      "delete_url"    => U("admin@/front_attach/".__FUNCTION__."?do=del"),
		      "post_params"   => array("tid"=>$tid,"pid"=>$pid),
		      "attachlist"    => $attachlist,
		      "upload_types"  => $file_types,
		      "max_size"      => $max_size,
		      "path"          => $path,
		      "btn_insert"    => 1,
		      "fm_id"         => $fm_id,
		      "fm"            => $fm,
		      "insert_js"     => "{$insert_js}",
		      "inp_fm"        => "fm",
		      "inp_fm_id"     => "fm_id",
		  	);
		    return C("attach")->create_upload($options);
		}elseif($up == 1){
			$do   = R("do","string");
			$tid  = R("tid","int");
			$pid  = R("pid","int");
			$uid  = R("uid","int");
			if($do === "del"){//删除附件
				$attach_id = R("attach_id","int");
				$option = array(
				    "table_name" => $table_name,
					"path"       => $path,
					"img_size"   => $img_size,
					"where"      => "id = {$attach_id}"
				);
				if($attach_id){
					$ret = C("attach")->delete($option);
				}
				if($ret){
					tAjax::json(array("error"=>0,"message"=>"删除成功！"));
				}
				tAjax::json(array("error"=>1,"message"=>"删除失败！"));
			}else{//上传附件
				$option = array(
				    "table_name" => $table_name,
					"path"       => $path,
					"ext_insert" => array("tid" => $tid,"pid" => $pid,"uid"=>$uid),
					"file_types" => $file_types,
					"max_size"   => $max_size,
					"img_size"   => $img_size
				);
				$return = C("attach")->upload($option);
				tAjax::json($return);
			}
		}
	}
	//头像上传
	public function avatar_upload(){
		$uid = R("uid","int");
		$userinfo = C("user")->get_cache_userinfo($uid);
		$post_name = 'thk_filedata';
		if(isset($userinfo['uid']) && $userinfo['uid'] && empty($_FILES[$post_name]) === false){
	                $file_avatar_path = C("user")->get_avatar_path($userinfo['uid']);
					$file_store_path = ROOT_PATH."attach/avatar/".$file_avatar_path."/";
					$file_path = tUrl::create()."attach/avatar/".$file_avatar_path."/";
					$return_file = "";
					$up_obj = new tUpload(2048,array("jpg","gif","png","jpeg"));
					$up_obj->set_dir($file_store_path);
					$upstate = $up_obj->execute();
					//获取用户信息
					$is_array = null;
					$upfile_result = $upstate[$post_name][0];
					if($upfile_result['flag']==1){
						//上传成功后图片信息
						$file_name   = $upfile_result['dir'].$upfile_result['name'].".".$upfile_result['ext'];
						tImage::thumb($file_name,300,300,"");
						tFile::copy($file_name,ROOT_PATH.C("user")->get_avatar($userinfo['uid'],"300"),"x");
						tAjax::respons(array("error"=>0,"id"=>$userinfo['uid'],"path"=>C("user")->get_avatar($userinfo['uid'],"300")),"json");
					}
	     }
	     tAjax::respons(array("error"=>1,"message"=>"upload error!"),"json");
	}
	//公司LOGO上传
	public function company_logo_upload(){
		$members_id = R("uid","int");
		$userinfo = C("member")->get_cache_userinfo($members_id);
		$post_name = 'thk_filedata';
		$logo_size = array(120,60);
		if(isset($userinfo['id']) && $userinfo['id'] && empty($_FILES[$post_name]) === false){
	                $file_avatar_path = C("member")->get_avatar_path($userinfo['id']);
					$file_store_path = ROOT_PATH."attach/avatar/".$file_avatar_path."/";
					$file_path = tUrl::create()."attach/avatar/".$file_avatar_path."/";
					
					$return_file = "";
					$up_obj = new tUpload(2048,array("jpg","gif","png","jpeg"));
					
					$up_obj->set_dir($file_store_path);
					$upstate = $up_obj->execute();
					//获取用户信息
					$is_array = null;
					$upfile_result = $upstate[$post_name][0];
					if($upfile_result['flag']==1){
						//上传成功后图片信息
						$file_name   = $upfile_result['dir'].$upfile_result['name'].".".$upfile_result['ext'];
						tImage::thumb($file_name,$logo_size[0],$logo_size[1],"");
						tFile::copy($file_name,ROOT_PATH.C("member")->get_avatar($userinfo['id'],"_logo"),"x");
						
						
						$userinfo['setlogo'] = 1;
	    		        C('member')->set_cache_userinfo($userinfo['id'],$userinfo);
	    		
						tAjax::respons(array("error"=>0,"id"=>$userinfo['id'],"path"=>U("/").C("member")->get_avatar($userinfo['id'],"_logo")),"json");
					}
	     }
	     tAjax::respons(array("error"=>1,"message"=>"upload error!"),"json");
	}
}
?>