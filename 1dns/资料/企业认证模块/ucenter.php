<?php
class ucenter extends UC{
	public function __construct(){
		parent::__construct('ucenter');
	}
	public function index(){
		global $uid;
		if($this->userinfo['utype'] == 1){
			if(empty($this->userinfo['resume_id'])){
				$this->redirect("/uc_resume/edit?do=step1");
			}			
			$this->display("ucenter/index_u1");
		}elseif($this->userinfo['utype'] == 2){
			$this->company_id = $this->userinfo['company_id'];
			$this->display("ucenter/index_u2");
		}elseif($this->userinfo['utype'] == 3){
			$this->medier_id = $this->userinfo['medier_id'];
			$this->display("ucenter/index_u3");
		}
	}
	//首页获取
	public function index_tui(){
		$do = R("do","string");
		switch($do){
			case "get_tuijob":
				$condi = array(
						"job_cate"    => $this->userinfo['resume']['job_cate'],
						"page"        => 1,
						"keyword"     => "",
						"job_area"    => "",
						"pagesize"    => 8,
				);
				$res = SDK::web_api("/Job/GetListTui",$condi);
				if($res['status'] == 1){
					$tuilist = $res['data'];
					$tuilist['pagebar'] = tFun::pagebar($tuilist['total'],$tuilist['pagesize'],$tuilist['page'],tFun::build_url('/job/index',$condi,array('page'=>'_PG_')));
				}else{
					$tuilist = array();
				}
				tAjax::json($tuilist);
				break;
			default:
				break;
		}		
	}
	//修改密码
	public function profile_passwd(){
		global $uid;
	    if(tUtil::check_hash()){
	    	$pass = R("pass",'string');
	    	$pass2 = R("pass2",'string'); 
	    	$oldpass = R("oldpass",'string');
	    	if(strlen($pass)<6 || strlen($pass)>18){
	    		tAjax::json_error("新密码为6-18位字符！");
	    	}
	    	
	    	if($pass == $oldpass){
	    		tAjax::json_error("原密码与新密码相同！");
	    	}
	    	
	    	if(md5($oldpass) != M("user")->get_one("uid=$uid","password")){
	    		tAjax::json_error("原密码错误！");
	    	}
	    	
	    	if($pass != $pass2){
	    		tAjax::json_error("两次输入密码不一致！");
	    	}
	    	$passmd5 = md5($pass);
	    	M("user")->set_data(array("password"=>$passmd5));
	    	if(M("user")->update("uid='{$uid}'")){
	    		tAjax::json(array("error"=>0,"message"=>"修改成功！"));
	    	}else{
	    		tAjax::json_error("修改失败！");
	    	}
	    }else{
	    	$this->display();
	    }
	}
	//个人基本信息
    public function profile_basic(){
    	global $uid;
		//获取用户信息
	    if(tUtil::check_hash()){
	    	$data = array(
	    		"realname"   => R("realname","string"),
	    		"sex"        => R("sex","int"),
	    		"company"    => R("company","string"),
	    		"depart"     => R("depart","string"),
	    		"post"       => R("post","string"),
	    		"phone"      => R("phone","string"),
	    		"email"      => R("email","string"),
	    		"mobile"     => R("mobile","string"),
	    		"signname"   => R("signname","string"),
	    	);
	    	$birthdays = R("birthdays","string");
	    	$areas     = R("areas","string");
	    	$data['birthday'] = strtotime(implode("-",$birthdays));
	    	$data['area']     = implode(",",$areas);
	    	//检查手机
	    	if(empty($this->userinfo['uname']) && $data['uname']){
	    		$row = M("user")->get_row("uname='{$data['uname']}' AND uid<>'{$uid}'");
	            if(!empty($row)){
	            	tAjax::json_error("该用户名已经存在");
	            }
	    	}else{
	    		unset($data['uname']);
	    	}
	    	//检查手机
	    	if(empty($this->userinfo['mobile']) && $data['mobile'] && tValidate::is_mobile($data['mobile'])){
	    		$row = M("user")->get_row("mobile='{$data['mobile']}' AND uid<>'{$uid}'");
	            if(!empty($row)){
	            	tAjax::json_error("该手机已经存在");
	            }
	    	}else{
	    		unset($data['mobile']);
	    	}
	    	//检查邮箱
	    	if(empty($this->userinfo['email']) && $data['email'] && tValidate::is_email($data['email'])){
	    		$row = M("user")->get_row("email='{$data['email']}' AND uid<>'{$uid}'");
	            if(!empty($row)){
	            	tAjax::json_error("该邮箱已经存在");
	            }
	    	}else{
	    		unset($data['email']);
	    	}
	    	$ret = C("user")->update_user($uid,$data);
	    	tAjax::json_success("保存成功！");
	    }else{
	    	$this->display();
	    }
    }
    //设置头像
	public function profile_avatar(){
		global $uid;
		$this->assign("do","avatar");
		$do = R("do","string");
		//已经上传的 300 图大小
		$avatarpath = C("user")->get_avatar($uid,300);
        if($do == "save"){
            $x = intval(R("x"));
            $y = intval(R("y"));
            $w = intval(R("w"));
            $h = intval(R("h"));
            //设置目标LOGO宽度和高度
            $size1 = 150;
            $size2 = 50;
            $size3 = 30;
            $jpeg_quality = 100;
            //判断
            list($imagewidth, $imageheight, $imageType) = getimagesize($avatarpath);
            $imageType = image_type_to_mime_type($imageType);
            //Log::write($x."\t".$y."\t".$w."\t".$h."\t".$targ_w."\t".$targ_h."\t{$imageType}");
            $size1_path = C("user")->get_avatar($uid,$size1);
            $size2_path = C("user")->get_avatar($uid,$size2);
            $size3_path = C("user")->get_avatar($uid,$size3);
            switch($imageType) {
                case "image/gif":
                	$img_r=imagecreatefromgif($avatarpath);
                	//创建150*150规格图片
                    $dst_r = ImageCreateTrueColor( $size1, $size1 );
                    imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$size1,$size1,$w,$h);
                    imagegif($dst_r,$size1_path);
		   			imagedestroy($dst_r);
		   			//创建50*50规格图片
                    $dst_r = ImageCreateTrueColor( $size2, $size2 );
                    imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$size2,$size2,$w,$h);
                    imagegif($dst_r,$size2_path);
		   			imagedestroy($dst_r);
		   			//创建30*30规格图片
                    $dst_r = ImageCreateTrueColor( $size3, $size3 );
                    imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$size3,$size3,$w,$h);
                    imagegif($dst_r,$size3_path);
		   			imagedestroy($dst_r);
		   			imagedestroy($img_r); 
                    break;
                case "image/pjpeg":
                case "image/jpeg":
                case "image/jpg":
                	$img_r=imagecreatefromjpeg($avatarpath);   	
                	//创建150*150规格图片
                    $dst_r = ImageCreateTrueColor( $size1, $size1 );
                    imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$size1,$size1,$w,$h);
                    imagejpeg($dst_r,$size1_path,$jpeg_quality);
		   			imagedestroy($dst_r);
		   			//创建50*50规格图片
                    $dst_r = ImageCreateTrueColor( $size2, $size2 );
                    imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$size2,$size2,$w,$h);
                    imagejpeg($dst_r,$size2_path,$jpeg_quality);
		   			imagedestroy($dst_r);
		   			//创建30*30规格图片
                    $dst_r = ImageCreateTrueColor( $size3, $size3 );
                    imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$size3,$size3,$w,$h);
                    imagejpeg($dst_r,$size3_path,$jpeg_quality);
		   			imagedestroy($dst_r);
		   			imagedestroy($img_r); 
                    break;
                case "image/png":
                case "image/x-png":
                	$img_r=imagecreatefrompng($avatarpath);
                	//创建150*150规格图片
                    $dst_r = ImageCreateTrueColor( $size1, $size1 );
                    imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$size1,$size1,$w,$h);
                    imagepng($dst_r,$size1_path);
		   			imagedestroy($dst_r);
		   			//创建50*50规格图片
                    $dst_r = ImageCreateTrueColor( $size2, $size2 );
                    imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$size2,$size2,$w,$h);
                    imagepng($dst_r,$size2_path);
		   			imagedestroy($dst_r);
		   			//创建30*30规格图片
                    $dst_r = ImageCreateTrueColor( $size3, $size3 );
                    imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$size3,$size3,$w,$h);
                    imagepng($dst_r,$size3_path);
		   			imagedestroy($dst_r);
		   			imagedestroy($img_r); 
                    break;
            }
            M("user")->set_data(array("avatar"=>1));
            M("user")->update("uid=".$this->userinfo['uid']);
            $userinfo = $this->userinfo;
            if($userinfo){
				$userinfo["avatar"] =1;
                C("user")->set_cache_userinfo($this->userinfo['uid'],$userinfo);
			}
			//tFile::unlink(ROOT_PATH.$avatarpath);
	        tAjax::json(array("error"=>0,"message"=>"设置成功"));
            exit();
        }elseif($do == "del"){
            $avatar1 = C("user")->get_avatar($uid,150);
            $avatar2 = C("user")->get_avatar($uid,50);
            $avatar3 = C("user")->get_avatar($uid,30);
            
            M("user")->set_data(array("avatar"=>0));
            M("user")->update("uid=".$this->userinfo['uid']);
            
            $userinfo = $this->userinfo;
            if($userinfo){
				$userinfo["avatar"] =0;
                C("user")->set_cache_userinfo($this->userinfo['uid'],$userinfo);
			}
			
			tFile::unlink(ROOT_PATH.$avatarpath);
	        tFile::unlink(ROOT_PATH.$avatar1);
	        tFile::unlink(ROOT_PATH.$avatar2);
	        tFile::unlink(ROOT_PATH.$avatar3);
	        
	        tAjax::json(array("error"=>0,"message"=>"清除成功"));
        }else{
        	$noset = !file_exists(ROOT_PATH.$avatarpath)?1:0;
	        $this->assign("noset",$noset);
	        $this->assign("avatarpath",U('').$avatarpath."?".rand(1000,9999));
        	$this->display();
        }
	}
    //认证
    public function rz(){
    	global $uid;
    	$rz = array();
    	$tmp = M("rz")->query("uid=$uid");
    	if($tmp){
    		foreach($tmp as $v){
    			$rz[$v['name']] = $v;
    		}
    	}
    	$this->assign("rz",$rz);
    	$this->display();
    }
    //邮箱认证
    public function rz_email(){
    	global $uid;
    	if(tUtil::check_hash()){
    		$email = R("email","string");
    		if(empty($this->userinfo['email']) || $this->userinfo['email'] != $email){
    			$res = C("user")->check_user($email,"email","uid<>'{$this->userinfo['uid']}'");
    			if($res != ''){
    				tAjax::json_error($res);
    			}
    			$res = C("user")->update_user($this->userinfo['uid'],array(
    					"email"   => $email,
    					"emailrz" => 0,
    			));
    			if($res){//修改修改成功
    				C("user")->send_mail(array("type"=>"rz"),$uid,$email);
    			}
    			tAjax::json_success("验证邮件已经发到您的邮箱,请尽快验证!");
    		}else{
    			$ret = C("user")->send_mail(array("type"=>"rz"),$uid,$email);
    			if($ret){
    				tAjax::json_success("验证邮件已经发到您的邮箱,请尽快验证");
    			}else{
    				tAjax::json_error("验证失败！");
    			}
    		}
    	}
    }
    //手机认证
    public function rz_mobile(){
    	global $uid,$timestamp;
    	if(tUtil::check_hash()){
    		$mobile = R("mobile","string");
    		$rzcode = R("rzcode","string");
    		$type   = R("type","string");
    		$data = array(
    			"mobile" => $mobile,
    		);
    		if(empty($mobile) || !tValidate::is_mobile($mobile)){
    			tAjax::json_error("手机格式错误！");
    		}
    		if(empty($rzcode)){
    			tAjax::json_error("出错了! 认证码能为空");
    		}else{
    			if(C("sms")->chk_sms($rzcode,$data['mobile'],(($type == "modify")?"modify":"reg"))){
    				$data['mobilerz'] = 1;
    			}else{
    				tAjax::json_error("出错了! 认证码不正确");
    			}
    		}
    		if($type == "modify"){
    			$find = array(
    				"dateline" => $timestamp
    			);
    			$find['action'] = U("/ucenter/rz_mobile").tHash::uri($find);
    			$find['error']  = 0;
    			tAjax::json($find);
    		}
    		$data['mobilerz'] = 1;
    		if(empty($this->userinfo['mobile'])){ //增加手机号
    			$res = C("user")->check_user($mobile,"mobile","uid<>'{$this->userinfo['uid']}'");
    			if($res != ''){
    				tAjax::json_error($res);
    			}
    			$res = C("user")->update_user($this->userinfo['uid'],$data);
    			tAjax::json_success("提交成功!");
    		}elseif($mobile != $this->userinfo['mobile']){//修改手机号
    			$find = array(
    				"dateline" => R("dateline","int"),
    			);
    			$thash = R("thash","string");
    			if(!tHash::chk_uri($thash,$find)){
    				tAjax::json_error("非法修改请求");
    			}
    			$res = C("user")->check_user($mobile,"mobile","uid<>'{$this->userinfo['uid']}'");
    			if($res != ''){
    				tAjax::json_error($res);
    			}
    			$res = C("user")->update_user($this->userinfo['uid'],$data);
    			tAjax::json_success("修改成功!");    			
    		}else{
    			tAjax::json_error("提交失败！");
    		}
    	}
    }

    //其他认证上传
    public function rz_upload(){
    	global $uid;
    	if(tUtil::check_hash()){
    		$post_name = 'attach_file';
    		$name      = R("name","string");
    		if(!in_array($name,array('idcard','zhizhao','chengruoshu','xukezheng'))){
    			tAjax::json_error("万才网不提供此类认证！");
    		}
    		if(empty($_FILES[$post_name]) === false){    			
    			//上传路径
    			$save_dir = ROOT_PATH."www/static/";
				$file_date_path = tTime::get_datetime("Y/m/d");
				$file_store_path = $save_dir."attach/rz/".$file_date_path."/";
				$file_path = U()."attach/rz/".$file_date_path."/";
    			$return_file = "";
    			$up_obj = new tUpload(1024,array("jpg","gif","png","jpeg"));
    			$up_obj->set_dir($file_store_path);
    			$upstate = $up_obj->execute();
    			//获取用户信息
    			$is_array = null;
    			if(!isset($upstate[$post_name])){
    				tAjax::json_error("上传失败！");
    			}
    			$upfile_result = $upstate[$post_name][0];
    			if($upfile_result['flag']==1){
    				//上传成功后图片信息
    				$file_name     = $upfile_result['dir'].$upfile_result['name'].".".$upfile_result['ext'];
    				$res_file_name = $file_path.$upfile_result['name'].".".$upfile_result['ext'];
    				if($upfile_result['width']<500 || $upfile_result['height'] < 500){
    					tFile::unlink($file_name);
    					tAjax::json_error("上传图片太小!");
    				}
    				$rz = M("rz")->get_row("uid='{$uid}' AND name='{$name}'");    				
    				if(isset($rz['id'])){
    					$status = $rz['status'];
    					tFile::unlink($save_dir.$rz['path']);
    					$updata = array("path"=>$res_file_name);
    					if($rz['status'] > 1){
    						$updata['status'] = 1;//修改认证
    						$status  = 1;
    					}
    					M("rz")->set_data($updata);
    					$ret = M("rz")->update("id='{$rz['id']}'");
    				}else{
    					$status = 0;
    					$updata = array(
    						'uid'  => $uid,
    						'name' => $name,
    						"path" => $res_file_name,
    						'status' => 0,
    					);
    					M("rz")->set_data($updata);
    					$ret = M("rz")->add();
    				}
    				if($ret){
    					tAjax::respons(array("error"=>0,"path"=>U("static@/").$res_file_name,"status"=>$status),"json");
    				}else{
    					tFile::unlink($file_name);
    					tAjax::json_error("上传失败");
    				}
    			}elseif ($upfile_result['flag']== -1){
    				tAjax::json_error("上传必须为jpg,gif,png,jpeg图片");
    			}elseif ($upfile_result['flag']== -2){
    				tAjax::json_error("上传必须为jpg,gif,png,jpeg图片");
    			}
    		}
    	}
    }
    //头像上传
    public function avatar_upload(){
    	$do       = R("do","string");
    	$save_dir = ROOT_PATH."www/static/";
    	$path     = "avatar";
    	if($do == "upload"){
    		$post_name = 'avatar_file';
    		if(empty($_FILES[$post_name]) === false){    			
    			$file_avatar_path = tFun::get_avatar_path($this->userinfo['uid']);
    			$file_store_path = $save_dir."attach/{$path}/".$file_avatar_path."/";
    			$file_path = U("")."attach/{$path}/".$file_avatar_path."/";
    			$return_file = "";
    			$up_obj = new tUpload(500,array("jpg","gif","png","jpeg"));
    			$up_obj->set_dir($file_store_path);
    			$upstate = $up_obj->execute();
    			//获取用户信息
    			$is_array = null;
    			if(!isset($upstate[$post_name])){
    				tAjax::json_error("上传失败！");
    			}
    			$upfile_result = $upstate[$post_name][0];
    			if($upfile_result['flag']==1){
    				//上传成功后图片信息
    				$file_name    = $upfile_result['dir'].$upfile_result['name'].".".$upfile_result['ext'];
    				$file_to_name = $save_dir.tFun::get_avatar($this->userinfo['uid'],$path,"_tmp");
    				if($upfile_result['width']<50 || $upfile_result['height'] < 50){
    					tFile::unlink($file_name);
    					tAjax::json_error("上传图片尺寸太小!");
    				}
    				tImage::copythumb($file_name,$file_to_name,160,160,"");
    				tFile::unlink($file_name);
    				tAjax::respons(array("error"=>0,"path"=>tFun::avatar($this->userinfo['uid'],$path,"_tmp")),"json");
    			}elseif ($upfile_result['flag']== -1){
    				tAjax::json_error("上传必须为jpg,gif,png,jpeg图片");
    			}elseif ($upfile_result['flag']== -2){
    				tAjax::json_error("上传图片文件太大");
    			}
    		}
    	}elseif($do == "save"){
    		$file_name    =  $save_dir.tFun::get_avatar($this->userinfo['uid'],"avatar","_tmp");
    		$file_to_name =  $save_dir.tFun::get_avatar($this->userinfo['uid'],"avatar","");    		
    		$ret = tFile::copy($file_name,$file_to_name,"x");
    		if($ret){
    			$updata = array("avatar"=>1);
    			$ret = C("user")->update_user($this->userinfo['uid'],$updata,"",false);
    			if($ret){
    				C("user")->update_cache_userinfo($this->userinfo['uid'],$updata);
    			}
    		}
    		if($ret){
    			tAjax::json_success("保存成功");
    		}else{
    			tAjax::json_success("保存失败");
    		}
    	}elseif($do == "cancel"){
    		$file_name    =  $save_dir.tFun::get_avatar($this->userinfo['uid'],"avatar","_tmp");
    		$ret = tFile::unlink($file_name);
    		if($ret){
    			tAjax::json_success("取消成功");
    		}else{
    			tAjax::json_success("取消失败");
    		}
    	}
    }
	//LOGO上传
	public function logo_upload(){
		if(tUtil::check_hash()){
			$save_dir  = ROOT_PATH."www/static/";
			$path      = "logo";
			$post_name = 'attach_file';
			if(empty($_FILES[$post_name]) === false){
				$file_avatar_path = tFun::get_avatar_path($this->userinfo['uid']);
				$file_store_path = $save_dir."attach/{$path}/".$file_avatar_path."/";
				$file_path = U("")."attach/{$path}/".$file_avatar_path."/";
				$return_file = "";
				$up_obj = new tUpload(1024,array("jpg","gif","png","jpeg"));
				$up_obj->set_dir($file_store_path);
				$upstate = $up_obj->execute();
				//获取用户信息
				$is_array = null;
				if(!isset($upstate[$post_name])){
					tAjax::json_error("上传失败！");
				}
				$upfile_result = $upstate[$post_name][0];
				if($upfile_result['flag']==1){
					//上传成功后图片信息
					$file_name    = $upfile_result['dir'].$upfile_result['name'].".".$upfile_result['ext'];
					$file_to_name = $save_dir.tFun::get_avatar($this->userinfo['uid'],$path);
					if($upfile_result['width']<50 || $upfile_result['height'] < 50){
						tFile::unlink($file_name);
						tAjax::json_error("上传图片尺寸太小!");
					}
					tImage::copythumb($file_name,$file_to_name,150,150,"");
					tFile::unlink($file_name);
					tAjax::respons(array("error"=>0,"path"=>tFun::avatar($this->userinfo['uid'],$path,"")),"json");
				}elseif ($upfile_result['flag']== -1){
					tAjax::json_error("上传必须为jpg,gif,png,jpeg图片");
				}elseif ($upfile_result['flag']== -2){
					tAjax::json_error("上传图片文件太大");
				}
			}
		}
	}
	//----------------------------------------------------------------
	//关注
	public function follow(){
		global $user_id,$timestamp;
		$fid = R("fid","int");
		$utype = R("utype","int");
		if ($user_id <= 0 || $fid <= 0 || !in_array($utype, array('1', '2','3'))){
			tAjax::error("关注参数错误！");
		}
		if ($user_id == $fid)tAjax::error("不能关注自己!");
		if(!$fuser = M("members")->get_one("id=$fid","id")){
			tAjax::error("关注的用户不存在!");
		}
		if (M("weibo_follow")->get_one("members_id=$user_id AND fid=$fid","COUNT(*)") == 0){ // 未关注
			M("weibo_follow")->set_data(array("members_id"=>$user_id,"fid"=>$fid,"utype"=>$utype));
			if(M("weibo_follow")->add()){
				$my_userinfo = C("member")->get_cache_userinfo($user_id);
				$my_userinfo['following'] ++;
				C("member")->set_cache_userinfo($user_id,$my_userinfo);
				
				$to_userinfo = C("member")->get_cache_userinfo($fid);
				$to_userinfo['follower'] ++;
				C("member")->set_cache_userinfo($fid,$to_userinfo);
			}
		}
		$x = M("weibo_follow")->get_one("members_id={$fid} AND fid={$user_id}","id");
		tAjax::success($x?2:1);
	}
	//取消关注
	public function unfollow(){
		global $user_id,$timestamp;
		$fid = R("fid","int");
		$utype = R("utype","int");
		if ($user_id <= 0 || $fid <= 0 || !in_array($utype, array('1', '2','3'))){
			tAjax::error("关注参数错误！");
		}
		if ($user_id == $fid)tAjax::error("不能关注自己!");
		$id = M("weibo_follow")->get_one("members_id=$user_id AND fid=$fid","id");
		if ($id){ // 未关注
			M("weibo_follow")->del("id=$id");
			$my_userinfo = C("member")->get_cache_userinfo($user_id);
			$my_userinfo['following'] --;
			C("member")->set_cache_userinfo($user_id,$my_userinfo);
				
			$to_userinfo = C("member")->get_cache_userinfo($fid);
			$to_userinfo['follower'] --;
			C("member")->set_cache_userinfo($fid,$to_userinfo);
			tAjax::success(1);
		}else{
			tAjax::error("取消失败!");
		}
	}
	//获取系统通知
	public function notify_count(){
		global $user_id;
		$type = R("type","string");
		tAjax::json_success(C("notify")->get_count($user_id,$type));
	}
	//用户签到
	public function usersign(){
		global $user_id,$user_type,$timestamp;
		$do = R("do","string");
		if(empty($user_id))tAjax::respons(array("error"=>0,"issign"=>1));
		switch($do){
			default:
				tAjax::success(0);
				break;
			case "sign":
				if(M("usersign")->get_one("members_id='{$user_id}' AND yearday='".intval(date("Ymd"))."'","id")>0){
					tAjax::error("亲,您今天已经签到！");
				}else{
					$signdata = array("members_id"=>$user_id,"yearday"=>intval(date("Ymd",$timestamp)),"dateline"=>$timestamp);
					M("usersign")->set_data($signdata);
					if(M("usersign")->add()){
						C("point")->send("usersign",$user_id);
						tAjax::success(1);
					}else{
						tAjax::error("亲,签到失败！");
					}
				}
			case "init":
				if(M("usersign")->get_one("members_id='{$user_id}' AND yearday='".intval(date("Ymd"))."'","id")>0){
					tAjax::respons(array("error"=>0,"issign"=>1));
				}else{
					tAjax::respons(array("error"=>0,"issign"=>0));
				}
				break;			
		}
	}	
	//自动执行
	public function auto_exec(){
		
	}
}
?>