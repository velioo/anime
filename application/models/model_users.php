<?php

Class Model_users extends CI_Model {
	
	function __construct() {
		parent::__construct();
	
	}
	/*
	function getFirstNames() {
		$query = $this->db->query('SELECT first_name FROM phonebook');
		
		if($query->num_rows() > 0) {
			return $query;
		} else {
			return NULL;
		}
		
	}
	
	function getUsers() {
		$query = $this->db->query('SELECT * FROM phonebook');
		
		if($query->num_rows() > 0) {
			return $query;
		} else {
			return NULL;
		}
	}
	
	function getCityUsers($city) {
		$query = $this->db->query("SELECT * FROM phonebook WHERE city='{$city}'");
		if($query->num_rows() > 0) {
			return $query;
		} else {
			return NULL;
		}
	}
	
	function insertUser($data) {
		$this->db->insert('phonebook', $data);
	}
	
	function deleteUser($id) {
		$this->db->where('id', $id);
		$this->db->delete('phonebook');
		
	}
	
	function updateUser($id, $data) {
		$this->db->where('id', $id);
		$this->db->update('phonebook', $data);
	}
	*/
}

?>