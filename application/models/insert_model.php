<?php
Class Insert_model extends CI_Model {

	function __construct() {
		parent::__construct();

	}
	
	function insert_studio($studio) {
		$studio = addslashes($studio);
		$this->db->query("INSERT INTO studios(name) VALUES ('{$studio}')");
	}
	
	function insert_genre($genre) {
		$genre = addslashes($genre);
		$this->db->query("INSERT INTO genres(name) VALUES ('{$genre}')");
	}
	
	function insert_anime($id, $values) {
		$query = $this->db->query("SELECT id FROM anidb WHERE id = '{$id}'");
		if($query->num_rows() > 0) {
			return;
		} else {
			$this->db->query("INSERT INTO `anidb` (`id`, `anidbid`, `title`, `type`, `startdate`, `enddate`, `related`, `creators`, 
					`description`, `rating`, `picture`, `categories`, `characters`, `epnos`, `airdates`, `episodetitles`, `unixtime`) VALUES {$values}");
		}
	}
	
	function update_picture($id, $picture_name) {
		$picture_name = $picture_name . ".jpg";
		$this->db->query("UPDATE anidb SET picture = '{$picture_name}' WHERE id = '{$id}'");
	}
	
}

?>