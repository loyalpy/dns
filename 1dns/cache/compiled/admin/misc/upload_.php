<div class="attach-box">
    <?php if($option['btn_setfm']){?>
	<input type="hidden" name="fm_id" value="<?php echo isset($option['fm_id'])?$option['fm_id']:"";?>" />
	<input type="hidden" name="fm" value="<?php echo isset($option['fm'])?$option['fm']:"";?>" />
	<?php }?>
	<p class="btn-upload">
		<button class="btn btn-danger btn-xs" type="button">
		<span class="glyphicon glyphicon-picture"></span> <?php echo isset($option['upload_btn_txt'])?$option['upload_btn_txt']:"";?>
		</button>
		<span class="btn-swfupload"><span id="spanUPButton"></span></span>
		<span class="btn_cpbtn"><button clip="" type="button" class="btn btn-xs btn-success copyclip all_copyclip">拷贝所有图片</button></span>
	</p>
	<div id="swfupload_filebox"></div>
	<script type="text/template" class="tpl">
	<#macro form data>
	<div class="attach_${data.id}">
		<div class="swfitem-img"><img width="50" height="50" class="purview" src="${data.imgpath}" alt="${data.name}" /></div>
		<div class = "swfitem-desc"><textarea class="form-control" rows="2" name="attach_ids[${data.id}]">${data['desc']}</textarea></div>
		<div class = "swfitem-op">
		    <?php if($option['btn_setfm']){?>
			<button type="button" class="btn btn-default btn-xs setfm">主图</button> 
			<?php }?>
			<?php if($option['btn_insert']){?>
			<button type="button" class="btn btn-default btn-xs insert">插入</button> 
			<?php }?>
			<?php if($option['btn_delete']){?>
			<button type="button" class="btn btn-default btn-xs delete">删除</button>
			<?php }?> 
            <#if (data.imgpath)>
			<button type="button" clip="http://<?php echo tServer::get_host();?>${data.imgpath.replace('-50-50','')}" class="btn btn-default btn-xs copyclip">复制</button>
            </#if>
		</div>
		<div class="clearfix"></div>
	</div>
	</#macro>
	</script>
</div>
<script language="javascript" src="<?php echo U("static@/javascript/utils/swfupload.core.js");?>"></script>
<script language="javascript" src="<?php echo U("static@/javascript/utils/jquery.swfupload.event.js");?>"></script>
<script language="javascript">
	  $(function(){
		var settings = {
			flash_url : "<?php echo U("/static/public/flash/moreupload.swf");?>",
			upload_url: "<?php echo isset($option['upload_url'])?$option['upload_url']:"";?>",
			post_params: <?php echo isset($option['post_params_json'])?$option['post_params_json']:"";?>,
			file_size_limit : "<?php echo isset($option['upmaxsize'])?$option['upmaxsize']:"";?>KB",
			file_queue_limit: <?php echo isset($option['file_queue_limit'])?$option['file_queue_limit']:"";?>,
			file_types : "<?php echo isset($option['upload_types_str'])?$option['upload_types_str']:"";?>",
			button_image_url : "",
			button_placeholder_id : "spanUPButton",
			button_width: 101,
			button_height: 18,
			debugEnabled:true,
			button_text : "",
			//文件队列处理
			file_queued_handler : swfupload_event.fileQueued,
			file_dialog_complete_handler: swfupload_event.fileDialogComplete,
			file_queue_error_handler : swfupload_event.fileQueueError,
			//单个上传处理
			upload_start_handler : swfupload_event.uploadStart,
			upload_progress_handler : swfupload_event.uploadProgress,
			upload_success_handler : swfupload_event.uploadSuccess,
			upload_complete_handler : swfupload_event.uploadComplete,
			//自定义设置
			custom_settings : {
				filebox : "swfupload_filebox",
				fileinsert:function(file_id){
					<?php echo isset($option['insert_js'])?$option['insert_js']:"";?>
				},
				delete_url:"<?php echo isset($option['delete_url'])?$option['delete_url']:"";?>",
				inp_fm:"<?php echo isset($option['inp_fm'])?$option['inp_fm']:"";?>",
				inp_fm_id:"<?php echo isset($option['inp_fm_id'])?$option['inp_fm_id']:"";?>",
				fm_id:"<?php echo isset($option['fm_id'])?$option['fm_id']:"";?>"
			}
		};
		var upl = new SWFUpload(settings);
		var all_images = "";
		var tmp = "";
		<?php if(!empty($option['attachlist'])){?>
		  <?php foreach($option['attachlist'] as $key => $item){?>
		  tmp = "<?php echo U("static@/attach");?>/<?php echo isset($option['path'])?$option['path']:"";?>/<?php echo isset($item['path'])?$item['path']:"";?>/<?php echo isset($item['filename'])?$item['filename']:"";?>_50_50.<?php echo isset($item['ext'])?$item['ext']:"";?>";
		  all_images += "<img src='http://<?php echo tServer::get_host();?>{webroot attach}/<?php echo isset($option['path'])?$option['path']:"";?>/<?php echo isset($item['path'])?$item['path']:"";?>/<?php echo isset($item['filename'])?$item['filename']:"";?>.<?php echo isset($item['ext'])?$item['ext']:"";?>' /><br/>";
		  new swfupload_filebox({id:<?php echo isset($item['id'])?$item['id']:"";?>,imgpath:tmp,desc:"<?php echo isset($item['description'])?$item['description']:"";?>",loaded:1}, upl.customSettings);
		  <?php }?>
		   $(".all_copyclip").attr("clip",all_images);
		   $('.copyclip').zclip({
		       path: "<?php echo U("static@/javascript/jquery.zclip/ZeroClipboard.swf");?>",
		       copy: function(){
		          var obj = this;
		          return $(obj).attr("clip");
		  　　　 }
		   });
		<?php }?>
	 });
</script>