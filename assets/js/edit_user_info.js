function editUserInfo(file_chosen, filesize) {
	var height = window.innerHeight;
	var margin = 1;
	if(file_chosen == true) {
		margin = filesize/120000;
	}
	
	document.getElementById("edit_cover_label").style.display = "inline-block";
	document.getElementById("edit_avatar_label").style.display = "inline-block";
	document.getElementById("submit_info").style.display = "inline-block";		
	document.getElementById("show_edits").style.display = "none";		
	document.getElementById("user-bar").style.cursor = "move";					
	$('#user-bar').mousedown(function(e){
	    var prevY = e.clientY;
	    $(this).mousemove(function(e){
	      var offset = document.getElementById("top_offset").getAttribute("value");
	      if(isNaN(offset)) {
	    	  offset = 1;
	      } else {
	    	  offset = parseInt(offset);
	      }
	      if(e.clientY < prevY) { 
			  if(offset >= 580) {
			    offset = 580;
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
	$('#user-bar').mouseup(function(){
	  $(this).unbind("mousemove");
	});      
}

function updateDb(baseurl) {
    var textValue = $("#user_description_area").val();
	var ageValue = $("#age_edit").val();
	var genderValue = $("#gender_edit").val();
	var locationValue = $("#location_edit").val();
    if(!(ageValue <= 0)){
	    $.ajax({
	          method: "POST",
	          url: baseurl + '/userupdates/update_user_info',
	          data: { textAreaValue: textValue, age: ageValue, gender: genderValue, location: locationValue }
	        })
	      .done(function( msg ) {	  
	      });    
	     showUpdateUserInfoContent();
	} else {
		$("#wtf_age").css('display', 'inline');
		$("#wtf_age").text(" Baka...");
	}
};

/*function fix_error_messages() {
	var e = document.getElementsByClassName("error");
	for (i = 0; i < e.length; i++) {
		e[i].style.marginTop = "155px";
	}
	 e = document.getElementsByClassName("error_a");
	for (i = 0; i < e.length; i++) {
		e[i].style.marginTop = "155px";
	}
}*/

$(document).ready(function() {	
	$("#user_description_area").keyup(function(){
		  $("#user_description_area_char_count").text("Left: " + (500 - $(this).val().length));
	});
	
	$('#edit_cover_button').change(function(){
		var file = this.files[0]
		var size = file.size;
		if(document.getElementById("edit_cover_button").value != "") {		
			if($('#user-bar').length > 0) {
				editUserInfo(true, size);	
				showCover(this, "#user-bar");
			} else {
				editAnimeInfo(true, size);	
				showCover(this, "#anime-bar");
			}
			
		}	
	});

	$('#edit_avatar_button').change(function(){
		if(document.getElementById("edit_avatar_button").value != "") {					
			showCover(this, "#user_image");
		}	
	});	
});

function changeCoverPosition(bar) {
	var width = window.innerWidth;
	var offset = document.getElementById("top_offset").getAttribute("value");
	document.getElementById(bar).style.backgroundPosition = "0px" + " -" + (offset) + "px";	
}

function showCover(input, bar) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
        	if(bar != "#user_image") {
        		$(bar).css('background-image', 'url(' + e.target.result + ')');
        	} else {
        		$('#user_image').attr('src', e.target.result);
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
	$('#wtf_age').css('display', 'none');
	$("#user_description").text($("#user_description_area").val());
	$("#age").text("Age: " + $("#age_edit").val());
	
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