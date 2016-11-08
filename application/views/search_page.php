<?php include 'head.php'; include 'navigation.php';?>

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
		 				 echo "<p class='title_paragraph'><a href = '#' class = 'anime_title'>"  . $titles[$anime['canonical_title']] . "</a></p>";
		 				 if($anime['episode_count'] > 0) {
		 				 	$episode_count = $anime['episode_count'];
		 				 } else {
		 				 	$episode_count = "?";
		 				 }
		 				 
		 				 if($anime['episode_count'] == 1) 
		 				 	$ep = "ep";
		 				  else 
		 				 	$ep = "eps";
		 				 
		 				 $show_type = "";
		 				 	
		 				 switch($anime['show_type']) {
		 				 	case 0:
		 				 		$show_type = "Unknown";
		 				 		break;
		 				 	case 1:
		 				 		$show_type = "TV";
		 				 		break;
		 				 	case 2:
		 				 		$show_type = "Special";
		 				 		break;
		 				 	case 3:
		 				 		$show_type = "OVA";
		 				 		break;
		 				 	case 4:
		 				 		$show_type = "ONA";
		 				 		break;
		 				 	case 5:
		 				 		$show_type = "Movie";
		 				 		break;
		 				 	case 6:
		 				 		$show_type = "Music";
		 				 		break;
		 				 }
		 				 	
		 				 echo "<p class='second_paragraph'>" . $show_type . " | " . $episode_count . " " . $ep . "</p>";
		 				 echo "<p class='third_paragraph'>";
		 				 		if(isset($anime['genres'])) {
	 								foreach($anime['genres'] as $genre) {
										echo " <span title='{$genre}'>" . $genre . "</span> ";
	 								}
		 				 		}
							echo "</p>";
	 				 ?>				 
	 				 <?php $random_num = time();?>
	 				 <div class="anime_body">
	 				 	<a href="#"><img class="anime_poster" src="<?php echo asset_url() . "poster_images/" . $anime['poster_image_file_name']. "?rand={$random_num}";?> " onerror="this.src='<?php echo asset_url()."imgs/None.jpg"?>'"></a> 			
	 				 	<div class="anime_synopsis_block">
	 				 		<p class="anime_synopsis"><?php echo preg_replace('#(\\\r|\\\r\\\n|\\\n)#', '<br/>', $anime['synopsis']);?></p>
	 				 	</div>
	 				 </div>
	 				 <div class="anime_footer">
	 				 	<?php 
	 				 		if($anime['start_date'] != "0000-00-00") {
	 				 			$split_by_slash_date = explode("-", $anime['start_date']);
	 				 			if($split_by_slash_date[1] == "00") {
									$month_short = "???";
	 				 			} else {
			 				 		$start_date = date("F", strtotime($anime['start_date']));
			 				 		$month_short = substr($start_date, 0, 3);
	 				 			}
	 				 			if($split_by_slash_date[2] == "00") {
	 				 				$day = "??";
	 				 			} else {
		 				 			$day = $split_by_slash_date[2];			 			
	 				 			}
	 				 			if($split_by_slash_date[0] == "0000") {
	 				 				$year = "????";
	 				 			} else {
	 				 				$year = $split_by_slash_date[0];
	 				 			}
		 				 		
		 				 		$final_date = $month_short . " " . $day . ", " . $year;
	 				 		} else {
	 				 			$final_date = "??? ??, ????";
	 				 		}
	 				 		
	 				 	?>
	 				 
	 				 	<p class="anime_date_paragraph"><i title="Air date" class="fa fa-calendar" aria-hidden="true"></i> <?php echo $final_date;?></p>
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
			        <td><a id="table_first_column" href="<?php echo site_url("login/profile/{$user['username']}");?>"><?php echo $user['username'];?></a></td>
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