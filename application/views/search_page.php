<?php include 'head.php';?>

<link rel="stylesheet" type="text/css" href="<?php echo asset_url() . "tablesorter-master/css/theme.default.css";?>">
<script src="<?php echo asset_url() . "tablesorter-master/js/jquery.tablesorter.js";?>"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/ui-lightness/jquery-ui.css">
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>


<?php
	if(isset($this->session->userdata['is_logged_in'])) 
		$logged = TRUE;
	else 
		$logged = FALSE;
?>

<script type="text/javascript">
	$(document).ready(function() {
		var genres_checkbox = "<?php echo asset_url() . "imgs/genres_checkbox.png";?>";
		$('label.css-label').css('background-image', 'url(' + genres_checkbox + ')');
		<?php if(isset($animes_matched)) {?>
			$('head').append('<script src="<?php echo asset_url() . "js/browse_animes.js";?>">');	
			addListeners();
			var star_empty_url_small = "<?php echo asset_url() . "imgs/star_empty_icon_small.png";?>";
			var star_fill_url_small = "<?php echo asset_url() . "imgs/star_fill_icon_small.png";?>";
	
			$('.rating-bg').css('background', 'url(' + star_empty_url_small + ') repeat-x top left');
			$('.rating').css('background', 'url(' + star_fill_url_small + ') repeat-x top left');

			<?php foreach($filters['genres'] as $genre) {?>
			var genre = "<?php echo $genre ?>";
			$('.genre_div .css-label').each(function() {
				if($(this).text() == genre) {
					$(this).prev().prop('checked', true);
				}
			});
			<?php }?>	
			putGenresFilter();
			var type = <?php if($filters['type'] !== NULL) echo $filters['type']; else echo -1;?>;
			if(type != -1) {
				$("input[name=type][value=" + type + "]").attr('checked', 'checked');
				putTypeFilter();	
			}
			var min_eps = <?php if(isset($filters['episodes']['min'])) echo $filters['episodes']['min']; else echo -1;?>;
			var max_eps = <?php if(isset($filters['episodes']['max'])) echo $filters['episodes']['max']; else echo -1;?>;
			if(min_eps != -1) {
				$('#min_episodes').val(min_eps);
			}	
			if(max_eps != -1) {
				$('#max_episodes').val(max_eps);
			}			
			putEpisodesFilter();

			var min_year = <?php if(isset($filters['year']['min'])) echo $filters['year']['min']; else echo -1;?>;
			var max_year = <?php if(isset($filters['year']['max'])) echo $filters['year']['max']; else echo -1;?>;
			if(min_year != -1) {
				$('#min_year').val(min_year);
			}	
			if(max_year != -1) {
				$('#max_year').val(max_year);
			}			
			putYearsFilter();
		<?php } else if(isset($characters_matched)) {?>
			$('head').append('<script src="<?php echo asset_url() . "js/character_user_status.js";?>">');	
		<?php } else if(isset($users_matched)) {?>
			//$("#users_table").tablesorter();
		<?php } else if(isset($actors_matched)) {?>
			$('head').append('<script src="<?php echo asset_url() . "js/actor_user_status.js";?>">');	
		<?php }?>	     
	});

	function getStatusUrl() {
		var url = "<?php echo site_url("watchlists/update_status");?>";
		return url;
	}

	function getScoreUrl() {
		var url = "<?php echo site_url("watchlists/update_score");?>";
		return url;
	}

	function getCharacterUserStatusUrl() {
		var character_status_url = "<?php echo site_url("characters/change_character_user_status");?>";
		return character_status_url;
	}

	function getActorUserStatusUrl() {
		var actor_status_url = "<?php echo site_url("actors/change_actor_user_status");?>";
		return actor_status_url;
	}

	function getAvgFilterValues() {
		var avg_values = [<?php if(isset($filters['ratings']['greater'])) echo $filters['ratings']['greater']; else echo 0;?>, 
						  <?php if(isset($filters['ratings']['less'])) echo $filters['ratings']['less']; else echo 5; ?>];
		return avg_values;
	}
</script>

<?php include 'navigation.php';?>

