<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Recommendations_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function get_recommended_animes($genres, $anime_ids, $average_year, $limit) {
		
		$average_year = $average_year - 10;
		
		$this->db->select('a.id,a.slug,episode_count,episode_length,synopsis,average_rating,
							total_votes,age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,created_at, 
 							group_concat(g.name) as genres');
		$this->db->join('anime_genres as ag', 'ag.anime_id=a.id');
		$this->db->join('genres as g', 'g.id=ag.genre_id');
		$this->db->where('YEAR(a.start_date) >', $average_year);
		$this->db->where_not_in('a.id', $anime_ids);
		$this->db->group_by('a.id');
		$query = $this->db->get('animes as a');

		$all_animes = $query->result_array();	
		shuffle($all_animes);
		
/* 		$genre_names = array();
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
		
		return $animes;
	}
	
	function get_most_watched_genres() {
		
		$user_id = $this->session->userdata('id');
		$statuses = array(1, 2);
		
		//$this->db->select('g.name, g.id, COUNT(g.id) as count, group_concat(DISTINCT a.id) as anime_ids');
		$this->db->select('group_concat(DISTINCT g.name) as genres');
		$this->db->join('animes as a', 'a.id=w.anime_id');
		$this->db->join('anime_genres as ag', 'ag.anime_id=a.id');
		$this->db->join('genres as g', 'g.id=ag.genre_id');
		$this->db->where('w.user_id', $user_id);
		$this->db->where_in('w.status', $statuses);
		//$this->db->order_by('count', 'DESC');
		$query = $this->db->get('watchlists as w');
		
		if($query->num_rows() > 0) {		
			return $query->row_array()['genres'];
		} else {
			return NULL;
		}
		//return $query->result_array();
	}
	
	function get_excluded_anime_ids() {
		
		$user_id = $this->session->userdata('id');
		$statuses = array(1, 2, 3, 4, 5);
		
		$this->db->simple_query('SET group_concat_max_len = 4096');
		$this->db->select('group_concat(w.anime_id) as anime_ids');
		$this->db->where('w.user_id', $user_id);
		$this->db->where_in('w.status', $statuses);
		$query = $this->db->get('watchlists as w');
		
		return $query->row_array()['anime_ids'];
	}
	
	function get_avarage_watch_year() {
		$user_id = $this->session->userdata('id');
		$statuses = array(1, 2);
		
		$this->db->select('YEAR(a.start_date) as year');
		$this->db->join('animes as a', 'a.id=w.anime_id');
		$this->db->where('w.user_id', $user_id);
		$this->db->where_in('w.status', $statuses);
		$query = $this->db->get('watchlists as w');
		
		if($query->num_rows() > 0) {
		
			$result_array = $query->result_array();
			
			$sum = 0;
			foreach($result_array as $year) {
				$sum = $sum + $year['year'];
			}
			
			$average_year = floor($sum/count($result_array));
		} else {
			return 0;
		}
		
		return $average_year;
	}
	
}

?>