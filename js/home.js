$(document).ready(function() {
	$('.bigimg').css('position', 'absolute');
	u=$('#svideoul');
	u.css('position', 'relative');
	u.css('top', '0px');
	
	t = $('#svideodiv');
	t.css('overflow', 'scroll');
	t.css('overflow-x', 'hidden');
	t.css('overflow-y', 'hidden');
	t.css('position', 'relative');
	t.css('scrollbar-width', '50px');
	
	t.mousemove(function(e){
		dh=t.height();
		sh=u.height();
		if(dh>sh)return;
		my=e.pageY-this.offsetTop;
		
		st = t.scrollTop();
		if(my<(dh/2)){
			t.scrollTop(st-5);
		}else{
			t.scrollTop(st+5);
		};
	});
	$.fn.changeStyle = function changeStyle(){
		var mobiles = new Array(
			"midp", "j2me", "avant", "docomo", "novarra", "palmos", "palmsource",
			"240x320", "opwv", "chtml", "pda", "windows ce", "mmp/",
			"blackberry", "mib/", "symbian", "wireless", "nokia", "hand", "mobi",
			"phone", "cdm", "up.b", "audio", "sie-", "sec-", "samsung", "htc",
			"mot-", "mitsu", "sagem", "sony", "alcatel", "lg", "eric", "vx",
			"NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch",
			"rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi",
			"bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo",
			"sgh", "gradi", "jb", "dddi", "moto", "iphone", "android",
			"iPod", "incognito", "webmate", "dream", "cupcake", "webos",
			"s8000", "bada", "googlebot-mobile"
		);
		var ua = navigator.userAgent.toLowerCase();
		var isMobile = false;
		for (var i = 0; i < mobiles.length; i++) {
			if (ua.indexOf(mobiles[i]) > 0) {
				isMobile = true;
				$('#svideodiv').css('overflow-y', 'auto');
				break;
			}
		}
	};
	$(this).changeStyle();
	$.fn.changeImages = function changeImages(vids) {
		if((!vids)||vids.length<1) return;
		vidn = vids.shift();
		vido = vids[vids.length-1];
		vids.push(vidn);
		
		bimgn = "#bimg" + vidn;
		bimgo = "#bimg" + vido;
		
		$(bimgo).fadeOut();
		$(bimgn).fadeIn();
		
		simgn = "#simg" + vidn;
		simgo = "#simg" + vido;
		
		$(simgo).attr('class', '');
		$(simgn).attr('class', 'gre_bg');
		
		//console.log('vidn:'+vidn+', vido:'+vido);
		
		setTimeout("$(this).changeImages(vids)", 3000);
	};
	$(this).changeImages(vids);
});
