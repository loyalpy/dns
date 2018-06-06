//文件队列时候调用
function fileQueued(file) {
	try {
		var upload_btn = document.getElementById('upload_btn');
		if(this.getStats().files_queued >0){
			upload_btn.disabled = false;	
		}
		var progress = new FileProgress(file, this.customSettings.uploadprogressbar);
		progress.toggleCancelFile(true,this);
	}catch(ex){
		this.debug(ex);
	}

}
//对话框完成时候调用
function fileDialogComplete() {
	var upload_btn = document.getElementById('upload_btn');
	upload_btn.onclick = doSubmit;
}
//上传按钮调用
function doSubmit(){
	upl.startUpload();	
	document.getElementById('upload_btn').disabled = true;
}
//单个文件上传开始调用
function uploadStart(file) {
	try {
		var desc = document.getElementById('desc'+file.id).value;
		var cid = document.getElementById('cate'+file.id).value;
		if(cid == 0)
		{
			cid = document.getElementById("sel_user_category").value;
		}
		upl.addPostParam("desc["+file.id+"]", desc);
		upl.addPostParam("cate["+file.id+"]", cid);
	}catch (ex) {
		this.debug(ex);
	}
	
}
//单个文件上传进度调用
function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
		var progress = new FileProgress(file, this.customSettings.uploadprogressbar);
		progress.setProgress(percent);
		progress.setStatus("已上传："+ percent +"%  "+"上传速度："+ SWFUpload.speed.formatBPS(file.currentSpeed));
		progress.toggleCancel(true, this);
	} catch (ex) {
		this.debug(ex);
	}
	
}
//上传成功调用
function uploadSuccess(file, serverData) {
	try {

	} catch (ex) {
		this.debug(ex);
	}
}
//上传完成调用
function uploadComplete(file) {
	var progress = new FileProgress(file, this.customSettings.uploadprogressbar);
	//progress.setTimer(setTimeout(function(){
//		progress.disappear();
//	}, 1000));
	progress.setStatus("<span class=\"txt-blue\">上传成功！</span>");
	document.getElementById('upload_btn').disabled = true;
	upl.removePostParam("desc["+file.id+"]");
	upl.removePostParam("cate["+file.id+"]");
	if(this.getStats().files_queued ==0){
		document.getElementById('up_msg').innerHTML ="操作成功，请关闭此上传对话框进行其他操作。";
		document.getElementById('up_msg_tips').style.display = '';
		setTimeout(function(){self.parent.hs.close();self.parent.document.location.replace("index.php?inc=document,my&do=list&cid="+document.getElementById("sel_user_category").value);},1000);
	}
}
//文件队列出错调用
function fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("同一次上传超过最大的文件数。");
			return;
		}

		var progress = new FileProgress(file, this.customSettings.uploadprogressbar);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			progress.setStatus("当前文件大小："+SWFUpload.speed.formatBytes(file.size)+", "+"超过了单个文件的最大限制");
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			progress.setStatus("零字节文件，系统自动删除。");
			break;
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
			progress.setStatus("同一次上传超过最大的文件数。");
			break;
		default:
			if (file !== null) {
				progress.setStatus("未知错误"+"\n Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + SWFUpload.speed.formatBytes(file.size) + ", Message: " + message);
			}
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}
