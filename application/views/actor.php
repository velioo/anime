<?php include 'head.php';?>

<?php
	if(isset($this->session->userdata['is_logged_in'])) 
		$logged = TRUE;
	else 
		$logged = FALSE;
?>

<?php 
	if(isset($this->session->userdata['admin'])) {
		if($this->session->userdata['admin'] === TRUE and $logged === TRUE) {
			$is_admin = TRUE;
		}
	} else {
		$is_admin = FALSE;
	}
?>

<script>
	$(document).ready(function() {
		$('head').append('<script src="<?php echo asset_url() . "js/actor.js";?>">');			
		$('head').append('<script src="<?php echo asset_url() . "js/actor_user_status.js";?>">');								
	});

	function getActorId() {
		var actor_id = <?php echo $actor['id'];?>;
		return actor_id;
	}

	function getActorName() {
		var actor_name = "<?php echo $actor['first_name'] . " " . $actor['last_name'];?>";
		return actor_name;
	}
	
	function getActorUserStatusUrl() {
		var actor_status_url = "<?php echo site_url("actors/change_actor_user_status");?>";
		return actor_status_url;
	}

	function getActorUserStatusLoadUrl() {
		var actor_user_status_load_url = "<?php echo site_url("actors/load_actor_users_statuses/");?>";
		return actor_user_status_load_url;
	}

	function getTotalLoveGroups() {
		var total_love_groups = <?php echo $total_love_groups;?>;
		return total_love_groups;
	}

	function getTotalHateGroups() {
		var total_hate_groups = <?php echo $total_hate_groups;?>;
		return total_hate_groups;
	}
</script>

<?php include 'navigation.php';?>

