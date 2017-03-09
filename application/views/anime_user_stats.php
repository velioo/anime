<?php include 'head.php';?>
<?php include 'navigation.php'; ?>

<link rel="stylesheet" type="text/css" href="<?php echo asset_url() . "css/anime_navigation_bar.css";?>">
<link rel="stylesheet" type="text/css" href="<?php echo asset_url() . "css/anime_user_stats.css";?>">
<script src="<?php //echo asset_url() . "js/characters.js";?>"></script>

<script type="text/javascript">
$(document).ready(function() {
	var star_empty_url_small = "<?php echo asset_url() . "imgs/star_empty_icon_small.png" ?>";
	var star_fill_url_small = "<?php echo asset_url() . "imgs/star_fill_icon_small.png" ?>";
	
	$('.star-ratings-sprite').css('background', 'url(' + star_empty_url_small + ') repeat-x');
	$('.star-ratings-sprite-rating').css('background', 'url(' + star_fill_url_small + ') repeat-x');

	var status_id = <?php echo $status_id;?>;
	if(status_id == 1) {
		$('#watched_tab').css("background-color", "#6F99E4");
	} else if(status_id == 2) {	
		$('#watching_tab').css("background-color", "#8DEA43");
	}  else if(status_id == 3) {
		$('#want_watch_tab').css("background-color", "#FCFC3C");
	}  else if(status_id == 4) {
		$('#stalled_tab').css("background-color", "#FC9F3C");
	}  else if(status_id == 5) {
		$('#dropped_tab').css("background-color", "#D93D48");
	}
});
</script>

<?php $slug = str_replace(" ", "-", $anime['slug']);?>

