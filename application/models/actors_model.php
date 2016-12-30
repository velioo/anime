<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Actors_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function add_actor($actor_array) {
		$this->db->insert('actors', $actor_array);
	}
	
	function get_all_actor_users_statuses($actor_id, $status, $limit, $offset) {
		$this->db->select('u.username');
		$this->db->join('users as u', 'u.id=aus.user_id');
		$this->db->where("aus.actor_id = {$actor_id}");
		$this->db->where("aus.status = {$status}");
		$this->db->limit($limit, $offset);
		$query = $this->db->get('actors_users_status as aus');
	
		return $query->result_array();
	}
	
	function update_actor($actor_array) {
		$this->db->where('id', $actor_array['id']);
		$this->db->update('actors', $actor_array);
	}
	
	function add_actor_related_animes_characters($row_array) {
		$this->db->select('a.id as anime_id, a.slug as slug, a.titles as titles, a.poster_image_file_name as anime_image, c.id as character_id, c.first_name, c.last_name, c.image_file_name as character_image');
		$this->db->join('character_actors as ca', 'ca.anime_id = a.id');
		$this->db->join('characters as c', 'c.id = ca.character_id');
		$this->db->where("ca.actor_id = {$row_array['id']}");
		$this->db->order_by('a.slug', 'asc');
		$query = $this->db->get('animes as a');
	
		$query = $query->result_array();		
		for($i = 0; $i < count($query); $i++) {
			$query[$i]['slug'] = str_replace(" ", "-", $query[$i]['slug']);
			
			$character_slug = "";			
			if($query[$i]['first_name'] != "") {
				$character_slug.=$query[$i]['first_name'];
			}
			if($query[$i]['last_name'] != "") {
				if($character_slug != "")
					$character_slug.="-";
					$character_slug.=$query[$i]['last_name'];
			}
			
			$query[$i]['character_slug'] = $character_slug;
		}
		
		$row_array['animes_characters'] = $query;	
		return $row_array;
	}
	
	function add_actor_user_status($actors, $many_actors=FALSE) {
		$user_id = $this->session->userdata('id');
		if($many_actors) {
			for($i = 0; $i < count($actors); $i++) {
				$this->db->select('status');
				$this->db->where('actor_id', $actors[$i]['id']);
				$this->db->where('user_id', $user_id);
				$status = $this->db->get('actors_users_status');
				if($status->num_rows() == 1)  {
					$actors[$i]['actor_user_status'] = $status->row_array()['status'];
				}
			}
		} else {
			$this->db->select('status');
			$this->db->where('actor_id', $actors['id']);
			$this->db->where('user_id', $user_id);
			$status = $this->db->get('actors_users_status');
			if($status->num_rows() == 1)  {
				$actors['actor_user_status'] = $status->row_array()['status'];
			}
		}
	
		return $actors;
	}
	
	function add_actor_user_statuses($row_array) {
		$this->db->select('u.username');
		$this->db->join('users as u', 'u.id=aus.user_id');
		$this->db->where("aus.actor_id = {$row_array['id']}");
		$this->db->where("aus.status = 1");
		$loved = $this->db->get('actors_users_status as aus');
	
		$row_array['actor_love'] = $loved->result_array();
		$row_array['actor_love_count'] = $loved->num_rows();
	
		$this->db->select('u.username');
		$this->db->join('users as u', 'u.id=aus.user_id');
		$this->db->where("aus.actor_id = {$row_array['id']}");
		$this->db->where("aus.status = 0");
		$hated = $this->db->get('actors_users_status as aus');
	
		$row_array['actor_hate'] = $hated->result_array();
		$row_array['actor_hate_count'] = $hated->num_rows();
	
		return $row_array;
	}
	
	function get_actor($actor_id) {
		$query = $this->db->get_where('actors', array('id' => $actor_id));
	
		if($query->num_rows() == 1) {
			$row_array = $query->row_array();
			$row_array = $this->add_actor_related_animes_characters($row_array);		
			$row_array = $this->add_actor_user_statuses($row_array);
			if($this->session->userdata('is_logged_in')) {
				$row_array = $this->add_actor_user_status($row_array, FALSE);
			}	
			return $row_array;
		} else {
			return FALSE;
		}
	}
	
	function get_actors_json_data() {
		$this->db->select('id,first_name,last_name,first_name_japanese,last_name_japanese,image_file_name');
		$query = $this->db->get('actors');
		return $query->result_array();
	}
	
	function get_user_actors($user_id, $status=null, $limit="", $offset="") {
		$data = array(
			'actors_users_status.user_id' => $user_id
		);
	
		if($status !== null) {
			$data['actors_users_status.status'] = $status;
		}
	
		$this->db->select('actors.id,actors.first_name, actors.last_name, actors.language, actors.image_file_name');
		$this->db->join('actors', 'actors.id=actors_users_status.actor_id');
		$this->db->order_by('actors.first_name, actors.last_name');
		$query = $this->db->get_where('actors_users_status', $data, $limit, $offset);
		$actors = $query->result_array();

		for($i = 0; $i < count($actors); $i++) {
			$actor_slug = "";
			if($actors[$i]['first_name'] != "") {
				$actor_slug.=$actors[$i]['first_name'];
			}
			if($actors[$i]['last_name'] != "") {
				if($actor_slug != "")
					$actor_slug.="-";
					$actor_slug.=$actors[$i]['last_name'];
			}
			$actors[$i]['actor_slug'] = $actor_slug;
		}
			
		$actors = $this->add_actor_user_status($actors, TRUE);
	
		return $actors;
	}
	
	function get_user_actors_count($user_id, $status=null) {
		$data = array(
			'actors_users_status.user_id' => $user_id
		);
	
		if($status !== null) {
			$data['actors_users_status.status'] = $status;
		}
	
		$this->db->select('COUNT(1) as count');
		$query = $this->db->get_where('actors_users_status', $data);
	
		return $query->row_array()['count'];
	}
	
	function change_actor_user_status($actor_id, $status) {
		$user_id = $this->session->userdata('id');
		$query = $this->db->get_where('actors_users_status', array('actor_id' => $actor_id, 'user_id' => $user_id));
		if($query->num_rows() == 0) {
			$query = $this->db->insert('actors_users_status', array('actor_id' => $actor_id, 'user_id' => $user_id, 'status' => $status));
			return $query;
		} else {
			if($query->row_array()['status'] == $status) {
				$query = $this->db->delete('actors_users_status', array('actor_id' => $actor_id, 'user_id' => $user_id));
				return $query;
			} else {
				$this->db->where('actor_id', $actor_id);
				$this->db->where('user_id', $user_id);
				$query = $this->db->update('actors_users_status', array('status' => $status));
				return $query;
			}
		}
	}
	
	function check_if_actor_exists($actor_array) {
		$this->db->where('id', $actor_array['id']);
		$query = $this->db->get('actors');
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
}

?>