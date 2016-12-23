<?php include 'head.php';?>

<script src="<?php echo asset_url() . "js/browse_animes.js";?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo asset_url() . "tablesorter-master/css/theme.default.css";?>">
<script src="<?php echo asset_url() . "tablesorter-master/js/jquery.tablesorter.js";?>"></script>

<?php
	if(isset($this->session->userdata['is_logged_in'])) 
		$logged = TRUE;
	else 
		$logged = FALSE;
?>

<script type="text/javascript">
	$(document).ready(function() {
		addListeners();
		var star_empty_url_small = "<?php echo asset_url() . "imgs/star_empty_icon_small.png" ?>";
		var star_fill_url_small = "<?php echo asset_url() . "imgs/star_fill_icon_small.png" ?>";

		$('.rating-bg').css('background', 'url(' + star_empty_url_small + ') repeat-x top left');
		$('.rating').css('background', 'url(' + star_fill_url_small + ') repeat-x top left');
	});

	function getStatusUrl() {
		var url = "<?php echo site_url("watchlists/update_status");?>";
		return url;
	}

	function getScoreUrl() {
		var url = "<?php echo site_url("watchlists/update_score");?>";
		return url;
	}
</script>

<?php include 'navigation.php';?>

<div id="wrap">
	<div class="container-fluid scrollable content" style="padding: 0px;">
		<h1><?php echo $header;?></h1>
		<?php if(isset($animes_matched)) {  $counter = 0;?>
			<div id="search_navigation">
				<form action="<?php echo site_url("SearchC/search_anime")?>" method="get" accept-charset="utf-8">
					<select name="sort_selected" onchange="this.form.submit()" style="width: 150px; font-family: cursive;">
						<option selected disabled style="display: none" value=""></option>
						<option value="slug" <?php if($sort_by == 'slug') echo 'selected="selected"'; ?> class="navigation_small_search_option">Name</option>
					    <option value="start_date" <?php if($sort_by == 'start_date') echo 'selected="selected"'; ?> class="navigation_small_search_option">Newest</option>
					    <option value="average_rating" <?php if($sort_by == 'average_rating') echo 'selected="selected"'; ?> class="navigation_small_search_option">Highest Rated</option>
					</select>
					<input type="hidden" name="last_search" value="<?php if(isset($last_search)) echo $last_search;?>">
					<input type="hidden" name="sort_order" value="ASC">	
				</form>
				<br/><br/>
			</div>
					<?php foreach ($animes_matched as $anime) { ?>
			 		<?php if ($counter == 0) echo '<div class="row">';?>
			 		<?php if (($counter % 3 == 0) and ($counter != 0)) echo '</div> <br/> <div class="row">';?>
	 				 <?php
		 				 $temp = $anime['titles'];	 				 
		 				 $titles = convert_titles_to_hash($temp);	
		 				 echo '<div class="col-sm-4">';
		 				 echo "<p class='title_paragraph'><a href = '" . site_url("animeContent/anime/" . str_replace(" ", "-", $anime['slug'])) . "' class = 'anime_title'>"  . $titles['main'] . "</a></p>";
		 				 if($anime['episode_count'] > 0) {
		 				 	$episode_count = $anime['episode_count'];
		 				 } else {
		 				 	$episode_count = "?";
		 				 }
		 				 
		 				 if($anime['episode_count'] == 1) 
		 				 	$ep = "ep";
		 				  else 
		 				 	$ep = "eps";
		 				 
		 				 $show_type = get_show_type($anime['show_type']);
		 				 	
		 				 echo "<p class='second_paragraph'>" . $show_type . " | " . $episode_count . " " . $ep . "</p>";
		 				 echo "<div class='third_paragraph'>" . "<p class='measure_paragraph'>";
		 				 		if(isset($anime['genres'])) {
	 								foreach($anime['genres'] as $genre) {
										echo " <span title='{$genre}'>" . $genre . "</span> ";
	 								}
		 				 		}
						echo "</p></div>";
	 				 ?>				 
	 				 <?php $random_num = time();?>
	 				 <div class="anime_body">
	 				 	<a href="<?php echo site_url("animeContent/anime/" . str_replace(" ", "-", $anime['slug']));?>"><img class="anime_poster" src="<?php echo asset_url() . "poster_images/" . $anime['poster_image_file_name']. "?rand={$random_num}";?> " onerror="this.src='<?php echo asset_url()."imgs/None.jpg"?>'"></a> 			
	 				 	<div class="anime_synopsis_block">
	 				 		<p class="anime_synopsis"><?php echo preg_replace('#(\\\r|\\\r\\\n|\\\n)#', '<br/>', $anime['synopsis']);?></p>
	 				 	</div>
	 				 </div>
	 				 <div class="anime_footer">
	 				 	<?php $final_date = convert_date($anime['start_date']);?> 				 
	 				 	<p class="anime_date_paragraph"><i title="Air date" class="fa fa-calendar" aria-hidden="true"></i> <?php echo $final_date;?></p>
	 				 	<p class="anime_rating_paragraph"><i title="<?php echo number_format($anime['average_rating']/2, 2) . " out of " . "5 from " . $anime['total_votes'] . " votes";?>" class="fa fa-star-o" aria-hidden="true"></i> <?php echo number_format($anime['average_rating']/2, 2);?></p>
	 				 	<?php if($logged) {?>
	 				 		<a class="disable-link-decoration anime_user_status_paragraph" data-id="<?php echo $anime['id'];?>"><?php if(isset($anime['user_status'])) 
	 				 						echo "<span class='user_status' data-id=" . $anime['user_status'] .">" . get_watchlist_status_name($anime['user_status']) . "</span>&nbsp;" . 
	 				 							 "<img src='" . asset_url() . "imgs/search_star_icon2.png" ."' style='margin-top: -10px;'>" . 
	 				 								"<span class='user_score' data-id='" . $anime['user_score'] ."'>" . $anime['user_score']/2 . "</span>"; 
	 				 					    else echo  				 						
	 				 								"<span class='user_status' data-id='0'>Add</span>&nbsp;" . 
	 				 								"<img src='" . asset_url() . "imgs/search_star_icon2.png" ."' style='margin-top: -10px; display: none;'>" .
	 				 								"<span class='user_score' data-id='0'></span>";?>
	 				 		</a>
	 				 	<?php }?>
	 				 </div>
	 				 </div>		
	 				 <?php $counter++;?>
			<?php }?>
			<?php echo "</div> <br/>"?>
			<div class="text-center">
				<?php if(isset($animes_matched)) echo $pagination?>
			</div>
		  <?php } else if(isset($users_matched)) { ?>
		  <div class="table-responsive">
			   <table id="users_table" class="table tablesorter">
			    <thead>
			      <tr>
			        <th>USERNAME</th>
			        <th>JOIN DATE</th>
			        <th>ANIMES</th>
			      </tr>
			    </thead>
			    <tbody>
			      <?php foreach ($users_matched as $user) { ?>
			      <tr class="user_row">
			        <td>
				        <a href="<?php echo site_url("users/profile/{$user['username']}");?>" class="disable-link-decoration red-text">
				        	<div class="user_image_div"><img src="<?php echo asset_url() . "user_profile_images/{$user['profile_image']}";?>" class="user_image">
				        	</div>
				        	<?php echo $user['username'];?>
				        </a>
			        </td>
			        <td><?php echo $user['joined_on'];?></td>
			        <td><?php if($user['anime_count'] != 0){ 
			        	echo "<a href='" . site_url("watchlists/user_watchlist/{$user['username']}") . "' class='disable-link-decoration red-text'>" . $user['anime_count'] . "</a>";} else echo "0";?>
			        </td>
			      </tr>
			      <?php }?>
			    </tbody>
			  </table>
			  </div>
		  <?php } else if(isset($characters_matched)) { ?>
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
			      <?php foreach ($characters_matched as $character) { ?>			      
			      <?php $character_slug = "";					
					if($character['first_name'] != "") {
						$character_slug.=$character['first_name'] . "-";
					} 
					if($character['last_name'] != "") {
						$character_slug.=$character['last_name'];
					}
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
			        		<a href="<?php echo site_url("animeContent/anime/" . str_replace(" ", "-", $anime['slug']));?>" class="disable-link-decoration red-text"><?php echo convert_titles_to_hash($anime['titles'])['main'];?></a><br/>
			        	<?php }?>
			        </td>
			        <td class="character_user_status">
		    			<div class="wrap_user_status">
				    		<span title="I love this character" class="fa-stack fa-2x love">
				    			<i class="fa fa-heart"></i>
				    		</span>
			    			<span title="I hate this character" class="fa-stack fa-2x hate">
							    <i class="fa fa-heart fa-stack-1x"></i>
							    <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
							</span>
						</div>
		    		</td>
			      </tr>
			      <?php }?>
			    </tbody>
			  </table>
			  </div>
			  <div class="text-center">
			  	<?php if(isset($characters_matched)) echo $pagination?>
			  </div>
		  <?php } else if(isset($lists_matched)) { ?>
		  
		  <?php } else { ?>
		  		<h3>No results found</h3>
		 <?php }?>
	  
	</div>