<div id="wrap">
	<div id="anime-bar" style="background-image:url('<?php if($anime['cover_image_file_name'] != "") echo asset_url() . "anime_cover_images/" . $anime['cover_image_file_name']; else echo asset_url() . "anime_cover_images/Default.jpg";?>');">
		<input type="hidden" name="top_offset" id="top_offset" value="<?php echo $anime['cover_image_top_offset'];?>">
	</div>
	<div class="container-fluid scrollable" id="user_stats_content">	

		<?php $temp = $anime['titles'];	$titles = convert_titles_to_hash($temp);?>	
		<div id="anime_title_div">	
			<p id="anime_title"><?php echo "<a href='" . site_url("animeContent/anime/{$slug}") ."' class='disable-link-decoration'" . "<span class='blue-text'>" . $titles['main'] . "</span></a>";?>&nbsp;<span class="fa fa-chevron-right" style="font-size: 20px; color:#4F5155; font-weight: 900;"></span>&nbsp;<span style="color:#4F5155;">User Stats</span></p>
		</div>
	
		<div class="col-sm-12" id="anime_navigation_bar">
			<div class="menu_title_div" id="menu_home_div">
				<a href="<?php echo site_url("animeContent/anime/{$slug}");?>" class="menu_title" id="home_menu_title">HOME</a>
			</div>
			<div class="menu_title_div" id="menu_reviews_div">
				<a href="<?php echo site_url("animeContent/reviews/{$slug}");?>" class="menu_title" id="reviews_menu_title">REVIEWS</a>
			</div>
			<div class="menu_title_div" id="menu_recommendations_div">
				<a href="#" class="menu_title" id="recommendations_menu_title">RECOMMENDATIONS</a>
			</div>
			<div class="menu_title_div" id="menu_characters_div">
				<a href="<?php echo site_url("animeContent/characters/{$slug}");?>" class="menu_title" id="characters_menu_title">CHARACTERS</a>
			</div>
			<div class="menu_title_div" id="menu_user_stats_div">
				<a href="<?php echo site_url("animeContent/user_stats/{$slug}")?>" class="menu_title" id="user_stats_menu_title">USER STATS</a>
			</div>
			<div class="menu_title_div" id="menu_gallery_div">
				<a href="#" class="menu_title" id="gallery_menu_title">GALLERY</a>
			</div>
			<div class="menu_title_div" id="menu_episodes_div">
				<a href="#" class="menu_title" id="episodes_menu_title">EPISODES</a>
			</div>
		</div>
		
		<div id="filters_content" class="col-sm-12">
			<div id="user_stats_filter">
				<div id="filter_group" class="btn-group" role="group">
				   <a href="<?php echo site_url("animeContent/user_stats/{$slug}/watched")?>" id="watched_tab" class="disable-link-decoration btn btn-primary filter">
				  	Watched
				  </a>
				   <a href="<?php echo site_url("animeContent/user_stats/{$slug}/watching")?>" id="watching_tab" class="disable-link-decoration btn btn-primary filter">
				 	 Watching
				  </a>
				   <a href="<?php echo site_url("animeContent/user_stats/{$slug}/want_to_watch")?>" id="want_watch_tab" class="disable-link-decoration btn btn-primary filter">
				 	 Want to Watch
				  </a>
				   <a href="<?php echo site_url("animeContent/user_stats/{$slug}/stalled")?>" id="stalled_tab" class="disable-link-decoration btn btn-primary filter">
				  	Stalled
				  	</a>
				  <a href="<?php echo site_url("animeContent/user_stats/{$slug}/dropped")?>" id="dropped_tab" class="disable-link-decoration btn btn-primary filter">
				  	Dropped
				  </a>
				</div>
			</div>
		</div>
		
		 <form id="users_form" action="<?php echo site_url("animeContent/user_stats/{$slug}/{$status}")?>" method="get" accept-charset="utf-8">
			  <select id="users_select" name="sort_selected" onchange="this.form.submit()" style="width: 150px; font-family: cursive;">
				  <option value="name_asc" <?php if($sort_by == 'name_asc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Name ASC</option>
			      <option value="name_desc" <?php if($sort_by == 'name_desc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Name DESC</option>
			      <option value="eps_asc" <?php if($sort_by == 'eps_asc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Episodes ASC</option>
			      <option value="eps_desc" <?php if($sort_by == 'eps_desc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Episodes DESC</option>
			      <option value="rating_asc" <?php if($sort_by == 'rating_asc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Rating ASC</option>
			      <option value="rating_desc" <?php if($sort_by == 'rating_desc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Rating DESC</option>
			      <option value="date_asc" <?php if($sort_by == 'date_asc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Date ASC</option>
			      <option value="date_desc" <?php if($sort_by == 'date_desc') echo 'selected="selected"'; ?> class="navigation_small_search_option">Date DESC</option>
			  </select>	  
			  <input type="hidden" id="page" name="page" value="<?php if(isset($page)) echo $page; ?>">
		  </form>
		
		<div>
			<table id="users_table" class="table">
		    <thead>
		      <tr>
		        <th>USERNAME</th>
		        <th>STATUS</th>
		        <th>EPS</th>
		        <th>RATING</th>
		        <th>DATE</th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php foreach ($users as $user) { ?>
		      <tr class="user_row">
		        <td>
			        <a href="<?php echo site_url("users/profile/{$user['username']}");?>" class="disable-link-decoration red-text">
			        	<div class="user_image_div"><img src="<?php if($user['profile_image'] != "") echo asset_url() . "user_profile_images/{$user['profile_image']}"; else echo asset_url() . "imgs/Default_Avatar.jpg";?>" class="user_image">
			        	</div>
			        	<?php echo $user['username'];?>
			        </a>
		        </td>
		        <td><span class="status-square <?php echo $status_square?>"></span><?php echo get_watchlist_status_name($user['status']);?></td>
		        <td><?php echo $user['eps_watched'];?></td>
		        <?php $score_percentage = (($user['score'] * 10) . "%"); ?>
		        <td title="<?php echo $user['score']/2 . ' out of 5'; ?>" class="anime_rating">
					<div class="star-ratings-sprite" style="display: inline-block;">
						<span style="width:<?php echo $score_percentage ?>" class="star-ratings-sprite-rating"></span>
					</div>
				</td>
				<td><?php $timestamp = strtotime($user['status_updated_at']); echo date('d-m-Y', $timestamp);?></td>
		      </tr>
		      <?php }?>
		    </tbody>
		    </table>
		</div>
		<div class="text-center">
			<?php if(isset($users)) { echo $pagination; }?>
		</div>
	
	</div>
</div>

<?php include 'footer.php';?>