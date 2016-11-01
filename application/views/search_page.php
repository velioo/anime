<?php include 'head.php'; include 'navigation.php';?>
<div id="wrap">
	<div class="container-fluid scrollable content">
		<h1><?php echo $header;?></h1>
		<?php if(isset($animes_matched)) {  $counter = 0;?>
			<div id="search_navigation">
				<form action="<?php echo site_url("SearchC/search_anime")?>" method="get" accept-charset="utf-8">			
					<select name="sort_selected" onchange="this.form.submit()" style="width: 150px; font-family: cursive;">
						<option value="slug" <?php if($sort_by == 'slug') echo 'selected="selected"'; ?> class="navigation_small_search_option">Name</option>
					    <option value="start_date" <?php if($sort_by == 'start_date') echo 'selected="selected"'; ?> class="navigation_small_search_option">Newest</option>
					    <option value="average_rating" <?php if($sort_by == 'average_rating') echo 'selected="selected"'; ?> class="navigation_small_search_option">Highest Rated</option>
					</select>
					<input type="hidden" name="last_search" value="<?php echo $last_search;?>">
					<input type="hidden" name="sort_order" value="ASC">	
				</form>
				<br/><br/>
			</div>
					<?php foreach ($animes_matched as $anime) { ?>
			 		<?php if ($counter == 0) echo '<div class="row">';?>
			 		<?php if (($counter % 3 == 0) and ($counter != 0)) echo '</div> <br/> <div class="row">';?>
	 				 <div class="col-sm-4">	
	 				 <?php
		 				 $temp = $anime['titles'];	 				 
		 				 $titles = convert_titles_to_hash($temp);		  				 
		 				 echo $titles[$anime['canonical_title']] . "<br/>";
	 				 ?>				 
	 				 <?php $random_num = time();?>
	 				 <a href="#"><img src="<?php echo asset_url() . "poster_images/" . $anime['poster_image_file_name']. "?rand={$random_num}";?> " onerror="this.src='<?php echo asset_url()."imgs/None.jpg"?>'" style="width:165px; height:235px;"></a>
	 					<?php echo "<br/>"?> Episodes: <?php if($anime['episode_count'] > 0) echo $anime['episode_count']; else echo "?";?>
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
			        <td><a id="table_first_row" href="<?php echo site_url("login/profile/{$user['username']}");?>"><?php echo $user['username'];?></a></td>
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