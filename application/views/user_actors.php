<?php include 'head.php';?>
   
<link rel="stylesheet" href="<?php echo asset_url() . "css/user_navigation_bar.css";?>" type="text/css" />
<script src="<?php echo asset_url() . "js/edit_user_info.js";?>"></script>
<script src="<?php echo asset_url() . "js/follow.js";?>"></script>
   
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
	$('head').append('<script src="<?php echo asset_url() . "js/actor_user_status.js";?>">');
	<?php if($is_you) { ?>
		function showEditFields() {
				editUserInfo(false, 0);		
				$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/temp_disable_selection.css";?>" type="text/css" />');		
		}
	<?php }?>
	function getActorUserStatusUrl() {
		var actor_status_url = "<?php echo site_url("actors/change_actor_user_status");?>";
		return actor_status_url;
	}
</script>

<?php include 'navigation.php';?>
   
<div id="wrap">
	<?php include 'user_profile_top.php';?>
	<div class="container-fluid scrollable" id="actors_content">
		<div id="loves_menu">
			<div id="loves_submenu" class="btn-group">
				  <a href="<?php if($status == "LOVE") { echo site_url("characters/loves/{$user['username']}"); } else { echo site_url("characters/hates/{$user['username']}");}?>" class="disable-link-decoration btn btn-primary filter" id="characters_tab">Characters</a>
				  <a href="<?php if($status == "LOVE") { echo site_url("actors/loves/{$user['username']}"); } else { echo site_url("actors/hates/{$user['username']}");}?>" class="disable-link-decoration btn btn-primary filter" id="actors_tab" style="background-color: #DEDEDE;">Actors</a>
			</div>
		</div>
		<div class="main_title"><?php echo $header;?>				
		</div>	
	    <div class="table-responsive">	    
		   <table class="table">
		    <thead>
		      <tr>
		        <th>NAME</th>
		        <th></th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php if(isset($actors)) { foreach ($actors as $actor) { ?>			      
		      <tr class="user_row">
		        <td class="actor_name_image">
			        <a href="<?php echo site_url("actors/actor/{$actor['id']}/{$actor['actor_slug']}");?>" class="disable-link-decoration red-text">
			        	<img src="<?php echo asset_url() . "actor_images/{$actor['image_file_name']}";?>" class="actor_image">				        	
			        </a>			        
			        <div class="wrap_actor_name_div">
				    	<a href="<?php echo site_url("actors/actor/{$actor['id']}/{$actor['actor_slug']}");?>" class="disable-link-decoration red-text">
				    		<?php echo stripslashes($actor['first_name']) . " " . stripslashes($actor['last_name']);?>
				    	</a>
				    	<p class="language">
				    		<span class="language_text">
				    		<img src="<?php echo asset_url() . "imgs/{$actor['language']}.png";?>" class="actor_flag_image"><?php echo " " . stripslashes($actor['language']);?>
				    		</span>
					    </p>
				    </div>
		        </td>
		        <td class="actor_user_status">
	    			<div class="wrap_user_status" data-id="<?php echo $actor['id'];?>">
	    				<?php if(isset($actor['actor_user_status'])) { 
	    					if($actor['actor_user_status'] == 1){ $actor_user_status = 1; }
	    					else if($actor['actor_user_status'] == 0) {$actor_user_status = 0;} } else {$actor_user_status = 2;}		    				
	    				?>
			    		<span title="I love this actor" class="fa-stack fa-2x love <?php if($actor_user_status == 1) echo "love_on";?>" data-value="1">
			    			<i class="fa fa-heart"></i>
			    		</span>
		    			<span title="I hate this actor" class="fa-stack fa-2x hate <?php if($actor_user_status == 0) echo "hate_on";?>" data-value="0">
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