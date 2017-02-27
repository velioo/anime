<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class AnimeContent extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
		$this->load->model('animes_model');
	}
	
	public function anime($slug=NULL) {
		
		if($slug != NULL) {
			
			$this->load->model('reviews_model');
			
			$slug = str_replace("-", " ", $slug);
			
			$query = $this->animes_model->get_anime_id($slug);
			
			if($query !== FALSE) {

				$anime_id = $query['id'];
					
				$anime = $this->animes_model->get_anime($anime_id);
					
				if($anime !== FALSE) {
				
					$data['anime'] = $anime;
				
					$reviews = $this->reviews_model->get_anime_reviews($anime_id);
				
					if($reviews !== FALSE) {
						$data['reviews'] = $reviews;
					}
				
					if($this->session->userdata('is_logged_in') === TRUE) {
						$this->load->model('watchlist_model');
						
						$watchlist = $this->watchlist_model->get_watchlist_status_score($anime_id);
						if($watchlist !== FALSE) {
							$data['watchlist_status_name'] = get_watchlist_status_name($watchlist['status']);
							$data['score'] = $watchlist['score'];
						}					
						
						$has_written_review = $this->reviews_model->get_user_review($anime_id, $this->session->userdata('id'));
						if($has_written_review !== FALSE) {
							$data['has_written_review'] = TRUE;
						} else {
							$data['has_written_review'] = FALSE;
						}
					} else {
						$data['has_written_review'] = FALSE;
					}
					
					$user_stats = $this->animes_model->get_users_count_grouped_by_status($anime_id);
					
					$user_stats = process_and_return_stats($user_stats);
					
					if($user_stats !== FALSE) {
						$data['user_stats'] = $user_stats;
					} else {
						$this->helpers_model->server_error();
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
	
	public function reviews($slug=NULL) {
		
		if($slug != NULL) {
			
			$this->load->model('reviews_model');
			
			$slug = str_replace("-", " ", $slug);
			
			$query = $this->animes_model->get_anime_id($slug);
				
			if($query !== FALSE) {
			
				$anime_id = $query['id'];
				
				$anime = $this->animes_model->get_anime($anime_id);

				$total_reviews = $this->reviews_model->get_total_reviews_count($anime_id);
				
				if($total_reviews !== FALSE) {
					$reviews_per_page = 10;
					$data['total_groups'] = ceil($total_reviews/$reviews_per_page);
				} else {
					$this->helpers_model->server_error();
				}
				
				if($this->session->userdata('is_logged_in') === TRUE) {
					$user_review = $this->reviews_model->get_user_review($anime_id, $this->session->userdata('id'));
					
					if($user_review !== FALSE) {
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
	
	public function characters($slug=NULL) {
		if($slug != NULL) {
			$this->load->model('characters_model');
			
			$slug = str_replace("-", " ", $slug);				
			$query = $this->animes_model->get_anime_id($slug);
			
			if($query !== FALSE) {
					
				$anime_id = $query['id'];
				
				$anime = $this->animes_model->get_anime($anime_id);
				
				$total_characters = $this->characters_model->get_characters_count($anime_id);
				
				if($total_characters !== FALSE) {	
					$characters_per_page = 50;
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
	
	public function user_stats($slug=NULL, $status="watched") {
		if($slug != NULL) {
			
			$slug = str_replace("-", " ", $slug);		

			$query = $this->animes_model->get_anime_id($slug);
			
			if($query !== FALSE) {
				
				$anime_id = $query['id'];
				$anime = $this->animes_model->get_anime($anime_id);
				
				$status_id = get_watchlist_status_id($status);
				
				if($status_id == "") {
					$this->helpers_model->page_not_found();
				}
				
				$this->load->library('pagination');			
				$config = $this->configure_pagination();				
				$config['base_url'] = site_url("animeContent/user_stats/{$slug}/{$status}");
				$config['per_page'] = 50;
								
				if($this->input->get('page') != NULL and is_numeric($this->input->get('page'))) { 
					$start = $this->input->get('page') * $config['per_page'] - $config['per_page'];
					$data['page'] = $this->input->get('page');
				} else {
					$start = 0;
					$data['page'] = 1;
				}
				
				if($this->input->get('sort_selected') !== NULL) {
					$sort_by = $this->input->get('sort_selected');
					$sort = get_users_sort($sort_by);
				} else {
					$sort_by = "name_asc";
					$sort = get_users_sort($sort_by);
				}
			
				$users = $this->animes_model->get_users($anime_id, $status_id, $start, $config['per_page'], $sort);				
				$config['total_rows'] = $this->animes_model->get_users_count($anime_id, $status_id);
				
				if($query !== FALSE) {
					$this->pagination->initialize($config);
					$data['pagination'] = $this->pagination->create_links();
					$data['users'] = $users;
				}
			
				$data['sort_by'] = $sort_by;
				$data['status'] = $status;
				$data['status_id'] = $status_id;
				$data['status_square'] = get_status_square($status_id);
				
				$data['anime'] = $anime;
				$data['title'] = "V-Anime";
				$data['css'] = 'anime_user_stats.css';
				$this->load->view('anime_user_stats', $data);
			} else {
				$this->helpers_model->page_not_found();
			}
			
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	function configure_pagination() {
		$config['num_links'] = 4;
		$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
	
		return $config;
	}

}


?>