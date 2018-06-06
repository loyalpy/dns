//文件队列时候调用
function fileQueued(file) {
	try {
		if(this.getStats().files_queued >0){
			upl.startUpload();
		}
		var progress = new FileProgress(file, this.customSettings.uploadprogressbar);
		progress.toggleCancelFile(true,this);
	}catch(ex){
		this.debug(ex);
	}
}
//对话框完成时候调用
function fileDialogComplete() {
}
//上传按钮调用
function doSubmit(){
	upl.startUpload();
}
//单个文件上传开始调用
function uploadStart(file) {
	try {
		//var desc = document.getElementById('desc'+file.id).value;
		//upl.addPostParam("desc["+file.id+"]", desc);
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
		progress.setStatus("<span>已上传："+ percent +"%</span>");// "+"上传速度："+ SWFUpload.speed.formatBPS(file.currentSpeed));
		progress.toggleCancel(true, this);
	} catch (ex) {
		this.debug(ex);
	}
}
//上传成功调用
function uploadSuccess(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.uploadprogressbar);
		var ophtml = "";
		//alert(serverData);
        eval("res="+serverData);
        if(res.error == 1){
        	progress.setStatus('该文件已经存在');
        	setTimeout('$("#'+file.id+'").empty().remove()',300);
        }
        $("#"+file.id).addClass("attach_"+res.id);
        $("#"+file.id).find("textarea.description").attr("name","attach_ids["+res.id+"]").val(res.description);
        $("#"+file.id).find("a.progressCancel").click(
        function(){
        	myattach.delsquare(this,""+res.id);
        });
        //$("#"+file.id).find("img.purview").attr("src",res.file_path);
        var image = new Image();
                image.src = res.file_path;
                image.onreadystatechange=function(){
			    if (image.readyState=="complete") {
			 		$("#"+file.id).find("img.purview").attr("src",res.file_path);
			 		editor_insert_attach(res.id);
			 		$("#"+file.id).append('<div class="ops">&nbsp;&nbsp;<a href="javascript:void(0);" onclick="myattach.setfm('+res.id+');">设为封面</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="editor_insert_attach('+res.id+');">插入</a></div>');
			    }
		}
		$("#"+file.id).find(".progressBarboxCompleted").hide();
	} catch (ex) {
		this.debug(ex);
	}
}
//上传完成调用
function uploadComplete(file) {
	upl.removePostParam("desc["+file.id+"]");	
    if(this.getStats().files_queued ==0){
    	$("#btn_completed_submit").attr("disabled",false);
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
			progress.setStatus("<span class='txt-red'>文件大小："+SWFUpload.speed.formatBytes(file.size)+", "+"超过限制</span>");
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			progress.setStatus("<span class='txt-red'>零字节文件，系统自动删除。</span>");
			break;
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
			progress.setStatus("<span class='txt-red'>同一次上传超过最大的文件数。</span>");
			break;
		default:
			if (file !== null) {
				progress.setStatus("<span class='txt-red'>未知错误"+"\n Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + SWFUpload.speed.formatBytes(file.size) + ", Message: " + message + "</span>");
			}
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}
//上
function FileProgress(file, targetID) {
	this.fileProgressID = file.id;

	this.opacity = 100;
	this.height = 0;
	this.wrapperClass = "progressWrapper";
	this.progressContainerClass = "progressContainer";
	this.progressCancelClass = "progressCancel";

	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	//创建文件上传框
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;

		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "progressContainer";
        /* 取消按钮*/
		var progressCancel = document.createElement("a");
		progressCancel.className = "progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "hidden";
		progressCancel.appendChild(document.createTextNode("取消"));

		var progressText = document.createElement("div");
		progressText.className = "progressImg";
		progressText.innerHTML += "<img class='purview' src='"+U()+"common/images/null.gif' />";
		//progressText.innerHTML += "<span class=\"name\">"+file.name+"</span>";
		//progressText.innerHTML += "<span class=\"size\">"+Math.round(file.size/1024)+"K</span>";
		
		var progressCtn = document.createElement("div");
		progressCtn.style.display = '';
		progressCtn.id = "ctn"+this.fileProgressID;
		progressCtn.className = "progressDesc";
		progressCtn.innerHTML += "";
		progressCtn.innerHTML += "<textarea name='' onfocus='util.focus(this,\"描述\")' onblur='util.blur(this,\"描述\")' class=\"description txt-gray\">描述</textarea>";

		var progressBar = document.createElement("div");
		progressBar.className = "progressBarbox";
		progressBar.innerHTML = "<div class='progressBarInProgress'></div>";

		var progressStatus = document.createElement("div");
		progressStatus.className = "progressBarStatus";
		//progressStatus.id = file.id+"_status";
		progressStatus.innerHTML = "&nbsp;";

		this.fileProgressElement.appendChild(progressCancel);
		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressCtn);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressBar);

		this.fileProgressWrapper.appendChild(this.fileProgressElement);

		document.getElementById(targetID).appendChild(this.fileProgressWrapper);
		//清除浮动
		$("#"+targetID).find(".cl").remove();
		$("#"+targetID).append("<div class='cl'></div>");
	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
		this.reset();
	}

	this.height = this.fileProgressWrapper.offsetHeight;
	this.setTimer(null);
}
FileProgress.prototype.get_extension = function(n){
	n = n.substr(n.lastIndexOf('.')+1);
	return n.toLowerCase();
}
FileProgress.prototype.get_icon = function(ext){
    var icons =	{
		'default':'file',
		'7z' : '7z',
		'asp' : 'asp',
		'aspx' : 'aspx',
		'bat' : 'bat',
		'bmp' : 'bmp',
		'chm' : 'chm',
		'css' : 'css',
		'db' : 'db',
		'dll' : 'dll',
		'doc' : 'doc',
		'exe' : 'exe',
		'file' : 'file',
		'fla' : 'fla',
		'gif' : 'gif',
		'htm' : 'htm',
		'html' : 'html',
		'images' : 'images',
		'ini' : 'ini',
		'jpeg' : 'jpeg',
		'jpg' : 'jpg',
		'js' : 'js',
		'jsp' : 'jsp',
		'lnk' : 'lnk',
		'mdb' : 'mdb',
		'mov' : 'mov',
		'mp3' : 'mp3',
		'pdf' : 'pdf',
		'php' : 'php',
		'png' : 'png',
		'ppt' : 'ppt',
		'psd' : 'psd',
		'qt' : 'qt',
		'quicktime' : 'quicktime',
		'rar' : 'rar',
		'reg' : 'reg',
		'rm' : 'rm',
		'rmvb' : 'rmvb',
		'shtml' : 'shtml',
		'swf' : 'swf',
		'tif' : 'tif',
		'torrent' : 'torrent',
		'txt' : 'txt',
		'vbs' : 'vbs',
		'video' : 'video',
		'video2' : 'video2',
		'video3' : 'video3',
		'vsd' : 'vsd',
		'wmv' : 'wmv',
		'xls' : 'xls',
		'xml' : 'xml',
		'xsl' : 'xsl',
		'zip' : 'zip'
	}
    return icons[ext] ? icons[ext] : icons['default'];
}
FileProgress.prototype.setTimer = function (timer) {
	this.fileProgressElement["FP_TIMER"] = timer;
};
FileProgress.prototype.getTimer = function (timer) {
	return this.fileProgressElement["FP_TIMER"] || null;
};

