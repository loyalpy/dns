var from_point;
var to_point;
//http://api.map.baidu.com/api?v=2.0&ak=9d91d8b1f34adbd3242eb377dee57455
$(function () {
	if($.cookies.get('gps')==null){
	    $('.loading_overlay').css({ 'display': 'block', 'opacity': '0.8'});
	    $('.display1').show();
	    getMaps1('121.427239', '28.662118','台州市政府');
	}
	$(".modifygps").click(function (){ 
        $('.loading_overlay').css({ 'display': 'block', 'opacity': '0.8'});
	    $('.display1').show();
	       
        var areaall=$('#position').val().split(',');
        getMaps1(areaall[0],areaall[1],areaall[2]);
	});  
        
	$(".submit1").click(function (){ 
	         $('.display1').hide();
	         $(".loading_showbox").show().stop(true).animate({ 'margin-top': '350px','margin-left': '-100px', 'opacity': '1','top':'0','z-index':'10050'}, 0);
	         
	         $.ajax({
			   type: "post",
			   url: "ajax/gps.ashx",
			   data:{position:$('#position').val()},
			   success : function(){
		           location.href='near.aspx?key='+($('.inp_h_sea').val()!=""?$('.inp_h_sea').val():"");
			   }
		 }); 
     });   

     $(".close1").click(function (){ 
             $(".loading_overlay").css({ 'display': 'none', 'opacity': '0' });
	         $('.display1').hide();
      });   
      
     $(".checkarea").find('a').click(function (){ 
          var areaall=$(this).attr('ref').split(',');
          getMaps1(areaall[0],areaall[1],areaall[2]);
           
          $('#position').val($(this).attr('ref'));
     }); 
        
     $(".juli").click(function (){ 
          $('.loading_overlay').css({ 'display': 'block', 'opacity': '0.8'});
	      $('.display2').show();
	         
	      from_point=$('#position').val();
	      to_point=$(this).data('id');
	      gongjiao();
      });       
        
      $(".close_box").click(function (){ 
         $('.loading_overlay').css({ 'display': 'none', 'opacity': '0'});
	     $('.display2').hide();
      });     
})
      
        
      function getMaps1(lng,lat,position) {
			var map = new BMap.Map("map1");
				map.enableScrollWheelZoom();
				map.addControl(new BMap.NavigationControl());
			var point = new BMap.Point(lng,lat);
				map.centerAndZoom(point, 15);
			var markerls = new BMap.Marker(point);
			var infoWindow = new BMap.InfoWindow("<span style=\"font-size:14px;\">当前位置:"+position+"<br><span style=\"font-size:12px; line-height:24px;\">(提示:任意点击地图,选择您的位置)</span></span>"); 
				map.openInfoWindow(infoWindow,point);
				markerls.addEventListener("click", function() {
					map.openInfoWindow(infoWindow,point);
				});
				markerls.enableDragging(true); 
				map.addOverlay(markerls);
			var geoc = new BMap.Geocoder();		
				map.addEventListener('click', function(e) {
				    map.removeOverlay(markerls);
					markerls = new BMap.Marker(new BMap.Point(e.point.lng, e.point.lat));
					map.addOverlay(markerls);					
							
					var pt = e.point;
		            geoc.getLocation(pt, function(rs){
			        var addComp = rs.addressComponents;
			        var address=addComp.district +  addComp.street+ addComp.streetNumber;
			             $('#position').val(e.point.lng+','+e.point.lat+','+address);  
		            var infoWindow_new = new BMap.InfoWindow("<span style=\"font-size:14px;\">当前位置:"+address+"<br><span style=\"font-size:12px; line-height:24px;\">(提示:任意点击地图,选择您的位置)</span></span>");
		                map.openInfoWindow(infoWindow_new,new BMap.Point(e.point.lng, e.point.lat));
		             });  
			});    
      }
      
     
     function drive(f_lng,f_lat,f_position,c_lng,c_lat,c_position,num,dom,type){
        var f_point=new BMap.Point(f_lng,f_lat);
        var c_point=new BMap.Point(c_lng,c_lat)
        
        map = new BMap.Map("map2", { minZoom: 12 });
        map.enableScrollWheelZoom();
        map.enableKeyboard();
        map.addControl(new BMap.NavigationControl());
        map.centerAndZoom(f_point, 15);
            
        if(type==1)
        {
            var transit = new BMap.TransitRoute("台州市");
                transit.setSearchCompleteCallback(function(results){
                  if (transit.getStatus() == BMAP_STATUS_SUCCESS){
                    if(num==-1){
                        var s = [];
                        for (i = 0; i < results.getNumPlans(); i ++){
                            var luxian='<div class="items plr10">'+('，'+results.getPlan(i).getDescription(false)).replace(/，步行/g,'</div></b></p><div class="items plr10"><div class="trans_icon zoubu">步行：</div><p class="maptext">步行<b>').replace(/，乘坐/g,'</b></p></div><div class="items plr10"><div class="trans_icon bus">公交：</div><p class="maptext">乘坐 <b>').replace(/，到达/g,'</b> 到达 <b>').replace(/终点/g,'终点</b></p></div>').replace('','<div class="items plr10"></b></p></div>');
                          if(i==0){
                             s.push('<div class="luxian" onclick="drive('+f_lng+','+f_lat+',\''+f_position+'\','+c_lng+','+c_lat+',\''+c_position+'\','+i+',this,'+type+')"><div class="luxian_box"><div class="items mb10 plr10"><div class="trans_img qidian">起点：</div><p class="maptext"><span class="title">'+f_position+'</span></p></div>'+luxian+'<div class="items plr10"><div class="trans_img zhongdian">终点：</div><p class="maptext"><span class="title">'+c_position+'</span></p></div></div></div>');
                            }
                             else{
                             s.push('<div class="luxian opacity" onclick="drive('+f_lng+','+f_lat+',\''+f_position+'\','+c_lng+','+c_lat+',\''+c_position+'\','+i+',this,'+type+')"><div class="luxian_box"><div class="items mb10 plr10"><div class="trans_img qidian">起点：</div><p class="maptext"><span class="title">'+f_position+'</span></p></div>'+luxian+'<div class="items plr10"><div class="trans_img zhongdian">终点：</div><p class="maptext"><span class="title">'+c_position+'</span></p></div></div></div>');
                             }
                        }
                        $('#r-result').html(s.join(''));
                    }

                      var num1=num;
                      if(num==-1){
                        num1=0;
                      }
                      if(num!=-1){
                        var _dom = $(dom);
                            _dom.removeClass('opacity')
                            _dom.siblings("div").removeClass('opacity').addClass('opacity');
                      }
                         var firstPlan = results.getPlan(num1);
                         for (var i = 0; i < firstPlan.getNumRoutes(); i ++){
                           var walk = firstPlan.getRoute(i);
                           if (walk.getDistance(false) > 0){
                              map.addOverlay(new BMap.Polyline(walk.getPath(), {strokeColor: "green"}));
                            }
                          }
                         for (i = 0; i < firstPlan.getNumLines(); i ++){
                            var line = firstPlan.getLine(i);
                            map.addOverlay(new BMap.Polyline(line.getPath()));
                          }
                  }
                  else{
                    $('#r-result').html('<div style="text-align:center; font-size:14px; padding:20px;">无法查询公交路线</div>');   
                  }
                })
               transit.search(f_point, c_point);
               map.addOverlay(new BMap.Marker(f_point, {icon: new BMap.Icon('/images/icon_start.gif', new BMap.Size(29, 37))})); 
               map.addOverlay(new BMap.Marker(c_point, {icon: new BMap.Icon('/images/icon_end.gif', new BMap.Size(29, 37))}));
           }
           else{
              var driving = new BMap.DrivingRoute(map, {
                    renderOptions : {map : map,autoViewport:true},
                     onSearchComplete: function(results){
                     
                      if (driving.getStatus() == BMAP_STATUS_SUCCESS){
                          var plan = results.getPlan(0);
                          var route = plan.getRoute(0);
                          var s = [];
                          for (var i = 0; i < route.getNumSteps(); i ++){
                            var step = route.getStep(i);
                            s.push('<div class="items plr10">'+(i + 1) + ". " + step.getDescription()+'</div>');
                          }
                          $('#l-result').html('<div class="items mb10 plr10"><div class="trans_img qidian">起点：</div><p class="maptext"><span class="title">'+f_position+'</span></p></div>'+s.join("")+'<div class="items plr10 mt30"  style="margin-top:10px !important;"><div class="trans_img zhongdian">终点：</div><p class="maptext"><span class="title">'+c_position+'</span></p></div>');
                        }
                        else{
                          $('#l-result').html('<div style="text-align:center; font-size:14px; padding:20px;">无法查询驾车路线</div>');   
                        }
                                    
                       }
                     });
                 driving.search(f_point, c_point);
           }  
    }
    
     function gongjiao(dom){
       var areaall1=from_point.split(',');
       var areaall2=to_point.split(',');
        drive(areaall1[0],areaall1[1],areaall1[2],areaall2[0],areaall2[1],areaall2[2],-1,dom,1);
        $('.gongjiao').show();
        $('.car').hide();
    }  

    function jiache(dom){
       var areaall1=from_point.split(',');
       var areaall2=to_point.split(',');
        drive(areaall1[0],areaall1[1],areaall1[2],areaall2[0],areaall2[1],areaall2[2],-1,dom,2);
        $('.gongjiao').hide();
        $('.car').show();
    }  