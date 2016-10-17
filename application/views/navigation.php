<nav class="navbar navbar-inverse navbar-fixed-top navigation">
  <div class="container-fluid navigation_container">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo base_url();?>">
      	<img alt="None" src="<?php echo asset_url()."imgs/V-Anime.png"?>" id="logo" width="150px" height="50px" style="margin-top: -12px;">
      </a>
    </div>
    <br><br><br>
       <!-- Hidden dropdown  for small screens -->
    <div class="dropdown" id="menu_dropdown_div">
  	<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="navigation_small_button" style="font-family: cursive;">Menu
		  <span class="caret"></span></button>
		  <ul class="dropdown-menu multi-level" role="menu" id="dropdown_list" style="border-radius: 0px;">
		  	<li class="dropdown-submenu"><a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default; font-weight: normal;" id="anime_tab">Anime</a>
		  		<ul class="dropdown-menu">
                  <li><a href="#">Browse Anime</a></li>
                  <li><a href="#">Top Anime</a></li>
                 </ul>
		  	</li>
		    <li class="dropdown-submenu"><a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default; font-weight: normal;" id="characters_tab">Characters</a>
		    	<ul class="dropdown-menu">
                  <li><a href="#">Browse Characters</a></li>
                  <li><a href="#">Top Loved Characters</a></li>
                  <li><a href="#">Top Hated Characters</a></li>
                </ul>
		    </li>
		    <li class="dropdown-submenu"><a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default; font-weight: normal;" id="community_tab">Community</a>
		    	<ul class="dropdown-menu">
                  <li><a href="#">Anime Reviews</a></li>
                  <li><a href="#">Custom Lists</a></li>
                </ul>
		    </li>
		  </ul>
	</div>
	<!-- -------------------------------- -->
    <ul class="nav navbar-nav">
	      <li><a href="<?php echo base_url(); ?>" id="home_tab" >Home</a></li>
	      <li class="dropdown">
		      <a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default" id="anime_tab">Anime</a>
	      <ul class="dropdown-menu">
		      <li><a href="<?php echo base_url(); ?>">Browse Anime</a></li>
	      	<li><a href="<?php echo base_url(); ?>">Top Anime</a></li>
		  </ul>
	  </li>
	  <li class="dropdown">
		  <a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default" id="characters_tab">Characters</a>
	      <ul class="dropdown-menu">
			   <li><a href="<?php echo base_url(); ?>">Browse Characters</a></li>
		      <li><a href="<?php echo base_url(); ?>">Top Loved Characters</a></li>
		      <li><a href="<?php echo base_url(); ?>">Top Hated Characters</a></li>
		  </ul>
	  </li>
		  <li class="dropdown">
			  <a class="dropdown-toggle disabled" data-toggle="dropdown" href="#" style="cursor:default" id="community_tab">Community</a>
		      <ul class="dropdown-menu">
			  	<li><a href="<?php echo base_url(); ?>">Anime Reviews</a></li>
		      	<li><a href="<?php echo base_url(); ?>">Custom Lists</a></li>
			  </ul>
		  </li>
	</ul>
	
	<div id="navigation_small_search">
		<form action="#" method="post" style="display: inline;">
      			<select id="search_select" name="search_select">
				    <option value="anime" selected="selected" class="navigation_small_search_option">Anime</option>
				    <option value="characters" class="navigation_small_search_option">Characters</option>
				    <option value="users" class="navigation_small_search_option">Users</option>
				    <option value="lists" class="navigation_small_search_option">Lists</option>
				</select>
				<input type="text" name="search" id="search_box">
				<input type="submit" name="submit" value="Search" id="search_submit">
		</form>
	</div>
	
	<div id="navigation_small_logsign">
		<a id="navigation_small_signup" href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a>
      	<a id="navigation_small_login" href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a>
	</div>
	
      	<ul class="nav navbar-nav navbar-right" id="right_navigation">
	      	<li id="search">
	      		<form action="#" method="post">
	      			<select id="search_select" name="search_select">
					    <option value="anime" selected="selected">Anime</option>
					    <option value="characters">Characters</option>
					    <option value="users">Users</option>
					    <option value="lists">Lists</option>
					</select>
					<input type="text" name="search" id="search_box">
					<input type="submit" name="submit" value="Search">
				</form> 
			</li>
      		<li><a id="signup_button" href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
      		<li><a id="login_button" href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
   		</ul>
  </div>
</nav>