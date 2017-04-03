<?php include 'head.php';?>
<?php include 'navigation.php'; ?>

<link rel="stylesheet" href="<?php echo asset_url() . "css/user_navigation_bar.css";?>" type="text/css" />
<script src="<?php echo asset_url() . "js/edit_user_info.js";?>"></script>

<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $user['username'])) {
		$is_you = TRUE;
	} else {
		$is_you = FALSE;
	}
?>

<script type="text/javascript">
	$(document).ready(function() {	
		<?php if(!$is_you) {?>
			$('head').append('<script src="<?php echo asset_url() . "js/follow.js";?>">');	
		<?php }?>		
	});
	
	<?php if($is_you) { ?>
	function showEditFields() {
		editUserInfo(false, 0);		
		$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/temp_disable_selection.css";?>" type="text/css" />');		
	}
	<?php }?>

	function getFollowUrl() {
		var follow_url = "<?php echo site_url("follow/follow_user")?>";
		return follow_url;
	}

	function getUnfollowUrl() {
		var unfollow_url = "<?php echo site_url("follow/unfollow_user")?>";
		return unfollow_url;
	}

	function getUserId() {
		return <?php echo $user['id'];?>;
	}
</script>

<div id="wrap">
	<?php include 'user_profile_top.php';?>
	<div class="container-fluid scrollable" id="follow_content">		
		<h1 class="main_title2"><?php echo $header;?><span style="float: right;"><?php echo $users_count;?></span></h1>	
		<div id="follow_div">
			<?php foreach($users as $user_) {?>
				<div class="user_image_div">
					<a href="<?php echo site_url("users/profile/{$user_['username']}")?>" class="disable-link-decoration">
					<img src="<?php if($user_['profile_image'] != "") { echo asset_url(). "user_profile_images/" . $user_['profile_image'];} else { echo asset_url() ."imgs/Default_Avatar.jpg";}?>" class="user_image">
					<span class="user_name red-text"><?php echo $user_['username'];?></span></a>
				</div>
			<?php }?>
		</div>
	</div>
</div>
	
<?php include 'footer.php';?>
