function myFunction() {
    var w = window.innerWidth;
    var h = window.innerHeight;
    document.getElementById("demo").innerHTML = "Width: " + w + "<br>Height: " + h;
}

function reScale() {	
	var width = window.innerWidth;
	var height = window.innerHeight;
	
	if($('#user-bar').length > 0) {
		var offset = document.getElementById("top_offset").getAttribute("value");
		document.getElementById("user-bar").style.width = width + "px";
		document.getElementById("user-bar").style.backgroundPosition = "0px" + " -" + (offset* width) + "px";	
		if($('#submit_cover_button').length > 0) {
			document.getElementById("submit_cover_button").style.marginRight = width/6 + "px";	
			document.getElementById("edit_cover_label").style.marginRight =  width/5 + "px";
		}
	}

	var e = document.getElementsByClassName("container-fluid");
	for (i = 0; i < e.length; i++) {
		if(width <= 1150) {	
		    e[i].style.width = width + "px";
		} else {
			e[i].style.width = "1150px";
		}
	}
	
	if(width <= 1150) {	
		if ($('#signup_button').length > 0) {
			document.getElementById("signup_button").style.marginRight = "-21px";	
		}
	} else {
		document.getElementById("signup_button").style.marginRight = "0px";
	}
	
}

function putCaret() {
    if(window.innerWidth <= 321)
    	document.getElementById("navigation_small_button").innerHTML = "<span class=\"caret\"></span>";
	if(window.innerWidth > 321)
		document.getElementById("navigation_small_button").innerHTML = "Menu <span class=\"caret\"></span>";
}

window.addEventListener("resize", function(){
	if ($('.profile_menu').length > 0) {
		if(window.innerWidth < 800)
			document.getElementById("submit_button").style.marginRight = "70px";
		else
			document.getElementById("submit_button").style.marginRight = "0px";
	} 
	
	reScale();
	putCaret();
});

window.addEventListener("orientationchange", function() {
	reScale();
	putCaret();
}, false);

document.addEventListener("DOMContentLoaded", function() {
	if ($('.profile_menu').length > 0) {
		if(window.innerWidth < 800) 
			document.getElementById("submit_button").style.marginRight = "70px";
		document.getElementById("search").style.marginRight = "0px";
	}
	
	if($('.text-over-img').length > 0) {
		var e = document.getElementsByClassName("text-over-img");
		for(i = 0; i < e.length; i++) {
			var text = e[i].innerHTML;
			if(text.length >= 29) {
				e[i].style.marginTop = "-52px";
			}

		}
		
	}
	reScale();
	putCaret();
});
