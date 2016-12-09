<?php
Class Watchlist_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function calculate_anime_score($anime_id) {
		$query = $this->db->query("SELECT watchlists.score as score FROM watchlists JOIN animes ON animes.id=watchlists.anime_id
																			JOIN users ON users.id=watchlists.user_id WHERE watchlists.anime_id = {$anime_id}");
		if($query) {
			$total_votes = 0;
			$total_sum = 0;
			foreach ($query->result_array() as $row) {
				if($row['score'] != 0) {
					$total_sum+=$row['score'];
					$total_votes++;
				}
			}
	
			if($total_votes != 0) {
				$average_rating =  number_format($total_sum/$total_votes, 3);
			} else {
				$average_rating = 0;
			}
	
			$this->db->query("UPDATE animes SET average_rating = {$average_rating}, total_votes = {$total_votes} WHERE id = {$anime_id}");
	
		} else {
			return FALSE;
		}
	}
	
	function get_watchlist_status_score($anime_id) {		
		$user_id = $this->session->userdata('id');
		$query = $this->db->query("SELECT watchlists.status, watchlists.score FROM watchlists JOIN animes ON animes.id=watchlists.anime_id 
					JOIN users ON users.id=watchlists.user_id WHERE watchlists.anime_id = {$anime_id} and watchlists.user_id = {$user_id}");
		
		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}		
	}
	
	function get_watchlist($user_id) {
		if($user_id != null) {
			$query = $this->db->query("SELECT watchlists.status as status, watchlists.eps_watched as eps_watched, watchlists.score as score, animes.titles as titles, animes.slug as slug, animes.episode_count as episode_count, 
									animes.average_rating as average_rating, animes.show_type as show_type,animes.id as anime_id, animes.start_date as start_date
									FROM watchlists JOIN animes ON animes.id=watchlists.anime_id
													JOIN users ON users.id=watchlists.user_id
													WHERE watchlists.user_id = {$user_id}");
			if($query) {
				return $query->result_array();
			} else {
				return FALSE;
			}
		}
	}
	
	function update_status($anime_id, $status) {		
		$user_id = $this->session->userdata('id');

		$query = $this->db->query("SELECT watchlists.status, animes.episode_count FROM watchlists JOIN animes ON animes.id=watchlists.anime_id 
											JOIN users ON users.id=watchlists.user_id WHERE watchlists.anime_id = {$anime_id} and watchlists.user_id = {$user_id}");
		if($query->num_rows() == 1) {			
			if($status != 6) {					
				if($status == 1) {
					$eps = " , watchlists.eps_watched = " . $query->row_array()['episode_count'] . " ";
				} else {
					$eps = " , watchlists.eps_watched = 0 ";
				}
					
				$query = $this->db->query("UPDATE watchlists JOIN animes ON animes.id=watchlists.anime_id
							JOIN users ON users.id=watchlists.user_id SET watchlists.status = {$status} {$eps} WHERE watchlists.anime_id = {$anime_id} and watchlists.user_id = {$user_id}");
			} else {
				$query = $this->db->query("DELETE FROM watchlists WHERE watchlists.anime_id = {$anime_id} and watchlists.user_id = {$user_id}");
				$this->calculate_anime_score($anime_id);
			}
			
		} else {							
			if($status == 1) {
				$query = $this->db->query("SELECT episode_count FROM animes WHERE id = {$anime_id}");
				if($query->num_rows() == 1) {
					$eps = $query->row_array()['episode_count'];
				} else { 
					return FALSE;
				}
			} else {
				$eps = 0;
			}
			
			$query = $this->db->query("INSERT INTO watchlists (anime_id, user_id, status, eps_watched) VALUES ({$anime_id}, {$user_id}, {$status}, {$eps})");	
		}
		
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function update_score($anime_id, $value) {
		$user_id = $this->session->userdata('id');
		$query = $this->db->query("SELECT watchlists.score FROM watchlists JOIN animes ON animes.id=watchlists.anime_id
								JOIN users ON users.id=watchlists.user_id WHERE watchlists.anime_id = {$anime_id} and watchlists.user_id = {$user_id}");
		if($query->num_rows() == 1) {
			$query = $this->db->query("UPDATE watchlists JOIN animes ON animes.id=watchlists.anime_id
										JOIN users ON users.id=watchlists.user_id  SET watchlists.score = {$value} WHERE watchlists.anime_id = {$anime_id} and watchlists.user_id = {$user_id}");
		} else {
			return FALSE;
		}

		$this->calculate_anime_score($anime_id);

		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_eps($anime_id, $eps_watched) {	
		$user_id = $this->session->userdata('id');
		$query = $this->db->query("UPDATE watchlists JOIN animes ON animes.id=watchlists.anime_id
				JOIN users ON users.id=watchlists.user_id SET eps_watched = {$eps_watched} WHERE watchlists.anime_id = {$anime_id} and watchlists.user_id = {$user_id}");
			
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function add_user_statuses($animes) {
		$user_id = $this->session->userdata('id');
		
		foreach($animes as $anime) {
			$anime_ids[] = $anime['id'];
		}
		
		$ids = join("','",$anime_ids);
		
		$statuses = $this->db->query("SELECT watchlists.anime_id,status,score,eps_watched FROM watchlists JOIN animes ON animes.id=watchlists.anime_id 
																			JOIN users ON users.id=watchlists.user_id WHERE watchlists.anime_id IN ('{$ids}') and watchlists.user_id = {$user_id}");
		
		for($i = 0; $i < count($animes); $i++) {
			foreach($statuses->result_array() as $status) {
				if($animes[$i]['id'] == $status['anime_id']) {
					$animes[$i]['user_status'] = $status['status'];			
					$animes[$i]['user_score'] = $status['score'];			
				}
			}
		}
		
		return $animes;
	}
		
	
}
?>