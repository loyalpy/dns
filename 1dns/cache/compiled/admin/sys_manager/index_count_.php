{block head_menu}

{/block}
{block css}
<style>
</style>
{/block}

{block main}
<div class="uc-index-row1">
   <div class="uc-userinfo">
       <!--<div class="avatar"><a class="tiptitle" data-content="点击修改头像" href="<?php echo U("account@/ucenter/profile_avatar");?>" target="_blank"><img class="myavatar_50" width="50" height="50" src="<?php echo tFun::avatar($uid);?>" /></a> </div>-->
       <div class="avatar"><a class="tiptitle"  href="javascript:void 0;" target="_blank"><img class="myavatar_50" width="50" height="50" src="<?php echo tFun::avatar($uid);?>" /></a> </div>
       <div class="info"><h1>Hi! <span class="font-org f24"><?php echo tUtil::substr($this->userinfo['name'],3);?></span>&nbsp;&nbsp;<a href="<?php echo U("account@/ucenter/profile_basic");?>" class="font-blue">修改</a></h1>
       <h2 class="f12 font-gray">问题联系QQ：415480171
       </h2>
       </div>
       <div class="op">
          <?php if($this->check_purview('/domain_manager/domain')){?>
          <a href="<?php echo U("/domain_manager/domain");?>" class="btn btn-default btn-xs">域名管理</a>&nbsp;
          <?php }?>
           <?php if($this->check_purview("/sys_manager/index_weixinport")){?>
           <a target="_blank" href="<?php echo U("/sys_manager/set_wxmenu");?>" target="_blank" class="btn btn-default btn-xs">微信菜单生成</a>&nbsp;
           <?php }?>
           <div class="dis5"></div>
           <?php if($this->check_purview("/sys_manager/index_import")){?>
           <a target="_blank" style="display:none;" href="<?php echo U("/import_manager/importsdsd");?>" target="_blank" class="btn btn-default btn-xs">老版数据导入</a>&nbsp;
           <?php }?>
           <?php if($this->check_purview("/sys_manager/index_batchrash")){?>
           <a target="_blank" style="display:none;" href="<?php echo U("/import_manager/domain_refresh");?>" target="_blank" class="btn btn-default btn-xs">域名批量刷新</a>&nbsp;
           <?php }?>
          </p>
       </div>
       <a type="button" href="<?php echo U("sys_manager/index");?>" class="btn btn-warning btn-sm" style="float: right"><cite class="glyphicon glyphicon-align-justify"></cite> 切换统计数据</a>
       <div class="cl"></div>
   </div>
   <div class="sysnotify">

   </div>
   <div class="cl"></div>
</div>

<?php //年份,月份
$month = R("month","int");
$year = R("year","int");
$month = $month?$month:intval(tTime::get_datetime("m"));
$year = $year?$year:intval(tTime::get_datetime("Y"));

?>
<div class="headbar" style="margin-left: 60px;">
    <div class="operating">
	<span class="form">
	<select name="year" id="Myear">
        <option value="2013" <?php if($year == 2013){?>selected="selected"<?php }?>>2013</option>
        <option value="2012" <?php if($year == 2012){?>selected="selected"<?php }?>>2012</option>
        <option value="2014" <?php if($year == 2014){?>selected="selected"<?php }?>>2014</option>
        <option value="2015" <?php if($year == 2015){?>selected="selected"<?php }?>>2015</option>
        <option value="2016" <?php if($year == 2016){?>selected="selected"<?php }?>>2016</option>
    </select>&nbsp;
	<select name="month" id="Mmonth">
        <option value="1" <?php if($month == 1){?>selected="selected"<?php }?>>1</option>
        <option value="2" <?php if($month == 2){?>selected="selected"<?php }?>>2</option>
        <option value="3" <?php if($month == 3){?>selected="selected"<?php }?>>3</option>
        <option value="4" <?php if($month == 4){?>selected="selected"<?php }?>>4</option>
        <option value="5" <?php if($month == 5){?>selected="selected"<?php }?>>5</option>
        <option value="6" <?php if($month == 6){?>selected="selected"<?php }?>>6</option>
        <option value="7" <?php if($month == 7){?>selected="selected"<?php }?>>7</option>
        <option value="8" <?php if($month == 8){?>selected="selected"<?php }?>>8</option>
        <option value="9" <?php if($month == 9){?>selected="selected"<?php }?>>9</option>
        <option value="10" <?php if($month == 10){?>selected="selected"<?php }?>>10</option>
        <option value="11" <?php if($month == 11){?>selected="selected"<?php }?>>11</option>
        <option value="12" <?php if($month == 12){?>selected="selected"<?php }?>>12</option>
    </select>
	<button class="operating_btn" onclick="$.redirect('<?php echo U("/sys_manager/index_count?year=");?>'+$('#Myear').val()+'&month='+$('#Mmonth').val())" type="button">查看</button>
	</span>
    </div>
</div>
<div class="list content">
    <div class="fl" style="width:600px;">
        <script type="text/javascript" src="<?php echo U("/sys_manager/sys_count_image?t=user_reg_month&w=600&h=260&month=$month&year=$year");?>"></script>
    </div>
    <div class="fr" style="width:500px;">
        <script type="text/javascript" src="<?php echo U("/sys_manager/sys_count_image?t=user_reg_year&w=500&h=260&month=$month&year=$year");?>"></script>
    </div>
    <div class="cl"></div>
    <div class="fl" style="width:600px;">
        <script type="text/javascript" src="<?php echo U("/sys_manager/sys_count_image?t=user_account_month&w=600&h=260&month=$month&year=$year");?>"></script>
    </div>
    <div class="fr" style="width:520px;">
        <script type="text/javascript" src="<?php echo U("/sys_manager/sys_count_image?t=user_account_year&w=520&h=260&month=$month&year=$year");?>"></script>
    </div>
    <div class="dis10"></div>
    <div class="dis30"></div>
</div>
{/block}


{block javascript}
<?php echo $this->fetch('tpl/form')?>
<script language="javascript" src="<?php echo U("static@/javascript/highcharts/highcharts.js");?>"></script>
<script language="javascript">
$(function(){

})
</script>
{/block}