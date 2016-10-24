<?php include 'head.php'; include 'navigation.php';?>


<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $results['username'])) 
		$is_you = TRUE;
	else 
		$is_you = FALSE;
?>

<div id="wrap">

	<div class="container-fluid scrollable content">
		<h1><?php echo $header;?></h1>
		<br/><br/><br/>
		<?php
			if($is_you) {
				echo "Hello <i>" . $results['username'] . "</i> !</b>";
				echo "<br/>";
			} else {
				echo "This is <i>" . $results['username'] . "'s</i> profile !</b>";
				echo "<br/>";
			}
			echo "<br/>";
			if($is_you) {
				echo "Welcome to Admin Page";
				echo "<br/>";
			}
			echo "<br/>";
			if($is_you) {
				echo "Your Username is " . $results['username'];
				echo "<br/>";
			}
			
			if($is_you) {
				echo "Your Email is " . $results['email'];
				echo "<br/>";
			}
			
			if($is_you) {
				echo "You joined V-Anime on " . $results['joined_on'];
				echo "<br/>";
				
			} else {
				echo "He joined V-Anime on " . $results['joined_on'];
				echo "<br/>";
			}
		?>
		<br/><br/><br/>
	</div>
</div>

	
<?php include 'footer.php';?>