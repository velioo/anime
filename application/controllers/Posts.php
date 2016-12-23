<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller {
	
	private $logged;
	
	public function __construct() {
		parent::__construct();
		$this->load->model('posts_model');
		$this->load->model('helpers_model');
		$this->logged = $this->session->userdata('is_logged_in');
	}
		
	public function index() {
		redirect("home");
	}

	public function load_posts() {
		
		$this->load->model('users_model');
		
		$wall_owner = $this->input->post('wall_owner');
		$group_number = $this->input->post('group_number');
		$posts_per_page = 10;
		$offset = ceil($group_number * $posts_per_page);
		
		$wall_owner_info = $this->users_model->get_user_info($wall_owner);
		
		$posts = $this->posts_model->get_posts($wall_owner, $posts_per_page, $offset);
		
		if($posts) {
			
			$elements_array = array();
			
			$element = "";
			
			foreach($posts as $post) {
				
				$element = "";
				
				$element.='<div class="post" data-id="' . $post['id'] . '">
					<div class="post_header">
						<div class="user_image_div">
				      		<a href="' . site_url("users/profile/{$post['username']}") . '"><img class="user_image" src="' . asset_url() . "user_profile_images/" . $post['profile_image'] . '"></a>
				        </div>
				        <div class="user_name">
				        	<a href="' . site_url("users/profile/{$post['username']}") .'" class="disable-link-decoration">' . $post['username'] . '</a>
				        </div>';
				
					if($this->logged && $this->session->userdata('username') == $post['username']) {
				        $element.='<div class="post_settings_div">
				        	<span class="fa fa-angle-down open_post_settings"></span>
				        	<div class="post_settings">
				        		<div class="post_option edit_post">Edit Post</div>
				        		<div class="post_option delete_post">Delete Post</div>
				        	</div>
				        </div>';
					}
					
				        $element.='<div class="post_time">';
				        $element.=convert_date(date("Y-m-d", strtotime($post['created_at'])));
				        $element.='</div>
					</div>
					<div class="post_body">' . stripslashes($post['content'])	. '</div>
        			<div class="comments">';
				
					$comments = $this->posts_model->get_post_comments($post['id']);
				
					foreach ($comments as $comment) {
						$element.='<div class="comment" data-id="' . $comment['id'] . '">
										<div class="user_image_div">
											<a href="' . site_url("users/profile/{$comment['username']}") . '"><img class="user_image" src="'. asset_url() . "user_profile_images/" . $comment['profile_image'] . '"></a>
										</div>
								   <div class="comment_text more"><span class="user_name"><a href="' . site_url("users/profile/{$comment['username']}") . '" class="disable-link-decoration">' . $comment['username'] . '&nbsp</a>
								   </span><span class="content">' . stripslashes($comment['content']) . '</span></div>';	
						
						if($this->logged && $this->session->userdata('username') == $comment['username']) {
								 $element.='<div class="post_settings_div">
									<span class="fa fa-angle-down open_post_settings"></span>
									<div class="post_settings">
										<div class="post_option edit_comment">Edit Comment</div>
										<div class="post_option delete_comment">Delete Comment</div>
									</div>
								</div>';	
						}	
								
						$element.='<div class="post_time">';
						$element.=convert_date(date("Y-m-d", strtotime($comment['created_at'])));	
						$element.='</div>
							    </div>';
					}
				
        			$element.='</div>
					<div class="post_footer">';
        				if($this->logged) {
							$element.='<input type="text" class="submit_comment" placeholder="Leave a Comment...">';
        				} else {
        					$element.="<p style='text-align: center; margin-top: 10px;'><a href='" . site_url("login/login_page") . "' class='disable-link-decoration blue-text'>Log in</a> to comment</p>";
        				}
					$element.='</div>
				</div>';
				
				$elements_array[] = $element;
			}
			
			foreach($elements_array as $element) {
				echo $element;
			}
		} else {
			echo 0;
		}
	}
	
	public function add_post() {
		if($this->logged) {
			
			$wall_owner = $this->input->post('wall_owner');
			$content = $this->input->post('content');
			
			$content = addslashes(trim($content));
			
			$query = $this->posts_model->add_post($wall_owner, $content);
			
			if($query) {
				echo $query;
			} else {
				echo 0;
			}
			
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function edit_post() {
		if($this->logged) {
			
			$post_id = $this->input->post('post_id');
			$content = $this->input->post('content');
			
			$content = addslashes(trim($content));
			
			$query = $this->posts_model->edit_post($post_id, $content);	
			
			if($query) {
				echo "Success";
			} else {
				echo "Fail";
			}
				
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function delete_post() {
		if($this->logged) {
			
			$post_id = $this->input->post('post_id');
			
			$query = $this->posts_model->delete_post($post_id);
			
			if($query) {
				echo "Success";
			} else {
				echo "Fail";
			}
				
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function add_comment() {
		if($this->logged) {
			
			$post_id = $this->input->post('post_id');
			$content = $this->input->post('content');
				
			$content = addslashes(trim($content));
				
			$query = $this->posts_model->add_comment($post_id, $content);
			
			if($query) {
				echo "Success";
			} else {
				echo "Fail";
			}
				
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function edit_comment() {
		if($this->logged) {
			
			$comment_id = $this->input->post('comment_id');
			$content = $this->input->post('content');
			
			$content = addslashes(trim($content));
			
			$query = $this->posts_model->edit_comment($comment_id, $content);
				
			if($query) {
				echo "Success";
			} else {
				echo "Fail";
			}
				
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function delete_comment() {
		if($this->logged) {
			
			$comment_id = $this->input->post('comment_id');
				
			$query = $this->posts_model->delete_comment($comment_id);
				
			if($query) {
				echo "Success";
			} else {
				echo "Fail";
			}
				
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
}

?>