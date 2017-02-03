<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserUpdates extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
		$this->load->library('form_validation');
		$this->load->model('users_model');
	}
	
	function update_user_pictures() {
		
		$this->load->library('upload');
		
		if($this->session->userdata('is_logged_in')) {
		
			$unique_id = uniqid();
			
		    $config['upload_path']          = './assets/user_cover_images/';
	        $config['allowed_types']        = 'gif|jpg|png';
	        $config['max_size']             = 2048;
	        $config['file_name'] = $this->session->userdata['id'] . "_" . $unique_id . ".jpg";
	        $config['overwrite'] = TRUE;
	        
	        $this->upload->initialize($config);
	
	        $offset = $this->input->post('top_offset');
	        
	        if($offset != null) {
	       		$this->users_model->update_cover_offset($offset);
	        }
	        
	        if (!$this->upload->do_upload('edit_cover')) {
	        	$error = array('error' => $this->upload->display_errors('<p class="error">(Cover) ', '</p>'));
	        	$this->session->set_flashdata('error', $error['error']);
	        } else {
	        	$cover_image = $this->users_model->get_user_cover_image()['cover_image'];
	        	unlink("./assets/user_cover_images/{$cover_image}");
	        	$query = $this->users_model->update_cover_image($config['file_name']);
	        	if($query === FALSE) {
	        		$this->helpers_model->server_error();
	        	}
	         }             
	         
	         $config['upload_path']          = './assets/user_profile_images/';
	         $config['allowed_types']        = 'gif|jpg|png';
	         $config['max_size']             = 2048;
	         $config['file_name'] = $this->session->userdata['id'] . "_" . $unique_id . ".jpg";
	         $config['overwrite'] = TRUE;
	         
	         $this->upload->initialize($config);     
	         
	         if (!$this->upload->do_upload('edit_avatar')) {
	         	$error = array('error_a' => $this->upload->display_errors('<p class="error_a">(Avatar) ', '</p>'));
	         	$this->session->set_flashdata('error_a', $error['error_a']);
	         } else {
	         	$avatar_image = $this->users_model->get_user_avatar_image()['profile_image'];
	         	unlink("./assets/user_profile_images/{$avatar_image}");
	         	$query = $this->users_model->update_avatar_image($config['file_name']);
	         	if($query === FALSE) {
	         		$this->helpers_model->server_error();
	         	} else {
	         		$this->session->set_userdata('user_avatar', $config['file_name']);
	         		$this->write_users_json(VERIFICATION_TOKEN);
	         	}
	         }         
	         
	         redirect("users/profile/{$this->session->userdata['username']}");
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function update_user_info() {	
		
		if($this->session->userdata('is_logged_in')) {
		
			$bio = $_POST['textAreaValue']; 
			$birthdate = $_POST['birthValue'];
			$gender = $_POST['gender'];
			$location = $_POST['location'];
			
			if($bio == NULL) {		
				$bio = "";
			}
			
			if($birthdate == NULL) {
				$birthdate = "";
			}
			
			if($gender == NULL) {
				$gender = "unknown";
			}
			
			if($location == NULL) {
				$location = "";
			}
			
			$this->users_model->update_user_info($this->session->userdata['id'], $bio, $birthdate, $gender, $location);
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function update_user_privacy_notifications() {	
		$age_visibility = $this->input->post('age_visibility');
		
		if($age_visibility != null && $this->session->userdata('is_logged_in')) {	
			$this->users_model->update_user_privacy_notifications($age_visibility);	
			
			redirect("users/profile/{$this->session->userdata['username']}");
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function update_user_preferences() {		
		$default_watchlist_page = $this->input->post('default_watchlist_page');

	 	if($default_watchlist_page != null && $this->session->userdata('is_logged_in')) {
			$this->users_model->update_user_preferences($default_watchlist_page);
			
			redirect("users/profile/{$this->session->userdata['username']}");
		} else {
			$this->helpers_model->bad_request();
		} 
	}
	
	public function update_user_account_info() {				
		$data = array();
		
		$username = $this->session->userdata('username');
		$email = $this->session->userdata('email');
		$password = "";
		
		if($this->session->userdata('is_logged_in')) {
			
			if($this->input->post('username') != $username) {
				$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]|callback_check_if_username_exists|alpha_dash');
				$data['username'] = $this->input->post('username');
			}
			
			if($this->input->post('email') != $email) {
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_if_email_exists');
				$data['email'] = $this->input->post('email');
			}
			
			if($this->input->post('password') != "") {
				$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
				$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required|matches[password]');
				$data['password'] = hash('sha256', $this->input->post('password'));
			}		
			
			if($this->form_validation->run() == FALSE) {
				$this->user_settings();
			} else {					
				if(count($data) > 0) {					
					$result = $this->users_model->update_user_acc_info($data);
				
					if($result !== FALSE) {
						
						if(isset($data['username']))
							$this->write_users_json(VERIFICATION_TOKEN);
						
						$data = array(
								'id' => $result['id'],
								'username' => $result['username'],
								'email' => $result['email'],
								'is_logged_in' => true
						);
						
						$is_admin = $this->users_model->check_if_user_is_admin($result['id']);
							
						if($is_admin !== FALSE) {
							$data['admin'] = TRUE;
						}
							
						$this->session->set_userdata($data);
						$message = "Successfully updated your account !";
						$this->session->set_flashdata('message', $message);
					} else {
						$message = "There was an error updating while updating your account !";
						$this->session->set_flashdata('message', $message);
					}
				}
				
				$this->nocache();
				$this->user_settings();
			}
		} else {
			$this->helpers_model->bad_request();
		}
		
	}
	
	public function user_settings() {
		if($this->session->userdata('is_logged_in')) {
			
			$user = $this->users_model->get_user_info_logged($this->session->userdata['username']);
			$is_facebook_connected = $this->users_model->check_if_user_connected_to_fb($this->session->userdata['id']);
			
			if($user !== FALSE) {
				
				if($is_facebook_connected) {
					$data['is_fb_connected'] = "Disconnect Facebook";
				} else {
					$data['is_fb_connected'] = "Connect Facebook";
				}
				
				$data['user'] = $user;
				
				if($this->session->flashdata('message')) {
					$data['message'] = $this->session->flashdata('message');
				} else {
					$data['message'] = "";
				}
				
				$data['title'] = 'Settings';
				$data['css'] = 'user_settings.css';
				$data['header'] = "Settings";
				$this->load->view('user_account_settings', $data);
			} else {
				echo "There was an internal error";
			}
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function reset_password($temp_pass){
		$query = $this->users_model->is_temp_pass_valid($temp_pass);
		if($query !== FALSE){
			$data['user_id'] = $query['user_id'];
			$data['title'] = 'V-Anime';
			$data['css'] = 'login.css';
			$data['header'] = 'Reset your password';
			$this->load->view('reset_password_page', $data);
	
		} else{
			$data['header'] = "Link is invalid or has expired";
			$this->login_page($data);
		}
	}
	
	public function update_forgotten_password($user_id=NULL) {
		if($user_id != NULL and is_numeric($user_id)) {
			
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
			$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required|matches[password]');
		
			if($this->form_validation->run() == FALSE) {
				$data['user_id'] = $user_id;
				$data['title'] = 'V-Anime';
				$data['css'] = 'login.css';
				$data['header'] = 'Reset your password';
				$this->load->view('reset_password_page', $data);
			} else {
				$password = hash('sha256', $this->input->post('password'));
				$query = $this->users_model->update_user_password($user_id, $password);
				if($query !== FALSE) {
					$this->users_model->delete_temp_pass($user_id);
					$data['header'] = "You successfully changed your password";
					$this->login_page($data);
				}
			}
		} else {
			$this->helpers_model->page_not_found();
		}
	}
	
	function facebook_connect() {		
		if($this->session->userdata('is_logged_in')) {
		
			$query = $this->users_model->check_if_user_connected_to_fb($this->session->userdata['id']);
		
			if($query !== FALSE) {
					
				$query = $this->users_model->disconnect_facebook($this->session->userdata['id']);
					
				if($query !== FALSE) {
					redirect("userUpdates/user_settings");
				} else {
					$error = "Please add a password to your account before disconnecting Facebook";
					redirect("userUpdates/user_settings/$error");
				}
					
			} else {
				redirect("login/facebook_login/connect");
			}
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function check_if_username_exists($requested_username) {		
		if(strtolower($this->session->userdata('username')) == strtolower($requested_username)) {
			return TRUE;
		}
	
		$username_available = $this->users_model->check_if_username_exists($requested_username);
	
		if($username_available !== FALSE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	
	public function check_if_email_exists($requested_email) {	
		$email_available = $this->users_model->check_if_email_exists($requested_email);
	
		if($email_available !== FALSE) {
			return TRUE;
		} else {
			return FALSE;
		}
	
	}
	
	function write_users_json($verification_token=null) {
		if($verification_token === VERIFICATION_TOKEN) {
	
			$result_array = $this->users_model->get_users_json_data();
	
			if($result_array !== FALSE) {
	
				$all_names = "";
					
				foreach ($result_array as $user) {
						
					$name = $user['username'];
					$image = $user['profile_image'];

					$result[] = array('name'=> $name, 'image'=> $image);
				}
	
				$fp = fopen('assets/json/autocomplete_users.json', 'w');
				flock($fp, LOCK_EX);
				fwrite($fp, json_encode($result));
				flock($fp, LOCK_UN);
				fclose($fp);
			}
		} else {
			$this->helpers_model->unauthorized();
		}
	}
	
	public function login_page($data) {
		$data['title'] = 'Login';
		$data['css'] = 'login.css';
		$this->load->view('login_page', $data);
	}
	
	function nocache() {
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}	
}
	
?>