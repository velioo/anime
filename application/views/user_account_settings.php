<?php include 'head.php';?>

<?php include 'navigation.php'; ?>

<?php 
if (!isset($this->session->userdata['is_logged_in'])) {
	header("location: " . site_url("Login/login_page"));
}

?>

<div id="wrap">
	<div class="container-fluid scrollable content">
	    <h1 style="text-align: center;"><?php echo $header;?></h1>
		<br>
		<div id="change_acc_info_div">
			<p id="title">Change Account Info</p>
			<br/>
			<p class="error"><?php echo $fb_message;?></p>
			<?php 
				$username = set_value('username') == false ? $username : set_value('username');
				$email = set_value('email') == false ? $email : set_value('email');
					
				echo form_open('userUpdates/update_user_account_info', 'class="signloginform" autocomplete="off"');
				echo form_label('Username', 'username');
				echo form_input('username', set_value('username', $username));
				echo form_error('username', '<p class="error">*', '</p>');echo "<br/>";
				echo form_label('Email', 'email');
				echo form_input('email', set_value('email', $email));
				echo form_error('email', '<p class="error">*', '</p>');echo "<br/>";
				echo form_label('Password(Optional)', 'password');
				echo form_password('password', '');
				echo form_error('password', '<p class="error">*', '</p>');echo "<br/>";
				echo form_label('Confirm Password(Optional)', 'password_confirm');
				echo form_password('password_confirm', '');
				echo form_error('password_confirm', '<p class="error">*', '</p>');echo "<br/><br/>";
				echo form_submit('submit', 'Save', 'id=submit');
				echo form_close();
			?>
			<form action="<?php echo site_url("login/facebook_connect");?>" method="post">
			 <button type="submit" id="fb-login" class="btn btn-block btn-social btn-facebook" style="width:250px;margin-left:120px;margin-top:50px;">
			    <span class="fa fa-facebook"></span><?php echo $is_fb_connected;?>
			 </button>
		 	</form>
		 	<br/>
		</div>
	</div>

</div>

<?php include 'footer.php';?>