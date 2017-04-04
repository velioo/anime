<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Recommendations extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
		$this->load->model('watchlist_model');
		$this->load->model('recommendations_model');
	}
	
	public function anime_recommendations() {		
		
		$data['title'] = 'Anime Recommendations';
		$data['css'] = 'anime_recommendations.css';
					
		if($this->session->userdata('is_logged_in') === TRUE) {						
			
			$genres = $this->recommendations_model->get_most_watched_genres();
			
			if($genres !== NULL) {
				
				$anime_ids = $this->recommendations_model->get_excluded_anime_ids();
				$average_year = $this->recommendations_model->get_avarage_watch_year();
	
				$anime_ids = explode(',', $anime_ids);
				$genres = explode(',', $genres);
				
				$limit = 30;
				
				$animes = $this->recommendations_model->get_recommended_animes($genres, $anime_ids, $average_year, $limit);
	
				if(count($animes) > 0) {
					$animes = $this->watchlist_model->add_user_statuses($animes);
				}			
				
			} else {
				$animes = array();
			}
			
			$data['animes'] = $animes;
		} else {
			
		}
		
		$this->load->view('anime_recommendations', $data);
	}

}
?>