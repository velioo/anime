<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo base_url();?>">
      	<img alt="None" src="<?php echo asset_url()."imgs/V-Anime.png"?>" width="150px" height="50px" style="margin-top: -12px;">
      </a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="<?php echo base_url(); ?>" id="home_tab" >Home</a></li>
      <li class="dropdown">
	      <a class="dropdown-toggle disabled tog" data-toggle="dropdown" href="#" style="cursor:default" id="anime_tab">Anime</a>
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
      	<ul class="nav navbar-nav navbar-right">
      		<li><a id="signup_button" href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
      		<li><a id="login_button" href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
   		</ul>
  </div>
</nav>