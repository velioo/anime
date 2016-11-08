<?php include 'head.php';?>

<div id="wrap">
	<?php include 'navigation.php';?>
	
	<div class="container-fluid scrollable content">
		<?php if(isset($this->session->userdata['is_logged_in'])) {
			$message = "Welcome back " . "<var style='color:red; font-size: 35px;'>". $this->session->userdata['username'] . "</var>";
		} else $message = "Welcome to V-Anime";?>
		<h3 id="main_title"><?php echo $message;?></h3>
		<h5>Latest Anime</h5>
	    <?php include 'slider.php';?>
	    <br/>
	    <button onclick="myFunction()">Try it</button>
		<p id="demo"></p>
	
		  <a href="<?php echo site_url("home/write")?>">Write</a>
		  <!--  <a href="<?php //echo site_url("Home/update_anidb")?>">Insert</a>-->
	
	</div>
</div>
	<?php include 'footer.php';?>
