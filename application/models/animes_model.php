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
       	
       	if($anime_object->data->attributes->abbreviatedTitles != NULL) {
       		$new_anime_data['abbreviated_titles'] = implode("___", $anime_object->data->attributes->abbreviatedTitles);
       	}
       	
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
		 		'cover_image_top_offset' => $anime_object->data->attributes->coverImageTopOffset,
		); 
		 
		 if($anime_object->data->attributes->abbreviatedTitles != NULL) {
		 	$update_anime_data['abbreviated_titles'] = implode("___", $anime_object->data->attributes->abbreviatedTitles);
		 }
	
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
	
	function add_studio($studio_object) {
		$studio = array(
			'id' => $studio_object->data->id,
			'slug' => $studio_object->data->attributes->slug,
			'name' => $studio_object->data->attributes->name
		);
		
		$query = $this->db->insert('studios', $studio);
		
		return $query;
	}
	
	function make_anime_studio_relation($anime_id, $studio_id) {
		
		$data = array(
			'anime_id' => $anime_id,
			'studio_id' => $studio_id
		);
		
		$query = $this->db->get_where('rel_anime_studios', $data);
		
		if($query->num_rows() == 0) {
			$query = $this->db->insert('rel_anime_studios', $data);
		}	
		
		return $query;
	}
	
	function get_anime($id) {
		
		$query = $this->db->query("SELECT @rn:=@rn+1 AS rank,id
									FROM (
									SELECT id
									FROM animes
									GROUP BY id
									ORDER BY average_rating DESC, slug ASC									
									) t1, (SELECT @rn:=0) t2");
		$result_array = $query->result_array();		
		$rank = 0;		
		foreach($result_array as $arr) {
			if($arr['id'] == $id) {
				$rank = $arr['rank'];
			}
		}
	
		$query = $this->db->get_where('animes', array('id' => $id));
		
		if($query->num_rows() == 1) {
			$row_array = $this->add_anime_genre($id, $query->row_array());
			$row_array['rank'] = $rank;
			return $row_array;
		} else {
			return FALSE;
		}
	}
	
	function get_all_animes_count() {
		$query = $this->db->get("animes");
		return $query->num_rows();
	}
	
	function get_all_animes($limit, $offset) {
		if($this->session->userdata('is_logged_in')) {
			$user_id = $this->session->userdata('id');		
			
			$query = $this->db->query("
					SELECT @rn:=@rn+1 AS rank,id,slug,titles,average_rating,show_type,start_date,status,score,eps_watched
					FROM (
					SELECT a.id,a.slug,a.titles,a.average_rating,a.show_type,a.start_date,w.status,w.score,w.eps_watched
					FROM animes as a LEFT JOIN watchlists as w ON w.anime_id=a.id and w.user_id={$user_id}
					ORDER BY average_rating DESC, slug ASC LIMIT {$limit} OFFSET {$offset}
					) t1, (SELECT @rn:={$offset}) t2");
		} else {
			$query = $this->db->query("
					SELECT @rn:=@rn+1 AS rank,id,slug,titles,average_rating,show_type,start_date
					FROM (
					SELECT a.id,a.slug,a.titles,a.average_rating,a.show_type,a.start_date
					FROM animes as a
					ORDER BY average_rating DESC, slug ASC LIMIT {$limit} OFFSET {$offset}
					) t1, (SELECT @rn:={$offset}) t2");
		}		
		return $query->result_array();
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
		$this->db->select('id, titles, abbreviated_titles');
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
	
	function get_users_count($anime_id, $status=NULL) {
		
		$this->db->where('anime_id', $anime_id);
		if($status != NULL) {			
			$this->db->where('status', $status);		
		}
		
		$query = $this->db->get('watchlists');
		
		return $query->num_rows();
	}
	
	function get_users_count_grouped_by_status($anime_id) {
		
		$this->db->select("status, COUNT(status) as users_count");
		$this->db->where('anime_id', $anime_id);
		$this->db->group_by('status');	
		$query = $this->db->get('watchlists');
		
		return $query->result_array();
	}
	
	function get_users($anime_id, $status, $offset, $limit, $sort=NULL) {	
		$this->db->select('username, profile_image, eps_watched, score, status_updated_at, status');
		$this->db->where('anime_id', $anime_id);		
		$this->db->where('status', $status);	
		$this->db->join('users as u', 'u.id=w.user_id');
		if($sort != NULL) {
			$this->db->order_by($sort[0], $sort[1]);
			$this->db->order_by("username", "ASC");
		}
		$this->db->limit($limit, $offset);	
		
		$query = $this->db->get('watchlists as w');
		
		return $query->result_array();
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
		$this->db->select('id, slug, titles, abbreviated_titles, poster_image_file_name');
		$query = $this->db->get('animes');
		return $query->result_array();
	}
	
/* 	function calculate_anime_ranks() {
		$this->db->select("id,average_rating");
		$this->db->order_by("average_rating", "DESC");
		$this->db->order_by("slug", "ASC");
		$query = $this->db->get("animes");
	
		$counter = 0;
		foreach($query->result_array() as $row) {
			$counter++;
			$this->db->where("id", $row['id']);
			$this->db->update("animes", array('rank' => $counter));
		}
	} */
	
	function check_if_anime_exists($id) {		
		$query = $this->db->get_where('animes', array('id' => $id));
		
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function check_if_studio_exists($id) {
		$query = $this->db->get_where('studios', array('id' => $id));
		
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}
?>