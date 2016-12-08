var twoMB = 2097152;

function editUserInfo(file_chosen, filesize) {
	var max_offset = 1000;
	var margin = 3;
	if(file_chosen == true) {
		margin = filesize/100000;
	}
	
	$('#edit_cover_label').css("display", "inline-block");
	$('#edit_avatar_span').css("display", "block");
	$('#user_image').css("filter", "brightness(50%)");
	$('#submit_info').css("display", "inline-block");
	$('#show_edits').css("display", "none");
	$('#user-bar').css("cursor", "move");				
	$('#user-bar').mousedown(function(e){
	    var prevY = e.clientY;
	    $(this).mousemove(function(e){
	      var offset = $('#top_offset').val();
	      if(isNaN(offset)) {
	    	  offset = 1;
	      } else {
	    	  offset = parseInt(offset);
	      }
	      if(e.clientY < prevY) { 
			  if(offset >= max_offset) {
			    offset = max_offset;
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
	      
	       $("#top_offset").attr("value", offset);
		   changeCoverPosition("user-bar");
	      prevY = e.clientY;
	    });
	});     
	$('body').mouseup(function(){
	  $('#user-bar').unbind("mousemove");
	});      
}

function updateDb(baseurl) {
    var textValue = $("#user_description_area").val();
    var dayValue = $('#day_edit').val();
    var monthValue = $('#month_edit').val();
    var yearValue = $('#year_edit').val();
    var birthValue = yearValue + "-" + monthValue + "-" + dayValue;
	var genderValue = $("#gender_edit").val();
	var locationValue = $("#location_edit").val();
	var url = getUserUpdatesUrl();

    $.ajax({
          method: "POST",
          url: url,
          data: { textAreaValue: textValue, birthValue: birthValue, gender: genderValue, location: locationValue }
        })
      .done(function( msg ) {	  
      });    
     showUpdateUserInfoContent();
};

$(document).ready(function() {	
	$("#user_description_area").keyup(function(){
		  $("#user_description_area_char_count").text("Left: " + (500 - $(this).val().length));
	});
	
	$('#edit_cover_button').change(function(){
		var file = this.files[0]
		var size = file.size;
		var cover_button = $(this);	
		
		if(size > twoMB) {
			if($('.error').length <= 0) {
				$("<p class='error'>Yout file is too large. Max size 2MB</p>").insertAfter('#edit_avatar_button');
			}
	        cover_button.replaceWith(cover_button = cover_button.clone(true));
		} else {
			if($('.error').length > 0) {
				$('.error').remove();
			}
			if($('#edit_cover_button').val() != "") {		
				if($('#user-bar').length > 0) {
					showCover(this, "#user-bar");
					editUserInfo(true, size);
				} else {
					showCover(this, "#anime-bar");
					editAnimeInfo(true, size);					
				}	
			}	
		}
	});
	
	$('#edit_avatar_span').click(function() {
		$('#edit_avatar_button').click();
	});

	$('#edit_avatar_button').change(function(){
		if($('#edit_avatar_button').val() != "") {					
			showCover(this, "#user_image");
		}	
	});	

});

function changeCoverPosition(bar) {
	var offset = $('#top_offset').val();
	$('#' + bar).css("background-position", "0px" + " -" + (offset) + "px" )
}

function showCover(input, bar) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
        	if(bar != "#user_image" && bar != "#poster_image") {
        		$(bar).css('background-image', 'url(' + e.target.result + ')');
        	} else {
        		$(bar).attr('src', e.target.result);
        	}       	
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function showUserInfoEdit() {
	$('#show_content_div').css('display', 'none');
	$('#edit_user_info').css('display', 'none');
	$('#save_user_info').css('display', 'inline-block');
	$('#edit_content_div').css('display', 'block');
	$("#user_description_area_char_count").text("Left: " + (500 - $('#user_description_area').val().length));
}

function showUpdateUserInfoContent() {
	$('#show_content_div').css('display', 'block');
	$('#edit_user_info').css('display', 'block');
	$('#save_user_info').css('display', 'none');
	$('#edit_content_div').css('display', 'none');
	$("#user_description").text($("#user_description_area").val());
	
	var gender_icon;
	var gender = $("#gender_edit").val();

	if(gender == "male") {
		gender_icon = "<i class=\"fa fa-mars\"></i>";
	} else if(gender == "female") {
		gender_icon = "<i class=\"fa fa-venus\"></i>";
	} else {
		gender_icon = "<i class=\"fa fa-genderless\"></i>";
	}
	
	var gender = "Gender: " + gender_icon;	
	$("#gender").html(gender);	
	
	var location = $("#location_edit").val();
	
	var country_content = "<i class=\"fa fa-home\"></i> Lives in: <strong>" + location + "</strong>";
	$("#country").html(country_content);
}