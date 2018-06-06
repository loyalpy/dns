//�ļ�����ʱ�����
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
//�Ի������ʱ�����
function fileDialogComplete() {
	var upload_btn = document.getElementById('upload_btn');
	upload_btn.onclick = doSubmit;
}
//�ϴ���ť����
function doSubmit(){
	upl.startUpload();	
	document.getElementById('upload_btn').disabled = true;
}
//�����ļ��ϴ���ʼ����
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
//�����ļ��ϴ����ȵ���
function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
		var progress = new FileProgress(file, this.customSettings.uploadprogressbar);
		progress.setProgress(percent);
		progress.setStatus("���ϴ���"+ percent +"%  "+"�ϴ��ٶȣ�"+ SWFUpload.speed.formatBPS(file.currentSpeed));
		progress.toggleCancel(true, this);
	} catch (ex) {
		this.debug(ex);
	}
	
}
//�ϴ��ɹ�����
function uploadSuccess(file, serverData) {
	try {

	} catch (ex) {
		this.debug(ex);
	}
}
//�ϴ���ɵ���
function uploadComplete(file) {
	var progress = new FileProgress(file, this.customSettings.uploadprogressbar);
	//progress.setTimer(setTimeout(function(){
//		progress.disappear();
//	}, 1000));
	progress.setStatus("<span class=\"txt-blue\">�ϴ��ɹ���</span>");
	document.getElementById('upload_btn').disabled = true;
	upl.removePostParam("desc["+file.id+"]");
	upl.removePostParam("cate["+file.id+"]");
	if(this.getStats().files_queued ==0){
		document.getElementById('up_msg').innerHTML ="�����ɹ�����رմ��ϴ��Ի����������������";
		document.getElementById('up_msg_tips').style.display = '';
		setTimeout(function(){self.parent.hs.close();self.parent.document.location.replace("index.php?inc=document,my&do=list&cid="+document.getElementById("sel_user_category").value);},1000);
	}
}
//�ļ����г������
function fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("ͬһ���ϴ����������ļ�����");
			return;
		}

		var progress = new FileProgress(file, this.customSettings.uploadprogressbar);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			progress.setStatus("��ǰ�ļ���С��"+SWFUpload.speed.formatBytes(file.size)+", "+"�����˵����ļ����������");
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			progress.setStatus("���ֽ��ļ���ϵͳ�Զ�ɾ����");
			break;
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
			progress.setStatus("ͬһ���ϴ����������ļ�����");
			break;
		default:
			if (file !== null) {
				progress.setStatus("δ֪����"+"\n Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + SWFUpload.speed.formatBytes(file.size) + ", Message: " + message);
			}
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}
