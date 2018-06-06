var swfupload_event = {
	//文件队列
	fileQueued:function(file){
		try {
			if(this.getStats().files_queued >0){
				this.startUpload();
			}
			file.loaded = 0;
			var filebox = new swfupload_filebox(file, this.customSettings);
			filebox.cacel_upload(this);
		}catch(ex){
			this.debug(ex);
		}
	},
	//文件队列完成
	fileDialogComplete:function(file){},
	//文件队列出错
	fileQueueError:function(file, errorCode, message){
		try {
			if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
				alert("同一次上传超过最大的文件数。");
				return;
			}
			var filebox = new swfupload_filebox(file, this.customSettings);
			switch (errorCode) {
				case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
					filebox.set_status("<span class='error'>文件大小："+SWFUpload.speed.formatBytes(file.size)+", "+"超过限制</span>");
					break;
				case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
					filebox.set_status("<span class='error'>零字节文件，系统自动删除。</span>");
					break;
				case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
					filebox.set_status("<span class='error'>同一次上传超过最大的文件数。</span>");
					break;
				default:
					if (file !== null) {
						filebox.set_status("<span class='error'>未知错误"+"\n Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + parseInt(file.size/1024) + "K, Message: " + message + "</span>");
					}
					break;
			}
		} catch (ex) {
	        this.debug(ex);
	    }
	},
	//单个上传开始
	uploadStart:function(file){
		try {
		}catch (ex) {
			this.debug(ex);
		}
	},
	//单个上传进度
	uploadProgress:function(file, bytesLoaded, bytesTotal) {
		try {
			var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
			var filebox = new swfupload_filebox(file, this.customSettings);
			filebox.set_progress(percent);
		} catch (ex) {
			this.debug(ex);
		}
	},
	//单个上传成功
	uploadSuccess:function(file, server_data){
		try {
			var filebox = new swfupload_filebox(file, this.customSettings);
			var ophtml = "";
	        eval("res="+server_data);
	        if(res.error == 1){
	        	filebox.set_status('该文件已经存在');
	        	setTimeout(function(){
	        		filebox.hidden();
	        	},300);
	        }
	        var tpl_v = {
	        	id:res.id,
	        	imgpath:res.file.replace("SIZE","50_50"),
	        	name:"",
	        	desc:res.desc      	
	        }
	        filebox.obj.parent().html(""+easyTemplate(filebox.tpl,tpl_v));
	        filebox.file_id = res.id;//重新定义文件ID
	        filebox.loaded = 1; //状态更正为已经上传
	        filebox.obj = filebox.box.find(".attach_"+filebox.file_id);//重新获取该对象
	        filebox.init();//定义事件
	        
	        filebox.obj.parent().find('.copyclip').zclip({
			       path: U("/common/javascript/jquery.zclip/ZeroClipboard.swf"),
			       copy: function(){
			          var obj = this;
			          return $(obj).attr("clip");
			  　　　 }
			});
		} catch (ex) {
			this.debug(ex);
		}
		
	},
	//单个上传完成
	uploadComplete:function(){
		var stats = this.getStats();
		if (stats.files_queued > 0){
			this.startUpload();
		}
	},
	//退出上传列表
}
//swfupload_filebox
var swfupload_filebox = function(file,setting){
	var $that = this;
	$that.setting = setting;
	$that.box = $("#"+$that.setting.filebox);
	$that.tpl = $that.box.parent().find("script.tpl").html();
	$that.file_id = file.id;//文件ID
	$that.loaded = file.loaded; //是否是已经上传
	$that.obj = $that.box.find(".attach_"+$that.file_id);//上传的对象
	
	if(!$that.obj.get(0)){
		if(typeof file.desc == "undefined"){
			file.desc = "";
		}
		$that.box.append("<div class=\"swfitem\">"+easyTemplate($that.tpl,file)+"</div>");
		$that.box.find(".xclearfix").remove();
		$that.box.append("<div class='xclearfix clearfix'></div>");
		$that.obj = $that.box.find(".attach_"+$that.file_id);
		//初始事件
		$that.init();
	}
}
swfupload_filebox.prototype.init = function(){
	var $that = this;
	//设置封面
	$that.set_fm();
	//设置插入
	$that.insert_attach();
	//设置删除
	$that.delete_attach();
}
//设置封面
swfupload_filebox.prototype.set_fm = function(){
	var $that = this;
	var file_id = parseInt($that.file_id);
	if($that.loaded == 1 && file_id > 0 && $that.setting.inp_fm_id){
		if($that.setting.fm_id == file_id){
			$that.obj.parent().addClass("active");
		}
		$that.obj.find("button.setfm").click(function(){
			var id = parseInt($("input[name='"+$that.setting.inp_fm_id+"']").val());
			if(id == file_id){
				$("input[name='"+$that.setting.inp_fm_id+"']").val(0);
				$("input[name='"+$that.setting.inp_fm+"']").val("");
				
				$that.box.find("div.active").removeClass("active");
			}else{
				$("input[name='"+$that.setting.inp_fm_id+"']").val(file_id);
				$("input[name='"+$that.setting.inp_fm+"']").val($that.obj.find("img.purview").attr("src").replace("50-50","SIZE"));
				
				$that.box.find("div.active").removeClass("active");
				$that.obj.parent().addClass("active")
			}
		});
	}
}
//插入到编辑框
swfupload_filebox.prototype.insert_attach = function(){
	var $that = this;
	var file_id = parseInt($that.file_id);
	if($that.loaded == 1 && file_id > 0){
	     $that.obj.find("button.insert").click(function(){
              $that.setting.fileinsert(file_id);
		});
	}
}
//删除附件
swfupload_filebox.prototype.delete_attach = function(){
	var $that = this;
	var file_id = parseInt($that.file_id);
	if($that.loaded == 1 && file_id > 0){
	     $that.obj.find("button.delete").click(function(){
			$.ajaxPassport({url:$that.setting.delete_url,"success":function(res){
				if(res.error == 0){
					$.tips(res.message,"success");
					$that.hidden();
				}else{
					$.tips(res.message,"danger");
				}
			},"data":{"attach_id":$that.file_id},"loading_txt":"正在删除附件……"});
		});
	}
}
//取消上传
swfupload_filebox.prototype.cacel_upload = function(swfUploadInstance){
	if (swfUploadInstance) {
		var $that = this;
		var file_id = $that.file_id;
		//取消上传
		$that.obj.find("button.delete").click(function(){
			swfUploadInstance.cancelUpload(file_id);
			$that.hidden();
			return false;
		});
	}
}
//隐藏
swfupload_filebox.prototype.hidden = function(){
	var $that = this;
	$that.obj.parent().fadeOut("fast");
}
//上传进度显示
swfupload_filebox.prototype.set_progress = function(percent){
	var $that = this;
	if(percent>=100){
		$that.obj.find("img.purview").before("<span class=\"loading\"></span>");
	}
}
//上传状态提示
swfupload_filebox.prototype.set_status = function(txt){
	var $that = this;
	if(!$that.obj.find("span.status_tip").get(0)){
		$that.obj.append("<span class='status_tip'></span>");
	}
	$that.obj.find("span.status_tip").html(txt).fadeIn("fast");
	setTimeout(function(){
		$that.obj.find("span.status_tip").fadeOut("fast");
	},3000);
}