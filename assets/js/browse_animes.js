function addListeners() {	
	$('.third_paragraph').each(function() {
		var height = $(this).height();
		if(height >= 50) {
			$(this).after("<span class='fa fa-angle-down' onClick='angleDown(this)'></span>");
		} 
		
		$(this).height(55);
	});
	
}

function angleDown(element) {
	var target = $(element).closest(".col-sm-4").find(".anime_body");
	target.css("background-color", "transparent");
	var child = target.find(".anime_poster");
	child.css("margin-top", "23px");
	child = target.find(".anime_synopsis_block");
	child.css("margin-top", "23px");
	$(element).removeClass('fa fa-angle-down').addClass('fa fa-angle-up');
	$(element).attr("onclick","angleUp(this)");
}

function angleUp(element) {
	var target = $(element).closest(".col-sm-4").find(".anime_body");
	target.css("background-color", "transparent");
	var child = target.find(".anime_poster");
	child.css("margin-top", "0px");
	child = target.find(".anime_synopsis_block");
	child.css("margin-top", "0px");
	$(element).removeClass('fa fa-angle-up').addClass('fa fa-angle-down');
	$(element).attr("onclick","angleDown(this)");
}