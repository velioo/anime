<?php
Class Search_model extends CI_Model {

	function __construct() {
		parent::__construct();

	}
	
	function get_latest_anime() {
		$query = $this->db->query("SELECT id,titles,canonical_title,poster_image_file_name FROM anime ORDER BY start_date DESC LIMIT 28");	
		return $query->result_array();
	}
	
	function search_users($user) {
		$query = $this->db->query("SELECT username, joined_on FROM users WHERE username LIKE '%{$user}%'");
	
		if($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function get_anime_count($anime) {
		$query = $this->db->query("SELECT * FROM anime WHERE titles LIKE '%{$anime}%' or slug LIKE '%{$anime}%'");
		return $query->num_rows();
	}
	
	function search_animes($anime, $limit, $offset, $sort_by, $order) {	
		$query = $this->db->query("SELECT * FROM anime WHERE titles LIKE '%{$anime}%' or slug LIKE '%{$anime}%' ORDER BY $sort_by $order, created_at DESC LIMIT {$limit} OFFSET {$offset}");
		if($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function search_characters($character) {
		$query = $this->db->query("SELECT name, gender, profile_image FROM characters WHERE name LIKE '%{$character}%'");
	
		if($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function search_lists($list) {
		$query = $this->db->query("SELECT name, type FROM user_lists WHERE name LIKE '%{$list}%'");
	
		if($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
}
?>