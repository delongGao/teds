$(document).ready(function() {
	// add more field groups for artifacts, personas, scenarios
	$(".addmore").on('click',function(){
		var newBox = $(this).next("div").find("div:eq(0)");
		$(this).next("div").append( newBox.clone().fadeIn() ); 
		// add remove button
		var removeButton = $('<div class="removeThis btn btn-sm btn-danger">X</div>');
		$(this).next('div').find('div:last').append(removeButton);
		removeButton.bind('click', removeThis);
		return false;
	});
	
	$("#category .chkAll").on('click',function(){
		console.log("hit")
		console.log($(this).parents(".columns").children(":checkbox"));
	});
});

function removeThis() {
	$(this).parent('div').remove();
}