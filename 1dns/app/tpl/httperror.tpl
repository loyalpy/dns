<html>
 <head>
  <title>WebServer - Error report <?php echo $show_data['title'];?></title>
  <style><!--H1 {font-family:Tahoma,Arial,sans-serif;color:white;background-color:#525D76;font-size:22px;} H2 {font-family:Tahoma,Arial,sans-serif;color:white;background-color:#525D76;font-size:16px;} H3 {font-family:Tahoma,Arial,sans-serif;color:white;background-color:#525D76;font-size:14px;} BODY {font-family:Tahoma,Arial,sans-serif;color:black;background-color:white;} B {font-family:Tahoma,Arial,sans-serif;color:white;background-color:#525D76;} P {font-family:Tahoma,Arial,sans-serif;background:white;color:black;font-size:12px;}A {color : black;}A.name {color : black;}HR {color : #525D76;}*{margin:0;padding:0;}a img {border:none;}--></style> 
 </head>
 <body style="text-align: cetner;">
  <div class="aps" style="margin-top:60px;width:843px;margin:60px auto;">
  <a href="/"><img alt="404" src="/static/public/images/404.png" /></a></div>
  <div style="display:none;">
  <h1>HTTP Status <?php echo $show_data['http_num'];?> - <?php echo $show_data['heading'];?></h1>
  <hr size="1" noshade="noshade" />
  <p><b>type</b> Status report <?php echo $show_data['http_num'];?></p>
  <p><b>message</b> <u></u></p>
  <hr size="1" noshade="noshade" />
  <h3>WebServer 1.0</h3>
  </div>
 </body>
 <!--<?php echo $show_data['message'];?> message-->
</html>