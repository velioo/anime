<?php

class Reviews extends CI_Controller {
	
	public function add_edit_review($slug = null) {
		$this->load->model('reviews_model');
		$this->load->model('animes_model');
		
		if($slug != null) {
			
			$slug = str_replace("-", " ", $slug);
			
			$query = $this->animes_model->get_anime_id($slug);
			
			if($query) {
					
				$anime_id = $query['id'];
			
				$anime = $this->animes_model->get_anime($anime_id);
				if($anime) {
					$temp = $anime['titles'];
					$titles = convert_titles_to_hash($temp);
					$data['anime_name'] = $titles['main'];
					$data['anime_id'] = $anime['id'];
					$data['slug'] = str_replace(" ", "-", $anime['slug']);
					
					if($this->session->userdata('is_logged_in') === TRUE) {
						$user_review = $this->reviews_model->get_user_review($anime_id);
						if($user_review) {
							$data['review'] = $user_review;
						}
					} 
					
				} else {
					$this->page_not_found();
				}
				
				$data['title'] = 'V-Anime';
				$data['css'] = 'review.css';
				$this->load->view('add_edit_review_page', $data);
			} else {
				$this->page_not_found();
			}
		} else {
			$this->page_not_found();
		}
	}
	
	public function submit_review($anime_id=null) {
		$this->load->model('reviews_model');
		$this->load->model('animes_model');

		if($anime_id != null and is_numeric($anime_id)) {
		
			$user_review = addslashes($this->input->post('user_review'));
			
			$user_scores[] = $this->input->post('story');
			$user_scores[] = $this->input->post('animation');
			$user_scores[] = $this->input->post('sound');
			$user_scores[] = $this->input->post('characters');
			$user_scores[] = $this->input->post('enjoyment');
			$user_scores[] = $this->input->post('overall');
	
			for($i = 0; $i < 6; $i++) {
				if(!($user_scores[$i] >= 1 && $user_scores[$i] <= 10)) {
					$user_scores[$i] = 0;
				} 
			}
			
			if($this->session->flashdata('update')) { 
				$query = $this->reviews_model->update_review($anime_id, $user_review, $user_scores);
			} else {
				$query = $this->reviews_model->add_review($anime_id, $user_review, $user_scores);
			}
		
			if($query) {		
				if($query === "exists") {
					$message = "You've already added a review for this show";
				} else if($query === "updated"){
					$message = "You've successfully updated your review";
				} else {
					$message = "You've successfully added a review";
				}
				
				$this->session->set_flashdata('message', $message);
				
				$anime = $this->animes_model->get_anime($anime_id);
				
				$slug = str_replace(" ", "-", $anime['slug']);
				
				redirect("animeContent/anime/{$slug}");
			} else {
				$this->server_error();
			}		
		} else {
			$this->page_not_found();
		}
	}
	
	function load_reviews($anime_id=null) {	
		$this->load->model('reviews_model');
		$this->load->model('animes_model');
 		
  		if($anime_id != null and is_numeric($anime_id)) {
		
			$group_number = $this->input->post('group_number');
			$reviews_per_page = 4;
			$offset = ceil($group_number * $reviews_per_page);
			
			$result = $this->reviews_model->get_anime_reviews($anime_id, $reviews_per_page, $offset);

			if($result) {
				
				$counter = 1;
				$text_array = array();
				$review_div;
				$start_with;
				$end_with;
				
	 			foreach($result as $review) {
	 				
	 				$text = strip_review_tags($review['review_text']);
	 				
	 				if(mb_strlen($text) > 200) {
	 					$text =  '"' . substr($text, 0, 200) . "...\"";
	 				} else {
	 					$text =  '"' . $text .  '"';
	 				}
	 				
	 				if($counter % 2 == 1) {	 					
	 					$review_div = "first_review_div";	 					
	 					$start_with = '<div class="wrap_review_divs">';	 					
	 					$end_with = '';
	 					
	 				} else {	 					
	 					$review_div = "second_review_div";
	 					$start_with = "";
	 					$end_with = '</div>';
	 				}
	 				
	 				$text = $start_with . '<div class="review_div ' . $review_div . ' talk-bubble tri-right border round btm-right-in"><p class="review_text">' . $text . '</p>
		 						 		<div class="user_review_image_div">
		 						 			<a href="' . site_url("users/profile/{$review['username']}") . '"><div class="user_review_image_second_div"><img class="user_review_image" src="' . 
		 						 									asset_url() . "user_profile_images/{$review['profile_image']}" . '"></div>' . '<div class="user_review_image_username_div">' . $review['username'] . '</div>' . '</a>
		 						 								 			
		 						 		</div>
		 						 		<a href="'. site_url("reviews/user_review/" . str_replace(" ", "-", $review['slug']) . "/{$review['username']}") . '" class="read_more">
		 						 				<span class="blue-text">Full review...</span></a>
		 						 	</div>' . $end_with;
	 						 									
	 				$text_array[] = $text;				
						
					$counter++;
				} 
				
				foreach($text_array as $text) {
					echo $text;
				}
							
			} else {
				echo "<h1 style='text-align: center;margin-top:20px;'>No Reviews Yet</h1>";
			}
		}   
		
	}
	
	public function user_review($slug = null, $username = null) {
		$this->load->model('reviews_model');
		$this->load->model('animes_model');
		$this->load->model('users_model');
		
		if(($slug != null) && ($username != null)) {

			$slug = str_replace("-", " ", $slug);
			
			$query = $this->animes_model->get_anime_id($slug);
			
			if($query) {
				
				$anime_id = $query['id'];
				
				$query = $this->users_model->get_user_info($username);
				
				if($query) {
					
					$user_id = $query['id'];
				
					$anime = $this->animes_model->get_anime($anime_id);
				
					if($anime) {			
						$data['anime'] = $anime;
						
						$user_review = $this->reviews_model->get_user_review($anime_id, $user_id);
						if($user_review) {
							$data['review'] = $user_review;
						}
						
					} else {
						$this->page_not_found();
					}
					
					$data['title'] = 'V-Anime';
					$data['css'] = 'user_review.css';
					$this->load->view('show_review_page', $data);
				} else {
					$this->page_not_found();
				}
				
			} else {
				$this->page_not_found();
			}
		} else {
			$this->page_not_found();
		}

	}
	
	public function user_reviews($username) {
		$this->load->model('reviews_model');
		$this->load->model('users_model');
		
		if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
			$query = $this->users_model->get_user_info_logged($username);
		} else {
			$query = $this->users_model->get_user_info($username);
			if(!$query) {
				$this->page_not_found();
			}
		}
				
		$data['results'] = $query;
		
		$user_reviews = $this->reviews_model->get_user_review(0, $query['id']);
		
		if($user_reviews) {
			$data['reviews'] = $user_reviews;
		} 
		
		$data['title'] = $username . '\'s profile';
		$data['css'] = 'user.css';
		$this->load->view('user_reviews', $data);
	}

	
	function server_error() {
		header('HTTP/1.1 500 Internal Server Error');
		echo "<h1>Error 500 Internal Server Error</h1>";
		echo "There was a problem with the server";
		exit();
	}
	
	function page_not_found() {
		header('HTTP/1.0 404 Not Found');
		echo "<h1>Error 404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		exit();
	}
	
}

?>