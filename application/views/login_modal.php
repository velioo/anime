<link rel="stylesheet" href="<?php echo asset_url() . "css/login_modal.css";?>" type="text/css" />

<script>
	$(document).ready(function() {
		$('head').append('<script src="<?php echo asset_url() . "js/asynch_login.js";?>">');
	});

	function getLoginUrl() {
		var login_url = "<?php echo site_url("login/asynch_log_in");?>";
		return login_url;
	}
</script>

<div id="login_modal" class="modal fade" role="dialog">
	<div class="wrap_modal_elements modal_login">
	<div class="modal-dialog modal_login">
        <div class="modal-content modal_login">
		    <div class="modal-header modal_login">
		        <a href="#" data-dismiss="modal" class="close">&times;</a>
		         <h3 class="login_modal_title">Login</h3>
		    </div>
		    <div class="modal-body modal_login">
		    	<p id="login_error" style="color: red; display: none;">Username or password is incorrect</p>
		        <label for="username" class="modal_login">Username</label><input id="modal_username" type="text" name="username" class="modal_login">
		        <label for="password" class="modal_login">Password</label><input id="modal_password" type="password" name="password" class="modal_login">
		    </div>
		    <div class="modal-footer modal_login">
		        <input id="submit_login" class="submit modal_login button-black" type="submit" value="Login">
		        <br/>
		        <a href="<?php echo site_url("signUp/signup_page")?>" id="create_new_anchor" class="disable-link-decoration"><span class="red-text">Click here to create an account</span></a>
		        <a href="<?php echo site_url("login/forgotten_password")?>" id="modal-forgot_password" class="disable-link-decoration"><span class="red-text">Forgot your password?</span></a>
		        <br/>
		        <?php $redirect_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>				 
				 <form action="<?php echo site_url("login/facebook_login");?>" method="post">
					 <button type="submit" id="modal-fb-login" class="btn btn-block btn-social btn-facebook">
					    <span class="fa fa-facebook"></span>Connect with Facebook
					 </button>
					 <input type="hidden" name="redirect_url" value="<?php echo $redirect_url;?>">
				 </form>
		    </div>
	    </div>
    </div>
    </div>
</div>