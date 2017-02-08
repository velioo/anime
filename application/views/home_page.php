<?php include 'head.php';?>

<?php include 'navigation.php';?>
<div id="wrap">
	
	<div class="container-fluid scrollable content">
		<?php if(isset($this->session->userdata['is_logged_in'])) {
			$message = "Welcome back " . "<var style='color:red; font-size: 35px;'>". $this->session->userdata['username'] . "</var>";
		} else $message = "Welcome to V-Anime";?>
		<h3 class="main_title"><?php echo $message;?></h3>
		<p style="text-align: center;">V-Anime is a social network for anime fans.<br/>Discover new animes, set up lists of what you've seen and share your experiences.</p> <!-- Search over 11,000 animes, 38,000 characters and 6000 actors. -->
		<h5>Latest Anime</h5>
	    <?php include 'slider.php';?>
	    <br/>
	    <!--<button onclick="myFunction()">Try it</button>-->
		<p id="demo"></p>
	
		   <?php //echo '<a href="' . site_url("home/speed_test") . '">Speed test</a>'; ?>
		   <?php //echo '<a href="' . site_url("CharacterUpdates/get_add_characters_actors/8ghj30jhkll70gvk19f8kkujai90816k") . '">Update Characters</br></a>'; ?>
		   <?php //echo '<a href="' . site_url("characterUpdates/write_actors_json/8ghj30jhkll70gvk19f8kkujai90816k") . '">Write actors json<br/></a>'; ?>
		   <?php //echo '<a href="' . site_url("posts/delete_comment/82") . '">Test Comment</a></br>'; ?>
		   <?php //echo '<a href="' . site_url("notifications/character_v1") . '">AniList</a>'; ?>
	</div>
</div>
<?php include 'footer.php';?>
