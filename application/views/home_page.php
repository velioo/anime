<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $title; ?></title>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <link rel="stylesheet" type="text/css" href="<?php echo asset_url();?>css/<?php echo $css;?>">
	  
	  <style type="text/css">
	  	
	  </style>
	  
</head>
<body>

<div id="container">
	<?php include 'navigation.php';?>
	
	<div id="footer">
		<div id="footerContainer">
			 <a href="<?php base_url();?>" style="color:white; ">Home |</a>
			 <a href="<?php base_url();?>" style="color:white; ">About</a>
			 <a href="<?php base_url();?>" style="color:white; ">Support</a>
			 <a href="<?php base_url();?>" style="color:white; ">Terms</a>
			 <a href="<?php base_url();?>" style="color:white; ">Privacy</a>
		     <p class="text-muted credit" style="font-size: 10px">V-Anime is a property of Darth Velioo, LLC ©2016 All Rights Reserved..</p>
	     </div>
	</div>
</div>


</body>
</html>