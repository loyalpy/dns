<?php
class tUpload{
	//允许上传附件类型
	private $allow_type = array('jpg','gif','png','zip','rar','docx','doc','ppt','tar.gz','xls/doc');
	//附件存放物理目录
	private $dir = 'attach/';
	//最大允许文件大小，单位为B(字节)
    private $maxsize;
    /**
     * @brief 构造函数
     * @param Int   $size 允许最大上传KB数
     * @param Array $type 允许上传的类型
     */
    function __construct($size = 2048,$type = array()){
    	//设置附件上传类型
    	if(!empty($type)){
    		$this->allow_type = $type;
    	}
    	//设置附件上传最大值
    	//上传最大尺寸
	    $upload_max = tFile::get_byte_value(ini_get('upload_max_filesize'));
	    //POST最大尺寸
	    $post_max = tFile::get_byte_value(ini_get('post_max_size'));
	    //系统设置的尺寸  
	    $settings_max = tFile::get_byte_value("{$size}k");
	    //取最小
	    $max_file_size_byte = min($upload_max, $post_max);
	    $max_file_size_byte = min($max_file_size_byte,$settings_max);
	    
	    $this->maxsize = $max_file_size_byte;
    }
    /**
     * @brief 获取最大尺寸
     */
    public function get_maxsize(){
    	return $this->maxsize;
    }
    /**
     * @brief 设置上传文件存放目录
     * @param String $dir 文件存放目录
     */
    function set_dir($dir){
    	if($dir != '' && !is_dir($dir)){
    		tFile::mkdir($dir);
    	}
    	$dir       = strtr($dir,'\\','/');
    	$this->dir = substr($dir,-1)=='/' ? $dir : $dir.'/';
    }
    /**
     * @brief  开始执行上传
     * @return array 包含上传成功信息的数组
     *		$file = array(
	 *			 name    如果上传成功，则返回上传后的文件名称，如果失败，则返回客户端名称
	 *			 size    上传附件大小
	 *           filesrc 上传文件完整路径
	 *			 dir     上传目录
	 *			 ininame 上传图片名
	 *			 flag    -1:文件类型不允许; -2:文件大小超出限制; 1:上传成功
	 *			 ext     上传附件扩展名
     *		);
     */
    public function execute(){
    	//总的文件上传信息
    	$info = array();
        foreach($_FILES as $field => $file){
            $fileinfo = array();
			//不存在上传的文件名
            if(!isset($_FILES[$field]['name']) || $_FILES[$field]['name'] == '' || !isset($_FILES[$field]['tmp_name'])){
            	continue;
            }
			//上传控件为数组格式 file[]格式
            if(is_array($_FILES[$field]['name'])){
                $keys = array_keys($_FILES[$field]['name']);
                foreach($keys as $key){
                    if(!isset($_FILES[$field]['name'][$key]) || $_FILES[$field]['name'][$key] == ''){
                        continue;
                    }
                    //获取扩展名
                    $fileext = tFile::get_filetype($_FILES[$field]['tmp_name'][$key]);
                    if(is_array($fileext) || $fileext == "xls/doc" || $fileext == null){
                        $fileext = tFile::get_ext($_FILES[$field]['name'][$key]);
                    }

					/*开始上传文件*/
                    //(1)上传类型不符合
                    if(!in_array($fileext,$this->allow_type)){
                        $fileinfo[$key]['name'] = $_FILES[$field]['name'][$key];
                        $fileinfo[$key]['flag'] = -1;
                    }
                    //(2)上传大小不符合
                    else if($_FILES[$field]['size'][$key] > $this->maxsize){
                        $fileinfo[$key]['name'] = $_FILES[$field]['name'][$key];
                        $fileinfo[$key]['flag'] = -2;
                    }
					//(3)成功情况
                    else{
	                    //修改图片状态值
	                    $fileinfo[$key]['name']     = tTime::get_datetime('Ymdhis').mt_rand(100,999);
	                    $fileinfo[$key]['dir']      = $this->dir;
	                    $fileinfo[$key]['size']     = $_FILES[$field]['size'][$key];
	                    $fileinfo[$key]['ininame']  = tUtil::filter($_FILES[$field]['name'][$key]);
	                    $fileinfo[$key]['ext']      = $fileext;
	                    $fileinfo[$key]['filesrc']  = $fileinfo[$key]['dir'].$fileinfo[$key]['name'];
	                    $fileinfo[$key]['is_image'] = 0;
	                    $fileinfo[$key]['width']    = 0;
	                    $fileinfo[$key]['height']   = 0;
	                    $fileinfo[$key]['flag']     = 0;
                        
	                    if(is_uploaded_file($_FILES[$field]['tmp_name'][$key])){
	                        if(move_uploaded_file($_FILES[$field]['tmp_name'][$key],$this->dir.$fileinfo[$key]['name'].".".$fileext)){
	                            if(file_exists($_FILES[$field]['tmp_name'][$key])){
	                                tFile::unlink($_FILES[$field]['tmp_name'][$key]);
	                            }
	                            $fileinfo[$key]['flag'] = 1;
	                            if(in_array($fileext,array("gif","jpg","jpeg","png","tmp"))){
		                        	list($img_width,$img_height) = getimagesize($this->dir.$fileinfo[$key]['name'].".".$fileext);
		                        	$fileinfo[$key]['width'] = $img_width;
		                        	$fileinfo[$key]['height'] = $img_height;
		                        	$fileinfo[$key]['is_image'] = 1;
		                        }
	                        }
	                    }
                    }
                }
            }else{
                if($_FILES[$field]['name'] == '' || $_FILES[$field]['tmp_name'] == ''){
                    continue;
                }
                //获取扩展名
                $fileext = tFile::get_filetype($_FILES[$field]['tmp_name']);
                if(is_array($fileext) || $fileext == "xls/doc" || $fileext == null){
                    $fileext = tFile::get_ext($_FILES[$field]['name']);
                }
                //tAjax::respons(array("error"=>1,"message"=>$fileext),"json");
                /*开始上传文件*/
                //(1)上传类型不符合
                if(!in_array($fileext,$this->allow_type)){
                    $fileinfo[0]['name'] = $_FILES[$field]['name'];
                    $fileinfo[0]['flag'] = -1;
                }
                //(2)上传大小不符合
                else if($_FILES[$field]['size'] > $this->maxsize){
                    $fileinfo[0]['name'] = $_FILES[$field]['name'];
                    $fileinfo[0]['flag'] = -2;
                }
				//(3)成功情况
                else{
	                //修改图片状态值
	                $fileinfo[0]['name']        = tTime::get_datetime('Ymdhis').mt_rand(100,999);
	                $fileinfo[0]['dir']         = $this->dir;
	                $fileinfo[0]['size']        = $_FILES[$field]['size'];
	                $fileinfo[0]['ininame']     = tUtil::filter($_FILES[$field]['name']);
	                $fileinfo[0]['ext']         = $fileext;
	                $fileinfo[0]['fileSrc']     = $fileinfo[0]['dir'].$fileinfo[0]['name'];
	                $fileinfo[0]['is_image']    = 0;
	                $fileinfo[0]['width']       = 0;
	                $fileinfo[0]['height']      = 0;
	                $fileinfo[0]['flag']        = 0;

	                if(is_uploaded_file($_FILES[$field]['tmp_name'])){
	                    if(move_uploaded_file($_FILES[$field]['tmp_name'],$this->dir.$fileinfo[0]['name'].".".$fileext)){
	                        if(file_exists($_FILES[$field]['tmp_name'])){
	                            tFile::unlink($_FILES[$field]['tmp_name']);
	                        }
	                        $fileinfo[0]['flag'] = 1;
	                        if(in_array($fileext,array("gif","jpg","jpeg","png"))){
	                        	list($img_width,$img_height) = getimagesize($this->dir.$fileinfo[0]['name'].".".$fileext);
	                        	$fileinfo[0]['width'] = $img_width;
	                        	$fileinfo[0]['height'] = $img_height;
	                        	$fileinfo[0]['is_image'] = 1;
	                        }
	                    }
	                }
                }
            }
            $info[$field]=$fileinfo;
        }
        return $info;
    }
}
?>