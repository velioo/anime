<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
		$this->load->model('reviews_model');
	}
	
	public function add_edit_review($slug = null) {
		$this->load->model('animes_model');
		
		if($slug != null && $this->session->userdata('is_logged_in') == TRUE) {
			
			$slug = str_replace("-", " ", $slug);
			
			$query = $this->animes_model->get_anime_id($slug);
			
			if($query !== FALSE) {
					
				$anime_id = $query['id'];
			
				$anime = $this->animes_model->get_anime($anime_id);
				if($anime !== FALSE) {
					$temp = $anime['titles'];
					$titles = convert_titles_to_hash($temp);
					$data['anime_name'] = $titles['main'];
					$data['anime_id'] = $anime['id'];
					$data['slug'] = str_replace(" ", "-", $anime['slug']);
					
					if($this->session->userdata('is_logged_in') === TRUE) {
						$user_review = $this->reviews_model->get_user_review($anime_id, $this->session->userdata('id'));
						if($user_review !== FALSE) {
							$data['review'] = $user_review;
						}
					} 
					
				} else {
					$this->helpers_model->page_not_found();
				}
				
				$data['title'] = 'V-Anime';
				$data['css'] = 'review.css';
				$this->load->view('add_edit_review_page', $data);
			} else {
				$this->helpers_model->page_not_found();
			}
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function submit_review() {
		$this->load->model('animes_model');
		
		$anime_id = $this->input->post('anime_id');
		
		if($anime_id != NULL && is_numeric($anime_id) && $this->session->userdata('is_logged_in')) {

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
			
			$review_exists = $this->reviews_model->check_if_review_exists($anime_id, $this->session->userdata('id'));
			
			if($review_exists !== FALSE) { 
				$query = $this->reviews_model->update_review($anime_id, $user_review, $user_scores);
			} else {
				$query = $this->reviews_model->add_review($anime_id, $user_review, $user_scores);
			}
		
			if($query !== FALSE) {						
				$result = $this->animes_model->get_anime_slug($anime_id);
				
				$slug = str_replace(" ", "-", $result['slug']);	
				
				redirect("reviews/review/{$slug}/{$this->session->userdata('username')}");
			} else {
				$this->helpers_model->server_error();
			}		
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	function load_reviews($anime_id = null) {	
		$this->load->model('animes_model');
 		
  		if($anime_id != null and is_numeric($anime_id)) {
		
			$group_number = $this->input->post('group_number');
			$reviews_per_page = 10;
			$offset = ceil($group_number * $reviews_per_page);
			
			$result = $this->reviews_model->get_anime_reviews($anime_id, $reviews_per_page, $offset);

			if($result !== FALSE) {
				
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
		 						 		<a href="'. site_url("reviews/review/" . str_replace(" ", "-", $review['slug']) . "/{$review['username']}") . '" class="read_more">
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
		} else {
			$this->helpers_model->bad_request();
		}
		
	}
	
	public function review($slug = null, $username = null) {
		$this->load->model('animes_model');
		$this->load->model('users_model');
		
		if(($slug != null) && ($username != null)) {

			$slug = str_replace("-", " ", $slug);
			
			$query = $this->animes_model->get_anime_id($slug);
			
			if($query !== FALSE) {
				
				$anime_id = $query['id'];
				
				$query = $this->users_model->get_user_info($username);
				
				if($query !== FALSE) {
					
					$user_id = $query['id'];
				
					$anime = $this->animes_model->get_anime($anime_id);
				
					if($anime !== FALSE) {			
						$data['anime'] = $anime;
						
						$user_review = $this->reviews_model->get_user_review($anime_id, $user_id);
						if($user_review !== FALSE) {
							$data['review'] = $user_review;
						}
						
					} else {
						$this->helpers_model->page_not_found();
					}
					
					$data['title'] = 'V-Anime';
					$data['css'] = 'user_review.css';
					$this->load->view('show_review_page', $data);
				} else {
					$this->helpers_model->page_not_found();
				}
				
			} else {
				$this->helpers_model->page_not_found();
			}
		} else {
			$this->helpers_model->bad_request();
		}

	}
	
	public function user_reviews($username=null) {		
		if($username != null) {			
			$this->load->model('users_model');
			
			if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
				$query = $this->users_model->get_user_info_logged($username);
			} else {
				$query = $this->users_model->get_user_info($username);
				if($query === FALSE) {
					$this->helpers_model->page_not_found();
				}
			}
					
			$data['user'] = $query;
			
			$total_reviews = $this->reviews_model->get_total_reviews_count_user($query['id']);
				
			if($total_reviews !== FALSE) {
				$reviews_per_page = 10;
				$data['total_groups'] = ceil($total_reviews/$reviews_per_page);
			} else {
				$this->helpers_model->server_error();
			}
	
			$data['title'] = $username . '\'s profile';
			$data['css'] = 'user_reviews.css';
			$this->load->view('user_reviews', $data);
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function load_reviews_user($user_id = null) {			
		if($user_id != null and is_numeric($user_id)) {
			
			$this->load->model('animes_model');
		
			$group_number = $this->input->post('group_number');
			$reviews_per_page = 10;
			$offset = ceil($group_number * $reviews_per_page);
			
			$result = $this->reviews_model->get_user_reviews($user_id, $reviews_per_page, $offset);
			
 			if($result !== FALSE) {
				if(!$this->contains_array($result)) {
					$result = array($result);
				}

				$element_array = array();
				$review_div;
				
				if((isset($this->session->userdata['is_logged_in'])) && ($this->session->userdata('id') === $user_id))  {
					$modify = TRUE;
				} else {
					$modify = FALSE;
				}
				
				foreach($result as $review) {
				
					$slug = str_replace(" ", "-", $review['slug']);
					$full_title = convert_titles_to_hash($review['titles'])['main'];
					
					$review_text = strip_review_tags($review['review_text']);
				
					if(mb_strlen($review_text) > 190) {
						$review_text =  '"' . substr($review_text, 0, 190) . "...\"";
					} else {
						$review_text =  '"' . $review_text .  '"';
					}
					
					if(mb_strlen($full_title) > 35) {
						$title = substr($full_title, 0, 35) . "...";
					} else {
						$title = $full_title;
					}
					
					$element = '<div class="review_div">
								<div class="anime_title"><a title="' . $full_title .'" href="' . site_url("animeContent/anime/{$slug}") . '" class="disable-link-decoration title">' . $title . '</a></div>';
								if($modify === TRUE) {
									$element.='<div class="edit_delete">
											<a href="' . site_url("reviews/add_edit_review/{$slug}") . '" class="disable-link-decoration edit_review"><span class="fa fa-pencil"></span> Edit</a>
												<p class="delete_review" data-id="' . $review['anime_id'] .'"><span class="fa fa-times"></span> Delete</p>
											</div>';
								}						
					$element.= '<div class="review_date"><span class="fa fa-clock-o"> ' . convert_date(date('Y-m-d', strtotime($review['created_at']))) . '</span></div>
								<a href="' . site_url("reviews/review/{$slug}/{$review['username']}") .'" class="disable-link-decoration"><p class="review_text blue-text">' . $review_text . '</p></a>
							</div>';
				
					$element_array[] = $element;
				}
				
				foreach($element_array as $e) {
					echo $e;
				}				
	
			} else {
				if(($this->session->userdata('is_logged_in') === TRUE) && ($this->session->userdata('id') == $user_id)) {
					echo "<h1 style='text-align: center;margin-top:20px;'>You have no reviews yet</h1>";
				} else {
					echo "<h1 style='text-align: center;margin-top:20px;'>This user has no reviews yet</h1>";
				}			
			} 		
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function delete_review() {
		if($this->session->userdata('is_logged_in')) {
			
			$anime_id = $this->input->post('anime_id');
			$user_id = $this->session->userdata('id');		
			
			$query = $this->reviews_model->delete_review($anime_id, $user_id);
	
			if($query !== FALSE) {
				echo "Success";
			} else {
				echo "Fail";
			}
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	function contains_array($array){
		foreach($array as $value){
			if(is_array($value)) {
				return true;
			}
		}
		return false;
	}
}

?>