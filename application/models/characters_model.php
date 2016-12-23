<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Characters_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function get_character($character_id) {
		$query = $this->db->get_where('characters', array('id' => $character_id));
				
		if($query->num_rows() == 1) {
			$row_array = $query->row_array();
			$row_array = $this->add_character_related_animes($row_array);
			$row_array = $this->add_character_actors($row_array, FALSE);	
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
		
		return $result;
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
			}
		} else {
			for($i = 0; $i < count($characters['animes']); $i++) {
				$this->db->select('animes.id as anime_id,actors.id as actor_id,actors.first_name,actors.last_name,actors.language,actors.image_file_name');
				$this->db->join('character_actors', 'character_actors.actor_id=actors.id');
				$this->db->join('animes', 'animes.id=character_actors.anime_id');
				$this->db->join('characters', 'characters.id=character_actors.character_id');
				$this->db->where('character_actors.character_id', $characters['id']);
				$this->db->where('animes.id', $characters['animes'][$i]['id']);
				$actors = $this->db->get('actors');
				$characters['animes'][$i]['actors'] = $actors->result_array();
			}
		}
		return $characters;
	}
	
	function add_character_related_animes($row_array) {
		$animes = $this->db->query("SELECT a.id as id, a.slug as slug, a.titles as titles, ca.role FROM animes as a JOIN character_animes as ca ON ca.anime_id = a.id
				JOIN characters as c ON c.id = ca.character_id WHERE c.id = {$row_array['id']} ");	
		$animes = $animes->result_array();	
		for($i = 0; $i < count($animes); $i++) {
			$animes[$i]['slug'] = str_replace(" ", "-", $animes[$i]['slug']);
		}
		$row_array['animes'] = $animes;		
		return $row_array;
	}
	
	function get_characters_count($anime_id) {
		$this->db->select('COUNT(1) as count');
		$this->db->from('character_animes');
		$this->db->where('character_animes.anime_id', $anime_id);
		$characters = $this->db->get();	
		return $characters->row_array();
	}
	
	function add_character($character_array) {
		$this->db->insert('characters', $character_array);
	}
	
	function update_character($character_array) {
		$this->db->where('id', $character_array['id']);
		$this->db->update('characters', $character_array);
	}
	
	function add_actor($actor_array) {
		$this->db->insert('actors', $actor_array);
	}
	
	function update_actor($actor_array) {
		$this->db->where('id', $actor_array['id']);
		$this->db->update('actors', $actor_array);
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
	
	function get_characters_json_data() {
		$this->db->select('id,first_name,last_name,alt_name,japanese_name,image_file_name');
		$query = $this->db->get('characters');
		return $query->result_array();
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