FileProgress.prototype.reset = function () {
	this.fileProgressElement.className = "progressContainer";

	this.fileProgressElement.childNodes[3].innerHTML = "&nbsp;";
	this.fileProgressElement.childNodes[3].className = "progressBarStatus";
	
	this.fileProgressElement.childNodes[4].childNodes[0].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[4].childNodes[0].style.width = "0%";
	
	this.appear();	
};
FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[4].childNodes[0].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[4].childNodes[0].style.width = percentage + "%";
	if(percentage>=100){
		this.fileProgressElement.childNodes[4].className = "progressBarboxCompleted";
	}
	this.appear();	
};
FileProgress.prototype.setComplete = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[4].childNodes[0].className = "progressBarComplete";
	this.fileProgressElement.childNodes[4].childNodes[0].style.width = "";
	var oSelf = this;
	this.setTimer(setTimeout(function () {
		oSelf.disappear();
	}, 10000));
};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[4].childNodes[0].className = "progressBarError";
	this.fileProgressElement.childNodes[4].childNodes[0].style.width = "";

	var oSelf = this;
	this.setTimer(setTimeout(function () {
		oSelf.disappear();
	}, 1000));
};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[4].childNodes[0].className = "progressBarError";
	this.fileProgressElement.childNodes[4].childNodes[0].style.width = "";

	var oSelf = this;
	this.setTimer(setTimeout(function () {
		oSelf.disappear();
	}, 1000));
};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressElement.childNodes[3].innerHTML = status;
};
FileProgress.prototype.toggleCancel = function (show, swfUploadInstance) {
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfUploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
			swfUploadInstance.cancelUpload(fileID);
			return false;
		};
	}
};
FileProgress.prototype.toggleCancelFile = function (show, swfUploadInstance) {
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfUploadInstance) {
		var oSelf = this;
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
			swfUploadInstance.cancelUpload(fileID);
			oSelf.disappear();
			return false;
		};
	}
};
FileProgress.prototype.appear = function () {
	if (this.getTimer() !== null) {
		clearTimeout(this.getTimer());
		this.setTimer(null);
	}
	
	if (this.fileProgressWrapper.filters) {
		try {
			this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 100;
		} catch (e) {
			this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=100)";
		}
	} else {
		this.fileProgressWrapper.style.opacity = 1;
	}	
	this.fileProgressWrapper.style.height = "";
	this.height = this.fileProgressWrapper.offsetHeight;
	this.opacity = 100;
	this.fileProgressWrapper.style.display = "";
};
FileProgress.prototype.disappear = function () {
	var reduceOpacityBy = 15;
	var reduceHeightBy = 4;
	var rate = 30;	
	if (this.opacity > 0) {
		this.opacity -= reduceOpacityBy;
		if (this.opacity < 0) {
			this.opacity = 0;
		}
		if (this.fileProgressWrapper.filters) {
			try {
				this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = this.opacity;
			} catch (e) {
				this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=" + this.opacity + ")";
			}
		} else {
			this.fileProgressWrapper.style.opacity = this.opacity / 100;
		}
	}
	if (this.height > 0) {
		this.height -= reduceHeightBy;
		if (this.height < 0) {
			this.height = 0;
		}

		this.fileProgressWrapper.style.height = this.height + "px";
	}
	if (this.height > 0 || this.opacity > 0) {
		var oSelf = this;
		this.setTimer(setTimeout(function () {
			oSelf.disappear();
		}, rate));
	} else {
		this.fileProgressWrapper.style.display = "none";
		this.setTimer(null);
	}
};
//////////////////队列上传相关事件处理
var SWFUpload;
if (typeof(SWFUpload) === "function") {
	SWFUpload.queue = {};	
	SWFUpload.prototype.initSettings = (function (oldInitSettings) {
		return function () {
			if (typeof(oldInitSettings) === "function") {
				oldInitSettings.call(this);
			}
			//定义队列变量
			this.queueSettings = {};
			
			this.queueSettings.queue_cancelled_flag = false;
			this.queueSettings.queue_upload_count = 0;
			
			//记录老的上传完成句柄
			this.queueSettings.user_upload_complete_handler = this.settings.upload_complete_handler;
			//记录老的上传开始句柄
			this.queueSettings.user_upload_start_handler = this.settings.upload_start_handler;
			
			//设置新的上传完成句柄
			this.settings.upload_complete_handler = SWFUpload.queue.uploadCompleteHandler;
			//设置新的上传开始句柄
			this.settings.upload_start_handler = SWFUpload.queue.uploadStartHandler;
			
			//队列完成句柄
			this.settings.queue_complete_handler = this.settings.queue_complete_handler || null;
		};
	})(SWFUpload.prototype.initSettings);

	SWFUpload.prototype.startUpload = function (fileID) {
		this.queueSettings.queue_cancelled_flag = false;
		this.callFlash("StartUpload", [fileID]);
	};
	SWFUpload.prototype.cancelQueue = function () {
		this.queueSettings.queue_cancelled_flag = true;
		this.stopUpload();
		
		var stats = this.getStats();
		while (stats.files_queued > 0) {
			this.cancelUpload();
			stats = this.getStats();
		}
	};	
	SWFUpload.queue.uploadStartHandler = function (file) {
		var returnValue;
		if (typeof(this.queueSettings.user_upload_start_handler) === "function") {
			returnValue = this.queueSettings.user_upload_start_handler.call(this, file);
		}
		
		returnValue = (returnValue === false) ? false : true;
		
		this.queueSettings.queue_cancelled_flag = !returnValue;

		return returnValue;
	};	
	SWFUpload.queue.uploadCompleteHandler = function (file) {
		var user_upload_complete_handler = this.queueSettings.user_upload_complete_handler;
		var continueUpload;
		
		if (file.filestatus === SWFUpload.FILE_STATUS.COMPLETE) {
			this.queueSettings.queue_upload_count++;
		}

		if (typeof(user_upload_complete_handler) === "function") {
			continueUpload = (user_upload_complete_handler.call(this, file) === false) ? false : true;
		} else if (file.filestatus === SWFUpload.FILE_STATUS.QUEUED) {
			continueUpload = false;
		} else {
			continueUpload = true;
		}
		
		if (continueUpload) {
			var stats = this.getStats();
			if (stats.files_queued > 0 && this.queueSettings.queue_cancelled_flag === false) {
				this.startUpload();
			} else if (this.queueSettings.queue_cancelled_flag === false) {
				this.queueEvent("queue_complete_handler", [this.queueSettings.queue_upload_count]);
				this.queueSettings.queue_upload_count = 0;
			} else {
				this.queueSettings.queue_cancelled_flag = false;
				this.queueSettings.queue_upload_count = 0;
			}
		}
	};
}
//////////////////speed
var SWFUpload;
if (typeof(SWFUpload) === "function") {
	SWFUpload.speed = {};	
	SWFUpload.prototype.initSettings = (function (oldInitSettings) {
		return function () {
			if (typeof(oldInitSettings) === "function") {
				oldInitSettings.call(this);
			}
			
			this.ensureDefault = function (settingName, defaultValue) {
				this.settings[settingName] = (this.settings[settingName] == undefined) ? defaultValue : this.settings[settingName];
			};

			this.fileSpeedStats = {};
			this.speedSettings = {};

			this.ensureDefault("moving_average_history_size", "10");			
			this.speedSettings.user_file_queued_handler = this.settings.file_queued_handler;
			this.speedSettings.user_file_queue_error_handler = this.settings.file_queue_error_handler;
			this.speedSettings.user_upload_start_handler = this.settings.upload_start_handler;
			this.speedSettings.user_upload_error_handler = this.settings.upload_error_handler;
			this.speedSettings.user_upload_progress_handler = this.settings.upload_progress_handler;
			this.speedSettings.user_upload_success_handler = this.settings.upload_success_handler;
			this.speedSettings.user_upload_complete_handler = this.settings.upload_complete_handler;
			
			this.settings.file_queued_handler = SWFUpload.speed.fileQueuedHandler;
			this.settings.file_queue_error_handler = SWFUpload.speed.fileQueueErrorHandler;
			this.settings.upload_start_handler = SWFUpload.speed.uploadStartHandler;
			this.settings.upload_error_handler = SWFUpload.speed.uploadErrorHandler;
			this.settings.upload_progress_handler = SWFUpload.speed.uploadProgressHandler;
			this.settings.upload_success_handler = SWFUpload.speed.uploadSuccessHandler;
			this.settings.upload_complete_handler = SWFUpload.speed.uploadCompleteHandler;
			
			delete this.ensureDefault;
		};
	})(SWFUpload.prototype.initSettings);
	
	SWFUpload.speed.fileQueuedHandler = function (file) {
		if (typeof this.speedSettings.user_file_queued_handler === "function") {
			//file = SWFUpload.speed.extendFile(file);
			
			return this.speedSettings.user_file_queued_handler.call(this, file);
		}
	};
	
	SWFUpload.speed.fileQueueErrorHandler = function (file, errorCode, message) {
		if (typeof this.speedSettings.user_file_queue_error_handler === "function") {
			//file = SWFUpload.speed.extendFile(file);			
			return this.speedSettings.user_file_queue_error_handler.call(this, file, errorCode, message);
		}
	};

	SWFUpload.speed.uploadStartHandler = function (file) {
		if (typeof this.speedSettings.user_upload_start_handler === "function") {
			file = SWFUpload.speed.extendFile(file, this.fileSpeedStats);
			return this.speedSettings.user_upload_start_handler.call(this, file);
		}
	};
	
	SWFUpload.speed.uploadErrorHandler = function (file, errorCode, message) {
		file = SWFUpload.speed.extendFile(file, this.fileSpeedStats);
		SWFUpload.speed.removeTracking(file, this.fileSpeedStats);

		if (typeof this.speedSettings.user_upload_error_handler === "function") {
			return this.speedSettings.user_upload_error_handler.call(this, file, errorCode, message);
		}
	};
	SWFUpload.speed.uploadProgressHandler = function (file, bytesComplete, bytesTotal) {
		this.updateTracking(file, bytesComplete);
		file = SWFUpload.speed.extendFile(file, this.fileSpeedStats);

		if (typeof this.speedSettings.user_upload_progress_handler === "function") {
			return this.speedSettings.user_upload_progress_handler.call(this, file, bytesComplete, bytesTotal);
		}
	};
	
	SWFUpload.speed.uploadSuccessHandler = function (file, serverData) {
		if (typeof this.speedSettings.user_upload_success_handler === "function") {
			file = SWFUpload.speed.extendFile(file, this.fileSpeedStats);
			return this.speedSettings.user_upload_success_handler.call(this, file, serverData);
		}
	};
	SWFUpload.speed.uploadCompleteHandler = function (file) {
		file = SWFUpload.speed.extendFile(file, this.fileSpeedStats);
		SWFUpload.speed.removeTracking(file, this.fileSpeedStats);

		if (typeof this.speedSettings.user_upload_complete_handler === "function") {
			return this.speedSettings.user_upload_complete_handler.call(this, file);
		}
	};
	
	SWFUpload.speed.extendFile = function (file, trackingList) {
		var tracking;
		
		if (trackingList) {
			tracking = trackingList[file.id];
		}
		
		if (tracking) {
			file.currentSpeed = tracking.currentSpeed;
			file.averageSpeed = tracking.averageSpeed;
			file.movingAverageSpeed = tracking.movingAverageSpeed;
			file.timeRemaining = tracking.timeRemaining;
			file.timeElapsed = tracking.timeElapsed;
			file.percentUploaded = tracking.percentUploaded;
			file.sizeUploaded = tracking.bytesUploaded;

		} else {
			file["currentSpeed"] = 0;
			file.averageSpeed = 0;
			file.movingAverageSpeed = 0;
			file.timeRemaining = 0;
			file.timeElapsed = 0;
			file.percentUploaded = 0;
			file.sizeUploaded = 0;
		}
		
		return file;
	};
	
	SWFUpload.prototype.updateTracking = function (file, bytesUploaded) {
		var tracking = this.fileSpeedStats[file.id];
		if (!tracking) {
			this.fileSpeedStats[file.id] = tracking = {};
		}
		
		bytesUploaded = bytesUploaded || tracking.bytesUploaded || 0;
		if (bytesUploaded < 0) {
			bytesUploaded = 0;
		}
		if (bytesUploaded > file.size) {
			bytesUploaded = file.size;
		}
		
		var tickTime = (new Date()).getTime();
		if (!tracking.startTime) {
			tracking.startTime = (new Date()).getTime();
			tracking.lastTime = tracking.startTime;
			tracking.currentSpeed = 0;
			tracking.averageSpeed = 0;
			tracking.movingAverageSpeed = 0;
			tracking.movingAverageHistory = [];
			tracking.timeRemaining = 0;
			tracking.timeElapsed = 0;
			tracking.percentUploaded = bytesUploaded / file.size;
			tracking.bytesUploaded = bytesUploaded;
		} else if (tracking.startTime > tickTime) {
			this.debug("When backwards in time");
		} else {
			var now = (new Date()).getTime();
			var lastTime = tracking.lastTime;
			var deltaTime = now - lastTime;
			var deltaBytes = bytesUploaded - tracking.bytesUploaded;
			
			if (deltaBytes === 0 || deltaTime === 0) {
				return tracking;
			}
			
			tracking.lastTime = now;
			tracking.bytesUploaded = bytesUploaded;
			
			tracking.currentSpeed = (deltaBytes * 8 ) / (deltaTime / 1000);
			tracking.averageSpeed = (tracking.bytesUploaded * 8) / ((now - tracking.startTime) / 1000);

			tracking.movingAverageHistory.push(tracking.currentSpeed);
			if (tracking.movingAverageHistory.length > this.settings.moving_average_history_size) {
				tracking.movingAverageHistory.shift();
			}
			
			tracking.movingAverageSpeed = SWFUpload.speed.calculateMovingAverage(tracking.movingAverageHistory);
			
			tracking.timeRemaining = (file.size - tracking.bytesUploaded) * 8 / tracking.movingAverageSpeed;
			tracking.timeElapsed = (now - tracking.startTime) / 1000;
			
			tracking.percentUploaded = (tracking.bytesUploaded / file.size * 100);
		}
		
		return tracking;
	};
	SWFUpload.speed.removeTracking = function (file, trackingList) {
		try {
			trackingList[file.id] = null;
			delete trackingList[file.id];
		} catch (ex) {
		}
	};
	
	SWFUpload.speed.formatUnits = function (baseNumber, unitDivisors, unitLabels, singleFractional) {
		var i, unit, unitDivisor, unitLabel;

		if (baseNumber === 0) {
			return "0 " + unitLabels[unitLabels.length - 1];
		}
		
		if (singleFractional) {
			unit = baseNumber;
			unitLabel = unitLabels.length >= unitDivisors.length ? unitLabels[unitDivisors.length - 1] : "";
			for (i = 0; i < unitDivisors.length; i++) {
				if (baseNumber >= unitDivisors[i]) {
					unit = (baseNumber / unitDivisors[i]).toFixed(2);
					unitLabel = unitLabels.length >= i ? " " + unitLabels[i] : "";
					break;
				}
			}
			
			return unit + unitLabel;
		} else {
			var formattedStrings = [];
			var remainder = baseNumber;
			
			for (i = 0; i < unitDivisors.length; i++) {
				unitDivisor = unitDivisors[i];
				unitLabel = unitLabels.length > i ? " " + unitLabels[i] : "";
				
				unit = remainder / unitDivisor;
				if (i < unitDivisors.length -1) {
					unit = Math.floor(unit);
				} else {
					unit = unit.toFixed(2);
				}
				if (unit > 0) {
					remainder = remainder % unitDivisor;
					
					formattedStrings.push(unit + unitLabel);
				}
			}
			
			return formattedStrings.join(" ");
		}
	};
	
	SWFUpload.speed.formatBPS = function (baseNumber) {
		var bpsUnits = [1073741824, 1048576, 1024, 1], bpsUnitLabels = ["Gbps", "Mbps", "Kbps", "bps"];
		return SWFUpload.speed.formatUnits(baseNumber, bpsUnits, bpsUnitLabels, true);
	
	};
	SWFUpload.speed.formatTime = function (baseNumber) {
		var timeUnits = [86400, 3600, 60, 1], timeUnitLabels = ["d", "h", "m", "s"];
		return SWFUpload.speed.formatUnits(baseNumber, timeUnits, timeUnitLabels, false);
	
	};
	SWFUpload.speed.formatBytes = function (baseNumber) {
		var sizeUnits = [1073741824, 1048576, 1024, 1], sizeUnitLabels = ["GB", "MB", "KB", "bytes"];
		return SWFUpload.speed.formatUnits(baseNumber, sizeUnits, sizeUnitLabels, true);
	
	};
	SWFUpload.speed.formatPercent = function (baseNumber) {
		return baseNumber.toFixed(2) + " %";
	};
	
	SWFUpload.speed.calculateMovingAverage = function (history) {
		var vals = [], size, sum = 0.0, mean = 0.0, varianceTemp = 0.0, variance = 0.0, standardDev = 0.0;
		var i;
		var mSum = 0, mCount = 0;
		
		size = history.length;
		
		if (size >= 8) {
			for (i = 0; i < size; i++) {
				vals[i] = history[i];
				sum += vals[i];
			}

			mean = sum / size;

			for (i = 0; i < size; i++) {
				varianceTemp += Math.pow((vals[i] - mean), 2);
			}

			variance = varianceTemp / size;
			standardDev = Math.sqrt(variance);
			
			for (i = 0; i < size; i++) {
				vals[i] = (vals[i] - mean) / standardDev;
			}

			var deviationRange = 2.0;
			for (i = 0; i < size; i++) {
				
				if (vals[i] <= deviationRange && vals[i] >= -deviationRange) {
					mCount++;
					mSum += history[i];
				}
			}
			
		} else {
			mCount = size;
			for (i = 0; i < size; i++) {
				mSum += history[i];
			}
		}

		return mSum / mCount;
	};
	
}