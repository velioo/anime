<?php include 'head.php';?>

<?php include 'navigation.php';?>
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
					
			echo form_open('signUp/create_user', 'class="signloginform" autocomplete="off"'); 			
			echo form_label('Username', 'username');
			echo form_input('username', set_value('username', $username)); 
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
			echo form_submit('submit', 'Sign Up !', 'id="submit" class="button-black"');
			echo form_close();		
		?>
		<p class="center_paragraph">By clicking Sign Up, you are indicating that you have read and agree to the 
			<a href="#" class="accept disable-link-decoration">
				<span class="red-text">Terms of Use</span>
			</a> and 
			<a href="#" class="accept disable-link-decoration">
				<span class="red-text">Privacy Policy</span>
			</a>
		</p>
		<p class="center_paragraph" style="display: inline;">Already have an account ?<a href="<?php echo site_url("login/login_page")?>" class="accept disable-link-decoration" style="display: inline;"> <span class="red-text">Sign in</span></a>.</p>
		  <form action="<?php echo site_url("login/facebook_login");?>" method="post">
			 <button type="submit" id="fb-login" class="btn btn-block btn-social btn-facebook" style="width:300px;margin-left:32%;margin-top:50px;">
			    <span class="fa fa-facebook"></span>Connect with Facebook
			 </button>
		 </form>
		<br/><br/><br/>
	</div>
</div>

<?php include 'footer.php';?>