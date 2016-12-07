<?php include 'head.php';?>

<link rel="stylesheet" type="text/css" href="<?php echo asset_url() . "css/anime_navigation_bar.css";?>">

<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $review['username'])) {
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

<?php 
	if(isset($this->session->userdata['admin'])) {
		if($this->session->userdata['admin'] === TRUE and $logged === TRUE) {
			$is_admin = TRUE;
		}
	} else {
		$is_admin = FALSE;
	}
?>

<?php include 'navigation.php'; ?>

<div id="wrap">
	<?php $random_num = time();?>
	<div id="anime-bar" style="background-image:url('<?php if($anime['cover_image_file_name'] != ""){ echo asset_url() . "anime_cover_images/" . $anime['cover_image_file_name']; if($this->session->flashdata('new_cover')) echo "?rand={$random_num}"; } else echo asset_url() . "anime_cover_images/Default.jpg"?>'); ">
		<input type="hidden" name="top_offset" id="top_offset" value="<?php echo $anime['cover_image_top_offset'];?>">
	</div>
	<div class="container-fluid scrollable" id="review_div">
		<a id="back_link" href="<?php echo site_url("animeContent/anime/" . str_replace(" ", "-", $anime['slug']))?>"><span id="go_back" class="fa fa-arrow-left"> <span style="font-family: 'Open Sans', sans-serif;">Back to anime page</span></span></a>
		<div id="wrap_review_header">
			<div id="user_info_div">
				<a id="link_to_user" href="<?php echo site_url("users/profile/{$review['username']}")?>" >
					<div class="user_review_image_div">
						<img class="user_review_image" src="<?php echo asset_url() . "user_profile_images/{$review['profile_image']}"?>">
					</div>
					<span id="user_name" class="blue-text"><?php echo $review['username'];?></span>
				</a>
				<span id="review_date"><?php echo "On " . convert_date(date('Y-m-d', strtotime($review['created_at']))); ?></span>
			</div>
			<?php if($is_you) {?>
				<p id="last_updated"><a href="<?php echo site_url("reviews/add_edit_review/{$anime['slug']}");?>" class="disable-link-decoration"><span class="blue-text">Edit your Review</span></a></p>
			<?php }?>		
		</div>
		<div id="review_content">
			<?php $temp = $anime['titles'];	$titles = convert_titles_to_hash($temp);?>	
			<div id="review_title_div">	
				<p id="review_title"><?php echo $titles['main'];?> Review</p>
			</div>
			<?php echo stripslashes($review['review_text']);?>
		</div>	
		<div id="scores_div">
			<div class="score_div">
				Story<span class="score"><?php if($review['story'] != 0) echo $review['story'] . "/" . "10"; else echo "?/10";?></span>
			</div>
			<div class="score_div">
				Animation<span class="score"><?php if($review['animation'] != 0) echo $review['animation'] . "/" . "10"; else echo "?/10";?></span>
			</div>
			<div class="score_div">
				Sound<span class="score"><?php if($review['sound'] != 0) echo $review['sound'] . "/" . "10"; else echo "?/10";?></span>
			</div>
			<div class="score_div">
				Characters<span class="score"><?php if($review['characters'] != 0) echo $review['characters'] . "/" . "10"; else echo "?/10";?></span>
			</div>
			<div class="score_div">
				Enjoyment<span class="score"><?php if($review['enjoyment'] != 0) echo $review['enjoyment'] . "/" . "10"; else echo "?/10";?></span>
			</div>
			<div class="score_div">
				Overall<span class="score"><?php echo $review['overall'] . "/" . "10";?></span>
			</div>
		</div>	
	</div>
</div>

<?php include 'footer.php';?>