<?php $random_num = time();?>
<div id="anime-bar" style="background-image:url('<?php if($anime['cover_image_file_name'] != ""){ echo asset_url() . "anime_cover_images/" . $anime['cover_image_file_name']; if($this->session->flashdata('new_cover')) echo "?rand={$random_num}"; } else echo asset_url() . "anime_cover_images/Default.jpg"?>'); ">
	<div class="container-fluid top-container">		
		<?php if($is_admin) {?>
		<div id="edit_div">
			<label class="button-black admin_item" id="show_edits" onClick="showEditFields()">Edit</label>			
			<form action="<?php echo site_url("animeUpdates/update_anime/" . $anime['id']);?>" method="post" enctype="multipart/form-data">
				<input type="submit" class="button-black admin_item" name="submit_info" id="submit_info" value="Save">
				<input type="file" name="edit_cover" accept="image/*" id="edit_cover_button"><label for="edit_cover_button" class="button-black admin_item" id="edit_cover_label"><span class="glyphicon glyphicon-pencil"></span> Edit Cover</label>
				<input type="file" name="edit_poster" accept="image/*" id="edit_poster_button">
				<?php if($this->session->flashdata('error')) { 
					  if(strpos($this->session->flashdata('error'), "You did not select a file to upload") == FALSE)
					  	echo $this->session->flashdata('error');
				  } 
				  if($this->session->flashdata('error_a')) {
				  	if(strpos($this->session->flashdata('error_a'), "You did not select a file to upload") == FALSE)
				  		echo $this->session->flashdata('error_a');
				  }
				?>
		<?php } ?> 	
				<input type="hidden" name="top_offset" id="top_offset" value="<?php echo $anime['cover_image_top_offset'];?>">	
		<?php if($is_admin) {?>		
			</form>
		</div>
		<?php }?>
	</div>
</div>