<div id="wrap">
	<div class="container-fluid scrollable content">
		<h1 class="main_header"><?php echo $header;?></h1>
		<?php if(isset($animes_matched)) {  $counter = 0;?>			
			<ul id="anime_filters" class="nav nav-tabs">
			  <li class="active"><a data-toggle="tab" href="#filter_name">Name</a></li>
			  <li><a data-toggle="tab" href="#filter_rating">Avg Rating</a></li>
			  <li><a data-toggle="tab" href="#filter_genres">Genres</a></li>
			  <li><a data-toggle="tab" href="#filter_type">Type</a></li>
			  <li><a data-toggle="tab" href="#filter_year">Year</a></li>
			  <li><a data-toggle="tab" href="#filter_episodes">Episodes</a></li>
			  <li><a data-toggle="tab" href="#filter_mylist">My List</a></li>
			</ul>				
			<div id="search_navigation">
				<form id="animes_search_form" action="<?php echo site_url("SearchC/search_anime")?>" method="get" accept-charset="utf-8">
					<div id="wrap_filters_div">
						<div class="tab-content">
					  		<div id="filter_name" class="tab-pane active filter_tab">
					  			<input type="text" id="name_search" name="last_search" placeholder="Search animes..." value="<?php if(isset($last_search)) echo $last_search; ?>">
						    </div>
						  	<div id="filter_rating" class="tab-pane filter_tab" style="text-align: center;">
						  		<div class="avg_ranges_div">
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">0</span></div>
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">0.5</span></div>
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">1</span></div>
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">1.5</span></div>
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">2</span></div>
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">2.5</span></div>
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">3</span></div>
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">3.5</span></div>
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">4</span></div>
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">4.5</span></div>
						  			<div class="avg_range"><img src="<?php echo asset_url() . "imgs/search_star_icon2.png";?>" class="range_star"><span class="range_text">5</span></div>
						  		</div>
						  		<div id="slider-range"></div>
						  		<input type="hidden" id="avg_amount1" name="avg_amount1">
   								<input type="hidden" id="avg_amount2" name="avg_amount2">
						  	</div>
						  	<div id="filter_genres" class="tab-pane filter_tab">
						  		<div class="genre_div"><input id="action_genre" type="checkbox" name="genre[]" value="action" class="css-checkbox"> <label for="action_genre" class="css-label">Action</label></div>
						  		<div class="genre_div"><input id="adventure_genre" type="checkbox" name="genre[]" value="adventure" class="css-checkbox"> <label for="adventure_genre" class="css-label">Adventure</label></div>
						  		<div class="genre_div"><input id="anime_influenced_genre" type="checkbox" name="genre[]" value="anime_influenced" class="css-checkbox"> <label for="anime_influenced_genre" class="css-label">Anime Influenced</label></div>
						  		<div class="genre_div"><input id="comedy_genre" type="checkbox" name="genre[]" value="comedy" class="css-checkbox"> <label for="comedy_genre" class="css-label">Comedy</label></div>
						  		<div class="genre_div"><input id="dementia_genre" type="checkbox" name="genre[]" value="dementia" class="css-checkbox"> <label for="dementia_genre" class="css-label">Dementia</label></div>
						  		<div class="genre_div"><input id="demons_genre" type="checkbox" name="genre[]" value="demons" class="css-checkbox"> <label for="demons_genre" class="css-label">Demons</label></div>
						  		<div class="genre_div"><input id="doujinshi_genre" type="checkbox" name="genre[]" value="doujinshi" class="css-checkbox"> <label for="doujinshi_genre" class="css-label">Doujinshi</label></div>
						  		<div class="genre_div"><input id="drama_genre" type="checkbox" name="genre[]" value="drama" class="css-checkbox"> <label for="drama_genre" class="css-label">Drama</label></div>
						  		<div class="genre_div"><input id="ecchi_genre" type="checkbox" name="genre[]" value="ecchi" class="css-checkbox"> <label for="ecchi_genre" class="css-label">Ecchi</label></div>
						  		<div class="genre_div"><input id="fantasy_genre" type="checkbox" name="genre[]" value="fantasy" class="css-checkbox"> <label for="fantasy_genre" class="css-label">Fantasy</label></div>
						  		<div class="genre_div"><input id="game_genre" type="checkbox" name="genre[]" value="game" class="css-checkbox"> <label for="game_genre" class="css-label">Game</label></div>
						  		<div class="genre_div"><input id="gender_bender_genre" type="checkbox" name="genre[]" value="gender_bender" class="css-checkbox"> <label for="gender_bender_genre" class="css-label">Gender Bender</label></div>
						  		<div class="genre_div"><input id="gore_genre" type="checkbox" name="genre[]" value="gore" class="css-checkbox"> <label for="gore_genre" class="css-label">Gore</label></div>
						  		<div class="genre_div"><input id="harem_genre" type="checkbox" name="genre[]" value="harem" class="css-checkbox"> <label for="harem_genre" class="css-label">Harem</label></div>
						  		<div class="genre_div"><input id="historical_genre" type="checkbox" name="genre[]" value="historical" class="css-checkbox"> <label for="historical_genre" class="css-label">Historical</label></div>
						  		<div class="genre_div"><input id="horror_genre" type="checkbox" name="genre[]" value="horror" class="css-checkbox"> <label for="horror_genre" class="css-label">Horror</label></div>
						  		<div class="genre_div"><input id="kids_genre" type="checkbox" name="genre[]" value="kids" class="css-checkbox"> <label for="kids_genre" class="css-label">Kids</label></div>
						  		<div class="genre_div"><input id="magic_genre" type="checkbox" name="genre[]" value="magic" class="css-checkbox"> <label for="magic_genre" class="css-label">Magic</label></div>
						  		<div class="genre_div"><input id="mahou_shoujo_genre" type="checkbox" name="genre[]" value="mahou_shoujo" class="css-checkbox"> <label for="mahou_shoujo_genre" class="css-label">Mahou Shoujo</label></div>
						  		<div class="genre_div"><input id="mahou_shounen_genre" type="checkbox" name="genre[]" value="mahou_shounen" class="css-checkbox"> <label for="mahou_shounen_genre" class="css-label">Mahou Shounen</label></div>
						  		<div class="genre_div"><input id="martial_arts_genre" type="checkbox" name="genre[]" value="martial_arts" class="css-checkbox"> <label for="martial_arts_genre" class="css-label">Martial Arts</label></div>
						  		<div class="genre_div"><input id="mecha_genre" type="checkbox" name="genre[]" value="mecha" class="css-checkbox"> <label for="mecha_genre" class="css-label">Mecha</label></div>
						  		<div class="genre_div"><input id="military_genre" type="checkbox" name="genre[]" value="military" class="css-checkbox"> <label for="military_genre" class="css-label">Military</label></div>
						  		<div class="genre_div"><input id="music_genre" type="checkbox" name="genre[]" value="music" class="css-checkbox"> <label for="music_genre" class="css-label">Music</label></div>
						  		<div class="genre_div"><input id="mystery_genre" type="checkbox" name="genre[]" value="mystery" class="css-checkbox"> <label for="mystery_genre" class="css-label">Mystery</label></div>
						  		<div class="genre_div"><input id="parody_genre" type="checkbox" name="genre[]" value="parody" class="css-checkbox"> <label for="parody_genre" class="css-label">Parody</label></div>
						  		<div class="genre_div"><input id="police_genre" type="checkbox" name="genre[]" value="police" class="css-checkbox"> <label for="police_genre" class="css-label">Police</label></div>
						  		<div class="genre_div"><input id="psychological_genre" type="checkbox" name="genre[]" value="psychological" class="css-checkbox"> <label for="psychological_genre" class="css-label">Psychological</label></div>
						  		<div class="genre_div"><input id="racing_genre" type="checkbox" name="genre[]" value="racing" class="css-checkbox"> <label for="racing_genre" class="css-label">Racing</label></div>
						  		<div class="genre_div"><input id="romance_genre" type="checkbox" name="genre[]" value="romance" class="css-checkbox"> <label for="romance_genre" class="css-label">Romance</label></div>
						  		<div class="genre_div"><input id="samurai_genre" type="checkbox" name="genre[]" value="samurai" class="css-checkbox"> <label for="samurai_genre" class="css-label">Samurai</label></div>
						  		<div class="genre_div"><input id="school_genre" type="checkbox" name="genre[]" value="school" class="css-checkbox"> <label for="school_genre" class="css-label">School</label></div>
						  		<div class="genre_div"><input id="sci-fi_genre" type="checkbox" name="genre[]" value="sci-Fi" class="css-checkbox"> <label for="sci-fi_genre" class="css-label">Sci-Fi</label></div>
						  		<div class="genre_div"><input id="shoujo_ai_genre" type="checkbox" name="genre[]" value="shoujo_ai" class="css-checkbox"> <label for="shoujo_ai_genre" class="css-label">Shoujo Ai</label></div>
						  		<div class="genre_div"><input id="shounen_ai_genre" type="checkbox" name="genre[]" value="shounen_ai" class="css-checkbox"> <label for="shounen_ai_genre" class="css-label">Shounen Ai</label></div>
						  		<div class="genre_div"><input id="slice_of_life_genre" type="checkbox" name="genre[]" value="slice_of_life" class="css-checkbox"> <label for="slice_of_life_genre" class="css-label">Slice of Life</label></div>
						  		<div class="genre_div"><input id="space_genre" type="checkbox" name="genre[]" value="space" class="css-checkbox"> <label for="space_genre" class="css-label">Space</label></div>
						  		<div class="genre_div"><input id="sports_genre" type="checkbox" name="genre[]" value="sports" class="css-checkbox"> <label for="sports_genre" class="css-label">Sports</label></div>
						  		<div class="genre_div"><input id="super_power_genre" type="checkbox" name="genre[]" value="super_power" class="css-checkbox"> <label for="super_power_genre" class="css-label">Super Power</label></div>
						  		<div class="genre_div"><input id="supernatural_genre" type="checkbox" name="genre[]" value="supernatural" class="css-checkbox"> <label for="supernatural_genre" class="css-label">Supernatural</label></div>
						  		<div class="genre_div"><input id="thriller_genre" type="checkbox" name="genre[]" value="thriller" class="css-checkbox"> <label for="thriller_genre" class="css-label">Thriller</label></div>
						  		<div class="genre_div"><input id="vampire_genre" type="checkbox" name="genre[]" value="vampire" class="css-checkbox"> <label for="vampire_genre" class="css-label">Vampire</label></div>
						  		<div class="genre_div"><input id="yaoi_genre" type="checkbox" name="genre[]" value="yaoi" class="css-checkbox"> <label for="yaoi_genre" class="css-label">Yaoi</label></div>
						  		<div class="genre_div"><input id="yuri_genre" type="checkbox" name="genre[]" value="yuri" class="css-checkbox"> <label for="yuri_genre" class="css-label">Yuri</label></div>
						  	</div>
						  	<div id="filter_type" class="tab-pane filter_tab">
						  		<ul class="type_div">
									<li>
									    <input type="radio" id="type1" name="type" value="5" />
									    <label for="type1">Movie</label>
									</li>
									<li>
									    <input type="radio" id="type2" name="type" value="6" />
									    <label for="type2">Music</label>
									</li>
									<li>
									    <input type="radio" id="type3" name="type" value="4" />
									    <label title="Original Net Animation(Web)" for="type3">ONA</label>
									</li>
									<li>
									    <input type="radio" id="type4" name="type"  value="0" />
									    <label for="type4">Other</label>
									</li>
									<li>
									    <input type="radio" id="type5" name="type" value="3" />
									    <label title="Original Video Animation" for="type5">OVA</label>
									</li>
									<li>
									    <input type="radio" id="type6" name="type" value="2" />
									    <label for="type6">Special</label>
									</li>
									<li>
									    <input type="radio" id="type7" name="type" value="1" />
									    <label for="type7">TV</label>
									</li>
								</ul>
						  	</div>
						  	<div id="filter_episodes" class="tab-pane filter_tab">
						  		<input type="number" id="min_episodes" name="min_episodes" min="1" max="99999" onkeypress="return isNumberKey(event)" /> 
						  		<span class="to_text">to</span> 
						  		<input type="number" id="max_episodes" name="max_episodes" min="1" max="99999" onkeypress="return isNumberKey(event)" />
						  	</div>
						  	<div id="filter_year" class="tab-pane filter_tab">
						  		<input type="number" id="min_year" name="min_year" min="1907" max="<?php echo date("Y") + 3;?>" onkeypress="return isNumberKey(event)" /> 
						  		<span class="to_text">to</span> 
						  		<input type="number" id="max_year" name="max_year" min="1907" max="<?php echo date("Y") + 3;?>" onkeypress="return isNumberKey(event)" />
						  	</div>
						  	<div id="filter_mylist" class="tab-pane filter_tab">
						  		mylist
						  	</div>
						</div>			
						<div id="current_filters_div">
							<button id="apply_filters_button" type="submit" class="button-black">Apply filters</button>
							<div id="current_filters"><span class="clear_filters red-text">clear filters</span></div>
						</div>
					</div>
					<select name="sort_selected" onchange="this.form.submit()" style="width: 150px; font-family: cursive;">
						<option selected disabled style="display: none" value=""></option>
						<option value="slug" <?php if($sort_by == 'slug') echo 'selected="selected"'; ?> class="navigation_small_search_option">Name</option>
					    <option value="start_date" <?php if($sort_by == 'start_date') echo 'selected="selected"'; ?> class="navigation_small_search_option">Newest</option>
					    <option value="average_rating" <?php if($sort_by == 'average_rating') echo 'selected="selected"'; ?> class="navigation_small_search_option">Highest Rated</option>
					</select>
					<input type="hidden" name="sort_order" value="ASC">	
				</form>
				<br/><br/>
			</div>
					<?php if($animes_matched !== FALSE) foreach ($animes_matched as $anime) { ?>
			 		<?php if ($counter == 0) echo '<div class="row">';?>
			 		<?php if (($counter % 3 == 0) and ($counter != 0)) echo '</div> <br/> <div class="row">';?>
	 				 <?php
		 				 $temp = $anime['titles'];	 				 
		 				 $titles = convert_titles_to_hash($temp);	
		 				 echo '<div class="col-sm-4">';
		 				 echo "<p class='title_paragraph'><a href = '" . site_url("animeContent/anime/" . str_replace(" ", "-", $anime['slug'])) . "' class = 'anime_title disable-link-decoration blue-text'>"  . $titles['main'] . "</a></p>";
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
							switch($anime['user_status']) {
								case 1:
									$status = "blue";
									break;
								case 2:
									$status = "green";
									break;
								case 3:
									$status = "yellow";
									break;								
								case 4:
									$status = "orange";
									break;								
								case 5:
									$status = "red";
									break;
								default:	
									break;
							}
						}
						
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
			<?php } else echo "<h3>No results found</h3>";?>
			<?php echo "</div> <br/>"?>
			<div class="text-center">
				<?php if(isset($animes_matched)) echo $pagination?>
			</div>
		  <?php } else if(isset($users_matched)) { ?>	
		  <form id="users_search_form" action="<?php echo site_url("SearchC/search_users")?>" method="get" accept-charset="utf-8">
			  <select name="sort_selected" onchange="this.form.submit()" style="width: 150px; font-family: cursive;">
				  <option value="name_asc" <?php if($sort_by == 'name_asc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Name ASC</option>
			      <option value="name_desc" <?php if($sort_by == 'name_desc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Name DESC</option>
			      <option value="join_asc" <?php if($sort_by == 'join_asc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Join date ASC</option>
			      <option value="join_desc" <?php if($sort_by == 'join_desc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Join date DESC</option>
			      <option value="animes_asc" <?php if($sort_by == 'animes_asc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Animes ASC</option>
			      <option value="animes_desc" <?php if($sort_by == 'animes_desc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Animes DESC</option>
			  </select>	  
			  <input type="hidden" id="last_search" name="last_search" value="<?php if(isset($last_search)) echo $last_search; ?>">
		  </form>
		  <br/>
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
				        	<div class="user_image_div"><img src="<?php if($user['profile_image'] != "") echo asset_url() . "user_profile_images/{$user['profile_image']}"; else echo asset_url() . "imgs/Default_Avatar.jpg";?>" class="user_image">
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
			 <div class="text-center">
				<?php if(isset($users_matched)) echo $pagination?>
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
			        		<a href="<?php echo site_url("animeContent/anime/" . str_replace(" ", "-", $anime['slug']));?>" class="disable-link-decoration red-text"><?php echo convert_titles_to_hash($anime['titles'])['main'];?></a><br/>
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
			      <?php }?>
			    </tbody>
			  </table>
			  </div>
			  <div class="text-center">
			  	<?php if(isset($characters_matched)) echo $pagination?>
			  </div>
		  <?php } else if(isset($actors_matched)) { ?>
		  		<div class="table-responsive">
			   <table class="table">
			    <thead>
			    	<tr>
				    	<th>NAME</th>
				        <th></th>
			        </tr>
			    </thead>
			    <tbody>
			      <?php foreach ($actors_matched as $actor) { 
			      	$actor_slug = "";
			      	if($actor['first_name'] != "") {
			      		$actor_slug.=$actor['first_name'];
			      	}
			      	if($actor['last_name'] != "") {
			      		if($actor_slug != "")
			      			$actor_slug.="-";
			      		$actor_slug.=$actor['last_name'];
			      	}
			      		
			      	$actor['actor_slug'] = $actor_slug;			      	
			      ?>			      
			      <tr class="user_row">
			        <td class="actor_name_image">
				        <a href="<?php echo site_url("actors/actor/{$actor['id']}/{$actor['actor_slug']}");?>" class="disable-link-decoration red-text">
				        	<img src="<?php echo asset_url() . "actor_images/{$actor['image_file_name']}";?>" class="actor_image">				        	
				        </a>				        
				        <div class="wrap_actor_name_div">
					    	<a href="<?php echo site_url("actors/actor/{$actor['id']}/{$actor['actor_slug']}");?>" class="disable-link-decoration red-text">
					    		<?php echo trim(stripslashes($actor['first_name'])) . " " . trim(stripslashes($actor['last_name']));?>
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
			      <?php }?>
			    </tbody>
			  </table>
			  </div>
			  <div class="text-center">
			  	<?php if(isset($actors_matched)) echo $pagination?>
			  </div>
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

<?php if(!$logged) { include 'login_modal.php'; }?>