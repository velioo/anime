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
			$average_year = $this->recommendations_model->get_avarage_watch_year();
			
			$anime_ids = array();
			
			foreach($genres as $genre) {
				$temp = explode(',', $genre['anime_ids']);
				foreach($temp as $t) {
					if(!in_array($t, $anime_ids)) {
						$anime_ids[] = $t;
					}
				}
			}
			
			$limit = 30;
			
			$animes = $this->recommendations_model->get_recommended_animes($genres, $anime_ids, $average_year, $limit);
			$animes = $this->watchlist_model->add_user_statuses($animes);
			
			$data['animes'] = $animes;
		} else {
			
		}
		
		$this->load->view('anime_recommendations', $data);
	}

}
?>