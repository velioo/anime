<?php include 'head.php';?>

<link rel="stylesheet" href="<?php echo asset_url() . "css/user_navigation_bar.css";?>" type="text/css" />
<link rel="stylesheet" href="<?php echo asset_url() . "css/user_reviews.css";?>" type="text/css" />

<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $results['username'])) 
		$is_you = TRUE;
	else 
		$is_you = FALSE;
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('head').append('<script src="<?php echo asset_url() . "js/edit_user_info.js";?>">');	
		$('#reviews').css("opacity", "1");
	});
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
	<div class="container-fluid scrollable content" id="user_content">
		<br>		
		<?php if(isset($reviews)) { ?>
			<?php foreach($reviews as $review) {?>
			
				<p><?php echo $review['slug'];?></p>
				
			<?php }?>
			
		<?php } else {
			if($is_you) { echo "<p style='text-align:center;font-size:20px;'>You haven't written any anime reviews yet.</p>"; }  
				else {echo "<p style='text-align:center;font-size:20px;'>" . $results['username'] . " hasn't written any anime reviews yet.</p>"; } 
			 }
		?>


	</div>
</div>
	
<?php include 'footer.php';?>