<div id="wrap">
	<div class="container-fluid scrollable" id="actor_div">	
		<p id="actor_name"><?php echo stripslashes($actor['first_name']) . " " . stripslashes($actor['last_name']); if($actor['first_name_japanese'] != "") echo "(" . $actor['first_name_japanese'] . " " . $actor['last_name_japanese'] . ")";?></p>
		<div class="wrap_left_side_div">
			<img src="<?php echo asset_url() . "actor_images/" . $actor['image_file_name'];?>" id="actor_image">
			<div id="wrap_left_side_without_image">
				<div class="user_statuses">
					<p class="user_status_header"><?php echo $actor['actor_love_count']; if($actor['actor_love_count'] != 1) echo " users "; else echo " user ";?>
						<span class="fa-stack fa-2x static_heart">
		    				<i class="fa fa-heart"></i>
		    			</span>
		    			<a href="#"><span class="see_all all_loved red-text">See all users</span></a>
		    		</p>
		    		<?php if(count($actor['actor_love_count']) >= 20) $limit = 20; else $limit = $actor['actor_love_count'];
		    			for($i = 0; $i < $limit; $i++) {?>
		    				<a href="<?php echo site_url("users/profile/{$actor['actor_love'][$i]['username']}")?>" class="disable-link-decoration red-text"><?php echo $actor['actor_love'][$i]['username'];?></a>, 
		    		<?php }?>
				</div>
				<div class="user_statuses" style="margin-top: 10px;">
					<p class="user_status_header"><?php echo $actor['actor_hate_count']; if($actor['actor_hate_count'] != 1) echo " users "; else echo " user ";?>
						<span class="fa-stack fa-2x static_heart" style="margin-left: -10px;">
						    <i class="fa fa-heart fa-stack-1x"></i>
						    <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
						</span>	
						<a href="#"><span class="see_all all_hated red-text">See all users</span></a>	
		    		</p>
		    		<?php if(count($actor['actor_hate_count']) >= 20) $limit = 20; else $limit = $actor['actor_hate_count'];
		    			for($i = 0; $i < $limit; $i++) {?>
		    				<a href="<?php echo site_url("users/profile/{$actor['actor_hate'][$i]['username']}")?>" class="disable-link-decoration red-text"><?php echo $actor['actor_hate'][$i]['username'];?></a>, 
		    		<?php }?>
				</div>
			</div>
			<div id="show_actor_info">
				<a href="#"><button class="show_actor_button button-blue">Show actor info</button></a>
			</div>
		</div>
		<div id="actor_info_div">
			<p><strong>Language: </strong><img src="<?php echo asset_url() . "imgs/{$actor['language']}.png";?>" class="actor_flag_image"><?php echo " " . $actor['language'];?></p>
			<div class="more"><?php echo $actor['info'];?></div>		
			<?php if(isset($actor['actor_user_status'])) { 
    					if($actor['actor_user_status'] == 1){ $actor_user_status = 1; }
    					else if($actor['actor_user_status'] == 0) {$actor_user_status = 0;} } else {$actor_user_status = 2;}		    				
    		?>
			<span class="i_span">I </span>
			<div class="wrap_user_status" data-id="<?php echo $actor['id'];?>">
    			<span title="I love this actor" class="fa-stack fa-2x love <?php if($actor_user_status == 1) echo "love_on";?>" data-value="1">
	    			<i class="fa fa-heart"></i>
	    		</span>
    			<span title="I hate this actor" class="fa-stack fa-2x hate <?php if($actor_user_status == 0) echo "hate_on";?>" data-value="0">
				    <i class="fa fa-heart fa-stack-1x"></i>
				    <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
				</span>
			</div>	
			<div id="actor_animes_actors_div">	
			   <p id="anime_actor_header">Voice Actor</p>
			   <table class="table">
			   <thead>
			   		<tr>
			   			<th>Anime</th>
			   			<th>Character</th>
			   		</tr>
			   </thead> 
				    <tbody>
				    	<?php foreach($actor['animes_characters'] as $anime_character) {?>
						<tr class="actor_anime_actor_row">
							<td class="anime">
								<a href="<?php echo site_url("animeContent/anime/{$anime_character['slug']}");?>" class="disable-link-decoration">
									<img src="<?php echo asset_url(). "poster_images/" . $anime_character['anime_image']?>" class="anime_image">
								</a>
								
								<div class="wrap_anime_name_div">
							    	<a href="<?php echo site_url("animeContent/anime/{$anime_character['slug']}");?>" class="disable-link-decoration">
										<span class="red-text"><?php echo convert_titles_to_hash($anime_character['titles'])['main'];?></span>
									</a>
							    </div>							
							</td>
							<td class="character">
								<a href="<?php echo site_url("characters/character/{$anime_character['character_id']}/{$anime_character['character_slug']}");?>" class="disable-link-decoration">
									<img src="<?php echo asset_url(). "character_images/" . $anime_character['character_image']?>" class="character_image">
								</a>
								<div class="wrap_character_name_div">
									<a href="<?php echo site_url("characters/character/{$anime_character['character_id']}/{$anime_character['character_slug']}");?>" class="disable-link-decoration">
										<span class="character_name red-text"><?php echo $anime_character['first_name'] . " " . $anime_character['last_name'];?></span>
									</a>
								</div>
							</td>
						</tr>
						<?php }?>
				    </tbody>
			  </table>
		</div>
		</div>
		<div id="all_users">
			<p class="user_status_header"></p>
			<table class="table">
			   <thead></thead> 
				    <tbody id="user_status_tbody">						
				    </tbody>
			  </table>			
		</div>
		<div id="loader_image_div">
			<img src="<?php echo asset_url() . "imgs/loading_records.gif";?>" class="loader_image">
		</div>
	</div>	
</div>


<?php include 'footer.php';?>

<div id="actor_modal" class="modal">
  <div id="center_div">
	  <span class="close" id="close_modal">×</span>
	  <img class="modal-content" id="modal_image">
  </div>
</div>

<?php if(!$logged) { include 'login_modal.php'; }?>