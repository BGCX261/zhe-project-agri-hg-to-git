// JavaScript Document
$(document).ready(function() { 

	
	$('.menuImg').mouseover(function(){
		var n = $(this).index() + 1;				
		$(this).find('img').attr('src','images/menu_' + n + '_ov.' + 'jpg');
		$('.smenu_nav').css('visibility','hidden');
		$('.main_nav_' + (n-1)).css('visibility','visible');
	});		
	
	$('.menuImg').mouseout(function(){		
		var x = parseInt($('#mMenu').attr('current'));//current select
		var n = parseInt($(this).index()) + 1;	
		if(n!=x){
			$(this).find('img').attr('src','images/menu_' + n + '_off.' + 'jpg');
		}
	});
	
	$('.mA_con').mouseover(function(){
		$('.smenu_nav').css('visibility','hidden');
		$('.mn_1_list').css('visibility','hidden');
	});
	
	$('.mn_1 > li').mouseover(function(){
		$('.mn_1_list').css('visibility','hidden');
		$(this).find('ul').css('visibility','visible');
	});
	
	$('.mn_1 > li').mouseout(function(){
		$(this).find('ul').css('visibility','hidden');
	});
	
});
