function editUserInfo(file_chosen, filesize) {
	var height = window.innerHeight;
	var margin = 1;
	if(file_chosen == true) {
		margin = filesize/120000;
	}
	document.getElementById("edit_cover_label").style.display = "block";
	document.getElementById("edit_avatar_label").style.display = "block";
	document.getElementById("submit_info").style.display = "block";		
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
		   changeCoverPosition();
	      prevY = e.clientY;
	    });
	});     
	$('#user-bar').mouseup(function(){
	  $(this).unbind("mousemove");
	});      

}

function updateDb(baseurl) {
    var textValue = document.getElementById("user_description_area").value;
	var ageValue = document.getElementById("age_edit").value;
	var genderValue = document.getElementById("gender_edit").value;
	var locationValue = document.getElementById("location_edit").value;
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
		document.getElementById("wtf_age").style.display = "inline";
		$("#wtf_age").text(" Baka...");
	}
};

function fix_error_messages() {
	var e = document.getElementsByClassName("error");
	for (i = 0; i < e.length; i++) {
		e[i].style.marginTop = "155px";
	}
	 e = document.getElementsByClassName("error_a");
	for (i = 0; i < e.length; i++) {
		e[i].style.marginTop = "155px";
	}
}

$(document).ready(function() {	
	$("#user_description_area").keyup(function(){
		  $("#user_description_area_char_count").text("Left: " + (500 - $(this).val().length));
	});
	$('#edit_cover_button').change(function(){
		var file = this.files[0]
		var size = file.size;
		if(document.getElementById("edit_cover_button").value != "") {					
			editUserInfo(true, size);	
			showCover(this);
		}	
	});

	$('#edit_avatar_button').change(function(){
		if(document.getElementById("edit_avatar_button").value != "") {					
			showAvatar(this);
		}	
	});	
});

function changeCoverPosition() {
	var width = window.innerWidth;
	var offset = document.getElementById("top_offset").getAttribute("value");
	document.getElementById("user-bar").style.backgroundPosition = "0px" + " -" + (offset) + "px";	
}

function showCover(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
        	$('#user-bar').css('background-image', 'url(' + e.target.result + ')');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function showAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
        	$('#user_image').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function showUserInfoEdit() {
	document.getElementById("show_content_div").style.display = "none";
	document.getElementById("edit_user_info").style.display = "none";
	document.getElementById("save_user_info").style.display = "inline-block";
	document.getElementById("edit_content_div").style.display = "block";
	$("#user_description_area_char_count").text("Left: " + (500 - $('#user_description_area').val().length));
}

function showUpdateUserInfoContent() {
	document.getElementById("show_content_div").style.display = "block";
	document.getElementById("edit_user_info").style.display = "block";
	document.getElementById("save_user_info").style.display = "none";
	document.getElementById("edit_content_div").style.display = "none";
	document.getElementById("wtf_age").style.display = "none";
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