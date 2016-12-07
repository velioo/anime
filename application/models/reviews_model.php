<?php

Class Reviews_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function add_review($anime_id, $user_review, $user_scores) {
		
		$user_id = $this->session->userdata('id');
		
		$exists = $this->check_if_review_exists($anime_id, $user_id);
		
		if($exists) {
			return "exists";
		} else {	
			$review = array(
				'anime_id' => $anime_id,
				'user_id' => $user_id,
				'story' => $user_scores[0],
				'animation' => $user_scores[1],
				'sound' => $user_scores[2],
				'characters' => $user_scores[3],
				'enjoyment' => $user_scores[4], 
				'overall' => $user_scores[5],
				'review_text' => $user_review,
			);
			
			$query = $this->db->insert('reviews', $review);
			
			return $query;
		}

	}
	
	function update_review($anime_id, $user_review, $user_scores) {
		$user_id = $this->session->userdata('id');
		
		$review = array(
				'story' => $user_scores[0],
				'animation' => $user_scores[1],
				'sound' => $user_scores[2],
				'characters' => $user_scores[3],
				'enjoyment' => $user_scores[4],
				'overall' => $user_scores[5],
				'review_text' => $user_review,
		);
		
		$query = $this->db->query("SELECT user_id FROM reviews WHERE user_id = {$user_id} and anime_id = {$anime_id}");
		
		if($query->num_rows() > 0) {
			$this->db->where('anime_id', $anime_id);
			$this->db->where('user_id', $user_id);
			$query = $this->db->update('reviews', $review);		
			if($query) {
				return "updated";
			} else {
				return FALSE;
			}
		} else {
			$this->add_review($anime_id, $user_review, $user_scores);
		}
	}
	
	function check_if_review_exists($anime_id, $user_id) {
		
		$query = $this->db->query("SELECT reviews.user_id FROM reviews JOIN animes ON animes.id=reviews.anime_id 
																JOIN users ON users.id=reviews.user_id WHERE reviews.anime_id = {$anime_id} and reviews.user_id = {$user_id}");
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function get_anime_reviews($anime_id, $limit = 3, $offset = 0) {
		
		$limit_offset = "LIMIT ". $limit .  " OFFSET " . $offset;	
		
		$query = $this->db->query("SELECT reviews.user_id, reviews.story,reviews.animation,reviews.sound,reviews.characters,
								   reviews.enjoyment,reviews.overall,reviews.review_text,reviews.created_at,reviews.updated_at,users.username,users.profile_image,animes.slug FROM reviews 
									JOIN animes ON animes.id=reviews.anime_id
									JOIN users ON users.id=reviews.user_id WHERE reviews.anime_id = {$anime_id} ORDER BY reviews.updated_at DESC {$limit_offset}");
		if($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function get_total_reviews_count($anime_id) {
		$query = $this->db->query("SELECT COUNT(1) as count FROM reviews WHERE anime_id = {$anime_id}");
		
		if($query) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_total_reviews_count_user($user_id) {
		$query = $this->db->query("SELECT COUNT(1) as count FROM reviews WHERE user_id = {$user_id}");
	
		if($query) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_user_review($anime_id = 0, $user_id = 0, $limit = 0, $offset = 0) {
		
		if($anime_id != 0) {
			$search_with_anime_id = "reviews.anime_id = {$anime_id} and";
		} else {
			$search_with_anime_id = "";
		}
		
		if($user_id == 0) {
			$user_id = $this->session->userdata('id');
			if($user_id == null) {
				return FALSE;
			}
		} 
		
		if($limit != 0 && $offset != 0) {
			$limit_offset = "LIMIT ". $limit .  " OFFSET " . $offset;
		} else {
			$limit_offset = "";
		}
		
		$query = $this->db->query("SELECT reviews.anime_id,reviews.user_id,reviews.story,reviews.animation,reviews.sound,reviews.characters,
								   reviews.enjoyment,reviews.overall,reviews.review_text,reviews.created_at,reviews.updated_at,users.username,users.profile_image,animes.slug,animes.titles FROM reviews JOIN animes ON animes.id=reviews.anime_id 
																		JOIN users ON users.id=reviews.user_id WHERE {$search_with_anime_id} reviews.user_id = {$user_id} {$limit_offset}");
		
		if($query->num_rows() == 1) {
			return $query->row_array();
		} else if($query->num_rows() > 0){
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function delete_review($anime_id, $user_id) {
		$query = $this->db->query("DELETE FROM reviews USING reviews JOIN animes ON animes.id=reviews.anime_id 
														JOIN users ON users.id=reviews.user_id WHERE reviews.anime_id = {$anime_id} and reviews.user_id = {$user_id}");
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
}
?>