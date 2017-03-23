<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Characters_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function add_character($character_array) {
		$this->db->insert('characters', $character_array);
	}
	
	function add_character_actors($characters, $many_characters=FALSE) {		
		if($many_characters) {
			for($i = 0; $i < count($characters); $i++) {
				$this->db->select('actors.id,actors.first_name,actors.last_name,actors.language,actors.image_file_name');
				$this->db->join('character_actors', 'character_actors.actor_id=actors.id');
				$this->db->join('animes', 'animes.id=character_actors.anime_id');
				$this->db->join('characters', 'characters.id=character_actors.character_id');
				$this->db->where('character_actors.character_id', $characters[$i]['id']);
				$this->db->where('animes.id', $characters[$i]['anime_id']);
				$actors = $this->db->get('actors');
				$characters[$i]['actors'] = $actors->result_array();
	
				for($j = 0; $j < count($characters[$i]['actors']); $j++) {
					$actor_slug = "";
					if($characters[$i]['actors'][$j]['first_name'] != "") {
						$actor_slug.=$characters[$i]['actors'][$j]['first_name'];
					}
					if($characters[$i]['actors'][$j]['last_name'] != "") {
						if($actor_slug != "")
							$actor_slug.="-";
							$actor_slug.=$characters[$i]['actors'][$j]['last_name'];
					}					
					$characters[$i]['actors'][$j]['actor_slug'] = $actor_slug;		
				}
			}
		} else {
			for($i = 0; $i < count($characters['animes']); $i++) {
				$this->db->select('animes.id as anime_id,actors.id,actors.first_name,actors.last_name,actors.language,actors.image_file_name');
				$this->db->join('character_actors', 'character_actors.actor_id=actors.id');
				$this->db->join('animes', 'animes.id=character_actors.anime_id');
				$this->db->join('characters', 'characters.id=character_actors.character_id');
				$this->db->where('character_actors.character_id', $characters['id']);
				$this->db->where('animes.id', $characters['animes'][$i]['id']);
				$actors = $this->db->get('actors');
				$characters['animes'][$i]['actors'] = $actors->result_array();
				
				for($j = 0; $j < count($characters['animes'][$i]['actors']); $j++) {
					$actor_slug = "";
					if($characters['animes'][$i]['actors'][$j]['first_name'] != "") {
						$actor_slug.=$characters['animes'][$i]['actors'][$j]['first_name'];
					}
					if($characters['animes'][$i]['actors'][$j]['last_name'] != "") {
						if($actor_slug != "")
							$actor_slug.="-";
							$actor_slug.=$characters['animes'][$i]['actors'][$j]['last_name'];
					}
					$characters['animes'][$i]['actors'][$j]['actor_slug'] = $actor_slug;
				}
			}
		}
		return $characters;
	}
	
	function add_character_user_status($characters, $many_characters=FALSE) {
		$user_id = $this->session->userdata('id');
		if($many_characters) {
			for($i = 0; $i < count($characters); $i++) {	
				$this->db->select('status');
				$this->db->where('character_id', $characters[$i]['id']);
				$this->db->where('user_id', $user_id);
				$status = $this->db->get('characters_users_status');
				if($status->num_rows() == 1)  {
					$characters[$i]['character_user_status'] = $status->row_array()['status'];
				}
			}
		} else {
			$this->db->select('status');
			$this->db->where('character_id', $characters['id']);
			$this->db->where('user_id', $user_id);
			$status = $this->db->get('characters_users_status');
			if($status->num_rows() == 1)  {
				$characters['character_user_status'] = $status->row_array()['status'];
			}
		}
		
		return $characters;
	}
	
	function add_character_related_animes($row_array) {
		$this->db->select('a.id as id, a.slug as slug, a.titles as titles, ca.role');
		$this->db->join('character_animes as ca', 'ca.anime_id = a.id');
		$this->db->join('characters as c', 'c.id = ca.character_id');
		$this->db->where("c.id = {$row_array['id']}");
		$animes = $this->db->get('animes as a');
		
		$animes = $animes->result_array();	
		for($i = 0; $i < count($animes); $i++) {
			$animes[$i]['slug'] = str_replace(" ", "-", $animes[$i]['slug']);
		}
		$row_array['animes'] = $animes;		
		return $row_array;
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
		for($i = 0; $i < count($animes); $i++) {
			$animes[$i]['slug'] = str_replace(" ", "-", $animes[$i]['slug']);
		}	
		
		for($i = 0; $i < count($result_array); $i++) { // add animes to the according characters
			foreach($animes as $anime) {
				if($anime['character_id'] == $result_array[$i]['id']) {
					$result_array[$i]['animes'][] = $anime;
				}
			}
		}
	
		return $result_array;
	}
	
	function add_character_user_statuses($row_array) {
		$this->db->select('u.username');
		$this->db->join('users as u', 'u.id=cus.user_id');
		$this->db->where("cus.character_id = {$row_array['id']}");
		$this->db->where("cus.status = 1");
		$loved = $this->db->get('characters_users_status as cus');
		
		$row_array['character_love'] = $loved->result_array();
		$row_array['character_love_count'] = $loved->num_rows();
		
		$this->db->select('u.username');
		$this->db->join('users as u', 'u.id=cus.user_id');
		$this->db->where("cus.character_id = {$row_array['id']}");
		$this->db->where("cus.status = 0");
		$hated = $this->db->get('characters_users_status as cus');
		
		$row_array['character_hate'] = $hated->result_array();
		$row_array['character_hate_count'] = $hated->num_rows();
		
		return $row_array;		
	}
	
	function get_all_character_users_statuses($character_id, $status, $limit, $offset) {
		$this->db->select('u.username');
		$this->db->join('users as u', 'u.id=cus.user_id');
		$this->db->where("cus.character_id = {$character_id}");
		$this->db->where("cus.status = {$status}");
		$this->db->limit($limit, $offset);
		$query = $this->db->get('characters_users_status as cus');
		
		return $query->result_array();
	}
	
	function update_character($character_array) {
		$this->db->where('id', $character_array['id']);
		$this->db->update('characters', $character_array);
	}
	
	function get_character($character_id) {
		$query = $this->db->get_where('characters', array('id' => $character_id));
	
		if($query->num_rows() == 1) {
			$row_array = $query->row_array();
			$row_array = $this->add_character_related_animes($row_array);
			$row_array = $this->add_character_actors($row_array, FALSE);
			$row_array = $this->add_character_user_statuses($row_array);
			if($this->session->userdata('is_logged_in')) {
				$row_array = $this->add_character_user_status($row_array, FALSE);
			}
			return $row_array;
		} else {
			return FALSE;
		}
	}
	
	function get_characters_actors($anime_id, $limit, $offset) {
		$this->db->select('characters.id,characters.first_name,characters.last_name,characters.alt_name,character_animes.role,characters.image_file_name,character_animes.anime_id');
		$this->db->join('character_animes', 'character_animes.character_id=characters.id');
		$this->db->join('animes', 'animes.id=character_animes.anime_id');
		$this->db->where('character_animes.anime_id', $anime_id);
		$this->db->order_by('character_animes.role', 'asc');
		$this->db->order_by('characters.first_name', 'asc');
		$characters = $this->db->get('characters', $limit, $offset);
	
		$result = $this->add_character_actors($characters->result_array(), TRUE);
		if($this->session->userdata('is_logged_in')) {
			$result = $this->add_character_user_status($result, TRUE);
		}
		return $result;
	}
	
	function get_characters_count($anime_id) {
		$this->db->select('COUNT(1) as count');
		$this->db->from('character_animes');
		$this->db->where('character_animes.anime_id', $anime_id);
		$characters = $this->db->get();	
		return $characters->row_array();
	}
	
	function get_characters_json_data() {
		$this->db->select('id,first_name,last_name,alt_name,japanese_name,image_file_name');
		$query = $this->db->get('characters');
		return $query->result_array();
	}
	
	function get_user_characters($user_id, $status=null, $limit="", $offset="", $details=FALSE) {
		$data = array(
			'cus.user_id' => $user_id
		);	
		
		if($status !== null) {
			$data['cus.status'] = $status;
		}
		
		$this->db->select('c.id,c.first_name,c.last_name,c.alt_name,c.image_file_name');
		$this->db->join('characters as c', 'c.id=cus.character_id');
		$this->db->order_by('COALESCE(NULLIF(c.first_name, ""), c.last_name)');
		$query = $this->db->get_where('characters_users_status as cus', $data, $limit, $offset);			
		$characters = $query->result_array();
		
		if($details) {		
			
			$characters = $this->add_characters_related_animes($characters);
			
			if($this->session->userdata('is_logged_in')) {
				$characters = $this->add_character_user_status($characters, TRUE);
			}
		}

		return $characters;
	}
	
	function get_user_characters_count($user_id, $status=null) {
		$data = array(
				'characters_users_status.user_id' => $user_id
		);
		
		if($status !== null) {
			$data['characters_users_status.status'] = $status;
		}
		
		$this->db->select('COUNT(1) as count');
		$query = $this->db->get_where('characters_users_status', $data);
		
		return $query->row_array()['count'];
	}
	
	function get_all_characters($status, $limit, $offset) {
		
		$query = $this->db->query("
				SELECT @rn:=@rn+1 AS rank,id,first_name,last_name,alt_name,image_file_name,count
				FROM (
				SELECT c.id,c.first_name,c.last_name,c.alt_name,c.image_file_name, COUNT(c.id) as count, CONCAT_WS('', first_name, last_name) as name
				FROM characters as c JOIN characters_users_status as cus ON cus.character_id=c.id
				WHERE cus.status={$status}
				GROUP BY c.id
				ORDER BY count DESC, name ASC LIMIT {$limit} OFFSET {$offset}
				) t1, (SELECT @rn:={$offset}) t2");
		
		$characters = $query->result_array();
		
		$characters = $this->add_characters_related_animes($characters);	
		
		if($this->session->userdata('is_logged_in')) {		
			$characters = $this->add_character_user_status($characters, TRUE);	
		}
		
		return $characters;
	}
	
	function get_all_characters_count($status) {
		$this->db->where('cus.status', $status);
		$this->db->group_by("cus.character_id");
		$query = $this->db->get('characters_users_status as cus');
		
		return $query->num_rows();
	}
	
	function change_character_user_status($character_id, $status) {
		$user_id = $this->session->userdata('id');
		$query = $this->db->get_where('characters_users_status', array('character_id' => $character_id, 
																	   'user_id' => $user_id));
		if($query->num_rows() == 0) {
			$query = $this->db->insert('characters_users_status', array('character_id' => $character_id, 
																		'user_id' => $user_id, 'status' => $status));
			return $query;
		} else {
			if($query->row_array()['status'] == $status) {
				$query = $this->db->delete('characters_users_status', array('character_id' => $character_id, 
																			'user_id' => $user_id));
				return $query;
			} else {
				$this->db->where('character_id', $character_id);
				$this->db->where('user_id', $user_id);
				$query = $this->db->update('characters_users_status', array('status' => $status));
				return $query;
			}
		}
	}
	
	function make_character_actor_relation($character_id, $actor_id, $anime_id) {
		$data = array(
				'character_id' => $character_id,
				'actor_id' => $actor_id,
				'anime_id' => $anime_id
		);
	
		$exists = $this->db->get_where('character_actors', array('character_id' => $character_id, 'actor_id' => $actor_id, 'anime_id' => $anime_id));
	
		if($exists->num_rows() <= 0) {
			$this->db->insert('character_actors', $data);
		}
	}
	
	function make_character_anime_relation($anime_id, $character_id, $role) {
		$data = array(
				'anime_id' => $anime_id,
				'character_id' => $character_id,
				'role' => $role
		);
	
		$exists = $this->db->get_where('character_animes', array('anime_id' => $anime_id, 'character_id' => $character_id));
	
		if($exists->num_rows() <= 0) {
			$this->db->insert('character_animes', $data);
		} else {
			$data = array(
					'role' => $role
			);
			$this->db->where('anime_id', $anime_id);
			$this->db->where('character_id', $character_id);
			$this->db->update('character_animes', $data);
		}
	
	}
	
	function check_if_character_exists($character_array) {
		$this->db->where('id', $character_array['id']);
		$query = $this->db->get('characters');
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
}

?>