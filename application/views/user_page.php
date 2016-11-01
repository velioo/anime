<?php include 'head.php'; include 'navigation.php';?>

<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $results['username'])) 
		$is_you = TRUE;
	else 
		$is_you = FALSE;
?>

<script type="text/javascript">
	function showEditFields() {
		editUserInfo(false, 0);		
		$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/temp_disable_selection.css";?>" type="text/css" />');
		
		var e = document.getElementsByClassName("error");
		for (i = 0; i < e.length; i++) {
			e[i].style.marginTop = "155px";
		}
		 e = document.getElementsByClassName("error_a");
		for (i = 0; i < e.length; i++) {
			e[i].style.marginTop = "155px";
		}
		
	}
</script>

<div id="wrap">
	<?php $random_num = time();?>
	<div id="user-bar" style="background-image:url('<?php if($results['cover_image'] != "") echo asset_url() . "user_cover_images/" . $results['cover_image'] . "?rand={$random_num}"; else echo asset_url() . "user_cover_images/Default.jpg"?>'); ">
		<div class="container-fluid top-container">		
			<a href="#" class="thumbnail"><div id="user_image_div"><img src="<?php echo asset_url() . "user_profile_images/" . $results['profile_image'] ."?rand={$random_num};"?>" onerror="this.src='<?php echo asset_url()."user_profile_images/Default.png"?>'"  alt="Image" id="user_image"></div></a>
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
				<button class="btn btn-primary dropdown-toggle" id="show_edits" onClick="showEditFields()">Edit</button>			
				<form action="<?php echo site_url("UserUpdates/update_profile")?>" method="post" enctype="multipart/form-data">
					<input type="file" name="edit_cover" accept="image/*" id="edit_cover_button"><label for="edit_cover_button" id="edit_cover_label"><span class="glyphicon glyphicon-pencil"></span> Edit Cover</label>
					<input type="file" name="edit_avatar" accept="image/*" id="edit_avatar_button"><label for="edit_avatar_button" id="edit_avatar_label"><span class="glyphicon glyphicon-pencil"></span> Edit Avatar</label>
			<?php }?>		
					<input type="hidden" name="top_offset" id="top_offset" value="<?php echo $results['top_offset'];?>">
			<?php if($is_you) {?>		
					<input type="submit" name="submit_info" id="submit_info" value="Save">
				</form>
			</div>
			<?php }?>
		</div>
	</div>
	<div class="container-fluid scrollable content">
		<h1><?php echo $header;?></h1>
		<button onclick="myFunction()">Try it</button>
		<p id="demo"></p>
		<?php if($is_you) { ?>
	
				
		<?php } else { ?>
				
		<?php }?>	

		<br/><br/><br/>
	</div>
</div>

	
<?php include 'footer.php';?>