<?php include 'head.php';?>
<?php include 'navigation.php';?>

<script src="<?php echo asset_url() . "js/anime_recommendations.js";?>"></script>

<script type="text/javascript">
$(document).ready(function() {	
	addListeners();
	var star_empty_url_small = "<?php echo asset_url() . "imgs/star_empty_icon_small.png";?>";
	var star_fill_url_small = "<?php echo asset_url() . "imgs/star_fill_icon_small.png";?>";
	
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

<div id="wrap">
	<div class="container-fluid scrollable" id="animes_content">
		<h1 class="main_title">Anime Recommendations</h1>	
	
		<?php $counter = 0; if(isset($animes)){ if(count($animes) > 0) { foreach ($animes as $anime) { ?>
	 		<?php if ($counter == 0) echo '<div class="row">';?>
	 		<?php if (($counter % 3 == 0) and ($counter != 0)) echo '</div> <br/> <div class="row">';?>
 				 <?php
	 				 $temp = $anime['titles'];	 				 
	 				 $titles = convert_titles_to_hash($temp);	
	 				 echo '<div class="col-sm-4 anime_column">';
	 				 echo "<p class='title_paragraph'><a href = '" . site_url("animeContent/anime/" . $anime['slug']) . "' class = 'anime_title disable-link-decoration blue-text'>"  . $titles['main'] . "</a></p>";
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
	 				 			$genres = explode(",", $anime['genres']);
	 				 			sort($genres);
 								foreach($genres as $genre) {
									echo " <span title='{$genre}'>" . $genre . "</span> ";
 								}
	 				 		}
					echo "</p></div>";
					
					if(isset($anime['user_status'])) {
						$status = get_status_square($anime['user_status']);
					}
					
 				 ?>				 
 				 <?php $random_num = time();?>
 				 <div class="anime_body">
 				 	<a href="<?php echo site_url("animeContent/anime/" . $anime['slug']);?>"><img class="anime_poster" src="<?php echo asset_url() . "poster_images/" . $anime['poster_image_file_name']. "?rand={$random_num}";?> " onerror="this.src='<?php echo asset_url()."imgs/None.jpg"?>'"></a> 			
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
 				 						echo "<span class='user_status' data-id=" . $anime['user_status'] ."><span class='status-square " . $status . "'></span>" . get_watchlist_status_name($anime['user_status']) . "</span>&nbsp;" . 
 				 							 "<img src='" . asset_url() . "imgs/search_star_icon2.png" ."' style='margin-top: -10px;'>" . 
 				 								"<span class='user_score' data-id='" . $anime['user_score'] ."'>" . $anime['user_score']/2 . "</span>"; 
 				 					    else echo  				 						
 				 								"<span class='user_status' data-id='0'>Add</span>&nbsp;" . 
 				 								"<img src='" . asset_url() . "imgs/search_star_icon2.png" ."' style='margin-top: -10px; display: none;'>" .
 				 								"<span class='user_score' data-id='0'></span>";?>
 				 		</a>
 				 	<?php } else { ?>
 						<span class="not_logged_add_paragraph log_in_modal">Add</span> 				 	
 				 	<?php }?>

 				 </div>
	 	</div>		
 		<?php $counter++;?>
		<?php } echo "</div> <br/>"; ?>
			<div class="text-center recommend_new_button_div">
				<a href="<?php echo site_url("recommendations/anime_recommendations");?>" class="disable-link-decoration button-black recommend_new_button">Recommend New</a>
			</div>
		<?php } else { ?>
			<div class="not_logged">You should add more animes to your <a href="<?php echo site_url("watchlists/user_watchlist/{$this->session->userdata('username')}");?>" class="disable-link-decoration blue-text">Watchlist</a> for us to recommend you similar</div>
		<?php } } else { ?>	
			<div class="not_logged"><span class="disable-link-decoration blue-text log_in_modal">Log in</span> to receive personal recommendations</div>
		<?php }?>
		<?php //echo "</div> <br/>";?>
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