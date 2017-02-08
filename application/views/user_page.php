<?php include 'head.php';?>

<link rel="stylesheet" href="<?php echo asset_url() . "css/user_navigation_bar.css";?>" type="text/css" />
<script src="<?php echo asset_url() . "jquery.ns-autogrow-1.1.6/dist/jquery.ns-autogrow.js";?>"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="<?php echo asset_url() . "js/ratings_chart.js";?>"></script>

<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $user['username'])) {
		$is_you = TRUE;
		$logged = TRUE;
	} else {
		$is_you = FALSE;
		if(isset($this->session->userdata['is_logged_in']))
			$logged = TRUE;
		else
			$logged = FALSE;
	}
?>

<script type="text/javascript">

	var is_you = <?php if($is_you) echo 1; else echo 0;?>;
	var is_logged = <?php if($logged) echo 1; else echo 0;?>;
	var total_groups = <?php echo $total_groups;?>;
	
	$(document).ready(function() {		
		$('head').append('<script src="<?php echo asset_url() . "js/edit_user_info.js";?>">');	
		$('head').append('<script src="<?php echo asset_url() . "js/posts.js";?>">');	
		<?php if(!$is_you) {?>
			$('head').append('<script src="<?php echo asset_url() . "js/follow.js";?>">');	
		<?php }?>
		<?php if($user['birthdate'] != "0000-00-00") {$dateValues = explode("-", $user['birthdate']); } else {$dateValues[0] = "0000";  $dateValues[1] = "00"; $dateValues[2] = "00";}?>
		var dayValue = "<?php echo $dateValues[2]?>";
		var monthValue = "<?php echo $dateValues[1]?>";
		var yearValue = "<?php echo $dateValues[0]?>";

		var currentYear = new Date().getFullYear();
		var lastYear = currentYear - 100;
		var year;
		for(year = currentYear; year >= lastYear; year--) {
			$('#year_edit').append('<option value=' + year + '>' + year + '</option>');
		}
		
		$('#day_edit').val(dayValue);
		$('#month_edit').val(monthValue);
		$('#year_edit').val(yearValue);
	});
	<?php if($is_you) { ?>
	function showEditFields() {
		editUserInfo(false, 0);		
		$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/temp_disable_selection.css";?>" type="text/css" />');		
	}
	
	function getUserUpdatesUrl() {
		var user_updates_url = "<?php echo site_url("userUpdates/update_user_info");?>";
		return user_updates_url;
	}
	<?php }?>

	function getPostsUrl() {
		var load_posts_url = "<?php echo site_url("posts/load_posts");?>";
		return load_posts_url;
	}
	
	function getWallOwner() {
		var wall_owner = <?php echo $user['id'];?>;
		return wall_owner;
	}

	function getUserId() {
		var user_id = <?php echo $user['id'];?>;
		return user_id;
	}

	function getIsYou() {
		return is_you;
	}

	function getIsLogged() {
		return is_logged;
	}

	function getTotalPosts() {
		return total_groups;
	}

	function getFollowUrl() {
		var follow_url = "<?php echo site_url("follow/follow_user")?>";
		return follow_url;
	}

	function getUnfollowUrl() {
		var unfollow_url = "<?php echo site_url("follow/unfollow_user")?>";
		return unfollow_url;
	}
	
	<?php if($logged) {?>
	function getUserImage() {
		var avatar_image = "<?php echo asset_url() . "user_profile_images/" . $this->session->userdata('user_avatar');?>";
		return avatar_image;
	}

	function getUserName() {
		var username = "<?php echo $this->session->userdata('username');?>";
		return username;
	}

	function getUserUrl() {
		var user_url = "<?php echo site_url("users/profile/{$this->session->userdata('username')}");?>";
		return user_url;
	}

	function getAddPostUrl() {
		var add_post_url = "<?php echo site_url("posts/add_post");?>";
		return add_post_url;
	}

	function getEditPostUrl() {
		var edit_post_url = "<?php echo site_url("posts/edit_post");?>";
		return edit_post_url;
	}
	
	function getAddCommentUrl() {
		var add_comment_url = "<?php echo site_url("posts/add_comment");?>";
		return add_comment_url;
	}

	function getEditCommentUrl() {
		var edit_comment_url = "<?php echo site_url("posts/edit_comment");?>";
		return edit_comment_url;
	}

	function getDeletePostUrl() {
		var delete_post_url = "<?php echo site_url("posts/delete_post");?>";
		return delete_post_url;
	}

	function getDeleteCommentUrl() {
		var delete_comment_url = "<?php echo site_url("posts/delete_comment");?>";
		return delete_comment_url;
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
				<p id="user_description"><?php echo stripslashes(htmlspecialchars($user['bio']));?></p>
				<p class="personal_info">Joined on: <?php echo convert_date($user['joined_on']);?></p>
				<?php if($user['show_age'] == 1) {?>
					<p class="personal_info" id="age">Age: <?php if($user['birthdate'] != "0000-00-00"){ echo date_diff(date_create($user['birthdate']), date_create('today'))->y;} else echo "?";?></p>
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
					<p class="personal_info" id="country"><i class="fa fa-home"></i> Lives in: <?php echo "<strong>" . stripslashes($user['country']) . "</strong>";?></p>	
				<?php }?>			
			</div>
			<?php if($is_you) {?>
				<div class="div_content col-sm-12" id="edit_content_div">
					<label for="user_bio" style="margin-right:10px;">Description </label><label for="user_bio" id="user_description_area_char_count">Left</label>
					<input type="hidden" value="">
					<textarea name="user_bio" rows="4" cols="43" maxlength="1000" id="user_description_area" placeholder="Describe yourself here..."><?php echo stripslashes(htmlspecialchars(trim($user['bio'])));?></textarea>
					<br><br>
					<label style="margin-right:10px;">Birthdate:</label>
					<select name="day_edit" class="edit_birth" id="day_edit">
						<option value="00">Day</option>
						<option value="01">1</option>
						<option value="02">2</option>
						<option value="03">3</option>
						<option value="04">4</option>
						<option value="05">5</option>
						<option value="06">6</option>
						<option value="07">7</option>
						<option value="08">8</option>
						<option value="09">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>	
						<option value="30">30</option>	
						<option value="31">31</option>	
					</select>
					<select name="month_edit" class="edit_birth" id="month_edit">
						<option value="00">Month</option>
						<option value="01">January</option>
						<option value="02">February</option>
						<option value="03">March</option>
						<option value="04">April</option>
						<option value="05">May</option>
						<option value="06">June</option>
						<option value="07">July</option>
						<option value="08">August</option>
						<option value="09">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
					</select>
					<select name="year_edit" class="edit_birth" id="year_edit">
						<option value="0000">Year</option>
					</select>
					<br><br>
					<label for="gender_edit" style="margin-right:10px;">Gender </label>
					<select name="gender_edit" id="gender_edit">
					  <option value="male" <?php if($user['gender'] == "male") echo "selected='selected'"?>>Male</option>
					  <option value="female" <?php if($user['gender'] == "female") echo "selected='selected'"?>>Female</option>
					  <option value="unknown" <?php if($user['gender'] == "unknown") echo "selected='selected'"?>>Unknown</option>
					</select>
					<br><br>
					<label for="location_edit"><i class="fa fa-home"></i>Location </label>
					<input type="text"  name="location_edit" id="location_edit" value="<?php echo stripslashes($user['country']);?>">
				</div>
			<?php }?>
			<div id="user_left_side_panel">
				<div class="wrap_header">
					<p class="status_header">Life on anime</p>
				</div>
				<div class="status_div" style="background-color: white;">
					<div class="life_on_anime_div">
						<p class="life_status"><?php echo "<span class='time_number'>" . $minutes . "</span>";?><span class="time_text"><?php if($minutes == 1) echo " Minute"; else echo " Minutes";?></span></p>
						<p class="life_status"><?php echo "<span class='time_number'>" . $hours . "</span>";?><span class="time_text"><?php if($hours == 1) echo " Hour"; else echo " Hours";?></span></p>
						<p class="life_status"><?php echo "<span class='time_number'>" . $days . "</span>";?><span class="time_text"><?php if($days == 1) echo " Day"; else echo " Days";?></span></p>
						<p class="life_status"><?php echo "<span class='time_number'>" . $weeks . "</span>";?><span class="time_text"><?php if($weeks == 1) echo " Week"; else echo " Weeks";?></span></p>
						<p class="life_status"><?php echo "<span class='time_number'>" . $months . "</span>";?><span class="time_text"><?php if($months == 1) echo " Month"; else echo " Months";?></span></p>
						<p class="life_status"><?php echo "<span class='time_number'>" . $years . "</span>";?><span class="time_text"><?php if($years == 1) echo " Year"; else echo " Years";?></span></p>
					</div>					
				</div>
				<div class="wrap_header">
					<p class="status_header">Watchlist status</p>
				</div>
				<div class="status_div" style="background-color: white;">
					<div class="wrap_watchlist_status_div">
						<p><span class="status-square blue"></span><?php echo "<span class='status_number'>" . $user['watched'] . "</span>";?> Watched</p>
						<p><span class="status-square green"></span><?php echo "<span class='status_number'>" . $user['watching'] . "</span>";?> Watching</p>
						<p><span class="status-square yellow"></span><?php echo "<span class='status_number'>" . $user['want_to_watch'] . "</span>";?> Want to Watch</p>
						<p><span class="status-square orange"></span><?php echo "<span class='status_number'>" . $user['stalled'] . "</span>";?> Stalled</p>
						<p><span class="status-square red"></span><?php echo "<span class='status_number'>" .  $user['dropped'] . "</span>";?> Dropped</p>
					</div>
					<div class="all_episodes_div">
						<p class="total_episodes_paragraph"><span class="total_episodes"><?php echo number_format($user['total_episodes']);?></span><span class="total_episodes_text">Total episodes</span></p>
					</div>
				</div>
				<div class="wrap_header">
					<p class="status_header">Anime Ratings</p>
				</div>
				<div class="status_div" style="background-color: white;">
					<div id="ratings_chart_div" data-scores="<?php echo implode(",", $scores);?>">
					</div>
				</div>
				<div class="wrap_header">
					<p class="status_header">I adore these characters</p>
					<a href="<?php echo site_url("characters/loves/{$user['username']}");?>" class="disable-link-decoration"><button class="see_all_characters_button button-black">See all</button></a>	
				</div>	
				<div class="status_div">
					<?php if(count($random_loved_characters) >= 5) $limit = 5; else $limit = count($random_loved_characters); for($i = 0; $i < $limit; $i++) {  
							$character_slug = "";
							if($random_loved_characters[$i]['first_name'] != "") {
								$character_slug.=$random_loved_characters[$i]['first_name'];
							}
							if($random_loved_characters[$i]['last_name'] != "") {
								if($character_slug != "")
									$character_slug.="-";
									$character_slug.=$random_loved_characters[$i]['last_name'];
							}
							
							$character_slug = preg_replace('/[^\00-\255]+/u', ' ', $character_slug);
							$character_slug = str_replace(" ", "-", $character_slug);
					?>			
						<div class="tooltip_m">			
							<a href="<?php echo site_url("characters/character/{$random_loved_characters[$i]['id']}/{$character_slug}")?>" class="disable-link-decoration">						
							<div class="character_image_div"><img src="<?php echo asset_url() . "character_images/{$random_loved_characters[$i]['image_file_name']}";?>" class="character_image"></div></a>
							<span class="tooltiptext"><?php echo $random_loved_characters[$i]['first_name'] . " " . $random_loved_characters[$i]['last_name'];?></span>
						</div>
					<?php }?>							
				</div>
				<div class="wrap_header">
					<p class="status_header">I'm not a fan of these characters</p>
					<a href="<?php echo site_url("characters/hates/{$user['username']}");?>" class="disable-link-decoration"><button class="see_all_characters_button button-black">See all</button></a>	
				</div>
				<div class="status_div">
					<?php if(count($random_hated_characters) >= 5) $limit = 5; else $limit = count($random_hated_characters); for($i = 0; $i < $limit; $i++) {  
							$character_slug = "";
							if($random_hated_characters[$i]['first_name'] != "") {
								$character_slug.=$random_hated_characters[$i]['first_name'];
							}
							if($random_hated_characters[$i]['last_name'] != "") {
								if($character_slug != "")
									$character_slug.="-";
									$character_slug.=$random_hated_characters[$i]['last_name'];
							}
						?>			
						<div class="tooltip_m">				
							<a href="<?php echo site_url("characters/character/{$random_hated_characters[$i]['id']}/{$character_slug}")?>" class="disable-link-decoration">							
							<div class="character_image_div"><img src="<?php echo asset_url() . "character_images/{$random_hated_characters[$i]['image_file_name']}";?>" class="character_image"></div></a>
							<span class="tooltiptext"><?php echo $random_hated_characters[$i]['first_name'] . " " . $random_hated_characters[$i]['last_name'];?></span>
						</div>
					<?php }?>	
				</div>
			</div>
		</div>
		<div id="wrap_right_side">
			<?php if($logged) {?>
			<div id="wrap_area_options">
				<textarea rows="2" id="new_post_area" name="new_post_area" placeholder="Write Something..."></textarea>
				<div id="post_options">
					<label id="submit_post" class="button-blue">Post</label>
				</div>
			</div>
			<?php } else {?>
				<div class="not_logged"><span class="disable-link-decoration blue-text log_in_modal">Log in</span> to add posts, comments and follow</div>
			<?php }?>
			<div id="timeline_div">
				<div id="loader_image_div">
					<img src="<?php echo asset_url() . "imgs/loading_records.gif";?>" class="loader_image">
				</div>
			</div>
		</div>
	</div>
</div>
	
<?php include 'footer.php';?>

<div id="confirm_delete_modal" class="modal fade" role="dialog">
	<div class="wrap_modal_elements">
	<div class="modal-dialog">
        <div class="modal-content">
		    <div class="modal-header">
		        <a href="#" data-dismiss="modal" class="close">&times;</a>
		         <h3 class="modal_title"></h3>
		    </div>
		    <div class="modal-body">
		        <p>You are about to delete your <span class="modal_subtitle">post</span>, this procedure is irreversible.</p>
		        <p>Do you want to proceed?</p>
		    </div>
		    <div class="modal-footer">
		        <a class="btn danger">Yes</a>
		        <a data-dismiss="modal" class="btn secondary">No</a>
		    </div>
	    </div>
    </div>
    </div>
</div>

<?php if(!$logged) { include 'login_modal.php'; }?>