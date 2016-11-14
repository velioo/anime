if($('#poster_image').css("width") > 0)
	$('#type_episodes_div').css("width", image_width);

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
	}, 250);
});

var video_url = $("#anime_video").attr('src');
$("#anime_video").attr('src', '');

$('#close_youtube_video_button').click(function() {
	$("#center_video_div").css("-webkit-animation-name", "zoom_out");
	$("#center_video_div").css("animation-name", "zoom_out");
	$("#center_video_div").css("-webkit-animation-duration", "0.3s");
	$("#center_video_div").css("animation-duration", "0.3s");
	setTimeout(function(){
    	 $('#youtube_modal').css("display", "none");
    	 $('#anime_video').replaceWith("<iframe id='anime_video' width='1200' height='700' src='' frameborder='0' allowfullscreen></iframe>");	    	 
		 $("#center_video_div").css("-webkit-animation-name", "zoom");
		 $("#center_video_div").css("animation-name", "zoom");
		 $("#center_video_div").css("-webkit-animation-duration", "0.6s");
		 $("#center_video_div").css("animation-duration", "0.6s");
	}, 250);
});

$('#show_video').click(function() {
	 $('#youtube_modal').css("display", "block");
	 $("#anime_video").attr('src', video_url);
});

function editAnimeInfo(file_chosen, filesize) {
	var height = window.innerHeight;
	var margin = 1;
	if(file_chosen == true) {
		margin = filesize/120000;
	}
	document.getElementById("edit_cover_label").style.display = "block";
	document.getElementById("submit_info").style.display = "block";		
	document.getElementById("show_edits").style.display = "none";		
	document.getElementById("anime-bar").style.cursor = "move";					
	$('#anime-bar').mousedown(function(e){
	    var prevY = e.clientY;
	    $(this).mousemove(function(e){
	      var offset = document.getElementById("top_offset").getAttribute("value");
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
		   document.getElementById("top_offset").setAttribute("value", offset);
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
	
	        var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<p class="morelink">' + moretext + '</p></span>';
	
	        $(this).html(html);
	    }
	
	});
	
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