</div>

	
<?php include 'footer.php';?>

<div id="change_status_modal" class="modal fade" role="dialog">
	<div class="wrap_modal_elements">
	<div class="modal-dialog">
        <div class="modal-content">
		    <div class="modal-header">
		        <a href="#" data-dismiss="modal" class="close">&times;</a>
		         <h3 id="anime_name"></h3>
		    </div>
		    <div class="modal-body">
		        <div class="wrap_modal_content">
			        <p class="body_title">My anime: &nbsp;&nbsp;</p>
			        <button id="watchlist_button" class="button-red">Add to list<span class="watchlist_caret fa fa-caret-down"></span></button>
				    <div id="watchlist_dropdown" class="w3-dropdown-content w3-border">
				      <a class="watchlist_item" data-id="1">Watched</a>
				      <a class="watchlist_item" data-id="2">Watching</a>
				      <a class="watchlist_item" data-id="3">Want to Watch</a>
				      <a class="watchlist_item" data-id="4">Stalled</a>
				      <a class="watchlist_item" data-id="5">Dropped</a>
				      <a class="watchlist_item" data-id="6" style="color: red;">Remove</a>				 
				    </div>
				    <div class="star-rating">

					    <input class="rb0" id="Ans_1" name="userScore" type="radio" value="0" checked="checked"/>                       
					    <input class="rb1" id="Ans_2" name="userScore" type="radio" value="1" />
					    <input class="rb2" id="Ans_3" name="userScore" type="radio" value="2" />
					    <input class="rb3" id="Ans_4" name="userScore" type="radio" value="3" />    
					    <input class="rb4" id="Ans_5" name="userScore" type="radio" value="4" />    
					    <input class="rb5" id="Ans_6" name="userScore" type="radio" value="5" />    
					    <input class="rb6" id="Ans_7" name="userScore" type="radio" value="6" />
					    <input class="rb7" id="Ans_8" name="userScore" type="radio" value="7" />    
					    <input class="rb8" id="Ans_9" name="userScore" type="radio" value="8" />
					    <input class="rb9" id="Ans_10" name="userScore" type="radio" value="9" />
					    <input class="rb10" id="Ans_11" name="userScore" type="radio" value="10" />
					    
					    <label for="Ans_1" class="star rb0l"></label>
					    <label for="Ans_2" class="star rb1l"></label>
					    <label for="Ans_3" class="star rb2l"></label>
					    <label for="Ans_4" class="star rb3l"></label>
					    <label for="Ans_5" class="star rb4l"></label>
					    <label for="Ans_6" class="star rb5l"></label>
					    <label for="Ans_7" class="star rb6l"></label>
					    <label for="Ans_8" class="star rb7l"></label>
					    <label for="Ans_9" class="star rb8l"></label>
					    <label for="Ans_10" class="star rb9l"></label>
					    <label for="Ans_11" class="star rb10l last"></label>
					    
					    <label for="Ans_1" class="rb">0</label>
					    <label for="Ans_2" class="rb">1</label>
					    <label for="Ans_3" class="rb">2</label>
					    <label for="Ans_4" class="rb">3</label>
					    <label for="Ans_5" class="rb">4</label>
					    <label for="Ans_6" class="rb">5</label>
					    <label for="Ans_7" class="rb">6</label>
					    <label for="Ans_8" class="rb">7</label>
					    <label for="Ans_9" class="rb">8</label>
					    <label for="Ans_10" class="rb">9</label>
					    <label for="Ans_11" class="rb">10</label>
					    
					    <div class="rating"></div>
					    <div class="rating-bg"></div> 
					</div> 
					<div id="loader_image_div">
						<img src="<?php echo asset_url() . "imgs/loading_icon_2.gif";?>" id="loader_image">
					</div>
				    <a data-dismiss="modal" class="close_modal btn secondary button-black">Done</a>
		        </div>
		    </div>
	    </div>
    </div>
    </div>
</div>