<?php $random_num = time(); 
	$online = false;
	date_default_timezone_set('Europe/Sofia');
	$current_time = $date = date('Y-m-d H:i:s'); $current_time = strtotime($current_time);
	$last_online = strtotime($user['last_online']);	
	$time_difference = round(abs($current_time - $last_online) / 60) . " minutes ago";
	
	if($time_difference <= 10) {
		$online = true;
	} 
   if($time_difference > 60 && $time_difference < 1440) {
		$time_difference = round(abs($current_time - $last_online) / 3600) . " hours ago";
	} else if($time_difference > 1440) {
		$time_difference = round(abs($current_time - $last_online) / (3600 * 24)) . " days ago";
	}
?>
<div id="user-bar" style="background-image:url('<?php if($user['cover_image'] != "") { echo asset_url() . "user_cover_images/" . $user['cover_image']; } else echo asset_url() . "user_cover_images/Default.jpg"?>'); ">
	<div class="container-fluid top-container">		
		<a class="thumbnail">
			<div id="user_image_div">
				<img src="<?php echo asset_url() . "user_profile_images/" . $user['profile_image'];?>" onerror="this.src='<?php echo asset_url()."user_profile_images/Default.png"?>'"  alt="Image" id="user_image">
				<span id="edit_avatar_span" class="fa fa-camera"></span>
			</div>
		</a>
		<h1 id="username"><div title="<?php if($online) echo "Currently Online"; else echo "Last online " . $time_difference;?>" class="<?php if($online) echo "green_dot"; else echo "red_dot" ?>"></div><?php echo $user['username'];?></h1>
		<div id="user_navigation_div">
			<a href="<?php echo site_url("users/profile/{$user['username']}");?>"><button class="button-black navigation_item" id="timeline">Timeline</button></a>
			<a href="<?php echo site_url("watchlists/user_watchlist/{$user['username']}");?>"><label class="button-black navigation_item" id="watchlist" onClick="">Watchlist</label></a>
			<a href="#"><label class="button-black navigation_item" id="groups" onClick="">Groups</label></a>	
			<a href="<?php echo site_url("reviews/user_reviews/{$user['username']}");?>"><button class="button-black navigation_item" id="reviews" onClick="">Reviews</button></a>	
			<a href="#"><label class="button-black navigation_item" id="recommendations" onClick="">Recommendations</label></a>	
			<a href="#"><label class="button-black navigation_item" id="followers" onClick="">Followers</label></a>	
			<a href="#"><label class="button-black navigation_item" id="following" onClick="">Following</label></a>
		</div>
		<?php if($is_you) {?>
		<div class="wrap_buttons_div">
			<label class="navigation_item button-black" id="show_edits" onClick="showEditFields()">Edit</label>			
				<form action="<?php echo site_url("userUpdates/update_user_pictures")?>" method="post" enctype="multipart/form-data">
				<input type="submit" class="navigation_item button-black" name="submit_info" id="submit_info" value="Save">
				<input type="file" name="edit_cover" accept="image/*" id="edit_cover_button"><label for="edit_cover_button" class="navigation_item button-black" id="edit_cover_label"><span class="glyphicon glyphicon-pencil"></span> Edit Cover</label>
				<input type="file" name="edit_avatar" accept="image/*" id="edit_avatar_button">
				<?php if($this->session->flashdata('error')) { 
					  if(strpos($this->session->flashdata('error'), "You did not select a file to upload") == FALSE)
					  	echo $this->session->flashdata('error');
				  } 
				  if($this->session->flashdata('error_a')) {
				  	if(strpos($this->session->flashdata('error_a'), "You did not select a file to upload") == FALSE)
				  		echo $this->session->flashdata('error_a');
				  }
				?>
		<?php } else { ?>
		<div class="wrap_buttons_div">
			<button class="btn btn-primary button-blue" id="follow_button" onClick="">Follow</button>	
		</div>
		<?php }?>	
				<input type="hidden" name="top_offset" id="top_offset" value="<?php echo $user['top_offset'];?>">	
		<?php if($is_you) {?>		
			</form>
		</div>
		<?php }?>
	</div>
</div>