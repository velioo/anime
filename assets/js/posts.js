$(document).ready(function() {

	var total_groups = getTotalPosts();
	var wall_owner = getWallOwner();
	var is_you = getIsYou();
	var is_logged = getIsLogged();
	var comment_id = null;
	var edited_post_id = null;
	var total_records = 0;
	var loading = false;
	
	$('#new_post_area').autogrow({vertical: true, horizontal: false, flickering: false});
	
	$('#new_post_area').focus(function() {
		$('#post_options').css("display", "inline-block");
	});
	
	var load_posts_url = getPostsUrl();
	
	loading = true;
    $.post(load_posts_url, {'wall_owner': wall_owner, 'group_number': total_records},
	    function(data){ 
	        if (data != "") {  
		        $(data).each(function(index, element) {
		        	$('#timeline_div div:last').before(element);
		        });		        
		    	add_fix_comments();		    	
		        total_records++;
	         }
	        loading = false;
    });
    
	$(window).scroll(function() {		
		if(total_records >= total_groups) {
			$(window).off('scroll');
		}
	    if(($(window).scrollTop() + $(window).height() == $(document).height()) && loading == false) {    
	        if(total_records < total_groups) {
	          loading = true; 
	          $('#loader_image_div').show(); 
		          $.post(load_posts_url, {'wall_owner': wall_owner, 'group_number': total_records},
	                  function(data){ 
	                      if (data != "") {              
	                    	  $(data).each(function(index, element) {
	                    		  $('#timeline_div div:last').before(element);
	                    	  });
	                    	  add_fix_comments();	
	                    	  total_records++;
	                      }
	                      loading = false;
	                      $('#loader_image_div').hide(); 
	                  });         	  
	        }
	    }
	});
	
	function strip_tags(str){
		return str.replace(/<\/?[^>]+>/gi, '');
	}

	
	$('#submit_post').click(function() {
		var content = strip_tags($('#new_post_area').val());
		
		if(content != '') {
			
			var div = document.createElement('div');
			div.innerHTML = content;
			var content = div.textContent || div.innerText || '';
			var user_url = getUserUrl();
			var username = getUserName();
			var user_image = getUserImage();
			var url = getAddPostUrl();
			var date = Date().toString();
			date = date.split(' ');
			date = date[2] + " " + date[1] + " " + date[3];		
		
			$.ajax({
		        method: "POST",
		        url: url,
		        data: { wall_owner: wall_owner, content: content }
		      })
		    .done(function(post_id) {
		    	if(post_id) {			
					var modify_div = "<div class='post_settings_div'>\
					        	<span class='fa fa-angle-down open_post_settings'></span>\
					        	<div class='post_settings'>\
					        		<div class='post_option edit_post'>Edit Post</div>\
					        		<div class='post_option delete_post'>Delete Post</div>\
					        	</div>\
					        </div>";
					
						$('#timeline_div').prepend("<div class='post' data-id='" + post_id + "'>\
							<div class='post_header'>\
								<div class='user_image_div'>\
						      		<a href='" + user_url + "'><img class='user_image' src='" + user_image + "'></a>\
						        </div>\
						        <div class='user_name'>\
						        	<a href='" + user_url + "' class='disable-link-decoration'>" + username + "</a>\
						        </div>" + modify_div + 
						        "<div class='post_time'>" + date + "<a href='site_url('posts/post/'" + post_id + "')' class='disable-link-decoration gray-text'> &middot; Permalink</a>" +
						        "</div>\
							</div>\
							<div class='post_body'>" + content + 
							"</div>\
							<div class='comments'>\
							</div>\
							<div class='post_footer'>\
								<input type='text' class='submit_comment' placeholder='Leave a Comment...'>\
							</div>\
						</div>");
						$('#new_post_area').val('');
						$('#post_options').hide();			
		    	} else {
		    		window.alert("Failed to create post")
		    	}
		    });
		}
	});
	
	$('#timeline_div').on("keypress", ".submit_comment", function(e) {
		 if (e.which == 13) {
			 if($(this).parent().parent().data('id') != edited_post_id) {
				 var content = strip_tags($(this).val());
				 if(content != "") {
					 var post_id = $(this).parent().parent().data('id');
					 var user_url = getUserUrl();
					 var username = getUserName();
					 var user_image = getUserImage();			 
					 var url = getAddCommentUrl();
					 var self = $(this);
				     var date = Date().toString();
					 date = date.split(' ');
					 date = date[2] + " " + date[1] + " " + date[3];	
					 
					$.ajax({
				        method: "POST",
				        url: url,
				        data: { post_id: post_id, content: content }
				      })
				    .done(function(comment_id) {
				    	if(comment_id) {
				    		
							var modify_div = "<div class='post_settings_div'>\
								        	<span class='fa fa-angle-down open_post_settings'></span>\
								        	<div class='post_settings'>\
								        		<div class='post_option edit_comment'>Edit Comment</div>\
								        		<div class='post_option delete_comment'>Delete Comment</div>\
								        	</div>\
								        </div>";
							 
							self.parent().prev().append("<div class='comment' data-id='" + comment_id + "'>\
										<div class='user_image_div'>\
								      		<a href='" + user_url + "'><img class='user_image' src='" + user_image + "'></a>\
								        </div>\
								         <div class='comment_text more'><span class='user_name'><a href='" + user_url + "'class='disable-link-decoration'>" + username + "&nbsp</a></span><span class='content'>" + content + "</span></div>" + modify_div + 		        					       
								       "<div class='post_time'>" + date +
								       "</div>\
									</div>");
							 self.val("");
							 self.blur();
		
							 add_fix_comments();			    		
				    	} else {
				    		window.alert("Failed to create comment")
				    	}
				    });
				}
			 } else {
				 var content = strip_tags($(this).val());
				 var url = getEditCommentUrl();
				 var self = $(this);
				 
				 $.ajax({
				        method: "POST",
				        url: url,
				        data: { comment_id: comment_id, content: content }
				      })
				    .done(function(msg) {
				    	if(msg == "Success") {
				    		$('.comment').each(function() {
				    			if($(this).data('id') == comment_id) {
				    				$(this).find('.comment_text').find('.content').text(content);			
				    				$(this).css('border', "none");
				    				$(this).css('border-bottom', "1px solid #DEDEDE");
				    			}
				    		});
				    		
				    		self.val("");
							self.blur();
		
							add_fix_comments();				    		
				    	} else {
				    		window.alert("Failed to update comment")
				    	}
						 comment_id = null;
						 edited_post_id = null;
				    });
			 }
		 }
	});
	
	$('#timeline_div').on("click", ".open_post_settings", function(e) {
		e.stopPropagation();
		if($(this).next().is(":hidden")) {
			$('.post_settings').hide();
			$(this).next().show();
		} else {
			$('.post_settings').hide();
		}
	});
	
	$('#timeline_div').on("click", ".post_option", function() {		
		var self = $(this);
		
		if($(this).hasClass('edit_post')) {
			$('.wrap_edit_post_area_div').remove();
			$('.post_body').show();
			var post_text = $(this).parent().parent().parent().next().text();
			$(this).parent().parent().parent().next().after("<div class='wrap_edit_post_area_div'><textarea class='edit_post_area' placeholder='Edit your post...'></textarea><label class='save_edit_post button-blue'>Save</label></div>");		
			$('.edit_post_area').val($(this).parent().parent().parent().next().text());
			$(this).parent().parent().parent().next().hide();		
			
			$('.edit_post_area').autogrow({vertical: true, horizontal: false, flickering: false});				
		} else if(self.hasClass('delete_post')) {
		    var id = self.parent().parent().parent().parent().data('id');	     	    
		    $('#confirm_delete_modal').data('id', id);
		    $('#confirm_delete_modal').data('delete_type', 'post');
		    $('#confirm_delete_modal').find('.modal_title').text("Delete Post");
		    $('#confirm_delete_modal').find('.modal_subtitle').text("post");
		    $('#confirm_delete_modal').modal({
		        show: true
		    });
		    $('body').css("padding-right", "0");			
		} else if(self.hasClass('edit_comment')) {
			
			$('.comment').css('border', "none");
			$('.comment').css('border-bottom', "1px solid #DEDEDE");
			self.parent().parent().parent().css('border', "1px solid #223777");
			
			if(self.parent().parent().prev().find('.content').has('.morelink').length > 0) {
				var text = self.parent().parent().prev().find('.content').contents().first()[0].textContent + self.parent().parent().prev().find('.content').find('.morecontent_span').text();			
				self.parent().parent().parent().parent().next().find('.submit_comment').val(text);								
			} else {
				self.parent().parent().parent().parent().next().find('.submit_comment').val(
						self.parent().parent().prev().find('.content').text()
				);
			}
			comment_id = self.parent().parent().parent().data('id');
			edited_post_id = self.parent().parent().parent().parent().parent().data('id');
			self.parent().parent().parent().parent().next().find('.submit_comment').focus();
						
		} else if(self.hasClass('delete_comment')){
		    var id = self.parent().parent().parent().data('id');	     	    
		    $('#confirm_delete_modal').data('id', id);
		    $('#confirm_delete_modal').data('delete_type', 'comment');
		    $('#confirm_delete_modal').find('.modal_title').text("Delete Comment");
		    $('#confirm_delete_modal').find('.modal_subtitle').text("comment");
		    $('#confirm_delete_modal').modal({
		        show: true
		    });
		    $('body').css("padding-right", "0");
		}		
	});
	
	$('#timeline_div').on("click", ".save_edit_post", function() {
		var content = strip_tags($(this).prev().val());
		var div = document.createElement('div');
		div.innerHTML = content;
		var content = div.textContent || div.innerText || '';
		var post_id = $(this).parent().parent().data('id');
		var self = $(this);
		var url = getEditPostUrl();
		
		$.ajax({
	        method: "POST",
	        url: url,
	        data: { post_id: post_id, content: content }
	      })
	    .done(function(msg) {
	    	if(msg == "Success") {	
	    		var post_body = self.parent().prev();
	    		post_body.text(content);
	    		self.parent().remove();		
	    		post_body.show();		    		
	    	} else {
	    		window.alert("Failed to update post");
	    	}
	    });					
	});
	
	var id;
	$('#confirm_delete_modal').on('shown.bs.modal', function () {
		id = $(this).data('id');
		var delete_type = $(this).data('delete_type');
	    var removeBtn = $(this).find('.danger');
		
	    removeBtn.on('click', function() {    	
	    	if(delete_type == "post") {
	    		deletePost(id);
	    	} else {
	    		deleteComment(id);
	    	}
	    });

	});
	
	$(window).click(function() {
		$('.post_settings').hide();
	});
	
});

