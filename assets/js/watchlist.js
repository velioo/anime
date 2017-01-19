var sortAsc = 0;
var last_sort_clicked = "";
var click_counter = 0;
$(document).ready(function() {	
	var url = getWatchlistUrl();
	var user_id = getUserId();	
	var star_empty_url_small = getStarEmptyUrl();
	var star_fill_url_small = getStarFillUrl();
	var default_watchlist_page = getDefaultPage();
	var counter = 0;
	var is_you = getIsYou();	
	
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
	
	$('#loader_watchlist_image_div').show();
	setTimeout(function() {
    $.post(url, {'user_id': user_id},
    function(data){ 
        if (data != "") {  
          var counter = 0;
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
    		    	count_animes();
    	    	} else {
    	    		window.alert("Failed to update watchlist");
    	    	}
    	    });    		
    	});
          
  		  $('.rating-bg').css('background', 'url(' + star_empty_url_small + ') repeat-x top left');
  		  $('.rating').css('background', 'url(' + star_fill_url_small + ') repeat-x top left');
  		  $('.star-ratings-sprite').css('background', 'url(' + star_empty_url_small + ') repeat-x');
  		  $('.star-ratings-sprite-rating').css('background', 'url(' + star_fill_url_small + ') repeat-x');
  		 
  		  count_animes();
  		  if(is_you) {
  			  setDefaultSort();
  		  } else {
  			$('.anime_row').css("visibility", "visible");
  		  }
        }
    	
        $('#loader_watchlist_image_div').hide();
    }); 
    
	}, 0);
	
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
		
		count_animes();
		
	});
	
	var delay = (function(){
		  var timer = 0;
		  return function(callback, ms){
		    clearTimeout (timer);
		    timer = setTimeout(callback, ms);
		  };
		})();
	
	$('#watchlist_search').keyup(function() {
		var value = $(this).val();
		  delay(function(){
			if(value != "") {
				$('.watchlist_content .table-responsive .table tbody tr').hide();
				$('.watchlist_content .table-responsive .table tbody tr').each(function() {
					var anime_title = $(this).find('.title a span').text();
					if(anime_title.toUpperCase().indexOf(value.toUpperCase()) != -1){
						$(this).show();
					}
				});
			} else {
				$('.watchlist_content .table-responsive .table tbody tr').show();
			}
			count_animes();
		}, 200);
	});
	
	$('.title_caret').click(function() {
		var sort_by = $(this).parent().find('.title_text').text().toUpperCase();
		var sort_text = "";
		var is_you = getIsYou();
			
		click_counter++;
		
		if(click_counter >= 3) {
			sort_by = "DEFAULT";
			click_counter = 1;
		} 
		
		if(last_sort_clicked != sort_by) {
			sortAsc = 0;
			click_counter = 1;
			$('.title_caret').show();
		}
		
		switch(sort_by) {
		case "TITLE":
			sort_text = "td.title a span";
			break;
		case "TYPE":
			sort_text = "td.type";
			break;
		case "YEAR":
			sort_text = "td.year";
			break;
		case "PROGRESS":
			if(is_you) {
				sort_text = "td.anime_progress .hidden_watched_eps";
			} else {
				sort_text = "td.anime_progress .eps_watched_guest";
			}
			break;
		case "AVG":
			sort_text = "td.avg";
			break;
		case "RATING":
			sort_text = "td.anime_rating .hidden_user_score";
			break;
		default:
			sort_text = "td.title .hidden_row_number";
			break;
		}
		
		if(sortAsc) {
			sortAsc = 0;
			$(this).parent().find('.fa-caret-up').hide();
			$(this).parent().find('.fa-caret-down').show();
		} else {
			sortAsc = 1;
			$(this).parent().find('.fa-caret-down').hide();
			$(this).parent().find('.fa-caret-up').show();
		}	
		
		if(sort_by == "DEFAULT") {
			$('.title_caret').show();
		}
			
		last_sort_clicked = sort_by;		
		
		$('.watchlist_content .table-responsive .table').each(function(){
			$table = $(this);
			$rows = $(this).find('tr');				
			$rows.sort(function (a, b) {
				if(sort_by != "PROGRESS" && sort_by != "YEAR" && sort_by != "RATING" && sort_by != "DEFAULT") {
    				var A = $(a).find(sort_text).text();
    				var B = $(b).find(sort_text).text();
    				
    				if(sortAsc) {
    					if(A < B) {
    						return -1;
    					} 					
    					if(A > B) {
    						return 1;
    					} 
    				} else {
    					if(A > B) {
    						return -1;
    					} 				
    					if(A < B) {
    						return 1;
    					}
    				}
    				
					A = $(a).find('td.title a span').text();
					B = $(b).find('td.title a span').text();      						
					if(A < B) {
						return -1;
					} 					
					if(A > B) {
						return 1;
					}
    				
    				return 0;
				} else {
    				var A = parseInt($(a).find(sort_text).text());
    				var B = parseInt($(b).find(sort_text).text());
        				
    				if(sortAsc) {
    					if(A > B) {
    				        return 1;
    					} else if(A < B ) {
    						return -1;
    					}      					
    				} else {
    					if(A > B) {
    						return -1;
    					} else if(A < B) {
    						return 1;
    					}
    				}
    				
					A = $(a).find('td.title a span').text();
					B = $(b).find('td.title a span').text();      						
					if(A < B) {
						return -1;
					} 					
					if(A > B) {
						return 1;
					}
					
					return 0;
				}
			}).appendTo($table);					
		});
		
		if(is_you) {		
			var url = getUpdateDefaultWatchlistSortUrl();
			var default_watchlist_sort = sort_by + " " + sortAsc;
			$.ajax({
		        method: "POST",
		        url: url,
		        data: { default_watchlist_sort: default_watchlist_sort }
		      })
		    .done(function(msg) {	    	
		    }); 
		}
		
	});
	
	var same_value = false;

	$('.watchlist_content').on("keydown", ".progress_input", function(e){
		if(e.keyCode == 86) {
			if(e.ctrlKey) {
				console.log("ctrl + v click");
				return true;
			}
		} else {
			if(e.ctrlKey) {
				return true;
			} 
		}
		
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

	    		if(max_value != "?") {	    		
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
			 if ((e.keyCode != 8 && e.keyCode != 0 && ((e.keyCode < 48 || (e.keyCode > 57 && e.keyCode < 96) || e.keyCode > 105))) && (e.keyCode != 86 )) {			
			 } else {
	    		var max_value = $(this).next().text().trim();
	    		value = parseInt(value);	
	    		
	    		if(max_value != '?'){	    		
		    		max_value = parseInt(max_value);		    				    				
		    		if(!same_value) {
		    			updateEpisodes($(this), value, max_value);	  
		    		}		
	    		} else {
	    			if(!same_value) {
		    			max_value = '?';
		    			updateEpisodes($(this), value, max_value);
	    			}
	    		}
			 }
		}
		same_value = false;
	});


	$('.watchlist_content').on("click", ".count_up", function(event){
		var max_value = $(this).prev().text();
		var value = parseInt($(this).prev().prev().val());
		value++;
		if(max_value != '?') {
			max_value = parseInt(max_value);			
			updateEpisodes($(this), value, max_value);
		} else {
			max_value = '?';
			updateEpisodes($(this), value, max_value);
		}
	});
	
	$(window).click(function() {
		$('.watchlist_dropdown').removeClass('w3-show');
	});
	
});

