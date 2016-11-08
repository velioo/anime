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
		$username = addslashes($username);
		$query = $this->db->query("SELECT username FROM users WHERE username = '{$username}'");

		if($query->num_rows() > 0) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function check_if_email_exists($email) {
		$email = addslashes($email);
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
	
	
	function update_user_info($id, $bio, $age, $gender, $country) {
		$bio = addslashes($bio);
		$bio = htmlspecialchars($bio);
		$age = intval($age);
		$gender = addslashes($gender);
		$gender = htmlspecialchars($gender);
		$country = addslashes($country);
		$country = htmlspecialchars($country);
		$this->db->query("UPDATE users SET bio = '{$bio}', age = {$age}, gender = '{$gender}', country = '{$country}' WHERE id = $id");
	}
	
	function get_id_by_email($email) {
		$query = $this->db->query("SELECT id FROM users WHERE email = '{$email}'");
		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function temp_reset_password($user_id, $temp_pass){
		$this->db->query("INSERT INTO user_temp_passes(user_id, temp_pass) VALUES ({$user_id}, '{$temp_pass}')");
	}
	
	function is_temp_pass_valid($temp_pass){
		$query = $this->db->query("SELECT user_id, temp_pass FROM user_temp_passes WHERE temp_pass = '{$temp_pass}'");
		
		if($query->num_rows() == 1){
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}

	function update_user_password($id, $password) {
		$query = $this->db->query("UPDATE users SET password = '{$password}' WHERE id = {$id}");
		return $query;
	}
	
	function delete_temp_pass($user_id) {
		$this->db->query("DELETE FROM user_temp_passes WHERE user_id = {$user_id}");
	}
}

?>