<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Users_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	
	}
	
	function validate() {
		$username = $this->input->post('username');
		$password = hash('sha256', $this->input->post('password'));		
		$this->db->select('username, password');
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$query = $this->db->get('users');
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
		if($query) {
			$user_settings_data = array(
				'user_id' => $this->db->insert_id()			
			);			
			$query = $this->db->insert('user_settings', $user_settings_data);			
			return $query;
		} else {
			return FALSE;
		}
	}
	
	function create_new_user_by_facebook_login($fb_user_id, $username, $email, $access_token) {
		$new_user_data = array (
			'username' => $username,
			'email' => $email,
			'password' => hash('sha256', uniqid("", TRUE)),
			'joined_on' => date("Y-m-d")
		);
		$query = $this->db->insert('users', $new_user_data);
		
		if($query) {			
			$user_id = $this->db->insert_id();			
			$query = $this->db->insert('user_settings', array('user_id' => $user_id));
			if(!$query) {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	
		$facebook_data = array(
			'user_id' => $user_id,
			'fb_user_id' => $fb_user_id,
			'email' => $email,
			'access_token' => $access_token
		);
		
		$query = $this->db->insert('facebook_accounts', $facebook_data);
		
		if(!$query) {
			return FALSE;
		}
	
		$query = $this->db->get_where('users', array('username' => $username));
		
		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function connect_facebook($user_id, $email, $fb_user_id, $access_token) {
		
		$data = array(
			'user_id' => $user_id,
			'fb_user_id' => $fb_user_id,
			'email' => $email,
			'access_token' => $access_token,
			'changed_pass' => 1
		);
		
		$query = $this->db->insert('facebook_accounts', $data);
	
		if($query) {
			$this->db->where('id', $user_id);
			$this->db->update('users', array('email' => $email));
		
			$this->db->select('email');
			$this->db->where('id', $user_id);
			$query = $this->db->get('users');
			
			if($query->num_rows() == 1)
				return $query->row_array();
				else
					return FALSE;
		} else {
			return FALSE;
		}
	}
	
	function disconnect_facebook($user_id) {
		$this->db->select('fa.changed_pass');
		$this->db->join('users as u', 'u.id=fa.user_id');
		$this->db->where('fa.user_id', $user_id);
		$query = $this->db->get('facebook_accounts as fa');

		if($query->num_rows() == 1) {
			if($query->row_array()['changed_pass'] == 1) {
				$query = $this->db->delete('facebook_accounts', array('user_id' => $user_id));
				return $query;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function update_user_activity() {
		$user_id = $this->session->userdata('id');
		$date = date('Y-m-d H:i:s');
		$this->db->where('id', $user_id);
		$this->db->update('users', array('last_online' => $date));
	}
	
	function update_cover_image($image) {
		$user_id = $this->session->userdata('id');
		$this->db->where('id', $user_id);
		$query = $this->db->update('users', array('cover_image' => $image));
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_avatar_image($image) {
		$user_id = $this->session->userdata('id');
		$this->db->where('id', $user_id);
		$query = $this->db->update('users', array('profile_image' => $image));
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_cover_offset($offset) {
		$user_id = $this->session->userdata('id');
		if($user_id != null) {
			$this->db->where('id', $user_id);
			$this->db->update('users', array('top_offset' => $offset));
		} else {
			return FALSE;
		}
	}
	
	function update_user_info($id, $bio, $birthdate, $gender, $country) {
		
		$data = array();
		
		$bio = addslashes($bio);
		$bio = strip_tags($bio);	
		
		$data['bio'] = $bio;
		
		if(validateDate($birthdate, 'Y-m-d')) {
			$current_year = date("Y");
			$last_year = $current_year - 100;
			$year = date('Y', strtotime($birthdate));
			if(($year >= $last_year) && ($year <= $current_year)) {
				$data['birthdate'] = $birthdate;
			}			
		} 
		
		if(($gender == "male") || ($gender == "female") || ($gender == "unknown")) {
			$data['gender'] = $gender;
		} 
		
		$country = addslashes($country);
		$country = strip_tags($country);
		
		$data['country'] = $country;
		
		$this->db->where('id', $id);
		$query = $this->db->update('users', $data);
		
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function update_user_acc_info($data) {
		$user_id = $this->session->userdata('id');		
		$this->db->where('id', $user_id);
		$query = $this->db->update('users', $data);
	
		if($query) {					
			if(isset($data['password'])) {
				$this->db->select('fa.changed_pass');
				$this->db->join('users as u', 'u.id=fa.user_id');
				$this->db->where('fa.user_id', $user_id);
				$query = $this->db->get('facebook_accounts as fa');
				if($query->num_rows() == 1) {
					if($query->row_array()['changed_pass'] == 0) {
						$this->db->where('user_id', $user_id);
						$query = $this->db->update('facebook_accounts', array('changed_pass' => 1));
					}
				}
			}
				
			$this->db->select('username');
			$this->db->where('id', $user_id);
			$query = $this->db->get('users');
			
			if($query->num_rows() == 1) {		
				$user_data = $this->get_user_info_logged($query->row_array()['username']);
			} else {
				return FALSE;
			}
			
			return $user_data;
		} else {
			return FALSE;
		}
	}
	
	function update_user_privacy_notifications($age_visibility) {
		if($age_visibility == 0 || $age_visibility == 1) {
			$user_id = $this->session->userdata('id');
			$this->db->where('user_id', $user_id);
			$query = $this->db->update('user_settings', array('show_age' => $age_visibility));
			if($query) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function update_user_preferences($default_watchlist_page) {
		if($default_watchlist_page >= 0 && $default_watchlist_page <= 5) {
		$user_id = $this->session->userdata('id');
			$this->db->where('user_id', $user_id);
			$query = $this->db->update('user_settings', array('default_watchlist_page' => $default_watchlist_page));
			if($query) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function update_user_password($id, $password) {
		$this->db->where('id', $id);
		$query = $this->db->update('users', array('password' => $password));
		return $query;
	}
	
	function get_user_info_logged($username) {
		$this->db->select('u.*,us.show_age,us.default_watchlist_page,us.default_watchlist_sort,us.show_last_online');
		$this->db->join('user_settings as us', 'us.user_id=u.id');
		$this->db->where('username', $username);
		$query = $this->db->get('users as u');
		
		if ($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_user_info($username, $id = 0) {
		
		$this->db->select('u.id,u.username,u.joined_on,u.country,u.profile_image,u.cover_image,u.top_offset,u.gender,u.bio,u.last_online,u.birthdate,
				us.show_age,us.default_watchlist_sort,us.default_watchlist_page,us.show_last_online');
		$this->db->join('user_settings as us', 'us.user_id=u.id');
		
		if($id == 0) {		
			$this->db->where('username', $username);	
		} else {
			$this->db->where('id', $id);
		}
		
		$query = $this->db->get('users as u');
		 		
		if ($query->num_rows() == 1) {
			
			$result_array = $query->row_array();
			
			if($this->session->userdata('is_logged_in')) {
				$query = $this->db->get_where('followers', array('follower_id' => $this->session->userdata('id'), 'following_id' => $result_array['id']));				
				if($query->num_rows() == 1) {
					$result_array['follow_status'] = 1;
				} else {
					$result_array['follow_status'] = 0;
				}
			}
			
			return $result_array;
		} else {
			return FALSE;
		}
	}
	
	function get_id_by_email($email) {
		$this->db->select('id');
		$this->db->where('email', $email);
		$query = $this->db->get('users');

		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_user_cover_image() {
		$user_id = $this->session->userdata('id');
		
		$this->db->select('cover_image');
		$this->db->where('id', $user_id);
		$query = $this->db->get('users');

		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_user_avatar_image() {
		$user_id = $this->session->userdata('id');
		
		$this->db->select('profile_image');
		$this->db->where('id', $user_id);
		$query = $this->db->get('users');

		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function get_users_json_data() {
		$this->db->select('username, profile_image');
		$query = $this->db->get('users');
		return $query->result_array();
	}
	
	function check_if_username_exists($username) {
		$username = addslashes($username);
		
		$query = $this->db->get_where('users', array('username' => $username));

		if($query->num_rows() > 0) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function check_if_email_exists($email) {
		$email = addslashes($email);

		$query = $this->db->get_where('users', array('email' => $email));
	
		if($query->num_rows() > 0) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function check_if_user_is_admin($id) {
		
		$this->db->select('u.id');
		$this->db->join('admins as a', 'a.user_id=u.id');
		$this->db->where('u.id', $id);
		$query = $this->db->get('users as u');

		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function check_if_user_connected_to_fb($user_id) {
		
		$this->db->select('u.id');
		$this->db->join('facebook_accounts as fa', 'fa.user_id=u.id');
		$this->db->where('u.id', $user_id);
		$query = $this->db->get('users as u');
	
		if($query->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	
	
	function check_if_fb_acc_exist_and_return_user($fb_user_id) {
		
		$this->db->select('u.id,u.username,u.email,u.profile_image');
		$this->db->join('facebook_accounts as fa', 'fa.user_id=u.id');
		$this->db->where('fa.fb_user_id', $fb_user_id);
		$query = $this->db->get('users as u');

		if($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return FALSE;
		}
	}
	
	function temp_reset_password($user_id, $temp_pass){
		$this->db->insert('user_temp_passes', array('user_id' => $user_id, 'temp_pass' => $temp_pass));
	}
	
	function is_temp_pass_valid($temp_pass){
		
		$this->db->select('user_id, temp_pass');
		$this->db->where('temp_pass', $temp_pass);
		$query = $this->db->get('user_temp_passes');
		
		if($query->num_rows() == 1){
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}
	
	function delete_temp_pass($user_id) {
		$this->db->delete('user_temp_passes', array('user_id' => $user_id));
	}
}

?>