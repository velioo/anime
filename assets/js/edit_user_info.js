function showEditFields() {
	editUserInfo(false, 0);		
	$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/temp_disable_selection.css";?>" type="text/css" />');
	
	var e = document.getElementsByClassName("error");
	for (i = 0; i < e.length; i++) {
		e[i].style.marginTop = "155px";
	}
	 e = document.getElementsByClassName("error_a");
	for (i = 0; i < e.length; i++) {
		e[i].style.marginTop = "155px";
	}
	
}

function editUserInfo(file_chosen, filesize) {
	var height = window.innerHeight;
	var margin = height/1700000;
	if(file_chosen == true) {
		margin = filesize/120000000;
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
	    	  offset = 0.01;
	      } else {
	    	  offset = parseFloat(offset);
	      }
	      if(e.clientY < prevY) { 
			  if(offset >= 0.3) {
			    offset = 0.3;
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
$(document).ready(function() {
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
	document.getElementById("user-bar").style.backgroundPosition = "0px" + " -" + (offset* width) + "px";	
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
}

function showUpdateUserInfoContent() {
	document.getElementById("show_content_div").style.display = "block";
	document.getElementById("edit_user_info").style.display = "block";
	document.getElementById("save_user_info").style.display = "none";
	document.getElementById("edit_content_div").style.display = "none";
	document.getElementById("user_description").innerHTML = document.getElementById("user_description_area").value;
	document.getElementById("age").innerHTML = "Age: " + document.getElementById("age_edit").value;
	var gender_icon;
	var gender = document.getElementById("gender_edit").value;
	if(gender == "male") {
		gender_icon = "<i class=\"fa fa-mars\"></i>";
	}else if(gender == "female") {
		gender_icon = "<i class=\"fa fa-venus\"></i>";
	} else {
		gender_icon = "<i class=\"fa fa-genderless\"></i>";
	}
	var gender_title = "Gender: ";
	var res = gender_title.concat(gender_icon);
	document.getElementById("gender").innerHTML = res;	
	document.getElementById("country").innerHTML = "<i class=\"fa fa-home\"></i> Lives in: <strong>" + document.getElementById("location_edit").value + "</strong>";
}










