<?php include 'head.php';?>

<?php
	if(isset($this->session->userdata['is_logged_in'])) 
		$logged = TRUE;
	else 
		$logged = FALSE;
?>

<?php 
	if(isset($this->session->userdata['admin'])) {
		$is_admin = TRUE;
	} else {
		$is_admin = FALSE;
	}
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('head').append('<script src="<?php echo asset_url() . "js/edit_user_info.js";?>">');	
		var image_width = $('#poster_image').css("width");
		if(image_width > 0)
	 		$('#type_episodes_div').css("width", image_width);

		var modalImg = document.getElementById("modal_image");
		$("#poster_image").click(function(){
			$('#anime_modal').css("display", "block");
			var src = $(this).attr("src");
		    $("#modal_image").attr("src", src);
		});

		$('#close_modal').click(function(){
			$("#center_div").css("-webkit-animation-name", "zoom_out");
			$("#center_div").css("animation-name", "zoom_out");
			$("#center_div").css("-webkit-animation-duration", "0.3s");
			$("#center_div").css("animation-duration", "0.3s");
			setTimeout(function(){
				 $('#anime_modal').css("display", "none"); 
				 $("#center_div").css("-webkit-animation-name", "zoom");
				 $("#center_div").css("animation-name", "zoom");
				 $("#center_div").css("-webkit-animation-duration", "0.6s");
				 $("#center_div").css("animation-duration", "0.6s");
			}, 250);
		});

		var video_url = $("#anime_video").attr('src');
		$("#anime_video").attr('src', '');

	    $('#close_youtube_video_button').click(function() {
			$("#center_video_div").css("-webkit-animation-name", "zoom_out");
			$("#center_video_div").css("animation-name", "zoom_out");
			$("#center_video_div").css("-webkit-animation-duration", "0.3s");
			$("#center_video_div").css("animation-duration", "0.3s");
			setTimeout(function(){
		    	 $('#youtube_modal').css("display", "none");
		    	 $("#anime_video").attr('src', '');
				 $("#center_video_div").css("-webkit-animation-name", "zoom");
				 $("#center_video_div").css("animation-name", "zoom");
				 $("#center_video_div").css("-webkit-animation-duration", "0.6s");
				 $("#center_video_div").css("animation-duration", "0.6s");
			}, 250);
		    
	    });

	    $('#show_video').click(function() {
			 $('#youtube_modal').css("display", "block");
			 $("#anime_video").attr('src', video_url);
	    });
		
	});
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
			<div id="synopsis_div">
				<p id="synopsis"><?php echo preg_replace('#(\\\r|\\\r\\\n|\\\n)#', '<br/>', $anime['synopsis']);?></p>
			</div>
			<?php if($anime['youtube_video_id'] != "") {?>
			<div id="youtube_trailer_div">
				<a id="show_video" class="btn btn-lg btn-primary">Trailer</a>
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