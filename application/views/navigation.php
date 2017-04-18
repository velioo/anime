<?php
	if(isset($this->session->userdata['is_logged_in'])) 
		$logged = TRUE;
	else 
		$logged = FALSE;
	
	if(isset($this->session->userdata['admin'])) {
		if($this->session->userdata['admin'] === TRUE and $logged === TRUE) {
			$is_admin = TRUE;
		}
	} else {
		$is_admin = FALSE;
	}
?>

<script>
	function get_asset_url() {
		var assetUrl = "<?php echo asset_url(); ?>";
		return assetUrl;
	}

	function get_anime_url() {
		var animeUrl = "<?php echo site_url("animeContent/anime/");?>"
		return animeUrl;
	}

	function get_character_url() {
		var characterUrl = "<?php echo site_url("characters/character/");?>"
		return characterUrl;
	}

	function get_user_url() {
		var userUrl = "<?php echo site_url("users/profile/");?>"
		return userUrl;
	}

	function get_actor_url() {
		var actorUrl = "<?php echo site_url("actors/actor/");?>"
		return actorUrl;
	}

	function get_data_url() {
		var dataUrl = "<?php echo base_url() . "searchC/get_search_results/";?>";
		return dataUrl;
	}

	function getNotificationsUrl() {
		var notificationUrl = "<?php echo site_url("notifications/load_notifications");?>";
		return notificationUrl;
	}

	function getMarkAsReadUrl() {
		var markAsReadUrl = "<?php echo site_url("notifications/mark_as_read");?>";
		return markAsReadUrl;
	}

	<?php if($this->session->userdata('is_logged_in')) {?>
		$('head').append('<script src="<?php echo asset_url() . "js/notifications.js";?>">');
	<?php }?>

	$(document).ready(function() {

			$(document).on('click', 'a', function (e) {
				  e.stopPropagation();
			});

			$('a.dropdown-toggle').click(function() {
				$(this).parent().parent().find('.dropdown-submenu').each(function() {
					if($(this).hasClass('open')) {
						$(this).toggleClass('open');
					}
				});
				$(this).parent().toggleClass('open');
			});
	});
</script>

