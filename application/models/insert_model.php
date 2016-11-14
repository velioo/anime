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
		$this->db->query("INSERT INTO genres(name, slug, description) VALUES ('{$genre}')");
	}
	
	function insert_anime($id, $values) {
		$query = $this->db->query("SELECT id FROM animes WHERE id = {$id}");
		if($query->num_rows() > 0) {
			return;
		} else {
			$this->db->query("INSERT INTO `animes` (`id`, `slug`, `age_rating`, `episode_count`, `episode_length`, `synopsis`, `youtube_video_id`, 
					`cover_image_file_name`, `age_rating_guide`, `show_type`, `start_date`, `end_date`, `poster_image_file_name`, `cover_image_top_offset`,
					`titles`, `canonical_title`, `abbreviated_titles`) VALUES ({$values})");
			echo $values;
		}
	}
	
	function update_picture($id, $picture_name) {
		$picture_name = $picture_name . ".jpg";
		$this->db->query("UPDATE animes SET poster_image_file_name = '{$picture_name}' WHERE id = '{$id}'");
	}
	
	function update_synopsis($id, $synopsis) {
		$this->db->query("UPDATE animes SET synopsis = '{$synopsis}' WHERE id = {$id}");
	}
	
	function check_if_anime_exists($id) {
		$query = $this->db->query("SELECT id FROM animes WHERE id = {$id}");
		
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
}

?>