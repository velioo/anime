show_hide_text();

$("#poster_image").click(function(){
	$('#anime_modal').css("display", "block");
    $("#modal_image").attr("src", $(this).attr("src"));
});

$('#close_modal').click(function(){
	$("#center_div").css("-webkit-animation-name", "zoom_out");
	$("#center_div").css("animation-name", "zoom_out");
	$("#center_div").css("-webkit-animation-duration", "0.3s");
	$("#center_div").css("animation-duration", "0.3s");
	setTimeout(function(){
		 $('#anime_modal').css("display", "none"); 
		 $("#center_div").css("-webkit-animation-name", "zoom");
		 $("#center_div").css("animation-name", "zoom");
		 $("#center_div").css("-webkit-animation-duration", "0.6s");
		 $("#center_div").css("animation-duration", "0.6s");
	}, 150);
});

var video_url = $("#anime_video").attr('src');
$("#anime_video").attr('src', '');

$('#close_youtube_video_button').click(function() {
	$("#center_video_div").css("-webkit-animation-name", "zoom_out");
	$("#center_video_div").css("animation-name", "zoom_out");
	$("#center_video_div").css("-webkit-animation-duration", "0.2s");
	$("#center_video_div").css("animation-duration", "0.2s");
	setTimeout(function(){
    	 $('#youtube_modal').css("display", "none");
    	 $('#anime_video').replaceWith("<iframe id='anime_video' width='1200' height='700' src='' frameborder='0' allowfullscreen></iframe>");	    	 
		 $("#center_video_div").css("-webkit-animation-name", "zoom");
		 $("#center_video_div").css("animation-name", "zoom");
		 $("#center_video_div").css("-webkit-animation-duration", "0.6s");
		 $("#center_video_div").css("animation-duration", "0.6s");
	}, 150);
});

$('#show_video').click(function() {
	 $('#youtube_modal').css("display", "block");
	 $("#anime_video").attr('src', video_url);
});

$('#edit_poster_span').click(function() {
	$('#edit_poster_button').click();
});

$('#edit_poster_button').change(function(){
	if($('#edit_poster_button').val() != "") {					
		showCover(this, "#poster_image");
	}	
});	

function editAnimeInfo(file_chosen, filesize) {
	var height = window.innerHeight;
	var margin = 3;
	if(file_chosen == true) {
		margin = filesize/120000;
	}
	$('#edit_cover_label').css("display", "inline-block");
	$('#submit_info').css("display", "inline-block");
	$('#edit_poster_span').css("display", "block");
	$('#poster_image').css("filter", "brightness(50%)");
	$('#show_edits').css("display", "none");
	$('#anime-bar').css("cursor", "move");	
	$('#anime-bar').mousedown(function(e){
	    var prevY = e.clientY;
	    $(this).mousemove(function(e){
	      var offset = $('#top_offset').val();
	      if(isNaN(offset)) {
	    	  offset = 1;
	      } else {
	    	  offset = parseInt(offset);
	      }
	      if(e.clientY < prevY) { 
			  if(offset >= 630) {
			    offset = 630;
			  } else {
			    offset+=margin;
			  }	
		   }
	      if(e.clientY > prevY) { 
			   if(offset <= 0) {
			     offset = 0;
			    } else {
			     offset-=margin;
			   }
		   }
	       $('#top_offset').attr("value", offset);
		   changeCoverPosition("anime-bar");
	      prevY = e.clientY;
	    });
	});     
	$('#anime-bar').mouseup(function(){
	  $(this).unbind("mousemove");
	});      
}

function show_hide_text() {
	var showChar = 350;
	var ellipsestext = "...";
	var moretext = "Show more";
	var lesstext = "Show less";
	
	$('.more').each(function() {
	    var content = $(this).html();
	
	    if(content.length > showChar) {
	
	        var c = content.substr(0, showChar);
	        var h = content.substr(showChar, content.length - showChar);
	
	        var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<p class="morelink red-text">' + moretext + '</p></span>';
	
	        $(this).html(html);
	    }
	
	});
	
	$('#synopsis_div').show();
	$('#anime_genres_div').show();
	$('#anime_user_watchlist_div').show();
	
	$(".morelink").click(function(){
	    if($(this).hasClass("less")) {
	        $(this).removeClass("less");
	        $(this).html(moretext);
	    } else {
	        $(this).addClass("less");
	        $(this).html(lesstext);
	    }
	    $(this).parent().prev().toggle();
	    $(this).prev().toggle();
	    return false;
	});
}

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

$('.watchlist_item').click(function() {
	var status = $(this).data('id');
	var url = getStatusUrl();
	var anime_id = getAnimeId();
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
	        	$('#watchlist_button').html('Watched<span id="watchlist_caret" class="fa fa-caret-down"></span>');
	    		break;
	    	case 2:
	    		$('#watchlist_button').html('Watching<span id="watchlist_caret" class="fa fa-caret-down"></span>');
	    		break;
	    	case 3:
	    		$('#watchlist_button').html('Want to Watch<span id="watchlist_caret" class="fa fa-caret-down"></span>');
	    		break;
	    	case 4:
	    		$('#watchlist_button').html('Stalled<span id="watchlist_caret" class="fa fa-caret-down"></span>');
	    		break;
	    	case 5:
	    		$('#watchlist_button').html('Dropped<span id="watchlist_caret" class="fa fa-caret-down"></span>');
	    		break;
	    	case 6:
	    		$('#watchlist_button').html('Add to Watchlist<span id="watchlist_caret" class="fa fa-caret-down"></span>');
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
	var anime_id = getAnimeId();
	$('#loader_image_div').css("display", "inline-block");
	  $.ajax({
	        method: "POST",
	        url: url,
	        data: { anime_id: anime_id, value: value }
	      })
	    .done(function(msg) {
	    	if(msg != "Success") {
	    		window.alert("Failed to update watchlist");
	    	}
	    	setTimeout(function(){ $('#loader_image_div').hide(); }, 200);	    	
	    }); 
	
});


