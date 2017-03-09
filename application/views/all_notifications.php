<?php include 'head.php';?>
<?php include 'navigation.php'; ?>

<script src="<?php echo asset_url() . "js/all_notifications.js";?>"></script>

<script>
	function getTotalNotificationGroups() {
		var total_groups = <?php echo $total_groups;?>;
		return total_groups;
	}
</script>

<div id="wrap">
	<div class="container-fluid scrollable" style="text-align: center;">
	   	<div class="notifications_content_all">
	   		
	   	</div>
	   	<div id="loader_image_div">
			<img src="<?php echo asset_url() . "imgs/loading_records.gif";?>" class="loader_image">
		</div>
	</div>
</div>

<?php include 'footer.php';?>