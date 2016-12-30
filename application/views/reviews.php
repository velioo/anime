<?php include 'head.php';?>

<link rel="stylesheet" type="text/css" href="<?php echo asset_url() . "css/anime_navigation_bar.css";?>">
<link rel="stylesheet" type="text/css" href="<?php echo asset_url() . "css/bubbles.css";?>">
<script src="<?php echo asset_url() . "js/reviews.js";?>"></script>

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
$(document).ready(function() {	
	var total_groups = <?php echo $total_groups;?>;
	var site_url = "<?php echo site_url("reviews/load_reviews/");?>";
	var anime_id = <?php echo $anime['id'];?>;

	initScroll(total_groups, site_url, anime_id);
});

<?php if($is_admin) { ?>
<?php }?>
</script>

<?php include 'navigation.php'; ?>
<?php $slug = str_replace(" ", "-", $anime['slug']);?>

<div id="wrap">
	<div id="anime-bar" style="background-image:url('<?php if($anime['cover_image_file_name'] != "") echo asset_url() . "anime_cover_images/" . $anime['cover_image_file_name']; else echo asset_url() . "anime_cover_images/Default.jpg";?>'); ">
		<input type="hidden" name="top_offset" id="top_offset" value="<?php echo $anime['cover_image_top_offset'];?>">
	</div>
	<div class="container-fluid scrollable" id="reviews_content">	
		<?php $temp = $anime['titles'];	$titles = convert_titles_to_hash($temp);?>	
		<div id="anime_title_div">	
			<p id="anime_title"><?php echo "<a href='" . site_url("animeContent/anime/{$slug}") ."' class='disable-link-decoration'" . "<span class='blue-text'>" . $titles['main'] . "</span></a>";?>&nbsp;<span class="fa fa-chevron-right" style="font-size: 20px; color:#4F5155; font-weight: 900;"></span>&nbsp;<span style="color:#4F5155;">Reviews</span></p>
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
				<a href="#" class="menu_title" id="user_stats_menu_title">USER STATS</a>
			</div>
			<div class="menu_title_div" id="menu_gallery_div">
				<a href="#" class="menu_title" id="gallery_menu_title">GALLERY</a>
			</div>
			<div class="menu_title_div" id="menu_episodes_div">
				<a href="#" class="menu_title" id="episodes_menu_title">EPISODES</a>
			</div>
		</div>
		
		<div class="col-sm-12" id="new_review_div">
			<a href="<?php if($logged) { echo site_url("reviews/add_edit_review/{$slug}");} else echo "#";?>" style="height: 50px;"><button class="btn btn-primary button-blue log_in_modal" id="reviews_user_button"><?php echo $button_name; ?></button></a>
		</div>
		
		<div class="col-sm-12" id="reviews_div">

		</div>
		<div id="loader_image_div">
			<img src="<?php echo asset_url() . "imgs/loading_records.gif";?>" class="loader_image">
		</div>
	</div>
</div>

<?php include 'footer.php';?>

<?php if(!$logged) { include 'login_modal.php'; }?>