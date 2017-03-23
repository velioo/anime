<?php include 'head.php';?>
<?php include 'navigation.php';?>

<?php 
if (isset($this->session->userdata['is_logged_in'])) {
	header("location: " . site_url("Login/log_in"));
}
?>

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
			
			$label_attr = array(
					'class' => 'signup_label'
			);
		
			echo form_open('login/log_in', 'class="signloginform"');
			echo form_label('Username', 'username', $label_attr);
			echo form_input('username', set_value('username', $username) ); echo "<br/>";
			echo form_label('Password', 'password', $label_attr);
			echo form_password('password'); echo "<br/>";echo "<br/>";
			if(isset($incorrect))
				echo "<p class='error' style='margin-left:180px;'>*" . $incorrect . "</p>"; echo "<br/>";
			echo form_submit('submit', 'Login', 'class="submit button-black"');echo "<br/>";echo "<br/>";
			echo form_close();
		?>
		<p class="center_paragraph">
			<a href="<?php echo site_url("signUp/signup_page")?>" id="create_account" class="disable-link-decoration"><span class="red-text">Click here to create an account</span></a><br/>
			<a href="<?php echo site_url("login/forgotten_password")?>" id="forgot_password" class="disable-link-decoration"><span class="red-text">Forgot your password?</span></a>
		</p>		
		 <form action="<?php echo site_url("login/facebook_login");?>" method="post">
			 <button type="submit" id="fb-login" class="btn btn-block btn-social btn-facebook">
			    <span class="fa fa-facebook"></span>Connect with Facebook
			 </button>
		 </form>
	</div>
	

</div>

<?php include 'footer.php';?>