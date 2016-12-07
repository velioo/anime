<?php include 'head.php'; ?>

<?php include 'navigation.php';?>
<?php 
if (isset($this->session->userdata['is_logged_in'])) {
	header("location: " . site_url("Login/log_in"));
}?>

<div id="wrap">

	<div class="container-fluid scrollable signuplogin_container">
	<h1><?php echo $header;?></h1>
		<br/><br/>
		<p style="color:#2F343B;">Forgot your info? No problem! Enter your account's email address and we'll send you instructions on how to update your password.</p>
		<?php 		
		
			$email = set_value('email') == false ? '' : set_value('email');
			
			echo form_open('emails/send_password_reset_link', 'class="signloginform"');
			echo form_label('Email', 'email');
			echo form_input('email', set_value('email', $email) );
			echo form_error('email', '<p class="error">*', '</p>');echo "<br/>";
			echo form_submit('submit', 'Send', 'id="submit" class="button-black"');echo "<br/>";echo "<br/>";
			echo form_close();
		?>
	</div>
</div>

<?php include 'footer.php';?>