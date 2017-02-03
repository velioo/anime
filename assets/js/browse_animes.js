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
	
	putNameFilter();
	
	if($('#current_filters').find('.filter').length > 0) {
		$('#current_filters_div').show();
	}
	
	$('#current_filters').bind("DOMSubtreeModified",function(){
		if($('#current_filters').find('.filter').length <= 0) {
			$('#current_filters_div').hide();
		} else {
			$('#current_filters_div').show();
		}
	});
	
	$('#name_search').keyup(function() {
		putNameFilter();
	});	
	
	$("#slider-range").slider({
		  range: true,
		  min: 0,
		  max: 5,
		  step: 0.01,
		  values: [ 0, 5 ],
		  slide: function( event, ui ) {
			$("#avg_amount1").val(ui.values[0]);
			$("#avg_amount2").val(ui.values[1]);
			putAvgFilter(ui.values[0], ui.values[1]);
		  }
	});
	
	var avg_values = getAvgFilterValues();
	$("#avg_amount1").val(avg_values[0]);	
	$("#avg_amount2").val(avg_values[1]);
	$('#slider-range').slider('values',0,avg_values[0]);
	$('#slider-range').slider('values',1,avg_values[1]);	
	putAvgFilter(avg_values[0], avg_values[1]);
	 
	$('.css-label').click(function() {
		if($(this).prev().is(':checked')) {
			removeFilter($(this).text());
		} else {
			addGenreFilter($(this).text());
		}
	});
	
	$('input:radio[name="type"]').change(function() {
		putTypeFilter();
	});
	
	$('#min_episodes').bind('keyup mouseup', function () {
		putEpisodesFilter();
	});
	
	$('#max_episodes').bind('keyup mouseup', function () {
		putEpisodesFilter();
	});
	
	$('#min_year').bind('keyup mouseup', function () {
		putYearsFilter();           
	});
	
	$('#max_year').bind('keyup mouseup', function () {
		putYearsFilter();           
	});
	
	$('#current_filters').on('click', '.filter', function() {
		var self = $(this);
		if(self.hasClass('name_filter')) {
			$('#name_search').val("");
		} else if(self.hasClass('genre_filter')) {
			$('.css-label').each(function() {
				if($(this).text() == self.find('.filter_text').text()) {
					$(this).prev().prop('checked', false);
				}
			});
		} else if(self.hasClass('avg_filter')) {
			$('#slider-range').slider('values',0,0);
			$('#slider-range').slider('values',1,5);
			$("#avg_amount1").val(0);
			$("#avg_amount2").val(5);		
		} else if(self.hasClass('type_filter')) {
			$('.type_div li input').prop('checked', false);
		} else if(self.hasClass('episodes_filter')) {
			if(self.hasClass('min')) {
				$('#min_episodes').val("");
			} else {
				$('#max_episodes').val("");
			}
		} else if(self.hasClass('year_filter')) {
			if(self.hasClass('min')) {
				$('#min_year').val("");
			} else {
				$('#max_year').val("");
			}
		}
		self.remove();
	});
	
	$('.clear_filters').click(function() {
		$('.filter').each(function() {
			$(this).click();
			$('#animes_search_form').submit();
		});
	});
	
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
	    	$('.star-rating').hide();
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
	        	if(last_element.find('.user_score').text() == "") {
	        		last_element.find('.user_score').text(0);
	        	}
		    	switch(status) {
		    	case 1:
		        	$('#watchlist_button').html('Watched <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		        	last_element.find('.user_status').html('<span class="status-square blue"></span>Watched');
		    		break;
		    	case 2:
		    		$('#watchlist_button').html('Watching <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		    		last_element.find('.user_status').html('<span class="status-square green"></span>Watching');
		    		break;
		    	case 3:
		    		$('#watchlist_button').html('Want to Watch <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		    		last_element.find('.user_status').html('<span class="status-square yellow"></span>Want to Watch');
		    		break;
		    	case 4:
		    		$('#watchlist_button').html('Stalled <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		    		last_element.find('.user_status').html('<span class="status-square orange"></span>Stalled');
		    		break;
		    	case 5:
		    		$('#watchlist_button').html('Dropped <span id="watchlist_caret" class="fa fa-caret-down"></span>');
		    		last_element.find('.user_status').html('<span class="status-square red"></span>Dropped');
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

function putNameFilter() {
	var value = $('#name_search').val();
	if(value == "" ) {
		if($('.filter.name_filter').length > 0) {
			$('.filter.name_filter').remove();
		}
	} else {
		if($('.filter.name_filter').length > 0) {
			$('.filter.name_filter').html('Name contains ' + value + ' <span class="delete_filter_span">&#10006;');
		} else {
			$('#current_filters').prepend('<span class="filter name_filter">Name contains ' + value + ' <span class="delete_filter_span">&#10006;</span></span>');
		}		
	}
}

function putAvgFilter(value1, value2) {	
	if(value1 != 0) {
		if($('.filter.avg_filter.greater').length > 0) {
			$('.filter.avg_filter.greater').html('Rating &ge; ' + value1 + ' <span class="delete_filter_span">&#10006;</span>');
		} else {
			$('#current_filters').prepend('<span class="filter avg_filter greater"> Rating &ge; ' + value1 + ' <span class="delete_filter_span">&#10006;</span></span>');
		}
	} else {
		if($('.filter.avg_filter.greater').length > 0) {
			$('.filter.avg_filter.greater').html('Rating &ge; ' + value1 + ' <span class="delete_filter_span">&#10006;</span>');
		}
	}
	
	if(value2 != 5) {
		if($('.filter.avg_filter.less').length > 0) {
			$('.filter.avg_filter.less').html('Rating &le; ' + value2 + ' <span class="delete_filter_span">&#10006;</span>');
		} else {
			$('#current_filters').prepend('<span class="filter avg_filter less"> Rating &le; ' + value2 + ' <span class="delete_filter_span">&#10006;</span></span>');
		}
	} else {
		if($('.filter.avg_filter.less').length > 0) {
			$('.filter.avg_filter.less').html('Rating &le; ' + value2 + ' <span class="delete_filter_span">&#10006;</span>');
		}
	}
}

function putGenresFilter() {	
	$('.css-checkbox').each(function() {
		var value = $(this).next().text();
		if($(this).is(':checked')) {
			$('#current_filters').prepend('<span class="filter genre_filter"><span class="filter_text">' + value + '</span> <span class="delete_filter_span">&#10006;</span></span>');
		}
	});
}

function putTypeFilter() {
	if ($("input[name='type']:checked").val()) {
		var value = $('input[name="type"]:checked', '#animes_search_form').next().text();
		if($('.filter.type_filter').length > 0) {
			$('.filter.type_filter .filter_text').html(value);
		} else {
			$('#current_filters').prepend('<span class="filter type_filter"><span class="filter_text">' + value + '</span> <span class="delete_filter_span">&#10006;</span></span>');
		}
	}
}

function putEpisodesFilter() {
	if($('#min_episodes').val() != "") {
		var value = $('#min_episodes').val();
		if($('.filter.episodes_filter.min').length > 0) {
			$('.filter.episodes_filter.min .filter_text').html("Episodes >= " + value);
		} else {
			$('#current_filters').prepend('<span class="filter episodes_filter min"><span class="filter_text">Episodes >= ' + value + '</span> <span class="delete_filter_span">&#10006;</span></span>');
		}
	} else {
		$('.filter.episodes_filter.min').click();
	}
	if($('#max_episodes').val() != "") {
		var value = $('#max_episodes').val();
		if($('.filter.episodes_filter.max').length > 0) {
			$('.filter.episodes_filter.max .filter_text').html("Episodes <= " + value);
		} else {
			$('#current_filters').prepend('<span class="filter episodes_filter max"><span class="filter_text">Episodes <= ' + value + '</span> <span class="delete_filter_span">&#10006;</span></span>');
		}
	} else {
		$('.filter.episodes_filter.max').click();
	}
}

function putYearsFilter() {
	if($('#min_year').val() != "") {
		var value = $('#min_year').val();
		if($('.filter.year_filter.min').length > 0) {
			$('.filter.year_filter.min .filter_text').html("Year >= " + value);
		} else {
			$('#current_filters').prepend('<span class="filter year_filter min"><span class="filter_text">Year >= ' + value + '</span> <span class="delete_filter_span">&#10006;</span></span>');
		}
	} else {
		$('.filter.year_filter.min').click();
	}
	if($('#max_year').val() != "") {
		var value = $('#max_year').val();
		if($('.filter.year_filter.max').length > 0) {
			$('.filter.year_filter.max .filter_text').html("Year <= " + value);
		} else {
			$('#current_filters').prepend('<span class="filter year_filter max"><span class="filter_text">Year <= ' + value + '</span> <span class="delete_filter_span">&#10006;</span></span>');
		}
	} else {
		$('.filter.year_filter.max').click();
	}
}

function removeFilter(value) {
	$('.filter_text').each(function() {
		if($(this).text() == value) {
			$(this).parent().remove();
		}
	});
}

function addGenreFilter(value) {
	$('#current_filters').prepend('<span class="filter genre_filter"><span class="filter_text">' + value + '</span> <span class="delete_filter_span">&#10006;</span></span>');
}

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

