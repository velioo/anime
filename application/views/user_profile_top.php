<?php $random_num = time();?>
<div id="user-bar" style="background-image:url('<?php if($results['cover_image'] != "") echo asset_url() . "user_cover_images/" . $results['cover_image'] . "?rand={$random_num}"; else echo asset_url() . "user_cover_images/Default.jpg"?>'); ">
	<div class="container-fluid top-container">		
		<a href="#" class="thumbnail"><div id="user_image_div"><img src="<?php echo asset_url() . "user_profile_images/" . $results['profile_image'] ."?rand={$random_num};"?>" onerror="this.src='<?php echo asset_url()."user_profile_images/Default.png"?>'"  alt="Image" id="user_image"></div></a>
		<h1 id="username"><?php echo $results['username'];?></h1>
		<div id="user_navigation_div">
			<a href="#"><button class="btn btn-primary user_navigation" id="timeline" onClick="">Timeline</button></a>
			<a href="#"><button class="btn btn-primary user_navigation" class="user_navigation" onClick="">Anime</button></a>
			<a href="#"><button class="btn btn-primary user_navigation" onClick="">Groups</button></a>	
			<a href="#"><button class="btn btn-primary user_navigation" onClick="">Reviews</button></a>	
			<a href="#"><button class="btn btn-primary user_navigation" onClick="">Recommendations</button></a>	
			<a href="#"><button class="btn btn-primary user_navigation" onClick="">Followers</button></a>	
			<a href="#"><button class="btn btn-primary user_navigation" class="user_navigation" onClick="">Following</button></a>	
		</div>
		<?php if($is_you) {?>
		<div id="edit_div">
			<?php if($this->session->flashdata('error')) { 
					  if(strpos($this->session->flashdata('error'), "You did not select a file to upload") == FALSE)
					  	echo $this->session->flashdata('error');
				  } 
				  if($this->session->flashdata('error_a')) {
				  	if(strpos($this->session->flashdata('error_a'), "You did not select a file to upload") == FALSE)
				  		echo $this->session->flashdata('error_a');
				  }
			?>
			<button class="btn btn-primary" id="show_edits" onClick="showEditFields()">Edit</button>			
			<form action="<?php echo site_url("UserUpdates/update_profile")?>" method="post" enctype="multipart/form-data">
				<input type="file" name="edit_cover" accept="image/*" id="edit_cover_button"><label for="edit_cover_button" id="edit_cover_label"><span class="glyphicon glyphicon-pencil"></span> Edit Cover</label>
				<input type="file" name="edit_avatar" accept="image/*" id="edit_avatar_button"><label for="edit_avatar_button" id="edit_avatar_label"><span class="glyphicon glyphicon-pencil"></span> Edit Avatar</label>
		<?php } else { ?>
		<div id="follow_div">
			<button class="btn btn-primary" id="follow_button" onClick="">Follow</button>	
		</div>
		<?php }?>	
				<input type="hidden" name="top_offset" id="top_offset" value="<?php echo $results['top_offset'];?>">
		<?php if($is_you) {?>		
				<input type="submit" name="submit_info" id="submit_info" value="Save">
			</form>
		</div>
		<?php }?>
	</div>
</div>