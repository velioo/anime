<?php include 'head.php'; include 'navigation.php';?>

<?php 
if (isset($this->session->userdata['is_logged_in'])) {
	header("location: " . site_url("Login/log_in"));
}

?>

<div id="wrap">

	<div class="container-fluid scrollable signuplogin_container">
		<h1><?php echo $header;?></h1>
		<br/><br/><br/>
		<?php 
			$username = set_value('username') == false ? '' : set_value('username');
			$email = set_value('email') == false ? '' : set_value('email');
					
			echo form_open('SignUp/create_user', 'class="signloginform"'); 
			echo form_label('Username', 'username');
			echo form_input('username', set_value('username', $username) ); 
			echo form_error('username', '<p class="error">*', '</p>');echo "<br/>";
			echo form_label('Email', 'email');
			echo form_input('email', set_value('email', $email));
			echo form_error('email', '<p class="error">*', '</p>');echo "<br/>";
			echo form_label('Password', 'password');
			echo form_password('password', '');
			echo form_error('password', '<p class="error">*', '</p>');echo "<br/>";
			echo form_label('Confirm Password', 'password_confirm');
			echo form_password('password_confirm', '');
			echo form_error('password_confirm', '<p class="error">*', '</p>');echo "<br/>";
			echo form_submit('submit', 'Sign Up !', 'id=submit');
			echo form_close();
		?>
		<br/><br/><br/>
	</div>
</div>

<?php include 'footer.php';?>