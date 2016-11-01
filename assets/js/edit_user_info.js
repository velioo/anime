function editUserInfo(file_chosen, filesize) {
	var margin = 0.0005;
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