function add_fix_comments() {
	$('.comment_text').each(function() {
		if($(this).height() <= 25) {
			$(this).parent().find('.post_time').css("margin-top", "-20px");
		} else {
			$(this).parent().find('.post_time').css("margin-top", "0px");
		}
		show_hide_text($(this));
	});
}

function show_hide_text(element) {
	var showChar = 150;
	var ellipsestext = "...";
	var moretext = "Show more";
	var lesstext = "Show less";
	
    var content = element.find('.content').html();
    
    if(content.length > showChar) {

        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);

        var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span class="morecontent_span">' + h + '</span>&nbsp;&nbsp;<p class="morelink red-text">' + moretext + '</p></span>';

        element.find('.content').html(html);
    }

	$(element).find('.morelink').click(function(){
	    if($(this).hasClass("less")) {
	        $(this).removeClass("less");
	        $(this).html(moretext);
	    } else {
	        $(this).addClass("less");
	        $(this).html(lesstext);
	    }
	    $(this).parent().prev().toggle();
	    $(this).prev().toggle();
	    return false;
	});
}

function deletePost(post_id) {
	$('.btn.danger').off('click');
	var url = getDeletePostUrl();
	$('#confirm_delete_modal').modal('hide');
    $.ajax({
        method: "POST",
        url: url,
        data: { post_id: post_id}
      })
    .done(function(msg) {	  
    	if(msg == "Success") {
    		$('.post').each(function() {    			
    			var data_id = $(this).attr("data-id");
    			if(post_id == data_id) {
    				$(this).remove();
    				return false;
    			}
    		});    		    
    	} else {
    		window.alert("Failed to delete review");
    	}
    }); 
}

function deleteComment(comment_id) {
	$('.btn.danger').off('click');
	var url = getDeleteCommentUrl();
	$('#confirm_delete_modal').modal('hide');
    $.ajax({
        method: "POST",
        url: url,
        data: { comment_id: comment_id}
      })
    .done(function(msg) {	  
    	if(msg == "Success") {
    		$('.comment').each(function() {    			
    			var data_id = $(this).attr("data-id");
    			if(comment_id == data_id) {
    				$(this).remove();
    				return false;
    			}
    		});    		    
    	} else {
    		window.alert("Failed to delete review");
    	}
    }); 
}