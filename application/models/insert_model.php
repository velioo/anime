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
	
	function insert_anime($anime_info) {		
		$is_unique = $this->db->query("SELECT id FROM animes WHERE name = '{$anime_info['name']}'");
		if($is_unique->num_rows() > 0) {
			return;
		} else {
	 		$this->db->query("INSERT INTO animes(name, episodes, air_date, info, type, source, cover_image)
										  VALUES('{$anime_info['name']}', '{$anime_info['episodes']}', '{$anime_info['air_date']}',
											'{$anime_info['info']}', '{$anime_info['type']}', '{$anime_info['source']}', '{$anime_info['cover_image']}')"); 
		}
		
		$studios = explode(",", $anime_info['studios']);
		$query = $this->db->query("SELECT id FROM animes WHERE name = '{$anime_info['name']}'");
		$anime = $query->row_array(); 

 		foreach ($studios as $studio) {
			$studio = trim($studio);		
			$query = $this->db->query("SELECT id FROM studios WHERE name = '{$studio}'");
			$studio = $query->row_array();
			$is_unique = $this->db->query("SELECT anime_id, studio_id FROM rel_anime_studios WHERE anime_id = '{$anime['id']}' and studio_id = '{$studio['id']}'");
			if($is_unique->num_rows() > 0) {
				
			} else {
				$this->db->query("INSERT INTO rel_anime_studios(anime_id, studio_id) VALUES('{$anime['id']}', '{$studio['id']}')");
			}
		} 
		
 		$genres = explode(" ", $anime_info['genres']);
		$length = count($genres);
		$real_genres = array();
		for($i = 0; $i < $length; $i++) {
			switch ($genres[$i]) {
				case "Martial":
					array_push($real_genres, $genres[$i] . " " . $genres[$i + 1]);
					$i++;
					break;
				case "Shoujo":
					if (isset($genres[$i+1])) {
						if($genres[$i+1] == "Ai") {
							array_push($real_genres, $genres[$i] . " " . $genres[$i + 1]);
							$i++;
						} else {
							array_push($real_genres, $genres[$i]);
						}
					}
					break;
				case "Shounen":
					if (isset($genres[$i+1])) {			
						if($genres[$i+1] == "Ai") {
							array_push($real_genres, $genres[$i] . " " .  $genres[$i + 1]);
							$i++;
						} else {
							array_push($real_genres, $genres[$i]);
						}
					}
					break;
				case "Slice":
					array_push($real_genres, $genres[$i] . " " . $genres[$i + 1] . " " . $genres[$i + 2]);
					$i+=2;
					break;
				case "Super":
					array_push($real_genres, $genres[$i] . " " . $genres[$i + 1]);
					$i++;
					break;
				default:
					array_push($real_genres, $genres[$i]);
					break;
			}
		}
 		foreach ($real_genres as $genre) {
			$genre = trim($genre);
			$query = $this->db->query("SELECT id FROM genres WHERE name = '{$genre}'");
			$genre_query = $query->row_array();
			$is_unique = $this->db->query("SELECT anime_id, genre_id FROM rel_anime_genres WHERE anime_id = '{$anime['id']}' and genre_id = '{$genre_query['id']}'");
			if($is_unique->num_rows() > 0) {
			
			} else {
				$this->db->query("INSERT INTO rel_anime_genres(anime_id, genre_id) VALUES('{$anime['id']}', '{$genre_query['id']}')");
			}
		}
	}
}

?>