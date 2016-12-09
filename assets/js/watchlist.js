$(document).ready(function() {	
	var url = getWatchlistUrl();
	var user_id = getUserId();	
	var star_empty_url_small = getStarEmptyUrl();
	var star_fill_url_small = getStarFillUrl();
	var default_watchlist_page = getDefaultPage();
	var counter = 0;
	
	switch(default_watchlist_page) {
		case 0: 
			$('.watchlist_content').show();		
			$('#all_tab').css("background-color", "#DEDEDE");
			break;
		case 1: 
			$('#watched_content').show();
			$('#watched_tab').css("background-color", "#DEDEDE");
			break;
		case 2: 			
			$('#watching_content').show();
			$('#watching_tab').css("background-color", "#DEDEDE");
			break;
		case 3: 			
			$('#want_watch_content').show();
			$('#want_watch_tab').css("background-color", "#DEDEDE");
			break;
		case 4: 
			$('#stalled').show();
			$('#stalled_tab').css("background-color", "#DEDEDE");
		case 5: 			
			$('#dropped').show();
			$('#dropped_tab').css("background-color", "#DEDEDE");
			break;
		default: 
			$('.watchlist_content').show();
			$('#all_tab').css("background-color", "#DEDEDE");
			break;
	}
	
    $.post(url, {'user_id': user_id},
    function(data){ 
        if (data != "") {  
          $(data).each(function(index, element) {
       	  
        	  counter++;
        	  var status = $(element).data("id");
        	  var score_value = $(element).find('.star-rating').data("id");
        	 
        	  switch(status) {
        	  case 1:
        		  $('#watched_content .table-responsive .table tbody').append(element);
        		  break;
        	  case 2:
        		  $('#watching_content .table-responsive .table tbody').append(element);
        		  break;
        	  case 3:
        		  $('#want_watch_content .table-responsive .table tbody').append(element);
        		  break;
        	  case 4:
        		  $('#stalled_content .table-responsive .table tbody').append(element);
        		  break;
        	  case 5:
        		  $('#dropped_content .table-responsive .table tbody').append(element);
        		  break;
        	  default: 
        		  break;
        	  }
        	  
        	  if(score_value >= 0 && score_value <=10) {    
        		  $('input[name=userScore' + counter + '][value=' + parseInt(score_value) + ']').prop('checked',true);
        	  }
        	  
      	  });
          
                  
      	$('input:radio').change(function() {
    		var value = $(this).val();
    		var url = getScoreUrl();
    		var anime_id = $(this).parent().parent().parent().find('.title').data('id');
    		var self = $(this);
    		
    		 self.parent().parent().next().find('.loader_image_div').css("display", "inline-block");
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
    		var anime_id = $(this).parent().parent().parent().find('.title').data('id');
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
    		        	self.parent().prev().html('Watched<span class="watchlist_caret fa fa-caret-down"></span>');
    		        	self.parent().parent().parent().detach().appendTo('#watched_content .table-responsive .table tbody');
    		        	var anime_progress = self.parent().parent().parent().find('.anime_progress');
    		        	anime_progress.find('.progress_input').val(anime_progress.find('.max_episodes').text().trim());
    		    		break;
    		    	case 2:
    		    		self.parent().prev().html('Watching<span class="watchlist_caret fa fa-caret-down"></span>');
    		    		self.parent().parent().parent().detach().appendTo('#watching_content .table-responsive .table tbody');
    		    		break;
    		    	case 3:
    		    		self.parent().prev().html('Want to Watch<span class="watchlist_caret fa fa-caret-down"></span>');
    		    		self.parent().parent().parent().detach().appendTo('#want_watch_content .table-responsive .table tbody');
    		    		break;
    		    	case 4:
    		    		self.parent().prev().html('Stalled<span class="watchlist_caret fa fa-caret-down"></span>');
    		    		self.parent().parent().parent().detach().appendTo('#stalled_content .table-responsive .table tbody');
    		    		break;
    		    	case 5:
    		    		self.parent().prev().html('Dropped<span class="watchlist_caret fa fa-caret-down"></span>');
    		    		self.parent().parent().parent().detach().appendTo('#dropped_content .table-responsive .table tbody');
    		    		break;
    		    	case 6:
    		    		self.parent().prev().html('Add to Watchlist<span class="watchlist_caret fa fa-caret-down"></span>');
    		    		self.hide();
    		    	default:
    		    		break;
    		    	}
    	    	} else {
    	    		window.alert("Failed to update watchlist");
    	    	}
    	    }); 
    		
    	});
          
  		  $('.rating-bg').css('background', 'url(' + star_empty_url_small + ') repeat-x top left');
  		  $('.rating').css('background', 'url(' + star_fill_url_small + ') repeat-x top left');
  		  $('.star-ratings-sprite').css('background', 'url(' + star_empty_url_small + ') repeat-x');
  		  $('.star-ratings-sprite-rating').css('background', 'url(' + star_fill_url_small + ') repeat-x');
        }
    }); 
	
	$('.filter').click(function() {	
		var filter = $(this).data('id');
		
		$('.filter').css("background-color", "white");
		$(this).css("background-color", "#DEDEDE");
		
		switch(filter) {
		case 0:
			$('.watchlist_content').show();
			break;
		case 1:
			$('.watchlist_content').hide();
			$('#watched_content').show();
			break;
		case 2:
			$('.watchlist_content').hide();
			$('#watching_content').show();
			break;
		case 3:
			$('.watchlist_content').hide();
			$('#want_watch_content').show();
			break;
		case 4:
			$('.watchlist_content').hide();
			$('#stalled_content').show();
			break;
		case 5:
			$('.watchlist_content').hide();
			$('#dropped_content').show();
			break;
		}
		
	});
	
	var same_value = false;

	$('.watchlist_content').on("keydown", ".progress_input", function(e){
		if(e.ctrlKey) {
		} else {
			if(($(this).val().length == 1) && (e.keyCode == 8)) {
				if($(this).val() == 0) {
					same_value = true;
				}
				$(this).val("0");
				return false;
			}
		    if (e.keyCode != 8 && e.keyCode != 0 && ((e.keyCode < 48 || (e.keyCode > 57 && e.keyCode < 96) || e.keyCode > 105) && (e.keyCode != 37) && (e.keyCode != 38) && (e.keyCode != 39) && (e.keyCode != 40))) {
		    	same_value = true;
		    	return false;
		    } else {
		    	if($(this).val() == "0" && (e.keyCode != 37) && (e.keyCode != 38) && (e.keyCode != 39) && (e.keyCode != 40)) {
		    		$(this).val("");
		    	}

		    	if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
		    		var value = $(this).val();
		    		if((e.keyCode >= 96 && e.keyCode <= 105)) {
		    			var user_input = String.fromCharCode(e.keyCode - 48);
		    		} else {
		    			var user_input = String.fromCharCode(e.keyCode);
		    		}		    		
		    		var max_value = $(this).next().text().trim();

		    		if((parseInt(value + user_input) > max_value) && window.getSelection().toString() == "") {
		    			same_value = true;
		    			return false;
		    		}
		    		if(window.getSelection().toString() != "") {
		    			if(parseInt(user_input) > parseInt(max_value)) {
		    				$(this).val(max_value);
		    				return false;
		    			}
		    		}
		    	}
		    }
		}
		
	});
	
	$('.watchlist_content').on("keyup", ".progress_input", function(e){
		var value = $(this).val();
		if((value == "")) {
			$(this).val("0");
		} else {
			 if (e.keyCode != 8 && e.keyCode != 0 && ((e.keyCode < 48 || (e.keyCode > 57 && e.keyCode < 96) || e.keyCode > 105))) {			
			 } else {
	    		var max_value = parseInt($(this).next().text().trim());
	    		value = parseInt(value);			
	    		if(!same_value) {
	    			updateEpisodes($(this), value, max_value);	  
	    		}				  		
			 }
		}
		same_value = false;
	});


	$('.watchlist_content').on("click", ".count_up", function(event){
		var max_value = parseInt($(this).prev().text());
		var value = parseInt($(this).prev().prev().val());
		value++;
		updateEpisodes($(this), value, max_value);
	});
	
	$(window).click(function() {
		$('.watchlist_dropdown').removeClass('w3-show');
	});
	
});

function updateEpisodes(self, eps_watched, max_episodes) {
	if(eps_watched >= 0 && eps_watched <= max_episodes) {
		var url = getUpdateEpsUrl();	
		var anime_id = self.parent().parent().find('.title').data('id');
	
		 self.parent().parent().find('.status').find('.loader_image_div').css("display", "inline-block");
			  $.ajax({
		        method: "POST",
		        url: url,
		        data: { anime_id: anime_id, eps_watched: eps_watched }
		      })
		    .done(function(msg) {
		    	if(msg != "Success") {
		    		window.alert("Failed to update watchlist");
		    	}
		    	setTimeout(function(){  self.parent().parent().find('.status').find('.loader_image_div').hide(); }, 200);	    	
		    }); 
		
		self.parent().find('.progress_input').val(eps_watched);
		
		if(eps_watched == max_episodes) {
			var element = self.parent().parent().find('.status').find('.watchlist_dropdown').find('.watchlist_item[data-id=1]').click();
		}
	}
}
