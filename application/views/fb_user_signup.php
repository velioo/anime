<?php include 'head.php';?>
<?php include 'navigation.php';?>

<?php 
if (isset($this->session->userdata['is_logged_in'])) {
	header("location: " . site_url("Login/log_in"));
}

?>

<div id="wrap">
	<div class="container-fluid scrollable content signuplogin_container">
		<h1><?php echo $header;?></h1>
		<p style="font-size: 20px; text-align: center;"><?php if(isset($message)) echo $message?></p>
		<br/>

			<?php $username = set_value('username') == false ? '' : set_value('username');
				  $email = set_value('email') == false ? '' : set_value('email');
				  $label_attr = array(
				  		'class' => 'signup_label'
				  );
				  
				  
				echo form_open("signUp/create_facebook_user/{$fb_user_id}/{$fb_access_token}/{$fb_email}", 'class="signloginform" autocomplete="off"'); 				
				echo form_label('Username', 'username', $label_attr);
				echo form_input('username', set_value('username', $username)); 
				echo form_error('username', '<p class="error">*', '</p>');echo "<br/>";
				if(!$fb_email) {
					echo form_label('Email', 'email', $label_attr);
					echo form_input('email', set_value('email', $email));
					echo form_error('email', '<p class="error">*', '</p>');echo "<br/>";
					echo "<p class='error'> The email associated with you Facebook account was taken</p><br>";
				}
				echo form_submit('submit', 'Sign Up !', 'class="submit button-black"');
				echo form_close();	
			?>
	</div>
</div>

<?php include 'footer.php';?>