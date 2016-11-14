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
		  	<li class="dropdown-submenu"><a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default; font-weight: normal;">Anime</a>
		  		<ul class="dropdown-menu">
                  <li><a href="<?php echo site_url('SearchC/search_anime?search=""')?>">Browse Anime</a></li>
                  <li><a href="#">Top Anime</a></li>
                 </ul>
		  	</li>
		    <li class="dropdown-submenu"><a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default; font-weight: normal;">Characters</a>
		    	<ul class="dropdown-menu">
                  <li><a href="#">Browse Characters</a></li>
                  <li><a href="#">Top Loved Characters</a></li>
                  <li><a href="#">Top Hated Characters</a></li>
                </ul>
		    </li>
		    <li class="dropdown-submenu"><a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default; font-weight: normal;">Community</a>
		    	<ul class="dropdown-menu">
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
		      <li><a href="<?php echo site_url('SearchC/search_anime?search=""')?>">Browse Anime</a></li>
	      	  <li><a href="<?php echo base_url(); ?>">Top Anime</a></li>
		  </ul>
	  </li>
	  <li class="dropdown">
		  <a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default">Characters</a>
	      <ul class="dropdown-menu">
			  <li><a href="<?php echo base_url(); ?>">Browse Characters</a></li>
		      <li><a href="<?php echo base_url(); ?>">Top Loved Characters</a></li>
		      <li><a href="<?php echo base_url(); ?>">Top Hated Characters</a></li>
		  </ul>
	  </li>
		  <li class="dropdown">
			  <a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default">Community</a>
		      <ul class="dropdown-menu">
			  	<li><a href="<?php echo base_url(); ?>">Anime Reviews</a></li>
		      	<li><a href="<?php echo base_url(); ?>">Custom Lists</a></li>
			  </ul>
		  </li>
	</ul>
	
	<div id="navigation_small_search">
		<form class="search_form" name="small_search_form_name" action="<?php echo site_url("SearchC/search");?>" method="get" style="display: inline;">
      			<select class="search_select" name="search_select">
				    <option value="anime" selected="selected" class="navigation_small_search_option">Anime</option>
				    <option value="characters" class="navigation_small_search_option">Characters</option>
				    <option value="users" class="navigation_small_search_option">Users</option>
				    <option value="lists" class="navigation_small_search_option">Lists</option>
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
		      		<a id="navigation_small_login" href="<?php echo site_url("Login")?>"><span class="glyphicon glyphicon-log-in"></span> Login</a>
		    <?php } else {?>
				<div class="dropdown small_profile_menu">
				  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="small_user_button"><?php echo $this->session->userdata['username'];?> 
				  </button>
				  <ul class="dropdown-menu" style="min-width:50px;">
				  	<li><a href="<?php echo site_url("login/profile/" . $this->session->userdata['username']); ?>" style="text-align: right;">Profile</a></li>
				    <li><a href="<?php echo site_url("userUpdates/user_settings"); ?>" style="text-align: right;">Settings</a></li>
				    <li><a href="<?php echo site_url("Login/logout"); ?>" style="text-align: right;">Logout</a></li>
				  </ul>
				</div>
		    <?php }?>
       </div>
	
      	<ul class="nav navbar-nav navbar-right" id="right_navigation">
	      	<li id="search">
	      		<form class="search_form" name="search_form_name" action="<?php echo site_url('SearchC/search');?>" method="get">
	      			<select class="search_select" name="search_select">
					    <option value="anime" selected="selected">Anime</option>
					    <option value="characters">Characters</option>
					    <option value="users">Users</option>
					    <option value="lists">Lists</option>
					</select>
					<input type="text" name="search" id="search_box" placeholder="Search...">
					<button class="btn btn-primary dropdown-toggle" type="submit" name="submit" id="submit_button">
					   <span class="glyphicon glyphicon-search" style="color:white;"></span>
					</button>
				</form> 
			</li>
			<?php if(!isset($this->session->userdata['is_logged_in'])) {?>
	      		<li><a id="signup_button" href="<?php echo site_url("SignUp")?>"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
	      		<li><a id="login_button" href="<?php echo site_url("Login")?>"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      		<?php } else {?>
      	   	   <li class="dropdown profile_menu">
				  <a id="user_button" href="<?php echo site_url("Login/profile/" . $this->session->userdata['username']);?>" style="padding-top:10px;"><?php echo $this->session->userdata['username'];?></a>
			      <ul class="dropdown-menu">
				  	<li><a href="<?php echo site_url("userUpdates/user_settings"); ?>" style="text-align: right;">Settings</a></li>
			      	<li><a href="<?php echo site_url("Login/logout"); ?>" style="text-align: right;">Logout</a></li>
				  </ul>
			  </li>
			  	    
      		<?php }?>
   		</ul>
  </div>
</nav>