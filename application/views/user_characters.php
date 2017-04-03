<?php include 'head.php';?>
<?php include 'navigation.php';?>

<link rel="stylesheet" href="<?php echo asset_url() . "css/user_navigation_bar.css";?>" type="text/css" />
<script src="<?php echo asset_url() . "js/edit_user_info.js";?>"></script>
<script src="<?php echo asset_url() . "js/follow.js";?>"></script>
   
<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $user['username'])) {
		$is_you = TRUE;
	} else {
		$is_you = FALSE;
	}
?>

<script type="text/javascript">
	$('head').append('<script src="<?php echo asset_url() . "js/character_user_status.js";?>">');
	<?php if($is_you) { ?>
		function showEditFields() {
				editUserInfo(false, 0);		
				$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/temp_disable_selection.css";?>" type="text/css" />');		
		}
	<?php }?>
	function getCharacterUserStatusUrl() {
		var character_status_url = "<?php echo site_url("characters/change_character_user_status");?>";
		return character_status_url;
	}
</script>
   
<div id="wrap">
	<?php include 'user_profile_top.php';?>
	<div class="container-fluid scrollable" id="characters_content">
		<div id="loves_menu">
			<div id="loves_submenu" class="btn-group">
				  <a href="<?php if($status == "LOVE") { echo site_url("characters/loves/{$user['username']}"); } else { echo site_url("characters/hates/{$user['username']}");}?>" class="disable-link-decoration btn btn-primary filter" id="characters_tab" style="background-color: #DEDEDE;">Characters</a>
				  <a href="<?php if($status == "LOVE") { echo site_url("actors/loves/{$user['username']}"); } else { echo site_url("actors/hates/{$user['username']}");}?>" class="disable-link-decoration btn btn-primary filter" id="actors_tab">Actors</a>
			</div>
		</div>
		<div class="main_title2"><?php echo $header;?>				
		</div>	
	    <div class="table-responsive">	    
		   <table class="table">
		    <thead>
		      <tr>
		        <th>NAME</th>
		        <th>APPEARS IN</th>
		        <th></th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php if(isset($characters)) { foreach ($characters as $character) { ?>			      
		      <?php $character_slug = "";					
				if($character['first_name'] != "") {
					$character_slug.=$character['first_name'];
				} 
				if($character['last_name'] != "") {
					if($character_slug != "")
						$character_slug.="-";
					$character_slug.=$character['last_name'];
				}
				
				$character_slug = preg_replace('/[^\00-\255]+/u', ' ', $character_slug);
				$character_slug = str_replace(" ", "-", $character_slug);
			   ?>
		      <tr class="user_row">
		        <td class="character_name_image">
			        <a href="<?php echo site_url("characters/character/{$character['id']}/{$character_slug}");?>" class="disable-link-decoration red-text">
			        	<img src="<?php echo asset_url() . "character_images/{$character['image_file_name']}";?>" class="character_image">				        	
			        </a>
			        
			        <div class="wrap_character_name_div">
				    	<a href="<?php echo site_url("characters/character/{$character['id']}/{$character_slug}");?>" class="disable-link-decoration red-text">
				    		<?php echo stripslashes($character['first_name']) . " " . stripslashes($character['last_name']);?>
				    	</a>
				    	<?php if($character['alt_name'] != "") {?>
				    		<p class="aliases"><strong>Aliases: </strong><span class="aliases_text"><?php echo stripslashes($character['alt_name']); ?></span></p>
				    	<?php }?>
				    </div>
		        </td>
		        <td class="character_appears_in">
		        	<?php foreach($character['animes'] as $anime) {?>
		        		<a href="<?php echo site_url("animeContent/anime/" . str_replace(" ", "-", $anime['slug']));?>" class="disable-link-decoration red-text related_animes"><?php echo convert_titles_to_hash($anime['titles'])['main'];?></a><br/>
		        	<?php }?>
		        </td>
		        <td class="character_user_status">
	    			<div class="wrap_user_status" data-id="<?php echo $character['id'];?>">
	    				<?php if(isset($character['character_user_status'])) { 
	    					if($character['character_user_status'] == 1){ $character_user_status = 1; }
	    					else if($character['character_user_status'] == 0) {$character_user_status = 0;} } else {$character_user_status = 2;}		    				
	    				?>
			    		<span title="I love this character" class="fa-stack fa-2x love <?php if($character_user_status == 1) echo "love_on";?>" data-value="1">
			    			<i class="fa fa-heart"></i>
			    		</span>
		    			<span title="I hate this character" class="fa-stack fa-2x hate <?php if($character_user_status == 0) echo "hate_on";?>" data-value="0">
						    <i class="fa fa-heart fa-stack-1x"></i>
						    <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
						</span>
					</div>
	    		</td>
		      </tr>
		      <?php }}?>
		    </tbody>
		  </table>
		  </div>
		  <div class="text-center">
		  	<?php if(isset($pagination)) {echo $pagination;} ?>
		  </div>
	  </div>
</div>

<?php include 'footer.php';?>