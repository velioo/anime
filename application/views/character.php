<?php include 'head.php';?>
<?php include 'navigation.php';?>

<script>
	$(document).ready(function() {
		$('head').append('<script src="<?php echo asset_url() . "js/character.js";?>">');			
		$('head').append('<script src="<?php echo asset_url() . "js/character_user_status.js";?>">');								
	});

	function getCharacterId() {
		var character_id = <?php echo $character['id'];?>;
		return character_id;
	}

	function getCharacterName() {
		var character_name = "<?php echo $character['first_name'] . " " . $character['last_name'];?>";
		return character_name;
	}
	
	function getCharacterUserStatusUrl() {
		var character_status_url = "<?php echo site_url("characters/change_character_user_status");?>";
		return character_status_url;
	}

	function getCharacterUserStatusLoadUrl() {
		var character_user_status_load_url = "<?php echo site_url("characters/load_character_users_statuses/");?>";
		return character_user_status_load_url;
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

<div id="wrap">
	<div class="container-fluid scrollable" id="character_div">	
		<p id="character_name"><?php echo stripslashes($character['first_name']) . " " . stripslashes($character['last_name']); if($character['japanese_name'] != "") echo "(" . $character['japanese_name'] . ")";?></p>
		<div class="wrap_left_side_div">
			<img src="<?php echo asset_url() . "character_images/" . $character['image_file_name'];?>" id="character_image">
			<div id="wrap_left_side_without_image">
				<p id="aliases"><strong>Aliases: </strong><p>
				<p id="aliases_text"><?php echo $character['alt_name'];?></p>
				<div class="user_statuses">
					<p class="user_status_header"><?php echo $character['character_love_count']; if($character['character_love_count'] != 1) echo " users "; else echo " user ";?>
						<span class="fa-stack fa-2x static_heart">
		    				<i class="fa fa-heart"></i>
		    			</span>
		    			<a href="#"><span class="see_all all_loved red-text">See all users</span></a>
		    		</p>
		    		<?php if(count($character['character_love_count']) >= 20) $limit = 20; else $limit = $character['character_love_count'];
		    			for($i = 0; $i < $limit; $i++) {?>
		    				<a href="<?php echo site_url("users/profile/{$character['character_love'][$i]['username']}")?>" class="disable-link-decoration red-text"><?php echo $character['character_love'][$i]['username'];?></a>, 
		    		<?php }?>
				</div>
				<div class="user_statuses" style="margin-top: 10px;">
					<p class="user_status_header"><?php echo $character['character_hate_count']; if($character['character_hate_count'] != 1) echo " users "; else echo " user ";?>
						<span class="fa-stack fa-2x static_heart" style="margin-left: -10px;">
						    <i class="fa fa-heart fa-stack-1x"></i>
						    <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
						</span>	
						<a href="#"><span class="see_all all_hated red-text">See all users</span></a>	
		    		</p>
		    		<?php if(count($character['character_hate_count']) >= 20) $limit = 20; else $limit = $character['character_hate_count'];
		    			for($i = 0; $i < $limit; $i++) {?>
		    				<a href="<?php echo site_url("users/profile/{$character['character_hate'][$i]['username']}")?>" class="disable-link-decoration red-text"><?php echo $character['character_hate'][$i]['username'];?></a>, 
		    		<?php }?>
				</div>
			</div>
			<div id="show_character_info">
				<a href="#"><button class="show_character_button button-blue">Show character info</button></a>
			</div>
		</div>
		<div id="character_info_div">
			<div class="more"><?php echo $character['info'];?></div>		
			<?php if(isset($character['character_user_status'])) { 
    					if($character['character_user_status'] == 1){ $character_user_status = 1; }
    					else if($character['character_user_status'] == 0) {$character_user_status = 0;} } else {$character_user_status = 2;}		    				
    		?>
			<span class="i_span">I </span>
			<div class="wrap_user_status" data-id="<?php echo $character['id'];?>">
    			<span title="I love this character" class="fa-stack fa-2x love <?php if($character_user_status == 1) echo "love_on";?>" data-value="1">
	    			<i class="fa fa-heart"></i>
	    		</span>
    			<span title="I hate this character" class="fa-stack fa-2x hate <?php if($character_user_status == 0) echo "hate_on";?>" data-value="0">
				    <i class="fa fa-heart fa-stack-1x"></i>
				    <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
				</span>
			</div>	
			<div id="character_animes_actors_div">	
				<p id="anime_roles_header">Anime Roles</p>
			   <table class="table">
			   <thead></thead> 
				    <tbody>
				    	<?php foreach($character['animes'] as $anime) {?>
						<tr class="character_anime_actor_row">
							<td class="anime"><a href="<?php echo site_url("animeContent/anime/{$anime['slug']}")?>" class="disable-link-decoration red-text"><?php echo convert_titles_to_hash($anime['titles'])['main'];?></a></td>
							<td class="role"><?php echo $anime['role'];?></td>
							<td class="actors">
								<?php foreach($anime['actors'] as $actor) {?>
									<a href="<?php echo site_url("actors/actor/{$actor['id']}/{$actor['actor_slug']}");?>" class="disable-link-decoration blue-text actor_name">
									<?php if($actor['language'] != "") { ?> 
										<img src="<?php echo asset_url() . "imgs/{$actor['language']}.png";?>" class="actor_flag_image">
									<?php }?>					
									<?php echo stripslashes($actor['first_name']) . " " . stripslashes($actor['last_name'])?></a>
								<?php }?>
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

<div id="character_modal" class="modal">
  <div id="center_div">
	  <span class="close" id="close_modal">×</span>
	  <img class="modal-content" id="modal_image">
  </div>
</div>

<?php //if(!$logged) { include 'login_modal.php'; }?>