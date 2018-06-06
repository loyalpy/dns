<?php
class ucenter extends UC{
	public function __construct(){
		parent::__construct('ucenter');
	}
	public function index(){
		global $uid;
        $this->redirect("/finance");
	}
	
	//系统通知
	public function profile_msg(){
		global $uid;
		$do 	  = R("do","string");
		$page 	  = R("page","int");
		$page 	  = $page?$page:1;
		$pageurl  = U("/ucenter/profile_msg?do=get");
		$keyword  = R("keyword","string");
		$condition = R("condition","string");

		//查询搜索
		$where = "recieve_uid = '{$uid}'";
		$where .= " AND (title LIKE '%{$keyword}%' OR content LIKE '%{$keyword}%')";
		if($condition == 1){
			$where .= " AND status='{$condition}'";
		}elseif($condition == 2){
			$where .= " AND status=0";
		}

		//全部消息，已读消息
		$all_infor = M("sys_information")->get_one("recieve_uid={$uid}","count(*)");
		$view_infor = M("sys_information")->get_one("recieve_uid={$uid} AND status=1","count(*)");
		$this->assign("all_infor",$all_infor);
		$this->assign("view_infor",$view_infor);

		if($do == "get"){
			$data['page']  = $page;
			$data['where'] = $where;
			$data['pagesize'] = 20;
			$data['order'] = "id DESC";
			$result = M("@sys_information")->get_list($data,$pageurl);
			$result['pagebar'] = tFun::pagebar_js($result['pagebar'],$pageurl,"load_information_list");
			tAjax::json(array("error"=>0,"msg"=>"加载成功","data"=>$result));
		}
		$this->display();
	}
	//系统通知批量操作
	public function profile_msg_batch(){
		global $timestamp,$uid;
		$do = R("do","string");
		$id  = R("id","int");
		$viewdateline = R("viewdateline","string");
		switch($do){
			case "mark":	//标记为已读信息
				$res = M("sys_information")->set_data(array("status"=> 1,))->update("id='{$id}'");
				//更新浏览时间，为了区别成功时返回的状态，所以分开更新。
				M("sys_information")->set_data(array("viewdateline"=>$timestamp))->update("id='{$id}'");
				if ($res == 0 ) {
					tAjax::json_error("标记失败");
				}else{
					tAjax::json_success("标记成功");
				}
				break;
			case "del":		//删除信息
				$rst   = M("sys_information")->del("id={$id}");
				if ($rst) {
					tAjax::json_success("删除成功");
				} else {
					tAjax::json_error("删除失败");
				}
				break;
			default:
		}
	}
	//修改密码
	public function profile_passwd(){
		global $uid;
		$id = R("id","int");
		//获取用户名密码
	    if ($id > 0) {
	    	$pass = R("pass",'string');
	    	$pass2 = R("pass2",'string'); 
	    	$oldpass = R("oldpass",'string');

			if (empty($oldpass)) {
				tAjax::json_error("请输入原密码！");
			}
			if(md5($oldpass) != M("user")->get_one("uid=$uid","password")){
				tAjax::json_error("原密码错误！");
			}

			if($pass == $oldpass){
				tAjax::json_error("新密码与原密码相同！");
			}

	    	if(strlen($pass)<6 || strlen($pass)>18){
	    		tAjax::json_error("新密码为6-18位字符！");
	    	}
	    	
	    	if($pass != $pass2){
	    		tAjax::json_error("两次输入密码不一致！");
	    	}
	    	$passmd5 = md5($pass);
	    	M("user")->set_data(array("password"=>$passmd5));
	    	if(M("user")->update("uid='{$uid}'")){
//				$uid = 0;
//				C("session")->update_login("logout");
//				tCookie::destroy(array('style'));
	    		tAjax::json_success("修改成功，请重新登录!");
	    	}else{
	    		tAjax::json_error("修改失败！");
	    	}
	    }else{
	    	$this->display();
	    }
	}
	//安全中心
	public function safety_center(){
		global $uid;

		$userinfo = $this->userinfo;
		$ips = M("rz_ip")->get_row("uid='{$uid}'");
		$this->assign("userinfo",$userinfo);
		$this->assign("ips",$ips);
		$this->display();
	}
	//个人基本信息
    public function profile_basic(){
    	global $uid;
		$id = R("id","int");
		//获取用户信息
	    if ($id >0) {
	    	$data = array(
	    		"realname"   	=> R("realname","string"),
	    		"sex"        	=> R("sex","int"),
	    		"company"  => R("company","string"),
	    		"depart"      => R("depart","string"),
//	    		"email"        => R("email","string"),
	    		"signname" => R("signname","string"),
				'area'			=>R("areas","string"),
				'birthday'	=>strtotime(R("birthdays","string"))
	    	);
//			if (empty($data['uname'])) {
//				tAjax::json_error("用户名不能为空");
//			}
//	    	//检查用户名
//			$uname = $this->userinfo['uname'];
//			if ($uname != $data['uname']) {
//				$row = M("user")->get_row("uname='{$data['uname']}' AND uid<>'{$uid}' AND urole=0");
//				if (!empty($row)) {
//					tAjax::json_error("该用户名已经存在");
//				}
//			}
	    	//检查邮箱
//			$email = $this->userinfo['email'];
//			if (!tValidate::is_email($data['email'])) {
//				tAjax::json_error("邮箱格式不正确");
//			}
//	    	if($email != $data['email']){
//	    		$row = M("user")->get_row("email='{$data['email']}' AND uid<>'{$uid}'");
//	            if(!empty($row)){
//	            	tAjax::json_error("该邮箱已经存在");
//	            }
//	    	}
	    	$ret = C("user")->update_user($uid,$data);
	    	tAjax::json_success("保存成功！");
	    }else{
			$userinfo = $this->userinfo;
			$userinfo['birthday'] = explode("-",date("Y-m-d",$userinfo['birthday']));
			$userinfo['area'] = explode(",",$userinfo['area']);
			$this->assign("userinfo",$userinfo);
	    	$this->display();
	    }
    }
	//企业基本信息
	public function profile_basic_com(){
		global $uid;
		$id = R("id","int");
		//获取用户信息
		if ($id >0) {
			$data = array(
				"company_name"   	=> R("company_name","string"),
			);
			if (empty($data['company_name'])) {
				tAjax::json_error("企业名称不能为空！");
			}
			//判断是否上传证件扫描件
			if ((M("rz")->get_one("uid='{$uid}' AND name = 'shenfenzheng'","count(*)")) <= 0) {
				tAjax::json_error("请上传营业执照扫描件！");
			}
			if ((M("rz")->get_one("uid='{$uid}' AND name = 'jigou'","count(*)")) <= 0) {
				tAjax::json_error("请上传组织机构扫描件！");
			}
			
			if (M("company")->get_one("uid='{$uid}'","count(*)")) { //更改
				M("company")->set_data($data)->update("uid='{$uid}'");
				$company_id = M("company")->get_one("uid = '{$uid}'","company_id");
				M("company_ext")->set_data(array('content'=>R("com_card","string")))->update("company_id='{$company_id}'");
				tAjax::json_success("保存成功！");
			}else{	//添加
				$data['uid'] = $uid;
				$ret = M("company")->set_data($data)->add();
				if ($ret) {
					$map = array(
						'company_id'	=> $ret,
						'name'				=> "证件号码",
						'content'			=> R("com_card","string"),
					);
					if (empty($map['content'])) {
						tAjax::json_error("组织机构代码证或营业执照注册号不能为空！");
					}
					$ret = M("company_ext")->set_data($map)->add();
					if ($ret) {
						C("user")->update_user($uid,array('utype'=>2),'',true);
					}
					tAjax::json_success("添加成功！");
				}else{
					tAjax::json_error("添加失败！");
				}
			}
		}else{
			$userinfo = $this->userinfo;
			$company = M("company")->get_row("uid={$uid}");
			if (isset($company['company_id'])) {
				$com_card = M("company_ext")->get_row("company_id={$company['company_id']}");
				$this->assign("com_card",$com_card);
			}
			$rz = array();
			$tmp = M("rz")->query("uid=$uid");
			if($tmp){
				foreach($tmp as $v){
					$rz[$v['name']] = $v;
				}
			}
			$this->assign("rz",$rz);
			$this->assign("company",$company);
			$this->assign("userinfo",$userinfo);
			$this->display();
		}
	}
	//授权ip
	public function rz_ip(){
		global $uid;
		$ips = R("ips","string");
		//去除所有空格
		$ips = str_replace(array(" ","　"),"",$ips);
		//多条处理
		$search  = array("、",",","，",";",";","。","\n");
		$replace = "<br />";
		$ips    = nl2br(str_replace($search, $replace, $ips));
		$ips  = explode($replace,$ips);
		$ips  = array_filter($ips);
		$ips  = array_unique($ips);

		$data = array(
			"ips"   	=> implode(";",$ips),
		);
		foreach ($ips as $key=>$val){
			if(!tValidate::is_ip($val)){
				tAjax::json_error("错误的IP段");
			}
		}
		if (M("rz_ip")->get_one("uid='{$uid}'","count(*)")) { //更改
			if (empty($data['ips'])) {
				M("rz_ip")->del("uid = '{$uid}'");
			}else{
				M("rz_ip")->set_data($data)->update("uid = {$uid}");
			}
			tAjax::json_success("保存成功！");
		}else{	//添加
			if (empty($data['ips'])) {
				tAjax::json_success("保存成功！");
			}
			$data['uid'] = $uid;
			$ret = M("rz_ip")->set_data($data)->add();
			if ($ret) {
				tAjax::json_success("添加成功！");
			}else{
				tAjax::json_error("添加失败！");
			}
		}
	}
    //设置头像
	public function profile_avatar(){
		global $uid;
		$this->assign("do","avatar");
		$do = R("do","string");
		//已经上传的 300 图大小
		$avatarpath =tFun::avatar($uid,300);
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
	        $this->assign("avatarpath",$avatarpath."?".rand(1000,9999));
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
		global $uid,$timestamp;
    	if(tUtil::check_hash()){
			
    		$email = R("email","string");
			$email = empty($email)?$this->userinfo['email']:$email;

			//判断邮箱格式是否正确
			if (!tValidate::is_email($email)) {
				tAjax::json_error("请输入正确的邮箱格式！");
			}

    		if($this->userinfo['email'] != $email){
				//判断输入邮箱是否为已认证邮箱，唯一邮箱
    			$res = C("user")->check_user($email,"email","uid<>'{$this->userinfo['uid']}'");
    			if($res != ''){
    				tAjax::json_error($res);
    			}
    			$res = C("user")->update_user($this->userinfo['uid'],array(
    					"email"   => $email,
    					"emailrz" => 0,
    			));
    			if($res){//修改成功
    				$ret1 = C("user")->send_mail(array("type"=>"rz"),$uid,$email);
					if($ret1 && $ret1['statusCode'] == 200){
						tAjax::json_success("发送成功!");
					}else{
						tAjax::json_error("发送失败！");
					}
    			}
    			tAjax::json_success("发送成功!");
    		}else{
    			$ret2 = C("user")->send_mail(array("type"=>"rz"),$uid,$email);
    			if($ret2 && $ret2['statusCode'] == 200){
    				tAjax::json_success("发送成功!");
    			}else{
    				tAjax::json_error("发送失败！");
    			}
    		}
    	}
    }
	//发送手机验证码
	public function send_mobile(){
		global $uid,$timestamp;

		$mobile =  R("mobile","string");
		$type = R("type","string");
		$type = ($type == "modify")?"modify":"reg";

		//判断手机号码存在时，输入手机号码是否正确
		if (!empty($this->userinfo['mobile']) && $this->userinfo['mobile'] != $mobile && $type == "reg") {
			tAjax::json_error("手机号码错误，请填写注册时手机号码！");
		}

		//判断输入手机号码是否为已认证手机号码，唯一号码
		$row = C("user")->check_user($mobile,"mobile","uid<>'{$this->userinfo['uid']}'");
		if($row != ''){
			tAjax::json_error($row);
		}

		$data = array("type"=>$type);
		$res = C("user")->send_sms($data,$mobile,$uid);
		if ($res) {
			tAjax::json_success("ok");
		}else{
			tAjax::json_error("发送验证码失败！");
		}
	}
    //手机认证
    public function rz_mobile(){
    	global $uid,$timestamp;
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
			tAjax::json_error("出错了! 认证码不能为空");
		}else{
			if(C("sms")->chk_sms($rzcode,$data['mobile'],(($type == "modify")?"modify":"reg"))){
				$data['mobilerz'] = 1;
			}else{
				tAjax::json_error("出错了! 认证码不正确");
			}
		}
		$data['mobilerz'] = 1;
		if(empty($this->userinfo['mobile'])){ //增加手机号
			$res = C("user")->check_user($mobile,"mobile","uid<>'{$this->userinfo['uid']}'");
			if($res != ''){
				tAjax::json_error($res);
			}
			C("user")->update_user($this->userinfo['uid'],$data);
			tAjax::json_success("验证成功!");
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
			C("user")->update_user($this->userinfo['uid'],$data);
			tAjax::json_success("验证成功!");
		}else{
			C("user")->update_user($this->userinfo['uid'],$data);
			tAjax::json_success("验证成功！");
		}
    }

    //其他认证上传
    public function rz_upload(){
    	global $uid;
    	if(tUtil::check_hash()){
    		$post_name = 'attach_file';
    		$name      = R("name","string");
    		if(!in_array($name,array('shenfenzheng','jigou'))){
    			tAjax::json_error("八戒DNS不提供此类认证！");
    		}
    		if(empty($_FILES[$post_name]) === false){    			
    			//上传路径
    			$save_dir = ROOT_PATH."www/static/";
				$file_date_path = tTime::get_datetime("Y/m/d");
				$file_store_path = $save_dir."attach/rz/".$file_date_path."/";
				$file_path = U()."attach/rz/".$file_date_path."/";
    			$return_file = "";
    			$up_obj = new tUpload(2048,array("jpg","gif","png","jpeg"));
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
    					tAjax::json(array("error"=>0,"path"=>U("static@/").$res_file_name,"status"=>$status));
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
	//企业资料上传
	public function logo_upload(){
		$save_dir  		= ROOT_PATH."www/static/";
		if(tUtil::check_hash()) {
			$path = "logo";
			$post_name = 'attach_file';
			if (empty($_FILES[$post_name]) === false) {
				$file_avatar_path = tFun::get_avatar_path($this->userinfo['uid']);
				$file_store_path = $save_dir . "attach/{$path}/" . $file_avatar_path . "/";
				$file_path = U("") . "attach/{$path}/" . $file_avatar_path . "/";
				$return_file = "";
				$up_obj = new tUpload(1024, array("jpg", "gif", "png", "jpeg"));
				$up_obj->set_dir($file_store_path);
				$upstate = $up_obj->execute();

				//获取用户信息
				$is_array = null;
				if (!isset($upstate[$post_name])) {
					tAjax::json_error("上传失败！");
				}
				$upfile_result = $upstate[$post_name][0];
				if ($upfile_result['flag'] == 1) {
					//上传成功后图片信息
					$file_name = $upfile_result['dir'] . $upfile_result['name'] . "." . $upfile_result['ext'];
					$file_to_name = $save_dir . tFun::get_avatar($this->userinfo['uid'], $path);
					if ($upfile_result['width'] < 50 || $upfile_result['height'] < 50) {
						tFile::unlink($file_name);
						tAjax::json_error("上传图片尺寸太小!");
					}
					tImage::copythumb($file_name, $file_to_name, 150, 150, "");
					tFile::unlink($file_name);
					tAjax::respons(array("error" => 0, "path" => tFun::avatar($this->userinfo['uid'], $path, "")), "json");
				} elseif ($upfile_result['flag'] == -1) {
					tAjax::json_error("上传必须为jpg,gif,png,jpeg图片");
				} elseif ($upfile_result['flag'] == -2) {
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
    
    //微信绑定
    public function safety_center_wx(){

    }
	//绑定微信生成二维码
	public function qrcode(){
		global $uid;
		echo SDKwx::qrcode($uid);
	}
	//成功绑定信息
	public function wxbd(){
		global $uid;
		$do = R("do","string");
		if($do === "success"){
			$this->_msg("绑定微信成功！","您已成功绑定我们的官方微信","/ucenter/safety_center#返回安全中心","success");
		}elseif($do === "check"){
			$res = M("@user_bd")->get_wxbd($uid);
			if($res['status'] == 2){
				tAjax::json_success("已绑定");
			}else{
				tAjax::json_error("未绑定");
			}
		}elseif($do === "unbd"){
			$psw = R("password","string");
			if (empty($psw)) {
				tAjax::json_error("密码不能为空");
			}
			//验证账户登录密码是否正确
			if ($this->userinfo['password'] != md5($psw)) {
				tAjax::json_error("密码错误");
			}
            $ret = M("@user_bd")->unwxbd($uid);
            if($ret){
            	tAjax::json_success("解除绑定成功");            	
            }else{
            	tAjax::json_error("解除绑定失败");
            }

		}else{
			$res = M("@user_bd")->get_wxbd($uid);
			if($res['status'] == 2){
				$this->_msg("您已绑定微信！","您已成功绑定我们的官方微信","/ucenter/safety_center#返回安全中心","success");
			}
			$this->display();
		}
	}
}
?>