<?php include 'head.php';?>

<link rel="stylesheet" type="text/css" href="<?php echo asset_url() . "css/anime_navigation_bar.css";?>">
<link rel="stylesheet" type="text/css" href="<?php echo asset_url() . "css/bubbles.css";?>">

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
	$('head').append('<script src="<?php echo asset_url() . "js/reviews.js";?>">');	

	var total_groups = <?php echo $total_groups;?>;
	var base_url = "<?php echo base_url();?>";
	var anime_id = <?php echo $anime['id'];?>;
	
	initScroll(total_groups, base_url, anime_id);
		
});

<?php if($is_admin) { ?>
<?php }?>
</script>

<?php include 'navigation.php'; ?>

<div id="wrap">
	<?php $random_num = time();?>
	<div id="anime-bar" style="background-image:url('<?php if($anime['cover_image_file_name'] != ""){ echo asset_url() . "anime_cover_images/" . $anime['cover_image_file_name']; if($this->session->flashdata('new_cover')) echo "?rand={$random_num}"; } else echo asset_url() . "anime_cover_images/Default.jpg"?>'); ">
		<input type="hidden" name="top_offset" id="top_offset" value="<?php echo $anime['cover_image_top_offset'];?>">
	</div>
	<div class="container-fluid scrollable" id="reviews_content">	
		<?php $temp = $anime['titles'];	$titles = convert_titles_to_hash($temp);?>	
		<div id="anime_title_div">	
			<p id="anime_title"><?php echo $titles['main'];?>&nbsp;<span class="fa fa-chevron-right"></span>&nbsp;Reviews</p>
		</div>
	
		<div class="col-sm-12" id="anime_navigation_bar">
			<div class="menu_title_div" id="menu_home_div">
				<a href="<?php echo site_url("animeContent/anime/" .  str_replace(" ", "-", $anime['slug']));?>" class="menu_title" id="home_menu_title">HOME</a>
			</div>
			<div class="menu_title_div" id="menu_reviews_div">
				<a href="<?php echo site_url("animeContent/reviews/" . str_replace(" ", "-", $anime['slug']));?>" class="menu_title" id="reviews_menu_title">REVIEWS</a>
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
		
		<div class="col-sm-12" id="new_review_div">
			<a href="<?php echo site_url("reviews/add_edit_review/" . str_replace(" ", "-", $anime['slug']));?>" style="height: 50px;"><button class="btn btn-primary button-blue" id="reviews_user_button"><?php echo $button_name; ?></button></a>
		</div>
		
		<div class="col-sm-12" id="reviews_div">

		</div>
		<div id="loader_image_div">
			<img src="<?php echo asset_url() . "imgs/loading_records.gif";?>" class="loader_image">
		</div>
	</div>
</div>

<?php include 'footer.php';?>