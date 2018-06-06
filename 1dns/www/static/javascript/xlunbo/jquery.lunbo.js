//�����ű�����
var scripts = document.getElementsByTagName('script');  
var current_script = scripts[scripts.length - 1];
var script_parm = {};
var p_str = current_script.src.split("?")[1].split("&");
if(p_str.length>0){
    for (var i=0;i<p_str.length ;i++ ){
		var tmp = p_str[i].split("=");
		script_parm[tmp[0]] = tmp[1];
    }
}
p_str=tmp=current_script=scripts = null;

$(function() {
			var sWidth = $("#focus").width(); //��ȡ����ͼ�Ŀ�ȣ���ʾ�����
			var len = $("#focus ul li").length; //��ȡ����ͼ����
			var index = 0;
			var picTimer;
			
			//���´���������ְ�ť�Ͱ�ť��İ�͸������������һҳ����һҳ������ť
			var btn = "<div class='btnBg'></div><div class='btn'>";
			for(var i=0; i < len; i++) {
				btn += "<span></span>";
			}
			btn += "</div><div class='preNext pre'></div><div class='preNext next'></div>";
			$("#focus").append(btn);
			$("#focus .btnBg").css("opacity",0.5);
		
			//ΪС��ť�����껬���¼�������ʾ��Ӧ������
			$("#focus .btn span").css("opacity",0.4).mouseenter(function() {
				index = $("#focus .btn span").index(this);
				showPics(index);
			}).eq(0).trigger("mouseenter");
		
			//��һҳ����һҳ��ť͸���ȴ���
			$("#focus .preNext").css("opacity",0.08).hover(function() {
				$(this).stop(true,false).animate({"opacity":"0.3"},300);
			},function() {
				$(this).stop(true,false).animate({"opacity":"0.08"},300);
			});
		
			//��һҳ��ť
			$("#focus .pre").click(function() {
				index -= 1;
				if(index == -1) {index = len - 1;}
				showPics(index);
			});
		
			//��һҳ��ť
			$("#focus .next").click(function() {
				index += 1;
				if(index == len) {index = 0;}
				showPics(index);
			});
		
			//����Ϊ���ҹ�����������liԪ�ض�����ͬһ�����󸡶�������������Ҫ�������ΧulԪ�صĿ��
			$("#focus ul").css("width",sWidth * (len));
			$("#focus ul li").css("width",sWidth);
			//��껬�Ͻ���ͼʱֹͣ�Զ����ţ�����ʱ��ʼ�Զ�����
			$("#focus").hover(function() {
				clearInterval(picTimer);
			},function() {
				picTimer = setInterval(function() {
					showPics(index);
					index++;
					if(index == len) {index = 0;}
				},4000); //��4000�����Զ����ŵļ������λ������
			}).trigger("mouseleave");
			
			//��ʾͼƬ���������ݽ��յ�indexֵ��ʾ��Ӧ������
			function showPics(index) { //��ͨ�л�
				var nowLeft = -index*sWidth; //����indexֵ����ulԪ�ص�leftֵ
				$("#focus ul").stop(true,false).animate({"left":nowLeft},300); //ͨ��animate()����ulԪ�ع������������position
				$("#focus .btn span").removeClass("on").eq(index).addClass("on"); //Ϊ��ǰ�İ�ť�л���ѡ�е�Ч��
				$("#focus .btn span").stop(true,false).animate({"opacity":"0.4"},300).eq(index).stop(true,false).animate({"opacity":"1"},300); //Ϊ��ǰ�İ�ť�л���ѡ�е�Ч��
			}
});