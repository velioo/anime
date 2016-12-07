<?php include 'head.php';?>

<script src="<?php echo asset_url() . "js/browse_animes.js";?>"></script>

<script type="text/javascript">
	$(document).ready(function() {
		addListeners();
	});
</script>

<?php include 'navigation.php';?>

<div id="wrap">
	<div class="container-fluid scrollable content">
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
			   <table class="table">
			    <thead>
			      <tr>
			        <th>Username</th>
			        <th>Join Date</th>
			      </tr>
			    </thead>
			    <tbody>
			      <?php foreach ($users_matched as $user) { ?>
			      <tr>
			        <td><a id="table_first_column" href="<?php echo site_url("users/profile/{$user['username']}");?>"><?php echo $user['username'];?></a></td>
			        <td><?php echo $user['joined_on'];?></td>
			      </tr>
			      <?php }?>
			    </tbody>
			  </table>
			  </div>
		  <?php } else if(isset($characters_matched)) { ?>
		  
		  <?php } else if(isset($lists_matched)) { ?>
		  
		  <?php } else { ?>
		  		<h3>No results found</h3>
		 <?php }?>
	  
	</div>
</div>

	
<?php include 'footer.php';?>