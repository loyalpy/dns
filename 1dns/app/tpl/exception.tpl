<style>
* {font-family:"Microsoft YaHei";}
.fb {font-weight:700;}
.font-red {color:#f00;}
.font-org {color:#f60;}
.message,.trace{padding:1em;border:solid 1px #000;margin:10px 0;background:#FFD;line-height:150%;}
.message{background:#FFD;color:#2E2E2E;border:1px solid #E0E0E0;}
.trace{background:#E7F7FF;border:1px solid #E0E0E0;color:#535353;}
.code{overflow:auto;padding:5px;background:#EEE;border:1px solid #ddd;}
.notice{padding:10px;margin:5px;color:#666;	background:#FCFCFC;border:1px solid #E0E0E0;}
code{font-size:14px!important;padding:0 .2em!important;border-bottom:1px solid #DEDEDE !important}
</style>
<div class="notice">
<?php if(isset($error['file'])) {?>
<p><strong>[Location]</strong>　FILE: <span class="font-red"><?php echo $error['file'] ;?></span>　LINE: <span class="font-red"><?php echo $error['line'];?></span></p>
<div class="code"><?php echo $error['detail'];?></div>
<?php }?>
<p></p>
<p class="fb font-org">[Info]</p>
<p class="message"><strong><?php echo strip_tags($error['type']);?></strong> :  <?php echo strip_tags($error['message']);?>
</p>
<?php if(isset($error['trace'])) {?>
<p></p>
<p class="fb font-org">[Trace]</p>
<p class="trace">
<?php echo nl2br($error['trace']);?>
</p>
<?php }?>
</div>
</div>
