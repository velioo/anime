<?php include 'head.php';?>
<?php include 'navigation.php'; ?>

<?php 
if (!isset($this->session->userdata['is_logged_in'])) {
	header("location: " . site_url("Login/login_page"));
}
?>

<script>
$(document).ready(function() {
	var age_visibility = <?php echo $user['show_age'];?>;
	var default_watchlist_page = <?php echo $user['default_watchlist_page'];?>;
	$('#age_visibility').val(age_visibility);
	$('#default_watchlist_page').val(default_watchlist_page);

	$('#fb-login').click(function() {
		$('.loading_div').show();
	});
	
});
</script>

<div id="wrap">
	<div class="container-fluid scrollable content">
	    <h1 style="text-align: center;"><?php echo $header;?></h1>
		<br>
		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#change_acc_info_div">Account settings</a></li>
		  <li><a data-toggle="tab" href="#privacy_notifications_div">Privacy &amp; Notifications</a></li>
		  <li><a data-toggle="tab" href="#preferences_div">Preferences</a></li>
		</ul>
		
		<div class="tab-content">
		  	<div id="change_acc_info_div" class="tab-pane fade in active">
				<p class="error"><?php if (stripos($message, 'successfully') !== false) { echo "<span style='color:green;'>" . $message . "</span>"; } else { echo $message; }?></p>
				<?php 
					$username = set_value('username') == false ? $user['username'] : set_value('username');
					$email = set_value('email') == false ? $user['email'] : set_value('email');
						
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
					echo form_submit('submit', 'Save', 'class="submit button-black"');
					echo form_close();
				?>
				<form action="<?php echo site_url("userUpdates/facebook_connect");?>" method="post">
				 <button type="submit" id="fb-login" class="btn btn-block btn-social btn-facebook">
				    <span class="fa fa-facebook"></span><?php echo $is_fb_connected;?>
				 </button>
			 	</form>
			 	<div class="loading_div"><img src="<?php echo asset_url() . "imgs/loading_icon.gif";?>" class="loading_icon"></div>
			 	<br/>
			</div>
		  <div id="privacy_notifications_div" class="preferences_div tab-pane fade">
		  		<form action="<?php echo site_url("userUpdates/update_user_privacy_notifications");?>" method="post">
			  		<label for="age_visibility">Visibility:</label>
			   		<select name="age_visibility" id="age_visibility" class="preferences_dropdown">
			   			<option value="1">Show Age</option>
			   			<option value="0">Hide Age</option>
			   		</select>
			   		<input type="submit" class="submit_privacy button-black" value="Update preferences">
		   		</form>
		  </div>
		  
		    <div id="preferences_div" class="preferences_div tab-pane fade">
		  		<form action="<?php echo site_url("userUpdates/update_user_preferences");?>" method="post">
			  		<label for="default_watchlist_page">Default watchlist page</label>
			   		<select name="default_watchlist_page" id="default_watchlist_page" class="preferences_dropdown">
			   			<option value="0">All</option>
			   			<option value="1">Watched</option>
			   			<option value="2">Watching</option>
			   			<option value="3">Want to Watch</option>
			   			<option value="4">Stalled</option>
			   			<option value="5">Dropped</option>
			   		</select>
			   		<input type="submit" class="submit_privacy button-black" value="Update preferences">
		   		</form>
		  </div>
		  
		 
		</div>
	</div>

</div>

<?php include 'footer.php';?>