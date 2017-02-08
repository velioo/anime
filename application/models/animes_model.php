<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Animes_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function add_anime($anime_object) {
       	$new_anime_data = array (
       			'id' => $anime_object->data->id,
       			'slug' => $anime_object->data->attributes->slug,
       			'age_rating' => $anime_object->data->attributes->ageRating,
       			'episode_count' => $anime_object->data->attributes->episodeCount,
       			'episode_length' => $anime_object->data->attributes->episodeLength,
       			'synopsis' => $anime_object->data->attributes->synopsis,
       			'age_rating_guide' => $anime_object->data->attributes->ageRatingGuide,
       			'show_type' => $anime_object->data->attributes->showType,
       			'start_date' => $anime_object->data->attributes->startDate,
       			'end_date' => $anime_object->data->attributes->endDate,
       			'titles' => $anime_object->data->attributes->titles,
       			'youtube_video_id' => $anime_object->data->attributes->youtubeVideoId,    			      					
				'poster_image_file_name' => $anime_object->data->attributes->posterImage,
				'cover_image_file_name' => $anime_object->data->attributes->coverImage,
       			'cover_image_top_offset' => $anime_object->data->attributes->coverImageTopOffset
		);   
       	
		$query = $this->db->insert('animes', $new_anime_data);
		
		if($query) {
			foreach($anime_object->data->genres as $genre) {	
				$query = $this->db->get_where('genres', array('id' => $genre));
				if($query->num_rows() == 1) {
					$this->db->insert('anime_genres', array('anime_id' => $anime_object->data->id, 'genre_id' => $genre));	
				}
			}
	
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_anime($anime_object) {		
		 $update_anime_data = array (
				'slug' => $anime_object->data->attributes->slug,
				'age_rating' => $anime_object->data->attributes->ageRating,
				'episode_count' => $anime_object->data->attributes->episodeCount,
				'episode_length' => $anime_object->data->attributes->episodeLength,
				'synopsis' => $anime_object->data->attributes->synopsis,
				'age_rating_guide' => $anime_object->data->attributes->ageRatingGuide,
				'show_type' => $anime_object->data->attributes->showType,
				'start_date' => $anime_object->data->attributes->startDate,
				'end_date' => $anime_object->data->attributes->endDate,
				'titles' => $anime_object->data->attributes->titles,
		 		'youtube_video_id' => $anime_object->data->attributes->youtubeVideoId,
		 		'cover_image_top_offset' => $anime_object->data->attributes->coverImageTopOffset
		); 
	
		$this->db->select('poster_image_file_name, cover_image_file_name');
		$this->db->where('id', $anime_object->data->id);
		$query = $this->db->get('animes');
		if($query->num_rows() == 1) {
			if((strpos($query->row_array()['poster_image_file_name'], 'manual_') === false)) {
				$update_anime_data['poster_image_file_name'] = $anime_object->data->attributes->posterImage;
				$date = date('Y-m-d H:i:s');
				$update_anime_data['poster_image_updated_at'] = $date;
			}
			if((strpos($query->row_array()['cover_image_file_name'], 'manual_') === false)) {
				$update_anime_data['cover_image_file_name'] = $anime_object->data->attributes->coverImage;
				$date = date('Y-m-d H:i:s');
				$update_anime_data['cover_image_updated_at'] = $date;
			}
		}

		$this->db->where('id', $anime_object->data->id);
		$query = $this->db->update('animes', $update_anime_data);
		
		if($query) {
			 $this->db->delete('anime_genres', array('anime_id' => $anime_object->data->id));
			 foreach($anime_object->data->genres as $genre) {
			 	$query = $this->db->get_where('genres', array('id' => $genre));
			 	if($query->num_rows() == 1) {
			 		$this->db->insert('anime_genres', array('anime_id' => $anime_object->data->id, 'genre_id' => $genre));
			 	}
			}	  
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function get_anime($id) {
		$query = $this->db->get_where('animes', array('id' => $id));
		
		if($query->num_rows() == 1) {
			$row_array = $this->add_anime_genre($id, $query->row_array());
			return $row_array;
		} else {
			return FALSE;
		}
	}
	
	function get_anime_id($slug) {
		$this->db->select('id');
		$this->db->where('slug', $slug);
		$query = $this->db->get('animes');

		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}		
	}
	
	function get_anime_slug($id) {
		$this->db->select('slug');
		$this->db->where('id', $id);
		$query = $this->db->get('animes');
		
		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_anime_id_by_title($anime_title_en, $anime_title_en_jp, $anime_title_ja_jp) {
		$this->db->select('id, titles');
		$this->db->like('titles', $anime_title_en);
		$this->db->or_like('titles', $anime_title_en_jp);
		$this->db->or_like('titles', $anime_title_ja_jp);
		$query = $this->db->get('animes');
		if($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function get_anime_cover_image($id) {
		$this->db->select('cover_image_file_name');
		$this->db->where('id', $id);
		$query = $this->db->get('animes');

		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_anime_poster_image($id) {
		$this->db->select('poster_image_file_name');
		$this->db->where('id', $id);
		$query = $this->db->get('animes');

		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function update_cover_offset($id, $offset) {
		$this->db->where('id', $id);
		$query = $this->db->update('animes', array('cover_image_top_offset' => $offset));
		return $query;
	}
	
	function update_cover_image($id, $image) {
		$date = date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		$query = $this->db->update('animes', array('cover_image_file_name' => $image, 'cover_image_updated_at' => $date));
		return $query;
	}
	
	function update_poster_image($id, $image) {
		$date = date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		$query = $this->db->update('animes', array('poster_image_file_name' => $image, 'poster_image_updated_at' => $date));
		return $query;
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
		$this->db->select('id, slug, titles, poster_image_file_name');
		$query = $this->db->get('animes');
		return $query->result_array();
	}
	
	function check_if_anime_exists($id) {		
		$query = $this->db->get_where('animes', array('id' => $id));
		
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}
?>