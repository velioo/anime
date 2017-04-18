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
					
		if($this->session->userdata('is_logged_in') === TRUE) {						
			
			$genres = $this->recommendations_model->get_watched_genres();
			
			if($genres !== NULL) {
				
				$anime_ids = $this->recommendations_model->get_excluded_anime_ids();
				$average_year = $this->recommendations_model->get_avarage_watch_year();
	
				$anime_ids = explode(',', $anime_ids);
				$genres = explode(',', $genres);
				
				$limit = 30;
				$average_year = $average_year - 10;
				
				$all_animes = $this->recommendations_model->get_recommended_animes($anime_ids, $average_year, $limit);
				/* $genre_names = array();
				 foreach($genres as $genre) {
				 $genre_names[] = $genre['name'];
				 } */			
				$animes = array();
				$counter = 0;
				foreach($all_animes as $anime) {
					$temp_genres = explode(",", $anime['genres']);
					foreach($temp_genres as $genre) {
						if(in_array($genre, $genres)) {
							$counter++;
						}
					}
					if($counter >= round(count($temp_genres)/2)) {
						$animes[] = $anime;
					}						
					if(count($animes) == $limit) {
						break;
					}						
					$counter = 0;
				}				
				for($i = 0; $i < count($animes); $i++) {
					$animes[$i]['slug'] = str_replace(" ", "-", $animes[$i]['slug']);
				}				
				if(count($animes) > 0) {
					$animes = $this->watchlist_model->add_user_statuses($animes);
				}			
				
			} else {
				$animes = array();
			}
			
			$data['animes'] = $animes;
		} else {
			
		}
		
		$data['title'] = 'Anime Recommendations';
		$data['css'] = 'anime_recommendations.css';
		
		$this->load->view('anime_recommendations', $data);
	}

}
?>