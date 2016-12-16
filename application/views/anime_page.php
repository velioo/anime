<?php include 'head.php';?>

<link rel="stylesheet" type="text/css" href="<?php echo asset_url() . "css/anime_navigation_bar.css";?>">

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

<script type="text/javascript">
$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/anime_navigation_bar.css";?>" type="text/css" />');
$(document).ready(function() {
	$('head').append('<script src="<?php echo asset_url() . "js/edit_user_info.js";?>">');	
	$('head').append('<script src="<?php echo asset_url() . "js/anime_content.js";?>">');		

	var star_empty_url = "<?php echo asset_url() . "imgs/star_empty_icon.png" ?>";
	var star_fill_url = "<?php echo asset_url() . "imgs/star_fill_icon.png" ?>";
	var star_empty_url_small = "<?php echo asset_url() . "imgs/star_empty_icon_small.png" ?>";
	var star_fill_url_small = "<?php echo asset_url() . "imgs/star_fill_icon_small.png" ?>";

	$('.star-ratings-sprite').css('background', 'url(' + star_empty_url_small + ') repeat-x');
	$('.star-ratings-sprite-rating').css('background', 'url(' + star_fill_url_small + ') repeat-x');	
	$('.rating-bg').css('background', 'url(' + star_empty_url + ') repeat-x top left');
	$('.rating').css('background', 'url(' + star_fill_url + ') repeat-x top left');

	var anime_in_watchlist = <?php if(isset($watchlist_status_name)) echo 1; else echo 0;?>;

	if(anime_in_watchlist) {
	    $('.watchlist_item').show();
		$('.star-rating').css("display", "inline-block");
		var score = <?php if(isset($score)) echo $score; else echo 0;?>;
		$('input[name=userScore][value=' + score + ']').prop('checked',true);
	}
});

	function getStatusUrl() {
		var url = "<?php echo site_url("watchlists/update_status");?>";
		return url;
	}

	function getScoreUrl() {
		var url = "<?php echo site_url("watchlists/update_score");?>";
		return url;
	}

	function getAnimeId() {
		var id = <?php echo $anime['id'];?>;
		return id;
	}	

<?php if($is_admin) { ?>
function showEditFields() {
		editAnimeInfo(false, 0);		
		$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/temp_disable_selection.css";?>" type="text/css" />');	
	}
<?php }?>
</script>

<?php include 'navigation.php'; ?>

