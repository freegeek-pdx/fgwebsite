$(document).ready(function(){
	$("div.hide_show_area '.$ele.' + div").hide();
	$("div.hide_show_area '.$ele.'").click(function () { 
		$(this).next("div").slideToggle("fast"); 
	});
});