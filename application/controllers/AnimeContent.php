<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class AnimeContent extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
	}
	
	public function anime($slug = null) {
		
		if($slug != null) {
			
			$this->load->model('animes_model');
			$this->load->model('reviews_model');
			
			$slug = str_replace("-", " ", $slug);
			
			$query = $this->animes_model->get_anime_id($slug);
			
			if($query) {

				$anime_id = $query['id'];
					
				$anime = $this->animes_model->get_anime($anime_id);
					
				if($anime) {
				
					$data['anime'] = $anime;
				
					$reviews = $this->reviews_model->get_anime_reviews($anime_id);
				
					if($reviews) {
						$data['reviews'] = $reviews;
					}
				
					if($this->session->userdata('is_logged_in') === TRUE) {
						$this->load->model('watchlist_model');
						
						$watchlist = $this->watchlist_model->get_watchlist_status_score($anime_id);
						if($watchlist) {
							$data['watchlist_status_name'] = get_watchlist_status_name($watchlist['status']);
							$data['score'] = $watchlist['score'];
						}					
						
						$has_written_review = $this->reviews_model->get_user_review($anime_id);
						if($has_written_review) {
							$data['has_written_review'] = TRUE;
						} else {
							$data['has_written_review'] = FALSE;
						}
					} else {
						$data['has_written_review'] = FALSE;
					}
				
					$data['title'] = 'V-Anime';
					$data['css'] = 'animes.css';
					$data['header'] = "Anime";
					$this->load->view('anime_page', $data);
				
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
	
	public function reviews($slug = null) {
		
		if($slug != null) {
			
			$this->load->model('animes_model');
			$this->load->model('reviews_model');
			
			$slug = str_replace("-", " ", $slug);
			
			$query = $this->animes_model->get_anime_id($slug);
				
			if($query) {
			
				$anime_id = $query['id'];
				
				$anime = $this->animes_model->get_anime($anime_id);

				$total_reviews = $this->reviews_model->get_total_reviews_count($anime_id);
				
				if($total_reviews) {
					$reviews_per_page = 10;
					$data['total_groups'] = ceil($total_reviews['count']/$reviews_per_page);
				} else {
					$this->helpers_model->server_error();
				}
				
				if($this->session->userdata('is_logged_in') === TRUE) {
					$user_review = $this->reviews_model->get_user_review($anime_id);
					
					if($user_review) {
						$data['button_name'] = "Edit your review";
					} else {
						$data['button_name'] = "Write a review";
					}
				} else {
					$data['button_name'] = "Write a review";
				}
				
				$data['anime'] = $anime;
				$data['title'] = "V-Anime";
				$data['css'] = 'reviews.css';
				$this->load->view('reviews', $data);	
			} else {
				$this->helpers_model->page_not_found();
			}
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function characters($slug=null) {
		if($slug != null) {
			$this->load->model('animes_model');
			$this->load->model('characters_model');
			
			$slug = str_replace("-", " ", $slug);
				
			$query = $this->animes_model->get_anime_id($slug);
			
			if($query) {
					
				$anime_id = $query['id'];
				
				$anime = $this->animes_model->get_anime($anime_id);
				
				$total_characters = $this->characters_model->get_characters_count($anime_id);
				
				if($total_characters) {	
					$characters_per_page = 20;
					$data['total_groups'] = ceil($total_characters['count']/$characters_per_page);
				} else {
					$this->helpers_model->server_error();
				}
				
				$data['anime'] = $anime;
				$data['title'] = "V-Anime";
				$data['css'] = 'characters.css';
				$this->load->view('characters', $data);			
			} else {
				$this->helpers_model->page_not_found();
			}
			
		} else {
			$this->helpers_model->bad_request();
		}
	}

}


?>