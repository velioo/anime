<?php
Class Search_model extends CI_Model {

	function __construct() {
		parent::__construct();

	}
	
	function get_latest_anime() {
		$query = $this->db->query("SELECT id,titles,canonical_title,poster_image_file_name FROM animes WHERE start_date <= CURDATE() ORDER BY start_date DESC LIMIT 28");	
		return $query->result_array();
	}
	
	function search_users($user) {
		$user = addslashes($user);
		$query = $this->db->query("SELECT username, joined_on FROM users WHERE username LIKE '%{$user}%'");
	
		if($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function get_animes_count($anime, $limit, $offset, $sort_by, $order) {
		
		$result_array = $this->query_search($anime, $limit, $offset, $sort_by, $order, TRUE);
		
		if($result_array != FALSE) {
			return count($result_array);
		} else {
			return FALSE;
		}
	}
	
	function search_animes($anime, $limit, $offset, $sort_by, $order, $user_sorted_results) {
		$result_array = $this->query_search($anime, $limit, $offset, $sort_by, $order, FALSE, $user_sorted_results);
		if($result_array != FALSE) {
			return $result_array;
		} else {
			return FALSE;
		}
	}
	
	function query_search($anime, $limit, $offset, $sort_by, $order, $all, $user_sorted_results=FALSE) {
		$anime = trim($anime);			
		$anime =  addslashes($anime);
		
		if($all == TRUE) {
			$limit_offset = "";
		} else {
			$limit_offset = "LIMIT ". $limit .  " OFFSET " . $offset;
		}		
		
		$split_anime = explode(" ", $anime);
		$anime = "";
		foreach ($split_anime as $a) {
			$a = preg_replace("/[^\w]+/", " ", $a);
			$a = trim($a);
			$anime.=$a . " ";
		}
		
		$anime = trim($anime);	
		
		if($user_sorted_results) {
			$order_by_rnk = "";
		} else {
			$order_by_rnk = "rnk ASC,";
		}
		
		
		if($anime != "") {		
 			
			$query = array();
			
 			$query = $this->db->query("SELECT DISTINCT id,slug,episode_count,episode_length,synopsis,average_rating,
					age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title,created_at FROM
			(
				SELECT 1 AS rnk, id,slug,episode_count,episode_length,synopsis,average_rating,
					age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title,created_at FROM animes
				WHERE slug LIKE '{$anime}%' 
				UNION
				SELECT 2 AS rnk, id,slug,episode_count,episode_length,synopsis,average_rating,
				age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title,created_at FROM animes
				WHERE titles LIKE '%{$anime}%'	
				UNION
				SELECT 3 AS rnk, id,slug,episode_count,episode_length,synopsis,average_rating,
				age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title,created_at FROM animes
				WHERE synopsis LIKE '%{$anime}%'
			
			) tab
					ORDER BY {$order_by_rnk} {$sort_by} $order {$limit_offset}");  

			
			$result_array = $query->result_array();
			
			$result_array = $this->add_anime_genres_type($result_array);
		
			
/* 			UNION
			SELECT 3 AS rnk, id,slug,episode_count,episode_length,synopsis,average_rating,
			age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title,created_at FROM animes
			WHERE synopsis LIKE '%{$anime}%' */
			
/* 			$query1 = $this->db->query("SELECT id,slug,episode_count,episode_length,average_rating,
					age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title
					FROM animes WHERE slug LIKE '{$anime}%' {$limit_offset}" );
			
			echo $query1->num_rows() ."<br/>";
			
			$query2 = $this->db->query("SELECT id,slug,episode_count,episode_length,average_rating,
					age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title
					FROM animes WHERE titles LIKE '%{$anime}%' ORDER BY {$sort_by} $order, created_at DESC {$limit_offset}");
			
			echo $query2->num_rows() ."<br/>";
			
			$query3 = $this->db->query("SELECT id,slug,episode_count,episode_length,average_rating,
					age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title
					FROM animes WHERE synopsis LIKE '%{$anime}%' ORDER BY {$sort_by} $order, created_at DESC {$limit_offset}");
			
			echo $query3->num_rows() ."<br/>";
			
			$query = $query1->result_array();
			$query = $query + $query2->result_array();
			$query = $query + $query3->result_array();
			
			$result_array = array();
			
			foreach($query as $k=>$v){
				if(!in_array($v, $result_array)){
					$result_array[]=$v;
				}
			}
			
			echo count($result_array) . "<br/>"; */
			
/* 			if($query->num_rows() < 0) { 
			$query = $this->db->query("SELECT id,slug,episode_count,episode_length,average_rating,
					age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title
					FROM animes WHERE MATCH(slug) AGAINST('{$words}' IN BOOLEAN MODE)  {$limit_offset}");		
			}
			if($query->num_rows() < 0) {
				$query = $this->db->query("SELECT id,slug,episode_count,episode_length,average_rating,
						age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title
						FROM animes WHERE MATCH(titles) AGAINST('{$words}' IN BOOLEAN MODE) {$limit_offset}");
			}		
			
			if($query->num_rows() <= 0) {
				$query = $this->db->query("SELECT id,slug,episode_count,episode_length,average_rating,
						age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title
						FROM animes WHERE MATCH(synopsis) AGAINST('{$words}' IN BOOLEAN MODE) {$limit_offset}");
			} */

		} else {
			$query = $this->db->query("SELECT id,slug,episode_count,episode_length,synopsis,average_rating,
					age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title FROM animes ORDER BY {$sort_by} $order, slug ASC {$limit_offset}");
			$result_array = $query->result_array();
					
			if($all != TRUE) {
				$result_array = $this->add_anime_genres_type($result_array);
			}
		}
		
		if(count($result_array) > 0) {
			return $result_array;
		} else {
			
			$like_statement = "";
			foreach ($split_anime as $a) {
				$like_statement.=" or slug LIKE '{$a}%'";
			}
			$like_statement = trim($like_statement);
			$like_statement = substr($like_statement, 3, (strlen($like_statement) - 1));
			
			$query = $this->db->query("SELECT id,slug,episode_count,episode_length,average_rating,
					age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title
					FROM animes WHERE {$like_statement} {$limit_offset}");
			
			if($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				$like_statement = "";
				foreach ($split_anime as $a) {
					$like_statement.=" or slug LIKE '%{$a}%'";
				}
				$like_statement = trim($like_statement);
				$like_statement = substr($like_statement, 3, (strlen($like_statement) - 1));
					
				$query = $this->db->query("SELECT id,slug,episode_count,episode_length,average_rating,
						age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,canonical_title
						FROM animes WHERE {$like_statement} {$limit_offset}");
				if($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return FALSE;
				}
			}
		}
	}
	
	function add_anime_genres_type($result_array) {
		$anime_ids = array();
		
		foreach($result_array as $anime) {
			$anime_ids[] = $anime['id'];
		}
		
		$ids = join("','",$anime_ids);
		
		$genres = $this->db->query("SELECT a.id as anime_id, g.name as genre FROM genres g JOIN anime_genres ag ON g.id = ag.genre_id
				JOIN animes a ON a.id = ag.anime_id WHERE a.id IN ('{$ids}')");
		
		$genres = $genres->result_array();		
		
		for($i = 0; $i < count($result_array); $i++) {
			
			foreach($genres as $genre) {
				if($genre['anime_id'] == $result_array[$i]['id']) {
					$result_array[$i]['genres'][] = $genre['genre'];
				} 
			}			
		}			
		return $result_array;
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