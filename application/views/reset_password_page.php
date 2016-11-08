<?php include 'head.php'; include 'navigation.php';?>


<?php 
if (isset($this->session->userdata['is_logged_in'])) {
	header("location: " . site_url("Login/log_in"));
}?>

<div id="wrap">

	<div class="container-fluid scrollable signuplogin_container">
	<h1><?php echo $header;?></h1>
		<br/><br/>
		<p style="color:#2F343B; margin-left:30%; font-size:20px; color:red;">Type your new password here</p>
		<br/>
		<?php 		
			
			echo form_open('login/update_password/' . $user_id, 'class="signloginform"');
			echo form_label('Password', 'password');
			echo form_password('password', '');
			echo form_error('password', '<p class="error">*', '</p>');echo "<br/>";
			echo form_label('Confirm Password', 'password_confirm');
			echo form_password('password_confirm', '');
			echo form_error('password_confirm', '<p class="error">*', '</p>');echo "<br/>";
			echo form_submit('submit', 'Update Password', 'id=update');
			echo form_close();
		?>
	</div>
</div>

<?php include 'footer.php';?>