<div id="wrap">
	<?php include 'anime_page_top.php';?>
	<div class="container-fluid scrollable" id="anime_content">	
	
	<?php 		
		$anime['slug'] = str_replace(" ", "-", $anime['slug']);
	?>
	
	<?php $temp = $anime['titles'];	$titles = convert_titles_to_hash($temp);?>	
		<div id="anime_title_div">	
			<p id="anime_title"><?php echo $titles['main'];?></p>
		</div>
	
	<div class="col-sm-12" id="anime_navigation_bar">
		<div class="menu_title_div" id="menu_home_div">
			<a href="<?php echo site_url("animeContent/anime/" . $anime['slug']);?>" class="menu_title" id="home_menu_title">HOME</a>
		</div>
		<div class="menu_title_div" id="menu_reviews_div">
			<a href="<?php echo site_url("animeContent/reviews/" . $anime['slug']);?>" class="menu_title" id="reviews_menu_title">REVIEWS</a>
		</div>
		<div class="menu_title_div" id="menu_recommendations_div">
			<a href="#" class="menu_title" id="recommendations_menu_title">RECOMMENDATIONS</a>
		</div>
		<div class="menu_title_div" id="menu_characters_div">
			<a href="#" class="menu_title" id="characters_menu_title">CHARACTERS</a>
		</div>
		<div class="menu_title_div" id="menu_user_stats_div">
			<a href="#" class="menu_title" id="user_stats_menu_title">USER STATS</a>
		</div>
		<div class="menu_title_div" id="menu_gallery_div">
			<a href="#" class="menu_title" id="gallery_menu_title">GALLERY</a>
		</div>
		<div class="menu_title_div" id="menu_episodes_div">
			<a href="#" class="menu_title" id="episodes_menu_title">EPISODES</a>
		</div>
	</div>
		<div class="col-sm-12" id="anime_info">		
			<div id="wrap_anime_info_bar">
				<div id="type_episodes_div">
					<?php $show_type = get_show_type($anime['show_type']);
						if($anime['episode_count'] > 1) 
							$ep = "eps";
						else if($anime['episode_count'] == 1)
							$ep = "ep";		
						else 
							$ep = "eps";
						
						if($anime['episode_count'] == 0) {
							if($anime['show_type'] == 1)
								$ep_count = "?";
							else if($anime['show_type'] == 5)
								$ep_count = "1";
							else 
								$ep_count = $anime['episode_count'];
						} else 
							$ep_count = $anime['episode_count'];
					?>
					<p id="type_episodes"><?php echo $show_type . " (" . $ep_count . " " . $ep . ")" . " * " . $anime['episode_length'] . " min";?></p>
				</div>		
				<?php $age_rating = get_age_rating($anime['age_rating']);?>	
				<div title="<?php echo $anime['age_rating_guide'];?>" id="age_rating_div">
					<p id="age_rating"><?php echo $age_rating;?></p>
				</div>	
				<div id="air_end_date_div">
					<?php $start_date = convert_date($anime['start_date'], "anime_content");
					  $end_date = convert_date($anime['end_date'], "anime_content");
						if($start_date != $end_date) { 
							echo '<p class="date"><i title="Air date" class="fa fa-calendar"  aria-hidden="true"></i> ' . $start_date . ' - </p>' . 
								 '<p class="date"><i title="End date" aria-hidden="true"></i> ' . $end_date . '</p>';
						} else {
							echo  '<p class="date"><i title="Air date" class="fa fa-calendar" aria-hidden="true"></i> ' . $start_date . '</p>';
						}
						
						$percentage = $anime['average_rating']/5 * 50 . "%";
					?> 				 
				</div>
				<div title="<?php echo number_format($anime['average_rating']/2, 3) . " out of " . "5 from " . $anime['total_votes'] . " votes";?>" class="star-ratings-sprite" id="rating_div">
					<span style="width:<?php echo $percentage?>" class="star-ratings-sprite-rating"></span>
				</div>
				<div id="ranked_div">
					<p id="ranked">Rank #</p>
				</div>
			</div>
			<div id="wrap_poster_span_div">
				<img src="<?php echo asset_url() . "poster_images/" . $anime['poster_image_file_name']; if($this->session->flashdata('new_poster')) echo "?rand={$random_num}";?>" onerror="this.src='<?php echo asset_url()."imgs/None.jpg"?>'"  alt="Image" id="poster_image">
				<span id="edit_poster_span" class="fa fa-camera"></span>
			</div>
			<div id="anime_modal" class="modal">
			  <div id="center_div">
				  <span class="close" id="close_modal">×</span>
				  <img class="modal-content" id="modal_image">
			  </div>
			</div>
			<div id="wrap_synopsis_genres">
				<div class="more" id="synopsis_div">
					<?php echo preg_replace('#(\\\r|\\\r\\\n|\\\n)#', '<br/>', $anime['synopsis']);?>		
				</div>
				<div id="anime_genres_div">
					<?php if(isset($anime['genres'])) { 
							 echo "<strong style='color: #23272D;'>Genres: </strong>";
							 foreach($anime['genres'] as $genre) {
							 	echo "<span style='text-decoration: underline;'>" . $genre ."</span> &nbsp";
					 	     }
						  }
					 ?>
				</div>
				<?php if($logged) {?>
				<div id="anime_user_watchlist_div">
					<div id="wrap_watchlist" class="w3-dropdown-click">
				    <button id="watchlist_button" class="button-red"><?php if(isset($watchlist_status_name)) echo $watchlist_status_name; else echo "Add to Watchlist";?><span id="watchlist_caret" class="fa fa-caret-down"></span></button>
					    <div id="watchlist_dropdown" class="w3-dropdown-content w3-border">
					      <a class="watchlist_item" data-id="1">Watched</a>
					      <a class="watchlist_item" data-id="2">Watching</a>
					      <a class="watchlist_item" data-id="3">Want to Watch</a>
					      <a class="watchlist_item" data-id="4">Stalled</a>
					      <a class="watchlist_item" data-id="5">Dropped</a>
					      <a class="watchlist_item" data-id="6" style="display: none;">Remove from List</a>
					    </div>
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
					    
					    <label for="Ans_1" class="star rb0l" onclick=""></label>
					    <label for="Ans_2" class="star rb1l" onclick=""></label>
					    <label for="Ans_3" class="star rb2l" onclick=""></label>
					    <label for="Ans_4" class="star rb3l" onclick=""></label>
					    <label for="Ans_5" class="star rb4l" onclick=""></label>
					    <label for="Ans_6" class="star rb5l" onclick=""></label>
					    <label for="Ans_7" class="star rb6l" onclick=""></label>
					    <label for="Ans_8" class="star rb7l" onclick=""></label>
					    <label for="Ans_9" class="star rb8l" onclick=""></label>
					    <label for="Ans_10" class="star rb9l" onclick=""></label>
					    <label for="Ans_11" class="star rb10l last" onclick=""></label>
					    
					    <label for="Ans_1" class="rb" onclick="">0</label>
					    <label for="Ans_2" class="rb" onclick="">1</label>
					    <label for="Ans_3" class="rb" onclick="">2</label>
					    <label for="Ans_4" class="rb" onclick="">3</label>
					    <label for="Ans_5" class="rb" onclick="">4</label>
					    <label for="Ans_6" class="rb" onclick="">5</label>
					    <label for="Ans_7" class="rb" onclick="">6</label>
					    <label for="Ans_8" class="rb" onclick="">7</label>
					    <label for="Ans_9" class="rb" onclick="">8</label>
					    <label for="Ans_10" class="rb" onclick="">9</label>
					    <label for="Ans_11" class="rb" onclick="">10</label>
					    
					    <div class="rating"></div>
					    <div class="rating-bg"></div> 
					</div> 
					<div id="loader_image_div">
						<img src="<?php echo asset_url() . "imgs/loading_icon_2.gif";?>" class="loader_image">
					</div>
				</div>
				<?php }?>
			</div>
			<?php if($anime['youtube_video_id'] != "") {?>
			<div id="youtube_trailer_div">
				<div id="show_video" style="background-image: url('https://i3.ytimg.com/vi/<?php echo $anime['youtube_video_id']?>/hqdefault.jpg');">
					<span class="fa fa-youtube-play"></span>
				</div>
			</div>
			<div id="youtube_modal" class="modal">	
				<div id="center_video_div">		
					<button id="close_youtube_video_button" type="button" class="close" aria-hidden="true">×</button>
            		<iframe id="anime_video" width="1200" height="700" src="//www.youtube.com/embed/<?php echo $anime['youtube_video_id']?>?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>
            	</div>
			</div>
			<?php }?>
		</div>
		<p class="anime_content_title">Reviews</p>
		<div class="col-sm-9" id="reviews">	
		<a href="<?php echo site_url("reviews/add_edit_review/" . $anime['slug']);?>" class="add_edit_review disable-link-decoration"><?php if($logged && $has_written_review) echo "<span class='blue-text'> Edit your Review</span>"; else echo "<span class='red-text'> Write a Review</span>";?></a>
			<?php if(isset($reviews)) foreach($reviews as $review) {?>
			<div class="wrap_review">
			    <div class="review_header_div">
			      <div class="user_review_image_div">
			      	<a href="<?php echo site_url("users/profile/{$review['username']}");?>"><img class="user_review_image" src="<?php echo asset_url() . "user_profile_images/{$review['profile_image']}"?>"></a>
			      </div>
			      <a href="<?php echo site_url("users/profile/{$review['username']}")?>" class="user_name"><?php echo $review['username']?></a>
			      <div class="review_header_right_part">
				      <p class="review_right_p">
				      <?php if($review['updated_at'] != NULL) echo convert_date(date('Y-m-d', strtotime($review['updated_at']))); else echo convert_date(date('Y-m-d', strtotime($review['created_at'])));?>
				      </p>
				      <p class="review_right_p" style="padding-top: 10px;">Score <span style="color:#5E5E5E; font-size: 18px;"><?php echo $review['overall'] . "/10";?></span></p>
			      </div>
			    </div>
			    <div class="review_body">
			     	<?php $text = strip_review_tags($review['review_text']);						
					 	if(mb_strlen($text) > 500) {
							echo substr($text, 0, 500) . "..." . "<a href='" . site_url("reviews/review/" . $anime['slug'] . "/" . $review['username']) . "' class='read_more'> Show whole review</a>";
						} else {
							echo $text . "<a href='" . site_url("reviews/review/" . $anime['slug'] . "/" . $review['username']) . "' class='disable-link-decoration'> <span class = 'red-text'> Show whole review</span></a>";
						}
			     	?>		     
			    </div>
		   </div>
		   <hr>
		   <?php } else { ?>
		   		<p style="margin-top:20px;">No Reviews yet</p> 
		   		<div id="wrap_write_first_review">
		   			<a id="write_first_review" href="<?php echo site_url("reviews/add_edit_review/" . $anime['slug']);?>"><button id="write_first_review_button" class="btn btn-primary button-red">Be the first to write one</button> </a>
		   		</div>
		   <?php }?>
		   <?php if(isset($reviews)) {?>
			   <div id="wrap_see_all_reviews">
			  		<a id="link_see_all_reviews" href="<?php echo site_url("animeContent/reviews/" . $anime['slug']);?>"><button id="see_all_reviews_button" class="btn btn-primary button-blue">See All</button></a>
			   </div>
		   <?php } ?>
		</div>
	</div>
</div>


<?php include 'footer.php';?>









