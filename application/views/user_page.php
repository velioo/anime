<?php include 'head.php'; include 'navigation.php';?>

<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $results['username'])) 
		$is_you = TRUE;
	else 
		$is_you = FALSE;
?>

<script type="text/javascript">
	$(document).ready(function() {
		document.getElementById("timeline").style.opacity = "1";	
		$("#save_user_info").on('click', function(e){
		    e.preventDefault(); 
		    var textValue = document.getElementById("user_description_area").value;
			var ageValue = document.getElementById("age_edit").value;
			var genderValue = document.getElementById("gender_edit").value;
			var locationValue = document.getElementById("location_edit").value;
		    
		    $.ajax({
		          method: "POST",
		          url: '<?php echo site_url("UserUpdates/update_user_info")?>',
		          data: { textAreaValue: textValue, age: ageValue, gender: genderValue, location: locationValue }
		        })
		      .done(function( msg ) {
					
		      });    
		    showUpdateUserInfoContent();
		});	

		$("#user_description_area").keyup(function(){
			  $("#user_description_area_char_count").text("Left: " + (500 - $(this).val().length));
		});

	});
</script>

<div id="wrap">
	<?php include 'user_profile_top.php';?>
	<div class="container-fluid scrollable content" id="user_content">
		<br>
		<div id="personal_info_div">
			<div class="div_title">
				<p>About <?php echo $results['username'];?>
				<?php if($is_you) {?>
					<span class="fa fa-pencil edit_icon" id="edit_user_info" onClick="showUserInfoEdit()" ></span> 
					<button class="btn btn-primary save_button" id="save_user_info"  onClick="">Save</button>
				<?php }?>
				</p>			
			</div>
			<div class="div_content" id="show_content_div">
				<p id="user_description"><?php echo htmlspecialchars($results['bio']);?></p>
				<p class="personal_info">Joined on: <?php echo $results['joined_on'];?></p>
				<?php if($results['show_age'] == 1) {?>
					<p class="personal_info" id="age">Age: <?php echo $results['age'];?></p>
				<?php }?>	
				<?php if($results['gender'] != "") {?>
				<?php if($results['gender'] == "male") {?>
					<p class="personal_info" id="gender">Gender: <i class="fa fa-mars"></i></p>
				<?php } else if($results['gender'] == "female"){?>
					<p class="personal_info">Gender: <i class="fa fa-venus"></i><p>
				<?php } else { ?>
					<p class="personal_info">Gender: <i class="fa fa-genderless"></i></p>
				<?php }}?>
				<?php if($results['country'] != "") {?>
					<p class="personal_info" id="country"><i class="fa fa-home"></i> Lives in: <?php echo "<strong>" . $results['country'] . "</strong>";?></p>	
				<?php }?>			
			</div>
			<?php if($is_you) {?>
				<div class="div_content" id="edit_content_div">
					<label for="user_bio" style="margin-right:10px;">Description </label><label for="user_bio" id="user_description_area_char_count">Left <?php ?></label>
					<input type="hidden" value="">
					<textarea name="user_bio" rows="4" cols="43" maxlength="500" id="user_description_area" placeholder="Describe yourself here..."><?php echo trim($results['bio']);?></textarea>
					<br><br>
					<label for="age_edit" style="margin-right:10px;">Age </label><input name="age_edit" type="text" id="age_edit" placeholder="Write your age..." value="<?php echo $results['age']?>">
					<br><br>
					<label for="gender_edit" style="margin-right:10px;">Gender </label>
					<select name="gender_edit" id="gender_edit">
					  <option value="male" <?php if($results['gender'] == "male") echo "selected='selected'"?>>Male</option>
					  <option value="female" <?php if($results['gender'] == "female") echo "selected='selected'"?>>Female</option>
					  <option value="unknown" <?php if($results['gender'] == "unknown") echo "selected='selected'"?>>Unknown</option>
					</select>
					<br><br>
					<label for="location_edit"><i class="fa fa-home"></i>Location </label>
					<input type="text"  name="location_edit" id="location_edit" value="<?php echo $results['country'];?>">
				</div>
			<?php }?>
		</div>

	</div>
</div>



	
<?php include 'footer.php';?>