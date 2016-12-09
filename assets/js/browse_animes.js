function addListeners() {	
	$('.measure_paragraph').each(function() {
		var height = $(this).height();
		if(height >= 50) {
			$(this).after("<span class='fa fa-angle-down' onClick='angleDown(this)'></span>");
		} 
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

$(document).ready(function() {
	
	var last_element;
	
	$(window).click(function() {
		$('#watchlist_dropdown').removeClass('w3-show');
	});

	$("#watchlist_button").click(function(e){
		e.stopPropagation();
		var watchlist = $('#watchlist_dropdown');
	    if (watchlist.hasClass('w3-show')) {
	    	watchlist.removeClass('w3-show');
	    } else { 
	    	watchlist.addClass('w3-show');
	    }
	});
	
	$('.anime_user_status_paragraph').click(function(e){
		last_element = $(this);
		
	    var anime_id = $(this).data('id');	    
	    var anime_name = $(this).parent().parent().find('.title_paragraph a').text();

	    $('#anime_name').text(anime_name); 
	    
	    $('#watchlist_dropdown').data('id', anime_id);

	    var user_anime_status = $(this).parent().find('.user_status').text();
	    var user_anime_score = $(this).parent().find('.user_score').data('id');

	    if(user_anime_status != "" && user_anime_status != "Add") {	    
	    	$('#watchlist_button').html(user_anime_status + '<span class="watchlist_caret fa fa-caret-down"></span>');
	    } else {
	    	$('#watchlist_button').html('Add to Watchlist <span class="watchlist_caret fa fa-caret-down"></span>');
	    }
	    $('input[name=userScore][value=' + user_anime_score + ']').prop('checked',true);
	    
	    $('#change_status_modal').modal('show');
	    
	    $('body').css("padding-right", "0");
	});
	
	$('.watchlist_item').click(function() {
		var status = $(this).data('id');
		var url = getStatusUrl();
		var anime_id = $(this).parent().data('id');
		var self = $(this);
	
	    $.ajax({
	        method: "POST",
	        url: url,
	        data: { anime_id: anime_id, status: status }
	      })
	    .done(function(msg) {
	    	if(msg == "Success") {
	        	last_element.find('.user_status').data('id', status);
	        	last_element.find('img').show();
		    	switch(status) {
		    	case 1:
		        	$('#watchlist_button').html('Watched <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		        	last_element.find('.user_status').text('Watched');
		    		break;
		    	case 2:
		    		$('#watchlist_button').html('Watching <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		    		last_element.find('.user_status').text('Watching');
		    		break;
		    	case 3:
		    		$('#watchlist_button').html('Want to Watch <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		    		last_element.find('.user_status').text('Want to Watch');
		    		break;
		    	case 4:
		    		$('#watchlist_button').html('Stalled <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		    		last_element.find('.user_status').text('Stalled');
		    		break;
		    	case 5:
		    		$('#watchlist_button').html('Dropped <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		    		last_element.find('.user_status').text('Dropped');
		    		break;
		    	case 6:
		    		$('#watchlist_button').html('Add to Watchlist <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		    		last_element.find('.user_status').text('Add');
		    		last_element.find('.user_score').text('');
		    		last_element.find('img').hide();
		    		self.hide();
		    	default:
		    		break;
		    	}
	    	} else {
	    		window.alert("Failed to update watchlist");
	    	}
	    }); 
	    
	    if(status >= 1 && status <=5) {
	    	$('.star-rating').css("display", "inline-block");
	    	self.parent().children().show();
	    } else {
	    	$('.star-rating').hide();
	    }
		
	});
	
	$('input[type=radio][name=userScore]').change(function() {
		var value = this.value;
		var url = getScoreUrl();
		var anime_id = $(this).parent().parent().find('#watchlist_dropdown').data('id');
		
		$('#loader_image_div').css("display", "inline-block");
		  $.ajax({
		        method: "POST",
		        url: url,
		        data: { anime_id: anime_id, value: value }
		      })
		    .done(function(msg) {
		    	if(msg == "Success") {
		    		last_element.find('.user_score').data('id', value);		    		
		    		last_element.find('.user_score').text(value/2);		    		
		    	} else {
		    		window.alert("Failed to update watchlist");
		    	}
		    	setTimeout(function(){ $('#loader_image_div').hide(); }, 200);	    	
		    }); 
		
	});
	
});