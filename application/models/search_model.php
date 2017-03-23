<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Search_model extends CI_Model {

	function __construct() {
		parent::__construct();

	}
	
	function get_latest_anime() {
		$query = $this->db->query("SELECT id,slug,titles,poster_image_file_name FROM animes WHERE start_date <= CURDATE() ORDER BY start_date DESC LIMIT 28");	
		return $query->result_array();
	}
	
	function search_users($username, $limit, $offset, $sort, $all=FALSE) {
		$user = addslashes($username);
		$this->db->select('u.id, u.username, u.joined_on, u.profile_image, COUNT(w.id) as anime_count');
		$this->db->join('watchlists as w', 'w.user_id=u.id', 'left');
		$this->db->like('username', $username);
		$this->db->group_by('u.id');
		if($all === FALSE) {
			$this->db->order_by($sort[0], $sort[1]);
			$this->db->order_by("username", "ASC");
			$this->db->limit($limit, $offset);
		}
		$users = $this->db->get('users as u');
		
		if($users) {
			if($all === FALSE) {	
				return $users->result_array();
			} else {
				return $users->num_rows();
			}
		} else {
			return FALSE;
		}
	}
	
	function get_animes_count($anime, $limit, $offset, $sort_by, $order, $filters) {		
		$result_array = $this->query_animes_search($anime, $limit, $offset, $sort_by, $order, $filters, FALSE, TRUE);		
		if($result_array != FALSE) {
			return count($result_array);
		} else {
			return FALSE;
		}
	}
	
	function search_animes($anime, $limit, $offset, $sort_by, $order, $filters, $user_sorted_results=FALSE) {
		$result_array = $this->query_animes_search($anime, $limit, $offset, $sort_by, $order, $filters, $user_sorted_results, FALSE);
		if($result_array != FALSE) {
			return $result_array;
		} else {
			return FALSE;
		}
	}
	
	function query_animes_search($anime, $limit, $offset, $sort_by, $order, $filters, $user_sorted_results=FALSE, $all) {
		$anime = trim($anime);			
		$anime =  addslashes($anime);
		
		if($all === TRUE) {
			$limit_offset = "";
		} else {
			$limit_offset = "LIMIT ". $limit .  " OFFSET " . $offset;
		}		
		
		$split_anime = explode(" ", $anime);
		
		$anime = "";
		foreach ($split_anime as $a) {
			$a = preg_replace("/[^\w]+/u", " ", $a);
			$a = trim($a);
			$anime.=$a . " ";
		}
		
		$anime = trim($anime);	
		
		$having_statement = "";
		
		if(count($filters['ratings']) > 0) {
			$having_statement.="HAVING ";
			if(isset($filters['ratings']['greater'])) {
				$having_statement.="(average_rating/2) >= {$filters['ratings']['greater']} AND ";
			}	
			if(isset($filters['ratings']['less'])) {
				$having_statement.="(average_rating/2) <= {$filters['ratings']['less']} AND ";
			}
		}
		
		if(count($filters['genres']) > 0) {
			if($having_statement == "") {
				$having_statement.="HAVING ";
			}
			foreach($filters['genres'] as $genre) {
				$having_statement.="genres LIKE '%{$genre}%' AND ";
			}
		}
		
		if($filters['type'] !== NULL) {
			if($having_statement == "") {
				$having_statement.="HAVING ";
			}
			$having_statement.="show_type = {$filters['type']} AND ";
		}
		
		if(count($filters['episodes']) > 0) {
			if($having_statement == "") {
				$having_statement.="HAVING ";
			}
			if(isset($filters['episodes']['min'])) {
				$having_statement.="episode_count >= {$filters['episodes']['min']} AND ";
			}	
			if(isset($filters['episodes']['max'])) {
				$having_statement.="episode_count <= {$filters['episodes']['max']} AND ";
			}
		}
		
		if(count($filters['year']) > 0) {
			if($having_statement == "") {
				$having_statement.="HAVING ";
			}
			if(isset($filters['year']['min'])) {
				$having_statement.="YEAR(`start_date`) >= {$filters['year']['min']} AND ";
			}
			if(isset($filters['year']['max'])) {
				$having_statement.="YEAR(`start_date`) <= {$filters['year']['max']} AND ";
			}
		}
		
		$having_statement = substr($having_statement, 0, (strlen($having_statement) - 4));		
		
		if($anime != "") {			
			
			if($user_sorted_results) { // if user sorted results sort by $sort_by
				$order_by_rnk = "";
			} else {
				$order_by_rnk = "rnk ASC,";
			}
			
			$query = array();			
 			$query = $this->db->query("SELECT DISTINCT id,slug,episode_count,episode_length,synopsis,average_rating,
					total_votes,age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,created_at,genres FROM
					(
						SELECT 1 AS rnk, animes.id,animes.slug,episode_count,episode_length,synopsis,average_rating,
							total_votes,age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,created_at, 
 							group_concat(g.name) as genres FROM animes
		 					JOIN anime_genres as ag ON ag.anime_id = animes.id
		 					JOIN genres as g ON g.id = ag.genre_id
						WHERE animes.slug LIKE '{$anime}%' GROUP BY animes.id {$having_statement}
						UNION
						SELECT 2 AS rnk, animes.id,animes.slug,episode_count,episode_length,synopsis,average_rating,
							total_votes,age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,created_at,
							group_concat(g.name) as genres FROM animes
							JOIN anime_genres as ag ON ag.anime_id = animes.id
		 					JOIN genres as g ON g.id = ag.genre_id
						WHERE titles LIKE '%{$anime}%' GROUP BY animes.id {$having_statement}
						UNION
						SELECT 3 AS rnk, animes.id,animes.slug,episode_count,episode_length,synopsis,average_rating,
							total_votes,age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,created_at,
							group_concat(g.name) as genres FROM animes 
							JOIN anime_genres as ag ON ag.anime_id = animes.id
		 					JOIN genres as g ON g.id = ag.genre_id
							WHERE MATCH(animes.slug) AGAINST('{$anime}' IN BOOLEAN MODE) GROUP BY animes.id	{$having_statement}		
					) tab
					ORDER BY {$order_by_rnk} {$sort_by} {$order} {$limit_offset}");  

			
			$result_array = $query->result_array();
	
		} else {
			$query = $this->db->query("SELECT animes.id,animes.slug,episode_count,episode_length,synopsis,average_rating,
					total_votes,age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,created_at,group_concat(g.name) as genres
					FROM animes 
					JOIN anime_genres as ag ON ag.anime_id = animes.id
 					JOIN genres as g ON g.id = ag.genre_id
					GROUP BY animes.id {$having_statement} ORDER BY {$sort_by} $order, slug ASC {$limit_offset}");
			$result_array = $query->result_array();
			
		}
		
		if(count($result_array) > 0) {
			return $result_array;
		} else {		
			$like_statement = "";
			foreach ($split_anime as $a) {
				$like_statement.=" or animes.slug LIKE '{$a}%'";
			}
			$like_statement = trim($like_statement);
			$like_statement = substr($like_statement, 3, (strlen($like_statement) - 1));
			
			$query = $this->db->query("SELECT animes.id,animes.slug,episode_count,episode_length,synopsis,average_rating,
						total_votes,age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,created_at,group_concat(g.name) as genres
					FROM animes 
					JOIN anime_genres as ag ON ag.anime_id = animes.id
 					JOIN genres as g ON g.id = ag.genre_id
					WHERE {$like_statement} GROUP BY animes.id {$having_statement} {$limit_offset}");
			
			if($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				$like_statement = "";
				foreach ($split_anime as $a) {
					$like_statement.=" or animes.slug LIKE '%{$a}%'";
				}
				$like_statement = trim($like_statement);
				$like_statement = substr($like_statement, 3, (strlen($like_statement) - 1));
					
				$query = $this->db->query("SELECT animes.id,animes.slug,episode_count,episode_length,synopsis,average_rating,
							total_votes,age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,created_at,group_concat(g.name) as genres
						FROM animes 
						JOIN anime_genres as ag ON ag.anime_id = animes.id
 						JOIN genres as g ON g.id = ag.genre_id
						WHERE {$like_statement} GROUP BY animes.id {$having_statement} {$limit_offset}");
				if($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return FALSE;
				}
			}
		}
	}
	
	function search_characters($character, $limit, $offset) {
		$result_array = $this->query_characters_search($character, $limit, $offset, FALSE);
		if($result_array !== FALSE) {
			return $result_array;
		} else {
			return FALSE;
		}
	}
	
	function get_characters_count($character, $limit, $offset) {
		$result_array = $this->query_characters_search($character, $limit, $offset, TRUE);	
		if($result_array !== FALSE) {
			return count($result_array);
		} else {
			return FALSE;
		}
	}
	
	function search_actors($actor, $limit, $offset) {
		$result_array = $this->query_actors_search($actor, $limit, $offset, FALSE);		
		if($result_array !== FALSE) {
			return $result_array;
		} else {
			return FALSE;
		}
	}
	
	function get_actors_count($actor, $limit, $offset) {
		$result_array = $this->query_actors_search($actor, $limit, $offset, TRUE);	
		if($result_array !== FALSE) {
			return count($result_array);
		} else {
			return FALSE;
		}
	}
	
	function query_characters_search($character, $limit, $offset, $all) {
		$character = trim($character);
		$character = addslashes($character);
	
		if($all === TRUE) {
			$limit_offset = "";
		} else {
			$limit_offset = "LIMIT ". $limit .  " OFFSET " . $offset;
		}
	
		if($character != "") {
	
			$query = array();
			
			$query = $this->db->query("SELECT id,first_name,last_name,alt_name,image_file_name,created_at
							FROM characters
							WHERE MATCH(first_name,last_name,alt_name) 
							      AGAINST('{$character}' IN BOOLEAN MODE) {$limit_offset}");
						
			$result_array = $query->result_array();
			
			if($all !== TRUE) {			
				$result_array = $this->add_characters_related_animes($result_array);
				if($this->session->userdata('is_logged_in')) {
					$result_array = $this->add_character_user_status($result_array);
				}
			}
				
		} else {
			if($all === TRUE) {
				$query = $this->db->get('characters');
				$result_array = $query->result_array();
			} else {					
				$query = $this->db->query("SELECT id,first_name,last_name,alt_name,image_file_name,created_at FROM characters {$limit_offset}");
				$result_array = $query->result_array();
				$result_array = $this->add_characters_related_animes($result_array);
				if($this->session->userdata('is_logged_in')) {
					$result_array = $this->add_character_user_status($result_array);
				}
			}			
		}
	
		if(count($result_array) > 0) {
			return $result_array;
		} else {
			$query = $this->db->query("SELECT id,first_name,last_name,alt_name,image_file_name,created_at FROM characters
				WHERE first_name LIKE '%{$character}%' or last_name LIKE '%{$character}%' {$limit_offset}");
			
			$result_array = $query->result_array();
			
			if($all !== TRUE) {
				$result_array = $this->add_characters_related_animes($result_array);
				if($this->session->userdata('is_logged_in')) {
					$result_array = $this->add_character_user_status($result_array);
				}
			}
			
			return $result_array;
			
		}
	}
	
	function query_actors_search($actor, $limit, $offset, $all) {
		$actor = trim($actor);
		$actor = addslashes($actor);
	
		if($all === TRUE) {
			$limit_offset = "";
		} else {
			$limit_offset = "LIMIT ". $limit .  " OFFSET " . $offset;
		}
	
		if($actor != "") {
	
			$query = array();
	
			$query = $this->db->query("SELECT id,first_name,last_name,image_file_name,language,created_at
					FROM actors
					WHERE MATCH(first_name,last_name, first_name_japanese, last_name_japanese)
					AGAINST('{$actor}' IN BOOLEAN MODE) {$limit_offset}");
	
			$result_array = $query->result_array();
			
			if($all !== TRUE) {
				if($this->session->userdata('is_logged_in')) {
					$result_array = $this->add_actor_user_status($result_array);
				}
			}
	
		} else {
			if($all === TRUE) {
				$query = $this->db->get('actors');
				$result_array = $query->result_array();
			} else {
				$query = $this->db->query("SELECT id,first_name,last_name,image_file_name,language,created_at FROM actors {$limit_offset}");
				$result_array = $query->result_array();
				
				if($this->session->userdata('is_logged_in')) {
					$result_array = $this->add_actor_user_status($result_array);
				}
			}
		}
	
		if(count($result_array) > 0) {
			return $result_array;
		} else {
			$query = $this->db->query("SELECT id,first_name,last_name,image_file_name,language,created_at FROM actors
					WHERE first_name LIKE '%{$actor}%' or last_name LIKE '%{$actor}%' {$limit_offset}");
	
			$result_array = $query->result_array();
			
			if($all !== TRUE) {
				if($this->session->userdata('is_logged_in')) {
					$result_array = $this->add_actor_user_status($result_array);
				}
			}
	
			return $result_array;
	
		}
	}
	
	function add_characters_related_animes($result_array) {
		$character_ids = array();
		
		foreach($result_array as $character) {
			$character_ids[] = $character['id'];
		}
		
		$this->db->select('c.id as character_id, a.id as id, a.slug as slug, a.titles as titles');
		$this->db->join('character_animes as ca', 'ca.character_id = c.id');
		$this->db->join('animes as a', 'a.id = ca.anime_id');
		$this->db->where_in('c.id', $character_ids);
		$animes = $this->db->get('characters as c');
		
		$animes = $animes->result_array();		
		
		for($i = 0; $i < count($result_array); $i++) { // add animes to the according characters		
			foreach($animes as $anime) {
				if($anime['character_id'] == $result_array[$i]['id']) {
					$result_array[$i]['animes'][] = $anime;
				} 
			}			
		}

		return $result_array;		
	}
	
	function add_character_user_status($characters) {
		$user_id = $this->session->userdata('id');
		for($i = 0; $i < count($characters); $i++) {
			$this->db->select('status');
			$this->db->where('character_id', $characters[$i]['id']);
			$this->db->where('user_id', $user_id);
			$status = $this->db->get('characters_users_status');
			if($status->num_rows() == 1)  {
				$characters[$i]['character_user_status'] = $status->row_array()['status'];
			}
		}
		return $characters;
	}
	
	function add_actor_user_status($actors) {
		$user_id = $this->session->userdata('id');
		for($i = 0; $i < count($actors); $i++) {
			$this->db->select('status');
			$this->db->where('actor_id', $actors[$i]['id']);
			$this->db->where('user_id', $user_id);
			$status = $this->db->get('actors_users_status');
			if($status->num_rows() == 1)  {
				$actors[$i]['actor_user_status'] = $status->row_array()['status'];
			}
		}
		return $actors;
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