<nav class="navbar navbar-inverse navbar-fixed-top navigation">
  <div class="container-fluid navigation_container">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo base_url();?>">
      	<img alt="None" src="<?php echo asset_url()."imgs/V-Anime.png"?>" id="logo" width="150px" height="50px" style="margin-top: -12px;">
      </a>
    </div>
    <br/>
    <div class="dropdown" id="small_menu_dropdown_div">
  	<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="navigation_small_button" style="font-family: cursive; font-size:10px;">Menu
		  <span class="caret"></span></button>
		  <ul class="dropdown-menu multi-level" role="menu" id="dropdown_list" style="border-radius: 0px;">
		  	<li class="dropdown-submenu"><a class="dropdown-toggle" href="<?php echo site_url("home");?>" style="font-weight: normal;">Home</a>
		  	<li class="dropdown-submenu"><a class="dropdown-toggle" href="#" style="font-weight: normal;">Anime</a>
		  		<ul class="dropdown-menu">
                  <li><a href="<?php echo site_url('searchC/search_anime?search=""');?>">Browse Anime</a></li>
                  <li><a href="<?php echo site_url("animeContent/top_anime");?>">Top Anime</a></li>
                  <li><a href="<?php echo site_url("recommendations/anime_recommendations");?>">Recommendations</a></li>
                 </ul>
		  	</li>
		    <li class="dropdown-submenu"><a class="dropdown-toggle" href="#" style="font-weight: normal;">Characters</a>
		    	<ul class="dropdown-menu">
                  <li><a href="<?php echo site_url('searchC/search_character?search=""');?>">Browse Characters</a></li>
                  <li><a href="<?php echo site_url('searchC/search_people?search=""');?>">Browse Actors</a></li>
                  <li><a href="<?php echo site_url('characters/top_loved_characters');?>">Top Loved Characters</a></li>
                  <li><a href="<?php echo site_url('characters/top_hated_characters');?>">Top Hated Characters</a></li>
                </ul>
		    </li>
		    <li class="dropdown-submenu"><a class="dropdown-toggle" href="#" style="font-weight: normal;">Community</a>
		    	<ul class="dropdown-menu">
                  <li><a href="<?php echo site_url('searchC/search_users?search=""');?>">Browse Users</a></li>
                  <li><a href="#">Anime Reviews</a></li>
                  <li><a href="#">Custom Lists</a></li>
                </ul>
		    </li>
		  </ul>
	</div>

    <ul class="nav navbar-nav">
	      <li class="dropdown">
		      <a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default">Anime</a>
	      <ul class="dropdown-menu">
		      <li><a href="<?php echo site_url('searchC/search_anime?search=""')?>">Browse Anime</a></li>
	      	  <li><a href="<?php echo site_url("animeContent/top_anime");?>">Top Anime</a></li>
	      	  <li><a href="<?php echo site_url("recommendations/anime_recommendations");?>">Recommendations</a></li>
		  </ul>
	  </li>
	  <li class="dropdown">
		  <a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default">Characters</a>
	      <ul class="dropdown-menu">
			  <li><a href="<?php echo site_url('searchC/search_character?search=""');?>">Browse Characters</a></li>
			  <li><a href="<?php echo site_url('searchC/search_people?search=""');?>">Browse Actors</a></li>
		      <li><a href="<?php echo site_url('characters/top_loved_characters');?>">Top Loved Characters</a></li>
              <li><a href="<?php echo site_url('characters/top_hated_characters');?>">Top Hated Characters</a></li>
		  </ul>
	  </li>
		  <li class="dropdown">
			  <a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default">Community</a>
		      <ul class="dropdown-menu">
		      	<li><a href="<?php echo site_url('searchC/search_users?search=""');?>">Browse Users</a></li>
			  	<li><a href="<?php echo base_url(); ?>">Anime Reviews</a></li>
		      	<li><a href="<?php echo base_url(); ?>">Custom Lists</a></li>
			  </ul>
		  </li>
	</ul>
	
	<div id="navigation_small_search">
		<form class="search_form" name="small_search_form_name" action="<?php echo site_url("searchC/search");?>" method="get" style="display: inline;">
      			<select id="small_search_select" class="search_select" name="search_select">
				    <option value="animes" selected="selected" class="navigation_small_search_option">Anime</option>
				    <option value="characters" class="navigation_small_search_option">Characters</option>
				    <option value="users" class="navigation_small_search_option">Users</option>
				    <option value="people" class="navigation_small_search_option">Actors</option>
				</select>
				<input type="text" name="search" id="small_search_box" placeholder="Search..."> 
				<button type="submit" name="submit" id="submit_button">
				   <span class="glyphicon glyphicon-search" style="color:white;"></span>
				</button>
		</form>
	</div>
	
		<div id="navigation_small_logsign">
			<?php if(!isset($this->session->userdata['is_logged_in'])) {?>
		      		<a id="navigation_small_signup" href="<?php echo site_url("SignUp")?>"><span class="glyphicon glyphicon-user"></span> Sign Up</a>
		      		<a id="navigation_small_login" class="log_in_modal" href="<?php //echo site_url("Login")?>"><span class="glyphicon glyphicon-log-in log_in_modal"></span> Login</a>
		    <?php } else {?>
		    	<i class="notifications_icon fa fa-bell" aria-hidden="true"><span class="notifications_number"></span></i>	
				<div class="dropdown small_profile_menu">
				  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="small_user_button"><?php echo $this->session->userdata['username'];?> 
				</button>
				  <ul class="dropdown-menu" style="min-width:50px;">
				  	<li><a href="<?php echo site_url("users/profile/" . $this->session->userdata['username']); ?>" style="text-align: right;">Profile</a></li>
				    <li><a href="<?php echo site_url("userUpdates/user_settings"); ?>" style="text-align: right;">Settings</a></li>
				    <li><a href="<?php echo site_url("Login/logout"); ?>" style="text-align: right;">Logout</a></li>
				  </ul>
				</div>
		    <?php }?>
       </div>
	
      	<ul class="nav navbar-nav navbar-right" id="right_navigation">
	      	<li id="search">
	      		<form class="search_form" name="search_form_name" action="<?php echo site_url('searchC/search');?>" method="get">
	      			<select id="default_search_select" class="search_select" name="search_select">
					    <option value="animes" selected="selected">Anime</option>
					    <option value="characters">Characters</option>
					    <option value="users">Users</option>
					    <option value="people">Actors</option>
					</select>
					<input type="text" name="search" id="search_box" placeholder="Search...">
					<button class="btn btn-primary dropdown-toggle" type="submit" name="submit" id="submit_button">
					   <span class="glyphicon glyphicon-search" style="color:white;"></span>
					</button>
				</form> 
			</li>
			<?php if(!isset($this->session->userdata['is_logged_in'])) {?>
	      		<li><a id="signup_button" href="<?php echo site_url("SignUp")?>"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
	      		<li><a id="login_button" class="log_in_modal" href="<?php //echo site_url("Login")?>"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      		<?php } else {?>  
      		   <i class="notifications_icon fa fa-bell" aria-hidden="true"><span class="notifications_number"></span></i>	       		   
      	   	   <li class="dropdown profile_menu">     	   	   	  
				  <a id="user_button" href="<?php echo site_url("users/profile/" . $this->session->userdata['username']);?>" style="padding-top:10px;">
				  <div id="user_image_div_main_menu"><img id="user_image_main_menu" src="<?php if($this->session->userdata('user_avatar') != "") { echo asset_url(). "user_profile_images/" . $this->session->userdata('user_avatar');} else { echo asset_url()."imgs/Default_Avatar.jpg";}?>">
				  </div><span id="user_username_main_menu"><?php echo $this->session->userdata['username'];?></span></a>
			      <ul class="dropdown-menu">
				  	<li><a href="<?php echo site_url("userUpdates/user_settings"); ?>" style="text-align: right;">Settings</a></li>
			      	<li><a href="<?php echo site_url("Login/logout"); ?>" style="text-align: right;">Logout</a></li>
				  </ul>
			  </li>				  
      		<?php }?>
   		</ul>
   		<div class="notifications_content">
		   	<a href="<?php echo site_url("notifications/all_notifications");?>" class="disable-link-decoration"><div class="see_all_notifications gray-text">See all notifications</div></a>
		   	<div class="notifications" id="notifications">
		   	
		   	</div>
		</div>
  </div>
</nav>

<?php if(!$logged) { include 'login_modal.php'; }?>