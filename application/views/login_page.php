<?php include 'head.php'; include 'navigation.php';?>


<?php 
if (isset($this->session->userdata['is_logged_in'])) {

	header("location: " . site_url("Login/log_in"));
}?>

<div id="wrap">

	<div class="container-fluid scrollable signuplogin_container">
	<?php if(isset($account_created)) {?>
		<h1><?php echo $account_created;?></h1>
	<?php } else if(isset($message_display)){?>
		<h1><?php echo $message_display;?></h1>
	<?php } else {?>
		<h1><?php echo $header;?></h1>
	<?php }?>
		<br/><br/><br/>
		<?php 
		$username = set_value('username') == false ? '' : set_value('username');
		
			echo form_open('login/log_in', 'class="signloginform"');
			echo form_label('Username', 'username');
			echo form_input('username', set_value('username', $username) ); echo "<br/>";
			echo form_label('Password', 'password');
			echo form_password('password'); echo "<br/>";echo "<br/>";
			if(isset($incorrect))
				echo "<p class='error' style='margin-left:180px;'>*" . $incorrect . "</p>"; echo "<br/>";
			echo form_submit('submit', 'Login', 'id=submit');echo "<br/>";echo "<br/>";
			echo anchor('SignUp/signup_page', 'Click here to create an account', 'id=anchor');
			echo form_close();
		?>
		<a href="<?php echo site_url("login/forgotten_password")?>" id="forgot_password">Forgot your password?</a>
	</div>
	

</div>

<?php include 'footer.php';?>