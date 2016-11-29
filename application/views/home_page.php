<?php include 'head.php';?>

<?php include 'navigation.php';?>
<div id="wrap">
	
	<div class="container-fluid scrollable content">
		<?php if(isset($this->session->userdata['is_logged_in'])) {
			$message = "Welcome back " . "<var style='color:red; font-size: 35px;'>". $this->session->userdata['username'] . "</var>";
		} else $message = "Welcome to V-Anime";?>
		<h3 class="main_title"><?php echo $message;?></h3>
		<h5>Latest Anime</h5>
	    <?php include 'slider.php';?>
	    <br/>
	    <button onclick="myFunction()">Try it</button>
		<p id="demo"></p>
	
		   <?php echo '<a href="' . site_url("home/test_v2") . '">JsonV2</a>'; ?>
		   <?php //echo '<a href="' . site_url("animeUpdates/write_json_autocomplete") . '">Write Json</a>'; ?>
		   <?php //echo '<a href="' . site_url("animeUpdates/get_update_animes") . '">Get json data with FGC</a></br>'; ?>
		   <?php //echo '<a href="' . site_url("animeUpdates/get_update_animes_curl") . '">Get json data with cUrl</a>'; ?>
	</div>
</div>
<?php include 'footer.php';?>
