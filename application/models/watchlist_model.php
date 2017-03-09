<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Watchlist_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function calculate_anime_score($anime_id) {
		$this->db->select('w.score as score');
		$this->db->join('animes', 'animes.id=w.anime_id');
		$this->db->join('users', 'users.id=w.user_id');
		$this->db->where('w.anime_id', $anime_id);
		$query = $this->db->get('watchlists as w');

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
				$average_rating = number_format($total_sum/$total_votes, 3);
			} else {
				$average_rating = 0;
			}
	
			$this->db->where('id', $anime_id);
			$query = $this->db->update('animes', array('average_rating' => $average_rating, 'total_votes' => $total_votes));
	
			return $query;
			
		} else {
			return FALSE;
		}
	}
	
	function get_all_watchlist_statuses($user_id) {
		$this->db->select('watchlists.status, watchlists.score, watchlists.eps_watched, animes.episode_length');
		$this->db->join('animes', 'animes.id=watchlists.anime_id');
		$this->db->where('watchlists.user_id', $user_id);
		$query = $this->db->get('watchlists');
		
		return $query->result_array();
	}
	
	function get_watchlist_status_score($anime_id) {		
		$user_id = $this->session->userdata('id');
		
		$this->db->select('w.status, w.score');
		$this->db->join('animes', 'animes.id=w.anime_id');
		$this->db->join('users', 'users.id=w.user_id');
		$this->db->where('w.anime_id', $anime_id);
		$this->db->where('w.user_id', $user_id);
		$query = $this->db->get('watchlists as w');
		
		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}		
	}
	
	function get_watchlist($user_id, $status=0) {
		if($user_id != null) {		
			$this->db->select('w.status as status, w.eps_watched as eps_watched, w.score as score, animes.titles as titles, animes.slug as slug, animes.episode_count as episode_count, 
									animes.average_rating as average_rating, animes.show_type as show_type,animes.id as anime_id, animes.start_date as start_date');
			$this->db->join('animes', 'animes.id=w.anime_id');
			$this->db->join('users', 'users.id=w.user_id');
			if($status != 0) {
				$this->db->where('w.status', $status);
			}
			$this->db->where('w.user_id', $user_id);
			$query = $this->db->get('watchlists as w');

			if($query) {
				return $query->result_array();
			} else {
				return FALSE;
			}
		}
	}
	
	function add_status($anime_id, $status) {
		$user_id = $this->session->userdata('id');
	
		if($status == 1) { // if user has chosen "Watched" option from the dropdown
			$this->db->select('episode_count');
			$this->db->where('id', $anime_id);
			$query = $this->db->get('animes');
			$eps = $query->row_array()['episode_count'];
		} else {
			$eps = 0;
		}
	
		$data = array(
				'anime_id' => $anime_id,
				'user_id' => $user_id,
				'status' => $status,
				'eps_watched' => $eps
		);
	
		$query = $this->db->insert('watchlists', $data);
	
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_status($anime_id, $status) {		
		$user_id = $this->session->userdata('id');
		
		$data = array('status' => $status, 'status_updated_at' => date('Y-m-d H:i:s'));
			
		if($status == 1) {
			$this->db->select('episode_count');
			$this->db->where('id', $anime_id);
			$query = $this->db->get('animes');
			$data['eps_watched'] = $query->row_array()['episode_count'];
		}
		
		$this->db->where('anime_id', $anime_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->update('watchlists', $data);
			
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function delete_status($anime_id) {
		$user_id = $this->session->userdata('id');		
		$query = $this->db->delete('watchlists', array('anime_id' => $anime_id, 'user_id' => $user_id));
		return $query;
	}

	function update_score($anime_id, $score) {
		$user_id = $this->session->userdata('id');
		
		$this->db->select('w.score');
		$this->db->join('animes', 'animes.id=w.anime_id');
		$this->db->join('users', 'users.id=w.user_id');
		$this->db->where('w.anime_id', $anime_id);
		$this->db->where('w.user_id', $user_id);
		$query = $this->db->get('watchlists as w');

		if($query->num_rows() == 1) {
			$data = array('w.score' => $score);			
			$this->db->where('w.anime_id', $anime_id);
			$this->db->where('w.user_id', $user_id);
			$query = $this->db->update('watchlists as w', $data);
			$afftected_rows = $this->db->affected_rows();
		} else {
			return FALSE;
		} 	

		if($query === TRUE && $afftected_rows > 0) {
			$this->calculate_anime_score($anime_id);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_eps($anime_id, $eps_watched) {	
		$user_id = $this->session->userdata('id');
		
		$data = array('eps_watched' => $eps_watched);
			
		$this->db->where('anime_id', $anime_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->update('watchlists', $data);
			
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_default_watchlist_sort($default_watchlist_sort) {
		$user_id = $this->session->userdata('id');
		
		$this->db->where('user_id', $user_id);
		$query = $this->db->update('user_settings', array('default_watchlist_sort' => $default_watchlist_sort));
		
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
		
		$this->db->select('w.anime_id,status,score,eps_watched');
		$this->db->join('animes', 'animes.id=w.anime_id');
		$this->db->join('users', 'users.id=w.user_id');
		$this->db->where_in('w.anime_id', $anime_ids);
		$this->db->where('w.user_id', $user_id);
		$statuses = $this->db->get('watchlists as w');
		
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