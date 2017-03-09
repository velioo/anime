$(document).ready(function() {	

	$('input:radio').change(function() {
		var value = $(this).val();
		var url = getScoreUrl();
		var anime_id = $(this).parent().data('id');
		var self = $(this);
		
		 self.parent().parent().next().find('.loader_image_div').show();
		  $.ajax({
		        method: "POST",
		        url: url,
		        data: { anime_id: anime_id, value: value }
		      })
		    .done(function(msg) {
		    	if(msg != "Success") {
		    		window.alert("Failed to update watchlist");
		    	}
		    	setTimeout(function(){ self.parent().parent().next().find('.loader_image_div').hide(); }, 200);	    	
		    });  		
	
	});
	
	$(".watchlist_button").click(function(e){
		e.stopPropagation();
		var watchlist = $(this).parent().find('.watchlist_dropdown');
	    if (watchlist.hasClass('w3-show')) {
	    	watchlist.removeClass('w3-show');
	    } else { 
	    	watchlist.addClass('w3-show');
	    }
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
		    	switch(status) {
		    	case 1:
		    		self.parent().prev().html('<span class="status-square blue"></span>Watched<span class="watchlist_caret fa fa-caret-down"></span>');
		    		break;
		    	case 2:
		    		self.parent().prev().html('<span class="status-square green"></span>Watching<span class="watchlist_caret fa fa-caret-down"></span>');
		    		break;
		    	case 3:
		    		self.parent().prev().html('<span class="status-square yellow"></span>Want to Watch<span class="watchlist_caret fa fa-caret-down"></span>');
		    		break;
		    	case 4:
		    		self.parent().prev().html('<span class="status-square orange"></span>Stalled<span class="watchlist_caret fa fa-caret-down"></span>');
		    		break;
		    	case 5:
		    		self.parent().prev().html('<span class="status-square red"></span>Dropped<span class="watchlist_caret fa fa-caret-down"></span>');
		    		break;
		    	case 6:
		    		self.parent().prev().html('Add to Watchlist<span class="watchlist_caret fa fa-caret-down"></span>');
		    	default:
		    		break;
		    	}
	    	} else {
	    		window.alert("Failed to update watchlist");
	    	}
	    }); 
	    
	    if(status >= 1 && status <=5) {
	    	self.parent().parent().prev().find('.star-rating').css("display", "inline-block");
	    	self.parent().children().show();
	    } else {
	    	self.parent().parent().prev().find('.star-rating').hide();
	    }
	});
	
	$(window).click(function() {
		$('.watchlist_dropdown').removeClass('w3-show');
	});

});