<?php include 'head.php';?>

<link rel="stylesheet" href="<?php echo asset_url() . "css/user_navigation_bar.css";?>" type="text/css" />

<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $user['username'])) 
		$is_you = TRUE;
	else 
		$is_you = FALSE;
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('head').append('<script src="<?php echo asset_url() . "js/edit_user_info.js";?>">');	
	});
	<?php if($is_you) { ?>
	function showEditFields() {
			editUserInfo(false, 0);		
			$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/temp_disable_selection.css";?>" type="text/css" />');		
		}
	<?php }?>
</script>

<?php include 'navigation.php'; ?>

<div id="wrap">
	<?php include 'user_profile_top.php';?>
	<div class="container-fluid scrollable content" id="user_content">
		<br>
		<div id="personal_info_div">
			<div class="div_title col-sm-12">
				<p>About <?php echo $user['username'];?>
				<?php if($is_you) {?>
					<span class="fa fa-pencil edit_icon" id="edit_user_info" onClick="showUserInfoEdit()" ></span> 
					<button class="btn btn-primary button-black" id="save_user_info"  onClick="updateDb(<?php echo "'" . site_url("userUpdates/update_user_info") . "'"; ?>)">Save</button>
				<?php }?>
				</p>			
			</div>
			<div class="div_content col-sm-12" id="show_content_div">
				<p id="user_description"><?php echo htmlspecialchars($user['bio']);?></p>
				<p class="personal_info">Joined on: <?php echo $user['joined_on'];?></p>
				<?php if($user['show_age'] == 1) {?>
					<p class="personal_info" id="age">Age: <?php echo $user['age'];?></p>
				<?php }?>	
				<?php if($user['gender'] != "") {?>
				<?php if($user['gender'] == "male") {?>
					<p class="personal_info" id="gender">Gender: <i class="fa fa-mars"></i></p>
				<?php } else if($user['gender'] == "female"){?>
					<p class="personal_info" id="gender">Gender: <i class="fa fa-venus"></i><p>
				<?php } else { ?>
					<p class="personal_info" id="gender">Gender: <i class="fa fa-genderless"></i></p>
				<?php }}?>
				<?php if($user['country'] != "") {?>
					<p class="personal_info" id="country"><i class="fa fa-home"></i> Lives in: <?php echo "<strong>" . $user['country'] . "</strong>";?></p>	
				<?php }?>			
			</div>
			<?php if($is_you) {?>
				<div class="div_content col-sm-12" id="edit_content_div">
					<label for="user_bio" style="margin-right:10px;">Description </label><label for="user_bio" id="user_description_area_char_count">Left <?php ?></label>
					<input type="hidden" value="">
					<textarea name="user_bio" rows="4" cols="43" maxlength="500" id="user_description_area" placeholder="Describe yourself here..."><?php echo trim($user['bio']);?></textarea>
					<br><br>
					<label for="age_edit" style="margin-right:10px;">Age </label><input name="age_edit" type="text" id="age_edit" placeholder="Write your age..." value="<?php echo $user['age']?>">
					<p id="wtf_age"></p>
					<br><br>
					<label for="gender_edit" style="margin-right:10px;">Gender </label>
					<select name="gender_edit" id="gender_edit">
					  <option value="male" <?php if($user['gender'] == "male") echo "selected='selected'"?>>Male</option>
					  <option value="female" <?php if($user['gender'] == "female") echo "selected='selected'"?>>Female</option>
					  <option value="unknown" <?php if($user['gender'] == "unknown") echo "selected='selected'"?>>Unknown</option>
					</select>
					<br><br>
					<label for="location_edit"><i class="fa fa-home"></i>Location </label>
					<input type="text"  name="location_edit" id="location_edit" value="<?php echo $user['country'];?>">
				</div>
			<?php }?>
		</div>

	</div>
</div>
	
<?php include 'footer.php';?>