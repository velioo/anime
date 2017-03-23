<?php include 'head.php';?>
<?php include 'navigation.php';?>

<script type="text/javascript">
	$('head').append('<script src="<?php echo asset_url() . "js/character_user_status.js";?>">');
	function getCharacterUserStatusUrl() {
		var character_status_url = "<?php echo site_url("characters/change_character_user_status");?>";
		return character_status_url;
	}
</script>
   
<div id="wrap">
	<div class="container-fluid scrollable" id="characters_content">
		<p class="main_title"><?php echo $header;?></p>	
	    <div class="table-responsive">	    
		   <table class="table">
		    <thead>
		      <tr>
		      	<th class="rank_title">RANK</th>
		        <th><span class="name_title">NAME</span></th>
		        <th>LIKES</th>
		        <th>APPEARS IN</th>
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
		      	<td class="rank"><?php echo $character['rank'];?></td>
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
		        <td class="likes"><?php echo $character['count'];?></td>
		        <td class="character_appears_in">
		        	<?php foreach($character['animes'] as $anime) {?>
		        		<div class="related_animes"><a href="<?php echo site_url("animeContent/anime/" . str_replace(" ", "-", $anime['slug']));?>" class="disable-link-decoration red-text"><?php echo convert_titles_to_hash($anime['titles'])['main'];?></a></div>
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