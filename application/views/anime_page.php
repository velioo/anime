<?php include 'head.php';?>

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
	$('head').append('<script src="<?php echo asset_url() . "js/edit_user_info.js";?>">');	
	$('head').append('<script src="<?php echo asset_url() . "js/anime_content.js";?>">');	
});

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
		<div class="col-sm-12" id="anime_info">		
			<?php $temp = $anime['titles'];	
				$titles = convert_titles_to_hash($temp);?>	
			<div id="anime_title_div">	
				<p id="anime_title"><?php echo $titles[$anime['canonical_title']];?></p>
			</div>
			<div>
				<div id="type_episodes_div">
					<?php $show_type = get_type($anime['show_type']);
						if($anime['episode_count'] > 1) 
							$ep = "eps";
						else if($anime['episode_count'] == 1)
							$ep = "ep";		
						else 
							$ep = "eps";
						
						if($anime['episode_count'] == 0)
							$ep_count = "?";
						else 
							$ep_count = $anime['episode_count'];
					?>
					<p id="type_episodes"><?php echo $show_type . " (" . $ep_count . " " . $ep . ")" . " * " . $anime['episode_length'] . " min";?></p>
				</div>		
				<?php $age_rating = get_age_rating($anime['age_rating'], $anime['age_rating_guide']);?>	
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
						
						$percentage = $anime['average_rating']/5 * 100 . "%";
					?> 				 
				</div>
				<div title="<?php echo $anime['average_rating'] . " out of " . "5"?>" class="star-ratings-sprite" id="rating_div">
					<span style="width:<?php echo $percentage?>" class="star-ratings-sprite-rating"></span>
				</div>
				<div id="ranked_div">
					<p id="ranked">Rank #</p>
				</div>
			</div>
			<img src="<?php echo asset_url() . "poster_images/" . $anime['poster_image_file_name'] ."?rand={$random_num};"?>" onerror="this.src='<?php echo asset_url()."imgs/None.jpg"?>'"  alt="Image" id="poster_image">
			<div id="anime_modal" class="modal">
			  <div id="center_div">
				  <span class="close" id="close_modal">×</span>
				  <img class="modal-content" id="modal_image">
			  </div>
			</div>
			<div class="more" id="synopsis_div">
				<?php echo preg_replace('#(\\\r|\\\r\\\n|\\\n)#', '<br/>', $anime['synopsis']);?>		
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
	</div>

</div>

<?php include 'footer.php';?>