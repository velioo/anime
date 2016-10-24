<?php
Class Search_model extends CI_Model {

	function __construct() {
		parent::__construct();

	}
	
	function search_users($user) {
		$query = $this->db->query("SELECT username, joined_on FROM users WHERE username LIKE '%{$user}%'");
	
		if($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function search_animes($anime) {
		$query = $this->db->query("SELECT name, episodes, air_date, air_season, rating, info, ranked, score, type, cover_image FROM animes WHERE name LIKE '%{$anime}%'");
	
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