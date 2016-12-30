<?php include 'head.php';?>

<script src="<?php echo asset_url() . "js/post_permalink.js";?>"></script>
<script src="<?php echo asset_url() . "jquery.ns-autogrow-1.1.6/dist/jquery.ns-autogrow.js";?>"></script>

<?php
	if(isset($this->session->userdata['is_logged_in']) and ($this->session->userdata['username'] == $post['username'])) {
		$is_you = TRUE;
		$logged = TRUE;
	} else {
		$is_you = FALSE;
		if(isset($this->session->userdata['is_logged_in']))
			$logged = TRUE;
		else
			$logged = FALSE;
	}
?>

<script type="text/javascript">

	function getWallOwner() {
		var wall_owner = <?php echo $post['user_id'];?>;
		return wall_owner;
	}
	
	function getIsYou() {
		return <?php if($is_you) echo 1; else echo 0;?>;
	}
	
	function getIsLogged() {
		return <?php if($logged) echo 1; else echo 0;?>;
	}
	
	<?php if($logged) {?>
	function getUserImage() {
		var avatar_image = "<?php echo asset_url() . "user_profile_images/" . $this->session->userdata('user_avatar');?>";
		return avatar_image;
	}
	
	function getUserName() {
		var username = "<?php echo $this->session->userdata('username');?>";
		return username;
	}
	
	function getUserId() {
		var user_id = <?php echo $this->session->userdata('id');?>;
		return user_id;
	}
	
	function getUserUrl() {
		var user_url = "<?php echo site_url("users/profile/{$this->session->userdata('username')}");?>";
		return user_url;
	}
	
	function getEditPostUrl() {
		var edit_post_url = "<?php echo site_url("posts/edit_post");?>";
		return edit_post_url;
	}
	
	function getAddCommentUrl() {
		var add_comment_url = "<?php echo site_url("posts/add_comment");?>";
		return add_comment_url;
	}
	
	function getEditCommentUrl() {
		var edit_comment_url = "<?php echo site_url("posts/edit_comment");?>";
		return edit_comment_url;
	}
	
	function getDeletePostUrl() {
		var delete_post_url = "<?php echo site_url("posts/delete_post");?>";
		return delete_post_url;
	}
	
	function getDeleteCommentUrl() {
		var delete_comment_url = "<?php echo site_url("posts/delete_comment");?>";
		return delete_comment_url;
	}
	
	<?php }?>

	function getDeletePostRedirectUrl() {
		var redirect_url = "<?php echo site_url("users/profile/{$post['username']}");?>";
		return redirect_url;
	}

</script>

<?php include 'navigation.php'; ?>

<div id="wrap">
	<div class="container-fluid scrollable" style="text-align: center;">
		<div id="timeline_div" style="margin-right: 0px; width: 70%; float: none; text-align: left;">
			<?php if(!$logged) {?>
				<div class="not_logged_permalink"><span class="disable-link-decoration blue-text log_in_modal">Log in</span> to add posts, comments and follow</div>
			<?php }?>
			<div class="post" data-id="<?php echo $post['id'] ?>">
				<div class="post_header">
					<div class="user_image_div">
			      		<a href="<?php echo site_url("users/profile/{$post['username']}"); ?>"><img class="user_image" src="<?php echo asset_url() . "user_profile_images/" . $post['profile_image'];?>"></a>
			        </div>
			        <div class="user_name">
			        	<a href="<?php echo site_url("users/profile/{$post['username']}");?>" class="disable-link-decoration"><?php echo $post['username'];?></a>
			        </div>
			       	  <?php  if($is_you) { ?>
				        <div class="post_settings_div">
				        	<span class="fa fa-angle-down open_post_settings"></span>
				        	<div class="post_settings">
				        		<div class="post_option edit_post">Edit Post</div>
				        		<div class="post_option delete_post">Delete Post</div>
				        	</div>
				        </div>
					<?php } ?>
					<div class="post_time">
						<?php echo convert_date(date("Y-m-d", strtotime($post['created_at'])));?>
						<a href="<?php echo site_url("posts/post/{$post['id']}");?>" class="disable-link-decoration gray-text"> &middot; Permalink</a>
					</div>
			 	</div>
			 	<div class="post_body"><?php echo stripslashes($post['content']);?></div>
        			<div class="comments">
        				<?php foreach ($post['comments'] as $comment) { ?>
						<div class="comment" data-id=" <?php echo $comment['id']?>">
							<div class="user_image_div">
								<a href="<?php echo site_url("users/profile/{$comment['username']}");?>"><img class="user_image" src="<?php echo asset_url() . "user_profile_images/" . $comment['profile_image'];?>"></a>
							</div>
						    <div class="comment_text more">
							   <span class="user_name">
							   		<a href="<?php echo site_url("users/profile/{$comment['username']}");?>" class="disable-link-decoration"><?php echo $comment['username']; ?>&nbsp;</a>
							   </span>
							   <span class="content"><?php echo stripslashes($comment['content']);?></span>
						    </div>							
							<?php if($logged && $this->session->userdata('username') == $comment['username']) { ?>
									 <div class="post_settings_div">
										<span class="fa fa-angle-down open_post_settings"></span>
										<div class="post_settings">
											<div class="post_option edit_comment">Edit Comment</div>
											<div class="post_option delete_comment">Delete Comment</div>
										</div>
									</div>	
							<?php }?>	
								
							<div class="post_time">
							<?php echo convert_date(date("Y-m-d", strtotime($comment['created_at'])));?>
					   		</div>
					   </div>
					<?php }?>
        		 </div>
        		 <div class="post_footer">
        		 	<?php if($logged) {?>
        		 		<input type="text" class="submit_comment" placeholder="Leave a Comment...">
        		 	<?php }?>
        		 </div>
		     </div>
	   </div>
	</div>
</div>

<?php include 'footer.php';?>

<div id="confirm_delete_modal" class="modal fade" role="dialog">
	<div class="wrap_modal_elements">
	<div class="modal-dialog">
        <div class="modal-content">
		    <div class="modal-header">
		        <a href="#" data-dismiss="modal" class="close">&times;</a>
		         <h3 class="modal_title"></h3>
		    </div>
		    <div class="modal-body">
		        <p>You are about to delete your <span class="modal_subtitle">post</span>, this procedure is irreversible.</p>
		        <p>Do you want to proceed?</p>
		    </div>
		    <div class="modal-footer">
		        <a class="btn danger">Yes</a>
		        <a data-dismiss="modal" class="btn secondary">No</a>
		    </div>
	    </div>
    </div>
    </div>
</div>

<?php if(!$logged) { include 'login_modal.php'; }?>