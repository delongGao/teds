$(document).ready(function() {
	// add more field groups for artifacts, personas, scenarios
	$(".addmore").on('click',function(){
		var newBox = $(this).next("div").find("div:eq(0)");
		$(this).next("div").append( newBox.clone().fadeIn() ); 
		return false;
	});
	
	$("#category .chkAll").on('click',function(){
		console.log("hit")
		console.log($(this).parents(".columns").children(":checkbox"));
	});
		
});