<?php include 'head.php';?>
<?php include 'navigation.php'; ?>

<link rel="stylesheet" href="<?php echo asset_url() . "css/user_navigation_bar.css";?>" type="text/css" />
<script src="<?php echo asset_url() . "js/edit_user_info.js";?>"></script>
<script src="<?php echo asset_url() . "js/watchlist.js";?>"></script>

<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $user['username'])) {
		$is_you = TRUE;
	} else {
		$is_you = FALSE;
	}
?>

<script type="text/javascript">
	var star_empty_url_small = "<?php echo asset_url() . "imgs/star_empty_icon_small.png" ?>";
	var star_fill_url_small = "<?php echo asset_url() . "imgs/star_fill_icon_small.png" ?>";
	
	$(document).ready(function() {
		$('#animes').css("opacity", "1");	
		<?php if(!$is_you) {?>
			$('head').append('<script src="<?php echo asset_url() . "js/follow.js";?>">');	
		<?php }?>		
	});	

	function getUserId() {
		return <?php echo $user['id'];?>;
	}

	function getIsYou() {
		var is_you = <?php if($is_you) echo 1; else echo 0; ?>;
		return is_you;
	}
	
	//function getWatchlistUrl() {
		//var watchlist_url = "<?php echo site_url("watchlists/load_watchlist");?>";
		//return watchlist_url;
	//}

	function getUpdateDefaultWatchlistSortUrl() {
		var update_watchlist_sort_url = "<?php echo site_url("watchlists/update_default_watchlist_sort"); ?>";
		return update_watchlist_sort_url;
	}

	function getDefaultWatchlistSortUrl() {
		var default_watchlist_sort = "<?php echo site_url("watchlists/get_default_watchlist_sort");?>";
		return default_watchlist_sort;
	}

	function getDefaultPage() {
		var default_watchlist_page = <?php echo $page;?>;
		return default_watchlist_page;
	}

	function getStarEmptyUrl() {
		return star_empty_url_small;
	}

	function getStarFillUrl() {
		return star_fill_url_small;
	}

	function getScoreUrl() {
		var score_url = "<?php echo site_url("watchlists/update_score");?>";
		return score_url;
	}

	function getStatusUrl() {
		var url = "<?php echo site_url("watchlists/update_status");?>";
		return url;
	}

	function getUpdateEpsUrl() {
		var update_eps_url = "<?php echo site_url("watchlists/update_eps");?>";
		return update_eps_url;
	}

	function getFollowUrl() {
		var follow_url = "<?php echo site_url("follow/follow_user")?>";
		return follow_url;
	}

	function getUnfollowUrl() {
		var unfollow_url = "<?php echo site_url("follow/unfollow_user")?>";
		return unfollow_url;
	}
	
	<?php if($is_you) { ?>
	function showEditFields() {
			editUserInfo(false, 0);		
			$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/temp_disable_selection.css";?>" type="text/css" />');		
	}

	<?php }?>
</script>

