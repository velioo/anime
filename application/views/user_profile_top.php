<?php $random_num = time();?>
<div id="user-bar" style="background-image:url('<?php if($results['cover_image'] != "") { echo asset_url() . "user_cover_images/" . $results['cover_image']; if($this->session->flashdata('new_cover')) echo "?rand={$random_num}"; } else echo asset_url() . "user_cover_images/Default.jpg"?>'); ">
	<div class="container-fluid top-container">		
		<a href="#" class="thumbnail">
			<div id="user_image_div">
				<img src="<?php echo asset_url() . "user_profile_images/" . $results['profile_image']; if($this->session->flashdata('new_avatar')) echo "?rand={$random_num}"; ?>" onerror="this.src='<?php echo asset_url()."user_profile_images/Default.png"?>'"  alt="Image" id="user_image">
				<span id="edit_avatar_span" class="fa fa-camera"></span>
			</div>
		</a>
		<h1 id="username"><?php echo $results['username'];?></h1>
		<div id="user_navigation_div">
			<a href="<?php echo site_url("users/profile/{$results['username']}");?>"><button class="btn btn-primary button-black" id="timeline">Timeline</button></a>
			<a href="#"><button class="btn btn-primary button-black" onClick="">Anime</button></a>
			<a href="#"><button class="btn btn-primary button-black" onClick="">Groups</button></a>	
			<a href="<?php echo site_url("reviews/user_reviews/{$results['username']}");?>"><button class="btn btn-primary button-black" id="reviews" onClick="">Reviews</button></a>	
			<a href="#"><button class="btn btn-primary button-black" onClick="">Recommendations</button></a>	
			<a href="#"><button class="btn btn-primary button-black" onClick="">Followers</button></a>	
			<a href="#"><button class="btn btn-primary button-black" onClick="">Following</button></a>	
		</div>
		<?php if($is_you) {?>
		<div class="wrap_buttons_div">
			<button class="btn btn-primary button-black" id="show_edits" onClick="showEditFields()">Edit</button>			
				<form action="<?php echo site_url("userUpdates/update_user_pictures")?>" method="post" enctype="multipart/form-data">
				<input type="submit" class="button-black" name="submit_info" id="submit_info" value="Save">
				<input type="file" name="edit_cover" accept="image/*" id="edit_cover_button"><label for="edit_cover_button" class="button-black" id="edit_cover_label"><span class="glyphicon glyphicon-pencil"></span> Edit Cover</label>
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
				<input type="hidden" name="top_offset" id="top_offset" value="<?php echo $results['top_offset'];?>">	
		<?php if($is_you) {?>		
			</form>
		</div>
		<?php }?>
	</div>
</div>