function updateEpisodes(self, eps_watched, max_episodes) {
	if((eps_watched >= 0 && eps_watched <= max_episodes) || max_episodes == '?') {
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
		self.parent().find('.hidden_watched_eps').text(eps_watched);
		
		if(eps_watched == max_episodes) {
			var element = self.parent().parent().find('.status').find('.watchlist_dropdown').find('.watchlist_item[data-id=1]').click();
			count_animes();
		}
	}
}

function count_animes() {
	$('.watchlist_content').each(function() {
		$(this).find('.anime_count').text($(this).find('.table-responsive .table tr:visible').length);
	});
	setDefaultSort();
}

function setDefaultSort() {
	var default_watchlist_sort;
	var url = getDefaultWatchlistSortUrl()
	
$.post(url, {},
    function(data){
	 	var temp_data;
        if (data != "") {        	
        	default_watchlist_sort = data;	           	
        	var split_sort_by_order = default_watchlist_sort.split(" ");
        	var sort_text;
        	sortAsc = parseInt(split_sort_by_order[1]);
        	var is_you = getIsYou();
        	var sort_by = split_sort_by_order[0];
        	
        	switch(sort_by) {
        	case "TITLE":
        		sort_text = "td.title a span";
        		if(sortAsc) {
        			$('#title_sort').next().hide();
        		} else {
        			$('#title_sort').hide();
        		}
        		break;
        	case "TYPE":
        		sort_text = "td.type";
        		if(sortAsc) {
        			$('#type_sort').next().hide();
        		} else {
        			$('#type_sort').hide();
        		}
        		break;
        	case "YEAR":
        		sort_text = "td.year";
        		if(sortAsc) {
        			$('#year_sort').next().hide();
        		} else {
        			$('#year_sort').hide();
        		}
        		break;
        	case "PROGRESS":
        		if(is_you) {
        			sort_text = "td.anime_progress .hidden_watched_eps";
        		} else {
        			sort_text = "td.anime_progress .eps_watched_guest";
        		}		
        		if(sortAsc) {
        			$('#progress_sort').next().hide();
        		} else {
        			$('#progress_sort').hide();
        		}
        		break;
        	case "AVG":
        		sort_text = "td.avg";
        		if(sortAsc) {
        			$('#avg_sort').next().hide();
        		} else {
        			$('#avg_sort').hide();
        		}
        		break;
        	case "RATING":
        		sort_text = "td.anime_rating .hidden_user_score";
        		if(sortAsc) {
        			$('#rating_sort').next().hide();
        		} else {
        			$('#rating_sort').hide();
        		}
        		break;
        	default:
        		sort_text = "td.title .hidden_row_number";
        		break;
        	}	
        	
        	if(sortAsc) {
        		click_counter = 1;
        		last_sort_clicked = sort_by;
        	} else {
        		click_counter = 2;
        	}
        	
        	$('.watchlist_content .table-responsive .table').each(function(){
        		$table = $(this);
        		$rows = $(this).find('tr');	
        		$rows.sort(function (a, b) {
        			if(sort_by != "PROGRESS" && sort_by != "YEAR" && sort_by != "RATING" && sort_by != "DEFAULT") {
        				var A = $(a).find(sort_text).text();
        				var B = $(b).find(sort_text).text();
        				
        				if(sortAsc) {
        					if(A < B) {
        						return -1;
        					} 					
        					if(A > B) {
        						return 1;
        					} 
        				} else {
        					if(A > B) {
        						return -1;
        					} 				
        					if(A < B) {
        						return 1;
        					}
        				}
        				
						A = $(a).find('td.title a span').text();
						B = $(b).find('td.title a span').text();      						
						if(A < B) {
    						return -1;
    					} 					
    					if(A > B) {
    						return 1;
    					}
        				
        				return 0;
        			} else {
        				var A = parseInt($(a).find(sort_text).text());
        				var B = parseInt($(b).find(sort_text).text());
            				
        				if(sortAsc) {
        					if(A > B) {
        				        return 1;
        					} else if(A < B ) {
        						return -1;
        					}      					
        				} else {
        					if(A > B) {
        						return -1;
        					} else if(A < B) {
        						return 1;
        					}
        				}
        				
						A = $(a).find('td.title a span').text();
						B = $(b).find('td.title a span').text();      						
						if(A < B) {
    						return -1;
    					} 					
    					if(A > B) {
    						return 1;
    					}
    					
    					return 0;
        			}
        		}).appendTo($table);	
        	});
        	$('.anime_row').css("visibility", "visible");
        }	
  });	
}