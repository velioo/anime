<?php
class UserUpdates extends CI_Controller {
	
	function update_user_pictures() {
		
		$this->load->model('users_model');
		$this->load->library('upload');
		
		$unique_id = uniqid();
		
	    $config['upload_path']          = './assets/user_cover_images/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 2048;
        $config['file_name'] = $this->session->userdata['id'] . "_" . $unique_id . ".jpg";
        $config['overwrite'] = TRUE;
        
        $this->upload->initialize($config);

        $offset = $this->input->post('top_offset');
        
        if($offset != null) {
       		$this->users_model->update_cover_offset($this->session->userdata['id'], $offset);
        }
        
        if (!$this->upload->do_upload('edit_cover')) {
        	$error = array('error' => $this->upload->display_errors('<p class="error">(Cover) ', '</p>'));
        	$this->session->set_flashdata('error', $error['error']);
        } else {
        	$query = $this->users_model->update_cover_image($this->session->userdata['id'],  $config['file_name']);
        	if(!$query) {
        		$this->server_error();
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
         	$query = $this->users_model->update_avatar_image($this->session->userdata['id'],  $config['file_name']);
         	if(!$query) {
         		$this->server_error();
         	}
         }         
         
         redirect("users/profile/{$this->session->userdata['username']}");
	}
	
	public function update_user_info() {
		$this->load->model('users_model');		
		$bio = $_POST['textAreaValue']; 
		$birthdate = $_POST['birthValue'];
		$gender = $_POST['gender'];
		$location = $_POST['location'];
		
		if($bio == null) {		
			$bio = "";
		}
		
		if($birthdate == null) {
			$birthdate = "";
		}
		
		if($gender == null) {
			$gender = "unknown";
		}
		
		if($location == null) {
			$location = "";
		}
		
		$this->users_model->update_user_info($this->session->userdata['id'], $bio, $birthdate, $gender, $location);

	}
	
	public function update_user_privacy_notifications() {
		$this->load->model('users_model');		
		$age_visibility = $this->input->post('age_visibility');
		
		if($age_visibility != null) {	
			$this->users_model->update_user_privacy_notifications($age_visibility);	
			
			redirect("users/profile/{$this->session->userdata['username']}");
		} else {
			$this->bad_request();
		}
	}
	
	public function update_user_preferences() {
		$this->load->model('users_model');
		
		$default_watchlist_page = $this->input->post('default_watchlist_page');
		echo $default_watchlist_page;
	 	if($default_watchlist_page != null) {
			$this->users_model->update_user_preferences($default_watchlist_page);
			
			redirect("users/profile/{$this->session->userdata['username']}");
		} else {
			$this->bad_request();
		} 
	}
	
	public function update_user_account_info() {		
		$this->load->library('form_validation');
		
		$username = $this->session->userdata('username');
		$email = $this->session->userdata('email');
		$password = "";
		if($username != null && $email != null) {
			
			if($this->input->post('username') != $username) {
				$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]|callback_check_if_username_exists|alpha_dash');
				$username = $this->input->post('username');
			}
			
			if($this->input->post('email') != $email) {
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_if_email_exists');
				$email = $this->input->post('email');
			}
			
			if($this->input->post('password') != "") {
				$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
				$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required|matches[password]');
				$password = $this->input->post('password');
			}		
			
			if($this->form_validation->run() == FALSE) {
				$this->user_settings();
			} else {
				$this->load->model('users_model');
					
				if(($username != $this->session->userdata['username']) or ($email != $this->session->userdata['email']) or $password != "") {
					if($password != "")
						$password = hash('sha256', $password);
						$result = $this->users_model->update_user_acc_info($username, $email, $password);
						
					if ($result) {
						$data = array(
								'id' => $result['id'],
								'username' => $result['username'],
								'email' => $result['email'],
								'is_logged_in' => true
						);
						
						$is_admin = $this->users_model->check_if_user_is_admin($result['id']);
							
						if($is_admin) {
							$data['admin'] = TRUE;
						}
							
						$this->session->set_userdata($data);
					}
				} 
	
				$this->nocache();
				redirect("users/profile/{$this->session->userdata['username']}");
			}
		} else {
			$this->bad_request();
		}
		
	}
	
	public function user_settings($fb_message = "") {
		$this->load->model('users_model');
		$user = $this->users_model->get_user_info_logged($this->session->userdata['username']);
		$is_facebook_connected = $this->users_model->check_if_user_connected_to_fb($this->session->userdata['id']);
		if($user) {
			if($is_facebook_connected) {
				$data['is_fb_connected'] = "Disconnect Facebook";
			} else {
				$data['is_fb_connected'] = "Connect Facebook";
			}
			$data['user'] = $user;
			$data['fb_message'] = $fb_message;
			$data['title'] = 'Settings';
			$data['css'] = 'user_settings.css';
			$data['header'] = "Settings";
			$this->load->view('user_account_settings', $data);
		} else {
			echo "There was an internal error";
		}

	}
	
	public function reset_password($temp_pass){
		$this->load->model('users_model');
		$query = $this->users_model->is_temp_pass_valid($temp_pass);
		if($query){
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
	
	public function update_forgotten_password($user_id=null) {
		$this->load->library('form_validation');
		$this->load->model('users_model');
	
		if($user_id != null and is_numeric($user_id)) {
			
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
				if($query) {
					$this->users_model->delete_temp_pass($user_id);
					$data['header'] = "You successfully changed your password";
					$this->login_page($data);
				}
			}
		} else {
			$this->page_not_found();
		}
	}
	
	function facebook_connect() {
		$this->load->model('users_model');
	
		$query = $this->users_model->check_if_user_connected_to_fb($this->session->userdata['id']);
	
		if($query) {
				
			$query = $this->users_model->disconnect_facebook($this->session->userdata['id']);
				
			if($query) {
				redirect("userUpdates/user_settings");
			} else {
				$error = "Please add a password to your account before disconnecting Facebook";
				redirect("userUpdates/user_settings/$error");
			}
				
		} else {
			redirect("login/facebook_login/connect");
		}
	}
	
	public function check_if_username_exists($requested_username) {
		$this->load->model('users_model');
		
		if(strtolower($this->session->userdata('username')) == strtolower($requested_username)) {
			return TRUE;
		}
	
		$username_available = $this->users_model->check_if_username_exists($requested_username);
	
		if($username_available) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	
	public function check_if_email_exists($requested_email) {
		$this->load->model('users_model');
	
		$email_available = $this->users_model->check_if_email_exists($requested_email);
	
		if($email_available) {
			return TRUE;
		} else {
			return FALSE;
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
	
	function page_not_found() {
		header('HTTP/1.0 404 Not Found');
		echo "<h1>Error 404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		exit();
	}
	
	function bad_request() {
		header('HTTP/1.1 400 Bad Request');
		echo "<h1>Error 400 Bad request</h1>";
		echo "The requested action cannot be executed.";
		exit();
	}
	
	function server_error() {
		header('HTTP/1.1 500 Internal Server Error');
		echo "<h1>Error 500 Internal Server Error</h1>";
		echo "There was a problem with the server";
		exit();
	}
	
}
	
?>