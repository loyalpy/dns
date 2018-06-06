<?php
/**
 * @brief 附件上传模块
 * @class cls_attach
 */
class cls_attach{
	//创建多文件上传
	public function create_upload($option = array()){
		global $uid;
		//配置参数
		//url 
		$option['upload_url']   = !isset($option['upload_url'])?U('/ucenter/upload_attach'):$option['upload_url'];
		$option['delete_url']   = !isset($option['delete_url'])?U('/ucenter/upload_attach_del'):$option['delete_url'];
		//upload setting
		$option['max_size']      = !isset($option['max_size'])?2048:$option['max_size'];
		$option['upload_types'] = !isset($option['upload_types'])?array("jpg","gif","png","bmp","jpeg"):$option['upload_types'];
		$option['upload_types_str'] = "*.".implode(";*.",$option['upload_types']);
		//parm
		$option['post_params']  = !isset($option['post_params'])?array():$option['post_params'];
		$option['post_params']['uid'] = $uid;
		$option['post_params_json'] = JSON::encode($option['post_params']);
		//file_queue
		$option['file_queue_limit'] = !isset($option['file_queue_limit'])?20:$option['file_queue_limit'];
		//com
		$option['upload_btn_txt']   = !isset($option['upload_btn_txt'])?"上传附件":$option['upload_btn_txt'];
		$option['inp_fm']   = !isset($option['inp_fm'])?"data[fm]":$option['inp_fm'];
		$option['inp_fm_id']   = !isset($option['inp_fm_id'])?"data[fm_id]":$option['inp_fm_id'];
		$option['fm_id']   = !isset($option['fm_id'])?0:$option['fm_id'];
		//insert js
		//1 or 0
		$option['btn_setfm']   = !isset($option['btn_setfm'])?1:$option['btn_setfm'];
		$option['btn_insert']  = !isset($option['btn_insert'])?0:$option['btn_insert'];
		$option['btn_delete']  = !isset($option['btn_delete'])?1:$option['btn_delete'];
		
		$option['insert_js']   = !isset($option['insert_js'])?($option['btn_insert']?"KE_Mcontent.insertHtml(\"[attach]\"+file_id+\"[/attach]\");":""):$option['insert_js'];
		
		//附件表
		$option['path'] = !isset($option['path'])?"attach":$option['path'];
		$option['attachlist']  = !isset($option['attachlist'])?array():$option['attachlist'];
		//初始上传组件的最大尺寸
		$up_obj = new tUpload($option['max_size']);
        $option['upmaxsize'] = intval($up_obj->get_maxsize()/1024);
        App::$controller->assign("option",$option);
        echo App::$controller->fetch("misc/upload");
	}
	//多文件上传单个处理
	public function upload($option = array()){
		global $uid;
		//初始选项
		$table_name    = !isset($option['table_name'])?"user_attach":$option['table_name'];
		$ext_insert    = !isset($option['ext_insert'])?array():$option['ext_insert'];
		$path          = !isset($option['path'])?"attach":$option['path'];
		$max_size      = !isset($option['max_size'])?2048:$option['max_size'];
		$file_types	   = !isset($option['file_types'])?array("jpg","gif","png","bmp","jpeg"):$option['file_types'];
		$post_name     = !isset($option['post_name'])?'upload_file':$option['post_name'];
		$img_size      = !isset($option['img_size'])?array(1=>array(50,50),2=>array(100,100),3=>array(180,180),4=>array(320,320)):$option['img_size'];
		
		$ext_insert['uid'] = (isset($ext_insert['uid']) && $ext_insert['uid'])?$ext_insert['uid']:$uid;
		$file = isset($_FILES[$post_name])?$_FILES[$post_name]:0;
		if($file == 0)die("hack!");
        //上传路径
		$file_date_path = tTime::get_datetime("Y/m/d");
		$file_store_path = ROOT_PATH."www/static/attach/{$path}/".$file_date_path."/";
		$file_path = U("")."attach/{$path}/".$file_date_path."/";
		//返回文件		
		$return_file = "";
		//上传对象
		$up_obj = new tUpload($max_size,$file_types);
		$up_obj->set_dir($file_store_path);
		$upstate = $up_obj->execute();
		$is_array = null;
		foreach($upstate as $key => $rs){
			foreach($rs as $inner_key => $val){
				if($val['flag'] == 1){
					//上传成功后图片信息
					$file_name   = $val['dir'].$val['name'].".".$val['ext'];
					$file_md5    = md5_file($file_name);
					$rs[$inner_key]['path']  = $file_name;
					//插入数据
					$insert_data = array(
						'filemd5'    => $file_md5,//判断文件唯一标准
						'ext'        => $val['ext'],
						'thumb'      => 0,//如果为 0 则不是图片附件
						'path'       => $file_date_path,
						'filename'   => $val['name'],
						'size'       => $val['size'],
						'width'      => $val['width'],
						'height'     => $val['height'],
						'dateline'   => tTime::get_time(),
						'description'=> $val['ininame'],
					);
					if($ext_insert){
						foreach ($ext_insert as $key=>$v){
							$insert_data[$key] = $v;
						}
					}
					//处理图片附件
					if($val['is_image'] == 1){
						//裁切图
			            if($img_size){
			            	foreach($img_size as $k=>$v){
			            		$file_to_name   = $val['dir'].$val['name']."_{$v[0]}_{$v[1]}.".$val['ext'];
			            		if(tImage::copythumb($file_name,$file_to_name,$v[0],$v[1])){
			            			$insert_data['thumb'] = $k;
			            		}/*
			            		if($v[0] <= $val['width'] || $v[1] <= $val['height']){
			            			$file_to_name   = $val['dir'].$val['name']."_{$v[0]}_{$v[1]}.".$val['ext'];
			            			if(tImage::copythumb($file_name,$file_to_name,$v[0],$v[1])){
			            				$insert_data['thumb'] = $k;
			            			}
			            		}*/
			            	}
			            }
			            //获取文件信息
				        if(tImage::fixthumb($file_name,1280,100000,"")){
				             $insert_data["width"] = 1280;
				             $insert_data["height"] = intval($insert_data["width"]*$val['height']/$val['width']);
				        }
			            $return_file = U("static@").$file_path.$val['name']."_SIZE.{$val['ext']}";
					}
					M($table_name)->set_data($insert_data);
		            $insert_id = M($table_name)->add();
		            return array("error"=>0,"id"=>$insert_id,"ext"=>$insert_data['ext'],"thumb"=>$insert_data['thumb'],"desc"=>$insert_data['description'],"file"=>$return_file);
				}elseif($val['flag'] == -1){
					return array("error"=>1,"message"=>"文件格式限制上传");
				}elseif($val['flag'] == -2){
					return array("error"=>1,"message"=>"大小超过限度");
				}else{
					return array("error"=>1,"message"=>"上传失败");
				}
			}
		}
	}
	//删除附件
	public function delete($option = array()){
		global $uid;
		//初始选项
		$table_name    = !isset($option['table_name'])?"user_attach":$option['table_name'];
		$where         = !isset($option['where'])?"0":$option['where'];
		$path          = !isset($option['path'])?"attach":$option['path'];
		$img_size      = !isset($option['img_size'])?array(1=>array(50,50),2=>array(100,100),3=>array(180,180),4=>array(320,320)):$option['img_size'];
		
		if($where){
			$attachlist = M($table_name)->query($where);
			if($attachlist){
				 foreach($attachlist as $k=>$v){
				     if($v['thumb'] >0 ){
					     foreach($img_size as $thumb=>$size){
					     	tFile::unlink(ROOT_PATH."www/static/attach/{$path}/".$v['path'].'/'.$v['filename']."_{$size[0]}_{$size[1]}.".$v['ext']);
					     }
				     }
				     tFile::unlink(ROOT_PATH."www/static/attach/{$path}/".$v['path'].'/'.$v['filename'].".".$v['ext']);
				  }
			}
			//删除数据库
			return M($table_name)->del($where);
		}
		return 0;
	}
	//更新附件
	public function update($option = array()){
		$table_name    = !isset($option['table_name'])?"user_attach":$option['table_name'];
		$where         = !isset($option['where'])?"0":$option['where'];
		$data          = !isset($option['data'])?array():$option['data'];
		if($where && $data){
			 M($table_name)->set_data($data);
			 return M($table_name)->update($where);
		}
	}
	//获取文件类型
	public function get_file_types($only_img = 1){
		if($only_img){
			return array("jpg","gif","png","bmp","jpeg");
		}else{
			return array("jpg","gif","png","bmp","jpeg","rar","tar.gz","zip","doc","docx","ppt");
		}
	}
	//上传单个文件
	public function alone_upload($option = array()){
		global $uid;
		//初始选项
		$path          = !isset($option['path'])?"attach":$option['path'];
		$max_size      = !isset($option['max_size'])?2048:$option['max_size'];
		$file_types	   = !isset($option['file_types'])?array("jpg","gif","png","bmp","jpeg"):$option['file_types'];
		$post_name     = !isset($option['post_name'])?'':$option['post_name'];
		$img_size      = !isset($option['img_size'])?array():$option['img_size'];

		if(empty($post_name))die("hack");
		$post_name_a = explode(",",$post_name);
        //上传路径
		$file_date_path = tTime::get_datetime("Y/m/d");
		$file_store_path = ROOT_PATH."attach/{$path}/".$file_date_path."/";
		$file_path = tUrl::create()."attach/{$path}/".$file_date_path."/";
		//返回文件		
		$return_file = "";
		//上传对象
		$up_obj = new tUpload($max_size,$file_types);
		$up_obj->set_dir($file_store_path);
		$upstate = $up_obj->execute();
		//返回
		$return = array();//array("error"=>1,"url"=>"","thumb"=>"","message"=>"上传失败");
		foreach($post_name_a as $v){
			$post_key = $v;
			$return[$post_key] = array("error"=>1,"url"=>"","thumb"=>"","message"=>"上传失败");
			if(isset($upstate[$post_key])){
				$upfiled = $upstate[$post_key][0];
				if($upfiled['flag'] == -1){
					$return[$post_key]['message'] = '上传的文件类型不符合';
				}else if($upfiled['flag'] == -2){
					$return[$post_key]['message'] = '大小超过限度';
				}else if($upfiled['flag'] == 1){
					$return[$post_key]['url'] = $file_path.$upfiled['name'].".".$upfiled['ext'];
				    //处理图片附件
					if($upfiled['is_image'] == 1){
						//裁切图
				        if($img_size){
				           	foreach($img_size as $k=>$v){
				           		if($v[0] <= $upfiled['width'] || $v[1] <= $upfiled['height']){
				           			if(tImage::fixthumb($return[$post_key]['url'],$v[0],$v[1],"-{$v[0]}-{$v[1]}",false)){
				           				$insert_data['thumb'] = $k;
				           			}
				           		}
				           	}
				        }
				        //获取文件信息
					    tImage::fixthumb($return[$post_key]['url'],1280,100000,"");
					}
					$return[$post_key]['error'] = 0;
				}
			}
		}
		return $return;
	}
}
?>