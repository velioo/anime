<?php $random_num = time();?>
<div id="anime-bar" style="background-image:url('<?php if($anime['cover_image_file_name'] != "") echo asset_url() . "anime_cover_images/" . $anime['cover_image_file_name'] . "?rand={$random_num}"; else echo asset_url() . "anime_cover_images/Default.jpg"?>'); ">
	<div class="container-fluid top-container">		
		<?php if($is_admin) {?>
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
			<form action="<?php echo site_url("animeUpdates/update_anime/" . $anime['id']);?>" method="post" enctype="multipart/form-data">
				<input type="file" name="edit_cover" accept="image/*" id="edit_cover_button"><label for="edit_cover_button" id="edit_cover_label"><span class="glyphicon glyphicon-pencil"></span> Edit Cover</label>
		<?php } ?> 	
				<input type="hidden" name="top_offset" id="top_offset" value="<?php echo $anime['cover_image_top_offset'];?>">
		<?php if($is_admin) {?>		
				<input type="submit" name="submit_info" id="submit_info" value="Save">
			</form>
		</div>
		<?php }?>
	</div>
</div>