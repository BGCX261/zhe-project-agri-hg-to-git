// JavaScript Document
function searcha(object,val) {
	document.getElementById(object).style.display = val;
}
$.fn.datebox.defaults.formatter = function(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	m = (m < 10) ? '0'+m : m;
	var d = date.getDate();
	d = (d < 10) ? '0'+d : d;
	return y+'-'+m+'-'+d;
}