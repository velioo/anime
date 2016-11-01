<?php

Class Users_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	
	}
	
	function validate() {
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));

		$query = $this->db->query("SELECT username FROM users WHERE username='{$username}' and password='{$password}'");
	
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_cover_image($id, $image) {
		$this->db->query("UPDATE users SET cover_image = '{$image}' WHERE id = {$id}");
	}
	
	function update_avatar_image($id, $image) {
		$this->db->query("UPDATE users SET profile_image = '{$image}' WHERE id = {$id}");
	}
	
	function update_cover_offset($id, $offset) {
		$this->db->query("UPDATE users SET top_offset = '{$offset}' WHERE id = {$id}");
	}
	
	function check_if_username_exists($username) {
		$query = $this->db->query("SELECT username FROM users WHERE username = '{$username}'");

		if($query->num_rows() > 0) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function check_if_email_exists($email) {
		$query = $this->db->query("SELECT email FROM users WHERE email = '{$email}'");
	
		if($query->num_rows() > 0) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function create_user() {
		
		$new_user_data = array (
			'username' => $this->input->post('username'),
			'email' => $this->input->post('email'),
			'password' => md5($this->input->post('password')),
			'joined_on' => date("Y-m-d")
		);
		$query = $this->db->insert('users', $new_user_data);
		return $query;
		
	}
	
	function get_user_info_logged($username) {
		$query = $this->db->query("SELECT * FROM users WHERE username='{$username}'");

		if ($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_user_info($username) {
		$query = $this->db->query("SELECT username,joined_on,country,profile_image,cover_image,top_offset,gender,bio,life_anime,last_online,total_episodes,age,show_age FROM users WHERE username='{$username}'");
		
		if ($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
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