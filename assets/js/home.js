var scale = 0;
var counter = 0;
var window_difference;
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

	if(typeof zoom_in == "undefined") {
		if(window_difference == 0 || window_difference == 1) {} else 
		if(window_difference > -230 && window_difference < 0) {scale+=60; counter = -1;} else 
		if(window_difference > -500 && window_difference < 0) {scale+=120; counter = -2;} else 
		if(window_difference > -650 && window_difference < 0) {scale+=180; counter = -3;} else 
		if(window_difference > -970 && window_difference < 0) {scale+=240; counter = -4;} else 
		if(window_difference > -1930 && window_difference < 0) {scale+=300; counter = -5;} else 
		if(window_difference > -3900 && window_difference < 0) {scale+=360; counter = -6;} else 
		if(window_difference > -5800 && window_difference < 0) {scale+=420; counter = -7;} else
		if(window_difference < 200 ) {scale-=60; counter = 1;} else 
		if(window_difference < 400 ) {scale-=120; counter = 2;} else 
		if(window_difference < 800 ) {scale-=180; counter = 3;} else 
		if(window_difference < 900 ) {scale-=240; counter = 4;} else 
		if(window_difference < 1000 ) {scale-=300; counter = 5;} else 
		if(window_difference < 1200 ) {scale-=360; counter = 6;} else 
		if(window_difference < 1400 ) {scale-=420; counter = 7;} else		
		if(window_difference < 1500 ) {scale-=480; counter = 8;} else	
		if(window_difference < 1600 ) {scale-=540; counter = 9;} 
	} else {
		if((window_width == original_width) || ((original_width - window_width) == 1 )) {
			scale = 0;
			counter = 0;
		} else if((window_width == prev_window_width) || (Math.abs(window_width - prev_window_width) == 1)) {	
		} else if(zoom_in == true){
			counter++;
			scale-=60;
		} else if(zoom_in == false) {
			counter--;
			scale+=60;
		}
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
	
	if($('#small_search_box').length > 0) {
		if($('#small_search_box').is(":visible")) {
			$('#small_search_box').val($('#search_box').val());
		}
	}
	
}

function putCaret() {
    if(window.innerWidth <= 321)
    	document.getElementById("navigation_small_button").innerHTML = "<span class=\"caret\"></span>";
	if(window.innerWidth > 321)
		document.getElementById("navigation_small_button").innerHTML = "Menu <span class=\"caret\"></span>";
}

$(document).ready(function() {
	if($('.text-over-img').length > 0) {
		$('.text-over-img').each(function() {
			var text = $(this).text();
			if(text.length > 26) {
				if(text.length <= 50) {
					$(this).css("margin-top", "-52px");
				} else if(text.length <= 70){
					$(this).css("margin-top", "-69px");
				} else {
					$(this).css("margin-top", "-87px");
				}
				var temp = $(this).text().split(" ");
				var line_text = "", i;
				var whole_text = "";
				for (i = 0; i < temp.length; i++) {
				    if((line_text.length + temp[i].length) > 26) { 
				    	line_text = line_text + "\n";
				    	whole_text = whole_text + line_text;
				    	line_text = temp[i];
				    } else {
				    	line_text = line_text + " " + temp[i];
				    	if(line_text.length > 26) {
				    		line_text = line_text + "\n";
					    	whole_text = whole_text + line_text;
					    	line_text = "";
				    	}
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
	reScale();
	putCaret();
});

window.addEventListener("orientationchange", function() {
	reScale();
	putCaret();
}, false);

document.addEventListener("DOMContentLoaded", function() {
	original_width = window.outerWidth;
	window_width = window.innerWidth;
	prev_window_width = window_width;

	window_difference = original_width - window_width;
	
	reScale();
	putCaret();
});
