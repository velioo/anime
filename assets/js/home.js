function myFunction() {
    var w = window.innerWidth;
    var h = window.innerHeight;
    document.getElementById("demo").innerHTML = "Width: " + w + "<br>Height: " + h;
}

function reScale() {
	var width = window.innerWidth;
	var height = window.innerHeight;
	if(window.innerWidth <= 1000) {
		var e = document.getElementsByClassName("container-fluid");
		for (i = 0; i < e.length; i++) {
		    e[i].style.width = width + "px";
		}
		document.getElementById("slider").style.height = height/2 + "px";
		document.getElementById("footer").style.minWidth = width + "px";
	}
}

function putCaret() {
    if(window.innerWidth <= 321)
    	document.getElementById("navigation_small_button").innerHTML = "<span class=\"caret\"></span>";
	if(window.innerWidth > 321)
		document.getElementById("navigation_small_button").innerHTML = "Menu <span class=\"caret\"></span>";
}

window.addEventListener("resize", function(){
	reScale();
	putCaret();
});

window.addEventListener("orientationchange", function() {
	reScale();
	putCaret();
}, false);

document.addEventListener("DOMContentLoaded", function() {
	if ($('.profile_menu').length > 0) {
		document.getElementById("search").style.marginRight = "0px";
	}
	reScale();
	putCaret();
});

