<?php
class tImage{
	/**
	 * @brief 构造函数
	 * @param string $fileName 要处理的图片文件名称
	 */
	function __construct($file_name){

	}
	/**
	 * @brief 创建图片资源句柄
	 * @param string $fileName 图片文件名称
	 * @return resource 图片资源句柄; null:无匹配类型
	 */
	public static function create_image_resource($file_name){
		$image_res = null;
		//获取文件扩展名
		$file_ext  = tFile::get_ext($file_name);
	    switch($file_ext){
	        case 'jpg' :
	        case 'jpeg':
	        {
	        	$image_res = imagecreatefromjpeg($file_name);
	        }
	        break;

	        case 'gif' :
	        {
	        	$image_res = imagecreatefromgif($file_name);
	        }
	        break;

	        case 'png' :
	        {
	        	$image_res = imagecreatefrompng($file_name);
	        }
	        break;

	        case 'bmp' :
	        {
				$image_res = imagecreatefromwbmp($file_name);
	        }
	        break;
	    }
	    return $image_res;
	}
	/**
	 * @brief 生成图片文件
	 * @param resource $imageRes      图片资源名称
	 * @param string   $thumbFileName 缩略图名称
	 * @param bool     $imageResult   生成缩略图状态 true:成功; false:失败;
	 */
	public static function create_imagefile($image_res,$thumb_filename){
		//如果目录不可写直接返回，防止错误抛出
		if(!is_writeable(dirname($thumb_filename))){
			return false;
		}
		$image_result = false;
		//获取文件扩展名
		$file_ext = tFile::get_ext($thumb_filename);
	    switch($file_ext){
	        case 'jpg' :
	        case 'jpeg':
	        {
	        	$image_result = imagejpeg($image_res,$thumb_filename,100);
	        }
	        break;
	        case 'gif' :
	        {
	        	$image_result = imagegif($image_res,$thumb_filename);
	        }
	        break;

	        case 'png' :
	        {
	        	$image_result = imagepng($image_res,$thumb_filename);
	        }
	        break;

	        case 'bmp' :
	        {
				$image_result = imagewbmp($image_res,$thumb_filename);
	        }
	        break;
	    }
	    return $image_result;
	}
	/**
	 * @brief 生成缩略图
	 * @param string  $fileName 生成缩略图的目标文件名
	 * @param int     $width    缩略图的宽度
	 * @param int     $height   缩略图的高度
	 * @param string  $ExtName  缩略图文件名附加值
	 * @return string 缩略图文件名
	 */
	public static function thumb($file_name, $width = 200, $height = 200 ,$ext_name = '_thumb'){
		if(is_file($file_name)){
			//获取原图信息
			list($img_width,$img_height) = getimagesize($file_name);
			//计算宽高比例,获取缩略图的宽度和高度
		    if($img_width >= $img_height){
		    	$thumb_width  = $width;
		    	$thumb_height = ($width / $img_width) * $img_height;
		    }else{
		    	$thumb_width  = ($height / $img_height) * $img_width;
		        $thumb_height = $height;
		    }
			//生成$fileName文件图片资源
		    $thumb_res = self::create_image_resource($file_name);
	        $thumb_box = imageCreateTrueColor($width,$height);
	        //填充补白
			$pad_color = imagecolorallocate($thumb_box,255,255,255);
        	imagefilledrectangle($thumb_box,0,0,$width,$height,$pad_color);
			//拷贝图像
	        imagecopyresampled($thumb_box, $thumb_res, ($width-$thumb_width)/2, ($height-$thumb_height)/2, 0, 0, $thumb_width, $thumb_height, $img_width, $img_height);
	        //生成缩略图文件名
	        $file_ext       = tFile::get_ext($file_name);
	        $thumb_filename = str_replace('.'.$file_ext,$ext_name.'.'.$file_ext,$file_name);
			//生成图片文件
	        $result = self::create_imagefile($thumb_box,$thumb_filename);
	        if($result == true){
	        	return $thumb_filename;
	        }else{
	        	return null;
	        }
		}else{
			return null;
		}
	}
	/**
	 * @brief 生成图片文件
	 * 
	 */
	public static function fixthumb($file_name, $width = 196, $max_height = 120 ,$ext_name = '_thumb',$force=true){
		if(is_file($file_name)){
			//获取原图信息
			$file_ext       = tFile::get_ext($file_name);
			list($img_width,$img_height) = getimagesize($file_name);
			//切图类型 $cut_type=0 则切宽度   $cut_type=1 则切高度
			$cut_type = (($width/$max_height)>($img_width/$img_height))?1:0;
			$cut_type = $force?1:$cut_type;
			//确定裁剪图片的宽度和高度
			if($cut_type == 1){
				//计算宽高比例,获取缩略图的宽度和高度
				$thumb_width = min($width,$img_width);
			    if($img_width < $width){
			    	$thumb_height = min($max_height,$img_height);
			    	if($img_height < $max_height){
				    	return false;
				    }
			    }else{
			    	$thumb_height = min($max_height,($img_height*$thumb_width)/$img_width);
			    }
			    $cut_width = $img_width;
			    $cut_height = intval(($img_width*$thumb_height)/$thumb_width);
			    $sx = 0;
			    $sy = ($img_height-$cut_height)/2;
			}elseif($cut_type == 0){
				//计算宽高比例,获取缩略图的宽度和高度
				$thumb_height = min($max_height,$img_height);
				if($img_height < $thumb_height && $img_width < $width){
				    return false;
			    }
			    $thumb_width = min($width,$img_width);
			    $cut_width = intval(($img_height*$thumb_width)/$thumb_height);//$img_width;
			    $cut_height = $img_height;
			    
			    $sx = ($img_width-$cut_width)/2;
			    $sy = 0;
			}
		   
			//生成$fileName文件图片资源
		    $thumb_res = self::create_image_resource($file_name);
	        $thumb_box = imageCreateTrueColor($thumb_width,$thumb_height);
	        //填充补白
			$pad_color = imagecolorallocate($thumb_box,255,255,255);
        	imagefilledrectangle($thumb_box,0,0,$thumb_width,$thumb_height,$pad_color);
			//拷贝图像
			
	        imagecopyresampled($thumb_box, $thumb_res, 0, 0, $sx, $sy, $thumb_width, $thumb_height, $cut_width, $cut_height);
	        //生成缩略图文件名
	        $thumb_filename = str_replace('.'.$file_ext,$ext_name.'.'.$file_ext,$file_name);
			//生成图片文件
	        $result = self::create_imagefile($thumb_box,$thumb_filename);
	        if($result == true){
	        	return true;
	        }else{
	        	return false;
	        }
		}else{
			return false;
		}
	}
	/**
	 * @brief 完全拷贝图片文件
	 * 
	 */
	public static function copythumb($file_name, $to_file_name,$width = 196, $height = 120 ){
		if(is_file($file_name)){
			//获取原图信息
			$file_ext       = tFile::get_ext($file_name);
			list($img_width,$img_height) = getimagesize($file_name);
			//生成$fileName文件图片资源
		    $thumb_res = self::create_image_resource($file_name);
	        $thumb_box = imageCreateTrueColor($width,$height);
			//拷贝图像
	        imagecopyresampled($thumb_box, $thumb_res, 0, 0, 0, 0, $width, $height, $img_width, $img_height);
			//生成图片文件
	        $result = self::create_imagefile($thumb_box,$to_file_name);
	        if($result == true){
	        	return true;
	        }else{
	        	return false;
	        }
		}else{
			return false;
		}
	}
}

?>
