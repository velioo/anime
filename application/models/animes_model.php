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

}
?>