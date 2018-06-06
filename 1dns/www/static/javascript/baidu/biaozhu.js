/**
 * 
 */
/**
 * 
 */
var mybaidu = {
	map: null,
	local: null,
	init_map: function() {
		var lng = $("#Mlng").val() || 120.243918,
			lat = $("#Mlat").val() || 30.216384;
		
		this.map = new BMap.Map("map_canvas"), this.map.enableScrollWheelZoom(), this.map.addControl(new BMap.NavigationControl);
		var point = new BMap.Point(lng, lat);
		
		this.map.centerAndZoom(point, 15);
		var marker = new BMap.Marker(point),
			infowindow = new BMap.InfoWindow('<div style="font:bold 14px/20px 宋体;color:#333">' + $("#Mname").val() + '</div><div style="font: 12px/20px 宋体;color:#333">地址：' + $("#Mpos").val() + "</div>");
		
		marker.addEventListener("click", function(){
			this.openInfoWindow(infowindow)
		}), marker.enableDragging(!0), marker.addEventListener("dragend", function(a) {
			$("#Mlng").val(a.point.lng), $("#Mlat").val(a.point.lat)
		}), this.map.addOverlay(marker), this.map.addEventListener("click", function(a) {
			mybaidu.map.removeOverlay(marker), 
			$("#Mlng").val(a.point.lng), 
			$("#Mlat").val(a.point.lat), 
			marker = new BMap.Marker(new BMap.Point(a.point.lng, a.point.lat)), 
			mybaidu.map.addOverlay(marker), marker.enableDragging(!0), marker.addEventListener("dragend", function(a) {
				$("#Mlng").val(a.point.lng), $("#Mlat").val(a.point.lat)
			})
		}), 
		this.local = new BMap.LocalSearch(mybaidu.map, {
			renderOptions: {
				map: mybaidu.map
			}
		})
	},
	search_map: function(a) {
		this.local.search(a)
	}
}