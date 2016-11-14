<?php
Class Animes_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function get_anime($id) {
		$query = $this->db->query("SELECT * FROM animes WHERE id = {$id}");
		
		if($query->num_rows() == 1) {
			return $query->row_array();
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

}
?>