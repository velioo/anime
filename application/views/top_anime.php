<?php include 'head.php';?>
<?php include 'navigation.php'; ?>

<script type="text/javascript" src="<?php echo asset_url() . "js/top_anime.js";?>"></script>

<script>
	$(document).ready(function() {
		var star_empty_url_small = "<?php echo asset_url() . "imgs/star_empty_icon_small.png" ?>";
		var star_fill_url_small = "<?php echo asset_url() . "imgs/star_fill_icon_small.png" ?>";
	
		$('.rating-bg').css('background', 'url(' + star_empty_url_small + ') repeat-x top left');
		$('.rating').css('background', 'url(' + star_fill_url_small + ') repeat-x top left');
	});
	
	function getScoreUrl() {
		var score_url = "<?php echo site_url("watchlists/update_score");?>";
		return score_url;
	}
	
	function getStatusUrl() {
		var url = "<?php echo site_url("watchlists/update_status");?>";
		return url;
	}
</script>

<div id="wrap">
	<div class="container-fluid scrollable" id="animes_content">	
		<br>		
		<p class="main_title">Top Anime</p>	
		<div id="watchlist_content" class="col-sm-12">	
			<div id="wrap_table_div">			
				<div class="table-responsive">
					  <table class="table">
						    <thead>
						      <tr>
						        <th class="rank_title">RANK</th>
						        <th>TITLE</th>
						        <th>TYPE</th>
						        <th>YEAR</th>
						       <!--  <th>Progress</th> -->
						        <th>AVG</th>
						        <th>RATING</th>
						        <th>STATUS</th>
						      </tr>
						    </thead>			
							<tbody>
					    	<?php $counter = 0; foreach($animes as $anime) { $slug = str_replace(" ", "-", $anime['slug']);?>
					    		<tr>
					    			<td class="rank"><?php echo $anime['rank'];?></td>
					    			<td class="title"><a href="<?php echo site_url("animeContent/anime/{$slug}");?>" class="disable-link-decoration red-text"><?php echo convert_titles_to_hash($anime['titles'])['main'];?></a></td>
					    			<td class="type"><?php echo get_show_type($anime['show_type']);?></td>
					    			<td class="year"><?php $date = explode("-", $anime['start_date']); if($date[2] != "0000") echo $date[0]; else echo "????";?></td>
					    			<td class="avg"><?php echo number_format((float)$anime['average_rating']/2, 2, '.', '');?></td>		
					    			<td class="user_rating">	
					    				<?php if(isset($anime['status'])) {?>	    			
						    			<div class="star-rating" data-id="<?php echo $anime['id'];?>" <?php if($anime['status'] == NULL) echo " style='display:none;'";?>>			
										    <input class="rb0" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="0" <?php if($anime['score'] == 0) echo "checked";?>/>                       
										    <input class="rb1" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="1" <?php if($anime['score'] == 1) echo "checked";?>/>
										    <input class="rb2" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="2" <?php if($anime['score'] == 2) echo "checked";?>/>
										    <input class="rb3" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="3" <?php if($anime['score'] == 3) echo "checked";?>/>    
										    <input class="rb4" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="4" <?php if($anime['score'] == 4) echo "checked";?>/>    
										    <input class="rb5" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="5" <?php if($anime['score'] == 5) echo "checked";?>/>    
										    <input class="rb6" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="6" <?php if($anime['score'] == 6) echo "checked";?>/>
										    <input class="rb7" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="7" <?php if($anime['score'] == 7) echo "checked";?>/>    
										    <input class="rb8" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="8" <?php if($anime['score'] == 8) echo "checked";?>/>
										    <input class="rb9" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="9" <?php if($anime['score'] == 9) echo "checked";?>/>
										    <input class="rb10" id="Ans_<?php echo $counter++;?>" name="userScore<?php echo $anime['id'];?>" type="radio" value="10" <?php if($anime['score'] == 10) echo "checked";?>/>
										    <?php $counter-=11;?>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb0l" onclick=""></label>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb1l" onclick=""></label>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb2l" onclick=""></label>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb3l" onclick=""></label>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb4l" onclick=""></label>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb5l" onclick=""></label>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb6l" onclick=""></label>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb7l" onclick=""></label>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb8l" onclick=""></label>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb9l" onclick=""></label>
										    <label for="Ans_<?php echo $counter++;?>" class="star rb10l last" onclick=""></label>
										    <?php $counter-=11;?>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">0</label>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">1</label>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">2</label>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">3</label>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">4</label>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">5</label>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">6</label>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">7</label>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">8</label>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">9</label>
										    <label for="Ans_<?php echo $counter++;?>" class="rb" onclick="">10</label>
										    
										    <div class="rating"></div>
										    <div class="rating-bg"></div> 
										</div> 		
										<?php }?>    			
					    			</td>		
					    			
					    			<td class="status"> 
					    				<?php if(isset($anime['status'])) {?>
										<button class="watchlist_button button-white"><?php if($anime['status'] != NULL) { echo "<span class='status-square " . get_status_square($anime['status']). "'></span>" . get_watchlist_status_name($anime['status']);} else echo "Add to Watchlist"; ?><span class="watchlist_caret fa fa-caret-down"></span></button>
									    <div class="w3-dropdown-content w3-border watchlist_dropdown" data-id="<?php echo $anime['id'];?>">
									      <a class="watchlist_item" data-id="1">Watched</a>
									      <a class="watchlist_item" data-id="2">Watching</a>
									      <a class="watchlist_item" data-id="3">Want to Watch</a>
									      <a class="watchlist_item" data-id="4">Stalled</a>
									      <a class="watchlist_item" data-id="5">Dropped</a>
									      <a class="watchlist_item" data-id="6" style="color: red;">Remove</a>
									    </div>
										<div class="loader_image_div">
											<img src="<?php echo asset_url() . "imgs/loading_icon_2.gif";?>" class="loader_image">
										</div>	
										<?php }?>
									</td>	    					    						    			
					    		</tr>		
					    	<?php }?>			    	
					       </tbody>
					  </table>
				  </div>		
			</div>		
		</div>
		<div class="text-center">
			<?php if(isset($pagination)) echo $pagination;?>
		</div>
	</div>
</div>


<?php include 'footer.php';?>