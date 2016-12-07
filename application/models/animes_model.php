<?php
Class Animes_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function add_anime($anime_object, $genres) {
       	$new_anime_data = array (
       		'id' => $anime_object->id,
			'slug' => $anime_object->slug,
			'age_rating' => $anime_object->age_rating,
			'episode_count' => $anime_object->episode_count,
			'episode_length' => $anime_object->episode_length,
			'synopsis' => $anime_object->synopsis,
			'age_rating_guide' => $anime_object->age_rating_guide,
			'show_type' => $anime_object->show_type,
			'start_date' => $anime_object->started_airing,
			'end_date' => $anime_object->finished_airing,
			'poster_image_file_name' => $anime_object->cover_image,
			'titles' => $anime_object->titles,
		);   
       	
		$query = $this->db->insert('animes', $new_anime_data);
		
		if($query) {
			foreach($genres as $genre) {
				$genre_query = $this->db->query("SELECT id FROM genres WHERE name = '{$genre}'");
				$genre_id = $genre_query->row_array()['id'];				
				$query = $this->db->query("INSERT INTO anime_genres(anime_id, genre_id) VALUES ({$anime_object->id}, {$genre_id})");		
			}
	
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_anime($anime_object, $genres) {
		 $update_anime_data = array (
				'slug' => $anime_object->slug,
				'age_rating' => $anime_object->age_rating,
				'episode_count' => $anime_object->episode_count,
				'episode_length' => $anime_object->episode_length,
				'synopsis' => $anime_object->synopsis,
				'age_rating_guide' => $anime_object->age_rating_guide,
				'show_type' => $anime_object->show_type,
				'start_date' => $anime_object->started_airing,
				'end_date' => $anime_object->finished_airing,
				'titles' => $anime_object->titles,
		); 
		
		$query = $this->db->query("SELECT poster_image_file_name FROM animes WHERE id = {$anime_object->id}");
		if($query->num_rows() == 1) {
			if((strpos($query->row_array()['poster_image_file_name'], 'manual_') === false)) {
				$update_anime_data['poster_image_file_name'] = $anime_object->cover_image;
			}
		}

		$this->db->where('id', $anime_object->id);
		$query = $this->db->update('animes', $update_anime_data);
		
		if($query) {
			 $this->db->query("DELETE FROM anime_genres WHERE anime_id = {$anime_object->id}");
			 foreach($genres as $genre) {
				$genre_query = $this->db->query("SELECT id FROM genres WHERE name = '{$genre}'");
				$genre_id = $genre_query->row_array()['id'];
				$query = $this->db->query("INSERT INTO anime_genres(anime_id, genre_id) VALUES ({$anime_object->id}, {$genre_id})");
			}	  
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function get_anime($id) {
		
		if(is_numeric($id)) {
		
			$query = $this->db->query("SELECT * FROM animes WHERE id = {$id}");
			
			if($query->num_rows() == 1) {
				$row_array = $this->add_anime_genre($id, $query->row_array());
				return $row_array;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function get_anime_id($slug) {
		$query = $this->db->query("SELECT id FROM animes WHERE slug = '{$slug}' ");
		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
		
	}
	
	function get_anime_slug($id) {
		$query = $this->db->query("SELECT slug FROM animes WHERE id = '{$id}' ");
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
	
	function update_poster_image($id, $image) {
		$query = $this->db->query("UPDATE animes SET poster_image_file_name = '{$image}' WHERE id = {$id}");
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function add_anime_genre($id, $anime_array) {
		$genres = $this->db->query("SELECT g.name as genre FROM genres g JOIN anime_genres ag ON g.id = ag.genre_id
				JOIN animes a ON a.id = ag.anime_id WHERE a.id = {$id}");
		
		foreach($genres->result_array() as $genre) {
			$anime_array['genres'][] = $genre['genre'];
		}
		
		return $anime_array;
	}
	
	function get_anime_json_data() {
		$query = $this->db->query("SELECT id, slug, titles, poster_image_file_name FROM animes");
		return $query->result_array();
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