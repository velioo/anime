var scale = 0;
var counter = 0;
var original_width;
var window_width;
var zoom_in;
var prev_window_width;
var asset_url;

function myFunction() {
    var w = window.innerWidth;
    var h = window.innerHeight;
    document.getElementById("demo").innerHTML = "Width: " + w + "<br>Height: " + h;
}

function reScale() {	
	var height = window.innerHeight;
	//console.log("original: " + original_width + " window_width: " + window_width);
	if((window_width == original_width) || ((original_width - window_width) == 1 )) {
		scale = 0;
		counter = 0;
	} else if(zoom_in == true){
		counter++;
		scale-=60;
	} else if(zoom_in == false) {
		counter--;
		scale+=60;
	}
	
	if($('#user-bar').length > 0) {
		var offset = document.getElementById("top_offset").getAttribute("value");
		offset = parseInt(offset);
		scale = parseInt(scale);
		if(((offset + scale) < 0) || (counter > 5)) {
			$('#user-bar').css("background-position", "0px 0px");
		} else {
			$('#user-bar').css("background-position", "0px" + " -" + (offset + scale) + "px");
		}
	}
	
	if($('#anime-bar').length > 0) {
		var offset = document.getElementById("top_offset").getAttribute("value");
		offset = parseInt(offset);
		scale = parseInt(scale);
		if(((offset + scale) < 0) || (counter > 5)) {
			$('#anime-bar').css("background-position", "0px 0px");
		} else {
			$('#anime-bar').css("background-position", "0px" + " -" + (offset + scale) + "px");
		}
	}
	
	var e = document.getElementsByClassName("container-fluid");
	for (i = 0; i < e.length; i++) {
		if(window_width <= 1150) {	
		    e[i].style.width = window_width + "px";
		} else {
			e[i].style.width = "1150px";
		}
	}
	
	if(window_width <= 1150) {	
		if ($('#signup_button').length > 0) {
			$('#signup_button').css("margin-right", "-21px");
			//document.getElementById("signup_button").style.marginRight = "-21px";	
		}
	} else if($('#signup_button').length > 0) {
		$('#signup_button').css("margin-right", "0px");
		//document.getElementById("signup_button").style.marginRight = "0px";
	}
	
}

function putCaret() {
    if(window.innerWidth <= 321)
    	document.getElementById("navigation_small_button").innerHTML = "<span class=\"caret\"></span>";
	if(window.innerWidth > 321)
		document.getElementById("navigation_small_button").innerHTML = "Menu <span class=\"caret\"></span>";
}

$(document).ready(function() {
	window_width = window.outerWidth;
	prev_window_width = window_width;
	original_width = window_width;
	$('.search_form').each(function(){
		$(this).submit(function () {		
			if($(this).attr('name') == "search_form_name") {
				var text = $.trim($('#search_box').val());
			} else {
				var text = $.trim($('#small_search_box').val());
			}		
		    if (text  === '') {
		        return false;
		    }		

		});
	});
	
	if($('.text-over-img').length > 0) {
		$('.text-over-img').each(function() {
			var text = $(this).text();
			
			if(text.length > 26) {
				if(text.length <= 50) {
					$(this).css("margin-top", "-52px");
				} else {
					$(this).css("margin-top", "-69px");
				}
				var temp = $(this).text().split(" ");
				var line_text = "", i;
				var whole_text = "";
				for (i = 0; i < temp.length; i++) {
				    if((line_text.length + temp[i].length) >= 26) {
				    	line_text = line_text + "\n";
				    	whole_text = whole_text + line_text;
				    	line_text = temp[i];
				    } else {
				    	line_text = line_text + " " + temp[i];
				    }
				}
				
				if(line_text.length < 26) {
					whole_text = whole_text + line_text;
				}
				
				whole_text = whole_text.replace(/^\s+|\s+$/g, '');
				
				$(this).text(whole_text);
			}
		});
	}
});

$(window).resize(function(){
	prev_window_width = window_width;
	window_width = window.innerWidth;
	if(window_width - prev_window_width != 1) {
		if(window_width > prev_window_width) {
			zoom_in = false;
		} else {
			zoom_in = true;
		}
	}
	
	if ($('.profile_menu').length > 0) {
		if(window.innerWidth < 800) {
			$('#submit_button').css('margin-right', "70px");
		} else {
			$('#submit_button').css('margin-right', "0px");
		}
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
	reScale();
	putCaret();
});
