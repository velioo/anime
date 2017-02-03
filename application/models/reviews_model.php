<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Reviews_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function add_review($anime_id, $user_review, $user_scores) {
		
		$user_id = $this->session->userdata('id');
		
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
		
		$this->db->where('anime_id', $anime_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->update('reviews', $review);		
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function check_if_review_exists($anime_id, $user_id) {
		
		$query = $this->db->get_where('reviews', array('anime_id' => $anime_id, 'user_id' => $user_id));

		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function get_anime_reviews($anime_id, $limit = 3, $offset = 0) {
		
		$this->db->select('r.user_id, r.story,r.animation,r.sound,r.characters,
								   r.enjoyment,r.overall,r.review_text,r.created_at,r.updated_at,u.username,u.profile_image,a.slug');
		$this->db->join('animes as a', 'a.id=r.anime_id');
		$this->db->join('users as u', 'u.id=r.user_id');
		$this->db->where('r.anime_id', $anime_id);
		$this->db->order_by('r.updated_at', 'DESC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get('reviews as r');
		
		if($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function get_total_reviews_count($anime_id) {
		$query = $this->db->get_where('reviews', array('anime_id' => $anime_id));
		
		if($query) {
			return $query->num_rows();
		} else {
			return FALSE;
		}
	}
	
	function get_total_reviews_count_user($user_id) {
		$query = $this->db->get_where('reviews', array('user_id' => $user_id));
	
		if($query) {
			return $query->num_rows();
		} else {
			return FALSE;
		}
	}
	
	function get_user_review($anime_id = 0, $user_id = 0) {	
		$this->db->select('r.anime_id,r.user_id,r.story,r.animation,r.sound,r.characters,
								   r.enjoyment,r.overall,r.review_text,r.created_at,r.updated_at,u.username,u.profile_image,a.slug,a.titles');
		$this->db->join('animes as a', 'a.id=r.anime_id');
		$this->db->join('users as u', 'u.id=r.user_id');
		$this->db->where('r.user_id', $user_id);
		$this->db->where('r.anime_id', $anime_id);
		$query = $this->db->get('reviews as r');
		
		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_user_reviews($user_id = 0, $limit = 0, $offset = 0) {
		
		$this->db->select('r.anime_id,r.user_id,r.story,r.animation,r.sound,r.characters,
								   r.enjoyment,r.overall,r.review_text,r.created_at,r.updated_at,u.username,u.profile_image,a.slug,a.titles');
		$this->db->join('animes as a', 'a.id=r.anime_id');
		$this->db->join('users as u', 'u.id=r.user_id');
		$this->db->where('r.user_id', $user_id);
		$this->db->order_by('r.created_at', 'DESC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get('reviews as r');
		
	 	if($query->num_rows() > 0){
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function delete_review($anime_id, $user_id) {
		
		$query = $this->db->delete('reviews', array('anime_id' => $anime_id, 'user_id' => $user_id));

		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>