<div id="wrap">
	<?php include 'user_profile_top.php';?>
	<div class="container-fluid scrollable" id="user_content">
		<br>		
		<p class="main_title">Watchlist</p>	
		<div id="watchlist_content" class="col-sm-12">
			<div id="watchlist_filter">
				<div id="filter_group" class="btn-group" role="group">
				  <a href="<?php echo site_url("watchlists/user_watchlist/{$user['username']}/all")?>" id="all_tab" class="disable-link-decoration btn btn-primary filter">
				  	All
				  </a>
				   <a href="<?php echo site_url("watchlists/user_watchlist/{$user['username']}/watched")?>" id="watched_tab" class="disable-link-decoration btn btn-primary filter">
				  	Watched
				  </a>
				   <a href="<?php echo site_url("watchlists/user_watchlist/{$user['username']}/watching")?>" id="watching_tab" class="disable-link-decoration btn btn-primary filter">
				 	 Watching
				  </a>
				   <a href="<?php echo site_url("watchlists/user_watchlist/{$user['username']}/want_to_watch")?>" id="want_watch_tab" class="disable-link-decoration btn btn-primary filter">
				 	 Want to Watch
				  </a>
				   <a href="<?php echo site_url("watchlists/user_watchlist/{$user['username']}/stalled")?>" id="stalled_tab" class="disable-link-decoration btn btn-primary filter">
				  	Stalled
				  	</a>
				  <a href="<?php echo site_url("watchlists/user_watchlist/{$user['username']}/dropped")?>" id="dropped_tab" class="disable-link-decoration btn btn-primary filter">
				  	Dropped
				  </a>
				</div>
			</div>
			<div id="watchlist_search_div">
				<input type="text" id="watchlist_search" name="watchlist_search" placeholder="Search watchlist...">
			</div>
			<div id="sort_options_div">			
				<div class="table-responsive">
				   <table class="table">
					    <thead>
					      <tr>
					        <th id="title_header"><span class="title_text">Title</span><span id="title_sort" class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th id="type_header"><span class="title_text">Type</span><span id="type_sort" class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th id="year_header"><span class="title_text">Year</span><span id="year_sort" class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th id="progress_header"><span class="title_text">Progress</span><span id="progress_sort" class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th id="avg_header"><span class="title_text">AVG</span><span id="avg_sort" class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th id="rating_header"><span class="title_text">Rating</span><span id="rating_sort" class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th id="status_header"><span class="title_text">Status</span></th>
					      </tr>
					    </thead>
				  </table>
			  </div>		
			</div>
						
			<?php if($page == 1 || $page == 0) {?>
			
			<div id="watched_content" class="watchlist_content">
				<div id="watched_row" class="title_row">Watched<span class="anime_count"></span></div>			
				<div class="table-responsive">
				   <table class="table tablesorter">
				   <thead></thead> 
					    <tbody>
					    	<?php foreach($watched_animes as $anime) {
					    		  	echo $anime;
					    		  }
					    	?>				    	
					    </tbody>
				  </table>
			  </div>
			</div>
			
			<?php } if($page == 2 || $page == 0) {?>
			
			<div id="watching_content" class="watchlist_content">
				<div id="watching_row" class="title_row">Watching<span class="anime_count"></span></div>
				<div class="table-responsive">
				   <table class="table tablesorter">
				   <thead></thead> 
					    <tbody>
					    	<?php foreach($watching_animes as $anime) {
					    			echo $anime;
					    		  }
					    	?>	
					    </tbody>
				  </table>
			  </div>
			</div>
			
			<?php } if($page == 3 || $page == 0) {?>
			
			<div id="want_watch_content" class="watchlist_content">
				<div id="want_watch_row" class="title_row">Want to Watch<span class="anime_count"></span></div>
				<div class="table-responsive">
				   <table class="table tablesorter">
				   <thead></thead> 
					    <tbody>
					    	<?php foreach($want_to_watch_animes as $anime) {
					    		  	echo $anime;
					    		  }
					    	?>	
					    </tbody>
				  </table>
			  </div>
			</div>
			
			<?php } if($page == 4 || $page == 0) {?>
			
			<div id="stalled_content" class="watchlist_content">
				<div id="stalled_row" class="title_row">Stalled<span class="anime_count"></span></div>
				<div class="table-responsive">
				   <table class="table tablesorter">
				   <thead></thead> 
					    <tbody>
					    	<?php foreach($stalled_animes as $anime) {
					    		  	echo $anime;
					    		  }
					    	?>	
					    </tbody>
				  </table>
			  </div>
			</div>
			
			<?php } if($page == 5 || $page == 0) {?>
			
			<div id="dropped_content" class="watchlist_content">
				<div id="dropped_row" class="title_row">Dropped<span class="anime_count"></span></div>
				<div class="table-responsive">
				   <table class="table tablesorter">
				   <thead></thead> 
					    <tbody>
					    	<?php foreach($dropped_animes as $anime) {
					    		  	echo $anime;
					    		  }
					    	?>	
					    </tbody>
				  </table>
			  </div>
			</div>
			
			<?php }?>
			<div id="loader_watchlist_image_div">
				<img src="<?php echo asset_url() . "imgs/loading_records.gif";?>" class="loader_watchlist_image">
			</div>
		</div>
	</div>
</div>
	
<?php include 'footer.php';?>