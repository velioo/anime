<?php

Class Users_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	
	}
	
	function validate() {
		$username = $this->input->post('username');
		$password = hash('sha256', $this->input->post('password'));
		
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
			'password' => hash('sha256', $this->input->post('password')),
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
	
	function check_if_user_is_admin($id) {
		$query = $this->db->query("SELECT users.id FROM users JOIN admins ON admins.user_id=users.id WHERE users.id = {$id}");	
		if($query->num_rows() == 1) {
			return TRUE;
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
	
	function update_user_acc_info($id, $username, $email, $password) {
		
		$fields = "";
		
		if($username != $this->session->userdata['username']) {
			$fields.="username = '{$username}', ";
		}
		
		if($email != $this->session->userdata['email']) {
			$fields.="email = '{$email}', ";
		}
		
		if($password != "") {
			$fields.="password = '{$password}', ";
		}
		
		$fields = substr($fields, 0 , strlen($fields) - 2);
		
		$query = $this->db->query("UPDATE users SET {$fields} WHERE id = {$id}");
		
		if($query) {
			$query = $this->db->query("SELECT username FROM users WHERE id = {$id}");
			$user_data = $this->get_user_info_logged($query->result_array()[0]['username']);
			return $user_data;
		} else {
			return FALSE;
		}
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
	
	function check_if_user_connected_to_fb($user_id) {
		$query = $this->db->query("SELECT users.id FROM users JOIN facebook_accounts ON facebook_accounts.user_id=users.id WHERE users.id = {$user_id}");
		
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function disconnect_facebook($user_id) {
		$query = $this->db->query("SELECT password FROM users WHERE id = {$user_id}");
		
		if($query->num_rows() > 0) {
			if($query->result_array()[0]['password'] == NULL) {
				return FALSE;
			} else {
				$query = $this->db->query("DELETE FROM facebook_accounts WHERE user_id = {$user_id}");
				return $query;
			}
		} else {
			return FALSE;
		}
	}
	
	function connect_facebook($user_id, $email, $fb_user_id, $access_token) {
		$query = $this->db->query("INSERT INTO facebook_accounts(user_id, fb_user_id, email, access_token) VALUES ({$user_id}, '{$fb_user_id}', '{$email}', '{$access_token}')");
		
		if($query) {
			$this->db->query("UPDATE users SET email = '{$email}' WHERE id = {$user_id}");
			$query = $this->db->query("SELECT email FROM users WHERE id = {$user_id}");
			if($query->num_rows() == 1)
				return $query->row_array();
			else 
				return FALSE;
		} else {
			return FALSE;
		}
	}
	
	function check_if_fb_acc_exist_and_return_user($fb_user_id) {	
		$query = $this->db->query("SELECT users.id,users.username,users.email,users.joined_on,users.country,users.cover_image,users.top_offset,
				users.gender,users.bio,users.life_anime,users.last_online,users.total_episodes,users.age,users.show_age FROM users JOIN facebook_accounts ON facebook_accounts.user_id=users.id WHERE fb_user_id = '{$fb_user_id}'");
		
		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function create_new_user_by_facebook_login($fb_user_id,$username,$email,$access_token) {		
		$new_user_data = array (
				'username' => $username,
				'email' => $email,
				'joined_on' => date("Y-m-d")
		);
		$query = $this->db->insert('users', $new_user_data);
		$query = $this->db->query("SELECT * FROM users WHERE username = '{$username}'");
		
		$user_id = $query->result_array()[0]['id'];	
		
		$this->db->query("INSERT INTO facebook_accounts(user_id, fb_user_id, email, access_token) VALUES ({$user_id}, '{$fb_user_id}', '{$email}', '{$access_token}')");	
		
		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
}

?>