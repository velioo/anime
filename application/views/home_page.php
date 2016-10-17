<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $title; ?></title>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	  
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2, user-scalable=1">
	  <meta name="apple-mobile-web-app-capable" content="yes" />  
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <link rel="stylesheet" type="text/css" href="<?php echo asset_url();?>css/<?php echo $css;?>">
	  <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	  
	  <script type="text/javascript">
		    function myFunction() {
			    var w = window.innerWidth;
			    var h = window.innerHeight;
			    document.getElementById("demo").innerHTML = "Width: " + w + "<br>Height: " + h;
			}

		    window.addEventListener("resize", function(){
			    if(window.innerWidth <= 321)
		        	document.getElementById("navigation_small_button").innerHTML = "<span class=\"caret\"></span>";
	        	if(window.innerWidth > 321)
	        		document.getElementById("navigation_small_button").innerHTML = "Menu <span class=\"caret\"></span>";
		    });
	  </script>
	  
</head>
<body>

<div id="container" class="header">
	<?php include 'navigation.php';?>
	
	<div class="container-fluid scrollable content">
		<h3 id="main_title">Welcome To V-Anime</h3>
		<h5>Latest Anime</h5>
	    <?php include 'slider.php';?>
	    <button onclick="myFunction()">Try it</button>
		<p id="demo"></p>
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		
	</div>
	
	<?php include 'footer.php';?>
</div>


</body>
</html>