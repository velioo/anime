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
	
	function create_new_user_by_facebook_login($fb_user_id, $username, $email, $access_token) {
		$new_user_data = array (
				'username' => $username,
				'email' => $email,
				'password' => hash('sha256', uniqid("", TRUE)),
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
	
	function disconnect_facebook($user_id) {
		$query = $this->db->query("SELECT facebook_accounts.changed_pass FROM facebook_accounts JOIN users ON users.id=facebook_accounts.user_id WHERE facebook_accounts.user_id = {$user_id}");
		if($query->num_rows() == 1) {
			if($query->row_array()['changed_pass'] == 1) {
				$query = $this->db->query("DELETE FROM facebook_accounts WHERE user_id = {$user_id}");
				return $query;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function update_cover_image($image) {
		$user_id = $this->session->userdata('id');
		if($user_id != null) {
			$query = $this->db->query("UPDATE users SET cover_image = '{$image}' WHERE id = {$user_id}");
			if($query) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function update_avatar_image($image) {
		$user_id = $this->session->userdata('id');
		if($user_id != null) {
			$query = $this->db->query("UPDATE users SET profile_image = '{$image}' WHERE id = {$user_id}");
			if($query) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function update_cover_offset($offset) {
		$user_id = $this->session->userdata('id');
		if($user_id != null) {
			$this->db->query("UPDATE users SET top_offset = '{$offset}' WHERE id = {$user_id}");
		} else {
			return FALSE;
		}
	}
	
	function update_user_info($id, $bio, $birthdate, $gender, $country) {
		$bio = addslashes($bio);
		$bio = htmlspecialchars($bio);	
		if(validateDate($birthdate, 'Y-m-d')) {
			$current_year = date("Y");
			$last_year = $current_year - 100;
			$year = date('Y', strtotime($birthdate));
			if(($year < $last_year) || ($year > $current_year)) {
				$birthdate = "";
			} else {
				$birthdate = ", birthdate = '{$birthdate}'";
			}			
		} else {
			$birthdate = "";
		}
		
		if(($gender != "male") && ($gender != "female") && ($gender != "unknown")) {
			$gender = "";
		} else {
			$gender = ", gender = '{$gender}'";
		}
		
		$country = addslashes($country);
		$country = htmlspecialchars($country);
		$this->db->query("UPDATE users SET bio = '{$bio}' {$birthdate} {$gender} , country = '{$country}' WHERE id = {$id}");
	}
	
	function update_user_acc_info($username="", $email="", $password="") {
		$user_id = $this->session->userdata('id');
		if($user_id != null) {
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
		
			$query = $this->db->query("UPDATE users SET {$fields} WHERE id = {$user_id}");
		
			if($query) {					
				if($password != "") {
					$query = $this->db->query("SELECT facebook_accounts.changed_pass FROM facebook_accounts JOIN users ON users.id=facebook_accounts.user_id WHERE facebook_accounts.user_id = {$user_id}");
					if($query->num_rows() == 1) {
						if($query->row_array()['changed_pass'] == 0) {
							$this->db->query("UPDATE facebook_accounts JOIN users ON users.id=facebook_accounts.user_id SET changed_pass = 1 WHERE facebook_accounts.user_id = {$user_id}");
						}
					}
				}
					
				$query = $this->db->query("SELECT username FROM users WHERE id = {$user_id}");
				$user_data = $this->get_user_info_logged($query->result_array()[0]['username']);
				return $user_data;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function update_user_privacy_notifications($age_visibility) {
		if($age_visibility == 0 || $age_visibility == 1) {
			$user_id = $this->session->userdata('id');
			if($user_id != null) {
				$query = $this->db->query("UPDATE user_settings JOIN users ON users.id=user_settings.user_id SET show_age = {$age_visibility} WHERE user_settings.user_id = {$user_id}");
				if($query) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}
	}
	
	function update_user_preferences($default_watchlist_page) {
		if($default_watchlist_page >= 0 && $default_watchlist_page <= 5) {
			$user_id = $this->session->userdata('id');
			if($user_id != null) {
				$query = $this->db->query("UPDATE user_settings JOIN users ON users.id=user_settings.user_id SET default_watchlist_page = {$default_watchlist_page} WHERE user_settings.user_id = {$user_id}");
				if($query) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			return FALSE;
		}
	}
	
	function update_user_password($id, $password) {
		$query = $this->db->query("UPDATE users SET password = '{$password}' WHERE id = {$id}");
		return $query;
	}
	
	function get_user_info_logged($username) {
		$query = $this->db->query("SELECT u.*,us.show_age,us.default_watchlist_page,us.show_last_online FROM users as u JOIN user_settings as us ON us.user_id=u.id WHERE username='{$username}'");
	
		if ($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_user_info($username) {
		$query = $this->db->query("SELECT u.id,u.username,u.joined_on,u.country,u.profile_image,u.cover_image,u.top_offset,u.gender,u.bio,u.life_anime,u.last_online,u.total_episodes,u.birthdate,
				us.show_age,us.default_watchlist_page,us.show_last_online
				FROM users as u JOIN user_settings as us ON us.user_id=u.id WHERE username='{$username}'");
	
		if ($query->num_rows() == 1) {
			return $query->row_array();
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
	
	function get_user_cover_image() {
		$user_id = $this->session->userdata('id');
		if($user_id != null) {
			$query = $this->db->query("SELECT cover_image FROM users WHERE id = {$user_id}");
			if($query->num_rows() == 1) {
				return $query->row_array();
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function get_user_avatar_image() {
		$user_id = $this->session->userdata('id');
		if($user_id != null) {
			$query = $this->db->query("SELECT profile_image FROM users WHERE id = {$user_id}");
			if($query->num_rows() == 1) {
				return $query->row_array();
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
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
	
	function check_if_user_is_admin($id) {
		$query = $this->db->query("SELECT users.id FROM users JOIN admins ON admins.user_id=users.id WHERE users.id = {$id}");	
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function check_if_user_connected_to_fb($user_id) {
		$query = $this->db->query("SELECT users.id FROM users JOIN facebook_accounts ON facebook_accounts.user_id=users.id WHERE users.id = {$user_id}");
	
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	
	
	function check_if_fb_acc_exist_and_return_user($fb_user_id) {
		$query = $this->db->query("SELECT u.id,u.username,u.email,u.joined_on,u.country,u.cover_image,u.top_offset,
				u.gender,u.bio,u.life_anime,u.last_online,u.total_episodes,u.birthdate,us.show_age,us.default_watchlist_page,
				us.show_last_online FROM users as u JOIN facebook_accounts ON facebook_accounts.user_id=u.id
				JOIN user_settings as us ON us.user_id=u.id WHERE fb_user_id = '{$fb_user_id}'");
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
	
	function delete_temp_pass($user_id) {
		$this->db->query("DELETE FROM user_temp_passes WHERE user_id = {$user_id}");
	}

}

?>