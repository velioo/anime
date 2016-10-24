<?php include 'head.php'; include 'navigation.php';?>

<?php
	if ($this->session->userdata['is_logged_in'] == true) {
		$username = $this->session->userdata['username'];
		$email = $this->session->userdata['email'];
		$joined_on = $this->session->userdata['joined_on'];
	} else {
		header("location: " . site_url("Login"));
	}
?>

<div id="wrap">
<h1><?php echo $header;?></h1>

	<div class="container-fluid scrollable content">
		<br/><br/><br/>
		<?php
			echo "Hello <i>" . $username . "</i> !</b>";
			echo "<br/>";
			echo "<br/>";
			echo "Welcome to Admin Page";
			echo "<br/>";
			echo "<br/>";
			echo "Your Username is " . $username;
			echo "<br/>";
			echo "Your Email is " . $email;
			echo "<br/>";
			echo "You joined V-Anime on " . $joined_on;
			echo "<br/>"
		?>
		<br/><br/><br/>
	</div>
</div>

	
<?php include 'footer.php';?>