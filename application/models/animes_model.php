<?php
Class Animes_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function get_anime($id) {
		$query = $this->db->query("SELECT * FROM animes WHERE id = {$id}");
		
		if($query->num_rows() == 1) {
			$row_array = $this->add_anime_genre($id, $query->row_array());
			return $row_array;
		} else {
			return FALSE;
		}
	}
	
	function update_cover_offset($id, $offset) {
		$query = $this->db->query("UPDATE animes SET cover_image_top_offset = '{$offset}' WHERE id = {$id}");	
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_cover_image($id, $image) {
		$query = $this->db->query("UPDATE animes SET cover_image_file_name = '{$image}' WHERE id = {$id}");	
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_poster_image($id, $image) {
		$query = $this->db->query("UPDATE animes SET poster_image_file_name = '{$image}' WHERE id = {$id}");
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function add_anime_genre($id, $row_array) {
		$genres = $this->db->query("SELECT g.name as genre FROM genres g JOIN anime_genres ag ON g.id = ag.genre_id
				JOIN animes a ON a.id = ag.anime_id WHERE a.id = {$id}");
		
		foreach($genres->result_array() as $genre) {
			$row_array['genres'][] = $genre['genre'];
		}
		
		return $row_array;
	}
	
	function get_anime_json_data() {
		$query = $this->db->query("SELECT titles, id, poster_image_file_name, canonical_title FROM animes");
		return $query->result_array();
	}

}
?>