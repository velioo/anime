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
		$('head').append('<script src="<?php echo asset_url() . "js/character.js";?>">');			
	});
</script>

<?php include 'navigation.php'; ?>

<div id="wrap">
	<div class="container-fluid scrollable" id="character_div">	
		<p id="character_name"><?php echo $character['first_name'] . " " . $character['last_name']; if($character['japanese_name'] != "") echo "(" . $character['japanese_name'] . ")";?></p>
		<div class="wrap_left_side_div">
			<img src="<?php echo asset_url() . "character_images/" . $character['image_file_name'];?>" id="character_image">
			<p id="aliases"><strong>Aliases: </strong><p>
			<p id="aliases_text"><?php echo $character['alt_name'];?></p>
		</div>
		<div id="character_info_div">
			<div class="more"><?php echo $character['info'];?></div>		
			<span class="i_span">I </span><div class="wrap_user_status">
	    		<span title="I love this character" class="fa-stack fa-2x love">
	    			<i class="fa fa-heart"></i>
	    		</span>
    			<span title="I hate this character" class="fa-stack fa-2x hate">
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
									<a href="#" class="disable-link-decoration blue-text actor_name">
									<?php if($actor['language'] != "") { ?> 
										<img src="<?php echo asset_url() . "imgs/{$actor['language']}.png";?>" class="actor_flag_image">
									<?php }?>					
									<?php echo $actor['first_name'] . " " . $actor['last_name']?></a>
								<?php }?>
							</td>
						</tr>
						<?php }?>
				    </tbody>
			  </table>
		</div>
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