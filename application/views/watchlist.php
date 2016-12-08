<?php include 'head.php';?>

<link rel="stylesheet" href="<?php echo asset_url() . "css/user_navigation_bar.css";?>" type="text/css" />
<script src="<?php echo asset_url() . "js/edit_user_info.js";?>"></script>
<script src="<?php echo asset_url() . "js/watchlist.js";?>"></script>

<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $user['username'])) 
		$is_you = TRUE;
	else 
		$is_you = FALSE;
?>

<script type="text/javascript">
	var user_id = <?php echo $user['id'];?>;
	var star_empty_url_small = "<?php echo asset_url() . "imgs/star_empty_icon_small.png" ?>";
	var star_fill_url_small = "<?php echo asset_url() . "imgs/star_fill_icon_small.png" ?>";
	
	$(document).ready(function() {
		$('#animes').css("opacity", "1");			
	});	

	function getUserId() {
		return user_id;
	}
	
	function getWatchlistUrl() {
		var watchlist_url = "<?php echo site_url("watchlists/load_watchlist");?>";
		return watchlist_url;
	}

	function getDefaultPage() {
		var default_watchlist_page = <?php if($is_you) echo $user['default_watchlist_page']; else echo 0;?>;
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
	
	<?php if($is_you) { ?>
	function showEditFields() {
			editUserInfo(false, 0);		
			$('head').append('<link rel="stylesheet" href="<?php echo asset_url() . "css/temp_disable_selection.css";?>" type="text/css" />');		
	}

	<?php }?>
</script>

<?php include 'navigation.php'; ?>

<div id="wrap">
	<?php include 'user_profile_top.php';?>
	<div class="container-fluid scrollable" id="user_content">
		<br>		
		<p class="main_title">Watchlist</p>	
		<div id="watchlist_content" class="col-sm-12">
			<div id="watchlist_filter">
				<div id="filter_group" class="btn-group">
				  <button type="button" class="btn btn-primary filter" id="all_tab" data-id="0">All</button>
				  <button type="button" class="btn btn-primary filter" id="watched_tab" data-id="1">Watched</button>
				  <button type="button" class="btn btn-primary filter" id="watching_tab" data-id="2">Watching</button>
				  <button type="button" class="btn btn-primary filter" id="want_watch_tab" data-id="3">Want to Watch</button>
				  <button type="button" class="btn btn-primary filter" id="stalled_tab" data-id="4">Stalled</button>
				  <button type="button" class="btn btn-primary filter" id="dropped_tab" data-id="5">Dropped</button>
				</div>
			</div>
			<div id="watchlist_search_div">
				<input  type="text" id="watchlist_search" name="watchlist_search" placeholder="Search watchlist...">
			</div>
			<div id="sort_options_div">			
				<div class="table-responsive">
				   <table class="table">
					    <thead>
					      <tr>
					        <th style="width: 38%;">Title<span class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th style="padding-right: 2px; padding-left: 0px">Type<span class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th style="padding-right: 10px; width: 90px; padding-left: 0px;">Year<span class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th style="padding-right: 9px; width: 145px; padding-left: 0px;">Progress<span class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th>AVG<span class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th >Rating<span class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					        <th >Status<span class="title_caret fa fa-caret-up"></span><span class="title_caret fa fa-caret-down"></span></th>
					      </tr>
					    </thead>
				  </table>
			  </div>		
			</div>
			
			<div id="watched_content" class="watchlist_content">
				<div id="watched_row" class="title_row">Watched</div>			
				<div class="table-responsive">
				   <table class="table">
					    <tbody>
					    </tbody>
				  </table>
			  </div>
			</div>
			<div id="watching_content" class="watchlist_content">
				<div id="watching_row" class="title_row">Watching</div>
				<div class="table-responsive">
				   <table class="table">
					    <tbody>
					    </tbody>
				  </table>
			  </div>
			</div>
			<div id="want_watch_content" class="watchlist_content">
				<div id="want_watch_row" class="title_row">Want to Watch</div>
				<div class="table-responsive">
				   <table class="table">
					    <tbody>
					    </tbody>
				  </table>
			  </div>
			</div>
			<div id="stalled_content" class="watchlist_content">
				<div id="stalled_row" class="title_row">Stalled</div>
				<div class="table-responsive">
				   <table class="table">
					    <tbody>
					    </tbody>
				  </table>
			  </div>
			</div>
			<div id="dropped_content" class="watchlist_content">
				<div id="dropped_row" class="title_row">Dropped</div>
				<div class="table-responsive">
				   <table class="table">
					    <tbody>
					    </tbody>
				  </table>
			  </div>
			</div>
		</div>
	</div>
</div>
	
<?php include 'footer.php';?>